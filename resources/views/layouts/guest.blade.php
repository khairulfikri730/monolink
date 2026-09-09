<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Monolink') }} — Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: { brand: { 50:'#eff6ff',100:'#dbeafe',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',900:'#1e3a8a' } },
                        fontFamily: { sans: ['Inter','system-ui','sans-serif'] }
                    }
                }
            }
        </script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
        <style>body{font-family:'Inter',system-ui,sans-serif} .glass{backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)}</style>
    </head>
    <body class="min-h-screen bg-[#f8fafc] antialiased">
        <div class="min-h-screen flex flex-col lg:flex-row">
            <!-- LEFT — Brand / Preview (hidden on mobile) -->
            <div class="hidden lg:flex lg:w-[54%] bg-[#071A8C] relative overflow-hidden flex-col p-8 xl:p-10 text-white">
                <!-- subtle glow -->
                <div class="absolute -top-32 -right-32 w-[520px] h-[520px] bg-white/10 rounded-full blur-[80px] pointer-events-none"></div>
                <div class="absolute -bottom-40 -left-40 w-[560px] h-[560px] bg-[#3b82f6]/20 rounded-full blur-[90px] pointer-events-none"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-white/[0.04] via-transparent to-black/10 pointer-events-none"></div>

                <div class="relative flex items-center justify-between">
                    <a href="/" class="flex items-center gap-2">
                        <span class="w-9 h-9 rounded-xl bg-white text-[#071A8C] flex items-center justify-center font-extrabold text-[16px] tracking-tight">M</span>
                        <span class="text-[20px] font-bold tracking-tight">Mono<span class="font-extrabold">link</span></span>
                        <span class="ml-2 text-[10px] tracking-[0.18em] uppercase opacity-60 border border-white/20 px-2 py-0.5 rounded-full">Bio Platform</span>
                    </a>
                    <a href="/" class="hidden xl:inline-flex items-center gap-1.5 text-xs font-medium text-white/70 hover:text-white bg-white/10 hover:bg-white/15 border border-white/15 px-3 py-1.5 rounded-full transition">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to home
                    </a>
                </div>

                <div class="relative flex-1 flex flex-col justify-center max-w-[560px] mx-auto w-full py-8">
                    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/15 text-white/90 text-xs font-medium px-3 py-1.5 rounded-full w-fit">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Trusted by creators, brands & UMKM
                    </div>
                    <h1 class="mt-5 text-[34px] xl:text-[40px] font-bold leading-[0.95] tracking-tight">
                        Satu link<br>
                        <span class="text-white/80 font-medium">untuk semua</span><br>
                        karya Anda.
                    </h1>
                    <p class="mt-4 text-[15px] leading-relaxed text-white/70 max-w-[44ch]">
                        Buat halaman bio profesional, atur link, sosial & tampilan tanpa coding. <span class="text-white font-medium">Simple to create. Easy to customize.</span>
                    </p>

                    <!-- Phone preview mock -->
                    <div class="mt-8 flex gap-4 items-start">
                        <div class="relative bg-white rounded-[28px] p-2.5 shadow-[0_20px_60px_rgba(0,0,0,0.35)] w-[168px] flex-shrink-0">
                            <div class="bg-[#071A8C] rounded-[20px] p-4 pt-6 text-center text-white overflow-hidden relative">
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                                <div class="w-14 h-14 rounded-full bg-white mx-auto border-2 border-white/30 shadow-md overflow-hidden">
                                    <img src="https://i.pravatar.cc/112?img=12" class="w-full h-full object-cover" alt="avatar">
                                </div>
                                <p class="mt-3 text-[13px] font-bold tracking-tight">Aura Ashel</p>
                                <p class="text-[11px] text-white/70">Digital Creator • Padang</p>
                                <div class="mt-3 space-y-2">
                                    <div class="h-9 rounded-full bg-transparent border border-white text-white flex items-center justify-center text-xs font-medium">Instagram</div>
                                    <div class="h-9 rounded-full bg-white text-[#071A8C] flex items-center justify-center text-xs font-bold">WhatsApp</div>
                                    <div class="h-9 rounded-full bg-transparent border border-white text-white flex items-center justify-center text-xs font-medium">Website</div>
                                </div>
                                <p class="mt-3 text-[9px] tracking-widest uppercase opacity-50">Preview</p>
                            </div>
                        </div>
                        <div class="flex-1 space-y-3 pt-2">
                            <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-2xl p-3 backdrop-blur">
                                <span class="w-9 h-9 rounded-xl bg-white text-[#071A8C] flex items-center justify-center flex-shrink-0"><i data-lucide="palette" class="w-4 h-4"></i></span>
                                <div>
                                    <p class="text-sm font-semibold leading-none">Appearance</p>
                                    <p class="text-xs text-white/60">Navy • Outline 28px</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-2xl p-3 backdrop-blur">
                                <span class="w-9 h-9 rounded-xl bg-white text-emerald-600 flex items-center justify-center flex-shrink-0"><i data-lucide="bar-chart-3" class="w-4 h-4"></i></span>
                                <div>
                                    <p class="text-sm font-semibold leading-none">Analytics</p>
                                    <p class="text-xs text-white/60">Views & clicks realtime</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-2xl p-3 backdrop-blur">
                                <span class="w-9 h-9 rounded-xl bg-white text-violet-600 flex items-center justify-center flex-shrink-0"><i data-lucide="share-2" class="w-4 h-4"></i></span>
                                <div>
                                    <p class="text-sm font-semibold leading-none">Share & QR</p>
                                    <p class="text-xs text-white/60">domain.com/username</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative flex items-center justify-between text-xs text-white/50 pt-6 border-t border-white/10">
                    <span>© {{ date('Y') }} Monolink • Monodev</span>
                    <span class="hidden sm:inline"> Aman • Cepat • No-code</span>
                </div>
            </div>

            <!-- RIGHT — Form -->
            <div class="flex-1 flex flex-col bg-[#f8fafc] min-h-screen lg:min-h-0">
                <!-- Mobile top bar -->
                <div class="lg:hidden flex items-center justify-between p-5 bg-white border-b border-gray-200/70">
                    <a href="/" class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-[#071A8C] text-white flex items-center justify-center font-extrabold text-sm">M</span>
                        <span class="font-bold tracking-tight text-gray-900">Mono<span class="text-[#071A8C]">link</span></span>
                    </a>
                    <a href="/" class="text-xs font-medium text-gray-500 hover:text-gray-700">Home</a>
                </div>

                <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
                    <div class="w-full max-w-[420px]">
                        {{ $slot }}
                    </div>
                </div>

                <p class="hidden lg:block text-center text-xs text-gray-400 pb-6">
                    Dengan masuk, Anda menyetujui <a href="#" class="underline hover:text-gray-600">Syarat</a> & <a href="#" class="underline hover:text-gray-600">Kebijakan Privasi</a>
                </p>
            </div>
        </div>
        <script>if(window.lucide&&lucide.icons) lucide.createIcons({icons: lucide.icons});</script>
    </body>
</html>
