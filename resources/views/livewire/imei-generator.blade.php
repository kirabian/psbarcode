<div class="p-6 bg-zinc-50 min-h-screen font-sans">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <div class="max-w-7xl mx-auto">
        @if (!$viewMode)
            <div class="bg-white p-8 rounded-3xl shadow-lg border border-zinc-200 mb-6">
                <h2 class="text-2xl font-bold mb-6 text-zinc-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                        <span class="mdi mdi-text-box-multiple text-white text-xl"></span>
                    </div>
                    Input Data IMEI
                </h2>
                <textarea wire:model.defer="inputText" rows="10"
                    class="w-full p-4 border border-zinc-200 rounded-2xl font-mono text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-zinc-50 focus:bg-white transition-all"
                    placeholder="Paste IMEI list here (one per line or space separated)..."></textarea>
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-zinc-500 font-medium">Format: 15 digit numbers</div>
                    <button wire:click="organize"
                        class="px-6 py-3 bg-emerald-500 text-white rounded-xl font-semibold shadow-sm hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-2">
                        <span class="mdi mdi-play"></span> Process Data
                    </button>
                </div>
            </div>
        @elseif($viewMode == 'select')
            <div class="max-w-4xl mx-auto py-12 text-center">
                <h2 class="text-3xl font-bold text-zinc-900 mb-10">Select Operation Mode</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <button wire:click="setView('card')"
                        class="group p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-emerald-500 transition-all hover:shadow-xl">
                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500 transition-colors">
                            <span class="mdi mdi-qrcode-scan text-3xl text-emerald-600 group-hover:text-white transition-colors"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">Generate Barcode</h3>
                        <p class="text-sm text-zinc-500 mt-2">Create barcode cards for IMEIs</p>
                    </button>
                    <button wire:click="setView('checker')"
                        class="group p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-blue-500 transition-all hover:shadow-xl">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition-colors">
                            <span class="mdi mdi-shield-check text-3xl text-blue-600 group-hover:text-white transition-colors"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">iCloud Checker</h3>
                        <p class="text-sm text-zinc-500 mt-2">Verify iCloud lock status</p>
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-zinc-200 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <button wire:click="setView('select')" 
                            class="px-4 py-2 bg-zinc-100 text-zinc-700 rounded-xl font-semibold hover:bg-zinc-200 transition-all text-sm flex items-center gap-2">
                            <span class="mdi mdi-arrow-left"></span> Back
                        </button>
                        <h2 class="text-xl font-bold text-zinc-900">IMEI List: {{ ucfirst($viewMode) }}</h2>
                    </div>

                    <div class="flex items-center gap-2">
                        <button wire:click="checkAllIcloud" wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-orange-500 text-white rounded-xl font-semibold text-sm hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-all active:scale-95">
                            <span wire:loading wire:target="checkAllIcloud" class="mdi mdi-loading mdi-spin"></span>
                            <span wire:loading.remove wire:target="checkAllIcloud" class="mdi mdi-refresh"></span>
                            Check All
                        </button>

                        @if($viewMode == 'card')
                        <select wire:model="selectedCardType" 
                            class="px-4 py-2.5 rounded-xl border border-zinc-200 font-semibold text-sm outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="iphone">iPhone Standard</option>
                            <option value="iphone14">iPhone 14</option>
                        </select>
                        @endif

                        <button wire:click="resetForm" 
                            class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-semibold hover:bg-red-100 transition-all text-sm">
                            Reset
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-xs text-zinc-500 font-semibold mb-1">Total IMEIs</p>
                        @php 
                            $countReady = collect($readyGroups)->flatten(1)->count() * 2;
                            $countPaired = count($pairedSingles) * 2;
                            $countLeftover = $leftoverSingle ? 1 : 0;
                            $totalImeis = $countReady + $countPaired + $countLeftover;
                        @endphp
                        <p class="text-3xl font-bold text-zinc-900">{{ $totalImeis }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-xs text-emerald-600 font-semibold mb-1">iCloud ON</p>
                        <p class="text-3xl font-bold text-emerald-600">{{ collect($icloudStatus)->where('status', 'ON')->count() }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-xs text-red-600 font-semibold mb-1">iCloud OFF</p>
                        <p class="text-3xl font-bold text-red-600">{{ collect($icloudStatus)->where('status', 'OFF')->count() }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-xs text-zinc-400 font-semibold mb-1">Pending</p>
                        <p class="text-3xl font-bold text-zinc-400">{{ $totalImeis - count($icloudStatus) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if (!empty($readyGroups))
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 px-2">
                                <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                                <h3 class="font-bold text-blue-600 text-sm">Paired (Same TAC)</h3>
                            </div>
                            @foreach ($readyGroups as $tac => $pairs)
                                @foreach ($pairs as $item)
                                    <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-l-blue-500 border border-zinc-200">
                                        <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-xs">
                                            <div>
                                                <p class="text-blue-600 font-semibold mb-1">IMEI 1</p>
                                                <p class="font-bold text-zinc-900">{{ $item['imei1'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-zinc-400 font-semibold mb-1">IMEI 2</p>
                                                <p class="font-bold text-zinc-900">{{ $item['imei2'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="checkIcloud('{{ $item['imei1'] }}')" wire:loading.attr="disabled" 
                                                class="flex-1 py-3 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                                {{ isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                                   (isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                                <span wire:loading wire:target="checkIcloud('{{ $item['imei1'] }}')" class="mdi mdi-loading mdi-spin"></span>
                                                <span>{{ $icloudStatus[$item['imei1']]['status'] ?? 'Check #1' }}</span>
                                            </button>
                                            <button wire:click="checkIcloud('{{ $item['imei2'] }}')" wire:loading.attr="disabled"
                                                class="flex-1 py-3 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                                {{ isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                                   (isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                                <span wire:loading wire:target="checkIcloud('{{ $item['imei2'] }}')" class="mdi mdi-loading mdi-spin"></span>
                                                <span>{{ $icloudStatus[$item['imei2']]['status'] ?? 'Check #2' }}</span>
                                            </button>
                                            @if ($viewMode == 'card')
                                                <button wire:click="openCard('{{ $item['imei1'] }}', '{{ $item['imei2'] }}')" 
                                                    class="px-5 py-3 bg-blue-500 text-white rounded-xl font-semibold text-xs hover:bg-blue-600 shadow-sm">
                                                    Card
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-4">
                        @if (!empty($pairedSingles))
                            <div class="flex items-center gap-2 px-2">
                                <div class="w-1 h-6 bg-orange-500 rounded-full"></div>
                                <h3 class="font-bold text-orange-600 text-sm">Singles (Merged)</h3>
                            </div>
                            @foreach ($pairedSingles as $pair)
                                <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-l-orange-500 border border-zinc-200">
                                    <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-xs">
                                        <div>
                                            <p class="text-orange-600 font-semibold mb-1">IMEI A</p>
                                            <p class="font-bold text-zinc-900">{{ $pair[0] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-orange-600 font-semibold mb-1">IMEI B</p>
                                            <p class="font-bold text-zinc-900">{{ $pair[1] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="checkIcloud('{{ $pair[0] }}')" wire:loading.attr="disabled"
                                            class="flex-1 py-3 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                            {{ isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                               (isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                            <span wire:loading wire:target="checkIcloud('{{ $pair[0] }}')" class="mdi mdi-loading mdi-spin"></span>
                                            <span>{{ $icloudStatus[$pair[0]]['status'] ?? 'Check A' }}</span>
                                        </button>
                                        <button wire:click="checkIcloud('{{ $pair[1] }}')" wire:loading.attr="disabled"
                                            class="flex-1 py-3 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                            {{ isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                               (isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                            <span wire:loading wire:target="checkIcloud('{{ $pair[1] }}')" class="mdi mdi-loading mdi-spin"></span>
                                            <span>{{ $icloudStatus[$pair[1]]['status'] ?? 'Check B' }}</span>
                                        </button>
                                        @if ($viewMode == 'card')
                                            <button wire:click="openCard('{{ $pair[0] }}', '{{ $pair[1] }}')" 
                                                class="px-5 py-3 bg-orange-500 text-white rounded-xl font-semibold text-xs hover:bg-orange-600 shadow-sm">
                                                Card
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($leftoverSingle)
                            <div class="flex items-center gap-2 px-2 mt-6">
                                <div class="w-1 h-6 bg-red-500 rounded-full"></div>
                                <h3 class="font-bold text-red-600 text-sm">Leftover (Odd)</h3>
                            </div>
                            <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-l-red-500 border border-zinc-200">
                                <div class="mb-4 font-mono text-xs">
                                    <p class="text-red-600 font-semibold mb-1">IMEI</p>
                                    <p class="font-bold text-zinc-900">{{ $leftoverSingle }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="checkIcloud('{{ $leftoverSingle }}')" wire:loading.attr="disabled"
                                        class="flex-1 py-3 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                        {{ isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                           (isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                        <span wire:loading wire:target="checkIcloud('{{ $leftoverSingle }}')" class="mdi mdi-loading mdi-spin"></span>
                                        <span>{{ $icloudStatus[$leftoverSingle]['status'] ?? 'Check IMEI' }}</span>
                                    </button>
                                    @if ($viewMode == 'card')
                                        <button wire:click="openCard('{{ $leftoverSingle }}', null)" 
                                            class="px-5 py-3 bg-red-500 text-white rounded-xl font-semibold text-xs hover:bg-red-600 shadow-sm">
                                            Card
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if ($showModal && $selectedItem)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="relative w-full max-w-sm my-auto">
                <div class="flex justify-between mb-4 px-2">
                    <button onclick="downloadCard()" 
                        class="px-6 py-3 bg-emerald-500 text-white rounded-2xl font-semibold text-sm shadow-lg hover:bg-emerald-600 transition-all active:scale-95">
                        Download JPG
                    </button>
                    <button wire:click="closeModal" 
                        class="px-6 py-3 bg-white/20 text-white rounded-2xl font-semibold text-sm hover:bg-white/30 transition-all">
                        Close
                    </button>
                </div>
                <div id="capture-area" class="rounded-[50px] overflow-hidden shadow-2xl flex justify-center bg-black">
                    @if($selectedItem['deviceModel'] == 'iphone14')
                        @include('livewire.partials.iphone-14-card', ['item' => $selectedItem, 'id' => 'ios-card-14'])
                    @else
                        @include('livewire.partials.iphone-card', ['item' => $selectedItem, 'id' => 'ios-card-std'])
                    @endif
                </div>
            </div>
        </div>
    @endif

    <script>
        window.addEventListener('modalOpened', event => {
            setTimeout(() => { if(window.JsBarcode) JsBarcode(".barcode-svg").init(); }, 300);
        });
        function downloadCard() {
            const area = document.getElementById('capture-area');
            html2canvas(area, { scale: 3, useCORS: true, backgroundColor: '#000' }).then(canvas => {
                const link = document.createElement('a');
                link.download = `IMEI-CARD-${Date.now()}.jpg`;
                link.href = canvas.toDataURL('image/jpeg', 0.9);
                link.click();
            });
        }
    </script>
</div>