@php
$facilityCategories = [];

if (isset($dbFacilities) && $dbFacilities->isNotEmpty()) {
    foreach ($dbFacilities as $f) {
        $key = $f->slug ?: (\Illuminate\Support\Str::slug($f->tab_name) ?: 'cat-' . $f->id);
        $facilityCategories[$key] = [
            'id' => $key,
            'tab_name' => $f->tab_name ?: strtoupper($key),
            'badge' => $f->badge ?: (str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) . ' / ' . strtoupper($f->tab_name ?: $key)),
            'title' => $f->title ?: ($f->label ?: 'Nucomu Space'),
            'room_label' => $f->badge ?: (str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) . ' / ' . strtoupper($f->tab_name ?: $key)),
            'subtitle' => $f->subtitle ?: ($f->title ?: 'Fasilitas Unggulan'),
            'description' => $f->description ?: '',
            'features' => is_array($f->features) ? $f->features : [],
            'photos' => $f->formatted_photos,
        ];
    }
}

// Fallback jika belum ada data di database
if (empty($facilityCategories)) {
    $facilityCategories = [
        'indoor' => [
            'id' => 'indoor',
            'tab_name' => 'INDOOR',
            'badge' => '01 / WORKSPACE & LOUNGE',
            'title' => 'Indoor Lounge Work Space',
            'room_label' => '01 / INDOOR LOUNGE',
            'subtitle' => 'Workspace & Lounge',
            'description' => 'Area dalam ruangan ber-AC yang tenang dengan meja ergonomis, stopkontak melimpah di setiap sudut, serta koneksi internet cepat. Ideal untuk WFC (Work From Cafe) maupun rapat santai.',
            'features' => [
                'Suhu ruangan sejuk & sirkulasi udara bersih',
                'Pencahayaan hangat bebas silau untuk kenyamanan mata',
                'Stopkontak di setiap meja & Wi-Fi berkecepatan tinggi',
            ],
            'photos' => [
                [
                    'src' => asset('images/nucomu/nucomu_story_2.png'),
                    'alt' => 'Indoor Lounge Nucomu Cafe — Meja Ergonomis & Monstera',
                    'caption' => 'Meja Ergonomis & Suasana Asri',
                ],
                [
                    'src' => asset('images/nucomu/nucomu_story_1.png'),
                    'alt' => 'Espresso Bar Nucomu Cafe — Counter Seduh & Mesin Kopi',
                    'caption' => 'Espresso Bar & Counter Seduh',
                ],
                [
                    'src' => asset('images/nucomu/nucomu_story_4.png'),
                    'alt' => 'Sudut Apparel Nucomu Cafe — Merchandise & Cermin Estetis',
                    'caption' => 'Sudut Apparel & Cermin Estetis',
                ],
            ],
        ],
        'outdoor' => [
            'id' => 'outdoor',
            'tab_name' => 'OUTDOOR',
            'badge' => '02 / OUTDOOR TERRACE',
            'title' => 'Outdoor Garden Terrace',
            'room_label' => '02 / OUTDOOR TERRACE',
            'subtitle' => 'Garden Terrace',
            'description' => 'Taman terbuka bersuasana asri dengan naungan pepohonan rindang dan pencahayaan malam yang temaram romantis. Tempat favorit untuk menikmati senja, angin sepoi-sepoi, dan obrolan santai tanpa sekat kaku.',
            'features' => [
                'Smoking area yang lega dan asri',
                'Suasana hijau teduh dengan bangku taman nyaman',
                'Atmosfer santai untuk bercengkerama sore hingga malam',
            ],
            'photos' => [
                [
                    'src' => asset('images/nucomu/nucomu_story_3.png'),
                    'alt' => 'Outdoor Garden Terrace Nucomu Cafe — Kursi Kayu Alami & Lampu Hangat',
                    'caption' => 'Bangku Kayu & Lampu Temaram',
                ],
                [
                    'src' => asset('images/nucomu/nucomu_outdoor_2.png'),
                    'alt' => 'Outdoor Garden Nucomu Cafe — Area Terbuka Hijau & Sejuk',
                    'caption' => 'Area Terbuka Hijau & Sejuk',
                ],
                [
                    'src' => asset('images/nucomu/nucomu_outdoor_3.png'),
                    'alt' => 'Outdoor Nucomu Cafe — Sudut Santai Sore Hari',
                    'caption' => 'Sudut Santai Sore Hari',
                ],
            ],
        ],
        'musholla' => [
            'id' => 'musholla',
            'tab_name' => 'MUSHOLLA',
            'badge' => '03 / KENYAMANAN IBADAH',
            'title' => 'Musala Bersih & Terpisah',
            'room_label' => '03 / KENYAMANAN IBADAH',
            'subtitle' => 'Kenyamanan Ibadah',
            'description' => 'Fasilitas musala khusus lengkap dengan sarana wudhu yang selalu terjaga kebersihannya, sajadah, mukena wangi, serta pendingin udara untuk kenyamanan dan ketenangan ibadah Anda.',
            'features' => [
                'Sarana Wudhu Bersih',
                'Mukena & Sajadah',
                'Sejuk & Tenang',
            ],
            'photos' => [
                [
                    'src' => asset('images/nucomu/nucomu_story_1.png'),
                    'alt' => 'Mushola Nucomu Cafe — Bersih & Tenang',
                    'caption' => 'Area Ibadah Bersih & Tenang',
                ],
            ],
        ],
    ];
}

$firstKey = array_key_first($facilityCategories);
$firstCat = $facilityCategories[$firstKey];
@endphp

<!-- SECTION 2: INTERACTIVE FLOATING GALLERY (CAROUSEL DINAMIS PER KONTEN) -->
<div id="facilities-interactive-gallery" class="mt-8 sm:mt-12 lg:mt-14 mb-16 sm:mb-20 pb-4 sm:pb-6" data-facilities-gallery>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 xl:gap-14 items-center">
        
        <!-- SISI KIRI (40-45%): INFORMASI KATEGORI, TABS DINAMIS & NAVIGASI KATEGORI -->
        <div class="lg:col-span-5 flex flex-col justify-center text-left space-y-6">
            
            <!-- Category Navigation: Tabs Otomatis dari Admin & Arrow Switcher -->
            <div class="facility-anim-left facility-delay-1 flex flex-wrap items-center gap-3">
                
                <!-- Category Tabs (Otomatis Sesuai Data Admin) -->
                <div class="inline-flex flex-wrap items-center p-1 bg-[#F5F5F5] border border-[#E5E5E5] rounded-full shadow-2xs gap-1" role="tablist" aria-label="Pilih Kategori Area Nucomu">
                    @foreach($facilityCategories as $catKey => $catData)
                        <button type="button"
                                id="facility-tab-{{ $catKey }}"
                                role="tab"
                                aria-selected="{{ $catKey === $firstKey ? 'true' : 'false' }}"
                                aria-controls="facility-photo-stack"
                                data-facility-category-trigger="{{ $catKey }}"
                                class="facility-category-tab px-4 sm:px-5 py-2 rounded-full text-xs font-semibold tracking-wider uppercase transition-all duration-300 {{ $catKey === $firstKey ? 'bg-[#242424] text-white shadow-xs' : 'bg-transparent text-[#666666] hover:text-[#1C1C1C]' }} focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2">
                            {{ $catData['tab_name'] ?? strtoupper($catKey) }}
                        </button>
                    @endforeach
                </div>

                <!-- Category Arrows: Berpindah Antar Slide Carousel -->
                <div class="flex items-center gap-1.5" role="group" aria-label="Pindah Slide Carousel">
                    <button type="button"
                            id="facility-category-prev"
                            data-facility-arrow="prev"
                            class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] text-[#1C1C1C] hover:bg-[#242424] hover:text-white hover:border-[#242424] transition-all duration-200 flex items-center justify-center shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]"
                            aria-label="Pindah ke slide sebelumnya"
                            title="Slide Sebelumnya">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button type="button"
                            id="facility-category-next"
                            data-facility-arrow="next"
                            class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] text-[#1C1C1C] hover:bg-[#242424] hover:text-white hover:border-[#242424] transition-all duration-200 flex items-center justify-center shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]"
                            aria-label="Pindah ke slide berikutnya"
                            title="Slide Berikutnya">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Dynamic Information Pane (Fade In/Out saat Switch Slide) -->
            <div id="facility-info-pane" class="space-y-4 transition-all duration-300 transform opacity-100 translate-y-0">
                <!-- Category Label / Badge -->
                <span id="facility-info-label" class="facility-anim-left facility-delay-2 text-[11px] font-semibold text-[#737373] uppercase tracking-[0.2em] block font-body">
                    {{ $firstCat['badge'] }}
                </span>

                <!-- Category Heading -->
                <h3 id="facility-info-title" class="facility-anim-left facility-delay-3 text-2xl sm:text-3xl font-semibold text-[#1C1C1C] tracking-tight font-body leading-tight">
                    {{ $firstCat['title'] }}
                </h3>

                <!-- Category Description -->
                <p id="facility-info-desc" class="facility-anim-left facility-delay-4 font-body text-xs sm:text-sm text-[#666666] leading-relaxed font-normal max-w-xl">
                    {{ $firstCat['description'] }}
                </p>

                <!-- Key Features Checklist (Poin Bercentang Fleksibel) -->
                <ul id="facility-info-features" class="facility-anim-left facility-delay-5 space-y-2.5 pt-2 text-xs sm:text-sm text-[#444444] font-body" aria-label="Keunggulan Fasilitas">
                    @foreach($firstCat['features'] as $feature)
                        <li class="flex items-center gap-3">
                            <span class="w-5 h-5 rounded-full bg-[#1C1C1C]/6 border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] shrink-0" aria-hidden="true">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </span>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Accessibility Note / Hint for Gallery Interaction -->
            <div class="facility-anim-left facility-delay-6 pt-2 flex items-center gap-2 text-[11px] text-[#737373] font-body">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m3.407-4.243L5.566 4.666" />
                </svg>
                <span>Pilih tab atau gunakan tombol panah untuk menjelajah fasilitas lainnya</span>
            </div>

        </div>

        <!-- SISI KANAN (55-60%): ASYMMETRICAL FLOATING GALLERY (SLOT MAIN + SUB) -->
        <div class="lg:col-span-7">
            <div id="facility-photo-stack"
                 class="facility-photo-stack-container facility-anim-right relative w-full h-[360px] sm:h-[420px] md:h-[460px] lg:h-[490px] select-none"
                 role="region"
                 aria-label="Galeri Foto Fasilitas Nucomu"
                 tabindex="0">

                {{-- LOOP SETIAP SLIDE FASILITAS DINAMIS --}}
                @foreach($facilityCategories as $catKey => $catData)
                    @php
                        $photoCount = count($catData['photos']);
                        $isCurrent = ($catKey === $firstKey);
                    @endphp

                    <div class="facility-category-stack absolute inset-0 transition-opacity duration-500 {{ $isCurrent ? 'opacity-100 z-10 pointer-events-auto' : 'opacity-0 z-0 pointer-events-none' }}"
                         data-facility-category-stack="{{ $catKey }}"
                         aria-hidden="{{ $isCurrent ? 'false' : 'true' }}">

                        <!-- FRAME 1: SLOT MAIN (Foto Utama Kiri Atas) -->
                        <div class="facility-frame-main absolute z-20 rounded-[20px] overflow-hidden border-[3.5px] border-white shadow-[0_20px_42px_-10px_rgba(0,0,0,0.18)] bg-neutral-100 select-none">
                            <div class="facility-main-slides relative w-full h-full">
                                @foreach($catData['photos'] as $idx => $photo)
                                    <div class="facility-slide-main absolute inset-0 transition-opacity duration-700 ease-in-out {{ $idx === 0 ? 'active opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}"
                                         data-slide-index="{{ $idx }}"
                                         data-photo-caption="{{ $photo['caption'] }}"
                                         data-photo-alt="{{ $photo['alt'] }}"
                                         aria-hidden="{{ $idx === 0 ? 'false' : 'true' }}">
                                        <img src="{{ $photo['src'] }}"
                                             alt="{{ $photo['alt'] }}"
                                             class="w-full h-full object-cover object-center"
                                             loading="{{ ($isCurrent && $idx === 0) ? 'eager' : 'lazy' }}">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/10 pointer-events-none" aria-hidden="true"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- FRAME 2: SLOT SUB (Foto Pendukung Kanan Bawah — Muncul jika foto >= 2) -->
                        @if($photoCount >= 2)
                            <div class="facility-frame-sub absolute z-30 rounded-[20px] overflow-hidden border-[3.5px] border-white shadow-[0_22px_46px_-8px_rgba(0,0,0,0.28)] bg-neutral-100 select-none group/sub cursor-pointer hover:shadow-[0_28px_52px_-10px_rgba(0,0,0,0.34)] transition-all duration-300">
                                <button type="button"
                                        class="facility-sub-trigger relative w-full h-full block focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2"
                                        aria-label="Tampilkan foto berikutnya"
                                        title="Klik untuk tampilkan sebagai foto utama">
                                    <div class="facility-sub-slides relative w-full h-full">
                                        @foreach($catData['photos'] as $idx => $photo)
                                            <div class="facility-slide-sub absolute inset-0 transition-opacity duration-700 ease-in-out {{ $idx === 1 ? 'active opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}"
                                                 data-slide-index="{{ $idx }}"
                                                 data-photo-caption="{{ $photo['caption'] }}"
                                                 data-photo-alt="{{ $photo['alt'] }}"
                                                 aria-hidden="{{ $idx === 1 ? 'false' : 'true' }}">
                                                <img src="{{ $photo['src'] }}"
                                                     alt="{{ $photo['alt'] }}"
                                                     class="w-full h-full object-cover object-center group-hover/sub:scale-[1.03] transition-transform duration-700 ease-out"
                                                     loading="{{ ($isCurrent && $idx === 1) ? 'eager' : 'lazy' }}">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/10 pointer-events-none" aria-hidden="true"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Visual Click Cue Pill -->
                                    <div class="facility-photo-click-badge absolute bottom-3 right-3 z-20 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-[9px] font-bold text-[#1C1C1C] border border-[#E5E5E5] shadow-xs uppercase tracking-wider opacity-0 group-hover/sub:opacity-100 transition-opacity duration-200 pointer-events-none hidden sm:flex items-center gap-1.5" aria-hidden="true">
                                        <svg class="w-3 h-3 text-[#1C1C1C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Ganti</span>
                                    </div>
                                </button>
                            </div>
                        @endif

                    </div>
                @endforeach

                <!-- KARTU A (KANAN ATAS FOTO UTAMA): BADGE & SUBJUDUL -->
                <div id="facility-card-a"
                     style="z-index: 60;"
                     class="absolute -top-3 sm:-top-4 right-2 sm:right-auto sm:left-[52%] lg:left-[54%] z-50 bg-white/95 backdrop-blur-md border border-[#E5E5E5] rounded-[14px] sm:rounded-[16px] px-3.5 sm:px-4 py-2 sm:py-2.5 shadow-[0_12px_28px_-4px_rgba(0,0,0,0.16)] max-w-[195px] sm:max-w-[240px] pointer-events-none select-none transition-all duration-300"
                     aria-hidden="true">
                    <span id="facility-card-a-badge" class="text-[9px] sm:text-[10px] font-bold text-[#737373] uppercase tracking-[0.18em] block font-body mb-0.5">
                        {{ $firstCat['room_label'] }}
                    </span>
                    <p id="facility-card-a-subtitle" class="text-xs sm:text-[13px] font-bold text-[#1C1C1C] leading-snug font-body truncate">
                        {{ $firstCat['subtitle'] }}
                    </p>
                </div>

                <!-- KARTU B (KIRI BAWAH FOTO UTAMA): CAPTION & PENGHITUNG / DOTS -->
                <div id="facility-card-b"
                     style="z-index: 60;"
                     class="absolute -bottom-4 sm:-bottom-5 lg:-bottom-6 left-2 sm:left-4 lg:left-6 z-50 bg-white/95 backdrop-blur-md border border-[#E5E5E5] rounded-[14px] sm:rounded-[16px] px-3.5 sm:px-4 py-2.5 sm:py-3 shadow-[0_14px_32px_-6px_rgba(0,0,0,0.18)] max-w-[240px] sm:max-w-[280px] md:max-w-[320px] pointer-events-none select-none transition-all duration-300"
                     aria-hidden="true">
                    <p id="facility-card-b-caption" class="text-xs sm:text-[13px] font-bold text-[#1C1C1C] leading-snug font-body">
                        {{ $firstCat['photos'][0]['caption'] ?? '' }}
                    </p>
                    <div id="facility-card-b-meta" class="flex items-center gap-2.5 mt-1.5 {{ count($firstCat['photos']) <= 1 ? 'hidden' : '' }}">
                        <span id="facility-card-b-counter" class="text-[10.5px] sm:text-[11px] font-medium text-[#666666] font-body tracking-wide">
                            1 / {{ count($firstCat['photos']) }}
                        </span>
                        <div id="facility-card-b-dots" class="flex items-center gap-1" aria-hidden="true">
                            @foreach($firstCat['photos'] as $dotIdx => $dotPhoto)
                                <span class="facility-counter-dot transition-all duration-300 {{ $dotIdx === 0 ? 'w-3.5 h-1.5 bg-[#242424] rounded-full' : 'w-1.5 h-1.5 bg-[#D1D5DB] rounded-full' }}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Oper data lengkap ke window JS untuk interaktivitas instan tanpa re-render --}}
<script>
    window.NUCOMU_FACILITIES_DATA = @json($facilityCategories);
</script>
