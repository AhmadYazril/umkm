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

        <!-- Full Catalog Grid (Popular Dish Floating Plate Layout + Expandable Options / Add to Cart on Click) -->
        @if(count($filteredMenu) > 0)
            <div id="catalog-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 items-start gap-x-6 gap-y-20 pt-16 pb-16">
                @foreach($filteredMenu as $index => $item)
                    @php
                        $cat = $item['category'] ?? 'coffee';
                        $unit = $item['unit'] ?? ($cat === 'coffee' || $cat === 'beverages' ? '/cup' : ($cat === 'dessert' ? '/slice' : '/porsi'));
                        $hasOptions = !isset($item['options']) || !empty($item['options']);
                        $opt1 = $hasOptions ? ($item['options'][0] ?? match($cat) {
                            'coffee', 'beverages' => ['title' => 'Level Manis', 'choices' => ['Normal (100%)', 'Less Sugar (50%)', 'Tanpa Gula (0%)']],
                            'meals' => ['title' => 'Pilihan Telur', 'choices' => ['Sunny Side Up', 'Over Medium', 'Well Done']],
                            'dessert' => ['title' => 'Suhu Saji', 'choices' => ['Chilled (Dingin)', 'Room Temp', 'Warm (Hangat)']],
                            'snacks' => ['title' => 'Pilihan Saus', 'choices' => ['Truffle Mayo', 'Smoky BBQ', 'Sambal Nucomu']],
                            default => ['title' => 'Pilihan Rasa', 'choices' => ['Normal', 'Less Sugar', 'Tanpa Gula']]
                        }) : null;
                        $opt2 = $hasOptions ? ($item['options'][1] ?? match($cat) {
                            'coffee', 'beverages' => ['title' => 'Penyajian', 'choices' => ['Dingin / Iced', 'Hangat / Hot', 'Extra Oat Milk']],
                            'meals' => ['title' => 'Spicy Level', 'choices' => ['Mild', 'Medium Spicy', 'Extra Hot']],
                            'dessert' => ['title' => 'Extra Topping', 'choices' => ['Vanilla Cream', 'Berry Compote', 'Original']],
                            'snacks' => ['title' => 'Porsi', 'choices' => ['Regular Size', 'Sharing Size', 'Extra Parmesan']],
                            default => ['title' => 'Penyajian', 'choices' => ['Dingin', 'Hangat']]
                        }) : null;
                    @endphp
                    <div class="popular-dish-card group relative bg-white border border-stone-200/80 rounded-[2rem] p-5 pt-18 sm:pt-20 pb-5 shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer h-fit self-start"
                         data-category="{{ $item['category'] }}"
                         data-item-id="{{ $item['id'] }}"
                         onclick="togglePopularDishOptions(this)">
                        
                        <!-- Floating Circular Plate -->
                        <div class="dish-plate-wrap absolute -top-12 sm:-top-16 left-1/2 -translate-x-1/2 w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden p-1.5 bg-white shadow-xl border border-stone-200/90 transition-transform duration-300 group-hover:scale-105 z-10 flex items-center justify-center">
                            <img src="{{ $item['image'] }}" 
                                 alt="{{ $item['name'] }}" 
                                 class="w-full h-full object-cover rounded-full">
                        </div>

                        <!-- Card Body: Name & Price ONLY ("cm keterangan harga sma nama", add keranjang tidak langsung muncul) -->
                        <div class="text-center pt-2">
                            <h3 class="font-editorial text-lg sm:text-xl font-bold text-neutral-900 leading-snug line-clamp-1 group-hover:text-black transition-colors">
                                {{ $item['name'] }}
                            </h3>
                            <div class="mt-1 flex items-baseline justify-center space-x-1">
                                <span class="card-price font-editorial text-lg font-bold text-neutral-900">
                                    {{ $item['price_formatted'] }}
                                </span>
                                <span class="text-xs text-neutral-400 font-normal">
                                    {{ $unit }}
                                </span>
                            </div>
                        </div>

                        <!-- Options Panel: Expands on Click ("nanti kalau di-klik baru muncul") -->
                        <div class="product-options-panel border-stone-150/80"
                             onclick="event.stopPropagation()">
                            
                            @if($hasOptions)
                                <div class="grid grid-cols-2 gap-3 mb-4 text-left">
                                    <!-- Column 1 -->
                                    @if($opt1)
                                    <div>
                                        <span class="text-xs font-bold text-neutral-800 block mb-2 tracking-tight">
                                            {{ $opt1['title'] }}
                                        </span>
                                        <div class="space-y-1.5">
                                            @foreach($opt1['choices'] as $cIdx => $choice)
                                                <label class="flex items-center space-x-2 text-[11px] sm:text-xs text-neutral-600 cursor-pointer hover:text-neutral-900 select-none group">
                                                    <input type="radio" 
                                                           name="opt1_cat_{{ $item['id'] }}" 
                                                           value="{{ $choice }}" 
                                                           {{ $cIdx === 0 ? 'checked' : '' }}
                                                           class="accent-neutral-900 w-3.5 h-3.5 cursor-pointer">
                                                    <span class="group-hover:text-neutral-900">{{ $choice }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Column 2 -->
                                    @if($opt2)
                                    <div>
                                        <span class="text-xs font-bold text-neutral-800 block mb-2 tracking-tight">
                                            {{ $opt2['title'] }}
                                        </span>
                                        <div class="space-y-1.5">
                                            @foreach($opt2['choices'] as $cIdx => $choice)
                                                <label class="flex items-center space-x-2 text-[11px] sm:text-xs text-neutral-600 cursor-pointer hover:text-neutral-900 select-none group">
                                                    <input type="radio" 
                                                           name="opt2_cat_{{ $item['id'] }}" 
                                                           value="{{ $choice }}" 
                                                           {{ $cIdx === 0 ? 'checked' : '' }}
                                                           class="accent-neutral-900 w-3.5 h-3.5 cursor-pointer">
                                                    <span class="group-hover:text-neutral-900">{{ $choice }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <!-- Menu Tanpa Varian: Keterangan singkat bila ada, tanpa opsi radio -->
                                @if(!empty($item['short_info']))
                                    <p class="text-[11px] text-neutral-500 text-center mb-3 leading-snug line-clamp-2">
                                        {{ $item['short_info'] }}
                                    </p>
                                @endif
                            @endif

                            <!-- Add to Cart Button (Muncul setelah kartu diklik) -->
                            <button type="button" 
                                    onclick="event.stopPropagation(); submitPopularDishOrder(this.closest('.popular-dish-card'), '{{ addslashes($item['name']) }}', '{{ $item['price_formatted'] }}')" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-neutral-900 hover:bg-black text-white text-xs sm:text-sm font-bold tracking-wide shadow-md shadow-neutral-900/15 active:scale-95 transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                                <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                                <span>+ Add to Cart</span>
                            </button>
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
