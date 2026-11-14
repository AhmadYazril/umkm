<!-- SECTION 6: UNIFIED REVIEWS SECTION (3D STAGE ANIMATION WITH GOOGLE MAPS CARDS STYLE) -->
@php
    $allReviewsCollection = collect($dynamicReviews ?? []);
    if ($allReviewsCollection->isEmpty()) {
        $allReviewsCollection = collect(config('nucomu.reviews', []))->map(function (array $review) {
            $rawImages = $review['review_images'] ?? (! empty($review['review_image']) ? [$review['review_image']] : []);
            $resolvedImages = collect($rawImages)->filter()->map(function (string $img) {
                return (! str_starts_with($img, 'http://') && ! str_starts_with($img, 'https://')) ? asset(ltrim($img, '/')) : $img;
            })->values()->all();

            return [
                'id' => $review['id'] ?? null,
                'name' => $review['name'] ?? 'Pengunjung Nucomu',
                'handle' => $review['handle'] ?? 'Local Guide · Ulasan',
                'avatar_url' => $review['avatar'] ?? null,
                'rating' => (int) ($review['rating'] ?? 5),
                'content' => $review['comment'] ?? '',
                'order_tag' => $review['order_tag'] ?? 'Menu Nucomu',
                'time_ago' => $review['time_ago'] ?? 'Baru-baru ini',
                'review_image' => $resolvedImages[0] ?? null,
                'review_images' => $resolvedImages,
                'source' => 'google_maps',
            ];
        });
    }
    $reviewsArray = $allReviewsCollection->values()->all();

    // Mapping avatar letter background colors matching Google Reviews
    $avatarPalette = [
        'D' => '#C2185B', // Dya slma (magenta/crimson)
        'K' => '#7B1FA2', // Karina Wulan Cahyani (purple)
        'T' => '#00897B', // Tani Kopi (teal)
        'R' => '#1E88E5', // Rizky Pratama (blue)
        'M' => '#43A047', // Muhammad Kaisi
        'A' => '#E53935',
        'F' => '#3949AB',
        'B' => '#FB8C00',
        'N' => '#00ACC1',
    ];
@endphp

<style>
    /* ======================================================================
       PALET WARNA SECTION REVIEWS (CSS VARIABLES TERPUSAT)
       ====================================================================== */
    #reviews {
        --nucomu-bg-page: #FFFFFF;
        --nucomu-bg-container: #FFFFFF;
        --nucomu-bg-container-fade: rgba(255, 255, 255, 0.75);
        --nucomu-bg-container-transparent: rgba(255, 255, 255, 0);
        --nucomu-border: rgba(43, 29, 22, 0.06);
        --nucomu-container-shadow: 0 2px 10px rgba(43, 29, 22, 0.03);
        --nucomu-text: #2B1D16;
        --nucomu-accent: #54392B;
        --nucomu-star: #E0A030;
        --nucomu-card-bg: #FFFFFF;
        --nucomu-card-border: #EEE8DE;
        --nucomu-card-shadow: 0 3px 10px rgba(43, 29, 22, 0.08), 0 12px 28px -4px rgba(43, 29, 22, 0.16), 0 20px 38px -8px rgba(43, 29, 22, 0.14);
        --nucomu-card-shadow-active: 0 2px 8px rgba(43, 29, 22, 0.08), 0 14px 30px -4px rgba(43, 29, 22, 0.22), 0 26px 50px -10px rgba(43, 29, 22, 0.22);
        --nucomu-card-shadow-hover: 0 6px 14px rgba(43, 29, 22, 0.10), 0 20px 38px -4px rgba(43, 29, 22, 0.26), 0 32px 60px -10px rgba(43, 29, 22, 0.25);
        --nucomu-btn-secondary-bg: #FFFFFF;
        --nucomu-btn-secondary-border: #D8CBB8;
        --nucomu-btn-secondary-hover: #F7F1E8;
        --nucomu-btn-primary-bg: #2B1D16;
        --nucomu-btn-primary-text: #F5EFE6;
        --nucomu-btn-primary-hover: #3A2A21;

        background-color: var(--nucomu-bg-page);
        color: var(--nucomu-text);
    }

    /* ======================================================================
       STAGE CONTAINER & EDGE FADES (BORDER SAMAR AGAR KARTU TERASA LEGA)
       ====================================================================== */
    #reviews .nucomu-reviews-stage {
        background-color: var(--nucomu-bg-container);
        border: 1px solid var(--nucomu-border);
        padding-top: 18px;
        padding-bottom: 18px;
        box-shadow: var(--nucomu-container-shadow);
    }

    #reviews .nucomu-reviews-fade-left {
        background: linear-gradient(
            to right,
            var(--nucomu-bg-container) 0%,
            var(--nucomu-bg-container-fade) 48%,
            var(--nucomu-bg-container-transparent) 100%
        );
    }

    #reviews .nucomu-reviews-fade-right {
        background: linear-gradient(
            to left,
            var(--nucomu-bg-container) 0%,
            var(--nucomu-bg-container-fade) 48%,
            var(--nucomu-bg-container-transparent) 100%
        );
    }

    @media (min-width: 640px) {
        #reviews .nucomu-reviews-stage {
            padding-top: 18px;
            padding-bottom: 20px;
        }
    }

    @media (min-width: 1024px) {
        #reviews .nucomu-reviews-stage {
            padding-top: 20px;
            padding-bottom: 22px;
        }
    }

    /* ======================================================================
       REVIEWS CAROUSEL STAGE (1 KARTU AKTIF TENGAH + KARTU SAMPING RINGAN)
       ====================================================================== */
    #reviews .nucomu-3d-viewport {
        perspective: none;
    }

    #reviews .nucomu-stage-card {
        background-color: var(--nucomu-card-bg);
        border: 1px solid var(--nucomu-card-border);
        box-shadow: var(--nucomu-card-shadow);
        transition:
            transform 300ms ease,
            opacity 300ms ease,
            filter 300ms ease,
            box-shadow 300ms ease;
        will-change: transform, opacity;
        backface-visibility: hidden;
    }

    #reviews .nucomu-stage-card:only-child {
        transform: translate3d(0%, 0px, 0px) !important;
    }

    /* 1. ACTIVE CENTER CARD (Kartu Aktif Utama) */
    #reviews .nucomu-stage-card.is-front-center {
        z-index: 32;
        opacity: 1;
        filter: none;
        transform: translate3d(0%, 0px, 0px) scale(1);
        box-shadow: var(--nucomu-card-shadow-active);
    }

    /* 2. FRONT LEFT CARD (Kartu Tidak Aktif Kiri - Opacity 0.55, Scale 0.94, Tanpa Blur) */
    #reviews .nucomu-stage-card.is-front-left {
        z-index: 25;
        opacity: 0.55;
        filter: none;
        transform: translate3d(calc(-100% - 20px), 0px, 0px) scale(0.94);
        box-shadow: var(--nucomu-card-shadow);
    }

    /* 3. FRONT RIGHT CARD (Kartu Tidak Aktif Kanan - Opacity 0.55, Scale 0.94, Tanpa Blur) */
    #reviews .nucomu-stage-card.is-front-right {
        z-index: 25;
        opacity: 0.55;
        filter: none;
        transform: translate3d(calc(100% + 20px), 0px, 0px) scale(0.94);
        box-shadow: var(--nucomu-card-shadow);
    }

    /* 4. STACKED LEFT CARD (Kartu Tepi Kiri - Tanpa Blur) */
    #reviews .nucomu-stage-card.is-stack-left {
        z-index: 15;
        opacity: 0.35;
        filter: none;
        transform: translate3d(calc(-193% - 36px), 0px, 0px) scale(0.86);
        box-shadow: var(--nucomu-card-shadow);
    }

    /* 5. STACKED RIGHT CARD (Kartu Tepi Kanan - Tanpa Blur) */
    #reviews .nucomu-stage-card.is-stack-right {
        z-index: 15;
        opacity: 0.35;
        filter: none;
        transform: translate3d(calc(193% + 36px), 0px, 0px) scale(0.86);
        box-shadow: var(--nucomu-card-shadow);
    }

    /* HOVER INTERACTION ON DESKTOP ONLY */
    @media (hover: hover) and (min-width: 1024px) {
        #reviews .nucomu-stage-card.is-front-center:hover {
            transform: translate3d(0%, -4px, 0px) scale(1.01);
            box-shadow: var(--nucomu-card-shadow-hover);
        }
        #reviews .nucomu-stage-card.is-front-left:hover {
            opacity: 0.85;
            transform: translate3d(calc(-100% - 20px), -3px, 0px) scale(0.96);
            box-shadow: var(--nucomu-card-shadow-active);
        }
        #reviews .nucomu-stage-card.is-front-right:hover {
            opacity: 0.85;
            transform: translate3d(calc(100% + 20px), -3px, 0px) scale(0.96);
            box-shadow: var(--nucomu-card-shadow-active);
        }
        #reviews .nucomu-stage-card.is-stack-left:hover {
            opacity: 0.65;
            transform: translate3d(calc(-193% - 36px), -3px, 0px) scale(0.88);
        }
        #reviews .nucomu-stage-card.is-stack-right:hover {
            opacity: 0.65;
            transform: translate3d(calc(193% + 36px), -3px, 0px) scale(0.88);
        }
    }

    /* ALL OTHER CARDS HIDDEN IN BACKGROUND */
    #reviews .nucomu-stage-card.is-hidden-left,
    #reviews .nucomu-stage-card.is-hidden-right {
        z-index: 1;
        opacity: 0;
        pointer-events: none;
        transform: translate3d(0%, 15px, 0px) scale(0.7);
    }

    /* RESPONSIVE ON TABLET */
    @media (max-width: 1023px) {
        #reviews .nucomu-stage-card.is-front-center {
            transform: translate3d(0%, 0px, 0px) scale(1);
            z-index: 32;
            opacity: 1;
            filter: none;
        }
        #reviews .nucomu-stage-card.is-front-left {
            transform: translate3d(calc(-96% - 14px), 0px, 0px) scale(0.94);
            z-index: 25;
            opacity: 0.55;
            filter: none;
        }
        #reviews .nucomu-stage-card.is-front-right {
            transform: translate3d(calc(96% + 14px), 0px, 0px) scale(0.94);
            z-index: 25;
            opacity: 0.55;
            filter: none;
        }
        #reviews .nucomu-stage-card.is-stack-left,
        #reviews .nucomu-stage-card.is-stack-right {
            opacity: 0;
            pointer-events: none;
            transform: translate3d(0%, 15px, 0px) scale(0.7);
        }
    }

    /* RESPONSIVE ON MOBILE (1 KARTU UTAMA RAPI DI TENGAH + PEEK KIRI KANAN) */
    @media (max-width: 767px) {
        #reviews .nucomu-stage-card.is-front-center {
            transform: translate3d(0%, 0px, 0px) scale(1);
            z-index: 30;
            opacity: 1;
            filter: none;
        }
        #reviews .nucomu-stage-card.is-front-right {
            transform: translate3d(calc(100% + 14px), 0px, 0px) scale(0.94);
            z-index: 20;
            opacity: 0.55;
            filter: none;
        }
        #reviews .nucomu-stage-card.is-front-left {
            transform: translate3d(calc(-100% - 14px), 0px, 0px) scale(0.94);
            z-index: 20;
            opacity: 0.55;
            filter: none;
        }
        #reviews .nucomu-stage-card.is-stack-left,
        #reviews .nucomu-stage-card.is-stack-right {
            opacity: 0;
            pointer-events: none;
            transform: translate3d(0%, 15px, 0px) scale(0.7);
        }
    }

    /* ======================================================================
       SECTION HEADER EDITORIAL (SPLIT DESKTOP 1 BARIS, STACK RATA KIRI MOBILE)
       ====================================================================== */
    #reviews .nucomu-reviews-header {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        gap: 16px;
        margin-bottom: 24px;
        padding: 0;
        position: relative;
        z-index: 10;
        opacity: 1;
        filter: none;
    }

    #reviews .nucomu-reviews-header-left {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        min-width: 0;
        flex: 1 1 auto;
    }

    #reviews .nucomu-reviews-eyebrow {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        line-height: 1.2;
        color: var(--nucomu-accent);
        margin-bottom: 8px;
    }

    #reviews .nucomu-reviews-header-title {
        font-family: "Fraunces", Georgia, "Times New Roman", serif;
        font-size: clamp(2rem, 4vw, 3.25rem);
        font-weight: 500;
        font-style: normal;
        line-height: 1.05;
        letter-spacing: -0.01em;
        color: var(--nucomu-text);
        opacity: 1;
        filter: none;
        margin: 0;
    }

    #reviews .nucomu-reviews-header-title-accent {
        font-style: italic;
        font-weight: 500;
        color: var(--nucomu-accent);
    }

    #reviews .nucomu-reviews-header-right {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        gap: 12px;
        width: 100%;
    }

    #reviews .nucomu-review-stars {
        color: var(--nucomu-star);
    }

    #reviews .nucomu-reviews-header-actions {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    #reviews .nucomu-reviews-btn-secondary,
    #reviews .nucomu-reviews-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: auto;
        min-height: 40px;
        padding: 8px 14px;
        border-radius: 9999px;
        font-size: 13px;
        line-height: 1.25;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition:
            background-color 180ms ease,
            border-color 180ms ease,
            color 180ms ease,
            transform 180ms ease,
            box-shadow 180ms ease;
    }

    #reviews .nucomu-reviews-btn-secondary {
        background-color: var(--nucomu-btn-secondary-bg);
        border: 1px solid var(--nucomu-btn-secondary-border);
        color: var(--nucomu-text);
        font-weight: 500;
    }

    #reviews .nucomu-reviews-btn-secondary:hover {
        background-color: var(--nucomu-btn-secondary-hover);
        border-color: var(--nucomu-btn-secondary-border);
    }

    #reviews .nucomu-reviews-btn-primary {
        background-color: var(--nucomu-btn-primary-bg);
        border: 1px solid var(--nucomu-btn-primary-bg);
        color: var(--nucomu-btn-primary-text);
        font-weight: 600;
        box-shadow: 0 4px 14px -4px rgba(43, 29, 22, 0.25);
    }

    #reviews .nucomu-reviews-btn-primary:hover {
        background-color: var(--nucomu-btn-primary-hover);
        border-color: var(--nucomu-btn-primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 22px -6px rgba(43, 29, 22, 0.32);
    }

    #reviews .nucomu-reviews-btn-primary:active {
        transform: translateY(0);
    }

    #reviews .nucomu-reviews-btn-secondary:focus-visible,
    #reviews .nucomu-reviews-btn-primary:focus-visible,
    #reviews .nucomu-reviews-nav-arrow:focus-visible,
    #reviews .nucomu-reviews-dot:focus-visible,
    #reviews .nucomu-review-readmore:focus-visible {
        outline: 2px solid var(--nucomu-accent);
        outline-offset: 2px;
    }

    #reviews .nucomu-review-readmore {
        color: var(--nucomu-accent);
        background: transparent;
        border: 0;
        padding: 0;
        text-decoration: underline;
        text-underline-offset: 2px;
        border-radius: 3px;
        transition: color 180ms ease;
    }

    #reviews .nucomu-review-readmore:hover {
        color: var(--nucomu-text);
    }

    /* ======================================================================
       KONTROL NAVIGASI CAROUSEL (PANAH OUTLINE + DOTS)
       ====================================================================== */
    #reviews .nucomu-reviews-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 12px;
        padding: 0 16px;
        position: relative;
        z-index: 45;
    }

    #reviews .nucomu-reviews-nav-arrow {
        width: 36px;
        height: 36px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background-color: var(--nucomu-btn-secondary-bg);
        border: 1px solid var(--nucomu-btn-secondary-border);
        color: var(--nucomu-text);
        cursor: pointer;
        transition:
            background-color 180ms ease,
            border-color 180ms ease,
            color 180ms ease,
            transform 180ms ease;
    }

    #reviews .nucomu-reviews-nav-arrow:hover {
        background-color: var(--nucomu-btn-secondary-hover);
        border-color: var(--nucomu-text);
    }

    #reviews .nucomu-reviews-nav-arrow:active {
        transform: scale(0.95);
    }

    #reviews .nucomu-reviews-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 2px;
    }

    #reviews .nucomu-reviews-dot {
        width: 20px;
        height: 24px;
        padding: 0;
        border: 0;
        border-radius: 9999px;
        background: transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    #reviews .nucomu-reviews-dot::before {
        content: "";
        display: block;
        width: 6px;
        height: 6px;
        border-radius: 9999px;
        background-color: var(--nucomu-btn-secondary-border);
        transition: width 220ms ease, background-color 220ms ease;
    }

    #reviews .nucomu-reviews-dot:hover::before {
        background-color: var(--nucomu-text);
    }

    #reviews .nucomu-reviews-dot.is-active {
        width: 28px;
    }

    #reviews .nucomu-reviews-dot.is-active::before {
        width: 18px;
        background-color: var(--nucomu-accent);
    }

    @media (min-width: 640px) {
        #reviews .nucomu-reviews-btn-secondary,
        #reviews .nucomu-reviews-btn-primary {
            min-height: 42px;
            padding: 9px 18px;
            font-size: 13.5px;
        }
    }

    @media (min-width: 768px) {
        #reviews .nucomu-reviews-header {
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px 28px;
            margin-bottom: 32px;
        }

        #reviews .nucomu-reviews-header-right {
            align-items: flex-end;
            text-align: right;
            width: auto;
            flex-shrink: 0;
        }

        #reviews .nucomu-reviews-header-actions {
            width: auto;
            justify-content: flex-end;
            flex-wrap: nowrap;
        }
    }

    @media (min-width: 1024px) {
        #reviews .nucomu-reviews-header {
            margin-bottom: 36px;
        }

        #reviews .nucomu-reviews-header-title {
            white-space: nowrap;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        #reviews .nucomu-stage-card {
            transition: opacity 0.25s ease;
        }
        #reviews .nucomu-reviews-btn-secondary,
        #reviews .nucomu-reviews-btn-primary,
        #reviews .nucomu-reviews-nav-arrow,
        #reviews .nucomu-reviews-dot::before {
            transition: background-color 180ms ease, border-color 180ms ease;
        }
        #reviews .nucomu-reviews-btn-primary:hover,
        #reviews .nucomu-reviews-nav-arrow:active {
            transform: none;
        }
    }
</style>

<section id="reviews"
         data-anim-mode="stacked-3d"
         class="pt-6 sm:pt-8 lg:pt-10 pb-16 sm:pb-20 lg:pb-24 relative z-10 overflow-hidden border-b border-[#E5E5E5] select-none">

    <!-- =========================================================================
         SECTION HEADER (SPLIT 1 BARIS DESKTOP) & STAGE CONTAINER
         ========================================================================= -->
    <div class="w-full max-w-[1840px] mx-auto px-3 sm:px-4 lg:px-5 relative z-10">

        <!-- Section Header (Kiri: Eyebrow & Judul 1 Baris Desktop | Kanan: Aksi) -->
        <div class="nucomu-reviews-header">
            <div class="nucomu-reviews-header-left">
                <span class="nucomu-reviews-eyebrow font-body">ULASAN PELANGGAN</span>
                <h2 class="nucomu-reviews-header-title">
                    Kata Mereka Tentang <span class="nucomu-reviews-header-title-accent">Nucomu</span>
                </h2>
            </div>

            <div class="nucomu-reviews-header-right">
                <div class="nucomu-reviews-header-actions font-body">
                    <a href="{{ config('nucomu.contact.google_maps_url') }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Lihat semua ulasan Nucomu Cafe di Google Maps (buka di tab baru)"
                       class="nucomu-reviews-btn-secondary">
                        <span>Lihat semua ulasan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" aria-hidden="true">
                            <path d="M7 17L17 7"/>
                            <path d="M7 7h10v10"/>
                        </svg>
                    </a>

                    <button type="button"
                            onclick="openHomeReviewModal()"
                            aria-label="Tulis ulasan baru tentang Nucomu Cafe"
                            class="nucomu-reviews-btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" aria-hidden="true">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                            <path d="m15 5 4 4"/>
                        </svg>
                        <span>Tulis Ulasan</span>
                    </button>
                </div>
            </div>
        </div>

        @if(session('review_success'))
            <div class="max-w-3xl mx-auto mb-6 p-4 rounded-2xl bg-white border border-[#E5E5E5] text-[#1C1C1C] text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs font-body">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-[#1C1C1C] text-base" aria-hidden="true"></i>
                    <span>{{ session('review_success') }}</span>
                </div>
            </div>
        @endif

        <div id="homeReviewStageWrapper"
             role="region"
             aria-roledescription="carousel"
             aria-label="Ulasan pelanggan Nucomu Cafe"
             class="nucomu-reviews-stage relative rounded-[20px] sm:rounded-[26px] overflow-hidden select-none">

            <!-- Soft Edge Fades di tepi dalam kontainer agar kartu samping menyatu halus dengan warna #FFFFFF -->
            <div class="nucomu-reviews-fade-left pointer-events-none hidden md:block absolute inset-y-0 left-0 w-8 lg:w-10 xl:w-14 z-40" aria-hidden="true"></div>
            <div class="nucomu-reviews-fade-right pointer-events-none hidden md:block absolute inset-y-0 right-0 w-8 lg:w-10 xl:w-14 z-40" aria-hidden="true"></div>

            <!-- Viewport: Menampung Kartu Aktif Utama + Kartu Samping -->
            <div id="nucomuStageViewport"
                 class="nucomu-3d-viewport relative h-[448px] sm:h-[468px] flex items-center justify-center mx-auto w-full">
                @foreach($reviewsArray as $idx => $rev)
                    @php
                        $cardImages = !empty($rev['review_images']) && is_array($rev['review_images'])
                            ? array_slice($rev['review_images'], 0, 4)
                            : (!empty($rev['review_image']) ? [$rev['review_image']] : []);

                        // Avatar determination matching Google Reviews
                        $nameClean = trim($rev['name'] ?? 'Pengunjung');
                        $initial = strtoupper(substr($nameClean, 0, 1));
                        $avatarBg = $avatarPalette[$initial] ?? '#1C1C1C';

                        // Specific checks: Dya slma (idx 0) and Karina (idx 2) use colored letter circle, Febry has photo
                        $isLetterAvatar = in_array($idx, [0, 2]) || empty($rev['avatar_url']) || str_contains($rev['avatar_url'] ?? '', 'placeholder') || str_contains($rev['avatar_url'] ?? '', 'unsplash');
                    @endphp

                    <!-- 1 Stage Card Article (Tampilan Google Reviews Card Bersih) -->
                    <article data-stage-index="{{ $idx }}"
                             data-order-tag="{{ $rev['order_tag'] ?? 'Menu Nucomu' }}"
                             onclick="focusStageCard({{ $idx }})"
                             class="nucomu-stage-card absolute w-[82%] max-w-[285px] sm:max-w-none sm:w-[275px] md:w-[270px] lg:w-[295px] xl:w-[320px] 2xl:w-[330px] rounded-[18px] sm:rounded-[20px] p-4 sm:p-5 cursor-pointer flex flex-col justify-between min-h-[416px] sm:min-h-[436px] bg-white text-[#1C1C1C] border border-[#EEE8DE] shadow-[0_8px_28px_rgba(43,29,22,0.10)]">

                    <div>
                        <!-- 1. Reviewer Header (Avatar + Name + Subtitle) -->
                        <div class="flex items-center gap-2.5 mb-2">
                            @if($isLetterAvatar)
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full shrink-0 flex items-center justify-center text-white font-bold text-sm shadow-xs select-none"
                                     style="background-color: {{ $avatarBg }};">
                                    {{ $initial }}
                                </div>
                            @else
                                <img src="{{ $rev['avatar_url'] }}"
                                     alt="{{ $rev['name'] }}"
                                     referrerpolicy="no-referrer"
                                     loading="lazy"
                                     class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover shrink-0 border border-[#E5E5E5] bg-neutral-100 shadow-xs select-none"
                                     onerror="this.outerHTML='<div class=\'w-9 h-9 sm:w-10 sm:h-10 rounded-full shrink-0 flex items-center justify-center text-white font-bold text-sm shadow-xs select-none\' style=\'background-color: {{ $avatarBg }};\'>{{ $initial }}</div>'">
                            @endif

                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm sm:text-[15px] font-bold text-[#1C1C1C] leading-snug font-body truncate">
                                    {{ $rev['name'] }}
                                </h4>
                                <p class="text-[11px] text-[#71717A] mt-0.5 font-body truncate">
                                    {{ $rev['handle'] ?? 'Pengunjung Google Maps' }}
                                </p>
                            </div>
                        </div>

                        <!-- 2. 5 Gold Rating Stars & Relative Time Ago -->
                        <div class="flex items-center gap-2 mb-2">
                            <div class="nucomu-review-stars flex items-center gap-0.5 text-xs"
                                 aria-label="Rating {{ $rev['rating'] ?? 5 }} dari 5 bintang">
                                @for($s = 0; $s < ($rev['rating'] ?? 5); $s++)
                                    <i class="fa-solid fa-star text-[10px]" aria-hidden="true"></i>
                                @endfor
                            </div>
                            <span class="text-[11px] text-[#71717A] font-body">{{ $rev['time_ago'] ?? 'Baru-baru ini' }}</span>
                        </div>

                        <!-- 3. Review Comment Text + Link Baca Selengkapnya (muncul otomatis jika teks terpotong) -->
                        <div class="mb-2.5 min-h-[68px] flex flex-col items-start">
                            <p data-review-comment="{{ $idx }}"
                               class="nucomu-review-comment text-[12.5px] sm:text-[13px] text-[#333333] leading-snug line-clamp-3 font-body whitespace-pre-line">{{ trim($rev['content']) }}</p>
                            <button type="button"
                                    data-readmore-for="{{ $idx }}"
                                    onclick="event.stopPropagation(); openSingleReviewModal({{ $idx }}, this)"
                                    aria-label="Baca selengkapnya ulasan dari {{ $rev['name'] }}"
                                    class="nucomu-review-readmore hidden mt-1 text-[11.5px] font-semibold font-body cursor-pointer">
                                Baca selengkapnya
                            </button>
                        </div>

                        <!-- 4. Attached Photo Grid (2x2 Grid for 4 photos, 1x2 for 2 photos) -->
                        @if(count($cardImages) >= 4)
                            <div class="grid grid-cols-2 grid-rows-2 gap-[3px] h-[136px] sm:h-[142px] rounded-lg sm:rounded-xl overflow-hidden bg-neutral-100 border border-[#EBEBEB] mb-2.5">
                                @foreach(array_slice($cardImages, 0, 4) as $imgIdx => $imgUrl)
                                    <div class="relative group cursor-pointer overflow-hidden w-full h-full bg-neutral-200"
                                         onclick="event.stopPropagation(); openReviewImagePreview('{{ $imgUrl }}', '{{ addslashes($rev['name']) }}')"
                                         title="Klik untuk memperbesar">
                                        <img src="{{ $imgUrl }}"
                                             alt="Foto ulasan {{ $rev['name'] }}"
                                             referrerpolicy="no-referrer"
                                             loading="lazy"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        {{-- Video duration badges on Dya slma (idx 0) first two videos --}}
                                        @if($idx === 0 && in_array($imgIdx, [0, 1]))
                                            <span class="absolute bottom-1.5 right-1.5 bg-black/65 text-white text-[9.5px] px-1.5 py-0.5 rounded font-mono font-medium backdrop-blur-xs flex items-center gap-1 shadow-xs pointer-events-none">
                                                0:02
                                            </span>
                                        @endif
                                        <span class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                                            <i class="fa-solid fa-expand"></i>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(count($cardImages) === 2)
                            <div class="grid grid-cols-2 gap-[3px] h-[136px] sm:h-[142px] rounded-lg sm:rounded-xl overflow-hidden bg-neutral-100 border border-[#EBEBEB] mb-2.5">
                                @foreach($cardImages as $imgUrl)
                                    <div class="relative group cursor-pointer overflow-hidden w-full h-full bg-neutral-200"
                                         onclick="event.stopPropagation(); openReviewImagePreview('{{ $imgUrl }}', '{{ addslashes($rev['name']) }}')"
                                         title="Klik untuk memperbesar">
                                        <img src="{{ $imgUrl }}"
                                             alt="Foto ulasan {{ $rev['name'] }}"
                                             referrerpolicy="no-referrer"
                                             loading="lazy"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <span class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs">
                                            <i class="fa-solid fa-expand"></i>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(count($cardImages) === 3)
                            <div class="grid grid-cols-2 gap-[3px] h-[136px] sm:h-[142px] rounded-lg sm:rounded-xl overflow-hidden bg-neutral-100 border border-[#EBEBEB] mb-2.5">
                                <div class="relative group cursor-pointer overflow-hidden w-full h-full bg-neutral-200"
                                     onclick="event.stopPropagation(); openReviewImagePreview('{{ $cardImages[0] }}', '{{ addslashes($rev['name']) }}')">
                                    <img src="{{ $cardImages[0] }}"
                                         alt="Foto ulasan {{ $rev['name'] }}"
                                         referrerpolicy="no-referrer"
                                         loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="grid grid-rows-2 gap-[3px] w-full h-full">
                                    @foreach(array_slice($cardImages, 1, 2) as $imgUrl)
                                        <div class="relative group cursor-pointer overflow-hidden w-full h-full bg-neutral-200"
                                             onclick="event.stopPropagation(); openReviewImagePreview('{{ $imgUrl }}', '{{ addslashes($rev['name']) }}')">
                                            <img src="{{ $imgUrl }}"
                                                 alt="Foto ulasan {{ $rev['name'] }}"
                                                 referrerpolicy="no-referrer"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif(count($cardImages) === 1)
                            <div class="h-[136px] sm:h-[142px] rounded-lg sm:rounded-xl overflow-hidden bg-neutral-100 border border-[#EBEBEB] mb-2.5 relative group cursor-pointer"
                                 onclick="event.stopPropagation(); openReviewImagePreview('{{ $cardImages[0] }}', '{{ addslashes($rev['name']) }}')">
                                <img src="{{ $cardImages[0] }}"
                                     alt="Foto ulasan {{ $rev['name'] }}"
                                     referrerpolicy="no-referrer"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-2 mb-2.5"></div>
                        @endif
                    </div>

                    <!-- 5. Card Footer: Menu Favorit Pill -->
                    <div class="pt-2.5 border-t border-[#F3F4F6] flex items-center gap-1.5 mt-auto">
                        <span class="text-[11px] text-[#71717A] font-body shrink-0">Menu Favorit:</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F5F5F5] text-[#1C1C1C] border border-[#E5E5E5] truncate font-body">
                            <i class="fa-solid fa-mug-saucer text-[9px] text-[#1C1C1C]" aria-hidden="true"></i>
                            <span class="truncate">{{ $rev['order_tag'] ?? 'Menu Nucomu' }}</span>
                        </span>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Kontrol Navigasi Carousel (Panah Kiri/Kanan Outline + Dot Indicators) -->
            @if(count($reviewsArray) > 1)
                <div class="nucomu-reviews-nav font-body" role="group" aria-label="Navigasi carousel ulasan">
                    <button type="button"
                            id="reviewsPrevBtn"
                            onclick="prevHomeReview()"
                            aria-label="Ulasan sebelumnya"
                            class="nucomu-reviews-nav-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </button>

                    <div class="nucomu-reviews-dots" role="group" aria-label="Pilih ulasan">
                        @foreach($reviewsArray as $dotIdx => $dotRev)
                            <button type="button"
                                    class="nucomu-reviews-dot {{ $dotIdx === 0 ? 'is-active' : '' }}"
                                    data-dot-index="{{ $dotIdx }}"
                                    onclick="focusStageCard({{ $dotIdx }})"
                                    aria-label="Tampilkan ulasan {{ $dotIdx + 1 }} dari {{ count($reviewsArray) }} oleh {{ $dotRev['name'] ?? 'Pengunjung Nucomu' }}"
                                    aria-current="{{ $dotIdx === 0 ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>

                    <button type="button"
                            id="reviewsNextBtn"
                            onclick="nextHomeReview()"
                            aria-label="Ulasan berikutnya"
                            class="nucomu-reviews-nav-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                </div>
            @endif

        </div>

    </div>

</section>

<!-- MODAL DETAIL 1 ULASAN LENGKAP BESERTA FOTONYA -->
<div id="singleReviewModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-[#1C1C1C]/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeSingleReviewModal()">
    <div id="singleReviewModalContent"
         class="bg-[#FAFAFA] w-full max-w-lg rounded-[28px] border border-[#E5E5E5] shadow-2xl relative transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col overflow-hidden font-body"
         onclick="event.stopPropagation()">

        <!-- Modal Header: Reviewer Info -->
        <div class="p-5 sm:p-6 border-b border-[#E5E5E5] flex items-center justify-between gap-3 bg-white">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="relative shrink-0">
                    <img id="singleReviewAvatar"
                         src=""
                         alt="Avatar Pengunjung"
                         referrerpolicy="no-referrer"
                         class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-[#1C1C1C]/15 bg-neutral-100 shadow-xs">
                    <div id="singleReviewAvatarFallback"
                         class="hidden w-12 h-12 sm:w-14 sm:h-14 rounded-full items-center justify-center text-white font-bold text-lg shadow-xs select-none"></div>
                </div>
                <div class="min-w-0">
                    <h3 id="singleReviewName" class="text-base sm:text-lg font-bold text-[#1C1C1C] truncate"></h3>
                    <span id="singleReviewHandle" class="text-xs text-[#666666] block truncate"></span>
                </div>
            </div>

            <button type="button"
                    onclick="closeSingleReviewModal()"
                    aria-label="Tutup ulasan lengkap"
                    class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] hover:bg-[#1C1C1C] hover:text-white flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs shrink-0 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Modal Body: Full Review & Photos for 1 Review Only -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-1 space-y-4">
            <!-- Rating Stars & Time Ago -->
            <div class="flex items-center justify-between gap-2">
                <div class="inline-flex items-center gap-1.5 bg-[#1C1C1C] text-white px-3 py-1 rounded-full text-xs">
                    <div id="singleReviewStars" class="flex items-center gap-0.5 text-[11px]" style="color: #E0A030;"></div>
                    <span id="singleReviewRatingNum" class="font-bold text-[11px] ml-0.5">5.0</span>
                </div>
                <span id="singleReviewTimeAgo" class="text-xs text-[#8A8A8A] font-medium"></span>
            </div>

            <!-- Full Review Content -->
            <div class="bg-white border border-[#E5E5E5] rounded-2xl p-4 sm:p-5 shadow-2xs">
                <p id="singleReviewText" class="text-sm sm:text-[15px] text-[#222222] leading-relaxed whitespace-pre-line"></p>
            </div>

            <!-- Review Photos Container -->
            <div id="singleReviewPhotosWrap" class="hidden space-y-2">
                <div class="flex items-center justify-between text-xs text-[#666666]">
                    <span class="font-semibold text-[#1C1C1C] flex items-center gap-1.5">
                        <i class="fa-solid fa-camera"></i>
                        <span>Foto Ulasan Pengunjung</span>
                    </span>
                    <span class="text-[11px] text-[#8A8A8A]">Klik foto untuk memperbesar</span>
                </div>
                <div id="singleReviewPhotosGrid" class="grid gap-2"></div>
            </div>
        </div>

        <!-- Modal Footer: Menu Order Tag & Close Button -->
        <div class="px-5 sm:px-6 py-4 bg-white border-t border-[#E5E5E5] flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                <span class="text-xs text-[#8A8A8A] shrink-0">Menu Favorit:</span>
                <span id="singleReviewOrderTag" class="text-xs font-semibold text-[#1C1C1C] bg-[#F5F5F5] border border-[#E5E5E5] px-3 py-1 rounded-full truncate"></span>
            </div>

            <button type="button"
                    onclick="closeSingleReviewModal()"
                    class="px-4 py-2 rounded-full bg-[#1C1C1C] hover:bg-black text-white text-xs font-bold transition-all shrink-0 cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL VIEW ALL ULASAN -->
<div id="allReviewsModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-[#1C1C1C]/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeAllReviewsModal()">
    <div id="allReviewsModalContent"
         class="bg-[#FAFAFA] w-full max-w-5xl rounded-[28px] border border-[#E5E5E5] shadow-2xl relative transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col overflow-hidden font-body"
         onclick="event.stopPropagation()">

        <!-- Modal Header -->
        <div class="p-5 sm:p-6 border-b border-[#E5E5E5] flex flex-wrap items-center justify-between gap-4 bg-white shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] text-lg shrink-0">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-xl font-bold text-[#1C1C1C]">Semua Ulasan Pengunjung Nucomu Cafe</h3>
                    <div class="flex items-center gap-2 text-xs text-[#666666] mt-0.5">
                        <span class="font-bold text-[#1C1C1C]">★ 4.9 / 5.0</span>
                        <span>•</span>
                        <span>Menampilkan {{ $allReviewsCollection->count() }} Ulasan Pilihan &amp; Terbaru</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button"
                        onclick="closeAllReviewsModal(); openHomeReviewModal();"
                        class="text-xs font-bold text-white bg-[#1C1C1C] hover:bg-black px-4 py-2 rounded-full inline-flex items-center gap-1.5 shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-plus-circle text-white"></i>
                    <span>Tulis Ulasan</span>
                </button>
                <button type="button"
                        onclick="closeAllReviewsModal()"
                        aria-label="Tutup semua ulasan"
                        class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] hover:bg-[#1C1C1C] hover:text-white flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <!-- Filter Bar inside Modal -->
        <div class="px-5 sm:px-6 py-3 bg-[#F9F9F9] border-b border-[#E5E5E5] flex flex-wrap items-center justify-between gap-3 shrink-0">
            <span class="text-xs font-semibold text-[#666666]">Filter Ulasan:</span>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button"
                        id="filterReviewsAllBtn"
                        onclick="filterModalReviews('all')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#1C1C1C] text-white transition-all shadow-2xs cursor-pointer">
                    Semua ({{ $allReviewsCollection->count() }})
                </button>
                <button type="button"
                        id="filterReviewsPhotoBtn"
                        onclick="filterModalReviews('with-photo')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-[#F5F5F5] text-[#555555] border border-[#E5E5E5] transition-all cursor-pointer">
                    <i class="fa-solid fa-camera mr-1 text-[#1C1C1C]"></i> Dengan Foto
                </button>
            </div>
        </div>

        <!-- Modal Body (Grid of Reviews) -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="allReviewsModalGrid">
                @foreach($allReviewsCollection as $mIdx => $mRev)
                    @php
                        $mImages = !empty($mRev['review_images']) && is_array($mRev['review_images'])
                            ? $mRev['review_images']
                            : (!empty($mRev['review_image']) ? [$mRev['review_image']] : []);
                        $hasPhotos = count($mImages) > 0;
                        $mInitial = strtoupper(substr(trim($mRev['name'] ?? 'P'), 0, 1));
                        $mAvatarBg = $avatarPalette[$mInitial] ?? '#1C1C1C';
                    @endphp
                    <div class="modal-review-item bg-white border border-[#E5E5E5] rounded-2xl p-4 sm:p-5 flex flex-col justify-between hover:border-[#1C1C1C]/40 transition-colors shadow-2xs"
                         data-has-photo="{{ $hasPhotos ? '1' : '0' }}">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="flex items-center gap-2.5">
                                    @if(!empty($mRev['avatar_url']) && !in_array($mIdx, [0, 2]))
                                        <img src="{{ $mRev['avatar_url'] }}"
                                             alt="{{ $mRev['name'] }}"
                                             referrerpolicy="no-referrer"
                                             loading="lazy"
                                             class="w-10 h-10 rounded-full object-cover border border-[#E5E5E5] bg-neutral-100">
                                    @else
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-xs"
                                             style="background-color: {{ $mAvatarBg }};">
                                            {{ $mInitial }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="text-xs sm:text-sm font-bold text-[#1C1C1C]">{{ $mRev['name'] }}</h5>
                                        <span class="text-[11px] text-[#666666] block">{{ $mRev['handle'] ?? 'Pengunjung Nucomu' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5 text-xs text-[#1C1C1C]">
                                    @for($i = 0; $i < ($mRev['rating'] ?? 5); $i++)
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs sm:text-sm text-[#333333] leading-relaxed whitespace-pre-line mb-3">
                                "{{ $mRev['content'] }}"
                            </p>
                            @if($hasPhotos)
                                <div class="flex gap-2 overflow-x-auto pb-1 mb-2">
                                    @foreach($mImages as $img)
                                        <img src="{{ $img }}"
                                             alt="Foto ulasan {{ $mRev['name'] }}"
                                             referrerpolicy="no-referrer"
                                             loading="lazy"
                                             onclick="openReviewImagePreview('{{ $img }}', '{{ addslashes($mRev['name']) }}')"
                                             class="w-16 h-16 rounded-xl object-cover border border-[#E5E5E5] cursor-pointer hover:opacity-90 transition-opacity shrink-0">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="pt-2.5 border-t border-[#F0F0F0] flex items-center justify-between text-[11px] text-[#8A8A8A] mt-2">
                            <span><i class="fa-solid fa-mug-saucer mr-1 text-[#1C1C1C]"></i>{{ $mRev['order_tag'] ?? 'Menu Nucomu' }}</span>
                            <span>{{ $mRev['time_ago'] ?? 'Baru-baru ini' }}</span>
                        </div>
                    </div>
                @endforeach
                <!-- Empty State Message when Filter has no results -->
                <div id="allReviewsEmptyState" class="hidden col-span-full py-12 text-center font-body text-sm text-[#888888]">
                    <i class="fa-regular fa-image text-2xl text-[#CCCCCC] block mb-2"></i>
                    Belum ada ulasan dengan foto.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FORM TULIS ULASAN BARU -->
<div id="homeReviewModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-[#1C1C1C]/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeHomeReviewModal()">
    <div id="homeModalContent"
         class="bg-[#FAFAFA] w-full max-w-lg rounded-[28px] border border-[#E5E5E5] shadow-2xl relative transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col overflow-hidden font-body"
         onclick="event.stopPropagation()">

        <div class="p-5 sm:p-6 border-b border-[#E5E5E5] flex items-center justify-between bg-white">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-[#1C1C1C]">Bagikan Pengalaman Anda</h3>
                <p class="text-xs text-[#666666]">Ulasan Anda sangat berarti bagi pengembangan Nucomu Cafe</p>
            </div>
            <button type="button"
                    onclick="closeHomeReviewModal()"
                    aria-label="Tutup formulir ulasan"
                    class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] hover:bg-[#1C1C1C] hover:text-white flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs cursor-pointer">
                <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
            </button>
        </div>

        <form action="{{ route('nucomu.review.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-5 sm:p-6 overflow-y-auto space-y-4 flex-1">
            @csrf

            <!-- Rating Stars Selection -->
            <div>
                <label class="block text-xs font-semibold text-[#1C1C1C] mb-1.5">Beri Bintang Kepuasan</label>
                <div class="flex items-center gap-2" id="homeStarRating">
                    @for($s = 1; $s <= 5; $s++)
                        <button type="button"
                                onclick="setHomeRating({{ $s }})"
                                class="text-2xl text-[#1C1C1C] hover:scale-125 transition-transform cursor-pointer">
                            <i class="fa-solid fa-star"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="homeRatingInput" value="5">
            </div>

            <!-- Name -->
            <div>
                <label for="homeReviewerName" class="block text-xs font-semibold text-[#1C1C1C] mb-1">Nama Lengkap *</label>
                <input type="text"
                       name="name"
                       id="homeReviewerName"
                       required
                       placeholder="Contoh: Budi Santoso"
                       class="w-full bg-white border border-[#E5E5E5] focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] rounded-xl px-3.5 py-2.5 text-xs text-[#1C1C1C] outline-hidden">
            </div>

            <!-- Handle / Role -->
            <div>
                <label for="homeReviewerHandle" class="block text-xs font-semibold text-[#1C1C1C] mb-1">Status / Profesi (Opsional)</label>
                <input type="text"
                       name="handle"
                       id="homeReviewerHandle"
                       placeholder="Contoh: Pecinta Kopi Manual Brew · Tulungagung"
                       class="w-full bg-white border border-[#E5E5E5] focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] rounded-xl px-3.5 py-2.5 text-xs text-[#1C1C1C] outline-hidden">
            </div>

            <!-- Order Tag -->
            <div>
                <label for="homeReviewOrderTag" class="block text-xs font-semibold text-[#1C1C1C] mb-1">Menu yang Dipesan</label>
                <input type="text"
                       name="order_tag"
                       id="homeReviewOrderTag"
                       placeholder="Contoh: Kopi Susu Nucomu + Basque Cheesecake"
                       class="w-full bg-white border border-[#E5E5E5] focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] rounded-xl px-3.5 py-2.5 text-xs text-[#1C1C1C] outline-hidden">
            </div>

            <!-- Comment -->
            <div>
                <label for="homeReviewComment" class="block text-xs font-semibold text-[#1C1C1C] mb-1">Cerita / Komentar Anda *</label>
                <textarea name="comment"
                          id="homeReviewComment"
                          rows="4"
                          required
                          placeholder="Ceritakan rasa kopi, kenyamanan suasana, atau keramahan barista kami..."
                          class="w-full bg-white border border-[#E5E5E5] focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] rounded-xl px-3.5 py-2.5 text-xs text-[#1C1C1C] outline-hidden"></textarea>
            </div>

            <!-- Optional Image Upload -->
            <div>
                <label class="block text-xs font-semibold text-[#1C1C1C] mb-1">Foto Momen di Nucomu (Opsional, Maks 2MB)</label>
                <div class="relative border-2 border-dashed border-[#DDDDDD] hover:border-[#1C1C1C] rounded-2xl p-4 text-center cursor-pointer transition-colors bg-white">
                    <input type="file"
                           name="review_image"
                           accept="image/*"
                           onchange="previewHomeReviewUpload(this)"
                           class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    <div class="space-y-1">
                        <i class="fa-solid fa-cloud-arrow-up text-xl text-[#8A8A8A]"></i>
                        <p class="text-xs text-[#555555] font-medium" id="homeReviewImageLabel">Pilih foto dari perangkat Anda</p>
                    </div>
                </div>
                <div id="homeReviewUploadPreviewWrap" class="mt-2 hidden">
                    <img id="homeReviewUploadPreviewImg" src="" alt="Pratinjau Foto" class="h-20 rounded-xl object-cover border border-[#E5E5E5]">
                </div>
            </div>

            <div class="pt-3 border-t border-[#E5E5E5] flex items-center justify-end gap-2">
                <button type="button"
                        onclick="closeHomeReviewModal()"
                        class="px-4 py-2 rounded-full border border-[#E5E5E5] text-xs font-semibold text-[#666666] hover:bg-[#F5F5F5] transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-[#1C1C1C] hover:bg-black text-white text-xs font-bold transition-all shadow-xs cursor-pointer">
                    Kirim Ulasan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- LIGHTBOX FOTO ULASAN -->
<div id="reviewImageLightbox"
     class="fixed inset-0 z-[70] bg-black/90 backdrop-blur-md flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeReviewImagePreview()">
    <div class="relative max-w-3xl w-full flex flex-col items-center" onclick="event.stopPropagation()">
        <button type="button"
                onclick="closeReviewImagePreview()"
                aria-label="Tutup pratinjau foto ulasan"
                class="absolute -top-12 right-0 w-9 h-9 rounded-full bg-white/20 hover:bg-white text-white hover:text-black flex items-center justify-center transition-colors cursor-pointer">
            <i class="fa-solid fa-xmark text-base" aria-hidden="true"></i>
        </button>
        <img id="reviewLightboxImg" src="" alt="Pratinjau Foto Ulasan" class="max-h-[80vh] w-auto rounded-2xl object-contain border border-white/20 shadow-2xl">
        <p id="reviewLightboxCaption" class="text-xs text-white/80 mt-3 font-body text-center"></p>
    </div>
</div>

<!-- =========================================================================
     JAVASCRIPT: 3D STAGE CAROUSEL CONTROLLER
     Animasi panggung 3D: 3 kartu depan + kartu tumpuk samping kiri/kanan
     ========================================================================= -->
<script>
    const homeReviews = @json($reviewsArray);
    const homeReviewAvatarPalette = @json($avatarPalette);
    let homeReviewIndex = 0;
    const HOME_REVIEW_INTERVAL = 60000; // 1 menit per pergeseran ulasan otomatis
    let homeReviewTimer = null;
    let isHomeReviewPaused = false;
    let lastSingleReviewTrigger = null;

    // Deteksi ulasan yang terpotong line-clamp-3 dan tampilkan link "Baca selengkapnya"
    function updateReviewReadMoreButtons() {
        const comments = document.querySelectorAll('#nucomuStageViewport [data-review-comment]');
        comments.forEach((pEl) => {
            const idx = pEl.getAttribute('data-review-comment');
            const btnEl = document.querySelector(`#nucomuStageViewport [data-readmore-for="${idx}"]`);
            if (!btnEl) return;

            const isTruncated = pEl.scrollHeight > pEl.clientHeight + 2;
            btnEl.classList.toggle('hidden', !isTruncated);
        });
    }

    // Komposisi panggung: 1 kartu aktif di tengah + kartu samping (opacity 0.55, scale 0.94)
    function updateStageCardsPositions() {
        const cards = document.querySelectorAll('#nucomuStageViewport .nucomu-stage-card');
        const total = cards.length;
        if (total === 0) return;

        cards.forEach((card, idx) => {
            card.classList.remove(
                'is-front-center',
                'is-front-left',
                'is-front-right',
                'is-stack-left',
                'is-stack-right',
                'is-hidden-left',
                'is-hidden-right'
            );

            let diff = idx - homeReviewIndex;
            if (diff > total / 2) diff -= total;
            if (diff < -total / 2) diff += total;

            if (diff === 0) {
                // Kartu Aktif Utama Tengah
                card.classList.add('is-front-center');
            } else if (diff === -1) {
                // Kartu Samping Kiri
                card.classList.add('is-front-left');
            } else if (diff === 1) {
                // Kartu Samping Kanan
                card.classList.add('is-front-right');
            } else if (diff === -2) {
                // Ditumpuk 1 di Samping Kiri (Peek)
                card.classList.add('is-stack-left');
            } else if (diff === 2) {
                // Ditumpuk 1 di Samping Kanan (Peek)
                card.classList.add('is-stack-right');
            } else if (diff < -2) {
                card.classList.add('is-hidden-left');
            } else {
                card.classList.add('is-hidden-right');
            }
        });

        const dots = document.querySelectorAll('#homeReviewStageWrapper .nucomu-reviews-dot');
        dots.forEach((dot) => {
            const dotIdx = parseInt(dot.getAttribute('data-dot-index') || '-1', 10);
            const isActive = dotIdx === homeReviewIndex;
            dot.classList.toggle('is-active', isActive);
            dot.setAttribute('aria-current', isActive ? 'true' : 'false');
        });
    }

    function focusStageCard(index) {
        if (!homeReviews || homeReviews.length === 0) return;
        homeReviewIndex = ((index % homeReviews.length) + homeReviews.length) % homeReviews.length;
        updateStageCardsPositions();
        startHomeReviewLoop();
    }

    function nextHomeReview() {
        if (!homeReviews || homeReviews.length <= 1) return;
        homeReviewIndex = (homeReviewIndex + 1) % homeReviews.length;
        updateStageCardsPositions();
        startHomeReviewLoop();
    }

    function prevHomeReview() {
        if (!homeReviews || homeReviews.length <= 1) return;
        homeReviewIndex = (homeReviewIndex - 1 + homeReviews.length) % homeReviews.length;
        updateStageCardsPositions();
        startHomeReviewLoop();
    }

    function startHomeReviewLoop() {
        if (homeReviewTimer) {
            clearInterval(homeReviewTimer);
        }
        homeReviewTimer = setInterval(() => {
            if (!isHomeReviewPaused && homeReviews && homeReviews.length > 1) {
                homeReviewIndex = (homeReviewIndex + 1) % homeReviews.length;
                updateStageCardsPositions();
            }
        }, HOME_REVIEW_INTERVAL);
    }

    function pauseHomeReview() { isHomeReviewPaused = true; }
    function resumeHomeReview() { isHomeReviewPaused = false; }

    // Autoplay berhenti saat hover / fokus & dukungan panah keyboard
    const reviewStageEl = document.getElementById('homeReviewStageWrapper');
    if (reviewStageEl) {
        reviewStageEl.addEventListener('mouseenter', pauseHomeReview);
        reviewStageEl.addEventListener('mouseleave', resumeHomeReview);
        reviewStageEl.addEventListener('focusin', pauseHomeReview);
        reviewStageEl.addEventListener('focusout', (e) => {
            if (!e.relatedTarget || !reviewStageEl.contains(e.relatedTarget)) {
                resumeHomeReview();
            }
        });
        reviewStageEl.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                prevHomeReview();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                nextHomeReview();
            }
        });
    }

    // Touch Swipe Navigation for Mobile
    let reviewTouchStartX = 0;
    let reviewTouchEndX = 0;
    const reviewViewportEl = document.getElementById('nucomuStageViewport');
    if (reviewViewportEl) {
        reviewViewportEl.addEventListener('touchstart', e => {
            reviewTouchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        reviewViewportEl.addEventListener('touchend', e => {
            reviewTouchEndX = e.changedTouches[0].screenX;
            const diff = reviewTouchEndX - reviewTouchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) {
                    nextHomeReview();
                } else {
                    prevHomeReview();
                }
            }
        }, { passive: true });
    }

    // Lightbox & Modals Controller
    function openReviewImagePreview(imgUrl, reviewerName) {
        const modal = document.getElementById('reviewImageLightbox');
        const img = document.getElementById('reviewLightboxImg');
        const caption = document.getElementById('reviewLightboxCaption');
        if (modal && img) {
            img.src = imgUrl;
            if (caption) caption.innerText = reviewerName ? `Foto ulasan dari ${reviewerName}` : '';
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }
    }

    function closeReviewImagePreview() {
        const modal = document.getElementById('reviewImageLightbox');
        if (modal) {
            modal.classList.add('opacity-0', 'pointer-events-none');
        }
    }

    function openSingleReviewModal(index, triggerEl = null) {
        if (!homeReviews || homeReviews.length === 0) return;
        const safeIdx = ((index % homeReviews.length) + homeReviews.length) % homeReviews.length;
        const rev = homeReviews[safeIdx];
        if (!rev) return;

        lastSingleReviewTrigger = triggerEl || document.activeElement;

        const modal = document.getElementById('singleReviewModal');
        const content = document.getElementById('singleReviewModalContent');
        const avatarEl = document.getElementById('singleReviewAvatar');
        const avatarFallbackEl = document.getElementById('singleReviewAvatarFallback');
        const nameEl = document.getElementById('singleReviewName');
        const handleEl = document.getElementById('singleReviewHandle');
        const starsEl = document.getElementById('singleReviewStars');
        const ratingNumEl = document.getElementById('singleReviewRatingNum');
        const timeAgoEl = document.getElementById('singleReviewTimeAgo');
        const textEl = document.getElementById('singleReviewText');
        const orderTagEl = document.getElementById('singleReviewOrderTag');
        const photosWrapEl = document.getElementById('singleReviewPhotosWrap');
        const photosGridEl = document.getElementById('singleReviewPhotosGrid');

        const reviewerName = (rev.name || 'Pengunjung Nucomu').trim();
        const initialChar = reviewerName.charAt(0).toUpperCase() || 'P';
        const avatarBg = homeReviewAvatarPalette[initialChar] || '#1C1C1C';
        const rawAvatarUrl = rev.avatar_url || '';
        const useLetterAvatar = [0, 2].includes(safeIdx) || !rawAvatarUrl || rawAvatarUrl.includes('placeholder') || rawAvatarUrl.includes('unsplash');

        if (avatarEl && avatarFallbackEl) {
            avatarFallbackEl.innerText = initialChar;
            avatarFallbackEl.style.backgroundColor = avatarBg;

            if (useLetterAvatar) {
                avatarEl.classList.add('hidden');
                avatarEl.removeAttribute('src');
                avatarFallbackEl.classList.remove('hidden');
                avatarFallbackEl.classList.add('flex');
            } else {
                avatarFallbackEl.classList.add('hidden');
                avatarFallbackEl.classList.remove('flex');
                avatarEl.classList.remove('hidden');
                avatarEl.onerror = function() {
                    avatarEl.classList.add('hidden');
                    avatarFallbackEl.classList.remove('hidden');
                    avatarFallbackEl.classList.add('flex');
                };
                avatarEl.src = rawAvatarUrl;
                avatarEl.alt = reviewerName;
            }
        }
        if (nameEl) nameEl.innerText = reviewerName;
        if (handleEl) handleEl.innerText = rev.handle || 'Pengunjung Google Maps';
        if (timeAgoEl) timeAgoEl.innerText = rev.time_ago || 'Baru-baru ini';
        if (textEl) textEl.innerText = `"${(rev.content || '').trim()}"`;
        if (orderTagEl) {
            orderTagEl.innerHTML = `<i class="fa-solid fa-mug-saucer mr-1 text-[10px] text-[#1C1C1C]" aria-hidden="true"></i> ${rev.order_tag || 'Menu Nucomu'}`;
        }

        const starsCount = parseInt(rev.rating || 5, 10) || 5;
        if (ratingNumEl) ratingNumEl.innerText = `${starsCount}.0`;
        if (starsEl) {
            starsEl.innerHTML = '';
            for (let i = 0; i < starsCount; i++) {
                starsEl.innerHTML += '<i class="fa-solid fa-star" aria-hidden="true"></i>';
            }
        }

        const rawPhotos = Array.isArray(rev.review_images) && rev.review_images.length > 0
            ? rev.review_images.slice(0, 4)
            : (rev.review_image ? [rev.review_image] : []);

        if (photosWrapEl && photosGridEl) {
            photosGridEl.innerHTML = '';
            if (rawPhotos.length > 0) {
                photosWrapEl.classList.remove('hidden');
                photosGridEl.className = rawPhotos.length === 1 ? 'grid grid-cols-1 gap-2' : 'grid grid-cols-2 gap-2';
                rawPhotos.forEach((imgUrl) => {
                    const item = document.createElement('div');
                    item.className = 'relative group cursor-pointer aspect-[4/3] rounded-2xl overflow-hidden border border-[#E5E5E5] bg-neutral-100';
                    item.onclick = () => openReviewImagePreview(imgUrl, rev.name);
                    item.innerHTML = `<img src="${imgUrl}" alt="Foto ulasan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                      <span class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1.5"><i class="fa-solid fa-expand"></i><span>Perbesar</span></span>`;
                    photosGridEl.appendChild(item);
                });
            } else {
                photosWrapEl.classList.add('hidden');
            }
        }

        if (modal && content) {
            pauseHomeReview();
            document.body.style.overflow = 'hidden';
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }

    function closeSingleReviewModal() {
        const modal = document.getElementById('singleReviewModal');
        const content = document.getElementById('singleReviewModalContent');
        if (modal && content) {
            const wasOpen = !modal.classList.contains('pointer-events-none');
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            if (wasOpen) {
                const allModal = document.getElementById('allReviewsModal');
                if (!allModal || allModal.classList.contains('pointer-events-none')) {
                    document.body.style.overflow = '';
                    resumeHomeReview();
                }
                if (lastSingleReviewTrigger && typeof lastSingleReviewTrigger.focus === 'function') {
                    lastSingleReviewTrigger.focus();
                }
                lastSingleReviewTrigger = null;
            }
        }
    }

    function openAllReviewsModal() {
        const modal = document.getElementById('allReviewsModal');
        const content = document.getElementById('allReviewsModalContent');
        if (modal && content) {
            pauseHomeReview();
            document.body.style.overflow = 'hidden';
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }

    function closeAllReviewsModal() {
        const modal = document.getElementById('allReviewsModal');
        const content = document.getElementById('allReviewsModalContent');
        if (modal && content) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            document.body.style.overflow = '';
            resumeHomeReview();
        }
    }

    function filterModalReviews(mode) {
        const items = document.querySelectorAll('#allReviewsModal .modal-review-item');
        const allBtn = document.getElementById('filterReviewsAllBtn');
        const photoBtn = document.getElementById('filterReviewsPhotoBtn');
        const emptyState = document.getElementById('allReviewsEmptyState');
        let visibleCount = 0;

        items.forEach(item => {
            if (mode === 'with-photo') {
                if (item.getAttribute('data-has-photo') === '1') {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            } else {
                item.classList.remove('hidden');
                visibleCount++;
            }
        });

        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        if (allBtn && photoBtn) {
            if (mode === 'with-photo') {
                photoBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#1C1C1C] text-white transition-all shadow-2xs cursor-pointer';
                allBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-[#F5F5F5] text-[#555555] border border-[#E5E5E5] transition-all cursor-pointer';
            } else {
                allBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#1C1C1C] text-white transition-all shadow-2xs cursor-pointer';
                photoBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-[#F5F5F5] text-[#555555] border border-[#E5E5E5] transition-all cursor-pointer';
            }
        }
    }

    function openHomeReviewModal() {
        const modal = document.getElementById('homeReviewModal');
        const modalContent = document.getElementById('homeModalContent');
        if (modal && modalContent) {
            pauseHomeReview();
            document.body.style.overflow = 'hidden';
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
            setHomeRating(5);
        }
    }

    function closeHomeReviewModal() {
        const modal = document.getElementById('homeReviewModal');
        const modalContent = document.getElementById('homeModalContent');
        if (modal && modalContent) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            const allModal = document.getElementById('allReviewsModal');
            if (!allModal || allModal.classList.contains('pointer-events-none')) {
                document.body.style.overflow = '';
                resumeHomeReview();
            }
        }
    }

    let homeCurrentRating = 5;
    function setHomeRating(rating) {
        homeCurrentRating = rating;
        const ratingInput = document.getElementById('homeRatingInput');
        if (ratingInput) ratingInput.value = rating;
        const stars = document.getElementById('homeStarRating').children;
        for (let i = 0; i < stars.length; i++) {
            if (i < rating) {
                stars[i].classList.add('text-[#1C1C1C]');
                stars[i].classList.remove('text-stone-300');
            } else {
                stars[i].classList.remove('text-[#1C1C1C]');
                stars[i].classList.add('text-stone-300');
            }
        }
    }

    function previewHomeReviewUpload(input) {
        const label = document.getElementById('homeReviewImageLabel');
        const wrap = document.getElementById('homeReviewUploadPreviewWrap');
        const img = document.getElementById('homeReviewUploadPreviewImg');
        if (input.files && input.files[0]) {
            if (label) label.innerText = input.files[0].name;
            const reader = new FileReader();
            reader.onload = function(e) {
                if (img && wrap) {
                    img.src = e.target.result;
                    wrap.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReviewImagePreview();
            closeSingleReviewModal();
            closeAllReviewsModal();
            closeHomeReviewModal();
            document.body.style.overflow = '';
            resumeHomeReview();
        }
    });

    // Inisialisasi Panggung Ulasan & Deteksi Teks Terpotong
    let reviewReadMoreResizeTimer = null;
    function initNucomuHomeReviews() {
        updateStageCardsPositions();
        updateReviewReadMoreButtons();
        startHomeReviewLoop();

        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(updateReviewReadMoreButtons);
        }
    }

    window.addEventListener('resize', () => {
        if (reviewReadMoreResizeTimer) clearTimeout(reviewReadMoreResizeTimer);
        reviewReadMoreResizeTimer = setTimeout(updateReviewReadMoreButtons, 120);
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNucomuHomeReviews);
    } else {
        initNucomuHomeReviews();
    }
</script>
