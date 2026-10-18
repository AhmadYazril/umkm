<!-- Lightbox Modal for Gallery Images -->
<div id="lightbox-modal" 
     class="fixed inset-0 z-50 bg-ink-black/90 backdrop-blur-md flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeLightbox()">
    
    <!-- Top Bar Controls -->
    <div class="absolute top-5 right-5 z-20 flex items-center space-x-4" onclick="event.stopPropagation()">
        <span id="lightbox-counter" class="text-xs uppercase tracking-widest text-light-gray font-semibold">1 / 5</span>
        <button onclick="closeLightbox()" 
                class="w-10 h-10 rounded-full bg-charcoal/80 text-warm-ivory flex items-center justify-center hover:bg-white hover:text-ink-black transition-colors"
                aria-label="Tutup Lightbox">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
    </div>

    <!-- Navigation Arrows -->
    <button onclick="prevLightbox(event)" 
            class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-charcoal/70 text-warm-ivory flex items-center justify-center hover:bg-white hover:text-ink-black transition-colors"
            aria-label="Foto Sebelumnya">
        <span class="material-symbols-outlined text-2xl">chevron_left</span>
    </button>
    <button onclick="nextLightbox(event)" 
            class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-charcoal/70 text-warm-ivory flex items-center justify-center hover:bg-white hover:text-ink-black transition-colors"
            aria-label="Foto Berikutnya">
        <span class="material-symbols-outlined text-2xl">chevron_right</span>
    </button>

    <!-- Image Display Container (No captions displayed on photo as instructed) -->
    <div class="relative max-w-5xl max-h-[85vh] p-2 flex items-center justify-center" onclick="event.stopPropagation()">
        <img id="lightbox-image" 
             src="" 
             alt="Nucomu Cafe Gallery Preview" 
             class="max-w-full max-h-[80vh] object-contain rounded shadow-2xl border border-white/10 transition-opacity duration-200">
        <p id="lightbox-title" class="sr-only"></p>
    </div>
</div>
