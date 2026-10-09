@extends('layouts.admin')
@section('title', 'Kelola Galeri — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Kelola Galeri Foto</h1>
            <p class="text-xs text-zinc-400 mt-1">Upload dan atur foto interior cafe, suasana, dan sajian produk.</p>
        </div>
    </div>

    {{-- Form Upload Foto Baru --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8">
        <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4 border-b border-zinc-800 pb-3">
            Tambah Foto Galeri Baru
        </h2>

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @csrf

            <div class="sm:col-span-4">
                <label for="image" class="block text-xs font-bold text-zinc-400 mb-1">File Foto *</label>
                <input type="file" name="image" id="image" required accept="image/*"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-400 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-zinc-800 file:text-white">
            </div>

            <div class="sm:col-span-4">
                <label for="caption" class="block text-xs font-bold text-zinc-400 mb-1">Judul / Caption</label>
                <input type="text" name="caption" id="caption" placeholder="Contoh: Suasana Malam Outdoor"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-white">
            </div>

            <div class="sm:col-span-3">
                <label for="category" class="block text-xs font-bold text-zinc-400 mb-1">Kategori</label>
                <input type="text" name="category" id="category" placeholder="Interior / Coffee / Dessert"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-white placeholder-zinc-600 focus:outline-none focus:border-white">
            </div>

            <div class="sm:col-span-1 flex items-end">
                <button type="submit" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase py-3 rounded-xl transition-all shadow-md">
                    Upload
                </button>
            </div>
        </form>
    </div>

    {{-- Grid Foto Galeri --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($gallery as $item)
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden group flex flex-col justify-between">
                <div class="relative aspect-square bg-zinc-950">
                    @if($item->image && file_exists(public_path('storage/' . $item->image)))
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->caption }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-zinc-600 text-xs font-mono">NO IMAGE</div>
                    @endif
                </div>

                <div class="p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono text-zinc-500 uppercase block">{{ $item->category ?? 'Galeri' }}</span>
                        <h3 class="font-bold text-xs text-white line-clamp-1">{{ $item->caption ?? 'Tanpa Judul' }}</h3>
                    </div>

                    <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus foto galeri ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-950 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-zinc-900/40 border border-zinc-800 rounded-3xl p-8 text-zinc-500 text-xs">
                Belum ada foto galeri diunggah.
            </div>
        @endforelse
    </div>

</div>
@endsection
