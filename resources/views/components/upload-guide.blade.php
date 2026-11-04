@props([
    'appLimitBytes' => 5242880,
    'type' => 'foto',
    'dimensionText' => '',
    'formatText' => 'JPG, PNG, atau WebP',
    'id' => null,
])

@php
    $limitInfo = \App\Support\FileUploadHelper::getEffectiveLimit($appLimitBytes, $type);
@endphp

<div {{ $id ? 'id=' . $id : '' }}
     class="p-4 rounded-xl border text-[13.5px] space-y-1.5 transition"
     style="background-color: var(--admin-bg-active, #F4F7F5); border-color: var(--admin-border-card, #E2E8E5);">
    <div class="font-semibold flex items-center justify-between" style="color: var(--admin-text-main, #1F2926);">
        <span class="flex items-center gap-2">
            <i class="fa-solid fa-circle-info" style="color: var(--admin-btn-primary-bg, #2F6B4F);"></i>
            <span class="guide-title-text">Panduan Ukuran &amp; Format {{ ucfirst($type) }}</span>
        </span>
        <span class="text-[11.5px] px-2 py-0.5 rounded-full font-bold"
              style="background-color: {{ !empty($limitInfo['is_limited_by_server'] ?? $limitInfo['is_throttled']) ? '#FEF3C7' : 'var(--admin-bg-card, #FFFFFF)' }}; color: {{ !empty($limitInfo['is_limited_by_server'] ?? $limitInfo['is_throttled']) ? '#92400E' : 'var(--admin-text-main, #1F2926)' }};">
            Maks. {{ $limitInfo['formatted'] ?? $limitInfo['limit_formatted'] ?? '5 MB' }}
        </span>
    </div>
    
    <p class="leading-relaxed text-[13px] guide-desc-text" style="color: var(--admin-text-sub, #4A5552);">
        @if(!empty($dimensionText))
            {{ $dimensionText }}
        @endif
        Format yang didukung: <strong>{{ $formatText }}</strong>.
        Ukuran file: <strong>{{ $limitInfo['formatted'] ?? $limitInfo['limit_formatted'] ?? '5 MB' }}</strong>.
    </p>

    @if(!empty($limitInfo['is_limited_by_server'] ?? $limitInfo['is_throttled']))
        <div class="p-2.5 rounded-lg border text-[12px] leading-relaxed flex items-start gap-2 bg-amber-50/80 border-amber-200 text-amber-900 mt-2">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 shrink-0"></i>
            <div>
                <strong>Batas Server Saat Ini:</strong> Server PHP Anda membatasi unggahan hingga <strong>{{ $limitInfo['server_limit_formatted'] ?? '2 MB' }}</strong> (nilai <code>upload_max_filesize</code> di php.ini). Perkecil ukuran berkas ke bawah {{ $limitInfo['formatted'] ?? $limitInfo['limit_formatted'] ?? '2 MB' }} atau minta pengelola server menaikkan batas ke 32 MB agar mendukung hingga {{ $limitInfo['app_limit_formatted'] ?? '5 MB' }}.
            </div>
        </div>
    @endif
</div>
