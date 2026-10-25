@extends('layouts.app')
@section('title', 'Reservasi Meja & Paket Acara — Nucomu Cafe')

@section('content')
<section class="py-12 sm:py-16 bg-warm-ivory min-h-screen text-charcoal">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#E06D24]/10 border border-[#E06D24]/20 text-[#E06D24] text-xs font-bold uppercase tracking-widest mb-2">
                <span class="material-symbols-outlined text-sm">celebration</span>
                <span>Formulir Kunjungan &amp; Paket Acara</span>
            </div>
            <h1 class="font-editorial text-3xl sm:text-5xl font-bold text-ink-black leading-tight mb-2">
                Reservasi Meja &amp; Paket Acara
            </h1>
            <p class="text-charcoal/75 text-xs sm:text-sm">
                Pilih paket dekorasi untuk momen spesial <strong>Bridal</strong> atau <strong>Birthday</strong> di Nucomu Cafe Tulungagung.
            </p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl mb-8 text-center text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 text-xs space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        @php
            $packageOptions = [
                [
                    'code' => 'A',
                    'name' => 'Paket Dekorasi A',
                    'price' => '35K',
                    'badge' => 'Essential',
                    'badge_style' => 'bg-stone-100 text-stone-700 border-stone-200',
                    'desc' => 'Dekorasi meja, lilin, dipinjami korek, lettering ucapan',
                    'highlights' => ['Dekorasi Meja', 'Lilin & Korek', 'Lettering Ucapan'],
                ],
                [
                    'code' => 'B',
                    'name' => 'Paket Dekorasi B',
                    'price' => '60K',
                    'badge' => 'Populer',
                    'badge_style' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'desc' => 'Paket 35K + cheesecake slice 1',
                    'highlights' => ['Semua Paket 35K', '+ 1 Cheesecake Slice'],
                ],
                [
                    'code' => 'C',
                    'name' => 'Paket Dekorasi C',
                    'price' => '100K',
                    'badge' => 'Best Value',
                    'badge_style' => 'bg-[#E06D24]/10 text-[#E06D24] border-[#E06D24]/25',
                    'desc' => 'Paket 35K + 1 whole cheesecake diameter 10cm + 1 minuman nucomu coffee',
                    'highlights' => ['Semua Paket 35K', '+ 1 Whole Cheesecake Ø 10cm', '+ 1 Minuman Nucomu Coffee'],
                ],
            ];
        @endphp

        <div class="bg-pure-white border border-soft-beige rounded-3xl p-6 sm:p-10 shadow-xl">
            <form action="{{ route('reservation.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="page-fullName" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                            Nama Lengkap <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="fullName" id="page-fullName" required placeholder="Masukkan nama Anda"
                            class="w-full bg-warm-ivory/60 border border-soft-beige rounded-xl px-4 py-3 text-sm text-ink-black focus:outline-none focus:border-ink-black transition-colors">
                    </div>

                    <div>
                        <label for="page-phone" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                            Nomor WhatsApp <span class="text-red-600">*</span>
                        </label>
                        <input type="tel" name="phone" id="page-phone" required placeholder="08xxxxxxxxxx"
                            class="w-full bg-warm-ivory/60 border border-soft-beige rounded-xl px-4 py-3 text-sm text-ink-black focus:outline-none focus:border-ink-black transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="page-date" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                            Tanggal Reservasi <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="date" id="page-date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                            class="w-full bg-warm-ivory/60 border border-soft-beige rounded-xl px-4 py-3 text-sm text-ink-black focus:outline-none focus:border-ink-black transition-colors">
                    </div>

                    <div>
                        <label for="page-time" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                            Jam Kedatangan <span class="text-red-600">*</span>
                        </label>
                        <input type="time" name="time" id="page-time" required value="15:00"
                            class="w-full bg-warm-ivory/60 border border-soft-beige rounded-xl px-4 py-3 text-sm text-ink-black focus:outline-none focus:border-ink-black transition-colors">
                    </div>

                    <div>
                        <label for="page-guestCount" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                            Jumlah Tamu <span class="text-red-600">*</span>
                        </label>
                        <select name="guestCount" id="page-guestCount" required
                            class="w-full bg-warm-ivory/60 border border-soft-beige rounded-xl px-4 py-3 text-sm text-ink-black focus:outline-none focus:border-ink-black transition-colors">
                            <option value="1 - 2 Orang">1 – 2 Orang (Meja Intim)</option>
                            <option value="3 - 5 Orang" selected>3 – 5 Orang (Meja Reguler)</option>
                            <option value="6 - 10 Orang">6 – 10 Orang (Meja Komunal)</option>
                            <option value="10+ Orang">10+ Orang (Group Gathering)</option>
                        </select>
                    </div>
                </div>

                <!-- PAKET ACARA: BRIDAL & BIRTHDAY (2-STEP SELECTION) -->
                <div class="rounded-2xl bg-warm-ivory/60 border border-soft-beige p-5 space-y-5">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-ink-black">
                            1. Pilih Kategori Paket Acara Terlebih Dahulu <span class="text-red-600">*</span>
                        </h3>
                        <p class="text-xs text-charcoal/70 mt-0.5">
                            Pilih <strong>Paket Acara Bridal</strong> atau <strong>Paket Acara Birthday</strong> di bawah ini untuk menampilkan pilihan paket dekorasinya:
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <button type="button"
                                id="page-btn-cat-bridal"
                                onclick="choosePageEventCategory('bridal')"
                                class="text-left rounded-2xl p-4 border-2 border-stone-200 hover:border-rose-400 bg-white transition-all flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-xl">favorite</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-rose-600 block">Kategori 01</span>
                                    <span class="font-editorial text-lg font-bold text-ink-black block">Paket Acara Bridal</span>
                                    <span class="text-[11px] text-charcoal/70 block">Bridal Shower &amp; Party</span>
                                </div>
                            </div>
                            <span id="page-check-cat-bridal" class="w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent shrink-0">✓</span>
                        </button>

                        <button type="button"
                                id="page-btn-cat-birthday"
                                onclick="choosePageEventCategory('birthday')"
                                class="text-left rounded-2xl p-4 border-2 border-stone-200 hover:border-amber-400 bg-white transition-all flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-xl">cake</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700 block">Kategori 02</span>
                                    <span class="font-editorial text-lg font-bold text-ink-black block">Paket Acara Birthday</span>
                                    <span class="text-[11px] text-charcoal/70 block">Birthday Celebration</span>
                                </div>
                            </div>
                            <span id="page-check-cat-birthday" class="w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent shrink-0">✓</span>
                        </button>
                    </div>

                    <input type="hidden" id="page-serviceType" name="serviceType" value="">
                    <input type="hidden" id="page-eventSection" name="eventSection" value="">
                    <input type="hidden" id="page-eventPackage" name="eventPackage" value="">

                    <div id="page-empty-cat-prompt" class="rounded-xl border border-dashed border-stone-300 bg-white/80 px-4 py-4 text-center">
                        <span class="material-symbols-outlined text-[#E06D24] text-xl mb-1 block">touch_app</span>
                        <p class="text-xs font-semibold text-ink-black">
                            Silakan pilih kategori acara di atas (<strong>Bridal</strong> atau <strong>Birthday</strong>) terlebih dahulu
                        </p>
                        <p class="text-[11px] text-charcoal/70 mt-0.5">
                            Pilihan Paket Dekorasi A (35K), B (60K), dan C (100K) akan muncul setelah kategori dipilih.
                        </p>
                    </div>

                    <!-- SECTION 1: BRIDAL -->
                    <div id="page-sec-bridal" class="hidden rounded-2xl bg-white border-2 border-[#E06D24]/30 p-4 sm:p-5">
                        <div class="flex items-center gap-2.5 mb-3.5">
                            <div class="w-9 h-9 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg">favorite</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-rose-600 block">2. Pilih Paket Dekorasi Bridal</span>
                                <h4 class="font-editorial text-xl font-bold text-ink-black">Pilihan Paket Acara Bridal</h4>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($packageOptions as $idx => $pkg)
                                <div onclick="selectPagePackage('Bridal', '{{ $pkg['name'] }}', '{{ $pkg['price'] }}', '{{ $pkg['desc'] }}', this)"
                                     data-page-sec="Bridal"
                                     data-page-code="{{ $pkg['code'] }}"
                                     class="page-pkg-card cursor-pointer rounded-xl p-4 border border-stone-200 bg-white hover:border-[#E06D24]/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $pkg['badge_style'] }}">{{ $pkg['badge'] }}</span>
                                            <span class="text-sm font-extrabold text-[#E06D24]">{{ $pkg['price'] }}</span>
                                        </div>
                                        <h5 class="text-xs font-bold text-ink-black mb-1">{{ $pkg['name'] }}</h5>
                                        <p class="text-[11px] text-charcoal/80 leading-snug mb-3">{{ $pkg['desc'] }}</p>
                                    </div>
                                    <div class="pt-2 border-t border-stone-100 flex flex-wrap gap-1">
                                        @foreach($pkg['highlights'] as $hl)
                                            <span class="text-[10px] px-2 py-0.5 rounded bg-warm-ivory text-espresso-brown font-medium">{{ $hl }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SECTION 2: BIRTHDAY -->
                    <div id="page-sec-birthday" class="hidden rounded-2xl bg-white border-2 border-[#E06D24]/30 p-4 sm:p-5">
                        <div class="flex items-center gap-2.5 mb-3.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg">cake</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700 block">2. Pilih Paket Dekorasi Birthday</span>
                                <h4 class="font-editorial text-xl font-bold text-ink-black">Pilihan Paket Acara Birthday</h4>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($packageOptions as $pkg)
                                <div onclick="selectPagePackage('Birthday', '{{ $pkg['name'] }}', '{{ $pkg['price'] }}', '{{ $pkg['desc'] }}', this)"
                                     data-page-sec="Birthday"
                                     data-page-code="{{ $pkg['code'] }}"
                                     class="page-pkg-card cursor-pointer rounded-xl p-4 border border-stone-200 bg-white hover:border-[#E06D24]/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $pkg['badge_style'] }}">{{ $pkg['badge'] }}</span>
                                            <span class="text-sm font-extrabold text-[#E06D24]">{{ $pkg['price'] }}</span>
                                        </div>
                                        <h5 class="text-xs font-bold text-ink-black mb-1">{{ $pkg['name'] }}</h5>
                                        <p class="text-[11px] text-charcoal/80 leading-snug mb-3">{{ $pkg['desc'] }}</p>
                                    </div>
                                    <div class="pt-2 border-t border-stone-100 flex flex-wrap gap-1">
                                        @foreach($pkg['highlights'] as $hl)
                                            <span class="text-[10px] px-2 py-0.5 rounded bg-warm-ivory text-espresso-brown font-medium">{{ $hl }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="page-summary-bar" class="hidden rounded-xl bg-ink-black text-warm-ivory px-4 py-3 flex-wrap items-center justify-between gap-2 text-xs">
                        <div>
                            <span class="text-warm-ivory/70">Paket Terpilih:</span>
                            <strong id="page-selected-summary" class="text-amber-300 ml-1">Belum dipilih</strong>
                        </div>
                        <span id="page-selected-desc" class="text-[11px] text-warm-ivory/70 italic"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="page-area" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                            Pilihan Area <span class="text-red-600">*</span>
                        </label>
                        <select name="area" id="page-area" required
                            class="w-full bg-warm-ivory/60 border border-soft-beige rounded-xl px-4 py-3 text-sm text-ink-black focus:outline-none focus:border-ink-black transition-colors">
                            <option value="Indoor (AC & Working Space)">Indoor (AC &amp; Working Space)</option>
                            <option value="Outdoor (Smoking Area & Garden)">Outdoor (Smoking Area &amp; Garden)</option>
                            <option value="Fleksibel (Sesuai Ketersediaan)">Fleksibel (Sesuai Ketersediaan)</option>
                        </select>
                    </div>

                    <div>
                        <label for="page-notes" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                            Catatan Khusus / Tulisan Lettering
                        </label>
                        <input type="text" name="notes" id="page-notes" placeholder="Contoh: Lettering ucapan 'Happy Birthday Dinda'..."
                            class="w-full bg-warm-ivory/60 border border-soft-beige rounded-xl px-4 py-3 text-sm text-ink-black focus:outline-none focus:border-ink-black transition-colors">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-ink-black hover:bg-neutral-800 text-warm-ivory font-bold text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg flex items-center justify-center space-x-2">
                        <span>Kirim Permintaan Reservasi via WhatsApp</span>
                        <span class="material-symbols-outlined text-sm">send</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

<script>
    function choosePageEventCategory(categoryKey) {
        const key = (categoryKey || '').toLowerCase();
        const promptEl = document.getElementById('page-empty-cat-prompt');
        const summaryBar = document.getElementById('page-summary-bar');
        const secBridal = document.getElementById('page-sec-bridal');
        const secBirthday = document.getElementById('page-sec-birthday');
        const btnBridal = document.getElementById('page-btn-cat-bridal');
        const btnBirthday = document.getElementById('page-btn-cat-birthday');
        const checkBridal = document.getElementById('page-check-cat-bridal');
        const checkBirthday = document.getElementById('page-check-cat-birthday');

        if (promptEl) promptEl.classList.add('hidden');
        if (summaryBar) {
            summaryBar.classList.remove('hidden');
            summaryBar.classList.add('flex');
        }

        if (key === 'bridal') {
            if (secBridal) secBridal.classList.remove('hidden');
            if (secBirthday) secBirthday.classList.add('hidden');
            if (btnBridal) btnBridal.className = 'text-left rounded-2xl p-4 border-2 border-[#E06D24] ring-2 ring-[#E06D24]/20 bg-[#FFFDFB] transition-all flex items-center justify-between gap-3 shadow-md';
            if (btnBirthday) btnBirthday.className = 'text-left rounded-2xl p-4 border-2 border-stone-200 hover:border-amber-400 bg-white transition-all flex items-center justify-between gap-3 shadow-2xs';
            if (checkBridal) checkBridal.className = 'w-6 h-6 rounded-full bg-[#E06D24] text-white flex items-center justify-center text-xs font-bold shrink-0';
            if (checkBirthday) checkBirthday.className = 'w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent shrink-0';

            const firstCard = document.querySelector('.page-pkg-card[data-page-sec="Bridal"][data-page-code="A"]');
            selectPagePackage('Bridal', 'Paket Dekorasi A', '35K', 'Dekorasi meja, lilin, dipinjami korek, lettering ucapan', firstCard);
        } else {
            if (secBirthday) secBirthday.classList.remove('hidden');
            if (secBridal) secBridal.classList.add('hidden');
            if (btnBirthday) btnBirthday.className = 'text-left rounded-2xl p-4 border-2 border-[#E06D24] ring-2 ring-[#E06D24]/20 bg-[#FFFDFB] transition-all flex items-center justify-between gap-3 shadow-md';
            if (btnBridal) btnBridal.className = 'text-left rounded-2xl p-4 border-2 border-stone-200 hover:border-rose-400 bg-white transition-all flex items-center justify-between gap-3 shadow-2xs';
            if (checkBirthday) checkBirthday.className = 'w-6 h-6 rounded-full bg-[#E06D24] text-white flex items-center justify-center text-xs font-bold shrink-0';
            if (checkBridal) checkBridal.className = 'w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent shrink-0';

            const firstCard = document.querySelector('.page-pkg-card[data-page-sec="Birthday"][data-page-code="A"]');
            selectPagePackage('Birthday', 'Paket Dekorasi A', '35K', 'Dekorasi meja, lilin, dipinjami korek, lettering ucapan', firstCard);
        }
    }

    function selectPagePackage(section, pkgName, price, desc, cardEl) {
        document.getElementById('page-serviceType').value = `${section} - ${pkgName} (${price})`;
        document.getElementById('page-eventSection').value = section;
        document.getElementById('page-eventPackage').value = `${pkgName} : ${price} (${desc})`;
        document.getElementById('page-selected-summary').innerText = `${section} — ${pkgName} : ${price}`;
        document.getElementById('page-selected-desc').innerText = desc;

        document.querySelectorAll('.page-pkg-card').forEach(c => {
            c.className = 'page-pkg-card cursor-pointer rounded-xl p-4 border border-stone-200 bg-white hover:border-[#E06D24]/50 transition-all duration-200 flex flex-col justify-between';
        });

        if (cardEl) {
            cardEl.className = 'page-pkg-card cursor-pointer rounded-xl p-4 border border-[#E06D24] bg-[#FFFDFB] ring-2 ring-[#E06D24]/20 shadow-md transition-all duration-200 flex flex-col justify-between';
        }
    }
</script>
@endsection
