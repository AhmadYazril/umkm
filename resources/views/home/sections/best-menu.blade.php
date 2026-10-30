<!-- SECTION 3: MENU SHOWCASE (BEST MENU HERO SWITCHER & MENU CATEGORIES) -->
<section id="menu" class="pt-16 sm:pt-20 lg:pt-28 pb-6 sm:pb-8 lg:pb-10 bg-[#FAF8F5] border-b border-light-gray/60 relative">
    
    <!-- =========================================================================
         PART 0: BEST MENU INTERACTIVE SHOWCASE (MATCHING USER REFERENCE DESIGN)
         Monochrome Palette: Abu-abu, Putih, Hitam
         ========================================================================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20 lg:mb-28">
        
        <!-- Section Title & Subtitle (Clean Editorial Serif, No Orange Badge) -->
        <div class="text-center max-w-2xl mx-auto mb-10 reveal-on-scroll">
            <h2 class="font-editorial text-4xl sm:text-5xl font-bold tracking-tight text-neutral-900 uppercase">
                BEST MENU
            </h2>
            <p class="font-body text-xs sm:text-sm text-neutral-500 mt-2">
                Explore our curated selection of fine artisanal coffees and gourmet delights.
            </p>
        </div>

        <!-- The Reference Showcase Card (Clean White, Responsive 3-Column Layout) -->
        <div class="relative bg-white rounded-3xl sm:rounded-[2.5rem] shadow-xl border border-stone-150 p-6 sm:p-10 lg:p-14 overflow-hidden">
            
            <!-- Faded Watermark in Background -->
            <div class="absolute -left-6 top-1/2 -translate-y-1/2 select-none pointer-events-none opacity-[0.04] font-editorial font-extrabold text-[120px] sm:text-[180px] lg:text-[220px] leading-none text-neutral-900 z-0 tracking-tighter">
                FOOD<br>ZONE
            </div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- 1. LEFT COLUMN: Bold Typography & Menu Story (~4 cols) -->
                <div class="lg:col-span-4 flex flex-col justify-center text-left">
                    <span class="text-[11px] uppercase tracking-widest font-semibold text-neutral-400 block mb-3">
                        FOOD ZONE
                    </span>
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="text-[11px] uppercase tracking-widest font-bold text-neutral-900">FEATURED</span>
                        <span class="material-symbols-outlined text-sm text-neutral-400 font-light">restaurant</span>
                    </div>
                    <h3 id="best-hero-title" class="font-editorial text-4xl sm:text-5xl lg:text-6xl font-bold text-neutral-900 leading-[1.05] tracking-tight transition-all duration-300">
                        {!! $bestMenu[0]['display_title'] ?? $bestMenu[0]['name'] !!}
                    </h3>

                    <div class="flex items-center space-x-2.5 my-3.5">
                        <span class="w-6 h-[1.5px] bg-neutral-900"></span>
                        <span id="best-hero-category" class="text-xs font-medium text-neutral-700">
                            {{ $bestMenu[0]['category_label'] }}
                        </span>
                    </div>

                    <p id="best-hero-desc" class="font-body text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-sm transition-all duration-300 min-h-[3rem]">
                        {{ $bestMenu[0]['description'] }}
                    </p>

                    <div class="mt-8 flex items-center space-x-2 select-none opacity-40">
                        <span class="material-symbols-outlined text-sm text-neutral-800">verified</span>
                        <span class="text-[9px] font-bold tracking-[0.25em] uppercase text-neutral-800">NUCOMU</span>
                    </div>
                </div>

                <!-- 2. CENTER COLUMN: Giant Plate, Gray Taupe Order Pill, and Slide Dots (~5 cols) -->
                <div class="lg:col-span-5 flex flex-col items-center justify-center my-4 lg:my-0">
                    <!-- Big Round Plate with Soft Shadow -->
                    <div id="best-hero-dish-wrapper" class="relative w-64 h-64 sm:w-72 sm:h-72 md:w-80 md:h-80 rounded-full p-2.5 bg-white shadow-2xl transition-all duration-500 ease-out border border-stone-100 flex items-center justify-center">
                        <img id="best-hero-img" 
                             src="{{ $bestMenu[0]['image'] }}" 
                             alt="{{ $bestMenu[0]['name'] }}" 
                             class="w-full h-full object-cover rounded-full shadow-inner transition-all duration-500">
                    </div>

                    <!-- Gray Taupe Pill Order Button Underneath (Matching User Screenshot) -->
                    <div class="mt-6 sm:-mt-5 relative z-20">
                        <button id="best-hero-order-btn" 
                                type="button"
                                onclick="orderCurrentBestMenuItem()" 
                                class="px-7 sm:px-8 py-2.5 rounded-full bg-[#B5ACA5] hover:bg-[#9E958E] text-white font-medium text-xs sm:text-sm uppercase tracking-wider flex items-center space-x-2 shadow-sm transition-all duration-200 hover:scale-105 active:scale-95 cursor-pointer">
                            <span id="best-hero-price" class="font-bold">{{ $bestMenu[0]['price_formatted'] }}</span>
                            <span class="font-bold">ORDER</span>
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>
                    </div>

                    <!-- Slide Dots Indicator -->
                    <div class="flex items-center space-x-1.5 mt-4 sm:mt-6" id="best-dots-container">
                        @foreach($bestMenu as $idx => $m)
                            <button type="button" 
                                    onclick="switchBestMenuItem({{ $idx }})" 
                                    class="best-menu-dot {{ $idx === 0 ? 'w-5 h-1.5 bg-[#B5ACA5]' : 'w-1.5 h-1.5 bg-neutral-300 hover:bg-neutral-400' }} rounded-full transition-all duration-300"
                                    aria-label="Menu {{ $idx + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 3. RIGHT COLUMN: Interactive Vertical Stack of Pills (~3 cols) -->
                <div class="lg:col-span-3 flex flex-col space-y-3 lg:-mr-14">
                    @foreach($bestMenu as $idx => $m)
                        <div onclick="switchBestMenuItem({{ $idx }})" 
                             class="best-menu-pill {{ $idx === 0 ? 'active bg-[#E5E2DE] text-neutral-900 shadow-xs' : 'bg-white hover:bg-neutral-50 text-neutral-800 border-b border-stone-100/90' }} flex items-center justify-between p-2.5 pl-3 sm:pl-4 rounded-2xl sm:rounded-l-full transition-all duration-300 cursor-pointer group">
                            
                            <!-- Left: Thumbnail in white circle -->
                            <div class="w-11 h-11 rounded-full overflow-hidden p-0.5 bg-white border border-stone-200 shadow-xs shrink-0">
                                <img src="{{ $m['image'] }}" 
                                     alt="{{ $m['name'] }}" 
                                     class="w-full h-full object-cover rounded-full group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Right Info: Title & Price -->
                            <div class="flex-1 ml-3 mr-2 text-left">
                                <span class="pill-title text-[11px] sm:text-xs font-bold uppercase tracking-wider block leading-tight {{ $idx === 0 ? 'text-neutral-900' : 'text-neutral-800' }}">
                                    {{ $m['name'] }}
                                </span>
                                <span class="pill-price text-xs sm:text-sm font-medium block mt-0.5 {{ $idx === 0 ? 'text-neutral-700 font-semibold' : 'text-neutral-500' }}">
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
    <div class="max-w-7xl xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <div class="text-center max-w-2xl mx-auto mb-10 reveal-on-scroll">
            <h2 class="font-editorial text-2xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-neutral-900 uppercase">
                OUR MENU CATEGORIES
            </h2>
            
            <!-- Monochromatic Divider -->
            <div class="flex items-center justify-center space-x-3 mt-3">
                <span class="w-12 h-px bg-neutral-300"></span>
                <span class="text-neutral-400 text-sm">♔</span>
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
                    <div class="cat-circle-img-wrap w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full overflow-hidden p-1 bg-white border border-neutral-200/90 shadow-sm group-hover:shadow-md group-hover:border-neutral-900 transition-all duration-300">
                        <img src="{{ $cat['image'] }}" 
                             alt="{{ $cat['name'] }}" 
                             class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <span class="cat-circle-label mt-3 text-xs sm:text-sm font-semibold text-neutral-700 tracking-wide text-center group-hover:text-neutral-900 transition-colors">
                        {{ $cat['name'] }}
                    </span>
                </button>
            @endforeach
        </div>

        <!-- Sekat Antara Menu Categories & Menu Pilihan -->
        <div class="relative max-w-5xl mx-auto my-10 sm:my-14 flex items-center justify-center">
            <div class="flex-grow h-px bg-gradient-to-r from-transparent via-neutral-300 to-transparent"></div>
            <div class="mx-5 flex items-center space-x-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
                <span class="text-sm text-neutral-400">♔</span>
                <span class="w-1.5 h-1.5 rounded-full bg-neutral-400"></span>
            </div>
            <div class="flex-grow h-px bg-gradient-to-l from-transparent via-neutral-300 to-transparent"></div>
        </div>

        <!-- 10 Menu Cards Grid (1 Baris 5: 5 Columns per Row + Expandable Options / Add to Cart on Click) -->
        <div id="recommendations-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 items-start gap-x-5 gap-y-20 pt-16 transition-all duration-300 mb-2 mt-6">
            @foreach($recommendations as $index => $item)
                @php
                    $hasOptions = !empty($item['options']);
                    $opt1 = $hasOptions ? ($item['options'][0] ?? null) : null;
                    $opt2 = $hasOptions ? ($item['options'][1] ?? null) : null;
                @endphp
                <div class="rec-card popular-dish-card group relative rounded-[2rem] p-4 sm:p-5 pt-16 sm:pt-20 pb-5 bg-white border border-stone-200/80 shadow-md hover:shadow-xl hover:border-neutral-400 transition-all duration-300 cursor-pointer h-fit self-start"
                     data-category="{{ $item['category'] }}"
                     data-item-id="{{ $item['id'] }}"
                     onclick="togglePopularDishOptions(this)">
                    
                    <!-- Floating Circular Plate (Overflows top of card) -->
                    <div class="dish-plate-wrap absolute -top-12 sm:-top-16 left-1/2 -translate-x-1/2 w-28 h-28 sm:w-32 sm:h-32 md:w-36 md:h-36 lg:w-32 lg:h-32 xl:w-36 xl:h-36 rounded-full overflow-hidden p-1.5 bg-white shadow-xl border border-stone-200/90 transition-transform duration-300 group-hover:scale-105 z-10 flex items-center justify-center">
                        <img src="{{ $item['image'] }}" 
                             alt="{{ $item['name'] }}" 
                             class="w-full h-full object-cover rounded-full">
                    </div>

                    <!-- Card Body: Name & Price ONLY ("bentuknya segini aja", add keranjang tidak langsung muncul) -->
                    <div class="text-center pt-2">
                        <h4 class="card-title font-editorial text-base sm:text-lg font-bold leading-snug line-clamp-1 text-neutral-900 group-hover:text-black transition-colors" title="{{ $item['name'] }}">
                            {{ $item['name'] }}
                        </h4>
                        <div class="mt-1 flex items-baseline justify-center space-x-1">
                            <span class="card-price font-editorial text-base sm:text-lg font-bold text-neutral-900">
                                {{ $item['price_formatted'] }}
                            </span>
                            <span class="text-xs text-neutral-400 font-normal">
                                {{ $item['unit'] ?? '/porsi' }}
                            </span>
                        </div>
                    </div>

                    <!-- Expandable Panel ("harus di klik dulu baru muncul") -->
                    <div class="product-options-panel border-stone-150/80"
                         onclick="event.stopPropagation()">
                        
                        @if($hasOptions)
                            <!-- 2 Columns of Options matching reference screenshot -->
                            <div class="grid grid-cols-2 gap-2 mb-3.5 text-left">
                                @if($opt1)
                                <div>
                                    <span class="text-[11px] sm:text-xs font-bold text-neutral-800 block mb-1.5 tracking-tight">
                                        {{ $opt1['title'] }}
                                    </span>
                                    <div class="space-y-1">
                                        @foreach($opt1['choices'] as $cIdx => $choice)
                                            <label class="flex items-center space-x-1.5 text-[10px] sm:text-[11px] text-neutral-600 cursor-pointer hover:text-neutral-900 select-none group">
                                                <input type="radio" 
                                                       name="opt1_{{ $item['id'] }}" 
                                                       value="{{ $choice }}" 
                                                       {{ $cIdx === 0 ? 'checked' : '' }}
                                                       class="accent-neutral-900 w-3 h-3 cursor-pointer shrink-0">
                                                <span class="group-hover:text-neutral-900 leading-tight truncate">{{ $choice }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($opt2)
                                <div>
                                    <span class="text-[11px] sm:text-xs font-bold text-neutral-800 block mb-1.5 tracking-tight">
                                        {{ $opt2['title'] }}
                                    </span>
                                    <div class="space-y-1">
                                        @foreach($opt2['choices'] as $cIdx => $choice)
                                            <label class="flex items-center space-x-1.5 text-[10px] sm:text-[11px] text-neutral-600 cursor-pointer hover:text-neutral-900 select-none group">
                                                <input type="radio" 
                                                       name="opt2_{{ $item['id'] }}" 
                                                       value="{{ $choice }}" 
                                                       {{ $cIdx === 0 ? 'checked' : '' }}
                                                       class="accent-neutral-900 w-3 h-3 cursor-pointer shrink-0">
                                                <span class="group-hover:text-neutral-900 leading-tight truncate">{{ $choice }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <!-- Menu Tanpa Varian: Keterangan singkat bila ada, tanpa opsi radio -->
                            @if(!empty($item['short_info']))
                                <p class="text-[11px] text-neutral-500 text-center mb-2.5 leading-snug line-clamp-2">
                                    {{ $item['short_info'] }}
                                </p>
                            @endif
                        @endif

                        <!-- Add to Cart Button (Muncul setelah diklik, warna hitam elegan) -->
                        <button type="button" 
                                onclick="event.stopPropagation(); submitPopularDishOrder(this.closest('.popular-dish-card'), '{{ addslashes($item['name']) }}', '{{ $item['price_formatted'] }}')" 
                                class="w-full py-2 px-3 rounded-xl bg-neutral-900 hover:bg-black text-white text-xs sm:text-sm font-bold tracking-wide shadow-md shadow-neutral-900/15 active:scale-95 transition-all flex items-center justify-center space-x-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                            <span>+ Add to Cart</span>
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
    </div>
</section>
