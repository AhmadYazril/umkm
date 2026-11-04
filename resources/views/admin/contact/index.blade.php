@extends('layouts.admin')
@section('title', 'Pengaturan Kontak & Lokasi — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-2xs anim-fade-up">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold space-y-1 shadow-2xs anim-fade-up">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Terdapat kesalahan pada formulir kontak:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-800">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Top Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Modul 9 &bull; Kontak, Lokasi &amp; Media Sosial</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Pengaturan Kontak &amp; Lokasi
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kelola nomor WhatsApp, rute Google Maps, tautan media sosial, serta sinkronisasi pesanan Zendo &amp; GrabFood yang tampil di seluruh website.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('home') }}#contact" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Lihat Bagian Kontak di Beranda Website">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-muted-text"></i>
                <span>Bagian Kontak Web</span>
            </a>
            <a href="{{ route('contact') }}" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Lihat Halaman Kontak Lengkap">
                <i class="fa-regular fa-map text-xs text-muted-text"></i>
                <span>Halaman /kontak</span>
            </a>
        </div>
    </div>

    {{-- Main Form & Live Preview Grid --}}
    <form action="{{ route('admin.contact.update') }}" method="POST" id="contactForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Left Column: Form Fields (7 Cols) --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- CARD 1: Layanan WhatsApp & Pemesanan Online --}}
                <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-ink-black">Layanan WhatsApp &amp; Pemesanan Online</h2>
                            <p class="text-[11px] text-muted-text">Nomor layanan chat pelanggan dan integrasi checkout Modul 8</p>
                        </div>
                    </div>

                    {{-- 1. WhatsApp Cafe (Required) --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="cafe_whatsapp" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <span>Nomor WhatsApp Cafe Resmi</span>
                                <span class="text-rose-500">*</span>
                                <span class="text-[10px] font-normal px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">Wajib</span>
                            </label>
                            <a id="btnTestCafeWa" href="#" target="_blank" rel="noopener noreferrer"
                               class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>Tes Chat WA</span>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input type="text" name="cafe_whatsapp" id="cafe_whatsapp"
                                   value="{{ old('cafe_whatsapp', $contact['cafe_whatsapp']) }}"
                                   required
                                   placeholder="Contoh: 081234567890 atau 6281234567890"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Nomor otomatis dinormalisasi ke format <code class="px-1 py-0.5 rounded bg-warm-stone font-mono text-[10px]">628xxx</code>. Dipakai di seluruh tombol reservasi, chat web, dan footer.
                        </p>
                        @error('cafe_whatsapp')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 2. WhatsApp Kurir Zendo (Shared with Module 8) --}}
                    <div class="space-y-1.5 pt-3 border-t border-hairline/60">
                        <div class="flex items-center justify-between">
                            <label for="zendo_whatsapp" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <span>Nomor WhatsApp Kurir Zendo Delivery</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                                    <i class="fa-solid fa-link text-[9px] mr-1"></i>Sinkron Modul 8
                                </span>
                            </label>
                            <a id="btnTestZendoWa" href="#" target="_blank" rel="noopener noreferrer"
                               class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>Tes Chat Zendo</span>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-solid fa-motorcycle"></i>
                            </span>
                            <input type="text" name="zendo_whatsapp" id="zendo_whatsapp"
                                   value="{{ old('zendo_whatsapp', $contact['zendo_whatsapp']) }}"
                                   placeholder="Contoh: 085707300240"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Kunci setting: <code class="px-1 py-0.5 rounded bg-warm-stone font-mono text-[10px]">zendo_whatsapp</code>. Tersinkronisasi dengan alur checkout Zendo Delivery (Modul 8).
                        </p>
                        @error('zendo_whatsapp')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 3. Link GrabFood (Shared with Module 8) --}}
                    <div class="space-y-1.5 pt-3 border-t border-hairline/60">
                        <div class="flex items-center justify-between">
                            <label for="grabfood_url" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <span>Tautan Outlet GrabFood</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <i class="fa-solid fa-link text-[9px] mr-1"></i>Sinkron Modul 8
                                </span>
                            </label>
                            <a id="btnTestGrabfood" href="#" target="_blank" rel="noopener noreferrer"
                               class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>Tes Buka GrabFood</span>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-solid fa-bag-shopping"></i>
                            </span>
                            <input type="url" name="grabfood_url" id="grabfood_url"
                                   value="{{ old('grabfood_url', $contact['grabfood_url']) }}"
                                   placeholder="Contoh: https://food.grab.com/id/id/restaurant/nucomu-cafe/..."
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Kunci setting: <code class="px-1 py-0.5 rounded bg-warm-stone font-mono text-[10px]">grabfood_url</code>. Jika diisi, tombol GrabFood akan tampil di daftar menu, keranjang, dan footer. Kosongkan jika sedang tidak aktif.
                        </p>
                        @error('grabfood_url')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- CARD 2: Media Sosial & Email --}}
                <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
                        <div class="w-8 h-8 rounded-xl bg-pink-50 border border-pink-200 text-pink-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-share-nodes"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-ink-black">Media Sosial &amp; Email Publik</h2>
                            <p class="text-[11px] text-muted-text">Tautan akun resmi yang tampil di footer &amp; menu web</p>
                        </div>
                    </div>

                    {{-- 4. Instagram --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="cafe_instagram" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <i class="fa-brands fa-instagram text-pink-600"></i>
                                <span>Tautan Instagram</span>
                            </label>
                            <a id="btnTestInstagram" href="#" target="_blank" rel="noopener noreferrer"
                               class="text-[11px] font-bold text-pink-700 hover:text-pink-900 inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>Tes Link</span>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-brands fa-instagram"></i>
                            </span>
                            <input type="text" name="cafe_instagram" id="cafe_instagram"
                                   value="{{ old('cafe_instagram', $contact['cafe_instagram']) }}"
                                   placeholder="Contoh: https://www.instagram.com/nucomu.cafe atau @nucomu.cafe"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Bisa berupa URL lengkap atau username (@nucomu.cafe). Jika dikosongkan, ikon Instagram di web otomatis disembunyikan.
                        </p>
                        @error('cafe_instagram')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 5. TikTok --}}
                    <div class="space-y-1.5 pt-3 border-t border-hairline/60">
                        <div class="flex items-center justify-between">
                            <label for="cafe_tiktok" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <i class="fa-brands fa-tiktok text-ink-black"></i>
                                <span>Tautan TikTok</span>
                            </label>
                            <a id="btnTestTiktok" href="#" target="_blank" rel="noopener noreferrer"
                               class="text-[11px] font-bold text-charcoal hover:text-ink-black inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>Tes Link</span>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-brands fa-tiktok"></i>
                            </span>
                            <input type="text" name="cafe_tiktok" id="cafe_tiktok"
                                   value="{{ old('cafe_tiktok', $contact['cafe_tiktok']) }}"
                                   placeholder="Contoh: https://www.tiktok.com/@nucomu.cafe atau @nucomu.cafe"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Bisa berupa URL lengkap atau username (@nucomu.cafe). Jika dikosongkan, ikon TikTok di web otomatis disembunyikan.
                        </p>
                        @error('cafe_tiktok')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 6. Email Resmi Cafe --}}
                    <div class="space-y-1.5 pt-3 border-t border-hairline/60">
                        <div class="flex items-center justify-between">
                            <label for="cafe_email" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <i class="fa-regular fa-envelope text-blue-600"></i>
                                <span>Email Resmi Cafe (Opsional)</span>
                            </label>
                            <a id="btnTestEmail" href="#"
                               class="text-[11px] font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1">
                                <i class="fa-regular fa-paper-plane"></i>
                                <span>Tes Kirim Email</span>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <input type="email" name="cafe_email" id="cafe_email"
                                   value="{{ old('cafe_email', $contact['cafe_email']) }}"
                                   placeholder="Contoh: info@nucomucafe.com atau nucomu.tulungagung@gmail.com"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Digunakan untuk keperluan kontak bisnis/kemitraan dan tampil di footer website bila diisi.
                        </p>
                        @error('cafe_email')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- CARD 3: Alamat & Google Maps --}}
                <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-ink-black">Alamat &amp; Google Maps</h2>
                            <p class="text-[11px] text-muted-text">Lokasi fisik kafe, tautan rute, dan peta embed</p>
                        </div>
                    </div>

                    {{-- 7. Alamat Fisik --}}
                    <div class="space-y-1.5">
                        <label for="cafe_address" class="text-xs font-bold text-ink-black">
                            Alamat Lengkap Cafe
                        </label>
                        <textarea name="cafe_address" id="cafe_address" rows="3"
                                  placeholder="Contoh: Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition leading-relaxed">{{ old('cafe_address', $contact['cafe_address']) }}</textarea>
                        <p class="text-[11px] text-muted-text">
                            Tampil pada kartu lokasi beranda, halaman kontak, dan footer.
                        </p>
                        @error('cafe_address')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 8. Tautan Rute Google Maps --}}
                    <div class="space-y-1.5 pt-3 border-t border-hairline/60">
                        <div class="flex items-center justify-between">
                            <label for="cafe_maps_url" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <i class="fa-solid fa-diamond-turn-right text-blue-600"></i>
                                <span>Tautan Rute Google Maps</span>
                            </label>
                            <a id="btnTestMapsUrl" href="#" target="_blank" rel="noopener noreferrer"
                               class="text-[11px] font-bold text-blue-700 hover:text-blue-900 inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>Tes Buka Rute</span>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-text text-xs">
                                <i class="fa-solid fa-location-arrow"></i>
                            </span>
                            <input type="text" name="cafe_maps_url" id="cafe_maps_url"
                                   value="{{ old('cafe_maps_url', $contact['cafe_maps_url']) }}"
                                   placeholder="Contoh: https://maps.app.goo.gl/... atau https://maps.google.com/?q=Nucomu+Cafe"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        </div>
                        <p class="text-[11px] text-muted-text">
                            Tautan saat pelanggan mengklik tombol "Buka Rute Google Maps" di website.
                        </p>
                        @error('cafe_maps_url')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 9. Kode Embed Google Maps (iframe / URL src) --}}
                    <div class="space-y-1.5 pt-3 border-t border-hairline/60">
                        <div class="flex items-center justify-between">
                            <label for="cafe_maps_embed" class="text-xs font-bold text-ink-black flex items-center gap-1.5">
                                <i class="fa-solid fa-code text-purple-600"></i>
                                <span>Kode Embed Peta Google Maps</span>
                            </label>
                            <span class="text-[11px] text-muted-text">Bisa paste tag &lt;iframe&gt; lengkap</span>
                        </div>
                        <textarea name="cafe_maps_embed" id="cafe_maps_embed" rows="3"
                                  placeholder="Contoh: <iframe src=&quot;https://www.google.com/maps/embed?...&quot; ...></iframe> atau langsung URL https://..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-mono text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition leading-relaxed">{{ old('cafe_maps_embed', $contact['cafe_maps_embed']) }}</textarea>
                        <p class="text-[11px] text-muted-text">
                            Jika kosong, website otomatis menampilkan peta interaktif berdasarkan nama dan alamat kafe di atas.
                        </p>
                        @error('cafe_maps_embed')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- CARD 4: Jam Operasional Ringkas --}}
                <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center font-bold text-sm">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-ink-black">Ringkasan Jam Operasional</h2>
                            <p class="text-[11px] text-muted-text">Teks jam buka untuk tampilan ringkas beranda &amp; footer</p>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="cafe_opening_hours" class="text-xs font-bold text-ink-black">
                            Teks Jam Buka Ringkas
                        </label>
                        <input type="text" name="cafe_opening_hours" id="cafe_opening_hours"
                               value="{{ old('cafe_opening_hours', $contact['cafe_opening_hours']) }}"
                               placeholder="Contoh: Senin – Sabtu: 11:00 – 21:00 WIB | Minggu: 11:00 – 22:00 WIB"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-hairline bg-pure-white text-xs font-medium text-ink-black focus:outline-none focus:ring-2 focus:ring-ink-black/20 focus:border-ink-black transition">
                        <p class="text-[11px] text-muted-text">
                            Tampil pada bar navigasi mobile dan ringkasan tab jam buka di web.
                        </p>
                        @error('cafe_opening_hours')
                            <p class="text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 7-Day Operating Hours Reference --}}
                    @if(isset($operatingHours) && $operatingHours->count() > 0)
                        <div class="pt-3 border-t border-hairline/60">
                            <details class="group cursor-pointer">
                                <summary class="flex items-center justify-between text-xs font-bold text-charcoal hover:text-ink-black list-none">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-calendar-week text-muted-text"></i>
                                        <span>Lihat Jadwal Harian Database (7 Hari)</span>
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-muted-text group-open:rotate-180 transition-transform"></i>
                                </summary>
                                <div class="mt-3 overflow-hidden rounded-xl border border-hairline bg-warm-canvas/40 divide-y divide-hairline text-[11px]">
                                    @foreach($operatingHours as $oh)
                                        <div class="flex items-center justify-between px-3.5 py-2">
                                            <span class="font-semibold text-charcoal">{{ $oh->day_name }}</span>
                                            @if($oh->is_closed)
                                                <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[10px] border border-rose-200">LIBUR</span>
                                            @else
                                                <span class="font-mono text-ink-black font-medium">{{ date('H:i', strtotime($oh->open_time)) }} &ndash; {{ date('H:i', strtotime($oh->close_time)) }} WIB</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        </div>
                    @endif
                </div>

                {{-- Submit Action Bar --}}
                <div class="p-4 rounded-2xl bg-pure-white border border-hairline shadow-xs flex items-center justify-between gap-4 sticky bottom-4 z-20">
                    <div class="text-[11px] text-muted-text hidden sm:block">
                        <i class="fa-solid fa-shield-halved text-emerald-600 mr-1"></i>
                        <span>Perubahan langsung tersimpan ke database &amp; berlaku di seluruh web.</span>
                    </div>
                    <button type="submit" id="btnSubmitContact"
                            class="w-full sm:w-auto px-6 py-3 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm flex items-center justify-center gap-2 btn-press">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Pengaturan Kontak</span>
                    </button>
                </div>

            </div>

            {{-- Right Column: Live Preview & Channel Status Panel (5 Cols) --}}
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">

                {{-- Status & Preview Channels Card --}}
                <div class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-5">
                    
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider mb-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Live Preview Channel</span>
                        </div>
                        <h3 class="text-sm font-bold text-ink-black">Status Saluran Kontak di Web</h3>
                        <p class="text-[11px] text-muted-text mt-0.5">
                            Platform yang diisi akan otomatis aktif dan ditampilkan di website pelanggan.
                        </p>
                    </div>

                    {{-- Channel Status Grid --}}
                    <div class="space-y-2.5">
                        
                        {{-- WhatsApp Cafe Card --}}
                        <div id="previewCardCafeWa" class="p-3 rounded-xl border border-hairline bg-warm-canvas/50 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 text-sm shadow-2xs">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink-black">WhatsApp Cafe</span>
                                        <span id="badgeCafeWa" class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                    </div>
                                    <p id="labelCafeWa" class="text-[11px] font-mono text-muted-text truncate">
                                        {{ $contact['cafe_whatsapp'] ?: '-' }}
                                    </p>
                                </div>
                            </div>
                            <a id="linkActionCafeWa" href="#" target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 rounded-lg bg-pure-white border border-hairline flex items-center justify-center text-charcoal hover:text-emerald-700 hover:border-emerald-300 transition shadow-2xs"
                               title="Tes Chat WhatsApp Cafe">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>

                        {{-- WhatsApp Zendo Card --}}
                        <div id="previewCardZendoWa" class="p-3 rounded-xl border border-hairline bg-warm-canvas/50 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 text-sm shadow-2xs">
                                    <i class="fa-solid fa-motorcycle"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink-black">WhatsApp Zendo</span>
                                        <span id="badgeZendoWa" class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                    </div>
                                    <p id="labelZendoWa" class="text-[11px] font-mono text-muted-text truncate">
                                        {{ $contact['zendo_whatsapp'] ?: 'Belum diisi' }}
                                    </p>
                                </div>
                            </div>
                            <a id="linkActionZendoWa" href="#" target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 rounded-lg bg-pure-white border border-hairline flex items-center justify-center text-charcoal hover:text-amber-700 hover:border-amber-300 transition shadow-2xs"
                               title="Tes Chat WhatsApp Zendo">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>

                        {{-- GrabFood Card --}}
                        <div id="previewCardGrabfood" class="p-3 rounded-xl border border-hairline bg-warm-canvas/50 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-[#00B14F] text-white flex items-center justify-center shrink-0 text-sm shadow-2xs">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink-black">GrabFood</span>
                                        <span id="badgeGrabfood" class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ !empty($contact['grabfood_url']) ? 'bg-emerald-100 text-emerald-800' : 'bg-warm-stone text-muted-text' }}">
                                            {{ !empty($contact['grabfood_url']) ? 'Tampil di Web' : 'Disembunyikan' }}
                                        </span>
                                    </div>
                                    <p id="labelGrabfood" class="text-[11px] text-muted-text truncate">
                                        {{ !empty($contact['grabfood_url']) ? $contact['grabfood_url'] : 'Belum diisi (tidak ada tombol mati)' }}
                                    </p>
                                </div>
                            </div>
                            <a id="linkActionGrabfood" href="#" target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 rounded-lg bg-pure-white border border-hairline flex items-center justify-center text-charcoal hover:text-emerald-700 hover:border-emerald-300 transition shadow-2xs"
                               title="Tes Buka GrabFood">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>

                        {{-- Instagram Card --}}
                        <div id="previewCardInstagram" class="p-3 rounded-xl border border-hairline bg-warm-canvas/50 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white flex items-center justify-center shrink-0 text-sm shadow-2xs">
                                    <i class="fa-brands fa-instagram"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink-black">Instagram</span>
                                        <span id="badgeInstagram" class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ !empty($contact['cafe_instagram']) ? 'bg-emerald-100 text-emerald-800' : 'bg-warm-stone text-muted-text' }}">
                                            {{ !empty($contact['cafe_instagram']) ? 'Tampil di Footer' : 'Disembunyikan' }}
                                        </span>
                                    </div>
                                    <p id="labelInstagram" class="text-[11px] text-muted-text truncate">
                                        {{ !empty($contact['cafe_instagram']) ? $contact['cafe_instagram'] : 'Belum diisi' }}
                                    </p>
                                </div>
                            </div>
                            <a id="linkActionInstagram" href="#" target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 rounded-lg bg-pure-white border border-hairline flex items-center justify-center text-charcoal hover:text-pink-700 hover:border-pink-300 transition shadow-2xs"
                               title="Tes Buka Instagram">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>

                        {{-- TikTok Card --}}
                        <div id="previewCardTiktok" class="p-3 rounded-xl border border-hairline bg-warm-canvas/50 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-black text-white flex items-center justify-center shrink-0 text-sm shadow-2xs">
                                    <i class="fa-brands fa-tiktok"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink-black">TikTok</span>
                                        <span id="badgeTiktok" class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ !empty($contact['cafe_tiktok']) ? 'bg-emerald-100 text-emerald-800' : 'bg-warm-stone text-muted-text' }}">
                                            {{ !empty($contact['cafe_tiktok']) ? 'Tampil di Footer' : 'Disembunyikan' }}
                                        </span>
                                    </div>
                                    <p id="labelTiktok" class="text-[11px] text-muted-text truncate">
                                        {{ !empty($contact['cafe_tiktok']) ? $contact['cafe_tiktok'] : 'Belum diisi' }}
                                    </p>
                                </div>
                            </div>
                            <a id="linkActionTiktok" href="#" target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 rounded-lg bg-pure-white border border-hairline flex items-center justify-center text-charcoal hover:text-black hover:border-charcoal transition shadow-2xs"
                               title="Tes Buka TikTok">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>

                        {{-- Email Card --}}
                        <div id="previewCardEmail" class="p-3 rounded-xl border border-hairline bg-warm-canvas/50 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 text-sm shadow-2xs">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink-black">Email Resmi</span>
                                        <span id="badgeEmail" class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ !empty($contact['cafe_email']) ? 'bg-emerald-100 text-emerald-800' : 'bg-warm-stone text-muted-text' }}">
                                            {{ !empty($contact['cafe_email']) ? 'Tampil di Footer' : 'Disembunyikan' }}
                                        </span>
                                    </div>
                                    <p id="labelEmail" class="text-[11px] text-muted-text truncate">
                                        {{ !empty($contact['cafe_email']) ? $contact['cafe_email'] : 'Belum diisi' }}
                                    </p>
                                </div>
                            </div>
                            <a id="linkActionEmail" href="#"
                               class="w-7 h-7 rounded-lg bg-pure-white border border-hairline flex items-center justify-center text-charcoal hover:text-blue-700 hover:border-blue-300 transition shadow-2xs"
                               title="Tes Kirim Email">
                                <i class="fa-regular fa-paper-plane text-[10px]"></i>
                            </a>
                        </div>

                        {{-- Google Maps Rute Card --}}
                        <div id="previewCardMapsUrl" class="p-3 rounded-xl border border-hairline bg-warm-canvas/50 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-red-500 text-white flex items-center justify-center shrink-0 text-sm shadow-2xs">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-ink-black">Rute Google Maps</span>
                                        <span id="badgeMapsUrl" class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ !empty($contact['cafe_maps_url']) ? 'bg-emerald-100 text-emerald-800' : 'bg-warm-stone text-muted-text' }}">
                                            {{ !empty($contact['cafe_maps_url']) ? 'Rute Aktif' : 'Belum diisi' }}
                                        </span>
                                    </div>
                                    <p id="labelMapsUrl" class="text-[11px] text-muted-text truncate">
                                        {{ !empty($contact['cafe_maps_url']) ? $contact['cafe_maps_url'] : 'Belum diisi' }}
                                    </p>
                                </div>
                            </div>
                            <a id="linkActionMapsUrl" href="#" target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 rounded-lg bg-pure-white border border-hairline flex items-center justify-center text-charcoal hover:text-red-700 hover:border-red-300 transition shadow-2xs"
                               title="Tes Buka Google Maps">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>

                    </div>

                    {{-- Mini Web Footer Icon Preview Strip --}}
                    <div class="pt-4 border-t border-hairline space-y-2">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-bold text-ink-black">Simulasi Ikon di Footer Web:</span>
                            <span class="text-[10px] text-muted-text">Hanya ikon aktif yang tampil</span>
                        </div>
                        <div class="p-3 rounded-xl bg-warm-stone/50 border border-hairline flex items-center gap-2 flex-wrap" id="miniFooterIconsContainer">
                            {{-- Live Footer Icon Chips --}}
                            <span id="miniIconInstagram" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-pure-white border border-hairline text-[11px] font-semibold text-charcoal {{ empty($contact['cafe_instagram']) ? 'hidden' : '' }}">
                                <i class="fa-brands fa-instagram text-pink-600"></i>
                                <span>Instagram</span>
                            </span>
                            <span id="miniIconTiktok" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-pure-white border border-hairline text-[11px] font-semibold text-charcoal {{ empty($contact['cafe_tiktok']) ? 'hidden' : '' }}">
                                <i class="fa-brands fa-tiktok text-black"></i>
                                <span>TikTok</span>
                            </span>
                            <span id="miniIconGrabfood" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-pure-white border border-hairline text-[11px] font-semibold text-charcoal {{ empty($contact['grabfood_url']) ? 'hidden' : '' }}">
                                <i class="fa-solid fa-bag-shopping text-[#00B14F]"></i>
                                <span>GrabFood</span>
                            </span>
                            <span id="miniIconEmail" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-pure-white border border-hairline text-[11px] font-semibold text-charcoal {{ empty($contact['cafe_email']) ? 'hidden' : '' }}">
                                <i class="fa-regular fa-envelope text-blue-600"></i>
                                <span>Email</span>
                            </span>
                            <span id="miniIconMaps" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-pure-white border border-hairline text-[11px] font-semibold text-charcoal {{ empty($contact['cafe_maps_url']) ? 'hidden' : '' }}">
                                <i class="fa-solid fa-location-dot text-red-600"></i>
                                <span>Maps</span>
                            </span>
                            <span id="miniIconEmptyWarning" class="text-[10px] text-muted-text italic {{ (!empty($contact['cafe_instagram']) || !empty($contact['cafe_tiktok']) || !empty($contact['grabfood_url']) || !empty($contact['cafe_email']) || !empty($contact['cafe_maps_url'])) ? 'hidden' : '' }}">
                                Tidak ada tautan eksternal yang diisi.
                            </span>
                        </div>
                    </div>

                    {{-- Live Google Maps Iframe Frame --}}
                    <div class="pt-4 border-t border-hairline space-y-2">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-bold text-ink-black">Pratinjau Peta Interaktif:</span>
                            <button type="button" onclick="refreshMapPreview()" class="text-[10px] text-muted-text hover:text-ink-black flex items-center gap-1">
                                <i class="fa-solid fa-rotate-right"></i>
                                <span>Muat Ulang</span>
                            </button>
                        </div>
                        <div class="w-full h-44 rounded-xl border border-hairline overflow-hidden bg-warm-stone relative shadow-inner">
                            <iframe id="liveMapFrame"
                                    src="{{ !empty($contact['cafe_maps_embed']) ? $contact['cafe_maps_embed'] : ('https://maps.google.com/maps?q=' . urlencode($contact['cafe_address'] ?: 'Nucomu Cafe Tulungagung') . '&t=&z=16&ie=UTF8&iwloc=&output=embed') }}"
                                    class="w-full h-full border-0"
                                    loading="lazy"
                                    title="Pratinjau Peta Lokasi Kafe">
                            </iframe>
                        </div>
                        <p class="text-[10px] text-muted-text italic">
                            *Peta ini yang tampil di beranda dan halaman /kontak untuk memandu pelanggan datang langsung.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </form>

</div>

@push('scripts')
<script>
(function() {
    // Normalization helper for phones
    function cleanPhoneTo62(raw) {
        if (!raw) return '';
        var digits = raw.replace(/[^0-9]/g, '');
        if (digits.indexOf('08') === 0) {
            digits = '628' + digits.substring(2);
        } else if (digits.indexOf('8') === 0) {
            digits = '628' + digits.substring(1);
        } else if (digits.indexOf('0') === 0) {
            digits = '62' + digits.substring(1);
        }
        return digits;
    }

    // Helper to format full URL
    function formatSocialUrl(raw, platform) {
        if (!raw) return '';
        raw = raw.trim();
        if (/^https?:\/\//i.test(raw)) return raw;

        if (platform === 'instagram') {
            var user = raw.replace(/^(https?:\/\/)?(www\.)?instagram\.com\/?/i, '').replace(/^@+/, '');
            return 'https://www.instagram.com/' + user;
        } else if (platform === 'tiktok') {
            var user = raw.replace(/^(https?:\/\/)?(www\.)?tiktok\.com\/?/i, '').replace(/^@+/, '');
            return 'https://www.tiktok.com/@' + user;
        } else {
            return 'https://' + raw.replace(/^\/+/, '');
        }
    }

    // Extract embed source if user pasted iframe tag
    function extractEmbedSrc(raw) {
        if (!raw) return '';
        var match = raw.match(/src="([^"]+)"/i);
        if (match && match[1]) {
            return match[1];
        }
        return raw.trim();
    }

    // DOM Elements
    var cafeWaInput = document.getElementById('cafe_whatsapp');
    var zendoWaInput = document.getElementById('zendo_whatsapp');
    var grabfoodInput = document.getElementById('grabfood_url');
    var igInput = document.getElementById('cafe_instagram');
    var tiktokInput = document.getElementById('cafe_tiktok');
    var emailInput = document.getElementById('cafe_email');
    var mapsUrlInput = document.getElementById('cafe_maps_url');
    var mapsEmbedInput = document.getElementById('cafe_maps_embed');
    var addressInput = document.getElementById('cafe_address');

    // Action Test Buttons
    var btnTestCafeWa = document.getElementById('btnTestCafeWa');
    var btnTestZendoWa = document.getElementById('btnTestZendoWa');
    var btnTestGrabfood = document.getElementById('btnTestGrabfood');
    var btnTestInstagram = document.getElementById('btnTestInstagram');
    var btnTestTiktok = document.getElementById('btnTestTiktok');
    var btnTestEmail = document.getElementById('btnTestEmail');
    var btnTestMapsUrl = document.getElementById('btnTestMapsUrl');

    // Preview Elements
    var labelCafeWa = document.getElementById('labelCafeWa');
    var badgeCafeWa = document.getElementById('badgeCafeWa');
    var linkActionCafeWa = document.getElementById('linkActionCafeWa');

    var labelZendoWa = document.getElementById('labelZendoWa');
    var badgeZendoWa = document.getElementById('badgeZendoWa');
    var linkActionZendoWa = document.getElementById('linkActionZendoWa');

    var labelGrabfood = document.getElementById('labelGrabfood');
    var badgeGrabfood = document.getElementById('badgeGrabfood');
    var linkActionGrabfood = document.getElementById('linkActionGrabfood');

    var labelInstagram = document.getElementById('labelInstagram');
    var badgeInstagram = document.getElementById('badgeInstagram');
    var linkActionInstagram = document.getElementById('linkActionInstagram');

    var labelTiktok = document.getElementById('labelTiktok');
    var badgeTiktok = document.getElementById('badgeTiktok');
    var linkActionTiktok = document.getElementById('linkActionTiktok');

    var labelEmail = document.getElementById('labelEmail');
    var badgeEmail = document.getElementById('badgeEmail');
    var linkActionEmail = document.getElementById('linkActionEmail');

    var labelMapsUrl = document.getElementById('labelMapsUrl');
    var badgeMapsUrl = document.getElementById('badgeMapsUrl');
    var linkActionMapsUrl = document.getElementById('linkActionMapsUrl');

    var liveMapFrame = document.getElementById('liveMapFrame');

    // Mini footer chips
    var miniIconInstagram = document.getElementById('miniIconInstagram');
    var miniIconTiktok = document.getElementById('miniIconTiktok');
    var miniIconGrabfood = document.getElementById('miniIconGrabfood');
    var miniIconEmail = document.getElementById('miniIconEmail');
    var miniIconMaps = document.getElementById('miniIconMaps');
    var miniIconEmptyWarning = document.getElementById('miniIconEmptyWarning');

    function syncAll() {
        // 1. Cafe WA
        var rawCafeWa = cafeWaInput ? cafeWaInput.value.trim() : '';
        var cleanCafeWa = cleanPhoneTo62(rawCafeWa);
        var waUrl = cleanCafeWa ? ('https://wa.me/' + cleanCafeWa) : '#';
        if (btnTestCafeWa) btnTestCafeWa.href = waUrl;
        if (linkActionCafeWa) linkActionCafeWa.href = waUrl;
        if (labelCafeWa) labelCafeWa.textContent = cleanCafeWa || 'Wajib diisi';
        if (badgeCafeWa) {
            badgeCafeWa.textContent = cleanCafeWa ? 'Aktif' : 'Belum diisi';
            badgeCafeWa.className = cleanCafeWa ? 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800' : 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-100 text-rose-800';
        }

        // 2. Zendo WA
        var rawZendoWa = zendoWaInput ? zendoWaInput.value.trim() : '';
        var cleanZendoWa = cleanPhoneTo62(rawZendoWa);
        var zendoUrl = cleanZendoWa ? ('https://wa.me/' + cleanZendoWa) : '#';
        if (btnTestZendoWa) btnTestZendoWa.href = zendoUrl;
        if (linkActionZendoWa) linkActionZendoWa.href = zendoUrl;
        if (labelZendoWa) labelZendoWa.textContent = cleanZendoWa || 'Belum diisi';
        if (badgeZendoWa) {
            badgeZendoWa.textContent = cleanZendoWa ? 'Aktif' : 'Nonaktif';
            badgeZendoWa.className = cleanZendoWa ? 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800' : 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-warm-stone text-muted-text';
        }

        // 3. GrabFood
        var rawGrab = grabfoodInput ? grabfoodInput.value.trim() : '';
        var grabUrl = formatSocialUrl(rawGrab, 'grab');
        if (btnTestGrabfood) btnTestGrabfood.href = grabUrl || '#';
        if (linkActionGrabfood) linkActionGrabfood.href = grabUrl || '#';
        if (labelGrabfood) labelGrabfood.textContent = rawGrab || 'Belum diisi (tidak ada tombol mati)';
        if (badgeGrabfood) {
            badgeGrabfood.textContent = rawGrab ? 'Tampil di Web' : 'Disembunyikan';
            badgeGrabfood.className = rawGrab ? 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800' : 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-warm-stone text-muted-text';
        }
        if (miniIconGrabfood) {
            rawGrab ? miniIconGrabfood.classList.remove('hidden') : miniIconGrabfood.classList.add('hidden');
        }

        // 4. Instagram
        var rawIg = igInput ? igInput.value.trim() : '';
        var igUrl = formatSocialUrl(rawIg, 'instagram');
        if (btnTestInstagram) btnTestInstagram.href = igUrl || '#';
        if (linkActionInstagram) linkActionInstagram.href = igUrl || '#';
        if (labelInstagram) labelInstagram.textContent = rawIg || 'Belum diisi';
        if (badgeInstagram) {
            badgeInstagram.textContent = rawIg ? 'Tampil di Footer' : 'Disembunyikan';
            badgeInstagram.className = rawIg ? 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800' : 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-warm-stone text-muted-text';
        }
        if (miniIconInstagram) {
            rawIg ? miniIconInstagram.classList.remove('hidden') : miniIconInstagram.classList.add('hidden');
        }

        // 5. TikTok
        var rawTiktok = tiktokInput ? tiktokInput.value.trim() : '';
        var tiktokUrl = formatSocialUrl(rawTiktok, 'tiktok');
        if (btnTestTiktok) btnTestTiktok.href = tiktokUrl || '#';
        if (linkActionTiktok) linkActionTiktok.href = tiktokUrl || '#';
        if (labelTiktok) labelTiktok.textContent = rawTiktok || 'Belum diisi';
        if (badgeTiktok) {
            badgeTiktok.textContent = rawTiktok ? 'Tampil di Footer' : 'Disembunyikan';
            badgeTiktok.className = rawTiktok ? 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800' : 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-warm-stone text-muted-text';
        }
        if (miniIconTiktok) {
            rawTiktok ? miniIconTiktok.classList.remove('hidden') : miniIconTiktok.classList.add('hidden');
        }

        // 6. Email
        var rawEmail = emailInput ? emailInput.value.trim() : '';
        var mailtoUrl = rawEmail ? ('mailto:' + rawEmail) : '#';
        if (btnTestEmail) btnTestEmail.href = mailtoUrl;
        if (linkActionEmail) linkActionEmail.href = mailtoUrl;
        if (labelEmail) labelEmail.textContent = rawEmail || 'Belum diisi';
        if (badgeEmail) {
            badgeEmail.textContent = rawEmail ? 'Tampil di Footer' : 'Disembunyikan';
            badgeEmail.className = rawEmail ? 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800' : 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-warm-stone text-muted-text';
        }
        if (miniIconEmail) {
            rawEmail ? miniIconEmail.classList.remove('hidden') : miniIconEmail.classList.add('hidden');
        }

        // 7. Maps URL
        var rawMapsUrl = mapsUrlInput ? mapsUrlInput.value.trim() : '';
        var formattedMapsUrl = formatSocialUrl(rawMapsUrl, 'maps');
        if (btnTestMapsUrl) btnTestMapsUrl.href = formattedMapsUrl || '#';
        if (linkActionMapsUrl) linkActionMapsUrl.href = formattedMapsUrl || '#';
        if (labelMapsUrl) labelMapsUrl.textContent = rawMapsUrl || 'Belum diisi';
        if (badgeMapsUrl) {
            badgeMapsUrl.textContent = rawMapsUrl ? 'Rute Aktif' : 'Belum diisi';
            badgeMapsUrl.className = rawMapsUrl ? 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800' : 'px-2 py-0.5 rounded-full text-[9px] font-bold bg-warm-stone text-muted-text';
        }
        if (miniIconMaps) {
            rawMapsUrl ? miniIconMaps.classList.remove('hidden') : miniIconMaps.classList.add('hidden');
        }

        // Footer empty check
        var anyActive = !!(rawIg || rawTiktok || rawGrab || rawEmail || rawMapsUrl);
        if (miniIconEmptyWarning) {
            anyActive ? miniIconEmptyWarning.classList.add('hidden') : miniIconEmptyWarning.classList.remove('hidden');
        }
    }

    window.refreshMapPreview = function() {
        if (!liveMapFrame) return;
        var rawEmbed = mapsEmbedInput ? mapsEmbedInput.value.trim() : '';
        var embedSrc = extractEmbedSrc(rawEmbed);
        if (embedSrc && /^https?:\/\//i.test(embedSrc)) {
            liveMapFrame.src = embedSrc;
        } else {
            var addr = addressInput ? addressInput.value.trim() : '';
            var query = encodeURIComponent(addr || 'Nucomu Cafe Tulungagung');
            liveMapFrame.src = 'https://maps.google.com/maps?q=' + query + '&t=&z=16&ie=UTF8&iwloc=&output=embed';
        }
    };

    // Attach listeners
    [cafeWaInput, zendoWaInput, grabfoodInput, igInput, tiktokInput, emailInput, mapsUrlInput].forEach(function(inp) {
        if (inp) {
            inp.addEventListener('input', syncAll);
            inp.addEventListener('change', syncAll);
        }
    });

    if (mapsEmbedInput) {
        mapsEmbedInput.addEventListener('change', window.refreshMapPreview);
    }
    if (addressInput) {
        addressInput.addEventListener('change', function() {
            if (!mapsEmbedInput || !mapsEmbedInput.value.trim()) {
                window.refreshMapPreview();
            }
        });
    }

    // Initial run
    syncAll();
})();
</script>
@endpush
@endsection
