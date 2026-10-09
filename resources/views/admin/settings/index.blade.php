@extends('layouts.admin')
@section('title', 'Pengaturan Cafe — Admin Nucomu Cafe')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    
    <div>
        <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Pengaturan Cafe & Jam Operasional</h1>
        <p class="text-xs text-zinc-400 mt-1">Ubah profil bisnis, alamat di Tulungagung, kontak WhatsApp, dan jam operasional cafe.</p>
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
                    <input type="text" name="store_name" id="store_name" value="{{ $settings['store_name'] ?? 'Nucomu Cafe' }}" required
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Nomor WhatsApp CS / Kasir</label>
                    <input type="text" name="phone" id="phone" value="{{ $settings['phone'] ?? '+62 812-3456-7890' }}" required
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-white">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Alamat Lengkap Cafe</label>
                <textarea name="address" id="address" rows="2" required
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white">{{ $settings['address'] ?? 'Jl. Panglima Sudirman No. 45, Kebonsari, Kec. Tulungagung, Kabupaten Tulungagung, Jawa Timur 66212' }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="instagram" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Username Instagram</label>
                    <input type="text" name="instagram" id="instagram" value="{{ $settings['instagram'] ?? '@nucomu.cafe' }}"
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white font-mono focus:outline-none focus:border-white">
                </div>

                <div>
                    <label for="tagline" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Tagline Cafe</label>
                    <input type="text" name="tagline" id="tagline" value="{{ $settings['tagline'] ?? 'Coffee & Dessert — New, Unforgettable, Comfy, Musings' }}"
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
