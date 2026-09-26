<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $profile->display_name }} | Monolink</title>
    <meta name="description" content="{{ $profile->bio ?? $profile->display_name . ' — Monolink profile' }}">
    <meta property="og:title" content="{{ $profile->display_name }} | Monolink">
    <meta property="og:description" content="{{ $profile->bio ?? '' }}">
    <meta property="og:image" content="{{ $profile->profile_image_url }}">
    <meta name="theme-color" content="{{ ($profile->theme->primary_color ?? '#ffffff') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @php
        $t = $profile->theme;
        $bgType  = $t->background_type  ?? 'SOLID';
        $bgValue = $t->background_value ?? '#071A8C';
        $textColor   = $t->text_color        ?? '#ffffff';
        $primaryColor= $t->primary_color     ?? '#ffffff';
        $btnColor    = $t->button_color      ?? '#ffffff';
        $btnText     = $t->button_text_color ?? '#ffffff';
        $btnStyle    = $t->button_style      ?? 'OUTLINE';
        $font        = $t->font_family       ?? 'Inter';
        $layout      = strtolower(trim($t->layout ?? 'center'));
        if (!in_array($layout, ['center','left','right'])) $layout = 'center';
        $gradDir     = $t->gradient_direction ?? 'to bottom right';
        $gradColors  = $t->gradient_colors   ?? null;

        $isCenter = $layout === 'center';
        $isLeft = $layout === 'left';
        $isRight = $layout === 'right';
        $wrapAlign = $isLeft ? 'items-start text-left' : ($isRight ? 'items-end text-right' : 'items-center text-center');
        $contentAlign = $isLeft ? 'text-left' : ($isRight ? 'text-right' : 'text-center');
        $contentMargin = $isLeft ? 'mr-auto' : ($isRight ? 'ml-auto' : 'mx-auto');
        $flexJustify = $isLeft ? 'justify-start' : ($isRight ? 'justify-end' : 'justify-center');

        // Determine button background / border mapping for CSS variables (Linktree tokens)
        $isOutline = $btnStyle === 'OUTLINE' || $btnStyle === 'NEON';
        $buttonBg = $isOutline ? 'transparent' : $btnColor;
        $borderColor = $btnColor;
        // radius mapping: PILL=9999, SQUARE=10px, otherwise 28px/14px
        $radiusMap = [
            'PILL' => '9999px',
            'SQUARE' => '10px',
            'ROUNDED' => '14px',
            'GLASS' => '14px',
            'SHADOW' => '14px',
            'NEON' => '14px',
            'OUTLINE' => '28px',
        ];
        $buttonRadius = $radiusMap[$btnStyle] ?? '28px';

        // profileBackground is solid bgValue, for gradient/image use fallback navy
        $profileBg = ($bgType === 'SOLID') ? $bgValue : '#071A8C';
        $desktopFrameColor = "color-mix(in srgb, #0A1C8C 88%, white 12%)";
    @endphp

    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $font) }}:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* ===== RESET — verbatim dari Linktree paste ===== */
        *, ::before, ::after { box-sizing: inherit; }
        *, ::after, ::before {
            --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0;
            --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1;
            --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000;
        }
        *, ::after, ::before { box-sizing: border-box; border-width: 0px; border-style: solid; border-color: initial; border-image: initial; }
        html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, menu, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, main, menu, nav, output, ruby, section, summary, time, mark, audio, video {
            margin: 0px; padding: 0px; border: 0px; font: inherit; vertical-align: baseline;
        }

        /* ===== TOKENS — 1:1 dari element.style html { ... } paste ===== */
        html {
            font-size: 16px;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
            line-height: 1.5;
            text-size-adjust: 100%;
            tab-size: 4;
            font-family: '{{ $font }}', 'Link Sans Product', Inter, Helvetica, Arial, system-ui, -apple-system, sans-serif;
            font-variation-settings: "PRDT" 300;
            font-variant-ligatures: discretionary-ligatures;
            scrollbar-width: auto;
            scrollbar-color: color-mix(in srgb, var(--background-contrast-color, #000000) 50%, transparent) color-mix(in srgb, var(--profileBackground, #ffffff) 80%, black);
            /* === Linktree tokens === */
            --button-style-text: {{ $btnText }};
            --button-style-background: {{ $buttonBg }};
            --button-style-background-hover: color-mix(in srgb, var(--button-style-background) 93%, var(--background-contrast-color) 7%);
            --button-style-border: 1px solid var(--button-style-border-color);
            --button-style-border-color: {{ $borderColor }};
            --button-style-shadow: none;
            --button-style-shadow-color: #000000;
            --button-style-contrast-color: #000000;
            --button-style-radius: {{ $buttonRadius }};
            --button-style-inner-radius: min(var(--button-style-radius), max(4px, calc(var(--button-style-radius) - 8px)));
            --link-content-radius: min(var(--button-style-inner-radius), 14px);
            --button-style-skeleton-color: rgba(0, 0, 0, 0.05);
            --desktop-frame-color: {{ $desktopFrameColor }};
            --header-font-family: '{{ $font }}', 'Link Sans Product', sans-serif;
            --header-font-weight: 600;
            --header-text-color: {{ $textColor }};
            --profile-container-desktop-width: 580px;
            --header-bio-font-size: 16px;
            --link-gap: 14px;
            --button-style-inner-padding: var(--link-gap);
            --link-preview-thumbnail-width: 160px;
            --linkRadius: var(--button-style-radius);
            --preview-dim-opacity: 0.4;
            --preview-dim-duration: 240ms;
            --preview-dim-easing: cubic-bezier(0.2, 0, 0, 1);
            --background-contrast-color: #ffffff;
            --backdrop-contrast-color: #ffffff;
            --backdrop-contrast-color-inverse: #000000;
            --bodyText: {{ $textColor }};
            --linkBackground: var(--button-style-background);
            --linkText: var(--button-style-text);
            --linkHoverBackground: var(--button-style-background-hover);
            --linkHoverText: var(--button-style-text);
            --profileBackground: {{ $profileBg }};
            --defaultAvatarBackground: #061492;
            --defaultAvatarText: #FFFFFF;
            --profileTitleText: {{ $textColor }};
            --profileDescriptionText: {{ $textColor }};
            --socialLinkFill: {{ $textColor }};
            --bannerText: #071A8C;
            --linkShadow: #000;
            --profileFontFamilyPrimary: '{{ $font }}', 'Link Sans Product', sans-serif;
            --profileFontFamilySecondary: Inter, sans-serif;
            --profileFontWeightNormal: 500;
            --profileFontWeightBold: 700;
            --profileDescriptionFontWeight: 500;
            --linkTextFontWeight: 500;
            --headerFontWeight: 700;
            --embedLinkTextFontWeight: 500;
            --signupSubmitTextFontWeight: 700;
        }

        /* ===== BODY — pakai --profileBackground persis Linktree ===== */
        body {
            font-family: var(--profileFontFamilyPrimary);
            color: var(--bodyText);
            min-height: 100dvh;
            margin: 0;
            position: relative;
            isolation: isolate;
            background-color: var(--profileBackground);
            @if($bgType === 'GRADIENT')
                @php $stops = $gradColors ? implode(', ', $gradColors) : $bgValue; @endphp
                background-image: linear-gradient({{ $gradDir }}, {{ $stops }});
                background-attachment: fixed;
            @elseif($bgType === 'IMAGE')
                background-color: #0f172a;
                background-image: url('{{ $t->background_image ? asset("storage/".$t->background_image) : $bgValue }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            @endif
        }
        @if($bgType === 'IMAGE')
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.10) 0%, rgba(0,0,0,0.36) 100%);
            z-index: -1;
            pointer-events: none;
        }
        @endif

        /* ===== UTILITIES — untuk alignment left/center/right (tanpa Tailwind) ===== */
        .flex { display: flex; }
        .inline-flex { display: inline-flex; }
        .items-start { align-items: flex-start; }
        .items-center { align-items: center; }
        .items-end { align-items: flex-end; }
        .justify-start { justify-content: flex-start; }
        .justify-center { justify-content: center; }
        .justify-end { justify-content: flex-end; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .w-full { width: 100%; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .mr-auto { margin-right: auto; }
        .ml-auto { margin-left: auto; }
        .gap-1\.5 { gap: 6px; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .flex-1 { flex: 1 1 0%; }
        .flex-shrink-0 { flex-shrink: 0; }

        /* ===== LAYOUT — 580px center, isi yang geser left/center/right ===== */
        .profile-wrap {
            width: 100%;
            max-width: var(--profile-container-desktop-width);
            margin: 0 auto;
            padding: 40px 16px 32px;
            display: flex;
            flex-direction: column;
            min-height: 100dvh;
        }
        @media (min-width: 640px) {
            .profile-wrap { padding: 56px 24px 48px; max-width: var(--profile-container-desktop-width); }
        }

        .avatar-wrap { position: relative; flex-shrink: 0; display: inline-flex; }
        .avatar-img {
            width: 96px; height: 96px; border-radius: 9999px; object-fit: cover; display: block;
            border: 3px solid #ffffff;
            box-shadow: 0 8px 30px rgba(0,0,0,0.14), 0 2px 8px rgba(0,0,0,0.10);
            background: var(--defaultAvatarBackground);
        }

        .display-name {
            font-family: var(--header-font-family);
            font-weight: var(--headerFontWeight);
            font-size: 22px; letter-spacing: -0.025em; line-height: 1.15; margin-top: 18px;
            color: var(--profileTitleText); text-wrap: balance; text-align: inherit;
        }
        @media (min-width: 640px) { .display-name { font-size: 26px; } }
        .bio {
            margin-top: 10px; font-family: var(--profileFontFamilySecondary);
            font-size: var(--header-bio-font-size); font-weight: var(--profileDescriptionFontWeight);
            line-height: 1.65; opacity: 0.92; max-width: 40ch; text-wrap: balance;
            color: var(--profileDescriptionText); text-align: inherit;
        }
        .meta-row {
            margin-top: 14px; display: flex; flex-wrap: wrap; gap: 10px;
            font-size: 13px; opacity: 0.78; align-items: center; color: var(--bodyText);
        }
        .meta-row a { color: var(--bodyText); text-decoration: none; }
        .meta-row a:hover { color: var(--bodyText); text-decoration: underline; text-underline-offset: 3px; opacity: 1; }

        .social-row { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 20px; }
        .social-btn {
            width: 42px; height: 42px; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center;
            background: transparent; border: 1px solid color-mix(in srgb, var(--socialLinkFill) 20%, transparent);
            color: var(--socialLinkFill);
            transition: all 0.22s cubic-bezier(.2,.8,.2,1); text-decoration: none;
        }
        .social-btn:hover {
            transform: translateY(-2px) scale(1.05);
            background: var(--socialLinkFill); color: var(--profileBackground);
            border-color: var(--socialLinkFill);
        }
        .social-btn i { width: 18px; height: 18px; }

        .links-stack {
            width: 100%; margin-top: 28px; display: flex; flex-direction: column;
            gap: var(--link-gap); text-align: inherit; align-items: stretch;
        }

        /* ===== BUTTON — persis Linktree: var(--button-style-*) ===== */
        .link-btn {
            display: flex; align-items: center; justify-content: center; gap: 12px;
            width: 100%; min-height: 56px; padding: 16px 20px;
            font-family: var(--profileFontFamilySecondary);
            font-weight: var(--linkTextFontWeight); font-size: 15.5px; line-height: 1.35; letter-spacing: -0.01em;
            text-decoration: none; position: relative;
            background: var(--linkBackground); color: var(--linkText);
            border: var(--button-style-border); border-radius: var(--linkRadius);
            box-shadow: var(--button-style-shadow);
            transition: transform 0.2s cubic-bezier(.2,.8,.2,1), box-shadow 0.2s ease, background 0.2s ease, filter 0.2s ease;
            will-change: transform; justify-content: center; text-align: center;
        }
        .link-btn:hover { transform: translateY(-2px) scale(1.005); background: var(--linkHoverBackground); color: var(--linkHoverText); }
        .link-btn:active { transform: translateY(0px) scale(0.99); }
        .link-btn .link-arrow { opacity: 0.62; flex-shrink: 0; transition: transform 0.2s ease, opacity 0.2s ease; }
        .link-btn:hover .link-arrow { opacity: 1; transform: translate(2px,-2px); }

        /* Variant overrides yang tetap pakai token — untuk Glass/Shadow/Neon tetap mix */
        @if($btnStyle === 'GLASS')
        .link-btn { background: color-mix(in srgb, var(--button-style-background) 90%, transparent); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.55); box-shadow: 0 8px 30px rgba(0,0,0,0.10); }
        @endif
        @if($btnStyle === 'SHADOW')
        .link-btn { box-shadow: 5px 5px 0 rgba(0,0,0,0.18); }
        .link-btn:hover { box-shadow: 7px 7px 0 rgba(0,0,0,0.18); }
        @endif
        @if($btnStyle === 'NEON')
        .link-btn { box-shadow: 0 0 18px color-mix(in srgb, var(--button-style-border-color) 40%, transparent), inset 0 0 8px rgba(0,0,0,0.05); }
        .link-btn:hover { background: var(--button-style-border-color); color: var(--profileBackground); box-shadow: 0 0 26px color-mix(in srgb, var(--button-style-border-color) 70%, transparent); }
        @endif

        .maps-card {
            width: 100%; background: #ffffff; border-radius: var(--link-content-radius);
            border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 8px 30px rgba(0,0,0,0.09);
            overflow: hidden; padding: 0;
        }
        .maps-card-head {
            display: flex; align-items: center; justify-content: center; gap: 12px;
            padding: 16px 20px; min-height: 56px;
        }
        .maps-card-head i { width: 16px; height: 16px; color: #0f172a; flex-shrink: 0; }
        .maps-card-head .maps-title {
            flex: 1; text-align: center; font-size: 15.5px; font-weight: 500; line-height: 1.35; letter-spacing: -0.01em;
            font-family: var(--profileFontFamilySecondary); color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 0 4px;
        }
        .maps-card-head .maps-arrow { opacity: 0.52; flex-shrink: 0; }
        .maps-card a.maps-open { font-size: 11px; font-weight: 600; color: #ffffff; text-decoration: none; padding: 4px 10px; border-radius: 99px; background: var(--profileBackground); }
        .maps-card a.maps-open:hover { filter: brightness(1.08); }
        .maps-card iframe { width: 100%; height: 240px; border-radius: 12px; border: none; display: block; }

        footer { margin-top: 40px; padding-bottom: 18px; text-align: center; }
        .footer-link { font-size: 11px; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; opacity: 0.55; text-decoration: none; color: var(--bodyText); display: inline-flex; align-items: center; gap: 6px; transition: opacity 0.2s; }
        .footer-link:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <main class="profile-wrap {{ $wrapAlign }}">
        <div class="flex {{ $flexJustify }} w-full">
            <div class="avatar-wrap">
                <img src="{{ $profile->profile_image_url }}"
                     alt="{{ $profile->display_name }}"
                     class="avatar-img"
                     loading="eager"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($profile->display_name) }}&background=061492&color=fff&size=200'">
            </div>
        </div>

        <h1 class="display-name {{ $contentAlign }} w-full">{{ $profile->display_name }}</h1>

        @if($profile->bio)
            <p class="bio {{ $contentAlign }} {{ $contentMargin }}">{{ $profile->bio }}</p>
        @endif

        @if($profile->location || $profile->website)
            <div class="meta-row {{ $flexJustify }} {{ $contentAlign }} w-full">
                @if($profile->location)
                    <span class="inline-flex items-center gap-1.5">
                        <i data-lucide="map-pin" style="width:14px;height:14px;"></i> {{ $profile->location }}
                    </span>
                @endif
                @if($profile->website)
                    <a href="{{ $profile->website }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5">
                        <i data-lucide="globe" style="width:14px;height:14px;"></i>
                        {{ parse_url($profile->website, PHP_URL_HOST) ?? 'Website' }}
                    </a>
                @endif
            </div>
        @endif

        @if($profile->socialLinks->count() > 0)
            <div class="social-row {{ $flexJustify }} w-full">
                @foreach($profile->socialLinks as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer"
                       class="social-btn" aria-label="{{ $social->platform }}" title="{{ $social->platform }}">
                        <i data-lucide="{{ $social->lucide_icon }}" style="stroke-width:1.75"></i>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="links-stack">
            @forelse($profile->links->where('is_active', true) as $link)
                @php $isMaps = in_array(strtoupper($link->type ?? ''), ['GOOGLE_MAPS','MAP']); @endphp
                @if($isMaps)
                    <div class="maps-card" onclick="window.open('{{ route('link.redirect', $link->id) }}','_blank')" style="cursor:pointer;" role="link" tabindex="0" aria-label="{{ $link->title }}">
                        <div class="maps-card-head">
                            <i data-lucide="map-pin"></i>
                            <span class="maps-title">{{ $link->title }}</span>
                            <i data-lucide="arrow-up-right" class="maps-arrow w-[16px] h-[16px]"></i>
                        </div>
                        @php
                            $raw = trim($link->url);
                            $resolved = $raw;
                            $latLng = null;
                            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $resolved, $m)) {
                                $latLng = $m[1] . ',' . $m[2];
                            } elseif (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $resolved, $m)) {
                                $latLng = $m[1] . ',' . $m[2];
                            }
                            $host = strtolower((string) parse_url($resolved, PHP_URL_HOST));
                            $isGoogleHost = $host === 'google.com' || str_ends_with($host, '.google.com')
                                || $host === 'goo.gl' || str_ends_with($host, '.goo.gl');
                            if ($latLng) {
                                $src = 'https://www.google.com/maps?q=' . $latLng . '&hl=en&z=15&output=embed';
                            } elseif ($isGoogleHost && str_contains($resolved, '/embed')) {
                                $src = $resolved;
                            } elseif ($isGoogleHost && str_contains($resolved, '/maps')) {
                                $sep = str_contains($resolved, '?') ? '&' : '?';
                                $src = $resolved . $sep . 'output=embed';
                            } else {
                                $src = 'https://www.google.com/maps?q=' . urlencode($resolved) . '&z=15&output=embed';
                            }
                        @endphp
                        <div style="padding: 0 10px 10px;">
                            <iframe src="{{ $src }}" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="{{ $link->title }}"></iframe>
                        </div>
                    </div>
                @else
                    <a href="{{ route('link.redirect', $link->id) }}" target="_blank"
                       rel="noopener noreferrer" class="link-btn group">
                        @if($link->thumbnail)
                            <img src="{{ asset('storage/'.$link->thumbnail) }}" alt="" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 shadow-sm">
                        @elseif($link->icon)
                            <span class="flex-shrink-0 text-[17px] leading-none">{{ $link->icon }}</span>
                        @endif
                        <span class="flex-1 text-center truncate px-1">{{ $link->title }}</span>
                        <i data-lucide="arrow-up-right" class="link-arrow w-[16px] h-[16px]"></i>
                    </a>
                @endif
            @empty
                <div class="text-center py-10 px-6 rounded-[16px] border border-dashed bg-white/10 backdrop-blur" style="border-color: color-mix(in srgb, var(--bodyText) 18%, transparent); color: var(--bodyText)">
                    <i data-lucide="link-2" class="w-6 h-6 mx-auto mb-2 opacity-60"></i>
                    <p class="text-sm font-semibold">Belum ada link</p>
                    <p class="text-xs mt-1 opacity-70">Link akan tampil di sini setelah ditambahkan dari dashboard.</p>
                </div>
            @endforelse
        </div>

        <footer class="text-center w-full flex justify-center">
            <a href="https://monodev.tech/" target="_blank" rel="noopener"
               class="footer-link">
                <span>Powered by</span><span style="font-weight:800; letter-spacing:0.06em; text-transform:none; opacity:1;">Monodev</span>
            </a>
        </footer>
    </main>

    <script>if(window.lucide && lucide.icons) lucide.createIcons({icons: lucide.icons, attrs:{'stroke-width':1.75}});</script>
</body>
</html>
