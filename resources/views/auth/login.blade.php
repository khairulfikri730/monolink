<x-guest-layout>
    <div class="bg-white rounded-[24px] shadow-[0_12px_40px_rgba(0,0,0,0.08)] border border-gray-200/70 overflow-hidden">
        <div class="p-7 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-[22px] font-bold tracking-tight text-gray-900">Welcome back 👋</h1>
                    <p class="text-sm text-gray-500 mt-1">Masuk ke dashboard Monolink Anda</p>
                </div>
                <span class="hidden sm:flex w-10 h-10 rounded-2xl bg-[#071A8C] text-white items-center justify-center shadow-md">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                </span>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold tracking-widest uppercase text-gray-500 mb-1.5">Email</label>
                    <div class="relative group">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#071A8C] transition">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="you@monolink.id"
                            class="block w-full rounded-xl border-gray-200 pl-10 pr-3 py-3 text-sm focus:border-[#071A8C] focus:ring-[#071A8C] shadow-sm" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold tracking-widest uppercase text-gray-500">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#071A8C] hover:text-[#0A1C8C] hover:underline">Lupa password?</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#071A8C] transition">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full rounded-xl border-gray-200 pl-10 pr-11 py-3 text-sm focus:border-[#071A8C] focus:ring-[#071A8C] shadow-sm pr-11" />
                        <button type="button" onclick="togglePwd()" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition">
                            <i id="eye-on" data-lucide="eye" class="w-4 h-4"></i>
                            <i id="eye-off" data-lucide="eye-off" class="w-4 h-4 hidden"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <label class="flex items-center gap-2.5 py-1 cursor-pointer select-none group">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#071A8C] focus:ring-[#071A8C]/20">
                    <span class="text-sm text-gray-600 group-hover:text-gray-800">Ingat saya</span>
                    <span class="ml-auto text-xs text-gray-400"> Aman di perangkat pribadi</span>
                </label>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[#071A8C] hover:bg-[#0A1C8C] active:bg-[#06147a] text-white font-semibold text-sm py-3.5 rounded-xl shadow-[0_8px_20px_rgba(7,26,140,0.28)] hover:shadow-[0_12px_28px_rgba(7,26,140,0.32)] hover:-translate-y-[1px] active:translate-y-0 transition-all">
                    Masuk ke Dashboard
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>

                <div class="flex items-center gap-3 py-2">
                    <span class="h-px flex-1 bg-gray-200"></span>
                    <span class="text-xs text-gray-400">atau</span>
                    <span class="h-px flex-1 bg-gray-200"></span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 font-medium text-sm py-3 rounded-xl transition">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> Daftar
                    </a>
                    <a href="/" class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white font-medium text-sm py-3 rounded-xl transition">
                        <i data-lucide="home" class="w-4 h-4"></i> Beranda
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-gray-50 border-t border-gray-100 px-7 sm:px-8 py-4 flex items-center justify-between">
            <p class="text-xs text-gray-500">Belum punya akun? <span class="text-gray-400">Hubungi Admin</span></p>
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Secure login
            </span>
        </div>
    </div>

    <p class="lg:hidden text-center text-xs text-gray-400 mt-6">
        Dengan masuk, Anda menyetujui Syarat & Kebijakan Privasi
    </p>

    <script>
        function togglePwd(){
            const i=document.getElementById('password'), a=document.getElementById('eye-on'), b=document.getElementById('eye-off');
            if(i.type==='password'){ i.type='text'; a.classList.add('hidden'); b.classList.remove('hidden'); } else { i.type='password'; b.classList.add('hidden'); a.classList.remove('hidden'); }
        }
        if(window.lucide&&lucide.icons) lucide.createIcons({icons: lucide.icons});
    </script>
</x-guest-layout>
