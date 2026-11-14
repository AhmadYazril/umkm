<!-- SECTION 2: ABOUT NUCOMU CAFE -->
<section id="about" 
         class="relative pt-16 sm:pt-20 lg:pt-28 pb-20 sm:pb-28 lg:pb-32 overflow-hidden border-b border-[var(--color-about-divider,#E5E5E5)]"
         style="background-color: var(--color-about-bg, #FAFAFA); transition: background-color 0.8s ease;">

    <style>
        #about {
            --color-about-bg: #FAFAFA;
            --color-about-backplate: #A5A5A5;
            --color-about-badge-dot: #242424;
            --color-about-label: #737373;
            --color-about-divider: #E5E5E5;
            --color-about-focus-ring: #242424;
            --color-about-title: #1C1C1C;
            --color-about-meta: #737373;
            --color-about-body: #666666;
            --color-about-quote-bg: #F2F2F2;
            --color-about-quote-border: #E5E5E5;
            --color-about-quote-text: #333333;
            --color-about-quote-muted: #6E6E6E;
        }
    </style>

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
            .story-typing-cursor,
            #story-typewriter-cursor {
                display: none !important;
            }
        </style>
    </noscript>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- SUBSECTION 2A: OUR STORY / ABOUT NUCOMU (3D ROOM TOUR VIDEO & CAFE PROFILE) -->
        <div id="our-story-section" class="mb-20 sm:mb-24 lg:mb-28">
            {{-- TWO-COLUMN MAIN CONTENT --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 xl:gap-20 items-center">

                {{-- KOLOM KIRI: ELEGANT LAYERED FLOATING FRAME DENGAN VIDEO ANIMASI ROOM TOUR 3D NUCOMU --}}
                <div class="lg:col-span-6 flex justify-center lg:justify-start">
                    <div class="group/story-card relative w-full max-w-[440px] sm:max-w-[470px] lg:max-w-[490px] xl:max-w-[520px] p-4 sm:p-5 lg:p-6">

                        {{-- 1. Lapisan Belakang (Back Plate): Monochrome backplate tone, Rounded Corners, Rotasi -2.6 Derajat --}}
                        <div id="about-backplate" 
                             class="story-anim-deco absolute inset-0 bg-[var(--color-about-backplate,#A5A5A5)] rounded-[28px] sm:rounded-[34px] lg:rounded-[40px] pointer-events-none z-0 shadow-md origin-center transform -rotate-2 sm:-rotate-[2.6deg]"
                             aria-hidden="true"></div>

                        {{-- 2. Frame Media Nucomu Cafe (Video Animasi Room Tour 3D) --}}
                        <div id="about-video-container"
                             class="story-anim-photo relative z-10 w-full rounded-[18px] sm:rounded-[22px] lg:rounded-[24px] overflow-hidden border-[3.5px] sm:border-4 border-white shadow-[0_20px_45px_-10px_rgba(0,0,0,0.22)] bg-neutral-900 select-none aspect-video sm:aspect-video lg:aspect-[16/10]">
                            
                            {{-- Video Room Tour 3D --}}
                            <video id="about-room-tour-video"
                                   class="absolute inset-0 w-full h-full object-cover"
                                   muted
                                   loop
                                   playsinline
                                   preload="metadata">
                                <source src="{{ asset('videos/room-tour.mp4') }}" type="video/mp4">
                            </video>

                            {{-- Badge Tag Dinamis di Pojok Kiri Bawah --}}
                            <div id="about-video-badge" class="absolute bottom-3 left-3 z-30 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-full text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[var(--color-about-title,#1C1C1C)] border border-white/60 shadow-sm flex items-center space-x-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--color-about-badge-dot,#242424)] animate-pulse"></span>
                                <span id="about-video-badge-text">ROOM TOUR 3D</span>
                            </div>

                        </div>

                        {{-- 3. Badge Logo Resmi Nucomu (Sudut Kiri Atas Frame) --}}
                        <div class="story-anim-badge-logo absolute top-1.5 left-1.5 sm:top-2 sm:left-2 lg:top-2.5 lg:left-2.5 z-20 w-[68px] h-[68px] sm:w-[76px] sm:h-[76px] lg:w-[82px] lg:h-[82px] bg-[#E8E8E8] rounded-[16px] sm:rounded-[18px] shadow-[0_10px_25px_-6px_rgba(0,0,0,0.22)] flex flex-col items-center justify-center p-2 border border-white/95 select-none" title="Nucomu Cafe Tulungagung">
                            <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" 
                                 alt="Logo Nucomu Cafe" 
                                 class="h-6 sm:h-7 lg:h-8 w-auto object-contain brightness-0">
                            <span class="text-[7.5px] sm:text-[8.5px] font-extrabold tracking-[0.2em] text-[#1C1C1C] uppercase mt-1 font-body">NUCOMU</span>
                        </div>

                        {{-- 4. Tombol Play/Pause Bulat Interaktif (Sudut Kanan Bawah Frame) --}}
                        <button id="about-video-play-btn"
                                type="button"
                                aria-label="Jeda Video Room Tour 3D"
                                title="Putar / Jeda Video"
                                class="story-anim-badge-coffee absolute bottom-1.5 right-1.5 sm:bottom-2 sm:right-2 lg:bottom-2.5 lg:right-2.5 z-20 w-[54px] h-[54px] sm:w-[60px] sm:h-[60px] lg:w-[66px] lg:h-[66px] bg-[#242424] hover:bg-[#383838] active:scale-90 transition-all rounded-full shadow-[0_12px_28px_-6px_rgba(0,0,0,0.36)] flex items-center justify-center text-white border-[3px] border-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-[var(--color-about-focus-ring,#242424)]/40">
                            {{-- Ikon Pause (aktif saat memutar) --}}
                            <svg id="about-icon-pause" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                            {{-- Ikon Play (aktif saat dijeda) --}}
                            <svg id="about-icon-play" class="w-5 h-5 text-white ml-0.5 hidden" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </button>

                    </div>
                </div>

                {{-- KOLOM KANAN: EYEBROW, HEADLINE EDITORIAL & PARAGRAF PROFIL (STATIS SERVER-SIDE) --}}
                <div class="lg:col-span-6 flex flex-col justify-center text-left lg:pl-2">

                    {{-- 1. Eyebrow Label dengan Garis Aksen Tipis --}}
                    <div class="story-anim-right story-delay-1 flex items-center gap-3 mb-4 sm:mb-5">
                        <span class="text-xs sm:text-[13px] font-bold text-[var(--color-about-label,#737373)] uppercase tracking-[0.22em] font-body">
                            {{ $brand['about_label'] ?? 'TENTANG KAMI' }}
                        </span>
                        <span class="sr-only">{{ $brand['about_label'] ?? 'OUR STORY / TENTANG KAMI' }}</span>
                        <div class="w-12 h-px bg-[var(--color-about-divider,#E5E5E5)]" aria-hidden="true"></div>
                    </div>

                    {{-- 2. Headline Utama: Modern Minimalist Manrope Semibold (600) Statis Server-Side --}}
                    <div class="story-anim-right story-delay-2 mb-5 flex items-center">
                        <h2 class="font-body text-3xl sm:text-4xl lg:text-[40px] xl:text-[44px] font-semibold text-[var(--color-about-title,#1C1C1C)] leading-[1.18] tracking-tight">
                            <span id="about-headline">{{ !empty($brand['cafe_story_headline']) ? $brand['cafe_story_headline'] : 'Nucomu Cafe' }}</span>
                        </h2>
                        <div class="sr-only">
                            <h2>{{ $brand['story_headline'] ?? ($brand['cafe_story_headline'] ?? 'Berawal dari Rasa, Kembali karena Rindu.') }}</h2>
                            <p>Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.</p>
                            <p>Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.</p>
                            <p>Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.</p>
                            <img src="{{ asset('images/nucomu_story_1.png') }}" alt="Nucomu Cafe Story" />
                        </div>
                    </div>

                    {{-- 3. Paragraf Deskripsi Profil Nucomu Cafe (Statis Server-Side) --}}
                    <div class="space-y-4 max-w-xl text-[15px] sm:text-base text-[var(--color-about-body,#666666)] leading-[1.8] font-body">
                        <p class="story-anim-right story-delay-3">
                            {{ $brand['cafe_story_p1'] ?? 'Kafe adalah tempat bersantai untuk menikmati kopi, makanan ringan, dan dessert sambil bertemu teman, bekerja, atau belajar. Nucomu Cafe (Coffee & Dessert) hadir dengan konsep itu: ruang yang nyaman dan bersih, kopi yang diracik segar, dan dessert andalan seperti cheesecake, tiramisu, donut butter cream, dan banana toffee.' }}
                        </p>
                        <p class="story-anim-right story-delay-4">
                            {{ $brand['cafe_story_p2'] ?? 'Terletak di Plosokandang, Tulungagung, Nucomu punya area indoor dan outdoor yang luas, cocok untuk mahasiswa, nugas, atau WFC. Fasilitas lengkap: WiFi, toilet, dan mushola. Find your favorite taste.' }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- QUOTE SECTION: MINIMALIST EDITORIAL QUOTE CARD --}}
            <div id="story-quote-wrapper" class="mt-16 sm:mt-20 lg:mt-24">
                <div class="relative bg-[var(--color-about-quote-bg,#F2F2F2)] backdrop-blur-sm border border-[var(--color-about-quote-border,#E5E5E5)] rounded-[24px] sm:rounded-[28px] p-6 sm:p-8 lg:p-9 shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all duration-300 hover:border-[#D4D4D4]">
                    
                    <div class="flex flex-col md:flex-row items-center md:items-start lg:items-center justify-between gap-6">

                        {{-- Left Quote Icon Indicator --}}
                        <div class="flex-shrink-0 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-[var(--color-about-quote-border,#E5E5E5)] flex items-center justify-center text-[#1C1C1C] shadow-sm">
                                <span class="font-editorial text-3xl font-bold leading-none select-none">&ldquo;</span>
                            </div>
                            <div class="hidden sm:block w-px h-10 bg-[var(--color-about-divider,#E5E5E5)]" aria-hidden="true"></div>
                        </div>

                        {{-- Center: Quote Typography with Screen Reader & Typewriter Fallback --}}
                        @php
                            $quoteText = $brand['about_quote'] ?? 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.';
                        @endphp
                        <div class="flex-grow text-center md:text-left px-1 sm:px-3">
                            <p class="sr-only">
                                &ldquo;{{ $quoteText }}&rdquo;
                            </p>
                            <div class="relative">
                                {{-- Phantom text for zero layout shift (card height remains rock-solid while typing) --}}
                                <p class="font-editorial italic font-medium text-lg sm:text-xl lg:text-[22px] leading-[1.55] invisible select-none pointer-events-none" aria-hidden="true">
                                    &ldquo;{{ $quoteText }}&rdquo;
                                </p>
                                <blockquote class="absolute inset-0 font-editorial italic font-medium text-lg sm:text-xl lg:text-[22px] text-[var(--color-about-quote-text,#333333)] leading-[1.55]">
                                    <span id="story-typewriter-text" data-quote="&ldquo;{{ $quoteText }}&rdquo;">&ldquo;{{ $quoteText }}&rdquo;</span><span id="story-typewriter-cursor" class="inline-block w-[2px] h-[0.9em] bg-[#1C1C1C] align-middle ml-1 animate-pulse" aria-hidden="true"></span>
                                </blockquote>
                            </div>
                        </div>

                        {{-- Right: Editorial Signature & Stamp --}}
                        <div class="flex-shrink-0 flex items-center gap-3 self-center md:self-end lg:self-center">
                            <div class="hidden lg:block w-12 h-px bg-[var(--color-about-divider,#E5E5E5)]" aria-hidden="true"></div>
                            <div class="text-right select-none">
                                <span class="block text-[11px] font-bold tracking-[0.2em] text-[var(--color-about-body,#666666)] uppercase font-body">NUCOMU CAFE</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- SUBSECTION 2B: FASILITAS CAFE (MODERN ELEGANT MONOCHROME — FLOATING GALLERY) -->
        <div id="facilities" class="pt-14 sm:pt-18 lg:pt-22 border-t border-[var(--color-about-divider,#E5E5E5)]">
            
            <!-- 1. Introduction Header (Minimal Editorial Split) -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-12 pb-6 sm:pb-8 border-b border-[var(--color-about-divider,#E5E5E5)] gap-4 reveal-fade-up">
                <div>
                    <span class="text-[11px] font-semibold text-[var(--color-about-meta,#737373)] uppercase tracking-[0.22em] font-body block mb-2">
                        OUR SPACE / FASILITAS NUCOMU
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-[var(--color-about-title,#1C1C1C)] tracking-tight font-body leading-tight">
                        Setiap Sudut, Punya Cerita.
                    </h2>
                </div>
                <p class="font-body text-xs sm:text-sm text-[var(--color-about-body,#666666)] max-w-md font-normal leading-relaxed">
                    Dirancang dengan cermat untuk kenyamanan bekerja, kehangatan bercengkerama, hingga ketenangan beribadah.
                </p>
            </div>

            <!-- 2. Interactive Floating Gallery (Carousel Fasilitas Dinamis) -->
            @include('home.sections.facilities.gallery')

        </div>

    </div>
</section>

<!-- SCRIPT: ABOUT SECTION 3D ROOM TOUR VIDEO CONTROLLER -->
<script>
    (function() {
        const video = document.getElementById('about-room-tour-video');
        const playBtn = document.getElementById('about-video-play-btn');
        const iconPlay = document.getElementById('about-icon-play');
        const iconPause = document.getElementById('about-icon-pause');
        const aboutCard = document.getElementById('about-video-container');
        const storySection = document.getElementById('our-story-section') || aboutCard;

        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let isVisible = false;
        let isManuallyPaused = false;

        function updateUI(isPlaying) {
            if (iconPlay && iconPause) {
                if (isPlaying) {
                    iconPlay.classList.add('hidden');
                    iconPause.classList.remove('hidden');
                    if (playBtn) playBtn.setAttribute('aria-label', 'Jeda Video Room Tour 3D');
                } else {
                    iconPlay.classList.remove('hidden');
                    iconPause.classList.add('hidden');
                    if (playBtn) playBtn.setAttribute('aria-label', 'Putar Video Room Tour 3D');
                }
            }
        }

        function playVideo() {
            if (!video) return;
            if (video.paused) {
                video.play().then(function() {
                    updateUI(true);
                }).catch(function() {
                    updateUI(false);
                });
            } else {
                updateUI(true);
            }
        }

        function pauseVideo() {
            if (!video) return;
            if (!video.paused) {
                video.pause();
            }
            updateUI(false);
        }

        if (playBtn && video) {
            playBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (video.paused) {
                    isManuallyPaused = false;
                    playVideo();
                } else {
                    isManuallyPaused = true;
                    pauseVideo();
                }
            });
        }

        if (video) {
            video.addEventListener('play', function() { updateUI(true); });
            video.addEventListener('pause', function() { updateUI(false); });
        }

        // IntersectionObserver: Autoplay video saat masuk layar & pause saat keluar
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    isVisible = entry.isIntersecting;
                    if (isVisible) {
                        if (!prefersReduced && !isManuallyPaused && !document.hidden) {
                            playVideo();
                        }
                    } else {
                        pauseVideo();
                    }
                });
            }, { threshold: 0.2 });

            observer.observe(storySection || aboutCard);
        } else {
            if (!prefersReduced) playVideo();
        }

        // Pause saat tab browser tidak aktif, resume saat kembali aktif
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                pauseVideo();
            } else if (isVisible && !isManuallyPaused && !prefersReduced) {
                playVideo();
            }
        });
    })();
</script>
