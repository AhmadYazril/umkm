@extends('layouts.app')
@section('title', 'Kontak & Lokasi — Nucomu Cafe')

@section('content')
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
<section class="py-16 min-h-screen bg-[var(--bg-page)] text-[#F5F5F5] relative">
    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-4xl mx-auto mb-12 sm:mb-16 reveal flex flex-col items-center">
            <span class="text-xs font-mono tracking-widest text-[#B5B5B5] uppercase block mb-3 pl-[0.1em]">Hubungi Kami</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-[#F5F5F5] uppercase mx-auto">
                Kontak & <span class="text-[#8F8F8F]">Lokasi</span>
            </h1>
            <p class="text-[#B5B5B5] text-sm sm:text-base leading-relaxed max-w-3xl mx-auto mt-3.5">
                Kunjungi kami di Tulungagung atau hubungi tim Nucomu Cafe untuk pertanyaan, kerjasama, dan reservasi.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
            
            {{-- Info Cards (Col 5) --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- Alamat Card --}}
                <div class="nu-glass-card reveal bg-[var(--surface)] backdrop-blur-md border border-[var(--border)] rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2.5 bg-white/10 border border-[var(--border)] rounded-xl text-[#F5F5F5] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase text-[#F5F5F5]">Alamat Cafe</h3>
                    </div>
                    <p class="text-[#B5B5B5] text-xs leading-relaxed">
                        {{ $contact['address'] ?? config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221') }}
                    </p>
                    @if(!empty($mapsDirectUrl))
                        <div class="mt-3 pt-3 border-t border-[var(--border-soft)]">
                            <a href="{{ $mapsDirectUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#F5F5F5] hover:text-white underline-offset-4 hover:underline">
                                <span>Buka di Google Maps</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- WhatsApp / Telepon Card --}}
                <div class="nu-glass-card reveal bg-[var(--surface)] backdrop-blur-md border border-[var(--border)] rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2.5 bg-white/10 border border-[var(--border)] rounded-xl text-[#F5F5F5] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase text-[#F5F5F5]">WhatsApp & CS</h3>
                    </div>
                    <p class="text-[#B5B5B5] text-xs mb-2">Respon cepat via WhatsApp:</p>
                    <a href="https://wa.me/{{ $contact['whatsapp_clean'] ?? '6281234567890' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 text-sm font-bold text-[#F5F2EE] hover:text-white underline underline-offset-4">
                        <span>{{ $contact['whatsapp_display'] ?? '+62 812-3456-7890' }}</span>
                    </a>
                </div>

                {{-- Jam Operasional Card --}}
                <div class="nu-glass-card reveal bg-[var(--surface)] backdrop-blur-md border border-[var(--border)] rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-white/10 border border-[var(--border)] rounded-xl text-[#F5F5F5] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase text-[#F5F5F5]">Jam Operasional</h3>
                    </div>
                    
                    <div class="space-y-2 text-xs">
                        @if(!empty($contact['opening_hours']))
                            <div class="p-2.5 rounded-xl bg-white/[0.04] border border-[var(--border-soft)] font-medium text-[#F5F5F5]">
                                {{ $contact['opening_hours'] }}
                            </div>
                        @endif
                        @foreach($operatingHours as $oh)
                            <div class="flex justify-between py-1 border-b border-[var(--border-soft)]">
                                <span class="text-[#B5B5B5] font-medium">{{ $oh->day_name }}</span>
                                @if($oh->is_closed)
                                    <span class="text-red-400 font-bold uppercase">LIBUR</span>
                                @else
                                    <span class="text-[#F5F5F5] font-mono tabular-nums">{{ date('H:i', strtotime($oh->open_time)) }} - {{ date('H:i', strtotime($oh->close_time)) }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- 3D Interactive Map Showcase (Col 7 - Matching Reference Border & Direct Maps Integration) --}}
            <div class="reveal lg:col-span-7 flex flex-col justify-center">
                <div class="relative w-full max-w-[620px] mx-auto select-none" id="contact-3d-scene-container" style="perspective: 1200px;">
                    <!-- Ambient Glow -->
                    <div class="absolute -inset-4 sm:-inset-6 rounded-[44px] filter blur-2xl opacity-75 pointer-events-none -z-10" style="background: var(--glow);" aria-hidden="true"></div>

                    <!-- Outer Frame with Border Design -->
                    <div id="contact-3d-interactive-card"
                         class="nu-glass-card group/card relative block w-full rounded-[28px] sm:rounded-[36px] lg:rounded-[40px] p-2 sm:p-2.5 bg-[#121212] border border-[var(--border)] shadow-[var(--shadow)] transition-all duration-300 ease-out will-change-transform cursor-pointer"
                         onclick="openNucomuGoogleMaps(event)"
                         title="Buka rute Nucomu Cafe di Google Maps">
                        
                        <!-- Video Viewport -->
                        <div class="relative w-full aspect-[16/9] rounded-[22px] sm:rounded-[30px] lg:rounded-[32px] overflow-hidden bg-[#0E0E0E] flex items-center justify-center shadow-inner cursor-pointer">
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

                            <!-- Gloss Highlights Overlay -->
                            <div class="absolute inset-x-0 top-0 h-1/4 bg-gradient-to-b from-white/15 to-transparent pointer-events-none" aria-hidden="true"></div>
                        </div>

                        <!-- Prominent Interactive Pill Button: "Buka di Google Maps ↗" -->
                        <a href="{{ $mapsDirectUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           id="btn-open-in-google-maps"
                           onclick="openNucomuGoogleMaps(event)"
                           class="absolute bottom-4 right-4 sm:bottom-5 sm:right-5 z-30 inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-full bg-[#F5F2EE] hover:bg-white text-[#111111] text-xs sm:text-[13px] font-semibold tracking-wide shadow-xl border border-white/25 transition-all duration-300 hover:scale-105 active:scale-95 group/pill focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#121212] focus:ring-[#F5F2EE] cursor-pointer"
                           title="Buka rute Nucomu Cafe di Google Maps"
                           aria-label="Buka di Google Maps">
                            <span class="relative flex h-2 sm:h-2.5 w-2 sm:w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 sm:h-2.5 w-2 sm:w-2.5 bg-emerald-600"></span>
                            </span>
                            <span class="font-body text-[#111111]">Buka di Google Maps</span>
                            <svg class="w-3.5 h-3.5 text-[#111111] group-hover/pill:translate-x-0.5 group-hover/pill:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Bottom Address & Directions Quick Bar -->
                    <div class="mt-3.5 px-3 flex items-center justify-between text-xs sm:text-[13px] text-[#B5B5B5] font-body">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#F5F2EE] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span class="font-medium text-[#F5F5F5]">{{ $contact['address'] ?? 'Jl. Mayor Sujadi, Plosokandang, Kedungwaru, Tulungagung' }}</span>
                        </div>
                        <a href="{{ $mapsDirectUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1 text-xs sm:text-[13px] font-semibold text-[#F5F5F5] hover:text-white transition-colors group/petunjuk shrink-0">
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

@push('scripts')
<script>
    (function() {
        let lastMapOpenTime = 0;
        window.openNucomuGoogleMaps = function(e) {
            const now = Date.now();
            if (now - lastMapOpenTime < 600) return;
            lastMapOpenTime = now;
            if (e && typeof e.stopPropagation === 'function') {
                e.stopPropagation();
            }
            const mapsUrl = @json($mapsDirectUrl);
            window.open(mapsUrl, '_blank', 'noopener,noreferrer');
        };

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
    })();
</script>
@endpush

        </div>

    </div>
</section>
@endsection
