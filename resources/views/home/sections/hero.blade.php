<!-- SECTION 1: HOME / HERO (3 SLIDES: PRODUCT IN FRONT OF GIANT HEADLINE) -->
@php
    $activeBanners = $activeBanners ?? ($banners ?? collect());
    $isDarkTheme = ($siteTheme ?? \App\Models\Setting::getSiteTheme()) === 'dark';

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

    // 3D Orbiting Strawberries for Slide 3 (Sweet Moment / Artisanal Pastry) matching 3D vortex orbit design
    $vortexStrawberries = [
        // Bokeh foreground (large & depth-of-field blurred)
        ['src' => 'images/hero/strawberry-4.webp', 'x' => -340, 'y' => 170, 'z' => 95, 'w' => 140, 'rot' => -22, 'blur' => 3.8, 'opacity' => 0.95, 'zIndex' => 35, 'animType' => 1, 'animDur' => 6.4, 'animDelay' => 0.0, 'entranceDelay' => 0.15, 'depth' => 46, 'extraClass' => 'max-sm:hidden'],
        ['src' => 'images/hero/strawberry-1.webp', 'x' => 320, 'y' => 145, 'z' => 90, 'w' => 135, 'rot' => 28, 'blur' => 3.4, 'opacity' => 0.95, 'zIndex' => 35, 'animType' => 2, 'animDur' => 6.0, 'animDelay' => -1.2, 'entranceDelay' => 0.25, 'depth' => 42, 'extraClass' => 'max-sm:hidden'],
        ['src' => 'images/hero/strawberry-5.webp', 'x' => 330, 'y' => -160, 'z' => 80, 'w' => 100, 'rot' => 32, 'blur' => 2.6, 'opacity' => 0.92, 'zIndex' => 35, 'animType' => 3, 'animDur' => 5.4, 'animDelay' => -2.0, 'entranceDelay' => 0.35, 'depth' => 36, 'extraClass' => 'max-sm:hidden'],
        ['src' => 'images/hero/strawberry-3.webp', 'x' => 180, 'y' => 215, 'z' => 75, 'w' => 88, 'rot' => 40, 'blur' => 2.0, 'opacity' => 0.90, 'zIndex' => 32, 'animType' => 4, 'animDur' => 5.8, 'animDelay' => -0.8, 'entranceDelay' => 0.2, 'depth' => 32, 'extraClass' => 'max-sm:hidden'],

        // Midground sharp swirl along cake base & spiraling curving up-right
        ['src' => 'images/hero/strawberry-4.webp', 'x' => -160, 'y' => 120, 'z' => 35, 'w' => 58, 'rot' => -18, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 25, 'animType' => 1, 'animDur' => 5.0, 'animDelay' => -0.5, 'entranceDelay' => 0.1, 'depth' => 22, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-3.webp', 'x' => -80, 'y' => 150, 'z' => 45, 'w' => 62, 'rot' => 22, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 26, 'animType' => 2, 'animDur' => 5.3, 'animDelay' => -1.0, 'entranceDelay' => 0.15, 'depth' => 24, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-1.webp', 'x' => 15, 'y' => 155, 'z' => 50, 'w' => 62, 'rot' => -15, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 26, 'animType' => 3, 'animDur' => 4.8, 'animDelay' => -1.5, 'entranceDelay' => 0.2, 'depth' => 25, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-5.webp', 'x' => 100, 'y' => 135, 'z' => 45, 'w' => 56, 'rot' => 32, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 25, 'animType' => 4, 'animDur' => 5.1, 'animDelay' => -2.0, 'entranceDelay' => 0.25, 'depth' => 23, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-2.webp', 'x' => 175, 'y' => 105, 'z' => 35, 'w' => 54, 'rot' => 24, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 24, 'animType' => 1, 'animDur' => 4.9, 'animDelay' => -2.5, 'entranceDelay' => 0.3, 'depth' => 20, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-3.webp', 'x' => 210, 'y' => 50, 'z' => 25, 'w' => 50, 'rot' => -20, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 24, 'animType' => 2, 'animDur' => 5.2, 'animDelay' => -3.0, 'entranceDelay' => 0.35, 'depth' => 18, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-4.webp', 'x' => 210, 'y' => -10, 'z' => 15, 'w' => 46, 'rot' => 15, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 23, 'animType' => 3, 'animDur' => 5.5, 'animDelay' => -3.5, 'entranceDelay' => 0.4, 'depth' => 16, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-1.webp', 'x' => 180, 'y' => -65, 'z' => 5, 'w' => 42, 'rot' => -12, 'blur' => 0, 'opacity' => 0.95, 'zIndex' => 22, 'animType' => 4, 'animDur' => 5.0, 'animDelay' => -4.0, 'entranceDelay' => 0.45, 'depth' => 14, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-5.webp', 'x' => 140, 'y' => -100, 'z' => -10, 'w' => 38, 'rot' => -28, 'blur' => 0.4, 'opacity' => 0.9, 'zIndex' => 18, 'animType' => 1, 'animDur' => 4.7, 'animDelay' => -4.5, 'entranceDelay' => 0.5, 'depth' => 12, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-3.webp', 'x' => 90, 'y' => -125, 'z' => -25, 'w' => 34, 'rot' => 30, 'blur' => 0.8, 'opacity' => 0.85, 'zIndex' => 16, 'animType' => 2, 'animDur' => 5.3, 'animDelay' => -5.0, 'entranceDelay' => 0.55, 'depth' => 10, 'extraClass' => 'max-sm:scale-75'],

        // Strawberries along lower-left curve (framing cake tip)
        ['src' => 'images/hero/strawberry-2.webp', 'x' => -245, 'y' => 85, 'z' => 30, 'w' => 56, 'rot' => 38, 'blur' => 0, 'opacity' => 1.0, 'zIndex' => 24, 'animType' => 3, 'animDur' => 5.6, 'animDelay' => -1.8, 'entranceDelay' => 0.2, 'depth' => 20, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-4.webp', 'x' => -305, 'y' => 65, 'z' => 20, 'w' => 50, 'rot' => -25, 'blur' => 0.2, 'opacity' => 0.95, 'zIndex' => 23, 'animType' => 4, 'animDur' => 4.9, 'animDelay' => -2.3, 'entranceDelay' => 0.25, 'depth' => 18, 'extraClass' => 'max-sm:scale-75'],

        // Background floating strawberries (behind cake)
        ['src' => 'images/hero/strawberry-1.webp', 'x' => -195, 'y' => -160, 'z' => -40, 'w' => 68, 'rot' => 20, 'blur' => 1.6, 'opacity' => 0.85, 'zIndex' => 14, 'animType' => 1, 'animDur' => 6.2, 'animDelay' => -0.7, 'entranceDelay' => 0.3, 'depth' => 14, 'extraClass' => 'max-sm:scale-75'],
        ['src' => 'images/hero/strawberry-3.webp', 'x' => 105, 'y' => -185, 'z' => -35, 'w' => 54, 'rot' => -25, 'blur' => 1.2, 'opacity' => 0.85, 'zIndex' => 14, 'animType' => 2, 'animDur' => 5.7, 'animDelay' => -1.4, 'entranceDelay' => 0.35, 'depth' => 12, 'extraClass' => 'max-sm:scale-75'],
    ];

    $rawSlides = [
        [
            'id' => 'fresh-beans',
            'type' => 'product',
            'label' => '01 / SPECIALTY ROASTERY',
            'giant_headline' => 'FRESH BEANS',
            'giant_headline_size' => 'text-[clamp(3.2rem,11vw,9.8rem)] tracking-tight',
            'giant_headline_class' => 'hero-giant-headline-text',
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
            'giant_headline_size' => 'text-[clamp(3.2rem,11.5vw,9.8rem)] tracking-tight',
            'giant_headline_class' => 'hero-giant-headline-text',
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
            'bg_color' => $isDarkTheme ? '#141414' : '#EDEDED',
            'theme' => $isDarkTheme ? 'dark' : 'light',
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
            'giant_headline_size' => 'text-[clamp(3.2rem,11vw,9.8rem)] tracking-tight',
            'giant_headline_class' => 'hero-giant-headline-text',
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
            'bg_color' => $isDarkTheme ? '#1E1B1C' : '#FAFAFA',
            'theme' => $isDarkTheme ? 'dark' : 'light',
            'floaters' => [],
        ],
    ];

    // Di environment lokal tampilkan semua slide
    // Di produksi, hanya render slide yang aset fotonya sudah ada di public/
    $slides = array_filter($rawSlides, function ($slide) {
        if (app()->isLocal()) {
            return true;
        }
        return file_exists(public_path($slide['product_image']));
    });
    $slides = array_values($slides);
    $totalSlides = count($slides);

    $hasActiveHeroBanners = isset($activeBanners) && $activeBanners->count() > 0;
    $firstBannerLayout = $hasActiveHeroBanners ? ($activeBanners->first()->layout_mode ?: 'poster') : null;
    $initialHeroBgColor = $hasActiveHeroBanners
        ? ($firstBannerLayout === 'split' && ! $isDarkTheme ? '#F9F9F9' : '#0C0C0C')
        : ($slides[0]['bg_color'] ?? '#242424');
    $initialHeroNavbarTheme = $hasActiveHeroBanners
        ? ($firstBannerLayout === 'split' && ! $isDarkTheme ? 'light' : 'dark')
        : ($slides[0]['theme'] ?? 'dark');
@endphp

<div id="hero-navbar-offset"
     class="pt-16 sm:pt-20 transition-colors duration-[800ms]"
     style="background-color: var(--hero-bg-color, {{ $initialHeroBgColor }}); transition: background-color 0.8s cubic-bezier(0.16, 1, 0.3, 1);">
<section id="home" 
         data-navbar-theme="{{ $initialHeroNavbarTheme }}"
         class="relative min-h-[560px] sm:min-h-[580px] lg:min-h-[600px] flex items-center justify-center overflow-hidden border-b border-[var(--border-soft)] select-none group/hero"
         style="background-color: {{ $initialHeroBgColor }}; transition: background-color 0.8s ease;">

    <!-- Soft feathered ambient glow for subtle dark depth -->
    <div class="absolute inset-0 pointer-events-none z-1" 
         style="background: radial-gradient(ellipse 70% 70% at 50% 50%, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.01) 60%, transparent 100%);">
    </div>

    @if(isset($activeBanners) && $activeBanners->count() > 0)
        <!-- =========================================================================
             1. DESKTOP ONLY: PROMOTIONAL BANNERS (SCREEN WIDTH >= 768px)
             Di HP banner TIDAK ditampilkan dan media (gambar/video) TIDAK diunduh.
             ========================================================================= -->
        <div class="hidden md:block w-full h-full absolute inset-0" id="desktop-hero-banners-wrapper">
            @foreach($activeBanners as $banner)
                @php
                    $bannerLayoutMode = $banner->layout_mode ?: 'poster';
                    $bannerSlideBg = ($bannerLayoutMode === 'split' && ! $isDarkTheme) ? '#F9F9F9' : '#0C0C0C';
                    $bannerSlideTheme = ($bannerLayoutMode === 'split' && ! $isDarkTheme) ? 'light' : 'dark';
                @endphp
                <div class="hero-slide-item hero-banner-slide absolute inset-0 w-full h-full flex items-center justify-center {{ $loop->first ? 'active z-10' : 'opacity-0 pointer-events-none z-0' }}"
                     data-slide-index="{{ $loop->index }}"
                     data-layout-mode="{{ $bannerLayoutMode }}"
                     data-bg-color="{{ $bannerSlideBg }}"
                     data-theme="{{ $bannerSlideTheme }}">
                    
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

                                <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.08] mb-3 tracking-tight drop-shadow-sm max-w-2xl">
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
                                       class="inline-flex items-center justify-center bg-[#F5F2EE] hover:bg-white text-[#111111] text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-md transition-all duration-200 active:scale-95 cursor-pointer">
                                        {{ $banner->button_text ?: 'PESAN SEKARANG' }}
                                    </a>
                                    <a href="#menu" 
                                       class="inline-flex items-center justify-center bg-black/40 hover:bg-white/15 text-[#F5F5F5] border border-white/45 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95 backdrop-blur-xs">
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
                                        <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[var(--text-muted)] mb-1.5 leading-tight">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif

                                    <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[var(--text)] leading-[1.08] mb-3 tracking-tight">
                                        {{ $banner->title }}
                                    </h1>

                                    <div class="inline-flex items-center space-x-2 mb-3 bg-[var(--surface-strong)] backdrop-blur-sm px-3.5 py-1 rounded-full border border-[var(--border)] shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--text-muted)]"></span>
                                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[var(--text-muted)]">
                                            Nucomu Promo • Tulungagung
                                        </span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-[var(--text-muted)]"></span>
                                    </div>

                                    @if($banner->description)
                                        <p class="font-body text-xs sm:text-sm text-[var(--text-muted)] max-w-lg leading-relaxed mb-6 font-normal">
                                            {{ $banner->description }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="{{ $banner->button_link ?: '#menu' }}" 
                                           class="inline-flex items-center justify-center bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-md transition-all duration-200 active:scale-95 cursor-pointer">
                                            {{ $banner->button_text ?: 'PESAN SEKARANG' }}
                                        </a>
                                        <a href="#menu" 
                                           class="inline-flex items-center justify-center bg-transparent hover:bg-[var(--btn-outline-hover-bg)] text-[var(--text)] border border-[var(--btn-outline)] text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
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
                                        <div class="absolute bottom-3 left-3 bg-[var(--overlay-pill-bg)] backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-[var(--overlay-pill-text)] border border-[var(--overlay-pill-border)] shadow-sm flex items-center space-x-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[var(--text-muted)]"></span>
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

            @if($slide['id'] === 'fresh-beans')
                <!-- -----------------------------------------------------------------
                     SLIDE 1: SPECIALTY ROASTERY - FRESH BEANS (DARK EDITORIAL POSTER + 3D ORBIT)
                     ----------------------------------------------------------------- -->
                <div class="relative w-full h-full flex items-center justify-center overflow-hidden hero-coffee-pack-canvas select-none">

                    <!-- Warm Amber Spotlight Glow behind center coffee pack -->
                    <div class="hero-glow-amber-center absolute inset-0 pointer-events-none z-10" aria-hidden="true"></div>

                    <!-- 1. Giant Headline Behind Product: FRESH BEANS (z-10) -->
                    <div class="hero-giant-headline absolute inset-0 flex items-center justify-center z-10 pointer-events-none select-none px-4 transition-transform duration-300">
                        <span class="font-sans font-extrabold uppercase {{ $slide['giant_headline_size'] ?? 'text-[clamp(3.4rem,11.5vw,10rem)]' }} leading-none tracking-tight text-center whitespace-nowrap hero-giant-headline-text select-none">
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

            @elseif($slide['id'] === 'sweet-moment')
                <!-- -----------------------------------------------------------------
                     SLIDE 3: ARTISANAL PASTRY - SWEET MOMENT (ADAPTIVE EDITORIAL POSTER + SCATTERED STRAWBERRIES)
                     ----------------------------------------------------------------- -->
                <div class="relative w-full h-full flex items-center justify-center overflow-hidden select-none"
                     style="background: {{ $isDarkTheme ? 'linear-gradient(145deg, #1A1113 0%, #110C0E 55%, #0A0608 100%)' : 'linear-gradient(145deg, #FFFFFF 0%, #FAF7F6 55%, #F4EEEC 100%)' }};">

                    <!-- Crimson / Blush Spotlight Glow (subtle) -->
                    <div class="absolute inset-0 pointer-events-none z-5" style="background: {{ $isDarkTheme ? 'radial-gradient(circle 480px at 50% 48%, rgba(180,20,40,0.13) 0%, rgba(120,10,25,0.05) 45%, transparent 72%)' : 'radial-gradient(circle 480px at 50% 48%, rgba(215,65,85,0.09) 0%, rgba(180,40,60,0.03) 45%, transparent 72%)' }};" aria-hidden="true"></div>

                    <!-- 1. Giant Headline Behind Product: SWEET MOMENT (z-10) -->
                    <div class="hero-giant-headline absolute inset-0 flex items-center justify-center z-10 pointer-events-none select-none px-4 transition-transform duration-300">
                        <span class="font-sans font-extrabold uppercase {{ $slide['giant_headline_size'] ?? 'text-[clamp(3.2rem,11vw,9.8rem)]' }} leading-none tracking-tight text-center whitespace-nowrap {{ $isDarkTheme ? 'hero-giant-headline-text' : 'text-[#1C1C1C]/12' }} select-none">
                            SWEET MOMENT
                        </span>
                    </div>

                    <!-- 2 & 3. Central Cheesecake + Scattered Strawberries (7 items) + Slay Jam Drips (z-20) -->
                    <div class="hero-product-layer relative z-20 flex flex-col items-center justify-center px-4 transition-transform duration-300 max-md:mb-24 max-md:-mt-6">
                        <div class="relative flex items-center justify-center">

                            <!-- Scattered Strawberries — 7 items -->

                            {{-- 1. Far top-left --}}
                            <div class="hero-floater absolute -left-16 sm:-left-28 lg:-left-36 top-4 sm:top-8 w-10 sm:w-14 lg:w-16 z-25 hero-bean-anim-1 drop-shadow-[0_8px_18px_rgba(0,0,0,0.3)] pointer-events-none select-none" data-depth="24" aria-hidden="true">
                                <img src="{{ asset('images/hero/strawberry-1.webp') }}" alt="" loading="eager" decoding="async" class="w-full h-auto object-contain transform -rotate-12">
                            </div>

                            {{-- 2. Top-right --}}
                            <div class="hero-floater absolute -right-10 sm:-right-18 lg:-right-24 top-8 sm:top-12 w-9 sm:w-12 lg:w-14 z-25 hero-bean-anim-3 drop-shadow-[0_6px_14px_rgba(0,0,0,0.28)] pointer-events-none select-none" data-depth="20" aria-hidden="true">
                                <img src="{{ asset('images/hero/strawberry-4.webp') }}" alt="" loading="eager" decoding="async" class="w-full h-auto object-contain transform rotate-20">
                            </div>

                            {{-- 3. High above, center-right --}}
                            <div class="hero-floater absolute left-1/2 translate-x-10 sm:translate-x-16 -top-10 sm:-top-14 w-7 sm:w-9 lg:w-11 z-25 hero-bean-anim-4 drop-shadow-[0_8px_16px_rgba(0,0,0,0.22)] pointer-events-none select-none" data-depth="22" aria-hidden="true">
                                <img src="{{ asset('images/hero/strawberry-2.webp') }}" alt="" loading="eager" decoding="async" class="w-full h-auto object-contain transform -rotate-25">
                            </div>

                            {{-- 4. Far right mid --}}
                            <div class="hero-floater absolute -right-16 sm:-right-26 lg:-right-34 top-1/2 w-9 sm:w-12 lg:w-14 z-25 hero-bean-anim-2 drop-shadow-[0_6px_14px_rgba(0,0,0,0.25)] pointer-events-none select-none" data-depth="16" aria-hidden="true">
                                <img src="{{ asset('images/hero/strawberry-5.webp') }}" alt="" loading="eager" decoding="async" class="w-full h-auto object-contain transform rotate-40">
                            </div>

                            {{-- 5. Bottom-right --}}
                            <div class="hero-floater absolute -right-12 sm:-right-20 lg:-right-28 -bottom-8 sm:-bottom-12 w-10 sm:w-13 lg:w-15 z-20 hero-bean-anim-4 drop-shadow-[0_10px_20px_rgba(0,0,0,0.25)] pointer-events-none select-none" data-depth="19" aria-hidden="true">
                                <img src="{{ asset('images/hero/strawberry-3.webp') }}" alt="" loading="eager" decoding="async" class="w-full h-auto object-contain transform rotate-15">
                            </div>

                            {{-- 6. Bottom-left close --}}
                            <div class="hero-floater absolute -left-8 sm:-left-14 lg:-left-18 -bottom-6 sm:-bottom-10 w-9 sm:w-12 lg:w-14 z-25 hero-bean-anim-1 drop-shadow-[0_8px_16px_rgba(0,0,0,0.28)] pointer-events-none select-none" data-depth="17" aria-hidden="true">
                                <img src="{{ asset('images/hero/strawberry-1.webp') }}" alt="" loading="eager" decoding="async" class="w-full h-auto object-contain transform -rotate-30">
                            </div>

                            {{-- 7. Far bottom-left bokeh (large, blurred) --}}
                            <div class="hero-floater absolute -left-20 sm:-left-34 lg:-left-42 bottom-10 sm:bottom-16 w-12 sm:w-15 lg:w-17 z-30 hero-bean-anim-3 drop-shadow-[0_14px_28px_rgba(0,0,0,0.35)] blur-[0.5px] opacity-85 pointer-events-none select-none" data-depth="32" aria-hidden="true">
                                <img src="{{ asset('images/hero/strawberry-2.webp') }}" alt="" loading="eager" decoding="async" class="w-full h-auto object-contain transform -rotate-10">
                            </div>

                            <!-- Central Cheesecake with Float + Slay Jam Drip Animations -->
                            <div class="relative z-20 select-none">
                                <img src="{{ asset($slide['product_image']) }}"
                                     alt="{{ $slide['product_name'] }}"
                                     width="{{ $slide['img_width'] ?? 730 }}"
                                     height="{{ $slide['img_height'] ?? 667 }}"
                                     loading="eager"
                                     decoding="async"
                                     class="w-auto max-h-[220px] sm:max-h-[300px] lg:max-h-[360px] xl:max-h-[400px] object-contain hero-float-cake select-none">

                                <!-- Slay Jam Drip Stream 1 — Main front drop (deepest, longest fall) -->
                                <div class="hero-jam-stream" style="left: 51%; top: 22%;" aria-hidden="true">
                                    <div class="hero-jam-nodule hero-jam-nodule-1"></div>
                                    <div class="hero-jam-drop hero-jam-drop-1" style="background: radial-gradient(ellipse at 38% 20%, #FF5570 0%, #D10A24 55%, #7A0210 100%); border-radius: 40% 40% 55% 55%;"></div>
                                    <div class="hero-jam-micro-1" style="left: 5px;"></div>
                                </div>

                                <!-- Slay Jam Drip Stream 2 — Center-left drop -->
                                <div class="hero-jam-stream" style="left: 42%; top: 20%;" aria-hidden="true">
                                    <div class="hero-jam-nodule hero-jam-nodule-2"></div>
                                    <div class="hero-jam-drop hero-jam-drop-2" style="background: radial-gradient(ellipse at 38% 20%, #FF4466 0%, #C8081E 55%, #720010 100%); border-radius: 40% 40% 55% 55%;"></div>
                                </div>

                                <!-- Slay Jam Drip Stream 3 — Left corner micro drop -->
                                <div class="hero-jam-stream" style="left: 35%; top: 24%;" aria-hidden="true">
                                    <div class="hero-jam-nodule hero-jam-nodule-3"></div>
                                    <div class="hero-jam-drop hero-jam-drop-3" style="background: radial-gradient(ellipse at 38% 20%, #FF3850 0%, #BE0820 55%, #680010 100%); border-radius: 40% 40% 55% 55%;"></div>
                                </div>

                                <!-- Slay Jam Drip Stream 4 — Center-right slope drop -->
                                <div class="hero-jam-stream" style="left: 60%; top: 21%;" aria-hidden="true">
                                    <div class="hero-jam-nodule hero-jam-nodule-4"></div>
                                    <div class="hero-jam-drop hero-jam-drop-4" style="background: radial-gradient(ellipse at 38% 20%, #FF5060 0%, #C80A22 55%, #720012 100%); border-radius: 40% 40% 55% 55%;"></div>
                                </div>

                                <!-- Slay Jam Drip Stream 5 — Right crust flank -->
                                <div class="hero-jam-stream" style="left: 68%; top: 26%;" aria-hidden="true">
                                    <div class="hero-jam-nodule hero-jam-nodule-5"></div>
                                    <div class="hero-jam-drop hero-jam-drop-5" style="background: radial-gradient(ellipse at 38% 20%, #FF3850 0%, #BE0820 55%, #620010 100%); border-radius: 40% 40% 55% 55%;"></div>
                                </div>
                            </div>
                            <div class="hero-cake-shadow-warm" aria-hidden="true"></div>

                        </div>
                    </div>

                    <!-- 4. Left Product Information & CTA in Bottom Left (z-30) -->
                    <div class="hero-product-info absolute max-sm:bottom-[80px] bottom-10 lg:bottom-14 left-5 sm:left-8 lg:left-14 z-30 text-left max-w-xs sm:max-w-sm pointer-events-auto">
                        <!-- Label with baseline divider -->
                        <div class="flex items-center gap-2.5 sm:gap-3 mb-2.5 sm:mb-3.5">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-[0.22em] sm:tracking-[0.25em] {{ $isDarkTheme ? 'text-white/70' : 'text-[#4A4A4A]' }}">
                                {{ $slide['label'] }}
                            </p>
                            <span class="hidden sm:block w-16 lg:w-24 h-[1px] {{ $isDarkTheme ? 'bg-white/20' : 'bg-black/20' }}"></span>
                        </div>

                        <!-- CTA Button -->
                        <div>
                            <a href="{{ $slide['cta_primary']['url'] }}"
                               class="inline-flex items-center gap-2 font-semibold text-xs sm:text-sm uppercase tracking-wider px-5 sm:px-7 py-2.5 sm:py-3.5 rounded-full {{ $isDarkTheme ? 'bg-white text-[#1C1C1C] hover:bg-neutral-100' : 'bg-[#1C1C1C] text-white hover:bg-[#333333]' }} transition-all duration-200 active:scale-95 shadow-lg group/btn cursor-pointer">
                                <span>{{ $slide['cta_primary']['text'] }}</span>
                                <span class="material-symbols-outlined text-base transition-transform duration-200 group-hover/btn:translate-x-1" aria-hidden="true">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>

            @else
                <!-- -----------------------------------------------------------------
                     SLIDE 2: DAILY SIGNATURE DRINK - FRESH BREW (ADAPTIVE EDITORIAL POSTER)
                     ----------------------------------------------------------------- -->
                <div class="relative w-full h-full flex items-center justify-center overflow-hidden"
                     style="background: {{ $isDarkTheme ? 'linear-gradient(145deg, #181818 0%, #101010 55%, #0B0B0B 100%)' : 'linear-gradient(145deg, #F5F5F5 0%, #EDEDED 55%, #E4E4E4 100%)' }};">
                        <!-- Radial Spotlight behind center product -->
                        <div class="absolute inset-0 pointer-events-none z-5" style="background: {{ $isDarkTheme ? 'radial-gradient(circle 420px at 50% 48%, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 45%, transparent 72%)' : 'radial-gradient(circle 420px at 50% 48%, rgba(255, 255, 255, 0.75) 0%, rgba(255, 255, 255, 0.25) 45%, transparent 72%)' }};" aria-hidden="true"></div>

                        <!-- 1. Headline Raksasa (Di Belakang Produk, z-10) -->
                        <div class="hero-giant-headline absolute inset-0 flex items-center justify-center z-10 pointer-events-none select-none px-4 sm:px-8 transition-transform duration-300">
                            <span class="font-sans font-extrabold uppercase {{ $slide['giant_headline_size'] ?? 'text-[clamp(3.2rem,11.5vw,9.8rem)]' }} leading-none tracking-tight text-center whitespace-nowrap {{ $isDarkTheme ? ($slide['giant_headline_class'] ?? 'hero-giant-headline-text') : 'text-[#1C1C1C]/12' }} select-none">
                                {{ $slide['giant_headline'] }}
                            </span>
                        </div>

                        <!-- 2 & 3. Produk di Tengah dengan Floating Elements di Sekelilingnya (z-20) -->
                        <div class="hero-product-layer relative z-20 flex flex-col items-center justify-center px-4 transition-transform duration-300 max-md:mb-24 max-md:-mt-6">
                            @if(file_exists(public_path($slide['product_image'])))
                                <div class="relative flex items-center justify-center">
                                    
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

                                    <div class="hero-product-shadow opacity-40" aria-hidden="true"></div>
                                </div>
                            @endif
                        </div>

                    <!-- 4. Informasi Produk & CTA di Kiri Bawah (z-30) -->
                    <div class="hero-product-info absolute max-sm:bottom-[80px] bottom-10 lg:bottom-14 left-5 sm:left-8 lg:left-14 z-30 text-left max-w-xs sm:max-w-sm pointer-events-auto">
                        <!-- Label with baseline divider -->
                        <div class="flex items-center gap-2.5 sm:gap-3 mb-2.5 sm:mb-3.5">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-[0.22em] sm:tracking-[0.25em] {{ $isDarkTheme ? 'text-white/70' : 'text-[#4A4A4A]' }}">
                                {{ $slide['label'] }}
                            </p>
                            <span class="hidden sm:block w-16 lg:w-24 h-[1px] {{ $isDarkTheme ? 'bg-white/20' : 'bg-black/20' }}"></span>
                        </div>
                        <div>
                            <a href="{{ $slide['cta_primary']['url'] }}" 
                               class="inline-flex items-center gap-2 font-semibold text-xs sm:text-sm uppercase tracking-wider px-5 sm:px-7 py-2.5 sm:py-3.5 rounded-full {{ $isDarkTheme ? 'bg-white text-[#1C1C1C] hover:bg-neutral-100' : 'bg-[#1C1C1C] text-white hover:bg-[#333333]' }} transition-all duration-200 active:scale-95 shadow-lg group/btn cursor-pointer">
                                <span>{{ $slide['cta_primary']['text'] }}</span>
                                <span class="material-symbols-outlined text-base transition-transform duration-200 group-hover/btn:translate-x-1" aria-hidden="true">arrow_forward</span>
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
                    class="hero-nav-arrow hero-prev-arrow hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-black/65 hover:bg-black/85 text-[#F5F5F5] border border-white/25 backdrop-blur-md absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 shadow-[0_4px_16px_rgba(0,0,0,0.45)] cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F5F5F5]"
                    aria-label="Slide Sebelumnya">
                <span class="material-symbols-outlined text-xl">chevron_left</span>
            </button>
            <button type="button" 
                    onclick="nextHeroSlide()" 
                    class="hero-nav-arrow hero-next-arrow hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-black/65 hover:bg-black/85 text-[#F5F5F5] border border-white/25 backdrop-blur-md absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 shadow-[0_4px_16px_rgba(0,0,0,0.45)] cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F5F5F5]"
                    aria-label="Slide Berikutnya">
                <span class="material-symbols-outlined text-xl">chevron_right</span>
            </button>
        @endif

        <!-- Desktop Indicator Dots -->
        @if($activeBanners->count() > 1)
            <div class="hero-controls-bar hidden md:flex absolute bottom-4 left-1/2 -translate-x-1/2 z-30 items-center space-x-2 bg-black/60 hover:bg-black/75 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20 shadow-md text-white select-none" id="desktop-hero-dots">
                @foreach($activeBanners as $b)
                    <button type="button" onclick="goToHeroSlide({{ $loop->index }})" class="hero-dot-btn {{ $loop->first ? 'w-6 bg-white' : 'w-2 bg-white/35 hover:bg-white/70' }} h-1.5 rounded-full transition-all duration-300" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>
        @endif

        <!-- Mobile Indicator Dots (4 Slide Hero Bawaan) -->
        <div class="hero-controls-bar flex md:hidden absolute bottom-4 left-1/2 -translate-x-1/2 z-30 items-center space-x-2 bg-black/60 hover:bg-black/75 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20 shadow-md text-white select-none" id="mobile-hero-dots">
            <button type="button" onclick="goToHeroSlide(0)" class="hero-dot-btn w-6 h-1.5 rounded-full bg-white transition-all duration-300" aria-label="Slide 1"></button>
            <button type="button" onclick="goToHeroSlide(1)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-white/35 hover:bg-white/70 transition-all duration-300" aria-label="Slide 2"></button>
            <button type="button" onclick="goToHeroSlide(2)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-white/35 hover:bg-white/70 transition-all duration-300" aria-label="Slide 3"></button>
            <button type="button" onclick="goToHeroSlide(3)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-white/35 hover:bg-white/70 transition-all duration-300" aria-label="Slide 4"></button>
        </div>
    @else
        @if($totalSlides > 1)
        <!-- Left Carousel Arrow -->
        <button type="button" 
                onclick="prevHeroSlide()" 
                class="hero-nav-arrow hero-prev-arrow hidden sm:flex items-center justify-center w-10 h-10 lg:w-11 lg:h-11 rounded-full bg-black/65 hover:bg-black/85 text-[#F5F5F5] border border-white/25 backdrop-blur-md shadow-[0_4px_16px_rgba(0,0,0,0.45)] absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F5F5F5]"
                aria-label="Slide Sebelumnya">
            <span class="material-symbols-outlined text-xl">chevron_left</span>
        </button>

        <!-- Right Carousel Arrow -->
        <button type="button" 
                onclick="nextHeroSlide()" 
                class="hero-nav-arrow hero-next-arrow hidden sm:flex items-center justify-center w-10 h-10 lg:w-11 lg:h-11 rounded-full bg-black/65 hover:bg-black/85 text-[#F5F5F5] border border-white/25 backdrop-blur-md shadow-[0_4px_16px_rgba(0,0,0,0.45)] absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F5F5F5]"
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
