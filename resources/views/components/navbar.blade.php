<!-- Main Top Navbar (Styled with Nucomu Warm Ivory & Terracotta Theme) -->
<header id="main-navbar" class="sticky top-0 left-0 right-0 z-40 bg-[#FAF8F5]/95 backdrop-blur-md border-b border-[#54392B]/10 navbar-visible transition-all duration-300">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 h-16 sm:h-20 flex items-center justify-between">
        
        <!-- Official Brand Logo & Wordmark (Far Left) -->
        <a href="{{ route('home') }}#home" class="flex items-center space-x-3 group" aria-label="Nucomu Cafe">
            <img src="{{ asset('images/nucomu/nucomu_logo_brand.png') }}" 
                 alt="NUCOMU CAFE" 
                 class="h-6 sm:h-7 md:h-8 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
        </a>

        <!-- Desktop Navigation Items (Theme-consistent Colors) -->
        <nav class="hidden md:flex items-center space-x-6 lg:space-x-8">
            <a href="{{ route('home') }}#home" 
               class="desktop-nav-link nav-link active font-body text-sm lg:text-base font-semibold text-[#2A1D17] hover:text-[#E06D24] transition-colors">
                Home
            </a>
            <a href="{{ route('home') }}#about" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold text-[#2A1D17] hover:text-[#E06D24] transition-colors">
                About
            </a>
            <a href="{{ route('home') }}#menu" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold text-[#2A1D17] hover:text-[#E06D24] transition-colors">
                Menu
            </a>
            <a href="{{ route('home') }}#gallery" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold text-[#2A1D17] hover:text-[#E06D24] transition-colors">
                Gallery
            </a>
            <a href="{{ route('home') }}#layanan" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold text-[#2A1D17] hover:text-[#E06D24] transition-colors">
                Layanan
            </a>
            <a href="{{ route('home') }}#contact" 
               class="desktop-nav-link nav-link font-body text-sm lg:text-base font-semibold text-[#2A1D17] hover:text-[#E06D24] transition-colors">
                Contact
            </a>
        </nav>



        <!-- Mobile Hamburger Button (Only on small screens) -->
        <div class="md:hidden flex items-center">
            <button onclick="toggleMobileDrawer()" class="p-2 text-[#2A1D17] hover:text-[#E06D24] focus:outline-none transition-colors" aria-label="Buka Menu Navigasi">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>
        </div>

    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div id="mobile-drawer" class="fixed inset-0 bg-[#2A1D17]/60 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden">
    <!-- Click backdrop to close -->
    <div class="absolute inset-0" onclick="toggleMobileDrawer()"></div>

    <!-- Slide-in Drawer Panel (Warm Ivory & Terracotta Theme) -->
    <div id="mobile-drawer-panel" class="absolute top-0 right-0 w-4/5 max-w-sm h-full bg-[#FAF8F5] shadow-2xl p-6 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out border-l border-[#54392B]/15">
        <div>
            <!-- Drawer Header -->
            <div class="flex items-center justify-between pb-5 border-b border-[#54392B]/10">
                <img src="{{ asset('images/nucomu/nucomu_logo_brand.png') }}" 
                     alt="NUCOMU CAFE" 
                     class="h-6 w-auto object-contain">
                <button onclick="toggleMobileDrawer()" class="p-2 text-[#54392B] hover:text-[#E06D24] transition-colors" aria-label="Tutup Menu">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            <!-- Drawer Links -->
            <nav class="flex flex-col space-y-1.5 mt-6">
                <a href="{{ route('home') }}#home" onclick="toggleMobileDrawer()" class="text-sm font-bold text-[#2A1D17] py-2.5 border-b border-[#54392B]/10 hover:text-[#E06D24] transition-colors">Home</a>
                <a href="{{ route('home') }}#about" onclick="toggleMobileDrawer()" class="text-sm font-bold text-[#2A1D17] py-2.5 border-b border-[#54392B]/10 hover:text-[#E06D24] transition-colors">About</a>
                <a href="{{ route('home') }}#menu" onclick="toggleMobileDrawer()" class="text-sm font-bold text-[#2A1D17] py-2.5 border-b border-[#54392B]/10 hover:text-[#E06D24] transition-colors">Menu</a>
                <a href="{{ route('home') }}#gallery" onclick="toggleMobileDrawer()" class="text-sm font-bold text-[#2A1D17] py-2.5 border-b border-[#54392B]/10 hover:text-[#E06D24] transition-colors">Gallery</a>
                <a href="{{ route('home') }}#layanan" onclick="toggleMobileDrawer()" class="text-sm font-bold text-[#2A1D17] py-2.5 border-b border-[#54392B]/10 hover:text-[#E06D24] transition-colors">Layanan</a>
                <a href="{{ route('home') }}#contact" onclick="toggleMobileDrawer()" class="text-sm font-bold text-[#2A1D17] py-2.5 border-b border-[#54392B]/10 hover:text-[#E06D24] transition-colors">Contact</a>
            </nav>
        </div>

        <!-- Drawer Bottom Actions -->
        <div class="pt-6 border-t border-[#54392B]/10 flex flex-col space-y-4">
            <button type="button" 
                    onclick="toggleMobileDrawer(); openReservationModal('Mobile Drawer');" 
                    class="w-full py-3.5 bg-[#2A1D17] hover:bg-[#E06D24] text-white text-xs font-bold uppercase tracking-editorial-widest rounded-xl text-center transition-colors shadow-sm active:scale-95">
                Reservasi Tempat
            </button>
            <div class="text-center text-xs text-[#5C4E45] font-medium">
                {{ config('nucomu.contact.hours') }}
            </div>
        </div>
    </div>
</div>
