@extends('layouts.app')
@section('title', 'Kontak & Lokasi — Nucomu Cafe')

@section('content')
<section class="py-16 bg-zinc-950 min-h-screen text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-2">Hubungi Kami</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight uppercase mb-4">
                Kontak & <span class="text-zinc-500">Lokasi</span>
            </h1>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">
                Kunjungi kami di Tulungagung atau hubungi tim Nucomu Cafe untuk pertanyaan, kerjasama, dan reservasi.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
            
            {{-- Info Cards (Col 5) --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- Alamat Card --}}
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase">Alamat Cafe</h3>
                    </div>
                    <p class="text-zinc-400 text-xs leading-relaxed">
                        {{ $settings['address'] ?? 'Jl. Panglima Sudirman No. 45, Kebonsari, Kec. Tulungagung, Kabupaten Tulungagung, Jawa Timur 66212' }}
                    </p>
                </div>

                {{-- WhatsApp / Telepon Card --}}
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase">WhatsApp & CS</h3>
                    </div>
                    <p class="text-zinc-400 text-xs mb-2">Respon cepat via WhatsApp:</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['phone'] ?? '6281234567890') }}" target="_blank" class="inline-flex items-center space-x-2 text-sm font-bold text-emerald-400 hover:underline">
                        <span>{{ $settings['phone'] ?? '+62 812-3456-7890' }}</span>
                    </a>
                </div>

                {{-- Jam Operasional Card --}}
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-base uppercase">Jam Operasional</h3>
                    </div>
                    
                    <div class="space-y-2 text-xs">
                        @foreach($operatingHours as $oh)
                            <div class="flex justify-between py-1 border-b border-zinc-800/50">
                                <span class="text-zinc-400 font-medium">{{ $oh->day_name }}</span>
                                @if($oh->is_closed)
                                    <span class="text-red-400 font-bold uppercase">LIBUR</span>
                                @else
                                    <span class="text-white font-mono">{{ date('H:i', strtotime($oh->open_time)) }} - {{ date('H:i', strtotime($oh->close_time)) }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Maps Embed (Col 7) --}}
            <div class="lg:col-span-7 bg-zinc-900/60 border border-zinc-800/80 rounded-3xl overflow-hidden min-h-[400px] flex flex-col justify-between p-2">
                <div class="w-full h-full min-h-[450px] bg-zinc-950 rounded-2xl relative overflow-hidden flex flex-col items-center justify-center p-6 text-center">
                    {{-- Embed Google Maps --}}
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15794.137887556094!2d111.902!3d-8.064!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOMKwMDMnNTAuNCJTIDExM8KwNTQnMDcuMiJF!5e0!3m2!1sid!2sid!4v1600000000000!5m2!1sid!2sid" 
                        class="w-full h-full absolute inset-0 grayscale opacity-80 hover:opacity-100 hover:grayscale-0 transition-all duration-500 border-0" 
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    <div class="absolute bottom-4 left-4 right-4 bg-zinc-950/90 backdrop-blur-md border border-zinc-800 p-4 rounded-xl text-left pointer-events-none">
                        <span class="text-xs font-bold text-white block">Nucomu Cafe — Tulungagung</span>
                        <span class="text-[11px] text-zinc-400 block">Jalan Panglima Sudirman, Kebonsari</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
