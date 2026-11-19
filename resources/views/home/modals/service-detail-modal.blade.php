<!-- Service Detail Modal -->
<div id="service-detail-modal" 
     class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeServiceDetailModal()">
    
    <div class="bg-[#121212] rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-[var(--shadow)] border border-[var(--border)] relative max-h-[90vh] overflow-y-auto"
         onclick="event.stopPropagation()">
        
        <!-- Close Button -->
        <button onclick="closeServiceDetailModal()" 
                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/10 border border-[var(--border)] hover:bg-[#F5F2EE] hover:text-[#111111] text-[#F5F5F5] flex items-center justify-center transition-colors"
                aria-label="Tutup Detail Layanan">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Service Image Frame -->
        <div class="w-full h-52 sm:h-64 rounded-xl overflow-hidden mb-6 bg-[#1A1A1A] border border-[var(--border)]">
            <img id="service-modal-image" src="" alt="Detail Layanan Nucomu" class="w-full h-full object-cover">
        </div>

        <!-- Meta & Title -->
        <div class="mb-4">
            <span id="service-modal-category" class="text-[10px] font-semibold uppercase tracking-editorial-widest text-[#B5B5B5] block mb-1">
                KATEGORI LAYANAN
            </span>
            <h3 id="service-modal-title" class="font-editorial text-2xl sm:text-3xl font-bold text-[#F5F5F5]">
                Nama Layanan
            </h3>
        </div>

        <!-- Full Description -->
        <div class="mb-6">
            <p id="service-modal-desc" class="font-body text-xs sm:text-sm text-[#B5B5B5] leading-relaxed font-normal">
                Deskripsi lengkap layanan.
            </p>
        </div>

        <!-- Suggested Area -->
        <div class="p-4 bg-[var(--surface)] rounded border border-[var(--border)] mb-6 flex items-center space-x-3">
            <span class="material-symbols-outlined text-[#F5F2EE]">meeting_room</span>
            <div class="text-xs">
                <span class="font-semibold text-[#F5F5F5] block">Rekomendasi Area:</span>
                <span id="service-modal-area" class="text-[#B5B5B5]">Indoor / Outdoor</span>
            </div>
        </div>

        <!-- Action: Reservasi Sekarang Button -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <button id="service-modal-reserve-btn" 
                    type="button" 
                    class="w-full sm:flex-1 py-3.5 bg-[#F5F2EE] text-[#111111] text-xs font-semibold uppercase tracking-widest rounded hover:bg-white transition-colors text-center">
                Reservasi Acara Ini
            </button>
            <button onclick="closeServiceDetailModal()" 
                    type="button" 
                    class="w-full sm:w-auto px-6 py-3.5 border border-[var(--btn-outline)] text-[#F5F5F5] text-xs font-semibold uppercase tracking-widest rounded hover:border-white hover:bg-white/5 transition-colors">
                Tutup
            </button>
        </div>

    </div>
</div>
