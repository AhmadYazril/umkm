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
       REVIEWS FULL-WIDTH 3D STAGE (2 FRONT CARDS + 1 STACKED LEFT & RIGHT)
       Tanpa kotak background hitam, mentok full screen samping kanan kiri
       ====================================================================== */
    #reviews .nucomu-3d-viewport {
        perspective: 1600px;
        transform-style: preserve-3d;
    }

    #reviews .nucomu-stage-card {
        transition:
            transform 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            opacity 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            filter 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            box-shadow 0.45s ease;
        will-change: transform, opacity, filter;
        backface-visibility: hidden;
    }

    /* 1. FRONT LEFT CARD (Tampil 2 card di depan - Kiri) */
    #reviews .nucomu-stage-card.is-front-left {
        z-index: 30;
        opacity: 1;
        filter: blur(0px);
        transform: translate3d(calc(-50% - 15px), -6px, 40px) rotateY(-3deg) scale(1.02);
        box-shadow:
            0 24px 60px -15px rgba(0, 0, 0, 0.16),
            0 0 0 1px rgba(0, 0, 0, 0.08);
    }
    #reviews .nucomu-stage-card.is-front-left:hover {
        transform: translate3d(calc(-50% - 15px), -10px, 48px) rotateY(-3deg) scale(1.04) !important;
        box-shadow:
            0 30px 70px -12px rgba(0, 0, 0, 0.22),
            0 0 0 1.5px rgba(0, 0, 0, 0.12);
    }

    /* 2. FRONT RIGHT CARD (Tampil 2 card di depan - Kanan) */
    #reviews .nucomu-stage-card.is-front-right {
        z-index: 30;
        opacity: 1;
        filter: blur(0px);
        transform: translate3d(calc(50% + 15px), -6px, 40px) rotateY(3deg) scale(1.02);
        box-shadow:
            0 24px 60px -15px rgba(0, 0, 0, 0.16),
            0 0 0 1px rgba(0, 0, 0, 0.08);
    }
    #reviews .nucomu-stage-card.is-front-right:hover {
        transform: translate3d(calc(50% + 15px), -10px, 48px) rotateY(3deg) scale(1.04) !important;
        box-shadow:
            0 30px 70px -12px rgba(0, 0, 0, 0.22),
            0 0 0 1.5px rgba(0, 0, 0, 0.12);
    }

    /* 3. STACKED LEFT CARD (Di belakangnya ditumpuk 1 di kiri) */
    #reviews .nucomu-stage-card.is-stack-left {
        z-index: 15;
        opacity: 0.72;
        filter: blur(0.4px);
        transform: translate3d(-148%, 8px, -70px) rotateY(15deg) scale(0.88);
        box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.12);
    }
    #reviews .nucomu-stage-card.is-stack-left:hover {
        opacity: 0.95;
        filter: blur(0px);
    }

    /* 4. STACKED RIGHT CARD (Di belakangnya ditumpuk 1 di kanan) */
    #reviews .nucomu-stage-card.is-stack-right {
        z-index: 15;
        opacity: 0.72;
        filter: blur(0.4px);
        transform: translate3d(148%, 8px, -70px) rotateY(-15deg) scale(0.88);
        box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.12);
    }
    #reviews .nucomu-stage-card.is-stack-right:hover {
        opacity: 0.95;
        filter: blur(0px);
    }

    /* ALL OTHER CARDS HIDDEN IN BACKGROUND */
    #reviews .nucomu-stage-card.is-hidden-left,
    #reviews .nucomu-stage-card.is-hidden-right {
        z-index: 1;
        opacity: 0;
        pointer-events: none;
        transform: translate3d(0%, 25px, -240px) scale(0.7);
    }

    /* RESPONSIVE ON TABLET & MOBILE */
    @media (max-width: 1023px) {
        #reviews .nucomu-stage-card.is-front-left {
            transform: translate3d(-46%, -4px, 30px) rotateY(-2.5deg) scale(0.96);
        }
        #reviews .nucomu-stage-card.is-front-right {
            transform: translate3d(46%, -4px, 30px) rotateY(2.5deg) scale(0.96);
        }
        #reviews .nucomu-stage-card.is-stack-left {
            transform: translate3d(-122%, 8px, -70px) rotateY(12deg) scale(0.80);
            opacity: 0.45;
        }
        #reviews .nucomu-stage-card.is-stack-right {
            transform: translate3d(122%, 8px, -70px) rotateY(-12deg) scale(0.80);
            opacity: 0.45;
        }
    }

    @media (max-width: 767px) {
        #reviews .nucomu-stage-card.is-front-left {
            transform: translate3d(-24%, 0px, 20px) rotateY(-3deg) scale(0.88);
            z-index: 30;
        }
        #reviews .nucomu-stage-card.is-front-right {
            transform: translate3d(24%, 0px, 20px) rotateY(3deg) scale(0.88);
            z-index: 29;
        }
        #reviews .nucomu-stage-card.is-stack-left {
            transform: translate3d(-78%, 10px, -60px) rotateY(10deg) scale(0.72);
            opacity: 0.35;
            z-index: 10;
        }
        #reviews .nucomu-stage-card.is-stack-right {
            transform: translate3d(78%, 10px, -60px) rotateY(-10deg) scale(0.72);
            opacity: 0.35;
            z-index: 10;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        #reviews .nucomu-stage-card {
            transition: opacity 0.25s ease;
        }
    }
</style>

<section id="reviews"
         data-anim-mode="stacked-3d"
         class="py-16 sm:py-20 lg:py-24 relative z-10 bg-[#FAF8F5] text-[#1C1C1C] overflow-hidden border-b border-[#E5E5E5] select-none">

    <!-- Header Container (Centered Content) -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Section Header (Monochrome Editorial + 5-Star Showcase Badge) -->
        <div class="text-center mb-10 space-y-3">
            <div class="flex items-center justify-center gap-3">
                <span class="text-xs sm:text-[13px] font-bold text-[#666666] uppercase tracking-[0.22em] font-body">
                    05 / ULASAN PENGUNJUNG
                </span>
                <div class="w-12 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
            </div>

            <!-- 5-Star Row Above Title -->
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
    </div>

    <!-- ==============================================================
         FULL-WIDTH 3D STAGE (MENTOK FULL SCREEN SAMPAI SAMPING KANAN KIRI)
         Komposisi: 2 Card di Depan + 1 Ditumpuk di Belakang Kiri & Kanan
         Tanpa background kotak hitam & tanpa marquee ribbon di bawah
         ============================================================== -->
    <div id="homeReviewStageWrapper" class="w-full relative py-6 sm:py-8 overflow-hidden select-none">
        
        <!-- Left Navigation Floating Button -->
        <button type="button"
                onclick="prevHomeReview()"
                aria-label="Ulasan Sebelumnya"
                class="absolute left-3 sm:left-6 lg:left-12 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white/90 hover:bg-[#1C1C1C] text-[#1C1C1C] hover:text-white border border-neutral-200/90 shadow-xl backdrop-blur-md flex items-center justify-center transition-all duration-300 active:scale-95">
            <i class="fa-solid fa-chevron-left text-sm sm:text-base"></i>
        </button>

        <!-- Right Navigation Floating Button -->
        <button type="button"
                onclick="nextHomeReview()"
                aria-label="Ulasan Selanjutnya"
                class="absolute right-3 sm:right-6 lg:right-12 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white/90 hover:bg-[#1C1C1C] text-[#1C1C1C] hover:text-white border border-neutral-200/90 shadow-xl backdrop-blur-md flex items-center justify-center transition-all duration-300 active:scale-95">
            <i class="fa-solid fa-chevron-right text-sm sm:text-base"></i>
        </button>

        <!-- 3D Full-Width Viewport: Menampung 4 Kartu Aktif (2 Depan, 1 Belakang Kiri, 1 Belakang Kanan) -->
        <div id="nucomuStageViewport"
             class="nucomu-3d-viewport relative h-[360px] sm:h-[350px] flex items-center justify-center mx-auto w-full">
            @foreach($reviewsArray as $idx => $rev)
                @php
                    $stageImages = !empty($rev['review_images']) && is_array($rev['review_images'])
                        ? array_slice($rev['review_images'], 0, 4)
                        : (!empty($rev['review_image']) ? [$rev['review_image']] : []);
                    $firstPhoto = $stageImages[0] ?? null;
                    // Alternate Mixed Monochrome Card Themes:
                    // Even cards: Crisp Alabaster/White Card (#FFFFFF)
                    // Odd cards: Obsidian Charcoal Card (#242424 / #1A1A1A)
                    $isLightCard = $idx % 2 === 0;
                @endphp
                <article data-stage-index="{{ $idx }}"
                         onclick="focusStageCard({{ $idx }})"
                         class="nucomu-stage-card absolute w-[86%] sm:w-[350px] md:w-[390px] lg:w-[420px] rounded-3xl p-5 sm:p-6 cursor-pointer flex flex-col justify-between min-h-[290px] sm:min-h-[285px] {{ $isLightCard ? 'bg-white text-[#181818] border border-neutral-200/90 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.12)]' : 'bg-gradient-to-br from-[#242424] via-[#1A1A1A] to-[#121212] text-white border border-white/20 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.25)]' }}">

                    <div>
                        <!-- Card Header: Avatar + Name + Rating Stars -->
                        <div class="flex items-center justify-between gap-3 mb-3.5">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="{{ $rev['avatar_url'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80' }}"
                                         alt="{{ $rev['name'] }}"
                                         referrerpolicy="no-referrer"
                                         class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover {{ $isLightCard ? 'border-2 border-[#1C1C1C]/15 bg-neutral-100' : 'border-2 border-white/30 bg-neutral-800' }} shadow-md">
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

                    <!-- Card Footer: Like/Helpful, Order Tag & Action Circle -->
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

        <!-- Pagination Dots -->
        <div class="mt-4 flex items-center justify-center relative z-30">
            <div id="nucomuStageDots" class="flex items-center justify-center gap-1.5">
                @foreach($reviewsArray as $idx => $rev)
                    <button type="button"
                            onclick="focusStageCard({{ $idx }})"
                            data-dot-index="{{ $idx }}"
                            aria-label="Pilih ulasan {{ $idx + 1 }}"
                            class="h-2 rounded-full transition-all duration-300 {{ $idx === 0 || $idx === 1 ? 'w-6 bg-[#1C1C1C]' : 'w-2 bg-[#D1D5DB] hover:bg-neutral-500' }}">
                    </button>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Hidden Hooks for Legacy Compatibility -->
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
                        class="text-xs font-bold text-white bg-[#1C1C1C] hover:bg-black px-4 py-2 rounded-full inline-flex items-center gap-1.5 shadow-xs transition-all">
                    <i class="fa-solid fa-plus-circle text-white"></i>
                    <span>Tulis Ulasan</span>
                </button>
                <button type="button"
                        onclick="closeAllReviewsModal()"
                        aria-label="Tutup ulasan"
                        class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] hover:bg-[#1C1C1C] hover:text-white flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs">
                    <i class="fa-solid fa-xmark text-sm"></i>
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
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#1C1C1C] text-white transition-all shadow-2xs">
                    Semua ({{ $allReviewsCollection->count() }})
                </button>
                <button type="button"
                        id="filterReviewsPhotoBtn"
                        onclick="filterModalReviews('with-photo')"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-[#F5F5F5] text-[#555555] border border-[#E5E5E5] transition-all">
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
                    @endphp
                    <div class="modal-review-item bg-white border border-[#E5E5E5] rounded-2xl p-4 sm:p-5 flex flex-col justify-between hover:border-[#1C1C1C]/40 transition-colors shadow-2xs"
                         data-has-photo="{{ $hasPhotos ? '1' : '0' }}">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $mRev['avatar_url'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80' }}"
                                         alt="{{ $mRev['name'] }}"
                                         referrerpolicy="no-referrer"
                                         loading="lazy"
                                         class="w-10 h-10 rounded-full object-cover border border-[#E5E5E5] bg-neutral-100">
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
                    class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] hover:bg-[#1C1C1C] hover:text-white flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs">
                <i class="fa-solid fa-xmark text-sm"></i>
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
                                class="text-2xl text-[#1C1C1C] hover:scale-125 transition-transform">
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
                        class="px-4 py-2 rounded-full border border-[#E5E5E5] text-xs font-semibold text-[#666666] hover:bg-[#F5F5F5] transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-full bg-[#1C1C1C] hover:bg-black text-white text-xs font-bold transition-all shadow-xs">
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
                class="absolute -top-12 right-0 w-9 h-9 rounded-full bg-white/20 hover:bg-white text-white hover:text-black flex items-center justify-center transition-colors">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>
        <img id="reviewLightboxImg" src="" alt="Pratinjau Foto Ulasan" class="max-h-[80vh] w-auto rounded-2xl object-contain border border-white/20 shadow-2xl">
        <p id="reviewLightboxCaption" class="text-xs text-white/80 mt-3 font-body text-center"></p>
    </div>
</div>

<!-- =========================================================================
     JAVASCRIPT: 4-CARD 3D COMPOSITION (2 FRONT + 1 STACKED LEFT & RIGHT)
     ========================================================================= -->
<script>
    const homeReviews = @json($reviewsArray);
    let homeReviewIndex = 0;
    const HOME_REVIEW_INTERVAL = 5000;
    let homeReviewTimer = null;
    let isHomeReviewPaused = false;

    // Komposisi 3D: 2 kartu di depan + 1 kartu ditumpuk di belakang kiri & kanan
    function updateStageCardsPositions() {
        const cards = document.querySelectorAll('#nucomuStageViewport .nucomu-stage-card');
        const dots = document.querySelectorAll('#nucomuStageDots [data-dot-index]');
        const total = cards.length;
        if (total === 0) return;

        cards.forEach((card, idx) => {
            card.classList.remove(
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
                // Kartu Depan Kiri
                card.classList.add('is-front-left');
            } else if (diff === 1 || (total === 2 && diff === -1)) {
                // Kartu Depan Kanan
                card.classList.add('is-front-right');
            } else if (diff === -1) {
                // Ditumpuk 1 di Belakang Kiri
                card.classList.add('is-stack-left');
            } else if (diff === 2) {
                // Ditumpuk 1 di Belakang Kanan
                card.classList.add('is-stack-right');
            } else if (diff < -1) {
                card.classList.add('is-hidden-left');
            } else {
                card.classList.add('is-hidden-right');
            }
        });

        dots.forEach((dot, idx) => {
            if (idx === homeReviewIndex || idx === (homeReviewIndex + 1) % total) {
                dot.className = 'h-2 rounded-full transition-all duration-300 w-6 bg-[#1C1C1C]';
            } else {
                dot.className = 'h-2 rounded-full transition-all duration-300 w-2 bg-[#D1D5DB] hover:bg-neutral-500';
            }
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

    // Hover Pause on Stage
    const reviewStageEl = document.getElementById('homeReviewStageWrapper');
    if (reviewStageEl) {
        reviewStageEl.addEventListener('mouseenter', pauseHomeReview);
        reviewStageEl.addEventListener('mouseleave', resumeHomeReview);
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

    // Modal Details Controller
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
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            const allModal = document.getElementById('allReviewsModal');
            if (!allModal || allModal.classList.contains('pointer-events-none')) {
                document.body.style.overflow = '';
                resumeHomeReview();
            }
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

    let homeCurrentRating = 5;
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
            document.body.style.overflow = '';
            resumeHomeReview();
        }
    });

    function initNucomuHomeReviews() {
        updateStageCardsPositions();
        startHomeReviewLoop();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNucomuHomeReviews);
    } else {
        initNucomuHomeReviews();
    }
</script>
