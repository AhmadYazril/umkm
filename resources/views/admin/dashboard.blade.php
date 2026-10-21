@extends('layouts.admin')
@section('title', 'Dashboard Admin — Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-goldAccent mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sistem Live
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Dashboard Overview</h1>
            <p class="text-xs text-gray-400 mt-1">Ringkasan aktivitas transaksi dan operasional Nucomu Cafe hari ini.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.orders.scan') }}" class="px-4 py-2.5 rounded-xl bg-goldAccent text-black font-extrabold text-xs uppercase tracking-wider hover:bg-amber-400 transition-all shadow-lg shadow-goldAccent/20 flex items-center gap-2">
                <i class="fa-solid fa-qrcode"></i>
                <span>Scan QR Kasir</span>
            </a>
            <a href="{{ route('admin.menus.create') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white border border-white/15 font-bold text-xs uppercase transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Menu</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Card 1: Omset Hari Ini --}}
        <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-goldAccent/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-gray-400">Omset Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-goldAccent/10 border border-goldAccent/20 flex items-center justify-center text-goldAccent">
                    <i class="fa-solid fa-rupiah-sign text-xs"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-white font-mono">
                Rp {{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-emerald-400 font-semibold mt-2 flex items-center gap-1">
                <i class="fa-solid fa-circle-check text-[9px]"></i> Terverifikasi Lunas
            </span>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-goldAccent/5 rounded-full blur-xl pointer-events-none"></div>
        </div>

        {{-- Card 2: Total Pesanan Hari Ini --}}
        <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-cyanAccent/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-gray-400">Pesanan Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-cyanAccent/10 border border-cyanAccent/20 flex items-center justify-center text-cyanAccent">
                    <i class="fa-solid fa-receipt text-xs"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-white font-mono">
                {{ $stats['orders_today'] ?? 0 }} <span class="text-xs font-normal text-gray-400">transaksi</span>
            </div>
            <span class="text-[10px] text-gray-400 mt-2 block">Dine-in, Takeaway, Slip QR</span>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-cyanAccent/5 rounded-full blur-xl pointer-events-none"></div>
        </div>

        {{-- Card 3: Pending Orders --}}
        <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-amber-400/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-gray-400">Perlu Diproses</span>
                <div class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                    <i class="fa-solid fa-clock text-xs"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-400 font-mono">
                {{ $stats['pending_orders'] ?? 0 }} <span class="text-xs font-normal text-gray-400">pesanan</span>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-[10px] text-amber-400 hover:text-amber-300 mt-2 inline-flex items-center gap-1 font-bold">
                Lihat Pesanan Pending <i class="fa-solid fa-arrow-right text-[8px]"></i>
            </a>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-500/5 rounded-full blur-xl pointer-events-none"></div>
        </div>

        {{-- Card 4: Total Menu Active --}}
        <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-violetAccent/40 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-mono uppercase tracking-widest text-gray-400">Total Menu Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-violetAccent/10 border border-violetAccent/20 flex items-center justify-center text-violetAccent">
                    <i class="fa-solid fa-mug-hot text-xs"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-white font-mono">
                {{ $stats['total_menus'] ?? 0 }} <span class="text-xs font-normal text-gray-400">item</span>
            </div>
            <span class="text-[10px] text-gray-400 mt-2 block">Kopi, Non-kopi, Dessert</span>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-violetAccent/5 rounded-full blur-xl pointer-events-none"></div>
        </div>

    </div>

    {{-- Tabel Pesanan Terbaru --}}
    <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-goldAccent/10 border border-goldAccent/20 flex items-center justify-center text-goldAccent">
                    <i class="fa-solid fa-list-check text-sm"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-white">Pesanan Terbaru</h2>
                    <p class="text-[11px] text-gray-400">Transaksi masuk dari pelanggan (termasuk scan slip QR)</p>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-goldAccent hover:text-amber-300 transition-colors flex items-center gap-1.5">
                <span>Lihat Semua</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-black/40 text-gray-400 uppercase text-[10px] font-mono tracking-widest border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4">Kode Pesanan</th>
                        <th class="px-6 py-4">Pemesan</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-goldAccent">
                                {{ $order->code }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-white block">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-gray-500 font-mono">{{ $order->customer_phone && $order->customer_phone !== '-' ? $order->customer_phone : 'Slip QR Kasir' }}</span>
                            </td>
                            <td class="px-6 py-4 uppercase font-semibold text-gray-300 text-[11px]">
                                {{ str_replace('_', ' ', $order->order_type) }}
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-white">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($order->status === 'pending')
                                    <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Pending</span>
                                @elseif($order->status === 'processing')
                                    <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Diproses</span>
                                @elseif($order->status === 'completed')
                                    <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Selesai</span>
                                @else
                                    <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-goldAccent hover:text-black text-white text-[11px] font-bold border border-white/10 transition-all">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-xs">
                                <i class="fa-solid fa-receipt text-3xl opacity-20 block mb-2"></i>
                                Belum ada pesanan terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
