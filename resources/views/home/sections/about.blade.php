<!-- SECTION 2: ABOUT NUCOMU CAFE -->
<section id="about" 
         class="relative pt-16 sm:pt-20 lg:pt-28 pb-20 sm:pb-28 lg:pb-32 overflow-hidden border-b border-[#222222]"
         style="background-color: var(--color-about-bg, #0A0A0A); transition: background-color 0.8s ease;">

    <style>
        #about {
            --color-about-bg: #0A0A0A;
            --color-about-backplate: #1A1A1A;
            --color-about-badge-dot: #FFFFFF;
            --color-about-label: #A0A0A0;
            --color-about-divider: #262626;
            --color-about-focus-ring: #FFFFFF;
            --color-about-title: #FFFFFF;
            --color-about-meta: #A0A0A0;
            --color-about-body: #B0B0B0;
            --color-about-quote-bg: #141414;
            --color-about-quote-border: #2A2A2A;
            --color-about-quote-text: #F5F5F5;
            --color-about-quote-muted: #8E8E8E;
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
                <span style="font-family: var(--font-sans); font-size: clamp(90px, 16vw, 260px); font-weight: 800; line-height: 1; letter-spacing: -0.01em; color: #FFFFFF; opacity: 0.02; white-space: nowrap; display: block;">NUCOMU</span>
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

                        {{-- 1. Lapisan Belakang (Back Plate): Monochrome backplate tone, Rounded Corners, Rotasi -2.6 Derajat --}}
                        <div id="about-backplate" 
                             class="story-anim-deco absolute inset-0 bg-[#161616] border border-white/10 rounded-[28px] sm:rounded-[34px] lg:rounded-[40px] pointer-events-none z-0 shadow-2xl origin-center"
                             aria-hidden="true"></div>

                        {{-- 2. Frame Media Nucomu Cafe (Video Animasi Room Tour 3D atau Foto Pilihan Admin) --}}
                        <div id="about-video-container"
                             class="story-anim-photo relative z-10 w-full rounded-[18px] sm:rounded-[22px] lg:rounded-[24px] overflow-hidden border-[3.5px] sm:border-4 border-white/15 shadow-[0_20px_50px_rgba(0,0,0,0.85)] bg-neutral-950 select-none aspect-video sm:aspect-video lg:aspect-[16/10]">
                            
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
                        <div class="story-anim-badge-logo absolute top-1.5 left-1.5 sm:top-2 sm:left-2 lg:top-2.5 lg:left-2.5 z-20 w-[68px] h-[68px] sm:w-[76px] sm:h-[76px] lg:w-[82px] lg:h-[82px] bg-[#1C1C1C] rounded-[16px] sm:rounded-[18px] shadow-[0_12px_28px_-6px_rgba(0,0,0,0.75)] flex flex-col items-center justify-center p-2 border border-white/15 select-none" title="Nucomu Cafe Tulungagung">
                            <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" 
                                 alt="Logo Nucomu Cafe" 
                                 class="h-6 sm:h-7 lg:h-8 w-auto object-contain invert brightness-200">
                            <span class="text-[7.5px] sm:text-[8.5px] font-extrabold tracking-[0.2em] text-white/90 uppercase mt-1 font-body">NUCOMU</span>
                        </div>

                        {{-- 4. Tombol Play/Pause Bulat Interaktif (Hanya tampil jika media adalah video) --}}
                        @if(($brand['about_media_type'] ?? 'video') === 'video')
                        <button id="about-video-play-btn"
                                type="button"
                                aria-label="Jeda Video Room Tour 3D"
                                title="Putar / Jeda Video"
                                class="story-anim-badge-coffee absolute bottom-1.5 right-1.5 sm:bottom-2 sm:right-2 lg:bottom-2.5 lg:right-2.5 z-20 w-[54px] h-[54px] sm:w-[60px] sm:h-[60px] lg:w-[66px] lg:h-[66px] bg-white hover:bg-neutral-200 rounded-full shadow-[0_12px_28px_-6px_rgba(0,0,0,0.85)] flex items-center justify-center text-black border-[3px] border-[#0A0A0A] cursor-pointer focus:outline-none focus:ring-2 focus:ring-white">
                            {{-- Ikon Pause --}}
                            <svg id="about-icon-pause" class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                            {{-- Ikon Play --}}
                            <svg id="about-icon-play" class="w-5 h-5 text-black ml-0.5 hidden" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </button>
                        @endif

                    </div>

                    {{-- Caption Di Bawah Frame Media Sesuai Gambar --}}
                    <div class="story-anim-caption mt-2 text-center w-full max-w-[440px] sm:max-w-[470px] lg:max-w-[490px] xl:max-w-[520px]">
                        <span class="font-editorial text-xs tracking-[0.25em] text-white/50 uppercase select-none">
                            Room Tour View
                        </span>
                    </div>
                </div>

                {{-- KOLOM KANAN: EYEBROW/TITLE, PARAGRAF & TAGLINE (TEKS ASLI NUCOMU SEPERTI SEBELUMNYA DENGAN GAYA ELEGAN DARI GAMBAR) --}}
                <div class="lg:col-span-6 flex flex-col justify-center text-left lg:pl-2">

                    {{-- 1. Eyebrow Label dengan Garis Aksen Tipis (Seperti Sebelumnya) --}}
                    <div class="story-anim-right story-delay-1 flex items-center gap-3 mb-4 sm:mb-5">
                        <span class="text-xs sm:text-[13px] font-bold text-white/70 uppercase tracking-[0.22em] font-body">
                            {{ !empty($brand['about_label']) ? $brand['about_label'] : 'TENTANG KAMI' }}
                        </span>
                        <div class="w-12 h-px bg-white/20" aria-hidden="true"></div>
                    </div>

                    {{-- 2. Headline Utama: Nucomu Cafe (Seperti Sebelumnya) --}}
                    <div class="story-anim-right story-delay-2 mb-5 flex items-center">
                        <h2 class="font-heading text-3xl sm:text-4xl lg:text-[40px] xl:text-[44px] font-bold text-white leading-[1.18] tracking-tight">
                            <span id="about-headline">{{ !empty($brand['cafe_story_headline']) ? $brand['cafe_story_headline'] : 'Nucomu Cafe' }}</span>
                        </h2>
                    </div>

                    {{-- 3. Paragraf 1: Teks Asli Profil Nucomu Cafe (Seperti Sebelumnya, dengan Gaya Tipografi Serif Hangat) --}}
                    <p class="story-anim-right story-delay-3 font-editorial text-base sm:text-[17px] text-[#EDE6DD] leading-[1.8] font-normal mb-5 max-w-xl">
                        {{ $brand['cafe_story_p1'] ?? 'Kafe adalah tempat bersantai untuk menikmati kopi, makanan ringan, dan dessert sambil bertemu teman, bekerja, atau belajar. Nucomu Cafe (Coffee & Dessert) hadir dengan konsep itu: ruang yang nyaman dan bersih, kopi yang diracik segar, dan dessert andalan seperti cheesecake, tiramisu, donut butter cream, dan banana toffee.' }}
                    </p>

                    {{-- 4. Garis Pemisah Tipis dengan Dot di Tengah (Dipertahankan dari Gambar) --}}
                    <div class="story-anim-right story-delay-4 flex items-center gap-3 my-4 max-w-xl text-white/30" aria-hidden="true">
                        <div class="flex-1 h-px bg-white/20"></div>
                        <span class="w-1.5 h-1.5 rounded-full bg-white/60 border border-white/40 shadow-sm"></span>
                        <div class="flex-1 h-px bg-white/20"></div>
                    </div>

                    {{-- 5. Paragraf 2: Teks Asli Fasilitas Nucomu (Dengan Ikon Bullet Lingkaran Ganda & Aksen Garis Kiri dari Gambar) --}}
                    <div class="story-anim-right story-delay-5 flex items-start gap-3.5 mb-6 max-w-xl">
                        {{-- Concentric Double-Circle Bullet Icon --}}
                        <div class="flex-shrink-0 mt-1" aria-hidden="true">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-white/40 text-white/90">
                                <span class="w-2 h-2 rounded-full bg-white/80"></span>
                            </span>
                        </div>
                        {{-- Text with Left Accent Line --}}
                        <div class="border-l border-white/25 pl-4">
                            <p class="font-editorial text-[15px] sm:text-base text-[#E2DDD5] leading-[1.75] font-normal">
                                {{ $brand['cafe_story_p2'] ?? 'Terletak di Plosokandang, Tulungagung, Nucomu punya area indoor dan outdoor yang luas, cocok untuk mahasiswa, nugas, atau WFC. Fasilitas lengkap: WiFi, toilet, dan mushola.' }}
                            </p>
                        </div>
                    </div>

                    {{-- 6. Tagline: Find your favorite taste. (Dipertahankan dari Gambar) --}}
                    <div class="story-anim-right story-delay-6 pt-1">
                        <p class="font-editorial italic text-3xl sm:text-4xl lg:text-[40px] text-white leading-tight font-normal">
                            {{ !empty($brand['about_tagline']) ? $brand['about_tagline'] : 'Find your favorite taste.' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <!-- SUBSECTION 2B: FASILITAS CAFE (MODERN ELEGANT MONOCHROME — FLOATING GALLERY) -->
        <div id="facilities" class="relative overflow-hidden pt-14 sm:pt-18 lg:pt-22 border-t border-[#222222]">

            {{-- Faded Watermark Background Text --}}
            <div class="absolute top-1/2 -translate-y-1/2 right-0 pointer-events-none select-none z-0" aria-hidden="true">
                <span style="font-family: var(--font-sans); font-size: clamp(90px, 16vw, 260px); font-weight: 800; line-height: 1; letter-spacing: -0.01em; color: #FFFFFF; opacity: 0.02; white-space: nowrap; display: block;">NUCOMU</span>
            </div>
            
            <!-- 1. Introduction Header (Vertikal & Rata Tengah) -->
            <div class="mb-8 sm:mb-12 pb-6 sm:pb-8 border-b border-[#222222] reveal-fade-up">
                <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
                    <span class="text-[11px] font-semibold text-[#8E8E8E] uppercase tracking-[0.22em] font-body block mb-3 pl-[0.22em]">
                        OUR SPACE / FASILITAS NUCOMU
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-normal text-white tracking-tight font-editorial leading-tight mx-auto">
                        Setiap Sudut, Punya Cerita.
                    </h2>
                    <p class="font-body text-xs sm:text-sm text-[#A0A0A0] max-w-3xl font-normal leading-relaxed mx-auto mt-3.5">
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
