@props([
    'step' => null,
    'title',
    'description' => null,
    'icon' => null,
])

<section {{ $attributes->merge(['class' => 'admin-card p-5 sm:p-6 space-y-4 sm:space-y-5 transition-shadow']) }}
         style="background-color: var(--admin-bg-card, #FFFFFF); border-color: var(--admin-border-card, #E6E8E4); border-radius: var(--admin-radius-card, 12px);">
    
    {{-- Header Langkah --}}
    <div class="flex items-start sm:items-center justify-between gap-3 pb-3.5 border-b"
         style="border-color: var(--admin-border-card, #E6E8E4);">
        <div class="flex items-center gap-3">
            @if($step)
                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold shrink-0"
                      style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                    {{ $step }}
                </span>
            @elseif($icon)
                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs shrink-0"
                      style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                    <i class="{{ $icon }}" aria-hidden="true"></i>
                </span>
            @endif
            <div>
                <h2 class="text-[15px] sm:text-base font-bold tracking-tight m-0"
                    style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                    @if($step && !str_starts_with($title, $step . '.')){{ $step }}. @endif{{ $title }}
                </h2>
                @if($description)
                    <p class="text-[12.5px] mt-0.5 m-0 leading-normal" style="color: var(--admin-text-sub, #555D58);">
                        {{ $description }}
                    </p>
                @endif
            </div>
        </div>

        @if(isset($badge))
            <div class="shrink-0">{{ $badge }}</div>
        @endif
    </div>

    {{-- Isi Konten Bagian --}}
    <div class="space-y-4">
        {{ $slot }}
    </div>
</section>
