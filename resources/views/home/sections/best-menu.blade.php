<!-- SECTION 3: MENU SHOWCASE (BEST MENU HERO SWITCHER & MENU CATEGORIES) -->
<section id="menu" class="py-16 sm:py-20 lg:py-28 bg-[#FAF8F5] border-b border-light-gray/60 relative">
    
    <!-- =========================================================================
         PART 0: BEST MENU INTERACTIVE SHOWCASE (MATCHING USER REFERENCE DESIGN)
         Before Categories, interactive dish switcher with right-side pills
         ========================================================================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20 lg:mb-28">
        
        <!-- Section Title & Divider -->
        <div class="text-center max-w-2xl mx-auto mb-10 reveal-on-scroll">
            <span class="text-xs uppercase tracking-widest font-bold text-[#E06D24] block mb-2">NUCOMU SIGNATURE</span>
            <h2 class="font-editorial text-3xl sm:text-5xl font-bold tracking-tight text-neutral-900 uppercase">
                BEST MENU
            </h2>
            <div class="flex items-center justify-center space-x-3 mt-3">
                <span class="w-12 h-px bg-neutral-300"></span>
                <span class="text-[#E06D24] text-sm">♔</span>
                <span class="w-12 h-px bg-neutral-300"></span>
            </div>
            <p class="font-body text-xs sm:text-sm text-neutral-500 mt-2">
                Pilih menu di sisi kanan untuk melihat sajian unggulan kopi & dessert artisanal kami.
            </p>
        </div>

        <!-- The Reference Showcase Card (Clean White, No Top Navbar, Responsive 3-Column Layout) -->
        <div class="relative bg-white rounded-3xl sm:rounded-[2.5rem] shadow-xl border border-stone-150 p-6 sm:p-10 lg:p-14 overflow-hidden">
            
            <!-- Faded Watermark in Background -->
            <div class="absolute -left-6 top-1/2 -translate-y-1/2 select-none pointer-events-none opacity-[0.04] font-editorial font-extrabold text-[120px] sm:text-[180px] lg:text-[220px] leading-none text-neutral-900 z-0 tracking-tighter">
                FOOD<br>ZONE
            </div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- 1. LEFT COLUMN: Bold Typography & Menu Story (~4 cols) -->
                <div class="lg:col-span-4 flex flex-col justify-center text-left">
                    <span class="text-[11px] uppercase tracking-widest font-bold text-[#E06D24] block mb-2">
                        BEST CHOICE
                    </span>
                    <h3 id="best-hero-title" class="font-editorial text-4xl sm:text-5xl lg:text-6xl font-bold text-neutral-900 leading-[1.05] tracking-tight transition-all duration-300">
                        {!! $bestMenu[0]['display_title'] ?? $bestMenu[0]['name'] !!}
                    </h3>

                    <div class="flex items-center space-x-3 my-4">
                        <span class="w-10 h-0.5 bg-neutral-900"></span>
                        <span id="best-hero-category" class="text-xs font-bold uppercase tracking-wider text-neutral-600">
                            {{ $bestMenu[0]['category_label'] }}
                        </span>
                    </div>

                    <p id="best-hero-desc" class="font-body text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-sm transition-all duration-300 min-h-[3rem]">
                        {{ $bestMenu[0]['description'] }}
                    </p>
                </div>

                <!-- 2. CENTER COLUMN: Giant Plate, Order Pill, and Slide Dots (~5 cols) -->
                <div class="lg:col-span-5 flex flex-col items-center justify-center my-4 lg:my-0">
                    <!-- Big Round Plate with Soft Shadow -->
                    <div id="best-hero-dish-wrapper" class="relative w-64 h-64 sm:w-72 sm:h-72 md:w-80 md:h-80 rounded-full p-2.5 bg-white shadow-2xl transition-all duration-500 ease-out border border-stone-100 flex items-center justify-center">
                        <img id="best-hero-img" 
                             src="{{ $bestMenu[0]['image'] }}" 
                             alt="{{ $bestMenu[0]['name'] }}" 
                             class="w-full h-full object-cover rounded-full shadow-inner transition-all duration-500">
                    </div>

                    <!-- Orange Pill Order Button Underneath -->
                    <div class="mt-6 sm:-mt-5 relative z-20">
                        <button id="best-hero-order-btn" 
                                type="button"
                                onclick="openReservationModal('Pesanan Best Menu: {{ $bestMenu[0]['name'] }}')" 
                                class="px-7 sm:px-8 py-3 rounded-full bg-[#E06D24] hover:bg-[#c95b17] text-white font-bold text-xs sm:text-sm uppercase tracking-wider flex items-center space-x-2.5 shadow-lg shadow-orange-500/25 transition-all duration-200 hover:scale-105 active:scale-95">
                            <span id="best-hero-price">{{ $bestMenu[0]['price_formatted'] }}</span>
                            <span>ORDER</span>
                            <span class="material-symbols-outlined text-base">chevron_right</span>
                        </button>
                    </div>

                    <!-- Slide Dots Indicator -->
                    <div class="flex items-center space-x-2 mt-4 sm:mt-6" id="best-dots-container">
                        @foreach($bestMenu as $idx => $m)
                            <button type="button" 
                                    onclick="switchBestMenuItem({{ $idx }})" 
                                    class="best-menu-dot {{ $idx === 0 ? 'w-6 h-2 bg-[#E06D24]' : 'w-2 h-2 bg-neutral-300 hover:bg-neutral-400' }} rounded-full transition-all duration-300"
                                    aria-label="Menu {{ $idx + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 3. RIGHT COLUMN: Interactive Vertical Stack of Pills (~3 cols) -->
                <div class="lg:col-span-3 flex flex-col space-y-3.5 lg:-mr-14">
                    @foreach($bestMenu as $idx => $m)
                        <div onclick="switchBestMenuItem({{ $idx }})" 
                             class="best-menu-pill {{ $idx === 0 ? 'active bg-[#E06D24] text-white shadow-lg shadow-orange-500/20' : 'bg-white text-neutral-800 shadow-md hover:shadow-lg hover:-translate-x-1 border border-stone-100' }} flex items-center justify-between p-3 pl-3.5 sm:pl-5 rounded-2xl sm:rounded-l-full transition-all duration-300 cursor-pointer group">
                            
                            <!-- Left: Thumbnail in white circle -->
                            <div class="w-12 h-12 rounded-full overflow-hidden p-0.5 bg-white border border-stone-200 shadow-sm shrink-0">
                                <img src="{{ $m['image'] }}" 
                                     alt="{{ $m['name'] }}" 
                                     class="w-full h-full object-cover rounded-full group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Right Info: Title & Price -->
                            <div class="flex-1 ml-3.5 mr-2 text-left">
                                <span class="pill-title text-[11px] sm:text-xs font-bold uppercase tracking-wider block leading-tight {{ $idx === 0 ? 'text-white' : 'text-neutral-800' }}">
                                    {{ $m['name'] }}
                                </span>
                                <span class="pill-price text-xs sm:text-sm font-bold block mt-0.5 {{ $idx === 0 ? 'text-white' : 'text-[#E06D24]' }}">
                                    {{ $m['price_formatted'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <!-- Data Injection for Instant Reactive Switching -->
    <script>
        window.nucomuBestMenuItems = @json($bestMenu);
    </script>


    <!-- =========================================================================
         PART 1: OUR MENU CATEGORIES (6 Circular Thumbnails with Hover Zoom)
         ========================================================================= -->
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 pt-8">
        <div class="text-center max-w-2xl mx-auto mb-10 reveal-on-scroll">
            <h2 class="font-editorial text-2xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-ink-black uppercase">
                OUR MENU CATEGORIES
            </h2>
            
            <!-- Ornate Crown / Floral Motif Divider -->
            <div class="flex items-center justify-center space-x-3 mt-3">
                <span class="w-12 h-px bg-neutral-300"></span>
                <span class="text-[#E06D24] text-sm">♔</span>
                <span class="w-12 h-px bg-neutral-300"></span>
            </div>
        </div>

        <!-- 6 Circular Category Items (Direct Filter Controls) -->
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-4 sm:gap-6 max-w-5xl mx-auto mb-8 reveal-on-scroll">
            @foreach($menuCategories as $cat)
                <button type="button" 
                        onclick="handleCircularCategoryClick('{{ $cat['id'] }}', this)" 
                        class="cat-circle-btn group flex flex-col items-center focus:outline-none transition-transform hover:-translate-y-1"
                        data-cat-id="{{ $cat['id'] }}">
                    <div class="cat-circle-img-wrap w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden p-1 bg-white border border-neutral-200/90 shadow-sm group-hover:shadow-md group-hover:border-[#E06D24] transition-all duration-300">
                        <img src="{{ $cat['image'] }}" 
                             alt="{{ $cat['name'] }}" 
                             class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <span class="cat-circle-label mt-3 text-xs sm:text-sm font-semibold text-neutral-800 tracking-wide text-center group-hover:text-[#E06D24] transition-colors">
                        {{ $cat['name'] }}
                    </span>
                </button>
            @endforeach
        </div>

        <!-- SEKAT AESTHETIC ANTARA KATEGORI & MENU -->
        <div class="relative max-w-5xl mx-auto my-10 sm:my-12 flex items-center justify-center reveal-on-scroll">
            <div class="flex-grow h-px bg-gradient-to-r from-transparent via-[#54392B]/20 to-[#54392B]/30"></div>
            <div class="mx-4 sm:mx-6 flex items-center space-x-2.5 px-4 sm:px-5 py-1.5 rounded-full bg-white border border-stone-200/90 shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                <span class="material-symbols-outlined text-sm text-[#E06D24]">local_cafe</span>
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                    Daftar Sajian Pilihan
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
            </div>
            <div class="flex-grow h-px bg-gradient-to-l from-transparent via-[#54392B]/20 to-[#54392B]/30"></div>
        </div>

        <!-- 8 Menu Cards Grid (Clean & Direct, Text Header & Duplicate Tabs Removed) -->
        <div id="recommendations-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 transition-all duration-300 mb-12">
            @foreach($recommendations as $item)
                <div class="rec-card bg-white rounded-2xl shadow-sm border border-stone-200/80 overflow-hidden hover:shadow-xl hover:border-[#E06D24]/30 transition-all duration-300 flex flex-col justify-between group"
                     data-category="{{ $item['category'] }}">
                    <div>
                        <!-- Card Image with Top Badge -->
                        <div class="relative overflow-hidden aspect-[4/3] bg-warm-ivory">
                            <img src="{{ $item['image'] }}" 
                                 alt="{{ $item['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            @if(isset($item['badge']) && $item['badge'])
                                <span class="absolute top-3 left-3 {{ $item['badge_color'] ?? 'bg-[#E06D24]' }} text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md shadow-sm">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 sm:p-5">
                            <h4 class="font-editorial text-lg sm:text-xl font-bold text-[#2A1D17] group-hover:text-[#E06D24] transition-colors line-clamp-1">
                                {{ $item['name'] }}
                            </h4>
                            
                            <p class="text-xs text-[#5C4E45] font-normal mt-1 line-clamp-2 leading-relaxed min-h-[2.5rem]">
                                {{ $item['short_info'] }}
                            </p>
                            
                            <!-- Star Rating and Price Row -->
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-stone-100">
                                <!-- Amber Rating Stars -->
                                <div class="flex items-center space-x-1">
                                    <span class="text-amber-400 text-xs tracking-tighter">★★★★★</span>
                                    <span class="text-[11px] font-semibold text-neutral-500">({{ $item['rating'] }})</span>
                                </div>
                                
                                <!-- Price -->
                                <span class="text-sm sm:text-base font-bold text-[#2A1D17] font-editorial">
                                    {{ $item['price_formatted'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="px-4 pb-4 sm:px-5 sm:pb-5 pt-0">
                        <button type="button" 
                                onclick="openReservationModal('Pesanan Menu: {{ $item['name'] }}')" 
                                class="w-full py-2.5 px-3 rounded-lg bg-[#2A1D17] hover:bg-[#E06D24] text-white text-xs font-bold uppercase tracking-wider transition-colors duration-200 flex items-center justify-center space-x-1.5 shadow-sm active:scale-95">
                            <span>Pesan / Order</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State Fallback -->
        <div id="recommendations-empty-state" class="hidden text-center py-12 bg-white rounded-2xl border border-neutral-100 mb-12 p-8">
            <span class="material-symbols-outlined text-neutral-400 text-4xl mb-2">restaurant_menu</span>
            <p class="font-editorial text-xl font-bold text-neutral-900">Sajian Sedang Disiapkan</p>
            <p class="font-body text-xs text-neutral-500 mt-1">Daftar sajian untuk kategori ini sedang diperbarui bersama barista kami.</p>
        </div>

        <!-- View Full Catalog Link -->
        <div class="text-center reveal-on-scroll">
            <a href="{{ route('menu.all') }}" class="inline-flex items-center space-x-2.5 px-7 py-3 rounded-full bg-[#2A1D17] hover:bg-[#E06D24] text-white text-xs font-bold uppercase tracking-widest shadow-md transition-all duration-200 active:scale-95">
                <span>Lihat Semua Menu (Katalog Lengkap)</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
