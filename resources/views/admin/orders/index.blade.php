@extends('layouts.admin')
@section('title', 'Kelola Pesanan — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Manajemen Pesanan</h1>
            <p class="text-xs text-gray-400 mt-1">Daftar transaksi pesanan pelanggan dine-in, takeaway, dan slip scan kasir.</p>
        </div>
        <a href="{{ route('admin.orders.scan') }}" class="px-4 py-2.5 rounded-xl bg-goldAccent text-black font-extrabold text-xs uppercase tracking-wider hover:bg-amber-400 transition-all shadow-lg shadow-goldAccent/20 flex items-center gap-2">
            <i class="fa-solid fa-qrcode"></i>
            <span>Buka Scanner QR</span>
        </a>
    </div>

    {{-- Filter Status Tabs --}}
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 border-b border-white/10 text-xs">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ !request('status') ? 'bg-goldAccent text-black shadow-md' : 'text-gray-400 hover:text-white bg-white/5' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'pending' ? 'bg-amber-400 text-black shadow-md' : 'text-gray-400 hover:text-white bg-white/5' }}">
            Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'processing' ? 'bg-blue-400 text-black shadow-md' : 'text-gray-400 hover:text-white bg-white/5' }}">
            Diproses
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'completed' ? 'bg-emerald-400 text-black shadow-md' : 'text-gray-400 hover:text-white bg-white/5' }}">
            Selesai
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-xl font-bold transition-all {{ request('status') === 'cancelled' ? 'bg-rose-400 text-black shadow-md' : 'text-gray-400 hover:text-white bg-white/5' }}">
            Dibatalkan
        </a>
    </div>

    {{-- Orders Table --}}
    <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-black/40 text-gray-400 uppercase text-[10px] font-mono tracking-widest border-b border-white/10">
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
                <tbody class="divide-y divide-white/5">
                    @forelse($orders as $order)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-goldAccent block text-sm">{{ $order->code }}</span>
                                <span class="text-[10px] text-gray-500 font-mono">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-bold text-white text-sm block">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-gray-400 font-mono">{{ $order->customer_phone && $order->customer_phone !== '-' ? $order->customer_phone : 'Slip Pesan QR' }}</span>
                            </td>

                            <td class="px-6 py-4">
                                @if(str_starts_with((string) $order->notes, '[KURIR ZENDO]'))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase">
                                        <i class="fa-brands fa-whatsapp"></i> Kurir Zendo
                                    </span>
                                @else
                                    <span class="uppercase font-semibold text-gray-200 block">{{ str_replace('_', ' ', $order->order_type) }}</span>
                                @endif
                                @if($order->table)
                                    <span class="text-[10px] text-gray-500 font-mono">Meja {{ $order->table->number }}</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-mono font-bold text-white text-sm">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="uppercase font-bold text-gray-300 block">{{ $order->payment_method }}</span>
                                @if($order->payment_status === 'paid')
                                    <span class="text-[10px] text-emerald-400 font-semibold font-mono">Lunas</span>
                                @else
                                    <span class="text-[10px] text-amber-400 font-semibold font-mono">Belum Bayar</span>
                                @endif
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

                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" title="Cetak Struk" class="p-2 text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-lg border border-white/10 transition-colors inline-block">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-goldAccent text-black font-extrabold text-[11px] uppercase hover:bg-amber-400 transition-colors shadow-sm">
                                    <span>Detail</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-500 text-xs">
                                <i class="fa-solid fa-receipt text-3xl opacity-20 block mb-2"></i>
                                Belum ada data pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-white/10">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
