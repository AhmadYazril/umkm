@extends('layouts.admin')
@section('title', 'Kelola Event — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Kelola Event & Promo</h1>
            <p class="text-xs text-zinc-400 mt-1">Agenda kegiatan, live music, promo, dan gathering komunitas.</p>
        </div>
    </div>

    {{-- Form Tambah Event Baru --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4 border-b border-zinc-800 pb-3">
            Tambah Agenda Event Baru
        </h2>

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @csrf

            <div class="sm:col-span-4">
                <label for="title" class="block text-xs font-bold text-zinc-400 mb-1">Judul Event *</label>
                <input type="text" name="title" id="title" required placeholder="Contoh: Acoustic Night Weekend"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-white">
            </div>

            <div class="sm:col-span-3">
                <label for="date" class="block text-xs font-bold text-zinc-400 mb-1">Tanggal Event *</label>
                <input type="date" name="date" id="date" required
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-white">
            </div>

            <div class="sm:col-span-5">
                <label for="image" class="block text-xs font-bold text-zinc-400 mb-1">Poster / Banner Foto</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-400 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-zinc-800 file:text-white">
            </div>

            <div class="sm:col-span-10">
                <label for="description" class="block text-xs font-bold text-zinc-400 mb-1">Deskripsi Event *</label>
                <input type="text" name="description" id="description" required placeholder="Penjelasan mengenai acara, jam pelaksanaan, dan pengisi acara..."
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-white">
            </div>

            <div class="sm:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase py-3 rounded-xl transition-all shadow-md">
                    Simpan Event
                </button>
            </div>
        </form>
    </div>

    {{-- Events List Table --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-mono tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-6 py-4">Judul & Tanggal</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    @forelse($events as $event)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-white text-sm block">{{ $event->title }}</span>
                                <span class="text-[10px] font-mono text-emerald-400 font-semibold block">{{ date('d M Y', strtotime($event->date)) }}</span>
                            </td>

                            <td class="px-6 py-4 text-zinc-400">
                                {{ $event->description }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus agenda event ini?')">
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
                            <td colspan="3" class="px-6 py-12 text-center text-zinc-500 text-xs">
                                Belum ada agenda event dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
