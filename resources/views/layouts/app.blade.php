<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDesc ?? 'Nucomu Cafe — Coffee & Dessert di Tulungagung. Tempat nyaman untuk menikmati dessert otentik, kopi, dan makanan dengan konsep monokrom elegan.' }}">
    <title>@yield('title', 'Nucomu Cafe') — Coffee & Dessert Tulungagung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        'nc-black': '#0a0a0a',
                        'nc-gray-dark': '#1a1a1a',
                        'nc-gray': '#4a4a4a',
                        'nc-gray-mid': '#888888',
                        'nc-gray-light': '#d4d4d4',
                        'nc-gray-pale': '#f0f0f0',
                        'nc-white': '#fafafa',
                    },
                    letterSpacing: {
                        'widest2': '0.3em',
                    }
                }
            }
        }
    </script>
    <style>
        /* Topographic pattern */
        .topo-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400'%3E%3Cpath d='M0 200 Q50 180 100 200 Q150 220 200 200 Q250 180 300 200 Q350 220 400 200' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 220 Q50 200 100 220 Q150 240 200 220 Q250 200 300 220 Q350 240 400 220' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 180 Q50 160 100 180 Q150 200 200 180 Q250 160 300 180 Q350 200 400 180' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 160 Q50 140 100 160 Q150 180 200 160 Q250 140 300 160 Q350 180 400 160' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 240 Q50 220 100 240 Q150 260 200 240 Q250 220 300 240 Q350 260 400 240' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 140 Q50 120 100 140 Q150 160 200 140 Q250 120 300 140 Q350 160 400 140' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 260 Q50 240 100 260 Q150 280 200 260 Q250 240 300 260 Q350 280 400 260' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 120 Q50 100 100 120 Q150 140 200 120 Q250 100 300 120 Q350 140 400 120' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 280 Q50 260 100 280 Q150 300 200 280 Q250 260 300 280 Q350 300 400 280' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 300 Q50 280 100 300 Q150 320 200 300 Q250 280 300 300 Q350 320 400 300' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3Cpath d='M0 100 Q50 80 100 100 Q150 120 200 100 Q250 80 300 100 Q350 120 400 100' fill='none' stroke='%23000' stroke-width='0.5' opacity='0.06'/%3E%3C/svg%3E");
        }
        .nav-link { @apply text-nc-gray hover:text-nc-black transition-colors duration-200 text-sm font-medium tracking-wide; }
        .btn-primary { @apply bg-nc-black text-white px-6 py-3 text-sm font-semibold tracking-widest hover:bg-nc-gray-dark transition-all duration-300; }
        .btn-outline { @apply border border-nc-black text-nc-black px-6 py-3 text-sm font-semibold tracking-widest hover:bg-nc-black hover:text-white transition-all duration-300; }
        .section-title { @apply text-3xl md:text-4xl font-serif font-bold tracking-wide text-nc-black; }
        .card-hover { @apply transition-all duration-300 hover:-translate-y-1 hover:shadow-lg; }
        /* Flash messages */
        .alert-success { @apply bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded; }
        .alert-error   { @apply bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded; }

        /* Aesthetic Scroll Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            filter: blur(4px);
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1), filter 0.85s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform, filter;
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-36px);
            filter: blur(4px);
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1), filter 0.85s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform, filter;
        }
        .reveal-left.revealed {
            opacity: 1;
            transform: translateX(0);
            filter: blur(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(36px);
            filter: blur(4px);
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1), filter 0.85s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform, filter;
        }
        .reveal-right.revealed {
            opacity: 1;
            transform: translateX(0);
            filter: blur(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.92);
            filter: blur(4px);
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1), filter 0.85s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform, filter;
        }
        .reveal-scale.revealed {
            opacity: 1;
            transform: scale(1);
            filter: blur(0);
        }

        /* Stagger Delays */
        .delay-75  { transition-delay: 75ms; }
        .delay-100 { transition-delay: 100ms; }
        .delay-150 { transition-delay: 150ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-250 { transition-delay: 250ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
        .delay-500 { transition-delay: 500ms; }

        @media (prefers-reduced-motion: reduce) {
            .reveal, .reveal-left, .reveal-right, .reveal-scale {
                opacity: 1 !important;
                transform: none !important;
                filter: none !important;
                transition: none !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-nc-white text-nc-black font-sans antialiased">

    {{-- NAVBAR --}}
    @include('public.partials.navbar')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div class="fixed top-20 right-4 z-50 alert-success shadow-md max-w-sm" id="flash-msg">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="fixed top-20 right-4 z-50 alert-error shadow-md max-w-sm" id="flash-msg">
        {{ session('error') }}
    </div>
    @endif

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('public.partials.footer')

    <script>
        // Auto-hide flash message
        setTimeout(() => {
            const el = document.getElementById('flash-msg');
            if (el) el.style.display = 'none';
        }, 4000);

        // Mobile menu toggle
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Aesthetic Scroll Reveal
        document.addEventListener('DOMContentLoaded', () => {
            // Auto stagger children if parent container has data-stagger
            document.querySelectorAll('[data-stagger]').forEach(parent => {
                const step = parseInt(parent.dataset.stagger) || 100;
                Array.from(parent.children).forEach((child, idx) => {
                    if (!child.style.transitionDelay && (
                        child.classList.contains('reveal') || 
                        child.classList.contains('reveal-scale') || 
                        child.classList.contains('reveal-left') || 
                        child.classList.contains('reveal-right')
                    )) {
                        child.style.transitionDelay = `${idx * step}ms`;
                    }
                });
            });

            const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
            if (!('IntersectionObserver' in window)) {
                revealElements.forEach(el => el.classList.add('revealed'));
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    } else {
                        // Reset class agar animasi dapat muncul berulang kali setiap kali discroll kembali
                        entry.target.classList.remove('revealed');
                    }
                });
            }, {
                root: null,
                rootMargin: '0px 0px -30px 0px',
                threshold: 0.08
            });

            revealElements.forEach(el => observer.observe(el));
        });
    </script>
    @stack('scripts')
</body>
</html>
