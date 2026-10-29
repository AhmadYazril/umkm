<!-- Reservation Modal Form -->
<div id="reservation-modal" 
     class="fixed inset-0 z-50 bg-[#1C1C1C]/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeReservationModal()"
     role="dialog"
     aria-modal="true"
     aria-labelledby="reservation-modal-title">
    
    <div id="reservation-modal-dialog"
         class="bg-[#FAFAFA] rounded-[24px] sm:rounded-[28px] max-w-2xl w-full p-5 sm:p-7 shadow-2xl border border-[#E5E5E5] relative max-h-[92vh] flex flex-col transform translate-y-6 scale-[0.98] transition-all duration-300 ease-out font-sans overflow-hidden"
         onclick="event.stopPropagation()">
        
        <!-- Close Button (X) -->
        <button type="button"
                id="res-close-btn"
                onclick="closeReservationModal()" 
                class="absolute top-4 right-4 sm:top-5 sm:right-5 w-9 h-9 rounded-full bg-white border border-[#E5E5E5] text-[#1C1C1C] hover:bg-[#242424] hover:text-white transition-colors duration-200 flex items-center justify-center z-20 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]"
                aria-label="Tutup Formulir Reservasi">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Modal Header -->
        <div class="border-b border-[#E5E5E5] pb-4 mb-4 pr-10 shrink-0">
            <span class="block text-[10px] sm:text-[11px] font-semibold uppercase tracking-[0.2em] text-[#8A8A8A] mb-1.5">
                Formulir Reservasi &amp; Paket Acara
            </span>
            <h3 id="reservation-modal-title" class="font-sans text-2xl sm:text-3xl font-semibold tracking-tight text-[#1C1C1C]">
                Reservasi Nucomu Cafe
            </h3>
            <p class="text-xs text-[#666666] leading-relaxed mt-1">
                Lengkapi data kunjungan dan pilih paket dekorasi acara spesial Anda. Rincian reservasi akan otomatis dirangkum ke WhatsApp resmi Nucomu.
            </p>
        </div>

        <!-- Scrollable Form Container -->
        <div id="reservation-modal-body" class="overflow-y-auto flex-1 pr-1 -mr-1 pb-4 space-y-4">
            <!-- Inline Error Box -->
            <div id="res-error-box" class="hidden p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl leading-relaxed">
                <!-- Injected via JavaScript -->
            </div>

            <!-- Success Feedback Toast -->
            <div id="res-success-toast" class="hidden p-3.5 bg-[#242424] border border-white/10 text-white text-xs rounded-xl text-center font-medium shadow-md">
                Pengajuan berhasil dirangkum. Membuka WhatsApp resmi Nucomu...
            </div>

            <!-- Reservation Form -->
            <form id="reservation-form" novalidate onsubmit="return handleReservationSubmit(event)" class="space-y-4">
                <!-- 1. Nama Pemesan & 2. Nomor WhatsApp (2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                    <div>
                        <label for="res-name" class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">
                            Nama Pemesan <span class="text-[#1C1C1C]">*</span>
                        </label>
                        <input type="text" 
                               id="res-name" 
                               name="fullName" 
                               required 
                               placeholder="Contoh: Raditya Danendra" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs font-sans text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#242424] focus:ring-1 focus:ring-[#242424] transition-colors">
                    </div>
                    <div>
                        <label for="res-phone" class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">
                            Nomor WhatsApp Aktif <span class="text-[#1C1C1C]">*</span>
                        </label>
                        <input type="tel" 
                               id="res-phone" 
                               name="phone" 
                               required 
                               placeholder="081234567890" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs font-sans text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#242424] focus:ring-1 focus:ring-[#242424] transition-colors">
                    </div>
                </div>

                <!-- 3. Tanggal Acara & 4. Waktu Kedatangan (2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                    <div>
                        <label for="res-date" class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">
                            Tanggal Acara <span class="text-[#1C1C1C]">*</span>
                        </label>
                        <input type="date" 
                               id="res-date" 
                               name="date" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs font-sans text-[#1C1C1C] focus:outline-none focus:border-[#242424] focus:ring-1 focus:ring-[#242424] transition-colors">
                    </div>
                    <div>
                        <label for="res-time" class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">
                            Waktu Kedatangan <span class="text-[#1C1C1C]">*</span>
                        </label>
                        <input type="time" 
                               id="res-time" 
                               name="time" 
                               required 
                               value="15:00"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs font-sans text-[#1C1C1C] focus:outline-none focus:border-[#242424] focus:ring-1 focus:ring-[#242424] transition-colors">
                    </div>
                </div>

                <!-- 5. Jumlah Tamu & 6. Pilihan Area (2 Kolom - Lebar Lega & Tidak Terpotong) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                    <div>
                        <label for="res-guests" class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">
                            Jumlah Tamu <span class="text-[#1C1C1C]">*</span>
                        </label>
                        <select id="res-guests" 
                                name="guestCount" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs font-sans text-[#1C1C1C] focus:outline-none focus:border-[#242424] focus:ring-1 focus:ring-[#242424] transition-colors">
                            <option value="1 - 2 Orang">1 – 2 Orang (Meja Intim)</option>
                            <option value="3 - 5 Orang" selected>3 – 5 Orang (Meja Reguler)</option>
                            <option value="6 - 10 Orang">6 – 10 Orang (Meja Komunal)</option>
                            <option value="10+ Orang">10+ Orang (Group Gathering)</option>
                            <option value="Acara Privat (Seluruh Ruang)">Acara Privat (Seluruh Ruang)</option>
                        </select>
                    </div>
                    <div>
                        <label for="res-area" class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">
                            Pilihan Area <span class="text-[#1C1C1C]">*</span>
                        </label>
                        <select id="res-area" 
                                name="area" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs font-sans text-[#1C1C1C] focus:outline-none focus:border-[#242424] focus:ring-1 focus:ring-[#242424] transition-colors">
                            <option value="Indoor (AC & Working Space)">Indoor (AC &amp; Working Space)</option>
                            <option value="Outdoor (Smoking Area & Garden)">Outdoor (Smoking Area &amp; Garden)</option>
                            <option value="Fleksibel (Sesuai Ketersediaan)">Fleksibel (Sesuai Ketersediaan)</option>
                        </select>
                    </div>
                </div>

                <!-- 7. SECTION PAKET ACARA: BRIDAL & BIRTHDAY -->
                @php
                    $packageOptions = [
                        [
                            'code' => 'A',
                            'name' => 'Paket Dekorasi A',
                            'price' => '35K',
                            'price_full' => 'Rp 35.000',
                            'badge' => 'Essential',
                            'desc' => 'Dekorasi meja, lilin, dipinjami korek, lettering ucapan',
                            'highlights' => ['Dekorasi Meja', 'Lilin & Korek', 'Lettering Ucapan'],
                        ],
                        [
                            'code' => 'B',
                            'name' => 'Paket Dekorasi B',
                            'price' => '60K',
                            'price_full' => 'Rp 60.000',
                            'badge' => 'Populer',
                            'desc' => 'Paket 35K + cheesecake slice 1',
                            'highlights' => ['Semua Paket 35K', '+ 1 Cheesecake Slice'],
                        ],
                        [
                            'code' => 'C',
                            'name' => 'Paket Dekorasi C',
                            'price' => '100K',
                            'price_full' => 'Rp 100.000',
                            'badge' => 'Best Value',
                            'desc' => 'Paket 35K + 1 whole cheesecake diameter 10cm + 1 minuman nucomu coffee',
                            'highlights' => ['Semua Paket 35K', '+ 1 Whole Cheesecake Ø 10cm', '+ 1 Minuman Nucomu Coffee'],
                        ],
                    ];
                @endphp

                <div class="rounded-2xl bg-white border border-[#E5E5E5] p-4 sm:p-5 space-y-4 shadow-2xs">
                    <!-- STEP 1: PILIH KATEGORI PAKET ACARA TERLEBIH DAHULU -->
                    <div>
                        <div class="mb-3">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-[#1C1C1C]">
                                1. Pilih Kategori Paket Acara Terlebih Dahulu <span class="text-[#1C1C1C]">*</span>
                            </label>
                            <p class="text-[11px] text-[#666666] mt-0.5">
                                Pilih acara <strong>Bridal</strong> atau <strong>Birthday</strong> di bawah ini untuk menampilkan daftar pilihan paket dekorasinya:
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Card Pilihan 1: Bridal -->
                            <button type="button"
                                    id="btn-choose-bridal"
                                    onclick="chooseReservationEventCategory('bridal')"
                                    data-section-tab="bridal"
                                    class="res-section-tab-btn group text-left rounded-2xl p-4 border border-[#E5E5E5] hover:border-[#CCCCCC] bg-white transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]">
                                <div class="flex items-center gap-3">
                                    <div id="icon-box-bridal" class="w-11 h-11 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0 transition-colors">
                                        <span class="material-symbols-outlined text-xl">favorite</span>
                                    </div>
                                    <div>
                                        <span id="label-cat-bridal" class="text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A] block transition-colors">Kategori 01</span>
                                        <span id="title-cat-bridal" class="font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] block leading-tight transition-colors">Paket Acara Bridal</span>
                                        <span id="sub-cat-bridal" class="text-[11px] text-[#666666] block mt-0.5 transition-colors">Bridal Shower &amp; Intimate Party</span>
                                    </div>
                                </div>
                                <span id="check-cat-bridal" class="w-6 h-6 rounded-full border border-[#E5E5E5] bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0">
                                    ✓
                                </span>
                            </button>

                            <!-- Card Pilihan 2: Birthday -->
                            <button type="button"
                                    id="btn-choose-birthday"
                                    onclick="chooseReservationEventCategory('birthday')"
                                    data-section-tab="birthday"
                                    class="res-section-tab-btn group text-left rounded-2xl p-4 border border-[#E5E5E5] hover:border-[#CCCCCC] bg-white transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]">
                                <div class="flex items-center gap-3">
                                    <div id="icon-box-birthday" class="w-11 h-11 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0 transition-colors">
                                        <span class="material-symbols-outlined text-xl">cake</span>
                                    </div>
                                    <div>
                                        <span id="label-cat-birthday" class="text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A] block transition-colors">Kategori 02</span>
                                        <span id="title-cat-birthday" class="font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] block leading-tight transition-colors">Paket Acara Birthday</span>
                                        <span id="sub-cat-birthday" class="text-[11px] text-[#666666] block mt-0.5 transition-colors">Birthday Celebration &amp; Treats</span>
                                    </div>
                                </div>
                                <span id="check-cat-birthday" class="w-6 h-6 rounded-full border border-[#E5E5E5] bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0">
                                    ✓
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Inputs Bound to Form Submission -->
                    <input type="hidden" id="res-service-type" name="serviceType" value="">
                    <input type="hidden" id="res-event-section" name="eventSection" value="">
                    <input type="hidden" id="res-event-package" name="eventPackage" value="">

                    <!-- Prompt Sebelum Kategori Dipilih -->
                    <div id="res-empty-category-prompt" class="rounded-xl border border-dashed border-[#DDDDDD] bg-[#F5F5F5] px-4 py-4 text-center">
                        <span class="material-symbols-outlined text-[#242424] text-xl mb-1 block">touch_app</span>
                        <p class="text-xs font-semibold text-[#1C1C1C]">
                            Silakan klik salah satu kategori di atas (<strong>Paket Acara Bridal</strong> atau <strong>Paket Acara Birthday</strong>)
                        </p>
                        <p class="text-[11px] text-[#666666] mt-0.5">
                            Pilihan <strong>Paket Dekorasi A (35K)</strong>, <strong>Paket Dekorasi B (60K)</strong>, dan <strong>Paket Dekorasi C (100K)</strong> akan muncul setelah kategori dipilih.
                        </p>
                    </div>

                    <!-- STEP 2A: PILIHAN PAKET DEKORASI UNTUK BRIDAL (MUNCUL SETELAH PILIH BRIDAL) -->
                    <div id="res-section-bridal"
                         data-package-section="bridal"
                         class="res-package-section hidden rounded-2xl border border-[#E5E5E5] bg-[#F5F5F5] p-4 transition-all duration-300">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-white border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">favorite</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A]">2. Pilih Paket Dekorasi Bridal</span>
                                        <span id="badge-active-bridal" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#242424] text-white">
                                            ✓ Bridal Aktif
                                        </span>
                                    </div>
                                    <h4 class="font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] leading-tight mt-0.5">
                                        Pilihan Paket Acara Bridal (Bridal Shower &amp; Party)
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            @foreach($packageOptions as $pkg)
                                <div onclick="selectReservationPackage('Bridal', '{{ $pkg['name'] }}', '{{ $pkg['price'] }}', '{{ $pkg['desc'] }}', this)"
                                     data-section="Bridal"
                                     data-pkg-code="{{ $pkg['code'] }}"
                                     class="res-pkg-card cursor-pointer rounded-xl p-3.5 border border-[#E5E5E5] bg-white hover:border-[#CCCCCC] transition-all duration-200 flex flex-col justify-between relative shadow-2xs">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1.5">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#EEEEEE] text-[#444444] border border-[#DDDDDD]">
                                                {{ $pkg['badge'] }}
                                            </span>
                                            <span class="res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-[#F5F5F5] text-transparent border border-[#E5E5E5]">
                                                ✓
                                            </span>
                                        </div>

                                        <div class="flex items-baseline justify-between gap-1 mb-1">
                                            <h5 class="text-xs font-semibold text-[#1C1C1C]">{{ $pkg['name'] }}</h5>
                                            <span class="text-sm font-semibold text-[#1C1C1C]">{{ $pkg['price'] }}</span>
                                        </div>

                                        <p class="text-[11px] text-[#666666] leading-snug mb-2.5">
                                            {{ $pkg['desc'] }}
                                        </p>
                                    </div>

                                    <div class="pt-2 border-t border-[#F0F0F0] flex flex-wrap gap-1">
                                        @foreach($pkg['highlights'] as $hl)
                                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-[#F5F5F5] text-[#444444] font-medium border border-[#E5E5E5]">
                                                {{ $hl }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- STEP 2B: PILIHAN PAKET DEKORASI UNTUK BIRTHDAY (MUNCUL SETELAH PILIH BIRTHDAY) -->
                    <div id="res-section-birthday"
                         data-package-section="birthday"
                         class="res-package-section hidden rounded-2xl border border-[#E5E5E5] bg-[#F5F5F5] p-4 transition-all duration-300">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-white border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">cake</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A]">2. Pilih Paket Dekorasi Birthday</span>
                                        <span id="badge-active-birthday" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#242424] text-white">
                                            ✓ Birthday Aktif
                                        </span>
                                    </div>
                                    <h4 class="font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] leading-tight mt-0.5">
                                        Pilihan Paket Acara Birthday (Birthday Celebration)
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            @foreach($packageOptions as $pkg)
                                <div onclick="selectReservationPackage('Birthday', '{{ $pkg['name'] }}', '{{ $pkg['price'] }}', '{{ $pkg['desc'] }}', this)"
                                     data-section="Birthday"
                                     data-pkg-code="{{ $pkg['code'] }}"
                                     class="res-pkg-card cursor-pointer rounded-xl p-3.5 border border-[#E5E5E5] bg-white hover:border-[#CCCCCC] transition-all duration-200 flex flex-col justify-between relative shadow-2xs">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1.5">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#EEEEEE] text-[#444444] border border-[#DDDDDD]">
                                                {{ $pkg['badge'] }}
                                            </span>
                                            <span class="res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-[#F5F5F5] text-transparent border border-[#E5E5E5]">
                                                ✓
                                            </span>
                                        </div>

                                        <div class="flex items-baseline justify-between gap-1 mb-1">
                                            <h5 class="text-xs font-semibold text-[#1C1C1C]">{{ $pkg['name'] }}</h5>
                                            <span class="text-sm font-semibold text-[#1C1C1C]">{{ $pkg['price'] }}</span>
                                        </div>

                                        <p class="text-[11px] text-[#666666] leading-snug mb-2.5">
                                            {{ $pkg['desc'] }}
                                        </p>
                                    </div>

                                    <div class="pt-2 border-t border-[#F0F0F0] flex flex-wrap gap-1">
                                        @foreach($pkg['highlights'] as $hl)
                                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-[#F5F5F5] text-[#444444] font-medium border border-[#E5E5E5]">
                                                {{ $hl }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Selected Package Summary Bar (Muncul Setelah Kategori & Paket Dipilih) -->
                    <div id="res-package-summary-bar" class="hidden rounded-xl bg-[#242424] text-white px-4 py-3 flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs border border-white/10 shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-neutral-300 text-base">verified</span>
                            <span class="text-white/75">Paket Terpilih:</span>
                            <strong id="res-selected-package-summary" class="text-white font-semibold">
                                Belum dipilih
                            </strong>
                        </div>
                        <span id="res-selected-package-detail" class="text-[11px] text-white/70">
                            Pilih kategori &amp; paket dekorasi di atas
                        </span>
                    </div>
                </div>

                <!-- 8. Catatan Khusus (1 Kolom Penuh) -->
                <div>
                    <label for="res-notes" class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">
                        Catatan Khusus / Tulisan Lettering (Opsional)
                    </label>
                    <input type="text"
                           id="res-notes" 
                           name="notes" 
                           placeholder="Contoh: Tulisan lettering 'Happy Bridal Rara', meja dekat jendela..." 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs font-sans text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#242424] focus:ring-1 focus:ring-[#242424] transition-colors">
                </div>
            </form>
        </div>

        <!-- Submit Action - Sticky di Bawah pada Mobile, Tanpa Menutupi Konten Form -->
        <div class="shrink-0 pt-3 border-t border-[#E5E5E5] bg-[#FAFAFA] z-10">
            <button type="submit" 
                    form="reservation-form"
                    id="res-submit-btn"
                    class="w-full min-h-[46px] py-3.5 bg-[#242424] text-white text-xs font-semibold uppercase tracking-widest rounded-xl hover:bg-[#1C1C1C] active:bg-black transition-colors flex items-center justify-center space-x-2 shadow-md focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                <span>Lanjutkan Kirim via WhatsApp</span>
                <span class="material-symbols-outlined text-sm">send</span>
            </button>
            <p class="text-[10px] text-[#8A8A8A] text-center mt-2 leading-relaxed">
                *Pesan formulir beserta pilihan paket acara akan disusun otomatis dan dibuka di WhatsApp resmi Nucomu Cafe.
            </p>
        </div>

    </div>
</div>
