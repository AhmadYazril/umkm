<!-- GLOBAL FOOTER -->
@php
    $brand = $brand ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? \App\Http\Controllers\NucomuController::resolveBrandSettings() : config('nucomu.brand', []));
    $contact = $contact ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? \App\Http\Controllers\NucomuController::getContactSettings() : []);
    $operatingHours = $operatingHours ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? \App\Http\Controllers\NucomuController::getOperatingHoursSchedule() : [
        'days' => [],
        'groups' => [
            ['label' => 'Senin – Sabtu', 'hours' => '11:00 – 21:00 WIB', 'is_closed' => false],
            ['label' => 'Minggu', 'hours' => '11:00 – 22:00 WIB', 'is_closed' => false],
        ],
        'is_open' => true,
        'status_text_html' => 'Buka sekarang &bull; tutup 21:00 WIB',
    ]);
    $footerAddress = $contact['address'] ?? config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221');
    $footerWaClean = $contact['whatsapp_clean'] ?? config('nucomu.contact.whatsapp_number', '6281234567890');
    $footerWaDisplay = $contact['whatsapp_display'] ?? config('nucomu.contact.whatsapp_display', '+62 812-3456-7890');
    $footerInstagram = $contact['instagram'] ?? null;
    $footerTiktok = $contact['tiktok'] ?? null;
    $footerGrabfood = $contact['grabfood_url'] ?? null;
    $footerMapsUrl = $contact['maps_url'] ?? config('nucomu.contact.google_maps_url', 'https://maps.google.com/?q=Nucomu+Cafe+Tulungagung');
    $footerEmail = $contact['email'] ?? null;
    $footerHours = $contact['hours'] ?? config('nucomu.contact.hours', 'Senin – Sabtu: 11:00 – 21:00 WIB | Minggu: 11:00 – 22:00 WIB');
    $footerBrandName = $brand['name'] ?? config('nucomu.brand.name', 'Nucomu Cafe');
    $footerLogoUrl = !empty($brand['has_custom_logo']) ? $brand['logo_url'] : asset('images/nucomu/footer_logo_monochrome.png');

    $isOpen = (bool) ($operatingHours['is_open'] ?? true);
    $statusText = $operatingHours['status_text_html'] ?? 'Buka sekarang &bull; tutup 21:00 WIB';
@endphp

<footer id="global-footer" 
        data-navbar-theme="dark"
        class="relative bg-[#111113] text-zinc-300 pt-10 md:pt-16 pb-8 border-t border-white/10 overflow-hidden font-body select-none-bg"
        style="background-color: #111113;">

    <!-- Background Artwork Layer (Monochrome Exact Match) -->
    <div class="absolute inset-0 pointer-events-none select-none z-0 overflow-hidden">
        <img src="{{ asset('images/nucomu/footer_bg_monochrome.png') }}" 
             alt="" 
             class="w-full h-full object-cover object-bottom opacity-100" 
             loading="lazy">
        <!-- Mobile-only contrast scrim for crisper legibility on narrow screens -->
        <div class="md:hidden absolute inset-0 bg-gradient-to-b from-[#111113]/80 via-[#111113]/65 to-[#111113]/92" aria-hidden="true"></div>
    </div>

    <!-- Main Footer Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- 4 Columns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 md:gap-8 lg:gap-10 pb-8 md:pb-12">
            
            <!-- Col 1: Brand & Identity (4 Cols) -->
            <div class="lg:col-span-4 flex flex-col justify-between pb-6 border-b border-white/10 md:pb-0 md:border-b-0">
                <div class="flex flex-col items-center text-center md:block md:text-left">
                    <!-- Logo & Brand Header -->
                    <a href="{{ route('home') }}#home" class="inline-flex items-center gap-3 md:gap-3.5 group">
                        <img src="{{ $footerLogoUrl }}" 
                             alt="{{ $footerBrandName }} Logo" 
                             class="w-11 h-11 md:w-12 md:h-12 rounded-full object-contain shrink-0 group-hover:scale-105 transition-transform duration-300 shadow-sm ring-1 ring-white/15 md:ring-0">
                        <div class="flex flex-col text-left">
                            <span class="font-sans text-xl md:text-2xl lg:text-[26px] font-bold tracking-tight text-white leading-tight">{{ $footerBrandName }}</span>
                            <span class="text-[9px] uppercase tracking-[0.22em] text-zinc-400 font-semibold mt-0.5">SLOW HOSPITALITY &amp; ROASTERY</span>
                        </div>
                    </a>

                    <!-- Description Text -->
                    <p class="font-body text-xs text-zinc-300/90 leading-relaxed mt-3.5 md:mt-5 max-w-[330px] md:max-w-sm mx-auto md:mx-0 font-normal [text-wrap:pretty]">
                        Ruang santai bernuansa monokrom hangat di Tulungagung untuk menikmati kopi single origin, dessert artisanal, dan momen-momen sederhana yang berkesan.
                    </p>

                    <!-- Luxury Editorial Quote Block (Hidden on Mobile, Preserved on Desktop md+) -->
                    <div class="footer-quote-box hidden md:flex mt-6 relative items-stretch gap-4 cursor-pointer group/quote select-none py-2 px-1 transition-all duration-500 hover:translate-x-1.5"
                         id="footer-quote-box"
                         role="region"
                         aria-label="Nucomu Cafe Slogan">

                        <!-- Ambient Radial Backlight Glow on hover -->
                        <div class="absolute -inset-2 rounded-2xl bg-gradient-to-r from-white/[0.06] via-[#c2a378]/[0.05] to-transparent opacity-0 group-hover/quote:opacity-100 transition-opacity duration-700 pointer-events-none blur-md"></div>

                        <!-- Oversized Decorative Quotation Mark Watermark -->
                        <span class="absolute -top-3 left-1 font-sans italic font-bold text-5xl sm:text-6xl text-white/[0.05] select-none pointer-events-none leading-none transition-all duration-500 group-hover/quote:text-[#c2a378]/25 group-hover/quote:-translate-y-0.5" aria-hidden="true">&ldquo;</span>

                        <!-- Vertical Accent Bar with Slow-Drip Traveling Light Bead -->
                        <div class="w-[2.5px] bg-zinc-800/90 rounded-full relative overflow-hidden shrink-0 self-stretch min-h-[52px]">
                            <!-- Base gradient bar: scaleY(0) -> scaleY(1) on scroll reveal -->
                            <div class="footer-quote-line absolute inset-0 bg-gradient-to-b from-white via-zinc-200 to-[#c2a378] w-full rounded-full origin-top transition-transform duration-700 ease-out" style="transform: scaleY(0);"></div>
                            <!-- Slow-Drip Traveling Glowing Light Droplet -->
                            <div class="footer-quote-bead"></div>
                        </div>

                        <!-- Quote Text with Masked Stagger Reveal & Continuous Warm Champagne Liquid Shimmer -->
                        <div class="footer-quote-content relative z-10 py-0.5 flex flex-col justify-center">
                            <p class="font-sans italic font-semibold text-lg sm:text-[21px] text-zinc-200 leading-[1.3] footer-quote-text">
                                <span class="footer-quote-row-wrap block overflow-hidden">
                                    <span class="footer-quote-row block transition-all duration-700 ease-out">
                                        "Tempat Kembali, Rasa yang
                                    </span>
                                </span>
                                <span class="footer-quote-row-wrap block overflow-hidden mt-0.5">
                                    <span class="footer-quote-row block transition-all duration-700 ease-out" style="transition-delay: 160ms;">
                                        Dirindukan."
                                    </span>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: Navigation Links (2 Cols) -->
            <div class="lg:col-span-2 text-center md:text-left">
                <h4 class="text-[11px] md:text-xs font-bold tracking-[0.2em] md:tracking-[0.18em] text-white uppercase">
                    NAVIGASI
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-4 md:mb-6 mx-auto md:mx-0"></div>

                <ul class="flex flex-wrap items-center justify-center gap-2 md:block md:space-y-3 font-body text-xs" id="footer-nav-list">
                    @php
                        $footerNavLinks = [
                            ['label' => 'Home', 'url' => route('home').'#home'],
                            ['label' => 'About', 'url' => route('home').'#about'],
                            ['label' => 'Menu', 'url' => route('home').'#menu'],
                            ['label' => 'Gallery', 'url' => route('home').'#gallery'],
                            ['label' => 'Layanan', 'url' => route('home').'#layanan'],
                        ];
                    @endphp

                    @foreach ($footerNavLinks as $idx => $link)
                        <li class="footer-nav-item" style="transition-delay: {{ $idx * 80 + 100 }}ms;">
                            <a href="{{ $link['url'] }}" class="group relative inline-flex items-center justify-center min-h-[40px] px-4 py-2 rounded-full bg-white/[0.05] border border-white/10 active:scale-95 active:bg-white/15 md:min-h-0 md:px-0 md:py-0.5 md:rounded-none md:bg-transparent md:border-0 md:active:scale-100 md:active:bg-transparent text-zinc-200 md:text-zinc-300 hover:text-white transition-all duration-200 font-medium md:font-normal">
                                <span class="hidden md:inline-block text-zinc-500 mr-2 text-[11px] font-mono group-hover:text-white group-hover:translate-x-[3px] transition-all duration-200">&gt;</span>
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
                <h4 class="text-[11px] md:text-xs font-bold tracking-[0.2em] md:tracking-[0.18em] text-white uppercase">
                    JAM BUKA
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-3.5 md:mb-4 mx-auto md:mx-0"></div>

                <!-- Real-time Status Badge with Pulsing Glow -->
                <div id="footer-status-badge" class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-zinc-900/90 border border-white/10 text-[11px] text-zinc-300 backdrop-blur-sm mb-3.5 shadow-sm transition-all duration-300 mx-auto md:mx-0">
                    <div class="relative flex items-center justify-center w-2.5 h-2.5 shrink-0">
                        <span id="footer-status-glow" class="absolute inset-0 rounded-full {{ $isOpen ? 'footer-pulse-open' : 'footer-pulse-closed' }}"></span>
                        <span id="footer-status-dot" class="w-2 h-2 rounded-full relative z-10 {{ $isOpen ? 'bg-white shadow-[0_0_8px_rgba(255,255,255,0.95)]' : 'bg-zinc-400' }}"></span>
                    </div>
                    <span id="footer-status-text" class="tracking-tight">{!! $statusText !!}</span>
                </div>

                <!-- Hours Card with Elevation & Row Highlight & Icon Micro-Animations -->
                @php $footerOpGroups = $operatingHours['groups'] ?? []; @endphp
                <div class="footer-hours-card {{ count($footerOpGroups) === 2 ? 'grid grid-cols-2 divide-x divide-white/10' : 'block space-y-1' }} md:block md:divide-x-0 rounded-2xl bg-zinc-900/70 backdrop-blur-md border border-white/10 p-2 md:p-3.5 shadow-inner transition-all duration-300 hover:border-white/25 hover:shadow-[0_12px_32px_rgba(0,0,0,0.6),0_0_16px_rgba(255,255,255,0.04)] text-left">
                    @foreach($footerOpGroups as $opIdx => $opGroup)
                        @if($opIdx > 0)
                            <!-- Dashed Divider (animated dash-offset on card hover, desktop only) -->
                            <div class="hidden md:block my-2.5 overflow-hidden px-1">
                                <svg class="footer-dash-line w-full h-[2px] block text-white/20" preserveAspectRatio="none" viewBox="0 0 200 2">
                                    <line x1="0" y1="1" x2="200" y2="1" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4, 4" />
                                </svg>
                            </div>
                        @endif
                        <div class="footer-op-row group/row flex flex-col items-center text-center gap-2 px-2 py-2.5 md:flex-row md:items-center md:text-left md:gap-3 md:p-2 rounded-xl transition-colors duration-200 hover:bg-white/[0.05] cursor-default">
                            <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-white/[0.05] border border-white/10 flex items-center justify-center shrink-0 text-zinc-300 transition-colors duration-200 group-hover/row:border-white/25 group-hover/row:text-white">
                                @if($opIdx === 0)
                                    <svg class="footer-sun-icon w-4 h-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="4"/>
                                        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                                    </svg>
                                @else
                                    <svg class="footer-moon-icon w-4 h-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9.5px] md:text-[10px] font-semibold tracking-wider text-zinc-400 uppercase transition-colors group-hover/row:text-zinc-300">{{ strtoupper($opGroup['label']) }}</p>
                                <p class="text-[12px] sm:text-[13px] md:text-sm font-bold text-white tracking-wide mt-0.5 tabular-nums whitespace-nowrap">{{ $opGroup['hours'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Dining info -->
                <div class="flex items-center justify-center md:justify-start gap-2 mt-3 text-[11.5px] md:text-xs text-zinc-400">
                    <svg class="w-3.5 h-3.5 text-zinc-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"/>
                        <path d="M15 2v18"/>
                        <path d="M7 2v20"/>
                        <path d="M3 2v4a4 4 0 0 0 8 0V2"/>
                    </svg>
                    <span>Dine-in, takeaway &amp; reservasi</span>
                </div>
            </div>

            <!-- Col 4: Contact & Social Media (3 Cols) -->
            <div class="lg:col-span-3 text-center md:text-left">
                <h4 class="text-[11px] md:text-xs font-bold tracking-[0.2em] md:tracking-[0.18em] text-white uppercase">
                    HUBUNGI &amp; IKUTI KAMI
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-4 md:mb-5 mx-auto md:mx-0"></div>

                <!-- Dynamic Social Media Icon Circles (Only shown if filled by admin) -->
                <div class="flex items-center justify-center md:justify-start gap-3 md:gap-2.5 mb-4 md:mb-5 flex-wrap">
                    @if(!empty($footerInstagram))
                        <a href="{{ $footerInstagram }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-11 h-11 md:w-9 md:h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group active:scale-95"
                           aria-label="Instagram Nucomu Cafe"
                           title="Instagram Nucomu Cafe">
                            <i class="fa-brands fa-instagram text-base group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if(!empty($footerTiktok))
                        <a href="{{ $footerTiktok }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-11 h-11 md:w-9 md:h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group active:scale-95"
                           aria-label="TikTok Nucomu Cafe"
                           title="TikTok Nucomu Cafe">
                            <i class="fa-brands fa-tiktok text-sm group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if(!empty($footerGrabfood))
                        <a href="{{ $footerGrabfood }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-11 h-11 md:w-9 md:h-9 rounded-full bg-[#00B14F]/90 hover:bg-[#00B14F] text-white border border-[#00B14F]/40 hover:border-[#00B14F] transition-all duration-200 flex items-center justify-center shadow-sm group active:scale-95"
                           aria-label="GrabFood Nucomu Cafe"
                           title="Kunjungi GrabFood Nucomu Cafe">
                            <i class="fa-solid fa-bag-shopping text-sm group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if(!empty($footerEmail))
                        <a href="mailto:{{ $footerEmail }}" 
                           class="w-11 h-11 md:w-9 md:h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group active:scale-95"
                           aria-label="Email Nucomu Cafe"
                           title="{{ $footerEmail }}">
                            <i class="fa-solid fa-envelope text-xs group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if(!empty($footerMapsUrl))
                        <a href="{{ $footerMapsUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-11 h-11 md:w-9 md:h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group active:scale-95"
                           aria-label="Lokasi Google Maps"
                           title="Buka Google Maps">
                            <i class="fa-solid fa-location-dot text-sm group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>

                <!-- Mobile Glass Card Wrapper for Contact Details (Flat & Unchanged on Desktop md+) -->
                <div class="rounded-2xl bg-zinc-900/70 backdrop-blur-md border border-white/10 p-3.5 space-y-3 text-left md:rounded-none md:bg-transparent md:backdrop-blur-none md:border-0 md:p-0 md:space-y-0">
                    <!-- WhatsApp Contact Info -->
                    <div class="flex items-center gap-2.5 text-xs mb-0 md:mb-3 text-zinc-300 pb-3 border-b border-white/10 md:pb-0 md:border-b-0">
                        <span class="w-8 h-8 rounded-full bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center shrink-0 md:w-auto md:h-auto md:bg-transparent md:border-0 md:rounded-none">
                            <i class="fa-brands fa-whatsapp text-emerald-500 text-base shrink-0" aria-hidden="true"></i>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block text-[10px] uppercase tracking-wider text-zinc-400 font-semibold md:inline md:text-xs md:normal-case md:tracking-normal md:text-zinc-300 md:font-normal">WhatsApp<span class="hidden md:inline">:</span></span>
                            <a href="https://wa.me/{{ $footerWaClean }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="inline-flex items-center font-bold text-white hover:underline transition-colors md:ml-1 text-[13px] md:text-xs tabular-nums">
                                {{ $footerWaDisplay }}
                            </a>
                        </span>
                        <a href="https://wa.me/{{ $footerWaClean }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           aria-label="Chat WhatsApp Nucomu Cafe"
                           class="md:hidden shrink-0 inline-flex items-center justify-center px-3 min-h-[34px] rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-[11px] font-semibold active:scale-95 transition-transform">
                            Chat
                        </a>
                    </div>

                    <!-- Address Location Info -->
                    <div class="flex items-start gap-2.5 text-xs text-zinc-400">
                        <span class="w-8 h-8 rounded-full bg-white/[0.05] border border-white/10 flex items-center justify-center shrink-0 mt-0.5 md:w-auto md:h-auto md:bg-transparent md:border-0 md:rounded-none">
                            <i class="fa-solid fa-location-dot text-zinc-400 shrink-0" aria-hidden="true"></i>
                        </span>
                        @if(!empty($footerMapsUrl))
                            <a href="{{ $footerMapsUrl }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="hover:text-zinc-200 transition-colors leading-relaxed text-[12px] md:text-xs text-zinc-300/90 md:text-zinc-400 [text-wrap:pretty]">
                                {{ $footerAddress }}
                            </a>
                        @else
                            <span class="leading-relaxed text-[12px] md:text-xs text-zinc-300/90 md:text-zinc-400 [text-wrap:pretty]">{{ $footerAddress }}</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Copyright & Bottom Row -->
        <div class="pt-6 md:pt-10 pb-2 md:pb-4 border-t border-white/10 md:border-t-0 flex flex-col-reverse md:flex-row items-center justify-between gap-4 text-xs">
            <!-- Copyright Left -->
            <p class="text-zinc-400 text-[11px] md:text-xs tracking-normal text-center md:text-left leading-relaxed max-w-[300px] md:max-w-none [text-wrap:pretty]">
                &copy; {{ date('Y') }} Nucomu Cafe. Seluruh Hak Cipta Dilindungi. Tulungagung, Jawa Timur.
            </p>

            <!-- Back to Top Right -->
            <div class="flex items-center">
                <button type="button"
                        onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        aria-label="Kembali ke atas" 
                        class="inline-flex items-center justify-center gap-2 px-4 h-11 md:w-9 md:h-9 md:px-0 rounded-full bg-zinc-900/90 hover:bg-white text-zinc-200 md:text-zinc-300 hover:text-[#111113] border border-white/15 hover:border-white transition-all duration-200 shadow-md shrink-0 group active:scale-95">
                    <svg class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 15-6-6-6 6"/>
                    </svg>
                    <span class="md:hidden text-[11px] font-semibold tracking-wider uppercase">Kembali ke Atas</span>
                </button>
            </div>
        </div>

    </div>
</footer>

<!-- FOOTER STYLES & MICRO-INTERACTIONS -->
<style>
    /* 1. Staggered Navigation Items */
    .footer-nav-item {
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .footer-nav-visible .footer-nav-item {
        opacity: 1;
        transform: translateY(0);
    }

    /* 2. Live Status Pulsing Glow */
    .footer-pulse-open {
        background-color: rgba(255, 255, 255, 0.7);
        animation: pulseGlowWhite 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulseGlowWhite {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.75);
        }
        70% {
            transform: scale(1.15);
            box-shadow: 0 0 0 6px rgba(255, 255, 255, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
        }
    }

    .footer-pulse-closed {
        background-color: rgba(161, 161, 170, 0.45);
        animation: pulseGlowGrey 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulseGlowGrey {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(161, 161, 170, 0.55);
        }
        70% {
            transform: scale(1.15);
            box-shadow: 0 0 0 5px rgba(161, 161, 170, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(161, 161, 170, 0);
        }
    }

    /* 3. Operational Card Icon Animations */
    /* Sun Icon: smooth continuous rotation */
    .footer-op-row:hover .footer-sun-icon {
        animation: footerSunSpin 6s linear infinite;
    }
    @keyframes footerSunSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Moon Icon: subtle rocking / tilt */
    .footer-op-row:hover .footer-moon-icon {
        animation: footerMoonRock 1.6s ease-in-out infinite;
    }
    @keyframes footerMoonRock {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }

    /* Dotted Line: horizontal dash-offset animation moving left to right */
    .footer-hours-card:hover .footer-dash-line line {
        animation: footerDashShift 0.8s linear infinite;
    }
    @keyframes footerDashShift {
        from { stroke-dashoffset: 0; }
        to { stroke-dashoffset: -16; }
    }

    /* 4. Contact Icon Micro-Bounce */
    .footer-contact-row:hover .footer-contact-icon {
        animation: contactIconBounce 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) 1;
    }
    @keyframes contactIconBounce {
        0%, 100% { transform: translateY(0); }
        35% { transform: translateY(-4px); }
        70% { transform: translateY(1.5px); }
    }

    /* 5. Luxury Editorial Quote Section Animations */
    .footer-quote-row {
        transform: translateY(115%);
        opacity: 0;
        color: #f4f4f5;
        background: linear-gradient(
            105deg,
            #d4d4d8 0%,
            #e4e4e7 25%,
            #ffffff 45%,
            #edd5be 52%,
            #ffffff 60%,
            #e4e4e7 75%,
            #d4d4d8 100%
        );
        background-size: 260% 100%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: footerQuoteShimmer 4.5s ease-in-out infinite;
        transition: filter 0.4s ease, transform 0.4s ease;
    }

    .quote-revealed .footer-quote-line {
        transform: scaleY(1) !important;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.6), 0 0 18px rgba(194, 163, 120, 0.35);
    }

    .quote-revealed .footer-quote-row {
        transform: translateY(0%) !important;
        opacity: 1 !important;
    }

    .quote-revealed .footer-quote-content {
        animation: footerQuoteFloat 5.5s ease-in-out infinite 1s;
    }

    @keyframes footerQuoteFloat {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-2.5px);
        }
    }

    /* Slow-Drip Traveling Glowing Light Bead */
    .footer-quote-bead {
        position: absolute;
        top: -35%;
        left: 0;
        width: 100%;
        height: 35%;
        background: linear-gradient(180deg, transparent 0%, #ffffff 50%, #c2a378 80%, transparent 100%);
        border-radius: 9999px;
        box-shadow: 0 0 8px #ffffff, 0 0 14px #c2a378;
        opacity: 0;
        pointer-events: none;
    }

    .quote-revealed .footer-quote-bead {
        animation: footerBeadTravel 3.8s cubic-bezier(0.4, 0, 0.2, 1) infinite 0.9s;
    }

    @keyframes footerBeadTravel {
        0% {
            top: -35%;
            opacity: 0;
        }
        15% {
            opacity: 1;
        }
        70% {
            opacity: 1;
        }
        85%, 100% {
            top: 115%;
            opacity: 0;
        }
    }

    /* Liquid Warm Champagne & Silver Shimmer Wave */
    @keyframes footerQuoteShimmer {
        0%, 15% {
            background-position: 160% 0;
        }
        60%, 100% {
            background-position: -160% 0;
        }
    }

    /* Hover State: brighter luminous sheen & glowing drop shadow */
    .footer-quote-box:hover .footer-quote-row {
        filter: drop-shadow(0 0 12px rgba(237, 213, 190, 0.45));
    }

    .footer-quote-box:hover .footer-quote-line {
        box-shadow: 0 0 14px rgba(255, 255, 255, 0.9), 0 0 24px rgba(194, 163, 120, 0.7) !important;
    }

    .footer-quote-box:hover .footer-quote-bead {
        animation-duration: 2.2s;
    }

    /* Interactive Click Sparkle */
    .footer-quote-box.quote-sparkle .footer-quote-row {
        animation: footerQuoteSparkle 1.2s ease-out 1, footerQuoteShimmer 4.5s ease-in-out infinite;
    }
    @keyframes footerQuoteSparkle {
        0% {
            filter: drop-shadow(0 0 18px rgba(255, 255, 255, 1)) drop-shadow(0 0 26px rgba(194, 163, 120, 0.9));
        }
        100% {
            filter: drop-shadow(0 0 0px transparent);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .footer-quote-row {
            transform: none !important;
            opacity: 1 !important;
            animation: none !important;
        }
        .footer-quote-line {
            transform: none !important;
        }
        .footer-quote-bead {
            display: none !important;
        }
        .footer-quote-content {
            animation: none !important;
        }
    }

    /* Mobile-only Footer Polish (<= 767px ONLY; Desktop >= 768px untouched) */
    @media (max-width: 767px) {
        #global-footer .footer-nav-item {
            opacity: 1 !important;
            transform: none !important;
        }
        #global-footer #footer-quote-box,
        #global-footer #footer-quote-box * {
            animation: none !important;
        }
    }
</style>

<!-- FOOTER LOGIC & INTERACTIONS SCRIPT -->
<script>
    (function() {
        // --- 1. Real-time Jam Buka Updater (Asia/Jakarta / WIB, Database-backed) ---
        const footerScheduleDays = @json($operatingHours['days'] ?? []);
        function parseFooterTimeMins(str, fallback) {
            if (!str || str.indexOf(':') === -1) return fallback;
            const parts = str.split(':');
            return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10);
        }

        function updateNucomuFooterHours() {
            const badgeText = document.getElementById('footer-status-text');
            const badgeGlow = document.getElementById('footer-status-glow');
            const badgeDot = document.getElementById('footer-status-dot');
            if (!badgeText || !badgeGlow || !badgeDot) return;

            try {
                const now = new Date();
                const jakartaString = now.toLocaleString('en-US', { timeZone: 'Asia/Jakarta' });
                const jakartaTime = new Date(jakartaString);

                const day = jakartaTime.getDay();
                const currentMins = jakartaTime.getHours() * 60 + jakartaTime.getMinutes();

                const todayCfg = footerScheduleDays[day] || { open_time: '11:00', close_time: day === 0 ? '22:00' : '21:00', is_closed: false };
                const openMins = parseFooterTimeMins(todayCfg.open_time, 11 * 60);
                const closeMins = parseFooterTimeMins(todayCfg.close_time, (day === 0 ? 22 : 21) * 60);
                const closeTimeStr = todayCfg.close_time || (day === 0 ? '22:00' : '21:00');
                const openTimeStr = todayCfg.open_time || '11:00';

                const isOpen = !todayCfg.is_closed && (currentMins >= openMins && currentMins < closeMins);

                if (isOpen) {
                    badgeText.innerHTML = `Buka sekarang &bull; tutup ${closeTimeStr} WIB`;
                    badgeGlow.className = 'absolute inset-0 rounded-full footer-pulse-open';
                    badgeDot.className = 'w-2 h-2 rounded-full relative z-10 bg-white shadow-[0_0_8px_rgba(255,255,255,0.95)]';
                } else {
                    let reopenStr = `buka kembali ${openTimeStr} WIB`;
                    if (todayCfg.is_closed || currentMins >= closeMins) {
                        for (let offset = 1; offset <= 7; offset++) {
                            const nextCfg = footerScheduleDays[(day + offset) % 7];
                            if (nextCfg && !nextCfg.is_closed) {
                                reopenStr = offset === 1
                                    ? `buka besok ${nextCfg.open_time || '11:00'} WIB`
                                    : `buka ${nextCfg.day_name || ''} ${nextCfg.open_time || '11:00'} WIB`;
                                break;
                            }
                        }
                    }
                    badgeText.innerHTML = `Tutup &bull; ${reopenStr}`;
                    badgeGlow.className = 'absolute inset-0 rounded-full footer-pulse-closed';
                    badgeDot.className = 'w-2 h-2 rounded-full relative z-10 bg-zinc-400';
                }
            } catch (e) {
                console.warn('Footer hours update warning:', e);
            }
        }

        // Run immediately and every 30 seconds
        updateNucomuFooterHours();
        setInterval(updateNucomuFooterHours, 30000);

        // --- 2. Social Icons Magnetic Pull Effect ---
        const magnetBtns = document.querySelectorAll('.footer-magnet-btn');
        magnetBtns.forEach(btn => {
            btn.addEventListener('mousemove', function(e) {
                const rect = btn.getBoundingClientRect();
                const btnCenterX = rect.left + rect.width / 2;
                const btnCenterY = rect.top + rect.height / 2;
                const deltaX = (e.clientX - btnCenterX) * 0.35;
                const deltaY = (e.clientY - btnCenterY) * 0.35;
                btn.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
            });

            btn.addEventListener('mouseleave', function() {
                btn.style.transform = 'translate(0px, 0px)';
                btn.style.transition = 'transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease';
            });

            btn.addEventListener('mouseenter', function() {
                btn.style.transition = 'transform 0.1s ease-out, background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease';
            });
        });

        // --- 3. Scroll Reveal for Navigation & Quote Block ---
        const footerSection = document.getElementById('global-footer');
        if (footerSection && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Reveal Quote block
                        const quoteBox = footerSection.querySelector('.footer-quote-box');
                        if (quoteBox) quoteBox.classList.add('quote-revealed');

                        // Reveal Navigation list staggered
                        const navList = footerSection.querySelector('#footer-nav-list');
                        if (navList) navList.classList.add('footer-nav-visible');

                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            observer.observe(footerSection);
        } else {
            // Direct fallback
            const quoteBox = document.querySelector('.footer-quote-box');
            if (quoteBox) quoteBox.classList.add('quote-revealed');
            const navList = document.querySelector('#footer-nav-list');
            if (navList) navList.classList.add('footer-nav-visible');
        }

        // --- 4. Interactive Click Sparkle for Quote Block ---
        const quoteBoxEl = document.getElementById('footer-quote-box');
        if (quoteBoxEl) {
            quoteBoxEl.addEventListener('click', function() {
                quoteBoxEl.classList.remove('quote-sparkle');
                void quoteBoxEl.offsetWidth; // force reflow
                quoteBoxEl.classList.add('quote-sparkle');
            });
        }
    })();
</script>
