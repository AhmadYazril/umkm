@extends('layouts.admin')
@section('title', 'Galeri Foto & Feed — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-2xs anim-fade-up">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any() || session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold space-y-2 shadow-2xs anim-fade-up">
            <div class="flex items-center gap-2 font-bold">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>{{ session('error') ? 'Terjadi kesalahan unggah:' : 'Terdapat kesalahan pada formulir:' }}</span>
            </div>
            @if(session('error'))
                <p class="leading-relaxed pl-5 text-[12.5px] font-medium">{{ session('error') }}</p>
            @endif
            @if($errors->any())
                <ul class="list-disc list-inside space-y-0.5 text-rose-800 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    {{-- Top Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Modul 5 &bull; Galeri Visual &amp; Live Feed</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Galeri Foto Kafe
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kelola koleksi foto suasana, seduhan kopi, interior, dan momen Nucomu yang tampil di beranda dan halaman galeri web.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Toggle Button to Open/Close Upload Form --}}
            <button type="button" onclick="toggleUploadDrawer()" id="btnToggleUpload"
                    class="px-4 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition-all shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-plus text-xs" id="iconToggleUpload"></i>
                <span id="textToggleUpload">Unggah Foto Baru</span>
            </button>

            <a href="{{ route('home') }}#gallery" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Buka Story Feed di Website Pelanggan">
                <span>Feed Beranda</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>

            <a href="{{ route('gallery') }}" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Buka Halaman Galeri Lengkap">
                <span>Halaman Galeri</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- Stats Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 anim-fade-up">
        <div class="p-5 rounded-2xl bg-pure-white border border-hairline shadow-card flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-muted-text">Total Foto Galeri</p>
                <p class="text-2xl font-black text-ink-black mt-1">{{ $totalCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                <i class="fa-solid fa-images text-base"></i>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-pure-white border border-hairline shadow-card flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Tampil di Web (Aktif)</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $activeCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-circle-check text-base"></i>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-pure-white border border-hairline shadow-card flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-muted-text">Disembunyikan</p>
                <p class="text-2xl font-black text-charcoal mt-1">{{ $inactiveCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-muted-text">
                <i class="fa-solid fa-eye-slash text-base"></i>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         COLLAPSIBLE UPLOAD FORM (Minimalist Studio)
         ========================================================================= --}}
    <div id="uploadDrawer" class="{{ $errors->any() ? '' : 'hidden' }} bg-pure-white border border-hairline rounded-3xl p-6 sm:p-8 shadow-card anim-fade-up">
        <div class="flex items-center justify-between pb-4 border-b border-hairline mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-ink-black text-white flex items-center justify-center shadow-xs">
                    <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-ink-black">Unggah Foto Galeri Baru</h2>
                    <p class="text-xs text-muted-text">Setiap foto wajib memiliki judul dan file gambar sesuai panduan.</p>
                </div>
            </div>
            <button type="button" onclick="toggleUploadDrawer()" class="p-2 text-muted-text hover:text-ink-black rounded-xl hover:bg-warm-stone transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        @php
            $galleryLimits = \App\Support\FileUploadHelper::getEffectiveLimit(2048 * 1024, 'foto galeri');
        @endphp

        {{-- Guide Banner --}}
        <div class="mb-6 p-4 rounded-2xl bg-warm-ivory border border-hairline flex flex-col md:flex-row items-start md:items-center justify-between gap-3 text-xs text-charcoal">
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-circle-info text-xs"></i>
                </div>
                <div class="space-y-0.5">
                    <p class="font-bold text-ink-black">Panduan Ukuran &amp; Format Foto</p>
                    <p class="text-[11px] text-muted-text">
                        Rekomendasi rasio: <strong class="text-charcoal font-semibold">1:1 (1080 &times; 1080 px)</strong> untuk foto kotak atau <strong class="text-charcoal font-semibold">4:3 (1200 &times; 900 px)</strong> untuk feed landscape. Format: <strong>JPG, PNG, WebP</strong>. Maksimal <strong>{{ $galleryLimits['formatted'] ?? $galleryLimits['limit_formatted'] ?? '2 MB' }}</strong>.
                    </p>
                    @if(!empty($galleryLimits['is_throttled'] ?? $galleryLimits['is_limited_by_server']))
                        <p class="text-[10.5px] text-amber-800 font-medium pt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> Batas server PHP saat ini ({{ $galleryLimits['server_limit_formatted'] ?? '2 MB' }}) lebih kecil dari batas aplikasi (2 MB).
                        </p>
                    @endif
                </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-white border border-hairline text-[10px] font-mono font-bold text-charcoal shrink-0 self-end md:self-auto">
                Maks. {{ $galleryLimits['formatted'] ?? $galleryLimits['limit_formatted'] ?? '2 MB' }}
            </span>
        </div>

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- Left: Upload Dropzone & Live Preview (5 Cols) --}}
                <div class="lg:col-span-5 space-y-2">
                    <label class="block text-xs font-bold text-charcoal">
                        Foto Gambar <span class="text-rose-600">*</span>
                    </label>

                    <div id="dropzoneCreate" 
                         onclick="document.getElementById('createImageInput').click()"
                         class="relative w-full aspect-square sm:aspect-[4/3] rounded-2xl border-2 border-dashed border-hairline hover:border-ink-black bg-warm-ivory flex flex-col items-center justify-center p-6 text-center cursor-pointer transition-all group overflow-hidden">
                        
                        <input type="file" name="image" id="createImageInput" required accept="image/jpeg,image/png,image/webp"
                               data-max-bytes="{{ $galleryLimits['effective_bytes'] ?? $galleryLimits['limit_bytes'] ?? 2097152 }}"
                               data-allowed-ext="jpg,jpeg,png,webp"
                               data-feedback-target="#createImageFeedback"
                               class="hidden" onchange="previewCreateImage(event)">

                        {{-- Placeholder --}}
                        <div id="createDropzonePlaceholder" class="space-y-2 pointer-events-none transition-transform group-hover:scale-105">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-hairline text-ink-black flex items-center justify-center mx-auto shadow-2xs">
                                <i class="fa-solid fa-camera text-base"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-ink-black block">Klik untuk memilih foto</span>
                                <span class="text-[11px] text-muted-text block mt-0.5">Rasio 1:1 atau 4:3 &bull; Maks. {{ $galleryLimits['formatted'] ?? $galleryLimits['limit_formatted'] ?? '2 MB' }}</span>
                            </div>
                        </div>

                        {{-- Instant Preview --}}
                        <img id="createImagePreview" src="" alt="Pratinjau" class="hidden absolute inset-0 w-full h-full object-cover">
                        <div id="createImageOverlay" class="hidden absolute inset-0 bg-ink-black/40 backdrop-blur-xs flex items-center justify-center text-white text-xs font-bold gap-2">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Ganti Foto</span>
                        </div>
                    </div>

                    {{-- Instant feedback container --}}
                    <div id="createImageFeedback" class="mt-1 text-left"></div>
                </div>

                {{-- Right: Inputs (7 Cols) --}}
                <div class="lg:col-span-7 space-y-4">
                    
                    {{-- Judul Foto --}}
                    <div>
                        <label for="create_caption" class="block text-xs font-bold text-charcoal mb-1.5">
                            Judul Foto / Caption <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="caption" id="create_caption" required 
                               value="{{ old('caption') }}"
                               placeholder="Contoh: Suasana Meja Bar Nucomu Sore Hari"
                               class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
                        <p class="text-[10px] text-muted-text mt-1">Wajib diisi. Tampil sebagai judul utama di web dan story feed.</p>
                    </div>

                    {{-- Kategori & Urutan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="create_category" class="block text-xs font-bold text-charcoal mb-1.5">
                                Kategori Foto
                            </label>
                            <select name="category" id="create_category" 
                                    class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                                <option value="ambience" {{ old('category') == 'ambience' ? 'selected' : '' }}>Suasana / Ambience</option>
                                <option value="coffee" {{ old('category') == 'coffee' ? 'selected' : '' }}>Kopi / Manual Brew</option>
                                <option value="menu" {{ old('category') == 'menu' ? 'selected' : '' }}>Menu &amp; Pastry</option>
                                <option value="interior" {{ old('category') == 'interior' ? 'selected' : '' }}>Interior &amp; Ruangan</option>
                                <option value="outdoor" {{ old('category') == 'outdoor' ? 'selected' : '' }}>Area Outdoor</option>
                            </select>
                        </div>

                        <div>
                            <label for="create_sort_order" class="block text-xs font-bold text-charcoal mb-1.5">
                                Nomor Urutan Tampil
                            </label>
                            <input type="number" name="sort_order" id="create_sort_order" min="1"
                                   value="{{ old('sort_order', $totalCount + 1) }}"
                                   class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Keterangan Foto --}}
                    <div>
                        <label for="create_description" class="block text-xs font-bold text-charcoal mb-1.5">
                            Keterangan Cerita Singkat <span class="text-muted-text font-normal">(Opsional)</span>
                        </label>
                        <textarea name="description" id="create_description" rows="2" 
                                  placeholder="Ceritakan sedikit mengenai momen, aroma seduh, atau suasana di foto ini..."
                                  class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition resize-none">{{ old('description') }}</textarea>
                    </div>

                    {{-- Status Aktif Checkbox --}}
                    <div class="pt-2 flex items-center">
                        <label class="relative flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-10 h-6 bg-warm-stone border border-hairline peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-hairline after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ink-black"></div>
                            <span class="text-xs font-bold text-ink-black">Tampilkan di Website Pelanggan Langsung (Aktif)</span>
                        </label>
                    </div>

                    {{-- Submit Action Bar --}}
                    <div class="pt-4 border-t border-hairline flex items-center justify-end gap-3">
                        <button type="button" onclick="toggleUploadDrawer()" class="px-4 py-2.5 rounded-xl border border-hairline text-xs font-bold text-charcoal hover:bg-warm-stone transition btn-press">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press">
                            Simpan &amp; Publikasikan Foto
                        </button>
                    </div>

                </div>

            </div>
        </form>
    </div>

    {{-- =========================================================================
         SEARCH & FILTER TOOLBAR
         ========================================================================= --}}
    <div class="bg-pure-white border border-hairline rounded-2xl p-4 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-4 anim-fade-up">
        
        {{-- Search Input --}}
        <form action="{{ route('admin.gallery.index') }}" method="GET" class="flex-1 flex items-center gap-2">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-muted-text text-xs"></i>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari judul foto atau keterangan..."
                       class="w-full bg-warm-ivory border border-hairline rounded-xl pl-9 pr-4 py-2 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>
            @if($status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            @if($category !== 'all')
                <input type="hidden" name="category" value="{{ $category }}">
            @endif
            <button type="submit" class="px-3.5 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold transition btn-press">
                Cari
            </button>
            @if($q || $status !== 'all' || $category !== 'all')
                <a href="{{ route('admin.gallery.index') }}" class="px-3 py-2 rounded-xl border border-hairline text-xs font-bold text-charcoal hover:bg-warm-stone transition" title="Reset Filter">
                    Reset
                </a>
            @endif
        </form>

        {{-- Status Filter Buttons --}}
        <div class="flex items-center gap-1.5 self-start md:self-auto overflow-x-auto text-xs font-bold select-none">
            <a href="{{ route('admin.gallery.index', array_merge(request()->except('status'), ['status' => 'all'])) }}"
               class="px-3 py-1.5 rounded-xl transition {{ $status === 'all' ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent' }}">
                Semua ({{ $totalCount }})
            </a>
            <a href="{{ route('admin.gallery.index', array_merge(request()->except('status'), ['status' => 'active'])) }}"
               class="px-3 py-1.5 rounded-xl transition {{ $status === 'active' ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent' }}">
                Aktif ({{ $activeCount }})
            </a>
            <a href="{{ route('admin.gallery.index', array_merge(request()->except('status'), ['status' => 'inactive'])) }}"
               class="px-3 py-1.5 rounded-xl transition {{ $status === 'inactive' ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent' }}">
                Disembunyikan ({{ $inactiveCount }})
            </a>
        </div>

    </div>

    {{-- =========================================================================
         THUMBNAIL GRID (Responsive 1-col HP, 2-col Tablet, 3-4 col Desktop)
         ========================================================================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 anim-fade-up">
        @forelse($items as $item)
            <div class="group relative bg-pure-white rounded-2xl border border-hairline overflow-hidden shadow-card hover:shadow-card-hover transition-all flex flex-col justify-between">
                
                <div>
                    {{-- 4:3 / 16:10 Frame Photo --}}
                    <div class="relative w-full aspect-[4/3] bg-warm-stone overflow-hidden">
                        <img src="{{ $item->image_url }}" alt="{{ $item->caption }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">

                        {{-- Subtle Dark Scrim on Hover --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-black/70 via-transparent to-transparent pointer-events-none"></div>

                        {{-- Top Badge: Status Aktif & Urutan --}}
                        <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between pointer-events-none">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold tracking-wider backdrop-blur-md shadow-xs {{ $item->is_active ? 'bg-emerald-500/90 text-white' : 'bg-neutral-800/90 text-neutral-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full inline-block mr-1 {{ $item->is_active ? 'bg-white animate-pulse' : 'bg-neutral-400' }}"></span>
                                {{ $item->is_active ? 'Aktif' : 'Disembunyikan' }}
                            </span>

                            <span class="px-2 py-0.5 rounded-md bg-ink-black/70 backdrop-blur-md text-white text-[10px] font-mono font-bold border border-white/10">
                                #{{ $item->sort_order }}
                            </span>
                        </div>

                        {{-- Bottom Overlay Tag on Photo --}}
                        <div class="absolute bottom-2.5 left-2.5 right-2.5 pointer-events-none">
                            <span class="px-2 py-0.5 rounded-full bg-white/90 backdrop-blur-xs text-ink-black text-[9.5px] font-mono font-bold tracking-wider uppercase">
                                {{ $item->category ?: 'Ambience' }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Info Content --}}
                    <div class="p-4 space-y-1.5">
                        <h3 class="text-xs sm:text-sm font-bold text-ink-black leading-snug line-clamp-2">
                            {{ $item->caption ?: 'Tanpa Judul' }}
                        </h3>
                        @if($item->description)
                            <p class="text-[11px] text-muted-text line-clamp-2 leading-relaxed">
                                {{ $item->description }}
                            </p>
                        @else
                            <p class="text-[11px] text-muted-text/60 italic">
                                Tidak ada keterangan tambahan.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Action Bar at Bottom --}}
                <div class="p-3 bg-warm-ivory/50 border-t border-hairline flex items-center justify-between gap-2">
                    
                    {{-- Toggle Active / Inactive Form --}}
                    <form action="{{ route('admin.gallery.toggle-active', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="px-2.5 py-1.5 rounded-lg border border-hairline text-[11px] font-bold transition flex items-center gap-1.5 {{ $item->is_active ? 'bg-white text-charcoal hover:bg-warm-stone' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}"
                                title="{{ $item->is_active ? 'Sembunyikan dari Web' : 'Tampilkan di Web' }}">
                            <i class="fa-solid {{ $item->is_active ? 'fa-eye-slash text-[10px]' : 'fa-eye text-[10px]' }}"></i>
                            <span>{{ $item->is_active ? 'Sembunyikan' : 'Aktifkan' }}</span>
                        </button>
                    </form>

                    <div class="flex items-center gap-1.5">
                        {{-- Edit Button --}}
                        <button type="button" 
                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->caption) }}', '{{ addslashes($item->description ?? '') }}', '{{ $item->category ?? 'ambience' }}', {{ $item->sort_order }}, {{ $item->is_active ? 1 : 0 }}, '{{ $item->image_url }}')"
                                class="w-8 h-8 rounded-lg bg-white hover:bg-warm-stone border border-hairline text-ink-black flex items-center justify-center text-xs transition shadow-2xs btn-press"
                                title="Edit Foto &amp; Judul">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        {{-- Delete Form --}}
                        <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto \'{{ addslashes($item->caption) }}\'? File foto fisik di server juga akan dihapus permanen.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-8 h-8 rounded-lg bg-white hover:bg-rose-50 border border-hairline hover:border-rose-300 text-rose-600 flex items-center justify-center text-xs transition shadow-2xs btn-press"
                                    title="Hapus Foto">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        @empty
            <div class="col-span-full py-16 bg-pure-white border border-hairline rounded-3xl p-8 text-center text-muted-text text-xs">
                <div class="w-14 h-14 rounded-2xl bg-warm-stone border border-hairline flex items-center justify-center mx-auto mb-3 text-ink-black text-xl shadow-2xs">
                    <i class="fa-solid fa-images"></i>
                </div>
                <h3 class="text-sm font-bold text-ink-black">Tidak Ada Foto Galeri Ditemukan</h3>
                <p class="text-xs text-muted-text max-w-md mx-auto mt-1 leading-relaxed">
                    @if($q || $status !== 'all' || $category !== 'all')
                        Tidak ada foto yang cocok dengan pencarian atau filter yang dipilih. Silakan reset filter.
                    @else
                        Belum ada foto yang diunggah ke galeri. Klik tombol di bawah untuk mengunggah foto baru.
                    @endif
                </p>
                <button type="button" onclick="toggleUploadDrawer()" class="mt-4 px-5 py-2.5 rounded-xl bg-ink-black text-white font-bold text-xs uppercase tracking-wider shadow-xs hover:bg-charcoal transition btn-press inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Unggah Foto Sekarang</span>
                </button>
            </div>
        @endforelse
    </div>

    {{-- Pagination Bar --}}
    @if($items->hasPages())
        <div class="pt-4 flex items-center justify-center">
            {{ $items->links() }}
        </div>
    @endif

</div>

{{-- =========================================================================
     MODAL EDIT FOTO GALERI
     ========================================================================= --}}
<div id="editGalleryModal" class="fixed inset-0 z-50 bg-ink-black/60 backdrop-blur-xs hidden items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-xl bg-pure-white rounded-3xl border border-hairline shadow-2xl p-6 sm:p-8 my-8 text-ink-black" onclick="event.stopPropagation()">
        
        {{-- Header Modal --}}
        <div class="flex items-center justify-between pb-4 border-b border-hairline mb-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black font-bold text-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-extrabold text-ink-black">Edit Foto Galeri</h3>
                    <p class="text-[11px] text-muted-text">Ubah judul, ganti file gambar, atau sesuaikan urutan tampil.</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="p-2 text-muted-text hover:text-ink-black rounded-lg transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="editGalleryForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Current Image vs New Image Upload --}}
            <div>
                <label class="block text-xs font-bold text-charcoal mb-1.5">
                    Ganti Foto Gambar <span class="text-muted-text font-normal">(Kosongkan jika tidak ingin mengganti)</span>
                </label>
                
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    {{-- Current Preview Thumbnail --}}
                    <div class="sm:col-span-4 relative aspect-[4/3] rounded-xl overflow-hidden border border-hairline bg-warm-stone">
                        <img id="editCurrentThumb" src="" alt="Foto Saat Ini" class="w-full h-full object-cover">
                        <span class="absolute bottom-1 left-1 bg-ink-black/80 text-white text-[9px] px-1.5 py-0.5 rounded font-mono font-bold">Saat Ini</span>
                    </div>

                    {{-- File Input with Instant Replace Preview --}}
                    <div class="sm:col-span-8">
                        <input type="file" name="image" id="editImageInput" accept="image/jpeg,image/png,image/webp"
                               data-max-bytes="{{ $galleryLimits['effective_bytes'] ?? $galleryLimits['limit_bytes'] ?? 2097152 }}"
                               data-allowed-ext="jpg,jpeg,png,webp"
                               data-feedback-target="#editImageFeedback"
                               onchange="previewEditImage(event)"
                               class="w-full text-xs text-muted-text file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-warm-stone file:text-ink-black hover:file:bg-charcoal hover:file:text-white file:transition cursor-pointer border border-hairline rounded-xl p-2 bg-warm-ivory">
                        <p class="text-[10px] text-muted-text mt-1">Rekomendasi 1:1 atau 4:3 &bull; JPG, PNG, WebP &bull; Maks. {{ $galleryLimits['formatted'] ?? $galleryLimits['limit_formatted'] ?? '2 MB' }}</p>
                        <div id="editImageFeedback" class="mt-1 text-left"></div>
                    </div>
                </div>
            </div>

            {{-- Judul Foto --}}
            <div>
                <label for="edit_caption" class="block text-xs font-bold text-charcoal mb-1.5">
                    Judul Foto / Caption <span class="text-rose-600">*</span>
                </label>
                <input type="text" name="caption" id="edit_caption" required 
                       class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            {{-- Kategori & Urutan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit_category" class="block text-xs font-bold text-charcoal mb-1">
                        Kategori
                    </label>
                    <select name="category" id="edit_category" 
                            class="w-full bg-warm-ivory border border-hairline rounded-xl px-3 py-2 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                        <option value="ambience">Suasana / Ambience</option>
                        <option value="coffee">Kopi / Manual Brew</option>
                        <option value="menu">Menu &amp; Pastry</option>
                        <option value="interior">Interior &amp; Ruangan</option>
                        <option value="outdoor">Area Outdoor</option>
                    </select>
                </div>

                <div>
                    <label for="edit_sort_order" class="block text-xs font-bold text-charcoal mb-1">
                        Nomor Urutan
                    </label>
                    <input type="number" name="sort_order" id="edit_sort_order" min="1"
                           class="w-full bg-warm-ivory border border-hairline rounded-xl px-3 py-2 text-xs text-ink-black font-mono font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
                </div>
            </div>

            {{-- Keterangan Foto --}}
            <div>
                <label for="edit_description" class="block text-xs font-bold text-charcoal mb-1.5">
                    Keterangan Cerita Singkat <span class="text-muted-text font-normal">(Opsional)</span>
                </label>
                <textarea name="description" id="edit_description" rows="2" 
                          class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2 text-xs text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition resize-none"></textarea>
            </div>

            {{-- Status Aktif Checkbox --}}
            <div class="pt-1 flex items-center">
                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="sr-only peer">
                    <div class="w-10 h-6 bg-warm-stone border border-hairline peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-hairline after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ink-black"></div>
                    <span class="text-xs font-bold text-ink-black">Tampilkan di Website Pelanggan (Aktif)</span>
                </label>
            </div>

            {{-- Submit Footer --}}
            <div class="pt-4 border-t border-hairline flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-hairline text-xs font-bold text-charcoal hover:bg-warm-stone transition btn-press">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // 1. Toggle Collapsible Upload Drawer
    function toggleUploadDrawer() {
        const drawer = document.getElementById('uploadDrawer');
        const icon = document.getElementById('iconToggleUpload');
        const text = document.getElementById('textToggleUpload');
        
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
            if (text) text.textContent = 'Unggah Foto Baru';
        }
    }

    // 2. Preview Create Image
    function previewCreateImage(event) {
        const file = event.target.files && event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('createImagePreview');
            const overlay = document.getElementById('createImageOverlay');
            const placeholder = document.getElementById('createDropzonePlaceholder');

            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (overlay) overlay.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    // 3. Open Edit Modal with Prefilled Data
    function openEditModal(id, caption, description, category, sortOrder, isActive, imageUrl) {
        const modal = document.getElementById('editGalleryModal');
        const form = document.getElementById('editGalleryForm');
        
        form.action = '/admin/galeri/' + id;
        document.getElementById('edit_caption').value = caption;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_category').value = category;
        document.getElementById('edit_sort_order').value = sortOrder;
        document.getElementById('edit_is_active').checked = Boolean(isActive);
        document.getElementById('editCurrentThumb').src = imageUrl;
        document.getElementById('editImageInput').value = '';

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    // 4. Close Edit Modal
    function closeEditModal() {
        const modal = document.getElementById('editGalleryModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // 5. Preview Replacement Image in Edit Modal
    function previewEditImage(event) {
        const file = event.target.files && event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const thumb = document.getElementById('editCurrentThumb');
            if (thumb) {
                thumb.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }

    // Close Modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });

    // Close Modal on backdrop click
    document.getElementById('editGalleryModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection
