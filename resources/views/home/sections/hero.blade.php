<!-- SECTION 1: HOME / HERO (4 SLIDES: SLIDE 0 WELCOME LATTE + SLIDES 1-3 PRODUCT IN FRONT OF GIANT HEADLINE) -->
@php
    $activeBanners = $activeBanners ?? ($banners ?? collect());
    $activeBanners = $activeBanners ?? ($banners ?? collect());

    // 3D Coffee Beans for Slide 1 (Fresh Beans) matching reference recording exactly
    $vortexBeans = [
        // Bokeh foreground (large & depth-of-field blurred)
        ['src' => 'images/hero/bean-1.webp', 'x' => -330, 'y' => 175, 'z' => 95, 'w' => 140, 'rot' => -24, 'blur' => 3.8, 'opacity' => 0.95, 'zIndex' => 35, 'animType' => 1, 'animDur' => 6.4, 'animDelay' => 0.0, 'entranceDelay' => 0.15, 'depth' => 46, 'extraClass' => 'max-sm:hidden'],
        ['src' => 'images/hero/bean-2.webp', 'x' => 310, 'y' => 145, 'z' => 90, 'w' => 135, 'rot' => 32, 'blur' => 3.4, 'opacity' => 0.95, 'zIndex' => 35, 'animType' => 2, 'animDur' => 6.0, 'animDelay' => -1.2, 'entranceDelay' => 0.25, 'depth' => 42, 'extraClass' => 'max-sm:hidden'],
        ['src' => 'images/hero/bean-3.webp', 'x' => 340, 'y' => -160, 'z' => 80, 'w' => 95, 'rot' => 28, 'blur' => 2.6, 'opacity' => 0.92, 'zIndex' => 35, 'animType' => 3, 'animDur' => 5.4, 'animDelay' => -2.0, 'entranceDelay' => 0.35, 'depth' => 36, 'extraClass' => 'max-sm:hidden'],
        ['src' => 'images/hero/bean-1.webp', 'x' => 175, 'y' => 220, 'z' => 75, 'w' => 85, 'rot' => 42, 'blur' => 2.0, 'opacity' => 0.90, 'zIndex' => 32, 'animType' => 4, 'animDur' => 5.8, 'animDelay' => -0.8, 'entranceDelay' => 0.2, 'depth' => 32, 'extraClass' => 'max-sm:hidden'],

        // Midground sharp swirl along pack bottom & spiral curving up-right
        ['src' => 'images/hero/bean-2.webp', 'x' => -155, 'y' => 110, 'z' => 35, 'w' => 56, 'rot' => 32, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 25, 'animType' => 1, 'animDur' => 5.0, 'animDelay' => -0.5, 'entranceDelay' => 0.1, 'depth' => 22, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-3.webp', 'x' => -75, 'y' => 140, 'z' => 45, 'w' => 60, 'rot' => 25, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 26, 'animType' => 2, 'animDur' => 5.3, 'animDelay' => -1.0, 'entranceDelay' => 0.15, 'depth' => 24, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-1.webp', 'x' => 15, 'y' => 145, 'z' => 50, 'w' => 60, 'rot' => 30, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 26, 'animType' => 3, 'animDur' => 4.8, 'animDelay' => -1.5, 'entranceDelay' => 0.2, 'depth' => 25, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-2.webp', 'x' => 95, 'y' => 125, 'z' => 45, 'w' => 54, 'rot' => 28, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 25, 'animType' => 4, 'animDur' => 5.1, 'animDelay' => -2.0, 'entranceDelay' => 0.25, 'depth' => 23, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-3.webp', 'x' => 165, 'y' => 95, 'z' => 35, 'w' => 52, 'rot' => 22, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 24, 'animType' => 1, 'animDur' => 4.9, 'animDelay' => -2.5, 'entranceDelay' => 0.3, 'depth' => 20, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-1.webp', 'x' => 195, 'y' => 45, 'z' => 25, 'w' => 48, 'rot' => 15, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 24, 'animType' => 2, 'animDur' => 5.2, 'animDelay' => -3.0, 'entranceDelay' => 0.35, 'depth' => 18, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-2.webp', 'x' => 195, 'y' => -10, 'z' => 15, 'w' => 44, 'rot' => 5, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 23, 'animType' => 3, 'animDur' => 5.5, 'animDelay' => -3.5, 'entranceDelay' => 0.4, 'depth' => 16, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-3.webp', 'x' => 170, 'y' => -60, 'z' => 5, 'w' => 40, 'rot' => -10, 'blur' => 0, 'opacity' => 0.95, 'zIndex' => 22, 'animType' => 4, 'animDur' => 5.0, 'animDelay' => -4.0, 'entranceDelay' => 0.45, 'depth' => 14, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-1.webp', 'x' => 135, 'y' => -95, 'z' => -10, 'w' => 36, 'rot' => -25, 'blur' => 0.4, 'opacity' => 0.9, 'zIndex' => 18, 'animType' => 1, 'animDur' => 4.7, 'animDelay' => -4.5, 'entranceDelay' => 0.5, 'depth' => 12, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-2.webp', 'x' => 90, 'y' => -120, 'z' => -25, 'w' => 32, 'rot' => -40, 'blur' => 0.8, 'opacity' => 0.85, 'zIndex' => 16, 'animType' => 2, 'animDur' => 5.3, 'animDelay' => -5.0, 'entranceDelay' => 0.55, 'depth' => 10, 'extraClass' => 'max-sm:scale-75'],

        // Beans along lower-left curve
        ['src' => 'images/hero/bean-3.webp', 'x' => -240, 'y' => 80, 'z' => 30, 'w' => 54, 'rot' => 40, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 24, 'animType' => 3, 'animDur' => 5.6, 'animDelay' => -1.8, 'entranceDelay' => 0.2, 'depth' => 20, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-1.webp', 'x' => -300, 'y' => 60, 'z' => 20, 'w' => 48, 'rot' => 35, 'blur' => 0.2, 'opacity' => 0.95, 'zIndex' => 23, 'animType' => 4, 'animDur' => 4.9, 'animDelay' => -2.3, 'entranceDelay' => 0.25, 'depth' => 18, 'extraClass' => 'max-sm:scale-75'],

        // Background floating beans (behind pack)
        ['src' => 'images/hero/bean-2.webp', 'x' => -190, 'y' => -160, 'z' => -40, 'w' => 68, 'rot' => 22, 'blur' => 1.6, 'opacity' => 0.85, 'zIndex' => 14, 'animType' => 1, 'animDur' => 6.2, 'animDelay' => -0.7, 'entranceDelay' => 0.3, 'depth' => 14, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/bean-3.webp', 'x' => 105, 'y' => -185, 'z' => -35, 'w' => 52, 'rot' => 45, 'blur' => 1.2, 'opacity' => 0.85, 'zIndex' => 14, 'animType' => 2, 'animDur' => 5.7, 'animDelay' => -1.4, 'entranceDelay' => 0.35, 'depth' => 12, 'extraClass' => 'max-sm:scale-75'],
    ];

    $rawSlides = [
        [
            'id' => 'welcome',
            'type' => 'welcome',
            'label' => 'NUCOMU CAFE',
            'headline_line1' => 'Your Everyday',
            'headline_line2' => 'Coffee Spot.',
            'paragraph' => 'Nikmati dessert manis, hidangan lezat, camilan favorit, serta kopi dan minuman menyegarkan dengan harga yang bersahabat.',
            'cta_primary' => [
                'text' => 'Lihat Menu',
                'url' => route('home') . '#menu',
            ],
            'cta_secondary' => [
                'text' => 'Tentang Kami',
                'url' => route('home') . '#about',
            ],
            'image_webp' => 'images/hero/hero-welcome.webp',
            'image_jpg' => 'images/hero/hero-welcome.jpg',
            'bg_color' => '#140E0A',
            'theme' => 'dark',
            'product_image' => 'images/hero/hero-welcome.webp',
        ],
        [
            'id' => 'fresh-beans',
            'type' => 'product',
            'label' => '01 / SPECIALTY ROASTERY',
            'giant_headline' => 'FRESH BEANS',
            'giant_headline_class' => 'text-white/18',
            'subtext' => null,
            'cta_primary' => [
                'text' => 'Lihat Menu',
                'url' => route('home') . '#menu',
            ],
            'product_image' => 'images/hero/coffee-pack.webp',
            'product_name' => 'Kemasan kopi Nucomu Cafe',
            'img_width' => 900,
            'img_height' => 1389,
            'aspect_ratio' => '2/3',
            'bg_color' => '#242424',
            'theme' => 'dark',
            'floaters' => [],
        ],
        [
            'id' => 'fresh-brew',
            'type' => 'product',
            'label' => '02 / DAILY SIGNATURE DRINK',
            'giant_headline' => 'FRESH BREW',
            'giant_headline_class' => 'text-[#1C1C1C]/25',
            'subtext' => null,
            'cta_primary' => [
                'text' => 'Lihat Menu',
                'url' => route('home') . '#menu',
            ],
            'product_image' => 'images/hero/drink.webp',
            'product_name' => 'Signature Iced Coffee Drink with Whole Coffee Beans',
            'img_width' => 900,
            'img_height' => 1157,
            'aspect_ratio' => 'auto',
            'img_class' => 'w-auto max-h-[250px] sm:max-h-[340px] lg:max-h-[410px] xl:max-h-[430px] object-contain relative z-20 hero-float-idle',
            'bg_color' => '#EDEDED',
            'theme' => 'light',
            'has_ambient_glow' => false,
            'floaters' => [
                // 1. Kiri atas (jauh)
                [
                    'src' => 'images/hero/bean-1.png',
                    'wrapper_class' => 'absolute -left-16 sm:-left-28 lg:-left-36 top-4 sm:top-8 w-9 sm:w-12 lg:w-14 z-25 hero-bean-anim-1 drop-shadow-[0_8px_16px_rgba(0,0,0,0.22)]',
                    'img_class' => 'w-full h-auto object-contain transform -rotate-35',
                    'depth' => 24,
                ],
                // 2. Kiri atas (sedang)
                [
                    'src' => 'images/hero/bean-2.png',
                    'wrapper_class' => 'absolute -left-10 sm:-left-16 lg:-left-20 top-12 sm:top-16 w-8 sm:w-10 lg:w-12 z-25 hero-bean-anim-3 drop-shadow-[0_6px_12px_rgba(0,0,0,0.25)]',
                    'img_class' => 'w-full h-auto object-contain transform -rotate-20',
                    'depth' => 18,
                ],
                // 3. Atas kiri gelas
                [
                    'src' => 'images/hero/bean-4.png',
                    'wrapper_class' => 'absolute -left-2 sm:-left-4 -top-6 sm:-top-8 w-6 sm:w-8 lg:w-9 z-30 hero-bean-anim-2 drop-shadow-[0_6px_10px_rgba(0,0,0,0.22)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-45',
                    'depth' => 28,
                ],
                // 4. Atas tengah-kanan melayang tinggi
                [
                    'src' => 'images/hero/bean-5.png',
                    'wrapper_class' => 'absolute left-1/2 translate-x-8 sm:translate-x-12 -top-10 sm:-top-14 w-7 sm:w-9 lg:w-11 z-25 hero-bean-anim-4 drop-shadow-[0_8px_14px_rgba(0,0,0,0.2)]',
                    'img_class' => 'w-full h-auto object-contain transform -rotate-15',
                    'depth' => 22,
                ],
                // 5. Kanan atas
                [
                    'src' => 'images/hero/bean-2.png',
                    'wrapper_class' => 'absolute -right-8 sm:-right-14 lg:-right-18 top-16 sm:top-20 w-9 sm:w-11 lg:w-13 z-25 hero-bean-anim-1 drop-shadow-[0_8px_14px_rgba(0,0,0,0.25)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-35',
                    'depth' => 20,
                ],
                // 6. Kanan jauh memancar keluar
                [
                    'src' => 'images/hero/bean-6.png',
                    'wrapper_class' => 'absolute -right-16 sm:-right-26 lg:-right-34 top-28 sm:top-36 w-8 sm:w-10 lg:w-12 z-25 hero-bean-anim-3 drop-shadow-[0_6px_12px_rgba(0,0,0,0.2)]',
                    'img_class' => 'w-full h-auto object-contain transform -rotate-25',
                    'depth' => 16,
                ],
                // 7. Kanan tengah
                [
                    'src' => 'images/hero/bean-1.png',
                    'wrapper_class' => 'absolute -right-10 sm:-right-16 lg:-right-22 top-1/2 w-7 sm:w-9 lg:w-11 z-25 hero-bean-anim-2 drop-shadow-[0_6px_12px_rgba(0,0,0,0.22)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-60',
                    'depth' => 25,
                ],
                // 8. Kanan bawah
                [
                    'src' => 'images/hero/bean-3.png',
                    'wrapper_class' => 'absolute right-2 sm:right-6 -bottom-3 sm:-bottom-5 w-8 sm:w-10 lg:w-12 z-25 hero-bean-anim-2 drop-shadow-[0_6px_10px_rgba(0,0,0,0.25)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-15',
                    'depth' => 14,
                ],
                // 9. Kanan bawah melayang keluar
                [
                    'src' => 'images/hero/bean-4.png',
                    'wrapper_class' => 'absolute -right-12 sm:-right-20 lg:-right-28 -bottom-10 sm:-bottom-14 w-9 sm:w-12 lg:w-14 z-20 hero-bean-anim-4 drop-shadow-[0_8px_16px_rgba(0,0,0,0.22)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-40',
                    'depth' => 19,
                ],
                // 10. Kiri tengah-bawah
                [
                    'src' => 'images/hero/bean-3.png',
                    'wrapper_class' => 'absolute -left-12 sm:-left-20 lg:-left-26 bottom-24 sm:bottom-32 w-8 sm:w-10 lg:w-12 z-25 hero-bean-anim-1 drop-shadow-[0_8px_14px_rgba(0,0,0,0.22)]',
                    'img_class' => 'w-full h-auto object-contain transform -rotate-50',
                    'depth' => 22,
                ],
                // 11. Kiri bawah
                [
                    'src' => 'images/hero/bean-5.png',
                    'wrapper_class' => 'absolute -left-6 sm:-left-12 lg:-left-16 -bottom-4 sm:-bottom-7 w-7 sm:w-9 lg:w-11 z-25 hero-bean-anim-3 drop-shadow-[0_6px_12px_rgba(0,0,0,0.25)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-20',
                    'depth' => 17,
                ],
                // 12. Depan bokeh (besar di depan kiri bawah)
                [
                    'src' => 'images/hero/bean-6.png',
                    'wrapper_class' => 'absolute -left-20 sm:-left-36 lg:-left-44 bottom-6 sm:bottom-10 w-11 sm:w-14 lg:w-16 z-30 hero-bean-anim-4 drop-shadow-[0_12px_24px_rgba(0,0,0,0.3)] filter blur-[0.6px] opacity-90',
                    'img_class' => 'w-full h-auto object-contain transform -rotate-15',
                    'depth' => 32,
                ],
            ],
            'has_float_rotate' => false,
        ],
        [
            'id' => 'sweet-moment',
            'type' => 'product',
            'label' => '03 / ARTISANAL PASTRY',
            'giant_headline' => 'SWEET MOMENT',
            'giant_headline_class' => 'text-[#1C1C1C]/22',
            'subtext' => null,
            'cta_primary' => [
                'text' => 'Lihat Menu',
                'url' => route('home') . '#menu',
            ],
            'product_image' => 'images/hero/dessert.webp',
            'product_name' => 'Strawberry Basque Cheesecake',
            'img_width' => 730,
            'img_height' => 667,
            'aspect_ratio' => 'auto',
            'img_class' => 'w-[85vw] max-w-[330px] sm:max-w-[440px] lg:max-w-[490px] xl:max-w-[540px] max-h-[260px] sm:max-h-[340px] lg:max-h-[390px] xl:max-h-[420px] object-contain relative z-20 hero-float-rotate',
            'bg_color' => '#FAFAFA',
            'theme' => 'light',
            'floaters' => [],
            'has_float_rotate' => true,
        ],
    ];

    // Di environment lokal tampilkan semua slide
    // Di produksi, hanya render slide yang aset fotonya sudah ada di public/
    $slides = array_filter($rawSlides, function ($slide) {
        if ($slide['type'] === 'welcome') {
            return true;
        }
        if (app()->isLocal()) {
            return true;
        }
        return file_exists(public_path($slide['product_image']));
    });
    $slides = array_values($slides);
    $totalSlides = count($slides);

    $hasActiveHeroBanners = isset($activeBanners) && $activeBanners->count() > 0;
    $firstHeroBannerMode = $hasActiveHeroBanners ? ($activeBanners->first()->layout_mode ?: 'poster') : null;
    $initialHeroBgColor = $hasActiveHeroBanners
        ? ($firstHeroBannerMode === 'overlay' ? '#191919' : '#F9F9F9')
        : ($slides[0]['bg_color'] ?? '#140E0A');
    $initialHeroNavbarTheme = $hasActiveHeroBanners
        ? ($firstHeroBannerMode === 'overlay' ? 'dark' : 'light')
        : ($slides[0]['theme'] ?? 'dark');
@endphp

<div id="hero-navbar-offset"
     class="pt-16 sm:pt-20 transition-colors duration-[800ms]"
     style="background-color: var(--hero-bg-color, {{ $initialHeroBgColor }}); transition: background-color 0.8s cubic-bezier(0.16, 1, 0.3, 1);">
<section id="home" 
         data-navbar-theme="{{ $initialHeroNavbarTheme }}"
         class="relative min-h-[560px] sm:min-h-[580px] lg:min-h-[600px] flex items-center justify-center overflow-hidden border-b border-light-gray/60 select-none group/hero"
         style="background-color: {{ $initialHeroBgColor }}; transition: background-color 0.8s ease;">

    <!-- Soft feathered ambient glow for warmth and depth -->
    <div class="absolute inset-0 pointer-events-none z-1" 
         style="background: radial-gradient(ellipse 70% 70% at 50% 50%, rgba(255, 255, 255, 0.45) 0%, rgba(255, 255, 255, 0.15) 60%, transparent 100%);">
    </div>

    @if(isset($activeBanners) && $activeBanners->count() > 0)
        <!-- =========================================================================
             1. DESKTOP ONLY: PROMOTIONAL BANNERS (SCREEN WIDTH >= 768px)
             Di HP banner TIDAK ditampilkan dan media (gambar/video) TIDAK diunduh.
             ========================================================================= -->
        <div class="hidden md:block w-full h-full absolute inset-0" id="desktop-hero-banners-wrapper">
            @foreach($activeBanners as $banner)
                @php
                    $bannerMode = $banner->layout_mode ?: 'poster';
                    $bannerBgColor = $bannerMode === 'overlay' ? '#191919' : '#F9F9F9';
                    $bannerTheme = $bannerMode === 'overlay' ? 'dark' : 'light';
                @endphp
                <div class="hero-slide-item hero-banner-slide absolute inset-0 w-full h-full flex items-center justify-center {{ $loop->first ? 'active z-10' : 'opacity-0 pointer-events-none z-0' }}"
                     data-slide-index="{{ $loop->index }}"
                     data-layout-mode="{{ $bannerMode }}"
                     data-theme="{{ $bannerTheme }}"
                     data-bg-color="{{ $bannerBgColor }}">
                    
                    @if(($banner->layout_mode ?: 'poster') === 'poster')
                        <!-- -----------------------------------------------------------------
                             MODE 1: POSTER (DEFAULT) - Full Edge-to-Edge Desktop
                             Seluruh poster menjadi link jika button_link diisi
                             ----------------------------------------------------------------- -->
                        @if(!empty($banner->button_link))
                            <a href="{{ $banner->button_link }}" class="group/poster relative w-full h-full flex items-center justify-center overflow-hidden block cursor-pointer" title="{{ $banner->title }}">
                        @else
                            <div class="relative w-full h-full flex items-center justify-center overflow-hidden">
                        @endif
                            @if($banner->media_type === 'video')
                                <video class="w-full h-full object-cover desktop-banner-video"
                                       preload="none" muted loop playsinline
                                       data-desktop-src="{{ $banner->media_url }}">
                                    <source data-desktop-src="{{ $banner->media_url }}" type="video/mp4">
                                </video>
                            @else
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1920 800'%3E%3C/svg%3E"
                                     data-desktop-src="{{ $banner->media_url }}"
                                     alt="{{ $banner->title }}"
                                     class="w-full h-full object-cover desktop-banner-img transition-transform duration-700 ease-out group-hover/poster:scale-[1.015]">
                            @endif

                        @if(!empty($banner->button_link))
                            </a>
                        @else
                            </div>
                        @endif

                    @elseif($banner->layout_mode === 'overlay')
                        <!-- -----------------------------------------------------------------
                             MODE 2: OVERLAY - Background Image/Video with Text on Top
                             ----------------------------------------------------------------- -->
                        <div class="relative w-full h-full flex items-center justify-center overflow-hidden">
                            <!-- Background Media -->
                            <div class="absolute inset-0 z-0">
                                @if($banner->media_type === 'video')
                                    <video class="w-full h-full object-cover desktop-banner-video"
                                           preload="none" muted loop playsinline
                                           data-desktop-src="{{ $banner->media_url }}">
                                        <source data-desktop-src="{{ $banner->media_url }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1920 800'%3E%3C/svg%3E"
                                         data-desktop-src="{{ $banner->media_url }}"
                                         alt="{{ $banner->title }}"
                                         class="w-full h-full object-cover desktop-banner-img">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/35 backdrop-blur-[0.5px]"></div>
                            </div>

                            <!-- Text Content on Top -->
                            <div class="relative z-20 max-w-6xl mx-auto px-6 sm:px-10 lg:px-14 py-12 lg:py-16 w-full text-left hero-text-content">
                                @if($banner->subtitle)
                                    <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-stone-300 mb-1.5 leading-tight">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.08] mb-3 tracking-tight drop-shadow-sm max-w-2xl" style="font-variant-numeric: lining-nums;">
                                    {{ $banner->title }}
                                </h1>

                                <div class="inline-flex items-center space-x-2 mb-3 bg-white/15 backdrop-blur-md px-3.5 py-1 rounded-full border border-white/25 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#555555]"></span>
                                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-white">
                                        Nucomu Promo • Tulungagung
                                    </span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#555555]"></span>
                                </div>

                                @if($banner->description)
                                    <p class="font-body text-xs sm:text-sm text-stone-200 max-w-lg leading-relaxed mb-6 font-normal drop-shadow-xs">
                                        {{ $banner->description }}
                                    </p>
                                @endif

                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ $banner->button_link ?: '#menu' }}" 
                                       class="inline-flex items-center justify-center bg-[#1F1F1F] hover:bg-[#333333] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-md transition-all duration-200 active:scale-95 cursor-pointer">
                                        {{ $banner->button_text ?: 'PESAN SEKARANG' }}
                                    </a>
                                    <a href="#menu" 
                                       class="inline-flex items-center justify-center bg-white/20 hover:bg-white text-white hover:text-[#1F1F1F] border border-white/40 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95 backdrop-blur-xs">
                                        DAFTAR MENU
                                    </a>
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- -----------------------------------------------------------------
                             MODE 3: TERBAGI (SPLIT) - Left Text + Right Media Card
                             Kompatibilitas penuh dengan desain asli (Banner 1)
                             ----------------------------------------------------------------- -->
                        <div class="relative z-20 max-w-6xl mx-auto px-5 sm:px-8 lg:px-12 py-10 sm:py-12 lg:py-14 w-full">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                                
                                <!-- Text Column (Left) -->
                                <div class="hero-text-content lg:col-span-7 text-left order-2 lg:order-1">
                                    @if($banner->subtitle)
                                        <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[#555555] mb-1.5 leading-tight">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif

                                    <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#1F1F1F] leading-[1.08] mb-3 tracking-tight" style="font-variant-numeric: lining-nums;">
                                        {{ $banner->title }}
                                    </h1>

                                    <div class="inline-flex items-center space-x-2 mb-3 bg-white/70 backdrop-blur-sm px-3.5 py-1 rounded-full border border-neutral-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#555555]"></span>
                                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#555555]">
                                            Nucomu Promo • Tulungagung
                                        </span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#555555]"></span>
                                    </div>

                                    @if($banner->description)
                                        <p class="font-body text-xs sm:text-sm text-[#555555] max-w-lg leading-relaxed mb-6 font-normal">
                                            {{ $banner->description }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="{{ $banner->button_link ?: '#menu' }}" 
                                           class="inline-flex items-center justify-center bg-[#1F1F1F] hover:bg-[#333333] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-md transition-all duration-200 active:scale-95 cursor-pointer">
                                            {{ $banner->button_text ?: 'PESAN SEKARANG' }}
                                        </a>
                                        <a href="#menu" 
                                           class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#1F1F1F] border border-neutral-300 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                                            DAFTAR MENU
                                        </a>
                                    </div>
                                </div>

                                <!-- Media Column (Right) -->
                                <div class="hero-video-container lg:col-span-5 flex justify-center lg:justify-end order-1 lg:order-2">
                                    <div class="relative w-full max-w-xs sm:max-w-sm lg:max-w-md aspect-square overflow-hidden bg-transparent group rounded-[24px] shadow-card">
                                        @if($banner->media_type === 'video')
                                            <video class="hero-slide-video w-full h-full object-cover rounded-[24px] desktop-banner-video"
                                                   preload="none" muted loop playsinline
                                                   data-desktop-src="{{ $banner->media_url }}">
                                                <source data-desktop-src="{{ $banner->media_url }}" type="video/mp4">
                                            </video>
                                        @else
                                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 800'%3E%3C/svg%3E"
                                                 data-desktop-src="{{ $banner->media_url }}"
                                                 alt="{{ $banner->title }}"
                                                 class="w-full h-full object-cover rounded-[24px] desktop-banner-img">
                                        @endif
                                        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-[#252525] border border-white/60 shadow-sm flex items-center space-x-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#555555]"></span>
                                            <span>{{ $banner->subtitle ?: 'Special Banner' }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        <!-- =========================================================================
             2. MOBILE ONLY (SCREEN WIDTH < 768px): HERO BAWAAN WEB
             Di HP banner tidak ditampilkan sama sekali dan tidak mengunduh berkas banner.
             ========================================================================= -->
        <div class="block md:hidden w-full h-full absolute inset-0" id="mobile-hero-fallback-wrapper">
            @include('home.sections.hero-default-slides')
        </div>

    @else
        <!-- =========================================================================
             TIDAK ADA BANNER AKTIF: TAMPILKAN HERO BAWAAN KAFE (SEMUA LAYAR)
             ========================================================================= -->
        <div class="w-full h-full absolute inset-0" id="default-hero-slides-wrapper">
            @foreach($slides as $index => $slide)
        <div id="hero-slide-{{ $index }}"
             class="hero-slide-item absolute inset-0 w-full h-full flex items-center justify-center {{ $index === 0 ? 'active z-10 opacity-100' : 'opacity-0 pointer-events-none z-0' }}"
             data-slide-index="{{ $index }}"
             data-bg-color="{{ $slide['bg_color'] }}"
             data-theme="{{ $slide['theme'] }}"
             role="group"
             aria-roledescription="slide"
             aria-label="{{ $index + 1 }} dari {{ $totalSlides }}: {{ $slide['headline_line1'] ?? $slide['giant_headline'] }}"
             aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
             {!! $index !== 0 ? 'inert' : '' !!}>

            @if($slide['type'] === 'welcome')
                <!-- -----------------------------------------------------------------
                     SLIDE 0: SAMBUTAN (FOTO LATTE FULL-BLEED + UAP + KEN BURNS)
                     ----------------------------------------------------------------- -->
                <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none">
                    <picture>
                        @if(file_exists(public_path($slide['image_webp'])))
                            <source srcset="{{ asset($slide['image_webp']) }}" type="image/webp">
                        @endif
                        <img src="{{ asset($slide['image_jpg']) }}" 
                             alt="Nucomu Cafe Specialty Coffee Latte" 
                             width="1672" 
                             height="941" 
                             fetchpriority="high"
                             decoding="async"
                             class="hero-welcome-photo absolute inset-0 w-full h-full object-cover object-[72%_50%] max-md:object-[70%_40%] hero-ken-burns">
                    </picture>

                    <!-- Overlay Gradien Desktop (Kiri gelap ke transparan kanan) & Mobile (Bawah ke atas) -->
                    <div class="absolute inset-0 bg-gradient-to-r from-[#140E0A]/90 via-[#140E0A]/60 to-transparent max-md:bg-gradient-to-t max-md:from-[#140E0A]/95 max-md:via-[#140E0A]/70 max-md:to-transparent pointer-events-none z-10" aria-hidden="true"></div>

                    <!-- Vinyet Halus Atas & Bawah -->
                    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/50 pointer-events-none z-10" aria-hidden="true"></div>

                    <!-- Efek Uap Tipis Responsif (Hanya aktif di desktop >= 1024px pada titik latte 72% x, 38% y) -->
                    <div class="hero-steam-container hidden lg:block absolute pointer-events-none z-15"
                         style="left: 72%; top: 38%; transform: translate(-50%, -100%);"
                         aria-hidden="true">
                        <div class="hero-steam-wisp hero-steam-1"></div>
                        <div class="hero-steam-wisp hero-steam-2"></div>
                        <div class="hero-steam-wisp hero-steam-3"></div>
                    </div>
                </div>

                <!-- Konten Teks Sambutan (Rata Kiri, Vertikal Tengah) -->
                <div class="relative z-20 max-w-7xl mx-auto px-5 sm:px-8 lg:px-14 py-12 sm:py-16 w-full flex items-center min-h-[560px] sm:min-h-[580px] lg:min-h-[600px]">
                    <div class="hero-text-content text-left max-w-xl max-md:mt-auto max-md:pb-14">
                        <!-- Label Kecil Monokrom -->
                        <p class="font-body text-xs sm:text-sm font-semibold tracking-[0.25em] text-white/70 uppercase mb-3 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>
                            <span>{{ $slide['label'] }}</span>
                        </p>

                        <!-- Headline Besar Manrope Semibold -->
                        <h1 class="font-body text-3xl sm:text-5xl lg:text-6xl font-semibold text-white leading-[1.12] tracking-tight mb-4">
                            {{ $slide['headline_line1'] }}<br>
                            {{ $slide['headline_line2'] }}
                        </h1>

                        <!-- Garis Putih Tipis Pendek (~48px) -->
                        <div class="w-12 h-0.5 bg-white/60 mb-4 sm:mb-5" aria-hidden="true"></div>

                        <!-- Paragraf Sambutan Maks 46ch -->
                        <p class="font-body text-xs sm:text-sm lg:text-base text-white/75 max-w-[46ch] leading-relaxed mb-7 sm:mb-8 font-normal">
                            {{ $slide['paragraph'] }}
                        </p>

                        <!-- Tombol Aksi (Utama Putih, Kedua Outline Putih Tipis) -->
                        <div class="flex flex-wrap items-center gap-3.5 max-sm:w-full">
                            <a href="{{ $slide['cta_primary']['url'] }}" 
                               class="inline-flex items-center justify-center bg-white hover:bg-neutral-100 text-[#242424] text-xs sm:text-sm font-semibold uppercase tracking-wider px-7 py-3.5 rounded-full shadow-lg shadow-black/25 transition-all duration-200 active:scale-95 group/btn max-sm:w-full gap-2 cursor-pointer">
                                <span>{{ $slide['cta_primary']['text'] }}</span>
                                <span class="material-symbols-outlined text-base transition-transform duration-200 group-hover/btn:translate-x-1">arrow_forward</span>
                            </a>
                            <a href="{{ $slide['cta_secondary']['url'] }}" 
                               class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 text-white border border-white/60 hover:border-white text-xs sm:text-sm font-semibold uppercase tracking-wider px-6 py-3.5 rounded-full backdrop-blur-sm transition-all duration-200 active:scale-95 max-sm:w-full cursor-pointer">
                                <span>{{ $slide['cta_secondary']['text'] }}</span>
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($slide['id'] === 'fresh-beans')
                <!-- -----------------------------------------------------------------
                     SLIDE 1: SPECIALTY ROASTERY - FRESH BEANS (DARK EDITORIAL POSTER + 3D ORBIT)
                     ----------------------------------------------------------------- -->
                <div class="relative w-full h-full flex items-center justify-center overflow-hidden hero-coffee-pack-canvas select-none">

                    <!-- Warm Amber Spotlight Glow behind center coffee pack -->
                    <div class="hero-glow-amber-center absolute inset-0 pointer-events-none z-10" aria-hidden="true"></div>

                    <!-- 1. Giant Headline Behind Product: FRESH BEANS (z-10) -->
                    <div class="hero-giant-headline absolute inset-0 flex items-center justify-center z-10 pointer-events-none select-none px-4 transition-transform duration-300">
                        <span class="font-body font-black uppercase text-[clamp(4.2rem,13.8vw,11.5rem)] leading-none tracking-tighter text-center whitespace-nowrap text-white/18 select-none">
                            FRESH BEANS
                        </span>
                    </div>

                    <!-- 2. Golden Swoosh Trajectory Curve Lines (z-15) -->
                    <svg class="hero-golden-swoosh absolute inset-0 w-full h-full pointer-events-none z-15 select-none" viewBox="0 0 1440 600" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="none">
                        <!-- Primary Golden Swoosh Line -->
                        <path d="M -80 470 C 250 465, 480 445, 740 395 C 1000 345, 1220 230, 1520 120" 
                              stroke="url(#goldSwooshGrad1)" stroke-width="2.2" stroke-linecap="round" />
                        
                        <!-- Secondary Parallel Golden Swoosh Line -->
                        <path d="M -80 500 C 270 495, 510 475, 770 425 C 1030 375, 1250 260, 1520 155" 
                              stroke="url(#goldSwooshGrad2)" stroke-width="1.5" opacity="0.7" stroke-linecap="round" />

                        <!-- Upper Delicate Filament Curve -->
                        <path d="M 520 380 C 820 310, 1140 210, 1520 90" 
                              stroke="url(#goldSwooshGrad1)" stroke-width="1.2" opacity="0.5" stroke-linecap="round" />

                        <defs>
                            <linearGradient id="goldSwooshGrad1" x1="0" y1="0.8" x2="1" y2="0.2">
                                <stop offset="0%" stop-color="#C8A97E" stop-opacity="0" />
                                <stop offset="15%" stop-color="#E5C595" stop-opacity="0.85" />
                                <stop offset="50%" stop-color="#D4AF37" stop-opacity="0.9" />
                                <stop offset="85%" stop-color="#E5C595" stop-opacity="0.8" />
                                <stop offset="100%" stop-color="#A88555" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="goldSwooshGrad2" x1="0" y1="0.8" x2="1" y2="0.2">
                                <stop offset="0%" stop-color="#C8A97E" stop-opacity="0" />
                                <stop offset="20%" stop-color="#E5C595" stop-opacity="0.6" />
                                <stop offset="60%" stop-color="#D4AF37" stop-opacity="0.75" />
                                <stop offset="100%" stop-color="#A88555" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <!-- 3. Central Coffee Pack with 3D Float + Orbiting Beans (z-20) -->
                    <div class="hero-vortex-scene relative z-20 flex flex-col items-center justify-center pointer-events-none">
                        <div class="relative flex items-center justify-center">
                            
                            <!-- Central Coffee Pack with 3D Float (z-20) -->
                            <img src="{{ asset($slide['product_image']) }}"
                                 alt="{{ $slide['product_name'] }}"
                                 width="{{ $slide['img_width'] ?? 900 }}"
                                 height="{{ $slide['img_height'] ?? 1389 }}"
                                 loading="eager"
                                 decoding="async"
                                 class="w-auto h-auto max-h-[220px] sm:max-h-[290px] md:max-h-[340px] lg:max-h-[370px] xl:max-h-[395px] object-contain hero-pack-3d relative z-20 select-none">
                            <div class="hero-pack-shadow-warm" aria-hidden="true"></div>

                            <!-- 3D Orbiting Coffee Beans Container with Proportional Responsive Scaling -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none max-md:scale-[0.68] max-sm:scale-[0.46] origin-center" style="transform-style: preserve-3d;">
                                @foreach($vortexBeans as $vIdx => $vBean)
                                    <div class="vortex-bean-node {{ $vBean['extraClass'] ?? '' }}"
                                         data-depth="{{ $vBean['depth'] }}"
                                         style="--target-x: {{ $vBean['x'] }}px; --target-y: {{ $vBean['y'] }}px; --target-z: {{ $vBean['z'] }}px; --target-rot: {{ $vBean['rot'] }}deg; --target-opacity: {{ $vBean['opacity'] }}; transform: translate3d({{ $vBean['x'] }}px, {{ $vBean['y'] }}px, {{ $vBean['z'] }}px) rotate({{ $vBean['rot'] }}deg); z-index: {{ $vBean['zIndex'] }}; opacity: {{ $vBean['opacity'] }}; width: {{ $vBean['w'] }}px; animation-delay: {{ $vBean['entranceDelay'] }}s;"
                                         aria-hidden="true">
                                        <img src="{{ asset($vBean['src']) }}"
                                             alt=""
                                             loading="eager"
                                             decoding="async"
                                             class="vortex-bean-img hero-bean-orbit-{{ $vBean['animType'] }}"
                                             style="{{ !empty($vBean['blur']) ? 'filter: blur('.$vBean['blur'].'px) drop-shadow(0 12px 24px rgba(0,0,0,0.65));' : 'filter: drop-shadow(0 8px 18px rgba(0,0,0,0.55));' }} animation-duration: {{ $vBean['animDur'] }}s; animation-delay: {{ $vBean['animDelay'] }}s;">
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <!-- 4. Left Product Information & CTA in Bottom Left (z-30) -->
                    <div class="hero-product-info absolute max-sm:bottom-[80px] bottom-10 lg:bottom-14 left-5 sm:left-8 lg:left-14 z-30 text-left max-w-xs sm:max-w-sm pointer-events-auto">
                        <!-- Label with baseline divider -->
                        <div class="flex items-center gap-2.5 sm:gap-3 mb-2.5 sm:mb-3.5">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-[0.22em] sm:tracking-[0.25em] text-white/70">
                                {{ $slide['label'] }}
                            </p>
                            <span class="hidden sm:block w-16 lg:w-24 h-[1px] bg-white/20"></span>
                        </div>

                        <!-- CTA Button (White Pill with Right Arrow) -->
                        <div>
                            <a href="{{ $slide['cta_primary']['url'] }}" 
                               class="inline-flex items-center gap-2 font-semibold text-xs sm:text-sm uppercase tracking-wider px-5 sm:px-7 py-2.5 sm:py-3.5 rounded-full bg-white text-[#1C1C1C] hover:bg-neutral-100 transition-all duration-200 active:scale-95 shadow-lg group/btn cursor-pointer">
                                <span>{{ $slide['cta_primary']['text'] }}</span>
                                <span class="material-symbols-outlined text-base transition-transform duration-200 group-hover/btn:translate-x-1" aria-hidden="true">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>

            @else
                <!-- -----------------------------------------------------------------
                     SLIDES 2-3: PRODUK DI DEPAN HEADLINE RAKSASA (EDITORIAL POSTER)
                     ----------------------------------------------------------------- -->
                <div class="relative w-full h-full flex items-center justify-center overflow-hidden">
                        <!-- Ambient Warm Glow (Other Slides) -->
                        @if(!empty($slide['has_ambient_glow']))
                            <div class="hero-glow-amber absolute inset-0 pointer-events-none z-10" aria-hidden="true"></div>
                        @endif

                        <!-- 1. Headline Raksasa (Di Belakang Produk, z-10) -->
                        <div class="hero-giant-headline absolute inset-0 flex items-center justify-center z-10 pointer-events-none select-none px-4 transition-transform duration-300">
                            <span class="font-body font-black uppercase text-[clamp(3.8rem,14vw,11.5rem)] leading-none tracking-tighter text-center whitespace-nowrap {{ $slide['giant_headline_class'] ?? ($slide['theme'] === 'dark' ? 'text-white/20' : 'text-[#1C1C1C]/22') }}">
                                {{ $slide['giant_headline'] }}
                            </span>
                        </div>

                        <!-- 2 & 3. Produk di Tengah dengan Floating Elements di Sekelilingnya (z-20) -->
                        <div class="hero-product-layer relative z-20 flex flex-col items-center justify-center px-4 transition-transform duration-300 max-md:mb-24 max-md:-mt-6">
                            @if(file_exists(public_path($slide['product_image'])))
                                <div class="relative flex items-center justify-center {{ $slide['id'] === 'sweet-moment' ? 'w-[85vw] max-w-[330px] sm:max-w-[440px] lg:max-w-[490px] xl:max-w-[540px]' : '' }}">
                                    
                                    <!-- Floating Elements (Slide 2) -->
                                    @if(!empty($slide['floaters']))
                                        @foreach($slide['floaters'] as $fIdx => $floater)
                                            @if(file_exists(public_path($floater['src'])))
                                                <div class="hero-floater {{ $floater['wrapper_class'] ?? '' }} pointer-events-none select-none"
                                                     data-depth="{{ $floater['depth'] ?? 18 }}"
                                                     aria-hidden="true">
                                                    <img src="{{ asset($floater['src']) }}" 
                                                         alt="" 
                                                         loading="eager"
                                                         decoding="async"
                                                         class="{{ $floater['img_class'] ?? 'w-full h-auto object-contain' }}">
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                    <img src="{{ asset($slide['product_image']) }}"
                                         alt="{{ $slide['product_name'] }}"
                                         width="{{ $slide['img_width'] ?? 420 }}"
                                         height="{{ $slide['img_height'] ?? 420 }}"
                                         loading="lazy"
                                         decoding="async"
                                         class="{{ $slide['img_class'] ?? 'w-full h-auto object-contain relative z-20 hero-float-idle' }}">

                                    @if($slide['id'] === 'sweet-moment')
                                        <!-- Animasi Tetesan Slay (Strawberry Jam Drip) yang Menetes Jatuh -->
                                        <div class="hero-jam-drip-wrapper absolute pointer-events-none select-none z-25"
                                             style="left: 31%; top: 60%; width: 22px; height: 32px;"
                                             aria-hidden="true">
                                            <!-- Tetesan Utama Selai Stroberi Berkilap -->
                                            <svg class="hero-jam-drop-main w-full h-full" viewBox="0 0 24 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <defs>
                                                    <radialGradient id="jamGradHero" cx="35%" cy="30%" r="65%">
                                                        <stop offset="0%" stop-color="#FF3848" />
                                                        <stop offset="40%" stop-color="#D11224" />
                                                        <stop offset="85%" stop-color="#880816" />
                                                        <stop offset="100%" stop-color="#55030D" />
                                                    </radialGradient>
                                                    <linearGradient id="jamGlossHero" x1="0" y1="0" x2="1" y2="1">
                                                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.85" />
                                                        <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0" />
                                                    </linearGradient>
                                                </defs>
                                                <path d="M12 0 C12 4, 2 15, 2 23 C2 29.5 6.5 35 12 35 C17.5 35 22 29.5 22 23 C22 15, 12 4, 12 0 Z" fill="url(#jamGradHero)" filter="drop-shadow(0 3px 6px rgba(130, 10, 20, 0.45))" />
                                                <ellipse cx="8" cy="18" rx="2" ry="5.5" transform="rotate(-18 8 18)" fill="url(#jamGlossHero)" />
                                                <circle cx="14" cy="27" r="1.3" fill="#FFFFFF" opacity="0.5" />
                                            </svg>

                                            <!-- Tetesan Tambahan Kecil (Trailing Micro Drip) -->
                                            <div class="hero-jam-drop-trail absolute left-1/2 -translate-x-1/2 -top-2 w-2 h-2.5 rounded-full bg-[#C51224] opacity-0" aria-hidden="true"></div>
                                        </div>
                                    @endif

                                    <div class="hero-product-shadow {{ $slide['id'] === 'sweet-moment' ? 'hero-product-shadow-wide' : '' }} {{ $slide['theme'] === 'dark' ? 'opacity-40' : 'opacity-25' }}" aria-hidden="true"></div>
                                </div>
                            @endif
                        </div>

                    <!-- 4. Informasi Produk & CTA di Kiri Bawah (z-30) -->
                    <div class="hero-product-info absolute max-sm:bottom-[88px] bottom-10 lg:bottom-14 left-5 sm:left-8 lg:left-14 z-30 text-left max-w-xs sm:max-w-sm">
                        <p class="text-[11px] sm:text-xs font-semibold uppercase tracking-[0.25em] {{ $slide['theme'] === 'dark' ? 'text-white/70' : 'text-[#666666]' }}">
                            {{ $slide['label'] }}
                        </p>
                        @if(!empty($slide['subtext']))
                            <p class="text-xs sm:text-sm font-medium mt-1 {{ $slide['theme'] === 'dark' ? 'text-white/90' : 'text-[#242424]' }}">
                                {{ $slide['subtext'] }}
                            </p>
                        @endif
                        <div class="mt-3.5">
                            <a href="{{ $slide['cta_primary']['url'] }}" 
                               class="inline-flex items-center gap-2 font-semibold text-xs sm:text-sm uppercase tracking-wider px-6 sm:px-7 py-3 rounded-full transition-all duration-200 active:scale-95 shadow-md group/btn cursor-pointer {{ $slide['theme'] === 'dark' ? 'bg-white text-[#242424] hover:bg-neutral-100' : 'bg-[#242424] text-white hover:bg-black' }}">
                                <span>{{ $slide['cta_primary']['text'] }}</span>
                                <span class="material-symbols-outlined text-base transition-transform duration-200 group-hover/btn:translate-x-1">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    @endforeach

    <!-- =========================================================================
         CAROUSEL CONTROLS (ARROWS, COUNTER & DOTS, PAUSE BUTTON)
         Hanya dirender bila jumlah slide aktif > 1
         ========================================================================= -->
        </div>
    @endif

    <!-- =========================================================================
         CAROUSEL CONTROLS: ARROWS & DOTS
         ========================================================================= -->
    @if(isset($activeBanners) && $activeBanners->count() > 0)
        <!-- Desktop Nav Arrows (Hanya jika banner aktif > 1) -->
        @if($activeBanners->count() > 1)
            <button type="button" 
                    onclick="prevHeroSlide()" 
                    class="hero-nav-arrow hero-prev-arrow hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-white/90 hover:bg-white text-neutral-900 border border-stone-300/80 backdrop-blur-md absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 shadow-[0_2px_10px_rgba(0,0,0,0.12)] cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]"
                    aria-label="Slide Sebelumnya">
                <span class="material-symbols-outlined text-xl">chevron_left</span>
            </button>
            <button type="button" 
                    onclick="nextHeroSlide()" 
                    class="hero-nav-arrow hero-next-arrow hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-white/90 hover:bg-white text-neutral-900 border border-stone-300/80 backdrop-blur-md absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 shadow-[0_2px_10px_rgba(0,0,0,0.12)] cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]"
                    aria-label="Slide Berikutnya">
                <span class="material-symbols-outlined text-xl">chevron_right</span>
            </button>
        @endif

        <!-- Desktop Indicator Dots -->
        @if($activeBanners->count() > 1)
            <div class="hero-controls-bar hidden md:flex absolute bottom-4 left-1/2 -translate-x-1/2 z-30 items-center space-x-2 bg-black/45 hover:bg-black/60 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20 shadow-md text-white select-none" id="desktop-hero-dots">
                @foreach($activeBanners as $b)
                    <button type="button" onclick="goToHeroSlide({{ $loop->index }})" class="hero-dot-btn {{ $loop->first ? 'w-6 bg-[#1F1F1F]' : 'w-2 bg-neutral-600/40 hover:bg-neutral-800' }} h-1.5 rounded-full transition-all duration-300" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>
        @endif

        <!-- Mobile Indicator Dots (4 Slide Hero Bawaan) -->
        <div class="hero-controls-bar flex md:hidden absolute bottom-4 left-1/2 -translate-x-1/2 z-30 items-center space-x-2 bg-black/45 hover:bg-black/60 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20 shadow-md text-white select-none" id="mobile-hero-dots">
            <button type="button" onclick="goToHeroSlide(0)" class="hero-dot-btn w-6 h-1.5 rounded-full bg-[#1F1F1F] transition-all duration-300" aria-label="Slide 1"></button>
            <button type="button" onclick="goToHeroSlide(1)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 2"></button>
            <button type="button" onclick="goToHeroSlide(2)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 3"></button>
            <button type="button" onclick="goToHeroSlide(3)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 4"></button>
        </div>
    @else
        @if($totalSlides > 1)
        <!-- Left Carousel Arrow -->
        <button type="button" 
                onclick="prevHeroSlide()" 
                class="hero-nav-arrow hero-prev-arrow hidden sm:flex items-center justify-center w-10 h-10 lg:w-11 lg:h-11 rounded-full bg-white/90 hover:bg-white text-[#1C1C1C] border border-stone-300/80 backdrop-blur-md shadow-[0_2px_10px_rgba(0,0,0,0.12)] absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]"
                aria-label="Slide Sebelumnya">
            <span class="material-symbols-outlined text-xl">chevron_left</span>
        </button>

        <!-- Right Carousel Arrow -->
        <button type="button" 
                onclick="nextHeroSlide()" 
                class="hero-nav-arrow hero-next-arrow hidden sm:flex items-center justify-center w-10 h-10 lg:w-11 lg:h-11 rounded-full bg-white/90 hover:bg-white text-[#1C1C1C] border border-stone-300/80 backdrop-blur-md shadow-[0_2px_10px_rgba(0,0,0,0.12)] absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]"
                aria-label="Slide Berikutnya">
            <span class="material-symbols-outlined text-xl">chevron_right</span>
        </button>

        <!-- Bottom Controls Bar: Dots, Pause/Play (Clean Balanced Pill without Counter) -->
        <div class="hero-controls-bar absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2.5 sm:gap-3 bg-black/45 hover:bg-black/60 backdrop-blur-md pl-3.5 pr-2.5 py-1.5 sm:py-2 rounded-full border border-white/20 text-white select-none shadow-md">
            <!-- Slide Indicator Dots -->
            <div class="flex items-center gap-1.5" role="tablist" aria-label="Indikator Slide Hero">
                @foreach($slides as $idx => $s)
                    <button type="button" 
                            onclick="goToHeroSlide({{ $idx }})" 
                            class="hero-dot-btn h-1.5 rounded-full transition-all duration-300 {{ $idx === 0 ? 'w-6 bg-white' : 'w-2 bg-white/35 hover:bg-white/70' }}" 
                            role="tab"
                            id="hero-tab-{{ $idx }}"
                            aria-controls="hero-slide-{{ $idx }}"
                            aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                            aria-label="Ke slide {{ $idx + 1 }}"></button>
                @endforeach
            </div>

            <!-- Divider -->
            <span class="w-px h-3.5 bg-white/25" aria-hidden="true"></span>

            <!-- Pause / Play Button -->
            <button type="button"
                    id="hero-pause-btn"
                    onclick="toggleHeroAutoplay()"
                    class="w-6 h-6 flex items-center justify-center text-white/80 hover:text-white transition-colors focus:outline-none focus-visible:ring-1 focus-visible:ring-white/50 rounded-full cursor-pointer"
                    aria-label="Jeda tayangan slide otomatis"
                    title="Jeda / Putar tayangan otomatis">
                <span id="hero-pause-icon" class="material-symbols-outlined text-base">pause</span>
            </button>
        </div>
    @endif
@endif

</section>
</div>
