<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile->display_name }} | Monolink</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $profile->theme->font_family ?? 'Inter') }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: '{{ $profile->theme->font_family ?? 'Inter' }}', sans-serif;
            color: {{ $profile->theme->text_color ?? '#000000' }};
            
            @if(($profile->theme->background_type ?? 'SOLID') === 'SOLID')
                background-color: {{ $profile->theme->background_value ?? '#ffffff' }};
            @elseif(($profile->theme->background_type ?? 'SOLID') === 'GRADIENT')
                background-image: linear-gradient(135deg, {{ explode(',', $profile->theme->background_value)[0] ?? '#ffffff' }}, {{ explode(',', $profile->theme->background_value)[1] ?? '#f3f4f6' }});
            @elseif(($profile->theme->background_type ?? 'SOLID') === 'IMAGE')
                background-image: url('{{ $profile->theme->background_value }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            @endif
        }

        .link-card {
            background-color: {{ $profile->theme->button_color ?? '#000000' }};
            color: {{ $profile->theme->button_text_color ?? '#ffffff' }};
            transition: all 0.2s ease-in-out;
        }

        .link-card:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        @if(($profile->theme->button_style ?? 'ROUNDED') === 'ROUNDED')
            .link-card { border-radius: 0.5rem; }
        @elseif(($profile->theme->button_style ?? 'ROUNDED') === 'PILL')
            .link-card { border-radius: 9999px; }
        @elseif(($profile->theme->button_style ?? 'ROUNDED') === 'SQUARE')
            .link-card { border-radius: 0; }
        @elseif(($profile->theme->button_style ?? 'ROUNDED') === 'GLASS')
            .link-card { 
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                color: {{ $profile->theme->text_color ?? '#000000' }};
                border-radius: 0.5rem;
            }
        @elseif(($profile->theme->button_style ?? 'ROUNDED') === 'OUTLINE')
            .link-card { 
                background: transparent;
                border: 2px solid {{ $profile->theme->button_color ?? '#000000' }};
                color: {{ $profile->theme->button_color ?? '#000000' }};
                border-radius: 0.5rem;
            }
            .link-card:hover {
                background: {{ $profile->theme->button_color ?? '#000000' }};
                color: {{ $profile->theme->button_text_color ?? '#ffffff' }};
            }
        @endif
    </style>
</head>
<body class="min-h-screen py-10 px-4 flex flex-col items-center">
    
    <main class="w-full max-w-lg mx-auto flex flex-col items-center text-center">
        <!-- Profile Picture -->
        <img src="{{ $profile->profile_image_url }}" alt="{{ $profile->display_name }}" class="w-24 h-24 rounded-full object-cover border-4 shadow-sm" style="border-color: {{ $profile->theme->primary_color ?? '#ffffff' }}">
        
        <!-- Name & Bio -->
        <h1 class="text-2xl font-bold mt-4">{{ $profile->display_name }}</h1>
        
        @if($profile->bio)
            <p class="mt-2 text-sm opacity-80 max-w-sm">{{ $profile->bio }}</p>
        @endif
        
        @if($profile->location || $profile->website)
            <div class="flex items-center justify-center gap-4 mt-3 text-xs opacity-70">
                @if($profile->location)
                    <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $profile->location }}</span>
                @endif
                @if($profile->website)
                    <a href="{{ $profile->website }}" target="_blank" class="flex items-center gap-1 hover:underline"><i data-lucide="globe" class="w-3 h-3"></i> {{ parse_url($profile->website, PHP_URL_HOST) ?? 'Website' }}</a>
                @endif
            </div>
        @endif

        <!-- Social Icons -->
        @if($profile->socialLinks->count() > 0)
            <div class="flex flex-wrap items-center justify-center gap-4 mt-6">
                @foreach($profile->socialLinks as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer" class="text-2xl hover:scale-110 transition-transform" style="color: {{ $profile->theme->text_color ?? '#000' }}">
                        {{ $social->icon }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Links -->
        <div class="w-full mt-8 flex flex-col gap-4">
            @foreach($profile->links as $link)
                <a href="{{ route('link.redirect', $link->id) }}" target="_blank" rel="noopener noreferrer" class="link-card block w-full py-4 px-6 relative font-medium shadow-sm">
                    <span class="z-10 relative">{{ $link->title }}</span>
                </a>
            @endforeach
        </div>

    </main>

    <footer class="mt-12 text-sm opacity-50 font-medium pb-8">
        <a href="{{ route('dashboard') }}" class="hover:underline">Create your own Monolink</a>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
