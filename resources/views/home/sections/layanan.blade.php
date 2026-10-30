<!-- SECTION 7: LAYANAN & PEMESANAN (MODERN ELEGANT MONOCHROME EDITORIAL) -->
<section id="layanan" class="py-16 sm:py-20 lg:py-24 bg-[#FAFAFA] border-b border-[#E5E5E5] overflow-hidden">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- Section Header (Sesuai Pola Fasilitas Nucomu / Minimal Editorial Split) -->
        <div class="layanan-anim-header flex flex-col sm:flex-row sm:items-end justify-between mb-10 sm:mb-12 pb-6 sm:pb-8 border-b border-[#E5E5E5] gap-4">
            <div>
                <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.22em] font-body block mb-2">
                    KEMUDAHAN AKSES &amp; RESERVASI
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-[#1C1C1C] tracking-tight font-body leading-tight">
                    Layanan &amp; Pemesanan
                </h2>
            </div>
            <p class="font-body text-xs sm:text-sm text-[#666666] max-w-md font-normal leading-relaxed">
                Pilih cara terbaik untuk menikmati sajian kopi dan suasana hangat Nucomu Cafe sesuai kebutuhan Anda.
            </p>
        </div>

        <!-- 3-KARTU TERANG SEJAJAR & PANEL DETAIL RESERVASI (CSS GRID BERBASIS ORDER) -->
        <!-- Desktop: 3 kolom sejajar (Kartu 1, 2, 3), Baris 2 Panel Detail full-width -->
        <!-- Tablet: Kartu 1 (full), Panel Detail (di bawah Kartu 1), Kartu 2 & 3 berdampingan -->
        <!-- Mobile: 1 kolom terurut: Kartu 1 -> Panel Detail -> Kartu 2 -> Kartu 3 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6 items-stretch relative">
            
            <!-- KARTU 1 (01 / RESERVASI) - WHITE CARD DENGAN FOTO ORGANIK BLOB DI KANAN -->
            <div id="layanan-card-reservasi" 
                 class="layanan-card-reservasi layanan-anim-card-1 relative bg-white text-[#1C1C1C] rounded-[20px] p-6 sm:p-7 border border-[#E5E5E5] shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between text-left group overflow-hidden order-1 md:col-span-2 lg:col-span-1 lg:order-1">
                
                <div class="h-full flex flex-col justify-between">
                    <!-- Header Kartu: Label Nomor (Tanpa Badge) -->
                    <div class="mb-3">
                        <span class="text-[10px] sm:text-[10.5px] font-semibold text-[#8A8A8A] uppercase tracking-[0.16em] font-body block">
                            01 / RESERVASI
                        </span>
                    </div>

                    <!-- Body Row: Teks & Aksi di Kiri + Foto Organik Meja Cafe di Kanan -->
                    <div class="flex items-start justify-between gap-3 sm:gap-4 flex-1">
                        <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                            <div>
                                <!-- Ikon Kalender -->
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] shrink-0 shadow-2xs group-hover:scale-105 transition-transform mb-3" aria-hidden="true">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#1C1C1C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                </div>
                                <h3 class="font-body text-lg sm:text-xl font-semibold text-[#1C1C1C] leading-snug tracking-tight mb-2">
                                    Reservasi Meja &amp; Acara Privat
                                </h3>
                                <p class="font-body text-xs text-[#666666] leading-relaxed font-normal">
                                    Pesan meja khusus untuk kebutuhan acara spesial, mulai dari pertemuan, ulang tahun, hingga acara privat lainnya.
                                </p>
                            </div>

                            <!-- Tombol Lihat Detail (Pill Hitam di Kiri Bawah) -->
                            <div class="pt-4 mt-auto">
                                <button type="button" 
                                        id="btn-toggle-layanan-detail"
                                        onclick="toggleLayananDetailPanel()" 
                                        aria-expanded="false" 
                                        aria-controls="layanan-detail-panel"
                                        class="inline-flex items-center gap-2 bg-[#242424] hover:bg-[#1C1C1C] text-white font-semibold text-xs uppercase tracking-wider px-5 py-2.5 rounded-full shadow-xs hover:shadow-md transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                                    <span id="btn-toggle-layanan-text">Lihat Detail</span>
                                    <svg id="btn-toggle-layanan-icon" class="w-3.5 h-3.5 text-white transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Visual Kanan: Foto Cafe Nucomu (Sistem - Arch/Kubah Seimbang dengan Card 3) -->
                        <div class="w-[95px] h-[135px] sm:w-[110px] sm:h-[155px] rounded-l-full overflow-hidden shrink-0 shadow-2xs group-hover:scale-105 transition-transform duration-500 self-center">
                            <img src="{{ asset('images/nucomu/nucomu_outdoor_2.png') }}" 
                                 alt="Area Meja Reservasi Nucomu" 
                                 class="w-full h-full object-cover" 
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <!-- KARTU 2 (02 / DELIVERY) - WHITE CARD DENGAN LOGO KURIR ZENDO DI KANAN -->
            <div class="layanan-anim-card-2 bg-white rounded-[20px] p-6 sm:p-7 border border-[#E5E5E5] shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between text-left group overflow-hidden order-3 md:col-span-1 lg:order-2">
                <div class="h-full flex flex-col justify-between">
                    <!-- Header Kartu: Label Nomor (Tanpa Badge) -->
                    <div class="mb-3">
                        <span class="text-[10px] sm:text-[10.5px] font-semibold text-[#8A8A8A] uppercase tracking-[0.16em] font-body block">
                            02 / DELIVERY
                        </span>
                    </div>

                    <!-- Body Row: Teks & Aksi di Kiri + Logo Kurir Zendo di Kanan -->
                    <div class="flex items-start justify-between gap-3 sm:gap-4 flex-1">
                        <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                            <div>
                                <!-- Ikon WhatsApp Hijau -->
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#25D366] flex items-center justify-center text-white shrink-0 shadow-2xs group-hover:scale-105 transition-transform mb-3" aria-hidden="true">
                                    <svg class="w-5 h-5 text-white fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.067-2.228-.567-1.78-.737-2.909-2.544-2.998-2.663-.088-.119-.724-.962-.724-1.834 0-.872.457-1.302.62-1.479.163-.177.356-.222.475-.222.119 0 .238.002.341.008.109.006.255-.041.399.305.148.356.505 1.233.549 1.322.044.089.073.193.015.311-.059.119-.089.193-.178.297-.089.104-.187.232-.267.311-.089.089-.182.185-.078.363.104.178.462.763.992 1.235.684.609 1.261.798 1.439.887.178.089.282.074.386-.044.104-.119.444-.518.563-.696.119-.178.238-.148.399-.089.163.059 1.034.488 1.212.577.178.089.297.133.341.207.044.074.044.43-.1.835z"/>
                                    </svg>
                                </div>
                                <h4 class="font-body text-lg sm:text-xl font-semibold text-[#1C1C1C] leading-snug tracking-tight mb-2">
                                    Delivery via WhatsApp (Zendo)
                                </h4>
                                <p class="font-body text-xs text-[#666666] leading-relaxed font-normal">
                                    Nikmati menu favoritmu dengan mudah melalui WhatsApp. Pesanan akan segera diproses dan diantar ke lokasi kamu.
                                </p>
                            </div>

                            <!-- Tombol Pesan Sekarang (Pill Gray di Kiri Bawah) -->
                            <div class="pt-4 mt-auto">
                                <button type="button" onclick="setModalProcessMethod('zendo'); openCartModal();" 
                                   class="inline-flex items-center gap-2 bg-[#F0F0F0] hover:bg-[#242424] text-[#1C1C1C] hover:text-white border border-[#E5E5E5] hover:border-[#242424] font-semibold text-xs uppercase tracking-wider px-5 py-2.5 rounded-full shadow-2xs hover:shadow-sm transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2 cursor-pointer">
                                    <span>Pesan Sekarang</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Visual Kanan: Kurir Zendo Lingkaran Abu-abu -->
                        <div class="w-[95px] h-[95px] sm:w-[110px] sm:h-[110px] rounded-full overflow-hidden shrink-0 shadow-2xs group-hover:scale-105 transition-transform duration-500 self-center">
                            <img src="{{ asset('images/logos/courier_monochrome.png') }}" 
                                 alt="Kurir Zendo" 
                                 class="w-full h-full object-cover rounded-full" 
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <!-- KARTU 3 (03 / ONLINE ORDER) - WHITE CARD DENGAN FOTO ARCH LATTE DI KANAN -->
            <div class="layanan-anim-card-3 bg-white rounded-[20px] p-6 sm:p-7 border border-[#E5E5E5] shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between text-left group overflow-hidden order-4 md:col-span-1 lg:order-3">
                <div class="h-full flex flex-col justify-between">
                    <!-- Header Kartu: Label Nomor (Tanpa Badge) -->
                    <div class="mb-3">
                        <span class="text-[10px] sm:text-[10.5px] font-semibold text-[#8A8A8A] uppercase tracking-[0.16em] font-body block">
                            03 / ONLINE ORDER
                        </span>
                    </div>

                    <!-- Body Row: Teks & Aksi di Kiri + Foto Arch Latte di Kanan -->
                    <div class="flex items-start justify-between gap-3 sm:gap-4 flex-1">
                        <div class="flex-1 min-w-0 flex flex-col justify-between h-full">
                            <div>
                                <!-- Ikon GrabFood Hijau -->
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#00B14F] flex items-center justify-center text-white shrink-0 shadow-2xs group-hover:scale-105 transition-transform mb-3" aria-hidden="true">
                                    <svg class="w-5 h-5 text-white fill-current" viewBox="0 0 24 24">
                                        <path d="M19 17h2c.55 0 1-.45 1-1v-3c0-.55-.45-1-1-1h-1V9c0-.55-.45-1-1-1h-4V6c0-.55-.45-1-1-1H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h1.18c.41 1.16 1.52 2 2.82 2s2.41-.84 2.82-2h4.36c.41 1.16 1.52 2 2.82 2s2.41-.84 2.82-2H19c.55 0 1-.45 1-1s-.45-1-1-1zm-11 2c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm10 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zM4 8h10v6H4V8zm12 2h3v4h-3v-4z"/>
                                    </svg>
                                </div>
                                <h4 class="font-body text-lg sm:text-xl font-semibold text-[#1C1C1C] leading-snug tracking-tight mb-2">
                                    Pesan Menu Favoritmu di GrabFood
                                </h4>
                                <p class="font-body text-xs text-[#666666] leading-relaxed font-normal">
                                    Dapatkan menu Nucomu favoritmu melalui aplikasi GrabFood dengan proses yang praktis dan cepat.
                                </p>
                            </div>

                            <!-- Tombol Pesan Sekarang (Pill Gray di Kiri Bawah) -->
                            <div class="pt-4 mt-auto">
                                <a href="https://food.grab.com" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="inline-flex items-center gap-2 bg-[#F0F0F0] hover:bg-[#242424] text-[#1C1C1C] hover:text-white border border-[#E5E5E5] hover:border-[#242424] font-semibold text-xs uppercase tracking-wider px-5 py-2.5 rounded-full shadow-2xs hover:shadow-sm transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                                    <span>Pesan Sekarang</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Visual Kanan: Foto Arch/Dome Kopi Latte (Sesuai Referensi Pengguna) -->
                        <div class="w-[95px] h-[135px] sm:w-[110px] sm:h-[155px] rounded-l-full overflow-hidden shrink-0 shadow-2xs group-hover:scale-105 transition-transform duration-500 self-center">
                            <img src="{{ asset('images/nucomu/latte_grabfood.jpg') }}" 
                                 alt="Kopi Latte Nucomu di GrabFood" 
                                 class="w-full h-full object-cover" 
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL DETAIL RESERVASI (TERBUKA DI BAWAH KARTU 1 / BARIS 2) -->
            <!-- Tablet: Tepat di bawah Kartu 1 (order-2) -->
            <!-- Desktop: Baris 2 Full Width (lg:order-4 lg:col-span-3) -->
            <!-- Mobile: Tepat di bawah Kartu 1 (order-2) -->
            <div id="layanan-detail-panel" 
                 role="region" 
                 aria-labelledby="layanan-detail-title"
                 aria-hidden="true"
                 class="layanan-detail-wrapper hidden col-span-1 md:col-span-2 lg:col-span-3 order-2 lg:order-4 transition-all duration-400 ease-out overflow-hidden">
                
                <div id="layanan-detail-card" 
                     class="relative bg-white rounded-[20px] p-6 sm:p-7 lg:p-7 border border-[#E5E5E5] shadow-sm">
                    
                    <!-- Notch Penunjuk ke Kartu 1 (Berwarna sama dengan latar panel & border menyambung) -->
                    <div id="layanan-panel-notch" 
                         class="absolute -top-2.5 w-5 h-5 bg-white border-t border-l border-[#E5E5E5] transform rotate-45 -translate-x-1/2 z-10 transition-all duration-300" 
                         aria-hidden="true"></div>

                    <!-- 3-Column Content Layout + Foto Meja Indoor Nucomu di Kanan (28-30% lebar) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 lg:gap-5 xl:gap-7 items-start">
                        
                        <!-- KOLOM 1: JUDUL & AKSI FORM RESERVASI -->
                        <div class="lg:col-span-3 space-y-3.5 pr-0 lg:pr-3.5 xl:pr-5 lg:border-r lg:border-[#E5E5E5] flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] sm:text-[10.5px] font-semibold text-[#8A8A8A] uppercase tracking-[0.16em] font-body block mb-1.5">
                                    01 / RESERVASI
                                </span>
                                <h3 id="layanan-detail-title" class="font-body text-xl sm:text-[22px] font-semibold text-[#1C1C1C] leading-snug tracking-tight">
                                    Reservasi Meja &amp; Acara Privat
                                </h3>
                                <p class="font-body text-xs text-[#666666] leading-relaxed font-normal mt-2">
                                    Pesan meja khusus untuk kebutuhan kerja tim, ulang tahun, gathering, atau momen intim bersama pasangan agar kepastian tempat terjaga tanpa perlu khawatir kehabisan area favorit.
                                </p>
                            </div>

                            <div class="pt-1">
                                <button type="button" 
                                        id="btn-layanan-open-reservation"
                                        onclick="openReservationWithCurrentCategory()" 
                                        class="w-full min-h-[42px] inline-flex items-center justify-center gap-2 bg-[#242424] hover:bg-[#1C1C1C] text-white text-xs font-bold uppercase tracking-wider px-5 py-3 rounded-full shadow-md hover:shadow-lg transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                                    <span>Buka Form Reservasi Meja</span>
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                </button>
                                <span class="text-[10.5px] text-[#8A8A8A] font-medium font-body flex items-center justify-center gap-1.5 mt-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#8A8A8A]" aria-hidden="true"></span>
                                    <span>Konfirmasi cepat via WhatsApp</span>
                                </span>
                            </div>
                        </div>

                        <!-- KOLOM 2: PILIHAN KATEGORI ACARA & CHECKLIST (SEBARIS & KOMPAK) -->
                        <div class="lg:col-span-3 space-y-3 pt-6 md:pt-0 px-0 md:px-4 lg:px-3.5 xl:px-5 lg:border-r lg:border-[#E5E5E5]">
                            <div>
                                <span class="text-[10px] sm:text-[10.5px] font-semibold text-[#8A8A8A] uppercase tracking-[0.16em] font-body block mb-2">
                                    PILIHAN KATEGORI ACARA
                                </span>
                                
                                <!-- Pilihan Paket Dibuat Sebaris (1 Row Grid) -->
                                <div class="grid grid-cols-2 gap-1.5 sm:gap-2 mb-2.5" role="tablist" aria-label="Pilihan Kategori Acara">
                                    <button type="button"
                                            id="layanan-chip-bridal"
                                            role="tab"
                                            aria-selected="true"
                                            aria-controls="layanan-desc-bridal"
                                            onclick="selectLayananCategoryChip('Bridal')"
                                            class="layanan-chip w-full min-h-[36px] sm:min-h-[38px] px-1 sm:px-2 py-1.5 sm:py-2 rounded-full text-[9.5px] sm:text-[11px] xl:text-xs font-semibold tracking-tight sm:tracking-normal uppercase transition-all duration-200 bg-[#242424] text-white shadow-xs focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2 inline-flex items-center justify-center text-center whitespace-nowrap">
                                        Paket Bridal
                                    </button>
                                    <button type="button"
                                            id="layanan-chip-birthday"
                                            role="tab"
                                            aria-selected="false"
                                            aria-controls="layanan-desc-birthday"
                                            onclick="selectLayananCategoryChip('Birthday')"
                                            class="layanan-chip w-full min-h-[36px] sm:min-h-[38px] px-1 sm:px-2 py-1.5 sm:py-2 rounded-full text-[9.5px] sm:text-[11px] xl:text-xs font-semibold tracking-tight sm:tracking-normal uppercase transition-all duration-200 bg-white border border-[#CCCCCC] text-[#444444] hover:border-[#8A8A8A] hover:text-[#1C1C1C] focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2 inline-flex items-center justify-center text-center whitespace-nowrap">
                                        Paket Birthday
                                    </button>
                                </div>

                                <!-- Keterangan Singkat Kategori (Compact Box) -->
                                <div class="rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] p-2.5 sm:p-3 transition-all duration-300 mb-2.5">
                                    <div id="layanan-desc-bridal" class="transition-opacity duration-300">
                                        <p class="text-[11px] sm:text-xs text-[#444444] font-body leading-snug">
                                             Momen manis bersama sahabat menjelang hari bahagia. Tersedia opsi penataan meja khusus, lilin perayaan, serta sajian personal hangat.
                                        </p>
                                    </div>
                                    <div id="layanan-desc-birthday" class="hidden opacity-0 transition-opacity duration-300">
                                        <p class="text-[11px] sm:text-xs text-[#444444] font-body leading-snug">
                                            Perayaan ulang tahun intim dan berkesan di Nucomu. Tersedia penataan meja perayaan, lilin kejutan, hingga sajian cake artisanal spesial.
                                        </p>
                                    </div>
                                    <span class="block text-[10px] text-[#8A8A8A] font-body mt-1.5">
                                        Rincian dan pilihan Paket Dekorasi A, B, &amp; C dapat dipilih langsung di formulir reservasi.
                                    </span>
                                </div>

                                <!-- Checklist Asli (2 Item) Compact -->
                                <ul class="space-y-2 text-[11px] sm:text-xs text-[#444444] font-body" aria-label="Keunggulan Reservasi">
                                    <li class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#242424] shrink-0" aria-hidden="true">
                                            <svg class="w-2.5 h-2.5 text-[#242424]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                        </span>
                                        <span>Bebas pilih area Indoor AC sejuk atau Outdoor Garden asri</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#242424] shrink-0" aria-hidden="true">
                                            <svg class="w-2.5 h-2.5 text-[#242424]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                        </span>
                                        <span>Dukungan penataan meja, bunga, &amp; ucapan personal khusus</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- KOLOM 3: CARA RESERVASI (3 LANGKAH DENGAN GARIS PENGHUBUNG VERTIKAL) -->
                        <div class="lg:col-span-3 space-y-3 pt-6 md:pt-0 px-0 md:px-4 lg:px-3.5 xl:px-5 lg:border-r lg:border-[#E5E5E5]">
                            <div>
                                <span class="text-[10px] sm:text-[10.5px] font-semibold text-[#8A8A8A] uppercase tracking-[0.16em] font-body block mb-2.5">
                                    CARA RESERVASI
                                </span>
                                
                                <div class="relative pl-6 space-y-3">
                                    <!-- Garis Penghubung Vertikal Tipis -->
                                    <div class="absolute left-2.5 top-2.5 bottom-2.5 w-[1px] bg-[#E5E5E5]" aria-hidden="true"></div>

                                    <!-- Langkah 1 -->
                                    <div class="relative flex items-start gap-2.5">
                                        <span class="absolute -left-6 top-0.5 w-5 h-5 rounded-full bg-[#242424] text-white text-[10px] font-bold flex items-center justify-center shrink-0 font-body z-10 shadow-2xs">
                                            1
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-semibold text-[#1C1C1C] font-body">Pilih Paket</h4>
                                            <p class="text-[10.5px] text-[#666666] font-body leading-snug mt-0.5">
                                                Tentukan kebutuhan momen Bridal, Birthday, atau Meja Reguler.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Langkah 2 -->
                                    <div class="relative flex items-start gap-2.5">
                                        <span class="absolute -left-6 top-0.5 w-5 h-5 rounded-full bg-[#242424] text-white text-[10px] font-bold flex items-center justify-center shrink-0 font-body z-10 shadow-2xs">
                                            2
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-semibold text-[#1C1C1C] font-body">Isi Formulir</h4>
                                            <p class="text-[10.5px] text-[#666666] font-body leading-snug mt-0.5">
                                                Lengkapi data nama, WhatsApp, tanggal, jam, &amp; jumlah tamu.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Langkah 3 -->
                                    <div class="relative flex items-start gap-2.5">
                                        <span class="absolute -left-6 top-0.5 w-5 h-5 rounded-full bg-[#242424] text-white text-[10px] font-bold flex items-center justify-center shrink-0 font-body z-10 shadow-2xs">
                                            3
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-semibold text-[#1C1C1C] font-body">Konfirmasi WhatsApp</h4>
                                            <p class="text-[10.5px] text-[#666666] font-body leading-snug mt-0.5">
                                                Rincian terkirim ke WhatsApp resmi Nucomu untuk finalisasi meja.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM 4: FOTO MEJA CAFE NUCOMU (PERSEGI / 1:1 SQUARE) DENGAN KONTROL INDOOR & OUTDOOR -->
                        <div class="lg:col-span-3 pt-6 md:pt-0 w-full flex items-center justify-center self-center">
                            <div class="relative w-full aspect-square max-w-[220px] sm:max-w-[280px] lg:max-w-none rounded-[22px] overflow-hidden border border-[#E5E5E5] shadow-xs group select-none bg-neutral-100">
                                
                                <!-- Foto Utama (Pergantian Langsung Bebas Animasi Otomatis) -->
                                <img id="layanan-photo-img"
                                     src="{{ asset('images/nucomu/nucomu_story_4.png') }}" 
                                     alt="Area Meja Indoor & Sudut Nucomu Cafe" 
                                     class="w-full h-full object-cover select-none pointer-events-none" 
                                     loading="lazy">
                                
                                <!-- Gradient Overlay Atas & Bawah untuk Kontras Visual Elegan -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-black/20 pointer-events-none" aria-hidden="true"></div>

                                <!-- Top-Left Badge: Nucomu Monogram Card (Sesuai Referensi Visual) -->
                                <div class="absolute top-2.5 sm:top-3 left-2.5 sm:left-3 z-10 bg-white/95 backdrop-blur-xs rounded-xl sm:rounded-2xl p-1.5 sm:p-2 border border-black/5 shadow-sm flex flex-col items-center justify-center pointer-events-none" aria-hidden="true">
                                    <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu Logo" class="w-4 h-4 sm:w-5 sm:h-5 object-contain">
                                    <span class="text-[7.5px] sm:text-[8px] font-black text-[#1C1C1C] tracking-[0.16em] uppercase mt-0.5 font-body leading-none">NUCOMU</span>
                                </div>

                                <!-- Navigasi Panah Kiri (<) Manual Slider -->
                                <button type="button"
                                        id="btn-layanan-photo-prev"
                                        onclick="event.stopPropagation(); prevLayananPhoto()"
                                        aria-label="Foto Sebelumnya"
                                        title="Foto Sebelumnya"
                                        class="absolute left-2.5 sm:left-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-8.5 sm:h-8.5 rounded-full bg-black/50 hover:bg-black/85 text-white flex items-center justify-center backdrop-blur-xs transition-all duration-200 cursor-pointer shadow-md active:scale-90 focus:outline-none focus:ring-2 focus:ring-white">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                    </svg>
                                </button>

                                <!-- Navigasi Panah Kanan (>) Manual Slider -->
                                <button type="button"
                                        id="btn-layanan-photo-next"
                                        onclick="event.stopPropagation(); nextLayananPhoto()"
                                        aria-label="Foto Berikutnya"
                                        title="Foto Berikutnya"
                                        class="absolute right-2.5 sm:right-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-8.5 sm:h-8.5 rounded-full bg-black/50 hover:bg-black/85 text-white flex items-center justify-center backdrop-blur-xs transition-all duration-200 cursor-pointer shadow-md active:scale-90 focus:outline-none focus:ring-2 focus:ring-white">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                    </svg>
                                </button>

                                <!-- Floating Caption Card Melayang di Bawah: Memisahkan Area Indoor & Outdoor, Arrow Button Sebagai Switcher -->
                                <div onclick="toggleLayananPhotoArea()"
                                     class="group/pill absolute bottom-2.5 sm:bottom-3 left-2.5 sm:left-3 right-2.5 sm:right-3 z-20 bg-white/95 hover:bg-white backdrop-blur-xs rounded-[14px] p-2 sm:p-2.5 shadow-md border border-black/5 flex items-center justify-between gap-2 cursor-pointer transition-all duration-200"
                                     role="button"
                                     tabindex="0"
                                     aria-label="Ganti ke Area Outdoor"
                                     onkeydown="if(event.key==='Enter'||event.key===' ') { event.preventDefault(); toggleLayananPhotoArea(); }">
                                    
                                    <div class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                                        <!-- Icon Badge Kopi Monokrom (Coffee Cup Monogram) -->
                                        <div class="w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-full bg-[#242424] text-white flex items-center justify-center shrink-0 shadow-2xs group-hover/pill:scale-105 transition-transform" aria-hidden="true">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h13a3 3 0 013 3v2a3 3 0 01-3 3H3m0-8v8m0-8h-1a1 1 0 00-1 1v6a1 1 0 001 1h1m0 0v2a1 1 0 001 1h11a1 1 0 001-1v-2M6 4v2m4-2v2m4-2v2"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <h5 id="layanan-photo-title" class="text-xs sm:text-[12.5px] font-bold text-[#1C1C1C] font-body leading-tight truncate">
                                                    Area Indoor
                                                </h5>
                                                <span id="layanan-photo-counter" class="text-[9.5px] text-[#8A8A8A] font-semibold font-body">1/3</span>
                                            </div>
                                            <p id="layanan-photo-subtitle" class="text-[10px] text-[#666666] font-body leading-tight truncate">
                                                Bebas pilih area Indoor AC sejuk
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Tombol Panah Switcher Indoor <-> Outdoor -->
                                    <button type="button" 
                                            id="btn-toggle-layanan-area"
                                            onclick="event.stopPropagation(); toggleLayananPhotoArea()"
                                            title="Ganti ke Area Outdoor"
                                            aria-label="Ganti ke Area Outdoor"
                                            class="w-7 h-7 sm:w-7.5 sm:h-7.5 rounded-full bg-[#F5F5F5] group-hover/pill:bg-[#242424] text-[#1C1C1C] group-hover/pill:text-white border border-[#E5E5E5] group-hover/pill:border-[#242424] flex items-center justify-center shrink-0 transition-all duration-200 cursor-pointer shadow-2xs active:scale-90 focus:outline-none focus:ring-2 focus:ring-[#242424]">
                                        <svg id="icon-toggle-layanan-area" class="w-3.5 h-3.5 transition-transform duration-200 group-hover/pill:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Data Foto Asli Nucomu untuk Galeri Area Indoor & Outdoor -->
    <script>
        window.NUCOMU_LAYANAN_PHOTOS = {
            indoor: [
                {
                    src: "{{ asset('images/nucomu/nucomu_story_4.png') }}",
                    alt: "Area Meja Indoor & Sudut Nucomu Cafe",
                    title: "Area Indoor",
                    subtitle: "Bebas pilih area Indoor AC sejuk"
                },
                {
                    src: "{{ asset('images/nucomu/nucomu_story_2.png') }}",
                    alt: "Indoor Lounge & Meja Kerja Nucomu Cafe",
                    title: "Area Indoor",
                    subtitle: "Bebas pilih area Indoor AC sejuk"
                },
                {
                    src: "{{ asset('images/nucomu/nucomu_story_1.png') }}",
                    alt: "Espresso Bar & Counter Seduh Nucomu Cafe",
                    title: "Area Indoor",
                    subtitle: "Bebas pilih area Indoor AC sejuk"
                }
            ],
            outdoor: [
                {
                    src: "{{ asset('images/nucomu/nucomu_outdoor_2.png') }}",
                    alt: "Outdoor Garden Terrace Nucomu Cafe",
                    title: "Area Outdoor",
                    subtitle: "Bebas pilih area Outdoor Garden asri"
                },
                {
                    src: "{{ asset('images/nucomu/nucomu_outdoor_3.png') }}",
                    alt: "Outdoor Reserved Table Nucomu Cafe",
                    title: "Area Outdoor",
                    subtitle: "Bebas pilih area Outdoor Garden asri"
                },
                {
                    src: "{{ asset('images/nucomu/Beranda Kafe Nucomu yang Hangat.png') }}",
                    alt: "Teras Depan Kafe Nucomu",
                    title: "Area Outdoor",
                    subtitle: "Bebas pilih area Outdoor Garden asri"
                }
            ]
        };
    </script>
</section>
