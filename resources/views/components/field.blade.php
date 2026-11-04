@props([
    'for' => null,
    'label',
    'required' => false,
    'hint' => null,
    'recommendation' => null,
    'defaultValue' => null,
    'counter' => true,
    'max' => null,
])

@php
    $fieldId = $for ?? $attributes->get('id');
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1.5 field-wrapper']) }} data-field-id="{{ $fieldId }}">
    {{-- Label & Reset Button Row (Selalu sejajar satu baris tanpa terpotong) --}}
    <div class="flex items-center justify-between gap-2 min-w-0">
        <label @if($fieldId) for="{{ $fieldId }}" @endif class="block text-[13.5px] font-semibold select-none truncate" style="color: var(--admin-text-main, #1B1F1D);">
            {{ $label }}
            @if($required)
                <span class="text-rose-600 font-bold ml-0.5" title="Wajib diisi">*</span>
            @endif
        </label>

        @if(!is_null($defaultValue))
            <button type="button"
                    class="text-[12px] font-medium hover:underline transition shrink-0 whitespace-nowrap cursor-pointer flex items-center gap-1.5 py-0.5 px-1 rounded focus:outline-none focus:ring-1 focus:ring-[var(--admin-btn-primary-bg,#2F6B4F)] ml-auto"
                    style="color: var(--admin-btn-primary-bg, #2F6B4F);"
                    onclick="resetFieldToDefault('{{ $fieldId }}', @js($defaultValue))"
                    title="Isi kembali dengan teks bawaan">
                <i class="fa-solid fa-arrow-rotate-left text-[10px]" aria-hidden="true"></i>
                <span>Kembalikan ke teks bawaan</span>
            </button>
        @endif
    </div>

    {{-- Penjelasan Bagian & Contoh --}}
    @if($hint)
        <p class="text-[12.5px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
            {{ $hint }}
        </p>
    @endif

    {{-- Input Slot --}}
    <div class="relative">
        {{ $slot }}
    </div>

    {{-- Saran Panjang & Penghitung Karakter --}}
    @if($counter || $recommendation)
        <div class="flex items-center justify-between text-[11.5px] pt-0.5" style="color: var(--admin-text-muted, #7A847E);">
            <div>
                @if($recommendation)
                    <span>{{ $recommendation }}</span>
                @endif
            </div>
            @if($counter && $fieldId)
                <div id="{{ $fieldId }}-counter-box" class="tabular-nums font-medium text-right">
                    <span id="{{ $fieldId }}-count">0</span>@if($max)/{{ $max }}@endif huruf
                </div>
            @endif
        </div>
    @endif

    {{-- Error Message --}}
    @if($fieldId)
        @error($fieldId)
            <p class="text-[12px] font-medium text-rose-600 mt-1 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation text-xs" aria-hidden="true"></i>
                <span>{{ $message }}</span>
            </p>
        @enderror
    @endif
</div>

@once
@push('scripts')
<script>
    function resetFieldToDefault(fieldId, defaultValue) {
        const input = document.getElementById(fieldId);
        if (!input) return;
        input.value = defaultValue;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
        
        // Highlight animasi lembut
        input.classList.add('ring-2', 'ring-emerald-500');
        setTimeout(() => input.classList.remove('ring-2', 'ring-emerald-500'), 600);
        
        if (typeof showAdminToast === 'function') {
            showAdminToast('Teks berhasil dikembalikan ke bawaan.', 'info', 'Teks Bawaan', 2000);
        }
    }

    function updateFieldCounter(fieldId, max = null) {
        const input = document.getElementById(fieldId);
        const countEl = document.getElementById(fieldId + '-count');
        if (!input || !countEl) return;
        const len = input.value ? input.value.length : 0;
        countEl.textContent = len;
        if (max && len > max) {
            countEl.classList.add('text-rose-600', 'font-bold');
        } else {
            countEl.classList.remove('text-rose-600', 'font-bold');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.field-wrapper').forEach(wrapper => {
            const fieldId = wrapper.dataset.fieldId;
            if (!fieldId) return;
            const input = document.getElementById(fieldId);
            if (!input) return;
            const max = input.getAttribute('maxlength') ? parseInt(input.getAttribute('maxlength')) : null;
            updateFieldCounter(fieldId, max);
            input.addEventListener('input', () => updateFieldCounter(fieldId, max));
        });
    });
</script>
@endpush
@endonce
