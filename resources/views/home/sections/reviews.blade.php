<!-- SECTION 6: REVIEWS (WHAT OUR CLIENTS SAY — MATCHING USER REFERENCE DESIGN) -->
<section id="reviews" class="py-16 sm:py-20 lg:py-24 bg-[#FAF8F5] border-b border-light-gray/60">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- Section Header with Highlighted Orange Text -->
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-14 reveal-on-scroll">
            <h2 class="font-editorial text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-neutral-900">
                Apa kata <span class="text-[#E06D24]">pengunjung kami</span>
            </h2>
            <div class="flex items-center justify-center space-x-3 mt-3">
                <span class="w-10 h-px bg-neutral-300"></span>
                <span class="text-[#E06D24] text-xs">★</span>
                <span class="w-10 h-px bg-neutral-300"></span>
            </div>
            <p class="font-body text-xs sm:text-sm text-neutral-500 mt-2">
                Cerita dan pengalaman nyata dari mereka yang telah menikmati kopi, suasana, dan momen santai di Nucomu Cafe.
            </p>
        </div>

        <!-- Review Slides Container -->
        <div class="relative max-w-6xl mx-auto">
            
            <!-- Slide 1 (First 3 Reviews) -->
            <div class="review-slide grid grid-cols-1 md:grid-cols-3 gap-6 transition-all duration-300">
                @foreach(array_slice($reviews, 0, 3) as $rev)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-150 flex items-start space-x-4 relative hover:shadow-md transition-shadow group">
                        <!-- Left: Circular Avatar -->
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden shrink-0 border border-stone-200 bg-stone-100 shadow-sm">
                            <img src="{{ $rev['avatar'] }}" 
                                 alt="{{ $rev['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <!-- Right: Info, Rating & Comment -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm sm:text-base text-neutral-900 leading-snug">
                                {{ $rev['name'] }}
                            </h4>
                            
                            <!-- Star Rating (Amber / Orange) -->
                            <div class="flex items-center space-x-0.5 my-1 text-[#E06D24] text-xs sm:text-sm">
                                @for($i = 0; $i < $rev['rating']; $i++)
                                    <span>★</span>
                                @endfor
                            </div>

                            <p class="text-xs text-neutral-600 leading-relaxed font-normal mt-1.5">
                                {{ $rev['comment'] }}
                            </p>

                            <!-- Orange Quote Icon at bottom -->
                            <span class="text-[#E06D24] text-2xl font-serif font-black leading-none block mt-2 select-none">
                                ”
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Slide 2 (Next 3 Reviews, initially hidden) -->
            <div class="review-slide hidden grid grid-cols-1 md:grid-cols-3 gap-6 transition-all duration-300">
                @foreach(array_slice($reviews, 3, 3) as $rev)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-150 flex items-start space-x-4 relative hover:shadow-md transition-shadow group">
                        <!-- Left: Circular Avatar -->
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden shrink-0 border border-stone-200 bg-stone-100 shadow-sm">
                            <img src="{{ $rev['avatar'] }}" 
                                 alt="{{ $rev['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <!-- Right: Info, Rating & Comment -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm sm:text-base text-neutral-900 leading-snug">
                                {{ $rev['name'] }}
                            </h4>
                            
                            <!-- Star Rating (Amber / Orange) -->
                            <div class="flex items-center space-x-0.5 my-1 text-[#E06D24] text-xs sm:text-sm">
                                @for($i = 0; $i < $rev['rating']; $i++)
                                    <span>★</span>
                                @endfor
                            </div>

                            <p class="text-xs text-neutral-600 leading-relaxed font-normal mt-1.5">
                                {{ $rev['comment'] }}
                            </p>

                            <!-- Orange Quote Icon at bottom -->
                            <span class="text-[#E06D24] text-2xl font-serif font-black leading-none block mt-2 select-none">
                                ”
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dots Indicator Underneath (Matching Reference Design) -->
            <div class="flex items-center justify-center space-x-2 mt-8 sm:mt-10">
                <button type="button" 
                        onclick="switchReviewSlide(0)" 
                        class="review-dot w-6 h-2 bg-[#E06D24] rounded-full transition-all duration-300" 
                        aria-label="Slide 1">
                </button>
                <button type="button" 
                        onclick="switchReviewSlide(1)" 
                        class="review-dot w-2 h-2 bg-neutral-300 hover:bg-neutral-400 rounded-full transition-all duration-300" 
                        aria-label="Slide 2">
                </button>
            </div>

            <!-- Subtle Google Maps Link at bottom -->
            <div class="text-center mt-6">
                <a href="{{ config('nucomu.contact.google_maps_url') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="inline-flex items-center space-x-1.5 text-[11px] font-semibold uppercase tracking-wider text-neutral-500 hover:text-[#E06D24] transition-colors">
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                    <span>Buka Ulasan Resmi di Google Maps</span>
                </a>
            </div>

        </div>

    </div>
</section>
