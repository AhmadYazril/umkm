@extends('layouts.admin')
@section('title', 'Kelola Pesanan — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Manajemen Pesanan</h1>
            <p class="text-xs text-zinc-400 mt-1">Daftar transaksi pesanan pelanggan dine-in, takeaway, dan pre-order.</p>
        </div>
    </div>

    {{-- Filter Status Tabs --}}
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 border-b border-zinc-800 text-xs">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ !request('status') ? 'bg-white text-zinc-950' : 'text-zinc-400 hover:text-white bg-zinc-900' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'pending' ? 'bg-yellow-400 text-zinc-950' : 'text-zinc-400 hover:text-white bg-zinc-900' }}">
            Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'processing' ? 'bg-blue-400 text-zinc-950' : 'text-zinc-400 hover:text-white bg-zinc-900' }}">
            Diproses
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'completed' ? 'bg-emerald-400 text-zinc-950' : 'text-zinc-400 hover:text-white bg-zinc-900' }}">
            Selesai
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'cancelled' ? 'bg-red-400 text-zinc-950' : 'text-zinc-400 hover:text-white bg-zinc-900' }}">
            Dibatalkan
        </a>
    </div>

    {{-- Orders Table --}}
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-mono tracking-widest border-b border-zinc-800">
                    <tr>
                        <th class="px-6 py-4">Kode & Waktu</th>
                        <th class="px-6 py-4">Pemesan</th>
                        <th class="px-6 py-4">Tipe & Meja</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Metode & Bayar</th>
                        <th class="px-6 py-4">Status Pesanan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    @forelse($orders as $order)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-white font-mono block text-sm">{{ $order->code }}</span>
                                <span class="text-[10px] text-zinc-500 font-mono">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-bold text-white block">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-zinc-400 font-mono">{{ $order->customer_phone }}</span>
                            </td>

                            <td class="px-6 py-4 uppercase font-semibold">
                                {{ str_replace('_', ' ', $order->order_type) }}
                                @if($order->table)
                                    <span class="text-zinc-400 block text-[11px]">Meja {{ $order->table->number }}</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-mono font-bold text-white text-sm">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 uppercase text-[11px]">
                                <span class="font-bold text-white block">{{ $order->payment_method }}</span>
                                <span class="text-zinc-400 font-mono">{{ $order->payment_status }}</span>
                            </td>

                            <td class="px-6 py-4">
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()"
                                        class="bg-zinc-950 border border-zinc-800 rounded-lg px-2.5 py-1 text-[11px] font-bold text-white focus:outline-none focus:border-white">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </form>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" title="Cetak Struk Nota" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-[11px] font-bold p-2 rounded-lg border border-zinc-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-white hover:bg-zinc-200 text-zinc-950 text-[11px] font-bold px-3 py-1.5 rounded-lg transition-colors">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500 text-xs">
                                Tidak ada data pesanan yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-zinc-800">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
