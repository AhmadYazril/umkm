@php
    $hours   = \App\Models\OperatingHour::orderBy('day_of_week')->get();
    $ig      = \App\Models\Setting::get('cafe_instagram');
    $tt      = \App\Models\Setting::get('cafe_tiktok');
    $wa      = \App\Models\Setting::get('cafe_whatsapp');
    $address = \App\Models\Setting::get('cafe_address');
@endphp

<footer class="relative bg-zinc-950 text-white overflow-hidden">

    {{-- ── TOP CTA BAND ── --}}
    <div class="border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div>
                <p class="text-[11px] font-mono uppercase tracking-widest text-zinc-500 mb-2">Siap berkunjung?</p>
                <h2 class="text-2xl sm:text-4xl font-serif font-bold text-white leading-tight">
                    Temukan Momen Terbaikmu<br>
                    <span class="italic font-normal text-zinc-400">di Nucomu Cafe.</span>
                </h2>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                <a href="{{ route('reservation') }}"
                   class="inline-flex items-center gap-2.5 px-7 py-3.5 bg-white text-zinc-950 text-[11px] font-bold tracking-widest uppercase rounded-full hover:bg-zinc-100 transition-all duration-300 shadow-lg hover:shadow-white/10 hover:-translate-y-0.5 group">
                    <span>Reservasi Meja</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="{{ route('menu') }}"
                   class="inline-flex items-center gap-2 px-7 py-3.5 border border-white/15 text-zinc-300 text-[11px] font-semibold tracking-widest uppercase rounded-full hover:border-white/40 hover:text-white transition-all duration-300">
                    Lihat Menu
                </a>
            </div>
        </div>
    </div>

    {{-- ── MAIN FOOTER GRID ── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">

            {{-- Brand Column (wider) --}}
            <div class="lg:col-span-4">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3.5 mb-7 group">
                    <div class="w-12 h-12 bg-white flex items-center justify-center shadow-md group-hover:shadow-white/20 transition-shadow">
                        <span class="text-zinc-950 font-serif font-black text-xl tracking-tighter select-none">NU</span>
                    </div>
                    <div>
                        <div class="font-black text-white text-base tracking-[0.28em] leading-none mb-0.5">NUCOMU</div>
                        <div class="text-zinc-500 text-[10px] tracking-[0.25em] uppercase">Coffee & Dessert</div>
                    </div>
                </a>

                {{-- Tagline --}}
                <blockquote class="border-l-2 border-white/20 pl-4 mb-6">
                    <p class="text-zinc-400 text-sm font-serif italic leading-relaxed">
                        "New, Unforgettable,<br>Comfy, Musings"
                    </p>
                </blockquote>

                {{-- Short Desc --}}
                <p class="text-zinc-500 text-sm leading-relaxed mb-7 max-w-xs">
                    Ruang monokrom elegan di Tulungagung untuk menikmati dessert otentik, kopi artisanal, dan momen berharga bersama orang tersayang.
                </p>

                {{-- Social Links --}}
                <div class="flex items-center gap-3 flex-wrap">
                    @if($wa && !str_contains($wa, '[ISI'))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-white/[0.06] border border-white/10 text-zinc-400 hover:text-white hover:border-white/30 hover:bg-white/10 transition-all text-[11px] tracking-widest uppercase font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.096.543 4.07 1.492 5.788L0 24l6.386-1.468A11.935 11.935 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.006-1.376l-.36-.213-3.722.855.882-3.617-.236-.373A9.776 9.776 0 012.182 12C2.182 6.58 6.58 2.182 12 2.182S21.818 6.58 21.818 12 17.42 21.818 12 21.818z"/></svg>
                        <span>WhatsApp</span>
                    </a>
                    @endif
                    @if($ig && !str_contains($ig, '[ISI'))
                    <a href="{{ $ig }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-white/[0.06] border border-white/10 text-zinc-400 hover:text-white hover:border-white/30 hover:bg-white/10 transition-all text-[11px] tracking-widest uppercase font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        <span>Instagram</span>
                    </a>
                    @endif
                    @if($tt && !str_contains($tt, '[ISI'))
                    <a href="{{ $tt }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-white/[0.06] border border-white/10 text-zinc-400 hover:text-white hover:border-white/30 hover:bg-white/10 transition-all text-[11px] tracking-widest uppercase font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        <span>TikTok</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Spacer on large --}}
            <div class="hidden lg:block lg:col-span-1"></div>

            {{-- Operating Hours --}}
            <div class="lg:col-span-3">
                <h3 class="text-[10px] font-mono uppercase tracking-[0.2em] text-zinc-500 mb-6 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Jam Operasional</span>
                </h3>
                <ul class="space-y-2.5">
                    @php $nowDow = now('Asia/Jakarta')->dayOfWeek; @endphp
                    @foreach($hours as $h)
                    @php $isToday = ($h->day_of_week == $nowDow); @endphp
                    <li class="flex justify-between items-center gap-4
                        {{ $isToday ? 'text-white' : ($h->is_closed ? 'text-zinc-600' : 'text-zinc-400') }}">
                        <span class="text-sm flex items-center gap-2">
                            @if($isToday)
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                            @else
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-transparent shrink-0"></span>
                            @endif
                            {{ $h->day_name }}
                            @if($isToday)
                                <span class="text-[9px] bg-white text-zinc-950 px-1.5 py-0.5 rounded-full font-bold tracking-widest uppercase">Hari Ini</span>
                            @endif
                        </span>
                        <span class="font-mono text-xs tracking-wider shrink-0
                            {{ $h->is_closed ? 'text-red-500/70' : '' }}">
                            {{ $h->schedule_text }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @if($address && !str_contains($address, '[ISI'))
                <div class="mt-6 pt-5 border-t border-white/5 flex items-start gap-2 text-zinc-500 text-xs leading-relaxed">
                    <svg class="w-3.5 h-3.5 mt-0.5 shrink-0 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $address }}</span>
                </div>
                @endif
            </div>

            {{-- Navigation --}}
            <div class="lg:col-span-2">
                <h3 class="text-[10px] font-mono uppercase tracking-[0.2em] text-zinc-500 mb-6">Navigasi</h3>
                <ul class="space-y-3">
                    @foreach([
                        ['route' => 'home',        'label' => 'Beranda'],
                        ['route' => 'menu',        'label' => 'Menu'],
                        ['route' => 'about',       'label' => 'Cerita Kami'],
                        ['route' => 'gallery',     'label' => 'Galeri'],
                        ['route' => 'facilities',  'label' => 'Fasilitas'],
                        ['route' => 'reservation', 'label' => 'Reservasi'],
                        ['route' => 'contact',     'label' => 'Kontak'],
                        ['route' => 'order.track', 'label' => 'Lacak Pesanan'],
                    ] as $nav)
                    <li>
                        <a href="{{ route($nav['route']) }}"
                           class="text-zinc-500 hover:text-white text-sm transition-all duration-200 hover:translate-x-1 inline-flex items-center gap-1.5 group">
                            <span class="w-0 group-hover:w-2 h-px bg-white transition-all duration-200 overflow-hidden"></span>
                            {{ $nav['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Quick Order --}}
            <div class="lg:col-span-2">
                <h3 class="text-[10px] font-mono uppercase tracking-[0.2em] text-zinc-500 mb-6">Pesan Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('menu') }}"
                       class="flex items-center gap-3 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.07] hover:bg-white/[0.08] hover:border-white/20 transition-all group">
                        <span class="text-lg">☕</span>
                        <div>
                            <p class="text-xs font-semibold text-white leading-tight">Lihat Menu</p>
                            <p class="text-[10px] text-zinc-500">Kopi & Dessert</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-zinc-600 ml-auto group-hover:text-white group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('cart') }}"
                       class="flex items-center gap-3 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.07] hover:bg-white/[0.08] hover:border-white/20 transition-all group">
                        <span class="text-lg">🛒</span>
                        <div>
                            <p class="text-xs font-semibold text-white leading-tight">Keranjang</p>
                            <p class="text-[10px] text-zinc-500">Lanjut Pesan</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-zinc-600 ml-auto group-hover:text-white group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('events') }}"
                       class="flex items-center gap-3 p-3.5 rounded-2xl bg-white/[0.04] border border-white/[0.07] hover:bg-white/[0.08] hover:border-white/20 transition-all group">
                        <span class="text-lg">🎉</span>
                        <div>
                            <p class="text-xs font-semibold text-white leading-tight">Event</p>
                            <p class="text-[10px] text-zinc-500">Promo & Acara</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-zinc-600 ml-auto group-hover:text-white group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── BOTTOM BAR ── --}}
    <div class="border-t border-white/[0.05]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-4 text-xs text-zinc-600 font-mono">
                <span>© {{ date('Y') }} Nucomu Cafe</span>
                <span class="hidden sm:inline text-zinc-800">·</span>
                <span class="hidden sm:inline">All rights reserved</span>
            </div>
            <div class="flex items-center gap-3 text-xs text-zinc-600">
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-mono">Buka &amp; Melayani</span>
                </span>
                <span class="text-zinc-800">·</span>
                <span class="font-mono">Tulungagung, Jawa Timur</span>
            </div>
        </div>
    </div>

    {{-- Big NUCOMU watermark --}}
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 text-white/[0.025] font-serif font-black text-[10rem] sm:text-[16rem] leading-none select-none pointer-events-none whitespace-nowrap overflow-hidden">
        NUCOMU
    </div>

</footer>
