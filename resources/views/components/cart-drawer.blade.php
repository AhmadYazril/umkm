<!-- =========================================================================
     NUCOMU SLIDE-OVER CART DRAWER & CHECKOUT ENGINE
     Palette: Warm Monochrome (Deep Espresso #2c2825, Warm Stone #ded8ce, Krem #faf8f5, Pure White #ffffff, Peach Accent #e8b49a)
     Features:
       - Right-side slide-over drawer (~30% screen, max 420px, 90% mobile)
       - Smooth 300ms ease-in-out slide & backdrop blur
       - Floating white capsule pill in bottom-right (appears when cart > 0)
       - Item list with quantity stepper (- 1 +), option chips, trash delete
       - Subtotal, Promo (green), and Final Total with lining-nums
       - Integrated Checkout supporting Modul 8 (Kasir & Zendo Courier)
       - Full accessibility: focus trap, Esc to close, aria-live announcer, prefers-reduced-motion
     ========================================================================= -->

@php
    $txSettings = $txSettings ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? (new \App\Http\Controllers\NucomuController())->getTransactionSettings() : []);
    $orderModeDineIn = (bool) ($txSettings['order_mode_dine_in'] ?? true);
    $orderModeZendoTracking = (bool) ($txSettings['order_mode_zendo_tracking'] ?? false);
    $orderModeZendoChat = (bool) ($txSettings['order_mode_zendo_chat'] ?? false);
    $orderModeZendo = $orderModeZendoTracking || $orderModeZendoChat;
    $isAnyOrderModeActive = (bool) ($txSettings['is_any_mode_active'] ?? ($orderModeDineIn || $orderModeZendo));
    $orderDineInCash = (bool) ($txSettings['order_dine_in_cash'] ?? true);
    $orderDineInQris = (bool) ($txSettings['order_dine_in_qris'] ?? true);
    $qrisImage = $txSettings['qris_image'] ?? null;
    $transactionMode = $transactionMode ?? ($txSettings['transaction_mode'] ?? 'both');
    $zendoWhatsapp = $zendoWhatsapp ?? ($txSettings['zendo_whatsapp_clean'] ?? '6281234567890');
    $cafeLat = (float) ($txSettings['cafe_lat'] ?? -8.0765099);
    $cafeLng = (float) ($txSettings['cafe_lng'] ?? 111.918712);
    $zendoTariff = $txSettings['zendo_tariff'] ?? [
        'tier_0_3' => 10000,
        'tier_3_4' => 12000,
        'tier_4_5' => 15000,
        'tier_5_6' => 17000,
        'tier_6_7' => 18000,
        'extra_per_km' => 2500,
    ];
    $zendoCouriers = $txSettings['zendo_couriers'] ?? [
        ['index' => 1, 'name' => 'Kurir Zendo 1', 'phone' => $zendoWhatsapp, 'clean_phone' => $zendoWhatsapp]
    ];
    $initialProcessMethod = ($orderModeDineIn) ? 'kasir' : ($orderModeZendo ? 'zendo' : 'kasir');
    $hideLiveTracking = (bool) ($txSettings['hide_live_tracking'] ?? false);
@endphp

<style>
    :root {
        --menu-avatar-border: #FFFFFF;
        --menu-avatar-shadow: 0 6px 16px rgba(0, 0, 0, 0.14), 0 14px 28px -6px rgba(0, 0, 0, 0.16);
        --menu-avatar-hover-shadow: 0 18px 36px -6px rgba(0, 0, 0, 0.24);
        --menu-card-hover-shadow: 0 22px 44px -12px rgba(0, 0, 0, 0.16);
        --menu-card-open-shadow: 0 26px 52px -12px rgba(0, 0, 0, 0.20);
        --floating-cart-hover-shadow: 0 18px 40px -8px rgba(0, 0, 0, 0.22);
    }

    [data-theme="dark"],
    .dark {
        --menu-avatar-border: #1E1E1E;
        --menu-avatar-shadow: 0 6px 16px rgba(0, 0, 0, 0.55), 0 16px 32px -4px rgba(0, 0, 0, 0.65);
        --menu-avatar-hover-shadow: 0 22px 42px rgba(0, 0, 0, 0.75);
        --menu-card-hover-shadow: 0 24px 54px rgba(0, 0, 0, 0.72);
        --menu-card-open-shadow: 0 28px 60px rgba(0, 0, 0, 0.78);
        --floating-cart-hover-shadow: 0 22px 52px rgba(0, 0, 0, 0.72);
    }

    /* Typography: Tabular numbers for price consistency */
    .menu-card-price-row, .card-price, .font-editorial-num, .lining-nums {
        font-variant-numeric: tabular-nums;
        -moz-font-feature-settings: "tnum" 1;
        -webkit-font-feature-settings: "tnum" 1;
        font-feature-settings: "tnum" 1;
    }

    /* N-Column Vertical Flex Layout Container */
    .menu-columns-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 12px;
        width: 100%;
    }
    @media (min-width: 640px) {
        .menu-columns-container {
            gap: 24px;
        }
    }

    /* Independent Vertical Flex Column: expanding a card in this column pushes cards below it without affecting other columns */
    .menu-column {
        flex: 1 1 0%;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 74px; /* Compact mobile vertical gap */
    }
    @media (min-width: 640px) {
        .menu-column {
            gap: 112px; /* 7rem gap-y-28 on desktop/tablet */
        }
    }

    /* Card Wrapper: in normal document flow */
    .menu-card-wrapper {
        width: 100%;
        position: relative;
    }

    /* Menu Card: In-flow glass/surface card with rounded corners, soft glow, and smooth border transitions */
    .menu-card {
        position: relative;
        width: 100%;
        background-color: var(--surface-solid);
        background-image: var(--glow);
        border-radius: 24px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        padding: 56px 11px 14px 11px;
        text-align: center;
        box-sizing: border-box;
        transition: border-color 0.28s ease, box-shadow 0.28s ease, background-color 0.28s ease;
    }
    @supports ((backdrop-filter: blur(10px)) or (-webkit-backdrop-filter: blur(10px))) {
        .menu-card {
            background-color: var(--surface);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    }
    @media (min-width: 640px) {
        .menu-card {
            border-radius: 28px;
            box-shadow: var(--shadow);
            padding: 72px 16px 20px 16px;
        }
    }

    /* Header Trigger Button: Encompasses Avatar, Title, Price and triggers full expansion */
    .menu-card-header-btn {
        display: block;
        width: 100%;
        background: transparent;
        border: none;
        padding: 0;
        margin: 0;
        cursor: pointer;
        text-align: center;
        outline: none;
        user-select: none;
    }
    .menu-card-header-btn:focus-visible {
        outline: 2px solid var(--field-focus);
        outline-offset: 4px;
        border-radius: 16px;
    }

    /* Overhanging Large Circular Photo */
    .menu-card-avatar {
        position: absolute;
        top: -44px;
        left: 50%;
        transform: translateX(-50%) translateY(0) scale(1);
        transform-origin: center center;
        width: 94px;
        height: 94px;
        border-radius: 50%;
        border: 4px solid var(--menu-avatar-border);
        box-shadow: var(--menu-avatar-shadow);
        overflow: hidden;
        background-color: var(--media-bg);
        padding: 0;
        margin: 0;
        display: block;
        cursor: pointer;
        outline: none;
        transition: transform 280ms ease-out, box-shadow 280ms ease-out;
        z-index: 10;
        pointer-events: auto;
        will-change: transform, box-shadow;
    }
    .menu-card-avatar:focus-visible {
        outline: 2px solid var(--field-focus);
        outline-offset: 3px;
    }
    @media (min-width: 640px) {
        .menu-card-avatar {
            top: -62px;
            width: 126px;
            height: 126px;
            border-width: 5px;
            box-shadow: var(--menu-avatar-shadow);
        }
    }
    .menu-card-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Menu Title: bold with consistent min-height for clean row alignment */
    .menu-card-name {
        font-family: var(--font-sans), sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        line-height: 1.22;
        color: var(--text);
        margin-bottom: 4px;
        min-height: 2.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        word-break: break-word;
        text-align: center;
    }
    @media (min-width: 640px) {
        .menu-card-name {
            font-size: 1.15rem;
            min-height: 2.8rem;
        }
    }

    /* Price & Unit Row (font-variant-numeric: tabular-nums) */
    .menu-card-price-row {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 3px;
        font-family: var(--font-sans), sans-serif;
        font-size: 0.94rem;
        font-weight: 700;
        color: var(--text);
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    @media (min-width: 640px) {
        .menu-card-price-row {
            gap: 4px;
            font-size: 1.05rem;
        }
    }
    .menu-card-unit {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    /* Drawer Container: in-flow collapsible section */
    .menu-card-drawer {
        height: 0px;
        overflow: hidden;
        box-sizing: border-box;
        transition: height 380ms cubic-bezier(0.16, 1, 0.3, 1);
        will-change: height;
    }

    /* Divider Line: becomes visible inside drawer when opened */
    .menu-card-divider {
        height: 1px;
        background-color: var(--border);
        margin: 10px auto 10px auto;
        width: 80%;
        opacity: 0.9;
    }

    /* Drawer Inner Content: options, description, and action button */
    .menu-card-drawer-content {
        opacity: 0;
        pointer-events: none;
        transition: opacity 280ms ease 100ms;
        padding-top: 2px;
    }

    /* Keadaan 1: HOVER */
    @media (hover: hover) and (pointer: fine) {
        .menu-card:hover {
            z-index: 25;
            cursor: pointer;
        }
        .menu-card:not(.is-open):hover {
            border-color: var(--card-hover-border);
            box-shadow: var(--menu-card-hover-shadow);
        }
        /* Foto bulat membesar mendekat ke depan dan bayangan lebih tegas */
        .menu-card:hover .menu-card-avatar {
            transform: translateX(-50%) translateY(-3px) scale(1.10);
            box-shadow: var(--menu-avatar-hover-shadow);
            z-index: 30;
        }
    }

    /* Fokus Keyboard (:has(:focus-visible)) */
    .menu-card:has(:focus-visible):not(.is-open) {
        border-color: var(--border-strong);
        box-shadow: var(--menu-card-hover-shadow);
        z-index: 25;
    }
    .menu-card:has(:focus-visible) .menu-card-avatar {
        transform: translateX(-50%) translateY(-3px) scale(1.10);
        box-shadow: var(--menu-avatar-hover-shadow);
        z-index: 30;
    }

    /* Keadaan 2: KLIK (Kartu terbuka penuh) */
    .menu-card.is-open {
        z-index: 35;
        background-color: var(--surface-elevated);
        border-color: var(--border-strong) !important;
        box-shadow: var(--menu-card-open-shadow) !important;
    }
    .menu-card.is-open .menu-card-avatar {
        transform: translateX(-50%) translateY(-3px) scale(1.10);
        box-shadow: var(--menu-avatar-hover-shadow);
        z-index: 30;
    }
    .menu-card.is-open .menu-card-drawer-content {
        opacity: 1;
        pointer-events: auto;
    }

    /* Radio Options 2-Columns (1-Col on ultra-narrow mobile < 390px) */
    .options-columns {
        display: grid;
        grid-template-columns: 1fr;
        gap: 6px;
        text-align: left;
    }
    @media (min-width: 390px) {
        .options-columns {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
    }
    .option-group-title {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text);
        margin-bottom: 4px;
        display: block;
    }
    .radio-pill-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        color: var(--text-muted);
        cursor: pointer;
        padding: 1.5px 0;
        transition: color 0.15s ease;
        user-select: none;
    }
    .radio-pill-label:hover {
        color: var(--text);
    }
    .radio-pill-label input[type="radio"] {
        accent-color: var(--accent-solid);
        width: 12px;
        height: 12px;
        cursor: pointer;
        margin: 0;
        flex-shrink: 0;
    }

    /* Short Description for Menu without Options */
    .menu-card-desc {
        font-size: 0.72rem;
        color: var(--text-muted);
        line-height: 1.35;
        margin: 6px 0 10px 0;
        text-align: center;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Add to Cart Button */
    .btn-add-cart {
        width: 100%;
        padding: 8px 12px;
        border-radius: 9999px;
        background-color: var(--accent-solid);
        color: var(--accent-solid-text);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        box-shadow: var(--shadow-soft);
        transition: background-color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
    }
    .btn-add-cart:hover {
        background-color: var(--accent-solid-hover);
        box-shadow: var(--shadow);
    }
    .btn-add-cart:active {
        transform: scale(0.97);
    }
    .btn-add-cart.is-added {
        background-color: #15803d !important;
        color: #ffffff !important;
    }
    .btn-add-cart.disabled {
        background-color: var(--badge-bg) !important;
        color: var(--text-faint) !important;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Floating Cart Pill (Full-width sleek bar on mobile < 640px, bottom-right pill on desktop >= 640px) */
    .floating-cart-pill {
        position: fixed;
        bottom: 14px;
        left: 14px;
        right: 14px;
        z-index: 45;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background-color: var(--glass-surface-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 11px 16px;
        box-shadow: var(--shadow);
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.2s ease, opacity 0.3s ease;
        color: var(--text);
    }
    @media (min-width: 640px) {
        .floating-cart-pill {
            bottom: 24px;
            left: auto;
            right: 24px;
            justify-content: flex-start;
            gap: 8px;
            border-radius: 9999px;
            padding: 8px 18px 8px 12px;
        }
    }
    .floating-cart-pill:hover {
        transform: translateY(-2px);
        border-color: var(--border-strong);
        box-shadow: var(--floating-cart-hover-shadow);
    }
    .floating-cart-pill:active {
        transform: scale(0.97);
    }

    /* Pop bump animation for cart badges */
    @keyframes cartBadgeBump {
        0% { transform: scale(1); }
        40% { transform: scale(1.35); }
        100% { transform: scale(1); }
    }
    .cart-bump {
        animation: cartBadgeBump 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Drawer Scrollbar */
    .drawer-scroll {
        scrollbar-width: thin;
        scrollbar-color: var(--scrollbar-thumb) var(--surface-solid);
    }
    .drawer-scroll::-webkit-scrollbar { width: 5px; }
    .drawer-scroll::-webkit-scrollbar-track { background: var(--surface-solid); }
    .drawer-scroll::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 9999px; }

    /* Reduced Motion: matikan zoom dan gerak naik, ganti dengan perubahan garis tepi dan bayangan saja */
    @media (prefers-reduced-motion: reduce) {
        .menu-card, .menu-card-drawer, #cart-drawer-panel, .floating-cart-pill {
            transition: none !important;
            animation: none !important;
        }
        .menu-card-avatar {
            transition: box-shadow 0.2s ease !important;
            transform: translateX(-50%) translateY(0) scale(1) !important;
        }
        .menu-card:hover .menu-card-avatar,
        .menu-card:has(:focus-visible) .menu-card-avatar,
        .menu-card.is-open .menu-card-avatar {
            transform: translateX(-50%) translateY(0) scale(1) !important;
        }
    }
</style>

<!-- Accessibility Announcer for Screen Readers -->
<div id="cart-aria-announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>

<!-- =========================================================================
     1. FLOATING BOTTOM-RIGHT CAPSULE BUTTON
     Pill with thin border, shopping cart icon, count badge, and "My cart: Rp [total]"
     ========================================================================= -->
@if($isAnyOrderModeActive)
<button type="button" 
        id="floating-cart-btn"
        onclick="openCartDrawer()" 
        class="floating-cart-pill opacity-0 pointer-events-none translate-y-4"
        aria-label="Lihat Keranjang Belanja">
    <div class="flex items-center gap-2.5 sm:gap-2">
        <div class="relative flex items-center">
            <span class="material-symbols-outlined text-[var(--text)] text-xl">shopping_cart</span>
            <span id="floating-cart-badge" 
                  class="absolute -top-2 -right-2 bg-[var(--accent-solid)] text-[var(--accent-solid-text)] text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-xs">
                0
            </span>
        </div>
        <span class="font-body text-xs font-bold sm:font-semibold text-[var(--text-muted)] tracking-wide">
            My cart:
        </span>
    </div>
    <div class="flex items-center gap-1.5">
        <span id="floating-cart-total" class="font-editorial-num text-xs font-bold text-[var(--text)]">
            Rp 0
        </span>
        <span class="material-symbols-outlined text-base text-[var(--text-muted)] sm:hidden">chevron_right</span>
    </div>
</button>
@endif

<!-- =========================================================================
     2. BACKDROP OVERLAY & SLIDE-OVER DRAWER
     ========================================================================= -->
<div id="cart-drawer-backdrop" 
     class="fixed inset-0 bg-black/70 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeCartDrawer()"
     aria-hidden="true">
</div>

<aside id="cart-drawer-panel" 
       class="fixed top-0 right-0 h-full w-full max-w-[420px] bg-[var(--surface-solid)] z-50 shadow-2xl flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out border-l border-[var(--border)]"
       role="dialog"
       aria-modal="true"
       aria-labelledby="drawer-cart-title">
    
    <!-- DRAWER HEADER -->
    <div class="p-4 sm:p-5 border-b border-[var(--border)] flex items-center justify-between bg-[var(--surface-strong-solid)] shrink-0">
        <!-- View 1: Cart Header -->
        <div id="cart-header-items" class="flex items-center space-x-2.5">
            <span class="material-symbols-outlined text-[var(--text)] text-2xl">shopping_cart</span>
            <h3 id="drawer-cart-title" class="font-editorial text-2xl font-bold text-[var(--text)] tracking-tight">
                My cart
            </h3>
            <span id="cart-header-count" class="px-2 py-0.5 text-xs font-bold rounded-full bg-[var(--badge-bg)] border border-[var(--border)] text-[var(--text)]">
                0
            </span>
        </div>

        <!-- View 2: Checkout Header (Initially hidden) -->
        <div id="cart-header-checkout" class="hidden items-center space-x-2.5">
            <button type="button" 
                    onclick="switchCartView('items')" 
                    class="w-8 h-8 rounded-full bg-[var(--badge-bg)] hover:bg-[var(--btn-outline-hover-bg)] border border-[var(--border)] text-[var(--text)] flex items-center justify-center transition-colors cursor-pointer"
                    aria-label="Kembali ke keranjang">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </button>
            <h3 class="font-editorial text-xl font-bold text-[var(--text)] tracking-tight">
                Checkout Pesanan
            </h3>
        </div>

        <!-- View 3: Success Header (Initially hidden) -->
        <div id="cart-header-success" class="hidden items-center space-x-2.5">
            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-2xl">check_circle</span>
            <h3 class="font-editorial text-xl font-bold text-[var(--text)] tracking-tight">
                Pesanan Berhasil
            </h3>
        </div>

        <!-- Close Button -->
        <button type="button" 
                id="cart-drawer-close-btn"
                onclick="closeCartDrawer()" 
                class="w-9 h-9 rounded-full bg-[var(--badge-bg)] hover:bg-[var(--accent-solid)] hover:text-[var(--accent-solid-text)] text-[var(--text-muted)] flex items-center justify-center transition-colors focus:outline-none cursor-pointer"
                aria-label="Tutup Keranjang">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
    </div>

    <!-- =========================================================================
         VIEW 1: CART ITEMS LIST
         ========================================================================= -->
    <div id="cart-view-items" class="flex flex-col flex-grow overflow-hidden">
        <!-- Scrollable Items Area -->
        <div class="flex-grow overflow-y-auto p-4 sm:p-5 space-y-3 drawer-scroll" id="cart-scroll-area">
            
            <!-- Empty State -->
            <div id="cart-empty-state" class="py-16 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-[var(--badge-bg)] border border-[var(--border-soft)] flex items-center justify-center text-[var(--text-faint)] mb-3">
                    <span class="material-symbols-outlined text-3xl">remove_shopping_cart</span>
                </div>
                <h4 class="font-editorial text-lg font-bold text-[var(--text)]">Keranjang Masih Kosong</h4>
                <p class="font-body text-xs text-[var(--text-muted)] max-w-xs mx-auto mt-1 leading-relaxed">
                    Pilih sajian favorit Anda di daftar menu untuk mulai memesan.
                </p>
            </div>

            <!-- Populated Cart Items List Container -->
            <div id="cart-items-list" class="space-y-3">
                <!-- Dynamically populated by JS renderCartDrawer() -->
            </div>
        </div>

        <!-- Cart View Footer: Pricing Breakdown & Checkout Button -->
        <div id="cart-footer-panel" class="p-4 sm:p-5 border-t border-[var(--border)] bg-[var(--surface-strong-solid)] space-y-3 shrink-0">
            <div class="space-y-1.5 text-xs sm:text-sm">
                <div class="flex items-center justify-between text-[var(--text-muted)] font-medium">
                    <span>Subtotal Menu</span>
                    <span id="cart-subtotal" class="font-semibold text-[var(--text)] font-editorial-num">Rp 0</span>
                </div>
                <div class="flex items-center justify-between text-[var(--text-muted)] text-[11px] sm:text-xs">
                    <span>Diskon / Promo</span>
                    <span class="font-medium text-emerald-600 dark:text-emerald-400 font-editorial-num">Rp 0</span>
                </div>
                <div class="pt-2 border-t border-[var(--border-soft)] flex items-baseline justify-between">
                    <span class="font-bold text-sm sm:text-base text-[var(--text)]">Final Total</span>
                    <span id="cart-final-total" class="font-editorial text-xl font-bold text-[var(--text)] font-editorial-num">
                        Rp 0
                    </span>
                </div>
            </div>

            @if($isAnyOrderModeActive)
            <!-- Monochromatic Checkout Button (Switches to in-drawer checkout) -->
            <button type="button" 
                    id="cart-checkout-btn"
                    onclick="switchCartView('checkout')" 
                    class="w-full py-3.5 px-5 rounded-2xl bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] font-bold text-sm tracking-wide shadow-md active:scale-98 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                <span>Checkout / Pesan Sekarang</span>
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </button>
            @endif
        </div>
    </div>

    <!-- =========================================================================
         VIEW 2: IN-DRAWER CHECKOUT FORM
         ========================================================================= -->
    <div id="cart-view-checkout" class="hidden flex-col flex-grow overflow-hidden">
        <form id="drawer-checkout-form" onsubmit="submitDrawerCheckout(event)" class="flex flex-col flex-grow overflow-hidden">
            <!-- Scrollable Form Body -->
            <div class="flex-grow overflow-y-auto p-4 sm:p-5 space-y-4 drawer-scroll">
                
                <!-- 1. METODE PROSES TRANSAKSI (Kasir vs Zendo Courier) -->
                @if($orderModeDineIn && $orderModeZendo)
                <div class="space-y-2">
                    <label class="text-xs font-bold text-[var(--text)] uppercase tracking-wider flex items-center gap-1.5">
                        <span>1. Metode Pesanan</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div id="method-card-kasir" 
                             onclick="selectDrawerMethod('kasir')" 
                             class="drawer-method-card active p-3 rounded-2xl bg-[var(--surface-strong)] border-2 border-[var(--accent-solid)] shadow-xs cursor-pointer transition-all">
                            <div class="flex items-start justify-between">
                                <span class="material-symbols-outlined text-[var(--text)] text-xl">store</span>
                                <span class="method-dot w-2.5 h-2.5 rounded-full bg-[var(--accent-solid)]"></span>
                            </div>
                            <h5 class="text-xs font-bold text-[var(--text)] mt-2">Kasir Kafe</h5>
                            <p class="text-[10px] text-[var(--text-muted)] mt-0.5">Dine-In / Takeaway</p>
                        </div>
                        <div id="method-card-zendo" 
                             onclick="selectDrawerMethod('zendo')" 
                             class="drawer-method-card p-3 rounded-2xl bg-[var(--surface)] border-2 border-[var(--border)] hover:border-[var(--border-strong)] cursor-pointer transition-all">
                            <div class="flex items-start justify-between">
                                <span class="material-symbols-outlined text-[var(--text-muted)] text-xl">two_wheeler</span>
                                <span class="method-dot w-2.5 h-2.5 rounded-full bg-[var(--border-strong)]"></span>
                            </div>
                            <h5 class="text-xs font-bold text-[var(--text)] mt-2">Kurir Zendo</h5>
                            <p class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ count($zendoCouriers) }} Kurir Siaga</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 2. DATA PEMESAN -->
                <div class="space-y-3">
                    <div>
                        <label for="drawer-customer-name" class="block text-xs font-bold text-[var(--text)] uppercase tracking-wider mb-1">
                            Nama Pemesan <span class="text-[var(--error-text)]">*</span>
                        </label>
                        <input type="text" 
                               id="drawer-customer-name" 
                               required 
                               placeholder="Nama lengkap atau panggilan..." 
                               class="w-full py-2.5 px-3 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-[var(--text)] text-xs focus:outline-none focus:border-[var(--field-focus)] transition-all placeholder:text-[var(--text-faint)]">
                    </div>
                    <div>
                        <label for="drawer-customer-phone" class="block text-xs font-bold text-[var(--text)] uppercase tracking-wider mb-1">
                            Nomor WhatsApp <span class="text-[var(--error-text)]">*</span>
                        </label>
                        <input type="tel" 
                               id="drawer-customer-phone" 
                               required 
                               placeholder="08xxxxxxxxxx" 
                               class="w-full py-2.5 px-3 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-[var(--text)] text-xs focus:outline-none focus:border-[var(--field-focus)] transition-all placeholder:text-[var(--text-faint)]">
                    </div>

                    <!-- Kasir Specific: Nomor Meja -->
                    <div id="drawer-field-table">
                        <label for="drawer-customer-table" class="block text-xs font-bold text-[var(--text)] uppercase tracking-wider mb-1">
                            Nomor Meja <span class="text-[var(--text-muted)] font-normal">(Kosongkan jika Take Away)</span>
                        </label>
                        <input type="text" 
                               id="drawer-customer-table" 
                               placeholder="Contoh: Meja 05 / Take Away" 
                               class="w-full py-2.5 px-3 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-[var(--text)] text-xs focus:outline-none focus:border-[var(--field-focus)] transition-all placeholder:text-[var(--text-faint)]">
                    </div>

                    <!-- Kasir Specific: Metode Pembayaran (Dine-In / Take Away) -->
                    <div id="drawer-field-payment" class="space-y-2 {{ $initialProcessMethod === 'zendo' ? 'hidden' : '' }}">
                        <label class="block text-xs font-bold text-[var(--text)] uppercase tracking-wider">
                            Metode Pembayaran
                        </label>
                        <input type="hidden" id="drawer-payment-method" value="{{ $orderDineInCash ? 'cash' : ($orderDineInQris ? 'qris' : 'transfer') }}">
                        <div class="grid grid-cols-3 gap-2">
                            @if($orderDineInCash)
                                <button type="button"
                                        data-drawer-pay="cash"
                                        onclick="selectDrawerPaymentMethod('cash')"
                                        class="drawer-pay-btn py-2.5 px-2 rounded-xl text-xs font-bold border transition-all cursor-pointer bg-[var(--accent-solid)] text-[var(--accent-solid-text)] border-[var(--accent-solid)]">
                                    Tunai
                                </button>
                            @endif
                            @if($orderDineInQris)
                                <button type="button"
                                        data-drawer-pay="qris"
                                        onclick="selectDrawerPaymentMethod('qris')"
                                        class="drawer-pay-btn py-2.5 px-2 rounded-xl text-xs font-bold border transition-all cursor-pointer {{ ! $orderDineInCash ? 'bg-[var(--accent-solid)] text-[var(--accent-solid-text)] border-[var(--accent-solid)]' : 'bg-[var(--field-bg)] text-[var(--text-muted)] border-[var(--field-border)] hover:text-[var(--text)]' }}">
                                    QRIS
                                </button>
                            @endif
                            <button type="button"
                                    data-drawer-pay="transfer"
                                    onclick="selectDrawerPaymentMethod('transfer')"
                                    class="drawer-pay-btn py-2.5 px-2 rounded-xl text-xs font-bold border transition-all cursor-pointer {{ ! $orderDineInCash && ! $orderDineInQris ? 'bg-[var(--accent-solid)] text-[var(--accent-solid-text)] border-[var(--accent-solid)]' : 'bg-[var(--field-bg)] text-[var(--text-muted)] border-[var(--field-border)] hover:text-[var(--text)]' }}">
                                Transfer
                            </button>
                        </div>
                    </div>

                    <!-- Zendo Specific: Alamat Pengiriman -->
                    <div id="drawer-field-address" class="hidden space-y-2">
                        <label for="drawer-customer-address" class="block text-xs font-bold text-[var(--text)] uppercase tracking-wider mb-1">
                            Alamat Pengantaran Zendo <span class="text-[var(--error-text)]">*</span>
                        </label>
                        <textarea id="drawer-customer-address" 
                                  rows="2" 
                                  placeholder="Nama jalan, nomor rumah, patokan..." 
                                  class="w-full py-2.5 px-3 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-[var(--text)] text-xs focus:outline-none focus:border-[var(--field-focus)] transition-all placeholder:text-[var(--text-faint)]"></textarea>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label for="drawer-order-notes" class="block text-xs font-bold text-[var(--text)] uppercase tracking-wider mb-1">
                            Catatan Tambahan <span class="text-[var(--text-muted)] font-normal">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="drawer-order-notes" 
                               placeholder="Contoh: Kurangi es, saus dipisah..." 
                               class="w-full py-2.5 px-3 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-[var(--text)] text-xs focus:outline-none focus:border-[var(--field-focus)] transition-all placeholder:text-[var(--text-faint)]">
                    </div>

                    <!-- Error Banner -->
                    <div id="drawer-checkout-error" class="hidden p-3 rounded-xl bg-[var(--error-bg)] border border-[var(--error-border)] text-[var(--error-text)] text-xs"></div>
                </div>

            </div>

            <!-- Checkout View Footer: Submit & Back -->
            <div class="p-4 sm:p-5 border-t border-[var(--border)] bg-[var(--surface-strong-solid)] space-y-2 shrink-0">
                <button type="submit" 
                        id="drawer-submit-btn" 
                        class="w-full py-3.5 px-5 rounded-2xl bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] font-bold text-sm tracking-wide shadow-md active:scale-98 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                    <span id="drawer-submit-text">Konfirmasi Pesanan</span>
                    <span id="drawer-submit-spinner" class="hidden animate-spin material-symbols-outlined text-base">progress_activity</span>
                </button>
                <button type="button" 
                        onclick="switchCartView('items')" 
                        class="w-full py-2 text-center text-xs font-semibold text-[var(--text-muted)] hover:text-[var(--text)] transition-colors cursor-pointer">
                    ← Kembali ke Daftar Menu
                </button>
            </div>
        </form>
    </div>

    <!-- =========================================================================
         VIEW 3: SUCCESS & ORDER SLIP QR
         ========================================================================= -->
    <div id="cart-view-success" class="hidden flex-col flex-grow overflow-hidden p-5 sm:p-6 text-center space-y-4 drawer-scroll overflow-y-auto">
        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-500/15 border border-emerald-500/35 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
            <span class="material-symbols-outlined text-3xl">check_circle</span>
        </div>

        <div>
            <span class="text-[11px] font-bold uppercase tracking-widest text-[var(--text-muted)] block">PESANAN DITERIMA</span>
            <h4 class="font-editorial text-2xl font-bold text-[var(--text)] mt-0.5" id="success-order-code">NCM-ORDER</h4>
            <p class="text-xs text-[var(--text-muted)] mt-1" id="success-subtext">
                Tunjukkan QR code ini ke kasir untuk proses pembayaran.
            </p>
        </div>

        <!-- QR Code Plate (White quiet-zone preserved for reliable camera QR scanning) -->
        <div class="bg-white p-4 rounded-3xl border border-[var(--border)] shadow-md mx-auto w-fit flex flex-col items-center justify-center" id="drawer-qr-container">
            <div id="drawer-qrcode-target"></div>
            <img id="drawer-qr-fallback" class="hidden w-40 h-40 object-contain rounded" alt="QR Code">
            <span class="text-[10px] text-[#191919] font-bold uppercase tracking-wider mt-2.5 block" id="success-qr-label">
                SCAN DI KASIR
            </span>
        </div>

        <!-- Summary Details -->
        <div class="p-3.5 rounded-2xl bg-[var(--surface)] border border-[var(--border)] text-xs text-left space-y-1.5">
            <div class="flex justify-between text-[var(--text-muted)]">
                <span>Pemesan:</span>
                <span id="success-customer-name" class="font-bold text-[var(--text)]">-</span>
            </div>
            <div class="flex justify-between text-[var(--text-muted)]">
                <span>Tipe:</span>
                <span id="success-order-type" class="font-bold text-[var(--text)]">-</span>
            </div>
            <div class="flex justify-between text-[var(--text-muted)] pt-1.5 border-t border-[var(--border-soft)]">
                <span>Total:</span>
                <span id="success-total-amount" class="font-bold text-emerald-600 dark:text-emerald-400 text-sm font-editorial-num">Rp 0</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-2 pt-2">
            <a id="success-wa-link" 
               href="#" 
               target="_blank" 
               class="hidden w-full py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center space-x-2 transition-all shadow-md shadow-emerald-600/20 active:scale-98">
                <i class="fa-brands fa-whatsapp text-base"></i>
                <span>Kirim Pesanan ke WhatsApp Kurir Zendo</span>
            </a>
            <a id="success-slip-link" 
               href="#" 
               target="_blank" 
               class="w-full py-3 px-4 rounded-xl bg-[var(--badge-bg)] hover:bg-[var(--btn-outline-hover-bg)] text-[var(--text)] border border-[var(--border)] font-bold text-xs flex items-center justify-center space-x-1.5 transition-colors">
                <span class="material-symbols-outlined text-sm">receipt_long</span>
                <span>Buka Slip Lengkap & Cetak Struk</span>
            </a>
            <button type="button" 
                    onclick="resetDrawerToCart()" 
                    class="w-full py-3 px-4 rounded-xl bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] font-bold text-xs shadow-md transition-all cursor-pointer">
                Selesai / Pesan Menu Lain
            </button>
        </div>
    </div>

</aside>

<!-- =========================================================================
     3. CLIENT JAVASCRIPT ENGINE (DRAWER, CART STATE, HOVER & TOUCH)
     ========================================================================= -->
<script>
    window.nucomuCart = {};
    window.currentDrawerMethod = '{{ $initialProcessMethod }}';
    let previousActiveElement = null;

    // Load cart from localStorage
    try {
        const stored = localStorage.getItem('nucomu_cart');
        if (stored) {
            window.nucomuCart = JSON.parse(stored);
        }
    } catch (e) {
        window.nucomuCart = {};
    }

    // ============================================
    // DRAWER OPEN & CLOSE CONTROLLERS
    // ============================================
    window.openCartDrawer = function() {
        const panel = document.getElementById('cart-drawer-panel');
        const backdrop = document.getElementById('cart-drawer-backdrop');
        if (!panel || !backdrop) return;

        previousActiveElement = document.activeElement;

        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100', 'pointer-events-auto');
        panel.classList.remove('translate-x-full');
        panel.classList.add('translate-x-0');
        document.body.style.overflow = 'hidden';

        renderCartDrawer();

        // Focus trap initial focus
        const closeBtn = document.getElementById('cart-drawer-close-btn');
        if (closeBtn) closeBtn.focus();
    };

    window.closeCartDrawer = function() {
        const panel = document.getElementById('cart-drawer-panel');
        const backdrop = document.getElementById('cart-drawer-backdrop');
        if (!panel || !backdrop) return;

        panel.classList.add('translate-x-full');
        panel.classList.remove('translate-x-0');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        document.body.style.overflow = '';

        if (previousActiveElement && typeof previousActiveElement.focus === 'function') {
            previousActiveElement.focus();
        }
    };

    // Alias for modal backwards compatibility
    window.openCartModal = window.openCartDrawer;
    window.closeCartModal = window.closeCartDrawer;
    window.toggleCartDrawer = function() {
        const panel = document.getElementById('cart-drawer-panel');
        if (panel && panel.classList.contains('translate-x-0')) {
            window.closeCartDrawer();
        } else {
            window.openCartDrawer();
        }
    };

    // Escape Key to close drawer
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const panel = document.getElementById('cart-drawer-panel');
            if (panel && panel.classList.contains('translate-x-0')) {
                window.closeCartDrawer();
            }
            // Close touched cards on mobile
            document.querySelectorAll('.menu-card.is-expanded').forEach(c => c.classList.remove('is-expanded'));
        }
    });

    // Touch support: Tap on mobile expands card, tap outside closes
    document.addEventListener('click', function(e) {
        const isCard = e.target.closest('.menu-card-slot');
        if (!isCard) {
            document.querySelectorAll('.menu-card.is-expanded').forEach(c => c.classList.remove('is-expanded'));
        }
    });

    // ============================================
    // CART STATE: ADD, UPDATE, REMOVE
    // ============================================
    window.addToCart = function(itemId, name, price, options, image) {
        const optArray = Array.isArray(options) ? options.filter(Boolean) : [];
        const optKey = optArray.slice().sort().join('__');
        const key = itemId + (optKey ? '::' + optKey : '');

        if (!window.nucomuCart[key]) {
            window.nucomuCart[key] = {
                id: itemId,
                name: name,
                price: parseInt(price, 10) || 0,
                options: optArray,
                optionsStr: optArray.join(', '),
                image: image || '',
                qty: 1
            };
        } else {
            window.nucomuCart[key].qty += 1;
        }

        try {
            localStorage.setItem('nucomu_cart', JSON.stringify(window.nucomuCart));
        } catch (e) {}

        // Announce to screen reader
        const announcer = document.getElementById('cart-aria-announcer');
        if (announcer) {
            announcer.textContent = `${name} berhasil ditambahkan ke keranjang.`;
        }

        // Pop bump animation on header and floating badge
        triggerCartBadgeBump();
        renderCartDrawer();
    };

    function triggerCartBadgeBump() {
        const navBadge = document.getElementById('nav-cart-badge');
        const mobileNavBadge = document.getElementById('mobile-nav-cart-badge');
        const drawerBadge = document.getElementById('drawer-cart-badge');
        const floatingBadge = document.getElementById('floating-cart-badge');

        [navBadge, mobileNavBadge, drawerBadge, floatingBadge].forEach(b => {
            if (b) {
                b.classList.remove('cart-bump');
                void b.offsetWidth; // force reflow
                b.classList.add('cart-bump');
            }
        });
    }

    window.updateDrawerQty = function(key, delta) {
        if (!window.nucomuCart[key]) return;
        window.nucomuCart[key].qty += delta;
        if (window.nucomuCart[key].qty <= 0) {
            delete window.nucomuCart[key];
        }
        try {
            localStorage.setItem('nucomu_cart', JSON.stringify(window.nucomuCart));
        } catch (e) {}
        renderCartDrawer();
    };
    window.updateModalQty = window.updateDrawerQty;

    window.removeDrawerCartItem = function(key) {
        if (window.nucomuCart[key]) {
            delete window.nucomuCart[key];
            try {
                localStorage.setItem('nucomu_cart', JSON.stringify(window.nucomuCart));
            } catch (e) {}
            renderCartDrawer();
        }
    };
    window.removeModalCartItem = window.removeDrawerCartItem;

    // ============================================
    // MENU CARD RESPONSIVE N-COLUMN & EXPANSION ENGINE
    // ============================================
    window.getMenuBreakpointColumns = function() {
        if (window.innerWidth >= 1280) return 5;
        if (window.innerWidth >= 768) return 3;
        return 2;
    };

    window.distributeMenuCards = function(poolId, containerId, categoryFilter = 'all') {
        const pool = document.getElementById(poolId);
        const container = document.getElementById(containerId);
        if (!container) return;

        const numCols = window.getMenuBreakpointColumns();

        // Collect all card wrappers from pool and container
        let wrappers = [];
        if (pool) {
            wrappers = wrappers.concat(Array.from(pool.querySelectorAll('.menu-card-wrapper')));
        }
        wrappers = wrappers.concat(Array.from(container.querySelectorAll('.menu-card-wrapper')));

        if (wrappers.length === 0) return;

        // Deduplicate wrappers by dataset index and id
        const seen = new Set();
        const uniqueWrappers = [];
        wrappers.forEach(w => {
            const id = (w.dataset.index || '0') + '-' + (w.dataset.id || '');
            if (!seen.has(id)) {
                seen.add(id);
                uniqueWrappers.push(w);
            }
        });

        // Always sort by original data-index to guarantee consistent row order (i mod N)
        uniqueWrappers.sort((a, b) => parseInt(a.dataset.index || '0', 10) - parseInt(b.dataset.index || '0', 10));

        // Preserve open card ID if any card is currently open
        const currentlyOpen = container.querySelector('.menu-card.is-open');
        const openCardId = currentlyOpen ? currentlyOpen.dataset.cardId : null;

        // Filter cards matching active category
        const visibleCards = [];
        uniqueWrappers.forEach(w => {
            const cat = w.dataset.category || '';
            const cats = cat.split(' ');
            const isMatch = (categoryFilter === 'all') || cats.includes(categoryFilter);
            if (isMatch) {
                w.style.display = 'block';
                visibleCards.push(w);
            } else {
                w.style.display = 'none';
                if (pool) pool.appendChild(w);
            }
        });

        // Empty state toggling
        const emptyStateId = containerId === 'recommendations-columns-container' 
            ? 'recommendations-empty-state' 
            : (containerId === 'catalog-columns-container' ? 'catalog-empty-state' : null);
        if (emptyStateId) {
            const emptyState = document.getElementById(emptyStateId);
            if (emptyState) {
                if (visibleCards.length === 0) {
                    emptyState.classList.remove('hidden');
                    container.classList.add('hidden');
                } else {
                    emptyState.classList.add('hidden');
                    container.classList.remove('hidden');
                }
            }
        }

        // Rebuild independent vertical flex columns
        container.innerHTML = '';
        container.dataset.renderedCols = String(numCols);

        const columns = [];
        for (let c = 0; c < numCols; c++) {
            const col = document.createElement('div');
            col.className = 'menu-column flex-1 flex flex-col gap-y-[74px] sm:gap-y-28 min-w-0';
            container.appendChild(col);
            columns.push(col);
        }

        // Distribute card i into column (i mod numCols)
        visibleCards.forEach((wrap, idx) => {
            const targetCol = idx % numCols;
            columns[targetCol].appendChild(wrap);
        });

        // Restore drawer height for open card
        if (openCardId) {
            const restoredCard = container.querySelector(`.menu-card[data-card-id="${openCardId}"]`);
            if (restoredCard) {
                restoredCard.classList.add('is-open');
                const drawer = restoredCard.querySelector('.menu-card-drawer');
                if (drawer) {
                    drawer.style.height = drawer.scrollHeight + 'px';
                }
            }
        }
    };

    window.openMenuCard = function(cardEl) {
        if (!cardEl) return;

        // 1. Close any currently open card first (only 1 open at a time)
        const currentlyOpen = document.querySelector('.menu-card.is-open');
        if (currentlyOpen && currentlyOpen !== cardEl) {
            window.closeMenuCard(currentlyOpen);
        }

        // 2. Open target card
        cardEl.classList.add('is-open');
        const btn = cardEl.querySelector('.menu-card-header-btn');
        if (btn) btn.setAttribute('aria-expanded', 'true');

        const drawer = cardEl.querySelector('.menu-card-drawer');
        if (drawer) {
            drawer.style.height = drawer.scrollHeight + 'px';
        }

        // 3. Smooth scroll into view if bottom of open card goes below screen
        setTimeout(() => {
            const rect = cardEl.getBoundingClientRect();
            if (rect.bottom > window.innerHeight) {
                cardEl.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
        }, 180);
    };

    window.closeMenuCard = function(cardEl) {
        if (!cardEl) return;
        cardEl.classList.remove('is-open');
        const btn = cardEl.querySelector('.menu-card-header-btn');
        if (btn) btn.setAttribute('aria-expanded', 'false');

        const drawer = cardEl.querySelector('.menu-card-drawer');
        if (drawer) {
            drawer.style.height = '0px';
            setTimeout(() => {
                if (!cardEl.classList.contains('is-open')) {
                    drawer.style.height = '';
                }
            }, 400);
        }
    };

    window.toggleMenuCard = function(cardEl) {
        if (!cardEl) return;
        if (cardEl.classList.contains('is-open')) {
            window.closeMenuCard(cardEl);
        } else {
            window.openMenuCard(cardEl);
        }
    };

    // Close open card when clicking outside
    document.addEventListener('click', function(e) {
        const openCard = document.querySelector('.menu-card.is-open');
        if (!openCard) return;
        if (!openCard.contains(e.target)) {
            window.closeMenuCard(openCard);
        }
    });

    // Close open card when pressing Escape and return focus (unless photo lightbox is currently open)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.key === 'Esc') {
            const lightbox = document.getElementById('lightbox-modal');
            if (lightbox && !lightbox.classList.contains('pointer-events-none')) {
                return;
            }
            const openCard = document.querySelector('.menu-card.is-open');
            if (openCard) {
                window.closeMenuCard(openCard);
                const btn = openCard.querySelector('.menu-card-header-btn');
                if (btn) btn.focus();
            }
        }
    });

    // Responsive redistribution on breakpoint change
    let lastMenuBreakpointCols = window.getMenuBreakpointColumns();
    window.addEventListener('resize', function() {
        const newCols = window.getMenuBreakpointColumns();
        if (newCols !== lastMenuBreakpointCols) {
            lastMenuBreakpointCols = newCols;
            if (document.getElementById('catalog-columns-container')) {
                window.distributeMenuCards('catalog-cards-pool', 'catalog-columns-container');
            }
            if (document.getElementById('recommendations-columns-container')) {
                const activeCat = window.activeRecommendationCategory || 'all';
                window.distributeMenuCards('recommendations-cards-pool', 'recommendations-columns-container', activeCat);
            }
        }
    });

    // ============================================
    // MENU CARD ORDER SUBMISSION (Add to Cart button with 1.5s visual feedback)
    // ============================================
    window.submitMenuCardOrder = function(cardOrSlotEl, name, priceFormatted) {
        if (!cardOrSlotEl) return;
        const cardEl = cardOrSlotEl.closest('.menu-card') || cardOrSlotEl;
        const wrapperEl = cardEl.closest('.menu-card-wrapper') || cardEl;
        const itemId = cardEl.dataset.id || wrapperEl.dataset.id || name.toLowerCase().replace(/[^a-z0-9]/g, '-');
        
        let numericPrice = 0;
        if (cardEl.dataset.price) {
            numericPrice = parseInt(cardEl.dataset.price, 10);
        } else if (wrapperEl.dataset.price) {
            numericPrice = parseInt(wrapperEl.dataset.price, 10);
        } else if (priceFormatted) {
            numericPrice = parseInt(String(priceFormatted).replace(/[^0-9]/g, ''), 10) || 0;
        }

        const checkedRadios = cardEl.querySelectorAll('input[type="radio"]:checked');
        const options = [];
        checkedRadios.forEach(r => {
            if (r.value) options.push(r.value);
        });

        const imgEl = cardEl.querySelector('img');
        const imgSrc = imgEl ? imgEl.src : '';

        window.addToCart(itemId, name, numericPrice, options, imgSrc);

        // Visual feedback on button (Exact match to video Scene 3: "Masuk Keranjang!" for 1.5s)
        const btn = cardEl.querySelector('.btn-add-cart');
        if (btn && !btn.classList.contains('is-added')) {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<span class="material-symbols-outlined text-sm">check</span><span>Masuk Keranjang!</span>`;
            btn.classList.add('is-added');

            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('is-added');
            }, 1500);
        }
        // Notice: Card stays open after adding to cart, as requested
    };
    window.submitPopularDishOrder = window.submitMenuCardOrder;
    window.toggleSlotTouch = function(slotEl) {
        const card = slotEl?.querySelector('.menu-card') || slotEl;
        window.toggleMenuCard(card);
    };

    // ============================================
    // RENDER CART DRAWER ITEMS & TOTALS
    // ============================================
    window.renderCartDrawer = function() {
        const itemsContainer = document.getElementById('cart-items-list');
        const emptyState = document.getElementById('cart-empty-state');
        const headerCount = document.getElementById('cart-header-count');
        const subtotalEl = document.getElementById('cart-subtotal');
        const finalTotalEl = document.getElementById('cart-final-total');
        const floatingBtn = document.getElementById('floating-cart-btn');
        const floatingBadge = document.getElementById('floating-cart-badge');
        const floatingTotal = document.getElementById('floating-cart-total');
        const navBadge = document.getElementById('nav-cart-badge');
        const mobileNavBadge = document.getElementById('mobile-nav-cart-badge');
        const drawerBadge = document.getElementById('drawer-cart-badge');

        const keys = Object.keys(window.nucomuCart);
        let subtotal = 0;
        let totalQty = 0;

        if (keys.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (itemsContainer) itemsContainer.innerHTML = '';
            if (headerCount) headerCount.innerText = '0';
            if (subtotalEl) subtotalEl.innerText = 'Rp 0';
            if (finalTotalEl) finalTotalEl.innerText = 'Rp 0';

            // Hide floating button when empty
            if (floatingBtn) {
                floatingBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                floatingBtn.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
            }
            if (floatingBadge) floatingBadge.innerText = '0';
            if (floatingTotal) floatingTotal.innerText = 'Rp 0';
            if (navBadge) {
                navBadge.innerText = '0';
                navBadge.classList.add('hidden');
            }
            if (mobileNavBadge) {
                mobileNavBadge.innerText = '0';
                mobileNavBadge.classList.add('hidden');
            }
            if (drawerBadge) drawerBadge.innerText = '0';
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        let html = '';
        keys.forEach(key => {
            const item = window.nucomuCart[key];
            const lineTotal = item.price * item.qty;
            subtotal += lineTotal;
            totalQty += item.qty;

            // Render option chips
            let chipsHtml = '';
            if (item.options && item.options.length > 0) {
                chipsHtml = '<div class="flex flex-wrap gap-1 mt-1">';
                item.options.forEach(opt => {
                    chipsHtml += `<span class="inline-block px-2 py-0.5 rounded-full bg-[var(--badge-bg)] text-[var(--text-muted)] text-[10px] font-medium leading-none">${opt}</span>`;
                });
                chipsHtml += '</div>';
            }

            html += `
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-[var(--surface)] border border-[var(--border)] transition-all">
                <img src="${item.image || 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&q=80&w=200'}" 
                     alt="${item.name}" 
                     class="w-12 h-12 rounded-xl object-cover shrink-0 border border-[var(--border)] shadow-xs">
                
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-[var(--text)] leading-tight truncate">
                        ${item.qty}× ${item.name}
                    </p>
                    ${chipsHtml}
                    <p class="text-[11px] font-bold text-[var(--text-muted)] font-editorial-num mt-1">
                        Rp ${lineTotal.toLocaleString('id-ID')}
                    </p>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" 
                            onclick="updateDrawerQty('${key}', -1)" 
                            class="w-6 h-6 rounded-full bg-[var(--badge-bg)] border border-[var(--border)] hover:border-[var(--border-strong)] text-[var(--text)] text-xs flex items-center justify-center font-bold transition-all cursor-pointer" 
                            aria-label="Kurangi jumlah">−</button>
                    <span class="text-xs font-bold text-[var(--text)] w-4 text-center font-mono">${item.qty}</span>
                    <button type="button" 
                            onclick="updateDrawerQty('${key}', 1)" 
                            class="w-6 h-6 rounded-full bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] text-xs flex items-center justify-center font-bold transition-all cursor-pointer" 
                            aria-label="Tambah jumlah">+</button>
                    <button type="button" 
                            onclick="removeDrawerCartItem('${key}')" 
                            class="w-6 h-6 rounded-full text-[var(--text-faint)] hover:text-[var(--error-text)] flex items-center justify-center ml-1 text-xs transition-colors cursor-pointer" 
                            title="Hapus menu" 
                            aria-label="Hapus menu">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </div>
            </div>`;
        });

        if (itemsContainer) itemsContainer.innerHTML = html;
        if (headerCount) headerCount.innerText = totalQty;
        if (subtotalEl) subtotalEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        if (finalTotalEl) finalTotalEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

        // Show floating capsule when totalQty > 0
        if (floatingBtn) {
            floatingBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
            floatingBtn.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
        }
        if (floatingBadge) floatingBadge.innerText = totalQty;
        if (floatingTotal) floatingTotal.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

        if (navBadge) {
            navBadge.innerText = totalQty;
            navBadge.classList.remove('hidden');
        }
        if (mobileNavBadge) {
            mobileNavBadge.innerText = totalQty;
            mobileNavBadge.classList.remove('hidden');
        }
        if (drawerBadge) drawerBadge.innerText = totalQty;
    };
    window.renderModalCart = window.renderCartDrawer;

    // ============================================
    // DRAWER VIEWS SWITCHER (Items <-> Checkout <-> Success)
    // ============================================
    window.switchCartView = function(viewName) {
        const viewItems = document.getElementById('cart-view-items');
        const viewCheckout = document.getElementById('cart-view-checkout');
        const viewSuccess = document.getElementById('cart-view-success');

        const headItems = document.getElementById('cart-header-items');
        const headCheckout = document.getElementById('cart-header-checkout');
        const headSuccess = document.getElementById('cart-header-success');

        if (viewItems) viewItems.classList.toggle('hidden', viewName !== 'items');
        if (viewCheckout) viewCheckout.classList.toggle('hidden', viewName !== 'checkout');
        if (viewSuccess) viewSuccess.classList.toggle('hidden', viewName !== 'success');

        if (headItems) headItems.classList.toggle('hidden', viewName !== 'items');
        if (headCheckout) headCheckout.classList.toggle('hidden', viewName !== 'checkout');
        if (headSuccess) headSuccess.classList.toggle('hidden', viewName !== 'success');
    };

    window.selectDrawerPaymentMethod = function(payMethod) {
        const input = document.getElementById('drawer-payment-method');
        if (input) input.value = payMethod;
        document.querySelectorAll('.drawer-pay-btn').forEach(btn => {
            const m = btn.getAttribute('data-drawer-pay');
            btn.className = m === payMethod
                ? 'drawer-pay-btn py-2.5 px-2 rounded-xl text-xs font-bold border transition-all cursor-pointer bg-[var(--accent-solid)] text-[var(--accent-solid-text)] border-[var(--accent-solid)]'
                : 'drawer-pay-btn py-2.5 px-2 rounded-xl text-xs font-bold border transition-all cursor-pointer bg-[var(--field-bg)] text-[var(--text-muted)] border-[var(--field-border)] hover:text-[var(--text)]';
        });
    };

    window.selectDrawerMethod = function(method) {
        window.currentDrawerMethod = method;
        const cardKasir = document.getElementById('method-card-kasir');
        const cardZendo = document.getElementById('method-card-zendo');
        const fieldTable = document.getElementById('drawer-field-table');
        const fieldPayment = document.getElementById('drawer-field-payment');
        const fieldAddress = document.getElementById('drawer-field-address');
        const submitText = document.getElementById('drawer-submit-text');

        const dotKasir = cardKasir ? cardKasir.querySelector('.method-dot') : null;
        const dotZendo = cardZendo ? cardZendo.querySelector('.method-dot') : null;
        const iconKasir = cardKasir ? cardKasir.querySelector('.material-symbols-outlined') : null;
        const iconZendo = cardZendo ? cardZendo.querySelector('.material-symbols-outlined') : null;

        if (method === 'kasir') {
            if (cardKasir) {
                cardKasir.classList.add('border-[var(--accent-solid)]');
                cardKasir.classList.remove('border-[var(--border)]');
            }
            if (cardZendo) {
                cardZendo.classList.remove('border-[var(--accent-solid)]');
                cardZendo.classList.add('border-[var(--border)]');
            }
            if (dotKasir) { dotKasir.className = 'method-dot w-2.5 h-2.5 rounded-full bg-[var(--accent-solid)]'; }
            if (dotZendo) { dotZendo.className = 'method-dot w-2.5 h-2.5 rounded-full bg-[var(--border-strong)]'; }
            if (iconKasir) { iconKasir.className = 'material-symbols-outlined text-[var(--text)] text-xl'; }
            if (iconZendo) { iconZendo.className = 'material-symbols-outlined text-[var(--text-muted)] text-xl'; }

            if (fieldTable) fieldTable.classList.remove('hidden');
            if (fieldPayment) fieldPayment.classList.remove('hidden');
            if (fieldAddress) fieldAddress.classList.add('hidden');
            if (submitText) submitText.innerText = 'Konfirmasi Pesanan (Kasir)';
        } else {
            if (cardZendo) {
                cardZendo.classList.add('border-[var(--accent-solid)]');
                cardZendo.classList.remove('border-[var(--border)]');
            }
            if (cardKasir) {
                cardKasir.classList.remove('border-[var(--accent-solid)]');
                cardKasir.classList.add('border-[var(--border)]');
            }
            if (dotZendo) { dotZendo.className = 'method-dot w-2.5 h-2.5 rounded-full bg-[var(--accent-solid)]'; }
            if (dotKasir) { dotKasir.className = 'method-dot w-2.5 h-2.5 rounded-full bg-[var(--border-strong)]'; }
            if (iconZendo) { iconZendo.className = 'material-symbols-outlined text-[var(--text)] text-xl'; }
            if (iconKasir) { iconKasir.className = 'material-symbols-outlined text-[var(--text-muted)] text-xl'; }

            if (fieldTable) fieldTable.classList.add('hidden');
            if (fieldPayment) fieldPayment.classList.add('hidden');
            if (fieldAddress) fieldAddress.classList.remove('hidden');
            if (submitText) submitText.innerText = 'Konfirmasi Pesanan (Kurir Zendo)';
        }
    };
    window.setModalProcessMethod = window.selectDrawerMethod;

    // ============================================
    // CHECKOUT FORM SUBMISSION
    // ============================================
    window.submitDrawerCheckout = async function(e) {
        e.preventDefault();

        const nameInput = document.getElementById('drawer-customer-name');
        const phoneInput = document.getElementById('drawer-customer-phone');
        const tableInput = document.getElementById('drawer-customer-table');
        const addressInput = document.getElementById('drawer-customer-address');
        const notesInput = document.getElementById('drawer-order-notes');
        const errorEl = document.getElementById('drawer-checkout-error');
        const submitBtn = document.getElementById('drawer-submit-btn');
        const submitSpinner = document.getElementById('drawer-submit-spinner');
        const submitText = document.getElementById('drawer-submit-text');

        if (errorEl) errorEl.classList.add('hidden');

        const customerName = (nameInput ? nameInput.value : '').trim();
        const customerPhone = (phoneInput ? phoneInput.value : '').trim();
        const isZendo = window.currentDrawerMethod === 'zendo';
        const address = (addressInput ? addressInput.value : '').trim();
        const tableNumber = (tableInput ? tableInput.value : '').trim();
        const notes = (notesInput ? notesInput.value : '').trim();

        if (!customerName || !customerPhone) {
            if (errorEl) {
                errorEl.innerText = 'Mohon lengkapi Nama Pemesan dan Nomor WhatsApp.';
                errorEl.classList.remove('hidden');
            }
            return;
        }

        if (isZendo && !address) {
            if (errorEl) {
                errorEl.innerText = 'Mohon masukkan alamat lengkap pengantaran Zendo.';
                errorEl.classList.remove('hidden');
            }
            return;
        }

        const items = Object.values(window.nucomuCart);
        if (items.length === 0) {
            if (errorEl) {
                errorEl.innerText = 'Keranjang Anda masih kosong.';
                errorEl.classList.remove('hidden');
            }
            return;
        }

        // Format items for payload with complete keys matching server validation
        const payloadItems = items.map(it => {
            const qty = parseInt(it.qty, 10) || 1;
            const price = parseInt(it.price, 10) || 0;
            return {
                id: it.id,
                menu_id: it.id,
                name: it.name,
                menu_name: it.name,
                qty: qty,
                price: price,
                unit_price: price,
                line_total: price * qty,
                options: it.options || [],
                notes: it.optionsStr || ''
            };
        });

        let subtotal = 0;
        payloadItems.forEach(it => subtotal += it.line_total);

        const selectedPayMethod = isZendo
            ? 'cash'
            : (document.getElementById('drawer-payment-method')?.value || 'cash');

        const payload = {
            process_method: isZendo ? 'zendo' : 'kasir',
            customer_name: customerName,
            customer_phone: customerPhone,
            table_no: tableNumber,
            table_number: tableNumber,
            dine_type: tableNumber ? 'dine_in' : 'take_away',
            delivery_address: address,
            notes: notes,
            payment_method: selectedPayMethod,
            items: payloadItems,
            total: subtotal,
            subtotal: subtotal,
            grand_total: subtotal
        };

        // UI loading state
        if (submitBtn) submitBtn.disabled = true;
        if (submitSpinner) submitSpinner.classList.remove('hidden');
        if (submitText) submitText.innerText = 'Memproses Pesanan...';

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.content : '{{ csrf_token() }}';

            // Resolve endpoints dynamically so it works seamlessly on root domain or subdirectory (Laragon /umkm/public)
            const primaryEndpoint = '{{ route("api.transactions.process", [], false) }}';
            const fallbackEndpoint = '{{ route("nucomu.order.store", [], false) }}';

            let res;
            try {
                res = await fetch(primaryEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });
            } catch (fetchErr) {
                console.warn('Primary endpoint network error, falling back to /pesan:', fetchErr);
            }

            // Fallback to /pesan if primary endpoint returned 404 or failed
            if (!res || res.status === 404) {
                res = await fetch(fallbackEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });
            }

            const data = await res.json();

            if (!res.ok || !data.success) {
                throw new Error(data.message || 'Gagal memproses pesanan.');
            }

            // Clear Cart
            window.nucomuCart = {};
            try { localStorage.removeItem('nucomu_cart'); } catch (err) {}
            renderCartDrawer();

            // Populate Success View
            const codeEl = document.getElementById('success-order-code');
            const nameEl = document.getElementById('success-customer-name');
            const typeEl = document.getElementById('success-order-type');
            const totalEl = document.getElementById('success-total-amount');
            const slipLink = document.getElementById('success-slip-link');
            const waLink = document.getElementById('success-wa-link');
            const subtextEl = document.getElementById('success-subtext');
            const qrContainer = document.getElementById('drawer-qr-container');

            if (codeEl) codeEl.innerText = data.order_code || 'NCM-ORDER';
            if (nameEl) nameEl.innerText = customerName;
            if (typeEl) typeEl.innerText = isZendo ? 'Kurir Zendo' : (tableNumber ? `Kasir (Meja ${tableNumber})` : 'Kasir (Take Away)');
            if (totalEl) totalEl.innerText = 'Rp ' + (data.grand_total || subtotal).toLocaleString('id-ID');

            if (isZendo) {
                if (subtextEl) {
                    subtextEl.innerText = data.courier_name 
                        ? `Pesanan Anda akan diantar oleh ${data.courier_name}. Klik tombol di bawah untuk konfirmasi WhatsApp.`
                        : 'Pesanan Kurir Zendo berhasil dibuat. Silakan konfirmasi ke WhatsApp kurir.';
                }
                if (qrContainer) qrContainer.classList.add('hidden');
                if (slipLink) slipLink.classList.add('hidden');
                if (waLink && data.whatsapp_url) {
                    waLink.href = data.whatsapp_url;
                    waLink.classList.remove('hidden');
                }
            } else {
                if (subtextEl) {
                    subtextEl.innerText = 'Tunjukkan QR code ini ke kasir untuk proses pembayaran.';
                }
                if (qrContainer) qrContainer.classList.remove('hidden');
                if (waLink) waLink.classList.add('hidden');
                if (slipLink) {
                    if (data.slip_url) {
                        slipLink.href = data.slip_url;
                        slipLink.classList.remove('hidden');
                    } else {
                        slipLink.classList.add('hidden');
                    }
                }
                // Render QR code
                generateDrawerQrCode(data.order_code || 'NCM-ORDER', data.slip_url || '');
            }

            // Switch to success view
            switchCartView('success');

        } catch (err) {
            if (errorEl) {
                errorEl.innerText = err.message || 'Terjadi kesalahan sistem saat memproses pesanan.';
                errorEl.classList.remove('hidden');
            }
        } finally {
            if (submitBtn) submitBtn.disabled = false;
            if (submitSpinner) submitSpinner.classList.add('hidden');
            if (submitText) submitText.innerText = isZendo ? 'Konfirmasi Pesanan (Kurir Zendo)' : 'Konfirmasi Pesanan (Kasir)';
        }
    };

    function generateDrawerQrCode(orderCode, slipUrl) {
        const target = document.getElementById('drawer-qrcode-target');
        const fallback = document.getElementById('drawer-qr-fallback');
        if (!target) return;

        target.innerHTML = '';
        const qrContent = slipUrl || orderCode;

        if (typeof QRCode !== 'undefined') {
            try {
                new QRCode(target, {
                    text: qrContent,
                    width: 150,
                    height: 150,
                    colorDark: '#191919',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M
                });
                return;
            } catch (e) {}
        }

        if (fallback) {
            fallback.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrContent)}&color=191919`;
            fallback.classList.remove('hidden');
        }
    }

    window.resetDrawerToCart = function() {
        switchCartView('items');
        closeCartDrawer();
    };

    // Initial render on page load
    document.addEventListener('DOMContentLoaded', () => {
        renderCartDrawer();
    });
</script>
