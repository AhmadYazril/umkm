<!-- SECTION 8: CONTACT & LOCATION (MODERN MONOCHROME EDITORIAL EXACT MATCH) -->
<section id="contact" class="py-16 sm:py-20 lg:py-24 bg-[#FAFAFA] border-b border-[#E5E5E5] overflow-hidden">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 xl:gap-20 items-center">
            
            <!-- Left Info Column (6 Cols) -->
            <div class="lg:col-span-6 reveal-on-scroll">
                
                <!-- Category Label -->
                <span class="text-xs sm:text-[13px] font-bold text-[#666666] uppercase tracking-[0.22em] font-body block mb-3">
                    LOKASI &amp; KONTAK RESMI
                </span>

                <!-- Main Headline (Consistent with About & Layanan Sans-Serif Style) -->
                <h2 class="font-body text-3xl sm:text-4xl lg:text-[44px] font-semibold text-[#1C1C1C] leading-[1.15] tracking-tight mb-8">
                    Kunjungi Nucomu<br>di Tulungagung.
                </h2>

                <!-- 3-Tab Segmented Switcher & Prev/Next Arrows -->
                <div class="flex items-center gap-3 mb-8">
                    <!-- Segmented Tab Pill Group with Smooth Sliding Highlight -->
                    <div id="contact-tab-pill-container" class="relative inline-flex items-center p-1 bg-[#EBEBEB] rounded-full shadow-2xs select-none" role="tablist" aria-label="Pilih Informasi Kontak">
                        <!-- Sliding Black Pill Highlight Indicator -->
                        <span id="contact-tab-slider" 
                              class="absolute top-1 bottom-1 rounded-full bg-[#1C1C1C] shadow-xs pointer-events-none transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] will-change-[left,width] z-0"
                              style="left: 4px; width: 88px;"
                              aria-hidden="true">
                        </span>

                        <button type="button" 
                                id="contact-tab-btn-alamat"
                                onclick="switchContactTab('alamat')"
                                role="tab"
                                aria-selected="true"
                                aria-controls="contact-panel-alamat"
                                class="contact-tab-btn is-active relative z-10 min-w-[76px] sm:min-w-[90px] px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs font-semibold tracking-wider uppercase text-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]">
                            ALAMAT
                        </button>
                        <button type="button" 
                                id="contact-tab-btn-jam"
                                onclick="switchContactTab('jam')"
                                role="tab"
                                aria-selected="false"
                                aria-controls="contact-panel-jam"
                                class="contact-tab-btn is-inactive relative z-10 min-w-[76px] sm:min-w-[90px] px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs font-semibold tracking-wider uppercase text-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]">
                            JAM
                        </button>
                        <button type="button" 
                                id="contact-tab-btn-whatsapp"
                                onclick="switchContactTab('whatsapp')"
                                role="tab"
                                aria-selected="false"
                                aria-controls="contact-panel-whatsapp"
                                class="contact-tab-btn is-inactive relative z-10 min-w-[76px] sm:min-w-[90px] px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs font-semibold tracking-wider uppercase text-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#1C1C1C]">
                            WHATSAPP
                        </button>
                    </div>

                    <!-- Category Arrows: Navigasi Pindah Tab (< / >) -->
                    <div class="flex items-center gap-1.5 ml-1" role="group" aria-label="Navigasi Tab Kontak">
                        <button type="button" 
                                onclick="navigateContactTab(-1)" 
                                class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] text-[#1C1C1C] hover:bg-[#1C1C1C] hover:text-white hover:border-[#1C1C1C] transition-all duration-200 flex items-center justify-center shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#1C1C1C] active:scale-95"
                                aria-label="Tab sebelumnya"
                                title="Sebelumnya">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" 
                                onclick="navigateContactTab(1)" 
                                class="w-9 h-9 rounded-full bg-white border border-[#E5E5E5] text-[#1C1C1C] hover:bg-[#1C1C1C] hover:text-white hover:border-[#1C1C1C] transition-all duration-200 flex items-center justify-center shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#1C1C1C] active:scale-95"
                                aria-label="Tab berikutnya"
                                title="Berikutnya">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tab Content Panels Container (Minimum height prevents layout shifts) -->
                <div class="min-h-[160px] sm:min-h-[175px] mb-8 flex flex-col justify-center">
                    
                    <!-- TAB 1: ALAMAT -->
                    <div id="contact-panel-alamat" class="contact-tab-panel transition-all duration-300">
                        <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.2em] font-body block mb-2">
                            01 / ALAMAT
                        </span>
                        <p class="font-body text-base sm:text-lg font-semibold text-[#1C1C1C] leading-snug tracking-tight mb-3">
                            {{ $contact['address'] ?? config('nucomu.contact.address', 'Jl. Mayor Sujadi, Manggisan, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221') }}
                        </p>
                        <div class="flex items-start gap-2 text-xs sm:text-[13px] text-[#666666] font-body leading-relaxed">
                            <svg class="w-4 h-4 text-[#8A8A8A] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            <span>Patokan: {{ $contact['landmark'] ?? config('nucomu.contact.landmark', '200 meter ke timur dari Bravo Swalayan (sisi utara jalan sebelum perempatan).') }}</span>
                        </div>
                    </div>

                    <!-- TAB 2: JAM OPERASIONAL -->
                    <div id="contact-panel-jam" class="contact-tab-panel hidden transition-all duration-300">
                        <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.2em] font-body block mb-2">
                            02 / JAM OPERASIONAL
                        </span>
                        
                        <!-- Status Pill Badge -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F5F5F5] border border-[#E5E5E5] text-xs font-medium text-[#1C1C1C] mb-3.5">
                            <span class="w-2 h-2 rounded-full bg-[#1C1C1C] shrink-0"></span>
                            <span id="contact-section-hours-status">Buka sekarang &middot; tutup 21:00 WIB</span>
                        </div>

                        <!-- Schedule Rows -->
                        <div class="space-y-2 font-body text-sm text-[#444444] mb-2.5">
                            <div class="flex items-center justify-between sm:justify-start sm:gap-8">
                                <span class="w-28 text-[#666666]">Senin &ndash; Sabtu</span>
                                <span class="font-bold text-[#1C1C1C]">11:00 &ndash; 21:00 WIB</span>
                            </div>
                            <div class="flex items-center justify-between sm:justify-start sm:gap-8">
                                <span class="w-28 text-[#666666]">Minggu</span>
                                <span class="font-bold text-[#1C1C1C]">11:00 &ndash; 22:00 WIB</span>
                            </div>
                        </div>
                        <p class="font-body text-xs text-[#8A8A8A]">
                            {{ config('nucomu.contact.hours_brew', 'Bar kopi & dine-in buka setiap hari.') }}
                        </p>
                    </div>

                    <!-- TAB 3: WHATSAPP -->
                    <div id="contact-panel-whatsapp" class="contact-tab-panel hidden transition-all duration-300">
                        <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.2em] font-body block mb-2">
                            03 / LAYANAN WHATSAPP
                        </span>
                        <div class="mb-4">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact['whatsapp_number'] ?? config('nucomu.contact.whatsapp_number', '6285934960910')) }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="inline-block font-body text-2xl sm:text-3xl font-bold text-[#1C1C1C] pb-1 border-b-2 border-[#1C1C1C] hover:text-[#555555] hover:border-[#555555] transition-colors tracking-tight">
                                {{ $contact['whatsapp_display'] ?? config('nucomu.contact.whatsapp_display', '+62 859-3496-0910') }}
                            </a>
                        </div>
                        <ul class="space-y-2 font-body text-xs sm:text-[13px] text-[#444444]">
                            <li class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] shrink-0 text-xs font-bold">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span>Pemesanan takeaway</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] shrink-0 text-xs font-bold">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span>Pesan hampers</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] shrink-0 text-xs font-bold">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span>Reservasi meja</span>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Action Buttons (Bottom Left) -->
                <div class="flex items-center gap-3.5 flex-wrap">
                    <!-- Button 1: Buka Rute Google Maps (With Sliding Arrow & Glint Shimmer) -->
                    <a href="{{ $contact['google_maps_url'] ?? $contact['maps_url'] ?? config('nucomu.contact.google_maps_url') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       id="btn-maps-cta"
                       class="relative overflow-hidden inline-flex items-center gap-2.5 px-6 sm:px-7 py-3 sm:py-3.5 bg-[#1C1C1C] hover:bg-black text-white text-xs sm:text-[13px] font-semibold tracking-wide rounded-full shadow-sm hover:shadow-md transition-all duration-200 group active:scale-95 select-none">
                        <!-- Shimmer / Glint Sweep Light Layer -->
                        <span class="contact-btn-shimmer absolute inset-0 -translate-x-full pointer-events-none bg-gradient-to-r from-transparent via-white/35 to-transparent skew-x-[-20deg]" aria-hidden="true"></span>
                        
                        <span class="relative z-10">Buka Rute Google Maps</span>
                        
                        <!-- Smooth Sliding Oscillating Arrow Icon -->
                        <svg class="w-4 h-4 relative z-10 contact-arrow-icon transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <!-- Button 2: Reservasi (With Invert Fill Hover & Mechanical Bounce Lift Click) -->
                    <button type="button" 
                            id="btn-reservation-cta"
                            onclick="if(typeof openReservationModal === 'function') { openReservationModal(); } else { window.location.href='{{ route('home') }}#layanan'; }" 
                            class="relative overflow-hidden inline-flex items-center gap-2 px-5 sm:px-6 py-3 sm:py-3.5 bg-white text-[#1C1C1C] text-xs sm:text-[13px] font-semibold tracking-wide rounded-full border border-[#1C1C1C] shadow-2xs transition-all duration-300 group active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#1C1C1C] select-none">
                        <!-- Invert Fill Background Sweep (Bottom-to-Top) -->
                        <span class="contact-invert-fill absolute inset-0 bg-[#1C1C1C] translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-0 pointer-events-none rounded-full" aria-hidden="true"></span>

                        <svg class="w-4 h-4 text-[#1C1C1C] group-hover:text-white transition-colors duration-300 relative z-10 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span class="relative z-10 text-[#1C1C1C] group-hover:text-white transition-colors duration-300">Reservasi</span>
                    </button>
                </div>

            </div>

            <!-- Right Visual Locator Frame (6 Cols - Interactive 3D Tilt Google Maps & Floating Badges) -->
            <div class="lg:col-span-6 reveal-on-scroll">
                <div class="relative w-full max-w-[490px] sm:max-w-[530px] lg:max-w-[550px] mx-auto p-4 sm:p-5" 
                     id="contact-map-perspective-wrap" 
                     style="perspective: 1200px;">
                    
                    <!-- Backplate Plate (Tilted Soft Grey, 3D Tilt Interactive with Spring Return) -->
                    <div id="contact-map-backplate" 
                         class="absolute inset-0 bg-[#A8A8A8] rounded-[28px] sm:rounded-[36px] shadow-md origin-center pointer-events-none z-0 will-change-transform" 
                         style="transform: rotate(-2deg); transform-style: preserve-3d;" 
                         aria-hidden="true"></div>

                    <!-- Foreground Map Frame (White rounded card with border and shadow) -->
                    <div id="contact-map-card" 
                         class="relative z-10 w-full h-[320px] sm:h-[380px] lg:h-[400px] bg-white rounded-[24px] sm:rounded-[30px] p-2.5 sm:p-3 border border-[#E5E5E5] shadow-xl overflow-hidden flex flex-col group/map will-change-transform"
                         style="transform-style: preserve-3d;">
                        
                        <!-- Inner Container with Border Radius & Overflow Hidden (Ensures borders stay pristine during smooth zoom) -->
                        <div class="w-full h-full relative rounded-[18px] sm:rounded-[22px] overflow-hidden bg-[#F0F0F0]">
                            
                            <!-- Zoomable Map Target (Smooth Zoom-in on Hover, Zoom-out on Leave) -->
                            <div id="contact-map-zoom-target" class="w-full h-full transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] origin-center will-change-transform">
                                <iframe src="https://maps.google.com/maps?q={{ config('nucomu.contact.cafe_lat', -8.0765099) }},{{ config('nucomu.contact.cafe_lng', 111.918712) }}&hl=id&z=17&t=m&output=embed" 
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

                        </div>

                    </div>

                    <!-- Floating Badge 1 (Top-Left: Logo Badge) -->
                    <div class="absolute -top-1 -left-1 sm:-top-2 sm:-left-2 z-20 bg-white/95 backdrop-blur-md rounded-2xl p-2.5 sm:p-3 shadow-lg border border-[#E5E5E5] flex flex-col items-center justify-center pointer-events-auto transition-transform duration-300 hover:scale-105">
                        <img src="{{ asset('images/nucomu/footer_logo_monochrome.png') }}" 
                             alt="Nucomu Cafe" 
                             class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-contain mb-1">
                        <span class="text-[8.5px] sm:text-[9px] font-bold tracking-[0.2em] text-[#1C1C1C] uppercase font-body">NUCOMU</span>
                    </div>

                    <!-- Floating Badge 2 (Top-Right: Location Badge) -->
                    <div class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 z-20 bg-white/95 backdrop-blur-md rounded-2xl px-3 sm:px-4 py-2 sm:py-2.5 shadow-lg border border-[#E5E5E5] pointer-events-auto text-left transition-transform duration-300 hover:scale-105">
                        <span class="text-[8.5px] sm:text-[9.5px] font-semibold text-[#8A8A8A] uppercase tracking-[0.18em] font-body block">
                            LOKASI / TULUNGAGUNG
                        </span>
                        <p class="text-xs sm:text-[13px] font-bold text-[#1C1C1C] font-body mt-0.5">
                            Plosokandang, Kedungwaru
                        </p>
                    </div>

                    <!-- Floating Badge 3 (Bottom-Left: Rating & Direct Maps Link Badge) -->
                    <div class="absolute -bottom-1 -left-1 sm:-bottom-2 sm:-left-2 z-20 bg-white/95 backdrop-blur-md rounded-2xl px-3.5 sm:px-4 py-2.5 sm:py-3 shadow-lg border border-[#E5E5E5] pointer-events-auto text-left transition-transform duration-300 hover:scale-105">
                        <p class="text-xs sm:text-[13px] font-bold text-[#1C1C1C] font-body">
                            Nucomu Cafe
                        </p>
                        <div class="flex items-center gap-1.5 text-[11px] text-[#666666] font-body font-medium mt-0.5">
                            <span class="text-[#1C1C1C] font-bold">4.9</span>
                            <span class="text-amber-500">★</span>
                            <span>(74 ulasan)</span>
                        </div>
                        <a href="{{ $contact['google_maps_url'] ?? $contact['maps_url'] ?? config('nucomu.contact.google_maps_url') }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#1C1C1C] hover:text-[#555555] transition-colors mt-1 font-body group">
                            <span class="group-hover:underline">Buka di Google Maps</span>
                            <svg class="w-3 h-3 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>

                    <!-- Floating Badge 4 (Bottom-Right: Circular Pin Button) -->
                    <a href="{{ $contact['google_maps_url'] ?? $contact['maps_url'] ?? config('nucomu.contact.google_maps_url') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       aria-label="Buka Nucomu Cafe di Google Maps" 
                       title="Buka Rute di Google Maps" 
                       class="absolute -bottom-1 -right-1 sm:-bottom-2 sm:-right-2 z-20 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[#1C1C1C] hover:bg-black text-white flex items-center justify-center shadow-xl border-[3px] border-white transition-all duration-300 hover:scale-110 active:scale-95 group pointer-events-auto">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Micro-Interactions Custom Styling for Contact Section -->
<style>
    /* 1. Button Arrow Constant Nudge Animation on Hover */
    @keyframes contactArrowNudge {
        0%, 100% {
            transform: translateX(0);
        }
        50% {
            transform: translateX(5px);
        }
    }
    #btn-maps-cta:hover .contact-arrow-icon {
        animation: contactArrowNudge 0.85s ease-in-out infinite;
    }

    /* 2. Button Shimmer / Glint Sweep Light Effect */
    @keyframes contactGlintSwipe {
        0% {
            transform: translateX(-160%) skewX(-20deg);
            opacity: 0;
        }
        20% {
            opacity: 1;
        }
        100% {
            transform: translateX(260%) skewX(-20deg);
            opacity: 0;
        }
    }
    .contact-btn-shimmer-active {
        animation: contactGlintSwipe 0.75s ease-out forwards;
    }

    /* 3. Tab Panel Fade Transition */
    .contact-tab-panel {
        transition: opacity 0.3s ease, transform 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    }

    /* 4. Tab Pill Button Active/Inactive System */
    .contact-tab-btn {
        transition: color 0.25s ease-out;
        cursor: pointer;
        user-select: none;
        -webkit-user-select: none;
    }
    .contact-tab-btn.is-active,
    .contact-tab-btn[aria-selected="true"] {
        color: #FFFFFF !important;
    }
    .contact-tab-btn.is-inactive,
    .contact-tab-btn[aria-selected="false"] {
        color: #666666 !important;
    }
    .contact-tab-btn.is-inactive:hover,
    .contact-tab-btn[aria-selected="false"]:hover {
        color: #1C1C1C !important;
    }
</style>

<!-- Micro-Interactions Script for Contact Section -->
<script>
    (function() {
        const contactTabs = ['alamat', 'jam', 'whatsapp'];
        let currentContactTabIndex = 0;

        // --- TAB SLIDING HIGHLIGHT LOGIC ---
        function updateTabHighlight(tabName) {
            const container = document.getElementById('contact-tab-pill-container');
            const slider = document.getElementById('contact-tab-slider');
            const activeBtn = document.getElementById('contact-tab-btn-' + tabName);
            if (!container || !slider || !activeBtn) return;

            // Pixel-accurate offset & width within the segmented container
            const containerRect = container.getBoundingClientRect();
            const btnRect = activeBtn.getBoundingClientRect();
            const left = Math.round(btnRect.left - containerRect.left);
            const width = Math.round(btnRect.width);

            slider.style.left = left + 'px';
            slider.style.width = width + 'px';

            contactTabs.forEach(function(tab) {
                const btn = document.getElementById('contact-tab-btn-' + tab);
                if (!btn) return;
                const isActive = (tab === tabName);
                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                if (isActive) {
                    btn.classList.add('is-active');
                    btn.classList.remove('is-inactive');
                    btn.style.color = '#FFFFFF';
                } else {
                    btn.classList.remove('is-active');
                    btn.classList.add('is-inactive');
                    btn.style.color = '';
                }
            });
        }

        window.switchContactTab = function(tabName) {
            const index = contactTabs.indexOf(tabName);
            if (index !== -1) {
                currentContactTabIndex = index;
            }
            renderContactTab();
        };

        window.navigateContactTab = function(direction) {
            currentContactTabIndex = (currentContactTabIndex + direction + contactTabs.length) % contactTabs.length;
            renderContactTab();
        };

        function renderContactTab() {
            const activeTab = contactTabs[currentContactTabIndex];
            updateTabHighlight(activeTab);

            contactTabs.forEach(function(tab) {
                const panel = document.getElementById('contact-panel-' + tab);
                if (!panel) return;
                if (tab === activeTab) {
                    panel.classList.remove('hidden');
                    // Reflow to smoothly trigger fade-in transition
                    void panel.offsetWidth;
                    panel.classList.add('opacity-100');
                    panel.classList.remove('opacity-0', 'translate-y-1');
                } else {
                    panel.classList.add('hidden');
                    panel.classList.remove('opacity-100');
                    panel.classList.add('opacity-0', 'translate-y-1');
                }
            });
        }

        // Initialize Slider position when loaded & on window resize
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', renderContactTab);
        } else {
            renderContactTab();
        }
        window.addEventListener('load', renderContactTab);
        window.addEventListener('resize', function() {
            const activeTab = contactTabs[currentContactTabIndex];
            updateTabHighlight(activeTab);
        });

        // --- BUTTON SHIMMER INTERACTION ---
        const mapsBtn = document.getElementById('btn-maps-cta');
        if (mapsBtn) {
            const shimmer = mapsBtn.querySelector('.contact-btn-shimmer');
            mapsBtn.addEventListener('mouseenter', function() {
                if (!shimmer) return;
                shimmer.classList.remove('contact-btn-shimmer-active');
                void shimmer.offsetWidth; // Reflow to reset and trigger animation
                shimmer.classList.add('contact-btn-shimmer-active');
            });
        }

        // --- 3D PERSPECTIVE TILT & MAP SMOOTH ZOOM ---
        const wrap = document.getElementById('contact-map-perspective-wrap');
        const backplate = document.getElementById('contact-map-backplate');
        const card = document.getElementById('contact-map-card');
        const zoomTarget = document.getElementById('contact-map-zoom-target');

        if (wrap && backplate && card) {
            let isHovering = false;
            let rafId = null;
            const defaultBackplateDeg = -2;

            wrap.addEventListener('mouseenter', function() {
                isHovering = true;
                backplate.style.transition = 'transform 0.15s ease-out';
                card.style.transition = 'transform 0.15s ease-out';
                if (zoomTarget) {
                    zoomTarget.style.transform = 'scale(1.08)';
                }
            });

            wrap.addEventListener('mousemove', function(e) {
                if (!isHovering) return;
                const rect = wrap.getBoundingClientRect();
                const normX = ((e.clientX - rect.left) / rect.width) - 0.5;
                const normY = ((e.clientY - rect.top) / rect.height) - 0.5;

                if (rafId) cancelAnimationFrame(rafId);
                rafId = requestAnimationFrame(function() {
                    const tiltMax = 9;
                    const rotateX = -normY * tiltMax;
                    const rotateY = normX * tiltMax;
                    const dynamicZ = defaultBackplateDeg + (normX * 4);

                    // 3D tilt on the grey background plate
                    backplate.style.transform = 
                        'perspective(1000px) rotateX(' + rotateX.toFixed(2) + 'deg) rotateY(' + rotateY.toFixed(2) + 'deg) rotateZ(' + dynamicZ.toFixed(2) + 'deg) scale3d(1.03, 1.03, 1.03)';

                    // Subtle parallax tilt on the foreground white card
                    card.style.transform = 
                        'perspective(1000px) rotateX(' + (rotateX * 0.35).toFixed(2) + 'deg) rotateY(' + (rotateY * 0.35).toFixed(2) + 'deg) translateZ(8px)';
                });
            });

            wrap.addEventListener('mouseleave', function() {
                isHovering = false;
                if (rafId) cancelAnimationFrame(rafId);

                // Elastic bounce / spring back to resting angle
                backplate.style.transition = 'transform 0.75s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                backplate.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) rotateZ(' + defaultBackplateDeg + 'deg) scale3d(1, 1, 1)';

                card.style.transition = 'transform 0.65s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0)';

                // Smooth zoom out back to 1.0
                if (zoomTarget) {
                    zoomTarget.style.transform = 'scale(1)';
                }
            });
        }

        // Live Operational Hours Calculator
        try {
            const now = new Date();
            const utcTime = now.getTime() + (now.getTimezoneOffset() * 60000);
            const wibDate = new Date(utcTime + (3600000 * 7));
            const day = wibDate.getDay();
            const hour = wibDate.getHours();
            const minute = wibDate.getMinutes();
            const timeDecimal = hour + (minute / 60);

            const isSunday = (day === 0);
            const closingHour = isSunday ? 22 : 21;
            const isOpen = (timeDecimal >= 11 && timeDecimal < closingHour);

            const statusEl = document.getElementById('contact-section-hours-status');
            if (statusEl) {
                if (isOpen) {
                    statusEl.innerHTML = 'Buka sekarang &middot; tutup ' + closingHour + ':00 WIB';
                } else {
                    statusEl.innerHTML = 'Tutup &middot; buka kembali 11:00 WIB';
                }
            }
        } catch(e) {}
    })();
</script>
