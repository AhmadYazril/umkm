<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Kedaluwarsa (419) — Nucomu Cafe</title>
    
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
                        'warm-ivory': '#F9F9F9',
                        'warm-stone': '#E5E5E5',
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
</head>
<body class="bg-warm-ivory text-ink-black font-sans min-h-screen flex items-center justify-center p-4 antialiased selection:bg-ink-black selection:text-white">

    <div class="w-full max-w-md bg-white border border-hairline rounded-3xl p-8 sm:p-10 shadow-card text-center">
        
        {{-- Shield / Clock Icon --}}
        <div class="w-16 h-16 bg-amber-50 text-amber-600 border border-amber-200 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xs">
            <i class="fa-solid fa-shield-halved text-2xl"></i>
        </div>

        <div class="inline-block px-3 py-1 bg-warm-ivory border border-hairline rounded-full text-[11px] font-mono font-bold text-muted-text uppercase tracking-wider mb-3">
            Error 419 • Sesi Berakhir
        </div>

        <h1 class="text-2xl font-black tracking-tight text-ink-black mb-2 uppercase">Sesi Keamanan Kedaluwarsa</h1>
        <p class="text-muted-text text-xs leading-relaxed mb-6">
            Halaman ini tidak aktif dalam waktu yang cukup lama sehingga token keamanan (CSRF) telah kedaluwarsa untuk melindungi akun Anda.
        </p>

        <div class="space-y-3">
            <button onclick="handleReload()" class="w-full bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-rotate-right text-xs"></i>
                <span id="btnText">Muat Ulang Halaman Baru</span>
            </button>

            <a href="{{ url('/') }}" class="w-full bg-warm-ivory hover:bg-warm-stone text-charcoal font-semibold text-xs py-3 rounded-xl transition border border-hairline flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
        </div>

        <p class="text-[11px] text-muted-text mt-6">
            Otomatis memuat ulang dalam <span id="countdown" class="font-bold text-ink-black font-mono">5</span> detik...
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
