<!-- SECTION 4: ALL MENU (CATALOG WITH HYBRID CATEGORIES & 8 EDITORIAL CARDS) -->
<section class="py-20 lg:py-28 bg-pure-white border-b border-light-gray/60" id="all-menu-section">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- Header & Category Switcher -->
        <div class="text-center max-w-2xl mx-auto mb-14 reveal-on-scroll">
            <span class="text-[11px] font-semibold uppercase tracking-editorial-widest text-medium-gray block mb-2">KATALOG LENGKAP</span>
            <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-ink-black">Menu Nucomu</h2>
            <p class="font-body text-xs sm:text-sm text-charcoal/70 mt-2">
                Koleksi minuman kopi, non-kopi pilihan, dessert, serta sajian snack dari bar dan dapur Nucomu.
            </p>
        </div>

        <!-- Hybrid Category Indicators (Visual + Tabs) -->
        <div class="flex justify-start md:justify-center items-center space-x-4 sm:space-x-8 overflow-x-auto no-scrollbar pb-6 mb-12 border-b border-light-gray/50 reveal-on-scroll">
            
            <button type="button" 
                    onclick="filterAllMenuCategory('all', this)"
                    class="all-menu-cat-btn active-cat flex flex-col items-center group pb-2 border-b-2 border-neutral-900 text-neutral-900 font-bold focus:outline-none transition-all shrink-0">
                <span class="text-xs uppercase tracking-editorial-widest">Semua Menu</span>
            </button>

            <button type="button" 
                    onclick="filterAllMenuCategory('coffee', this)"
                    class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-neutral-500 hover:text-neutral-900 focus:outline-none transition-all shrink-0">
                <span class="text-xs uppercase tracking-editorial-widest">Coffee</span>
            </button>

            <button type="button" 
                    onclick="filterAllMenuCategory('non-coffee', this)"
                    class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-neutral-500 hover:text-neutral-900 focus:outline-none transition-all shrink-0">
                <span class="text-xs uppercase tracking-editorial-widest">Non-Coffee</span>
            </button>

            <button type="button" 
                    onclick="filterAllMenuCategory('dessert', this)"
                    class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-neutral-500 hover:text-neutral-900 focus:outline-none transition-all shrink-0">
                <span class="text-xs uppercase tracking-editorial-widest">Dessert</span>
            </button>

            <button type="button" 
                    onclick="filterAllMenuCategory('snacks', this)"
                    class="all-menu-cat-btn flex flex-col items-center group pb-2 border-b-2 border-transparent text-neutral-500 hover:text-neutral-900 focus:outline-none transition-all shrink-0">
                <span class="text-xs uppercase tracking-editorial-widest">Food & Snacks</span>
            </button>

        </div>

        <!-- 8 Product Editorial Grid (Clean, concise info, name and price, no long description on cards) -->
        <div id="all-menu-grid" class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 transition-opacity duration-300">
            @foreach(array_slice($allMenu, 0, 8) as $item)
                <div class="catalog-card-item bg-warm-ivory/40 border border-soft-beige rounded p-4 flex flex-col justify-between hover:border-ink-black transition-all duration-300 group"
                     data-category="{{ $item['category'] }}">
                    <div>
                        <!-- Photo Focus -->
                        <div class="relative overflow-hidden aspect-[4/3] rounded-sm mb-3.5 bg-warm-ivory img-editorial border border-light-gray/60">
                            <img src="{{ $item['image'] }}" 
                                 alt="{{ $item['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-2 left-2 bg-warm-ivory/90 backdrop-blur-sm px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-charcoal rounded">
                                {{ $item['category_label'] }}
                            </span>
                        </div>

                        <!-- Info: Name, Short Note, Price -->
                        <h4 class="font-editorial text-lg sm:text-xl font-bold text-ink-black leading-snug group-hover:underline underline-offset-2">
                            {{ $item['name'] }}
                        </h4>
                        <p class="font-body text-[11px] text-medium-gray mt-1 line-clamp-1">
                            {{ $item['short_info'] }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-light-gray/60 flex items-center justify-between mt-3">
                        <span class="font-editorial text-base sm:text-lg font-bold text-ink-black">{{ $item['price_formatted'] }}</span>
                        <button onclick="openReservationModal('Order Menu: {{ $item['name'] }}')" 
                                class="w-7 h-7 rounded-full bg-ink-black text-warm-ivory flex items-center justify-center text-xs hover:bg-neutral-800 transition-colors"
                                aria-label="Pesan {{ $item['name'] }}">
                            <span class="material-symbols-outlined text-sm">add</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Elegant Empty State Fallback -->
        <div id="all-menu-empty-state" class="hidden text-center py-12 bg-warm-ivory/30 border border-soft-beige rounded mt-6">
            <span class="material-symbols-outlined text-medium-gray text-4xl mb-2">restaurant_menu</span>
            <p class="font-editorial text-xl font-bold text-ink-black">Menu Sedang Disiapkan</p>
            <p class="font-body text-xs text-medium-gray mt-1">Daftar sajian untuk kategori ini sedang diperbarui bersama barista kami.</p>
        </div>

        <!-- Catalog CTA: Lihat Semua Menu (Dedicated Full Page Route) -->
        <div class="mt-14 text-center reveal-on-scroll">
            <a href="{{ route('menu.all') }}" class="inline-flex items-center space-x-2 border-b-2 border-ink-black pb-1 text-ink-black text-xs font-semibold uppercase tracking-editorial-widest hover:text-medium-gray hover:border-medium-gray transition-colors">
                <span>Lihat Semua Menu (Katalog Lengkap)</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

    </div>
</section>
