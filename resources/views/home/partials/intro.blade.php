<!-- INTRO / OPENING PRELOADER WITH SVG LOGO ANIMATION (MODERN ELEGANT MONOCHROME) -->
<div id="nucomu-intro-overlay"
     class="nucomu-intro-overlay fixed inset-0 z-[99999] bg-[#FAFAFA] flex flex-col items-center justify-center select-none"
     role="dialog"
     aria-modal="true"
     aria-label="Pembukaan Nucomu Cafe">

    <!-- Container Logo SVG & Teks Branding -->
    <div class="nucomu-intro-content flex flex-col items-center justify-center text-center px-4">
        <!-- SVG Logo Animasi Nucomu (Persis koordinat & pathLength dari Animasi_Logo.html) -->
        <svg id="nucomu-intro-svg"
             class="nucomu-intro-svg w-[min(70vw,280px)] sm:w-[min(60vw,320px)] h-auto overflow-visible"
             viewBox="0 0 216 236"
             aria-label="Nucomu Cafe"
             role="img">
            <!-- Ring Luar (#E5E5E5) -->
            <circle class="intro-ring" cx="109" cy="118" r="81" pathLength="1"/>
            
            <!-- Logo Mark (#1C1C1C) & Dot (#1C1C1C) -->
            <g class="intro-logo">
                <path class="intro-mark" pathLength="1" d="M89 122V98Q89 88 99 88H104Q114 88 114 98V152A8 8 0 0 0 130 152V124"/>
                <circle class="intro-dot" cx="130" cy="106" r="6.5"/>
            </g>
        </svg>

        <!-- Teks Branding "NUCOMU CAFE" -->
        <span class="intro-text mt-4 sm:mt-5 text-[13px] sm:text-[14px] font-semibold text-[#1C1C1C] uppercase tracking-[0.28em] font-body">
            NUCOMU CAFE
        </span>
    </div>

    <!-- Tombol Lewati di Pojok Kanan Bawah -->
    <button type="button"
            id="nucomu-intro-skip-btn"
            onclick="window.skipNucomuIntro?.()"
            class="nucomu-intro-skip absolute bottom-6 right-6 sm:bottom-8 sm:right-8 text-xs font-semibold uppercase tracking-wider text-[#666666] hover:text-[#1C1C1C] px-3.5 py-1.5 rounded-full border border-transparent hover:border-[#E5E5E5] transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]"
            aria-label="Lewati animasi pembukaan">
        Lewati
    </button>
</div>

<!-- Instant Inline Fallback & Key Handler (Ensures Skip & Esc work even before external JS finishes loading) -->
<script>
    (function() {
        if (!window.__nucomuIntroStartTime) {
            window.__nucomuIntroStartTime = performance.now();
        }
        if (!window.skipNucomuIntro) {
            window.skipNucomuIntro = function() {
                var overlay = document.getElementById('nucomu-intro-overlay');
                if (!overlay || overlay.dataset.finished === 'true') return;
                overlay.dataset.finished = 'true';
                window.nucomuIntroIsDone = true;
                overlay.classList.add('is-fading');
                setTimeout(function() {
                    try { sessionStorage.setItem('nucomu_intro_seen', '1'); } catch (e) {}
                    if (overlay && overlay.parentNode) {
                        overlay.parentNode.removeChild(overlay);
                    }
                    document.documentElement.classList.remove('nucomu-intro-active');
                    document.documentElement.classList.add('nucomu-intro-seen');
                    document.documentElement.style.overflow = '';
                    document.body.style.overflow = '';
                    window.dispatchEvent(new CustomEvent('nucomu:intro-done'));
                }, 300);
            };
        }
        function handleIntroEarlyEsc(e) {
            if (e.key === 'Escape') {
                var isActive = document.documentElement.classList.contains('nucomu-intro-active') && !window.nucomuIntroIsDone;
                var overlay = document.getElementById('nucomu-intro-overlay');
                if (isActive && overlay && !overlay.classList.contains('is-fading')) {
                    window.skipNucomuIntro();
                    window.removeEventListener('keydown', handleIntroEarlyEsc);
                }
            }
        }
        window.addEventListener('keydown', handleIntroEarlyEsc);
    })();
</script>
