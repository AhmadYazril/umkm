@php
    $navTxSettings = $txSettings ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? (new \App\Http\Controllers\NucomuController())->getTransactionSettings() : []);
    $isAnyOrderModeActive = (bool) ($navTxSettings['is_any_mode_active'] ?? true);
    $isHomeWithHero = request()->routeIs('home') || request()->is('/');
    $hasHeroBanners = isset($activeBanners) && $activeBanners->count() > 0;
    $initialHeroNavbarBg = !$isHomeWithHero
        ? '#0C0C0C'
        : ($hasHeroBanners ? '#0C0C0C' : '#140E0A');
    $initialNavbarThemeClass = $isHomeWithHero
        ? 'navbar--over-hero navbar--flush-hero'
        : 'navbar--scrolled';
@endphp
@if($isHomeWithHero)
<script>
    document.documentElement.style.setProperty('--hero-bg-color', @json($initialHeroNavbarBg));
</script>
@endif
<!-- Main Top Navbar (Styled with Nucomu Warm Ivory & Terracotta Theme) -->
<header id="main-navbar" class="fixed top-0 left-0 right-0 z-40 navbar-visible {{ $initialNavbarThemeClass }}">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 h-16 sm:h-20 flex items-center justify-between">
        
        <!-- Official Brand Logo & Wordmark (Far Left) -->
        <a href="{{ route('home') }}#home" class="flex items-center space-x-3 group" aria-label="Nucomu Cafe">
            <img src="{{ asset('images/nucomu/nucomu_logo_brand.png') }}" 
                 alt="NUCOMU CAFE" 
                 id="navbar-brand-logo"
                 class="h-6 sm:h-7 md:h-8 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
        </a>

        <!-- Desktop Navigation Items (Theme-consistent Monochrome) -->
        <nav class="hidden md:flex items-center space-x-6 lg:space-x-8">
            <a href="{{ route('home') }}#home" 
               class="desktop-nav-link nav-link active font-body text-sm lg:text-base font-semibold transition-colors">
                Home
            </a>
            <a href="{{ route('home') }}#about" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold transition-colors">
                About
            </a>
            <a href="{{ route('home') }}#menu" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold transition-colors">
                Menu
            </a>
            <a href="{{ route('home') }}#gallery" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold transition-colors">
                Gallery
            </a>
            <a href="{{ route('home') }}#layanan" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold transition-colors">
                Layanan
            </a>
            <a href="{{ route('home') }}#contact" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold transition-colors">
                Contact
            </a>
            @if($isAnyOrderModeActive)
            <button type="button" 
                    onclick="openCartModal()" 
                    id="nav-cart-btn"
                    class="nav-cart-btn inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shadow-sm">
                <i class="fa-solid fa-bag-shopping text-[11px]"></i>
                <span>Keranjang</span>
                <span id="nav-cart-badge" class="px-1.5 py-0.5 rounded-full text-[10px] font-black leading-none">0</span>
            </button>
            @endif
        </nav>



        <!-- Mobile Quick Cart & Hamburger Button (Only on small screens) -->
        <div class="md:hidden flex items-center gap-2">
            @if($isAnyOrderModeActive)
            <button type="button"
                    onclick="openCartModal()"
                    id="mobile-nav-cart-btn"
                    class="nav-cart-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer shadow-xs active:scale-95"
                    aria-label="Buka Keranjang Pesanan">
                <i class="fa-solid fa-bag-shopping text-[11px]"></i>
                <span id="mobile-nav-cart-badge" class="nav-cart-badge-el px-1.5 py-0.5 rounded-full text-[10px] font-black leading-none">0</span>
            </button>
            @endif
            <button onclick="toggleMobileDrawer()" class="mobile-hamburger-btn p-2 rounded-xl focus:outline-none transition-colors active:scale-95" aria-label="Buka Menu Navigasi">
                <span class="material-symbols-outlined text-2xl leading-none">menu</span>
            </button>
        </div>

    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div id="mobile-drawer" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden">
    <!-- Click backdrop to close -->
    <div class="absolute inset-0" onclick="toggleMobileDrawer()"></div>

    <!-- Slide-in Drawer Panel (Monochrome Dark Editorial) -->
    <div id="mobile-drawer-panel" class="absolute top-0 right-0 w-[84%] max-w-[340px] h-full bg-[#121212] shadow-2xl p-5 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-out border-l border-white/10 overflow-y-auto">
        <div>
            <!-- Drawer Header -->
            <div class="flex items-center justify-between pb-4 border-b border-white/10">
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/nucomu/nucomu_logo_brand.png') }}" 
                         alt="NUCOMU CAFE" 
                         class="h-6 w-auto object-contain brightness-0 invert">
                </div>
                <button onclick="toggleMobileDrawer()" class="w-9 h-9 rounded-full bg-white/5 hover:bg-white/15 text-[#F5F5F5] flex items-center justify-center transition-colors active:scale-95" aria-label="Tutup Menu">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <!-- Drawer Links -->
            <nav class="flex flex-col space-y-1 mt-4" aria-label="Menu Mobile">
                <a href="{{ route('home') }}#home" onclick="toggleMobileDrawer(); if(typeof switchMobileHomePage==='function'){switchMobileHomePage('home-about','home');}" class="mobile-nav-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-bold text-[#F5F5F5] bg-white/8 hover:bg-white/12 transition-colors">
                    <span class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[18px] text-white/80">home</span>
                        <span>Home &amp; About</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/10 text-white/70">Page 1</span>
                </a>
                <a href="{{ route('home') }}#menu" onclick="toggleMobileDrawer(); if(typeof switchMobileHomePage==='function'){switchMobileHomePage('menu','menu');}" class="mobile-nav-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-[#C8C8C8] hover:text-white hover:bg-white/5 transition-colors">
                    <span class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[18px] text-white/60">restaurant_menu</span>
                        <span>Menu Favorit</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/10 text-white/70">Page 2</span>
                </a>
                <a href="{{ route('home') }}#gallery" onclick="toggleMobileDrawer(); if(typeof switchMobileHomePage==='function'){switchMobileHomePage('gallery-reviews','gallery');}" class="mobile-nav-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-[#C8C8C8] hover:text-white hover:bg-white/5 transition-colors">
                    <span class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[18px] text-white/60">photo_library</span>
                        <span>Galeri &amp; Ulasan</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/10 text-white/70">Page 3</span>
                </a>
                <a href="{{ route('home') }}#layanan" onclick="toggleMobileDrawer(); if(typeof switchMobileHomePage==='function'){switchMobileHomePage('layanan-contact','layanan');}" class="mobile-nav-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-[#C8C8C8] hover:text-white hover:bg-white/5 transition-colors">
                    <span class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[18px] text-white/60">concierge</span>
                        <span>Layanan &amp; Kontak</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/10 text-white/70">Page 4</span>
                </a>
                <a href="{{ route('menu.all') }}" onclick="toggleMobileDrawer()" class="mobile-nav-link flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-[#C8C8C8] hover:text-white hover:bg-white/5 transition-colors">
                    <span class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[18px] text-white/60">menu_book</span>
                        <span>Katalog Menu Lengkap</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/10 text-white/80">All</span>
                </a>
            </nav>
        </div>

        <!-- Drawer Bottom Actions -->
        <div class="pt-5 mt-4 border-t border-white/10 flex flex-col space-y-2.5">
            @if($isAnyOrderModeActive)
            <button type="button" 
                    onclick="toggleMobileDrawer(); openCartModal();" 
                    class="w-full py-3 px-4 bg-[#F5F2EE] hover:bg-white text-[#111111] border border-white/25 text-xs font-bold uppercase tracking-wider rounded-xl text-center transition-all shadow-sm active:scale-95 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span>Keranjang Pesanan</span>
                </span>
                <span id="drawer-cart-badge" class="px-2 py-0.5 rounded-full bg-[#111111] text-[#F5F5F5] text-[10px] font-black">0</span>
            </button>
            @endif
            <button type="button" 
                    onclick="toggleMobileDrawer(); openReservationModal('Mobile Drawer');" 
                    class="w-full py-3 px-4 bg-white/5 hover:bg-white/12 text-[#F5F5F5] border border-white/20 text-xs font-bold uppercase tracking-editorial-widest rounded-xl text-center transition-colors shadow-sm active:scale-95 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">event_seat</span>
                <span>Reservasi Tempat</span>
            </button>
            <div class="pt-1 text-center text-[11px] text-white/50 font-medium flex items-center justify-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>{{ config('nucomu.contact.hours') }}</span>
            </div>
        </div>
    </div>
</div>
