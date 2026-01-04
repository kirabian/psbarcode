<div class="p-4 md:p-6 bg-zinc-50 min-h-screen font-sans">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <div class="max-w-7xl mx-auto">
        @if (!$viewMode)
            <div class="bg-white p-6 md:p-8 rounded-3xl shadow-lg border border-zinc-200 mb-6">
                <h2 class="text-2xl font-bold mb-6 text-zinc-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                        <span class="mdi mdi-text-box-multiple text-white text-xl"></span>
                    </div>
                    Input Data IMEI
                </h2>
                <textarea wire:model.defer="inputText" rows="10"
                    class="w-full p-4 border border-zinc-200 rounded-2xl font-mono text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-zinc-50 focus:bg-white transition-all outline-none"
                    placeholder="Paste IMEI list here..."></textarea>
                <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-zinc-500 font-medium order-2 md:order-1">Format: 15 digit numbers</div>
                    <button wire:click="organize"
                        class="w-full md:w-auto px-6 py-3 bg-emerald-500 text-white rounded-xl font-semibold shadow-sm hover:bg-emerald-600 transition-all active:scale-95 flex items-center justify-center gap-2 order-1 md:order-2">
                        <span class="mdi mdi-play"></span> Process Data
                    </button>
                </div>
            </div>
        @elseif($viewMode == 'select')
            <div class="max-w-4xl mx-auto py-12 text-center px-4">
                <h2 class="text-3xl font-bold text-zinc-900 mb-10">Select Operation Mode</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <button wire:click="setView('card')" class="group p-8 md:p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-emerald-500 transition-all">
                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500 transition-colors">
                            <span class="mdi mdi-qrcode-scan text-3xl text-emerald-600 group-hover:text-white"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">Generate Barcode</h3>
                        <p class="text-sm text-zinc-500 mt-2">Create barcode cards for IMEIs</p>
                    </button>
                    <button wire:click="setView('checker')" class="group p-8 md:p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-blue-500 transition-all">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition-colors">
                            <span class="mdi mdi-shield-check text-3xl text-blue-600 group-hover:text-white"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">iCloud Checker</h3>
                        <p class="text-sm text-zinc-500 mt-2">Verify iCloud lock status</p>
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-6">
                <div class="bg-white p-4 md:p-6 rounded-3xl shadow-lg border border-zinc-200 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <button wire:click="setView('select')" class="p-2.5 bg-zinc-100 text-zinc-700 rounded-xl hover:bg-zinc-200 transition-all">
                            <span class="mdi mdi-arrow-left text-xl"></span>
                        </button>
                        <h2 class="text-lg md:text-xl font-bold text-zinc-900 line-clamp-1 uppercase tracking-tight">{{ $viewMode }} Panel</h2>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                        <div class="flex bg-zinc-100 p-1 rounded-xl gap-1 w-full sm:w-auto overflow-x-auto">
                            <button onclick="copyToClipboard(`{{ $this->getAllImeisString() }}`)" class="flex-1 sm:flex-none px-3 py-1.5 hover:bg-white rounded-lg text-xs font-bold text-zinc-600 transition-all">All</button>
                            <button onclick="copyToClipboard(`{{ $this->getDoubleImeisString() }}`)" class="flex-1 sm:flex-none px-3 py-1.5 hover:bg-white rounded-lg text-xs font-bold text-blue-600 transition-all">Double</button>
                            <button onclick="copyToClipboard(`{{ $this->getSingleImeisString() }}`)" class="flex-1 sm:flex-none px-3 py-1.5 hover:bg-white rounded-lg text-xs font-bold text-orange-600 transition-all">Single</button>
                        </div>
                        <button wire:click="checkAllIcloud" wire:loading.attr="disabled" class="flex-1 sm:flex-none px-4 py-2.5 bg-orange-500 text-white rounded-xl font-semibold text-xs hover:bg-orange-600 flex items-center justify-center gap-2 transition-all">
                            <span wire:loading wire:target="checkAllIcloud" class="mdi mdi-loading mdi-spin"></span>
                            <span wire:loading.remove wire:target="checkAllIcloud" class="mdi mdi-refresh"></span> Check All
                        </button>
                        @if($viewMode == 'card')
                        <button onclick="downloadAllAsZip()" class="flex-1 sm:flex-none px-4 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold text-xs hover:bg-emerald-700 flex items-center justify-center gap-2 transition-all shadow-sm">
                            <span class="mdi mdi-zip-box"></span> Download ZIP
                        </button>
                        <select wire:model="selectedCardType" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-zinc-200 font-semibold text-xs bg-white outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="iphone">iPhone Std</option>
                            <option value="iphone14">iPhone 14</option>
                        </select>
                        @endif
                        <button wire:click="resetForm" class="flex-1 sm:flex-none px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-semibold hover:bg-red-100 transition-all text-xs">Reset</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    @php 
                        $countReady = collect($readyGroups)->flatten(1)->count() * 2;
                        $countPaired = count($pairedSingles) * 2;
                        $countLeftover = $leftoverSingle ? 1 : 0;
                        $totalImeis = $countReady + $countPaired + $countLeftover;
                    @endphp
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase mb-1">Total IMEIs</p>
                        <p class="text-2xl md:text-3xl font-black text-zinc-900">{{ $totalImeis }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-emerald-600 font-bold uppercase mb-1">iCloud ON</p>
                        <p class="text-2xl md:text-3xl font-black text-emerald-600">{{ collect($icloudStatus)->where('status', 'ON')->count() }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-red-600 font-bold uppercase mb-1">iCloud OFF</p>
                        <p class="text-2xl md:text-3xl font-black text-red-600">{{ collect($icloudStatus)->where('status', 'OFF')->count() }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-zinc-400 font-bold uppercase mb-1">Pending</p>
                        <p class="text-2xl md:text-3xl font-black text-zinc-400">{{ $totalImeis - count($icloudStatus) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if (!empty($readyGroups))
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 px-2"><div class="w-1 h-6 bg-blue-500 rounded-full"></div><h3 class="font-bold text-blue-600 text-sm">Paired (Same TAC)</h3></div>
                            @foreach ($readyGroups as $tac => $pairs)
                                @foreach ($pairs as $item)
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-zinc-200 group">
                                        <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-xs">
                                            <div><p class="text-blue-600 font-bold mb-1 tracking-tight">IMEI 1</p><p class="font-black text-zinc-900 select-all">{{ $item['imei1'] }}</p></div>
                                            <div><p class="text-zinc-400 font-bold mb-1 tracking-tight">IMEI 2</p><p class="font-black text-zinc-900 select-all">{{ $item['imei2'] }}</p></div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="checkIcloud('{{ $item['imei1'] }}')" wire:loading.attr="disabled" class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'ON' ? 'bg-emerald-500 text-white' : (isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">
                                                {{ $icloudStatus[$item['imei1']]['status'] ?? 'Check 1' }}
                                            </button>
                                            <button wire:click="checkIcloud('{{ $item['imei2'] }}')" wire:loading.attr="disabled" class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'ON' ? 'bg-emerald-500 text-white' : (isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">
                                                {{ $icloudStatus[$item['imei2']]['status'] ?? 'Check 2' }}
                                            </button>
                                            @if ($viewMode == 'card')
                                                <button wire:click="openCard('{{ $item['imei1'] }}', '{{ $item['imei2'] }}')" class="px-4 py-2.5 bg-blue-500 text-white rounded-xl font-bold text-[10px] hover:bg-blue-600 transition-transform active:scale-95">CARD</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-4">
                        @if (!empty($pairedSingles))
                            <div class="flex items-center gap-2 px-2"><div class="w-1 h-6 bg-orange-500 rounded-full"></div><h3 class="font-bold text-orange-600 text-sm">Singles (Merged)</h3></div>
                            @foreach ($pairedSingles as $pair)
                                <div class="bg-white p-4 rounded-2xl shadow-sm border border-zinc-200 group">
                                    <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-xs">
                                        <div><p class="text-orange-600 font-bold mb-1 tracking-tight">IMEI A</p><p class="font-black text-zinc-900 select-all">{{ $pair[0] }}</p></div>
                                        <div><p class="text-orange-600 font-bold mb-1 tracking-tight">IMEI B</p><p class="font-black text-zinc-900 select-all">{{ $pair[1] }}</p></div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="checkIcloud('{{ $pair[0] }}')" class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'ON' ? 'bg-emerald-500 text-white' : (isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">{{ $icloudStatus[$pair[0]]['status'] ?? 'Check A' }}</button>
                                        <button wire:click="checkIcloud('{{ $pair[1] }}')" class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'ON' ? 'bg-emerald-500 text-white' : (isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">{{ $icloudStatus[$pair[1]]['status'] ?? 'Check B' }}</button>
                                        @if ($viewMode == 'card')
                                            <button wire:click="openCard('{{ $pair[0] }}', '{{ $pair[1] }}')" class="px-4 py-2.5 bg-orange-500 text-white rounded-xl font-bold text-[10px] hover:bg-orange-600 transition-transform active:scale-95">CARD</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($leftoverSingle)
                            <div class="flex items-center gap-2 px-2 mt-6"><div class="w-1 h-6 bg-red-500 rounded-full"></div><h3 class="font-bold text-red-600 text-sm">Leftover (Odd)</h3></div>
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-zinc-200">
                                <div class="mb-4 font-mono text-xs font-black text-zinc-900 select-all">{{ $leftoverSingle }}</div>
                                <div class="flex gap-2">
                                    <button wire:click="checkIcloud('{{ $leftoverSingle }}')" class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'ON' ? 'bg-emerald-500 text-white' : (isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">{{ $icloudStatus[$leftoverSingle]['status'] ?? 'Check Single' }}</button>
                                    @if ($viewMode == 'card')
                                        <button wire:click="openCard('{{ $leftoverSingle }}', null)" class="px-4 py-2.5 bg-red-500 text-white rounded-xl font-bold text-[10px] hover:bg-red-600 transition-transform active:scale-95">CARD</button>
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
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-md p-2 md:p-4 overflow-y-auto">
            <div class="relative w-full max-w-sm my-auto">
                <div class="flex justify-between mb-4 px-2">
                    <button onclick="downloadCard()" class="px-5 py-3 bg-emerald-500 text-white rounded-2xl font-bold text-xs shadow-lg hover:bg-emerald-600 transition-all active:scale-95">Download JPG</button>
                    <button wire:click="closeModal" class="px-5 py-3 bg-white/10 text-white rounded-2xl font-bold text-xs hover:bg-white/20 transition-all">Close</button>
                </div>
                <div id="capture-area" class="rounded-[50px] overflow-hidden shadow-2xl flex justify-center bg-black scale-90 sm:scale-100 transition-transform">
                    @if($selectedItem['deviceModel'] == 'iphone14')
                        @include('livewire.partials.iphone-14-card', ['item' => $selectedItem, 'id' => 'ios-card-14'])
                    @else
                        @include('livewire.partials.iphone-card', ['item' => $selectedItem, 'id' => 'ios-card-std'])
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div id="zip-temp-area" style="position: absolute; left: -9999px; top: -9999px;"></div>

    <script>
        window.addEventListener('modalOpened', event => {
            setTimeout(() => { if(window.JsBarcode) JsBarcode(".barcode-svg").init(); }, 300);
        });

        async function downloadAllAsZip() {
            const zip = new JSZip();
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="mdi mdi-loading mdi-spin"></span> Bundling...';
            btn.disabled = true;

            @this.getImeiDataForZip().then(async (data) => {
                const tempArea = document.getElementById('zip-temp-area');
                for (const item of data) {
                    tempArea.innerHTML = item.html;
                    // Inisialisasi barcode di elemen temp
                    JsBarcode(tempArea.querySelector(".barcode-svg")).init();
                    
                    const canvas = await html2canvas(tempArea.firstChild, { scale: 2, useCORS: true, backgroundColor: '#000' });
                    const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.8));
                    zip.file(`IMEI_${item.imei1}.jpg`, blob);
                }
                
                const content = await zip.generateAsync({type: "blob"});
                saveAs(content, `Barcodes_${Date.now()}.zip`);
                
                btn.innerHTML = originalText;
                btn.disabled = false;
                tempArea.innerHTML = '';
            });
        }

        function downloadCard() {
            const area = document.getElementById('capture-area');
            html2canvas(area, { scale: 3, useCORS: true, backgroundColor: '#000' }).then(canvas => {
                const link = document.createElement('a');
                link.download = `IMEI-${Date.now()}.jpg`;
                link.href = canvas.toDataURL('image/jpeg', 0.9);
                link.click();
            });
        }

        function copyToClipboard(text) {
            if (!text) return;
            const cleanText = text.replace(/\\n/g, '\n');
            navigator.clipboard.writeText(cleanText).then(() => alert('Copied to clipboard!'));
        }
    </script>
</div>