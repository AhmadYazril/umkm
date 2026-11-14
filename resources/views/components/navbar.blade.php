@php
    $navTxSettings = $txSettings ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? (new \App\Http\Controllers\NucomuController())->getTransactionSettings() : []);
    $isAnyOrderModeActive = (bool) ($navTxSettings['is_any_mode_active'] ?? true);
    $isHomeWithHero = request()->routeIs('home') || request()->is('/');
    $hasHeroBanners = isset($activeBanners) && $activeBanners->count() > 0;
    $firstHeroBannerMode = $hasHeroBanners ? ($activeBanners->first()->layout_mode ?: 'poster') : null;
    $isInitialHeroDark = $isHomeWithHero && (!$hasHeroBanners || $firstHeroBannerMode === 'overlay');
    $initialHeroNavbarBg = !$isHomeWithHero
        ? '#F9F9F9'
        : ($hasHeroBanners ? ($firstHeroBannerMode === 'overlay' ? '#191919' : '#F9F9F9') : '#140E0A');
    $initialNavbarThemeClass = $isHomeWithHero
        ? ($isInitialHeroDark ? 'navbar--over-hero navbar--flush-hero' : 'navbar--scrolled navbar--flush-hero')
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



        <!-- Mobile Hamburger Button (Only on small screens) -->
        <div class="md:hidden flex items-center">
            <button onclick="toggleMobileDrawer()" class="mobile-hamburger-btn p-2 focus:outline-none transition-colors" aria-label="Buka Menu Navigasi">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>
        </div>

    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div id="mobile-drawer" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden">
    <!-- Click backdrop to close -->
    <div class="absolute inset-0" onclick="toggleMobileDrawer()"></div>

    <!-- Slide-in Drawer Panel (Monochrome Minimalist) -->
    <div id="mobile-drawer-panel" class="absolute top-0 right-0 w-4/5 max-w-sm h-full bg-[#F9F9F9] shadow-2xl p-6 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out border-l border-black/10">
        <div>
            <!-- Drawer Header -->
            <div class="flex items-center justify-between pb-5 border-b border-black/10">
                <img src="{{ asset('images/nucomu/nucomu_logo_brand.png') }}" 
                     alt="NUCOMU CAFE" 
                     class="h-6 w-auto object-contain">
                <button onclick="toggleMobileDrawer()" class="p-2 text-[#111111] hover:text-black transition-colors" aria-label="Tutup Menu">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            <!-- Drawer Links -->
            <nav class="flex flex-col space-y-1.5 mt-6">
                <a href="{{ route('home') }}#home" onclick="toggleMobileDrawer()" class="text-sm font-bold text-[#111111] py-2.5 border-b border-black/10 hover:text-black transition-colors">Home</a>
                <a href="{{ route('home') }}#about" onclick="toggleMobileDrawer()" class="text-sm font-semibold text-[#4A4A4A] py-2.5 border-b border-black/10 hover:text-[#111111] transition-colors">About</a>
                <a href="{{ route('home') }}#menu" onclick="toggleMobileDrawer()" class="text-sm font-semibold text-[#4A4A4A] py-2.5 border-b border-black/10 hover:text-[#111111] transition-colors">Menu</a>
                <a href="{{ route('home') }}#gallery" onclick="toggleMobileDrawer()" class="text-sm font-semibold text-[#4A4A4A] py-2.5 border-b border-black/10 hover:text-[#111111] transition-colors">Gallery</a>
                <a href="{{ route('home') }}#layanan" onclick="toggleMobileDrawer()" class="text-sm font-semibold text-[#4A4A4A] py-2.5 border-b border-black/10 hover:text-[#111111] transition-colors">Layanan</a>
                <a href="{{ route('home') }}#contact" onclick="toggleMobileDrawer()" class="text-sm font-semibold text-[#4A4A4A] py-2.5 border-b border-black/10 hover:text-[#111111] transition-colors">Contact</a>
            </nav>
        </div>

        <!-- Drawer Bottom Actions -->
        <div class="pt-6 border-t border-black/10 flex flex-col space-y-3">
            @if($isAnyOrderModeActive)
            <button type="button" 
                    onclick="toggleMobileDrawer(); openCartModal();" 
                    class="w-full py-3.5 bg-[#111111] hover:bg-black text-white border border-black/20 text-xs font-bold uppercase tracking-wider rounded-xl text-center transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>Buka Keranjang Pesanan</span>
                <span id="drawer-cart-badge" class="px-2 py-0.5 rounded-full bg-white text-[#111111] text-[10px] font-black">0</span>
            </button>
            @endif
            <button type="button" 
                    onclick="toggleMobileDrawer(); openReservationModal('Mobile Drawer');" 
                    class="w-full py-3 bg-[#111111] hover:bg-black text-white text-xs font-bold uppercase tracking-editorial-widest rounded-xl text-center transition-colors shadow-sm active:scale-95">
                Reservasi Tempat
            </button>
            <div class="text-center text-xs text-[#5C4E45] font-medium">
                {{ config('nucomu.contact.hours') }}
            </div>
        </div>
    </div>
</div>
