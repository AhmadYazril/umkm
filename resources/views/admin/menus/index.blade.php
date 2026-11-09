@extends('layouts.admin')
@section('title', 'Menu — Nucomu Cafe')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">
    
    {{-- 1. Header Halaman Standar (x-page-header dengan Judul Tenang, Tombol ? Petunjuk, Kelola Kategori & + Tambah Menu) --}}
    <x-page-header title="Menu">
        <x-slot name="actions">
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                {{-- Tombol Petunjuk --}}
                <button type="button"
                        class="admin-help-link btn-press"
                        id="menu-help-trigger"
                        aria-expanded="false"
                        aria-controls="menu-help-panel-content"
                        onclick="toggleHelpPanel('menu-help-panel')">
                    <i class="fa-regular fa-circle-question text-[13px]" aria-hidden="true"></i>
                    <span class="toggle-label" data-open="? Petunjuk" data-close="Tutup petunjuk">? Petunjuk</span>
                    <i class="fa-solid fa-chevron-down text-[10px] toggle-chevron" aria-hidden="true"></i>
                </button>

                {{-- Tombol Kelola Kategori (Membuka Laci Kelola Kategori) --}}
                <button type="button"
                        onclick="openCategoryDrawer()"
                        class="min-h-[36px] px-3.5 py-2 rounded-xl text-[13px] font-semibold border transition flex items-center gap-2 shadow-xs cursor-pointer btn-press bg-white hover:bg-[#F0F4F1]"
                        style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                        title="Atur kelompok sajian dan urutan kategori">
                    <i class="fa-solid fa-tags text-xs" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                    <span>Kelola Kategori</span>
                </button>

                {{-- Tombol Tambah Menu (Hijau Solid, Teks Putih, Kontras >= 4.5:1) --}}
                <a href="{{ route('admin.menus.create') }}"
                   class="min-h-[36px] px-4 py-2 rounded-xl text-[13.5px] font-semibold transition flex items-center gap-2 shadow-sm cursor-pointer btn-press"
                   style="background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF;"
                   title="Tambah sajian menu baru ke katalog">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Tambah Menu</span>
                </a>
            </div>
        </x-slot>

        {{-- Panel Petunjuk Penggunaan (x-help-panel) --}}
        <x-slot name="help">
            <x-help-panel id="menu-help-panel" :trigger="false">
                <div class="space-y-4">
                    {{-- Judul Panel Petunjuk --}}
                    <div>
                        <h3 class="text-[15px] font-bold tracking-tight" style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                            Panduan Mengelola Menu &amp; Kategori
                        </h3>
                        <p class="text-[12.5px] mt-0.5 m-0" style="color: var(--admin-text-sub, #555D58);">
                            Kelola sajian makanan, minuman kopi &amp; non-kopi, serta kategori yang tampil di website pelanggan Nucomu Cafe.
                        </p>
                    </div>

                    {{-- 4 Langkah Singkat Menambah Menu --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-1">
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">1</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Nama &amp; Kategori</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Tulis nama menu yang menarik dan pilih kategori sajian yang sesuai.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">2</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Harga &amp; Penjelasan</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Ketik angka harga dalam rupiah tanpa titik. Tulis 1&ndash;2 kalimat deskripsi rasa.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">3</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Foto Sajian 1:1</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Unggah foto kotak (rasio 1:1, disarankan 800 &times; 800 px) agar tampak proporsional.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl border bg-white space-y-1.5" style="border-color: var(--admin-border-card, #E6E8E4);">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center"
                                  style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">4</span>
                            <div class="font-bold text-[13px]" style="color: var(--admin-text-main, #1B1F1D);">Menu Andalan</div>
                            <p class="text-[12px] leading-relaxed m-0" style="color: var(--admin-text-sub, #555D58);">
                                Beri bintang untuk menandai sajian sebagai menu andalan beranda website.
                            </p>
                        </div>
                    </div>

                    {{-- Arti Tiap Bagian & Di Mana Tampil di Website --}}
                    <div class="p-3.5 rounded-xl border text-[12.5px] leading-relaxed space-y-2"
                         style="background-color: var(--admin-bg-sidebar-hover, #F0F4F1); border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-circle-nodes text-xs" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                            <span>Arti Fitur &amp; Letaknya di Website</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Kategori Sajian:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Mengelompokkan menu di tab katalog website agar pelanggan mudah memilih sajian.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Menu Andalan (★):</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Diprioritaskan masuk ke 8 sajian pilihan yang dipajang di halaman utama (homepage) website.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Spesifikasi & Batas Ukuran Foto --}}
                    <div class="p-3.5 rounded-xl border text-[12.5px] leading-relaxed space-y-2"
                         style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); border-color: var(--admin-border-card, #E6E8E4);">
                        <div class="font-semibold flex items-center gap-2" style="color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-camera text-xs" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                            <span>Panduan Format &amp; Ukuran Foto Menu</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1 text-[12px]">
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Rasio Foto:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">1:1 (disarankan <strong>800 &times; 800 px</strong>) agar sajian tampil proporsional.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Format File:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">JPG, JPEG, PNG, atau WebP.</span>
                            </div>
                            <div>
                                <span class="font-semibold block" style="color: var(--admin-text-main, #1B1F1D);">Batas Ukuran Server:</span>
                                <span style="color: var(--admin-text-sub, #555D58);">Maksimal <strong>{{ $menuPhotoLimits['formatted'] ?? '1 MB' }}</strong> per foto.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tips Singkat --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[12px] pt-1"
                         style="color: var(--admin-text-sub, #555D58);">
                        <p class="m-0">
                            <strong>Tips Praktis:</strong> Gunakan tombol <span class="font-semibold text-emerald-800">"Kelola Kategori"</span> untuk menambah kategori baru atau mengatur urutan tampil kategori di website.
                        </p>
                    </div>
                </div>
            </x-help-panel>
        </x-slot>
    </x-page-header>

    {{-- Ringkasan Metrik Tenang (Satu Baris Teks Bersih) --}}
    <div class="flex flex-wrap items-center justify-between gap-2 text-[13px] px-1 -mt-2" style="color: var(--admin-text-sub, #555D58);">
        <div class="flex items-center gap-2">
            <span class="font-semibold" style="color: var(--admin-text-main, #1B1F1D);">{{ $totalCount }} menu terdaftar</span>
            @if($trashedCount > 0)
                <span aria-hidden="true">&middot;</span>
                <span class="text-rose-700 font-medium">{{ $trashedCount }} terarsip</span>
            @endif
        </div>
        <div class="flex items-center gap-3 text-xs">
            <a href="{{ route('kasir.index') }}" target="_blank"
               class="inline-flex items-center gap-1.5 font-semibold text-stone-700 hover:text-stone-900 transition"
               title="Buka POS Kasir (/kasir)">
                <i class="fa-solid fa-cash-register text-xs" aria-hidden="true"></i>
                <span>POS Kasir</span>
            </a>
            <span class="text-stone-300" aria-hidden="true">&middot;</span>
            <a href="{{ route('home') }}#all-menu-section" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 font-semibold text-emerald-800 hover:text-emerald-900 transition">
                <span>Lihat katalog di website</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    {{-- Alert Notifikasi Sukses / Gagal --}}
    @if(session('success'))
        <div class="p-3.5 sm:p-4 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-[13px] leading-relaxed anim-fade-up"
             style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); border-color: #C3DCD0; color: #164E33;">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 shrink-0 text-base" aria-hidden="true"></i>
                <div>
                    <span class="font-bold">Berhasil.</span>
                    <span class="opacity-90 ml-1">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 sm:p-4 rounded-xl border flex items-center justify-between gap-3 text-[13px] leading-relaxed anim-fade-up"
             style="background-color: #FDF2F2; border-color: #F8B4B4; color: #9B1C1C;">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 shrink-0 text-base" aria-hidden="true"></i>
                <div>
                    <span class="font-bold">Perhatian:</span>
                    <span class="opacity-90 ml-1">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- TOOLBAR FILTER & TAMPILAN (Chip Kategori, Segmented Status, Live Search & View Switcher) --}}
    <div class="bg-white rounded-2xl border p-4 shadow-sm space-y-3.5" style="border-color: var(--admin-border-card, #E6E8E4);">
        
        {{-- Baris 1: Chip Kategori --}}
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs font-medium no-scrollbar w-full">
                {{-- Chip Semua --}}
                <a href="{{ route('admin.menus.index', array_filter(['status' => $status !== 'all' ? $status : null, 'search' => $search, 'sort' => $sortBy !== 'default' ? $sortBy : null, 'per_page' => $perPage !== 12 ? $perPage : null])) }}"
                   class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 cursor-pointer font-semibold text-[12.5px] border"
                   style="{{ empty($categoryId) ? 'background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF; border-color: var(--admin-btn-primary-bg, #2F6B4F);' : 'background-color: #FFFFFF; color: var(--admin-text-main, #1B1F1D); border-color: var(--admin-border-card, #E6E8E4);' }}">
                    Semua ({{ $totalCount }})
                </a>

                {{-- Chip Tiap Kategori --}}
                @foreach($categories as $cat)
                    <a href="{{ route('admin.menus.index', array_filter(['category' => $cat->id, 'status' => $status !== 'all' ? $status : null, 'search' => $search, 'sort' => $sortBy !== 'default' ? $sortBy : null, 'per_page' => $perPage !== 12 ? $perPage : null])) }}"
                       class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 cursor-pointer font-semibold text-[12.5px] border"
                       style="{{ $categoryId == $cat->id ? 'background-color: var(--admin-btn-primary-bg, #2F6B4F); color: #FFFFFF; border-color: var(--admin-btn-primary-bg, #2F6B4F);' : 'background-color: #FFFFFF; color: var(--admin-text-main, #1B1F1D); border-color: var(--admin-border-card, #E6E8E4);' }}">
                        {{ $cat->name }} ({{ $cat->menus_count ?? 0 }})
                    </a>
                @endforeach

                {{-- Chip Tambah/Kelola Kategori Cepat --}}
                <button type="button"
                        onclick="openCategoryDrawer()"
                        class="px-3 py-1.5 rounded-xl transition-all shrink-0 text-xs font-semibold flex items-center gap-1.5 border border-dashed hover:bg-[#F0F4F1] cursor-pointer"
                        style="border-color: var(--admin-btn-primary-bg, #2F6B4F); color: var(--admin-btn-primary-bg, #2F6B4F);"
                        title="Tambah atau kelola kategori">
                    <i class="fa-solid fa-plus text-[10px]" aria-hidden="true"></i>
                    <span>Kategori</span>
                </button>
            </div>
        </div>

        {{-- Baris 2: Segmented Status, Live Search & View Switcher --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-3 border-t" style="border-color: var(--admin-border-card, #E6E8E4);">
            
            {{-- Filter Status (Segmented Control: Semua & Terarsip) --}}
            <div class="inline-flex items-center p-1 rounded-xl border bg-[#F7F8F6] text-xs font-semibold self-start"
                 style="border-color: var(--admin-border-card, #E6E8E4);">
                <a href="{{ route('admin.menus.index', array_filter(['category' => $categoryId, 'search' => $search, 'sort' => $sortBy !== 'default' ? $sortBy : null, 'per_page' => $perPage !== 12 ? $perPage : null])) }}"
                   class="px-3 py-1 rounded-lg transition-all text-[12px] {{ $status === 'all' || empty($status) ? 'bg-white shadow-xs font-bold' : 'hover:text-black' }}"
                   style="color: {{ $status === 'all' || empty($status) ? 'var(--admin-text-main, #1B1F1D)' : 'var(--admin-text-sub, #555D58)' }};">
                    Semua
                </a>
                <a href="{{ route('admin.menus.index', array_filter(['category' => $categoryId, 'status' => 'trashed', 'search' => $search, 'sort' => $sortBy !== 'default' ? $sortBy : null, 'per_page' => $perPage !== 12 ? $perPage : null])) }}"
                   class="px-3 py-1 rounded-lg transition-all text-[12px] {{ $status === 'trashed' ? 'bg-white shadow-xs font-bold text-stone-900' : 'hover:text-black' }}"
                   style="color: {{ $status === 'trashed' ? '#1B1F1D' : 'var(--admin-text-sub, #555D58)' }};">
                    <i class="fa-solid fa-box-archive text-[10px] mr-1" aria-hidden="true"></i>
                    Terarsip ({{ $trashedCount }})
                </a>
            </div>

            {{-- Sisi Kanan: Live Search & Switcher Kartu | Daftar --}}
            <div class="flex items-center gap-2.5 w-full md:w-auto">
                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('admin.menus.index') }}" class="relative w-full sm:w-64">
                    @if(!empty($categoryId)) <input type="hidden" name="category" value="{{ $categoryId }}"> @endif
                    @if($status !== 'all') <input type="hidden" name="status" value="{{ $status }}"> @endif
                    @if($sortBy !== 'default') <input type="hidden" name="sort" value="{{ $sortBy }}"> @endif
                    @if($perPage !== 12) <input type="hidden" name="per_page" value="{{ $perPage }}"> @endif

                    <input type="text"
                           name="search"
                           id="menuLiveSearch"
                           value="{{ $search }}"
                           placeholder="Cari nama menu..."
                           oninput="filterMenuItemsClient(this.value)"
                           class="w-full bg-[#F7F8F6] border rounded-xl px-3.5 py-1.5 pl-9 text-xs transition focus:outline-none focus:bg-white"
                           style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs pointer-events-none"
                       style="color: var(--admin-text-sub, #555D58);" aria-hidden="true"></i>

                    @if(!empty($search))
                        <a href="{{ route('admin.menus.index', array_filter(['category' => $categoryId, 'status' => $status !== 'all' ? $status : null, 'sort' => $sortBy !== 'default' ? $sortBy : null, 'per_page' => $perPage !== 12 ? $perPage : null])) }}"
                           class="absolute right-2.5 top-2 text-xs text-gray-400 hover:text-gray-700" title="Hapus pencarian">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </form>

                {{-- View Switcher: Kartu | Daftar --}}
                <div class="flex items-center p-1 rounded-xl border bg-[#F7F8F6] text-xs font-semibold shrink-0"
                     style="border-color: var(--admin-border-card, #E6E8E4);">
                    <button type="button"
                            onclick="switchMenuView('card')"
                            id="btnViewCard"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer bg-white shadow-xs font-bold"
                            style="color: var(--admin-text-main, #1B1F1D);"
                            title="Tampilan Kartu">
                        <i class="fa-solid fa-grip text-xs" aria-hidden="true"></i>
                        <span>Kartu</span>
                    </button>
                    <button type="button"
                            onclick="switchMenuView('list')"
                            id="btnViewList"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer"
                            style="color: var(--admin-text-sub, #555D58);"
                            title="Tampilan Daftar (Tabel)">
                        <i class="fa-solid fa-list text-xs" aria-hidden="true"></i>
                        <span>Daftar</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

    {{-- =========================================================================
         TAMPILAN 1: KARTU (CARD GRID VISUAL DENGAN FOTO 1:1 KOTAK & STATUS CEPAT)
         ========================================================================= --}}
    <div id="menuCardContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5 anim-fade-up">
        @forelse($menus as $menu)
            @php
                $hasImage = !empty($menu->image);
                $imageUrl = $menu->image_url;
                $isOrdered = ($menu->order_items_count ?? 0) > 0;
                $menuStockVal = $menu->stock !== null ? (int) $menu->stock : ($menu->is_available ? 25 : 0);
                if (!$menu->is_available) {
                    $menuStockVal = 0;
                }
            @endphp
            <div class="menu-card-item bg-white border rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between"
                 style="border-color: var(--admin-border-card, #E6E8E4);"
                 data-menu-id="{{ $menu->id }}"
                 data-menu-name="{{ strtolower($menu->name) }}"
                 data-menu-category="{{ strtolower($menu->category->name ?? '') }}"
                 data-menu-price="{{ $menu->price }}">

                <div>
                    {{-- Kotak Foto 1:1 --}}
                    <div class="relative aspect-square w-full bg-[#F7F8F6] border-b overflow-hidden group"
                         style="border-color: var(--admin-border-card, #E6E8E4);">
                        
                        @if($hasImage)
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $menu->name }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center">
                                <div class="w-12 h-12 rounded-xl border flex items-center justify-center mb-2 bg-white"
                                     style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-sub, #555D58);">
                                    <i class="fa-solid fa-camera text-base opacity-40" aria-hidden="true"></i>
                                </div>
                                <span class="text-xs font-medium" style="color: var(--admin-text-sub, #555D58);">Belum ada foto</span>
                                <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                   class="mt-2 text-[11px] font-semibold text-emerald-800 hover:underline inline-flex items-center gap-1">
                                    <i class="fa-solid fa-plus text-[9px]" aria-hidden="true"></i>
                                    <span>Tambah foto</span>
                                </a>
                            </div>
                        @endif

                        {{-- Chip Kategori di Kiri Atas Foto --}}
                        <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-white/95 backdrop-blur-xs border shadow-2xs"
                                  style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                {{ $menu->category->name ?? 'Menu' }}
                            </span>
                            @if($menu->trashed())
                                <span class="px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-stone-900 text-white shadow-2xs flex items-center gap-1">
                                    <i class="fa-solid fa-box-archive text-[9px]" aria-hidden="true"></i>
                                    <span>Terarsip</span>
                                </span>
                            @endif
                        </div>

                        {{-- Badges di Kanan Atas Foto: Bintang Andalan --}}
                        <div class="absolute top-2.5 right-2.5 flex items-center gap-1.5">
                            {{-- Tombol Bintang Andalan Cepat --}}
                            <button type="button"
                                    onclick="toggleFeaturedAjax({{ $menu->id }}, this)"
                                    class="w-7 h-7 rounded-lg bg-white/95 backdrop-blur-xs border flex items-center justify-center transition shadow-2xs hover:scale-105 cursor-pointer btn-press"
                                    style="border-color: var(--admin-border-card, #E6E8E4);"
                                    title="{{ $menu->is_featured || $menu->is_best_seller ? 'Menu andalan (klik untuk lepas)' : 'Tandai sebagai menu andalan beranda' }}">
                                <i class="fa-solid fa-star text-xs star-icon {{ $menu->is_featured || $menu->is_best_seller ? 'text-amber-500' : 'text-gray-300' }}" aria-hidden="true"></i>
                            </button>
                        </div>

                        {{-- Badge Stok di Kanan Bawah Foto --}}
                        <div class="absolute bottom-2.5 right-2.5">
                            <span id="adminGridStockPill-{{ $menu->id }}"
                                  class="px-2.5 py-0.5 rounded-lg text-[10.5px] font-mono font-bold shadow-2xs border backdrop-blur-xs {{ $menuStockVal > 5 ? 'bg-white/95 text-[#1B1F1D] border-[#E6E8E4]' : ($menuStockVal > 0 ? 'bg-amber-50/95 text-amber-800 border-amber-300' : 'bg-rose-600 text-white border-rose-700') }}">
                                Stok: {{ $menuStockVal }}
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Menu (Nama, Harga, Penjelasan) --}}
                    <div class="p-3.5 sm:p-4 space-y-1.5">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-[14.5px] leading-snug line-clamp-1" style="color: var(--admin-text-main, #1B1F1D);" title="{{ $menu->name }}">
                                {{ $menu->name }}
                            </h3>
                        </div>

                        {{-- Harga: Plus Jakarta Sans Bold, tabular-nums --}}
                        <div class="text-[15.5px] font-extrabold tracking-tight tabular-nums" style="color: var(--admin-btn-primary-bg, #2F6B4F);">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </div>

                        {{-- Deskripsi Singkat 2 Baris --}}
                        <p class="text-[12px] leading-relaxed line-clamp-2 min-h-[36px] m-0" style="color: var(--admin-text-sub, #555D58);">
                            {{ $menu->description ?: 'Pilihan racikan istimewa Nucomu Cafe' }}
                        </p>
                    </div>
                </div>

                {{-- Bagian Bawah Kartu: Kontrol Stok Cepat & Tombol Aksi Teks --}}
                <div class="px-3.5 sm:px-4 py-2.5 border-t bg-[#FAFBF9] flex items-center justify-between gap-2"
                     style="border-color: var(--admin-border-card, #E6E8E4);">
                    @if($menu->trashed())
                        <span class="text-[11px] font-medium text-stone-500">Nonaktif di katalog</span>
                        <form action="{{ route('admin.menus.restore', $menu->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 transition shadow-2xs btn-press inline-flex items-center gap-1.5"
                                    title="Pulihkan menu ke katalog">
                                <i class="fa-solid fa-rotate-left text-[11px]" aria-hidden="true"></i>
                                <span>Pulihkan</span>
                            </button>
                        </form>
                    @else
                        {{-- Kontrol Stok Cepat (- / +) --}}
                        <div class="inline-flex items-center gap-1 bg-white border rounded-lg p-0.5 shadow-2xs"
                             style="border-color: var(--admin-border-card, #E6E8E4);"
                             title="Atur stok porsi menu (otomatis sinkron dengan POS Kasir)">
                            <button type="button"
                                    onclick="adjustAdminMenuStock({{ $menu->id }}, { delta: -1 })"
                                    class="w-6 h-6 rounded-md hover:bg-[#F0F4F1] text-xs font-bold flex items-center justify-center transition cursor-pointer"
                                    style="color: var(--admin-text-main, #1B1F1D);">
                                &minus;
                            </button>
                            <input type="number"
                                   id="adminGridStockInput-{{ $menu->id }}"
                                   value="{{ $menuStockVal }}"
                                   min="0"
                                   max="9999"
                                   onchange="adjustAdminMenuStock({{ $menu->id }}, { stock: parseInt(this.value || '0', 10) })"
                                   class="w-9 text-center bg-transparent text-[11.5px] font-mono font-bold focus:outline-none tabular-nums"
                                   style="color: var(--admin-text-main, #1B1F1D);"
                                   aria-label="Stok {{ $menu->name }}">
                            <button type="button"
                                    onclick="adjustAdminMenuStock({{ $menu->id }}, { delta: 1 })"
                                    class="w-6 h-6 rounded-md hover:bg-[#F0F4F1] text-xs font-bold flex items-center justify-center transition cursor-pointer"
                                    style="color: var(--admin-text-main, #1B1F1D);">
                                +
                            </button>
                        </div>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.menus.edit', $menu->id) }}"
                               class="px-2.5 py-1 rounded-lg text-xs font-semibold border bg-white hover:bg-[#F0F4F1] transition shadow-2xs btn-press"
                               style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                               title="Edit rincian menu">
                                Edit
                            </a>

                            <button type="button"
                                    onclick="confirmDeleteMenu({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->order_items_count ?? 0 }})"
                                    class="px-2 py-1 rounded-lg text-xs font-semibold hover:bg-rose-50 text-rose-700 transition cursor-pointer btn-press"
                                    title="Hapus menu">
                                Hapus
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white border rounded-3xl p-8"
                 style="border-color: var(--admin-border-card, #E6E8E4);">
                <div class="w-14 h-14 rounded-2xl border flex items-center justify-center mx-auto mb-3.5 bg-[#F7F8F6]"
                     style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-sub, #555D58);">
                    <i class="fa-solid fa-utensils text-xl" aria-hidden="true"></i>
                </div>
                <h3 class="text-[15px] font-bold" style="color: var(--admin-text-main, #1B1F1D);">
                    Tidak Ada Menu yang Ditemukan
                </h3>
                <p class="text-[12.5px] max-w-md mx-auto mt-1 m-0" style="color: var(--admin-text-sub, #555D58);">
                    @if(!empty($search) || !empty($categoryId) || $status !== 'all')
                        Tidak ada sajian yang sesuai dengan filter atau kata kunci pencarian Anda.
                    @else
                        Katalog sajian kafe Anda masih kosong. Mulai tambahkan racikan menu pertama Anda!
                    @endif
                </p>
                <div class="mt-4 flex items-center justify-center gap-2.5">
                    @if(!empty($search) || !empty($categoryId) || $status !== 'all')
                        <a href="{{ route('admin.menus.index') }}"
                           class="px-4 py-2 rounded-xl text-xs font-semibold border bg-white hover:bg-gray-50 transition"
                           style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                            Reset Semua Filter
                        </a>
                    @endif
                    <a href="{{ route('admin.menus.create') }}"
                       class="px-4 py-2 rounded-xl text-xs font-semibold text-white shadow-xs transition btn-press"
                       style="background-color: var(--admin-btn-primary-bg, #2F6B4F);">
                        <i class="fa-solid fa-plus text-[10px] mr-1.5" aria-hidden="true"></i>
                        <span>Tambah Menu Baru</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- =========================================================================
         TAMPILAN 2: DAFTAR (TABEL TERSTRUKTUR DENGAN SORTABLE COLUMN & RESPONSIF)
         ========================================================================= --}}
    <div id="menuListContainer" class="hidden bg-white border rounded-2xl overflow-hidden shadow-xs anim-fade-up"
         style="border-color: var(--admin-border-card, #E6E8E4);">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F7F8F6] uppercase text-[10.5px] font-semibold tracking-wider border-b"
                       style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-sub, #555D58);">
                    <tr>
                        <th class="px-5 py-3.5">Menu</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <span>Harga</span>
                                <div class="flex flex-col text-[8px] leading-tight">
                                    <a href="{{ route('admin.menus.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}"
                                       class="hover:text-black {{ $sortBy === 'price_asc' ? 'text-emerald-700 font-bold' : '' }}" title="Harga Termurah">▲</a>
                                    <a href="{{ route('admin.menus.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}"
                                       class="hover:text-black {{ $sortBy === 'price_desc' ? 'text-emerald-700 font-bold' : '' }}" title="Harga Termahal">▼</a>
                                </div>
                            </div>
                        </th>
                        <th class="px-4 py-3.5 text-center">Andalan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--admin-border-card, #E6E8E4);">
                    @forelse($menus as $menu)
                        @php
                            $hasImage = !empty($menu->image);
                            $imageUrl = $menu->image_url;
                        @endphp
                        <tr class="menu-list-row hover:bg-[#FAFBF9] transition"
                            data-menu-name="{{ strtolower($menu->name) }}"
                            data-menu-category="{{ strtolower($menu->category->name ?? '') }}">
                            
                            {{-- Kolom Foto & Nama --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl border bg-[#F7F8F6] overflow-hidden shrink-0"
                                         style="border-color: var(--admin-border-card, #E6E8E4);">
                                        @if($hasImage)
                                            <img src="{{ $imageUrl }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fa-solid fa-utensils text-xs" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                               class="font-bold text-[13.5px] hover:underline truncate"
                                               style="color: var(--admin-text-main, #1B1F1D);">
                                                {{ $menu->name }}
                                            </a>
                                            @if($menu->trashed())
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-stone-900 text-white shrink-0">
                                                    Terarsip
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11.5px] truncate max-w-xs m-0 mt-0.5" style="color: var(--admin-text-sub, #555D58);">
                                            {{ $menu->description ?: 'Nucomu Cafe' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border bg-white"
                                      style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                                    {{ $menu->category->name ?? 'Menu' }}
                                </span>
                            </td>

                            {{-- Harga --}}
                            <td class="px-4 py-3.5 font-bold tabular-nums text-[13.5px]" style="color: var(--admin-btn-primary-bg, #2F6B4F);">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>

                            {{-- Menu Andalan (Bintang) --}}
                            <td class="px-4 py-3.5 text-center">
                                <button type="button"
                                        onclick="toggleFeaturedAjax({{ $menu->id }}, this)"
                                        class="w-7 h-7 rounded-lg border inline-flex items-center justify-center transition hover:bg-amber-50 cursor-pointer btn-press"
                                        style="border-color: var(--admin-border-card, #E6E8E4);"
                                        title="{{ $menu->is_featured || $menu->is_best_seller ? 'Menu andalan' : 'Bukan andalan' }}">
                                    <i class="fa-solid fa-star text-xs star-icon {{ $menu->is_featured || $menu->is_best_seller ? 'text-amber-500' : 'text-gray-300' }}" aria-hidden="true"></i>
                                </button>
                            </td>

                            {{-- Tombol Aksi --}}
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($menu->trashed())
                                        <form action="{{ route('admin.menus.restore', $menu->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 transition shadow-2xs btn-press inline-flex items-center gap-1"
                                                    title="Pulihkan menu ke katalog">
                                                <i class="fa-solid fa-rotate-left text-[11px]" aria-hidden="true"></i>
                                                <span>Pulihkan</span>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                           class="px-2.5 py-1 rounded-lg text-xs font-semibold border bg-white hover:bg-[#F0F4F1] transition shadow-2xs btn-press"
                                           style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);"
                                           title="Edit sajian">
                                            Edit
                                        </a>
                                        <button type="button"
                                                onclick="confirmDeleteMenu({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->order_items_count ?? 0 }})"
                                                class="px-2 py-1 rounded-lg text-xs font-semibold hover:bg-rose-50 text-rose-700 transition cursor-pointer btn-press"
                                                title="Hapus sajian">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center" style="color: var(--admin-text-sub, #555D58);">
                                Tidak ada menu yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- =========================================================================
         PAGINATION BAHASA INDONESIA & PEMILIH JUMLAH PER HALAMAN
         ========================================================================= --}}
    @if($menus->total() > 0)
        <div class="p-4 bg-white border rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-3 text-xs shadow-xs"
             style="border-color: var(--admin-border-card, #E6E8E4);">
            
            {{-- Info Halaman Bahasa Indonesia --}}
            <div class="text-[12.5px] font-medium" style="color: var(--admin-text-sub, #555D58);">
                Menampilkan <span class="font-bold" style="color: var(--admin-text-main, #1B1F1D);">{{ $menus->firstItem() ?? 0 }}</span>
                sampai <span class="font-bold" style="color: var(--admin-text-main, #1B1F1D);">{{ $menus->lastItem() ?? 0 }}</span>
                dari <span class="font-bold" style="color: var(--admin-text-main, #1B1F1D);">{{ $menus->total() }}</span> menu
            </div>

            {{-- Pemilih Per Halaman & Tombol Navigasi --}}
            <div class="flex items-center gap-3">
                {{-- Opsi Per Halaman --}}
                <div class="flex items-center gap-1.5 text-xs">
                    <span style="color: var(--admin-text-sub, #555D58);">Tampilkan:</span>
                    @foreach([12, 24, 48] as $size)
                        <a href="{{ route('admin.menus.index', array_merge(request()->query(), ['per_page' => $size, 'page' => 1])) }}"
                           class="px-2 py-1 rounded-lg font-semibold border transition text-[11.5px] {{ $perPage == $size ? 'bg-[#2F6B4F] text-white border-[#2F6B4F]' : 'bg-white hover:bg-gray-100 text-[#1B1F1D]' }}"
                           style="{{ $perPage != $size ? 'border-color: var(--admin-border-card, #E6E8E4);' : '' }}">
                            {{ $size }}
                        </a>
                    @endforeach
                </div>

                {{-- Tombol Previous & Next --}}
                <div class="flex items-center gap-1">
                    @if($menus->onFirstPage())
                        <span class="px-2.5 py-1 rounded-lg border bg-gray-50 text-gray-300 text-xs cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </span>
                    @else
                        <a href="{{ $menus->previousPageUrl() }}"
                           class="px-2.5 py-1 rounded-lg border bg-white hover:bg-[#F0F4F1] text-xs font-semibold transition"
                           style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </a>
                    @endif

                    <span class="px-2 text-xs font-bold" style="color: var(--admin-text-main, #1B1F1D);">
                        {{ $menus->currentPage() }} / {{ $menus->lastPage() }}
                    </span>

                    @if($menus->hasMorePages())
                        <a href="{{ $menus->nextPageUrl() }}"
                           class="px-2.5 py-1 rounded-lg border bg-white hover:bg-[#F0F4F1] text-xs font-semibold transition"
                           style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    @else
                        <span class="px-2.5 py-1 rounded-lg border bg-gray-50 text-gray-300 text-xs cursor-not-allowed">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </span>
                    @endif
                </div>
            </div>

        </div>
    @endif

</div>

{{-- =========================================================================
     MODAL DIALOG PENGAMAN HAPUS MENU (Aman dari Masalah Foreign Key Riwayat Pesanan)
     ========================================================================= --}}
<div id="modalDeleteMenu" class="hidden fixed inset-0 z-50 bg-black/45 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl border shadow-2xl w-full max-w-md p-6 sm:p-7 space-y-4 anim-fade-up"
         style="border-color: var(--admin-border-card, #E6E8E4);">
        
        <div class="flex items-start gap-3">
            <div id="deleteModalIcon" class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 bg-rose-50 text-rose-600">
                <i class="fa-solid fa-trash-can text-sm" aria-hidden="true"></i>
            </div>
            <div>
                <h3 id="deleteModalTitle" class="text-[15px] font-bold leading-snug" style="color: var(--admin-text-main, #1B1F1D);">
                    Hapus Menu
                </h3>
                <p id="deleteModalDesc" class="text-[12.5px] leading-relaxed mt-1" style="color: var(--admin-text-sub, #555D58);">
                    Konfirmasi tindakan untuk menu ini.
                </p>
            </div>
        </div>

        {{-- Opsi Aksi Hapus / Arsipkan / Tandai Habis --}}
        <div id="deleteModalActions" class="pt-2 flex flex-col gap-2">
            {{-- Tombol-tombol diinjeksi via JavaScript sesuai apakah menu punya pesanan --}}
        </div>

        <div class="pt-2 border-t flex justify-end" style="border-color: var(--admin-border-card, #E6E8E4);">
            <button type="button"
                    onclick="closeDeleteMenuModal()"
                    class="px-4 py-2 rounded-xl text-xs font-semibold border bg-white hover:bg-[#F0F4F1] transition"
                    style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                Batal
            </button>
        </div>

    </div>
</div>

{{-- Form Hapus Tersembunyi --}}
<form id="formDeleteMenuHidden" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
    <input type="hidden" name="force" id="inputDeleteForce" value="0">
    <input type="hidden" name="action" id="inputDeleteAction" value="">
</form>

{{-- =========================================================================
     LACI (DRAWER) KELOLA KATEGORI MENU (Slide-in Drawer Sisi Kanan)
     ========================================================================= --}}
<div id="categoryDrawerOverlay"
     class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-xs transition-opacity duration-300"
     onclick="closeCategoryDrawer()">
</div>

<div id="categoryDrawer"
     class="fixed top-0 right-0 z-50 h-full w-full max-w-lg bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col">
    
    {{-- Header Drawer --}}
    <div class="p-5 sm:p-6 border-b flex items-center justify-between" style="border-color: var(--admin-border-card, #E6E8E4);">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                 style="background-color: var(--admin-bg-sidebar-active, #E6F0EA); color: var(--admin-btn-primary-bg, #2F6B4F);">
                <i class="fa-solid fa-tags text-xs" aria-hidden="true"></i>
            </div>
            <div>
                <h3 class="text-[15px] font-bold leading-snug" style="color: var(--admin-text-main, #1B1F1D); font-family: var(--font-heading);">
                    Kelola Kategori Menu
                </h3>
                <p class="text-[11.5px] m-0" style="color: var(--admin-text-sub, #555D58);">
                    Atur kelompok sajian dan urutan tab filter di katalog web.
                </p>
            </div>
        </div>
        <button type="button"
                onclick="closeCategoryDrawer()"
                class="w-8 h-8 rounded-xl flex items-center justify-center hover:bg-[#F0F4F1] transition text-[#555D58] hover:text-[#1B1F1D]">
            <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
        </button>
    </div>

    {{-- Body Drawer: Form Tambah & Daftar Kategori --}}
    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-6">
        
        {{-- 1. Form Tambah Kategori Cepat (Paling Atas, TANPA Double Plus) --}}
        <div class="p-4 rounded-2xl border bg-[#F7F8F6] space-y-3" style="border-color: var(--admin-border-card, #E6E8E4);">
            <div class="font-bold text-xs flex items-center gap-1.5" style="color: var(--admin-text-main, #1B1F1D);">
                <i class="fa-solid fa-plus text-[10px]" style="color: var(--admin-btn-primary-bg, #2F6B4F);" aria-hidden="true"></i>
                <span>Tambah Kategori Baru</span>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-2.5">
                @csrf
                <div>
                    <input type="text"
                           name="name"
                           required
                           placeholder="Nama kategori (misal: Mocktail, Pastry, Artisan Tea)"
                           class="w-full bg-white border rounded-xl px-3.5 py-2 text-xs font-medium transition focus:outline-none focus:border-[#2F6B4F]"
                           style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition shadow-xs cursor-pointer btn-press"
                            style="background-color: var(--admin-btn-primary-bg, #2F6B4F);">
                        <i class="fa-solid fa-plus text-[10px] mr-1" aria-hidden="true"></i>
                        <span>Tambah Kategori</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- 2. Daftar Kategori yang Ada --}}
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs font-bold px-1" style="color: var(--admin-text-sub, #555D58);">
                <span>Daftar Kategori ({{ $categories->count() }})</span>
                <span class="text-[11px] font-normal">Gunakan panah untuk ubah urutan</span>
            </div>

            <div id="categoryDrawerList" class="space-y-2">
                @foreach($categories as $index => $cat)
                    <div class="category-item-row p-3 rounded-xl border bg-white flex items-center justify-between gap-3 shadow-2xs hover:border-[#2F6B4F] transition"
                         style="border-color: var(--admin-border-card, #E6E8E4);"
                         data-cat-id="{{ $cat->id }}"
                         data-cat-name="{{ $cat->name }}"
                         data-cat-count="{{ $cat->menus_count ?? 0 }}">
                        
                        {{-- Kiri: Urutan & Nama Kategori --}}
                        <div class="flex items-center gap-2.5 min-w-0">
                            {{-- Tombol Panah Atas & Bawah --}}
                            <div class="flex flex-col gap-0.5 shrink-0">
                                <button type="button"
                                        onclick="moveCategoryOrder({{ $cat->id }}, 'up')"
                                        class="w-5 h-4 rounded flex items-center justify-center hover:bg-gray-100 text-gray-500 hover:text-black {{ $index === 0 ? 'opacity-30 cursor-not-allowed' : '' }}"
                                        title="Pindah ke atas">
                                    <i class="fa-solid fa-chevron-up text-[9px]" aria-hidden="true"></i>
                                </button>
                                <button type="button"
                                        onclick="moveCategoryOrder({{ $cat->id }}, 'down')"
                                        class="w-5 h-4 rounded flex items-center justify-center hover:bg-gray-100 text-gray-500 hover:text-black {{ $index === count($categories) - 1 ? 'opacity-30 cursor-not-allowed' : '' }}"
                                        title="Pindah ke bawah">
                                    <i class="fa-solid fa-chevron-down text-[9px]" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-[13px] truncate" style="color: var(--admin-text-main, #1B1F1D);">
                                        {{ $cat->name }}
                                    </span>
                                    @if(!$cat->is_active)
                                        <span class="px-1.5 py-0.2 rounded text-[9.5px] font-semibold bg-gray-100 text-gray-600">
                                            Disembunyikan
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[11.5px] text-gray-500 flex items-center gap-1.5 mt-0.5">
                                    <span>{{ $cat->menus_count ?? 0 }} menu</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Aksi (Toggle Aktif, Edit Nama, Hapus) --}}
                        <div class="flex items-center gap-1.5 shrink-0">
                            {{-- Toggle Aktif --}}
                            <form action="{{ route('admin.categories.toggle', $cat->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="w-7 h-7 rounded-lg border flex items-center justify-center hover:bg-gray-50 text-gray-600 transition"
                                        style="border-color: var(--admin-border-card, #E6E8E4);"
                                        title="{{ $cat->is_active ? 'Tampil di website (klik untuk sembunyikan)' : 'Disembunyikan (klik untuk tampilkan)' }}">
                                    <i class="fa-solid {{ $cat->is_active ? 'fa-eye text-emerald-700' : 'fa-eye-slash text-gray-400' }} text-xs" aria-hidden="true"></i>
                                </button>
                            </form>

                            {{-- Edit Nama Modal / Inline --}}
                            <button type="button"
                                    onclick="editCategoryNameModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                    class="w-7 h-7 rounded-lg border flex items-center justify-center hover:bg-gray-50 text-gray-700 transition"
                                    style="border-color: var(--admin-border-card, #E6E8E4);"
                                    title="Ubah nama kategori">
                                <i class="fa-solid fa-pen text-[10px]" aria-hidden="true"></i>
                            </button>

                            {{-- Hapus Kategori: Peringatan Jika Masih Memuat Menu --}}
                            @if(($cat->menus_count ?? 0) > 0)
                                <button type="button"
                                        onclick="showCategoryCannotDeleteNotice('{{ addslashes($cat->name) }}', {{ $cat->menus_count }}, {{ $cat->id }})"
                                        class="w-7 h-7 rounded-lg border flex items-center justify-center hover:bg-amber-50 text-amber-700 transition"
                                        style="border-color: var(--admin-border-card, #E6E8E4);"
                                        title="Kategori ini berisi menu (tidak dapat dihapus)">
                                    <i class="fa-solid fa-lock text-[10px]" aria-hidden="true"></i>
                                </button>
                            @else
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-7 h-7 rounded-lg border flex items-center justify-center hover:bg-rose-50 text-rose-700 transition"
                                            style="border-color: var(--admin-border-card, #E6E8E4);"
                                            title="Hapus kategori kosong">
                                        <i class="fa-solid fa-trash-can text-[10px]" aria-hidden="true"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Footer Drawer --}}
    <div class="p-4 border-t bg-[#FAFBF9] flex items-center justify-between text-xs" style="border-color: var(--admin-border-card, #E6E8E4);">
        <span style="color: var(--admin-text-sub, #555D58);">
            Perubahan kategori otomatis langsung aktif di katalog website.
        </span>
        <button type="button"
                onclick="closeCategoryDrawer()"
                class="px-4 py-2 rounded-xl text-xs font-semibold border bg-white hover:bg-gray-50 transition"
                style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
            Tutup
        </button>
    </div>

</div>

{{-- Modal Ubah Nama Kategori --}}
<div id="modalEditCategory" class="hidden fixed inset-0 z-50 bg-black/45 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl border shadow-2xl w-full max-w-sm p-6 space-y-4 anim-fade-up"
         style="border-color: var(--admin-border-card, #E6E8E4);">
        <div class="flex items-center justify-between pb-2 border-b" style="border-color: var(--admin-border-card, #E6E8E4);">
            <h3 class="text-sm font-bold" style="color: var(--admin-text-main, #1B1F1D);">Ubah Nama Kategori</h3>
            <button type="button" onclick="closeEditCategoryModal()" class="text-gray-400 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <form id="formEditCategory" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_cat_name" class="block text-xs font-semibold mb-1" style="color: var(--admin-text-main, #1B1F1D);">
                    Nama Kategori Baru
                </label>
                <input type="text"
                       name="name"
                       id="edit_cat_name"
                       required
                       class="w-full bg-[#F7F8F6] border rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:bg-white focus:border-[#2F6B4F]"
                       style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t" style="border-color: var(--admin-border-card, #E6E8E4);">
                <button type="button" onclick="closeEditCategoryModal()"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-semibold border bg-white hover:bg-gray-50 transition"
                        style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-1.5 rounded-xl text-xs font-semibold text-white transition shadow-xs btn-press"
                        style="background-color: var(--admin-btn-primary-bg, #2F6B4F);">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Dialog Penjelasan: Kategori Tidak Dapat Dihapus Karena Memuat Menu --}}
<div id="modalCategoryCannotDelete" class="hidden fixed inset-0 z-50 bg-black/45 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl border shadow-2xl w-full max-w-md p-6 space-y-4 anim-fade-up"
         style="border-color: var(--admin-border-card, #E6E8E4);">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 bg-amber-50 text-amber-700">
                <i class="fa-solid fa-lock text-base" aria-hidden="true"></i>
            </div>
            <div>
                <h3 class="text-[15px] font-bold" style="color: var(--admin-text-main, #1B1F1D);">
                    Kategori Tidak Dapat Dihapus
                </h3>
                <p id="categoryCannotDeleteText" class="text-[12.5px] leading-relaxed mt-1" style="color: var(--admin-text-sub, #555D58);">
                    Kategori ini masih berisi menu.
                </p>
            </div>
        </div>

        <div class="pt-3 border-t flex items-center justify-between" style="border-color: var(--admin-border-card, #E6E8E4);">
            <a id="btnFilterCategoryMenus"
               href="#"
               class="text-xs font-bold text-emerald-800 hover:underline flex items-center gap-1.5">
                <span>Lihat menu kategori ini</span>
                <i class="fa-solid fa-arrow-right text-[10px]" aria-hidden="true"></i>
            </a>
            <button type="button"
                    onclick="document.getElementById('modalCategoryCannotDelete').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold border bg-white hover:bg-gray-50 transition"
                    style="border-color: var(--admin-border-card, #E6E8E4); color: var(--admin-text-main, #1B1F1D);">
                Mengerti
            </button>
        </div>
    </div>
</div>

{{-- =========================================================================
     JAVASCRIPT INTERAKTIF (DRAWER, AJAX SWITCH, VIEW SWITCHER & MODAL SAFETY)
     ========================================================================= --}}
<script>
    // 1. View Switcher: Kartu | Daftar (Persisted in localStorage)
    function switchMenuView(viewMode) {
        const cardContainer = document.getElementById('menuCardContainer');
        const listContainer = document.getElementById('menuListContainer');
        const btnCard = document.getElementById('btnViewCard');
        const btnList = document.getElementById('btnViewList');

        if (viewMode === 'list') {
            if (cardContainer) cardContainer.classList.add('hidden');
            if (listContainer) listContainer.classList.remove('hidden');

            btnList.classList.add('bg-white', 'shadow-xs', 'font-bold');
            btnList.style.color = 'var(--admin-text-main, #1B1F1D)';
            btnCard.classList.remove('bg-white', 'shadow-xs', 'font-bold');
            btnCard.style.color = 'var(--admin-text-sub, #555D58)';

            localStorage.setItem('nucomu_admin_menu_view', 'list');
        } else {
            if (cardContainer) cardContainer.classList.remove('hidden');
            if (listContainer) listContainer.classList.add('hidden');

            btnCard.classList.add('bg-white', 'shadow-xs', 'font-bold');
            btnCard.style.color = 'var(--admin-text-main, #1B1F1D)';
            btnList.classList.remove('bg-white', 'shadow-xs', 'font-bold');
            btnList.style.color = 'var(--admin-text-sub, #555D58)';

            localStorage.setItem('nucomu_admin_menu_view', 'card');
        }
    }

    // Restore view preference
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('nucomu_admin_menu_view');
        if (savedView === 'list') {
            switchMenuView('list');
        }
    });

    // 2. Client-side Filter Search
    function filterMenuItemsClient(query) {
        const term = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.menu-card-item');
        const rows = document.querySelectorAll('.menu-list-row');

        cards.forEach(card => {
            const name = card.getAttribute('data-menu-name') || '';
            const cat = card.getAttribute('data-menu-category') || '';
            if (!term || name.includes(term) || cat.includes(term)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });

        rows.forEach(row => {
            const name = row.getAttribute('data-menu-name') || '';
            const cat = row.getAttribute('data-menu-category') || '';
            if (!term || name.includes(term) || cat.includes(term)) {
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

    // 3. Quick AJAX Featured Toggle (Menu Andalan Beranda)
    async function toggleFeaturedAjax(menuId, buttonEl) {
        const icon = buttonEl.querySelector('.star-icon');
        const isCurrentlyActive = icon && icon.classList.contains('text-amber-500');

        if (icon) {
            if (isCurrentlyActive) {
                icon.classList.remove('text-amber-500');
                icon.classList.add('text-gray-300');
            } else {
                icon.classList.add('text-amber-500');
                icon.classList.remove('text-gray-300');
            }
        }

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/admin/menu/${menuId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });
            if (!res.ok) {
                // Revert
                if (icon) {
                    if (isCurrentlyActive) {
                        icon.classList.add('text-amber-500');
                        icon.classList.remove('text-gray-300');
                    } else {
                        icon.classList.remove('text-amber-500');
                        icon.classList.add('text-gray-300');
                    }
                }
            }
        } catch (e) {
            if (icon) {
                if (isCurrentlyActive) {
                    icon.classList.add('text-amber-500');
                    icon.classList.remove('text-gray-300');
                } else {
                    icon.classList.remove('text-amber-500');
                    icon.classList.add('text-gray-300');
                }
            }
        }
    }

    // 4. Delete Menu Safety Dialog
    function confirmDeleteMenu(menuId, menuName, orderCount) {
        const modal = document.getElementById('modalDeleteMenu');
        const title = document.getElementById('deleteModalTitle');
        const desc = document.getElementById('deleteModalDesc');
        const actionsContainer = document.getElementById('deleteModalActions');
        const formHidden = document.getElementById('formDeleteMenuHidden');
        const inputForce = document.getElementById('inputDeleteForce');
        const inputAction = document.getElementById('inputDeleteAction');

        formHidden.action = `/admin/menu/${menuId}`;

        if (orderCount > 0) {
            title.textContent = 'Menu Memiliki Riwayat Pesanan';
            desc.innerHTML = `Menu <strong>"${menuName}"</strong> sudah pernah dipesan pelanggan (<strong>${orderCount} transaksi</strong>). Agar riwayat transaksi pelanggan dan pembukuan kafe tetap rapi dan akurat, menu ini tidak dapat dihapus permanen. Menu dapat diarsipkan (disembunyikan dari katalog website):`;

            actionsContainer.innerHTML = `
                <button type="button" onclick="submitDeleteMenuAction('archive')"
                        class="w-full px-4 py-2.5 rounded-xl text-xs font-semibold bg-[#2F6B4F] hover:bg-[#25553E] text-white transition flex items-center justify-center gap-2 cursor-pointer btn-press">
                    <i class="fa-solid fa-box-archive text-xs"></i>
                    <span>Sembunyikan dari Website (Arsipkan)</span>
                </button>
            `;
        } else {
            title.textContent = `Hapus Menu "${menuName}"?`;
            desc.innerHTML = `Menu ini belum pernah dipesan oleh pelanggan. Anda dapat menghapusnya secara permanen dari sistem. Tindakan ini tidak dapat dibatalkan.`;

            actionsContainer.innerHTML = `
                <button type="button" onclick="submitDeleteMenuAction('force_delete')"
                        class="w-full px-4 py-2.5 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white transition flex items-center justify-center gap-2 cursor-pointer btn-press">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                    <span>Hapus Menu Permanen</span>
                </button>
            `;
        }

        modal.classList.remove('hidden');
    }

    function submitDeleteMenuAction(actionType) {
        const formHidden = document.getElementById('formDeleteMenuHidden');
        const inputForce = document.getElementById('inputDeleteForce');
        const inputAction = document.getElementById('inputDeleteAction');

        if (actionType === 'archive') {
            inputForce.value = '0';
            inputAction.value = 'archive';
        } else if (actionType === 'force_delete') {
            inputForce.value = '1';
            inputAction.value = '';
        }

        formHidden.submit();
    }

    function closeDeleteMenuModal() {
        document.getElementById('modalDeleteMenu').classList.add('hidden');
    }

    // 6. Category Drawer Controls
    function openCategoryDrawer() {
        const drawer = document.getElementById('categoryDrawer');
        const overlay = document.getElementById('categoryDrawerOverlay');
        if (overlay) overlay.classList.remove('hidden');
        if (drawer) {
            drawer.classList.remove('translate-x-full');
        }
    }

    function closeCategoryDrawer() {
        const drawer = document.getElementById('categoryDrawer');
        const overlay = document.getElementById('categoryDrawerOverlay');
        if (drawer) {
            drawer.classList.add('translate-x-full');
        }
        if (overlay) overlay.classList.add('hidden');
    }

    // 8. Reorder Category via Arrow Buttons
    async function moveCategoryOrder(categoryId, direction) {
        const rows = Array.from(document.querySelectorAll('.category-item-row'));
        const currentIndex = rows.findIndex(r => r.getAttribute('data-cat-id') == categoryId);
        if (currentIndex === -1) return;

        const targetIndex = direction === 'up' ? currentIndex - 1 : currentIndex + 1;
        if (targetIndex < 0 || targetIndex >= rows.length) return;

        // Swap in DOM
        const currentEl = rows[currentIndex];
        const targetEl = rows[targetIndex];
        const list = document.getElementById('categoryDrawerList');

        if (direction === 'up') {
            list.insertBefore(currentEl, targetEl);
        } else {
            list.insertBefore(targetEl, currentEl);
        }

        // Collect new order IDs
        const newOrder = Array.from(document.querySelectorAll('.category-item-row'))
            .map(r => parseInt(r.getAttribute('data-cat-id')));

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            await fetch("{{ route('admin.categories.reorder') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ order: newOrder })
            });
        } catch (e) {
            console.error('Gagal menyimpan urutan:', e);
        }
    }

    // 9. Edit Category Name Modal
    function editCategoryNameModal(catId, catName) {
        const modal = document.getElementById('modalEditCategory');
        const input = document.getElementById('edit_cat_name');
        const form = document.getElementById('formEditCategory');

        form.action = `/admin/kategori/${catId}`;
        input.value = catName;
        modal.classList.remove('hidden');
        input.focus();
    }

    function closeEditCategoryModal() {
        document.getElementById('modalEditCategory').classList.add('hidden');
    }

    // 10. Cannot Delete Category Notice
    function showCategoryCannotDeleteNotice(catName, menuCount, catId) {
        const modal = document.getElementById('modalCategoryCannotDelete');
        const text = document.getElementById('categoryCannotDeleteText');
        const btnFilter = document.getElementById('btnFilterCategoryMenus');

        text.innerHTML = `Kategori <strong>"${catName}"</strong> masih memuat <strong>${menuCount} menu</strong>. Demi keamanan katalog, silakan pindahkan atau hapus menu-menunya terlebih dahulu sebelum menghapus kategori ini.`;
        btnFilter.href = `{{ route('admin.menus.index') }}?category=${catId}`;

        modal.classList.remove('hidden');
    }
</script>
@endsection
