@extends('layouts.admin')
@section('title', 'Scan QR Pesanan — Admin Nucomu Cafe')

@section('content')
<div class="space-y-8">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black uppercase text-white tracking-tight">Scan QR Pesanan</h1>
            <p class="text-xs text-zinc-400 mt-1">Scan QR code dari slip pelanggan untuk langsung melihat detail pesanan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- QR Scanner --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b border-zinc-800 pb-3">
                <i class="fa-solid fa-camera mr-2"></i> Kamera Scanner
            </h2>

            <div id="reader" class="w-full rounded-2xl overflow-hidden bg-zinc-950 border border-zinc-800" style="min-height: 280px;"></div>

            <div id="scanResult" class="hidden bg-emerald-950/60 border border-emerald-800 text-emerald-300 p-4 rounded-xl text-sm font-semibold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-400 text-xl"></i>
                <div>
                    <p>QR terdeteksi! Mengalihkan...</p>
                    <p id="scanResultUrl" class="text-xs font-mono text-emerald-400 mt-0.5 truncate"></p>
                </div>
            </div>

            <div id="scanError" class="hidden bg-red-950/60 border border-red-800 text-red-300 p-4 rounded-xl text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                <span id="scanErrorMsg">Tidak dapat mengakses kamera.</span>
            </div>

            <button onclick="startScan()" id="startScanBtn"
                class="w-full py-3 rounded-xl bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider transition-all">
                <i class="fa-solid fa-camera mr-2"></i> Mulai Scan Kamera
            </button>
            <button onclick="stopScan()" id="stopScanBtn" style="display:none;"
                class="w-full py-3 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-white font-bold text-xs uppercase transition-all">
                <i class="fa-solid fa-stop mr-2"></i> Stop Scan
            </button>
        </div>

        {{-- Manual Search --}}
        <div class="space-y-6">
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b border-zinc-800 pb-3">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari Manual via Kode
                </h2>

                <form action="{{ route('admin.orders.scan.search') }}" method="GET" class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 mb-1">Kode Pesanan</label>
                        <input type="text" name="code" value="{{ request('code') }}"
                            placeholder="Contoh: NCM-20261021-001"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-3.5 py-2.5 text-sm text-white font-mono placeholder-zinc-600 focus:outline-none focus:border-white uppercase tracking-widest"
                            oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-white hover:bg-zinc-200 text-zinc-950 font-black text-xs uppercase tracking-wider transition-all">
                        Cari Pesanan
                    </button>
                </form>

                @if(request('code') && !isset($order))
                    <div class="bg-red-950/50 border border-red-800 text-red-300 p-3 rounded-xl text-xs">
                        Kode "<span class="font-mono font-bold">{{ request('code') }}</span>" tidak ditemukan.
                    </div>
                @endif
            </div>

            @if(isset($order))
                <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden">
                    {{-- Order found card --}}
                    <div class="p-6 border-b border-zinc-800">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <span class="text-[10px] font-mono text-zinc-400 uppercase block">Ditemukan</span>
                                <h3 class="text-xl font-black text-white font-mono">{{ $order->code }}</h3>
                            </div>
                            @php
                                $statusMap = [
                                    'pending' => ['label' => 'Menunggu', 'class' => 'bg-amber-950 text-amber-300 border-amber-800'],
                                    'processing' => ['label' => 'Diproses', 'class' => 'bg-blue-950 text-blue-300 border-blue-800'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-emerald-950 text-emerald-300 border-emerald-800'],
                                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-red-950 text-red-300 border-red-800'],
                                ];
                                $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-zinc-800 text-zinc-300 border-zinc-700'];
                            @endphp
                            <span class="text-xs font-bold px-3 py-1.5 rounded-full border {{ $s['class'] }}">{{ $s['label'] }}</span>
                        </div>

                        <div class="space-y-2 text-xs text-zinc-400">
                            <div class="flex justify-between">
                                <span>Nama</span>
                                <span class="text-white font-bold">{{ $order->customer_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total</span>
                                <span class="text-white font-bold font-mono">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Item</span>
                                <span class="text-white font-bold">{{ $order->items->count() }} menu</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-white text-zinc-950 font-black text-xs uppercase tracking-wider hover:bg-zinc-200 transition-all">
                            <i class="fa-solid fa-eye"></i> Lihat Detail Pesanan
                        </a>
                    </div>
                </div>
            @endif

            {{-- Recent orders --}}
            <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 sm:p-8">
                <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-400 border-b border-zinc-800 pb-3 mb-4">
                    <i class="fa-solid fa-clock mr-2"></i> Pesanan Terbaru
                </h2>
                <div class="space-y-3">
                    @foreach($recentOrders as $ro)
                        <a href="{{ route('admin.orders.show', $ro->id) }}"
                           class="flex items-center justify-between p-3 rounded-xl bg-zinc-950 border border-zinc-800 hover:border-zinc-600 transition-all group">
                            <div>
                                <p class="text-xs font-bold text-white font-mono">{{ $ro->code }}</p>
                                <p class="text-[10px] text-zinc-400">{{ $ro->customer_name }} · {{ $ro->items->count() }} menu</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-mono text-white font-bold">Rp {{ number_format($ro->total, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-zinc-500">{{ $ro->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Html5-QRCode library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5Qrcode = null;

    function startScan() {
        document.getElementById('startScanBtn').style.display = 'none';
        document.getElementById('stopScanBtn').style.display = 'block';
        document.getElementById('scanError').classList.add('hidden');

        html5Qrcode = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(cameras => {
            if (!cameras || cameras.length === 0) {
                showScanError('Tidak ada kamera yang tersedia.');
                return;
            }

            const cameraId = cameras[cameras.length - 1].id; // Prefer back camera

            html5Qrcode.start(
                cameraId,
                { fps: 10, qrbox: { width: 220, height: 220 } },
                (decodedText) => {
                    handleScanResult(decodedText);
                },
                () => {} // Ignore decode errors (continuous scanning)
            ).catch(err => {
                showScanError('Gagal mengakses kamera: ' + err);
            });
        }).catch(err => {
            showScanError('Tidak dapat mengakses kamera: ' + err);
        });
    }

    function stopScan() {
        if (html5Qrcode) {
            html5Qrcode.stop().then(() => {
                document.getElementById('startScanBtn').style.display = 'block';
                document.getElementById('stopScanBtn').style.display = 'none';
            });
        }
    }

    function handleScanResult(text) {
        // Stop scanning after first successful scan
        stopScan();

        document.getElementById('scanResult').classList.remove('hidden');
        document.getElementById('scanResultUrl').innerText = text;

        // Redirect after short delay
        setTimeout(() => {
            window.location.href = text;
        }, 1000);
    }

    function showScanError(msg) {
        document.getElementById('scanError').classList.remove('hidden');
        document.getElementById('scanErrorMsg').innerText = msg;
        document.getElementById('startScanBtn').style.display = 'block';
        document.getElementById('stopScanBtn').style.display = 'none';
    }
</script>
@endsection
