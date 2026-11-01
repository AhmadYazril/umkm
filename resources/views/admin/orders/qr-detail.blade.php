<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan #{{ $order->code }} — Nucomu Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ink-black': '#191919',
                        'warm-ivory': '#FAF8F5',
                        'warm-stone': '#F5F2EC',
                        'hairline': '#E5E5E5',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-warm-ivory text-ink-black font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm mx-auto space-y-4">

        {{-- Header --}}
        <div class="text-center">
            <div class="w-12 h-12 bg-ink-black text-white font-bold rounded-2xl flex items-center justify-center text-base mx-auto mb-2 shadow-xs">
                <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu" class="h-6 w-auto object-contain brightness-0 invert">
            </div>
            <span class="font-extrabold text-sm text-ink-black tracking-wider block">NUCOMU CAFE</span>
            <span class="text-[10px] text-gray-500 uppercase tracking-widest font-mono">Slip QR Kasir</span>
        </div>

        {{-- Order Card --}}
        <div class="bg-white border border-hairline rounded-3xl overflow-hidden shadow-sm">

            {{-- Code + Status --}}
            <div class="p-5 border-b border-hairline">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Kode Pesanan</p>
                        <p class="font-mono font-bold text-xl text-ink-black">{{ $order->code }}</p>
                    </div>
                    @php
                        $statusMap = [
                            'pending'    => ['label' => 'Menunggu',  'cls' => 'bg-amber-50 text-amber-800 border-amber-200'],
                            'processing' => ['label' => 'Diproses',  'cls' => 'bg-blue-50 text-blue-800 border-blue-200'],
                            'completed'  => ['label' => 'Selesai',   'cls' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                            'cancelled'  => ['label' => 'Dibatalkan','cls' => 'bg-rose-50 text-rose-800 border-rose-200'],
                        ];
                        $s = $statusMap[$order->status] ?? ['label' => $order->status, 'cls' => 'bg-warm-stone text-gray-800 border-hairline'];
                    @endphp
                    <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $s['cls'] }}">{{ $s['label'] }}</span>
                </div>

                <div class="mt-3 pt-3 border-t border-hairline">
                    <p class="text-xs text-gray-500">Nama Pemesan</p>
                    <p class="font-bold text-ink-black text-base">{{ $order->customer_name }}</p>
                </div>

                <div class="mt-2 flex items-center gap-3 text-[11px] text-gray-500 font-mono">
                    <span>{{ $order->created_at->format('H:i') }} WIB</span>
                    <span>&bull;</span>
                    <span>{{ $order->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            {{-- Items --}}
            <div class="p-5 border-b border-hairline">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-3 font-bold">Rincian Menu</p>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-ink-black text-sm">{{ $item->menu_name }}</p>
                                <p class="text-[11px] text-gray-500 font-mono">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->qty }}
                                </p>
                            </div>
                            <span class="font-mono font-bold text-ink-black text-sm">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->notes)
                <div class="px-5 py-3 bg-warm-stone/40 border-b border-hairline">
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Catatan</p>
                    <p class="text-xs text-ink-black italic">{{ $order->notes }}</p>
                </div>
            @endif

            {{-- Total --}}
            <div class="p-5 bg-warm-stone/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-bold">Total Pembayaran</p>
                        <p class="text-[10px] text-gray-500">Bayar di kasir</p>
                    </div>
                    <span class="font-mono font-bold text-2xl text-ink-black">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Admin login button --}}
        <div class="text-center">
            <a href="{{ route('admin.login') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-hairline text-gray-700 hover:text-ink-black text-xs font-bold transition shadow-xs">
                <i class="fa-solid fa-lock text-xs"></i> 
                <span>Login Admin untuk Proses Pesanan</span>
            </a>
        </div>

    </div>
</body>
</html>
