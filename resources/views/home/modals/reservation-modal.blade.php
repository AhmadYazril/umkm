<!-- Reservation Modal Form -->
<div id="reservation-modal" 
     class="fixed inset-0 z-50 bg-ink-black/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeReservationModal()">
    
    <div class="bg-warm-ivory rounded-3xl max-w-2xl w-full p-5 sm:p-8 shadow-2xl border border-soft-beige relative max-h-[92vh] overflow-y-auto"
         onclick="event.stopPropagation()">
        
        <!-- Close Button -->
        <button type="button"
                onclick="closeReservationModal()" 
                class="absolute top-5 right-5 w-9 h-9 rounded-full bg-light-gray/70 hover:bg-ink-black hover:text-warm-ivory text-charcoal flex items-center justify-center transition-colors z-10"
                aria-label="Tutup Formulir Reservasi">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Modal Header -->
        <div class="border-b border-soft-beige pb-4 mb-5 pr-10">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#E06D24]/10 border border-[#E06D24]/20 text-[#E06D24] text-[10px] font-bold uppercase tracking-widest mb-1.5">
                <span class="material-symbols-outlined text-xs">celebration</span>
                <span>Formulir Reservasi &amp; Paket Acara</span>
            </div>
            <h3 class="font-editorial text-2xl sm:text-3xl font-bold text-ink-black">
                Reservasi Nucomu Cafe
            </h3>
            <p class="font-body text-xs text-charcoal/70 mt-1">
                Lengkapi data kunjungan dan pilih paket dekorasi acara spesial Anda. Rincian reservasi akan otomatis dirangkum ke WhatsApp resmi Nucomu.
            </p>
        </div>

        <!-- Inline Error Box -->
        <div id="res-error-box" class="hidden mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl leading-relaxed">
            <!-- Injected via JavaScript -->
        </div>

        <!-- Success Feedback Toast -->
        <div id="res-success-toast" class="hidden mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl text-center font-medium">
            Pengajuan berhasil dirangkum. Membuka WhatsApp...
        </div>

        <!-- Reservation Form -->
        <form id="reservation-form" onsubmit="return handleReservationSubmit(event)" class="space-y-5">
            <!-- 1. Nama Pemesan & 2. Nomor WhatsApp -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="res-name" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Nama Pemesan <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           id="res-name" 
                           name="fullName" 
                           required 
                           placeholder="Contoh: Raditya Danendra" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
                <div>
                    <label for="res-phone" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Nomor WhatsApp Aktif <span class="text-red-600">*</span>
                    </label>
                    <input type="tel" 
                           id="res-phone" 
                           name="phone" 
                           required 
                           placeholder="081234567890" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
            </div>

            <!-- 3. Tanggal, 4. Waktu & 5. Jumlah Tamu -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="res-date" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Tanggal Acara <span class="text-red-600">*</span>
                    </label>
                    <input type="date" 
                           id="res-date" 
                           name="date" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
                <div>
                    <label for="res-time" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Waktu Kedatangan <span class="text-red-600">*</span>
                    </label>
                    <input type="time" 
                           id="res-time" 
                           name="time" 
                           required 
                           value="15:00"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
                <div>
                    <label for="res-guests" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Jumlah Tamu <span class="text-red-600">*</span>
                    </label>
                    <select id="res-guests" 
                            name="guestCount" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                        <option value="1 - 2 Orang">1 – 2 Orang (Meja Intim)</option>
                        <option value="3 - 5 Orang" selected>3 – 5 Orang (Meja Reguler)</option>
                        <option value="6 - 10 Orang">6 – 10 Orang (Meja Komunal)</option>
                        <option value="10+ Orang">10+ Orang (Group Gathering)</option>
                        <option value="Acara Privat (Seluruh Ruang)">Acara Privat (Seluruh Ruang)</option>
                    </select>
                </div>
            </div>

            <!-- 6. SECTION PAKET ACARA: BRIDAL & BIRTHDAY -->
            @php
                $packageOptions = [
                    [
                        'code' => 'A',
                        'name' => 'Paket Dekorasi A',
                        'price' => '35K',
                        'price_full' => 'Rp 35.000',
                        'badge' => 'Essential',
                        'badge_style' => 'bg-stone-100 text-stone-700 border-stone-200',
                        'desc' => 'Dekorasi meja, lilin, dipinjami korek, lettering ucapan',
                        'highlights' => ['Dekorasi Meja', 'Lilin & Korek', 'Lettering Ucapan'],
                    ],
                    [
                        'code' => 'B',
                        'name' => 'Paket Dekorasi B',
                        'price' => '60K',
                        'price_full' => 'Rp 60.000',
                        'badge' => 'Populer',
                        'badge_style' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'desc' => 'Paket 35K + cheesecake slice 1',
                        'highlights' => ['Semua Paket 35K', '+ 1 Cheesecake Slice'],
                    ],
                    [
                        'code' => 'C',
                        'name' => 'Paket Dekorasi C',
                        'price' => '100K',
                        'price_full' => 'Rp 100.000',
                        'badge' => 'Best Value',
                        'badge_style' => 'bg-[#E06D24]/10 text-[#E06D24] border-[#E06D24]/25',
                        'desc' => 'Paket 35K + 1 whole cheesecake diameter 10cm + 1 minuman nucomu coffee',
                        'highlights' => ['Semua Paket 35K', '+ 1 Whole Cheesecake Ø 10cm', '+ 1 Minuman Nucomu Coffee'],
                    ],
                ];
            @endphp

            <div class="rounded-2xl bg-pure-white border border-soft-beige p-4 sm:p-5 shadow-2xs space-y-4">
                <!-- STEP 1: PILIH KATEGORI PAKET ACARA TERLEBIH DAHULU -->
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink-black">
                                1. Pilih Kategori Paket Acara Terlebih Dahulu <span class="text-red-600">*</span>
                            </label>
                            <p class="text-[11px] text-charcoal/70 mt-0.5">
                                Pilih acara <strong>Bridal</strong> atau <strong>Birthday</strong> di bawah ini untuk memunculkan daftar pilihan paket dekorasinya:
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Card Pilihan 1: Bridal -->
                        <button type="button"
                                id="btn-choose-bridal"
                                onclick="chooseReservationEventCategory('bridal')"
                                data-section-tab="bridal"
                                class="res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-stone-200/90 hover:border-rose-400 bg-gradient-to-br from-[#FFFDFB] to-rose-50/40 transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <span class="material-symbols-outlined text-xl">favorite</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-rose-600 block">Kategori 01</span>
                                    <span class="font-editorial text-lg font-bold text-ink-black block leading-tight">Paket Acara Bridal</span>
                                    <span class="text-[11px] text-charcoal/70 block mt-0.5">Bridal Shower &amp; Intimate Party</span>
                                </div>
                            </div>
                            <span id="check-cat-bridal" class="w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0">
                                ✓
                            </span>
                        </button>

                        <!-- Card Pilihan 2: Birthday -->
                        <button type="button"
                                id="btn-choose-birthday"
                                onclick="chooseReservationEventCategory('birthday')"
                                data-section-tab="birthday"
                                class="res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-stone-200/90 hover:border-amber-400 bg-gradient-to-br from-[#FFFDFB] to-amber-50/40 transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <span class="material-symbols-outlined text-xl">cake</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700 block">Kategori 02</span>
                                    <span class="font-editorial text-lg font-bold text-ink-black block leading-tight">Paket Acara Birthday</span>
                                    <span class="text-[11px] text-charcoal/70 block mt-0.5">Birthday Celebration &amp; Treats</span>
                                </div>
                            </div>
                            <span id="check-cat-birthday" class="w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0">
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
                <div id="res-empty-category-prompt" class="rounded-xl border border-dashed border-stone-300 bg-warm-ivory/60 px-4 py-4 text-center">
                    <span class="material-symbols-outlined text-[#E06D24] text-xl mb-1 block">touch_app</span>
                    <p class="text-xs font-semibold text-ink-black">
                        Silakan klik salah satu kategori di atas (<strong>Paket Acara Bridal</strong> atau <strong>Paket Acara Birthday</strong>)
                    </p>
                    <p class="text-[11px] text-charcoal/70 mt-0.5">
                        Pilihan <strong>Paket Dekorasi A (35K)</strong>, <strong>Paket Dekorasi B (60K)</strong>, dan <strong>Paket Dekorasi C (100K)</strong> akan muncul setelah kategori dipilih.
                    </p>
                </div>

                <!-- STEP 2A: PILIHAN PAKET DEKORASI UNTUK BRIDAL (MUNCUL SETELAH PILIH BRIDAL) -->
                <div id="res-section-bridal"
                     data-package-section="bridal"
                     class="res-package-section hidden rounded-2xl border-2 border-[#E06D24]/30 bg-gradient-to-br from-[#FFFDFB] to-[#FAF4EE] p-4 transition-all duration-200">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-lg">favorite</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-rose-600">2. Pilih Paket Dekorasi Bridal</span>
                                    <span id="badge-active-bridal" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E06D24] text-white">
                                        ✓ Bridal Aktif
                                    </span>
                                </div>
                                <h4 class="font-editorial text-lg sm:text-xl font-bold text-ink-black leading-tight">
                                    Pilihan Paket Acara Bridal (Bridal Shower &amp; Party)
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        @foreach($packageOptions as $idx => $pkg)
                            <div onclick="selectReservationPackage('Bridal', '{{ $pkg['name'] }}', '{{ $pkg['price'] }}', '{{ $pkg['desc'] }}', this)"
                                 data-section="Bridal"
                                 data-pkg-code="{{ $pkg['code'] }}"
                                 class="res-pkg-card cursor-pointer rounded-xl p-3.5 border border-stone-200/90 bg-white/80 hover:border-[#E06D24]/50 hover:bg-white transition-all duration-200 flex flex-col justify-between relative">
                                <div>
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $pkg['badge_style'] }}">
                                            {{ $pkg['badge'] }}
                                        </span>
                                        <span class="res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-stone-100 text-transparent border border-stone-300">
                                            ✓
                                        </span>
                                    </div>

                                    <div class="flex items-baseline justify-between gap-1 mb-1">
                                        <h5 class="text-xs font-bold text-ink-black">{{ $pkg['name'] }}</h5>
                                        <span class="text-sm font-extrabold text-[#E06D24]">{{ $pkg['price'] }}</span>
                                    </div>

                                    <p class="text-[11px] text-charcoal/80 leading-snug mb-2.5">
                                        {{ $pkg['desc'] }}
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-stone-100 flex flex-wrap gap-1">
                                    @foreach($pkg['highlights'] as $hl)
                                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-warm-ivory text-espresso-brown font-medium">
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
                     class="res-package-section hidden rounded-2xl border-2 border-[#E06D24]/30 bg-gradient-to-br from-[#FFFDFB] to-[#FAF4EE]/60 p-4 transition-all duration-200">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-lg">cake</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700">2. Pilih Paket Dekorasi Birthday</span>
                                    <span id="badge-active-birthday" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#E06D24] text-white">
                                        ✓ Birthday Aktif
                                    </span>
                                </div>
                                <h4 class="font-editorial text-lg sm:text-xl font-bold text-ink-black leading-tight">
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
                                 class="res-pkg-card cursor-pointer rounded-xl p-3.5 border border-stone-200/90 bg-white/80 hover:border-[#E06D24]/50 hover:bg-white transition-all duration-200 flex flex-col justify-between relative">
                                <div>
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $pkg['badge_style'] }}">
                                            {{ $pkg['badge'] }}
                                        </span>
                                        <span class="res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-stone-100 text-transparent border border-stone-300">
                                            ✓
                                        </span>
                                    </div>

                                    <div class="flex items-baseline justify-between gap-1 mb-1">
                                        <h5 class="text-xs font-bold text-ink-black">{{ $pkg['name'] }}</h5>
                                        <span class="text-sm font-extrabold text-[#E06D24]">{{ $pkg['price'] }}</span>
                                    </div>

                                    <p class="text-[11px] text-charcoal/80 leading-snug mb-2.5">
                                        {{ $pkg['desc'] }}
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-stone-100 flex flex-wrap gap-1">
                                    @foreach($pkg['highlights'] as $hl)
                                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-warm-ivory text-espresso-brown font-medium">
                                            {{ $hl }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Selected Package Summary Bar (Muncul Setelah Kategori & Paket Dipilih) -->
                <div id="res-package-summary-bar" class="hidden rounded-xl bg-ink-black text-warm-ivory px-4 py-3 flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400 text-base">verified</span>
                        <span class="text-warm-ivory/75">Paket Terpilih:</span>
                        <strong id="res-selected-package-summary" class="text-amber-300 font-bold">
                            Belum dipilih
                        </strong>
                    </div>
                    <span id="res-selected-package-detail" class="text-[11px] text-warm-ivory/70 italic">
                        Pilih kategori &amp; paket dekorasi di atas
                    </span>
                </div>
            </div>

            <!-- 7. Pilihan Area & 8. Catatan Khusus -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="res-area" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Pilihan Area <span class="text-red-600">*</span>
                    </label>
                    <select id="res-area" 
                            name="area" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                        <option value="Indoor (AC & Working Space)">Indoor (AC &amp; Working Space)</option>
                        <option value="Outdoor (Smoking Area & Garden)">Outdoor (Smoking Area &amp; Garden)</option>
                        <option value="Fleksibel (Sesuai Ketersediaan)">Fleksibel (Sesuai Ketersediaan)</option>
                    </select>
                </div>

                <div>
                    <label for="res-notes" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Catatan Khusus / Tulisan Lettering (Opsional)
                    </label>
                    <input type="text"
                           id="res-notes" 
                           name="notes" 
                           placeholder="Contoh: Tulisan lettering 'Happy Bridal Rara', meja dekat jendela..." 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
            </div>

            <!-- Submit Action -->
            <div class="pt-1">
                <button type="submit" 
                        class="w-full py-3.5 bg-ink-black text-warm-ivory text-xs font-semibold uppercase tracking-widest rounded-xl hover:bg-neutral-800 transition-colors flex items-center justify-center space-x-2 shadow-lg">
                    <span>Lanjutkan Kirim via WhatsApp</span>
                    <span class="material-symbols-outlined text-sm">send</span>
                </button>
                <p class="text-[10px] text-medium-gray text-center mt-2 leading-relaxed">
                    *Pesan formulir beserta pilihan paket acara akan disusun otomatis dan dibuka di WhatsApp resmi Nucomu Cafe.
                </p>
            </div>
        </form>

    </div>
</div>
