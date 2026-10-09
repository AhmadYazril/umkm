@extends('layouts.app')
@section('title', 'Reservasi Meja — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight uppercase mb-2">
                Reservasi <span class="text-zinc-500">Meja & Event</span>
            </h1>
            <p class="text-zinc-400 text-sm">
                Rencanakan pertemuan bisnis, gathering komunitas, atau momen berkumpul keluarga di Nucomu Cafe Tulungagung.
            </p>
        </div>

        {{-- Success / Error Flash Alert --}}
        @if(session('success'))
            <div class="bg-emerald-950/80 border border-emerald-800 text-emerald-300 p-4 rounded-2xl mb-8 text-center text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-zinc-900/80 border border-zinc-800 rounded-3xl p-6 sm:p-10 shadow-2xl">
            <form action="{{ route('reservation.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                            Nama Lengkap <span class="text-white">*</span>
                        </label>
                        <input type="text" name="name" id="name" required placeholder="Masukkan nama Anda"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                            Nomor WhatsApp <span class="text-white">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" required placeholder="08xxxxxxxxxx"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="date" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                            Tanggal Reservasi <span class="text-white">*</span>
                        </label>
                        <input type="date" name="date" id="date" required min="{{ date('Y-m-d') }}"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition-colors">
                    </div>

                    <div>
                        <label for="time" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                            Jam Kedatangan <span class="text-white">*</span>
                        </label>
                        <input type="time" name="time" id="time" required
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition-colors">
                    </div>

                    <div>
                        <label for="guests" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                            Jumlah Tamu (Orang) <span class="text-white">*</span>
                        </label>
                        <input type="number" name="guests" id="guests" min="1" max="50" value="2" required
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition-colors">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                        Catatan Khusus / Keperluan Event
                    </label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Contoh: Reservasi ulang tahun, minta meja dekat colokan, gathering 10 orang..."
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-white transition-colors"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-black text-sm uppercase tracking-wider py-4 rounded-2xl transition-all shadow-xl hover:shadow-white/10 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Kirim Permintaan Reservasi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection
