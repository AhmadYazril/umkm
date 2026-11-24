<!-- SECTION 3: MENU SHOWCASE (EDITORIAL FOOD GALLERY, CATEGORIES & BEST MENU) -->
<section id="menu" class="pt-16 sm:pt-20 lg:pt-24 pb-12 sm:pb-16 lg:pb-20 bg-[#FFFFFF] border-t border-b border-[#E5E4E0] relative overflow-x-clip">

    <!-- =========================================================================
         PART 0: BEST MENU INTERACTIVE SHOWCASE (EDITORIAL MONOCHROME)
         Palette: Pure White #FFFFFF, Ink Black #191919, Subtle Border #E5E4E0
         ========================================================================= -->
    <div class="relative z-10 max-w-7xl xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 mb-12 sm:mb-14 lg:mb-16">
        
        <!-- Section Title & Subtitle (Editorial Cormorant Garamond + DM Sans) -->
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10 reveal-on-scroll flex flex-col items-center">
            <h2 class="nucomu-best-title text-[#191919] uppercase mx-auto">
                BEST MENU
            </h2>
            <p class="nucomu-sans text-[13px] sm:text-sm text-[#8A8A86] mt-2.5 max-w-xl mx-auto leading-relaxed">
                Explore our curated selection of fine artisanal coffees and gourmet delights.
            </p>
        </div>

        <!-- The Showcase Card (Crisp Pure White Elevated on Warm Ivory) -->
        <div class="relative bg-[#FFFFFF] rounded-[28px] sm:rounded-[36px] shadow-[0_14px_40px_-14px_rgba(25,25,25,0.08)] border border-[#E5E4E0] p-6 sm:p-10 lg:p-14 overflow-hidden">
            
            <!-- Subtle Editorial Watermark in Background -->
            <div class="nucomu-serif-display absolute -left-4 top-1/2 -translate-y-1/2 select-none pointer-events-none opacity-[0.03] font-semibold text-[110px] sm:text-[170px] lg:text-[210px] leading-none text-[#191919] z-0 tracking-tight">
                FOOD<br>ZONE
            </div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- 1. LEFT COLUMN: Editorial Display Typography & Menu Story (~4 cols) -->
                <div class="lg:col-span-4 flex flex-col justify-center text-left">
                    <span class="nucomu-sans text-[10.5px] uppercase tracking-[0.22em] font-semibold text-[#8A8A86] block mb-2.5">
                        FOOD ZONE
                    </span>
                    <div class="flex items-center space-x-2 mb-2.5">
                        <span class="nucomu-sans text-[11px] uppercase tracking-[0.18em] font-semibold text-[#191919]">FEATURED</span>
                        <span class="material-symbols-outlined text-sm text-[#8A8A86] font-light">restaurant</span>
                    </div>
                    <h3 id="best-hero-title" class="nucomu-serif-display text-4xl sm:text-5xl lg:text-[54px] font-semibold text-[#191919] leading-[1.05] tracking-[-0.02em] transition-all duration-300">
                        {!! $bestMenu[0]['display_title'] ?? $bestMenu[0]['name'] !!}
                    </h3>

                    <div class="flex items-center space-x-3 my-4">
                        <span class="w-7 h-px bg-[#191919]"></span>
                        <span id="best-hero-category" class="nucomu-sans text-xs font-medium tracking-wide text-[#30302D]">
                            {{ $bestMenu[0]['category_label'] }}
                        </span>
                    </div>

                    <p id="best-hero-desc" class="nucomu-sans text-[13px] sm:text-sm text-[#8A8A86] leading-relaxed max-w-sm transition-all duration-300 min-h-[3rem]">
                        {{ $bestMenu[0]['description'] }}
                    </p>

                    <div class="mt-8 flex items-center space-x-2 select-none opacity-55">
                        <span class="material-symbols-outlined text-sm text-[#30302D]">verified</span>
                        <span class="nucomu-sans text-[9.5px] font-semibold tracking-[0.26em] uppercase text-[#30302D]">NUCOMU</span>
                    </div>
                </div>

                <!-- 2. CENTER COLUMN: Prominent Circular Dish Plate, Ink CTA Pill, and Slide Dots (~5 cols) -->
                <div class="lg:col-span-5 flex flex-col items-center justify-center my-4 lg:my-0">
                    <!-- Big Round Plate with Editorial White Frame -->
                    <div id="best-hero-dish-wrapper" class="relative w-64 h-64 sm:w-72 sm:h-72 md:w-80 md:h-80 rounded-full p-2.5 bg-[#FFFFFF] shadow-[0_18px_44px_-14px_rgba(25,25,25,0.16)] transition-all duration-500 ease-out border border-[#E5E4E0] flex items-center justify-center">
                        <button type="button"
                                onclick="openCurrentBestMenuPhoto()"
                                aria-label="Lihat foto {{ $bestMenu[0]['name'] }}"
                                title="Klik untuk melihat foto"
                                class="group relative w-full h-full rounded-full overflow-hidden cursor-pointer focus:outline-none">
                            <img id="best-hero-img" 
                                 src="{{ $bestMenu[0]['image'] }}" 
                                 alt="{{ $bestMenu[0]['name'] }}" 
                                 class="w-full h-full object-cover rounded-full shadow-inner transition-transform duration-500 ease-out group-hover:scale-[1.06]">
                        </button>
                    </div>

                    <!-- Ink Black Pill Order Button Underneath -->
                    <div class="mt-6 sm:-mt-5 relative z-20">
                        <button id="best-hero-order-btn" 
                                type="button" 
                                onclick="orderCurrentBestMenuItem()" 
                                class="nucomu-sans group px-7 sm:px-8 py-2.5 rounded-full bg-[#191919] hover:bg-[#30302D] text-[#FFFFFF] font-semibold text-xs sm:text-[13px] uppercase tracking-[0.12em] flex items-center space-x-2 shadow-[0_10px_24px_-6px_rgba(25,25,25,0.28)] transition-all duration-200 hover:-translate-y-0.5 active:scale-95 cursor-pointer">
                            <span id="best-hero-price" class="font-bold tabular-nums">{{ $bestMenu[0]['price_formatted'] }}</span>
                            <span class="font-semibold tracking-[0.14em]">ORDER</span>
                            <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-0.5">chevron_right</span>
                        </button>
                    </div>

                    <!-- Slide Dots Indicator -->
                    <div class="flex items-center space-x-1.5 mt-4 sm:mt-6" id="best-dots-container">
                        @foreach($bestMenu as $idx => $m)
                            <button type="button" 
                                    onclick="switchBestMenuItem({{ $idx }})" 
                                    class="best-menu-dot {{ $idx === 0 ? 'w-5 h-1.5 bg-[#191919]' : 'w-1.5 h-1.5 bg-[#D6D4CD] hover:bg-[#8A8A86]' }} rounded-full transition-all duration-300" 
                                    aria-label="Menu {{ $idx + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 3. RIGHT COLUMN: Interactive Vertical Stack of Pills on Desktop, Compact 2x2 Grid on Mobile -->
                <div class="lg:col-span-3 grid grid-cols-2 gap-2.5 lg:flex lg:flex-col lg:gap-0 lg:space-y-2.5 lg:-mr-14">
                    @foreach($bestMenu as $idx => $m)
                        <div onclick="switchBestMenuItem({{ $idx }})" 
                             class="best-menu-pill {{ $idx === 0 ? 'active bg-[#191919] text-white shadow-md' : 'bg-[#FFFFFF] hover:bg-[#F5F5F5] text-[#191919] border border-[#E5E4E0]' }} flex items-center justify-between p-2 sm:p-2.5 pl-2.5 sm:pl-4 rounded-2xl lg:rounded-l-full transition-all duration-300 cursor-pointer group hover:-translate-x-1">
                            
                            <!-- Left: Thumbnail in white circle -->
                            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-full overflow-hidden p-0.5 bg-[#FFFFFF] border border-[#E5E4E0] shadow-2xs shrink-0">
                                <img src="{{ $m['image'] }}" 
                                     alt="{{ $m['name'] }}" 
                                     class="w-full h-full object-cover rounded-full group-hover:scale-[1.06] transition-transform duration-300">
                            </div>

                            <!-- Right Info: Title & Price -->
                            <div class="flex-1 ml-2 sm:ml-3 mr-1 sm:mr-2 text-left min-w-0">
                                <span class="pill-title nucomu-sans text-[10px] sm:text-xs font-bold uppercase tracking-wider block leading-tight truncate {{ $idx === 0 ? 'text-white' : 'text-[#191919]' }}">
                                    {{ $m['name'] }}
                                </span>
                                <span class="pill-price nucomu-sans text-[11px] sm:text-[13px] font-medium block mt-0.5 tabular-nums {{ $idx === 0 ? 'text-white/80' : 'text-[#8A8A86]' }}">
                                    {{ $m['price_formatted'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <!-- Data Injection for Instant Reactive Switching -->
    <script>
        window.nucomuBestMenuItems = @json($bestMenu);
    </script>


    <!-- =========================================================================
         PART 1: EDITORIAL FOOD GALLERY — MENU NAVIGATION, CATEGORIES & FAVORITES
         ========================================================================= -->
    <style>
        #menu {
            --menu-ink: #191919;
            --menu-charcoal: #30302D;
            --menu-ivory: #FFFFFF;
            --menu-white: #FFFFFF;
            --menu-border: #E5E4E0;
            --menu-muted: #8A8A86;
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
        }
        #menu :focus-visible {
            outline: 2px solid var(--menu-ink);
            outline-offset: 3px;
        }
        #menu .nucomu-sans,
        #menu .font-body,
        #menu .font-manrope,
        #menu .option-group-title,
        #menu .radio-pill-label,
        #menu .menu-card-desc,
        #menu .btn-add-cart {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
        }
        #menu .nucomu-serif-display,
        #menu .nucomu-best-title,
        #menu .nucomu-explore-display,
        #menu .nucomu-modal-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }
        #menu .nucomu-best-title {
            font-size: clamp(28px, 3vw, 38px);
            font-weight: 600;
            line-height: 1.1;
            letter-spacing: 0.06em;
            color: var(--menu-ink);
        }

        /* Active BEST MENU Pill Contrast & Dot Override (Keeps nucomu.js switcher seamless) */
        #menu .best-menu-pill {
            background-color: var(--menu-white);
            border: 1px solid var(--menu-border);
            color: var(--menu-ink);
        }
        #menu .best-menu-pill:hover {
            border-color: rgba(25, 25, 25, 0.25);
        }
        #menu .best-menu-pill.active {
            background-color: var(--menu-ink) !important;
            border-color: var(--menu-ink) !important;
            color: var(--menu-white) !important;
            box-shadow: 0 12px 26px -8px rgba(25, 25, 25, 0.26) !important;
        }
        #menu .best-menu-pill.active .pill-title {
            color: var(--menu-white) !important;
        }
        #menu .best-menu-pill.active .pill-price {
            color: rgba(255, 255, 255, 0.78) !important;
        }
        #menu .best-menu-dot.w-5 {
            background-color: var(--menu-ink) !important;
        }

        /* Editorial Food Gallery Heading: EXPLORE OUR / Menu */
        #menu .nucomu-explore-title {
            color: var(--menu-ink);
            margin-bottom: 0;
        }
        #menu .nucomu-explore-kicker {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.28em;
            line-height: 1.2;
            color: var(--menu-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 4px;
            padding-left: 0.28em;
        }
        #menu .nucomu-explore-kicker::before,
        #menu .nucomu-explore-kicker::after {
            content: '';
            width: 24px;
            height: 1px;
            background-color: #D2D0CA;
        }
        #menu .nucomu-explore-display {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(42px, 4.6vw, 56px);
            font-weight: 600;
            font-style: italic;
            line-height: 1.02;
            letter-spacing: -0.02em;
            color: var(--menu-ink);
            display: block;
        }

        /* Modal Title */
        #menu .nucomu-modal-title {
            font-size: clamp(24px, 2.2vw, 30px);
            font-weight: 600;
            font-style: normal;
            line-height: 1.15;
            letter-spacing: -0.015em;
            color: var(--menu-ink);
        }

        /* Circular Category Navigation */
        #menu .nucomu-cat-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        #menu .nucomu-cat-scroll::-webkit-scrollbar {
            display: none;
        }
        #menu .cat-circle-img-wrap {
            background-color: var(--menu-white);
            border: 1px solid var(--menu-border);
            box-shadow: 0 8px 20px -6px rgba(25, 25, 25, 0.09);
        }
        #menu .cat-circle-label {
            position: relative;
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: clamp(14px, 1.05vw, 15.5px);
            font-weight: 500;
            line-height: 1.35;
            letter-spacing: -0.005em;
            color: var(--menu-charcoal);
            padding-bottom: 6px;
        }
        #menu .cat-circle-label::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 20px;
            height: 1.5px;
            background-color: var(--menu-ink);
            transform: translateX(-50%) scaleX(0);
            transform-origin: center;
            opacity: 0;
            transition: transform 280ms cubic-bezier(0.16, 1, 0.3, 1), opacity 220ms ease;
        }
        #menu .cat-circle-btn.active-cat .cat-circle-img-wrap {
            border-color: var(--menu-ink) !important;
            box-shadow:
                0 0 0 2px var(--menu-ivory),
                0 0 0 3.5px var(--menu-ink),
                0 12px 24px -6px rgba(25, 25, 25, 0.16) !important;
        }
        #menu .cat-circle-btn.active-cat .cat-circle-label {
            color: var(--menu-ink) !important;
            font-weight: 700 !important;
        }
        #menu .cat-circle-btn.active-cat .cat-circle-label::after {
            transform: translateX(-50%) scaleX(1);
            opacity: 1;
        }

        /* Editorial Monochrome Carousel Controls (Placed cleanly ABOVE menu grid) */
        #menu .nucomu-menu-nav-arrow {
            width: 34px;
            height: 34px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background-color: var(--menu-white);
            border: 1px solid var(--menu-border);
            color: var(--menu-ink);
            cursor: pointer;
            transition:
                background-color 180ms ease,
                border-color 180ms ease,
                color 180ms ease,
                transform 180ms ease,
                opacity 180ms ease;
        }
        #menu .nucomu-menu-nav-arrow:hover:not(:disabled) {
            background-color: var(--menu-ink);
            border-color: var(--menu-ink);
            color: var(--menu-white);
        }
        #menu .nucomu-menu-nav-arrow:active:not(:disabled) {
            transform: scale(0.94);
        }
        #menu .nucomu-menu-nav-arrow:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }
        #menu .nucomu-menu-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
        }
        #menu .nucomu-menu-dot {
            width: 18px;
            height: 24px;
            padding: 0;
            border: 0;
            border-radius: 9999px;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: width 220ms ease;
        }
        #menu .nucomu-menu-dot::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background-color: #D2D0CA;
            transition: width 220ms cubic-bezier(0.22, 1, 0.36, 1), background-color 200ms ease;
        }
        #menu .nucomu-menu-dot:hover::before {
            background-color: var(--menu-charcoal);
        }
        #menu .nucomu-menu-dot.is-active {
            width: 26px;
        }
        #menu .nucomu-menu-dot.is-active::before {
            width: 18px;
            background-color: var(--menu-ink);
        }

        /* Paginated 2-Row Horizontal Slider Track */
        #menu #recommendations-viewport {
            overflow-x: clip;
            overflow-y: visible;
            touch-action: pan-y;
        }
        #menu #recommendations-columns-container {
            display: block;
            width: 100%;
        }
        #menu .menu-slide-track {
            display: flex;
            align-items: flex-start;
            gap: 24px;
            width: 100%;
            transition: transform 460ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform;
        }
        #menu .menu-page-slide {
            flex: 0 0 100%;
            width: 100%;
            min-width: 100%;
            box-sizing: border-box;
        }
        #menu .menu-page-slide.is-collapsed-page {
            height: 0 !important;
            min-height: 0 !important;
            overflow: hidden !important;
            visibility: hidden;
            pointer-events: none;
        }

        /* Editorial Monochrome 5-Column Grid & Elevated Pure White Card Surface */
        #menu .menu-columns-container {
            gap: 16px;
        }
        @media (min-width: 640px) {
            #menu .menu-columns-container {
                gap: 24px;
            }
        }
        @media (min-width: 1024px) {
            #menu .menu-columns-container {
                gap: 28px;
            }
        }
        #menu .menu-column {
            gap: 92px;
        }
        @media (min-width: 640px) {
            #menu .menu-column {
                gap: 108px;
            }
        }
        #menu .menu-card {
            background-color: var(--menu-white);
            border: 1px solid var(--menu-border);
            border-radius: 20px;
            box-shadow:
                0 2px 10px -2px rgba(25, 25, 25, 0.04),
                0 12px 28px -10px rgba(25, 25, 25, 0.08);
            padding: 50px 14px 16px 14px;
            transition:
                transform 280ms cubic-bezier(0.16, 1, 0.3, 1),
                border-color 280ms ease,
                box-shadow 280ms ease;
        }
        @media (min-width: 640px) {
            #menu .menu-card {
                border-radius: 24px;
                padding: 62px 16px 18px 16px;
            }
        }
        @media (hover: hover) and (pointer: fine) {
            #menu .menu-card:not(.is-open):hover {
                transform: translateY(-3px);
                border-color: rgba(25, 25, 25, 0.22);
                box-shadow:
                    0 8px 18px -4px rgba(25, 25, 25, 0.06),
                    0 20px 36px -10px rgba(25, 25, 25, 0.12);
            }
        }
        #menu .menu-card.is-open {
            border-color: rgba(25, 25, 25, 0.24) !important;
            box-shadow:
                0 10px 22px -4px rgba(25, 25, 25, 0.07),
                0 24px 44px -10px rgba(25, 25, 25, 0.14) !important;
        }
        #menu .menu-card-avatar {
            top: -42px;
            width: 84px;
            height: 84px;
            border: 3px solid var(--menu-white);
            background-color: var(--menu-white);
            box-shadow:
                0 0 0 1px rgba(25, 25, 25, 0.06),
                0 6px 16px rgba(25, 25, 25, 0.10),
                0 12px 24px -6px rgba(25, 25, 25, 0.12);
        }
        @media (min-width: 640px) {
            #menu .menu-card-avatar {
                top: -58px;
                width: 114px;
                height: 114px;
                border-width: 4px;
            }
        }
        #menu .menu-card-name {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.28;
            letter-spacing: -0.01em;
            color: var(--menu-ink);
            min-height: 2.65rem;
            margin-bottom: 4px;
        }
        #menu .menu-card-price-row {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 15.5px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--menu-ink);
            gap: 4px;
            font-variant-numeric: tabular-nums lining-nums;
        }
        #menu .menu-card-price-row .font-editorial-num {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 15.5px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--menu-ink);
        }
        @media (min-width: 1024px) {
            #menu .menu-card-price-row,
            #menu .menu-card-price-row .font-editorial-num {
                font-size: 16.5px;
            }
        }
        #menu .menu-card-unit {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 12.5px;
            font-weight: 400;
            line-height: 1.3;
            color: var(--menu-muted);
        }
        #menu .menu-card-divider {
            background-color: var(--menu-border);
        }
        #menu .option-group-title {
            color: var(--menu-ink);
            font-weight: 700;
            letter-spacing: 0.08em;
        }
        #menu .radio-pill-label {
            color: var(--menu-charcoal);
        }
        #menu .radio-pill-label:hover {
            color: var(--menu-ink);
        }
        #menu .radio-pill-label input[type="radio"] {
            accent-color: var(--menu-ink);
        }
        #menu .menu-card-desc {
            color: var(--menu-muted);
        }
        #menu .btn-add-cart {
            background-color: var(--menu-ink);
            color: var(--menu-white);
            box-shadow: 0 6px 16px -4px rgba(25, 25, 25, 0.22);
        }
        #menu .btn-add-cart:hover {
            background-color: var(--menu-charcoal);
            box-shadow: 0 10px 22px -4px rgba(25, 25, 25, 0.28);
        }
        #menu .btn-add-cart.disabled {
            background-color: var(--menu-muted) !important;
        }

        /* Inline Collapsible Cara Pemesanan 3-Step Strip */
        #menu #cara-pemesanan-panel {
            display: grid;
            grid-template-rows: 0fr;
            opacity: 0;
            margin-top: 0;
            pointer-events: none;
            transition: grid-template-rows 280ms cubic-bezier(0.22, 1, 0.36, 1), opacity 220ms ease, margin-top 280ms cubic-bezier(0.22, 1, 0.36, 1);
        }
        #menu #cara-pemesanan-panel.is-open {
            grid-template-rows: 1fr;
            opacity: 1;
            margin-top: 1.25rem;
            pointer-events: auto;
        }
        #menu #cara-pemesanan-panel > .cara-pemesanan-panel-inner {
            overflow: hidden;
            min-height: 0;
        }
        #menu #cara-pemesanan-trigger[aria-expanded="true"] .cara-arrow-icon {
            transform: rotate(90deg);
        }

        @media (prefers-reduced-motion: reduce) {
            #menu .menu-card,
            #menu .menu-card-avatar,
            #menu .cat-circle-btn,
            #menu .cat-circle-img-wrap img,
            #menu .cat-circle-label::after,
            #menu .menu-slide-track,
            #menu .nucomu-menu-dot,
            #menu .nucomu-menu-dot::before,
            #menu .best-menu-pill,
            #menu #cara-pemesanan-panel,
            #menu .cara-arrow-icon {
                transition: none !important;
                transform: none !important;
            }
            #menu .menu-slide-track {
                transition: none !important;
            }
            #menu .menu-card:hover .menu-card-avatar,
            #menu .menu-card.is-open .menu-card-avatar {
                transform: translateX(-50%) !important;
            }
        }
    </style>

    @php
        $guideDineIn = (bool) ($txSettings['order_mode_dine_in'] ?? true);
        $guideZendo = (bool) (($txSettings['order_mode_zendo_tracking'] ?? false) || ($txSettings['order_mode_zendo_chat'] ?? false));
        $guideActive = (bool) ($txSettings['is_any_mode_active'] ?? ($guideDineIn || $guideZendo));
        $guideStep2Desc = match (true) {
            $guideDineIn && $guideZendo => 'Isi data & pilih Dine-In, Takeaway, atau Antar.',
            $guideZendo => 'Lengkapi alamat pengantaran Kurir Zendo.',
            default => 'Isi data & pilih Dine-In atau Takeaway.',
        };
        $guideStep3Desc = match (true) {
            $guideDineIn && $guideZendo => 'Tunjukkan QR slip ke kasir atau tunggu kurir.',
            $guideZendo => 'Konfirmasi pesanan & pantau pengantaran kurir.',
            default => 'Tunjukkan QR slip pesanan Anda di kasir.',
        };
    @endphp

    <div class="relative z-10 max-w-7xl xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- 1. Menu Navigation: Editorial Food Gallery -->
        <div class="text-center max-w-2xl mx-auto reveal-on-scroll flex flex-col items-center">
            <h2 class="nucomu-explore-title mx-auto flex flex-col items-center" aria-label="Explore Our Menu">
                <span class="nucomu-explore-kicker">Explore Our</span> <span class="nucomu-explore-display">Menu</span>
            </h2>
            <svg class="w-24 sm:w-28 h-2.5 mt-1.5 text-[#191919]/35" viewBox="0 0 120 10" fill="none" aria-hidden="true">
                <path d="M4 7.5C36 2 84 2 116 7.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"/>
            </svg>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="nucomu-cat-scroll mt-6 sm:mt-8 flex flex-nowrap items-start justify-start sm:justify-center overflow-x-auto sm:overflow-visible gap-5 sm:gap-8 md:gap-10 lg:gap-12 -mx-4 px-4 sm:mx-0 sm:px-0 py-3 reveal-on-scroll"
                 role="tablist"
                 aria-label="Kategori Menu">
                @foreach($menuCategories as $cat)
                    <button type="button"
                            onclick="handleCircularCategoryClick('{{ $cat['id'] }}', this)"
                            class="cat-circle-btn group shrink-0 flex flex-col items-center cursor-pointer focus:outline-none rounded-2xl transition-transform duration-300 hover:-translate-y-1"
                            data-cat-id="{{ $cat['id'] }}">
                        <div class="cat-circle-img-wrap w-[78px] h-[78px] sm:w-[90px] sm:h-[90px] md:w-[96px] md:h-[96px] lg:w-[100px] lg:h-[100px] shrink-0 rounded-full overflow-hidden p-[4px] bg-[#FFFFFF] border border-[#E5E4E0] shadow-[0_8px_20px_-6px_rgba(25,25,25,0.09)] group-hover:shadow-[0_12px_26px_-6px_rgba(25,25,25,0.14)] transition-all duration-300">
                            <img src="{{ $cat['image'] }}"
                                 alt="{{ $cat['name'] }}"
                                 class="w-full h-full object-cover rounded-full group-hover:scale-[1.06] transition-transform duration-500 ease-out">
                        </div>
                        <span class="cat-circle-label nucomu-sans mt-3 text-center whitespace-nowrap group-hover:text-[#191919] transition-colors">
                            {{ $cat['name'] }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- 2. Top Checkout Guide & Slide Controls Bar (Above Menu Cards) -->
        <div class="w-full mt-7 sm:mt-9 pb-3.5 border-b border-[#E5E4E0] flex items-center justify-between gap-3 sm:gap-4 reveal-on-scroll">
            <!-- Left: Checkout Guide Toggle Trigger -->
            <div class="flex items-center shrink-0">
                @if($guideActive)
                    <button type="button"
                            id="cara-pemesanan-trigger"
                            onclick="window.toggleCaraPemesananGuide(this)"
                            aria-controls="cara-pemesanan-panel"
                            aria-expanded="false"
                            class="nucomu-sans group shrink-0 inline-flex items-center gap-1.5 bg-transparent p-0 border-0 text-[13px] font-medium text-[#30302D] hover:text-[#191919] transition-colors duration-200 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-[#8A8A86] group-hover:text-[#191919] transition-colors shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <span class="underline underline-offset-[4px] decoration-[#191919]/25 group-hover:decoration-[#191919]">Cara Pemesanan</span>
                        <svg class="cara-arrow-icon w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/>
                            <path d="m13 6 6 6-6 6"/>
                        </svg>
                    </button>
                @else
                    <span class="nucomu-sans text-[13px] font-medium text-[#8A8A86] shrink-0">
                        Pemesanan langsung di kasir
                    </span>
                @endif
            </div>
                    </span>
                @endif
            </div>

            <!-- Right: Editorial Horizontal Pager Controls (Counter + Arrows + Capsule Dots) -->
            <div id="recommendations-carousel-controls"
                 class="hidden items-center gap-2 sm:gap-2.5 ml-auto shrink-0"
                 role="group"
                 aria-label="Navigasi halaman menu">
                <span id="rec-page-counter"
                      class="hidden sm:inline-block nucomu-sans text-[12px] font-medium text-[#8A8A86] tabular-nums tracking-wide select-none">
                    01 / 01
                </span>

                <span class="hidden sm:inline-block w-px h-3.5 bg-[#E5E4E0] mx-0.5" aria-hidden="true"></span>

                <button type="button"
                        id="rec-prev-btn"
                        onclick="if (typeof window.prevMenuPage === 'function') window.prevMenuPage();"
                        aria-label="Halaman menu sebelumnya"
                        class="nucomu-menu-nav-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </button>

                <div id="rec-page-dots"
                     class="nucomu-menu-dots"
                     role="group"
                     aria-label="Pilih halaman menu">
                </div>

                <button type="button"
                        id="rec-next-btn"
                        onclick="if (typeof window.nextMenuPage === 'function') window.nextMenuPage();"
                        aria-label="Halaman menu berikutnya"
                        class="nucomu-menu-nav-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </div>

        @if($guideActive)
            <!-- Inline Minimalist 3-Step Guide Strip (Hidden Until Toggled) -->
            <div id="cara-pemesanan-panel"
                 role="region"
                 aria-label="Cara Pemesanan"
                 aria-hidden="true"
                 inert>
                <div class="cara-pemesanan-panel-inner">
                    <div class="pb-1.5">
                        <div class="w-full rounded-2xl bg-[#FFFFFF] border border-[#E5E4E0] shadow-[0_8px_24px_-12px_rgba(25,25,25,0.07)] overflow-hidden text-left">
                            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#E5E4E0]">
                                <div class="flex items-center gap-3.5 px-5 py-4 sm:px-6 sm:py-[18px]">
                                    <span class="w-9 h-9 rounded-full bg-[#F5F5F5] border border-[#E5E4E0]/80 text-[#30302D] flex items-center justify-center shrink-0" aria-hidden="true">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 8v8"/>
                                            <path d="M8 12h8"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="nucomu-sans text-[13.5px] font-semibold text-[#191919] leading-snug">
                                            01 &middot; Pilih Menu &amp; Varian
                                        </p>
                                        <p class="nucomu-sans text-[12px] text-[#8A8A86] leading-snug mt-0.5">
                                            Ketuk kartu sajian lalu tambah ke keranjang.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3.5 px-5 py-4 sm:px-6 sm:py-[18px]">
                                    <span class="w-9 h-9 rounded-full bg-[#F5F5F5] border border-[#E5E4E0]/80 text-[#30302D] flex items-center justify-center shrink-0" aria-hidden="true">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                            <path d="M3 6h18"/>
                                            <path d="M16 10a4 4 0 0 1-8 0"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="nucomu-sans text-[13.5px] font-semibold text-[#191919] leading-snug">
                                            02 &middot; Checkout di Keranjang
                                        </p>
                                        <p class="nucomu-sans text-[12px] text-[#8A8A86] leading-snug mt-0.5">
                                            {{ $guideStep2Desc }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3.5 px-5 py-4 sm:px-6 sm:py-[18px]">
                                    <span class="w-9 h-9 rounded-full bg-[#F5F5F5] border border-[#E5E4E0]/80 text-[#30302D] flex items-center justify-center shrink-0" aria-hidden="true">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                                            <path d="M14 14h3v3"/>
                                            <path d="M21 14v3h-3"/>
                                            <path d="M14 21h3"/>
                                            <path d="M21 21v-1"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="nucomu-sans text-[13.5px] font-semibold text-[#191919] leading-snug">
                                            03 &middot; Konfirmasi &amp; Bayar
                                        </p>
                                        <p class="nucomu-sans text-[12px] text-[#8A8A86] leading-snug mt-0.5">
                                            {{ $guideStep3Desc }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function () {
                    const syncGuideState = function (isOpen, triggerEl) {
                        const panel = document.getElementById('cara-pemesanan-panel');
                        const trigger = triggerEl || document.getElementById('cara-pemesanan-trigger');
                        if (!panel) {
                            return;
                        }
                        panel.classList.toggle('is-open', isOpen);
                        panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                        panel.inert = !isOpen;
                        if (trigger) {
                            trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                        }
                    };

                    window.toggleCaraPemesananGuide = function (triggerEl) {
                        const panel = document.getElementById('cara-pemesanan-panel');
                        if (!panel) {
                            return;
                        }
                        syncGuideState(!panel.classList.contains('is-open'), triggerEl);
                    };

                    window.closeCaraPemesananGuide = function () {
                        const panel = document.getElementById('cara-pemesanan-panel');
                        if (!panel || !panel.classList.contains('is-open')) {
                            return;
                        }
                        syncGuideState(false);
                    };

                    document.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape') {
                            window.closeCaraPemesananGuide();
                        }
                    });
                })();
            </script>
        @endif

        <!-- 10 Menu Cards N-Column Layout (5 Kolom Desktop, 3 Kolom Tablet, 2 Kolom Mobile) -->
        <div id="recommendations-cards-pool" style="display: none;" aria-hidden="true">
            @foreach($recommendations as $index => $item)
                @php
                    $itemSlug = \Illuminate\Support\Str::slug($item['id'] ?? $item['name']);
                    $cat = $item['category'] ?? 'coffee';
                    $unit = $item['unit'] ?? match(strtolower($cat)) {
                        'coffee', 'kopi', 'non-coffee', 'non-kopi', 'beverages' => '/cup',
                        'dessert' => '/slice',
                        'snack', 'snacks', 'makanan', 'meals' => '/porsi',
                        default => '/porsi'
                    };
                    $hasOptions = !empty($item['options']);
                    $opt1 = $hasOptions ? ($item['options'][0] ?? null) : null;
                    $opt2 = $hasOptions ? ($item['options'][1] ?? null) : null;
                    $isAvailable = $item['is_available'] ?? true;
                @endphp
                <div class="menu-card-wrapper rec-card" 
                     data-index="{{ $index }}"
                     data-category="{{ $item['filter_categories'] ?? $item['category'] ?? '' }}"
                     data-id="{{ $item['id'] ?? $itemSlug }}"
                     data-name="{{ $item['name'] }}"
                     data-price="{{ $item['price'] }}">
                    
                    <article class="menu-card {{ !$isAvailable ? 'opacity-65 grayscale-[30%]' : '' }}"
                             data-card-id="rec-{{ $itemSlug }}-{{ $index }}"
                             data-id="{{ $item['id'] ?? $itemSlug }}"
                             data-name="{{ $item['name'] }}"
                             data-price="{{ $item['price'] }}">
                        
                        <!-- Foto Bulat Menembus Tepi Atas Kartu (Klik untuk melihat foto menu) -->
                        <button type="button"
                                class="menu-card-avatar"
                                onclick="openMenuPhotoPreview(this)"
                                aria-label="Lihat foto {{ $item['name'] }}"
                                title="Klik untuk melihat foto">
                            <img src="{{ $item['image'] }}" 
                                 alt="{{ $item['name'] }}" 
                                 loading="lazy" 
                                 width="130" 
                                 height="130"
                                 class="w-full h-full object-cover">
                            @if(!$isAvailable)
                                <span class="absolute inset-0 bg-black/60 text-white font-bold text-[10px] uppercase tracking-wider flex items-center justify-center">
                                    Habis
                                </span>
                            @endif
                        </button>

                        <!-- Header Trigger: Klik bagian nama & harga kartu untuk buka/tutup detail menu -->
                        <button type="button" 
                                class="menu-card-header-btn" 
                                onclick="toggleMenuCard(this.closest('.menu-card'))"
                                aria-expanded="false"
                                aria-controls="drawer-rec-{{ $itemSlug }}-{{ $index }}"
                                title="Klik untuk membuka detail menu {{ $item['name'] }}">

                            <!-- Judul Menu Serif -->
                            <h4 class="menu-card-name" title="{{ $item['name'] }}">
                                {{ $item['name'] }}
                            </h4>

                            <!-- Baris Harga & Satuan (font-variant-numeric: lining-nums) -->
                            <div class="menu-card-price-row">
                                <span class="font-editorial-num">{{ $item['price_formatted'] }}</span>
                                <span class="menu-card-unit">{{ $unit }}</span>
                            </div>
                        </button>

                        <!-- Drawer yang Mengintip Saat Hover (18px) dan Terbuka Penuh Saat Klik -->
                        <div id="drawer-rec-{{ $itemSlug }}-{{ $index }}" 
                             class="menu-card-drawer" 
                             onclick="event.stopPropagation()">
                            
                            <!-- Garis Pemisah Tipis (Mengintip saat Hover) -->
                            <div class="menu-card-divider"></div>

                            <!-- Isi Menu (Opsi Varian / Deskripsi Singkat + Tombol Add to Cart) -->
                            <div class="menu-card-drawer-content">
                                @if($hasOptions)
                                    <div class="options-columns mb-3">
                                        @if($opt1)
                                        <div>
                                            <span class="option-group-title">{{ $opt1['title'] }}</span>
                                            <div class="space-y-0.5">
                                                @foreach($opt1['choices'] as $cIdx => $choice)
                                                    <label class="radio-pill-label">
                                                        <input type="radio" 
                                                               name="opt1_rec_{{ $itemSlug }}_{{ $index }}" 
                                                               value="{{ $choice }}" 
                                                               {{ $cIdx === 0 ? 'checked' : '' }}>
                                                        <span class="truncate">{{ $choice }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        @if($opt2)
                                        <div>
                                            <span class="option-group-title">{{ $opt2['title'] }}</span>
                                            <div class="space-y-0.5">
                                                @foreach($opt2['choices'] as $cIdx => $choice)
                                                    <label class="radio-pill-label">
                                                        <input type="radio" 
                                                               name="opt2_rec_{{ $itemSlug }}_{{ $index }}" 
                                                               value="{{ $choice }}" 
                                                               {{ $cIdx === 0 ? 'checked' : '' }}>
                                                        <span class="truncate">{{ $choice }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <!-- Menu Tanpa Varian: Deskripsi singkat maks 2 baris -->
                                    <p class="menu-card-desc">
                                        {{ $item['short_info'] ?? 'Sajian autentik barista pilihan Nucomu Cafe.' }}
                                    </p>
                                @endif

                                @if($txSettings['is_any_mode_active'] ?? true)
                                    @if($isAvailable)
                                        <button type="button" 
                                                onclick="submitMenuCardOrder(this.closest('.menu-card'), '{{ addslashes($item['name']) }}', '{{ $item['price_formatted'] }}')"
                                                class="btn-add-cart"
                                                aria-label="Tambah {{ $item['name'] }} ke Keranjang">
                                            <span class="material-symbols-outlined text-sm">shopping_cart</span>
                                            <span>+ Add to Cart</span>
                                        </button>
                                    @else
                                        <button type="button" disabled class="btn-add-cart disabled">
                                            <span>Menu Habis</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>

                    </article>
                </div>
            @endforeach
        </div>

        <!-- Dynamic 2-Row Paginated Horizontal Carousel Viewport -->
        <div id="recommendations-viewport" class="relative w-full overflow-x-clip">
            <div id="recommendations-columns-container" class="mt-2 sm:mt-3 pt-14 sm:pt-16 pb-4 sm:pb-6 transition-opacity duration-200">
                <!-- Distributed into paginated 2-row horizontal slides by script -->
            </div>
        </div>

        <!-- Inline Distribution Trigger -->
        <script>
            if (typeof window.distributeMenuCards === 'function') {
                window.distributeMenuCards('recommendations-cards-pool', 'recommendations-columns-container');
            } else {
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof window.distributeMenuCards === 'function') {
                        window.distributeMenuCards('recommendations-cards-pool', 'recommendations-columns-container');
                    }
                });
            }
        </script>

        <!-- Empty State Fallback -->
        <div id="recommendations-empty-state" class="hidden text-center py-12 bg-[#FFFFFF] rounded-2xl border border-[#E5E4E0] mt-6 mb-8 p-8 shadow-[0_8px_24px_-8px_rgba(25,25,25,0.06)]">
            <span class="material-symbols-outlined text-[#8A8A86] text-4xl mb-2">restaurant_menu</span>
            <p class="nucomu-serif-display text-2xl font-semibold text-[#191919]">Sajian Sedang Disiapkan</p>
            <p class="nucomu-sans text-[13px] text-[#8A8A86] mt-1">Daftar sajian untuk kategori ini sedang diperbarui bersama barista kami.</p>
        </div>

        <!-- Mobile Shortcut to Full Menu Catalog -->
        <div class="md:hidden text-center pb-4">
            <a href="{{ route('menu.all') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-[#191919] text-white text-xs font-bold uppercase tracking-wider shadow-md active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-base">menu_book</span>
                <span>Buka Katalog Menu Lengkap</span>
            </a>
        </div>
    </div>
</section>
