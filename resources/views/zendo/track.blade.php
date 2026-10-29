<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $isCourier ? 'Workspace Kurir Zendo' : 'Live Tracking Pesanan' }} — #{{ $delivery->order_code }}</title>
    <!-- Plus Jakarta Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Leaflet CSS & JS (Local Assets + CDN Fallback) -->
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
    <script>
        if (typeof L === 'undefined') {
            document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">');
            document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"><\/script>');
        }
    </script>
    <style>
        #trackingMap {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 0;
            background: #16161d;
        }
        .leaflet-container {
            width: 100% !important;
            height: 100% !important;
            font-family: inherit;
            background: #16161d !important;
        }
        .leaflet-control-attribution { font-size: 9px !important; opacity: 0.7; }
        .pulse-marker {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-ring 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 14px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .courier-motor-icon {
            transition: transform 0.4s ease-out;
        }

        /* ==========================================================
           VERIFIKASI PASSWORD MODAL & CARD STYLES (GAYA OTP DINAMIS)
           ========================================================== */
        :root {
            --pw-primary-start: #6C5CE7;
            --pw-primary-end: #4F6BFF;
            --pw-primary-glow: rgba(108, 92, 231, 0.28);
            --pw-success: #22C55E;
            --pw-success-dark: #16A34A;
            --pw-success-glow: rgba(34, 197, 94, 0.32);
            --pw-error: #EF4444;
            --pw-error-glow: rgba(239, 68, 68, 0.28);
            --pw-radius-card: 28px;
            --pw-radius-box: 14px;
            --pw-radius-btn: 14px;
            --pw-ease-smooth: cubic-bezier(0.16, 1, 0.3, 1);
            --pw-ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Modal Overlay Gelap Gradient Ungu-Biru */
        .verify-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px 16px;
            background: radial-gradient(circle at 50% 20%, #1A1638 0%, #121026 50%, #0A0A14 100%);
            backdrop-filter: blur(12px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .verify-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        /* Ambient Glow di belakang modal */
        .verify-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 1;
            pointer-events: none;
            opacity: 0.35;
        }
        .verify-blob-1 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, #6C5CE7, transparent 70%);
            top: 5%; left: 8%;
            animation: verifyAmbientFloat 14s ease-in-out infinite alternate;
        }
        .verify-blob-2 {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #4F6BFF, transparent 70%);
            bottom: 5%; right: 8%;
            opacity: 0.28;
            animation: verifyAmbientFloat 18s ease-in-out infinite alternate-reverse;
        }
        @keyframes verifyAmbientFloat {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.08); }
        }

        /* Kartu Putih di Tengah (Radius 28px, Shadow Lembut, Garis Gradient di Tepi Atas) */
        .verify-card-box {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            background: #FFFFFF;
            color: #1E293B;
            border-radius: var(--pw-radius-card);
            padding: 36px 26px 30px;
            box-shadow: 
                0 28px 56px -12px rgba(0, 0, 0, 0.45),
                0 12px 28px -4px rgba(108, 92, 231, 0.18),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transform: scale(0.92) translateY(12px);
            transition: transform 0.35s var(--pw-ease-bounce), box-shadow 0.35s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .verify-modal-overlay.active .verify-card-box {
            transform: scale(1) translateY(0);
        }

        /* Garis gradient tipis di tepi atas kartu */
        .verify-card-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pw-primary-start), var(--pw-primary-end));
        }

        /* Tombol close (x) bulat abu-abu di pojok kanan atas kartu */
        .verify-close-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #F1F5F9;
            color: #64748B;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            transition: all 0.2s ease;
            z-index: 20;
        }
        .verify-close-btn:hover {
            background: #E2E8F0;
            color: #0F172A;
            transform: rotate(90deg);
        }
        .verify-close-btn:focus-visible {
            outline: 2px solid var(--pw-primary-start);
            outline-offset: 2px;
        }

        /* Shake on error (Horizontal Shake ~400ms) */
        .verify-card-box.shake {
            animation: verifyShake 400ms cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }
        @keyframes verifyShake {
            0%, 100% { transform: translateX(0); }
            15% { transform: translateX(-9px) rotate(-1deg); }
            30% { transform: translateX(8px) rotate(1deg); }
            45% { transform: translateX(-6px) rotate(-0.5deg); }
            60% { transform: translateX(5px) rotate(0.5deg); }
            75% { transform: translateX(-3px); }
            90% { transform: translateX(2px); }
        }

        /* Ikon Gembok Putih & Cincin Putus-Putus (Dashed) */
        .verify-icon-wrap {
            position: relative;
            width: 82px;
            height: 82px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-rotating-ring {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            animation: verifySpinDashedRing 14s linear infinite;
            transform-origin: center;
            pointer-events: none;
            transition: animation-duration 0.3s ease;
        }
        .verify-rotating-ring circle {
            fill: none;
            stroke: url(#verifyDashedRingGrad);
            stroke-width: 2.2;
            stroke-dasharray: 6 7;
            stroke-linecap: round;
            opacity: 0.9;
        }
        .state-loading .verify-rotating-ring {
            animation-duration: 1.2s !important;
        }
        @keyframes verifySpinDashedRing {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Cincin hijau berdenyut saat sukses */
        .verify-pulse-ring-success {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid var(--pw-success);
            opacity: 0;
            pointer-events: none;
            display: none;
        }
        .state-success .verify-pulse-ring-success {
            display: block;
            animation: pulseSuccessRing 1.8s infinite cubic-bezier(0.25, 1, 0.5, 1);
        }
        @keyframes pulseSuccessRing {
            0% { transform: scale(0.92); opacity: 0.9; }
            70% { transform: scale(1.3); opacity: 0; }
            100% { transform: scale(1.32); opacity: 0; }
        }

        /* Badge Gembok */
        .verify-icon-badge {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--pw-primary-start) 0%, var(--pw-primary-end) 100%);
            box-shadow: 0 10px 22px var(--pw-primary-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            transition: all 0.35s var(--pw-ease-bounce);
        }
        .verify-icon-badge svg {
            width: 25px;
            height: 25px;
            fill: #FFFFFF;
        }
        .state-success .verify-icon-badge {
            background: linear-gradient(135deg, var(--pw-success) 0%, var(--pw-success-dark) 100%) !important;
            box-shadow: 0 10px 24px var(--pw-success-glow) !important;
            transform: scale(1.08);
        }
        .state-success .verify-icon-lock { display: none; }
        .state-success .verify-icon-check { display: block !important; }

        .verify-check-path {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            animation: drawCheckSvg 0.6s 0.2s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        @keyframes drawCheckSvg {
            to { stroke-dashoffset: 0; }
        }

        .state-error .verify-icon-badge {
            background: linear-gradient(135deg, var(--pw-error) 0%, #DC2626 100%);
            box-shadow: 0 10px 22px var(--pw-error-glow);
        }

        /* Typography & Chip Akun */
        .verify-title {
            font-size: 21px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.025em;
            margin-bottom: 5px;
            text-align: center;
        }
        .verify-subtitle {
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
            line-height: 1.45;
            margin-bottom: 22px;
            text-align: center;
        }
        .verify-account-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            color: #0F172A;
            background: #F1F5F9;
            padding: 3px 12px;
            border-radius: 999px;
            margin-top: 5px;
            font-size: 12px;
            border: 1px solid #E2E8F0;
            letter-spacing: 0.01em;
        }

        /* KOTAK PASSWORD GAYA OTP (JUMLAH DINAMIS) */
        .verify-otp-wrapper {
            width: 100%;
            margin-bottom: 12px;
        }
        .verify-otp-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin: 0 auto;
            width: 100%;
            transition: opacity 0.3s ease;
        }
        .verify-otp-box-wrap {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .verify-otp-input {
            width: var(--box-w, 48px);
            height: var(--box-h, 56px);
            border-radius: var(--pw-radius-box);
            background: #FFFFFF;
            border: 1.6px solid rgba(108, 92, 231, 0.25);
            font-family: 'Plus Jakarta Sans', monospace, sans-serif;
            font-size: 22px;
            font-weight: 800;
            text-align: center;
            color: #0F172A;
            outline: none;
            caret-color: var(--pw-primary-start);
            box-shadow: 
                0 2px 8px rgba(15, 23, 42, 0.04),
                inset 0 -3px 0 rgba(108, 92, 231, 0.22);
            transition: all 0.25s var(--pw-ease-smooth);
            -webkit-user-select: auto;
            user-select: auto;
        }

        /* Kotak aktif / fokus: naik sedikit & glow ungu */
        .verify-otp-input:focus {
            transform: translateY(-2px) scale(1.05);
            border-color: var(--pw-primary-start);
            box-shadow: 
                0 0 0 4px var(--pw-primary-glow),
                0 8px 18px -4px rgba(108, 92, 231, 0.2),
                inset 0 -3px 0 var(--pw-primary-start);
            z-index: 5;
        }

        /* Pop saat terisi */
        .verify-otp-input.pop {
            animation: verifyOtpPop 0.2s var(--pw-ease-bounce);
        }
        @keyframes verifyOtpPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.14); }
            100% { transform: scale(1); }
        }

        /* Wave denyut berurutan saat loading */
        .state-loading .verify-otp-input {
            animation: verifyWavePulse 1.2s ease-in-out infinite;
            cursor: wait;
        }
        @keyframes verifyWavePulse {
            0%, 100% { transform: translateY(0); opacity: 0.7; }
            50% { transform: translateY(-6px); opacity: 1; border-color: var(--pw-primary-start); }
        }

        /* Kotak saat error */
        .state-error .verify-otp-input {
            border-color: var(--pw-error) !important;
            color: var(--pw-error) !important;
            box-shadow: 
                0 0 0 4px var(--pw-error-glow) !important,
                inset 0 -3px 0 var(--pw-error-color, #EF4444) !important;
        }

        /* Kotak saat sukses (stagger) */
        .state-success .verify-otp-input {
            background: linear-gradient(135deg, var(--pw-success) 0%, var(--pw-success-dark) 100%) !important;
            border-color: var(--pw-success-dark) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px var(--pw-success-glow) !important;
            pointer-events: none;
        }

        /* Baris info bawah: Toggle mata & {PASSWORD_LENGTH} karakter */
        .verify-otp-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            padding: 0 4px;
        }
        .verify-btn-toggle-eye {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: transparent;
            border: none;
            color: #64748B;
            font-family: inherit;
            font-size: 11.5px;
            font-weight: 600;
            cursor: pointer;
            padding: 3px 6px;
            border-radius: 6px;
            transition: color 0.2s ease, background 0.2s ease;
        }
        .verify-btn-toggle-eye:hover {
            color: var(--pw-primary-start);
            background: #F1F5F9;
        }
        .verify-btn-toggle-eye svg {
            width: 15px;
            height: 15px;
            fill: currentColor;
        }
        .verify-char-count-text {
            font-size: 11.5px;
            font-weight: 600;
            color: #94A3B8;
            text-align: right;
        }

        /* Error Alert */
        .verify-error-banner {
            display: none;
            align-items: center;
            gap: 8px;
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #B91C1C;
            padding: 9px 14px;
            border-radius: 12px;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 14px;
            animation: verifySlideFade 0.3s var(--pw-ease-smooth);
        }
        .verify-error-banner svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
            flex-shrink: 0;
        }
        @keyframes verifySlideFade {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status / Loading Indicator Otomatis (Tanpa Tombol Verifikasi) */
        .verify-auto-status {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin-top: 14px;
            padding: 9px 16px;
            border-radius: 12px;
            background: rgba(108, 92, 231, 0.08);
            border: 1px solid rgba(108, 92, 231, 0.16);
            color: #6C5CE7;
            font-size: 13.5px;
            font-weight: 700;
            transition: all 0.3s var(--pw-ease-smooth);
        }
        .state-loading .verify-auto-status {
            display: flex;
            animation: verifyStatusFadeIn 0.3s ease;
        }
        .state-success .verify-auto-status {
            display: flex;
            background: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.25);
            color: #16A34A;
        }
        .verify-spinner-inline {
            width: 15px;
            height: 15px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: verifySpinSpinner 0.75s linear infinite;
        }
        .state-success .verify-spinner-inline {
            display: none;
        }
        @keyframes verifyStatusFadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes verifySpinSpinner {
            to { transform: rotate(360deg); }
        }

        .verify-lock-text {
            display: none;
            font-size: 12px;
            font-weight: 700;
            color: var(--pw-error);
            margin-top: 10px;
        }

        #verifyConfettiCanvas {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            pointer-events: none;
            z-index: 999999;
        }

        @media (prefers-reduced-motion: reduce) {
            .verify-card-box.shake, .verify-rotating-ring, .verify-blob {
                animation: none !important;
            }
            #verifyConfettiCanvas {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-[#121216] text-white antialiased flex flex-col h-screen overflow-hidden">

    {{-- Top App Bar --}}
    <header class="bg-[#1c1c24]/95 backdrop-blur border-b border-white/10 px-4 py-3 shrink-0 z-20 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ url('/') }}" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs text-white transition shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    @if($isCourier)
                        <span class="text-xs font-black tracking-wider uppercase text-emerald-400">
                            <i class="fa-solid fa-motorcycle mr-1"></i> Mode Kurir
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 truncate">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                            {{ $delivery->courier_name }} ({{ $delivery->courier_phone }})
                        </span>
                        <a href="{{ route('zendo.track', $delivery->tracking_token) }}" title="Lihat tampilan dari sisi pelanggan" class="px-2 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white border border-white/10 text-[9px] font-bold transition">
                            <i class="fa-solid fa-eye"></i> Tampilan Pelanggan
                        </a>
                        <form method="POST" action="{{ route('zendo.courier.unbind') }}" class="inline ml-1" onsubmit="try { localStorage.removeItem('zendo_courier_permanent_access'); localStorage.removeItem('zendo_courier_device_token'); } catch(e){} return confirm('Keluar dari Mode Kurir pada perangkat ini? Anda harus memasukkan PIN lagi jika ingin masuk kembali.');">
                            @csrf
                            <button type="submit" title="Lepas perangkat ini dari Mode Kurir" class="px-2 py-0.5 rounded-md bg-zinc-800/80 hover:bg-rose-950 text-gray-400 hover:text-rose-300 border border-white/10 text-[9px] font-bold transition">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    @else
                        <span class="text-xs font-black tracking-wider uppercase text-amber-400">Nucomu Cafe x Zendo</span>
                        <span id="liveBadge" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Live Tracking
                        </span>
                        <button type="button" onclick="openCourierPinModal()" class="px-2 py-0.5 rounded-md bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 text-[9px] font-bold transition flex items-center gap-1 ml-1 cursor-pointer">
                            <i class="fa-solid fa-motorcycle"></i> Masuk Mode Kurir
                        </button>
                    @endif
                </div>
                <h1 class="text-sm font-extrabold text-white truncate">Pesanan #{{ $delivery->order_code }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span id="statusHeaderBadge" class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                {{ $delivery->status === 'completed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : ($delivery->status === 'almost_done' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-blue-500/20 text-blue-300 border border-blue-500/40') }}">
                {{ $delivery->status_label }}
            </span>
        </div>
    </header>

    @if(session('info'))
        <div class="bg-blue-500/20 border-b border-blue-500/30 text-blue-200 px-4 py-2.5 text-xs flex items-center justify-between gap-3 shrink-0 z-20">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-400"></i>
                <span>{{ session('info') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-blue-300 hover:text-white">&times;</button>
        </div>
    @endif

    {{-- Main Viewport: Leaflet Map + Floating Cards --}}
    <div class="relative flex-1 w-full min-h-0 overflow-hidden" style="min-height: 0;">
        {{-- Map Container --}}
        <div id="trackingMap" class="absolute inset-0 w-full h-full z-0 bg-[#16161d]"></div>

        {{-- Floating Recenter Map Button --}}
        <button type="button" onclick="recenterMap()"
                class="absolute right-4 top-4 z-10 w-10 h-10 rounded-full bg-[#1c1c24]/90 border border-white/15 text-white shadow-xl flex items-center justify-center hover:bg-white/20 active:scale-95 transition"
                title="Pusatkan Peta ke Kurir">
            <i class="fa-solid fa-location-crosshairs text-sm text-emerald-400"></i>
        </button>

        {{-- ======================================================== --}}
        {{-- MODE 1: WORKSPACE KURIR AKTIF (JIKA DIAKSES DARI LINK KURIR) --}}
        {{-- ======================================================== --}}
        @if($isCourier)
            <div class="absolute inset-x-0 bottom-0 z-10 max-w-xl mx-auto p-3 sm:p-4 max-h-[75vh] overflow-y-auto">
                <div class="bg-[#1c1c24]/95 backdrop-blur-xl border border-white/15 rounded-3xl p-4 sm:p-5 shadow-2xl space-y-3.5">

                    {{-- GPS Status Bar --}}
                    <div id="gpsBanner" class="bg-emerald-500/15 border border-emerald-500/30 rounded-2xl p-3 text-xs flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                            <div class="min-w-0">
                                <p class="font-bold text-emerald-300 text-xs truncate">🔒 GPS Lock Aktif (Anti Fake GPS)</p>
                                <p id="gpsStatusSub" class="text-[10px] text-gray-400 truncate">Menyiarkan lokasi ke pelanggan & peta live...</p>
                            </div>
                        </div>
                        <span id="gpsAccuracyBadge" class="px-2 py-0.5 rounded-lg bg-black/40 font-mono text-[10px] text-emerald-300 font-bold shrink-0">
                            Akurasi: Menghubungkan...
                        </span>
                    </div>

                    {{-- Fake GPS Warning Alert --}}
                    <div id="fakeGpsWarning" class="hidden bg-rose-500/20 border-2 border-rose-500 text-rose-200 rounded-2xl p-3 text-xs space-y-1">
                        <p class="font-extrabold text-white text-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-ban text-rose-400"></i>
                            <span>GPS TERKUNCI (Terdeteksi Mock Location / Fake GPS)</span>
                        </p>
                        <p id="fakeGpsReason" class="text-rose-200 text-[11px]"></p>
                    </div>

                    {{-- Rincian Tujuan & Jarak Realtime --}}
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <div class="bg-black/40 border border-white/10 rounded-2xl p-3">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Jarak ke Nucomu Kafe</p>
                            <p id="distToCafeText" class="text-base font-black text-amber-400 font-mono mt-0.5">-- m</p>
                            <p class="text-[10px] text-gray-500">Radius Ambil: ≤ 500m</p>
                        </div>
                        <div class="bg-black/40 border border-white/10 rounded-2xl p-3">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Jarak ke Pelanggan</p>
                            <p id="distToDestText" class="text-base font-black text-emerald-400 font-mono mt-0.5">-- m</p>
                            <p class="text-[10px] text-gray-500">Radius Selesai: ≤ 200m</p>
                        </div>
                    </div>

                    {{-- Tombol Aksi Kurir --}}
                    <div class="space-y-2">
                        {{-- Tombol 1: Ambil Pesanan di Kafe --}}
                        <div id="pickupActionWrap" class="{{ $delivery->status === 'assigned' ? '' : 'hidden' }}">
                            <button type="button" id="btnPickup" onclick="submitCourierStatus('picked_up')" disabled
                                    class="w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed">
                                <i class="fa-solid fa-box-open"></i>
                                <span id="btnPickupText">Dekati Kafe untuk Ambil (≤ 500m)</span>
                            </button>
                        </div>

                        {{-- Tombol 2: Selesaikan Pengantaran --}}
                        <div id="completeActionWrap" class="{{ in_array($delivery->status, ['picked_up', 'almost_done']) ? '' : 'hidden' }}">
                            <button type="button" id="btnComplete" onclick="submitCourierStatus('completed')" disabled
                                    class="w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed">
                                <i class="fa-solid fa-circle-check"></i>
                                <span id="btnCompleteText">Dekati Tujuan untuk Selesai (≤ 200m)</span>
                            </button>
                        </div>

                        {{-- Status Selesai --}}
                        @if($delivery->status === 'completed')
                            <div class="bg-emerald-500/20 border border-emerald-500/40 rounded-2xl p-3 text-center space-y-0.5">
                                <p class="text-emerald-300 font-black text-xs">✅ Pengantaran Telah Selesai</p>
                                <p class="text-[10px] text-gray-400">Terima kasih atas kerja keras Anda! Status Anda kembali siap menerima order berikutnya.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Info Alamat Pelanggan --}}
                    <div class="pt-2 border-t border-white/10 text-xs space-y-1">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Pemesan & Alamat</p>
                                <p class="text-white font-bold">{{ $delivery->customer_name }} — <span class="font-normal text-gray-300">{{ $delivery->delivery_address ?: 'Titik Lokasi Peta' }}</span></p>
                            </div>
                            @if($delivery->destination_lat && $delivery->destination_lng)
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $delivery->destination_lat }},{{ $delivery->destination_lng }}" target="_blank"
                                   class="px-2.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-[10px] flex items-center gap-1 shrink-0 transition">
                                    <i class="fa-solid fa-diamond-turn-right"></i> Maps ↗
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        {{-- ======================================================== --}}
        {{-- MODE 2: TAMPILAN PELACAKAN PELANGGAN (LIVE TRACKING GRAB) --}}
        {{-- ======================================================== --}}
        @else
            <div class="absolute inset-x-0 bottom-0 z-10 max-w-xl mx-auto p-3 sm:p-4 max-h-[75vh] overflow-y-auto">
                <div class="bg-[#1c1c24]/95 backdrop-blur-xl border border-white/15 rounded-3xl p-4 sm:p-5 shadow-2xl space-y-4">

                    {{-- ETA & Jarak Sisa --}}
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Estimasi Tiba (ETA)</p>
                            <p id="etaText" class="text-2xl font-black text-amber-400 font-mono mt-0.5">
                                {{ $initialPayload['eta_minutes'] ? '~'.$initialPayload['eta_minutes'].' Menit' : ($delivery->status === 'completed' ? 'Tiba di Lokasi' : 'Menghitung...') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Jarak Sisa</p>
                            <p id="remainingText" class="text-base font-extrabold text-white font-mono mt-0.5">
                                {{ $initialPayload['remaining_label'] ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Kurir Profile & Kontak WhatsApp --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-green-400 text-black flex items-center justify-center text-xl font-black shadow-lg shadow-emerald-500/20 shrink-0">
                                <i class="fa-solid fa-motorcycle"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-sm font-black text-white truncate">{{ $delivery->courier_name }}</h2>
                                <p class="text-xs text-emerald-400 font-mono flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                    <span>Kurir Terverifikasi (+{{ $delivery->courier_phone }})</span>
                                </p>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ $delivery->courier_phone }}" target="_blank"
                           class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-black font-extrabold text-xs flex items-center gap-1.5 shadow-md shadow-emerald-500/20 transition shrink-0">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Chat Kurir</span>
                        </a>
                    </div>

                    {{-- Status GPS Kurir & Anti Fake-GPS --}}
                    <div class="bg-black/40 border border-white/10 rounded-2xl p-3 text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 flex items-center gap-1.5 text-[11px]">
                                <i class="fa-solid fa-satellite-dish text-emerald-400"></i>
                                <span>Status GPS Kurir:</span>
                            </span>
                            <span id="gpsStatusBadge" class="font-bold text-[11px] {{ $delivery->hasCourierLocation() ? 'text-emerald-400' : 'text-amber-400' }}">
                                {{ $delivery->hasCourierLocation() ? '🟢 GPS Terhubung Aktif' : '🟡 Menunggu Sinyal GPS' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-gray-500">
                            <span>Proteksi GPS Lock:</span>
                            <span class="text-emerald-400 font-semibold">Aktif (Anti Fake GPS)</span>
                        </div>
                    </div>

                    {{-- Rincian Pesanan (Bisa Dilipat) --}}
                    <details class="group bg-white/5 rounded-2xl border border-white/10 overflow-hidden text-xs">
                        <summary class="px-3.5 py-2.5 font-bold text-gray-300 cursor-pointer flex items-center justify-between select-none hover:text-white">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-rose-400"></i>
                                <span>Tujuan: {{ Str::limit($delivery->delivery_address ?: 'Titik Lokasi Pelanggan', 30) }}</span>
                            </span>
                            <i class="fa-solid fa-chevron-down text-[10px] transition group-open:rotate-180"></i>
                        </summary>
                        <div class="p-3.5 pt-1 space-y-2 text-gray-400 border-t border-white/5">
                            <p class="text-white font-medium">{{ $delivery->delivery_address ?: '-' }}</p>
                            @if($delivery->order)
                                <div class="pt-2 border-t border-white/5 space-y-1 text-[11px]">
                                    <p class="font-bold text-gray-300">Menu Pesanan:</p>
                                    @foreach($delivery->order->items as $item)
                                        <div class="flex justify-between">
                                            <span>{{ $item->qty }}x {{ $item->menu_name }}</span>
                                            <span class="font-mono text-gray-300">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between font-bold text-white pt-1 border-t border-white/10">
                                        <span>Total:</span>
                                        <span class="font-mono text-amber-400">Rp {{ number_format($delivery->order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </details>

                </div>
            </div>
        @endif
    </div>

    {{-- JAVASCRIPT ENGINE (MAP, GPS, CLIENT HAIVERSINE, REALTIME STREAMING) --}}
    <script>
        const isCourierMode = {{ $isCourier ? 'true' : 'false' }};
        const initialData = @json($initialPayload);
        const dataUrl = '{{ route("zendo.track.data", $delivery->tracking_token, false) }}';
        const locationUrl = '{{ route("zendo.track.location", $delivery->tracking_token, false) }}';
        const statusUrl = '{{ route("zendo.track.status", $delivery->tracking_token, false) }}';

        const cafeCoords = {
            lat: {{ $cafeLat }},
            lng: {{ $cafeLng }}
        };
        const destCoords = {
            lat: {{ $delivery->destination_lat ?? 'null' }},
            lng: {{ $delivery->destination_lng ?? 'null' }}
        };

        let map = null;
        let courierMarker = null;
        let cafeMarker = null;
        let destMarker = null;
        let trailPolyline = null;
        let firstFitDone = false;
        let lastPosition = null;
        let isSendingGps = false;

        // Initialize Map with Official Free OpenStreetMap Basemap (NO API KEY REQUIRED)
        function initMap() {
            if (typeof L === 'undefined') {
                console.warn('Menunggu library Leaflet termuat...');
                setTimeout(initMap, 150);
                return;
            }

            if (map) return;

            const cafe = initialData.cafe || { lat: cafeCoords.lat, lng: cafeCoords.lng };
            const dest = initialData.destination || { lat: destCoords.lat, lng: destCoords.lng };
            const courier = initialData.courier;

            const centerLat = (courier && courier.lat !== null && !isNaN(courier.lat)) 
                ? courier.lat 
                : (dest && dest.lat ? dest.lat : (cafe ? cafe.lat : -8.0765));
            const centerLng = (courier && courier.lng !== null && !isNaN(courier.lng)) 
                ? courier.lng 
                : (dest && dest.lng ? dest.lng : (cafe ? cafe.lng : 111.9187));

            map = L.map('trackingMap', {
                zoomControl: false,
                attributionControl: true
            }).setView([centerLat, centerLng], 14);

            // OpenStreetMap Standard Tiles (100% Free, No API Key Required)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // 1. Cafe Marker
            if (cafe && cafe.lat && cafe.lng) {
                const cafeIcon = L.divIcon({
                    className: '',
                    html: `
                        <div class="w-9 h-9 rounded-full bg-[#2B1F1A] border-2 border-amber-400 flex items-center justify-center text-amber-300 text-sm shadow-xl">
                            <i class="fa-solid fa-mug-hot"></i>
                        </div>
                    `,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18]
                });
                cafeMarker = L.marker([cafe.lat, cafe.lng], { icon: cafeIcon }).addTo(map)
                    .bindPopup('<strong class="text-xs">Nucomu Cafe</strong><br><span class="text-[10px]">Titik Asal Pesanan</span>');
            }

            // 2. Destination Marker (Customer)
            if (dest && dest.lat && dest.lng) {
                const destIcon = L.divIcon({
                    className: '',
                    html: `
                        <div class="w-9 h-9 rounded-full bg-rose-600 border-2 border-white flex items-center justify-center text-white text-sm shadow-xl">
                            <i class="fa-solid fa-house-chimney"></i>
                        </div>
                    `,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18]
                });
                destMarker = L.marker([dest.lat, dest.lng], { icon: destIcon }).addTo(map)
                    .bindPopup('<strong class="text-xs">Titik Pengantaran</strong><br><span class="text-[10px]">' + (dest.address || 'Alamat Pelanggan') + '</span>');
            }

            // 3. Polyline Route Trail
            trailPolyline = L.polyline([], {
                color: '#10b981',
                weight: 4,
                opacity: 0.85,
                dashArray: '6, 8',
                lineJoin: 'round'
            }).addTo(map);

            // 4. Update initial marker & trail
            if (courier && courier.lat !== null && !isNaN(courier.lat)) {
                updateCourierMarker(courier);
            }
            if (initialData.trail) {
                updateTrail(initialData.trail);
            }

            fitBounds();

            // CRITICAL: Refresh ukuran map agar tile langsung render sempurna di flexbox
            setTimeout(() => {
                if (map) {
                    map.invalidateSize();
                    fitBounds();
                }
            }, 150);
            setTimeout(() => {
                if (map) {
                    map.invalidateSize();
                }
            }, 600);

            window.addEventListener('resize', () => {
                if (map) map.invalidateSize();
            });

            // If initial data has coordinates, compute initial distances in courier mode
            if (isCourierMode && courier && courier.lat !== null) {
                updateCourierDistances(courier.lat, courier.lng, courier.accuracy || 10);
            }
        }

        function createCourierIcon(heading) {
            const rot = (typeof heading === 'number' && !isNaN(heading)) ? heading : 0;
            return L.divIcon({
                className: '',
                html: `
                    <div class="relative w-12 h-12 flex items-center justify-center pulse-marker rounded-full">
                        <div class="courier-motor-icon w-10 h-10 rounded-full bg-emerald-500 border-2 border-white shadow-2xl flex items-center justify-center text-black font-black text-base"
                             style="transform: rotate(${rot}deg);">
                            <i class="fa-solid fa-motorcycle"></i>
                        </div>
                    </div>
                `,
                iconSize: [48, 48],
                iconAnchor: [24, 24]
            });
        }

        function updateCourierMarker(courier) {
            if (!courier || courier.lat === null || courier.lng === null) {
                return;
            }

            const latLng = [courier.lat, courier.lng];
            const icon = createCourierIcon(courier.heading);

            if (!courierMarker) {
                courierMarker = L.marker(latLng, { icon: icon }).addTo(map)
                    .bindPopup('<strong>' + (courier.name || 'Kurir Zendo') + '</strong><br>Sedang mengantar pesanan.');
            } else {
                courierMarker.setLatLng(latLng);
                courierMarker.setIcon(icon);
            }
        }

        function updateTrail(trailPoints) {
            if (!trailPolyline) return;
            if (Array.isArray(trailPoints) && trailPoints.length > 0) {
                trailPolyline.setLatLngs(trailPoints);
            }
        }

        function fitBounds() {
            if (!map) return;
            const group = [];
            if (cafeMarker) group.push(cafeMarker.getLatLng());
            if (destMarker) group.push(destMarker.getLatLng());
            if (courierMarker) group.push(courierMarker.getLatLng());

            if (group.length > 0) {
                try {
                    map.fitBounds(L.latLngBounds(group), {
                        padding: [60, 60],
                        maxZoom: 16
                    });
                } catch (e) {
                    console.warn('fitBounds:', e);
                }
            }
        }

        function recenterMap() {
            if (!map) return;
            map.invalidateSize();
            if (courierMarker) {
                map.setView(courierMarker.getLatLng(), 16);
            } else {
                fitBounds();
            }
        }

        // Haversine Distance in meters (instant client calculation)
        function computeHaversineMeters(lat1, lon1, lat2, lon2) {
            const R = 6371e3;
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        function formatDistanceMeters(meters) {
            if (meters === null || meters === undefined || isNaN(meters)) return '-- m';
            if (meters >= 1000) {
                return (meters / 1000).toFixed(1).replace('.', ',') + ' km';
            }
            return Math.round(meters) + ' m';
        }

        // Update Courier distances & action buttons immediately in UI
        function updateCourierDistances(curLat, curLng, accuracy) {
            const distCafeEl = document.getElementById('distToCafeText');
            const distDestEl = document.getElementById('distToDestText');
            const accBadge = document.getElementById('gpsAccuracyBadge');

            if (accBadge && accuracy !== undefined) {
                accBadge.innerText = 'Akurasi: ±' + Math.round(accuracy) + ' m';
            }

            const distToCafe = computeHaversineMeters(curLat, curLng, cafeCoords.lat, cafeCoords.lng);
            if (distCafeEl) {
                distCafeEl.innerText = formatDistanceMeters(distToCafe);
            }

            let distToDest = null;
            if (destCoords.lat && destCoords.lng) {
                distToDest = computeHaversineMeters(curLat, curLng, destCoords.lat, destCoords.lng);
                if (distDestEl) {
                    distDestEl.innerText = formatDistanceMeters(distToDest);
                }
            }

            // Update Button Ambil
            const btnPickup = document.getElementById('btnPickup');
            const btnPickupText = document.getElementById('btnPickupText');
            if (btnPickup) {
                if (distToCafe <= 500) {
                    btnPickup.disabled = false;
                    btnPickup.className = 'w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition cursor-pointer';
                    btnPickupText.innerText = '✅ Ambil Pesanan Sekarang (Sudah di Kafe)';
                } else {
                    btnPickup.disabled = true;
                    btnPickup.className = 'w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed';
                    btnPickupText.innerText = 'Dekati Kafe untuk Ambil (≤ 500m)';
                }
            }

            // Update Button Selesai
            const btnComplete = document.getElementById('btnComplete');
            const btnCompleteText = document.getElementById('btnCompleteText');
            if (btnComplete) {
                if (distToDest !== null && distToDest <= 200) {
                    btnComplete.disabled = false;
                    btnComplete.className = 'w-full py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition cursor-pointer';
                    btnCompleteText.innerText = '✅ Selesaikan Pengantaran (Sudah di Tujuan)';
                } else {
                    btnComplete.disabled = true;
                    btnComplete.className = 'w-full py-3.5 rounded-2xl bg-zinc-800 text-gray-400 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-not-allowed';
                    btnCompleteText.innerText = 'Dekati Tujuan untuk Selesai (≤ 200m)';
                }
            }
        }

        // Send GPS Reading to server with Anti-Fake GPS checks
        async function sendGpsReading(pos) {
            if (isSendingGps) return;
            isSendingGps = true;

            const coords = pos.coords;
            updateCourierDistances(coords.latitude, coords.longitude, coords.accuracy);

            // Update marker on map
            updateCourierMarker({
                lat: coords.latitude,
                lng: coords.longitude,
                heading: coords.heading,
                name: '{{ $delivery->courier_name }}'
            });

            // Anti-fake GPS heuristics
            const signals = [];
            if (coords.accuracy <= 0) signals.push('accuracy_zero');
            if (coords.isMock || coords.mocked || pos.isMock) signals.push('mock_flag_detected');
            if (lastPosition) {
                const dt = Math.max(1, (pos.timestamp - lastPosition.timestamp) / 1000);
                const dist = computeHaversineMeters(lastPosition.coords.latitude, lastPosition.coords.longitude, coords.latitude, coords.longitude);
                if (dist > 300 && (dist / dt) > 45) { // > 162 km/h
                    signals.push('teleport_velocity');
                }
            }

            const payload = {
                lat: coords.latitude,
                lng: coords.longitude,
                accuracy: coords.accuracy || 10,
                speed: coords.speed,
                heading: coords.heading,
                altitude: coords.altitude,
                timestamp: pos.timestamp || Date.now(),
                signals: signals
            };

            try {
                const res = await fetch(locationUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (res.status === 403 || data.revoked) {
                    try {
                        localStorage.removeItem('zendo_courier_permanent_access');
                        localStorage.removeItem('zendo_courier_device_token');
                    } catch (e) {}
                    alert(data.message || 'Akses kurir pada perangkat ini telah direset oleh admin.');
                    window.location.reload();
                    return;
                }
                handleGpsServerFeedback(data);
            } catch (err) {
                console.warn('Gagal streaming GPS kurir:', err);
            } finally {
                isSendingGps = false;
                lastPosition = pos;
            }
        }

        function handleGpsServerFeedback(data) {
            const warningBox = document.getElementById('fakeGpsWarning');
            const reasonEl = document.getElementById('fakeGpsReason');

            if (data.locked || data.suspicious) {
                if (warningBox && reasonEl) {
                    reasonEl.innerText = data.message || 'Terdeteksi indikasi Fake GPS.';
                    warningBox.classList.remove('hidden');
                }
            } else if (warningBox) {
                warningBox.classList.add('hidden');
            }

            if (data.payload && data.payload.trail) {
                updateTrail(data.payload.trail);
            }
        }

        // Submit Status Change (Ambil Pesanan / Selesai)
        async function submitCourierStatus(status) {
            try {
                const res = await fetch(statusUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                });
                const data = await res.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal mengubah status.');
                }
            } catch (err) {
                alert('Terjadi kesalahan koneksi.');
            }
        }

        // Start High Accuracy GPS Streaming for Courier
        function startCourierGps() {
            if (!navigator.geolocation) {
                const badge = document.getElementById('gpsAccuracyBadge');
                if (badge) badge.innerText = 'GPS: Tidak didukung browser';
                return;
            }

            // 1. Immediate position attempt
            navigator.geolocation.getCurrentPosition(
                sendGpsReading,
                (err) => {
                    console.warn('GPS initial err:', err.message);
                    const badge = document.getElementById('gpsAccuracyBadge');
                    if (badge) badge.innerText = err.code === 1 ? 'GPS: Izin lokasi ditolak' : 'GPS: Mencari sinyal...';
                },
                { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
            );

            // 2. Continuous position watcher
            navigator.geolocation.watchPosition(
                sendGpsReading,
                (err) => {
                    console.warn('GPS watch err:', err.message);
                },
                { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 }
            );
        }

        // Customer Mode: Poll Live Status every 3.5 seconds
        async function pollTracking() {
            try {
                const res = await fetch(dataUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    applyCustomerPayload(data);
                }
            } catch (err) {
                console.warn('Gagal memuat live data:', err);
            }
        }

        function applyCustomerPayload(data) {
            if (!data) return;

            const etaEl = document.getElementById('etaText');
            if (etaEl) {
                etaEl.innerText = data.eta_minutes ? `~${data.eta_minutes} Menit` : (data.status === 'completed' ? 'Tiba di Lokasi' : 'Menghitung...');
            }

            const remEl = document.getElementById('remainingText');
            if (remEl) {
                remEl.innerText = data.remaining_label || '-';
            }

            const badgeEl = document.getElementById('statusHeaderBadge');
            if (badgeEl) {
                badgeEl.innerText = data.status_label || '';
            }

            const gpsBadge = document.getElementById('gpsStatusBadge');
            if (gpsBadge) {
                if (data.gps && data.gps.locked) {
                    gpsBadge.className = 'font-bold text-[11px] text-rose-400';
                    gpsBadge.innerText = '🔴 GPS Terkunci (Fake GPS Terdeteksi)';
                } else if (data.courier && data.courier.lat !== null) {
                    gpsBadge.className = 'font-bold text-[11px] text-emerald-400';
                    gpsBadge.innerText = data.courier.is_stale ? '🟡 Sinyal Terakhir ' + data.courier.seconds_ago + 's lalu' : '🟢 GPS Terhubung Aktif';
                } else {
                    gpsBadge.className = 'font-bold text-[11px] text-amber-400';
                    gpsBadge.innerText = '🟡 Menunggu Sinyal GPS Kurir';
                }
            }

            updateCourierMarker(data.courier);
            updateTrail(data.trail);

            if (map && (!map._size || map._size.x === 0 || map._size.y === 0)) {
                map.invalidateSize();
            }

            if (!firstFitDone && data.courier && data.courier.lat) {
                fitBounds();
                firstFitDone = true;
            }
        }

        // ==========================================================
        // LOGIKA VERIFIKASI PIN KURIR (GAYA OTP DINAMIS)
        // ==========================================================
        const PASSWORD_LENGTH = {{ (int) ($courierPinLength ?? 4) }};
        const AUTO_SUBMIT = true;

        const VerifyState = {
            IDLE: 'idle',
            LOADING: 'loading',
            SUCCESS: 'success',
            ERROR: 'error',
            LOCKED: 'locked'
        };

        let currentVerifyState = VerifyState.IDLE;
        let verifyFailedAttempts = 0;
        let verifyCountdownTimer = null;
        let isPasswordRevealed = false;
        let rawCharacters = new Array(PASSWORD_LENGTH).fill('');
        let maskTimers = new Array(PASSWORD_LENGTH).fill(null);
        let verifyRedirectUrl = '';

        function renderOtpBoxes() {
            const container = document.getElementById('verifyOtpContainer');
            const countText = document.getElementById('verifyCharCountText');
            if (!container) return;

            container.innerHTML = '';
            if (countText) countText.textContent = `${PASSWORD_LENGTH} karakter`;

            let boxW = 52;
            let boxH = 60;
            if (PASSWORD_LENGTH > 8) {
                boxW = 40; boxH = 52;
            } else if (PASSWORD_LENGTH > 6) {
                boxW = 46; boxH = 56;
            }

            for (let i = 0; i < PASSWORD_LENGTH; i++) {
                const wrap = document.createElement('div');
                wrap.className = 'verify-otp-box-wrap';

                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'verify-otp-input';
                input.maxLength = 1;
                input.autocomplete = 'off';
                input.spellcheck = false;
                input.autocapitalize = 'none';
                input.inputMode = 'numeric';
                input.pattern = '[0-9]*';
                input.dataset.index = i;
                input.setAttribute('aria-label', `Karakter ${i + 1} dari ${PASSWORD_LENGTH}`);

                input.style.setProperty('--box-w', `${boxW}px`);
                input.style.setProperty('--box-h', `${boxH}px`);
                input.style.animationDelay = `${i * 0.08}s`;

                input.addEventListener('input', (e) => handleOtpInput(e, i));
                input.addEventListener('keydown', (e) => handleOtpKeyDown(e, i));
                input.addEventListener('paste', handleOtpPaste);
                input.addEventListener('focus', () => setTimeout(() => input.select(), 10));

                wrap.appendChild(input);
                container.appendChild(wrap);
            }
        }

        const getOtpInputs = () => {
            const c = document.getElementById('verifyOtpContainer');
            return c ? Array.from(c.querySelectorAll('.verify-otp-input')) : [];
        };

        function setBoxCharacter(index, char) {
            const inputs = getOtpInputs();
            const input = inputs[index];
            if (!input) return;

            rawCharacters[index] = char;

            if (!char) {
                input.value = '';
                if (maskTimers[index]) clearTimeout(maskTimers[index]);
                return;
            }

            input.classList.remove('pop');
            void input.offsetWidth;
            input.classList.add('pop');

            if (isPasswordRevealed) {
                input.value = char;
            } else {
                input.value = char;
                if (maskTimers[index]) clearTimeout(maskTimers[index]);
                maskTimers[index] = setTimeout(() => {
                    if (!isPasswordRevealed && rawCharacters[index]) {
                        input.value = '•';
                    }
                }, 400);
            }
        }

        function handleOtpInput(e, index) {
            if (currentVerifyState === VerifyState.LOCKED) return;
            if (currentVerifyState === VerifyState.ERROR) {
                currentVerifyState = VerifyState.IDLE;
            }

            const char = e.target.value.slice(-1);

            if (char) {
                setBoxCharacter(index, char);

                const card = document.getElementById('verifyCardBox');
                const banner = document.getElementById('verifyErrorBanner');
                if (card && card.classList.contains('state-error')) {
                    card.classList.remove('state-error');
                }
                if (banner) banner.style.display = 'none';

                const inputs = getOtpInputs();
                if (index < PASSWORD_LENGTH - 1) {
                    inputs[index + 1].focus();
                } else {
                    // Jika di kotak terakhir, tutup keyboard HP agar pengguna langsung melihat proses verifikasi
                    if (document.activeElement && typeof document.activeElement.blur === 'function') {
                        document.activeElement.blur();
                    }
                }
                checkOtpCompletion();
            } else {
                setBoxCharacter(index, '');
                checkOtpCompletion();
            }
        }

        function handleOtpKeyDown(e, index) {
            if (currentVerifyState === VerifyState.LOCKED) return;
            const inputs = getOtpInputs();

            if (e.key === 'Backspace') {
                if (!rawCharacters[index] && index > 0) {
                    e.preventDefault();
                    setBoxCharacter(index - 1, '');
                    inputs[index - 1].focus();
                    checkOtpCompletion();
                } else {
                    setBoxCharacter(index, '');
                    checkOtpCompletion();
                }
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                if (index > 0) inputs[index - 1].focus();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                if (index < PASSWORD_LENGTH - 1) inputs[index + 1].focus();
            } else if (e.key === 'Home') {
                e.preventDefault();
                if (inputs[0]) inputs[0].focus();
            } else if (e.key === 'End') {
                e.preventDefault();
                if (inputs[PASSWORD_LENGTH - 1]) inputs[PASSWORD_LENGTH - 1].focus();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                submitCourierPin();
            }
        }

        function handleOtpPaste(e) {
            e.preventDefault();
            if (currentVerifyState === VerifyState.LOCKED) return;

            const text = (e.clipboardData || window.clipboardData).getData('text').trim();
            if (!text) return;

            const chars = text.slice(0, PASSWORD_LENGTH).split('');
            chars.forEach((c, idx) => setBoxCharacter(idx, c));

            const inputs = getOtpInputs();
            const nextIdx = Math.min(chars.length, PASSWORD_LENGTH - 1);
            if (inputs[nextIdx]) inputs[nextIdx].focus();
            checkOtpCompletion();
        }

        function checkOtpCompletion() {
            const inputs = getOtpInputs();
            if (inputs.length === 0) return;

            // Pastikan setiap karakter tersinkronisasi jika ada pengisian otomatis di HP
            inputs.forEach((inp, idx) => {
                if (!rawCharacters[idx] && inp.value && inp.value !== '•') {
                    rawCharacters[idx] = inp.value;
                }
            });

            const allFilled = rawCharacters.length === PASSWORD_LENGTH && rawCharacters.every(c => c && c !== '');

            if (allFilled && currentVerifyState !== VerifyState.LOADING && currentVerifyState !== VerifyState.LOCKED) {
                submitCourierPin();
            }
        }

        function toggleVerifyPassword() {
            isPasswordRevealed = !isPasswordRevealed;
            const inputs = getOtpInputs();
            const showSvg = document.getElementById('verifyEyeSvgShow');
            const hideSvg = document.getElementById('verifyEyeSvgHide');
            const label = document.getElementById('verifyEyeLabel');
            const btn = document.getElementById('verifyBtnToggleEye');

            if (isPasswordRevealed) {
                if (showSvg) showSvg.style.display = 'none';
                if (hideSvg) hideSvg.style.display = 'block';
                if (label) label.textContent = 'Sembunyikan';
                if (btn) btn.setAttribute('aria-label', 'Sembunyikan karakter password');

                inputs.forEach((input, idx) => {
                    if (maskTimers[idx]) clearTimeout(maskTimers[idx]);
                    input.value = rawCharacters[idx] || '';
                });
            } else {
                if (showSvg) showSvg.style.display = 'block';
                if (hideSvg) hideSvg.style.display = 'none';
                if (label) label.textContent = 'Lihat';
                if (btn) btn.setAttribute('aria-label', 'Tampilkan karakter password');

                inputs.forEach((input, idx) => {
                    if (rawCharacters[idx]) input.value = '•';
                });
            }
        }

        function openCourierPinModal() {
            if (window.location.search.includes('view=customer')) {
                window.location.href = '{{ route("zendo.track", $delivery->tracking_token, false) }}';
                return;
            }

            const overlay = document.getElementById('verifyModalOverlay');
            if (overlay) {
                overlay.classList.add('active');
                resetVerifyState();
                setTimeout(() => {
                    const inputs = getOtpInputs();
                    if (inputs[0]) inputs[0].focus();
                }, 150);
            }
        }

        function closeCourierPinModal() {
            if (currentVerifyState === VerifyState.LOADING) return;
            const overlay = document.getElementById('verifyModalOverlay');
            if (overlay) overlay.classList.remove('active');
        }

        function resetVerifyState() {
            if (currentVerifyState === VerifyState.LOCKED) return;
            currentVerifyState = VerifyState.IDLE;
            const card = document.getElementById('verifyCardBox');
            const cardTitle = document.getElementById('verifyCardTitle');
            const cardSubtitle = document.getElementById('verifyCardSubtitle');
            const errorBanner = document.getElementById('verifyErrorBanner');
            const statusText = document.getElementById('verifyStatusText');

            if (card) {
                card.classList.remove('state-loading', 'state-success', 'state-error', 'shake');
            }
            rawCharacters = new Array(PASSWORD_LENGTH).fill('');
            const inputs = getOtpInputs();
            inputs.forEach(input => {
                input.value = '';
                input.disabled = false;
            });

            if (statusText) statusText.textContent = 'Memeriksa PIN...';
            if (cardTitle) cardTitle.textContent = 'Verifikasi Dirimu';
            if (cardSubtitle) {
                cardSubtitle.innerHTML = 'Masukkan PIN akses kurir Nucomu<br><span class="verify-account-chip"><svg style="width:12px;height:12px;fill:currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg> {{ $delivery->courier_name }} ({{ substr($delivery->courier_phone, 0, 4) }}●●●●{{ substr($delivery->courier_phone, -2) }})</span>';
            }
            if (errorBanner) errorBanner.style.display = 'none';
        }

        async function submitCourierPin(e) {
            if (e && typeof e.preventDefault === 'function') e.preventDefault();
            if (currentVerifyState === VerifyState.LOADING || currentVerifyState === VerifyState.LOCKED) return;

            if (currentVerifyState === VerifyState.SUCCESS) {
                if (verifyRedirectUrl) window.location.href = verifyRedirectUrl;
                return;
            }

            const allFilled = rawCharacters.every(c => c !== '');
            if (!allFilled) return;

            const password = rawCharacters.join('');
            const card = document.getElementById('verifyCardBox');
            const statusText = document.getElementById('verifyStatusText');
            const errorBanner = document.getElementById('verifyErrorBanner');
            const errorMsg = document.getElementById('verifyErrorMsg');

            currentVerifyState = VerifyState.LOADING;
            card.classList.remove('state-error');
            card.classList.add('state-loading');
            const inputs = getOtpInputs();
            inputs.forEach(input => input.disabled = true);
            if (statusText) statusText.textContent = 'Memeriksa PIN...';
            if (errorBanner) errorBanner.style.display = 'none';

            try {
                const startTime = Date.now();
                const res = await fetch('{{ route("zendo.track.pin", $delivery->tracking_token, false) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pin: password })
                });

                const data = await res.json();
                const elapsed = Date.now() - startTime;
                const remainingWait = Math.max(0, 900 - elapsed);

                setTimeout(() => {
                    if (data.success && data.redirect_url) {
                        currentVerifyState = VerifyState.SUCCESS;
                        verifyRedirectUrl = data.redirect_url;
                        card.classList.remove('state-loading', 'state-error');
                        card.classList.add('state-success');

                        const cardTitle = document.getElementById('verifyCardTitle');
                        const cardSubtitle = document.getElementById('verifyCardSubtitle');
                        if (cardTitle) cardTitle.textContent = 'Berhasil Diverifikasi';
                        if (cardSubtitle) cardSubtitle.innerHTML = 'PIN cocok! Mengalihkan ke mode kurir...';
                        if (statusText) statusText.textContent = '✓ PIN Cocok! Mengalihkan...';

                        inputs.forEach((input, idx) => {
                            input.style.transition = `all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) ${idx * 0.08}s`;
                            input.value = '•';
                        });

                        try {
                            localStorage.setItem('zendo_courier_permanent_access', '1');
                            if (data.device_token) {
                                localStorage.setItem('zendo_courier_device_token', data.device_token);
                            }
                        } catch (e) {}

                        launchVerifyConfetti();

                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1200);

                    } else {
                        verifyFailedAttempts++;
                        currentVerifyState = VerifyState.ERROR;
                        card.classList.remove('state-loading');
                        card.classList.add('state-error');

                        card.classList.remove('shake');
                        void card.offsetWidth;
                        card.classList.add('shake');

                        inputs.forEach(input => input.disabled = false);

                        setTimeout(() => {
                            rawCharacters = new Array(PASSWORD_LENGTH).fill('');
                            inputs.forEach(input => input.value = '');

                            if (verifyFailedAttempts >= 3) {
                                lockVerifyTimer(30);
                            } else {
                                currentVerifyState = VerifyState.IDLE;
                                if (errorBanner && errorMsg) {
                                    errorBanner.style.display = 'flex';
                                    errorMsg.textContent = data.message || `PIN salah. Coba lagi (${verifyFailedAttempts}/3).`;
                                }
                                if (inputs[0]) inputs[0].focus();
                            }
                        }, 420);
                    }
                }, remainingWait);

            } catch (err) {
                currentVerifyState = VerifyState.IDLE;
                card.classList.remove('state-loading');
                inputs.forEach(input => input.disabled = false);
                if (errorBanner && errorMsg) {
                    errorBanner.style.display = 'flex';
                    errorMsg.textContent = 'Gagal menghubungi server. Periksa koneksi internet.';
                }
            }
        }

        function lockVerifyTimer(seconds) {
            currentVerifyState = VerifyState.LOCKED;
            const errorBanner = document.getElementById('verifyErrorBanner');
            const errorMsg = document.getElementById('verifyErrorMsg');
            const lockText = document.getElementById('verifyLockText');
            const inputs = getOtpInputs();

            inputs.forEach(input => input.disabled = true);
            if (errorBanner && errorMsg) {
                errorBanner.style.display = 'flex';
                errorMsg.textContent = 'Terlalu banyak percobaan salah. Kotak terkunci.';
            }

            let rem = seconds;
            if (lockText) {
                lockText.style.display = 'block';
                lockText.textContent = `Coba lagi dalam 0:${rem < 10 ? '0' + rem : rem}`;
            }

            if (verifyCountdownTimer) clearInterval(verifyCountdownTimer);
            verifyCountdownTimer = setInterval(() => {
                rem--;
                if (rem <= 0) {
                    clearInterval(verifyCountdownTimer);
                    unlockVerifyState();
                } else if (lockText) {
                    lockText.textContent = `Coba lagi dalam 0:${rem < 10 ? '0' + rem : rem}`;
                }
            }, 1000);
        }

        function unlockVerifyState() {
            currentVerifyState = VerifyState.IDLE;
            verifyFailedAttempts = 0;
            const lockText = document.getElementById('verifyLockText');
            const errorBanner = document.getElementById('verifyErrorBanner');
            const card = document.getElementById('verifyCardBox');
            const inputs = getOtpInputs();

            if (card) card.classList.remove('state-error');
            inputs.forEach(input => input.disabled = false);
            if (lockText) lockText.style.display = 'none';
            if (errorBanner) errorBanner.style.display = 'none';
            if (inputs[0]) inputs[0].focus();
        }

        function launchVerifyConfetti() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            const canvas = document.getElementById('verifyConfettiCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            const colors = ['#6C5CE7', '#4F6BFF', '#22C55E', '#F59E0B', '#EC4899', '#38BDF8'];
            const particles = [];
            for (let i = 0; i < 75; i++) {
                particles.push({
                    x: canvas.width / 2 + (Math.random() * 80 - 40),
                    y: canvas.height / 2 - 80,
                    w: Math.random() * 8 + 5,
                    h: Math.random() * 6 + 4,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    vx: (Math.random() - 0.5) * 12,
                    vy: Math.random() * -11 - 4,
                    gravity: 0.35,
                    rotation: Math.random() * 360,
                    rotSpeed: (Math.random() - 0.5) * 10,
                    opacity: 1
                });
            }

            let animId;
            function render() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                let count = 0;
                particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;
                    p.vy += p.gravity;
                    p.rotation += p.rotSpeed;
                    p.opacity -= 0.01;
                    if (p.opacity > 0 && p.y < canvas.height) {
                        count++;
                        ctx.save();
                        ctx.translate(p.x, p.y);
                        ctx.rotate((p.rotation * Math.PI) / 180);
                        ctx.globalAlpha = Math.max(0, p.opacity);
                        ctx.fillStyle = p.color;
                        ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                        ctx.restore();
                    }
                });
                if (count > 0) animId = requestAnimationFrame(render);
                else ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            render();
        }

        function startAllTrackingInit() {
            initMap();
            renderOtpBoxes();

            // Auto-restore Mode Kurir permanen jika perangkat ini sebelumnya sudah diverifikasi via PIN
            if (!isCourierMode && !window.location.search.includes('view=customer')) {
                try {
                    const savedToken = localStorage.getItem('zendo_courier_device_token');
                    const hasPermanent = localStorage.getItem('zendo_courier_permanent_access') === '1';
                    if (hasPermanent && savedToken) {
                        fetch('{{ route("zendo.track.pin", $delivery->tracking_token, false) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ device_token: savedToken })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.redirect_url) {
                                window.location.href = data.redirect_url;
                            }
                        })
                        .catch(() => {});
                    }
                } catch (e) {}
            }

            if (isCourierMode) {
                startCourierGps();
            } else {
                setInterval(pollTracking, 3500);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startAllTrackingInit);
        } else {
            startAllTrackingInit();
        }
    </script>

    <!-- ==========================================================
         MODAL VERIFIKASI PASSWORD KURIR (GAYA OTP DINAMIS)
         ========================================================== -->
    <div id="verifyModalOverlay" class="verify-modal-overlay" onclick="if(event.target===this) closeCourierPinModal();">
        
        <!-- Ambient Globs -->
        <div class="verify-blob verify-blob-1" aria-hidden="true"></div>
        <div class="verify-blob verify-blob-2" aria-hidden="true"></div>

        <!-- Confetti Canvas -->
        <canvas id="verifyConfettiCanvas" aria-hidden="true"></canvas>

        <div class="verify-card-box" id="verifyCardBox">
            
            <!-- Tombol Close (X) bulat abu-abu di pojok kanan atas -->
            <button type="button" class="verify-close-btn" onclick="closeCourierPinModal()" title="Tutup" aria-label="Tutup Verifikasi">&times;</button>

            <!-- 1. Ikon Gembok Putih di Lingkaran Gradient & Cincin Dashed Putar -->
            <div class="verify-icon-wrap" aria-hidden="true">
                <svg class="verify-rotating-ring" viewBox="0 0 82 82">
                    <defs>
                        <linearGradient id="verifyDashedRingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#6C5CE7" stop-opacity="0.9" />
                            <stop offset="50%" stop-color="#4F6BFF" stop-opacity="0.6" />
                            <stop offset="100%" stop-color="#C084FC" stop-opacity="0.2" />
                        </linearGradient>
                    </defs>
                    <circle cx="41" cy="41" r="38" />
                </svg>

                <!-- Cincin Hijau Denyut Sukses -->
                <div class="verify-pulse-ring-success"></div>

                <!-- Lingkaran Badge Utama -->
                <div class="verify-icon-badge" id="verifyIconBadge">
                    <svg class="verify-icon-lock" viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                    <svg class="verify-icon-check" viewBox="0 0 24 24" style="display: none; fill: none; stroke: #FFFFFF; stroke-width: 3.2; stroke-linecap: round; stroke-linejoin: round;">
                        <path class="verify-check-path" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <!-- 2 & 3. Judul, Subjudul & Chip Akun -->
            <div class="text-center">
                <h2 class="verify-title" id="verifyCardTitle">Verifikasi Dirimu</h2>
                <p class="verify-subtitle" id="verifyCardSubtitle">
                    Masukkan PIN akses kurir Nucomu<br>
                    <span class="verify-account-chip">
                        <svg style="width:12px;height:12px;fill:currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        {{ $delivery->courier_name }} ({{ substr($delivery->courier_phone, 0, 4) }}●●●●{{ substr($delivery->courier_phone, -2) }})
                    </span>
                </p>
            </div>

            <!-- Pesan Error / Alert -->
            <div class="verify-error-banner" id="verifyErrorBanner" role="alert" aria-live="assertive">
                <svg viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 9a1 1 0 012 0v3a1 1 0 11-2 0V9zm1-4a1 1 0 100 2 1 1 0 000-2z"/>
                </svg>
                <span id="verifyErrorMsg">PIN akses salah. Coba lagi.</span>
            </div>

            <!-- Form Kotak Password Dinamis -->
            <form id="verifyFormInner" onsubmit="event.preventDefault(); submitCourierPin(event);" novalidate>
                
                <!-- 4. Kotak Password OTP (Direncanakan & Dirender lewat JS) -->
                <div class="verify-otp-wrapper">
                    <div class="verify-otp-container" id="verifyOtpContainer">
                        <!-- Kotak dinamis dirender via renderOtpBoxes() -->
                    </div>

                    <!-- Baris Info Bawah: Toggle Mata & {PASSWORD_LENGTH} karakter -->
                    <div class="verify-otp-meta-row">
                        <button type="button" class="verify-btn-toggle-eye" id="verifyBtnToggleEye" onclick="toggleVerifyPassword()" aria-label="Tampilkan karakter password">
                            <svg id="verifyEyeSvgShow" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                            <svg id="verifyEyeSvgHide" viewBox="0 0 24 24" style="display: none;">
                                <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
                            </svg>
                            <span id="verifyEyeLabel">Lihat</span>
                        </button>
                        <span class="verify-char-count-text" id="verifyCharCountText">{{ $courierPinLength ?? 4 }} karakter</span>
                    </div>
                </div>

                <!-- 5. Status / Loading Indicator Otomatis (Tanpa Tombol Submit) -->
                <div class="verify-auto-status" id="verifyAutoStatus">
                    <span class="verify-spinner-inline" id="verifyInlineSpinner"></span>
                    <span id="verifyStatusText">Memeriksa PIN...</span>
                </div>
            </form>

            <!-- 6. Footer: Lupa PIN & Lock Countdown -->
            <div class="text-center mt-3 flex flex-col items-center">
                <button type="button" class="text-xs font-semibold text-[#6C5CE7] hover:underline cursor-pointer bg-transparent border-none" onclick="alert('Silakan hubungi admin kafe untuk bantuan reset PIN akses kurir.')">
                    Lupa PIN akses?
                </button>
                <span class="verify-lock-text" id="verifyLockText">Coba lagi dalam 0:30</span>
            </div>

        </div>
    </div>
</body>
</html>
