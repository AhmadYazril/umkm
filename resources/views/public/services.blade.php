@extends('layouts.app')
@section('title', 'Layanan — Nucomu Cafe')

@section('content')
@php
    $services = config('nucomu.services');
    $contact = \App\Http\Controllers\NucomuController::getContactSettings();
    $wa = $contact['whatsapp_clean'] ?? config('nucomu.contact.whatsapp_number', '6281234567890');
    $hours = $contact['hours'] ?? config('nucomu.contact.hours');
    $steps = [
        ['Pilih layanan', 'Tentukan jenis acara atau area yang kamu butuhkan.'],
        ['Hubungi kami', 'Kirim tanggal, jumlah tamu, dan kebutuhanmu lewat WhatsApp.'],
        ['Terima konfirmasi', 'Kami cek ketersediaan area dan membalas dengan detail pelaksanaan.'],
    ];
@endphp

<div class="bg-[var(--bg-page)] text-[var(--text)]">

    {{-- Hero --}}
    <section class="pt-32 sm:pt-40 pb-16 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 reveal">
        <h1 class="font-editorial text-5xl sm:text-7xl lg:text-8xl leading-[0.92] tracking-editorial-tighter max-w-4xl text-[var(--text)]">
            Tempat untuk momen yang ingin kamu rayakan.
        </h1>
        <p class="mt-8 max-w-md text-sm sm:text-base leading-relaxed text-[var(--text-muted)]">
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
            <article class="grid md:grid-cols-12 gap-8 md:gap-14 items-center py-12 border-t border-[var(--border-soft)]">
                <div class="md:col-span-6 {{ $loop->even ? 'md:order-2' : '' }}">
                    <div class="aspect-[4/3] overflow-hidden border border-[var(--border)] bg-[var(--media-bg)] {{ $loop->even ? 'rounded-b-[4rem]' : 'rounded-t-[4rem]' }}">
                        <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}" loading="lazy"
                             class="w-full h-full object-cover grayscale hover:grayscale-0 hover:scale-105 transition duration-700">
                    </div>
                </div>
                <div class="md:col-span-6">
                    <p class="text-sm text-[var(--text-muted)]">{{ $s['category'] }}</p>
                    <h2 class="font-editorial text-4xl sm:text-5xl mt-2 mb-4 leading-tight text-[var(--text)]">{{ $s['title'] }}</h2>
                    <p class="text-sm leading-relaxed text-[var(--text-muted)] max-w-md">{{ $s['full_desc'] }}</p>
                    <p class="mt-5 text-sm text-[var(--text)]"><span class="text-[var(--text-muted)]">Area yang disarankan:</span> {{ $s['suggested_area'] }}</p>
                    <a href="{{ $link }}" target="_blank" rel="noopener"
                       class="inline-block mt-6 px-7 py-3.5 rounded-full bg-[var(--accent-solid)] text-[var(--accent-solid-text)] text-sm font-semibold hover:bg-[var(--accent-solid-hover)] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--field-focus)]">
                        Tanya layanan ini
                    </a>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Cara memesan: urutan nyata, jadi bernomor --}}
    <section class="bg-[var(--bg-section-alt)] border-y border-[var(--border-soft)] text-[var(--text)] mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <h2 class="font-editorial text-4xl sm:text-5xl mb-12 max-w-md text-[var(--text)]">Cara memesan</h2>
            <ol class="grid md:grid-cols-3 gap-10">
                @foreach($steps as $i => [$title, $desc])
                    <li class="border-t border-[var(--border)] pt-5">
                        <span class="font-editorial text-6xl text-[var(--text-faint)] leading-none">{{ $i + 1 }}</span>
                        <h3 class="font-editorial text-2xl mt-4 mb-2 text-[var(--text)]">{{ $title }}</h3>
                        <p class="text-sm leading-relaxed text-[var(--text-muted)]">{{ $desc }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Penutup --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h2 class="font-editorial text-3xl sm:text-4xl max-w-md text-[var(--text)]">Belum yakin acaranya cocok?</h2>
            <p class="mt-3 text-sm text-[var(--text-muted)]">Kami buka {{ $hours }}.</p>
        </div>
        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"
           class="px-7 py-3.5 rounded-full border border-[var(--btn-outline)] text-[var(--text)] text-sm font-semibold text-center hover:bg-[var(--accent-solid)] hover:text-[var(--accent-solid-text)] hover:border-[var(--accent-solid)] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--field-focus)]">
            Ngobrol lewat WhatsApp
        </a>
    </section>

</div>
@endsection