/**
 * File Upload Client-side Validator for Nucomu Cafe
 * Checks file type and size immediately upon selection in the browser.
 * Gives instant Indonesian feedback without blocking submission.
 */
(function() {
    'use strict';

    function formatBytes(bytes) {
        if (!bytes || bytes <= 0) return '0 MB';
        const mb = bytes / (1024 * 1024);
        return (mb >= 1 ? mb.toFixed(1) : mb.toFixed(2)) + ' MB';
    }

    function getFileExtension(filename) {
        return (filename.split('.').pop() || '').toLowerCase();
    }

    function validateFileInput(inputEl) {
        const feedbackContainerId = inputEl.getAttribute('data-feedback-target');
        let feedbackEl = null;
        if (feedbackContainerId) {
            feedbackEl = feedbackContainerId.startsWith('#') || feedbackContainerId.startsWith('.')
                ? document.querySelector(feedbackContainerId)
                : document.getElementById(feedbackContainerId);
        }

        if (!feedbackEl) {
            // Find existing feedback element next to dropzone/input or create one
            const parent = inputEl.closest('.upload-dropzone') || inputEl.parentElement;
            feedbackEl = parent.querySelector('.file-upload-feedback-box');
            if (!feedbackEl) {
                feedbackEl = document.createElement('div');
                feedbackEl.className = 'file-upload-feedback-box mt-2.5';
                parent.appendChild(feedbackEl);
            }
        }

        const files = inputEl.files;
        if (!files || files.length === 0) {
            feedbackEl.innerHTML = '';
            feedbackEl.classList.add('hidden');
            return;
        }

        const file = files[0];
        const fileName = file.name;
        const fileSize = file.size;
        const fileSizeMb = (fileSize / (1024 * 1024)).toFixed(1);
        const ext = getFileExtension(fileName);

        // Detect current media type if radio selector exists (e.g. video vs image)
        const form = inputEl.closest('form');
        const mediaTypeRadio = form ? form.querySelector('input[name="media_type"]:checked') : null;
        const currentMediaType = mediaTypeRadio ? mediaTypeRadio.value : (inputEl.getAttribute('data-media-type') || 'image');

        // Determine limits
        let appMaxBytes = 5 * 1024 * 1024; // default 5 MB
        let allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

        if (inputEl.getAttribute('data-max-bytes')) {
            appMaxBytes = parseInt(inputEl.getAttribute('data-max-bytes'), 10);
        } else if (inputEl.getAttribute('data-max-size-bytes')) {
            appMaxBytes = parseInt(inputEl.getAttribute('data-max-size-bytes'), 10);
        } else if (inputEl.getAttribute('data-max-size-mb')) {
            appMaxBytes = parseFloat(inputEl.getAttribute('data-max-size-mb')) * 1024 * 1024;
        } else if (currentMediaType === 'video') {
            appMaxBytes = 20 * 1024 * 1024; // 20 MB
            allowedExts = ['mp4', 'webm', 'mov'];
        }

        if (inputEl.getAttribute('data-allowed-ext')) {
            allowedExts = inputEl.getAttribute('data-allowed-ext').toLowerCase().split(',').map(s => s.trim().replace('.', ''));
        } else if (inputEl.getAttribute('data-allowed-types')) {
            allowedExts = inputEl.getAttribute('data-allowed-types').toLowerCase().split(',').map(s => s.trim().replace('.', ''));
        } else if (currentMediaType === 'video') {
            allowedExts = ['mp4', 'webm', 'mov'];
        }

        // Server limits from global variable (injected by Laravel view)
        const serverLimits = window.__SERVER_UPLOAD_LIMITS__ || {
            uploadMaxBytes: 2 * 1024 * 1024,
            uploadMaxRaw: '2M',
            postMaxBytes: 8 * 1024 * 1024
        };

        const serverMaxBytes = serverLimits.uploadMaxBytes || (2 * 1024 * 1024);
        const serverMaxFormatted = serverLimits.uploadMaxRaw || '2 MB';

        let hasError = false;
        let errorMessage = '';

        // 1. Check extension
        if (allowedExts.length > 0 && !allowedExts.includes(ext)) {
            hasError = true;
            errorMessage = `Format berkas <strong>.${ext}</strong> tidak didukung. Harap pilih berkas dengan format <strong>${allowedExts.join(', ').toUpperCase()}</strong>.`;
        }
        // 2. Check if exceeds server PHP limit
        else if (fileSize > serverMaxBytes) {
            hasError = true;
            errorMessage = `Ukuran berkas <strong>${fileSizeMb} MB</strong> melebihi batas unggah server PHP saat ini (<strong>${serverMaxFormatted}</strong>). File kemungkinan akan ditolak saat disimpan. Perkecil resolusi/ukuran berkas atau hubungi pengelola untuk menaikkan batas <code>upload_max_filesize</code> di php.ini.`;
        }
        // 3. Check if exceeds application limit
        else if (fileSize > appMaxBytes) {
            hasError = true;
            const appMaxFormatted = formatBytes(appMaxBytes);
            errorMessage = `Ukuran berkas <strong>${fileSizeMb} MB</strong> melebihi batas aplikasi (maksimal <strong>${appMaxFormatted}</strong> untuk ${currentMediaType === 'video' ? 'video' : 'foto'}). Silakan perkecil ukuran berkas.`;
        }

        feedbackEl.classList.remove('hidden');

        if (hasError) {
            feedbackEl.innerHTML = `
                <div class="p-3 rounded-xl border text-[13px] leading-relaxed flex items-start gap-2.5 bg-amber-50/90 border-amber-200 text-amber-950 shadow-2xs">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <strong class="font-semibold block text-amber-900 mb-0.5">Peringatan Ukuran / Format Berkas:</strong>
                        <span>${errorMessage}</span>
                    </div>
                </div>
            `;
        } else {
            feedbackEl.innerHTML = `
                <div class="p-2.5 rounded-xl border text-[12.5px] leading-relaxed flex items-center gap-2 bg-emerald-50/90 border-emerald-200 text-emerald-950">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm shrink-0"></i>
                    <span>Berkas <strong>${fileName}</strong> (ukuran: ${fileSizeMb} MB) memenuhi ketentuan dan siap diunggah.</span>
                </div>
            `;
        }
    }

    function initAllUploadValidators() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(inputEl => {
            if (inputEl.dataset.uploadValidatorAttached) return;
            inputEl.dataset.uploadValidatorAttached = 'true';

            inputEl.addEventListener('change', function() {
                validateFileInput(inputEl);
            });
        });

        // If media type radio changes, re-validate current file
        const mediaRadios = document.querySelectorAll('input[name="media_type"]');
        mediaRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const form = radio.closest('form');
                if (form) {
                    const fileInput = form.querySelector('input[type="file"]');
                    if (fileInput && fileInput.files && fileInput.files.length > 0) {
                        validateFileInput(fileInput);
                    }
                }
            });
        });
    }

    window.validateSelectedUploadFile = validateFileInput;
    window.initUploadValidators = initAllUploadValidators;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllUploadValidators);
    } else {
        initAllUploadValidators();
    }
})();
