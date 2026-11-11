<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <!-- Google Fonts: Cormorant Garamond, Manrope, Alex Brush & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Manrope:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

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
                        'ink-black': '#191919',
                        'charcoal': '#252525',
                        'warm-ivory': '#F9F9F9',
                        'pure-white': '#FFFFFF',
                        'light-gray': '#E5E5E5',
                        'medium-gray': '#737373',
                        'soft-beige': '#E5E5E5',
                        'warm-stone': '#D4D4D4',
                        'espresso-brown': '#252525',
                        'espresso-dark': '#1A1A1A',
                        'accent-gold': '#555555',
                    },
                    fontFamily: {
                        editorial: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        script: ['"Alex Brush"', 'cursive'],
                        body: ['Manrope', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    },
                    letterSpacing: {
                        'editorial-widest': '0.15em',
                        'editorial-tighter': '-0.02em',
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
<body class="bg-warm-ivory text-charcoal font-body antialiased selection:bg-ink-black selection:text-warm-ivory">

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
