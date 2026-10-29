<!-- SECTION 2: ABOUT NUCOMU CAFE -->
<section id="about" class="relative bg-[#FAFAFA] pt-16 sm:pt-20 lg:pt-28 pb-20 sm:pb-28 lg:pb-32 overflow-hidden border-b border-[#DDDDDD]/80">
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
        <span class="vertical-lr-editorial text-[10px] font-semibold tracking-[0.3em] uppercase text-[#9E9E9E] font-body">
            NUCOMU CAFE &amp; EATERY
        </span>
        <div class="w-px h-16 bg-[#CCCCCC]"></div>
    </div>

    {{-- Perimeter Decorative Element 2: Top Right Editorial Established Timestamp --}}
    <div class="hidden sm:flex absolute right-6 sm:right-10 lg:right-16 top-10 sm:top-14 flex-col items-end pointer-events-none select-none z-10" aria-hidden="true">
        <div class="text-right">
            <span class="block text-[11px] font-bold tracking-[0.2em] text-[#888888] uppercase font-body">EST.</span>
            <span class="block text-[11px] font-medium tracking-[0.18em] text-[#888888] font-body">2024</span>
        </div>
        <div class="w-px h-10 bg-[#DDDDDD] mt-2"></div>
    </div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- SUBSECTION 2A: OUR STORY / ABOUT NUCOMU (MODERN ELEGANT MONOCHROME EDITORIAL) -->
        <div id="our-story-section" class="mb-20 sm:mb-24 lg:mb-28">
            {{-- TWO-COLUMN MAIN CONTENT --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 xl:gap-20 items-center">

                {{-- KOLOM KIRI: ELEGANT LAYERED FLOATING FRAME DENGAN VIDEO NUCOMU CAFE --}}
                <div class="lg:col-span-6 flex justify-center lg:justify-start">
                    <div class="group/story-card relative w-full max-w-[440px] sm:max-w-[470px] lg:max-w-[490px] xl:max-w-[510px] p-4 sm:p-5 lg:p-6">

                        {{-- 1. Lapisan Belakang (Back Plate): Abu-Abu Lembut (#A8A8A8), Rounded Corners, Rotasi -2.6 Derajat --}}
                        <div class="story-anim-deco absolute inset-0 bg-[#A8A8A8] rounded-[28px] sm:rounded-[34px] lg:rounded-[40px] pointer-events-none z-0 shadow-md origin-center transform -rotate-2 sm:-rotate-[2.6deg]" aria-hidden="true"></div>

                        {{-- 2. Frame Video Nucomu Cafe (Rasio 16:9, Bingkai Putih Tegas) --}}
                        <div id="about-video-container"
                             class="story-anim-photo group/carousel relative z-10 w-full rounded-[18px] sm:rounded-[22px] lg:rounded-[24px] overflow-hidden border-[3.5px] sm:border-4 border-white shadow-[0_20px_45px_-10px_rgba(0,0,0,0.22)] bg-pure-white select-none aspect-video"
                             style="transition: background-color 0.4s ease;">
                            <video id="about-video-player"
                                   autoplay
                                   muted
                                   playsinline
                                   preload="auto"
                                   class="w-full h-full object-contain block"
                                   style="aspect-ratio: 16/9; mix-blend-mode: multiply; transition: opacity 0.4s ease-in-out; opacity: 1;">
                                <source src="video/tiramisu.mp4" type="video/mp4">
                            </video>
                            <canvas id="about-video-canvas"
                                    class="w-full h-full object-contain block absolute inset-0 pointer-events-none"
                                    style="aspect-ratio: 16/9; display: none; opacity: 0; transition: opacity 0.4s ease-in-out;">
                            </canvas>
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

                {{-- KOLOM KANAN: LABEL, HEADLINE EDITORIAL, DESKRIPSI & TOMBOL MENU --}}
                <div class="lg:col-span-6 flex flex-col justify-center text-left lg:pl-2">

                    {{-- 1. Label Section dengan Garis Aksen Tipis --}}
                    <div class="story-anim-right story-delay-1 flex items-center gap-3 mb-5">
                        <span class="text-xs sm:text-[13px] font-bold text-[#666666] uppercase tracking-[0.22em] font-body">
                            OUR STORY / TENTANG KAMI
                        </span>
                        <div class="w-12 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
                    </div>

                    {{-- 2. Headline Utama: Modern Minimalist Manrope Semibold (600) untuk Kedua Baris --}}
                    <div class="story-anim-right story-delay-2 mb-6">
                        <h2 class="font-body text-3xl sm:text-4xl lg:text-[40px] xl:text-[44px] font-semibold text-[#1C1C1C] leading-[1.18] tracking-tight">
                            <span class="block">Berawal dari Rasa,</span>
                            <span class="block mt-1 sm:mt-1.5">Kembali karena Rindu.</span>
                        </h2>
                    </div>

                    {{-- 3 & 4. Deskripsi Nucomu Cafe (Dua Paragraf dengan Typografi Rapi & Proporsional) --}}
                    <div class="space-y-4 max-w-xl text-[15px] sm:text-base text-[#666666] leading-[1.8] font-body mb-8">
                        <p class="story-anim-right story-delay-3">
                            Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.
                        </p>
                        <p class="story-anim-right story-delay-4">
                            Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.
                        </p>
                    </div>

                    {{-- 5. Tombol Jelajahi Pilihan Menu (Pill Button Charcoal #242424 dengan Smooth Hover) --}}
                    <div class="story-anim-right story-delay-5">
                        <a href="#menu"
                           class="group inline-flex items-center gap-3.5 bg-[#242424] hover:bg-[#383838] active:bg-[#1A1A1A] text-white text-sm sm:text-[15px] font-medium py-3.5 px-8 rounded-full transition-colors duration-300 shadow-[0_8px_20px_-4px_rgba(36,36,36,0.3)] hover:shadow-[0_12px_28px_-6px_rgba(36,36,36,0.4)] focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                            <span>Jelajahi Pilihan Menu</span>
                            <svg class="w-4 h-4 text-white transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                </div>

            </div>

            {{-- QUOTE SECTION: MINIMALIST EDITORIAL QUOTE CARD --}}
            <div id="story-quote-wrapper" class="mt-16 sm:mt-20 lg:mt-24">
                <div class="relative bg-[#F2F2F2] border border-[#DDDDDD] rounded-[24px] sm:rounded-[28px] p-6 sm:p-8 lg:p-9 shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all duration-300 hover:border-[#CCCCCC]">
                    
                    <div class="flex flex-col md:flex-row items-center md:items-start lg:items-center justify-between gap-6">

                        {{-- Left Quote Icon Indicator --}}
                        <div class="flex-shrink-0 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-[#E0E0E0] flex items-center justify-center text-[#777777] shadow-sm">
                                <span class="font-editorial text-3xl font-bold leading-none select-none">&ldquo;</span>
                            </div>
                            <div class="hidden sm:block w-px h-10 bg-[#DDDDDD]" aria-hidden="true"></div>
                        </div>

                        {{-- Center: Quote Typography with Screen Reader & Typewriter Fallback --}}
                        <div class="flex-grow text-center md:text-left px-1 sm:px-3">
                            <p class="sr-only">
                                "Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan."
                            </p>
                            <blockquote class="font-editorial italic font-medium text-lg sm:text-xl lg:text-[22px] text-[#333333] leading-[1.55]">
                                <span id="story-typewriter-text">&ldquo;Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.&rdquo;</span><span id="story-typewriter-cursor" class="story-typing-cursor inline-block w-[2px] h-[1em] bg-[#333333] align-middle ml-1 hidden" aria-hidden="true"></span>
                            </blockquote>
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
        <div id="facilities" class="pt-14 sm:pt-18 lg:pt-22 border-t border-[#E5E5E5]">
            
            <!-- 1. Introduction Header (Minimal Editorial Split) -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-12 pb-6 sm:pb-8 border-b border-[#E5E5E5] gap-4 reveal-fade-up">
                <div>
                    <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.22em] font-body block mb-2">
                        OUR SPACE / FASILITAS NUCOMU
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-[#1C1C1C] tracking-tight font-body leading-tight">
                        Setiap Sudut, Punya Cerita.
                    </h2>
                </div>
                <p class="font-body text-xs sm:text-sm text-[#666666] max-w-md font-normal leading-relaxed">
                    Dirancang dengan cermat untuk kenyamanan bekerja, kehangatan bercengkerama, hingga ketenangan beribadah.
                </p>
            </div>

            <!-- 2. Interactive Floating Gallery (Indoor / Outdoor) -->
            @include('home.sections.facilities.gallery')

            <!-- 3. Additional Facilities (Musala & Restroom) -->
            @include('home.sections.facilities.additional-cards')

        </div>

    </div>
</section>

<!-- SCRIPT: ABOUT SECTION VIDEO SEQUENCER -->
<script>
    (function() {
        const playlist = [
            'video/tiramisu.mp4',
            'video/chescake.mp4',
            'video/spagheti.mp4',
            'video/kopsu.mp4'
        ];
        let currentIndex = 0;
        const container = document.getElementById('about-video-container');
        const video = document.getElementById('about-video-player');
        const canvas = document.getElementById('about-video-canvas');
        if (!video || !canvas) return;

        const ctx = canvas.getContext('2d', { willReadFrequently: true });
        let animFrameId = null;

        // Preload cache for smooth transitions
        const preloaded = {};
        function preloadVideo(index) {
            const src = playlist[index];
            if (!preloaded[src]) {
                const v = document.createElement('video');
                v.preload = 'auto';
                v.src = src;
                preloaded[src] = v;
            }
        }

        // Preload next video while current is playing
        preloadVideo((currentIndex + 1) % playlist.length);

        let isTransitioning = false;

        function renderKopsuFrame() {
            if (currentIndex !== 3 || video.paused || video.ended) {
                return;
            }

            if (video.videoWidth && (canvas.width !== video.videoWidth || canvas.height !== video.videoHeight)) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
            }

            const w = canvas.width;
            const h = canvas.height;
            if (w && h) {
                // Enlarge image (scale 1.38x) from center
                const scale = 1.38;
                const sw = w / scale;
                const sh = h / scale;
                const sx = (w - sw) / 2;
                const sy = (h - sh) / 2;

                ctx.drawImage(video, sx, sy, sw, sh, 0, 0, w, h);

                const imgData = ctx.getImageData(0, 0, w, h);
                const data32 = new Uint32Array(imgData.data.buffer);
                const len = data32.length;

                // Hero section Kopi Susu theme color #D9DECF:
                // R: 217 (0xD9), G: 222 (0xDE), B: 207 (0xCF)
                for (let i = 0; i < len; i++) {
                    const pixel = data32[i];
                    const r = pixel & 0xFF;
                    const g = (pixel >> 8) & 0xFF;
                    const b = (pixel >> 16) & 0xFF;
                    const maxChannel = Math.max(r, g, b);

                    // Remove dark charcoal background & blend smoothly
                    if (maxChannel <= 48) {
                        data32[i] = 0xFFCFDED9;
                    } else if (maxChannel < 68) {
                        const factor = (maxChannel - 48) / 20;
                        const blendedR = Math.round(217 * (1 - factor) + r * factor);
                        const blendedG = Math.round(222 * (1 - factor) + g * factor);
                        const blendedB = Math.round(207 * (1 - factor) + b * factor);
                        data32[i] = (0xFF << 24) | (blendedB << 16) | (blendedG << 8) | blendedR;
                    }
                }

                ctx.putImageData(imgData, 0, 0);
            }

            animFrameId = requestAnimationFrame(renderKopsuFrame);
        }

        function playVideoSafely() {
            video.muted = true;
            const promise = video.play();
            if (promise !== undefined) {
                promise.catch(function() {
                    function retryPlay() {
                        video.muted = true;
                        video.play().then(function() {
                            window.removeEventListener('click', retryPlay);
                            window.removeEventListener('touchstart', retryPlay);
                            window.removeEventListener('scroll', retryPlay);
                        }).catch(function() {});
                    }
                    window.addEventListener('click', retryPlay, { once: false, passive: true });
                    window.addEventListener('touchstart', retryPlay, { once: false, passive: true });
                    window.addEventListener('scroll', retryPlay, { once: false, passive: true });
                });
            }
        }

        function transitionToNext() {
            if (isTransitioning) return;
            isTransitioning = true;

            // Fade out active display
            if (currentIndex === 3) {
                canvas.style.opacity = '0';
            } else {
                video.style.opacity = '0';
            }

            setTimeout(function() {
                if (animFrameId) {
                    cancelAnimationFrame(animFrameId);
                    animFrameId = null;
                }

                currentIndex = (currentIndex + 1) % playlist.length;
                video.src = playlist[currentIndex];
                video.load();

                // Preload upcoming video
                preloadVideo((currentIndex + 1) % playlist.length);

                if (currentIndex === 3) {
                    // Match container background with hero section theme color #D9DECF
                    if (container) container.style.backgroundColor = '#D9DECF';
                    video.style.opacity = '0';
                    canvas.style.display = 'block';
                } else {
                    if (container) container.style.backgroundColor = '#FFFFFF';
                    canvas.style.display = 'none';
                    canvas.style.opacity = '0';
                }

                function onPlaying() {
                    video.removeEventListener('playing', onPlaying);
                    if (currentIndex === 3) {
                        canvas.style.opacity = '1';
                        renderKopsuFrame();
                    } else {
                        video.style.opacity = '1';
                    }
                    isTransitioning = false;
                }

                video.addEventListener('playing', onPlaying);
                playVideoSafely();
            }, 400);
        }

        video.addEventListener('ended', transitionToNext);

        if (document.readyState === 'complete') {
            playVideoSafely();
        } else {
            window.addEventListener('load', playVideoSafely);
        }
    })();
</script>
