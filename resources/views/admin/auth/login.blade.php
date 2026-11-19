<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator — Nucomu Cafe</title>
    
    <!-- Google Fonts: Cormorant Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        editorial: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                    },
                    colors: {
                        brand: {
                            card: '#1c1c1e',
                            input: '#2c2c2e',
                        }
                    },
                    boxShadow: {
                        'glass': '0 30px 60px -15px rgba(0, 0, 0, 0.65), 0 0 1px 1px rgba(255, 255, 255, 0.08)',
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
    </style>
</head>
<body class="bg-[#121214] text-neutral-100 font-sans min-h-screen relative overflow-x-hidden selection:bg-white selection:text-neutral-900">

    {{-- Dark Atmosphere Cafe Background --}}
    <div class="fixed inset-0 pointer-events-none z-0">
        <img 
            src="{{ asset('images/auth/login_bg.jpg') }}" 
            alt="Nucomu Cafe Ambiance" 
            class="w-full h-full object-cover object-center filter brightness-[0.92] contrast-[1.05]">
        <div class="absolute inset-0 bg-gradient-to-r from-black/40 via-black/30 to-black/60"></div>
    </div>

    {{-- Main Container --}}
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-12">
        <div class="w-full max-w-6xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-16">
            
            {{-- Left Side: Editorial Brand Showcase --}}
            <div class="hidden lg:flex flex-col justify-center flex-1 max-w-xl text-left anim-fade-up">
                <p class="text-sm font-medium tracking-wider text-neutral-300/80 mb-5 uppercase">
                    Nucomu Cafe
                </p>
                <h2 class="font-editorial text-4xl sm:text-5xl lg:text-[54px] font-normal tracking-tight text-white leading-[1.12]">
                    Satu panel untuk semua<br>yang terjadi di kafe.
                </h2>
                <p class="text-neutral-300/75 text-sm sm:text-[15px] font-sans font-normal mt-5 max-w-md leading-relaxed">
                    Pantau pesanan, atur menu, dan baca laporan harian tanpa berpindah halaman.
                </p>
            </div>

            {{-- Right Side: Glass Login Card --}}
            <main class="w-full max-w-[430px] bg-[#1c1c1e]/90 backdrop-blur-xl border border-white/10 rounded-[28px] sm:rounded-[32px] p-7 sm:p-9 shadow-glass anim-fade-up">
                
                {{-- Brand Monogram Badge (White squircle with black icon) --}}
                <div class="w-13 h-13 sm:w-14 sm:h-14 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                    <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu Cafe" class="h-7 w-auto object-contain">
                </div>

                {{-- Card Heading & Subtitle --}}
                <div class="mb-7">
                    <h1 class="font-editorial text-3xl sm:text-[34px] font-normal tracking-tight text-white mb-2 leading-snug">
                        Selamat datang kembali
                    </h1>
                    <p class="text-neutral-400 text-xs sm:text-sm font-normal leading-relaxed">
                        Masuk dengan akun administrator untuk membuka panel pengelolaan.
                    </p>
                </div>

                {{-- Feedback / Alert Notifications --}}
                @if(session('session_expired') || session('error'))
                    <div class="mb-5 bg-amber-500/15 border border-amber-500/30 text-amber-200 px-4 py-3 rounded-2xl text-xs font-medium flex items-start gap-2.5">
                        <i class="fa-solid fa-triangle-exclamation text-amber-400 mt-0.5 shrink-0 text-xs"></i>
                        <span class="leading-snug">{{ session('session_expired') ?? session('error') }}</span>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="mb-5 bg-rose-500/15 border border-rose-500/30 text-rose-200 px-4 py-3 rounded-2xl text-xs font-medium flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 mt-0.5 shrink-0 text-xs"></i>
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
                            class="w-full bg-[#2c2c2e]/90 hover:bg-[#343437]/90 text-white placeholder:text-neutral-400 text-sm rounded-2xl px-5 py-3.5 sm:py-4 border border-white/10 focus:border-white/30 focus:bg-[#323235] focus:outline-none focus:ring-4 focus:ring-white/5 transition-all duration-200">
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
                            class="w-full bg-[#2c2c2e]/90 hover:bg-[#343437]/90 text-white placeholder:text-neutral-400 text-sm rounded-2xl pl-5 pr-12 py-3.5 sm:py-4 border border-white/10 focus:border-white/30 focus:bg-[#323235] focus:outline-none focus:ring-4 focus:ring-white/5 transition-all duration-200">
                        
                        <button 
                            type="button" 
                            id="togglePasswordBtn" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white focus:outline-none p-1.5 transition-colors cursor-pointer"
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

                    {{-- Submit Button (Crisp White Button with Dark Text) --}}
                    <div class="pt-1.5">
                        <button 
                            type="submit" 
                            id="btnLoginSubmit" 
                            class="w-full bg-white hover:bg-neutral-100 text-neutral-900 font-medium text-sm py-3.5 sm:py-4 px-6 rounded-2xl transition shadow-md btn-press flex items-center justify-center gap-2 cursor-pointer">
                            <span id="btnLoginSpinner" class="hidden">
                                <svg class="spin-loader h-4 w-4 text-neutral-900" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <span id="btnLoginText">Masuk ke admin panel</span>
                        </button>
                    </div>
                </form>

                {{-- Footer Link --}}
                <div class="mt-8 pt-6 border-t border-white/10 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm text-neutral-400 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        <span>Kembali ke website pelanggan</span>
                    </a>
                </div>

            </main>

        </div>
    </div>

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
