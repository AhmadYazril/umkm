@extends('layouts.admin')
@section('title', 'Dashboard Overview — Nucomu Cafe Tulungagung')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Nucomu Cafe &bull; Operasional Terkini</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Ringkasan Dashboard
            </h1>
            <p class="text-xs text-muted-text mt-1 leading-relaxed">
                Pantau pesanan pelanggan, pendapatan hari ini, dan aktivitas operasional secara real-time.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" onclick="toggleAdminNotificationDropdown(event)" class="px-3.5 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition-all shadow-xs flex items-center gap-2 btn-press" title="Buka Kabar &amp; Pesan Kafe">
                <div class="relative flex items-center justify-center">
                    <i class="fa-regular fa-bell text-xs"></i>
                    <span id="dashboardMsgPing" class="w-2 h-2 rounded-full bg-emerald-400 animate-ping absolute -top-1 -right-1 {{ ($pendingNotificationCount ?? 0) > 0 ? '' : 'hidden' }}"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 absolute -top-1 -right-1"></span>
                </div>
                <span>Kabar &amp; Pesan</span>
                <span id="dashboardMsgBadge" class="px-1.5 py-0.5 rounded-full bg-white text-ink-black text-[9.5px] font-mono font-bold leading-none {{ ($pendingNotificationCount ?? 0) > 0 ? '' : 'hidden' }}">
                    {{ $pendingNotificationCount ?? 0 }}
                </span>
            </button>
            <a href="{{ route('admin.orders.scan') }}" class="px-4 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone text-ink-black border border-hairline font-bold text-xs uppercase tracking-wider transition-all shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-qrcode text-xs"></i>
                <span>Scan QR Kasir</span>
            </a>
            <a href="{{ route('admin.menus.create') }}" class="px-4 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone text-ink-black border border-hairline font-bold text-xs uppercase transition-all shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Menu</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-2.5 rounded-xl bg-warm-stone hover:bg-white text-ink-black border border-hairline text-xs font-semibold transition-all" title="Buka Beranda Pelanggan">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>
    </div>

    {{-- Stats Cards Grid (4 Cards with Monochrome Editorial Aesthetics & Hover Lift) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Card 1: Omset Hari Ini --}}
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card card-lift relative overflow-hidden group reveal-on-scroll anim-fade-up anim-delay-1">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-muted-text font-semibold">Omset Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                    <i class="fa-solid fa-rupiah-sign text-xs"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-ink-black font-mono tracking-tight">
                Rp {{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold">
                <i class="fa-solid fa-circle-check text-[10px]"></i>
                <span>Terverifikasi Lunas (Kasir &amp; Transfer)</span>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-warm-stone/40 rounded-full blur-xl pointer-events-none"></div>
        </div>

        {{-- Card 2: Total Pesanan Hari Ini --}}
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card card-lift relative overflow-hidden group reveal-on-scroll anim-fade-up anim-delay-2">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-muted-text font-semibold">Pesanan Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                    <i class="fa-solid fa-receipt text-xs"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-ink-black font-mono tracking-tight">
                {{ $stats['orders_today'] ?? 0 }} <span class="text-xs font-normal text-muted-text font-sans">transaksi</span>
            </div>
            <div class="mt-3 text-[11px] text-muted-text flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-charcoal"></span>
                <span>Dine-in, Takeaway &amp; Kurir Zendo</span>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-warm-stone/40 rounded-full blur-xl pointer-events-none"></div>
        </div>

        {{-- Card 3: Perlu Diproses --}}
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card card-lift relative overflow-hidden group reveal-on-scroll anim-fade-up anim-delay-3">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-muted-text font-semibold">Perlu Diproses</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-xs"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-ink-black font-mono tracking-tight">
                {{ $stats['pending_orders'] ?? 0 }} <span class="text-xs font-normal text-muted-text font-sans">pesanan</span>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="mt-3 inline-flex items-center gap-1 text-[11px] font-bold text-ink-black hover:text-accent-terracotta transition-colors">
                <span>Buka antrean pending</span>
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-100/30 rounded-full blur-xl pointer-events-none"></div>
        </div>

        {{-- Card 4: Total Menu Aktif --}}
        <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card card-lift relative overflow-hidden group reveal-on-scroll anim-fade-up anim-delay-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-muted-text font-semibold">Menu Tersedia</span>
                <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                    <i class="fa-solid fa-mug-hot text-xs"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-ink-black font-mono tracking-tight">
                {{ $stats['total_menus'] ?? 0 }} <span class="text-xs font-normal text-muted-text font-sans">pilihan</span>
            </div>
            <a href="{{ route('admin.menus.index') }}" class="mt-3 inline-flex items-center gap-1 text-[11px] font-bold text-ink-black hover:text-accent-terracotta transition-colors">
                <span>Kelola katalog menu</span>
                <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-warm-stone/40 rounded-full blur-xl pointer-events-none"></div>
        </div>

    </div>

    {{-- Main 2-Column Section: Pesanan Terbaru (Left) & Menu Terlaris + Quick Tools (Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- Left: Tabel Pesanan Terbaru (8 Cols) --}}
        <div class="lg:col-span-8 bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl overflow-hidden shadow-card reveal-on-scroll">
            <div class="p-5 sm:p-6 border-b border-hairline flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                        <i class="fa-solid fa-receipt text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-ink-black">Pesanan Terbaru Masuk</h2>
                        <p class="text-[11px] text-muted-text">Transaksi dine-in, takeaway, dan slip pesanan scan QR.</p>
                    </div>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-ink-black hover:text-accent-terracotta transition-colors flex items-center gap-1.5 btn-press">
                    <span>Lihat Semua</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-charcoal">
                    <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                        <tr>
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Pelanggan</th>
                            <th class="px-5 py-3.5">Tipe</th>
                            <th class="px-5 py-3.5">Total</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-warm-stone/30 transition-colors">
                                <td class="px-5 py-4 font-mono">
                                    <span class="font-bold text-ink-black block">{{ $order->code }}</span>
                                    <span class="text-[10px] text-muted-text">{{ $order->created_at->format('H:i') }} WIB</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-ink-black block">{{ $order->customer_name }}</span>
                                    <span class="text-[10px] text-muted-text font-mono">{{ $order->customer_phone && $order->customer_phone !== '-' ? $order->customer_phone : 'Scan Meja' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if(str_starts_with((string) $order->notes, '[KURIR ZENDO]'))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold">
                                            <i class="fa-brands fa-whatsapp"></i> Kurir Zendo
                                        </span>
                                    @else
                                        <span class="uppercase text-[10px] font-bold text-charcoal bg-warm-stone px-2 py-0.5 rounded-full border border-hairline">
                                            {{ str_replace('_', ' ', $order->order_type) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-ink-black">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if($order->status === 'pending')
                                        <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Pending</span>
                                    @elseif($order->status === 'processing')
                                        <span class="bg-blue-50 text-blue-800 border border-blue-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Diproses</span>
                                    @elseif($order->status === 'completed')
                                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Selesai</span>
                                    @else
                                        <span class="bg-rose-50 text-rose-800 border border-rose-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Batal</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-warm-stone hover:bg-ink-black hover:text-white text-ink-black text-[11px] font-bold transition btn-press">
                                        <span>Detail</span>
                                        <i class="fa-solid fa-chevron-right text-[8px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-muted-text">
                                    <div class="w-12 h-12 rounded-2xl bg-warm-stone flex items-center justify-center mx-auto mb-3 text-muted-text">
                                        <i class="fa-solid fa-inbox text-lg"></i>
                                    </div>
                                    <p class="font-medium text-xs">Belum ada transaksi pesanan hari ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Menu Terlaris & Pintasan Konten (4 Cols) --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Menu Terlaris Card --}}
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-card anim-fade-up anim-delay-1">
                <div class="flex items-center justify-between pb-4 border-b border-hairline mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-ink-black">
                            <i class="fa-solid fa-fire text-xs"></i>
                        </div>
                        <h3 class="text-sm font-bold text-ink-black">Menu Terlaris (Best Seller)</h3>
                    </div>
                </div>

                <div class="space-y-3.5">
                    @forelse($topMenus as $idx => $tm)
                        <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-warm-stone/40 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-warm-stone text-ink-black font-editorial text-sm font-bold flex items-center justify-center shrink-0">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="truncate">
                                    <h4 class="font-bold text-xs text-ink-black truncate">{{ $tm->menu_name }}</h4>
                                    <span class="text-[10px] text-muted-text font-mono">{{ $tm->total_qty }} porsi terjual</span>
                                </div>
                            </div>
                            <span class="font-mono text-xs font-bold text-ink-black shrink-0">
                                Rp {{ number_format($tm->total_revenue, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-muted-text text-xs">
                            Belum ada riwayat pesanan terakumulasi.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pintasan Pengelolaan Konten Web --}}
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-card anim-fade-up anim-delay-2">
                <h3 class="text-sm font-bold text-ink-black mb-1">Pintasan Web Customer</h3>
                <p class="text-[11px] text-muted-text mb-4">Sesuaikan konten yang tampil di halaman depan website.</p>

                <div class="space-y-2">
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-warm-stone/60 hover:bg-warm-stone text-xs font-bold text-ink-black transition btn-press">
                        <span class="flex items-center gap-2.5">
                            <i class="fa-solid fa-sliders text-muted-text text-xs"></i>
                            <span>Pengaturan Hero &amp; Transaksi</span>
                        </span>
                        <i class="fa-solid fa-chevron-right text-[9px] text-muted-text"></i>
                    </a>

                    <a href="{{ route('admin.gallery.index') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-warm-stone/60 hover:bg-warm-stone text-xs font-bold text-ink-black transition btn-press">
                        <span class="flex items-center gap-2.5">
                            <i class="fa-solid fa-camera-retro text-muted-text text-xs"></i>
                            <span>Upload Story &amp; Galeri</span>
                        </span>
                        <i class="fa-solid fa-chevron-right text-[9px] text-muted-text"></i>
                    </a>

                    <a href="{{ route('admin.testimonials.index') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-warm-stone/60 hover:bg-warm-stone text-xs font-bold text-ink-black transition btn-press">
                        <span class="flex items-center gap-2.5">
                            <i class="fa-solid fa-star text-muted-text text-xs"></i>
                            <span>Kelola Ulasan Pelanggan</span>
                        </span>
                        <i class="fa-solid fa-chevron-right text-[9px] text-muted-text"></i>
                    </a>

                    <a href="{{ route('admin.reservations.index') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-warm-stone/60 hover:bg-warm-stone text-xs font-bold text-ink-black transition btn-press">
                        <span class="flex items-center gap-2.5">
                            <i class="fa-solid fa-calendar-check text-muted-text text-xs"></i>
                            <span>Permintaan Reservasi Meja</span>
                        </span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-ink-black text-white font-bold">
                            {{ $pendingReservations ?? 0 }}
                        </span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
