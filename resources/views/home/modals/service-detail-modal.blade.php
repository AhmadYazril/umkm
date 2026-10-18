<!-- Service Detail Modal -->
<div id="service-detail-modal" 
     class="fixed inset-0 z-50 bg-ink-black/75 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeServiceDetailModal()">
    
    <div class="bg-warm-ivory rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-soft-beige relative max-h-[90vh] overflow-y-auto"
         onclick="event.stopPropagation()">
        
        <!-- Close Button -->
        <button onclick="closeServiceDetailModal()" 
                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-light-gray/60 hover:bg-ink-black hover:text-warm-ivory text-charcoal flex items-center justify-center transition-colors"
                aria-label="Tutup Detail Layanan">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Service Image Frame -->
        <div class="w-full h-52 sm:h-64 rounded-xl overflow-hidden mb-6 bg-light-gray border border-soft-beige">
            <img id="service-modal-image" src="" alt="Detail Layanan Nucomu" class="w-full h-full object-cover">
        </div>

        <!-- Meta & Title -->
        <div class="mb-4">
            <span id="service-modal-category" class="text-[10px] font-semibold uppercase tracking-editorial-widest text-medium-gray block mb-1">
                KATEGORI LAYANAN
            </span>
            <h3 id="service-modal-title" class="font-editorial text-2xl sm:text-3xl font-bold text-ink-black">
                Nama Layanan
            </h3>
        </div>

        <!-- Full Description -->
        <div class="mb-6">
            <p id="service-modal-desc" class="font-body text-xs sm:text-sm text-charcoal/85 leading-relaxed font-normal">
                Deskripsi lengkap layanan.
            </p>
        </div>

        <!-- Suggested Area -->
        <div class="p-4 bg-pure-white rounded border border-soft-beige mb-6 flex items-center space-x-3">
            <span class="material-symbols-outlined text-ink-black">meeting_room</span>
            <div class="text-xs">
                <span class="font-semibold text-ink-black block">Rekomendasi Area:</span>
                <span id="service-modal-area" class="text-medium-gray">Indoor / Outdoor</span>
            </div>
        </div>

        <!-- Action: Reservasi Sekarang Button -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <button id="service-modal-reserve-btn" 
                    type="button" 
                    class="w-full sm:flex-1 py-3.5 bg-ink-black text-warm-ivory text-xs font-semibold uppercase tracking-widest rounded hover:bg-neutral-800 transition-colors text-center">
                Reservasi Acara Ini
            </button>
            <button onclick="closeServiceDetailModal()" 
                    type="button" 
                    class="w-full sm:w-auto px-6 py-3.5 border border-charcoal/30 text-charcoal text-xs font-semibold uppercase tracking-widest rounded hover:border-ink-black transition-colors">
                Tutup
            </button>
        </div>

    </div>
</div>
