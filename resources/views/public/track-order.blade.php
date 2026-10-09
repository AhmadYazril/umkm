@extends('layouts.app')
@section('title', 'Lacak Pesanan — Nucomu Cafe')

@section('content')
<section class="py-12 bg-zinc-950 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-xl mx-auto mb-10">
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white uppercase mb-2">
                Lacak <span class="text-zinc-500">Pesanan</span>
            </h1>
            <p class="text-zinc-400 text-sm">
                Masukkan Kode Pesanan Anda (contoh: NCM-20261009-001) untuk memantau status pesanan secara real-time.
            </p>

            <form action="{{ route('order.track') }}" method="GET" class="mt-8 flex items-center bg-zinc-900 border border-zinc-800 rounded-full p-1.5 focus-within:border-white transition-all">
                <input type="text" name="code" value="{{ request('code') }}" placeholder="Masukkan Kode Pesanan..." required
                    class="w-full bg-transparent px-5 py-2.5 text-sm text-white placeholder-zinc-500 focus:outline-none uppercase font-mono">
                <button type="submit" class="bg-white text-zinc-950 font-bold px-6 py-2.5 rounded-full hover:bg-zinc-200 transition-colors flex items-center space-x-2 shrink-0 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Lacak</span>
                </button>
            </form>
        </div>

        @if(isset($order) && $order)
            <div class="bg-zinc-900/80 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-8">
                
                {{-- Status Header --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-zinc-800 pb-6 gap-4">
                    <div>
                        <span class="text-xs text-zinc-500 uppercase tracking-widest font-mono">Kode Pesanan</span>
                        <h2 class="text-2xl font-black text-white font-mono">{ $order->code }</h2>
                        <p class="text-xs text-zinc-400 mt-1">Pemesan: {{ $order->customer_name }} ({{ $order->customer_phone }})</p>
                    </div>

                    <div>
                        @if($order->status === 'pending')
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-950 text-yellow-400 border border-yellow-800 text-xs font-bold uppercase tracking-wider">
                                Menunggu Konfirmasi
                            </span>
                        @elseif($order->status === 'processing')
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-blue-950 text-blue-400 border border-blue-800 text-xs font-bold uppercase tracking-wider">
                                Sedang Diproses Barista / Dapur
                            </span>
                        @elseif($order->status === 'completed')
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-emerald-950 text-emerald-400 border border-emerald-800 text-xs font-bold uppercase tracking-wider">
                                Selesai / Siap Diambil
                            </span>
                        @elseif($order->status === 'cancelled')
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-950 text-red-400 border border-red-800 text-xs font-bold uppercase tracking-wider">
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Status Timeline Progress --}}
                @if($order->status !== 'cancelled')
                    <div class="py-4">
                        <div class="grid grid-cols-3 gap-2 relative">
                            {{-- Step 1 --}}
                            <div class="text-center space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition-all {{ in_array($order->status, ['pending', 'processing', 'completed']) ? 'bg-white text-zinc-950 shadow-lg shadow-white/20' : 'bg-zinc-800 text-zinc-500' }}">
                                    1
                                </div>
                                <span class="block text-xs font-bold text-white">Diterima</span>
                                <span class="block text-[10px] text-zinc-500">Pesanan masuk</span>
                            </div>

                            {{-- Step 2 --}}
                            <div class="text-center space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition-all {{ in_array($order->status, ['processing', 'completed']) ? 'bg-white text-zinc-950 shadow-lg shadow-white/20' : 'bg-zinc-800 text-zinc-500' }}">
                                    2
                                </div>
                                <span class="block text-xs font-bold text-white">Diproses</span>
                                <span class="block text-[10px] text-zinc-500">Barista menyiapkan</span>
                            </div>

                            {{-- Step 3 --}}
                            <div class="text-center space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center font-bold text-xs transition-all {{ $order->status === 'completed' ? 'bg-emerald-400 text-zinc-950 shadow-lg shadow-emerald-400/20' : 'bg-zinc-800 text-zinc-500' }}">
                                    3
                                </div>
                                <span class="block text-xs font-bold text-white">Selesai</span>
                                <span class="block text-[10px] text-zinc-500">Siap dinikmati</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Order Items Snapshot --}}
                <div class="bg-zinc-950 border border-zinc-800/80 rounded-2xl p-5 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b border-zinc-800 pb-2">
                        Rincian Item
                    </h3>
                    <div class="space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex justify-between text-xs text-zinc-300">
                                <div>
                                    <span class="font-medium text-white">{{ $item->menu_name }}</span>
                                    <span class="text-zinc-500"> × {{ $item->qty }}</span>
                                </div>
                                <span class="font-mono">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between text-sm font-black text-white pt-3 border-t border-zinc-800">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>
        @elseif(request('code'))
            <div class="text-center py-12 bg-zinc-900/40 border border-zinc-800/80 rounded-3xl p-8">
                <svg class="w-12 h-12 text-zinc-600 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-base font-bold text-white mb-1">Pesanan Tidak Ditemukan</h3>
                <p class="text-zinc-400 text-xs">Pastikan kode pesanan yang Anda masukkan sudah benar (contoh: NCM-20261009-001).</p>
            </div>
        @endif

    </div>
</section>
@endsection
