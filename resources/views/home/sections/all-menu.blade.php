@php
    $txSettings = $txSettings ?? (class_exists(\App\Http\Controllers\NucomuController::class) ? (new \App\Http\Controllers\NucomuController())->getTransactionSettings() : []);
    $isAnyOrderModeActive = (bool) ($txSettings['is_any_mode_active'] ?? true);
@endphp

<!-- SECTION 4: ALL MENU (CATALOG WITH HYBRID CATEGORIES & 8 EDITORIAL CARDS) -->
<section class="py-20 lg:py-28 bg-[var(--bg-section-alt)] border-b border-[var(--border)]" id="all-menu-section">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- Header & Category Switcher -->
        <div class="text-center max-w-2xl mx-auto mb-14 reveal-on-scroll">
            <span class="text-[11px] font-semibold uppercase tracking-editorial-widest text-[#B5B5B5] block mb-2">KATALOG LENGKAP</span>
            <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-[#F5F5F5]">Menu Nucomu</h2>
            <p class="font-body text-xs sm:text-sm text-[#B5B5B5] mt-2">
                Koleksi minuman kopi, non-kopi pilihan, dessert, serta sajian snack dari bar dan dapur Nucomu.
            </p>
        </div>

        <!-- Hybrid Category Indicators (Visual + Dynamic Tabs from Admin) -->
        <div class="flex justify-start md:justify-center items-center space-x-4 sm:space-x-8 overflow-x-auto no-scrollbar pb-6 mb-12 border-b border-[var(--border-soft)] reveal-on-scroll">
            
            <button type="button" 
                    onclick="filterAllMenuCategory('all', this)"
                    class="all-menu-cat-btn active-cat flex flex-col items-center group pb-2 border-b-2 border-[#F5F5F5] text-[#F5F5F5] font-bold focus:outline-none transition-all shrink-0">
                <span class="text-xs uppercase tracking-editorial-widest">Semua Menu</span>
            </button>

            @if(isset($dbCategories) && $dbCategories->isNotEmpty())
                @foreach($dbCategories as $catItem)
                    <button type="button" 
                            onclick="filterAllMenuCategory('{{ $catItem->slug }}', this)"
                            class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-[#B5B5B5] hover:text-[#F5F5F5] focus:outline-none transition-all shrink-0">
                        <span class="text-xs uppercase tracking-editorial-widest">{{ $catItem->name }}</span>
                    </button>
                @endforeach
            @else
                <button type="button" 
                        onclick="filterAllMenuCategory('coffee', this)"
                        class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-[#B5B5B5] hover:text-[#F5F5F5] focus:outline-none transition-all shrink-0">
                    <span class="text-xs uppercase tracking-editorial-widest">Coffee</span>
                </button>

                <button type="button" 
                        onclick="filterAllMenuCategory('non-coffee', this)"
                        class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-[#B5B5B5] hover:text-[#F5F5F5] focus:outline-none transition-all shrink-0">
                    <span class="text-xs uppercase tracking-editorial-widest">Non-Coffee</span>
                </button>

                <button type="button" 
                        onclick="filterAllMenuCategory('dessert', this)"
                        class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-[#B5B5B5] hover:text-[#F5F5F5] focus:outline-none transition-all shrink-0">
                    <span class="text-xs uppercase tracking-editorial-widest">Dessert</span>
                </button>

                <button type="button" 
                        onclick="filterAllMenuCategory('snacks', this)"
                        class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-[#B5B5B5] hover:text-[#F5F5F5] focus:outline-none transition-all shrink-0">
                    <span class="text-xs uppercase tracking-editorial-widest">Food & Snacks</span>
                </button>
            @endif

        </div>

        @if(! $isAnyOrderModeActive)
            <div class="max-w-2xl mx-auto mb-8 p-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] text-[#B5B5B5] text-center text-xs sm:text-sm reveal-on-scroll">
                <i class="fa-solid fa-circle-info mr-1.5 text-[#F5F5F5]"></i>
                Pemesanan online sedang tidak tersedia saat ini. Silakan berkunjung langsung ke kafe kami.
            </div>
        @endif

        <!-- 8 Product Editorial Grid (Clean, concise info, name and price, no long description on cards) -->
        <div id="all-menu-grid" class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 transition-opacity duration-300">
            @foreach(array_slice($allMenu, 0, 8) as $item)
                <div class="catalog-card-item nu-glass-card rounded-2xl p-4 flex flex-col justify-between transition-all duration-300 group"
                     data-category="{{ $item['category'] }}">
                    <div>
                        <!-- Photo Focus -->
                        <div class="relative overflow-hidden aspect-[4/3] rounded-lg mb-3.5 bg-[#141414] img-editorial border border-[var(--border-soft)]">
                            <img src="{{ $item['image'] }}" 
                                 alt="{{ $item['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-2 left-2 bg-black/75 backdrop-blur-sm px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-[#F5F5F5] border border-[var(--border-soft)] rounded">
                                {{ $item['category_label'] }}
                            </span>
                        </div>

                        <!-- Info: Name, Short Note, Price -->
                        <h4 class="font-editorial text-lg sm:text-xl font-bold text-[#F5F5F5] leading-snug group-hover:underline underline-offset-2">
                            {{ $item['name'] }}
                        </h4>
                        <p class="font-body text-[11px] text-[#B5B5B5] mt-1 line-clamp-1">
                            {{ $item['short_info'] }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-[var(--border-soft)] flex items-center justify-between mt-3">
                        <span class="font-editorial text-base sm:text-lg font-bold text-[#F5F5F5] tabular-nums">{{ $item['price_formatted'] }}</span>
                        @if($isAnyOrderModeActive)
                            <button type="button" 
                                    onclick="addToCart('{{ $item['id'] }}', '{{ addslashes($item['name']) }}', {{ $item['price'] }}, null, '{{ $item['image'] }}')" 
                                    class="w-8 h-8 rounded-full bg-[#F5F2EE] text-[#111111] hover:bg-white active:scale-95 flex items-center justify-center text-xs transition-all cursor-pointer shadow-xs"
                                    aria-label="Tambah {{ $item['name'] }} ke Keranjang"
                                    title="Tambah ke Keranjang">
                                <span class="material-symbols-outlined text-sm">add</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Elegant Empty State Fallback -->
        <div id="all-menu-empty-state" class="hidden text-center py-12 nu-glass-card rounded-2xl mt-6">
            <span class="material-symbols-outlined text-[#B5B5B5] text-4xl mb-2">restaurant_menu</span>
            <p class="font-editorial text-xl font-bold text-[#F5F5F5]">Menu Sedang Disiapkan</p>
            <p class="font-body text-xs text-[#B5B5B5] mt-1">Daftar sajian untuk kategori ini sedang diperbarui bersama barista kami.</p>
        </div>

        <!-- Catalog CTA: Lihat Semua Menu (Dedicated Full Page Route) -->
        <div class="mt-14 text-center reveal-on-scroll">
            <a href="{{ route('menu.all') }}" class="inline-flex items-center space-x-2 border-b-2 border-[#F5F5F5] pb-1 text-[#F5F5F5] text-xs font-semibold uppercase tracking-editorial-widest hover:text-[#B5B5B5] hover:border-[#B5B5B5] transition-colors">
                <span>Lihat Semua Menu (Katalog Lengkap)</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

    </div>
</section>
