@extends('layouts.admin')
@section('title', 'Galeri & Live Story — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    
    {{-- Minimalist Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Story &amp; Suasana Kafe &bull; Live Feed</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Galeri &amp; Live Story
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kelola feed visual cerita harian, manual brew, suasana interior, dan bakery yang tampil di beranda web.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Toggle Button to Open/Close Minimal Upload Drawer --}}
            <button type="button" onclick="toggleUploadDrawer()" id="btnToggleUpload"
                    class="px-4 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition-all shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-plus text-xs" id="iconToggleUpload"></i>
                <span id="textToggleUpload">Unggah Story Baru</span>
            </button>

            <a href="{{ route('home') }}#gallery" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Buka Story Feed di Website Pelanggan">
                <span>Web Customer</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- =========================================================================
         COLLAPSIBLE MINIMALIST UPLOAD STUDIO (Terbuka/Tertutup Halus & Intuitif)
         ========================================================================= --}}
    <div id="uploadDrawer" class="hidden bg-pure-white border border-hairline rounded-3xl p-6 sm:p-8 shadow-card reveal-on-scroll anim-fade-up">
        <div class="flex items-center justify-between pb-4 border-b border-hairline mb-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                    <i class="fa-solid fa-camera-retro text-xs"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-ink-black">Unggah Story Baru ke Feed</h2>
                    <p class="text-[11px] text-muted-text">Pilih foto dan beri judul singkat untuk cerita kafe hari ini.</p>
                </div>
            </div>
            <button type="button" onclick="toggleUploadDrawer()" class="p-2 text-muted-text hover:text-ink-black rounded-lg transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- Left: Modern Minimalist Dropzone & Instant Preview (5 Cols) --}}
                <div class="lg:col-span-5">
                    <label class="block text-xs font-bold text-charcoal mb-2">
                        Foto Story <span class="text-rose-600">*</span>
                    </label>

                    <div id="dropzoneContainer" 
                         onclick="document.getElementById('imageInput').click()"
                         class="relative w-full aspect-video sm:aspect-[4/3] rounded-2xl border-2 border-dashed border-hairline hover:border-ink-black bg-warm-ivory flex flex-col items-center justify-center p-6 text-center cursor-pointer transition-all group overflow-hidden">
                        
                        <input type="file" name="image" id="imageInput" required accept="image/*" class="hidden" onchange="handleImagePreview(event)">

                        {{-- Default Placeholder --}}
                        <div id="dropzonePlaceholder" class="space-y-3 pointer-events-none transition-transform group-hover:scale-105">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-hairline text-ink-black flex items-center justify-center mx-auto shadow-2xs">
                                <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-ink-black block">Klik untuk memilih foto</span>
                                <span class="text-[10px] text-muted-text block mt-0.5">JPG, PNG, atau WebP (Maks. 4MB)</span>
                            </div>
                        </div>

                        {{-- Instant Preview Image --}}
                        <img id="imagePreview" src="" alt="Pratinjau Story" class="hidden absolute inset-0 w-full h-full object-cover">
                        <div id="imagePreviewOverlay" class="hidden absolute inset-0 bg-ink-black/40 backdrop-blur-xs flex items-center justify-center text-white text-xs font-bold gap-2">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Ganti Foto</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Essential Inputs & One-Click Tag Presets (7 Cols) --}}
                <div class="lg:col-span-7 space-y-4">
                    
                    {{-- Caption / Judul --}}
                    <div>
                        <label for="caption" class="block text-xs font-bold text-charcoal mb-1.5">
                            Judul Cerita / Caption Utama <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="caption" id="caption" required 
                               placeholder="Contoh: Manual Pour-over Beans House Blend"
                               class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
                    </div>

                    {{-- Tagar Cepat / Quick Badge Presets --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="category_badge" class="text-xs font-bold text-charcoal">
                                Tagar Badge (#Tag)
                            </label>
                            <span class="text-[10px] text-muted-text">Klik tagar cepat di bawah:</span>
                        </div>
                        <input type="text" name="category_badge" id="category_badge" 
                               value="#CraftCoffee" placeholder="#CraftCoffee"
                               class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2 text-xs font-mono text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition">
                        
                        {{-- Preset Pills --}}
                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                            <button type="button" onclick="setPresetBadge('#CraftCoffee', 'Coffee')" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                #CraftCoffee
                            </button>
                            <button type="button" onclick="setPresetBadge('#CozyVibes', 'Interior')" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                #CozyVibes
                            </button>
                            <button type="button" onclick="setPresetBadge('#ArtisanalBake', 'Bakery')" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                #ArtisanalBake
                            </button>
                            <button type="button" onclick="setPresetBadge('#ManualBrew', 'Coffee')" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                #ManualBrew
                            </button>
                            <button type="button" onclick="setPresetBadge('#NucomuStory', 'Ambience')" class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-warm-stone/80 hover:bg-ink-black hover:text-white text-charcoal border border-hairline transition">
                                #NucomuStory
                            </button>
                        </div>
                    </div>

                    {{-- Collapsible Advanced Details Accordion --}}
                    <div class="pt-2">
                        <button type="button" onclick="toggleAdvancedOptions()" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-text hover:text-ink-black transition">
                            <i class="fa-solid fa-sliders text-[10px]"></i>
                            <span>Pengaturan Detail Tambahan (Author, Waktu &amp; Deskripsi)</span>
                            <i class="fa-solid fa-chevron-down text-[9px] transition-transform" id="iconAdvancedOpt"></i>
                        </button>

                        <div id="advancedOptionsPanel" class="hidden mt-3 p-4 rounded-2xl bg-warm-ivory border border-hairline space-y-3.5">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label for="author" class="block text-[11px] font-bold text-charcoal mb-1">Author</label>
                                    <input type="text" name="author" id="author" value="Barista Corner"
                                           class="w-full bg-white border border-hairline rounded-lg px-3 py-1.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                                </div>
                                <div>
                                    <label for="time_label" class="block text-[11px] font-bold text-charcoal mb-1">Label Waktu</label>
                                    <input type="text" name="time_label" id="time_label" value="Baru saja"
                                           class="w-full bg-white border border-hairline rounded-lg px-3 py-1.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                                </div>
                                <div>
                                    <label for="category" class="block text-[11px] font-bold text-charcoal mb-1">Kategori</label>
                                    <input type="text" name="category" id="category" value="Coffee"
                                           class="w-full bg-white border border-hairline rounded-lg px-3 py-1.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                                </div>
                            </div>
                            <div>
                                <label for="description" class="block text-[11px] font-bold text-charcoal mb-1">Deskripsi Singkat Cerita (Opsional)</label>
                                <textarea name="description" id="description" rows="2" placeholder="Proses seduh perlahan untuk mengekstrak aroma floral..."
                                          class="w-full bg-white border border-hairline rounded-lg px-3 py-1.5 text-xs text-ink-black focus:outline-none focus:border-ink-black resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Action Bar --}}
                    <div class="pt-3 border-t border-hairline flex items-center justify-end gap-3">
                        <button type="button" onclick="toggleUploadDrawer()" class="px-4 py-2.5 rounded-xl border border-hairline text-xs font-bold text-charcoal hover:bg-warm-stone transition btn-press">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press">
                            Terbitkan Story
                        </button>
                    </div>

                </div>

            </div>
        </form>
    </div>

    {{-- =========================================================================
         VIEW MODE SELECTOR & CATEGORY FILTER TABS (Minimalis & Cepat)
         ========================================================================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-hairline reveal-on-scroll">
        {{-- Category Filters --}}
        <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-bold select-none">
            <button type="button" onclick="filterGalleryCards('all', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl transition bg-ink-black text-white shadow-xs">
                Semua Story ({{ $items->count() }})
            </button>
            <button type="button" onclick="filterGalleryCards('coffee', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl transition text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent">
                Coffee
            </button>
            <button type="button" onclick="filterGalleryCards('interior', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl transition text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent">
                Interior
            </button>
            <button type="button" onclick="filterGalleryCards('bakery', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl transition text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent">
                Bakery
            </button>
            <button type="button" onclick="filterGalleryCards('ambience', this)" class="gallery-filter-btn px-3.5 py-1.5 rounded-xl transition text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent">
                Ambience
            </button>
        </div>

        {{-- Switch Layout Mode: Grid Minimalis vs Simulasi Live Story --}}
        <div class="flex items-center gap-1 bg-warm-stone/70 p-1 rounded-xl border border-hairline self-start sm:self-auto text-xs font-bold">
            <button type="button" onclick="switchGalleryView('grid')" id="btnViewGrid" class="px-3 py-1.5 rounded-lg bg-pure-white text-ink-black shadow-xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-grip text-xs"></i>
                <span>Grid Foto</span>
            </button>
            <button type="button" onclick="switchGalleryView('live-player')" id="btnViewLive" class="px-3 py-1.5 rounded-lg text-muted-text hover:text-ink-black transition flex items-center gap-1.5">
                <i class="fa-solid fa-play text-xs"></i>
                <span>Simulasi Web Live</span>
            </button>
        </div>
    </div>

    {{-- =========================================================================
         VIEW 1: GRID MINIMALIS (Foto Elegan dengan Rasio 16:10 & Quick Action Hover)
         ========================================================================= --}}
    <div id="galleryGridView" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 reveal-on-scroll">
        @forelse($items as $item)
            @php
                $itemImageUrl = null;
                if ($item->image && file_exists(public_path('storage/' . $item->image))) {
                    $itemImageUrl = asset('storage/' . $item->image);
                } elseif ($item->image && (str_starts_with($item->image, 'http://') || str_starts_with($item->image, 'https://'))) {
                    $itemImageUrl = $item->image;
                }
                $itemCatSlug = strtolower($item->category ?? 'ambience');
            @endphp

            <div class="gallery-card group relative bg-pure-white rounded-2xl border border-hairline overflow-hidden shadow-card card-lift transition-all flex flex-col"
                 data-category="{{ $itemCatSlug }}">
                
                {{-- 16:10 Aspect Visual Frame --}}
                <div class="relative w-full aspect-[16/10] bg-warm-stone overflow-hidden cursor-pointer"
                     onclick="openLightbox('{{ $itemImageUrl }}', '{{ addslashes($item->caption ?? 'Nucomu Story') }}', '{{ addslashes($item->author ?? 'Barista Corner') }}', '{{ addslashes($item->category_badge ?? '#Story') }}', '{{ addslashes($item->description ?? '') }}')">
                    
                    @if($itemImageUrl)
                        <img src="{{ $itemImageUrl }}" alt="{{ $item->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-muted-text text-xs font-mono">
                            <i class="fa-regular fa-image text-xl"></i>
                        </div>
                    @endif

                    {{-- Subtle Gradient Scrim --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-black/80 via-ink-black/20 to-transparent pointer-events-none"></div>

                    {{-- Top Badges --}}
                    <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between pointer-events-none">
                        <span class="px-2 py-0.5 rounded-full bg-ink-black/70 backdrop-blur-xs text-white text-[9.5px] font-mono font-bold tracking-wider border border-white/10">
                            {{ $item->category_badge ?: '#Story' }}
                        </span>
                        <span class="text-[9.5px] text-white/90 drop-shadow-xs font-mono">
                            {{ $item->time_label ?: 'Baru saja' }}
                        </span>
                    </div>

                    {{-- Bottom Caption inside photo overlay --}}
                    <div class="absolute bottom-2.5 left-2.5 right-2.5 pointer-events-none">
                        <h3 class="text-xs font-bold text-white leading-tight drop-shadow-xs line-clamp-1 font-sans">
                            {{ $item->caption ?: 'Nucomu Story' }}
                        </h3>
                        <p class="text-[10px] text-white/80 line-clamp-1 mt-0.5 font-light">
                            {{ $item->author ?: 'Barista Corner' }}
                        </p>
                    </div>

                    {{-- Quick Action Hover Bar --}}
                    <div class="absolute inset-0 bg-ink-black/50 backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2.5 pointer-events-auto">
                        <button type="button" 
                                onclick="event.stopPropagation(); openLightbox('{{ $itemImageUrl }}', '{{ addslashes($item->caption ?? 'Nucomu Story') }}', '{{ addslashes($item->author ?? 'Barista Corner') }}', '{{ addslashes($item->category_badge ?? '#Story') }}', '{{ addslashes($item->description ?? '') }}')"
                                class="w-9 h-9 rounded-xl bg-white text-ink-black flex items-center justify-center text-xs shadow-xs hover:scale-110 transition btn-press"
                                title="Lihat Pratinjau Penuh">
                            <i class="fa-solid fa-expand"></i>
                        </button>
                        
                        <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" 
                              onsubmit="event.stopPropagation(); return confirm('Hapus story galeri ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-9 h-9 rounded-xl bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-xs shadow-xs hover:scale-110 transition btn-press"
                                    title="Hapus Story">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Minimal Footer Row --}}
                <div class="p-3 bg-pure-white flex items-center justify-between text-[11px] text-muted-text border-t border-hairline">
                    <span class="font-bold text-charcoal uppercase text-[10px] font-mono tracking-wider">
                        {{ $item->category ?: 'Ambiance' }}
                    </span>
                    <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus story ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:text-rose-800 text-[10px] font-bold hover:underline">
                            Hapus
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="col-span-full py-16 bg-pure-white border border-hairline rounded-3xl p-8 text-center text-muted-text text-xs">
                <div class="w-14 h-14 rounded-2xl bg-warm-stone border border-hairline flex items-center justify-center mx-auto mb-3 text-ink-black text-xl shadow-2xs">
                    <i class="fa-solid fa-camera-retro"></i>
                </div>
                <h3 class="text-sm font-bold text-ink-black">Belum Ada Story Khusus yang Diunggah</h3>
                <p class="text-xs text-muted-text max-w-md mx-auto mt-1 leading-relaxed">
                    Website customer saat ini sedang menampilkan story bawaan. Klik tombol di bawah untuk mengunggah cerita visual kafe Anda sendiri.
                </p>
                <button type="button" onclick="toggleUploadDrawer()" class="mt-4 px-5 py-2.5 rounded-xl bg-ink-black text-white font-bold text-xs uppercase tracking-wider shadow-xs hover:bg-charcoal transition btn-press inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Unggah Story Pertama</span>
                </button>
            </div>
        @endforelse
    </div>

    {{-- =========================================================================
         VIEW 2: SIMULASI LIVE STORY PLAYER (Sama Persis dengan Web Customer)
         ========================================================================= --}}
    <div id="galleryLivePlayerView" class="hidden max-w-3xl mx-auto space-y-4">
        
        <div class="flex items-center justify-between text-xs px-2 text-muted-text">
            <span class="font-mono font-bold uppercase tracking-wider text-charcoal">
                Simulasi Feed Beranda Web Customer
            </span>
            <span class="bg-warm-stone px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold text-ink-black border border-hairline">
                16:10 Ratio &bull; Otomatis Berganti
            </span>
        </div>

        <div class="relative w-full aspect-[16/10] max-h-[460px] rounded-3xl overflow-hidden bg-neutral-900 border border-hairline shadow-elevated select-none group">
            
            <img id="liveSimulatorImage" src="" alt="Live Simulator" class="w-full h-full object-cover transition-all duration-700">

            {{-- Dark Gradients --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/15 to-black/85 pointer-events-none"></div>

            {{-- Top User Info --}}
            <div class="absolute top-0 left-0 right-0 p-5 sm:p-6 z-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full p-0.5 bg-white/40 backdrop-blur-md shadow-md border border-white/50">
                        <img id="liveSimulatorAvatar" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span id="liveSimulatorAuthor" class="text-xs sm:text-sm font-semibold text-white drop-shadow-sm font-sans tracking-tight">Barista Corner</span>
                            <i class="fa-solid fa-circle-check text-white text-xs"></i>
                        </div>
                        <span id="liveSimulatorTime" class="text-[10px] sm:text-xs text-neutral-300 drop-shadow-sm font-mono">Baru saja</span>
                    </div>
                </div>

                <span id="liveSimulatorBadge" class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest px-3.5 py-1.5 rounded-full bg-ink-black/80 backdrop-blur-md border border-white/20 text-white shadow-md font-mono">
                    #CraftCoffee
                </span>
            </div>

            {{-- Touch Click Zones (Left / Right) --}}
            <div class="absolute inset-0 z-10 flex">
                <div class="w-1/3 h-full cursor-pointer" onclick="prevSimStory(event)" title="Story Sebelumnya"></div>
                <div class="w-2/3 h-full cursor-pointer" onclick="nextSimStory(event)" title="Story Berikutnya"></div>
            </div>

            {{-- Bottom Caption Overlay --}}
            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20 space-y-2 pointer-events-none">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-ink-black/80 backdrop-blur-md border border-white/20 text-[10px] font-semibold text-white">
                    <i class="fa-solid fa-location-dot text-neutral-300"></i> Nucomu Main Bar
                </div>
                <h3 id="liveSimulatorTitle" class="text-xl sm:text-2xl font-bold text-white leading-tight drop-shadow font-editorial">
                    Loading...
                </h3>
                <p id="liveSimulatorDesc" class="text-xs text-neutral-200 leading-relaxed drop-shadow max-w-xl font-normal">
                    ...
                </p>
            </div>

            {{-- Navigation Arrows --}}
            <button type="button" onclick="prevSimStory(event)" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-ink-black flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
            <button type="button" onclick="nextSimStory(event)" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-ink-black flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>
        </div>

        <p class="text-[11px] text-center text-muted-text">
            Klik sisi kiri atau kanan layar untuk berpindah story, atau arahkan kursor ke dalam frame untuk menjeda perputaran otomatis.
        </p>
    </div>

</div>

{{-- =========================================================================
     MINIMALIST FULLSCREEN LIGHTBOX MODAL
     ========================================================================= --}}
<div id="galleryLightboxModal" class="fixed inset-0 z-50 bg-ink-black/85 backdrop-blur-md hidden items-center justify-center p-4" onclick="closeLightbox()">
    <div class="relative max-w-2xl w-full bg-ink-black rounded-3xl overflow-hidden border border-white/15 shadow-2xl text-white" onclick="event.stopPropagation()">
        
        {{-- Close button --}}
        <button type="button" onclick="closeLightbox()" class="absolute top-4 right-4 z-30 w-9 h-9 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        {{-- Large Image --}}
        <div class="relative w-full aspect-[16/10] bg-black">
            <img id="lightboxImage" src="" class="w-full h-full object-cover">
        </div>

        {{-- Details bar --}}
        <div class="p-6 space-y-2 bg-[#1C1C1C]">
            <div class="flex items-center justify-between text-xs text-neutral-400">
                <span id="lightboxBadge" class="font-mono text-emerald-400 font-bold">#Story</span>
                <span id="lightboxAuthor" class="font-medium">Barista Corner</span>
            </div>
            <h3 id="lightboxTitle" class="text-lg font-bold text-white font-sans"></h3>
            <p id="lightboxDesc" class="text-xs text-neutral-300 leading-relaxed"></p>
        </div>
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
            if (text) text.textContent = 'Unggah Story Baru';
        }
    }

    // 2. Drag & Drop / File Input Instant Preview
    function handleImagePreview(e) {
        const file = e.target.files && e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(evt) {
            const previewImg = document.getElementById('imagePreview');
            const previewOverlay = document.getElementById('imagePreviewOverlay');
            const placeholder = document.getElementById('dropzonePlaceholder');

            if (previewImg) {
                previewImg.src = evt.target.result;
                previewImg.classList.remove('hidden');
            }
            if (previewOverlay) previewOverlay.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    // 3. Preset Badge Helper
    function setPresetBadge(badge, category) {
        const badgeInput = document.getElementById('category_badge');
        const catInput = document.getElementById('category');
        if (badgeInput) badgeInput.value = badge;
        if (catInput) catInput.value = category;
    }

    // 4. Toggle Advanced Options
    function toggleAdvancedOptions() {
        const panel = document.getElementById('advancedOptionsPanel');
        const icon = document.getElementById('iconAdvancedOpt');
        if (!panel) return;
        
        const isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
            panel.classList.add('hidden');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
    }

    // 5. Category Filtering
    function filterGalleryCards(category, btnEl) {
        // Update active button visual
        document.querySelectorAll('.gallery-filter-btn').forEach(btn => {
            btn.classList.remove('bg-ink-black', 'text-white', 'shadow-xs');
            btn.classList.add('text-muted-text', 'hover:text-ink-black', 'hover:bg-warm-stone');
        });
        if (btnEl) {
            btnEl.classList.remove('text-muted-text', 'hover:text-ink-black', 'hover:bg-warm-stone');
            btnEl.classList.add('bg-ink-black', 'text-white', 'shadow-xs');
        }

        const cards = document.querySelectorAll('.gallery-card');
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category') || '';
            if (category === 'all' || cardCat.includes(category)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 6. Switch View: Grid vs Live Story Player
    function switchGalleryView(mode) {
        const gridView = document.getElementById('galleryGridView');
        const liveView = document.getElementById('galleryLivePlayerView');
        const btnGrid = document.getElementById('btnViewGrid');
        const btnLive = document.getElementById('btnViewLive');

        if (mode === 'grid') {
            if (gridView) gridView.classList.remove('hidden');
            if (liveView) liveView.classList.add('hidden');
            
            btnGrid.classList.add('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnGrid.classList.remove('text-muted-text');
            btnLive.classList.remove('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnLive.classList.add('text-muted-text');
        } else {
            if (gridView) gridView.classList.add('hidden');
            if (liveView) liveView.classList.remove('hidden');

            btnLive.classList.add('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnLive.classList.remove('text-muted-text');
            btnGrid.classList.remove('bg-pure-white', 'text-ink-black', 'shadow-xs');
            btnGrid.classList.add('text-muted-text');

            initLiveSimulator();
        }
    }

    // 7. Live Story Player Simulator Logic
    const simulatorItems = @json($items);
    let simIndex = 0;
    let simTimer = null;

    function initLiveSimulator() {
        if (!simulatorItems || !simulatorItems.length) return;
        renderSimStory();
        clearInterval(simTimer);
        simTimer = setInterval(nextSimStory, 4000);
    }

    function renderSimStory() {
        if (!simulatorItems || !simulatorItems.length) return;
        const current = simulatorItems[simIndex];

        let imgUrl = current.image;
        if (imgUrl && !imgUrl.startsWith('http')) {
            imgUrl = '/storage/' + imgUrl;
        }

        const imgEl = document.getElementById('liveSimulatorImage');
        const authorEl = document.getElementById('liveSimulatorAuthor');
        const timeEl = document.getElementById('liveSimulatorTime');
        const badgeEl = document.getElementById('liveSimulatorBadge');
        const titleEl = document.getElementById('liveSimulatorTitle');
        const descEl = document.getElementById('liveSimulatorDesc');

        if (imgEl && imgUrl) imgEl.src = imgUrl;
        if (authorEl) authorEl.textContent = current.author || 'Barista Corner';
        if (timeEl) timeEl.textContent = current.time_label || 'Baru saja';
        if (badgeEl) badgeEl.textContent = current.category_badge || '#CraftCoffee';
        if (titleEl) titleEl.textContent = current.caption || 'Nucomu Story';
        if (descEl) descEl.textContent = current.description || 'Pilihan racikan dan suasana Nucomu Cafe.';
    }

    function nextSimStory(e) {
        if (e) e.stopPropagation();
        if (!simulatorItems.length) return;
        simIndex = (simIndex + 1) % simulatorItems.length;
        renderSimStory();
    }

    function prevSimStory(e) {
        if (e) e.stopPropagation();
        if (!simulatorItems.length) return;
        simIndex = (simIndex - 1 + simulatorItems.length) % simulatorItems.length;
        renderSimStory();
    }

    // 8. Lightbox Modal
    function openLightbox(imgUrl, caption, author, badge, desc) {
        const modal = document.getElementById('galleryLightboxModal');
        const img = document.getElementById('lightboxImage');
        const title = document.getElementById('lightboxTitle');
        const auth = document.getElementById('lightboxAuthor');
        const b = document.getElementById('lightboxBadge');
        const d = document.getElementById('lightboxDesc');

        if (img) img.src = imgUrl;
        if (title) title.textContent = caption;
        if (auth) auth.textContent = author;
        if (b) b.textContent = badge;
        if (d) d.textContent = desc;

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeLightbox() {
        const modal = document.getElementById('galleryLightboxModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Keyboard support for Lightbox
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endsection
