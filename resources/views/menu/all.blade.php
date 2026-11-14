@extends('layouts.app')

@section('title', 'Katalog Lengkap Menu — Nucomu Cafe Tulungagung')
@section('meta_description', 'Jelajahi seluruh daftar menu minuman kopi, non-kopi, dessert artisanal, dan makanan ringan di Nucomu Cafe Tulungagung.')

@section('content')
<div class="pt-28 pb-24 bg-[#F9F9F9] min-h-screen border-b border-light-gray/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs & Back Link -->
        <div class="mb-6">
            <a href="{{ route('home') }}#menu" class="inline-flex items-center space-x-1.5 text-xs uppercase tracking-editorial-widest font-semibold text-neutral-400 hover:text-neutral-900 transition-colors">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-[11px] font-semibold uppercase tracking-widest text-neutral-400 block mb-2">KATALOG LENGKAP MENU</span>
            <h1 class="font-editorial text-4xl sm:text-5xl lg:text-6xl font-bold text-neutral-900 tracking-tight">Katalog Sajian Nucomu</h1>
            <p class="font-body text-xs sm:text-sm text-neutral-500 mt-2.5 leading-relaxed">
                Koleksi racikan kopi single origin Sendang Tulungagung, seduhan daun teh bergamot, ceremonial matcha, dan sajian hangat dapur Nucomu.
            </p>
        </div>

        <!-- 1. Baris Kategori Dinamis dari Database (Teks dengan Indikator Titik Aktif) -->
        <div class="flex items-center justify-center flex-wrap gap-x-6 sm:gap-x-10 gap-y-3 mb-14 border-b border-stone-200/70 pb-3" role="tablist">
            @php
                $activeCategories = $dbCategories->filter(function($c) {
                    return (bool) $c->is_active;
                });
            @endphp

            <!-- Tab Semua Menu -->
            <a href="{{ route('menu.all', ['category' => 'all']) }}" 
               role="tab"
               aria-selected="{{ $selectedCategory === 'all' ? 'true' : 'false' }}"
               class="group flex flex-col items-center focus:outline-none transition-colors">
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider transition-colors {{ $selectedCategory === 'all' ? 'text-neutral-900 font-bold' : 'text-neutral-400 group-hover:text-neutral-700' }}">
                    Semua Menu
                </span>
                @if($selectedCategory === 'all')
                    <span class="w-1.5 h-1.5 rounded-full bg-neutral-900 mt-1.5"></span>
                @else
                    <span class="w-1.5 h-1.5 rounded-full bg-transparent mt-1.5"></span>
                @endif
            </a>

            <!-- Tab Kategori dari Database (Urutan Admin) -->
            @foreach($activeCategories as $catItem)
                @php
                    $isCurrentCatActive = ($selectedCategory === $catItem->slug);
                @endphp
                <a href="{{ route('menu.all', ['category' => $catItem->slug]) }}" 
                   role="tab"
                   aria-selected="{{ $isCurrentCatActive ? 'true' : 'false' }}"
                   class="group flex flex-col items-center focus:outline-none transition-colors">
                    <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider transition-colors {{ $isCurrentCatActive ? 'text-neutral-900 font-bold' : 'text-neutral-400 group-hover:text-neutral-700' }}">
                        {{ $catItem->name }}
                    </span>
                    @if($isCurrentCatActive)
                        <span class="w-1.5 h-1.5 rounded-full bg-neutral-900 mt-1.5"></span>
                    @else
                        <span class="w-1.5 h-1.5 rounded-full bg-transparent mt-1.5"></span>
                    @endif
                </a>
            @endforeach
        </div>

        @if(! ($txSettings['is_any_mode_active'] ?? true))
            <div class="max-w-2xl mx-auto mb-10 p-4 rounded-2xl bg-white/90 border border-stone-200 text-stone-600 text-center text-xs sm:text-sm shadow-xs">
                <i class="fa-solid fa-circle-info mr-1.5 text-neutral-900"></i>
                Pemesanan online sedang tidak tersedia saat ini. Silakan berkunjung langsung ke kafe kami.
            </div>
        @endif

        <!-- 2 & 3. N-Column Layout (5 Kolom Desktop, 3 Kolom Tablet, 2 Kolom Mobile) -->
        @if(count($filteredMenu) > 0)
            <!-- Source Cards Pool (Rendered in Blade, distributed into N independent vertical columns via JS) -->
            <div id="catalog-cards-pool" style="display: none;" aria-hidden="true">
                @foreach($filteredMenu as $index => $item)
                    @php
                        $itemSlug = \Illuminate\Support\Str::slug($item['id'] ?? $item['name']);
                        $cat = $item['category'] ?? 'coffee';
                        // Tentukan satuan berdasarkan data atau kategori fallback tanpa menambah kolom DB
                        $unit = $item['unit'] ?? match(strtolower($cat)) {
                            'coffee', 'kopi', 'non-coffee', 'non-kopi', 'beverages' => '/cup',
                            'dessert' => '/slice',
                            'snack', 'snacks', 'makanan', 'meals' => '/porsi',
                            default => '/porsi'
                        };
                        $hasOptions = !empty($item['options']);
                        $opt1 = $hasOptions ? ($item['options'][0] ?? null) : null;
                        $opt2 = $hasOptions ? ($item['options'][1] ?? null) : null;
                        $isAvailable = $item['is_available'] ?? true;
                    @endphp
                    <div class="menu-card-wrapper" 
                         data-index="{{ $index }}"
                         data-category="{{ $item['category'] ?? '' }}"
                         data-id="{{ $item['id'] ?? $itemSlug }}"
                         data-name="{{ $item['name'] }}"
                         data-price="{{ $item['price'] }}">
                        
                        <article class="menu-card {{ !$isAvailable ? 'opacity-65 grayscale-[30%]' : '' }}"
                                 data-card-id="cat-{{ $itemSlug }}-{{ $index }}"
                                 data-id="{{ $item['id'] ?? $itemSlug }}"
                                 data-name="{{ $item['name'] }}"
                                 data-price="{{ $item['price'] }}">
                            
                            <!-- Foto Bulat (Avatar) Menembus Tepi Atas Kartu (Klik untuk melihat foto menu) -->
                            <button type="button"
                                    class="menu-card-avatar"
                                    onclick="openMenuPhotoPreview(this)"
                                    aria-label="Lihat foto {{ $item['name'] }}"
                                    title="Klik untuk melihat foto">
                                <img src="{{ $item['image'] }}" 
                                     alt="{{ $item['name'] }}" 
                                     loading="lazy" 
                                     width="130" 
                                     height="130"
                                     class="w-full h-full object-cover">
                                @if(!$isAvailable)
                                    <span class="absolute inset-0 bg-black/60 text-white font-bold text-[10px] uppercase tracking-wider flex items-center justify-center">
                                        Habis
                                    </span>
                                @endif
                            </button>

                            <!-- Header Trigger: Klik nama/harga kartu untuk buka/tutup detail menu -->
                            <button type="button" 
                                    class="menu-card-header-btn" 
                                    onclick="toggleMenuCard(this.closest('.menu-card'))"
                                    aria-expanded="false"
                                    aria-controls="drawer-cat-{{ $itemSlug }}-{{ $index }}"
                                    title="Klik untuk membuka detail menu {{ $item['name'] }}">

                                <!-- Judul Menu Serif -->
                                <h3 class="menu-card-name" title="{{ $item['name'] }}">
                                    {{ $item['name'] }}
                                </h3>

                                <!-- Baris Harga & Satuan (font-variant-numeric: lining-nums) -->
                                <div class="menu-card-price-row">
                                    <span class="font-editorial-num">{{ $item['price_formatted'] }}</span>
                                    <span class="menu-card-unit">{{ $unit }}</span>
                                </div>
                            </button>

                            <!-- Drawer yang Mengintip Saat Hover (18px) dan Terbuka Penuh Saat Klik -->
                            <div id="drawer-cat-{{ $itemSlug }}-{{ $index }}" 
                                 class="menu-card-drawer" 
                                 onclick="event.stopPropagation()">
                                
                                <!-- Garis Pemisah Tipis (Mengintip saat Hover) -->
                                <div class="menu-card-divider"></div>

                                <!-- Isi Menu (Opsi Varian / Deskripsi Singkat + Tombol Add to Cart) -->
                                <div class="menu-card-drawer-content">
                                    @if($hasOptions)
                                        <div class="options-columns mb-3">
                                            @if($opt1)
                                            <div>
                                                <span class="option-group-title">{{ $opt1['title'] }}</span>
                                                <div class="space-y-0.5">
                                                    @foreach($opt1['choices'] as $cIdx => $choice)
                                                        <label class="radio-pill-label">
                                                            <input type="radio" 
                                                                   name="opt1_cat_{{ $itemSlug }}_{{ $index }}" 
                                                                   value="{{ $choice }}" 
                                                                   {{ $cIdx === 0 ? 'checked' : '' }}>
                                                            <span class="truncate">{{ $choice }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            @if($opt2)
                                            <div>
                                                <span class="option-group-title">{{ $opt2['title'] }}</span>
                                                <div class="space-y-0.5">
                                                    @foreach($opt2['choices'] as $cIdx => $choice)
                                                        <label class="radio-pill-label">
                                                            <input type="radio" 
                                                                   name="opt2_cat_{{ $itemSlug }}_{{ $index }}" 
                                                                   value="{{ $choice }}" 
                                                                   {{ $cIdx === 0 ? 'checked' : '' }}>
                                                            <span class="truncate">{{ $choice }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Menu Tanpa Varian: Deskripsi singkat maks 2 baris -->
                                        <p class="menu-card-desc">
                                            {{ $item['short_info'] ?? 'Sajian autentik barista pilihan Nucomu Cafe.' }}
                                        </p>
                                    @endif

                                    @if($txSettings['is_any_mode_active'] ?? true)
                                        @if($isAvailable)
                                            <button type="button" 
                                                    onclick="submitMenuCardOrder(this.closest('.menu-card'), '{{ addslashes($item['name']) }}', '{{ $item['price_formatted'] }}')"
                                                    class="btn-add-cart"
                                                    aria-label="Tambah {{ $item['name'] }} ke Keranjang">
                                                <span class="material-symbols-outlined text-sm">shopping_cart</span>
                                                <span>+ Add to Cart</span>
                                            </button>
                                        @else
                                            <button type="button" disabled class="btn-add-cart disabled">
                                                <span>Menu Habis</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </article>
                    </div>
                @endforeach
            </div>

            <!-- Dynamic N-Column Flex Container -->
            <div id="catalog-columns-container" class="menu-columns-container pt-16 pb-16">
                <!-- Distributed dynamically into N columns by script -->
            </div>

            <!-- Inline Distribution Trigger -->
            <script>
                if (typeof window.distributeMenuCards === 'function') {
                    window.distributeMenuCards('catalog-cards-pool', 'catalog-columns-container');
                } else {
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof window.distributeMenuCards === 'function') {
                            window.distributeMenuCards('catalog-cards-pool', 'catalog-columns-container');
                        }
                    });
                }
            </script>

            <!-- Noscript Fallback -->
            <noscript>
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-x-4 sm:gap-x-6 gap-y-24 sm:gap-y-28 pt-16 pb-16">
                    @foreach($filteredMenu as $index => $item)
                        @php
                            $itemSlug = \Illuminate\Support\Str::slug($item['id'] ?? $item['name']);
                            $cat = $item['category'] ?? 'coffee';
                            $unit = $item['unit'] ?? match(strtolower($cat)) {
                                'coffee', 'kopi', 'non-coffee', 'non-kopi', 'beverages' => '/cup',
                                'dessert' => '/slice',
                                'snack', 'snacks', 'makanan', 'meals' => '/porsi',
                                default => '/porsi'
                            };
                            $isAvailable = $item['is_available'] ?? true;
                        @endphp
                        <div class="menu-card">
                            <div class="menu-card-avatar">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="menu-card-name">{{ $item['name'] }}</h3>
                            <div class="menu-card-price-row">
                                <span class="font-editorial-num">{{ $item['price_formatted'] }}</span>
                                <span class="menu-card-unit">{{ $unit }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </noscript>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white border border-stone-200 rounded-2xl max-w-xl mx-auto p-8 shadow-xs my-10">
                <span class="material-symbols-outlined text-stone-400 text-5xl mb-3">coffee</span>
                <h3 class="font-editorial text-2xl font-bold text-neutral-900">Menu Belum Tersedia</h3>
                <p class="font-body text-xs text-neutral-500 mt-1 leading-relaxed">
                    Daftar sajian untuk kategori ini sedang disiapkan oleh barista kami.
                </p>
                <a href="{{ route('menu.all', ['category' => 'all']) }}" class="mt-5 inline-block text-xs font-semibold uppercase tracking-widest text-neutral-900 border-b border-neutral-900 pb-0.5 hover:text-neutral-600 transition-colors">
                    Kembali ke Semua Menu &rarr;
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
