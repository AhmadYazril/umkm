@extends('layouts.admin')
@section('title', 'Tambah Banner Halaman Depan — Nucomu Cafe')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pt-1">
    
    {{-- Top Back & Breadcrumb --}}
    <div class="flex items-center justify-between pb-4 border-b anim-fade-up" style="border-color: var(--admin-border-card);">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.banners.index') }}" 
               class="min-h-[44px] min-w-[44px] rounded-xl flex items-center justify-center transition btn-press border"
               style="background-color: var(--admin-bg-card); border-color: var(--admin-border-card); color: var(--admin-text-main);"
               title="Kembali ke Daftar Banner">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <span class="text-[13px] font-medium block" style="color: var(--admin-text-sub);">Banner Halaman Depan</span>
                <h1 class="text-2xl sm:text-3xl font-heading font-bold tracking-tight" style="color: var(--admin-text-main);">
                    Tambah Banner Halaman Depan
                </h1>
            </div>
        </div>

        <a href="{{ route('admin.banners.index') }}" class="min-h-[44px] px-4 flex items-center text-[14px] font-medium transition hover:opacity-80" style="color: var(--admin-text-sub);">
            Batal
        </a>
    </div>

    {{-- Alert Catatan Khusus Layar Komputer --}}
    <div class="p-3.5 sm:p-4 rounded-xl border flex items-start sm:items-center gap-3 text-[13.5px] leading-relaxed"
         style="background-color: var(--admin-bg-active, #F4F7F5); border-color: var(--admin-border-card, #E2E8E5); color: var(--admin-text-sub, #4A5552);">
        <i class="fa-solid fa-desktop text-base shrink-0 mt-0.5 sm:mt-0" style="color: var(--admin-btn-primary-bg, #2F6B4F);"></i>
        <div>
            <strong class="font-semibold" style="color: var(--admin-text-main, #1F2926);">Khusus Layar Komputer:</strong> Banner hanya tampil di layar komputer. Di HP banner tidak ditampilkan.
        </div>
    </div>

    {{-- Alert Validation Errors --}}
    @if($errors->any() || session('error'))
        <div class="p-4 rounded-2xl border text-[14px] shadow-xs space-y-2 anim-fade-up" style="background-color: #FDF1F1; border-color: rgba(179,58,58,0.3); color: #B33A3A;">
            <div class="font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ session('error') ? 'Terjadi kesalahan unggah:' : 'Mohon periksa isian formulir:' }}</span>
            </div>
            @if(session('error'))
                <p class="leading-relaxed pl-5 text-[13.5px]">{{ session('error') }}</p>
            @endif
            @if($errors->any())
                <ul class="list-disc list-inside space-y-0.5 pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    {{-- Form Container --}}
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Card 1: Pilihan Mode Tampilan Banner --}}
        <div class="admin-card p-6 sm:p-7 space-y-5">
            <div class="flex items-center gap-2.5 pb-3.5 border-b" style="border-color: var(--admin-border-card);">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs border"
                     style="background-color: var(--admin-bg-active); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <i class="fa-solid fa-table-columns"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold" style="color: var(--admin-text-main);">Mode Tampilan Banner</h2>
                    <p class="text-[13px]" style="color: var(--admin-text-sub);">Pilih bagaimana gambar dan teks promo ditampilkan di layar komputer</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                {{-- Mode 1: Poster (Default) --}}
                <label class="cursor-pointer relative">
                    <input type="radio" name="layout_mode" value="poster" 
                           {{ old('layout_mode', 'poster') === 'poster' ? 'checked' : '' }} 
                           onchange="handleLayoutModeChange('poster')" class="peer sr-only">
                    <div class="h-full p-4 rounded-xl border-2 transition flex flex-col justify-between gap-3"
                         style="background-color: var(--admin-bg-card); border-color: var(--admin-border-card);">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[14px] font-bold" style="color: var(--admin-text-main);">Mode Poster</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                      style="background-color: var(--admin-bg-active); color: var(--admin-btn-primary-bg);">Default</span>
                            </div>
                            <p class="text-[12.5px] leading-relaxed" style="color: var(--admin-text-sub);">
                                Gambar utuh edge-to-edge tanpa teks tambahan yang menimpa. Seluruh poster menjadi tautan link yang dapat diklik.
                            </p>
                        </div>
                        <div class="w-full aspect-[21/9] rounded-lg border flex items-center justify-center overflow-hidden"
                             style="background: linear-gradient(135deg, #2F6B4F 0%, #1D4332 100%); border-color: rgba(255,255,255,0.15);">
                            <span class="text-[10.5px] text-white/90 font-medium flex items-center gap-1.5">
                                <i class="fa-regular fa-image text-xs"></i> Full Poster Image
                            </span>
                        </div>
                    </div>
                </label>

                {{-- Mode 2: Teks di atas gambar (Overlay) --}}
                <label class="cursor-pointer relative">
                    <input type="radio" name="layout_mode" value="overlay" 
                           {{ old('layout_mode') === 'overlay' ? 'checked' : '' }} 
                           onchange="handleLayoutModeChange('overlay')" class="peer sr-only">
                    <div class="h-full p-4 rounded-xl border-2 transition flex flex-col justify-between gap-3"
                         style="background-color: var(--admin-bg-card); border-color: var(--admin-border-card);">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[14px] font-bold" style="color: var(--admin-text-main);">Teks di Atas Gambar</span>
                            </div>
                            <p class="text-[12.5px] leading-relaxed" style="color: var(--admin-text-sub);">
                                Gambar sebagai latar belakang penuh dengan judul, subjudul, dan tombol promo menimpa di atasnya.
                            </p>
                        </div>
                        <div class="w-full aspect-[21/9] rounded-lg border relative flex items-center px-3 overflow-hidden"
                             style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.65)), #3E342F; border-color: rgba(0,0,0,0.1);">
                            <div class="space-y-1 text-left">
                                <span class="block text-[8px] text-amber-300 font-bold uppercase tracking-wider">Promo Khusus</span>
                                <span class="block text-[11px] text-white font-bold leading-tight">Judul di Atas Banner</span>
                            </div>
                        </div>
                    </div>
                </label>

                {{-- Mode 3: Terbagi (Split) --}}
                <label class="cursor-pointer relative">
                    <input type="radio" name="layout_mode" value="split" 
                           {{ old('layout_mode') === 'split' ? 'checked' : '' }} 
                           onchange="handleLayoutModeChange('split')" class="peer sr-only">
                    <div class="h-full p-4 rounded-xl border-2 transition flex flex-col justify-between gap-3"
                         style="background-color: var(--admin-bg-card); border-color: var(--admin-border-card);">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[14px] font-bold" style="color: var(--admin-text-main);">Terbagi (Split)</span>
                            </div>
                            <p class="text-[12.5px] leading-relaxed" style="color: var(--admin-text-sub);">
                                Kolom teks dan tombol di sisi kiri, kartu foto/video di sisi kanan (tampilan promo asli).
                            </p>
                        </div>
                        <div class="w-full aspect-[21/9] rounded-lg border grid grid-cols-2 gap-1.5 p-2 items-center"
                             style="background-color: var(--admin-bg-active); border-color: var(--admin-border-card);">
                            <div class="space-y-0.5">
                                <span class="block h-1.5 w-10 rounded" style="background-color: var(--admin-text-sub); opacity: 0.5;"></span>
                                <span class="block h-2.5 w-14 rounded" style="background-color: var(--admin-text-main);"></span>
                                <span class="block h-1.5 w-8 rounded" style="background-color: var(--admin-btn-primary-bg);"></span>
                            </div>
                            <div class="h-full rounded border flex items-center justify-center text-[8px] text-white"
                                 style="background-color: #3E342F; border-color: var(--admin-border-card);">
                                Kartu Foto
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Card 2: Informasi Utama Banner --}}
        <div class="admin-card p-6 sm:p-7 space-y-5">
            <div class="flex items-center gap-2.5 pb-3.5 border-b" style="border-color: var(--admin-border-card);">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs border"
                     style="background-color: var(--admin-bg-active); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold" style="color: var(--admin-text-main);">Informasi Banner</h2>
                    <p class="text-[13px]" style="color: var(--admin-text-sub);">Teks judul, label promo, dan penjelasan isi banner</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Judul Banner <span style="color: var(--admin-status-danger-text); font-weight: bold;">* (Wajib)</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           oninput="updateLivePreview()"
                           placeholder="Contoh: Weekend Coffee Fest - Buy 1 Get 1 Tiramisu"
                           class="w-full rounded-xl px-4 py-2.5 text-[14.5px] font-medium border focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">
                        Nama banner untuk identifikasi di panel admin dan judul promosi di layar.
                    </span>
                </div>

                <div>
                    <label for="subtitle" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Label Kecil / Subjudul <span class="text-[12px] font-normal" style="color: var(--admin-text-sub);">(Mode Overlay &amp; Terbagi)</span>
                    </label>
                    <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}"
                           oninput="updateLivePreview()"
                           placeholder="Contoh: Promo Spesial, Event Musik, Menu Baru"
                           class="w-full rounded-xl px-4 py-2.5 text-[14.5px] border focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">Teks kecil yang tampil di atas judul.</span>
                </div>

                <div>
                    <label for="sort_order" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Urutan Tampil
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full rounded-xl px-4 py-2.5 text-[14.5px] border focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">Angka lebih kecil tampil lebih awal (0, 1, 2, ...).</span>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Keterangan Tambahan <span class="text-[12px] font-normal" style="color: var(--admin-text-sub);">(Mode Overlay &amp; Terbagi)</span>
                    </label>
                    <textarea name="description" id="description" rows="3"
                              oninput="updateLivePreview()"
                              placeholder="Tuliskan keterangan detail promo, ketentuan penukaran voucher, atau info event..."
                              class="w-full rounded-xl px-4 py-2.5 text-[14.5px] border focus:outline-none transition resize-none"
                              style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">{{ old('description') }}</textarea>
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">Penjelasan singkat yang muncul di bawah judul banner.</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Upload Media & Panduan Ukuran Komputer --}}
        <div class="admin-card p-6 sm:p-7 space-y-5">
            <div class="flex items-center gap-2.5 pb-3.5 border-b" style="border-color: var(--admin-border-card);">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs border"
                     style="background-color: var(--admin-bg-active); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <i class="fa-solid fa-photo-film"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold" style="color: var(--admin-text-main);">Upload Foto atau Video Komputer</h2>
                    <p class="text-[13px]" style="color: var(--admin-text-sub);">File media banner untuk layar komputer</p>
                </div>
            </div>

            {{-- Media Type Selector --}}
            <div>
                <label class="block text-[14px] font-semibold mb-2" style="color: var(--admin-text-main);">
                    Pilih Tipe Media <span style="color: var(--admin-status-danger-text); font-weight: bold;">* (Wajib)</span>
                </label>
                <div class="grid grid-cols-2 gap-3 max-w-md">
                    <label class="cursor-pointer">
                        <input type="radio" name="media_type" value="image" {{ old('media_type', 'image') === 'image' ? 'checked' : '' }} onchange="toggleMediaTypeUI('image')" class="peer sr-only">
                        <div class="min-h-[44px] p-3 rounded-xl border text-center font-medium text-[14px] transition flex items-center justify-center gap-2"
                             style="background-color: var(--admin-bg-card); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                            <i class="fa-regular fa-image text-sm"></i>
                            <span>Foto / Gambar</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="media_type" value="video" {{ old('media_type') === 'video' ? 'checked' : '' }} onchange="toggleMediaTypeUI('video')" class="peer sr-only">
                        <div class="min-h-[44px] p-3 rounded-xl border text-center font-medium text-[14px] transition flex items-center justify-center gap-2"
                             style="background-color: var(--admin-bg-card); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                            <i class="fa-solid fa-video text-sm"></i>
                            <span>Video Pendek</span>
                        </div>
                    </label>
                </div>
            </div>

            @php
                $imageLimits = \App\Support\FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto banner');
                $videoLimits = \App\Support\FileUploadHelper::getEffectiveLimit(20 * 1024 * 1024, 'video banner');
            @endphp

            {{-- Panduan Ukuran Khusus Komputer (Dinamis Sesuai Batas Server) --}}
            <div class="p-4 rounded-xl border text-[13.5px] space-y-1.5 transition-all" id="mediaGuideBox"
                 style="background-color: var(--admin-bg-active); border-color: var(--admin-border-card);">
                <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main);">
                    <i class="fa-solid fa-circle-info" style="color: var(--admin-btn-primary-bg);"></i>
                    <span id="guideTitle">Panduan Ukuran Foto Komputer</span>
                </div>
                <p class="leading-relaxed text-[13px]" id="guideDesc" style="color: var(--admin-text-sub);">
                    Rasio landscape disarankan <strong>1920 &times; 800 px</strong> atau <strong>1920 &times; 1080 px</strong>, minimal lebar <strong>1600 px</strong>. Format: JPG, PNG, atau WebP. Ukuran file maksimal <strong id="guideLimitBadge">{{ $imageLimits['formatted'] ?? $imageLimits['limit_formatted'] ?? '5 MB' }}</strong>.
                </p>
                <div id="guideServerNote" class="{{ !empty($imageLimits['is_throttled'] ?? $imageLimits['is_limited_by_server']) ? '' : 'hidden' }} pt-1 text-[12px] font-medium text-amber-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                    <span>Batas unggah server PHP saat ini ({{ $imageLimits['server_limit_formatted'] ?? '2 MB' }}) lebih kecil dari batas aplikasi banner (5 MB).</span>
                </div>
            </div>

            {{-- File Input & Single Dropzone --}}
            <div>
                <label for="media_file" class="block text-[14px] font-semibold mb-2" style="color: var(--admin-text-main);">
                    Pilih File Foto atau Video <span style="color: var(--admin-status-danger-text); font-weight: bold;">* (Wajib)</span>
                </label>
                
                <div class="relative border-2 border-dashed rounded-2xl p-6 sm:p-8 text-center cursor-pointer transition group"
                     style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card);"
                     onclick="document.getElementById('media_file').click()">
                    <input type="file" name="media_file" id="media_file" class="sr-only" required
                           data-max-bytes="{{ $imageLimits['effective_bytes'] ?? $imageLimits['limit_bytes'] ?? 5242880 }}"
                           data-allowed-ext="jpg,jpeg,png,webp"
                           data-feedback-target="#bannerFileFeedback"
                           onchange="handleMediaPreview(event)">
                    
                    <div id="dropzonePrompt" class="space-y-2">
                        <div class="w-12 h-12 rounded-xl border flex items-center justify-center mx-auto transition group-hover:scale-105"
                             style="background-color: var(--admin-bg-card); border-color: var(--admin-border-card); color: var(--admin-text-sub);">
                            <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                        </div>
                        <div class="text-[14.5px] font-semibold" style="color: var(--admin-text-main);">
                            Klik atau Seret Berkas ke Sini
                        </div>
                        <p class="text-[12.5px]" id="fileRequirementLabel" style="color: var(--admin-text-sub);">
                            Landscape min. 1600 px (Maks {{ $imageLimits['formatted'] ?? $imageLimits['limit_formatted'] ?? '5 MB' }}) atau Video MP4 (Maks {{ $videoLimits['formatted'] ?? $videoLimits['limit_formatted'] ?? '20 MB' }})
                        </p>
                    </div>

                    {{-- Live Preview Box: Khusus Tampilan Komputer --}}
                    <div id="previewContainer" class="hidden relative max-w-2xl mx-auto rounded-xl overflow-hidden border shadow-card"
                         style="border-color: var(--admin-border-card); background-color: #111;">
                        
                        <div class="px-3 py-1.5 border-b flex items-center justify-between text-[11.5px] text-white/80"
                             style="background-color: #1f2421; border-color: rgba(255,255,255,0.1);">
                            <span class="font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-desktop text-xs" style="color: var(--admin-btn-primary-bg);"></i>
                                <span>Pratinjau: Tampilan Komputer</span>
                            </span>
                            <span class="text-white/60 text-[11px]" id="previewModeLabel">Mode Poster</span>
                        </div>

                        <div class="relative w-full aspect-[21/9] flex items-center justify-center overflow-hidden bg-black/40" id="previewCanvas">
                            <img id="imgPreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <video id="videoPreview" src="" controls class="w-full h-full object-cover hidden"></video>

                            {{-- Overlay Text Mockup (shown only in overlay mode) --}}
                            <div id="overlayMockup" class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent p-6 text-left flex flex-col justify-center hidden">
                                <span id="mockSubtitle" class="text-[12px] font-semibold text-amber-300 uppercase tracking-wider mb-1">Subjudul Promo</span>
                                <h3 id="mockTitle" class="text-lg font-bold text-white leading-tight max-w-md drop-shadow">Judul Banner</h3>
                                <p id="mockDesc" class="text-[11px] text-stone-200 mt-1 max-w-sm line-clamp-2">Keterangan promo akan tampil di sini...</p>
                                <div class="mt-3">
                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold text-white bg-[#1F1F1F]">PESAN SEKARANG</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="event.stopPropagation(); clearMediaPreview();" 
                                class="absolute top-2 right-2 w-7 h-7 rounded-lg bg-black/70 text-white flex items-center justify-center text-xs hover:bg-[#B33A3A] transition"
                                title="Ganti File">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                {{-- Client-side live instant feedback box --}}
                <div id="bannerFileFeedback" class="mt-2 text-left"></div>
            </div>
        </div>

        {{-- Card 4: Jadwal Tayang Otomatis & Tombol Aksi --}}
        <div class="admin-card p-6 sm:p-7 space-y-5">
            <div class="flex items-center gap-2.5 pb-3.5 border-b" style="border-color: var(--admin-border-card);">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs border"
                     style="background-color: var(--admin-bg-active); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold" style="color: var(--admin-text-main);">Jadwal Tayang Otomatis</h2>
                    <p class="text-[13px]" style="color: var(--admin-text-sub);">Banner hanya akan tampil di komputer selama rentang tanggal dan jam ini</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="start_time" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Mulai Ditampilkan Pada <span style="color: var(--admin-status-danger-text); font-weight: bold;">* (Wajib)</span>
                    </label>
                    <input type="datetime-local" name="start_time" id="start_time" required
                           value="{{ old('start_time', $defaultStartTime) }}"
                           class="w-full rounded-xl px-4 py-2.5 text-[14px] border focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">Banner otomatis muncul di komputer tepat pada jam ini.</span>
                </div>

                <div>
                    <label for="end_time" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Berhenti Ditampilkan Pada <span style="color: var(--admin-status-danger-text); font-weight: bold;">* (Wajib)</span>
                    </label>
                    <input type="datetime-local" name="end_time" id="end_time" required
                           value="{{ old('end_time', $defaultEndTime) }}"
                           class="w-full rounded-xl px-4 py-2.5 text-[14px] border focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">Setelah jam ini lewat, banner otomatis berhenti tampil.</span>
                </div>

                <div>
                    <label for="button_link" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Link Tujuan Poster / Tombol (Opsional)
                    </label>
                    <input type="text" name="button_link" id="button_link" value="{{ old('button_link', '#menu') }}"
                           placeholder="Contoh: #menu, #reservasi, /#contact, atau tautan luar"
                           class="w-full rounded-xl px-4 py-2.5 text-[14.5px] border focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">
                        Di mode Poster, pengguna mengklik poster untuk menuju tautan ini.
                    </span>
                </div>

                <div>
                    <label for="button_text" class="block text-[14px] font-semibold mb-1.5" style="color: var(--admin-text-main);">
                        Tulisan Tombol Aksi <span class="text-[12px] font-normal" style="color: var(--admin-text-sub);">(Mode Overlay &amp; Terbagi)</span>
                    </label>
                    <input type="text" name="button_text" id="button_text" value="{{ old('button_text', 'Pesan Sekarang') }}"
                           placeholder="Contoh: Pesan Sekarang, Cek Promo, Reservasi Meja"
                           class="w-full rounded-xl px-4 py-2.5 text-[14.5px] border focus:outline-none transition min-h-[44px]"
                           style="background-color: var(--admin-bg-page); border-color: var(--admin-border-card); color: var(--admin-text-main);">
                    <span class="text-[12.5px] mt-1.5 block" style="color: var(--admin-text-sub);">Teks tombol yang tampil di banner.</span>
                </div>

                <div class="sm:col-span-2 pt-2">
                    <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                               class="rounded border text-[#2F6B4F] focus:ring-0 w-4 h-4"
                               style="border-color: var(--admin-border-card);">
                        <div>
                            <span class="text-[14.5px] font-semibold block" style="color: var(--admin-text-main);">Aktifkan banner ini saat jadwal tiba</span>
                            <span class="text-[12.5px]" style="color: var(--admin-text-sub);">Jika tidak dicentang, banner disimpan sebagai draf tanpa tayang.</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.banners.index') }}" 
               class="min-h-[44px] px-5 py-2.5 rounded-xl border text-[14px] font-medium transition btn-press flex items-center"
               style="border-color: var(--admin-border-card); color: var(--admin-text-sub);">
                Batal
            </a>
            <button type="submit" 
                    class="admin-btn-primary min-h-[44px] px-6 py-2.5 rounded-xl text-[14px] font-semibold transition shadow-xs btn-press flex items-center gap-2">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Simpan &amp; Terbitkan Banner</span>
            </button>
        </div>

    </form>

</div>

<script>
    let currentLayoutMode = '{{ old('layout_mode', 'poster') }}';

    function handleLayoutModeChange(mode) {
        currentLayoutMode = mode;
        updateLayoutUI(mode);
        updateLivePreview();
    }

    function updateLayoutUI(mode) {
        const modeLabelEl = document.getElementById('previewModeLabel');
        const overlayMockup = document.getElementById('overlayMockup');

        if (mode === 'poster') {
            if (modeLabelEl) modeLabelEl.textContent = 'Mode Poster (Full-width)';
            if (overlayMockup) overlayMockup.classList.add('hidden');
        } else if (mode === 'overlay') {
            if (modeLabelEl) modeLabelEl.textContent = 'Mode Teks di Atas Gambar';
            if (overlayMockup) overlayMockup.classList.remove('hidden');
        } else {
            if (modeLabelEl) modeLabelEl.textContent = 'Mode Terbagi (Split)';
            if (overlayMockup) overlayMockup.classList.add('hidden');
        }
    }

    function updateLivePreview() {
        const titleVal = document.getElementById('title')?.value || 'Judul Banner';
        const subVal = document.getElementById('subtitle')?.value || 'Subjudul Promo';
        const descVal = document.getElementById('description')?.value || 'Keterangan promo...';

        const mockTitle = document.getElementById('mockTitle');
        const mockSub = document.getElementById('mockSubtitle');
        const mockDesc = document.getElementById('mockDesc');

        if (mockTitle) mockTitle.textContent = titleVal;
        if (mockSub) mockSub.textContent = subVal;
        if (mockDesc) mockDesc.textContent = descVal;
    }

    const bannerLimits = {
        image: @json($imageLimits),
        video: @json($videoLimits)
    };

    function toggleMediaTypeUI(type) {
        const fileInput = document.getElementById('media_file');
        const guideTitle = document.getElementById('guideTitle');
        const guideDesc = document.getElementById('guideDesc');
        const reqLabel = document.getElementById('fileRequirementLabel');
        const serverNote = document.getElementById('guideServerNote');

        const activeLimit = bannerLimits[type] || bannerLimits.image;

        if (fileInput) {
            fileInput.dataset.maxBytes = activeLimit.effective_bytes;
        }

        if (type === 'video') {
            if (fileInput) {
                fileInput.accept = 'video/mp4,video/webm,video/quicktime';
                fileInput.dataset.allowedExt = 'mp4,webm,mov';
            }
            if (guideTitle) guideTitle.textContent = 'Panduan Video Komputer';
            if (guideDesc) guideDesc.innerHTML = 'Format <strong>MP4 (H.264)</strong>, WebM, atau MOV, rasio 16:9, durasi 10&ndash;30 detik, maksimal <strong>' + activeLimit.formatted + '</strong>. Video berputar otomatis tanpa suara di komputer.';
            if (reqLabel) reqLabel.textContent = 'Format MP4, WebM (Maksimal ' + activeLimit.formatted + ')';
            if (serverNote) {
                if (activeLimit.is_throttled) {
                    serverNote.classList.remove('hidden');
                    serverNote.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-amber-600"></i><span>Batas unggah server PHP saat ini (' + activeLimit.server_limit_formatted + ') lebih kecil dari batas aplikasi video (20 MB).</span>';
                } else {
                    serverNote.classList.add('hidden');
                }
            }
        } else {
            if (fileInput) {
                fileInput.accept = 'image/jpeg,image/png,image/webp,image/gif';
                fileInput.dataset.allowedExt = 'jpg,jpeg,png,webp';
            }
            if (guideTitle) guideTitle.textContent = 'Panduan Ukuran Foto Komputer';
            if (guideDesc) guideDesc.innerHTML = 'Rasio landscape disarankan <strong>1920 &times; 800 px</strong> atau <strong>1920 &times; 1080 px</strong>, minimal lebar <strong>1600 px</strong>. Format: JPG, PNG, atau WebP. Maksimal <strong>' + activeLimit.formatted + '</strong>.';
            if (reqLabel) reqLabel.textContent = 'Landscape min. 1600 px, Maks ' + activeLimit.formatted;
            if (serverNote) {
                if (activeLimit.is_throttled) {
                    serverNote.classList.remove('hidden');
                    serverNote.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-amber-600"></i><span>Batas unggah server PHP saat ini (' + activeLimit.server_limit_formatted + ') lebih kecil dari batas aplikasi banner (5 MB).</span>';
                } else {
                    serverNote.classList.add('hidden');
                }
            }
        }
    }

    function handleMediaPreview(event) {
        const file = event.target.files[0];
        if (!file) return;

        const isVideo = file.type.startsWith('video/');
        const promptEl = document.getElementById('dropzonePrompt');
        const previewContainer = document.getElementById('previewContainer');
        const imgPreview = document.getElementById('imgPreview');
        const videoPreview = document.getElementById('videoPreview');

        const objectUrl = URL.createObjectURL(file);

        promptEl.classList.add('hidden');
        previewContainer.classList.remove('hidden');

        if (isVideo) {
            imgPreview.classList.add('hidden');
            videoPreview.classList.remove('hidden');
            videoPreview.src = objectUrl;
            const videoRadio = document.querySelector('input[name="media_type"][value="video"]');
            if (videoRadio) {
                videoRadio.checked = true;
                toggleMediaTypeUI('video');
            }
        } else {
            videoPreview.classList.add('hidden');
            imgPreview.classList.remove('hidden');
            imgPreview.src = objectUrl;
            const imgRadio = document.querySelector('input[name="media_type"][value="image"]');
            if (imgRadio) {
                imgRadio.checked = true;
                toggleMediaTypeUI('image');
            }
        }

        updateLayoutUI(currentLayoutMode);
        updateLivePreview();
    }

    function clearMediaPreview() {
        const fileInput = document.getElementById('media_file');
        const promptEl = document.getElementById('dropzonePrompt');
        const previewContainer = document.getElementById('previewContainer');
        const imgPreview = document.getElementById('imgPreview');
        const videoPreview = document.getElementById('videoPreview');

        fileInput.value = '';
        imgPreview.src = '';
        videoPreview.src = '';
        imgPreview.classList.add('hidden');
        videoPreview.classList.add('hidden');
        previewContainer.classList.add('hidden');
        promptEl.classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const initialType = document.querySelector('input[name="media_type"]:checked')?.value || 'image';
        toggleMediaTypeUI(initialType);
        updateLayoutUI(currentLayoutMode);
        updateLivePreview();
    });
</script>
@endsection
