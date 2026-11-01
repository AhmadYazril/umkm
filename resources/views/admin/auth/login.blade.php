<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator — Nucomu Cafe</title>
    
    <!-- Google Fonts: Cormorant Garamond, Plus Jakarta Sans, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ink-black': '#191919',
                        'charcoal': '#30302D',
                        'warm-ivory': '#FAF8F5',
                        'warm-stone': '#F5F2EC',
                        'hairline': '#E5E5E5',
                        'muted-text': '#666666',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        editorial: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    boxShadow: {
                        'card': '0 4px 20px -2px rgba(25, 25, 25, 0.05), 0 2px 6px -1px rgba(25, 25, 25, 0.03)',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes revealFadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-up {
            animation: revealFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .btn-press {
            transition: transform 0.15s ease, background-color 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }
        .btn-press:active {
            transform: scale(0.97);
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
<body class="bg-warm-ivory text-ink-black font-sans min-h-screen flex items-center justify-center p-4 antialiased selection:bg-ink-black selection:text-white">

    <div class="w-full max-w-md bg-white border border-hairline rounded-3xl p-8 sm:p-10 shadow-card anim-fade-up">
        
        {{-- Brand Logo Monogram --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-ink-black rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-xs">
                <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu Cafe" class="h-7 w-auto object-contain brightness-0 invert">
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-ink-black font-sans uppercase">Admin Workspace</h1>
            <p class="text-muted-text text-xs mt-1">Masuk ke Panel Pengelolaan Nucomu Cafe</p>
        </div>

        @if(session('session_expired') || session('error'))
            <div class="bg-amber-50 border border-amber-200 text-amber-900 p-3.5 rounded-2xl text-xs mb-6 font-semibold text-center">
                {{ session('session_expired') ?? session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 p-3.5 rounded-2xl text-xs mb-6 font-semibold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form id="adminLoginForm" action="{{ route('admin.login.post') }}" method="POST" class="space-y-5" onsubmit="handleLoginSubmit(event)">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-charcoal mb-1.5">
                    Email Administrator
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', 'admin@nucomu.com') }}" required autofocus placeholder="admin@nucomu.com"
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-charcoal mb-1.5">
                    Kata Sandi
                </label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            {{-- Submit Button with Smooth Spinning Loading Indicator --}}
            <button type="submit" id="btnLoginSubmit" class="w-full bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition shadow-xs btn-press flex items-center justify-center gap-2 cursor-pointer">
                <span id="btnLoginSpinner" class="hidden">
                    <svg class="spin-loader h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <span id="btnLoginText">Masuk ke Admin Panel</span>
            </button>
        </form>

        <div class="mt-8 text-center pt-6 border-t border-hairline">
            <a href="{{ route('home') }}" class="text-xs text-muted-text hover:text-ink-black font-semibold transition-colors inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Website Pelanggan</span>
            </a>
        </div>

    </div>

    <script>
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
    </script>

</body>
</html>
