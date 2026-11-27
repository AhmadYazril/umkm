<!-- GLOBAL FOOTER -->
@php
    $brand = $brand ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? \App\Http\Controllers\NucomuController::resolveBrandSettings() : config('nucomu.brand', []));
    $contact = $contact ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? \App\Http\Controllers\NucomuController::getContactSettings() : []);
    $operatingHours = $operatingHours ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? \App\Http\Controllers\NucomuController::getOperatingHoursSchedule() : [
        'days' => [],
        'groups' => [
            ['label' => 'Senin', 'hours' => 'Libur / Tutup', 'is_closed' => true],
            ['label' => 'Selasa – Jumat', 'hours' => '11:00 – 20:30 WIB', 'is_closed' => false],
            ['label' => 'Sabtu – Minggu', 'hours' => '11:00 – 21:30 WIB', 'is_closed' => false],
        ],
        'is_open' => true,
        'status_text_html' => 'Buka sekarang &bull; tutup 20:30 WIB',
        'status_text_middot' => 'Buka sekarang &middot; tutup 20:30 WIB',
    ]);
    $footerAddress = $contact['address'] ?? config('nucomu.contact.address', 'Jl. Mayor Sujadi, Manggisan, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221');
    $footerWaClean = $contact['whatsapp_clean'] ?? config('nucomu.contact.whatsapp_number', '6285934960910');
    $footerWaDisplay = $contact['whatsapp_display'] ?? config('nucomu.contact.whatsapp_display', '+62 859-3496-0910');
    
    // Explicitly connect to nucomu.cafe as requested
    $footerInstagram = !empty($contact['instagram']) && !str_contains($contact['instagram'], 'placeholder')
        ? (str_starts_with($contact['instagram'], 'http') ? $contact['instagram'] : 'https://www.instagram.com/'.ltrim($contact['instagram'], '@'))
        : 'https://www.instagram.com/nucomu.cafe';
        
    $footerTiktok = !empty($contact['tiktok']) && !str_contains($contact['tiktok'], 'placeholder')
        ? (str_starts_with($contact['tiktok'], 'http') ? $contact['tiktok'] : 'https://www.tiktok.com/@'.ltrim($contact['tiktok'], '@'))
        : 'https://www.tiktok.com/@nucomu.cafe';

    $footerFacebook = $contact['facebook'] ?? 'https://facebook.com/nucomucafe';
    $footerYoutube = $contact['youtube'] ?? 'https://youtube.com/@nucomucafe';
    $footerTwitter = $contact['twitter'] ?? 'https://x.com/nucomucafe';
    $footerMapsUrl = $contact['maps_url'] ?? config('nucomu.contact.google_maps_url', 'https://maps.google.com/?q=Nucomu+Cafe+Tulungagung');
    $footerBrandName = $brand['name'] ?? config('nucomu.brand.name', 'Nucomu Cafe');
    $footerLogoUrl = asset('images/nucomu/footer_logo_monochrome.png');

    $isOpen = (bool) ($operatingHours['is_open'] ?? true);
    $statusText = $operatingHours['status_text_middot'] ?? ($isOpen ? 'Buka sekarang &middot; tutup 20:30 WIB' : 'Tutup &middot; buka besok 11:00 WIB');

    // Day of week calculation for schedule card highlight (Asia/Jakarta)
    $nowWib = \Carbon\Carbon::now('Asia/Jakarta');
    $currentDow = (int) $nowWib->dayOfWeek; // 0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat
    $isMon = ($currentDow === 1);
    $isTueFri = ($currentDow >= 2 && $currentDow <= 5);
    $isSatSun = ($currentDow === 6 || $currentDow === 0);
@endphp

<footer id="global-footer" 
        data-navbar-theme="dark"
        class="relative bg-[#0E0E10] text-[#D4D4D4] pt-14 md:pt-16 pb-8 border-t border-white/10 overflow-hidden font-body select-none-bg"
        style="background-color: #0E0E10;">

    <!-- Background Artwork Layer (Monochrome Exact Match with bottom watermark & right logo) -->
    <div class="absolute inset-0 pointer-events-none select-none z-0 overflow-hidden">
        <img src="{{ asset('images/nucomu/footer_bg_monochrome.png') }}" 
             alt="" 
             class="w-full h-full object-cover object-bottom opacity-100 transition-opacity duration-700" 
             loading="lazy">
        <!-- Mobile-only contrast scrim for crisper legibility on small screens -->
        <div class="md:hidden absolute inset-0 bg-gradient-to-b from-[#0E0E10]/85 via-[#0E0E10]/60 to-[#0E0E10]/95" aria-hidden="true"></div>
    </div>

    <!-- Main Footer Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        <!-- 4 Columns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-8 md:pb-10">
            
            <!-- Col 1: Brand & Tagline (4 Cols) -->
            <div class="lg:col-span-4 flex flex-col justify-between pb-6 border-b border-white/10 md:pb-0 md:border-b-0">
                <div class="flex flex-col items-center text-center md:block md:text-left">
                    <!-- Logo & Brand Header with Micro-Interaction -->
                    <a href="{{ route('home') }}#home" class="inline-flex items-center gap-3.5 group">
                        <img src="{{ $footerLogoUrl }}" 
                             alt="{{ $footerBrandName }} Logo" 
                             class="w-12 h-12 rounded-full object-contain shrink-0 group-hover:scale-105 group-hover:rotate-2 transition-transform duration-300 bg-white p-0.5 shadow-sm ring-1 ring-white/15">
                        <div class="flex flex-col text-left">
                            <span class="font-sans text-2xl font-bold tracking-tight text-white leading-tight group-hover:text-zinc-100 transition-colors">{{ $footerBrandName }}</span>
                            <span class="text-[9.5px] uppercase tracking-[0.24em] text-zinc-400 font-semibold mt-0.5">SLOW HOSPITALITY &amp; ROASTERY</span>
                        </div>
                    </a>

                    <!-- Description Text -->
                    <p class="font-body text-xs text-zinc-400 leading-relaxed mt-5 max-w-[340px] md:max-w-sm mx-auto md:mx-0 font-normal [text-wrap:pretty]">
                        Ruang santai bernuansa monokrom hangat di Tulungagung untuk menikmati kopi single origin, dessert artisanal, dan momen-momen sederhana yang berkesan.
                    </p>

                    <!-- Exact Editorial Quote Block (with left border & quotes & interactive hover effect) -->
                    <div class="footer-quote-box mt-6 pl-4 border-l-2 border-white/20 py-0.5 text-left max-w-xs mx-auto md:mx-0 cursor-default group/quote">
                        <p class="font-sans italic font-semibold text-lg text-white leading-snug transition-transform duration-300 group-hover/quote:translate-x-1">
                            &ldquo;Tempat Kembali, Rasa yang<br>Dirindukan.&rdquo;
                        </p>
                    </div>
                </div>
            </div>

            <!-- Col 2: Navigation Links (2 Cols) -->
            <div class="lg:col-span-2 text-center md:text-left">
                <h4 class="text-xs font-bold tracking-[0.2em] text-white uppercase">
                    NAVIGASI
                </h4>
                <div class="w-7 h-[2px] bg-white/80 mt-2 mb-6 mx-auto md:mx-0"></div>

                <ul class="flex flex-wrap items-center justify-center gap-2 md:block md:space-y-3.5 font-body text-xs" id="footer-nav-list">
                    @php
                        $footerNavLinks = [
                            ['label' => 'Home', 'url' => route('home').'#home'],
                            ['label' => 'About', 'url' => route('home').'#about'],
                            ['label' => 'Menu', 'url' => route('home').'#menu'],
                            ['label' => 'Gallery', 'url' => route('home').'#gallery'],
                            ['label' => 'Layanan', 'url' => route('home').'#layanan'],
                            ['label' => 'Contact', 'url' => route('home').'#kontak'],
                        ];
                    @endphp

                    @foreach ($footerNavLinks as $idx => $link)
                        <li class="footer-nav-item" style="transition-delay: {{ $idx * 60 + 80 }}ms;">
                            <a href="{{ $link['url'] }}" class="group relative inline-flex items-center text-zinc-400 hover:text-white transition-colors duration-200">
                                <span class="hidden md:inline-block text-zinc-500 mr-2.5 text-[11px] font-mono group-hover:text-white group-hover:translate-x-1 transition-all duration-200">&gt;</span>
                                <span class="relative">
                                    <span>{{ $link['label'] }}</span>
                                    <span class="hidden md:block absolute left-0 -bottom-0.5 w-0 h-[1.5px] bg-white group-hover:w-full transition-all duration-300 ease-out pointer-events-none rounded-full"></span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Col 3: Operational Hours (3 Cols) -->
            <div class="lg:col-span-3 text-center md:text-left">
                <h4 class="text-xs font-bold tracking-[0.2em] text-white uppercase">
                    JAM BUKA
                </h4>
                <div class="w-7 h-[2px] bg-white/80 mt-2 mb-5 mx-auto md:mx-0"></div>

                <!-- Real-time Status Badge with Pulsing Glowing Dot -->
                <div id="footer-status-badge" class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-[#18181B] border border-white/10 text-xs text-zinc-300 mb-4 shadow-sm mx-auto md:mx-0 transition-all duration-300 hover:border-white/25">
                    <span id="footer-status-dot" class="w-2 h-2 rounded-full relative z-10 {{ $isOpen ? 'bg-white footer-dot-pulse' : 'bg-zinc-400' }}"></span>
                    <span id="footer-status-text" class="text-[11px] tracking-tight">{!! $statusText !!}</span>
                </div>

                <!-- Hours Card Container -->
                <div class="rounded-2xl bg-[#141416]/90 border border-white/10 p-3.5 space-y-2 text-left shadow-sm transition-all duration-300 hover:border-white/20">
                    <!-- Row 1: SENIN -->
                    <div class="{{ $isMon ? 'bg-[#242428] border border-white/10 rounded-xl px-3.5 py-2.5 flex items-center justify-between shadow-2xs' : 'px-2 py-1' }}">
                        <div>
                            <span class="text-[10px] font-bold tracking-wider {{ $isMon ? 'text-zinc-200' : 'text-zinc-400' }} uppercase block">SENIN</span>
                            <span class="text-xs {{ $isMon ? 'font-bold text-white' : 'text-zinc-300 font-medium' }} block mt-0.5">Libur / Tutup</span>
                        </div>
                        @if($isMon)
                            <span class="w-2 h-2 rounded-full bg-white shadow-[0_0_8px_rgba(255,255,255,0.85)] animate-pulse shrink-0"></span>
                        @endif
                    </div>

                    <!-- Row 2: SELASA – JUMAT -->
                    <div class="{{ $isTueFri ? 'bg-[#242428] border border-white/10 rounded-xl px-3.5 py-2.5 flex items-center justify-between shadow-2xs' : 'px-2 py-1' }}">
                        <div>
                            <span class="text-[10px] font-bold tracking-wider {{ $isTueFri ? 'text-zinc-200' : 'text-zinc-400' }} uppercase block">SELASA – JUMAT</span>
                            <span class="text-xs {{ $isTueFri ? 'font-bold text-white' : 'text-zinc-300 font-medium' }} tracking-wide block mt-0.5">11:00 – 20:30 WIB</span>
                        </div>
                        @if($isTueFri)
                            <span class="w-2 h-2 rounded-full bg-white shadow-[0_0_8px_rgba(255,255,255,0.85)] animate-pulse shrink-0"></span>
                        @endif
                    </div>

                    <!-- Row 3: SABTU – MINGGU -->
                    <div class="{{ $isSatSun ? 'bg-[#242428] border border-white/10 rounded-xl px-3.5 py-2.5 flex items-center justify-between shadow-2xs' : 'px-2 py-1' }}">
                        <div>
                            <span class="text-[10px] font-bold tracking-wider {{ $isSatSun ? 'text-zinc-200' : 'text-zinc-400' }} uppercase block">SABTU – MINGGU</span>
                            <span class="text-xs {{ $isSatSun ? 'font-bold text-white' : 'text-zinc-300 font-medium' }} block mt-0.5">11:00 – 21:30 WIB</span>
                        </div>
                        @if($isSatSun)
                            <span class="w-2 h-2 rounded-full bg-white shadow-[0_0_8px_rgba(255,255,255,0.85)] animate-pulse shrink-0"></span>
                        @endif
                    </div>
                </div>

                <!-- Dining info with gentle hover animation -->
                <div class="flex items-center justify-center md:justify-start gap-2 mt-3 text-xs text-zinc-400 group cursor-default">
                    <i class="fa-solid fa-utensils text-zinc-400 text-xs group-hover:text-zinc-200 transition-colors"></i>
                    <span class="group-hover:text-zinc-300 transition-colors">Dine-in, takeaway &amp; reservasi</span>
                </div>
            </div>

            <!-- Col 4: Contact & Social Media (3 Cols) -->
            <div class="lg:col-span-3 text-center md:text-left">
                <h4 class="text-xs font-bold tracking-[0.2em] text-white uppercase">
                    HUBUNGI &amp; IKUTI KAMI
                </h4>
                <div class="w-7 h-[2px] bg-white/80 mt-2 mb-5 mx-auto md:mx-0"></div>

                <!-- 5 Social Media Round Buttons in 4-column Grid layout (matching exact mockup with X on 2nd row) -->
                <div class="grid grid-cols-4 gap-2.5 w-max mb-5 mx-auto md:mx-0">
                    <!-- 1. Instagram (@nucomu.cafe) -->
                    <a href="{{ $footerInstagram }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="footer-social-btn w-9 h-9 rounded-full bg-[#18181A] border border-white/10 text-zinc-300 hover:text-white hover:border-white/40 hover:bg-[#252528] flex items-center justify-center transition-all duration-300 shadow-sm group active:scale-95"
                       aria-label="Instagram Nucomu Cafe (nucomu.cafe)"
                       title="Instagram: @nucomu.cafe">
                        <i class="fa-brands fa-instagram text-sm group-hover:scale-115 transition-transform duration-200"></i>
                    </a>

                    <!-- 2. TikTok (@nucomu.cafe) -->
                    <a href="{{ $footerTiktok }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="footer-social-btn w-9 h-9 rounded-full bg-[#18181A] border border-white/10 text-zinc-300 hover:text-white hover:border-white/40 hover:bg-[#252528] flex items-center justify-center transition-all duration-300 shadow-sm group active:scale-95"
                       aria-label="TikTok Nucomu Cafe (nucomu.cafe)"
                       title="TikTok: @nucomu.cafe">
                        <i class="fa-brands fa-tiktok text-xs group-hover:scale-115 transition-transform duration-200"></i>
                    </a>

                    <!-- 3. Facebook -->
                    <a href="{{ $footerFacebook }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="footer-social-btn w-9 h-9 rounded-full bg-[#18181A] border border-white/10 text-zinc-300 hover:text-white hover:border-white/40 hover:bg-[#252528] flex items-center justify-center transition-all duration-300 shadow-sm group active:scale-95"
                       aria-label="Facebook Nucomu Cafe"
                       title="Facebook">
                        <i class="fa-brands fa-facebook-f text-xs group-hover:scale-115 transition-transform duration-200"></i>
                    </a>

                    <!-- 4. YouTube -->
                    <a href="{{ $footerYoutube }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="footer-social-btn w-9 h-9 rounded-full bg-[#18181A] border border-white/10 text-zinc-300 hover:text-white hover:border-white/40 hover:bg-[#252528] flex items-center justify-center transition-all duration-300 shadow-sm group active:scale-95"
                       aria-label="YouTube Nucomu Cafe"
                       title="YouTube">
                        <i class="fa-brands fa-youtube text-xs group-hover:scale-115 transition-transform duration-200"></i>
                    </a>

                    <!-- 5. X / Twitter (Row 2, Aligned below Instagram) -->
                    <a href="{{ $footerTwitter }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="footer-social-btn w-9 h-9 rounded-full bg-[#18181A] border border-white/10 text-zinc-300 hover:text-white hover:border-white/40 hover:bg-[#252528] flex items-center justify-center transition-all duration-300 shadow-sm group active:scale-95"
                       aria-label="X (Twitter) Nucomu Cafe"
                       title="X (Twitter)">
                        <svg class="w-3.5 h-3.5 fill-current group-hover:scale-115 transition-transform duration-200" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>

                <!-- Contact Info List -->
                <div class="space-y-3 text-left">
                    <!-- WhatsApp -->
                    <div class="flex items-center gap-2.5 text-xs text-zinc-300 group">
                        <i class="fa-regular fa-comment-dots text-zinc-400 text-sm shrink-0 group-hover:text-zinc-200 transition-colors"></i>
                        <div class="flex items-center gap-1">
                            <span class="text-zinc-400">WhatsApp:</span>
                            <a href="https://wa.me/{{ $footerWaClean }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="font-bold text-white hover:underline transition-colors hover:text-zinc-100">
                                {{ $footerWaDisplay }}
                            </a>
                        </div>
                    </div>

                    <!-- Address & Route Link -->
                    <div class="flex items-start gap-2.5 text-xs text-zinc-400 group">
                        <i class="fa-solid fa-location-dot text-zinc-400 text-sm shrink-0 mt-0.5 group-hover:text-zinc-200 transition-colors"></i>
                        <div class="leading-relaxed">
                            <p class="m-0 text-zinc-400 leading-relaxed [text-wrap:pretty]">{{ $footerAddress }}</p>
                            @if(!empty($footerMapsUrl))
                                <a href="{{ $footerMapsUrl }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="inline-flex items-center gap-1 text-xs text-zinc-300 hover:text-white hover:underline mt-1.5 font-medium transition-colors group/route">
                                    <span>Buka rute</span>
                                    <span class="text-[11px] group-hover/route:translate-x-1 transition-transform">&rarr;</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Copyright & Bottom Row -->
        <div class="pt-8 mt-10 border-t border-white/[0.08] flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
            <!-- Copyright Left -->
            <p class="text-zinc-500 text-xs text-center md:text-left m-0">
                &copy; {{ date('Y') }} {{ $footerBrandName }}. Seluruh Hak Cipta Dilindungi. Tulungagung, Jawa Timur.
            </p>

            <!-- Center Tagline -->
            <p class="text-[10px] uppercase tracking-[0.25em] text-zinc-500 font-mono text-center m-0">
                CRAFTED WITH CARE &middot; MONOCHROME EDITORIAL
            </p>

            <!-- Back to Top Right with Enhanced Animation -->
            <div class="flex items-center justify-center">
                <button type="button" 
                        onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        aria-label="Kembali ke atas" 
                        title="Kembali ke atas"
                        class="w-8 h-8 rounded-full border border-white/15 bg-[#18181A] text-zinc-300 hover:text-black hover:bg-white flex items-center justify-center transition-all duration-300 cursor-pointer shadow-sm group active:scale-90 hover:scale-110 hover:shadow-[0_0_14px_rgba(255,255,255,0.25)]">
                    <i class="fa-solid fa-arrow-up text-xs group-hover:-translate-y-0.5 transition-transform duration-200"></i>
                </button>
            </div>
        </div>

    </div>
</footer>

<!-- FOOTER STYLES & ANIMATIONS -->
<style>
    #global-footer {
        --footer-bg: #0E0E10;
    }
    
    /* 1. Staggered Navigation Items */
    .footer-nav-item {
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .footer-nav-visible .footer-nav-item {
        opacity: 1;
        transform: translateY(0);
    }

    /* 2. Concentric Pulsing Dot for Status */
    .footer-dot-pulse {
        animation: footerPulseDot 2.2s infinite;
    }
    @keyframes footerPulseDot {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.75), 0 0 8px rgba(255, 255, 255, 0.9);
        }
        70% {
            box-shadow: 0 0 0 7px rgba(255, 255, 255, 0), 0 0 12px rgba(255, 255, 255, 0.5);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0), 0 0 8px rgba(255, 255, 255, 0.9);
        }
    }

    /* 3. Social Media Button Hover Effects */
    .footer-social-btn:hover {
        transform: translateY(-2px) scale(1.08);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5), 0 0 12px rgba(255, 255, 255, 0.15);
    }

    /* 4. Quote Box Micro-interaction */
    .footer-quote-box {
        transition: transform 0.35s ease, border-color 0.35s ease;
    }
    .footer-quote-box:hover {
        border-color: rgba(255, 255, 255, 0.6);
        transform: translateX(4px);
    }

    @media (max-width: 767px) {
        #global-footer .footer-nav-item {
            opacity: 1 !important;
            transform: none !important;
        }
    }
</style>

<script>
    (function() {
        const footerSection = document.getElementById('global-footer');
        if (footerSection && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const navList = footerSection.querySelector('#footer-nav-list');
                        if (navList) navList.classList.add('footer-nav-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            observer.observe(footerSection);
        } else {
            const navList = document.querySelector('#footer-nav-list');
            if (navList) navList.classList.add('footer-nav-visible');
        }
    })();
</script>
