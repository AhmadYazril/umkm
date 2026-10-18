<!-- Reservation Modal Form -->
<div id="reservation-modal" 
     class="fixed inset-0 z-50 bg-ink-black/75 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closeReservationModal()">
    
    <div class="bg-warm-ivory rounded-2xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-soft-beige relative max-h-[92vh] overflow-y-auto"
         onclick="event.stopPropagation()">
        
        <!-- Close Button -->
        <button onclick="closeReservationModal()" 
                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-light-gray/60 hover:bg-ink-black hover:text-warm-ivory text-charcoal flex items-center justify-center transition-colors"
                aria-label="Tutup Formulir Reservasi">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <!-- Modal Header -->
        <div class="border-b border-soft-beige pb-4 mb-5">
            <span class="text-[10px] font-semibold uppercase tracking-editorial-widest text-medium-gray block mb-1">
                FORMULIR KUNJUNGAN RESMI
            </span>
            <h3 class="font-editorial text-2xl sm:text-3xl font-bold text-ink-black">
                Reservasi Nucomu Cafe
            </h3>
            <p class="font-body text-xs text-charcoal/70 mt-1">
                Silakan lengkapi detail berikut. Rincian pengajuan akan otomatis terangkum untuk dikonfirmasi melalui WhatsApp staf Nucomu.
            </p>
        </div>

        <!-- Inline Error Box -->
        <div id="res-error-box" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded leading-relaxed">
            <!-- Injected via JavaScript -->
        </div>

        <!-- Success Feedback Toast -->
        <div id="res-success-toast" class="hidden mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded text-center font-medium">
            Pengajuan berhasil dirangkum. Membuka WhatsApp...
        </div>

        <!-- Reservation Form -->
        <form id="reservation-form" onsubmit="return handleReservationSubmit(event)" class="space-y-4">
            <!-- 1. Nama Pemesan -->
            <div>
                <label for="res-name" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                    Nama Pemesan <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       id="res-name" 
                       name="fullName" 
                       required 
                       placeholder="Contoh: Raditya Danendra" 
                       class="w-full px-3.5 py-2.5 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
            </div>

            <!-- 2. WhatsApp & 3. Jumlah Tamu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="res-phone" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Nomor WhatsApp Aktif <span class="text-red-600">*</span>
                    </label>
                    <input type="tel" 
                           id="res-phone" 
                           name="phone" 
                           required 
                           placeholder="081234567890" 
                           class="w-full px-3.5 py-2.5 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
                <div>
                    <label for="res-guests" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Jumlah Tamu <span class="text-red-600">*</span>
                    </label>
                    <select id="res-guests" 
                            name="guestCount" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                        <option value="1 - 2 Orang">1 – 2 Orang (Meja Intim)</option>
                        <option value="3 - 5 Orang" selected>3 – 5 Orang (Meja Reguler)</option>
                        <option value="6 - 10 Orang">6 – 10 Orang (Meja Komunal)</option>
                        <option value="10+ Orang">10+ Orang (Group Gathering)</option>
                        <option value="Acara Privat (Seluruh Ruang)">Acara Privat (Seluruh Ruang)</option>
                    </select>
                </div>
            </div>

            <!-- 4. Tanggal & 5. Waktu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="res-date" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Tanggal Acara <span class="text-red-600">*</span>
                    </label>
                    <input type="date" 
                           id="res-date" 
                           name="date" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
                <div>
                    <label for="res-time" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Waktu Kedatangan <span class="text-red-600">*</span>
                    </label>
                    <input type="time" 
                           id="res-time" 
                           name="time" 
                           required 
                           value="15:00"
                           class="w-full px-3.5 py-2.5 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                </div>
            </div>

            <!-- 6. Jenis Acara / Layanan & 7. Pilihan Area -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="res-service-type" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Jenis Acara / Layanan <span class="text-red-600">*</span>
                    </label>
                    <select id="res-service-type" 
                            name="serviceType" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                        <option value="Santai & Ngopi Reguler">Santai & Ngopi Reguler</option>
                        <option value="Birthday Treats">Birthday Treats</option>
                        <option value="Bridal Party & Shower">Bridal Party & Shower</option>
                        <option value="Penyewaan Area Cafe">Penyewaan Area Cafe</option>
                        <option value="Engagement Gathering">Engagement Gathering</option>
                        <option value="Workshop & Creative Class">Workshop & Creative Class</option>
                        <option value="Pop-up & Brand Collaboration">Pop-up & Brand Collaboration</option>
                    </select>
                </div>
                <div>
                    <label for="res-area" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                        Pilihan Area <span class="text-red-600">*</span>
                    </label>
                    <select id="res-area" 
                            name="area" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors">
                        <option value="Indoor (AC & Working Space)">Indoor (AC & Working Space)</option>
                        <option value="Outdoor (Smoking Area & Garden)">Outdoor (Smoking Area & Garden)</option>
                        <option value="Fleksibel (Sesuai Ketersediaan)">Fleksibel (Sesuai Ketersediaan)</option>
                    </select>
                </div>
            </div>

            <!-- 8. Catatan Khusus -->
            <div>
                <label for="res-notes" class="block text-[11px] font-semibold uppercase tracking-wider text-charcoal mb-1">
                    Catatan Khusus (Opsional)
                </label>
                <textarea id="res-notes" 
                          name="notes" 
                          rows="2" 
                          placeholder="Contoh: Butuh meja dekat stopkontak, request lilin cake ulang tahun..." 
                          class="w-full px-3.5 py-2 rounded bg-pure-white border border-soft-beige text-xs font-body text-charcoal focus:outline-none focus:border-ink-black transition-colors"></textarea>
            </div>

            <!-- Submit Action -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3.5 bg-ink-black text-warm-ivory text-xs font-semibold uppercase tracking-widest rounded hover:bg-neutral-800 transition-colors flex items-center justify-center space-x-2">
                    <span>Lanjutkan Kirim via WhatsApp</span>
                    <span class="material-symbols-outlined text-sm">send</span>
                </button>
                <p class="text-[10px] text-medium-gray text-center mt-2 leading-relaxed">
                    *Pesan formulir akan disusun dan dibuka di WhatsApp resmi Nucomu Cafe (+62 812-3456-7890) untuk konfirmasi ketersediaan meja.
                </p>
            </div>
        </form>

    </div>
</div>
