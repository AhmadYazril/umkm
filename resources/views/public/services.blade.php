@extends('layouts.app')
@section('title', 'Layanan — Nucomu Cafe')

@section('content')
@php
    $services = config('nucomu.services');
    $wa = config('nucomu.contact.whatsapp_number');
    $hours = config('nucomu.contact.hours');
    $steps = [
        ['Pilih layanan', 'Tentukan jenis acara atau area yang kamu butuhkan.'],
        ['Hubungi kami', 'Kirim tanggal, jumlah tamu, dan kebutuhanmu lewat WhatsApp.'],
        ['Terima konfirmasi', 'Kami cek ketersediaan area dan membalas dengan detail pelaksanaan.'],
    ];
@endphp

<div class="text-zinc-900 dark:text-white">

    {{-- Hero --}}
    <section class="pt-32 sm:pt-40 pb-16 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 reveal">
        <h1 class="font-editorial text-5xl sm:text-7xl lg:text-8xl leading-[0.92] tracking-editorial-tighter max-w-4xl text-zinc-950 dark:text-white">
            Tempat untuk momen yang ingin kamu rayakan.
        </h1>
        <p class="mt-8 max-w-md text-sm sm:text-base leading-relaxed text-zinc-600 dark:text-zinc-400">
            Dari ulang tahun kecil sampai workshop komunitas, Nucomu menyiapkan ruang, hidangan, dan suasana yang tenang.
        </p>
    </section>

    {{-- Daftar layanan: baris selang-seling --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        @foreach($services as $s)
            @php
                $msg = urlencode("Halo Nucomu Cafe, saya ingin bertanya tentang layanan {$s['title']}.");
                $link = "https://wa.me/{$wa}?text={$msg}";
            @endphp
            <article class="grid md:grid-cols-12 gap-8 md:gap-14 items-center py-12 border-t border-zinc-300 dark:border-zinc-800">
                <div class="md:col-span-6 {{ $loop->even ? 'md:order-2' : '' }}">
                    <div class="aspect-[4/3] overflow-hidden border border-zinc-300 dark:border-zinc-800 {{ $loop->even ? 'rounded-b-[4rem]' : 'rounded-t-[4rem]' }}">
                        <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}" loading="lazy"
                             class="w-full h-full object-cover grayscale hover:grayscale-0 hover:scale-105 transition duration-700">
                    </div>
                </div>
                <div class="md:col-span-6">
                    <p class="text-sm text-zinc-500">{{ $s['category'] }}</p>
                    <h2 class="font-editorial text-4xl sm:text-5xl mt-2 mb-4 leading-tight">{{ $s['title'] }}</h2>
                    <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400 max-w-md">{{ $s['full_desc'] }}</p>
                    <p class="mt-5 text-sm"><span class="text-zinc-500">Area yang disarankan:</span> {{ $s['suggested_area'] }}</p>
                    <a href="{{ $link }}" target="_blank" rel="noopener"
                       class="inline-block mt-6 px-7 py-3.5 rounded-full bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 text-sm font-semibold hover:bg-zinc-700 dark:hover:bg-zinc-300 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-950 dark:focus-visible:outline-white">
                        Tanya layanan ini
                    </a>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Cara memesan: urutan nyata, jadi bernomor --}}
    <section class="bg-zinc-950 text-white mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <h2 class="font-editorial text-4xl sm:text-5xl mb-12 max-w-md">Cara memesan</h2>
            <ol class="grid md:grid-cols-3 gap-10">
                @foreach($steps as $i => [$title, $desc])
                    <li class="border-t border-zinc-700 pt-5">
                        <span class="font-editorial text-6xl text-zinc-700 leading-none">{{ $i + 1 }}</span>
                        <h3 class="font-editorial text-2xl mt-4 mb-2">{{ $title }}</h3>
                        <p class="text-sm leading-relaxed text-zinc-400">{{ $desc }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Penutup --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h2 class="font-editorial text-3xl sm:text-4xl max-w-md">Belum yakin acaranya cocok?</h2>
            <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400">Kami buka {{ $hours }}.</p>
        </div>
        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"
           class="px-7 py-3.5 rounded-full border border-zinc-950 dark:border-white text-sm font-semibold text-center hover:bg-zinc-950 hover:text-white dark:hover:bg-white dark:hover:text-zinc-950 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-950 dark:focus-visible:outline-white">
            Ngobrol lewat WhatsApp
        </a>
    </section>

</div>
@endsection