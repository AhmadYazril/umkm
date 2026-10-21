@extends('layouts.admin')
@section('title', 'Kelola Menu & Varian — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    {{-- Header & Bulk Actions --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Kelola Menu & Varian</h1>
            <p class="text-xs text-gray-400 mt-1">Daftar menu kopi, minuman non-kopi, dessert artisanal, dan camilan Nucomu Cafe.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Hapus Semua Data Contoh Button --}}
            <form action="{{ route('admin.menus.deleteSample') }}" method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN? Semua menu berlabel (Data Contoh) akan dihapus secara permanen.')">
                @csrf
                <button type="submit" class="bg-rose-950/70 hover:bg-rose-900 text-rose-300 border border-rose-800/80 font-bold text-xs px-4 py-2.5 rounded-xl transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Hapus Menu Contoh</span>
                </button>
            </form>

            <a href="{{ route('admin.menus.create') }}" class="bg-goldAccent hover:bg-amber-400 text-black font-extrabold text-xs uppercase px-4 py-2.5 rounded-xl transition-all flex items-center space-x-2 shadow-lg shadow-goldAccent/20">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Menu Baru</span>
            </a>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.menus.index') }}" class="px-3.5 py-1.5 rounded-lg border font-bold transition-all {{ !request('category') ? 'bg-goldAccent text-black border-goldAccent' : 'bg-black/40 text-gray-400 border-white/10 hover:text-white' }}">
                Semua
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('admin.menus.index', ['category' => $cat->id]) }}" class="px-3.5 py-1.5 rounded-lg border font-bold transition-all {{ request('category') == $cat->id ? 'bg-goldAccent text-black border-goldAccent' : 'bg-black/40 text-gray-400 border-white/10 hover:text-white' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.menus.index') }}" method="GET" class="w-full sm:w-64">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama menu..."
                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 pl-9 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-goldAccent">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500 text-xs"></i>
            </div>
        </form>
    </div>

    {{-- Table Menu --}}
    <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-black/40 text-gray-400 uppercase text-[10px] font-mono tracking-widest border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4">Menu</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Status & Badge</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-xl bg-black/40 border border-white/10 overflow-hidden shrink-0">
                                        @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                                        @elseif($menu->image && (str_starts_with($menu->image, 'http://') || str_starts_with($menu->image, 'https://')))
                                            <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-600">
                                                <i class="fa-solid fa-mug-hot"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white text-sm">{{ $menu->name }}</h3>
                                        <p class="text-gray-400 text-[11px] line-clamp-1 max-w-xs">{{ $menu->description }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="bg-white/5 text-gray-300 border border-white/10 px-2.5 py-1 rounded-full text-[11px] font-medium">
                                    {{ $menu->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 font-mono font-bold text-white text-sm">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5 items-center">
                                    {{-- Toggle Available --}}
                                    <form action="{{ route('admin.menus.toggleAvailable', $menu->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border transition-all {{ $menu->is_available ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                        </button>
                                    </form>

                                    @if($menu->is_featured)
                                        <span class="bg-goldAccent/10 text-goldAccent border border-goldAccent/20 px-2 py-0.5 rounded-full text-[10px] font-bold">
                                            Unggulan
                                        </span>
                                    @endif

                                    @if($menu->is_sample)
                                        <span class="bg-black/60 text-gray-400 border border-white/10 px-2 py-0.5 rounded text-[10px] font-mono">
                                            Contoh
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="p-2 text-gray-400 hover:text-goldAccent bg-white/5 hover:bg-white/10 rounded-lg border border-white/10 transition-colors" title="Edit Menu">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-lg border border-rose-500/20 transition-colors" title="Hapus Menu">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500 text-xs">
                                <i class="fa-solid fa-mug-hot text-3xl opacity-20 block mb-2"></i>
                                Belum ada menu yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($menus->hasPages())
            <div class="p-4 border-t border-white/10">
                {{ $menus->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
