@php
    $firstCategoryKey = $groupedCategories->first()['key'] ?? 'minuman';
    $bestSellerItems = $bestSellerItems ?? collect();
    $minumanGroup = $groupedCategories->firstWhere('key', 'minuman');
    $defaultCategoryKey = $minumanGroup['key'] ?? $firstCategoryKey;
    $initialCatalogTab = $bestSellerItems->count() >= 4 ? 'terlaris' : $defaultCategoryKey;

    $outOfStockCount = $catalogItems->filter(fn ($i) => ! $i['is_available'] || (int) $i['stock'] <= 0)->count();
    $availableCount = $catalogItems->count() - $outOfStockCount;

    $catalogSections = collect([
        [
            'key' => 'terlaris',
            'tab_label' => 'Terlaris',
            'heading' => 'Menu Terlaris',
            'items' => $bestSellerItems,
        ],
    ])->merge(
        $groupedCategories->map(fn ($catGroup) => [
            'key' => $catGroup['key'],
            'tab_label' => $catGroup['label'],
            'heading' => 'Kategori ' . $catGroup['label'],
            'items' => $catGroup['items'],
        ])
    )->values();

    $catalogJsMap = $catalogItems->mapWithKeys(fn ($item) => [
        (string) $item['id'] => [
            'id' => (int) $item['id'],
            'name' => $item['name'],
            'price' => (int) $item['price'],
            'image' => $item['image'],
            'group_key' => $item['group_key'],
            'sub_category' => $item['sub_category'],
            'is_available' => (bool) $item['is_available'] && (int) $item['stock'] > 0,
            'options' => $item['options'] ?? [],
        ],
    ])->all();
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Camera QR / 1D Barcode Scanner & Offline Code 128 Renderer --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="{{ asset('js/nucomu-barcode.js') }}"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'canvas': '#F6F7F5',
                        'surface': '#FFFFFF',
                        'ink': '#1B1F1D',
                        'sub': '#555D58',
                        'muted': '#7A847E',
                        'line': '#E6E8E4',
                        'forest': '#2F6B4F',
                        'forest-dark': '#24543D',
                        'forest-soft': '#E6F0EA',
                        'danger': '#C0392B',
                        'danger-soft': '#FDEEEB',
                        'warning': '#D9A441',
                        'warning-soft': '#FEF7EA',
                        'warning-ink': '#8A6200',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        html, body, button, input, select, textarea {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            font-variant-numeric: lining-nums tabular-nums;
            -webkit-font-smoothing: antialiased;
        }

        .tabular-nums {
            font-variant-numeric: lining-nums tabular-nums;
        }

        .fa, .fas, .far, .fa-solid, .fa-regular {
            font-family: "Font Awesome 6 Free" !important;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #D4D8D2; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #2F6B4F; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible {
            outline: 2px solid #2F6B4F;
            outline-offset: 2px;
        }

        .kasir-menu-box {
            position: relative;
            overflow: hidden;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: transform 0.14s ease, border-color 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
        }
        .kasir-menu-box:active:not(.is-out-of-stock) {
            transform: scale(0.98);
        }
        .kasir-menu-box.is-in-cart {
            border-color: #2F6B4F !important;
            box-shadow: 0 0 0 2px #E6F0EA;
        }
        .kasir-menu-box.is-out-of-stock {
            opacity: 0.45;
            background-color: #F6F7F5;
            cursor: not-allowed;
        }
        .kasir-menu-box.is-out-of-stock .menu-photo {
            filter: grayscale(100%);
        }

        @keyframes sheetSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-sheet-up {
            animation: sheetSlideUp 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.97) translateY(6px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-modal-pop {
            animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-canvas text-ink font-sans font-normal text-sm min-h-screen flex flex-col antialiased selection:bg-forest selection:text-white">

    {{-- =========================================================================
         TOP BAR (1 baris ~56px di Tablet/Desktop, tanpa teks & ikon dekoratif)
         ========================================================================= --}}
    <header class="sticky top-0 z-40 bg-white border-b border-line">
        <div class="max-w-[1680px] mx-auto px-3.5 sm:px-5 h-14 flex items-center justify-between gap-2 sm:gap-4">

            {{-- Kiri: Logo + Titik Status Sinkron + "Nucomu Kasir" --}}
            <div class="flex items-center justify-between gap-2 min-w-0 w-full sm:w-auto">
                <a href="{{ route('kasir.index') }}" class="flex items-center gap-2.5 min-w-0">
                    <span class="w-8 h-8 rounded-lg bg-forest text-white flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu" class="h-4 w-auto object-contain brightness-0 invert">
                    </span>
                    <span class="relative flex items-center gap-2 min-w-0">
                        <span class="text-sm font-bold text-ink truncate">Nucomu Kasir</span>
                        <span id="kasirSyncDot" class="w-2 h-2 rounded-full bg-forest shrink-0" title="Status sinkronisasi database"></span>
                        <span id="kasirSyncErrorLabel" class="hidden text-xs font-bold text-danger shrink-0">Offline</span>
                    </span>
                </a>

                {{-- Aksi Kanan Versi HP --}}
                <div class="flex sm:hidden items-center gap-1.5 shrink-0">
                    <button
                        type="button"
                        onclick="toggleKasirMoreMenu(event)"
                        aria-label="Menu lainnya"
                        class="w-9 h-9 rounded-xl bg-canvas border border-line text-sm font-bold text-sub flex items-center justify-center cursor-pointer active:scale-95"
                    >
                        &#8943;
                    </button>
                    <form action="{{ route('kasir.logout') }}" method="POST" class="inline">
                        @csrf
                        <button
                            type="submit"
                            class="h-9 px-3 rounded-xl bg-canvas border border-line text-xs font-bold text-sub cursor-pointer active:scale-95"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tengah: 4 Tab di Tablet/Desktop (di HP pindah ke Bottom Navigation Bar) --}}
            <nav aria-label="Menu Utama Kasir" class="hidden sm:flex sm:items-center gap-2 shrink-0">
                <button
                    type="button"
                    id="mainTabBtn-pos"
                    onclick="switchKasirMainTab('pos')"
                    class="kasir-main-tab order-1 sm:order-1 min-h-[44px] px-3.5 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 cursor-pointer {{ $activeTab === 'pos' ? 'bg-forest text-white' : 'text-sub bg-canvas sm:bg-transparent' }}"
                >
                    <span>Menu Kasir</span>
                </button>

                <button
                    type="button"
                    id="mainTabBtn-stock"
                    onclick="switchKasirMainTab('stock')"
                    class="kasir-main-tab order-3 sm:order-2 min-h-[44px] px-3.5 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 cursor-pointer {{ $activeTab === 'stock' ? 'bg-forest text-white' : 'text-sub bg-canvas sm:bg-transparent' }}"
                >
                    <span>Stok Barang</span>
                    <span
                        id="headerStockAlertBadge"
                        class="{{ $outOfStockCount > 0 ? 'inline-flex' : 'hidden' }} px-1.5 py-0.5 rounded-md text-xs font-bold tabular-nums leading-none bg-danger text-white"
                    >
                        {{ $outOfStockCount }}
                    </span>
                </button>

                <button
                    type="button"
                    id="mainTabBtn-scan"
                    onclick="switchKasirMainTab('scan')"
                    class="kasir-main-tab order-2 sm:order-3 min-h-[44px] px-3.5 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 cursor-pointer {{ $activeTab === 'scan' ? 'bg-forest text-white' : 'text-sub bg-canvas sm:bg-transparent' }}"
                >
                    <span>Scan QR</span>
                </button>

                <button
                    type="button"
                    id="mainTabBtn-orders"
                    onclick="switchKasirMainTab('orders')"
                    class="kasir-main-tab order-4 sm:order-4 min-h-[44px] px-3.5 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 cursor-pointer {{ $activeTab === 'orders' ? 'bg-forest text-white' : 'text-sub bg-canvas sm:bg-transparent' }}"
                >
                    <span>Pesanan</span>
                    <span
                        id="headerPendingOrdersBadge"
                        class="{{ $pendingOrdersCount > 0 ? 'inline-flex' : 'hidden' }} px-1.5 py-0.5 rounded-md text-xs font-bold tabular-nums leading-none bg-warning-soft text-warning-ink"
                    >
                        {{ $pendingOrdersCount }}
                    </span>
                </button>
            </nav>

            {{-- Kanan: Hanya Tombol "⋯" (Menu Admin & Ringkasan Hari Ini) dan "Keluar" --}}
            <div class="hidden sm:flex items-center gap-2 shrink-0 relative">
                <button
                    type="button"
                    id="kasirMoreMenuBtn"
                    onclick="toggleKasirMoreMenu(event)"
                    aria-label="Ringkasan hari ini dan tautan Admin"
                    class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl text-sm font-bold text-sub flex items-center justify-center cursor-pointer"
                >
                    &#8943;
                </button>

                <form action="{{ route('kasir.logout') }}" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="min-h-[44px] px-3.5 rounded-xl text-xs font-bold text-sub cursor-pointer"
                    >
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- Dropdown Menu "⋯": Ringkasan Hari Ini + Panel Admin --}}
        <div
            id="kasirMoreDropdown"
            class="hidden fixed top-14 right-4 z-50 w-64 bg-white border border-line rounded-xl shadow-lg p-4 space-y-4"
        >
            <div class="space-y-2">
                <div class="text-xs font-normal text-muted">Ringkasan Hari Ini</div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-sub font-normal">Transaksi</span>
                    <span id="headerTodayOrdersCount" class="font-bold text-ink tabular-nums">{{ $todayOrdersCount }} Trx</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-sub font-normal">Omzet</span>
                    <span id="headerTodayRevenue" class="font-bold text-forest-dark tabular-nums">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="pt-3 border-t border-line">
                <a
                    href="{{ route('admin.dashboard') }}"
                    target="_blank"
                    class="min-h-[44px] w-full px-3 rounded-xl bg-canvas text-xs font-bold text-ink flex items-center justify-between"
                >
                    <span>Buka Panel Admin</span>
                </a>
            </div>
        </div>
    </header>

    {{-- =========================================================================
         MAIN WORKSPACE CONTAINER
         ========================================================================= --}}
    <main class="flex-1 w-full max-w-[1680px] mx-auto px-4 sm:px-5 py-4">

        {{-- =====================================================================
             TAB 1: MENU KASIR (Kiri ~62% Scroll Katalog, Kanan ~38% Tagihan Diam)
             ===================================================================== --}}
        <section id="kasirMainPanel-pos" class="{{ $activeTab === 'pos' ? '' : 'hidden' }} lg:h-[calc(100vh-5.5rem)] lg:overflow-hidden">
            <div class="h-full grid grid-cols-1 lg:grid-cols-[minmax(0,62fr)_minmax(0,38fr)] gap-4 lg:gap-5 items-stretch">

                {{-- KOLOM KIRI (~62%): Pencarian + Chip Kategori + Grid Kartu Menu --}}
                <div class="flex flex-col min-w-0 lg:h-full lg:overflow-hidden">

                    {{-- Kolom Pencarian Satu Baris (Tanpa kotak pembungkus tambahan) --}}
                    <div class="shrink-0 space-y-4 pb-4">
                        <div class="relative">
                            <input
                                type="search"
                                id="kasirMenuSearchInput"
                                oninput="searchKasirMenus(this.value)"
                                placeholder="Cari menu..."
                                class="w-full min-h-[46px] bg-white border border-line rounded-xl pl-4 pr-12 py-2 text-sm font-normal text-ink placeholder-muted focus:outline-none focus:border-forest"
                            >
                            <button
                                type="button"
                                id="kasirSearchClearBtn"
                                onclick="resetKasirMenuFilter()"
                                aria-label="Hapus pencarian"
                                class="hidden absolute right-1 top-1/2 -translate-y-1/2 w-11 h-11 rounded-xl text-sub items-center justify-center cursor-pointer"
                            >
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>

                        {{-- Chip Kategori: Teks saja (tanpa ikon & tanpa angka) --}}
                        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                            @foreach($catalogSections as $section)
                                @php
                                    $isCatActive = $section['key'] === $initialCatalogTab;
                                @endphp
                                <button
                                    type="button"
                                    onclick="filterKasirCategory('{{ $section['key'] }}')"
                                    data-cat-btn="{{ $section['key'] }}"
                                    class="kasir-cat-pill shrink-0 min-h-[44px] px-4 rounded-xl text-xs transition-colors cursor-pointer flex items-center justify-center {{ $isCatActive ? 'bg-forest text-white border border-forest font-bold' : 'bg-white text-sub border border-line font-normal' }}"
                                >
                                    {{ $section['tab_label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Grid Menu yang Bisa Di-Scroll --}}
                    <div id="kasirCatalogScrollArea" class="lg:flex-1 lg:overflow-y-auto pb-36 sm:pb-28 lg:pb-4 lg:pr-1 space-y-4">
                        <div id="kasirCatalogContainer" class="space-y-4">
                            @foreach($catalogSections as $section)
                                <div
                                    class="kasir-category-section {{ $section['key'] === $initialCatalogTab ? '' : 'hidden' }}"
                                    data-category-section="{{ $section['key'] }}"
                                >
                                    {{-- Penanda semantik kategori (tanpa tampilan ramai) --}}
                                    <h2 class="sr-only">{{ $section['heading'] }}</h2>

                                    @if($section['items']->isEmpty())
                                        <div class="bg-white border border-line rounded-xl p-8 text-center">
                                            <p class="text-sm font-bold text-ink">Belum ada menu di kategori ini</p>
                                            <p class="text-xs font-normal text-sub mt-1">Pilih tab kategori lain di atas.</p>
                                        </div>
                                    @else
                                        <div class="kasir-menu-grid grid grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-2.5 sm:gap-4">
                                            @foreach($section['items'] as $menu)
                                                @php
                                                    $isAvail = (bool) $menu['is_available'] && (int) $menu['stock'] > 0;
                                                    $hasOptions = ! empty($menu['options']) && count($menu['options']) > 0;
                                                @endphp
                                                <button
                                                    type="button"
                                                    onclick="kasirTapMenuBox(this, event)"
                                                    data-menu-id="{{ $menu['id'] }}"
                                                    data-menu-name="{{ $menu['name'] }}"
                                                    data-menu-price="{{ $menu['price'] }}"
                                                    data-menu-stock="{{ $isAvail ? 25 : 0 }}"
                                                    data-menu-available="{{ $isAvail ? '1' : '0' }}"
                                                    data-menu-category="{{ $menu['group_key'] }}"
                                                    data-menu-subcategory="{{ $menu['sub_category'] }}"
                                                    data-menu-image="{{ $menu['image'] }}"
                                                    data-search-text="{{ strtolower($menu['name'] . ' ' . $menu['group_label'] . ' ' . $menu['sub_category']) }}"
                                                    aria-disabled="{{ $isAvail ? 'false' : 'true' }}"
                                                    class="kasir-menu-box {{ ! $isAvail ? 'is-out-of-stock' : '' }} text-left bg-white border border-line rounded-xl flex flex-col justify-between cursor-pointer"
                                                >
                                                    {{-- Foto Menu Rasio 4:3 --}}
                                                    <div class="relative w-full aspect-[4/3] bg-canvas overflow-hidden">
                                                        <img
                                                            src="{{ $menu['image'] }}"
                                                            alt="{{ $menu['name'] }}"
                                                            loading="lazy"
                                                            class="menu-photo w-full h-full object-cover object-center"
                                                            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=600';"
                                                        >

                                                        {{-- Badge Merah Habis (Hanya muncul saat stok habis) --}}
                                                        <span
                                                            data-stock-pill="{{ $menu['id'] }}"
                                                            class="{{ ! $isAvail ? 'inline-flex' : 'hidden' }} absolute top-2 left-2 px-2 py-0.5 rounded-md bg-danger text-white text-xs font-bold"
                                                        >
                                                            Habis
                                                        </span>

                                                        {{-- Badge Jumlah di Keranjang --}}
                                                        <span
                                                            data-qty-badge="{{ $menu['id'] }}"
                                                            class="menu-box-qty-badge hidden absolute top-2 right-2 min-w-[28px] h-7 px-2 rounded-lg bg-forest text-white text-xs font-bold tabular-nums items-center justify-center"
                                                        >
                                                            1x
                                                        </span>
                                                    </div>

                                                    {{-- Nama Menu & Harga Menjadi Fokus Utama --}}
                                                    <div class="p-2.5 sm:p-3 flex-1 w-full flex flex-col justify-between gap-1.5 sm:gap-2 bg-white">
                                                        <h3 class="text-xs sm:text-sm font-bold text-ink leading-snug line-clamp-2">
                                                            {{ $menu['name'] }}
                                                        </h3>

                                                        <div class="flex items-baseline justify-between gap-1.5 sm:gap-2">
                                                            <span class="text-xs sm:text-sm font-bold text-forest-dark tabular-nums">
                                                                {{ $menu['price_formatted'] }}
                                                            </span>
                                                            @if($hasOptions)
                                                                <span class="text-[10.5px] sm:text-xs font-normal text-muted shrink-0">+ pilihan</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Empty Search State --}}
                        <div id="kasirEmptySearchState" class="hidden bg-white border border-line rounded-xl p-8 text-center space-y-3">
                            <p class="text-sm font-bold text-ink">Menu tidak ditemukan</p>
                            <p class="text-xs font-normal text-sub">Coba kata kunci lain yang lebih singkat.</p>
                            <button
                                type="button"
                                onclick="resetKasirMenuFilter()"
                                class="min-h-[44px] px-5 rounded-xl bg-forest text-white text-xs font-bold cursor-pointer"
                            >
                                Hapus Pencarian
                            </button>
                        </div>
                    </div>
                </div>

                {{-- =================================================================
                     KOLOM KANAN (~38% Tablet Landscape): TAGIHAN DIAM
                     HP PORTRAIT: Drawer Bottom Sheet
                     ================================================================= --}}
                <aside
                    id="kasirCartAside"
                    onclick="handleCartBackdropClick(event)"
                    class="hidden lg:block lg:h-full lg:min-h-0"
                >
                    <div
                        id="kasirCartPanel"
                        class="bg-white border border-line rounded-t-2xl lg:rounded-xl overflow-hidden flex flex-col w-full max-h-[90vh] lg:max-h-none lg:h-full"
                    >
                        {{-- Mobile Sheet Top Header with Drag Handle --}}
                        <div class="lg:hidden pt-2 pb-1.5 px-4 border-b border-line bg-white shrink-0">
                            <div class="w-10 h-1 rounded-full bg-line mx-auto mb-1.5"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-ink">Rincian Tagihan Kasir</span>
                                <button
                                    type="button"
                                    onclick="closeMobileKasirDrawer()"
                                    aria-label="Tutup panel tagihan"
                                    class="w-9 h-9 rounded-xl bg-canvas text-ink flex items-center justify-center cursor-pointer"
                                >
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ZONE 1: HEADER TAGIHAN (Tenang, Tanpa Badge & Tanpa Ikon Dekoratif) --}}
                        <div class="p-4 border-b border-line space-y-4 shrink-0">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-baseline gap-2">
                                    <h2 class="text-sm font-bold text-ink">Tagihan</h2>
                                    <span id="kasirCartCountBadge" class="text-xs font-normal text-muted tabular-nums">0 item</span>
                                </div>

                                <button
                                    type="button"
                                    id="btnClearCart"
                                    onclick="confirmClearKasirCart()"
                                    class="hidden min-h-[44px] px-3 rounded-xl text-xs font-bold text-danger items-center cursor-pointer shrink-0"
                                >
                                    Kosongkan
                                </button>
                            </div>

                            {{-- SUMBER TRANSAKSI: Di Tempat (Barcode), Zendo, atau Grab --}}
                            <div class="space-y-2">
                                <label class="block text-xs font-normal text-sub">Sumber transaksi</label>
                                <input type="hidden" id="kasirOrderChannel" value="direct">
                                <div class="grid grid-cols-3 gap-1.5 bg-canvas p-1 rounded-xl">
                                    <button
                                        type="button"
                                        id="channelBtn-direct"
                                        onclick="setKasirOrderChannel('direct')"
                                        class="kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-forest text-white text-xs font-bold transition-all flex flex-col items-center justify-center gap-0.5 cursor-pointer"
                                    >
                                        <span class="flex items-center gap-1 leading-tight">
                                            <i class="fa-solid fa-barcode text-[11px]"></i>
                                            <span>Di Tempat</span>
                                        </span>
                                        <span class="text-[10px] font-normal opacity-90 leading-none">Barcode</span>
                                    </button>

                                    <button
                                        type="button"
                                        id="channelBtn-zendo"
                                        onclick="setKasirOrderChannel('zendo')"
                                        class="kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-white text-ink border border-line text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                    >
                                        <img src="{{ asset('images/logos/zendo-courier.png') }}" alt="Logo Zendo" class="w-6 h-6 rounded-full object-cover shrink-0">
                                        <span>Zendo</span>
                                    </button>

                                    <button
                                        type="button"
                                        id="channelBtn-grab"
                                        onclick="setKasirOrderChannel('grab')"
                                        class="kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-white text-ink border border-line text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                    >
                                        <img src="{{ asset('images/logos/grabfood.png') }}" alt="Logo GrabFood" class="w-6 h-6 rounded-md object-contain bg-white shrink-0">
                                        <span>Grab</span>
                                    </button>
                                </div>

                                {{-- Banner Logo Khusus Mitra (Zendo / GrabFood) --}}
                                <div id="kasirChannelBrandBanner" class="hidden rounded-xl border p-2.5 flex items-center gap-3">
                                    <img id="kasirChannelBannerLogo" src="" alt="Logo Mitra" class="w-10 h-10 rounded-xl object-contain bg-white p-1 border border-line shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span id="kasirChannelBannerTitle" class="text-xs font-bold text-ink">Transaksi Mitra</span>
                                            <span id="kasirChannelBannerPill" class="px-2 py-0.5 rounded-md text-[10px] font-bold">Tanpa Barcode</span>
                                        </div>
                                        <p id="kasirChannelBannerDesc" class="text-[11px] font-normal text-sub leading-snug mt-0.5">
                                            Kurir menyampaikan pesanan langsung ke kasir (input manual).
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Makan di Sini / Bawa Pulang + Format Barcode (Khusus Di Tempat) --}}
                            <div id="kasirDineTypeWrap" class="space-y-2.5">
                                <input type="hidden" id="kasirOrderType" value="dine_in">
                                <div class="grid grid-cols-2 gap-2 bg-canvas p-1 rounded-xl">
                                    <button
                                        type="button"
                                        id="orderTypeBtn-dine_in"
                                        onclick="setKasirOrderType('dine_in')"
                                        class="min-h-[44px] px-3 rounded-lg bg-forest text-white text-xs font-bold transition-colors flex items-center justify-center cursor-pointer"
                                    >
                                        Makan di Sini
                                    </button>
                                    <button
                                        type="button"
                                        id="orderTypeBtn-take_away"
                                        onclick="setKasirOrderType('take_away')"
                                        class="min-h-[44px] px-3 rounded-lg text-sub text-xs font-normal transition-colors flex items-center justify-center cursor-pointer"
                                    >
                                        Bawa Pulang
                                    </button>
                                </div>

                                {{-- Pilihan Format Barcode (QR vs Barcode Bergaris) saat Transaksi --}}
                                <div id="kasirBarcodeTypeWrap" class="flex items-center justify-between gap-2 bg-canvas rounded-xl p-1.5">
                                    <span class="text-[11px] font-normal text-sub pl-2 shrink-0">Format kode</span>
                                    <input type="hidden" id="kasirBarcodeType" value="qr">
                                    <div class="grid grid-cols-2 gap-1 flex-1 max-w-[220px]">
                                        <button
                                            type="button"
                                            id="kasirBarcodeBtn-qr"
                                            onclick="setKasirBarcodeType('qr')"
                                            class="min-h-[34px] px-2.5 rounded-lg bg-forest text-white text-[11px] font-bold flex items-center justify-center gap-1 cursor-pointer transition-colors"
                                        >
                                            <i class="fa-solid fa-qrcode text-[11px]"></i>
                                            <span>QR Code</span>
                                        </button>
                                        <button
                                            type="button"
                                            id="kasirBarcodeBtn-barcode"
                                            onclick="setKasirBarcodeType('barcode')"
                                            class="min-h-[34px] px-2.5 rounded-lg bg-white text-sub border border-line text-[11px] font-normal flex items-center justify-center gap-1 cursor-pointer transition-colors"
                                        >
                                            <i class="fa-solid fa-barcode text-[11px]"></i>
                                            <span>Garis</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Nama Pelanggan & No. Meja Satu Baris Berlabel Jelas --}}
                            <div class="flex items-end gap-3">
                                <div class="flex-1 min-w-0">
                                    <label for="kasirCustomerName" id="kasirCustomerNameLabel" class="block text-xs font-normal text-sub mb-1">Nama pelanggan</label>
                                    <input
                                        type="text"
                                        id="kasirCustomerName"
                                        placeholder="Opsional"
                                        class="w-full min-h-[44px] bg-canvas border border-line rounded-xl px-3 py-2 text-sm font-normal text-ink placeholder-muted focus:outline-none focus:border-forest focus:bg-white"
                                    >
                                </div>

                                <div id="kasirTableNoWrap" class="w-32 sm:w-36 shrink-0">
                                    <label for="kasirTableNo" class="block text-xs font-normal text-sub mb-1">No. meja</label>
                                    <select
                                        id="kasirTableNo"
                                        class="w-full min-h-[44px] bg-canvas border border-line rounded-xl px-3 py-2 text-sm font-normal text-ink focus:outline-none focus:border-forest focus:bg-white cursor-pointer"
                                    >
                                        <option value="">-</option>
                                        @foreach($tables as $tbl)
                                            <option value="{{ $tbl->number }}">Meja {{ $tbl->number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ZONE 2: DAFTAR ITEM KERANJANG --}}
                        <div class="flex-1 overflow-y-auto px-4 py-3 min-h-[140px]">
                            <div id="kasirCartEmpty" class="h-full py-8 flex items-center justify-center text-center">
                                <p class="text-xs font-normal text-muted">Belum ada pesanan</p>
                            </div>

                            <div id="kasirCartItemsList" class="hidden divide-y divide-line"></div>
                        </div>

                        {{-- ZONE 3: BLOK PEMBAYARAN (Tetap untuk Tahap 2), TOTAL PALING BESAR, BAYAR SELEBAR PENUH 56px --}}
                        <div class="bg-white border-t border-line p-4 space-y-4 shrink-0">

                            {{-- Pilihan Metode Bayar & Status (Tahap 2 akan memperluas bagian ini) --}}
                            <div class="flex flex-wrap items-center gap-2">
                                <input type="hidden" id="kasirPaymentMethod" value="cash">
                                <div class="grid grid-cols-3 gap-1.5 flex-1 min-w-[180px] bg-canvas p-1 rounded-xl">
                                    <button
                                        type="button"
                                        onclick="selectKasirPaymentMethod('cash')"
                                        data-pay-btn="cash"
                                        class="kasir-pay-pill min-h-[44px] px-2 rounded-lg text-xs font-bold transition-colors flex items-center justify-center cursor-pointer bg-forest text-white"
                                    >
                                        Tunai
                                    </button>
                                    <button
                                        type="button"
                                        onclick="selectKasirPaymentMethod('qris')"
                                        data-pay-btn="qris"
                                        class="kasir-pay-pill min-h-[44px] px-2 rounded-lg text-xs font-normal transition-colors flex items-center justify-center cursor-pointer text-sub"
                                    >
                                        QRIS
                                    </button>
                                    <button
                                        type="button"
                                        onclick="selectKasirPaymentMethod('transfer')"
                                        data-pay-btn="transfer"
                                        class="kasir-pay-pill min-h-[44px] px-2 rounded-lg text-xs font-normal transition-colors flex items-center justify-center cursor-pointer text-sub"
                                    >
                                        Transfer
                                    </button>
                                </div>

                                <input type="hidden" id="kasirPaymentStatus" value="paid">
                                <button
                                    type="button"
                                    id="kasirPaidStatusBtn"
                                    onclick="toggleKasirPaidStatus()"
                                    class="min-h-[44px] px-3.5 rounded-xl bg-forest-soft text-forest-dark text-xs font-bold transition-colors cursor-pointer shrink-0"
                                >
                                    <span id="kasirPaidLabel">Lunas</span>
                                </button>
                            </div>

                            {{-- Kalkulator Tunai Sementara (Bagian Tahap 2) --}}
                            <div id="kasirCashCalculator" class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-normal text-muted">Rp</span>
                                        <input
                                            type="number"
                                            id="kasirCashReceived"
                                            min="0"
                                            step="1000"
                                            oninput="calculateKasirChange()"
                                            placeholder="Uang diterima"
                                            class="w-full min-h-[44px] bg-canvas border border-line rounded-xl pl-9 pr-3 py-2 text-sm font-bold tabular-nums text-ink placeholder-muted focus:outline-none focus:border-forest focus:bg-white"
                                        >
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" onclick="setQuickCash('exact')" class="min-h-[44px] px-3 rounded-xl bg-canvas text-xs font-bold text-ink cursor-pointer">Pas</button>
                                        <button type="button" onclick="setQuickCash(50000)" class="min-h-[44px] px-3 rounded-xl bg-canvas text-xs font-bold text-ink tabular-nums cursor-pointer">50K</button>
                                        <button type="button" onclick="setQuickCash(100000)" class="min-h-[44px] px-3 rounded-xl bg-canvas text-xs font-bold text-ink tabular-nums cursor-pointer">100K</button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-sub font-normal">Kembalian</span>
                                    <span id="kasirCashChangeDisplay" class="font-bold tabular-nums text-ink">Rp 0</span>
                                </div>
                            </div>

                            {{-- TOTAL PALING BESAR + TOMBOL BAYAR SELEBAR PENUH (56px) --}}
                            <div class="space-y-3 pt-1">
                                <div class="flex items-baseline justify-between gap-3">
                                    <span class="text-xs font-normal text-sub">Total</span>
                                    <span id="kasirGrandTotalDisplay" class="text-2xl font-bold text-ink tabular-nums tracking-tight">
                                        Rp 0
                                    </span>
                                </div>

                                <button
                                    type="button"
                                    id="btnSubmitKasirOrder"
                                    onclick="submitKasirOrder()"
                                    disabled
                                    class="w-full min-h-[56px] h-14 rounded-xl bg-forest hover:bg-forest-dark disabled:bg-line disabled:text-muted text-white font-bold text-sm flex items-center justify-center cursor-pointer disabled:cursor-not-allowed transition-colors active:scale-[0.99]"
                                >
                                    <span id="btnSubmitKasirOrderText">Bayar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </section>

        {{-- =====================================================================
             TAB 2: STOK BARANG (Satu-satunya tempat tombol Habis / Ada berada)
             ===================================================================== --}}
        <section id="kasirMainPanel-stock" class="{{ $activeTab === 'stock' ? '' : 'hidden' }} space-y-4 pb-24 sm:pb-8">

            {{-- Summary Ringkas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-line rounded-xl p-4 flex items-center justify-between">
                    <span class="text-xs font-normal text-sub">Menu Tersedia</span>
                    <span id="stockStatAvailable" class="text-2xl font-bold text-forest-dark tabular-nums">{{ $availableCount }}</span>
                </div>
                <div class="bg-white border border-line rounded-xl p-4 flex items-center justify-between">
                    <span class="text-xs font-normal text-sub">Menu Habis</span>
                    <span id="stockStatOut" class="text-2xl font-bold text-danger tabular-nums">{{ $outOfStockCount }}</span>
                </div>
                <div class="bg-white border border-line rounded-xl p-4 flex items-center justify-between">
                    <span class="text-xs font-normal text-sub">Total Menu</span>
                    <span class="text-2xl font-bold text-ink tabular-nums">{{ $catalogItems->count() }}</span>
                </div>
            </div>

            {{-- Filter Kategori Stok & Pencarian --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                    @foreach($groupedCategories as $catIdx => $catGroup)
                        <button
                            type="button"
                            onclick="filterStockCategory('{{ $catGroup['key'] }}')"
                            data-stock-cat-btn="{{ $catGroup['key'] }}"
                            class="kasir-stock-cat-pill shrink-0 min-h-[44px] px-4 rounded-xl text-xs transition-colors cursor-pointer flex items-center {{ $catIdx === 0 ? 'bg-forest text-white border border-forest font-bold' : 'bg-white text-sub border border-line font-normal' }}"
                        >
                            {{ $catGroup['label'] }}
                        </button>
                    @endforeach
                </div>

                <input
                    type="search"
                    id="kasirStockSearchInput"
                    oninput="filterStockItems()"
                    placeholder="Cari menu stok..."
                    class="w-full md:w-72 min-h-[44px] bg-white border border-line rounded-xl px-4 py-2 text-sm text-ink placeholder-muted focus:outline-none focus:border-forest"
                >
            </div>

            {{-- Stock Cards per Category --}}
            <div class="space-y-4">
                @foreach($groupedCategories as $groupIndex => $catGroup)
                    <div
                        class="kasir-stock-section space-y-4 {{ $groupIndex === 0 ? '' : 'hidden' }}"
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
                                    class="kasir-stock-card bg-white border border-line rounded-xl p-4 flex flex-col justify-between gap-4"
                                >
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <img
                                                src="{{ $menu['image'] }}"
                                                alt="{{ $menu['name'] }}"
                                                class="w-12 h-12 rounded-xl object-cover bg-canvas shrink-0"
                                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=200';"
                                            >
                                            <div class="min-w-0">
                                                <h3 class="text-sm font-bold text-ink leading-snug">{{ $menu['name'] }}</h3>
                                                <span class="text-xs font-bold text-forest-dark tabular-nums">{{ $menu['price_formatted'] }}</span>
                                            </div>
                                        </div>

                                        <span
                                            id="stockCardStatus-{{ $menu['id'] }}"
                                            class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-bold {{ $isAvail ? 'bg-forest-soft text-forest-dark' : 'bg-danger-soft text-danger' }}"
                                        >
                                            {{ $isAvail ? 'Tersedia' : 'Habis' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        <span id="stockCardDesc-{{ $menu['id'] }}" class="text-xs font-normal text-muted">
                                            {{ $isAvail ? 'Bisa dipesan di kasir' : 'Nonaktif sementara' }}
                                        </span>
                                        <button
                                            type="button"
                                            id="stockCardActionBtn-{{ $menu['id'] }}"
                                            onclick="toggleMenuStockStatus(event, {{ $menu['id'] }})"
                                            class="shrink-0 min-h-[44px] px-4 rounded-xl text-xs font-bold transition-colors cursor-pointer active:scale-95 {{ $isAvail ? 'bg-danger-soft text-danger' : 'bg-forest text-white' }}"
                                        >
                                            {{ $isAvail ? 'Tandai Habis' : 'Tandai Ada' }}
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
             TAB 3: SCAN QR & BARCODE (Kamera Live + Foto Kamera HP + Alat Scanner USB/BT)
             ===================================================================== --}}
        <section id="kasirMainPanel-scan" class="{{ $activeTab === 'scan' ? '' : 'hidden' }} pb-24 sm:pb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

                <div class="lg:col-span-5 bg-white border border-line rounded-xl p-4 sm:p-5 space-y-4">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h2 class="text-sm font-bold text-ink">Scan QR &amp; Barcode Pesanan</h2>
                            <p class="text-xs font-normal text-sub">Arahkan kamera, scan dengan alat barcode kasir, atau foto slip</p>
                        </div>
                        <span id="cameraScannerBadge" class="text-xs font-bold px-2.5 py-1 rounded-lg bg-canvas text-sub shrink-0">
                            Siaga
                        </span>
                    </div>

                    {{-- Peringatan Otomatis Saat Dibuka via HTTP IP LAN (Bukan HTTPS / localhost) --}}
                    <div id="kasirCameraHttpWarning" class="hidden rounded-xl border border-warning bg-warning-soft p-3 text-xs text-warning-ink space-y-1.5">
                        <div class="font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Kamera Live Diblokir Browser di HTTP IP LAN</span>
                        </div>
                        <p class="font-normal leading-relaxed text-ink">
                            Browser hanya mengizinkan streaming kamera lewat <strong>HTTPS</strong> atau <strong>localhost</strong>. Saat pakai IP LAN (<code id="kasirCurrentOriginCode" class="font-bold"></code>), Anda tetap bisa scan dengan:
                        </p>
                        <ul class="list-disc pl-4 space-y-0.5 font-normal text-ink">
                            <li><strong>Alat Scanner Barcode USB/BT</strong> langsung tembak barcode garis (otomatis masuk kolom cari).</li>
                            <li><strong>Tombol Foto / Kamera HP</strong> di bawah (jalan normal tanpa HTTPS).</li>
                            <li>Atau buka lewat <code>http://localhost:8000/kasir</code> di laptop host / gunakan HTTPS (ngrok).</li>
                        </ul>
                    </div>

                    <div class="relative rounded-xl overflow-hidden bg-ink aspect-[4/3] flex items-center justify-center">
                        <div id="kasirQrReader" class="w-full h-full"></div>
                        <div id="kasirQrFileReaderHidden" class="hidden"></div>
                        <div id="kasirQrPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 text-white gap-3">
                            <i class="fa-solid fa-camera text-2xl"></i>
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                <button
                                    type="button"
                                    onclick="startKasirCameraScanner()"
                                    class="min-h-[46px] px-4 rounded-xl bg-forest text-white text-xs font-bold transition cursor-pointer"
                                >
                                    Aktifkan Kamera Live
                                </button>
                                <label
                                    for="kasirPhotoScanInput"
                                    class="min-h-[46px] px-4 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white text-xs font-bold transition cursor-pointer inline-flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-image"></i>
                                    <span>Foto / Kamera HP</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <input
                        type="file"
                        id="kasirPhotoScanInput"
                        accept="image/*"
                        capture="environment"
                        onchange="handleKasirPhotoScan(this)"
                        class="hidden"
                    >

                    <div class="flex gap-2">
                        <button
                            type="button"
                            id="btnStopCameraScanner"
                            onclick="stopKasirCameraScanner()"
                            class="hidden flex-1 min-h-[44px] rounded-xl border border-line bg-canvas text-xs font-bold text-ink transition cursor-pointer"
                        >
                            Matikan Kamera
                        </button>
                        <label
                            for="kasirPhotoScanInput"
                            class="flex-1 min-h-[44px] px-3 rounded-xl border border-line bg-canvas hover:bg-forest-soft text-xs font-bold text-ink transition cursor-pointer flex items-center justify-center gap-2"
                        >
                            <i class="fa-solid fa-camera-retro text-forest"></i>
                            <span>Scan via Foto Barcode / QR</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-line space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <label for="manualOrderCodeInput" class="block text-xs font-bold text-ink">
                                Pencarian Pesanan &amp; Alat Barcode Scanner
                            </label>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-forest-soft text-forest-dark text-[10px] font-bold">
                                <i class="fa-solid fa-barcode"></i>
                                <span>Scanner USB/BT Aktif</span>
                            </span>
                        </div>
                        <form onsubmit="handleManualQrLookup(event)" class="flex gap-2">
                            <input
                                type="text"
                                id="manualOrderCodeInput"
                                value="{{ request('code') }}"
                                autocomplete="off"
                                placeholder="Scan barcode garis atau ketik: NCM-20261115-001"
                                class="flex-1 min-h-[48px] bg-canvas border border-line rounded-xl px-3.5 py-2 text-sm font-bold text-ink uppercase placeholder-muted focus:outline-none focus:border-forest focus:bg-white"
                            >
                            <button
                                type="submit"
                                class="min-h-[48px] px-5 rounded-xl bg-forest text-white text-sm font-bold transition cursor-pointer"
                            >
                                Cari
                            </button>
                        </form>
                        <p class="text-[11px] font-normal text-sub">
                            Saat men-scan barcode bergaris memakai alat scan kasir, kode pesanan langsung otomatis tertulis di sini dan menampilkan rincian pesanan.
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div id="scannedOrderResultContainer" class="bg-white border border-line rounded-xl p-5 min-h-[360px] flex flex-col justify-center">
                        @if($scannedOrder)
                            @php
                                $scannedIsZendo = $scannedOrder->isZendo();
                                $scannedServiceBadgeClass = $scannedIsZendo
                                    ? 'bg-sky-50 text-sky-800 border border-sky-200'
                                    : ($scannedOrder->order_type === 'dine_in'
                                        ? 'bg-canvas text-ink border border-line'
                                        : 'bg-stone-100 text-sub border border-line');
                                $scannedServiceIcon = $scannedIsZendo
                                    ? 'fa-motorcycle'
                                    : ($scannedOrder->order_type === 'dine_in' ? 'fa-utensils' : 'fa-bag-shopping');
                            @endphp
                            <div class="space-y-4">
                                <div class="flex flex-wrap items-start justify-between gap-3 pb-4 border-b border-line">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-md {{ $scannedOrder->payment_status === 'paid' ? 'bg-forest-soft text-forest-dark' : 'bg-warning-soft text-warning-ink' }}">
                                                {{ $scannedOrder->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }} ({{ $scannedOrder->status_label }})
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-md {{ $scannedServiceBadgeClass }}">
                                                <i class="fa-solid {{ $scannedServiceIcon }} text-[10px]"></i>
                                                <span>{{ $scannedOrder->service_type_label }}</span>
                                            </span>
                                            @if($scannedOrder->channel === 'zendo')
                                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-md bg-rose-50 text-rose-700 border border-rose-200">
                                                    <img src="{{ asset('images/logos/zendo-courier.png') }}" alt="Zendo" class="w-4 h-4 rounded-full object-cover">
                                                    <span>Zendo</span>
                                                </span>
                                            @elseif($scannedOrder->channel === 'grab')
                                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    <img src="{{ asset('images/logos/grabfood.png') }}" alt="GrabFood" class="w-4 h-4 object-contain">
                                                    <span>GrabFood</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-md bg-canvas text-ink border border-line">
                                                    <i class="fa-solid fa-barcode text-[11px] text-forest"></i>
                                                    <span>Di Tempat (Barcode)</span>
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-sm font-bold text-ink mt-2 tabular-nums">#{{ $scannedOrder->code }}</h3>
                                        <p class="text-xs font-normal text-sub mt-0.5">
                                            {{ $scannedOrder->customer_name ?: 'Pelanggan Kasir' }}
                                            @if($scannedOrder->table)
                                                • Meja {{ $scannedOrder->table->number }}
                                            @endif
                                            @if($scannedOrder->customer_phone && $scannedOrder->customer_phone !== '-')
                                                • {{ $scannedOrder->customer_phone }}
                                            @endif
                                        </p>
                                        @if($scannedIsZendo && $scannedOrder->delivery_address)
                                            <p class="text-xs text-sky-900 bg-sky-50/70 border border-sky-200/80 rounded-lg px-2.5 py-1.5 mt-2">
                                                <i class="fa-solid fa-location-dot text-sky-700 mr-1"></i>{{ $scannedOrder->delivery_address }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a
                                            href="{{ route('kasir.orders.receipt', $scannedOrder->id) }}"
                                            target="_blank"
                                            class="min-h-[44px] px-4 rounded-xl bg-canvas border border-line text-ink text-xs font-bold flex items-center"
                                        >
                                            Cetak Struk
                                        </a>
                                    </div>
                                </div>

                                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                    @foreach($scannedOrder->items as $item)
                                        <div class="flex items-center justify-between bg-canvas rounded-xl px-3.5 py-2.5 text-xs">
                                            <div>
                                                <span class="font-bold text-ink">{{ $item->menu_name }}</span>
                                                <span class="text-sub ml-1.5 tabular-nums">x{{ $item->qty }}</span>
                                            </div>
                                            <span class="font-bold text-ink tabular-nums">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pt-3 border-t border-line space-y-1.5">
                                    @if($scannedOrder->effective_delivery_fee > 0)
                                        <div class="flex items-center justify-between text-xs text-sub">
                                            <span>Subtotal Menu (Bayar ke Kafe)</span>
                                            <span class="font-bold tabular-nums text-ink">Rp {{ number_format($scannedOrder->effective_subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-sky-800">
                                            <span>Ongkir Kurir Zendo</span>
                                            <span class="font-bold tabular-nums">Rp {{ number_format($scannedOrder->effective_delivery_fee, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-xs font-normal text-sub">{{ $scannedIsZendo ? 'Total Pelanggan' : 'Total' }}</span>
                                        <span class="text-2xl font-bold text-ink tabular-nums">Rp {{ number_format($scannedOrder->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                @php
                                    $scannedBucket = match (true) {
                                        $scannedOrder->status === 'cancelled' => 'cancelled',
                                        $scannedOrder->status === 'completed' || (in_array($scannedOrder->status, ['processing', 'ready'], true) && $scannedOrder->payment_status === 'paid') => 'completed',
                                        default => 'unpaid',
                                    };
                                @endphp
                                <div class="pt-4 border-t border-line">
                                    @if($scannedBucket === 'unpaid')
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button type="button" onclick="openPayOrderModal({{ $scannedOrder->id }})" class="flex-1 min-h-[48px] px-4 rounded-xl bg-forest hover:bg-forest-dark text-white text-xs font-bold cursor-pointer">
                                                Bayar &amp; Selesai
                                            </button>
                                            @if($scannedOrder->canBeEditedAtKasir())
                                                <button type="button" onclick="openEditOrderModal({{ $scannedOrder->id }})" class="min-h-[48px] px-4 rounded-xl bg-warning-soft text-warning-ink border border-warning/40 text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer">
                                                    <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                                    <span>Ubah Pesanan</span>
                                                </button>
                                            @endif
                                            <button type="button" onclick="openCancelOrderConfirm({{ $scannedOrder->id }})" class="min-h-[48px] px-4 rounded-xl bg-danger-soft text-danger text-xs font-bold cursor-pointer">
                                                Batalkan
                                            </button>
                                        </div>
                                    @elseif($scannedBucket === 'completed')
                                        <div class="flex items-center justify-between gap-3 rounded-xl bg-forest-soft px-4 py-3 text-xs font-bold text-forest-dark">
                                            <span>Transaksi Selesai • Lunas ({{ $scannedOrder->payment_method_label }})</span>
                                            <a href="{{ route('kasir.orders.receipt', $scannedOrder->id) }}" target="_blank" class="underline">Cetak Struk</a>
                                        </div>
                                    @else
                                        <div class="rounded-xl bg-danger-soft px-4 py-3 text-xs font-bold text-danger">
                                            Pesanan ini sudah dibatalkan.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 px-4 space-y-2">
                                <h3 class="text-sm font-bold text-ink">Belum ada QR / Barcode dipindai</h3>
                                <p class="text-xs font-normal text-sub max-w-sm mx-auto">
                                    Scan QR atau barcode garis pelanggan (via kamera, foto, atau alat scanner kasir) untuk langsung membuka detail pesanan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        {{-- =====================================================================
             TAB 4: PESANAN (Sumber Transaksi + 3 Tab Ringkas: Belum Bayar | Selesai | Batal + Filter Jenis)
             ===================================================================== --}}
        @php
            $kasirCountDirect = $recentOrders->filter(fn ($o) => $o->channel === 'direct')->count();
            $kasirCountZendo = $recentOrders->filter(fn ($o) => $o->channel === 'zendo')->count();
            $kasirCountGrab = $recentOrders->filter(fn ($o) => $o->channel === 'grab')->count();
        @endphp
        <section id="kasirMainPanel-orders" class="{{ $activeTab === 'orders' ? '' : 'hidden' }} space-y-4 pb-24 sm:pb-8">

            {{-- Pemisahan Riwayat Transaksi Berdasarkan Sumber: Di Tempat (Barcode), Zendo, Grab --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5">
                <button
                    type="button"
                    onclick="filterKasirOrderChannel('all')"
                    data-ord-channel-btn="all"
                    class="kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-forest text-white border-forest"
                >
                    <div class="min-w-0">
                        <span class="block text-xs font-bold truncate">Semua Sumber</span>
                        <span class="block text-[10.5px] opacity-85">Gabungan riwayat</span>
                    </div>
                    <span id="kasirChannelCount-all" class="px-2 py-0.5 rounded-lg bg-white/20 text-xs font-bold tabular-nums">{{ $recentOrders->count() }}</span>
                </button>

                <button
                    type="button"
                    onclick="filterKasirOrderChannel('direct')"
                    data-ord-channel-btn="direct"
                    class="kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-white text-ink border-line hover:border-forest"
                >
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-8 h-8 rounded-lg bg-forest-soft text-forest-dark flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-barcode text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <span class="block text-xs font-bold truncate">Di Tempat (Barcode)</span>
                            <span class="block text-[10.5px] text-sub">Langsung / QR &amp; Garis</span>
                        </div>
                    </div>
                    <span id="kasirChannelCount-direct" class="px-2 py-0.5 rounded-lg bg-canvas text-ink text-xs font-bold tabular-nums">{{ $kasirCountDirect }}</span>
                </button>

                <button
                    type="button"
                    onclick="filterKasirOrderChannel('zendo')"
                    data-ord-channel-btn="zendo"
                    class="kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-white text-ink border-line hover:border-rose-400"
                >
                    <div class="flex items-center gap-2 min-w-0">
                        <img src="{{ asset('images/logos/zendo-courier.png') }}" alt="Zendo" class="w-8 h-8 rounded-full object-cover shrink-0 border border-line bg-white">
                        <div class="min-w-0">
                            <span class="block text-xs font-bold truncate">Zendo</span>
                            <span class="block text-[10.5px] text-sub">Kurir WA &bull; Tanpa Barcode</span>
                        </div>
                    </div>
                    <span id="kasirChannelCount-zendo" class="px-2 py-0.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold tabular-nums">{{ $kasirCountZendo }}</span>
                </button>

                <button
                    type="button"
                    onclick="filterKasirOrderChannel('grab')"
                    data-ord-channel-btn="grab"
                    class="kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-white text-ink border-line hover:border-emerald-500"
                >
                    <div class="flex items-center gap-2 min-w-0">
                        <img src="{{ asset('images/logos/grabfood.png') }}" alt="GrabFood" class="w-8 h-8 rounded-lg object-contain shrink-0 border border-line bg-white p-0.5">
                        <div class="min-w-0">
                            <span class="block text-xs font-bold truncate">GrabFood</span>
                            <span class="block text-[10.5px] text-sub">Mitra Grab &bull; Tanpa Barcode</span>
                        </div>
                    </div>
                    <span id="kasirChannelCount-grab" class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-bold tabular-nums">{{ $kasirCountGrab }}</span>
                </button>
            </div>

            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                        <button type="button" onclick="filterKasirOrderStatus('unpaid')" data-ord-filter="unpaid" class="kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-bold bg-forest text-white cursor-pointer shrink-0">Belum Bayar</button>
                        <button type="button" onclick="filterKasirOrderStatus('completed')" data-ord-filter="completed" class="kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0">Selesai</button>
                        <button type="button" onclick="filterKasirOrderStatus('cancelled')" data-ord-filter="cancelled" class="kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0">Batal</button>
                    </div>

                    <input
                        type="search"
                        id="kasirOrderSearchInput"
                        oninput="searchKasirOrderRows(this.value)"
                        placeholder="Cari kode, nama, atau alamat..."
                        class="w-full sm:w-64 min-h-[44px] bg-white border border-line rounded-xl px-4 py-2 text-sm text-ink placeholder-muted focus:outline-none focus:border-forest"
                    >
                </div>

                {{-- Filter Jenis Layanan: Semua / Kurir Zendo / Makan di Sini / Bawa Pulang --}}
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                    <button type="button" onclick="filterKasirOrderService('all')" data-service-filter="all" class="kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-bold bg-ink text-white cursor-pointer shrink-0">Semua Jenis</button>
                    <button type="button" onclick="filterKasirOrderService('zendo')" data-service-filter="zendo" class="kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-normal bg-white text-sky-800 border border-sky-200 cursor-pointer shrink-0 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-motorcycle text-[10px]"></i>
                        <span>Kurir Zendo</span>
                    </button>
                    <button type="button" onclick="filterKasirOrderService('dine_in')" data-service-filter="dine_in" class="kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-utensils text-[10px]"></i>
                        <span>Makan di Sini</span>
                    </button>
                    <button type="button" onclick="filterKasirOrderService('take_away')" data-service-filter="take_away" class="kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-bag-shopping text-[10px]"></i>
                        <span>Bawa Pulang</span>
                    </button>
                </div>
            </div>

            <div id="kasirOrdersGridContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($recentOrders as $ord)
                    @php
                        $ordIsZendo = $ord->isZendo();
                        $ordServiceKey = $ordIsZendo ? 'zendo' : ($ord->order_type === 'dine_in' ? 'dine_in' : 'take_away');
                        $ordBucket = match (true) {
                            $ord->status === 'cancelled' => 'cancelled',
                            $ord->status === 'completed' || (in_array($ord->status, ['processing', 'ready'], true) && $ord->payment_status === 'paid') => 'completed',
                            default => 'unpaid',
                        };
                        $ordServiceBadgeClass = $ordIsZendo
                            ? 'bg-sky-50 text-sky-800 border border-sky-200'
                            : ($ord->order_type === 'dine_in'
                                ? 'bg-canvas text-ink border border-line'
                                : 'bg-stone-100 text-sub border border-line');
                        $ordServiceIcon = $ordIsZendo
                            ? 'fa-motorcycle'
                            : ($ord->order_type === 'dine_in' ? 'fa-utensils' : 'fa-bag-shopping');
                    @endphp
                    <div
                        class="kasir-order-card bg-white border {{ $ordIsZendo ? 'border-sky-200' : 'border-line' }} rounded-xl p-4 flex flex-col justify-between gap-4 {{ $ordBucket === 'unpaid' ? '' : 'hidden' }}"
                        data-order-id="{{ $ord->id }}"
                        data-order-bucket="{{ $ordBucket }}"
                        data-order-status="{{ $ord->status }}"
                        data-order-channel="{{ $ord->channel }}"
                        data-order-service="{{ $ordServiceKey }}"
                        data-order-search="{{ strtolower($ord->code . ' ' . $ord->customer_name . ' ' . $ord->channel_label . ' ' . ($ord->delivery_address ?? '')) }}"
                    >
                        <div>
                            <div class="flex items-start justify-between gap-2 pb-3 border-b border-line">
                                <div class="min-w-0 w-full">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-xs font-bold text-ink tabular-nums">#{{ $ord->code }}</span>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $ordBucket === 'completed' ? 'bg-forest-soft text-forest-dark' : ($ordBucket === 'cancelled' ? 'bg-danger-soft text-danger' : 'bg-warning-soft text-warning-ink') }}">
                                            {{ $ordBucket === 'completed' ? 'Selesai • ' . $ord->payment_method_label : ($ordBucket === 'cancelled' ? 'Batal' : 'Belum Bayar') }}
                                        </span>
                                        @if($ord->channel === 'zendo')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200 text-[10.5px] font-bold">
                                                <img src="{{ asset('images/logos/zendo-courier.png') }}" alt="Zendo" class="w-3.5 h-3.5 rounded-full object-cover">
                                                <span>Zendo</span>
                                            </span>
                                        @elseif($ord->channel === 'grab')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10.5px] font-bold">
                                                <img src="{{ asset('images/logos/grabfood.png') }}" alt="Grab" class="w-3.5 h-3.5 object-contain">
                                                <span>GrabFood</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-canvas text-sub border border-line text-[10.5px] font-bold">
                                                <i class="fa-solid fa-barcode text-[10px] text-forest"></i>
                                                <span>Di Tempat</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md {{ $ordServiceBadgeClass }}">
                                            <i class="fa-solid {{ $ordServiceIcon }} text-[10px]"></i>
                                            <span>{{ $ord->service_type_label }}</span>
                                        </span>
                                        <span class="text-xs font-bold text-ink truncate">
                                            {{ $ord->customer_name ?: 'Pelanggan Kasir' }}
                                            @if($ord->table)
                                                <span class="font-normal text-sub">• Meja {{ $ord->table->number }}</span>
                                            @endif
                                        </span>
                                    </div>
                                    @if($ordBucket === 'unpaid')
                                        <p class="text-[11px] text-sub mt-1">
                                            @if($ordIsZendo)
                                                Pembayaran: <strong class="text-sky-800">Pilih saat kurir tiba</strong>
                                            @else
                                                Pembayaran: <strong class="text-ink">{{ $ord->payment_method_label }}</strong>
                                            @endif
                                        </p>
                                    @endif
                                    @if($ordIsZendo && $ord->delivery_address)
                                        <p class="text-[11px] text-sky-900 bg-sky-50/70 border border-sky-200/70 rounded-lg px-2 py-1 mt-2 line-clamp-2">
                                            <i class="fa-solid fa-location-dot text-sky-700 mr-1"></i>{{ $ord->delivery_address }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="py-3 space-y-1.5 max-h-36 overflow-y-auto text-xs">
                                @foreach($ord->items as $it)
                                    <div class="flex items-center justify-between text-ink">
                                        <span class="pr-2 font-normal">{{ $it->menu_name }} <strong class="font-bold tabular-nums">x{{ $it->qty }}</strong></span>
                                        <span class="font-bold tabular-nums shrink-0">Rp {{ number_format($it->line_total, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-3 border-t border-line space-y-3">
                            <div class="space-y-1">
                                @if($ord->effective_delivery_fee > 0)
                                    <div class="flex items-center justify-between text-[11px] text-sky-800">
                                        <span>Ongkir Kurir Zendo</span>
                                        <span class="font-bold tabular-nums">Rp {{ number_format($ord->effective_delivery_fee, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-normal text-sub tabular-nums">{{ $ord->created_at?->format('d M H:i') }}</span>
                                    <span class="text-sm font-bold text-ink tabular-nums">Rp {{ number_format($ord->total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($ordBucket === 'unpaid')
                                    <button
                                        type="button"
                                        onclick="openPayOrderModal({{ $ord->id }})"
                                        class="flex-1 min-h-[44px] px-3.5 rounded-xl bg-forest hover:bg-forest-dark text-white text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer"
                                    >
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                        <span>Bayar &amp; Selesai</span>
                                    </button>
                                    @if($ord->canBeEditedAtKasir())
                                        <button
                                            type="button"
                                            onclick="openEditOrderModal({{ $ord->id }})"
                                            class="min-h-[44px] px-3 rounded-xl bg-warning-soft text-warning-ink border border-warning/40 text-xs font-bold flex items-center gap-1 cursor-pointer shrink-0"
                                            title="Ubah item pesanan"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                            <span>Ubah</span>
                                        </button>
                                    @endif
                                    <button
                                        type="button"
                                        onclick="openCancelOrderConfirm({{ $ord->id }})"
                                        class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl bg-danger-soft text-danger text-xs font-bold flex items-center justify-center cursor-pointer shrink-0"
                                        title="Batalkan pesanan"
                                        aria-label="Batalkan pesanan"
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @elseif($ordBucket === 'completed')
                                    <a
                                        href="{{ route('kasir.orders.receipt', $ord->id) }}"
                                        target="_blank"
                                        class="flex-1 min-h-[44px] px-3.5 rounded-xl bg-forest text-white text-xs font-bold flex items-center justify-center gap-1.5"
                                    >
                                        <i class="fa-solid fa-print text-[11px]"></i>
                                        <span>Cetak Struk</span>
                                    </a>
                                @else
                                    <span class="flex-1 min-h-[44px] px-3.5 rounded-xl bg-canvas text-muted text-xs font-bold flex items-center justify-center">
                                        Pesanan Dibatalkan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border border-line rounded-xl p-12 text-center">
                        <p class="text-sm font-bold text-ink">Belum ada pesanan</p>
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    {{-- =========================================================================
         HP PORTRAIT BOTTOM BAR: "Lihat pesanan (n item) Rp xx" -> Buka Drawer Bawah
         ========================================================================= --}}
    <div id="mobileKasirBottomBar" class="lg:hidden fixed bottom-[62px] sm:bottom-0 inset-x-0 z-30 px-3 py-2.5 bg-gradient-to-t from-canvas via-canvas/95 to-transparent pointer-events-none {{ $activeTab === 'pos' ? '' : 'hidden' }}">
        <button
            type="button"
            onclick="openMobileKasirDrawer()"
            class="w-full min-h-[50px] bg-forest hover:bg-forest-dark text-white rounded-2xl px-4 shadow-lg flex items-center justify-between gap-3 pointer-events-auto cursor-pointer active:scale-[0.99] transition-transform"
        >
            <span class="flex items-center gap-2 text-xs sm:text-sm font-bold">
                <i class="fa-solid fa-bag-shopping text-xs"></i>
                <span id="mobileCartSummaryLabel">Lihat pesanan (0 item)</span>
            </span>
            <span class="flex items-center gap-1.5 text-xs sm:text-sm font-bold tabular-nums shrink-0">
                <span id="mobileCartTotalBadge">Rp 0</span>
                <i class="fa-solid fa-chevron-up text-[10px] opacity-80"></i>
            </span>
        </button>
    </div>

    {{-- =========================================================================
         MOBILE BOTTOM NAVIGATION BAR (Hanya di HP < 640px)
         Membuat perpindahan Menu Kasir, Scan QR, Stok, dan Pesanan super cepat
         ========================================================================= --}}
    <nav aria-label="Navigasi Bawah Kasir Mobile"
         class="sm:hidden fixed bottom-0 inset-x-0 z-40 h-[62px] bg-white/98 backdrop-blur-md border-t border-line grid grid-cols-4 px-1 select-none shadow-[0_-4px_20px_rgba(27,31,29,0.06)]">
        <button
            type="button"
            id="mobileMainTabBtn-pos"
            onclick="switchKasirMainTab('pos')"
            class="kasir-mobile-nav-tab flex flex-col items-center justify-center gap-0.5 py-1.5 rounded-xl transition-colors cursor-pointer {{ $activeTab === 'pos' ? 'text-forest font-bold' : 'text-sub font-normal opacity-75' }}"
        >
            <i class="fa-solid fa-cash-register text-[15px]"></i>
            <span class="text-[10.5px] leading-tight">Menu Kasir</span>
        </button>

        <button
            type="button"
            id="mobileMainTabBtn-scan"
            onclick="switchKasirMainTab('scan')"
            class="kasir-mobile-nav-tab flex flex-col items-center justify-center gap-0.5 py-1.5 rounded-xl transition-colors cursor-pointer {{ $activeTab === 'scan' ? 'text-forest font-bold' : 'text-sub font-normal opacity-75' }}"
        >
            <i class="fa-solid fa-qrcode text-[15px]"></i>
            <span class="text-[10.5px] leading-tight">Scan QR</span>
        </button>

        <button
            type="button"
            id="mobileMainTabBtn-stock"
            onclick="switchKasirMainTab('stock')"
            class="kasir-mobile-nav-tab relative flex flex-col items-center justify-center gap-0.5 py-1.5 rounded-xl transition-colors cursor-pointer {{ $activeTab === 'stock' ? 'text-forest font-bold' : 'text-sub font-normal opacity-75' }}"
        >
            <div class="relative">
                <i class="fa-solid fa-boxes-stacked text-[15px]"></i>
                <span
                    id="mobileBottomStockBadge"
                    class="{{ $outOfStockCount > 0 ? 'inline-flex' : 'hidden' }} absolute -top-1.5 -right-3 min-w-[15px] h-[15px] px-1 rounded-full text-[9px] font-bold tabular-nums leading-none bg-danger text-white items-center justify-center"
                >
                    {{ $outOfStockCount }}
                </span>
            </div>
            <span class="text-[10.5px] leading-tight">Stok</span>
        </button>

        <button
            type="button"
            id="mobileMainTabBtn-orders"
            onclick="switchKasirMainTab('orders')"
            class="kasir-mobile-nav-tab relative flex flex-col items-center justify-center gap-0.5 py-1.5 rounded-xl transition-colors cursor-pointer {{ $activeTab === 'orders' ? 'text-forest font-bold' : 'text-sub font-normal opacity-75' }}"
        >
            <div class="relative">
                <i class="fa-solid fa-receipt text-[15px]"></i>
                <span
                    id="mobileBottomPendingBadge"
                    class="{{ $pendingOrdersCount > 0 ? 'inline-flex' : 'hidden' }} absolute -top-1.5 -right-3 min-w-[15px] h-[15px] px-1 rounded-full text-[9px] font-bold tabular-nums leading-none bg-warning text-white items-center justify-center"
                >
                    {{ $pendingOrdersCount }}
                </span>
            </div>
            <span class="text-[10.5px] leading-tight">Pesanan</span>
        </button>
    </nav>

    {{-- =========================================================================
         MODAL VARIAN & CATATAN CEPAT MENU (Ringkas & Tenang)
         ========================================================================= --}}
    <div
        id="kasirVariantModal"
        onclick="if (event.target === this) closeKasirVariantModal()"
        class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-black/45 backdrop-blur-[2px] p-0 sm:p-4"
    >
        <div class="bg-white border border-line rounded-t-2xl sm:rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden animate-sheet-up sm:animate-modal-pop flex flex-col max-h-[90vh]">
            <div class="p-4 border-b border-line flex items-center justify-between gap-3 bg-white shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <img id="variantModalImg" src="" alt="" class="w-12 h-12 rounded-xl object-cover bg-canvas shrink-0">
                    <div class="min-w-0">
                        <h3 id="variantModalTitle" class="text-sm font-bold text-ink leading-snug">Nama Menu</h3>
                        <span id="variantModalBasePrice" class="text-sm font-bold text-forest-dark tabular-nums">Rp 0</span>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="closeKasirVariantModal()"
                    aria-label="Tutup jendela varian"
                    class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl text-sub flex items-center justify-center cursor-pointer shrink-0"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div id="variantModalBody" class="p-4 overflow-y-auto space-y-4 flex-1"></div>

            <div class="p-4 border-t border-line bg-white flex items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-2 bg-canvas rounded-xl p-1">
                    <button
                        type="button"
                        onclick="adjustVariantModalQty(-1)"
                        aria-label="Kurangi jumlah"
                        class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-lg bg-white text-ink text-sm font-bold flex items-center justify-center cursor-pointer"
                    >
                        -
                    </button>
                    <span id="variantModalQtyText" class="min-w-[24px] text-center text-sm font-bold text-ink tabular-nums">1</span>
                    <button
                        type="button"
                        onclick="adjustVariantModalQty(1)"
                        aria-label="Tambah jumlah"
                        class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-lg bg-white text-ink text-sm font-bold flex items-center justify-center cursor-pointer"
                    >
                        +
                    </button>
                </div>

                <button
                    type="button"
                    id="variantModalConfirmBtn"
                    onclick="confirmKasirVariantSelection()"
                    class="flex-1 min-h-[56px] px-4 rounded-xl bg-forest hover:bg-forest-dark text-white text-sm font-bold flex items-center justify-center cursor-pointer tabular-nums"
                >
                    <span id="variantModalConfirmText">Tambah • Rp 0</span>
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL KONFIRMASI KOSONGKAN KERANJANG
         ========================================================================= --}}
    <div
        id="kasirConfirmModal"
        onclick="if (event.target === this) closeKasirConfirmModal()"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/45 backdrop-blur-[2px] p-4"
    >
        <div class="bg-white border border-line rounded-2xl max-w-sm w-full p-5 shadow-2xl animate-modal-pop space-y-4">
            <div class="space-y-1">
                <h3 id="confirmModalTitle" class="text-sm font-bold text-ink">Kosongkan Tagihan?</h3>
                <p id="confirmModalDesc" class="text-xs font-normal text-sub leading-relaxed">
                    Seluruh item yang sudah dipilih pada tagihan ini akan dihapus.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button
                    type="button"
                    onclick="closeKasirConfirmModal()"
                    class="min-h-[48px] px-4 rounded-xl bg-canvas text-sm font-bold text-ink cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    id="confirmModalActionBtn"
                    onclick="executeClearKasirCart()"
                    class="min-h-[48px] px-4 rounded-xl bg-danger text-white text-sm font-bold cursor-pointer"
                >
                    Ya, Kosongkan
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL BUKTI TRANSAKSI BERHASIL & CETAK STRUK
         ========================================================================= --}}
    <div id="kasirOrderSuccessModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/45 backdrop-blur-[2px] p-4">
        <div class="bg-white border border-line rounded-2xl max-w-sm w-full p-6 shadow-2xl animate-modal-pop text-center relative space-y-4">
            <button
                type="button"
                onclick="closeOrderSuccessModal()"
                aria-label="Tutup bukti transaksi"
                class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl text-sub flex items-center justify-center absolute top-3 right-3 cursor-pointer"
            >
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <div class="space-y-1 pt-2">
                <span class="text-xs font-bold text-forest-dark block">Transaksi Berhasil</span>
                <h3 id="modalSuccessOrderCode" class="text-2xl font-bold text-ink tabular-nums">#NCM-000</h3>
                <p id="modalSuccessCustomerName" class="text-xs font-normal text-sub">Pelanggan Kasir</p>
            </div>

            {{-- Partner Badge (Zendo / Grab — Tanpa Barcode) --}}
            <div id="modalSuccessPartnerBanner" class="hidden rounded-xl border p-3 flex items-center gap-3 text-left">
                <img id="modalSuccessPartnerLogo" src="" alt="Logo Mitra" class="w-10 h-10 rounded-xl object-contain bg-white p-1 border border-line shrink-0">
                <div class="min-w-0">
                    <span id="modalSuccessPartnerTitle" class="text-xs font-bold text-ink block">Transaksi Mitra</span>
                    <span class="text-[11px] font-normal text-sub block">Input manual kasir &bull; Tanpa barcode</span>
                </div>
            </div>

            {{-- Switchable QR / Barcode Garis (Khusus Transaksi Di Tempat) --}}
            <div id="modalSuccessCodeBox" class="hidden bg-canvas border border-line rounded-xl p-3 space-y-2.5">
                <div class="grid grid-cols-2 gap-1 bg-white p-1 rounded-lg border border-line">
                    <button
                        type="button"
                        id="modalSuccessCodeBtn-qr"
                        onclick="switchSuccessModalCodeMode('qr')"
                        class="py-1.5 rounded-md text-[11px] font-bold bg-forest text-white cursor-pointer flex items-center justify-center gap-1"
                    >
                        <i class="fa-solid fa-qrcode"></i>
                        <span>QR Code</span>
                    </button>
                    <button
                        type="button"
                        id="modalSuccessCodeBtn-barcode"
                        onclick="switchSuccessModalCodeMode('barcode')"
                        class="py-1.5 rounded-md text-[11px] font-bold text-sub cursor-pointer flex items-center justify-center gap-1"
                    >
                        <i class="fa-solid fa-barcode"></i>
                        <span>Barcode Garis</span>
                    </button>
                </div>
                <div id="modalSuccessQrWrap" class="bg-white rounded-lg p-3 flex items-center justify-center">
                    <div id="modalSuccessQrTarget"></div>
                </div>
                <div id="modalSuccessBarcodeWrap" class="hidden bg-white rounded-lg p-2.5">
                    <div id="modalSuccessBarcodeTarget"></div>
                </div>
            </div>

            <div class="bg-canvas rounded-xl p-4 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-sub font-normal">Total</span>
                    <span id="modalSuccessTotalText" class="font-bold text-ink text-sm tabular-nums">Rp 0</span>
                </div>
                <div id="modalSuccessChangeRow" class="flex items-center justify-between pt-2 border-t border-line">
                    <span class="text-sub font-normal">Kembalian</span>
                    <span id="modalSuccessChangeText" class="font-bold text-forest-dark text-sm tabular-nums">Rp 0</span>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a
                    id="modalSuccessReceiptBtn"
                    href="#"
                    target="_blank"
                    class="flex-1 min-h-[48px] px-4 rounded-xl bg-canvas text-ink text-xs font-bold flex items-center justify-center"
                >
                    Cetak Struk
                </a>
                <button
                    type="button"
                    onclick="closeOrderSuccessModal()"
                    class="flex-1 min-h-[48px] px-4 rounded-xl bg-forest text-white text-xs font-bold cursor-pointer"
                >
                    Selesai
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL UBAH ITEM PESANAN (Solusi A: Edit Pesanan Pending saat Kurir Datang)
         ========================================================================= --}}
    <div
        id="kasirEditOrderModal"
        onclick="if (event.target === this) closeEditOrderModal()"
        class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-black/45 backdrop-blur-[2px] p-0 sm:p-4"
    >
        <div class="bg-white border border-line rounded-t-2xl sm:rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden animate-sheet-up sm:animate-modal-pop flex flex-col max-h-[92vh]">
            {{-- Header --}}
            <div class="p-4 border-b border-line flex items-start justify-between gap-3 bg-white shrink-0">
                <div class="min-w-0 space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 id="editOrderModalTitle" class="text-sm font-bold text-ink tabular-nums">Ubah Pesanan #NCM-000</h3>
                        <span id="editOrderModalServiceBadge" class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md bg-sky-50 text-sky-800 border border-sky-200">
                            Kurir Zendo
                        </span>
                    </div>
                    <p id="editOrderModalSubtitle" class="text-xs font-normal text-sub truncate">Pelanggan</p>
                </div>
                <button
                    type="button"
                    onclick="closeEditOrderModal()"
                    aria-label="Tutup ubah pesanan"
                    class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl text-sub flex items-center justify-center cursor-pointer shrink-0"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4 overflow-y-auto space-y-5 flex-1">
                {{-- Info Alamat (jika Zendo) --}}
                <div id="editOrderAddressBox" class="hidden text-xs text-sky-900 bg-sky-50/70 border border-sky-200/80 rounded-xl px-3 py-2">
                    <i class="fa-solid fa-location-dot text-sky-700 mr-1.5"></i>
                    <span id="editOrderAddressText"></span>
                </div>

                {{-- Daftar Item Pesanan --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-ink">Item Pesanan Saat Ini</span>
                        <span id="editOrderItemCountLabel" class="text-xs font-normal text-muted tabular-nums">0 item</span>
                    </div>
                    <div id="editOrderItemsList" class="space-y-2"></div>
                </div>

                {{-- Tambah Menu Pengganti / Tambahan --}}
                <div class="pt-4 border-t border-line space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="text-xs font-bold text-ink">Tambah Menu Pengganti / Tambahan</span>
                        <input
                            type="search"
                            id="editOrderCatalogSearch"
                            oninput="renderEditOrderCatalog(this.value)"
                            placeholder="Cari nama menu..."
                            class="w-full sm:w-60 min-h-[40px] bg-canvas border border-line rounded-xl px-3 py-1.5 text-xs text-ink placeholder-muted focus:outline-none focus:border-forest focus:bg-white"
                        >
                    </div>
                    <div id="editOrderCatalogList" class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto pr-1"></div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="p-4 border-t border-line bg-canvas/50 space-y-3 shrink-0">
                <div class="space-y-1 text-xs">
                    <div class="flex items-center justify-between text-sub">
                        <span>Subtotal Menu Baru</span>
                        <span id="editOrderSubtotalText" class="font-bold text-ink tabular-nums">Rp 0</span>
                    </div>
                    <div id="editOrderDeliveryFeeRow" class="hidden flex items-center justify-between text-sky-800">
                        <span>Ongkir Kurir Zendo</span>
                        <span id="editOrderDeliveryFeeText" class="font-bold tabular-nums">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between pt-1.5 border-t border-line">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-ink text-xs">Total Baru</span>
                            <span id="editOrderDiffBadge" class="hidden text-[11px] font-bold px-2 py-0.5 rounded-md tabular-nums"></span>
                        </div>
                        <span id="editOrderTotalText" class="text-lg font-bold text-ink tabular-nums">Rp 0</span>
                    </div>
                </div>

                <div id="editOrderErrorBox" class="hidden text-xs font-bold text-danger bg-danger-soft border border-danger/20 rounded-xl px-3 py-2"></div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <button
                        type="button"
                        onclick="closeEditOrderModal()"
                        class="min-h-[46px] px-4 rounded-xl bg-white border border-line text-xs font-bold text-sub cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        id="btnSaveEditOrderOnly"
                        onclick="submitEditKasirOrder(false)"
                        class="min-h-[46px] px-4 rounded-xl bg-white border border-forest text-xs font-bold text-forest-dark cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Simpan Perubahan
                    </button>
                    <button
                        type="button"
                        id="btnSaveEditOrderAndPay"
                        onclick="submitEditKasirOrder(true)"
                        class="min-h-[46px] px-4 rounded-xl bg-forest hover:bg-forest-dark text-xs font-bold text-white cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Simpan &amp; Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL PEMBAYARAN PESANAN (Dine-In/Take Away sesuai pilihan | Zendo pilih metode dulu)
         ========================================================================= --}}
    <div
        id="kasirPayOrderModal"
        onclick="if (event.target === this) closePayOrderModal()"
        class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-black/45 backdrop-blur-[2px] p-0 sm:p-4"
    >
        <div class="bg-white border border-line rounded-t-2xl sm:rounded-2xl max-w-md w-full shadow-2xl overflow-hidden animate-sheet-up sm:animate-modal-pop flex flex-col max-h-[92vh]">
            {{-- Header --}}
            <div class="p-4 border-b border-line flex items-start justify-between gap-3 bg-white shrink-0">
                <div class="min-w-0 space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 id="payOrderModalTitle" class="text-sm font-bold text-ink tabular-nums">Pembayaran #NCM-000</h3>
                        <span id="payOrderModalServiceBadge" class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md bg-sky-50 text-sky-800 border border-sky-200">
                            Kurir Zendo
                        </span>
                    </div>
                    <p id="payOrderModalSubtitle" class="text-xs font-normal text-sub truncate">Pelanggan</p>
                </div>
                <button
                    type="button"
                    onclick="closePayOrderModal()"
                    aria-label="Tutup pembayaran"
                    class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl text-sub flex items-center justify-center cursor-pointer shrink-0"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4 overflow-y-auto space-y-4 flex-1">
                {{-- Ringkasan Tagihan --}}
                <div class="bg-canvas border border-line rounded-xl p-3.5 space-y-1.5 text-xs">
                    <div id="payOrderZendoFeeNotice" class="hidden space-y-1 pb-2 mb-1 border-b border-line">
                        <div class="flex items-center justify-between text-sky-800">
                            <span>Ongkir Kurir Zendo (untuk kurir)</span>
                            <span id="payOrderDeliveryFeeText" class="font-bold tabular-nums">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between text-sub">
                            <span>Total di Struk Pelanggan</span>
                            <span id="payOrderCustomerTotalText" class="font-bold tabular-nums text-ink">Rp 0</span>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span id="payOrderAmountLabel" class="text-xs font-bold text-sub">Total Tagihan</span>
                        <span id="payOrderPayableAmountText" class="text-2xl font-bold text-ink tabular-nums">Rp 0</span>
                    </div>
                </div>

                {{-- Pilihan Metode Pembayaran --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span id="payOrderMethodHint" class="text-xs font-bold text-ink">Pilih Metode Pembayaran</span>
                        <span id="payOrderCustomerChoiceBadge" class="hidden text-[11px] font-bold px-2 py-0.5 rounded-md bg-canvas border border-line text-sub"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <button
                            type="button"
                            onclick="selectPayOrderMethod('cash')"
                            data-pay-modal-btn="cash"
                            class="pay-modal-method-btn min-h-[48px] px-2 rounded-xl border border-line bg-canvas text-xs font-bold text-sub flex flex-col items-center justify-center gap-0.5 cursor-pointer transition-colors"
                        >
                            <i class="fa-solid fa-money-bill-wave text-xs"></i>
                            <span>Tunai</span>
                        </button>
                        <button
                            type="button"
                            onclick="selectPayOrderMethod('qris')"
                            data-pay-modal-btn="qris"
                            class="pay-modal-method-btn min-h-[48px] px-2 rounded-xl border border-line bg-canvas text-xs font-bold text-sub flex flex-col items-center justify-center gap-0.5 cursor-pointer transition-colors"
                        >
                            <i class="fa-solid fa-qrcode text-xs"></i>
                            <span>QRIS</span>
                        </button>
                        <button
                            type="button"
                            onclick="selectPayOrderMethod('transfer')"
                            data-pay-modal-btn="transfer"
                            class="pay-modal-method-btn min-h-[48px] px-2 rounded-xl border border-line bg-canvas text-xs font-bold text-sub flex flex-col items-center justify-center gap-0.5 cursor-pointer transition-colors"
                        >
                            <i class="fa-solid fa-building-columns text-xs"></i>
                            <span>Transfer</span>
                        </button>
                    </div>
                </div>

                {{-- Bagian Hitung Kembalian (HANYA tampil jika metode = Tunai) --}}
                <div id="payOrderCashSection" class="hidden space-y-2.5 pt-1">
                    <label for="payOrderCashReceived" class="block text-xs font-bold text-ink">
                        Uang Tunai Diterima
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-muted">Rp</span>
                        <input
                            type="number"
                            id="payOrderCashReceived"
                            min="0"
                            step="1000"
                            oninput="calculatePayOrderChange()"
                            placeholder="Masukkan nominal uang..."
                            class="w-full min-h-[48px] bg-canvas border border-line rounded-xl pl-10 pr-3.5 py-2 text-base font-bold tabular-nums text-ink placeholder-muted focus:outline-none focus:border-forest focus:bg-white"
                        >
                    </div>
                    <div class="grid grid-cols-4 gap-1.5">
                        <button type="button" onclick="setPayOrderQuickCash('exact')" class="min-h-[42px] px-2 rounded-xl bg-canvas border border-line text-xs font-bold text-ink cursor-pointer">Pas</button>
                        <button type="button" onclick="setPayOrderQuickCash(20000)" class="min-h-[42px] px-2 rounded-xl bg-canvas border border-line text-xs font-bold text-ink tabular-nums cursor-pointer">20K</button>
                        <button type="button" onclick="setPayOrderQuickCash(50000)" class="min-h-[42px] px-2 rounded-xl bg-canvas border border-line text-xs font-bold text-ink tabular-nums cursor-pointer">50K</button>
                        <button type="button" onclick="setPayOrderQuickCash(100000)" class="min-h-[42px] px-2 rounded-xl bg-canvas border border-line text-xs font-bold text-ink tabular-nums cursor-pointer">100K</button>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-canvas px-3.5 py-2.5 text-xs">
                        <span class="text-sub font-normal">Kembalian</span>
                        <span id="payOrderChangeDisplay" class="text-base font-bold tabular-nums text-ink">Rp 0</span>
                    </div>
                </div>

                {{-- Bagian Info Non-Tunai (QRIS / Transfer — tanpa hitung kembalian) --}}
                <div id="payOrderNonCashSection" class="hidden rounded-xl bg-forest-soft/60 border border-forest/20 p-3.5 space-y-2.5 text-xs text-forest-dark">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 shrink-0"></i>
                        <span id="payOrderNonCashText" class="leading-relaxed font-normal">Pembayaran non-tunai tidak memerlukan perhitungan kembalian.</span>
                    </div>
                    <div id="payOrderQrisPreviewWrap" class="hidden pt-1 text-center">
                        <img id="payOrderQrisPreviewImg" src="{{ $qrisImageUrl ?? '' }}" alt="QRIS Nucomu Cafe" class="w-40 h-40 object-contain mx-auto rounded-xl bg-white p-2 border border-line">
                    </div>
                </div>

                <div id="payOrderErrorBox" class="hidden text-xs font-bold text-danger bg-danger-soft border border-danger/20 rounded-xl px-3 py-2"></div>
            </div>

            {{-- Footer --}}
            <div class="p-4 border-t border-line bg-white grid grid-cols-3 gap-2 shrink-0">
                <button
                    type="button"
                    onclick="closePayOrderModal()"
                    class="min-h-[48px] px-3 rounded-xl bg-canvas border border-line text-xs font-bold text-sub cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    id="btnConfirmPayOrder"
                    onclick="submitPayKasirOrder()"
                    class="col-span-2 min-h-[48px] px-4 rounded-xl bg-forest hover:bg-forest-dark disabled:bg-line disabled:text-muted text-white text-xs font-bold cursor-pointer disabled:cursor-not-allowed transition-colors"
                >
                    Bayar &amp; Selesai
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         MODAL KONFIRMASI BATALKAN PESANAN
         ========================================================================= --}}
    <div
        id="kasirCancelOrderModal"
        onclick="if (event.target === this) closeCancelOrderConfirm()"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/45 backdrop-blur-[2px] p-4"
    >
        <div class="bg-white border border-line rounded-2xl max-w-sm w-full p-5 shadow-2xl animate-modal-pop space-y-4">
            <div class="space-y-1">
                <h3 id="cancelOrderModalTitle" class="text-sm font-bold text-ink">Batalkan Pesanan?</h3>
                <p id="cancelOrderModalDesc" class="text-xs font-normal text-sub leading-relaxed">
                    Pesanan akan dipindahkan ke daftar Batal dan stok menu akan dikembalikan otomatis.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button
                    type="button"
                    onclick="closeCancelOrderConfirm()"
                    class="min-h-[48px] px-4 rounded-xl bg-canvas text-sm font-bold text-ink cursor-pointer"
                >
                    Kembali
                </button>
                <button
                    type="button"
                    id="btnConfirmCancelOrder"
                    onclick="executeCancelKasirOrder()"
                    class="min-h-[48px] px-4 rounded-xl bg-danger text-white text-sm font-bold cursor-pointer"
                >
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         TOAST NOTIFIKASI TENANG
         ========================================================================= --}}
    <div
        id="kasirToast"
        class="fixed top-16 right-4 z-50 pointer-events-none opacity-0 -translate-y-2 transition-all duration-200 bg-ink text-white px-4 py-2.5 rounded-xl shadow-lg text-xs font-normal"
    >
        <span id="kasirToastText">Menu ditambahkan</span>
    </div>

    {{-- =========================================================================
         KASIR INTERACTIVE JAVASCRIPT ENGINE
         ========================================================================= --}}
    <script>
        const KASIR_CATALOG = @json($catalogJsMap);
        const KASIR_CHANNEL_ASSETS = {
            zendo: {
                logo: @json(asset('images/logos/zendo-courier.png')),
                title: 'Transaksi Kurir Zendo',
                pill: 'Tanpa Barcode',
                desc: 'Kurir Zendo menyampaikan pesanan ke kasir — input manual tanpa barcode.',
                bannerClass: 'rounded-xl border border-rose-200 bg-rose-50/80 p-2.5 flex items-center gap-3',
                pillClass: 'px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-600 text-white',
            },
            grab: {
                logo: @json(asset('images/logos/grabfood.png')),
                title: 'Transaksi GrabFood',
                pill: 'Tanpa Barcode',
                desc: 'Driver Grab menyampaikan pesanan ke kasir — input manual tanpa barcode.',
                bannerClass: 'rounded-xl border border-emerald-300 bg-emerald-50/80 p-2.5 flex items-center gap-3',
                pillClass: 'px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#00B14F] text-white',
            },
        };
        const KASIR_BASE_QR_URL = @json($baseQrUrl);
        const KASIR_QRIS_IMAGE_URL = @json($qrisImageUrl ?? null);
        const INITIAL_RECENT_ORDERS = @json($recentOrdersPayload ?? []);
        const INITIAL_SCANNED_ORDER = @json($scannedOrderPayload ?? null);

        const KASIR_STATE = {
            cart: [],
            orderChannel: 'direct',
            barcodeType: 'qr',
            activeMainTab: @json($activeTab),
            activeCategory: @json($initialCatalogTab),
            activeStockCategory: @json($firstCategoryKey),
            activeOrderChannelFilter: 'all',
            activeOrderStatus: 'unpaid',
            activeOrderService: 'all',
            orderSearchQuery: '',
            lastSuccessOrder: null,
            recentOrders: Array.isArray(INITIAL_RECENT_ORDERS) ? INITIAL_RECENT_ORDERS : [],
            ordersById: {},
            knownOrderIds: new Set(),
            activeScannedOrder: INITIAL_SCANNED_ORDER || null,
            editOrderDraft: null,
            payOrderDraft: null,
            cancelOrderTargetId: null,
            stocks: {
                @foreach($catalogItems as $ci)
                    {{ $ci['id'] }}: {{ $ci['is_available'] && (int)$ci['stock'] > 0 ? (int)$ci['stock'] : 0 }},
                @endforeach
            },
            variantDraft: null,
            mobileCartOpen: false,
            html5QrScanner: null,
            scannerRunning: false,
        };

        (KASIR_STATE.recentOrders || []).forEach(ord => {
            KASIR_STATE.ordersById[ord.id] = ord;
            KASIR_STATE.knownOrderIds.add(Number(ord.id));
        });
        if (KASIR_STATE.activeScannedOrder && KASIR_STATE.activeScannedOrder.id) {
            KASIR_STATE.ordersById[KASIR_STATE.activeScannedOrder.id] = KASIR_STATE.activeScannedOrder;
            KASIR_STATE.knownOrderIds.add(Number(KASIR_STATE.activeScannedOrder.id));
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024 && KASIR_STATE.mobileCartOpen) {
                closeMobileKasirDrawer();
            }
        });

        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('kasirMoreDropdown');
            const moreBtn = document.getElementById('kasirMoreMenuBtn');
            if (!dropdown || dropdown.classList.contains('hidden')) return;
            const clickedToggle = (moreBtn && moreBtn.contains(e.target)) || (e.target && e.target.closest('button[onclick*="toggleKasirMoreMenu"]'));
            if (!dropdown.contains(e.target) && !clickedToggle) {
                dropdown.classList.add('hidden');
            }
        });

        function toggleKasirMoreMenu(event) {
            if (event) event.stopPropagation();
            const dropdown = document.getElementById('kasirMoreDropdown');
            if (!dropdown) return;
            dropdown.classList.toggle('hidden');
        }

        function escapeKasirHtml(text) {
            return String(text ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function formatRupiah(amount) {
            const n = Math.max(0, Math.round(Number(amount) || 0));
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function switchKasirMainTab(tab) {
            KASIR_STATE.activeMainTab = tab;

            ['pos', 'stock', 'scan', 'orders'].forEach(t => {
                const panel = document.getElementById(`kasirMainPanel-${t}`);
                const btn = document.getElementById(`mainTabBtn-${t}`);
                const mobileBtn = document.getElementById(`mobileMainTabBtn-${t}`);
                if (panel) panel.classList.toggle('hidden', t !== tab);
                if (btn) {
                    const orderClasses = t === 'pos'
                        ? 'order-1 sm:order-1'
                        : t === 'stock'
                            ? 'order-3 sm:order-2'
                            : t === 'scan'
                                ? 'order-2 sm:order-3'
                                : 'order-4 sm:order-4';

                    btn.className = t === tab
                        ? `kasir-main-tab ${orderClasses} min-h-[44px] px-3.5 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 cursor-pointer bg-forest text-white`
                        : `kasir-main-tab ${orderClasses} min-h-[44px] px-3.5 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 cursor-pointer text-sub bg-canvas sm:bg-transparent`;
                }
                if (mobileBtn) {
                    mobileBtn.className = t === tab
                        ? 'kasir-mobile-nav-tab relative flex flex-col items-center justify-center gap-0.5 py-1.5 rounded-xl transition-colors cursor-pointer text-forest font-bold'
                        : 'kasir-mobile-nav-tab relative flex flex-col items-center justify-center gap-0.5 py-1.5 rounded-xl transition-colors cursor-pointer text-sub font-normal opacity-75';
                }
            });

            const mobileBar = document.getElementById('mobileKasirBottomBar');
            if (mobileBar) {
                mobileBar.classList.toggle('hidden', tab !== 'pos');
            }

            if (tab !== 'pos' && KASIR_STATE.mobileCartOpen) {
                closeMobileKasirDrawer();
            }

            if (tab !== 'scan' && KASIR_STATE.scannerRunning) {
                stopKasirCameraScanner();
            }

            if (tab === 'scan') {
                checkKasirCameraEnvironment();
                setTimeout(() => {
                    const codeInput = document.getElementById('manualOrderCodeInput');
                    if (codeInput && window.innerWidth >= 768) {
                        codeInput.focus();
                    }
                }, 80);
            }
        }

        function openMobileKasirDrawer() {
            const aside = document.getElementById('kasirCartAside');
            if (!aside) return;
            KASIR_STATE.mobileCartOpen = true;
            aside.className = 'fixed inset-0 z-50 flex flex-col justify-end bg-black/45 backdrop-blur-[2px] lg:static lg:block lg:h-full lg:min-h-0 lg:bg-transparent lg:backdrop-blur-none';
            document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
        }

        function closeMobileKasirDrawer() {
            const aside = document.getElementById('kasirCartAside');
            if (!aside) return;
            KASIR_STATE.mobileCartOpen = false;
            aside.className = 'hidden lg:block lg:h-full lg:min-h-0';
            document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
        }

        function handleCartBackdropClick(event) {
            if (window.innerWidth < 1024 && event.target && event.target.id === 'kasirCartAside') {
                closeMobileKasirDrawer();
            }
        }

        /**
         * Tap Menu Box — Menu tanpa varian langsung masuk 1 ketukan; menu dengan varian buka popup.
         */
        function kasirTapMenuBox(boxEl, event) {
            if (!boxEl) return;

            const id = Number(boxEl.getAttribute('data-menu-id'));
            const catalogMenu = KASIR_CATALOG[String(id)] || null;
            const name = catalogMenu?.name || boxEl.getAttribute('data-menu-name') || 'Menu';
            const price = Number(catalogMenu?.price ?? boxEl.getAttribute('data-menu-price')) || 0;
            const image = catalogMenu?.image || boxEl.getAttribute('data-menu-image') || '';
            const groupKey = catalogMenu?.group_key || boxEl.getAttribute('data-menu-category') || 'minuman';
            const subCategory = catalogMenu?.sub_category || boxEl.getAttribute('data-menu-subcategory') || '';
            const options = Array.isArray(catalogMenu?.options) ? catalogMenu.options : [];
            const isAvailable = Number(KASIR_STATE.stocks[id] ?? (boxEl.getAttribute('data-menu-stock') ?? 25)) > 0;

            if (!isAvailable) {
                showKasirToast(`${name} sedang habis.`);
                return;
            }

            if (options.length > 0) {
                openKasirVariantModal({
                    menuId: id,
                    name,
                    price,
                    image,
                    groupKey,
                    subCategory,
                    options,
                    editLineKey: null,
                });
                return;
            }

            addConfiguredItemToCart({
                menuId: id,
                name,
                basePrice: price,
                unitPrice: price,
                image,
                qty: 1,
                selectedOptions: [],
                quickNotes: [],
                customNote: '',
                hasOptions: false,
            });
            showKasirToast(`${name} ditambahkan`);
        }

        function classifyMenuOptions(options, groupKey) {
            const tempVariants = [];
            const sizeVariants = [];
            const otherVariants = [];
            const sugarAddons = [];
            const extraAddons = [];

            const tempPattern = /(^|\b)(hot|iced|ice|panas|dingin)(\b|$)/i;
            const sizePattern = /(^|\b)(regular|reguler|large|medium|small|tall|grande|venti|oz|cup|size|ukuran)(\b|$)/i;
            const sugarPattern = /(%|gula|sugar|manis)/i;

            options.forEach(opt => {
                const name = String(opt.name || '');
                const type = String(opt.type || 'variant');
                if (type === 'variant') {
                    if (tempPattern.test(name)) {
                        tempVariants.push(opt);
                    } else if (sizePattern.test(name)) {
                        sizeVariants.push(opt);
                    } else {
                        otherVariants.push(opt);
                    }
                } else {
                    if (sugarPattern.test(name)) {
                        sugarAddons.push(opt);
                    } else {
                        extraAddons.push(opt);
                    }
                }
            });

            const quickNoteChoices = groupKey === 'minuman'
                ? ['Less ice', 'Less sugar', 'Pisah gula', 'No ice']
                : ['Pisah sambal', 'Tidak pedas', 'Hangatkan'];

            return { tempVariants, sizeVariants, otherVariants, sugarAddons, extraAddons, quickNoteChoices };
        }

        function openKasirVariantModal(config) {
            const modal = document.getElementById('kasirVariantModal');
            if (!modal) return;

            const grouped = classifyMenuOptions(config.options || [], config.groupKey);
            const preSelected = config.preSelected || {};

            let chosenTempId = preSelected.tempId ?? (grouped.tempVariants[0]?.id ?? null);
            let chosenSizeId = preSelected.sizeId ?? (grouped.sizeVariants[0]?.id ?? null);
            let chosenOtherId = preSelected.otherId ?? (grouped.otherVariants[0]?.id ?? null);
            let chosenSugarId = preSelected.sugarId ?? null;
            let chosenAddonIds = Array.isArray(preSelected.addonIds) ? [...preSelected.addonIds] : [];
            let chosenQuickNotes = Array.isArray(preSelected.quickNotes) ? [...preSelected.quickNotes] : [];

            KASIR_STATE.variantDraft = {
                ...config,
                grouped,
                chosenTempId,
                chosenSizeId,
                chosenOtherId,
                chosenSugarId,
                chosenAddonIds,
                chosenQuickNotes,
                customNote: config.customNote || '',
                qty: config.qty || 1,
            };

            const imgEl = document.getElementById('variantModalImg');
            const titleEl = document.getElementById('variantModalTitle');
            const basePriceEl = document.getElementById('variantModalBasePrice');

            if (imgEl) {
                imgEl.src = config.image;
                imgEl.alt = config.name;
            }
            if (titleEl) titleEl.textContent = config.name;
            if (basePriceEl) basePriceEl.textContent = formatRupiah(config.price);

            renderVariantModalBody();
            updateVariantModalSummary();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeKasirVariantModal() {
            const modal = document.getElementById('kasirVariantModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            KASIR_STATE.variantDraft = null;
        }

        function renderVariantModalBody() {
            const body = document.getElementById('variantModalBody');
            const draft = KASIR_STATE.variantDraft;
            if (!body || !draft) return;

            const { tempVariants, sizeVariants, otherVariants, sugarAddons, extraAddons, quickNoteChoices } = draft.grouped;
            const sections = [];

            const renderSingleGroup = (title, items, selectedId, handlerName) => {
                if (!items.length) return '';
                const buttons = items.map(opt => {
                    const active = Number(selectedId) === Number(opt.id);
                    const extraPrice = Number(opt.price) > 0 ? `<span class="text-xs font-normal">+${formatRupiah(opt.price)}</span>` : '';
                    return `
                        <button
                            type="button"
                            onclick="${handlerName}(${opt.id})"
                            class="min-h-[48px] px-4 py-2 rounded-xl border text-xs flex items-center justify-between gap-2 cursor-pointer transition-colors ${active ? 'border-forest bg-forest-soft text-forest-dark font-bold' : 'border-line bg-white text-ink font-normal'}"
                        >
                            <span>${escapeKasirHtml(opt.name)}</span>
                            ${extraPrice}
                        </button>
                    `;
                }).join('');
                return `
                    <div class="space-y-2">
                        <label class="block text-xs font-normal text-sub">${escapeKasirHtml(title)}</label>
                        <div class="grid grid-cols-2 gap-2">${buttons}</div>
                    </div>
                `;
            };

            if (tempVariants.length) sections.push(renderSingleGroup('Suhu', tempVariants, draft.chosenTempId, 'selectVariantTemp'));
            if (sizeVariants.length) sections.push(renderSingleGroup('Ukuran', sizeVariants, draft.chosenSizeId, 'selectVariantSize'));
            if (otherVariants.length) sections.push(renderSingleGroup('Pilihan Varian', otherVariants, draft.chosenOtherId, 'selectVariantOther'));

            if (sugarAddons.length) {
                const sugarBtns = sugarAddons.map(opt => {
                    const active = Number(draft.chosenSugarId) === Number(opt.id);
                    return `
                        <button
                            type="button"
                            onclick="toggleVariantSugar(${opt.id})"
                            class="min-h-[44px] px-3.5 py-2 rounded-xl border text-xs cursor-pointer transition-colors ${active ? 'border-forest bg-forest-soft text-forest-dark font-bold' : 'border-line bg-white text-sub font-normal'}"
                        >
                            ${escapeKasirHtml(opt.name)}
                        </button>
                    `;
                }).join('');
                sections.push(`
                    <div class="space-y-2">
                        <label class="block text-xs font-normal text-sub">Level gula</label>
                        <div class="flex flex-wrap gap-2">${sugarBtns}</div>
                    </div>
                `);
            }

            if (extraAddons.length) {
                const extraBtns = extraAddons.map(opt => {
                    const active = draft.chosenAddonIds.includes(Number(opt.id));
                    const priceBadge = Number(opt.price) > 0 ? `+${formatRupiah(opt.price)}` : 'Gratis';
                    return `
                        <button
                            type="button"
                            onclick="toggleVariantAddon(${opt.id})"
                            class="min-h-[44px] px-3.5 py-2 rounded-xl border text-xs flex items-center gap-2 cursor-pointer transition-colors ${active ? 'border-forest bg-forest-soft text-forest-dark font-bold' : 'border-line bg-white text-ink font-normal'}"
                        >
                            <span>${escapeKasirHtml(opt.name)}</span>
                            <span class="text-xs font-normal tabular-nums">${priceBadge}</span>
                        </button>
                    `;
                }).join('');
                sections.push(`
                    <div class="space-y-2">
                        <label class="block text-xs font-normal text-sub">Tambahan</label>
                        <div class="flex flex-wrap gap-2">${extraBtns}</div>
                    </div>
                `);
            }

            const quickBtns = quickNoteChoices.map(chip => {
                const active = draft.chosenQuickNotes.includes(chip);
                return `
                    <button
                        type="button"
                        onclick="toggleVariantQuickNote('${escapeKasirHtml(chip)}')"
                        class="min-h-[44px] px-3.5 py-2 rounded-xl border text-xs cursor-pointer transition-colors ${active ? 'border-forest bg-forest-soft text-forest-dark font-bold' : 'border-line bg-canvas text-ink font-normal'}"
                    >
                        ${escapeKasirHtml(chip)}
                    </button>
                `;
            }).join('');

            sections.push(`
                <div class="space-y-2">
                    <label class="block text-xs font-normal text-sub">Catatan cepat</label>
                    <div class="flex flex-wrap gap-2">${quickBtns}</div>
                    <input
                        type="text"
                        id="variantModalCustomNote"
                        value="${escapeKasirHtml(draft.customNote)}"
                        oninput="if(KASIR_STATE.variantDraft){KASIR_STATE.variantDraft.customNote = this.value;}"
                        placeholder="Catatan tambahan (opsional)..."
                        class="w-full min-h-[44px] bg-canvas border border-line rounded-xl px-3.5 py-2 text-sm font-normal text-ink placeholder-muted focus:outline-none focus:border-forest focus:bg-white"
                    >
                </div>
            `);

            body.innerHTML = sections.join('');
        }

        function selectVariantTemp(optId) {
            if (!KASIR_STATE.variantDraft) return;
            KASIR_STATE.variantDraft.chosenTempId = optId;
            renderVariantModalBody();
            updateVariantModalSummary();
        }

        function selectVariantSize(optId) {
            if (!KASIR_STATE.variantDraft) return;
            KASIR_STATE.variantDraft.chosenSizeId = optId;
            renderVariantModalBody();
            updateVariantModalSummary();
        }

        function selectVariantOther(optId) {
            if (!KASIR_STATE.variantDraft) return;
            KASIR_STATE.variantDraft.chosenOtherId = optId;
            renderVariantModalBody();
            updateVariantModalSummary();
        }

        function toggleVariantSugar(optId) {
            if (!KASIR_STATE.variantDraft) return;
            KASIR_STATE.variantDraft.chosenSugarId = Number(KASIR_STATE.variantDraft.chosenSugarId) === Number(optId) ? null : optId;
            renderVariantModalBody();
            updateVariantModalSummary();
        }

        function toggleVariantAddon(optId) {
            if (!KASIR_STATE.variantDraft) return;
            const id = Number(optId);
            const list = KASIR_STATE.variantDraft.chosenAddonIds;
            const idx = list.indexOf(id);
            if (idx === -1) {
                list.push(id);
            } else {
                list.splice(idx, 1);
            }
            renderVariantModalBody();
            updateVariantModalSummary();
        }

        function toggleVariantQuickNote(chipText) {
            if (!KASIR_STATE.variantDraft) return;
            const list = KASIR_STATE.variantDraft.chosenQuickNotes;
            const idx = list.indexOf(chipText);
            if (idx === -1) {
                list.push(chipText);
            } else {
                list.splice(idx, 1);
            }
            renderVariantModalBody();
        }

        function adjustVariantModalQty(delta) {
            if (!KASIR_STATE.variantDraft) return;
            KASIR_STATE.variantDraft.qty = Math.max(1, KASIR_STATE.variantDraft.qty + delta);
            updateVariantModalSummary();
        }

        function getVariantModalSelectedObjects(draft) {
            const optionsById = new Map((draft.options || []).map(o => [Number(o.id), o]));
            const selectedIds = [
                draft.chosenTempId,
                draft.chosenSizeId,
                draft.chosenOtherId,
                draft.chosenSugarId,
                ...draft.chosenAddonIds,
            ].filter(v => v !== null && typeof v !== 'undefined');

            return selectedIds
                .map(id => optionsById.get(Number(id)))
                .filter(Boolean);
        }

        function updateVariantModalSummary() {
            const draft = KASIR_STATE.variantDraft;
            if (!draft) return;

            const selectedObjs = getVariantModalSelectedObjects(draft);
            const optionExtra = selectedObjs.reduce((sum, o) => sum + (Number(o.price) || 0), 0);
            const unitPrice = draft.price + optionExtra;
            const lineTotal = unitPrice * draft.qty;

            const qtyEl = document.getElementById('variantModalQtyText');
            const btnText = document.getElementById('variantModalConfirmText');
            if (qtyEl) qtyEl.textContent = String(draft.qty);
            if (btnText) {
                const prefix = draft.editLineKey ? 'Simpan' : 'Tambah';
                btnText.textContent = `${prefix} • ${formatRupiah(lineTotal)}`;
            }
        }

        function confirmKasirVariantSelection() {
            const draft = KASIR_STATE.variantDraft;
            if (!draft) return;

            const customNoteInput = document.getElementById('variantModalCustomNote');
            const customNote = (customNoteInput ? customNoteInput.value : draft.customNote || '').trim();
            const selectedObjs = getVariantModalSelectedObjects(draft);
            const optionExtra = selectedObjs.reduce((sum, o) => sum + (Number(o.price) || 0), 0);
            const unitPrice = draft.price + optionExtra;

            if (draft.editLineKey) {
                const editIdx = KASIR_STATE.cart.findIndex(item => item.lineKey === draft.editLineKey);
                if (editIdx !== -1) {
                    KASIR_STATE.cart.splice(editIdx, 1);
                }
            }

            addConfiguredItemToCart({
                menuId: draft.menuId,
                name: draft.name,
                basePrice: draft.price,
                unitPrice,
                image: draft.image,
                qty: draft.qty,
                selectedOptions: selectedObjs.map(o => ({ id: Number(o.id), name: o.name, price: Number(o.price) || 0, type: o.type || 'variant' })),
                quickNotes: [...draft.chosenQuickNotes],
                customNote,
                hasOptions: true,
                preSelected: {
                    tempId: draft.chosenTempId,
                    sizeId: draft.chosenSizeId,
                    otherId: draft.chosenOtherId,
                    sugarId: draft.chosenSugarId,
                    addonIds: [...draft.chosenAddonIds],
                },
            });

            const message = draft.editLineKey ? `Varian ${draft.name} diperbarui` : `${draft.name} ditambahkan`;
            closeKasirVariantModal();
            showKasirToast(message);
        }

        function buildCartLineKey(item) {
            const optPart = (item.selectedOptions || []).map(o => Number(o.id)).sort((a, b) => a - b).join(',');
            const quickPart = [...(item.quickNotes || [])].sort().join('|');
            const notePart = (item.customNote || '').trim().toLowerCase();
            return `${item.menuId}__${optPart}__${quickPart}__${notePart}`;
        }

        function addConfiguredItemToCart(config) {
            const lineKey = buildCartLineKey(config);
            const existing = KASIR_STATE.cart.find(item => item.lineKey === lineKey);

            if (existing) {
                existing.qty += config.qty;
            } else {
                KASIR_STATE.cart.push({
                    lineKey,
                    id: config.menuId,
                    menuId: config.menuId,
                    name: config.name,
                    basePrice: config.basePrice,
                    price: config.unitPrice,
                    image: config.image,
                    qty: config.qty,
                    selectedOptions: config.selectedOptions || [],
                    quickNotes: config.quickNotes || [],
                    customNote: config.customNote || '',
                    hasOptions: Boolean(config.hasOptions),
                    preSelected: config.preSelected || null,
                });
            }

            renderKasirCart(config.menuId);
        }

        function editKasirCartItemVariant(lineKey) {
            const item = KASIR_STATE.cart.find(i => i.lineKey === lineKey);
            if (!item) return;
            const catalogMenu = KASIR_CATALOG[String(item.menuId)];
            if (!catalogMenu || !Array.isArray(catalogMenu.options) || catalogMenu.options.length === 0) return;

            openKasirVariantModal({
                menuId: item.menuId,
                name: item.name,
                price: catalogMenu.price,
                image: item.image,
                groupKey: catalogMenu.group_key,
                subCategory: catalogMenu.sub_category,
                options: catalogMenu.options,
                editLineKey: item.lineKey,
                qty: item.qty,
                customNote: item.customNote,
                preSelected: {
                    ...(item.preSelected || {}),
                    quickNotes: [...(item.quickNotes || [])],
                },
            });
        }

        function adjustKasirCartQty(keyOrId, delta) {
            const idx = KASIR_STATE.cart.findIndex(i => i.lineKey === keyOrId || Number(i.id) === Number(keyOrId));
            if (idx === -1) return;

            const item = KASIR_STATE.cart[idx];
            const isAvailable = Number(KASIR_STATE.stocks[item.menuId] ?? 25) > 0;
            if (delta > 0 && !isAvailable) {
                showKasirToast(`${item.name} sedang habis.`);
                return;
            }

            item.qty += delta;
            if (item.qty <= 0) {
                KASIR_STATE.cart.splice(idx, 1);
            }
            renderKasirCart(item.menuId);
        }

        function confirmClearKasirCart() {
            if (KASIR_STATE.cart.length === 0) return;
            const modal = document.getElementById('kasirConfirmModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeKasirConfirmModal() {
            const modal = document.getElementById('kasirConfirmModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function executeClearKasirCart() {
            KASIR_STATE.cart = [];
            closeKasirConfirmModal();
            renderKasirCart();
            showKasirToast('Tagihan dikosongkan');
        }

        function clearKasirCart() {
            confirmClearKasirCart();
        }

        function renderKasirCart() {
            const emptyEl = document.getElementById('kasirCartEmpty');
            const listEl = document.getElementById('kasirCartItemsList');
            const countBadge = document.getElementById('kasirCartCountBadge');
            const clearBtn = document.getElementById('btnClearCart');
            const totalEl = document.getElementById('kasirGrandTotalDisplay');
            const submitBtn = document.getElementById('btnSubmitKasirOrder');
            const mobileLabel = document.getElementById('mobileCartSummaryLabel');
            const mobileTotal = document.getElementById('mobileCartTotalBadge');

            const totalQty = KASIR_STATE.cart.reduce((sum, i) => sum + i.qty, 0);
            const grandTotal = KASIR_STATE.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);

            if (countBadge) countBadge.textContent = `${totalQty} item`;
            if (totalEl) totalEl.textContent = formatRupiah(grandTotal);
            if (mobileLabel) mobileLabel.textContent = `Lihat pesanan (${totalQty} item)`;
            if (mobileTotal) mobileTotal.textContent = formatRupiah(grandTotal);
            if (submitBtn) submitBtn.disabled = totalQty === 0;
            if (clearBtn) {
                clearBtn.classList.toggle('hidden', totalQty === 0);
                clearBtn.classList.toggle('inline-flex', totalQty > 0);
            }

            const qtyByMenuId = {};
            KASIR_STATE.cart.forEach(item => {
                qtyByMenuId[item.menuId] = (qtyByMenuId[item.menuId] || 0) + item.qty;
            });

            document.querySelectorAll('.kasir-menu-box').forEach(box => {
                const boxId = Number(box.getAttribute('data-menu-id'));
                const totalForMenu = qtyByMenuId[boxId] || 0;
                const badge = box.querySelector(`[data-qty-badge="${boxId}"]`);

                if (totalForMenu > 0) {
                    box.classList.add('is-in-cart');
                    if (badge) {
                        badge.textContent = `${totalForMenu}x`;
                        badge.classList.remove('hidden');
                        badge.classList.add('inline-flex');
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
                    const safeLineKey = escapeKasirHtml(item.lineKey);

                    const detailParts = [];
                    (item.selectedOptions || []).forEach(opt => {
                        detailParts.push(opt.price > 0 ? `${opt.name} (+${formatRupiah(opt.price)})` : opt.name);
                    });
                    (item.quickNotes || []).forEach(qn => detailParts.push(qn));
                    if (item.customNote) detailParts.push(`Catatan: ${item.customNote}`);

                    const detailsLine = detailParts.length > 0
                        ? `<p class="text-xs font-normal text-sub leading-relaxed break-words">${escapeKasirHtml(detailParts.join(' · '))}</p>`
                        : '';

                    const minusOrTrashBtn = item.qty === 1
                        ? `<button
                                type="button"
                                onclick="adjustKasirCartQty('${safeLineKey}', -1)"
                                aria-label="Hapus ${escapeKasirHtml(item.name)}"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl bg-danger-soft text-danger flex items-center justify-center cursor-pointer active:scale-95 transition-transform"
                            >
                                <i class="fa-regular fa-trash-can text-sm"></i>
                            </button>`
                        : `<button
                                type="button"
                                onclick="adjustKasirCartQty('${safeLineKey}', -1)"
                                aria-label="Kurangi ${escapeKasirHtml(item.name)}"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl bg-canvas text-ink text-sm font-bold flex items-center justify-center cursor-pointer active:scale-95 transition-transform"
                            >
                                -
                            </button>`;

                    return `
                        <div class="py-3 space-y-2.5">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1 space-y-1">
                                    <div class="text-sm font-bold text-ink leading-snug break-words">
                                        ${escapeKasirHtml(item.name)}
                                    </div>
                                    ${detailsLine}
                                </div>
                                ${item.hasOptions ? `
                                    <button
                                        type="button"
                                        onclick="editKasirCartItemVariant('${safeLineKey}')"
                                        class="min-h-[44px] px-2.5 text-xs font-bold text-forest-dark shrink-0 cursor-pointer"
                                    >
                                        Ubah
                                    </button>
                                ` : ''}
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm font-bold text-ink tabular-nums">${formatRupiah(lineTotal)}</span>

                                <div class="flex items-center gap-2 shrink-0">
                                    ${minusOrTrashBtn}
                                    <span class="min-w-[24px] text-center text-sm font-bold text-ink tabular-nums">${item.qty}</span>
                                    <button
                                        type="button"
                                        onclick="adjustKasirCartQty('${safeLineKey}', 1)"
                                        aria-label="Tambah ${escapeKasirHtml(item.name)}"
                                        class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl bg-canvas text-ink text-sm font-bold flex items-center justify-center cursor-pointer active:scale-95 transition-transform"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            calculateKasirChange();
        }

        /**
         * Stock Control Engine (Hanya di tab Stok Barang)
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
                    showKasirToast(data.message || (nextAvailable ? 'Menu ditandai tersedia' : 'Menu ditandai habis'));
                } else {
                    applyStockToDom(id, currentStock, isCurrentlyAvailable);
                    showKasirToast('Gagal mengubah status menu.');
                }
            } catch (e) {
                applyStockToDom(id, currentStock, isCurrentlyAvailable);
                showKasirToast('Koneksi terganggu. Status dikembalikan.');
            }
        }

        function applyStockToDom(menuId, stockVal, isAvailableVal = null) {
            const id = Number(menuId);
            const isAvailable = isAvailableVal !== null ? Boolean(isAvailableVal) : (Number(stockVal) > 0);
            const stock = isAvailable ? 25 : 0;
            KASIR_STATE.stocks[id] = stock;

            if (KASIR_CATALOG[String(id)]) {
                KASIR_CATALOG[String(id)].is_available = isAvailable;
            }

            document.querySelectorAll(`.kasir-menu-box[data-menu-id="${id}"]`).forEach(box => {
                box.setAttribute('data-menu-stock', String(stock));
                box.setAttribute('aria-disabled', isAvailable ? 'false' : 'true');
                box.classList.toggle('is-out-of-stock', !isAvailable);

                const pill = box.querySelector(`[data-stock-pill="${id}"]`);
                if (pill) {
                    pill.classList.toggle('hidden', isAvailable);
                    pill.classList.toggle('inline-flex', !isAvailable);
                }
            });

            const cardStatus = document.getElementById(`stockCardStatus-${id}`);
            const cardBtn = document.getElementById(`stockCardActionBtn-${id}`);
            const cardDesc = document.getElementById(`stockCardDesc-${id}`);
            if (cardStatus) {
                cardStatus.textContent = isAvailable ? 'Tersedia' : 'Habis';
                cardStatus.className = isAvailable
                    ? 'shrink-0 px-2.5 py-1 rounded-lg text-xs font-bold bg-forest-soft text-forest-dark'
                    : 'shrink-0 px-2.5 py-1 rounded-lg text-xs font-bold bg-danger-soft text-danger';
            }
            if (cardBtn) {
                cardBtn.className = isAvailable
                    ? 'shrink-0 min-h-[44px] px-4 rounded-xl text-xs font-bold transition-colors cursor-pointer active:scale-95 bg-danger-soft text-danger'
                    : 'shrink-0 min-h-[44px] px-4 rounded-xl text-xs font-bold transition-colors cursor-pointer active:scale-95 bg-forest text-white';
                cardBtn.textContent = isAvailable ? 'Tandai Habis' : 'Tandai Ada';
            }
            if (cardDesc) {
                cardDesc.textContent = isAvailable ? 'Bisa dipesan di kasir' : 'Nonaktif sementara';
            }

            if (!isAvailable) {
                const beforeLen = KASIR_STATE.cart.length;
                KASIR_STATE.cart = KASIR_STATE.cart.filter(i => Number(i.menuId) !== id);
                if (KASIR_STATE.cart.length !== beforeLen) {
                    renderKasirCart();
                }
            }

            const allStocks = Object.values(KASIR_STATE.stocks).map(Number);
            const availCount = allStocks.filter(s => s > 0).length;
            const outCount = allStocks.filter(s => s <= 0).length;

            const statAvail = document.getElementById('stockStatAvailable');
            const statOut = document.getElementById('stockStatOut');
            const headerBadge = document.getElementById('headerStockAlertBadge');
            const mobileStockBadge = document.getElementById('mobileBottomStockBadge');

            if (statAvail) statAvail.textContent = String(availCount);
            if (statOut) statOut.textContent = String(outCount);
            if (headerBadge) {
                headerBadge.textContent = String(outCount);
                headerBadge.classList.toggle('hidden', outCount === 0);
                headerBadge.classList.toggle('inline-flex', outCount > 0);
            }
            if (mobileStockBadge) {
                mobileStockBadge.textContent = String(outCount);
                mobileStockBadge.classList.toggle('hidden', outCount === 0);
                mobileStockBadge.classList.toggle('inline-flex', outCount > 0);
            }
        }

        function filterStockCategory(catKey) {
            KASIR_STATE.activeStockCategory = catKey;

            document.querySelectorAll('.kasir-stock-cat-pill').forEach(btn => {
                const key = btn.getAttribute('data-stock-cat-btn');
                btn.className = key === catKey
                    ? 'kasir-stock-cat-pill shrink-0 min-h-[44px] px-4 rounded-xl text-xs transition-colors cursor-pointer flex items-center bg-forest text-white border border-forest font-bold'
                    : 'kasir-stock-cat-pill shrink-0 min-h-[44px] px-4 rounded-xl text-xs transition-colors cursor-pointer flex items-center bg-white text-sub border border-line font-normal';
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

        function filterKasirCategory(catKey) {
            KASIR_STATE.activeCategory = catKey;

            document.querySelectorAll('.kasir-cat-pill').forEach(btn => {
                const key = btn.getAttribute('data-cat-btn');
                btn.className = key === catKey
                    ? 'kasir-cat-pill shrink-0 min-h-[44px] px-4 rounded-xl text-xs transition-colors cursor-pointer flex items-center justify-center bg-forest text-white border border-forest font-bold'
                    : 'kasir-cat-pill shrink-0 min-h-[44px] px-4 rounded-xl text-xs transition-colors cursor-pointer flex items-center justify-center bg-white text-sub border border-line font-normal';
            });

            applyKasirCatalogFilters();
        }

        function searchKasirMenus(val) {
            const clearBtn = document.getElementById('kasirSearchClearBtn');
            if (clearBtn) {
                const hasText = String(val || '').trim() !== '';
                clearBtn.classList.toggle('hidden', !hasText);
                clearBtn.classList.toggle('flex', hasText);
            }
            applyKasirCatalogFilters();
        }

        function resetKasirMenuFilter() {
            const searchEl = document.getElementById('kasirMenuSearchInput');
            if (searchEl) searchEl.value = '';
            searchKasirMenus('');
        }

        function applyKasirCatalogFilters() {
            const q = (document.getElementById('kasirMenuSearchInput')?.value || '').toLowerCase().trim();
            let totalVisibleBoxes = 0;

            document.querySelectorAll('.kasir-category-section').forEach(section => {
                const secKey = section.getAttribute('data-category-section');
                const isSearchAllowedSection = q !== '' ? secKey !== 'terlaris' : KASIR_STATE.activeCategory === secKey;

                let visibleInSection = 0;
                section.querySelectorAll('.kasir-menu-box').forEach(box => {
                    const text = box.getAttribute('data-search-text') || '';
                    const searchMatches = q === '' || text.includes(q);
                    const show = isSearchAllowedSection && searchMatches;
                    box.classList.toggle('hidden', !show);
                    if (show) {
                        visibleInSection++;
                        totalVisibleBoxes++;
                    }
                });

                const isEmptyTerlarisActive = q === '' && secKey === 'terlaris' && KASIR_STATE.activeCategory === 'terlaris' && section.querySelectorAll('.kasir-menu-box').length === 0;
                section.classList.toggle('hidden', visibleInSection === 0 && !isEmptyTerlarisActive);
            });

            const emptyEl = document.getElementById('kasirEmptySearchState');
            if (emptyEl) emptyEl.classList.toggle('hidden', totalVisibleBoxes > 0 || q === '');
        }

        function setKasirOrderChannel(channel) {
            const normalized = ['direct', 'zendo', 'grab'].includes(channel) ? channel : 'direct';
            KASIR_STATE.orderChannel = normalized;

            const hiddenInput = document.getElementById('kasirOrderChannel');
            if (hiddenInput) hiddenInput.value = normalized;

            const directBtn = document.getElementById('channelBtn-direct');
            const zendoBtn = document.getElementById('channelBtn-zendo');
            const grabBtn = document.getElementById('channelBtn-grab');

            if (directBtn) {
                directBtn.className = normalized === 'direct'
                    ? 'kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-forest text-white text-xs font-bold transition-all flex flex-col items-center justify-center gap-0.5 cursor-pointer shadow-xs'
                    : 'kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-white text-sub border border-line text-xs font-bold transition-all flex flex-col items-center justify-center gap-0.5 cursor-pointer';
            }
            if (zendoBtn) {
                zendoBtn.className = normalized === 'zendo'
                    ? 'kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-rose-600 text-white border border-rose-600 text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-xs'
                    : 'kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-white text-ink border border-line text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer';
            }
            if (grabBtn) {
                grabBtn.className = normalized === 'grab'
                    ? 'kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-[#00B14F] text-white border border-[#00B14F] text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-xs'
                    : 'kasir-channel-btn min-h-[46px] px-2 py-1.5 rounded-lg bg-white text-ink border border-line text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer';
            }

            const banner = document.getElementById('kasirChannelBrandBanner');
            const bannerLogo = document.getElementById('kasirChannelBannerLogo');
            const bannerTitle = document.getElementById('kasirChannelBannerTitle');
            const bannerPill = document.getElementById('kasirChannelBannerPill');
            const bannerDesc = document.getElementById('kasirChannelBannerDesc');
            const dineWrap = document.getElementById('kasirDineTypeWrap');
            const tableWrap = document.getElementById('kasirTableNoWrap');
            const customerLabel = document.getElementById('kasirCustomerNameLabel');
            const customerInput = document.getElementById('kasirCustomerName');

            if (normalized === 'direct') {
                if (banner) banner.classList.add('hidden');
                if (dineWrap) dineWrap.classList.remove('hidden');
                setKasirOrderType(document.getElementById('kasirOrderType')?.value || 'dine_in');
                if (customerLabel) customerLabel.textContent = 'Nama pelanggan';
                if (customerInput) customerInput.placeholder = 'Opsional';
            } else {
                const cfg = KASIR_CHANNEL_ASSETS[normalized];
                if (banner && cfg) {
                    banner.className = cfg.bannerClass;
                    if (bannerLogo) bannerLogo.src = cfg.logo;
                    if (bannerTitle) bannerTitle.textContent = cfg.title;
                    if (bannerPill) {
                        bannerPill.textContent = cfg.pill;
                        bannerPill.className = cfg.pillClass;
                    }
                    if (bannerDesc) bannerDesc.textContent = cfg.desc;
                }
                if (dineWrap) dineWrap.classList.add('hidden');
                if (tableWrap) tableWrap.classList.add('hidden');
                const orderTypeInput = document.getElementById('kasirOrderType');
                if (orderTypeInput) orderTypeInput.value = 'take_away';
                if (customerLabel) {
                    customerLabel.textContent = normalized === 'zendo' ? 'Nama pelanggan / Kurir Zendo' : 'Nama / No. Order GrabFood';
                }
                if (customerInput) {
                    customerInput.placeholder = normalized === 'zendo' ? 'Contoh: Budi / Kurir Zendo' : 'Contoh: GF-123 / Ani';
                }
            }
        }

        function setKasirBarcodeType(type) {
            const normalized = type === 'barcode' ? 'barcode' : 'qr';
            KASIR_STATE.barcodeType = normalized;
            const input = document.getElementById('kasirBarcodeType');
            if (input) input.value = normalized;

            const qrBtn = document.getElementById('kasirBarcodeBtn-qr');
            const barBtn = document.getElementById('kasirBarcodeBtn-barcode');
            if (qrBtn) {
                qrBtn.className = normalized === 'qr'
                    ? 'min-h-[34px] px-2.5 rounded-lg bg-forest text-white text-[11px] font-bold flex items-center justify-center gap-1 cursor-pointer transition-colors'
                    : 'min-h-[34px] px-2.5 rounded-lg bg-white text-sub border border-line text-[11px] font-normal flex items-center justify-center gap-1 cursor-pointer transition-colors';
            }
            if (barBtn) {
                barBtn.className = normalized === 'barcode'
                    ? 'min-h-[34px] px-2.5 rounded-lg bg-forest text-white text-[11px] font-bold flex items-center justify-center gap-1 cursor-pointer transition-colors'
                    : 'min-h-[34px] px-2.5 rounded-lg bg-white text-sub border border-line text-[11px] font-normal flex items-center justify-center gap-1 cursor-pointer transition-colors';
            }
        }

        function setKasirOrderType(type) {
            const input = document.getElementById('kasirOrderType');
            const dineBtn = document.getElementById('orderTypeBtn-dine_in');
            const takeBtn = document.getElementById('orderTypeBtn-take_away');
            const tableWrap = document.getElementById('kasirTableNoWrap');

            if (input) input.value = type;
            if (dineBtn && takeBtn) {
                if (type === 'dine_in') {
                    dineBtn.className = 'min-h-[44px] px-3 rounded-lg bg-forest text-white text-xs font-bold transition-colors flex items-center justify-center cursor-pointer';
                    takeBtn.className = 'min-h-[44px] px-3 rounded-lg text-sub text-xs font-normal transition-colors flex items-center justify-center cursor-pointer';
                } else {
                    takeBtn.className = 'min-h-[44px] px-3 rounded-lg bg-forest text-white text-xs font-bold transition-colors flex items-center justify-center cursor-pointer';
                    dineBtn.className = 'min-h-[44px] px-3 rounded-lg text-sub text-xs font-normal transition-colors flex items-center justify-center cursor-pointer';
                }
            }

            if (tableWrap) {
                tableWrap.classList.toggle('hidden', type === 'take_away');
            }
        }

        function selectKasirPaymentMethod(method) {
            const input = document.getElementById('kasirPaymentMethod');
            const calc = document.getElementById('kasirCashCalculator');
            if (input) input.value = method;

            document.querySelectorAll('.kasir-pay-pill').forEach(btn => {
                const m = btn.getAttribute('data-pay-btn');
                btn.className = m === method
                    ? 'kasir-pay-pill min-h-[44px] px-2 rounded-lg text-xs font-bold transition-colors flex items-center justify-center cursor-pointer bg-forest text-white'
                    : 'kasir-pay-pill min-h-[44px] px-2 rounded-lg text-xs font-normal transition-colors flex items-center justify-center cursor-pointer text-sub';
            });

            if (calc) {
                calc.classList.toggle('hidden', method !== 'cash');
            }
        }

        function toggleKasirPaidStatus() {
            const input = document.getElementById('kasirPaymentStatus');
            const btn = document.getElementById('kasirPaidStatusBtn');
            const label = document.getElementById('kasirPaidLabel');
            if (!input || !btn || !label) return;

            const next = input.value === 'paid' ? 'unpaid' : 'paid';
            input.value = next;

            if (next === 'paid') {
                btn.className = 'min-h-[44px] px-3.5 rounded-xl bg-forest-soft text-forest-dark text-xs font-bold transition-colors cursor-pointer shrink-0';
                label.textContent = 'Lunas';
            } else {
                btn.className = 'min-h-[44px] px-3.5 rounded-xl bg-warning-soft text-warning-ink text-xs font-bold transition-colors cursor-pointer shrink-0';
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
                display.className = 'font-bold tabular-nums text-ink';
                return;
            }

            const diff = cash - grandTotal;
            if (diff >= 0) {
                display.textContent = formatRupiah(diff);
                display.className = 'font-bold tabular-nums text-forest-dark';
            } else {
                display.textContent = `Kurang ${formatRupiah(Math.abs(diff))}`;
                display.className = 'font-bold tabular-nums text-danger';
            }
        }

        async function submitKasirOrder() {
            if (KASIR_STATE.cart.length === 0) return;

            const btn = document.getElementById('btnSubmitKasirOrder');
            const btnText = document.getElementById('btnSubmitKasirOrderText');
            if (btn) btn.disabled = true;
            if (btnText) btnText.textContent = 'Menyimpan...';

            const currentChannel = KASIR_STATE.orderChannel || 'direct';
            const payload = {
                customer_name: document.getElementById('kasirCustomerName')?.value || '',
                order_type: currentChannel === 'direct' ? (document.getElementById('kasirOrderType')?.value || 'dine_in') : 'take_away',
                order_channel: currentChannel,
                barcode_type: currentChannel === 'direct' ? (KASIR_STATE.barcodeType || 'qr') : null,
                table_no: currentChannel === 'direct' ? (document.getElementById('kasirTableNo')?.value || '') : '',
                payment_method: document.getElementById('kasirPaymentMethod')?.value || 'cash',
                payment_status: document.getElementById('kasirPaymentStatus')?.value || 'paid',
                cash_received: Number(document.getElementById('kasirCashReceived')?.value || 0),
                notes: document.getElementById('kasirOrderNotes')?.value || '',
                items: KASIR_STATE.cart.map(i => {
                    const combinedNotes = [...(i.quickNotes || [])];
                    if (i.customNote) combinedNotes.push(i.customNote);
                    return {
                        menu_id: i.menuId,
                        menu_name: i.name,
                        unit_price: i.price,
                        qty: i.qty,
                        options: (i.selectedOptions || []).map(o => ({ name: o.name, price: o.price })),
                        item_notes: combinedNotes.join(', '),
                    };
                }),
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
                    showKasirToast(data.message || 'Gagal menyimpan pesanan.');
                    if (btn) btn.disabled = false;
                    if (btnText) btnText.textContent = 'Bayar';
                    return;
                }

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
                if (KASIR_STATE.mobileCartOpen) {
                    closeMobileKasirDrawer();
                }
                if (document.getElementById('kasirCustomerName')) document.getElementById('kasirCustomerName').value = '';
                if (document.getElementById('kasirTableNo')) document.getElementById('kasirTableNo').value = '';
                if (document.getElementById('kasirCashReceived')) document.getElementById('kasirCashReceived').value = '';

                openOrderSuccessModal({
                    code: data.order.code,
                    customerName: data.order.customer_name,
                    total: data.order.total,
                    changeAmount: data.order.change_amount || 0,
                    receiptUrl: data.receipt_url,
                    showChange: payload.payment_method === 'cash',
                    channel: data.order.order_channel || currentChannel,
                    usesBarcode: Boolean(data.order.uses_barcode),
                    barcodeType: data.order.barcode_type || KASIR_STATE.barcodeType || 'qr',
                    qrUrl: data.qr_url || (KASIR_BASE_QR_URL + data.order.code),
                });
                showKasirToast(`Pesanan #${data.order.code} tersimpan`);
                syncKasirWithDatabase(false);
            } catch (err) {
                showKasirToast('Koneksi bermasalah. Coba lagi.');
            } finally {
                if (btnText) btnText.textContent = 'Bayar';
            }
        }

        function openOrderSuccessModal(infoOrCode, customerNameArg = null, totalAmountArg = 0, changeAmountArg = 0, receiptUrlArg = null, showChangeArg = true) {
            const info = (infoOrCode && typeof infoOrCode === 'object')
                ? infoOrCode
                : {
                    code: infoOrCode,
                    customerName: customerNameArg,
                    total: totalAmountArg,
                    changeAmount: changeAmountArg,
                    receiptUrl: receiptUrlArg,
                    showChange: showChangeArg,
                    channel: 'direct',
                    usesBarcode: true,
                    barcodeType: KASIR_STATE.barcodeType || 'qr',
                    qrUrl: KASIR_BASE_QR_URL + String(infoOrCode || ''),
                };

            const modal = document.getElementById('kasirOrderSuccessModal');
            const codeEl = document.getElementById('modalSuccessOrderCode');
            const nameEl = document.getElementById('modalSuccessCustomerName');
            const totalEl = document.getElementById('modalSuccessTotalText');
            const changeRow = document.getElementById('modalSuccessChangeRow');
            const changeEl = document.getElementById('modalSuccessChangeText');
            const receiptBtn = document.getElementById('modalSuccessReceiptBtn');
            const partnerBanner = document.getElementById('modalSuccessPartnerBanner');
            const partnerLogo = document.getElementById('modalSuccessPartnerLogo');
            const partnerTitle = document.getElementById('modalSuccessPartnerTitle');
            const codeBox = document.getElementById('modalSuccessCodeBox');

            if (!modal) return;

            KASIR_STATE.lastSuccessOrder = info;

            if (codeEl) codeEl.textContent = `#${info.code}`;
            if (nameEl) nameEl.textContent = info.customerName || 'Pelanggan Kasir';
            if (totalEl) totalEl.textContent = formatRupiah(info.total);
            if (changeRow) changeRow.classList.toggle('hidden', info.showChange === false);
            if (changeEl) changeEl.textContent = formatRupiah(info.changeAmount || 0);
            if (receiptBtn && info.receiptUrl) receiptBtn.href = info.receiptUrl;

            const isPartner = info.channel === 'zendo' || info.channel === 'grab' || info.usesBarcode === false;
            if (isPartner && KASIR_CHANNEL_ASSETS[info.channel]) {
                const cfg = KASIR_CHANNEL_ASSETS[info.channel];
                if (partnerBanner) {
                    partnerBanner.className = cfg.bannerClass + ' text-left';
                }
                if (partnerLogo) partnerLogo.src = cfg.logo;
                if (partnerTitle) partnerTitle.textContent = cfg.title;
                if (codeBox) codeBox.classList.add('hidden');
            } else {
                if (partnerBanner) partnerBanner.classList.add('hidden');
                if (codeBox) codeBox.classList.remove('hidden');
                switchSuccessModalCodeMode(info.barcodeType === 'barcode' ? 'barcode' : 'qr');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function switchSuccessModalCodeMode(mode) {
            const normalized = mode === 'barcode' ? 'barcode' : 'qr';
            const info = KASIR_STATE.lastSuccessOrder;
            if (!info) return;

            const qrWrap = document.getElementById('modalSuccessQrWrap');
            const barWrap = document.getElementById('modalSuccessBarcodeWrap');
            const qrBtn = document.getElementById('modalSuccessCodeBtn-qr');
            const barBtn = document.getElementById('modalSuccessCodeBtn-barcode');

            if (qrWrap) qrWrap.classList.toggle('hidden', normalized !== 'qr');
            if (barWrap) barWrap.classList.toggle('hidden', normalized !== 'barcode');

            if (qrBtn) {
                qrBtn.className = normalized === 'qr'
                    ? 'py-1.5 rounded-md text-[11px] font-bold bg-forest text-white cursor-pointer flex items-center justify-center gap-1'
                    : 'py-1.5 rounded-md text-[11px] font-bold text-sub cursor-pointer flex items-center justify-center gap-1';
            }
            if (barBtn) {
                barBtn.className = normalized === 'barcode'
                    ? 'py-1.5 rounded-md text-[11px] font-bold bg-forest text-white cursor-pointer flex items-center justify-center gap-1'
                    : 'py-1.5 rounded-md text-[11px] font-bold text-sub cursor-pointer flex items-center justify-center gap-1';
            }

            if (normalized === 'barcode') {
                if (window.NucomuBarcode) {
                    window.NucomuBarcode.renderCode128Svg('#modalSuccessBarcodeTarget', info.code, {
                        barHeight: 56,
                        moduleWidth: 2,
                        fontSize: 13,
                    });
                }
            } else {
                const qrTarget = document.getElementById('modalSuccessQrTarget');
                if (qrTarget) {
                    qrTarget.innerHTML = '';
                    if (typeof QRCode === 'function') {
                        new QRCode(qrTarget, {
                            text: info.qrUrl || info.code,
                            width: 148,
                            height: 148,
                            colorDark: '#1B1F1D',
                            colorLight: '#FFFFFF',
                            correctLevel: QRCode.CorrectLevel ? QRCode.CorrectLevel.M : 2,
                        });
                    }
                }
            }
        }

        function closeOrderSuccessModal() {
            const modal = document.getElementById('kasirOrderSuccessModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function checkKasirCameraEnvironment() {
            const warningEl = document.getElementById('kasirCameraHttpWarning');
            const originCodeEl = document.getElementById('kasirCurrentOriginCode');
            if (!warningEl || !window.NucomuBarcode) return;

            const support = window.NucomuBarcode.checkCameraSupport();
            if (originCodeEl) originCodeEl.textContent = support.origin || window.location.origin;
            warningEl.classList.toggle('hidden', support.supported);
        }

        async function startKasirCameraScanner() {
            const placeholder = document.getElementById('kasirQrPlaceholder');
            const stopBtn = document.getElementById('btnStopCameraScanner');
            const badge = document.getElementById('cameraScannerBadge');

            checkKasirCameraEnvironment();

            if (!window.NucomuBarcode) {
                showKasirToast('Scanner belum siap.');
                return;
            }

            const support = window.NucomuBarcode.checkCameraSupport();
            if (!support.supported) {
                if (badge) badge.textContent = 'Butuh HTTPS';
                showKasirToast('Kamera live butuh HTTPS/localhost. Gunakan Foto/Kamera HP atau alat scanner USB.');
                return;
            }

            if (placeholder) placeholder.classList.add('hidden');
            if (stopBtn) stopBtn.classList.remove('hidden');
            if (badge) badge.textContent = 'Aktif';

            try {
                KASIR_STATE.html5QrScanner = await window.NucomuBarcode.startSmartCamera(
                    'kasirQrReader',
                    (decodedText) => {
                        stopKasirCameraScanner();
                        const clean = window.NucomuBarcode.extractOrderCode(decodedText);
                        const inputEl = document.getElementById('manualOrderCodeInput');
                        if (inputEl) inputEl.value = clean;
                        lookupKasirOrderCode(clean);
                    }
                );
                KASIR_STATE.scannerRunning = true;
            } catch (err) {
                KASIR_STATE.scannerRunning = false;
                if (placeholder) placeholder.classList.remove('hidden');
                if (stopBtn) stopBtn.classList.add('hidden');
                if (badge) badge.textContent = 'Gagal';
                showKasirToast(err?.message || 'Izinkan akses kamera, gunakan Foto/Kamera HP, atau alat scanner.');
            }
        }

        async function handleKasirPhotoScan(inputEl) {
            const file = inputEl?.files?.[0];
            if (!file) return;

            const badge = document.getElementById('cameraScannerBadge');
            if (badge) badge.textContent = 'Membaca...';

            try {
                const decoded = await window.NucomuBarcode.scanImageFile('kasirQrFileReaderHidden', file);
                const cleanCode = window.NucomuBarcode.extractOrderCode(decoded);
                const codeInput = document.getElementById('manualOrderCodeInput');
                if (codeInput) codeInput.value = cleanCode;
                if (badge) badge.textContent = 'Terdeteksi';
                lookupKasirOrderCode(cleanCode);
            } catch (err) {
                if (badge) badge.textContent = 'Siaga';
                showKasirToast('Barcode / QR tidak terbaca pada foto. Pastikan gambar jelas & terang.');
            } finally {
                inputEl.value = '';
            }
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
            const inputEl = document.getElementById('manualOrderCodeInput');
            const rawVal = inputEl?.value || '';
            const clean = window.NucomuBarcode ? window.NucomuBarcode.extractOrderCode(rawVal) : rawVal.trim();
            if (inputEl && clean) inputEl.value = clean;
            lookupKasirOrderCode(clean);
        }

        function getOrderServiceMeta(order) {
            if (order && order.is_zendo) {
                return {
                    key: 'zendo',
                    label: order.service_type_label || 'Kurir Zendo',
                    badgeClass: 'bg-sky-50 text-sky-800 border border-sky-200',
                    icon: 'fa-motorcycle',
                    cardBorder: 'border-sky-200',
                };
            }
            if (order && order.order_type === 'dine_in') {
                return {
                    key: 'dine_in',
                    label: order.service_type_label || 'Makan di Sini',
                    badgeClass: 'bg-canvas text-ink border border-line',
                    icon: 'fa-utensils',
                    cardBorder: 'border-line',
                };
            }
            return {
                key: 'take_away',
                label: order?.service_type_label || 'Bawa Pulang',
                badgeClass: 'bg-stone-100 text-sub border border-line',
                icon: 'fa-bag-shopping',
                cardBorder: 'border-line',
            };
        }

        function formatOrderOptionsText(options) {
            if (!Array.isArray(options) || options.length === 0) return '';
            return options.map(opt => {
                if (opt && typeof opt === 'object') {
                    return opt.price > 0 ? `${opt.name} (+${formatRupiah(opt.price)})` : (opt.name || '');
                }
                return String(opt || '');
            }).filter(Boolean).join(' · ');
        }

        function getKasirOrderBucket(order) {
            if (!order) return 'unpaid';
            if (order.kasir_bucket) return order.kasir_bucket;
            if (order.status === 'cancelled') return 'cancelled';
            if (order.status === 'completed' || ((order.status === 'processing' || order.status === 'ready') && order.payment_status === 'paid')) {
                return 'completed';
            }
            return 'unpaid';
        }

        function formatPaymentMethodLabel(method) {
            const m = String(method || 'cash').toLowerCase();
            if (m === 'qris') return 'QRIS';
            if (m === 'transfer') return 'Transfer';
            return 'Tunai';
        }

        function getOrderChannelBadgeHtml(order) {
            const ch = (order && order.order_channel) ? order.order_channel : (order && order.is_zendo ? 'zendo' : 'direct');
            if (ch === 'zendo') {
                return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200 text-[10.5px] font-bold"><img src="${KASIR_CHANNEL_ASSETS.zendo.logo}" alt="Zendo" class="w-3.5 h-3.5 rounded-full object-cover"><span>Zendo</span></span>`;
            }
            if (ch === 'grab') {
                return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10.5px] font-bold"><img src="${KASIR_CHANNEL_ASSETS.grab.logo}" alt="GrabFood" class="w-3.5 h-3.5 object-contain"><span>GrabFood</span></span>`;
            }
            return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-canvas text-sub border border-line text-[10.5px] font-bold"><i class="fa-solid fa-barcode text-[10px] text-forest"></i><span>Di Tempat</span></span>`;
        }

        function renderScannedOrderResult(o) {
            const container = document.getElementById('scannedOrderResultContainer');
            if (!container || !o) return;

            KASIR_STATE.activeScannedOrder = o;
            KASIR_STATE.ordersById[o.id] = o;

            const serviceMeta = getOrderServiceMeta(o);
            const bucket = getKasirOrderBucket(o);
            const deliveryFee = Number(o.delivery_fee || 0);
            const payLabel = o.payment_method_label || formatPaymentMethodLabel(o.payment_method);

            const itemsHtml = (o.items || []).map(i => {
                const optsText = formatOrderOptionsText(i.options);
                return `
                    <div class="flex items-start justify-between gap-2 bg-canvas rounded-xl px-3.5 py-2.5 text-xs">
                        <div class="min-w-0">
                            <div>
                                <span class="font-bold text-ink">${escapeKasirHtml(i.menu_name)}</span>
                                <span class="text-sub ml-1.5 tabular-nums">x${i.qty}</span>
                            </div>
                            ${optsText ? `<p class="text-[11px] text-sub mt-0.5">${escapeKasirHtml(optsText)}</p>` : ''}
                        </div>
                        <span class="font-bold text-ink tabular-nums shrink-0">${formatRupiah(i.line_total)}</span>
                    </div>
                `;
            }).join('');

            const customerMetaParts = [escapeKasirHtml(o.customer_name || 'Pelanggan Kasir')];
            if (o.table_number) {
                customerMetaParts.push(`Meja ${escapeKasirHtml(o.table_number)}`);
            }
            if (o.customer_phone && o.customer_phone !== '-') {
                customerMetaParts.push(escapeKasirHtml(o.customer_phone));
            }

            const paymentInfoLine = bucket === 'unpaid'
                ? (o.is_zendo
                    ? `<p class="text-xs text-sub mt-1">Pembayaran: <strong class="text-sky-800">Pilih metode saat kurir Zendo bayar</strong></p>`
                    : `<p class="text-xs text-sub mt-1">Metode pilihan pelanggan: <strong class="text-ink">${escapeKasirHtml(payLabel)}</strong></p>`)
                : '';

            const addressHtml = (o.is_zendo && o.delivery_address)
                ? `<p class="text-xs text-sky-900 bg-sky-50/70 border border-sky-200/80 rounded-lg px-2.5 py-1.5 mt-2"><i class="fa-solid fa-location-dot text-sky-700 mr-1"></i>${escapeKasirHtml(o.delivery_address)}</p>`
                : '';

            const editButtonHtml = o.can_edit
                ? `<button type="button" onclick="openEditOrderModal(${o.id})" class="min-h-[48px] px-4 rounded-xl bg-warning-soft text-warning-ink border border-warning/40 text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer"><i class="fa-solid fa-pen-to-square text-[11px]"></i><span>Ubah Pesanan</span></button>`
                : '';

            const feeRowsHtml = deliveryFee > 0
                ? `
                    <div class="flex items-center justify-between text-xs text-sub">
                        <span>Subtotal Menu (Bayar ke Kafe)</span>
                        <span class="font-bold tabular-nums text-ink">${formatRupiah(o.subtotal)}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-sky-800">
                        <span>Ongkir Kurir Zendo</span>
                        <span class="font-bold tabular-nums">${formatRupiah(deliveryFee)}</span>
                    </div>
                `
                : '';

            const statusBadgeClass = bucket === 'completed'
                ? 'bg-forest-soft text-forest-dark'
                : (bucket === 'cancelled' ? 'bg-danger-soft text-danger' : 'bg-warning-soft text-warning-ink');
            const statusBadgeText = bucket === 'completed'
                ? `SELESAI • LUNAS (${payLabel})`
                : (bucket === 'cancelled' ? 'BATAL' : 'BELUM BAYAR');

            let footerActionsHtml = '';
            if (bucket === 'unpaid') {
                footerActionsHtml = `
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="openPayOrderModal(${o.id})" class="flex-1 min-h-[48px] px-4 rounded-xl bg-forest hover:bg-forest-dark text-white text-xs font-bold cursor-pointer">
                            Bayar &amp; Selesai
                        </button>
                        ${editButtonHtml}
                        <button type="button" onclick="openCancelOrderConfirm(${o.id})" class="min-h-[48px] px-4 rounded-xl bg-danger-soft text-danger text-xs font-bold cursor-pointer">
                            Batalkan
                        </button>
                    </div>
                `;
            } else if (bucket === 'completed') {
                footerActionsHtml = `
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-forest-soft px-4 py-3 text-xs font-bold text-forest-dark">
                        <span>Transaksi Selesai • Lunas (${escapeKasirHtml(payLabel)})</span>
                        <a href="${escapeKasirHtml(o.receipt_url)}" target="_blank" class="underline">Cetak Struk</a>
                    </div>
                `;
            } else {
                footerActionsHtml = `
                    <div class="rounded-xl bg-danger-soft px-4 py-3 text-xs font-bold text-danger">
                        Pesanan ini sudah dibatalkan.
                    </div>
                `;
            }

            container.innerHTML = `
                <div class="space-y-4">
                    <div class="flex flex-wrap items-start justify-between gap-3 pb-4 border-b border-line">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-md ${statusBadgeClass}">
                                    ${escapeKasirHtml(statusBadgeText)}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-md ${serviceMeta.badgeClass}">
                                    <i class="fa-solid ${serviceMeta.icon} text-[10px]"></i>
                                    <span>${escapeKasirHtml(serviceMeta.label)}</span>
                                </span>
                                ${getOrderChannelBadgeHtml(o)}
                            </div>
                            <h3 class="text-sm font-bold text-ink mt-2 tabular-nums">#${escapeKasirHtml(o.code)}</h3>
                            <p class="text-xs font-normal text-sub mt-0.5">${customerMetaParts.join(' • ')}</p>
                            ${paymentInfoLine}
                            ${addressHtml}
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="${escapeKasirHtml(o.receipt_url)}" target="_blank" class="min-h-[44px] px-4 rounded-xl bg-canvas border border-line text-ink text-xs font-bold flex items-center">
                                Cetak Struk
                            </a>
                        </div>
                    </div>
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">${itemsHtml}</div>
                    <div class="pt-3 border-t border-line space-y-1.5">
                        ${feeRowsHtml}
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-xs font-normal text-sub">${o.is_zendo ? 'Total Pelanggan' : 'Total'}</span>
                            <span class="text-2xl font-bold text-ink tabular-nums">${formatRupiah(o.total)}</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-line">${footerActionsHtml}</div>
                </div>
            `;
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

                renderScannedOrderResult(data.order);
                showKasirToast(`Pesanan #${data.order.code} ditemukan`);
            } catch (e) {
                showKasirToast('Gagal memuat detail pesanan');
            }
        }

        async function updateKasirOrderStatus(orderId, status, paymentStatus = null, extraPayload = {}) {
            try {
                const bodyPayload = { status, ...extraPayload };
                if (paymentStatus) {
                    bodyPayload.payment_status = paymentStatus;
                }

                const res = await fetch(`/kasir/pesanan/${orderId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify(bodyPayload),
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    if (data.order) {
                        upsertKasirOrderInState(data.order);
                        if (KASIR_STATE.activeScannedOrder && Number(KASIR_STATE.activeScannedOrder.id) === Number(data.order.id)) {
                            renderScannedOrderResult(data.order);
                        }
                    }
                    if (data.stocks) {
                        Object.entries(data.stocks).forEach(([mid, newStock]) => {
                            applyStockToDom(Number(mid), Number(newStock));
                        });
                    }
                    if (data.stats) {
                        updateKasirHeaderStats(data.stats);
                    }
                    renderKasirOrdersGrid();
                    showKasirToast(data.message || 'Status pesanan diperbarui');
                    syncKasirWithDatabase(false);
                    return data;
                }
                showKasirToast(data.message || 'Gagal memperbarui status pesanan');
                return { success: false, message: data.message };
            } catch (e) {
                showKasirToast('Gagal memperbarui status pesanan');
                return { success: false, message: 'Koneksi bermasalah. Coba lagi.' };
            }
        }

        function upsertKasirOrderInState(orderObj) {
            if (!orderObj || !orderObj.id) return;
            KASIR_STATE.ordersById[orderObj.id] = orderObj;
            KASIR_STATE.knownOrderIds.add(Number(orderObj.id));

            const idx = KASIR_STATE.recentOrders.findIndex(o => Number(o.id) === Number(orderObj.id));
            if (idx !== -1) {
                KASIR_STATE.recentOrders[idx] = orderObj;
            } else {
                KASIR_STATE.recentOrders.unshift(orderObj);
            }
        }

        function updateKasirChannelCounts(orders) {
            let countDirect = 0;
            let countZendo = 0;
            let countGrab = 0;
            (orders || []).forEach(o => {
                const ch = o.order_channel || (o.is_zendo ? 'zendo' : 'direct');
                if (ch === 'zendo') countZendo++;
                else if (ch === 'grab') countGrab++;
                else countDirect++;
            });

            const cAll = document.getElementById('kasirChannelCount-all');
            const cDir = document.getElementById('kasirChannelCount-direct');
            const cZen = document.getElementById('kasirChannelCount-zendo');
            const cGrb = document.getElementById('kasirChannelCount-grab');
            if (cAll) cAll.textContent = String((orders || []).length);
            if (cDir) cDir.textContent = String(countDirect);
            if (cZen) cZen.textContent = String(countZendo);
            if (cGrb) cGrb.textContent = String(countGrab);
        }

        function renderKasirOrdersGrid() {
            const container = document.getElementById('kasirOrdersGridContainer');
            if (!container) return;

            updateKasirChannelCounts(KASIR_STATE.recentOrders || []);

            const channelFilter = KASIR_STATE.activeOrderChannelFilter || 'all';
            const statusFilter = KASIR_STATE.activeOrderStatus || 'unpaid';
            const serviceFilter = KASIR_STATE.activeOrderService || 'all';
            const query = (KASIR_STATE.orderSearchQuery || '').toLowerCase().trim();

            const filtered = (KASIR_STATE.recentOrders || []).filter(ord => {
                const ch = ord.order_channel || (ord.is_zendo ? 'zendo' : 'direct');
                const serviceMeta = getOrderServiceMeta(ord);
                const bucket = getKasirOrderBucket(ord);
                if (channelFilter !== 'all' && ch !== channelFilter) {
                    return false;
                }
                if (query === '' && bucket !== statusFilter) {
                    return false;
                }
                if (serviceFilter !== 'all' && serviceMeta.key !== serviceFilter) {
                    return false;
                }
                if (query !== '') {
                    const itemsText = (ord.items || []).map(it => it.menu_name || '').join(' ');
                    const hay = `${ord.code || ''} ${ord.customer_name || ''} ${ord.channel_label || ''} ${ord.delivery_address || ''} ${serviceMeta.label} ${itemsText}`.toLowerCase();
                    if (!hay.includes(query)) {
                        return false;
                    }
                }
                return true;
            });

            if (filtered.length === 0) {
                const emptyLabel = query !== ''
                    ? 'Pesanan tidak ditemukan untuk kata kunci tersebut'
                    : (serviceFilter !== 'all' || channelFilter !== 'all'
                        ? 'Belum ada pesanan pada filter ini'
                        : (statusFilter === 'unpaid'
                            ? 'Tidak ada pesanan yang menunggu pembayaran'
                            : (statusFilter === 'completed' ? 'Belum ada pesanan selesai' : 'Belum ada pesanan dibatalkan')));
                container.innerHTML = `
                    <div class="col-span-full bg-white border border-line rounded-xl p-12 text-center">
                        <p class="text-sm font-bold text-ink">${escapeKasirHtml(emptyLabel)}</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(ord => {
                const serviceMeta = getOrderServiceMeta(ord);
                const bucket = getKasirOrderBucket(ord);
                const deliveryFee = Number(ord.delivery_fee || 0);
                const payLabel = ord.payment_method_label || formatPaymentMethodLabel(ord.payment_method);

                const itemsHtml = (ord.items || []).map(it => {
                    const optsText = formatOrderOptionsText(it.options);
                    return `
                        <div class="flex items-start justify-between gap-2 text-ink">
                            <div class="min-w-0 pr-2">
                                <span class="font-normal">${escapeKasirHtml(it.menu_name)} <strong class="font-bold tabular-nums">x${it.qty}</strong></span>
                                ${optsText ? `<div class="text-[10.5px] text-sub truncate">${escapeKasirHtml(optsText)}</div>` : ''}
                            </div>
                            <span class="font-bold tabular-nums shrink-0">${formatRupiah(it.line_total)}</span>
                        </div>
                    `;
                }).join('');

                const paymentMetaHtml = bucket === 'unpaid'
                    ? (ord.is_zendo
                        ? `<p class="text-[11px] text-sub mt-1">Pembayaran: <strong class="text-sky-800">Pilih saat kurir tiba</strong></p>`
                        : `<p class="text-[11px] text-sub mt-1">Pembayaran: <strong class="text-ink">${escapeKasirHtml(payLabel)}</strong></p>`)
                    : '';

                const addressHtml = (ord.is_zendo && ord.delivery_address)
                    ? `<p class="text-[11px] text-sky-900 bg-sky-50/70 border border-sky-200/70 rounded-lg px-2 py-1 mt-2 line-clamp-2"><i class="fa-solid fa-location-dot text-sky-700 mr-1"></i>${escapeKasirHtml(ord.delivery_address)}</p>`
                    : '';

                const feeHtml = deliveryFee > 0
                    ? `<div class="flex items-center justify-between text-[11px] text-sky-800"><span>Ongkir Kurir Zendo</span><span class="font-bold tabular-nums">${formatRupiah(deliveryFee)}</span></div>`
                    : '';

                const badgeClass = bucket === 'completed'
                    ? 'bg-forest-soft text-forest-dark'
                    : (bucket === 'cancelled' ? 'bg-danger-soft text-danger' : 'bg-warning-soft text-warning-ink');
                const badgeText = bucket === 'completed'
                    ? `Selesai • ${payLabel}`
                    : (bucket === 'cancelled' ? 'Batal' : 'Belum Bayar');

                let actionButtonsHtml = '';
                if (bucket === 'unpaid') {
                    const editBtnHtml = ord.can_edit
                        ? `<button type="button" onclick="openEditOrderModal(${ord.id})" class="min-h-[44px] px-3 rounded-xl bg-warning-soft text-warning-ink border border-warning/40 text-xs font-bold flex items-center gap-1 cursor-pointer shrink-0" title="Ubah item pesanan"><i class="fa-solid fa-pen-to-square text-[11px]"></i><span>Ubah</span></button>`
                        : '';
                    actionButtonsHtml = `
                        <button type="button" onclick="openPayOrderModal(${ord.id})" class="flex-1 min-h-[44px] px-3.5 rounded-xl bg-forest hover:bg-forest-dark text-white text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check text-[11px]"></i>
                            <span>Bayar &amp; Selesai</span>
                        </button>
                        ${editBtnHtml}
                        <button type="button" onclick="openCancelOrderConfirm(${ord.id})" class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl bg-danger-soft text-danger text-xs font-bold flex items-center justify-center cursor-pointer shrink-0" title="Batalkan pesanan" aria-label="Batalkan pesanan">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;
                } else if (bucket === 'completed') {
                    actionButtonsHtml = `
                        <a href="${escapeKasirHtml(ord.receipt_url)}" target="_blank" class="flex-1 min-h-[44px] px-3.5 rounded-xl bg-forest text-white text-xs font-bold flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-print text-[11px]"></i>
                            <span>Cetak Struk</span>
                        </a>
                    `;
                } else {
                    actionButtonsHtml = `
                        <span class="flex-1 min-h-[44px] px-3.5 rounded-xl bg-canvas text-muted text-xs font-bold flex items-center justify-center">
                            Pesanan Dibatalkan
                        </span>
                    `;
                }

                return `
                    <div
                        class="kasir-order-card bg-white border ${serviceMeta.cardBorder} rounded-xl p-4 flex flex-col justify-between gap-4"
                        data-order-id="${ord.id}"
                        data-order-bucket="${escapeKasirHtml(bucket)}"
                        data-order-status="${escapeKasirHtml(ord.status)}"
                        data-order-channel="${escapeKasirHtml(ord.order_channel || (ord.is_zendo ? 'zendo' : 'direct'))}"
                        data-order-service="${escapeKasirHtml(serviceMeta.key)}"
                    >
                        <div>
                            <div class="flex items-start justify-between gap-2 pb-3 border-b border-line">
                                <div class="min-w-0 w-full">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-xs font-bold text-ink tabular-nums">#${escapeKasirHtml(ord.code)}</span>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-md ${badgeClass}">
                                            ${escapeKasirHtml(badgeText)}
                                        </span>
                                        ${getOrderChannelBadgeHtml(ord)}
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md ${serviceMeta.badgeClass}">
                                            <i class="fa-solid ${serviceMeta.icon} text-[10px]"></i>
                                            <span>${escapeKasirHtml(serviceMeta.label)}</span>
                                        </span>
                                        <span class="text-xs font-bold text-ink truncate">
                                            ${escapeKasirHtml(ord.customer_name || 'Pelanggan Kasir')}
                                            ${ord.table_number ? `<span class="font-normal text-sub">• Meja ${escapeKasirHtml(ord.table_number)}</span>` : ''}
                                        </span>
                                    </div>
                                    ${paymentMetaHtml}
                                    ${addressHtml}
                                </div>
                            </div>

                            <div class="py-3 space-y-1.5 max-h-36 overflow-y-auto text-xs">${itemsHtml}</div>
                        </div>

                        <div class="pt-3 border-t border-line space-y-3">
                            <div class="space-y-1">
                                ${feeHtml}
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-normal text-sub tabular-nums">${escapeKasirHtml(ord.created_at_short || ord.created_at_time || '')}</span>
                                    <span class="text-sm font-bold text-ink tabular-nums">${formatRupiah(ord.total)}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">${actionButtonsHtml}</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        /**
         * Modal Ubah Pesanan (Solusi A: revisi pesanan pending saat kurir/pelanggan datang)
         */
        function openEditOrderModal(orderId) {
            const ord = KASIR_STATE.ordersById[orderId] || (KASIR_STATE.recentOrders || []).find(o => Number(o.id) === Number(orderId));
            if (!ord) {
                showKasirToast('Data pesanan tidak ditemukan.');
                return;
            }
            if (!ord.can_edit) {
                showKasirToast('Pesanan ini sudah dibayar atau diproses sehingga tidak dapat diubah.');
                return;
            }

            KASIR_STATE.editOrderDraft = {
                orderId: ord.id,
                code: ord.code,
                customerName: ord.customer_name || 'Pelanggan Kasir',
                isZendo: Boolean(ord.is_zendo),
                serviceMeta: getOrderServiceMeta(ord),
                deliveryAddress: ord.delivery_address || '',
                deliveryFee: Number(ord.delivery_fee || 0),
                originalTotal: Number(ord.total || 0),
                updateUrl: ord.update_items_url || `/kasir/pesanan/${ord.id}/items`,
                items: (ord.items || []).map((it, idx) => ({
                    draftKey: `orig_${idx}_${it.menu_id || 'x'}_${Date.now()}`,
                    menu_id: it.menu_id ?? null,
                    menu_name: it.menu_name,
                    unit_price: Number(it.unit_price || 0),
                    qty: Math.max(1, Number(it.qty || 1)),
                    options: Array.isArray(it.options) ? [...it.options] : [],
                })),
            };

            const titleEl = document.getElementById('editOrderModalTitle');
            const badgeEl = document.getElementById('editOrderModalServiceBadge');
            const subEl = document.getElementById('editOrderModalSubtitle');
            const addrBox = document.getElementById('editOrderAddressBox');
            const addrText = document.getElementById('editOrderAddressText');
            const searchEl = document.getElementById('editOrderCatalogSearch');
            const errBox = document.getElementById('editOrderErrorBox');

            if (titleEl) titleEl.textContent = `Ubah Pesanan #${ord.code}`;
            if (badgeEl) {
                badgeEl.className = `inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md ${KASIR_STATE.editOrderDraft.serviceMeta.badgeClass}`;
                badgeEl.innerHTML = `<i class="fa-solid ${KASIR_STATE.editOrderDraft.serviceMeta.icon} text-[10px]"></i><span>${escapeKasirHtml(KASIR_STATE.editOrderDraft.serviceMeta.label)}</span>`;
            }
            if (subEl) subEl.textContent = ord.customer_name || 'Pelanggan Kasir';
            if (addrBox && addrText) {
                if (ord.is_zendo && ord.delivery_address) {
                    addrText.textContent = ord.delivery_address;
                    addrBox.classList.remove('hidden');
                } else {
                    addrBox.classList.add('hidden');
                }
            }
            if (searchEl) searchEl.value = '';
            if (errBox) {
                errBox.textContent = '';
                errBox.classList.add('hidden');
            }

            renderEditOrderModalItems();
            renderEditOrderCatalog('');
            updateEditOrderSummary();

            const modal = document.getElementById('kasirEditOrderModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeEditOrderModal() {
            KASIR_STATE.editOrderDraft = null;
            const modal = document.getElementById('kasirEditOrderModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function renderEditOrderModalItems() {
            const draft = KASIR_STATE.editOrderDraft;
            const listEl = document.getElementById('editOrderItemsList');
            const countEl = document.getElementById('editOrderItemCountLabel');
            if (!draft || !listEl) return;

            const totalQty = draft.items.reduce((sum, it) => sum + Number(it.qty || 0), 0);
            if (countEl) countEl.textContent = `${totalQty} item`;

            if (draft.items.length === 0) {
                listEl.innerHTML = `
                    <div class="rounded-xl border border-dashed border-line bg-canvas p-4 text-center text-xs text-sub">
                        Belum ada item. Pilih menu pengganti di bawah sebelum menyimpan.
                    </div>
                `;
                return;
            }

            listEl.innerHTML = draft.items.map((it, idx) => {
                const optsText = formatOrderOptionsText(it.options);
                const lineTotal = Number(it.unit_price) * Number(it.qty);
                return `
                    <div class="flex items-center justify-between gap-2 bg-canvas border border-line rounded-xl px-3 py-2.5">
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-bold text-ink truncate">${escapeKasirHtml(it.menu_name)}</div>
                            ${optsText ? `<div class="text-[11px] text-sub truncate">${escapeKasirHtml(optsText)}</div>` : ''}
                            <div class="text-[11px] text-muted tabular-nums mt-0.5">${formatRupiah(it.unit_price)} × ${it.qty} = <strong class="text-ink">${formatRupiah(lineTotal)}</strong></div>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" onclick="adjustEditOrderItemQty(${idx}, -1)" aria-label="Kurangi" class="w-9 h-9 rounded-lg bg-white border border-line text-ink text-xs font-bold flex items-center justify-center cursor-pointer">-</button>
                            <span class="min-w-[22px] text-center text-xs font-bold text-ink tabular-nums">${it.qty}</span>
                            <button type="button" onclick="adjustEditOrderItemQty(${idx}, 1)" aria-label="Tambah" class="w-9 h-9 rounded-lg bg-white border border-line text-ink text-xs font-bold flex items-center justify-center cursor-pointer">+</button>
                            <button type="button" onclick="removeEditOrderItem(${idx})" aria-label="Hapus item" class="w-9 h-9 rounded-lg bg-danger-soft text-danger text-xs flex items-center justify-center cursor-pointer ml-0.5">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function adjustEditOrderItemQty(index, delta) {
            const draft = KASIR_STATE.editOrderDraft;
            if (!draft || !draft.items[index]) return;
            draft.items[index].qty += delta;
            if (draft.items[index].qty <= 0) {
                draft.items.splice(index, 1);
            }
            renderEditOrderModalItems();
            updateEditOrderSummary();
        }

        function removeEditOrderItem(index) {
            const draft = KASIR_STATE.editOrderDraft;
            if (!draft || !draft.items[index]) return;
            draft.items.splice(index, 1);
            renderEditOrderModalItems();
            updateEditOrderSummary();
        }

        function renderEditOrderCatalog(searchQuery) {
            const listEl = document.getElementById('editOrderCatalogList');
            if (!listEl) return;

            const q = String(searchQuery || '').toLowerCase().trim();
            const menus = Object.values(KASIR_CATALOG || {}).filter(m => {
                if (q === '') return true;
                return String(m.name || '').toLowerCase().includes(q)
                    || String(m.sub_category || '').toLowerCase().includes(q);
            });

            if (menus.length === 0) {
                listEl.innerHTML = `<div class="col-span-full text-center text-xs text-muted py-4">Menu tidak ditemukan</div>`;
                return;
            }

            listEl.innerHTML = menus.map(m => {
                const stockVal = Number(KASIR_STATE.stocks[m.id] ?? (m.is_available ? 25 : 0));
                const isAvail = stockVal > 0 && Boolean(m.is_available);
                return `
                    <div class="flex items-center justify-between gap-2 p-2 rounded-xl border border-line bg-white ${!isAvail ? 'opacity-50' : ''}">
                        <div class="flex items-center gap-2 min-w-0">
                            <img src="${escapeKasirHtml(m.image || '')}" alt="" class="w-9 h-9 rounded-lg object-cover bg-canvas shrink-0">
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-ink truncate">${escapeKasirHtml(m.name)}</div>
                                <div class="text-[11px] font-bold text-forest-dark tabular-nums">${formatRupiah(m.price)}</div>
                            </div>
                        </div>
                        <button
                            type="button"
                            onclick="addCatalogMenuToEditOrder(${Number(m.id)})"
                            ${!isAvail ? 'disabled' : ''}
                            class="min-h-[36px] px-3 rounded-lg text-xs font-bold shrink-0 cursor-pointer disabled:cursor-not-allowed ${isAvail ? 'bg-forest-soft text-forest-dark hover:bg-forest hover:text-white' : 'bg-danger-soft text-danger'}"
                        >
                            ${isAvail ? '+ Tambah' : 'Habis'}
                        </button>
                    </div>
                `;
            }).join('');
        }

        function addCatalogMenuToEditOrder(menuId) {
            const draft = KASIR_STATE.editOrderDraft;
            const catalogMenu = KASIR_CATALOG[String(menuId)];
            if (!draft || !catalogMenu) return;

            const existing = draft.items.find(it =>
                Number(it.menu_id) === Number(catalogMenu.id)
                && (!Array.isArray(it.options) || it.options.length === 0)
            );

            if (existing) {
                existing.qty += 1;
            } else {
                draft.items.push({
                    draftKey: `new_${catalogMenu.id}_${Date.now()}`,
                    menu_id: Number(catalogMenu.id),
                    menu_name: catalogMenu.name,
                    unit_price: Number(catalogMenu.price || 0),
                    qty: 1,
                    options: [],
                });
            }

            renderEditOrderModalItems();
            updateEditOrderSummary();
        }

        function updateEditOrderSummary() {
            const draft = KASIR_STATE.editOrderDraft;
            if (!draft) return;

            const subtotal = draft.items.reduce((sum, it) => sum + (Number(it.unit_price || 0) * Number(it.qty || 0)), 0);
            const deliveryFee = Number(draft.deliveryFee || 0);
            const newTotal = subtotal + deliveryFee;
            const diff = newTotal - Number(draft.originalTotal || 0);

            const subEl = document.getElementById('editOrderSubtotalText');
            const feeRow = document.getElementById('editOrderDeliveryFeeRow');
            const feeEl = document.getElementById('editOrderDeliveryFeeText');
            const totalEl = document.getElementById('editOrderTotalText');
            const diffEl = document.getElementById('editOrderDiffBadge');
            const btnSave = document.getElementById('btnSaveEditOrderOnly');
            const btnSavePay = document.getElementById('btnSaveEditOrderAndPay');

            if (subEl) subEl.textContent = formatRupiah(subtotal);
            if (feeRow && feeEl) {
                feeRow.classList.toggle('hidden', deliveryFee <= 0);
                feeEl.textContent = formatRupiah(deliveryFee);
            }
            if (totalEl) totalEl.textContent = formatRupiah(newTotal);

            if (diffEl) {
                if (diff === 0) {
                    diffEl.classList.add('hidden');
                } else {
                    diffEl.classList.remove('hidden');
                    diffEl.textContent = diff > 0 ? `+${formatRupiah(diff)}` : `-${formatRupiah(Math.abs(diff))}`;
                    diffEl.className = diff > 0
                        ? 'text-[11px] font-bold px-2 py-0.5 rounded-md tabular-nums bg-warning-soft text-warning-ink'
                        : 'text-[11px] font-bold px-2 py-0.5 rounded-md tabular-nums bg-forest-soft text-forest-dark';
                }
            }

            const hasItems = draft.items.length > 0;
            if (btnSave) btnSave.disabled = !hasItems;
            if (btnSavePay) btnSavePay.disabled = !hasItems;
        }

        async function submitEditKasirOrder(proceedToPay = false) {
            const draft = KASIR_STATE.editOrderDraft;
            if (!draft || draft.items.length === 0) return;

            const btnSave = document.getElementById('btnSaveEditOrderOnly');
            const btnSavePay = document.getElementById('btnSaveEditOrderAndPay');
            const errBox = document.getElementById('editOrderErrorBox');

            if (btnSave) btnSave.disabled = true;
            if (btnSavePay) btnSavePay.disabled = true;
            if (errBox) errBox.classList.add('hidden');

            try {
                const res = await fetch(draft.updateUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        items: draft.items.map(it => ({
                            menu_id: it.menu_id,
                            menu_name: it.menu_name,
                            unit_price: Number(it.unit_price || 0),
                            qty: Number(it.qty || 1),
                            options: Array.isArray(it.options) ? it.options : [],
                        })),
                        mark_completed_paid: false,
                    }),
                });

                const data = await res.json();
                if (!res.ok || !data.success) {
                    if (errBox) {
                        errBox.textContent = data.message || 'Gagal menyimpan perubahan pesanan.';
                        errBox.classList.remove('hidden');
                    }
                    updateEditOrderSummary();
                    return;
                }

                if (data.stocks) {
                    Object.entries(data.stocks).forEach(([mid, newStock]) => {
                        applyStockToDom(Number(mid), Number(newStock));
                    });
                }
                if (data.stats) {
                    updateKasirHeaderStats(data.stats);
                }
                if (data.order) {
                    upsertKasirOrderInState(data.order);
                    if (KASIR_STATE.activeScannedOrder && Number(KASIR_STATE.activeScannedOrder.id) === Number(data.order.id)) {
                        renderScannedOrderResult(data.order);
                    }
                }

                renderKasirOrdersGrid();
                closeEditOrderModal();
                showKasirToast(data.message || 'Pesanan berhasil diperbarui');

                if (proceedToPay && data.order) {
                    openPayOrderModal(data.order.id);
                } else {
                    syncKasirWithDatabase(false);
                }
            } catch (e) {
                if (errBox) {
                    errBox.textContent = 'Koneksi terganggu. Coba lagi.';
                    errBox.classList.remove('hidden');
                }
                updateEditOrderSummary();
            }
        }

        /**
         * Modal Pembayaran Pintar:
         * - Dine-In / Take Away: pakai metode pilihan pelanggan (QRIS/Transfer langsung tanpa kembalian; Tunai input uang & kembalian)
         * - Kurir Zendo: pilih metode pembayaran kurir terlebih dahulu (Tunai -> hitung kembalian; QRIS/Transfer -> langsung konfirmasi)
         */
        function openPayOrderModal(orderId) {
            const ord = KASIR_STATE.ordersById[orderId] || (KASIR_STATE.recentOrders || []).find(o => Number(o.id) === Number(orderId));
            if (!ord) {
                showKasirToast('Data pesanan tidak ditemukan.');
                return;
            }

            const serviceMeta = getOrderServiceMeta(ord);
            const isZendo = Boolean(ord.is_zendo);
            const payableAmount = Number(ord.payable_amount ?? (isZendo ? ord.subtotal : ord.total) ?? 0);
            const customerMethod = String(ord.payment_method || 'cash').toLowerCase();

            KASIR_STATE.payOrderDraft = {
                orderId: ord.id,
                code: ord.code,
                customerName: ord.customer_name || 'Pelanggan Kasir',
                isZendo,
                payableAmount,
                deliveryFee: Number(ord.delivery_fee || 0),
                customerTotal: Number(ord.total || 0),
                customerChosenMethod: isZendo ? null : customerMethod,
                selectedMethod: isZendo ? null : customerMethod,
                receiptUrl: ord.receipt_url,
            };

            const titleEl = document.getElementById('payOrderModalTitle');
            const badgeEl = document.getElementById('payOrderModalServiceBadge');
            const subEl = document.getElementById('payOrderModalSubtitle');
            const zendoNotice = document.getElementById('payOrderZendoFeeNotice');
            const feeText = document.getElementById('payOrderDeliveryFeeText');
            const custTotalText = document.getElementById('payOrderCustomerTotalText');
            const amtLabel = document.getElementById('payOrderAmountLabel');
            const amtText = document.getElementById('payOrderPayableAmountText');
            const methodHint = document.getElementById('payOrderMethodHint');
            const choiceBadge = document.getElementById('payOrderCustomerChoiceBadge');
            const cashInput = document.getElementById('payOrderCashReceived');
            const errBox = document.getElementById('payOrderErrorBox');

            if (titleEl) titleEl.textContent = `Pembayaran #${ord.code}`;
            if (badgeEl) {
                badgeEl.className = `inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md ${serviceMeta.badgeClass}`;
                badgeEl.innerHTML = `<i class="fa-solid ${serviceMeta.icon} text-[10px]"></i><span>${escapeKasirHtml(serviceMeta.label)}</span>`;
            }
            if (subEl) subEl.textContent = ord.customer_name || 'Pelanggan Kasir';
            if (zendoNotice) {
                zendoNotice.classList.toggle('hidden', !isZendo || Number(ord.delivery_fee || 0) <= 0);
            }
            if (feeText) feeText.textContent = formatRupiah(ord.delivery_fee || 0);
            if (custTotalText) custTotalText.textContent = formatRupiah(ord.total || 0);
            if (amtLabel) amtLabel.textContent = isZendo ? 'Tagihan Menu (Bayar ke Kafe)' : 'Total Tagihan';
            if (amtText) amtText.textContent = formatRupiah(payableAmount);

            if (methodHint) {
                methodHint.textContent = isZendo ? 'Pilih Metode Pembayaran Kurir Zendo' : 'Metode Pembayaran';
            }
            if (choiceBadge) {
                if (!isZendo) {
                    choiceBadge.textContent = `Pilihan pelanggan: ${formatPaymentMethodLabel(customerMethod)}`;
                    choiceBadge.classList.remove('hidden');
                } else {
                    choiceBadge.classList.add('hidden');
                }
            }
            if (cashInput) cashInput.value = '';
            if (errBox) {
                errBox.textContent = '';
                errBox.classList.add('hidden');
            }

            syncPayOrderModalMethodUi();

            const modal = document.getElementById('kasirPayOrderModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closePayOrderModal() {
            KASIR_STATE.payOrderDraft = null;
            const modal = document.getElementById('kasirPayOrderModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function selectPayOrderMethod(method) {
            if (!KASIR_STATE.payOrderDraft) return;
            KASIR_STATE.payOrderDraft.selectedMethod = method;
            const errBox = document.getElementById('payOrderErrorBox');
            if (errBox) errBox.classList.add('hidden');
            syncPayOrderModalMethodUi();
        }

        function syncPayOrderModalMethodUi() {
            const draft = KASIR_STATE.payOrderDraft;
            if (!draft) return;

            const method = draft.selectedMethod;
            document.querySelectorAll('.pay-modal-method-btn').forEach(btn => {
                const m = btn.getAttribute('data-pay-modal-btn');
                btn.className = m === method
                    ? 'pay-modal-method-btn min-h-[48px] px-2 rounded-xl border border-forest bg-forest text-xs font-bold text-white flex flex-col items-center justify-center gap-0.5 cursor-pointer transition-colors'
                    : 'pay-modal-method-btn min-h-[48px] px-2 rounded-xl border border-line bg-canvas text-xs font-bold text-sub flex flex-col items-center justify-center gap-0.5 cursor-pointer transition-colors';
            });

            const cashSection = document.getElementById('payOrderCashSection');
            const nonCashSection = document.getElementById('payOrderNonCashSection');
            const nonCashText = document.getElementById('payOrderNonCashText');
            const qrisWrap = document.getElementById('payOrderQrisPreviewWrap');
            const confirmBtn = document.getElementById('btnConfirmPayOrder');

            if (cashSection) cashSection.classList.toggle('hidden', method !== 'cash');
            if (nonCashSection) nonCashSection.classList.toggle('hidden', method !== 'qris' && method !== 'transfer');

            if (method === 'qris') {
                if (nonCashText) nonCashText.textContent = 'Pembayaran QRIS — tidak perlu hitung kembalian. Pastikan pembayaran QRIS sudah masuk.';
                if (qrisWrap) qrisWrap.classList.toggle('hidden', !KASIR_QRIS_IMAGE_URL);
            } else if (method === 'transfer') {
                if (nonCashText) nonCashText.textContent = 'Pembayaran Transfer — tidak perlu hitung kembalian. Pastikan transfer sudah diterima.';
                if (qrisWrap) qrisWrap.classList.add('hidden');
            }

            if (confirmBtn) {
                if (!method) {
                    confirmBtn.disabled = true;
                    confirmBtn.textContent = 'Pilih Metode Pembayaran';
                } else if (method === 'cash') {
                    confirmBtn.textContent = 'Bayar & Selesai';
                    calculatePayOrderChange();
                    setTimeout(() => document.getElementById('payOrderCashReceived')?.focus(), 50);
                } else {
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = `Konfirmasi ${formatPaymentMethodLabel(method)} & Selesai`;
                }
            }
        }

        function setPayOrderQuickCash(amount) {
            const draft = KASIR_STATE.payOrderDraft;
            const input = document.getElementById('payOrderCashReceived');
            if (!draft || !input) return;

            input.value = amount === 'exact' ? draft.payableAmount : amount;
            calculatePayOrderChange();
        }

        function calculatePayOrderChange() {
            const draft = KASIR_STATE.payOrderDraft;
            const input = document.getElementById('payOrderCashReceived');
            const display = document.getElementById('payOrderChangeDisplay');
            const confirmBtn = document.getElementById('btnConfirmPayOrder');
            if (!draft || !display) return;

            const rawVal = String(input?.value || '').trim();
            const cash = Number(rawVal || 0);

            if (rawVal === '' || cash === 0) {
                display.textContent = 'Rp 0 (Uang Pas)';
                display.className = 'text-base font-bold tabular-nums text-ink';
                if (confirmBtn && draft.selectedMethod === 'cash') confirmBtn.disabled = false;
                return;
            }

            const diff = cash - draft.payableAmount;
            if (diff >= 0) {
                display.textContent = formatRupiah(diff);
                display.className = 'text-base font-bold tabular-nums text-forest-dark';
                if (confirmBtn && draft.selectedMethod === 'cash') confirmBtn.disabled = false;
            } else {
                display.textContent = `Kurang ${formatRupiah(Math.abs(diff))}`;
                display.className = 'text-base font-bold tabular-nums text-danger';
                if (confirmBtn && draft.selectedMethod === 'cash') confirmBtn.disabled = true;
            }
        }

        async function submitPayKasirOrder() {
            const draft = KASIR_STATE.payOrderDraft;
            if (!draft || !draft.selectedMethod) return;

            const confirmBtn = document.getElementById('btnConfirmPayOrder');
            const errBox = document.getElementById('payOrderErrorBox');
            const cashInputVal = String(document.getElementById('payOrderCashReceived')?.value || '').trim();

            const extraPayload = {
                payment_method: draft.selectedMethod,
            };

            if (draft.selectedMethod === 'cash') {
                const cashNum = cashInputVal !== '' ? Number(cashInputVal) : draft.payableAmount;
                if (cashNum < draft.payableAmount) {
                    if (errBox) {
                        errBox.textContent = 'Uang tunai yang diterima kurang dari total tagihan.';
                        errBox.classList.remove('hidden');
                    }
                    return;
                }
                extraPayload.cash_received = cashNum;
            }

            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Memproses...';
            }
            if (errBox) errBox.classList.add('hidden');

            const result = await updateKasirOrderStatus(draft.orderId, 'completed', 'paid', extraPayload);
            if (!result || !result.success) {
                if (errBox) {
                    errBox.textContent = result?.message || 'Gagal menyelesaikan pembayaran.';
                    errBox.classList.remove('hidden');
                }
                syncPayOrderModalMethodUi();
                return;
            }

            const orderCode = result.order?.code || draft.code;
            const customerName = result.order?.customer_name || draft.customerName;
            const paidAmount = Number(result.payable_amount ?? draft.payableAmount);
            const changeAmount = Number(result.change_amount || 0);
            const receiptUrl = result.receipt_url || draft.receiptUrl;

            const isCashPayment = draft.selectedMethod === 'cash';
            const paidOrderObj = result.order || {};
            closePayOrderModal();
            openOrderSuccessModal({
                code: orderCode,
                customerName: customerName,
                total: paidAmount,
                changeAmount: changeAmount,
                receiptUrl: receiptUrl,
                showChange: isCashPayment,
                channel: paidOrderObj.order_channel || (paidOrderObj.is_zendo ? 'zendo' : 'direct'),
                usesBarcode: paidOrderObj.uses_barcode !== undefined ? Boolean(paidOrderObj.uses_barcode) : !Boolean(paidOrderObj.is_zendo),
                barcodeType: paidOrderObj.barcode_type || 'qr',
                qrUrl: KASIR_BASE_QR_URL + orderCode,
            });
        }

        function openCancelOrderConfirm(orderId) {
            const ord = KASIR_STATE.ordersById[orderId] || (KASIR_STATE.recentOrders || []).find(o => Number(o.id) === Number(orderId));
            KASIR_STATE.cancelOrderTargetId = orderId;
            const titleEl = document.getElementById('cancelOrderModalTitle');
            if (titleEl) {
                titleEl.textContent = ord ? `Batalkan Pesanan #${ord.code}?` : 'Batalkan Pesanan?';
            }
            const modal = document.getElementById('kasirCancelOrderModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeCancelOrderConfirm() {
            KASIR_STATE.cancelOrderTargetId = null;
            const modal = document.getElementById('kasirCancelOrderModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        async function executeCancelKasirOrder() {
            const orderId = KASIR_STATE.cancelOrderTargetId;
            if (!orderId) return;
            closeCancelOrderConfirm();
            await updateKasirOrderStatus(orderId, 'cancelled', null);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (KASIR_STATE.payOrderDraft) closePayOrderModal();
                else if (KASIR_STATE.editOrderDraft) closeEditOrderModal();
                else if (KASIR_STATE.cancelOrderTargetId) closeCancelOrderConfirm();
            }
        });

        function updateKasirHeaderStats(stats) {
            if (!stats) return;
            const trxEl = document.getElementById('headerTodayOrdersCount');
            const revEl = document.getElementById('headerTodayRevenue');
            const pendEl = document.getElementById('headerPendingOrdersBadge');
            const mobilePendEl = document.getElementById('mobileBottomPendingBadge');

            if (trxEl && typeof stats.today_orders_count !== 'undefined') {
                trxEl.textContent = `${stats.today_orders_count} Trx`;
            }
            if (revEl && typeof stats.today_revenue !== 'undefined') {
                revEl.textContent = formatRupiah(stats.today_revenue);
            }
            if (pendEl && typeof stats.pending_orders_count !== 'undefined') {
                const pendCount = Number(stats.pending_orders_count) || 0;
                pendEl.textContent = String(pendCount);
                pendEl.classList.toggle('hidden', pendCount === 0);
                pendEl.classList.toggle('inline-flex', pendCount > 0);
                if (mobilePendEl) {
                    mobilePendEl.textContent = String(pendCount);
                    mobilePendEl.classList.toggle('hidden', pendCount === 0);
                    mobilePendEl.classList.toggle('inline-flex', pendCount > 0);
                }
            }
        }

        function setSyncConnectionStatus(isConnected) {
            const dot = document.getElementById('kasirSyncDot');
            const errLabel = document.getElementById('kasirSyncErrorLabel');
            if (dot) {
                dot.className = isConnected
                    ? 'w-2 h-2 rounded-full bg-forest shrink-0'
                    : 'w-2 h-2 rounded-full bg-danger shrink-0';
            }
            if (errLabel) {
                errLabel.classList.toggle('hidden', isConnected);
            }
        }

        async function syncKasirWithDatabase(showNotification = false) {
            try {
                const res = await fetch(@json(route('kasir.sync')), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    setSyncConnectionStatus(true);
                    if (data.stats) {
                        updateKasirHeaderStats(data.stats);
                    }
                    if (data.stocks) {
                        Object.values(data.stocks).forEach(item => {
                            applyStockToDom(Number(item.id), Number(item.stock), Boolean(item.is_available));
                        });
                    }
                    if (Array.isArray(data.recent_orders)) {
                        const newlyArrivedPending = [];
                        data.recent_orders.forEach(ord => {
                            const idNum = Number(ord.id);
                            if (!KASIR_STATE.knownOrderIds.has(idNum)) {
                                KASIR_STATE.knownOrderIds.add(idNum);
                                if (ord.status === 'pending') {
                                    newlyArrivedPending.push(ord);
                                }
                            }
                            KASIR_STATE.ordersById[ord.id] = ord;
                        });

                        KASIR_STATE.recentOrders = data.recent_orders;
                        renderKasirOrdersGrid();

                        if (KASIR_STATE.activeScannedOrder && KASIR_STATE.ordersById[KASIR_STATE.activeScannedOrder.id]) {
                            const refreshedScanned = KASIR_STATE.ordersById[KASIR_STATE.activeScannedOrder.id];
                            if (JSON.stringify(refreshedScanned) !== JSON.stringify(KASIR_STATE.activeScannedOrder)) {
                                renderScannedOrderResult(refreshedScanned);
                            }
                        }

                        if (newlyArrivedPending.length > 0 && !showNotification) {
                            const firstNew = newlyArrivedPending[0];
                            showKasirToast(`Pesanan baru masuk: #${firstNew.code} (${firstNew.service_type_label})`);
                        }
                    }
                    if (showNotification) {
                        showKasirToast('Data kasir tersinkron');
                    }
                } else {
                    setSyncConnectionStatus(false);
                }
            } catch (err) {
                setSyncConnectionStatus(false);
                if (showNotification) {
                    showKasirToast('Tidak tersambung ke server');
                }
            }
        }

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

        function filterKasirOrderChannel(channel) {
            KASIR_STATE.activeOrderChannelFilter = channel || 'all';
            document.querySelectorAll('.kasir-ord-channel-btn').forEach(btn => {
                const key = btn.getAttribute('data-ord-channel-btn');
                const active = key === KASIR_STATE.activeOrderChannelFilter;
                if (key === 'all') {
                    btn.className = active
                        ? 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-forest text-white border-forest'
                        : 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-white text-ink border-line hover:border-forest';
                } else if (key === 'zendo') {
                    btn.className = active
                        ? 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-rose-50 text-ink border-rose-500 ring-1 ring-rose-300'
                        : 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-white text-ink border-line hover:border-rose-400';
                } else if (key === 'grab') {
                    btn.className = active
                        ? 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-emerald-50 text-ink border-[#00B14F] ring-1 ring-emerald-300'
                        : 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-white text-ink border-line hover:border-emerald-500';
                } else {
                    btn.className = active
                        ? 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-forest-soft text-ink border-forest ring-1 ring-forest/30'
                        : 'kasir-ord-channel-btn p-3 rounded-xl border text-left transition-all cursor-pointer flex items-center justify-between gap-2 bg-white text-ink border-line hover:border-forest';
                }
            });
            renderKasirOrdersGrid();
        }

        function filterKasirOrderStatus(status) {
            KASIR_STATE.activeOrderStatus = status;

            document.querySelectorAll('.kasir-ord-filter').forEach(btn => {
                const st = btn.getAttribute('data-ord-filter');
                btn.className = st === KASIR_STATE.activeOrderStatus
                    ? 'kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-bold bg-forest text-white cursor-pointer shrink-0'
                    : 'kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0';
            });

            renderKasirOrdersGrid();
        }

        function filterKasirOrderService(serviceKey) {
            KASIR_STATE.activeOrderService = serviceKey;

            document.querySelectorAll('.kasir-service-filter').forEach(btn => {
                const key = btn.getAttribute('data-service-filter');
                if (key === serviceKey) {
                    btn.className = key === 'zendo'
                        ? 'kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-bold bg-sky-700 text-white border border-sky-700 cursor-pointer shrink-0 inline-flex items-center gap-1.5'
                        : 'kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-bold bg-ink text-white border border-ink cursor-pointer shrink-0 inline-flex items-center gap-1.5';
                } else {
                    btn.className = key === 'zendo'
                        ? 'kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-normal bg-white text-sky-800 border border-sky-200 cursor-pointer shrink-0 inline-flex items-center gap-1.5'
                        : 'kasir-service-filter min-h-[38px] px-3.5 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0 inline-flex items-center gap-1.5';
                }
            });

            renderKasirOrdersGrid();
        }

        function searchKasirOrderRows(q) {
            KASIR_STATE.orderSearchQuery = String(q || '');
            renderKasirOrdersGrid();
        }

        // Attach Global USB / Bluetooth Hardware Barcode Scanner Listener & Initial Orders Render
        document.addEventListener('DOMContentLoaded', () => {
            checkKasirCameraEnvironment();
            if (window.NucomuBarcode) {
                window.NucomuBarcode.attachHardwareScanner({
                    getInput: () => document.getElementById('manualOrderCodeInput'),
                    onScan: (cleanCode) => {
                        if (KASIR_STATE.activeMainTab !== 'scan') {
                            switchKasirMainTab('scan');
                        }
                        const inputEl = document.getElementById('manualOrderCodeInput');
                        if (inputEl) inputEl.value = cleanCode;
                        lookupKasirOrderCode(cleanCode);
                    },
                });
            }
        });

        renderKasirOrdersGrid();

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
