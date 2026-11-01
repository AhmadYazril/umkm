@extends('layouts.admin')
@section('title', 'Kurir & Pemantauan Zendo — Nucomu Cafe')

@section('content')
@php
    $totalCouriers = count($couriers);
    $connectedCouriers = collect($couriers)->where('is_connected', true);
    $activeOnlineCouriers = collect($couriers)->where('is_online', true);
    $tariff = $txSettings['zendo_tariff'] ?? [];
    $hideLiveTracking = ($settings['hide_live_tracking'] ?? '0') === '1';
@endphp

<div class="max-w-7xl mx-auto space-y-8">

    {{-- Banner Status Fitur Live Tracking (Klien vs Sekolah Quick Toggle) --}}
    <div class="rounded-2xl sm:rounded-3xl p-5 sm:p-6 border border-hairline bg-pure-white flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-card reveal-on-scroll anim-fade-up">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl {{ $hideLiveTracking ? 'bg-warm-stone text-muted-text' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }} flex items-center justify-center shrink-0 text-base font-bold shadow-2xs">
                <i class="fa-solid {{ $hideLiveTracking ? 'fa-eye-slash' : 'fa-map-location-dot' }}"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-xs sm:text-sm font-bold text-ink-black uppercase tracking-wide">
                        Status Fitur Live Tracking:
                        <span class="{{ $hideLiveTracking ? 'text-charcoal' : 'text-emerald-700' }}">
                            {{ $hideLiveTracking ? 'DISEMBUNYIKAN (Mode Klien)' : 'AKTIF PENUH (Mode Sekolah)' }}
                        </span>
                    </h3>
                </div>
                <p class="text-xs text-muted-text mt-1 leading-relaxed max-w-2xl">
                    @if($hideLiveTracking)
                        Saat checkout, pelanggan <strong>langsung diarahkan ke WhatsApp</strong> dengan format <strong>struk kasir ringkas</strong> (tanpa URL link live tracking).
                    @else
                        Pelanggan &amp; kurir dapat mengakses fitur <strong>peta live tracking GPS</strong>, dan link pelacakan disertakan otomatis di pesan WhatsApp.
                    @endif
                </p>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="shrink-0 flex items-center gap-3">
            @csrf
            <input type="hidden" name="hide_live_tracking" value="{{ $hideLiveTracking ? '0' : '1' }}">
            <div class="flex items-center gap-2.5">
                <button type="submit" 
                        title="{{ $hideLiveTracking ? 'Klik untuk aktifkan Peta Live (Mode Sekolah)' : 'Klik untuk sembunyikan Peta (Mode Klien)' }}"
                        class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer rounded-full p-1 transition-all duration-300 ease-in-out focus:outline-none select-none {{ ! $hideLiveTracking ? 'bg-ink-black shadow-xs' : 'bg-[#D9D6CE]' }}">
                    <span class="pointer-events-none inline-block h-6 w-6 rounded-full bg-white shadow-md transform transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ ! $hideLiveTracking ? 'translate-x-6' : 'translate-x-0' }}"></span>
                </button>
                <span class="text-xs font-bold {{ ! $hideLiveTracking ? 'text-ink-black' : 'text-muted-text' }}">
                    {{ ! $hideLiveTracking ? 'Live Tracking ON' : 'Live Tracking OFF' }}
                </span>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="p-2.5 rounded-xl bg-warm-stone hover:bg-hairline text-ink-black text-xs font-bold transition btn-press" title="Buka Pengaturan Lengkap">
                <i class="fa-solid fa-sliders text-xs"></i>
            </a>
        </form>
    </div>

    {{-- Header Banner: Konsep Grab-like Tracking & Anti Fake GPS (Nucomu Monochrome Dark) --}}
    <div class="bg-ink-black rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-white shadow-card border border-ink-black reveal-on-scroll">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-white text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Sistem Pelacakan Live Kurir Zendo (Grab-like) &bull; Nucomu Cafe</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-sans">
                    Pemantauan Kurir &amp; Status Koneksi Nomor Zendo
                </h2>
                <p class="text-xs sm:text-sm text-warm-stone/80 mt-1.5 max-w-3xl leading-relaxed">
                    Admin cukup memantau nomor kurir yang <strong>terhubung</strong> dan melihat <strong>posisi pergerakan kurir di peta secara live</strong>.
                    Perubahan status (ambil pesanan di kafe &amp; antar ke tujuan) dilakukan oleh kurir via <strong>Link Kurir Khusus</strong> dengan proteksi <strong>GPS Lock</strong>.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="#section-pengaturan-kurir"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-warm-stone text-ink-black font-bold text-xs transition shadow-xs btn-press">
                    <i class="fa-solid fa-users text-xs"></i>
                    <span>Kelola Kurir ({{ $totalCouriers }})</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition border border-white/15 btn-press">
                    <i class="fa-solid fa-sliders text-xs"></i>
                    <span>Pengaturan</span>
                </a>
            </div>
        </div>

        {{-- Alur Grab-like Zendo (3 Kolom Monochrome) --}}
        <div class="mt-6 pt-5 border-t border-white/10 grid grid-cols-1 md:grid-cols-3 gap-3.5 text-xs">
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-link text-xs text-warm-stone"></i>
                    <span>1. Link Kurir &amp; Seleksi Nomor</span>
                </div>
                <p class="text-warm-stone/70 mt-1.5 leading-relaxed">
                    Setiap order menghasilkan <strong>Link Kurir</strong> yang hanya bisa dibuka nomor kurir bertugas setelah verifikasi WhatsApp.
                </p>
            </div>
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-lock text-xs text-warm-stone"></i>
                    <span>2. GPS Lock (Anti Fake-GPS)</span>
                </div>
                <p class="text-warm-stone/70 mt-1.5 leading-relaxed">
                    Kurir menyiarkan koordinat akurasi tinggi. Tombol ambil pesanan hanya aktif di kafe (&le; 500m) dan tombol selesai hanya aktif di tujuan (&le; 200m).
                </p>
            </div>
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="font-bold text-white flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-xs text-warm-stone"></i>
                        <span>3. Link Peta Live Tracking</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-white/15 text-white text-[9.5px] font-mono">Live Map</span>
                </div>
                <p class="text-warm-stone/70 mt-1.5 leading-relaxed">
                    Pelanggan dan admin dapat membuka peta live untuk memantau perjalanan kurir secara visual dan real-time.
                </p>
            </div>
        </div>
    </div>

    {{-- 4 Kartu Metrik Kurir (Warm Monochrome Grid) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 reveal-on-scroll">
        <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline p-5 shadow-card card-lift">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-mono uppercase tracking-wider text-muted-text font-semibold">Total Kurir</span>
                <span class="w-8 h-8 rounded-xl bg-warm-stone border border-hairline text-ink-black flex items-center justify-center font-bold text-xs font-mono">#{{ $totalCouriers }}</span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-ink-black font-mono tracking-tight mt-2">{{ $totalCouriers }} <span class="text-xs font-normal text-muted-text font-sans">Kurir</span></p>
            <p class="text-[11px] text-muted-text mt-1">Nomor WhatsApp terdaftar</p>
        </div>

        <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline p-5 shadow-card card-lift">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-mono uppercase tracking-wider text-muted-text font-semibold">Nomor Terhubung</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-800 font-mono tracking-tight mt-2">{{ $connectedCouriers->count() }} <span class="text-xs font-normal text-muted-text font-sans">Verified</span></p>
            <p class="text-[11px] text-muted-text mt-1">Perangkat terikat ke sistem</p>
        </div>

        <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline p-5 shadow-card card-lift">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-mono uppercase tracking-wider text-muted-text font-semibold">Kurir Online GPS</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-ink-black font-mono tracking-tight mt-2">{{ $activeOnlineCouriers->count() }} <span class="text-xs font-normal text-muted-text font-sans">Online</span></p>
            <p class="text-[11px] text-muted-text mt-1">Sinyal GPS real-time aktif</p>
        </div>

        <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline p-5 shadow-card card-lift">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-mono uppercase tracking-wider text-muted-text font-semibold">Pengantaran Aktif</span>
                <span class="w-2.5 h-2.5 rounded-full bg-charcoal"></span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-ink-black font-mono tracking-tight mt-2">{{ collect($couriers)->sum('active_orders_count') }} <span class="text-xs font-normal text-muted-text font-sans">Order</span></p>
            <p class="text-[11px] text-muted-text mt-1">Dalam proses pengiriman</p>
        </div>
    </div>

    {{-- SECTION 1: Status Koneksi Nomor Setiap Kurir & Tautan Pemantauan --}}
    <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline shadow-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-hairline flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-warm-stone/40">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-ink-black flex items-center gap-2">
                    <i class="fa-solid fa-mobile-screen-button text-charcoal"></i>
                    <span>Status Koneksi Nomor Kurir &amp; Tautan Pemantauan Langsung</span>
                </h3>
                <p class="text-xs text-muted-text mt-0.5">
                    Admin memantau nomor kurir terhubung. Seluruh penanganan order diserahkan ke kurir via Link Kurir.
                </p>
            </div>
            <a href="{{ route('admin.zendo.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-hairline bg-white hover:bg-warm-stone text-xs font-bold text-ink-black transition self-start sm:self-auto shadow-2xs btn-press">
                <i class="fa-solid fa-arrows-rotate text-xs"></i>
                <span>Refresh Status</span>
            </a>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($couriers as $courier)
                @php
                    $isConnected = $courier['is_connected'] ?? false;
                    $isOnline = $courier['is_online'] ?? false;
                    $activeDeliveries = $courier['active_deliveries'] ?? [];
                @endphp

                <div class="rounded-2xl border {{ $isConnected ? 'border-emerald-200 bg-emerald-50/20' : 'border-hairline bg-warm-ivory' }} p-5 flex flex-col justify-between gap-4 shadow-xs transition hover:border-ink-black/30">
                    <div>
                        {{-- Baris Header Kurir --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-ink-black text-white font-extrabold text-xs font-mono flex items-center justify-center shrink-0 shadow-2xs">
                                    #{{ $courier['index'] }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-ink-black text-sm sm:text-base truncate">{{ $courier['name'] }}</h4>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-mono font-bold text-charcoal bg-white border border-hairline px-2 py-0.5 rounded-md">
                                            📱 {{ $courier['phone'] ?: '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Badge Koneksi Nomor --}}
                            @if($isConnected)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terhubung
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-warm-stone text-muted-text border border-hairline shrink-0">
                                    Belum Terhubung
                                </span>
                            @endif
                        </div>

                        {{-- Status GPS Kurir --}}
                        <div class="mt-3 pt-3 border-t border-hairline flex items-center justify-between text-xs text-muted-text">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-satellite-dish text-[11px] {{ $isOnline ? 'text-emerald-600' : 'text-muted-text' }}"></i>
                                <span>Status GPS:</span>
                            </span>
                            <span class="font-bold {{ $isOnline ? 'text-emerald-700' : 'text-charcoal' }}">
                                {{ $isOnline ? '🟢 GPS Aktif Online' : ($isConnected ? '💤 Standby' : '⚪ Menunggu Kurir') }}
                            </span>
                        </div>
                    </div>

                    {{-- Footer Kurir: Chat WA --}}
                    <div class="pt-3 border-t border-hairline flex items-center justify-between text-xs">
                        <span class="text-muted-text text-[11px]">Selesai hari ini: <strong class="text-ink-black font-mono">{{ $courier['completed_today_count'] ?? 0 }}</strong></span>
                        @if(!empty($courier['clean_phone']))
                            <a href="https://wa.me/{{ $courier['clean_phone'] }}" target="_blank"
                               class="text-emerald-800 font-bold hover:underline flex items-center gap-1 text-xs">
                                <i class="fa-brands fa-whatsapp text-sm text-emerald-600"></i>
                                <span>Hubungi Kurir ↗</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- SECTION: Tabel Perangkat yang Terhubung Menjadi Kurir (Bisa Reset) --}}
    <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline shadow-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-hairline flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-warm-stone/40">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-ink-black flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-ink-black text-white flex items-center justify-center text-xs shadow-2xs">
                        <i class="fa-solid fa-mobile-screen"></i>
                    </span>
                    <span>Tabel Perangkat Terhubung sebagai Kurir ({{ count($connectedDevices) }} Perangkat)</span>
                </h3>
                <p class="text-xs text-muted-text mt-0.5">
                    Daftar HP / browser kurir yang memegang akses permanen Mode Kurir. Admin dapat <strong>mereset perangkat</strong> untuk mengeluarkan kurir sewaktu-waktu.
                </p>
            </div>
            @if(count($connectedDevices) > 0)
                <form method="POST" action="{{ route('admin.zendo.devices.resetAll') }}"
                      onsubmit="return confirm('⚠️ PERINGATAN: Apakah Anda yakin ingin mereset dan mengeluarkan SEMUA perangkat kurir?\n\nSeluruh HP kurir akan langsung dikeluarkan dari Mode Kurir dan wajib memasukkan PIN baru untuk masuk kembali.');">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-pure-white hover:bg-rose-50 text-rose-700 hover:text-rose-800 border border-hairline text-xs font-bold transition shadow-2xs btn-press">
                        <i class="fa-solid fa-power-off text-xs"></i>
                        <span>Reset Semua Perangkat Kurir</span>
                    </button>
                </form>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal">
                <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                    <tr>
                        <th class="py-3.5 px-6">Kurir Zendo</th>
                        <th class="py-3.5 px-6">Perangkat &amp; Browser</th>
                        <th class="py-3.5 px-6">Alamat IP</th>
                        <th class="py-3.5 px-6">Waktu Terhubung</th>
                        <th class="py-3.5 px-6">Status &amp; Sinyal Terakhir</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($connectedDevices as $dev)
                        <tr class="hover:bg-warm-stone/30 transition-colors">
                            <td class="py-3.5 px-6 align-middle">
                                <div class="font-bold text-ink-black flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-md bg-ink-black text-white font-extrabold text-[10px] flex items-center justify-center">
                                        <i class="fa-solid fa-user-check text-[9px]"></i>
                                    </span>
                                    <span>{{ $dev['courier_name'] ?? 'Kurir Zendo' }}</span>
                                </div>
                                <div class="inline-flex items-center gap-1 font-mono text-[10px] font-semibold text-charcoal bg-warm-stone px-2 py-0.5 rounded border border-hairline mt-1">
                                    📱 +{{ $dev['courier_phone'] ?? '-' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-6 align-middle">
                                <div class="font-semibold text-ink-black flex items-center gap-1.5">
                                    <i class="fa-solid {{ str_contains(strtolower($dev['device_name'] ?? ''), 'windows') || str_contains(strtolower($dev['device_name'] ?? ''), 'mac') ? 'fa-laptop' : 'fa-mobile-screen' }} text-muted-text"></i>
                                    <span>{{ $dev['device_name'] ?? 'Perangkat Kurir' }}</span>
                                </div>
                                @if(!empty($dev['token_preview']))
                                    <span class="text-[10px] text-muted-text font-mono">Token: {{ $dev['token_preview'] }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 align-middle font-mono text-muted-text text-[11px]">
                                {{ $dev['ip_address'] ?? '-' }}
                            </td>
                            <td class="py-3.5 px-6 align-middle">
                                <span class="font-semibold text-ink-black">{{ $dev['connected_at_formatted'] ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-6 align-middle">
                                @if($dev['is_online'] ?? false)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        🟢 Online (GPS Aktif)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-warm-stone text-charcoal border border-hairline">
                                        <span class="w-1.5 h-1.5 rounded-full bg-muted-text"></span>
                                        💤 Standby
                                    </span>
                                @endif
                                <p class="text-[10px] text-muted-text mt-0.5">{{ $dev['last_active_diff'] ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-6 align-middle text-right">
                                <form method="POST" action="{{ route('admin.zendo.devices.reset', $dev['id']) }}"
                                      onsubmit="return confirm('Keluarkan perangkat {{ addslashes($dev['device_name'] ?? 'ini') }} dari Mode Kurir?\n\nKurir (+{{ $dev['courier_phone'] ?? '' }}) harus memasukkan PIN lagi jika ingin mengakses kembali.');">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-pure-white hover:bg-rose-50 text-rose-700 border border-hairline font-bold text-xs transition shadow-2xs btn-press">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                        <span>Reset / Keluarkan</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-muted-text text-xs">
                                <div class="w-12 h-12 rounded-2xl bg-warm-stone text-muted-text flex items-center justify-center mx-auto mb-2 text-base">
                                    <i class="fa-solid fa-mobile-screen"></i>
                                </div>
                                <p class="font-bold text-ink-black">Belum ada perangkat kurir yang terhubung</p>
                                <p class="text-[11px] text-muted-text mt-0.5">Perangkat HP/browser kurir akan otomatis tercatat di sini begitu kurir memasukkan PIN pada link pesanan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: Daftar Semua Pengantaran Zendo & Tombol Buka Peta Live --}}
    <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline shadow-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-hairline flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 bg-warm-stone/40">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-ink-black">Riwayat &amp; Pemantauan Pengantaran Zendo</h3>
                <p class="text-xs text-muted-text mt-0.5">Buka peta live untuk melihat posisi kurir sudah sampai mana dan rute dari mana kurir berjalan.</p>
            </div>

            {{-- Form Filter --}}
            <form method="GET" action="{{ route('admin.zendo.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="courier_phone" class="px-3 py-2 rounded-xl border border-hairline text-xs bg-white text-ink-black focus:outline-none focus:border-ink-black">
                    <option value="">Semua Nomor Kurir</option>
                    @foreach($couriers as $c)
                        @if(!empty($c['clean_phone']))
                            <option value="{{ $c['clean_phone'] }}" @selected($filterPhone === $c['clean_phone'])>
                                #{{ $c['index'] }} {{ $c['name'] }} (+{{ $c['clean_phone'] }})
                            </option>
                        @endif
                    @endforeach
                </select>

                <select name="status" class="px-3 py-2 rounded-xl border border-hairline text-xs bg-white text-ink-black focus:outline-none focus:border-ink-black">
                    <option value="all" @selected($filterStatus === 'all')>Semua Status</option>
                    <option value="assigned" @selected($filterStatus === 'assigned')>Menunggu Kurir</option>
                    <option value="picked_up" @selected($filterStatus === 'picked_up')>Dalam Perjalanan</option>
                    <option value="almost_done" @selected($filterStatus === 'almost_done')>Hampir Sampai</option>
                    <option value="completed" @selected($filterStatus === 'completed')>Selesai</option>
                </select>

                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari kode / nama / alamat..."
                       class="px-3 py-2 rounded-xl border border-hairline text-xs bg-white text-ink-black focus:outline-none focus:border-ink-black w-48">

                <button type="submit" class="px-4 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold transition shadow-xs btn-press">
                    Filter
                </button>
                @if($filterPhone !== '' || $filterStatus !== 'all' || $searchQuery !== '')
                    <a href="{{ route('admin.zendo.index') }}" class="px-3 py-2 rounded-xl border border-hairline text-xs text-muted-text hover:text-ink-black bg-white">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal">
                <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                    <tr>
                        <th class="py-3.5 px-6">Kode &amp; Waktu</th>
                        <th class="py-3.5 px-6">Kurir Zendo</th>
                        <th class="py-3.5 px-6">Pelanggan &amp; Alamat Tujuan</th>
                        <th class="py-3.5 px-6">Status &amp; Posisi Kurir</th>
                        <th class="py-3.5 px-6 text-right">Peta Pelacakan (Grab-like)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($deliveries as $deliv)
                        <tr class="hover:bg-warm-stone/30 transition-colors">
                            <td class="py-3.5 px-6 align-top">
                                <span class="font-mono font-bold text-ink-black block text-sm">#{{ $deliv->order_code }}</span>
                                <p class="text-[10px] text-muted-text font-mono mt-0.5">{{ $deliv->created_at ? $deliv->created_at->format('d M, H:i') : '-' }}</p>
                            </td>
                            <td class="py-3.5 px-6 align-top">
                                <div class="font-bold text-ink-black">{{ $deliv->courier_name }}</div>
                                <div class="inline-flex items-center gap-1 font-mono text-[10px] font-semibold text-charcoal bg-warm-stone px-2 py-0.5 rounded border border-hairline mt-1">
                                    📱 +{{ $deliv->courier_phone }}
                                </div>
                                <div class="text-[10px] text-muted-text mt-1">
                                    {{ $deliv->courier_verified_at ? '✅ Nomor Terverifikasi' : '⏳ Menunggu Buka Link' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-6 align-top max-w-xs">
                                <p class="font-bold text-ink-black">{{ $deliv->customer_name ?: 'Pelanggan' }}</p>
                                <p class="text-charcoal mt-0.5 leading-snug">📍 {{ $deliv->delivery_address ?: '-' }}</p>
                            </td>
                            <td class="py-3.5 px-6 align-top">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold text-[10px] uppercase border
                                    {{ $deliv->status === 'completed' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($deliv->status === 'almost_done' ? 'bg-amber-50 text-amber-900 border-amber-200' : ($deliv->status === 'picked_up' ? 'bg-blue-50 text-blue-900 border-blue-200' : 'bg-warm-stone text-charcoal border-hairline')) }}">
                                    {{ $deliv->status_label }}
                                </span>
                                @if($deliv->hasCourierLocation())
                                    <p class="text-[10px] text-muted-text mt-1">
                                        GPS: {{ $deliv->location_updated_at ? $deliv->location_updated_at->diffForHumans() : '-' }}
                                    </p>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 align-top text-right">
                                <div class="inline-flex flex-col items-end gap-1.5">
                                    <a href="{{ route('zendo.track', $deliv->tracking_token) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs shadow-xs transition btn-press">
                                        <i class="fa-solid fa-map-location-dot text-xs"></i>
                                        <span>Buka Peta Live ↗</span>
                                    </a>
                                    <a href="{{ route('zendo.courier.show', $deliv->courier_token) }}" target="_blank"
                                       class="text-[10px] text-muted-text hover:text-ink-black font-semibold"
                                       title="Buka langsung sebagai Workspace Kurir">
                                        <i class="fa-solid fa-motorcycle text-[10px] mr-0.5"></i> Buka Mode Kurir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-muted-text text-xs">
                                Belum ada pengantaran kurir Zendo yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveries->hasPages())
            <div class="px-6 py-4 border-t border-hairline">
                {{ $deliveries->links() }}
            </div>
        @endif
    </div>

    {{-- SECTION 3: Pengaturan Multi-Nomor HP Kurir Zendo --}}
    <div id="section-pengaturan-kurir" class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline shadow-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 bg-warm-stone/40 border-b border-hairline flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-ink-black">Pengaturan Jumlah &amp; Nomor WhatsApp Kurir Zendo</h3>
                <p class="text-xs text-muted-text mt-0.5">
                    Atur berapa jumlah kurir Zendo dan tentukan nomor WhatsApp masing-masing kurir.
                </p>
            </div>
            <button type="button" id="btnAddCourierRow"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold transition shadow-xs btn-press">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kurir Zendo</span>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.zendo.couriers.update') }}" class="p-6 space-y-6">
            @csrf

            {{-- Pengaturan PIN Kurir Zendo (Akses Permanen) --}}
            <div class="p-4 sm:p-5 rounded-2xl border border-hairline bg-warm-ivory shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <label for="zendoCourierPinInput" class="text-xs font-bold text-ink-black flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-ink-black text-white flex items-center justify-center text-xs">
                                <i class="fa-solid fa-key text-[10px]"></i>
                            </span>
                            <span>PIN Akses Mode Kurir (Dapat Diatur Bebas oleh Admin)</span>
                        </label>
                        <p class="text-[11px] text-muted-text leading-relaxed max-w-xl">
                            Kurir memasukkan PIN ini <strong>hanya 1 kali</strong> di HP saat pertama kali membuka link. Setelah PIN diverifikasi, HP kurir <strong>otomatis terakses permanen</strong> untuk order-order selanjutnya.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div class="text-right sm:text-left">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-muted-text mb-1">PIN Kurir (Angka)</span>
                            <input type="text" name="zendo_courier_pin" id="zendoCourierPinInput"
                                   value="{{ old('zendo_courier_pin', $settings['zendo_courier_pin'] ?? '1234') }}"
                                   inputmode="numeric" pattern="[0-9]*" maxlength="10" placeholder="1234" required
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                   class="w-32 px-3 py-2 rounded-xl border border-hairline bg-white text-center font-mono font-bold text-base text-ink-black focus:border-ink-black focus:outline-none tracking-widest shadow-xs">
                            <span class="block text-[10px] text-muted-text font-medium mt-0.5">Wajib angka (0-9)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline">
                <div>
                    <label for="zendoCourierCountInput" class="block text-xs font-bold text-ink-black">Jumlah Kurir Zendo Aktif</label>
                    <p class="text-[11px] text-muted-text mt-0.5">Ubah angka ini untuk menambah baris nomor HP kurir.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="btnDecCourierCount"
                            class="w-9 h-9 rounded-xl border border-hairline bg-pure-white hover:bg-warm-stone font-bold text-ink-black flex items-center justify-center transition btn-press">
                        −
                    </button>
                    <input type="number" name="zendo_courier_count" id="zendoCourierCountInput"
                           value="{{ $totalCouriers }}" min="1" max="50"
                           class="w-20 px-3 py-2 rounded-xl border border-hairline bg-white text-center font-bold text-sm text-ink-black font-mono focus:outline-none focus:border-ink-black">
                    <button type="button" id="btnIncCourierCount"
                            class="w-9 h-9 rounded-xl bg-ink-black hover:bg-charcoal font-bold text-white flex items-center justify-center transition btn-press">
                        +
                    </button>
                </div>
            </div>

            <div id="zendoCouriersFormList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($couriers as $idx => $courier)
                    <div class="courier-form-card rounded-2xl border border-hairline bg-warm-ivory p-4 space-y-3" data-courier-row="{{ $idx }}">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-ink-black text-white text-xs font-bold font-mono">
                                Kurir Zendo #<span class="courier-num-label">{{ $idx + 1 }}</span>
                            </span>
                            @if($courier['is_connected'] ?? false)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    🟢 Nomor Terhubung
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-warm-stone text-muted-text border border-hairline">
                                    ⚪ Belum Verifikasi
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-charcoal mb-1">Nama / Label Kurir</label>
                                <input type="text"
                                       name="zendo_couriers[{{ $idx }}][name]"
                                       value="{{ $courier['name'] }}"
                                       placeholder="Kurir Zendo {{ $idx + 1 }}"
                                       required
                                       class="w-full px-3 py-2 rounded-xl border border-hairline bg-white text-xs font-semibold text-ink-black focus:border-ink-black focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-charcoal mb-1">Nomor WhatsApp Kurir #{{ $idx + 1 }}</label>
                                <input type="text"
                                       name="zendo_couriers[{{ $idx }}][phone]"
                                       value="{{ $courier['phone'] }}"
                                       placeholder="Contoh: 628123456789{{ $idx + 1 }}"
                                       required
                                       class="courier-phone-field w-full px-3 py-2 rounded-xl border border-hairline bg-white text-xs font-mono font-bold text-ink-black focus:border-ink-black focus:outline-none">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-hairline">
                <p class="text-xs text-muted-text">
                    💡 Setiap nomor HP kurir disimpan terpisah dan digunakan untuk otentikasi seleksi nomor saat kurir membuka link tugasnya.
                </p>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs shrink-0 btn-press">
                    Simpan Semua Nomor Kurir Zendo
                </button>
            </div>
        </form>
    </div>

    {{-- SECTION 4: Pengaturan Tarif Ongkir Zendo Berdasarkan Jarak & Titik Kafe --}}
    <div class="bg-pure-white rounded-2xl sm:rounded-3xl border border-hairline shadow-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-hairline bg-warm-stone/40">
            <h3 class="text-sm sm:text-base font-bold text-ink-black">Pengaturan Tarif Ongkir Zendo (Berdasarkan Jarak Kafe) &amp; Gateway WA</h3>
            <p class="text-xs text-muted-text mt-0.5">Atur tarif per kilometer dan koordinat titik Nucomu Cafe.</p>
        </div>

        <form method="POST" action="{{ route('admin.zendo.tariff.update') }}" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">0 – 3 km (Rp)</label>
                    <input type="number" name="zendo_tier_0_3" value="{{ $tariff['tier_0_3'] ?? 10000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-bold text-ink-black font-mono focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">3,1 – 4 km (Rp)</label>
                    <input type="number" name="zendo_tier_3_4" value="{{ $tariff['tier_3_4'] ?? 12000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-bold text-ink-black font-mono focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">4,1 – 5 km (Rp)</label>
                    <input type="number" name="zendo_tier_4_5" value="{{ $tariff['tier_4_5'] ?? 15000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-bold text-ink-black font-mono focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">5,1 – 6 km (Rp)</label>
                    <input type="number" name="zendo_tier_5_6" value="{{ $tariff['tier_5_6'] ?? 17000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-bold text-ink-black font-mono focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">6,1 – 7 km (Rp)</label>
                    <input type="number" name="zendo_tier_6_7" value="{{ $tariff['tier_6_7'] ?? 18000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-bold text-ink-black font-mono focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">&gt; 7 km (+Rp/km)</label>
                    <input type="number" name="zendo_extra_per_km" value="{{ $tariff['extra_per_km'] ?? 2500 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-bold text-ink-black font-mono focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-3 border-t border-hairline">
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">Latitude Nucomu Cafe</label>
                    <input type="text" name="cafe_lat" value="{{ $txSettings['cafe_lat'] ?? '-8.0765099' }}"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-mono text-ink-black focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">Longitude Nucomu Cafe</label>
                    <input type="text" name="cafe_lng" value="{{ $txSettings['cafe_lng'] ?? '111.918712' }}"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs font-mono text-ink-black focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">Endpoint API WA Gateway (Opsional)</label>
                    <input type="text" name="zendo_wa_api_url" value="{{ $txSettings['zendo_wa_api_url'] ?? 'https://api.fonnte.com/send' }}"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs text-ink-black focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">Token API WA Gateway (Opsional)</label>
                    <input type="password" name="zendo_wa_api_token" value="{{ $txSettings['zendo_wa_api_token'] ?? '' }}"
                           placeholder="Isi token Fonnte jika ada"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs text-ink-black focus:bg-white focus:border-ink-black focus:outline-none">
                </div>
                <div class="sm:col-span-4">
                    <label class="block text-[11px] font-bold text-charcoal mb-1">
                        Domain / URL Publik Aplikasi (Agar Link WhatsApp Otomatis Bisa Diklik)
                    </label>
                    <input type="text" name="zendo_public_url" value="{{ $txSettings['zendo_public_url'] ?? '' }}"
                           placeholder="Contoh: https://xxx.ngrok-free.app atau https://nucomucafe.com (Kosongkan jika pakai default)"
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-warm-ivory text-xs text-ink-black focus:bg-white focus:border-ink-black focus:outline-none">
                    <p class="text-[10px] text-muted-text mt-1.5 leading-relaxed">
                        <i class="fa-solid fa-circle-info text-ink-black"></i> <strong>Penting:</strong> WhatsApp membatasi klik langsung pada tautan yang menggunakan alamat IP lokal mentah (seperti <code>192.168.x.x</code>). Jika Anda menggunakan Ngrok, Cloudflare Tunnel, atau domain website, masukkan alamatnya di sini agar link di WhatsApp bisa langsung diklik kurir.
                    </p>
                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-hairline">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press">
                    Simpan Tarif &amp; Pengaturan Zendo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const countInput = document.getElementById('zendoCourierCountInput');
    const listContainer = document.getElementById('zendoCouriersFormList');
    const btnAdd = document.getElementById('btnAddCourierRow');
    const btnInc = document.getElementById('btnIncCourierCount');
    const btnDec = document.getElementById('btnDecCourierCount');

    if (!countInput || !listContainer) return;

    function createCourierCard(index) {
        const num = index + 1;
        const card = document.createElement('div');
        card.className = 'courier-form-card rounded-2xl border border-hairline bg-warm-ivory p-4 space-y-3';
        card.setAttribute('data-courier-row', String(index));
        card.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-ink-black text-white text-xs font-bold font-mono">
                    Kurir Zendo #<span class="courier-num-label">${num}</span>
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-warm-stone text-muted-text border border-hairline">
                    ⚪ Belum Verifikasi
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">Nama / Label Kurir</label>
                    <input type="text"
                           name="zendo_couriers[${index}][name]"
                           value="Kurir Zendo ${num}"
                           placeholder="Kurir Zendo ${num}"
                           required
                           class="w-full px-3 py-2 rounded-xl border border-hairline bg-white text-xs font-semibold text-ink-black focus:border-ink-black focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-charcoal mb-1">Nomor WhatsApp Kurir #${num}</label>
                    <input type="text"
                           name="zendo_couriers[${index}][phone]"
                           value=""
                           placeholder="Masukkan nomor WA Kurir #${num} (misal: 0812...)"
                           required
                           class="courier-phone-field w-full px-3 py-2 rounded-xl border border-hairline bg-white text-xs font-mono font-bold text-ink-black focus:border-ink-black focus:outline-none">
                </div>
            </div>
        `;
        return card;
    }

    function syncRowsToCount(targetCount) {
        const count = Math.max(1, Math.min(50, parseInt(targetCount || 1, 10)));
        countInput.value = count;

        const currentCards = listContainer.querySelectorAll('.courier-form-card');
        if (currentCards.length < count) {
            for (let i = currentCards.length; i < count; i++) {
                const newCard = createCourierCard(i);
                listContainer.appendChild(newCard);
                const phoneInput = newCard.querySelector('.courier-phone-field');
                if (phoneInput && i === count - 1) {
                    phoneInput.focus();
                }
            }
        } else if (currentCards.length > count) {
            for (let i = currentCards.length - 1; i >= count; i--) {
                currentCards[i].remove();
            }
        }
    }

    countInput.addEventListener('change', function () {
        syncRowsToCount(this.value);
    });

    if (btnAdd) {
        btnAdd.addEventListener('click', function () {
            syncRowsToCount(parseInt(countInput.value || 1, 10) + 1);
        });
    }
    if (btnInc) {
        btnInc.addEventListener('click', function () {
            syncRowsToCount(parseInt(countInput.value || 1, 10) + 1);
        });
    }
    if (btnDec) {
        btnDec.addEventListener('click', function () {
            syncRowsToCount(parseInt(countInput.value || 1, 10) - 1);
        });
    }
});
</script>
@endsection
