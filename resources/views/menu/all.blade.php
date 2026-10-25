@extends('layouts.app')

@section('title', 'Katalog Lengkap Menu — Nucomu Cafe Tulungagung')
@section('meta_description', 'Jelajahi seluruh daftar menu minuman kopi, non-kopi, dessert artisanal, dan makanan ringan di Nucomu Cafe Tulungagung.')

@section('content')
<div class="pt-28 pb-20 bg-warm-ivory min-h-screen border-b border-light-gray/60">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- Breadcrumbs & Back Link -->
        <div class="mb-8">
            <a href="{{ route('home') }}#menu" class="inline-flex items-center space-x-1.5 text-xs uppercase tracking-editorial-widest font-semibold text-medium-gray hover:text-ink-black transition-colors">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-[11px] font-semibold uppercase tracking-editorial-widest text-medium-gray block mb-2">KATALOG LENGKAP MENU</span>
            <h1 class="font-editorial text-4xl sm:text-6xl font-bold text-ink-black tracking-tight">Katalog Sajian Nucomu</h1>
            <p class="font-body text-xs sm:text-sm text-charcoal/70 mt-3 leading-relaxed">
                Seluruh racikan kopi single origin Sendang Tulungagung, seduhan daun teh bergamot, ceremonial matcha, dan kue buatan rumah.
            </p>
        </div>

        <!-- Category Filter Tabs -->
        <div class="flex justify-center items-center flex-wrap gap-2.5 mb-14 border-b border-light-gray pb-4">
            <a href="{{ route('menu.all', ['category' => 'all']) }}" 
               class="px-5 py-2 rounded text-xs font-semibold uppercase tracking-wider transition-all {{ $selectedCategory === 'all' ? 'bg-neutral-900 text-white' : 'bg-white text-neutral-700 border border-neutral-200 hover:border-neutral-900' }}">
                Semua Menu
            </a>
            <a href="{{ route('menu.all', ['category' => 'coffee']) }}" 
               class="px-5 py-2 rounded text-xs font-semibold uppercase tracking-wider transition-all {{ $selectedCategory === 'coffee' ? 'bg-neutral-900 text-white' : 'bg-white text-neutral-700 border border-neutral-200 hover:border-neutral-900' }}">
                Coffee
            </a>
            <a href="{{ route('menu.all', ['category' => 'non-coffee']) }}" 
               class="px-5 py-2 rounded text-xs font-semibold uppercase tracking-wider transition-all {{ $selectedCategory === 'non-coffee' ? 'bg-neutral-900 text-white' : 'bg-white text-neutral-700 border border-neutral-200 hover:border-neutral-900' }}">
                Non-Coffee
            </a>
            <a href="{{ route('menu.all', ['category' => 'dessert']) }}" 
               class="px-5 py-2 rounded text-xs font-semibold uppercase tracking-wider transition-all {{ $selectedCategory === 'dessert' ? 'bg-neutral-900 text-white' : 'bg-white text-neutral-700 border border-neutral-200 hover:border-neutral-900' }}">
                Dessert
            </a>
            <a href="{{ route('menu.all', ['category' => 'snacks']) }}" 
               class="px-5 py-2 rounded text-xs font-semibold uppercase tracking-wider transition-all {{ $selectedCategory === 'snacks' ? 'bg-neutral-900 text-white' : 'bg-white text-neutral-700 border border-neutral-200 hover:border-neutral-900' }}">
                Food & Snacks
            </a>
        </div>

        <!-- Full Catalog Grid -->
        @if(count($filteredMenu) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($filteredMenu as $item)
                    <div class="bg-pure-white border border-soft-beige rounded p-4 sm:p-5 flex flex-col justify-between hover:border-ink-black transition-all duration-300 group shadow-sm">
                        <div>
                            <!-- Image -->
                            <div class="relative overflow-hidden aspect-square rounded-sm mb-3.5 bg-warm-ivory img-editorial border border-light-gray/60">
                                <img src="{{ $item['image'] }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <span class="absolute top-2 left-2 bg-warm-ivory/95 backdrop-blur-sm px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-charcoal rounded">
                                    {{ $item['category_label'] }}
                                </span>
                            </div>

                            <!-- Name, Short Info, Price -->
                            <h3 class="font-editorial text-lg sm:text-xl font-bold text-ink-black leading-snug group-hover:underline underline-offset-2">
                                {{ $item['name'] }}
                            </h3>
                            <p class="font-body text-[11px] text-medium-gray mt-1 line-clamp-2">
                                {{ $item['short_info'] }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-light-gray/60 flex items-center justify-between mt-4">
                            <span class="font-editorial text-base sm:text-lg font-bold text-ink-black">{{ $item['price_formatted'] }}</span>
                            <a href="{{ route('nucomu.order', ['add' => $item['name']]) }}" 
                               class="inline-flex items-center space-x-1 text-[11px] font-semibold uppercase tracking-wider text-ink-black border border-neutral-300 hover:border-neutral-900 px-3 py-1 rounded transition-colors">
                                <span>Pesan</span>
                                <span class="material-symbols-outlined text-xs">add</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-pure-white border border-soft-beige rounded max-w-xl mx-auto p-8 shadow-sm">
                <span class="material-symbols-outlined text-medium-gray text-5xl mb-3">coffee</span>
                <h3 class="font-editorial text-2xl font-bold text-ink-black">Menu Belum Tersedia</h3>
                <p class="font-body text-xs text-medium-gray mt-1 leading-relaxed">
                    Daftar sajian untuk kategori ini sedang disiapkan oleh barista kami.
                </p>
                <a href="{{ route('menu.all', ['category' => 'all']) }}" class="mt-5 inline-block text-xs font-semibold uppercase tracking-widest text-ink-black border-b border-ink-black pb-0.5">
                    Kembali ke Semua Menu &rarr;
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
