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

        $this->readyGroups = [];
        $this->randomItems = [];
        $this->pairedSingles = [];
        $this->leftoverSingle = null;
        
        $grouped = collect($allImeis)->groupBy(fn ($imei) => substr($imei, 0, 8));

        foreach ($grouped as $tac => $imeis) {
            $chunked = $imeis->chunk(2);
            foreach ($chunked as $chunk) {
                if ($chunk->count() === 2) {
                    $this->readyGroups[$tac][] = [
                        'imei1' => $chunk->first(), 
                        'imei2' => $chunk->last()
                    ];
                } else {
                    $this->randomItems[] = $chunk->first();
                }
            }
        }

        if (!empty($this->randomItems)) {
            $singleCollection = collect($this->randomItems)->values();
            if ($singleCollection->count() % 2 !== 0) {
                $this->leftoverSingle = $singleCollection->pop();
            }
            if ($singleCollection->isNotEmpty()) {
                $this->pairedSingles = $singleCollection->chunk(2)
                    ->map(fn ($c) => $c->values()->toArray())
                    ->toArray();
            }
        }
        $this->viewMode = 'select';
    }

    public function setView($mode) { $this->viewMode = $mode; }

    public function checkIcloud($imei)
    {
        if (!$imei) return;
        if (isset($this->icloudStatus[$imei]) && in_array($this->icloudStatus[$imei]['status'], ['ON', 'OFF'])) return;
        
        $this->icloudStatus[$imei] = ['status' => 'Mengecek...', 'color' => 'orange', 'icon' => 'mdi-loading mdi-spin'];

        try {
            $response = Http::asForm()->timeout(20)->post($this->apiUrl, [
                'service' => $this->serviceId, 
                'imei' => $imei, 
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $res = $response->json();
                $isON = false;
                if (isset($res['object']['fmiOn'])) { 
                    $isON = (bool)$res['object']['fmiOn']; 
                } else {
                    $raw = strtoupper($res['response'] ?? '');
                    $isON = str_contains($raw, 'ON') && !str_contains($raw, 'OFF');
                }
                
                $this->icloudStatus[$imei] = [
                    'status' => $isON ? 'ON' : 'OFF',
                    'color' => $isON ? 'green' : 'red',
                    'icon' => $isON ? 'mdi-lock' : 'mdi-lock-open',
                ];
            }
        } catch (\Exception $e) { 
            $this->icloudStatus[$imei] = ['status' => 'ERROR', 'color' => 'gray', 'icon' => 'mdi-alert']; 
        }
    }

    public function checkAllIcloud()
    {
        foreach ($this->readyGroups as $tac => $pairs) {
            foreach ($pairs as $item) { 
                $this->checkIcloud($item['imei1']); 
                $this->checkIcloud($item['imei2']); 
            }
        }
        foreach ($this->pairedSingles as $pair) { 
            $this->checkIcloud($pair[0]); 
            $this->checkIcloud($pair[1]); 
        }
        if ($this->leftoverSingle) { 
            $this->checkIcloud($this->leftoverSingle); 
        }
    }

    public function openCard($imei1, $imei2 = null)
    {
        $this->lastTheme = ($this->lastTheme == 'light') ? 'dark' : 'light';
        $this->selectedItem = [
            'imei1' => $imei1,
            'imei2' => $imei2 ?: $imei1,
            'eid' => '8904' . mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999),
            'meid' => substr($imei1, 0, 14),
            'hour' => now()->format('H'),
            'minute' => now()->format('i'),
            'batteryLevel' => rand(45, 95),
            'theme' => $this->lastTheme,
            'deviceModel' => $this->selectedCardType,
            'useWifi' => (bool)rand(0, 1)
        ];
        $this->showModal = true;
        $this->dispatchBrowserEvent('modalOpened');
    }

    public function closeModal() { $this->showModal = false; $this->selectedItem = null; }
    public function resetForm() { $this->reset(); }
    public function render() { return view('livewire.imei-generator'); }
}