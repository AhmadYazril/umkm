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

    {{-- Grafik Keuangan Penjualan (Hanya tampil dan berjalan jika tidak di-hide di Pengaturan Admin) --}}
    @if(!empty($showDashboardChart) && !empty($financialChart))
        <div id="dashboardFinancialChartSection" class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-card reveal-on-scroll anim-fade-up space-y-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-hairline">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700 shrink-0">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-base sm:text-lg font-extrabold text-ink-black">Grafik Keuangan Penjualan</h2>
                            <span id="chartPeriodBadge" class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-mono font-bold uppercase">
                                {{ $financialChart['daily']['title'] ?? '7 Hari Terakhir' }}
                            </span>
                        </div>
                        <p class="text-[11.5px] text-muted-text mt-0.5">
                            Visualisasi pergerakan omzet penjualan lunas/selesai dan total pesanan masuk secara berkala.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    {{-- Period Tabs: Harian / Mingguan / Bulanan --}}
                    <div class="inline-flex items-center p-1 rounded-xl bg-warm-stone/60 border border-hairline text-xs font-bold">
                        <button type="button"
                                onclick="switchDashboardChartPeriod('daily')"
                                id="chartTabBtn_daily"
                                class="chart-period-btn px-3 py-1.5 rounded-lg transition cursor-pointer bg-ink-black text-white shadow-2xs">
                            Harian (7 Hari)
                        </button>
                        <button type="button"
                                onclick="switchDashboardChartPeriod('weekly')"
                                id="chartTabBtn_weekly"
                                class="chart-period-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-muted-text hover:text-ink-black">
                            Mingguan (4 Mgg)
                        </button>
                        <button type="button"
                                onclick="switchDashboardChartPeriod('monthly')"
                                id="chartTabBtn_monthly"
                                class="chart-period-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-muted-text hover:text-ink-black">
                            Bulanan (12 Bln)
                        </button>
                    </div>

                    {{-- Quick Hide Button (Langsung sembunyikan grafik) --}}
                    <form action="{{ route('admin.settings.toggleChart') }}" method="POST" class="inline-block" onsubmit="return confirm('Sembunyikan (Hide) grafik keuangan dari Dashboard? Anda dapat menampilkannya kembali di halaman Pengaturan Admin.');">
                        @csrf
                        <input type="hidden" name="hide_dashboard_chart" value="1">
                        <button type="submit"
                                class="px-3 py-2 rounded-xl bg-pure-white hover:bg-warm-stone text-muted-text hover:text-ink-black border border-hairline text-xs font-bold transition flex items-center gap-1.5 cursor-pointer btn-press"
                                title="Sembunyikan grafik ini (bisa diaktifkan lagi di Pengaturan)">
                            <i class="fa-solid fa-eye-slash text-xs"></i>
                            <span class="hidden sm:inline">Hide Grafik</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Summary Metrics Strip for Selected Period --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
                <div class="p-3.5 rounded-2xl bg-warm-stone/30 border border-hairline">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Omzet Lunas / Selesai</span>
                    <div id="chartSumRevenue" class="text-base sm:text-lg font-extrabold font-mono text-emerald-700 mt-1">
                        Rp {{ number_format($financialChart['daily']['summary']['total_revenue'] ?? 0, 0, ',', '.') }}
                    </div>
                    <span id="chartSumPaidCount" class="text-[10px] text-muted-text block mt-0.5">
                        {{ $financialChart['daily']['summary']['total_paid_orders'] ?? 0 }} transaksi selesai/lunas
                    </span>
                </div>

                <div class="p-3.5 rounded-2xl bg-warm-stone/30 border border-hairline">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Total Penjualan (Non-Batal)</span>
                    <div id="chartSumGross" class="text-base sm:text-lg font-extrabold font-mono text-ink-black mt-1">
                        Rp {{ number_format($financialChart['daily']['summary']['total_gross_sales'] ?? 0, 0, ',', '.') }}
                    </div>
                    <span id="chartSumTotalOrders" class="text-[10px] text-muted-text block mt-0.5">
                        Total {{ $financialChart['daily']['summary']['total_orders'] ?? 0 }} pesanan masuk
                    </span>
                </div>

                <div class="p-3.5 rounded-2xl bg-warm-stone/30 border border-hairline">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Rata-Rata Omzet</span>
                    <div id="chartSumAvg" class="text-base sm:text-lg font-extrabold font-mono text-ink-black mt-1">
                        Rp {{ number_format($financialChart['daily']['summary']['avg_revenue'] ?? 0, 0, ',', '.') }}
                    </div>
                    <span class="text-[10px] text-muted-text block mt-0.5">Rata-rata per titik waktu</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-warm-stone/30 border border-hairline">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-muted-text font-bold block">Puncak Penjualan</span>
                    <div id="chartSumPeak" class="text-base sm:text-lg font-extrabold font-mono text-ink-black mt-1">
                        Rp {{ number_format($financialChart['daily']['summary']['peak_revenue'] ?? 0, 0, ',', '.') }}
                    </div>
                    <span id="chartSumPeakLabel" class="text-[10px] text-muted-text block mt-0.5">
                        Tertinggi pada: {{ $financialChart['daily']['summary']['peak_label'] ?? '-' }}
                    </span>
                </div>
            </div>

            {{-- Canvas Chart Container --}}
            <div class="relative w-full h-[270px] sm:h-[310px] pt-2 select-none">
                <canvas id="financialSalesCanvas" class="w-full h-full block"></canvas>
                <div id="financialChartTooltip"
                     class="hidden absolute pointer-events-none z-20 bg-ink-black text-white text-[11px] rounded-xl px-3 py-2 shadow-lg border border-white/10 min-w-[175px] transition-opacity duration-100">
                </div>
            </div>

            {{-- Legend & Shortcut to Rekap PDF Transaksi --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-hairline text-xs">
                <div class="flex flex-wrap items-center gap-4">
                    <span class="inline-flex items-center gap-2 font-semibold text-charcoal">
                        <span class="w-3 h-3 rounded-xs bg-[#2F6B4F] inline-block"></span>
                        <span>Omzet Penjualan (Selesai / Lunas)</span>
                    </span>
                    <span class="inline-flex items-center gap-2 font-semibold text-muted-text">
                        <span class="w-3 h-3 rounded-xs bg-[#D5DFD9] border border-[#96B5A5] inline-block"></span>
                        <span>Total Pesanan Masuk (Non-Batal)</span>
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.orders.index') }}#rekap-laporan-section" class="inline-flex items-center gap-1.5 font-bold text-[#2F6B4F] hover:underline">
                        <i class="fa-solid fa-file-pdf text-xs"></i>
                        <span>Download Rekap PDF Transaksi</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

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

@if(!empty($showDashboardChart) && !empty($financialChart))
@push('scripts')
<script>
(function () {
    const chartDatasets = {{ \Illuminate\Support\Js::from($financialChart) }};
    let activePeriod = 'daily';
    let barHitboxes = [];

    const canvas = document.getElementById('financialSalesCanvas');
    const tooltip = document.getElementById('financialChartTooltip');
    if (!canvas) return;

    function formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }

    function formatShortRupiah(num) {
        const val = Number(num || 0);
        if (val >= 1000000) {
            return 'Rp ' + (val / 1000000).toFixed(val % 1000000 === 0 ? 0 : 1).replace('.', ',') + ' jt';
        }
        if (val >= 1000) {
            return 'Rp ' + Math.round(val / 1000).toLocaleString('id-ID') + ' rb';
        }
        return 'Rp ' + val;
    }

    function updateSummaryStrip(series) {
        if (!series || !series.summary) return;
        const s = series.summary;
        const elRev = document.getElementById('chartSumRevenue');
        const elPaid = document.getElementById('chartSumPaidCount');
        const elGross = document.getElementById('chartSumGross');
        const elOrders = document.getElementById('chartSumTotalOrders');
        const elAvg = document.getElementById('chartSumAvg');
        const elPeak = document.getElementById('chartSumPeak');
        const elPeakLbl = document.getElementById('chartSumPeakLabel');
        const elBadge = document.getElementById('chartPeriodBadge');

        if (elBadge) elBadge.textContent = series.title || '';
        if (elRev) elRev.textContent = formatRupiah(s.total_revenue);
        if (elPaid) elPaid.textContent = (s.total_paid_orders || 0) + ' transaksi selesai/lunas';
        if (elGross) elGross.textContent = formatRupiah(s.total_gross_sales);
        if (elOrders) elOrders.textContent = 'Total ' + (s.total_orders || 0) + ' pesanan masuk';
        if (elAvg) elAvg.textContent = formatRupiah(s.avg_revenue);
        if (elPeak) elPeak.textContent = formatRupiah(s.peak_revenue);
        if (elPeakLbl) elPeakLbl.textContent = 'Tertinggi pada: ' + (s.peak_label || '-');
    }

    function renderFinancialChart() {
        const series = chartDatasets[activePeriod];
        if (!series || !series.points) return;

        const rect = canvas.parentElement.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        const width = Math.max(300, rect.width);
        const height = Math.max(220, rect.height);

        canvas.width = width * dpr;
        canvas.height = height * dpr;
        const ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);
        ctx.clearRect(0, 0, width, height);

        const padLeft = 68;
        const padRight = 18;
        const padTop = 20;
        const padBottom = 38;
        const plotW = width - padLeft - padRight;
        const plotH = height - padTop - padBottom;

        const points = series.points;
        const count = points.length;
        let maxVal = 0;
        points.forEach(pt => {
            maxVal = Math.max(maxVal, Number(pt.revenue || 0), Number(pt.gross_sales || 0));
        });
        if (maxVal <= 0) maxVal = 100000;
        else maxVal = Math.ceil(maxVal * 1.15 / 10000) * 10000;

        // Horizontal grid lines & Y-axis labels
        const gridSteps = 4;
        ctx.font = '600 10px "Plus Jakarta Sans", sans-serif';
        ctx.textAlign = 'right';
        ctx.textBaseline = 'middle';

        for (let i = 0; i <= gridSteps; i++) {
            const ratio = i / gridSteps;
            const y = padTop + plotH - (ratio * plotH);
            const val = Math.round(maxVal * ratio);

            ctx.strokeStyle = i === 0 ? '#D2D6D3' : '#ECEEED';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(padLeft, y);
            ctx.lineTo(width - padRight, y);
            ctx.stroke();

            ctx.fillStyle = '#7A847E';
            ctx.fillText(formatShortRupiah(val), padLeft - 8, y);
        }

        // Draw bars & collect hitboxes
        barHitboxes = [];
        const groupW = plotW / Math.max(1, count);
        const barW = Math.min(34, Math.max(10, groupW * 0.44));

        const lineCoords = [];

        points.forEach((pt, idx) => {
            const centerX = padLeft + (idx * groupW) + (groupW / 2);
            const grossVal = Number(pt.gross_sales || 0);
            const revVal = Number(pt.revenue || 0);

            const grossH = Math.max(grossVal > 0 ? 4 : 0, (grossVal / maxVal) * plotH);
            const revH = Math.max(revVal > 0 ? 4 : 0, (revVal / maxVal) * plotH);

            const xBar = centerX - (barW / 2);
            const yGross = padTop + plotH - grossH;
            const yRev = padTop + plotH - revH;

            // Gross background bar (Pesanan Non-Batal)
            if (grossH > 0) {
                ctx.fillStyle = '#DCE6E0';
                ctx.beginPath();
                ctx.roundRect ? ctx.roundRect(xBar, yGross, barW, grossH, [6, 6, 0, 0]) : ctx.rect(xBar, yGross, barW, grossH);
                ctx.fill();
            }

            // Verified Revenue bar (Omzet Lunas / Selesai)
            if (revH > 0) {
                ctx.fillStyle = '#2F6B4F';
                ctx.beginPath();
                ctx.roundRect ? ctx.roundRect(xBar, yRev, barW, revH, [6, 6, 0, 0]) : ctx.rect(xBar, yRev, barW, revH);
                ctx.fill();
            }

            const activeTopY = revVal > 0 ? yRev : yGross;
            lineCoords.push({ x: centerX, y: activeTopY });

            // X-axis label
            ctx.fillStyle = '#555D58';
            ctx.font = '600 10px "Plus Jakarta Sans", sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';
            ctx.fillText(pt.label || '', centerX, padTop + plotH + 10);

            barHitboxes.push({
                xLeft: padLeft + (idx * groupW),
                xRight: padLeft + ((idx + 1) * groupW),
                centerX: centerX,
                topY: Math.min(yGross, yRev),
                point: pt
            });
        });

        // Smooth trend line connecting bar peaks
        if (lineCoords.length > 1) {
            ctx.strokeStyle = '#1B1F1D';
            ctx.lineWidth = 2;
            ctx.beginPath();
            lineCoords.forEach((pt, i) => {
                if (i === 0) ctx.moveTo(pt.x, pt.y);
                else ctx.lineTo(pt.x, pt.y);
            });
            ctx.stroke();

            lineCoords.forEach(pt => {
                ctx.fillStyle = '#FFFFFF';
                ctx.strokeStyle = '#1B1F1D';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.arc(pt.x, pt.y, 3.5, 0, Math.PI * 2);
                ctx.fill();
                ctx.stroke();
            });
        }
    }

    window.switchDashboardChartPeriod = function (period) {
        if (!chartDatasets[period]) return;
        activePeriod = period;

        ['daily', 'weekly', 'monthly'].forEach(p => {
            const btn = document.getElementById('chartTabBtn_' + p);
            if (!btn) return;
            if (p === period) {
                btn.className = 'chart-period-btn px-3 py-1.5 rounded-lg transition cursor-pointer bg-ink-black text-white shadow-2xs';
            } else {
                btn.className = 'chart-period-btn px-3 py-1.5 rounded-lg transition cursor-pointer text-muted-text hover:text-ink-black';
            }
        });

        updateSummaryStrip(chartDatasets[period]);
        renderFinancialChart();
    };

    canvas.addEventListener('mousemove', function (e) {
        if (!tooltip || !barHitboxes.length) return;
        const rect = canvas.getBoundingClientRect();
        const mx = e.clientX - rect.left;
        const my = e.clientY - rect.top;

        const hit = barHitboxes.find(b => mx >= b.xLeft && mx <= b.xRight);
        if (!hit) {
            tooltip.classList.add('hidden');
            return;
        }

        const p = hit.point;
        tooltip.innerHTML =
            '<div class="font-bold text-emerald-300 mb-1">' + (p.full_label || p.label) + '</div>' +
            '<div class="flex justify-between gap-3"><span>Omzet Lunas:</span><strong class="font-mono">' + formatRupiah(p.revenue) + '</strong></div>' +
            '<div class="flex justify-between gap-3 text-stone-300"><span>Total Non-Batal:</span><span class="font-mono">' + formatRupiah(p.gross_sales) + '</span></div>' +
            '<div class="flex justify-between gap-3 text-stone-300 pt-1 mt-1 border-t border-white/15"><span>Transaksi:</span><span class="font-mono">' + (p.orders_count || 0) + ' pesanan (' + (p.paid_count || 0) + ' lunas)</span></div>';

        tooltip.classList.remove('hidden');
        const tipW = tooltip.offsetWidth || 185;
        let leftPos = hit.centerX - (tipW / 2);
        leftPos = Math.max(8, Math.min(rect.width - tipW - 8, leftPos));
        const topPos = Math.max(8, hit.topY - 78);

        tooltip.style.left = leftPos + 'px';
        tooltip.style.top = topPos + 'px';
    });

    canvas.addEventListener('mouseleave', function () {
        if (tooltip) tooltip.classList.add('hidden');
    });

    window.addEventListener('resize', renderFinancialChart);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderFinancialChart);
    } else {
        renderFinancialChart();
    }
})();
</script>
@endpush
@endif
@endsection
