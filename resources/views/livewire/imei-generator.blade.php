<div class="p-6 bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen font-sans">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <div class="max-w-7xl mx-auto">
        @if (!$viewMode)
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 mb-6">
                <h2 class="text-2xl font-bold mb-6 text-slate-800 flex items-center gap-2">
                    <span class="mdi mdi-text-box-multiple text-blue-500"></span> Input Data IMEI
                </h2>
                <textarea wire:model.defer="inputText" rows="10"
                    class="w-full p-4 border border-gray-300 rounded-xl font-mono text-sm focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                    placeholder="Tempel daftar IMEI di sini (satu per baris atau dipisahkan spasi)..."></textarea>
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-500 italic"> Format: 15 digit angka </div>
                    <button wire:click="organize"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5">
                        <span class="mdi mdi-play mr-2"></span> PROSES DATA
                    </button>
                </div>
            </div>
        @elseif($viewMode == 'select')
            <div class="max-w-3xl mx-auto py-12 text-center">
                <h2 class="text-3xl font-black text-slate-800 mb-10 italic uppercase tracking-tighter">Pilih Mode Operasi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <button wire:click="setView('card')"
                        class="group p-8 bg-white rounded-3xl shadow-xl border-4 border-transparent hover:border-blue-500 transition-all duration-300 hover:-translate-y-1">
                        <span class="mdi mdi-qrcode-scan text-4xl text-blue-600"></span>
                        <h3 class="text-xl font-bold text-slate-800 mt-4 uppercase">BUAT BARCODE</h3>
                    </button>
                    <button wire:click="setView('checker')"
                        class="group p-8 bg-white rounded-2xl shadow-xl border-4 border-transparent hover:border-green-500 transition-all duration-300 hover:-translate-y-1">
                        <span class="mdi mdi-shield-check text-4xl text-green-600"></span>
                        <h3 class="text-xl font-bold text-slate-800 mt-4 uppercase">ICLOUD CHECKER</h3>
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <button wire:click="setView('select')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition-all text-xs">← KEMBALI</button>
                        <h2 class="text-xl font-black text-slate-800 italic uppercase tracking-tighter">LIST IMEI: {{ strtoupper($viewMode) }}</h2>
                    </div>

                    <div class="flex items-center gap-2">
                        <button wire:click="checkAllIcloud" wire:loading.attr="disabled"
                            class="px-5 py-2 bg-orange-600 text-white rounded-xl font-black text-xs hover:bg-orange-700 shadow-md flex items-center gap-2 transition-all">
                            <span wire:loading wire:target="checkAllIcloud" class="mdi mdi-loading mdi-spin"></span>
                            <span wire:loading.remove wire:target="checkAllIcloud" class="mdi mdi-refresh"></span>
                            CEK SEMUA IMEI
                        </button>

                        @if($viewMode == 'card')
                        <select wire:model="selectedCardType" class="px-3 py-2 rounded-xl border border-slate-200 font-bold text-xs outline-none bg-white">
                            <option value="iphone">iPhone Biasa</option>
                            <option value="iphone14">iPhone 14</option>
                        </select>
                        @endif

                        <button wire:click="resetForm" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-100 transition-all text-xs">RESET</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow border border-gray-200">
                        <p class="text-[10px] text-gray-500 font-black uppercase">Total</p>
                        @php 
                            $countReady = collect($readyGroups)->flatten(1)->count() * 2;
                            $countPaired = count($pairedSingles) * 2;
                            $countLeftover = $leftoverSingle ? 1 : 0;
                            $totalImeis = $countReady + $countPaired + $countLeftover;
                        @endphp
                        <p class="text-2xl font-black text-slate-800">{{ $totalImeis }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow border border-gray-200">
                        <p class="text-[10px] text-green-600 font-black uppercase">ICloud ON</p>
                        <p class="text-2xl font-black text-green-600">{{ collect($icloudStatus)->where('status', 'ON')->count() }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow border border-gray-200">
                        <p class="text-[10px] text-red-600 font-black uppercase">ICloud OFF</p>
                        <p class="text-2xl font-black text-red-600">{{ collect($icloudStatus)->where('status', 'OFF')->count() }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow border border-gray-200">
                        <p class="text-[10px] text-gray-400 font-black uppercase">Pending</p>
                        <p class="text-2xl font-black text-gray-400">{{ $totalImeis - count($icloudStatus) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @if (!empty($readyGroups))
                        <div class="space-y-4">
                            <h3 class="font-black text-blue-700 italic text-sm tracking-widest uppercase px-2">● DOUBLE (TAC SAMA)</h3>
                            @foreach ($readyGroups as $tac => $pairs)
                                @foreach ($pairs as $item)
                                    <div class="bg-white p-5 rounded-3xl shadow border border-gray-200 border-l-[12px] border-l-blue-600">
                                        <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-[11px] font-black italic">
                                            <p class="text-blue-700">IMEI 1: <span class="text-sm font-black text-slate-900">{{ $item['imei1'] }}</span></p>
                                            <p class="text-slate-400">IMEI 2: <span class="text-sm font-black text-slate-900">{{ $item['imei2'] }}</span></p>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="checkIcloud('{{ $item['imei1'] }}')" wire:loading.attr="disabled" 
                                                class="flex-1 py-3 rounded-xl text-[10px] font-black transition-all flex items-center justify-center gap-1
                                                {{ isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'ON' ? 'bg-green-600 text-white shadow-inner' : 
                                                   (isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'OFF' ? 'bg-red-600 text-white shadow-inner' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') }}">
                                                <span wire:loading wire:target="checkIcloud('{{ $item['imei1'] }}')" class="mdi mdi-loading mdi-spin"></span>
                                                <span>{{ $icloudStatus[$item['imei1']]['status'] ?? 'CEK 1' }}</span>
                                            </button>
                                            <button wire:click="checkIcloud('{{ $item['imei2'] }}')" wire:loading.attr="disabled"
                                                class="flex-1 py-3 rounded-xl text-[10px] font-black transition-all flex items-center justify-center gap-1
                                                {{ isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'ON' ? 'bg-green-600 text-white shadow-inner' : 
                                                   (isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'OFF' ? 'bg-red-600 text-white shadow-inner' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') }}">
                                                <span wire:loading wire:target="checkIcloud('{{ $item['imei2'] }}')" class="mdi mdi-loading mdi-spin"></span>
                                                <span>{{ $icloudStatus[$item['imei2']]['status'] ?? 'CEK 2' }}</span>
                                            </button>
                                            @if ($viewMode == 'card')
                                                <button wire:click="openCard('{{ $item['imei1'] }}', '{{ $item['imei2'] }}')" class="px-5 py-3 bg-blue-600 text-white rounded-xl font-black text-[10px] hover:bg-blue-700 shadow-md">CARD</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-4">
                        @if (!empty($pairedSingles))
                            <h3 class="font-black text-orange-700 italic text-sm tracking-widest uppercase px-2">● SINGLE (GABUNGAN)</h3>
                            @foreach ($pairedSingles as $pair)
                                <div class="bg-white p-5 rounded-3xl shadow border border-gray-200 border-l-[12px] border-l-orange-500">
                                    <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-[11px] font-black italic">
                                        <p class="text-orange-600">A: <span class="text-sm font-black text-slate-900">{{ $pair[0] }}</span></p>
                                        <p class="text-orange-600">B: <span class="text-sm font-black text-slate-900">{{ $pair[1] }}</span></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="checkIcloud('{{ $pair[0] }}')" wire:loading.attr="disabled"
                                            class="flex-1 py-3 rounded-xl text-[10px] font-black transition-all flex items-center justify-center gap-1
                                            {{ isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'ON' ? 'bg-green-600 text-white shadow-inner' : 
                                               (isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'OFF' ? 'bg-red-600 text-white shadow-inner' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') }}">
                                            <span wire:loading wire:target="checkIcloud('{{ $pair[0] }}')" class="mdi mdi-loading mdi-spin"></span>
                                            <span>{{ $icloudStatus[$pair[0]]['status'] ?? 'CEK A' }}</span>
                                        </button>
                                        <button wire:click="checkIcloud('{{ $pair[1] }}')" wire:loading.attr="disabled"
                                            class="flex-1 py-3 rounded-xl text-[10px] font-black transition-all flex items-center justify-center gap-1
                                            {{ isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'ON' ? 'bg-green-600 text-white shadow-inner' : 
                                               (isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'OFF' ? 'bg-red-600 text-white shadow-inner' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') }}">
                                            <span wire:loading wire:target="checkIcloud('{{ $pair[1] }}')" class="mdi mdi-loading mdi-spin"></span>
                                            <span>{{ $icloudStatus[$pair[1]]['status'] ?? 'CEK B' }}</span>
                                        </button>
                                        @if ($viewMode == 'card')
                                            <button wire:click="openCard('{{ $pair[0] }}', '{{ $pair[1] }}')" class="px-5 py-3 bg-orange-600 text-white rounded-xl font-black text-[10px] hover:bg-orange-700 shadow-md">CARD</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($leftoverSingle)
                            <h3 class="font-black text-red-700 italic text-sm tracking-widest uppercase px-2 mt-6">● LEFTOVER (GANJIL)</h3>
                            <div class="bg-white p-5 rounded-3xl shadow border border-gray-200 border-l-[12px] border-l-red-600">
                                <div class="mb-4 font-mono text-[11px] font-black italic">
                                    <p class="text-red-600">IMEI: <span class="text-sm font-black text-slate-900">{{ $leftoverSingle }}</span></p>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="checkIcloud('{{ $leftoverSingle }}')" wire:loading.attr="disabled"
                                        class="flex-1 py-3 rounded-xl text-[10px] font-black transition-all flex items-center justify-center gap-1
                                        {{ isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'ON' ? 'bg-green-600 text-white shadow-inner' : 
                                           (isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'OFF' ? 'bg-red-600 text-white shadow-inner' : 'bg-slate-100 text-slate-600 hover:bg-slate-200') }}">
                                        <span wire:loading wire:target="checkIcloud('{{ $leftoverSingle }}')" class="mdi mdi-loading mdi-spin"></span>
                                        <span>{{ $icloudStatus[$leftoverSingle]['status'] ?? 'CEK IMEI' }}</span>
                                    </button>
                                    @if ($viewMode == 'card')
                                        <button wire:click="openCard('{{ $leftoverSingle }}', null)" class="px-5 py-3 bg-red-600 text-white rounded-xl font-black text-[10px] hover:bg-red-700 shadow-md">CARD</button>
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
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/95 backdrop-blur-md p-4 overflow-y-auto">
            <div class="relative w-full max-w-sm my-auto">
                <div class="flex justify-between mb-4 px-2">
                    <button onclick="downloadCard()" class="px-6 py-2 bg-green-600 text-white rounded-full font-black text-[10px] shadow-lg hover:bg-green-500 uppercase tracking-tighter transition-all">DOWNLOAD JPG</button>
                    <button wire:click="closeModal" class="px-6 py-2 bg-white/20 text-white rounded-full font-black text-[10px] hover:bg-white/30 transition-all uppercase tracking-tighter">TUTUP</button>
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