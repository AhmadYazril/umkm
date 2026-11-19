<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator — Nucomu Cafe</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#111111',
                            card: '#ffffff',
                            muted: '#6b7280',
                            input: '#f4f4f6',
                        }
                    },
                    boxShadow: {
                        'soft-elevation': '0 25px 60px -15px rgba(0, 0, 0, 0.07), 0 10px 25px -10px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes revealFadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-up {
            animation: revealFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .btn-press {
            transition: transform 0.15s ease, background-color 0.2s ease, opacity 0.2s ease;
        }
        .btn-press:active {
            transform: scale(0.985);
        }

        /* Spinning loader rotation */
        @keyframes spinnerRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spin-loader {
            animation: spinnerRotate 0.85s linear infinite;
        }

        /* Smooth radial background */
        .bg-concentric-mesh {
            background-color: #f1f2f4;
            background-image: radial-gradient(circle at 50% 50%, #ffffff 0%, #f6f7f9 45%, #eaecf0 100%);
        }
    </style>
</head>
<body class="bg-concentric-mesh text-neutral-900 font-sans min-h-screen flex items-center justify-center p-4 sm:p-6 antialiased relative overflow-x-hidden selection:bg-neutral-900 selection:text-white">

    {{-- Subtle concentric circles background pattern matching design --}}
    <div class="fixed inset-0 pointer-events-none flex items-center justify-center overflow-hidden z-0 select-none">
        <svg class="w-[1400px] h-[1400px] max-w-none opacity-80" viewBox="0 0 1400 1400" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="700" cy="700" r="190" stroke="#000000" stroke-opacity="0.045" stroke-width="1"/>
            <circle cx="700" cy="700" r="300" stroke="#000000" stroke-opacity="0.04" stroke-width="1"/>
            <circle cx="700" cy="700" r="430" stroke="#000000" stroke-opacity="0.035" stroke-width="1"/>
            <circle cx="700" cy="700" r="580" stroke="#000000" stroke-opacity="0.03" stroke-width="1"/>
            <circle cx="700" cy="700" r="740" stroke="#000000" stroke-opacity="0.025" stroke-width="1"/>
            <circle cx="700" cy="700" r="920" stroke="#000000" stroke-opacity="0.02" stroke-width="1"/>
        </svg>
    </div>

    {{-- Main Login Card --}}
    <main class="w-full max-w-[430px] bg-white border border-neutral-200/60 rounded-[28px] sm:rounded-[32px] p-7 sm:p-9 shadow-soft-elevation anim-fade-up relative z-10">
        
        {{-- Brand Monogram Badge --}}
        <div class="w-13 h-13 sm:w-14 sm:h-14 bg-[#111111] rounded-2xl flex items-center justify-center mb-6 shadow-xs">
            <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu Cafe" class="h-7 w-auto object-contain brightness-0 invert">
        </div>

        {{-- Heading & Subtitle --}}
        <div class="mb-7">
            <h1 class="text-2xl sm:text-[27px] font-bold tracking-tight text-neutral-900 leading-[1.25]">
                Masuk ke ruang kerja<br>admin
            </h1>
            <p class="text-neutral-500 text-xs sm:text-sm font-normal mt-2 leading-relaxed">
                Kelola menu, pesanan, dan laporan Nucomu Cafe dari satu tempat.
            </p>
        </div>

        {{-- Feedback / Alert Notifications --}}
        @if(session('session_expired') || session('error'))
            <div class="mb-5 bg-amber-50/90 border border-amber-200/80 text-amber-900 px-4 py-3 rounded-2xl text-xs font-medium flex items-start gap-2.5">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 shrink-0 text-xs"></i>
                <span class="leading-snug">{{ session('session_expired') ?? session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="mb-5 bg-rose-50/90 border border-rose-200/80 text-rose-800 px-4 py-3 rounded-2xl text-xs font-medium flex items-start gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-xs"></i>
                <span class="leading-snug">{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form Login --}}
        <form id="adminLoginForm" action="{{ route('admin.login.post') }}" method="POST" class="space-y-3.5" onsubmit="handleLoginSubmit(event)">
            @csrf

            {{-- Input Email --}}
            <div>
                <label for="email" class="sr-only">Email administrator</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', 'admin@nucomu.com') }}" 
                    required 
                    autofocus 
                    autocomplete="email"
                    placeholder="Email administrator"
                    class="w-full bg-[#f4f4f6] text-neutral-900 placeholder:text-neutral-400 text-sm rounded-2xl px-5 py-3.5 sm:py-4 border border-transparent focus:border-neutral-300 focus:bg-white focus:outline-none focus:ring-4 focus:ring-neutral-900/5 transition-all duration-200">
            </div>

            {{-- Input Password with Toggle Eye --}}
            <div class="relative">
                <label for="password" class="sr-only">Kata sandi</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Kata sandi"
                    class="w-full bg-[#f4f4f6] text-neutral-900 placeholder:text-neutral-400 text-sm rounded-2xl pl-5 pr-12 py-3.5 sm:py-4 border border-transparent focus:border-neutral-300 focus:bg-white focus:outline-none focus:ring-4 focus:ring-neutral-900/5 transition-all duration-200">
                
                <button 
                    type="button" 
                    id="togglePasswordBtn" 
                    onclick="togglePasswordVisibility()" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-700 focus:outline-none p-1.5 transition-colors cursor-pointer"
                    title="Tampilkan / sembunyikan kata sandi"
                    aria-label="Tampilkan atau sembunyikan kata sandi">
                    {{-- Open eye --}}
                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{-- Slashed eye (hidden initially) --}}
                    <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>

            {{-- Submit Button --}}
            <div class="pt-1.5">
                <button 
                    type="submit" 
                    id="btnLoginSubmit" 
                    class="w-full bg-[#111111] hover:bg-neutral-800 text-white font-medium text-sm py-3.5 sm:py-4 px-6 rounded-2xl transition shadow-xs btn-press flex items-center justify-center gap-2 cursor-pointer">
                    <span id="btnLoginSpinner" class="hidden">
                        <svg class="spin-loader h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span id="btnLoginText">Masuk ke admin panel</span>
                </button>
            </div>
        </form>

        {{-- Footer Link --}}
        <div class="mt-8 pt-6 border-t border-neutral-100 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm text-neutral-500 hover:text-neutral-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke website pelanggan</span>
            </a>
        </div>

    </main>

    <script>
        // Toggle password show / hide
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            
            if (!passwordInput) return;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                if (eyeIcon) eyeIcon.classList.add('hidden');
                if (eyeOffIcon) eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                if (eyeIcon) eyeIcon.classList.remove('hidden');
                if (eyeOffIcon) eyeOffIcon.classList.add('hidden');
            }
        }

        // Handle login form submit animation
        function handleLoginSubmit(event) {
            const btn = document.getElementById('btnLoginSubmit');
            const btnText = document.getElementById('btnLoginText');
            const spinner = document.getElementById('btnLoginSpinner');
            
            if (!btn || !btnText || !spinner) return;

            // Activate spinning animation & update label
            spinner.classList.remove('hidden');
            btnText.textContent = 'Memverifikasi...';
            btn.classList.add('opacity-80', 'cursor-wait');
            btn.style.pointerEvents = 'none';
        }

        // BFCache (back-forward navigation) reload to ensure fresh CSRF token
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Auto-refresh stale tab if left in background for > 20 minutes
        let pageLoadedAt = Date.now();
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                const idleMinutes = (Date.now() - pageLoadedAt) / (1000 * 60);
                if (idleMinutes > 20) {
                    window.location.reload();
                }
            }
        });
    </script>

</body>
</html>
