@props([
    'formId' => 'aboutForm',
    'saveText' => 'Simpan perubahan',
    'cancelText' => 'Batalkan',
    'unsavedMessage' => 'Ada perubahan yang belum disimpan',
])

{{-- Baris Tombol Aksi Formulir Standar (Non-Sticky, Mengikuti Alur Halaman) --}}
<div {{ $attributes->merge(['class' => 'mt-5 sm:mt-6 mb-8 sm:mb-12 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5 pt-1']) }}>
    
    {{-- Status Indikator Perubahan (Sisi Kiri: HANYA MUNCUL JIKA ADA PERUBAHAN) --}}
    <div id="formActionsStatus" 
         class="hidden items-center gap-2 text-[12.5px] sm:text-[13px] font-medium text-amber-800 select-none order-2 sm:order-1 self-center sm:self-auto">
        <span class="w-2.5 h-2.5 rounded-full shrink-0 bg-amber-500 animate-pulse"></span>
        <span>{{ $unsavedMessage }}</span>
    </div>

    {{-- Tombol Aksi (Sisi Kanan: Rata Kanan di Desktop, Tersusun Rapi Penuh di HP) --}}
    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 shrink-0 ml-auto w-full sm:w-auto order-1 sm:order-2">
        
        {{-- Tombol Batalkan (HP: di bawah, Desktop: di kiri) --}}
        <button type="button" 
                id="formActionsCancelBtn"
                onclick="handleDiscardChanges('{{ $formId }}')"
                class="min-h-[44px] sm:min-h-[36px] px-5 py-2 rounded-xl border text-[13.5px] font-semibold transition flex items-center justify-center gap-1.5 cursor-pointer"
                style="background-color: var(--admin-btn-secondary-bg, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
            <i class="fa-solid fa-arrow-rotate-left text-xs" aria-hidden="true"></i>
            <span>{{ $cancelText }}</span>
        </button>

        {{-- Tombol Simpan Perubahan (HP: di atas, Desktop: di kanan, Hijau Solid, Teks Putih, Kontras >= 4.5:1) --}}
        <button type="submit" 
                form="{{ $formId }}"
                id="formActionsSubmitBtn"
                disabled
                class="min-h-[44px] sm:min-h-[36px] px-6 py-2 rounded-xl text-[13.5px] font-semibold transition flex items-center justify-center gap-2 shadow-sm disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
                style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;"
                title="Simpan perubahan data">
            <i class="fa-solid fa-floppy-disk text-xs" aria-hidden="true"></i>
            <span id="formActionsSubmitText">{{ $saveText }}</span>
        </button>
    </div>

</div>

@once
@push('scripts')
<script>
    (function() {
        let isFormDirty = false;
        let initialFormState = {};
        const form = document.getElementById('{{ $formId }}');
        const submitBtn = document.getElementById('formActionsSubmitBtn');
        const statusEl = document.getElementById('formActionsStatus');

        if (!form) return;

        // Ambil snapshot awal seluruh isian input form
        function captureInitialState() {
            const formData = new FormData(form);
            initialFormState = {};
            for (let [key, val] of formData.entries()) {
                if (val instanceof File) {
                    initialFormState[key] = val.name ? val.name : '';
                } else {
                    initialFormState[key] = val;
                }
            }
        }

        // Bandingkan isian saat ini dengan snapshot awal
        function checkIfDirty() {
            const currentData = new FormData(form);
            let dirty = false;

            for (let [key, val] of currentData.entries()) {
                if (key === '_token') continue;
                if (val instanceof File) {
                    if (val.size > 0) {
                        dirty = true;
                        break;
                    }
                } else {
                    const initialVal = initialFormState[key] ?? '';
                    if (val !== initialVal) {
                        dirty = true;
                        break;
                    }
                }
            }

            setDirtyState(dirty);
        }

        function setDirtyState(dirty) {
            isFormDirty = dirty;
            window.__IS_ADMIN_FORM_DIRTY__ = dirty;

            if (dirty) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('disabled:opacity-60', 'disabled:cursor-not-allowed');
                }
                if (statusEl) {
                    statusEl.classList.remove('hidden');
                    statusEl.classList.add('flex');
                }
            } else {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('disabled:opacity-60', 'disabled:cursor-not-allowed');
                }
                if (statusEl) {
                    statusEl.classList.add('hidden');
                    statusEl.classList.remove('flex');
                }
            }
        }

        window.setFormDirty = () => setDirtyState(true);
        window.markFormSaved = () => {
            captureInitialState();
            setDirtyState(false);
        };

        // Pasang event listener ke formulir
        form.addEventListener('input', checkIfDirty);
        form.addEventListener('change', checkIfDirty);

        // Saat form di-submit, matikan dirty check agar tidak memicu dialog beforeunload
        form.addEventListener('submit', function() {
            isFormDirty = false;
            window.__IS_ADMIN_FORM_DIRTY__ = false;
            if (submitBtn) {
                submitBtn.disabled = true;
                const txt = document.getElementById('formActionsSubmitText');
                if (txt) txt.textContent = 'Menyimpan...';
            }
        });

        // Peringatan konfirmasi saat meninggalkan halaman jika ada perubahan belum disimpan
        window.addEventListener('beforeunload', function(e) {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = 'Ada perubahan yang belum Anda simpan. Tetap tinggalkan halaman?';
                return e.returnValue;
            }
        });

        // Inisialisasi awal saat halaman siap: pastikan status selalu bersih (tidak muncul)
        window.addEventListener('DOMContentLoaded', function() {
            captureInitialState();
            setDirtyState(false);
        });

        // Fungsi pembatalan perubahan
        window.handleDiscardChanges = function(targetFormId) {
            const f = document.getElementById(targetFormId);
            if (!f) return;

            if (isFormDirty) {
                if (confirm('Kembalikan semua isian formulir ke kondisi sebelum diedit? Perubahan yang belum disimpan akan hilang.')) {
                    f.reset();
                    if (typeof syncAllPreviews === 'function') {
                        syncAllPreviews();
                    }
                    if (typeof cancelSelectedMediaFile === 'function') {
                        cancelSelectedMediaFile();
                    }
                    captureInitialState();
                    setDirtyState(false);
                    if (typeof showAdminToast === 'function') {
                        showAdminToast('Perubahan dibatalkan.', 'info', 'Dibatalkan', 2000);
                    }
                }
            } else {
                if (typeof showAdminToast === 'function') {
                    showAdminToast('Belum ada perubahan yang dilakukan.', 'info', 'Informasi', 2000);
                }
            }
        };
    })();
</script>
@endpush
@endonce
