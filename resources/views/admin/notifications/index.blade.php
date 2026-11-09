@extends('layouts.admin')
@section('title', 'Pusat Notifikasi & Kejadian Kafe — Nucomu Cafe')

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
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Modul 10 &bull; Pusat Notifikasi &amp; Pemantauan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Pusat Notifikasi Kafe
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kumpulan kejadian penting: banner tersisa 30 menit / kedaluwarsa, reservasi baru, pesanan masuk, dan ulasan menunggu kurasi.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
                        title="Tandai semua notifikasi sebagai telah dibaca">
                    <i class="fa-solid fa-check-double text-emerald-600"></i>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Filter Segmented Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-hairline/60 text-xs">
        <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}"
           class="px-3.5 py-1.5 rounded-full font-bold transition flex items-center gap-2 shrink-0 {{ $filter === 'all' ? 'bg-ink-black text-white shadow-xs' : 'bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline' }}">
            <span>Semua</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'all' ? 'bg-white/20 text-white' : 'bg-warm-stone text-muted-text' }}">
                {{ $counts['all'] }}
            </span>
        </a>

        <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
           class="px-3.5 py-1.5 rounded-full font-bold transition flex items-center gap-2 shrink-0 {{ $filter === 'unread' ? 'bg-rose-600 text-white shadow-xs' : 'bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline' }}">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
            <span>Belum Dibaca</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'unread' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">
                {{ $counts['unread'] }}
            </span>
        </a>

        <a href="{{ route('admin.notifications.index', ['filter' => 'banner']) }}"
           class="px-3.5 py-1.5 rounded-full font-bold transition flex items-center gap-2 shrink-0 {{ $filter === 'banner' ? 'bg-amber-600 text-white shadow-xs' : 'bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline' }}">
            <i class="fa-solid fa-bullhorn text-[11px]"></i>
            <span>Banner Promo</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'banner' ? 'bg-white/20 text-white' : 'bg-warm-stone text-muted-text' }}">
                {{ $counts['banner'] }}
            </span>
        </a>

        <a href="{{ route('admin.notifications.index', ['filter' => 'reservation']) }}"
           class="px-3.5 py-1.5 rounded-full font-bold transition flex items-center gap-2 shrink-0 {{ $filter === 'reservation' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline' }}">
            <i class="fa-solid fa-calendar-check text-[11px]"></i>
            <span>Reservasi</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'reservation' ? 'bg-white/20 text-white' : 'bg-warm-stone text-muted-text' }}">
                {{ $counts['reservation'] }}
            </span>
        </a>

        <a href="{{ route('admin.notifications.index', ['filter' => 'order']) }}"
           class="px-3.5 py-1.5 rounded-full font-bold transition flex items-center gap-2 shrink-0 {{ $filter === 'order' ? 'bg-blue-600 text-white shadow-xs' : 'bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline' }}">
            <i class="fa-solid fa-receipt text-[11px]"></i>
            <span>Pesanan</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'order' ? 'bg-white/20 text-white' : 'bg-warm-stone text-muted-text' }}">
                {{ $counts['order'] }}
            </span>
        </a>

        <a href="{{ route('admin.notifications.index', ['filter' => 'review']) }}"
           class="px-3.5 py-1.5 rounded-full font-bold transition flex items-center gap-2 shrink-0 {{ $filter === 'review' ? 'bg-purple-600 text-white shadow-xs' : 'bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline' }}">
            <i class="fa-solid fa-star text-[11px]"></i>
            <span>Ulasan</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'review' ? 'bg-white/20 text-white' : 'bg-warm-stone text-muted-text' }}">
                {{ $counts['review'] }}
            </span>
        </a>
    </div>

    {{-- Notifications List Container --}}
    <div class="space-y-3">
        @forelse($notifications as $item)
            @php
                $bgColor = $item['is_read'] ? 'bg-pure-white/70 opacity-75' : 'bg-pure-white shadow-2xs border-l-4 border-l-ink-black';
                $iconClass = match($item['type']) {
                    'banner' => 'fa-solid fa-bullhorn text-amber-600 bg-amber-50 border-amber-200',
                    'reservation' => 'fa-solid fa-calendar-check text-emerald-600 bg-emerald-50 border-emerald-200',
                    'order' => 'fa-solid fa-receipt text-blue-600 bg-blue-50 border-blue-200',
                    'review' => 'fa-solid fa-star text-purple-600 bg-purple-50 border-purple-200',
                    default => 'fa-solid fa-bell text-charcoal bg-warm-stone border-hairline',
                };
            @endphp
            <div class="p-4 sm:p-5 rounded-2xl border border-hairline {{ $bgColor }} transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                {{-- Left Side: Icon & Details --}}
                <div class="flex items-start gap-3.5 min-w-0 flex-1">
                    <div class="w-10 h-10 rounded-2xl border flex items-center justify-center shrink-0 text-base shadow-2xs {{ $iconClass }}">
                        <i class="{{ explode(' ', $iconClass)[0] }} {{ explode(' ', $iconClass)[1] }}"></i>
                    </div>

                    <div class="space-y-1 min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-warm-stone/70 text-charcoal">
                                {{ $item['type_label'] }}
                            </span>
                            <span class="text-[11px] font-mono text-muted-text">
                                &bull; {{ $item['time_ago'] }}
                            </span>
                            @if(! $item['is_read'])
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    <span>Baru</span>
                                </span>
                            @else
                                <span class="text-[10px] text-muted-text italic">
                                    Sudah dibaca
                                </span>
                            @endif
                        </div>

                        <h3 class="text-sm font-bold text-ink-black leading-snug">
                            {{ $item['title'] }}
                        </h3>

                        <p class="text-xs text-muted-text leading-relaxed">
                            {{ $item['message'] }}
                        </p>
                    </div>
                </div>

                {{-- Right Side: Action Buttons --}}
                <div class="flex items-center gap-2 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-hairline/60 justify-end">
                    {{-- Direct Action Link to relevant module --}}
                    @if(!empty($item['action_url']))
                        <a href="{{ $item['action_url'] }}"
                           class="px-3 py-1.5 rounded-xl bg-ink-black hover:bg-charcoal text-white text-[11px] font-bold transition shadow-xs flex items-center gap-1.5 btn-press">
                            <span>{{ $item['action_label'] }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @endif

                    {{-- Toggle Read/Unread Form --}}
                    <form action="{{ route('admin.notifications.toggleRead') }}" method="POST">
                        @csrf
                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                        <button type="submit"
                                class="p-2 rounded-xl border border-hairline bg-pure-white hover:bg-warm-stone text-muted-text hover:text-ink-black text-xs transition shadow-2xs"
                                title="{{ $item['is_read'] ? 'Tandai sebagai belum dibaca' : 'Tandai sebagai sudah dibaca' }}">
                            <i class="{{ $item['is_read'] ? 'fa-regular fa-envelope' : 'fa-solid fa-check text-emerald-600' }}"></i>
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="p-12 text-center rounded-2xl bg-pure-white border border-hairline space-y-3">
                <div class="w-12 h-12 rounded-full bg-warm-stone flex items-center justify-center mx-auto text-muted-text text-lg">
                    <i class="fa-regular fa-bell-slash"></i>
                </div>
                <h3 class="text-sm font-bold text-ink-black">Tidak Ada Notifikasi</h3>
                <p class="text-xs text-muted-text max-w-sm mx-auto">
                    Semua kejadian di kategori ini telah dibaca atau tidak ada aktivitas baru saat ini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
