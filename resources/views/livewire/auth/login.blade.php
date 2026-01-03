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