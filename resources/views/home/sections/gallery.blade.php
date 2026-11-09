<!-- SECTION 4: SHOWCASE GALLERY (3-PHOTO STUDIO DISPLAY - LARGE, CLEAN, MATCHING USER REFERENCE LAYOUT) -->
@php
    $galleryItems = collect();
    if (!empty($stories) && count($stories) > 0) {
        $galleryItems = collect($stories)->map(function($item) {
            return [
                'image' => $item['image'] ?? '',
                'caption' => $item['caption'] ?? ($item['title'] ?? 'Nucomu Cafe'),
                'description' => $item['description'] ?? ($item['desc'] ?? ''),
                'category' => $item['category_badge'] ?? ('#' . ($item['category'] ?? 'Nucomu')),
            ];
        });
    } elseif (!empty($gallery) && count($gallery) > 0) {
        $galleryItems = collect($gallery)->map(function($item) {
            return [
                'image' => $item['image'] ?? '',
                'caption' => $item['title'] ?? 'Nucomu Cafe',
                'description' => $item['description'] ?? '',
                'category' => '#' . ($item['category'] ?? 'Nucomu'),
            ];
        });
    }

    // Pastikan minimal ada 3 foto agar 3 frame dapat menampilkan foto berbeda
    if ($galleryItems->count() > 0 && $galleryItems->count() < 3) {
        $clones = $galleryItems->toArray();
        while ($galleryItems->count() < 3) {
            $galleryItems = $galleryItems->concat($clones);
        }
    }
    $totalPhotos = $galleryItems->count();
@endphp

<section id="gallery" class="py-14 sm:py-18 lg:py-22 px-4 sm:px-6 lg:px-8 relative z-10 bg-[#FAFAFA] text-[#1C1C1C] overflow-hidden border-b border-[#E5E5E5]">
    
    <div class="max-w-7xl mx-auto relative z-10">
        
        <!-- Section Header (Clean Editorial Pattern) -->
        <div class="max-w-3xl mx-auto text-center mb-8 sm:mb-12">
            <div class="flex items-center justify-center gap-3 mb-2.5">
                <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.22em] font-body">
                    04 / GALERI NUCOMU
                </span>
                <div class="w-10 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-[#1C1C1C] tracking-tight font-editorial leading-tight">
                Sudut &amp; Momen di Nucomu
            </h2>
            <p class="text-[#666666] text-xs sm:text-sm max-w-md mx-auto font-body leading-relaxed mt-2">
                Potret hangat suasana kafe, detail seduhan artisanal, dan arsitektur monokrom Nucomu Cafe.
            </p>
        </div>

        @if($totalPhotos > 0)
            <!-- =========================================================================
                 3D 3-PHOTO STUDIO DISPLAY (LEBAR, LEGA, TENGAH TERBUKA JELAS, KANAN TANPA BAYANGAN)
                 Tiga frame foto tampil serempak: Kiri (Miring Squircle), Tengah (Belakang, Lega), Kanan (Tegak Bersih)
                 ========================================================================= -->
            <div class="relative w-full max-w-6xl mx-auto h-[380px] sm:h-[480px] md:h-[540px] lg:h-[600px] flex items-center justify-center select-none">
                
                <!-- Ambient Floor Shadow (Studio Podium Effect) -->
                <div class="absolute bottom-2 sm:bottom-4 left-1/2 -translate-x-1/2 w-[85%] max-w-4xl h-12 sm:h-16 bg-black/[0.05] blur-2xl rounded-full pointer-events-none -z-0" aria-hidden="true"></div>

                <!-- =========================================================================
                     FRAME 1: CENTER / BACK FRAME (Kartu Tengah Belakang - Lebih Tinggi, Elegan, Terbuka Jelas)
                     ========================================================================= -->
                <div id="galleryFrameCenter"
                     class="gallery-frame-center group absolute top-1 sm:top-2 lg:top-3 left-1/2 -translate-x-1/2 z-10 w-[260px] sm:w-[350px] md:w-[400px] lg:w-[450px] aspect-square p-3 sm:p-4 lg:p-5 bg-white rounded-xl sm:rounded-2xl border border-neutral-200/90 shadow-[0_20px_45px_-10px_rgba(0,0,0,0.18)] transition-all duration-500">
                    
                    <!-- Washi Tape: Top-Right Corner (Monochrome Charcoal Tape) -->
                    <div class="nucomu-tape absolute -top-3.5 -right-3.5 w-20 sm:w-24 lg:w-28 h-6 sm:h-7 lg:h-8 bg-[#1C1C1C]/80 shadow-[0_3px_8px_rgba(0,0,0,0.22)] backdrop-blur-[2px] border-l-2 border-r-2 border-dashed border-neutral-400/50 -rotate-35 z-30 pointer-events-none"
                         style="clip-path: polygon(0% 4%, 100% 0%, 98% 96%, 2% 100%);"></div>

                    <!-- Inner Slides Container -->
                    <div class="relative w-full h-full rounded-lg sm:rounded-xl overflow-hidden bg-neutral-100">
                        @foreach($galleryItems as $idx => $item)
                            <div class="gallery-slide-center absolute inset-0 transition-opacity duration-700 ease-in-out {{ $idx === 1 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}"
                                 data-slide-index="{{ $idx }}">
                                <img src="{{ $item['image'] }}"
                                     alt="{{ $item['caption'] }}"
                                     class="w-full h-full object-cover object-center filter grayscale contrast-[1.08] brightness-[0.98] transition-transform duration-700 group-hover:scale-105"
                                     loading="{{ $idx < 3 ? 'eager' : 'lazy' }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- =========================================================================
                     FRAME 2: LEFT / FRONT FRAME (Kartu Kiri Depan - Sudut Bulat Squircle, Miring -6deg, Lebih ke Kiri)
                     ========================================================================= -->
                <div id="galleryFrameLeft"
                     class="gallery-frame-left group absolute bottom-3 sm:bottom-5 lg:bottom-7 left-0 sm:left-[2%] md:left-[3%] lg:left-[5%] z-20 w-[220px] sm:w-[300px] md:w-[340px] lg:w-[380px] aspect-square p-3 sm:p-4 lg:p-5 bg-white rounded-[28px] sm:rounded-[36px] lg:rounded-[44px] border border-neutral-200/90 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.22)] -rotate-3 sm:-rotate-6 md:-rotate-8 transition-transform duration-500 cursor-pointer hover:-rotate-3"
                     onclick="prevNucomuGallery(event)"
                     title="Klik untuk putar foto">
                    
                    <!-- Washi Tape: Top-Right Corner (Monochrome Charcoal Tape) -->
                    <div class="nucomu-tape absolute -top-3.5 -right-3.5 w-20 sm:w-24 lg:w-28 h-6 sm:h-7 lg:h-8 bg-[#1C1C1C]/80 shadow-[0_3px_8px_rgba(0,0,0,0.22)] backdrop-blur-[2px] border-l-2 border-r-2 border-dashed border-neutral-400/50 -rotate-32 z-30 pointer-events-none"
                         style="clip-path: polygon(0% 4%, 100% 0%, 98% 96%, 2% 100%);"></div>

                    <!-- Washi Tape: Bottom-Left Corner (Monochrome Charcoal Tape) -->
                    <div class="nucomu-tape absolute -bottom-3.5 -left-3.5 w-20 sm:w-24 lg:w-28 h-6 sm:h-7 lg:h-8 bg-[#1C1C1C]/80 shadow-[0_3px_8px_rgba(0,0,0,0.22)] backdrop-blur-[2px] border-l-2 border-r-2 border-dashed border-neutral-400/50 rotate-40 z-30 pointer-events-none"
                         style="clip-path: polygon(2% 0%, 98% 4%, 100% 100%, 0% 96%);"></div>

                    <!-- Inner Slides Container with Rounded Corners -->
                    <div class="relative w-full h-full rounded-[20px] sm:rounded-[28px] lg:rounded-[34px] overflow-hidden bg-neutral-100">
                        @foreach($galleryItems as $idx => $item)
                            <div class="gallery-slide-left absolute inset-0 transition-opacity duration-700 ease-in-out {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}"
                                 data-slide-index="{{ $idx }}">
                                <img src="{{ $item['image'] }}"
                                     alt="{{ $item['caption'] }}"
                                     class="w-full h-full object-cover object-center filter grayscale contrast-[1.08] brightness-[0.98] transition-transform duration-700 group-hover:scale-105"
                                     loading="{{ $idx < 3 ? 'eager' : 'lazy' }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- =========================================================================
                     FRAME 3: RIGHT / FRONT FRAME (Kartu Kanan Depan - Tegak Lurus, Lebih ke Kanan, Tanpa Pantulan Bawah)
                     ========================================================================= -->
                <div id="galleryFrameRight"
                     class="gallery-frame-right group absolute bottom-3 sm:bottom-5 lg:bottom-7 right-0 sm:right-[2%] md:right-[3%] lg:right-[5%] z-20 w-[220px] sm:w-[300px] md:w-[340px] lg:w-[380px] aspect-square p-3 sm:p-4 lg:p-5 bg-white rounded-lg sm:rounded-xl border border-neutral-200/90 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.22)] rotate-0 transition-transform duration-500 cursor-pointer"
                     onclick="nextNucomuGallery(event)"
                     title="Klik untuk putar foto">
                    
                    <!-- Washi Tape: Top-Right Corner (Monochrome Charcoal Tape) -->
                    <div class="nucomu-tape absolute -top-3.5 -right-3.5 w-20 sm:w-24 lg:w-28 h-6 sm:h-7 lg:h-8 bg-[#1C1C1C]/80 shadow-[0_3px_8px_rgba(0,0,0,0.22)] backdrop-blur-[2px] border-l-2 border-r-2 border-dashed border-neutral-400/50 -rotate-35 z-30 pointer-events-none"
                         style="clip-path: polygon(0% 4%, 100% 0%, 98% 96%, 2% 100%);"></div>

                    <!-- Washi Tape: Bottom-Left Corner (Monochrome Charcoal Tape) -->
                    <div class="nucomu-tape absolute -bottom-3.5 -left-3.5 w-20 sm:w-24 lg:w-28 h-6 sm:h-7 lg:h-8 bg-[#1C1C1C]/80 shadow-[0_3px_8px_rgba(0,0,0,0.22)] backdrop-blur-[2px] border-l-2 border-r-2 border-dashed border-neutral-400/50 rotate-45 z-30 pointer-events-none"
                         style="clip-path: polygon(2% 0%, 98% 4%, 100% 100%, 0% 96%);"></div>

                    <!-- Inner Slides Container -->
                    <div class="relative w-full h-full rounded-md sm:rounded-lg overflow-hidden bg-neutral-100">
                        @foreach($galleryItems as $idx => $item)
                            <div class="gallery-slide-right absolute inset-0 transition-opacity duration-700 ease-in-out {{ $idx === 2 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}"
                                 data-slide-index="{{ $idx }}">
                                <img src="{{ $item['image'] }}"
                                     alt="{{ $item['caption'] }}"
                                     class="w-full h-full object-cover object-center filter grayscale contrast-[1.08] brightness-[0.98] transition-transform duration-700 group-hover:scale-105"
                                     loading="{{ $idx < 3 ? 'eager' : 'lazy' }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- CAPTION & MINIMAL NAVIGATION CONTROLS (BAWAH FRAME) -->
            <div class="max-w-xl mx-auto mt-6 sm:mt-8 px-4 text-center">
                <!-- Center Photo Info Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-[#1C1C1C]/5 border border-[#E5E5E5] text-[10px] sm:text-xs font-semibold text-[#1C1C1C] uppercase tracking-wider mb-1.5 font-body" id="galleryCenterBadge">
                    {{ $galleryItems[1]['category'] ?? '#Nucomu' }}
                </div>
                
                <h3 id="galleryCenterTitle" class="text-base sm:text-lg md:text-xl font-bold text-[#1C1C1C] font-editorial tracking-tight transition-all duration-300">
                    {{ $galleryItems[1]['caption'] ?? 'Nucomu Cafe' }}
                </h3>
                
                <p id="galleryCenterDesc" class="text-xs text-[#666666] font-body mt-1 line-clamp-1 max-w-md mx-auto transition-all duration-300">
                    {{ $galleryItems[1]['description'] ?? '' }}
                </p>

                <!-- Navigation Chevrons & Dots -->
                <div class="flex items-center justify-center gap-3.5 mt-4">
                    <button type="button"
                            onclick="prevNucomuGallery(event)"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-[#E5E5E5] text-[#1C1C1C] hover:bg-[#1C1C1C] hover:text-white hover:border-[#1C1C1C] transition-all flex items-center justify-center shadow-2xs"
                            aria-label="Foto Sebelumnya"
                            title="Foto Sebelumnya">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Dot Indicators -->
                    <div id="galleryDotsContainer" class="flex items-center gap-1.5" aria-label="Indikator Foto Galeri">
                        @foreach($galleryItems as $dIdx => $dItem)
                            <button type="button"
                                    onclick="selectNucomuGallery({{ $dIdx }})"
                                    class="gallery-dot transition-all duration-300 {{ $dIdx === 1 ? 'w-5 h-1.5 bg-[#1C1C1C] rounded-full' : 'w-1.5 h-1.5 bg-[#D1D5DB] rounded-full hover:bg-neutral-500' }}"
                                    aria-label="Pilih foto {{ $dIdx + 1 }}"></button>
                        @endforeach
                    </div>

                    <button type="button"
                            onclick="nextNucomuGallery(event)"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-[#E5E5E5] text-[#1C1C1C] hover:bg-[#1C1C1C] hover:text-white hover:border-[#1C1C1C] transition-all flex items-center justify-center shadow-2xs"
                            aria-label="Foto Selanjutnya"
                            title="Foto Selanjutnya">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border border-[#E5E5E5] max-w-xl mx-auto p-8 shadow-xs">
                <i class="fa-solid fa-images text-3xl text-neutral-400 mb-3 block"></i>
                <h3 class="text-base font-bold text-[#1C1C1C] mb-1">Galeri Foto Belum Tersedia</h3>
                <p class="text-xs text-[#666666]">Foto galeri akan segera diperbarui di Nucomu Cafe.</p>
            </div>
        @endif

    </div>
</section>

<!-- SCRIPT: SMOOTH AUTOPLAY 3-FRAME CYCLER (CROSSFADE TRANSITION SEPERTI DI FASILITAS, JALAN SENDIRI TANPA PAUSE) -->
<script>
(function() {
    const galleryItems = @json($galleryItems);
    const total = galleryItems.length;
    if (total === 0) return;

    let currentIndex = 0;
    // Interval rotasi foto: 3500ms (3.5 detik) berjalan otomatis dan konsisten
    const ROTATION_INTERVAL = 3500;
    let rotationTimer = null;

    // Cache Slide NodeLists
    const leftSlides = document.querySelectorAll('.gallery-slide-left');
    const centerSlides = document.querySelectorAll('.gallery-slide-center');
    const rightSlides = document.querySelectorAll('.gallery-slide-right');

    // Caption Elements
    const badgeEl = document.getElementById('galleryCenterBadge');
    const titleEl = document.getElementById('galleryCenterTitle');
    const descEl = document.getElementById('galleryCenterDesc');
    const dotsContainer = document.getElementById('galleryDotsContainer');

    function updateFrameSlides(slidesList, targetIdx) {
        slidesList.forEach(slide => {
            const idx = parseInt(slide.getAttribute('data-slide-index'), 10);
            if (idx === targetIdx) {
                slide.classList.add('opacity-100', 'z-10');
                slide.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
                slide.setAttribute('aria-hidden', 'false');
                const img = slide.querySelector('img');
                if (img && img.loading === 'lazy') {
                    img.loading = 'eager';
                }
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0', 'pointer-events-none');
                slide.setAttribute('aria-hidden', 'true');
            }
        });
    }

    function renderGalleryStep() {
        const leftIdx = currentIndex % total;
        const centerIdx = (currentIndex + 1) % total;
        const rightIdx = (currentIndex + 2) % total;

        // Update ketiga frame foto secara serempak
        updateFrameSlides(leftSlides, leftIdx);
        updateFrameSlides(centerSlides, centerIdx);
        updateFrameSlides(rightSlides, rightIdx);

        // Update informasi caption untuk foto tengah (utama)
        const activeItem = galleryItems[centerIdx];
        if (activeItem) {
            if (badgeEl) badgeEl.textContent = activeItem.category || '#Nucomu';
            if (titleEl) titleEl.textContent = activeItem.caption || 'Nucomu Cafe';
            if (descEl) descEl.textContent = activeItem.description || '';
        }

        // Update dot indicators
        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.gallery-dot');
            dots.forEach((dot, i) => {
                if (i === centerIdx) {
                    dot.className = 'gallery-dot transition-all duration-300 w-5 h-1.5 bg-[#1C1C1C] rounded-full';
                } else {
                    dot.className = 'gallery-dot transition-all duration-300 w-1.5 h-1.5 bg-[#D1D5DB] rounded-full hover:bg-neutral-500';
                }
            });
        }
    }

    function advanceToNextPhoto() {
        currentIndex = (currentIndex + 1) % total;
        renderGalleryStep();
    }

    function stepToPrevPhoto() {
        currentIndex = (currentIndex - 1 + total) % total;
        renderGalleryStep();
    }

    // Autoplay jalan sendiri tanpa fitur tahan untuk stop
    function startAutoplay() {
        clearInterval(rotationTimer);
        rotationTimer = setInterval(advanceToNextPhoto, ROTATION_INTERVAL);
    }

    // Tombol kontrol manual: berganti foto dan lanjutkan autoplay secara otomatis
    window.nextNucomuGallery = function(e) {
        if (e) e.stopPropagation();
        advanceToNextPhoto();
        startAutoplay();
    };

    window.prevNucomuGallery = function(e) {
        if (e) e.stopPropagation();
        stepToPrevPhoto();
        startAutoplay();
    };

    window.selectNucomuGallery = function(targetIdx) {
        // Atur agar targetIdx menjadi foto tengah (center)
        currentIndex = (targetIdx - 1 + total) % total;
        renderGalleryStep();
        startAutoplay();
    };

    // Jalankan saat DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            renderGalleryStep();
            startAutoplay();
        });
    } else {
        renderGalleryStep();
        startAutoplay();
    }
})();
</script>
