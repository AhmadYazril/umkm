@extends('layouts.admin')
@section('title', 'Katalog Sajian Kafe — Nucomu Cafe')

@section('content')
@php
    $totalCount = $menus->total();
    $availableCount = \App\Models\Menu::where('is_available', true)->count();
    $unavailableCount = \App\Models\Menu::where('is_available', false)->count();
    $bestSellerCount = \App\Models\Menu::where('is_best_seller', true)->count();
@endphp

<div class="space-y-8 max-w-7xl mx-auto">
    
    {{-- Header Section & Summary Metrics --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-mug-hot text-[10px]"></i>
                <span>Katalog Sajian Kafe &bull; Nucomu Workspace</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Katalog Menu &amp; Varian
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Atur kopi, artisanal dessert, minuman non-kopi, dan makanan ringan yang dapat dipesan pelanggan.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Quick Add Toggle Button --}}
            <button type="button" onclick="toggleMenuDrawer()" id="btnToggleMenuDrawer"
                    class="px-4 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition-all shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-plus text-xs" id="iconToggleMenu"></i>
                <span id="textToggleMenu">Tambah Menu Cepat</span>
            </button>

            @if(\App\Models\Menu::where('is_sample', true)->exists())
                <form action="{{ route('admin.menus.deleteSample') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua menu contoh bawaan?')">
                    @csrf
                    <button type="submit" class="bg-pure-white hover:bg-rose-50 text-rose-700 hover:text-rose-800 border border-hairline font-bold text-xs px-3.5 py-2.5 rounded-xl transition flex items-center space-x-2 shadow-xs btn-press" title="Hapus menu contoh bawaan">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Hapus Contoh</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('kasir.index') }}" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Buka POS Kasir (/kasir)">
                <i class="fa-solid fa-cash-register text-xs"></i>
                <span>POS Kasir</span>
            </a>

            <a href="{{ route('home') }}#all-menu-section" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Lihat Menu di Website Pelanggan">
                <span>Web Customer</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- 4 Metric Summary Chips (Warm Monochrome) --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 reveal-on-scroll">
        <div class="bg-pure-white rounded-2xl border border-hairline p-4 shadow-card flex items-center justify-between">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Total Menu</span>
                <span class="text-xl sm:text-2xl font-mono font-extrabold text-ink-black">{{ $totalCount }}</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                <i class="fa-solid fa-utensils text-xs"></i>
            </div>
        </div>

        <div class="bg-pure-white rounded-2xl border border-hairline p-4 shadow-card flex items-center justify-between">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Tersedia</span>
                <span class="text-xl sm:text-2xl font-mono font-extrabold text-emerald-700">{{ $availableCount }}</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-800">
                <i class="fa-solid fa-circle-check text-xs"></i>
            </div>
        </div>

        <div class="bg-pure-white rounded-2xl border border-hairline p-4 shadow-card flex items-center justify-between">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Habis (Sold Out)</span>
                <span class="text-xl sm:text-2xl font-mono font-extrabold text-rose-700">{{ $unavailableCount }}</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-800">
                <i class="fa-solid fa-ban text-xs"></i>
            </div>
        </div>

        <div class="bg-pure-white rounded-2xl border border-hairline p-4 shadow-card flex items-center justify-between">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Best Seller</span>
                <span class="text-xl sm:text-2xl font-mono font-extrabold text-amber-800">{{ $bestSellerCount }}</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-800">
                <i class="fa-solid fa-star text-xs"></i>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         COLLAPSIBLE QUICK ADD MENU STUDIO (Tambah Menu Cepat Tanpa Pindah Halaman)
         ========================================================================= --}}
    <div id="quickMenuDrawer" class="hidden bg-pure-white border border-hairline rounded-3xl p-6 sm:p-8 shadow-card reveal-on-scroll anim-fade-up">
        <div class="flex items-center justify-between pb-4 border-b border-hairline mb-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                    <i class="fa-solid fa-plus text-xs"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-ink-black">Tambah Menu Baru Cepat</h2>
                    <p class="text-[11px] text-muted-text">Tambahkan racikan menu langsung ke katalog web tanpa meninggalkan halaman.</p>
                </div>
            </div>
            <button type="button" onclick="toggleMenuDrawer()" class="p-2 text-muted-text hover:text-ink-black rounded-lg transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- Left: Image Upload Dropzone (4 Cols) --}}
                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold text-charcoal mb-2">
                        Foto Sajian Menu
                    </label>

                    <div onclick="document.getElementById('quickMenuImageInput').click()"
                         class="relative w-full aspect-[4/3] rounded-2xl border-2 border-dashed border-hairline hover:border-ink-black bg-warm-ivory flex flex-col items-center justify-center p-4 text-center cursor-pointer transition-all group overflow-hidden">
                        
                        <input type="file" name="image" id="quickMenuImageInput" accept="image/*" class="hidden" onchange="previewQuickMenuImage(event)">

                        <div id="quickMenuPlaceholder" class="space-y-2 pointer-events-none group-hover:scale-105 transition-transform">
                            <div class="w-10 h-10 rounded-xl bg-white border border-hairline text-ink-black flex items-center justify-center mx-auto shadow-2xs">
                                <i class="fa-solid fa-camera text-sm"></i>
                            </div>
                            <span class="text-xs font-bold text-ink-black block">Pilih Foto Menu</span>
                            <span class="text-[10px] text-muted-text block">JPG, PNG, WebP (Maks. 2MB)</span>
                        </div>

                        <img id="quickMenuPreviewImg" src="" alt="Preview Menu" class="hidden absolute inset-0 w-full h-full object-cover">
                        <div id="quickMenuPreviewOverlay" class="hidden absolute inset-0 bg-ink-black/40 backdrop-blur-xs flex items-center justify-center text-white text-xs font-bold gap-1.5">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Ganti Foto</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Essential Fields & Price Presets (8 Cols) --}}
                <div class="lg:col-span-8 space-y-4">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="quick_name" class="block text-xs font-bold text-charcoal mb-1.5">
                                Nama Menu <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="name" id="quick_name" required placeholder="Contoh: Iced Palm Sugar Latte"
                                   class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                        </div>

                        <div>
                            <label for="quick_category_id" class="block text-xs font-bold text-charcoal mb-1.5">
                                Kategori <span class="text-rose-600">*</span>
                            </label>
                            <select name="category_id" id="quick_category_id" required
                                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Harga & Preset Cepat --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="quick_price" class="text-xs font-bold text-charcoal">
                                Harga Menu (Rupiah) <span class="text-rose-600">*</span>
                            </label>
                            <span class="text-[10px] text-muted-text">Pilihan harga cepat:</span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-xs font-mono font-bold text-muted-text">Rp</span>
                            <input type="number" name="price" id="quick_price" required min="0" step="500" placeholder="25000"
                                   class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 pl-10 text-xs text-ink-black font-mono font-bold focus:outline-none focus:border-ink-black focus:bg-white transition">
                        </div>
                        
                        {{-- Price preset buttons --}}
                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                            <button type="button" onclick="setQuickPrice(15000)" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                15.000
                            </button>
                            <button type="button" onclick="setQuickPrice(20000)" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                20.000
                            </button>
                            <button type="button" onclick="setQuickPrice(25000)" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                25.000
                            </button>
                            <button type="button" onclick="setQuickPrice(28000)" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                28.000
                            </button>
                            <button type="button" onclick="setQuickPrice(32000)" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                32.000
                            </button>
                            <button type="button" onclick="setQuickPrice(38000)" class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                38.000
                            </button>
                        </div>
                    </div>

                    {{-- Deskripsi Singkat & Stok Awal --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label for="quick_description" class="block text-xs font-bold text-charcoal mb-1.5">
                                Deskripsi Singkat / Catatan Rasa
                            </label>
                            <input type="text" name="description" id="quick_description" placeholder="Espresso house blend dengan susu segar dan gula aren alami..."
                                   class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2 text-xs text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition">
                        </div>
                        <div>
                            <label for="quick_stock" class="block text-xs font-bold text-charcoal mb-1.5">
                                Stok Awal (Porsi)
                            </label>
                            <input type="number" name="stock" id="quick_stock" value="25" min="0" max="9999" required
                                   class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2 text-xs font-mono font-bold text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Checkboxes Status --}}
                    <div class="flex flex-wrap items-center gap-3 pt-2 text-xs font-bold">
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-warm-ivory border border-hairline hover:bg-warm-stone/50 transition">
                            <input type="checkbox" name="is_available" value="1" checked class="w-4 h-4 text-ink-black bg-white border-hairline rounded">
                            <span class="text-ink-black">Langsung Tersedia</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-warm-ivory border border-hairline hover:bg-warm-stone/50 transition">
                            <input type="checkbox" name="is_best_seller" value="1" class="w-4 h-4 text-ink-black bg-white border-hairline rounded">
                            <span class="text-charcoal">Tandai Best Seller</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-warm-ivory border border-hairline hover:bg-warm-stone/50 transition">
                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-ink-black bg-white border-hairline rounded">
                            <span class="text-charcoal">Tandai Unggulan</span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-hairline flex items-center justify-end gap-3">
                        <button type="button" onclick="toggleMenuDrawer()" class="px-4 py-2 rounded-xl border border-hairline text-xs font-bold text-charcoal hover:bg-warm-stone transition btn-press">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press">
                            Simpan ke Menu
                        </button>
                    </div>

                </div>

            </div>
        </form>
    </div>

    {{-- =========================================================================
         FLEXIBLE FILTER & VIEW MODE TOOLBAR (Kategori, Live Search & View Switcher)
         ========================================================================= --}}
    <div class="bg-pure-white border border-hairline rounded-2xl p-4 shadow-card space-y-3 reveal-on-scroll">
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            
            {{-- Category Filter Pills --}}
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-bold pb-1 select-none">
                <a href="{{ route('admin.menus.index') }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 {{ !request('category') ? 'bg-ink-black text-white shadow-xs' : 'bg-warm-stone/70 text-charcoal hover:bg-warm-stone border border-hairline' }}">
                    Semua ({{ $totalCount }})
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('admin.menus.index', ['category' => $cat->id, 'status' => request('status')]) }}" 
                       class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 {{ request('category') == $cat->id ? 'bg-ink-black text-white shadow-xs' : 'bg-warm-stone/70 text-charcoal hover:bg-warm-stone border border-hairline' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            {{-- Right: View Switcher (Grid vs Table) --}}
            <div class="flex items-center gap-2 self-start lg:self-auto">
                <div class="flex items-center gap-1 bg-warm-stone/70 p-1 rounded-xl border border-hairline text-xs font-bold">
                    <button type="button" onclick="switchMenuView('grid')" id="btnViewMenuGrid" 
                            class="px-3 py-1.5 rounded-lg bg-pure-white text-ink-black shadow-xs transition flex items-center gap-1.5"
                            title="Tampilan Kartu Visual">
                        <i class="fa-solid fa-grip text-xs"></i>
                        <span>Grid Visual</span>
                    </button>
                    <button type="button" onclick="switchMenuView('table')" id="btnViewMenuTable" 
                            class="px-3 py-1.5 rounded-lg text-muted-text hover:text-ink-black transition flex items-center gap-1.5"
                            title="Tampilan Tabel Rinci">
                        <i class="fa-solid fa-list text-xs"></i>
                        <span>Tabel</span>
                    </button>
                </div>
            </div>

        </div>

        {{-- Second Row: Status Filter Chips & Live Search --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-hairline text-xs">
            
            {{-- Quick Status Filter Chips --}}
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[10px] uppercase font-bold text-muted-text font-mono mr-1">Status:</span>
                <a href="{{ route('admin.menus.index', ['category' => request('category')]) }}" 
                   class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition {{ !request('status') ? 'bg-ink-black text-white' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
                    Semua
                </a>
                <a href="{{ route('admin.menus.index', ['category' => request('category'), 'status' => 'available']) }}" 
                   class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition {{ request('status') === 'available' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
                    ● Tersedia
                </a>
                <a href="{{ route('admin.menus.index', ['category' => request('category'), 'status' => 'unavailable']) }}" 
                   class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition {{ request('status') === 'unavailable' ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
                    ○ Habis
                </a>
            </div>

            {{-- Live Search Input --}}
            <div class="relative w-full sm:w-64">
                <input type="text" id="menuLiveSearch" placeholder="Cari nama menu..."
                       oninput="filterMenuItemsClient(this.value)"
                       value="{{ request('search') }}"
                       class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-1.5 pl-9 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-muted-text text-xs"></i>
            </div>

        </div>

    </div>

    {{-- =========================================================================
         VIEW 1: GRID VISUAL (Koleksi Kartu Produk Nucomu yang Bersih & Fotogenik)
         ========================================================================= --}}
    <div id="menuGridView" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 reveal-on-scroll">
        @forelse($menus as $menu)
            @php
                $menuImageUrl = null;
                if ($menu->image && file_exists(public_path('storage/' . $menu->image))) {
                    $menuImageUrl = asset('storage/' . $menu->image);
                } elseif ($menu->image && (str_starts_with($menu->image, 'http://') || str_starts_with($menu->image, 'https://'))) {
                    $menuImageUrl = $menu->image;
                }
            @endphp

            <div class="menu-item-card group bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 flex flex-col justify-between hover:border-ink-black transition-all duration-300 shadow-card card-lift"
                 data-menu-name="{{ strtolower($menu->name) }}"
                 data-menu-category="{{ strtolower($menu->category->name ?? '') }}">
                
                <div>
                    {{-- 4:3 Editorial Focus Image Frame --}}
                    <div class="relative overflow-hidden aspect-[4/3] rounded-xl sm:rounded-2xl mb-3 bg-warm-stone border border-hairline">
                        @if($menuImageUrl)
                            <img src="{{ $menuImageUrl }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-muted-text text-xs">
                                <i class="fa-solid fa-mug-hot text-xl"></i>
                            </div>
                        @endif

                        {{-- Category Pill --}}
                        <span class="absolute top-2 left-2 bg-ink-black/80 backdrop-blur-xs text-white px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-md font-mono border border-white/10">
                            {{ $menu->category->name ?? 'Menu' }}
                        </span>

                        {{-- Badges at Top Right --}}
                        <div class="absolute top-2 right-2 flex flex-col items-end gap-1">
                            @if($menu->is_best_seller)
                                <span class="bg-amber-400 text-stone-950 font-bold px-2 py-0.5 text-[9px] uppercase tracking-wider rounded-md shadow-xs flex items-center gap-1">
                                    <i class="fa-solid fa-fire text-[8px]"></i> Best
                                </span>
                            @endif
                            @if($menu->is_sample)
                                <span class="bg-white/90 text-charcoal border border-hairline font-bold px-1.5 py-0.2 text-[8px] uppercase rounded">
                                    Contoh
                                </span>
                            @endif
                        </div>

                        {{-- Quick Availability Floating Pill & Live Stock Badge --}}
                        @php
                            $menuStockVal = $menu->stock !== null ? (int) $menu->stock : ($menu->is_available ? 25 : 0);
                            if (!$menu->is_available) {
                                $menuStockVal = 0;
                            }
                        @endphp
                        <div class="absolute bottom-2 left-2 right-2 flex items-center justify-between gap-1.5">
                            <form action="{{ route('admin.menus.toggleAvailable', $menu->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        id="adminGridStatusBtn-{{ $menu->id }}"
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold shadow-xs transition btn-press flex items-center gap-1.5 {{ $menu->is_available && $menuStockVal > 0 ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-rose-600 hover:bg-rose-700 text-white' }}"
                                        title="Klik untuk mengubah ketersediaan">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    <span id="adminGridStatusText-{{ $menu->id }}">{{ $menu->is_available && $menuStockVal > 0 ? 'Tersedia' : 'Habis' }}</span>
                                </button>
                            </form>

                            <span id="adminGridStockPill-{{ $menu->id }}"
                                  class="px-2 py-0.5 rounded-full text-[9.5px] font-mono font-bold shadow-xs border backdrop-blur-xs {{ $menuStockVal > 5 ? 'bg-white/95 text-ink-black border-hairline' : ($menuStockVal > 0 ? 'bg-amber-50/95 text-amber-800 border-amber-300' : 'bg-rose-600 text-white border-rose-700') }}">
                                Stok: {{ $menuStockVal }}
                            </span>
                        </div>
                    </div>

                    {{-- Title & Short Info --}}
                    <div class="space-y-1">
                        <div class="flex items-start justify-between gap-1">
                            <h3 class="font-sans font-bold text-xs sm:text-sm text-ink-black leading-snug group-hover:underline underline-offset-2 line-clamp-1">
                                {{ $menu->name }}
                            </h3>
                        </div>
                        <p class="text-[11px] text-muted-text line-clamp-1 leading-relaxed">
                            {{ $menu->description ?: 'Pilihan racikan Nucomu Cafe' }}
                        </p>
                    </div>
                </div>

                {{-- Inline Stock Control & Action Bar --}}
                <div class="pt-3 border-t border-hairline space-y-2.5 mt-3">
                    <div class="flex items-center justify-between bg-warm-ivory border border-hairline rounded-xl px-2.5 py-1.5">
                        <span class="text-[10px] font-mono font-bold text-muted-text uppercase">Stok Kasir</span>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="adjustAdminMenuStock({{ $menu->id }}, { delta: -1 })"
                                    class="w-6 h-6 rounded-lg bg-white hover:bg-ink-black hover:text-white border border-hairline text-ink-black font-bold text-xs flex items-center justify-center transition">
                                -
                            </button>
                            <input type="number" id="adminGridStockInput-{{ $menu->id }}" value="{{ $menuStockVal }}" min="0" max="9999"
                                   onchange="adjustAdminMenuStock({{ $menu->id }}, { stock: parseInt(this.value || '0', 10) })"
                                   class="w-11 text-center bg-white border border-hairline rounded-lg py-0.5 text-[11px] font-mono font-bold text-ink-black focus:outline-none focus:border-ink-black">
                            <button type="button" onclick="adjustAdminMenuStock({{ $menu->id }}, { delta: 1 })"
                                    class="w-6 h-6 rounded-lg bg-white hover:bg-ink-black hover:text-white border border-hairline text-ink-black font-bold text-xs flex items-center justify-center transition">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-xs sm:text-sm text-ink-black">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </span>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.menus.edit', $menu->id) }}" 
                               class="w-7 h-7 rounded-lg bg-warm-stone hover:bg-ink-black hover:text-white text-ink-black flex items-center justify-center text-xs transition btn-press" 
                               title="Edit Menu">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                            </a>

                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-7 h-7 rounded-lg bg-warm-stone hover:bg-rose-50 text-muted-text hover:text-rose-700 flex items-center justify-center text-xs transition btn-press" 
                                        title="Hapus Menu">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-pure-white border border-hairline rounded-3xl p-8 text-muted-text text-xs">
                <div class="w-14 h-14 rounded-2xl bg-warm-stone border border-hairline flex items-center justify-center mx-auto mb-3 text-ink-black text-xl shadow-2xs">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <h3 class="text-sm font-bold text-ink-black">Belum Ada Menu</h3>
                <p class="text-xs text-muted-text max-w-md mx-auto mt-1">
                    Katalog menu kafe Anda masih kosong atau tidak ada menu yang cocok dengan pencarian.
                </p>
                <button type="button" onclick="toggleMenuDrawer()" class="mt-4 px-5 py-2.5 rounded-xl bg-ink-black text-white font-bold text-xs uppercase tracking-wider shadow-xs hover:bg-charcoal transition btn-press inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Menu Baru</span>
                </button>
            </div>
        @endforelse
    </div>

    {{-- =========================================================================
         VIEW 2: TABEL RINGKAS (Untuk Audit & Administrasi Cepat)
         ========================================================================= --}}
    <div id="menuTableView" class="hidden bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl overflow-hidden shadow-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal">
                <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                    <tr>
                        <th class="px-6 py-4">Menu</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Stok (Kasir &amp; DB)</th>
                        <th class="px-6 py-4">Ketersediaan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($menus as $menu)
                        @php
                            $tableImg = null;
                            if ($menu->image && file_exists(public_path('storage/' . $menu->image))) {
                                $tableImg = asset('storage/' . $menu->image);
                            } elseif ($menu->image && (str_starts_with($menu->image, 'http://') || str_starts_with($menu->image, 'https://'))) {
                                $tableImg = $menu->image;
                            }
                            $rowStock = $menu->stock !== null ? (int) $menu->stock : ($menu->is_available ? 25 : 0);
                            if (!$menu->is_available) {
                                $rowStock = 0;
                            }
                        @endphp
                        <tr class="hover:bg-warm-stone/30 transition-colors menu-table-row" data-menu-name="{{ strtolower($menu->name) }}">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-warm-stone border border-hairline overflow-hidden shrink-0 shadow-2xs">
                                        @if($tableImg)
                                            <img src="{{ $tableImg }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-muted-text">
                                                <i class="fa-solid fa-mug-hot text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-ink-black text-xs block truncate">{{ $menu->name }}</span>
                                            @if($menu->is_best_seller)
                                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-amber-50 text-amber-800 border border-amber-200 font-bold">★ Best</span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-muted-text truncate max-w-xs">{{ $menu->description ?: 'Nucomu Cafe' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-warm-stone text-charcoal border border-hairline uppercase font-mono">
                                    {{ $menu->category->name ?? 'Menu' }}
                                </span>
                            </td>

                            <td class="px-6 py-3.5 font-mono font-bold text-ink-black text-xs">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-3.5">
                                <div class="inline-flex items-center gap-1 bg-warm-ivory border border-hairline rounded-xl p-1">
                                    <button type="button" onclick="adjustAdminMenuStock({{ $menu->id }}, { delta: -1 })"
                                            class="w-6 h-6 rounded-lg bg-white hover:bg-ink-black hover:text-white border border-hairline text-ink-black font-bold text-xs flex items-center justify-center transition">
                                        -
                                    </button>
                                    <input type="number" id="adminTableStockInput-{{ $menu->id }}" value="{{ $rowStock }}" min="0" max="9999"
                                           onchange="adjustAdminMenuStock({{ $menu->id }}, { stock: parseInt(this.value || '0', 10) })"
                                           class="w-12 text-center bg-white border border-hairline rounded-lg py-0.5 text-[11px] font-mono font-bold text-ink-black focus:outline-none focus:border-ink-black">
                                    <button type="button" onclick="adjustAdminMenuStock({{ $menu->id }}, { delta: 1 })"
                                            class="w-6 h-6 rounded-lg bg-white hover:bg-ink-black hover:text-white border border-hairline text-ink-black font-bold text-xs flex items-center justify-center transition">
                                        +
                                    </button>
                                </div>
                            </td>

                            <td class="px-6 py-3.5">
                                <form action="{{ route('admin.menus.toggleAvailable', $menu->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" id="adminTableStatusBtn-{{ $menu->id }}" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition btn-press {{ $menu->is_available && $rowStock > 0 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
                                        {{ $menu->is_available && $rowStock > 0 ? '● Tersedia' : '○ Habis' }}
                                    </button>
                                </form>
                            </td>

                            <td class="px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="p-1.5 rounded-lg bg-warm-stone hover:bg-ink-black hover:text-white text-ink-black transition btn-press" title="Edit Menu">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-warm-stone hover:bg-rose-50 text-muted-text hover:text-rose-700 transition btn-press" title="Hapus Menu">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-muted-text">
                                Belum ada menu yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Bar --}}
    @if($menus->hasPages())
        <div class="p-4 bg-pure-white border border-hairline rounded-2xl shadow-card flex items-center justify-center">
            {{ $menus->links() }}
        </div>
    @endif

</div>

<script>
    // 1. Toggle Quick Menu Add Drawer
    function toggleMenuDrawer() {
        const drawer = document.getElementById('quickMenuDrawer');
        const icon = document.getElementById('iconToggleMenu');
        const text = document.getElementById('textToggleMenu');
        if (!drawer) return;

        const isHidden = drawer.classList.contains('hidden');
        if (isHidden) {
            drawer.classList.remove('hidden');
            if (icon) icon.className = 'fa-solid fa-xmark text-xs';
            if (text) text.textContent = 'Tutup Formulir';
            drawer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            drawer.classList.add('hidden');
            if (icon) icon.className = 'fa-solid fa-plus text-xs';
            if (text) text.textContent = 'Tambah Menu Cepat';
        }
    }

    // 2. Instant Image Preview for Quick Add
    function previewQuickMenuImage(e) {
        const file = e.target.files && e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.getElementById('quickMenuPreviewImg');
            const overlay = document.getElementById('quickMenuPreviewOverlay');
            const placeholder = document.getElementById('quickMenuPlaceholder');

            if (preview) {
                preview.src = evt.target.result;
                preview.classList.remove('hidden');
            }
            if (overlay) overlay.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    // 3. Quick Price Preset Setter
    function setQuickPrice(amount) {
        const input = document.getElementById('quick_price');
        if (input) input.value = amount;
    }

    // 4. View Switcher: Grid vs Table
    function switchMenuView(mode) {
        const gridView = document.getElementById('menuGridView');
        const tableView = document.getElementById('menuTableView');
        const btnGrid = document.getElementById('btnViewMenuGrid');
        const btnTable = document.getElementById('btnViewMenuTable');

        if (mode === 'grid') {
            if (gridView) gridView.classList.remove('hidden');
            if (tableView) tableView.classList.add('hidden');

            btnGrid.classList.add('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnGrid.classList.remove('text-muted-text');
            btnTable.classList.remove('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnTable.classList.add('text-muted-text');
            localStorage.setItem('nucomu_admin_menu_view', 'grid');
        } else {
            if (gridView) gridView.classList.add('hidden');
            if (tableView) tableView.classList.remove('hidden');

            btnTable.classList.add('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnTable.classList.remove('text-muted-text');
            btnGrid.classList.remove('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnGrid.classList.add('text-muted-text');
            localStorage.setItem('nucomu_admin_menu_view', 'table');
        }
    }

    // 5. Client-Side Instant Live Search Filter
    function filterMenuItemsClient(searchTerm) {
        const query = searchTerm.toLowerCase().trim();
        const cards = document.querySelectorAll('.menu-item-card');
        const rows = document.querySelectorAll('.menu-table-row');

        cards.forEach(card => {
            const name = card.getAttribute('data-menu-name') || '';
            const cat = card.getAttribute('data-menu-category') || '';
            if (!query || name.includes(query) || cat.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });

        rows.forEach(row => {
            const name = row.getAttribute('data-menu-name') || '';
            if (!query || name.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // 6. Live Stock Control Synced Between Admin, DB, and POS Kasir
    async function adjustAdminMenuStock(menuId, payload) {
        try {
            const res = await fetch(`/admin/menu/${menuId}/stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (!res.ok || !data.success) return;

            const newStock = parseInt(data.stock, 10) || 0;
            const isAvail = newStock > 0;

            const gridInput = document.getElementById(`adminGridStockInput-${menuId}`);
            if (gridInput) gridInput.value = newStock;
            const tableInput = document.getElementById(`adminTableStockInput-${menuId}`);
            if (tableInput) tableInput.value = newStock;

            const gridPill = document.getElementById(`adminGridStockPill-${menuId}`);
            if (gridPill) {
                gridPill.textContent = `Stok: ${newStock}`;
                gridPill.className = `px-2 py-0.5 rounded-full text-[9.5px] font-mono font-bold shadow-xs border backdrop-blur-xs ${newStock > 5 ? 'bg-white/95 text-ink-black border-hairline' : (newStock > 0 ? 'bg-amber-50/95 text-amber-800 border-amber-300' : 'bg-rose-600 text-white border-rose-700')}`;
            }

            const gridStatusBtn = document.getElementById(`adminGridStatusBtn-${menuId}`);
            const gridStatusText = document.getElementById(`adminGridStatusText-${menuId}`);
            if (gridStatusBtn && gridStatusText) {
                gridStatusText.textContent = isAvail ? 'Tersedia' : 'Habis';
                gridStatusBtn.className = `px-2.5 py-1 rounded-full text-[10px] font-bold shadow-xs transition btn-press flex items-center gap-1.5 ${isAvail ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-rose-600 hover:bg-rose-700 text-white'}`;
            }

            const tableStatusBtn = document.getElementById(`adminTableStatusBtn-${menuId}`);
            if (tableStatusBtn) {
                tableStatusBtn.textContent = isAvail ? '● Tersedia' : '○ Habis';
                tableStatusBtn.className = `px-2.5 py-1 rounded-full text-[10px] font-bold border transition btn-press ${isAvail ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'}`;
            }

            if (typeof showAdminToast === 'function') {
                showAdminToast(data.message || 'Stok diperbarui di DB & Kasir', 'success', 'Sinkron Stok');
            }
        } catch (err) {
            if (typeof showAdminToast === 'function') {
                showAdminToast('Gagal memperbarui stok menu', 'error', 'Error');
            }
        }
    }

    // Restore saved view preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('nucomu_admin_menu_view');
        if (savedView === 'table') {
            switchMenuView('table');
        }
    });
</script>
@endsection
