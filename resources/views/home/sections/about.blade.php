<!-- SECTION 2: ABOUT NUCOMU CAFE -->
<section id="about" 
         class="relative pt-16 sm:pt-20 lg:pt-28 pb-20 sm:pb-28 lg:pb-32 overflow-hidden border-b border-[#DDDDDD]/80"
         style="background-color: #F9F9F9; transition: background-color 0.8s ease;">

    {{-- NoScript Accessibility Fallback: Ensures content is visible if JS is disabled --}}
    <noscript>
        <style>
            .story-anim-photo,
            .story-anim-badge-logo,
            .story-anim-badge-coffee,
            .story-anim-right,
            .facility-anim-left,
            .facility-anim-right,
            .reveal-on-scroll,
            .reveal-from-left,
            .reveal-from-right,
            .reveal-fade-up {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            .story-anim-deco {
                opacity: 1 !important;
                transform: rotate(-2.6deg) !important;
                transition: none !important;
            }
            .story-typing-cursor {
                display: none !important;
            }
        </style>
    </noscript>

    {{-- Perimeter Decorative Element 1: Vertical Marginal Text on Left (Desktop Editorial Accent) --}}
    <div class="hidden xl:flex absolute left-5 2xl:left-8 top-1/3 -translate-y-1/2 flex-col items-center gap-6 pointer-events-none select-none z-10" aria-hidden="true">
        <span class="vertical-lr-editorial text-[10px] font-semibold tracking-[0.3em] uppercase text-[#737373] font-body">
            NUCOMU CAFE &amp; EATERY
        </span>
        <div class="w-px h-16 bg-[#252525]/15"></div>
    </div>

    {{-- Perimeter Decorative Element 2: Top Right Editorial Established Timestamp --}}
    <div class="hidden sm:flex absolute right-6 sm:right-10 lg:right-16 top-10 sm:top-14 flex-col items-end pointer-events-none select-none z-10" aria-hidden="true">
        <div class="text-right">
            <span class="block text-[11px] font-bold tracking-[0.2em] text-[#737373] uppercase font-body">EST.</span>
            <span class="block text-[11px] font-medium tracking-[0.18em] text-[#737373] font-body">2024</span>
        </div>
        <div class="w-px h-10 bg-[#252525]/15 mt-2"></div>
    </div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- SUBSECTION 2A: OUR STORY / ABOUT NUCOMU (ANIMATED VIDEO & DYNAMIC WARM BACKGROUNDS) -->
        <div id="our-story-section" class="mb-20 sm:mb-24 lg:mb-28">
            {{-- TWO-COLUMN MAIN CONTENT --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 xl:gap-20 items-center">

                {{-- KOLOM KIRI: ELEGANT LAYERED FLOATING FRAME DENGAN 4 ANIMASI VIDEO NUCOMU CAFE --}}
                <div class="lg:col-span-6 flex justify-center lg:justify-start">
                    <div class="group/story-card relative w-full max-w-[440px] sm:max-w-[470px] lg:max-w-[490px] xl:max-w-[510px] p-4 sm:p-5 lg:p-6">

                        {{-- 1. Lapisan Belakang (Back Plate): Neutral gray matching tone, Rounded Corners, Rotasi -2.6 Derajat --}}
                        <div id="about-backplate" 
                             class="story-anim-deco absolute inset-0 bg-[#9E9E9E] rounded-[28px] sm:rounded-[34px] lg:rounded-[40px] pointer-events-none z-0 shadow-md origin-center transform -rotate-2 sm:-rotate-[2.6deg]"
                             style="transition: background-color 0.8s ease;"
                             aria-hidden="true"></div>

                        {{-- 2. Frame Media Nucomu Cafe (4 Video Animasi Sesuai Arahan) --}}
                        <div id="about-video-container"
                             class="story-anim-photo group/carousel relative z-10 w-full rounded-[18px] sm:rounded-[22px] lg:rounded-[24px] overflow-hidden border-[3.5px] sm:border-4 border-white shadow-[0_20px_45px_-10px_rgba(0,0,0,0.22)] bg-neutral-900 select-none aspect-square"
                             style="transition: background-color 0.5s ease;">
                            
                            <!-- Video 0: Tiramisu -->
                            <video class="about-slide-video absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out opacity-100 z-10"
                                   autoplay muted loop playsinline preload="auto">
                                <source src="{{ asset('videos/tiramisu.mp4') }}" type="video/mp4">
                            </video>

                            <!-- Video 1: Cheesecake -->
                            <video class="about-slide-video absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out opacity-0 z-0 pointer-events-none"
                                   muted loop playsinline preload="auto">
                                <source src="{{ asset('videos/cheesecake.mp4') }}" type="video/mp4">
                            </video>

                            <!-- Video 2: Spaghetti -->
                            <video class="about-slide-video absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out opacity-0 z-0 pointer-events-none"
                                   muted loop playsinline preload="auto">
                                <source src="{{ asset('videos/spagheti.mp4') }}" type="video/mp4">
                            </video>

                            <!-- Video 3: Kopi Susu -->
                            <video class="about-slide-video absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out opacity-0 z-0 pointer-events-none"
                                   muted loop playsinline preload="auto">
                                <source src="{{ asset('videos/kopsu.mp4') }}" type="video/mp4">
                            </video>

                            <!-- Badge Tag Dinamis di Atas Video (Pojok Kiri Bawah) -->
                            <div id="about-video-badge" class="absolute bottom-3 left-3 z-30 bg-white/90 backdrop-blur-md px-3.5 py-1.5 rounded-full text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#252525] border border-white/60 shadow-sm flex items-center space-x-1.5 transition-all duration-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#555555]"></span>
                                <span id="about-video-badge-text">Menu 01 • Tiramisu</span>
                            </div>

                        </div>

                        {{-- 3. Badge Logo Resmi Nucomu (Sudut Kiri Atas Foto) --}}
                        <div class="story-anim-badge-logo absolute top-1.5 left-1.5 sm:top-2 sm:left-2 lg:top-2.5 lg:left-2.5 z-20 w-[68px] h-[68px] sm:w-[76px] sm:h-[76px] lg:w-[82px] lg:h-[82px] bg-[#E8E8E8] rounded-[16px] sm:rounded-[18px] shadow-[0_10px_25px_-6px_rgba(0,0,0,0.22)] flex flex-col items-center justify-center p-2 border border-white/95 select-none" title="Nucomu Cafe Tulungagung">
                            <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" 
                                 alt="Logo Nucomu Cafe" 
                                 class="h-6 sm:h-7 lg:h-8 w-auto object-contain brightness-0">
                            <span class="text-[7.5px] sm:text-[8.5px] font-extrabold tracking-[0.2em] text-[#1C1C1C] uppercase mt-1 font-body">NUCOMU</span>
                        </div>

                        {{-- 4. Badge Ikon Kopi (Sudut Kanan Bawah Foto) --}}
                        <div class="story-anim-badge-coffee absolute bottom-1.5 right-1.5 sm:bottom-2 sm:right-2 lg:bottom-2.5 lg:right-2.5 z-20 w-[54px] h-[54px] sm:w-[60px] sm:h-[60px] lg:w-[66px] lg:h-[66px] bg-[#242424] rounded-full shadow-[0_12px_28px_-6px_rgba(0,0,0,0.36)] flex items-center justify-center text-white border-[3px] border-white" title="Nucomu Coffee Craftsmanship" aria-label="Ikon Kopi Nucomu">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                <line x1="6" y1="1" x2="6" y2="4"></line>
                                <line x1="10" y1="1" x2="10" y2="4"></line>
                                <line x1="14" y1="1" x2="14" y2="4"></line>
                            </svg>
                        </div>

                    </div>
                </div>

                {{-- KOLOM KANAN: LABEL, HEADLINE EDITORIAL, KETERANGAN MENGETIK CEPAT & TOMBOL MENU --}}
                <div class="lg:col-span-6 flex flex-col justify-center text-left lg:pl-2">

                    {{-- 1. Label Section dengan Garis Aksen Tipis --}}
                    <div class="story-anim-right story-delay-1 flex items-center gap-3 mb-5">
                        <span id="about-slide-label" class="text-xs sm:text-[13px] font-bold text-[#555555] uppercase tracking-[0.22em] font-body transition-colors duration-500">
                            {{ $brand['about_label'] ?? 'SIGNATURE ARTISANAL DESSERT' }}
                        </span>
                        <span class="sr-only">{{ $brand['about_label'] ?? 'OUR STORY / TENTANG KAMI' }}</span>
                        <div class="w-12 h-px bg-neutral-300" aria-hidden="true"></div>
                    </div>

                    {{-- 2. Headline Utama: Modern Minimalist Manrope Semibold (600) --}}
                    <div class="story-anim-right story-delay-2 mb-5 min-h-[72px] sm:min-h-[84px] flex items-center">
                        <h2 id="about-slide-headline" class="font-body text-3xl sm:text-4xl lg:text-[40px] xl:text-[44px] font-semibold text-[#1C1C1C] leading-[1.18] tracking-tight transition-opacity duration-300">
                            @if(!empty($brand['story_headline']))
                                {{ $brand['story_headline'] }}
                            @else
                                Classic Italian<br>Tiramisu Nucomu
                            @endif
                        </h2>
                        <span class="sr-only">
                            {{ $brand['story_headline'] ?? 'Berawal dari Rasa, Kembali karena Rindu.' }}
                        </span>
                    </div>

                    {{-- 3. Keterangan Nucomu Cafe: Tampil Utuh Langsung & Fade Halus Tanpa Ketik per Huruf --}}
                    <div class="space-y-4 max-w-xl text-[15px] sm:text-base text-[#555555] leading-[1.8] font-body mb-8 min-h-[110px] sm:min-h-[130px]">
                        <p id="about-slide-description" class="story-anim-right story-delay-3 transition-opacity duration-300 opacity-100">
                            Lapisan ladyfingers berpadu espresso Sendang Tulungagung, krim mascarpone lembut, dan taburan cocoa powder murni pilihan untuk momen santai yang memikat rasa.
                        </p>
                        <div class="sr-only">
                            <p>{{ $brand['story_p1'] ?? 'Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.' }}</p>
                            <p>{{ $brand['story_p2'] ?? 'Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.' }}</p>
                        </div>
                    </div>

                    {{-- 4. Tombol Jelajahi Pilihan Menu --}}
                    <div class="story-anim-right story-delay-5">
                        <a href="{{ $brand['about_button_link'] ?? '#menu' }}"
                           class="group inline-flex items-center gap-3.5 bg-[#242424] hover:bg-[#383838] active:bg-[#1A1A1A] text-white text-sm sm:text-[15px] font-medium py-3.5 px-8 rounded-full transition-colors duration-300 shadow-[0_8px_20px_-4px_rgba(36,36,36,0.3)] hover:shadow-[0_12px_28px_-6px_rgba(36,36,36,0.4)] focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                            <span>{{ $brand['about_button_text'] ?? 'Jelajahi Pilihan Menu' }}</span>
                            <svg class="w-4 h-4 text-white transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                </div>

            </div>

            {{-- QUOTE SECTION: MINIMALIST EDITORIAL QUOTE CARD --}}
            <div id="story-quote-wrapper" class="mt-16 sm:mt-20 lg:mt-24">
                <div class="relative bg-[#F1F1F1]/90 backdrop-blur-sm border border-[#E0E0E0] rounded-[24px] sm:rounded-[28px] p-6 sm:p-8 lg:p-9 shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all duration-300 hover:border-neutral-400">
                    
                    <div class="flex flex-col md:flex-row items-center md:items-start lg:items-center justify-between gap-6">

                        {{-- Left Quote Icon Indicator --}}
                        <div class="flex-shrink-0 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-[#E0E0E0] flex items-center justify-center text-[#777777] shadow-sm">
                                <span class="font-editorial text-3xl font-bold leading-none select-none">&ldquo;</span>
                            </div>
                            <div class="hidden sm:block w-px h-10 bg-[#DDDDDD]" aria-hidden="true"></div>
                        </div>

                        {{-- Center: Quote Typography with Screen Reader & Layout-Shift-Free Typewriter --}}
                        @php
                            $quoteFull = $brand['about_quote'] ?? 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.';
                        @endphp
                        <div class="flex-grow text-center md:text-left px-1 sm:px-3">
                            <p class="sr-only">
                                "{{ $quoteFull }}"
                            </p>
                            {{-- Grid Stacking: Phantom text reserves full height across all responsive breakpoints (1440, 1024, 768, 390px) --}}
                            <div class="grid [grid-template-areas:'quote-stack'] items-center text-center md:text-left">
                                {{-- 1. Phantom Invisible Full Text: Memesan tinggi & line-wrap penuh sejak awal agar kartu tidak loncat --}}
                                <blockquote class="[grid-area:quote-stack] font-editorial italic font-medium text-lg sm:text-xl lg:text-[22px] text-transparent leading-[1.55] select-none pointer-events-none invisible" aria-hidden="true">
                                    &ldquo;{{ $quoteFull }}&rdquo;
                                </blockquote>
                                {{-- 2. Elemen Aktif Mengetik: Bertumpuk di area yang sama, aria-hidden agar tidak dibaca per huruf --}}
                                <blockquote class="[grid-area:quote-stack] font-editorial italic font-medium text-lg sm:text-xl lg:text-[22px] text-[#333333] leading-[1.55]" aria-hidden="true">
                                    <span id="story-typewriter-text" data-quote="&ldquo;{{ $quoteFull }}&rdquo;">&ldquo;{{ $quoteFull }}&rdquo;</span><span id="story-typewriter-cursor" class="story-typing-cursor inline-block w-[1.5px] h-[1em] bg-[#666666] align-middle ml-0.5 hidden"></span>
                                </blockquote>
                            </div>
                        </div>

                        {{-- Right: Editorial Signature & Stamp --}}
                        <div class="flex-shrink-0 flex items-center gap-3 self-center md:self-end lg:self-center">
                            <div class="hidden lg:block w-12 h-px bg-[#CCCCCC]" aria-hidden="true"></div>
                            <div class="text-right select-none">
                                <span class="block text-[11px] font-bold tracking-[0.2em] text-[#666666] uppercase font-body">NUCOMU</span>
                                <span class="block text-[10px] font-medium tracking-[0.16em] text-[#999999] uppercase font-body">EST. 2024</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- SUBSECTION 2B: FASILITAS CAFE (MODERN ELEGANT MONOCHROME — FLOATING GALLERY) -->
        <div id="facilities" class="pt-14 sm:pt-18 lg:pt-22 border-t border-neutral-200">
            
            <!-- 1. Introduction Header (Minimal Editorial Split) -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-12 pb-6 sm:pb-8 border-b border-neutral-200 gap-4 reveal-fade-up">
                <div>
                    <span class="text-[11px] font-semibold text-[#666666] uppercase tracking-[0.22em] font-body block mb-2">
                        OUR SPACE / FASILITAS NUCOMU
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-[#1C1C1C] tracking-tight font-body leading-tight">
                        Setiap Sudut, Punya Cerita.
                    </h2>
                </div>
                <p class="font-body text-xs sm:text-sm text-[#555555] max-w-md font-normal leading-relaxed">
                    Dirancang dengan cermat untuk kenyamanan bekerja, kehangatan bercengkerama, hingga ketenangan beribadah.
                </p>
            </div>

            <!-- 2. Interactive Floating Gallery (Carousel Fasilitas Dinamis) -->
            @include('home.sections.facilities.gallery')

        </div>

    </div>
</section>

<!-- SCRIPT: ABOUT SECTION 4 VIDEO SEQUENCER (FADE INSTAN TANPA ANIMASI KETIK) -->
<script>
    (function() {
        const aboutSection = document.getElementById('about');
        const backplate = document.getElementById('about-backplate');
        const container = document.getElementById('about-video-container');
        const labelEl = document.getElementById('about-slide-label');
        const headlineEl = document.getElementById('about-slide-headline');
        const descEl = document.getElementById('about-slide-description');
        const badgeTextEl = document.getElementById('about-video-badge-text');

        if (!aboutSection || !descEl) return;

        const videos = container ? Array.from(container.querySelectorAll('.about-slide-video')) : [];

        // Data 4 video animasi Nucomu Cafe dengan warna background monokrom netral, headline & keterangan
        const slides = [
            {
                label: 'SIGNATURE ARTISANAL DESSERT',
                headline: 'Classic Italian<br>Tiramisu Nucomu',
                text: 'Lapisan ladyfingers berpadu espresso Sendang Tulungagung, krim mascarpone lembut, dan taburan cocoa powder murni pilihan untuk momen santai yang memikat rasa.',
                badge: 'Menu 01 • Tiramisu',
                bgColor: '#F9F9F9',
                backplateColor: '#9E9E9E'
            },
            {
                label: 'BEST SELLER ARTISANAL DAIRY',
                headline: 'Basque Burnt<br>Cheesecake',
                text: 'Creamy baked basque cheesecake dengan burnt caramelized top yang gurih harum dan filling vanilla beans lumer di lidah, favorit sepanjang masa di Nucomu Cafe.',
                badge: 'Menu 02 • Cheesecake',
                bgColor: '#F7F7F7',
                backplateColor: '#969696'
            },
            {
                label: 'HEARTY ITALIAN COMFORT MEAL',
                headline: 'Spaghetti Aglio<br>Olio Con Peperoncino',
                text: 'Pasta spaghetti al dente berpadu tumisan bawang putih aromatik, cabai kering harum, minyak zaitun extra virgin, dan taburan peterseli segar untuk santap hangat yang lezat.',
                badge: 'Menu 03 • Spaghetti',
                bgColor: '#F8F8F8',
                backplateColor: '#A0A0A0'
            },
            {
                label: 'AUTHENTIC SPECIALTY COFFEE',
                headline: 'Es Kopi Susu<br>Gula Aren Nucomu',
                text: 'Racikan espresso murni dari biji kopi arabika lereng Gunung Wilis Sendang Tulungagung, berpadu susu segar gurih dingin dan sirup gula aren organik yang memanjakan hari Anda.',
                badge: 'Menu 04 • Kopi Susu',
                bgColor: '#F6F6F6',
                backplateColor: '#9B9B9B'
            }
        ];

        let currentIndex = 0;
        let slideTimer = null;
        let isVisible = true;

        function clearTimers() {
            if (slideTimer) {
                clearTimeout(slideTimer);
                slideTimer = null;
            }
        }

        function renderSlide(index) {
            clearTimers();
            const slide = slides[index];
            if (!slide) return;

            // 1. Ubah warna background section About & Backplate secara halus
            aboutSection.style.backgroundColor = slide.bgColor;
            if (backplate) {
                backplate.style.backgroundColor = slide.backplateColor;
            }

            // 2. Ganti video animasi dengan crossfade halus
            videos.forEach((video, idx) => {
                if (idx === index) {
                    video.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                    video.classList.add('opacity-100', 'z-10');
                    video.currentTime = 0;
                    if (isVisible && !document.hidden) {
                        video.play().catch(function() {});
                    }
                } else {
                    video.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                    video.classList.remove('opacity-100', 'z-10');
                    video.pause();
                }
            });

            // 3. Update Badge dan Label
            if (badgeTextEl) {
                badgeTextEl.textContent = slide.badge;
            }
            if (labelEl) {
                labelEl.textContent = slide.label;
            }

            // 4. Update Headline dan Deskripsi: Langsung tampil utuh dengan transisi fade singkat (0.25 - 0.35 dtk)
            if (headlineEl) {
                headlineEl.style.opacity = '0';
            }
            if (descEl) {
                descEl.style.opacity = '0';
            }

            setTimeout(function() {
                if (headlineEl) {
                    headlineEl.innerHTML = slide.headline;
                    headlineEl.style.opacity = '1';
                }
                if (descEl) {
                    descEl.textContent = slide.text;
                    descEl.style.opacity = '1';
                }
            }, 100);

            // 5. Pergantian slide berikutnya (5.5 detik)
            slideTimer = setTimeout(function() {
                currentIndex = (currentIndex + 1) % slides.length;
                renderSlide(currentIndex);
            }, 5500);
        }

        // Jalankan IntersectionObserver agar animasi hanya berjalan saat user melihat section About
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    isVisible = entry.isIntersecting;
                    const activeVideo = videos[currentIndex];
                    if (isVisible) {
                        if (activeVideo && activeVideo.paused) {
                            activeVideo.play().catch(function() {});
                        }
                        if (!slideTimer) {
                            renderSlide(currentIndex);
                        }
                    } else {
                        if (activeVideo && !activeVideo.paused) {
                            activeVideo.pause();
                        }
                        clearTimers();
                    }
                });
            }, { threshold: 0.15 });

            observer.observe(aboutSection);
        } else {
            renderSlide(0);
        }

        // Pause saat tab browser tidak aktif, resume saat kembali aktif
        document.addEventListener('visibilitychange', function() {
            const activeVideo = videos[currentIndex];
            if (document.hidden) {
                if (activeVideo && !activeVideo.paused) {
                    activeVideo.pause();
                }
                clearTimers();
            } else {
                if (isVisible) {
                    if (activeVideo && activeVideo.paused) {
                        activeVideo.play().catch(function() {});
                    }
                    if (!slideTimer) {
                        renderSlide(currentIndex);
                    }
                }
            }
        });
    })();
</script>
