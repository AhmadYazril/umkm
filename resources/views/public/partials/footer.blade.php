<footer class="bg-nc-black text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

            {{-- Brand --}}
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white flex items-center justify-center">
                        <span class="text-nc-black font-serif font-bold text-xl tracking-tighter">NU</span>
                    </div>
                    <div>
                        <div class="font-black text-white text-lg tracking-widest2">NUCOMU</div>
                        <div class="text-nc-gray-mid text-xs tracking-widest">COFFEE & DESSERT</div>
                    </div>
                </div>
                <p class="text-nc-gray-mid text-sm leading-relaxed max-w-xs italic">
                    "New, Unforgettable, Comfy, Musings"
                </p>
                <p class="text-nc-gray-light text-sm leading-relaxed mt-3 max-w-sm">
                    Tempat nyaman dengan konsep monokrom elegan untuk menikmati dessert otentik, kopi, dan makanan di Tulungagung.
                </p>
                {{-- Sosial Media --}}
                <div class="flex gap-4 mt-6">
                    @php $ig = \App\Models\Setting::get('cafe_instagram'); $tt = \App\Models\Setting::get('cafe_tiktok'); @endphp
                    @if($ig && !str_contains($ig, '[ISI'))
                    <a href="{{ $ig }}" target="_blank" class="text-nc-gray-mid hover:text-white transition-colors text-sm">Instagram</a>
                    @endif
                    @if($tt && !str_contains($tt, '[ISI'))
                    <a href="{{ $tt }}" target="_blank" class="text-nc-gray-mid hover:text-white transition-colors text-sm">TikTok</a>
                    @endif
                    @php $wa = \App\Models\Setting::get('cafe_whatsapp'); @endphp
                    @if($wa && !str_contains($wa, '[ISI'))
                    <a href="https://wa.me/{{ $wa }}" target="_blank" class="text-nc-gray-mid hover:text-white transition-colors text-sm">WhatsApp</a>
                    @endif
                </div>
            </div>

            {{-- Jam Operasional --}}
            <div>
                <h3 class="font-semibold text-white text-sm tracking-widest mb-4 uppercase">Jam Buka</h3>
                @php
                    $hours = \App\Models\OperatingHour::orderBy('day_of_week')->get();
                @endphp
                <ul class="space-y-1">
                    @foreach($hours as $h)
                    <li class="flex justify-between text-sm {{ $h->is_closed ? 'text-nc-gray-mid' : 'text-nc-gray-light' }}">
                        <span>{{ $h->day_name }}</span>
                        <span>{{ $h->schedule_text }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Navigasi --}}
            <div>
                <h3 class="font-semibold text-white text-sm tracking-widest mb-4 uppercase">Navigasi</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Beranda</a></li>
                    <li><a href="{{ route('menu') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Menu</a></li>
                    <li><a href="{{ route('about') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Cerita Kami</a></li>
                    <li><a href="{{ route('facilities') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Fasilitas</a></li>
                    <li><a href="{{ route('gallery') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Galeri</a></li>
                    <li><a href="{{ route('reservation') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Reservasi</a></li>
                    <li><a href="{{ route('contact') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Kontak</a></li>
                    <li><a href="{{ route('order.track') }}" class="text-nc-gray-mid hover:text-white text-sm transition-colors">Lacak Pesanan</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-nc-gray-dark mt-12 pt-6 flex flex-col md:flex-row items-center justify-between gap-2">
            <p class="text-nc-gray-mid text-xs">© {{ date('Y') }} Nucomu Cafe. All rights reserved.</p>
            <p class="text-nc-gray-mid text-xs">Tulungagung, Jawa Timur</p>
        </div>
    </div>
</footer>
