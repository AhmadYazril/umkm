<!-- GLOBAL FOOTER -->
<footer class="bg-ink-black text-warm-ivory pt-16 pb-12 border-t border-charcoal">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- Main Footer Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 pb-12 border-b border-charcoal/80">
            
            <!-- Col 1: Brand & Identity (4 Cols) -->
            <div class="lg:col-span-4 flex flex-col">
                <a href="{{ route('home') }}#home" class="flex items-center space-x-3 mb-5 group">
                    <div class="w-10 h-10 rounded-full bg-warm-ivory text-ink-black flex items-center justify-center font-editorial text-xl font-bold">
                        N
                    </div>
                    <div class="flex flex-col">
                        <span class="font-editorial text-2xl font-bold tracking-tight text-warm-ivory">Nucomu Cafe</span>
                        <span class="text-[9px] uppercase tracking-editorial-widest text-medium-gray font-semibold">SLOW HOSPITALITY & ROASTERY</span>
                    </div>
                </a>
                <p class="font-body text-xs text-light-gray/70 leading-relaxed mb-6 max-w-sm font-normal">
                    Ruang santai bernuansa monokrom hangat di Tulungagung untuk menikmati kopi single origin, dessert artisanal, dan momen-momen sederhana yang berkesan.
                </p>
                <div class="mt-auto">
                    <span class="font-editorial text-xl italic text-soft-beige">"Tempat Kembali, Rasa yang Dirindukan."</span>
                </div>
            </div>

            <!-- Col 2: Navigation Links (5 Items strictly: Home, About, Menu, Gallery, Layanan) (3 Cols) -->
            <div class="lg:col-span-3">
                <h4 class="text-[11px] font-semibold uppercase tracking-editorial-widest text-medium-gray mb-5">
                    Navigasi
                </h4>
                <ul class="space-y-3 font-body text-xs text-light-gray/80">
                    <li>
                        <a href="{{ route('home') }}#home" class="hover:text-warm-ivory transition-colors">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#about" class="hover:text-warm-ivory transition-colors">About</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#menu" class="hover:text-warm-ivory transition-colors">Menu</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#gallery" class="hover:text-warm-ivory transition-colors">Gallery</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#layanan" class="hover:text-warm-ivory transition-colors">Layanan</a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Operational Hours & Location (2 Cols) -->
            <div class="lg:col-span-2">
                <h4 class="text-[11px] font-semibold uppercase tracking-editorial-widest text-medium-gray mb-5">
                    Jam Buka
                </h4>
                <p class="text-xs text-light-gray/85 font-medium mb-0.5">Senin – Sabtu:</p>
                <p class="font-editorial text-base text-warm-ivory mb-2">11:00 – 21:00 WIB</p>
                <p class="text-xs text-light-gray/85 font-medium mb-0.5">Minggu:</p>
                <p class="font-editorial text-base text-warm-ivory mb-2">11:00 – 22:00 WIB</p>
                <p class="text-[11px] text-medium-gray leading-relaxed">
                    Dine-in, takeaway &amp; reservasi
                </p>
            </div>

            <!-- Col 4: Social Media, Google Maps & WhatsApp (3 Cols) -->
            <div class="lg:col-span-3">
                <h4 class="text-[11px] font-semibold uppercase tracking-editorial-widest text-medium-gray mb-5">
                    Hubungi & Ikuti Kami
                </h4>
                
                <!-- Social Media Links (Instagram, TikTok, WhatsApp, Google Maps) -->
                <div class="flex items-center space-x-3 mb-5">
                    <!-- Instagram -->
                    <a href="{{ config('nucomu.contact.instagram_url') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-charcoal text-warm-ivory flex items-center justify-center hover:bg-warm-ivory hover:text-ink-black transition-colors"
                       aria-label="Instagram Nucomu Cafe">
                        <span class="text-xs font-bold font-body">IG</span>
                    </a>

                    <!-- TikTok -->
                    <a href="{{ config('nucomu.contact.tiktok_url') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-charcoal text-warm-ivory flex items-center justify-center hover:bg-warm-ivory hover:text-ink-black transition-colors"
                       aria-label="TikTok Nucomu Cafe">
                        <span class="text-xs font-bold font-body">TT</span>
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('nucomu.contact.whatsapp_number')) }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-charcoal text-warm-ivory flex items-center justify-center hover:bg-warm-ivory hover:text-ink-black transition-colors"
                       aria-label="WhatsApp Nucomu Cafe">
                        <span class="material-symbols-outlined text-base">chat</span>
                    </a>

                    <!-- Google Maps Link -->
                    <a href="{{ config('nucomu.contact.google_maps_url') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-charcoal text-warm-ivory flex items-center justify-center hover:bg-warm-ivory hover:text-ink-black transition-colors"
                       aria-label="Google Maps Nucomu Cafe">
                        <span class="material-symbols-outlined text-base">location_on</span>
                    </a>
                </div>

                <p class="text-xs text-light-gray/70">
                    WhatsApp: <span class="text-warm-ivory font-medium">{{ config('nucomu.contact.whatsapp_display') }}</span>
                </p>
                <p class="text-[11px] text-medium-gray mt-1 leading-relaxed">
                    {{ config('nucomu.contact.address') }}
                </p>
            </div>

        </div>

        <!-- Copyright Row (Without Back to Top button as specified) -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-[11px] text-light-gray/50">
            <p>&copy; {{ date('Y') }} Nucomu Cafe. Seluruh Hak Cipta Dilindungi. Tulungagung, Jawa Timur.</p>
            <p class="mt-2 sm:mt-0 text-[10px] text-medium-gray tracking-wider">
                DESIGNED WITH EDITORIAL MINIMALISM & WARM HOSPITALITY
            </p>
        </div>

    </div>
</footer>
