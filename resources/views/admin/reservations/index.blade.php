@extends('layouts.admin')
@section('title', 'Manajemen Reservasi & Form Builder — Nucomu Cafe')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12 font-sans">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-calendar-check text-[10px] text-accent-terracotta"></i>
                <span>Modul 7 — Reservasi &amp; Form Builder</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Reservasi Meja &amp; Formulir Web
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Kelola daftar reservasi pelanggan masuk, atur kustomisasi kolom formulir (Form Builder), dan tentukan nomor WhatsApp tujuan.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('reservation') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition btn-press flex items-center gap-2 shadow-xs">
                <span>Halaman Formulir Web</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
            <button type="button" onclick="openNewFieldModal()" class="px-4 py-2 rounded-xl bg-ink-black hover:bg-neutral-800 text-white text-xs font-bold transition btn-press flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kolom Formulir</span>
            </button>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl text-xs flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-xs">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-900 rounded-2xl text-xs space-y-1 shadow-xs">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
                <span>Terjadi kendala validasi:</span>
            </div>
            @foreach($errors->all() as $err)
                <div class="pl-6">• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    {{-- Statistics Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 anim-fade-up anim-delay-1">
        <div class="bg-pure-white p-4 rounded-2xl border border-hairline shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-muted-text block mb-1">Total Reservasi</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-ink-black font-mono">{{ $totalCount }}</span>
                <span class="w-8 h-8 rounded-xl bg-warm-stone flex items-center justify-center text-charcoal text-xs">
                    <i class="fa-solid fa-calendar-days"></i>
                </span>
            </div>
        </div>

        <div class="bg-pure-white p-4 rounded-2xl border border-hairline shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 block mb-1">Menunggu Konfirmasi</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-amber-600 font-mono">{{ $pendingCount }}</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock"></i>
                </span>
            </div>
        </div>

        <div class="bg-pure-white p-4 rounded-2xl border border-hairline shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">Disetujui</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-emerald-600 font-mono">{{ $confirmedCount }}</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-check"></i>
                </span>
            </div>
        </div>

        <div class="bg-pure-white p-4 rounded-2xl border border-hairline shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 block mb-1">Selesai</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-blue-600 font-mono">{{ $completedCount }}</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
        </div>

        <div class="bg-pure-white p-4 rounded-2xl border border-hairline shadow-xs col-span-2 sm:col-span-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-700 block mb-1">Dibatalkan</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-rose-600 font-mono">{{ $cancelledCount }}</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </span>
            </div>
        </div>
    </div>

    {{-- Main Module Tabs (Reservasi Masuk vs Form Builder & WhatsApp) --}}
    <div class="border-b border-hairline flex items-center gap-2 text-xs font-bold anim-fade-up">
        <button type="button" 
                id="tab-btn-reservations" 
                onclick="switchTab('reservations')" 
                class="px-5 py-3 border-b-2 border-ink-black text-ink-black flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-inbox text-xs"></i>
            <span>Daftar Reservasi Masuk</span>
            @if($pendingCount > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-amber-100 text-amber-800 border border-amber-200">{{ $pendingCount }} pending</span>
            @endif
        </button>

        <button type="button" 
                id="tab-btn-builder" 
                onclick="switchTab('builder')" 
                class="px-5 py-3 border-b-2 border-transparent text-muted-text hover:text-ink-black flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-sliders text-xs"></i>
            <span>Form Builder &amp; Nomor WhatsApp</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-warm-stone text-charcoal border border-hairline">{{ $fields->count() }} kolom</span>
        </button>
    </div>

    {{-- TAB 1: DAFTAR RESERVASI MASUK --}}
    <div id="tab-pane-reservations" class="space-y-4">
        
        {{-- Search and Status Filters --}}
        <div class="bg-pure-white border border-hairline p-4 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- Search Form --}}
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="flex-1 flex items-center gap-2 max-w-md">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="date_filter" value="{{ $dateFilter }}">
                <div class="relative w-full">
                    <input type="text" 
                           name="q" 
                           value="{{ $q }}" 
                           placeholder="Cari nama pelanggan, nomor WA, catatan..." 
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-hairline bg-warm-stone/40 text-xs text-ink-black placeholder-muted-text focus:outline-none focus:border-ink-black focus:bg-white transition-colors">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-muted-text text-xs"></i>
                </div>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-ink-black hover:bg-neutral-800 text-white text-xs font-bold transition btn-press shrink-0">
                    Cari
                </button>
                @if($q !== '' || $status !== 'all' || $dateFilter !== 'all')
                    <a href="{{ route('admin.reservations.index') }}" class="px-3 py-2.5 rounded-xl bg-warm-stone hover:bg-soft-beige text-charcoal text-xs font-bold transition btn-press shrink-0" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>

            {{-- Status & Date Quick Filters --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Status Pills --}}
                <div class="inline-flex p-1 bg-warm-stone rounded-xl border border-hairline text-xs font-semibold">
                    <a href="{{ route('admin.reservations.index', ['q' => $q, 'status' => 'all', 'date_filter' => $dateFilter]) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ $status === 'all' ? 'bg-pure-white text-ink-black shadow-2xs font-bold' : 'text-muted-text hover:text-ink-black' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.reservations.index', ['q' => $q, 'status' => 'pending', 'date_filter' => $dateFilter]) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ $status === 'pending' ? 'bg-amber-100 text-amber-900 shadow-2xs font-bold' : 'text-muted-text hover:text-ink-black' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.reservations.index', ['q' => $q, 'status' => 'confirmed', 'date_filter' => $dateFilter]) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ $status === 'confirmed' ? 'bg-emerald-100 text-emerald-900 shadow-2xs font-bold' : 'text-muted-text hover:text-ink-black' }}">
                        Disetujui
                    </a>
                    <a href="{{ route('admin.reservations.index', ['q' => $q, 'status' => 'completed', 'date_filter' => $dateFilter]) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ $status === 'completed' ? 'bg-blue-100 text-blue-900 shadow-2xs font-bold' : 'text-muted-text hover:text-ink-black' }}">
                        Selesai
                    </a>
                    <a href="{{ route('admin.reservations.index', ['q' => $q, 'status' => 'cancelled', 'date_filter' => $dateFilter]) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ $status === 'cancelled' ? 'bg-rose-100 text-rose-900 shadow-2xs font-bold' : 'text-muted-text hover:text-ink-black' }}">
                        Batal
                    </a>
                </div>

                {{-- Date Filter Dropdown --}}
                <form action="{{ route('admin.reservations.index') }}" method="GET" class="inline-block">
                    <input type="hidden" name="q" value="{{ $q }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <select name="date_filter" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-hairline bg-pure-white text-xs font-semibold text-charcoal focus:outline-none focus:border-ink-black cursor-pointer">
                        <option value="all" {{ $dateFilter === 'all' ? 'selected' : '' }}>Semua Jadwal</option>
                        <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Jadwal Hari Ini</option>
                        <option value="upcoming" {{ $dateFilter === 'upcoming' ? 'selected' : '' }}>Jadwal Mendatang</option>
                        <option value="past" {{ $dateFilter === 'past' ? 'selected' : '' }}>Riwayat Lewat</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden lg:block bg-pure-white border border-hairline rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-charcoal">
                    <thead class="bg-warm-stone/60 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                        <tr>
                            <th class="px-5 py-4">Tgl &amp; Waktu</th>
                            <th class="px-5 py-4">Nama Pelanggan</th>
                            <th class="px-5 py-4">WhatsApp Kontak</th>
                            <th class="px-5 py-4 text-center">Tamu</th>
                            <th class="px-5 py-4">Catatan / Detail</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-right">Aksi &amp; Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @forelse($reservations as $res)
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', (string)$res->phone);
                                if (str_starts_with($cleanPhone, '08')) {
                                    $cleanPhone = '628' . substr($cleanPhone, 2);
                                } elseif (str_starts_with($cleanPhone, '8')) {
                                    $cleanPhone = '628' . substr($cleanPhone, 1);
                                }
                                $dateStr = $res->date ? $res->date->format('d M Y') : '-';
                                $timeStr = $res->time ? substr((string)$res->time, 0, 5) . ' WIB' : '-';
                                $waGreet = "Halo Kak " . ($res->name ?: 'Pelanggan') . ", terima kasih telah melakukan reservasi di Nucomu Cafe Tulungagung untuk tanggal " . $dateStr . " jam " . $timeStr . " (" . $res->guests . " orang). Reservasi meja Anda telah kami terima!";
                                $waDirectUrl = "https://wa.me/" . $cleanPhone . "?text=" . rawurlencode($waGreet);
                                $hasCustomData = is_array($res->form_data) && count($res->form_data) > 0;
                            @endphp
                            <tr class="hover:bg-warm-stone/25 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-bold text-ink-black font-mono block text-sm">{{ $dateStr }}</span>
                                    <span class="text-[11px] text-muted-text font-mono">{{ $timeStr }}</span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="font-bold text-ink-black text-sm">{{ $res->name }}</div>
                                    <span class="text-[10px] text-muted-text font-mono">ID: #{{ $res->id }} • {{ $res->created_at ? $res->created_at->diffForHumans() : '' }}</span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <a href="{{ $waDirectUrl }}" target="_blank" 
                                       class="font-mono text-emerald-800 font-bold hover:underline inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs">
                                        <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                                        <span>{{ $res->phone }}</span>
                                        <span class="text-[9px]">↗</span>
                                    </a>
                                </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg bg-warm-stone font-mono font-bold text-ink-black text-xs">
                                        {{ $res->guests }} org
                                    </span>
                                </td>

                                <td class="px-5 py-4 max-w-xs">
                                    <p class="text-charcoal text-xs line-clamp-2">{{ $res->notes ?: '-' }}</p>
                                    @if($hasCustomData)
                                        <button type="button" 
                                                onclick='showReservationDetailModal(@json($res))'
                                                class="text-[11px] text-accent-terracotta font-bold hover:underline inline-flex items-center gap-1 mt-1">
                                            <i class="fa-solid fa-list-check text-[10px]"></i>
                                            <span>Lihat isian form ({{ count($res->form_data) }} item)</span>
                                        </button>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if($res->status === 'pending')
                                        <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Menunggu</span>
                                    @elseif($res->status === 'confirmed')
                                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Disetujui</span>
                                    @elseif($res->status === 'completed')
                                        <span class="bg-blue-50 text-blue-800 border border-blue-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Selesai</span>
                                    @else
                                        <span class="bg-rose-50 text-rose-800 border border-rose-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Dibatalkan</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        {{-- Detail Button --}}
                                        <button type="button" 
                                                onclick='showReservationDetailModal(@json($res))' 
                                                class="p-2 rounded-xl bg-warm-stone hover:bg-soft-beige text-charcoal border border-hairline transition btn-press" 
                                                title="Lihat Detail Formulir">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </button>

                                        {{-- Update Status Dropdown --}}
                                        <form action="{{ route('admin.reservations.updateStatus', $res->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="px-2.5 py-1.5 rounded-xl border border-hairline bg-pure-white text-[11px] font-bold text-charcoal focus:outline-none focus:border-ink-black cursor-pointer">
                                                <option value="pending" {{ $res->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $res->status === 'confirmed' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="completed" {{ $res->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                                <option value="cancelled" {{ $res->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </form>

                                        {{-- Delete Button with Confirm --}}
                                        <button type="button" 
                                                onclick="confirmDeleteReservation('{{ route('admin.reservations.destroy', $res->id) }}', '{{ addslashes($res->name) }}')"
                                                class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition btn-press" 
                                                title="Hapus Reservasi">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-muted-text">
                                    <div class="w-12 h-12 rounded-2xl bg-warm-stone flex items-center justify-center mx-auto mb-3 text-muted-text">
                                        <i class="fa-solid fa-calendar-xmark text-lg"></i>
                                    </div>
                                    <p class="font-bold text-ink-black text-sm">Tidak ada data reservasi ditemukan.</p>
                                    <p class="text-xs text-muted-text mt-1">Coba sesuaikan kata kunci pencarian atau filter status Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reservations->hasPages())
                <div class="p-4 border-t border-hairline bg-warm-stone/20">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>

        {{-- Mobile Cards View (Responsive untuk Layar HP) --}}
        <div class="lg:hidden space-y-3.5">
            @forelse($reservations as $res)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', (string)$res->phone);
                    if (str_starts_with($cleanPhone, '08')) {
                        $cleanPhone = '628' . substr($cleanPhone, 2);
                    } elseif (str_starts_with($cleanPhone, '8')) {
                        $cleanPhone = '628' . substr($cleanPhone, 1);
                    }
                    $dateStr = $res->date ? $res->date->format('d M Y') : '-';
                    $timeStr = $res->time ? substr((string)$res->time, 0, 5) . ' WIB' : '-';
                    $waGreet = "Halo Kak " . ($res->name ?: 'Pelanggan') . ", terima kasih telah melakukan reservasi di Nucomu Cafe Tulungagung untuk tanggal " . $dateStr . " jam " . $timeStr . " (" . $res->guests . " orang). Reservasi meja Anda telah kami terima!";
                    $waDirectUrl = "https://wa.me/" . $cleanPhone . "?text=" . rawurlencode($waGreet);
                    $hasCustomData = is_array($res->form_data) && count($res->form_data) > 0;
                @endphp
                <div class="bg-pure-white border border-hairline rounded-2xl p-4 shadow-xs space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-ink-black text-sm">{{ $res->name }}</h3>
                            <span class="text-[11px] text-muted-text font-mono block">{{ $dateStr }} • {{ $timeStr }}</span>
                        </div>
                        <div>
                            @if($res->status === 'pending')
                                <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Menunggu</span>
                            @elseif($res->status === 'confirmed')
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Disetujui</span>
                            @elseif($res->status === 'completed')
                                <span class="bg-blue-50 text-blue-800 border border-blue-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Selesai</span>
                            @else
                                <span class="bg-rose-50 text-rose-800 border border-rose-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Batal</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1 border-t border-hairline">
                        <span class="text-muted-text">Tamu: <strong class="text-ink-black font-mono">{{ $res->guests }} orang</strong></span>
                        <a href="{{ $waDirectUrl }}" target="_blank" class="text-emerald-800 font-bold inline-flex items-center gap-1 font-mono text-[11px]">
                            <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                            <span>{{ $res->phone }}</span>
                        </a>
                    </div>

                    @if($res->notes)
                        <div class="text-xs text-charcoal bg-warm-stone/40 p-2.5 rounded-xl border border-hairline/60">
                            {{ $res->notes }}
                        </div>
                    @endif

                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-hairline">
                        <button type="button" 
                                onclick='showReservationDetailModal(@json($res))'
                                class="px-3 py-1.5 rounded-xl bg-warm-stone hover:bg-soft-beige text-charcoal font-bold text-xs flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                            <span>Detail Isian</span>
                        </button>

                        <div class="flex items-center gap-1.5">
                            <form action="{{ route('admin.reservations.updateStatus', $res->id) }}" method="POST" class="inline-block">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="px-2 py-1.5 rounded-xl border border-hairline bg-pure-white text-[11px] font-bold text-charcoal focus:outline-none focus:border-ink-black">
                                    <option value="pending" {{ $res->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $res->status === 'confirmed' ? 'selected' : '' }}>Setujui</option>
                                    <option value="completed" {{ $res->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $res->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </form>

                            <button type="button" 
                                    onclick="confirmDeleteReservation('{{ route('admin.reservations.destroy', $res->id) }}', '{{ addslashes($res->name) }}')"
                                    class="p-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-xs">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-pure-white border border-hairline rounded-2xl p-8 text-center text-muted-text">
                    <p class="font-bold text-ink-black text-sm">Tidak ada data reservasi ditemukan.</p>
                </div>
            @endforelse

            @if($reservations->hasPages())
                <div class="p-3 bg-pure-white border border-hairline rounded-2xl">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- TAB 2: FORM BUILDER & WHATSAPP SETTINGS --}}
    <div id="tab-pane-builder" class="space-y-6 hidden">
        
        {{-- Section 1: Pengaturan Nomor WhatsApp Tujuan --}}
        <div class="bg-pure-white border border-hairline rounded-3xl p-5 sm:p-7 shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-hairline">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-ink-black text-base sm:text-lg">Nomor WhatsApp Tujuan Reservasi</h2>
                        <p class="text-xs text-muted-text mt-0.5">
                            Pesan dari formulir reservasi web customer akan otomatis dialihkan ke nomor WhatsApp ini via wa.me.
                        </p>
                    </div>
                </div>
                <div class="text-xs font-mono bg-warm-stone px-3.5 py-1.5 rounded-full border border-hairline text-charcoal">
                    Aktif: <strong class="text-emerald-800">+{{ $destinationWhatsapp }}</strong>
                </div>
            </div>

            <form action="{{ route('admin.reservations.whatsapp.update') }}" method="POST" class="pt-5 space-y-4 max-w-xl">
                @csrf
                <div>
                    <label for="whatsapp_number" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                        Nomor WhatsApp Cafe <span class="text-red-600">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text" 
                               name="whatsapp_number" 
                               id="whatsapp_number" 
                               value="{{ old('whatsapp_number', $destinationWhatsapp) }}" 
                               required
                               placeholder="Contoh: 081234567890 atau 6281234567890" 
                               class="flex-1 px-4 py-2.5 rounded-xl border border-hairline bg-warm-stone/30 text-xs font-mono text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition-colors">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-ink-black hover:bg-neutral-800 text-white text-xs font-bold transition btn-press shrink-0">
                            Simpan Nomor
                        </button>
                    </div>
                    <p class="text-[11px] text-muted-text mt-1.5">
                        💡 Nomor yang diawali <code>08...</code> atau <code>8...</code> akan otomatis dinormalisasi ke format internasional <code>628...</code> tanpa perlu edit kode program.
                    </p>
                </div>
            </form>
        </div>

        {{-- Section 2: Form Builder Manager (Kustomisasi Kolom Formulir) --}}
        <div class="bg-pure-white border border-hairline rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-accent-terracotta/10 border border-accent-terracotta/20 text-accent-terracotta text-[10px] font-bold uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-shapes"></i>
                        <span>Form Builder</span>
                    </div>
                    <h2 class="font-bold text-ink-black text-base sm:text-lg">Susunan Kolom Formulir Reservasi</h2>
                    <p class="text-xs text-muted-text mt-0.5">
                        Tambah, edit, urutkan, atau sembunyikan kolom formulir. Tampilan web customer akan otomatis menyesuaikan susunan di bawah ini.
                    </p>
                </div>
                <button type="button" 
                        onclick="openNewFieldModal()" 
                        class="px-4 py-2.5 rounded-xl bg-ink-black hover:bg-neutral-800 text-white text-xs font-bold transition btn-press flex items-center gap-2 shadow-xs shrink-0">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>+ Tambah Kolom Baru</span>
                </button>
            </div>

            {{-- Fields Table --}}
            <div class="overflow-x-auto border border-hairline rounded-2xl">
                <table class="w-full text-left text-xs text-charcoal">
                    <thead class="bg-warm-stone/60 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                        <tr>
                            <th class="px-4 py-3 text-center w-16">Urutan</th>
                            <th class="px-4 py-3">Judul Kolom (Label)</th>
                            <th class="px-4 py-3">Tipe Input</th>
                            <th class="px-4 py-3">Placeholder / Opsi</th>
                            <th class="px-4 py-3 text-center">Wajib?</th>
                            <th class="px-4 py-3 text-center">Tampil di Web?</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @forelse($fields as $f)
                            <tr class="hover:bg-warm-stone/20 transition-colors {{ !$f->is_active ? 'opacity-60 bg-warm-stone/10' : '' }}">
                                <td class="px-4 py-3 text-center font-mono font-bold text-ink-black">
                                    {{ $f->sort_order }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="font-bold text-ink-black text-xs block">{{ $f->label }}</span>
                                    <span class="text-[10px] text-muted-text font-mono">key: {{ $f->field_key }}</span>
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $typeBadge = match($f->type) {
                                            'text' => 'bg-stone-100 text-stone-800 border-stone-200',
                                            'tel' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            'date' => 'bg-blue-50 text-blue-800 border-blue-200',
                                            'time' => 'bg-purple-50 text-purple-800 border-purple-200',
                                            'number' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'textarea' => 'bg-stone-100 text-stone-800 border-stone-200',
                                            'photo' => 'bg-rose-50 text-rose-800 border-rose-200',
                                            'checkbox' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                            default => 'bg-warm-stone text-charcoal border-hairline',
                                        };
                                        $typeIcon = match($f->type) {
                                            'text' => 'fa-font',
                                            'tel' => 'fa-phone',
                                            'date' => 'fa-calendar',
                                            'time' => 'fa-clock',
                                            'number' => 'fa-hashtag',
                                            'textarea' => 'fa-align-left',
                                            'photo' => 'fa-image',
                                            'checkbox' => 'fa-square-check',
                                            default => 'fa-asterisk',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $typeBadge }}">
                                        <i class="fa-solid {{ $typeIcon }} text-[9px]"></i>
                                        <span>{{ ucfirst($f->type) }}</span>
                                    </span>
                                </td>

                                <td class="px-4 py-3 max-w-xs">
                                    @if($f->type === 'checkbox' && !empty($f->options))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($f->options as $opt)
                                                <span class="text-[10px] px-2 py-0.5 rounded bg-warm-stone border border-hairline font-mono">{{ $opt }}</span>
                                            @endforeach
                                        </div>
                                    @elseif($f->type === 'photo')
                                        <span class="text-[11px] text-muted-text italic">Format JPG, PNG, WebP (Maks 2 MB)</span>
                                    @else
                                        <span class="text-xs text-charcoal font-mono">{{ $f->placeholder ?: '-' }}</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if($f->is_required)
                                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-200">Wajib</span>
                                    @else
                                        <span class="text-[10px] font-medium text-muted-text bg-warm-stone px-2 py-0.5 rounded-full border border-hairline">Opsional</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('admin.reservations.fields.toggle', $f->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition {{ $f->is_active ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-warm-stone text-muted-text border-hairline hover:bg-soft-beige' }}"
                                                title="Klik untuk ubah visibilitas kolom">
                                            {{ $f->is_active ? '✓ Aktif' : '✕ Tersembunyi' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" 
                                                onclick='openEditFieldModal(@json($f))' 
                                                class="p-1.5 rounded-lg bg-warm-stone hover:bg-soft-beige text-charcoal border border-hairline transition btn-press" 
                                                title="Edit Kolom">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>

                                        <button type="button" 
                                                onclick="confirmDeleteField('{{ route('admin.reservations.fields.destroy', $f->id) }}', '{{ addslashes($f->label) }}')"
                                                class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition btn-press" 
                                                title="Hapus Kolom">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-muted-text">
                                    Belum ada kolom formulir yang terdaftar. Klik "+ Tambah Kolom Baru" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3.5 bg-warm-stone/40 border border-hairline rounded-2xl text-xs text-charcoal flex items-start gap-2.5">
                <i class="fa-solid fa-shield-halved text-emerald-700 mt-0.5 text-sm"></i>
                <div class="leading-relaxed">
                    <strong>Jaminan Integritas Data Lama:</strong> Mengubah nama, urutan, atau menghapus kolom tidak akan merusak data reservasi pelanggan lama yang sudah tersimpan di database.
                </div>
            </div>
        </div>

    </div>

</div>

{{-- MODAL 1: LIHAT DETAIL FORMULIR RESERVASI LENGKAP --}}
<div id="modal-reservation-detail" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5 hidden opacity-0 transition-opacity duration-200">
    <div class="bg-pure-white rounded-3xl max-w-2xl w-full p-5 sm:p-7 shadow-2xl border border-hairline relative max-h-[90vh] flex flex-col font-sans" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-4 border-b border-hairline shrink-0">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-muted-text block mb-0.5">Rincian Formulir Reservasi</span>
                <h3 id="detail-modal-name" class="text-xl font-bold text-ink-black font-sans">Detail Pelanggan</h3>
            </div>
            <button type="button" onclick="closeReservationDetailModal()" class="w-8 h-8 rounded-full bg-warm-stone hover:bg-soft-beige text-charcoal flex items-center justify-center text-sm transition">
                ✕
            </button>
        </div>

        <div class="overflow-y-auto flex-1 py-4 space-y-4 pr-1">
            {{-- Quick Summary Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                <div class="p-3 bg-warm-stone/50 rounded-xl border border-hairline">
                    <span class="text-[10px] text-muted-text uppercase font-bold block mb-0.5">Status</span>
                    <span id="detail-modal-status-badge" class="font-bold text-ink-black">-</span>
                </div>
                <div class="p-3 bg-warm-stone/50 rounded-xl border border-hairline">
                    <span class="text-[10px] text-muted-text uppercase font-bold block mb-0.5">Tanggal Acara</span>
                    <span id="detail-modal-date" class="font-bold text-ink-black font-mono">-</span>
                </div>
                <div class="p-3 bg-warm-stone/50 rounded-xl border border-hairline">
                    <span class="text-[10px] text-muted-text uppercase font-bold block mb-0.5">Waktu</span>
                    <span id="detail-modal-time" class="font-bold text-ink-black font-mono">-</span>
                </div>
                <div class="p-3 bg-warm-stone/50 rounded-xl border border-hairline">
                    <span class="text-[10px] text-muted-text uppercase font-bold block mb-0.5">Jumlah Tamu</span>
                    <span id="detail-modal-guests" class="font-bold text-ink-black font-mono">-</span>
                </div>
            </div>

            {{-- Dynamic Form Data Table --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-charcoal mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-clipboard-list text-accent-terracotta"></i>
                    <span>Isian Formulir Lengkap Customer</span>
                </h4>
                <div class="border border-hairline rounded-2xl overflow-hidden bg-white">
                    <table class="w-full text-left text-xs text-charcoal">
                        <thead class="bg-warm-stone/50 text-[10px] font-mono uppercase text-muted-text border-b border-hairline">
                            <tr>
                                <th class="px-4 py-2.5">Kolom Formulir</th>
                                <th class="px-4 py-2.5">Jawaban / Berkas Terlampir</th>
                            </tr>
                        </thead>
                        <tbody id="detail-modal-form-items" class="divide-y divide-hairline">
                            {{-- Injected dynamically --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Notes Summary --}}
            <div id="detail-modal-notes-box" class="p-3.5 bg-warm-stone/40 border border-hairline rounded-2xl text-xs space-y-1">
                <span class="font-bold text-ink-black block text-[11px] uppercase tracking-wider">Catatan Tambahan Customer:</span>
                <p id="detail-modal-notes-text" class="text-charcoal leading-relaxed whitespace-pre-line">-</p>
            </div>
        </div>

        <div class="pt-4 border-t border-hairline flex flex-wrap items-center justify-between gap-3 shrink-0">
            <a id="detail-modal-wa-btn" href="#" target="_blank" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Hubungi via WhatsApp</span>
            </a>

            <div class="flex items-center gap-2">
                <form id="detail-modal-status-form" method="POST" class="inline-flex items-center gap-1.5">
                    @csrf
                    <span class="text-xs text-muted-text font-bold">Ubah Status:</span>
                    <select name="status" id="detail-modal-status-select" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-hairline bg-pure-white text-xs font-bold text-charcoal focus:outline-none focus:border-ink-black">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Disetujui</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </form>
                <button type="button" onclick="closeReservationDetailModal()" class="px-4 py-2 rounded-xl bg-warm-stone hover:bg-soft-beige text-charcoal text-xs font-bold transition">
                    Tutup
                </button>
            </div>
        </div>

    </div>
</div>

{{-- MODAL 2: TAMBAH / EDIT KOLOM FORM BUILDER --}}
<div id="modal-field-builder" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5 hidden opacity-0 transition-opacity duration-200">
    <div class="bg-pure-white rounded-3xl max-w-lg w-full p-5 sm:p-7 shadow-2xl border border-hairline relative max-h-[90vh] flex flex-col font-sans" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-4 border-b border-hairline shrink-0">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-muted-text block mb-0.5">Form Builder</span>
                <h3 id="builder-modal-title" class="text-lg font-bold text-ink-black font-sans">Tambah Kolom Baru</h3>
            </div>
            <button type="button" onclick="closeFieldModal()" class="w-8 h-8 rounded-full bg-warm-stone hover:bg-soft-beige text-charcoal flex items-center justify-center text-sm transition">
                ✕
            </button>
        </div>

        <form id="builder-modal-form" action="{{ route('admin.reservations.fields.store') }}" method="POST" class="overflow-y-auto flex-1 py-4 space-y-4 pr-1">
            @csrf

            <div>
                <label for="modal-field-label" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                    Judul Kolom (Label) <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       name="label" 
                       id="modal-field-label" 
                       required 
                       placeholder="Contoh: Pilihan Kursi Bayi, Foto Acuan Dekorasi" 
                       class="w-full px-4 py-2.5 rounded-xl border border-hairline bg-warm-stone/30 text-xs text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label for="modal-field-type" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                        Tipe Kolom <span class="text-red-600">*</span>
                    </label>
                    <select name="type" 
                            id="modal-field-type" 
                            onchange="handleFieldTypeChange(this.value)"
                            required
                            class="w-full px-3 py-2.5 rounded-xl border border-hairline bg-warm-stone/30 text-xs font-semibold text-charcoal focus:outline-none focus:border-ink-black cursor-pointer">
                        <option value="text">Teks Singkat (text)</option>
                        <option value="tel">Nomor Telepon / WA (tel)</option>
                        <option value="number">Angka (number)</option>
                        <option value="date">Tanggal (date)</option>
                        <option value="time">Waktu / Jam (time)</option>
                        <option value="textarea">Teks Panjang (textarea)</option>
                        <option value="photo">Upload Foto (photo)</option>
                        <option value="checkbox">Pilihan Checkbox (checkbox)</option>
                    </select>
                </div>

                <div>
                    <label for="modal-field-order" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                        Urutan Tampilan
                    </label>
                    <input type="number" 
                           name="sort_order" 
                           id="modal-field-order" 
                           placeholder="Otomatis" 
                           class="w-full px-4 py-2.5 rounded-xl border border-hairline bg-warm-stone/30 text-xs font-mono text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition-colors">
                </div>
            </div>

            <div id="wrapper-field-placeholder">
                <label for="modal-field-placeholder" class="block text-xs font-bold uppercase tracking-wider text-charcoal mb-1.5">
                    Contoh Isi (Placeholder)
                </label>
                <input type="text" 
                       name="placeholder" 
                       id="modal-field-placeholder" 
                       placeholder="Contoh: Khanzahamida" 
                       class="w-full px-4 py-2.5 rounded-xl border border-hairline bg-warm-stone/30 text-xs text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition-colors">
            </div>

            <div id="wrapper-field-checkbox" class="hidden space-y-1.5">
                <label for="modal-field-options" class="block text-xs font-bold uppercase tracking-wider text-charcoal">
                    Daftar Pilihan Opsi Checkbox <span class="text-red-600">*</span>
                </label>
                <p class="text-[11px] text-muted-text">Tuliskan minimal 2 pilihan, satu opsi per baris:</p>
                <textarea name="checkbox_options" 
                          id="modal-field-options" 
                          rows="4" 
                          placeholder="Pilihan 1&#10;Pilihan 2&#10;Pilihan 3" 
                          class="w-full px-4 py-2.5 rounded-xl border border-hairline bg-warm-stone/30 text-xs font-mono text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition-colors"></textarea>
            </div>

            <div id="wrapper-field-photo-info" class="hidden p-3 bg-amber-50/70 border border-amber-200 rounded-xl text-xs text-amber-900 leading-relaxed">
                📸 <strong>Ketentuan Foto:</strong> Format foto yang diterima customer adalah JPG, PNG, atau WebP dengan batas ukuran maksimal 2 MB. Foto disimpan di server dan tautan link disertakan di chat WhatsApp.
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" 
                           name="is_required" 
                           id="modal-field-required" 
                           value="1" 
                           class="w-4 h-4 rounded text-ink-black border-stone-300 focus:ring-ink-black">
                    <span class="text-xs font-bold text-ink-black">Kolom ini Wajib Diisi oleh Customer</span>
                </label>
            </div>

            <div class="pt-4 border-t border-hairline flex items-center justify-end gap-2 shrink-0">
                <button type="button" onclick="closeFieldModal()" class="px-4 py-2.5 rounded-xl bg-warm-stone hover:bg-soft-beige text-charcoal text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" id="builder-modal-submit-btn" class="px-5 py-2.5 rounded-xl bg-ink-black hover:bg-neutral-800 text-white text-xs font-bold transition btn-press">
                    Simpan Kolom
                </button>
            </div>
        </form>

    </div>
</div>

{{-- MODAL 3: KONFIRMASI HAPUS --}}
<div id="modal-delete-confirm" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5 hidden opacity-0 transition-opacity duration-200">
    <div class="bg-pure-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-hairline text-center space-y-4 font-sans" onclick="event.stopPropagation()">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center mx-auto text-lg">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="font-bold text-ink-black text-base" id="delete-confirm-title">Konfirmasi Hapus</h3>
            <p class="text-xs text-muted-text mt-1 leading-relaxed" id="delete-confirm-message">
                Apakah Anda yakin ingin menghapus data ini?
            </p>
        </div>
        <form id="delete-confirm-form" method="POST" class="flex items-center justify-center gap-2 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteConfirmModal()" class="px-4 py-2 rounded-xl bg-warm-stone hover:bg-soft-beige text-charcoal text-xs font-bold transition">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition btn-press">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

{{-- JavaScript Interactivity --}}
<script>
    // Tab Switching
    function switchTab(tab) {
        const paneRes = document.getElementById('tab-pane-reservations');
        const paneBuilder = document.getElementById('tab-pane-builder');
        const btnRes = document.getElementById('tab-btn-reservations');
        const btnBuilder = document.getElementById('tab-btn-builder');

        if (tab === 'builder') {
            paneRes.classList.add('hidden');
            paneBuilder.classList.remove('hidden');
            btnRes.className = 'px-5 py-3 border-b-2 border-transparent text-muted-text hover:text-ink-black flex items-center gap-2 transition-colors';
            btnBuilder.className = 'px-5 py-3 border-b-2 border-ink-black text-ink-black flex items-center gap-2 transition-colors';
            history.replaceState(null, null, '#builder');
        } else {
            paneBuilder.classList.add('hidden');
            paneRes.classList.remove('hidden');
            btnBuilder.className = 'px-5 py-3 border-b-2 border-transparent text-muted-text hover:text-ink-black flex items-center gap-2 transition-colors';
            btnRes.className = 'px-5 py-3 border-b-2 border-ink-black text-ink-black flex items-center gap-2 transition-colors';
            history.replaceState(null, null, '#reservations');
        }
    }

    // Auto open tab based on URL hash
    document.addEventListener('DOMContentLoaded', () => {
        if (window.location.hash === '#builder') {
            switchTab('builder');
        }
    });

    // 1. Reservation Detail Modal Logic
    function showReservationDetailModal(reservation) {
        if (!reservation) return;

        document.getElementById('detail-modal-name').innerText = reservation.name || 'Pelanggan';
        document.getElementById('detail-modal-date').innerText = reservation.date ? reservation.date.split('T')[0] : '-';
        document.getElementById('detail-modal-time').innerText = reservation.time ? reservation.time.substring(0, 5) + ' WIB' : '-';
        document.getElementById('detail-modal-guests').innerText = (reservation.guests || 2) + ' Orang';

        // Status Badge
        const statusEl = document.getElementById('detail-modal-status-badge');
        const st = reservation.status;
        if (st === 'pending') {
            statusEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 inline-block';
            statusEl.innerText = 'Menunggu';
        } else if (st === 'confirmed') {
            statusEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 inline-block';
            statusEl.innerText = 'Disetujui';
        } else if (st === 'completed') {
            statusEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200 inline-block';
            statusEl.innerText = 'Selesai';
        } else {
            statusEl.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200 inline-block';
            statusEl.innerText = 'Dibatalkan';
        }

        // WhatsApp Button
        const cleanPhone = (reservation.phone || '').replace(/[^0-9]/g, '');
        let waNumber = cleanPhone;
        if (waNumber.startsWith('08')) waNumber = '628' + waNumber.substring(2);
        else if (waNumber.startsWith('8')) waNumber = '628' + waNumber.substring(1);
        const waGreet = encodeURIComponent(`Halo Kak ${reservation.name || 'Pelanggan'}, kami dari Nucomu Cafe Tulungagung terkait reservasi meja Anda.`);
        document.getElementById('detail-modal-wa-btn').href = `https://wa.me/${waNumber}?text=${waGreet}`;

        // Status Form
        document.getElementById('detail-modal-status-form').action = `/admin/reservations/${reservation.id}/status`;
        document.getElementById('detail-modal-status-select').value = reservation.status || 'pending';

        // Render Dynamic Form Data
        const tbody = document.getElementById('detail-modal-form-items');
        tbody.innerHTML = '';

        if (Array.isArray(reservation.form_data) && reservation.form_data.length > 0) {
            reservation.form_data.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-warm-stone/20';

                const tdLabel = document.createElement('td');
                tdLabel.className = 'px-4 py-3 font-bold text-ink-black text-xs align-top w-1/3';
                tdLabel.innerText = item.label || item.key || '-';

                const tdVal = document.createElement('td');
                tdVal.className = 'px-4 py-3 text-xs align-top';

                if (item.type === 'photo') {
                    if (item.url) {
                        tdVal.innerHTML = `
                            <div class="space-y-1.5">
                                <a href="${item.url}" target="_blank" class="inline-block border border-hairline rounded-xl overflow-hidden shadow-2xs hover:opacity-90">
                                    <img src="${item.url}" alt="${item.label}" class="w-32 h-24 object-cover">
                                </a>
                                <a href="${item.url}" target="_blank" class="block text-[11px] text-accent-terracotta font-bold hover:underline">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i> Buka Foto Ukuran Penuh
                                </a>
                            </div>
                        `;
                    } else {
                        tdVal.innerHTML = '<span class="text-muted-text italic">Tidak melampirkan foto</span>';
                    }
                } else if (item.type === 'checkbox') {
                    const vals = Array.isArray(item.value) ? item.value : (item.value ? [item.value] : []);
                    if (vals.length > 0) {
                        tdVal.innerHTML = '<div class="flex flex-wrap gap-1">' + vals.map(v => `<span class="px-2 py-0.5 rounded bg-warm-stone border border-hairline font-mono text-[11px]">${v}</span>`).join('') + '</div>';
                    } else {
                        tdVal.innerHTML = '<span class="text-muted-text italic">-</span>';
                    }
                } else {
                    const textVal = Array.isArray(item.value) ? item.value.join(', ') : (item.value || '-');
                    tdVal.innerText = textVal;
                }

                tr.appendChild(tdLabel);
                tr.appendChild(tdVal);
                tbody.appendChild(tr);
            });
        } else {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="2" class="px-4 py-4 text-center text-muted-text italic">Tidak ada rincian data formulir kustom.</td>';
            tbody.appendChild(tr);
        }

        // Notes box
        const notesBox = document.getElementById('detail-modal-notes-box');
        const notesText = document.getElementById('detail-modal-notes-text');
        if (reservation.notes && reservation.notes.trim()) {
            notesText.innerText = reservation.notes;
            notesBox.classList.remove('hidden');
        } else {
            notesBox.classList.add('hidden');
        }

        // Open modal
        const modal = document.getElementById('modal-reservation-detail');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function closeReservationDetailModal() {
        const modal = document.getElementById('modal-reservation-detail');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    // 2. Form Builder Modal Logic
    function openNewFieldModal() {
        const form = document.getElementById('builder-modal-form');
        form.action = "{{ route('admin.reservations.fields.store') }}";
        // remove method put if any
        const methodPut = form.querySelector('input[name="_method"]');
        if (methodPut) methodPut.remove();

        document.getElementById('builder-modal-title').innerText = 'Tambah Kolom Formulir Baru';
        document.getElementById('builder-modal-submit-btn').innerText = 'Tambah Kolom';
        document.getElementById('modal-field-label').value = '';
        document.getElementById('modal-field-type').value = 'text';
        document.getElementById('modal-field-order').value = '';
        document.getElementById('modal-field-placeholder').value = '';
        document.getElementById('modal-field-options').value = '';
        document.getElementById('modal-field-required').checked = false;

        handleFieldTypeChange('text');

        const modal = document.getElementById('modal-field-builder');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function openEditFieldModal(field) {
        if (!field) return;

        const form = document.getElementById('builder-modal-form');
        form.action = `/admin/reservations/fields/${field.id}`;
        // add method PUT
        let methodPut = form.querySelector('input[name="_method"]');
        if (!methodPut) {
            methodPut = document.createElement('input');
            methodPut.type = 'hidden';
            methodPut.name = '_method';
            methodPut.value = 'PUT';
            form.appendChild(methodPut);
        }

        document.getElementById('builder-modal-title').innerText = `Edit Kolom: ${field.label}`;
        document.getElementById('builder-modal-submit-btn').innerText = 'Perbarui Kolom';
        document.getElementById('modal-field-label').value = field.label || '';
        document.getElementById('modal-field-type').value = field.type || 'text';
        document.getElementById('modal-field-order').value = field.sort_order || '';
        document.getElementById('modal-field-placeholder').value = field.placeholder || '';
        document.getElementById('modal-field-required').checked = Boolean(field.is_required);

        if (Array.isArray(field.options)) {
            document.getElementById('modal-field-options').value = field.options.join("\n");
        } else {
            document.getElementById('modal-field-options').value = '';
        }

        handleFieldTypeChange(field.type);

        const modal = document.getElementById('modal-field-builder');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function closeFieldModal() {
        const modal = document.getElementById('modal-field-builder');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    function handleFieldTypeChange(type) {
        const wrapPlaceholder = document.getElementById('wrapper-field-placeholder');
        const wrapCheckbox = document.getElementById('wrapper-field-checkbox');
        const wrapPhoto = document.getElementById('wrapper-field-photo-info');

        if (type === 'checkbox') {
            wrapPlaceholder.classList.add('hidden');
            wrapPhoto.classList.add('hidden');
            wrapCheckbox.classList.remove('hidden');
            document.getElementById('modal-field-options').required = true;
        } else if (type === 'photo') {
            wrapPlaceholder.classList.add('hidden');
            wrapCheckbox.classList.add('hidden');
            wrapPhoto.classList.remove('hidden');
            document.getElementById('modal-field-options').required = false;
        } else {
            wrapPlaceholder.classList.remove('hidden');
            wrapCheckbox.classList.add('hidden');
            wrapPhoto.classList.add('hidden');
            document.getElementById('modal-field-options').required = false;
        }
    }

    // 3. Delete Confirmation Dialogs
    function confirmDeleteReservation(actionUrl, name) {
        document.getElementById('delete-confirm-title').innerText = 'Hapus Reservasi';
        document.getElementById('delete-confirm-message').innerText = `Apakah Anda yakin ingin menghapus data reservasi dari "${name}"? Seluruh berkas atau foto yang pernah diunggah juga akan dibersihkan dari server.`;
        document.getElementById('delete-confirm-form').action = actionUrl;

        const modal = document.getElementById('modal-delete-confirm');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function confirmDeleteField(actionUrl, label) {
        document.getElementById('delete-confirm-title').innerText = 'Hapus Kolom Formulir';
        document.getElementById('delete-confirm-message').innerText = `Apakah Anda yakin ingin menghapus kolom formulir "${label}"? Kolom ini tidak akan lagi tampil di web customer, namun data isian dari reservasi lama tetap tersimpan aman di database.`;
        document.getElementById('delete-confirm-form').action = actionUrl;

        const modal = document.getElementById('modal-delete-confirm');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function closeDeleteConfirmModal() {
        const modal = document.getElementById('modal-delete-confirm');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
</script>
@endsection
