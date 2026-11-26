<!-- Service Detail Modal -->
<div id="service-detail-modal" 
     class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeServiceDetailModal()">
    
    <div class="bg-[var(--surface-solid)] rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-[var(--shadow)] border border-[var(--border)] relative max-h-[90vh] overflow-y-auto"
         onclick="event.stopPropagation()">
        
        <!-- Close Button -->
        <button onclick="closeServiceDetailModal()" 
                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-[var(--badge-bg)] border border-[var(--border)] hover:bg-[var(--accent-solid)] hover:text-[var(--accent-solid-text)] text-[var(--text)] flex items-center justify-center transition-colors"
                aria-label="Tutup Detail Layanan">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Service Image Frame -->
        <div class="w-full h-52 sm:h-64 rounded-xl overflow-hidden mb-6 bg-[var(--media-bg)] border border-[var(--border)]">
            <img id="service-modal-image" src="" alt="Detail Layanan Nucomu" class="w-full h-full object-cover">
        </div>

        <!-- Meta & Title -->
        <div class="mb-4">
            <span id="service-modal-category" class="text-[10px] font-semibold uppercase tracking-editorial-widest text-[var(--text-muted)] block mb-1">
                KATEGORI LAYANAN
            </span>
            <h3 id="service-modal-title" class="font-editorial text-2xl sm:text-3xl font-bold text-[var(--text)]">
                Nama Layanan
            </h3>
        </div>

        <!-- Full Description -->
        <div class="mb-6">
            <p id="service-modal-desc" class="font-body text-xs sm:text-sm text-[var(--text-muted)] leading-relaxed font-normal">
                Deskripsi lengkap layanan.
            </p>
        </div>

        <!-- Suggested Area -->
        <div class="p-4 bg-[var(--surface-strong-solid)] rounded border border-[var(--border)] mb-6 flex items-center space-x-3">
            <span class="material-symbols-outlined text-[var(--text)]">meeting_room</span>
            <div class="text-xs">
                <span class="font-semibold text-[var(--text)] block">Rekomendasi Area:</span>
                <span id="service-modal-area" class="text-[var(--text-muted)]">Indoor / Outdoor</span>
            </div>
        </div>

        <!-- Action: Reservasi Sekarang Button -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <button id="service-modal-reserve-btn" 
                    type="button" 
                    class="w-full sm:flex-1 py-3.5 bg-[var(--accent-solid)] text-[var(--accent-solid-text)] text-xs font-semibold uppercase tracking-widest rounded hover:bg-[var(--accent-solid-hover)] transition-colors text-center">
                Reservasi Acara Ini
            </button>
            <button onclick="closeServiceDetailModal()" 
                    type="button" 
                    class="w-full sm:w-auto px-6 py-3.5 border border-[var(--btn-outline)] text-[var(--text)] text-xs font-semibold uppercase tracking-widest rounded hover:border-[var(--text)] hover:bg-[var(--btn-outline-hover-bg)] transition-colors">
                Tutup
            </button>
        </div>

    </div>
</div>
