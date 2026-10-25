@extends('layouts.admin')
@section('title', 'Pengaturan Sistem & Cafe — Admin Nucomu Cafe')

@section('content')
@php
    $currentTransactionMode = $settings['transaction_mode'] ?? config('nucomu.contact.transaction_mode', 'both');
    $courierCount = isset($couriers) ? count($couriers) : (int) ($settings['zendo_courier_count'] ?? 1);
@endphp
<div class="max-w-5xl mx-auto space-y-8">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pengaturan Sistem & Profil Cafe</h1>
            <p class="text-xs text-gray-500 mt-1">Atur metode proses transaksi pesanan (Kasir, Kurir Zendo, atau Keduanya), profil kafe, dan jam operasional.</p>
        </div>
        <a href="{{ route('admin.zendo.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#2B1F1A] hover:bg-[#3E2C24] text-amber-300 font-bold text-xs transition shadow-sm self-start sm:self-auto">
            <span>Kelola Kurir & Pantau Zendo ({{ $courierCount }} Kurir)</span>
            <span>→</span>
        </a>
    </div>

    {{-- Form Pengaturan Mode Proses Transaksi --}}
    <div class="bg-zinc-900 border border-amber-500/30 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-zinc-800 pb-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-400 text-[10px] font-bold uppercase tracking-wider mb-1.5">
                    Mode Transaksi Web Pelanggan
                </span>
                <h2 class="text-base sm:text-lg font-extrabold uppercase tracking-wider text-white">
                    Pilih Metode Proses Transaksi di Halaman Pemesanan
                </h2>
            </div>
            <a href="{{ route('nucomu.order') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-amber-300 bg-amber-500/10 border border-amber-500/20 px-3.5 py-2 rounded-xl transition-colors self-start sm:self-auto">
                <span>Lihat Halaman Pemesanan</span>
                <span>↗</span>
            </a>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Opsi 1: Keduanya (Kasir & Zendo) --}}
                <label class="relative cursor-pointer group">
                    <input type="radio" name="transaction_mode" value="both" class="peer sr-only" {{ $currentTransactionMode === 'both' ? 'checked' : '' }}>
                    <div class="h-full rounded-2xl p-4 bg-zinc-950 border-2 border-zinc-800 peer-checked:border-amber-400 peer-checked:bg-amber-500/10 hover:border-zinc-700 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                    Fleksibel
                                </span>
                            </div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wide mb-1">Keduanya (Kasir & Zendo)</h3>
                            <p class="text-xs text-zinc-400 leading-relaxed">
                                Pelanggan bisa memilih sendiri saat pesan menu: mau proses lewat <strong>Kasir (QR Slip)</strong> atau diantar <strong>Kurir Zendo</strong>.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-zinc-800/80 flex items-center justify-between text-[11px] font-bold text-amber-400">
                            <span>Aktifkan Kasir + Zendo</span>
                            <span>✓</span>
                        </div>
                    </div>
                </label>

                {{-- Opsi 2: Kasir Saja --}}
                <label class="relative cursor-pointer group">
                    <input type="radio" name="transaction_mode" value="kasir" class="peer sr-only" {{ $currentTransactionMode === 'kasir' ? 'checked' : '' }}>
                    <div class="h-full rounded-2xl p-4 bg-zinc-950 border-2 border-zinc-800 peer-checked:border-amber-400 peer-checked:bg-amber-500/10 hover:border-zinc-700 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                    Kasir QR
                                </span>
                            </div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wide mb-1">Proses Kasir Saja</h3>
                            <p class="text-xs text-zinc-400 leading-relaxed">
                                Pelanggan memilih menu di web lalu sistem membuat <strong>Slip Pesanan & QR Code</strong> untuk ditunjukkan dan dibayar di kasir.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-zinc-800/80 flex items-center justify-between text-[11px] font-bold text-cyan-400">
                            <span>Hanya Kasir</span>
                            <span>✓</span>
                        </div>
                    </div>
                </label>

                {{-- Opsi 3: Zendo Saja --}}
                <label class="relative cursor-pointer group">
                    <input type="radio" name="transaction_mode" value="zendo" class="peer sr-only" {{ $currentTransactionMode === 'zendo' ? 'checked' : '' }}>
                    <div class="h-full rounded-2xl p-4 bg-zinc-950 border-2 border-zinc-800 peer-checked:border-emerald-400 peer-checked:bg-emerald-500/10 hover:border-zinc-700 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    Kurir Zendo
                                </span>
                            </div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wide mb-1">Proses Zendo Saja</h3>
                            <p class="text-xs text-zinc-400 leading-relaxed">
                                Pelanggan memilih menu & titik peta di web (tanpa bukti struk), lalu pesanan <strong>otomatis dikirim ke kurir Zendo yang tersedia</strong>.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-zinc-800/80 flex items-center justify-between text-[11px] font-bold text-emerald-400">
                            <span>Hanya Kurir Zendo</span>
                            <span>✓</span>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Info Card Pemisahan Halaman Manajemen & Pemantauan Zendo --}}
            <div class="bg-emerald-950/40 border border-emerald-500/30 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-emerald-300">
                        Manajemen Nomor HP Kurir & Pemantauan Pengiriman Zendo Dipisahkan
                    </h4>
                    <p class="text-xs text-zinc-300 mt-1">
                        Untuk mengatur banyak nomor HP kurir Zendo, memantau nomor HP kurir sedang mengantar pesanan ke mana saja, serta mengubah status pengiriman (Standby / Mengantar / Hampir Selesai), buka menu khusus <strong>Kurir & Pantau Zendo</strong>.
                    </p>
                </div>
                <a href="{{ route('admin.zendo.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-black text-xs whitespace-nowrap transition shrink-0">
                    Buka Halaman Zendo →
                </a>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-zinc-950 font-black text-xs uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-lg">
                    Simpan Mode Proses Transaksi
                </button>
            </div>
        </form>
    </div>

    {{-- Form Pengaturan Profil --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b border-zinc-800 pb-3">
            Profil & Kontak Cafe
        </h2>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="store_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Nama Cafe / UMKM</label>
                    <input type="text" name="store_name" id="store_name" value="{{ $settings['store_name'] ?? $settings['cafe_name'] ?? 'Nucomu Cafe' }}" required
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Nomor WhatsApp CS / Kasir</label>
                    <input type="text" name="phone" id="phone" value="{{ $settings['phone'] ?? $settings['cafe_whatsapp'] ?? '+62 812-3456-7890' }}" required
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-white">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Alamat Lengkap Cafe</label>
                <textarea name="address" id="address" rows="2" required
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white">{{ $settings['address'] ?? $settings['cafe_address'] ?? 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="instagram" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Username Instagram</label>
                    <input type="text" name="instagram" id="instagram" value="{{ $settings['instagram'] ?? $settings['cafe_instagram'] ?? '@nucomucafe' }}"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-white">
                </div>

                <div>
                    <label for="tagline" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Tagline Cafe</label>
                    <input type="text" name="tagline" id="tagline" value="{{ $settings['tagline'] ?? $settings['cafe_tagline'] ?? 'Coffee & Dessert — New, Unforgettable, Comfy, Musings' }}"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-md">
                    Simpan Pengaturan Profil
                </button>
            </div>
        </form>
    </div>

    {{-- Form Jam Operasional 7 Hari --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b border-zinc-800 pb-3">
            Pengaturan Jam Operasional (7 Hari)
        </h2>

        <form action="{{ route('admin.settings.hours') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-3">
                @foreach($operatingHours as $oh)
                    <div class="bg-zinc-950 border border-zinc-800 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <input type="hidden" name="hours[{{ $oh->id }}][id]" value="{{ $oh->id }}">
                        
                        <div class="w-32">
                            <span class="font-bold text-white text-sm block">{{ $oh->day_name }}</span>
                            <span class="text-[10px] text-zinc-500 font-mono">Hari {{ $oh->day_of_week }}</span>
                        </div>

                        <div class="flex items-center space-x-3 text-xs">
                            <div>
                                <span class="text-zinc-500 block text-[10px] uppercase">Jam Buka</span>
                                <input type="time" name="hours[{{ $oh->id }}][open_time]" value="{{ $oh->open_time }}"
                                    class="bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-1.5 text-white text-xs">
                            </div>
                            <span class="text-zinc-500 pt-3">-</span>
                            <div>
                                <span class="text-zinc-500 block text-[10px] uppercase">Jam Tutup</span>
                                <input type="time" name="hours[{{ $oh->id }}][close_time]" value="{{ $oh->close_time }}"
                                    class="bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-1.5 text-white text-xs">
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 pt-2 sm:pt-0">
                            <label class="flex items-center space-x-2 cursor-pointer text-xs font-semibold">
                                <input type="checkbox" name="hours[{{ $oh->id }}][is_closed]" value="1" {{ $oh->is_closed ? 'checked' : '' }}
                                    class="w-4 h-4 text-white bg-zinc-900 border-zinc-700 rounded">
                                <span class="{{ $oh->is_closed ? 'text-red-400 font-bold' : 'text-zinc-400' }}">Tutup / Libur</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-md">
                    Simpan Jam Operasional
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
