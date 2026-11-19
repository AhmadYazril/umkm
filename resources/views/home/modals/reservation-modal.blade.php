<!-- Reservation Modal Form -->
<div id="reservation-modal" 
     class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeReservationModal()"
     role="dialog"
     aria-modal="true"
     aria-labelledby="reservation-modal-title">
    
    <div id="reservation-modal-dialog"
         class="bg-[#121212] rounded-[24px] sm:rounded-[28px] max-w-2xl w-full p-5 sm:p-7 shadow-[var(--shadow)] border border-[var(--border)] relative max-h-[92vh] flex flex-col transform translate-y-6 scale-[0.98] transition-all duration-300 ease-out font-sans overflow-hidden"
         onclick="event.stopPropagation()">
        
        <!-- Close Button (X) -->
        <button type="button"
                id="res-close-btn"
                onclick="closeReservationModal()" 
                class="absolute top-4 right-4 sm:top-5 sm:right-5 w-9 h-9 rounded-full bg-white/10 border border-[var(--border)] text-[#F5F5F5] hover:bg-[#F5F2EE] hover:text-[#111111] transition-colors duration-200 flex items-center justify-center z-20 shadow-2xs focus:outline-none focus:ring-2 focus:ring-white/40"
                aria-label="Tutup Formulir Reservasi">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Modal Header -->
        <div class="border-b border-[var(--border-soft)] pb-4 mb-4 pr-10 shrink-0">
            <span class="block text-[10px] sm:text-[11px] font-semibold uppercase tracking-[0.2em] text-[#B5B5B5] mb-1.5">
                Formulir Reservasi &amp; Paket Acara
            </span>
            <h3 id="reservation-modal-title" class="font-sans text-2xl sm:text-3xl font-semibold tracking-tight text-[#F5F5F5]">
                Reservasi Nucomu Cafe
            </h3>
            <p class="text-xs text-[#B5B5B5] leading-relaxed mt-1">
                Lengkapi data kunjungan dan pilih paket dekorasi acara spesial Anda. Rincian reservasi akan otomatis dirangkum ke WhatsApp resmi Nucomu.
            </p>
        </div>

        <!-- Scrollable Form Container -->
        <div id="reservation-modal-body" class="overflow-y-auto flex-1 pr-1 -mr-1 pb-4 space-y-4">
            <!-- Inline Error Box -->
            <div id="res-error-box" class="hidden p-3.5 bg-red-950/60 border border-red-400/30 text-red-200 text-xs rounded-xl leading-relaxed">
                <!-- Injected via JavaScript -->
            </div>

            <!-- Success Feedback Toast -->
            <div id="res-success-toast" class="hidden p-3.5 bg-[#F5F2EE] border border-white/20 text-[#111111] text-xs rounded-xl text-center font-medium shadow-md">
                Pengajuan berhasil dirangkum. Membuka WhatsApp resmi Nucomu...
            </div>

            <!-- Reservation Form -->
            <form id="reservation-form" enctype="multipart/form-data" novalidate onsubmit="return handleReservationSubmit(event)" class="space-y-4">
                @php
                    $modalFields = $reservationFields ?? $fields ?? \App\Models\ReservationField::active()->get();
                @endphp

                <!-- Dynamic Form Builder Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4">
                    @forelse($modalFields as $field)
                        @if($field->type === 'photo')
                            <div class="col-span-full">
                                <label for="modal-field-{{ $field->field_key }}" class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">
                                    {{ $field->label }} @if($field->is_required)<span class="text-[#F5F5F5]">*</span>@endif
                                </label>
                                <div class="p-3.5 bg-[var(--surface)] border border-[var(--border)] rounded-xl space-y-2">
                                    <input type="file" 
                                           id="modal-field-{{ $field->field_key }}" 
                                           name="{{ $field->field_key }}" 
                                           data-label="{{ $field->label }}"
                                           accept=".jpg,.jpeg,.png,.webp"
                                           {{ $field->is_required ? 'required' : '' }}
                                           class="block w-full text-xs text-[#F5F5F5] file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#F5F2EE] file:text-[#111111] hover:file:bg-white cursor-pointer">
                                    <p class="text-[10px] text-[#B5B5B5]">
                                        <span class="font-semibold text-[#F5F5F5]">Panduan Ukuran:</span> Format JPG, PNG, atau WebP (Maksimal 2 MB).
                                    </p>
                                    <p class="text-[10px] text-[#B5B5B5] bg-white/[0.04] border border-[var(--border-soft)] rounded-lg p-2 leading-relaxed">
                                        💡 <strong>Catatan:</strong> Foto akan tersimpan di server kami &amp; link foto disertakan pada WhatsApp. Anda juga dapat melampirkan foto langsung di chat WhatsApp saat percakapan terbuka.
                                    </p>
                                </div>
                            </div>
                        @elseif($field->type === 'checkbox')
                            <div class="col-span-full">
                                <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">
                                    {{ $field->label }} @if($field->is_required)<span class="text-[#F5F5F5]">*</span>@endif
                                </label>
                                @if(!empty($field->options) && is_array($field->options))
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 bg-[var(--surface)] border border-[var(--border)] rounded-xl">
                                        @foreach($field->options as $optVal)
                                            <label class="flex items-center gap-2 p-2 bg-[#161616] rounded-lg border border-[var(--border-soft)] hover:border-white/30 cursor-pointer text-xs text-[#F5F5F5]">
                                                <input type="checkbox" 
                                                       name="{{ $field->field_key }}[]" 
                                                       value="{{ $optVal }}" 
                                                       data-label="{{ $field->label }}"
                                                       {{ $field->is_required ? 'required' : '' }}
                                                       class="w-4 h-4 rounded accent-[#F5F2EE] bg-[var(--field-bg)] border-[var(--field-border)] focus:ring-white/30">
                                                <span>{{ $optVal }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @elseif($field->type === 'textarea')
                            <div class="col-span-full">
                                <label for="modal-field-{{ $field->field_key }}" class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">
                                    {{ $field->label }} @if($field->is_required)<span class="text-[#F5F5F5]">*</span>@endif
                                </label>
                                <textarea id="modal-field-{{ $field->field_key }}" 
                                          name="{{ $field->field_key }}" 
                                          data-label="{{ $field->label }}"
                                          rows="2"
                                          placeholder="{{ $field->placeholder ?: 'Tuliskan ' . strtolower($field->label) . '...' }}" 
                                          {{ $field->is_required ? 'required' : '' }}
                                          class="w-full px-3.5 py-2 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs font-sans text-[#F5F5F5] placeholder-[#8F8F8F] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors"></textarea>
                            </div>
                        @elseif($field->type === 'date')
                            <div>
                                <label for="modal-field-{{ $field->field_key }}" class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">
                                    {{ $field->label }} @if($field->is_required)<span class="text-[#F5F5F5]">*</span>@endif
                                </label>
                                <input type="date" 
                                       id="modal-field-{{ $field->field_key }}" 
                                       name="{{ $field->field_key }}" 
                                       data-label="{{ $field->label }}"
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ date('Y-m-d') }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs font-sans text-[#F5F5F5] [color-scheme:dark] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors">
                            </div>
                        @elseif($field->type === 'time')
                            <div>
                                <label for="modal-field-{{ $field->field_key }}" class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">
                                    {{ $field->label }} @if($field->is_required)<span class="text-[#F5F5F5]">*</span>@endif
                                </label>
                                <input type="time" 
                                       id="modal-field-{{ $field->field_key }}" 
                                       name="{{ $field->field_key }}" 
                                       data-label="{{ $field->label }}"
                                       value="15:00"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs font-sans text-[#F5F5F5] [color-scheme:dark] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors">
                            </div>
                        @else
                            {{-- text, tel, number --}}
                            <div>
                                <label for="modal-field-{{ $field->field_key }}" class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">
                                    {{ $field->label }} @if($field->is_required)<span class="text-[#F5F5F5]">*</span>@endif
                                </label>
                                <input type="{{ $field->type === 'number' ? 'number' : ($field->type === 'tel' ? 'tel' : 'text') }}" 
                                       id="modal-field-{{ $field->field_key }}" 
                                       name="{{ $field->field_key }}" 
                                       data-label="{{ $field->label }}"
                                       placeholder="{{ $field->placeholder ?: ($field->type === 'number' ? 'Contoh: 4' : ($field->type === 'tel' ? '08xxxxxxxxxx' : 'Masukkan ' . strtolower($field->label))) }}" 
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs font-sans text-[#F5F5F5] placeholder-[#8F8F8F] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors">
                            </div>
                        @endif
                    @empty
                        <div class="col-span-full text-center py-4 text-xs text-[#B5B5B5]">
                            Belum ada kolom formulir yang diaktifkan oleh admin.
                        </div>
                    @endforelse
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

                <div class="nu-glass-card rounded-2xl bg-[var(--surface)] border border-[var(--border)] p-4 sm:p-5 space-y-4 shadow-2xs">
                    <!-- STEP 1: PILIH KATEGORI PAKET ACARA TERLEBIH DAHULU -->
                    <div>
                        <div class="mb-3">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-[#F5F5F5]">
                                1. Pilih Kategori Paket Acara Terlebih Dahulu <span class="text-[#F5F5F5]">*</span>
                            </label>
                            <p class="text-[11px] text-[#B5B5B5] mt-0.5">
                                Pilih acara <strong>Bridal</strong> atau <strong>Birthday</strong> di bawah ini untuk menampilkan daftar pilihan paket dekorasinya:
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Card Pilihan 1: Bridal -->
                            <button type="button"
                                    id="btn-choose-bridal"
                                    onclick="chooseReservationEventCategory('bridal')"
                                    data-section-tab="bridal"
                                    class="res-section-tab-btn group text-left rounded-2xl p-4 border border-[var(--border)] hover:border-white/30 bg-[var(--surface)] transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-white/40">
                                <div class="flex items-center gap-3">
                                    <div id="icon-box-bridal" class="w-11 h-11 rounded-xl bg-white/10 border border-white/15 text-[#F5F5F5] flex items-center justify-center shrink-0 transition-colors">
                                        <span class="material-symbols-outlined text-xl">favorite</span>
                                    </div>
                                    <div>
                                        <span id="label-cat-bridal" class="text-[10px] font-semibold uppercase tracking-widest text-[#B5B5B5] block transition-colors">Kategori 01</span>
                                        <span id="title-cat-bridal" class="font-sans text-base sm:text-lg font-semibold text-[#F5F5F5] block leading-tight transition-colors">Paket Acara Bridal</span>
                                        <span id="sub-cat-bridal" class="text-[11px] text-[#B5B5B5] block mt-0.5 transition-colors">Bridal Shower &amp; Intimate Party</span>
                                    </div>
                                </div>
                                <span id="check-cat-bridal" class="w-6 h-6 rounded-full border border-[var(--border)] bg-transparent flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0">
                                    ✓
                                </span>
                            </button>

                            <!-- Card Pilihan 2: Birthday -->
                            <button type="button"
                                    id="btn-choose-birthday"
                                    onclick="chooseReservationEventCategory('birthday')"
                                    data-section-tab="birthday"
                                    class="res-section-tab-btn group text-left rounded-2xl p-4 border border-[var(--border)] hover:border-white/30 bg-[var(--surface)] transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-white/40">
                                <div class="flex items-center gap-3">
                                    <div id="icon-box-birthday" class="w-11 h-11 rounded-xl bg-white/10 border border-white/15 text-[#F5F5F5] flex items-center justify-center shrink-0 transition-colors">
                                        <span class="material-symbols-outlined text-xl">cake</span>
                                    </div>
                                    <div>
                                        <span id="label-cat-birthday" class="text-[10px] font-semibold uppercase tracking-widest text-[#B5B5B5] block transition-colors">Kategori 02</span>
                                        <span id="title-cat-birthday" class="font-sans text-base sm:text-lg font-semibold text-[#F5F5F5] block leading-tight transition-colors">Paket Acara Birthday</span>
                                        <span id="sub-cat-birthday" class="text-[11px] text-[#B5B5B5] block mt-0.5 transition-colors">Birthday Celebration &amp; Treats</span>
                                    </div>
                                </div>
                                <span id="check-cat-birthday" class="w-6 h-6 rounded-full border border-[var(--border)] bg-transparent flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0">
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
                    <div id="res-empty-category-prompt" class="rounded-xl border border-dashed border-[var(--border)] bg-white/[0.04] px-4 py-4 text-center">
                        <span class="material-symbols-outlined text-[#F5F2EE] text-xl mb-1 block">touch_app</span>
                        <p class="text-xs font-semibold text-[#F5F5F5]">
                            Silakan klik salah satu kategori di atas (<strong>Paket Acara Bridal</strong> atau <strong>Paket Acara Birthday</strong>)
                        </p>
                        <p class="text-[11px] text-[#B5B5B5] mt-0.5">
                            Pilihan <strong>Paket Dekorasi A (35K)</strong>, <strong>Paket Dekorasi B (60K)</strong>, dan <strong>Paket Dekorasi C (100K)</strong> akan muncul setelah kategori dipilih.
                        </p>
                    </div>

                    <!-- STEP 2A: PILIHAN PAKET DEKORASI UNTUK BRIDAL (MUNCUL SETELAH PILIH BRIDAL) -->
                    <div id="res-section-bridal"
                         data-package-section="bridal"
                         class="res-package-section hidden rounded-2xl border border-[var(--border)] bg-white/[0.03] p-4 transition-all duration-300">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-white/10 border border-[var(--border)] text-[#F5F5F5] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">favorite</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-semibold uppercase tracking-widest text-[#B5B5B5]">2. Pilih Paket Dekorasi Bridal</span>
                                        <span id="badge-active-bridal" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5F2EE] text-[#111111]">
                                            ✓ Bridal Aktif
                                        </span>
                                    </div>
                                    <h4 class="font-sans text-base sm:text-lg font-semibold text-[#F5F5F5] leading-tight mt-0.5">
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
                                     class="res-pkg-card cursor-pointer rounded-xl p-3.5 border border-[var(--border)] bg-[var(--surface)] hover:border-white/30 transition-all duration-200 flex flex-col justify-between relative shadow-2xs">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1.5">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/10 text-[#D4D4D4] border border-[var(--border)]">
                                                {{ $pkg['badge'] }}
                                            </span>
                                            <span class="res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-white/10 text-transparent border border-[var(--border)]">
                                                ✓
                                            </span>
                                        </div>

                                        <div class="flex items-baseline justify-between gap-1 mb-1">
                                            <h5 class="text-xs font-semibold text-[#F5F5F5]">{{ $pkg['name'] }}</h5>
                                            <span class="text-sm font-semibold text-[#F5F5F5] tabular-nums">{{ $pkg['price'] }}</span>
                                        </div>

                                        <p class="text-[11px] text-[#B5B5B5] leading-snug mb-2.5">
                                            {{ $pkg['desc'] }}
                                        </p>
                                    </div>

                                    <div class="pt-2 border-t border-[var(--border-soft)] flex flex-wrap gap-1">
                                        @foreach($pkg['highlights'] as $hl)
                                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-white/5 text-[#D4D4D4] font-medium border border-[var(--border-soft)]">
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
                         class="res-package-section hidden rounded-2xl border border-[var(--border)] bg-white/[0.03] p-4 transition-all duration-300">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-white/10 border border-[var(--border)] text-[#F5F5F5] flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">cake</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-semibold uppercase tracking-widest text-[#B5B5B5]">2. Pilih Paket Dekorasi Birthday</span>
                                        <span id="badge-active-birthday" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5F2EE] text-[#111111]">
                                            ✓ Birthday Aktif
                                        </span>
                                    </div>
                                    <h4 class="font-sans text-base sm:text-lg font-semibold text-[#F5F5F5] leading-tight mt-0.5">
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
                                     class="res-pkg-card cursor-pointer rounded-xl p-3.5 border border-[var(--border)] bg-[var(--surface)] hover:border-white/30 transition-all duration-200 flex flex-col justify-between relative shadow-2xs">
                                    <div>
                                        <div class="flex items-center justify-between gap-1 mb-1.5">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/10 text-[#D4D4D4] border border-[var(--border)]">
                                                {{ $pkg['badge'] }}
                                            </span>
                                            <span class="res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-white/10 text-transparent border border-[var(--border)]">
                                                ✓
                                            </span>
                                        </div>

                                        <div class="flex items-baseline justify-between gap-1 mb-1">
                                            <h5 class="text-xs font-semibold text-[#F5F5F5]">{{ $pkg['name'] }}</h5>
                                            <span class="text-sm font-semibold text-[#F5F5F5] tabular-nums">{{ $pkg['price'] }}</span>
                                        </div>

                                        <p class="text-[11px] text-[#B5B5B5] leading-snug mb-2.5">
                                            {{ $pkg['desc'] }}
                                        </p>
                                    </div>

                                    <div class="pt-2 border-t border-[var(--border-soft)] flex flex-wrap gap-1">
                                        @foreach($pkg['highlights'] as $hl)
                                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-white/5 text-[#D4D4D4] font-medium border border-[var(--border-soft)]">
                                                {{ $hl }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Selected Package Summary Bar (Muncul Setelah Kategori & Paket Dipilih) -->
                    <div id="res-package-summary-bar" class="hidden rounded-xl bg-white/[0.06] text-[#F5F5F5] px-4 py-3 flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs border border-[var(--border)] shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#F5F2EE] text-base">verified</span>
                            <span class="text-[#B5B5B5]">Paket Terpilih:</span>
                            <strong id="res-selected-package-summary" class="text-[#F5F5F5] font-semibold">
                                Belum dipilih
                            </strong>
                        </div>
                        <span id="res-selected-package-detail" class="text-[11px] text-[#B5B5B5]">
                            Pilih kategori &amp; paket dekorasi di atas
                        </span>
                    </div>
                </div>

                @if($modalFields->where('field_key', 'notes')->isEmpty())
                    <!-- Catatan Khusus Tambahan (Jika belum ada di form builder) -->
                    <div>
                        <label for="res-notes" class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">
                            Catatan Khusus / Tulisan Lettering (Opsional)
                        </label>
                        <input type="text"
                               id="res-notes" 
                               name="notes" 
                               placeholder="Contoh: Tulisan lettering 'Happy Bridal Rara', meja dekat jendela..." 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs font-sans text-[#F5F5F5] placeholder-[#8F8F8F] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors">
                    </div>
                @endif
            </form>
        </div>

        <!-- Submit Action - Sticky di Bawah pada Mobile, Tanpa Menutupi Konten Form -->
        <div class="shrink-0 pt-3 border-t border-[var(--border-soft)] bg-[#121212] z-10">
            <button type="submit" 
                    form="reservation-form"
                    id="res-submit-btn"
                    class="w-full min-h-[46px] py-3.5 bg-[#F5F2EE] text-[#111111] text-xs font-semibold uppercase tracking-widest rounded-xl hover:bg-white active:bg-white transition-colors flex items-center justify-center space-x-2 shadow-md focus:outline-none focus:ring-2 focus:ring-white/40 focus:ring-offset-2 focus:ring-offset-[#121212]">
                <span>Lanjutkan Kirim via WhatsApp</span>
                <span class="material-symbols-outlined text-sm">send</span>
            </button>
            <p class="text-[10px] text-[#8F8F8F] text-center mt-2 leading-relaxed">
                *Pesan formulir beserta pilihan paket acara akan disusun otomatis dan dibuka di WhatsApp resmi Nucomu Cafe.
            </p>
        </div>

    </div>
</div>
