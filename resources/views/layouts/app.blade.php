<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Monolink') }} - Dashboard</title>

    <!-- Tailwind CSS (CDN for simple deployment) & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6', // primary blue
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform: translateY(6px);} to {opacity:1; transform: translateY(0);} }
        /* Professional scrollbar */
        ::-webkit-scrollbar { width:6px; height:6px; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:9999px; }
        ::-webkit-scrollbar-thumb:hover { background:#94a3b8; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 min-h-screen md:h-screen md:overflow-hidden">
    <div class="min-h-screen md:h-screen flex flex-col md:flex-row md:overflow-hidden">
        
        <!-- Sidebar — fixed on desktop, top on mobile -->
        <aside class="w-full md:w-[260px] bg-white border-r border-gray-200/70 flex-shrink-0 md:h-screen md:overflow-y-auto overflow-visible" x-data="{ open: false }">
            <div class="flex items-center justify-between p-5 border-b border-gray-200/70">
                <a href="{{ route('dashboard') }}" class="text-[20px] font-bold text-gray-900 tracking-tight">Mono<span class="text-brand-600">link</span></a>
                <button @click="open = !open" class="md:hidden p-2 text-gray-500 hover:bg-gray-50 rounded-lg transition">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
            
            <nav class="p-4 space-y-1" :class="{'hidden': !open, 'block': open, 'md:block': true}">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Overview
                </a>
                <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.profile') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user" class="w-5 h-5"></i> Profile
                </a>
                <a href="{{ route('dashboard.links') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.links') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="link-2" class="w-5 h-5"></i> Links
                </a>
                <a href="{{ route('dashboard.social') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.social') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="share-2" class="w-5 h-5"></i> Social
                </a>
                <a href="{{ route('dashboard.appearance') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.appearance') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="palette" class="w-5 h-5"></i> Appearance
                </a>
                <a href="{{ route('dashboard.analytics') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.analytics') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i> Analytics
                </a>

                <hr class="my-4 border-gray-200">
                
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-amber-600 hover:bg-amber-50">
                    <i data-lucide="shield" class="w-5 h-5"></i> Admin Panel
                </a>
                @endif
                
                <a href="{{ route('dashboard.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.settings') ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i> Settings
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 text-left">
                        <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content — header + scrollable content -->
        <main class="flex-1 flex flex-col md:h-screen md:overflow-hidden bg-[#f8fafc] min-w-0 min-h-0">
            <!-- Topbar — tetap di atas, tidak ikut scroll -->
            <header class="flex-shrink-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200/70 py-3.5 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-[18px] sm:text-xl font-semibold text-gray-800 tracking-tight truncate">@yield('title', 'Dashboard')</h2>
                    <p class="text-xs text-gray-400 hidden sm:block mt-0.5">Kelola tampilan profesional monolink Anda</p>
                </div>
                <a href="{{ route('public.profile', auth()->user()->profile->username) }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-700 bg-brand-50 px-3.5 py-2 rounded-full hover:bg-brand-100 transition flex-shrink-0">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span class="hidden sm:inline">View Live Profile</span><span class="sm:hidden">Live</span>
                </a>
            </header>

            <!-- Scrollable content — hanya bagian ini yang scroll di desktop -->
            <div id="main-scroll" class="flex-1 md:overflow-y-auto overflow-visible p-4 sm:p-6 lg:p-8 min-h-0">
                <div class="max-w-7xl mx-auto w-full">
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 flex items-center gap-3">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        if(window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons});
    </script>
    @stack('scripts')
</body>
</html>
