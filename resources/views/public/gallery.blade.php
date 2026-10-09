@extends('layouts.app')
@section('title', 'Galeri — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-2">Dokumentasi & Momen</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white uppercase mb-4">
                Galeri <span class="text-zinc-500">Nucomu</span>
            </h1>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">
                Intip suasana hangat, detail arsitektur monokrom, serta sajian kopi & dessert buatan kami di Tulungagung.
            </p>
        </div>

        @if($gallery->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($gallery as $item)
                    <div class="group relative bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden aspect-square hover:border-zinc-600 transition-all duration-300">
                        @if($item->image && file_exists(public_path('storage/' . $item->image)))
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-zinc-900 text-zinc-600 p-4 text-center">
                                <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[11px] font-mono text-zinc-500 uppercase tracking-widest">{{ $item->category ?? 'Galeri' }}</span>
                            </div>
                        @endif

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-5 flex flex-col justify-end">
                            <span class="text-[10px] font-mono text-zinc-400 uppercase tracking-widest block mb-1">
                                {{ $item->category ?? 'Nucomu Cafe' }}
                            </span>
                            <h3 class="text-sm font-bold text-white line-clamp-2">
                                {{ $item->caption ?? 'Suasana Nucomu Cafe Tulungagung' }}
                            </h3>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-zinc-900/40 border border-zinc-800/80 rounded-3xl p-8">
                <svg class="w-16 h-16 text-zinc-600 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-bold text-white mb-2">Galeri Foto Belum Tersedia</h3>
                <p class="text-zinc-400 text-sm">Foto galeri akan segera diperbarui oleh Admin Nucomu Cafe.</p>
            </div>
        @endif

    </div>
</section>
@endsection
