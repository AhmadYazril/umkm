{{-- ═══ FLOATING PILL ISLAND NAVBAR (ORGANIC, FLUID & MODERN) ═══ --}}
<header class="fixed top-3 sm:top-5 left-0 right-0 z-50 flex justify-center px-3 sm:px-6 pointer-events-none transition-all duration-300">
    <div class="w-full max-w-5xl">
        <nav class="pointer-events-auto rounded-full bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border border-white/90 dark:border-white/10 shadow-lg shadow-black/[0.04] dark:shadow-black/40 px-4 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between transition-all duration-300">

            {{-- Logo Navbar (Clean, Balanced & Always Visible) --}}
            <a href="{{ route('home') }}" 
               id="navbar-logo" 
               class="flex items-center gap-2.5 group transition-all duration-300 ease-out opacity-100 translate-y-0 pointer-events-auto">
                <div class="w-6 h-7 text-zinc-950 dark:text-white shrink-0 group-hover:scale-105 transition-transform">
                    <svg class="w-full h-full" viewBox="0 0 104 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M 24 46 V 24 C 24 10 52 10 52 24 V 46" stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="80" cy="44" r="7" fill="currentColor"/>
                        <path d="M 52 64 V 86 C 52 100 80 100 80 86 V 64" stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <div class="font-sans font-black text-zinc-900 dark:text-white text-sm sm:text-base tracking-widest leading-none">NUCOMU</div>
                    <div class="font-sans text-zinc-500 dark:text-zinc-400 text-[9px] sm:text-[10px] tracking-widest uppercase">COFFEE & DESSERT</div>
                </div>
            </a>

            {{-- Desktop Navigation (Di Kanan Atas dengan Tombol Pil Halus) --}}
            <div class="hidden md:flex items-center gap-1 sm:gap-1.5 ml-auto">
                <a href="{{ route('home') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-sm' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10' }}">Beranda</a>
                <a href="{{ route('menu') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 {{ request()->routeIs('menu*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-sm' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10' }}">Menu</a>
                <a href="{{ route('about') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 {{ request()->routeIs('about') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-sm' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10' }}">Cerita Kami</a>
                <a href="{{ route('gallery') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 {{ request()->routeIs('gallery') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-sm' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10' }}">Galeri</a>
                <a href="{{ route('contact') }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 shadow-sm' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10' }}">Kontak</a>

                <div class="h-4 w-px bg-zinc-300/80 dark:bg-zinc-700/80 mx-1"></div>

                {{-- Cart Icon --}}
                <a href="{{ route('cart') }}" class="relative p-2 rounded-full text-zinc-600 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10 transition-colors" title="Keranjang Belanja">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h11M7 13l-1 5m10-5l1 5M9 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-950 text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full shadow-sm">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- THEME SWITCHER TOGGLE BUTTON (Sun / Moon) --}}
                <button id="theme-toggle" type="button" class="p-2 rounded-full text-zinc-600 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10 transition-all border border-zinc-200/80 dark:border-white/10 shadow-sm" title="Beralih Mode Terang / Gelap">
                    {{-- Sun Icon (tampil di Dark Mode) --}}
                    <svg id="theme-toggle-sun-icon" class="w-4 h-4 hidden dark:block text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" clip-rule="evenodd"/>
                    </svg>
                    {{-- Moon Icon (tampil di Light Mode) --}}
                    <svg id="theme-toggle-moon-icon" class="w-4 h-4 block dark:hidden text-zinc-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile Actions --}}
            <div class="flex items-center gap-1.5 md:hidden ml-auto">
                <a href="{{ route('cart') }}" class="relative p-2 rounded-full text-zinc-600 dark:text-zinc-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h11M7 13l-1 5m10-5l1 5M9 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                    @if($cartCount > 0)
                    <span class="absolute 0 right-0 bg-zinc-900 dark:bg-white text-white dark:text-zinc-950 text-[9px] font-bold w-3.5 h-3.5 flex items-center justify-center rounded-full">{{ $cartCount }}</span>
                    @endif
                </a>
                <button id="theme-toggle-mobile" type="button" class="p-2 rounded-full text-zinc-700 dark:text-zinc-300">
                    <svg class="w-4 h-4 hidden dark:block text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" clip-rule="evenodd"/></svg>
                    <svg class="w-4 h-4 block dark:hidden text-zinc-700" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                </button>
                <button id="mobile-menu-btn" class="p-2 text-zinc-900 dark:text-white rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Mobile dropdown menu (Floating island rounded card) --}}
        <div id="mobile-menu" class="pointer-events-auto hidden md:hidden mt-2.5 rounded-3xl bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border border-white/80 dark:border-white/10 shadow-2xl p-4 transition-all">
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-2xl text-xs font-semibold text-zinc-900 dark:text-white hover:bg-black/5 dark:hover:bg-white/10">Beranda</a>
                <a href="{{ route('menu') }}" class="block px-4 py-2.5 rounded-2xl text-xs font-semibold text-zinc-900 dark:text-white hover:bg-black/5 dark:hover:bg-white/10">Menu</a>
                <a href="{{ route('about') }}" class="block px-4 py-2.5 rounded-2xl text-xs font-semibold text-zinc-900 dark:text-white hover:bg-black/5 dark:hover:bg-white/10">Cerita Kami</a>
                <a href="{{ route('gallery') }}" class="block px-4 py-2.5 rounded-2xl text-xs font-semibold text-zinc-900 dark:text-white hover:bg-black/5 dark:hover:bg-white/10">Galeri</a>
                <a href="{{ route('contact') }}" class="block px-4 py-2.5 rounded-2xl text-xs font-semibold text-zinc-900 dark:text-white hover:bg-black/5 dark:hover:bg-white/10">Kontak</a>
                <a href="{{ route('cart') }}" class="block px-4 py-2.5 rounded-2xl text-xs font-semibold text-zinc-900 dark:text-white hover:bg-black/5 dark:hover:bg-white/10">Keranjang ({{ count(session('cart', [])) }})</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleMobile = document.getElementById('theme-toggle-mobile');
        if (toggleMobile) {
            toggleMobile.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }
    });
</script>
