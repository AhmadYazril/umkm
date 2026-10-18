<!-- SECTION 1: HOME / HERO (3 BALANCED CREATIVE SLIDES WITH 1-MINUTE AUTO-TRANSITION) -->
<section id="home" class="relative min-h-[520px] sm:min-h-[570px] lg:min-h-[620px] flex items-center justify-center overflow-hidden border-b border-light-gray/60 select-none group/hero bg-[#FAF8F5]">

    <!-- =========================================================================
         SLIDE 0: WELCOME & SPECIALTY COFFEE (APPROVED OVERHEAD FLAT-LAY)
         ========================================================================= -->
    <div class="hero-slide-item absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out bg-cover bg-center flex items-center justify-center opacity-100 z-10"
         style="background-image: url('{{ asset('images/nucomu/hero_flatlay_bg.jpg') }}');"
         data-slide-index="0">
        
        <!-- Soft feathered center ambient glow for crisp typography -->
        <div class="absolute inset-0 pointer-events-none" 
             style="background: radial-gradient(ellipse 65% 65% at 50% 50%, rgba(246, 241, 234, 0.88) 0%, rgba(246, 241, 234, 0.55) 60%, rgba(246, 241, 234, 0.3) 100%);">
        </div>

        <!-- Center Content -->
        <div class="hero-content-wrapper relative z-20 max-w-2xl mx-auto px-5 sm:px-8 text-center py-10 sm:py-12 lg:py-14 transition-all duration-700 ease-out transform translate-y-0 opacity-100">
            <!-- Cursive Script Kicker -->
            <p class="font-script text-2xl sm:text-4xl lg:text-5xl text-[#3C281E] mb-1.5 leading-tight">
                Welcome to Our Cafe
            </p>

            <!-- Bold Editorial Serif Headline -->
            <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#2A1D17] leading-[1.08] mb-3 tracking-tight">
                Good Coffee,<br>
                Great Moments
            </h1>

            <!-- Nucomu Identity Touchpoint -->
            <div class="inline-flex items-center space-x-2 mb-2.5 bg-white/60 backdrop-blur-sm px-3.5 py-0.5 rounded-full border border-[#54392B]/15">
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                    Nucomu Cafe &bull; Tulungagung
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
            </div>

            <!-- Sensory Subtitle -->
            <p class="font-body text-xs sm:text-sm text-[#5C4E45] max-w-md mx-auto leading-relaxed mb-6 font-normal">
                Nikmati seduhan kopi single origin Sendang Tulungagung, pastry artisanal, dan waktu yang terasa lebih berarti di ruang monokrom bernapas hangat.
            </p>

            <!-- Dual Pill CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="#menu" 
                   class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 sm:px-8 sm:py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95">
                    VIEW OUR MENU
                </a>
                <button type="button" 
                        onclick="openReservationModal('Kunjungan Nucomu')" 
                        class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#54392B] border border-[#54392B]/30 text-xs font-bold uppercase tracking-widest px-7 py-3 sm:px-8 sm:py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                    RESERVASI MEJA
                </button>
            </div>
        </div>
    </div>


    <!-- =========================================================================
         SLIDE 1: DISKON & PROMO 20% (SPLIT EDITORIAL VOUCHER BOARDING PASS)
         Warm Ivory, Terracotta & Gold Palette
         ========================================================================= -->
    <div class="hero-slide-item absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out bg-gradient-to-br from-[#FDFBF7] via-[#F6EFE6] to-[#EFE3D2] flex items-center justify-center opacity-0 pointer-events-none z-0"
         data-slide-index="1">
        
        <!-- Subtle Organic Background Texture & Ambient Shapes -->
        <div class="absolute -right-20 -top-20 w-88 h-88 rounded-full bg-[#E06D24]/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-88 h-88 rounded-full bg-[#C59D5F]/15 blur-3xl pointer-events-none"></div>

        <div class="hero-content-wrapper relative z-20 max-w-6xl mx-auto px-5 sm:px-8 lg:px-12 py-10 sm:py-12 lg:py-14 transition-all duration-700 ease-out transform translate-y-4 opacity-0 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-center">
                
                <!-- Left Column: Story & Headline (Left Aligned) -->
                <div class="lg:col-span-7 text-left">
                    <div class="inline-flex items-center space-x-2 px-3 py-0.5 rounded-full bg-[#E06D24]/15 border border-[#E06D24]/30 text-[#E06D24] text-[10px] sm:text-[11px] font-bold uppercase tracking-widest mb-3">
                        <span class="material-symbols-outlined text-xs">local_offer</span>
                        <span>Penawaran Terbatas &bull; Kunjungan Pertama</span>
                    </div>

                    <h2 class="font-editorial text-2xl sm:text-4xl lg:text-5xl font-bold text-neutral-900 leading-[1.08] tracking-tight mb-3">
                        Nikmati Diskon 20%<br>
                        <span class="text-[#E06D24] italic">Momen Pertama</span> di Nucomu
                    </h2>

                    <p class="font-body text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-lg mb-6 font-normal">
                        Dapatkan potongan 20% untuk semua racikan kopi specialty, pastry buatan rumah, dan makanan berat di kunjungan pertama Anda. Cukup gunakan kode promo saat reservasi via WhatsApp.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" 
                                onclick="openReservationModal('Klaim Promo Diskon 20% Nucomu (Kode: NUCOMU20)')" 
                                class="inline-flex items-center space-x-2 bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-6 py-2.5 sm:px-7 sm:py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95">
                            <span>KLAIM PROMO SEKARANG</span>
                            <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </button>
                        <a href="#menu" 
                           class="inline-flex items-center space-x-2 bg-white hover:bg-neutral-50 text-neutral-800 border border-neutral-300 text-xs font-bold uppercase tracking-widest px-6 py-2.5 sm:px-7 sm:py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                            <span>LIHAT DAFTAR MENU</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Creative Luxury Coffee Voucher Pass -->
                <div class="lg:col-span-5 flex justify-center lg:justify-end">
                    <div class="w-full max-w-sm bg-white rounded-3xl p-5 sm:p-6 shadow-xl border-2 border-dashed border-[#E06D24]/40 relative overflow-hidden transform lg:rotate-2 hover:rotate-0 transition-transform duration-300">
                        
                        <!-- Voucher Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-neutral-100">
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Logo" class="h-5 w-auto object-contain">
                                <span class="font-bold text-xs uppercase tracking-wider text-neutral-900">NUCOMU VOUCHER</span>
                            </div>
                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-[#E06D24]/10 text-[#E06D24] tracking-wider">
                                SPECIAL PASS
                            </span>
                        </div>

                        <!-- Big Discount Graphic -->
                        <div class="py-3.5 text-center bg-gradient-to-b from-[#FDF8F3] to-white rounded-2xl my-2.5 border border-stone-100">
                            <span class="font-editorial text-5xl sm:text-6xl font-extrabold text-[#E06D24] tracking-tight leading-none block">
                                20%
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-800 mt-0.5 block">
                                POTONGAN SEMUA MENU
                            </span>
                        </div>

                        <!-- Promo Code Highlight Box -->
                        <div class="bg-[#FAF6F0] p-2.5 rounded-xl border border-[#E06D24]/20 flex items-center justify-between mt-1.5">
                            <div>
                                <span class="text-[8px] uppercase tracking-wider font-bold text-neutral-500 block">KODE KUPON:</span>
                                <span class="font-mono text-base font-bold text-[#E06D24] tracking-wider">NUCOMU20</span>
                            </div>
                            <button type="button" 
                                    onclick="openReservationModal('Klaim Promo Kode: NUCOMU20')" 
                                    class="px-2.5 py-1.5 rounded-lg bg-neutral-900 text-white text-[9px] font-bold uppercase tracking-wider hover:bg-[#E06D24] transition-colors">
                                Salin & Klaim
                            </button>
                        </div>

                        <!-- Terms Footer -->
                        <div class="mt-3 pt-2.5 border-t border-neutral-100 flex items-center justify-between text-[10px] text-neutral-500 font-medium">
                            <span>Berlaku Dine-in & Reservasi</span>
                            <span>Pusat Kota Tulungagung</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- =========================================================================
         SLIDE 2: KOLABORASI KREATIF (MAGAZINE EDITORIAL COLLAGE LAYOUT)
         Artisanal Warm Coffee Bar & Community Hub
         ========================================================================= -->
    <div class="hero-slide-item absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out bg-gradient-to-br from-[#F5F2EB] via-[#EDE7DC] to-[#E3DCce] flex items-center justify-center opacity-0 pointer-events-none z-0"
         data-slide-index="2">
        
        <div class="hero-content-wrapper relative z-20 max-w-6xl mx-auto px-5 sm:px-8 lg:px-12 py-10 sm:py-12 lg:py-14 transition-all duration-700 ease-out transform translate-y-4 opacity-0 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-center">
                
                <!-- Left Column: Overlapping Visual Photo Collage -->
                <div class="lg:col-span-5 relative order-2 lg:order-1 flex justify-center">
                    <div class="relative w-full max-w-sm sm:max-w-md">
                        <!-- Main Polaroid Photo (Barista / Manual Brew) -->
                        <div class="bg-white p-2.5 sm:p-3 rounded-2xl shadow-xl border border-stone-200 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                            <div class="aspect-[4/3] rounded-xl overflow-hidden bg-stone-200">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8m-7gZzFNVGOKTwQIA84CWnVhGxzO3qIGH9lV_9Py5u0C3qC5Wr-ivYu1cOzDbFKcBFVc5wmFGyzJohqCsYBDDie7yGQ9yhHA6vbKt1MxSq0M8jJMv_o2gIY6dy6ZkWncykJGJNe2GEKBxxklPZf-maWTBiqi07v5JdOgy3xQS8ylylWkCbAUX7C6DejM5RKoP3JSg1dmKOGzs5j77Avi6hycjNHtauDAphlrHBZ56aHCr99845yP" 
                                     alt="Manual Brew Workshop" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="p-2 text-left">
                                <span class="font-editorial text-xs sm:text-sm font-bold text-neutral-900 block">Manual Brew & Workshop</span>
                                <span class="text-[9px] sm:text-[10px] text-neutral-500 font-medium">Kalibrasi & Edukasi Kopi Sendang</span>
                            </div>
                        </div>

                        <!-- Secondary Overlapping Card (Pop-up Showcase) -->
                        <div class="absolute -bottom-4 -right-3 sm:-right-5 bg-white p-2 sm:p-2.5 rounded-2xl shadow-2xl border border-stone-200 w-36 sm:w-44 transform rotate-5 hover:rotate-0 transition-transform duration-300 hidden sm:block">
                            <div class="aspect-square rounded-xl overflow-hidden bg-stone-200">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1P8f-EBGZgQflvyyKW_OfkYt5VXDhIHhyiA0jYpsYzEy8byioxZDaWsSPLkisOP-W_QT-VcYpPWvkAwmmTF7cezpEXN28i0_M-PWtTJW2KjxmleAQqtWyBLZ5zzD30p7EmZtIpIYtRROYCmwqv1pjfq6g5hxHEJFUqd4c3lkTBiGsN776sKI08OynnTy1V_3sxpZlFx503s6QqS_iNqoum8aQF1Xp2-LoEa2gp7yB4VmJ0MaV4UcZ" 
                                     alt="Artisanal Creative Corner" 
                                     class="w-full h-full object-cover">
                            </div>
                            <span class="text-[9px] font-bold text-neutral-900 mt-1 block text-center">Art & Pop-up Showcase</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Editorial Text & Collaboration Actions -->
                <div class="lg:col-span-7 text-left order-1 lg:order-2">
                    <div class="inline-flex items-center space-x-2 px-3 py-0.5 rounded-full bg-[#54392B]/10 border border-[#54392B]/20 text-[#54392B] text-[10px] sm:text-[11px] font-bold uppercase tracking-widest mb-3">
                        <span class="material-symbols-outlined text-xs">handshake</span>
                        <span>Ruang Kolaboratif &bull; Kreator Tulungagung</span>
                    </div>

                    <h2 class="font-editorial text-2xl sm:text-4xl lg:text-5xl font-bold text-neutral-900 leading-[1.08] tracking-tight mb-3">
                        Where Ideas<br>
                        <span class="text-[#54392B] italic font-serif">Come to Brew</span>
                    </h2>

                    <p class="font-body text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-lg mb-4.5 font-normal">
                        Nucomu menyediakan ruang terbuka untuk kreator dan jenama lokal: pameran seni independen, pop-up store, workshop kriya mingguan, atau sesi diskusi komunitas kreatif di jantung Tulungagung.
                    </p>

                    <!-- Feature Tags -->
                    <div class="flex flex-wrap gap-1.5 mb-5">
                        <span class="px-2.5 py-0.5 bg-white/80 rounded-full border border-stone-200 text-xs font-semibold text-neutral-800">Pameran Karya Seni</span>
                        <span class="px-2.5 py-0.5 bg-white/80 rounded-full border border-stone-200 text-xs font-semibold text-neutral-800">Workshop Barista</span>
                        <span class="px-2.5 py-0.5 bg-white/80 rounded-full border border-stone-200 text-xs font-semibold text-neutral-800">Pop-up Showcase</span>
                        <span class="px-2.5 py-0.5 bg-white/80 rounded-full border border-stone-200 text-xs font-semibold text-neutral-800">Space Rental Acara</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" 
                                onclick="openReservationModal('Pengajuan Kolaborasi Nucomu')" 
                                class="inline-flex items-center space-x-2 bg-neutral-900 hover:bg-[#54392B] text-white text-xs font-bold uppercase tracking-widest px-6 py-2.5 sm:px-7 sm:py-3 rounded-full shadow-lg transition-all duration-200 active:scale-95">
                            <span>AJUKAN KOLABORASI</span>
                            <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </button>
                        <a href="#layanan" 
                           class="inline-flex items-center space-x-2 bg-white hover:bg-neutral-50 text-neutral-800 border border-neutral-300 text-xs font-bold uppercase tracking-widest px-6 py-2.5 sm:px-7 sm:py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                            <span>PELAJARI LAYANAN</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- =========================================================================
         CAROUSEL NAVIGATION CONTROLS (ARROWS & BOTTOM DOTS)
         ========================================================================= -->
    <!-- Left & Right Carousel Arrows (Desktop Floating) -->
    <button type="button" 
            onclick="prevHeroSlide()" 
            class="hidden sm:flex items-center justify-center w-10 h-10 rounded-full bg-white/85 hover:bg-white text-neutral-900 border border-stone-200 backdrop-blur-md absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 shadow-md"
            aria-label="Slide Sebelumnya">
        <span class="material-symbols-outlined text-xl">chevron_left</span>
    </button>
    <button type="button" 
            onclick="nextHeroSlide()" 
            class="hidden sm:flex items-center justify-center w-10 h-10 rounded-full bg-white/85 hover:bg-white text-neutral-900 border border-stone-200 backdrop-blur-md absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-30 transition-all duration-200 hover:scale-110 active:scale-95 shadow-md"
            aria-label="Slide Berikutnya">
        <span class="material-symbols-outlined text-xl">chevron_right</span>
    </button>

    <!-- Slide Indicator Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30 flex items-center space-x-2 bg-black/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-black/5">
        <button type="button" onclick="goToHeroSlide(0)" class="hero-dot-btn w-6 h-1.5 rounded-full bg-[#E06D24] transition-all duration-300" aria-label="Slide 1"></button>
        <button type="button" onclick="goToHeroSlide(1)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-400/70 hover:bg-neutral-600 transition-all duration-300" aria-label="Slide 2"></button>
        <button type="button" onclick="goToHeroSlide(2)" class="hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-400/70 hover:bg-neutral-600 transition-all duration-300" aria-label="Slide 3"></button>
    </div>

</section>
