<!-- SECTION 5: GALLERY (ASYMMETRIC EDITORIAL COLLAGE & LIGHTBOX) -->
<section id="gallery" class="py-20 lg:py-28 bg-warm-ivory border-b border-light-gray/60">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div class="reveal-on-scroll">
                <span class="text-[11px] font-semibold uppercase tracking-editorial-widest text-medium-gray block mb-2">GALERI FOTO</span>
                <h2 class="font-editorial text-3xl sm:text-5xl font-bold text-ink-black tracking-tight">Ruang, Rasa & Cerita</h2>
                <p class="font-body text-xs sm:text-sm text-charcoal/70 mt-2">
                    Dokumentasi interior tenang, presisi barista, dan kreasi sajian harian di Nucomu Tulungagung.
                </p>
            </div>
            
            <div class="mt-4 md:mt-0 flex items-center space-x-3 text-[10px] font-semibold uppercase tracking-editorial-widest text-medium-gray reveal-on-scroll">
                <span class="text-ink-black font-bold">Interior</span>
                <span>•</span>
                <span>Barista</span>
                <span>•</span>
                <span>Sajian</span>
            </div>
        </div>

        <!-- Asymmetrical Editorial Bento / Collage Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-6 items-stretch">
            
            <!-- Frame 1 (Large Feature: Interior - 8 Columns on Desktop) -->
            <div class="lg:col-span-8 bg-pure-white border border-soft-beige p-3 rounded group cursor-pointer"
                 onclick="openLightbox(0)">
                <div class="relative overflow-hidden aspect-[16/10] w-full rounded-sm bg-light-gray">
                    <img src="{{ $gallery[0]['image'] }}" 
                         alt="{{ $gallery[0]['title'] }}" 
                         data-full-src="{{ $gallery[0]['image'] }}"
                         class="gallery-trigger-img w-full h-full object-cover">
                </div>
            </div>

            <!-- Frame 2 (Plated Dessert & Pastry - 4 Columns on Desktop) -->
            <div class="lg:col-span-4 bg-pure-white border border-soft-beige p-3 rounded group cursor-pointer"
                 onclick="openLightbox(1)">
                <div class="relative overflow-hidden aspect-square w-full rounded-sm bg-light-gray">
                    <img src="{{ $gallery[1]['image'] }}" 
                         alt="{{ $gallery[1]['title'] }}" 
                         data-full-src="{{ $gallery[1]['image'] }}"
                         class="gallery-trigger-img w-full h-full object-cover">
                </div>
            </div>

            <!-- Frame 3 (Barista Manual Brew - 4 Columns) -->
            <div class="sm:col-span-1 lg:col-span-4 bg-pure-white border border-soft-beige p-3 rounded group cursor-pointer"
                 onclick="openLightbox(2)">
                <div class="relative overflow-hidden aspect-[4/3] w-full rounded-sm bg-light-gray">
                    <img src="{{ $gallery[2]['image'] }}" 
                         alt="{{ $gallery[2]['title'] }}" 
                         data-full-src="{{ $gallery[2]['image'] }}"
                         class="gallery-trigger-img w-full h-full object-cover">
                </div>
            </div>

            <!-- Frame 4 (Sanctuary Corner Reading - 4 Columns) -->
            <div class="sm:col-span-1 lg:col-span-4 bg-pure-white border border-soft-beige p-3 rounded group cursor-pointer"
                 onclick="openLightbox(3)">
                <div class="relative overflow-hidden aspect-[4/3] w-full rounded-sm bg-light-gray">
                    <img src="{{ $gallery[3]['image'] }}" 
                         alt="{{ $gallery[3]['title'] }}" 
                         data-full-src="{{ $gallery[3]['image'] }}"
                         class="gallery-trigger-img w-full h-full object-cover">
                </div>
            </div>

            <!-- Frame 5 (Latte Art & Barista Extraction - 4 Columns) -->
            <div class="sm:col-span-1 lg:col-span-4 bg-pure-white border border-soft-beige p-3 rounded group cursor-pointer"
                 onclick="openLightbox(4)">
                <div class="relative overflow-hidden aspect-[4/3] w-full rounded-sm bg-light-gray">
                    <img src="{{ $gallery[4]['image'] }}" 
                         alt="{{ $gallery[4]['title'] }}" 
                         data-full-src="{{ $gallery[4]['image'] }}"
                         class="gallery-trigger-img w-full h-full object-cover">
                </div>
            </div>

        </div>

    </div>
</section>
