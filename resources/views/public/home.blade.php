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
<section class="relative py-24 bg-nc-black text-white overflow-hidden topo-pattern">
    {{-- Ambient Glow Vignette --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-white/[0.03] rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/10 bg-white/[0.04] backdrop-blur-md mb-4 shadow-sm">
                <svg class="w-3.5 h-3.5 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs uppercase tracking-widest text-zinc-300 font-medium">Kapan Kami Buka</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-serif font-bold tracking-tight text-white mb-3">
                Jam Operasional
            </h2>
            <p class="text-nc-gray-mid text-sm md:text-base font-light leading-relaxed">
                Luangkan harimu dengan secangkir kopi hangat dan sajian dessert otentik di sudut terbaik Tulungagung.
            </p>
            <div class="w-12 h-px bg-white/20 mx-auto mt-6"></div>
        </div>

        {{-- Aesthetic Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            {{-- Left Side: Live Ambience & Experience Card --}}
            <div class="lg:col-span-5 bg-gradient-to-b from-zinc-900/90 to-zinc-950/90 border border-white/10 rounded-3xl p-7 sm:p-9 flex flex-col justify-between relative overflow-hidden backdrop-blur-md shadow-2xl">
                {{-- Decorative Brand Monogram Watermark --}}
                <div class="absolute -right-4 -bottom-6 text-white/[0.03] font-serif font-black text-9xl select-none pointer-events-none">
                    NU
                </div>

                <div>
                    {{-- Live Status Indicator --}}
                    <div class="mb-6">
                        @if($isOpen)
                            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold tracking-wider uppercase shadow-inner">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span>Buka Sekarang</span>
                            </div>
                            @if($todayHour)
                                <p class="text-xs text-zinc-400 mt-2 font-mono">
                                    Hari ini melayani hingga <span class="text-white font-semibold">{{ substr($todayHour->close_time, 0, 5) }} WIB</span>
                                </p>
                            @endif
                        @else
                            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-zinc-800/80 border border-zinc-700/60 text-zinc-400 text-xs font-semibold tracking-wider uppercase">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-zinc-500"></span>
                                <span>Sedang Tutup</span>
                            </div>
                            <p class="text-xs text-zinc-400 mt-2">
                                @if($todayHour && $todayHour->is_closed)
                                    Hari ini libur operasional. Sampai jumpa esok hari!
                                @elseif($todayHour)
                                    Buka hari ini pukul <span class="text-white font-medium">{{ substr($todayHour->open_time, 0, 5) }} WIB</span>
                                @else
                                    Silakan cek jadwal harian di samping.
                                @endif
                            </p>
                        @endif
                    </div>

                    {{-- Card Copy & Features --}}
                    <h3 class="text-2xl font-serif font-bold text-white mb-3">
                        Ruang Nyaman, Kopi Berkesan
                    </h3>
                    <p class="text-zinc-400 text-sm leading-relaxed mb-6">
                        Nucomu Cafe didesain dengan konsep monokrom yang tenang dan elegan. Pilihan tepat untuk santai, bekerja nyaman (WFC), atau berbagi cerita hangat.
                    </p>

                    <ul class="space-y-3 mb-8 text-xs sm:text-sm text-zinc-300">
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white shrink-0">☕</span>
                            <span>Kopi pilihan & signature artisan dessert</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white shrink-0">⚡</span>
                            <span>Wi-Fi kencang & stopkontak ramah WFC</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white shrink-0">🌿</span>
                            <span>Area indoor ber-AC & outdoor aesthetic</span>
                        </li>
                    </ul>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-white/10 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('reservation') }}" class="btn-primary text-center text-xs tracking-widest uppercase flex items-center justify-center gap-2 group">
                        <span>Reservasi Meja</span>
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline text-white border-white/20 hover:border-white hover:bg-white hover:text-nc-black text-center text-xs tracking-widest uppercase">
                        Lokasi & Kontak
                    </a>
                </div>
            </div>

            {{-- Right Side: Daily Schedule Table Card --}}
            <div class="lg:col-span-7 bg-zinc-900/40 border border-white/10 rounded-3xl p-6 sm:p-8 backdrop-blur-md shadow-2xl flex flex-col justify-between">
                <div>
                    {{-- Schedule Header --}}
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-white/10 text-xs font-semibold tracking-wider uppercase text-zinc-400">
                        <span>Hari</span>
                        <span>Jam Buka – Tutup</span>
                    </div>

                    {{-- Schedule List --}}
                    <div class="space-y-2">
                        @foreach($hours as $h)
                            @php
                                $isToday = ($h->day_of_week == $now->dayOfWeek);
                            @endphp

                            @if($isToday)
                                {{-- Highlighted Card for Today --}}
                                <div class="flex items-center justify-between px-4 py-3.5 rounded-2xl bg-white text-nc-black font-semibold shadow-lg transition-transform duration-200 hover:scale-[1.01]">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-sm sm:text-base font-bold">{{ $h->day_name }}</span>
                                        <span class="text-[10px] sm:text-xs bg-nc-black text-white px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                            Hari Ini
                                        </span>
                                    </div>
                                    <div>
                                        @if($h->is_closed)
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700 uppercase tracking-widest">
                                                LIBUR
                                            </span>
                                        @else
                                            <span class="font-mono text-sm sm:text-base font-bold tracking-tight">
                                                {{ substr($h->open_time, 0, 5) }} – {{ substr($h->close_time, 0, 5) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @elseif($h->is_closed)
                                {{-- Closed Day --}}
                                <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] text-zinc-500 hover:bg-white/[0.04] transition-colors">
                                    <span class="text-sm font-medium text-zinc-400">{{ $h->day_name }}</span>
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-950/40 border border-red-800/40 text-red-400 uppercase tracking-wider">
                                        LIBUR
                                    </span>
                                </div>
                            @else
                                {{-- Regular Open Day --}}
                                <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-white/[0.02] border border-white/[0.04] text-zinc-300 hover:bg-white/[0.05] hover:text-white transition-all">
                                    <span class="text-sm font-medium text-zinc-200">{{ $h->day_name }}</span>
                                    <span class="font-mono text-sm tracking-tight text-zinc-300">
                                        {{ substr($h->open_time, 0, 5) }} – {{ substr($h->close_time, 0, 5) }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Last Order Note --}}
                <div class="mt-6 pt-4 border-t border-white/5 flex items-center gap-2 text-xs text-zinc-400">
                    <svg class="w-4 h-4 text-zinc-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Pesanan terakhir (last order) dilayani 30 menit sebelum waktu tutup operasional.</span>
                </div>
            </div>

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
