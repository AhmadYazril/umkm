@extends('layouts.app')
@section('title', 'Fasilitas Cafe — Nucomu Cafe')

@section('content')
<section class="py-16 sm:py-20 bg-zinc-950 min-h-screen text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-4xl mx-auto mb-10 sm:mb-14 flex flex-col items-center">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-3 pl-[0.1em]">Our Space &amp; Amenities</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight uppercase mx-auto">
                Fasilitas <span class="text-zinc-500">Nucomu</span>
            </h1>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed max-w-3xl mx-auto mt-3.5">
                Dirancang khusus dengan cermat untuk kenyamanan bekerja (WFC), kehangatan berkumpul bersama teman, hingga ketenangan dalam beribadah.
            </p>
        </div>

        @if($facilities->count() > 0)
            <div class="space-y-12 sm:space-y-16 mb-16">
                @foreach($facilities as $facility)
                    @php
                        $photos = $facility->formatted_photos;
                    @endphp
                    <div class="bg-zinc-900/60 border border-zinc-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-2xl hover:border-zinc-700 transition-all">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            
                            {{-- Info Sisi Kiri --}}
                            <div class="lg:col-span-6 space-y-5 text-left">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-800/80 border border-zinc-700 text-xs font-mono font-bold text-zinc-300 uppercase tracking-widest">
                                    <span>#{{ str_pad($facility->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $facility->tab_name ?: strtoupper($facility->slug) }}</span>
                                </div>

                                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                                    {{ $facility->title ?: $facility->label }}
                                </h2>

                                <p class="text-zinc-400 text-xs sm:text-sm leading-relaxed">
                                    {{ $facility->description }}
                                </p>

                                @if(!empty($facility->features) && is_array($facility->features))
                                    <div class="pt-2">
                                        <span class="text-[11px] font-mono uppercase tracking-wider text-zinc-500 block mb-2.5 font-bold">Keunggulan Utama:</span>
                                        <ul class="space-y-2 text-xs sm:text-sm text-zinc-300">
                                            @foreach($facility->features as $feat)
                                                <li class="flex items-center gap-3">
                                                    <span class="w-5 h-5 rounded-full bg-white/10 border border-zinc-700 flex items-center justify-center text-white shrink-0">
                                                        <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                        </svg>
                                                    </span>
                                                    <span>{{ $feat }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            {{-- Foto Sisi Kanan (1-3 Foto Grid) --}}
                            <div class="lg:col-span-6">
                                @if(count($photos) === 1)
                                    <div class="rounded-2xl overflow-hidden border border-zinc-800 aspect-[16/10] bg-zinc-900 group relative">
                                        <img src="{{ $photos[0]['src'] }}" alt="{{ $photos[0]['alt'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                        <span class="absolute bottom-3 left-4 text-xs font-semibold text-white drop-shadow-sm">{{ $photos[0]['caption'] }}</span>
                                    </div>
                                @elseif(count($photos) === 2)
                                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                        @foreach($photos as $p)
                                            <div class="rounded-2xl overflow-hidden border border-zinc-800 aspect-[4/3] bg-zinc-900 group relative">
                                                <img src="{{ $p['src'] }}" alt="{{ $p['alt'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                                <span class="absolute bottom-2.5 left-3 text-[11px] font-semibold text-white drop-shadow-sm line-clamp-1">{{ $p['caption'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="grid grid-cols-12 gap-3 sm:gap-4">
                                        <div class="col-span-12 sm:col-span-7 rounded-2xl overflow-hidden border border-zinc-800 aspect-[4/3] bg-zinc-900 group relative">
                                            <img src="{{ $photos[0]['src'] }}" alt="{{ $photos[0]['alt'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                            <span class="absolute bottom-2.5 left-3 text-xs font-semibold text-white drop-shadow-sm line-clamp-1">{{ $photos[0]['caption'] }}</span>
                                        </div>
                                        <div class="col-span-12 sm:col-span-5 grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-4">
                                            @foreach(array_slice($photos, 1, 2) as $p)
                                                <div class="rounded-xl overflow-hidden border border-zinc-800 aspect-[4/3] sm:aspect-video bg-zinc-900 group relative">
                                                    <img src="{{ $p['src'] }}" alt="{{ $p['alt'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                                    <span class="absolute bottom-2 left-2 text-[10px] font-semibold text-white drop-shadow-sm line-clamp-1">{{ $p['caption'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-zinc-900/40 rounded-3xl border border-zinc-800">
                <p class="text-zinc-400 text-sm">Fasilitas sedang diperbarui.</p>
            </div>
        @endif

        {{-- WFC Friendly Banner --}}
        <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 border border-zinc-800 rounded-3xl p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest block mb-2">Work From Cafe</span>
                <h2 class="text-2xl font-black uppercase text-white mb-2">Bawa Laptopmu &amp; Fokus Bekerja</h2>
                <p class="text-zinc-400 text-sm max-w-xl">Nucomu Cafe mendukung produktivitasmu dengan musik latar yang tidak terlalu bising dan pilihan kopi penambah energi.</p>
            </div>
            <a href="{{ route('reservation') }}" class="bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider px-6 py-3.5 rounded-full transition-all shrink-0">
                Reservasi Meja WFC
            </a>
        </div>

    </div>
</section>
@endsection
