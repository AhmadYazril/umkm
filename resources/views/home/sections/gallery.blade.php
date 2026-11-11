<!-- =========================================================================
     SECTION 04: GALERI NUCOMU (3D STACKED CARDS SHOWCASE - CLEAN AIRY STAGE)
     Tampilan 3D bersih tanpa kotak hitam latar & tanpa pita marquee di bawah
     ========================================================================= -->
@php
    $galleryItems = collect();
    if (!empty($stories) && count($stories) > 0) {
        $galleryItems = collect($stories)->map(function($item) {
            $img = $item->image_url ?? ($item['image_url'] ?? ($item->image ?? ($item['image'] ?? '')));
            return [
                'id' => $item->id ?? ($item['id'] ?? null),
                'image' => $img,
                'caption' => $item->caption ?? ($item['caption'] ?? ($item->title ?? ($item['title'] ?? 'Nucomu Cafe'))),
                'description' => $item->description ?? ($item['description'] ?? 'Detail sudut arsitektur monokrom dan suasana hangat di Nucomu Cafe Tulungagung.'),
                'category' => $item->category_badge ?? ($item['category_badge'] ?? ($item->category ?? ($item['category'] ?? 'Nucomu'))),
            ];
        });
    } elseif (!empty($gallery) && count($gallery) > 0) {
        $galleryItems = collect($gallery)->map(function($item) {
            $img = is_array($item) ? ($item['image'] ?? '') : ($item->image_url ?? $item->image ?? '');
            $title = is_array($item) ? ($item['title'] ?? 'Nucomu Cafe') : ($item->caption ?? $item->title ?? 'Nucomu Cafe');
            $desc = is_array($item) ? ($item['description'] ?? ($item['short_desc'] ?? '')) : ($item->description ?? '');
            $cat = is_array($item) ? ($item['category'] ?? 'Nucomu') : ($item->category ?? 'Nucomu');
            return [
                'id' => is_array($item) ? ($item['id'] ?? null) : $item->id,
                'image' => $img,
                'caption' => $title,
                'description' => $desc ?: 'Detail sudut arsitektur monokrom dan suasana hangat di Nucomu Cafe Tulungagung.',
                'category' => $cat,
            ];
        });
    }

    if ($galleryItems->isEmpty()) {
        $defaultGallery = config('nucomu.gallery', []);
        $galleryItems = collect($defaultGallery)->map(function($item) {
            return [
                'id' => $item['id'] ?? 1,
                'image' => $item['image'] ?? '',
                'caption' => $item['title'] ?? 'Nucomu Cafe',
                'description' => $item['description'] ?? 'Detail sudut arsitektur monokrom dan suasana hangat di Nucomu Cafe Tulungagung.',
                'category' => $item['category'] ?? 'Nucomu',
            ];
        });
    }

    // Pastikan minimal ada 5 foto agar tumpukan 3D tampil proporsional
    if ($galleryItems->count() > 0 && $galleryItems->count() < 5) {
        $clones = $galleryItems->toArray();
        while ($galleryItems->count() < 5) {
            $galleryItems = $galleryItems->concat($clones);
        }
    }
    $totalPhotos = $galleryItems->count();
    $galleryArray = $galleryItems->values()->all();
@endphp

<style>
    /* ======================================================================
       GALLERY 3D STACKED CARDS (CLEAN & AIRY - FLOATING DIRECTLY ON PAGE)
       ====================================================================== */
    #gallery .nucomu-3d-gallery-viewport {
        perspective: 1400px;
        transform-style: preserve-3d;
    }

    #gallery .nucomu-gallery-card {
        transition:
            transform 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            opacity 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            filter 0.68s cubic-bezier(0.22, 1, 0.36, 1),
            box-shadow 0.45s ease,
            border-color 0.45s ease;
        will-change: transform, opacity, filter;
        backface-visibility: hidden;
    }

    /* Active Center Card Glow */
    #gallery .nucomu-gallery-card.is-center {
        z-index: 30;
        opacity: 1;
        filter: blur(0px);
        transform: translate3d(0%, -6px, 45px) rotateY(0deg) scale(1.04);
        box-shadow:
            0 28px 65px -15px rgba(0, 0, 0, 0.22),
            0 0 0 1px rgba(0, 0, 0, 0.08);
    }

    #gallery .nucomu-gallery-card.is-center:hover {
        transform: translate3d(0, -10px, 52px) rotateY(0deg) scale(1.05) !important;
        box-shadow:
            0 34px 75px -12px rgba(0, 0, 0, 0.28),
            0 0 0 1.5px rgba(0, 0, 0, 0.12);
    }

    /* 3D Stacked Cards: Left & Right */
    #gallery .nucomu-gallery-card.is-prev {
        z-index: 22;
        opacity: 0.82;
        filter: blur(0.4px);
        transform: translate3d(-64%, 6px, -70px) rotateY(12deg) scale(0.89);
    }
    #gallery .nucomu-gallery-card.is-next {
        z-index: 22;
        opacity: 0.82;
        filter: blur(0.4px);
        transform: translate3d(64%, 6px, -70px) rotateY(-12deg) scale(0.89);
    }
    #gallery .nucomu-gallery-card.is-prev:hover,
    #gallery .nucomu-gallery-card.is-next:hover {
        opacity: 0.98;
        filter: blur(0px);
    }
    #gallery .nucomu-gallery-card.is-stack-left {
        z-index: 12;
        opacity: 0.42;
        filter: blur(1.2px);
        transform: translate3d(-112%, 18px, -150px) rotateY(18deg) scale(0.79);
    }
    #gallery .nucomu-gallery-card.is-stack-right {
        z-index: 12;
        opacity: 0.42;
        filter: blur(1.2px);
        transform: translate3d(112%, 18px, -150px) rotateY(-18deg) scale(0.79);
    }
    #gallery .nucomu-gallery-card.is-hidden-left,
    #gallery .nucomu-gallery-card.is-hidden-right {
        z-index: 1;
        opacity: 0;
        pointer-events: none;
        transform: translate3d(0%, 28px, -240px) scale(0.7);
    }

    @media (max-width: 1023px) {
        #gallery .nucomu-gallery-card.is-prev {
            transform: translate3d(-25%, 10px, -60px) rotateY(8deg) scale(0.90);
            opacity: 0.50;
        }
        #gallery .nucomu-gallery-card.is-next {
            transform: translate3d(25%, 10px, -60px) rotateY(-8deg) scale(0.90);
            opacity: 0.50;
        }
        #gallery .nucomu-gallery-card.is-stack-left,
        #gallery .nucomu-gallery-card.is-stack-right {
            opacity: 0;
            pointer-events: none;
        }
    }
</style>

<section id="gallery"
         class="py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 relative z-10 bg-[#FAF8F5] text-[#1C1C1C] overflow-hidden border-b border-[#E5E5E5] select-none">

    <div class="max-w-6xl mx-auto relative z-10">

        <!-- Section Header (Monochrome Editorial + Gallery Showcase Badge) -->
        <div class="text-center mb-8 sm:mb-12 space-y-3">
            <div class="flex items-center justify-center gap-3">
                <span class="text-xs sm:text-[13px] font-bold text-[#666666] uppercase tracking-[0.22em] font-body">
                    04 / GALERI NUCOMU
                </span>
                <div class="w-12 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
            </div>

            <div class="flex items-center justify-center gap-2 text-lg sm:text-xl text-[#1C1C1C] pt-1" aria-hidden="true">
                <i class="fa-solid fa-camera drop-shadow-2xs"></i>
                <span class="text-xs font-mono tracking-widest uppercase text-neutral-500">Koleksi Visual</span>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-[#1C1C1C] tracking-tight font-editorial leading-tight">
                Sudut &amp; Momen di Nucomu
            </h2>

            <p class="text-[#666666] text-xs sm:text-sm max-w-xl mx-auto font-body leading-relaxed font-normal">
                Potret sudut arsitektur monokrom, detail seduhan kopi artisanal, dan kehangatan ruang kami di Tulungagung.
            </p>

            <!-- Action Capsules & Link to Full Archive -->
            <div class="pt-2 flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 font-body">
                <div class="inline-flex items-center gap-2 bg-white border border-[#E5E5E5] px-4 py-2 rounded-full shadow-2xs text-xs">
                    <span class="w-5 h-5 rounded-full bg-[#1C1C1C] flex items-center justify-center text-white text-[9px]">
                        <i class="fa-solid fa-images"></i>
                    </span>
                    <span class="font-bold text-[#1C1C1C]">{{ $totalPhotos }} Foto Terkurasi</span>
                </div>

                <a href="{{ route('gallery') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1C1C1C] hover:text-black bg-white hover:bg-[#F5F5F5] border border-[#E5E5E5] px-4 py-2 rounded-full transition-colors shadow-2xs">
                    <i class="fa-solid fa-border-all text-[#1C1C1C]"></i>
                    <span>Buka Arsip Galeri Lengkap</span>
                    <i class="fa-solid fa-arrow-right text-[10px] text-[#8A8A8A]"></i>
                </a>
            </div>
        </div>

        @if($totalPhotos > 0)
        <!-- ==============================================================
             CLEAN 3D STACKED GALLERY STAGE (TANPA KOTAK HITAM DI BELAKANG)
             ============================================================== -->
        <div id="homeGalleryContainer" class="relative py-2 sm:py-6 max-w-5xl mx-auto">

            <!-- Left Navigation Floating Button -->
            <button type="button"
                    onclick="prevGallery3D()"
                    aria-label="Foto Sebelumnya"
                    class="absolute left-0 sm:left-2 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white/90 hover:bg-[#1C1C1C] text-[#1C1C1C] hover:text-white border border-neutral-200/90 shadow-xl backdrop-blur-md flex items-center justify-center transition-all duration-300 active:scale-95">
                <i class="fa-solid fa-chevron-left text-sm sm:text-base"></i>
            </button>

            <!-- Right Navigation Floating Button -->
            <button type="button"
                    onclick="nextGallery3D()"
                    aria-label="Foto Selanjutnya"
                    class="absolute right-0 sm:right-2 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white/90 hover:bg-[#1C1C1C] text-[#1C1C1C] hover:text-white border border-neutral-200/90 shadow-xl backdrop-blur-md flex items-center justify-center transition-all duration-300 active:scale-95">
                <i class="fa-solid fa-chevron-right text-sm sm:text-base"></i>
            </button>

            <!-- 3D Viewport: Menampung Kartu-kartu 3D -->
            <div id="nucomuGalleryViewport"
                 class="nucomu-3d-gallery-viewport relative h-[390px] sm:h-[430px] md:h-[460px] flex items-center justify-center mx-auto w-full">
                @foreach($galleryArray as $idx => $item)
                    @php
                        // Alternate Mixed Monochrome Card Themes:
                        // Even: Crisp Alabaster Card (Putih Bersih Elegan)
                        // Odd: Obsidian Charcoal Card (Gelap Premium)
                        $isLightCard = $idx % 2 === 0;
                    @endphp
                    <article data-gallery-index="{{ $idx }}"
                             onclick="focusGallery3DCard({{ $idx }})"
                             class="nucomu-gallery-card absolute w-[86%] sm:w-[380px] md:w-[420px] lg:w-[450px] rounded-3xl p-4 sm:p-5 cursor-pointer flex flex-col justify-between min-h-[350px] sm:min-h-[390px] {{ $isLightCard ? 'bg-white text-[#181818] border border-neutral-200/90 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.15)]' : 'bg-gradient-to-br from-[#242424] via-[#1A1A1A] to-[#121212] text-white border border-white/20 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.30)]' }}">

                        <!-- Card Header: Category Badge + Numeric Index -->
                        <div class="flex items-center justify-between gap-3 mb-2.5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono uppercase tracking-wider font-semibold {{ $isLightCard ? 'bg-[#181818]/8 text-[#181818]' : 'bg-white/15 text-white' }}">
                                <i class="fa-solid fa-camera text-[9px]"></i>
                                <span>{{ $item['category'] }}</span>
                            </span>

                            <span class="font-mono text-xs font-bold tracking-widest {{ $isLightCard ? 'text-[#181818]/70' : 'text-white/70' }}">
                                {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($totalPhotos, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <!-- Card Photo Container -->
                        <div class="relative w-full aspect-[16/10] rounded-2xl overflow-hidden bg-black/30 border {{ $isLightCard ? 'border-neutral-200' : 'border-white/15' }} group/img shadow-md">
                            <img src="{{ $item['image'] }}"
                                 alt="{{ $item['caption'] }}"
                                 class="w-full h-full object-cover filter contrast-[1.05] group-hover/img:scale-105 transition-transform duration-700"
                                 loading="{{ $idx < 4 ? 'eager' : 'lazy' }}">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent pointer-events-none"></div>

                            <!-- Zoom/Lightbox Expand Button on Image -->
                            <button type="button"
                                    onclick="event.stopPropagation(); openGalleryModalFromIndex({{ $idx }})"
                                    title="Perbesar foto"
                                    aria-label="Perbesar foto {{ $item['caption'] }}"
                                    class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-black/60 hover:bg-black/90 text-white backdrop-blur-md border border-white/25 flex items-center justify-center opacity-80 group-hover/img:opacity-100 transition-all hover:scale-110">
                                <i class="fa-solid fa-expand text-[10px]"></i>
                            </button>
                        </div>

                        <!-- Card Body: Caption & Description -->
                        <div class="mt-3">
                            <h3 class="text-base sm:text-lg font-bold font-editorial tracking-tight truncate {{ $isLightCard ? 'text-[#181818]' : 'text-white' }}">
                                {{ $item['caption'] }}
                            </h3>
                            <p class="text-xs mt-0.5 line-clamp-1 font-body {{ $isLightCard ? 'text-[#666666]' : 'text-neutral-400' }}">
                                {{ $item['description'] }}
                            </p>
                        </div>

                        <!-- Card Footer: Location Tag & Action Button -->
                        <div class="pt-3 mt-2 border-t {{ $isLightCard ? 'border-[#181818]/10' : 'border-white/12' }} flex items-center justify-between gap-2 font-body">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium {{ $isLightCard ? 'text-[#444444]' : 'text-neutral-300' }}">
                                <i class="fa-solid fa-location-dot text-[10px] {{ $isLightCard ? 'text-[#181818]' : 'text-white' }}"></i>
                                <span>Nucomu Tulungagung</span>
                            </span>

                            <button type="button"
                                    onclick="event.stopPropagation(); openGalleryModalFromIndex({{ $idx }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-all hover:scale-105 {{ $isLightCard ? 'bg-[#181818] text-white hover:bg-black' : 'bg-white text-[#141414] hover:bg-neutral-200' }}">
                                <span>Detail</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination Dots (Minimalis di Bawah Kartu) -->
            <div class="mt-4 flex items-center justify-center relative z-30">
                <div id="nucomuGalleryDots" class="flex items-center justify-center gap-1.5">
                    @foreach($galleryArray as $idx => $item)
                        <button type="button"
                                onclick="focusGallery3DCard({{ $idx }})"
                                data-gallery-dot="{{ $idx }}"
                                aria-label="Pilih foto {{ $idx + 1 }}"
                                class="h-2 rounded-full transition-all duration-300 {{ $idx === 0 ? 'w-7 bg-[#1C1C1C]' : 'w-2 bg-[#D1D5DB] hover:bg-neutral-500' }}">
                        </button>
                    @endforeach
                </div>
            </div>

        </div>
        @else
        <div class="text-center py-20 bg-white rounded-3xl border border-[#E5E5E5] max-w-xl mx-auto p-8 shadow-xs">
            <i class="fa-solid fa-images text-3xl text-neutral-400 mb-3 block"></i>
            <h3 class="text-base font-bold text-[#1C1C1C] mb-1">Galeri Foto Belum Tersedia</h3>
            <p class="text-xs text-[#666666]">Foto galeri akan segera diperbarui di Nucomu Cafe.</p>
        </div>
        @endif

    </div>

</section>

<!-- =========================================================================
     LIGHTBOX / DETAIL MODAL UNTUK GALERI FOTO 3D
     ========================================================================= -->
<div id="galleryDetailModal"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-[#1C1C1C]/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeGalleryDetailModal()">
    <div id="galleryDetailModalContent"
         class="bg-[#181818] w-full max-w-2xl rounded-[28px] border border-white/20 shadow-2xl relative transform scale-95 transition-transform duration-300 max-h-[92vh] flex flex-col overflow-hidden font-body text-white"
         onclick="event.stopPropagation()">

        <!-- Modal Header -->
        <div class="p-4 sm:p-5 border-b border-white/10 flex items-center justify-between gap-3 bg-[#1F1F1F]">
            <div class="flex items-center gap-2.5">
                <span id="galleryModalCategory" class="px-2.5 py-0.5 rounded-full bg-white/15 text-[10px] font-mono uppercase tracking-wider text-white"></span>
                <span class="text-white/40 font-mono text-xs">|</span>
                <span id="galleryModalCounter" class="font-mono text-xs text-white/70"></span>
            </div>

            <button type="button"
                    onclick="closeGalleryDetailModal()"
                    aria-label="Tutup pratinjau"
                    class="w-8 h-8 rounded-full bg-white/10 hover:bg-white hover:text-black text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Image Area with Prev/Next Navigation -->
        <div class="relative w-full max-h-[58vh] overflow-hidden flex items-center justify-center bg-black">
            <img id="galleryModalImg"
                 src=""
                 alt=""
                 class="max-w-full max-h-[58vh] object-contain mx-auto filter contrast-105">

            <button type="button"
                    onclick="galleryModalStep(-1)"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-white hover:text-black text-white border border-white/20 flex items-center justify-center transition-all"
                    aria-label="Foto Sebelumnya">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>

            <button type="button"
                    onclick="galleryModalStep(1)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-white hover:text-black text-white border border-white/20 flex items-center justify-center transition-all"
                    aria-label="Foto Selanjutnya">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>
        </div>

        <!-- Modal Footer: Title & Full Description -->
        <div class="p-4 sm:p-5 border-t border-white/10 bg-[#1A1A1A] text-left">
            <h4 id="galleryModalTitle" class="text-base sm:text-lg font-bold font-editorial text-white"></h4>
            <p id="galleryModalDesc" class="text-xs text-neutral-300 mt-1 font-body leading-relaxed"></p>
        </div>
    </div>
</div>

<!-- =========================================================================
     JAVASCRIPT CONTROLLER: 3D STACK POSITIONS & AUTOPLAY (GALLERY)
     ========================================================================= -->
<script>
(function() {
    const galleryItems = @json($galleryArray);
    if (!galleryItems || galleryItems.length === 0) return;

    let galleryCurrentIndex = 0;
    const GALLERY_INTERVAL = 4500;
    let galleryTimer = null;
    let isGalleryPaused = false;

    // Menghitung & mengupdate posisi 3D kartu
    function updateGallery3DPositions() {
        const cards = document.querySelectorAll('#nucomuGalleryViewport .nucomu-gallery-card');
        const dots = document.querySelectorAll('#nucomuGalleryDots [data-gallery-dot]');
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

            let diff = idx - galleryCurrentIndex;
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
            if (idx === galleryCurrentIndex) {
                dot.className = 'h-2 rounded-full transition-all duration-300 w-7 bg-[#1C1C1C]';
            } else {
                dot.className = 'h-2 rounded-full transition-all duration-300 w-2 bg-[#D1D5DB] hover:bg-neutral-500';
            }
        });
    }

    function startGalleryLoop() {
        if (galleryTimer) clearInterval(galleryTimer);
        galleryTimer = setInterval(() => {
            if (!isGalleryPaused && galleryItems.length > 1) {
                galleryCurrentIndex = (galleryCurrentIndex + 1) % galleryItems.length;
                updateGallery3DPositions();
            }
        }, GALLERY_INTERVAL);
    }

    // Navigasi Publik
    window.nextGallery3D = function() {
        galleryCurrentIndex = (galleryCurrentIndex + 1) % galleryItems.length;
        updateGallery3DPositions();
        startGalleryLoop();
    };

    window.prevGallery3D = function() {
        galleryCurrentIndex = (galleryCurrentIndex - 1 + galleryItems.length) % galleryItems.length;
        updateGallery3DPositions();
        startGalleryLoop();
    };

    window.focusGallery3DCard = function(targetIdx) {
        galleryCurrentIndex = ((targetIdx % galleryItems.length) + galleryItems.length) % galleryItems.length;
        updateGallery3DPositions();
        startGalleryLoop();
    };

    // Pause on Hover
    const stageContainer = document.getElementById('homeGalleryContainer');
    if (stageContainer) {
        stageContainer.addEventListener('mouseenter', () => { isGalleryPaused = true; });
        stageContainer.addEventListener('mouseleave', () => { isGalleryPaused = false; });
    }

    // Touch Swipe Navigation for Mobile
    let touchStartX = 0;
    let touchEndX = 0;
    const viewportEl = document.getElementById('nucomuGalleryViewport');
    if (viewportEl) {
        viewportEl.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        viewportEl.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) {
                    window.nextGallery3D();
                } else {
                    window.prevGallery3D();
                }
            }
        }, { passive: true });
    }

    // Modal Detail Controller
    const modalEl = document.getElementById('galleryDetailModal');
    const modalContent = document.getElementById('galleryDetailModalContent');
    const modalImg = document.getElementById('galleryModalImg');
    const modalCategory = document.getElementById('galleryModalCategory');
    const modalCounter = document.getElementById('galleryModalCounter');
    const modalTitle = document.getElementById('galleryModalTitle');
    const modalDesc = document.getElementById('galleryModalDesc');

    window.openGalleryModalFromIndex = function(idx) {
        if (!modalEl || !modalContent) return;
        isGalleryPaused = true;
        galleryCurrentIndex = ((idx % galleryItems.length) + galleryItems.length) % galleryItems.length;
        updateModalData();
        modalEl.classList.remove('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    };

    window.closeGalleryDetailModal = function() {
        if (!modalEl || !modalContent) return;
        modalEl.classList.add('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        document.body.style.overflow = '';
        isGalleryPaused = false;
    };

    function updateModalData() {
        const item = galleryItems[galleryCurrentIndex];
        if (!item) return;
        if (modalImg) modalImg.src = item.image;
        if (modalCategory) modalCategory.textContent = item.category;
        if (modalCounter) modalCounter.textContent = (galleryCurrentIndex + 1) + ' / ' + galleryItems.length;
        if (modalTitle) modalTitle.textContent = item.caption;
        if (modalDesc) modalDesc.textContent = item.description;
    }

    window.galleryModalStep = function(step) {
        galleryCurrentIndex = (galleryCurrentIndex + step + galleryItems.length) % galleryItems.length;
        updateModalData();
        updateGallery3DPositions();
    };

    // Keyboard support
    window.addEventListener('keydown', function(e) {
        if (modalEl && !modalEl.classList.contains('pointer-events-none')) {
            if (e.key === 'Escape') closeGalleryDetailModal();
            if (e.key === 'ArrowRight') galleryModalStep(1);
            if (e.key === 'ArrowLeft') galleryModalStep(-1);
        }
    });

    // Inisialisasi Pertama
    updateGallery3DPositions();
    startGalleryLoop();

})();
</script>
