@props([
    'id' => 'help-panel-' . \Illuminate\Support\Str::random(8),
    'labelOpen' => 'Lihat petunjuk penggunaan',
    'labelClose' => 'Tutup petunjuk',
    'trigger' => true,
])

<div {{ $attributes->merge(['class' => 'w-full']) }} id="{{ $id }}">
    @if($trigger)
        {{-- Tombol Pemicu Tautan Kecil Default --}}
        <button type="button"
                id="{{ $id }}-trigger"
                class="help-panel-toggle btn-press"
                aria-expanded="false"
                aria-controls="{{ $id }}-content"
                onclick="toggleHelpPanel('{{ $id }}')">
            <i class="fa-regular fa-circle-question text-[13px] shrink-0" aria-hidden="true"></i>
            <span class="toggle-label" data-open="{{ $labelOpen }}" data-close="{{ $labelClose }}">{{ $labelOpen }}</span>
            <i class="fa-solid fa-chevron-down text-[10px] toggle-chevron shrink-0" aria-hidden="true"></i>
        </button>
    @endif

    {{-- Panel Konten Petunjuk Penggunaan --}}
    <div id="{{ $id }}-content"
         class="help-panel-container w-full"
         role="region"
         aria-labelledby="{{ $id }}-trigger">
        <div class="help-panel-inner">
            <div class="help-panel-card">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    function toggleHelpPanel(id, forceClose = false) {
        const root = document.getElementById(id);
        const content = document.getElementById(id + '-content') || (root ? root.querySelector('.help-panel-container') : null);
        if (!content) return;

        // Cari tombol pemicu baik di dalam root maupun tombol luar yang menunjuk ID ini
        const trigger = document.getElementById(id + '-trigger') 
            || (root ? root.querySelector('.help-panel-toggle') : null)
            || document.querySelector(`[aria-controls="${id}-content"]`);

        const label = trigger ? trigger.querySelector('.toggle-label') : null;

        const isCurrentlyOpen = trigger 
            ? (trigger.getAttribute('aria-expanded') === 'true') 
            : content.classList.contains('is-open');
        const shouldOpen = forceClose ? false : !isCurrentlyOpen;

        if (trigger) {
            trigger.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        }

        if (shouldOpen) {
            content.classList.add('is-open');
            if (label && label.dataset.close) {
                label.textContent = label.dataset.close;
            }
        } else {
            content.classList.remove('is-open');
            if (label && label.dataset.open) {
                label.textContent = label.dataset.open;
            }
        }
    }

    // Keyboard navigation: Close active help panels when pressing Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' || event.key === 'Esc') {
            const activeTriggers = document.querySelectorAll('[aria-expanded="true"][aria-controls$="-content"]');
            if (activeTriggers.length > 0) {
                activeTriggers.forEach(trigger => {
                    const controlsId = trigger.getAttribute('aria-controls');
                    const panelId = controlsId ? controlsId.replace('-content', '') : null;
                    if (panelId) {
                        toggleHelpPanel(panelId, true);
                        trigger.focus();
                    }
                });
            }
        }
    });
</script>
@endpush
@endonce
