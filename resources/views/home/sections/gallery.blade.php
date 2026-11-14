<!-- =========================================================================
     SECTION 04: GALERI NUCOMU (3D CURVED CYLINDER CAROUSEL)
     Efek lengkung 3D (dinding silinder), perspective CSS 3D, seamless auto-scroll,
     touch swipe, mouse drag, momentum inertia, dan lightbox detail modal.
     ========================================================================= -->
@php
    // Prioritaskan data galeri aktif dari database/Admin Galeri ($stories dari controller)
    $sourceStories = collect($stories ?? []);

    if ($sourceStories->isEmpty()) {
        try {
            $sourceStories = \App\Models\GalleryItem::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'desc')
                ->get();
        } catch (\Throwable $e) {
            $sourceStories = collect();
        }
    }

    $fallbackGalleryList = [
        [
            'id' => 1,
            'image' => 'images/gallery/interior.jpg',
            'caption' => 'Interior Nucomu Cafe',
            'description' => 'Suasana ruang utama yang tenang dan estetis dengan sentuhan arsitektur monokrom minimalis.',
            'category' => '#AMBIENCE',
            'alt' => 'Interior Nucomu Cafe dengan arsitektur monokrom dan suasana nyaman',
        ],
        [
            'id' => 2,
            'image' => 'images/gallery/area-duduk.jpg',
            'caption' => 'Area duduk nyaman dengan banyak colokan',
            'description' => 'Sudut santai yang luas dan tenang, ideal untuk bekerja, membaca buku, atau berdiskusi.',
            'category' => '#AMBIENCE',
            'alt' => 'Area duduk santai Nucomu Cafe yang nyaman dengan fasilitas colokan listrik',
        ],
        [
            'id' => 3,
            'image' => 'images/gallery/banoffee.jpg',
            'caption' => 'Banoffee — dessert ikonik Nucomu',
            'description' => 'Perpaduan manis pisang segar, saus toffee karamel lembut, dan taburan cokelat artisanal.',
            'category' => '#MENU',
            'alt' => 'Banoffee dessert ikonik Nucomu Cafe dengan lapisan pisang manis dan krim toffee',
        ],
        [
            'id' => 4,
            'image' => 'images/gallery/cheesecake.jpg',
            'caption' => 'Cheesecake creamy favorit pelanggan',
            'description' => 'Kue keju panggang lembut dengan tekstur creamy dan crust renyah gurih.',
            'category' => '#MENU',
            'alt' => 'Cheesecake creamy lembut favorit pelanggan Nucomu Cafe',
        ],
        [
            'id' => 5,
            'image' => 'images/gallery/foto5.jpg',
            'caption' => 'Seduhan Manual Brew Kopi Pilihan',
            'description' => 'Proses ekstraksi manual pour-over menggunakan biji kopi single origin beraroma kaya.',
            'category' => '#COFFEE',
            'alt' => 'Proses seduh manual brew pour-over kopi pilihan oleh barista Nucomu Cafe',
        ],
        [
            'id' => 6,
            'image' => 'images/gallery/foto6.jpg',
            'caption' => 'Signature Latte Art Hangat',
            'description' => 'Espresso bold berpadu dengan susu berbusa lembut berpola seni latte art elegan.',
            'category' => '#COFFEE',
            'alt' => 'Secangkir hot caffe latte dengan seni latte art elegan di Nucomu Cafe',
        ],
        [
            'id' => 7,
            'image' => 'images/gallery/foto7.jpg',
            'caption' => 'Area Terbuka Outdoor Teduh',
            'description' => 'Ruang terbuka berangin sejuk di bawah naungan teduh untuk bersantai di sore hari.',
            'category' => '#SPACE',
            'alt' => 'Sudut ruang outdoor Nucomu Cafe yang teduh, sejuk, dan santai',
        ],
        [
            'id' => 8,
            'image' => 'images/gallery/foto8.jpg',
            'caption' => 'Atmosfer Malam Hangat & Tenang',
            'description' => 'Pencahayaan warm tone temaram menyajikan atmosfer syahdu untuk obrolan malam hari.',
            'category' => '#VIBES',
            'alt' => 'Suasana malam hari yang hangat dan syahdu di Nucomu Cafe',
        ],
    ];

    $rawGalleryCollection = $sourceStories->isNotEmpty()
        ? $sourceStories
        : collect($fallbackGalleryList);

    $resolvedGallery = $rawGalleryCollection->values()->map(function ($rawItem, $index) {
        $rawImage = data_get($rawItem, 'image_url') ?? data_get($rawItem, 'image', '');
        $path = is_string($rawImage) ? trim($rawImage) : '';

        $src = ($path !== '' && ! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://'))
            ? asset(ltrim($path, '/'))
            : $path;

        $caption = trim((string) (data_get($rawItem, 'caption') ?? data_get($rawItem, 'title') ?? 'Nucomu Cafe'));
        if ($caption === '') {
            $caption = 'Nucomu Cafe';
        }

        $description = trim((string) data_get($rawItem, 'description', ''));
        if ($description === '') {
            $description = 'Momen dan suasana hangat yang terekam di Nucomu Cafe Tulungagung.';
        }

        $rawCategory = data_get($rawItem, 'category_badge') ?: data_get($rawItem, 'category', 'NUCOMU');
        $category = '#' . ltrim(strtoupper(trim((string) $rawCategory)), '#');

        $alt = trim((string) data_get($rawItem, 'alt', ''));
        if ($alt === '') {
            $alt = $caption . ' — Nucomu Cafe Tulungagung';
        }

        return [
            'id' => (int) (data_get($rawItem, 'id') ?? ($index + 1)),
            'image' => $path,
            'src' => $src,
            'local_path' => $path,
            'caption' => $caption,
            'description' => $description,
            'category' => $category,
            'alt' => $alt,
        ];
    })->all();

    $totalPhotos = count($resolvedGallery);
    $galleryArray = $resolvedGallery;

    // Duplikasi minimal 3 siklus (atau cukup agar track penuh) untuk perputaran infinite loop yang mulus
    $loopRepeats = $totalPhotos > 0 ? max(3, (int) ceil(12 / $totalPhotos)) : 3;
    $flatCards = [];
    for ($r = 0; $r < $loopRepeats; $r++) {
        foreach ($galleryArray as $origIdx => $item) {
            $flatCards[] = [
                'item' => $item,
                'origIdx' => $origIdx,
                'cardKey' => $r . '_' . $origIdx,
            ];
        }
    }
@endphp

<style>
    /* ======================================================================
       PALET & SECTION HEADER EDITORIAL (SPLIT DESKTOP 1 BARIS SEPERTI ULASAN)
       ====================================================================== */
    #gallery {
        --nucomu-text: #2B1D16;
        --nucomu-accent: #54392B;
        --nucomu-btn-secondary-bg: #FFFFFF;
        --nucomu-btn-secondary-border: #D8CBB8;
        --nucomu-btn-secondary-hover: #F7F1E8;
    }

    #gallery .nucomu-gallery-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0;
        margin: 0 auto 28px auto;
        padding: 0;
        position: relative;
        z-index: 10;
        max-width: 1040px;
        width: 100%;
    }

    #gallery .nucomu-gallery-header-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        min-width: 0;
        width: 100%;
    }

    #gallery .nucomu-gallery-eyebrow {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        padding-left: 0.14em;
        text-transform: uppercase;
        line-height: 1.2;
        color: var(--nucomu-accent);
        margin-bottom: 12px;
    }

    #gallery .nucomu-gallery-header-title {
        font-family: var(--font-heading), Georgia, serif;
        font-size: clamp(32px, 4vw, 48px);
        font-weight: 700;
        font-style: normal;
        line-height: 1.15;
        letter-spacing: -0.01em;
        color: var(--nucomu-text);
        margin: 0 auto;
        max-width: none;
        width: 100%;
    }

    #gallery .nucomu-gallery-header-title-accent {
        font-style: italic;
        font-weight: 700;
        color: var(--nucomu-accent);
    }

    #gallery .nucomu-gallery-header-subtitle {
        font-size: clamp(14px, 1.1vw, 15px);
        line-height: 1.55;
        color: #71645B;
        max-width: 820px;
        margin: 14px auto 0 auto;
        font-weight: 400;
        text-align: center;
    }

    #gallery .nucomu-gallery-header-right {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
        margin-top: 18px;
        width: 100%;
    }

    #gallery .nucomu-gallery-header-actions {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
    }

    #gallery .nucomu-gallery-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: auto;
        min-height: 40px;
        padding: 8px 16px;
        border-radius: 9999px;
        font-size: 13px;
        line-height: 1.25;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        background-color: var(--nucomu-btn-secondary-bg);
        border: 1px solid var(--nucomu-btn-secondary-border);
        color: var(--nucomu-text);
        font-weight: 500;
        transition:
            background-color 180ms ease,
            border-color 180ms ease,
            color 180ms ease,
            transform 180ms ease,
            box-shadow 180ms ease;
    }

    #gallery .nucomu-gallery-btn-secondary:hover {
        background-color: var(--nucomu-btn-secondary-hover);
        border-color: var(--nucomu-btn-secondary-border);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(43, 29, 22, 0.08);
    }

    @media (min-width: 640px) {
        #gallery .nucomu-gallery-badge,
        #gallery .nucomu-gallery-btn-secondary {
            min-height: 42px;
            padding: 9px 18px;
            font-size: 13.5px;
        }
    }

    @media (min-width: 768px) {
        #gallery .nucomu-gallery-header {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 0;
            margin-bottom: 36px;
        }

        #gallery .nucomu-gallery-header-title {
            white-space: nowrap;
        }

        #gallery .nucomu-gallery-header-right {
            align-items: center;
            text-align: center;
            width: auto;
            flex-shrink: 0;
        }

        #gallery .nucomu-gallery-header-actions {
            width: auto;
            justify-content: center;
            flex-wrap: nowrap;
        }
    }

    @media (min-width: 1024px) {
        #gallery .nucomu-gallery-header {
            margin-bottom: 40px;
        }

        #gallery .nucomu-gallery-header-title {
            white-space: nowrap;
        }
    }

    /* ======================================================================
       CIRCULAR 3D GALLERY STAGE (FRAMELESS STAGE ON WHITE BACKGROUND)
       Tanpa kotak/border/shadow pembungkus; clipping horizontal dijaga tetap rapi
       ====================================================================== */
    #circularGalleryStage {
        position: relative;
        background-color: transparent;
        overflow-x: clip;
        overflow-y: visible;
        user-select: none;
        -webkit-user-select: none;
        touch-action: pan-y;
    }

    /* Direct Parent Track: Memberikan perspective 3D (1200px) TANPA overflow: hidden */
    .gallery-3d-viewport {
        position: relative;
        z-index: 10;
        width: 100%;
        height: 100%;
        perspective: 1200px;
        -webkit-perspective: 1200px;
        perspective-origin: 50% 50%;
        cursor: default;
    }
    .gallery-3d-viewport.is-dragging {
        cursor: grabbing;
    }

    /* 3D Track: transform-style preserve-3d TANPA overflow: hidden */
    .gallery-3d-track {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
        -webkit-transform-style: preserve-3d;
        will-change: transform;
    }

    /* Kartu 3D Silinder (Rasio Seragam 3:4 agar tidak gepeng) */
    .carousel-3d-card {
        position: absolute;
        top: 50%;
        left: 0;
        aspect-ratio: 3 / 4;
        cursor: pointer;
        transform-origin: center center;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        will-change: transform, filter, opacity;
        border-radius: 18px;
        overflow: hidden;
        border: 3px solid #FFFFFF;
        box-shadow:
            0 2px 8px rgba(43, 29, 22, 0.08),
            0 12px 26px -4px rgba(43, 29, 22, 0.22),
            0 24px 48px -10px rgba(43, 29, 22, 0.24);
        background-color: #FFFFFF;
        transition: border-color 220ms ease, box-shadow 220ms ease;
    }
    .carousel-3d-card:hover {
        border-color: #FFFFFF;
        box-shadow:
            0 4px 12px rgba(43, 29, 22, 0.12),
            0 18px 34px -4px rgba(43, 29, 22, 0.28),
            0 30px 58px -10px rgba(43, 29, 22, 0.30);
    }
    @media (min-width: 640px) {
        .carousel-3d-card {
            border-radius: 20px;
        }
    }

    /* Side Feathers untuk pembauran visual tepi kiri & kanan ke latar putih */
    .circular-gallery-feather-left {
        position: absolute;
        inset-block: 0;
        left: 0;
        width: 32px;
        background: linear-gradient(to right, #FFFFFF 0%, rgba(255, 255, 255, 0.55) 50%, transparent 100%);
        pointer-events: none;
        z-index: 20;
    }
    .circular-gallery-feather-right {
        position: absolute;
        inset-block: 0;
        right: 0;
        width: 32px;
        background: linear-gradient(to left, #FFFFFF 0%, rgba(255, 255, 255, 0.55) 50%, transparent 100%);
        pointer-events: none;
        z-index: 20;
    }
    @media (min-width: 640px) {
        .circular-gallery-feather-left,
        .circular-gallery-feather-right {
            width: 64px;
        }
    }

</style>

<section id="gallery"
         class="pt-10 sm:pt-12 lg:pt-14 pb-4 sm:pb-6 lg:pb-8 relative z-10 bg-[#FFFFFF] text-[#1C1C1C] overflow-hidden select-none">

    <div class="w-full max-w-[1840px] mx-auto px-3 sm:px-4 lg:px-5 relative z-10">

        <!-- Section Header (Split 1 Baris Desktop: Kiri Eyebrow, Judul Fraunces & Subjudul | Kanan Aksi - Serasi dengan Ulasan) -->
        <div class="nucomu-gallery-header">
            <div class="nucomu-gallery-header-left">
                <span class="nucomu-gallery-eyebrow font-body">GALERI NUCOMU</span>
                <h2 class="nucomu-gallery-header-title">
                    Sudut &amp; Momen di <span class="nucomu-gallery-header-title-accent">Nucomu</span>
                </h2>
                <p class="nucomu-gallery-header-subtitle font-body">
                    Potret sudut arsitektur monokrom, detail seduhan kopi artisanal, dan kehangatan ruang kami di Tulungagung.
                </p>
            </div>

            <div class="nucomu-gallery-header-right">
                <div class="nucomu-gallery-header-actions font-body">
                    <a href="{{ route('gallery') }}"
                       class="nucomu-gallery-btn-secondary"
                       aria-label="Buka arsip galeri lengkap Nucomu Cafe">
                        <span>Buka Arsip Galeri</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" aria-hidden="true">
                            <path d="M7 17L17 7"/>
                            <path d="M7 7h10v10"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        @if($totalPhotos > 0)
        <!-- ==============================================================
             CIRCULAR 3D GALLERY STAGE (LENGKUNG DINDING SILINDER)
             ============================================================== -->
        <div id="circularGalleryStage"
             class="relative w-full mt-0 mb-1 sm:mb-2"
             style="height: clamp(430px, 52vh, 476px);">

            <!-- Soft Edge Fade Gradients -->
            <div class="circular-gallery-feather-left" aria-hidden="true"></div>
            <div class="circular-gallery-feather-right" aria-hidden="true"></div>

            <!-- Direct Track Parent Viewport (Perspective 1200px) -->
            <div id="gallery3DViewport" class="gallery-3d-viewport">
                <!-- 3D Track (transform-style: preserve-3d) -->
                <div id="gallery3DTrack" class="gallery-3d-track">
                    @foreach($flatCards as $idx => $cardData)
                        @php
                            $it = $cardData['item'];
                            $origIdx = $cardData['origIdx'];
                        @endphp
                        <div class="carousel-3d-card group/c"
                             data-orig-idx="{{ $origIdx }}"
                             style="width: clamp(210px, 22vw, 275px);">
                            <!-- Foto Tiap Item dengan src lokal berbeda, alt deskriptif, loading lazy, object-fit cover, dan onerror fallback -->
                            <img src="{{ $it['src'] }}"
                                 alt="{{ $it['alt'] }}"
                                 loading="lazy"
                                 draggable="false"
                                 onerror="window.handleGalleryImgError(this, '{{ addslashes($it['local_path']) }}', {{ (int) $it['id'] }}, '{{ addslashes($it['caption']) }}', '{{ addslashes($it['category']) }}')"
                                 class="w-full h-full object-cover pointer-events-none select-none">
                        </div>
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
                    class="w-8 h-8 rounded-full bg-white/10 hover:bg-white hover:text-black text-white flex items-center justify-center transition-colors cursor-pointer">
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
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-white hover:text-black text-white border border-white/20 flex items-center justify-center transition-all cursor-pointer"
                    aria-label="Foto Sebelumnya">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>

            <button type="button"
                    onclick="galleryModalStep(1)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/60 hover:bg-white hover:text-black text-white border border-white/20 flex items-center justify-center transition-all cursor-pointer"
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
     JAVASCRIPT CONTROLLER: CIRCULAR 3D CURVED CYLINDER (REQUESTANIMATIONFRAME)
     ========================================================================= -->
<script>
// Handler Global Onerror: Menampilkan placeholder abu-abu gelap unik per kartu jika gambar gagal dimuat
window.handleGalleryImgError = function(imgEl, fileName, cardNum, caption, category) {
    console.warn(`[Galeri Nucomu] Gagal memuat file gambar: "${fileName}" (Kartu #${cardNum} - ${caption})`);

    // Hentikan rekursi jika SVG placeholder di bawah ini memicu error
    imgEl.onerror = null;

    // Palet warna abu-abu gelap elegan yang berbeda per kartu
    const darkPalettes = [
        { bg: '#141414', border: '#2B2B2B', accent: '#C89D7C' },
        { bg: '#1A1A1A', border: '#333333', accent: '#D4A373' },
        { bg: '#181C1E', border: '#2C3539', accent: '#B58D67' },
        { bg: '#1E1B18', border: '#35302A', accent: '#E0A030' },
        { bg: '#191919', border: '#2F2F2F', accent: '#A0826C' },
        { bg: '#16191B', border: '#2B3236', accent: '#C29574' },
        { bg: '#1B1817', border: '#332D2B', accent: '#D0A37A' },
        { bg: '#121212', border: '#262626', accent: '#B8865B' }
    ];

    const theme = darkPalettes[(cardNum - 1) % darkPalettes.length];
    const safeCaption = (caption || 'Nucomu Cafe').replace(/[<>&"]/g, (c) => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;' })[c]);
    const safeCategory = (category || '#GALLERY').replace(/[<>&"]/g, '');
    const cleanFileName = (fileName || '').split('/').pop();

    // SVG Placeholder potret 3:4 (600x800)
    const svgContent = `
        <svg xmlns="http://www.w3.org/2000/svg" width="600" height="800" viewBox="0 0 600 800">
            <rect width="600" height="800" fill="${theme.bg}"/>
            <rect x="24" y="24" width="552" height="752" rx="28" fill="none" stroke="${theme.border}" stroke-width="2" stroke-dasharray="8 6"/>
            <rect x="210" y="240" width="180" height="36" rx="18" fill="#222222" stroke="${theme.border}" stroke-width="1.5"/>
            <text x="300" y="263" text-anchor="middle" fill="${theme.accent}" font-family="sans-serif" font-size="13" font-weight="700" letter-spacing="2">${safeCategory} · #${cardNum}</text>
            <circle cx="300" cy="370" r="54" fill="#202020" stroke="${theme.border}" stroke-width="2"/>
            <path d="M280 358h40l8 12h18c4.4 0 8 3.6 8 8v40c0 4.4-3.6 8-8 8H254c-4.4 0-8-3.6-8-8v-40c0-4.4 3.6-8 8-8h18l8-12z" fill="none" stroke="#666666" stroke-width="3" stroke-linejoin="round"/>
            <circle cx="300" cy="390" r="14" fill="none" stroke="#666666" stroke-width="3"/>
            <text x="300" y="480" text-anchor="middle" fill="#E8E8E8" font-family="'Playfair Display', Georgia, serif" font-size="22" font-weight="700">${safeCaption}</text>
            <text x="300" y="520" text-anchor="middle" fill="#888888" font-family="monospace" font-size="14">${cleanFileName}</text>
            <text x="300" y="555" text-anchor="middle" fill="#555555" font-family="sans-serif" font-size="12">File belum ditemukan di ${fileName}</text>
        </svg>
    `.trim();

    imgEl.src = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgContent);
};

(function() {
    const rawGalleryItems = @json($galleryArray);
    if (!rawGalleryItems || rawGalleryItems.length === 0) return;

    // --- Konfigurasi 3D Silinder & Gerakan ---
    const CONFIG = {
        autoSpeed: 0.65,         // Kecepatan auto-scroll ke kiri (pixel per frame)
        gap: 16,                 // Jarak horizontal antar kartu (pixel)
        maxAngle: 38,            // Sudut rotasi maksimum (derajat) ke arah tepi
        maxDepth: 220,           // Jarak kedalaman maksimum translateZ negatif (pixel)
        minScale: 0.88,          // Skala kartu saat berada di tepi paling luar
        dimmingFactor: 0.15,     // Redup sangat halus saat kartu mundur ke tepi
        lerpEase: 0.085,         // Kehalusan transisi gerakan lerp (0.05 - 0.12)
    };

    let galleryModalIndex = 0;
    const modalEl = document.getElementById('galleryDetailModal');
    const modalContent = document.getElementById('galleryDetailModalContent');
    const modalImg = document.getElementById('galleryModalImg');
    const modalCategory = document.getElementById('galleryModalCategory');
    const modalCounter = document.getElementById('galleryModalCounter');
    const modalTitle = document.getElementById('galleryModalTitle');
    const modalDesc = document.getElementById('galleryModalDesc');

    // 1. Lightbox Modal Controller
    window.openGalleryModalFromIndex = function(idx) {
        if (!modalEl || !modalContent) return;
        galleryModalIndex = ((idx % rawGalleryItems.length) + rawGalleryItems.length) % rawGalleryItems.length;
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
    };

    function updateModalData() {
        const item = rawGalleryItems[galleryModalIndex];
        if (!item) return;
        if (modalImg) {
            modalImg.src = item.src || item.image;
            modalImg.alt = item.alt || item.caption;
            modalImg.onerror = function() {
                window.handleGalleryImgError(modalImg, item.local_path || item.image, item.id || (galleryModalIndex + 1), item.caption, item.category);
            };
        }
        if (modalCategory) modalCategory.textContent = item.category;
        if (modalCounter) modalCounter.textContent = (galleryModalIndex + 1) + ' / ' + rawGalleryItems.length;
        if (modalTitle) modalTitle.textContent = item.caption;
        if (modalDesc) modalDesc.textContent = item.description;
    }

    window.galleryModalStep = function(step) {
        galleryModalIndex = (galleryModalIndex + step + rawGalleryItems.length) % rawGalleryItems.length;
        updateModalData();
    };

    window.addEventListener('keydown', function(e) {
        if (modalEl && !modalEl.classList.contains('pointer-events-none')) {
            if (e.key === 'Escape') closeGalleryDetailModal();
            if (e.key === 'ArrowRight') galleryModalStep(1);
            if (e.key === 'ArrowLeft') galleryModalStep(-1);
        }
    });

    // 2. Inisialisasi Carousel 3D Lengkung
    const stageEl = document.getElementById('circularGalleryStage');
    const viewportEl = document.getElementById('gallery3DViewport');
    const trackEl = document.getElementById('gallery3DTrack');
    if (!stageEl || !viewportEl || !trackEl) return;

    const cards = Array.from(trackEl.querySelectorAll('.carousel-3d-card'));
    const totalCards = cards.length;
    if (totalCards === 0) return;

    let viewportWidth = viewportEl.clientWidth || stageEl.clientWidth || 1000;
    let cardWidth = cards[0].offsetWidth || 250;
    let cardStride = cardWidth + CONFIG.gap;
    let totalTrackWidth = totalCards * cardStride;

    // Posisi dasar tiap kartu dalam track
    cards.forEach((card, idx) => {
        card.baseX = idx * cardStride;
    });

    function recalculateLayout() {
        viewportWidth = viewportEl.clientWidth || stageEl.clientWidth || 1000;
        if (cards[0]) {
            cardWidth = cards[0].offsetWidth || 250;
            cardStride = cardWidth + CONFIG.gap;
            totalTrackWidth = totalCards * cardStride;
            cards.forEach((card, idx) => {
                card.baseX = idx * cardStride;
            });
        }
    }
    window.addEventListener('resize', recalculateLayout);

    // Variabel gerak & interaksi
    let scrollX = 0;
    let targetScrollX = 0;
    let isDragging = false;
    let dragStartX = 0;
    let dragStartY = 0;
    let dragStartScrollX = 0;
    let lastDragX = 0;
    let velocity = 0;
    let hasMoved = false;
    let isHovered = false;
    let isDown = false;

    const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Event Hover
    stageEl.addEventListener('mouseenter', () => { isHovered = true; });
    stageEl.addEventListener('mouseleave', () => { isHovered = false; });

    // Pointer Drag (hanya aktif saat benar-benar ditarik horizontal, tidak mengganggu scroll halaman)
    viewportEl.addEventListener('pointerdown', (e) => {
        // Abaikan tombol kanan mouse
        if (e.pointerType === 'mouse' && e.button !== 0) return;

        isDown = true;
        isDragging = false;
        hasMoved = false;
        dragStartX = e.clientX;
        dragStartY = e.clientY;
        lastDragX = e.clientX;
        dragStartScrollX = targetScrollX;
        velocity = 0;
    });

    viewportEl.addEventListener('pointermove', (e) => {
        if (!isDown) return;

        const deltaX = e.clientX - dragStartX;
        const deltaY = e.clientY - dragStartY;

        if (!isDragging && Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaY) > 6) {
            isDown = false;
            return;
        }

        if (Math.abs(deltaX) > 6 && Math.abs(deltaX) > Math.abs(deltaY)) {
            hasMoved = true;
            if (!isDragging) {
                isDragging = true;
                viewportEl.classList.add('is-dragging');
                try {
                    viewportEl.setPointerCapture(e.pointerId);
                } catch (_) {}
            }
        }

        if (isDragging) {
            const stepX = e.clientX - lastDragX;
            velocity = stepX;
            lastDragX = e.clientX;
            targetScrollX -= stepX;
        }
    });

    function handlePointerEnd(e) {
        if (!isDown) return;
        isDown = false;
        viewportEl.classList.remove('is-dragging');

        try {
            if (viewportEl.hasPointerCapture && viewportEl.hasPointerCapture(e.pointerId)) {
                viewportEl.releasePointerCapture(e.pointerId);
            }
        } catch (_) {}

        if (isDragging) {
            isDragging = false;
            // Momentum inertia throw
            targetScrollX -= velocity * 6;
        }
    }

    viewportEl.addEventListener('pointerup', handlePointerEnd);
    viewportEl.addEventListener('pointercancel', handlePointerEnd);

    // Klik Kartu untuk Membuka Detail Modal (Hanya jika bukan hasil drag)
    cards.forEach((card) => {
        card.addEventListener('click', (e) => {
            if (hasMoved) return; // Mencegah klik saat menggeser
            const origIdx = parseInt(card.getAttribute('data-orig-idx'), 10);
            if (!isNaN(origIdx)) {
                openGalleryModalFromIndex(origIdx);
            }
        });
    });

    // 3. Render Loop via requestAnimationFrame
    let rafId = null;

    function render() {
        // Auto-scroll pelan ke kiri (loop tanpa putus)
        if (!isDown && !prefersReducedMotion) {
            const currentAutoSpeed = isHovered ? (CONFIG.autoSpeed * 0.25) : CONFIG.autoSpeed;
            targetScrollX += currentAutoSpeed;
        }

        // Kehalusan transisi (lerp)
        scrollX += (targetScrollX - scrollX) * CONFIG.lerpEase;

        const halfWidth = viewportWidth / 2;
        const centerX = halfWidth;

        for (let i = 0; i < totalCards; i++) {
            const card = cards[i];

            // Jarak horizontal relatif terhadap titik tengah container dalam loop modular
            let offset = ((card.baseX - scrollX) % totalTrackWidth);
            if (offset > totalTrackWidth / 2) offset -= totalTrackWidth;
            if (offset < -totalTrackWidth / 2) offset += totalTrackWidth;

            // Jarak ternormalisasi (0 = tengah tepat, 1 = di tepi kanan, -1 = di tepi kiri)
            const u = offset / halfWidth;
            const distAbs = Math.abs(u);

            // 1. Lengkung 3D Silinder (rotateY): Kartu di kanan condong ke kiri, kartu di kiri condong ke kanan
            const rotY = Math.max(-CONFIG.maxAngle, Math.min(CONFIG.maxAngle, -u * CONFIG.maxAngle));

            // 2. Kedalaman 3D (translateZ negatif): Semakin ke tepi semakin jauh ke belakang
            const transZ = -Math.pow(Math.min(distAbs, 1.8), 1.5) * CONFIG.maxDepth;

            // 3. Skala: Kartu tengah paling besar (1.0), makin ke tepi mengecil (0.88)
            const scale = Math.max(CONFIG.minScale, 1.0 - Math.min(distAbs, 1.0) * (1.0 - CONFIG.minScale));

            // 4. Kecerahan (brightness): Kartu tengah terang penuh (1.0 / filter none), ke tepi memudar sangat halus
            const brightness = Math.max(0.85, 1.0 - Math.min(distAbs, 1.2) * CONFIG.dimmingFactor);

            // 5. Opacity penuh untuk kartu terlihat agar foto jernih, hanya memudar di tepi terluar
            const opacity = distAbs > 0.90 ? Math.max(0, 1.0 - (distAbs - 0.90) * 2.2) : 1;

            // Posisi X di layar
            const cardLeft = centerX + offset - (cardWidth / 2);

            card.style.transform = `translate3d(${cardLeft.toFixed(1)}px, -50%, ${transZ.toFixed(1)}px) rotateY(${rotY.toFixed(1)}deg) scale(${scale.toFixed(3)})`;
            card.style.opacity = opacity.toFixed(2);

            // Kartu tengah dan sekitarnya tanpa filter brightness agar foto tetap tajam
            if (distAbs < 0.25) {
                card.style.filter = 'none';
            } else {
                card.style.filter = `brightness(${brightness.toFixed(2)})`;
            }

            card.style.pointerEvents = opacity < 0.1 ? 'none' : 'auto';
        }

        rafId = requestAnimationFrame(render);
    }

    rafId = requestAnimationFrame(render);

})();
</script>
