<!-- GLOBAL FOOTER -->
<footer class="relative bg-[#111113] text-zinc-300 pt-16 pb-8 border-t border-white/10 overflow-hidden font-body select-none-bg"
        style="background-color: #111113;">

    <!-- Background Artwork Layer (Monochrome Exact Match) -->
    <div class="absolute inset-0 pointer-events-none select-none z-0 overflow-hidden">
        <img src="{{ asset('images/nucomu/footer_bg_monochrome.png') }}" 
             alt="" 
             class="w-full h-full object-cover object-bottom opacity-100" 
             loading="lazy">
    </div>

    <!-- Main Footer Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- 4 Columns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-12">
            
            <!-- Col 1: Brand & Identity (4 Cols) -->
            <div class="lg:col-span-4 flex flex-col justify-between">
                <div>
                    <!-- Logo & Brand Header -->
                    <a href="{{ route('home') }}#home" class="inline-flex items-center gap-3.5 group">
                        <img src="{{ asset('images/nucomu/footer_logo_monochrome.png') }}" 
                             alt="Nucomu Cafe Logo" 
                             class="w-12 h-12 rounded-full object-contain shrink-0 group-hover:scale-105 transition-transform duration-300 shadow-sm">
                        <div class="flex flex-col">
                            <span class="font-editorial text-2xl lg:text-[26px] font-bold tracking-tight text-white leading-tight">Nucomu Cafe</span>
                            <span class="text-[9px] uppercase tracking-[0.22em] text-zinc-400 font-semibold mt-0.5">SLOW HOSPITALITY &amp; ROASTERY</span>
                        </div>
                    </a>

                    <!-- Description Text -->
                    <p class="font-body text-xs text-zinc-300/90 leading-relaxed mt-5 max-w-sm font-normal">
                        Ruang santai bernuansa monokrom hangat di Tulungagung untuk menikmati kopi single origin, dessert artisanal, dan momen-momen sederhana yang berkesan.
                    </p>

                    <!-- Quote Block -->
                    <div class="mt-6 border-l border-zinc-600 pl-4 py-0.5">
                        <p class="font-editorial italic text-lg sm:text-xl text-zinc-200 leading-snug">
                            "Tempat Kembali, Rasa yang<br>Dirindukan."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Col 2: Navigation Links (2 Cols) -->
            <div class="lg:col-span-2">
                <h4 class="text-xs font-bold tracking-[0.18em] text-white uppercase">
                    NAVIGASI
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-6"></div>

                <ul class="space-y-3 font-body text-xs">
                    <li>
                        <a href="{{ route('home') }}#home" class="group flex items-center text-zinc-300 hover:text-white transition-colors">
                            <span class="text-zinc-500 mr-2 text-[11px] font-mono group-hover:text-white transition-colors">&gt;</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#about" class="group flex items-center text-zinc-300 hover:text-white transition-colors">
                            <span class="text-zinc-500 mr-2 text-[11px] font-mono group-hover:text-white transition-colors">&gt;</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">About</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#menu" class="group flex items-center text-zinc-300 hover:text-white transition-colors">
                            <span class="text-zinc-500 mr-2 text-[11px] font-mono group-hover:text-white transition-colors">&gt;</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">Menu</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#gallery" class="group flex items-center text-zinc-300 hover:text-white transition-colors">
                            <span class="text-zinc-500 mr-2 text-[11px] font-mono group-hover:text-white transition-colors">&gt;</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">Gallery</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#layanan" class="group flex items-center text-zinc-300 hover:text-white transition-colors">
                            <span class="text-zinc-500 mr-2 text-[11px] font-mono group-hover:text-white transition-colors">&gt;</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">Layanan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Operational Hours (3 Cols) -->
            <div class="lg:col-span-3">
                <h4 class="text-xs font-bold tracking-[0.18em] text-white uppercase">
                    JAM BUKA
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-4"></div>

                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900/90 border border-white/10 text-[11px] text-zinc-300 backdrop-blur-sm mb-3.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-zinc-400 shrink-0"></span>
                    <span>Tutup &bull; buka kembali 11:00 WIB</span>
                </div>

                <!-- Hours Card -->
                <div class="rounded-2xl bg-zinc-900/70 backdrop-blur-md border border-white/10 p-3.5 sm:p-4 shadow-inner">
                    <!-- Senin – Sabtu -->
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-white/[0.05] border border-white/10 flex items-center justify-center shrink-0 text-zinc-300">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="4"/>
                                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold tracking-wider text-zinc-400 uppercase">SENIN – SABTU</p>
                            <p class="text-sm font-bold text-white tracking-wide mt-0.5">11:00 – 21:00 WIB</p>
                        </div>
                    </div>

                    <!-- Dashed Divider -->
                    <div class="border-t border-dashed border-white/10 my-3"></div>

                    <!-- Minggu -->
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-white/[0.05] border border-white/10 flex items-center justify-center shrink-0 text-zinc-300">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold tracking-wider text-zinc-400 uppercase">MINGGU</p>
                            <p class="text-sm font-bold text-white tracking-wide mt-0.5">11:00 – 22:00 WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Dining info -->
                <div class="flex items-center gap-2 mt-3 text-xs text-zinc-400">
                    <svg class="w-3.5 h-3.5 text-zinc-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"/>
                        <path d="M15 2v18"/>
                        <path d="M7 2v20"/>
                        <path d="M3 2v4a4 4 0 0 0 8 0V2"/>
                    </svg>
                    <span>Dine-in, takeaway &amp; reservasi</span>
                </div>
            </div>

            <!-- Col 4: Contact & Social Media (3 Cols) -->
            <div class="lg:col-span-3">
                <h4 class="text-xs font-bold tracking-[0.18em] text-white uppercase">
                    HUBUNGI &amp; IKUTI KAMI
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-5"></div>

                <!-- Social Media Icon Circles (Instagram, TikTok, Facebook, YouTube, X) -->
                <div class="flex items-center gap-2.5 mb-5 flex-wrap">
                    <!-- Instagram -->
                    <a href="{{ config('nucomu.contact.instagram_url', 'https://www.instagram.com/nucomu.cafe') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                       aria-label="Instagram Nucomu Cafe">
                        <svg class="w-4 h-4 fill-current group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>

                    <!-- TikTok -->
                    <a href="{{ config('nucomu.contact.tiktok_url', 'https://www.tiktok.com/@nucomu.cafe') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                       aria-label="TikTok Nucomu Cafe">
                        <svg class="w-3.5 h-3.5 fill-current group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298 0 .591.044.87.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.46V11.2a8.27 8.27 0 0 0 5.77 2.27V10.1a4.84 4.84 0 0 1-3.77-3.41h3.77z"/>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="https://facebook.com/nucomucafe" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                       aria-label="Facebook Nucomu Cafe">
                        <svg class="w-3.5 h-3.5 fill-current group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                            <path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.704 0-1.428.18-1.785.541-.357.362-.536 1.052-.536 2.072v1.367h4.721l-.571 3.667h-4.15v7.98H9.101z"/>
                        </svg>
                    </a>

                    <!-- YouTube -->
                    <a href="https://youtube.com/@nucomucafe" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                       aria-label="YouTube Nucomu Cafe">
                        <svg class="w-4 h-4 fill-current group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>

                    <!-- X (Twitter) -->
                    <a href="https://x.com/nucomucafe" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                       aria-label="X (Twitter) Nucomu Cafe">
                        <svg class="w-3.5 h-3.5 fill-current group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>

                <!-- WhatsApp Contact Info -->
                <div class="flex items-center gap-2.5 text-xs mb-3 text-zinc-300">
                    <svg class="w-4 h-4 text-zinc-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                    </svg>
                    <span>
                        WhatsApp: 
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('nucomu.contact.whatsapp_number', '6281234567890')) }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="font-bold text-white hover:underline transition-colors ml-1">
                            {{ config('nucomu.contact.whatsapp_display', '+62 812-3456-7890') }}
                        </a>
                    </span>
                </div>

                <!-- Address Location Info -->
                <div class="flex items-start gap-2.5 text-xs text-zinc-400">
                    <svg class="w-4 h-4 text-zinc-400 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <a href="{{ config('nucomu.contact.google_maps_url', 'https://maps.google.com') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="hover:text-zinc-200 transition-colors leading-relaxed">
                        {{ config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221') }}
                    </a>
                </div>
            </div>

        </div>

        <!-- Copyright & Bottom Row -->
        <div class="pt-10 pb-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <!-- Copyright Left -->
            <p class="text-zinc-400 text-xs tracking-normal text-center sm:text-left">
                &copy; {{ date('Y') }} Nucomu Cafe. Seluruh Hak Cipta Dilindungi. Tulungagung, Jawa Timur.
            </p>

            <!-- Back to Top Right -->
            <div class="flex items-center">
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        aria-label="Kembali ke atas" 
                        class="w-9 h-9 rounded-full bg-zinc-900/90 hover:bg-white text-zinc-300 hover:text-black border border-white/15 hover:border-white transition-all duration-200 flex items-center justify-center shadow-md shrink-0 group">
                    <svg class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m18 15-6-6-6 6"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>
</footer>
