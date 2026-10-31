@extends('layouts.admin')
@section('title', 'Pengaturan Sistem & Cafe — Admin Nucomu Cafe')

@section('content')
@php
    $currentTransactionMode = $settings['transaction_mode'] ?? config('nucomu.contact.transaction_mode', 'both');
    $courierCount = isset($couriers) ? count($couriers) : (int) ($settings['zendo_courier_count'] ?? 1);
    $hideLiveTracking = ($settings['hide_live_tracking'] ?? '0') === '1';
    $isKasirActive = in_array($currentTransactionMode, ['both', 'kasir']);
    $isZendoActive = in_array($currentTransactionMode, ['both', 'zendo']);
    $isTrackingActive = ! $hideLiveTracking;
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

    {{-- Unified Card: Metode Transaksi (2 Pilihan) & Fitur Live Tracking (Toggle Switch Premium Sesuai Gambar) --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl relative overflow-hidden">
        {{-- Header Card --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-zinc-800 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-sky-500/15 border border-sky-500/30 text-sky-400 text-[10px] font-bold uppercase tracking-wider">
                        Kontrol Transaksi & Pelacakan
                    </span>
                    <span id="headerTransactionBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-zinc-800 text-zinc-300 border border-zinc-700 text-[10px] font-bold">
                        {{ $currentTransactionMode === 'both' ? 'Kasir + Zendo Aktif' : ($currentTransactionMode === 'kasir' ? 'Hanya Kasir Aktif' : 'Hanya Kurir Zendo Aktif') }}
                    </span>
                    <span id="headerTrackingBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $isTrackingActive ? 'bg-sky-500/20 text-sky-300 border border-sky-500/40' : 'bg-zinc-800 text-zinc-400 border border-zinc-700' }} text-[10px] font-bold">
                        {{ $isTrackingActive ? 'Mode Sekolah (Peta Live)' : 'Mode Klien (Struk WA)' }}
                    </span>
                </div>
                <h2 class="text-base sm:text-lg font-black text-white tracking-wide">
                    METODE TRANSAKSI & FITUR LIVE TRACKING
                </h2>
                <p class="text-xs text-zinc-400 mt-1">
                    Atur status metode transaksi (hanya 2 metode: <strong>Kasir</strong> & <strong>Kurir Zendo</strong>) serta opsi Live Tracking kurir (Mode Sekolah dengan peta GPS vs Mode Klien dengan struk ringkas WA).
                </p>
            </div>
            <a href="{{ route('home') }}#menu" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-400 hover:text-sky-300 bg-sky-500/10 border border-sky-500/20 px-3.5 py-2 rounded-xl transition-colors self-start sm:self-auto shrink-0">
                <span>Cek Tampilan Web</span>
                <span>↗</span>
            </a>
        </div>

        {{-- Form dengan 3 Toggle Switch Vertikal bergaris pemisah (Persis Sesuai Gambar Referensi) --}}
        <form id="unifiedSettingsForm" action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="transaction_mode" id="inputTransactionMode" value="{{ $currentTransactionMode }}">
            <input type="hidden" name="hide_live_tracking" id="inputHideLiveTracking" value="{{ $hideLiveTracking ? '1' : '0' }}">

            {{-- 3 Toggle Switches List dengan separator garis horizontal --}}
            <div class="rounded-2xl bg-zinc-950/80 border border-zinc-800/80 divide-y divide-zinc-800/80 overflow-hidden">
                
                {{-- TOGGLE ROW 1: METODE KASIR --}}
                <div class="p-4 sm:p-6 flex items-center justify-between gap-4 hover:bg-white/[0.02] transition-colors">
                    <div class="space-y-1 pr-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm sm:text-base font-extrabold text-white tracking-wide flex items-center gap-2">
                                <i class="fa-solid fa-qrcode text-amber-400 text-sm"></i>
                                <span>Metode Kasir (Slip QR & Bayar di Tempat)</span>
                            </span>
                            <span id="badgeKasir" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isKasirActive ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-zinc-800 text-zinc-500 border border-zinc-700' }}">
                                {{ $isKasirActive ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-400 leading-relaxed max-w-2xl">
                            Pelanggan memilih menu di website, lalu sistem menghasilkan <strong>Slip Pesanan & Kode QR</strong> untuk ditunjukkan dan dibayar langsung kepada kasir di kafe.
                        </p>
                    </div>

                    {{-- Switch Toggle 1: Kasir --}}
                    <button type="button" 
                            role="switch" 
                            aria-checked="{{ $isKasirActive ? 'true' : 'false' }}"
                            id="toggleKasirTrack"
                            onclick="toggleMethod('kasir')"
                            class="relative inline-flex h-8 w-14 sm:h-9 sm:w-16 shrink-0 cursor-pointer rounded-full p-1 transition-all duration-300 ease-in-out focus:outline-none select-none {{ $isKasirActive ? 'bg-[#7cb4fc] shadow-[0_0_14px_rgba(124,180,252,0.4)]' : 'bg-[#e2e8f0]' }}">
                        <span id="toggleKasirThumb"
                              class="pointer-events-none inline-block h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-[#18181b] shadow-md transform transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $isKasirActive ? 'translate-x-6 sm:translate-x-7' : 'translate-x-0' }}"></span>
                    </button>
                </div>

                {{-- TOGGLE ROW 2: METODE KURIR ZENDO --}}
                <div class="p-4 sm:p-6 flex items-center justify-between gap-4 hover:bg-white/[0.02] transition-colors">
                    <div class="space-y-1 pr-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm sm:text-base font-extrabold text-white tracking-wide flex items-center gap-2">
                                <i class="fa-solid fa-motorcycle text-emerald-400 text-sm"></i>
                                <span>Metode Kurir Zendo (Delivery WhatsApp)</span>
                            </span>
                            <span id="badgeZendo" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isZendoActive ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-zinc-800 text-zinc-500 border border-zinc-700' }}">
                                {{ $isZendoActive ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-400 leading-relaxed max-w-2xl">
                            Pelanggan memesan untuk diantar langsung ke rumah/tujuan oleh Kurir Zendo, dan pesanan diteruskan secara instan melalui WhatsApp.
                        </p>
                    </div>

                    {{-- Switch Toggle 2: Zendo --}}
                    <button type="button" 
                            role="switch" 
                            aria-checked="{{ $isZendoActive ? 'true' : 'false' }}"
                            id="toggleZendoTrack"
                            onclick="toggleMethod('zendo')"
                            class="relative inline-flex h-8 w-14 sm:h-9 sm:w-16 shrink-0 cursor-pointer rounded-full p-1 transition-all duration-300 ease-in-out focus:outline-none select-none {{ $isZendoActive ? 'bg-[#7cb4fc] shadow-[0_0_14px_rgba(124,180,252,0.4)]' : 'bg-[#e2e8f0]' }}">
                        <span id="toggleZendoThumb"
                              class="pointer-events-none inline-block h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-[#18181b] shadow-md transform transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $isZendoActive ? 'translate-x-6 sm:translate-x-7' : 'translate-x-0' }}"></span>
                    </button>
                </div>

                {{-- TOGGLE ROW 3: FITUR LIVE TRACKING (MODE SEKOLAH VS KLIEN) --}}
                <div class="p-4 sm:p-6 flex items-center justify-between gap-4 hover:bg-white/[0.02] transition-colors">
                    <div class="space-y-1 pr-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm sm:text-base font-extrabold text-white tracking-wide flex items-center gap-2">
                                <i class="fa-solid fa-map-location-dot text-sky-400 text-sm"></i>
                                <span>Fitur Live Tracking Kurir & Peta GPS</span>
                            </span>
                            <span id="badgeTracking" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isTrackingActive ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-zinc-800 text-zinc-400 border border-zinc-700' }}">
                                {{ $isTrackingActive ? 'MODE SEKOLAH (PETA AKTIF)' : 'MODE KLIEN (STRUK WA RINGKAS)' }}
                            </span>
                        </div>
                        <p id="descTracking" class="text-xs text-zinc-400 leading-relaxed max-w-2xl">
                            @if($isTrackingActive)
                                <strong class="text-sky-300">Mode Sekolah / Ujian (Peta Aktif):</strong> Peta interaktif & GPS aktif saat checkout, tombol pelacakan live Grab-like tampil, dan 1 link live tracking disertakan di WhatsApp.
                            @else
                                <strong class="text-emerald-400">Mode Klien (Sembunyikan Peta):</strong> Peta ditiadakan total dari checkout, pesanan langsung membuka WhatsApp berupa format <strong class="text-white">struk ringkas belanja</strong> (hanya menu & total tanpa kata-kata panjang).
                            @endif
                        </p>
                    </div>

                    {{-- Switch Toggle 3: Live Tracking --}}
                    <button type="button" 
                            role="switch" 
                            aria-checked="{{ $isTrackingActive ? 'true' : 'false' }}"
                            id="toggleTrackingTrack"
                            onclick="toggleTracking()"
                            class="relative inline-flex h-8 w-14 sm:h-9 sm:w-16 shrink-0 cursor-pointer rounded-full p-1 transition-all duration-300 ease-in-out focus:outline-none select-none {{ $isTrackingActive ? 'bg-[#7cb4fc] shadow-[0_0_14px_rgba(124,180,252,0.4)]' : 'bg-[#e2e8f0]' }}">
                        <span id="toggleTrackingThumb"
                              class="pointer-events-none inline-block h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-[#18181b] shadow-md transform transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $isTrackingActive ? 'translate-x-6 sm:translate-x-7' : 'translate-x-0' }}"></span>
                    </button>
                </div>

            </div>

            {{-- Link Pantau Kurir Zendo Quick Banner --}}
            <div class="bg-zinc-950/60 border border-zinc-800/80 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-users-gear text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Kelola Nomor HP Kurir & Pemantauan Pengiriman</h4>
                        <p class="text-[11px] text-zinc-400 mt-0.5">Atur daftar kurir Zendo, status pengantaran, dan pantau lokasi pengantaran di menu Zendo.</p>
                    </div>
                </div>
                <a href="{{ route('admin.zendo.index') }}" class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-white font-bold text-xs transition shrink-0 text-center">
                    Buka Halaman Zendo →
                </a>
            </div>

            {{-- Bottom Action Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-zinc-800/80">
                <div id="autoSaveStatusText" class="flex items-center gap-2 text-xs text-zinc-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Perubahan tersimpan otomatis seketika saat switch digeser.</span>
                </div>
                <button type="submit" class="bg-sky-500 hover:bg-sky-400 text-zinc-950 font-black text-xs uppercase tracking-wider px-6 py-2.5 rounded-xl transition-all shadow-lg hover:shadow-sky-500/20 cursor-pointer self-end sm:self-auto">
                    Simpan Semua Pengaturan
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

<script>
    let stateKasir = {{ $isKasirActive ? 'true' : 'false' }};
    let stateZendo = {{ $isZendoActive ? 'true' : 'false' }};
    let stateTracking = {{ $isTrackingActive ? 'true' : 'false' }};

    function updateSwitchVisual(trackId, thumbId, isOn) {
        const track = document.getElementById(trackId);
        const thumb = document.getElementById(thumbId);
        if (!track || !thumb) return;

        track.setAttribute('aria-checked', isOn ? 'true' : 'false');
        if (isOn) {
            track.classList.remove('bg-[#e2e8f0]');
            track.classList.add('bg-[#7cb4fc]', 'shadow-[0_0_14px_rgba(124,180,252,0.4)]');
            thumb.classList.remove('translate-x-0');
            thumb.classList.add('translate-x-6', 'sm:translate-x-7');
        } else {
            track.classList.remove('bg-[#7cb4fc]', 'shadow-[0_0_14px_rgba(124,180,252,0.4)]');
            track.classList.add('bg-[#e2e8f0]');
            thumb.classList.remove('translate-x-6', 'sm:translate-x-7');
            thumb.classList.add('translate-x-0');
        }
    }

    function toggleMethod(type) {
        if (type === 'kasir') {
            if (stateKasir && !stateZendo) {
                showAdminToast('Minimal 1 metode transaksi harus tetap aktif!', 'warning');
                return;
            }
            stateKasir = !stateKasir;
            updateSwitchVisual('toggleKasirTrack', 'toggleKasirThumb', stateKasir);
        } else if (type === 'zendo') {
            if (stateZendo && !stateKasir) {
                showAdminToast('Minimal 1 metode transaksi harus tetap aktif!', 'warning');
                return;
            }
            stateZendo = !stateZendo;
            updateSwitchVisual('toggleZendoTrack', 'toggleZendoThumb', stateZendo);
        }

        let mode = 'both';
        if (stateKasir && stateZendo) mode = 'both';
        else if (stateKasir && !stateZendo) mode = 'kasir';
        else if (!stateKasir && stateZendo) mode = 'zendo';

        document.getElementById('inputTransactionMode').value = mode;
        updateDynamicBadges();
        triggerAsyncSave();
    }

    function toggleTracking() {
        stateTracking = !stateTracking;
        updateSwitchVisual('toggleTrackingTrack', 'toggleTrackingThumb', stateTracking);
        document.getElementById('inputHideLiveTracking').value = stateTracking ? '0' : '1';
        updateDynamicBadges();
        triggerAsyncSave();
    }

    function updateDynamicBadges() {
        const badgeKasir = document.getElementById('badgeKasir');
        const badgeZendo = document.getElementById('badgeZendo');
        const badgeTracking = document.getElementById('badgeTracking');
        const descTracking = document.getElementById('descTracking');
        const headerTxBadge = document.getElementById('headerTransactionBadge');
        const headerTrackBadge = document.getElementById('headerTrackingBadge');

        if (badgeKasir) {
            badgeKasir.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full ' + (stateKasir ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-zinc-800 text-zinc-500 border border-zinc-700');
            badgeKasir.innerText = stateKasir ? 'AKTIF' : 'NONAKTIF';
        }

        if (badgeZendo) {
            badgeZendo.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full ' + (stateZendo ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-zinc-800 text-zinc-500 border border-zinc-700');
            badgeZendo.innerText = stateZendo ? 'AKTIF' : 'NONAKTIF';
        }

        if (headerTxBadge) {
            if (stateKasir && stateZendo) headerTxBadge.innerText = 'Kasir + Zendo Aktif';
            else if (stateKasir) headerTxBadge.innerText = 'Hanya Kasir Aktif';
            else headerTxBadge.innerText = 'Hanya Kurir Zendo Aktif';
        }

        if (badgeTracking) {
            badgeTracking.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full ' + (stateTracking ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-zinc-800 text-zinc-400 border border-zinc-700');
            badgeTracking.innerText = stateTracking ? 'MODE SEKOLAH (PETA AKTIF)' : 'MODE KLIEN (STRUK WA RINGKAS)';
        }

        if (descTracking) {
            descTracking.innerHTML = stateTracking
                ? '<strong class="text-sky-300">Mode Sekolah / Ujian (Peta Aktif):</strong> Peta interaktif & GPS aktif saat checkout, tombol pelacakan live Grab-like tampil, dan 1 link live tracking disertakan di WhatsApp.'
                : '<strong class="text-emerald-400">Mode Klien (Sembunyikan Peta):</strong> Peta ditiadakan total dari checkout, pesanan langsung membuka WhatsApp berupa format <strong class="text-white">struk ringkas belanja</strong> (hanya menu & total tanpa kata-kata panjang).';
        }

        if (headerTrackBadge) {
            headerTrackBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full ' + (stateTracking ? 'bg-sky-500/20 text-sky-300 border border-sky-500/40' : 'bg-zinc-800 text-zinc-400 border border-zinc-700') + ' text-[10px] font-bold';
            headerTrackBadge.innerText = stateTracking ? 'Mode Sekolah (Peta Live)' : 'Mode Klien (Struk WA)';
        }
    }

    let autoSaveTimeout = null;
    function triggerAsyncSave() {
        clearTimeout(autoSaveTimeout);
        const statusEl = document.getElementById('autoSaveStatusText');
        if (statusEl) {
            statusEl.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span><span class="text-amber-300">Menyimpan perubahan...</span>';
        }

        autoSaveTimeout = setTimeout(() => {
            const form = document.getElementById('unifiedSettingsForm');
            const formData = new FormData(form);

            fetch('{{ route("admin.settings.update") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (statusEl) {
                    statusEl.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-400"></span><span class="text-emerald-300 font-bold">✓ Tersimpan otomatis</span>';
                    setTimeout(() => {
                        statusEl.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-400"></span><span>Perubahan tersimpan otomatis seketika saat switch digeser.</span>';
                    }, 2500);
                }
                showAdminToast('✓ Pengaturan berhasil disimpan otomatis!', 'success');
            })
            .catch(err => {
                if (statusEl) {
                    statusEl.innerHTML = '<span class="w-2 h-2 rounded-full bg-rose-400"></span><span class="text-rose-300">Gagal auto-save. Silakan klik tombol Simpan.</span>';
                }
            });
        }, 150);
    }

    function showAdminToast(message, type = 'success') {
        let toast = document.getElementById('adminSettingsToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'adminSettingsToast';
            toast.className = 'fixed bottom-6 right-6 z-50 px-4 py-3 rounded-2xl shadow-2xl text-xs font-bold transition-all duration-300 transform translate-y-10 opacity-0 pointer-events-none flex items-center gap-2.5';
            document.body.appendChild(toast);
        }

        if (type === 'warning') {
            toast.className = 'fixed bottom-6 right-6 z-50 px-4 py-3 rounded-2xl shadow-2xl text-xs font-bold transition-all duration-300 transform flex items-center gap-2.5 bg-amber-400 text-zinc-950 border border-amber-300';
            toast.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-base"></i><span>' + message + '</span>';
        } else {
            toast.className = 'fixed bottom-6 right-6 z-50 px-4 py-3 rounded-2xl shadow-2xl text-xs font-bold transition-all duration-300 transform flex items-center gap-2.5 bg-zinc-900 text-sky-300 border border-sky-500/40';
            toast.innerHTML = '<i class="fa-solid fa-circle-check text-sky-400 text-base"></i><span>' + message + '</span>';
        }

        toast.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
        clearTimeout(window.adminToastTimer);
        window.adminToastTimer = setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
        }, 2600);
    }
</script>
@endsection
