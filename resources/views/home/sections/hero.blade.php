<!-- SECTION 1: HOME / HERO (4 SLIDES: SLIDE 0 WELCOME LATTE + SLIDES 1-3 PRODUCT IN FRONT OF GIANT HEADLINE) -->
@php
    // Generate 3D Vortex Beans for Slide 1 (Fresh Beans) matching screen recording exactly
    $vortexBeans = [];
    $numSpiral = 24;
    for ($i = 0; $i < $numSpiral; $i++) {
        $t = $i / ($numSpiral - 1);
        $angle = -2.3 + $t * 5.0;
        $rx = 85 + pow($t, 1.15) * 230;
        $ry = 55 + pow($t, 1.15) * 160;
        $cx = 30 + $t * 40;
        $cy = -15;
        $x = (int) round($cx + $rx * cos($angle));
        $y = (int) round($cy + $ry * sin($angle) * 0.92);
        $depthCurve = sin($angle);
        $z = (int) round($depthCurve * 45 + ($t - 0.3) * 45);
        $scale = max(0.32, round(0.35 + ($z + 60) / 150 * 0.75, 2));
        $tangentDeg = (int) round(($angle * 180 / M_PI) + 90);
        $rot = $tangentDeg + ($i % 5) * 18 - 25;
        $beanId = ($i % 3) + 1;
        $zIndex = $z < 0 ? 15 : 25;
        $blur = $z < -35 ? 1.0 : ($z > 70 ? 1.2 : 0);
        $opacity = $z < -40 ? 0.75 : 1.0;
        $animType = ($i % 4) + 1;
        $animDur = round(4.2 + ($i % 3) * 0.7, 1);
        $animDelay = round(-($i * 0.22), 2);
        $entranceDelay = round($t * 0.42, 2);
        $depth = (int) round(14 + ($z + 60) / 150 * 22);

        $vortexBeans[] = [
            'src' => "images/hero/bean-{$beanId}.webp",
            'x' => $x,
            'y' => $y,
            'z' => $z,
            'w' => (int) round(48 * $scale),
            'rot' => $rot,
            'blur' => $blur,
            'opacity' => $opacity,
            'zIndex' => $zIndex,
            'animType' => $animType,
            'animDur' => $animDur,
            'animDelay' => $animDelay,
            'entranceDelay' => $entranceDelay,
            'depth' => $depth,
            'extraClass' => 'max-sm:scale-75',
        ];
    }

    $cinematicFg = [
        ['beanId' => 1, 'x' => -410, 'y' => 220, 'z' => 95, 'w' => 135, 'rot' => -26, 'blur' => 3.8, 'opacity' => 0.95, 'zIndex' => 35, 'animType' => 1, 'animDur' => 6.4, 'animDelay' => 0.0, 'entranceDelay' => 0.15, 'depth' => 46, 'class' => 'max-md:hidden'],
        ['beanId' => 1, 'x' => -290, 'y' => -210, 'z' => 55, 'w' => 84, 'rot' => 18, 'blur' => 0.4, 'opacity' => 0.95, 'zIndex' => 35, 'animType' => 2, 'animDur' => 5.8, 'animDelay' => -1.2, 'entranceDelay' => 0.2, 'depth' => 32, 'class' => 'max-md:hidden'],
        ['beanId' => 3, 'x' => 80, 'y' => -210, 'z' => 40, 'w' => 55, 'rot' => 50, 'blur' => 1.2, 'opacity' => 0.88, 'zIndex' => 25, 'animType' => 3, 'animDur' => 4.9, 'animDelay' => -2.8, 'entranceDelay' => 0.35, 'depth' => 26, 'class' => 'max-sm:scale-75'],
        ['beanId' => 2, 'x' => 275, 'y' => -185, 'z' => 80, 'w' => 100, 'rot' => -18, 'blur' => 2.4, 'opacity' => 0.92, 'zIndex' => 35, 'animType' => 4, 'animDur' => 5.9, 'animDelay' => -0.8, 'entranceDelay' => 0.4, 'depth' => 38, 'class' => 'max-sm:scale-75'],
        ['beanId' => 1, 'x' => 410, 'y' => 35, 'z' => 85, 'w' => 115, 'rot' => 36, 'blur' => 2.8, 'opacity' => 0.95, 'zIndex' => 35, 'animType' => 1, 'animDur' => 6.8, 'animDelay' => -3.2, 'entranceDelay' => 0.45, 'depth' => 42, 'class' => 'max-md:hidden'],
        ['beanId' => 3, 'x' => 250, 'y' => 195, 'z' => 75, 'w' => 95, 'rot' => 52, 'blur' => 2.6, 'opacity' => 0.9, 'zIndex' => 32, 'animType' => 2, 'animDur' => 5.3, 'animDelay' => -1.9, 'entranceDelay' => 0.3, 'depth' => 36, 'class' => 'max-sm:scale-75'],
        ['beanId' => 2, 'x' => 5, 'y' => 220, 'z' => 70, 'w' => 75, 'rot' => -12, 'blur' => 2.0, 'opacity' => 0.88, 'zIndex' => 30, 'animType' => 3, 'animDur' => 5.7, 'animDelay' => -2.3, 'entranceDelay' => 0.2, 'depth' => 32, 'class' => 'max-sm:scale-75 max-md:hidden'],
    ];
    foreach ($cinematicFg as $cfg) {
        $vortexBeans[] = [
            'src' => "images/hero/bean-{$cfg['beanId']}.webp",
            'x' => $cfg['x'],
            'y' => $cfg['y'],
            'z' => $cfg['z'],
            'w' => $cfg['w'],
            'rot' => $cfg['rot'],
            'blur' => $cfg['blur'],
            'opacity' => $cfg['opacity'],
            'zIndex' => $cfg['zIndex'],
            'animType' => $cfg['animType'],
            'animDur' => $cfg['animDur'],
            'animDelay' => $cfg['animDelay'],
            'entranceDelay' => $cfg['entranceDelay'],
            'depth' => $cfg['depth'],
            'extraClass' => $cfg['class'],
        ];
    }

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
            'label' => 'ULTRA AROMA',
            'giant_headline' => 'PARTNER',
            'headline_line1' => 'RICH',
            'headline_line2' => 'AROMA',
            'subtext' => 'Nikmati keistimewaan biji kopi pilihan dengan aroma khas dan cita rasa istimewa dalam setiap seduhan.',
            'cta_primary' => [
                'text' => 'Lihat Menu',
                'url' => route('home') . '#menu',
            ],
            'product_image' => 'images/hero/coffee-pack.webp',
            'product_name' => 'Kemasan kopi Nucomu Cafe',
            'img_width' => 900,
            'img_height' => 1389,
            'aspect_ratio' => '2/3',
            'bg_color' => '#D7A461',
            'theme' => 'gold',
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
                [
                    'src' => 'images/hero/bean-1.png',
                    'wrapper_class' => 'absolute -left-10 sm:-left-16 lg:-left-20 top-12 sm:top-16 w-8 sm:w-10 lg:w-12 z-25 hero-bean-anim-3 drop-shadow-[0_6px_12px_rgba(0,0,0,0.25)]',
                    'img_class' => 'w-full h-auto object-contain transform -rotate-20',
                    'depth' => 18,
                ],
                [
                    'src' => 'images/hero/bean-2.png',
                    'wrapper_class' => 'absolute -right-8 sm:-right-14 lg:-right-18 top-20 sm:top-24 w-9 sm:w-11 lg:w-13 z-25 hero-bean-anim-1 drop-shadow-[0_8px_14px_rgba(0,0,0,0.25)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-35',
                    'depth' => 20,
                ],
                [
                    'src' => 'images/hero/bean-3.png',
                    'wrapper_class' => 'absolute right-2 sm:right-6 -bottom-2 sm:-bottom-4 w-7 sm:w-9 lg:w-11 z-25 hero-bean-anim-2 drop-shadow-[0_6px_10px_rgba(0,0,0,0.25)]',
                    'img_class' => 'w-full h-auto object-contain transform rotate-15',
                    'depth' => 14,
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
@endphp

<section id="home" 
         class="relative min-h-[560px] sm:min-h-[600px] lg:min-h-[640px] flex items-center justify-center overflow-hidden border-b border-light-gray/60 select-none group/hero focus:outline-none"
         style="background-color: {{ $slides[0]['bg_color'] ?? '#140E0A' }}; transition: background-color 0.8s ease;"
         role="region"
         aria-roledescription="carousel"
         aria-label="Nucomu Cafe Hero Slider"
         tabindex="0">

    <!-- =========================================================================
         HERO SLIDES CONTAINER
         ========================================================================= -->
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
                <div class="relative z-20 max-w-7xl mx-auto px-5 sm:px-8 lg:px-14 py-12 sm:py-16 w-full flex items-center min-h-[560px] sm:min-h-[600px] lg:min-h-[640px]">
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
                     SLIDE 1: HONEY GOLDEN THEME + 3D COFFEE BEAN VORTEX ANIMATION
                     ----------------------------------------------------------------- -->
                <div class="relative w-full h-full flex flex-col md:block items-center justify-center overflow-hidden hero-gold-canvas max-md:pt-4 max-md:pb-16 max-md:px-4">

                    <!-- Silk Light Wave Ribbons (1 Thick Glowing Wave 40-60px + Thin Swirling Ribbons) -->
                    <svg class="hero-silk-ribbons absolute inset-0 w-full h-full pointer-events-none z-10 select-none" viewBox="0 0 1440 640" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <!-- 1. Gelombang Tebal Berkilau (Visual Width 40-60px: stroke-width 50, blur) -->
                        <path class="hero-silk-wave-thick" d="M-80 570 C 300 520, 560 620, 860 410 C 1140 220, 1340 160, 1640 90" stroke="url(#silkGradThick)" stroke-width="50" stroke-linecap="round" />
                        <!-- 2 & 3. Garis Tipis Sutra Menyilang Ke Kanan Atas -->
                        <path class="hero-silk-wave-thin" d="M-120 540 C 280 490, 520 600, 820 420 C 1120 240, 1320 170, 1620 110" stroke="url(#silkGrad1)" stroke-width="3.5" />
                        <path class="hero-silk-wave-thin" d="M-40 590 C 360 530, 580 640, 890 440 C 1180 260, 1380 190, 1680 130" stroke="url(#silkGrad2)" stroke-width="2" opacity="0.75" />
                        <!-- 4. Garis Sutra Melengkung di Bawah Kiri (Sesuai Referensi Layar) -->
                        <path class="hero-silk-wave-thin" d="M-60 420 C 80 460, 200 540, 420 580" stroke="url(#silkGrad1)" stroke-width="2.5" opacity="0.65" />
                        <defs>
                            <linearGradient id="silkGradThick" x1="0" y1="0.8" x2="1" y2="0.2">
                                <stop offset="0%" stop-color="#FFF1CC" stop-opacity="0" />
                                <stop offset="25%" stop-color="#FFF1CC" stop-opacity="0.4" />
                                <stop offset="55%" stop-color="#FFE099" stop-opacity="0.55" />
                                <stop offset="80%" stop-color="#FFF5DE" stop-opacity="0.35" />
                                <stop offset="100%" stop-color="#FFF1CC" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="silkGrad1" x1="0" y1="0.5" x2="1" y2="0.5">
                                <stop offset="0%" stop-color="#FFF1CC" stop-opacity="0" />
                                <stop offset="25%" stop-color="#FFF1CC" stop-opacity="0.85" />
                                <stop offset="60%" stop-color="#FFE099" stop-opacity="0.9" />
                                <stop offset="100%" stop-color="#FFF1CC" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="silkGrad2" x1="0" y1="0.5" x2="1" y2="0.5">
                                <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0" />
                                <stop offset="35%" stop-color="#FFF5DE" stop-opacity="0.8" />
                                <stop offset="70%" stop-color="#FAD68A" stop-opacity="0.85" />
                                <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <!-- Left Composition: Pill Badge + Stacked Headline + Description + CTA Button -->
                    <div class="hero-slide1-left-col relative max-md:mt-1 max-md:mb-3 md:absolute md:left-20 lg:left-24 xl:left-[6%] md:top-1/2 md:-translate-y-1/2 z-30 flex flex-col items-center md:items-start text-center md:text-left select-none max-md:w-full max-w-[92%] md:max-w-[34%] lg:max-w-[32%]">
                        <!-- Dark Chocolate Pill Badge with Protruding Coffee Bean -->
                        <div class="relative inline-flex items-center pl-3.5 pr-7 py-1.5 rounded-full hero-pill-badge text-[10px] sm:text-xs font-bold tracking-widest uppercase shadow-sm mb-3 sm:mb-4 select-none">
                            <span>{{ $slide['label'] }}</span>
                            <img src="{{ asset('images/hero/bean-1.webp') }}" alt="" class="absolute -right-2.5 top-1/2 -translate-y-1/2 w-[22px] h-[22px] object-contain rotate-12 drop-shadow-sm pointer-events-none" aria-hidden="true">
                        </div>

                        <!-- Stacked Pure White Headline: RICH / AROMA (Matching Reference Video) -->
                        <h1 class="font-body font-black uppercase text-white text-[clamp(36px,4.5vw,72px)] leading-[0.95] tracking-tight drop-shadow-[0_4px_16px_rgba(70,35,10,0.35)] pointer-events-none">
                            RICH<br>
                            <span class="whitespace-nowrap">AROMA</span>
                        </h1>

                        <!-- Description in Soft Cream/White (#FFF5EA/90) for High Contrast on Gold -->
                        <p class="font-body font-normal text-[#FFF5EA]/90 text-[clamp(14px,1.1vw,16px)] leading-[1.6] max-w-[34ch] mt-4 mb-6 drop-shadow-sm line-clamp-4 md:line-clamp-3 lg:line-clamp-none">
                            {{ $slide['subtext'] }}
                        </p>

                        <!-- Dark Pill Button: Lihat Menu -->
                        <div class="z-30 max-md:w-full">
                            <a href="{{ $slide['cta_primary']['url'] }}" 
                               class="inline-flex items-center justify-center gap-2 font-semibold text-xs sm:text-sm uppercase tracking-wider px-7 py-3.5 rounded-full bg-[#2A1A10] text-[#EDDAC1] hover:bg-[#150d08] transition-all duration-200 active:scale-95 shadow-lg group/btn cursor-pointer min-h-[44px] max-md:w-full">
                                <span>{{ $slide['cta_primary']['text'] }}</span>
                                <span class="material-symbols-outlined text-base transition-transform duration-200 group-hover/btn:translate-x-1">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Behind Coffee Pack: PARTNER (Bold Dark Chocolate Typography, Behind Vortex, Hidden on Mobile/Tablet) -->
                    <div class="hero-partner-text hidden lg:block absolute left-[49%] top-1/2 -translate-y-1/2 z-10 select-none pointer-events-none text-left whitespace-nowrap">
                        <span class="font-body font-black uppercase text-[#2A1A10] text-[clamp(68px,8.6vw,144px)] leading-[0.9] tracking-tighter drop-shadow-[0_2px_12px_rgba(255,240,210,0.15)] opacity-90 block">
                            PARTNER
                        </span>
                    </div>

                    <!-- 3D Vortex Scene Container (z-20) -->
                    <div class="hero-vortex-scene relative z-20 flex flex-col items-center justify-center pointer-events-none max-md:order-first max-md:mt-1 max-md:mb-2 md:absolute md:left-[53%] lg:left-[50%] md:top-1/2 md:-translate-x-1/2 md:-translate-y-1/2" style="z-index: 20;">
                        <div class="relative flex items-center justify-center">
                            
                            <!-- Central Coffee Pack with 3D Float (z-20, in front of PARTNER text) -->
                            <img src="{{ asset($slide['product_image']) }}"
                                 alt="{{ $slide['product_name'] }}"
                                 width="{{ $slide['img_width'] ?? 900 }}"
                                 height="{{ $slide['img_height'] ?? 1389 }}"
                                 loading="eager"
                                 decoding="async"
                                 class="w-auto h-auto max-h-[175px] sm:max-h-[260px] md:max-h-[380px] lg:max-h-[440px] xl:max-h-[490px] object-contain hero-pack-3d relative z-20 select-none">
                            <div class="hero-pack-shadow-warm" aria-hidden="true"></div>

                            <!-- 3D Vortex Orbiting Coffee Beans Container with Proportional Responsive Scaling -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none max-md:scale-[0.65] max-sm:scale-[0.44] origin-center" style="transform-style: preserve-3d;">
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
                                             style="{{ !empty($vBean['blur']) ? 'filter: blur('.$vBean['blur'].'px) drop-shadow(0 10px 20px rgba(70,35,10,0.55));' : 'filter: drop-shadow(0 10px 20px rgba(70,35,10,0.55));' }} animation-duration: {{ $vBean['animDur'] }}s; animation-delay: {{ $vBean['animDelay'] }}s;">
                                    </div>
                                @endforeach
                            </div>

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
    @if($totalSlides > 1)
        <!-- Left Carousel Arrow -->
        <button type="button" 
                onclick="prevHeroSlide()" 
                class="hero-nav-arrow hero-prev-arrow hidden sm:flex items-center justify-center w-10 h-10 lg:w-11 lg:h-11 rounded-full bg-white/85 hover:bg-white text-[#1C1C1C] border border-stone-200/80 backdrop-blur-md shadow-md absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]"
                aria-label="Slide Sebelumnya">
            <span class="material-symbols-outlined text-xl">chevron_left</span>
        </button>

        <!-- Right Carousel Arrow -->
        <button type="button" 
                onclick="nextHeroSlide()" 
                class="hero-nav-arrow hero-next-arrow hidden sm:flex items-center justify-center w-10 h-10 lg:w-11 lg:h-11 rounded-full bg-white/85 hover:bg-white text-[#1C1C1C] border border-stone-200/80 backdrop-blur-md shadow-md absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]"
                aria-label="Slide Berikutnya">
            <span class="material-symbols-outlined text-xl">chevron_right</span>
        </button>

        <!-- Bottom Controls Bar: Counter, Dots, Pause/Play -->
        <div class="hero-controls-bar absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-30 flex items-center gap-3 bg-black/40 hover:bg-black/55 backdrop-blur-md px-4 py-1.5 sm:py-2 rounded-full border border-white/15 transition-all text-white select-none shadow-sm">
            <!-- Counter "01 / 04" -->
            <span class="hero-counter-text font-mono text-xs tracking-widest font-semibold">
                <span id="hero-current-num">01</span> / <span id="hero-total-num">{{ sprintf('%02d', $totalSlides) }}</span>
            </span>

            <span class="w-px h-3.5 bg-white/25" aria-hidden="true"></span>

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

            <span class="w-px h-3.5 bg-white/25" aria-hidden="true"></span>

            <!-- Pause / Play Button -->
            <button type="button"
                    id="hero-pause-btn"
                    onclick="toggleHeroAutoplay()"
                    class="w-6 h-6 flex items-center justify-center text-white/80 hover:text-white transition-colors focus:outline-none cursor-pointer"
                    aria-label="Jeda tayangan slide otomatis"
                    title="Jeda / Putar tayangan otomatis">
                <span id="hero-pause-icon" class="material-symbols-outlined text-base">pause</span>
            </button>
        </div>
    @endif

</section>
