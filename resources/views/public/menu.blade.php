@extends('layouts.app')
@section('title', 'Katalog Menu — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header & Search --}}
        <div class="text-center max-w-3xl mx-auto mb-10">
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white uppercase mb-4">
                Katalog <span class="text-zinc-500">Menu</span>
            </h1>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">
                Nikmati perpaduan kopi pilihan dan dessert artisanal buatan rumah di Nucomu Cafe. Disajikan hangat, dingin, dan dengan sepenuh hati.
            </p>

            {{-- Form Pencarian --}}
            <form action="{{ route('menu') }}" method="GET" class="mt-8 flex items-center max-w-lg mx-auto bg-zinc-900/90 border border-zinc-800 rounded-full p-1.5 focus-within:border-white transition-all">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Banoffee, Nucomu Coffee, Cheesecake..." 
                    class="w-full bg-transparent px-5 py-2.5 text-sm text-white placeholder-zinc-500 focus:outline-none">
                <button type="submit" class="bg-white text-zinc-950 font-bold px-5 py-2.5 rounded-full hover:bg-zinc-200 transition-colors flex items-center space-x-2 shrink-0 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari</span>
                </button>
            </form>
        </div>

        {{-- Filter Kategori --}}
        <div class="flex items-center justify-start sm:justify-center space-x-2 overflow-x-auto pb-4 mb-10 no-scrollbar">
            <a href="{{ route('menu', array_filter(['q' => request('q')])) }}" 
               class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all border {{ !request('category') ? 'bg-white text-zinc-950 border-white shadow-lg shadow-white/10' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:border-zinc-700 hover:text-white' }}">
               Semua Menu
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('menu', array_filter(['category' => $cat->slug, 'q' => request('q')])) }}" 
                   class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all border {{ request('category') === $cat->slug ? 'bg-white text-zinc-950 border-white shadow-lg shadow-white/10' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:border-zinc-700 hover:text-white' }}">
                   {{ $cat->name }}
                </a>
            @endforeach
        </div>

        {{-- Active Filter Info --}}
        @if(request('category') || request('q'))
            <div class="flex items-center justify-between bg-zinc-900/60 border border-zinc-800/80 rounded-2xl px-5 py-3 mb-8 text-sm text-zinc-400">
                <div>
                    Menampilkan hasil 
                    @if(request('q')) pencarian "<span class="text-white font-semibold">{{ request('q') }}</span>"@endif
                    @if(request('category')) kategori "<span class="text-white font-semibold">{{ $categories->firstWhere('slug', request('category'))?->name ?? request('category') }}</span>"@endif
                </div>
                <a href="{{ route('menu') }}" class="text-xs text-zinc-400 hover:text-white underline">Bersihkan Filter</a>
            </div>
        @endif

        {{-- Grid Card Menu --}}
        @if($menus->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($menus as $menu)
                    <div class="group bg-zinc-900/60 border border-zinc-800/80 rounded-2xl overflow-hidden hover:border-zinc-600 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1">
                        <div>
                            {{-- Image Container --}}
                            <div class="relative h-48 sm:h-56 bg-zinc-950 overflow-hidden">
                                @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-zinc-900 text-zinc-600 p-4 text-center">
                                        <svg class="w-12 h-12 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span class="text-xs uppercase tracking-widest font-mono text-zinc-500">Nucomu Menu</span>
                                    </div>
                                @endif

                                {{-- Badges --}}
                                <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                    @if($menu->is_featured)
                                        <span class="bg-white text-zinc-950 text-[10px] uppercase font-black tracking-widest px-2.5 py-1 rounded-full shadow-md">
                                            Unggulan
                                        </span>
                                    @endif
                                    @if($menu->is_best_seller)
                                        <span class="bg-zinc-950 text-white border border-zinc-700 text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-full">
                                            Best Seller
                                        </span>
                                    @endif
                                    @if($menu->is_sample)
                                        <span class="bg-zinc-800/90 text-zinc-400 text-[10px] font-mono px-2 py-0.5 rounded border border-zinc-700">
                                            Sample
                                        </span>
                                    @endif
                                </div>

                                {{-- Kategori Badge --}}
                                <div class="absolute bottom-3 right-3">
                                    <span class="bg-zinc-950/80 backdrop-blur-md text-zinc-300 border border-zinc-800 text-[11px] font-medium px-2.5 py-1 rounded-full">
                                        {{ $menu->category->name ?? 'Menu' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-white group-hover:text-zinc-200 transition-colors">
                                    {{ $menu->name }}
                                </h3>
                                <p class="text-zinc-400 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $menu->description ?? 'Nikmati kelezatan perpaduan cita rasa khas Nucomu Cafe.' }}
                                </p>
                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="p-5 pt-0 flex items-center justify-between mt-2">
                            <div>
                                <span class="text-xs text-zinc-500 block">Harga</span>
                                <span class="text-base font-extrabold text-white">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <a href="{{ route('menu.show', $menu->slug) }}" 
                               class="bg-white hover:bg-zinc-200 text-zinc-950 text-xs font-bold px-4 py-2.5 rounded-full transition-colors flex items-center space-x-1.5">
                                <span>Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-zinc-900/40 border border-zinc-800/80 rounded-3xl p-8">
                <svg class="w-16 h-16 text-zinc-600 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-bold text-white mb-2">Menu Tidak Ditemukan</h3>
                <p class="text-zinc-400 text-sm max-w-md mx-auto mb-6">
                    Maaf, menu yang Anda cari belum tersedia atau tidak sesuai dengan kata kunci pencarian.
                </p>
                <a href="{{ route('menu') }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-white text-zinc-950 font-bold text-xs uppercase tracking-wider hover:bg-zinc-200 transition-colors">
                    Lihat Semua Menu
                </a>
            </div>
        @endif

    </div>
</section>
@endsection
