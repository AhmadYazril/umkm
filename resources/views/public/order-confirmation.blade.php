@extends('layouts.app')
@section('title', 'Konfirmasi Pesanan #' . $order->code . ' — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-zinc-900/80 border border-zinc-800 rounded-3xl p-6 sm:p-10 text-center relative overflow-hidden shadow-2xl">
            
            {{-- Success Badge --}}
            <div class="w-16 h-16 bg-white text-zinc-950 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-white/10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-2xl sm:text-4xl font-black uppercase text-white tracking-tight mb-2">
                Pesanan Berhasil <span class="text-zinc-500">Dibuat</span>
            </h1>
            <p class="text-zinc-400 text-sm max-w-md mx-auto mb-6">
                Terima kasih, <strong class="text-white">{{ $order->customer_name }}</strong>! Pesanan Anda telah tercatat di sistem kami.
            </p>

            {{-- Order Code Box --}}
            <div class="bg-zinc-950 border border-zinc-800 rounded-2xl p-4 max-w-md mx-auto mb-8">
                <span class="text-xs text-zinc-500 uppercase tracking-widest block font-mono">Kode Pesanan Anda</span>
                <span class="text-2xl sm:text-3xl font-black text-white tracking-wider font-mono select-all">
                    {{ $order->code }}
                </span>
                <p class="text-[11px] text-zinc-400 mt-1">Simpan kode ini untuk pelacakan status pesanan.</p>
            </div>

            {{-- Summary Details --}}
            <div class="text-left bg-zinc-950/50 border border-zinc-800/80 rounded-2xl p-6 mb-8 space-y-4 text-xs">
                <div class="flex justify-between border-b border-zinc-800 pb-3">
                    <span class="text-zinc-400">Tipe Pesanan</span>
                    <span class="text-white font-bold uppercase">{{ str_replace('_', ' ', $order->order_type) }} @if($order->table) (Meja {{ $order->table->number }}) @endif</span>
                </div>
                <div class="flex justify-between border-b border-zinc-800 pb-3">
                    <span class="text-zinc-400">Metode Pembayaran</span>
                    <span class="text-white font-bold uppercase">{{ $order->payment_method }} ({{ ucfirst($order->payment_status) }})</span>
                </div>
                <div class="flex justify-between border-b border-zinc-800 pb-3">
                    <span class="text-zinc-400">Nomor Telepon</span>
                    <span class="text-white font-mono">{{ $order->customer_phone }}</span>
                </div>

                {{-- Items Table --}}
                <div class="pt-2">
                    <span class="text-zinc-400 font-bold block mb-2 uppercase">Rincian Menu:</span>
                    <div class="space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-zinc-300">
                                <div>
                                    <span>{{ $item->menu_name }}</span>
                                    <span class="text-zinc-500"> × {{ $item->qty }}</span>
                                    @if($item->options && is_array($item->options))
                                        <div class="text-[11px] text-zinc-400">
                                            @foreach($item->options as $opt)
                                                + {{ is_array($opt) ? $opt['name'] : $opt }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <span class="font-mono text-white">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between border-t border-zinc-800 pt-3 text-sm font-black text-white">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @if(isset($waUrl))
                    <a href="{{ $waUrl }}" target="_blank" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-black text-xs uppercase tracking-wider px-8 py-4 rounded-2xl transition-all flex items-center justify-center space-x-2 shadow-lg shadow-emerald-500/10">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l.006.012-1.162 4.24 4.341-1.139.006.007z"/>
                        </svg>
                        <span>Konfirmasi via WhatsApp</span>
                    </a>
                @endif

                <a href="{{ route('order.track', ['code' => $order->code]) }}" class="w-full sm:w-auto bg-zinc-800 hover:bg-zinc-700 text-white font-bold text-xs uppercase tracking-wider px-6 py-4 rounded-2xl transition-all border border-zinc-700 flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>Lacak Status</span>
                </a>
            </div>

        </div>

    </div>
</section>
@endsection
