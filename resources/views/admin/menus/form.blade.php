@extends('layouts.admin')
@section('title', (isset($menu) ? 'Edit Menu: ' . $menu->name : 'Tambah Menu Baru') . ' — Nucomu Cafe')

@section('content')
@php
    $isEdit = isset($menu);
    $actionUrl = $isEdit ? route('admin.menus.update', $menu->id) : route('admin.menus.store');
    $currentImg = null;
    if ($isEdit && $menu->image) {
        if (file_exists(public_path('storage/' . $menu->image))) {
            $currentImg = asset('storage/' . $menu->image);
        } elseif (str_starts_with($menu->image, 'http')) {
            $currentImg = $menu->image;
        }
    }
@endphp

<div class="max-w-5xl mx-auto space-y-8">
    
    {{-- Header Navigation --}}
    <div class="flex items-center justify-between pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-utensils text-[10px]"></i>
                <span>{{ $isEdit ? 'Perbarui Sajian' : 'Tambah Sajian Baru' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                {{ $isEdit ? 'Edit Menu: ' . $menu->name : 'Tambah Menu Baru' }}
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Lengkapi rincian sajian untuk katalog web pelanggan Nucomu Cafe.
            </p>
        </div>

        <a href="{{ route('admin.menus.index') }}" class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition btn-press flex items-center gap-2 shadow-xs">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Kembali ke Katalog</span>
        </a>
    </div>

    {{-- Studio Form Container --}}
    <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" 
          class="bg-pure-white border border-hairline rounded-3xl p-6 sm:p-8 space-y-6 shadow-card reveal-on-scroll anim-fade-up">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Left Column: Interactive Image Dropzone & Preview (4 Cols) --}}
            <div class="lg:col-span-5 space-y-3">
                <label class="block text-xs font-bold text-charcoal">
                    Foto Sajian Menu
                </label>

                <div onclick="document.getElementById('menuFormFileInput').click()"
                     class="relative w-full aspect-[4/3] rounded-2xl border-2 border-dashed border-hairline hover:border-ink-black bg-warm-ivory flex flex-col items-center justify-center p-6 text-center cursor-pointer transition-all group overflow-hidden">
                    
                    <input type="file" name="image" id="menuFormFileInput" accept="image/*" class="hidden" onchange="previewMenuFormFile(event)">

                    {{-- Default placeholder if no image exists --}}
                    <div id="formImgPlaceholder" class="{{ $currentImg ? 'hidden' : '' }} space-y-3 pointer-events-none group-hover:scale-105 transition-transform">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-hairline text-ink-black flex items-center justify-center mx-auto shadow-2xs">
                            <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-ink-black block">Klik untuk memilih foto</span>
                            <span class="text-[10px] text-muted-text block mt-0.5">JPG, PNG, WebP (Maks. 2MB)</span>
                        </div>
                    </div>

                    {{-- Live Image Preview --}}
                    <img id="formImgPreview" src="{{ $currentImg ?? '' }}" alt="Preview" class="{{ $currentImg ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover">
                    
                    <div id="formImgOverlay" class="{{ $currentImg ? '' : 'hidden' }} absolute inset-0 bg-ink-black/40 backdrop-blur-xs flex items-center justify-center text-white text-xs font-bold gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Ganti Foto</span>
                    </div>
                </div>

                <p class="text-[10px] text-muted-text text-center">
                    Gunakan foto dengan rasio 4:3 atau kotak agar presisi di katalog web customer.
                </p>
            </div>

            {{-- Right Column: Form Inputs (7 Cols) --}}
            <div class="lg:col-span-7 space-y-5">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-charcoal mb-1.5">
                            Nama Menu <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $menu->name ?? '') }}" required placeholder="Contoh: Classic Tiramisu Nucomu"
                               class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                    </div>

                    <div>
                        <label for="category_id" class="block text-xs font-bold text-charcoal mb-1.5">
                            Kategori <span class="text-rose-600">*</span>
                        </label>
                        <select name="category_id" id="category_id" required
                                class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Price & Quick Presets --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="price" class="text-xs font-bold text-charcoal">
                            Harga Menu (Rupiah) <span class="text-rose-600">*</span>
                        </label>
                        <span class="text-[10px] text-muted-text">Pilihan harga cepat:</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs font-mono font-bold text-muted-text">Rp</span>
                        <input type="number" name="price" id="price" value="{{ old('price', $menu->price ?? '') }}" required min="0" step="500" placeholder="28000"
                               class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 pl-10 text-xs text-ink-black font-mono font-bold focus:outline-none focus:border-ink-black focus:bg-white transition">
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        <button type="button" onclick="document.getElementById('price').value = 18000" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            18.000
                        </button>
                        <button type="button" onclick="document.getElementById('price').value = 22000" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            22.000
                        </button>
                        <button type="button" onclick="document.getElementById('price').value = 25000" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            25.000
                        </button>
                        <button type="button" onclick="document.getElementById('price').value = 28000" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            28.000
                        </button>
                        <button type="button" onclick="document.getElementById('price').value = 35000" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            35.000
                        </button>
                    </div>
                </div>

                {{-- Stock & Quick Presets (Synced with Kasir & DB) --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="stock" class="text-xs font-bold text-charcoal">
                            Stok Porsi (Tersinkron ke POS Kasir) <span class="text-rose-600">*</span>
                        </label>
                        <span class="text-[10px] text-muted-text">Pilihan stok cepat:</span>
                    </div>
                    <div class="relative">
                        <input type="number" name="stock" id="stock" value="{{ old('stock', isset($menu) ? ($menu->stock ?? ($menu->is_available ? 25 : 0)) : 25) }}" required min="0" max="9999" placeholder="25"
                               class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 pr-16 text-xs text-ink-black font-mono font-bold focus:outline-none focus:border-ink-black focus:bg-white transition">
                        <span class="absolute right-3.5 top-2.5 text-[10px] font-mono font-bold text-muted-text uppercase">Porsi</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        <button type="button" onclick="document.getElementById('stock').value = 0" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 border border-rose-200 transition">
                            0 (Habis)
                        </button>
                        <button type="button" onclick="document.getElementById('stock').value = 10" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            10 Porsi
                        </button>
                        <button type="button" onclick="document.getElementById('stock').value = 25" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            25 Porsi
                        </button>
                        <button type="button" onclick="document.getElementById('stock').value = 50" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            50 Porsi
                        </button>
                        <button type="button" onclick="document.getElementById('stock').value = 100" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                            100 Porsi
                        </button>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-charcoal mb-1.5">
                        Deskripsi Sajian &amp; Catatan Rasa
                    </label>
                    <textarea name="description" id="description" rows="3" placeholder="Jelaskan aroma, cita rasa, dan bahan racikan menu..."
                              class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">{{ old('description', $menu->description ?? '') }}</textarea>
                </div>

                {{-- Status Switches (Tersedia, Unggulan, Best Seller) --}}
                <div class="p-4 rounded-2xl bg-warm-ivory border border-hairline grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-bold">
                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-white border border-hairline hover:bg-warm-stone/40 transition">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-ink-black bg-white border-hairline rounded">
                        <span class="text-ink-black">Menu Tersedia</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-white border border-hairline hover:bg-warm-stone/40 transition">
                        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $menu->is_best_seller ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 text-ink-black bg-white border-hairline rounded">
                        <span class="text-charcoal">Best Seller</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-white border border-hairline hover:bg-warm-stone/40 transition">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $menu->is_featured ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 text-ink-black bg-white border-hairline rounded">
                        <span class="text-charcoal">Unggulan</span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="pt-4 border-t border-hairline flex items-center justify-between">
                    <a href="{{ route('admin.menus.index') }}" class="text-xs font-bold text-muted-text hover:text-ink-black transition">
                        Batal &amp; Kembali
                    </a>
                    <button type="submit" class="bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded-xl transition shadow-xs btn-press">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambahkan Menu' }}
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>

<script>
    function previewMenuFormFile(e) {
        const file = e.target.files && e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.getElementById('formImgPreview');
            const overlay = document.getElementById('formImgOverlay');
            const placeholder = document.getElementById('formImgPlaceholder');

            if (preview) {
                preview.src = evt.target.result;
                preview.classList.remove('hidden');
            }
            if (overlay) overlay.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
