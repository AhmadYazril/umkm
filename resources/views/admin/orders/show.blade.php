@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $order->code . ' — Admin Nucomu Cafe')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-goldAccent mb-1.5">
                <i class="fa-solid fa-receipt text-[10px]"></i> Transaksi Kasir
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Detail Pesanan</h1>
            <p class="text-xs text-gray-400 mt-0.5">Kode: <span class="font-mono text-goldAccent font-bold">{{ $order->code }}</span></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white font-bold text-xs px-4 py-2.5 rounded-xl border border-white/15 transition-colors flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Struk Nota</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-white font-bold transition-colors">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        
        {{-- Left Column: Items (Col 7) --}}
        <div class="md:col-span-7 space-y-6">
            <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 border-b border-white/10 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-utensils text-goldAccent"></i> Rincian Item Pesanan
                </h2>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-start justify-between border-b border-white/5 pb-3">
                            <div>
                                <h3 class="font-bold text-sm text-white">{{ $item->menu_name }}</h3>
                                <div class="text-xs text-gray-400 mt-0.5 font-mono">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->qty }} pcs
                                </div>
                                @if($item->options && is_array($item->options))
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($item->options as $opt)
                                            <span class="text-[10px] bg-black/40 text-gray-300 border border-white/10 px-2 py-0.5 rounded">
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
                    <span class="font-mono text-xl text-goldAccent">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-note-sticky text-amber-400"></i> Catatan Pelanggan
                    </h3>
                    <p class="text-xs text-gray-300 italic bg-black/40 p-3 rounded-xl border border-white/10">
                        "{{ $order->notes }}"
                    </p>
                </div>
            @endif
        </div>

        {{-- Right Column: Status & Customer Info (Col 5) --}}
        <div class="md:col-span-5 space-y-6">
            
            {{-- Update Status Box --}}
            <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 border-b border-white/10 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-goldAccent"></i> Update Status Pesanan
                </h2>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="status" class="block text-xs text-gray-400 mb-1 font-semibold">Status Progres</label>
                        <select name="status" id="status" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-goldAccent">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Kasir)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses (Barista/Dapur)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Siap Diambil/Disajikan)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_status" class="block text-xs text-gray-400 mb-1 font-semibold">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-goldAccent">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 text-black font-extrabold text-xs uppercase tracking-wider py-3 rounded-xl transition-all shadow-lg shadow-goldAccent/20">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Customer Info Box --}}
            <div class="bg-[#15151c]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 text-xs space-y-3.5 shadow-2xl">
                <h2 class="font-bold uppercase tracking-wider text-gray-400 border-b border-white/10 pb-2.5 flex items-center gap-2">
                    <i class="fa-solid fa-user text-goldAccent"></i> Informasi Pemesan
                </h2>
                <div>
                    <span class="text-gray-500 block">Nama Pemesan</span>
                    <span class="font-bold text-white text-base">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Kontak / Tipe Masuk</span>
                    @if($order->customer_phone && $order->customer_phone !== '-')
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="font-mono text-emerald-400 font-bold hover:underline">
                            {{ $order->customer_phone }} ↗
                        </a>
                    @else
                        <span class="text-goldAccent font-semibold">Slip Pesan Mandiri (Scan QR)</span>
                    @endif
                </div>
                <div>
                    <span class="text-gray-500 block">Tipe Pesanan</span>
                    <span class="font-bold text-white uppercase">{{ str_replace('_', ' ', $order->order_type) }}</span>
                </div>
                @if($order->table)
                    <div>
                        <span class="text-gray-500 block">Nomor Meja</span>
                        <span class="font-bold text-white">Meja {{ $order->table->number }} (Kapasitas {{ $order->table->capacity }} orang)</span>
                    </div>
                @endif
                <div>
                    <span class="text-gray-500 block">Metode Pembayaran</span>
                    <span class="font-bold text-white uppercase">{{ $order->payment_method }}</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
