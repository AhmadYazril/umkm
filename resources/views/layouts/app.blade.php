<!DOCTYPE html>
<html lang="id" class="transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDesc ?? 'Nucomu Cafe — Coffee & Dessert di Tulungagung. Tempat nyaman untuk menikmati dessert otentik, kopi, dan makanan dengan konsep monokrom elegan.' }}">
    <title>@yield('title', 'Nucomu Cafe') — Coffee & Dessert Tulungagung</title>

    {{-- Script Pencegah FOUC (Flash of Unstyled Content) saat muat tema --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,400;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
        /* Topographic pattern overlay for solid sections */
        .topo-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400'%3E%3Cpath d='M0 200 Q50 180 100 200 Q150 220 200 200 Q250 180 300 200 Q350 220 400 200' fill='none' stroke='%23888' stroke-width='0.5' opacity='0.08'/%3E%3C/svg%3E");
        }

        /* Ambient Cafe Backdrop Styling (Continuous, Seamless & Non-Stiff) */
        .ambient-cafe-backdrop {
            position: fixed;
            inset: 0;
            z-index: -10;
            background-image: url('{{ asset('images/cafe_bg.jpg') }}');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            pointer-events-none;
        }

        /* Light Mode: Warm, luminous, semi-transparent cafe veil */
        html:not(.dark) .ambient-cafe-backdrop::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(255, 255, 255, 0.72) 0%,
                rgba(250, 250, 249, 0.78) 35%,
                rgba(245, 245, 244, 0.82) 70%,
                rgba(255, 255, 255, 0.76) 100%
            );
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        /* Dark Mode: Luxury moody monochrome veil with glowing ambient cafe lights */
        html.dark .ambient-cafe-backdrop::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(10, 10, 10, 0.75) 0%,
                rgba(14, 14, 16, 0.79) 35%,
                rgba(12, 12, 14, 0.82) 70%,
                rgba(10, 10, 10, 0.78) 100%
            );
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        /* Frosted Glassmorphism Cards (Organic Squircles & Modern Blur) */
        .glass-card {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        html:not(.dark) .glass-card {
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.90);
            box-shadow: 0 16px 38px -12px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.02);
            color: #09090b;
        }

        html.dark .glass-card {
            background: rgba(18, 18, 20, 0.70);
            border: 1px solid rgba(255, 255, 255, 0.10);
            box-shadow: 0 20px 45px -12px rgba(0, 0, 0, 0.50), 0 0 0 1px rgba(255, 255, 255, 0.04);
            color: #ffffff;
        }

        /* Nav link — sliding underline on hover */
        .nav-link {
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            letter-spacing: 0.025em;
            position: relative;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -3px;
            width: 0;
            height: 1.5px;
            background-color: currentColor;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .nav-link:hover::after {
            width: 100%;
        }

        /* Button styles */
        .btn-primary {
            display: inline-block;
            background-color: #0a0a0a !important;
            color: #ffffff !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.1em !important;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            transition: background-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
        }
        html.dark .btn-primary {
            background-color: #ffffff !important;
            color: #0a0a0a !important;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .btn-outline {
            display: inline-block;
            border: 1px solid currentColor !important;
            background-color: transparent !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.1em !important;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            transition: background-color 0.25s ease, color 0.25s ease, transform 0.25s ease;
        }
        .btn-outline:hover {
            transform: translateY(-2px);
        }

        /* Flash messages */
        .alert-success { background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 1rem; border-radius: 0.5rem; }
        .alert-error   { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 0.5rem; }

        /* Scroll Reveal */
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
    </style>
    @stack('styles')
</head>
<body class="bg-stone-100/0 dark:bg-zinc-950/0 text-zinc-900 dark:text-white font-sans antialiased transition-colors duration-300 relative min-h-screen">

    {{-- UNIFIED CONTINUOUS AMBIENT CAFE BACKDROP (SEAMLESS, COZY & NON-STIFF) --}}
    <div class="ambient-cafe-backdrop" aria-hidden="true"></div>

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
        setTimeout(() => {
            const el = document.getElementById('flash-msg');
            if (el) el.style.display = 'none';
        }, 4000);

        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Theme Switcher Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const revealElements = document.querySelectorAll('.reveal, .reveal-scale');
            if (!('IntersectionObserver' in window)) {
                revealElements.forEach(el => el.classList.add('revealed'));
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    } else {
                        entry.target.classList.remove('revealed');
                    }
                });
            }, {
                rootMargin: '0px 0px -30px 0px',
                threshold: 0.08
            });

            revealElements.forEach(el => observer.observe(el));
        });
    </script>
    @stack('scripts')
</body>
</html>
