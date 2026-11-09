<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-theme="a">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pratinjau Pilihan Gaya Judul &amp; Desain Panel Admin — Nucomu Cafe</title>
    
    <!-- Google Fonts: Fraunces (Serif Editorial), Manrope (Sans), Plus Jakarta Sans (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,600&family=Manrope:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['"Manrope"', 'sans-serif'],
                        serif: ['"Fraunces"', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* =========================================================================
           GLOBAL TYPOGRAPHY & DESIGN TOKENS
           ========================================================================= */
        :root {
            --font-heading: 'Manrope', system-ui, -apple-system, sans-serif;
            --font-heading-accent: 'Fraunces', Georgia, serif;
            --font-body: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        html {
            font-size: 14px !important;
        }

        html, body, button, input, select, textarea {
            font-family: var(--font-body);
            font-variant-numeric: lining-nums tabular-nums;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Proteksi Penuh Font Awesome */
        .fa, .fas, .far, .fal, .fad,
        .fa-solid, .fa-regular, .fa-light, .fa-thin, .fa-duotone,
        .fa::before, .fas::before, .far::before, .fal::before, .fad::before,
        .fa-solid::before, .fa-regular::before, .fa-light::before, .fa-thin::before, .fa-duotone::before {
            font-family: "Font Awesome 6 Free" !important;
        }
        .fab, .fa-brands, .fab::before, .fa-brands::before {
            font-family: "Font Awesome 6 Brands" !important;
        }

        /* Gaya Judul 1: Serif Editorial (Fraunces 600, 30px, -0.01em) */
        .preview-title-serif {
            font-family: var(--font-heading-accent), Georgia, serif;
            font-size: 26px;
            font-weight: 600;
            letter-spacing: -0.01em;
            line-height: 1.2;
            font-variant-numeric: lining-nums tabular-nums;
            color: var(--p-text-main);
        }
        @media (min-width: 640px) {
            .preview-title-serif {
                font-size: 30px;
            }
        }

        /* Gaya Judul 2: Sans Garis Aksen (Manrope 700, 26px, -0.02em) */
        .preview-title-sans-bar {
            font-family: var(--font-heading), sans-serif;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.25;
            font-variant-numeric: lining-nums tabular-nums;
            color: var(--p-text-main);
        }
        @media (min-width: 640px) {
            .preview-title-sans-bar {
                font-size: 26px;
            }
        }

        /* Gaya Judul 3: Sapaan Kecil (Manrope 700, 26px, -0.02em) */
        .preview-title-eyebrow {
            font-family: var(--font-heading), sans-serif;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.25;
            font-variant-numeric: lining-nums tabular-nums;
            color: var(--p-text-main);
        }
        @media (min-width: 640px) {
            .preview-title-eyebrow {
                font-size: 26px;
            }
        }

        /* Tautan Teks Kecil "? Petunjuk" (13px, abu #6B7270, tanpa latar/border, hover warna aksen) */
        .preview-help-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            font-weight: 500;
            color: #6B7270;
            background: transparent;
            border: none;
            padding: 6px 4px;
            cursor: pointer;
            text-decoration: none;
            transition: color 0.15s ease;
            user-select: none;
        }
        .preview-help-link:hover,
        .preview-help-link[aria-expanded="true"] {
            color: var(--p-btn-primary-bg);
            text-decoration: none;
        }
        .preview-help-link:focus-visible {
            outline: 2px solid var(--p-btn-primary-bg);
            outline-offset: 2px;
            border-radius: 4px;
        }
        @media (max-width: 639px) {
            .preview-help-link {
                min-height: 44px;
                padding-top: 10px;
                padding-bottom: 10px;
            }
        }

        /* Panel Petunjuk Terbuka Halus di Bawah Judul */
        .preview-help-container {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
            opacity: 0;
        }
        .preview-help-container.is-open {
            grid-template-rows: 1fr;
            opacity: 1;
        }
        .preview-help-inner {
            overflow: hidden;
        }

        /* =========================================================================
           CSS VARIABLES UNTUK 3 DESAIN TERPILIH
           ========================================================================= */

        /* ─────────────────────────────────────────────────────────────
           DESAIN A: "Terang Segar"
           Latar #F6F7F5, Sidebar putih #FFFFFF, Teks #1B1F1D,
           Aksen hijau hutan #2F6B4F, Kartu putih border #E6E8E4 sudut 12px
           ───────────────────────────────────────────────────────────── */
        [data-theme="a"] {
            --p-bg-page: #F6F7F5;
            --p-bg-sidebar: #FFFFFF;
            --p-border-sidebar: #E6E8E4;
            --p-text-sidebar: #47504A;
            --p-text-sidebar-active: #2F6B4F;
            --p-bg-sidebar-active: #E6F0EA;
            --p-icon-sidebar: #7A857F;
            --p-icon-sidebar-active: #2F6B4F;
            --p-group-sidebar: #7A857F;
            --p-badge-sidebar-bg: #E6F0EA;
            --p-badge-sidebar-text: #2F6B4F;
            --p-scrollbar-sidebar: #D4D8D2;

            --p-bg-header: #F6F7F5;
            --p-border-header: #E6E8E4;

            --p-bg-card: #FFFFFF;
            --p-border-card: #E6E8E4;
            --p-radius-card: 12px;
            --p-shadow-card: 0 1px 2px rgba(0, 0, 0, 0.03);

            --p-text-main: #1B1F1D;
            --p-text-sub: #59635E;
            --p-text-muted: #828C86;

            --p-btn-primary-bg: #2F6B4F;
            --p-btn-primary-text: #FFFFFF;
            --p-btn-primary-hover: #24543D;

            --p-chip-active-bg: #2F6B4F;
            --p-chip-active-text: #FFFFFF;
            --p-chip-inactive-bg: #FFFFFF;
            --p-chip-inactive-text: #59635E;
            --p-chip-inactive-border: #E6E8E4;

            --p-status-active-text: #2F6B4F;
            --p-status-active-bg: #E6F0EA;
            --p-status-active-border: rgba(47, 107, 79, 0.35);

            --p-status-expiring-text: #976E1A;
            --p-status-expiring-bg: #FEF7EA;
            --p-status-expiring-border: rgba(217, 164, 65, 0.45);

            --p-status-danger-text: #C0392B;
            --p-status-danger-bg: #FDEEEB;
            --p-status-danger-border: rgba(192, 57, 43, 0.35);

            --p-progress-track: #EAECE8;
            --p-progress-bar: #2F6B4F;

            --p-btn-secondary-bg: #FFFFFF;
            --p-btn-secondary-border: #E6E8E4;
            --p-btn-secondary-text: #1B1F1D;
            --p-btn-secondary-hover: #F0F2EE;

            --p-logo-box-bg: #E6F0EA;
            --p-logo-box-border: #D1DFD6;
            --p-logo-box-img-filter: none;
            --p-logo-text: #1B1F1D;
        }

        /* ─────────────────────────────────────────────────────────────
           DESAIN B: "Krem Hangat"
           Latar #FBF8F3, Sidebar #F4EFE7, Teks #2B2420,
           Tombol espresso #3B2F2A, Status sage #5E7D5B, Kartu sudut 14px
           ───────────────────────────────────────────────────────────── */
        [data-theme="b"] {
            --p-bg-page: #FBF8F3;
            --p-bg-sidebar: #F4EFE7;
            --p-border-sidebar: #EAE3D8;
            --p-text-sidebar: #6E6259;
            --p-text-sidebar-active: #2B2420;
            --p-bg-sidebar-active: #E9E0D2;
            --p-icon-sidebar: #9A8E85;
            --p-icon-sidebar-active: #2B2420;
            --p-group-sidebar: #9A8E85;
            --p-badge-sidebar-bg: #3B2F2A;
            --p-badge-sidebar-text: #FBF8F3;
            --p-scrollbar-sidebar: #DDD4C7;

            --p-bg-header: #FBF8F3;
            --p-border-header: #EAE3D8;

            --p-bg-card: #FFFFFF;
            --p-border-card: #EAE3D8;
            --p-radius-card: 14px;
            --p-shadow-card: 0 2px 8px rgba(43, 36, 32, 0.04);

            --p-text-main: #2B2420;
            --p-text-sub: #7A6E65;
            --p-text-muted: #A3968C;

            --p-btn-primary-bg: #3B2F2A;
            --p-btn-primary-text: #FBF8F3;
            --p-btn-primary-hover: #271F1B;

            --p-chip-active-bg: #3B2F2A;
            --p-chip-active-text: #FBF8F3;
            --p-chip-inactive-bg: #FFFFFF;
            --p-chip-inactive-text: #7A6E65;
            --p-chip-inactive-border: #EAE3D8;

            --p-status-active-text: #3D563A;
            --p-status-active-bg: #EEF3EE;
            --p-status-active-border: rgba(94, 125, 91, 0.35);

            --p-status-expiring-text: #8F5F00;
            --p-status-expiring-bg: #FDF6E9;
            --p-status-expiring-border: rgba(217, 164, 65, 0.45);

            --p-status-danger-text: #B33A3A;
            --p-status-danger-bg: #FDF1F1;
            --p-status-danger-border: rgba(179, 58, 58, 0.35);

            --p-progress-track: #EFE9DF;
            --p-progress-bar: #5E7D5B;

            --p-btn-secondary-bg: #FFFFFF;
            --p-btn-secondary-border: #EAE3D8;
            --p-btn-secondary-text: #2B2420;
            --p-btn-secondary-hover: #FAF7F2;

            --p-logo-box-bg: #3B2F2A;
            --p-logo-box-border: #3B2F2A;
            --p-logo-box-img-filter: brightness(0) invert(1);
            --p-logo-text: #2B2420;
        }

        /* ─────────────────────────────────────────────────────────────
           DESAIN C: "Gelap Premium"
           Latar #141416, Sidebar #0F0F11, Kartu #1C1C1F,
           Teks #F1EDE6, Aksen pasir hangat #D6B98C, Status versi cerah
           ───────────────────────────────────────────────────────────── */
        [data-theme="c"] {
            --p-bg-page: #141416;
            --p-bg-sidebar: #0F0F11;
            --p-border-sidebar: #2A2A2E;
            --p-text-sidebar: #D1CBC3;
            --p-text-sidebar-active: #D6B98C;
            --p-bg-sidebar-active: rgba(214, 185, 140, 0.12);
            --p-icon-sidebar: #8C867E;
            --p-icon-sidebar-active: #D6B98C;
            --p-group-sidebar: #6B6660;
            --p-badge-sidebar-bg: #D6B98C;
            --p-badge-sidebar-text: #141416;
            --p-scrollbar-sidebar: #2A2A2E;

            --p-bg-header: #141416;
            --p-border-header: #2A2A2E;

            --p-bg-card: #1C1C1F;
            --p-border-card: #2A2A2E;
            --p-radius-card: 12px;
            --p-shadow-card: none;

            --p-text-main: #F1EDE6;
            --p-text-sub: #A8A29A;
            --p-text-muted: #6B6660;

            --p-btn-primary-bg: #D6B98C;
            --p-btn-primary-text: #141416;
            --p-btn-primary-hover: #E2CBA4;

            --p-chip-active-bg: #D6B98C;
            --p-chip-active-text: #141416;
            --p-chip-inactive-bg: #1C1C1F;
            --p-chip-inactive-text: #A8A29A;
            --p-chip-inactive-border: #2A2A2E;

            --p-status-active-text: #4ADE80;
            --p-status-active-bg: rgba(74, 222, 128, 0.16);
            --p-status-active-border: rgba(74, 222, 128, 0.35);

            --p-status-expiring-text: #FBBF24;
            --p-status-expiring-bg: rgba(251, 191, 36, 0.16);
            --p-status-expiring-border: rgba(251, 191, 36, 0.35);

            --p-status-danger-text: #F87171;
            --p-status-danger-bg: rgba(248, 113, 113, 0.16);
            --p-status-danger-border: rgba(248, 113, 113, 0.35);

            --p-progress-track: #2A2A2E;
            --p-progress-bar: #D6B98C;

            --p-btn-secondary-bg: #1C1C1F;
            --p-btn-secondary-border: #2A2A2E;
            --p-btn-secondary-text: #F1EDE6;
            --p-btn-secondary-hover: #26262B;

            --p-logo-box-bg: rgba(214, 185, 140, 0.15);
            --p-logo-box-border: rgba(214, 185, 140, 0.3);
            --p-logo-box-img-filter: brightness(0) invert(1);
            --p-logo-text: #F1EDE6;
        }

        /* Helper Transitions */
        .theme-transition {
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
        }

        /* Sidebar Scrollbar Styling (Tipis & Menyatu dengan Sidebar) */
        .preview-sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: var(--p-scrollbar-sidebar) transparent;
        }
        .preview-sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .preview-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .preview-sidebar-scroll::-webkit-scrollbar-thumb {
            background: var(--p-scrollbar-sidebar);
            border-radius: 4px;
        }

        /* Micro interactions */
        .btn-press:active {
            transform: scale(0.97);
        }
    </style>
</head>
<body class="theme-transition min-h-screen text-[14px] antialiased select-none" style="background-color: var(--p-bg-page); color: var(--p-text-main);">

    <!-- =========================================================================
         STICKY TOP TOOLBAR: PEMILIH DESAIN (A / B / C)
         ========================================================================= -->
    <header class="sticky top-0 z-[100] w-full bg-[#111315] text-[#FAF7F2] border-b border-[#2A2A2E] shadow-md px-3.5 sm:px-6 py-2.5">
        <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-300 font-bold text-[11px] border border-amber-500/30">
                    <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                    <span>HALAMAN PRATINJAU VISUAL</span>
                </span>
                <span class="text-[12px] text-gray-300 hidden md:inline">
                    Pilih desain di sebelah kanan untuk melihat perbedaan suasana secara instan:
                </span>
            </div>

            <!-- Switcher Buttons A / B / C -->
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button type="button" onclick="setPreviewTheme('a')" id="tabA"
                        class="btn-press flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl text-[12px] font-bold border transition-all">
                    <span class="w-2 h-2 rounded-full bg-[#2F6B4F]"></span>
                    <span>Desain A: Terang Segar</span>
                </button>

                <button type="button" onclick="setPreviewTheme('b')" id="tabB"
                        class="btn-press flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl text-[12px] font-bold border transition-all">
                    <span class="w-2 h-2 rounded-full bg-[#3B2F2A]"></span>
                    <span>Desain B: Krem Hangat</span>
                </button>

                <button type="button" onclick="setPreviewTheme('c')" id="tabC"
                        class="btn-press flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl text-[12px] font-bold border transition-all">
                    <span class="w-2 h-2 rounded-full bg-[#D6B98C]"></span>
                    <span>Desain C: Gelap Premium</span>
                </button>
            </div>
        </div>

        <!-- Deskripsi Ringkas Desain Aktif & Pilihan Cepat Gaya Judul -->
        <div class="max-w-[1440px] mx-auto pt-2 border-t border-[#2A2A2E]/60 mt-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-[11.5px] text-gray-400">
            <div id="themeDescriptionText" class="font-medium">
                <!-- Diisi otomatis oleh JavaScript -->
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-amber-400 font-bold text-[11px] uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-heading text-[10px]"></i>
                    <span>Gaya Judul:</span>
                </span>
                <button type="button" onclick="setTitleStyle('1')" id="topTitleBtn1"
                        class="px-2.5 py-0.5 rounded-lg text-[11px] font-bold transition-all border border-amber-400 bg-amber-400 text-black shadow-xs">
                    1. Serif Editorial
                </button>
                <button type="button" onclick="setTitleStyle('2')" id="topTitleBtn2"
                        class="px-2.5 py-0.5 rounded-lg text-[11px] font-medium transition-all border border-white/20 text-gray-300 hover:text-white hover:bg-white/10">
                    2. Sans Garis Aksen
                </button>
                <button type="button" onclick="setTitleStyle('3')" id="topTitleBtn3"
                        class="px-2.5 py-0.5 rounded-lg text-[11px] font-medium transition-all border border-white/20 text-gray-300 hover:text-white hover:bg-white/10">
                    3. Sapaan Kecil
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer Backdrop -->
    <div id="previewDrawerBackdrop" onclick="togglePreviewDrawer()" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

    <!-- =========================================================================
         LAYOUT UTAMA: SIDEBAR + CONTENT
         ========================================================================= -->
    <div class="flex min-h-[calc(100vh-80px)]">

        <!-- =====================================================================
             1. SIDEBAR NAVIGASI
             ===================================================================== -->
        <aside id="previewSidebar" 
               class="theme-transition fixed md:sticky top-[85px] md:top-0 left-0 bottom-0 w-[240px] md:w-[200px] shrink-0 flex flex-col h-screen max-h-screen overflow-hidden z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out border-r"
               style="background-color: var(--p-bg-sidebar); border-right-color: var(--p-border-sidebar);">
            
            <!-- Blok Logo: Setinggi Header (56px) dengan Garis Pemisah Sejajar Header -->
            <div class="theme-transition h-[56px] min-h-[56px] max-h-[56px] px-[16px] flex items-center justify-between border-b shrink-0 box-border relative z-10"
                 style="background-color: var(--p-bg-sidebar); border-bottom-color: var(--p-border-sidebar);">
                <a href="#dashboard" class="flex items-center gap-2.5 group min-w-0" title="Nucomu Cafe Admin">
                    <div class="w-[36px] h-[36px] rounded-lg border flex items-center justify-center shadow-xs transition-all shrink-0"
                         style="background-color: var(--p-logo-box-bg); border-color: var(--p-logo-box-border);">
                        <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu" 
                             class="h-[18px] w-auto object-contain"
                             style="filter: var(--p-logo-box-img-filter);">
                    </div>
                    <span class="text-[15.5px] font-bold tracking-tight leading-none truncate" style="color: var(--p-logo-text);">
                        NUCOMU CAFE
                    </span>
                </a>
                <button type="button" onclick="togglePreviewDrawer()" class="md:hidden p-1.5 rounded-lg opacity-70 hover:opacity-100 transition-opacity" aria-label="Tutup Menu">
                    <i class="fa-solid fa-xmark text-sm" style="color: var(--p-text-main);"></i>
                </button>
            </div>

            <!-- Kontainer Menu: Scroll Mandiri di Bawah Logo Tetap, Dashboard Utuh Tidak Tertimpa -->
            <div class="flex-1 overflow-y-auto preview-sidebar-scroll p-2.5 pb-8 space-y-[2px] min-h-0 relative z-0">
                <nav class="space-y-[2px]">
                    {{-- Dashboard --}}
                    <a href="#dashboard" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <rect width="7" height="9" x="3" y="3" rx="1"/>
                                <rect width="7" height="5" x="14" y="3" rx="1"/>
                                <rect width="7" height="9" x="14" y="12" rx="1"/>
                                <rect width="7" height="5" x="3" y="16" rx="1"/>
                            </svg>
                            <span class="truncate">Dashboard</span>
                        </div>
                    </a>

                    {{-- Label Grup Menu Utama --}}
                    <div class="px-[10px] pt-3.5 pb-1 text-[11px] font-semibold select-none" style="color: var(--p-group-sidebar);">
                        Menu Utama
                    </div>

                    {{-- 1. Banner (Aktif di Halaman Ini) --}}
                    <a href="#banner" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-semibold transition-all shadow-xs"
                       style="background-color: var(--p-bg-sidebar-active); color: var(--p-text-sidebar-active);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar-active);">
                                <path d="m3 11 18-5v12L3 14v-3z"/>
                                <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                            </svg>
                            <span class="truncate">Banner</span>
                        </div>
                        <span class="w-[18px] h-[18px] min-w-[18px] rounded-full text-[10.5px] font-bold flex items-center justify-center leading-none shrink-0" 
                              style="background-color: var(--p-badge-sidebar-bg); color: var(--p-badge-sidebar-text);">
                            {{ ($expiringCount ?? 0) > 0 ? $expiringCount : '1' }}
                        </span>
                    </a>

                    {{-- 2. Tentang Cafe --}}
                    <a href="#about" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                            <span class="truncate">Tentang Cafe</span>
                        </div>
                    </a>

                    {{-- 3. Fasilitas --}}
                    <a href="#facilities" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/>
                                <path d="M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v2H7v-2a2 2 0 0 0-4 0Z"/>
                                <path d="M5 18v2"/>
                                <path d="M19 18v2"/>
                            </svg>
                            <span class="truncate">Fasilitas</span>
                        </div>
                    </a>

                    {{-- 4. Menu --}}
                    <a href="#menu" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                <path d="M7 2v20"/>
                                <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                            </svg>
                            <span class="truncate">Menu</span>
                        </div>
                    </a>

                    {{-- 5. Gallery --}}
                    <a href="#gallery" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                <circle cx="9" cy="9" r="2"/>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                            </svg>
                            <span class="truncate">Gallery</span>
                        </div>
                    </a>

                    {{-- 6. Ulasan --}}
                    <a href="#testimonials" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <span class="truncate">Ulasan</span>
                        </div>
                    </a>

                    {{-- 7. Reservasi --}}
                    <a href="#reservations" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                <line x1="16" x2="16" y1="2" y2="6"/>
                                <line x1="8" x2="8" y1="2" y2="6"/>
                                <line x1="3" x2="21" y1="10" y2="10"/>
                                <path d="m9 16 2 2 4-4"/>
                            </svg>
                            <span class="truncate">Reservasi</span>
                        </div>
                        <span class="w-[18px] h-[18px] min-w-[18px] rounded-full text-[10.5px] font-bold flex items-center justify-center leading-none shrink-0"
                              style="background-color: var(--p-badge-sidebar-bg); color: var(--p-badge-sidebar-text);">
                            3
                        </span>
                    </a>

                    {{-- 8. Transaksi --}}
                    <a href="#orders" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"/>
                                <path d="M16 8h-8"/>
                                <path d="M16 12h-8"/>
                                <path d="M11 16H8"/>
                            </svg>
                            <span class="truncate">Transaksi</span>
                        </div>
                    </a>

                    {{-- 9. Kontak --}}
                    <a href="#contact" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <path d="M16 2v2"/>
                                <path d="M7 22v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/>
                                <path d="M8 2v2"/>
                                <circle cx="12" cy="11" r="4"/>
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                            </svg>
                            <span class="truncate">Kontak</span>
                        </div>
                    </a>
                </nav>

                {{-- Label Grup: Akun & Sistem --}}
                <div class="px-[10px] pt-3.5 pb-1 text-[11px] font-semibold select-none border-t mt-2"
                     style="color: var(--p-group-sidebar); border-top-color: var(--p-border-sidebar);">
                    Akun &amp; Sistem
                </div>

                <nav class="space-y-[2px]">
                    {{-- Profile --}}
                    <a href="#profile" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span class="truncate">Profil Admin</span>
                        </div>
                    </a>

                    {{-- Pusat Notifikasi --}}
                    <a href="#notifications" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                            </svg>
                            <span class="truncate">Pusat Notifikasi</span>
                        </div>
                    </a>

                    {{-- Bantuan --}}
                    <a href="#help" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <path d="M12 17h.01"/>
                            </svg>
                            <span class="truncate">Bantuan &amp; Panduan</span>
                        </div>
                    </a>

                    {{-- Pengaturan Kafe --}}
                    <a href="#settings" 
                       class="group relative flex items-center justify-between px-[10px] h-[36px] min-h-[44px] md:min-h-[36px] rounded-[8px] text-[13.5px] font-medium transition-all"
                       style="color: var(--p-text-sidebar);">
                        <div class="flex items-center gap-[10px] min-w-0">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors" style="color: var(--p-icon-sidebar);">
                                <line x1="4" x2="4" y1="21" y2="14"/>
                                <line x1="4" x2="4" y1="10" y2="3"/>
                                <line x1="12" x2="12" y1="21" y2="12"/>
                                <line x1="12" x2="12" y1="8" y2="3"/>
                                <line x1="20" x2="20" y1="21" y2="16"/>
                                <line x1="20" x2="20" y1="12" y2="3"/>
                                <line x1="1" x2="7" y1="14" y2="14"/>
                                <line x1="9" x2="15" y1="8" y2="8"/>
                                <line x1="17" x2="23" y1="16" y2="16"/>
                            </svg>
                            <span class="truncate">Pengaturan Kafe</span>
                        </div>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- =====================================================================
             2. AREA KONTEN UTAMA & HEADER
             ===================================================================== -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen theme-transition" style="background-color: var(--p-bg-page);">
            
            <!-- Sticky Header Bar: Menyatu dengan Latar Halaman, Tinggi 56px Sejajar Garis Sidebar -->
            <header class="theme-transition sticky top-[85px] md:top-0 z-40 h-[56px] min-h-[56px] max-h-[56px] border-b px-3.5 sm:px-6 flex items-center justify-between gap-3 box-border"
                    style="background-color: var(--p-bg-header); border-bottom-color: var(--p-border-header);">
                
                <!-- Left: Hamburger Mobile -->
                <div class="flex items-center min-w-0">
                    <button type="button" onclick="togglePreviewDrawer()" class="md:hidden p-2 rounded-xl border opacity-80 hover:opacity-100 transition-opacity btn-press"
                            style="border-color: var(--p-border-header); background-color: var(--p-bg-card); color: var(--p-text-main);"
                            aria-label="Buka Menu Admin">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                    <span class="hidden sm:inline font-bold text-[13px] ml-1 tracking-tight" style="color: var(--p-text-muted);">
                        Panel Kelola &bull; Nucomu Cafe
                    </span>
                </div>

                <!-- Right: Action Shortcuts, Notification Bell, User Profile (Tinggi Seragam 34px) -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Status Sistem Aktif (Tinggi 34px) -->
                    <div class="inline-flex items-center gap-1.5 px-3 h-[34px] min-h-[40px] sm:min-h-[34px] sm:h-[34px] rounded-full border text-[12px] font-semibold transition-colors shadow-2xs select-none"
                         style="background-color: var(--p-bg-card); border-color: var(--p-border-card); color: var(--p-text-main);">
                        <span class="w-2 h-2 rounded-full animate-pulse shrink-0" style="background-color: var(--p-status-active-text);"></span>
                        <span class="hidden sm:inline">Sistem aktif</span>
                    </div>

                    <!-- Tombol Lihat Website (Tinggi 34px) -->
                    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 px-3 h-[34px] min-h-[40px] sm:min-h-[34px] sm:h-[34px] rounded-xl border text-[12.5px] font-semibold tracking-tight transition-all shadow-2xs btn-press select-none"
                       style="background-color: var(--p-btn-secondary-bg); border-color: var(--p-btn-secondary-border); color: var(--p-btn-secondary-text);"
                       title="Buka website kafe di tab baru">
                        <span class="hidden sm:inline">Lihat Website</span>
                        <span class="sm:hidden text-[12px]">Web</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[9.5px]"></i>
                    </a>

                    <!-- Ikon Lonceng Notifikasi (Tinggi 34px) -->
                    <button type="button" 
                            class="relative w-[34px] h-[34px] min-w-[40px] min-h-[40px] sm:min-w-[34px] sm:min-h-[34px] sm:w-[34px] sm:h-[34px] rounded-xl border transition-colors flex items-center justify-center btn-press select-none"
                            style="background-color: var(--p-btn-secondary-bg); border-color: var(--p-btn-secondary-border); color: var(--p-btn-secondary-text);"
                            title="Notifikasi Sistem">
                        <i class="fa-regular fa-bell text-[14px]"></i>
                        <span class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 rounded-full text-white text-[10px] font-bold flex items-center justify-center leading-none shadow-xs"
                              style="background-color: var(--p-status-danger-text);">
                            {{ ($expiringCount ?? 0) > 0 ? $expiringCount : '1' }}
                        </span>
                    </button>

                    <!-- Foto & Profil Admin (Tinggi 34px) -->
                    <div class="flex items-center gap-2 px-2 h-[34px] min-h-[40px] sm:min-h-[34px] sm:h-[34px] rounded-xl border select-none"
                         style="background-color: var(--p-btn-secondary-bg); border-color: var(--p-btn-secondary-border); color: var(--p-btn-secondary-text);">
                        <img src="{{ auth()->user()?->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin+Nucomu&background=2B2420&color=FAF7F2' }}"
                             alt="Admin" class="w-[22px] h-[22px] rounded-full object-cover">
                        <span class="text-[12.5px] font-semibold hidden md:inline truncate max-w-[100px]">Admin</span>
                    </div>
                </div>
            </header>

            <!-- =================================================================
                 3. KONTEN HALAMAN: BANNER HALAMAN DEPAN
                 ================================================================= -->
            <main class="flex-1 p-4 sm:p-6 lg:p-7 max-w-7xl w-full mx-auto space-y-5">
                
                <!-- =============================================================
                     KOTAK PERBANDINGAN BERDAMPINGAN: 3 PILIHAN GAYA JUDUL
                     (Dapat Diklik untuk Mengubah Halaman Banner di Bawah Secara Instan)
                     ============================================================= -->
                <div class="rounded-2xl border p-4 sm:p-5 shadow-xs space-y-4"
                     style="background-color: var(--p-bg-card); border-color: var(--p-border-card);">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b"
                         style="border-bottom-color: var(--p-border-card);">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider mb-1"
                                 style="background-color: var(--p-bg-sidebar-active); color: var(--p-text-sidebar-active);">
                                <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                                <span>PILIHAN GAYA JUDUL PANEL ADMIN</span>
                            </div>
                            <h2 class="text-[17px] sm:text-[18px] font-bold" style="color: var(--p-text-main);">
                                Tiga Eksplorasi Karakter Judul Halaman
                            </h2>
                            <p class="text-[12.5px] mt-0.5" style="color: var(--p-text-sub);">
                                Bandingkan 3 pilihan karakter di bawah ini. Klik salah satu untuk melihat perubahannya langsung pada halaman Banner di bawah.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[12px] font-semibold px-3 py-1 rounded-xl border flex items-center gap-1.5"
                                  style="border-color: var(--p-border-card); background-color: var(--p-bg-page); color: var(--p-text-main);">
                                <span class="w-2 h-2 rounded-full bg-[#2F6B4F]"></span>
                                <span>Gaya Aktif: <strong id="activeTitleStyleLabel" class="text-[#2F6B4F]">1. Serif Editorial</strong></span>
                            </span>
                        </div>
                    </div>

                    <!-- 3 Kolom Pilihan Berdampingan -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5 items-stretch">
                        
                        <!-- ==========================================
                             KARTU 1: "SERIF EDITORIAL"
                             ========================================== -->
                        <div id="titleCard1" onclick="setTitleStyle('1')"
                             class="rounded-xl border p-4 flex flex-col justify-between transition-all cursor-pointer ring-2 ring-[#2F6B4F] border-transparent shadow-xs"
                             style="background-color: var(--p-bg-page); border-color: var(--p-border-card);">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-[#2F6B4F]/10 text-[#2F6B4F] uppercase tracking-wider">
                                        Pilihan 1
                                    </span>
                                    <span class="text-[11.5px] font-semibold" style="color: var(--p-text-muted);">Serif Editorial</span>
                                </div>

                                <!-- Rendered Header Preview 1 -->
                                <div class="p-3 rounded-lg border bg-white dark:bg-[#1A1A1E]" style="border-color: var(--p-border-card);">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="text-[20px] sm:text-[22px] font-semibold tracking-[-0.01em] leading-tight"
                                                 style="font-family: var(--font-heading-accent), Georgia, serif; font-variant-numeric: lining-nums; color: var(--p-text-main);">
                                                Banner <span class="italic" style="color: var(--p-btn-primary-bg);">Halaman Depan</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[11px] text-[#6B7270] flex items-center gap-1">
                                                <i class="fa-regular fa-circle-question text-[10px]"></i> Petunjuk
                                            </span>
                                            <span class="px-2 py-1 rounded-md text-[10.5px] font-bold text-white shadow-xs" style="background-color: var(--p-btn-primary-bg);">
                                                + Tambah
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <ul class="text-[12px] space-y-1.5 leading-relaxed pt-1" style="color: var(--p-text-sub);">
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-[#2F6B4F] mt-1 shrink-0"></i>
                                        <span>Font <strong>Fraunces 600</strong> (30px, -0.01em).</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-[#2F6B4F] mt-1 shrink-0"></i>
                                        <span>Kata terakhir <em>italic</em> berwarna hijau hutan <strong>#2F6B4F</strong>.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-[#2F6B4F] mt-1 shrink-0"></i>
                                        <span>Angka lining-nums normal, khas kafe artisan &amp; elegan.</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-4 mt-2 border-t" style="border-top-color: var(--p-border-card);">
                                <button type="button" id="cardSelectBtn1" onclick="event.stopPropagation(); setTitleStyle('1');"
                                        class="w-full py-2 px-3 rounded-xl text-[12px] font-bold text-white bg-[#2F6B4F] shadow-xs flex items-center justify-center gap-1.5 transition-all">
                                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                                    <span>Sedang Aktif di Bawah</span>
                                </button>
                            </div>
                        </div>

                        <!-- ==========================================
                             KARTU 2: "SANS GARIS AKSEN"
                             ========================================== -->
                        <div id="titleCard2" onclick="setTitleStyle('2')"
                             class="rounded-xl border p-4 flex flex-col justify-between transition-all cursor-pointer shadow-xs"
                             style="background-color: var(--p-bg-page); border-color: var(--p-border-card);">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-blue-500/10 text-blue-700 dark:text-blue-400 uppercase tracking-wider">
                                        Pilihan 2
                                    </span>
                                    <span class="text-[11.5px] font-semibold" style="color: var(--p-text-muted);">Sans Garis Aksen</span>
                                </div>

                                <!-- Rendered Header Preview 2 -->
                                <div class="p-3 rounded-lg border bg-white dark:bg-[#1A1A1E]" style="border-color: var(--p-border-card);">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="text-[18px] sm:text-[19px] font-bold tracking-[-0.02em] leading-tight"
                                                 style="font-family: var(--font-heading), sans-serif; font-variant-numeric: lining-nums; color: var(--p-text-main);">
                                                Banner Halaman Depan
                                            </div>
                                            <div class="w-[32px] h-[3px] rounded-full my-1" style="background-color: var(--p-btn-primary-bg);"></div>
                                            <div class="text-[11px] text-[#6B7270] truncate max-w-[170px]">
                                                Atur promo yang tampil di depan
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[11px] text-[#6B7270] flex items-center gap-1">
                                                <i class="fa-regular fa-circle-question text-[10px]"></i> Petunjuk
                                            </span>
                                            <span class="px-2 py-1 rounded-md text-[10.5px] font-bold text-white shadow-xs" style="background-color: var(--p-btn-primary-bg);">
                                                + Tambah
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <ul class="text-[12px] space-y-1.5 leading-relaxed pt-1" style="color: var(--p-text-sub);">
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-blue-600 mt-1 shrink-0"></i>
                                        <span>Font <strong>Manrope 700</strong> (26px, -0.02em).</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-blue-600 mt-1 shrink-0"></i>
                                        <span>Garis hijau <strong>40×3px rounded</strong> di bawah judul.</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-blue-600 mt-1 shrink-0"></i>
                                        <span>Satu kalimat ringkas 13px abu memberi panduan instan.</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-4 mt-2 border-t" style="border-top-color: var(--p-border-card);">
                                <button type="button" id="cardSelectBtn2" onclick="event.stopPropagation(); setTitleStyle('2');"
                                        class="w-full py-2 px-3 rounded-xl text-[12px] font-semibold border transition-all flex items-center justify-center gap-1.5 hover:bg-black/5 dark:hover:bg-white/5"
                                        style="border-color: var(--p-border-card); color: var(--p-text-main);">
                                    <span>Pilih Gaya 2 (Sans Garis Aksen)</span>
                                </button>
                            </div>
                        </div>

                        <!-- ==========================================
                             KARTU 3: "SAPAAN KECIL"
                             ========================================== -->
                        <div id="titleCard3" onclick="setTitleStyle('3')"
                             class="rounded-xl border p-4 flex flex-col justify-between transition-all cursor-pointer shadow-xs"
                             style="background-color: var(--p-bg-page); border-color: var(--p-border-card);">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-purple-500/10 text-purple-700 dark:text-purple-400 uppercase tracking-wider">
                                        Pilihan 3
                                    </span>
                                    <span class="text-[11.5px] font-semibold" style="color: var(--p-text-muted);">Sapaan Kecil</span>
                                </div>

                                <!-- Rendered Header Preview 3 -->
                                <div class="p-3 rounded-lg border bg-white dark:bg-[#1A1A1E]" style="border-color: var(--p-border-card);">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="text-[10px] font-semibold uppercase tracking-[0.06em] mb-0.5" style="color: var(--p-btn-primary-bg);">
                                                TAMPILAN WEBSITE
                                            </div>
                                            <div class="text-[18px] sm:text-[19px] font-bold tracking-[-0.02em] leading-tight"
                                                 style="font-family: var(--font-heading), sans-serif; font-variant-numeric: lining-nums; color: var(--p-text-main);">
                                                Banner Halaman Depan
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[11px] text-[#6B7270] flex items-center gap-1">
                                                <i class="fa-regular fa-circle-question text-[10px]"></i> Petunjuk
                                            </span>
                                            <span class="px-2 py-1 rounded-md text-[10.5px] font-bold text-white shadow-xs" style="background-color: var(--p-btn-primary-bg);">
                                                + Tambah
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <ul class="text-[12px] space-y-1.5 leading-relaxed pt-1" style="color: var(--p-text-sub);">
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-purple-600 mt-1 shrink-0"></i>
                                        <span>Teks sapaan kecil <strong>12px kapital hijau</strong> (0.06em).</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-purple-600 mt-1 shrink-0"></i>
                                        <span>Judul <strong>Manrope 700</strong> (26px, -0.02em).</span>
                                    </li>
                                    <li class="flex items-start gap-1.5">
                                        <i class="fa-solid fa-check text-[10px] text-purple-600 mt-1 shrink-0"></i>
                                        <span>Hirarki terarah, tenang, dan ala dashboard SaaS modern.</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-4 mt-2 border-t" style="border-top-color: var(--p-border-card);">
                                <button type="button" id="cardSelectBtn3" onclick="event.stopPropagation(); setTitleStyle('3');"
                                        class="w-full py-2 px-3 rounded-xl text-[12px] font-semibold border transition-all flex items-center justify-center gap-1.5 hover:bg-black/5 dark:hover:bg-white/5"
                                        style="border-color: var(--p-border-card); color: var(--p-text-main);">
                                    <span>Pilih Gaya 3 (Sapaan Kecil)</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- =============================================================
                     CONTOH PENERAPAN LENGKAP PADA HALAMAN BANNER
                     (Menyesuaikan Otomatis dengan Gaya yang Anda Pilih di Atas)
                     ============================================================= -->
                <div class="space-y-4 pt-1">
                    <div class="flex items-center gap-3">
                        <span class="text-[11.5px] font-bold uppercase tracking-wider text-gray-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-eye text-[11px]"></i>
                            <span>Simulasi Penuh Halaman Banner di Bawah:</span>
                        </span>
                        <div class="h-px flex-1" style="background-color: var(--p-border-card);"></div>
                    </div>

                    <!-- AREA JUDUL DINAMIS (Jarak 20-24px ke Konten di Bawahnya) -->
                    <div class="space-y-3 mb-5 sm:mb-6">
                        
                        <!-- VARIANT 1: SERIF EDITORIAL -->
                        <div id="liveTitleView1" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                            <div>
                                <h1 class="preview-title-serif">
                                    Banner <span class="italic font-normal sm:font-semibold" style="color: var(--p-btn-primary-bg);">Halaman Depan</span>
                                </h1>
                            </div>
                            <div class="flex flex-wrap items-center gap-3.5 shrink-0 w-full sm:w-auto">
                                <button type="button" onclick="togglePreviewHelp()" class="preview-help-link preview-help-trigger btn-press" aria-expanded="false">
                                    <i class="fa-regular fa-circle-question text-[12.5px] shrink-0"></i>
                                    <span>Petunjuk</span>
                                </button>
                                <a href="{{ route('admin.banners.create') }}"
                                   class="btn-press min-h-[44px] sm:min-h-[34px] sm:h-[34px] px-3.5 rounded-xl font-semibold text-[13px] transition-all flex items-center gap-2 shadow-xs"
                                   style="background-color: var(--p-btn-primary-bg); color: var(--p-btn-primary-text);">
                                    <i class="fa-solid fa-plus text-[12px]"></i>
                                    <span>Tambah Banner Baru</span>
                                </a>
                            </div>
                        </div>

                        <!-- VARIANT 2: SANS GARIS AKSEN -->
                        <div id="liveTitleView2" class="hidden flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                            <div>
                                <h1 class="preview-title-sans-bar">
                                    Banner Halaman Depan
                                </h1>
                                <div class="w-[40px] h-[3px] rounded-full my-1.5" style="background-color: var(--p-btn-primary-bg);"></div>
                                <p class="text-[13px] leading-normal" style="color: var(--p-text-sub);">
                                    Atur promo yang tampil di halaman depan website kafe
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3.5 shrink-0 w-full sm:w-auto">
                                <button type="button" onclick="togglePreviewHelp()" class="preview-help-link preview-help-trigger btn-press" aria-expanded="false">
                                    <i class="fa-regular fa-circle-question text-[12.5px] shrink-0"></i>
                                    <span>Petunjuk</span>
                                </button>
                                <a href="{{ route('admin.banners.create') }}"
                                   class="btn-press min-h-[44px] sm:min-h-[34px] sm:h-[34px] px-3.5 rounded-xl font-semibold text-[13px] transition-all flex items-center gap-2 shadow-xs"
                                   style="background-color: var(--p-btn-primary-bg); color: var(--p-btn-primary-text);">
                                    <i class="fa-solid fa-plus text-[12px]"></i>
                                    <span>Tambah Banner Baru</span>
                                </a>
                            </div>
                        </div>

                        <!-- VARIANT 3: SAPAAN KECIL -->
                        <div id="liveTitleView3" class="hidden flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                            <div>
                                <div class="text-[11.5px] sm:text-[12px] font-semibold uppercase tracking-[0.06em] mb-1" style="color: var(--p-btn-primary-bg);">
                                    TAMPILAN WEBSITE
                                </div>
                                <h1 class="preview-title-eyebrow">
                                    Banner Halaman Depan
                                </h1>
                            </div>
                            <div class="flex flex-wrap items-center gap-3.5 shrink-0 w-full sm:w-auto">
                                <button type="button" onclick="togglePreviewHelp()" class="preview-help-link preview-help-trigger btn-press" aria-expanded="false">
                                    <i class="fa-regular fa-circle-question text-[12.5px] shrink-0"></i>
                                    <span>Petunjuk</span>
                                </button>
                                <a href="{{ route('admin.banners.create') }}"
                                   class="btn-press min-h-[44px] sm:min-h-[34px] sm:h-[34px] px-3.5 rounded-xl font-semibold text-[13px] transition-all flex items-center gap-2 shadow-xs"
                                   style="background-color: var(--p-btn-primary-bg); color: var(--p-btn-primary-text);">
                                    <i class="fa-solid fa-plus text-[12px]"></i>
                                    <span>Tambah Banner Baru</span>
                                </a>
                            </div>
                        </div>

                        <!-- PANEL PETUNJUK INTERAKTIF (3 Bagian Ramah) -->
                        <div id="previewHelpContent" class="preview-help-container w-full">
                            <div class="preview-help-inner">
                                <div class="rounded-xl border p-4 text-[13px] shadow-sm my-2"
                                     style="background-color: var(--p-bg-card); border-color: var(--p-border-card); color: var(--p-text-main);">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        <div class="space-y-1.5">
                                            <h4 class="font-bold flex items-center gap-1.5 text-[13.5px]" style="color: var(--p-text-main);">
                                                <i class="fa-solid fa-list-ol text-[11px]" style="color: var(--p-btn-primary-bg);"></i>
                                                <span>Cara Menambah Banner</span>
                                            </h4>
                                            <ol class="list-decimal list-inside space-y-1 text-[12.5px] pl-0.5 leading-relaxed" style="color: var(--p-text-sub);">
                                                <li>Klik tombol <strong>Tambah Banner Baru</strong> di kanan atas.</li>
                                                <li>Isi judul promo dan link tujuan promosi.</li>
                                                <li>Unggah file foto atau video yang sesuai.</li>
                                                <li>Atur jadwal mulai dan selesai tayang banner.</li>
                                            </ol>
                                        </div>
                                        <div class="space-y-1.5">
                                            <h4 class="font-bold flex items-center gap-1.5 text-[13.5px]" style="color: var(--p-text-main);">
                                                <i class="fa-solid fa-photo-film text-[11px]" style="color: var(--p-btn-primary-bg);"></i>
                                                <span>Ukuran Media Disarankan</span>
                                            </h4>
                                            <ul class="space-y-1 text-[12.5px] leading-relaxed" style="color: var(--p-text-sub);">
                                                <li>• <strong>Foto:</strong> 16:9 (1920×1080 px), JPG/PNG/WebP, maks. 5 MB.</li>
                                                <li>• <strong>Video:</strong> 16:9 MP4/WebM/MOV, maks. 20 MB.</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-1.5">
                                            <h4 class="font-bold flex items-center gap-1.5 text-[13.5px]" style="color: var(--p-text-main);">
                                                <i class="fa-regular fa-lightbulb text-[11px]" style="color: var(--p-status-expiring-text);"></i>
                                                <span>Tips Pengelolaan</span>
                                            </h4>
                                            <ul class="space-y-1 text-[12.5px] leading-relaxed" style="color: var(--p-text-sub);">
                                                <li>• Banner otomatis selesai saat waktu tayang habis.</li>
                                                <li>• Perpanjang durasi kapan saja tanpa perlu edit ulang form.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Filter Status (Chip Navigasi Tanpa Kalimat Tambahan) -->
                <div class="flex items-center gap-1.5 overflow-x-auto text-[13px] no-scrollbar py-0.5">
                    <button type="button" class="min-h-[44px] sm:min-h-[33px] sm:h-[33px] px-3 rounded-xl font-semibold flex items-center justify-center transition shadow-2xs"
                            style="background-color: var(--p-chip-active-bg); color: var(--p-chip-active-text);">
                        <span>Semua ({{ max(1, $totalCount ?? 3) }})</span>
                    </button>

                    <button type="button" class="min-h-[44px] sm:min-h-[33px] sm:h-[33px] px-3 rounded-xl font-medium border flex items-center justify-center gap-1.5 transition"
                            style="background-color: var(--p-chip-inactive-bg); border-color: var(--p-chip-inactive-border); color: var(--p-chip-inactive-text);">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--p-status-active-text);"></span>
                        <span>Sedang Tampil ({{ max(1, $activeCount ?? 2) }})</span>
                    </button>

                    <button type="button" class="min-h-[44px] sm:min-h-[33px] sm:h-[33px] px-3 rounded-xl font-medium border flex items-center justify-center gap-1.5 transition"
                            style="background-color: var(--p-chip-inactive-bg); border-color: var(--p-chip-inactive-border); color: var(--p-status-expiring-text);">
                        <i class="fa-solid fa-clock text-[10.5px]"></i>
                        <span>Segera Berakhir (1)</span>
                    </button>

                    <button type="button" class="min-h-[44px] sm:min-h-[33px] sm:h-[33px] px-3 rounded-xl font-medium border flex items-center justify-center transition"
                            style="background-color: var(--p-chip-inactive-bg); border-color: var(--p-chip-inactive-border); color: var(--p-chip-inactive-text);">
                        <span>Akan Tampil (0)</span>
                    </button>

                    <button type="button" class="min-h-[44px] sm:min-h-[33px] sm:h-[33px] px-3 rounded-xl font-medium border flex items-center justify-center transition"
                            style="background-color: var(--p-chip-inactive-bg); border-color: var(--p-chip-inactive-border); color: var(--p-chip-inactive-text);">
                        <span>Sudah Berakhir (0)</span>
                    </button>
                </div>

                <!-- =============================================================
                     DAFTAR KARTU BANNER (GRID 2 KOLOM DI LAYAR LEBAR)
                     Angka "1" Tampil Sempurna & Tegas ("Buy 1 Get 1")
                     ============================================================= -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 lg:gap-5">
                    
                    {{-- KARTU 1: Real Data dari Database ("Buy 1 Get 1" - Sedang Tampil) --}}
                    @php
                        $firstBanner = $banners->first();
                        $card1Title = $firstBanner ? $firstBanner->title : 'Nucomu Weekend Fest: Buy 1 Get 1 Signature Tiramisu';
                        $card1Sub = $firstBanner && $firstBanner->subtitle ? str_ireplace(['&bull;', '&amp;bull;', '•'], ' · ', $firstBanner->subtitle) : 'Promo Menarik · Weekend Special';
                        $card1Desc = $firstBanner && $firstBanner->description ? $firstBanner->description : 'Nikmati promo beli 1 gratis 1 Classic Tiramisu Nucomu setiap pembelian varian Latte Sendang sepanjang akhir pekan!';
                        $card1Img = $firstBanner ? $firstBanner->media_url : asset('images/realistic-burger.jpg');
                    @endphp
                    <div class="theme-transition border overflow-hidden flex flex-col md:flex-row items-stretch p-3.5 sm:p-4 gap-3.5 sm:gap-4"
                         style="background-color: var(--p-bg-card); border-color: var(--p-border-card); border-radius: var(--p-radius-card); box-shadow: var(--p-shadow-card);">
                        
                        <!-- Foto di Kiri (Rasio 16:9, Lebar ~200px, Sudut 12px, Badge di Atas Foto) -->
                        <div class="w-full md:w-[200px] md:min-w-[200px] md:max-w-[200px] aspect-video rounded-[12px] overflow-hidden relative shrink-0 border"
                             style="border-color: var(--p-border-card); background-color: var(--p-bg-page);">
                            <img src="{{ $card1Img }}" alt="Banner 1" class="w-full h-full object-cover">
                            
                            <!-- Badge Status di Atas Foto (Pojok Kiri Atas) -->
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold border shadow-xs inline-flex items-center gap-1"
                                      style="background-color: var(--p-status-active-bg); color: var(--p-status-active-text); border-color: var(--p-status-active-border);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--p-status-active-text);"></span>
                                    <span>Sedang Tampil</span>
                                </span>
                            </div>

                            <span class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded-md text-[9.5px] font-semibold bg-black/70 backdrop-blur-xs text-white flex items-center gap-1 shadow-xs">
                                <i class="fa-regular fa-image text-[8px]"></i>
                                <span>Foto</span>
                            </span>
                        </div>

                        <!-- Kanan: Info, Jadwal, Bar Progres, Tombol Aksi -->
                        <div class="flex-1 min-w-0 flex flex-col justify-between space-y-2 py-0.5">
                            <div class="space-y-1">
                                <p class="text-[12px] font-medium truncate" style="color: var(--p-text-sub);">
                                    {{ $card1Sub }}
                                </p>
                                <!-- Judul Kartu: Sans-serif Plus Jakarta Sans 16px/600 (Angka "1" Normal & Tegak) -->
                                <h2 class="text-[15.5px] sm:text-[16px] font-semibold leading-snug line-clamp-1 sm:line-clamp-2" style="color: var(--p-text-main);" title="{{ $card1Title }}">
                                    {{ $card1Title }}
                                </h2>
                                <p class="text-[13px] line-clamp-2 leading-relaxed" style="color: var(--p-text-sub);">
                                    {{ $card1Desc }}
                                </p>
                            </div>

                            <!-- Bar Progres Durasi Waktu dengan Teks "Tersisa 4 hari" -->
                            <div class="space-y-1 pt-0.5">
                                <div class="flex items-center justify-between text-[12px]">
                                    <span class="inline-flex items-center gap-1.5 truncate" style="color: var(--p-text-sub);">
                                        <i class="fa-regular fa-calendar text-[11px]"></i>
                                        <span>02 Nov - 07 Nov 2026</span>
                                    </span>
                                    <span class="font-bold text-[11.5px] shrink-0 ml-2" style="color: var(--p-status-active-text);">
                                        Tersisa 4 hari
                                    </span>
                                </div>
                                <div class="w-full rounded-full h-[3.5px] overflow-hidden" style="background-color: var(--p-progress-track);">
                                    <div class="h-full rounded-full transition-all duration-500" style="width: 35%; background-color: var(--p-progress-bar);"></div>
                                </div>
                            </div>

                            <!-- Tombol Aksi Satu Baris (Semua Ada Tulisannya) -->
                            <div class="pt-2 border-t flex flex-wrap items-center justify-between gap-1.5"
                                 style="border-top-color: var(--p-border-card);">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <a href="#edit" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-3 rounded-lg text-[13px] font-semibold transition-all flex items-center gap-1.5 shadow-2xs"
                                       style="background-color: var(--p-btn-primary-bg); color: var(--p-btn-primary-text);">
                                        <i class="fa-regular fa-pen-to-square text-[11.5px]"></i>
                                        <span>Edit</span>
                                    </a>
                                    <button type="button" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-2.5 rounded-lg border text-[13px] font-medium transition-colors flex items-center gap-1.5 shadow-2xs"
                                            style="background-color: var(--p-btn-secondary-bg); border-color: var(--p-btn-secondary-border); color: var(--p-btn-secondary-text);">
                                        <i class="fa-regular fa-clock text-[11.5px]" style="color: var(--p-text-sub);"></i>
                                        <span>Perpanjang waktu</span>
                                    </button>
                                    <button type="button" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-2.5 rounded-lg border text-[13px] font-medium transition-colors flex items-center gap-1.5 shadow-2xs"
                                            style="background-color: var(--p-btn-secondary-bg); border-color: var(--p-btn-secondary-border); color: var(--p-text-sub);">
                                        <i class="fa-solid fa-eye-slash text-[11.5px]"></i>
                                        <span>Matikan</span>
                                    </button>
                                </div>
                                <button type="button" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-2 rounded-lg text-[13px] font-semibold transition-colors flex items-center gap-1"
                                        style="color: var(--p-status-danger-text);">
                                    <i class="fa-regular fa-trash-can text-[11.5px]"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- KARTU 2: Variasi Status ("Segera Berakhir" - Tersisa 25 menit) --}}
                    <div class="theme-transition border overflow-hidden flex flex-col md:flex-row items-stretch p-3.5 sm:p-4 gap-3.5 sm:gap-4"
                         style="background-color: var(--p-bg-card); border-color: var(--p-border-card); border-radius: var(--p-radius-card); box-shadow: var(--p-shadow-card);">
                        
                        <!-- Foto di Kiri -->
                        <div class="w-full md:w-[200px] md:min-w-[200px] md:max-w-[200px] aspect-video rounded-[12px] overflow-hidden relative shrink-0 border"
                             style="border-color: var(--p-border-card); background-color: var(--p-bg-page);">
                            <img src="{{ asset('images/nucomu/nucomu_hero.jpg') }}" alt="Banner 2" class="w-full h-full object-cover">
                            
                            <!-- Badge Status "Segera Berakhir" di Atas Foto -->
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold border shadow-xs inline-flex items-center gap-1 animate-pulse"
                                      style="background-color: var(--p-status-expiring-bg); color: var(--p-status-expiring-text); border-color: var(--p-status-expiring-border);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--p-status-expiring-text);"></span>
                                    <span>Segera Berakhir</span>
                                </span>
                            </div>

                            <span class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded-md text-[9.5px] font-semibold bg-black/70 backdrop-blur-xs text-white flex items-center gap-1 shadow-xs">
                                <i class="fa-regular fa-image text-[8px]"></i>
                                <span>Foto</span>
                            </span>
                        </div>

                        <!-- Kanan -->
                        <div class="flex-1 min-w-0 flex flex-col justify-between space-y-2 py-0.5">
                            <div class="space-y-1">
                                <p class="text-[12px] font-medium truncate" style="color: var(--p-text-sub);">
                                    Flash Promo · Nucomu Signature
                                </p>
                                <h2 class="text-[15.5px] sm:text-[16px] font-semibold leading-snug line-clamp-1 sm:line-clamp-2" style="color: var(--p-text-main);">
                                    Diskon 25% Varian Cold Brew &amp; Espresso Series
                                </h2>
                                <p class="text-[13px] line-clamp-2 leading-relaxed" style="color: var(--p-text-sub);">
                                    Penawaran spesial jam santai sore hari untuk seluruh racikan kopi biji arabika lokal Tulungagung pilihan barista.
                                </p>
                            </div>

                            <!-- Bar Progres dengan Teks "Tersisa 25 menit" -->
                            <div class="space-y-1 pt-0.5">
                                <div class="flex items-center justify-between text-[12px]">
                                    <span class="inline-flex items-center gap-1.5 truncate" style="color: var(--p-text-sub);">
                                        <i class="fa-regular fa-calendar text-[11px]"></i>
                                        <span>Hari ini, berakhir 16:00 WIB</span>
                                    </span>
                                    <span class="font-bold text-[11.5px] shrink-0 ml-2" style="color: var(--p-status-expiring-text);">
                                        Tersisa 25 menit
                                    </span>
                                </div>
                                <div class="w-full rounded-full h-[3.5px] overflow-hidden" style="background-color: var(--p-progress-track);">
                                    <div class="h-full rounded-full transition-all duration-500" style="width: 88%; background-color: var(--p-status-expiring-text);"></div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="pt-2 border-t flex flex-wrap items-center justify-between gap-1.5"
                                 style="border-top-color: var(--p-border-card);">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <a href="#edit" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-3 rounded-lg text-[13px] font-semibold transition-all flex items-center gap-1.5 shadow-2xs"
                                       style="background-color: var(--p-btn-primary-bg); color: var(--p-btn-primary-text);">
                                        <i class="fa-regular fa-pen-to-square text-[11.5px]"></i>
                                        <span>Edit</span>
                                    </a>
                                    <button type="button" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-2.5 rounded-lg border text-[13px] font-medium transition-colors flex items-center gap-1.5 shadow-2xs"
                                            style="background-color: var(--p-btn-secondary-bg); border-color: var(--p-btn-secondary-border); color: var(--p-btn-secondary-text);">
                                        <i class="fa-regular fa-clock text-[11.5px]" style="color: var(--p-text-sub);"></i>
                                        <span>Perpanjang waktu</span>
                                    </button>
                                    <button type="button" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-2.5 rounded-lg border text-[13px] font-medium transition-colors flex items-center gap-1.5 shadow-2xs"
                                            style="background-color: var(--p-btn-secondary-bg); border-color: var(--p-btn-secondary-border); color: var(--p-text-sub);">
                                        <i class="fa-solid fa-eye-slash text-[11.5px]"></i>
                                        <span>Matikan</span>
                                    </button>
                                </div>
                                <button type="button" class="btn-press min-h-[44px] sm:min-h-[32px] sm:h-[32px] px-2 rounded-lg text-[13px] font-semibold transition-colors flex items-center gap-1"
                                        style="color: var(--p-status-danger-text);">
                                    <i class="fa-regular fa-trash-can text-[11.5px]"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>

    <!-- =========================================================================
         SCRIPT: INSTANT PREVIEW THEME SWITCHER & DRAWER LOGIC
         ========================================================================= -->
    <script>
        const themesMeta = {
            a: {
                title: 'Desain A: "Terang Segar"',
                summary: 'Latar #F6F7F5 • Sidebar putih #FFFFFF • Teks #1B1F1D • Aksen hijau hutan #2F6B4F • Kartu putih sudut 12px, bayangan sangat tipis.'
            },
            b: {
                title: 'Desain B: "Krem Hangat"',
                summary: 'Latar #FBF8F3 • Sidebar #F4EFE7 • Teks #2B2420 • Tombol utama espresso #3B2F2A • Aksen status sage #5E7D5B • Kartu putih sudut 14px.'
            },
            c: {
                title: 'Desain C: "Gelap Premium"',
                summary: 'Latar #141416 • Sidebar #0F0F11 • Kartu #1C1C1F • Teks #F1EDE6 • Aksen pasir hangat #D6B98C • Sudut 12px, status versi cerah berdaya kontras tinggi.'
            }
        };

        function setPreviewTheme(themeKey) {
            const valid = ['a', 'b', 'c'].includes(themeKey) ? themeKey : 'a';
            document.documentElement.setAttribute('data-theme', valid);
            localStorage.setItem('nucomu_admin_preview_theme', valid);

            // Update tab button styles
            ['a', 'b', 'c'].forEach(k => {
                const btn = document.getElementById(`tab${k.toUpperCase()}`);
                if (btn) {
                    if (k === valid) {
                        btn.className = 'btn-press flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-xl text-[12px] font-bold border border-white/40 bg-white/15 text-white shadow-xs';
                    } else {
                        btn.className = 'btn-press flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl text-[12px] font-medium border border-transparent text-gray-400 hover:text-white hover:bg-white/5';
                    }
                }
            });

            // Update description text
            const descEl = document.getElementById('themeDescriptionText');
            if (descEl && themesMeta[valid]) {
                descEl.innerHTML = `<strong>${themesMeta[valid].title}</strong>: ${themesMeta[valid].summary}`;
            }
        }

        function togglePreviewDrawer() {
            const sidebar = document.getElementById('previewSidebar');
            const backdrop = document.getElementById('previewDrawerBackdrop');
            if (!sidebar || !backdrop) return;

            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            } else {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
            }
        }

        // Initialize from localStorage or default to 'a'
        document.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem('nucomu_admin_preview_theme') || 'a';
            setPreviewTheme(saved);
            setTitleStyle('1');
        });

        /* =========================================================================
           SCRIPT: TITLE STYLE SWITCHER (1. Serif Editorial / 2. Sans Bar / 3. Eyebrow)
           ========================================================================= */
        let currentTitleStyle = '1';

        function setTitleStyle(num) {
            currentTitleStyle = String(num);

            ['1', '2', '3'].forEach(n => {
                const topBtn = document.getElementById('topTitleBtn' + n);
                const card = document.getElementById('titleCard' + n);
                const cardBtn = document.getElementById('cardSelectBtn' + n);
                const liveView = document.getElementById('liveTitleView' + n);

                if (n === currentTitleStyle) {
                    if (topBtn) {
                        topBtn.className = 'px-2.5 py-0.5 rounded-lg text-[11px] font-bold transition-all border border-amber-400 bg-amber-400 text-black shadow-xs';
                    }
                    if (card) {
                        card.classList.add('ring-2', 'ring-[#2F6B4F]', 'border-transparent');
                    }
                    if (cardBtn) {
                        cardBtn.className = 'w-full py-2 px-3 rounded-xl text-[12px] font-bold text-white bg-[#2F6B4F] shadow-xs flex items-center justify-center gap-1.5 transition-all';
                        cardBtn.innerHTML = '<i class="fa-solid fa-circle-check text-[11px]"></i> <span>Sedang Aktif di Bawah</span>';
                    }
                    if (liveView) {
                        liveView.classList.remove('hidden');
                    }
                } else {
                    if (topBtn) {
                        topBtn.className = 'px-2.5 py-0.5 rounded-lg text-[11px] font-medium transition-all border border-white/20 text-gray-300 hover:text-white hover:bg-white/10';
                    }
                    if (card) {
                        card.classList.remove('ring-2', 'ring-[#2F6B4F]', 'border-transparent');
                    }
                    if (cardBtn) {
                        cardBtn.className = 'w-full py-2 px-3 rounded-xl text-[12px] font-semibold border transition-all flex items-center justify-center gap-1.5 hover:bg-black/5 dark:hover:bg-white/5';
                        cardBtn.innerHTML = `<span>Pilih Gaya ${n}</span>`;
                    }
                    if (liveView) {
                        liveView.classList.add('hidden');
                    }
                }
            });

            const activeLabel = document.getElementById('activeTitleStyleLabel');
            if (activeLabel) {
                if (currentTitleStyle === '1') activeLabel.textContent = '1. Serif Editorial (Fraunces)';
                else if (currentTitleStyle === '2') activeLabel.textContent = '2. Sans Garis Aksen (Manrope + Bar)';
                else if (currentTitleStyle === '3') activeLabel.textContent = '3. Sapaan Kecil (Eyebrow Tag)';
            }
        }

        function togglePreviewHelp() {
            const content = document.getElementById('previewHelpContent');
            const triggers = document.querySelectorAll('.preview-help-trigger');
            if (!content) return;

            const isOpen = content.classList.contains('is-open');
            if (isOpen) {
                content.classList.remove('is-open');
                triggers.forEach(t => t.setAttribute('aria-expanded', 'false'));
            } else {
                content.classList.add('is-open');
                triggers.forEach(t => t.setAttribute('aria-expanded', 'true'));
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' || e.key === 'Esc') {
                const content = document.getElementById('previewHelpContent');
                if (content && content.classList.contains('is-open')) {
                    content.classList.remove('is-open');
                    document.querySelectorAll('.preview-help-trigger').forEach(t => t.setAttribute('aria-expanded', 'false'));
                }
            }
        });
    </script>
</body>
</html>
