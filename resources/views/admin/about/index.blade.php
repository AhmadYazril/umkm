@extends('layouts.admin')
@section('title', 'Tentang Cafe — Nucomu Cafe')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    {{-- 1. Header Halaman Standar (x-page-header dengan Judul Tenang & Tombol ? Petunjuk) --}}
    <x-page-header title="Tentang Cafe">
        <x-slot name="actions">
            <button type="button"
                    class="admin-help-link btn-press"
                    id="about-help-trigger"
                    aria-expanded="false"
                    aria-controls="about-help-panel-content"
                    onclick="toggleHelpPanel('about-help-panel')">
                <i class="fa-regular fa-circle-question text-[13px]" aria-hidden="true"></i>
                <span class="toggle-label" data-open="? Petunjuk" data-close="Tutup petunjuk">? Petunjuk</span>
                <i class="fa-solid fa-chevron-down text-[10px] toggle-chevron" aria-hidden="true"></i>
            </button>
        </x-slot>

        {{-- Panel Petunjuk Penggunaan (x-help-panel) --}}
        <x-slot name="help">
            <x-help-panel id="about-help-panel" :trigger="false">
                <div class="space-y-4">
                    {{-- Judul Panel Petunjuk --}}
                    <div>
                        <h3 class="text-[15px] font-bold tracking-tight" style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                            Panduan Mengelola Halaman Tentang Cafe
                        </h3>
                        <p class="text-[12.5px] mt-0.5" style="color: var(--admin-text-sub, #555D58);">
                            Halaman ini mengatur teks dan media pengantar di bagian "Tentang Kami" pada website depan kafe.
                        </p>
                    </div>

                    {{-- 4 Langkah Singkat --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-1">
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">1</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Foto atau Video</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Pilih video looping singkat (10&ndash;30 detik) atau foto lanskap terbaik kafe.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">2</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Cerita Kafe</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Cukup isi judul besar dan cerita kafe. Bagian utama ini sudah memenuhi kebutuhan website Anda.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">+</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Pengaturan Tambahan</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Bersifat opsional. Buka bagian lipat ini hanya jika Anda ingin mengubah judul kecil, kutipan, atau tujuan tombol.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">3</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Simpan Perubahan</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Klik tombol hijau "Simpan perubahan" di bawah formulir untuk menerapkan konten baru ke website.
                            </p>
                        </div>
                    </div>

                    {{-- Spesifikasi & Batas Media Berdasarkan Batas Aktual Server --}}
                    <div class="p-3.5 rounded-xl border text-[12.5px] leading-relaxed space-y-2"
                         style="background-color: var(--admin-bg-sidebar-hover, #F0F4F1); border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-sliders text-xs" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                            <span>Batas Ukuran &amp; Format Media Saat Ini</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Foto / Gambar:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Rasio disarankan 16:9 &bull; Format: JPG, PNG, WebP &bull; Maksimal <strong>{{ $imageLimits['formatted'] ?? '5 MB' }}</strong>.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Video Pendek:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Format: MP4 atau WebM &bull; Durasi: 10&ndash;30 detik &bull; Maksimal <strong>{{ $videoLimits['formatted'] ?? '20 MB' }}</strong> (otomatis berulang tanpa suara).</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tips Menulis & Keterangan Bawaan --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[12px] pt-1"
                         style="color: var(--admin-text-sub, #555D58);">
                        <p class="m-0">
                            <strong>Tips Praktis:</strong> Mengisi foto/video dan cerita kafe sudah cukup untuk sebagian besar kebutuhan. Pengaturan tambahan hanya diperlukan jika Anda ingin memodifikasi detail kecil seperti kutipan atau tombol.
                        </p>
                        <p class="m-0 sm:text-right shrink-0">
                            <em>Jika tidak diubah, teks bawaan Nucomu Cafe tetap tampil di website.</em>
                        </p>
                    </div>
                </div>
            </x-help-panel>
        </x-slot>
    </x-page-header>

    {{-- Alert Berhasil / Flash Notification --}}
    @if(session('success'))
        <div class="p-3.5 sm:p-4 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-[13px] leading-relaxed anim-fade-up"
             style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); border-color: #C3DCD0; color: #164E33;">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 shrink-0 text-base" aria-hidden="true"></i>
                <div>
                    <span class="font-bold">Perubahan tersimpan.</span>
                    <span class="opacity-90 ml-1">Konten Tentang Cafe berhasil diperbarui.</span>
                </div>
            </div>
            <a href="{{ route('home') }}#about" target="_blank" rel="noopener noreferrer"
               class="font-bold underline hover:opacity-80 transition shrink-0 flex items-center gap-1.5 self-end sm:self-auto text-emerald-900">
                <span>Lihat hasilnya di website</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
            </a>
        </div>
    @endif

    {{-- Alert Kesalahan Input / Validasi --}}
    @if($errors->any() || session('error'))
        <div class="p-4 rounded-xl border bg-rose-50 border-rose-200 text-rose-900 text-[13px] shadow-xs space-y-1.5 anim-fade-up">
            <div class="font-bold flex items-center gap-2 text-rose-800">
                <i class="fa-solid fa-circle-xmark text-rose-600" aria-hidden="true"></i>
                <span>{{ session('error') ? 'Terjadi kesalahan:' : 'Terdapat beberapa isian yang perlu diperiksa:' }}</span>
            </div>
            @if(session('error'))
                <p class="leading-relaxed pl-6 m-0 text-[12.5px] font-medium">{{ session('error') }}</p>
            @endif
            @if($errors->any())
                <ul class="list-disc list-inside space-y-0.5 pl-6 m-0 text-[12.5px]">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    {{-- Tab Switcher Khusus Layar HP / Mobile (< lg) --}}
    <div class="lg:hidden flex items-center p-1 rounded-xl border bg-white gap-1 select-none"
         style="border-color: var(--admin-border-card, #E6E8E4); background-color: var(--admin-bg-card, #FFFFFF);">
        <button type="button" 
                onclick="switchMobileViewTab('form')" 
                id="mobileTabBtnForm"
                class="flex-1 py-2 px-3 rounded-lg text-[13px] font-semibold text-center transition flex items-center justify-center gap-2"
                style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;">
            <i class="fa-solid fa-pen-to-square text-xs" aria-hidden="true"></i>
            <span>Isi Formulir</span>
        </button>
        <button type="button" 
                onclick="switchMobileViewTab('preview')" 
                id="mobileTabBtnPreview"
                class="flex-1 py-2 px-3 rounded-lg text-[13px] font-medium text-center transition flex items-center justify-center gap-2"
                style="color: var(--admin-text-sub, #555D58);">
            <i class="fa-solid fa-eye text-xs" aria-hidden="true"></i>
            <span>Contoh Tampilan</span>
        </button>
    </div>

    {{-- Main Grid: Kolom Formulir di Kiri & Contoh Tampilan di Kanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">

        {{-- KOLOM FORMULIR (7 Kolom pada Desktop) --}}
        <div class="lg:col-span-7 space-y-6" id="aboutFormCol">
            <form action="{{ route('admin.about.update') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  id="aboutForm" 
                  class="space-y-6">
                @csrf

                {{-- KARTU LANGKAH 1: Foto atau video --}}
                <x-form-section step="1" 
                                title="Foto atau video" 
                                description="Pilih media visual yang menjadi daya tarik utama cerita kafe Anda di website">
                    
                    <x-media-picker name="media_file"
                                    typeInputName="media_type"
                                    :currentType="$aboutData['about_media_type'] ?? 'video'"
                                    :currentUrl="$aboutData['media_url'] ?? asset('videos/tiramisu.mp4')"
                                    :currentPath="$aboutData['about_media_path'] ?? 'video/tiramisu.mp4'"
                                    defaultType="video"
                                    :defaultUrl="asset('videos/tiramisu.mp4')"
                                    defaultPath="video/tiramisu.mp4"
                                    :imageLimits="$imageLimits"
                                    :videoLimits="$videoLimits" />
                </x-form-section>

                {{-- KARTU LANGKAH 2: Cerita cafe --}}
                <x-form-section step="2" 
                                title="Cerita cafe" 
                                description="Tuliskan judul utama dan 2 paragraf cerita mengenai suasana dan racikan kafe Anda">
                    
                    {{-- 2a. Judul besar --}}
                    <x-field for="cafe_story_headline"
                             label="Judul besar"
                             :required="true"
                             hint="Tampil paling menonjol sebagai pembuka cerita kafe. Contoh: Berawal dari Rasa, Kembali karena Rindu."
                             recommendation="Saran: 30–60 huruf"
                             :defaultValue="'Berawal dari Rasa, Kembali karena Rindu.'"
                             :counter="true"
                             :max="255">
                        <input type="text" 
                               name="cafe_story_headline" 
                               id="cafe_story_headline" 
                               value="{{ old('cafe_story_headline', $aboutData['cafe_story_headline']) }}" 
                               required
                               maxlength="255"
                               placeholder="Contoh: Berawal dari Rasa, Kembali karena Rindu."
                               oninput="liveSyncText('previewHeadline', this.value)"
                               class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] font-bold transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                               style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    </x-field>

                    {{-- 2b. Cerita cafe (paragraf 1) --}}
                    <x-field for="cafe_story_p1"
                             label="Cerita cafe (paragraf 1)"
                             :required="true"
                             hint="Paragraf pengantar filosofi rasa dan sajian kafe. Tampil tepat di bawah judul besar."
                             recommendation="Saran: 2–3 kalimat (100–250 huruf)"
                             :defaultValue="'Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.'"
                             :counter="true"
                             :max="1500">
                        <textarea name="cafe_story_p1" 
                                  id="cafe_story_p1" 
                                  rows="3" 
                                  required
                                  maxlength="1500"
                                  placeholder="Tuliskan cerita pembuka kafe Anda..."
                                  oninput="liveSyncText('previewP1', this.value)"
                                  class="w-full px-3.5 py-2.5 rounded-xl border text-[13.5px] leading-relaxed transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] resize-none"
                                  style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">{{ old('cafe_story_p1', $aboutData['cafe_story_p1']) }}</textarea>
                    </x-field>

                    {{-- 2c. Cerita cafe (paragraf 2) --}}
                    <x-field for="cafe_story_p2"
                             label="Cerita cafe (paragraf 2)"
                             :required="false"
                             hint="Paragraf lanjutan mengenai suasana ruang monokrom dan kenyamanan berkunjung."
                             recommendation="Saran: 2–3 kalimat (100–250 huruf) • Opsional"
                             :defaultValue="'Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.'"
                             :counter="true"
                             :max="1500">
                        <textarea name="cafe_story_p2" 
                                  id="cafe_story_p2" 
                                  rows="3" 
                                  maxlength="1500"
                                  placeholder="Tuliskan paragraf kedua mengenai suasana dan kenyamanan (opsional)..."
                                  oninput="liveSyncText('previewP2', this.value)"
                                  class="w-full px-3.5 py-2.5 rounded-xl border text-[13.5px] leading-relaxed transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] resize-none"
                                  style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">{{ old('cafe_story_p2', $aboutData['cafe_story_p2']) }}</textarea>
                    </x-field>
                </x-form-section>

                {{-- BAGIAN LIPAT (ACCORDION): Pengaturan tambahan --}}
                @php
                    $defaultAboutLabel = 'OUR STORY / TENTANG KAMI';
                    $defaultAboutQuote = 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.';
                    $defaultAboutButtonText = 'Jelajahi Pilihan Menu';
                    $defaultAboutButtonLink = '#menu';

                    $currentAboutLabel = old('about_label', $aboutData['about_label'] ?? $defaultAboutLabel);
                    $currentAboutQuote = old('about_quote', $aboutData['about_quote'] ?? $defaultAboutQuote);
                    $currentAboutButtonText = old('about_button_text', $aboutData['about_button_text'] ?? $defaultAboutButtonText);
                    $currentAboutButtonLink = old('about_button_link', $aboutData['about_button_link'] ?? $defaultAboutButtonLink);

                    $isLabelModified = trim((string)$currentAboutLabel) !== $defaultAboutLabel;
                    $isQuoteModified = trim((string)$currentAboutQuote) !== $defaultAboutQuote;
                    $isButtonTextModified = trim((string)$currentAboutButtonText) !== $defaultAboutButtonText;
                    $isButtonLinkModified = trim((string)$currentAboutButtonLink) !== $defaultAboutButtonLink;

                    $hasExtraModified = $isLabelModified || $isQuoteModified || $isButtonTextModified || $isButtonLinkModified;
                    $hasExtraErrors = $errors->has('about_label') || $errors->has('about_quote') || $errors->has('about_button_text') || $errors->has('about_button_link');

                    $accordionOpen = $hasExtraErrors || $hasExtraModified;
                @endphp

                <div id="extraAccordionCard" 
                     class="admin-card overflow-hidden border rounded-2xl transition-all shadow-xs"
                     style="background-color: var(--admin-bg-card, #FFFFFF); border-color: {{ $hasExtraErrors ? '#F43F5E' : 'var(--admin-border-card, #E6E8E4)' }};">
                    
                    {{-- Tombol Pemicu Accordion (Tinggi min 48px, mudah ditekan di HP) --}}
                    <button type="button"
                            id="extraAccordionTrigger"
                            onclick="toggleExtraAccordion()"
                            aria-expanded="{{ $accordionOpen ? 'true' : 'false' }}"
                            aria-controls="accordionExtraContent"
                            class="w-full min-h-[48px] px-4 sm:px-5 py-3.5 flex items-center justify-between gap-3 text-left transition hover:bg-[#FAFBF9] cursor-pointer select-none">
                        
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 border"
                                 style="background-color: var(--admin-bg-sidebar-hover, #F0F4F1); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-btn-primary-bg, #2F6B4F);">
                                <i class="fa-solid fa-sliders text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-[14px]" style="color: var(--admin-text-main, #1B1F1D);">
                                        Pengaturan tambahan
                                    </span>
                                    {{-- Penanda "Sudah diubah" --}}
                                    <span id="extraModifiedBadge" 
                                          class="{{ $hasExtraModified ? 'inline-flex' : 'hidden' }} items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                        <i class="fa-solid fa-circle-info text-[9.5px] text-amber-600"></i>
                                        <span>Sudah diubah</span>
                                    </span>
                                    @if($hasExtraErrors)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                            <i class="fa-solid fa-circle-exclamation text-[9.5px] text-rose-600"></i>
                                            <span>Periksa isian</span>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[12px] m-0 line-clamp-1" style="color: var(--admin-text-sub, #555D58);">
                                    Opsional. Biasanya tidak perlu diubah.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span id="extraAccordionStatusText" class="text-[12px] font-medium hidden sm:inline" style="color: var(--admin-text-sub, #555D58);">
                                {{ $accordionOpen ? 'Tutup' : 'Buka' }}
                            </span>
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center border bg-white"
                                 style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-sub, #555D58);">
                                <i id="extraAccordionChevron" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 {{ $accordionOpen ? 'rotate-180' : '' }}" aria-hidden="true"></i>
                            </div>
                        </div>
                    </button>

                    {{-- Konten Bagian Lipat --}}
                    <div id="accordionExtraContent" 
                         class="{{ $accordionOpen ? '' : 'hidden' }} px-4 sm:px-5 pb-5 pt-3 border-t space-y-5"
                         style="border-color: var(--admin-border-card, #E6E8E4);">
                        
                        {{-- 1. Judul kecil di atas judul --}}
                        <x-field for="about_label"
                                 label="Judul kecil di atas judul"
                                 :required="true"
                                 hint="Tampil kecil dengan garis pemisah tipis di atas judul besar. Contoh: OUR STORY / TENTANG KAMI"
                                 recommendation="Saran: 15–30 huruf"
                                 :defaultValue="'OUR STORY / TENTANG KAMI'"
                                 :counter="true"
                                 :max="100">
                            <input type="text" 
                                   name="about_label" 
                                   id="about_label" 
                                   value="{{ old('about_label', $aboutData['about_label']) }}" 
                                   required
                                   maxlength="100"
                                   placeholder="Contoh: OUR STORY / TENTANG KAMI"
                                   oninput="liveSyncText('previewLabel', this.value); checkExtraModifiedStatus();"
                                   class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] font-semibold transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] uppercase tracking-wider {{ $errors->has('about_label') ? 'border-rose-400 bg-rose-50/20' : '' }}"
                                   style="background-color: var(--admin-bg-card, #FFFFFF); border-color: {{ $errors->has('about_label') ? '#FB7185' : 'var(--admin-border-card, #E6E8E4)' }}; color: var(--admin-text-main, #1B1F1D);">
                        </x-field>

                        {{-- 2. Kalimat pilihan --}}
                        <div class="pt-4 border-t" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <x-field for="about_quote"
                                     label="Kalimat pilihan (tampil di kotak bawah)"
                                     :required="false"
                                     hint="Kutipan filosofis di kartu abu-abu bawah dengan tanda petik besar."
                                     recommendation="Saran: 1–2 kalimat bermakna (80–180 huruf) • Opsional"
                                     :defaultValue="'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.'"
                                     :counter="true"
                                     :max="500">
                                <textarea name="about_quote" 
                                          id="about_quote" 
                                          rows="2" 
                                          maxlength="500"
                                          placeholder="Tuliskan kutipan atau kalimat pilihan berkesan..."
                                          oninput="liveSyncText('previewQuote', this.value); checkExtraModifiedStatus();"
                                          class="w-full px-3.5 py-2.5 rounded-xl border text-[13.5px] italic leading-relaxed transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] resize-none {{ $errors->has('about_quote') ? 'border-rose-400 bg-rose-50/20' : '' }}"
                                          style="background-color: var(--admin-bg-card, #FFFFFF); border-color: {{ $errors->has('about_quote') ? '#FB7185' : 'var(--admin-border-card, #E6E8E4)' }}; color: var(--admin-text-main, #1B1F1D);">{{ old('about_quote', $aboutData['about_quote']) }}</textarea>
                            </x-field>
                        </div>

                        {{-- 3. Tulisan di tombol --}}
                        <div class="pt-4 border-t" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <x-field for="about_button_text"
                                     label="Tulisan di tombol"
                                     :required="false"
                                     hint="Teks pada tombol bulat utama di bawah cerita. Contoh: Jelajahi Pilihan Menu"
                                     recommendation="Saran: 15–30 huruf"
                                     :defaultValue="'Jelajahi Pilihan Menu'"
                                     :counter="true"
                                     :max="100">
                                <input type="text" 
                                       name="about_button_text" 
                                       id="about_button_text" 
                                       value="{{ old('about_button_text', $aboutData['about_button_text']) }}" 
                                       maxlength="100"
                                       placeholder="Contoh: Jelajahi Pilihan Menu"
                                       oninput="liveSyncText('previewBtnText', this.value); checkExtraModifiedStatus();"
                                       class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] {{ $errors->has('about_button_text') ? 'border-rose-400 bg-rose-50/20' : '' }}"
                                       style="background-color: var(--admin-bg-card, #FFFFFF); border-color: {{ $errors->has('about_button_text') ? '#FB7185' : 'var(--admin-border-card, #E6E8E4)' }}; color: var(--admin-text-main, #1B1F1D);">
                            </x-field>
                        </div>

                        {{-- 4. Tombol mengarah ke --}}
                        @php
                            $knownSections = [
                                '#menu' => 'menu',
                                '/menu' => 'menu',
                                '#all-menu' => 'menu',
                                '#reservasi' => 'reservasi',
                                '/reservasi' => 'reservasi',
                                '#reservation' => 'reservasi',
                                '#facilities' => 'facilities',
                                '#fasilitas' => 'facilities',
                                '#reviews' => 'reviews',
                                '#ulasan' => 'reviews',
                                '#contact' => 'contact',
                                '#kontak' => 'contact',
                            ];
                            $currentLinkVal = old('about_button_link', $aboutData['about_button_link'] ?? '#menu');
                            $matchedKey = $knownSections[$currentLinkVal] ?? 'custom';
                        @endphp

                        <div class="pt-4 border-t space-y-1.5 field-wrapper" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <div class="flex items-center justify-between gap-2 min-w-0">
                                <label for="aboutLinkSelector" class="block text-[13.5px] font-semibold select-none truncate" style="color: var(--admin-text-main, #1B1F1D);">
                                    Tombol mengarah ke
                                </label>
                                <button type="button"
                                        class="text-[12px] font-medium hover:underline transition shrink-0 whitespace-nowrap cursor-pointer flex items-center gap-1.5 py-0.5 px-1 rounded focus:outline-none focus:ring-1 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] ml-auto"
                                        style="color: var(--admin-btn-primary-bg, #2F6B4F);"
                                        onclick="resetAboutLinkToDefault()"
                                        title="Pilih kembali tujuan bawaan (Daftar menu)">
                                    <i class="fa-solid fa-arrow-rotate-left text-[10px]" aria-hidden="true"></i>
                                    <span>Kembalikan ke pilihan bawaan</span>
                                </button>
                            </div>
                            <p class="text-[12.5px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Pilih bagian halaman depan website yang dituju saat tombol diklik.
                            </p>

                            <select id="aboutLinkSelector"
                                    onchange="handleLinkSelectorChange(this.value)"
                                    class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] cursor-pointer"
                                    style="background-color: var(--admin-bg-card, #FFFFFF); border-color: {{ $errors->has('about_button_link') ? '#FB7185' : 'var(--admin-border-card, #E6E8E4)' }}; color: var(--admin-text-main, #1B1F1D);">
                                <option value="#menu" {{ $matchedKey === 'menu' ? 'selected' : '' }}>Daftar menu</option>
                                <option value="#reservasi" {{ $matchedKey === 'reservasi' ? 'selected' : '' }}>Reservasi meja</option>
                                <option value="#facilities" {{ $matchedKey === 'facilities' ? 'selected' : '' }}>Fasilitas kafe</option>
                                <option value="#reviews" {{ $matchedKey === 'reviews' ? 'selected' : '' }}>Ulasan pelanggan</option>
                                <option value="#contact" {{ $matchedKey === 'contact' ? 'selected' : '' }}>Kontak &amp; lokasi</option>
                                <option value="custom" {{ $matchedKey === 'custom' ? 'selected' : '' }}>Alamat web lain</option>
                            </select>

                            {{-- Kolom Input URL Kustom (Hanya muncul jika "Alamat web lain" dipilih) --}}
                            <div id="customLinkContainer" class="{{ $matchedKey === 'custom' ? '' : 'hidden' }} pt-2">
                                <label for="custom_url_input" class="block text-[12px] font-medium mb-1" style="color: var(--admin-text-sub, #555D58);">
                                    Ketikkan alamat web tujuan:
                                </label>
                                <input type="text"
                                       id="custom_url_input"
                                       value="{{ $matchedKey === 'custom' ? $currentLinkVal : '' }}"
                                       placeholder="https://... atau #bagian"
                                       oninput="handleCustomUrlInput(this.value)"
                                       class="w-full px-3.5 py-1.5 rounded-xl border text-[13px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                                       style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                <p class="text-[11.5px] mt-1 m-0" style="color: var(--admin-text-muted, #7A847E);">
                                    Contoh: https://instagram.com/nucomucafe
                                </p>
                            </div>

                            {{-- Input Tersembunyi yang Dikirim ke Server --}}
                            <input type="hidden" 
                                   name="about_button_link" 
                                   id="about_button_link" 
                                   value="{{ $currentLinkVal }}">
                            @error('about_button_link')
                                <p class="text-[12px] font-medium text-rose-600 mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation text-xs" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- BARIS TOMBOL AKSI FORMULIR (x-form-actions: Mengikuti alur form, non-sticky) --}}
                <x-form-actions formId="aboutForm" 
                                saveText="Simpan perubahan" 
                                cancelText="Batalkan" 
                                unsavedMessage="Ada perubahan yang belum disimpan" />

            </form>
        </div>

        {{-- KOLOM CONTOH TAMPILAN WEBSITE (5 Kolom pada Desktop, Sticky) --}}
        <div class="lg:col-span-5 sticky top-20 space-y-4" id="aboutPreviewCol">
            <div class="admin-card p-5 sm:p-6 shadow-card space-y-4"
                 style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); border-radius: var(--admin-radius-card, 12px);">
                
                {{-- Header Kartu Pratinjau --}}
                <div class="flex items-center justify-between pb-3.5 border-b"
                     style="border-color: var(--admin-border-card, #E6E8E4);">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="text-[14.5px] font-bold tracking-tight m-0" 
                                style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                                Contoh tampilan di website
                            </h3>
                        </div>
                        <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                            Berubah saat Anda mengetik
                        </p>
                    </div>
                    <span class="text-[11.5px] font-semibold px-2 py-0.5 rounded-full"
                          style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                        Sesuai Halaman Depan
                    </span>
                </div>

                {{-- Simulasi Kartu Nucomu Cafe --}}
                <div class="rounded-2xl border p-4 sm:p-5 space-y-4 select-none overflow-hidden"
                     style="background-color: #FAFAFA; border-color: var(--admin-border-card, #E6E8E4);">
                    
                    {{-- 1. Mini Layered Frame Visual Cafe --}}
                    <div class="relative max-w-[280px] mx-auto p-3">
                        {{-- Lapisan Belakang Miring (#A8A8A8) --}}
                        <div class="absolute inset-0 bg-[#A8A8A8] rounded-[22px] pointer-events-none transform -rotate-2 shadow-xs"></div>
                        
                        {{-- Frame Media --}}
                        <div class="relative z-10 w-full aspect-video rounded-[16px] overflow-hidden border-2 border-white shadow-md bg-black">
                            <div id="simMediaWrapper" class="w-full h-full">
                                <video id="simVideo" 
                                       src="{{ $aboutData['media_url'] }}" 
                                       autoplay muted loop playsinline 
                                       class="w-full h-full object-cover {{ ($aboutData['about_media_type'] ?? 'video') === 'video' ? '' : 'hidden' }}"></video>
                                <img id="simImg" 
                                     src="{{ $aboutData['media_url'] }}" 
                                     alt="Foto Nucomu" 
                                     class="w-full h-full object-cover {{ ($aboutData['about_media_type'] ?? 'video') === 'image' ? '' : 'hidden' }}">
                            </div>
                        </div>

                        {{-- Mini Badge Logo Nucomu (Kiri Atas) --}}
                        <div class="absolute top-1 left-1 z-20 w-8 h-8 bg-[#E8E8E8] rounded-lg border border-white flex items-center justify-center shadow-xs">
                            <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" class="h-3.5 w-auto brightness-0" alt="Logo">
                        </div>

                        {{-- Mini Badge Kopi (Kanan Bawah) --}}
                        <div class="absolute bottom-1 right-1 z-20 w-7 h-7 bg-[#242424] rounded-full border border-white flex items-center justify-center text-white text-[10px] shadow-xs">
                            <i class="fa-solid fa-mug-hot"></i>
                        </div>
                    </div>

                    {{-- 2. Teks Narasi Cerita --}}
                    <div class="space-y-3 pt-1">
                        {{-- Judul Kecil --}}
                        <div class="flex items-center gap-2">
                            <span id="previewLabel" class="text-[10px] font-bold text-[#666666] uppercase tracking-wider">
                                {{ $aboutData['about_label'] }}
                            </span>
                            <div class="w-8 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
                        </div>

                        {{-- Judul Besar --}}
                        <h4 id="previewHeadline" class="text-[17px] font-semibold text-[#1C1C1C] leading-snug m-0">
                            {{ $aboutData['cafe_story_headline'] }}
                        </h4>

                        {{-- Paragraf 1 --}}
                        <p id="previewP1" class="text-[12.5px] text-[#666666] leading-relaxed line-clamp-3 m-0">
                            {{ $aboutData['cafe_story_p1'] }}
                        </p>

                        {{-- Paragraf 2 --}}
                        <p id="previewP2" class="text-[12.5px] text-[#666666] leading-relaxed line-clamp-2 m-0 {{ empty($aboutData['cafe_story_p2']) ? 'hidden' : '' }}">
                            {{ $aboutData['cafe_story_p2'] }}
                        </p>

                        {{-- Tombol Aksi --}}
                        <div class="pt-1">
                            <span class="inline-flex items-center gap-2 bg-[#242424] text-white text-[11px] font-medium py-2 px-4 rounded-full shadow-xs">
                                <span id="previewBtnText">{{ $aboutData['about_button_text'] ?: 'Jelajahi Pilihan Menu' }}</span>
                                <i class="fa-solid fa-arrow-right text-[9px]" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>

                    {{-- 3. Kotak Kalimat Pilihan (Quote) --}}
                    <div id="previewQuoteBox" class="bg-[#F2F2F2] border border-[#DDDDDD] rounded-xl p-3 text-[12px] italic text-[#333333] leading-relaxed {{ empty($aboutData['about_quote']) ? 'hidden' : '' }}">
                        &ldquo;<span id="previewQuote">{{ $aboutData['about_quote'] }}</span>&rdquo;
                    </div>

                </div>

                <p class="text-[12px] m-0 text-center" style="color: var(--admin-text-sub, #555D58);">
                    Tampilan di atas mensimulasikan tata letak bagian Tentang Kami di halaman depan.
                </p>
            </div>
        </div>

    </div>


</div>

@push('scripts')
<script>
    // Live Synchronizer untuk Teks Pratinjau
    function liveSyncText(targetId, value) {
        const el = document.getElementById(targetId);
        if (!el) return;
        el.textContent = value;

        // Khusus paragraf 2 & quote box: sembunyikan jika kosong
        if (targetId === 'previewP2') {
            if (value && value.trim()) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        } else if (targetId === 'previewQuote') {
            const box = document.getElementById('previewQuoteBox');
            if (box) {
                if (value && value.trim()) {
                    box.classList.remove('hidden');
                } else {
                    box.classList.add('hidden');
                }
            }
        }
    }

    // Handler Dropdown "Tombol mengarah ke"
    function handleLinkSelectorChange(val) {
        const customContainer = document.getElementById('customLinkContainer');
        const customInput = document.getElementById('custom_url_input');
        const hiddenLink = document.getElementById('about_button_link');

        if (val === 'custom') {
            customContainer.classList.remove('hidden');
            if (hiddenLink) hiddenLink.value = customInput.value || '';
        } else {
            customContainer.classList.add('hidden');
            if (hiddenLink) hiddenLink.value = val;
        }

        // Trigger dirty checker
        if (hiddenLink) hiddenLink.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function handleCustomUrlInput(val) {
        const hiddenLink = document.getElementById('about_button_link');
        if (hiddenLink) {
            hiddenLink.value = val;
            hiddenLink.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    function resetAboutLinkToDefault() {
        const sel = document.getElementById('aboutLinkSelector');
        if (sel) {
            sel.value = '#menu';
            handleLinkSelectorChange('#menu');
            checkExtraModifiedStatus();
            if (typeof showAdminToast === 'function') {
                showAdminToast('Tujuan tombol dikembalikan ke Daftar menu.', 'info', 'Pilihan Bawaan', 2000);
            }
        }
    }

    // Nilai Bawaan untuk Deteksi Status "Sudah diubah"
    const defaultExtraValues = {
        about_label: 'OUR STORY / TENTANG KAMI',
        about_quote: 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.',
        about_button_text: 'Jelajahi Pilihan Menu',
        about_button_link: '#menu'
    };

    function checkExtraModifiedStatus() {
        const labelVal = (document.getElementById('about_label')?.value || '').trim();
        const quoteVal = (document.getElementById('about_quote')?.value || '').trim();
        const btnTextVal = (document.getElementById('about_button_text')?.value || '').trim();
        const linkVal = (document.getElementById('about_button_link')?.value || '').trim();

        const isModified = (
            labelVal !== defaultExtraValues.about_label ||
            quoteVal !== defaultExtraValues.about_quote ||
            btnTextVal !== defaultExtraValues.about_button_text ||
            linkVal !== defaultExtraValues.about_button_link
        );

        const badge = document.getElementById('extraModifiedBadge');
        if (badge) {
            if (isModified) {
                badge.classList.remove('hidden');
                badge.classList.add('inline-flex');
            } else {
                badge.classList.add('hidden');
                badge.classList.remove('inline-flex');
            }
        }

        return isModified;
    }

    function toggleExtraAccordion() {
        const content = document.getElementById('accordionExtraContent');
        const chevron = document.getElementById('extraAccordionChevron');
        const trigger = document.getElementById('extraAccordionTrigger');
        const statusText = document.getElementById('extraAccordionStatusText');
        if (!content) return;

        const isClosed = content.classList.contains('hidden');
        if (isClosed) {
            content.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
            if (statusText) statusText.textContent = 'Tutup';
        } else {
            content.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
            if (statusText) statusText.textContent = 'Buka';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('aboutForm');
        if (form) {
            form.addEventListener('input', checkExtraModifiedStatus);
            form.addEventListener('change', checkExtraModifiedStatus);

            // Buka accordion otomatis jika terjadi invalid event pada HTML5 validation di kolom dalam accordion
            form.addEventListener('invalid', function(e) {
                const content = document.getElementById('accordionExtraContent');
                if (content && content.contains(e.target) && content.classList.contains('hidden')) {
                    toggleExtraAccordion();
                }
            }, true);
        }

        checkExtraModifiedStatus();
    });

    // Sinkronisasi Live Media Pratinjau dari x-media-picker
    window.addEventListener('media-type-changed', function(e) {
        const type = e.detail.type;
        const simVideo = document.getElementById('simVideo');
        const simImg = document.getElementById('simImg');
        if (!simVideo || !simImg) return;

        if (type === 'video') {
            simVideo.classList.remove('hidden');
            simImg.classList.add('hidden');
        } else {
            simImg.classList.remove('hidden');
            simVideo.classList.add('hidden');
        }
    });

    window.addEventListener('new-media-selected', function(e) {
        const { url, isVideo } = e.detail;
        const simVideo = document.getElementById('simVideo');
        const simImg = document.getElementById('simImg');
        if (!simVideo || !simImg) return;

        if (isVideo) {
            simVideo.src = url;
            simVideo.classList.remove('hidden');
            simImg.classList.add('hidden');
            simVideo.play().catch(() => {});
        } else {
            simImg.src = url;
            simImg.classList.remove('hidden');
            simVideo.classList.add('hidden');
        }
    });

    window.addEventListener('new-media-cancelled', function() {
        const originalUrl = @js($aboutData['media_url']);
        const originalType = document.querySelector('input[name="media_type"]:checked')?.value || 'video';
        const simVideo = document.getElementById('simVideo');
        const simImg = document.getElementById('simImg');

        if (simVideo && simImg) {
            if (originalType === 'video') {
                simVideo.src = originalUrl;
                simVideo.classList.remove('hidden');
                simImg.classList.add('hidden');
            } else {
                simImg.src = originalUrl;
                simImg.classList.remove('hidden');
                simVideo.classList.add('hidden');
            }
        }
    });

    window.addEventListener('media-restored-default', function(e) {
        const { type, url } = e.detail;
        const simVideo = document.getElementById('simVideo');
        const simImg = document.getElementById('simImg');

        if (simVideo && simImg) {
            if (type === 'video') {
                simVideo.src = url;
                simVideo.classList.remove('hidden');
                simImg.classList.add('hidden');
            } else {
                simImg.src = url;
                simImg.classList.remove('hidden');
                simVideo.classList.add('hidden');
            }
        }
    });

    // Mobile View Toggle Tab (< lg)
    function switchMobileViewTab(tab) {
        const formCol = document.getElementById('aboutFormCol');
        const previewCol = document.getElementById('aboutPreviewCol');
        const btnForm = document.getElementById('mobileTabBtnForm');
        const btnPreview = document.getElementById('mobileTabBtnPreview');

        if (tab === 'form') {
            formCol.classList.remove('hidden');
            previewCol.classList.add('hidden');
            btnForm.style.backgroundColor = 'var(--admin-btn-primary-bg, #2F6B4F)';
            btnForm.style.color = '#FFFFFF';
            btnForm.classList.add('font-semibold');
            btnPreview.style.backgroundColor = 'transparent';
            btnPreview.style.color = 'var(--admin-text-sub, #555D58)';
            btnPreview.classList.remove('font-semibold');
        } else {
            previewCol.classList.remove('hidden');
            formCol.classList.add('hidden');
            btnPreview.style.backgroundColor = 'var(--admin-btn-primary-bg, #2F6B4F)';
            btnPreview.style.color = '#FFFFFF';
            btnPreview.classList.add('font-semibold');
            btnForm.style.backgroundColor = 'transparent';
            btnForm.style.color = 'var(--admin-text-sub, #555D58)';
            btnForm.classList.remove('font-semibold');
        }
    }

    // Reset pratinjau lengkap saat form dibatalkan
    function syncAllPreviews() {
        liveSyncText('previewLabel', document.getElementById('about_label')?.value || '');
        liveSyncText('previewHeadline', document.getElementById('cafe_story_headline')?.value || '');
        liveSyncText('previewP1', document.getElementById('cafe_story_p1')?.value || '');
        liveSyncText('previewP2', document.getElementById('cafe_story_p2')?.value || '');
        liveSyncText('previewQuote', document.getElementById('about_quote')?.value || '');
        liveSyncText('previewBtnText', document.getElementById('about_button_text')?.value || 'Jelajahi Pilihan Menu');
        const sel = document.getElementById('aboutLinkSelector');
        if (sel) handleLinkSelectorChange(sel.value);
        checkExtraModifiedStatus();
    }
</script>
@endpush
@endsection
