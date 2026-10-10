@extends('layouts.app')
@section('title', 'Nucomu Cafe — Coffee & Dessert Tulungagung')

@section('content')

{{-- ═══ HERO SECTION (FLUID, ORGANIC & IMMERSIVE CAFE SANCTUARY) ═══ --}}
<section class="relative min-h-[92vh] pt-24 sm:pt-28 pb-16 sm:pb-20 flex flex-col items-center justify-center text-zinc-900 dark:text-white transition-colors duration-300">
    <div class="relative z-10 text-center px-4 sm:px-6 max-w-4xl mx-auto w-full my-auto">
        
        {{-- LOGO BESAR UTAMA (Freestanding, Asli Sesuai Brand Tanpa Kotak Kaku) --}}
        <div id="hero-logo" class="flex flex-col items-center justify-center mb-6 reveal reveal-scale">
            {{-- Monogram Icon 'n. u.' (Freestanding Sesuai Sketsa & Logo Asli) --}}
            <div class="w-20 h-24 sm:w-24 sm:h-28 text-zinc-950 dark:text-white mb-3 hover:scale-105 transition-transform duration-300 drop-shadow-sm">
                <svg class="w-full h-full" viewBox="0 0 104 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 24 46 V 24 C 24 10 52 10 52 24 V 46" stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="80" cy="44" r="7" fill="currentColor"/>
                    <path d="M 52 64 V 86 C 52 100 80 100 80 86 V 64" stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            {{-- Brand Typography: NUCOMU, COFFEE & DESSERT, Tagline --}}
            <h1 class="font-black text-5xl sm:text-7xl md:text-8xl tracking-[0.22em] text-zinc-950 dark:text-white mb-2 uppercase drop-shadow-sm font-sans">
                NUCOMU
            </h1>
            <p class="text-zinc-800 dark:text-zinc-200 text-xs sm:text-base tracking-[0.38em] mb-2 uppercase font-bold">
                COFFEE & DESSERT
            </p>
            <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm tracking-wide font-sans font-normal italic">
                New, Unforgettable, Comfy, Musings
            </p>
        </div>

        {{-- Live Status Pill & Headline Sambutan (Fluid & Terbuka Tanpa Kotak Menutup Background) --}}
        <div class="max-w-2xl mx-auto text-center mb-7 reveal">
            {{-- Live Status Pill --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/80 dark:bg-zinc-900/80 border border-white/80 dark:border-white/10 shadow-sm backdrop-blur-md mb-4">
                @if($isOpen)
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Buka Sekarang</span>
                    @if($todayHour)
                    <span class="text-zinc-500 dark:text-zinc-400 text-xs font-mono">— Sampai {{ substr($todayHour->close_time, 0, 5) }} WIB</span>
                    @endif
                @else
                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                    <span class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider">Sedang Tutup</span>
                @endif
            </div>

            <h2 class="text-2xl sm:text-4xl font-serif font-bold text-zinc-950 dark:text-white mb-3 tracking-tight">
                Sanctuary Kopi & Artisan Dessert di Tulungagung
            </h2>
            <p class="text-zinc-700 dark:text-zinc-300 text-xs sm:text-sm leading-relaxed max-w-xl mx-auto font-normal">
                Nucomu Cafe hadir dengan konsep monokrom elegan — tempat nyaman untuk menikmati dessert otentik, makanan lezat, seduhan kopi pilihan, bersantai, dan Work From Cafe (WFC).
            </p>
        </div>

        {{-- CTA Buttons (Rounded Pill Halus & Dinamis) --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 mb-8 reveal">
            <a href="#best-menu" class="btn-primary rounded-full text-xs px-8 py-3.5 shadow-xl w-full sm:w-auto text-center flex items-center justify-center gap-2 group hover:scale-105 transition-all">
                <span>Jelajahi Best Menu</span>
                <svg class="w-3.5 h-3.5 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
            <a href="{{ route('menu') }}" class="btn-outline rounded-full text-xs px-8 py-3.5 bg-white/70 dark:bg-zinc-900/70 backdrop-blur-md border-zinc-300/80 dark:border-white/20 text-zinc-950 dark:text-white hover:bg-white dark:hover:bg-zinc-800 w-full sm:w-auto text-center shadow-sm">
                Buka Buku Menu Lengkap
            </a>
        </div>

        {{-- 3 Mini Highlight Pills (Melayang Lembut di Atas Ambiance Cafe) --}}
        <div class="inline-flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 max-w-2xl mx-auto mb-6 reveal">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/70 dark:bg-zinc-900/70 border border-white/80 dark:border-white/10 backdrop-blur-md shadow-sm">
                <span class="text-sm">☕</span>
                <span class="text-[11px] font-medium text-zinc-800 dark:text-zinc-200">Specialty Coffee & Dessert</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/70 dark:bg-zinc-900/70 border border-white/80 dark:border-white/10 backdrop-blur-md shadow-sm">
                <span class="text-sm">⚡</span>
                <span class="text-[11px] font-medium text-zinc-800 dark:text-zinc-200">WiFi Kencang & Ramah WFC</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/70 dark:bg-zinc-900/70 border border-white/80 dark:border-white/10 backdrop-blur-md shadow-sm">
                <span class="text-sm">🤍</span>
                <span class="text-[11px] font-medium text-zinc-800 dark:text-zinc-200">Monochrome Aesthetic</span>
            </div>
        </div>

        {{-- Ringkasan Jam Operasional (Sesuai Sketsa, Tanpa Kotak Kaku) --}}
        <div class="pt-5 border-t border-zinc-900/10 dark:border-white/10 max-w-xl mx-auto reveal flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-[11px] font-mono text-zinc-600 dark:text-zinc-400">
            <span class="px-3 py-1 rounded-full bg-white/60 dark:bg-zinc-900/60 backdrop-blur-sm border border-white/60 dark:border-white/5">
                Selasa–Jum: 11.00–22.00
            </span>
            <span class="px-3 py-1 rounded-full bg-white/60 dark:bg-zinc-900/60 backdrop-blur-sm border border-white/60 dark:border-white/5">
                Sabtu–Minggu: 11.00–23.00
            </span>
            <span class="px-3 py-1 rounded-full bg-rose-50/80 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-900/40">
                Senin: Libur
            </span>
            <a href="#jam-operasional-detail" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-950 dark:hover:text-white underline underline-offset-4 ml-1">Detail →</a>
        </div>

    </div>

    {{-- Smooth Scroll Indicator (Melayang Lembut) --}}
    <div class="mt-8 flex flex-col items-center gap-1.5 animate-bounce opacity-50">
        <span class="text-[9px] uppercase tracking-widest font-mono text-zinc-500 dark:text-zinc-400">Scroll</span>
        <div class="w-px h-5 bg-zinc-900 dark:bg-white"></div>
    </div>
</section>

{{-- ═══ BEST MENU SECTION (SEAMLESS FLOW LANGSUNG DARI HERO TANPA KOTAK PEMISAH) ═══ --}}
<section id="best-menu" class="relative py-20 sm:py-28 text-zinc-900 dark:text-white transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header (Fluid Header Tanpa Garis Pembatas Kaku) --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6 mb-12 reveal">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900/5 dark:bg-white/10 text-zinc-700 dark:text-zinc-300 text-[11px] font-mono uppercase tracking-widest mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>Favorites & Best Sellers</span>
                </div>
                <h2 class="text-3xl sm:text-5xl font-serif font-bold text-zinc-950 dark:text-white tracking-tight">
                    Best Menu Nucomu
                </h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm mt-2 max-w-xl font-normal">
                    Sajian artisan dessert dan seduhan kopi favorit yang paling sering dipilih dan dicintai pelanggan.
                </p>
            </div>
            
            {{-- Option Link Kanan Atas (Floating Pill Button) --}}
            <a href="{{ route('menu') }}" class="group inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-white/10 text-xs font-bold uppercase tracking-wider text-zinc-950 dark:text-white shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <span>Lihat Semua Menu</span>
                <svg class="w-4 h-4 group-hover:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        {{-- Best Menu Grid (Kartu Melengkung Organik & Lembut) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-7">
            @foreach($featuredMenus as $menu)
                <div class="group bg-white dark:bg-zinc-900/90 border border-zinc-200/60 dark:border-white/10 rounded-[2rem] overflow-hidden hover:border-zinc-400/80 dark:hover:border-zinc-600 transition-all duration-500 flex flex-col justify-between hover:-translate-y-2 hover:shadow-2xl shadow-md">
                    <div>
                        {{-- Image Container --}}
                        <div class="relative h-52 sm:h-60 bg-zinc-100 dark:bg-zinc-950 overflow-hidden rounded-t-[2rem]">
                            @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-zinc-100 dark:bg-zinc-900 text-zinc-400 dark:text-zinc-600 p-4 text-center">
                                    <span class="text-3xl mb-1">☕</span>
                                    <span class="text-xs uppercase tracking-widest font-mono text-zinc-500">Nucomu Best</span>
                                </div>
                            @endif

                            {{-- Badges --}}
                            <div class="absolute top-3.5 left-3.5 flex flex-wrap gap-1.5 z-10">
                                @if($menu->is_best_seller)
                                    <span class="bg-zinc-950 text-white dark:bg-white dark:text-zinc-950 text-[10px] uppercase font-black tracking-widest px-3 py-1 rounded-full shadow-md">
                                        Best Seller
                                    </span>
                                @endif
                                @if($menu->is_featured)
                                    <span class="bg-white/85 text-zinc-950 border border-white/80 dark:bg-zinc-900/85 dark:border-white/10 dark:text-white text-[10px] uppercase font-bold tracking-wider px-3 py-1 rounded-full backdrop-blur-md shadow-sm">
                                        Unggulan
                                    </span>
                                @endif
                            </div>

                            {{-- Category Badge --}}
                            <div class="absolute bottom-3.5 right-3.5 z-10">
                                <span class="bg-white/85 dark:bg-zinc-950/85 backdrop-blur-md text-zinc-800 dark:text-zinc-300 border border-white/80 dark:border-white/10 text-[11px] font-semibold px-3 py-1 rounded-full shadow-sm">
                                    {{ $menu->category->name ?? 'Best Menu' }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-zinc-950 dark:text-white group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors">
                                <a href="{{ route('menu.show', $menu->slug) }}">{{ $menu->name }}</a>
                            </h3>
                            <p class="text-zinc-600 dark:text-zinc-400 text-xs mt-2 line-clamp-2 leading-relaxed">
                                {{ $menu->description ?? 'Nikmati kelezatan racikan khas Nucomu Cafe Tulungagung.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Footer Card --}}
                    <div class="p-6 pt-0 flex items-center justify-between mt-2">
                        <div>
                            <span class="text-[10px] text-zinc-500 uppercase font-mono block">Harga</span>
                            <span class="text-base font-extrabold text-zinc-950 dark:text-white font-mono">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('menu.show', $menu->slug) }}" 
                           class="bg-zinc-950 hover:bg-zinc-800 text-white dark:bg-white dark:hover:bg-zinc-200 dark:text-zinc-950 text-xs font-bold px-4 py-2.5 rounded-full transition-all flex items-center space-x-1.5 shadow-md hover:scale-105">
                            <span>Detail</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-14 reveal">
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-widest border border-zinc-900/30 dark:border-white/30 text-zinc-900 dark:text-white hover:bg-zinc-900 hover:text-white dark:hover:bg-white dark:hover:text-zinc-900 transition-all shadow-sm hover:shadow-lg">
                <span>Lihat Seluruh Katalog Menu</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══ CERITA SINGKAT (GLASSMORPHISM JOURNAL CARD) ═══ --}}
<section id="cerita-singkat" class="relative py-24 text-zinc-900 dark:text-white overflow-hidden transition-colors duration-300">
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-3xl p-7 sm:p-14 shadow-2xl relative overflow-hidden reveal">
            
            <span class="absolute -top-10 -left-4 text-9xl sm:text-[14rem] font-serif opacity-10 select-none pointer-events-none leading-none">“</span>

            <div class="flex items-center justify-between mb-8 pb-6 border-b border-zinc-200/70 dark:border-white/10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 text-[11px] tracking-widest uppercase font-bold shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Cerita Kami</span>
                </div>
                <div class="flex items-center gap-2 text-[11px] font-mono tracking-widest text-zinc-500 dark:text-zinc-400 uppercase">
                    <span class="hidden sm:inline">EST. 2020</span>
                    <span class="hidden sm:inline">•</span>
                    <span>TULUNGAGUNG</span>
                </div>
            </div>

            <div class="max-w-2xl mb-8">
                <p class="text-xs font-mono uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-2">Philosophy & Journey</p>
                <h2 class="text-3xl sm:text-5xl font-serif font-bold text-zinc-950 dark:text-white tracking-tight leading-tight">
                    Dari Rindu Rasa,<br>
                    <span class="italic font-normal text-zinc-600 dark:text-zinc-300 underline decoration-zinc-300 dark:decoration-white/30 decoration-2 underline-offset-8">Lahirlah Nucomu.</span>
                </h2>
            </div>

            <div class="relative bg-white/60 dark:bg-zinc-900/60 backdrop-blur-md border border-white/70 dark:border-white/10 rounded-2xl p-6 sm:p-9 my-8 text-left transition-all shadow-sm">
                <div class="absolute left-0 top-6 bottom-6 w-1 bg-zinc-900 dark:bg-white rounded-r-full"></div>
                
                <div class="flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-4">
                    <svg class="w-4 h-4 text-zinc-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>Kisah Singkat</span>
                </div>

                <div class="min-h-[110px] sm:min-h-[90px]">
                    <p id="story-typewriter" 
                       data-text="{{ $settings['cafe_story'] ?? 'Nucomu Cafe hadir sebagai tempat yang nyaman untuk menikmati dessert, makanan, snack, kopi, dan minuman lainnya. Dengan konsep monokrom elegan — perpaduan hitam, putih, dan abu-abu — kami ingin setiap sudut cafe terasa seperti rumah kedua. Banyak dipilih pelanggan sebagai tempat favorit untuk WFC (Work From Cafe).' }}" 
                       class="text-zinc-800 dark:text-zinc-200 font-serif sm:text-lg text-base leading-relaxed tracking-wide inline">
                    </p>
                    <span id="story-cursor" class="inline-block w-0.5 h-5 sm:h-6 bg-zinc-900 dark:bg-white ml-1 animate-pulse align-middle"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 my-8">
                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-sm border border-white/70 dark:border-white/10 rounded-2xl p-4 flex items-center gap-3.5 shadow-sm hover:border-zinc-400 dark:hover:border-zinc-700 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 flex items-center justify-center font-serif font-black text-base shrink-0 shadow-sm">
                        20
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Awal 2020</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Berawal dari Banoffea</p>
                    </div>
                </div>

                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-sm border border-white/70 dark:border-white/10 rounded-2xl p-4 flex items-center gap-3.5 shadow-sm hover:border-zinc-400 dark:hover:border-zinc-700 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-stone-100/80 dark:bg-black/60 border border-zinc-300/80 dark:border-white/10 text-zinc-900 dark:text-white flex items-center justify-center text-base shrink-0 shadow-sm">
                        ☕
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Monokrom</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Elegan, Tenang & Bersih</p>
                    </div>
                </div>

                <div class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-sm border border-white/70 dark:border-white/10 rounded-2xl p-4 flex items-center gap-3.5 shadow-sm hover:border-zinc-400 dark:hover:border-zinc-700 transition-all duration-300">
                    <div class="w-10 h-10 rounded-xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 flex items-center justify-center text-sm font-bold shrink-0 shadow-sm">
                        NU
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Favorit WFC</h4>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Cozy, Nyaman & Produktif</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-zinc-200/70 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-zinc-500 dark:text-zinc-400 italic font-serif">
                    "New, Unforgettable, Comfy, Musings"
                </p>
                <a href="{{ route('about') }}" class="btn-primary rounded-full px-7 py-3 text-xs tracking-widest uppercase flex items-center gap-2 group shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <span>Baca Cerita Selengkapnya</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ═══ FASILITAS (ORGANIC & FLUID) ═══ --}}
<section class="py-20 sm:py-24 text-zinc-900 dark:text-white transition-colors duration-300 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900/5 dark:bg-white/10 text-zinc-700 dark:text-zinc-300 text-[11px] font-mono uppercase tracking-widest mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Kenyamanan Anda</span>
            </div>
            <h2 class="text-3xl sm:text-5xl font-serif font-bold text-zinc-950 dark:text-white tracking-tight">Fasilitas Nucomu</h2>
            <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm mt-2 max-w-md mx-auto">Dirancang untuk kenyamanan santai, hangout hangat, dan produktivitas WFC Anda.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-6">
            @foreach($facilities as $f)
            <div class="text-center p-6 bg-white dark:bg-zinc-900/80 border border-zinc-200/60 dark:border-white/10 hover:border-zinc-400/80 dark:hover:border-zinc-600 transition-all rounded-[2rem] reveal shadow-sm hover:shadow-xl hover:-translate-y-1.5 backdrop-blur-sm">
                <div class="w-12 h-12 bg-zinc-950 text-white dark:bg-white dark:text-zinc-950 rounded-2xl mx-auto mb-3.5 flex items-center justify-center font-bold shadow-md">
                    <span class="text-lg">⚡</span>
                </div>
                <p class="font-bold text-zinc-900 dark:text-white text-sm tracking-wide">{{ $f->label }}</p>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12 reveal">
            <a href="{{ route('facilities') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:text-zinc-950 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/10 transition-all">
                <span>Lihat Fasilitas Selengkapnya</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══ JAM OPERASIONAL ═══ --}}
<section id="jam-operasional-detail" class="relative py-24 text-zinc-900 dark:text-white overflow-hidden transition-colors duration-300">
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-zinc-200/80 dark:border-white/20 bg-white/80 dark:bg-black/60 backdrop-blur-md mb-4 shadow-md">
                <svg class="w-3.5 h-3.5 text-zinc-600 dark:text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs uppercase tracking-widest text-zinc-700 dark:text-zinc-300 font-medium">Kapan Kami Buka</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-serif font-bold tracking-tight text-zinc-950 dark:text-white mb-3">
                Jam Operasional Lengkap
            </h2>
            <p class="text-zinc-600 dark:text-zinc-300 text-sm md:text-base font-normal dark:font-light leading-relaxed">
                Jadwal harian buka cafe & waktu pelayanan pesanan di Nucomu Cafe.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            <div class="lg:col-span-5 glass-card rounded-[2.25rem] p-7 sm:p-9 flex flex-col justify-between relative overflow-hidden shadow-2xl reveal">
                <div>
                    <div class="mb-6">
                        @if($isOpen)
                            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-300 dark:border-emerald-500/40 text-emerald-800 dark:text-emerald-300 text-xs font-semibold tracking-wider uppercase shadow-inner">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span>Buka Sekarang</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-zinc-200 dark:bg-zinc-800/80 border border-zinc-300 dark:border-zinc-700/60 text-zinc-700 dark:text-zinc-400 text-xs font-semibold tracking-wider uppercase">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-zinc-500"></span>
                                <span>Sedang Tutup</span>
                            </div>
                        @endif
                    </div>

                    <h3 class="text-2xl font-serif font-bold text-zinc-950 dark:text-white mb-3">
                        Ruang Nyaman, Kopi Berkesan
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-300 text-sm leading-relaxed mb-6 font-normal dark:font-light">
                        Nucomu Cafe didesain dengan konsep monokrom yang tenang dan elegan. Pilihan tepat untuk santai, bekerja nyaman (WFC), atau berbagi cerita hangat.
                    </p>
                </div>

                <div class="pt-6 border-t border-zinc-200/60 dark:border-white/10 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('reservation') }}" class="btn-primary text-center text-xs tracking-widest uppercase rounded-full">
                        Reservasi Meja
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline text-center text-xs tracking-widest uppercase rounded-full border-zinc-900 text-zinc-900 dark:border-white dark:text-white">
                        Lokasi & Kontak
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7 glass-card rounded-[2.25rem] p-6 sm:p-8 shadow-2xl flex flex-col justify-between reveal">
                <div>
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200/60 dark:border-white/10 text-xs font-semibold tracking-wider uppercase text-zinc-500 dark:text-zinc-400">
                        <span>Hari</span>
                        <span>Jam Buka – Tutup</span>
                    </div>

                    <div class="space-y-2">
                        @foreach($hours as $h)
                            @php $isToday = ($h->day_of_week == $now->dayOfWeek); @endphp
                            @if($isToday)
                                <div class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 font-semibold shadow-lg">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-sm sm:text-base font-bold">{{ $h->day_name }}</span>
                                        <span class="text-[10px] sm:text-xs bg-white text-zinc-950 dark:bg-zinc-950 dark:text-white px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                            Hari Ini
                                        </span>
                                    </div>
                                    <div>
                                        @if($h->is_closed)
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-500 text-white uppercase tracking-widest">
                                                LIBUR
                                            </span>
                                        @else
                                            <span class="font-mono text-sm sm:text-base font-bold tracking-tight">
                                                {{ substr($h->open_time, 0, 5) }} – {{ substr($h->close_time, 0, 5) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @elseif($h->is_closed)
                                <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-white/40 dark:bg-white/[0.03] border border-zinc-200/60 dark:border-white/[0.05] text-zinc-500 dark:text-zinc-400">
                                    <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ $h->day_name }}</span>
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-950/60 dark:border-red-800/60 dark:text-red-300 uppercase tracking-wider">
                                        LIBUR
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-white/40 dark:bg-white/[0.03] border border-zinc-200/60 dark:border-white/[0.05] text-zinc-800 dark:text-zinc-200">
                                    <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $h->day_name }}</span>
                                    <span class="font-mono text-sm tracking-tight text-zinc-700 dark:text-zinc-300">
                                        {{ substr($h->open_time, 0, 5) }} – {{ substr($h->close_time, 0, 5) }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ PREVIEW GALERI (ORGANIC & FLUID) ═══ --}}
@if(isset($gallery) && $gallery->count() > 0)
<section class="py-20 sm:py-24 text-zinc-900 dark:text-white transition-colors duration-300 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900/5 dark:bg-white/10 text-zinc-700 dark:text-zinc-300 text-[11px] font-mono uppercase tracking-widest mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Dokumentasi & Momen</span>
            </div>
            <h2 class="text-3xl sm:text-5xl font-serif font-bold text-zinc-950 dark:text-white tracking-tight">Galeri Foto Nucomu</h2>
            <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm mt-2 max-w-md mx-auto">Sudut-sudut hangat dan sajian estetik yang terekam di Nucomu Cafe.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
            @foreach($gallery->take(6) as $item)
            <div class="aspect-square bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-white/10 rounded-[2rem] flex items-center justify-center overflow-hidden group reveal-scale shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                @if($item->image && file_exists(public_path('storage/' . $item->image)))
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->caption }}" class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700">
                @else
                    <div class="text-center text-zinc-500 text-xs p-4">
                        <div class="w-12 h-12 border-2 border-dashed border-zinc-300 dark:border-zinc-700 mx-auto mb-2 rounded-2xl"></div>
                        {{ $item->caption }}
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12 reveal">
            <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full text-xs font-bold uppercase tracking-wider border border-zinc-900/20 dark:border-white/20 text-zinc-900 dark:text-white hover:bg-zinc-900 hover:text-white dark:hover:bg-white dark:hover:text-zinc-900 transition-all shadow-sm">
                <span>Lihat Semua Foto</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══ TESTIMONI (ORGANIC & FLUID) ═══ --}}
@if(isset($testimonials) && $testimonials->count() > 0)
<section class="py-20 sm:py-24 text-zinc-900 dark:text-white transition-colors duration-300 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900/5 dark:bg-white/10 text-zinc-700 dark:text-zinc-300 text-[11px] font-mono uppercase tracking-widest mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Kata Mereka</span>
            </div>
            <h2 class="text-3xl sm:text-5xl font-serif font-bold text-zinc-950 dark:text-white tracking-tight">Testimoni Pelanggan</h2>
            <p class="text-zinc-600 dark:text-zinc-400 text-xs sm:text-sm mt-2 max-w-md mx-auto">Pengalaman hangat mereka yang telah berkunjung dan menikmati Nucomu.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimonials as $t)
            <div class="bg-white/80 dark:bg-zinc-900/80 p-7 border border-zinc-200/60 dark:border-white/10 rounded-[2rem] reveal shadow-md hover:shadow-xl hover:-translate-y-1.5 transition-all backdrop-blur-md">
                <div class="flex mb-3.5">
                    @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $t->rating ? 'text-amber-500 dark:text-amber-400' : 'text-zinc-300 dark:text-zinc-700' }} text-base">★</span>
                    @endfor
                </div>
                <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed italic mb-5">"{{ $t->content }}"</p>
                <p class="font-bold text-zinc-950 dark:text-white text-sm">— {{ $t->name }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ CTA LOKASI (ORGANIC & FLUID) ═══ --}}
<section class="py-20 sm:py-24 text-zinc-900 dark:text-white transition-colors duration-300 relative">
    <div class="max-w-4xl mx-auto px-4 text-center reveal">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-zinc-900/5 dark:bg-white/10 text-zinc-700 dark:text-zinc-300 text-[11px] font-mono uppercase tracking-widest mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Temukan Kami</span>
        </div>
        <h2 class="text-3xl sm:text-5xl font-serif font-bold text-zinc-950 dark:text-white tracking-tight mb-4">Kunjungi Nucomu Cafe</h2>
        <p class="text-zinc-700 dark:text-zinc-300 mb-1.5 text-base font-medium">{{ $settings['cafe_address'] ?? '[ISI: alamat lengkap]' }}</p>
        <p class="text-zinc-500 dark:text-zinc-400 text-sm mb-8">Tulungagung, Jawa Timur</p>
        
        <div class="bg-white/80 dark:bg-zinc-900/70 border border-zinc-200/80 dark:border-white/10 rounded-[2.25rem] h-64 flex items-center justify-center shadow-lg backdrop-blur-md">
            <div class="text-center text-zinc-500">
                <div class="text-3xl mb-2">📍</div>
                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Google Maps Embed</p>
                <p class="text-xs mt-1">[ISI: embed link Google Maps]</p>
            </div>
        </div>
        <div class="flex flex-row gap-10 justify-center items-center mt-8">
            <a href="{{ route('contact') }}" class="btn-text-link text-zinc-900 dark:text-white">Info Kontak</a>
            <a href="{{ route('reservation') }}" class="btn-text-link text-zinc-900 dark:text-white">Buat Reservasi</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const typewriterEl = document.getElementById('story-typewriter');
        const cursorEl = document.getElementById('story-cursor');
        
        if (!typewriterEl) return;

        const fullText = typewriterEl.getAttribute('data-text') || '';
        let typingTimer = null;
        let isTyping = false;

        const resetTyping = () => {
            if (typingTimer) {
                clearTimeout(typingTimer);
                typingTimer = null;
            }
            isTyping = false;
            typewriterEl.textContent = '';
            if (cursorEl) {
                cursorEl.style.display = 'inline-block';
            }
        };

        const startTyping = () => {
            if (isTyping) return;
            isTyping = true;
            let i = 0;
            typewriterEl.textContent = '';

            const typeNext = () => {
                if (!isTyping) return;
                if (i < fullText.length) {
                    typewriterEl.textContent += fullText.charAt(i);
                    const char = fullText.charAt(i);
                    i++;
                    
                    let delay = 18;
                    if (char === '.' || char === '!' || char === '?') {
                        delay = 140;
                    } else if (char === ',' || char === '—' || char === '-') {
                        delay = 90;
                    }

                    typingTimer = setTimeout(typeNext, delay);
                } else {
                    isTyping = false;
                    if (cursorEl) {
                        cursorEl.classList.remove('animate-pulse');
                        cursorEl.style.animation = 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite';
                    }
                }
            };

            typeNext();
        };

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        startTyping();
                    } else {
                        resetTyping();
                    }
                });
            }, {
                threshold: 0.2,
                rootMargin: '0px 0px -40px 0px'
            });

            observer.observe(typewriterEl);
        } else {
            typewriterEl.textContent = fullText;
        }
    });
</script>
@endpush
