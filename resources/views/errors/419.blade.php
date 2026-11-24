@php
    $activeTheme = ($siteTheme ?? \App\Models\Setting::getSiteTheme()) === 'dark' ? 'dark' : 'light';
    $isDarkTheme = $activeTheme === 'dark';
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $activeTheme }}" class="{{ $isDarkTheme ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="{{ $activeTheme }}">
    <title>Sesi Kedaluwarsa (419) — Nucomu Cafe</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/nucomu.css') }}?v={{ filemtime(public_path('css/nucomu.css')) }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Montserrat"', 'sans-serif'],
                        editorial: ['"Playfair Display"', 'Georgia', 'serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-[var(--bg-page)] text-[var(--text)] font-sans min-h-screen flex items-center justify-center p-4 antialiased selection:bg-[var(--accent-solid)] selection:text-[var(--accent-solid-text)]">

    <div class="w-full max-w-md bg-[var(--surface)] backdrop-blur-md border border-[var(--border)] rounded-3xl p-8 sm:p-10 shadow-[var(--shadow)] text-center">
        
        {{-- Shield / Clock Icon --}}
        <div class="w-16 h-16 bg-[var(--badge-bg)] text-[var(--text)] border border-[var(--border)] rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xs">
            <i class="fa-solid fa-shield-halved text-2xl"></i>
        </div>

        <div class="inline-block px-3 py-1 bg-[var(--badge-bg)] border border-[var(--border)] rounded-full text-[11px] font-mono font-bold text-[var(--text-muted)] uppercase tracking-wider mb-3">
            Error 419 • Sesi Berakhir
        </div>

        <h1 class="text-2xl font-black tracking-tight text-[var(--text)] mb-2 uppercase">Sesi Keamanan Kedaluwarsa</h1>
        <p class="text-[var(--text-muted)] text-xs leading-relaxed mb-6">
            Halaman ini tidak aktif dalam waktu yang cukup lama sehingga token keamanan (CSRF) telah kedaluwarsa untuk melindungi akun Anda.
        </p>

        <div class="space-y-3">
            <button onclick="handleReload()" class="w-full bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-rotate-right text-xs"></i>
                <span id="btnText">Muat Ulang Halaman Baru</span>
            </button>

            <a href="{{ url('/') }}" class="w-full bg-[var(--badge-bg)] hover:bg-[var(--btn-outline-hover-bg)] text-[var(--text)] font-semibold text-xs py-3 rounded-xl transition border border-[var(--border)] flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
        </div>

        <p class="text-[11px] text-[var(--text-faint)] mt-6">
            Otomatis memuat ulang dalam <span id="countdown" class="font-bold text-[var(--text)] font-mono">5</span> detik...
        </p>

    </div>

    <script>
        function handleReload() {
            // Using replace to avoid browser "Confirm Form Resubmission" prompt
            const targetUrl = document.referrer && document.referrer !== window.location.href
                ? document.referrer
                : (window.location.pathname.includes('/admin') ? '{{ route("admin.login") }}' : '{{ route("home") }}');
            
            window.location.replace(targetUrl);
        }

        let seconds = 5;
        const countdownEl = document.getElementById('countdown');
        const timer = setInterval(() => {
            seconds--;
            if (countdownEl) countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                handleReload();
            }
        }, 1000);
    </script>

</body>
</html>
