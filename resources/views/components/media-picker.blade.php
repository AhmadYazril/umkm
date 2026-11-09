@props([
    'name' => 'media_file',
    'typeInputName' => 'media_type',
    'currentType' => 'video',
    'currentUrl' => null,
    'currentPath' => null,
    'defaultType' => 'video',
    'defaultUrl' => null,
    'defaultPath' => null,
    'imageLimits' => null,
    'videoLimits' => null,
])

@php
    $imageLimits = $imageLimits ?? \App\Support\FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto');
    $videoLimits = $videoLimits ?? \App\Support\FileUploadHelper::getEffectiveLimit(20 * 1024 * 1024, 'video');
    $activeType = old($typeInputName, $currentType ?? 'video');
@endphp

<div class="space-y-4" id="mediaPickerRoot">
    {{-- 1. Pilihan Jenis Media (Segmented Control Berkontras Tinggi) --}}
    <div>
        <label class="block text-[13.5px] font-semibold mb-2" style="color: var(--admin-text-main, #1B1F1D);">
            Pilih Jenis Media <span class="text-rose-600 font-bold">*</span>
        </label>
        
        <div class="inline-flex p-1 rounded-xl border bg-white gap-1 select-none"
             style="border-color: var(--admin-border-card, #E6E8E4); background-color: var(--admin-bg-card, #FFFFFF);">
            
            {{-- Pilihan Foto --}}
            <label class="cursor-pointer">
                <input type="radio" 
                       name="{{ $typeInputName }}" 
                       value="image" 
                       id="mediaTypeImage"
                       {{ $activeType === 'image' ? 'checked' : '' }} 
                       onchange="switchMediaPickerType('image')"
                       class="sr-only peer">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-medium transition cursor-pointer
                             peer-checked:bg-[var(--admin-btn-primary-bg,#2F6B4F)] peer-checked:text-white peer-checked:font-semibold
                             text-[var(--admin-text-main,#1B1F1D)] hover:bg-[var(--admin-bg-sidebar-hover,#F0F4F1)]">
                    <i class="fa-regular fa-image text-xs" aria-hidden="true"></i>
                    <span>Foto / Gambar</span>
                </span>
            </label>

            {{-- Pilihan Video --}}
            <label class="cursor-pointer">
                <input type="radio" 
                       name="{{ $typeInputName }}" 
                       value="video" 
                       id="mediaTypeVideo"
                       {{ $activeType === 'video' ? 'checked' : '' }} 
                       onchange="switchMediaPickerType('video')"
                       class="sr-only peer">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-medium transition cursor-pointer
                             peer-checked:bg-[var(--admin-btn-primary-bg,#2F6B4F)] peer-checked:text-white peer-checked:font-semibold
                             text-[var(--admin-text-main,#1B1F1D)] hover:bg-[var(--admin-bg-sidebar-hover,#F0F4F1)]">
                    <i class="fa-solid fa-video text-xs" aria-hidden="true"></i>
                    <span>Video Pendek</span>
                </span>
            </label>
        </div>
    </div>

    {{-- 2. Panduan Ukuran & Format Ringkas (Dinamis Sesuai Pilihan) --}}
    <div class="p-3.5 sm:p-4 rounded-xl border text-[13px] space-y-1.5 transition-all"
         id="mediaPickerGuideBox"
         style="background-color: var(--admin-bg-active, #E6F0EA); border-color: var(--admin-border-card, #E6E8E4);">
        
        <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main, #1B1F1D);">
            <i class="fa-solid fa-circle-info" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
            <span id="mediaGuideTitle">Panduan Format &amp; Ukuran</span>
        </div>

        <p class="leading-relaxed m-0" id="mediaGuideDescription" style="color: var(--admin-text-sub, #555D58);">
            {{-- Terisi dinamis oleh JavaScript --}}
        </p>

        {{-- Peringatan Batas Server (Hanya jika batas PHP lebih kecil dari batas aplikasi) --}}
        <div id="mediaGuideServerWarning" class="hidden pt-1.5 text-[12px] font-medium text-amber-900 flex items-start gap-1.5">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 shrink-0 mt-0.5" aria-hidden="true"></i>
            <span id="mediaGuideServerWarningText">
                Batas upload server saat ini lebih kecil dari batas aplikasi. File yang melebihi batas server akan gagal diunggah.
            </span>
        </div>
    </div>

    {{-- 3. Media Yang Sedang Digunakan (Thumbnail & Aksi) --}}
    @if($currentUrl)
        <div class="p-3.5 sm:p-4 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3.5"
             id="currentMediaCard"
             style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4);">
            
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-24 h-16 sm:w-28 sm:h-18 rounded-lg overflow-hidden border shrink-0 bg-black relative flex items-center justify-center"
                     style="border-color: var(--admin-border-card, #E6E8E4);">
                    <video id="currentMediaVideoThumb" 
                           src="{{ $currentUrl }}" 
                           autoplay muted loop playsinline 
                           class="w-full h-full object-cover {{ $activeType === 'video' ? '' : 'hidden' }}"></video>
                    <img id="currentMediaImgThumb" 
                         src="{{ $currentUrl }}" 
                         alt="Media Saat Ini" 
                         class="w-full h-full object-cover {{ $activeType === 'image' ? '' : 'hidden' }}">
                </div>

                <div class="min-w-0 space-y-0.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                            Sedang dipakai
                        </span>
                        <span class="text-[12px] font-medium" id="currentMediaBadgeType" style="color: var(--admin-text-muted, #7A847E);">
                            {{ $activeType === 'video' ? 'Video Latar' : 'Foto Utama' }}
                        </span>
                    </div>
                    <p class="text-[12.5px] font-semibold truncate max-w-xs sm:max-w-sm m-0"
                       id="currentMediaNameText"
                       style="color: var(--admin-text-main, #1B1F1D);" 
                       title="{{ $currentPath ?? 'Berkas bawaan' }}">
                        {{ $currentPath ? basename($currentPath) : 'Media bawaan Nucomu' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                @if($defaultUrl && ($currentUrl !== $defaultUrl || $currentType !== $defaultType))
                    <button type="button"
                            class="text-[12.5px] font-medium px-2.5 py-1.5 rounded-lg hover:underline transition"
                            style="color: var(--admin-btn-primary-bg, #2F6B4F);"
                            onclick="restoreDefaultMediaFile(@js($defaultType), @js($defaultUrl), @js($defaultPath))">
                        <i class="fa-solid fa-arrow-rotate-left text-[10px] mr-1" aria-hidden="true"></i>
                        <span>Kembalikan ke bawaan</span>
                    </button>
                @endif
                <button type="button"
                        onclick="triggerFileInputClick()"
                        class="min-h-[34px] px-3.5 py-1.5 rounded-lg border text-[13px] font-semibold transition flex items-center gap-1.5"
                        style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    <i class="fa-solid fa-cloud-arrow-up text-xs" aria-hidden="true"></i>
                    <span>Ganti File</span>
                </button>
            </div>
        </div>
    @endif

    {{-- 4. Area Unggah Berkas Baru (Drag & Drop) --}}
    <div>
        <label for="{{ $name }}" class="block text-[13.5px] font-semibold mb-1.5" style="color: var(--admin-text-main, #1B1F1D);">
            Unggah File Media Baru <span class="text-[12px] font-normal" style="color: var(--admin-text-muted, #7A847E);">(Opsional jika tidak ingin mengganti)</span>
        </label>

        <div id="mediaDropzoneArea"
             onclick="triggerFileInputClick()"
             class="relative border-2 border-dashed rounded-xl p-6 sm:p-7 text-center cursor-pointer transition group"
             style="background-color: var(--admin-bg-page, #F6F7F5); border-color: var(--admin-border-card, #E6E8E4);">
            
            <input type="file" 
                   name="{{ $name }}" 
                   id="{{ $name }}" 
                   class="sr-only"
                   data-max-bytes="{{ $videoLimits['effective_bytes'] }}"
                   data-allowed-ext="mp4,webm,mov"
                   data-feedback-target="#mediaPickerFeedback"
                   onchange="handleSelectedMediaFile(event)">

            {{-- Tampilan Kosong (Prompt Awal) --}}
            <div id="mediaDropzonePrompt" class="space-y-2 pointer-events-none">
                <div class="w-10 h-10 rounded-xl border flex items-center justify-center mx-auto transition group-hover:scale-105"
                     style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-sub, #555D58);">
                    <i class="fa-solid fa-cloud-arrow-up text-base" aria-hidden="true"></i>
                </div>
                <div class="text-[14px] font-semibold" id="dropzonePromptTitle" style="color: var(--admin-text-main, #1B1F1D);">
                    Klik atau seret file ke sini untuk mengganti
                </div>
                <p class="text-[12px] m-0" id="dropzonePromptSub" style="color: var(--admin-text-sub, #555D58);">
                    Maksimal ukuran file disesuaikan dengan batas server
                </p>
            </div>

            {{-- Tampilan Setelah File Dipilih --}}
            <div id="mediaSelectedFilePreview" class="hidden space-y-3">
                <div class="max-w-xs mx-auto aspect-video rounded-lg overflow-hidden border bg-black relative shadow-sm"
                     style="border-color: var(--admin-border-card, #E6E8E4);">
                    <img id="newSelectedImgPreview" src="" alt="Pratinjau Foto" class="w-full h-full object-cover hidden">
                    <video id="newSelectedVideoPreview" src="" controls class="w-full h-full object-cover hidden"></video>
                </div>

                <div class="flex items-center justify-center gap-2 flex-wrap">
                    <span class="text-[12.5px] font-semibold" id="newSelectedFileName" style="color: var(--admin-text-main, #1B1F1D);">
                        nama-file.jpg
                    </span>
                    <span class="text-[11.5px] px-2 py-0.5 rounded font-medium" id="newSelectedFileSize"
                          style="background-color: var(--admin-bg-sidebar-hover, #F0F4F1); color: var(--admin-text-sub, #555D58);">
                        0 MB
                    </span>
                    <button type="button" 
                            onclick="event.stopPropagation(); cancelSelectedMediaFile();"
                            class="text-[12px] font-medium text-rose-600 hover:underline px-2 py-1 rounded transition ml-1">
                        <i class="fa-solid fa-xmark mr-1" aria-hidden="true"></i>
                        <span>Batal ganti berkas</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Wadah Pesan Validasi Instan --}}
        <div id="mediaPickerFeedback" class="mt-2 text-left"></div>
    </div>
</div>

@once
@push('scripts')
<script>
    const mediaLimitsConfig = {
        image: @json($imageLimits),
        video: @json($videoLimits)
    };

    function triggerFileInputClick() {
        const fileInput = document.getElementById('{{ $name }}');
        if (fileInput) fileInput.click();
    }

    function switchMediaPickerType(type) {
        const fileInput = document.getElementById('{{ $name }}');
        const guideTitle = document.getElementById('mediaGuideTitle');
        const guideDesc = document.getElementById('mediaGuideDescription');
        const warningBox = document.getElementById('mediaGuideServerWarning');
        const warningText = document.getElementById('mediaGuideServerWarningText');
        const promptTitle = document.getElementById('dropzonePromptTitle');
        const promptSub = document.getElementById('dropzonePromptSub');
        const currentVideo = document.getElementById('currentMediaVideoThumb');
        const currentImg = document.getElementById('currentMediaImgThumb');
        const currentBadge = document.getElementById('currentMediaBadgeType');

        const activeLimit = mediaLimitsConfig[type] || mediaLimitsConfig.video;

        if (fileInput) {
            fileInput.dataset.maxBytes = activeLimit.effective_bytes;
        }

        if (type === 'video') {
            if (fileInput) {
                fileInput.accept = 'video/mp4,video/webm,video/quicktime';
                fileInput.dataset.allowedExt = 'mp4,webm,mov';
            }
            if (guideTitle) guideTitle.textContent = 'Panduan Video Tentang Cafe';
            if (guideDesc) {
                guideDesc.innerHTML = 'Format <strong>MP4 atau WebM</strong>, durasi <strong>10&ndash;30 detik</strong>, resolusi disarankan 1920 &times; 1080 px (16:9), maksimal <strong>' + activeLimit.formatted + '</strong>. Video berputar otomatis berulang kali tanpa suara (muted loop).';
            }
            if (promptTitle) promptTitle.textContent = 'Klik atau seret file video ke sini';
            if (promptSub) promptSub.textContent = 'Format MP4 atau WebM (Maksimal ' + activeLimit.formatted + ')';
            if (currentBadge) currentBadge.textContent = 'Video Latar';
            if (currentVideo && currentImg) {
                currentVideo.classList.remove('hidden');
                currentImg.classList.add('hidden');
            }
        } else {
            if (fileInput) {
                fileInput.accept = 'image/jpeg,image/png,image/webp';
                fileInput.dataset.allowedExt = 'jpg,jpeg,png,webp';
            }
            if (guideTitle) guideTitle.textContent = 'Panduan Foto Tentang Cafe';
            if (guideDesc) {
                guideDesc.innerHTML = 'Rasio landscape disarankan <strong>16:9 (1920 &times; 1080 px)</strong> atau 4:3, format <strong>JPG, PNG, atau WebP</strong>, maksimal <strong>' + activeLimit.formatted + '</strong>. Pastikan foto memiliki pencahayaan jernih dan tajam.';
            }
            if (promptTitle) promptTitle.textContent = 'Klik atau seret foto ke sini';
            if (promptSub) promptSub.textContent = 'Format JPG, PNG, atau WebP (Maksimal ' + activeLimit.formatted + ')';
            if (currentBadge) currentBadge.textContent = 'Foto Utama';
            if (currentVideo && currentImg) {
                currentVideo.classList.add('hidden');
                currentImg.classList.remove('hidden');
            }
        }

        // Peringatan batas server jika kapasitas server PHP lebih kecil dari batas aplikasi
        if (warningBox && warningText) {
            if (activeLimit.is_throttled || activeLimit.is_limited_by_server) {
                warningBox.classList.remove('hidden');
                warningText.innerHTML = 'Batas upload server saat ini <strong>' + activeLimit.server_limit_formatted + '</strong>, jadi file yang lebih besar akan gagal. Hubungi pengelola teknis untuk menaikkannya.';
            } else {
                warningBox.classList.add('hidden');
            }
        }

        // Trigger custom event untuk preview website
        window.dispatchEvent(new CustomEvent('media-type-changed', { detail: { type } }));
    }

    function handleSelectedMediaFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        const isVideo = file.type.startsWith('video/');
        const promptEl = document.getElementById('mediaDropzonePrompt');
        const previewEl = document.getElementById('mediaSelectedFilePreview');
        const imgEl = document.getElementById('newSelectedImgPreview');
        const videoEl = document.getElementById('newSelectedVideoPreview');
        const nameEl = document.getElementById('newSelectedFileName');
        const sizeEl = document.getElementById('newSelectedFileSize');

        // Sembunyikan prompt, buka preview
        if (promptEl) promptEl.classList.add('hidden');
        if (previewEl) previewEl.classList.remove('hidden');

        if (nameEl) nameEl.textContent = file.name;
        if (sizeEl) {
            const mb = (file.size / (1024 * 1024)).toFixed(1);
            sizeEl.textContent = mb + ' MB';
        }

        const objectUrl = URL.createObjectURL(file);

        if (isVideo) {
            if (imgEl) imgEl.classList.add('hidden');
            if (videoEl) {
                videoEl.classList.remove('hidden');
                videoEl.src = objectUrl;
            }
            // Otomatis arahkan radio ke video jika belum
            const radioVideo = document.getElementById('mediaTypeVideo');
            if (radioVideo && !radioVideo.checked) {
                radioVideo.checked = true;
                switchMediaPickerType('video');
            }
        } else {
            if (videoEl) videoEl.classList.add('hidden');
            if (imgEl) {
                imgEl.classList.remove('hidden');
                imgEl.src = objectUrl;
            }
            const radioImg = document.getElementById('mediaTypeImage');
            if (radioImg && !radioImg.checked) {
                radioImg.checked = true;
                switchMediaPickerType('image');
            }
        }

        window.dispatchEvent(new CustomEvent('new-media-selected', { detail: { file, url: objectUrl, isVideo } }));
    }

    function cancelSelectedMediaFile() {
        const fileInput = document.getElementById('{{ $name }}');
        const promptEl = document.getElementById('mediaDropzonePrompt');
        const previewEl = document.getElementById('mediaSelectedFilePreview');
        const imgEl = document.getElementById('newSelectedImgPreview');
        const videoEl = document.getElementById('newSelectedVideoPreview');
        const feedbackEl = document.getElementById('mediaPickerFeedback');

        if (fileInput) {
            fileInput.value = '';
            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (imgEl) { imgEl.src = ''; imgEl.classList.add('hidden'); }
        if (videoEl) { videoEl.src = ''; videoEl.classList.add('hidden'); }
        if (previewEl) previewEl.classList.add('hidden');
        if (promptEl) promptEl.classList.remove('hidden');
        if (feedbackEl) feedbackEl.innerHTML = '';

        window.dispatchEvent(new CustomEvent('new-media-cancelled'));
    }

    function restoreDefaultMediaFile(type, url, path) {
        cancelSelectedMediaFile();
        const radio = type === 'image' ? document.getElementById('mediaTypeImage') : document.getElementById('mediaTypeVideo');
        if (radio) {
            radio.checked = true;
            radio.dispatchEvent(new Event('change', { bubbles: true }));
            switchMediaPickerType(type);
        }

        const currentThumbVideo = document.getElementById('currentMediaVideoThumb');
        const currentThumbImg = document.getElementById('currentMediaImgThumb');
        const currentNameText = document.getElementById('currentMediaNameText');

        if (type === 'video' && currentThumbVideo) {
            currentThumbVideo.src = url;
            currentThumbVideo.classList.remove('hidden');
            if (currentThumbImg) currentThumbImg.classList.add('hidden');
        } else if (currentThumbImg) {
            currentThumbImg.src = url;
            currentThumbImg.classList.remove('hidden');
            if (currentThumbVideo) currentThumbVideo.classList.add('hidden');
        }
        if (currentNameText) currentNameText.textContent = path ? path.split('/').pop() : 'Media bawaan Nucomu';

        if (typeof showAdminToast === 'function') {
            showAdminToast('Media dikembalikan ke pilihan bawaan.', 'info', 'Media Bawaan', 2500);
        }

        window.dispatchEvent(new CustomEvent('media-restored-default', { detail: { type, url } }));
    }

    // Drag and drop visual cues
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('mediaDropzoneArea');
        if (dropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('ring-2', 'ring-[var(--admin-btn-primary-bg,#2F6B4F)]');
                }, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('ring-2', 'ring-[var(--admin-btn-primary-bg,#2F6B4F)]');
                }, false);
            });
            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    const fileInput = document.getElementById('{{ $name }}');
                    if (fileInput) {
                        fileInput.files = files;
                        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            }, false);
        }

        // Inisialisasi awal
        const currentChecked = document.querySelector('input[name="{{ $typeInputName }}"]:checked')?.value || '{{ $activeType }}';
        switchMediaPickerType(currentChecked);
    });
</script>
@endpush
@endonce
