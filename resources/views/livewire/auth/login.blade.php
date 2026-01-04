<div class="min-h-screen flex items-center justify-center bg-zinc-50 font-sans p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-zinc-200">
        <div class="p-10 text-center border-b border-zinc-100">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-500 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">SecureHub</h1>
            <p class="text-zinc-500 text-sm mt-1">Internal Management Portal</p>
        </div>
        
        <div class="p-10">
            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="login">
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">Username</label>
                    <input type="text" wire:model.defer="id_login" 
                        class="w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition text-zinc-900 placeholder:text-zinc-400" 
                        placeholder="Enter your username">
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">Password</label>
                    <input type="password" wire:model.defer="password" 
                        class="w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition text-zinc-900 placeholder:text-zinc-400" 
                        placeholder="Enter your password">
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-emerald-500 hover:bg-emerald-600 disabled:bg-zinc-300 text-white font-semibold py-4 rounded-xl transition-all transform active:scale-98 disabled:cursor-not-allowed shadow-sm">
                    <span wire:loading.remove class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In
                    </span>
                    <span wire:loading class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Authenticating...
                    </span>
                </button>
            </form>
        </div>
        
        <div class="px-10 pb-10">
            <div class="pt-6 border-t border-zinc-100">
                <p class="text-xs text-center text-zinc-400">
                    Protected by enterprise-grade security
                </p>
            </div>
        </div>
    </div>
</div>