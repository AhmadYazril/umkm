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

    {{-- Camera QR Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

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
        <div class="max-w-[1680px] mx-auto px-4 sm:px-5 py-2 sm:py-0 sm:h-14 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">

            {{-- Kiri: Logo + Titik Status Sinkron + "Nucomu Kasir" --}}
            <div class="flex items-center justify-between gap-2 min-w-0">
                <a href="{{ route('kasir.index') }}" class="flex items-center gap-2.5 min-w-0">
                    <span class="w-8 h-8 rounded-lg bg-forest text-white flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu" class="h-4 w-auto object-contain brightness-0 invert">
                    </span>
                    <span class="relative flex items-center gap-2 min-w-0">
                        <span class="text-sm font-bold text-ink truncate">Nucomu Kasir</span>
                        <span id="kasirSyncDot" class="w-2 h-2 rounded-full bg-forest shrink-0" title="Status sinkronisasi database"></span>
                        <span id="kasirSyncErrorLabel" class="hidden text-xs font-bold text-danger shrink-0">Tidak tersambung</span>
                    </span>
                </a>

                {{-- Aksi Kanan Versi HP --}}
                <div class="flex sm:hidden items-center gap-2 shrink-0">
                    <button
                        type="button"
                        onclick="toggleKasirMoreMenu(event)"
                        aria-label="Menu lainnya"
                        class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl text-sm font-bold text-sub flex items-center justify-center cursor-pointer"
                    >
                        &#8943;
                    </button>
                    <form action="{{ route('kasir.logout') }}" method="POST" class="inline">
                        @csrf
                        <button
                            type="submit"
                            class="min-h-[44px] px-3 rounded-xl text-xs font-bold text-sub cursor-pointer"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tengah: 4 Tab Tanpa Ikon (HP: Menu Kasir & Scan QR paling depan) --}}
            <nav aria-label="Menu Utama Kasir" class="grid grid-cols-2 sm:flex sm:items-center gap-2 shrink-0">
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
                    <div id="kasirCatalogScrollArea" class="lg:flex-1 lg:overflow-y-auto pb-28 lg:pb-4 lg:pr-1 space-y-4">
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
                                        <div class="kasir-menu-grid grid grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-4">
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
                                                    <div class="p-3 flex-1 w-full flex flex-col justify-between gap-2 bg-white">
                                                        <h3 class="text-sm font-bold text-ink leading-snug line-clamp-2">
                                                            {{ $menu['name'] }}
                                                        </h3>

                                                        <div class="flex items-baseline justify-between gap-2">
                                                            <span class="text-sm font-bold text-forest-dark tabular-nums">
                                                                {{ $menu['price_formatted'] }}
                                                            </span>
                                                            @if($hasOptions)
                                                                <span class="text-xs font-normal text-muted shrink-0">+ pilihan</span>
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
                        {{-- Mobile Sheet Top Header --}}
                        <div class="lg:hidden px-4 py-2 border-b border-line flex items-center justify-between bg-white shrink-0">
                            <span class="text-sm font-bold text-ink">Tagihan</span>
                            <button
                                type="button"
                                onclick="closeMobileKasirDrawer()"
                                aria-label="Tutup panel tagihan"
                                class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-xl text-ink flex items-center justify-center cursor-pointer"
                            >
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
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

                            {{-- Makan di Sini / Bawa Pulang (44px, tanpa ikon) --}}
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

                            {{-- Nama Pelanggan & No. Meja Satu Baris Berlabel Jelas --}}
                            <div class="flex items-end gap-3">
                                <div class="flex-1 min-w-0">
                                    <label for="kasirCustomerName" class="block text-xs font-normal text-sub mb-1">Nama pelanggan</label>
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
        <section id="kasirMainPanel-stock" class="{{ $activeTab === 'stock' ? '' : 'hidden' }} space-y-4 pb-8">

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
             TAB 3: SCAN QR (Akan disempurnakan penuh pada Tahap 4)
             ===================================================================== --}}
        <section id="kasirMainPanel-scan" class="{{ $activeTab === 'scan' ? '' : 'hidden' }} pb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

                <div class="lg:col-span-5 bg-white border border-line rounded-xl p-4 sm:p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-ink">Scan QR Pesanan</h2>
                            <p class="text-xs font-normal text-sub">Arahkan kamera ke QR atau ketik kode</p>
                        </div>
                        <span id="cameraScannerBadge" class="text-xs font-bold px-2.5 py-1 rounded-lg bg-canvas text-sub">
                            Siaga
                        </span>
                    </div>

                    <div class="relative rounded-xl overflow-hidden bg-ink aspect-[4/3] flex items-center justify-center">
                        <div id="kasirQrReader" class="w-full h-full"></div>
                        <div id="kasirQrPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 text-white gap-3">
                            <i class="fa-solid fa-camera text-2xl"></i>
                            <button
                                type="button"
                                onclick="startKasirCameraScanner()"
                                class="min-h-[48px] px-5 rounded-xl bg-forest text-white text-xs font-bold transition cursor-pointer"
                            >
                                Aktifkan Kamera
                            </button>
                        </div>
                    </div>

                    <button
                        type="button"
                        id="btnStopCameraScanner"
                        onclick="stopKasirCameraScanner()"
                        class="hidden w-full min-h-[48px] rounded-xl border border-line bg-canvas text-xs font-bold text-ink transition cursor-pointer"
                    >
                        Matikan Kamera
                    </button>

                    <div class="pt-4 border-t border-line space-y-2">
                        <label for="manualOrderCodeInput" class="block text-xs font-normal text-sub">
                            Cari manual kode pesanan
                        </label>
                        <form onsubmit="handleManualQrLookup(event)" class="flex gap-2">
                            <input
                                type="text"
                                id="manualOrderCodeInput"
                                value="{{ request('code') }}"
                                placeholder="Contoh: NCM-20261115-001"
                                class="flex-1 min-h-[48px] bg-canvas border border-line rounded-xl px-3.5 py-2 text-sm font-bold text-ink uppercase placeholder-muted focus:outline-none focus:border-forest focus:bg-white"
                            >
                            <button
                                type="submit"
                                class="min-h-[48px] px-5 rounded-xl bg-forest text-white text-sm font-bold transition cursor-pointer"
                            >
                                Cari
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div id="scannedOrderResultContainer" class="bg-white border border-line rounded-xl p-5 min-h-[360px] flex flex-col justify-center">
                        @if($scannedOrder)
                            <div class="space-y-4">
                                <div class="flex flex-wrap items-start justify-between gap-3 pb-4 border-b border-line">
                                    <div>
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-md {{ $scannedOrder->payment_status === 'paid' ? 'bg-forest-soft text-forest-dark' : 'bg-warning-soft text-warning-ink' }}">
                                            {{ $scannedOrder->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                                        </span>
                                        <h3 class="text-sm font-bold text-ink mt-2 tabular-nums">#{{ $scannedOrder->code }}</h3>
                                        <p class="text-xs font-normal text-sub mt-0.5">
                                            {{ $scannedOrder->customer_name ?: 'Pelanggan Kasir' }}
                                            • {{ $scannedOrder->order_type === 'dine_in' ? 'Makan di Sini' : 'Bawa Pulang' }}
                                        </p>
                                    </div>
                                    <a
                                        href="{{ route('kasir.orders.receipt', $scannedOrder->id) }}"
                                        target="_blank"
                                        class="min-h-[44px] px-4 rounded-xl bg-canvas border border-line text-ink text-xs font-bold flex items-center"
                                    >
                                        Cetak Struk
                                    </a>
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

                                <div class="flex items-center justify-between pt-3 border-t border-line">
                                    <span class="text-xs font-normal text-sub">Total</span>
                                    <span class="text-2xl font-bold text-ink tabular-nums">Rp {{ number_format($scannedOrder->total, 0, ',', '.') }}</span>
                                </div>

                                <div class="pt-4 border-t border-line grid grid-cols-1 sm:grid-cols-3 gap-2">
                                    <button type="button" onclick="updateKasirOrderStatus({{ $scannedOrder->id }}, 'processing', 'paid')" class="min-h-[48px] px-3 rounded-xl border border-line text-xs font-bold text-ink cursor-pointer">
                                        Set Diproses & Lunas
                                    </button>
                                    <button type="button" onclick="updateKasirOrderStatus({{ $scannedOrder->id }}, 'ready', 'paid')" class="min-h-[48px] px-3 rounded-xl border border-line text-xs font-bold text-ink cursor-pointer">
                                        Set Siap Disajikan
                                    </button>
                                    <button type="button" onclick="updateKasirOrderStatus({{ $scannedOrder->id }}, 'completed', 'paid')" class="min-h-[48px] px-3 rounded-xl bg-forest text-white text-xs font-bold cursor-pointer">
                                        Selesaikan Pesanan
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 px-4 space-y-2">
                                <h3 class="text-sm font-bold text-ink">Belum ada QR dipindai</h3>
                                <p class="text-xs font-normal text-sub max-w-sm mx-auto">
                                    Scan QR pelanggan atau ketik kode pesanan untuk melihat detailnya.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        {{-- =====================================================================
             TAB 4: PESANAN (Perbaikan filter, auto-sync & status dikerjakan di Tahap 4)
             ===================================================================== --}}
        <section id="kasirMainPanel-orders" class="{{ $activeTab === 'orders' ? '' : 'hidden' }} space-y-4 pb-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                    <button type="button" onclick="filterKasirOrderStatus('pending')" data-ord-filter="pending" class="kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-bold bg-forest text-white cursor-pointer shrink-0">Pending</button>
                    <button type="button" onclick="filterKasirOrderStatus('processing')" data-ord-filter="processing" class="kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0">Diproses</button>
                    <button type="button" onclick="filterKasirOrderStatus('ready')" data-ord-filter="ready" class="kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0">Siap Saji</button>
                    <button type="button" onclick="filterKasirOrderStatus('completed')" data-ord-filter="completed" class="kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0">Selesai</button>
                </div>

                <input
                    type="search"
                    oninput="searchKasirOrderRows(this.value)"
                    placeholder="Cari kode atau nama pelanggan..."
                    class="w-full sm:w-64 min-h-[44px] bg-white border border-line rounded-xl px-4 py-2 text-sm text-ink placeholder-muted focus:outline-none focus:border-forest"
                >
            </div>

            <div id="kasirOrdersGridContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($recentOrders as $ord)
                    <div
                        class="kasir-order-card bg-white border border-line rounded-xl p-4 flex flex-col justify-between gap-4"
                        data-order-status="{{ $ord->status }}"
                        data-order-search="{{ strtolower($ord->code . ' ' . $ord->customer_name) }}"
                    >
                        <div>
                            <div class="flex items-start justify-between gap-2 pb-3 border-b border-line">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink tabular-nums">#{{ $ord->code }}</span>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $ord->payment_status === 'paid' ? 'bg-forest-soft text-forest-dark' : 'bg-warning-soft text-warning-ink' }}">
                                            {{ $ord->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold text-ink mt-1">
                                        {{ $ord->customer_name ?: 'Pelanggan Kasir' }}
                                        <span class="font-normal text-sub">• {{ $ord->order_type === 'dine_in' ? 'Makan di Sini' : 'Bawa Pulang' }}</span>
                                    </p>
                                </div>
                                <span id="orderCardStatusBadge-{{ $ord->id }}" class="text-xs font-normal px-2.5 py-1 rounded-lg bg-canvas text-ink">
                                    {{ $ord->status_label }}
                                </span>
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
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-normal text-sub tabular-nums">{{ $ord->created_at?->format('d M H:i') }}</span>
                                <span class="text-sm font-bold text-ink tabular-nums">Rp {{ number_format($ord->total, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <select
                                    onchange="updateKasirOrderStatus({{ $ord->id }}, this.value, 'paid')"
                                    class="flex-1 min-h-[44px] bg-canvas border border-line rounded-xl px-3 py-2 text-xs font-bold text-ink focus:outline-none focus:border-forest cursor-pointer"
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
                                    class="min-h-[44px] px-4 rounded-xl bg-forest text-white text-xs font-bold flex items-center"
                                >
                                    Struk
                                </a>
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
    <div id="mobileKasirBottomBar" class="lg:hidden fixed bottom-0 inset-x-0 z-30 p-3 bg-gradient-to-t from-canvas via-canvas/95 to-transparent pointer-events-none {{ $activeTab === 'pos' ? '' : 'hidden' }}">
        <button
            type="button"
            onclick="openMobileKasirDrawer()"
            class="w-full min-h-[56px] bg-forest hover:bg-forest-dark text-white rounded-xl px-4 shadow-lg flex items-center justify-between gap-3 pointer-events-auto cursor-pointer active:scale-[0.99] transition-transform"
        >
            <span id="mobileCartSummaryLabel" class="text-sm font-bold">Lihat pesanan (0 item)</span>
            <span id="mobileCartTotalBadge" class="text-sm font-bold tabular-nums shrink-0">Rp 0</span>
        </button>
    </div>

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

            <div class="bg-canvas rounded-xl p-4 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-sub font-normal">Total</span>
                    <span id="modalSuccessTotalText" class="font-bold text-ink text-sm tabular-nums">Rp 0</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-line">
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

        const KASIR_STATE = {
            cart: [],
            activeMainTab: @json($activeTab),
            activeCategory: @json($initialCatalogTab),
            activeStockCategory: @json($firstCategoryKey),
            stocks: {
                @foreach($catalogItems as $ci)
                    {{ $ci['id'] }}: {{ $ci['is_available'] && (int)$ci['stock'] > 0 ? 25 : 0 }},
                @endforeach
            },
            variantDraft: null,
            mobileCartOpen: false,
            html5QrScanner: null,
            scannerRunning: false,
        };

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

            if (statAvail) statAvail.textContent = String(availCount);
            if (statOut) statOut.textContent = String(outCount);
            if (headerBadge) {
                headerBadge.textContent = String(outCount);
                headerBadge.classList.toggle('hidden', outCount === 0);
                headerBadge.classList.toggle('inline-flex', outCount > 0);
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

            const payload = {
                customer_name: document.getElementById('kasirCustomerName')?.value || '',
                order_type: document.getElementById('kasirOrderType')?.value || 'dine_in',
                table_no: document.getElementById('kasirTableNo')?.value || '',
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

                openOrderSuccessModal(
                    data.order.code,
                    data.order.customer_name,
                    data.order.total,
                    data.order.change_amount || 0,
                    data.receipt_url
                );
                showKasirToast(`Pesanan #${data.order.code} tersimpan`);
                syncKasirWithDatabase(false);
            } catch (err) {
                showKasirToast('Koneksi bermasalah. Coba lagi.');
            } finally {
                if (btnText) btnText.textContent = 'Bayar';
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
                showKasirToast('Kamera belum siap.');
                return;
            }

            if (placeholder) placeholder.classList.add('hidden');
            if (stopBtn) stopBtn.classList.remove('hidden');
            if (badge) badge.textContent = 'Aktif';

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
                if (badge) badge.textContent = 'Gagal';
                showKasirToast('Izinkan akses kamera atau ketik kode manual.');
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
                    <div class="flex items-center justify-between bg-canvas rounded-xl px-3.5 py-2.5 text-xs">
                        <div>
                            <span class="font-bold text-ink">${escapeKasirHtml(i.menu_name)}</span>
                            <span class="text-sub ml-1.5 tabular-nums">x${i.qty}</span>
                        </div>
                        <span class="font-bold text-ink tabular-nums">${formatRupiah(i.line_total)}</span>
                    </div>
                `).join('');

                const isPaid = o.payment_status === 'paid';
                container.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-start justify-between gap-3 pb-4 border-b border-line">
                            <div>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-md ${isPaid ? 'bg-forest-soft text-forest-dark' : 'bg-warning-soft text-warning-ink'}">
                                    ${isPaid ? 'LUNAS' : 'BELUM BAYAR'} (${escapeKasirHtml(o.status_label)})
                                </span>
                                <h3 class="text-sm font-bold text-ink mt-2 tabular-nums">#${escapeKasirHtml(o.code)}</h3>
                                <p class="text-xs font-normal text-sub mt-0.5">${escapeKasirHtml(o.customer_name || 'Pelanggan Kasir')} • ${o.order_type === 'dine_in' ? 'Makan di Sini' : 'Bawa Pulang'}</p>
                            </div>
                            <a href="${data.receipt_url}" target="_blank" class="min-h-[44px] px-4 rounded-xl bg-canvas border border-line text-ink text-xs font-bold flex items-center">
                                Cetak Struk
                            </a>
                        </div>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">${itemsHtml}</div>
                        <div class="flex items-center justify-between pt-3 border-t border-line">
                            <span class="text-xs font-normal text-sub">Total</span>
                            <span class="text-2xl font-bold text-ink tabular-nums">${formatRupiah(o.total)}</span>
                        </div>
                        <div class="pt-4 border-t border-line grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <button type="button" onclick="updateKasirOrderStatus(${o.id}, 'processing', 'paid')" class="min-h-[48px] px-3 rounded-xl border border-line text-xs font-bold text-ink cursor-pointer">Set Diproses & Lunas</button>
                            <button type="button" onclick="updateKasirOrderStatus(${o.id}, 'ready', 'paid')" class="min-h-[48px] px-3 rounded-xl border border-line text-xs font-bold text-ink cursor-pointer">Set Siap Saji</button>
                            <button type="button" onclick="updateKasirOrderStatus(${o.id}, 'completed', 'paid')" class="min-h-[48px] px-3 rounded-xl bg-forest text-white text-xs font-bold cursor-pointer">Selesaikan Pesanan</button>
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
                    showKasirToast(data.message || 'Status pesanan diperbarui');
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
                const pendCount = Number(stats.pending_orders_count) || 0;
                pendEl.textContent = String(pendCount);
                pendEl.classList.toggle('hidden', pendCount === 0);
                pendEl.classList.toggle('inline-flex', pendCount > 0);
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

        function filterKasirOrderStatus(status) {
            document.querySelectorAll('.kasir-ord-filter').forEach(btn => {
                const st = btn.getAttribute('data-ord-filter');
                btn.className = st === status
                    ? 'kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-bold bg-forest text-white cursor-pointer shrink-0'
                    : 'kasir-ord-filter min-h-[44px] px-4 rounded-xl text-xs font-normal bg-white text-sub border border-line cursor-pointer shrink-0';
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
