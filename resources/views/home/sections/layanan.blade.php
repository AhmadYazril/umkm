<!-- SECTION 7: LAYANAN & PEMESANAN (SERVICES & ORDERING) -->
<section id="layanan" class="py-10 sm:py-14 bg-[#FAF8F5] border-b border-light-gray/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header (Compact Spacing, Large Font) -->
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10 reveal-on-scroll">
            <div class="inline-flex items-center space-x-2 px-3 py-0.5 rounded-full bg-[#E06D24]/10 border border-[#E06D24]/20 text-[#E06D24] text-[11px] font-bold uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                <span>Kemudahan Akses &amp; Reservasi</span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
            </div>
            <h2 class="font-editorial text-4xl sm:text-5xl lg:text-6xl font-light text-[#2A1D17] leading-tight">
                Layanan &amp; <span class="font-editorial italic font-normal text-[#E06D24]">Pemesanan</span>
            </h2>
            <p class="font-body text-xs sm:text-sm text-[#5C4E45] font-normal mt-2 max-w-lg mx-auto leading-relaxed">
                Pilih cara terbaik untuk menikmati sajian kopi dan suasana hangat Nucomu Cafe sesuai kebutuhan Anda.
            </p>
        </div>

        <!-- ASYMMETRICAL SERVICES BENTO GRID (1 ROW ON DESKTOP: 7 COLS / 5 COLS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-stretch">
            
            <!-- LEFT FEATURED CARD: RESERVASI MEJA & ACARA PRIVAT (7 COLS) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 lg:p-9 border border-[#E06D24]/25 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col justify-between text-left relative overflow-hidden bg-gradient-to-br from-white via-[#FAF8F5] to-[#F7EFE6]/50 reveal-on-scroll">
                
                <!-- Ambient Glow Corner -->
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-[#E06D24]/5 rounded-full blur-2xl pointer-events-none"></div>

                <div>
                    <!-- Top Badge Row -->
                    <div class="flex items-center justify-between mb-3.5">
                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#E06D24] text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                            <span class="material-symbols-outlined text-xs">stars</span>
                            <span>Rekomendasi Momen</span>
                        </span>
                        <span class="text-[11px] font-bold text-[#E06D24] uppercase tracking-widest">
                            01 / DINE-IN &amp; PRIVATE EVENTS
                        </span>
                    </div>

                    <!-- Title (Large & Prominent) -->
                    <h3 class="font-editorial text-3xl sm:text-4xl lg:text-[2.6rem] font-bold text-[#2A1D17] leading-tight mb-3">
                        Reservasi Meja &amp; Acara Privat
                    </h3>

                    <!-- Narrative -->
                    <p class="font-body text-xs sm:text-sm text-[#5C4E45] leading-relaxed mb-4">
                        Pesan meja khusus untuk kebutuhan kerja tim, ulang tahun, gathering, atau momen intim bersama pasangan agar kepastian tempat terjaga tanpa perlu khawatir kehabisan area favorit.
                    </p>

                    <!-- Pilihan Momen Chips (Bridal & Birthday Packages - Pilih Kategori Dulu) -->
                    <div class="mb-5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500 block mb-2">
                            Pilih Kategori Paket Acara (Klik untuk melihat pilihan paket dekorasi):
                        </span>
                        <div class="flex flex-wrap gap-2 mb-2.5">
                            <button type="button"
                                    id="layanan-btn-bridal"
                                    onclick="toggleLayananPackagePreview('Bridal')"
                                    class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-full bg-white hover:bg-rose-50 border border-stone-200 hover:border-rose-300 text-xs font-bold text-[#54392B] shadow-2xs transition-all">
                                <span class="material-symbols-outlined text-sm text-rose-500">favorite</span>
                                <span>Paket Bridal</span>
                                <span class="material-symbols-outlined text-xs text-neutral-400">expand_more</span>
                            </button>
                            <button type="button"
                                    id="layanan-btn-birthday"
                                    onclick="toggleLayananPackagePreview('Birthday')"
                                    class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-full bg-white hover:bg-amber-50 border border-stone-200 hover:border-amber-300 text-xs font-bold text-[#54392B] shadow-2xs transition-all">
                                <span class="material-symbols-outlined text-sm text-[#E06D24]">cake</span>
                                <span>Paket Birthday</span>
                                <span class="material-symbols-outlined text-xs text-neutral-400">expand_more</span>
                            </button>
                        </div>

                        <div id="layanan-package-options-wrap" class="hidden rounded-2xl bg-white/95 border border-[#E06D24]/30 p-3.5 shadow-sm transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <span id="layanan-selected-cat-label" class="text-[11px] font-bold uppercase tracking-wider text-[#E06D24]">
                                    Pilihan Paket Acara Bridal — Klik paket untuk reservasi:
                                </span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[11px]">
                                <div onclick="pickLayananPackageAndOpenModal('A', 'Paket Dekorasi A', '35K', 'Dekorasi meja, lilin, dipinjami korek, lettering ucapan')"
                                     class="p-2.5 rounded-xl bg-[#FAF8F5] hover:bg-white border border-stone-200 hover:border-[#E06D24] cursor-pointer transition-all group">
                                    <div class="flex items-center justify-between font-bold text-[#2A1D17] mb-0.5">
                                        <span class="group-hover:text-[#E06D24] transition-colors">Paket Dekorasi A</span>
                                        <span class="text-[#E06D24]">35K</span>
                                    </div>
                                    <p class="text-[10px] text-[#5C4E45] leading-snug">Dekorasi meja, lilin, dipinjami korek, lettering ucapan</p>
                                </div>
                                <div onclick="pickLayananPackageAndOpenModal('B', 'Paket Dekorasi B', '60K', 'Paket 35K + cheesecake slice 1')"
                                     class="p-2.5 rounded-xl bg-[#FAF8F5] hover:bg-white border border-stone-200 hover:border-[#E06D24] cursor-pointer transition-all group">
                                    <div class="flex items-center justify-between font-bold text-[#2A1D17] mb-0.5">
                                        <span class="group-hover:text-[#E06D24] transition-colors">Paket Dekorasi B</span>
                                        <span class="text-[#E06D24]">60K</span>
                                    </div>
                                    <p class="text-[10px] text-[#5C4E45] leading-snug">Paket 35K + cheesecake slice 1</p>
                                </div>
                                <div onclick="pickLayananPackageAndOpenModal('C', 'Paket Dekorasi C', '100K', 'Paket 35K + 1 whole cheesecake diameter 10cm + 1 minuman nucomu coffee')"
                                     class="p-2.5 rounded-xl bg-[#FAF8F5] hover:bg-white border border-stone-200 hover:border-[#E06D24] cursor-pointer transition-all group">
                                    <div class="flex items-center justify-between font-bold text-[#2A1D17] mb-0.5">
                                        <span class="group-hover:text-[#E06D24] transition-colors">Paket Dekorasi C</span>
                                        <span class="text-[#E06D24]">100K</span>
                                    </div>
                                    <p class="text-[10px] text-[#5C4E45] leading-snug">Paket 35K + 1 whole cheesecake Ø 10cm + 1 minuman nucomu coffee</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist (Tight Spacing) -->
                    <ul class="space-y-2 text-xs text-[#5C4E45] mb-6">
                        <li class="flex items-center space-x-2.5">
                            <span class="material-symbols-outlined text-[#E06D24] text-lg">check_circle</span>
                            <span>Bebas pilih area Indoor AC sejuk atau Outdoor Garden asri</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="material-symbols-outlined text-[#E06D24] text-lg">check_circle</span>
                            <span>Dukungan penataan meja, bunga, &amp; ucapan personal khusus</span>
                        </li>
                    </ul>
                </div>

                <!-- CTA Action Row -->
                <div class="pt-4 border-t border-stone-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <button type="button" 
                            onclick="openReservationModal('Reservasi Meja & Acara Privat')" 
                            class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded-full shadow-md shadow-orange-500/20 transition-all duration-200 active:scale-95">
                        <span>Buka Form Reservasi Meja</span>
                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                    </button>
                    <span class="text-[11px] text-neutral-500 font-medium">
                        &bull; Konfirmasi cepat via WhatsApp
                    </span>
                </div>
            </div>

            <!-- RIGHT COMPANION STACK: WHATSAPP DIRECT & GRABFOOD (5 COLS) -->
            <div class="lg:col-span-5 flex flex-col justify-between gap-4 lg:gap-5">
                
                <!-- CARD 1: WHATSAPP DIRECT DELIVERY -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 lg:p-6 border border-stone-200/90 shadow-md hover:border-emerald-500/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between text-left group reveal-on-scroll relative overflow-hidden flex-1">
                    <div>
                        <!-- Top Row: Icon + Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-2xs group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.067-2.228-.567-1.78-.737-2.909-2.544-2.998-2.663-.088-.119-.724-.962-.724-1.834 0-.872.457-1.302.62-1.479.163-.177.356-.222.475-.222.119 0 .238.002.341.008.109.006.255-.041.399.305.148.356.505 1.233.549 1.322.044.089.073.193.015.311-.059.119-.089.193-.178.297-.089.104-.187.232-.267.311-.089.089-.182.185-.078.363.104.178.462.763.992 1.235.684.609 1.261.798 1.439.887.178.089.282.074.386-.044.104-.119.444-.518.563-.696.119-.178.238-.148.399-.089.163.059 1.034.488 1.212.577.178.089.297.133.341.207.044.074.044.43-.1.835z"/>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold text-[#E06D24] uppercase tracking-widest">
                                    02 / DIRECT COURIER
                                </span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">
                                Respon &lt; 15 Mnt
                            </span>
                        </div>

                        <!-- Card Title (Large Font) -->
                        <h4 class="font-editorial text-2xl sm:text-[1.65rem] font-bold text-[#2A1D17] leading-tight mb-1.5">
                            Pesan Delivery via WhatsApp
                        </h4>
                        <p class="font-body text-xs text-[#5C4E45] leading-relaxed mb-3">
                            Pesan langsung melalui WhatsApp resmi Nucomu atau kurir Zendo Tulungagung untuk pengiriman pesanan kopi hangat sampai ke tempat Anda.
                        </p>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            <span class="px-2.5 py-0.5 rounded-full bg-stone-100 text-[10px] font-semibold text-neutral-700">Kurir Zendo Tulungagung</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-stone-100 text-[10px] font-semibold text-neutral-700">Kemasan Aman &amp; Hangat</span>
                        </div>
                    </div>

                    <!-- Bottom Link / Button -->
                    <div class="pt-3 border-t border-stone-100">
                        <a href="{{ route('nucomu.order', ['mode' => 'zendo']) }}" 
                           class="inline-flex items-center justify-between w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-full shadow-sm shadow-emerald-600/20 transition-all duration-200 active:scale-95">
                            <span>Pilih Menu &amp; Pesan via Zendo</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- CARD 2: GRABFOOD ONLINE DELIVERY -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 lg:p-6 border border-stone-200/90 shadow-md hover:border-green-500/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between text-left group reveal-on-scroll relative overflow-hidden flex-1">
                    <div>
                        <!-- Top Row: Icon + Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center border border-green-100 shadow-2xs group-hover:scale-105 transition-transform">
                                    <span class="material-symbols-outlined text-xl">two_wheeler</span>
                                </div>
                                <span class="text-[10px] font-bold text-[#E06D24] uppercase tracking-widest">
                                    03 / ONLINE DELIVERY APP
                                </span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200 text-[10px] font-bold uppercase tracking-wider">
                                Promo Merchant
                            </span>
                        </div>

                        <!-- Card Title (Large Font) -->
                        <h4 class="font-editorial text-2xl sm:text-[1.65rem] font-bold text-[#2A1D17] leading-tight mb-1.5">
                            Pesan Online via GrabFood
                        </h4>
                        <p class="font-body text-xs text-[#5C4E45] leading-relaxed mb-3">
                            Nikmati kemudahan pemesanan instan, promo voucher diskon aplikasi, dan fleksibilitas metode pembayaran digital melalui aplikasi GrabFood resmi Nucomu.
                        </p>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            <span class="px-2.5 py-0.5 rounded-full bg-stone-100 text-[10px] font-semibold text-neutral-700">Menu Lengkap &amp; Update</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-stone-100 text-[10px] font-semibold text-neutral-700">Banyak Promo Diskon</span>
                        </div>
                    </div>

                    <!-- Bottom Link / Button -->
                    <div class="pt-3 border-t border-stone-100">
                        <a href="https://food.grab.com" 
                           target="_blank" 
                           class="inline-flex items-center justify-between w-full bg-green-600 hover:bg-green-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-full shadow-sm shadow-green-600/20 transition-all duration-200 active:scale-95">
                            <span>Buka di GrabFood</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
