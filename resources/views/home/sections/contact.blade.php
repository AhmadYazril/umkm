<!-- SECTION 8: CONTACT & LOCATION (MODERN MONOCHROME EDITORIAL EXACT MATCH) -->
@php
    $contact = $contact ?? \App\Http\Controllers\NucomuController::getContactSettings();
    $mapsEmbedSrc = !empty($contact['maps_embed']) 
        ? $contact['maps_embed'] 
        : 'https://maps.google.com/maps?q=' . urlencode($contact['address'] ?? 'Nucomu Cafe Plosokandang Kedungwaru Tulungagung') . '&t=&z=16&ie=UTF8&iwloc=&output=embed';
    $mapsDirectUrl = !empty($contact['maps_url']) 
        ? $contact['maps_url'] 
        : config('nucomu.contact.google_maps_url', 'https://www.google.com/maps/place/Nucomu+Cafe/@-8.0765095,111.9002579,15z/data=!4m8!3m7!1s0x2e78e3a9244a4fe7:0x516fcae98c42d18!8m2!3d-8.0765099!4d111.918712!9m1!1b1!16s%2Fg%2F11ycy6lc94');

    // VIDEO ANIMASI 3D: Dapat diganti melalui database ($contact['map_video']), config nucomu.php, atau file public/videos/nucomu_3d_map.mp4
    $mapVideoSrc = !empty($contact['map_video']) 
        ? asset($contact['map_video']) 
        : asset(config('nucomu.contact.map_animation_video', 'videos/nucomu_3d_map.mp4'));
    $mapPosterSrc = !empty($contact['map_poster']) 
        ? asset($contact['map_poster']) 
        : asset(config('nucomu.contact.map_animation_poster', 'images/nucomu/nucomu_3d_map_poster.jpg'));
@endphp
<section id="contact" class="py-16 sm:py-20 lg:py-24 bg-[#FAFAFA] border-b border-[#E5E5E5] overflow-hidden relative">
    
    <!-- Subtle Elegant Watermark in Background (Matching Reference Mockup) -->
    <div class="absolute inset-0 flex items-center justify-start pointer-events-none select-none overflow-hidden -z-0 opacity-[0.025]" aria-hidden="true">
        <span class="text-[120px] sm:text-[180px] lg:text-[230px] font-serif font-black tracking-widest text-[#1C1C1C] uppercase leading-none whitespace-nowrap pl-4 sm:pl-8">
            NUCOMU
        </span>
    </div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 relative z-10">
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
                    <!-- Button 1: Buka Rute Google Maps -->
                    <a href="{{ $mapsDirectUrl }}" 
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

            <!-- Right Visual Locator Frame (6 Cols - Interactive 3D Animated Video & Direct Maps Integration) -->
            <div class="lg:col-span-6 reveal-on-scroll">
                <div class="relative w-full max-w-[560px] sm:max-w-[590px] lg:max-w-[620px] mx-auto select-none contact-map-perspective-wrap contact-map-zoom-target" 
                     id="contact-3d-scene-container" 
                     style="perspective: 1200px;">
                    
                    <!-- Ambient Glow Effect behind the Frame -->
                    <div class="absolute -inset-4 sm:-inset-6 bg-gradient-to-tr from-amber-500/10 via-red-500/5 to-transparent rounded-[44px] filter blur-2xl opacity-70 pointer-events-none -z-10" aria-hidden="true"></div>

                    <!-- 3D Interactive Floating Card Wrapper (6s alternate float animation) -->
                    <div id="contact-3d-float-wrapper" class="relative w-full transition-transform duration-500 will-change-transform">
                        
                        <!-- Outer Frame with Border Design (Matching User Reference Image) -->
                        <div id="contact-3d-interactive-card"
                             class="group/card relative block w-full rounded-[28px] sm:rounded-[36px] lg:rounded-[40px] p-2 sm:p-2.5 bg-gradient-to-br from-[#F5F6F8] via-[#ECEEF2] to-[#E2E5E9] border border-[#D0D4DA] shadow-xl sm:shadow-2xl transition-all duration-300 ease-out will-change-transform cursor-pointer"
                             onclick="openNucomuGoogleMaps(event)"
                             title="Buka rute Nucomu Cafe di Google Maps">
                            
                            <!-- Video Inner Viewport with Rounded Corners -->
                            <div class="relative w-full aspect-[16/9] rounded-[22px] sm:rounded-[30px] lg:rounded-[32px] overflow-hidden bg-[#EDEFF2] flex items-center justify-center shadow-inner cursor-pointer">
                                
                                <!-- 3D Animation Video (Easily replaceable via config or by replacing public/videos/nucomu_3d_map.mp4) -->
                                <video 
                                    id="contact-3d-video"
                                    autoplay 
                                    loop 
                                    muted 
                                    playsinline 
                                    webkit-playsinline
                                    preload="auto"
                                    poster="{{ $mapPosterSrc }}"
                                    class="w-full h-full object-cover object-center block pointer-events-none transform transition-transform duration-700 ease-out group-hover/card:scale-[1.02]"
                                    aria-label="Animasi 3D Peta Lokasi Nucomu Cafe Jl. Mayor Sujadi Tulungagung">
                                    <source src="{{ $mapVideoSrc }}" type="video/mp4">
                                    Your browser does not support HTML5 video.
                                </video>

                                <!-- Top Gloss Sheen Reflection -->
                                <div class="absolute inset-x-0 top-0 h-1/4 bg-gradient-to-b from-white/30 to-transparent pointer-events-none" aria-hidden="true"></div>

                                <!-- Glint Shimmer Sweep on Hover -->
                                <span class="contact-card-shimmer absolute inset-0 -translate-x-full pointer-events-none bg-gradient-to-r from-transparent via-white/25 to-transparent skew-x-[-20deg] opacity-0 group-hover/card:opacity-100 transition-opacity" aria-hidden="true"></span>
                            </div>

                            <!-- Prominent Interactive Pill Button: "Buka di Google Maps ↗" (Exact Match with Reference Image) -->
                            <a href="{{ $mapsDirectUrl }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               id="btn-open-in-google-maps"
                               onclick="openNucomuGoogleMaps(event)"
                               class="absolute bottom-4 right-4 sm:bottom-5 sm:right-5 z-30 inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-full bg-[#1C1C1C] hover:bg-black text-white text-xs sm:text-[13px] font-semibold tracking-wide shadow-xl border border-white/20 transition-all duration-300 hover:scale-105 active:scale-95 group/pill focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1C1C1C] cursor-pointer"
                               title="Buka rute Nucomu Cafe di Google Maps"
                               aria-label="Buka di Google Maps">
                                <!-- Pulsing Green Online/GPS Indicator Dot -->
                                <span class="relative flex h-2 sm:h-2.5 w-2 sm:w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 sm:h-2.5 w-2 sm:w-2.5 bg-emerald-400"></span>
                                </span>
                                <span class="font-body text-white">Buka di Google Maps</span>
                                <svg class="w-3.5 h-3.5 text-white/90 group-hover/pill:translate-x-0.5 group-hover/pill:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>

                        </div>

                    </div>

                    <!-- Bottom Address & Directions Quick Bar (Exact Match with Reference Image) -->
                    <div class="mt-3.5 px-3 flex items-center justify-between text-xs sm:text-[13px] text-[#555555] font-body">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span class="font-medium text-[#222222]">Jl. Mayor Sujadi, Plosokandang, Tulungagung</span>
                        </div>
                        <a href="{{ $mapsDirectUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1 text-xs sm:text-[13px] font-semibold text-[#1C1C1C] hover:text-[#555555] transition-colors group/petunjuk shrink-0">
                            <span class="group-hover/petunjuk:underline">Petunjuk Arah</span>
                            <svg class="w-3.5 h-3.5 group-hover/petunjuk:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

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

    /* 5. 3D Floating Tablet Animation (6s infinite alternate) */
    @keyframes nucomu3DFloat {
        0% {
            transform: translateY(0px) rotateX(1deg) rotateY(-0.5deg);
        }
        50% {
            transform: translateY(-8px) rotateX(-1.2deg) rotateY(0.6deg);
        }
        100% {
            transform: translateY(0px) rotateX(1deg) rotateY(-0.5deg);
        }
    }
    #contact-3d-float-wrapper {
        animation: nucomu3DFloat 6s ease-in-out infinite alternate;
        transform-style: preserve-3d;
    }
    #contact-3d-float-wrapper:hover {
        animation-play-state: paused;
    }

    /* 6. Card Shimmer Glint Sweep on Hover */
    #contact-3d-interactive-card:hover .contact-card-shimmer {
        animation: contactGlintSwipe 0.9s ease-out forwards;
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

        // --- DIRECT GOOGLE MAPS OPEN CONTROLLER ---
        let lastMapOpenTime = 0;
        window.openNucomuGoogleMaps = function(e) {
            const now = Date.now();
            if (now - lastMapOpenTime < 600) return;
            lastMapOpenTime = now;
            if (e) {
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
            }
            const mapsUrl = @json($mapsDirectUrl);
            window.open(mapsUrl, '_blank', 'noopener,noreferrer');
        };

        // --- 3D PERSPECTIVE INTERACTIVE TILT & CLICK FOR CONTACT 3D MAP ---
        const sceneContainer = document.getElementById('contact-3d-scene-container');
        const card3D = document.getElementById('contact-3d-interactive-card');
        const video3D = document.getElementById('contact-3d-video');

        if (sceneContainer && card3D) {
            let isHovering = false;
            let rafId = null;
            let isPointerDown = false;
            let pointerStartX = 0;
            let pointerStartY = 0;

            card3D.addEventListener('pointerdown', function(e) {
                if (e.button !== 0) return;
                isPointerDown = true;
                pointerStartX = e.clientX;
                pointerStartY = e.clientY;
            });

            card3D.addEventListener('pointerup', function(e) {
                if (!isPointerDown) return;
                isPointerDown = false;
                const moveDistance = Math.hypot(e.clientX - pointerStartX, e.clientY - pointerStartY);
                // Klik / tap valid bila kursor bergerak kurang dari 14px (mencegah salah deteksi saat drag/scroll)
                if (moveDistance < 14) {
                    window.openNucomuGoogleMaps(e);
                }
            });

            sceneContainer.addEventListener('mouseenter', function() {
                isHovering = true;
                card3D.style.transition = 'transform 0.15s ease-out, box-shadow 0.3s ease-out';
            });

            sceneContainer.addEventListener('mousemove', function(e) {
                if (!isHovering) return;
                const rect = sceneContainer.getBoundingClientRect();
                const normX = ((e.clientX - rect.left) / rect.width) - 0.5;
                const normY = ((e.clientY - rect.top) / rect.height) - 0.5;

                if (rafId) cancelAnimationFrame(rafId);
                rafId = requestAnimationFrame(function() {
                    const tiltMax = 7;
                    const rotateX = -normY * tiltMax;
                    const rotateY = normX * tiltMax;
                    card3D.style.transform = 'perspective(1200px) rotateX(' + rotateX.toFixed(2) + 'deg) rotateY(' + rotateY.toFixed(2) + 'deg) scale3d(1.02, 1.02, 1.02)';
                });
            });

            sceneContainer.addEventListener('mouseleave', function() {
                isHovering = false;
                if (rafId) cancelAnimationFrame(rafId);
                card3D.style.transition = 'transform 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.5s ease-out';
                card3D.style.transform = 'perspective(1200px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            });

            // Ensure video keeps playing smoothly when scrolled into view
            if (video3D) {
                if ('IntersectionObserver' in window) {
                    const videoObserver = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            if (entry.isIntersecting) {
                                const playPromise = video3D.play();
                                if (playPromise !== undefined) {
                                    playPromise.catch(function() {});
                                }
                            }
                        });
                    }, { threshold: 0.15 });
                    videoObserver.observe(video3D);
                } else {
                    video3D.play().catch(function() {});
                }
            }
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
