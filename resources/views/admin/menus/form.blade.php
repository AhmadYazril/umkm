@extends('layouts.admin')
@php
    $isEdit = isset($menu);
    $actionUrl = $isEdit ? route('admin.menus.update', $menu->id) : route('admin.menus.store');
    $currentImg = $isEdit ? $menu->image_url : null;
    $hasExistingImg = $isEdit && !empty($menu->image);

    $initialOptions = old('options');
    if (! is_array($initialOptions)) {
        $loadedOptions = $isEdit
            ? ($menu->relationLoaded('options') ? $menu->options : $menu->options()->get())
            : collect();
        $initialOptions = $loadedOptions->map(fn ($opt) => [
            'name' => $opt->name,
            'type' => $opt->type ?? 'variant',
            'price' => (int) ($opt->price ?? 0),
        ])->values()->all();
    } else {
        $initialOptions = array_values($initialOptions);
    }
@endphp

@section('title', ($isEdit ? 'Edit Menu: ' . $menu->name : 'Tambah Menu Baru') . ' — Nucomu Cafe')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">
    
    {{-- 1. Header Halaman Standar (x-page-header dengan Judul Tenang, Tombol Kembali & ? Petunjuk) --}}
    <x-page-header :title="$isEdit ? 'Edit Menu: ' . $menu->name : 'Tambah Menu Baru'">
        <x-slot name="actions">
            <div class="flex items-center gap-2.5">
                {{-- Tombol Petunjuk --}}
                <button type="button"
                        class="admin-help-link btn-press"
                        id="menu-form-help-trigger"
                        aria-expanded="false"
                        aria-controls="menu-form-help-panel-content"
                        onclick="toggleHelpPanel('menu-form-help-panel')">
                    <i class="fa-regular fa-circle-question text-[13px]" aria-hidden="true"></i>
                    <span class="toggle-label" data-open="? Petunjuk" data-close="Tutup petunjuk">? Petunjuk</span>
                    <i class="fa-solid fa-chevron-down text-[10px] toggle-chevron" aria-hidden="true"></i>
                </button>

                {{-- Tombol Kembali --}}
                <a href="{{ route('admin.menus.index') }}"
                   class="min-h-[36px] px-3.5 py-1.5 rounded-xl border text-[13px] font-semibold transition flex items-center gap-1.5 hover:bg-[#F0F4F1] cursor-pointer"
                   style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                   title="Kembali ke katalog menu">
                    <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </x-slot>

        {{-- Panel Petunjuk (x-help-panel) --}}
        <x-slot name="help">
            <x-help-panel id="menu-form-help-panel" :trigger="false">
                <div class="space-y-3.5">
                    <div>
                        <h3 class="text-[15px] font-bold tracking-tight" style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                            Panduan {{ $isEdit ? 'Mengedit' : 'Menambahkan' }} Sajian Menu
                        </h3>
                        <p class="text-[12.5px] mt-0.5 m-0" style="color: var(--admin-text-sub, #555D58);">
                            Isi formulir 4 langkah di bawah ini. Tampilan di sisi kanan akan berubah langsung saat Anda mengetik.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-[12.5px]">
                        <div class="p-3 rounded-xl border bg-white space-y-1" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="font-bold block" style="color: var(--admin-btn-primary-bg, #2F6B4F);">1. Nama &amp; Kategori</span>
                            <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Tuliskan nama sajian yang jelas dan pilih kategori yang pas. Anda juga dapat membuat kategori baru langsung di formulir ini.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="font-bold block" style="color: var(--admin-btn-primary-bg, #2F6B4F);">2. Harga &amp; Penjelasan</span>
                            <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Masukkan angka harga rupiah tanpa titik. Tulis 1&ndash;2 kalimat singkat yang menggugah selera tentang cita rasa sajian.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="font-bold block" style="color: var(--admin-btn-primary-bg, #2F6B4F);">3. Foto &amp; Status</span>
                            <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Unggah foto kotak 1:1 (disarankan 800 &times; 800 px). Atur status Tersedia dan apakah dijadikan menu andalan di beranda.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="font-bold block" style="color: var(--admin-btn-primary-bg, #2F6B4F);">4. Varian &amp; Opsi</span>
                            <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Tambahkan pilihan penyajian seperti Hot/Iced, ukuran, level gula, atau topping ekstra beserta tambahan harga bila ada.
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
                onclick="switchMenuFormMobileTab('form')"
                id="menuTabBtnForm"
                class="flex-1 py-2 px-3 rounded-lg text-[13px] font-semibold text-center transition flex items-center justify-center gap-2"
                style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;">
            <i class="fa-solid fa-pen-to-square text-xs" aria-hidden="true"></i>
            <span>Isi Formulir</span>
        </button>
        <button type="button"
                onclick="switchMenuFormMobileTab('preview')"
                id="menuTabBtnPreview"
                class="flex-1 py-2 px-3 rounded-lg text-[13px] font-medium text-center transition flex items-center justify-center gap-2"
                style="color: var(--admin-text-sub, #555D58);">
            <i class="fa-solid fa-eye text-xs" aria-hidden="true"></i>
            <span>Pratinjau Kartu</span>
        </button>
    </div>

    {{-- Main Grid: Kolom Formulir di Kiri & Pratinjau Tampilan Kartu di Kanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">
        
        {{-- =========================================================================
             KOLOM FORMULIR (7 Kolom pada Desktop)
             ========================================================================= --}}
        <div class="lg:col-span-7 space-y-6" id="menuFormCol">
            <form action="{{ $actionUrl }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="menuForm"
                  class="space-y-6">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <input type="hidden" name="action" id="formSubmitAction" value="">
                <input type="hidden" name="sort_order" value="{{ old('sort_order', $menu->sort_order ?? $nextSortOrder ?? 1) }}">
                <input type="hidden" name="remove_image" id="removeImageInput" value="0">

                {{-- KARTU LANGKAH 1: NAMA DAN KATEGORI --}}
                <x-form-section step="1"
                                title="Nama dan kategori"
                                description="Tentukan nama sajian dan kelompok kategori di katalog website">
                    
                    {{-- 1a. Nama Menu --}}
                    <x-field for="name"
                             label="Nama menu sajian"
                             :required="true"
                             hint="Tuliskan nama sajian yang jelas dan menarik. Contoh: Iced Palm Sugar Latte, Classic Tiramisu, Avocado Toast"
                             recommendation="Saran: 2–5 kata ringkas"
                             :counter="true"
                             :max="255">
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $menu->name ?? '') }}"
                               required
                               autofocus
                               maxlength="255"
                               placeholder="Contoh: Iced Palm Sugar Latte"
                               oninput="syncMenuPreviewName(this.value)"
                               class="w-full px-3.5 py-2 rounded-xl border text-[13.5px] font-bold transition focus:outline-none focus:ring-2 focus:ring-[#2F6B4F]"
                               style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    </x-field>

                    {{-- 1b. Kategori Sajian dengan Quick Add Dropdown --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="category_id" class="block text-[13px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                                Kategori sajian <span class="text-rose-600 font-bold">*</span>
                            </label>
                            
                            {{-- Tombol Toggle Tambah Kategori Baru Cepat --}}
                            <button type="button"
                                    onclick="toggleQuickCategoryInline()"
                                    id="btnToggleQuickCategory"
                                    class="text-xs font-semibold text-emerald-800 hover:text-emerald-900 inline-flex items-center gap-1 cursor-pointer">
                                <i class="fa-solid fa-plus text-[10px]" id="iconQuickCat"></i>
                                <span id="textQuickCat">+ Kategori baru</span>
                            </button>
                        </div>

                        {{-- Dropdown Kategori --}}
                        <select name="category_id"
                                id="category_id"
                                required
                                onchange="syncMenuPreviewCategory(this.options[this.selectedIndex].text)"
                                class="w-full px-3.5 py-2.5 rounded-xl border text-[13px] font-semibold transition focus:outline-none focus:ring-2 focus:ring-[#2F6B4F]"
                                style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                            <option value="" disabled {{ !old('category_id', $menu->category_id ?? '') ? 'selected' : '' }}>-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11.5px] m-0" style="color: var(--admin-text-sub, #555D58);">
                            Kelompokkan menu agar pengunjung mudah memilih tab sajian di website.
                        </p>

                        {{-- Inline Quick Add Category Box (Tersembunyi secara Default) --}}
                        <div id="quickCategoryInlineBox" class="hidden p-3 rounded-xl border bg-[#F7F8F6] space-y-2 anim-fade-up"
                             style="border-color: var(--admin-border-card, #E6E8E4);">
                            <div class="text-[12px] font-bold" style="color: var(--admin-text-main, #1B1F1D);">
                                Buat Kategori Baru Cepat:
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text"
                                       id="inlineNewCategoryName"
                                       placeholder="Nama kategori baru (misal: Manual Brew)"
                                       class="flex-1 px-3 py-1.5 rounded-lg border text-xs bg-white focus:outline-none focus:border-[#2F6B4F]"
                                       style="border-color: var(--admin-border-card, #E6E8E4);">
                                <button type="button"
                                        onclick="submitQuickCategoryAJAX()"
                                        id="btnSubmitInlineCat"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition shadow-xs cursor-pointer btn-press"
                                        style="background-color: var(--admin-btn-primary-bg, #2F6B4F);">
                                    Simpan
                                </button>
                            </div>
                            <p id="inlineCatFeedback" class="text-[11px] m-0 hidden text-emerald-800 font-medium"></p>
                        </div>
                    </div>

                </x-form-section>

                {{-- KARTU LANGKAH 2: HARGA DAN PENJELASAN --}}
                <x-form-section step="2"
                                title="Harga dan penjelasan"
                                description="Atur harga jual dalam rupiah dan catatan rasa menggugah selera">
                    
                    {{-- 2a. Harga Menu dengan Prefix Rp & Preset --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="price" class="block text-[13px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                                Harga sajian (Rupiah) <span class="text-rose-600 font-bold">*</span>
                            </label>
                            <span class="text-[11.5px]" style="color: var(--admin-text-sub, #555D58);">Pilihan harga cepat:</span>
                        </div>

                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-400 select-none pointer-events-none">Rp</span>
                            <input type="number"
                                   name="price"
                                   id="price"
                                   value="{{ old('price', $menu->price ?? '') }}"
                                   required
                                   min="0"
                                   step="500"
                                   placeholder="Contoh: 28000"
                                   oninput="syncMenuPreviewPrice(this.value)"
                                   class="w-full px-3.5 py-2 pl-10 rounded-xl border text-[14px] font-bold tabular-nums transition focus:outline-none focus:ring-2 focus:ring-[#2F6B4F]"
                                   style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                        </div>
                        <p class="text-[11.5px] m-0" style="color: var(--admin-text-sub, #555D58);">
                            Ketik hanya angka bulat tanpa titik (misal: 25000). Sistem otomatis menampilkan format <strong class="text-emerald-800">Rp 25.000</strong>.
                        </p>

                        {{-- Tombol Preset Harga Cepat --}}
                        <div class="flex flex-wrap items-center gap-1.5 pt-1">
                            @foreach([15000, 18000, 22000, 25000, 28000, 32000, 35000] as $preset)
                                <button type="button"
                                        onclick="setQuickPrice({{ $preset }})"
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold tabular-nums border bg-white hover:bg-[#F0F4F1] transition cursor-pointer"
                                        style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                    {{ number_format($preset, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2b. Penjelasan Singkat / Catatan Rasa --}}
                    <x-field for="description"
                             label="Penjelasan sajian / Catatan rasa"
                             :required="false"
                             hint="Tuliskan 1–2 kalimat singkat tentang cita rasa, aroma racikan, atau bahan istimewa sajian ini di website."
                             recommendation="Saran: 10–25 kata menggugah selera"
                             :counter="true"
                             :max="1500">
                        <textarea name="description"
                                  id="description"
                                  rows="3"
                                  maxlength="1500"
                                  placeholder="Contoh: Espresso house blend dipadu susu segar dingin dan kelembutan manis gula aren alami..."
                                  oninput="syncMenuPreviewDesc(this.value)"
                                  class="w-full px-3.5 py-2.5 rounded-xl border text-[13px] leading-relaxed transition resize-none focus:outline-none focus:ring-2 focus:ring-[#2F6B4F]"
                                  style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">{{ old('description', $menu->description ?? '') }}</textarea>
                    </x-field>

                </x-form-section>

                {{-- KARTU LANGKAH 3: FOTO DAN STATUS --}}
                <x-form-section step="3"
                                title="Foto dan status"
                                description="Unggah foto sajian rasio kotak 1:1 serta atur ketersediaan di website">
                    
                    {{-- 3a. Upload Foto 1:1 --}}
                    <div class="space-y-3">
                        <label class="block text-[13px] font-semibold" style="color: var(--admin-text-main, #1B1F1D);">
                            Foto sajian menu (Rasio 1:1)
                        </label>

                        {{-- Panduan Ukuran & Format Dinamis Sesuai Server --}}
                        <div class="p-3.5 rounded-xl border space-y-1.5"
                             style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); border-color: var(--admin-border-card, #E6E8E4);">
                            <div class="font-semibold text-xs flex items-center gap-1.5 text-emerald-900">
                                <i class="fa-solid fa-camera text-xs" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                                <span>Panduan Format &amp; Ukuran Foto</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-1 text-[11.5px]">
                                <div>
                                    <span class="font-semibold block text-emerald-950">Rasio Foto:</span>
                                    <span style="color: var(--admin-text-sub, #555D58);">1:1 (kotak, disarankan <strong>800 &times; 800 px</strong>)</span>
                                </div>
                                <div>
                                    <span class="font-semibold block text-emerald-950">Format File:</span>
                                    <span style="color: var(--admin-text-sub, #555D58);">JPG, JPEG, PNG, atau WebP</span>
                                </div>
                                <div>
                                    <span class="font-semibold block text-emerald-950">Batas Ukuran Server:</span>
                                    <span style="color: var(--admin-text-sub, #555D58);">Maksimal <strong>{{ $menuPhotoLimits['formatted'] ?? '1 MB' }}</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- Dropzone Foto Rasio Kotak --}}
                        <div class="flex flex-col sm:flex-row items-center gap-4 pt-1">
                            <div onclick="document.getElementById('menuImageInput').click()"
                                 class="relative aspect-square w-48 sm:w-52 rounded-2xl border-2 border-dashed flex flex-col items-center justify-center p-3 text-center cursor-pointer transition overflow-hidden group bg-stone-50 hover:border-[#2F6B4F]"
                                 style="border-color: var(--admin-border-card, #E6E8E4);">
                                
                                <input type="file"
                                       name="image"
                                       id="menuImageInput"
                                       class="sr-only"
                                       accept="image/jpeg,image/png,image/webp"
                                       data-max-bytes="{{ $menuPhotoLimits['effective_bytes'] ?? 1048576 }}"
                                       data-allowed-ext="jpg,jpeg,png,webp"
                                       data-feedback-target="#menuImageFeedback"
                                       onchange="handleMenuPhotoChange(event)">
                                
                                {{-- Pratinjau Foto --}}
                                <img id="menuFormPreviewImg"
                                     src="{{ $currentImg ?? '' }}"
                                     alt="Pratinjau Foto Menu"
                                     class="{{ $hasExistingImg ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover">
                                
                                {{-- Prompt / Placeholder jika belum ada foto --}}
                                <div id="menuFormPrompt" class="{{ $hasExistingImg ? 'hidden' : '' }} space-y-1.5 p-3 pointer-events-none">
                                    <div class="w-10 h-10 rounded-xl bg-white border flex items-center justify-center mx-auto shadow-2xs text-gray-500 group-hover:scale-105 transition"
                                         style="border-color: var(--admin-border-card, #E6E8E4);">
                                        <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                    </div>
                                    <span class="text-xs font-bold block" style="color: var(--admin-text-main, #1B1F1D);">Pilih Foto 1:1</span>
                                    <span class="text-[10px] text-gray-500 block">Maks. {{ $menuPhotoLimits['formatted'] ?? '1 MB' }}</span>
                                </div>

                                {{-- Overlay Ganti Foto saat Hover --}}
                                <div id="menuFormOverlay" class="{{ $hasExistingImg ? '' : 'hidden' }} absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center text-white text-xs font-bold gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-camera"></i>
                                    <span>Ganti Foto</span>
                                </div>
                            </div>

                            {{-- Tombol Ganti / Hapus Foto --}}
                            <div class="space-y-2 text-left sm:flex-1">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="document.getElementById('menuImageInput').click()"
                                            class="px-3.5 py-1.5 rounded-xl text-xs font-semibold border bg-white hover:bg-gray-50 transition cursor-pointer"
                                            style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                        <i class="fa-solid fa-image text-[10px] mr-1" aria-hidden="true"></i>
                                        <span>Pilih / Ganti Foto</span>
                                    </button>

                                    <button type="button"
                                            id="btnRemoveMenuImage"
                                            onclick="removeMenuImageAction()"
                                            class="{{ $hasExistingImg ? '' : 'hidden' }} px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-700 hover:bg-rose-50 transition cursor-pointer">
                                        <i class="fa-solid fa-trash-can text-[10px] mr-1" aria-hidden="true"></i>
                                        <span>Hapus Foto</span>
                                    </button>
                                </div>
                                <p class="text-[11.5px] leading-relaxed m-0 text-gray-500">
                                    Foto kotak memastikan sajian tampil rapi di seluruh daftar menu, kartu visual, dan keranjang pesanan website.
                                </p>
                                <div id="menuImageFeedback" class="text-left text-xs"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 3b. Menu Andalan Beranda (★) --}}
                    <div class="p-4 rounded-2xl border" style="border-color: var(--admin-border-card, #E6E8E4);">
                        <label class="flex items-center justify-between gap-3 cursor-pointer select-none">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-star text-amber-500 text-xs" aria-hidden="true"></i>
                                    <span class="text-[13.5px] font-bold" style="color: var(--admin-text-main, #1B1F1D);">
                                        Jadikan menu andalan di halaman depan
                                    </span>
                                </div>
                                <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                    Menu andalan diprioritaskan tampil pada 8 sajian pilihan di beranda (homepage) website kafe.
                                </p>
                            </div>

                            <div class="relative shrink-0">
                                <input type="checkbox"
                                       name="is_featured"
                                       value="1"
                                       id="is_featured"
                                       {{ old('is_featured', $menu->is_featured ?? ($menu->is_best_seller ?? false)) ? 'checked' : '' }}
                                       onchange="syncMenuPreviewFeatured(this.checked)"
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[var(--admin-btn-primary-bg,#2F6B4F)]"></div>
                            </div>
                        </label>
                    </div>

                </x-form-section>

                {{-- KARTU LANGKAH 4: VARIAN & OPSI TAMBAHAN --}}
                <div id="menu-variants-section">
                    <x-form-section step="4"
                                    title="Varian dan opsi tambahan"
                                    description="Opsional — atur pilihan penyajian (Hot / Iced), ukuran porsi, tingkat manis, atau tambahan ekstra">
                        <input type="hidden" name="sync_options" value="1">

                        {{-- Preset Cepat --}}
                        <div class="p-3.5 rounded-xl border bg-[#F7F8F6] space-y-2" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-xs font-bold" style="color: var(--admin-text-main, #1B1F1D);">
                                    Tambah cepat paket varian umum:
                                </span>
                                <span class="text-[11px]" style="color: var(--admin-text-sub, #555D58);">
                                    Klik untuk mengisi otomatis tanpa duplikat
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button type="button"
                                        onclick='addOptionPresets([{name:"Hot",type:"variant",price:0},{name:"Iced",type:"variant",price:0}])'
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold border bg-white hover:bg-[#E6F0EA] hover:border-[#2F6B4F] transition cursor-pointer">
                                    + Hot / Iced
                                </button>
                                <button type="button"
                                        onclick='addOptionPresets([{name:"Regular",type:"variant",price:0},{name:"Large",type:"variant",price:5000}])'
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold border bg-white hover:bg-[#E6F0EA] hover:border-[#2F6B4F] transition cursor-pointer">
                                    + Regular / Large (+Rp 5.000)
                                </button>
                                <button type="button"
                                        onclick='addOptionPresets([{name:"0% (Tanpa Gula)",type:"addon",price:0},{name:"50% (Sedang)",type:"addon",price:0},{name:"100% (Penuh)",type:"addon",price:0}])'
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold border bg-white hover:bg-[#E6F0EA] hover:border-[#2F6B4F] transition cursor-pointer">
                                    + Level Gula
                                </button>
                                <button type="button"
                                        onclick='addOptionPresets([{name:"Extra Shot",type:"addon",price:5000}])'
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold border bg-white hover:bg-[#E6F0EA] hover:border-[#2F6B4F] transition cursor-pointer">
                                    + Extra Shot (+Rp 5.000)
                                </button>
                            </div>
                        </div>

                        {{-- Empty State jika belum ada varian --}}
                        <div id="menuOptionsEmptyState"
                             class="{{ count($initialOptions) > 0 ? 'hidden' : '' }} p-4 rounded-xl border border-dashed text-center space-y-1 bg-stone-50/60"
                             style="border-color: var(--admin-border-card, #D6DDD7);">
                            <p class="text-xs font-semibold m-0" style="color: var(--admin-text-main, #1B1F1D);">
                                Belum ada varian untuk sajian ini
                            </p>
                            <p class="text-[11.5px] m-0" style="color: var(--admin-text-sub, #555D58);">
                                Klik tombol paket cepat di atas atau <strong>"+ Tambah Varian"</strong> di bawah bila menu memiliki pilihan varian.
                            </p>
                        </div>

                        {{-- Daftar Baris Varian --}}
                        <div id="menuOptionsContainer" class="space-y-2.5">
                            @foreach($initialOptions as $optIndex => $opt)
                                @php
                                    $optType = ($opt['type'] ?? 'variant') === 'addon' ? 'addon' : 'variant';
                                    $optName = (string) ($opt['name'] ?? '');
                                    $optPrice = (int) ($opt['price'] ?? 0);
                                @endphp
                                <div class="menu-option-row p-3 rounded-xl border bg-white grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end"
                                     style="border-color: var(--admin-border-card, #E6E8E4);">
                                    <div class="sm:col-span-4 space-y-1">
                                        <label class="block text-[11px] font-semibold" style="color: var(--admin-text-sub, #555D58);">
                                            Jenis Opsi
                                        </label>
                                        <select name="options[{{ $optIndex }}][type]"
                                                onchange="syncMenuPreviewOptions(); markFormAsDirty();"
                                                class="menu-option-type w-full px-2.5 py-2 rounded-lg border text-xs font-semibold bg-[#F7F8F6] focus:bg-white focus:outline-none focus:border-[#2F6B4F]"
                                                style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                            <option value="variant" {{ $optType === 'variant' ? 'selected' : '' }}>Varian Utama (Suhu / Ukuran)</option>
                                            <option value="addon" {{ $optType === 'addon' ? 'selected' : '' }}>Tambahan / Level Gula</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-4 space-y-1">
                                        <label class="block text-[11px] font-semibold" style="color: var(--admin-text-sub, #555D58);">
                                            Nama Varian / Opsi
                                        </label>
                                        <input type="text"
                                               name="options[{{ $optIndex }}][name]"
                                               value="{{ $optName }}"
                                               maxlength="100"
                                               placeholder="Contoh: Iced, Large, Less Sugar"
                                               oninput="syncMenuPreviewOptions(); markFormAsDirty();"
                                               class="menu-option-name w-full px-3 py-2 rounded-lg border text-xs font-semibold bg-white focus:outline-none focus:border-[#2F6B4F]"
                                               style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                    </div>
                                    <div class="sm:col-span-3 space-y-1">
                                        <label class="block text-[11px] font-semibold" style="color: var(--admin-text-sub, #555D58);">
                                            Tambahan Harga
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-2 text-[11px] font-semibold text-gray-400 pointer-events-none">+Rp</span>
                                            <input type="number"
                                                   name="options[{{ $optIndex }}][price]"
                                                   value="{{ $optPrice }}"
                                                   min="0"
                                                   step="500"
                                                   oninput="markFormAsDirty();"
                                                   class="menu-option-price w-full pl-9 pr-2.5 py-2 rounded-lg border text-xs font-bold tabular-nums bg-white focus:outline-none focus:border-[#2F6B4F]"
                                                   style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                        </div>
                                    </div>
                                    <div class="sm:col-span-1 flex justify-end">
                                        <button type="button"
                                                onclick="removeMenuOptionRow(this)"
                                                class="w-9 h-9 rounded-lg border flex items-center justify-center text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                style="border-color: var(--admin-border-card, #E6E8E4);"
                                                title="Hapus baris varian ini"
                                                aria-label="Hapus varian">
                                            <i class="fa-solid fa-trash-can text-xs" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                            <button type="button"
                                    onclick="addMenuOptionRow()"
                                    class="px-3.5 py-2 rounded-xl text-xs font-semibold border bg-white hover:bg-[#F0F4F1] text-emerald-900 transition inline-flex items-center gap-1.5 cursor-pointer"
                                    style="border-color: var(--admin-border-card, #E6E8E4);">
                                <i class="fa-solid fa-plus text-[10px]" aria-hidden="true"></i>
                                <span>Tambah Varian</span>
                            </button>
                            <span class="text-[11px]" style="color: var(--admin-text-sub, #555D58);">
                                Isi tambahan harga <strong>0</strong> bila tidak ada biaya ekstra.
                            </span>
                        </div>
                    </x-form-section>
                </div>

                {{-- =========================================================================
                     BARIS TOMBOL AKSI FORMULIR (x-form-actions: Non-Sticky, Mengikuti Alur)
                     ========================================================================= --}}
                <div class="mt-6 pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5">
                    
                    {{-- Status Indikator Perubahan Belum Disimpan --}}
                    <div id="formActionsStatus" class="hidden items-center gap-2 text-xs font-semibold text-amber-800 select-none">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0 bg-amber-500 animate-pulse"></span>
                        <span>Ada perubahan yang belum disimpan</span>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 shrink-0 ml-auto w-full sm:w-auto">
                        
                        {{-- Tombol Batalkan --}}
                        <a href="{{ route('admin.menus.index') }}"
                           class="min-h-[40px] px-5 py-2 rounded-xl border text-[13.5px] font-semibold transition flex items-center justify-center gap-1.5 cursor-pointer hover:bg-gray-50"
                           style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                            <span>Batalkan</span>
                        </a>

                        {{-- Khusus Form Tambah: Tombol Simpan & Tambah Lagi --}}
                        @if(!$isEdit)
                            <button type="button"
                                    onclick="submitMenuForm('save_and_add_another')"
                                    class="min-h-[40px] px-4 py-2 rounded-xl border text-[13px] font-semibold transition flex items-center justify-center gap-1.5 cursor-pointer bg-white hover:bg-emerald-50 text-emerald-900 border-emerald-300 btn-press">
                                <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                <span>Simpan &amp; Tambah Lagi</span>
                            </button>
                        @endif

                        {{-- Tombol Simpan Utama (Hijau Solid, Teks Putih) --}}
                        <button type="submit"
                                onclick="submitMenuForm('save')"
                                id="btnSubmitMenu"
                                class="min-h-[40px] px-6 py-2 rounded-xl text-[13.5px] font-semibold transition flex items-center justify-center gap-2 shadow-sm cursor-pointer btn-press"
                                style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;">
                            <i class="fa-solid fa-floppy-disk text-xs" aria-hidden="true"></i>
                            <span id="btnSubmitMenuText">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Menu' }}</span>
                        </button>
                    </div>

                </div>

            </form>
        </div>

        {{-- =========================================================================
             KOLOM CONTOH TAMPILAN KARTU WEBSITE (5 Kolom pada Desktop, Sticky)
             ========================================================================= --}}
        <div class="lg:col-span-5 sticky top-20 space-y-4" id="menuPreviewCol">
            <div class="bg-white p-5 sm:p-6 rounded-2xl border shadow-card space-y-4"
                 style="border-color: var(--admin-border-card, #E6E8E4);">
                
                {{-- Header Kartu Pratinjau --}}
                <div class="flex items-center justify-between pb-3.5 border-b"
                     style="border-color: var(--admin-border-card, #E6E8E4);">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="text-[14.5px] font-bold tracking-tight m-0"
                                style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                                Pratinjau kartu menu di website
                            </h3>
                        </div>
                        <p class="text-[12px] m-0" style="color: var(--admin-text-sub, #555D58);">
                            Berubah otomatis saat Anda mengisi form
                        </p>
                    </div>
                    <span class="text-[11.5px] font-semibold px-2 py-0.5 rounded-full"
                          style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                        Katalog Web
                    </span>
                </div>

                {{-- Simulasi Kartu Menu Pelanggan --}}
                <div class="max-w-[280px] sm:max-w-[300px] mx-auto bg-white border rounded-2xl overflow-hidden shadow-md select-none transition-all"
                     style="border-color: var(--admin-border-card, #E6E8E4);">
                    
                    {{-- 1:1 Foto Frame --}}
                    <div class="relative aspect-square w-full bg-[#F7F8F6] overflow-hidden border-b"
                         style="border-color: var(--admin-border-card, #E6E8E4);">
                        <img id="previewCustomerImg"
                             src="{{ $currentImg ?? asset('images/nucomu-latte-hero.jpg') }}"
                             alt="Pratinjau Sajian"
                             class="w-full h-full object-cover">

                        {{-- Chip Kategori di Kiri Atas --}}
                        <div class="absolute top-2.5 left-2.5">
                            <span id="previewCustomerCategory"
                                  class="px-2.5 py-0.5 rounded-md text-[10.5px] font-bold bg-white/95 backdrop-blur-xs border shadow-2xs"
                                  style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                {{ $menu->category->name ?? ($categories->first()->name ?? 'Kopi') }}
                            </span>
                        </div>

                        {{-- Badge Andalan di Kanan Atas --}}
                        <div id="previewCustomerFeatured"
                             class="{{ old('is_featured', $menu->is_featured ?? ($menu->is_best_seller ?? false)) ? '' : 'hidden' }} absolute top-2.5 right-2.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-400 text-stone-950 shadow-2xs flex items-center gap-1">
                                <i class="fa-solid fa-star text-[9px]"></i>
                                <span>Andalan</span>
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Sajian --}}
                    <div class="p-4 space-y-1.5">
                        <h4 id="previewCustomerName"
                            class="font-bold text-[15px] leading-snug line-clamp-1 m-0"
                            style="color: var(--admin-text-main, #1B1F1D);">
                            {{ old('name', $menu->name ?? 'Nama Menu Sajian') }}
                        </h4>

                        <div id="previewCustomerPrice"
                             class="text-[16px] font-bold tracking-tight tabular-nums"
                             style="color: var(--admin-btn-primary-bg, #2F6B4F);">
                            Rp {{ number_format(old('price', $menu->price ?? 28000), 0, ',', '.') }}
                        </div>

                        <p id="previewCustomerDesc"
                           class="text-[12px] leading-relaxed line-clamp-2 text-gray-500 m-0">
                            {{ old('description', $menu->description ?? 'Penjelasan aroma dan cita rasa racikan istimewa sajian kafe.') }}
                        </p>

                        <div id="previewCustomerOptions" class="hidden pt-1 flex flex-wrap gap-1"></div>

                        {{-- Tombol Aksi Pelanggan Tiruan --}}
                        <div class="pt-2">
                            <div class="w-full py-2 rounded-xl text-center text-xs font-bold text-white shadow-xs"
                                 style="background-color: var(--admin-btn-primary-bg, #2F6B4F);">
                                + Pesan Sekarang
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

{{-- =========================================================================
     JAVASCRIPT: LIVE PREVIEW, DIRTY TRACKING, QUICK CATEGORY & UPLOAD
     ========================================================================= --}}
<script>
    // 1. Live Preview Synchronizers
    function syncMenuPreviewName(val) {
        const target = document.getElementById('previewCustomerName');
        if (target) target.textContent = val.trim() ? val : 'Nama Menu Sajian';
    }

    function syncMenuPreviewCategory(val) {
        const target = document.getElementById('previewCustomerCategory');
        if (target) target.textContent = val.trim() ? val : 'Kategori';
    }

    function syncMenuPreviewPrice(val) {
        const target = document.getElementById('previewCustomerPrice');
        if (!target) return;
        const num = parseInt(val, 10);
        if (!isNaN(num) && num >= 0) {
            target.textContent = 'Rp ' + num.toLocaleString('id-ID');
        } else {
            target.textContent = 'Rp 0';
        }
    }

    function setQuickPrice(amount) {
        const input = document.getElementById('price');
        if (input) {
            input.value = amount;
            syncMenuPreviewPrice(amount);
            markFormAsDirty();
        }
    }

    function syncMenuPreviewDesc(val) {
        const target = document.getElementById('previewCustomerDesc');
        if (target) {
            target.textContent = val.trim() ? val : 'Penjelasan aroma dan cita rasa racikan istimewa sajian kafe.';
        }
    }

    function syncMenuPreviewFeatured(isFeatured) {
        const target = document.getElementById('previewCustomerFeatured');
        if (target) {
            if (isFeatured) {
                target.classList.remove('hidden');
            } else {
                target.classList.add('hidden');
            }
        }
    }

    // 2. Photo Upload & Preview
    function handleMenuPhotoChange(e) {
        const file = e.target.files && e.target.files[0];
        const feedbackEl = document.getElementById('menuImageFeedback');
        if (!file) return;

        // Size check
        const maxBytes = parseInt(e.target.getAttribute('data-max-bytes') || '1048576', 10);
        if (file.size > maxBytes) {
            const maxMB = (maxBytes / (1024 * 1024)).toFixed(1);
            if (feedbackEl) {
                feedbackEl.innerHTML = `<span class="text-rose-600 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1"></i> Ukuran foto melebihi batas server (${maxMB} MB). Perkecil foto terlebih dahulu.</span>`;
            }
            e.target.value = '';
            return;
        }

        if (feedbackEl) feedbackEl.innerHTML = '';

        const reader = new FileReader();
        reader.onload = function(evt) {
            const formPreview = document.getElementById('menuFormPreviewImg');
            const customerPreview = document.getElementById('previewCustomerImg');
            const prompt = document.getElementById('menuFormPrompt');
            const overlay = document.getElementById('menuFormOverlay');
            const removeBtn = document.getElementById('btnRemoveMenuImage');
            const removeInput = document.getElementById('removeImageInput');

            if (formPreview) {
                formPreview.src = evt.target.result;
                formPreview.classList.remove('hidden');
            }
            if (customerPreview) {
                customerPreview.src = evt.target.result;
            }
            if (prompt) prompt.classList.add('hidden');
            if (overlay) overlay.classList.remove('hidden');
            if (removeBtn) removeBtn.classList.remove('hidden');
            if (removeInput) removeInput.value = '0';

            markFormAsDirty();
        };
        reader.readAsDataURL(file);
    }

    function removeMenuImageAction() {
        const fileInput = document.getElementById('menuImageInput');
        const formPreview = document.getElementById('menuFormPreviewImg');
        const customerPreview = document.getElementById('previewCustomerImg');
        const prompt = document.getElementById('menuFormPrompt');
        const overlay = document.getElementById('menuFormOverlay');
        const removeBtn = document.getElementById('btnRemoveMenuImage');
        const removeInput = document.getElementById('removeImageInput');

        if (fileInput) fileInput.value = '';
        if (formPreview) {
            formPreview.src = '';
            formPreview.classList.add('hidden');
        }
        if (customerPreview) {
            customerPreview.src = "{{ asset('images/nucomu-latte-hero.jpg') }}";
        }
        if (prompt) prompt.classList.remove('hidden');
        if (overlay) overlay.classList.add('hidden');
        if (removeBtn) removeBtn.classList.add('hidden');
        if (removeInput) removeInput.value = '1';

        markFormAsDirty();
    }

    // 3. Quick Inline Category Toggle & AJAX Submit
    function toggleQuickCategoryInline() {
        const box = document.getElementById('quickCategoryInlineBox');
        const icon = document.getElementById('iconQuickCat');
        const text = document.getElementById('textQuickCat');
        const isHidden = box.classList.contains('hidden');

        if (isHidden) {
            box.classList.remove('hidden');
            if (icon) icon.className = 'fa-solid fa-xmark text-[10px]';
            if (text) text.textContent = 'Batal';
            document.getElementById('inlineNewCategoryName').focus();
        } else {
            box.classList.add('hidden');
            if (icon) icon.className = 'fa-solid fa-plus text-[10px]';
            if (text) text.textContent = '+ Kategori baru';
        }
    }

    async function submitQuickCategoryAJAX() {
        const nameInput = document.getElementById('inlineNewCategoryName');
        const feedback = document.getElementById('inlineCatFeedback');
        const catName = nameInput.value.trim();

        if (!catName) {
            nameInput.focus();
            return;
        }

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch("{{ route('admin.categories.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ name: catName })
            });

            const data = await res.json();
            if (res.ok && data.category) {
                // Add to select
                const select = document.getElementById('category_id');
                const opt = document.createElement('option');
                opt.value = data.category.id;
                opt.textContent = data.category.name;
                opt.selected = true;
                select.appendChild(opt);

                syncMenuPreviewCategory(data.category.name);
                toggleQuickCategoryInline();
                nameInput.value = '';

                if (feedback) {
                    feedback.textContent = `Kategori "${data.category.name}" berhasil dibuat & dipilih!`;
                    feedback.classList.remove('hidden');
                    setTimeout(() => feedback.classList.add('hidden'), 4000);
                }
            } else {
                alert(data.message || 'Gagal membuat kategori baru.');
            }
        } catch (e) {
            alert('Terjadi kesalahan saat menambahkan kategori.');
        }
    }

    // 4. Dirty Tracking & beforeunload
    let isFormDirty = false;
    function markFormAsDirty() {
        isFormDirty = true;
        const statusEl = document.getElementById('formActionsStatus');
        if (statusEl) {
            statusEl.classList.remove('hidden');
            statusEl.classList.add('flex');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('menuForm');
        if (form) {
            form.addEventListener('input', markFormAsDirty);
            form.addEventListener('change', markFormAsDirty);
        }

        window.addEventListener('beforeunload', function(e) {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = 'Ada perubahan yang belum Anda simpan. Tetap tinggalkan halaman?';
                return e.returnValue;
            }
        });
    });

    function submitMenuForm(actionType) {
        isFormDirty = false;
        const actionInput = document.getElementById('formSubmitAction');
        if (actionInput) actionInput.value = actionType;
    }

    // 5. Dynamic Variants & Addons Builder
    let nextMenuOptionIndex = {{ count($initialOptions) }};

    function updateMenuOptionsEmptyState() {
        const container = document.getElementById('menuOptionsContainer');
        const emptyEl = document.getElementById('menuOptionsEmptyState');
        if (!container || !emptyEl) return;

        const hasRows = container.querySelectorAll('.menu-option-row').length > 0;
        emptyEl.classList.toggle('hidden', hasRows);
    }

    function syncMenuPreviewOptions() {
        const previewEl = document.getElementById('previewCustomerOptions');
        const container = document.getElementById('menuOptionsContainer');
        if (!previewEl || !container) return;

        const names = Array.from(container.querySelectorAll('.menu-option-name'))
            .map((input) => input.value.trim())
            .filter(Boolean);

        previewEl.innerHTML = '';
        if (names.length === 0) {
            previewEl.classList.add('hidden');
            return;
        }

        names.slice(0, 6).forEach((name) => {
            const pill = document.createElement('span');
            pill.className = 'px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#F7F8F6] border text-[#1B1F1D]';
            pill.style.borderColor = 'var(--admin-border-card, #E6E8E4)';
            pill.textContent = name;
            previewEl.appendChild(pill);
        });

        if (names.length > 6) {
            const more = document.createElement('span');
            more.className = 'px-1.5 py-0.5 text-[10px] font-semibold text-gray-500';
            more.textContent = `+${names.length - 6} lagi`;
            previewEl.appendChild(more);
        }

        previewEl.classList.remove('hidden');
    }

    function addMenuOptionRow(preset) {
        const container = document.getElementById('menuOptionsContainer');
        if (!container) return;

        const idx = nextMenuOptionIndex++;
        const type = preset && preset.type === 'addon' ? 'addon' : 'variant';
        const name = preset && preset.name ? String(preset.name) : '';
        const price = preset && preset.price !== undefined ? Number(preset.price) : 0;

        const row = document.createElement('div');
        row.className = 'menu-option-row p-3 rounded-xl border bg-white grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end anim-fade-up';
        row.style.borderColor = 'var(--admin-border-card, #E6E8E4)';

        const typeCol = document.createElement('div');
        typeCol.className = 'sm:col-span-4 space-y-1';
        const typeLabel = document.createElement('label');
        typeLabel.className = 'block text-[11px] font-semibold';
        typeLabel.style.color = 'var(--admin-text-sub, #555D58)';
        typeLabel.textContent = 'Jenis Opsi';
        const typeSelect = document.createElement('select');
        typeSelect.name = `options[${idx}][type]`;
        typeSelect.className = 'menu-option-type w-full px-2.5 py-2 rounded-lg border text-xs font-semibold bg-[#F7F8F6] focus:bg-white focus:outline-none focus:border-[#2F6B4F]';
        typeSelect.style.borderColor = 'var(--admin-border-card, #E6E8E4)';
        typeSelect.style.color = 'var(--admin-text-main, #1B1F1D)';
        const optVariant = new Option('Varian Utama (Suhu / Ukuran)', 'variant', type === 'variant', type === 'variant');
        const optAddon = new Option('Tambahan / Level Gula', 'addon', type === 'addon', type === 'addon');
        typeSelect.appendChild(optVariant);
        typeSelect.appendChild(optAddon);
        typeSelect.addEventListener('change', () => { syncMenuPreviewOptions(); markFormAsDirty(); });
        typeCol.append(typeLabel, typeSelect);

        const nameCol = document.createElement('div');
        nameCol.className = 'sm:col-span-4 space-y-1';
        const nameLabel = document.createElement('label');
        nameLabel.className = 'block text-[11px] font-semibold';
        nameLabel.style.color = 'var(--admin-text-sub, #555D58)';
        nameLabel.textContent = 'Nama Varian / Opsi';
        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.name = `options[${idx}][name]`;
        nameInput.value = name;
        nameInput.maxLength = 100;
        nameInput.placeholder = 'Contoh: Iced, Large, Less Sugar';
        nameInput.className = 'menu-option-name w-full px-3 py-2 rounded-lg border text-xs font-semibold bg-white focus:outline-none focus:border-[#2F6B4F]';
        nameInput.style.borderColor = 'var(--admin-border-card, #E6E8E4)';
        nameInput.style.color = 'var(--admin-text-main, #1B1F1D)';
        nameInput.addEventListener('input', () => { syncMenuPreviewOptions(); markFormAsDirty(); });
        nameCol.append(nameLabel, nameInput);

        const priceCol = document.createElement('div');
        priceCol.className = 'sm:col-span-3 space-y-1';
        const priceLabel = document.createElement('label');
        priceLabel.className = 'block text-[11px] font-semibold';
        priceLabel.style.color = 'var(--admin-text-sub, #555D58)';
        priceLabel.textContent = 'Tambahan Harga';
        const priceWrap = document.createElement('div');
        priceWrap.className = 'relative';
        const prefix = document.createElement('span');
        prefix.className = 'absolute left-2.5 top-2 text-[11px] font-semibold text-gray-400 pointer-events-none';
        prefix.textContent = '+Rp';
        const priceInput = document.createElement('input');
        priceInput.type = 'number';
        priceInput.name = `options[${idx}][price]`;
        priceInput.value = isNaN(price) ? 0 : Math.max(0, price);
        priceInput.min = '0';
        priceInput.step = '500';
        priceInput.className = 'menu-option-price w-full pl-9 pr-2.5 py-2 rounded-lg border text-xs font-bold tabular-nums bg-white focus:outline-none focus:border-[#2F6B4F]';
        priceInput.style.borderColor = 'var(--admin-border-card, #E6E8E4)';
        priceInput.style.color = 'var(--admin-text-main, #1B1F1D)';
        priceInput.addEventListener('input', markFormAsDirty);
        priceWrap.append(prefix, priceInput);
        priceCol.append(priceLabel, priceWrap);

        const removeCol = document.createElement('div');
        removeCol.className = 'sm:col-span-1 flex justify-end';
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'w-9 h-9 rounded-lg border flex items-center justify-center text-rose-600 hover:bg-rose-50 transition cursor-pointer';
        removeBtn.style.borderColor = 'var(--admin-border-card, #E6E8E4)';
        removeBtn.title = 'Hapus baris varian ini';
        removeBtn.setAttribute('aria-label', 'Hapus varian');
        removeBtn.innerHTML = '<i class="fa-solid fa-trash-can text-xs" aria-hidden="true"></i>';
        removeBtn.addEventListener('click', () => removeMenuOptionRow(removeBtn));
        removeCol.appendChild(removeBtn);

        row.append(typeCol, nameCol, priceCol, removeCol);
        container.appendChild(row);

        updateMenuOptionsEmptyState();
        syncMenuPreviewOptions();
        markFormAsDirty();

        if (!preset) {
            nameInput.focus();
        }
    }

    function addOptionPresets(presets) {
        const container = document.getElementById('menuOptionsContainer');
        if (!container || !Array.isArray(presets)) return;

        const existingKeys = new Set(
            Array.from(container.querySelectorAll('.menu-option-row')).map((row) => {
                const t = row.querySelector('.menu-option-type')?.value || 'variant';
                const n = (row.querySelector('.menu-option-name')?.value || '').trim().toLowerCase();
                return `${t}|${n}`;
            })
        );

        presets.forEach((preset) => {
            const key = `${preset.type || 'variant'}|${String(preset.name || '').trim().toLowerCase()}`;
            if (!existingKeys.has(key)) {
                addMenuOptionRow(preset);
                existingKeys.add(key);
            }
        });
    }

    function removeMenuOptionRow(buttonEl) {
        const row = buttonEl.closest('.menu-option-row');
        if (row) {
            row.remove();
            updateMenuOptionsEmptyState();
            syncMenuPreviewOptions();
            markFormAsDirty();
        }
    }

    document.addEventListener('DOMContentLoaded', syncMenuPreviewOptions);

    // 6. Mobile Tab Switcher
    function switchMenuFormMobileTab(tab) {
        const formCol = document.getElementById('menuFormCol');
        const previewCol = document.getElementById('menuPreviewCol');
        const btnForm = document.getElementById('menuTabBtnForm');
        const btnPreview = document.getElementById('menuTabBtnPreview');

        if (tab === 'form') {
            formCol.classList.remove('hidden');
            previewCol.classList.add('hidden');
            btnForm.style.backgroundColor = 'var(--admin-btn-primary-bg, #2F6B4F)';
            btnForm.style.color = '#FFFFFF';
            btnPreview.style.backgroundColor = 'transparent';
            btnPreview.style.color = 'var(--admin-text-sub, #555D58)';
        } else {
            formCol.classList.add('hidden');
            previewCol.classList.remove('hidden');
            btnPreview.style.backgroundColor = 'var(--admin-btn-primary-bg, #2F6B4F)';
            btnPreview.style.color = '#FFFFFF';
            btnForm.style.backgroundColor = 'transparent';
            btnForm.style.color = 'var(--admin-text-sub, #555D58)';
        }
    }
</script>
@endsection
