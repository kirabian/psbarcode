<style>
    /* Hide scrollbar but keep functionality */
    ::-webkit-scrollbar { width: 0px; background: transparent; }
    body { -ms-overflow-style: none; scrollbar-width: none; overflow-y: scroll; }
    
    /* Prevent text selection for app-like feel */
    * { -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }
    input, textarea { -webkit-user-select: text; -moz-user-select: text; -ms-user-select: text; user-select: text; }
    
    /* Remove default margins and make it fullscreen */
    html, body { margin: 0; padding: 0; overflow-x: hidden; height: 100%; }
    
    /* App-like header */
    .app-header { position: sticky; top: 0; z-index: 50; backdrop-filter: blur(10px); }
</style>

<div class="bg-zinc-50 min-h-screen font-sans">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <!-- App Header -->
    <div class="app-header bg-white/80 border-b border-zinc-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-zinc-900">SecureHub</h1>
                    <p class="text-xs text-zinc-500">IMEI Management</p>
                </div>
            </div>
            @if($viewMode)
            <button wire:click="resetForm" class="p-2 hover:bg-zinc-100 rounded-xl transition-all">
                <span class="mdi mdi-refresh text-xl text-zinc-600"></span>
            </button>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-6">
        @if (!$viewMode)
            <div class="bg-white p-8 rounded-3xl shadow-lg border border-zinc-200 mb-6">
                <h2 class="text-xl font-bold mb-6 text-zinc-900">Input Data IMEI</h2>
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
            <div class="max-w-4xl mx-auto py-8 text-center">
                <h2 class="text-2xl font-bold text-zinc-900 mb-8">Select Operation Mode</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <button wire:click="setView('card')"
                        class="group p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-emerald-500 transition-all hover:shadow-xl active:scale-95">
                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500 transition-colors">
                            <span class="mdi mdi-qrcode-scan text-3xl text-emerald-600 group-hover:text-white transition-colors"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">Generate Barcode</h3>
                        <p class="text-sm text-zinc-500 mt-2">Create barcode cards for IMEIs</p>
                    </button>
                    <button wire:click="setView('checker')"
                        class="group p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-blue-500 transition-all hover:shadow-xl active:scale-95">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition-colors">
                            <span class="mdi mdi-shield-check text-3xl text-blue-600 group-hover:text-white transition-colors"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">iCloud Checker</h3>
                        <p class="text-sm text-zinc-500 mt-2">Verify iCloud lock status</p>
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-4">
                <!-- Action Bar -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-zinc-200 flex flex-wrap justify-between items-center gap-3">
                    <div class="flex items-center gap-3">
                        <button wire:click="setView('select')" 
                            class="p-2 bg-zinc-100 text-zinc-700 rounded-xl hover:bg-zinc-200 transition-all">
                            <span class="mdi mdi-arrow-left text-xl"></span>
                        </button>
                        <h2 class="text-lg font-bold text-zinc-900">{{ ucfirst($viewMode) }} Mode</h2>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <button wire:click="checkAllIcloud" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-orange-500 text-white rounded-xl font-semibold text-sm hover:bg-orange-600 shadow-sm flex items-center gap-2 transition-all active:scale-95">
                            <span wire:loading wire:target="checkAllIcloud" class="mdi mdi-loading mdi-spin"></span>
                            <span wire:loading.remove wire:target="checkAllIcloud" class="mdi mdi-refresh"></span>
                            Check All
                        </button>

                        @if($viewMode == 'card')
                        <select wire:model="selectedCardType" 
                            class="px-3 py-2 rounded-xl border border-zinc-200 font-semibold text-sm outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="iphone">iPhone Standard</option>
                            <option value="iphone14">iPhone 14</option>
                        </select>
                        @endif

                        <!-- Copy All Buttons -->
                        <button onclick="copyAllDouble()" 
                            class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-semibold hover:bg-blue-100 transition-all text-sm flex items-center gap-1">
                            <span class="mdi mdi-content-copy"></span> Double
                        </button>
                        <button onclick="copyAllSingle()" 
                            class="px-4 py-2 bg-orange-50 text-orange-600 rounded-xl font-semibold hover:bg-orange-100 transition-all text-sm flex items-center gap-1">
                            <span class="mdi mdi-content-copy"></span> Single
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
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 px-2">
                                <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                                <h3 class="font-bold text-blue-600 text-sm">Paired (Same TAC)</h3>
                            </div>
                            @foreach ($readyGroups as $tac => $pairs)
                                @foreach ($pairs as $item)
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-l-blue-500 border border-zinc-200" 
                                         data-group="double" data-imei1="{{ $item['imei1'] }}" data-imei2="{{ $item['imei2'] }}">
                                        <div class="grid grid-cols-2 gap-3 mb-3 font-mono text-xs">
                                            <div>
                                                <p class="text-blue-600 font-semibold mb-1">IMEI 1</p>
                                                <p class="font-bold text-zinc-900 break-all">{{ $item['imei1'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-zinc-400 font-semibold mb-1">IMEI 2</p>
                                                <p class="font-bold text-zinc-900 break-all">{{ $item['imei2'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="checkIcloud('{{ $item['imei1'] }}')" wire:loading.attr="disabled" 
                                                class="flex-1 py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                                {{ isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                                   (isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                                <span wire:loading wire:target="checkIcloud('{{ $item['imei1'] }}')" class="mdi mdi-loading mdi-spin"></span>
                                                <span>{{ $icloudStatus[$item['imei1']]['status'] ?? 'Check #1' }}</span>
                                            </button>
                                            <button wire:click="checkIcloud('{{ $item['imei2'] }}')" wire:loading.attr="disabled"
                                                class="flex-1 py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                                {{ isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                                   (isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                                <span wire:loading wire:target="checkIcloud('{{ $item['imei2'] }}')" class="mdi mdi-loading mdi-spin"></span>
                                                <span>{{ $icloudStatus[$item['imei2']]['status'] ?? 'Check #2' }}</span>
                                            </button>
                                            @if ($viewMode == 'card')
                                                <button wire:click="openCard('{{ $item['imei1'] }}', '{{ $item['imei2'] }}')" 
                                                    class="px-4 py-2.5 bg-blue-500 text-white rounded-xl font-semibold text-xs hover:bg-blue-600 shadow-sm">
                                                    Card
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-3">
                        @if (!empty($pairedSingles))
                            <div class="flex items-center gap-2 px-2">
                                <div class="w-1 h-6 bg-orange-500 rounded-full"></div>
                                <h3 class="font-bold text-orange-600 text-sm">Singles (Merged)</h3>
                            </div>
                            @foreach ($pairedSingles as $pair)
                                <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-l-orange-500 border border-zinc-200"
                                     data-group="single" data-imei1="{{ $pair[0] }}" data-imei2="{{ $pair[1] }}">
                                    <div class="grid grid-cols-2 gap-3 mb-3 font-mono text-xs">
                                        <div>
                                            <p class="text-orange-600 font-semibold mb-1">IMEI A</p>
                                            <p class="font-bold text-zinc-900 break-all">{{ $pair[0] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-orange-600 font-semibold mb-1">IMEI B</p>
                                            <p class="font-bold text-zinc-900 break-all">{{ $pair[1] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="checkIcloud('{{ $pair[0] }}')" wire:loading.attr="disabled"
                                            class="flex-1 py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                            {{ isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                               (isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                            <span wire:loading wire:target="checkIcloud('{{ $pair[0] }}')" class="mdi mdi-loading mdi-spin"></span>
                                            <span>{{ $icloudStatus[$pair[0]]['status'] ?? 'Check A' }}</span>
                                        </button>
                                        <button wire:click="checkIcloud('{{ $pair[1] }}')" wire:loading.attr="disabled"
                                            class="flex-1 py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                            {{ isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                               (isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                            <span wire:loading wire:target="checkIcloud('{{ $pair[1] }}')" class="mdi mdi-loading mdi-spin"></span>
                                            <span>{{ $icloudStatus[$pair[1]]['status'] ?? 'Check B' }}</span>
                                        </button>
                                        @if ($viewMode == 'card')
                                            <button wire:click="openCard('{{ $pair[0] }}', '{{ $pair[1] }}')" 
                                                class="px-4 py-2.5 bg-orange-500 text-white rounded-xl font-semibold text-xs hover:bg-orange-600 shadow-sm">
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
                            <div class="bg-white p-4 rounded-2xl shadow-sm border-l-4 border-l-red-500 border border-zinc-200"
                                 data-group="leftover" data-imei1="{{ $leftoverSingle }}">
                                <div class="mb-3 font-mono text-xs">
                                    <p class="text-red-600 font-semibold mb-1">IMEI</p>
                                    <p class="font-bold text-zinc-900 break-all">{{ $leftoverSingle }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="checkIcloud('{{ $leftoverSingle }}')" wire:loading.attr="disabled"
                                        class="flex-1 py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center justify-center gap-1
                                        {{ isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'ON' ? 'bg-emerald-500 text-white' : 
                                           (isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'OFF' ? 'bg-red-500 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200') }}">
                                        <span wire:loading wire:target="checkIcloud('{{ $leftoverSingle }}')" class="mdi mdi-loading mdi-spin"></span>
                                        <span>{{ $icloudStatus[$leftoverSingle]['status'] ?? 'Check IMEI' }}</span>
                                    </button>
                                    @if ($viewMode == 'card')
                                        <button wire:click="openCard('{{ $leftoverSingle }}', null)" 
                                            class="px-4 py-2.5 bg-red-500 text-white rounded-xl font-semibold text-xs hover:bg-red-600 shadow-sm">
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

        function copyAllDouble() {
            const doubleIMEIs = [];
            document.querySelectorAll('[data-group="double"]').forEach(card => {
                const imei1 = card.getAttribute('data-imei1');
                const imei2 = card.getAttribute('data-imei2');
                if(imei1 && imei2) {
                    doubleIMEIs.push(imei1, imei2);
                }
            });
            
            if(doubleIMEIs.length > 0) {
                navigator.clipboard.writeText(doubleIMEIs.join('\n')).then(() => {
                    showToast('Copied ' + doubleIMEIs.length + ' Double IMEIs!', 'success');
                });
            } else {
                showToast('No double IMEIs found', 'error');
            }
        }

        function copyAllSingle() {
            const singleIMEIs = [];
            document.querySelectorAll('[data-group="single"]').forEach(card => {
                const imei1 = card.getAttribute('data-imei1');
                const imei2 = card.getAttribute('data-imei2');
                if(imei1) singleIMEIs.push(imei1);
                if(imei2) singleIMEIs.push(imei2);
            });
            
            if(singleIMEIs.length > 0) {
                navigator.clipboard.writeText(singleIMEIs.join('\n')).then(() => {
                    showToast('Copied ' + singleIMEIs.length + ' Single IMEIs!', 'success');
                });
            } else {
                showToast('No single IMEIs found', 'error');
            }
        }

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `fixed top-20 right-6 z-[200] px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 animate-slide-in ${
                type === 'success' ? 'bg-emerald-500' : 'bg-red-500'
            } text-white font-semibold`;
            toast.innerHTML = `
                <span class="mdi ${type === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle'} text-2xl"></span>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slide-out 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }
    </script>

    <style>
        @keyframes slide-in {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slide-out {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
        .animate-slide-in { animation: slide-in 0.3s ease-out; }
    </style>
</div>