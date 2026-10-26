@extends('layouts.admin')
@section('title', 'Kurir & Pemantauan Zendo')

@section('content')
@php
    $totalCouriers = count($couriers);
    $connectedCouriers = collect($couriers)->where('is_connected', true);
    $activeOnlineCouriers = collect($couriers)->where('is_online', true);
    $tariff = $txSettings['zendo_tariff'] ?? [];
@endphp

<div class="max-w-7xl space-y-6">

    {{-- Header Banner: Konsep Grab-like Tracking & Anti Fake GPS --}}
    <div class="bg-gradient-to-br from-[#2B1F1A] via-[#3A2820] to-[#4A3328] rounded-2xl p-6 text-white shadow-lg border border-amber-900/30">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Pelacakan Live Kurir Zendo (Grab-like) & Anti Fake-GPS
                </div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight">Pemantauan Kurir & Status Koneksi Nomor Zendo</h2>
                <p class="text-xs sm:text-sm text-amber-100/80 mt-1 max-w-3xl leading-relaxed">
                    Admin cukup memantau apakah nomor kurir <strong>sudah terhubung</strong> dan melihat <strong>posisi pergerakan kurir dari mana di peta secara live</strong>.
                    Perubahan status (ambil pesanan di kafe & selesaikan pengantaran di tujuan) sepenuhnya dilakukan oleh kurir melalui <strong>Link Kurir Khusus</strong> dengan proteksi <strong>GPS Lock</strong>.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="#section-pengaturan-kurir"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 font-bold text-xs transition shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Kelola Nomor Kurir ({{ $totalCouriers }} Kurir)
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition border border-white/15">
                    Pengaturan Sistem
                </a>
            </div>
        </div>

        {{-- Alur Grab-like Zendo --}}
        <div class="mt-5 pt-4 border-t border-white/10 grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
            <div class="bg-white/5 rounded-xl p-3 border border-white/10">
                <div class="font-bold text-emerald-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-link"></i>
                    <span>1. Link Kurir & Seleksi Nomor</span>
                </div>
                <p class="text-amber-100/75 mt-1 leading-relaxed">
                    Setiap order menghasilkan <strong>Link Kurir</strong> yang <em>hanya bisa dibuka oleh nomor kurir yang ditugaskan</em> setelah verifikasi nomor WhatsApp.
                </p>
            </div>
            <div class="bg-white/5 rounded-xl p-3 border border-white/10">
                <div class="font-bold text-amber-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-lock"></i>
                    <span>2. GPS Lock (Anti Fake-GPS)</span>
                </div>
                <p class="text-amber-100/75 mt-1 leading-relaxed">
                    Kurir wajib menyiarkan lokasi GPS akurasi tinggi. Tombol ambil pesanan hanya aktif di kafe (&le; 500m) dan tombol selesai hanya aktif di lokasi tujuan (&le; 200m).
                </p>
            </div>
            <div class="bg-emerald-950/50 rounded-xl p-3 border border-emerald-400/30">
                <div class="font-bold text-emerald-200 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>3. Link Pemantauan Live</span>
                    </span>
                    <span class="px-2 py-0.5 rounded bg-emerald-500/30 text-emerald-200 text-[10px]">Pelanggan & Admin</span>
                </div>
                <p class="text-amber-100/75 mt-1 leading-relaxed">
                    Pelanggan dan admin dapat membuka peta live untuk melihat kurir sudah sampai mana dan rute perjalanan dari mana secara real-time.
                </p>
            </div>
        </div>
    </div>

    {{-- Statistik Kurir & Koneksi Nomor --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/80 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500">Total Kurir Terdaftar</span>
                <span class="w-8 h-8 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center font-bold text-xs">#{{ $totalCouriers }}</span>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ $totalCouriers }} <span class="text-xs font-medium text-gray-500">Kurir</span></p>
            <p class="text-[11px] text-gray-400 mt-1">Nomor WhatsApp dikonfigurasi</p>
        </div>

        <div class="bg-emerald-50/70 rounded-2xl border border-emerald-200 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-800">Nomor Kurir Terhubung</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 mt-2">{{ $connectedCouriers->count() }} <span class="text-xs font-medium text-emerald-600">Terverifikasi</span></p>
            <p class="text-[11px] text-emerald-700/80 mt-1">Perangkat kurir terikat ke sistem</p>
        </div>

        <div class="bg-blue-50/70 rounded-2xl border border-blue-200 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-blue-800">Kurir Online (GPS Aktif)</span>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
            </div>
            <p class="text-2xl font-extrabold text-blue-700 mt-2">{{ $activeOnlineCouriers->count() }} <span class="text-xs font-medium text-blue-600">Online</span></p>
            <p class="text-[11px] text-blue-700/80 mt-1">Sinyal GPS diterima baru saja</p>
        </div>

        <div class="bg-amber-50/80 rounded-2xl border border-amber-200 p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-900">Total Pengantaran Aktif</span>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 mt-2">{{ collect($couriers)->sum('active_orders_count') }} <span class="text-xs font-medium text-amber-700">Order</span></p>
            <p class="text-[11px] text-amber-800/80 mt-1">Sedang dalam proses delivery</p>
        </div>
    </div>

    {{-- SECTION 1: Status Koneksi Nomor Setiap Kurir & Tautan Pelacakan --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-gray-50/50">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i>
                    Status Koneksi Nomor Kurir & Tautan Pemantauan Langsung
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Admin hanya memantau apakah nomor kurir sudah terhubung. Seluruh penanganan order diserahkan ke kurir via Link Kurir.
                </p>
            </div>
            <a href="{{ route('admin.zendo.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-xs font-semibold text-gray-700 transition self-start sm:self-auto">
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

                <div class="rounded-2xl border-2 {{ $isConnected ? 'border-emerald-200 bg-emerald-50/20' : 'border-gray-200 bg-gray-50/40' }} p-5 flex flex-col justify-between gap-4 shadow-sm transition">
                    <div>
                        {{-- Baris Header Kurir --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-[#2B1F1A] text-amber-300 font-extrabold text-sm flex items-center justify-center shrink-0 shadow">
                                    #{{ $courier['index'] }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 text-sm sm:text-base truncate">{{ $courier['name'] }}</h4>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                                        <span class="inline-flex items-center gap-1 text-xs font-mono font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-md">
                                            📱 {{ $courier['phone'] ?: '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Badge Koneksi Nomor --}}
                            @if($isConnected)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terhubung
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-300 shrink-0">
                                    Belum Terhubung
                                </span>
                            @endif
                        </div>

                        {{-- Status GPS Kurir --}}
                        <div class="mt-3 pt-3 border-t border-gray-200/70 flex items-center justify-between text-xs text-gray-600">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-satellite-dish text-[11px] {{ $isOnline ? 'text-emerald-500' : 'text-gray-400' }}"></i>
                                <span>Status GPS:</span>
                            </span>
                            <span class="font-bold {{ $isOnline ? 'text-emerald-700' : 'text-gray-500' }}">
                                {{ $isOnline ? '🟢 GPS Aktif Online' : ($isConnected ? '💤 Standby' : '⚪ Menunggu Kurir') }}
                            </span>
                        </div>

                        {{-- Pesanan Aktif yang Sedang Dibawa Kurir --}}
                        <div class="mt-3 space-y-2">
                            @if(!empty($activeDeliveries))
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                    Pengantaran Sedang Berjalan ({{ count($activeDeliveries) }}):
                                </p>
                                @foreach($activeDeliveries as $activeDelivery)
                                    <div class="rounded-xl bg-white border border-gray-200 p-3 shadow-xs space-y-2 text-xs">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="font-mono font-bold text-gray-900">#{{ $activeDelivery->order_code }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                                {{ $activeDelivery->status === 'almost_done' ? 'bg-amber-100 text-amber-900' : ($activeDelivery->status === 'picked_up' ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-700') }}">
                                                {{ $activeDelivery->status_label }}
                                            </span>
                                        </div>
                                        <p class="text-gray-700 leading-snug line-clamp-2">
                                            📍 <strong>{{ $activeDelivery->delivery_address }}</strong>
                                        </p>

                                        {{-- Tombol Buka Peta Live Grab (Lacak dari mana) --}}
                                        <div class="pt-2 border-t border-gray-100 flex flex-wrap gap-1.5">
                                            <a href="{{ route('zendo.track', $activeDelivery->tracking_token) }}" target="_blank"
                                               class="flex-1 py-1.5 px-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] flex items-center justify-center gap-1 transition shadow-xs">
                                                <i class="fa-solid fa-map-location-dot"></i>
                                                <span>Buka Peta Live (Grab) ↗</span>
                                            </a>
                                            <a href="{{ route('zendo.courier.show', $activeDelivery->courier_token) }}" target="_blank"
                                               class="py-1.5 px-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700 text-[10px] font-semibold flex items-center gap-1 transition"
                                               title="Buka langsung sebagai Workspace Kurir">
                                                <i class="fa-solid fa-motorcycle text-[10px] text-emerald-600"></i>
                                                <span>Mode Kurir</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="rounded-xl border border-dashed border-gray-200 p-3 text-center text-xs text-gray-400">
                                    Belum ada order aktif yang dibawa.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Kurir: Chat WA --}}
                    <div class="pt-3 border-t border-gray-200/80 flex items-center justify-between text-xs">
                        <span class="text-gray-400 text-[11px]">Selesai hari ini: <strong>{{ $courier['completed_today_count'] ?? 0 }}</strong></span>
                        @if(!empty($courier['clean_phone']))
                            <a href="https://wa.me/{{ $courier['clean_phone'] }}" target="_blank"
                               class="text-emerald-700 font-bold hover:underline flex items-center gap-1 text-xs">
                                <i class="fa-brands fa-whatsapp text-sm text-emerald-600"></i>
                                <span>Hubungi Kurir ↗</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- SECTION 2: Daftar Semua Pengantaran Zendo & Tombol Buka Peta Live (Dari Mana) --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 bg-gray-50/50">
            <div>
                <h3 class="text-base font-bold text-gray-900">Riwayat & Pemantauan Pengantaran Zendo</h3>
                <p class="text-xs text-gray-500 mt-0.5">Buka peta live untuk melihat posisi kurir sudah sampai mana dan rute dari mana kurir berjalan.</p>
            </div>

            {{-- Form Filter --}}
            <form method="GET" action="{{ route('admin.zendo.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="courier_phone" class="px-3 py-2 rounded-xl border border-gray-200 text-xs bg-white focus:border-amber-500 focus:outline-none">
                    <option value="">Semua Nomor Kurir</option>
                    @foreach($couriers as $c)
                        @if(!empty($c['clean_phone']))
                            <option value="{{ $c['clean_phone'] }}" @selected($filterPhone === $c['clean_phone'])>
                                #{{ $c['index'] }} {{ $c['name'] }} (+{{ $c['clean_phone'] }})
                            </option>
                        @endif
                    @endforeach
                </select>

                <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 text-xs bg-white focus:border-amber-500 focus:outline-none">
                    <option value="all" @selected($filterStatus === 'all')>Semua Status</option>
                    <option value="assigned" @selected($filterStatus === 'assigned')>Menunggu Kurir</option>
                    <option value="picked_up" @selected($filterStatus === 'picked_up')>Dalam Perjalanan</option>
                    <option value="almost_done" @selected($filterStatus === 'almost_done')>Hampir Sampai</option>
                    <option value="completed" @selected($filterStatus === 'completed')>Selesai</option>
                </select>

                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari kode / nama / alamat..."
                       class="px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-amber-500 focus:outline-none w-48">

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#2B1F1A] text-white text-xs font-semibold hover:bg-[#3E2C24] transition">
                    Filter
                </button>
                @if($filterPhone !== '' || $filterStatus !== 'all' || $searchQuery !== '')
                    <a href="{{ route('admin.zendo.index') }}" class="px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3 px-4">Kode & Waktu</th>
                        <th class="py-3 px-4">Kurir Zendo</th>
                        <th class="py-3 px-4">Pelanggan & Alamat Tujuan</th>
                        <th class="py-3 px-4">Status & Posisi Kurir</th>
                        <th class="py-3 px-4 text-right">Peta Pelacakan (Grab-like)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($deliveries as $deliv)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="py-3.5 px-4 align-top">
                                <span class="font-mono font-bold text-gray-900">#{{ $deliv->order_code }}</span>
                                <p class="text-[11px] text-gray-500 mt-0.5">{{ $deliv->created_at ? $deliv->created_at->format('d M, H:i') : '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 align-top">
                                <div class="font-bold text-gray-900">{{ $deliv->courier_name }}</div>
                                <div class="inline-flex items-center gap-1 font-mono text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded mt-1">
                                    📱 +{{ $deliv->courier_phone }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1">
                                    {{ $deliv->courier_verified_at ? '✅ Nomor Kurir Terverifikasi' : '⏳ Menunggu Kurir Buka Link' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 align-top max-w-xs">
                                <p class="font-bold text-gray-900">{{ $deliv->customer_name ?: 'Pelanggan' }}</p>
                                <p class="text-gray-700 mt-0.5 leading-snug">📍 {{ $deliv->delivery_address ?: '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 align-top">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold text-[11px]
                                    {{ $deliv->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($deliv->status === 'almost_done' ? 'bg-amber-100 text-amber-900' : ($deliv->status === 'picked_up' ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $deliv->status_label }}
                                </span>
                                @if($deliv->hasCourierLocation())
                                    <p class="text-[10px] text-gray-500 mt-1">
                                        GPS terakhir: {{ $deliv->location_updated_at ? $deliv->location_updated_at->diffForHumans() : '-' }}
                                    </p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 align-top text-right">
                                <div class="inline-flex flex-col items-end gap-1.5">
                                    <a href="{{ route('zendo.track', $deliv->tracking_token) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                        <span>Buka Peta Live ↗</span>
                                    </a>
                                    <a href="{{ route('zendo.courier.show', $deliv->courier_token) }}" target="_blank"
                                       class="text-[10px] text-gray-500 hover:text-emerald-700 font-semibold"
                                       title="Buka langsung sebagai Workspace Kurir">
                                        <i class="fa-solid fa-motorcycle text-[10px] text-emerald-600 mr-0.5"></i> Buka Mode Kurir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-xs">
                                Belum ada pengantaran kurir Zendo yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveries->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $deliveries->links() }}
            </div>
        @endif
    </div>

    {{-- SECTION 3: Pengaturan Multi-Nomor HP Kurir Zendo --}}
    <div id="section-pengaturan-kurir" class="bg-white rounded-2xl border-2 border-emerald-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-emerald-50/70 border-b border-emerald-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-emerald-950">Pengaturan Jumlah & Nomor WhatsApp Setiap Kurir Zendo</h3>
                <p class="text-xs text-emerald-800/80 mt-0.5">
                    Atur berapa jumlah kurir Zendo dan tentukan nomor WhatsApp masing-masing kurir.
                </p>
            </div>
            <button type="button" id="btnAddCourierRow"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs">
                + Tambah Kurir Zendo
            </button>
        </div>

        <form method="POST" action="{{ route('admin.zendo.couriers.update') }}" class="p-6 space-y-5">
            @csrf

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div>
                    <label for="zendoCourierCountInput" class="block text-xs font-bold text-gray-800">Jumlah Kurir Zendo Aktif</label>
                    <p class="text-[11px] text-gray-500 mt-0.5">Ubah angka ini untuk menambah baris nomor HP kurir.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="btnDecCourierCount"
                            class="w-9 h-9 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 font-bold text-gray-700 flex items-center justify-center transition">
                        −
                    </button>
                    <input type="number" name="zendo_courier_count" id="zendoCourierCountInput"
                           value="{{ $totalCouriers }}" min="1" max="50"
                           class="w-20 px-3 py-2 rounded-xl border border-emerald-400 text-center font-extrabold text-sm text-emerald-950 focus:outline-none">
                    <button type="button" id="btnIncCourierCount"
                            class="w-9 h-9 rounded-xl border border-emerald-500 bg-emerald-600 hover:bg-emerald-700 font-bold text-white flex items-center justify-center transition">
                        +
                    </button>
                </div>
            </div>

            <div id="zendoCouriersFormList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($couriers as $idx => $courier)
                    <div class="courier-form-card rounded-xl border border-gray-200 bg-gray-50/60 p-4 space-y-3" data-courier-row="{{ $idx }}">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#2B1F1A] text-amber-300 text-xs font-bold">
                                Kurir Zendo #<span class="courier-num-label">{{ $idx + 1 }}</span>
                            </span>
                            @if($courier['is_connected'] ?? false)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    🟢 Nomor Terhubung
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-600">
                                    ⚪ Belum Verifikasi
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Nama / Label Kurir</label>
                                <input type="text"
                                       name="zendo_couriers[{{ $idx }}][name]"
                                       value="{{ $courier['name'] }}"
                                       placeholder="Kurir Zendo {{ $idx + 1 }}"
                                       required
                                       class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white text-xs font-semibold text-gray-900 focus:border-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Nomor WhatsApp Kurir #{{ $idx + 1 }}</label>
                                <input type="text"
                                       name="zendo_couriers[{{ $idx }}][phone]"
                                       value="{{ $courier['phone'] }}"
                                       placeholder="Contoh: 628123456789{{ $idx + 1 }}"
                                       required
                                       class="courier-phone-field w-full px-3 py-2 rounded-lg border border-emerald-300 bg-white text-xs font-mono font-bold text-gray-900 focus:border-emerald-600 focus:outline-none">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    💡 Setiap nomor HP kurir disimpan terpisah dan digunakan untuk otentikasi seleksi nomor saat kurir membuka link tugasnya.
                </p>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm shrink-0">
                    Simpan Semua Nomor Kurir Zendo
                </button>
            </div>
        </form>
    </div>

    {{-- SECTION 4: Pengaturan Tarif Ongkir Zendo Berdasarkan Jarak & Titik Kafe --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/40">
            <h3 class="text-base font-bold text-gray-900">Pengaturan Tarif Ongkir Zendo (Berdasarkan Jarak Kafe) & Gateway WA</h3>
            <p class="text-xs text-gray-500 mt-0.5">Atur tarif per kilometer dan koordinat titik Nucomu Cafe.</p>
        </div>

        <form method="POST" action="{{ route('admin.zendo.tariff.update') }}" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">0 – 3 km (Rp)</label>
                    <input type="number" name="zendo_tier_0_3" value="{{ $tariff['tier_0_3'] ?? 10000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">3,1 – 4 km (Rp)</label>
                    <input type="number" name="zendo_tier_3_4" value="{{ $tariff['tier_3_4'] ?? 12000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">4,1 – 5 km (Rp)</label>
                    <input type="number" name="zendo_tier_4_5" value="{{ $tariff['tier_4_5'] ?? 15000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">5,1 – 6 km (Rp)</label>
                    <input type="number" name="zendo_tier_5_6" value="{{ $tariff['tier_5_6'] ?? 17000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">6,1 – 7 km (Rp)</label>
                    <input type="number" name="zendo_tier_6_7" value="{{ $tariff['tier_6_7'] ?? 18000 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">&gt; 7 km (+Rp/km)</label>
                    <input type="number" name="zendo_extra_per_km" value="{{ $tariff['extra_per_km'] ?? 2500 }}" min="0" step="500"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-800">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-3 border-t border-gray-100">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Latitude Nucomu Cafe</label>
                    <input type="text" name="cafe_lat" value="{{ $txSettings['cafe_lat'] ?? '-8.0765099' }}"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Longitude Nucomu Cafe</label>
                    <input type="text" name="cafe_lng" value="{{ $txSettings['cafe_lng'] ?? '111.918712' }}"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Endpoint API WA Gateway (Opsional)</label>
                    <input type="text" name="zendo_wa_api_url" value="{{ $txSettings['zendo_wa_api_url'] ?? 'https://api.fonnte.com/send' }}"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Token API WA Gateway (Opsional)</label>
                    <input type="password" name="zendo_wa_api_token" value="{{ $txSettings['zendo_wa_api_token'] ?? '' }}"
                           placeholder="Isi token Fonnte jika ada"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">
                        Domain / URL Publik Aplikasi (Agar Link WhatsApp Otomatis Bisa Diklik)
                    </label>
                    <input type="text" name="zendo_public_url" value="{{ $txSettings['zendo_public_url'] ?? '' }}"
                           placeholder="Contoh: https://xxx.ngrok-free.app atau https://nucomucafe.com (Kosongkan jika pakai default)"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs">
                    <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">
                        <i class="fa-solid fa-circle-info text-blue-500"></i> <strong>Penting:</strong> WhatsApp tidak mengizinkan klik langsung pada tautan yang menggunakan alamat IP angka mentah (seperti <code>192.168.x.x</code>). Jika Anda menggunakan Ngrok, Cloudflare Tunnel, atau domain website, masukkan alamatnya di sini agar link di WhatsApp berwarna hijau dan bisa langsung diklik kurir.
                    </p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#2B1F1A] hover:bg-[#3E2C24] text-white font-bold text-xs transition">
                    Simpan Tarif & Pengaturan Zendo
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
        card.className = 'courier-form-card rounded-xl border border-emerald-300 bg-emerald-50/30 p-4 space-y-3';
        card.setAttribute('data-courier-row', String(index));
        card.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#2B1F1A] text-amber-300 text-xs font-bold">
                    Kurir Zendo #<span class="courier-num-label">${num}</span>
                </span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-600">
                    ⚪ Belum Verifikasi
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Nama / Label Kurir</label>
                    <input type="text"
                           name="zendo_couriers[${index}][name]"
                           value="Kurir Zendo ${num}"
                           placeholder="Kurir Zendo ${num}"
                           required
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white text-xs font-semibold text-gray-900 focus:border-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Nomor WhatsApp Kurir #${num}</label>
                    <input type="text"
                           name="zendo_couriers[${index}][phone]"
                           value=""
                           placeholder="Masukkan nomor WA Kurir #${num} (misal: 0812...)"
                           required
                           class="courier-phone-field w-full px-3 py-2 rounded-lg border border-emerald-400 bg-white text-xs font-mono font-bold text-gray-900 focus:border-emerald-600 focus:outline-none">
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
