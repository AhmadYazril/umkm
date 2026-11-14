<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0C0C0C">
    <meta name="color-scheme" content="dark">
    <style>:root{color-scheme:dark;}html,body{background-color:#0C0C0C;color:#F5F5F5;}</style>
    <title>@yield('title', 'Nucomu Cafe — Tempat Kembali, Rasa yang Dirindukan | Tulungagung')</title>
    <meta name="description" content="@yield('meta_description', 'Nucomu Cafe — Kafe bernuansa monokrom hangat di Tulungagung untuk menikmati kopi single origin, dessert artisanal, dan waktu yang tenang.')">

    <!-- Anti-flicker Early State Resolver for Nucomu Opening Intro (Main Page Only) -->
    @if(request()->routeIs('home') || request()->is('/'))
    <script>
        (function() {
            try {
                if (sessionStorage.getItem('nucomu_intro_seen') === '1') {
                    document.documentElement.classList.add('nucomu-intro-seen');
                } else {
                    document.documentElement.classList.add('nucomu-intro-active');
                }
            } catch (e) {
                document.documentElement.classList.add('nucomu-intro-seen');
            }
        })();
    </script>
    <style>
        html.nucomu-intro-seen #nucomu-intro-overlay { display: none !important; }
        html.nucomu-intro-active { overflow: hidden !important; }
        html.nucomu-intro-active body { overflow: hidden !important; }
    </style>
    <noscript>
        <style>
            #nucomu-intro-overlay { display: none !important; }
            html.nucomu-intro-active, html.nucomu-intro-active body { overflow: auto !important; }
        </style>
    </noscript>

    <!-- LCP Preload for Hero Welcome Slide -->
    <link rel="preload" as="image" href="{{ asset('images/hero/hero-welcome.webp') }}" type="image/webp" fetchpriority="high">
    @endif

    <!-- Google Fonts: Playfair Display (Serif) & Montserrat (Sans-Serif) - Modern Organic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700;1,800&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Material Symbols Outlined -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>

    <!-- Tailwind CSS CDN with Nucomu Palette Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'bg-page': '#0C0C0C',
                        'bg-section-alt': '#101010',
                        'accent-solid': '#F5F2EE',
                        'ink-black': '#F5F5F5',
                        'charcoal': '#B5B5B5',
                        'warm-ivory': '#0C0C0C',
                        'pure-white': '#F5F5F5',
                        'light-gray': 'rgba(255,255,255,0.14)',
                        'medium-gray': '#B5B5B5',
                        'soft-beige': 'rgba(255,255,255,0.08)',
                        'warm-stone': 'rgba(255,255,255,0.22)',
                        'espresso-brown': '#F5F2EE',
                        'espresso-dark': '#141414',
                        'accent-gold': '#B5B5B5',
                    },
                    fontFamily: {
                        sans: ['var(--font-sans)', 'system-ui', '-apple-system', 'sans-serif'],
                        body: ['var(--font-sans)', 'system-ui', '-apple-system', 'sans-serif'],
                        heading: ['var(--font-heading)', 'Georgia', 'serif'],
                        display: ['var(--font-heading)', 'Georgia', 'serif'],
                        editorial: ['var(--font-heading)', 'Georgia', 'serif'],
                        serif: ['var(--font-heading)', 'Georgia', 'serif'],
                        script: ['var(--font-heading)', 'Georgia', 'serif'],
                    },
                    letterSpacing: {
                        'editorial-widest': '0.15em',
                        'editorial-tighter': '-0.01em',
                    }
                }
            }
        }
    </script>

    <!-- Custom Nucomu CSS -->
    <link rel="stylesheet" href="{{ asset('css/nucomu.css') }}">

    <!-- Page-specific styles -->
    @stack('styles')
</head>
<body class="bg-[#0C0C0C] text-[#F5F5F5] font-body antialiased selection:bg-[#F5F2EE] selection:text-[#111111]">

    <!-- Opening Intro / Preloader (Main Page Only) -->
    @if(request()->routeIs('home') || request()->is('/'))
        @include('home.partials.intro')
    @endif

    <!-- Header Navigation -->
    @include('components.navbar')

    <!-- Main Content Flow -->
    <main>
        @yield('content')
    </main>

    <!-- Global Footer -->
    @include('components.footer')

    <!-- Global Interactive Modals & Cart Drawer -->
    @include('home.modals.lightbox-modal')
    @include('home.modals.service-detail-modal')
    @include('home.modals.reservation-modal')
    @include('components.cart-drawer')

    <!-- Global Client Script Config & Bundle -->
    @php
        $globalContact = \App\Http\Controllers\NucomuController::getContactSettings();
    @endphp
    <script>
        window.NUCOMU_WA_NUMBER = "{{ $globalContact['whatsapp_clean'] ?? config('nucomu.contact.whatsapp_number', '6281234567890') }}";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" defer></script>
    <script>
        window.__SERVER_UPLOAD_LIMITS__ = {
            uploadMaxBytes: {{ \App\Support\FileUploadHelper::getServerUploadLimits()['upload_max_filesize_bytes'] }},
            uploadMaxRaw: '{{ \App\Support\FileUploadHelper::getServerUploadLimits()['upload_max_filesize_raw'] }}',
            postMaxBytes: {{ \App\Support\FileUploadHelper::getServerUploadLimits()['post_max_size_bytes'] }},
            postMaxRaw: '{{ \App\Support\FileUploadHelper::getServerUploadLimits()['post_max_size_raw'] }}'
        };
    </script>
    <script src="{{ asset('js/file-upload-validator.js') }}" defer></script>
    <script src="{{ asset('js/nucomu.js') }}" defer></script>

    <!-- Page-specific scripts -->
    @stack('scripts')
</body>
</html>
