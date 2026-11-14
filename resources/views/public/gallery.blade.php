@extends('layouts.app')
@section('title', 'Galeri Arsitektur & Momen — Nucomu Cafe')

@section('content')
<!-- Architectural Showcase Carousel Section (Dengan Efek Paralaks & Sapuan Tirai) -->
@include('home.sections.gallery', ['stories' => $gallery])

<!-- Grid Arsip Visual Nucomu (Museum Exhibition Index) -->
<section class="py-16 sm:py-20 relative bg-white dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="mb-10 pb-6 border-b border-zinc-200 dark:border-zinc-800">
            <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
                <span class="text-xs font-mono uppercase tracking-[0.2em] text-zinc-500 block mb-3 pl-[0.2em]">Koleksi Visual Lengkap</span>
                <h2 class="text-2xl sm:text-4xl font-bold tracking-tight text-zinc-950 dark:text-white font-editorial mx-auto">
                    Arsip Foto &amp; Dokumentasi
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 max-w-3xl font-body leading-relaxed mx-auto mt-3.5">
                    Eksplorasi sudut ruang, detail material, dan suasana yang terekam di Nucomu Cafe Tulungagung.
                </p>
            </div>
        </div>

        @if($gallery->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-8">
                @foreach($gallery as $idx => $item)
                    @php
                        $imgSrc = $item->image_url ?? ($item->image ?? '');
                        $caption = $item->caption ?? ($item->title ?? 'Nucomu Cafe');
                        $cat = $item->category_badge ?? ($item->category ?? 'Nucomu');
                    @endphp
                    <div class="group relative bg-[#FAFAFA] dark:bg-zinc-900 border-2 border-zinc-900 dark:border-zinc-700 p-3 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer"
                         onclick="openPublicModal('{{ $imgSrc }}', '{{ addslashes($caption) }}', '{{ addslashes($item->description ?? '') }}', '{{ addslashes($cat) }}', '{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}')">
                        
                        <!-- Inner Photo Frame -->
                        <div class="relative aspect-[4/3] overflow-hidden bg-zinc-200 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700">
                            <img src="{{ $imgSrc }}"
                                 alt="{{ $caption }}"
                                 class="w-full h-full object-cover filter grayscale contrast-105 group-hover:grayscale-0 group-hover:scale-105 transition-all duration-500"
                                 loading="lazy">

                            <!-- Corner Mark -->
                            <div class="absolute top-2 right-2 px-1.5 py-0.5 bg-black/60 backdrop-blur-xs text-white font-mono text-[9px] uppercase tracking-widest">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>

                        <!-- Technical Caption Strip -->
                        <div class="mt-3 pt-2.5 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between text-[11px] font-mono">
                            <span class="text-zinc-500 uppercase tracking-widest truncate">{{ $cat }}</span>
                            <span class="text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">&rarr;</span>
                        </div>
                        <h3 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate mt-1">
                            {{ $caption }}
                        </h3>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white/70 dark:bg-zinc-900/40 backdrop-blur-md border border-zinc-200/80 dark:border-zinc-800/80 rounded-3xl p-8">
                <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-600 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-bold text-zinc-950 dark:text-white mb-2">Galeri Foto Belum Tersedia</h3>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">Foto galeri akan segera diperbarui oleh Admin Nucomu Cafe.</p>
            </div>
        @endif

    </div>
</section>

<!-- Lightbox Modal for Grid -->
<div id="publicGalleryModal"
     class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-6 opacity-0 pointer-events-none transition-opacity duration-300"
     onclick="closePublicModal()">
    <div class="relative max-w-4xl w-full bg-zinc-950 border border-zinc-800 p-4 sm:p-6 shadow-2xl"
         onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 border-b border-zinc-800 mb-3 text-white">
            <div class="flex items-center gap-2 font-mono text-xs">
                <span id="pubModalIdx" class="text-zinc-400"></span>
                <span class="text-zinc-600">|</span>
                <span id="pubModalCat" class="uppercase tracking-widest text-zinc-300"></span>
            </div>
            <button type="button"
                    onclick="closePublicModal()"
                    class="w-7 h-7 rounded-full bg-zinc-800 hover:bg-white hover:text-black text-zinc-400 transition-colors flex items-center justify-center font-mono text-sm">
                &times;
            </button>
        </div>
        <div class="max-h-[70vh] overflow-hidden flex items-center justify-center bg-black">
            <img id="pubModalImg" src="" alt="" class="max-w-full max-h-[70vh] object-contain">
        </div>
        <div class="pt-3 border-t border-zinc-800 mt-3 text-left">
            <h4 id="pubModalTitle" class="text-base font-bold text-white font-editorial"></h4>
            <p id="pubModalDesc" class="text-xs text-zinc-400 mt-1 font-body"></p>
        </div>
    </div>
</div>

<script>
function openPublicModal(src, title, desc, cat, idx) {
    const modal = document.getElementById('publicGalleryModal');
    if (!modal) return;
    document.getElementById('pubModalImg').src = src;
    document.getElementById('pubModalTitle').textContent = title;
    document.getElementById('pubModalDesc').textContent = desc;
    document.getElementById('pubModalCat').textContent = cat;
    document.getElementById('pubModalIdx').textContent = idx;
    modal.classList.remove('opacity-0', 'pointer-events-none');
    document.body.style.overflow = 'hidden';
}

function closePublicModal() {
    const modal = document.getElementById('publicGalleryModal');
    if (!modal) return;
    modal.classList.add('opacity-0', 'pointer-events-none');
    document.body.style.overflow = '';
}
</script>
@endsection
