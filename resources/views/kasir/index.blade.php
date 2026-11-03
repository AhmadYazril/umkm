@php
    $firstCategoryKey = $groupedCategories->first()['key'] ?? 'minuman';
    $outOfStockCount = $catalogItems->filter(fn ($i) => ! $i['is_available'] || (int) $i['stock'] <= 0)->count();
    $availableCount = $catalogItems->count() - $outOfStockCount;
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir POS (/kasir) — Nucomu Cafe</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Camera QR Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ink': '#141413',
                        'ink-soft': '#222220',
                        'charcoal': '#363633',
                        'ivory': '#F7F6F2',
                        'stone-warm': '#EFECE6',
                        'stone-deep': '#E4E0D8',
                        'line': '#E4E2DC',
                        'muted': '#6E6D68',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        editorial: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #D5D2C9; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #141413; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .bg-pos-canvas {
            background-color: #F7F6F2;
            background-image: radial-gradient(rgba(20, 20, 19, 0.055) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Staggered Entrance & Micro-Animations */
        @keyframes cardCascade {
            0% {
                opacity: 0;
                transform: translateY(16px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .anim-cascade {
            animation: cardCascade 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* Menu Box ("Kotak tapi engga kotak banget") */
        .kasir-menu-box {
            position: relative;
            overflow: hidden;
            border-radius: 1.35rem;
            transition:
                transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                border-color 0.22s ease,
                background-color 0.22s ease,
                opacity 0.22s ease;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .kasir-menu-box:hover:not(.is-out-of-stock) {
            transform: translateY(-4px);
            box-shadow: 0 18px 34px -10px rgba(20, 20, 19, 0.12), 0 4px 10px -2px rgba(20, 20, 19, 0.05);
            border-color: #141413;
        }
        .kasir-menu-box:active:not(.is-out-of-stock) {
            transform: scale(0.95);
        }
        .kasir-menu-box.is-in-cart {
            border-color: #141413 !important;
            box-shadow: 0 0 0 2px #141413, 0 14px 28px -8px rgba(20, 20, 19, 0.14);
            background-color: #FFFFFF;
        }
        .kasir-menu-box.is-out-of-stock {
            opacity: 0.65;
        }
        .kasir-menu-box.is-out-of-stock .menu-photo {
            filter: grayscale(100%);
        }

        @keyframes boxTapBounce {
            0% { transform: scale(1); }
            35% { transform: scale(0.93); }
            70% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }
        .animate-tap-bounce {
            animation: boxTapBounce 0.36s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes badgeSpringPop {
            0% { transform: scale(0.4); opacity: 0; }
            60% { transform: scale(1.18); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-badge-pop {
            animation: badgeSpringPop 0.32s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .tap-ripple {
            position: absolute;
            border-radius: 9999px;
            background: rgba(20, 20, 19, 0.12);
            transform: scale(0);
            animation: rippleExpand 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            pointer-events: none;
            z-index: 20;
        }
        @keyframes rippleExpand {
            to {
                transform: scale(3.5);
                opacity: 0;
            }
        }

        @keyframes floatPlusOne {
            0% { opacity: 1; transform: translate(-50%, 0) scale(0.85); }
            100% { opacity: 0; transform: translate(-50%, -36px) scale(1.1); }
        }
        .floating-plus-one {
            position: absolute;
            z-index: 30;
            pointer-events: none;
            animation: floatPlusOne 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cartItemSlideIn {
            0% {
                opacity: 0;
                transform: translateX(18px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        .anim-cart-item {
            animation: cartItemSlideIn 0.32s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .anim-row-bump {
            animation: boxTapBounce 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes gentleFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        .anim-float-slow {
            animation: gentleFloat 3.6s ease-in-out infinite;
        }

        .btn-checkout-shimmer {
            position: relative;
            overflow: hidden;
            transition: transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease, background-color 0.2s ease;
        }
        .btn-checkout-shimmer:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px -6px rgba(20, 20, 19, 0.35);
        }
        .btn-checkout-shimmer:not(:disabled):active {
            transform: scale(0.97);
        }
        .btn-checkout-shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 55%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.16), transparent);
            transition: left 0.65s ease;
        }
        .btn-checkout-shimmer:hover::after {
            left: 130%;
        }

        @keyframes modalPop {
            0% { opacity: 0; transform: scale(0.94) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .anim-modal-pop {
            animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-pos-canvas text-ink font-sans min-h-screen flex flex-col antialiased selection:bg-ink selection:text-white">

    {{-- =========================================================================
         TOP BAR: GLASSMORPHIC MONOCHROME POS HEADER
         ========================================================================= --}}
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-xl border-b border-line">
        <div class="max-w-[1640px] mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-3">

            {{-- Brand + Live Clock --}}
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('kasir.index') }}" class="w-10 h-10 rounded-xl bg-ink text-white flex items-center justify-center shrink-0 shadow-sm hover:scale-105 transition-transform">
                    <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu" class="h-5 w-auto object-contain brightness-0 invert">
                </a>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-extrabold tracking-tight text-ink uppercase truncate">Nucomu Kasir</span>
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-[10px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-ink text-white">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            POS
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-muted truncate">
                        <span class="font-semibold text-charcoal truncate">{{ auth()->user()->name ?? 'Kasir Nucomu' }}</span>
                        <span>•</span>
                        <span class="inline-flex items-center gap-1 text-[10px] text-muted font-mono bg-ivory px-2 py-0.5 rounded-lg border border-line" title="Database otomatis tersinkron setiap beberapa detik">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Auto-Sync DB</span>
                        </span>
                        <span>•</span>
                        <span id="kasirLiveClock" class="font-mono text-[10px] text-ink font-semibold">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>

            {{-- Main Workspace Switcher (Menu Kasir | Stok Barang | Scan QR | Pesanan) --}}
            <nav class="flex items-center bg-ivory p-1 rounded-2xl border border-line overflow-x-auto no-scrollbar">
                <button
                    type="button"
                    id="mainTabBtn-pos"
                    onclick="switchKasirMainTab('pos')"
                    class="kasir-main-tab shrink-0 px-3 sm:px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'pos' ? 'bg-ink text-white shadow-sm' : 'text-muted hover:text-ink' }}"
                >
                    <i class="fa-solid fa-border-all text-[11px]"></i>
                    <span>Menu Kasir</span>
                </button>
                <button
                    type="button"
                    id="mainTabBtn-stock"
                    onclick="switchKasirMainTab('stock')"
                    class="kasir-main-tab shrink-0 px-3 sm:px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'stock' ? 'bg-ink text-white shadow-sm' : 'text-muted hover:text-ink' }}"
                >
                    <i class="fa-solid fa-boxes-stacked text-[11px]"></i>
                    <span>Stok Barang</span>
                    <span
                        id="headerStockAlertBadge"
                        class="px-1.5 py-0.5 rounded-md text-[10px] font-mono leading-none {{ $outOfStockCount > 0 ? 'bg-ink text-white' : 'bg-stone-warm text-charcoal' }}"
                    >
                        {{ $outOfStockCount > 0 ? $outOfStockCount . ' Habis' : 'Semua Ada' }}
                    </span>
                </button>
                <button
                    type="button"
                    id="mainTabBtn-scan"
                    onclick="switchKasirMainTab('scan')"
                    class="kasir-main-tab shrink-0 px-3 sm:px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'scan' ? 'bg-ink text-white shadow-sm' : 'text-muted hover:text-ink' }}"
                >
                    <i class="fa-solid fa-qrcode text-[11px]"></i>
                    <span>Scan QR</span>
                </button>
                <button
                    type="button"
                    id="mainTabBtn-orders"
                    onclick="switchKasirMainTab('orders')"
                    class="kasir-main-tab shrink-0 px-3 sm:px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'orders' ? 'bg-ink text-white shadow-sm' : 'text-muted hover:text-ink' }}"
                >
                    <i class="fa-solid fa-receipt text-[11px]"></i>
                    <span class="hidden sm:inline">Pesanan</span>
                    <span id="headerPendingOrdersBadge" class="px-1.5 py-0.5 rounded-md text-[10px] font-mono leading-none {{ $activeTab === 'orders' ? 'bg-white text-ink' : 'bg-ink text-white' }}">
                        {{ $pendingOrdersCount }}
                    </span>
                </button>
            </nav>

            {{-- Right Actions: Stats, Admin Link & Logout (Tombol Sinkron Manual Dihilangkan) --}}
            <div class="flex items-center gap-2 shrink-0">
                <div class="hidden xl:flex items-center gap-3 bg-ivory border border-line rounded-xl px-3.5 py-1.5 text-xs">
                    <div>
                        <span class="text-muted text-[10px] uppercase font-mono">Hari Ini</span>
                        <span id="headerTodayOrdersCount" class="font-mono font-bold text-ink ml-1">{{ $todayOrdersCount }} Trx</span>
                    </div>
                    <span class="w-px h-3.5 bg-line"></span>
                    <div>
                        <span class="text-muted text-[10px] uppercase font-mono">Omzet</span>
                        <span id="headerTodayRevenue" class="font-mono font-bold text-ink ml-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a
                    href="{{ route('admin.dashboard') }}"
                    target="_blank"
                    class="hidden sm:inline-flex px-2.5 py-2 rounded-xl border border-line bg-white hover:bg-ivory text-xs font-bold text-ink transition-all items-center gap-1.5"
                    title="Buka Panel Admin Nucomu"
                >
                    <i class="fa-solid fa-sliders text-[11px]"></i>
                    <span class="hidden lg:inline text-[11px]">Admin</span>
                </a>

                <form action="{{ route('kasir.logout') }}" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-3 py-2 rounded-xl border border-line bg-white hover:bg-ink hover:text-white hover:border-ink text-xs font-bold text-ink transition-all flex items-center gap-1.5 cursor-pointer active:scale-95"
                        title="Keluar & Hapus Cookie Login Kasir"
                    >
                        <i class="fa-solid fa-right-from-bracket text-[11px]"></i>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </header>

    {{-- =========================================================================
         MAIN WORKSPACE CONTAINER
         ========================================================================= --}}
    <main class="flex-1 max-w-[1640px] w-full mx-auto px-4 sm:px-6 py-5">

        {{-- =====================================================================
             TAB 1: POS MENU KASIR + TAGIHAN KASIR
             ===================================================================== --}}
        <section id="kasirMainPanel-pos" class="{{ $activeTab === 'pos' ? '' : 'hidden' }}">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- LEFT COLUMN (7/12 or 8/12): CATEGORIZED MENU BOXES (NO "SEMUA MENU") --}}
                <div class="lg:col-span-7 xl:col-span-8 space-y-5">

                    {{-- Sticky Category Bar (Directly Minuman, Makanan, Snack, Dessert — NO "Semua Menu") --}}
                    <div class="sticky top-[4.5rem] z-30 bg-white/95 backdrop-blur-md border border-line rounded-2xl p-3 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2.5">

                            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-0.5 md:pb-0">
                                @foreach($groupedCategories as $catIdx => $catGroup)
                                    <button
                                        type="button"
                                        onclick="filterKasirCategory('{{ $catGroup['key'] }}')"
                                        data-cat-btn="{{ $catGroup['key'] }}"
                                        class="kasir-cat-pill shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 {{ $catIdx === 0 ? 'bg-ink text-white shadow-sm' : 'bg-ivory text-charcoal hover:bg-stone-warm border border-transparent' }}"
                                    >
                                        <i class="fa-solid {{ $catGroup['icon'] }} text-[11px]"></i>
                                        <span>{{ $catGroup['label'] }}</span>
                                        <span class="font-mono text-[10px] px-1.5 py-0.5 rounded-md {{ $catIdx === 0 ? 'bg-white/20 text-white' : 'bg-white text-charcoal' }}">
                                            {{ $catGroup['items']->count() }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>

                            {{-- Search Menu Input --}}
                            <div class="relative w-full md:w-64 shrink-0">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-muted pointer-events-none">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </span>
                                <input
                                    type="search"
                                    id="kasirMenuSearchInput"
                                    oninput="searchKasirMenus(this.value)"
                                    placeholder="Cari nama menu di kasir..."
                                    class="w-full bg-ivory border border-line rounded-xl pl-9 pr-3.5 py-2 text-xs text-ink placeholder-muted focus:outline-none focus:border-ink focus:bg-white transition-all"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Categorized Menu Sections --}}
                    <div id="kasirCatalogContainer" class="space-y-6">
                        @foreach($groupedCategories as $groupIndex => $catGroup)
                            <div
                                class="kasir-category-section space-y-4 anim-cascade {{ $groupIndex === 0 ? '' : 'hidden' }}"
                                data-category-section="{{ $catGroup['key'] }}"
                            >
                                {{-- Section Header --}}
                                <div class="flex flex-wrap items-center justify-between gap-2 bg-white border border-line rounded-2xl px-4 py-3 shadow-xs">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-ink text-white flex items-center justify-center shadow-xs shrink-0">
                                            <i class="fa-solid {{ $catGroup['icon'] }} text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h2 class="text-sm font-extrabold tracking-tight text-ink uppercase">
                                                    Kategori {{ $catGroup['label'] }}
                                                </h2>
                                                <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-ivory border border-line text-charcoal">
                                                    {{ $catGroup['items']->count() }} Menu
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-muted">{{ $catGroup['description'] }}</p>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        onclick="openStockTabForCategory('{{ $catGroup['key'] }}')"
                                        class="px-3 py-1.5 rounded-xl bg-ivory hover:bg-stone-warm border border-line text-[11px] font-bold text-ink flex items-center gap-1.5 cursor-pointer transition"
                                    >
                                        <i class="fa-solid fa-boxes-stacked text-[10px]"></i>
                                        <span>Kelola Stok {{ $catGroup['label'] }}</span>
                                    </button>
                                </div>

                                {{-- Menu Box Grid: Kotak tapi engga kotak banget (rounded-[1.35rem]), NO PLUS BUTTON --}}
                                <div class="kasir-menu-grid grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3.5">
                                    @foreach($catGroup['items'] as $itemIdx => $menu)
                                        <div
                                            role="button"
                                            tabindex="0"
                                            onclick="kasirTapMenuBox(this, event)"
                                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();kasirTapMenuBox(this, event);}"
                                            data-menu-id="{{ $menu['id'] }}"
                                            data-menu-name="{{ $menu['name'] }}"
                                            data-menu-price="{{ $menu['price'] }}"
                                            data-menu-stock="{{ $menu['is_available'] ? 25 : 0 }}"
                                            data-menu-available="{{ $menu['is_available'] ? '1' : '0' }}"
                                            data-menu-category="{{ $menu['group_key'] }}"
                                            data-menu-subcategory="{{ $menu['sub_category'] }}"
                                            data-menu-image="{{ $menu['image'] }}"
                                            data-search-text="{{ strtolower($menu['name'] . ' ' . $menu['group_label'] . ' ' . $menu['sub_category']) }}"
                                            class="kasir-menu-box {{ ! $menu['is_available'] ? 'is-out-of-stock' : '' }} group text-left bg-white border border-line flex flex-col justify-between cursor-pointer focus:outline-none anim-cascade"
                                            style="animation-delay: {{ min(($itemIdx * 35), 280) }}ms"
                                        >
                                            {{-- Full-Bleed Top Image with Subtle Zoom & Vignette --}}
                                            <div class="relative w-full aspect-[4/3] bg-stone-warm overflow-hidden">
                                                <img
                                                    src="{{ $menu['image'] }}"
                                                    alt="{{ $menu['name'] }}"
                                                    loading="lazy"
                                                    class="menu-photo w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500 ease-out"
                                                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=600';"
                                                >
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-65 group-hover:opacity-45 transition-opacity"></div>

                                                {{-- Top-Left Live Stock Pill (Tersedia vs Habis, Tanpa Jumlah Angka) --}}
                                                <span
                                                    id="menuBoxStockPill-{{ $menu['id'] }}"
                                                    class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold uppercase tracking-wider shadow-sm {{ ! $menu['is_available'] ? 'bg-ink text-white' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}"
                                                >
                                                    {{ ! $menu['is_available'] ? 'HABIS' : 'TERSEDIA' }}
                                                </span>

                                                {{-- Subcategory Pill on Bottom-Left of Photo --}}
                                                <span class="absolute bottom-2.5 left-2.5 px-2.5 py-0.5 rounded-lg bg-black/65 backdrop-blur-md text-white text-[9px] font-mono font-bold uppercase tracking-wider">
                                                    {{ $menu['sub_category'] }}
                                                </span>

                                                {{-- Selected Quantity Badge on Top-Right (Only visible when added to cart, NO + icon) --}}
                                                <span
                                                    id="menuBoxBadge-{{ $menu['id'] }}"
                                                    class="menu-box-qty-badge hidden absolute top-2.5 right-2.5 min-w-[30px] h-[30px] px-2 rounded-xl bg-ink text-white font-mono text-xs font-extrabold items-center justify-center shadow-lg border border-white/25"
                                                >
                                                    1x
                                                </span>
                                            </div>

                                            {{-- Card Body: Menu Name, Price & Direct Habis/Ada Toggle Trigger --}}
                                            <div class="p-3.5 flex-1 flex flex-col justify-between bg-white">
                                                <h3 class="text-xs sm:text-[13px] font-extrabold text-ink leading-snug line-clamp-2 group-hover:text-charcoal transition-colors">
                                                    {{ $menu['name'] }}
                                                </h3>

                                                <div class="mt-2.5 pt-2 border-t border-line/70 flex items-center justify-between gap-1">
                                                    <span class="text-xs sm:text-[13px] font-mono font-extrabold text-ink">
                                                        {{ $menu['price_formatted'] }}
                                                    </span>

                                                    {{-- Direct Habis / Ada Toggle Button --}}
                                                    <button
                                                        type="button"
                                                        id="menuBoxToggleBtn-{{ $menu['id'] }}"
                                                        onclick="toggleMenuStockStatus(event, {{ $menu['id'] }})"
                                                        class="px-2.5 py-1 rounded-lg border text-[10px] font-mono font-bold transition-all flex items-center gap-1 cursor-pointer active:scale-95 {{ $menu['is_available'] ? 'bg-ivory hover:bg-ink hover:text-white border-line text-charcoal' : 'bg-ink text-white border-ink hover:bg-charcoal' }}"
                                                        title="{{ $menu['is_available'] ? 'Klik untuk tandai HABIS' : 'Klik untuk tandai TERSEDIA' }}"
                                                    >
                                                        <i class="fa-solid {{ $menu['is_available'] ? 'fa-ban' : 'fa-check' }} text-[9px]"></i>
                                                        <span>{{ $menu['is_available'] ? 'Habis' : 'Ada' }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Empty Search State --}}
                    <div id="kasirEmptySearchState" class="hidden bg-white border border-line rounded-3xl p-12 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-ivory border border-line flex items-center justify-center mx-auto mb-3 text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h3 class="text-sm font-bold text-ink">Menu Tidak Ditemukan</h3>
                        <p class="text-xs text-muted mt-1">Coba kata kunci pencarian lain.</p>
                        <button type="button" onclick="resetKasirMenuFilter()" class="mt-4 px-4 py-2 rounded-xl bg-ink text-white text-xs font-bold cursor-pointer">
                            Reset Pencarian
                        </button>
                    </div>

                </div>

                {{-- =================================================================
                     RIGHT COLUMN (5/12 or 4/12): "TAGIHAN KASIR" POS DOCK
                     ================================================================= --}}
                <aside class="lg:col-span-5 xl:col-span-4 lg:sticky lg:top-[4.5rem]">
                    <div
                        id="kasirCartPanel"
                        class="bg-white border border-line rounded-[1.75rem] shadow-[0_20px_50px_-15px_rgba(20,20,19,0.08)] overflow-hidden flex flex-col lg:h-[calc(100vh-5.75rem)] lg:max-h-[820px]"
                    >
                        {{-- ZONE 1: DARK ARCHITECTURAL BILL HEADER + COMPACT CUSTOMER BAR --}}
                        <div class="bg-ink text-white p-4 sm:p-5 shrink-0">
                            <div class="flex items-center justify-between gap-2 mb-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-receipt text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h2 class="text-sm font-extrabold tracking-tight uppercase">Tagihan Kasir</h2>
                                            <span id="kasirCartCountBadge" class="px-2 py-0.5 rounded-full bg-white text-ink font-mono text-[10px] font-extrabold">
                                                0 Item
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-white/60 font-mono">Terminal POS Nucomu Cafe</p>
                                    </div>
                                </div>

                                {{-- Order Type Pill Toggle (Dine In / Take Away) --}}
                                <div class="flex items-center bg-white/10 p-1 rounded-xl border border-white/10 text-[11px] font-bold">
                                    <input type="hidden" id="kasirOrderType" value="dine_in">
                                    <button
                                        type="button"
                                        id="orderTypeBtn-dine_in"
                                        onclick="setKasirOrderType('dine_in')"
                                        class="px-2.5 py-1 rounded-lg bg-white text-ink transition-all cursor-pointer"
                                    >
                                        Dine In
                                    </button>
                                    <button
                                        type="button"
                                        id="orderTypeBtn-take_away"
                                        onclick="setKasirOrderType('take_away')"
                                        class="px-2.5 py-1 rounded-lg text-white/70 hover:text-white transition-all cursor-pointer"
                                    >
                                        Take Away
                                    </button>
                                </div>
                            </div>

                            {{-- Compact Single-Row Customer Input (No Table Number) --}}
                            <div class="relative">
                                <i class="fa-regular fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-[10px] text-white/50"></i>
                                <input
                                    type="text"
                                    id="kasirCustomerName"
                                    placeholder="Nama Pelanggan..."
                                    class="w-full bg-white/10 border border-white/15 rounded-xl pl-8 pr-3 py-2 text-xs text-white placeholder-white/45 focus:outline-none focus:bg-white/15 focus:border-white/40 transition"
                                >
                            </div>

                            {{-- Optional Collapsible Order Note --}}
                            <div class="mt-2 flex items-center justify-between">
                                <button
                                    type="button"
                                    onclick="toggleKasirNoteInput()"
                                    class="text-[10px] font-mono text-white/65 hover:text-white inline-flex items-center gap-1.5 cursor-pointer transition"
                                >
                                    <i class="fa-regular fa-note-sticky"></i>
                                    <span id="kasirNoteToggleLabel">+ Tambah Catatan Pesanan</span>
                                </button>
                                <button
                                    type="button"
                                    id="btnClearCart"
                                    onclick="clearKasirCart()"
                                    class="hidden text-[10px] font-mono font-bold text-rose-300 hover:text-rose-200 items-center gap-1 cursor-pointer transition"
                                >
                                    <i class="fa-regular fa-trash-can"></i>
                                    <span>Kosongkan</span>
                                </button>
                            </div>
                            <div id="kasirNoteWrap" class="hidden mt-2">
                                <input
                                    type="text"
                                    id="kasirOrderNotes"
                                    placeholder="Catatan: Less sugar / es pisah / pedas..."
                                    class="w-full bg-white/10 border border-white/15 rounded-xl px-3 py-1.5 text-xs text-white placeholder-white/45 focus:outline-none focus:border-white/40 transition"
                                >
                            </div>
                        </div>

                        {{-- ZONE 2: SCROLLABLE ORDER ITEMS LIST --}}
                        <div class="flex-1 overflow-y-auto px-4 py-3 bg-ivory/50 min-h-[210px]">
                            <div id="kasirCartEmpty" class="h-full py-8 flex flex-col items-center justify-center text-center px-4">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-line shadow-xs flex items-center justify-center text-ink mb-3 anim-float-slow">
                                    <i class="fa-solid fa-mug-hot text-lg"></i>
                                </div>
                                <p class="text-xs font-extrabold text-ink uppercase tracking-wider">Tagihan Masih Kosong</p>
                                <p class="text-[11px] text-muted mt-1 max-w-[230px] leading-relaxed">
                                    Ketuk langsung <span class="font-bold text-ink">kotak menu</span> di sebelah kiri untuk menambahkan pesanan ke tagihan ini.
                                </p>
                            </div>

                            <div id="kasirCartItemsList" class="hidden space-y-2"></div>
                        </div>

                        {{-- ZONE 3: PINNED PAYMENT & CHECKOUT FOOTER --}}
                        <div class="bg-white border-t border-dashed border-stone-deep p-4 sm:p-5 space-y-3.5 shrink-0">

                            {{-- Payment Method Pills + Paid Status Pill --}}
                            <div class="flex items-center justify-between gap-2">
                                <input type="hidden" id="kasirPaymentMethod" value="cash">
                                <div class="grid grid-cols-3 gap-1.5 flex-1 bg-ivory p-1 rounded-xl border border-line">
                                    <button
                                        type="button"
                                        onclick="selectKasirPaymentMethod('cash')"
                                        data-pay-btn="cash"
                                        class="kasir-pay-pill py-1.5 px-2 rounded-lg text-[11px] font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-ink text-white shadow-xs"
                                    >
                                        <i class="fa-solid fa-money-bill-wave text-[10px]"></i>
                                        <span>Tunai</span>
                                    </button>
                                    <button
                                        type="button"
                                        onclick="selectKasirPaymentMethod('qris')"
                                        data-pay-btn="qris"
                                        class="kasir-pay-pill py-1.5 px-2 rounded-lg text-[11px] font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer text-muted hover:text-ink"
                                    >
                                        <i class="fa-solid fa-qrcode text-[10px]"></i>
                                        <span>QRIS</span>
                                    </button>
                                    <button
                                        type="button"
                                        onclick="selectKasirPaymentMethod('transfer')"
                                        data-pay-btn="transfer"
                                        class="kasir-pay-pill py-1.5 px-2 rounded-lg text-[11px] font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer text-muted hover:text-ink"
                                    >
                                        <i class="fa-solid fa-building-columns text-[10px]"></i>
                                        <span>Transfer</span>
                                    </button>
                                </div>

                                <input type="hidden" id="kasirPaymentStatus" value="paid">
                                <button
                                    type="button"
                                    id="kasirPaidStatusBtn"
                                    onclick="toggleKasirPaidStatus()"
                                    class="px-3 py-2 rounded-xl border border-ink bg-ink/5 text-ink text-[11px] font-mono font-bold uppercase tracking-wider transition-all cursor-pointer shrink-0 flex items-center gap-1.5"
                                    title="Klik untuk ubah status Lunas / Belum Bayar"
                                >
                                    <span id="kasirPaidDot" class="w-2 h-2 rounded-full bg-ink"></span>
                                    <span id="kasirPaidLabel">Lunas</span>
                                </button>
                            </div>

                            {{-- Compact Cash Calculator --}}
                            <div id="kasirCashCalculator" class="bg-ivory border border-line rounded-2xl p-3 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="relative flex-1">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-mono font-bold text-muted">Rp</span>
                                        <input
                                            type="number"
                                            id="kasirCashReceived"
                                            min="0"
                                            step="1000"
                                            oninput="calculateKasirChange()"
                                            placeholder="Nominal tunai diterima..."
                                            class="w-full bg-white border border-line rounded-xl pl-8 pr-2.5 py-1.5 text-xs font-mono font-bold text-ink placeholder-muted focus:outline-none focus:border-ink"
                                        >
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button type="button" onclick="setQuickCash('exact')" class="px-2 py-1.5 rounded-lg bg-white border border-line hover:border-ink text-[10px] font-mono font-bold cursor-pointer transition">Pas</button>
                                        <button type="button" onclick="setQuickCash(50000)" class="px-2 py-1.5 rounded-lg bg-white border border-line hover:border-ink text-[10px] font-mono font-bold cursor-pointer transition">50K</button>
                                        <button type="button" onclick="setQuickCash(100000)" class="px-2 py-1.5 rounded-lg bg-white border border-line hover:border-ink text-[10px] font-mono font-bold cursor-pointer transition">100K</button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] px-1">
                                    <span class="text-muted font-medium">Kembalian Tunai</span>
                                    <span id="kasirCashChangeDisplay" class="font-mono font-extrabold text-ink">Rp 0</span>
                                </div>
                            </div>

                            {{-- Total & Checkout Submit Button --}}
                            <div class="pt-1 flex items-center justify-between gap-3">
                                <div>
                                    <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-muted block">Total Tagihan</span>
                                    <span id="kasirGrandTotalDisplay" class="text-xl sm:text-2xl font-mono font-extrabold text-ink tracking-tight">Rp 0</span>
                                </div>

                                <button
                                    type="button"
                                    id="btnSubmitKasirOrder"
                                    onclick="submitKasirOrder()"
                                    disabled
                                    class="flex-1 bg-ink hover:bg-ink-soft disabled:bg-stone-deep disabled:text-muted text-white font-bold text-xs uppercase tracking-wider py-3.5 px-5 rounded-2xl btn-checkout-shimmer flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed"
                                >
                                    <i class="fa-solid fa-print text-xs"></i>
                                    <span id="btnSubmitKasirOrderText">Proses & Cetak Struk</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </aside>

            </div>
        </section>

        {{-- =====================================================================
             TAB 2: KONTROL STOK BARANG KASIR
             ===================================================================== --}}
        <section id="kasirMainPanel-stock" class="{{ $activeTab === 'stock' ? '' : 'hidden' }} space-y-5">

            {{-- Summary Counters: Tersedia vs Habis (Tanpa Jumlah Angka) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-line rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-muted block">Menu Tersedia (Ada)</span>
                        <span id="stockStatAvailable" class="text-2xl font-mono font-extrabold text-emerald-700">{{ $catalogItems->filter(fn ($i) => $i['is_available'] && $i['stock'] > 0)->count() }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
                <div class="bg-white border border-line rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-muted block">Menu Habis (Kosong)</span>
                        <span id="stockStatOut" class="text-2xl font-mono font-extrabold text-ink">{{ $catalogItems->filter(fn ($i) => ! $i['is_available'] || $i['stock'] <= 0)->count() }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-ink text-white flex items-center justify-center">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                </div>
                <div class="bg-white border border-line rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-muted block">Total Menu Terdaftar</span>
                        <span class="text-2xl font-mono font-extrabold text-ink">{{ $catalogItems->count() }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-ivory border border-line flex items-center justify-center text-charcoal">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>

            {{-- Category Switcher for Stock Management (Minuman, Makanan, Snack, Dessert) --}}
            <div class="bg-white border border-line rounded-2xl p-3 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                    @foreach($groupedCategories as $catIdx => $catGroup)
                        <button
                            type="button"
                            onclick="filterStockCategory('{{ $catGroup['key'] }}')"
                            data-stock-cat-btn="{{ $catGroup['key'] }}"
                            class="kasir-stock-cat-pill shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 {{ $catIdx === 0 ? 'bg-ink text-white shadow-sm' : 'bg-ivory text-charcoal hover:bg-stone-warm' }}"
                        >
                            <i class="fa-solid {{ $catGroup['icon'] }} text-[11px]"></i>
                            <span>{{ $catGroup['label'] }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="relative w-full md:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-muted"></i>
                    <input
                        type="search"
                        id="kasirStockSearchInput"
                        oninput="filterStockItems()"
                        placeholder="Cari menu untuk ubah status habis / ada..."
                        class="w-full bg-ivory border border-line rounded-xl pl-9 pr-3.5 py-2 text-xs text-ink placeholder-muted focus:outline-none focus:border-ink"
                    >
                </div>
            </div>

            {{-- Stock Cards per Category (Status Ada vs Habis, Tanpa Input Angka) --}}
            <div class="space-y-6">
                @foreach($groupedCategories as $groupIndex => $catGroup)
                    <div
                        class="kasir-stock-section space-y-3 {{ $groupIndex === 0 ? '' : 'hidden' }}"
                        data-stock-section="{{ $catGroup['key'] }}"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach($catGroup['items'] as $menu)
                                @php
                                    $isAvail = (bool) $menu['is_available'] && (int) $menu['stock'] > 0;
                                @endphp
                                <div
                                    id="stockCard-{{ $menu['id'] }}"
                                    data-stock-search="{{ strtolower($menu['name'] . ' ' . $menu['sub_category']) }}"
                                    class="kasir-stock-card bg-white border border-line rounded-2xl p-4 shadow-xs flex flex-col justify-between gap-4 transition-all"
                                >
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <img
                                                src="{{ $menu['image'] }}"
                                                alt="{{ $menu['name'] }}"
                                                class="w-14 h-14 rounded-xl object-cover bg-stone-warm border border-line shrink-0"
                                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=200';"
                                            >
                                            <div class="min-w-0">
                                                <span class="text-[10px] font-mono uppercase tracking-wider text-muted block">{{ $menu['sub_category'] }}</span>
                                                <h3 class="text-sm font-extrabold text-ink truncate">{{ $menu['name'] }}</h3>
                                                <span class="text-xs font-mono font-bold text-charcoal">{{ $menu['price_formatted'] }}</span>
                                            </div>
                                        </div>

                                        <div class="text-right shrink-0">
                                            <span class="text-[10px] font-mono uppercase text-muted block mb-1">Status</span>
                                            <span
                                                id="stockCardStatus-{{ $menu['id'] }}"
                                                class="inline-block px-2.5 py-1 rounded-lg text-xs font-mono font-bold uppercase tracking-wider {{ $isAvail ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-ink text-white' }}"
                                            >
                                                {{ $isAvail ? 'Tersedia' : 'Habis' }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Quick Action: Pencet Habis / Tersedia --}}
                                    <div class="pt-3 border-t border-line flex items-center justify-between gap-2">
                                        <span id="stockCardDesc-{{ $menu['id'] }}" class="text-xs text-muted truncate">
                                            {{ $isAvail ? 'Siap dipesan kasir & pelanggan' : 'Dinonaktifkan karena habis' }}
                                        </span>
                                        <button
                                            type="button"
                                            id="stockCardActionBtn-{{ $menu['id'] }}"
                                            onclick="toggleMenuStockStatus(event, {{ $menu['id'] }})"
                                            class="shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-mono font-bold transition-all cursor-pointer active:scale-95 flex items-center gap-1.5 {{ $isAvail ? 'bg-ivory hover:bg-ink hover:text-white border border-line text-charcoal' : 'bg-ink text-white hover:bg-charcoal border border-ink' }}"
                                        >
                                            <i class="fa-solid {{ $isAvail ? 'fa-ban' : 'fa-check' }}"></i>
                                            <span>{{ $isAvail ? 'Pencet Habis' : 'Pencet Ada / Tersedia' }}</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- =====================================================================
             TAB 3: SCAN QR KAMERA & CARI KODE PESANAN
             ===================================================================== --}}
        <section id="kasirMainPanel-scan" class="{{ $activeTab === 'scan' ? '' : 'hidden' }}">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <div class="lg:col-span-5 bg-white border border-line rounded-[1.75rem] p-6 shadow-sm space-y-5 anim-cascade">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-ink text-white flex items-center justify-center">
                                <i class="fa-solid fa-camera text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-ink uppercase">Scan QR Pesanan</h2>
                                <p class="text-xs text-muted">Arahkan kamera ke QR pelanggan atau ketik kode</p>
                            </div>
                        </div>
                        <span id="cameraScannerBadge" class="text-[10px] font-mono font-bold uppercase px-2.5 py-1 rounded-lg bg-ivory border border-line text-muted">
                            Siaga
                        </span>
                    </div>

                    <div class="relative rounded-2xl overflow-hidden bg-ink border border-line aspect-[4/3] flex items-center justify-center">
                        <div id="kasirQrReader" class="w-full h-full"></div>
                        <div id="kasirQrPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 text-white/80">
                            <div class="w-14 h-14 rounded-2xl border border-white/20 bg-white/5 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-qrcode text-2xl"></i>
                            </div>
                            <p class="text-xs font-bold">Kamera Scanner QR Kasir</p>
                            <p class="text-[11px] text-white/55 mt-1 max-w-xs">
                                Klik tombol di bawah untuk menyalakan kamera dan memindai QR struk/pesanan pelanggan.
                            </p>
                            <button
                                type="button"
                                onclick="startKasirCameraScanner()"
                                class="mt-4 px-4 py-2.5 rounded-xl bg-white text-ink text-xs font-extrabold uppercase tracking-wider hover:bg-ivory transition cursor-pointer active:scale-95"
                            >
                                <i class="fa-solid fa-video mr-1.5"></i> Aktifkan Kamera QR
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <button
                            type="button"
                            id="btnStopCameraScanner"
                            onclick="stopKasirCameraScanner()"
                            class="hidden w-full py-2.5 rounded-xl border border-line bg-ivory hover:bg-stone-warm text-xs font-bold text-ink transition cursor-pointer"
                        >
                            <i class="fa-solid fa-video-slash mr-1.5"></i> Matikan Kamera
                        </button>
                    </div>

                    <div class="pt-4 border-t border-line">
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-muted mb-2">
                            Cari Manual Kode Pesanan / Tempel Link QR
                        </label>
                        <form onsubmit="handleManualQrLookup(event)" class="flex gap-2">
                            <input
                                type="text"
                                id="manualOrderCodeInput"
                                value="{{ request('code') }}"
                                placeholder="Contoh: NCM-20261102-001"
                                class="flex-1 bg-ivory border border-line rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold text-ink uppercase placeholder-muted focus:outline-none focus:border-ink focus:bg-white"
                            >
                            <button
                                type="submit"
                                class="px-5 py-2.5 rounded-xl bg-ink hover:bg-ink-soft text-white text-xs font-bold uppercase tracking-wider transition cursor-pointer active:scale-95"
                            >
                                Cari
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div id="scannedOrderResultContainer" class="bg-white border border-line rounded-[1.75rem] p-6 shadow-sm min-h-[380px] flex flex-col justify-center">
                        @if($scannedOrder)
                            <div class="space-y-5">
                                <div class="flex flex-wrap items-start justify-between gap-3 pb-4 border-b border-line">
                                    <div>
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-widest px-2.5 py-1 rounded-md bg-ink text-white">
                                            Pesanan Ditemukan
                                        </span>
                                        <h3 class="text-xl font-mono font-extrabold text-ink mt-2">#{{ $scannedOrder->code }}</h3>
                                        <p class="text-xs text-muted mt-0.5">
                                            {{ $scannedOrder->customer_name ?: 'Pelanggan Kasir' }}
                                            • {{ $scannedOrder->order_type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a
                                            href="{{ route('kasir.orders.receipt', $scannedOrder->id) }}"
                                            target="_blank"
                                            class="px-3.5 py-2 rounded-xl bg-ink text-white text-xs font-bold flex items-center gap-1.5"
                                        >
                                            <i class="fa-solid fa-print"></i> Cetak Struk
                                        </a>
                                    </div>
                                </div>

                                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                    @foreach($scannedOrder->items as $item)
                                        <div class="flex items-center justify-between bg-ivory border border-line rounded-xl px-3.5 py-2.5 text-xs">
                                            <div>
                                                <span class="font-bold text-ink">{{ $item->menu_name }}</span>
                                                <span class="font-mono text-muted ml-1.5">x{{ $item->qty }}</span>
                                            </div>
                                            <span class="font-mono font-bold text-ink">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-line">
                                    <span class="text-xs font-bold uppercase text-muted">Total Pembayaran</span>
                                    <span class="text-lg font-mono font-extrabold text-ink">Rp {{ number_format($scannedOrder->total, 0, ',', '.') }}</span>
                                </div>

                                <div class="pt-4 border-t border-line grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                    <button type="button" onclick="updateKasirOrderStatus({{ $scannedOrder->id }}, 'processing', 'paid')" class="py-2.5 px-3 rounded-xl border border-line hover:border-ink text-xs font-bold text-ink cursor-pointer transition">
                                        Set Diproses & Lunas
                                    </button>
                                    <button type="button" onclick="updateKasirOrderStatus({{ $scannedOrder->id }}, 'ready', 'paid')" class="py-2.5 px-3 rounded-xl border border-line hover:border-ink text-xs font-bold text-ink cursor-pointer transition">
                                        Set Siap Disajikan
                                    </button>
                                    <button type="button" onclick="updateKasirOrderStatus({{ $scannedOrder->id }}, 'completed', 'paid')" class="py-2.5 px-3 rounded-xl bg-ink text-white text-xs font-bold cursor-pointer transition">
                                        Selesaikan Pesanan
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10 px-4">
                                <div class="w-14 h-14 rounded-2xl bg-ivory border border-line flex items-center justify-center mx-auto mb-3 text-ink anim-float-slow">
                                    <i class="fa-solid fa-expand text-xl"></i>
                                </div>
                                <h3 class="text-sm font-extrabold text-ink uppercase">Belum Ada QR Dipindai</h3>
                                <p class="text-xs text-muted mt-1 max-w-sm mx-auto">
                                    Scan QR pesanan pelanggan menggunakan kamera di samping atau masukkan kode <span class="font-mono font-semibold text-ink">NCM-...</span> untuk melihat detail & memproses pembayaran.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        {{-- =====================================================================
             TAB 4: RIWAYAT & ANTREAN PESANAN + QR GENERATOR
             ===================================================================== --}}
        <section id="kasirMainPanel-orders" class="{{ $activeTab === 'orders' ? '' : 'hidden' }} space-y-5">

            <div class="bg-white border border-line rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                    <button type="button" onclick="filterKasirOrderStatus('pending')" data-ord-filter="pending" class="kasir-ord-filter px-3.5 py-1.5 rounded-xl text-xs font-bold bg-ink text-white cursor-pointer">Pending</button>
                    <button type="button" onclick="filterKasirOrderStatus('processing')" data-ord-filter="processing" class="kasir-ord-filter px-3.5 py-1.5 rounded-xl text-xs font-bold bg-ivory text-charcoal hover:bg-stone-warm cursor-pointer">Diproses</button>
                    <button type="button" onclick="filterKasirOrderStatus('ready')" data-ord-filter="ready" class="kasir-ord-filter px-3.5 py-1.5 rounded-xl text-xs font-bold bg-ivory text-charcoal hover:bg-stone-warm cursor-pointer">Siap Saji</button>
                    <button type="button" onclick="filterKasirOrderStatus('completed')" data-ord-filter="completed" class="kasir-ord-filter px-3.5 py-1.5 rounded-xl text-xs font-bold bg-ivory text-charcoal hover:bg-stone-warm cursor-pointer">Selesai</button>
                </div>

                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-muted"></i>
                    <input
                        type="search"
                        oninput="searchKasirOrderRows(this.value)"
                        placeholder="Cari kode / nama pelanggan..."
                        class="w-full bg-ivory border border-line rounded-xl pl-9 pr-3.5 py-2 text-xs text-ink placeholder-muted focus:outline-none focus:border-ink"
                    >
                </div>
            </div>

            <div id="kasirOrdersGridContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($recentOrders as $ord)
                    <div
                        class="kasir-order-card bg-white border border-line rounded-2xl p-4 shadow-xs flex flex-col justify-between gap-4 hover:border-ink transition-all"
                        data-order-status="{{ $ord->status }}"
                        data-order-search="{{ strtolower($ord->code . ' ' . $ord->customer_name) }}"
                    >
                        <div>
                            <div class="flex items-start justify-between gap-2 pb-3 border-b border-line">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-extrabold text-ink">#{{ $ord->code }}</span>
                                        <span class="text-[10px] font-mono font-bold uppercase px-2 py-0.5 rounded-md {{ $ord->payment_status === 'paid' ? 'bg-ink text-white' : 'bg-stone-warm text-charcoal' }}">
                                            {{ $ord->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold text-charcoal mt-1">
                                        {{ $ord->customer_name ?: 'Pelanggan Kasir' }}
                                        <span class="font-normal text-muted">• {{ $ord->order_type === 'dine_in' ? 'Dine In' : 'Take Away' }}</span>
                                    </p>
                                </div>
                                <span id="orderCardStatusBadge-{{ $ord->id }}" class="text-[10px] font-mono font-bold uppercase px-2.5 py-1 rounded-lg bg-ivory border border-line text-ink">
                                    {{ $ord->status_label }}
                                </span>
                            </div>

                            <div class="py-3 space-y-1.5 max-h-36 overflow-y-auto text-xs">
                                @foreach($ord->items as $it)
                                    <div class="flex items-center justify-between text-charcoal">
                                        <span class="truncate pr-2">{{ $it->menu_name }} <strong class="font-mono">x{{ $it->qty }}</strong></span>
                                        <span class="font-mono font-semibold shrink-0">Rp {{ number_format($it->line_total, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-3 border-t border-line space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-mono text-muted">{{ $ord->created_at?->format('d M H:i') }}</span>
                                <span class="text-sm font-mono font-extrabold text-ink">Rp {{ number_format($ord->total, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <select
                                    onchange="updateKasirOrderStatus({{ $ord->id }}, this.value, 'paid')"
                                    class="flex-1 bg-ivory border border-line rounded-xl px-2.5 py-2 text-xs font-bold text-ink focus:outline-none focus:border-ink cursor-pointer"
                                >
                                    <option value="pending" @selected($ord->status === 'pending')>Status: Pending</option>
                                    <option value="processing" @selected($ord->status === 'processing')>Status: Diproses</option>
                                    <option value="ready" @selected($ord->status === 'ready')>Status: Siap Saji</option>
                                    <option value="completed" @selected($ord->status === 'completed')>Status: Selesai</option>
                                    <option value="cancelled" @selected($ord->status === 'cancelled')>Status: Batal</option>
                                </select>

                                <a
                                    href="{{ route('kasir.orders.receipt', $ord->id) }}"
                                    target="_blank"
                                    class="px-3.5 py-2 rounded-xl bg-ink text-white text-xs font-bold transition flex items-center gap-1.5"
                                    title="Cetak Struk Thermal 80mm"
                                >
                                    <i class="fa-solid fa-print"></i>
                                    <span>Struk</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border border-line rounded-3xl p-12 text-center">
                        <p class="text-sm font-bold text-ink">Belum Ada Riwayat Pesanan</p>
                        <p class="text-xs text-muted mt-1">Transaksi yang dibuat di kasir akan langsung tampil di sini.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    {{-- =========================================================================
         MOBILE STICKY BOTTOM BILL BAR
         ========================================================================= --}}
    <div id="mobileKasirBottomBar" class="lg:hidden fixed bottom-3 inset-x-4 z-30 pointer-events-none">
        <button
            type="button"
            onclick="scrollToKasirBill()"
            class="w-full bg-ink text-white rounded-2xl p-3.5 shadow-2xl border border-white/15 flex items-center justify-between pointer-events-auto"
        >
            <div class="flex items-center gap-2.5">
                <span id="mobileCartCountBadge" class="w-7 h-7 rounded-lg bg-white text-ink font-mono text-xs font-extrabold flex items-center justify-center">0</span>
                <span class="text-xs font-bold">Lihat Tagihan Kasir</span>
            </div>
            <span id="mobileCartTotalBadge" class="font-mono text-sm font-extrabold">Rp 0</span>
        </button>
    </div>


    {{-- =========================================================================
         MODAL 2: BUKTI TRANSAKSI BERHASIL & CETAK STRUK (TANPA MEMBUAT QR)
         ========================================================================= --}}
    <div id="kasirOrderSuccessModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-line rounded-[2rem] max-w-sm w-full p-6 shadow-2xl anim-modal-pop text-center relative">
            <button
                type="button"
                onclick="closeOrderSuccessModal()"
                class="w-8 h-8 rounded-full bg-ivory border border-line text-ink hover:bg-ink hover:text-white transition flex items-center justify-center absolute top-4 right-4 cursor-pointer"
            >
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>

            <div class="w-14 h-14 rounded-2xl bg-ink text-white flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-check text-xl"></i>
            </div>

            <span class="text-[10px] font-mono font-bold uppercase tracking-widest px-2.5 py-1 rounded-md bg-ivory border border-line text-ink">
                Transaksi Berhasil
            </span>
            <h3 id="modalSuccessOrderCode" class="text-lg font-mono font-extrabold text-ink mt-2">#NCM-000</h3>
            <p id="modalSuccessCustomerName" class="text-xs text-muted">Pelanggan Kasir</p>

            <div class="my-5 bg-ivory border border-line rounded-2xl p-4 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-muted">Total Pembayaran</span>
                    <span id="modalSuccessTotalText" class="font-mono font-extrabold text-ink text-sm">Rp 0</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-line">
                    <span class="text-muted">Kembalian</span>
                    <span id="modalSuccessChangeText" class="font-mono font-bold text-ink">Rp 0</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    id="modalSuccessReceiptBtn"
                    href="#"
                    target="_blank"
                    class="flex-1 py-3 rounded-xl bg-ink text-white text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1.5"
                >
                    <i class="fa-solid fa-print"></i> Cetak Struk
                </a>
                <button
                    type="button"
                    onclick="closeOrderSuccessModal()"
                    class="py-3 px-4 rounded-xl border border-line hover:border-ink text-xs font-bold text-ink cursor-pointer"
                >
                    Selesai
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         FLOATING TOAST NOTIFICATION
         ========================================================================= --}}
    <div
        id="kasirToast"
        class="fixed top-20 right-5 z-50 pointer-events-none opacity-0 -translate-y-2 transition-all duration-300 bg-ink text-white px-4 py-2.5 rounded-2xl shadow-xl border border-white/15 flex items-center gap-2.5 text-xs font-semibold"
    >
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
        <span id="kasirToastText">Menu ditambahkan</span>
    </div>

    {{-- =========================================================================
         KASIR INTERACTIVE JAVASCRIPT ENGINE
         ========================================================================= --}}
    <script>
        const KASIR_STATE = {
            cart: [],
            activeCategory: @json($firstCategoryKey),
            activeStockCategory: @json($firstCategoryKey),
            stocks: {
                @foreach($catalogItems as $ci)
                    {{ $ci['id'] }}: {{ $ci['is_available'] && (int)$ci['stock'] > 0 ? 25 : 0 }},
                @endforeach
            },
            hostIp: localStorage.getItem('nucomu_kasir_host_ip') || @json($host),
            portSuffix: @json($portSuffix),
            scheme: @json($scheme),
            html5QrScanner: null,
            scannerRunning: false,
        };

        setInterval(() => {
            const clockEl = document.getElementById('kasirLiveClock');
            if (clockEl) {
                const now = new Date();
                clockEl.textContent = now.toTimeString().slice(0, 8);
            }
        }, 1000);

        document.addEventListener('DOMContentLoaded', () => {
            const badge = document.getElementById('headerHostIpBadge');
            const input = document.getElementById('kasirHostIpInput');
            if (badge && KASIR_STATE.hostIp) badge.textContent = KASIR_STATE.hostIp;
            if (input && KASIR_STATE.hostIp) input.value = KASIR_STATE.hostIp;
        });

        function formatRupiah(amount) {
            const n = Math.max(0, Math.round(Number(amount) || 0));
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function switchKasirMainTab(tab) {
            ['pos', 'stock', 'scan', 'orders'].forEach(t => {
                const panel = document.getElementById(`kasirMainPanel-${t}`);
                const btn = document.getElementById(`mainTabBtn-${t}`);
                if (panel) panel.classList.toggle('hidden', t !== tab);
                if (btn) {
                    if (t === tab) {
                        btn.className = 'kasir-main-tab shrink-0 px-3 sm:px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer bg-ink text-white shadow-sm';
                    } else {
                        btn.className = 'kasir-main-tab shrink-0 px-3 sm:px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer text-muted hover:text-ink';
                    }
                }
            });

            if (tab !== 'scan' && KASIR_STATE.scannerRunning) {
                stopKasirCameraScanner();
            }
        }

        function openStockTabForCategory(catKey) {
            switchKasirMainTab('stock');
            filterStockCategory(catKey);
        }

        /**
         * Tap Menu Box ("Kotak tapi engga kotak banget") — Checks live stock before adding to bill
         */
        function kasirTapMenuBox(boxEl, event) {
            if (!boxEl) return;

            const id = Number(boxEl.getAttribute('data-menu-id'));
            const name = boxEl.getAttribute('data-menu-name') || 'Menu';
            const price = Number(boxEl.getAttribute('data-menu-price')) || 0;
            const image = boxEl.getAttribute('data-menu-image') || '';
            const isAvailable = Number(KASIR_STATE.stocks[id] ?? (boxEl.getAttribute('data-menu-stock') ?? 25)) > 0;

            if (!isAvailable) {
                showKasirToast(`Menu ${name} sedang HABIS! Silakan pencet tombol Ada terlebih dahulu.`);
                return;
            }

            const existing = KASIR_STATE.cart.find(item => item.id === id);

            if (event) {
                const rect = boxEl.getBoundingClientRect();
                const x = (event.clientX || (rect.left + rect.width / 2)) - rect.left;
                const y = (event.clientY || (rect.top + rect.height / 2)) - rect.top;

                const ripple = document.createElement('span');
                ripple.className = 'tap-ripple';
                const size = Math.max(rect.width, rect.height) * 0.65;
                ripple.style.width = `${size}px`;
                ripple.style.height = `${size}px`;
                ripple.style.left = `${x - size / 2}px`;
                ripple.style.top = `${y - size / 2}px`;
                boxEl.appendChild(ripple);
                setTimeout(() => ripple.remove(), 560);

                const plusOne = document.createElement('span');
                plusOne.className = 'floating-plus-one px-2 py-0.5 rounded-lg bg-ink text-white font-mono text-[11px] font-extrabold shadow-md';
                plusOne.style.left = `${x}px`;
                plusOne.style.top = `${Math.max(24, y - 10)}px`;
                plusOne.textContent = '+1';
                boxEl.appendChild(plusOne);
                setTimeout(() => plusOne.remove(), 650);
            }

            boxEl.classList.remove('animate-tap-bounce');
            void boxEl.offsetWidth;
            boxEl.classList.add('animate-tap-bounce');

            if (existing) {
                existing.qty += 1;
            } else {
                KASIR_STATE.cart.push({ id, name, price, image, qty: 1 });
            }

            renderKasirCart(id);
            showKasirToast(`${name} ditambahkan ke tagihan`);
        }

        function adjustKasirCartQty(menuId, delta) {
            const id = Number(menuId);
            const idx = KASIR_STATE.cart.findIndex(i => i.id === id);
            if (idx === -1) return;

            const isAvailable = Number(KASIR_STATE.stocks[id] ?? 25) > 0;
            if (delta > 0 && !isAvailable) {
                showKasirToast(`Menu ${KASIR_STATE.cart[idx].name} sedang HABIS.`);
                return;
            }

            KASIR_STATE.cart[idx].qty += delta;
            if (KASIR_STATE.cart[idx].qty <= 0) {
                KASIR_STATE.cart.splice(idx, 1);
            }
            renderKasirCart(id);
        }

        function clearKasirCart() {
            KASIR_STATE.cart = [];
            renderKasirCart();
            showKasirToast('Tagihan dikosongkan');
        }

        function renderKasirCart(lastUpdatedId = null) {
            const emptyEl = document.getElementById('kasirCartEmpty');
            const listEl = document.getElementById('kasirCartItemsList');
            const countBadge = document.getElementById('kasirCartCountBadge');
            const clearBtn = document.getElementById('btnClearCart');
            const totalEl = document.getElementById('kasirGrandTotalDisplay');
            const submitBtn = document.getElementById('btnSubmitKasirOrder');
            const mobileCount = document.getElementById('mobileCartCountBadge');
            const mobileTotal = document.getElementById('mobileCartTotalBadge');

            const totalQty = KASIR_STATE.cart.reduce((sum, i) => sum + i.qty, 0);
            const grandTotal = KASIR_STATE.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);

            if (countBadge) countBadge.textContent = `${totalQty} Item`;
            if (totalEl) totalEl.textContent = formatRupiah(grandTotal);
            if (mobileCount) mobileCount.textContent = String(totalQty);
            if (mobileTotal) mobileTotal.textContent = formatRupiah(grandTotal);
            if (submitBtn) submitBtn.disabled = totalQty === 0;
            if (clearBtn) {
                clearBtn.classList.toggle('hidden', totalQty === 0);
                clearBtn.classList.toggle('inline-flex', totalQty > 0);
            }

            document.querySelectorAll('.kasir-menu-box').forEach(box => {
                const boxId = Number(box.getAttribute('data-menu-id'));
                const inCart = KASIR_STATE.cart.find(i => i.id === boxId);
                const badge = document.getElementById(`menuBoxBadge-${boxId}`);

                if (inCart && inCart.qty > 0) {
                    box.classList.add('is-in-cart');
                    if (badge) {
                        badge.textContent = `${inCart.qty}x`;
                        badge.classList.remove('hidden');
                        badge.classList.add('inline-flex');
                        if (lastUpdatedId === boxId) {
                            badge.classList.remove('animate-badge-pop');
                            void badge.offsetWidth;
                            badge.classList.add('animate-badge-pop');
                        }
                    }
                } else {
                    box.classList.remove('is-in-cart');
                    if (badge) {
                        badge.classList.add('hidden');
                        badge.classList.remove('inline-flex');
                    }
                }
            });

            if (KASIR_STATE.cart.length === 0) {
                if (emptyEl) emptyEl.classList.remove('hidden');
                if (listEl) {
                    listEl.classList.add('hidden');
                    listEl.innerHTML = '';
                }
                calculateKasirChange();
                return;
            }

            if (emptyEl) emptyEl.classList.add('hidden');
            if (listEl) {
                listEl.classList.remove('hidden');
                listEl.innerHTML = KASIR_STATE.cart.map(item => {
                    const lineTotal = item.price * item.qty;
                    const animClass = lastUpdatedId === item.id ? 'anim-row-bump border-ink' : 'anim-cart-item border-line';
                    return `
                        <div class="bg-white border ${animClass} rounded-2xl p-2.5 flex items-center justify-between gap-2.5 shadow-xs transition-all">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <img src="${item.image}" alt="${item.name}" class="w-11 h-11 rounded-xl object-cover bg-stone-warm shrink-0 border border-line/60" onerror="this.src='https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=200'">
                                <div class="min-w-0">
                                    <div class="text-xs font-extrabold text-ink truncate">${item.name}</div>
                                    <div class="text-[11px] font-mono text-muted">${formatRupiah(item.price)}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <div class="flex items-center bg-ivory border border-line rounded-xl p-0.5">
                                    <button type="button" onclick="adjustKasirCartQty(${item.id}, -1)" class="w-6 h-6 rounded-lg hover:bg-white text-ink flex items-center justify-center text-xs font-bold cursor-pointer active:scale-90 transition">-</button>
                                    <span class="px-2 font-mono text-xs font-extrabold text-ink">${item.qty}</span>
                                    <button type="button" onclick="adjustKasirCartQty(${item.id}, 1)" class="w-6 h-6 rounded-lg hover:bg-white text-ink flex items-center justify-center text-xs font-bold cursor-pointer active:scale-90 transition">+</button>
                                </div>
                                <div class="w-20 text-right font-mono text-xs font-extrabold text-ink">
                                    ${formatRupiah(lineTotal)}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            calculateKasirChange();
        }

        /**
         * Stock Control Engine (Status Ada vs Habis, Tanpa Jumlah Angka)
         */
        async function toggleMenuStockStatus(event, menuId) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            const id = Number(menuId);
            const currentStock = Number(KASIR_STATE.stocks[id] ?? 25);
            const isCurrentlyAvailable = currentStock > 0;
            const nextAvailable = !isCurrentlyAvailable;

            // Optimistic UI update
            applyStockToDom(id, nextAvailable ? 25 : 0, nextAvailable);

            try {
                const res = await fetch(@json(route('kasir.stock.update')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        menu_id: id,
                        toggle: true,
                        is_available: nextAvailable,
                        stock: nextAvailable ? 25 : 0,
                    }),
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    applyStockToDom(Number(data.menu_id), Number(data.stock), Boolean(data.is_available));
                    showKasirToast(data.message || (nextAvailable ? 'Menu diset TERSEDIA' : 'Menu diset HABIS'));
                } else {
                    applyStockToDom(id, currentStock, isCurrentlyAvailable);
                    showKasirToast('Gagal mengubah status menu');
                }
            } catch (e) {
                applyStockToDom(id, currentStock, isCurrentlyAvailable);
                showKasirToast('Gagal mengubah status menu');
            }
        }

        function applyStockToDom(menuId, stockVal, isAvailableVal = null) {
            const id = Number(menuId);
            const isAvailable = isAvailableVal !== null ? Boolean(isAvailableVal) : (Number(stockVal) > 0);
            const stock = isAvailable ? 25 : 0;
            KASIR_STATE.stocks[id] = stock;

            // 1. Update Menu Box in POS Catalog (Tab 1)
            const box = document.querySelector(`.kasir-menu-box[data-menu-id="${id}"]`);
            const pill = document.getElementById(`menuBoxStockPill-${id}`);
            const toggleBtn = document.getElementById(`menuBoxToggleBtn-${id}`);
            if (box) {
                box.setAttribute('data-menu-stock', String(stock));
                box.classList.toggle('is-out-of-stock', !isAvailable);
            }
            if (pill) {
                pill.textContent = isAvailable ? 'TERSEDIA' : 'HABIS';
                pill.className = isAvailable
                    ? 'absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold uppercase tracking-wider shadow-sm bg-emerald-50 text-emerald-800 border border-emerald-200'
                    : 'absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-lg text-[10px] font-mono font-bold uppercase tracking-wider shadow-sm bg-ink text-white';
            }
            if (toggleBtn) {
                toggleBtn.className = isAvailable
                    ? 'px-2.5 py-1 rounded-lg border text-[10px] font-mono font-bold transition-all flex items-center gap-1 cursor-pointer active:scale-95 bg-ivory hover:bg-ink hover:text-white border-line text-charcoal'
                    : 'px-2.5 py-1 rounded-lg border text-[10px] font-mono font-bold transition-all flex items-center gap-1 cursor-pointer active:scale-95 bg-ink text-white border-ink hover:bg-charcoal';
                toggleBtn.title = isAvailable ? 'Klik untuk tandai HABIS' : 'Klik untuk tandai TERSEDIA';
                toggleBtn.innerHTML = isAvailable
                    ? '<i class="fa-solid fa-ban text-[9px]"></i><span>Habis</span>'
                    : '<i class="fa-solid fa-check text-[9px]"></i><span>Ada</span>';
            }

            // 2. Update Stock Tab Card (Tab 2)
            const cardStatus = document.getElementById(`stockCardStatus-${id}`);
            const cardBtn = document.getElementById(`stockCardActionBtn-${id}`);
            const cardDesc = document.getElementById(`stockCardDesc-${id}`);
            if (cardStatus) {
                cardStatus.textContent = isAvailable ? 'Tersedia' : 'Habis';
                cardStatus.className = isAvailable
                    ? 'inline-block px-2.5 py-1 rounded-lg text-xs font-mono font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200'
                    : 'inline-block px-2.5 py-1 rounded-lg text-xs font-mono font-bold uppercase tracking-wider bg-ink text-white';
            }
            if (cardBtn) {
                cardBtn.className = isAvailable
                    ? 'shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-mono font-bold transition-all cursor-pointer active:scale-95 flex items-center gap-1.5 bg-ivory hover:bg-ink hover:text-white border border-line text-charcoal'
                    : 'shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-mono font-bold transition-all cursor-pointer active:scale-95 flex items-center gap-1.5 bg-ink text-white hover:bg-charcoal border border-ink';
                cardBtn.innerHTML = isAvailable
                    ? '<i class="fa-solid fa-ban"></i><span>Pencet Habis</span>'
                    : '<i class="fa-solid fa-check"></i><span>Pencet Ada / Tersedia</span>';
            }
            if (cardDesc) {
                cardDesc.textContent = isAvailable
                    ? 'Siap dipesan kasir & pelanggan'
                    : 'Dinonaktifkan karena habis';
            }

            // 3. Remove from cart if item is marked out of stock
            if (!isAvailable) {
                const cartIdx = KASIR_STATE.cart.findIndex(i => i.id === id);
                if (cartIdx !== -1) {
                    KASIR_STATE.cart.splice(cartIdx, 1);
                    renderKasirCart();
                }
            }

            // 4. Recalculate summary counters
            const allStocks = Object.values(KASIR_STATE.stocks).map(Number);
            const availCount = allStocks.filter(s => s > 0).length;
            const outCount = allStocks.filter(s => s <= 0).length;

            const statAvail = document.getElementById('stockStatAvailable');
            const statOut = document.getElementById('stockStatOut');
            const headerBadge = document.getElementById('headerStockAlertBadge');

            if (statAvail) statAvail.textContent = String(availCount);
            if (statOut) statOut.textContent = String(outCount);
            if (headerBadge) {
                headerBadge.textContent = outCount > 0 ? `${outCount} Habis` : 'Semua Ada';
                headerBadge.className = outCount > 0
                    ? 'px-1.5 py-0.5 rounded-md text-[10px] font-mono leading-none bg-ink text-white'
                    : 'px-1.5 py-0.5 rounded-md text-[10px] font-mono leading-none bg-stone-warm text-charcoal';
            }
        }

        function filterStockCategory(catKey) {
            KASIR_STATE.activeStockCategory = catKey;

            document.querySelectorAll('.kasir-stock-cat-pill').forEach(btn => {
                const key = btn.getAttribute('data-stock-cat-btn');
                btn.className = key === catKey
                    ? 'kasir-stock-cat-pill shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 bg-ink text-white shadow-sm'
                    : 'kasir-stock-cat-pill shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 bg-ivory text-charcoal hover:bg-stone-warm';
            });

            filterStockItems();
        }

        function filterStockItems() {
            const q = (document.getElementById('kasirStockSearchInput')?.value || '').toLowerCase().trim();

            document.querySelectorAll('.kasir-stock-section').forEach(sec => {
                const secKey = sec.getAttribute('data-stock-section');
                const showSec = q !== '' ? true : secKey === KASIR_STATE.activeStockCategory;

                let visibleCards = 0;
                sec.querySelectorAll('.kasir-stock-card').forEach(card => {
                    const text = card.getAttribute('data-stock-search') || '';
                    const match = showSec && (q === '' || text.includes(q));
                    card.classList.toggle('hidden', !match);
                    if (match) visibleCards++;
                });

                sec.classList.toggle('hidden', visibleCards === 0);
            });
        }

        /**
         * Category & Search Filtering (Strictly Categorized)
         */
        function filterKasirCategory(catKey) {
            KASIR_STATE.activeCategory = catKey;

            document.querySelectorAll('.kasir-cat-pill').forEach(btn => {
                const key = btn.getAttribute('data-cat-btn');
                if (key === catKey) {
                    btn.className = 'kasir-cat-pill shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 bg-ink text-white shadow-sm';
                } else {
                    btn.className = 'kasir-cat-pill shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 bg-ivory text-charcoal hover:bg-stone-warm border border-transparent';
                }
            });

            applyKasirCatalogFilters();
        }

        function searchKasirMenus() {
            applyKasirCatalogFilters();
        }

        function resetKasirMenuFilter() {
            const searchEl = document.getElementById('kasirMenuSearchInput');
            if (searchEl) searchEl.value = '';
            applyKasirCatalogFilters();
        }

        function applyKasirCatalogFilters() {
            const q = (document.getElementById('kasirMenuSearchInput')?.value || '').toLowerCase().trim();
            let totalVisibleBoxes = 0;

            document.querySelectorAll('.kasir-category-section').forEach(section => {
                const secKey = section.getAttribute('data-category-section');
                // When searching by keyword, search across categories; otherwise show only the active category
                const catMatches = q !== '' ? true : KASIR_STATE.activeCategory === secKey;

                let visibleInSection = 0;
                section.querySelectorAll('.kasir-menu-box').forEach(box => {
                    const text = box.getAttribute('data-search-text') || '';
                    const searchMatches = q === '' || text.includes(q);

                    const show = catMatches && searchMatches;
                    box.classList.toggle('hidden', !show);
                    if (show) {
                        visibleInSection++;
                        totalVisibleBoxes++;
                    }
                });

                section.classList.toggle('hidden', visibleInSection === 0);
            });

            const emptyEl = document.getElementById('kasirEmptySearchState');
            if (emptyEl) emptyEl.classList.toggle('hidden', totalVisibleBoxes > 0);
        }

        function setKasirOrderType(type) {
            const input = document.getElementById('kasirOrderType');
            const dineBtn = document.getElementById('orderTypeBtn-dine_in');
            const takeBtn = document.getElementById('orderTypeBtn-take_away');

            if (input) input.value = type;
            if (dineBtn && takeBtn) {
                if (type === 'dine_in') {
                    dineBtn.className = 'px-2.5 py-1 rounded-lg bg-white text-ink transition-all cursor-pointer';
                    takeBtn.className = 'px-2.5 py-1 rounded-lg text-white/70 hover:text-white transition-all cursor-pointer';
                } else {
                    takeBtn.className = 'px-2.5 py-1 rounded-lg bg-white text-ink transition-all cursor-pointer';
                    dineBtn.className = 'px-2.5 py-1 rounded-lg text-white/70 hover:text-white transition-all cursor-pointer';
                }
            }
        }

        function toggleKasirNoteInput() {
            const wrap = document.getElementById('kasirNoteWrap');
            const input = document.getElementById('kasirOrderNotes');
            if (!wrap) return;
            wrap.classList.toggle('hidden');
            if (!wrap.classList.contains('hidden') && input) {
                input.focus();
            }
        }

        function selectKasirPaymentMethod(method) {
            const input = document.getElementById('kasirPaymentMethod');
            const calc = document.getElementById('kasirCashCalculator');
            if (input) input.value = method;

            document.querySelectorAll('.kasir-pay-pill').forEach(btn => {
                const m = btn.getAttribute('data-pay-btn');
                if (m === method) {
                    btn.className = 'kasir-pay-pill py-1.5 px-2 rounded-lg text-[11px] font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-ink text-white shadow-xs';
                } else {
                    btn.className = 'kasir-pay-pill py-1.5 px-2 rounded-lg text-[11px] font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer text-muted hover:text-ink';
                }
            });

            if (calc) {
                calc.classList.toggle('hidden', method !== 'cash');
            }
        }

        function toggleKasirPaidStatus() {
            const input = document.getElementById('kasirPaymentStatus');
            const btn = document.getElementById('kasirPaidStatusBtn');
            const dot = document.getElementById('kasirPaidDot');
            const label = document.getElementById('kasirPaidLabel');
            if (!input || !btn || !dot || !label) return;

            const next = input.value === 'paid' ? 'unpaid' : 'paid';
            input.value = next;

            if (next === 'paid') {
                btn.className = 'px-3 py-2 rounded-xl border border-ink bg-ink/5 text-ink text-[11px] font-mono font-bold uppercase tracking-wider transition-all cursor-pointer shrink-0 flex items-center gap-1.5';
                dot.className = 'w-2 h-2 rounded-full bg-ink';
                label.textContent = 'Lunas';
            } else {
                btn.className = 'px-3 py-2 rounded-xl border border-line bg-ivory text-muted text-[11px] font-mono font-bold uppercase tracking-wider transition-all cursor-pointer shrink-0 flex items-center gap-1.5';
                dot.className = 'w-2 h-2 rounded-full bg-muted';
                label.textContent = 'Belum Bayar';
            }
        }

        function setQuickCash(amount) {
            const input = document.getElementById('kasirCashReceived');
            const grandTotal = KASIR_STATE.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            if (!input) return;

            input.value = amount === 'exact' ? grandTotal : amount;
            calculateKasirChange();
        }

        function calculateKasirChange() {
            const input = document.getElementById('kasirCashReceived');
            const display = document.getElementById('kasirCashChangeDisplay');
            if (!display) return;

            const grandTotal = KASIR_STATE.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            const cash = Number(input?.value || 0);

            if (grandTotal === 0 || cash === 0) {
                display.textContent = 'Rp 0';
                return;
            }

            const diff = cash - grandTotal;
            display.textContent = diff >= 0 ? formatRupiah(diff) : `Kurang ${formatRupiah(Math.abs(diff))}`;
        }

        async function submitKasirOrder() {
            if (KASIR_STATE.cart.length === 0) return;

            const btn = document.getElementById('btnSubmitKasirOrder');
            const btnText = document.getElementById('btnSubmitKasirOrderText');
            if (btn) btn.disabled = true;
            if (btnText) btnText.textContent = 'Menyimpan...';

            const payload = {
                customer_name: document.getElementById('kasirCustomerName')?.value || 'Pelanggan Kasir',
                order_type: document.getElementById('kasirOrderType')?.value || 'dine_in',
                payment_method: document.getElementById('kasirPaymentMethod')?.value || 'cash',
                payment_status: document.getElementById('kasirPaymentStatus')?.value || 'paid',
                cash_received: Number(document.getElementById('kasirCashReceived')?.value || 0),
                notes: document.getElementById('kasirOrderNotes')?.value || '',
                items: KASIR_STATE.cart.map(i => ({
                    menu_id: i.id,
                    menu_name: i.name,
                    unit_price: i.price,
                    qty: i.qty,
                })),
            };

            try {
                const res = await fetch(@json(route('kasir.orders.store')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();
                if (!res.ok || !data.success) {
                    showKasirToast(data.message || 'Gagal menyimpan pesanan');
                    if (btn) btn.disabled = false;
                    if (btnText) btnText.textContent = 'Proses & Cetak Struk';
                    return;
                }

                // Sync deducted stocks returned from server
                if (data.stocks) {
                    Object.entries(data.stocks).forEach(([mid, newStock]) => {
                        applyStockToDom(Number(mid), Number(newStock));
                    });
                }
                if (data.stats) {
                    updateKasirHeaderStats(data.stats);
                }

                KASIR_STATE.cart = [];
                renderKasirCart();
                if (document.getElementById('kasirCustomerName')) document.getElementById('kasirCustomerName').value = '';
                if (document.getElementById('kasirOrderNotes')) document.getElementById('kasirOrderNotes').value = '';
                if (document.getElementById('kasirCashReceived')) document.getElementById('kasirCashReceived').value = '';

                openOrderSuccessModal(
                    data.order.code,
                    data.order.customer_name,
                    data.order.total,
                    data.order.change_amount || 0,
                    data.receipt_url
                );
                showKasirToast(`Pesanan #${data.order.code} berhasil disimpan ke DB & Admin!`);
                syncKasirWithDatabase(false);
            } catch (err) {
                showKasirToast('Terjadi kesalahan jaringan.');
            } finally {
                if (btnText) btnText.textContent = 'Proses & Cetak Struk';
            }
        }

        function openOrderSuccessModal(orderCode, customerName, totalAmount, changeAmount = 0, receiptUrl = null) {
            const modal = document.getElementById('kasirOrderSuccessModal');
            const codeEl = document.getElementById('modalSuccessOrderCode');
            const nameEl = document.getElementById('modalSuccessCustomerName');
            const totalEl = document.getElementById('modalSuccessTotalText');
            const changeEl = document.getElementById('modalSuccessChangeText');
            const receiptBtn = document.getElementById('modalSuccessReceiptBtn');

            if (!modal) return;

            if (codeEl) codeEl.textContent = `#${orderCode}`;
            if (nameEl) nameEl.textContent = customerName || 'Pelanggan Kasir';
            if (totalEl) totalEl.textContent = formatRupiah(totalAmount);
            if (changeEl) changeEl.textContent = formatRupiah(changeAmount);
            if (receiptBtn && receiptUrl) receiptBtn.href = receiptUrl;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeOrderSuccessModal() {
            const modal = document.getElementById('kasirOrderSuccessModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function startKasirCameraScanner() {
            const placeholder = document.getElementById('kasirQrPlaceholder');
            const stopBtn = document.getElementById('btnStopCameraScanner');
            const badge = document.getElementById('cameraScannerBadge');

            if (typeof Html5Qrcode === 'undefined') {
                showKasirToast('Library kamera QR belum siap.');
                return;
            }

            if (placeholder) placeholder.classList.add('hidden');
            if (stopBtn) stopBtn.classList.remove('hidden');
            if (badge) badge.textContent = 'Kamera Aktif';

            KASIR_STATE.html5QrScanner = new Html5Qrcode('kasirQrReader');
            KASIR_STATE.scannerRunning = true;

            KASIR_STATE.html5QrScanner.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 220, height: 220 } },
                (decodedText) => {
                    stopKasirCameraScanner();
                    lookupKasirOrderCode(decodedText);
                },
                () => {}
            ).catch(() => {
                KASIR_STATE.scannerRunning = false;
                if (placeholder) placeholder.classList.remove('hidden');
                if (stopBtn) stopBtn.classList.add('hidden');
                if (badge) badge.textContent = 'Gagal Akses Kamera';
                showKasirToast('Izinkan akses kamera di browser atau gunakan input manual.');
            });
        }

        function stopKasirCameraScanner() {
            const placeholder = document.getElementById('kasirQrPlaceholder');
            const stopBtn = document.getElementById('btnStopCameraScanner');
            const badge = document.getElementById('cameraScannerBadge');

            if (KASIR_STATE.html5QrScanner && KASIR_STATE.scannerRunning) {
                KASIR_STATE.html5QrScanner.stop().catch(() => {}).finally(() => {
                    KASIR_STATE.scannerRunning = false;
                });
            }
            if (placeholder) placeholder.classList.remove('hidden');
            if (stopBtn) stopBtn.classList.add('hidden');
            if (badge) badge.textContent = 'Siaga';
        }

        function handleManualQrLookup(e) {
            e.preventDefault();
            const val = document.getElementById('manualOrderCodeInput')?.value || '';
            lookupKasirOrderCode(val);
        }

        async function lookupKasirOrderCode(rawCode) {
            const code = (rawCode || '').trim();
            if (!code) return;

            try {
                const url = new URL(@json(route('kasir.orders.lookup')), window.location.origin);
                url.searchParams.set('code', code);

                const res = await fetch(url.toString(), {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    showKasirToast(data.message || 'Pesanan tidak ditemukan');
                    return;
                }

                const o = data.order;
                const container = document.getElementById('scannedOrderResultContainer');
                if (!container) return;

                const itemsHtml = (o.items || []).map(i => `
                    <div class="flex items-center justify-between bg-ivory border border-line rounded-xl px-3.5 py-2.5 text-xs">
                        <div>
                            <span class="font-bold text-ink">${i.menu_name}</span>
                            <span class="font-mono text-muted ml-1.5">x${i.qty}</span>
                        </div>
                        <span class="font-mono font-bold text-ink">${formatRupiah(i.line_total)}</span>
                    </div>
                `).join('');

                container.innerHTML = `
                    <div class="space-y-5 anim-cascade">
                        <div class="flex flex-wrap items-start justify-between gap-3 pb-4 border-b border-line">
                            <div>
                                <span class="text-[10px] font-mono font-bold uppercase tracking-widest px-2.5 py-1 rounded-md bg-ink text-white">
                                    Pesanan Ditemukan (${o.status_label})
                                </span>
                                <h3 class="text-xl font-mono font-extrabold text-ink mt-2">#${o.code}</h3>
                                <p class="text-xs text-muted mt-0.5">${o.customer_name || 'Pelanggan Kasir'} • ${o.order_type === 'dine_in' ? 'Dine In' : 'Take Away'}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="${data.receipt_url}" target="_blank" class="px-3.5 py-2 rounded-xl bg-ink text-white text-xs font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-print"></i> Cetak Struk
                                </a>
                            </div>
                        </div>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">${itemsHtml}</div>
                        <div class="flex items-center justify-between pt-3 border-t border-line">
                            <span class="text-xs font-bold uppercase text-muted">Total Pembayaran</span>
                            <span class="text-lg font-mono font-extrabold text-ink">${formatRupiah(o.total)}</span>
                        </div>
                        <div class="pt-4 border-t border-line grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <button type="button" onclick="updateKasirOrderStatus(${o.id}, 'processing', 'paid')" class="py-2.5 px-3 rounded-xl border border-line hover:border-ink text-xs font-bold text-ink cursor-pointer transition">Set Diproses & Lunas</button>
                            <button type="button" onclick="updateKasirOrderStatus(${o.id}, 'ready', 'paid')" class="py-2.5 px-3 rounded-xl border border-line hover:border-ink text-xs font-bold text-ink cursor-pointer transition">Set Siap Saji</button>
                            <button type="button" onclick="updateKasirOrderStatus(${o.id}, 'completed', 'paid')" class="py-2.5 px-3 rounded-xl bg-ink text-white text-xs font-bold cursor-pointer transition">Selesaikan Pesanan</button>
                        </div>
                    </div>
                `;
                showKasirToast(`Pesanan #${o.code} ditemukan`);
            } catch (e) {
                showKasirToast('Gagal memuat detail pesanan');
            }
        }

        async function updateKasirOrderStatus(orderId, status, paymentStatus = 'paid') {
            try {
                const res = await fetch(`/kasir/pesanan/${orderId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ status, payment_status: paymentStatus }),
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    const badge = document.getElementById(`orderCardStatusBadge-${orderId}`);
                    if (badge) badge.textContent = data.order.status_label;
                    if (data.stats) {
                        updateKasirHeaderStats(data.stats);
                    }
                    showKasirToast(data.message || 'Status pesanan diperbarui di DB & Admin');
                    syncKasirWithDatabase(false);
                }
            } catch (e) {
                showKasirToast('Gagal memperbarui status pesanan');
            }
        }

        function updateKasirHeaderStats(stats) {
            if (!stats) return;
            const trxEl = document.getElementById('headerTodayOrdersCount');
            const revEl = document.getElementById('headerTodayRevenue');
            const pendEl = document.getElementById('headerPendingOrdersBadge');

            if (trxEl && typeof stats.today_orders_count !== 'undefined') {
                trxEl.textContent = `${stats.today_orders_count} Trx`;
            }
            if (revEl && typeof stats.today_revenue !== 'undefined') {
                revEl.textContent = formatRupiah(stats.today_revenue);
            }
            if (pendEl && typeof stats.pending_orders_count !== 'undefined') {
                pendEl.textContent = String(stats.pending_orders_count);
            }
        }

        async function syncKasirWithDatabase(showNotification = false) {
            try {
                const res = await fetch(@json(route('kasir.sync')), {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    if (data.stats) {
                        updateKasirHeaderStats(data.stats);
                    }
                    if (data.stocks) {
                        Object.values(data.stocks).forEach(item => {
                            applyStockToDom(Number(item.id), Number(item.stock), Boolean(item.is_available));
                        });
                    }
                    if (showNotification) {
                        showKasirToast('Database & Admin tersinkron otomatis');
                    }
                }
            } catch (err) {
                if (showNotification) {
                    showKasirToast('Gagal menyinkronkan data');
                }
            }
        }

        // Automatic DB sync (runs silently every 8 seconds, on focus, and on tab visibility)
        setInterval(() => {
            syncKasirWithDatabase(false);
        }, 8000);

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                syncKasirWithDatabase(false);
            }
        });
        window.addEventListener('focus', () => {
            syncKasirWithDatabase(false);
        });

        function filterKasirOrderStatus(status) {
            document.querySelectorAll('.kasir-ord-filter').forEach(btn => {
                const st = btn.getAttribute('data-ord-filter');
                btn.className = st === status
                    ? 'kasir-ord-filter px-3.5 py-1.5 rounded-xl text-xs font-bold bg-ink text-white cursor-pointer'
                    : 'kasir-ord-filter px-3.5 py-1.5 rounded-xl text-xs font-bold bg-ivory text-charcoal hover:bg-stone-warm cursor-pointer';
            });

            document.querySelectorAll('.kasir-order-card').forEach(card => {
                const st = card.getAttribute('data-order-status');
                card.classList.toggle('hidden', st !== status);
            });
        }

        function searchKasirOrderRows(q) {
            const query = (q || '').toLowerCase().trim();
            document.querySelectorAll('.kasir-order-card').forEach(card => {
                const text = card.getAttribute('data-order-search') || '';
                card.classList.toggle('hidden', query !== '' && !text.includes(query));
            });
        }

        function scrollToKasirBill() {
            switchKasirMainTab('pos');
            const bill = document.getElementById('kasirCartPanel');
            if (bill) {
                bill.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function showKasirToast(message) {
            const toast = document.getElementById('kasirToast');
            const text = document.getElementById('kasirToastText');
            if (!toast || !text) return;

            text.textContent = message;
            toast.classList.remove('opacity-0', '-translate-y-2');
            clearTimeout(window.kasirToastTimeout);
            window.kasirToastTimeout = setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-2');
            }, 2200);
        }
    </script>
</body>
</html>
