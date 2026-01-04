<div class="min-h-screen flex items-center justify-center bg-[#F8FAFC] font-sans p-6">
    <div class="max-w-[440px] w-full">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4 shadow-xl shadow-indigo-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3a10.003 10.003 0 00-7.306 3.148m0 0l.058.058m0 0a10.053 10.053 0 00-3.148 7.306 10.053 10.053 0 003.148 7.306m0 0l.058.058m0 0l.058.058m1.306-1.306a10.053 10.053 0 007.306 3.148 10.053 10.053 0 007.306-3.148m0 0l.058-.058m0 0a10.053 10.053 0 003.148-7.306 10.053 10.053 0 00-3.148-7.306m0 0l-.058-.058m0-0a10.053 10.053 0 00-7.306-3.148 10.053 10.053 0 00-7.306 3.148" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">NEXUS<span class="text-indigo-600">CORE</span></h1>
            <p class="text-slate-500 text-sm mt-1">Centralized Management Ecosystem</p>
        </div>

        <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 p-10">
            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-100 flex items-center gap-3 text-red-600 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="login" class="space-y-6">
                <div>
                    <label class="block text-[13px] font-semibold text-slate-700 mb-2 ml-1">Identity ID</label>
                    <div class="relative">
                        <input type="text" wire:model.defer="id_login" 
                            class="w-full bg-slate-50 px-4 py-3.5 rounded-xl border border-slate-200 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 outline-none transition-all duration-200 placeholder:text-slate-400" 
                            placeholder="Enter your unique ID">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="text-[13px] font-semibold text-slate-700">Security Key</label>
                        <a href="#" class="text-[12px] font-medium text-indigo-600 hover:text-indigo-700 transition">Forgot?</a>
                    </div>
                    <input type="password" wire:model.defer="password" 
                        class="w-full bg-slate-50 px-4 py-3.5 rounded-xl border border-slate-200 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 outline-none transition-all duration-200 placeholder:text-slate-400" 
                        placeholder="••••••••">
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-slate-900 hover:bg-indigo-700 text-white font-semibold py-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 group">
                    <span wire:loading.remove>Sign in to Workspace</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Authenticating...
                    </span>
                    <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>

        <p class="mt-8 text-center text-slate-400 text-xs font-medium tracking-wide uppercase">
            &copy; 2026 Nexus Systems &bull; Global Operations
        </p>
    </div>
</div>