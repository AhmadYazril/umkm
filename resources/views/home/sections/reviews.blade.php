<!-- SECTION 6: UNIFIED REVIEWS SECTION (MIXED MONOCHROME INTERACTIVE ANIMATIONS) -->
@php
    $allReviewsCollection = collect($dynamicReviews ?? []);
    if ($allReviewsCollection->isEmpty()) {
        $allReviewsCollection = collect(config('nucomu.reviews', []))->map(function (array $review) {
            $rawImages = $review['review_images'] ?? (! empty($review['review_image']) ? [$review['review_image']] : []);
            $resolvedImages = collect($rawImages)->filter()->map(function (string $img) {
                return (! str_starts_with($img, 'http://') && ! str_starts_with($img, 'https://')) ? asset(ltrim($img, '/')) : $img;
            })->values()->all();

            return [
                'name' => $review['name'] ?? 'Pengunjung Nucomu',
                'handle' => $review['handle'] ?? 'Local Guide · Ulasan',
                'avatar_url' => $review['avatar'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
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
@endphp
<style>
    /* ======================================================================
       NUCOMU MIXED MONOCHROME REVIEW ANIMATIONS
       1. Side-Scrolling Infinite Marquee (Hover Pause & Card Elevation)
       2. 3D Stacked Cards (Perspective Floating Transition)
       3. Fade-Slide Multi-Card Carousel (Smooth Cross-Fade & Slide)
       ====================================================================== */
    #reviews .nucomu-review-stage-bg {
        background:
            radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.10) 0%, transparent 62%),
            radial-gradient(circle at 85% 90%, rgba(255, 255, 255, 0.06) 0%, transparent 50%),
            linear-gradient(145deg, #141414 0%, #1E1E1E 50%, #111111 100%);
    }

    #reviews .nucomu-3d-viewport {
        perspective: 1400px;
        transform-style: preserve-3d;
    }

    #reviews .nucomu-stage-card {
        transition:
            transform 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            opacity 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            filter 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            box-shadow 0.45s ease,
            border-color 0.45s ease,
            background 0.45s ease;
        will-change: transform, opacity, filter;
        backface-visibility: hidden;
    }

    /* Active Center Card Glow (Monochrome Silver/Obsidian Mix) */
    #reviews .nucomu-stage-card.is-center {
        z-index: 30;
        opacity: 1;
        filter: blur(0px);
        transform: translate3d(0%, -6px, 40px) rotateY(0deg) scale(1.04);
        box-shadow:
            0 26px 60px -15px rgba(0, 0, 0, 0.65),
            0 0 0 1px rgba(255, 255, 255, 0.35),
            0 0 35px -8px rgba(255, 255, 255, 0.16);
    }

    #reviews .nucomu-stage-card.is-center:hover {
        transform: translate3d(0, -8px, 45px) rotateY(0deg) scale(1.05) !important;
        box-shadow:
            0 32px 70px -12px rgba(0, 0, 0, 0.78),
            0 0 0 1.5px rgba(255, 255, 255, 0.55),
            0 0 42px -6px rgba(255, 255, 255, 0.24);
    }

    /* 3D Stacked Cards (Floating Depth Stack - Auto Move 1 by 1 Every 5s) */
    #reviews .nucomu-stage-card.is-prev {
        z-index: 22;
        opacity: 0.78;
        filter: blur(0.4px);
        transform: translate3d(-64%, 6px, -70px) rotateY(12deg) scale(0.89);
    }
    #reviews .nucomu-stage-card.is-next {
        z-index: 22;
        opacity: 0.78;
        filter: blur(0.4px);
        transform: translate3d(64%, 6px, -70px) rotateY(-12deg) scale(0.89);
    }
    #reviews .nucomu-stage-card.is-prev:hover,
    #reviews .nucomu-stage-card.is-next:hover {
        opacity: 0.92;
        filter: blur(0px);
    }
    #reviews .nucomu-stage-card.is-stack-left {
        z-index: 12;
        opacity: 0.38;
        filter: blur(1.2px);
        transform: translate3d(-112%, 18px, -150px) rotateY(18deg) scale(0.79);
    }
    #reviews .nucomu-stage-card.is-stack-right {
        z-index: 12;
        opacity: 0.38;
        filter: blur(1.2px);
        transform: translate3d(112%, 18px, -150px) rotateY(-18deg) scale(0.79);
    }
    #reviews .nucomu-stage-card.is-hidden-left,
    #reviews .nucomu-stage-card.is-hidden-right {
        z-index: 1;
        opacity: 0;
        pointer-events: none;
        transform: translate3d(0%, 28px, -240px) scale(0.7);
    }

    @media (max-width: 1023px) {
        #reviews .nucomu-stage-card.is-prev {
            transform: translate3d(-24%, 10px, -60px) rotateY(8deg) scale(0.90);
            opacity: 0.45;
        }
        #reviews .nucomu-stage-card.is-next {
            transform: translate3d(24%, 10px, -60px) rotateY(-8deg) scale(0.90);
            opacity: 0.45;
        }
        #reviews .nucomu-stage-card.is-stack-left,
        #reviews .nucomu-stage-card.is-stack-right {
            opacity: 0;
            pointer-events: none;
        }
    }

    /* Infinite Side-Scroll Marquee (Animasi Ulasan Menyamping - Video 00:05-00:11) */
    @keyframes nucomuMarqueeScrollLeft {
        0% { transform: translate3d(0, 0, 0); }
        100% { transform: translate3d(-50%, 0, 0); }
    }

    #reviews .nucomu-marquee-track {
        display: flex;
        width: max-content;
        animation: nucomuMarqueeScrollLeft 42s linear infinite;
        will-change: transform;
    }

    #reviews .nucomu-marquee-wrapper:hover .nucomu-marquee-track,
    #reviews .nucomu-marquee-track.is-paused {
        animation-play-state: paused;
    }

    #reviews .nucomu-marquee-card {
        transition:
            transform 0.38s cubic-bezier(0.16, 1, 0.3, 1),
            box-shadow 0.38s ease,
            border-color 0.38s ease;
    }

    #reviews .nucomu-marquee-card:hover {
        transform: translateY(-6px) scale(1.02);
        z-index: 20;
    }

    @media (prefers-reduced-motion: reduce) {
        #reviews .nucomu-marquee-track {
            animation: none;
        }
        #reviews .nucomu-stage-card {
            transition: opacity 0.25s ease;
        }
    }
</style>

<section id="reviews"
         data-anim-mode="stacked-3d"
         class="py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 relative z-10 bg-[#FAF8F5] text-[#1C1C1C] overflow-hidden border-b border-[#E5E5E5]">

    <div class="max-w-6xl mx-auto relative z-10">

        <!-- Section Header (Monochrome Editorial + 5-Star Showcase Badge) -->
        <div class="text-center mb-10 space-y-3">
            <div class="flex items-center justify-center gap-3">
                <span class="text-xs sm:text-[13px] font-bold text-[#666666] uppercase tracking-[0.22em] font-body">
                    05 / ULASAN PENGUNJUNG
                </span>
                <div class="w-12 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
            </div>

            <!-- 5-Star Row Above Title (Matching Video Header) -->
            <div class="flex items-center justify-center gap-1.5 text-lg sm:text-xl text-[#1C1C1C] pt-1" aria-hidden="true">
                <i class="fa-solid fa-star drop-shadow-2xs"></i>
                <i class="fa-solid fa-star drop-shadow-2xs"></i>
                <i class="fa-solid fa-star drop-shadow-2xs"></i>
                <i class="fa-solid fa-star drop-shadow-2xs"></i>
                <i class="fa-solid fa-star drop-shadow-2xs"></i>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-[#1C1C1C] tracking-tight font-editorial leading-tight">
                Kata Mereka Tentang Nucomu
            </h2>

            <p class="text-[#666666] text-xs sm:text-sm max-w-xl mx-auto font-body leading-relaxed font-normal">
                Pengalaman langsung dari para pengunjung yang menikmati kopi, dessert artisanal, dan suasana hangat di Nucomu Cafe Tulungagung.
            </p>

            <!-- Rating Summary & Maps Link (Mixed Monochrome Pills) -->
            <div class="pt-2 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 font-body">
                <!-- Google Rating Capsule -->
                <div class="inline-flex items-center gap-2.5 bg-white border border-[#E5E5E5] px-4 py-2 rounded-full shadow-2xs">
                    <span class="w-6 h-6 rounded-full bg-[#1C1C1C] flex items-center justify-center text-white text-[10px]">
                        <i class="fa-brands fa-google"></i>
                    </span>
                    <div class="flex items-center gap-1.5 text-xs">
                        <span class="font-bold text-[#1C1C1C]">4.9 / 5.0</span>
                        <span class="text-[#1C1C1C] flex text-[10px] tracking-tight">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </span>
                        <span class="text-[#8A8A8A]">• 74+ Ulasan</span>
                    </div>
                </div>

                <button type="button"
                        onclick="openAllReviewsModal()"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1C1C1C] bg-white hover:bg-[#F5F5F5] border border-[#E5E5E5] px-4 py-2 rounded-full transition-colors shadow-2xs">
                    <i class="fa-solid fa-comments text-[#1C1C1C]"></i>
                    <span>View All ({{ $allReviewsCollection->count() }} Ulasan)</span>
                </button>

                <a href="{{ config('nucomu.contact.google_maps_url') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1C1C1C] hover:text-black bg-white hover:bg-[#F5F5F5] border border-[#E5E5E5] px-4 py-2 rounded-full transition-colors shadow-2xs">
                    <i class="fa-solid fa-location-dot text-[#1C1C1C]"></i>
                    <span>Lihat di Google Maps</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-[#8A8A8A]"></i>
                </a>

                <button type="button"
                        onclick="openHomeReviewModal()"
                        class="text-xs font-bold text-white bg-[#1C1C1C] hover:bg-black active:scale-95 transition-all inline-flex items-center gap-1.5 px-5 py-2 rounded-full shadow-xs">
                    <i class="fa-solid fa-plus-circle text-white"></i>
                    <span>Tulis Ulasan Anda</span>
                </button>
            </div>
        </div>

        @if(session('review_success'))
            <div class="max-w-3xl mx-auto mb-6 p-4 rounded-2xl bg-white border border-[#E5E5E5] text-[#1C1C1C] text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs font-body">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-[#1C1C1C] text-base"></i>
                    <span>{{ session('review_success') }}</span>
                </div>
            </div>
        @endif

        <!-- ==============================================================
             INTERACTIVE MIXED-MONOCHROME SHOWCASE STAGE
             (3D Stacked Cards Auto-Move 1 by 1 Every 5s + Side-Scroll Marquee)
             ============================================================== -->
        <div id="homeReviewContainer"
             class="nucomu-review-stage-bg rounded-[2rem] sm:rounded-[2.5rem] p-5 sm:p-8 lg:p-10 border border-[#2C2C2C] shadow-2xl relative overflow-hidden select-none">

            <!-- 1. TOP STAGE: 3D STACKED CARDS CAROUSEL -->
            <div class="relative py-2 sm:py-4">
                <!-- Left Navigation Floating Button -->
                <button type="button"
                        onclick="prevHomeReview()"
                        aria-label="Ulasan Sebelumnya"
                        class="absolute left-0 sm:left-2 top-1/2 -translate-y-1/2 z-40 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/15 hover:bg-white text-white hover:text-[#141414] border border-white/30 backdrop-blur-md flex items-center justify-center transition-all duration-300 shadow-lg active:scale-95">
                    <i class="fa-solid fa-chevron-left text-xs sm:text-sm"></i>
                </button>

                <!-- Right Navigation Floating Button -->
                <button type="button"
                        onclick="nextHomeReview()"
                        aria-label="Ulasan Selanjutnya"
                        class="absolute right-0 sm:right-2 top-1/2 -translate-y-1/2 z-40 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/15 hover:bg-white text-white hover:text-[#141414] border border-white/30 backdrop-blur-md flex items-center justify-center transition-all duration-300 shadow-lg active:scale-95">
                    <i class="fa-solid fa-chevron-right text-xs sm:text-sm"></i>
                </button>

                <!-- 3D / Multi-Card Stage Viewport -->
                <div id="nucomuStageViewport" class="nucomu-3d-viewport relative h-[325px] sm:h-[310px] flex items-center justify-center mx-auto max-w-5xl">
                    @foreach($reviewsArray as $idx => $rev)
                        @php
                            $stageImages = !empty($rev['review_images']) && is_array($rev['review_images'])
                                ? array_slice($rev['review_images'], 0, 4)
                                : (!empty($rev['review_image']) ? [$rev['review_image']] : []);
                            $firstPhoto = $stageImages[0] ?? null;
                            // Alternate Mixed Monochrome Card Themes:
                            // Even cards: Obsidian Glass (#222222 / #1A1A1A with crisp white & silver accents)
                            // Odd cards: Warm Alabaster/Ivory (#FAF8F5 / #FFFFFF with charcoal ink accents)
                            $isLightCard = $idx % 2 === 1;
                        @endphp
                        <article data-stage-index="{{ $idx }}"
                                 onclick="focusStageCard({{ $idx }})"
                                 class="nucomu-stage-card absolute w-[88%] sm:w-[360px] md:w-[390px] rounded-3xl p-5 sm:p-6 cursor-pointer flex flex-col justify-between min-h-[285px] sm:min-h-[280px] {{ $isLightCard ? 'bg-gradient-to-br from-[#FFFFFF] via-[#FAF8F5] to-[#EAE7E1] text-[#181818] border border-white/90' : 'bg-gradient-to-br from-[#262626] via-[#1C1C1C] to-[#121212] text-white border border-white/25' }}">

                            <div>
                                <!-- Card Header: Avatar + 5 Stars on Top (Exact Video Layout) -->
                                <div class="flex items-center justify-between gap-3 mb-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="relative shrink-0">
                                            <img src="{{ $rev['avatar_url'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80' }}"
                                                 alt="{{ $rev['name'] }}"
                                                 referrerpolicy="no-referrer"
                                                 class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover {{ $isLightCard ? 'border-2 border-[#1C1C1C]/20 bg-neutral-200' : 'border-2 border-white/35 bg-neutral-800' }} shadow-md">
                                            <span class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full {{ $isLightCard ? 'bg-[#181818] text-white' : 'bg-white text-[#181818]' }} flex items-center justify-center text-[9px] shadow-xs">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                        </div>

                                        <div>
                                            <h4 class="text-sm sm:text-base font-bold leading-snug font-body {{ $isLightCard ? 'text-[#141414]' : 'text-white' }}">
                                                {{ $rev['name'] }}
                                            </h4>
                                            <span class="text-[11px] font-medium block font-body {{ $isLightCard ? 'text-[#666666]' : 'text-neutral-400' }}">
                                                {{ $rev['handle'] ?? 'Pengunjung Nucomu' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- 5 Stars Pill -->
                                    <div class="flex items-center gap-0.5 text-xs sm:text-sm px-2.5 py-1 rounded-full {{ $isLightCard ? 'bg-[#181818] text-white' : 'bg-white/12 text-white border border-white/15' }}">
                                        @for($i = 0; $i < ($rev['rating'] ?? 5); $i++)
                                            <i class="fa-solid fa-star text-[11px]"></i>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Review Comment + Optional Thumbnail -->
                                <div class="flex items-start gap-3">
                                    <p class="text-xs sm:text-[13.5px] leading-relaxed line-clamp-3 font-body {{ $isLightCard ? 'text-[#333333]' : 'text-neutral-200' }} flex-1">
                                        "{{ $rev['content'] }}"
                                    </p>
                                    @if($firstPhoto)
                                        <div onclick="event.stopPropagation(); openReviewImagePreview('{{ $firstPhoto }}', '{{ addslashes($rev['name']) }}')"
                                             title="Perbesar foto ulasan"
                                             class="w-16 h-16 rounded-2xl overflow-hidden shrink-0 border {{ $isLightCard ? 'border-[#1C1C1C]/15' : 'border-white/20' }} relative group">
                                            <img src="{{ $firstPhoto }}"
                                                 alt="Foto {{ $rev['name'] }}"
                                                 referrerpolicy="no-referrer"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            <span class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px]">
                                                <i class="fa-solid fa-expand"></i>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer: Like/Helpful, Order Tag & Action Circle (Matching Video Icons) -->
                            <div class="pt-3.5 mt-3 border-t {{ $isLightCard ? 'border-[#1C1C1C]/10' : 'border-white/12' }} flex items-center justify-between gap-2 font-body">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="event.stopPropagation(); toggleReviewLike(this)"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-all {{ $isLightCard ? 'bg-[#181818]/8 hover:bg-[#181818] text-[#181818] hover:text-white' : 'bg-white/10 hover:bg-white text-neutral-200 hover:text-[#141414]' }}"
                                            title="Tandai ulasan membantu">
                                        <i class="fa-regular fa-thumbs-up text-[11px]"></i>
                                        <span class="like-count">{{ 12 + ($idx * 3) % 19 }}</span>
                                    </button>

                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium truncate max-w-[155px] {{ $isLightCard ? 'bg-[#181818]/6 text-[#333333]' : 'bg-white/10 text-neutral-300' }}">
                                        <i class="fa-solid fa-mug-saucer text-[10px]"></i>
                                        <span class="truncate">{{ $rev['order_tag'] ?? 'Menu Nucomu' }}</span>
                                    </span>
                                </div>

                                <button type="button"
                                        onclick="event.stopPropagation(); openSingleReviewModal({{ $idx }})"
                                        title="Lihat ulasan lengkap & foto"
                                        aria-label="Lihat ulasan lengkap {{ $rev['name'] }}"
                                        class="w-7 h-7 rounded-full flex items-center justify-center transition-all {{ $isLightCard ? 'bg-[#181818] text-white hover:scale-110' : 'bg-white text-[#141414] hover:scale-110' }}">
                                    <i class="fa-solid fa-ellipsis text-[11px]"></i>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <!-- Pagination Dots -->
            <div class="mt-3 mb-7 flex items-center justify-center relative z-30">
                <div id="nucomuStageDots" class="flex items-center justify-center gap-1.5">
                    @foreach($reviewsArray as $idx => $rev)
                        <button type="button"
                                onclick="focusStageCard({{ $idx }})"
                                data-dot-index="{{ $idx }}"
                                aria-label="Pilih ulasan {{ $idx + 1 }}"
                                class="h-2 rounded-full transition-all duration-300 {{ $idx === 0 ? 'w-7 bg-white' : 'w-2 bg-white/30 hover:bg-white/60' }}">
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Hidden/Synced Legacy Hooks for Compatibility -->
            <div class="sr-only" aria-hidden="true">
                <img id="homeReviewAvatar" src="" alt="">
                <h4 id="homeReviewName"></h4>
                <span id="homeReviewHandle"></span>
                <span id="homeReviewTimeAgo"></span>
                <div id="homeReviewStars"></div>
                <div id="homeReviewTextCol"><p id="homeReviewText"></p></div>
                <div id="homeReviewImageCol"><div id="homeReviewImagesWrap"></div></div>
                <span id="homeReviewOrderTag"></span>
            </div>

            <!-- ==============================================================
                 2. BOTTOM STAGE: ANIMASI ULASAN MENYAMPING (AUTO-SCROLL MARQUEE)
                 (Matches Video 00:05-00:11: Continuous Horizontal Scroll + Hover Elevate)
                 ============================================================== -->
            <div class="pt-5 border-t border-white/12 relative z-20">
                <div class="flex items-center justify-between mb-3 px-1 font-body">
                    <span class="text-[11px] uppercase tracking-[0.18em] text-neutral-400 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-arrows-left-right text-white/70"></i>
                        Live Stream Ulasan Pengunjung (Arahkan Kursor untuk Jeda)
                    </span>
                    <span class="text-[11px] text-neutral-400 hidden sm:inline">
                        {{ count($reviewsArray) }} Ulasan Terverifikasi
                    </span>
                </div>

                <div class="nucomu-marquee-wrapper relative overflow-hidden py-2 -mx-2 px-2">
                    <!-- Left & Right Edge Monochrome Fade Masks -->
                    <div class="pointer-events-none absolute inset-y-0 left-0 w-10 sm:w-16 bg-gradient-to-r from-[#161616] to-transparent z-10"></div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 w-10 sm:w-16 bg-gradient-to-l from-[#141414] to-transparent z-10"></div>

                    <div id="nucomuMarqueeTrack" class="nucomu-marquee-track gap-4">
                        @for($loopPass = 0; $loopPass < 2; $loopPass++)
                            @foreach($reviewsArray as $mIdx => $mRev)
                                @php
                                    // Mixed Monochrome Composition in Marquee: alternate dark charcoal & crisp ivory-white cards
                                    $isIvoryTile = ($mIdx % 2 === 0);
                                @endphp
                                <div onclick="focusStageCard({{ $mIdx }})"
                                     class="nucomu-marquee-card w-[270px] sm:w-[300px] shrink-0 rounded-2xl p-4 cursor-pointer flex flex-col justify-between {{ $isIvoryTile ? 'bg-[#FAF8F5] text-[#181818] border border-white shadow-md hover:border-white' : 'bg-[#222222]/95 text-white border border-white/15 shadow-md hover:border-white/50' }}">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <img src="{{ $mRev['avatar_url'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80' }}"
                                                     alt="{{ $mRev['name'] }}"
                                                     referrerpolicy="no-referrer"
                                                     loading="lazy"
                                                     class="w-9 h-9 rounded-full object-cover shrink-0 {{ $isIvoryTile ? 'border border-[#181818]/20' : 'border border-white/25' }}">
                                                <div class="min-w-0">
                                                    <h5 class="text-xs font-bold truncate font-body {{ $isIvoryTile ? 'text-[#141414]' : 'text-white' }}">
                                                        {{ $mRev['name'] }}
                                                    </h5>
                                                    <span class="text-[10px] truncate block font-body {{ $isIvoryTile ? 'text-[#666666]' : 'text-neutral-400' }}">
                                                        {{ $mRev['handle'] ?? 'Pengunjung Nucomu' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-0.5 text-[10px] shrink-0 {{ $isIvoryTile ? 'text-[#181818]' : 'text-white' }}">
                                                @for($i = 0; $i < ($mRev['rating'] ?? 5); $i++)
                                                    <i class="fa-solid fa-star"></i>
                                                @endfor
                                            </div>
                                        </div>

                                        <p class="text-[11.5px] leading-relaxed line-clamp-2 font-body {{ $isIvoryTile ? 'text-[#444444]' : 'text-neutral-300' }}">
                                            "{{ $mRev['content'] }}"
                                        </p>
                                    </div>

                                    <div class="pt-2.5 mt-2.5 border-t {{ $isIvoryTile ? 'border-[#181818]/10' : 'border-white/10' }} flex items-center justify-between text-[10.5px] font-body">
                                        <span class="truncate max-w-[175px] font-medium {{ $isIvoryTile ? 'text-[#333333]' : 'text-neutral-300' }}">
                                            <i class="fa-solid fa-mug-saucer mr-1 text-[9px]"></i>{{ $mRev['order_tag'] ?? 'Menu Nucomu' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 {{ $isIvoryTile ? 'text-[#181818]' : 'text-white/80' }} font-semibold">
                                            <i class="fa-regular fa-thumbs-up text-[10px]"></i> {{ 8 + ($mIdx * 5) % 17 }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODAL DETAIL 1 ULASAN LENGKAP BESERTA FOTONYA (DARI TOMBOL TITIK 3 KARTU 3D) -->
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
                    <span class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full bg-[#1C1C1C] text-white flex items-center justify-center text-[9px] shadow-xs">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </div>
                <div class="min-w-0">
                    <h3 id="singleReviewName" class="text-base sm:text-lg font-bold text-[#1C1C1C] truncate"></h3>
                    <span id="singleReviewHandle" class="text-xs text-[#666666] block truncate"></span>
                </div>
            </div>

            <button type="button"
                    onclick="closeSingleReviewModal()"
                    aria-label="Tutup ulasan lengkap"
                    class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] hover:bg-[#1C1C1C] hover:text-white flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs shrink-0">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Body: Full Review & Photos for 1 Review Only -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-1 space-y-4">
            <!-- Rating Stars & Time Ago -->
            <div class="flex items-center justify-between gap-2">
                <div class="inline-flex items-center gap-1.5 bg-[#1C1C1C] text-white px-3 py-1 rounded-full text-xs">
                    <div id="singleReviewStars" class="flex items-center gap-0.5 text-[11px]"></div>
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
                    class="px-4 py-2 rounded-full bg-[#1C1C1C] hover:bg-black text-white text-xs font-bold transition-all shrink-0">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL VIEW ALL ULASAN (SEMUA ULASAN PENGUNJUNG - MONOCHROME) -->
<div id="allReviewsModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-[#1C1C1C]/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeAllReviewsModal()">
    <div id="allReviewsModalContent"
         class="bg-[#FAFAFA] w-full max-w-5xl rounded-[28px] border border-[#E5E5E5] shadow-2xl relative transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col overflow-hidden font-body"
         onclick="event.stopPropagation()">

        <!-- Modal Header -->
        <div class="p-5 sm:p-6 border-b border-[#E5E5E5] flex flex-wrap items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] text-lg">
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
                        class="text-xs font-bold text-white bg-[#1C1C1C] hover:bg-black px-4 py-2 rounded-full inline-flex items-center gap-1.5 shadow-xs transition-all">
                    <i class="fa-solid fa-plus-circle text-white"></i>
                    <span>Tulis Ulasan</span>
                </button>
                <button type="button"
                        onclick="closeAllReviewsModal()"
                        class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] hover:bg-[#1C1C1C] hover:text-white flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Filter Bar inside Modal -->
        <div class="px-5 sm:px-6 py-3 bg-[#F9F9F9] border-b border-[#E5E5E5] flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <button type="button"
                        id="filterReviewsAllBtn"
                        onclick="filterModalReviews('all')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#1C1C1C] text-white transition-all shadow-2xs">
                    Semua Ulasan ({{ $allReviewsCollection->count() }})
                </button>
                <button type="button"
                        id="filterReviewsPhotoBtn"
                        onclick="filterModalReviews('with-photo')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-[#F5F5F5] text-[#555555] border border-[#E5E5E5] transition-all">
                    <i class="fa-solid fa-camera text-[#1C1C1C] mr-1"></i> Dengan Foto
                </button>
            </div>

            <a href="{{ config('nucomu.contact.google_maps_url') }}"
               target="_blank"
               rel="noopener noreferrer"
               class="text-xs text-[#1C1C1C] hover:text-black font-semibold inline-flex items-center gap-1.5">
                <i class="fa-brands fa-google"></i>
                <span>Buka Halaman Google Maps Nucomu Cafe</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>

        <!-- Scrollable All Reviews List -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($allReviewsCollection as $rev)
                    @php
                        $modalCardImages = !empty($rev['review_images']) && is_array($rev['review_images'])
                            ? array_slice($rev['review_images'], 0, 4)
                            : (!empty($rev['review_image']) ? [$rev['review_image']] : []);
                        $hasPhotos = count($modalCardImages) > 0;
                    @endphp
                    <div class="modal-review-item bg-white border border-[#E5E5E5] rounded-2xl p-5 flex flex-col justify-between shadow-2xs"
                         data-has-photo="{{ $hasPhotos ? '1' : '0' }}">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $rev['avatar_url'] ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80' }}"
                                         alt="{{ $rev['name'] }}"
                                         referrerpolicy="no-referrer"
                                         class="w-10 h-10 rounded-full object-cover border border-[#E5E5E5] bg-neutral-100 shrink-0">
                                    <div>
                                        <h5 class="text-sm font-bold text-[#1C1C1C] leading-snug">{{ $rev['name'] }}</h5>
                                        <span class="text-[11px] text-[#8A8A8A] block">{{ $rev['handle'] ?? 'Pengunjung Nucomu' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-2.5">
                                <div class="flex items-center gap-0.5 text-[#1C1C1C] text-xs">
                                    @for($i = 0; $i < ($rev['rating'] ?? 5); $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-[11px] text-[#8A8A8A]">{{ $rev['time_ago'] ?? 'Baru-baru ini' }}</span>
                            </div>

                            <p class="text-xs sm:text-sm text-[#555555] leading-relaxed mb-3.5 whitespace-pre-line">{{ $rev['content'] }}</p>

                            @if(count($modalCardImages) > 1)
                                <div class="grid grid-cols-2 gap-1.5 mb-3.5 rounded-xl overflow-hidden border border-[#E5E5E5]">
                                    @foreach($modalCardImages as $imgUrl)
                                        <div class="relative group cursor-pointer aspect-[4/3] bg-neutral-100 overflow-hidden"
                                             onclick="openReviewImagePreview('{{ $imgUrl }}', '{{ addslashes($rev['name']) }}')">
                                            <img src="{{ $imgUrl }}"
                                                 alt="Foto ulasan {{ $rev['name'] }}"
                                                 referrerpolicy="no-referrer"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(count($modalCardImages) === 1)
                                <div class="mb-3.5 rounded-xl overflow-hidden border border-[#E5E5E5] relative group cursor-pointer h-40 bg-neutral-100"
                                     onclick="openReviewImagePreview('{{ $modalCardImages[0] }}', '{{ addslashes($rev['name']) }}')">
                                    <img src="{{ $modalCardImages[0] }}"
                                         alt="Foto ulasan {{ $rev['name'] }}"
                                         referrerpolicy="no-referrer"
                                         loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-[#E5E5E5] flex items-center justify-between text-[11px]">
                            <span class="text-[#8A8A8A]">Menu Favorit:</span>
                            <span class="text-[#1C1C1C] font-medium bg-[#F5F5F5] border border-[#E5E5E5] px-2.5 py-0.5 rounded-full">
                                <i class="fa-solid fa-mug-saucer mr-1 text-[10px] text-[#1C1C1C]"></i> {{ $rev['order_tag'] ?? 'Menu Nucomu' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- MODAL LIGHTBOX FOTO ULASAN -->
<div id="reviewImageLightbox"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#1C1C1C]/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeReviewImagePreview()">
    <div class="relative max-w-2xl w-full" onclick="event.stopPropagation()">
        <button type="button"
                onclick="closeReviewImagePreview()"
                class="absolute -top-10 right-0 w-8 h-8 rounded-full bg-white/20 hover:bg-white text-white hover:text-[#1C1C1C] flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
        <img id="reviewLightboxImg" src="" alt="Foto Ulasan Pengunjung" referrerpolicy="no-referrer" class="w-full max-h-[80vh] object-contain rounded-2xl border border-white/20 shadow-2xl bg-black">
        <p id="reviewLightboxCaption" class="text-center text-xs text-white/90 mt-2.5 font-medium font-body"></p>
    </div>
</div>

<!-- MODAL FORM TAMBAH ULASAN -->
<div id="homeReviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#1C1C1C]/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeHomeReviewModal()">
    <div class="bg-[#FAFAFA] w-full max-w-md rounded-[28px] p-6 sm:p-8 border border-[#E5E5E5] shadow-2xl relative transform scale-95 transition-transform duration-300 max-h-[92vh] overflow-y-auto font-body" id="homeModalContent" onclick="event.stopPropagation()">
        <button type="button" onclick="closeHomeReviewModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-white border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] hover:bg-[#1C1C1C] hover:text-white transition-colors shadow-2xs">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C]">
                <i class="fa-solid fa-pen-to-square text-base"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#1C1C1C]">Tulis Ulasan Pengalaman Anda</h3>
                <p class="text-[11px] text-[#666666]">Bagikan kesan &amp; foto momen Anda di Nucomu Cafe</p>
            </div>
        </div>

        <form action="{{ route('nucomu.review.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Contoh: Nabila Putri" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Status / Info Singkat</label>
                    <input type="text" name="handle" placeholder="Contoh: Local Guide / Mahasiswa" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Menu yang Dipesan</label>
                    <input type="text" name="order_tag" placeholder="Contoh: Cheesecake & Nucomu Coffee" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Rating Bintang</label>
                <div class="flex gap-2 text-lg text-stone-300" id="homeStarRating">
                    <i class="fa-solid fa-star cursor-pointer hover:text-[#1C1C1C] transition-colors text-[#1C1C1C]" onclick="setHomeRating(1)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-[#1C1C1C] transition-colors text-[#1C1C1C]" onclick="setHomeRating(2)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-[#1C1C1C] transition-colors text-[#1C1C1C]" onclick="setHomeRating(3)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-[#1C1C1C] transition-colors text-[#1C1C1C]" onclick="setHomeRating(4)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-[#1C1C1C] transition-colors text-[#1C1C1C]" onclick="setHomeRating(5)"></i>
                </div>
                <input type="hidden" name="rating" id="homeRatingInput" value="5">
            </div>

            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Ulasan Anda *</label>
                <textarea name="content" rows="3" required placeholder="Ceritakan pengalaman nongkrong, rasa cheesecake/kopi, atau kenyamanan tempat di Nucomu Cafe..." class="w-full p-3 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors resize-none"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Upload Foto Makanan / Suasana (Opsional)</label>
                <label class="flex items-center justify-center gap-2 w-full px-3.5 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F5] border border-dashed border-[#CCCCCC] hover:border-[#1C1C1C] text-xs text-[#666666] cursor-pointer transition-colors">
                    <i class="fa-solid fa-camera text-[#1C1C1C]"></i>
                    <span id="homeReviewImageLabel">Pilih foto dari perangkat Anda...</span>
                    <input type="file" name="review_image" accept="image/*" class="hidden" onchange="previewHomeReviewUpload(this)">
                </label>
                <div id="homeReviewUploadPreviewWrap" class="hidden mt-2 relative rounded-xl overflow-hidden border border-[#E5E5E5] h-32 bg-neutral-100">
                    <img id="homeReviewUploadPreviewImg" src="" alt="Preview Foto" class="w-full h-full object-cover">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-full bg-[#1C1C1C] hover:bg-black text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md active:scale-95">
                Kirim Ulasan Sekarang
            </button>
        </form>
    </div>
</div>

<script>
    const homeReviews = @json($reviewsArray);
    let homeReviewIndex = 0;
    const HOME_REVIEW_INTERVAL = 5000;
    let homeReviewTimer = null;
    let isHomeReviewPaused = false;
    let currentNucomuAnimMode = 'stacked-3d';

    function updateStageCardsPositions() {
        const cards = document.querySelectorAll('#nucomuStageViewport .nucomu-stage-card');
        const dots = document.querySelectorAll('#nucomuStageDots [data-dot-index]');
        const total = cards.length;
        if (total === 0) return;

        cards.forEach((card, idx) => {
            card.classList.remove(
                'is-center',
                'is-prev',
                'is-next',
                'is-stack-left',
                'is-stack-right',
                'is-hidden-left',
                'is-hidden-right'
            );

            let diff = idx - homeReviewIndex;
            if (diff > total / 2) diff -= total;
            if (diff < -total / 2) diff += total;

            if (diff === 0) {
                card.classList.add('is-center');
            } else if (diff === -1 || (total === 2 && diff === 1)) {
                card.classList.add('is-prev');
            } else if (diff === 1) {
                card.classList.add('is-next');
            } else if (diff === -2) {
                card.classList.add('is-stack-left');
            } else if (diff === 2) {
                card.classList.add('is-stack-right');
            } else if (diff < -2) {
                card.classList.add('is-hidden-left');
            } else {
                card.classList.add('is-hidden-right');
            }
        });

        dots.forEach((dot, idx) => {
            if (idx === homeReviewIndex) {
                dot.className = 'h-2 rounded-full transition-all duration-300 w-7 bg-white';
            } else {
                dot.className = 'h-2 rounded-full transition-all duration-300 w-2 bg-white/30 hover:bg-white/60';
            }
        });
    }

    function focusStageCard(index) {
        if (!homeReviews || homeReviews.length === 0) return;
        homeReviewIndex = ((index % homeReviews.length) + homeReviews.length) % homeReviews.length;
        updateHomeReviewUI();
        startHomeReviewLoop();
    }

    function openSingleReviewModal(index) {
        if (!homeReviews || homeReviews.length === 0) return;
        const safeIdx = ((index % homeReviews.length) + homeReviews.length) % homeReviews.length;
        const rev = homeReviews[safeIdx];
        if (!rev) return;

        const modal = document.getElementById('singleReviewModal');
        const content = document.getElementById('singleReviewModalContent');
        const avatarEl = document.getElementById('singleReviewAvatar');
        const nameEl = document.getElementById('singleReviewName');
        const handleEl = document.getElementById('singleReviewHandle');
        const starsEl = document.getElementById('singleReviewStars');
        const ratingNumEl = document.getElementById('singleReviewRatingNum');
        const timeAgoEl = document.getElementById('singleReviewTimeAgo');
        const textEl = document.getElementById('singleReviewText');
        const orderTagEl = document.getElementById('singleReviewOrderTag');
        const photosWrapEl = document.getElementById('singleReviewPhotosWrap');
        const photosGridEl = document.getElementById('singleReviewPhotosGrid');

        if (avatarEl) {
            avatarEl.src = rev.avatar_url || rev.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80';
            avatarEl.alt = rev.name || 'Pengunjung Nucomu';
        }
        if (nameEl) nameEl.innerText = rev.name || 'Pengunjung Nucomu';
        if (handleEl) handleEl.innerText = rev.handle || 'Pengunjung Nucomu';
        if (timeAgoEl) timeAgoEl.innerText = rev.time_ago || 'Baru-baru ini';
        if (textEl) textEl.innerText = `"${rev.content || rev.text || ''}"`;
        if (orderTagEl) {
            orderTagEl.innerHTML = `<i class="fa-solid fa-mug-saucer mr-1 text-[10px] text-[#1C1C1C]"></i> ${rev.order_tag || rev.order || 'Menu Nucomu'}`;
        }

        const starsCount = parseInt(rev.rating || rev.stars || 5, 10) || 5;
        if (ratingNumEl) ratingNumEl.innerText = `${starsCount}.0`;
        if (starsEl) {
            starsEl.innerHTML = '';
            for (let i = 0; i < starsCount; i++) {
                starsEl.innerHTML += '<i class="fa-solid fa-star"></i>';
            }
        }

        const rawPhotos = Array.isArray(rev.review_images) && rev.review_images.length > 0
            ? rev.review_images.slice(0, 4)
            : (rev.review_image ? [rev.review_image] : []);

        if (photosWrapEl && photosGridEl) {
            photosGridEl.innerHTML = '';
            if (rawPhotos.length > 0) {
                photosWrapEl.classList.remove('hidden');
                photosGridEl.className = rawPhotos.length === 1
                    ? 'grid grid-cols-1 gap-2'
                    : 'grid grid-cols-2 gap-2';

                rawPhotos.forEach((imgUrl) => {
                    const photoItem = document.createElement('div');
                    photoItem.className = rawPhotos.length === 1
                        ? 'relative group cursor-pointer rounded-2xl overflow-hidden border border-[#E5E5E5] bg-neutral-100 max-h-72'
                        : 'relative group cursor-pointer aspect-[4/3] rounded-2xl overflow-hidden border border-[#E5E5E5] bg-neutral-100';
                    photoItem.onclick = function() {
                        openReviewImagePreview(imgUrl, rev.name || 'Pengunjung Nucomu');
                    };

                    const imgEl = document.createElement('img');
                    imgEl.src = imgUrl;
                    imgEl.alt = `Foto ulasan ${rev.name || 'Pengunjung'}`;
                    imgEl.referrerPolicy = 'no-referrer';
                    imgEl.loading = 'lazy';
                    imgEl.className = 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300';

                    const badgeEl = document.createElement('span');
                    badgeEl.className = 'absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1.5';
                    badgeEl.innerHTML = '<i class="fa-solid fa-expand"></i><span>Perbesar</span>';

                    photoItem.appendChild(imgEl);
                    photoItem.appendChild(badgeEl);
                    photosGridEl.appendChild(photoItem);
                });
            } else {
                photosWrapEl.classList.add('hidden');
            }
        }

        if (modal && content) {
            pauseHomeReview();
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }

    function closeSingleReviewModal() {
        const modal = document.getElementById('singleReviewModal');
        const content = document.getElementById('singleReviewModalContent');
        if (modal && content) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            resumeHomeReview();
        }
    }

    function toggleReviewLike(btnEl) {
        if (!btnEl) return;
        const countEl = btnEl.querySelector('.like-count');
        const iconEl = btnEl.querySelector('i');
        const isLiked = btnEl.getAttribute('data-liked') === '1';
        let currentCount = countEl ? parseInt(countEl.innerText, 10) || 0 : 0;

        if (isLiked) {
            btnEl.setAttribute('data-liked', '0');
            if (countEl) countEl.innerText = Math.max(0, currentCount - 1);
            if (iconEl) {
                iconEl.classList.remove('fa-solid');
                iconEl.classList.add('fa-regular');
            }
        } else {
            btnEl.setAttribute('data-liked', '1');
            if (countEl) countEl.innerText = currentCount + 1;
            if (iconEl) {
                iconEl.classList.remove('fa-regular');
                iconEl.classList.add('fa-solid');
            }
        }
    }

    function updateHomeReviewUI() {
        updateStageCardsPositions();
        if (!homeReviews || homeReviews.length === 0) return;
        const current = homeReviews[homeReviewIndex];
        const nameEl = document.getElementById('homeReviewName');
        const handleEl = document.getElementById('homeReviewHandle');
        const timeAgoEl = document.getElementById('homeReviewTimeAgo');
        const avatarEl = document.getElementById('homeReviewAvatar');
        const textEl = document.getElementById('homeReviewText');
        const orderEl = document.getElementById('homeReviewOrderTag');
        const starContainer = document.getElementById('homeReviewStars');

        if (nameEl) nameEl.innerText = current.name;
        if (handleEl) handleEl.innerText = current.handle || 'Pengunjung Nucomu';
        if (timeAgoEl) timeAgoEl.innerText = '• ' + (current.time_ago || 'Baru-baru ini');
        if (avatarEl) avatarEl.src = current.avatar_url || current.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80';
        if (textEl) textEl.innerText = `"${current.content || current.text || ''}"`;
        if (orderEl) orderEl.innerHTML = `<i class="fa-solid fa-mug-saucer mr-1 text-[#1C1C1C]"></i> ${current.order_tag || current.order || 'Menu Nucomu'}`;

        if (starContainer) {
            starContainer.innerHTML = '';
            const stars = current.rating || current.stars || 5;
            for (let i = 0; i < stars; i++) {
                starContainer.innerHTML += '<i class="fa-solid fa-star"></i>';
            }
        }
    }

    function nextHomeReview() {
        if (!homeReviews || homeReviews.length <= 1) return;
        homeReviewIndex = (homeReviewIndex + 1) % homeReviews.length;
        updateHomeReviewUI();
        startHomeReviewLoop();
    }

    function prevHomeReview() {
        if (!homeReviews || homeReviews.length <= 1) return;
        homeReviewIndex = (homeReviewIndex - 1 + homeReviews.length) % homeReviews.length;
        updateHomeReviewUI();
        startHomeReviewLoop();
    }

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

    function startHomeReviewLoop() {
        if (homeReviewTimer) {
            clearInterval(homeReviewTimer);
        }
        homeReviewTimer = setInterval(() => {
            if (!isHomeReviewPaused && homeReviews && homeReviews.length > 1) {
                homeReviewIndex = (homeReviewIndex + 1) % homeReviews.length;
                updateHomeReviewUI();
            }
        }, HOME_REVIEW_INTERVAL);
    }

    function pauseHomeReview() { isHomeReviewPaused = true; }
    function resumeHomeReview() { isHomeReviewPaused = false; }

    let homeCurrentRating = 5;
    function openHomeReviewModal() {
        const modal = document.getElementById('homeReviewModal');
        const modalContent = document.getElementById('homeModalContent');
        if (modal && modalContent) {
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
        }
    }

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

    function openAllReviewsModal() {
        const modal = document.getElementById('allReviewsModal');
        const content = document.getElementById('allReviewsModalContent');
        if (modal && content) {
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
        }
    }

    function filterModalReviews(mode) {
        const items = document.querySelectorAll('#allReviewsModal .modal-review-item');
        const allBtn = document.getElementById('filterReviewsAllBtn');
        const photoBtn = document.getElementById('filterReviewsPhotoBtn');

        items.forEach(item => {
            if (mode === 'with-photo') {
                if (item.getAttribute('data-has-photo') === '1') {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            } else {
                item.classList.remove('hidden');
            }
        });

        if (allBtn && photoBtn) {
            if (mode === 'with-photo') {
                photoBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#1C1C1C] text-white transition-all shadow-2xs';
                allBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-[#F5F5F5] text-[#555555] border border-[#E5E5E5] transition-all';
            } else {
                allBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#1C1C1C] text-white transition-all shadow-2xs';
                photoBtn.className = 'px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-[#F5F5F5] text-[#555555] border border-[#E5E5E5] transition-all';
            }
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReviewImagePreview();
            closeSingleReviewModal();
            closeAllReviewsModal();
            closeHomeReviewModal();
        }
    });

    function initNucomuHomeReviews() {
        updateHomeReviewUI();
        startHomeReviewLoop();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNucomuHomeReviews);
    } else {
        initNucomuHomeReviews();
    }
</script>
