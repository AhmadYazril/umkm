<!-- GLOBAL FOOTER -->
@php
    $contact = $contact ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? \App\Http\Controllers\NucomuController::getContactSettings() : []);
    $footerAddress = $contact['address'] ?? config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221');
    $footerWaClean = $contact['whatsapp_clean'] ?? config('nucomu.contact.whatsapp_number', '6281234567890');
    $footerWaDisplay = $contact['whatsapp_display'] ?? config('nucomu.contact.whatsapp_display', '+62 812-3456-7890');
    $footerInstagram = $contact['instagram'] ?? null;
    $footerTiktok = $contact['tiktok'] ?? null;
    $footerGrabfood = $contact['grabfood_url'] ?? null;
    $footerMapsUrl = $contact['maps_url'] ?? config('nucomu.contact.google_maps_url', 'https://maps.google.com/?q=Nucomu+Cafe+Tulungagung');
    $footerEmail = $contact['email'] ?? null;
    $footerHours = $contact['hours'] ?? config('nucomu.contact.hours', 'Senin – Sabtu: 11:00 – 21:00 WIB | Minggu: 11:00 – 22:00 WIB');

    $nowWib = \Carbon\Carbon::now('Asia/Jakarta');
    $dayOfWeek = (int) $nowWib->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
    $currentMinutes = (int) ($nowWib->hour * 60 + $nowWib->minute);

    // Senin – Sabtu: 11:00 – 21:00 WIB (660 - 1260 mins)
    // Minggu: 11:00 – 22:00 WIB (660 - 1320 mins)
    $openMinutes = 11 * 60;
    $closeMinutes = ($dayOfWeek === 0) ? (22 * 60) : (21 * 60);
    $closeTimeStr = ($dayOfWeek === 0) ? '22:00' : '21:00';

    $isOpen = ($currentMinutes >= $openMinutes && $currentMinutes < $closeMinutes);

    if ($isOpen) {
        $statusText = 'Buka sekarang &bull; tutup ' . $closeTimeStr . ' WIB';
    } else {
        if ($currentMinutes < $openMinutes) {
            $statusText = 'Tutup &bull; buka kembali 11:00 WIB';
        } else {
            $statusText = 'Tutup &bull; buka besok 11:00 WIB';
        }
    }
@endphp

<footer id="global-footer" 
        data-navbar-theme="dark"
        class="relative bg-[#111113] text-zinc-300 pt-16 pb-8 border-t border-white/10 overflow-hidden font-body select-none-bg"
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

                    <!-- Quote Block with Scroll Reveal & Typewriter Animation -->
                    <div class="footer-quote-box mt-6 flex items-stretch gap-3.5 cursor-pointer group/quote select-none py-1"
                         id="footer-quote-box"
                         title="Klik untuk memutar ulang efek ketik"
                         role="region"
                         aria-label="Slogan Nucomu Cafe">
                        <!-- Animated vertical line: scaleY(0) -> scaleY(1) with glowing accent -->
                        <div class="w-[2px] bg-zinc-700/80 rounded-full relative overflow-hidden shrink-0 self-stretch min-h-[50px]">
                            <div class="footer-quote-line absolute inset-0 bg-white w-full rounded-full origin-top transition-transform duration-700 ease-out shadow-[0_0_8px_rgba(255,255,255,0.7)]" style="transform: scaleY(0);"></div>
                        </div>
                        <!-- Quote text: typewriter effect with reserved min-height to prevent layout shifts -->
                        <div class="footer-quote-content py-0.5 transition-all duration-700 ease-out opacity-0 -translate-x-[5px] flex items-center min-h-[54px] sm:min-h-[58px]">
                            <p class="font-editorial italic text-lg sm:text-xl text-zinc-200 leading-snug footer-quote-text transition-all duration-300">
                                <span id="footer-typewriter-target" class="footer-quote-text-target">"Tempat Kembali, Rasa yang<br>Dirindukan."</span><span id="footer-typewriter-cursor" class="footer-typewriter-cursor" aria-hidden="true"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: Navigation Links (2 Cols) -->
            <div class="lg:col-span-2">
                <h4 class="text-xs font-bold tracking-[0.18em] text-white uppercase">
                    NAVIGASI
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-6"></div>

                <ul class="space-y-3 font-body text-xs" id="footer-nav-list">
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
                            <a href="{{ $link['url'] }}" class="group relative inline-flex items-center text-zinc-300 hover:text-white transition-colors duration-200 py-0.5">
                                <span class="text-zinc-500 mr-2 text-[11px] font-mono group-hover:text-white group-hover:translate-x-[3px] transition-all duration-200 inline-block">&gt;</span>
                                <span class="relative">
                                    <span>{{ $link['label'] }}</span>
                                    <span class="absolute left-0 -bottom-0.5 w-0 h-[1.5px] bg-white group-hover:w-full transition-all duration-300 ease-out pointer-events-none rounded-full"></span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Col 3: Operational Hours (3 Cols) -->
            <div class="lg:col-span-3">
                <h4 class="text-xs font-bold tracking-[0.18em] text-white uppercase">
                    JAM BUKA
                </h4>
                <div class="w-7 h-[2px] bg-white mt-2 mb-4"></div>

                <!-- Real-time Status Badge with Pulsing Glow -->
                <div id="footer-status-badge" class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-zinc-900/90 border border-white/10 text-[11px] text-zinc-300 backdrop-blur-sm mb-3.5 shadow-sm transition-all duration-300">
                    <div class="relative flex items-center justify-center w-2.5 h-2.5 shrink-0">
                        <span id="footer-status-glow" class="absolute inset-0 rounded-full {{ $isOpen ? 'footer-pulse-open' : 'footer-pulse-closed' }}"></span>
                        <span id="footer-status-dot" class="w-2 h-2 rounded-full relative z-10 {{ $isOpen ? 'bg-white shadow-[0_0_8px_rgba(255,255,255,0.95)]' : 'bg-zinc-400' }}"></span>
                    </div>
                    <span id="footer-status-text" class="tracking-tight">{!! $statusText !!}</span>
                </div>

                <!-- Hours Card with Elevation & Row Highlight & Icon Micro-Animations -->
                <div class="footer-hours-card rounded-2xl bg-zinc-900/70 backdrop-blur-md border border-white/10 p-3 sm:p-3.5 shadow-inner transition-all duration-300 hover:border-white/25 hover:shadow-[0_12px_32px_rgba(0,0,0,0.6),0_0_16px_rgba(255,255,255,0.04)]">
                    <!-- Row: Senin – Sabtu -->
                    <div class="footer-op-row group/row flex items-center gap-3 p-2 rounded-xl transition-colors duration-200 hover:bg-white/[0.05] cursor-default">
                        <div class="w-9 h-9 rounded-full bg-white/[0.05] border border-white/10 flex items-center justify-center shrink-0 text-zinc-300 transition-colors duration-200 group-hover/row:border-white/25 group-hover/row:text-white">
                            <svg class="footer-sun-icon w-4 h-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="4"/>
                                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold tracking-wider text-zinc-400 uppercase transition-colors group-hover/row:text-zinc-300">SENIN – SABTU</p>
                            <p class="text-sm font-bold text-white tracking-wide mt-0.5">11:00 – 21:00 WIB</p>
                        </div>
                    </div>

                    <!-- Dashed Divider (animated dash-offset on card hover) -->
                    <div class="my-2.5 overflow-hidden px-1">
                        <svg class="footer-dash-line w-full h-[2px] block text-white/20" preserveAspectRatio="none" viewBox="0 0 200 2">
                            <line x1="0" y1="1" x2="200" y2="1" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4, 4" />
                        </svg>
                    </div>

                    <!-- Row: Minggu -->
                    <div class="footer-op-row group/row flex items-center gap-3 p-2 rounded-xl transition-colors duration-200 hover:bg-white/[0.05] cursor-default">
                        <div class="w-9 h-9 rounded-full bg-white/[0.05] border border-white/10 flex items-center justify-center shrink-0 text-zinc-300 transition-colors duration-200 group-hover/row:border-white/25 group-hover/row:text-white">
                            <svg class="footer-moon-icon w-4 h-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold tracking-wider text-zinc-400 uppercase transition-colors group-hover/row:text-zinc-300">MINGGU</p>
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

                <!-- Dynamic Social Media Icon Circles (Only shown if filled by admin) -->
                <div class="flex items-center gap-2.5 mb-5 flex-wrap">
                    @if(!empty($footerInstagram))
                        <a href="{{ $footerInstagram }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                           aria-label="Instagram Nucomu Cafe"
                           title="Instagram Nucomu Cafe">
                            <i class="fa-brands fa-instagram text-base group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endif

                    @if(!empty($footerTiktok))
                        <a href="{{ $footerTiktok }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                           aria-label="TikTok Nucomu Cafe"
                           title="TikTok Nucomu Cafe">
                            <i class="fa-brands fa-tiktok text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endif

                    @if(!empty($footerGrabfood))
                        <a href="{{ $footerGrabfood }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-9 h-9 rounded-full bg-[#00B14F]/90 hover:bg-[#00B14F] text-white border border-[#00B14F]/40 hover:border-[#00B14F] transition-all duration-200 flex items-center justify-center shadow-sm group"
                           aria-label="GrabFood Nucomu Cafe"
                           title="Kunjungi GrabFood Nucomu Cafe">
                            <i class="fa-solid fa-bag-shopping text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endif

                    @if(!empty($footerEmail))
                        <a href="mailto:{{ $footerEmail }}" 
                           class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                           aria-label="Email Nucomu Cafe"
                           title="{{ $footerEmail }}">
                            <i class="fa-solid fa-envelope text-xs group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endif

                    @if(!empty($footerMapsUrl))
                        <a href="{{ $footerMapsUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-9 h-9 rounded-full bg-zinc-800/80 hover:bg-white text-zinc-300 hover:text-black border border-white/10 hover:border-white transition-all duration-200 flex items-center justify-center shadow-sm group"
                           aria-label="Lokasi Google Maps"
                           title="Buka Google Maps">
                            <i class="fa-solid fa-location-dot text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                    @endif
                </div>

                <!-- WhatsApp Contact Info -->
                <div class="flex items-center gap-2.5 text-xs mb-3 text-zinc-300">
                    <i class="fa-brands fa-whatsapp text-emerald-500 text-base shrink-0"></i>
                    <span>
                        WhatsApp: 
                        <a href="https://wa.me/{{ $footerWaClean }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="font-bold text-white hover:underline transition-colors ml-1">
                            {{ $footerWaDisplay }}
                        </a>
                    </span>
                </div>

                <!-- Address Location Info -->
                <div class="flex items-start gap-2.5 text-xs text-zinc-400">
                    <i class="fa-solid fa-location-dot text-zinc-400 shrink-0 mt-0.5"></i>
                    @if(!empty($footerMapsUrl))
                        <a href="{{ $footerMapsUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="hover:text-zinc-200 transition-colors leading-relaxed">
                            {{ $footerAddress }}
                        </a>
                    @else
                        <span class="leading-relaxed">{{ $footerAddress }}</span>
                    @endif
                </div>
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
                        class="w-9 h-9 rounded-full bg-zinc-900/90 hover:bg-white text-zinc-300 hover:text-[#111113] border border-white/15 hover:border-white transition-all duration-200 flex items-center justify-center shadow-md shrink-0 group">
                    <svg class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m18 15-6-6-6 6"/>
                    </svg>
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

    /* 5. Quote Section Scroll Reveal, Typewriter & Shimmer */
    .quote-revealed .footer-quote-line {
        transform: scaleY(1) !important;
    }
    .quote-revealed .footer-quote-content {
        opacity: 1 !important;
        transform: translateX(0) !important;
    }

    /* Typewriter Caret Animation */
    .footer-typewriter-cursor {
        display: inline-block;
        width: 2px;
        height: 1.1em;
        background-color: #ffffff;
        margin-left: 2px;
        vertical-align: -0.1em;
        border-radius: 9999px;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.9);
        transition: opacity 0.15s ease;
        opacity: 0;
    }
    .footer-typewriter-cursor.blinking {
        opacity: 1 !important;
        animation: footerCursorBlink 0.9s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .footer-typewriter-cursor.typing {
        opacity: 1 !important;
        animation: none;
    }
    @keyframes footerCursorBlink {
        0%, 100% {
            opacity: 1;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.95);
        }
        50% {
            opacity: 0.15;
            box-shadow: 0 0 2px rgba(255, 255, 255, 0.2);
        }
    }

    /* Shimmer effect on text: on hover or right after finishing typing */
    .footer-quote-box:hover .footer-quote-text-target,
    .footer-quote-text-target.shimmering {
        background: linear-gradient(
            105deg,
            #e4e4e7 0%,
            #e4e4e7 25%,
            #ffffff 50%,
            #e4e4e7 75%,
            #e4e4e7 100%
        );
        background-size: 200% 100%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: footerQuoteShimmer 2.2s linear infinite;
    }
    @keyframes footerQuoteShimmer {
        0% { background-position: 150% 0; }
        100% { background-position: -150% 0; }
    }
</style>

<!-- FOOTER LOGIC & INTERACTIONS SCRIPT -->
<script>
    (function() {
        // --- 1. Real-time Jam Buka Updater (Asia/Jakarta / WIB) ---
        function updateNucomuFooterHours() {
            const badgeText = document.getElementById('footer-status-text');
            const badgeGlow = document.getElementById('footer-status-glow');
            const badgeDot = document.getElementById('footer-status-dot');
            if (!badgeText || !badgeGlow || !badgeDot) return;

            try {
                // Get current time formatted to Asia/Jakarta
                const now = new Date();
                const jakartaString = now.toLocaleString('en-US', { timeZone: 'Asia/Jakarta' });
                const jakartaTime = new Date(jakartaString);

                const day = jakartaTime.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                const hour = jakartaTime.getHours();
                const minute = jakartaTime.getMinutes();
                const currentMins = hour * 60 + minute;

                // Senin – Sabtu: 11:00 – 21:00 WIB
                // Minggu: 11:00 – 22:00 WIB
                const openMins = 11 * 60; // 660 mins
                const closeMins = (day === 0) ? (22 * 60) : (21 * 60);
                const closeTimeStr = (day === 0) ? '22:00' : '21:00';

                const isOpen = (currentMins >= openMins && currentMins < closeMins);

                if (isOpen) {
                    badgeText.innerHTML = `Buka sekarang &bull; tutup ${closeTimeStr} WIB`;
                    badgeGlow.className = 'absolute inset-0 rounded-full footer-pulse-open';
                    badgeDot.className = 'w-2 h-2 rounded-full relative z-10 bg-white shadow-[0_0_8px_rgba(255,255,255,0.95)]';
                } else {
                    const reopenStr = (currentMins < openMins) ? 'buka kembali 11:00 WIB' : 'buka besok 11:00 WIB';
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
                        // Reveal Quote block & Trigger Typewriter
                        const quoteBox = footerSection.querySelector('.footer-quote-box');
                        if (quoteBox) {
                            quoteBox.classList.add('quote-revealed');
                            if (typeof startFooterQuoteTypewriter === 'function') {
                                startFooterQuoteTypewriter();
                            }
                        }

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
            if (quoteBox) {
                quoteBox.classList.add('quote-revealed');
                if (typeof startFooterQuoteTypewriter === 'function') {
                    startFooterQuoteTypewriter();
                }
            }
            const navList = document.querySelector('#footer-nav-list');
            if (navList) navList.classList.add('footer-nav-visible');
        }

        // --- 4. Nucomu Slogan Typewriter & Breathing Loop ---
        let startFooterQuoteTypewriter = function() {};

        (function setupQuoteTypewriter() {
            const quoteBox = document.getElementById('footer-quote-box');
            const quoteTarget = document.getElementById('footer-typewriter-target');
            const quoteCursor = document.getElementById('footer-typewriter-cursor');

            if (!quoteBox || !quoteTarget || !quoteCursor) return;

            const line1 = '"Tempat Kembali, Rasa yang';
            const line2 = 'Dirindukan."';

            let typingTimeout = null;
            let isHovered = false;
            let isStarted = false;
            let currentLine = 1;
            let charIndex = 0;
            let isDeleting = false;

            quoteBox.addEventListener('mouseenter', function() {
                isHovered = true;
            });

            quoteBox.addEventListener('mouseleave', function() {
                isHovered = false;
            });

            // Replay immediately on click
            quoteBox.addEventListener('click', function() {
                if (typingTimeout) clearTimeout(typingTimeout);
                isDeleting = false;
                currentLine = 1;
                charIndex = 0;
                quoteTarget.classList.remove('shimmering');
                quoteTarget.innerHTML = '';
                setCursorState('typing');
                typeStep();
            });

            function setCursorState(state) {
                if (state === 'typing') {
                    quoteCursor.classList.remove('blinking');
                    quoteCursor.classList.add('typing');
                    quoteCursor.style.opacity = '1';
                } else if (state === 'blinking') {
                    quoteCursor.classList.remove('typing');
                    quoteCursor.classList.add('blinking');
                } else if (state === 'hidden') {
                    quoteCursor.classList.remove('blinking', 'typing');
                    quoteCursor.style.opacity = '0';
                }
            }

            function typeStep() {
                // Respect users who prefer reduced motion
                if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    quoteTarget.innerHTML = line1 + '<br>' + line2;
                    setCursorState('blinking');
                    return;
                }

                if (!isDeleting) {
                    setCursorState('typing');

                    if (currentLine === 1) {
                        if (charIndex < line1.length) {
                            charIndex++;
                            quoteTarget.innerHTML = line1.substring(0, charIndex);

                            // Natural cadence: slightly longer pause at comma
                            const char = line1.charAt(charIndex - 1);
                            let delay = 50 + Math.random() * 25;
                            if (char === ',') delay += 180;

                            typingTimeout = setTimeout(typeStep, delay);
                        } else {
                            // Move to line 2 with line-break
                            currentLine = 2;
                            charIndex = 0;
                            quoteTarget.innerHTML = line1 + '<br>';
                            typingTimeout = setTimeout(typeStep, 160);
                        }
                    } else if (currentLine === 2) {
                        if (charIndex < line2.length) {
                            charIndex++;
                            quoteTarget.innerHTML = line1 + '<br>' + line2.substring(0, charIndex);

                            // Natural cadence: longer pause at period
                            const char = line2.charAt(charIndex - 1);
                            let delay = 50 + Math.random() * 25;
                            if (char === '.') delay += 200;

                            typingTimeout = setTimeout(typeStep, delay);
                        } else {
                            // Completed full sentence!
                            setCursorState('blinking');

                            // Soft light shimmer across the finished quote
                            quoteTarget.classList.add('shimmering');
                            setTimeout(function() {
                                quoteTarget.classList.remove('shimmering');
                            }, 2200);

                            // Comfortable reading delay before starting smooth backspace
                            function scheduleDelete(remainingMs) {
                                if (isHovered) {
                                    // Pause deleting when hovered so user can read
                                    typingTimeout = setTimeout(function() {
                                        scheduleDelete(1500);
                                    }, 1000);
                                    return;
                                }

                                if (remainingMs <= 0) {
                                    isDeleting = true;
                                    typeStep();
                                } else {
                                    const step = Math.min(500, remainingMs);
                                    typingTimeout = setTimeout(function() {
                                        scheduleDelete(remainingMs - step);
                                    }, step);
                                }
                            }

                            scheduleDelete(4500);
                        }
                    }
                } else {
                    // Backspacing
                    if (isHovered) {
                        setCursorState('blinking');
                        typingTimeout = setTimeout(typeStep, 600);
                        return;
                    }

                    setCursorState('typing');

                    if (currentLine === 2) {
                        if (charIndex > 0) {
                            charIndex--;
                            quoteTarget.innerHTML = line1 + '<br>' + line2.substring(0, charIndex);
                            typingTimeout = setTimeout(typeStep, 24);
                        } else {
                            // Move back to line 1
                            currentLine = 1;
                            charIndex = line1.length;
                            quoteTarget.innerHTML = line1;
                            typingTimeout = setTimeout(typeStep, 30);
                        }
                    } else if (currentLine === 1) {
                        if (charIndex > 0) {
                            charIndex--;
                            quoteTarget.innerHTML = line1.substring(0, charIndex);
                            typingTimeout = setTimeout(typeStep, 24);
                        } else {
                            // Backspaced to start! Brief breath before typing again
                            isDeleting = false;
                            currentLine = 1;
                            charIndex = 0;
                            setCursorState('blinking');
                            typingTimeout = setTimeout(typeStep, 750);
                        }
                    }
                }
            }

            startFooterQuoteTypewriter = function() {
                if (isStarted) return;
                isStarted = true;
                quoteTarget.innerHTML = '';
                setCursorState('typing');
                typingTimeout = setTimeout(typeStep, 300);
            };
        })();
    })();
</script>
