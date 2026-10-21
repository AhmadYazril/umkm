<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan #{{ $order->code }} — Nucomu Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm mx-auto space-y-4">

        {{-- Header --}}
        <div class="text-center">
            <div class="w-12 h-12 bg-white text-zinc-950 font-black rounded-xl flex items-center justify-center text-base mx-auto mb-2 shadow-lg">
                NU
            </div>
            <span class="font-extrabold text-sm text-white tracking-wider block">NUCOMU CAFE</span>
            <span class="text-[10px] text-zinc-400 uppercase tracking-widest font-mono">Detail Pesanan</span>
        </div>

        {{-- Order Card --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden">

            {{-- Code + Status --}}
            <div class="p-5 border-b border-zinc-800">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest mb-1">Kode Pesanan</p>
                        <p class="font-mono font-black text-xl text-white">{{ $order->code }}</p>
                    </div>
                    @php
                        $statusMap = [
                            'pending'    => ['label' => '⏳ Menunggu',  'cls' => 'bg-amber-950 text-amber-300 border-amber-800'],
                            'processing' => ['label' => '⚙️ Diproses',  'cls' => 'bg-blue-950 text-blue-300 border-blue-800'],
                            'completed'  => ['label' => '✅ Selesai',   'cls' => 'bg-emerald-950 text-emerald-300 border-emerald-800'],
                            'cancelled'  => ['label' => '❌ Dibatalkan','cls' => 'bg-red-950 text-red-300 border-red-800'],
                        ];
                        $s = $statusMap[$order->status] ?? ['label' => $order->status, 'cls' => 'bg-zinc-800 text-zinc-300 border-zinc-700'];
                    @endphp
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full border {{ $s['cls'] }}">{{ $s['label'] }}</span>
                </div>

                <div class="mt-3 pt-3 border-t border-zinc-800">
                    <p class="text-xs text-zinc-400">Nama Pemesan</p>
                    <p class="font-black text-white text-lg">{{ $order->customer_name }}</p>
                </div>

                <div class="mt-2 flex items-center gap-3 text-[11px] text-zinc-500 font-mono">
                    <span>{{ $order->created_at->format('H:i') }} WIB</span>
                    <span>·</span>
                    <span>{{ $order->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            {{-- Items --}}
            <div class="p-5 border-b border-zinc-800">
                <p class="text-[10px] text-zinc-400 uppercase tracking-widest mb-3 font-bold">Rincian Menu</p>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-white text-sm">{{ $item->menu_name }}</p>
                                <p class="text-[11px] text-zinc-400 font-mono">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->qty }}
                                </p>
                            </div>
                            <span class="font-mono font-bold text-white text-sm">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->notes)
                <div class="px-5 py-3 bg-zinc-950/50 border-b border-zinc-800">
                    <p class="text-[10px] text-zinc-400 uppercase tracking-widest mb-1">Catatan</p>
                    <p class="text-xs text-zinc-300 italic">{{ $order->notes }}</p>
                </div>
            @endif

            {{-- Total --}}
            <div class="p-5 bg-zinc-950/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-zinc-400">Total Pembayaran</p>
                        <p class="text-[10px] text-zinc-500">Tunai di kasir</p>
                    </div>
                    <span class="font-mono font-black text-2xl text-amber-400">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Admin login button --}}
        <div class="text-center">
            <a href="{{ route('admin.login') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-white text-xs font-bold transition-colors">
                <i class="fa-solid fa-lock text-xs"></i> Login Admin untuk Update Status
            </a>
        </div>

    </div>
</body>
</html>
