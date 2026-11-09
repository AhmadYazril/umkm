@extends('layouts.admin')
@section('title', 'Kurasi Ulasan & Reputasi — Nucomu Cafe')

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

    {{-- Top Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Modul 6 &bull; Kurasi Ulasan Pelanggan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Ulasan &amp; Reputasi Kafe
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Admin bertindak sebagai kurator ulasan. Pilih ulasan terbaik dari pelanggan untuk disematkan (di-pin) ke website.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('home') }}#reviews" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Buka Bagian Ulasan di Web Customer">
                <span>Lihat di Beranda Web</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- Stats Summary Cards (4 Cards) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 anim-fade-up">
        <div class="p-4 sm:p-5 rounded-2xl bg-pure-white border border-hairline shadow-card flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-text">Total Ulasan</p>
                <p class="text-xl sm:text-2xl font-black text-ink-black mt-1">{{ $totalCount }}</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                <i class="fa-solid fa-comments text-sm sm:text-base"></i>
            </div>
        </div>

        <div class="p-4 sm:p-5 rounded-2xl bg-pure-white border border-emerald-200/80 shadow-card flex items-center justify-between bg-gradient-to-br from-emerald-50/40 to-pure-white">
            <div>
                <div class="flex items-center gap-1.5">
                    <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-emerald-800">Sedang Di-Pin</p>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <p class="text-xl sm:text-2xl font-black text-emerald-600 mt-1">{{ $pinnedCount }} <span class="text-[11px] font-medium text-emerald-800">Ulasan</span></p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-100/70 border border-emerald-200 flex items-center justify-center text-emerald-700">
                <i class="fa-solid fa-thumbtack text-sm sm:text-base"></i>
            </div>
        </div>

        <div class="p-4 sm:p-5 rounded-2xl bg-pure-white border border-hairline shadow-card flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-text">Belum Di-Pin</p>
                <p class="text-xl sm:text-2xl font-black text-charcoal mt-1">{{ $unpinnedCount }}</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-muted-text">
                <i class="fa-solid fa-inbox text-sm sm:text-base"></i>
            </div>
        </div>

        <div class="p-4 sm:p-5 rounded-2xl bg-pure-white border border-hairline shadow-card flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-muted-text">Rata-Rata Rating</p>
                <div class="flex items-center gap-1.5 mt-1">
                    <p class="text-xl sm:text-2xl font-black text-ink-black">{{ $avgRating }}</p>
                    <span class="text-amber-500 text-xs">★</span>
                </div>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600">
                <i class="fa-solid fa-star text-sm sm:text-base"></i>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         TOOLBAR: PENCARIAN & FILTER TAB (SEMUA / DI-PIN / BELUM DI-PIN)
         ========================================================================= --}}
    <div class="bg-pure-white border border-hairline rounded-2xl p-4 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-4 anim-fade-up">
        
        {{-- Search Input --}}
        <form action="{{ route('admin.testimonials.index') }}" method="GET" class="flex-1 flex flex-wrap sm:flex-nowrap items-center gap-2">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-muted-text text-xs"></i>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama pengulas atau kata ulasan..."
                       class="w-full bg-warm-ivory border border-hairline rounded-xl pl-9 pr-4 py-2 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            @if($filter !== 'all')
                <input type="hidden" name="filter" value="{{ $filter }}">
            @endif

            {{-- Rating Filter Dropdown --}}
            <select name="rating" onchange="this.form.submit()" 
                    class="bg-warm-ivory border border-hairline rounded-xl px-3 py-2 text-xs text-charcoal font-semibold focus:outline-none focus:border-ink-black transition">
                <option value="">Semua Rating</option>
                <option value="5" {{ $rating == '5' ? 'selected' : '' }}>★ 5 Bintang</option>
                <option value="4" {{ $rating == '4' ? 'selected' : '' }}>★ 4 Bintang</option>
                <option value="3" {{ $rating == '3' ? 'selected' : '' }}>★ 3 Bintang</option>
                <option value="2" {{ $rating == '2' ? 'selected' : '' }}>★ 2 Bintang</option>
                <option value="1" {{ $rating == '1' ? 'selected' : '' }}>★ 1 Bintang</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white text-xs font-bold transition btn-press">
                Cari
            </button>

            @if($q || $filter !== 'all' || $rating)
                <a href="{{ route('admin.testimonials.index') }}" class="px-3 py-2 rounded-xl border border-hairline text-xs font-bold text-charcoal hover:bg-warm-stone transition" title="Reset Filter">
                    Reset
                </a>
            @endif
        </form>

        {{-- Pin Filter Tabs --}}
        <div class="flex items-center gap-1.5 self-start md:self-auto overflow-x-auto text-xs font-bold select-none w-full sm:w-auto">
            <a href="{{ route('admin.testimonials.index', array_merge(request()->except('filter'), ['filter' => 'all'])) }}"
               class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ $filter === 'all' ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent' }}">
                Semua ({{ $totalCount }})
            </a>
            <a href="{{ route('admin.testimonials.index', array_merge(request()->except('filter'), ['filter' => 'pinned'])) }}"
               class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ $filter === 'pinned' ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent' }}">
                <span class="inline-block mr-1">📌</span> Di-Pin ({{ $pinnedCount }})
            </a>
            <a href="{{ route('admin.testimonials.index', array_merge(request()->except('filter'), ['filter' => 'unpinned'])) }}"
               class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ $filter === 'unpinned' ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone border border-transparent' }}">
                Belum Di-Pin ({{ $unpinnedCount }})
            </a>
        </div>

    </div>

    {{-- =========================================================================
         DAFTAR ULASAN (RESPONSIF: Rapi di HP maupun Layar Lebar)
         ========================================================================= --}}
    <div class="space-y-4 anim-fade-up">
        @forelse($testimonials as $testi)
            <div class="p-5 sm:p-6 rounded-2xl sm:rounded-3xl bg-pure-white border transition-all shadow-card hover:shadow-card-hover {{ $testi->is_pinned ? 'border-emerald-200 bg-gradient-to-r from-emerald-50/20 via-pure-white to-pure-white' : 'border-hairline' }}">
                
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                    
                    {{-- User Profile & Content (Left Side) --}}
                    <div class="flex-1 space-y-3">
                        
                        {{-- Top Meta Row --}}
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                {{-- Avatar Initial or Image --}}
                                @if($testi->avatar_url)
                                    <img src="{{ $testi->avatar_url }}" alt="{{ $testi->name }}" class="w-10 h-10 rounded-full object-cover border border-hairline shadow-2xs">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-warm-stone border border-hairline flex items-center justify-center text-ink-black font-extrabold text-sm font-sans shadow-2xs">
                                        {{ strtoupper(substr($testi->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-extrabold text-ink-black font-sans leading-none">
                                            {{ $testi->name }}
                                        </h3>
                                        @if($testi->is_pinned)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i class="fa-solid fa-thumbtack text-[9px]"></i> PINNED DI WEB
                                            </span>
                                        @endif
                                        @if(! $testi->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-neutral-200 text-neutral-700">
                                                Disembunyikan
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] text-muted-text mt-1">
                                        <span>{{ $testi->handle ?: 'Pengunjung Nucomu' }}</span>
                                        <span>&bull;</span>
                                        <span class="font-mono">{{ $testi->created_at ? $testi->created_at->format('d M Y, H:i') : 'Bawaan Sistem' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Rating Stars & Order Tag --}}
                            <div class="flex items-center gap-2">
                                <div class="px-2.5 py-1 rounded-xl bg-warm-stone/80 border border-hairline flex items-center gap-1 text-xs">
                                    <span class="text-amber-500 tracking-tighter">
                                        @for($i = 0; $i < $testi->rating; $i++)
                                            ★
                                        @endfor
                                    </span>
                                    <span class="font-mono font-bold text-ink-black text-[11px] ml-1">{{ $testi->rating }}.0</span>
                                </div>

                                @if($testi->order_tag)
                                    <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-warm-ivory text-charcoal border border-hairline text-[11px] font-medium">
                                        <i class="fa-solid fa-mug-saucer text-[10px] text-muted-text"></i>
                                        <span>{{ $testi->order_tag }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Order tag on mobile if present --}}
                        @if($testi->order_tag)
                            <div class="sm:hidden pt-0.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-warm-ivory text-charcoal border border-hairline text-[10px] font-medium">
                                    <i class="fa-solid fa-mug-saucer text-[9px] text-muted-text"></i>
                                    <span>{{ $testi->order_tag }}</span>
                                </span>
                            </div>
                        @endif

                        {{-- Review Content (Read-Only Original Text) --}}
                        <div class="p-3.5 sm:p-4 rounded-2xl bg-warm-ivory/60 border border-hairline/80">
                            <p class="text-xs sm:text-sm text-charcoal leading-relaxed whitespace-pre-line font-sans">
                                "{{ $testi->content }}"
                            </p>
                        </div>

                        {{-- Attached Photo if customer uploaded photo --}}
                        @if($testi->review_image)
                            @php
                                $imgUrl = str_starts_with($testi->review_image, 'http') ? $testi->review_image : asset($testi->review_image);
                            @endphp
                            <div class="pt-1">
                                <a href="{{ $imgUrl }}" target="_blank" class="inline-flex items-center gap-2 text-[11px] font-bold text-charcoal hover:text-ink-black p-1.5 rounded-xl border border-hairline bg-white hover:bg-warm-stone transition">
                                    <img src="{{ $imgUrl }}" alt="Foto Ulasan" class="w-8 h-8 rounded-lg object-cover">
                                    <span>Lihat Foto Ulasan Pelanggan</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                </a>
                            </div>
                        @endif

                    </div>

                    {{-- Actions Bar (Right Side / Full Width on Mobile) --}}
                    <div class="pt-3 lg:pt-0 border-t lg:border-t-0 border-hairline flex flex-wrap items-center lg:flex-col lg:items-end gap-2 shrink-0">
                        
                        {{-- 1-Click Toggle Pin Button --}}
                        <form action="{{ route('admin.testimonials.toggle-pin', $testi->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-2xs btn-press {{ $testi->is_pinned ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-ink-black hover:bg-charcoal text-white' }}"
                                    title="{{ $testi->is_pinned ? 'Lepas Pin dari Web Customer' : 'Pin Ulasan ini ke Halaman Web Customer' }}">
                                <i class="fa-solid fa-thumbtack text-xs {{ $testi->is_pinned ? 'rotate-45' : '' }}"></i>
                                <span>{{ $testi->is_pinned ? 'Lepas Pin Web' : 'Pin ke Web' }}</span>
                            </button>
                        </form>

                        {{-- Toggle Sembunyikan (Hide without delete) --}}
                        <form action="{{ route('admin.testimonials.toggle-active', $testi->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="px-3 py-2 rounded-xl border border-hairline bg-white hover:bg-warm-stone text-charcoal text-xs font-semibold transition btn-press flex items-center gap-1.5"
                                    title="{{ $testi->is_active ? 'Sembunyikan ulasan ini sementara' : 'Aktifkan kembali ulasan' }}">
                                <i class="fa-solid {{ $testi->is_active ? 'fa-eye-slash text-[10px]' : 'fa-eye text-[10px]' }}"></i>
                                <span>{{ $testi->is_active ? 'Sembunyikan' : 'Tampilkan' }}</span>
                            </button>
                        </form>

                        {{-- Delete Inappropriate / Spam Review Button --}}
                        <form action="{{ route('admin.testimonials.destroy', $testi->id) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan dari \'{{ addslashes($testi->name) }}\'? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-2 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-xs font-semibold transition btn-press flex items-center gap-1.5"
                                    title="Hapus Ulasan Ini">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                <span>Hapus</span>
                            </button>
                        </form>

                    </div>

                </div>

            </div>
        @empty
            <div class="py-16 bg-pure-white border border-hairline rounded-3xl p-8 text-center text-muted-text text-xs shadow-card">
                <div class="w-14 h-14 rounded-2xl bg-warm-stone border border-hairline flex items-center justify-center mx-auto mb-3 text-ink-black text-xl shadow-2xs">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="text-sm font-bold text-ink-black">Tidak Ada Ulasan Ditemukan</h3>
                <p class="text-xs text-muted-text max-w-md mx-auto mt-1 leading-relaxed">
                    @if($q || $filter !== 'all' || $rating)
                        Tidak ada ulasan yang cocok dengan pencarian atau filter yang Anda pilih. Silakan reset filter.
                    @else
                        Belum ada ulasan yang masuk dari pelanggan.
                    @endif
                </p>
                @if($q || $filter !== 'all' || $rating)
                    <a href="{{ route('admin.testimonials.index') }}" class="mt-4 px-4 py-2 rounded-xl bg-ink-black text-white font-bold text-xs inline-flex items-center gap-2 shadow-xs hover:bg-charcoal transition btn-press">
                        <span>Reset Semua Filter</span>
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination Bar --}}
    @if($testimonials->hasPages())
        <div class="pt-4 flex items-center justify-center">
            {{ $testimonials->links() }}
        </div>
    @endif

</div>
@endsection
