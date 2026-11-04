@extends('layouts.admin')
@section('title', 'Tambah Fasilitas Baru — Nucomu Cafe')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-10">
    
    {{-- 1. Header Halaman Standar (x-page-header dengan Tombol Kembali & Tautan Petunjuk) --}}
    <x-page-header title="Tambah Fasilitas Baru">
        <x-slot name="actions">
            <div class="flex items-center gap-2.5">
                <button type="button"
                        class="admin-help-link btn-press"
                        id="facilities-create-help-trigger"
                        aria-expanded="false"
                        aria-controls="facilities-create-help-panel-content"
                        onclick="toggleHelpPanel('facilities-create-help-panel')">
                    <i class="fa-regular fa-circle-question text-[13px]" aria-hidden="true"></i>
                    <span class="toggle-label" data-open="? Petunjuk" data-close="Tutup petunjuk">? Petunjuk</span>
                    <i class="fa-solid fa-chevron-down text-[10px] toggle-chevron" aria-hidden="true"></i>
                </button>

                <a href="{{ route('admin.facilities.index') }}" 
                   class="min-h-[36px] px-3.5 py-1.5 rounded-xl border text-[13px] font-semibold transition flex items-center gap-1.5 hover:bg-[var(--admin-bg-sidebar-hover,#F0F4F1)]"
                   style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                   title="Kembali ke daftar fasilitas">
                    <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </x-slot>

        {{-- Panel Petunjuk (x-help-panel) --}}
        <x-slot name="help">
            <x-help-panel id="facilities-create-help-panel" :trigger="false">
                <div class="space-y-3.5">
                    <div>
                        <h3 class="text-[15px] font-bold tracking-tight" style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                            Panduan Menambahkan Fasilitas Baru
                        </h3>
                        <p class="text-[12.5px] mt-0.5 m-0" style="color: var(--admin-text-sub, #555D58);">
                            Isi ketiga langkah di bawah ini untuk menampilkan fasilitas baru di carousel website kafe.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-[12.5px]">
                        <div class="p-3 rounded-xl border bg-white space-y-1" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="font-bold block" style="color: var(--admin-btn-primary-bg, #2F6B4F);">1. Nama &amp; Tombol</span>
                            <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Nama tombol (misal: "Indoor") muncul sebagai tombol filter carousel. Judul tampil di sisi kiri.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="font-bold block" style="color: var(--admin-btn-primary-bg, #2F6B4F);">2. Penjelasan &amp; Poin</span>
                            <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Tuliskan suasana ruangan dan minimal 1 keunggulan utama (disarankan 3&ndash;5 poin singkat).
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="font-bold block" style="color: var(--admin-btn-primary-bg, #2F6B4F);">3. Foto Fasilitas</span>
                            <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Foto 1 wajib diunggah (rasio 4:3, maks {{ $facilityPhotoLimits['formatted'] ?? '5 MB' }}). Foto 2 &amp; 3 opsional.
                            </p>
                        </div>
                    </div>
                </div>
            </x-help-panel>
        </x-slot>
    </x-page-header>

    {{-- Alert Kesalahan Input / Validasi --}}
    @if($errors->any() || session('error'))
        <div class="p-4 rounded-xl border bg-rose-50 border-rose-200 text-rose-900 text-[13px] shadow-xs space-y-1.5 anim-fade-up">
            <div class="font-bold flex items-center gap-2 text-rose-800">
                <i class="fa-solid fa-circle-xmark text-rose-600" aria-hidden="true"></i>
                <span>{{ session('error') ? 'Terjadi kesalahan saat menyimpan:' : 'Terdapat beberapa isian yang perlu diperiksa:' }}</span>
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

    {{-- Tab Switcher Khusus Layar HP (< lg) --}}
    <div class="lg:hidden flex items-center rounded-xl p-1 border gap-1 select-none"
         style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4);">
        <button type="button" 
                onclick="switchMobileTab('form')" 
                id="mobileTabBtnForm"
                class="flex-1 py-2 px-3 rounded-lg text-[13px] font-semibold text-center transition flex items-center justify-center gap-2"
                style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;">
            <i class="fa-solid fa-pen-to-square text-xs" aria-hidden="true"></i>
            <span>Isi Formulir</span>
        </button>
        <button type="button" 
                onclick="switchMobileTab('preview')" 
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
        <div class="lg:col-span-7 space-y-6" id="facilityFormCol">
            <form action="{{ route('admin.facilities.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  id="facilityForm" 
                  class="space-y-6">
                @csrf

                <input type="hidden" name="sort_order" value="{{ old('sort_order', $nextSortOrder) }}">

                {{-- KARTU LANGKAH 1: Nama fasilitas --}}
                <x-form-section step="1" 
                                title="Nama fasilitas" 
                                description="Tentukan nama tombol pilihan dan judul fasilitas yang tampil di website">
                    
                    {{-- 1a. Nama tombol pilihan --}}
                    <x-field for="tab_name"
                             label="Nama tombol pilihan"
                             :required="true"
                             hint="Tampil sebagai teks tombol di menu atas carousel fasilitas website. Contoh: Indoor, Outdoor, Musholla, VIP Room"
                             recommendation="Saran: 1–2 kata singkat (6–20 huruf)"
                             :counter="true"
                             :max="50">
                        <input type="text" 
                               name="tab_name" 
                               id="tab_name" 
                               value="{{ old('tab_name') }}" 
                               required 
                               autofocus
                               maxlength="50"
                               placeholder="Contoh: Indoor, Outdoor, Musholla"
                               oninput="syncFacilityTab(this.value)"
                               class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] font-semibold transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] uppercase tracking-wider"
                               style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    </x-field>

                    {{-- 1b. Judul fasilitas --}}
                    <x-field for="title"
                             label="Judul fasilitas"
                             :required="true"
                             hint="Tampil paling besar sebagai judul utama saat fasilitas ini dipilih di website. Contoh: Indoor Lounge Work Space"
                             recommendation="Saran: 20–40 huruf"
                             :counter="true"
                             :max="255">
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title') }}" 
                               required 
                               maxlength="255"
                               placeholder="Contoh: Indoor Lounge Work Space"
                               oninput="syncFacilityTitle(this.value)"
                               class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] font-bold transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                               style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    </x-field>

                    {{-- 1c. Subjudul fasilitas (Opsional) --}}
                    <x-field for="subtitle"
                             label="Subjudul (opsional)"
                             :required="false"
                             hint="Teks kecil pelengkap judul utama (misal: Workspace & Lounge, Garden Terrace, Kenyamanan Ibadah)."
                             recommendation="Saran: 15–30 huruf • Opsional"
                             :counter="true"
                             :max="255">
                        <input type="text" 
                               name="subtitle" 
                               id="subtitle" 
                               value="{{ old('subtitle') }}" 
                               maxlength="255"
                               placeholder="Contoh: Workspace & Lounge"
                               oninput="syncFacilitySubtitle(this.value)"
                               class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                               style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    </x-field>
                </x-form-section>

                {{-- KARTU LANGKAH 2: Penjelasan dan keunggulan --}}
                <x-form-section step="2" 
                                title="Penjelasan dan keunggulan" 
                                description="Tuliskan cerita suasana ruangan dan daftar sarana kenyamanan fasilitas">
                    
                    {{-- 2a. Penjelasan Singkat --}}
                    <x-field for="description"
                             label="Penjelasan singkat fasilitas"
                             :required="true"
                             hint="Cerita 2–3 kalimat yang menggambarkan kenyamanan, suasana, dan keistimewaan fasilitas ini di website."
                             recommendation="Saran: 100–300 huruf"
                             :counter="true"
                             :max="3000">
                        <textarea name="description" 
                                  id="description" 
                                  rows="3" 
                                  required 
                                  maxlength="3000"
                                  placeholder="Contoh: Area dalam ruangan ber-AC yang tenang dengan meja ergonomis, stopkontak melimpah di setiap sudut, serta koneksi internet cepat. Ideal untuk WFC maupun rapat santai..."
                                  oninput="syncFacilityDesc(this.value)"
                                  class="w-full px-3.5 py-2.5 rounded-xl border text-[13.5px] leading-relaxed transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] resize-none"
                                  style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">{{ old('description') }}</textarea>
                    </x-field>

                    {{-- 2b. Daftar Keunggulan Fasilitas --}}
                    <div class="space-y-2 pt-2 border-t" style="border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <label class="block text-[13.5px] font-semibold select-none" style="color: var(--admin-text-main, #1B1F1D);">
                                    Daftar keunggulan fasilitas <span class="text-rose-600 font-bold">*</span>
                                </label>
                                <p class="text-[12.5px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                    Poin-poin bercentang hijau di bawah cerita (minimal 1 poin, disarankan 3&ndash;5 poin).
                                </p>
                            </div>

                            <button type="button" 
                                    onclick="addFeatureRow()"
                                    class="min-h-[32px] px-3 py-1 rounded-lg border text-[12.5px] font-semibold transition flex items-center gap-1.5 cursor-pointer shrink-0 hover:bg-[var(--admin-bg-sidebar-hover,#F0F4F1)]"
                                    style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-btn-primary-bg, #2F6B4F);"
                                    title="Tambah baris keunggulan baru">
                                <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                <span>Tambah keunggulan</span>
                            </button>
                        </div>

                        {{-- Wadah Baris Keunggulan Dinamis --}}
                        <div id="featuresListContainer" class="space-y-2.5 pt-1">
                            @php
                                $initialFeatures = old('features', [
                                    'Suhu ruangan sejuk & sirkulasi udara bersih',
                                    'Pencahayaan hangat bebas silau untuk kenyamanan mata',
                                    'Stopkontak di setiap meja & Wi-Fi berkecepatan tinggi'
                                ]);
                            @endphp

                            @foreach($initialFeatures as $idx => $featVal)
                                <div class="feature-item-row flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-xs font-bold"
                                         style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                                        <i class="fa-solid fa-check" aria-hidden="true"></i>
                                    </div>
                                    <input type="text" 
                                           name="features[]" 
                                           value="{{ $featVal }}" 
                                           required
                                           maxlength="255"
                                           placeholder="Contoh: Stopkontak di setiap meja & Wi-Fi cepat"
                                           oninput="syncFacilityFeatures()"
                                           class="flex-1 px-3.5 py-2 rounded-xl border text-[13.5px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                                           style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                    
                                    <button type="button" 
                                            onclick="removeFeatureRow(this)"
                                            class="w-8 h-8 rounded-xl border flex items-center justify-center text-rose-600 hover:bg-rose-50 transition shrink-0 cursor-pointer"
                                            style="border-color: var(--admin-border-card, #E6E8E4);"
                                            title="Hapus baris ini">
                                        <i class="fa-regular fa-trash-can text-xs" aria-hidden="true"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-form-section>

                {{-- KARTU LANGKAH 3: Foto fasilitas (1-3 Foto) --}}
                <x-form-section step="3" 
                                title="Foto fasilitas" 
                                description="Unggah 1 sampai 3 foto beresolusi tajam untuk galeri carousel fasilitas">
                    
                    {{-- Panduan Format & Ukuran Foto --}}
                    <div class="p-3.5 rounded-xl border text-[13px] space-y-1.5"
                         style="background-color: var(--admin-bg-active, #E6F0EA); border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-circle-info" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                            <span>Panduan Format &amp; Batas Ukuran Foto</span>
                        </div>
                        <p class="text-[12.5px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                            Gunakan rasio <strong>4:3 (disarankan 1200 &times; 900 px)</strong> &bull; Format: JPG, PNG, atau WebP &bull; Maksimal <strong>{{ $facilityPhotoLimits['formatted'] ?? '5 MB' }}</strong> per foto.
                        </p>
                        <p class="text-[12px] m-0 text-emerald-900 pt-0.5 font-medium">
                            Foto 1 wajib diisi dan menjadi foto utama besar. Foto 2 dan 3 opsional sebagai gambar pendukung.
                        </p>
                    </div>

                    {{-- 3 Slot Foto Terstruktur --}}
                    <div class="space-y-4">
                        
                        {{-- Slot 1: Foto Utama (Wajib) --}}
                        <div class="p-4 rounded-xl border space-y-3"
                             style="background-color: var(--admin-bg-page, #F6F7F5); border-color: var(--admin-border-card, #E6E8E4);">
                            <div class="flex items-center justify-between">
                                <span class="text-[13.5px] font-bold" style="color: var(--admin-text-main, #1B1F1D);">
                                    Foto 1 (Utama) <span class="text-rose-600 font-bold">*</span>
                                </span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                    Wajib
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-center">
                                {{-- Area Dropzone / Pratinjau Foto --}}
                                <div class="sm:col-span-5">
                                    <div class="relative border-2 border-dashed rounded-xl aspect-[4/3] flex flex-col items-center justify-center p-2 text-center cursor-pointer transition overflow-hidden group bg-white"
                                         style="border-color: var(--admin-border-card, #E6E8E4);"
                                         onclick="document.getElementById('photo_file_0').click()">
                                        
                                        <input type="file" 
                                               name="photo_files[0]" 
                                               id="photo_file_0" 
                                               class="sr-only" 
                                               required 
                                               accept="image/jpeg,image/png,image/webp"
                                               data-max-bytes="{{ $facilityPhotoLimits['effective_bytes'] ?? 5242880 }}"
                                               data-allowed-ext="jpg,jpeg,png,webp"
                                               data-feedback-target="#photo_feedback_0"
                                               onchange="handlePhotoSlotChange(event, 0)">
                                        
                                        <img id="slot_preview_img_0" src="#" alt="Pratinjau Foto 1" class="hidden absolute inset-0 w-full h-full object-cover">
                                        
                                        <div id="slot_prompt_0" class="space-y-1 p-2">
                                            <i class="fa-solid fa-cloud-arrow-up text-base group-hover:scale-110 transition" style="color: var(--admin-btn-primary-bg, #2F6B4F);"></i>
                                            <span class="text-[12.5px] font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Pilih Foto Utama</span>
                                            <span class="text-[11px] block" style="color: var(--admin-text-muted, #7A847E);">Rasio 4:3 &bull; Maks 5 MB</span>
                                        </div>
                                    </div>
                                    <div id="photo_feedback_0" class="mt-1.5 text-left"></div>
                                </div>

                                {{-- Keterangan Foto --}}
                                <div class="sm:col-span-7 space-y-1.5">
                                    <label for="photo_caption_0" class="block text-[12.5px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                                        Keterangan foto 1:
                                    </label>
                                    <input type="text" 
                                           name="photo_captions[0]" 
                                           id="photo_caption_0" 
                                           value="{{ old('photo_captions.0') }}"
                                           placeholder="Contoh: Meja ergonomis & suasana asri"
                                           oninput="syncFacilityCaption(0, this.value)"
                                           class="w-full px-3.5 py-2 rounded-xl border text-[13px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                                           style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                    <p class="text-[11.5px] m-0" style="color: var(--admin-text-muted, #7A847E);">
                                        Tampil di atas foto utama sebagai label keterangan singkat.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Slot 2: Foto Pendukung 1 (Opsional) --}}
                        <div class="p-4 rounded-xl border space-y-3"
                             style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4);">
                            <div class="flex items-center justify-between">
                                <span class="text-[13.5px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                                    Foto 2 (Pendukung)
                                </span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium" style="background-color: var(--admin-bg-sidebar-hover,#F0F4F1); color: var(--admin-text-sub,#555D58);">
                                    Opsional
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-center">
                                <div class="sm:col-span-5">
                                    <div class="relative border-2 border-dashed rounded-xl aspect-[4/3] flex flex-col items-center justify-center p-2 text-center cursor-pointer transition overflow-hidden group bg-stone-50"
                                         style="border-color: var(--admin-border-card, #E6E8E4);"
                                         onclick="document.getElementById('photo_file_1').click()">
                                        
                                        <input type="file" 
                                               name="photo_files[1]" 
                                               id="photo_file_1" 
                                               class="sr-only" 
                                               accept="image/jpeg,image/png,image/webp"
                                               data-max-bytes="{{ $facilityPhotoLimits['effective_bytes'] ?? 5242880 }}"
                                               data-allowed-ext="jpg,jpeg,png,webp"
                                               data-feedback-target="#photo_feedback_1"
                                               onchange="handlePhotoSlotChange(event, 1)">
                                        
                                        <img id="slot_preview_img_1" src="#" alt="Pratinjau Foto 2" class="hidden absolute inset-0 w-full h-full object-cover">
                                        
                                        <div id="slot_prompt_1" class="space-y-1 p-2">
                                            <i class="fa-solid fa-cloud-arrow-up text-base group-hover:scale-110 transition" style="color: var(--admin-text-sub, #555D58);"></i>
                                            <span class="text-[12.5px] font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Pilih Foto 2</span>
                                            <span class="text-[11px] block" style="color: var(--admin-text-muted, #7A847E);">Rasio 4:3 &bull; Maks 5 MB</span>
                                        </div>
                                    </div>
                                    <div id="photo_feedback_1" class="mt-1.5 text-left"></div>
                                </div>

                                <div class="sm:col-span-7 space-y-1.5">
                                    <label for="photo_caption_1" class="block text-[12.5px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                                        Keterangan foto 2:
                                    </label>
                                    <input type="text" 
                                           name="photo_captions[1]" 
                                           id="photo_caption_1" 
                                           value="{{ old('photo_captions.1') }}"
                                           placeholder="Contoh: Espresso bar & counter seduh"
                                           oninput="syncFacilityCaption(1, this.value)"
                                           class="w-full px-3.5 py-2 rounded-xl border text-[13px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                                           style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                </div>
                            </div>
                        </div>

                        {{-- Slot 3: Foto Pendukung 2 (Opsional) --}}
                        <div class="p-4 rounded-xl border space-y-3"
                             style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4);">
                            <div class="flex items-center justify-between">
                                <span class="text-[13.5px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                                    Foto 3 (Pendukung)
                                </span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium" style="background-color: var(--admin-bg-sidebar-hover,#F0F4F1); color: var(--admin-text-sub,#555D58);">
                                    Opsional
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-center">
                                <div class="sm:col-span-5">
                                    <div class="relative border-2 border-dashed rounded-xl aspect-[4/3] flex flex-col items-center justify-center p-2 text-center cursor-pointer transition overflow-hidden group bg-stone-50"
                                         style="border-color: var(--admin-border-card, #E6E8E4);"
                                         onclick="document.getElementById('photo_file_2').click()">
                                        
                                        <input type="file" 
                                               name="photo_files[2]" 
                                               id="photo_file_2" 
                                               class="sr-only" 
                                               accept="image/jpeg,image/png,image/webp"
                                               data-max-bytes="{{ $facilityPhotoLimits['effective_bytes'] ?? 5242880 }}"
                                               data-allowed-ext="jpg,jpeg,png,webp"
                                               data-feedback-target="#photo_feedback_2"
                                               onchange="handlePhotoSlotChange(event, 2)">
                                        
                                        <img id="slot_preview_img_2" src="#" alt="Pratinjau Foto 3" class="hidden absolute inset-0 w-full h-full object-cover">
                                        
                                        <div id="slot_prompt_2" class="space-y-1 p-2">
                                            <i class="fa-solid fa-cloud-arrow-up text-base group-hover:scale-110 transition" style="color: var(--admin-text-sub, #555D58);"></i>
                                            <span class="text-[12.5px] font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Pilih Foto 3</span>
                                            <span class="text-[11px] block" style="color: var(--admin-text-muted, #7A847E);">Rasio 4:3 &bull; Maks 5 MB</span>
                                        </div>
                                    </div>
                                    <div id="photo_feedback_2" class="mt-1.5 text-left"></div>
                                </div>

                                <div class="sm:col-span-7 space-y-1.5">
                                    <label for="photo_caption_2" class="block text-[12.5px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                                        Keterangan foto 3:
                                    </label>
                                    <input type="text" 
                                           name="photo_captions[2]" 
                                           id="photo_caption_2" 
                                           value="{{ old('photo_captions.2') }}"
                                           placeholder="Contoh: Sudut apparel & cermin estetis"
                                           oninput="syncFacilityCaption(2, this.value)"
                                           class="w-full px-3.5 py-2 rounded-xl border text-[13px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                                           style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                </div>
                            </div>
                        </div>

                    </div>
                </x-form-section>

                {{-- SAKLAR STATUS TAMPIL DI WEBSITE --}}
                <div class="admin-card p-4 sm:p-5 rounded-2xl border"
                     style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4);">
                    <label class="flex items-center justify-between gap-3 cursor-pointer select-none">
                        <div class="space-y-0.5">
                            <span class="text-[13.5px] font-bold block" style="color: var(--admin-text-main, #1B1F1D);">
                                Tampilkan fasilitas ini di website
                            </span>
                            <p class="text-[12.5px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Fasilitas ini langsung dapat dilihat dan dipilih oleh pengunjung di carousel halaman depan.
                            </p>
                        </div>

                        <div class="relative shrink-0">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   id="is_active" 
                                   {{ old('is_active', true) ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[var(--admin-btn-primary-bg,#2F6B4F)]"></div>
                        </div>
                    </label>
                </div>

                {{-- BARIS TOMBOL AKSI FORMULIR (x-form-actions: Non-sticky, mengikuti alur halaman) --}}
                <x-form-actions formId="facilityForm" 
                                saveText="Simpan fasilitas" 
                                cancelText="Batalkan" 
                                unsavedMessage="Ada perubahan yang belum disimpan" />

            </form>
        </div>

        {{-- KOLOM CONTOH TAMPILAN WEBSITE (5 Kolom pada Desktop, Sticky) --}}
        <div class="lg:col-span-5 sticky top-20 space-y-4" id="facilityPreviewCol">
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
                        Sesuai Carousel Web
                    </span>
                </div>

                {{-- Simulasi Bagian Fasilitas di Web Customer Nucomu --}}
                <div class="rounded-2xl border p-4 sm:p-5 space-y-4 select-none overflow-hidden"
                     style="background-color: #FAFAFA; border-color: var(--admin-border-card, #E6E8E4);">
                    
                    {{-- Simulasi Tombol Tab Carousel --}}
                    <div class="flex items-center gap-2 border-b pb-3 overflow-x-auto"
                         style="border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider text-white shadow-xs"
                             style="background-color: #191919;"
                             id="simTabBadge">
                            INDOOR
                        </div>
                        <div class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider text-stone-500 bg-stone-200/60">
                            OUTDOOR
                        </div>
                        <div class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider text-stone-500 bg-stone-200/60">
                            MUSHOLLA
                        </div>
                    </div>

                    {{-- Konten Carousel: Teks & Foto --}}
                    <div class="space-y-3.5">
                        
                        {{-- Foto Utama Carousel --}}
                        <div class="w-full aspect-[4/3] rounded-xl overflow-hidden border bg-black relative shadow-xs"
                             style="border-color: var(--admin-border-card, #E6E8E4);">
                            <img id="simPhotoImg" 
                                 src="{{ asset('images/nucomu/nucomu_story_2.png') }}" 
                                 alt="Foto Fasilitas" 
                                 class="w-full h-full object-cover">
                            
                            {{-- Caption Overlay --}}
                            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent p-2.5 text-white">
                                <span id="simPhotoCaption" class="text-[11px] font-semibold block truncate">
                                    Meja Ergonomis &amp; Suasana Asri
                                </span>
                            </div>
                        </div>

                        {{-- Judul & Subjudul --}}
                        <div class="space-y-0.5">
                            <span id="simSubtitle" class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 block">
                                Workspace &amp; Lounge
                            </span>
                            <h4 id="simTitle" class="text-[16px] font-bold text-stone-900 m-0 leading-snug"
                                style="font-family: var(--font-heading);">
                                Indoor Lounge Work Space
                            </h4>
                        </div>

                        {{-- Cerita Penjelasan --}}
                        <p id="simDesc" class="text-[12px] leading-relaxed text-stone-600 m-0">
                            Area dalam ruangan ber-AC yang tenang dengan meja ergonomis, stopkontak melimpah di setiap sudut, serta koneksi internet cepat.
                        </p>

                        {{-- Daftar Keunggulan Bercentang --}}
                        <div class="space-y-1.5 pt-2 border-t" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500 block">
                                Keunggulan Utama:
                            </span>
                            <ul id="simFeaturesList" class="space-y-1 text-[12px] text-stone-700 m-0 p-0 list-none">
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-check text-[10px] text-emerald-600 shrink-0"></i>
                                    <span>Suhu ruangan sejuk &amp; sirkulasi udara bersih</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-check text-[10px] text-emerald-600 shrink-0"></i>
                                    <span>Pencahayaan hangat bebas silau untuk kenyamanan mata</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-check text-[10px] text-emerald-600 shrink-0"></i>
                                    <span>Stopkontak di setiap meja &amp; Wi-Fi berkecepatan tinggi</span>
                                </li>
                            </ul>
                        </div>

                    </div>

                </div>

                <p class="text-[12px] m-0 text-center" style="color: var(--admin-text-sub, #555D58);">
                    Tampilan di atas mensimulasikan slide fasilitas di carousel beranda web.
                </p>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="{{ asset('js/file-upload-validator.js') }}"></script>
<script>
    // Live Synchronizers untuk Pratinjau
    function syncFacilityTab(val) {
        const badge = document.getElementById('simTabBadge');
        if (badge) badge.textContent = (val && val.trim()) ? val.toUpperCase() : 'FASILITAS';
    }

    function syncFacilityTitle(val) {
        const el = document.getElementById('simTitle');
        if (el) el.textContent = (val && val.trim()) ? val : 'Judul Fasilitas';
    }

    function syncFacilitySubtitle(val) {
        const el = document.getElementById('simSubtitle');
        if (el) el.textContent = (val && val.trim()) ? val : '';
    }

    function syncFacilityDesc(val) {
        const el = document.getElementById('simDesc');
        if (el) el.textContent = (val && val.trim()) ? val : 'Penjelasan singkat fasilitas kafe...';
    }

    function syncFacilityCaption(slotIdx, val) {
        if (slotIdx === 0) {
            const el = document.getElementById('simPhotoCaption');
            if (el) el.textContent = (val && val.trim()) ? val : 'Foto Utama Fasilitas';
        }
    }

    function syncFacilityFeatures() {
        const container = document.getElementById('simFeaturesList');
        if (!container) return;
        const inputs = document.querySelectorAll('input[name="features[]"]');
        let html = '';
        inputs.forEach(input => {
            const val = input.value.trim();
            if (val) {
                html += `<li class="flex items-center gap-2"><i class="fa-solid fa-check text-[10px] text-emerald-600 shrink-0"></i><span>${val}</span></li>`;
            }
        });
        container.innerHTML = html || '<li class="text-stone-400 italic">Belum ada keunggulan</li>';
    }

    // Tambah Baris Keunggulan
    function addFeatureRow() {
        const container = document.getElementById('featuresListContainer');
        if (!container) return;

        const row = document.createElement('div');
        row.className = 'feature-item-row flex items-center gap-2 anim-fade-up';
        row.innerHTML = `
            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-xs font-bold"
                 style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                <i class="fa-solid fa-check" aria-hidden="true"></i>
            </div>
            <input type="text" 
                   name="features[]" 
                   value="" 
                   required
                   maxlength="255"
                   placeholder="Tuliskan keunggulan lainnya..."
                   oninput="syncFacilityFeatures()"
                   class="flex-1 px-3.5 py-2 rounded-xl border text-[13.5px] transition focus:outline-none focus:ring-2 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)]"
                   style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
            <button type="button" 
                    onclick="removeFeatureRow(this)"
                    class="w-8 h-8 rounded-xl border flex items-center justify-center text-rose-600 hover:bg-rose-50 transition shrink-0 cursor-pointer"
                    style="border-color: var(--admin-border-card, #E6E8E4);"
                    title="Hapus baris ini">
                <i class="fa-regular fa-trash-can text-xs" aria-hidden="true"></i>
            </button>
        `;
        container.appendChild(row);
        row.querySelector('input').focus();
        
        // Trigger dirty checker
        const f = document.getElementById('facilityForm');
        if (f) f.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function removeFeatureRow(btn) {
        const rows = document.querySelectorAll('.feature-item-row');
        if (rows.length <= 1) {
            alert('Minimal 1 poin keunggulan fasilitas harus dipertahankan.');
            return;
        }
        const row = btn.closest('.feature-item-row');
        if (row) {
            row.remove();
            syncFacilityFeatures();
            const f = document.getElementById('facilityForm');
            if (f) f.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    // Penanganan Pratinjau Foto Slot
    function handlePhotoSlotChange(e, slotIdx) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(evt) {
            const previewImg = document.getElementById('slot_preview_img_' + slotIdx);
            const promptBox = document.getElementById('slot_prompt_' + slotIdx);

            if (previewImg) {
                previewImg.src = evt.target.result;
                previewImg.classList.remove('hidden');
            }
            if (promptBox) promptBox.classList.add('hidden');

            // Jika slot 0 (foto utama), sinkronkan langsung ke simulasi carousel kanan
            if (slotIdx === 0) {
                const simImg = document.getElementById('simPhotoImg');
                if (simImg) simImg.src = evt.target.result;
            }
        };
        reader.readAsDataURL(file);
    }

    // Toggle Tab Layar HP (< lg)
    function switchMobileTab(tab) {
        const formCol = document.getElementById('facilityFormCol');
        const previewCol = document.getElementById('facilityPreviewCol');
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

    // Inisialisasi Sinkronisasi Pratinjau Awal
    document.addEventListener('DOMContentLoaded', function() {
        syncFacilityTab(document.getElementById('tab_name')?.value || '');
        syncFacilityTitle(document.getElementById('title')?.value || '');
        syncFacilitySubtitle(document.getElementById('subtitle')?.value || '');
        syncFacilityDesc(document.getElementById('description')?.value || '');
        syncFacilityFeatures();
    });
</script>
@endpush
@endsection