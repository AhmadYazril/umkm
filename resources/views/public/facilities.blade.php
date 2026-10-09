@extends('layouts.app')
@section('title', 'Fasilitas Cafe — Nucomu Cafe')

@section('content')
<section class="py-16 bg-zinc-950 min-h-screen text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-2">Kenyamanan Anda</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight uppercase mb-4">
                Fasilitas <span class="text-zinc-500">Nucomu</span>
            </h1>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">
                Dirancang khusus untuk mendukung kenyamanan kerja fleksibel (Work From Cafe), kumpul komunitas, maupun tempat bersantai bersama teman.
            </p>
        </div>

        @if($facilities->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                @foreach($facilities as $facility)
                    <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6 hover:border-zinc-700 transition-all flex items-start space-x-4">
                        <div class="p-3 bg-zinc-950 border border-zinc-800 rounded-2xl text-white shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-white mb-1">{{ $facility->label }}</h3>
                            <p class="text-zinc-400 text-xs leading-relaxed">Disediakan untuk memastikan setiap pengunjung merasakan pengalaman terbaik.</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Hardcoded feature grid fallback --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <h3 class="font-bold text-base text-white mb-2">High-Speed Wi-Fi</h3>
                    <p class="text-zinc-400 text-xs">Koneksi internet cepat dan stabil untuk rapat online, pengerjaan tugas, atau browsing.</p>
                </div>
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <h3 class="font-bold text-base text-white mb-2">Stop Kontak di Setiap Meja</h3>
                    <p class="text-zinc-400 text-xs">Tak perlu khawatir kehabisan daya laptop atau HP saat asyik bekerja di cafe.</p>
                </div>
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <h3 class="font-bold text-base text-white mb-2">Ruangan Indoor Ber-AC</h3>
                    <p class="text-zinc-400 text-xs">Area sejuk, bebas asap rokok, dan tenang cocok untuk fokus bekerja.</p>
                </div>
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <h3 class="font-bold text-base text-white mb-2">Outdoor & Smoking Area</h3>
                    <p class="text-zinc-400 text-xs">Area luar ruangan yang estetik dengan pencahayaan hangat di malam hari.</p>
                </div>
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <h3 class="font-bold text-base text-white mb-2">Mushola Bersih</h3>
                    <p class="text-zinc-400 text-xs">Fasilitas ibadah lengkap dengan sarung, mukena, dan wudhu yang nyaman.</p>
                </div>
                <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-6">
                    <h3 class="font-bold text-base text-white mb-2">Area Parkir Luas</h3>
                    <p class="text-zinc-400 text-xs">Kapasitas parkir motor dan mobil aman di area cafe.</p>
                </div>
            </div>
        @endif

        {{-- WFC Friendly Banner --}}
        <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 border border-zinc-800 rounded-3xl p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest block mb-2">Work From Cafe</span>
                <h2 class="text-2xl font-black uppercase text-white mb-2">Bawa Laptopmu & Fokus Bekerja</h2>
                <p class="text-zinc-400 text-sm max-w-xl">Nucomu Cafe mendukung produktivitasmu dengan musik latar yang tidak terlalu bising dan pilihan kopi penambah energi.</p>
            </div>
            <a href="{{ route('reservation') }}" class="bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider px-6 py-3.5 rounded-full transition-all shrink-0">
                Reservasi Meja WFC
            </a>
        </div>

    </div>
</section>
@endsection
