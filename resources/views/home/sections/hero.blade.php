<!-- SECTION 1: HOME / HERO (4 ANIMATED MENU SLIDES WITH DYNAMIC WARM BACKGROUNDS & PROMOTIONAL BANNERS) -->
<section id="home" 
         class="relative min-h-[560px] sm:min-h-[600px] lg:min-h-[640px] flex items-center justify-center overflow-hidden border-b border-light-gray/60 select-none group/hero"
         style="background-color: #EADBC8; transition: background-color 0.8s ease;">

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
                <div class="hero-slide-item hero-banner-slide absolute inset-0 w-full h-full flex items-center justify-center {{ $loop->first ? 'active z-10' : 'opacity-0 pointer-events-none z-0' }}"
                     data-slide-index="{{ $loop->index }}"
                     data-layout-mode="{{ $banner->layout_mode ?: 'poster' }}"
                     data-bg-color="#FAF8F5">
                    
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
                                    <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[#F6DFD2] mb-1.5 leading-tight">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.08] mb-3 tracking-tight drop-shadow-sm max-w-2xl" style="font-variant-numeric: lining-nums;">
                                    {{ $banner->title }}
                                </h1>

                                <div class="inline-flex items-center space-x-2 mb-3 bg-white/15 backdrop-blur-md px-3.5 py-1 rounded-full border border-white/25 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-white">
                                        Nucomu Promo • Tulungagung
                                    </span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                                </div>

                                @if($banner->description)
                                    <p class="font-body text-xs sm:text-sm text-stone-200 max-w-lg leading-relaxed mb-6 font-normal drop-shadow-xs">
                                        {{ $banner->description }}
                                    </p>
                                @endif

                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ $banner->button_link ?: '#menu' }}" 
                                       class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95 cursor-pointer">
                                        {{ $banner->button_text ?: 'PESAN SEKARANG' }}
                                    </a>
                                    <a href="#menu" 
                                       class="inline-flex items-center justify-center bg-white/20 hover:bg-white text-white hover:text-[#54392B] border border-white/40 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95 backdrop-blur-xs">
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
                                        <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[#3C281E] mb-1.5 leading-tight">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif

                                    <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#2A1D17] leading-[1.08] mb-3 tracking-tight" style="font-variant-numeric: lining-nums;">
                                        {{ $banner->title }}
                                    </h1>

                                    <div class="inline-flex items-center space-x-2 mb-3 bg-white/70 backdrop-blur-sm px-3.5 py-1 rounded-full border border-[#54392B]/15 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                                            Nucomu Promo • Tulungagung
                                        </span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                                    </div>

                                    @if($banner->description)
                                        <p class="font-body text-xs sm:text-sm text-[#4A3B32] max-w-lg leading-relaxed mb-6 font-normal">
                                            {{ $banner->description }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="{{ $banner->button_link ?: '#menu' }}" 
                                           class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95 cursor-pointer">
                                            {{ $banner->button_text ?: 'PESAN SEKARANG' }}
                                        </a>
                                        <a href="#menu" 
                                           class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#54392B] border border-[#54392B]/30 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
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
                                        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-[#54392B] border border-white/60 shadow-sm flex items-center space-x-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
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
            @include('home.sections.hero-default-slides')
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
                    class="hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-white/85 hover:bg-white text-neutral-900 border border-stone-200/80 backdrop-blur-md absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 shadow-md"
                    aria-label="Slide Sebelumnya">
                <span class="material-symbols-outlined text-xl">chevron_left</span>
            </button>
            <button type="button" 
                    onclick="nextHeroSlide()" 
                    class="hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-white/85 hover:bg-white text-neutral-900 border border-stone-200/80 backdrop-blur-md absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 shadow-md"
                    aria-label="Slide Berikutnya">
                <span class="material-symbols-outlined text-xl">chevron_right</span>
            </button>
        @endif

        <!-- Desktop Indicator Dots -->
        @if($activeBanners->count() > 1)
            <div class="hidden md:flex absolute bottom-4 left-1/2 -translate-x-1/2 z-30 items-center space-x-2 bg-black/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-black/5" id="desktop-hero-dots">
                @foreach($activeBanners as $b)
                    <button type="button" onclick="goToHeroSlide({{ $loop->index }})" class="hero-dot-btn {{ $loop->first ? 'w-6 bg-[#E06D24]' : 'w-2 bg-neutral-600/40 hover:bg-neutral-800' }} h-1.5 rounded-full transition-all duration-300" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>
        @endif

        <!-- Mobile Indicator Dots (4 Slide Hero Bawaan) -->
        <div class="flex md:hidden absolute bottom-4 left-1/2 -translate-x-1/2 z-30 items-center space-x-2 bg-black/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-black/5" id="mobile-hero-dots">
            <button type="button" onclick="goToHeroSlide(0)" class="hero-dot-btn w-6 h-1.5 rounded-full bg-[#E06D24] transition-all duration-300" aria-label="Slide 1"></button>
            <button type="button" onclick="goToHeroSlide(1)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 2"></button>
            <button type="button" onclick="goToHeroSlide(2)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 3"></button>
            <button type="button" onclick="goToHeroSlide(3)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 4"></button>
        </div>
    @else
        <!-- Navigation for Default 4 Slides (Desktop & Mobile) -->
        <button type="button" 
                onclick="prevHeroSlide()" 
                class="hidden sm:flex items-center justify-center w-10 h-10 rounded-full bg-white/85 hover:bg-white text-neutral-900 border border-stone-200/80 backdrop-blur-md absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 shadow-md"
                aria-label="Slide Sebelumnya">
            <span class="material-symbols-outlined text-xl">chevron_left</span>
        </button>
        <button type="button" 
                onclick="nextHeroSlide()" 
                class="hidden sm:flex items-center justify-center w-10 h-10 rounded-full bg-white/85 hover:bg-white text-neutral-900 border border-stone-200/80 backdrop-blur-md absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 shadow-md"
                aria-label="Slide Berikutnya">
            <span class="material-symbols-outlined text-xl">chevron_right</span>
        </button>

        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30 flex items-center space-x-2 bg-black/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-black/5" id="default-hero-dots">
            <button type="button" onclick="goToHeroSlide(0)" class="hero-dot-btn w-6 h-1.5 rounded-full bg-[#E06D24] transition-all duration-300" aria-label="Slide 1"></button>
            <button type="button" onclick="goToHeroSlide(1)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 2"></button>
            <button type="button" onclick="goToHeroSlide(2)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 3"></button>
            <button type="button" onclick="goToHeroSlide(3)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 4"></button>
        </div>
    @endif

</section>
