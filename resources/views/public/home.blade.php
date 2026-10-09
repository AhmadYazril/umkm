@extends('layouts.app')
@section('title', 'Nucomu Cafe — Coffee & Dessert Tulungagung')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="relative min-h-[90vh] flex items-center justify-center topo-pattern overflow-hidden bg-nc-white">
    <div class="absolute inset-0 bg-gradient-to-b from-white/0 via-white/20 to-white/80 pointer-events-none"></div>
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        {{-- Logo Monogram --}}
        <div class="flex justify-center mb-8">
            <div class="w-24 h-24 bg-nc-black flex items-center justify-center shadow-2xl">
                <span class="text-white font-serif font-black text-4xl tracking-tighter">NU</span>
            </div>
        </div>
        <h1 class="font-black text-5xl md:text-7xl tracking-widest2 text-nc-black mb-2 uppercase">NUCOMU</h1>
        <p class="text-nc-gray text-base md:text-lg tracking-widest mb-2 uppercase font-medium">Coffee & Dessert</p>
        <p class="text-nc-gray-mid text-sm md:text-base italic mb-8">"New, Unforgettable, Comfy, Musings"</p>

        {{-- Status Buka/Tutup --}}
        <div class="inline-flex items-center gap-2 bg-white border border-nc-gray-light px-4 py-2 mb-10 shadow-sm">
            @if($isOpen)
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium text-green-700">Buka sekarang</span>
                @if($todayHour)
                <span class="text-nc-gray-mid text-sm">— sampai pukul {{ substr($todayHour->close_time, 0, 5) }}</span>
                @endif
            @else
                <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                <span class="text-sm font-medium text-red-600">Sedang tutup</span>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('menu') }}" id="hero-order-btn" class="btn-primary text-sm">Pesan Sekarang</a>
            <a href="#menu-unggulan" class="btn-outline text-sm">Lihat Menu</a>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 animate-bounce opacity-50">
        <div class="w-px h-8 bg-nc-black"></div>
        <div class="w-1.5 h-1.5 bg-nc-black rounded-full"></div>
    </div>
</section>

{{-- ═══ MENU UNGGULAN ═══ --}}
<section id="menu-unggulan" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-nc-gray-mid text-xs tracking-widest uppercase mb-2">Pilihan Terbaik</p>
            <h2 class="section-title">Menu Unggulan</h2>
            <div class="w-12 h-px bg-nc-black mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredMenus as $menu)
            <a href="{{ route('menu.show', $menu->slug) }}" class="group block card-hover">
                {{-- Gambar placeholder --}}
                <div class="aspect-square bg-nc-gray-pale border border-nc-gray-light flex flex-col items-center justify-center overflow-hidden">
                    @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="text-nc-gray-mid text-xs text-center p-4">
                            <div class="w-12 h-12 border-2 border-dashed border-nc-gray-light mx-auto mb-2 flex items-center justify-center">
                                <span class="text-nc-gray-light text-lg">☕</span>
                            </div>
                            <span>Foto Menu</span>
                        </div>
                    @endif
                </div>
                <div class="pt-3">
                    <div class="flex gap-1 mb-1 flex-wrap">
                        @if($menu->is_best_seller)
                        <span class="bg-nc-black text-white text-xs px-2 py-0.5 font-medium tracking-wide">Best Seller</span>
                        @endif
                        @if($menu->is_featured)
                        <span class="border border-nc-black text-nc-black text-xs px-2 py-0.5 font-medium tracking-wide">Unggulan</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-nc-black text-sm mt-1 group-hover:underline">{{ $menu->name }}</h3>
                    <p class="text-nc-gray-mid text-xs mt-0.5">{{ $menu->category->name }}</p>
                    <p class="font-bold text-nc-black text-sm mt-1">{{ $menu->formatted_price }}</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('menu') }}" class="btn-outline text-sm">Lihat Semua Menu</a>
        </div>
    </div>
</section>

{{-- ═══ CERITA SINGKAT ═══ --}}
<section class="py-20 bg-nc-gray-pale topo-pattern">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-nc-gray-mid text-xs tracking-widest uppercase mb-4">Tentang Kami</p>
        <h2 class="section-title mb-6">Dari Rindu Rasa,<br>Lahirlah Nucomu</h2>
        <div class="w-12 h-px bg-nc-black mx-auto mb-8"></div>
        <p class="text-nc-gray text-base md:text-lg leading-relaxed max-w-2xl mx-auto">
            {{ $settings['cafe_story'] ?? 'Nucomu Cafe hadir sebagai tempat yang nyaman untuk menikmati dessert, kopi, dan makanan dengan konsep monokrom elegan di Tulungagung.' }}
        </p>
        <a href="{{ route('about') }}" class="inline-block mt-8 btn-outline text-sm">Baca Selengkapnya</a>
    </div>
</section>

{{-- ═══ FASILITAS ═══ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-nc-gray-mid text-xs tracking-widest uppercase mb-2">Kenyamanan Anda</p>
            <h2 class="section-title">Fasilitas Nucomu</h2>
            <div class="w-12 h-px bg-nc-black mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($facilities as $f)
            <div class="text-center p-6 border border-nc-gray-light hover:border-nc-black transition-colors">
                <div class="w-12 h-12 bg-nc-black mx-auto mb-3 flex items-center justify-center">
                    <span class="text-white text-lg">⚡</span>
                </div>
                <p class="font-semibold text-nc-black text-sm tracking-wide">{{ $f->label }}</p>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('facilities') }}" class="btn-outline text-sm">Selengkapnya</a>
        </div>
    </div>
</section>

{{-- ═══ JAM OPERASIONAL ═══ --}}
<section class="py-20 bg-nc-black text-white topo-pattern">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <p class="text-nc-gray-mid text-xs tracking-widest uppercase mb-4">Kapan Kami Buka</p>
        <h2 class="text-3xl font-serif font-bold tracking-wide mb-10">Jam Operasional</h2>
        <div class="divide-y divide-nc-gray-dark">
            @foreach($hours as $h)
            <div class="flex justify-between py-3 {{ $h->is_closed ? 'text-nc-gray-mid' : 'text-white' }} {{ !$h->is_closed && $h->day_of_week == $now->dayOfWeek ? 'font-semibold' : '' }}">
                <span>{{ $h->day_name }}</span>
                <span>
                    {{ $h->schedule_text }}
                    @if(!$h->is_closed && $h->day_of_week == $now->dayOfWeek)
                    <span class="ml-2 text-xs bg-white text-nc-black px-2 py-0.5 font-semibold">Hari ini</span>
                    @endif
                </span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ PREVIEW GALERI ═══ --}}
@if($gallery->count() > 0)
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-nc-gray-mid text-xs tracking-widest uppercase mb-2">Momen di Nucomu</p>
            <h2 class="section-title">Galeri</h2>
            <div class="w-12 h-px bg-nc-black mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($gallery->take(6) as $item)
            <div class="aspect-square bg-nc-gray-pale border border-nc-gray-light flex items-center justify-center overflow-hidden group">
                @if($item->image && file_exists(public_path('storage/' . $item->image)))
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="text-center text-nc-gray-mid text-xs p-4">
                        <div class="w-12 h-12 border-2 border-dashed border-nc-gray-light mx-auto mb-2"></div>
                        {{ $item->caption }}
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('gallery') }}" class="btn-outline text-sm">Lihat Semua Foto</a>
        </div>
    </div>
</section>
@endif

{{-- ═══ TESTIMONI ═══ --}}
@if($testimonials->count() > 0)
<section class="py-20 bg-nc-gray-pale">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-nc-gray-mid text-xs tracking-widest uppercase mb-2">Kata Pelanggan</p>
            <h2 class="section-title">Testimoni</h2>
            <div class="w-12 h-px bg-nc-black mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimonials as $t)
            <div class="bg-white p-6 border border-nc-gray-light">
                <div class="flex mb-3">
                    @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $t->rating ? 'text-nc-black' : 'text-nc-gray-light' }} text-sm">★</span>
                    @endfor
                </div>
                <p class="text-nc-gray text-sm leading-relaxed italic mb-4">"{{ $t->content }}"</p>
                <p class="font-semibold text-nc-black text-sm">— {{ $t->name }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ CTA LOKASI ═══ --}}
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p class="text-nc-gray-mid text-xs tracking-widest uppercase mb-4">Temukan Kami</p>
        <h2 class="section-title mb-6">Kunjungi Nucomu Cafe</h2>
        <div class="w-12 h-px bg-nc-black mx-auto mb-8"></div>
        <p class="text-nc-gray mb-2">{{ $settings['cafe_address'] ?? '[ISI: alamat lengkap]' }}</p>
        <p class="text-nc-gray-mid text-sm">Tulungagung, Jawa Timur</p>
        {{-- Maps placeholder --}}
        <div class="mt-8 bg-nc-gray-pale border-2 border-dashed border-nc-gray-light h-64 flex items-center justify-center">
            <div class="text-center text-nc-gray-mid">
                <div class="text-3xl mb-2">📍</div>
                <p class="text-sm font-medium">Google Maps Embed</p>
                <p class="text-xs mt-1">[ISI: embed link Google Maps]</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
            <a href="{{ route('contact') }}" class="btn-primary text-sm">Info Kontak</a>
            <a href="{{ route('reservation') }}" class="btn-outline text-sm">Buat Reservasi</a>
        </div>
    </div>
</section>

@endsection
