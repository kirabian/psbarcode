<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ImeiGenerator extends Component
{
    public $inputText = '';
    public $readyGroups = [];
    public $pairedSingles = [];
    public $leftoverSingle = null;
    public $showModal = false;
    public $selectedItem = null;
    public $icloudStatus = [];
    public $viewMode = null;
    public $selectedCardType = 'iphone14';
    public $lastTheme = 'light';

    protected $apiUrl = 'https://api.ifreeicloud.co.uk';
    protected $apiKey = 'XF4-5K4-5T0-PLH-6BC-V3K-TU3-PCM';
    protected $serviceId = 4;

    public function organize()
    {
        preg_match_all('/\b\d{15}\b/', $this->inputText, $matches);
        $allImeis = array_unique($matches[0]);
        if (empty($allImeis))
            return;

        $this->reset(['readyGroups', 'pairedSingles', 'leftoverSingle']);
        $randomItems = [];

        // Logic Pengelompokan (Refactored)
        collect($allImeis)->groupBy(fn($i) => substr($i, 0, 8))->each(function ($group, $tac) use (&$randomItems) {
            $group->chunk(2)->each(function ($chunk) use ($tac, &$randomItems) {
                if ($chunk->count() === 2) {
                    $this->readyGroups[$tac][] = ['imei1' => $chunk->first(), 'imei2' => $chunk->last()];
                } else {
                    $randomItems[] = $chunk->first();
                }
            });
        });

        // Handle Sisa/Random
        $sc = collect($randomItems)->values();
        if ($sc->count() % 2 !== 0)
            $this->leftoverSingle = $sc->pop();
        if ($sc->isNotEmpty())
            $this->pairedSingles = $sc->chunk(2)->map->values()->toArray();

        $this->viewMode = 'select';
    }

    public function setView($mode)
    {
        $this->viewMode = $mode;
    }

    public function checkIcloud($imei)
    {
        if (!$imei || (isset($this->icloudStatus[$imei]) && in_array($this->icloudStatus[$imei]['status'], ['ON', 'OFF'])))
            return;
        $this->icloudStatus[$imei] = ['status' => 'Checking...', 'color' => 'orange'];

        try {
            $res = Http::asForm()->timeout(20)->post($this->apiUrl, ['service' => $this->serviceId, 'imei' => $imei, 'key' => $this->apiKey])->json();
            $isON = isset($res['object']['fmiOn']) ? (bool) $res['object']['fmiOn'] : (str_contains(strtoupper($res['response'] ?? ''), 'ON') && !str_contains(strtoupper($res['response'] ?? ''), 'OFF'));
            $this->icloudStatus[$imei] = ['status' => $isON ? 'ON' : 'OFF'];
        } catch (\Exception $e) {
            $this->icloudStatus[$imei] = ['status' => 'ERR'];
        }
    }

    public function checkAllIcloud()
    {
        foreach ($this->readyGroups as $pairs)
            foreach ($pairs as $item) {
                $this->checkIcloud($item['imei1']);
                $this->checkIcloud($item['imei2']);
            }
        foreach ($this->pairedSingles as $pair) {
            $this->checkIcloud($pair[0]);
            $this->checkIcloud($pair[1]);
        }
        if ($this->leftoverSingle)
            $this->checkIcloud($this->leftoverSingle);
    }

    public function openCard($imei1, $imei2 = null)
    {
        $this->lastTheme = ($this->lastTheme == 'light') ? 'dark' : 'light';
        $this->selectedItem = $this->createItemData($imei1, $imei2, $this->lastTheme);
        $this->showModal = true;
        $this->dispatchBrowserEvent('modalOpened');
    }

    private function createItemData($imei1, $imei2 = null, $theme = 'light')
    {
        return [
            'imei1' => $imei1,
            'imei2' => $imei2 ?: $imei1,
            'meid' => substr($imei1, 0, 14),
            'eid' => '8904' . mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999),
            'hour' => str_pad(mt_rand(1, 23), 2, '0', STR_PAD_LEFT),
            'minute' => str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT),
            'batteryLevel' => rand(45, 95),
            'theme' => $theme,
            'deviceModel' => $this->selectedCardType
        ];
    }

    public function getDoubleDataForZip($skip = 0, $take = 50)
    {
        $view = ($this->selectedCardType == 'iphone14') ? 'livewire.partials.iphone-14-card' : 'livewire.partials.iphone-card';
        $data = [];

        // Flatten all pairs first to ensure consistent ordering
        $allPairs = [];
        foreach ($this->readyGroups as $pairs) {
            foreach ($pairs as $p) {
                $allPairs[] = $p;
            }
        }

        // Slice the array based on skip and take
        $chunkedPairs = array_slice($allPairs, $skip, $take);

        // Calculate the starting index for theme alternator (light/dark)
        // If skip is 0, start at 0. If skip is 50, start at 50 to maintain pattern
        $currentIndex = $skip;

        foreach ($chunkedPairs as $p) {
            $data[] = [
                'imei1' => $p['imei1'],
                'html' => view($view, [
                    'item' => $this->createItemData($p['imei1'], $p['imei2'], ($currentIndex++ % 2 == 0) ? 'light' : 'dark'),
                    'id' => 'zd-' . $p['imei1']
                ])->render()
            ];
        }
        return $data;
    }

    public function getTotalDoubleCount()
    {
        $count = 0;
        foreach ($this->readyGroups as $pairs) {
            $count += count($pairs);
        }
        return $count;
    }

    public function getSingleDataForZip($skip = 0, $take = 50)
    {
        $view = ($this->selectedCardType == 'iphone14') ? 'livewire.partials.iphone-14-card' : 'livewire.partials.iphone-card';
        $data = [];

        // Flatten logic is simpler for single array, but just use array_slice on the property directly if indexed correctly
        // However pairedSingles is array of arrays [imei1, imei2].

        $chunkedSingles = array_slice($this->pairedSingles, $skip, $take);
        $currentIndex = $skip;

        foreach ($chunkedSingles as $ps) {
            $data[] = [
                'imei1' => $ps[0],
                'html' => view($view, [
                    'item' => $this->createItemData($ps[0], $ps[1], ($currentIndex++ % 2 == 0) ? 'light' : 'dark'),
                    'id' => 'zs-' . $ps[0]
                ])->render()
            ];
        }
        return $data;
    }

    public function getTotalSingleCount()
    {
        return count($this->pairedSingles);
    }

    public function getAllImeisString()
    {
        $l = [];
        foreach ($this->readyGroups as $pairs)
            foreach ($pairs as $p) {
                $l[] = $p['imei1'];
                $l[] = $p['imei2'];
            }
        foreach ($this->pairedSingles as $ps) {
            $l[] = $ps[0];
            $l[] = $ps[1];
        }
        if ($this->leftoverSingle)
            $l[] = $this->leftoverSingle;
        return implode('\n', $l);
    }

    public function getDoubleImeisString()
    {
        $l = [];
        foreach ($this->readyGroups as $pairs)
            foreach ($pairs as $p) {
                $l[] = $p['imei1'];
                $l[] = $p['imei2'];
            }
        return implode('\n', $l);
    }

    public function getSingleImeisString()
    {
        $l = [];
        foreach ($this->pairedSingles as $ps) {
            $l[] = $ps[0];
            $l[] = $ps[1];
        }
        if ($this->leftoverSingle)
            $l[] = $this->leftoverSingle;
        return implode('\n', $l);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
    public function resetForm()
    {
        $this->reset();
    }
    public function render()
    {
        return view('livewire.imei-generator');
    }
}