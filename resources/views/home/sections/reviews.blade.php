<!-- SECTION 6: UNIFIED REVIEWS SECTION (MONOCHROME EDITORIAL) -->
@php
    $allReviewsCollection = collect($dynamicReviews ?? []);
@endphp
<section id="reviews" class="py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 relative z-10 bg-[#FAF8F5] text-[#1C1C1C] overflow-hidden border-b border-[#E5E5E5]">

    <div class="max-w-6xl mx-auto relative z-10">
        
        <!-- Section Header (Monochrome Editorial Pattern) -->
        <div class="text-center mb-10 space-y-3">
            <div class="flex items-center justify-center gap-3">
                <span class="text-xs sm:text-[13px] font-bold text-[#666666] uppercase tracking-[0.22em] font-body">
                    05 / ULASAN PENGUNJUNG
                </span>
                <div class="w-12 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-[#1C1C1C] tracking-tight font-editorial leading-tight">
                Kata Mereka Tentang Nucomu
            </h2>

            <p class="text-[#666666] text-xs sm:text-sm max-w-xl mx-auto font-body leading-relaxed font-normal">
                Pengalaman langsung dari para pengunjung yang menikmati kopi, dessert artisanal, dan suasana hangat di Nucomu Cafe Tulungagung.
            </p>

            <!-- Rating Summary & Maps Link (Monochrome Pills) -->
            <div class="pt-3 flex flex-wrap items-center justify-center gap-3 font-body">
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

        <!-- Featured Spotlight Review Container (Auto-Loop, Monochrome Frame) -->
        <div class="max-w-3xl mx-auto mb-10">
            <div id="homeReviewContainer" 
                 class="w-full bg-white rounded-3xl sm:rounded-[2.5rem] p-6 sm:p-9 relative overflow-hidden border border-[#E5E5E5] shadow-sm transition-all duration-500 select-none"
                 onmouseenter="pauseHomeReview()" onmouseleave="resumeHomeReview()">

                <!-- Review Content -->
                <div class="space-y-5 relative z-10">
                    <!-- User Header -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5">
                            <img id="homeReviewAvatar" src="" alt="Avatar Pengunjung" referrerpolicy="no-referrer" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-[#E5E5E5] shadow-xs bg-neutral-100">
                            <div>
                                <h4 id="homeReviewName" class="text-base sm:text-lg font-bold text-[#1C1C1C] font-body leading-snug">Loading...</h4>
                                <div class="flex items-center gap-2 text-xs text-[#8A8A8A] mt-0.5 font-body">
                                    <span id="homeReviewHandle" class="text-[#1C1C1C] font-semibold">Local Guide</span>
                                    <span id="homeReviewTimeAgo" class="text-[#8A8A8A]">• Baru-baru ini</span>
                                </div>
                            </div>
                        </div>
                        <div id="homeReviewStars" class="flex text-[#1C1C1C] text-xs sm:text-sm gap-1 bg-[#F5F5F5] px-3.5 py-1.5 rounded-full border border-[#E5E5E5]">
                            <!-- Dynamic stars in sleek monochrome #1C1C1C -->
                        </div>
                    </div>

                    <!-- Quote Body + Optional Photos -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div id="homeReviewTextCol" class="sm:col-span-12 relative py-1">
                            <i class="fa-solid fa-quote-left text-3xl sm:text-4xl text-[#E5E5E5] absolute -top-2 -left-1"></i>
                            <p id="homeReviewText" class="text-sm sm:text-base text-[#1C1C1C] leading-relaxed italic relative z-10 pl-3 sm:pl-4 whitespace-pre-line font-body font-normal">
                                "Ulasan..."
                            </p>
                        </div>

                        <div id="homeReviewImageCol" class="hidden sm:col-span-5">
                            <div id="homeReviewImagesWrap"></div>
                        </div>
                    </div>

                    <!-- Ordered Tag & Navigation -->
                    <div class="pt-4 border-t border-[#E5E5E5] flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-[#8A8A8A] text-xs font-body uppercase tracking-wider text-[10px]">Pesanan:</span>
                            <span id="homeReviewOrderTag" class="font-semibold text-[#1C1C1C] bg-[#F5F5F5] border border-[#E5E5E5] px-3 py-1 rounded-full text-xs font-body">
                                <i class="fa-solid fa-mug-saucer mr-1 text-[#1C1C1C]"></i> Special Coffee
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="prevHomeReview()" class="w-8 h-8 rounded-full bg-white hover:bg-[#1C1C1C] hover:text-white border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs" aria-label="Ulasan Sebelumnya">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>
                            <button type="button" onclick="nextHomeReview()" class="w-8 h-8 rounded-full bg-white hover:bg-[#1C1C1C] hover:text-white border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] transition-colors shadow-2xs" aria-label="Ulasan Selanjutnya">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unified All Reviews Grid (Monochrome Clean Cards) -->
        <div id="allReviewsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach($allReviewsCollection as $rev)
                @php
                    $cardImages = !empty($rev['review_images']) && is_array($rev['review_images'])
                        ? array_slice($rev['review_images'], 0, 4)
                        : (!empty($rev['review_image']) ? [$rev['review_image']] : []);
                    $isHiddenInitially = $loop->index >= 3;
                @endphp
                <div class="bg-white hover:bg-[#FAFAFA] border border-[#E5E5E5] hover:border-[#1C1C1C]/35 rounded-2xl p-5 sm:p-6 flex flex-col justify-between transition-all duration-300 shadow-2xs hover:shadow-xs {{ $isHiddenInitially ? 'extra-review-card hidden' : '' }}">
                    <div>
                        <!-- Top User Row -->
                        <div class="flex items-start justify-between gap-2 mb-2.5">
                            <div class="flex items-center gap-3">
                                <img src="{{ $rev['avatar_url'] ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80' }}"
                                     alt="{{ $rev['name'] }}"
                                     referrerpolicy="no-referrer"
                                     class="w-10 h-10 rounded-full object-cover border border-[#E5E5E5] bg-neutral-100 shrink-0">
                                <div>
                                    <h5 class="text-sm font-bold text-[#1C1C1C] font-body leading-snug">{{ $rev['name'] }}</h5>
                                    <span class="text-[11px] text-[#8A8A8A] block font-body">{{ $rev['handle'] ?? 'Pengunjung Nucomu' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stars + Time Ago (Monochrome) -->
                        <div class="flex items-center gap-2 mb-2.5">
                            <div class="flex items-center gap-0.5 text-[#1C1C1C] text-xs">
                                @for($i = 0; $i < ($rev['rating'] ?? 5); $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                            </div>
                            <span class="text-[11px] text-[#8A8A8A] font-body">{{ $rev['time_ago'] ?? 'Baru-baru ini' }}</span>
                        </div>

                        <!-- Review Text -->
                        <p class="text-xs sm:text-sm text-[#555555] leading-relaxed mb-3.5 whitespace-pre-line font-body">{{ $rev['content'] }}</p>

                        <!-- Attached Review Photos (2x2 Grid for multi-photo or Single Photo) -->
                        @if(count($cardImages) > 1)
                            <div class="grid grid-cols-2 gap-1.5 mb-3.5 rounded-xl overflow-hidden border border-[#E5E5E5]">
                                @foreach($cardImages as $imgUrl)
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
                        @elseif(count($cardImages) === 1)
                            <div class="mb-3.5 rounded-xl overflow-hidden border border-[#E5E5E5] relative group cursor-pointer h-40 bg-neutral-100"
                                 onclick="openReviewImagePreview('{{ $cardImages[0] }}', '{{ addslashes($rev['name']) }}')">
                                <img src="{{ $cardImages[0] }}"
                                     alt="Foto ulasan {{ $rev['name'] }}"
                                     referrerpolicy="no-referrer"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2.5">
                                    <span class="text-[10px] font-semibold text-white flex items-center gap-1 font-body">
                                        <i class="fa-solid fa-expand text-[10px]"></i> Perbesar Foto Ulasan
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Order Tag (Monochrome Pill) -->
                    <div class="pt-3 border-t border-[#E5E5E5] flex items-center justify-between text-[11px]">
                        <span class="text-[#8A8A8A] font-body">Menu Favorit:</span>
                        <span class="text-[#1C1C1C] font-medium bg-[#F5F5F5] border border-[#E5E5E5] px-2.5 py-0.5 rounded-full font-body">
                            <i class="fa-solid fa-mug-saucer mr-1 text-[10px] text-[#1C1C1C]"></i> {{ $rev['order_tag'] ?? 'Menu Nucomu' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- View All Reviews Action Bar -->
        @if($allReviewsCollection->count() > 3)
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3 font-body">
                <button type="button"
                        id="toggleAllReviewsBtn"
                        onclick="toggleAllReviewsGrid()"
                        class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white hover:bg-[#1C1C1C] hover:text-white border border-[#1C1C1C] text-xs sm:text-sm font-bold text-[#1C1C1C] shadow-2xs hover:shadow-xs transition-all">
                    <i id="toggleAllReviewsIcon" class="fa-solid fa-layer-group"></i>
                    <span id="toggleAllReviewsText">View All Ulasan ({{ $allReviewsCollection->count() }} Ulasan)</span>
                    <i id="toggleAllReviewsChevron" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                </button>

                <button type="button"
                        onclick="openAllReviewsModal()"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#1C1C1C] hover:bg-black text-white text-xs sm:text-sm font-bold transition-all shadow-xs">
                    <i class="fa-solid fa-up-right-and-down-left-from-center text-xs"></i>
                    <span>Lihat Semua di Jendela Penuh</span>
                </button>
            </div>
        @endif
    </div>
</section>

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
        <div class="px-5 sm:px-6 py-3 bg-[#FAF8F5] border-b border-[#E5E5E5] flex flex-wrap items-center justify-between gap-3">
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
    const homeReviews = @json($dynamicReviews);
    let homeReviewIndex = 0;
    const HOME_REVIEW_INTERVAL = 5000;
    let homeReviewTimer = null;
    let isHomeReviewPaused = false;

    function updateHomeReviewUI() {
        if (!homeReviews || homeReviews.length === 0) return;
        const current = homeReviews[homeReviewIndex];
        const nameEl = document.getElementById('homeReviewName');
        const handleEl = document.getElementById('homeReviewHandle');
        const timeAgoEl = document.getElementById('homeReviewTimeAgo');
        const avatarEl = document.getElementById('homeReviewAvatar');
        const textEl = document.getElementById('homeReviewText');
        const orderEl = document.getElementById('homeReviewOrderTag');
        const starContainer = document.getElementById('homeReviewStars');
        const textCol = document.getElementById('homeReviewTextCol');
        const imgCol = document.getElementById('homeReviewImageCol');
        const imagesWrap = document.getElementById('homeReviewImagesWrap');

        if (nameEl) nameEl.innerText = current.name;
        if (handleEl) handleEl.innerText = current.handle || 'Pengunjung Nucomu';
        if (timeAgoEl) timeAgoEl.innerText = '• ' + (current.time_ago || 'Baru-baru ini');
        if (avatarEl) avatarEl.src = current.avatar_url || current.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80';
        if (textEl) textEl.innerText = `"${current.content || current.text || ''}"`;
        if (orderEl) orderEl.innerHTML = `<i class="fa-solid fa-mug-saucer mr-1 text-[#1C1C1C]"></i> ${current.order_tag || current.order || 'Menu Nucomu'}`;

        if (imgCol && imagesWrap && textCol) {
            const imgs = (Array.isArray(current.review_images) && current.review_images.length > 0)
                ? current.review_images.slice(0, 4)
                : (current.review_image ? [current.review_image] : []);

            if (imgs.length > 1) {
                const safeName = (current.name || '').replace(/'/g, "\\'");
                imagesWrap.innerHTML = `<div class="grid grid-cols-2 gap-1.5 rounded-2xl overflow-hidden border border-[#E5E5E5] bg-[#F5F5F5]">` +
                    imgs.map(url => `
                        <div class="relative group cursor-pointer aspect-[4/3] overflow-hidden" onclick="openReviewImagePreview('${url}', '${safeName}')">
                            <img src="${url}" alt="Foto Ulasan" referrerpolicy="no-referrer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    `).join('') +
                    `</div>`;
                imgCol.classList.remove('hidden');
                textCol.className = 'sm:col-span-7 relative py-1';
            } else if (imgs.length === 1) {
                const safeName = (current.name || '').replace(/'/g, "\\'");
                imagesWrap.innerHTML = `
                    <div class="relative rounded-2xl overflow-hidden border border-[#E5E5E5] group cursor-pointer aspect-[4/3] bg-[#F5F5F5]" onclick="openReviewImagePreview('${imgs[0]}', '${safeName}')">
                        <img src="${imgs[0]}" alt="Foto Ulasan" referrerpolicy="no-referrer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-full bg-black/60 backdrop-blur-xs text-[10px] text-white flex items-center gap-1 font-body">
                            <i class="fa-solid fa-magnifying-glass-plus text-[9px]"></i> Lihat Foto
                        </span>
                    </div>`;
                imgCol.classList.remove('hidden');
                textCol.className = 'sm:col-span-7 relative py-1';
            } else {
                imagesWrap.innerHTML = '';
                imgCol.classList.add('hidden');
                textCol.className = 'sm:col-span-12 relative py-1';
            }
        }

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
    }

    function prevHomeReview() {
        if (!homeReviews || homeReviews.length <= 1) return;
        homeReviewIndex = (homeReviewIndex - 1 + homeReviews.length) % homeReviews.length;
        updateHomeReviewUI();
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
        clearInterval(homeReviewTimer);
        homeReviewTimer = setInterval(() => {
            if (!isHomeReviewPaused && homeReviews.length > 1) {
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

    let isAllReviewsExpanded = false;
    function toggleAllReviewsGrid() {
        const extraCards = document.querySelectorAll('#allReviewsGrid .extra-review-card');
        const btnText = document.getElementById('toggleAllReviewsText');
        const chevron = document.getElementById('toggleAllReviewsChevron');
        isAllReviewsExpanded = !isAllReviewsExpanded;

        extraCards.forEach(card => {
            if (isAllReviewsExpanded) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        if (btnText) {
            btnText.innerText = isAllReviewsExpanded
                ? 'Sembunyikan Sebagian (Show Less)'
                : `View All Ulasan (${homeReviews ? homeReviews.length : extraCards.length + 3} Ulasan)`;
        }
        if (chevron) {
            if (isAllReviewsExpanded) {
                chevron.classList.add('rotate-180');
            } else {
                chevron.classList.remove('rotate-180');
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

    document.addEventListener('DOMContentLoaded', function() {
        updateHomeReviewUI();
        startHomeReviewLoop();
    });
</script>