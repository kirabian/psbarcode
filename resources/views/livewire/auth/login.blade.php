<div class="min-h-screen flex items-center justify-center bg-slate-100 font-sans">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200">
        <div class="bg-blue-600 p-8 text-center text-white">
            <h1 class="text-3xl font-black tracking-tighter italic">PSTORE <span class="font-light not-italic">IMEI</span></h1>
            <p class="text-blue-100 text-sm mt-2">Internal Management System v1.0</p>
        </div>
        
        <div class="p-8">
            @if (session()->has('error'))
                <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="login">
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ID Login</label>
                    <input type="text" wire:model.defer="id_login" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" 
                        placeholder="Masukkan ID Anda">
                </div>

                <div class="mb-8">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" wire:model.defer="password" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" 
                        placeholder="••••••••">
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all transform active:scale-95">
                    <span wire:loading.remove>MASUK KE SISTEM</span>
                    <span wire:loading>MENGONTROL AKSES...</span>
                </button>
            </form>
        </div>
        <div class="p-4 bg-slate-50 text-center border-t border-slate-100">
            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">© 2025 PSTORE GROUP INDONESIA</span>
        </div>
    </div>
</div>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 font-sans p-4">
    <div class="max-w-md w-full">
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-8 right-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        </div>

        <!-- Card -->
        <div class="relative bg-white/10 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-8 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
                </div>
                <div class="relative">
                    <h1 class="text-4xl font-black tracking-tight">NEXUS</h1>
                    <p class="text-purple-100 text-sm mt-1 font-light">Secure Access Portal</p>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                @if (session()->has('error'))
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-2xl text-red-200 text-sm font-semibold backdrop-blur">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="login" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-widest mb-3">Username</label>
                        <input type="text" wire:model.defer="id_login" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition backdrop-blur-sm" 
                            placeholder="Enter your username">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-widest mb-3">Password</label>
                        <input type="password" wire:model.defer="password" 
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition backdrop-blur-sm" 
                            placeholder="••••••••">
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-purple-500/50 transition-all transform hover:scale-105 active:scale-95 mt-8 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>SIGN IN</span>
                        <span wire:loading class="inline-flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            AUTHENTICATING...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-white/5 border-t border-white/10 text-center">
                <p class="text-[11px] text-slate-400 uppercase font-bold tracking-widest">© 2025 Nexus Security Systems</p>
            </div>
        </div>
    </div>
</div>