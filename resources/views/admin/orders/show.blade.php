@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $order->code . ' — Admin Nucomu Cafe')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black uppercase text-white tracking-tight">Detail Pesanan</h1>
            <p class="text-xs text-zinc-400 mt-1">Kode: <span class="font-mono text-white font-bold">{{ $order->code }}</span></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="bg-zinc-800 hover:bg-zinc-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl border border-zinc-700 transition-colors flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Struk Nota</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-zinc-400 hover:text-white font-bold">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        
        {{-- Left Column: Items (Col 7) --}}
        <div class="md:col-span-7 space-y-6">
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4 border-b border-zinc-800 pb-3">
                    Rincian Item Pesanan
                </h2>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-start justify-between border-b border-zinc-800/60 pb-3">
                            <div>
                                <h3 class="font-bold text-sm text-white">{{ $item->menu_name }}</h3>
                                <div class="text-xs text-zinc-400 mt-0.5">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->qty }} pcs
                                </div>
                                @if($item->options && is_array($item->options))
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($item->options as $opt)
                                            <span class="text-[10px] bg-zinc-950 text-zinc-400 border border-zinc-800 px-2 py-0.5 rounded">
                                                + {{ is_array($opt) ? $opt['name'] : $opt }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="font-mono font-bold text-white text-sm">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center pt-4 text-base font-black text-white">
                    <span>Total Pembayaran</span>
                    <span class="font-mono">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-2">Catatan Pesanan Total</h3>
                    <p class="text-xs text-zinc-300 italic bg-zinc-950 p-3 rounded-xl border border-zinc-800">
                        "{{ $order->notes }}"
                    </p>
                </div>
            @endif
        </div>

        {{-- Right Column: Status & Customer Info (Col 5) --}}
        <div class="md:col-span-5 space-y-6">
            
            {{-- Update Status Box --}}
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 mb-4 border-b border-zinc-800 pb-3">
                    Update Status Pesanan
                </h2>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="status" class="block text-xs text-zinc-400 mb-1">Status Progres</label>
                        <select name="status" id="status" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-white">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Konfirmasi)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses (Barista/Dapur)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Siap Diambil/Disajikan)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_status" class="block text-xs text-zinc-400 mb-1">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-white">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider py-3 rounded-xl transition-all shadow-md">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Customer Info Box --}}
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 text-xs space-y-3">
                <h2 class="font-bold uppercase tracking-wider text-zinc-400 border-b border-zinc-800 pb-2">
                    Informasi Pelanggan
                </h2>
                <div>
                    <span class="text-zinc-500 block">Nama Pemesan</span>
                    <span class="font-bold text-white text-sm">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-zinc-500 block">WhatsApp</span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="font-mono text-emerald-400 font-bold hover:underline">
                        {{ $order->customer_phone }} ↗
                    </a>
                </div>
                <div>
                    <span class="text-zinc-500 block">Tipe Pesanan</span>
                    <span class="font-bold text-white uppercase">{{ str_replace('_', ' ', $order->order_type) }}</span>
                </div>
                @if($order->table)
                    <div>
                        <span class="text-zinc-500 block">Nomor Meja</span>
                        <span class="font-bold text-white">Meja {{ $order->table->number }} (Kapasitas {{ $order->table->capacity }} orang)</span>
                    </div>
                @endif
                <div>
                    <span class="text-zinc-500 block">Metode Pembayaran</span>
                    <span class="font-bold text-white uppercase">{{ $order->payment_method }}</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
