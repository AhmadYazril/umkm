@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $order->code . ' — Nucomu Cafe')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-receipt text-[10px]"></i>
                <span>Rincian Transaksi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Pesanan #<span class="font-mono">{{ $order->code }}</span>
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Waktu pemesanan: <span class="font-mono text-ink-black">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
            </p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="bg-pure-white hover:bg-warm-stone text-ink-black font-bold text-xs px-4 py-2.5 rounded-xl border border-hairline transition flex items-center space-x-2 shadow-xs btn-press">
                <i class="fa-solid fa-print text-xs"></i>
                <span>Cetak Struk Nota</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2.5 rounded-xl bg-warm-stone hover:bg-pure-white text-ink-black border border-hairline text-xs font-bold transition btn-press">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        {{-- Left Column: Items (Col 7) --}}
        <div class="md:col-span-7 space-y-6">
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card reveal-on-scroll anim-fade-up">
                <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black mb-4 border-b border-hairline pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-utensils text-charcoal"></i>
                    <span>Rincian Item Pesanan</span>
                </h2>

                <div class="divide-y divide-hairline">
                    @foreach($order->items as $item)
                        <div class="py-3.5 flex items-start justify-between">
                            <div class="pr-4">
                                <h3 class="font-bold text-sm text-ink-black">{{ $item->menu_name }}</h3>
                                <div class="text-xs text-muted-text mt-0.5 font-mono">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->qty }} pcs
                                </div>
                                @if($item->options && is_array($item->options))
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($item->options as $opt)
                                            <span class="text-[10px] bg-warm-stone text-charcoal border border-hairline px-2 py-0.5 rounded">
                                                + {{ is_array($opt) ? $opt['name'] : $opt }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="font-mono font-bold text-ink-black text-sm shrink-0">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-hairline text-base font-bold text-ink-black">
                    <span>Total Pembayaran</span>
                    <span class="font-mono text-xl text-ink-black">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card reveal-on-scroll">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-ink-black mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-note-sticky text-charcoal"></i>
                        <span>Catatan Tambahan Pelanggan</span>
                    </h3>
                    <p class="text-xs text-charcoal italic bg-warm-ivory p-3.5 rounded-xl border border-hairline">
                        "{{ $order->notes }}"
                    </p>
                </div>
            @endif
        </div>

        {{-- Right Column: Status & Customer Info (Col 5) --}}
        <div class="md:col-span-5 space-y-6">
            
            {{-- Update Status Box --}}
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card reveal-on-scroll anim-fade-up">
                <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black mb-4 border-b border-hairline pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-charcoal"></i>
                    <span>Perbarui Status Pesanan</span>
                </h2>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="status" class="block text-xs text-charcoal mb-1.5 font-bold">Status Pesanan</label>
                        <select name="status" id="status" class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Kasir)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses (Dapur / Barista)</option>
                            <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Siap Saji (Ready to Pick)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_status" class="block text-xs text-charcoal mb-1.5 font-bold">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2.5 text-xs text-ink-black focus:outline-none focus:border-ink-black">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider py-3 rounded-xl transition shadow-xs btn-press">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Customer Info Box --}}
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 text-xs space-y-3.5 shadow-card reveal-on-scroll">
                <h2 class="font-bold uppercase tracking-wider text-ink-black border-b border-hairline pb-2.5 flex items-center gap-2">
                    <i class="fa-solid fa-user text-charcoal"></i>
                    <span>Informasi Pemesan</span>
                </h2>
                <div>
                    <span class="text-muted-text block text-[11px]">Nama Pemesan</span>
                    <span class="font-bold text-ink-black text-sm">{{ $order->customer_name }}</span>
                </div>
                <div>
                    <span class="text-muted-text block text-[11px]">Kontak WhatsApp</span>
                    @if($order->customer_phone && $order->customer_phone !== '-')
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="font-mono text-emerald-800 font-bold hover:underline inline-flex items-center gap-1">
                            <i class="fa-brands fa-whatsapp"></i>
                            <span>{{ $order->customer_phone }} ↗</span>
                        </a>
                    @else
                        <span class="text-muted-text italic">Slip Pesan Mandiri (Scan QR)</span>
                    @endif
                </div>
                <div>
                    <span class="text-muted-text block text-[11px]">Tipe Pesanan</span>
                    <span class="font-bold text-ink-black uppercase text-[11px]">{{ str_replace('_', ' ', $order->order_type) }}</span>
                </div>
                @if($order->table)
                    <div>
                        <span class="text-muted-text block text-[11px]">Nomor Meja</span>
                        <span class="font-bold text-ink-black">Meja #{{ $order->table->number }}</span>
                    </div>
                @endif
                <div>
                    <span class="text-muted-text block text-[11px]">Metode Pembayaran</span>
                    <span class="font-bold text-ink-black uppercase text-[11px]">{{ $order->payment_method ?? 'TUNAI' }}</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
