<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir — Nucomu Cafe</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                        'card': '0 16px 40px -10px rgba(25, 25, 25, 0.06), 0 4px 12px -2px rgba(25, 25, 25, 0.03)',
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
        .btn-press:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px -6px rgba(25, 25, 25, 0.28);
        }
        .btn-press:active {
            transform: scale(0.98);
        }
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
            <div class="w-14 h-14 bg-ink-black rounded-2xl flex items-center justify-center mx-auto mb-3.5 shadow-sm">
                <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu Cafe" class="h-7 w-auto object-contain brightness-0 invert">
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-ink-black font-sans uppercase">Masuk ke Kasir</h1>
            <p class="text-muted-text text-xs mt-1">Point of Sale & Manajemen Pesanan Nucomu Cafe</p>
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

        <form action="{{ route('kasir.login.post') }}" method="POST" class="space-y-5" onsubmit="handleKasirLoginSubmit()">
            @csrf

            {{-- Akun Kasir Otomatis Terpilih --}}
            <input type="hidden" name="email" value="{{ $kasirAccount->email ?? 'kasir@nucomu.com' }}">

            <div class="bg-warm-ivory border border-hairline rounded-2xl p-3.5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-ink-black text-white flex items-center justify-center font-mono text-xs font-extrabold shrink-0 shadow-xs">
                        <i class="fa-solid fa-cash-register text-xs"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-extrabold text-ink-black truncate">
                                {{ $kasirAccount->name ?? 'Kasir Nucomu' }}
                            </span>
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                        </div>
                        <span class="text-[11px] font-mono text-muted-text block truncate">
                            {{ $kasirAccount->email ?? 'kasir@nucomu.com' }}
                        </span>
                    </div>
                </div>
                <span class="text-[9.5px] font-mono font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg bg-white border border-hairline text-charcoal shrink-0">
                    Akun Kasir
                </span>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-charcoal mb-1.5">
                    Masukkan Kata Sandi Kasir
                </label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autofocus
                        placeholder="Masukkan kata sandi..."
                        class="w-full bg-warm-ivory border border-hairline rounded-xl pl-4 pr-10 py-3 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition"
                    >
                    <button
                        type="button"
                        onclick="toggleKasirPassword()"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-muted-text hover:text-ink-black transition-colors cursor-pointer"
                        title="Tampilkan / Sembunyikan Kata Sandi"
                    >
                        <i id="kasirPasswordEye" class="fa-regular fa-eye text-xs"></i>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                id="btnKasirLoginSubmit"
                class="w-full bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition btn-press flex items-center justify-center gap-2 cursor-pointer"
            >
                <span id="btnKasirLoginSpinner" class="hidden">
                    <svg class="spin-loader h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <span id="btnKasirLoginText">Masuk ke Kasir</span>
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
        function toggleKasirPassword() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('kasirPasswordEye');
            if (!passInput || !eyeIcon) return;

            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.className = 'fa-regular fa-eye-slash text-xs';
            } else {
                passInput.type = 'password';
                eyeIcon.className = 'fa-regular fa-eye text-xs';
            }
        }

        function handleKasirLoginSubmit() {
            const btn = document.getElementById('btnKasirLoginSubmit');
            const btnText = document.getElementById('btnKasirLoginText');
            const spinner = document.getElementById('btnKasirLoginSpinner');
            if (!btn || !btnText || !spinner) return;

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
