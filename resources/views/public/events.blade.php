@extends('layouts.app')
@section('title', 'Event & Promo — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-2">Agenda Cafe</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight uppercase mb-4">
                Event & <span class="text-zinc-500">Promo</span>
            </h1>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">
                Ikuti berbagai agenda menarik seperti live music, workshop manual brew, dan promo terbatas di Nucomu Cafe.
            </p>
        </div>

        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($events as $event)
                    <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl overflow-hidden hover:border-zinc-700 transition-all flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-zinc-950 relative overflow-hidden">
                                @if($event->image && file_exists(public_path('storage/' . $event->image)))
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-zinc-900 text-zinc-600 p-4 text-center">
                                        <svg class="w-12 h-12 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs uppercase tracking-widest font-mono text-zinc-500">Nucomu Special Event</span>
                                    </div>
                                @endif

                                <div class="absolute top-4 left-4 bg-white text-zinc-950 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider shadow-md">
                                    {{ date('d M Y', strtotime($event->date)) }}
                                </div>
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-bold text-white mb-2">{{ $event->title }}</h3>
                                <p class="text-zinc-400 text-xs leading-relaxed line-clamp-3">
                                    {{ $event->description }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('reservation') }}" class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-white hover:text-zinc-300 transition-colors">
                                <span>Reservasi Tempat Event</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-zinc-900/40 border border-zinc-800/80 rounded-3xl p-8 max-w-xl mx-auto">
                <svg class="w-16 h-16 text-zinc-600 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-bold text-white mb-2">Belum Ada Event Mendatang</h3>
                <p class="text-zinc-400 text-sm">Nantikan update event dan penawaran spesial terbaru dari Nucomu Cafe.</p>
            </div>
        @endif

    </div>
</section>
@endsection
