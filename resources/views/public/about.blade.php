@extends('layouts.app')
@section('title', 'Tentang Kami — Nucomu Cafe')

@section('content')
<section class="py-16 bg-zinc-950 text-white min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Hero Story --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-3">Cerita Perjalanan Kami</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight uppercase mb-6">
                Dari <span class="text-zinc-500">Banoffea</span> Hingga <span class="border-b-2 border-white pb-1">Nucomu Cafe</span>
            </h1>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">
                Perjalanan cita rasa yang dimulai dari usaha dessert rumahan di Tulungagung pada tahun 2020, kini tumbuh menjadi ruang berkumpul modern dengan filosofi kenyamanan dan kehangatan.
            </p>
        </div>

        {{-- Timeline / Story Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
            <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-8 hover:border-zinc-700 transition-all">
                <div class="w-12 h-12 bg-white text-zinc-950 font-black rounded-2xl flex items-center justify-center text-sm mb-6">
                    2020
                </div>
                <h2 class="text-xl font-bold uppercase mb-3">Awal Mula: Banoffea</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Dimulai dengan sajian Banoffee Pie berbahan dasar pisang pilihan dan karamel buatan sendiri. Kelezatan yang sederhana namun melekat di hati para pecinta dessert Tulungagung, menjadikan Banoffea pilihan favorit untuk momen manis.
                </p>
            </div>

            <div class="bg-zinc-900/60 border border-zinc-800/80 rounded-3xl p-8 hover:border-zinc-700 transition-all">
                <div class="w-12 h-12 bg-zinc-800 text-white font-black rounded-2xl flex items-center justify-center text-sm mb-6">
                    KINI
                </div>
                <h2 class="text-xl font-bold uppercase mb-3">Transformasi Nucomu Cafe</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Rebranding menjadi Nucomu Cafe membawa visi baru: menggabungkan sajian dessert unggulan kami dengan racikan kopi artisanal, makanan berat gurih, dan suasana tempat yang menenangkan untuk bekerja maupun bersantai.
                </p>
            </div>
        </div>

        {{-- 4 Filosofi NUCOMU Grid --}}
        <div class="bg-zinc-900/80 border border-zinc-800 rounded-3xl p-8 sm:p-12 mb-20">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-black uppercase tracking-tight mb-3">Filosofi <span class="text-zinc-500">NUCOMU</span></h2>
                <p class="text-zinc-400 text-sm">Empat pilar utama yang melandasi setiap cangkir kopi dan sajian yang kami hidangkan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-zinc-950 p-6 rounded-2xl border border-zinc-800/80 hover:border-white/40 transition-colors">
                    <span class="text-3xl font-black text-zinc-700 block mb-3 font-mono">N</span>
                    <h3 class="font-bold text-base text-white mb-2 uppercase">New</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed">Inovasi rasa yang selalu segar, kreatif, dan menghadirkan pengalaman kuliner baru.</p>
                </div>
                <div class="bg-zinc-950 p-6 rounded-2xl border border-zinc-800/80 hover:border-white/40 transition-colors">
                    <span class="text-3xl font-black text-zinc-700 block mb-3 font-mono">U</span>
                    <h3 class="font-bold text-base text-white mb-2 uppercase">Unforgettable</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed">Momen tak terlupakan di setiap sudut tempat dan tegukan hidangan kami.</p>
                </div>
                <div class="bg-zinc-950 p-6 rounded-2xl border border-zinc-800/80 hover:border-white/40 transition-colors">
                    <span class="text-3xl font-black text-zinc-700 block mb-3 font-mono">C</span>
                    <h3 class="font-bold text-base text-white mb-2 uppercase">Comfy</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed">Suasana yang tenang, nyaman untuk WFC (Work from Cafe), diskusi, atau sekadar melepas lelah.</p>
                </div>
                <div class="bg-zinc-950 p-6 rounded-2xl border border-zinc-800/80 hover:border-white/40 transition-colors">
                    <span class="text-3xl font-black text-zinc-700 block mb-3 font-mono">M</span>
                    <h3 class="font-bold text-base text-white mb-2 uppercase">Musings</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed">Ruang untuk merenung, bertukar ide, dan merajut inspirasi bersama teman dan kerabat.</p>
                </div>
            </div>
        </div>

        {{-- CTA Menu --}}
        <div class="text-center py-10">
            <h3 class="text-xl font-bold uppercase mb-4">Ingin Menikmati Hidangan Kami?</h3>
            <a href="{{ route('menu') }}" class="inline-flex items-center px-8 py-4 rounded-full bg-white text-zinc-950 font-black text-xs uppercase tracking-wider hover:bg-zinc-200 transition-all shadow-xl hover:shadow-white/10">
                Lihat Menu Lengkap
            </a>
        </div>

    </div>
</section>
@endsection
