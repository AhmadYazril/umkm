<!-- SECTION 2: ABOUT NUCOMU CAFE -->
<section id="about" 
         class="relative pt-16 sm:pt-20 lg:pt-28 pb-20 sm:pb-28 lg:pb-32 border-b border-[#DDDDDD]/80"
         style="background-color: #EADBC8; transition: background-color 0.8s ease; overflow: hidden;">

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
        <span class="vertical-lr-editorial text-[10px] font-semibold tracking-[0.3em] uppercase text-[#7A6B60] font-body">
            NUCOMU CAFE &amp; EATERY
        </span>
        <div class="w-px h-16 bg-[#54392B]/20"></div>
    </div>

    {{-- Perimeter Decorative Element 2: Top Right Editorial Established Timestamp --}}
    <div class="hidden sm:flex absolute right-6 sm:right-10 lg:right-16 top-10 sm:top-14 flex-col items-end pointer-events-none select-none z-10" aria-hidden="true">
        <div class="text-right">
            <span class="block text-[11px] font-bold tracking-[0.2em] text-[#7A6B60] uppercase font-body">EST.</span>
            <span class="block text-[11px] font-medium tracking-[0.18em] text-[#7A6B60] font-body">2024</span>
        </div>
        <div class="w-px h-10 bg-[#54392B]/20 mt-2"></div>
    </div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- SUBSECTION 2A: OUR STORY / ABOUT NUCOMU (3D ROOM TOUR VIDEO & CAFE PROFILE) -->
        <div id="our-story-section" class="relative overflow-hidden mb-20 sm:mb-24 lg:mb-28">
            {{-- Faded Watermark Background Text --}}
            <div class="absolute top-1/2 -translate-y-1/2 right-0 pointer-events-none select-none z-0" aria-hidden="true">
                <span style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(100px, 18vw, 300px); font-weight: 900; line-height: 1; letter-spacing: -0.03em; color: #54392B; opacity: 0.08; white-space: nowrap; display: block;">NUCOMU</span>
            </div>
            {{-- TWO-COLUMN MAIN CONTENT --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 xl:gap-20 items-center">

                {{-- KOLOM KIRI: ELEGANT LAYERED FLOATING FRAME DENGAN VIDEO ANIMASI ROOM TOUR 3D NUCOMU --}}
                <div class="lg:col-span-6 flex justify-center lg:justify-start">
                    <div class="group/story-card relative w-full max-w-[440px] sm:max-w-[470px] lg:max-w-[490px] xl:max-w-[520px] p-4 sm:p-5 lg:p-6">

                        {{-- 1. Lapisan Belakang (Back Plate): Warm matching tone, Rounded Corners, Rotasi -2.6 Derajat --}}
                        <div id="about-backplate" 
                             class="story-anim-deco absolute inset-0 bg-[#D5C0A8] rounded-[28px] sm:rounded-[34px] lg:rounded-[40px] pointer-events-none z-0 shadow-md origin-center transform -rotate-2 sm:-rotate-[2.6deg]"
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
                            <div id="about-video-badge" class="absolute bottom-3 left-3 z-30 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-full text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#54392B] border border-white/60 shadow-sm flex items-center space-x-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24] animate-pulse"></span>
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
                                class="story-anim-badge-coffee absolute bottom-1.5 right-1.5 sm:bottom-2 sm:right-2 lg:bottom-2.5 lg:right-2.5 z-20 w-[54px] h-[54px] sm:w-[60px] sm:h-[60px] lg:w-[66px] lg:h-[66px] bg-[#242424] hover:bg-[#383838] active:scale-90 transition-all rounded-full shadow-[0_12px_28px_-6px_rgba(0,0,0,0.36)] flex items-center justify-center text-white border-[3px] border-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#54392B]/40">
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

                {{-- KOLOM KANAN: EYEBROW, HEADLINE EDITORIAL, PARAGRAF PROFIL & TOMBOL MENU --}}
                <div class="lg:col-span-6 flex flex-col justify-center text-left lg:pl-2">

                    {{-- 1. Eyebrow Label dengan Garis Aksen Tipis --}}
                    <div class="story-anim-right story-delay-1 flex items-center gap-3 mb-4 sm:mb-5">
                        <span class="text-xs sm:text-[13px] font-bold text-[#54392B] uppercase tracking-[0.22em] font-body">
                            {{ $brand['about_label'] ?? 'TENTANG KAMI' }}
                        </span>
                        <span class="sr-only">{{ $brand['about_label'] ?? 'OUR STORY / TENTANG KAMI' }}</span>
                        <div class="w-12 h-px bg-[#54392B]/25" aria-hidden="true"></div>
                    </div>

                    {{-- 2. Headline Utama: Modern Minimalist Manrope Semibold (600) (Statis tanpa animasi mengetik) --}}
                    <div class="story-anim-right story-delay-2 mb-5 min-h-[44px] sm:min-h-[52px] flex items-center">
                        <h2 class="font-body text-3xl sm:text-4xl lg:text-[40px] xl:text-[44px] font-semibold text-[#1C1C1C] leading-[1.18] tracking-tight">
                            <span id="about-typing-headline">Nucomu Cafe</span>
                        </h2>
                        <noscript>
                            <h2 class="font-body text-3xl sm:text-4xl lg:text-[40px] xl:text-[44px] font-semibold text-[#1C1C1C] leading-[1.18] tracking-tight">Nucomu Cafe</h2>
                        </noscript>
                        <div class="sr-only">
                            <h2>Nucomu Cafe</h2>
                            <p>Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.</p>
                            <p>Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.</p>
                            <p>Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.</p>
                            <img src="{{ asset('images/nucomu_story_1.png') }}" alt="Nucomu Cafe Story" />
                        </div>
                    </div>

                    {{-- 3. Paragraf Deskripsi Profil Nucomu Cafe (Animasi Mengetik Cepat) --}}
                    <div class="space-y-4 max-w-xl text-[15px] sm:text-base text-[#4A3B32] leading-[1.8] font-body mb-7 min-h-[160px]">
                        <p class="story-anim-right story-delay-3">
                            <span id="about-typing-p1"></span><span id="about-typing-cursor-1" class="inline-block w-[2px] h-[1.1em] bg-[#1C1C1C] align-middle ml-0.5 animate-pulse" aria-hidden="true"></span>
                        </p>
                        <p class="story-anim-right story-delay-4">
                            <span id="about-typing-p2"></span><span id="about-typing-cursor-2" class="inline-block w-[2px] h-[1.1em] bg-[#1C1C1C] align-middle ml-0.5 animate-pulse hidden" aria-hidden="true"></span>
                        </p>
                        <noscript>
                            <p>Kafe adalah tempat bersantai untuk menikmati kopi, makanan ringan, dan dessert sambil bertemu teman, bekerja, atau belajar. Nucomu Cafe (Coffee &amp; Dessert) hadir dengan konsep itu: ruang yang nyaman dan bersih, kopi yang diracik segar, dan dessert andalan seperti cheesecake, tiramisu, donut butter cream, dan banana toffee.</p>
                            <p>Terletak di Plosokandang, Tulungagung, Nucomu punya area indoor dan outdoor yang luas, cocok untuk mahasiswa, nugas, atau WFC. Fasilitas lengkap: WiFi, toilet, dan mushola. <em>Find your favorite taste.</em></p>
                        </noscript>
                        <div class="sr-only">
                            <p>Kafe adalah tempat bersantai untuk menikmati kopi, makanan ringan, dan dessert sambil bertemu teman, bekerja, atau belajar. Nucomu Cafe (Coffee &amp; Dessert) hadir dengan konsep itu: ruang yang nyaman dan bersih, kopi yang diracik segar, dan dessert andalan seperti cheesecake, tiramisu, donut butter cream, dan banana toffee.</p>
                            <p>Terletak di Plosokandang, Tulungagung, Nucomu punya area indoor dan outdoor yang luas, cocok untuk mahasiswa, nugas, atau WFC. Fasilitas lengkap: WiFi, toilet, dan mushola. Find your favorite taste.</p>
                        </div>
                    </div>

                    {{-- 4. Tombol Pill Hitam "Lihat Menu Kami →" --}}
                    <div class="story-anim-right story-delay-5 mb-8">
                        <a href="{{ $brand['about_button_link'] ?? '#menu' }}"
                           class="group inline-flex items-center gap-3.5 bg-[#242424] hover:bg-[#383838] active:bg-[#1A1A1A] text-white text-sm sm:text-[15px] font-medium py-3.5 px-8 rounded-full transition-colors duration-300 shadow-[0_8px_20px_-4px_rgba(36,36,36,0.3)] hover:shadow-[0_12px_28px_-6px_rgba(36,36,36,0.4)] focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                            <span>{{ $brand['about_button_text'] ?? 'Jelajahi Pilihan Menu' }}</span>
                            <svg class="w-4 h-4 text-white transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                    {{-- 5. Info Kecil Bergaya Label Monospace --}}
                    <div class="story-anim-right story-delay-5 flex flex-wrap items-center gap-x-4 sm:gap-x-5 gap-y-2 pt-4 border-t border-[#54392B]/15 text-[#7A6B60] text-[11px] font-body tracking-[0.14em] uppercase font-semibold">
                        <span>EST. 2024</span>
                        <span class="w-1 h-1 rounded-full bg-[#54392B]/35"></span>
                        <span>SELASA–SABTU 11.00–21.00 · MINGGU S/D 22.00 (SENIN TUTUP)</span>
                        <span class="w-1 h-1 rounded-full bg-[#54392B]/35 hidden sm:inline-block"></span>
                        <span class="hidden sm:inline-block">TULUNGAGUNG</span>
                    </div>

                </div>

            </div>

            {{-- QUOTE SECTION: MINIMALIST EDITORIAL QUOTE CARD --}}
            <div id="story-quote-wrapper" class="mt-16 sm:mt-20 lg:mt-24">
                <div class="relative bg-white/70 backdrop-blur-sm border border-[#54392B]/15 rounded-[24px] sm:rounded-[28px] p-6 sm:p-8 lg:p-9 shadow-[0_10px_30px_rgba(0,0,0,0.03)] transition-all duration-300 hover:border-[#54392B]/30">
                    
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
                                "{{ $brand['about_quote'] ?? 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.' }}"
                            </p>
                            <blockquote class="font-editorial italic font-medium text-lg sm:text-xl lg:text-[22px] text-[#333333] leading-[1.55]">
                                <span>&ldquo;{{ $brand['about_quote'] ?? 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.' }}&rdquo;</span>
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
        <div id="facilities" class="relative overflow-hidden pt-14 sm:pt-18 lg:pt-22 border-t border-[#54392B]/15">

            {{-- Faded Watermark Background Text --}}
            <div class="absolute top-1/2 -translate-y-1/2 right-0 pointer-events-none select-none z-0" aria-hidden="true">
                <span style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(100px, 18vw, 300px); font-weight: 900; line-height: 1; letter-spacing: -0.03em; color: #54392B; opacity: 0.06; white-space: nowrap; display: block;">NUCOMU</span>
            </div>
            
            <!-- 1. Introduction Header (Minimal Editorial Split) -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-12 pb-6 sm:pb-8 border-b border-[#54392B]/15 gap-4 reveal-fade-up">
                <div>
                    <span class="text-[11px] font-semibold text-[#7A6B60] uppercase tracking-[0.22em] font-body block mb-2">
                        OUR SPACE / FASILITAS NUCOMU
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-[#1C1C1C] tracking-tight font-body leading-tight">
                        Setiap Sudut, Punya Cerita.
                    </h2>
                </div>
                <p class="font-body text-xs sm:text-sm text-[#5C4E45] max-w-md font-normal leading-relaxed">
                    Dirancang dengan cermat untuk kenyamanan bekerja, kehangatan bercengkerama, hingga ketenangan beribadah.
                </p>
            </div>

            <!-- 2. Interactive Floating Gallery (Carousel Fasilitas Dinamis) -->
            @include('home.sections.facilities.gallery')

        </div>

    </div>
</section>

<!-- SCRIPT: ABOUT SECTION 3D ROOM TOUR VIDEO CONTROLLER & FAST TYPEWRITER ANIMATION -->
<script>
    (function() {
        const video = document.getElementById('about-room-tour-video');
        const playBtn = document.getElementById('about-video-play-btn');
        const iconPlay = document.getElementById('about-icon-play');
        const iconPause = document.getElementById('about-icon-pause');
        const aboutCard = document.getElementById('about-video-container');
        const storySection = document.getElementById('our-story-section') || aboutCard;

        const headEl = document.getElementById('about-typing-headline');
        const headCur = document.getElementById('about-headline-cursor');
        const p1El = document.getElementById('about-typing-p1');
        const p2El = document.getElementById('about-typing-p2');
        const cur1 = document.getElementById('about-typing-cursor-1');
        const cur2 = document.getElementById('about-typing-cursor-2');

        const headText = "Nucomu Cafe";
        const p1Text = "Kafe adalah tempat bersantai untuk menikmati kopi, makanan ringan, dan dessert sambil bertemu teman, bekerja, atau belajar. Nucomu Cafe (Coffee & Dessert) hadir dengan konsep itu: ruang yang nyaman dan bersih, kopi yang diracik segar, dan dessert andalan seperti cheesecake, tiramisu, donut butter cream, dan banana toffee.";
        const p2Text = "Terletak di Plosokandang, Tulungagung, Nucomu punya area indoor dan outdoor yang luas, cocok untuk mahasiswa, nugas, atau WFC. Fasilitas lengkap: WiFi, toilet, dan mushola. Find your favorite taste.";

        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let isVisible = false;
        let isManuallyPaused = false;
        let typingStarted = false;
        let typingTimeouts = [];
        const typingSpeed = 4; // Ketik paragraf teks bawah dipercepat (4ms)

        function clearTypingTimers() {
            typingTimeouts.forEach(function(t) { clearTimeout(t); });
            typingTimeouts = [];
        }

        function resetTyping() {
            clearTypingTimers();
            typingStarted = false;
            if (headEl) headEl.textContent = headText;
            if (headCur) headCur.classList.add('hidden');
            if (p1El) p1El.textContent = '';
            if (p2El) p2El.textContent = '';
            if (cur1) cur1.classList.remove('hidden');
            if (cur2) cur2.classList.add('hidden');
        }

        function startTyping() {
            if (typingStarted) return;
            typingStarted = true;
            clearTypingTimers();

            if (prefersReduced) {
                if (headEl) headEl.textContent = headText;
                if (headCur) headCur.classList.add('hidden');
                if (p1El) p1El.textContent = p1Text;
                if (p2El) p2El.textContent = p2Text;
                if (cur1) cur1.classList.add('hidden');
                if (cur2) cur2.classList.add('hidden');
                return;
            }

            // Headline "Nucomu Cafe" langsung tampil statis tanpa animasi mengetik
            if (headEl) headEl.textContent = headText;
            if (headCur) headCur.classList.add('hidden');
            if (p1El) p1El.textContent = '';
            if (p2El) p2El.textContent = '';
            if (cur1) cur1.classList.remove('hidden');
            if (cur2) cur2.classList.add('hidden');

            // 1. Ketik Paragraf 1 (animasi mengetik dipercepat)
            let i = 0;
            function typeP1() {
                if (!isVisible || !p1El) return;
                if (i < p1Text.length) {
                    p1El.textContent = p1Text.slice(0, i + 1);
                    i++;
                    typingTimeouts.push(setTimeout(typeP1, typingSpeed));
                } else {
                    if (cur1) cur1.classList.add('hidden');
                    if (cur2) cur2.classList.remove('hidden');
                    // 2. Ketik Paragraf 2 (animasi mengetik dipercepat)
                    let j = 0;
                    function typeP2() {
                        if (!isVisible || !p2El) return;
                        if (j < p2Text.length) {
                            p2El.textContent = p2Text.slice(0, j + 1);
                            j++;
                            typingTimeouts.push(setTimeout(typeP2, typingSpeed));
                        } else {
                            typingTimeouts.push(setTimeout(function() {
                                if (cur2) cur2.classList.add('hidden');
                            }, 3500));
                        }
                    }
                    typingTimeouts.push(setTimeout(typeP2, 35));
                }
            }

            typingTimeouts.push(setTimeout(typeP1, 40));
        }

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

        // IntersectionObserver: Jalankan ketik cepat & autoplay video saat masuk layar
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    isVisible = entry.isIntersecting;
                    if (isVisible) {
                        startTyping();
                        if (!prefersReduced && !isManuallyPaused && !document.hidden) {
                            playVideo();
                        }
                    } else {
                        pauseVideo();
                        resetTyping();
                    }
                });
            }, { threshold: 0.2 });

            observer.observe(storySection || aboutCard);
        } else {
            startTyping();
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
