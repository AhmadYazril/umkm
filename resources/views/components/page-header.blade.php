@props([
    'title',
])

<div {{ $attributes->merge(['class' => 'space-y-3 mb-5 sm:mb-6']) }}>
    {{-- Header Row: Judul Bersih di Kiri & Tombol Aksi di Kanan --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
        {{-- Judul Halaman (Manrope 600, 22-24px, -0.02em, Angka Normal Lining-nums) --}}
        <div>
            <h1 class="admin-page-title">
                {{ $title }}
            </h1>
        </div>

        {{-- Kanan: Area Tombol / Tautan Petunjuk & Aksi --}}
        @if(isset($actions))
            <div class="flex flex-wrap items-center gap-3.5 shrink-0 w-full sm:w-auto">
                {{ $actions }}
            </div>
        @elseif(isset($slot) && $slot->isNotEmpty())
            <div class="flex flex-wrap items-center gap-3.5 shrink-0 w-full sm:w-auto">
                {{ $slot }}
            </div>
        @endif
    </div>

    {{-- Slot Panel Petunjuk (Opsional, terbuka tepat di bawah judul saat diklik) --}}
    @if(isset($help))
        <div class="w-full">
            {{ $help }}
        </div>
    @endif
</div>
