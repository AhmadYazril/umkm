@extends('layouts.admin')
@section('title', 'Dashboard Admin — Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Dashboard Overview</h1>
            <p class="text-xs text-zinc-400 mt-1">Ringkasan aktivitas transaksi dan operasional Nucomu Cafe hari ini.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.menus.create') }}" class="bg-white hover:bg-zinc-200 text-zinc-950 font-bold text-xs px-4 py-2.5 rounded-xl transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Menu Baru</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card 1: Omset Hari Ini --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <span class="text-[11px] font-mono uppercase tracking-widest text-zinc-500 block mb-2">Omset Hari Ini</span>
            <div class="text-2xl font-black text-white font-mono">
                Rp {{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-emerald-400 font-semibold mt-2 block">✓ Terverifikasi Lunas</span>
        </div>

        {{-- Card 2: Total Pesanan Hari Ini --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <span class="text-[11px] font-mono uppercase tracking-widest text-zinc-500 block mb-2">Pesanan Hari Ini</span>
            <div class="text-2xl font-black text-white font-mono">
                {{ $stats['orders_today'] ?? 0 }} <span class="text-sm font-normal text-zinc-400">transaksi</span>
            </div>
            <span class="text-[10px] text-zinc-500 mt-2 block">Dine-in, Takeaway, Pre-order</span>
        </div>

        {{-- Card 3: Pending Orders --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <span class="text-[11px] font-mono uppercase tracking-widest text-zinc-500 block mb-2">Perlu Diproses</span>
            <div class="text-2xl font-black text-yellow-400 font-mono">
                {{ $stats['pending_orders'] ?? 0 }} <span class="text-sm font-normal text-zinc-400">pesanan</span>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-[10px] text-yellow-400 hover:underline mt-2 block font-bold">
                Lihat Pesanan Pending →
            </a>
        </div>

        {{-- Card 4: Total Menu Active --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <span class="text-[11px] font-mono uppercase tracking-widest text-zinc-500 block mb-2">Total Menu Aktif</span>
            <div class="text-2xl font-black text-white font-mono">
                {{ $stats['total_menus'] ?? 0 }} <span class="text-sm font-normal text-zinc-400">item</span>
            </div>
            <span class="text-[10px] text-zinc-500 mt-2 block">Kategori Kopi & Dessert</span>
        </div>

    </div>

    {{-- Tabel Pesanan Terbaru --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-zinc-800 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-white uppercase tracking-wider">Pesanan Terbaru</h2>
                <p class="text-xs text-zinc-400">Transaksi masuk dari pelanggan publik</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-white hover:underline">
                Lihat Semua Pesanan →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-mono tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-6 py-4">Kode Pesanan</th>
                        <th class="px-6 py-4">Pemesan</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-white">
                                {{ $order->code }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-white block">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-zinc-500 font-mono">{{ $order->customer_phone }}</span>
                            </td>
                            <td class="px-6 py-4 uppercase font-semibold text-zinc-300">
                                {{ str_replace('_', ' ', $order->order_type) }}
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-white">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($order->status === 'pending')
                                    <span class="bg-yellow-950 text-yellow-400 border border-yellow-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Pending</span>
                                @elseif($order->status === 'processing')
                                    <span class="bg-blue-950 text-blue-400 border border-blue-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Diproses</span>
                                @elseif($order->status === 'completed')
                                    <span class="bg-emerald-950 text-emerald-400 border border-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Selesai</span>
                                @else
                                    <span class="bg-red-950 text-red-400 border border-red-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-zinc-800 hover:bg-zinc-700 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg border border-zinc-700 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-zinc-500 text-xs">
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
