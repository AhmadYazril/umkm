<!-- =========================================================================
     NUCOMU FLOATING CART & CHECKOUT MODAL
     Palette: Warm Monochrome (Espresso #2c2825, Warm Charcoal #3a3632, Warm Stone #ded8ce, Krem #faf8f5, Pure White)
     Floats in the center of the screen ("ngambang di tengah")
     Items accumulate on add-to-cart ("di timbun di keranjang") and only open on cart button click.
     ========================================================================= -->

@php
    $txSettings = $txSettings ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? (new \App\Http\Controllers\NucomuController())->getTransactionSettings() : []);
    $transactionMode = $transactionMode ?? ($txSettings['transaction_mode'] ?? 'both');
    $zendoWhatsapp = $zendoWhatsapp ?? ($txSettings['zendo_whatsapp_clean'] ?? '6281234567890');
    $cafeLat = (float) ($txSettings['cafe_lat'] ?? -8.0765099);
    $cafeLng = (float) ($txSettings['cafe_lng'] ?? 111.918712);
    $zendoTariff = $txSettings['zendo_tariff'] ?? [
        'tier_0_3' => 10000,
        'tier_3_4' => 12000,
        'tier_4_5' => 15000,
        'tier_5_6' => 17000,
        'tier_6_7' => 18000,
        'extra_per_km' => 2500,
    ];
    $zendoCouriers = $txSettings['zendo_couriers'] ?? [
        ['index' => 1, 'name' => 'Kurir Zendo 1', 'phone' => $zendoWhatsapp, 'clean_phone' => $zendoWhatsapp]
    ];
    $initialProcessMethod = $transactionMode === 'zendo' ? 'zendo' : 'kasir';
    $hideLiveTracking = (bool) ($txSettings['hide_live_tracking'] ?? false);
@endphp

<style>
    /* Warm monochrome scrollbar for the floating cart card */
    .cart-custom-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(44, 40, 37, 0.3) #faf8f5;
    }
    .cart-custom-scroll::-webkit-scrollbar { width: 6px; }
    .cart-custom-scroll::-webkit-scrollbar-track { background: #faf8f5; border-radius: 9999px; }
    .cart-custom-scroll::-webkit-scrollbar-thumb { background: #ded8ce; border-radius: 9999px; }
    .cart-custom-scroll::-webkit-scrollbar-thumb:hover { background: #9c968f; }

    /* Expandable Menu Options Panel */
    .product-options-panel {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        pointer-events: none;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease, margin 0.3s ease, padding 0.3s ease;
    }
    .popular-dish-card.active .product-options-panel {
        max-height: 500px;
        opacity: 1;
        pointer-events: auto;
        margin-top: 0.85rem;
        padding-top: 0.85rem;
        border-top: 1px dashed rgba(44, 40, 37, 0.15);
    }

    #modalZendoMap { z-index: 10; }
    .leaflet-control-attribution { font-size: 8px !important; opacity: 0.6; }
</style>

<!-- =========================================================================
     1. FLOATING BOTTOM-RIGHT CAPSULE BUTTON (WARM MONOCHROME)
     ========================================================================= -->
<button type="button" 
        id="floating-cart-btn"
        onclick="openCartModal()" 
        class="fixed bottom-6 right-6 z-40 bg-white/95 hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[#2c2825] shadow-[0_10px_30px_rgba(44,40,37,0.14),0_2px_8px_rgba(44,40,37,0.04)] rounded-full px-4 sm:px-5 py-2.5 sm:py-3 flex items-center space-x-3 transition-all duration-300 hover:scale-105 active:scale-95 group focus:outline-none backdrop-blur-md cursor-pointer"
        aria-label="Buka Keranjang Pesanan">
    <div class="relative flex items-center">
        <i class="fa-solid fa-bag-shopping text-[#2c2825] text-base sm:text-lg group-hover:scale-110 transition-transform"></i>
        <span id="floating-cart-badge" 
              class="absolute -top-2.5 -right-2.5 bg-[#2c2825] text-[#faf8f5] text-[10px] font-black w-4 h-4 sm:w-5 sm:h-5 rounded-full flex items-center justify-center shadow-md transition-transform duration-300">
            0
        </span>
    </div>
    <div class="flex flex-col text-left">
        <span class="text-[10px] text-[#78726b] uppercase font-bold tracking-wider leading-none">Keranjang</span>
        <span id="floating-cart-total" class="font-mono text-xs sm:text-sm font-bold text-[#2c2825] leading-tight mt-0.5">
            Rp 0
        </span>
    </div>
</button>

<!-- =========================================================================
     2. FLOATING CENTERED MODAL BACKDROP & CARD (WARM MONOCHROME)
     ========================================================================= -->
<div id="cartModalBackdrop" 
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 md:p-6 bg-[#1a1816]/65 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300"
     onclick="handleBackdropClick(event)">
    
    <!-- Modal Card (Floating in the center) -->
    <div id="cartModalCard" 
         class="relative w-full max-w-2xl max-h-[92vh] flex flex-col rounded-3xl bg-white text-[#2c2825] border border-[#ded8ce] shadow-[0_25px_65px_rgba(44,40,37,0.22)] overflow-hidden scale-95 transition-all duration-300">
        
        <!-- Top Warm Accent Line -->
        <div class="h-1 w-full bg-gradient-to-r from-[#2c2825] via-[#78726b] to-[#2c2825]"></div>

        <!-- =========================================================================
             A. MODAL HEADER
             ========================================================================= -->
        <div class="relative z-10 px-5 sm:px-6 py-4 border-b border-[#ece7de] bg-[#faf8f5] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white border border-[#ded8ce] p-0.5 shadow-xs shrink-0 flex items-center justify-center text-[#2c2825]">
                    <i class="fa-solid fa-mug-hot text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-extrabold text-[#2c2825] font-serif tracking-tight flex items-center gap-2">
                        <span>Keranjang & Checkout Nucomu</span>
                        <span id="modalHeaderCountBadge" class="px-2 py-0.5 rounded-full bg-white text-[#2c2825] text-[10px] font-mono font-bold border border-[#ded8ce]">0 Item</span>
                    </h3>
                    <p class="text-[11px] text-[#78726b]">Atur rincian pesanan dan proses ke Kasir atau Kurir Zendo</p>
                </div>
            </div>

            <button type="button" 
                    onclick="closeCartModal()" 
                    class="w-8 h-8 rounded-full bg-white hover:bg-[#ece7de] border border-[#ded8ce] text-[#5c5751] hover:text-[#2c2825] flex items-center justify-center transition-colors focus:outline-none cursor-pointer"
                    aria-label="Tutup Keranjang">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- =========================================================================
             B. VIEW 1: ORDER FORM (Metode, List Pesanan, Data Pemesan & Map)
             ========================================================================= -->
        <div id="cartModalFormView" class="relative z-10 flex-1 flex flex-col overflow-hidden">
            <!-- Scrollable Content Body -->
            <div class="flex-1 overflow-y-auto cart-custom-scroll p-4 sm:p-6 space-y-4">
                
                <!-- 1. METODE PROSES TRANSAKSI -->
                <div class="bg-[#faf8f5] rounded-2xl p-4 sm:p-5 border border-[#ded8ce] space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs sm:text-sm font-bold text-[#2c2825] flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-[#2c2825]"></i>
                            <span>1. Metode Proses Transaksi</span>
                        </h4>
                        @if($transactionMode === 'both')
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white text-[#2c2825] border border-[#ded8ce]">
                                Kasir / Zendo
                            </span>
                        @elseif($transactionMode === 'zendo')
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white text-[#2c2825] border border-[#ded8ce]">
                                Zendo Aktif
                            </span>
                        @else
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white text-[#2c2825] border border-[#ded8ce]">
                                Kasir Aktif
                            </span>
                        @endif
                    </div>

                    @if($transactionMode === 'both')
                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- Option Kasir -->
                            <button type="button" 
                                    id="modalMethodBtnKasir"
                                    onclick="setModalProcessMethod('kasir')" 
                                    class="p-3 sm:p-3.5 rounded-xl border text-left transition-all flex flex-col justify-between cursor-pointer {{ $initialProcessMethod === 'kasir' ? 'border-[#2c2825] bg-white text-[#2c2825] shadow-xs' : 'border-[#ded8ce] bg-[#faf8f5] text-[#78726b] hover:border-[#9c968f]' }}">
                                <div class="flex items-center justify-between mb-1.5">
                                    <i class="fa-solid fa-qrcode text-base {{ $initialProcessMethod === 'kasir' ? 'text-[#2c2825]' : 'text-[#78726b]' }}" id="modalIconKasir"></i>
                                    <span class="w-2.5 h-2.5 rounded-full {{ $initialProcessMethod === 'kasir' ? 'bg-[#2c2825]' : 'bg-[#ded8ce]' }}" id="modalDotKasir"></span>
                                </div>
                                <span class="text-xs sm:text-sm font-extrabold block leading-tight text-[#2c2825]">Proses Kasir</span>
                                <span class="text-[10px] text-[#78726b] mt-0.5 block">Slip & QR Kasir</span>
                            </button>

                            <!-- Option Kurir Zendo -->
                            <button type="button" 
                                    id="modalMethodBtnZendo"
                                    onclick="setModalProcessMethod('zendo')" 
                                    class="p-3 sm:p-3.5 rounded-xl border text-left transition-all flex flex-col justify-between cursor-pointer {{ $initialProcessMethod === 'zendo' ? 'border-[#2c2825] bg-white text-[#2c2825] shadow-xs' : 'border-[#ded8ce] bg-[#faf8f5] text-[#78726b] hover:border-[#9c968f]' }}">
                                <div class="flex items-center justify-between mb-1.5">
                                    <i class="fa-solid fa-motorcycle text-base {{ $initialProcessMethod === 'zendo' ? 'text-[#2c2825]' : 'text-[#78726b]' }}" id="modalIconZendo"></i>
                                    <span class="w-2.5 h-2.5 rounded-full {{ $initialProcessMethod === 'zendo' ? 'bg-[#2c2825]' : 'bg-[#ded8ce]' }}" id="modalDotZendo"></span>
                                </div>
                                <span class="text-xs sm:text-sm font-extrabold block leading-tight text-[#2c2825]">Kurir Zendo</span>
                                <span class="text-[10px] text-[#78726b] mt-0.5 block">Peta & Kirim Otomatis</span>
                            </button>
                        </div>
                    @elseif($transactionMode === 'zendo')
                        <div class="p-3.5 rounded-xl border border-[#ded8ce] bg-white flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#faf8f5] text-[#2c2825] border border-[#ded8ce] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-motorcycle text-base"></i>
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-[#2c2825]">Delivery Kurir Zendo</p>
                                <p class="text-[11px] text-[#78726b]">Pilih titik alamat di peta, ongkir dihitung otomatis & pesanan dikirim ke Kurir Zendo.</p>
                            </div>
                        </div>
                    @else
                        <div class="p-3.5 rounded-xl border border-[#ded8ce] bg-white flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#faf8f5] text-[#2c2825] border border-[#ded8ce] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-qrcode text-base"></i>
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-[#2c2825]">Transaksi Kasir (QR Slip)</p>
                                <p class="text-[11px] text-[#78726b]">Pilih menu favorit Anda lalu tunjukkan slip QR ke kasir untuk pembayaran.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 2. DAFTAR MENU PESANAN -->
                <div class="bg-[#faf8f5] rounded-2xl p-4 sm:p-5 border border-[#ded8ce] space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs sm:text-sm font-bold text-[#2c2825] flex items-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-[#2c2825]"></i>
                            <span>2. Daftar Menu Pesanan</span>
                        </h4>
                        <span id="modalCartCountBadge" class="text-[10px] font-mono font-bold text-[#78726b]">0 Item</span>
                    </div>

                    <!-- Items Container -->
                    <div id="modalCartItems" class="space-y-2.5 max-h-48 overflow-y-auto cart-custom-scroll pr-1 min-h-[55px]">
                        <!-- Populated by renderModalCart() -->
                    </div>

                    <!-- Empty State -->
                    <div id="modalEmptyCart" class="text-center py-6 text-[#78726b]">
                        <i class="fa-solid fa-bag-shopping text-2xl opacity-25 block mb-1.5 text-[#2c2825]"></i>
                        <p class="text-xs">Belum ada menu dipilih. Klik <strong class="text-[#2c2825]">+ Tambah</strong> pada menu pilihan Anda di halaman utama.</p>
                    </div>

                    <!-- Pricing Breakdown -->
                    <div id="modalCartTotalBreakdown" class="hidden border-t border-[#ece7de] pt-3 space-y-1.5 text-xs">
                        <div class="flex justify-between text-[#78726b]">
                            <span>Subtotal Menu</span>
                            <span id="modalSubtotalAmount" class="font-mono text-[#2c2825] font-semibold">Rp 0</span>
                        </div>

                        <div id="modalZendoFeeRow" class="hidden justify-between text-[#2c2825] font-semibold">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-motorcycle text-[10px]"></i>
                                <span>Ongkir Zendo (<span id="modalCartDistanceText">1,5 km</span>)</span>
                            </span>
                            <span id="modalDeliveryFeeAmount" class="font-mono font-bold">Rp 10.000</span>
                        </div>

                        <div class="flex justify-between items-center text-sm pt-2 border-t border-[#ece7de]">
                            <span class="font-bold text-[#2c2825]">Total Pembayaran</span>
                            <span id="modalTotalAmount" class="font-mono text-base sm:text-lg text-[#2c2825] font-black">Rp 0</span>
                        </div>

                        <p id="modalCartPaymentHint" class="text-[10px] text-[#78726b] pt-0.5 italic">
                            *Bayar di kasir setelah menunjukkan slip QR.
                        </p>
                    </div>
                </div>

                <!-- 3. DATA PEMESAN & LOKASI -->
                <div class="bg-[#faf8f5] rounded-2xl p-4 sm:p-5 border border-[#ded8ce] space-y-4">
                    <h4 class="text-xs sm:text-sm font-bold text-[#2c2825] flex items-center gap-2">
                        <i class="fa-solid fa-user text-[#2c2825]"></i>
                        <span>3. Data Pemesan & Lokasi</span>
                    </h4>

                    <!-- Nama Pemesan -->
                    <div>
                        <label for="modalCustomerName" class="block text-[11px] font-semibold text-[#2c2825] mb-1">
                            Nama Pemesan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="modalCustomerName" 
                               placeholder="Masukkan nama lengkap Anda..."
                               class="w-full bg-white border border-[#ded8ce] rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#2c2825] placeholder-[#9c968f] focus:outline-none focus:border-[#2c2825] transition-colors">
                    </div>

                    <!-- KASIR SPECIFIC: Dine In vs Take Away -->
                    <div id="modalKasirFields" class="space-y-2.5 pt-2 border-t border-[#ece7de]">
                        <label class="block text-[11px] font-semibold text-[#2c2825]">
                            Tipe Pesanan
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center space-x-2 p-2.5 rounded-xl bg-white border border-[#ded8ce] cursor-pointer text-xs select-none hover:border-[#2c2825] transition-colors">
                                <input type="radio" name="modal_dine_type" value="dine_in" checked onchange="toggleModalTableInput(true)" class="accent-[#2c2825]">
                                <span class="font-semibold text-[#2c2825]">Makan di Tempat</span>
                            </label>
                            <label class="flex items-center space-x-2 p-2.5 rounded-xl bg-white border border-[#ded8ce] cursor-pointer text-xs select-none hover:border-[#2c2825] transition-colors">
                                <input type="radio" name="modal_dine_type" value="take_away" onchange="toggleModalTableInput(false)" class="accent-[#2c2825]">
                                <span class="font-semibold text-[#2c2825]">Bawa Pulang</span>
                            </label>
                        </div>
                        <div id="modalTableWrap">
                            <input type="text" 
                                   id="modalTableNo" 
                                   placeholder="Nomor Meja (Contoh: Meja 04)" 
                                   class="w-full bg-white border border-[#ded8ce] rounded-xl px-3.5 py-2 text-xs text-[#2c2825] placeholder-[#9c968f] focus:outline-none focus:border-[#2c2825] transition-colors">
                        </div>
                    </div>

                    <!-- ZENDO SPECIFIC: Phone, Leaflet Map & Address -->
                    <div id="modalZendoFields" class="hidden space-y-3.5 pt-2 border-t border-[#ece7de]">
                        <!-- Info Kurir Zendo Siaga -->
                        <div class="bg-white border border-[#ded8ce] rounded-xl p-3 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#2c2825] flex items-center gap-1.5">
                                    <i class="fa-solid fa-motorcycle"></i> Pengiriman Otomatis Zendo
                                </span>
                                <span class="px-2 py-0.5 rounded-full bg-[#faf8f5] border border-[#ded8ce] text-[#2c2825] text-[10px] font-mono font-bold">
                                    {{ count($zendoCouriers) }} Kurir Siaga
                                </span>
                            </div>
                            <p class="text-[10px] text-[#78726b] leading-relaxed">
                                Pesanan langsung diteruskan otomatis ke nomor WhatsApp Kurir Zendo yang sedang standby.
                            </p>
                        </div>

                        <!-- WhatsApp Phone Input -->
                        <div>
                            <label for="modalCustomerPhone" class="block text-[11px] font-semibold text-[#2c2825] mb-1">
                                <i class="fa-brands fa-whatsapp mr-1"></i> Nomor WhatsApp Anda (Konfirmasi Kurir)
                            </label>
                            <input type="tel" 
                                   id="modalCustomerPhone" 
                                   placeholder="Contoh: 081234567890"
                                   class="w-full bg-white border border-[#ded8ce] rounded-xl px-3.5 py-2 text-xs text-[#2c2825] font-mono placeholder-[#9c968f] focus:outline-none focus:border-[#2c2825] transition-colors">
                        </div>

                        @if (! $hideLiveTracking)
                        <!-- Map & GPS Controls (Mode Sekolah / Peta Live Aktif) -->
                        <div class="bg-white border border-[#ded8ce] rounded-2xl p-3.5 space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#2c2825] flex items-center gap-1.5">
                                    <i class="fa-solid fa-map-location-dot"></i> Peta Lokasi Rumah Anda
                                </span>
                                <button type="button" 
                                        onclick="useMyGpsLocation()" 
                                        id="modalGpsBtn"
                                        class="px-2.5 py-1 rounded-lg bg-[#2c2825] hover:bg-[#3a3632] text-white text-[10px] font-bold transition-all flex items-center gap-1 cursor-pointer">
                                    <i class="fa-solid fa-location-crosshairs"></i>
                                    <span>GPS Saya</span>
                                </button>
                            </div>

                            <!-- Search Input Tulungagung -->
                            <div class="relative">
                                <div class="flex gap-1.5">
                                    <div class="relative flex-1">
                                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#78726b] text-xs"></i>
                                        <input type="text" 
                                               id="modalMapSearchInput"
                                               placeholder="Cari daerah / desa / jalan di Tulungagung..."
                                               onkeydown="if(event.key==='Enter'){event.preventDefault();searchModalMapLocation();}"
                                               class="w-full bg-[#faf8f5] border border-[#ded8ce] rounded-xl pl-8 pr-3 py-2 text-xs text-[#2c2825] placeholder-[#9c968f] focus:outline-none focus:border-[#2c2825]">
                                    </div>
                                    <button type="button" 
                                            onclick="searchModalMapLocation()" 
                                            id="modalMapSearchBtn"
                                            class="px-3.5 py-2 rounded-xl bg-[#2c2825] hover:bg-[#3a3632] text-white font-extrabold text-xs transition-all shrink-0 cursor-pointer">
                                        Cari
                                    </button>
                                </div>
                                <div id="modalMapSearchResults" class="hidden mt-1.5 bg-white border border-[#ded8ce] rounded-xl overflow-hidden shadow-2xl max-h-44 overflow-y-auto divide-y divide-[#ece7de] z-30 relative"></div>
                            </div>

                            <!-- Preset Area Chips Tulungagung -->
                            <div>
                                <span class="text-[10px] text-[#78726b] block mb-1.5">Pilih Cepat Area Tulungagung:</span>
                                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar">
                                    <button type="button" onclick="selectPresetArea('Plosokandang, Kedungwaru, Tulungagung', -8.0748, 111.9225)"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[10px] font-semibold text-[#5c5751] hover:text-[#2c2825] whitespace-nowrap transition-all">
                                        Plosokandang (~0.8 km)
                                    </button>
                                    <button type="button" onclick="selectPresetArea('Kedungwaru, Tulungagung', -8.0675, 111.9082)"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[10px] font-semibold text-[#5c5751] hover:text-[#2c2825] whitespace-nowrap transition-all">
                                        Kedungwaru (~2.1 km)
                                    </button>
                                    <button type="button" onclick="selectPresetArea('Aloon-Aloon Tulungagung, Pusat Kota', -8.0657, 111.9025)"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[10px] font-semibold text-[#5c5751] hover:text-[#2c2825] whitespace-nowrap transition-all">
                                        Aloon-Aloon (~3.3 km)
                                    </button>
                                    <button type="button" onclick="selectPresetArea('Boyolangu, Tulungagung', -8.0995, 111.8965)"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[10px] font-semibold text-[#5c5751] hover:text-[#2c2825] whitespace-nowrap transition-all">
                                        Boyolangu (~4.5 km)
                                    </button>
                                    <button type="button" onclick="selectPresetArea('Kauman, Tulungagung', -8.0590, 111.8730)"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[10px] font-semibold text-[#5c5751] hover:text-[#2c2825] whitespace-nowrap transition-all">
                                        Kauman (~5.6 km)
                                    </button>
                                    <button type="button" onclick="selectPresetArea('Sumbergempol, Tulungagung', -8.0970, 111.9585)"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[10px] font-semibold text-[#5c5751] hover:text-[#2c2825] whitespace-nowrap transition-all">
                                        Sumbergempol (~6.4 km)
                                    </button>
                                    <button type="button" onclick="selectPresetArea('Ngunut, Tulungagung', -8.1225, 112.0120)"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-[#faf8f5] border border-[#ded8ce] hover:border-[#2c2825] text-[10px] font-semibold text-[#5c5751] hover:text-[#2c2825] whitespace-nowrap transition-all">
                                        Ngunut (~12 km)
                                    </button>
                                </div>
                            </div>

                            <!-- Leaflet Interactive Map Container -->
                            <div class="relative rounded-xl overflow-hidden border border-[#ded8ce]">
                                <div id="modalZendoMap" class="w-full h-48 sm:h-52 bg-[#faf8f5]"></div>
                                <div class="bg-white px-3 py-1.5 text-[10px] text-[#78726b] flex items-center justify-between border-t border-[#ece7de]">
                                    <span><i class="fa-solid fa-hand-pointer text-[#2c2825] mr-1"></i> Geser pin hijau atau klik peta ke rumah Anda</span>
                                    <span id="modalCoordBadge" class="font-mono text-[#78726b]">-8.075, 111.922</span>
                                </div>
                            </div>

                            <!-- Live Distance & Tariff Badge -->
                            <div class="bg-[#faf8f5] border border-[#ded8ce] rounded-xl p-3 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] uppercase tracking-wider font-bold text-[#78726b] block">Jarak Kafe ke Lokasi</span>
                                        <span id="modalDistanceBadgeText" class="text-sm font-black text-[#2c2825] font-mono">1,5 km</span>
                                    </div>
                                    <div class="text-right">
                                        <span id="modalTariffBracketLabel" class="text-[10px] uppercase tracking-wider font-bold text-[#78726b] block">Tarif Zendo (0–3 km)</span>
                                        <span id="modalTariffFeeBadgeText" class="text-base font-black text-[#2c2825] font-mono">Rp {{ number_format($zendoTariff['tier_0_3'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="pt-1 border-t border-[#ece7de]">
                                    <button type="button" 
                                            onclick="document.getElementById('modalTariffTableInfo').classList.toggle('hidden')"
                                            class="w-full flex items-center justify-between text-[10px] font-bold text-[#5c5751] hover:text-[#2c2825] transition-colors cursor-pointer">
                                        <span><i class="fa-solid fa-circle-info mr-1"></i> Rincian Tarif Kurir Zendo</span>
                                        <i class="fa-solid fa-chevron-down text-[9px]"></i>
                                    </button>
                                    <div id="modalTariffTableInfo" class="hidden mt-1.5 pt-1.5 border-t border-[#ece7de] text-[10px] space-y-0.5 font-mono text-[#5c5751]">
                                        <div class="flex justify-between"><span>0 – 3 km</span><span class="text-[#2c2825] font-bold">Rp {{ number_format($zendoTariff['tier_0_3'], 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>3,1 – 4 km</span><span class="text-[#2c2825] font-bold">Rp {{ number_format($zendoTariff['tier_3_4'], 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>4,1 – 5 km</span><span class="text-[#2c2825] font-bold">Rp {{ number_format($zendoTariff['tier_4_5'], 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>5,1 – 6 km</span><span class="text-[#2c2825] font-bold">Rp {{ number_format($zendoTariff['tier_5_6'], 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>6,1 – 7 km</span><span class="text-[#2c2825] font-bold">Rp {{ number_format($zendoTariff['tier_6_7'], 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Di atas 7 km</span><span class="text-[#2c2825] font-bold">+Rp {{ number_format($zendoTariff['extra_per_km'], 0, ',', '.') }} / km</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Alamat Pengiriman -->
                            <div>
                                <label for="modalDeliveryAddress" class="block text-[11px] font-semibold text-[#2c2825] mb-1">
                                    <i class="fa-solid fa-location-dot mr-1"></i> Detail Alamat Pengiriman & Patokan <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="modalDeliveryAddress" 
                                          rows="2" 
                                          placeholder="Alamat otomatis terisi dari titik peta, tambahkan nomor rumah / warna pagar / RT RW..."
                                          class="w-full bg-white border border-[#ded8ce] rounded-xl p-2.5 text-xs text-[#2c2825] placeholder-[#9c968f] focus:outline-none focus:border-[#2c2825] transition-colors resize-none"></textarea>
                            </div>
                        </div>
                        @else
                        <!-- Mode Klien (Tanpa Peta & Tracking): Input Alamat Ringkas & Langsung WA -->
                        <div class="bg-white border border-[#ded8ce] rounded-2xl p-3.5 space-y-3">
                            <div>
                                <label for="modalDeliveryAddress" class="block text-[11px] font-semibold text-[#2c2825] mb-1">
                                    <i class="fa-solid fa-location-dot mr-1 text-[#2c2825]"></i> Alamat Lengkap Pengiriman <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="modalDeliveryAddress" 
                                          rows="3" 
                                          placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, desa/kelurahan, atau patokan rumah Anda..."
                                          class="w-full bg-[#faf8f5] border border-[#ded8ce] rounded-xl p-3 text-xs text-[#2c2825] placeholder-[#9c968f] focus:outline-none focus:border-[#2c2825] transition-colors resize-none"></textarea>
                            </div>
                            <div class="flex items-center gap-2 p-2.5 bg-[#faf8f5] rounded-xl border border-[#ded8ce] text-[11px] text-[#5c5751]">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-base shrink-0"></i>
                                <span>Pesanan langsung diarahkan ke WhatsApp kasir/kurir dalam format <strong>struk ringkas</strong>.</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- 4. CATATAN PESANAN -->
                    <div class="pt-2 border-t border-[#ece7de]">
                        <label for="modalOrderNotes" class="block text-[11px] font-semibold text-[#2c2825] mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-note-sticky text-[#2c2825]"></i>
                            <span>Catatan Pesanan <span class="text-[#78726b] font-normal">(Opsional)</span></span>
                        </label>
                        <input type="text" 
                               id="modalOrderNotes" 
                               placeholder="Contoh: less ice, gula dipisah, sambal banyak..."
                               class="w-full bg-white border border-[#ded8ce] rounded-xl px-3.5 py-2 text-xs text-[#2c2825] placeholder-[#9c968f] focus:outline-none focus:border-[#2c2825] transition-colors">
                    </div>

                    <!-- Error Alert Banner -->
                    <div id="modalCartError" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-300 text-rose-700 text-xs"></div>
                </div>

            </div>

            <!-- Footer: Checkout Action Button (Warm Monochrome Pill) -->
            <div class="p-4 sm:p-5 border-t border-[#ece7de] bg-[#faf8f5] space-y-2">
                <button type="button" 
                        id="modalSubmitBtn"
                        onclick="submitCartOrder()" 
                        class="w-full py-3.5 sm:py-4 rounded-2xl bg-[#2c2825] hover:bg-[#3a3632] text-[#faf8f5] font-extrabold text-xs sm:text-sm uppercase tracking-wider active:scale-98 transition-all shadow-md disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer">
                    <span id="modalSubmitText">
                        <i class="fa-solid fa-qrcode mr-1"></i> Buat Slip Pesanan (Kasir)
                    </span>
                    <span id="modalSubmitSpinner" class="hidden animate-spin material-symbols-outlined text-base">progress_activity</span>
                </button>
            </div>
        </div>

        <!-- =========================================================================
             C. VIEW 2: SUCCESS VIEW (Kasir QR Slip OR Zendo WhatsApp confirmation)
             ========================================================================= -->
        <div id="cartModalSuccessView" class="hidden relative z-10 flex-1 flex-col overflow-y-auto cart-custom-scroll p-6 sm:p-8 text-center space-y-5 bg-white">
            <!-- Icon -->
            <div id="successIconWrapper" class="w-16 h-16 rounded-full mx-auto flex items-center justify-center shadow-md bg-[#faf8f5] border-2 border-[#2c2825] text-[#2c2825]">
                <i id="successIcon" class="fa-solid fa-qrcode text-3xl"></i>
            </div>

            <div>
                <span id="successBadge" class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-[#faf8f5] border border-[#ded8ce] text-[#2c2825] text-[10px] font-bold uppercase tracking-wider mb-2">
                    Slip Pesanan Kasir Siap
                </span>
                <h4 id="successTitle" class="text-xl sm:text-2xl font-black text-[#2c2825] font-serif">Pesanan Anda Diterima</h4>
                <p id="successSubtext" class="text-xs text-[#78726b] max-w-sm mx-auto mt-1 leading-relaxed">
                    Tunjukkan kode QR berikut ke kasir untuk proses pencetakan dan pembayaran.
                </p>
            </div>

            <!-- QR Code Plate (Only for Kasir) -->
            <div id="modalQrPlate" class="bg-white p-4 rounded-3xl border border-[#ded8ce] shadow-md mx-auto w-fit flex flex-col items-center justify-center">
                <div id="modalQrcodeTarget"></div>
                <img id="modalQrFallback" class="hidden w-40 h-40 object-contain rounded" alt="QR Code">
                <span class="text-[10px] text-[#2c2825] font-extrabold uppercase tracking-wider mt-2.5 block">
                    SCAN DI KASIR
                </span>
            </div>

            <!-- Summary Box -->
            <div class="bg-[#faf8f5] border border-[#ded8ce] rounded-2xl p-4 text-xs text-left space-y-2 max-w-sm mx-auto w-full">
                <div class="flex justify-between text-[#78726b]">
                    <span>Kode Pesanan:</span>
                    <span id="modalSuccessOrderCode" class="font-mono font-bold text-[#2c2825]">NCM-ORDER</span>
                </div>
                <div class="flex justify-between text-[#78726b]">
                    <span>Nama Pemesan:</span>
                    <span id="modalSuccessCustomerName" class="font-bold text-[#2c2825]">-</span>
                </div>
                <div class="flex justify-between text-[#78726b]" id="modalSuccessTypeRow">
                    <span>Tipe Layanan:</span>
                    <span id="modalSuccessType" class="font-bold text-[#2c2825]">-</span>
                </div>
                <div class="flex justify-between text-[#2c2825] pt-2 border-t border-[#ece7de] font-bold">
                    <span>Total Pembayaran:</span>
                    <span id="modalSuccessTotal" class="font-mono text-sm text-[#2c2825] font-extrabold">Rp 0</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5 max-w-sm mx-auto w-full pt-2">
                <a id="modalSuccessSlipLink" 
                   href="#" 
                   target="_blank" 
                   class="w-full py-3.5 px-4 rounded-xl bg-[#2c2825] hover:bg-[#3a3632] text-white font-bold text-xs flex items-center justify-center gap-2 transition-all">
                    <i class="fa-solid fa-receipt text-amber-200"></i>
                    <span>Buka Slip Lengkap & Cetak Struk</span>
                </a>
                <a id="modalSuccessWaBtn" 
                   href="#" 
                   class="hidden w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-md transition-all">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Buka WhatsApp Sekarang</span>
                </a>
                <a id="modalSuccessTrackingBtn" 
                   href="#" 
                   target="_blank" 
                   class="hidden w-full py-3.5 px-4 rounded-xl bg-[#2c2825] hover:bg-[#3a3632] text-white font-extrabold text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-md transition-all">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Lacak Posisi Kurir di Peta</span>
                </a>
                <button type="button" 
                        onclick="resetModalToCart()" 
                        class="w-full py-3 px-4 rounded-xl bg-white hover:bg-[#faf8f5] text-[#2c2825] border border-[#ded8ce] font-bold text-xs transition-all cursor-pointer">
                    Selesai / Pesan Menu Lain
                </button>
            </div>
        </div>

    </div>
</div>

<!-- =========================================================================
     3. FLOATING ADDED TOAST NOTIFICATION (WARM MONOCHROME)
     ========================================================================= -->
<div id="nucomuAddedToast" 
     class="fixed top-24 right-4 z-50 bg-white/95 border border-[#ded8ce] shadow-xl rounded-2xl p-3.5 px-4 flex items-center gap-3 transition-all duration-300 opacity-0 translate-y-4 pointer-events-none backdrop-blur-md">
    <div class="w-8 h-8 rounded-full bg-[#faf8f5] border border-[#ded8ce] text-[#2c2825] flex items-center justify-center shrink-0">
        <i class="fa-solid fa-check text-xs"></i>
    </div>
    <div class="min-w-0 pr-2">
        <p class="text-xs font-bold text-[#2c2825] truncate"><span id="nucomuToastItemName">Menu</span> ditambahkan!</p>
        <p class="text-[10px] text-[#78726b]">Tersimpan di keranjang pesanan.</p>
    </div>
    <button type="button" 
            onclick="openCartModal()" 
            class="px-2.5 py-1 rounded-lg bg-[#2c2825] text-white font-extrabold text-[10px] uppercase tracking-wider hover:bg-[#3a3632] transition-all shrink-0 cursor-pointer">
        Lihat
    </button>
</div>

<!-- =========================================================================
     4. JAVASCRIPT CART & CHECKOUT ENGINE
     ========================================================================= -->
<script>
    // Configuration from PHP
    const CART_ADMIN_MODE = '{{ $transactionMode }}';
    const CART_CAFE_LAT = {{ $cafeLat }};
    const CART_CAFE_LNG = {{ $cafeLng }};
    const CART_ZENDO_TARIFF = @json($zendoTariff);
    const CART_ZENDO_COURIERS = @json($zendoCouriers);
    const CART_CSRF_TOKEN = '{{ csrf_token() }}';
    const CART_HIDE_LIVE_TRACKING = {{ $hideLiveTracking ? 'true' : 'false' }};

    // Global in-memory cart persisted in localStorage
    window.nucomuCart = JSON.parse(localStorage.getItem('nucomu_cart') || '{}');

    let modalCurrentMethod = '{{ $initialProcessMethod }}';
    let modalDeliveryFee = parseInt(CART_ZENDO_TARIFF.tier_0_3 || 10000, 10);
    let modalDistanceKm = 1.5;
    let modalSelectedLat = CART_CAFE_LAT;
    let modalSelectedLng = CART_CAFE_LNG;
    let modalSelectedAddress = '';

    let modalMap = null;
    let modalMarker = null;
    let modalCafeMarker = null;
    let modalRouteLine = null;

    // ============================================
    // MODAL OPEN / CLOSE CONTROLS
    // ============================================
    window.openCartModal = function() {
        const backdrop = document.getElementById('cartModalBackdrop');
        const card = document.getElementById('cartModalCard');
        if (!backdrop || !card) return;

        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.style.overflow = 'hidden';

        renderModalCart();

        // Invalidate map size if Zendo is currently active
        if (modalCurrentMethod === 'zendo') {
            setTimeout(() => {
                initModalZendoMap();
                if (modalMap) modalMap.invalidateSize();
            }, 250);
        }
    };

    window.closeCartModal = function() {
        const backdrop = document.getElementById('cartModalBackdrop');
        const card = document.getElementById('cartModalCard');
        if (!backdrop || !card) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        document.body.style.overflow = '';
    };

    window.handleBackdropClick = function(e) {
        if (e.target && e.target.id === 'cartModalBackdrop') {
            closeCartModal();
        }
    };

    window.toggleCartDrawer = window.openCartModal;

    // ESC key listener to close modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCartModal();
        }
    });

    // ============================================
    // TRANSACTION METHOD TOGGLE (KASIR VS ZENDO)
    // ============================================
    window.setModalProcessMethod = function(method) {
        if (CART_ADMIN_MODE === 'kasir') {
            modalCurrentMethod = 'kasir';
        } else if (CART_ADMIN_MODE === 'zendo') {
            modalCurrentMethod = 'zendo';
        } else {
            modalCurrentMethod = (method === 'zendo') ? 'zendo' : 'kasir';
        }

        const isZendo = modalCurrentMethod === 'zendo';
        const btnKasir = document.getElementById('modalMethodBtnKasir');
        const btnZendo = document.getElementById('modalMethodBtnZendo');
        const iconKasir = document.getElementById('modalIconKasir');
        const iconZendo = document.getElementById('modalIconZendo');
        const dotKasir = document.getElementById('modalDotKasir');
        const dotZendo = document.getElementById('modalDotZendo');
        const kasirFields = document.getElementById('modalKasirFields');
        const zendoFields = document.getElementById('modalZendoFields');
        const zendoFeeRow = document.getElementById('modalZendoFeeRow');
        const paymentHint = document.getElementById('modalCartPaymentHint');

        if (btnKasir && btnZendo) {
            btnKasir.className = 'p-3 sm:p-3.5 rounded-xl border text-left transition-all flex flex-col justify-between cursor-pointer ' +
                (!isZendo ? 'border-[#2c2825] bg-white text-[#2c2825] shadow-xs' : 'border-[#ded8ce] bg-[#faf8f5] text-[#78726b] hover:border-[#9c968f]');
            btnZendo.className = 'p-3 sm:p-3.5 rounded-xl border text-left transition-all flex flex-col justify-between cursor-pointer ' +
                (isZendo ? 'border-[#2c2825] bg-white text-[#2c2825] shadow-xs' : 'border-[#ded8ce] bg-[#faf8f5] text-[#78726b] hover:border-[#9c968f]');
        }
        if (iconKasir) iconKasir.className = 'fa-solid fa-qrcode text-base ' + (!isZendo ? 'text-[#2c2825]' : 'text-[#78726b]');
        if (iconZendo) iconZendo.className = 'fa-solid fa-motorcycle text-base ' + (isZendo ? 'text-[#2c2825]' : 'text-[#78726b]');
        if (dotKasir) dotKasir.className = 'w-2.5 h-2.5 rounded-full ' + (!isZendo ? 'bg-[#2c2825]' : 'bg-[#ded8ce]');
        if (dotZendo) dotZendo.className = 'w-2.5 h-2.5 rounded-full ' + (isZendo ? 'bg-[#2c2825]' : 'bg-[#ded8ce]');

        if (kasirFields) kasirFields.classList.toggle('hidden', isZendo);
        if (zendoFields) zendoFields.classList.toggle('hidden', !isZendo);
        if (zendoFeeRow) zendoFeeRow.classList.toggle('hidden', !isZendo || CART_HIDE_LIVE_TRACKING);

        if (paymentHint) {
            paymentHint.innerText = isZendo
                ? (CART_HIDE_LIVE_TRACKING
                    ? '*Pesanan langsung dikirimkan ke WhatsApp format struk ringkas.'
                    : '*Pesanan & titik lokasi langsung dikirim otomatis ke Kurir Zendo.')
                : '*Bayar di kasir setelah menunjukkan slip QR.';
        }

        resetModalSubmitButton();
        renderModalCart();

        if (isZendo) {
            setTimeout(() => {
                initModalZendoMap();
                if (modalMap) modalMap.invalidateSize();
            }, 180);
        }
    };

    window.toggleModalTableInput = function(isDineIn) {
        const wrap = document.getElementById('modalTableWrap');
        if (wrap) {
            wrap.classList.toggle('hidden', !isDineIn);
        }
    };

    function resetModalSubmitButton() {
        const btn = document.getElementById('modalSubmitBtn');
        const text = document.getElementById('modalSubmitText');
        const spinner = document.getElementById('modalSubmitSpinner');
        if (!btn || !text) return;

        btn.disabled = false;
        if (spinner) spinner.classList.add('hidden');

        if (modalCurrentMethod === 'zendo') {
            btn.className = 'w-full py-3.5 sm:py-4 rounded-2xl bg-[#2c2825] hover:bg-[#3a3632] text-[#faf8f5] font-extrabold text-xs sm:text-sm uppercase tracking-wider active:scale-98 transition-all shadow-md disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer';
            text.innerHTML = '<i class="fa-solid fa-motorcycle mr-1"></i> Kirim Pesanan ke Kurir Zendo';
        } else {
            btn.className = 'w-full py-3.5 sm:py-4 rounded-2xl bg-[#2c2825] hover:bg-[#3a3632] text-[#faf8f5] font-extrabold text-xs sm:text-sm uppercase tracking-wider active:scale-98 transition-all shadow-md disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer';
            text.innerHTML = '<i class="fa-solid fa-qrcode mr-1"></i> Buat Slip Pesanan (Kasir)';
        }
    }

    // ============================================
    // CART ADD & UPDATE ENGINE ("DI TIMBUN DI KERANJANG")
    // ============================================
    window.addToCart = function(id, name, price, options, image) {
        const optionsStr = Array.isArray(options) ? options.filter(Boolean).join(', ') : (options || '');
        const key = `${id}_${optionsStr.replace(/\s+/g, '_')}`;

        if (window.nucomuCart[key]) {
            window.nucomuCart[key].qty += 1;
        } else {
            window.nucomuCart[key] = {
                id: id,
                name: name,
                price: parseInt(price, 10) || 0,
                qty: 1,
                optionsStr: optionsStr,
                image: image || ''
            };
        }

        // Persist to localStorage
        try {
            localStorage.setItem('nucomu_cart', JSON.stringify(window.nucomuCart));
        } catch (e) {}

        renderModalCart();
        showNucomuAddedToast(name);

        // Bump animation on bottom-right floating cart capsule
        const badge = document.getElementById('floating-cart-badge');
        if (badge) {
            badge.classList.remove('scale-125');
            void badge.offsetWidth;
            badge.classList.add('scale-125');
            setTimeout(() => badge.classList.remove('scale-125'), 300);
        }
    };

    window.updateModalQty = function(key, delta) {
        if (!window.nucomuCart[key]) return;
        window.nucomuCart[key].qty += delta;
        if (window.nucomuCart[key].qty <= 0) {
            delete window.nucomuCart[key];
        }
        try {
            localStorage.setItem('nucomu_cart', JSON.stringify(window.nucomuCart));
        } catch (e) {}
        renderModalCart();
    };

    window.removeModalCartItem = function(key) {
        if (window.nucomuCart[key]) {
            delete window.nucomuCart[key];
            try {
                localStorage.setItem('nucomu_cart', JSON.stringify(window.nucomuCart));
            } catch (e) {}
            renderModalCart();
        }
    };

    // ============================================
    // MENU EXPANSION & SUBMISSION HELPERS
    // ============================================
    window.togglePopularDishOptions = function(cardEl) {
        if (!cardEl) return;
        const wasActive = cardEl.classList.contains('active');
        document.querySelectorAll('.popular-dish-card.active').forEach(c => {
            if (c !== cardEl) c.classList.remove('active');
        });
        cardEl.classList.toggle('active', !wasActive);
    };

    window.submitPopularDishOrder = function(cardEl, name, priceFormatted) {
        if (!cardEl) return;
        const itemId = cardEl.dataset.itemId || cardEl.dataset.id || name.toLowerCase().replace(/[^a-z0-9]/g, '-');
        
        // Extract numeric price
        let numericPrice = 0;
        if (cardEl.dataset.price) {
            numericPrice = parseInt(cardEl.dataset.price, 10);
        } else if (priceFormatted) {
            numericPrice = parseInt(String(priceFormatted).replace(/[^0-9]/g, ''), 10) || 0;
        }

        // Collect options from checked radio buttons
        const checkedRadios = cardEl.querySelectorAll('input[type="radio"]:checked');
        const options = [];
        checkedRadios.forEach(r => {
            if (r.value) options.push(r.value);
        });

        const imgEl = cardEl.querySelector('img');
        const imgSrc = imgEl ? imgEl.src : '';

        // Add item to cart without auto-opening modal ("di timbun di keranjang")
        window.addToCart(itemId, name, numericPrice, options, imgSrc);

        // Feedback visual pada tombol
        const btn = cardEl.querySelector('button[onclick*="submitPopularDishOrder"]');
        if (btn) {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<span class="material-symbols-outlined text-sm">check</span><span>Masuk Keranjang!</span>`;
            btn.classList.add('bg-emerald-700');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('bg-emerald-700');
            }, 1200);
        }
    };

    window.orderCurrentBestMenuItem = function() {
        const items = window.nucomuBestMenuItems || [];
        const idx = typeof window.currentBestMenuIndex === 'number' ? window.currentBestMenuIndex : 0;
        const item = items[idx] || items[0];

        if (item) {
            const numPrice = item.price || parseInt(String(item.price_formatted || '').replace(/[^0-9]/g, ''), 10) || 0;
            window.addToCart(item.id || item.slug || 'best-menu-' + idx, item.name, numPrice, null, item.image);
        }
    };

    // ============================================
    // RENDER CART ITEMS & CALCULATE TOTALS
    // ============================================
    window.renderModalCart = function() {
        const itemsContainer = document.getElementById('modalCartItems');
        const emptyState = document.getElementById('modalEmptyCart');
        const totalBreakdown = document.getElementById('modalCartTotalBreakdown');
        const headerBadge = document.getElementById('modalHeaderCountBadge');
        const countBadge = document.getElementById('modalCartCountBadge');
        const subtotalEl = document.getElementById('modalSubtotalAmount');
        const totalEl = document.getElementById('modalTotalAmount');
        const floatingBadge = document.getElementById('floating-cart-badge');
        const floatingTotal = document.getElementById('floating-cart-total');
        const navBadge = document.getElementById('nav-cart-badge');

        const keys = Object.keys(window.nucomuCart);
        let subtotal = 0;
        let totalQty = 0;

        if (keys.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (totalBreakdown) totalBreakdown.classList.add('hidden');
            if (itemsContainer) itemsContainer.innerHTML = '';
            if (headerBadge) headerBadge.innerText = '0 Item';
            if (countBadge) countBadge.innerText = '0 Item';
            if (floatingBadge) floatingBadge.innerText = '0';
            if (floatingTotal) floatingTotal.innerText = 'Rp 0';
            if (navBadge) {
                navBadge.innerText = '0';
                navBadge.classList.add('hidden');
            }
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        if (totalBreakdown) totalBreakdown.classList.remove('hidden');

        let html = '';
        keys.forEach(key => {
            const item = window.nucomuCart[key];
            const lineTotal = item.price * item.qty;
            subtotal += lineTotal;
            totalQty += item.qty;

            html += `
            <div class="flex items-center justify-between py-2.5 border-b border-[#ece7de] last:border-0 gap-2">
                <div class="flex-1 min-w-0 pr-1">
                    <p class="text-xs font-bold text-[#2c2825] truncate">${item.name}</p>
                    ${item.optionsStr ? `<p class="text-[10px] text-[#78726b] font-medium truncate">${item.optionsStr}</p>` : ''}
                    <p class="text-[10px] text-[#5c5751] font-mono mt-0.5">
                        Rp ${item.price.toLocaleString('id-ID')} × ${item.qty} = <span class="text-[#2c2825] font-bold">Rp ${lineTotal.toLocaleString('id-ID')}</span>
                    </p>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" onclick="updateModalQty('${key}', -1)" class="w-6 h-6 rounded-full bg-[#faf8f5] border border-[#ded8ce] hover:bg-[#ece7de] text-[#2c2825] text-xs flex items-center justify-center font-bold transition-colors cursor-pointer" aria-label="Kurangi jumlah">−</button>
                    <span class="text-xs font-bold text-[#2c2825] w-5 text-center font-mono">${item.qty}</span>
                    <button type="button" onclick="updateModalQty('${key}', 1)" class="w-6 h-6 rounded-full bg-[#2c2825] hover:bg-[#3a3632] text-white text-xs flex items-center justify-center font-bold transition-colors cursor-pointer" aria-label="Tambah jumlah">+</button>
                    <button type="button" onclick="removeModalCartItem('${key}')" class="w-6 h-6 rounded-full text-[#9c968f] hover:text-rose-600 flex items-center justify-center ml-1 text-xs transition-colors cursor-pointer" title="Hapus menu" aria-label="Hapus menu">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>`;
        });

        if (itemsContainer) itemsContainer.innerHTML = html;

        const isZendo = modalCurrentMethod === 'zendo';
        const activeDeliveryFee = (isZendo && !CART_HIDE_LIVE_TRACKING) ? modalDeliveryFee : 0;
        const grandTotal = subtotal + activeDeliveryFee;

        if (subtotalEl) subtotalEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        if (totalEl) totalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        if (headerBadge) headerBadge.innerText = `${totalQty} Item`;
        if (countBadge) countBadge.innerText = `${totalQty} Item`;
        if (floatingBadge) floatingBadge.innerText = totalQty;
        if (floatingTotal) floatingTotal.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

        if (navBadge) {
            navBadge.innerText = totalQty;
            navBadge.classList.remove('hidden');
        }
    };

    function showNucomuAddedToast(itemName) {
        const toast = document.getElementById('nucomuAddedToast');
        const text = document.getElementById('nucomuToastItemName');
        if (toast && text) {
            text.innerText = itemName;
            toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            clearTimeout(window.nucomuToastTimer);
            window.nucomuToastTimer = setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            }, 2500);
        }
    }

    // ============================================
    // LEAFLET MAP & DISTANCE ENGINE
    // ============================================
    function calculateZendoFee(distanceKm) {
        const d = Math.max(0, Math.round(distanceKm * 10) / 10);
        const t03 = parseInt(CART_ZENDO_TARIFF.tier_0_3 || 10000, 10);
        const t34 = parseInt(CART_ZENDO_TARIFF.tier_3_4 || 12000, 10);
        const t45 = parseInt(CART_ZENDO_TARIFF.tier_4_5 || 15000, 10);
        const t56 = parseInt(CART_ZENDO_TARIFF.tier_5_6 || 17000, 10);
        const t67 = parseInt(CART_ZENDO_TARIFF.tier_6_7 || 18000, 10);
        const extraPerKm = parseInt(CART_ZENDO_TARIFF.extra_per_km || 2500, 10);

        if (d <= 3.0) return t03;
        if (d <= 4.0) return t34;
        if (d <= 5.0) return t45;
        if (d <= 6.0) return t56;
        if (d <= 7.0) return t67;

        const extraKm = Math.ceil(d - 7.0);
        return t67 + (extraKm * extraPerKm);
    }

    function computeHaversineDistanceKm(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return Math.round(R * c * 10) / 10;
    }

    function initModalZendoMap() {
        if (typeof L === 'undefined') return;
        const mapContainer = document.getElementById('modalZendoMap');
        if (!mapContainer) return;

        if (!modalMap) {
            modalMap = L.map('modalZendoMap', {
                center: [modalSelectedLat, modalSelectedLng],
                zoom: 14,
                zoomControl: true,
                attributionControl: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(modalMap);

            // Cafe Pin
            const cafeIcon = L.divIcon({
                className: 'custom-cafe-pin',
                html: '<div style="background:#2c2825;color:#faf8f5;border:2px solid #fff;border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(0,0,0,0.3);font-size:12px;"><i class="fa-solid fa-mug-hot"></i></div>',
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });
            modalCafeMarker = L.marker([CART_CAFE_LAT, CART_CAFE_LNG], { icon: cafeIcon }).addTo(modalMap)
                .bindPopup('<strong style="color:#2c2825;">Nucomu Cafe</strong><br><span style="font-size:11px;color:#78726b;">Plosokandang, Tulungagung</span>');

            // Destination Pin (Draggable)
            const destIcon = L.divIcon({
                className: 'custom-dest-pin',
                html: '<div style="background:#2e7d32;color:#fff;border:2px solid #fff;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(46,125,50,0.5);font-size:13px;"><i class="fa-solid fa-house"></i></div>',
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });
            modalMarker = L.marker([modalSelectedLat, modalSelectedLng], { icon: destIcon, draggable: true }).addTo(modalMap);

            modalMarker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                updateModalLocation(pos.lat, pos.lng, true);
            });

            modalMap.on('click', function(e) {
                modalMarker.setLatLng(e.latlng);
                updateModalLocation(e.latlng.lat, e.latlng.lng, true);
            });

            // Initial route line
            modalRouteLine = L.polyline([[CART_CAFE_LAT, CART_CAFE_LNG], [modalSelectedLat, modalSelectedLng]], {
                color: '#2c2825',
                weight: 3,
                dashArray: '6, 6',
                opacity: 0.8
            }).addTo(modalMap);
        }

        updateModalLocation(modalSelectedLat, modalSelectedLng, false);
    }

    function updateModalLocation(lat, lng, doReverseGeocode = true) {
        modalSelectedLat = lat;
        modalSelectedLng = lng;

        if (modalMarker) modalMarker.setLatLng([lat, lng]);
        if (modalRouteLine) modalRouteLine.setLatLngs([[CART_CAFE_LAT, CART_CAFE_LNG], [lat, lng]]);

        // Calculate distance & fee
        const rawDist = computeHaversineDistanceKm(CART_CAFE_LAT, CART_CAFE_LNG, lat, lng);
        modalDistanceKm = Math.max(0.5, rawDist);
        modalDeliveryFee = calculateZendoFee(modalDistanceKm);

        // Update badges
        const distBadge = document.getElementById('modalDistanceBadgeText');
        const cartDistText = document.getElementById('modalCartDistanceText');
        const feeBadge = document.getElementById('modalTariffFeeBadgeText');
        const deliveryFeeAmount = document.getElementById('modalDeliveryFeeAmount');
        const coordBadge = document.getElementById('modalCoordBadge');
        const bracketLabel = document.getElementById('modalTariffBracketLabel');

        const distStr = modalDistanceKm.toFixed(1).replace('.', ',') + ' km';
        if (distBadge) distBadge.innerText = distStr;
        if (cartDistText) cartDistText.innerText = distStr;
        if (feeBadge) feeBadge.innerText = 'Rp ' + modalDeliveryFee.toLocaleString('id-ID');
        if (deliveryFeeAmount) deliveryFeeAmount.innerText = 'Rp ' + modalDeliveryFee.toLocaleString('id-ID');
        if (coordBadge) coordBadge.innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;

        if (bracketLabel) {
            if (modalDistanceKm <= 3) bracketLabel.innerText = 'Tarif Zendo (0–3 km)';
            else if (modalDistanceKm <= 4) bracketLabel.innerText = 'Tarif Zendo (3,1–4 km)';
            else if (modalDistanceKm <= 5) bracketLabel.innerText = 'Tarif Zendo (4,1–5 km)';
            else if (modalDistanceKm <= 6) bracketLabel.innerText = 'Tarif Zendo (5,1–6 km)';
            else if (modalDistanceKm <= 7) bracketLabel.innerText = 'Tarif Zendo (6,1–7 km)';
            else bracketLabel.innerText = 'Tarif Zendo (>7 km)';
        }

        renderModalCart();

        if (doReverseGeocode) {
            reverseGeocodeLocation(lat, lng);
        }
    }

    function reverseGeocodeLocation(lat, lng) {
        const addrInput = document.getElementById('modalDeliveryAddress');
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name && addrInput) {
                    addrInput.value = data.display_name;
                    modalSelectedAddress = data.display_name;
                }
            })
            .catch(() => {});
    }

    window.useMyGpsLocation = function() {
        if (!navigator.geolocation) {
            alert('Perangkat Anda tidak mendukung fitur GPS.');
            return;
        }

        const btn = document.getElementById('modalGpsBtn');
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Mencari...</span>';

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                updateModalLocation(lat, lng, true);
                if (modalMap) {
                    modalMap.setView([lat, lng], 16);
                }
                if (btn) btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i><span>GPS Saya</span>';
            },
            () => {
                alert('Tidak dapat mendeteksi lokasi GPS Anda. Silakan geser pin di peta.');
                if (btn) btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i><span>GPS Saya</span>';
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    };

    window.searchModalMapLocation = function() {
        const input = document.getElementById('modalMapSearchInput');
        const query = (input ? input.value : '').trim();
        if (!query) return;

        const resultsContainer = document.getElementById('modalMapSearchResults');
        const searchBtn = document.getElementById('modalMapSearchBtn');
        if (searchBtn) searchBtn.innerText = '...';

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Tulungagung, Jawa Timur')}`)
            .then(res => res.json())
            .then(items => {
                if (resultsContainer) {
                    if (items && items.length > 0) {
                        resultsContainer.innerHTML = items.map(item => `
                            <button type="button" 
                                    onclick="selectSearchResult(${item.lat}, ${item.lon}, '${item.display_name.replace(/'/g, "\\'")}')" 
                                    class="w-full text-left p-2.5 hover:bg-[#faf8f5] text-xs text-[#2c2825] flex flex-col cursor-pointer">
                                <span class="font-bold truncate">${item.display_name.split(',')[0]}</span>
                                <span class="text-[10px] text-[#78726b] truncate">${item.display_name}</span>
                            </button>
                        `).join('');
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-xs text-[#78726b] text-center">Lokasi tidak ditemukan di Tulungagung.</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                }
            })
            .catch(() => {})
            .finally(() => {
                if (searchBtn) searchBtn.innerText = 'Cari';
            });
    };

    window.selectSearchResult = function(lat, lng, label) {
        updateModalLocation(parseFloat(lat), parseFloat(lng), false);
        if (modalMap) modalMap.setView([lat, lng], 16);
        const addrInput = document.getElementById('modalDeliveryAddress');
        if (addrInput) addrInput.value = label;
        const resultsContainer = document.getElementById('modalMapSearchResults');
        if (resultsContainer) resultsContainer.classList.add('hidden');
    };

    window.selectPresetArea = function(label, lat, lng) {
        updateModalLocation(lat, lng, false);
        if (modalMap) modalMap.setView([lat, lng], 15);
        const addrInput = document.getElementById('modalDeliveryAddress');
        if (addrInput) addrInput.value = label;
    };

    // ============================================
    // SUBMIT ORDER (KASIR VS ZENDO)
    // ============================================
    window.submitCartOrder = function() {
        const keys = Object.keys(window.nucomuCart);
        const errorBanner = document.getElementById('modalCartError');

        if (keys.length === 0) {
            showCartError('Keranjang belanja kosong! Pilih menu favorit Anda terlebih dahulu.');
            return;
        }

        const nameInput = document.getElementById('modalCustomerName');
        const customerName = (nameInput ? nameInput.value : '').trim();
        if (!customerName) {
            showCartError('Harap isi Nama Pemesan.');
            nameInput?.focus();
            return;
        }

        const isZendo = modalCurrentMethod === 'zendo';
        let customerPhone = '';
        let deliveryAddress = '';
        let tableNo = '';

        if (isZendo) {
            const phoneInput = document.getElementById('modalCustomerPhone');
            const addrInput = document.getElementById('modalDeliveryAddress');
            customerPhone = (phoneInput ? phoneInput.value : '').trim();
            deliveryAddress = (addrInput ? addrInput.value : '').trim();

            if (!customerPhone) {
                showCartError('Harap isi nomor WhatsApp Anda untuk konfirmasi kurir.');
                phoneInput?.focus();
                return;
            }
            if (!deliveryAddress) {
                showCartError('Harap isi alamat lengkap pengiriman atau tentukan pin di peta.');
                addrInput?.focus();
                return;
            }
        } else {
            const tableInput = document.getElementById('modalTableNo');
            const dineRadio = document.querySelector('input[name="modal_dine_type"]:checked');
            const isDineIn = dineRadio ? dineRadio.value === 'dine_in' : true;
            tableNo = (tableInput && isDineIn) ? tableInput.value.trim() : (isDineIn ? 'Makan di Tempat' : 'Bawa Pulang');
        }

        const notesInput = document.getElementById('modalOrderNotes');
        const notes = notesInput ? notesInput.value.trim() : '';

        // Prepare order payload
        const items = keys.map(k => ({
            id: window.nucomuCart[k].id,
            name: window.nucomuCart[k].name,
            price: window.nucomuCart[k].price,
            qty: window.nucomuCart[k].qty,
            options: window.nucomuCart[k].optionsStr || ''
        }));

        const submitBtn = document.getElementById('modalSubmitBtn');
        const spinner = document.getElementById('modalSubmitSpinner');
        if (submitBtn) submitBtn.disabled = true;
        if (spinner) spinner.classList.remove('hidden');
        if (errorBanner) errorBanner.classList.add('hidden');

        const payload = {
            _token: CART_CSRF_TOKEN,
            customer_name: customerName,
            customer_phone: customerPhone,
            process_method: modalCurrentMethod,
            delivery_address: deliveryAddress,
            destination_lat: (isZendo && !CART_HIDE_LIVE_TRACKING) ? modalSelectedLat : null,
            destination_lng: (isZendo && !CART_HIDE_LIVE_TRACKING) ? modalSelectedLng : null,
            distance_km: (isZendo && !CART_HIDE_LIVE_TRACKING) ? modalDistanceKm : 0,
            delivery_fee: (isZendo && !CART_HIDE_LIVE_TRACKING) ? modalDeliveryFee : 0,
            table_no: tableNo,
            notes: notes,
            items: items
        };

        fetch('{{ route("nucomu.order.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CART_CSRF_TOKEN
            },
            body: JSON.stringify(payload)
        })
        .then(async res => {
            const data = await res.json().catch(() => null);
            if (!res.ok || !data) {
                throw new Error(data && data.message ? data.message : 'Gagal mengirim pesanan ke server');
            }
            return data;
        })
        .then(data => {
            handleOrderSuccess(data, customerName, isZendo);
        })
        .catch(err => {
            showCartError(err.message || 'Terjadi gangguan jaringan saat memproses pesanan.');
            resetModalSubmitButton();
        });
    };

    function showCartError(msg) {
        const errorBanner = document.getElementById('modalCartError');
        if (errorBanner) {
            errorBanner.innerText = msg;
            errorBanner.classList.remove('hidden');
            errorBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function handleOrderSuccess(data, customerName, isZendo) {
        // Clear local cart
        window.nucomuCart = {};
        try {
            localStorage.removeItem('nucomu_cart');
        } catch (e) {}
        renderModalCart();

        const formView = document.getElementById('cartModalFormView');
        const successView = document.getElementById('cartModalSuccessView');
        const codeEl = document.getElementById('modalSuccessOrderCode');
        const nameEl = document.getElementById('modalSuccessCustomerName');
        const typeEl = document.getElementById('modalSuccessType');
        const totalEl = document.getElementById('modalSuccessTotal');
        const slipLink = document.getElementById('modalSuccessSlipLink');
        const waBtn = document.getElementById('modalSuccessWaBtn');
        const trackBtn = document.getElementById('modalSuccessTrackingBtn');
        const qrPlate = document.getElementById('modalQrPlate');
        const successBadge = document.getElementById('successBadge');
        const successTitle = document.getElementById('successTitle');
        const successSubtext = document.getElementById('successSubtext');

        if (formView) formView.classList.add('hidden');
        if (successView) {
            successView.classList.remove('hidden');
            successView.classList.add('flex');
        }

        if (codeEl) codeEl.innerText = data.order_code || 'NCM-ORDER';
        if (nameEl) nameEl.innerText = customerName;
        if (typeEl) typeEl.innerText = isZendo ? 'Delivery Kurir Zendo' : 'Kasir (Makan di Tempat / Take Away)';
        if (totalEl) totalEl.innerText = 'Rp ' + (data.grand_total || 0).toLocaleString('id-ID');

        if (slipLink && data.slip_url) {
            slipLink.href = data.slip_url;
            slipLink.classList.remove('hidden');
        } else if (slipLink) {
            slipLink.classList.add('hidden');
        }

        if (isZendo) {
            if (qrPlate) qrPlate.classList.add('hidden');
            if (slipLink) slipLink.classList.add('hidden');

            const isHiddenTracking = data.hide_live_tracking || !data.tracking_url;

            if (isHiddenTracking) {
                // Mode Klien: Live tracking disembunyikan, langsung ke WhatsApp struk ringkas
                if (trackBtn) trackBtn.classList.add('hidden');
                if (waBtn && data.whatsapp_url) {
                    waBtn.href = data.whatsapp_url;
                    waBtn.classList.remove('hidden');
                }
                if (successBadge) successBadge.innerText = 'Pesanan Berhasil Disimpan';
                if (successTitle) successTitle.innerText = 'Membuka WhatsApp...';
                if (successSubtext) successSubtext.innerText = 'Pesanan langsung diteruskan ke WhatsApp dengan format struk belanja ringkas.';

                // Langsung arahkan browser ke WhatsApp
                if (data.whatsapp_url) {
                    window.location.href = data.whatsapp_url;
                }
            } else {
                // Mode Sekolah: Fitur Live Tracking aktif penuh
                if (waBtn) waBtn.classList.add('hidden');
                if (trackBtn && data.tracking_url) {
                    trackBtn.href = data.tracking_url;
                    trackBtn.classList.remove('hidden');
                } else if (trackBtn) {
                    trackBtn.classList.add('hidden');
                }
                if (successBadge) successBadge.innerText = 'Pesanan Diteruskan ke Kurir Zendo';
                if (successTitle) successTitle.innerText = 'Pesanan Berhasil Dikirim!';
                if (successSubtext) successSubtext.innerText = 'Kurir Zendo akan segera memproses pengantaran pesanan Anda.';

                if (data.whatsapp_url) {
                    window.open(data.whatsapp_url, '_blank');
                }
            }
        } else {
            if (waBtn) waBtn.classList.add('hidden');
            if (trackBtn) trackBtn.classList.add('hidden');
            if (qrPlate) qrPlate.classList.remove('hidden');
            if (successBadge) successBadge.innerText = 'Slip Pesanan Kasir Siap';
            if (successTitle) successTitle.innerText = 'Pesanan Anda Diterima';
            if (successSubtext) successSubtext.innerText = 'Tunjukkan kode QR berikut ke kasir untuk proses pencetakan dan pembayaran.';

            // Generate QR Code with qrcode.js
            generateKasirQrCode(data.order_code || 'NCM-ORDER', data.slip_url || '');
        }
    }

    function generateKasirQrCode(orderCode, slipUrl) {
        const target = document.getElementById('modalQrcodeTarget');
        const fallback = document.getElementById('modalQrFallback');
        if (!target) return;

        target.innerHTML = '';
        const qrContent = slipUrl || orderCode;

        if (typeof QRCode !== 'undefined') {
            try {
                new QRCode(target, {
                    text: qrContent,
                    width: 160,
                    height: 160,
                    colorDark: '#2c2825',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M
                });
                return;
            } catch (e) {}
        }

        // Fallback to quick QR API
        if (fallback) {
            fallback.src = `https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${encodeURIComponent(qrContent)}&color=2c2825`;
            fallback.classList.remove('hidden');
        }
    }

    window.resetModalToCart = function() {
        const formView = document.getElementById('cartModalFormView');
        const successView = document.getElementById('cartModalSuccessView');
        if (successView) {
            successView.classList.add('hidden');
            successView.classList.remove('flex');
        }
        if (formView) formView.classList.remove('hidden');
        closeCartModal();
        resetModalSubmitButton();
    };

    // ============================================
    // DOM READY & AUTO-PARAM HANDLER
    // ============================================
    document.addEventListener('DOMContentLoaded', () => {
        setModalProcessMethod(modalCurrentMethod);

        // Pre-select menu if ?add= is in URL
        const params = new URLSearchParams(window.location.search);
        const preAdd = params.get('add');
        if (preAdd) {
            const pLower = preAdd.trim().toLowerCase();
            const allItems = window.nucomuBestMenuItems || [];
            const found = allItems.find(m => (m.name || '').toLowerCase().includes(pLower));
            if (found) {
                const numPrice = found.price || parseInt(String(found.price_formatted || '').replace(/[^0-9]/g, ''), 10) || 0;
                window.addToCart(found.id || found.slug || 'pre-add', found.name, numPrice, null, found.image);
            }
        }

        // Open cart directly if URL has #cart or ?open_cart=1
        if (window.location.hash === '#cart' || params.get('open_cart') === '1') {
            window.openCartModal();
        }
    });
</script>
