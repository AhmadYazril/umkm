<!-- =========================================================================
     MY CART SIDEBAR DRAWER (Matching Original Design & Reference UI)
     Clean Nucomu White / Stone Palette with In-Card Checkout Integration
     ========================================================================= -->

<!-- Backdrop Overlay -->
<div id="cart-drawer-backdrop" 
     class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeCartDrawer()">
</div>

<!-- Slide-Over Drawer Panel (Far Right) -->
<aside id="cart-drawer-panel" 
       class="fixed top-0 right-0 h-full w-full max-w-sm sm:max-w-md md:max-w-lg bg-white z-50 shadow-2xl flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out border-l border-stone-200">
    
    <!-- Drawer Header -->
    <div class="p-4 sm:p-5 border-b border-stone-200/90 flex items-center justify-between bg-stone-50/90">
        <!-- View 1: Cart Header -->
        <div id="cart-header-items" class="flex items-center space-x-2.5">
            <span class="material-symbols-outlined text-neutral-900 text-2xl">shopping_cart</span>
            <h3 class="font-editorial text-xl sm:text-2xl font-bold text-neutral-900 tracking-tight">
                My cart
            </h3>
            <span id="cart-header-count" class="px-2 py-0.5 text-xs font-bold rounded-full bg-neutral-100 border border-neutral-200 text-neutral-900">
                0
            </span>
        </div>

        <!-- View 2: Checkout Header (Initially hidden) -->
        <div id="cart-header-checkout" class="hidden items-center space-x-2.5">
            <button type="button" 
                    onclick="switchCartView('items')" 
                    class="w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </button>
            <h3 class="font-editorial text-xl font-bold text-neutral-900 tracking-tight">
                Checkout Pesanan
            </h3>
        </div>

        <!-- View 3: Success Header (Initially hidden) -->
        <div id="cart-header-success" class="hidden items-center space-x-2.5">
            <span class="material-symbols-outlined text-emerald-600 text-2xl">check_circle</span>
            <h3 class="font-editorial text-xl font-bold text-neutral-900 tracking-tight">
                Pesanan Berhasil
            </h3>
        </div>

        <!-- Close Button -->
        <button type="button" 
                onclick="closeCartDrawer()" 
                class="w-9 h-9 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-600 hover:text-stone-900 flex items-center justify-center transition-colors focus:outline-none"
                aria-label="Tutup Keranjang">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
    </div>

    <!-- =========================================================================
         VIEW 1: CART ITEMS LIST (Original Clean White / Stone Layout)
         ========================================================================= -->
    <div id="cart-view-items" class="flex flex-col flex-grow overflow-hidden">
        <!-- Scrollable Items Area -->
        <div class="flex-grow overflow-y-auto p-4 sm:p-5 space-y-4" id="cart-scroll-area">
            
            <!-- Empty State -->
            <div id="cart-empty-state" class="py-16 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-stone-100 flex items-center justify-center text-stone-400 mb-3">
                    <span class="material-symbols-outlined text-3xl">remove_shopping_cart</span>
                </div>
                <h4 class="font-editorial text-lg font-bold text-neutral-800">Keranjang Masih Kosong</h4>
                <p class="font-body text-xs text-neutral-500 max-w-xs mx-auto mt-1 leading-relaxed">
                    Pilih sajian favorit Anda di daftar menu dan klik tombol <span class="text-neutral-900 font-semibold">+ Add to Cart</span> untuk mulai memesan.
                </p>
            </div>

            <!-- Populated Cart Items List Container -->
            <div id="cart-items-list" class="space-y-3">
                <!-- Dynamically populated by JS renderCart() -->
            </div>
        </div>

        <!-- Cart View Footer: Pricing Breakdown & Checkout Button -->
        <div id="cart-footer-panel" class="p-4 sm:p-5 border-t border-stone-200 bg-stone-50/90 space-y-3.5">
            <div class="space-y-1.5 text-xs sm:text-sm">
                <div class="flex items-center justify-between text-neutral-600 font-medium">
                    <span>Subtotal Menu</span>
                    <span id="cart-subtotal" class="font-semibold text-neutral-900">Rp 0</span>
                </div>
                <div class="flex items-center justify-between text-neutral-500 text-[11px] sm:text-xs">
                    <span>Diskon / Promo</span>
                    <span class="font-medium text-emerald-600">Rp 0</span>
                </div>
                <div class="pt-2 border-t border-stone-200/80 flex items-baseline justify-between">
                    <span class="font-bold text-sm sm:text-base text-neutral-900">Final Total</span>
                    <span id="cart-final-total" class="font-editorial text-lg sm:text-xl font-bold text-neutral-900">
                        Rp 0
                    </span>
                </div>
            </div>

            <!-- Monochromatic Checkout Button (Switches to in-card checkout) -->
            <button type="button" 
                    id="cart-checkout-btn"
                    onclick="switchCartView('checkout')" 
                    class="w-full py-3.5 px-5 rounded-2xl bg-neutral-900 hover:bg-black text-white font-bold text-sm tracking-wide shadow-lg shadow-neutral-900/15 active:scale-98 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                <span>Checkout / Pesan Sekarang</span>
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </button>

            <!-- Secondary: Order via WhatsApp -->
            <button type="button" 
                    onclick="checkoutViaWhatsApp()" 
                    class="w-full py-2.5 px-4 rounded-xl border border-stone-200 hover:border-neutral-900 text-neutral-700 hover:text-neutral-900 font-medium text-xs tracking-wide transition-all flex items-center justify-center space-x-1.5 bg-white shadow-xs">
                <span class="material-symbols-outlined text-sm text-neutral-800">chat</span>
                <span>Atau Pesan Cepat via WhatsApp</span>
            </button>
        </div>
    </div>


    <!-- =========================================================================
         VIEW 2: IN-CARD CHECKOUT FORM (Clean Nucomu White / Stone Palette)
         Matching User Reference Layout, styled with the original bright theme!
         ========================================================================= -->
    <div id="cart-view-checkout" class="hidden flex-col flex-grow overflow-hidden">
        <form id="drawer-checkout-form" onsubmit="submitDrawerCheckout(event)" class="flex flex-col flex-grow overflow-hidden">
            <!-- Scrollable Form Body -->
            <div class="flex-grow overflow-y-auto p-4 sm:p-5 space-y-4">
                
                <!-- 1. METODE PROSES TRANSAKSI -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-neutral-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="text-neutral-900">⚙</span> 1. Metode Proses Transaksi
                        </label>
                        <span class="px-2 py-0.5 rounded-full bg-stone-100 border border-stone-200 text-[10px] font-bold text-neutral-800 uppercase tracking-wider">
                            KASIR / ZENDO
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <!-- Option A: Proses Kasir -->
                        <div id="method-card-kasir" 
                             onclick="selectDrawerMethod('kasir')" 
                             class="drawer-method-card active relative p-3 rounded-2xl bg-white border-2 border-neutral-900 shadow-md cursor-pointer transition-all">
                            <div class="flex items-start justify-between">
                                <span class="material-symbols-outlined text-neutral-900 text-xl">qr_code_2</span>
                                <span class="method-dot w-2.5 h-2.5 rounded-full bg-neutral-900"></span>
                            </div>
                            <h5 class="text-xs sm:text-sm font-bold text-neutral-900 mt-2">Proses Kasir</h5>
                            <p class="text-[10px] text-neutral-500 mt-0.5">Slip & QR Kasir</p>
                        </div>

                        <!-- Option B: Kurir Zendo -->
                        <div id="method-card-zendo" 
                             onclick="selectDrawerMethod('zendo')" 
                             class="drawer-method-card relative p-3 rounded-2xl bg-white border-2 border-stone-200 hover:border-stone-400 cursor-pointer transition-all">
                            <div class="flex items-start justify-between">
                                <span class="material-symbols-outlined text-stone-400 text-xl">two_wheeler</span>
                                <span class="method-dot w-2.5 h-2.5 rounded-full bg-stone-300"></span>
                            </div>
                            <h5 class="text-xs sm:text-sm font-bold text-neutral-900 mt-2">Kurir Zendo</h5>
                            <p class="text-[10px] text-neutral-500 mt-0.5">Peta & Kirim Otomatis</p>
                        </div>
                    </div>
                </div>

                <!-- 2. DAFTAR MENU PESANAN (Ringkasan Item) -->
                <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/90 space-y-2.5">
                    <div class="flex items-center justify-between border-b border-stone-200 pb-2">
                        <span class="text-xs font-bold text-neutral-900 flex items-center gap-1.5">
                            <span class="text-neutral-900">🛍</span> 2. Daftar Menu Pesanan
                        </span>
                        <span id="checkout-summary-count" class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white border border-stone-200 text-neutral-700">
                            0 Item
                        </span>
                    </div>

                    <!-- Mini Cart Items List -->
                    <div id="checkout-summary-items" class="space-y-2 text-xs max-h-40 overflow-y-auto pr-1">
                        <!-- Populated by JS -->
                    </div>

                    <!-- Subtotal & Total Line -->
                    <div class="pt-2 border-t border-stone-200 space-y-1 text-xs">
                        <div class="flex justify-between text-neutral-600">
                            <span>Subtotal Menu</span>
                            <span id="checkout-subtotal-val" class="font-semibold text-neutral-900">Rp 0</span>
                        </div>
                        <div id="checkout-zendo-fee-row" class="hidden justify-between text-neutral-600">
                            <span>Ongkir Kurir Zendo</span>
                            <span id="checkout-zendo-fee-val" class="font-semibold text-emerald-600">Rp 10.000</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold pt-1.5 text-neutral-900 border-t border-stone-200/80">
                            <span>Total Pembayaran</span>
                            <span id="checkout-total-val" class="text-base font-editorial font-bold text-neutral-900">Rp 0</span>
                        </div>
                        <p id="checkout-note-helper" class="text-[10px] text-neutral-500 pt-0.5 italic">
                            *Bayar di kasir setelah menunjukkan slip QR.
                        </p>
                    </div>
                </div>

                <!-- 3. DATA PEMESAN & LOKASI -->
                <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200/90 space-y-3">
                    <span class="text-xs font-bold text-neutral-900 flex items-center gap-1.5 border-b border-stone-200 pb-2">
                        <span class="text-neutral-900">👤</span> 3. Data Pemesan & Lokasi
                    </span>

                    <!-- Nama Pemesan -->
                    <div>
                        <label class="block text-[11px] font-semibold text-neutral-700 mb-1">
                            Nama Pemesan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="drawer-customer-name" 
                               required
                               placeholder="Masukkan nama lengkap Anda..." 
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-stone-200 text-neutral-900 text-xs focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900/10 transition-all placeholder:text-stone-400">
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <label class="block text-[11px] font-semibold text-neutral-700 mb-1">
                            Nomor WhatsApp <span class="text-stone-400 font-normal">(opsional)</span>
                        </label>
                        <input type="tel" 
                               id="drawer-customer-phone" 
                               placeholder="08xxxxxxxxxx" 
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-stone-200 text-neutral-900 text-xs focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900/10 transition-all placeholder:text-stone-400">
                    </div>

                    <!-- KASIR SPECIFIC: Dine In vs Take Away -->
                    <div id="drawer-field-kasir-type" class="space-y-2">
                        <label class="block text-[11px] font-semibold text-neutral-700">
                            Tipe Pesanan
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center space-x-2 p-2.5 rounded-xl bg-white border border-stone-200 cursor-pointer text-xs select-none">
                                <input type="radio" name="drawer_dine_type" value="dine_in" checked onchange="toggleDrawerTableInput(true)" class="accent-neutral-900">
                                <span class="font-medium text-neutral-800">Makan di Tempat</span>
                            </label>
                            <label class="flex items-center space-x-2 p-2.5 rounded-xl bg-white border border-stone-200 cursor-pointer text-xs select-none">
                                <input type="radio" name="drawer_dine_type" value="take_away" onchange="toggleDrawerTableInput(false)" class="accent-neutral-900">
                                <span class="font-medium text-neutral-800">Bawa Pulang</span>
                            </label>
                        </div>
                        <div id="drawer-table-wrapper" class="pt-1">
                            <input type="text" 
                                   id="drawer-table-no" 
                                   placeholder="Nomor Meja (Contoh: Meja 04)" 
                                   class="w-full py-2.5 px-3 rounded-xl bg-white border border-stone-200 text-neutral-900 text-xs focus:outline-none focus:border-neutral-900 transition-all placeholder:text-stone-400">
                        </div>
                    </div>

                    <!-- ZENDO SPECIFIC: Alamat & Area Pengiriman (Hidden by default) -->
                    <div id="drawer-field-zendo-address" class="hidden space-y-2">
                        <label class="block text-[11px] font-semibold text-neutral-700">
                            Pilih Area / Alamat Pengiriman <span class="text-rose-500">*</span>
                        </label>
                        <select id="drawer-zendo-area-select" 
                                onchange="onDrawerZendoAreaChange(this.value)"
                                class="w-full py-2.5 px-3 rounded-xl bg-white border border-stone-200 text-neutral-900 text-xs focus:outline-none focus:border-neutral-900 transition-all">
                            <option value="Plosokandang, Kedungwaru, Tulungagung">Plosokandang, Kedungwaru (~1.5 km)</option>
                            <option value="Kedungwaru, Tulungagung">Kedungwaru Kota (~2.0 km)</option>
                            <option value="Aloon-Aloon Tulungagung / Kampungdalem">Aloon-Aloon Tulungagung (~2.8 km)</option>
                            <option value="Bago, Tulungagung">Bago, Tulungagung (~2.5 km)</option>
                            <option value="Boyolangu, Tulungagung">Boyolangu, Tulungagung (~3.5 km)</option>
                            <option value="custom">-- Tulis Alamat Manual --</option>
                        </select>
                        <textarea id="drawer-zendo-address-manual" 
                                  rows="2" 
                                  placeholder="Detail patokan alamat (nama jalan, warna pagar, RT/RW)..." 
                                  class="w-full py-2.5 px-3 rounded-xl bg-white border border-stone-200 text-neutral-900 text-xs focus:outline-none focus:border-neutral-900 transition-all placeholder:text-stone-400"></textarea>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block text-[11px] font-semibold text-neutral-500 mb-1">
                            Catatan Tambahan <span class="text-stone-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="drawer-order-notes" 
                               placeholder="Contoh: Kurangi es, saus dipisah..." 
                               class="w-full py-2.5 px-3 rounded-xl bg-white border border-stone-200 text-neutral-900 text-xs focus:outline-none focus:border-neutral-900 transition-all placeholder:text-stone-400">
                    </div>

                    <!-- Error Feedback Banner -->
                    <div id="drawer-checkout-error" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    </div>
                </div>

            </div>

            <!-- Checkout View Footer: Submit & Back -->
            <div class="p-4 sm:p-5 border-t border-stone-200 bg-stone-50/90 space-y-2">
                <button type="submit" 
                        id="drawer-submit-btn" 
                        class="w-full py-3.5 px-5 rounded-2xl bg-neutral-900 hover:bg-black text-white font-bold text-sm tracking-wide shadow-lg shadow-neutral-900/15 active:scale-98 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                    <span id="drawer-submit-text">Konfirmasi & Buat Pesanan (Kasir)</span>
                    <span id="drawer-submit-spinner" class="hidden animate-spin material-symbols-outlined text-base">progress_activity</span>
                </button>
                <button type="button" 
                        onclick="switchCartView('items')" 
                        class="w-full py-2 text-center text-xs font-semibold text-neutral-500 hover:text-neutral-900 transition-colors">
                    ← Kembali ke Daftar Menu
                </button>
            </div>
        </form>
    </div>


    <!-- =========================================================================
         VIEW 3: SUCCESS & ORDER SLIP QR (Clean White / Stone Box)
         ========================================================================= -->
    <div id="cart-view-success" class="hidden flex-col flex-grow overflow-hidden p-5 sm:p-6 text-center space-y-4">
        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
            <span class="material-symbols-outlined text-3xl">check_circle</span>
        </div>

        <div>
            <span class="text-[11px] font-bold uppercase tracking-widest text-neutral-700 block">PESANAN DITERIMA</span>
            <h4 class="font-editorial text-2xl font-bold text-neutral-900 mt-0.5" id="success-order-code">NCM-ORDER</h4>
            <p class="text-xs text-neutral-500 mt-1" id="success-subtext">
                Tunjukkan QR code ini ke kasir untuk proses pembayaran.
            </p>
        </div>

        <!-- QR Code Plate (White Plate with Border) -->
        <div class="bg-white p-4 rounded-3xl border border-stone-200 shadow-xl mx-auto w-fit flex flex-col items-center justify-center" id="drawer-qr-container">
            <div id="drawer-qrcode-target"></div>
            <img id="drawer-qr-fallback" class="hidden w-40 h-40 object-contain rounded" alt="QR Code">
            <span class="text-[10px] text-neutral-600 font-bold uppercase tracking-wider mt-2.5 block" id="success-qr-label">
                SCAN DI KASIR
            </span>
        </div>

        <!-- Order Summary Details -->
        <div class="p-3.5 rounded-2xl bg-stone-50 border border-stone-200 text-xs text-left space-y-1.5">
            <div class="flex justify-between text-neutral-600">
                <span>Pemesan:</span>
                <span id="success-customer-name" class="font-bold text-neutral-900">-</span>
            </div>
            <div class="flex justify-between text-neutral-600">
                <span>Tipe:</span>
                <span id="success-order-type" class="font-bold text-neutral-900">-</span>
            </div>
            <div class="flex justify-between text-neutral-600 pt-1.5 border-t border-stone-200">
                <span>Total:</span>
                <span id="success-total-amount" class="font-bold text-emerald-600 text-sm">Rp 0</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-2 pt-2">
            <a id="success-slip-link" 
               href="#" 
               target="_blank" 
               class="w-full py-3 px-4 rounded-xl bg-stone-100 hover:bg-stone-200 text-neutral-800 font-bold text-xs flex items-center justify-center space-x-1.5 transition-colors">
                <span class="material-symbols-outlined text-sm">receipt_long</span>
                <span>Buka Slip Lengkap & Cetak Struk</span>
            </a>
            <button type="button" 
                    onclick="resetDrawerToCart()" 
                    class="w-full py-3 px-4 rounded-xl bg-neutral-900 hover:bg-black text-white font-bold text-xs shadow-md shadow-neutral-900/15 transition-all">
                Selesai / Pesan Menu Lain
            </button>
        </div>
    </div>

</aside>

<!-- Floating Cart Button (Exact Match to User Reference Screenshot: White Capsule, Black Icon & Text) -->
<button type="button" 
        id="floating-cart-btn"
        onclick="toggleCartDrawer()" 
        class="fixed bottom-6 right-6 z-40 bg-white border border-stone-200/90 hover:border-neutral-400 text-neutral-900 shadow-md hover:shadow-xl rounded-2xl px-4 py-2.5 flex items-center space-x-2 transition-all duration-300 hover:scale-105 active:scale-95 group focus:outline-none"
        aria-label="Lihat Keranjang Belanja">
    <div class="relative flex items-center">
        <span class="material-symbols-outlined text-neutral-900 text-xl group-hover:scale-110 transition-transform">
            shopping_cart
        </span>
        <span id="floating-cart-badge" 
              class="absolute -top-2 -right-2 bg-neutral-900 text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-xs">
            0
        </span>
    </div>
    <span class="font-body text-xs font-semibold text-neutral-800 tracking-wide">
        My cart:
    </span>
    <span id="floating-cart-total" class="font-editorial text-xs font-bold text-neutral-900">
        Rp -
    </span>
</button>
