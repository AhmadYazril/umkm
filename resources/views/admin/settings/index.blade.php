@extends('layouts.admin')
@section('title', 'Pengaturan Cafe & Konten Web — Nucomu Cafe')

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
    
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-sliders text-[10px]"></i>
                <span>Pusat Konfigurasi Fleksibel</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Pengaturan Sistem &amp; Konten Web
            </h1>
            <p class="text-xs text-muted-text mt-1 leading-relaxed">
                Kelola metode transaksi pesanan, kustomisasi teks web customer, kontak, dan jam operasional.
            </p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('admin.zendo.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs transition shadow-xs btn-press">
                <i class="fa-solid fa-motorcycle text-xs"></i>
                <span>Kelola Kurir Zendo ({{ $courierCount }})</span>
            </a>
            <a href="{{ route('home') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone text-ink-black border border-hairline font-bold text-xs transition shadow-xs btn-press" title="Cek Web Pelanggan">
                <span>Web</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- Segmented Tab Navigation Bar (Styled with Nucomu Ivory & Monochrome Aesthetics) --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-hairline text-xs font-bold select-none anim-fade-up">
        <button type="button" onclick="switchSettingsTab('tab-transaksi')" id="btn-tab-transaksi" 
                class="settings-nav-tab px-4 py-2.5 rounded-xl transition-all duration-200 bg-ink-black text-white shadow-xs flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-toggle-on text-xs"></i>
            <span>Transaksi &amp; Live Tracking</span>
        </button>

        <button type="button" onclick="switchSettingsTab('tab-konten')" id="btn-tab-konten" 
                class="settings-nav-tab px-4 py-2.5 rounded-xl transition-all duration-200 text-muted-text hover:text-ink-black hover:bg-warm-stone/80 flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-pen-nib text-xs"></i>
            <span>Konten Halaman Depan</span>
        </button>

        <button type="button" onclick="switchSettingsTab('tab-profil')" id="btn-tab-profil" 
                class="settings-nav-tab px-4 py-2.5 rounded-xl transition-all duration-200 text-muted-text hover:text-ink-black hover:bg-warm-stone/80 flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-store text-xs"></i>
            <span>Profil &amp; Kontak Kafe</span>
        </button>

        <button type="button" onclick="switchSettingsTab('tab-jam')" id="btn-tab-jam" 
                class="settings-nav-tab px-4 py-2.5 rounded-xl transition-all duration-200 text-muted-text hover:text-ink-black hover:bg-warm-stone/80 flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-clock text-xs"></i>
            <span>Jam Operasional (7 Hari)</span>
        </button>
    </div>

    {{-- =========================================================================
         TAB 1: KONTROL METODE TRANSAKSI & LIVE TRACKING
         ========================================================================= --}}
    <div id="panel-tab-transaksi" class="settings-panel space-y-6 anim-fade-up">
        
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-6 shadow-card relative overflow-hidden">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-hairline pb-5">
                <div>
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-warm-stone border border-hairline text-ink-black text-[10px] font-bold uppercase tracking-wider">
                            Saklar Kontrol Sistem
                        </span>
                        <span id="headerTransactionBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-warm-stone text-charcoal border border-hairline text-[10px] font-bold">
                            {{ $currentTransactionMode === 'both' ? 'Kasir + Zendo Aktif' : ($currentTransactionMode === 'kasir' ? 'Hanya Kasir Aktif' : 'Hanya Kurir Zendo Aktif') }}
                        </span>
                        <span id="headerTrackingBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $isTrackingActive ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }} text-[10px] font-bold">
                            {{ $isTrackingActive ? 'Mode Sekolah (Peta Live GPS)' : 'Mode Klien (Struk Ringkas WA)' }}
                        </span>
                    </div>
                    <h2 class="text-base sm:text-lg font-bold text-ink-black tracking-wide">
                        Metode Transaksi &amp; Fitur Pelacakan Kurir
                    </h2>
                    <p class="text-xs text-muted-text mt-1">
                        Atur ketersediaan metode transaksi (Kasir &amp; Kurir Zendo) dan mode pelacakan di halaman checkout web customer.
                    </p>
                </div>
            </div>

            <form id="unifiedSettingsForm" action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="transaction_mode" id="inputTransactionMode" value="{{ $currentTransactionMode }}">
                <input type="hidden" name="hide_live_tracking" id="inputHideLiveTracking" value="{{ $hideLiveTracking ? '1' : '0' }}">

                {{-- 3 Toggle Switches List dengan Desain Monokrom --}}
                <div class="rounded-2xl bg-warm-ivory border border-hairline divide-y divide-hairline overflow-hidden">
                    
                    {{-- ROW 1: KASIR --}}
                    <div class="p-4 sm:p-6 flex items-center justify-between gap-4 hover:bg-pure-white/60 transition-colors">
                        <div class="space-y-1 pr-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm sm:text-base font-bold text-ink-black flex items-center gap-2">
                                    <i class="fa-solid fa-qrcode text-charcoal text-sm"></i>
                                    <span>Metode Kasir (Slip QR &amp; Bayar di Kafe)</span>
                                </span>
                                <span id="badgeKasir" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isKasirActive ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }}">
                                    {{ $isKasirActive ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </div>
                            <p class="text-xs text-muted-text leading-relaxed max-w-2xl">
                                Pelanggan memilih menu di website, lalu sistem menghasilkan <strong>Slip Pesanan &amp; Kode QR</strong> untuk ditunjukkan dan dibayar langsung kepada kasir di kafe.
                            </p>
                        </div>

                        {{-- Switch 1 --}}
                        <button type="button" 
                                role="switch" 
                                aria-checked="{{ $isKasirActive ? 'true' : 'false' }}"
                                id="toggleKasirTrack"
                                onclick="toggleMethod('kasir')"
                                class="relative inline-flex h-8 w-14 sm:h-9 sm:w-16 shrink-0 cursor-pointer rounded-full p-1 transition-all duration-300 ease-in-out focus:outline-none select-none {{ $isKasirActive ? 'bg-ink-black shadow-xs' : 'bg-[#D9D6CE]' }}">
                            <span id="toggleKasirThumb"
                                  class="pointer-events-none inline-block h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-white shadow-md transform transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $isKasirActive ? 'translate-x-6 sm:translate-x-7' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    {{-- ROW 2: ZENDO --}}
                    <div class="p-4 sm:p-6 flex items-center justify-between gap-4 hover:bg-pure-white/60 transition-colors">
                        <div class="space-y-1 pr-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm sm:text-base font-bold text-ink-black flex items-center gap-2">
                                    <i class="fa-solid fa-motorcycle text-charcoal text-sm"></i>
                                    <span>Metode Kurir Zendo (Delivery WhatsApp)</span>
                                </span>
                                <span id="badgeZendo" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isZendoActive ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }}">
                                    {{ $isZendoActive ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </div>
                            <p class="text-xs text-muted-text leading-relaxed max-w-2xl">
                                Pelanggan memesan untuk diantar langsung ke rumah/tujuan oleh Kurir Zendo, dan pesanan diteruskan secara instan melalui WhatsApp.
                            </p>
                        </div>

                        {{-- Switch 2 --}}
                        <button type="button" 
                                role="switch" 
                                aria-checked="{{ $isZendoActive ? 'true' : 'false' }}"
                                id="toggleZendoTrack"
                                onclick="toggleMethod('zendo')"
                                class="relative inline-flex h-8 w-14 sm:h-9 sm:w-16 shrink-0 cursor-pointer rounded-full p-1 transition-all duration-300 ease-in-out focus:outline-none select-none {{ $isZendoActive ? 'bg-ink-black shadow-xs' : 'bg-[#D9D6CE]' }}">
                            <span id="toggleZendoThumb"
                                  class="pointer-events-none inline-block h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-white shadow-md transform transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $isZendoActive ? 'translate-x-6 sm:translate-x-7' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                    {{-- ROW 3: LIVE TRACKING --}}
                    <div class="p-4 sm:p-6 flex items-center justify-between gap-4 hover:bg-pure-white/60 transition-colors">
                        <div class="space-y-1 pr-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm sm:text-base font-bold text-ink-black flex items-center gap-2">
                                    <i class="fa-solid fa-map-location-dot text-charcoal text-sm"></i>
                                    <span>Fitur Live Tracking Kurir &amp; Peta GPS</span>
                                </span>
                                <span id="badgeTracking" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isTrackingActive ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }}">
                                    {{ $isTrackingActive ? 'MODE SEKOLAH (PETA AKTIF)' : 'MODE KLIEN (STRUK WA RINGKAS)' }}
                                </span>
                            </div>
                            <p id="descTracking" class="text-xs text-muted-text leading-relaxed max-w-2xl">
                                @if($isTrackingActive)
                                    <strong class="text-ink-black">Mode Sekolah / Ujian (Peta Aktif):</strong> Peta interaktif &amp; GPS aktif saat checkout, tombol pelacakan live Grab-like tampil, dan 1 link live tracking disertakan di WhatsApp.
                                @else
                                    <strong class="text-ink-black">Mode Klien (Sembunyikan Peta):</strong> Peta ditiadakan total dari checkout, pesanan langsung membuka WhatsApp berupa format <strong>struk ringkas belanja</strong> (hanya menu &amp; total tanpa tautan panjang).
                                @endif
                            </p>
                        </div>

                        {{-- Switch 3 --}}
                        <button type="button" 
                                role="switch" 
                                aria-checked="{{ $isTrackingActive ? 'true' : 'false' }}"
                                id="toggleTrackingTrack"
                                onclick="toggleTracking()"
                                class="relative inline-flex h-8 w-14 sm:h-9 sm:w-16 shrink-0 cursor-pointer rounded-full p-1 transition-all duration-300 ease-in-out focus:outline-none select-none {{ $isTrackingActive ? 'bg-ink-black shadow-xs' : 'bg-[#D9D6CE]' }}">
                            <span id="toggleTrackingThumb"
                                  class="pointer-events-none inline-block h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-white shadow-md transform transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] {{ $isTrackingActive ? 'translate-x-6 sm:translate-x-7' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                </div>

                {{-- Status & Quick Action Bar --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-hairline">
                    <div id="autoSaveStatusText" class="flex items-center gap-2 text-xs text-muted-text">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Perubahan status saklar tersimpan otomatis secara instan.</span>
                    </div>
                    <button type="submit" class="bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded-xl transition shadow-xs cursor-pointer self-end sm:self-auto btn-press">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- =========================================================================
         TAB 2: KONTEN HALAMAN DEPAN (HERO, ABOUT/STORY, LAYANAN)
         ========================================================================= --}}
    <div id="panel-tab-konten" class="settings-panel space-y-6 hidden anim-fade-up">
        
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-6 shadow-card">
            
            <div class="border-b border-hairline pb-4">
                <h2 class="text-base font-bold text-ink-black">
                    Kustomisasi Konten Web Customer
                </h2>
                <p class="text-xs text-muted-text mt-1">
                    Sesuaikan judul hero, teks cerita (Our Story), konsep, dan deskripsi layanan kafe agar web fleksibel dan mudah diperbarui.
                </p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Section Hero Text --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-warm-ivory border border-hairline space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-ink-black flex items-center gap-2">
                        <i class="fa-solid fa-desktop text-charcoal"></i>
                        <span>1. Bagian Hero &amp; Tagline Beranda</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="hero_title" class="block text-xs font-bold text-charcoal mb-1">Judul Utama Hero (Heading)</label>
                            <input type="text" name="hero_title" id="hero_title" 
                                   value="{{ $settings['hero_title'] ?? 'Tempat Kembali, Rasa yang Dirindukan' }}"
                                   placeholder="Contoh: Tempat Kembali, Rasa yang Dirindukan"
                                   class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                        </div>

                        <div>
                            <label for="hero_badge" class="block text-xs font-bold text-charcoal mb-1">Badge Tagline Hero</label>
                            <input type="text" name="hero_badge" id="hero_badge" 
                                   value="{{ $settings['hero_badge'] ?? 'NUCOMU CAFE • TULUNGAGUNG' }}"
                                   placeholder="Contoh: NUCOMU CAFE • TULUNGAGUNG"
                                   class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                        </div>
                    </div>

                    <div>
                        <label for="hero_subtitle" class="block text-xs font-bold text-charcoal mb-1">Subjudul / Deskripsi Singkat Hero</label>
                        <textarea name="hero_subtitle" id="hero_subtitle" rows="2"
                                  placeholder="Deskripsi singkat yang tampil di bagian atas beranda..."
                                  class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">{{ $settings['hero_subtitle'] ?? 'Kafe bernuansa monokrom hangat di Tulungagung untuk menikmati kopi single origin, dessert artisanal, dan waktu yang tenang.' }}</textarea>
                    </div>
                </div>

                {{-- Section Our Story / About --}}
                <div class="p-4 sm:p-5 rounded-2xl bg-warm-ivory border border-hairline space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-ink-black flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-charcoal"></i>
                        <span>2. Cerita Tentang Kami (Our Story)</span>
                    </h3>

                    <div>
                        <label for="cafe_story_headline" class="block text-xs font-bold text-charcoal mb-1">Headline Utama Bagian Story</label>
                        <input type="text" name="cafe_story_headline" id="cafe_story_headline" 
                               value="{{ $settings['cafe_story_headline'] ?? 'Berawal dari Rasa, Kembali karena Rindu.' }}"
                               placeholder="Contoh: Berawal dari Rasa, Kembali karena Rindu."
                               class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="cafe_story_p1" class="block text-xs font-bold text-charcoal mb-1">Paragraf Cerita 1</label>
                            <textarea name="cafe_story_p1" id="cafe_story_p1" rows="3"
                                      class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">{{ $settings['cafe_story_p1'] ?? 'Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.' }}</textarea>
                        </div>

                        <div>
                            <label for="cafe_story_p2" class="block text-xs font-bold text-charcoal mb-1">Paragraf Cerita 2</label>
                            <textarea name="cafe_story_p2" id="cafe_story_p2" rows="3"
                                      class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">{{ $settings['cafe_story_p2'] ?? 'Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Action Submit --}}
                <div class="flex justify-end pt-3 border-t border-hairline">
                    <button type="submit" class="bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded-xl transition shadow-xs btn-press">
                        Simpan Konten Halaman Depan
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- =========================================================================
         TAB 3: PROFIL & KONTAK KAFE
         ========================================================================= --}}
    <div id="panel-tab-profil" class="settings-panel space-y-6 hidden anim-fade-up">
        
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-6 shadow-card">
            
            <div class="border-b border-hairline pb-4">
                <h2 class="text-base font-bold text-ink-black">
                    Profil Kafe &amp; Informasi Kontak Resmi
                </h2>
                <p class="text-xs text-muted-text mt-1">
                    Informasi ini digunakan pada navbar, footer, kartu kontak, dan template pesan WhatsApp.
                </p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="store_name" class="block text-xs font-bold text-charcoal mb-1.5">Nama Kafe / Brand</label>
                        <input type="text" name="store_name" id="store_name" value="{{ $settings['store_name'] ?? $settings['cafe_name'] ?? 'Nucomu Cafe' }}" required
                            class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-charcoal mb-1.5">Nomor WhatsApp CS / Kasir</label>
                        <input type="text" name="phone" id="phone" value="{{ $settings['phone'] ?? $settings['cafe_whatsapp'] ?? '+62 859-3496-0910' }}" required
                            class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono focus:outline-none focus:border-ink-black">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-charcoal mb-1.5">Alamat Lengkap Kafe</label>
                    <textarea name="address" id="address" rows="2" required
                        class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">{{ $settings['address'] ?? $settings['cafe_address'] ?? 'Jl. Mayor Sujadi, Manggisan, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221' }}</textarea>
                </div>

                <div>
                    <label for="cafe_landmark" class="block text-xs font-bold text-charcoal mb-1.5">Patokan Lokasi Kafe</label>
                    <input type="text" name="cafe_landmark" id="cafe_landmark" value="{{ $settings['cafe_landmark'] ?? '200 meter ke timur dari Bravo Swalayan (sisi utara jalan sebelum perempatan).' }}"
                        class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="instagram" class="block text-xs font-bold text-charcoal mb-1.5">Instagram</label>
                        <input type="text" name="instagram" id="instagram" value="{{ $settings['instagram'] ?? $settings['cafe_instagram'] ?? '@nucomucafe' }}"
                            class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono focus:outline-none focus:border-ink-black">
                    </div>

                    <div>
                        <label for="cafe_tiktok" class="block text-xs font-bold text-charcoal mb-1.5">TikTok</label>
                        <input type="text" name="cafe_tiktok" id="cafe_tiktok" value="{{ $settings['cafe_tiktok'] ?? '@nucomucafe' }}"
                            class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono focus:outline-none focus:border-ink-black">
                    </div>

                    <div>
                        <label for="cafe_email" class="block text-xs font-bold text-charcoal mb-1.5">Email Kafe</label>
                        <input type="email" name="cafe_email" id="cafe_email" value="{{ $settings['cafe_email'] ?? 'hello@nucomu.com' }}"
                            class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono focus:outline-none focus:border-ink-black">
                    </div>
                </div>

                <div>
                    <label for="cafe_maps_url" class="block text-xs font-bold text-charcoal mb-1.5">Link Google Maps / Petunjuk Arah</label>
                    <input type="url" name="cafe_maps_url" id="cafe_maps_url" value="{{ $settings['cafe_maps_url'] ?? 'https://maps.google.com/?q=Nucomu+Cafe+Tulungagung' }}"
                        placeholder="https://maps.google.com/..."
                        class="w-full bg-white border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono focus:outline-none focus:border-ink-black">
                </div>

                <div class="flex justify-end pt-3 border-t border-hairline">
                    <button type="submit" class="bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded-xl transition shadow-xs btn-press">
                        Simpan Profil &amp; Kontak
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- =========================================================================
         TAB 4: JAM OPERASIONAL 7 HARI
         ========================================================================= --}}
    <div id="panel-tab-jam" class="settings-panel space-y-6 hidden anim-fade-up">
        
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-6 shadow-card">
            
            <div class="border-b border-hairline pb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-ink-black">
                        Jadwal Jam Operasional (Senin — Minggu)
                    </h2>
                    <p class="text-xs text-muted-text mt-1">
                        Jam buka dan tutup yang ditampilkan pada customer web dan widget kontak.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.settings.hours') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-3">
                    @foreach($operatingHours as $oh)
                        <div class="bg-warm-ivory border border-hairline rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <input type="hidden" name="hours[{{ $oh->id }}][id]" value="{{ $oh->id }}">
                            
                            <div class="w-36">
                                <span class="font-bold text-ink-black text-sm block">{{ $oh->day_name }}</span>
                                <span class="text-[10px] text-muted-text font-mono">Hari ke-{{ $oh->day_of_week }}</span>
                            </div>

                            <div class="flex items-center space-x-3 text-xs">
                                <div>
                                    <span class="text-muted-text block text-[10px] uppercase font-bold mb-1">Jam Buka</span>
                                    <input type="time" name="hours[{{ $oh->id }}][open_time]" value="{{ $oh->open_time }}"
                                        class="bg-white border border-hairline rounded-lg px-3 py-1.5 text-ink-black text-xs font-mono">
                                </div>
                                <span class="text-muted-text pt-4 font-bold">-</span>
                                <div>
                                    <span class="text-muted-text block text-[10px] uppercase font-bold mb-1">Jam Tutup</span>
                                    <input type="time" name="hours[{{ $oh->id }}][close_time]" value="{{ $oh->close_time }}"
                                        class="bg-white border border-hairline rounded-lg px-3 py-1.5 text-ink-black text-xs font-mono">
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 pt-2 sm:pt-0">
                                <label class="flex items-center space-x-2 cursor-pointer text-xs font-semibold">
                                    <input type="checkbox" name="hours[{{ $oh->id }}][is_closed]" value="1" {{ $oh->is_closed ? 'checked' : '' }}
                                        class="w-4 h-4 text-ink-black bg-white border-hairline rounded">
                                    <span class="{{ $oh->is_closed ? 'text-rose-600 font-bold' : 'text-charcoal' }}">Tutup / Libur</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end pt-4 border-t border-hairline">
                    <button type="submit" class="bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded-xl transition shadow-xs btn-press">
                        Simpan Jam Operasional
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<script>
    // Tab Switcher
    function switchSettingsTab(tabId) {
        document.querySelectorAll('.settings-panel').forEach(panel => panel.classList.add('hidden'));
        document.querySelectorAll('.settings-nav-tab').forEach(btn => {
            btn.classList.remove('bg-ink-black', 'text-white', 'shadow-xs');
            btn.classList.add('text-muted-text', 'hover:text-ink-black', 'hover:bg-warm-stone/80');
        });

        const activePanel = document.getElementById('panel-' + tabId);
        const activeBtn = document.getElementById('btn-' + tabId);

        if (activePanel) {
            activePanel.classList.remove('hidden');
            if (window.initAdminScrollReveal) {
                setTimeout(window.initAdminScrollReveal, 50);
            }
        }
        if (activeBtn) {
            activeBtn.classList.remove('text-muted-text', 'hover:text-ink-black', 'hover:bg-warm-stone/80');
            activeBtn.classList.add('bg-ink-black', 'text-white', 'shadow-xs');
        }
    }

    // Toggle logic for transaction modes
    let stateKasir = {{ $isKasirActive ? 'true' : 'false' }};
    let stateZendo = {{ $isZendoActive ? 'true' : 'false' }};
    let stateTracking = {{ $isTrackingActive ? 'true' : 'false' }};

    function updateSwitchVisual(trackId, thumbId, isOn) {
        const track = document.getElementById(trackId);
        const thumb = document.getElementById(thumbId);
        if (!track || !thumb) return;

        if (isOn) {
            track.classList.remove('bg-[#D9D6CE]');
            track.classList.add('bg-ink-black', 'shadow-xs');
            thumb.classList.remove('translate-x-0');
            thumb.classList.add('translate-x-6', 'sm:translate-x-7');
        } else {
            track.classList.remove('bg-ink-black', 'shadow-xs');
            track.classList.add('bg-[#D9D6CE]');
            thumb.classList.remove('translate-x-6', 'sm:translate-x-7');
            thumb.classList.add('translate-x-0');
        }
    }

    function toggleMethod(target) {
        if (target === 'kasir') {
            if (stateKasir && !stateZendo) {
                alert('Minimal salah satu metode transaksi (Kasir atau Kurir Zendo) harus tetap aktif agar pelanggan dapat memesan.');
                return;
            }
            stateKasir = !stateKasir;
        } else if (target === 'zendo') {
            if (stateZendo && !stateKasir) {
                alert('Minimal salah satu metode transaksi (Kasir atau Kurir Zendo) harus tetap aktif agar pelanggan dapat memesan.');
                return;
            }
            stateZendo = !stateZendo;
        }

        updateSwitchVisual('toggleKasirTrack', 'toggleKasirThumb', stateKasir);
        updateSwitchVisual('toggleZendoTrack', 'toggleZendoThumb', stateZendo);

        const badgeKasir = document.getElementById('badgeKasir');
        if (badgeKasir) {
            badgeKasir.textContent = stateKasir ? 'AKTIF' : 'NONAKTIF';
            badgeKasir.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full ' + 
                (stateKasir ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline');
        }

        const badgeZendo = document.getElementById('badgeZendo');
        if (badgeZendo) {
            badgeZendo.textContent = stateZendo ? 'AKTIF' : 'NONAKTIF';
            badgeZendo.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full ' + 
                (stateZendo ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline');
        }

        let newMode = 'both';
        if (stateKasir && !stateZendo) newMode = 'kasir';
        if (!stateKasir && stateZendo) newMode = 'zendo';
        document.getElementById('inputTransactionMode').value = newMode;

        const headerBadge = document.getElementById('headerTransactionBadge');
        if (headerBadge) {
            headerBadge.textContent = newMode === 'both' ? 'Kasir + Zendo Aktif' : (newMode === 'kasir' ? 'Hanya Kasir Aktif' : 'Hanya Kurir Zendo Aktif');
        }

        autoSaveSetting('transaction_mode', newMode);
    }

    function toggleTracking() {
        stateTracking = !stateTracking;
        updateSwitchVisual('toggleTrackingTrack', 'toggleTrackingThumb', stateTracking);

        const badge = document.getElementById('badgeTracking');
        const desc = document.getElementById('descTracking');
        const headerBadge = document.getElementById('headerTrackingBadge');
        const hideInput = document.getElementById('inputHideLiveTracking');

        const hideVal = stateTracking ? '0' : '1';
        hideInput.value = hideVal;

        if (stateTracking) {
            badge.textContent = 'MODE SEKOLAH (PETA AKTIF)';
            badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200';
            desc.innerHTML = '<strong class="text-ink-black">Mode Sekolah / Ujian (Peta Aktif):</strong> Peta interaktif & GPS aktif saat checkout, tombol pelacakan live Grab-like tampil, dan 1 link live tracking disertakan di WhatsApp.';
            if (headerBadge) {
                headerBadge.textContent = 'Mode Sekolah (Peta Live GPS)';
                headerBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold';
            }
        } else {
            badge.textContent = 'MODE KLIEN (STRUK WA RINGKAS)';
            badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-warm-stone text-muted-text border border-hairline';
            desc.innerHTML = '<strong class="text-ink-black">Mode Klien (Sembunyikan Peta):</strong> Peta ditiadakan total dari checkout, pesanan langsung membuka WhatsApp berupa format <strong>struk ringkas belanja</strong> (hanya menu & total tanpa tautan panjang).';
            if (headerBadge) {
                headerBadge.textContent = 'Mode Klien (Struk Ringkas WA)';
                headerBadge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-warm-stone text-muted-text border border-hairline text-[10px] font-bold';
            }
        }

        autoSaveSetting('hide_live_tracking', hideVal);
    }

    function autoSaveSetting(key, value) {
        const statusText = document.getElementById('autoSaveStatusText');
        if (statusText) {
            statusText.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span><span>Menyimpan ke sistem...</span>';
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append(key, value);

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
            if (statusText) {
                statusText.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span><span class="text-emerald-800 font-semibold">Perubahan tersimpan otomatis (' + new Date().toLocaleTimeString('id-ID') + ')</span>';
            }
            if (window.showAdminToast) {
                window.showAdminToast('Pengaturan saklar transaksi berhasil disimpan otomatis ke sistem.', 'success', 'Tersimpan Otomatis');
            }
        })
        .catch(err => {
            if (statusText) {
                statusText.innerHTML = '<span class="w-2 h-2 rounded-full bg-rose-500"></span><span class="text-rose-700">Gagal auto-save, silakan klik tombol simpan manual.</span>';
            }
            if (window.showAdminToast) {
                window.showAdminToast('Gagal auto-save otomatis. Silakan gunakan tombol Simpan Perubahan.', 'error', 'Penyimpanan Gagal');
            }
        });
    }
</script>
@endsection
