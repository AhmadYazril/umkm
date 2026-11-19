<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Kedaluwarsa (419) — Nucomu Cafe</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">
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
                        sans: ['"Montserrat"', 'sans-serif'],
                        editorial: ['"Playfair Display"', 'Georgia', 'serif'],
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
<body class="bg-[#0C0C0C] text-[#F5F5F5] font-sans min-h-screen flex items-center justify-center p-4 antialiased selection:bg-[#F5F2EE] selection:text-[#111111]">

    <div class="w-full max-w-md bg-white/[0.04] backdrop-blur-md border border-white/[0.14] rounded-3xl p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.6)] text-center">
        
        {{-- Shield / Clock Icon --}}
        <div class="w-16 h-16 bg-white/10 text-[#F5F2EE] border border-white/15 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xs">
            <i class="fa-solid fa-shield-halved text-2xl"></i>
        </div>

        <div class="inline-block px-3 py-1 bg-white/[0.06] border border-white/[0.14] rounded-full text-[11px] font-mono font-bold text-[#B5B5B5] uppercase tracking-wider mb-3">
            Error 419 • Sesi Berakhir
        </div>

        <h1 class="text-2xl font-black tracking-tight text-[#F5F5F5] mb-2 uppercase">Sesi Keamanan Kedaluwarsa</h1>
        <p class="text-[#B5B5B5] text-xs leading-relaxed mb-6">
            Halaman ini tidak aktif dalam waktu yang cukup lama sehingga token keamanan (CSRF) telah kedaluwarsa untuk melindungi akun Anda.
        </p>

        <div class="space-y-3">
            <button onclick="handleReload()" class="w-full bg-[#F5F2EE] hover:bg-white text-[#111111] font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-rotate-right text-xs"></i>
                <span id="btnText">Muat Ulang Halaman Baru</span>
            </button>

            <a href="{{ url('/') }}" class="w-full bg-white/[0.04] hover:bg-white/[0.08] text-[#F5F5F5] font-semibold text-xs py-3 rounded-xl transition border border-white/[0.14] flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Kembali ke Beranda Utama</span>
            </a>
        </div>

        <p class="text-[11px] text-[#8F8F8F] mt-6">
            Otomatis memuat ulang dalam <span id="countdown" class="font-bold text-[#F5F5F5] font-mono">5</span> detik...
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
