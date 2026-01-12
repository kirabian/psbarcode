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
                    <button wire:click="setView('card')"
                        class="group p-8 md:p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-emerald-500 transition-all">
                        <div
                            class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500 transition-colors">
                            <span class="mdi mdi-qrcode-scan text-3xl text-emerald-600 group-hover:text-white"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">Generate Barcode</h3>
                        <p class="text-sm text-zinc-500 mt-2">Create barcode cards for IMEIs</p>
                    </button>
                    <button wire:click="setView('checker')"
                        class="group p-8 md:p-10 bg-white rounded-3xl shadow-lg border-2 border-zinc-200 hover:border-blue-500 transition-all">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition-colors">
                            <span class="mdi mdi-shield-check text-3xl text-blue-600 group-hover:text-white"></span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900">iCloud Checker</h3>
                        <p class="text-sm text-zinc-500 mt-2">Verify iCloud lock status</p>
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-6">
                <div
                    class="bg-white p-4 md:p-6 rounded-3xl shadow-lg border border-zinc-200 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <button wire:click="setView('select')"
                            class="p-2.5 bg-zinc-100 text-zinc-700 rounded-xl hover:bg-zinc-200 transition-all">
                            <span class="mdi mdi-arrow-left text-xl"></span>
                        </button>
                        <h2 class="text-lg md:text-xl font-bold text-zinc-900 line-clamp-1 uppercase tracking-tight">
                            {{ $viewMode }} Panel</h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                        <div class="flex bg-zinc-100 p-1 rounded-xl gap-1 w-full sm:w-auto overflow-x-auto">
                            <button onclick="copyToClipboard(`{{ $this->getAllImeisString() }}`)"
                                class="flex-1 sm:flex-none px-3 py-1.5 hover:bg-white rounded-lg text-xs font-bold text-zinc-600 transition-all">All</button>
                            <button onclick="copyToClipboard(`{{ $this->getDoubleImeisString() }}`)"
                                class="flex-1 sm:flex-none px-3 py-1.5 hover:bg-white rounded-lg text-xs font-bold text-blue-600 transition-all">Double</button>
                            <button onclick="copyToClipboard(`{{ $this->getSingleImeisString() }}`)"
                                class="flex-1 sm:flex-none px-3 py-1.5 hover:bg-white rounded-lg text-xs font-bold text-orange-600 transition-all">Single</button>
                        </div>
                        <button wire:click="checkAllIcloud" wire:loading.attr="disabled"
                            class="flex-1 sm:flex-none px-4 py-2.5 bg-orange-500 text-white rounded-xl font-semibold text-xs hover:bg-orange-600 flex items-center justify-center gap-2 transition-all">
                            <span wire:loading wire:target="checkAllIcloud" class="mdi mdi-loading mdi-spin"></span>
                            <span wire:loading.remove wire:target="checkAllIcloud" class="mdi mdi-refresh"></span> Check
                            All
                        </button>

                        @if ($viewMode == 'card')
                            <div class="flex gap-2 w-full sm:w-auto">
                                <button onclick="downloadZip('double')" id="btn-zip-double"
                                    class="btn-zip flex-1 sm:flex-none px-4 py-2.5 bg-blue-600 text-white rounded-xl font-semibold text-xs hover:bg-blue-700 flex items-center justify-center gap-2 transition-all shadow-sm">
                                    <span class="mdi mdi-zip-box"></span> ZIP Double
                                </button>
                                <button onclick="downloadZip('single')" id="btn-zip-single"
                                    class="btn-zip flex-1 sm:flex-none px-4 py-2.5 bg-orange-600 text-white rounded-xl font-semibold text-xs hover:bg-orange-700 flex items-center justify-center gap-2 transition-all shadow-sm">
                                    <span class="mdi mdi-zip-box"></span> ZIP Single
                                </button>
                            </div>
                            <select wire:model="selectedCardType"
                                class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-zinc-200 font-semibold text-xs bg-white outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="iphone">iPhone Std</option>
                                <option value="iphone14">iPhone 14</option>
                            </select>
                        @endif
                        <button wire:click="resetForm"
                            class="flex-1 sm:flex-none px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-semibold hover:bg-red-100 transition-all text-xs">Reset</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
                    @php
                        $countDouble = collect($readyGroups)->flatten(1)->count() * 2;
                        $countPairedSingle = count($pairedSingles) * 2;
                        $countOdd = $leftoverSingle ? 1 : 0;
                        $totalSingle = $countPairedSingle + $countOdd;
                        $totalImeis = $countDouble + $totalSingle;
                    @endphp
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase mb-1">Total IMEIs</p>
                        <p class="text-2xl md:text-3xl font-black text-zinc-900">{{ $totalImeis }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-blue-600 font-bold uppercase mb-1">Total Double</p>
                        <p class="text-2xl md:text-3xl font-black text-blue-600">{{ $countDouble }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-orange-600 font-bold uppercase mb-1">Total Single</p>
                        <p class="text-2xl md:text-3xl font-black text-orange-600">{{ $totalSingle }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-red-600 font-bold uppercase mb-1">iCloud ON</p>
                        <p class="text-2xl md:text-3xl font-black text-red-600">
                            {{ collect($icloudStatus)->where('status', 'ON')->count() }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-zinc-200">
                        <p class="text-[10px] text-emerald-600 font-bold uppercase mb-1">iCloud OFF</p>
                        <p class="text-2xl md:text-3xl font-black text-emerald-600">
                            {{ collect($icloudStatus)->where('status', 'OFF')->count() }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 text-center">
                    @if (!empty($readyGroups))
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 px-2">
                                <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                                <h3 class="font-bold text-blue-600 text-sm uppercase">Paired (TAC)</h3>
                            </div>
                            @foreach ($readyGroups as $tac => $pairs)
                                @foreach ($pairs as $item)
                                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-zinc-200">
                                        <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-xs">
                                            <div>
                                                <p class="text-blue-600 font-bold mb-1 tracking-tight">IMEI 1</p>
                                                <p class="font-black text-zinc-900">{{ $item['imei1'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-zinc-400 font-bold mb-1 tracking-tight">IMEI 2</p>
                                                <p class="font-black text-zinc-900">{{ $item['imei2'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="checkIcloud('{{ $item['imei1'] }}')"
                                                wire:loading.attr="disabled"
                                                class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'ON' ? 'bg-red-500 text-white' : (isset($icloudStatus[$item['imei1']]) && $icloudStatus[$item['imei1']]['status'] == 'OFF' ? 'bg-emerald-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">
                                                {{ $icloudStatus[$item['imei1']]['status'] ?? 'CHECK 1' }}
                                            </button>
                                            <button wire:click="checkIcloud('{{ $item['imei2'] }}')"
                                                wire:loading.attr="disabled"
                                                class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'ON' ? 'bg-red-500 text-white' : (isset($icloudStatus[$item['imei2']]) && $icloudStatus[$item['imei2']]['status'] == 'OFF' ? 'bg-emerald-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">
                                                {{ $icloudStatus[$item['imei2']]['status'] ?? 'CHECK 2' }}
                                            </button>
                                            @if ($viewMode == 'card')
                                                <button
                                                    wire:click="openCard('{{ $item['imei1'] }}', '{{ $item['imei2'] }}')"
                                                    class="px-4 py-2.5 bg-blue-500 text-white rounded-xl font-bold text-[10px]">CARD</button>
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
                                <h3 class="font-bold text-orange-600 text-sm uppercase">Merged</h3>
                            </div>
                            @foreach ($pairedSingles as $pair)
                                <div class="bg-white p-4 rounded-2xl shadow-sm border border-zinc-200">
                                    <div class="grid grid-cols-2 gap-4 mb-4 font-mono text-xs">
                                        <div>
                                            <p class="text-orange-600 font-bold mb-1 tracking-tight">IMEI A</p>
                                            <p class="font-black text-zinc-900">{{ $pair[0] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-orange-600 font-bold mb-1 tracking-tight">IMEI B</p>
                                            <p class="font-black text-zinc-900">{{ $pair[1] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="checkIcloud('{{ $pair[0] }}')"
                                            wire:loading.attr="disabled"
                                            class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'ON' ? 'bg-red-500 text-white' : (isset($icloudStatus[$pair[0]]) && $icloudStatus[$pair[0]]['status'] == 'OFF' ? 'bg-emerald-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">
                                            {{ $icloudStatus[$pair[0]]['status'] ?? 'CHECK A' }}
                                        </button>
                                        <button wire:click="checkIcloud('{{ $pair[1] }}')"
                                            wire:loading.attr="disabled"
                                            class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'ON' ? 'bg-red-500 text-white' : (isset($icloudStatus[$pair[1]]) && $icloudStatus[$pair[1]]['status'] == 'OFF' ? 'bg-emerald-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">
                                            {{ $icloudStatus[$pair[1]]['status'] ?? 'CHECK B' }}
                                        </button>
                                        @if ($viewMode == 'card')
                                            <button
                                                wire:click="openCard('{{ $pair[0] }}', '{{ $pair[1] }}')"
                                                class="px-4 py-2.5 bg-orange-500 text-white rounded-xl font-bold text-[10px]">CARD</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if ($leftoverSingle)
                            <div class="flex items-center gap-2 px-2 mt-6">
                                <div class="w-1 h-6 bg-red-500 rounded-full"></div>
                                <h3 class="font-bold text-red-600 text-sm uppercase">Odd</h3>
                            </div>
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-zinc-200">
                                <div class="mb-4 font-mono text-xs font-black text-zinc-900">{{ $leftoverSingle }}
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="checkIcloud('{{ $leftoverSingle }}')"
                                        wire:loading.attr="disabled"
                                        class="flex-1 py-2.5 rounded-xl text-[10px] font-bold transition-all {{ isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'ON' ? 'bg-red-500 text-white' : (isset($icloudStatus[$leftoverSingle]) && $icloudStatus[$leftoverSingle]['status'] == 'OFF' ? 'bg-emerald-500 text-white' : 'bg-zinc-100 text-zinc-700') }}">
                                        {{ $icloudStatus[$leftoverSingle]['status'] ?? 'CHECK SINGLE' }}
                                    </button>
                                    @if ($viewMode == 'card')
                                        <button wire:click="openCard('{{ $leftoverSingle }}', null)"
                                            class="px-4 py-2.5 bg-red-50 text-white rounded-xl font-bold text-[10px]">CARD</button>
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
        <div
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="relative w-full max-w-sm my-auto flex flex-col items-center">

                <div class="flex gap-3 mb-6">
                    <button onclick="downloadCardPng()"
                        class="flex items-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full font-bold text-sm shadow-lg transition-transform active:scale-95">
                        <span class="mdi mdi-download"></span> Download PNG
                    </button>
                    <button wire:click="closeModal"
                        class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-full font-bold text-sm transition-colors">
                        Close
                    </button>
                </div>

                <div id="capture-area" class="flex justify-center items-center p-4">
                    @if ($selectedItem['deviceModel'] == 'iphone14')
                        @include('livewire.partials.iphone-14-card', [
                            'item' => $selectedItem,
                            'id' => 'ios-card-14',
                        ])
                    @else
                        @include('livewire.partials.iphone-card', [
                            'item' => $selectedItem,
                            'id' => 'ios-card-std',
                        ])
                    @endif
                </div>

                <p class="text-white/40 text-xs mt-4">High Resolution PNG Generated</p>
            </div>
        </div>
    @endif

    <div id="zip-temp-area" style="position: absolute; left: -9999px; top: -9999px;"></div>

    <script>
        window.addEventListener('modalOpened', event => {
            setTimeout(() => {
                if (window.JsBarcode) JsBarcode(".barcode-svg").init();
            }, 300);
        });

        async function downloadZip(type) {
            const zip = new JSZip();
            const btn = document.getElementById('btn-zip-' + type);
            const otherBtn = document.getElementById('btn-zip-' + (type === 'double' ? 'single' : 'double'));
            const originalText = btn.innerHTML;
            const tempArea = document.getElementById('zip-temp-area');

            btn.disabled = true;
            if (otherBtn) otherBtn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');

            try {
                const data = (type === 'double') ? await @this.getDoubleDataForZip() : await @this
            .getSingleDataForZip();

                if (data.length === 0) {
                    alert('Tidak ada data.');
                    restoreButtons();
                    return;
                }

                for (let i = 0; i < data.length; i++) {
                    btn.innerHTML = `<span class="mdi mdi-loading mdi-spin"></span> Process ${i+1}/${data.length}`;

                    tempArea.innerHTML = data[i].html;
                    const barcodes = tempArea.querySelectorAll(".barcode-svg");
                    barcodes.forEach(el => {
                        if (el.getAttribute('data-value')) JsBarcode(el).init();
                    });

                    await new Promise(r => setTimeout(r, 150)); // Jeda sebentar untuk rendering font

                    // PENTING: backgroundColor: null agar sudut transparan
                    const canvas = await html2canvas(tempArea.firstChild, {
                        scale: 2,
                        useCORS: true,
                        logging: false,
                        backgroundColor: null
                    });

                    const blob = await new Promise(res => canvas.toBlob(res, 'image/png', 0.8));
                    zip.file(`${type.toUpperCase()}_${data[i].imei1}.png`, blob);

                    tempArea.innerHTML = ''; // Clean up
                    if (i % 10 === 0) await new Promise(r => setTimeout(r, 100)); // Garbage collection break
                }

                btn.innerHTML = `<span class="mdi mdi-loading mdi-spin"></span> Compressing...`;
                const content = await zip.generateAsync({
                    type: "blob"
                });
                saveAs(content, `${data.length} IMEI ${type.toUpperCase()}.zip`);

            } catch (e) {
                console.error(e);
                alert('Error: ' + e.message);
            } finally {
                restoreButtons();
            }

            function restoreButtons() {
                btn.innerHTML = originalText;
                btn.disabled = false;
                if (otherBtn) {
                    otherBtn.disabled = false;
                    otherBtn.classList.remove('opacity-50');
                }
                btn.classList.remove('opacity-75');
            }
        }

        function downloadCardPng() {
            // Deteksi ID mana yang sedang aktif (iPhone 14 atau Standard)
            let targetId = document.getElementById('ios-card-14') ? 'ios-card-14' : 'ios-card-std';
            const area = document.getElementById(targetId);

            if (!area) {
                alert('Card element not found!');
                return;
            }

            // Opsi html2canvas untuk transparansi
            html2canvas(area, {
                scale: 4, // Resolusi tinggi (4x)
                useCORS: true, // Izinkan aset luar
                allowTaint: true,
                backgroundColor: null, // <--- KUNCI: Membuat background canvas transparan
                logging: false,
                onclone: (clonedDoc) => {
                    // Trik tambahan: Pastikan elemen yang di-clone visibility-nya visible
                    const clonedElement = clonedDoc.getElementById(targetId);
                    if (clonedElement) {
                        clonedElement.style.display = 'block';
                        // Paksa background container menjadi transparan jika ada parent yang terbawa
                        if (clonedElement.parentElement) {
                            clonedElement.parentElement.style.backgroundColor = 'transparent';
                        }
                    }
                }
            }).then(canvas => {
                // Konversi ke Blob PNG
                canvas.toBlob(function(blob) {
                    saveAs(blob, `iPhone-Mockup-${Date.now()}.png`);
                });
            }).catch(err => {
                console.error("Error generating image:", err);
                alert("Gagal membuat gambar.");
            });
        }

        function copyToClipboard(text) {
            if (!text) return;
            const clean = text.replace(/\\n/g, '\n');
            navigator.clipboard.writeText(clean).then(() => alert('Copied!'));
        }
    </script>
</div>
