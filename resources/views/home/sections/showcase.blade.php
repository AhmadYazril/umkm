<!-- =========================================================================
     PRODUCT SHOWCASE SEQUENCE (SCROLL-TRIGGERED FLOATING INGREDIENTS & CRAFT STORY)
     Theme: Luxury Editorial Monochrome (Putih, Hitam, Abu-abu)
     ========================================================================= -->
<section id="product-showcase" class="relative bg-[#FAF8F5] text-neutral-900 overflow-hidden border-b border-neutral-200/80">

    <!-- MASTER PINNED STAGE -->
    <div id="product-showcase-pin" class="showcase-pinned-stage relative flex flex-col justify-center items-center py-12 lg:py-16">
        
        <!-- Ambient Radial Spotlight & Monochrome Grid -->
        <div class="showcase-bg-spotlight"></div>
        <div class="showcase-ambient-grid"></div>

        <!-- Vertical Milestone Timeline Dots (Right Edge) -->
        <div class="hidden sm:flex flex-col items-center space-y-3 absolute right-6 top-1/2 -translate-y-1/2 z-40 bg-white/70 backdrop-blur-md px-2 py-3 rounded-full border border-neutral-200/80 shadow-xs">
            <span class="showcase-timeline-dot w-2.5 h-2.5 rounded-full bg-neutral-900 transition-all duration-300"></span>
            <span class="showcase-timeline-dot w-1.5 h-1.5 rounded-full bg-neutral-300 transition-all duration-300"></span>
            <span class="showcase-timeline-dot w-1.5 h-1.5 rounded-full bg-neutral-300 transition-all duration-300"></span>
            <span class="showcase-timeline-dot w-1.5 h-1.5 rounded-full bg-neutral-300 transition-all duration-300"></span>
        </div>

        <!-- Top Header Kicker (Monochrome Clean) -->
        <div class="relative z-30 text-center max-w-xl mx-auto px-4 mb-4 sm:mb-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-neutral-900/5 border border-neutral-900/15 text-neutral-800 text-[10px] sm:text-[11px] font-bold uppercase tracking-widest mb-2 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-neutral-900"></span>
                <span>SIGNATURE SHOWCASE &bull; RACIKAN KHAS</span>
                <span class="w-1.5 h-1.5 rounded-full bg-neutral-900"></span>
            </div>
            <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-neutral-900 tracking-tight leading-tight">
                Nucomu Coffee
            </h2>
            <p class="font-body text-xs sm:text-sm text-neutral-500 mt-1.5 max-w-md mx-auto">
                Espresso pilihan, palm sugar syrup, creamer & fresh milk — sajian khas Nucomu yang paling dirindukan.
            </p>
        </div>

        <!-- MAIN INTERACTIVE ARENA: CUP & FLOATING INGREDIENTS -->
        <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 my-2 sm:my-4 flex items-center justify-center">

            <!-- =============================================================
                 LEFT CALLOUT: 88% STAT & INGREDIENT BREAKDOWN
                 ============================================================= -->
            <div id="showcase-stat-left" class="showcase-callout-panel absolute left-4 sm:left-6 lg:left-8 top-1/2 -translate-y-1/2 z-30 max-w-[210px] sm:max-w-xs text-left hidden md:block">
                <!-- Big 88% Stat -->
                <div class="bg-white/80 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-neutral-200/90 shadow-sm mb-3">
                    <span id="showcase-stat-num" class="font-editorial text-5xl sm:text-6xl font-black text-neutral-900 block leading-none tracking-tight">
                        88%
                    </span>
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-neutral-600 block mt-1">
                        Pelanggan Merekomendasikan
                    </span>
                    <p class="text-[11px] text-neutral-500 mt-1 leading-relaxed font-normal">
                        Keseimbangan espresso, palm sugar syrup, susu segar, dan creamer racikan barista Nucomu.
                    </p>
                </div>

                <!-- Composition List with Percentages -->
                <div class="bg-white/80 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-neutral-200/90 shadow-sm space-y-2 text-xs">
                    <span class="text-[9px] font-mono uppercase tracking-widest text-neutral-500 font-bold block mb-1">
                        Komposisi Racikan:
                    </span>
                    <div class="showcase-breakdown-item flex items-center justify-between text-neutral-800">
                        <span class="font-medium flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-900"></span>
                            <span>Espresso Shot</span>
                        </span>
                        <span class="font-bold font-mono">50%</span>
                    </div>
                    <div class="showcase-breakdown-item flex items-center justify-between text-neutral-800">
                        <span class="font-medium flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-500"></span>
                            <span>Fresh Milk & Creamer</span>
                        </span>
                        <span class="font-bold font-mono">35%</span>
                    </div>
                    <div class="showcase-breakdown-item flex items-center justify-between text-neutral-800">
                        <span class="font-medium flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-700"></span>
                            <span>Palm Sugar Syrup</span>
                        </span>
                        <span class="font-bold font-mono">15%</span>
                    </div>
                </div>
            </div>

            <!-- =============================================================
                 CENTER: FLOATING CUP & INGREDIENTS
                 ============================================================= -->
            <div class="relative flex items-center justify-center my-4">
                
                <!-- Main Cup -->
                <div class="showcase-product-wrapper">
                    <!-- ASET PRODUK UTAMA: Ganti file cup.svg ini dengan PNG transparan foto asli di public/assets/showcase/cup.png jika sudah ada -->
                    <img src="{{ asset('assets/showcase/cup.svg') }}" 
                         alt="Es Kopi Susu Gula Aren Nucomu" 
                         class="showcase-cup-image">
                    
                    <!-- Soft Breathing Shadow Under Cup -->
                    <div class="showcase-cup-shadow"></div>
                </div>

                <!-- =========================================================
                     6 FLOATING INGREDIENTS (ASET DAPAT DIGANTI DENGAN PNG ASLI)
                     Folder: public/assets/showcase/
                     ========================================================= -->
                <!-- 1. Biji Kopi Arabica Sendang (Top Left) -->
                <div class="showcase-ingredient ing-bean-1" title="Biji Kopi Sendang Tulungagung">
                    <img src="{{ asset('assets/showcase/bean1.svg') }}" alt="Biji Kopi Arabica Sendang">
                </div>

                <!-- 2. Kristal Es Batu (Top Right) -->
                <div class="showcase-ingredient ing-ice" title="Kristal Es Batu Murni">
                    <img src="{{ asset('assets/showcase/ice.svg') }}" alt="Kristal Es Batu Murni">
                </div>

                <!-- 3. Kayu Manis Batang (Mid Left) -->
                <div class="showcase-ingredient ing-cinnamon" title="Kayu Manis Alami">
                    <img src="{{ asset('assets/showcase/cinnamon.svg') }}" alt="Kayu Manis Alami">
                </div>

                <!-- 4. Daun Mint Segar (Mid Right) -->
                <div class="showcase-ingredient ing-mint" title="Daun Mint Segar">
                    <img src="{{ asset('assets/showcase/mint.svg') }}" alt="Daun Mint Segar">
                </div>

                <!-- 5. Cipratan Susu Murni (Top Center) -->
                <div class="showcase-ingredient ing-milk" title="Susu Segar Pasteurisasi">
                    <img src="{{ asset('assets/showcase/milk_splash.svg') }}" alt="Cipratan Susu Segar">
                </div>

                <!-- 6. Drizzle Gula Aren (Bottom Left) -->
                <div class="showcase-ingredient ing-sugar" title="Sirup Gula Aren Organik">
                    <img src="{{ asset('assets/showcase/sugar_drop.svg') }}" alt="Gula Aren Cair">
                </div>

                <!-- 7. Biji Kopi Pendamping (Mid Right Lower) -->
                <div class="showcase-ingredient ing-bean-2" title="Roasted Coffee Bean">
                    <img src="{{ asset('assets/showcase/bean2.svg') }}" alt="Roasted Coffee Bean">
                </div>

            </div>

            <!-- =============================================================
                 RIGHT CALLOUT: ANIMATED CIRCULAR METERS (KAFEIN & MANIS)
                 ============================================================= -->
            <div id="showcase-stat-right" class="showcase-callout-panel absolute right-4 sm:right-6 lg:right-8 top-1/2 -translate-y-1/2 z-30 max-w-[210px] sm:max-w-xs text-right hidden md:block">
                
                <!-- Meter 1: Tingkat Kafein (75%) -->
                <div class="bg-white/80 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-neutral-200/90 shadow-sm mb-3.5 flex items-center space-x-3.5 justify-end">
                    <div class="text-right">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500 block">
                            Tingkat Kafein
                        </span>
                        <span class="font-editorial text-lg font-bold text-neutral-900 block leading-tight">
                            Sedang – Kuat
                        </span>
                        <span class="text-[10px] text-neutral-400 font-medium">Double shot Sendang</span>
                    </div>
                    
                    <!-- SVG Circular Progress -->
                    <div class="relative w-14 h-14 shrink-0 flex items-center justify-center">
                        <svg class="circle-meter-svg w-14 h-14" viewBox="0 0 100 100">
                            <circle class="circle-track" cx="50" cy="50" r="45"></circle>
                            <circle id="caffeine-bar" class="circle-progress-bar" cx="50" cy="50" r="45" data-percent="75"></circle>
                        </svg>
                        <span id="caffeine-val" class="absolute font-mono text-xs font-bold text-neutral-900">
                            0%
                        </span>
                    </div>
                </div>

                <!-- Meter 2: Tingkat Manis (40%) -->
                <div class="bg-white/80 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-neutral-200/90 shadow-sm flex items-center space-x-3.5 justify-end">
                    <div class="text-right">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500 block">
                            Tingkat Manis
                        </span>
                        <span class="font-editorial text-lg font-bold text-neutral-900 block leading-tight">
                            Pas &amp; Seimbang
                        </span>
                        <span class="text-[10px] text-neutral-400 font-medium">Gula aren alami murni</span>
                    </div>
                    
                    <!-- SVG Circular Progress -->
                    <div class="relative w-14 h-14 shrink-0 flex items-center justify-center">
                        <svg class="circle-meter-svg w-14 h-14" viewBox="0 0 100 100">
                            <circle class="circle-track" cx="50" cy="50" r="45"></circle>
                            <circle id="sweet-bar" class="circle-progress-bar" cx="50" cy="50" r="45" data-percent="40"></circle>
                        </svg>
                        <span id="sweet-val" class="absolute font-mono text-xs font-bold text-neutral-900">
                            0%
                        </span>
                    </div>
                </div>

            </div>

        </div>

        <!-- =================================================================
             MASCOT (BARISTA 3D/CARTOON IDLE FLOATING)
             ================================================================= -->
        <div id="showcase-mascot" class="showcase-mascot-wrapper hidden sm:block">
            <!-- Speech Bubble -->
            <div id="showcase-mascot-bubble" class="absolute -top-7 right-2 bg-neutral-900 text-white text-[9px] font-bold py-1 px-2.5 rounded-full shadow-md whitespace-nowrap border border-white/20">
                <span>Diseduh segar tiap pesanan!</span>
            </div>
            <!-- Aset Maskot: Ganti barista_mascot.svg dengan PNG transparan maskot 3D di public/assets/showcase/barista_mascot.png jika ada -->
            <img src="{{ asset('assets/showcase/barista_mascot.svg') }}" 
                 alt="Nucomu Barista Mascot" 
                 class="w-full h-full object-contain">
        </div>

        <!-- =================================================================
             CENTER SPOTLIGHT HEADLINE & PILL CTA BUTTON
             ================================================================= -->
        <div id="showcase-spotlight-block" class="relative z-30 text-center max-w-xl mx-auto px-4 mt-4 sm:mt-6">
            <h3 class="font-editorial text-2xl sm:text-3xl lg:text-4xl font-bold text-neutral-900 leading-tight">
                "Setiap Tegukan, Rasa yang Dirindukan"
            </h3>
            <p class="font-body text-xs sm:text-sm text-neutral-600 mt-2 leading-relaxed">
                Paduan espresso segar, palm sugar syrup alami, creamer lembut, dan fresh milk — signature Nucomu Coffee yang hadir hanya untuk Anda.
            </p>
            <div class="mt-4 flex items-center justify-center space-x-3">
                <button type="button" 
                        onclick="openReservationModal('Pesanan Signature Showcase: Nucomu Coffee')" 
                        class="px-8 py-3 rounded-full bg-neutral-900 hover:bg-neutral-800 text-white text-xs font-bold uppercase tracking-widest shadow-md transition-all duration-200 hover:scale-105 active:scale-95 flex items-center space-x-2">
                    <span>Pesan Sekarang</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </div>

    </div>

    <!-- =====================================================================
         MENU THUMBNAILS GALLERY (4 STAGGER FADE-UP MONOCHROME CARDS)
         ===================================================================== -->
    <div id="showcase-gallery-section" class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-12 sm:py-16 bg-white border-t border-neutral-200/80">
        <div class="text-center max-w-lg mx-auto mb-8 sm:mb-10">
            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500 block mb-1">
                VARIAN POPULER LAINNYA
            </span>
            <h3 class="font-editorial text-2xl sm:text-3xl font-bold text-neutral-900">
                Pilihan Favorit Pengunjung
            </h3>
        </div>

        <!-- 4 Monochrome Cards Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- Card 1: Nucomu Coffee (Signature) -->
            <div class="showcase-thumb-item showcase-thumb-card bg-[#FAF8F5] border border-neutral-200/80 rounded-2xl p-3.5 sm:p-4 flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-square rounded-xl overflow-hidden bg-neutral-100 mb-3 border border-neutral-200/60">
                        <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&q=80&w=800" 
                             alt="Nucomu Coffee" 
                             class="w-full h-full object-cover transition-transform duration-500">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-neutral-900/80 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-wider">
                            Signature
                        </span>
                    </div>
                    <h4 class="font-editorial text-base sm:text-lg font-bold text-neutral-900 group-hover:underline leading-snug">
                        Nucomu Coffee
                    </h4>
                    <p class="text-[11px] text-neutral-500 line-clamp-1 mt-0.5">Espresso, palm sugar & fresh milk</p>
                </div>
                <div class="pt-3 border-t border-neutral-200/60 flex items-center justify-between mt-3">
                    <span class="font-mono text-xs sm:text-sm font-bold text-neutral-900">Rp 20.000</span>
                    <button type="button" 
                            onclick="openReservationModal('Order Showcase: Nucomu Coffee')" 
                            class="px-2.5 py-1 rounded-full bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-colors">
                        Pesan
                    </button>
                </div>
            </div>

            <!-- Card 2: Banoffee -->
            <div class="showcase-thumb-item showcase-thumb-card bg-[#FAF8F5] border border-neutral-200/80 rounded-2xl p-3.5 sm:p-4 flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-square rounded-xl overflow-hidden bg-neutral-100 mb-3 border border-neutral-200/60">
                        <img src="https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?auto=format&fit=crop&q=80&w=800" 
                             alt="Banoffee" 
                             class="w-full h-full object-cover transition-transform duration-500">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-neutral-900/80 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-wider">
                            Pastry
                        </span>
                    </div>
                    <h4 class="font-editorial text-base sm:text-lg font-bold text-neutral-900 group-hover:underline leading-snug">
                        Banoffee
                    </h4>
                    <p class="text-[11px] text-neutral-500 line-clamp-1 mt-0.5">Pisang, karamel & whipped cream</p>
                </div>
                <div class="pt-3 border-t border-neutral-200/60 flex items-center justify-between mt-3">
                    <span class="font-mono text-xs sm:text-sm font-bold text-neutral-900">Rp 28.000</span>
                    <button type="button" 
                            onclick="openReservationModal('Order Showcase: Banoffee')" 
                            class="px-2.5 py-1 rounded-full bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-colors">
                        Pesan
                    </button>
                </div>
            </div>

            <!-- Card 3: Basque Burnt Cheesecake -->
            <div class="showcase-thumb-item showcase-thumb-card bg-[#FAF8F5] border border-neutral-200/80 rounded-2xl p-3.5 sm:p-4 flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-square rounded-xl overflow-hidden bg-neutral-100 mb-3 border border-neutral-200/60">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCtV8I5EXKu32uYEfKT4WGrWx_ff9JizVDKZYhlBHCpPLkHuYNRdUSoSHGp-Zc4SAAiAjs61tKPWurUmdfI68at-x7a_kiGj4N31Jq8H5rp694k2LcQxExxSptiRnuOudGmFpYVd0rs5YGiZHpPTj5bT-jbZZn5e5gO1l-yYRPKTtp38UzHxVvuLIeBro1FMw5JE-8LnB_Tud6NaQV-TeSFgc40dMGLJpGB2IhhyKBoPCsN5mKB4yZW" 
                             alt="Basque Burnt Cheesecake" 
                             class="w-full h-full object-cover transition-transform duration-500">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-neutral-900/80 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-wider">
                            Best Seller
                        </span>
                    </div>
                    <h4 class="font-editorial text-base sm:text-lg font-bold text-neutral-900 group-hover:underline leading-snug">
                        Basque Cheesecake
                    </h4>
                    <p class="text-[11px] text-neutral-500 line-clamp-1 mt-0.5">Burnt top caramel & vanilla lumer</p>
                </div>
                <div class="pt-3 border-t border-neutral-200/60 flex items-center justify-between mt-3">
                    <span class="font-mono text-xs sm:text-sm font-bold text-neutral-900">Rp 36.000</span>
                    <button type="button" 
                            onclick="openReservationModal('Order Showcase: Basque Burnt Cheesecake')" 
                            class="px-2.5 py-1 rounded-full bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-colors">
                        Pesan
                    </button>
                </div>
            </div>

            <!-- Card 4: Mix Platter -->
            <div class="showcase-thumb-item showcase-thumb-card bg-[#FAF8F5] border border-neutral-200/80 rounded-2xl p-3.5 sm:p-4 flex flex-col justify-between group">
                <div>
                    <div class="relative aspect-square rounded-xl overflow-hidden bg-neutral-100 mb-3 border border-neutral-200/60">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrJGwSp9FdrUg5ecAwMNOJZoazn41-puOe_9mywfUHGU9KA0tvEZ_gxjN7rPKwWbzzsWr3vAnXUdOewdaBPJlg_66Q1r1l760AYrH5ghVC_MAhoLOcRD9qJdqh5BZcy4SQXWGljA3RgwWhhXLE44h168Wu4DLSjhLnj0casL29Hhq9J6FCkxb4csnVw4oR6V6DdQpF8I6HUvt15GL_tX4p9fGsrPFZer8U1qRqsiOAlMwXjNI-6FRB" 
                             alt="Mix Platter" 
                             class="w-full h-full object-cover transition-transform duration-500">
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-neutral-900/80 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-wider">
                            Sharing
                        </span>
                    </div>
                    <h4 class="font-editorial text-base sm:text-lg font-bold text-neutral-900 group-hover:underline leading-snug">
                        Mix Platter
                    </h4>
                    <p class="text-[11px] text-neutral-500 line-clamp-1 mt-0.5">Nugget, fries, sausage & 3 saus</p>
                </div>
                <div class="pt-3 border-t border-neutral-200/60 flex items-center justify-between mt-3">
                    <span class="font-mono text-xs sm:text-sm font-bold text-neutral-900">Rp 35.000</span>
                    <button type="button" 
                            onclick="openReservationModal('Order Showcase: Mix Platter')" 
                            class="px-2.5 py-1 rounded-full bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-colors">
                        Pesan
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- =====================================================================
         ORDER TRACKER (PENUTUP: STEPPER DITERIMA -> DISEDUH -> SIAP/DIANTAR)
         Theme: Dark Monochrome (#141414)
         ===================================================================== -->
    <div id="order-tracker-section" class="order-tracker-section py-14 sm:py-16 px-5 sm:px-8 lg:px-12">
        <div class="max-w-4xl mx-auto">
            
            <div class="text-center mb-10">
                <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 block mb-1">
                    PELACAK PROSES PESANAN
                </span>
                <h3 class="font-editorial text-2xl sm:text-3xl font-bold text-white">
                    Dari Biji Kopi Sampai Meja Anda
                </h3>
                <p class="text-xs text-neutral-400 mt-1">
                    Setiap cangkir diproses secara langsung dengan standar kalibrasi barista Nucomu.
                </p>
            </div>

            <!-- Stepper Track Container -->
            <div class="relative py-8 my-4">
                
                <!-- Inactive Track Line -->
                <div class="tracker-track-line"></div>
                
                <!-- Animated Active Progress Bar -->
                <div id="tracker-progress-bar" class="tracker-progress-active"></div>

                <!-- Animated Courier Bike Icon -->
                <!-- Aset Kurir: Ganti courier_bike.svg dengan PNG transparan di public/assets/showcase/courier_bike.png jika ada -->
                <div id="tracker-bike-icon" class="tracker-bike-icon">
                    <img src="{{ asset('assets/showcase/courier_bike.svg') }}" alt="Status Kurir">
                </div>

                <!-- 3 Stepper Milestone Nodes -->
                <div class="relative flex justify-between items-start">
                    
                    <!-- Milestone 1 -->
                    <div class="tracker-node is-active flex flex-col items-center text-center w-1/3">
                        <div class="tracker-dot w-6 h-6 rounded-full bg-neutral-900 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white mb-2 shadow-sm">
                            1
                        </div>
                        <span class="tracker-label text-xs sm:text-sm font-bold text-neutral-300 block">
                            Pesanan Diterima
                        </span>
                        <span class="text-[10px] text-neutral-400 mt-0.5 hidden sm:block">
                            Tercatat di sistem barista
                        </span>
                    </div>

                    <!-- Milestone 2 -->
                    <div class="tracker-node flex flex-col items-center text-center w-1/3">
                        <div class="tracker-dot w-6 h-6 rounded-full bg-neutral-800 border-2 border-neutral-600 flex items-center justify-center text-[10px] font-bold text-neutral-300 mb-2 shadow-sm transition-all">
                            2
                        </div>
                        <span class="tracker-label text-xs sm:text-sm font-medium text-neutral-400 block transition-all">
                            Diseduh Barista
                        </span>
                        <span class="text-[10px] text-neutral-400 mt-0.5 hidden sm:block">
                            Giling &amp; ekstraksi presisi
                        </span>
                    </div>

                    <!-- Milestone 3 -->
                    <div class="tracker-node flex flex-col items-center text-center w-1/3">
                        <div class="tracker-dot w-6 h-6 rounded-full bg-neutral-800 border-2 border-neutral-600 flex items-center justify-center text-[10px] font-bold text-neutral-300 mb-2 shadow-sm transition-all">
                            3
                        </div>
                        <span class="tracker-label text-xs sm:text-sm font-medium text-neutral-400 block transition-all">
                            Siap / Diantar
                        </span>
                        <span class="text-[10px] text-neutral-400 mt-0.5 hidden sm:block">
                            Dine-in meja / kurir meluncur
                        </span>
                    </div>

                </div>

            </div>

        </div>
    </div>

</section>
