<!-- SECTION 1: HOME / HERO (4 ANIMATED MENU SLIDES WITH DYNAMIC WARM BACKGROUNDS) -->
<section id="home" 
         class="relative min-h-[560px] sm:min-h-[600px] lg:min-h-[640px] flex items-center justify-center overflow-hidden border-b border-light-gray/60 select-none group/hero"
         style="background-color: #EADBC8; transition: background-color 0.8s ease;">

    <!-- Soft feathered ambient glow for warmth and depth -->
    <div class="absolute inset-0 pointer-events-none z-1" 
         style="background: radial-gradient(ellipse 70% 70% at 50% 50%, rgba(255, 255, 255, 0.45) 0%, rgba(255, 255, 255, 0.15) 60%, transparent 100%);">
    </div>

    <!-- =========================================================================
         SLIDE 0: TIRAMISU (#EADBC8 - Krem Kecokelatan)
         ========================================================================= -->
    <div class="hero-slide-item absolute inset-0 w-full h-full flex items-center justify-center active z-10"
         data-slide-index="0"
         data-bg-color="#EADBC8">
        
        <div class="relative z-20 max-w-6xl mx-auto px-5 sm:px-8 lg:px-12 py-10 sm:py-12 lg:py-14 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Text Column (Left) -->
                <div class="hero-text-content lg:col-span-7 text-left order-2 lg:order-1">
                    <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[#3C281E] mb-1.5 leading-tight">
                        Signature Artisanal Dessert
                    </p>

                    <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#2A1D17] leading-[1.08] mb-3 tracking-tight">
                        Classic Italian<br>
                        Tiramisu Nucomu
                    </h1>

                    <div class="inline-flex items-center space-x-2 mb-3 bg-white/70 backdrop-blur-sm px-3.5 py-1 rounded-full border border-[#54392B]/15 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                            Nucomu Cafe &bull; Tulungagung
                        </span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                    </div>

                    <p class="font-body text-xs sm:text-sm text-[#4A3B32] max-w-lg leading-relaxed mb-6 font-normal">
                        Lapisan ladyfingers berpadu espresso Sendang Tulungagung, krim mascarpone lembut, dan taburan cocoa powder murni pilihan untuk momen santai yang memikat rasa.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-editorial text-2xl sm:text-3xl font-bold text-[#2A1D17] mr-1">Rp 32.000</span>
                        <a href="{{ route('nucomu.order', ['add' => 'Tiramisu']) }}" 
                           class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95">
                            PESAN SEKARANG
                        </a>
                        <a href="#menu" 
                           class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#54392B] border border-[#54392B]/30 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                            DAFTAR MENU
                        </a>
                    </div>
                </div>

                <!-- Video Column (Right) -->
                <div class="hero-video-container lg:col-span-5 flex justify-center lg:justify-end order-1 lg:order-2">
                    <div class="relative w-full max-w-xs sm:max-w-sm lg:max-w-md aspect-square overflow-hidden bg-transparent group">
                        <video class="hero-slide-video w-full h-full object-contain rounded-[20px]"
                               autoplay muted loop playsinline preload="metadata"
                               poster="{{ asset('images/reviews/dya-3.jpg') }}">
                            <source src="{{ asset('videos/tiramisu.mp4') }}" type="video/mp4">
                        </video>
                        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-[#54392B] border border-white/60 shadow-sm flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                            <span>Menu 01 &bull; Tiramisu</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- =========================================================================
         SLIDE 1: CHEESECAKE (#F6DFD2 - Peach Lembut)
         ========================================================================= -->
    <div class="hero-slide-item absolute inset-0 w-full h-full flex items-center justify-center opacity-0 pointer-events-none z-0"
         data-slide-index="1"
         data-bg-color="#F6DFD2">
        
        <div class="relative z-20 max-w-6xl mx-auto px-5 sm:px-8 lg:px-12 py-10 sm:py-12 lg:py-14 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Text Column (Left) -->
                <div class="hero-text-content lg:col-span-7 text-left order-2 lg:order-1">
                    <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[#3C281E] mb-1.5 leading-tight">
                        Best Seller Artisanal Dairy
                    </p>

                    <h2 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#2A1D17] leading-[1.08] mb-3 tracking-tight">
                        Basque Burnt<br>
                        Cheesecake
                    </h2>

                    <div class="inline-flex items-center space-x-2 mb-3 bg-white/70 backdrop-blur-sm px-3.5 py-1 rounded-full border border-[#54392B]/15 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                            Fresh Baked Daily &bull; Nucomu
                        </span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                    </div>

                    <p class="font-body text-xs sm:text-sm text-[#4A3B32] max-w-lg leading-relaxed mb-6 font-normal">
                        Creamy baked basque cheesecake dengan burnt caramelized top yang gurih harum dan filling vanilla beans lumer di lidah, favorit sepanjang masa di Nucomu Cafe.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-editorial text-2xl sm:text-3xl font-bold text-[#2A1D17] mr-1">Rp 36.000</span>
                        <a href="{{ route('nucomu.order', ['add' => 'Cheesecake']) }}" 
                           class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95">
                            PESAN SEKARANG
                        </a>
                        <a href="#menu" 
                           class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#54392B] border border-[#54392B]/30 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                            DAFTAR MENU
                        </a>
                    </div>
                </div>

                <!-- Video Column (Right) -->
                <div class="hero-video-container lg:col-span-5 flex justify-center lg:justify-end order-1 lg:order-2">
                    <div class="relative w-full max-w-xs sm:max-w-sm lg:max-w-md aspect-square overflow-hidden bg-transparent group">
                        <video class="hero-slide-video w-full h-full object-contain rounded-[20px]"
                               autoplay muted loop playsinline preload="metadata"
                               poster="https://lh3.googleusercontent.com/aida-public/AB6AXuCtV8I5EXKu32uYEfKT4WGrWx_ff9JizVDKZYhlBHCpPLkHuYNRdUSoSHGp-Zc4SAAiAjs61tKPWurUmdfI68at-x7a_kiGj4N31Jq8H5rp694k2LcQxExxSptiRnuOudGmFpYVd0rs5YGiZHpPTj5bT-jbZZn5e5gO1l-yYRPKTtp38UzHxVvuLIeBro1FMw5JE-8LnB_Tud6NaQV-TeSFgc40dMGLJpGB2IhhyKBoPCsN5mKB4yZW">
                            <source src="{{ asset('videos/chescake.mp4') }}" type="video/mp4">
                        </video>
                        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-[#54392B] border border-white/60 shadow-sm flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                            <span>Menu 02 &bull; Cheesecake</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- =========================================================================
         SLIDE 2: SPAGHETTI (#F1D2B8 - Terakota Muda)
         ========================================================================= -->
    <div class="hero-slide-item absolute inset-0 w-full h-full flex items-center justify-center opacity-0 pointer-events-none z-0"
         data-slide-index="2"
         data-bg-color="#F1D2B8">
        
        <div class="relative z-20 max-w-6xl mx-auto px-5 sm:px-8 lg:px-12 py-10 sm:py-12 lg:py-14 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Text Column (Left) -->
                <div class="hero-text-content lg:col-span-7 text-left order-2 lg:order-1">
                    <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[#3C281E] mb-1.5 leading-tight">
                        Hearty Italian Comfort Meal
                    </p>

                    <h2 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#2A1D17] leading-[1.08] mb-3 tracking-tight">
                        Spaghetti Aglio<br>
                        Olio Con Peperoncino
                    </h2>

                    <div class="inline-flex items-center space-x-2 mb-3 bg-white/70 backdrop-blur-sm px-3.5 py-1 rounded-full border border-[#54392B]/15 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                            Chef's Pick &bull; Nucomu Kitchen
                        </span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                    </div>

                    <p class="font-body text-xs sm:text-sm text-[#4A3B32] max-w-lg leading-relaxed mb-6 font-normal">
                        Pasta spaghetti al dente berpadu tumisan bawang putih aromatik, cabai kering harum, minyak zaitun extra virgin, dan taburan peterseli segar untuk santap siang atau malam yang lezat.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-editorial text-2xl sm:text-3xl font-bold text-[#2A1D17] mr-1">Rp 34.000</span>
                        <a href="{{ route('nucomu.order', ['add' => 'Pasta Aglio Olio']) }}" 
                           class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95">
                            PESAN SEKARANG
                        </a>
                        <a href="#menu" 
                           class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#54392B] border border-[#54392B]/30 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                            DAFTAR MENU
                        </a>
                    </div>
                </div>

                <!-- Video Column (Right) -->
                <div class="hero-video-container lg:col-span-5 flex justify-center lg:justify-end order-1 lg:order-2">
                    <div class="relative w-full max-w-xs sm:max-w-sm lg:max-w-md aspect-square overflow-hidden bg-transparent group">
                        <video class="hero-slide-video w-full h-full object-contain rounded-[20px]"
                               autoplay muted loop playsinline preload="metadata"
                               poster="https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&q=80&w=800">
                            <source src="{{ asset('videos/spagheti.mp4') }}" type="video/mp4">
                        </video>
                        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-[#54392B] border border-white/60 shadow-sm flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                            <span>Menu 03 &bull; Spaghetti</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- =========================================================================
         SLIDE 3: KOPI SUSU (#D9DECF - Sage / Abu Kehijauan)
         ========================================================================= -->
    <div class="hero-slide-item absolute inset-0 w-full h-full flex items-center justify-center opacity-0 pointer-events-none z-0"
         data-slide-index="3"
         data-bg-color="#D9DECF">
        
        <div class="relative z-20 max-w-6xl mx-auto px-5 sm:px-8 lg:px-12 py-10 sm:py-12 lg:py-14 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Text Column (Left) -->
                <div class="hero-text-content lg:col-span-7 text-left order-2 lg:order-1">
                    <p class="font-script text-2xl sm:text-3xl lg:text-4xl text-[#3C281E] mb-1.5 leading-tight">
                        Authentic Local Specialty Coffee
                    </p>

                    <h2 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#2A1D17] leading-[1.08] mb-3 tracking-tight">
                        Es Kopi Susu<br>
                        Gula Aren Nucomu
                    </h2>

                    <div class="inline-flex items-center space-x-2 mb-3 bg-white/70 backdrop-blur-sm px-3.5 py-1 rounded-full border border-[#54392B]/15 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                            Single Origin Sendang &bull; Organic Aren
                        </span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                    </div>

                    <p class="font-body text-xs sm:text-sm text-[#4A3B32] max-w-lg leading-relaxed mb-6 font-normal">
                        Racikan espresso murni dari biji kopi arabika lereng Gunung Wilis Sendang Tulungagung, berpadu susu segar gurih dingin dan sirup gula aren organik yang memanjakan hari Anda.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-editorial text-2xl sm:text-3xl font-bold text-[#2A1D17] mr-1">Rp 22.000</span>
                        <a href="{{ route('nucomu.order', ['add' => 'Nucomu Coffee']) }}" 
                           class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95">
                            PESAN SEKARANG
                        </a>
                        <a href="#menu" 
                           class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#54392B] border border-[#54392B]/30 text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                            DAFTAR MENU
                        </a>
                    </div>
                </div>

                <!-- Video Column (Right) -->
                <div class="hero-video-container lg:col-span-5 flex justify-center lg:justify-end order-1 lg:order-2">
                    <div class="relative w-full max-w-xs sm:max-w-sm lg:max-w-md aspect-square overflow-hidden bg-transparent group">
                        <video class="hero-slide-video w-full h-full object-contain rounded-[20px]"
                               autoplay muted loop playsinline preload="metadata"
                               poster="https://lh3.googleusercontent.com/aida-public/AB6AXuCPqLrA-TY5mFKhRtn4JKGIrRcBH3BdS1fNJjmW2YLU-LfY2vZYIA_IREh3dxqIRd_3_6MPxGC-J7zRVl8pKRzlzudFwVSwuU9vphdP0N_CE1PyBfm3Du_d5hJkNoLQixpClSDb4DslruxOf4KXQRbVYsb7BsgAoHgevN1ubGxFM6t1k9fceENXwk2soL3QsW9DfXM101g70CDkDFHiMpgGjdyd2NpQBTUZONVX29AZoUp8LvPw3fbk">
                            <source src="{{ asset('videos/kopsu.mp4') }}" type="video/mp4">
                        </video>
                        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-[#54392B] border border-white/60 shadow-sm flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                            <span>Menu 04 &bull; Kopi Susu</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- =========================================================================
         CAROUSEL NAVIGATION CONTROLS (ARROWS & 4 BOTTOM DOTS)
         ========================================================================= -->
    <!-- Left & Right Carousel Arrows (Desktop Floating) -->
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

    <!-- Slide Indicator Dots (4 Dots) -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30 flex items-center space-x-2 bg-black/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-black/5">
        <button type="button" onclick="goToHeroSlide(0)" class="hero-dot-btn w-6 h-1.5 rounded-full bg-[#E06D24] transition-all duration-300" aria-label="Slide 1: Tiramisu"></button>
        <button type="button" onclick="goToHeroSlide(1)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 2: Cheesecake"></button>
        <button type="button" onclick="goToHeroSlide(2)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 3: Spaghetti"></button>
        <button type="button" onclick="goToHeroSlide(3)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300" aria-label="Slide 4: Kopi Susu"></button>
    </div>

</section>
