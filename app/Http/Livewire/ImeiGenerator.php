<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ImeiGenerator extends Component
{
    public $inputText = '';
    public $readyGroups = [];
    public $randomItems = [];
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
        if (empty($allImeis)) return;

        $this->readyGroups = []; $this->randomItems = [];
        $this->pairedSingles = []; $this->leftoverSingle = null;
        
        $grouped = collect($allImeis)->groupBy(fn ($imei) => substr($imei, 0, 8));
        foreach ($grouped as $tac => $imeis) {
            $chunked = $imeis->chunk(2);
            foreach ($chunked as $chunk) {
                if ($chunk->count() === 2) {
                    $this->readyGroups[$tac][] = ['imei1' => $chunk->first(), 'imei2' => $chunk->last()];
                } else { $this->randomItems[] = $chunk->first(); }
            }
        }
        if (!empty($this->randomItems)) {
            $sc = collect($this->randomItems)->values();
            if ($sc->count() % 2 !== 0) { $this->leftoverSingle = $sc->pop(); }
            if ($sc->isNotEmpty()) { $this->pairedSingles = $sc->chunk(2)->map(fn ($c) => $c->values()->toArray())->toArray(); }
        }
        $this->viewMode = 'select';
    }

    public function setView($mode) { $this->viewMode = $mode; }

    public function checkIcloud($imei)
    {
        if (!$imei || (isset($this->icloudStatus[$imei]) && in_array($this->icloudStatus[$imei]['status'], ['ON', 'OFF']))) return;
        $this->icloudStatus[$imei] = ['status' => 'Checking...', 'color' => 'orange'];
        try {
            $response = Http::asForm()->timeout(20)->post($this->apiUrl, ['service' => $this->serviceId, 'imei' => $imei, 'key' => $this->apiKey]);
            if ($response->successful()) {
                $res = $response->json();
                $isON = isset($res['object']['fmiOn']) ? (bool)$res['object']['fmiOn'] : (str_contains(strtoupper($res['response'] ?? ''), 'ON') && !str_contains(strtoupper($res['response'] ?? ''), 'OFF'));
                $this->icloudStatus[$imei] = ['status' => $isON ? 'ON' : 'OFF'];
            }
        } catch (\Exception $e) { $this->icloudStatus[$imei] = ['status' => 'ERR']; }
    }

    public function checkAllIcloud()
    {
        foreach ($this->readyGroups as $pairs) foreach ($pairs as $item) { $this->checkIcloud($item['imei1']); $this->checkIcloud($item['imei2']); }
        foreach ($this->pairedSingles as $pair) { $this->checkIcloud($pair[0]); $this->checkIcloud($pair[1]); }
        if ($this->leftoverSingle) $this->checkIcloud($this->leftoverSingle);
    }

    public function openCard($imei1, $imei2 = null)
    {
        $this->lastTheme = ($this->lastTheme == 'light') ? 'dark' : 'light';
        $this->selectedItem = $this->createItemData($imei1, $imei2);
        $this->showModal = true;
        $this->dispatchBrowserEvent('modalOpened');
    }

    private function createItemData($imei1, $imei2 = null) {
        return [
            'imei1' => $imei1, 'imei2' => $imei2 ?: $imei1, 'meid' => substr($imei1, 0, 14),
            'eid' => '8904' . mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999),
            'hour' => now()->format('H'), 'minute' => now()->format('i'),
            'batteryLevel' => rand(45, 95), 'theme' => $this->lastTheme,
            'deviceModel' => $this->selectedCardType
        ];
    }

    public function getImeiDataForZip() {
        $data = [];
        $view = ($this->selectedCardType == 'iphone14') ? 'livewire.partials.iphone-14-card' : 'livewire.partials.iphone-card';
        
        foreach($this->readyGroups as $tac => $pairs) {
            foreach($pairs as $p) {
                $item = $this->createItemData($p['imei1'], $p['imei2']);
                $data[] = ['imei1' => $p['imei1'], 'html' => view($view, ['item' => $item, 'id' => 'z-'.$p['imei1']])->render()];
            }
        }
        foreach($this->pairedSingles as $ps) {
            $item = $this->createItemData($ps[0], $ps[1]);
            $data[] = ['imei1' => $ps[0], 'html' => view($view, ['item' => $item, 'id' => 'z-'.$ps[0]])->render()];
        }
        if($this->leftoverSingle) {
            $item = $this->createItemData($this->leftoverSingle, null);
            $data[] = ['imei1' => $this->leftoverSingle, 'html' => view($view, ['item' => $item, 'id' => 'z-'.$this->leftoverSingle])->render()];
        }
        return $data;
    }

    public function getAllImeisString() {
        $l = [];
        foreach($this->readyGroups as $pairs) foreach($pairs as $p) { $l[] = $p['imei1']; $l[] = $p['imei2']; }
        foreach($this->pairedSingles as $ps) { $l[] = $ps[0]; $l[] = $ps[1]; }
        if($this->leftoverSingle) $l[] = $this->leftoverSingle;
        return implode('\n', $l);
    }

    public function getDoubleImeisString() {
        $l = []; foreach($this->readyGroups as $pairs) foreach($pairs as $p) { $l[] = $p['imei1']; $l[] = $p['imei2']; }
        return implode('\n', $l);
    }

    public function getSingleImeisString() {
        $l = []; foreach($this->pairedSingles as $ps) { $l[] = $ps[0]; $l[] = $ps[1]; }
        if($this->leftoverSingle) $l[] = $this->leftoverSingle;
        return implode('\n', $l);
    }

    public function closeModal() { $this->showModal = false; }
    public function resetForm() { $this->reset(); }
    public function render() { return view('livewire.imei-generator'); }
}