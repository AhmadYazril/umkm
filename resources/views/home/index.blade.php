@extends('layouts.app')

@section('title', 'Nucomu Cafe — Tempat Kembali, Rasa yang Dirindukan | Tulungagung')
@section('meta_description', 'Nucomu Cafe Tulungagung — Ruang santai monokrom hangat untuk menikmati seduhan kopi single origin, dessert artisanal, dan obrolan bermakna.')

@section('content')
    {{-- =====================================================================
         HALAMAN MOBILE 1: HOME & ABOUT (2 Section: Hero + About & Fasilitas)
         Di Desktop (md:): tampil berurutan normal seperti biasa
         ===================================================================== --}}
    <div id="mobile-page-home-about"
         class="mobile-home-page is-active-mobile-page"
         data-mobile-page="home-about">
        <!-- 1. Section Home / Hero (Flat-lay Overhead Composition) -->
        @include('home.sections.hero')

        <!-- 2. Section About (Cerita, Konsep & Fasilitas: Indoor, Outdoor, Musala, Toilet) -->
        @include('home.sections.about')
    </div>

    {{-- =====================================================================
         HALAMAN MOBILE 2: MENU (1 Section Khusus Menu Sendiri)
         Di Desktop (md:): tampil berurutan normal seperti biasa
         ===================================================================== --}}
    <div id="mobile-page-menu"
         class="mobile-home-page"
         data-mobile-page="menu">
        <div class="md:hidden h-14 bg-[#F9F9F9]" aria-hidden="true"></div>
        <!-- 3. Section Menu (Signature Best Menu Switcher & Categories) -->
        @include('home.sections.best-menu')
    </div>

    {{-- =====================================================================
         HALAMAN MOBILE 3: GALERI & ULASAN (2 Section: Gallery 3D + Reviews)
         Di Desktop (md:): tampil berurutan normal seperti biasa
         ===================================================================== --}}
    <div id="mobile-page-gallery-reviews"
         class="mobile-home-page"
         data-mobile-page="gallery-reviews">
        <div class="md:hidden h-14 bg-[#FFFFFF]" aria-hidden="true"></div>
        <!-- 4. Section Gallery (Asymmetric Editorial Collage & Lightbox) -->
        @include('home.sections.gallery')

        <!-- 6. Section Reviews (Google Reviews Hub with Honest Fallback, After Gallery & Before Layanan) -->
        @include('home.sections.reviews')
    </div>

    {{-- =====================================================================
         HALAMAN MOBILE 4: LAYANAN & KONTAK (2 Section: Layanan + Contact)
         Di Desktop (md:): tampil berurutan normal seperti biasa
         ===================================================================== --}}
    <div id="mobile-page-layanan-contact"
         class="mobile-home-page"
         data-mobile-page="layanan-contact">
        <div class="md:hidden h-14 bg-[#FAFAFA]" aria-hidden="true"></div>
        <!-- 7. Section Layanan (Detail Layanan & Modal Reservasi WhatsApp) -->
        @include('home.sections.layanan')

        <!-- 8. Section Contact (Lokasi, Jam Operasional & Petunjuk Arah) -->
        @include('home.sections.contact')
    </div>

    {{-- =====================================================================
         FLOATING GLASS BOTTOM LAY-UP NAVIGATION BAR (KHUSUS MOBILE < 768px)
         Membagi halaman menjadi 4 Page:
           1. Home & About (2 section)
           2. Menu (Sendiri)
           3. Galeri & Ulasan (2 section)
           4. Layanan & Kontak (2 section)
         ===================================================================== --}}
    <nav id="mobile-glass-layup-nav"
         class="md:hidden fixed bottom-3 left-3 right-3 z-40 select-none"
         aria-label="Navigasi Halaman Mobile">
        <div class="mobile-glass-layup-dock grid grid-cols-4 gap-1 p-1.5 rounded-[22px]">
            <button type="button"
                    onclick="switchMobileHomePage('home-about')"
                    data-mobile-page-btn="home-about"
                    aria-current="page"
                    class="mobile-glass-layup-btn is-active flex flex-col items-center justify-center gap-0.5 py-2 px-1 rounded-[16px] transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[20px] leading-none">home</span>
                <span class="text-[10px] font-bold tracking-tight leading-tight truncate max-w-full">Home &amp; About</span>
            </button>

            <button type="button"
                    onclick="switchMobileHomePage('menu')"
                    data-mobile-page-btn="menu"
                    class="mobile-glass-layup-btn flex flex-col items-center justify-center gap-0.5 py-2 px-1 rounded-[16px] transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[20px] leading-none">restaurant_menu</span>
                <span class="text-[10px] font-semibold tracking-tight leading-tight truncate max-w-full">Menu</span>
            </button>

            <button type="button"
                    onclick="switchMobileHomePage('gallery-reviews')"
                    data-mobile-page-btn="gallery-reviews"
                    class="mobile-glass-layup-btn flex flex-col items-center justify-center gap-0.5 py-2 px-1 rounded-[16px] transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[20px] leading-none">photo_library</span>
                <span class="text-[10px] font-semibold tracking-tight leading-tight truncate max-w-full">Galeri &amp; Ulas</span>
            </button>

            <button type="button"
                    onclick="switchMobileHomePage('layanan-contact')"
                    data-mobile-page-btn="layanan-contact"
                    class="mobile-glass-layup-btn flex flex-col items-center justify-center gap-0.5 py-2 px-1 rounded-[16px] transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[20px] leading-none">concierge</span>
                <span class="text-[10px] font-semibold tracking-tight leading-tight truncate max-w-full">Layanan &amp; Info</span>
            </button>
        </div>
    </nav>
@endsection
