<!-- SECTION 8: CONTACT & LOCATION (BEFORE FOOTER) -->
<section id="contact" class="py-16 sm:py-20 lg:py-24 bg-[#FAF8F5] border-b border-light-gray/60">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            
            <!-- Left Info Column (5 Cols) -->
            <div class="lg:col-span-5 reveal-on-scroll">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#E06D24]/10 border border-[#E06D24]/20 text-[#E06D24] text-[11px] font-bold uppercase tracking-widest mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                    <span>LOKASI &amp; KONTAK RESMI</span>
                </div>
                <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-[#2A1D17] mb-6 leading-tight">
                    Kunjungi Nucomu di Tulungagung.
                </h2>

                <div class="space-y-5 text-sm text-[#5C4E45] mb-8 font-body">
                    <!-- Address & Landmark -->
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-2xl bg-white border border-stone-200 shadow-2xs flex items-center justify-center text-[#E06D24] shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-xl">location_on</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#2A1D17]">Alamat</h4>
                            <p class="text-xs sm:text-sm text-[#5C4E45] mt-1 leading-relaxed font-medium">
                                {{ config('nucomu.contact.address') }}
                            </p>
                            @if(config('nucomu.contact.landmark'))
                                <span class="inline-block mt-1.5 px-2.5 py-0.5 rounded bg-stone-100 text-[11px] font-semibold text-neutral-700">
                                    Patokan: {{ config('nucomu.contact.landmark') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-2xl bg-white border border-stone-200 shadow-2xs flex items-center justify-center text-[#E06D24] shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-xl">schedule</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#2A1D17]">Jam Operasional</h4>
                            <p class="text-xs sm:text-sm text-[#5C4E45] mt-1 font-semibold text-[#2A1D17]">
                                {{ config('nucomu.contact.hours') }}
                            </p>
                            <span class="text-[11px] text-neutral-500 block mt-0.5">{{ config('nucomu.contact.hours_brew') }}</span>
                        </div>
                    </div>

                    <!-- Direct WhatsApp -->
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-2xl bg-white border border-stone-200 shadow-2xs flex items-center justify-center text-emerald-600 shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-xl">chat</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#2A1D17]">Layanan WhatsApp</h4>
                            <p class="text-xs sm:text-sm text-[#5C4E45] mt-1">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('nucomu.contact.whatsapp_number')) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="text-emerald-700 hover:text-emerald-800 font-bold underline underline-offset-4">
                                    {{ config('nucomu.contact.whatsapp_display') }}
                                </a>
                            </p>
                            <span class="text-[11px] text-neutral-500 block mt-0.5">Pemesanan takeaway, hampers &amp; reservasi</span>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Direction Button -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <a href="{{ config('nucomu.contact.google_maps_url') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center justify-center space-x-2.5 bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3.5 rounded-full shadow-md shadow-orange-500/20 transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined text-base">directions</span>
                        <span>Buka Rute Google Maps</span>
                    </a>
                </div>
            </div>

            <!-- Right Visual Locator Frame (7 Cols - Interactive Google Maps) -->
            <div class="lg:col-span-7 reveal-on-scroll">
                <div class="bg-white p-3 sm:p-4 border border-stone-200/90 rounded-3xl shadow-lg relative">
                    <div class="w-full h-[320px] sm:h-[380px] lg:h-[420px] relative bg-stone-100 overflow-hidden rounded-2xl border border-stone-200">
                        <!-- Interactive Google Maps Iframe -->
                        <iframe src="https://maps.google.com/maps?q=Nucomu+Cafe+Plosokandang+Kedungwaru+Tulungagung&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade" 
                                title="Peta Lokasi Nucomu Cafe Tulungagung"
                                class="w-full h-full object-cover">
                        </iframe>
                    </div>

                    <div class="mt-3.5 px-2 flex flex-col sm:flex-row items-center justify-between gap-2.5 text-xs text-neutral-500 font-medium">
                        <span class="flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Plosokandang, Kedungwaru, Tulungagung</span>
                        </span>
                        <a href="{{ config('nucomu.contact.google_maps_url') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="text-[#E06D24] hover:text-[#c95b17] font-bold inline-flex items-center space-x-1 transition-colors">
                            <span>Buka di Google Maps</span>
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
