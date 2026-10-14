@extends('layouts.app')
@section('title', 'Tentang Kami — Nucomu Cafe')

@section('content')
<section class="py-16 text-zinc-900 dark:text-white min-h-screen relative">
    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Hero Story --}}
        <div class="text-center max-w-3xl mx-auto mb-16 reveal">
            <span class="text-xs font-mono tracking-widest text-zinc-500 uppercase block mb-3">Cerita Perjalanan Kami</span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-zinc-950 dark:text-white uppercase mb-6">
                Dari <span class="text-zinc-500">Banoffea</span> Hingga <span class="border-b-2 border-zinc-950 dark:border-white pb-1">Nucomu Cafe</span>
            </h1>
            <p class="text-zinc-600 dark:text-zinc-400 text-sm sm:text-base leading-relaxed">
                Perjalanan cita rasa yang dimulai dari usaha dessert rumahan di Tulungagung pada tahun 2020, kini tumbuh menjadi ruang berkumpul modern dengan filosofi kenyamanan dan kehangatan.
            </p>
        </div>

        {{-- Timeline / Story Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
            <div class="reveal bg-white/80 dark:bg-zinc-900/60 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl p-8 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all shadow-sm">
                <div class="w-12 h-12 bg-zinc-950 text-white dark:bg-white dark:text-zinc-950 font-black rounded-2xl flex items-center justify-center text-sm mb-6 shadow-sm">
                    2020
                </div>
                <h2 class="text-xl font-bold uppercase mb-3 text-zinc-950 dark:text-white">Awal Mula: Banoffea</h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed" data-typewriter>
                    Dimulai dengan sajian Banoffee Pie berbahan dasar pisang pilihan dan karamel buatan sendiri. Kelezatan yang sederhana namun melekat di hati para pecinta dessert Tulungagung, menjadikan Banoffea pilihan favorit untuk momen manis.
                </p>
            </div>

            <div class="reveal bg-white/80 dark:bg-zinc-900/60 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl p-8 hover:border-zinc-400 dark:hover:border-zinc-700 transition-all shadow-sm">
                <div class="w-12 h-12 bg-zinc-200 text-zinc-950 dark:bg-zinc-800 dark:text-white font-black rounded-2xl flex items-center justify-center text-sm mb-6 shadow-sm">
                    KINI
                </div>
                <h2 class="text-xl font-bold uppercase mb-3 text-zinc-950 dark:text-white">Transformasi Nucomu Cafe</h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed" data-typewriter>
                    Rebranding menjadi Nucomu Cafe membawa visi baru: menggabungkan sajian dessert unggulan kami dengan racikan kopi artisanal, makanan berat gurih, dan suasana tempat yang menenangkan untuk bekerja maupun bersantai.
                </p>
            </div>
        </div>

        {{-- 4 Filosofi NUCOMU Grid --}}
        <div class="reveal bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 mb-20 shadow-sm">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-black uppercase tracking-tight mb-3 text-zinc-950 dark:text-white">Filosofi <span class="text-zinc-500">NUCOMU</span></h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">Empat pilar utama yang melandasi setiap cangkir kopi dan sajian yang kami hidangkan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="reveal-scale bg-zinc-100/90 dark:bg-zinc-950 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800/80 hover:border-zinc-400 dark:hover:border-white/40 transition-colors shadow-sm">
                    <span class="text-3xl font-black text-zinc-400 dark:text-zinc-700 block mb-3 font-mono">N</span>
                    <h3 class="font-bold text-base text-zinc-950 dark:text-white mb-2 uppercase">New</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs leading-relaxed">Inovasi rasa yang selalu segar, kreatif, dan menghadirkan pengalaman kuliner baru.</p>
                </div>
                <div class="reveal-scale bg-zinc-100/90 dark:bg-zinc-950 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800/80 hover:border-zinc-400 dark:hover:border-white/40 transition-colors shadow-sm">
                    <span class="text-3xl font-black text-zinc-400 dark:text-zinc-700 block mb-3 font-mono">U</span>
                    <h3 class="font-bold text-base text-zinc-950 dark:text-white mb-2 uppercase">Unforgettable</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs leading-relaxed">Momen tak terlupakan di setiap sudut tempat dan tegukan hidangan kami.</p>
                </div>
                <div class="reveal-scale bg-zinc-100/90 dark:bg-zinc-950 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800/80 hover:border-zinc-400 dark:hover:border-white/40 transition-colors shadow-sm">
                    <span class="text-3xl font-black text-zinc-400 dark:text-zinc-700 block mb-3 font-mono">C</span>
                    <h3 class="font-bold text-base text-zinc-950 dark:text-white mb-2 uppercase">Comfy</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs leading-relaxed">Suasana yang tenang, nyaman untuk WFC (Work from Cafe), diskusi, atau sekadar melepas lelah.</p>
                </div>
                <div class="reveal-scale bg-zinc-100/90 dark:bg-zinc-950 p-6 rounded-2xl border border-zinc-200/80 dark:border-zinc-800/80 hover:border-zinc-400 dark:hover:border-white/40 transition-colors shadow-sm">
                    <span class="text-3xl font-black text-zinc-400 dark:text-zinc-700 block mb-3 font-mono">M</span>
                    <h3 class="font-bold text-base text-zinc-950 dark:text-white mb-2 uppercase">Musings</h3>
                    <p class="text-zinc-600 dark:text-zinc-400 text-xs leading-relaxed">Ruang untuk merenung, bertukar ide, dan merajut inspirasi bersama teman dan kerabat.</p>
                </div>
            </div>
        </div>

        {{-- CTA Menu --}}
        <div class="text-center py-10 reveal">
            <h3 class="text-xl font-bold uppercase mb-4 text-zinc-950 dark:text-white">Ingin Menikmati Hidangan Kami?</h3>
            <a href="{{ route('menu') }}" class="inline-flex items-center px-8 py-4 rounded-full bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 font-black text-xs uppercase tracking-wider hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-all shadow-xl">
                Lihat Menu Lengkap
            </a>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<style>
    [data-typewriter] {
        position: relative;
    }
    .typewriter-cursor {
        display: inline-block;
        width: 2px;
        height: 1em;
        background-color: currentColor;
        margin-left: 2px;
        vertical-align: text-bottom;
        animation: tw-blink 0.75s step-end infinite;
        opacity: 0.7;
    }
    @keyframes tw-blink {
        0%, 100% { opacity: 0.7; }
        50% { opacity: 0; }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const typewriterElements = document.querySelectorAll('[data-typewriter]');

        /**
         * @param {HTMLElement} el
         */
        function runTypewriter(el) {
            const fullText = el.dataset.originalText || el.textContent.trim();
            el.dataset.originalText = fullText;

            // Remove any existing cursor
            const existingCursor = el.querySelector('.typewriter-cursor');
            if (existingCursor) existingCursor.remove();

            el.textContent = '';

            const cursor = document.createElement('span');
            cursor.className = 'typewriter-cursor';
            el.appendChild(cursor);

            let i = 0;
            const speed = 22; // ms per character

            function type() {
                if (i < fullText.length) {
                    el.insertBefore(document.createTextNode(fullText.charAt(i)), cursor);
                    i++;
                    setTimeout(type, speed);
                } else {
                    // Remove cursor after finishing
                    setTimeout(() => cursor.remove(), 1200);
                }
            }

            type();
        }

        if (!('IntersectionObserver' in window)) {
            typewriterElements.forEach(el => {
                const text = el.textContent.trim();
                el.dataset.originalText = text;
            });
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    runTypewriter(entry.target);
                }
            });
        }, {
            threshold: 0.3,
        });

        typewriterElements.forEach(el => observer.observe(el));
    });
</script>
@endpush
