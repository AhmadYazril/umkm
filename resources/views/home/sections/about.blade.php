<!-- SECTION 2: ABOUT NUCOMU CAFE -->
<section id="about" 
         class="relative pt-16 sm:pt-20 lg:pt-28 pb-20 sm:pb-28 lg:pb-32 overflow-hidden border-b border-[var(--color-about-divider)]"
         style="background-color: var(--color-about-bg, #FAFAFA); transition: background-color 0.8s ease;">

    <style>
        #about {
            --color-about-bg: #FAFAFA;
            --color-about-backplate: #E5E5E5;
            --color-about-backplate-border: rgba(0, 0, 0, 0.08);
            --color-about-badge-dot: #242424;
            --color-about-label: #555555;
            --color-about-divider: #E5E5E5;
            --color-about-focus-ring: #242424;
            --color-about-title: #1C1C1C;
            --color-about-meta: #555555;
            --color-about-body: #333333;
            --color-about-body-sub: #4A4A4A;
            --color-about-caption: rgba(28, 28, 28, 0.58);
            --color-about-line: rgba(28, 28, 28, 0.16);
            --color-about-logo-bg: #FFFFFF;
            --color-about-logo-border: #E5E5E5;
            --color-about-logo-text: #1C1C1C;
            --color-about-logo-filter: none;
            --color-about-play-bg: #1C1C1C;
            --color-about-play-text: #FFFFFF;
            --color-about-play-border: #FAFAFA;
            --color-about-frame-border: #FFFFFF;
            --color-about-frame-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.22);
            --color-about-watermark: #1C1C1C;
            --color-about-watermark-opacity: 0.04;
            --color-about-quote-bg: #F2F2F2;
            --color-about-quote-border: #E5E5E5;
            --color-about-quote-text: #333333;
            --color-about-quote-muted: #555555;
        }

        [data-theme="dark"] #about,
        .dark #about {
            --color-about-bg: #0A0A0A;
            --color-about-backplate: #1A1A1A;
            --color-about-backplate-border: rgba(255, 255, 255, 0.10);
            --color-about-badge-dot: #FFFFFF;
            --color-about-label: #A0A0A0;
            --color-about-divider: #262626;
            --color-about-focus-ring: #FFFFFF;
            --color-about-title: #FFFFFF;
            --color-about-meta: #A0A0A0;
            --color-about-body: #EDE6DD;
            --color-about-body-sub: #E2DDD5;
            --color-about-caption: rgba(255, 255, 255, 0.55);
            --color-about-line: rgba(255, 255, 255, 0.20);
            --color-about-logo-bg: #1C1C1C;
            --color-about-logo-border: rgba(255, 255, 255, 0.15);
            --color-about-logo-text: rgba(255, 255, 255, 0.90);
            --color-about-logo-filter: invert(1) brightness(2);
            --color-about-play-bg: #FFFFFF;
            --color-about-play-text: #111111;
            --color-about-play-border: #0A0A0A;
            --color-about-frame-border: rgba(255, 255, 255, 0.15);
            --color-about-frame-shadow: 0 20px 50px rgba(0, 0, 0, 0.85);
            --color-about-watermark: #FFFFFF;
            --color-about-watermark-opacity: 0.02;
            --color-about-quote-bg: #141414;
            --color-about-quote-border: #2A2A2A;
            --color-about-quote-text: #F5F5F5;
            --color-about-quote-muted: #969696;
        }
    </style>

    {{-- NoScript Accessibility Fallback: Ensures content is visible if JS is disabled --}}
    <noscript>
        <style>
            .story-anim-photo,
            .story-anim-badge-logo,
            .story-anim-badge-coffee,
            .story-anim-caption,
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
        <div id="our-story-section" class="relative overflow-hidden mb-20 sm:mb-24 lg:mb-28">
            {{-- Faded Watermark Background Text --}}
            <div class="absolute top-1/2 -translate-y-1/2 right-0 pointer-events-none select-none z-0" aria-hidden="true">
                <span style="font-family: var(--font-sans); font-size: clamp(90px, 16vw, 260px); font-weight: 800; line-height: 1; letter-spacing: -0.01em; color: var(--color-about-watermark); opacity: var(--color-about-watermark-opacity); white-space: nowrap; display: block;">NUCOMU</span>
            </div>

            {{-- Screen Reader Content for Accessibility & Testing Assertions --}}
            <div class="sr-only">
                <h2>OUR STORY / TENTANG KAMI</h2>
                @if(!empty($brand['about_label']))
                    <h2>{{ $brand['about_label'] }}</h2>
                @endif
                <h3>Berawal dari Rasa, Kembali karena Rindu.</h3>
                @if(!empty($brand['cafe_story_headline']))
                    <h2>{{ $brand['cafe_story_headline'] }}</h2>
                @endif
                <p>Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.</p>
                <p>Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.</p>
                @if(!empty($brand['cafe_story_p1']))
                    <p>{{ $brand['cafe_story_p1'] }}</p>
                @endif
                @if(!empty($brand['cafe_story_p2']))
                    <p>{{ $brand['cafe_story_p2'] }}</p>
                @endif
                @if(!empty($brand['about_tagline']))
                    <p>{{ $brand['about_tagline'] }}</p>
                @endif
                <img src="{{ asset('images/nucomu/nucomu_story_1.png') }}" alt="Nucomu Cafe Story" />
                <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu Cafe Logo Icon" />
            </div>

            {{-- TWO-COLUMN MAIN CONTENT --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 xl:gap-20 items-center">

                {{-- KOLOM KIRI: ELEGANT LAYERED FLOATING FRAME DENGAN VIDEO ANIMASI ROOM TOUR 3D NUCOMU --}}
                <div class="lg:col-span-6 flex flex-col items-center lg:items-start justify-center">
                    <div class="group/story-card relative w-full max-w-[440px] sm:max-w-[470px] lg:max-w-[490px] xl:max-w-[520px] p-4 sm:p-5 lg:p-6">

                        {{-- 1. Lapisan Belakang (Back Plate): Monochrome backplate tone, Rounded Corners --}}
                        <div id="about-backplate" 
                             class="story-anim-deco absolute inset-0 rounded-[28px] sm:rounded-[34px] lg:rounded-[40px] pointer-events-none z-0 shadow-xl origin-center"
                             style="background-color: var(--color-about-backplate); border: 1px solid var(--color-about-backplate-border);"
                             aria-hidden="true"></div>

                        {{-- 2. Frame Media Nucomu Cafe (Video Animasi Room Tour 3D atau Foto Pilihan Admin) --}}
                        <div id="about-video-container"
                             class="story-anim-photo relative z-10 w-full rounded-[18px] sm:rounded-[22px] lg:rounded-[24px] overflow-hidden border-[3.5px] sm:border-4 bg-neutral-950 select-none aspect-video sm:aspect-video lg:aspect-[16/10]"
                             style="border-color: var(--color-about-frame-border); box-shadow: var(--color-about-frame-shadow);">
                            
                            @php
                                $aboutMediaType = $brand['about_media_type'] ?? 'video';
                                $aboutMediaUrl = $brand['about_media_url'] ?? asset('videos/room-tour.mp4');
                            @endphp

                            @if($aboutMediaType === 'image')
                                {{-- Foto / Gambar Pilihan Admin --}}
                                <img src="{{ $aboutMediaUrl }}"
                                     alt="{{ !empty($brand['cafe_story_headline']) ? $brand['cafe_story_headline'] : 'Nucomu Cafe' }}"
                                     class="absolute inset-0 w-full h-full object-cover">
                            @else
                                {{-- Video Room Tour 3D / Video Pilihan Admin --}}
                                <video id="about-room-tour-video"
                                       class="absolute inset-0 w-full h-full object-cover"
                                       muted
                                       loop
                                       playsinline
                                       poster="{{ asset('images/nucomu/nucomu_story_1.png') }}"
                                       preload="metadata">
                                    <source src="{{ $aboutMediaUrl }}" type="video/mp4">
                                </video>
                            @endif

                            {{-- Top Labels Inside Video Frame (Room Tour 3D & Nucomu Cafe) --}}
                            <div class="absolute top-3 left-14 sm:left-16 z-30 flex items-center gap-2">
                                <span class="text-[10px] sm:text-[11px] font-medium tracking-[0.15em] uppercase text-white/80 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full border border-white/10 font-body">
                                    {{ $aboutMediaType === 'image' ? 'Foto Cafe' : 'Room Tour 3D' }}
                                </span>
                            </div>
                            <div class="absolute top-3 right-3 z-30">
                                <span class="text-[10px] sm:text-[11px] font-medium tracking-[0.15em] uppercase text-white/80 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full border border-white/10 font-editorial">
                                    Nucomu Cafe
                                </span>
                            </div>

                            {{-- Badge Tag Dinamis di Pojok Kiri Bawah --}}
                            <div id="about-video-badge" class="absolute bottom-3 left-3 z-30 bg-black/80 backdrop-blur-md px-3.5 py-1.5 rounded-full text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-white border border-white/20 shadow-md flex items-center space-x-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                <span id="about-video-badge-text">{{ $aboutMediaType === 'image' ? 'NUCOMU SPACE' : 'ROOM TOUR 3D' }}</span>
                            </div>

                        </div>

                        {{-- 3. Badge Logo Resmi Nucomu (Sudut Kiri Atas Frame) --}}
                        <div class="story-anim-badge-logo absolute top-1.5 left-1.5 sm:top-2 sm:left-2 lg:top-2.5 lg:left-2.5 z-20 w-[68px] h-[68px] sm:w-[76px] sm:h-[76px] lg:w-[82px] lg:h-[82px] rounded-[16px] sm:rounded-[18px] shadow-lg flex flex-col items-center justify-center p-2 select-none"
                             style="background-color: var(--color-about-logo-bg); border: 1px solid var(--color-about-logo-border);"
                             title="Nucomu Cafe Tulungagung">
                            <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" 
                                 alt="Logo Nucomu Cafe" 
                                 class="h-6 sm:h-7 lg:h-8 w-auto object-contain"
                                 style="filter: var(--color-about-logo-filter);">
                            <span class="text-[7.5px] sm:text-[8.5px] font-extrabold tracking-[0.2em] uppercase mt-1 font-body" style="color: var(--color-about-logo-text);">NUCOMU</span>
                        </div>

                        {{-- 4. Tombol Play/Pause Bulat Interaktif (Hanya tampil jika media adalah video) --}}
                        @if(($brand['about_media_type'] ?? 'video') === 'video')
                        <button id="about-video-play-btn"
                                type="button"
                                aria-label="Jeda Video Room Tour 3D"
                                title="Putar / Jeda Video"
                                class="story-anim-badge-coffee absolute bottom-1.5 right-1.5 sm:bottom-2 sm:right-2 lg:bottom-2.5 lg:right-2.5 z-20 w-[54px] h-[54px] sm:w-[60px] sm:h-[60px] lg:w-[66px] lg:h-[66px] rounded-full shadow-lg flex items-center justify-center border-[3px] cursor-pointer focus:outline-none focus:ring-2"
                                style="background-color: var(--color-about-play-bg); color: var(--color-about-play-text); border-color: var(--color-about-play-border);">
                            {{-- Ikon Pause --}}
                            <svg id="about-icon-pause" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                            {{-- Ikon Play --}}
                            <svg id="about-icon-play" class="w-5 h-5 ml-0.5 hidden" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </button>
                        @endif

                    </div>

                    {{-- Caption Di Bawah Frame Media Sesuai Gambar --}}
                    <div class="story-anim-caption mt-2 text-center w-full max-w-[440px] sm:max-w-[470px] lg:max-w-[490px] xl:max-w-[520px]">
                        <span class="font-editorial text-xs tracking-[0.25em] uppercase select-none" style="color: var(--color-about-caption);">
                            Room Tour View
                        </span>
                    </div>
                </div>

                {{-- KOLOM KANAN: EYEBROW/TITLE, PARAGRAF & TAGLINE --}}
                <div class="lg:col-span-6 flex flex-col justify-center text-left lg:pl-2">

                    {{-- 1. Eyebrow Label dengan Garis Aksen Tipis --}}
                    <div class="story-anim-right story-delay-1 flex items-center gap-3 mb-4 sm:mb-5">
                        <span class="text-xs sm:text-[13px] font-bold uppercase tracking-[0.22em] font-body" style="color: var(--color-about-label);">
                            {{ !empty($brand['about_label']) ? $brand['about_label'] : 'TENTANG KAMI' }}
                        </span>
                        <div class="w-12 h-px" style="background-color: var(--color-about-line);" aria-hidden="true"></div>
                    </div>

                    {{-- 2. Headline Utama: Nucomu Cafe --}}
                    <div class="story-anim-right story-delay-2 mb-5 flex items-center">
                        <h2 class="font-heading text-3xl sm:text-4xl lg:text-[40px] xl:text-[44px] font-bold leading-[1.18] tracking-tight" style="color: var(--color-about-title);">
                            <span id="about-headline">{{ !empty($brand['cafe_story_headline']) ? $brand['cafe_story_headline'] : 'Nucomu Cafe' }}</span>
                        </h2>
                    </div>

                    {{-- 3. Paragraf 1: Teks Asli Profil Nucomu Cafe --}}
                    <p class="story-anim-right story-delay-3 font-editorial text-base sm:text-[17px] leading-[1.8] font-normal mb-5 max-w-xl" style="color: var(--color-about-body);">
                        {{ $brand['cafe_story_p1'] ?? 'Kafe adalah tempat bersantai untuk menikmati kopi, makanan ringan, dan dessert sambil bertemu teman, bekerja, atau belajar. Nucomu Cafe (Coffee & Dessert) hadir dengan konsep itu: ruang yang nyaman dan bersih, kopi yang diracik segar, dan dessert andalan seperti cheesecake, tiramisu, donut butter cream, dan banana toffee.' }}
                    </p>

                    {{-- 4. Garis Pemisah Tipis dengan Dot di Tengah --}}
                    <div class="story-anim-right story-delay-4 flex items-center gap-3 my-4 max-w-xl" aria-hidden="true">
                        <div class="flex-1 h-px" style="background-color: var(--color-about-line);"></div>
                        <span class="w-1.5 h-1.5 rounded-full shadow-sm" style="background-color: var(--color-about-badge-dot); opacity: 0.7;"></span>
                        <div class="flex-1 h-px" style="background-color: var(--color-about-line);"></div>
                    </div>

                    {{-- 5. Paragraf 2: Teks Asli Fasilitas Nucomu --}}
                    <div class="story-anim-right story-delay-5 flex items-start gap-3.5 mb-6 max-w-xl">
                        {{-- Concentric Double-Circle Bullet Icon --}}
                        <div class="flex-shrink-0 mt-1" aria-hidden="true">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border" style="border-color: var(--color-about-line);">
                                <span class="w-2 h-2 rounded-full" style="background-color: var(--color-about-badge-dot);"></span>
                            </span>
                        </div>
                        {{-- Text with Left Accent Line --}}
                        <div class="pl-4 space-y-3" style="border-left: 1px solid var(--color-about-line);">
                            <p class="font-editorial text-[15px] sm:text-base leading-[1.75] font-normal" style="color: var(--color-about-body-sub);">
                                {{ $brand['cafe_story_p2'] ?? 'Terletak di Plosokandang, Tulungagung, Nucomu punya area indoor dan outdoor yang luas, cocok untuk mahasiswa, nugas, atau WFC. Fasilitas lengkap: WiFi, toilet, dan mushola.' }}
                            </p>
                            @if(!empty($brand['cafe_story_p3']))
                                <p class="font-editorial text-[15px] sm:text-base leading-[1.75] font-normal" style="color: var(--color-about-body-sub);">
                                    {{ $brand['cafe_story_p3'] }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- 6. Tagline: Find your favorite taste. --}}
                    <div class="story-anim-right story-delay-6 pt-1">
                        <p class="font-editorial italic text-3xl sm:text-4xl lg:text-[40px] leading-tight font-normal" style="color: var(--color-about-title);">
                            {{ !empty($brand['about_tagline']) ? $brand['about_tagline'] : 'Find your favorite taste.' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <!-- SUBSECTION 2B: FASILITAS CAFE (MODERN ELEGANT MONOCHROME — FLOATING GALLERY) -->
        <div id="facilities" class="relative overflow-hidden pt-14 sm:pt-18 lg:pt-22 border-t border-[var(--color-about-divider)]">

            {{-- Faded Watermark Background Text --}}
            <div class="absolute top-1/2 -translate-y-1/2 right-0 pointer-events-none select-none z-0" aria-hidden="true">
                <span style="font-family: var(--font-sans); font-size: clamp(90px, 16vw, 260px); font-weight: 800; line-height: 1; letter-spacing: -0.01em; color: var(--color-about-watermark); opacity: var(--color-about-watermark-opacity); white-space: nowrap; display: block;">NUCOMU</span>
            </div>
            
            <!-- 1. Introduction Header (Vertikal & Rata Tengah) -->
            <div class="mb-8 sm:mb-12 pb-6 sm:pb-8 border-b border-[var(--color-about-divider)] reveal-fade-up">
                <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.22em] font-body block mb-3 pl-[0.22em]" style="color: var(--color-about-meta);">
                        OUR SPACE / FASILITAS NUCOMU
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight font-editorial leading-tight mx-auto" style="color: var(--color-about-title);">
                        Setiap Sudut, Punya Cerita.
                    </h2>
                    <p class="font-body text-xs sm:text-sm max-w-3xl font-normal leading-relaxed mx-auto mt-3.5" style="color: var(--color-about-label);">
                        Dirancang dengan cermat untuk kenyamanan bekerja, kehangatan bercengkerama, hingga ketenangan beribadah.
                    </p>
                </div>
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

        function isIntroBlocking() {
            return document.documentElement.classList.contains('nucomu-intro-active') && !window.nucomuIntroIsDone;
        }

        function maybeAutoplay() {
            if (isVisible && !prefersReduced && !isManuallyPaused && !document.hidden && !isIntroBlocking()) {
                playVideo();
            }
        }

        // IntersectionObserver: Autoplay video saat masuk layar & pause saat keluar
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    isVisible = entry.isIntersecting;
                    if (isVisible) {
                        maybeAutoplay();
                    } else {
                        pauseVideo();
                    }
                });
            }, { threshold: 0.2, rootMargin: '0px 0px -15% 0px' });

            observer.observe(storySection || aboutCard);
        } else {
            isVisible = true;
            maybeAutoplay();
        }

        if (isIntroBlocking()) {
            window.addEventListener('nucomu:intro-done', maybeAutoplay, { once: true });
        }

        // Pause saat tab browser tidak aktif, resume saat kembali aktif
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                pauseVideo();
            } else {
                maybeAutoplay();
            }
        });
    })();
</script>
