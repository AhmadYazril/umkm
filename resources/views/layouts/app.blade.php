<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nucomu Cafe — Tempat Kembali, Rasa yang Dirindukan | Tulungagung')</title>
    <meta name="description" content="@yield('meta_description', 'Nucomu Cafe — Kafe bernuansa monokrom hangat di Tulungagung untuk menikmati kopi single origin, dessert artisanal, dan waktu yang tenang.')">

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
                        'charcoal': '#30302D',
                        'warm-ivory': '#F5F2EC',
                        'pure-white': '#FFFFFF',
                        'light-gray': '#E5E4E0',
                        'medium-gray': '#8A8A86',
                        'soft-beige': '#E7DED1',
                        'warm-stone': '#DAD7CF',
                        'espresso-brown': '#54392B',
                        'espresso-dark': '#3D281D',
                        'accent-gold': '#C2A378',
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
    <script>
        window.NUCOMU_WA_NUMBER = "{{ config('nucomu.contact.whatsapp_number', '6281234567890') }}";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="{{ asset('js/nucomu.js') }}"></script>

    <!-- Page-specific scripts -->
    @stack('scripts')
</body>
</html>
