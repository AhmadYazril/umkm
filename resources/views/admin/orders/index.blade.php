@extends('layouts.admin')
@section('title', 'Manajemen Pesanan Masuk — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-receipt text-[10px]"></i>
                <span>Arus Transaksi Masuk</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Manajemen Pesanan Masuk
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Daftar transaksi pesanan dine-in, takeaway, kurir Zendo, dan scan slip QR kasir.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('kasir.index') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone text-ink-black border border-hairline font-bold text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-cash-register text-xs"></i>
                <span>Buka POS Kasir</span>
            </a>
            <a href="{{ route('admin.orders.scan') }}" class="px-4 py-2.5 rounded-xl bg-ink-black text-white font-bold text-xs uppercase tracking-wider hover:bg-charcoal transition shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-qrcode text-xs"></i>
                <span>Buka Scanner QR</span>
            </a>
        </div>
    </div>

    {{-- Filter Status Tabs --}}
    <div class="flex items-center space-x-2 overflow-x-auto pb-1 border-b border-hairline text-xs font-bold reveal-on-scroll anim-fade-up">
        <a href="{{ route('admin.orders.index') }}" 
           class="px-4 py-2 rounded-xl transition-all {{ !request('status') ? 'bg-ink-black text-white shadow-xs' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'pending' ? 'bg-amber-50 text-amber-900 border border-amber-200 font-bold' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'processing' ? 'bg-blue-50 text-blue-900 border border-blue-200 font-bold' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Diproses
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'completed' ? 'bg-emerald-50 text-emerald-900 border border-emerald-200 font-bold' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Selesai
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'cancelled' ? 'bg-rose-50 text-rose-900 border border-rose-200 font-bold' : 'text-muted-text hover:text-ink-black hover:bg-warm-stone' }}">
            Dibatalkan
        </a>
    </div>

    {{-- Orders Table Card --}}
    <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl overflow-hidden shadow-card reveal-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal">
                <thead class="bg-warm-stone/50 text-muted-text uppercase text-[10px] font-mono tracking-widest border-b border-hairline">
                    <tr>
                        <th class="px-6 py-4">Kode &amp; Waktu</th>
                        <th class="px-6 py-4">Pemesan</th>
                        <th class="px-6 py-4">Tipe &amp; Meja</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Metode Bayar</th>
                        <th class="px-6 py-4">Status Pesanan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($orders as $order)
                        <tr class="hover:bg-warm-stone/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-ink-black block text-sm">{{ $order->code }}</span>
                                <span class="text-[10px] text-muted-text font-mono">{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-bold text-ink-black text-sm block">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-muted-text font-mono">{{ $order->customer_phone && $order->customer_phone !== '-' ? $order->customer_phone : 'Slip Pesan QR' }}</span>
                            </td>

                            <td class="px-6 py-4">
                                @if(str_starts_with((string) $order->notes, '[KURIR ZENDO]'))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold">
                                        <i class="fa-brands fa-whatsapp"></i> Kurir Zendo
                                    </span>
                                @else
                                    <span class="uppercase font-bold text-charcoal bg-warm-stone px-2 py-0.5 rounded-full border border-hairline text-[10px]">
                                        {{ str_replace('_', ' ', $order->order_type) }}
                                    </span>
                                @endif
                                @if($order->table)
                                    <span class="text-[10px] text-muted-text font-mono block mt-1">Meja #{{ $order->table->number }}</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-mono font-bold text-ink-black text-sm">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-[11px] font-semibold block text-charcoal">{{ strtoupper($order->payment_method ?? 'TUNAI') }}</span>
                                <span class="text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $order->payment_status === 'paid' ? '● Lunas' : '○ Belum Bayar' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($order->status === 'pending')
                                    <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Pending</span>
                                @elseif($order->status === 'processing')
                                    <span class="bg-blue-50 text-blue-800 border border-blue-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Diproses</span>
                                @elseif($order->status === 'ready')
                                    <span class="bg-purple-50 text-purple-800 border border-purple-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Siap Saji</span>
                                @elseif($order->status === 'completed')
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Selesai</span>
                                @else
                                    <span class="bg-rose-50 text-rose-800 border border-rose-200 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Batal</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-lg bg-warm-stone hover:bg-ink-black hover:text-white text-ink-black text-xs font-bold transition btn-press">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="p-1.5 rounded-lg bg-warm-stone hover:bg-ink-black hover:text-white text-muted-text transition btn-press" title="Cetak Nota">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-muted-text">
                                <div class="w-12 h-12 rounded-2xl bg-warm-stone flex items-center justify-center mx-auto mb-3 text-muted-text">
                                    <i class="fa-solid fa-receipt text-lg"></i>
                                </div>
                                <p class="font-medium text-xs">Tidak ada data pesanan pada kriteria ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-hairline bg-warm-ivory">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
