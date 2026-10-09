@extends('layouts.admin')
@section('title', 'Edit Menu ' . $menu->name . ' — Admin Nucomu Cafe')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black uppercase text-white tracking-tight">Edit Menu</h1>
            <p class="text-xs text-zinc-400 mt-1">Ubah informasi, harga, dan opsi varian menu {{ $menu->name }}.</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="text-xs text-zinc-400 hover:text-white font-bold">
            ← Kembali ke List Menu
        </a>
    </div>

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                    Nama Menu <span class="text-white">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" required
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition-colors">
            </div>

            <div>
                <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                    Kategori <span class="text-white">*</span>
                </label>
                <select name="category_id" id="category_id" required
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition-colors">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="price" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                    Harga (Rupiah) <span class="text-white">*</span>
                </label>
                <input type="number" name="price" id="price" value="{{ old('price', $menu->price) }}" required min="0" step="500"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition-colors">
            </div>

            <div>
                <label for="image" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                    Foto Menu Baru (Opsional)
                </label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-xs text-zinc-400 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-800 file:text-white hover:file:bg-zinc-700">
            </div>
        </div>

        <div>
            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">
                Deskripsi Singkat
            </label>
            <textarea name="description" id="description" rows="3"
                class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-white transition-colors">{{ old('description', $menu->description) }}</textarea>
        </div>

        {{-- Status Checkboxes --}}
        <div class="border-t border-b border-zinc-800 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-bold">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}
                    class="w-4 h-4 text-white bg-zinc-950 border-zinc-700 rounded focus:ring-zinc-500">
                <span class="text-white">Menu Tersedia</span>
            </label>

            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $menu->is_featured) ? 'checked' : '' }}
                    class="w-4 h-4 text-white bg-zinc-950 border-zinc-700 rounded focus:ring-zinc-500">
                <span class="text-white">Tampilkan di Menu Unggulan</span>
            </label>

            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $menu->is_best_seller) ? 'checked' : '' }}
                    class="w-4 h-4 text-white bg-zinc-950 border-zinc-700 rounded focus:ring-zinc-500">
                <span class="text-white">Label Best Seller</span>
            </label>
        </div>

        {{-- Dynamic Option Variants --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-300">
                    Opsi Varian & Add-On Tambahan
                </h3>
                <button type="button" onclick="addOptionRow()" class="text-xs bg-zinc-800 hover:bg-zinc-700 text-white font-bold px-3 py-1.5 rounded-lg border border-zinc-700">
                    + Tambah Opsi
                </button>
            </div>

            <div id="optionsContainer" class="space-y-3">
                {{-- Prefill existing options --}}
            </div>
        </div>

        <div class="pt-4 border-t border-zinc-800 flex justify-end space-x-3">
            <a href="{{ route('admin.menus.index') }}" class="px-5 py-3 rounded-xl bg-zinc-800 text-zinc-300 font-bold text-xs uppercase hover:bg-zinc-700 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-white text-zinc-950 font-black text-xs uppercase tracking-wider hover:bg-zinc-200 transition-all shadow-xl">
                Perbarui Menu
            </button>
        </div>
    </form>

</div>

<script>
let optionIndex = 0;
function addOptionRow(name = '', price = 0) {
    const container = document.getElementById('optionsContainer');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-3 bg-zinc-950 p-3 rounded-xl border border-zinc-800';
    row.id = `optRow_${optionIndex}`;
    row.innerHTML = `
        <input type="text" name="options[${optionIndex}][name]" value="${name}" placeholder="Nama Opsi (Contoh: Hot / Extra Shot)" required
            class="flex-1 bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-xs text-white">
        <input type="number" name="options[${optionIndex}][price]" value="${price}" placeholder="Harga Tambahan" required
            class="w-32 bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-xs text-white">
        <button type="button" onclick="document.getElementById('optRow_${optionIndex}').remove()" class="text-red-400 hover:text-red-300 p-2 font-bold text-xs">
            ✕
        </button>
    `;
    container.appendChild(row);
    optionIndex++;
}

// Init existing options
@foreach($menu->options as $opt)
    addOptionRow("{{ $opt->name }}", {{ $opt->price }});
@endforeach
</script>
@endsection
