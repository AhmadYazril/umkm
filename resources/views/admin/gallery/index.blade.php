@extends('layouts.admin')
@section('title', 'Kelola Galeri — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Kelola Galeri Story</h1>
            <p class="text-xs text-zinc-400 mt-1">Upload dan atur konten live story feed Nucomu Cafe.</p>
        </div>
        <a href="{{ route('nucomu.index') }}#gallery" target="_blank" class="px-4 py-2 bg-amber-500/10 border border-amber-500/20 text-amber-400 hover:bg-amber-500/20 rounded-xl text-xs font-bold transition-all">
            Lihat di Web Publik ↗
        </a>
    </div>

    {{-- Form Upload Foto & Story Baru --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4 border-b border-zinc-800 pb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Tambah Story Galeri Baru
        </h2>

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @csrf

            <div class="sm:col-span-4">
                <label for="image" class="block text-xs font-bold text-zinc-400 mb-1">File Foto Story *</label>
                <input type="file" name="image" id="image" required accept="image/*"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-400 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-zinc-800 file:text-white">
            </div>

            <div class="sm:col-span-4">
                <label for="caption" class="block text-xs font-bold text-zinc-400 mb-1">Judul Story / Caption *</label>
                <input type="text" name="caption" id="caption" required placeholder="Misal: Manual Pour-over Beans House Blend"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-4">
                <label for="category_badge" class="block text-xs font-bold text-zinc-400 mb-1">Badge Tag Story</label>
                <input type="text" name="category_badge" id="category_badge" placeholder="#CraftCoffee atau #CozyVibes"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-3">
                <label for="author" class="block text-xs font-bold text-zinc-400 mb-1">Author / Akun Pengunggah</label>
                <input type="text" name="author" id="author" placeholder="Contoh: Barista Corner"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-3">
                <label for="time_label" class="block text-xs font-bold text-zinc-400 mb-1">Label Waktu</label>
                <input type="text" name="time_label" id="time_label" placeholder="Contoh: 2 Jam Lalu / Baru saja"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-3">
                <label for="category" class="block text-xs font-bold text-zinc-400 mb-1">Kategori Internal</label>
                <input type="text" name="category" id="category" placeholder="Coffee / Interior / Bakery"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-3">
                <label for="avatar_url" class="block text-xs font-bold text-zinc-400 mb-1">URL Avatar (Opsional)</label>
                <input type="url" name="avatar_url" id="avatar_url" placeholder="https://..."
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500">
            </div>

            <div class="sm:col-span-10">
                <label for="description" class="block text-xs font-bold text-zinc-400 mb-1">Deskripsi Singkat Cerita</label>
                <textarea name="description" id="description" rows="2" placeholder="Proses seduh perlahan untuk mengekstrak aroma floral..."
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-amber-500 resize-none"></textarea>
            </div>

            <div class="sm:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-black font-black text-xs uppercase py-3 rounded-xl transition-all shadow-md">
                    Simpan Story
                </button>
            </div>
        </form>
    </div>

    {{-- Grid Foto Galeri --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($items as $item)
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden group flex flex-col justify-between">
                <div class="relative aspect-video bg-zinc-950 overflow-hidden">
                    @if($item->image && file_exists(public_path('storage/' . $item->image)))
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->caption }}" class="w-full h-full object-cover">
                    @elseif($item->image && (str_starts_with($item->image, 'http://') || str_starts_with($item->image, 'https://')))
                        <img src="{{ $item->image }}" alt="{{ $item->caption }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-zinc-600 text-xs font-mono">NO IMAGE</div>
                    @endif

                    @if($item->category_badge)
                        <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full bg-black/70 backdrop-blur text-amber-400 font-bold text-[9px]">
                            {{ $item->category_badge }}
                        </span>
                    @endif
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-center justify-between text-[10px] text-zinc-500 mb-1">
                            <span class="font-bold text-zinc-400">{{ $item->author ?? 'Barista Corner' }}</span>
                            <span>{{ $item->time_label ?? 'Baru saja' }}</span>
                        </div>
                        <h3 class="font-bold text-xs text-white line-clamp-1">{{ $item->caption ?? 'Tanpa Judul' }}</h3>
                        @if($item->description)
                            <p class="text-[11px] text-zinc-400 mt-1 line-clamp-2 leading-relaxed">{{ $item->description }}</p>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-zinc-800 flex items-center justify-between">
                        <span class="text-[10px] font-mono text-zinc-500 uppercase">{{ $item->category ?? 'Story' }}</span>

                        <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus story galeri ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-400 hover:text-red-300 hover:bg-red-950/60 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-zinc-900/40 border border-zinc-800 rounded-3xl p-8 text-zinc-500 text-xs">
                Belum ada foto story diunggah. Story default bawaan akan ditampilkan di halaman publik.
            </div>
        @endforelse
    </div>

</div>
@endsection
