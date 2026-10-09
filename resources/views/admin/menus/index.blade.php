@extends('layouts.admin')
@section('title', 'Kelola Menu — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    {{-- Header & Bulk Actions --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Kelola Menu & Varian</h1>
            <p class="text-xs text-zinc-400 mt-1">Daftar menu makanan, minuman kopi, dan dessert Nucomu Cafe.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Hapus Semua Data Contoh Button --}}
            <form action="{{ route('admin.menus.deleteSample') }}" method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN? Semua menu berlabel (Data Contoh) akan dihapus secara permanen.')">
                @csrf
                <button type="submit" class="bg-red-950 hover:bg-red-900 text-red-300 border border-red-800 font-bold text-xs px-4 py-2.5 rounded-xl transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus Semua Data Contoh</span>
                </button>
            </form>

            <a href="{{ route('admin.menus.create') }}" class="bg-white hover:bg-zinc-200 text-zinc-950 font-bold text-xs px-4 py-2.5 rounded-xl transition-all flex items-center space-x-2 shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Menu Baru</span>
            </a>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.menus.index') }}" class="px-3 py-1.5 rounded-lg border font-semibold {{ !request('category') ? 'bg-white text-zinc-950 border-white' : 'bg-zinc-950 text-zinc-400 border-zinc-800 hover:text-white' }}">
                Semua
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('admin.menus.index', ['category' => $cat->id]) }}" class="px-3 py-1.5 rounded-lg border font-semibold {{ request('category') == $cat->id ? 'bg-white text-zinc-950 border-white' : 'bg-zinc-950 text-zinc-400 border-zinc-800 hover:text-white' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.menus.index') }}" method="GET" class="w-full sm:w-64">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama menu..."
                class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2 text-xs text-white placeholder-zinc-500 focus:outline-none focus:border-white">
        </form>
    </div>

    {{-- Table Menu --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-mono tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-6 py-4">Menu & Kategori</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Status & Unggulan</th>
                        <th class="px-6 py-4">Tipe Data</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-zinc-950 rounded-xl overflow-hidden border border-zinc-800 shrink-0 flex items-center justify-center">
                                        @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] font-mono text-zinc-600">NO IMG</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-bold text-white text-sm block">{{ $menu->name }}</span>
                                        <span class="text-[11px] text-zinc-400 block">{{ $menu->category->name ?? 'Tanpa Kategori' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 font-mono font-bold text-white text-sm">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    {{-- Toggle Available --}}
                                    <form action="{{ route('admin.menus.toggleAvailable', $menu->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition-colors {{ $menu->is_available ? 'bg-emerald-950 text-emerald-400 border-emerald-800' : 'bg-zinc-800 text-zinc-500 border-zinc-700' }}">
                                            {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                        </button>
                                    </form>

                                    {{-- Toggle Featured --}}
                                    <form action="{{ route('admin.menus.toggleFeatured', $menu->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition-colors {{ $menu->is_featured ? 'bg-white text-zinc-950 border-white font-black' : 'bg-zinc-950 text-zinc-400 border-zinc-800' }}">
                                            ★ {{ $menu->is_featured ? 'Unggulan' : 'Biasa' }}
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($menu->is_sample)
                                    <span class="bg-zinc-800 text-zinc-400 border border-zinc-700 text-[10px] font-mono px-2 py-0.5 rounded">
                                        Sample Data
                                    </span>
                                @else
                                    <span class="bg-emerald-950 text-emerald-400 border border-emerald-800 text-[10px] font-mono px-2 py-0.5 rounded">
                                        Data Resmi
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="bg-zinc-800 hover:bg-zinc-700 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg border border-zinc-700 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-950 hover:bg-red-900 text-red-400 text-[11px] font-bold px-3 py-1.5 rounded-lg border border-red-800 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500 text-xs">
                                Tidak ada data menu yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-zinc-800">
            {{ $menus->links() }}
        </div>
    </div>

</div>
@endsection
