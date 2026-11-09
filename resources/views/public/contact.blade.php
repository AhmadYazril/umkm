@extends('layouts.app')
@section('title', 'Kontak & Lokasi — Nucomu Cafe')

@section('content')
@php
    $contact = $contact ?? \App\Http\Controllers\NucomuController::getContactSettings();
    $mapsEmbedSrc = !empty($contact['maps_embed']) 
        ? $contact['maps_embed'] 
        : 'https://maps.google.com/maps?q=' . urlencode($contact['address'] ?? 'Nucomu Cafe Plosokandang Kedungwaru Tulungagung') . '&t=&z=16&ie=UTF8&iwloc=&output=embed';
    $mapsDirectUrl = !empty($contact['maps_url']) 
        ? $contact['maps_url'] 
        : config('nucomu.contact.google_maps_url', 'https://maps.google.com/?q=Nucomu+Cafe+Tulungagung');
@endphp
<section class="py-16 min-h-screen text-zinc-900 dark:text-white relative">
    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-2">Hubungi Kami</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-zinc-950 dark:text-white uppercase mb-4">
                Kontak & <span class="text-zinc-500">Lokasi</span>
            </h1>
            <p class="text-zinc-600 dark:text-zinc-400 text-sm sm:text-base leading-relaxed">
                Kunjungi kami di Tulungagung atau hubungi tim Nucomu Cafe untuk pertanyaan, kerjasama, dan reservasi.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
            
            {{-- Info Cards (Col 5) --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- Alamat Card --}}
                <div class="reveal bg-white/80 dark:bg-zinc-900/60 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2.5 bg-zinc-100 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase text-zinc-950 dark:text-white">Alamat Cafe</h3>
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs leading-relaxed">
                        {{ $contact['address'] ?? config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221') }}
                    </p>
                    @if(!empty($mapsDirectUrl))
                        <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <a href="{{ $mapsDirectUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-800 dark:text-zinc-200 hover:text-amber-600">
                                <span>Buka di Google Maps</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- WhatsApp / Telepon Card --}}
                <div class="reveal bg-white/80 dark:bg-zinc-900/60 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2.5 bg-zinc-100 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase text-zinc-950 dark:text-white">WhatsApp & CS</h3>
                    </div>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs mb-2">Respon cepat via WhatsApp:</p>
                    <a href="https://wa.me/{{ $contact['whatsapp_clean'] ?? '6281234567890' }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 text-sm font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                        <span>{{ $contact['whatsapp_display'] ?? '+62 812-3456-7890' }}</span>
                    </a>
                </div>

                {{-- Jam Operasional Card --}}
                <div class="reveal bg-white/80 dark:bg-zinc-900/60 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-zinc-100 dark:bg-zinc-950 border border-zinc-200/80 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase text-zinc-950 dark:text-white">Jam Operasional</h3>
                    </div>
                    
                    <div class="space-y-2 text-xs">
                        @if(!empty($contact['opening_hours']))
                            <div class="p-2.5 rounded-xl bg-zinc-50 dark:bg-zinc-950/60 border border-zinc-200/60 dark:border-zinc-800/60 font-medium text-zinc-800 dark:text-zinc-200">
                                {{ $contact['opening_hours'] }}
                            </div>
                        @endif
                        @foreach($operatingHours as $oh)
                            <div class="flex justify-between py-1 border-b border-zinc-200/80 dark:border-zinc-800/50">
                                <span class="text-zinc-600 dark:text-zinc-400 font-medium">{{ $oh->day_name }}</span>
                                @if($oh->is_closed)
                                    <span class="text-red-500 dark:text-red-400 font-bold uppercase">LIBUR</span>
                                @else
                                    <span class="text-zinc-950 dark:text-white font-mono">{{ date('H:i', strtotime($oh->open_time)) }} - {{ date('H:i', strtotime($oh->close_time)) }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Maps Embed (Col 7) --}}
            <div class="reveal lg:col-span-7 bg-white/80 dark:bg-zinc-900/60 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl overflow-hidden min-h-[400px] flex flex-col justify-between p-2 shadow-sm">
                <div class="w-full h-full min-h-[450px] bg-zinc-100 dark:bg-zinc-950 rounded-2xl relative overflow-hidden flex flex-col items-center justify-center p-6 text-center">
                    {{-- Embed Google Maps --}}
                    <iframe src="{{ $mapsEmbedSrc }}" 
                        class="w-full h-full absolute inset-0 grayscale opacity-80 hover:opacity-100 hover:grayscale-0 transition-all duration-500 border-0" 
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Nucomu Cafe Tulungagung"></iframe>

                    <div class="absolute bottom-4 left-4 right-4 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800 p-4 rounded-xl text-left pointer-events-none shadow-md">
                        <span class="text-xs font-bold text-zinc-950 dark:text-white block">Nucomu Cafe — Tulungagung</span>
                        <span class="text-[11px] text-zinc-600 dark:text-zinc-400 block">{{ $contact['address'] ?? 'Jalan Mayor Sujadi, Plosokandang, Kedungwaru' }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

        </div>

    </div>
</section>
@endsection
