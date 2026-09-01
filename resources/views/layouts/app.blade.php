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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-white border-r border-gray-200 flex-shrink-0" x-data="{ open: false }">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900 tracking-tight">Mono<span class="text-brand-600">link</span></a>
                <button @click="open = !open" class="md:hidden text-gray-500">
                    <i data-lucide="menu"></i>
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

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Topbar (Mobile Profile Link) -->
            <header class="bg-white border-b border-gray-200 py-3 px-6 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                <a href="{{ route('public.profile', auth()->user()->profile->username) }}" target="_blank" class="text-sm font-medium text-brand-600 bg-brand-50 px-3 py-1.5 rounded-full hover:bg-brand-100 transition">
                    View Live Profile →
                </a>
            </header>

            <div class="p-6 max-w-5xl mx-auto">
                
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
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
