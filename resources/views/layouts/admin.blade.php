@php
    try {
        $pendingReservationCount = \App\Models\Reservation::where('status', 'pending')->count();
        $pendingOrderCount = \App\Models\Order::where('status', 'pending')->count();

        $allAdminNotifs = class_exists(\App\Http\Controllers\Admin\NotificationController::class)
            ? \App\Http\Controllers\Admin\NotificationController::gatherAllNotifications()
            : [];
        // Tab "Pesan": pesan dari customer (reservasi, pesanan, ulasan)
        $notifMessages = array_values(array_filter($allAdminNotifs, fn ($n) => in_array($n['type'], ['reservation', 'order', 'review'])));
        // Tab "Notifikasi": kabar sistem (banner promo hampir berakhir / kedaluwarsa)
        $notifSystems = array_values(array_filter($allAdminNotifs, fn ($n) => $n['type'] === 'banner'));

        $unreadMessagesCount = count(array_filter($notifMessages, fn ($n) => ! $n['is_read']));
        $unreadSystemCount = count(array_filter($notifSystems, fn ($n) => ! $n['is_read']));
        $unreadNotificationCount = $unreadMessagesCount + $unreadSystemCount;
        $pendingNotificationCount = $unreadNotificationCount;
    } catch (\Throwable $e) {
        $pendingReservationCount = 0;
        $pendingOrderCount = 0;
        $allAdminNotifs = [];
        $notifMessages = [];
        $notifSystems = [];
        $unreadMessagesCount = 0;
        $unreadSystemCount = 0;
        $unreadNotificationCount = 0;
        $pendingNotificationCount = 0;
    }

    $routePageMap = [
        'admin.dashboard' => 'Dashboard',
        'admin.banners.*' => 'Banner Halaman Depan',
        'admin.about.*' => 'Tentang Kafe',
        'admin.facilities.*' => 'Fasilitas Kafe',
        'admin.categories.*' => 'Kategori Menu',
        'admin.menus.*' => 'Daftar Menu',
        'admin.gallery.*' => 'Galeri Foto',
        'admin.testimonials.*' => 'Ulasan Pelanggan',
        'admin.reservations.*' => 'Reservasi Meja',
        'admin.orders.scan*' => 'Scan Barcode Kasir',
        'admin.orders.*' => 'Daftar Pesanan',
        'admin.events.*' => 'Agenda & Event',
        'admin.zendo.*' => 'Kurir Zendo',
        'admin.contact.*' => 'Kontak & Sosmed',
        'admin.settings.*' => 'Pengaturan Kafe',
        'admin.notifications.*' => 'Pusat Notifikasi',
        'admin.help.*' => 'Bantuan & Panduan',
        'admin.profile.*' => 'Profil Admin',
    ];

    $currentRouteName = request()->route() ? request()->route()->getName() : null;
    $fallbackPageTitle = 'Nucomu Cafe';
    if ($currentRouteName) {
        foreach ($routePageMap as $pattern => $title) {
            if (\Illuminate\Support\Str::is($pattern, $currentRouteName)) {
                $fallbackPageTitle = $title;
                break;
            }
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-admin-theme="a">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel — Nucomu Cafe Tulungagung')</title>
    
    <!-- Google Fonts: Fraunces (Judul Serif Accent), Manrope (Judul Sans), Plus Jakarta Sans (Isi) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,600&family=Manrope:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Nucomu Centralized Admin Design Tokens (Desain A: Terang Segar / Desain B: Krem Hangat) -->
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Legacy token aliases mapping to Design A tokens for backwards compatibility
                        'ink-black': '#1B1F1D',
                        'charcoal': '#30302D',
                        'warm-stone': '#EAE6DF',
                        'hairline': '#E6E8E4',
                        'pure-white': '#FFFFFF',
                        'muted-text': '#7A847E',
                        'warm-ivory': '#F6F7F5',
                    },
                    fontFamily: {
                        heading: ['"Manrope"', 'system-ui', 'sans-serif'],
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.04)',
                        'dropdown': '0 16px 36px -6px rgba(0, 0, 0, 0.12)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: var(--admin-bg-page);
            color: var(--admin-text-main);
            font-size: 14px;
        }

        /* Kartu Standar Panel Admin */
        .admin-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-card-radius);
            border: 1px solid var(--admin-card-border);
            box-shadow: var(--admin-card-shadow);
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
        }

        @media (hover: hover) and (pointer: fine) {
            .admin-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--admin-card-hover-shadow);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-card {
                transition: none !important;
                transform: none !important;
            }
            .admin-card:hover {
                transform: none !important;
            }
        }

        /* Scrollbar Konten & Sidebar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #F4F0EA; }
        ::-webkit-scrollbar-thumb { background: #D9D2C7; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #B8AC9F; }

        /* Slim Scrollbar for Dark Sidebar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(233, 225, 214, 0.20) transparent;
        }
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(233, 225, 214, 0.20);
            border-radius: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(233, 225, 214, 0.35);
        }

        /* =========================================================================
           SCROLL REVEAL (Persis seperti di Web Customer Nucomu)
           Elemen mulai kosong (transparan + sedikit turun), lalu meluncur naik saat discroll
           ========================================================================= */
        .reveal-on-scroll,
        .reveal-fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-on-scroll.is-visible,
        .reveal-fade-up.is-visible {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .reveal-from-left {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-from-left.is-visible {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        .reveal-from-right {
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-from-right.is-visible {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        .reveal-delay-1 { transition-delay: 0.08s; }
        .reveal-delay-2 { transition-delay: 0.16s; }
        .reveal-delay-3 { transition-delay: 0.24s; }
        .reveal-delay-4 { transition-delay: 0.32s; }

        /* Smooth Editorial Entrance Animations */
        @keyframes revealFadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .anim-fade-up {
            animation: revealFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .anim-delay-1 { animation-delay: 0.06s; }
        .anim-delay-2 { animation-delay: 0.12s; }
        .anim-delay-3 { animation-delay: 0.18s; }
        .anim-delay-4 { animation-delay: 0.24s; }

        /* Accessible Screen Reader / Visually Hidden Utility */
        .sr-only,
        .visually-hidden {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        /* Micro Interactions */
        .btn-press {
            transition: transform 0.15s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-press:active {
            transform: scale(0.97);
        }

        .card-lift {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .card-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px -6px rgba(25, 25, 25, 0.07);
        }

        /* Glass Sidebar with Warm Ivory Tint */
        .glass-sidebar {
            background: rgba(250, 248, 245, 0.96);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-right: 1px solid #E8E2D9;
        }

        /* Slim/Hidden Scrollbar for Sidebar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #E8E2D9 transparent;
        }
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #E8E2D9;
            border-radius: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #D5CDC3;
        }

        /* Custom Switch Animation */
        .switch-track {
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .switch-thumb {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* =========================================================================
           TOP-RIGHT POPUP TOAST SYSTEM (Sistem Notifikasi Melayang Kanan Atas)
           ========================================================================= */
        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(110%) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        @keyframes toastSlideOut {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateX(110%) scale(0.96);
            }
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }
        .toast-popup-item {
            animation: toastSlideIn 0.38s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .toast-popup-item.is-dismissing {
            animation: toastSlideOut 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .toast-progress-bar {
            animation: toastProgress linear forwards;
        }
        .toast-popup-item:hover .toast-progress-bar {
            animation-play-state: paused;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased min-h-screen flex flex-col md:flex-row relative selection:bg-[var(--admin-btn-primary-bg)] selection:text-white" style="background-color: var(--admin-bg-page); color: var(--admin-text-main);">

    <!-- Global Top-Right Toast Popup Container (Bisa Menampung Pesan Apa Saja dalam Kotak Persegi) -->
    <div id="adminToastContainer" class="fixed top-16 right-3.5 sm:top-20 sm:right-6 z-[99999] flex flex-col items-end gap-3 pointer-events-none select-none"></div>

    <!-- Mobile Drawer Backdrop -->
    <div id="admin-drawer-backdrop" onclick="toggleAdminDrawer()" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

    <!-- Sidebar Navigation: Desain A "Terang Segar" / Desain B "Krem Hangat" via CSS Variables -->
    <aside id="admin-sidebar" class="fixed md:sticky top-0 left-0 bottom-0 w-[240px] md:w-[200px] shrink-0 flex flex-col h-screen max-h-screen overflow-hidden z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out border-r" style="background-color: var(--admin-bg-sidebar); border-right-color: var(--admin-border-sidebar);">
        
        <!-- Brand Header: Tetap/Fixed di Atas Sidebar, Tinggi Sama Persis dengan Header (56px) & Garis Pemisah Sejajar -->
        <div class="h-[56px] min-h-[56px] max-h-[56px] px-[16px] flex items-center justify-between border-b shrink-0 box-border relative z-10 select-none" style="height: var(--admin-header-height); min-height: var(--admin-header-height); background-color: var(--admin-bg-sidebar); border-bottom-color: var(--admin-border-sidebar);">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group min-w-0" title="Nucomu Cafe Admin">
                <div class="w-[36px] h-[36px] rounded-lg border flex items-center justify-center shadow-xs transition-all shrink-0" style="background-color: var(--admin-logo-box-bg); border-color: var(--admin-logo-box-border);">
                    <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu" class="h-[18px] w-auto object-contain">
                </div>
                <span class="text-[15.5px] font-bold tracking-tight leading-none truncate" style="color: var(--admin-logo-text);">NUCOMU CAFE</span>
            </a>
            <button type="button" onclick="toggleAdminDrawer()" class="md:hidden p-1.5 rounded-lg opacity-70 hover:opacity-100 transition-opacity flex items-center justify-center min-w-[32px] min-h-[32px]" aria-label="Tutup Menu" style="color: var(--admin-text-sidebar);">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Scrollable Navigation Items Container (Scroll mandiri di bawah logo, Dashboard tidak terpotong) -->
        <div class="flex-1 overflow-y-auto admin-sidebar-scroll p-2.5 pb-8 space-y-[2px] min-h-0 relative z-0">
            <nav class="space-y-[2px]">
                {{-- Dashboard --}}
                @php $isActive = request()->routeIs('admin.dashboard'); @endphp
                <a href="{{ route('admin.dashboard') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <rect width="7" height="9" x="3" y="3" rx="1"/>
                            <rect width="7" height="5" x="14" y="3" rx="1"/>
                            <rect width="7" height="9" x="14" y="12" rx="1"/>
                            <rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        <span class="truncate">Dashboard</span>
                    </div>
                </a>

                {{-- Label Menu Utama --}}
                <div class="admin-sidebar-group-label">
                    Menu Utama
                </div>

                {{-- 1. Banner --}}
                @php
                    $isActive = request()->routeIs('admin.banners.*');
                    try {
                        $sidebarExpiringBannerCount = \App\Models\Banner::expiringSoon()->count();
                    } catch (\Throwable $e) {
                        $sidebarExpiringBannerCount = 0;
                    }
                @endphp
                <a href="{{ route('admin.banners.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}"
                   title="Kelola foto dan video banner di bagian atas website">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <path d="m3 11 18-5v12L3 14v-3z"/>
                            <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                        </svg>
                        <span class="truncate">Banner</span>
                    </div>
                    @if($sidebarExpiringBannerCount > 0)
                        <span class="admin-sidebar-badge" 
                              title="{{ $sidebarExpiringBannerCount }} banner segera berakhir">
                            {{ $sidebarExpiringBannerCount > 9 ? '9+' : $sidebarExpiringBannerCount }}
                        </span>
                    @endif
                </a>

                {{-- 2. Tentang Cafe --}}
                @php $isActive = request()->routeIs('admin.about.*'); @endphp
                <a href="{{ route('admin.about.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        <span class="truncate">Tentang Cafe</span>
                    </div>
                </a>

                {{-- 3. Fasilitas --}}
                @php $isActive = request()->routeIs('admin.facilities.*'); @endphp
                <a href="{{ route('admin.facilities.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/>
                            <path d="M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v2H7v-2a2 2 0 0 0-4 0Z"/>
                            <path d="M5 18v2"/>
                            <path d="M19 18v2"/>
                        </svg>
                        <span class="truncate">Fasilitas</span>
                    </div>
                </a>

                {{-- 4. Menu --}}
                @php $isActive = request()->routeIs('admin.menus.*', 'admin.categories.*'); @endphp
                <a href="{{ route('admin.menus.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                            <path d="M7 2v20"/>
                            <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                        </svg>
                        <span class="truncate">Menu</span>
                    </div>
                </a>

                {{-- 5. Gallery --}}
                @php $isActive = request()->routeIs('admin.gallery.*'); @endphp
                <a href="{{ route('admin.gallery.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                        <span class="truncate">Gallery</span>
                    </div>
                </a>

                {{-- 6. Ulasan --}}
                @php $isActive = request()->routeIs('admin.testimonials.*'); @endphp
                <a href="{{ route('admin.testimonials.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <span class="truncate">Ulasan</span>
                    </div>
                </a>

                {{-- 7. Reservasi --}}
                @php $isActive = request()->routeIs('admin.reservations.*'); @endphp
                <a href="{{ route('admin.reservations.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                            <path d="m9 16 2 2 4-4"/>
                        </svg>
                        <span class="truncate">Reservasi</span>
                    </div>
                    @if($pendingReservationCount > 0)
                        <span class="admin-sidebar-badge"
                              title="{{ $pendingReservationCount }} reservasi menunggu konfirmasi">
                            {{ $pendingReservationCount > 9 ? '9+' : $pendingReservationCount }}
                        </span>
                    @endif
                </a>

                {{-- 8. Transaksi --}}
                @php $isActive = request()->routeIs('admin.orders.*', 'admin.zendo.*'); @endphp
                <a href="{{ route('admin.orders.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"/>
                            <path d="M16 8h-8"/>
                            <path d="M16 12h-8"/>
                            <path d="M11 16H8"/>
                        </svg>
                        <span class="truncate">Transaksi</span>
                    </div>
                    @if($pendingOrderCount > 0)
                        <span class="admin-sidebar-badge"
                              title="{{ $pendingOrderCount }} pesanan menunggu">
                            {{ $pendingOrderCount > 9 ? '9+' : $pendingOrderCount }}
                        </span>
                    @endif
                </a>

                {{-- Shortcut Eksternal: POS Kasir --}}
                <a href="{{ route('kasir.index') }}" target="_blank"
                   class="admin-sidebar-nav-item"
                   title="Buka POS Kasir (/kasir)">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <rect width="20" height="14" x="2" y="5" rx="2"/>
                            <line x1="2" x2="22" y1="10" y2="10"/>
                        </svg>
                        <span class="truncate">POS Kasir</span>
                    </div>
                    <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-[#E6E8E4] text-[#1B1F1D]">↗</span>
                </a>

                {{-- 9. Kontak --}}
                @php $isActive = request()->routeIs('admin.contact.*'); @endphp
                <a href="{{ route('admin.contact.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
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

            {{-- Kelompok Terpisah: Akun & Sistem --}}
            <div class="admin-sidebar-group-label" style="border-top: 1px solid var(--admin-border-sidebar); margin-top: 8px;">
                Akun & Sistem
            </div>

            <nav class="space-y-[2px]">
                {{-- Profile --}}
                @php $isActive = request()->routeIs('admin.profile.*'); @endphp
                <a href="{{ route('admin.profile.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span class="truncate">Profil Admin</span>
                    </div>
                </a>

                {{-- Notifikasi --}}
                @php $isActive = request()->routeIs('admin.notifications.*'); @endphp
                <a href="{{ route('admin.notifications.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span class="truncate">Pusat Notifikasi</span>
                    </div>
                    @if(($unreadNotificationCount ?? 0) > 0)
                        <span class="admin-sidebar-badge"
                              title="{{ $unreadNotificationCount }} notifikasi belum dibaca">
                            {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
                        </span>
                    @endif
                </a>

                {{-- Bantuan --}}
                @php $isActive = request()->routeIs('admin.help.*'); @endphp
                <a href="{{ route('admin.help.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <path d="M12 17h.01"/>
                        </svg>
                        <span class="truncate">Bantuan & Panduan</span>
                    </div>
                </a>

                {{-- Setting --}}
                @php $isActive = request()->routeIs('admin.settings.*'); @endphp
                <a href="{{ route('admin.settings.index') }}" 
                   class="admin-sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                    <div class="flex items-center gap-[10px] min-w-0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors">
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

    <!-- Main Content & Sticky Top Bar Wrapper -->
    <div id="admin-main-wrapper" class="flex-1 flex flex-col min-w-0 min-h-screen" style="background-color: var(--admin-bg-page);">
        <!-- Sticky Header Bar: Menyatu dengan latar halaman & Garis Pemisah Sejajar -->
        <header class="sticky top-0 z-40 h-[56px] min-h-[56px] max-h-[56px] px-3.5 sm:px-5 lg:px-6 flex items-center justify-between gap-3 box-border" style="height: var(--admin-header-height); min-height: var(--admin-header-height); background-color: var(--admin-bg-header); border-bottom: 1px solid var(--admin-border-header);">
            <!-- Left Side: Mobile Hamburger (HP only) -->
            <div class="flex items-center min-w-0">
                <button type="button" onclick="toggleAdminDrawer()" class="md:hidden p-2 rounded-xl border opacity-80 hover:opacity-100 transition-opacity btn-press flex items-center justify-center min-w-[40px] min-h-[40px] shrink-0" style="border-color: var(--admin-border-header); background-color: var(--admin-bg-card); color: var(--admin-text-main);" aria-label="Buka Menu Admin">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
            </div>

            <!-- Right Side: Action Shortcuts, Notification Bell, User Menu (Tinggi Seragam 34px) -->
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                <!-- Status Koneksi Sistem (Tinggi 34px) -->
                <div id="systemStatusPill" 
                     class="admin-header-pill shadow-2xs"
                     title="Sistem aktif terhubung ke server"
                     aria-label="Sistem aktif"
                     role="status">
                    <span id="systemStatusDot" class="w-2 h-2 rounded-full animate-pulse shrink-0" style="background-color: var(--admin-status-active-bg-solid, #2F6B4F);"></span>
                    <span id="systemStatusText" class="hidden sm:inline font-sans text-[12px] tracking-tight">Sistem aktif</span>
                </div>

                <!-- Tombol Lihat Website (Tinggi 34px) -->
                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
                   class="admin-header-btn shadow-2xs btn-press select-none"
                   title="Buka website kafe di tab baru">
                    <span class="hidden sm:inline">Lihat Website</span>
                    <span class="sm:hidden text-[12px] font-bold">Web</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>

                <!-- Pusat Notifikasi & Pesan: 1 Ikon Lonceng (Tinggi 34px) -->
                <div class="relative flex items-center" id="adminNotifDropdownContainer">
                    <button type="button" onclick="toggleAdminNotificationDropdown(event)" id="adminNotifDropdownBtn"
                            class="admin-header-icon-btn shadow-2xs btn-press select-none"
                            title="Pusat Pesan &amp; Notifikasi ({{ $unreadNotificationCount }} belum dilihat)"
                            aria-label="Buka Pesan &amp; Notifikasi"
                            aria-expanded="false"
                            aria-haspopup="true">
                        <i class="fa-regular fa-bell text-[15px]"></i>
                        <span id="headerNotifBadgeTotal"
                              class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 rounded-full text-white text-[10px] font-bold flex items-center justify-center leading-none shadow-xs pointer-events-none {{ $unreadNotificationCount > 0 ? '' : 'hidden' }}"
                              style="background-color: var(--admin-status-danger-bg-solid, #C0392B);">
                            {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
                        </span>
                        <span class="sr-only">Ada {{ $unreadNotificationCount }} notifikasi belum dilihat</span>
                    </button>

                    <!-- Dropdown Panel (Lebar penuh di HP, w-[410px] di Desktop) -->
                    <div id="adminNotifDropdownMenu"
                         class="hidden fixed inset-x-3 top-16 sm:absolute sm:inset-x-auto sm:right-0 sm:top-full sm:mt-2 w-auto sm:w-[410px] max-h-[85vh] rounded-2xl shadow-dropdown z-50 overflow-hidden flex flex-col anim-fade-up"
                         style="background-color: var(--admin-bg-card); border: 1px solid var(--admin-border-header);">
                        
                        <!-- Header Dropdown: Judul & Tombol Tandai Semua Dilihat -->
                        <div class="px-4 py-3 border-b flex items-center justify-between gap-2 shrink-0" style="border-bottom-color: var(--admin-border-header); background-color: var(--admin-bg-sidebar-hover);">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs" style="background-color: var(--admin-btn-primary-bg); color: var(--admin-btn-primary-text);">
                                    <i class="fa-regular fa-bell"></i>
                                </div>
                                <h3 class="text-sm font-bold tracking-tight" style="color: var(--admin-text-main);">Kabar &amp; Pesan Kafe</h3>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="markAllNotificationsAsRead(event)"
                                        class="px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition-colors flex items-center gap-1 min-h-[34px] btn-press"
                                        style="border-color: var(--admin-border-header); background-color: var(--admin-bg-card); color: var(--admin-text-main);"
                                        title="Tandai semua sudah dilihat">
                                    <i class="fa-solid fa-check-double text-[10px]" style="color: var(--admin-btn-primary-bg);"></i>
                                    <span>Tandai Semua Dilihat</span>
                                </button>
                                <button type="button" onclick="toggleAdminNotificationDropdown(event)"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors sm:hidden"
                                        style="color: var(--admin-text-muted);"
                                        aria-label="Tutup">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 2 Tab Navigasi: Pesan vs Notifikasi -->
                        <div class="grid grid-cols-2 p-1.5 border-b gap-1 shrink-0 text-xs font-semibold" style="border-bottom-color: var(--admin-border-header); background-color: var(--admin-bg-sidebar-hover);">
                            <button type="button" onclick="switchNotifTab('pesan')" id="tabBtnPesan"
                                    class="notif-tab-btn min-h-[40px] px-3 py-2 rounded-xl shadow-xs flex items-center justify-center gap-2 transition-all font-semibold"
                                    style="background-color: var(--admin-btn-primary-bg); color: var(--admin-btn-primary-text);">
                                <i class="fa-regular fa-comments text-xs"></i>
                                <span>Pesan</span>
                                <span id="tabBadgePesan"
                                      class="px-1.5 py-0.5 rounded-full text-white text-[10px] font-extrabold leading-none {{ $unreadMessagesCount > 0 ? '' : 'hidden' }}"
                                      style="background-color: var(--admin-status-danger-bg-solid, #C0392B);">
                                    {{ $unreadMessagesCount > 9 ? '9+' : $unreadMessagesCount }}
                                </span>
                            </button>
                            <button type="button" onclick="switchNotifTab('notifikasi')" id="tabBtnNotif"
                                    class="notif-tab-btn min-h-[40px] px-3 py-2 rounded-xl flex items-center justify-center gap-2 transition-all font-semibold"
                                    style="color: var(--admin-text-sub);">
                                <i class="fa-regular fa-bell text-xs"></i>
                                <span>Notifikasi</span>
                                <span id="tabBadgeNotif"
                                      class="px-1.5 py-0.5 rounded-full text-white text-[10px] font-extrabold leading-none {{ $unreadSystemCount > 0 ? '' : 'hidden' }}"
                                      style="background-color: var(--admin-status-danger-bg-solid, #C0392B);">
                                    {{ $unreadSystemCount > 9 ? '9+' : $unreadSystemCount }}
                                </span>
                            </button>
                        </div>

                        <!-- Panel Tab 1: Pesan (Customer: Reservasi, Pesanan, Ulasan) -->
                        <div id="panelNotifPesan" class="notif-panel flex-1 overflow-y-auto divide-y p-0 max-h-[55vh]" style="border-color: var(--admin-border-header);">
                            @forelse($notifMessages as $msg)
                                <div class="notif-item-row p-3 sm:p-3.5 transition-colors flex items-start gap-3 relative group {{ $msg['is_read'] ? 'opacity-70' : '' }}"
                                     style="background-color: {{ $msg['is_read'] ? 'transparent' : 'var(--admin-bg-sidebar-hover)' }}; border-color: var(--admin-border-header);"
                                     data-notif-key="{{ $msg['key'] }}"
                                     data-is-read="{{ $msg['is_read'] ? '1' : '0' }}">
                                    <!-- Ikon Jenis -->
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5 border"
                                         style="background-color: var(--admin-status-active-bg); color: var(--admin-status-active-text); border-color: var(--admin-status-active-border);">
                                        @if($msg['type'] === 'reservation')
                                            <i class="fa-regular fa-calendar-check text-sm"></i>
                                        @elseif($msg['type'] === 'order')
                                            <i class="fa-solid fa-receipt text-sm"></i>
                                        @else
                                            <i class="fa-regular fa-star text-sm"></i>
                                        @endif
                                    </div>

                                    <!-- Teks Konten -->
                                    <div class="flex-1 min-w-0 pr-1">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-[10.5px] font-bold uppercase tracking-wider" style="color: var(--admin-text-muted);">{{ $msg['type_label'] }}</span>
                                            <span class="text-[11px] shrink-0 font-medium" style="color: var(--admin-text-muted);">{{ $msg['time_ago'] }}</span>
                                        </div>
                                        <a href="{{ $msg['action_url'] }}"
                                           onclick="openAndMarkNotificationRead('{{ $msg['key'] }}', '{{ $msg['action_url'] }}', event)"
                                           class="block text-xs sm:text-[13px] font-bold transition-colors line-clamp-1 mt-0.5"
                                           style="color: var(--admin-text-main);">
                                            {{ $msg['title'] }}
                                        </a>
                                        <p class="text-xs line-clamp-2 mt-0.5 leading-relaxed" style="color: var(--admin-text-sub);">
                                            {{ $msg['message'] }}
                                        </p>
                                    </div>

                                    <!-- Titik Belum Dilihat -->
                                    <div class="shrink-0 flex items-center self-center pl-1">
                                        <span class="notif-dot w-2.5 h-2.5 rounded-full {{ $msg['is_read'] ? 'hidden' : '' }}" style="background-color: var(--admin-status-danger-bg-solid, #C0392B);" title="Belum dilihat"></span>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 px-4 text-center" style="color: var(--admin-text-muted);">
                                    <i class="fa-regular fa-comments text-3xl mb-2 opacity-50 block"></i>
                                    <p class="text-xs font-semibold" style="color: var(--admin-text-main);">Belum ada pesan baru</p>
                                    <p class="text-[11px] mt-0.5">Semua reservasi, ulasan, dan pesanan pelanggan sudah dibaca.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Panel Tab 2: Notifikasi (Sistem: Banner Kedaluwarsa, dll.) -->
                        <div id="panelNotifSistem" class="notif-panel hidden flex-1 overflow-y-auto divide-y p-0 max-h-[55vh]" style="border-color: var(--admin-border-header);">
                            @forelse($notifSystems as $sys)
                                <div class="notif-item-row p-3 sm:p-3.5 transition-colors flex items-start gap-3 relative group {{ $sys['is_read'] ? 'opacity-70' : '' }}"
                                     style="background-color: {{ $sys['is_read'] ? 'transparent' : 'var(--admin-bg-sidebar-hover)' }}; border-color: var(--admin-border-header);"
                                     data-notif-key="{{ $sys['key'] }}"
                                     data-is-read="{{ $sys['is_read'] ? '1' : '0' }}">
                                    <!-- Ikon Jenis -->
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5 border"
                                         style="background-color: var(--admin-status-expiring-bg); color: var(--admin-status-expiring-text); border-color: var(--admin-status-expiring-border);">
                                        <i class="fa-solid fa-bullhorn text-sm"></i>
                                    </div>

                                    <!-- Teks Konten -->
                                    <div class="flex-1 min-w-0 pr-1">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-[10.5px] font-bold uppercase tracking-wider" style="color: var(--admin-text-muted);">{{ $sys['type_label'] }}</span>
                                            <span class="text-[11px] shrink-0 font-medium" style="color: var(--admin-text-muted);">{{ $sys['time_ago'] }}</span>
                                        </div>
                                        <a href="{{ $sys['action_url'] }}"
                                           onclick="openAndMarkNotificationRead('{{ $sys['key'] }}', '{{ $sys['action_url'] }}', event)"
                                           class="block text-xs sm:text-[13px] font-bold transition-colors line-clamp-1 mt-0.5"
                                           style="color: var(--admin-text-main);">
                                            {{ $sys['title'] }}
                                        </a>
                                        <p class="text-xs line-clamp-2 mt-0.5 leading-relaxed" style="color: var(--admin-text-sub);">
                                            {{ $sys['message'] }}
                                        </p>
                                    </div>

                                    <!-- Titik Belum Dilihat -->
                                    <div class="shrink-0 flex items-center self-center pl-1">
                                        <span class="notif-dot w-2.5 h-2.5 rounded-full {{ $sys['is_read'] ? 'hidden' : '' }}" style="background-color: var(--admin-status-danger-bg-solid, #C0392B);" title="Belum dilihat"></span>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 px-4 text-center" style="color: var(--admin-text-muted);">
                                    <i class="fa-regular fa-bell-slash text-3xl mb-2 opacity-50 block"></i>
                                    <p class="text-xs font-semibold" style="color: var(--admin-text-main);">Belum ada notifikasi baru</p>
                                    <p class="text-[11px] mt-0.5">Seluruh banner promo dan kabar sistem kafe berjalan normal.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer Dropdown: Link ke Pusat Notifikasi Lengkap -->
                        <div class="p-2.5 border-t text-center shrink-0" style="border-top-color: var(--admin-border-header); background-color: var(--admin-bg-sidebar-hover);">
                            <a href="{{ route('admin.notifications.index') }}"
                               class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl border text-xs font-bold transition-all shadow-2xs btn-press"
                               style="background-color: var(--admin-bg-card); color: var(--admin-text-main); border-color: var(--admin-border-header);">
                                <span>Buka Pusat Notifikasi Lengkap</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Foto & Nama Admin dengan Dropdown (Tinggi 34px) -->
                <div class="relative flex items-center" id="adminUserDropdownContainer">
                    <button type="button" onclick="toggleAdminUserDropdown(event)" id="adminUserDropdownBtn"
                            class="admin-header-btn shadow-2xs btn-press select-none !px-2"
                            aria-expanded="false" aria-haspopup="true">
                        <img src="{{ auth()->user()?->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin&background=2F6B4F&color=FFFFFF' }}"
                             alt="{{ auth()->user()?->name ?? 'Admin' }}"
                             class="w-[22px] h-[22px] rounded-full object-cover shrink-0" style="border: 1px solid var(--admin-border-header);">
                        <div class="hidden lg:flex flex-col text-left leading-tight">
                            <span class="text-[12px] font-bold max-w-[110px] truncate" style="color: var(--admin-text-main);">{{ auth()->user()?->name ?? 'Admin Nucomu' }}</span>
                            <span class="text-[9.5px] truncate font-medium" style="color: var(--admin-text-muted);">Pengelola Kafe</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[8px] hidden sm:block transition-transform duration-200" id="adminUserDropdownArrow" style="color: var(--admin-text-muted);"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="adminUserDropdownMenu" class="hidden absolute right-0 mt-2 w-56 rounded-2xl shadow-dropdown py-2 z-50 text-xs font-medium anim-fade-up"
                         style="background-color: var(--admin-bg-card); border: 1px solid var(--admin-border-header); color: var(--admin-text-main);">
                        <div class="px-4 py-2 border-b" style="border-bottom-color: var(--admin-border-header);">
                            <p class="font-bold truncate" style="color: var(--admin-text-main);">{{ auth()->user()?->name ?? 'Admin Nucomu' }}</p>
                            <p class="text-[10px] truncate font-mono" style="color: var(--admin-text-muted);">{{ auth()->user()?->email ?? 'admin@nucomu.com' }}</p>
                        </div>
                        <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 transition-colors" style="color: var(--admin-text-main);">
                            <i class="fa-regular fa-user text-sm w-4 text-center" style="color: var(--admin-text-muted);"></i>
                            <span>Profil Admin</span>
                        </a>
                        <a href="{{ route('admin.help.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 transition-colors" style="color: var(--admin-text-main);">
                            <i class="fa-regular fa-circle-question text-sm w-4 text-center" style="color: var(--admin-text-muted);"></i>
                            <span>Bantuan &amp; Panduan</span>
                        </a>
                        <div class="my-1 border-t" style="border-top-color: var(--admin-border-header);"></div>
                        <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari panel admin?');">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-left transition-colors font-semibold" style="color: var(--admin-status-danger-text);">
                                <i class="fa-solid fa-arrow-right-from-bracket text-sm w-4 text-center" style="color: var(--admin-status-danger-text);"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Flow Area (Padding Konten) -->
        <main class="flex-1 p-4 sm:p-6 lg:p-7 w-full max-w-7xl mx-auto" id="admin-main">
            @yield('content')
        </main>
    </div>

    <script>
        /* =========================================================================
           GLOBAL TOP-RIGHT TOAST NOTIFICATION ENGINE
           Mampu menampung dan menampilkan segala jenis pesan (sukses, error,
           peringatan, info, atau validasi form) secara dinamis & melayang di kanan atas.
           ========================================================================= */
        function showAdminToast(message, type = 'success', title = null, duration = 4500) {
            const container = document.getElementById('adminToastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'toast-popup-item pointer-events-auto bg-pure-white/98 backdrop-blur-md border border-hairline rounded-2xl p-3.5 sm:p-4 shadow-dropdown w-72 sm:w-80 relative overflow-hidden flex flex-col gap-2.5 group select-text';

            // Visual configuration per notification type
            const typeConfig = {
                success: {
                    badgeClass: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    icon: 'fa-solid fa-circle-check text-emerald-600',
                    defaultTitle: 'Berhasil',
                    progressColor: 'bg-emerald-500'
                },
                error: {
                    badgeClass: 'bg-rose-50 text-rose-800 border-rose-200',
                    icon: 'fa-solid fa-circle-xmark text-rose-600',
                    defaultTitle: 'Terjadi Kesalahan',
                    progressColor: 'bg-rose-500'
                },
                warning: {
                    badgeClass: 'bg-amber-50 text-amber-900 border-amber-200',
                    icon: 'fa-solid fa-triangle-exclamation text-amber-600',
                    defaultTitle: 'Peringatan',
                    progressColor: 'bg-amber-500'
                },
                info: {
                    badgeClass: 'bg-warm-stone text-ink-black border-hairline',
                    icon: 'fa-regular fa-comment-dots text-ink-black',
                    defaultTitle: 'Kotak Pesan',
                    progressColor: 'bg-ink-black'
                }
            };

            const cfg = typeConfig[type] || typeConfig.info;
            const finalTitle = title || cfg.defaultTitle;

            toast.innerHTML = `
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-7 h-7 rounded-xl border flex items-center justify-center shrink-0 ${cfg.badgeClass} shadow-2xs">
                            <i class="${cfg.icon} text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="font-bold text-xs text-ink-black font-sans leading-tight block truncate">${finalTitle}</span>
                            <span class="text-[9px] font-mono text-muted-text font-semibold uppercase tracking-wider block">Baru saja</span>
                        </div>
                    </div>
                    <button type="button" class="btn-dismiss-toast text-muted-text hover:text-ink-black p-1 rounded-lg transition-colors shrink-0" title="Tutup Notifikasi">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                <div class="text-xs text-charcoal leading-relaxed font-normal bg-warm-ivory/80 rounded-xl p-2.5 border border-hairline/60">
                    ${message}
                </div>
                <div class="toast-progress-bar absolute bottom-0 left-0 h-0.5 ${cfg.progressColor}" style="animation-duration: ${duration}ms;"></div>
            `;

            container.appendChild(toast);

            let dismissTimer = null;
            let remainingTime = duration;
            let startTime = Date.now();

            const dismiss = () => {
                if (toast.classList.contains('is-dismissing')) return;
                toast.classList.add('is-dismissing');
                setTimeout(() => {
                    if (toast.parentElement) toast.parentElement.removeChild(toast);
                }, 350);
            };

            const dismissBtn = toast.querySelector('.btn-dismiss-toast');
            if (dismissBtn) {
                dismissBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    clearTimeout(dismissTimer);
                    dismiss();
                });
            }

            // Start auto dismiss
            dismissTimer = setTimeout(dismiss, duration);

            // Pause on hover, resume on mouse leave
            toast.addEventListener('mouseenter', () => {
                clearTimeout(dismissTimer);
                remainingTime -= (Date.now() - startTime);
            });

            toast.addEventListener('mouseleave', () => {
                startTime = Date.now();
                dismissTimer = setTimeout(dismiss, Math.max(1000, remainingTime));
            });
        }

        window.showAdminToast = showAdminToast;
        window.toast = showAdminToast;

        /* =========================================================================
           HEADER NOTIFICATION & MESSAGE DROPDOWN CONTROLS
           ========================================================================= */
        function toggleAdminNotificationDropdown(event) {
            if (event) event.stopPropagation();
            const notifMenu = document.getElementById('adminNotifDropdownMenu');
            const notifBtn = document.getElementById('adminNotifDropdownBtn');
            const userMenu = document.getElementById('adminUserDropdownMenu');
            const userArrow = document.getElementById('adminUserDropdownArrow');
            const userBtn = document.getElementById('adminUserDropdownBtn');

            // Close user profile dropdown if open
            if (userMenu && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
                if (userArrow) userArrow.classList.remove('rotate-180');
                if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
            }

            if (!notifMenu) return;
            const isHidden = notifMenu.classList.contains('hidden');
            if (isHidden) {
                notifMenu.classList.remove('hidden');
                if (notifBtn) notifBtn.setAttribute('aria-expanded', 'true');
            } else {
                notifMenu.classList.add('hidden');
                if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
            }
        }

        // Backward compatibility alias for any existing buttons
        function toggleAdminMessagePopup() {
            toggleAdminNotificationDropdown();
        }

        function switchNotifTab(tabName) {
            const panelPesan = document.getElementById('panelNotifPesan');
            const panelSistem = document.getElementById('panelNotifSistem');
            const btnPesan = document.getElementById('tabBtnPesan');
            const btnNotif = document.getElementById('tabBtnNotif');

            if (tabName === 'pesan') {
                if (panelPesan) panelPesan.classList.remove('hidden');
                if (panelSistem) panelSistem.classList.add('hidden');

                if (btnPesan) {
                    btnPesan.className = 'notif-tab-btn min-h-[40px] px-3 py-2 rounded-xl bg-[#2B2420] text-[#FAF7F2] shadow-xs flex items-center justify-center gap-2 transition-all font-semibold';
                }
                if (btnNotif) {
                    btnNotif.className = 'notif-tab-btn min-h-[40px] px-3 py-2 rounded-xl text-[#7A6F66] hover:text-[#2B2420] hover:bg-white/60 flex items-center justify-center gap-2 transition-all font-semibold';
                }
            } else {
                if (panelPesan) panelPesan.classList.add('hidden');
                if (panelSistem) panelSistem.classList.remove('hidden');

                if (btnPesan) {
                    btnPesan.className = 'notif-tab-btn min-h-[40px] px-3 py-2 rounded-xl text-[#7A6F66] hover:text-[#2B2420] hover:bg-white/60 flex items-center justify-center gap-2 transition-all font-semibold';
                }
                if (btnNotif) {
                    btnNotif.className = 'notif-tab-btn min-h-[40px] px-3 py-2 rounded-xl bg-[#2B2420] text-[#FAF7F2] shadow-xs flex items-center justify-center gap-2 transition-all font-semibold';
                }
            }
        }

        function openAndMarkNotificationRead(key, targetUrl, event) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (key) {
                // Send toggle-read asynchronously to mark as read
                fetch("{{ route('admin.notifications.toggleRead') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ key: key }),
                    keepalive: true
                }).catch(() => {});
            }
            if (targetUrl) {
                window.location.href = targetUrl;
            }
        }

        function markAllNotificationsAsRead(event) {
            if (event) event.stopPropagation();
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Optimistically update UI
            document.querySelectorAll('.notif-dot').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.notif-item-row').forEach(row => {
                row.classList.remove('bg-[#FAF7F2]/50');
                row.classList.add('opacity-70', 'bg-white');
                row.setAttribute('data-is-read', '1');
            });

            const headerBadge = document.getElementById('headerNotifBadgeTotal');
            if (headerBadge) {
                headerBadge.textContent = '0';
                headerBadge.classList.add('hidden');
            }
            const badgePesan = document.getElementById('tabBadgePesan');
            if (badgePesan) {
                badgePesan.textContent = '0';
                badgePesan.classList.add('hidden');
            }
            const badgeNotif = document.getElementById('tabBadgeNotif');
            if (badgeNotif) {
                badgeNotif.textContent = '0';
                badgeNotif.classList.add('hidden');
            }
            const dashBadge = document.getElementById('dashboardMsgBadge');
            if (dashBadge) {
                dashBadge.textContent = '0';
                dashBadge.classList.add('hidden');
            }

            fetch("{{ route('admin.notifications.markAllRead') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                showAdminToast('Semua pesan & notifikasi ditandai telah dilihat.', 'success', 'Telah Dilihat', 2500);
            })
            .catch(() => {
                showAdminToast('Gagal menandai notifikasi.', 'error');
            });
        }

        /* =========================================================================
           LIGHTWEIGHT UNREAD NOTIFICATION & SYSTEM STATUS POLLING (Every 35 seconds)
           ========================================================================= */
        function updateSystemStatus(isOnline) {
            const pill = document.getElementById('systemStatusPill');
            const dot = document.getElementById('systemStatusDot');
            const text = document.getElementById('systemStatusText');
            if (!pill || !dot || !text) return;

            if (isOnline) {
                pill.classList.remove('border-rose-300', 'bg-rose-50/80', 'text-rose-700');
                pill.classList.add('border-[#E8E2D9]', 'bg-white', 'text-[#2B2420]');
                pill.setAttribute('title', 'Sistem aktif terhubung ke server');
                pill.setAttribute('aria-label', 'Sistem aktif');

                dot.classList.remove('bg-rose-500');
                dot.classList.add('bg-emerald-500', 'animate-pulse');

                text.textContent = 'Sistem aktif';
            } else {
                pill.classList.remove('border-[#E8E2D9]', 'bg-white', 'text-[#2B2420]');
                pill.classList.add('border-rose-300', 'bg-rose-50/80', 'text-rose-700');
                pill.setAttribute('title', 'Koneksi terputus: tidak dapat terhubung ke server');
                pill.setAttribute('aria-label', 'Tidak terhubung');

                dot.classList.remove('bg-emerald-500', 'animate-pulse');
                dot.classList.add('bg-rose-500');

                text.textContent = 'Tidak terhubung';
            }
        }

        function pollNotificationBadges() {
            fetch("{{ route('admin.notifications.poll') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Status ' + res.status);
                return res.json();
            })
            .then(data => {
                updateSystemStatus(true);
                if (!data) return;

                const headerBadge = document.getElementById('headerNotifBadgeTotal');
                if (headerBadge) {
                    if (data.unread_total > 0) {
                        headerBadge.textContent = data.unread_total > 9 ? '9+' : data.unread_total;
                        headerBadge.classList.remove('hidden');
                    } else {
                        headerBadge.classList.add('hidden');
                    }
                }

                const badgePesan = document.getElementById('tabBadgePesan');
                if (badgePesan) {
                    if (data.unread_messages > 0) {
                        badgePesan.textContent = data.unread_messages > 9 ? '9+' : data.unread_messages;
                        badgePesan.classList.remove('hidden');
                    } else {
                        badgePesan.classList.add('hidden');
                    }
                }

                const badgeNotif = document.getElementById('tabBadgeNotif');
                if (badgeNotif) {
                    if (data.unread_system > 0) {
                        badgeNotif.textContent = data.unread_system > 9 ? '9+' : data.unread_system;
                        badgeNotif.classList.remove('hidden');
                    } else {
                        badgeNotif.classList.add('hidden');
                    }
                }

                const dashBadge = document.getElementById('dashboardMsgBadge');
                if (dashBadge) {
                    if (data.unread_total > 0) {
                        dashBadge.textContent = data.unread_total;
                        dashBadge.classList.remove('hidden');
                    } else {
                        dashBadge.classList.add('hidden');
                    }
                }
            })
            .catch(() => {
                updateSystemStatus(false);
            });
        }

        // Start checking after 5 seconds, then repeat every 35 seconds (30-60 detik)
        setTimeout(pollNotificationBadges, 5000);
        setInterval(pollNotificationBadges, 35000);

        // Immediate online / offline listener
        window.addEventListener('online', () => pollNotificationBadges());
        window.addEventListener('offline', () => updateSystemStatus(false));

        function toggleAdminDrawer() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-drawer-backdrop');
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

        /* =========================================================================
           ADMIN TOP BAR USER DROPDOWN TOGGLE & CLICK-OUTSIDE DISMISSAL
           ========================================================================= */
        function toggleAdminUserDropdown(event) {
            if (event) event.stopPropagation();
            const notifMenu = document.getElementById('adminNotifDropdownMenu');
            const notifBtn = document.getElementById('adminNotifDropdownBtn');
            const userMenu = document.getElementById('adminUserDropdownMenu');
            const userArrow = document.getElementById('adminUserDropdownArrow');
            const userBtn = document.getElementById('adminUserDropdownBtn');

            // Close notif dropdown if open
            if (notifMenu && !notifMenu.classList.contains('hidden')) {
                notifMenu.classList.add('hidden');
                if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
            }

            if (!userMenu) return;
            const isHidden = userMenu.classList.contains('hidden');
            if (isHidden) {
                userMenu.classList.remove('hidden');
                if (userArrow) userArrow.classList.add('rotate-180');
                if (userBtn) userBtn.setAttribute('aria-expanded', 'true');
            } else {
                userMenu.classList.add('hidden');
                if (userArrow) userArrow.classList.remove('rotate-180');
                if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
            }
        }

        // Global dismiss for all header dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const notifContainer = document.getElementById('adminNotifDropdownContainer');
            const notifMenu = document.getElementById('adminNotifDropdownMenu');
            const notifBtn = document.getElementById('adminNotifDropdownBtn');
            if (notifContainer && notifMenu && !notifContainer.contains(e.target) && !notifMenu.classList.contains('hidden')) {
                notifMenu.classList.add('hidden');
                if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
            }

            const userContainer = document.getElementById('adminUserDropdownContainer');
            const userMenu = document.getElementById('adminUserDropdownMenu');
            const userArrow = document.getElementById('adminUserDropdownArrow');
            const userBtn = document.getElementById('adminUserDropdownBtn');
            if (userContainer && userMenu && !userContainer.contains(e.target) && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('hidden');
                if (userArrow) userArrow.classList.remove('rotate-180');
                if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // Close dropdowns on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const notifMenu = document.getElementById('adminNotifDropdownMenu');
                const notifBtn = document.getElementById('adminNotifDropdownBtn');
                if (notifMenu && !notifMenu.classList.contains('hidden')) {
                    notifMenu.classList.add('hidden');
                    if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
                }

                const userMenu = document.getElementById('adminUserDropdownMenu');
                const userArrow = document.getElementById('adminUserDropdownArrow');
                const userBtn = document.getElementById('adminUserDropdownBtn');
                if (userMenu && !userMenu.classList.contains('hidden')) {
                    userMenu.classList.add('hidden');
                    if (userArrow) userArrow.classList.remove('rotate-180');
                    if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        /* =========================================================================
           SCROLL REVEAL OBSERVER (Sama Persis dengan Nucomu Customer Website)
           Mendeteksi elemen yang berada di bawah layar, membiarkannya kosong, lalu
           meluncurkannya naik secara elegan begitu tergulung masuk ke viewport.
           ========================================================================= */
        function initAdminScrollReveal() {
            const reveals = document.querySelectorAll('.reveal-on-scroll, .reveal-fade-up, .reveal-from-left, .reveal-from-right');
            if (!reveals.length) return;

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                reveals.forEach(el => el.classList.add('is-visible'));
                return;
            }

            const onIntersect = (entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            };

            const observerOptions = {
                threshold: 0.08,
                rootMargin: '0px 0px -25px 0px'
            };

            if ('IntersectionObserver' in window) {
                // Observer for viewport (standard window scrolling)
                const viewportObserver = new IntersectionObserver(onIntersect, observerOptions);
                reveals.forEach(el => {
                    if (!el.classList.contains('is-visible')) {
                        viewportObserver.observe(el);
                    }
                });

                // Observer for #admin-main container (if inner container is scrolling)
                const mainContainer = document.getElementById('admin-main');
                if (mainContainer) {
                    const containerObserver = new IntersectionObserver(onIntersect, {
                        ...observerOptions,
                        root: mainContainer
                    });
                    reveals.forEach(el => {
                        if (!el.classList.contains('is-visible')) {
                            containerObserver.observe(el);
                        }
                    });
                }
            } else {
                reveals.forEach(el => el.classList.add('is-visible'));
            }
        }

        window.initAdminScrollReveal = initAdminScrollReveal;

        // Initialize scroll reveal on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                initAdminScrollReveal();
            });
        } else {
            initAdminScrollReveal();
        }

        // Trigger server session flash popups
        @if(session('success'))
            showAdminToast(@json(session('success')), 'success', 'Berhasil');
        @elseif(session('error'))
            showAdminToast(@json(session('error')), 'error', 'Perhatian');
        @elseif(session('warning'))
            showAdminToast(@json(session('warning')), 'warning', 'Peringatan');
        @elseif(session('info'))
            showAdminToast(@json(session('info')), 'info', 'Informasi');
        @elseif($errors->any())
            const errorHtml = `<ul class="list-disc pl-4 space-y-0.5 mt-1 font-normal text-xs text-amber-900">@foreach($errors->all() as $err)<li>{{ addslashes($err) }}</li>@endforeach</ul>`;
            showAdminToast(errorHtml, 'warning', 'Harap Periksa Input:');
        @endif

        /* =========================================================================
           IN-APP NOTIFICATION SYSTEM: 30-MINUTE BANNER EXPIRY WATCHER
           ========================================================================= */
        const shownBannerAlerts = new Set();
        function checkExpiringBanners() {
            fetch("{{ route('admin.banners.checkExpiring') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.banners && data.banners.length > 0) {
                    data.banners.forEach(b => {
                        const alertKey = `${b.id}_${b.remaining_minutes}`;
                        if (!shownBannerAlerts.has(alertKey)) {
                            shownBannerAlerts.add(alertKey);
                            const msgHtml = `
                                <div class="space-y-2">
                                    <p class="font-semibold text-xs leading-relaxed text-amber-950">
                                        Banner <strong>"${b.title}"</strong> tersisa <strong>${b.remaining_human}</strong> sebelum otomatis hilang dari web!
                                    </p>
                                    <div class="flex items-center gap-2 pt-1">
                                        <button type="button" onclick="quickExtendBanner(${b.id})" class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-[10px] btn-press">
                                            + Perpanjang 1 Jam
                                        </button>
                                        <a href="${b.edit_url}" class="px-2.5 py-1 rounded-lg bg-white border border-amber-300 text-amber-900 font-bold text-[10px] hover:bg-amber-50">
                                            Atur Jadwal &rarr;
                                        </a>
                                    </div>
                                </div>
                            `;
                            showAdminToast(msgHtml, 'warning', 'Peringatan Durasi Banner', 12000);
                        }
                    });
                }
            })
            .catch(() => {});
        }

        function quickExtendBanner(bannerId) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch(`/admin/banner/${bannerId}/perpanjang`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ extend_by: '1_hour' })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showAdminToast(res.message, 'success', 'Durasi Diperpanjang');
                    setTimeout(() => location.reload(), 1200);
                }
            })
            .catch(() => {
                showAdminToast('Gagal memperpanjang durasi banner.', 'error');
            });
        }

        // Start checking after 2.5 seconds, then check every 60 seconds
        setTimeout(checkExpiringBanners, 2500);
        setInterval(checkExpiringBanners, 60000);
    </script>
    <script>
        window.__SERVER_UPLOAD_LIMITS__ = {
            uploadMaxBytes: {{ \App\Support\FileUploadHelper::getServerUploadLimits()['upload_max_filesize_bytes'] }},
            uploadMaxRaw: '{{ \App\Support\FileUploadHelper::getServerUploadLimits()['upload_max_filesize_raw'] }}',
            postMaxBytes: {{ \App\Support\FileUploadHelper::getServerUploadLimits()['post_max_size_bytes'] }},
            postMaxRaw: '{{ \App\Support\FileUploadHelper::getServerUploadLimits()['post_max_size_raw'] }}'
        };
    </script>
    <script src="{{ asset('js/file-upload-validator.js') }}"></script>
    @stack('scripts')
</body>
</html>
