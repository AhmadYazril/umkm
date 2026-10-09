<nav class="fixed top-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-sm border-b border-nc-gray-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-nc-black flex items-center justify-center">
                    <span class="text-white font-serif font-bold text-lg tracking-tighter">NU</span>
                </div>
                <div class="hidden sm:block">
                    <div class="font-sans font-black text-nc-black text-base tracking-widest2 leading-none">NUCOMU</div>
                    <div class="font-sans text-nc-gray-mid text-xs tracking-widest">COFFEE & DESSERT</div>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'text-nc-black font-semibold' : '' }}">Beranda</a>
                <a href="{{ route('menu') }}" class="nav-link {{ request()->routeIs('menu*') ? 'text-nc-black font-semibold' : '' }}">Menu</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'text-nc-black font-semibold' : '' }}">Cerita Kami</a>
                <a href="{{ route('gallery') }}" class="nav-link {{ request()->routeIs('gallery') ? 'text-nc-black font-semibold' : '' }}">Galeri</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'text-nc-black font-semibold' : '' }}">Kontak</a>
            </div>

            {{-- CTA + Cart --}}
            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('cart') }}" class="relative p-2 text-nc-gray hover:text-nc-black transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h11M7 13l-1 5m10-5l1 5M9 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-nc-black text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('menu') }}" class="btn-primary text-xs px-4 py-2">Pesan Sekarang</a>
            </div>

            {{-- Mobile menu button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 text-nc-gray">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-nc-gray-light bg-white">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block nav-link py-1">Beranda</a>
            <a href="{{ route('menu') }}" class="block nav-link py-1">Menu</a>
            <a href="{{ route('about') }}" class="block nav-link py-1">Cerita Kami</a>
            <a href="{{ route('gallery') }}" class="block nav-link py-1">Galeri</a>
            <a href="{{ route('contact') }}" class="block nav-link py-1">Kontak</a>
            <a href="{{ route('cart') }}" class="block nav-link py-1">Keranjang ({{ count(session('cart', [])) }})</a>
            <a href="{{ route('menu') }}" class="block btn-primary text-center mt-2">Pesan Sekarang</a>
        </div>
    </div>
</nav>
<div class="h-16"></div>{{-- spacer --}}
