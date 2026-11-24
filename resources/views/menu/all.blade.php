@extends('layouts.app')

@section('title', 'Katalog Lengkap Menu — Nucomu Cafe Tulungagung')
@section('meta_description', 'Jelajahi seluruh daftar menu minuman kopi, non-kopi, dessert artisanal, dan makanan ringan di Nucomu Cafe Tulungagung.')

@section('content')
<div id="catalog-menu-page" class="pt-28 pb-24 bg-[#FFFFFF] min-h-screen border-b border-[#E5E4E0] relative overflow-x-clip">

    <style>
        #catalog-menu-page {
            --menu-ink: #191919;
            --menu-charcoal: #30302D;
            --menu-ivory: #FFFFFF;
            --menu-white: #FFFFFF;
            --menu-border: #E5E4E0;
            --menu-muted: #8A8A86;
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
        }
        #catalog-menu-page :focus-visible {
            outline: 2px solid var(--menu-ink);
            outline-offset: 3px;
        }
        #catalog-menu-page .nucomu-sans,
        #catalog-menu-page .font-body,
        #catalog-menu-page .option-group-title,
        #catalog-menu-page .radio-pill-label,
        #catalog-menu-page .menu-card-desc,
        #catalog-menu-page .btn-add-cart {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
        }
        #catalog-menu-page .nucomu-catalog-kicker {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.28em;
            color: var(--menu-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding-left: 0.28em;
        }
        #catalog-menu-page .nucomu-catalog-kicker::before,
        #catalog-menu-page .nucomu-catalog-kicker::after {
            content: '';
            width: 24px;
            height: 1px;
            background-color: #D2D0CA;
        }
        #catalog-menu-page .nucomu-catalog-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(40px, 5vw, 58px);
            font-weight: 600;
            line-height: 1.06;
            letter-spacing: -0.02em;
            color: var(--menu-ink);
        }
        #catalog-menu-page .menu-columns-container {
            gap: 16px;
        }
        @media (min-width: 640px) {
            #catalog-menu-page .menu-columns-container {
                gap: 24px;
            }
        }
        @media (min-width: 1024px) {
            #catalog-menu-page .menu-columns-container {
                gap: 28px;
            }
        }
        #catalog-menu-page .menu-column {
            gap: 92px;
        }
        @media (min-width: 640px) {
            #catalog-menu-page .menu-column {
                gap: 108px;
            }
        }
        #catalog-menu-page .menu-card {
            background-color: var(--menu-white);
            border: 1px solid var(--menu-border);
            border-radius: 20px;
            box-shadow:
                0 2px 10px -2px rgba(25, 25, 25, 0.04),
                0 12px 28px -10px rgba(25, 25, 25, 0.08);
            padding: 50px 14px 16px 14px;
            transition:
                transform 280ms cubic-bezier(0.16, 1, 0.3, 1),
                border-color 280ms ease,
                box-shadow 280ms ease;
        }
        @media (min-width: 640px) {
            #catalog-menu-page .menu-card {
                border-radius: 24px;
                padding: 62px 16px 18px 16px;
            }
        }
        @media (hover: hover) and (pointer: fine) {
            #catalog-menu-page .menu-card:not(.is-open):hover {
                transform: translateY(-3px);
                border-color: rgba(25, 25, 25, 0.22);
                box-shadow:
                    0 8px 18px -4px rgba(25, 25, 25, 0.06),
                    0 20px 36px -10px rgba(25, 25, 25, 0.12);
            }
        }
        #catalog-menu-page .menu-card.is-open {
            border-color: rgba(25, 25, 25, 0.24) !important;
            box-shadow:
                0 10px 22px -4px rgba(25, 25, 25, 0.07),
                0 24px 44px -10px rgba(25, 25, 25, 0.14) !important;
        }
        #catalog-menu-page .menu-card-avatar {
            top: -42px;
            width: 84px;
            height: 84px;
            border: 3px solid var(--menu-white);
            background-color: var(--menu-white);
            box-shadow:
                0 0 0 1px rgba(25, 25, 25, 0.06),
                0 6px 16px rgba(25, 25, 25, 0.10),
                0 12px 24px -6px rgba(25, 25, 25, 0.12);
        }
        @media (min-width: 640px) {
            #catalog-menu-page .menu-card-avatar {
                top: -58px;
                width: 114px;
                height: 114px;
                border-width: 4px;
            }
        }
        #catalog-menu-page .menu-card-name {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.28;
            letter-spacing: -0.01em;
            color: var(--menu-ink);
            min-height: 2.65rem;
            margin-bottom: 4px;
        }
        #catalog-menu-page .menu-card-price-row,
        #catalog-menu-page .menu-card-price-row .font-editorial-num {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 15.5px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--menu-ink);
            gap: 4px;
            font-variant-numeric: tabular-nums lining-nums;
        }
        @media (min-width: 1024px) {
            #catalog-menu-page .menu-card-price-row,
            #catalog-menu-page .menu-card-price-row .font-editorial-num {
                font-size: 16.5px;
            }
        }
        #catalog-menu-page .menu-card-unit {
            font-family: 'DM Sans', 'Manrope', system-ui, -apple-system, sans-serif;
            font-size: 12.5px;
            font-weight: 400;
            color: var(--menu-muted);
        }
        #catalog-menu-page .menu-card-divider {
            background-color: var(--menu-border);
        }
        #catalog-menu-page .option-group-title {
            color: var(--menu-ink);
            font-weight: 700;
            letter-spacing: 0.08em;
        }
        #catalog-menu-page .radio-pill-label {
            color: var(--menu-charcoal);
        }
        #catalog-menu-page .radio-pill-label:hover {
            color: var(--menu-ink);
        }
        #catalog-menu-page .radio-pill-label input[type="radio"] {
            accent-color: var(--menu-ink);
        }
        #catalog-menu-page .menu-card-desc {
            color: var(--menu-muted);
        }
        #catalog-menu-page .btn-add-cart {
            background-color: var(--menu-ink);
            color: var(--menu-white);
            box-shadow: 0 6px 16px -4px rgba(25, 25, 25, 0.22);
        }
        #catalog-menu-page .btn-add-cart:hover {
            background-color: var(--menu-charcoal);
            box-shadow: 0 10px 22px -4px rgba(25, 25, 25, 0.28);
        }
        #catalog-menu-page .btn-add-cart.disabled {
            background-color: var(--menu-muted) !important;
        }
        @media (prefers-reduced-motion: reduce) {
            #catalog-menu-page .menu-card,
            #catalog-menu-page .menu-card-avatar {
                transition: none !important;
                transform: none !important;
            }
            #catalog-menu-page .menu-card:hover .menu-card-avatar,
            #catalog-menu-page .menu-card.is-open .menu-card-avatar {
                transform: translateX(-50%) !important;
            }
        }
    </style>

    <div class="relative z-10 max-w-7xl xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs & Back Link -->
        <div class="mb-6">
            <a href="{{ route('home') }}#menu" class="nucomu-sans group inline-flex items-center space-x-2 text-xs uppercase tracking-[0.2em] font-semibold text-[#8A8A86] hover:text-[#191919] transition-colors">
                <span class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:-translate-x-0.5">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 flex flex-col items-center">
            <span class="nucomu-catalog-kicker mb-3">KATALOG LENGKAP MENU</span>
            <h1 class="nucomu-catalog-title mx-auto">Katalog Sajian Nucomu</h1>
            <svg class="w-24 sm:w-28 h-2.5 mt-2 text-[#191919]/30" viewBox="0 0 120 10" fill="none" aria-hidden="true">
                <path d="M4 7.5C36 2 84 2 116 7.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"/>
            </svg>
            <p class="nucomu-sans text-[13px] sm:text-sm text-[#8A8A86] mt-4 leading-relaxed max-w-2xl mx-auto">
                Koleksi racikan kopi single origin Sendang Tulungagung, seduhan daun teh bergamot, ceremonial matcha, dan sajian hangat dapur Nucomu.
            </p>
        </div>

        <!-- 1. Baris Kategori Dinamis dari Database (Editorial Pills) -->
        <div class="flex items-center justify-center flex-wrap gap-2.5 sm:gap-3 mb-10 pb-6 border-b border-[#E5E4E0]" role="tablist">
            @php
                $activeCategories = $dbCategories->filter(function($c) {
                    return (bool) $c->is_active;
                });
            @endphp

            <!-- Tab Semua Menu -->
            <a href="{{ route('menu.all', ['category' => 'all']) }}" 
               role="tab"
               aria-selected="{{ $selectedCategory === 'all' ? 'true' : 'false' }}"
               class="nucomu-sans inline-flex items-center justify-center rounded-full px-5 py-2 text-[12.5px] sm:text-[13px] font-semibold transition-all duration-200 border {{ $selectedCategory === 'all' ? 'bg-[#191919] text-[#FFFFFF] border-[#191919] shadow-[0_8px_20px_-6px_rgba(25,25,25,0.28)]' : 'bg-[#FFFFFF] text-[#30302D] border-[#E5E4E0] hover:border-[#191919]/35 hover:text-[#191919]' }}">
                Semua Menu
            </a>

            <!-- Tab Kategori dari Database (Urutan Admin) -->
            @foreach($activeCategories as $catItem)
                @php
                    $isCurrentCatActive = ($selectedCategory === $catItem->slug);
                @endphp
                <a href="{{ route('menu.all', ['category' => $catItem->slug]) }}" 
                   role="tab"
                   aria-selected="{{ $isCurrentCatActive ? 'true' : 'false' }}"
                   class="nucomu-sans inline-flex items-center justify-center rounded-full px-5 py-2 text-[12.5px] sm:text-[13px] font-semibold transition-all duration-200 border {{ $isCurrentCatActive ? 'bg-[#191919] text-[#FFFFFF] border-[#191919] shadow-[0_8px_20px_-6px_rgba(25,25,25,0.28)]' : 'bg-[#FFFFFF] text-[#30302D] border-[#E5E4E0] hover:border-[#191919]/35 hover:text-[#191919]' }}">
                    {{ $catItem->name }}
                </a>
            @endforeach
        </div>

        @if(! ($txSettings['is_any_mode_active'] ?? true))
            <div class="nucomu-sans max-w-2xl mx-auto mb-10 p-4 rounded-2xl bg-[#FFFFFF] border border-[#E5E4E0] text-[#30302D] text-center text-xs sm:text-sm shadow-[0_4px_16px_-6px_rgba(25,25,25,0.06)]">
                <i class="fa-solid fa-circle-info mr-1.5 text-[#191919]"></i>
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
            <div class="text-center py-16 bg-[#FFFFFF] border border-[#E5E4E0] rounded-2xl max-w-xl mx-auto p-8 shadow-[0_8px_24px_-8px_rgba(25,25,25,0.06)] my-10">
                <span class="material-symbols-outlined text-[#8A8A86] text-5xl mb-3">coffee</span>
                <h3 class="nucomu-catalog-title text-2xl sm:text-3xl font-semibold text-[#191919]" style="font-size: clamp(24px, 2.5vw, 30px);">Menu Belum Tersedia</h3>
                <p class="nucomu-sans text-xs sm:text-[13px] text-[#8A8A86] mt-1.5 leading-relaxed">
                    Daftar sajian untuk kategori ini sedang disiapkan oleh barista kami.
                </p>
                <a href="{{ route('menu.all', ['category' => 'all']) }}" class="nucomu-sans mt-5 inline-flex items-center gap-1.5 rounded-full bg-[#191919] hover:bg-[#30302D] text-[#FFFFFF] px-5 py-2 text-xs font-semibold transition-colors">
                    <span>Kembali ke Semua Menu</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
