@extends('layouts.admin')
@section('title', 'Kelola Testimoni — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Kelola Testimoni</h1>
            <p class="text-xs text-zinc-400 mt-1">Ulasan pelanggan Nucomu Cafe yang ditampilkan di beranda publik.</p>
        </div>

        <form action="{{ route('admin.testimonials.deleteSample') }}" method="POST" onsubmit="return confirm('Hapus semua testimoni data contoh?')">
            @csrf
            <button type="submit" class="bg-red-950 hover:bg-red-900 text-red-300 border border-red-800 font-bold text-xs px-4 py-2.5 rounded-xl transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Hapus Testimoni Contoh</span>
            </button>
        </form>
    </div>

    {{-- Form Tambah Testimoni Baru --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4 border-b border-zinc-800 pb-3">
            Tambah Testimoni Baru
        </h2>

        <form action="{{ route('admin.testimonials.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @csrf

            <div class="sm:col-span-4">
                <label for="name" class="block text-xs font-bold text-zinc-400 mb-1">Nama Pelanggan *</label>
                <input type="text" name="name" id="name" required placeholder="Contoh: Rian Tulungagung"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-white">
            </div>

            <div class="sm:col-span-2">
                <label for="rating" class="block text-xs font-bold text-zinc-400 mb-1">Rating (1-5)</label>
                <select name="rating" id="rating" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-white">
                    <option value="5" selected>★★★★★ (5)</option>
                    <option value="4">★★★★☆ (4)</option>
                    <option value="3">★★★☆☆ (3)</option>
                </select>
            </div>

            <div class="sm:col-span-4">
                <label for="content" class="block text-xs font-bold text-zinc-400 mb-1">Isi Ulasan *</label>
                <input type="text" name="content" id="content" required placeholder="Contoh: Banoffee-nya terbaik di Tulungagung!"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-white">
            </div>

            <div class="sm:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase py-3 rounded-xl transition-all shadow-md">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Testimonial Table --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-mono tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Isi Ulasan</th>
                        <th class="px-6 py-4">Tipe Data</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    @forelse($testimonials as $testi)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-white text-sm">
                                {{ $testi->name }}
                            </td>

                            <td class="px-6 py-4 font-mono text-amber-400">
                                {{ str_repeat('★', $testi->rating) }}
                            </td>

                            <td class="px-6 py-4 text-zinc-300 italic">
                                "{{ $testi->content }}"
                            </td>

                            <td class="px-6 py-4">
                                @if($testi->is_sample)
                                    <span class="bg-zinc-800 text-zinc-400 border border-zinc-700 text-[10px] font-mono px-2 py-0.5 rounded">Sample</span>
                                @else
                                    <span class="bg-emerald-950 text-emerald-400 border border-emerald-800 text-[10px] font-mono px-2 py-0.5 rounded">Resmi</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.testimonials.destroy', $testi->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-950 hover:bg-red-900 text-red-400 text-[11px] font-bold px-3 py-1.5 rounded-lg border border-red-800 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500 text-xs">
                                Belum ada testimoni.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
