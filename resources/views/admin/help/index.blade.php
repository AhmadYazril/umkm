@extends('layouts.admin')
@section('title', 'Bantuan & Panduan Pemakaian — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Top Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span>Modul 10 &bull; Pusat Bantuan &amp; Panduan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Panduan Pemakaian Admin
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Langkah singkat pengelolaan setiap menu kafe, standar ukuran foto yang disarankan, dan tanya jawab umum (FAQ).
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="#tabel-foto" class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-image text-xs text-muted-text"></i>
                <span>Standar Ukuran Foto</span>
            </a>
            <a href="#faq" class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-regular fa-circle-question text-xs text-muted-text"></i>
                <span>Tanya Jawab (FAQ)</span>
            </a>
        </div>
    </div>

    {{-- SECTION 1: STANDAR UKURAN FOTO & MEDIA CHEAT SHEET --}}
    <div id="tabel-foto" class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-ruler-combined"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-ink-black">Standar Rekomendasi Ukuran Foto &amp; Media</h2>
                <p class="text-[11px] text-muted-text">Pedoman ukuran agar tampilan website tetap tajam, proporsional, dan ringan dimuat di HP</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-hairline bg-warm-canvas/60 text-charcoal font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Menu / Bagian</th>
                        <th class="py-3 px-4">Rasio Aspek</th>
                        <th class="py-3 px-4">Resolusi Disarankan</th>
                        <th class="py-3 px-4">Format File</th>
                        <th class="py-3 px-4">Batas Maksimal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline font-medium text-ink-black">
                    <tr class="hover:bg-warm-stone/20 transition">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>1. Hero / Banner Promo</span>
                        </td>
                        <td class="py-3 px-4 font-mono">16:9 atau 21:9</td>
                        <td class="py-3 px-4 font-mono">1920 &times; 1080 px / 1200 &times; 675 px</td>
                        <td class="py-3 px-4">JPG, PNG, WebP</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">2 MB</td>
                    </tr>
                    <tr class="hover:bg-warm-stone/20 transition">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            <span>2. About / Kisah Kami</span>
                        </td>
                        <td class="py-3 px-4 font-mono">16:9 (Foto/Video)</td>
                        <td class="py-3 px-4 font-mono">1200 &times; 800 px (Video: 1080p)</td>
                        <td class="py-3 px-4">Foto: JPG/WebP &bull; Video: MP4</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">Foto 5 MB / Video 50 MB</td>
                    </tr>
                    <tr class="hover:bg-warm-stone/20 transition">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>3. Fasilitas &amp; Layanan</span>
                        </td>
                        <td class="py-3 px-4 font-mono">16:9 atau 4:3</td>
                        <td class="py-3 px-4 font-mono">1200 &times; 800 px</td>
                        <td class="py-3 px-4">JPG, PNG, WebP</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">2 MB</td>
                    </tr>
                    <tr class="hover:bg-warm-stone/20 transition bg-amber-50/30">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                            <span>4. Menu Makanan &amp; Minuman</span>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-amber-900">Wajib 1:1 (Persegi)</td>
                        <td class="py-3 px-4 font-mono">800 &times; 800 px</td>
                        <td class="py-3 px-4">JPG, PNG, WebP</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">1 MB</td>
                    </tr>
                    <tr class="hover:bg-warm-stone/20 transition">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            <span>5. Gallery &amp; Feed Foto</span>
                        </td>
                        <td class="py-3 px-4 font-mono">1:1 atau 4:3</td>
                        <td class="py-3 px-4 font-mono">1080 &times; 1080 px / 1200 &times; 900 px</td>
                        <td class="py-3 px-4">JPG, PNG, WebP</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">2 MB</td>
                    </tr>
                    <tr class="hover:bg-warm-stone/20 transition">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            <span>7. Lampiran Reservasi Tamu</span>
                        </td>
                        <td class="py-3 px-4 font-mono">Bebas</td>
                        <td class="py-3 px-4 font-mono">Menyesuaikan foto HP tamu</td>
                        <td class="py-3 px-4">JPG, PNG, WebP</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">2 MB</td>
                    </tr>
                    <tr class="hover:bg-warm-stone/20 transition">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-charcoal"></span>
                            <span>10. Profil Admin (Avatar)</span>
                        </td>
                        <td class="py-3 px-4 font-mono">1:1 (Persegi)</td>
                        <td class="py-3 px-4 font-mono">500 &times; 500 px</td>
                        <td class="py-3 px-4">JPG, PNG, WebP</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">2 MB</td>
                    </tr>
                    <tr class="hover:bg-warm-stone/20 transition">
                        <td class="py-3 px-4 font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <span>10. Logo &amp; Favicon Kafe</span>
                        </td>
                        <td class="py-3 px-4 font-mono">Bebas / 1:1</td>
                        <td class="py-3 px-4 font-mono">Logo bebas &bull; Favicon: 32 &times; 32 px</td>
                        <td class="py-3 px-4">PNG transparan / ICO</td>
                        <td class="py-3 px-4 font-semibold text-rose-600">Logo 2 MB / Favicon 1 MB</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION 2: PANDUAN RINGKAS PER MENU (MODUL 1 SAMPAI 9) --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-hairline">
            <h2 class="text-sm font-bold text-ink-black flex items-center gap-2">
                <i class="fa-solid fa-list-check text-charcoal"></i>
                <span>Panduan Langkah Singkat Pengelolaan per Menu</span>
            </h2>
            <span class="text-[11px] text-muted-text">9 Modul Operasional Kafe</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($modules as $mod)
                <div class="bg-pure-white rounded-2xl border border-hairline p-5 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-warm-stone/60 text-ink-black flex items-center justify-center font-bold text-sm shrink-0">
                                <i class="{{ $mod['icon'] }}"></i>
                            </div>
                            <h3 class="text-xs font-bold text-ink-black leading-snug">
                                {{ $mod['name'] }}
                            </h3>
                        </div>

                        <p class="text-[11px] text-muted-text leading-relaxed">
                            {{ $mod['summary'] }}
                        </p>

                        <div class="space-y-1.5 pt-2 border-t border-hairline/60">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-charcoal block">Langkah Penggunaan:</span>
                            <ul class="space-y-1 text-[11px] text-charcoal">
                                @foreach($mod['steps'] as $idx => $step)
                                    <li class="flex items-start gap-1.5">
                                        <span class="text-muted-text font-mono text-[10px] shrink-0">{{ $idx + 1 }}.</span>
                                        <span class="leading-relaxed">{{ $step }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="p-2.5 rounded-xl bg-warm-canvas/60 border border-hairline text-[10.5px] text-muted-text space-y-1">
                        <span class="font-bold text-ink-black block">Panduan Foto:</span>
                        <p class="leading-relaxed">{{ $mod['photo_guide'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- SECTION 3: TANYA JAWAB UMUM (FAQ) --}}
    <div id="faq" class="bg-pure-white rounded-2xl border border-hairline p-5 sm:p-6 shadow-xs space-y-5">
        <div class="flex items-center gap-2.5 pb-3 border-b border-hairline">
            <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                <i class="fa-regular fa-comments"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-ink-black">Tanya Jawab Umum (FAQ)</h2>
                <p class="text-[11px] text-muted-text">Pertanyaan yang sering ditanyakan seputar pengelolaan website Nucomu</p>
            </div>
        </div>

        <div class="space-y-3">
            @foreach($faqs as $faq)
                <details class="group rounded-xl border border-hairline bg-warm-canvas/30 p-4 transition-all">
                    <summary class="flex items-center justify-between text-xs font-bold text-ink-black cursor-pointer list-none gap-3">
                        <span class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-question text-amber-600 mt-0.5 shrink-0"></i>
                            <span class="leading-snug">{{ $faq['q'] }}</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-muted-text group-open:rotate-180 transition-transform shrink-0"></i>
                    </summary>
                    <div class="mt-3 pt-3 border-t border-hairline/60 text-xs text-charcoal leading-relaxed pl-6">
                        {{ $faq['a'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>

</div>
@endsection
