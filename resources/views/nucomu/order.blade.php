<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu — Nucomu Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Leaflet Interactive Map CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        obsidian: '#0c0c0f',
                        goldAccent: '#f59e0b',
                        violetAccent: '#8b5cf6',
                        cyanAccent: '#06b6d4',
                        roseAccent: '#f43f5e'
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0c0c0f; }
        ::-webkit-scrollbar-thumb { background: #272736; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #f59e0b; }

        .custom-panel-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(245, 158, 11, 0.35) rgba(12, 12, 15, 0.4);
        }
        .custom-panel-scroll::-webkit-scrollbar { width: 5px; }
        .custom-panel-scroll::-webkit-scrollbar-track { background: rgba(12, 12, 15, 0.4); border-radius: 9999px; }
        .custom-panel-scroll::-webkit-scrollbar-thumb { background: rgba(245, 158, 11, 0.35); border-radius: 9999px; }
        .custom-panel-scroll::-webkit-scrollbar-thumb:hover { background: rgba(245, 158, 11, 0.7); }

        .glass-card {
            background: rgba(21, 21, 28, 0.82);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .qty-btn { user-select: none; }
        .menu-card.selected { border-color: rgba(245, 158, 11, 0.7) !important; background: rgba(245, 158, 11, 0.08); }
        #zendoMap { z-index: 10; }
        .leaflet-control-attribution { font-size: 9px !important; opacity: 0.7; }
    </style>
</head>
<body class="bg-obsidian text-gray-100 font-sans min-h-screen relative overflow-x-clip">
    @php
        $transactionMode = $transactionMode ?? 'both';
        $zendoWhatsapp = $zendoWhatsapp ?? '6281234567890';
        $zendoSendMode = $zendoSendMode ?? 'auto_direct';
        $cafeLat = isset($cafeLat) ? (float) $cafeLat : -8.0765099;
        $cafeLng = isset($cafeLng) ? (float) $cafeLng : 111.918712;
        $zendoTariff = $zendoTariff ?? [
            'tier_0_3' => 10000,
            'tier_3_4' => 12000,
            'tier_4_5' => 15000,
            'tier_5_6' => 17000,
            'tier_6_7' => 18000,
            'extra_per_km' => 2500,
        ];
        $initialProcessMethod = $initialProcessMethod ?? ($transactionMode === 'zendo' ? 'zendo' : 'kasir');
    @endphp

    {{-- Ambient Glow --}}
    <div class="fixed top-10 left-1/4 w-96 h-96 rounded-full bg-goldAccent/10 pointer-events-none z-0 blur-3xl"></div>
    <div class="fixed bottom-10 right-10 w-96 h-96 rounded-full bg-violetAccent/10 pointer-events-none z-0 blur-3xl"></div>

    {{-- HEADER --}}
    <header class="sticky top-0 z-50 glass-card border-b border-white/5 px-4 sm:px-6 py-3.5">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-goldAccent via-amber-500 to-rose-500 p-0.5 shadow-lg shadow-goldAccent/20">
                    <div class="w-full h-full bg-obsidian rounded-[10px] flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-goldAccent text-sm"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-tight text-white">NUCOMU</span>
                    <span class="text-[9px] uppercase tracking-widest text-goldAccent font-semibold -mt-1">Coffee & Stories</span>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-white/5 border border-white/10 text-gray-300 hover:text-white text-xs font-semibold transition-all">
                    <i class="fa-solid fa-house text-[10px]"></i> Beranda
                </a>
                <button type="button" onclick="scrollToCheckoutSection()" class="relative px-4 py-2 rounded-full glass-card border border-goldAccent/40 text-goldAccent text-xs font-bold hover:border-goldAccent transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span>Form Pesanan</span>
                    <span id="cartBadge" class="hidden w-5 h-5 rounded-full bg-goldAccent text-black text-[10px] font-black flex items-center justify-center">0</span>
                </button>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 relative z-10 pb-28 lg:pb-12">

        {{-- HERO --}}
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-goldAccent mb-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Buka Sekarang · 08:00 - 23:00
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white mb-2">Pilih <span class="text-goldAccent">Menu</span> & Proses Transaksi</h1>
            <p class="text-gray-400 text-xs sm:text-sm max-w-2xl mx-auto" id="orderHeroSubtitle">
                @if($transactionMode === 'zendo')
                    Pilih menu favorit Anda, tentukan titik alamat pada peta Zendo untuk hitung ongkir otomatis, lalu kirim langsung ke Kurir Zendo.
                @elseif($transactionMode === 'kasir')
                    Pilih menu favorit Anda, isi nama pemesan, lalu tunjukkan slip QR ke kasir.
                @else
                    Pilih menu favorit Anda di kiri dan atur detail pesanan di panel kanan (bisa digeser langsung tanpa harus menunggu daftar menu habis).
                @endif
            </p>
        </div>

        {{-- MOBILE QUICK JUMP BAR (agar di HP tidak perlu scroll habis seluruh menu untuk ke form pesanan) --}}
        <div class="lg:hidden sticky top-[62px] z-40 mb-5 bg-[#121218]/95 backdrop-blur-xl p-1.5 rounded-2xl border border-white/10 grid grid-cols-2 gap-1.5 shadow-xl">
            <button type="button" id="mobileJumpMenuBtn" onclick="scrollToMenuSection()"
                class="py-2.5 px-3 rounded-xl text-xs font-extrabold uppercase tracking-wider bg-goldAccent text-black transition-all flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-utensils text-[11px]"></i>
                <span>1. Pilih Menu</span>
            </button>
            <button type="button" id="mobileJumpFormBtn" onclick="scrollToCheckoutSection()"
                class="py-2.5 px-3 rounded-xl text-xs font-extrabold uppercase tracking-wider bg-white/5 text-gray-300 border border-white/10 transition-all flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-map-location-dot text-emerald-400 text-[11px]"></i>
                <span>2. Detail & Checkout</span>
                <span id="mobileTabBadge" class="hidden px-1.5 py-0.2 rounded-full bg-emerald-400 text-black text-[10px] font-black">0</span>
            </button>
        </div>

        {{-- MAIN 2-COLUMN RESPONSIVE GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-7 items-start">

            {{-- LEFT COLUMN: MENU CATALOG (7 cols on lg, 8 cols on xl) --}}
            <div class="lg:col-span-7 xl:col-span-7" id="menuCatalogSection">

                {{-- CATEGORY FILTER --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-2.5 mb-5 no-scrollbar">
                    <button onclick="filterCategory('all')" data-cat="all"
                        class="cat-btn active px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap border border-goldAccent bg-goldAccent text-black transition-all">
                        Semua Menu
                    </button>
                    @foreach($categories as $cat)
                        <button onclick="filterCategory('{{ $cat->slug }}')" data-cat="{{ $cat->slug }}"
                            class="cat-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap border border-white/10 text-gray-400 glass-card transition-all hover:border-goldAccent/50 hover:text-white">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>

                {{-- MENU CARDS GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4" id="menuGrid">
                    @forelse($menus as $menu)
                        <div class="menu-card glass-card rounded-2xl overflow-hidden border border-white/10 transition-all duration-200 hover:border-white/25 flex flex-col"
                             data-cat="{{ $menu->category?->slug ?? 'other' }}"
                             data-id="{{ $menu->id }}"
                             data-name="{{ $menu->name }}"
                             data-price="{{ $menu->price }}">

                            {{-- Image --}}
                            <div class="relative h-40 sm:h-44 bg-zinc-900 overflow-hidden">
                                @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @elseif($menu->image && (str_starts_with($menu->image, 'http://') || str_starts_with($menu->image, 'https://')))
                                    <img src="{{ $menu->image }}" alt="{{ $menu->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <i class="fa-solid fa-mug-hot text-4xl opacity-30"></i>
                                    </div>
                                @endif

                                @if($menu->is_featured)
                                    <span class="absolute top-2 left-2 bg-goldAccent text-black text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full">
                                        Unggulan
                                    </span>
                                @endif
                                @if($menu->is_best_seller)
                                    <span class="absolute top-2 right-2 bg-rose-500 text-white text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full">
                                        Best Seller
                                    </span>
                                @endif

                                {{-- Category --}}
                                <div class="absolute bottom-2 left-2">
                                    <span class="bg-black/70 backdrop-blur text-gray-300 text-[10px] px-2 py-0.5 rounded-full border border-white/10">
                                        {{ $menu->category?->name ?? 'Menu' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-bold text-white text-sm mb-1">{{ $menu->name }}</h3>
                                @if($menu->description)
                                    <p class="text-gray-400 text-xs leading-relaxed line-clamp-2 flex-1">{{ $menu->description }}</p>
                                @endif

                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-goldAccent font-black text-base font-mono">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </span>
                                    <button type="button" onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                                            class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-goldAccent text-black text-xs font-bold hover:bg-amber-400 active:scale-95 transition-all">
                                        <i class="fa-solid fa-plus text-xs"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16 text-gray-500">
                            <i class="fa-solid fa-mug-hot text-4xl opacity-20 mb-3 block"></i>
                            <p class="text-sm">Belum ada menu tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT COLUMN: INDEPENDENTLY SCROLLABLE ORDER & ZENDO MAP PANEL (5 cols on lg, 5 cols on xl) --}}
            <div class="lg:col-span-5 xl:col-span-5 lg:sticky lg:top-20" id="orderCheckoutSection">
                <div class="lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto custom-panel-scroll lg:pr-1.5 space-y-4">

                    {{-- Mobile Back-to-Menu Header --}}
                    <div class="lg:hidden flex items-center justify-between bg-white/5 border border-white/10 rounded-2xl px-4 py-2.5">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-goldAccent flex items-center gap-1.5">
                            <i class="fa-solid fa-clipboard-check"></i> Formulir & Rincian Pesanan
                        </span>
                        <button type="button" onclick="scrollToMenuSection()" class="text-[11px] font-bold text-gray-300 hover:text-white bg-white/10 px-2.5 py-1 rounded-lg">
                            ↑ Tambah Menu Lain
                        </button>
                    </div>

                    {{-- 1. Process Method Selector (Kasir / Zendo / Both) --}}
                    <div class="glass-card rounded-2xl p-4 sm:p-5 border border-white/10" id="processMethodCard">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xs sm:text-sm font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-goldAccent"></i> 1. Metode Proses Transaksi
                            </h3>
                            @if($transactionMode === 'both')
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-goldAccent/15 text-goldAccent border border-goldAccent/30">
                                    Kasir / Zendo
                                </span>
                            @endif
                        </div>

                        @if($transactionMode === 'both')
                            <div class="grid grid-cols-2 gap-2.5">
                                <button type="button"
                                        id="methodBtnKasir"
                                        onclick="setProcessMethod('kasir')"
                                        class="p-3 rounded-xl border text-left transition-all flex flex-col justify-between {{ $initialProcessMethod === 'kasir' ? 'border-goldAccent bg-goldAccent/15 text-white' : 'border-white/10 bg-black/40 text-gray-400 hover:border-white/25' }}">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <i class="fa-solid fa-qrcode text-sm {{ $initialProcessMethod === 'kasir' ? 'text-goldAccent' : 'text-gray-400' }}" id="iconMethodKasir"></i>
                                        <span class="w-2 h-2 rounded-full {{ $initialProcessMethod === 'kasir' ? 'bg-goldAccent' : 'bg-white/20' }}" id="dotMethodKasir"></span>
                                    </div>
                                    <span class="text-xs font-extrabold block leading-tight">Proses Kasir</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Slip & QR Kasir</span>
                                </button>

                                <button type="button"
                                        id="methodBtnZendo"
                                        onclick="setProcessMethod('zendo')"
                                        class="p-3 rounded-xl border text-left transition-all flex flex-col justify-between {{ $initialProcessMethod === 'zendo' ? 'border-emerald-400 bg-emerald-500/15 text-white' : 'border-white/10 bg-black/40 text-gray-400 hover:border-white/25' }}">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <i class="fa-solid fa-motorcycle text-sm {{ $initialProcessMethod === 'zendo' ? 'text-emerald-400' : 'text-gray-400' }}" id="iconMethodZendo"></i>
                                        <span class="w-2 h-2 rounded-full {{ $initialProcessMethod === 'zendo' ? 'bg-emerald-400' : 'bg-white/20' }}" id="dotMethodZendo"></span>
                                    </div>
                                    <span class="text-xs font-extrabold block leading-tight">Kurir Zendo</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block">Peta & Kirim Otomatis</span>
                                </button>
                            </div>
                        @elseif($transactionMode === 'zendo')
                            <div class="p-3.5 rounded-xl border border-emerald-500/40 bg-emerald-500/10 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-motorcycle text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-white">Delivery Kurir Zendo</p>
                                    <p class="text-[10px] text-gray-300">Pilih titik alamat di peta, ongkir dihitung otomatis & terkirim ke Zendo.</p>
                                </div>
                            </div>
                        @else
                            <div class="p-3.5 rounded-xl border border-goldAccent/40 bg-goldAccent/10 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-goldAccent/20 text-goldAccent flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-qrcode text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-white">Transaksi Kasir (QR Slip)</p>
                                    <p class="text-[10px] text-gray-300">Pilih menu lalu tunjukkan slip QR ke kasir.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 2. Cart Items & Live Cost Breakdown --}}
                    <div class="glass-card rounded-2xl p-4 sm:p-5 border border-white/10">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-xs sm:text-sm font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-bag-shopping text-cyanAccent"></i> 2. Daftar Menu Pesanan
                            </h3>
                            <span id="panelItemCountBadge" class="text-[10px] font-mono font-bold text-gray-400">0 Item</span>
                        </div>

                        <div id="cartItems" class="space-y-2.5 max-h-48 overflow-y-auto custom-panel-scroll pr-1 min-h-[68px]">
                            <div id="emptyCart" class="text-center py-5 text-gray-500">
                                <i class="fa-solid fa-bag-shopping text-xl opacity-30 block mb-1.5"></i>
                                <p class="text-xs">Belum ada menu dipilih. Klik <strong>+ Tambah</strong> pada menu.</p>
                            </div>
                        </div>

                        {{-- Subtotal + Ongkir Zendo + Grand Total --}}
                        <div id="cartTotal" class="hidden border-t border-white/10 pt-3 mt-3 space-y-1.5">
                            <div class="flex justify-between text-xs text-gray-400">
                                <span>Subtotal Menu</span>
                                <span id="subtotalAmount" class="font-mono text-gray-200 font-semibold">Rp 0</span>
                            </div>

                            <div id="zendoFeeRow" class="flex justify-between text-xs text-emerald-400 {{ $initialProcessMethod === 'zendo' ? '' : 'hidden' }}">
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-motorcycle text-[10px]"></i>
                                    <span>Ongkir Zendo (<span id="cartDistanceText">1,5 km</span>)</span>
                                </span>
                                <span id="deliveryFeeAmount" class="font-mono font-bold">Rp 10.000</span>
                            </div>

                            <div class="flex justify-between items-center text-sm pt-2 border-t border-white/10">
                                <span class="font-bold text-white">Total Pembayaran</span>
                                <span id="totalAmount" class="font-mono text-lg text-goldAccent font-black">Rp 0</span>
                            </div>

                            <p id="cartPaymentHint" class="text-[10px] text-gray-400 pt-0.5">
                                {{ $initialProcessMethod === 'zendo' ? '*Pesanan & titik lokasi peta langsung dikirim otomatis ke kontak Kurir Zendo.' : '*Bayar di kasir setelah menunjukkan slip QR.' }}
                            </p>
                        </div>
                    </div>

                    {{-- 3. Customer Details & Zendo Interactive Map --}}
                    <div class="glass-card rounded-2xl p-4 sm:p-5 border border-white/10 space-y-4">
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-white mb-2.5 flex items-center gap-2">
                                <i class="fa-solid fa-user text-goldAccent"></i> 3. Data Pemesan & Lokasi
                            </h3>
                            <label for="customerName" class="block text-[11px] font-semibold text-gray-300 mb-1">Nama Pemesan <span class="text-rose-400">*</span></label>
                            <input type="text" id="customerName" placeholder="Masukkan nama lengkap Anda..."
                                class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-goldAccent transition-colors">
                        </div>

                        {{-- ZENDO INTERACTIVE MAP & DISTANCE CALCULATOR --}}
                        @php
                            $availableCouriers = $zendoCouriers ?? [
                                ['index' => 1, 'name' => 'Kurir Zendo 1', 'phone' => $zendoWhatsapp ?? '6281234567890', 'clean_phone' => $zendoWhatsapp ?? '6281234567890']
                            ];
                        @endphp
                        <div id="zendoFieldsWrap" class="space-y-3.5 pt-3 border-t border-white/10 {{ $initialProcessMethod === 'zendo' ? '' : 'hidden' }}">
                            {{-- Info Pembagian Kurir Zendo Otomatis (Acak yang Standby / Hampir Selesai) --}}
                            <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-3.5 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                                        <i class="fa-solid fa-motorcycle"></i> Pengiriman Otomatis Kurir Zendo
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[10px] font-mono font-bold">
                                        {{ count($availableCouriers) }} Kurir Siaga
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-300 leading-relaxed">
                                    Pesanan Anda (tanpa bukti struk) akan langsung diteruskan secara otomatis ke nomor WhatsApp kurir Zendo yang sedang kosong/standby atau yang pengirimannya hampir selesai.
                                </p>
                            </div>

                            <div>
                                <label for="customerPhone" class="block text-[11px] font-semibold text-emerald-400 mb-1">
                                    <i class="fa-brands fa-whatsapp mr-1"></i> Nomor WhatsApp Anda (Untuk Konfirmasi Kurir)
                                </label>
                                <input type="text" id="customerPhone" placeholder="Contoh: 081234567890"
                                    class="w-full bg-black/50 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white font-mono placeholder-gray-500 focus:outline-none focus:border-emerald-400 transition-colors">
                            </div>

                            {{-- Map Search & GPS Controls --}}
                            <div class="bg-black/40 border border-emerald-500/30 rounded-2xl p-3.5 space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                                        <i class="fa-solid fa-map-location-dot"></i> Peta Alamat & Cari Daerah (Zendo)
                                    </span>
                                    <button type="button" onclick="useMyGpsLocation()" id="gpsBtn"
                                        class="px-2.5 py-1 rounded-lg bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/30 text-emerald-300 text-[10px] font-bold transition-all flex items-center gap-1">
                                        <i class="fa-solid fa-location-crosshairs"></i>
                                        <span>GPS Saya</span>
                                    </button>
                                </div>

                                {{-- Search Input Daerah / Jalan --}}
                                <div class="relative">
                                    <div class="flex gap-1.5">
                                        <div class="relative flex-1">
                                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                            <input type="text" id="mapSearchInput"
                                                placeholder="Cari daerah / desa / jalan di Tulungagung..."
                                                onkeydown="if(event.key==='Enter'){event.preventDefault();searchMapLocation();}"
                                                class="w-full bg-zinc-950 border border-white/15 rounded-xl pl-8 pr-3 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-400">
                                        </div>
                                        <button type="button" onclick="searchMapLocation()" id="mapSearchBtn"
                                            class="px-3.5 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-black font-extrabold text-xs transition-all shrink-0">
                                            Cari
                                        </button>
                                    </div>

                                    {{-- Dropdown Hasil Pencarian Daerah --}}
                                    <div id="mapSearchResults" class="hidden mt-1.5 bg-zinc-950 border border-emerald-500/40 rounded-xl overflow-hidden shadow-2xl max-h-44 overflow-y-auto divide-y divide-white/5 z-30 relative"></div>
                                </div>

                                {{-- Quick Daerah Chips Tulungagung --}}
                                <div>
                                    <span class="text-[10px] text-gray-400 block mb-1.5">Pilih Cepat Area Tulungagung (Klik untuk set peta):</span>
                                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar">
                                        <button type="button" onclick="selectPresetArea('Plosokandang, Kedungwaru, Tulungagung', -8.0748, 111.9225)"
                                            class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/40 text-[10px] font-semibold text-gray-300 whitespace-nowrap transition-all">
                                            Plosokandang (~0.8 km)
                                        </button>
                                        <button type="button" onclick="selectPresetArea('Kedungwaru, Tulungagung', -8.0675, 111.9082)"
                                            class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/40 text-[10px] font-semibold text-gray-300 whitespace-nowrap transition-all">
                                            Kedungwaru (~2.1 km)
                                        </button>
                                        <button type="button" onclick="selectPresetArea('Aloon-Aloon Tulungagung, Pusat Kota', -8.0657, 111.9025)"
                                            class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/40 text-[10px] font-semibold text-gray-300 whitespace-nowrap transition-all">
                                            Aloon-Aloon (~3.3 km)
                                        </button>
                                        <button type="button" onclick="selectPresetArea('Boyolangu, Tulungagung', -8.0995, 111.8965)"
                                            class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/40 text-[10px] font-semibold text-gray-300 whitespace-nowrap transition-all">
                                            Boyolangu (~4.5 km)
                                        </button>
                                        <button type="button" onclick="selectPresetArea('Kauman, Tulungagung', -8.0590, 111.8730)"
                                            class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/40 text-[10px] font-semibold text-gray-300 whitespace-nowrap transition-all">
                                            Kauman (~5.6 km)
                                        </button>
                                        <button type="button" onclick="selectPresetArea('Sumbergempol, Tulungagung', -8.0970, 111.9585)"
                                            class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/40 text-[10px] font-semibold text-gray-300 whitespace-nowrap transition-all">
                                            Sumbergempol (~6.4 km)
                                        </button>
                                        <button type="button" onclick="selectPresetArea('Ngunut, Tulungagung', -8.1225, 112.0120)"
                                            class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-emerald-500/20 border border-white/10 hover:border-emerald-500/40 text-[10px] font-semibold text-gray-300 whitespace-nowrap transition-all">
                                            Ngunut (~12 km)
                                        </button>
                                    </div>
                                </div>

                                {{-- Interactive Leaflet Map Container --}}
                                <div class="relative rounded-xl overflow-hidden border border-white/15">
                                    <div id="zendoMap" class="w-full h-52 sm:h-56 bg-zinc-900"></div>
                                    <div class="bg-zinc-950/90 px-3 py-1.5 text-[10px] text-gray-400 flex items-center justify-between border-t border-white/10">
                                        <span><i class="fa-solid fa-hand-pointer text-emerald-400 mr-1"></i> Klik peta atau geser pin hijau ke lokasi Anda</span>
                                        <span id="coordBadge" class="font-mono text-gray-500">-8.075, 111.922</span>
                                    </div>
                                </div>

                                {{-- Live Distance & Zendo Tariff Calculation Box --}}
                                <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-300 block">Jarak Kafe ke Lokasi</span>
                                            <span id="distanceBadgeText" class="text-sm font-black text-white font-mono">1,5 km</span>
                                        </div>
                                        <div class="text-right">
                                            <span id="tariffBracketLabel" class="text-[10px] uppercase tracking-wider font-bold text-emerald-300 block">Tarif Zendo (0–3 km)</span>
                                            <span id="tariffFeeBadgeText" class="text-base font-black text-emerald-400 font-mono">Rp {{ number_format($zendoTariff['tier_0_3'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    {{-- Toggle Tabel Tarif Zendo --}}
                                    <div class="pt-1.5 border-t border-emerald-500/20">
                                        <button type="button" onclick="document.getElementById('zendoTariffTableInfo').classList.toggle('hidden')"
                                            class="w-full flex items-center justify-between text-[10px] font-bold text-emerald-300 hover:text-white transition-colors">
                                            <span><i class="fa-solid fa-circle-info mr-1"></i> Lihat Rincian Tarif Jarak Zendo</span>
                                            <i class="fa-solid fa-chevron-down text-[9px]"></i>
                                        </button>
                                        <div id="zendoTariffTableInfo" class="hidden mt-2 pt-2 border-t border-white/10 text-[10px] space-y-1 font-mono text-gray-300">
                                            <div class="flex justify-between"><span>0 – 3 km</span><span class="text-emerald-400 font-bold">Rp {{ number_format($zendoTariff['tier_0_3'], 0, ',', '.') }}</span></div>
                                            <div class="flex justify-between"><span>3,1 – 4 km</span><span class="text-emerald-400 font-bold">Rp {{ number_format($zendoTariff['tier_3_4'], 0, ',', '.') }}</span></div>
                                            <div class="flex justify-between"><span>4,1 – 5 km</span><span class="text-emerald-400 font-bold">Rp {{ number_format($zendoTariff['tier_4_5'], 0, ',', '.') }}</span></div>
                                            <div class="flex justify-between"><span>5,1 – 6 km</span><span class="text-emerald-400 font-bold">Rp {{ number_format($zendoTariff['tier_5_6'], 0, ',', '.') }}</span></div>
                                            <div class="flex justify-between"><span>6,1 – 7 km</span><span class="text-emerald-400 font-bold">Rp {{ number_format($zendoTariff['tier_6_7'], 0, ',', '.') }}</span></div>
                                            <div class="flex justify-between"><span>Di atas 7 km</span><span class="text-amber-400 font-bold">+Rp {{ number_format($zendoTariff['extra_per_km'], 0, ',', '.') }} / km</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail Alamat Pengiriman --}}
                            <div>
                                <label for="deliveryAddress" class="block text-[11px] font-semibold text-emerald-400 mb-1">
                                    <i class="fa-solid fa-location-dot mr-1"></i> Detail Alamat Pengiriman & Patokan <span class="text-rose-400">*</span>
                                </label>
                                <textarea id="deliveryAddress" rows="2" placeholder="Alamat otomatis terisi dari peta, tambahkan nomor rumah / warna pagar / patokan..."
                                    class="w-full bg-black/50 border border-white/15 rounded-xl p-3 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-400 transition-colors resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Notes --}}
                    <div class="glass-card rounded-2xl p-4 sm:p-5 border border-white/10">
                        <h3 class="text-xs sm:text-sm font-bold text-white mb-2.5 flex items-center gap-2">
                            <i class="fa-solid fa-note-sticky text-violetAccent"></i> 4. Catatan Pesanan (Opsional)
                        </h3>
                        <textarea id="orderNotes" rows="2" placeholder="Contoh: less sugar, es dipisah, extra hot..."
                            class="w-full bg-black/50 border border-white/15 rounded-xl p-3 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-goldAccent transition-colors resize-none"></textarea>
                    </div>

                    {{-- 5. Submit Button --}}
                    <button type="button" onclick="submitOrder()" id="submitBtn"
                        class="w-full py-4 rounded-2xl {{ $initialProcessMethod === 'zendo' ? 'bg-gradient-to-r from-emerald-500 via-emerald-400 to-green-400 shadow-emerald-500/20' : 'bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 shadow-goldAccent/20' }} text-black font-black text-sm uppercase tracking-wider hover:opacity-95 active:scale-98 transition-all shadow-lg disabled:opacity-50">
                        @if($initialProcessMethod === 'zendo')
                            <i class="fa-solid fa-paper-plane mr-2"></i> Pesan Sekarang (Otomatis ke Zendo)
                        @else
                            <i class="fa-solid fa-qrcode mr-2"></i> Buat Slip Pesanan (Kasir)
                        @endif
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- FLOATING ADDED NOTIFICATION (TOAST) --}}
    <div id="addedToast" class="fixed top-20 right-4 z-50 glass-card bg-[#15151c]/95 border border-goldAccent/40 shadow-2xl rounded-2xl px-4 py-3 flex items-center gap-3 transition-all duration-300 opacity-0 translate-y-4 pointer-events-none">
        <div class="w-8 h-8 rounded-full bg-goldAccent/20 flex items-center justify-center text-goldAccent">
            <i class="fa-solid fa-check text-xs"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-white"><span id="toastItemName">Menu</span> ditambahkan!</p>
            <p class="text-[10px] text-gray-400">Klik "Detail & Checkout" untuk lanjut memesan.</p>
        </div>
    </div>

    {{-- STICKY MOBILE BOTTOM BAR (langsung mengarahkan ke form pesanan di HP tanpa harus scroll semua menu) --}}
    <div id="mobileBottomBar" class="lg:hidden fixed bottom-0 left-0 right-0 z-40 glass-card bg-[#15151c]/95 border-t border-goldAccent/30 p-3.5 px-4 flex items-center justify-between shadow-2xl translate-y-full transition-transform duration-300">
        <div>
            <span id="mobileBarCount" class="text-[10px] uppercase font-bold text-gray-400 block font-mono">0 Item</span>
            <span id="mobileBarTotal" class="text-base font-black text-goldAccent font-mono">Rp 0</span>
        </div>
        <button type="button" onclick="scrollToCheckoutSection()" class="px-5 py-2.5 rounded-xl bg-goldAccent text-black font-black text-xs uppercase tracking-wider shadow-lg shadow-goldAccent/20 flex items-center gap-1.5">
            <span>Lanjut Checkout</span>
            <i class="fa-solid fa-arrow-down text-[11px]"></i>
        </button>
    </div>

    {{-- NOTIFIKASI PESANAN ZENDO TERKIRIM KE WHATSAPP (TANPA BUKTI STRUK) --}}
    <div id="zendoDirectSuccessModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/85 backdrop-blur-md">
        <div class="glass-card bg-[#15151c] border border-emerald-500/40 rounded-3xl max-w-sm w-full p-6 sm:p-7 shadow-2xl space-y-5 relative animate-fade-in text-center">
            <div class="space-y-2.5">
                <div class="w-16 h-16 rounded-full bg-emerald-500/20 border-2 border-emerald-400 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                    <i class="fa-brands fa-whatsapp text-emerald-400 text-3xl"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-[10px] font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Terkirim Otomatis ke WhatsApp
                </span>
                <h3 class="text-lg font-black text-white">Pesanan Terkirim ke WhatsApp Zendo!</h3>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Pesanan & titik lokasi Anda telah langsung dikirim otomatis ke WhatsApp <strong id="modalCourierName" class="text-emerald-400">Kurir Zendo</strong> (<span id="modalZendoContact" class="font-mono text-emerald-400 font-bold"></span>).
                </p>
            </div>

            <button type="button" onclick="closeZendoSuccessModal()"
                class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 via-emerald-400 to-green-400 text-black font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 hover:opacity-95 transition-all">
                <i class="fa-solid fa-check"></i>
                <span>Selesai</span>
            </button>
        </div>
    </div>

    <script>
        // ============================================
        // CONFIG & STATE
        // ============================================
        const adminTransactionMode = '{{ $transactionMode }}';
        const zendoSendMode = '{{ $zendoSendMode }}';
        const cafeLat = {{ $cafeLat }};
        const cafeLng = {{ $cafeLng }};
        const zendoTariff = @json($zendoTariff);
        const zendoCouriers = @json($availableCouriers);

        let currentProcessMethod = '{{ $initialProcessMethod }}';
        let selectedCourierIndex = (zendoCouriers[0] && zendoCouriers[0].index) ? zendoCouriers[0].index : 1;
        let selectedCourierName = (zendoCouriers[0] && zendoCouriers[0].name) ? zendoCouriers[0].name : 'Kurir Zendo 1';
        let selectedCourierPhone = (zendoCouriers[0] && zendoCouriers[0].clean_phone) ? zendoCouriers[0].clean_phone : '{{ $zendoWhatsapp }}';
        let cart = {}; // { menuId: { name, price, qty } }

        // Map & Distance State
        let zendoMap = null;
        let cafeMarker = null;
        let customerMarker = null;
        let routeLine = null;
        let customerLat = -8.0748;
        let customerLng = 111.9225;
        let currentDistanceKm = 1.5;
        let currentDeliveryFee = zendoTariff.tier_0_3 || 10000;

        // Preset Daerah Tulungagung untuk Pencarian Cepat & Akurat
        const tulungagungAreas = [
            { name: 'Plosokandang, Kec. Kedungwaru, Tulungagung', lat: -8.0748, lng: 111.9225 },
            { name: 'Kedungwaru, Kabupaten Tulungagung', lat: -8.0675, lng: 111.9082 },
            { name: 'Mangunsari, Kec. Kedungwaru, Tulungagung', lat: -8.0598, lng: 111.9055 },
            { name: 'Ketanon, Kec. Kedungwaru, Tulungagung', lat: -8.0492, lng: 111.9088 },
            { name: 'Tapan, Kec. Kedungwaru, Tulungagung', lat: -8.0395, lng: 111.9125 },
            { name: 'Rejoagung, Kec. Kedungwaru, Tulungagung', lat: -8.0570, lng: 111.9145 },
            { name: 'Aloon-Aloon Tulungagung / Kampungdalem', lat: -8.0657, lng: 111.9025 },
            { name: 'Jepun, Kec. Tulungagung', lat: -8.0725, lng: 111.9045 },
            { name: 'Bago, Kec. Tulungagung', lat: -8.0752, lng: 111.9070 },
            { name: 'Kepatihan, Kec. Tulungagung', lat: -8.0680, lng: 111.9095 },
            { name: 'Kenayan, Kec. Tulungagung', lat: -8.0615, lng: 111.9058 },
            { name: 'Sembung, Kec. Tulungagung', lat: -8.0790, lng: 111.8965 },
            { name: 'Boyolangu, Kabupaten Tulungagung', lat: -8.0995, lng: 111.8965 },
            { name: 'Sobontoro, Kec. Boyolangu, Tulungagung', lat: -8.0865, lng: 111.8995 },
            { name: 'Beji, Kec. Boyolangu, Tulungagung', lat: -8.0835, lng: 111.9050 },
            { name: 'Serut, Kec. Boyolangu, Tulungagung', lat: -8.0920, lng: 111.9120 },
            { name: 'Kauman, Kabupaten Tulungagung', lat: -8.0590, lng: 111.8730 },
            { name: 'Bolorejo, Kec. Kauman, Tulungagung', lat: -8.0630, lng: 111.8820 },
            { name: 'Sumbergempol, Kabupaten Tulungagung', lat: -8.0970, lng: 111.9585 },
            { name: 'Wonorejo, Kec. Sumbergempol, Tulungagung', lat: -8.0890, lng: 111.9440 },
            { name: 'Ngunut, Kabupaten Tulungagung', lat: -8.1225, lng: 112.0120 },
            { name: 'Rejotangan, Kabupaten Tulungagung', lat: -8.1330, lng: 112.0620 },
            { name: 'Ngantru, Kabupaten Tulungagung', lat: -8.0120, lng: 111.9460 },
            { name: 'Gondang, Kabupaten Tulungagung', lat: -8.0820, lng: 111.8480 },
            { name: 'Karangrejo, Kabupaten Tulungagung', lat: -8.0210, lng: 111.8690 },
            { name: 'Campurdarat, Kabupaten Tulungagung', lat: -8.1650, lng: 111.8550 },
            { name: 'Bandung, Kabupaten Tulungagung', lat: -8.1690, lng: 111.7850 },
            { name: 'Pakel, Kabupaten Tulungagung', lat: -8.1410, lng: 111.8250 },
            { name: 'Kalidawir, Kabupaten Tulungagung', lat: -8.1750, lng: 111.9680 },
        ];

        // ============================================
        // ZENDO DISTANCE & TARIFF CALCULATION (JAN 2025)
        // ============================================
        function calculateZendoFee(distanceKm) {
            const d = Math.max(0, Math.round(distanceKm * 10) / 10);
            const t03 = parseInt(zendoTariff.tier_0_3 || 10000);
            const t34 = parseInt(zendoTariff.tier_3_4 || 12000);
            const t45 = parseInt(zendoTariff.tier_4_5 || 15000);
            const t56 = parseInt(zendoTariff.tier_5_6 || 17000);
            const t67 = parseInt(zendoTariff.tier_6_7 || 18000);
            const extra = parseInt(zendoTariff.extra_per_km || 2500);

            if (d <= 3.0) {
                return { fee: t03, label: 'Tarif Zendo (0–3 km)' };
            }
            if (d <= 4.0) {
                return { fee: t34, label: 'Tarif Zendo (3,1–4 km)' };
            }
            if (d <= 5.0) {
                return { fee: t45, label: 'Tarif Zendo (4,1–5 km)' };
            }
            if (d <= 6.0) {
                return { fee: t56, label: 'Tarif Zendo (5,1–6 km)' };
            }
            if (d <= 7.0) {
                return { fee: t67, label: 'Tarif Zendo (6,1–7 km)' };
            }

            const extraKm = Math.ceil(d - 7.0);
            return {
                fee: t67 + (extraKm * extra),
                label: `Tarif >7 km (+${extraKm} km)`
            };
        }

        function computeHaversineRoadDistanceKm(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const straightKm = R * c;
            // Multiply by 1.25 typical urban road factor as instant fallback
            return Math.max(0.3, Math.round(straightKm * 1.25 * 10) / 10);
        }

        // ============================================
        // LEAFLET INTERACTIVE MAP INITIALIZATION
        // ============================================
        function initZendoMap() {
            if (zendoMap || typeof L === 'undefined') return;
            const mapEl = document.getElementById('zendoMap');
            if (!mapEl) return;

            zendoMap = L.map('zendoMap', {
                center: [cafeLat, cafeLng],
                zoom: 14,
                zoomControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(zendoMap);

            // Cafe Custom Icon
            const cafeIcon = L.divIcon({
                className: 'custom-cafe-pin',
                html: `<div style="background:#f59e0b;color:#000;width:30px;height:30px;border-radius:9999px;display:flex;align-items:center;justify-content:center;border:2px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,0.5);font-size:13px;"><i class="fa-solid fa-mug-hot"></i></div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            // Customer Custom Icon
            const customerIcon = L.divIcon({
                className: 'custom-cust-pin',
                html: `<div style="background:#10b981;color:#000;width:34px;height:34px;border-radius:9999px;display:flex;align-items:center;justify-content:center;border:3px solid #fff;box-shadow:0 4px 14px rgba(16,185,129,0.6);font-size:15px;"><i class="fa-solid fa-location-dot"></i></div>`,
                iconSize: [34, 34],
                iconAnchor: [17, 30]
            });

            cafeMarker = L.marker([cafeLat, cafeLng], { icon: cafeIcon })
                .addTo(zendoMap)
                .bindPopup('<b>Nucomu Cafe (Titik Asal)</b><br>Jl. Mayor Sujadi, Plosokandang');

            customerMarker = L.marker([customerLat, customerLng], {
                icon: customerIcon,
                draggable: true
            }).addTo(zendoMap).bindPopup('<b>Lokasi Pengiriman Anda</b><br>Geser pin ini sesuai titik rumah Anda');

            routeLine = L.polyline([[cafeLat, cafeLng], [customerLat, customerLng]], {
                color: '#10b981',
                weight: 3,
                dashArray: '6, 6'
            }).addTo(zendoMap);

            // Click on map to move customer pin
            zendoMap.on('click', function(e) {
                setCustomerMapPin(e.latlng.lat, e.latlng.lng, null, true);
            });

            // Drag customer pin
            customerMarker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                setCustomerMapPin(pos.lat, pos.lng, null, true);
            });

            updateDistanceAndTariffUI(customerLat, customerLng);
        }

        function setCustomerMapPin(lat, lng, labelText = null, reverseGeocode = false) {
            customerLat = parseFloat(lat.toFixed(6));
            customerLng = parseFloat(lng.toFixed(6));

            if (!zendoMap) {
                initZendoMap();
            }
            if (customerMarker) {
                customerMarker.setLatLng([customerLat, customerLng]);
            }
            if (routeLine) {
                routeLine.setLatLngs([[cafeLat, cafeLng], [customerLat, customerLng]]);
            }
            if (zendoMap) {
                zendoMap.fitBounds([[cafeLat, cafeLng], [customerLat, customerLng]], {
                    padding: [35, 35],
                    maxZoom: 16
                });
            }

            const coordBadge = document.getElementById('coordBadge');
            if (coordBadge) {
                coordBadge.innerText = `${customerLat.toFixed(4)}, ${customerLng.toFixed(4)}`;
            }

            const addressEl = document.getElementById('deliveryAddress');
            if (labelText && addressEl) {
                addressEl.value = labelText;
                addressEl.classList.remove('border-rose-500');
            } else if (reverseGeocode && addressEl) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${customerLat}&lon=${customerLng}&zoom=18&addressdetails=1`, {
                    headers: { 'Accept-Language': 'id' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data && data.display_name) {
                        addressEl.value = data.display_name;
                        addressEl.classList.remove('border-rose-500');
                    }
                })
                .catch(() => {
                    if (!addressEl.value.trim()) {
                        addressEl.value = `Titik Peta (${customerLat.toFixed(5)}, ${customerLng.toFixed(5)}), Tulungagung`;
                    }
                });
            }

            updateDistanceAndTariffUI(customerLat, customerLng);
        }

        function updateDistanceAndTariffUI(lat, lng) {
            // 1. Instant fallback road distance
            const fallbackKm = computeHaversineRoadDistanceKm(cafeLat, cafeLng, lat, lng);
            applyDistanceKm(fallbackKm);

            // 2. Try OSRM road routing for exact driving distance
            fetch(`https://router.project-osrm.org/route/v1/driving/${cafeLng},${cafeLat};${lng},${lat}?overview=false`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.routes && data.routes[0] && typeof data.routes[0].distance === 'number') {
                        const exactKm = Math.max(0.2, Math.round((data.routes[0].distance / 1000) * 10) / 10);
                        applyDistanceKm(exactKm);
                    }
                })
                .catch(() => {
                    // Keep Haversine road distance
                });
        }

        function applyDistanceKm(km) {
            currentDistanceKm = km;
            const calc = calculateZendoFee(currentDistanceKm);
            currentDeliveryFee = calc.fee;

            const formattedKm = currentDistanceKm.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' km';
            const formattedFee = 'Rp ' + currentDeliveryFee.toLocaleString('id-ID');

            const distBadge = document.getElementById('distanceBadgeText');
            const bracketEl = document.getElementById('tariffBracketLabel');
            const feeBadge = document.getElementById('tariffFeeBadgeText');
            const cartDist = document.getElementById('cartDistanceText');
            const cartFee = document.getElementById('deliveryFeeAmount');

            if (distBadge) distBadge.innerText = formattedKm;
            if (bracketEl) bracketEl.innerText = calc.label;
            if (feeBadge) feeBadge.innerText = formattedFee;
            if (cartDist) cartDist.innerText = formattedKm;
            if (cartFee) cartFee.innerText = formattedFee;

            renderCart();
        }

        function selectPresetArea(name, lat, lng) {
            const resultsEl = document.getElementById('mapSearchResults');
            if (resultsEl) resultsEl.classList.add('hidden');
            const searchInput = document.getElementById('mapSearchInput');
            if (searchInput) searchInput.value = name;
            setCustomerMapPin(lat, lng, name, false);
        }

        function searchMapLocation() {
            const input = document.getElementById('mapSearchInput');
            const resultsEl = document.getElementById('mapSearchResults');
            const btn = document.getElementById('mapSearchBtn');
            if (!input || !resultsEl) return;

            const query = input.value.trim();
            if (!query) {
                input.focus();
                return;
            }

            const qLower = query.toLowerCase();
            const localMatches = tulungagungAreas.filter(a => a.name.toLowerCase().includes(qLower));

            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            resultsEl.innerHTML = '';
            resultsEl.classList.remove('hidden');

            // Render local presets immediately if matched
            localMatches.forEach(item => {
                const km = computeHaversineRoadDistanceKm(cafeLat, cafeLng, item.lat, item.lng);
                const feeObj = calculateZendoFee(km);
                const btnItem = document.createElement('button');
                btnItem.type = 'button';
                btnItem.className = 'w-full text-left px-3.5 py-2.5 hover:bg-emerald-500/15 transition-colors flex items-center justify-between gap-2';
                btnItem.innerHTML = `
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate"><i class="fa-solid fa-location-dot text-emerald-400 mr-1.5"></i>${item.name}</p>
                        <p class="text-[10px] text-gray-400">Estimasi jarak ~${km.toFixed(1)} km dari Nucomu Cafe</p>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-emerald-400 shrink-0">Rp ${feeObj.fee.toLocaleString('id-ID')}</span>
                `;
                btnItem.onclick = () => selectPresetArea(item.name, item.lat, item.lng);
                resultsEl.appendChild(btnItem);
            });

            // Also search Nominatim OpenStreetMap
            const searchQuery = query.toLowerCase().includes('tulungagung') ? query : `${query}, Tulungagung`;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&countrycodes=id&limit=5`, {
                headers: { 'Accept-Language': 'id' }
            })
            .then(r => r.json())
            .then(data => {
                btn.innerText = 'Cari';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(place => {
                        const pLat = parseFloat(place.lat);
                        const pLng = parseFloat(place.lon);
                        const km = computeHaversineRoadDistanceKm(cafeLat, cafeLng, pLat, pLng);
                        const feeObj = calculateZendoFee(km);

                        const btnItem = document.createElement('button');
                        btnItem.type = 'button';
                        btnItem.className = 'w-full text-left px-3.5 py-2.5 hover:bg-emerald-500/15 transition-colors flex items-center justify-between gap-2';
                        btnItem.innerHTML = `
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-200 truncate"><i class="fa-solid fa-map-pin text-amber-400 mr-1.5"></i>${place.display_name}</p>
                                <p class="text-[10px] text-gray-400">Jarak ~${km.toFixed(1)} km</p>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-emerald-400 shrink-0">Rp ${feeObj.fee.toLocaleString('id-ID')}</span>
                        `;
                        btnItem.onclick = () => selectPresetArea(place.display_name, pLat, pLng);
                        resultsEl.appendChild(btnItem);
                    });
                } else if (localMatches.length === 0) {
                    resultsEl.innerHTML = `<div class="p-3 text-center text-xs text-gray-400">Lokasi tidak ditemukan. Silakan klik langsung pada peta di bawah.</div>`;
                }
            })
            .catch(() => {
                btn.innerText = 'Cari';
                if (localMatches.length === 0) {
                    resultsEl.innerHTML = `<div class="p-3 text-center text-xs text-gray-400">Silakan pilih area di atas atau klik titik lokasi pada peta.</div>`;
                }
            });
        }

        function useMyGpsLocation() {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung fitur GPS.');
                return;
            }
            const gpsBtn = document.getElementById('gpsBtn');
            if (gpsBtn) gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencari...';

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    if (gpsBtn) gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> GPS Saya';
                    setCustomerMapPin(pos.coords.latitude, pos.coords.longitude, null, true);
                },
                () => {
                    if (gpsBtn) gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> GPS Saya';
                    alert('Tidak dapat mengakses GPS. Silakan cari nama daerah atau klik pada peta.');
                },
                { enableHighAccuracy: true, timeout: 7000 }
            );
        }

        // ============================================
        // TRANSACTION PROCESS METHOD STATE (KASIR / ZENDO)
        // ============================================
        function setProcessMethod(method) {
            if (adminTransactionMode === 'kasir') {
                currentProcessMethod = 'kasir';
            } else if (adminTransactionMode === 'zendo') {
                currentProcessMethod = 'zendo';
            } else {
                currentProcessMethod = (method === 'zendo') ? 'zendo' : 'kasir';
            }

            const btnKasir = document.getElementById('methodBtnKasir');
            const btnZendo = document.getElementById('methodBtnZendo');
            const iconKasir = document.getElementById('iconMethodKasir');
            const iconZendo = document.getElementById('iconMethodZendo');
            const dotKasir = document.getElementById('dotMethodKasir');
            const dotZendo = document.getElementById('dotMethodZendo');
            const zendoFields = document.getElementById('zendoFieldsWrap');
            const zendoFeeRow = document.getElementById('zendoFeeRow');
            const paymentHint = document.getElementById('cartPaymentHint');

            const isZendo = currentProcessMethod === 'zendo';

            if (btnKasir && btnZendo) {
                btnKasir.className = 'p-3 rounded-xl border text-left transition-all flex flex-col justify-between ' +
                    (!isZendo ? 'border-goldAccent bg-goldAccent/15 text-white' : 'border-white/10 bg-black/40 text-gray-400 hover:border-white/25');
                btnZendo.className = 'p-3 rounded-xl border text-left transition-all flex flex-col justify-between ' +
                    (isZendo ? 'border-emerald-400 bg-emerald-500/15 text-white' : 'border-white/10 bg-black/40 text-gray-400 hover:border-white/25');
            }
            if (iconKasir) {
                iconKasir.className = 'fa-solid fa-qrcode text-sm ' + (!isZendo ? 'text-goldAccent' : 'text-gray-400');
            }
            if (iconZendo) {
                iconZendo.className = 'fa-solid fa-motorcycle text-sm ' + (isZendo ? 'text-emerald-400' : 'text-gray-400');
            }
            if (dotKasir) {
                dotKasir.className = 'w-2 h-2 rounded-full ' + (!isZendo ? 'bg-goldAccent' : 'bg-white/20');
            }
            if (dotZendo) {
                dotZendo.className = 'w-2 h-2 rounded-full ' + (isZendo ? 'bg-emerald-400' : 'bg-white/20');
            }

            if (zendoFields) {
                zendoFields.classList.toggle('hidden', !isZendo);
            }
            if (zendoFeeRow) {
                zendoFeeRow.classList.toggle('hidden', !isZendo);
            }
            if (paymentHint) {
                paymentHint.innerText = isZendo
                    ? '*Pesanan & titik lokasi peta langsung dikirim otomatis ke kontak Kurir Zendo.'
                    : '*Bayar di kasir setelah menunjukkan slip QR.';
            }

            if (isZendo) {
                setTimeout(() => {
                    initZendoMap();
                    if (zendoMap) zendoMap.invalidateSize();
                }, 120);
            }

            renderCart();
            resetSubmitButton();
        }

        function resetSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            if (!submitBtn) return;
            submitBtn.disabled = false;

            if (currentProcessMethod === 'zendo') {
                submitBtn.className = 'w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-emerald-400 to-green-400 text-black font-black text-sm uppercase tracking-wider hover:opacity-95 active:scale-98 transition-all shadow-lg shadow-emerald-500/20 disabled:opacity-50';
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane mr-2"></i> Pesan Sekarang (Otomatis ke Zendo)';
            } else {
                submitBtn.className = 'w-full py-4 rounded-2xl bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 text-black font-black text-sm uppercase tracking-wider hover:opacity-95 active:scale-98 transition-all shadow-lg shadow-goldAccent/20 disabled:opacity-50';
                submitBtn.innerHTML = '<i class="fa-solid fa-qrcode mr-2"></i> Buat Slip Pesanan (Kasir)';
            }
        }

        // ============================================
        // CART STATE & TOTAL CALCULATION
        // ============================================
        function addToCart(id, name, price) {
            if (cart[id]) {
                cart[id].qty += 1;
            } else {
                cart[id] = { name, price, qty: 1 };
            }
            renderCart();
            showAddedToast(name);
        }

        function showAddedToast(itemName) {
            const toast = document.getElementById('addedToast');
            const toastText = document.getElementById('toastItemName');
            if (toast && toastText) {
                toastText.innerText = itemName;
                toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                clearTimeout(window.toastTimer);
                window.toastTimer = setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                }, 2200);
            }
        }

        function updateQty(id, delta) {
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) {
                delete cart[id];
            }
            renderCart();
        }

        function renderCart() {
            const cartItemsEl = document.getElementById('cartItems');
            const emptyEl = document.getElementById('emptyCart');
            const totalEl = document.getElementById('cartTotal');
            const badge = document.getElementById('cartBadge');
            const mobileTabBadge = document.getElementById('mobileTabBadge');
            const panelCountBadge = document.getElementById('panelItemCountBadge');
            const mobileBar = document.getElementById('mobileBottomBar');
            const mobileTotalEl = document.getElementById('mobileBarTotal');
            const mobileCountEl = document.getElementById('mobileBarCount');
            const keys = Object.keys(cart);

            if (keys.length === 0) {
                if (emptyEl) emptyEl.classList.remove('hidden');
                if (totalEl) totalEl.classList.add('hidden');
                if (badge) badge.classList.add('hidden');
                if (mobileTabBadge) mobileTabBadge.classList.add('hidden');
                if (panelCountBadge) panelCountBadge.innerText = '0 Item';
                if (mobileBar) mobileBar.classList.add('translate-y-full');
                if (cartItemsEl) {
                    cartItemsEl.innerHTML = '';
                    if (emptyEl) cartItemsEl.appendChild(emptyEl);
                }
                return;
            }

            if (emptyEl) emptyEl.classList.add('hidden');
            if (totalEl) totalEl.classList.remove('hidden');
            if (mobileBar) mobileBar.classList.remove('translate-y-full');

            let html = '';
            let subtotal = 0;
            let totalQty = 0;

            keys.forEach(id => {
                const item = cart[id];
                const lineTotal = item.price * item.qty;
                subtotal += lineTotal;
                totalQty += item.qty;

                html += `
                <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                    <div class="flex-1 min-w-0 pr-2">
                        <p class="text-xs font-bold text-white truncate">${item.name}</p>
                        <p class="text-[10px] text-gray-400 font-mono">Rp ${item.price.toLocaleString('id-ID')} × ${item.qty} = <span class="text-goldAccent font-bold">Rp ${lineTotal.toLocaleString('id-ID')}</span></p>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" onclick="updateQty(${id}, -1)" class="qty-btn w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs flex items-center justify-center font-bold transition-colors">−</button>
                        <span class="text-xs font-bold text-white w-5 text-center font-mono">${item.qty}</span>
                        <button type="button" onclick="updateQty(${id}, 1)" class="qty-btn w-6 h-6 rounded-full bg-goldAccent hover:bg-amber-400 text-black text-xs flex items-center justify-center font-bold transition-colors">+</button>
                    </div>
                </div>`;
            });

            if (cartItemsEl) cartItemsEl.innerHTML = html;

            const isZendo = currentProcessMethod === 'zendo';
            const activeDeliveryFee = isZendo ? currentDeliveryFee : 0;
            const grandTotal = subtotal + activeDeliveryFee;

            const subtotalEl = document.getElementById('subtotalAmount');
            const totalElAmount = document.getElementById('totalAmount');

            if (subtotalEl) subtotalEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (totalElAmount) totalElAmount.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            if (mobileTotalEl) mobileTotalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            if (mobileCountEl) mobileCountEl.innerText = isZendo ? `${totalQty} Item (+Ongkir Zendo)` : `${totalQty} Item`;
            if (panelCountBadge) panelCountBadge.innerText = `${totalQty} Item`;

            if (badge) {
                badge.innerText = totalQty;
                badge.classList.remove('hidden');
            }
            if (mobileTabBadge) {
                mobileTabBadge.innerText = totalQty;
                mobileTabBadge.classList.remove('hidden');
            }
        }

        // ============================================
        // SMOOTH NAVIGATION FOR MOBILE & LAPTOP
        // ============================================
        function scrollToCheckoutSection() {
            const checkoutEl = document.getElementById('orderCheckoutSection');
            if (checkoutEl) {
                checkoutEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            if (currentProcessMethod === 'zendo') {
                setTimeout(() => {
                    initZendoMap();
                    if (zendoMap) zendoMap.invalidateSize();
                }, 250);
            }
        }

        function scrollToMenuSection() {
            const menuEl = document.getElementById('menuCatalogSection');
            if (menuEl) {
                menuEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // ============================================
        // CATEGORY FILTER
        // ============================================
        function filterCategory(cat) {
            document.querySelectorAll('.cat-btn').forEach(btn => {
                const isActive = btn.dataset.cat === cat;
                btn.classList.toggle('bg-goldAccent', isActive);
                btn.classList.toggle('text-black', isActive);
                btn.classList.toggle('border-goldAccent', isActive);
                btn.classList.toggle('text-gray-400', !isActive);
                btn.classList.toggle('border-white/10', !isActive);
            });

            document.querySelectorAll('.menu-card').forEach(card => {
                if (cat === 'all' || card.dataset.cat === cat) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // ============================================
        // ZENDO COURIER SELECTOR
        // ============================================
        function selectZendoCourier(idx, name, phone) {
            selectedCourierIndex = parseInt(idx, 10) || 1;
            selectedCourierName = name || `Kurir Zendo ${selectedCourierIndex}`;
            selectedCourierPhone = phone || '';

            document.querySelectorAll('.zendo-courier-btn').forEach(btn => {
                const isSelected = parseInt(btn.dataset.courierIdx, 10) === selectedCourierIndex;
                btn.className = 'zendo-courier-btn p-2.5 rounded-xl border text-left transition-all flex items-center justify-between gap-2 ' +
                    (isSelected ? 'border-emerald-400 bg-emerald-500/20 text-white' : 'border-white/10 bg-black/40 text-gray-300 hover:border-emerald-500/40');
                const dot = btn.querySelector('.courier-check-dot');
                if (dot) {
                    dot.className = 'courier-check-dot w-2.5 h-2.5 rounded-full shrink-0 ' + (isSelected ? 'bg-emerald-400' : 'bg-white/15');
                }
            });
        }

        // ============================================
        // SUBMIT ORDER (LANGSUNG KE WA KURIR ZENDO TANPA STRUK / SLIP KASIR)
        // ============================================
        function submitOrder() {
            const nameEl = document.getElementById('customerName');
            const phoneEl = document.getElementById('customerPhone');
            const addressEl = document.getElementById('deliveryAddress');
            const notesEl = document.getElementById('orderNotes');

            const name = nameEl ? nameEl.value.trim() : '';
            const phone = phoneEl ? phoneEl.value.trim() : '';
            const deliveryAddress = addressEl ? addressEl.value.trim() : '';
            const notes = notesEl ? notesEl.value.trim() : '';
            const keys = Object.keys(cart);

            if (keys.length === 0) {
                alert('Pilih setidaknya satu menu terlebih dahulu.');
                scrollToMenuSection();
                return;
            }
            if (!name) {
                scrollToCheckoutSection();
                if (nameEl) {
                    nameEl.focus();
                    nameEl.classList.add('border-rose-500');
                }
                alert('Masukkan nama pemesan terlebih dahulu.');
                return;
            }
            if (currentProcessMethod === 'zendo' && !deliveryAddress) {
                scrollToCheckoutSection();
                if (addressEl) {
                    addressEl.focus();
                    addressEl.classList.add('border-rose-500');
                }
                alert('Pilih titik lokasi pada peta atau masukkan alamat pengiriman terlebih dahulu.');
                return;
            }

            const items = keys.map(id => ({
                menu_id: parseInt(id),
                menu_name: cart[id].name,
                unit_price: cart[id].price,
                qty: cart[id].qty,
                line_total: cart[id].price * cart[id].qty,
            }));

            const subtotal = items.reduce((s, i) => s + i.line_total, 0);
            const total = currentProcessMethod === 'zendo' ? (subtotal + currentDeliveryFee) : subtotal;

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = currentProcessMethod === 'zendo'
                ? '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengirim ke WhatsApp ' + selectedCourierName + '...'
                : '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Membuat Slip Pesanan...';

            fetch('{{ route("nucomu.order.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    customer_name: name,
                    customer_phone: phone,
                    delivery_address: deliveryAddress,
                    distance_km: currentDistanceKm,
                    customer_lat: customerLat,
                    customer_lng: customerLng,
                    courier_index: selectedCourierIndex,
                    process_method: currentProcessMethod,
                    notes: notes,
                    items: items,
                    total: total
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (data.process_method === 'zendo') {
                        // Untuk Zendo: tidak menggunakan bukti struk, langsung terkirim ke WhatsApp Kurir Zendo
                        cart = {};
                        renderCart();
                        resetSubmitButton();

                        if (data.auto_sent) {
                            showZendoDirectSuccessModal(data);
                        } else if (data.whatsapp_url) {
                            window.location.href = data.whatsapp_url;
                        }
                    } else {
                        window.location.href = data.slip_url;
                    }
                } else {
                    alert(data.message || 'Terjadi kesalahan.');
                    resetSubmitButton();
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi.');
                resetSubmitButton();
            });
        }

        function showZendoDirectSuccessModal(data) {
            const modal = document.getElementById('zendoDirectSuccessModal');
            if (!modal) return;

            const courierNameEl = document.getElementById('modalCourierName');
            const contactEl = document.getElementById('modalZendoContact');

            if (courierNameEl) {
                courierNameEl.innerText = data.courier_name || selectedCourierName || 'Kurir Zendo';
            }
            if (contactEl) {
                contactEl.innerText = '+' + (data.zendo_contact || selectedCourierPhone || '{{ $zendoWhatsapp }}');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeZendoSuccessModal() {
            const modal = document.getElementById('zendoDirectSuccessModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Initialize state & pre-select menu if ?add= is passed in URL
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('customerName');
            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    this.classList.remove('border-rose-500');
                });
            }

            const addrInput = document.getElementById('deliveryAddress');
            if (addrInput) {
                addrInput.addEventListener('input', function() {
                    this.classList.remove('border-rose-500');
                });
            }

            setProcessMethod(currentProcessMethod);

            const params = new URLSearchParams(window.location.search);
            const preAddMenu = params.get('add');
            if (preAddMenu) {
                const targetLower = preAddMenu.trim().toLowerCase();
                const cards = document.querySelectorAll('.menu-card');
                for (const card of cards) {
                    const cardName = (card.dataset.name || '').trim().toLowerCase();
                    if (cardName && (cardName === targetLower || cardName.includes(targetLower) || targetLower.includes(cardName))) {
                        addToCart(parseInt(card.dataset.id), card.dataset.name, parseInt(card.dataset.price));
                        break;
                    }
                }
            }
        });
    </script>
</body>
</html>
