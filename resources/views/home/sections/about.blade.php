<!-- SECTION 2: ABOUT NUCOMU CAFE -->
<section id="about" class="py-20 lg:py-28 bg-pure-white border-b border-light-gray/60">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- SUBSECTION 2A: CERITA & KONSEP CAFE -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start mb-24">
            
            <!-- Left Editorial Text Column -->
            <div class="lg:col-span-5 reveal-on-scroll">
                <span class="text-[11px] font-semibold uppercase tracking-editorial-widest text-medium-gray block mb-3">TENTANG NUCOMU</span>
                <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-ink-black leading-[1.15] mb-6">
                    Lebih dari Sekadar Tempat Menikmati Kopi.
                </h2>
                <div class="w-12 h-0.5 bg-ink-black mb-6"></div>
                <p class="font-body text-sm sm:text-base text-charcoal/80 leading-relaxed mb-6 font-normal">
                    Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat. 
                </p>
                <p class="font-body text-sm text-medium-gray leading-relaxed mb-8">
                    Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.
                </p>

                <div class="pt-2">
                    <a href="#menu" class="inline-flex items-center space-x-2 text-xs font-semibold uppercase tracking-editorial-widest text-ink-black border-b border-ink-black pb-1 hover:text-medium-gray hover:border-medium-gray transition-colors">
                        <span>Jelajahi Pilihan Menu</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Right Asymmetric Image & Concept Highlight -->
            <div class="lg:col-span-7 flex flex-col space-y-8 reveal-on-scroll">
                <!-- Video Atmosphere Frame -->
                <div id="about-video-container" class="relative w-full aspect-video overflow-hidden rounded bg-pure-white" style="transition: background-color 0.4s ease;">
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

                <!-- Concept Statement -->
                <div class="p-6 sm:p-8 bg-warm-ivory/70 border border-soft-beige rounded">
                    <p class="font-editorial text-xl sm:text-2xl italic text-ink-black leading-relaxed">
                        "Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan."
                    </p>
                </div>
            </div>

        </div>

        <!-- SUBSECTION 2B: FASILITAS CAFE (DYNAMIC ASYMMETRICAL EDITORIAL) -->
        <div id="facilities" class="pt-12 sm:pt-16 lg:pt-20 border-t border-stone-200/80">
            
            <!-- Section Header (Editorial Asymmetric Split - Compact) -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-10 pb-6 border-b border-stone-200/80 reveal-on-scroll">
                <div>
                    <span class="inline-flex items-center space-x-1.5 text-[11px] font-bold uppercase tracking-widest text-[#E06D24] mb-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                        <span>Eksplorasi Ruang</span>
                    </span>
                    <h2 class="font-editorial text-2xl sm:text-4xl lg:text-5xl font-light text-[#2A1D17]">
                        Fasilitas <span class="font-editorial italic font-normal text-[#E06D24]">&amp; Area Cafe</span>
                    </h2>
                </div>
                <p class="font-body text-xs sm:text-sm text-[#5C4E45] max-w-md font-normal leading-relaxed mt-2 md:mt-0">
                    Dirancang dengan cermat untuk memenuhi kebutuhan kerja tenang, diskusi komunitas, hingga kenyamanan beribadah.
                </p>
            </div>

            <!-- Facilities Grid Layout (Asymmetrical Alternating - Compact) -->
            <div class="space-y-8 sm:space-y-10">
                
                <!-- Facility Item 1: Indoor Lounge (Image Left, Content Right) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center reveal-on-scroll">
                    <div class="lg:col-span-7 group relative overflow-hidden rounded-2xl h-[240px] sm:h-[280px] lg:h-[300px] shadow-md border border-stone-200/80 bg-stone-100">
                        <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1000&auto=format&fit=crop" 
                             alt="Indoor Lounge Nucomu" 
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        <div class="absolute top-3.5 left-3.5 bg-white/95 backdrop-blur-md px-3.5 py-1 rounded-full shadow-sm border border-stone-200/70 text-[10px] font-bold text-[#E06D24] uppercase tracking-wider flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                            <span>AC &bull; High-Speed Wi-Fi &bull; Power Outlets</span>
                        </div>
                    </div>
                    <div class="lg:col-span-5 lg:pl-2 space-y-3 text-left">
                        <span class="text-[11px] font-bold text-[#E06D24] uppercase tracking-widest block">01 / Workspace &amp; Lounge</span>
                        <h3 class="font-editorial text-2xl sm:text-3xl font-bold text-[#2A1D17] leading-tight">Indoor Lounge Work Space</h3>
                        <p class="font-body text-xs sm:text-[13px] text-[#5C4E45] leading-relaxed font-normal">
                            Area dalam ruangan ber-AC yang tenang dengan meja ergonomis, stopkontak melimpah di setiap sudut, serta koneksi internet cepat. Ideal untuk WFC (Work From Cafe) maupun rapat santai.
                        </p>
                        <ul class="space-y-2 pt-1 text-xs text-[#5C4E45]">
                            <li class="flex items-center space-x-2">
                                <span class="material-symbols-outlined text-[#E06D24] text-base">check_circle</span>
                                <span>Suhu ruangan sejuk &amp; sirkulasi udara bersih</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="material-symbols-outlined text-[#E06D24] text-base">check_circle</span>
                                <span>Pencahayaan hangat bebas silau untuk kenyamanan mata</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="material-symbols-outlined text-[#E06D24] text-base">check_circle</span>
                                <span>Stopkontak di setiap meja &amp; Wi-Fi berkecepatan tinggi</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Facility Item 2: Outdoor Garden (Reversed: Content Left, Image Right) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center reveal-on-scroll">
                    <div class="lg:col-span-5 lg:pr-2 space-y-3 text-left order-2 lg:order-1">
                        <span class="text-[11px] font-bold text-[#E06D24] uppercase tracking-widest block">02 / Open Air &amp; Santai</span>
                        <h3 class="font-editorial text-2xl sm:text-3xl font-bold text-[#2A1D17] leading-tight">Outdoor Garden Terrace</h3>
                        <p class="font-body text-xs sm:text-[13px] text-[#5C4E45] leading-relaxed font-normal">
                            Taman terbuka bersuasana asri dengan naungan pepohonan rindang dan pencahayaan malam yang temaram romantis. Tempat favorit untuk menikmati senja, angin sepoi-sepoi, dan obrolan santai tanpa sekat kaku.
                        </p>
                        <ul class="space-y-2 pt-1 text-xs text-[#5C4E45]">
                            <li class="flex items-center space-x-2">
                                <span class="material-symbols-outlined text-[#E06D24] text-base">check_circle</span>
                                <span>Smoking area yang lega dan asri</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="material-symbols-outlined text-[#E06D24] text-base">check_circle</span>
                                <span>Suasana hijau teduh dengan bangku taman nyaman</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="material-symbols-outlined text-[#E06D24] text-base">check_circle</span>
                                <span>Atmosfer santai untuk bercengkerama sore hingga malam</span>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-7 group relative overflow-hidden rounded-2xl h-[240px] sm:h-[280px] lg:h-[300px] shadow-md border border-stone-200/80 bg-stone-100 order-1 lg:order-2">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1000&auto=format&fit=crop" 
                             alt="Outdoor Garden Nucomu" 
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        <div class="absolute top-3.5 left-3.5 bg-white/95 backdrop-blur-md px-3.5 py-1 rounded-full shadow-sm border border-stone-200/70 text-[10px] font-bold text-[#E06D24] uppercase tracking-wider flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                            <span>Smoking Allowed &bull; Garden View</span>
                        </div>
                    </div>
                </div>

                <!-- Facility Item 3 & 4 Grid (Musala & Restroom - Compact) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6 pt-2">
                    <!-- Musala -->
                    <div class="bg-gradient-to-br from-[#FAF8F5] via-white to-[#F6EFE6]/40 p-5 sm:p-6 rounded-2xl border border-stone-200/90 shadow-sm hover:shadow-md transition-all duration-300 space-y-3 text-left reveal-on-scroll">
                        <div class="w-10 h-10 rounded-xl bg-[#E06D24]/10 border border-[#E06D24]/20 flex items-center justify-center text-[#E06D24] mb-2 shadow-sm">
                            <span class="material-symbols-outlined text-xl">mosque</span>
                        </div>
                        <span class="text-[11px] font-bold text-[#E06D24] uppercase tracking-widest block">03 / Kenyamanan Ibadah</span>
                        <h4 class="font-editorial text-xl sm:text-2xl font-bold text-[#2A1D17]">Musala Bersih &amp; Terpisah</h4>
                        <p class="font-body text-xs text-[#5C4E45] font-normal leading-relaxed">
                            Fasilitas musala khusus lengkap dengan sarana wudhu yang selalu terjaga kebersihannya, sajadah, mukena wangi, serta pendingin udara untuk kenyamanan dan ketenangan ibadah Anda.
                        </p>
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-semibold text-neutral-700 border border-stone-200 shadow-sm">Sarana Wudhu Bersih</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-semibold text-neutral-700 border border-stone-200 shadow-sm">Mukena &amp; Sajadah</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-semibold text-neutral-700 border border-stone-200 shadow-sm">Sejuk &amp; Tenang</span>
                        </div>
                    </div>

                    <!-- Kamar Mandi / Restroom -->
                    <div class="bg-gradient-to-br from-[#FAF8F5] via-white to-[#EDE7DC]/40 p-5 sm:p-6 rounded-2xl border border-stone-200/90 shadow-sm hover:shadow-md transition-all duration-300 space-y-3 text-left reveal-on-scroll">
                        <div class="w-10 h-10 rounded-xl bg-[#54392B]/10 border border-[#54392B]/20 flex items-center justify-center text-[#54392B] mb-2 shadow-sm">
                            <span class="material-symbols-outlined text-xl">wc</span>
                        </div>
                        <span class="text-[11px] font-bold text-[#54392B] uppercase tracking-widest block">04 / Kebersihan Utama</span>
                        <h4 class="font-editorial text-xl sm:text-2xl font-bold text-[#2A1D17]">Restroom Modern &amp; Higienis</h4>
                        <p class="font-body text-xs text-[#5C4E45] font-normal leading-relaxed">
                            Kamar mandi terpisah pria &amp; wanita dengan standar kebersihan berkala tinggi, dilengkapi sabun cuci tangan aromaterapi lembut, cermin estetis, dan ventilasi udara segar demi kenyamanan setiap pengunjung.
                        </p>
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-semibold text-neutral-700 border border-stone-200 shadow-sm">Terpisah Pria &amp; Wanita</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-semibold text-neutral-700 border border-stone-200 shadow-sm">Hand Wash Aromatik</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-white text-[10px] font-semibold text-neutral-700 border border-stone-200 shadow-sm">Sanitasi Teratur</span>
                        </div>
                    </div>
                </div>

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
