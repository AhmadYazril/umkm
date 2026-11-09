@extends('layouts.admin')
@section('title', 'Scan QR Pesanan Kasir — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-qrcode text-[10px]"></i>
                <span>Meja Kasir</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Scan QR Code Pesanan
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Arahkan kamera ke QR code slip pesanan pelanggan atau masukkan kode transaksi secara manual.
            </p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition btn-press flex items-center gap-2 shadow-xs">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Kembali ke Pesanan</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

        {{-- Left: QR Scanner Panel (Col 6) --}}
        <div class="md:col-span-6 bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-4 shadow-card reveal-on-scroll anim-fade-up">
            <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black border-b border-hairline pb-3 flex items-center gap-2">
                <i class="fa-solid fa-camera text-charcoal"></i>
                <span>Kamera Scanner Kasir</span>
            </h2>

            <div id="reader" class="w-full rounded-2xl overflow-hidden bg-warm-stone border border-hairline flex items-center justify-center" style="min-height: 280px;"></div>

            <div id="scanResult" class="hidden bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-xl text-xs font-semibold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <div class="min-w-0">
                    <p class="font-bold">QR terdeteksi! Mengalihkan ke pesanan...</p>
                    <p id="scanResultUrl" class="text-[11px] font-mono text-emerald-800 mt-0.5 truncate"></p>
                </div>
            </div>

            <div id="scanError" class="hidden bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-xl text-xs">
                <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                <span id="scanErrorMsg">Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan di peramban.</span>
            </div>

            <button onclick="startScan()" id="startScanBtn"
                class="w-full py-3 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press flex items-center justify-center gap-2">
                <i class="fa-solid fa-camera text-xs"></i>
                <span>Mulai Scan Kamera</span>
            </button>
            <button onclick="stopScan()" id="stopScanBtn" style="display:none;"
                class="w-full py-3 rounded-xl bg-warm-stone hover:bg-hairline text-ink-black font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press flex items-center justify-center gap-2">
                <i class="fa-solid fa-stop text-xs"></i>
                <span>Hentikan Kamera</span>
            </button>
        </div>

        {{-- Right: Manual Search & Result (Col 6) --}}
        <div class="md:col-span-6 space-y-6">
            
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-4 shadow-card reveal-on-scroll">
                <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black border-b border-hairline pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-charcoal"></i>
                    <span>Cari Manual via Kode Pesanan</span>
                </h2>

                <form action="{{ route('admin.orders.scan.search') }}" method="GET" class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-charcoal mb-1.5">Kode Pesanan Slip</label>
                        <input type="text" name="code" value="{{ request('code') }}"
                            placeholder="Contoh: NCM-20261021-001"
                            class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono placeholder-muted-text focus:outline-none focus:border-ink-black uppercase tracking-wider"
                            oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-warm-stone hover:bg-ink-black hover:text-white text-ink-black font-bold text-xs uppercase tracking-wider transition shadow-2xs btn-press">
                        Cari Pesanan
                    </button>
                </form>

                @if(request('code') && !isset($order))
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-xl text-xs">
                        Kode pesanan "<span class="font-mono font-bold">{{ request('code') }}</span>" tidak ditemukan dalam database.
                    </div>
                @endif
            </div>

            @if(isset($order))
                <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl overflow-hidden shadow-card anim-fade-up">
                    <div class="p-6 border-b border-hairline">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <span class="text-[10px] font-mono text-muted-text uppercase block font-bold">Pesanan Ditemukan</span>
                                <h3 class="text-xl font-extrabold text-ink-black font-mono">{{ $order->code }}</h3>
                            </div>
                            @php
                                $statusMap = [
                                    'pending' => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-800 border-amber-200'],
                                    'processing' => ['label' => 'Diproses', 'class' => 'bg-blue-50 text-blue-800 border-blue-200'],
                                    'ready' => ['label' => 'Siap Saji', 'class' => 'bg-purple-50 text-purple-800 border-purple-200'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                                    'cancelled' => ['label' => 'Batal', 'class' => 'bg-rose-50 text-rose-800 border-rose-200'],
                                ];
                                $s = $statusMap[$order->status] ?? ['label' => $order->status_label ?? $order->status, 'class' => 'bg-warm-stone text-charcoal border-hairline'];
                            @endphp
                            <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $s['class'] }}">{{ $s['label'] }}</span>
                        </div>

                        <div class="space-y-2 text-xs text-charcoal">
                            <div class="flex justify-between py-1 border-b border-hairline">
                                <span class="text-muted-text">Nama Pelanggan</span>
                                <span class="text-ink-black font-bold">{{ $order->customer_name }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-hairline">
                                <span class="text-muted-text">Tipe Pesanan</span>
                                <span class="uppercase font-bold">{{ str_replace('_', ' ', $order->order_type) }}</span>
                            </div>
                            @if($order->table)
                                <div class="flex justify-between py-1 border-b border-hairline">
                                    <span class="text-muted-text">Nomor Meja</span>
                                    <span class="font-bold">Meja #{{ $order->table->number }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-1.5 text-sm font-bold">
                                <span>Total Tagihan</span>
                                <span class="font-mono text-ink-black">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 flex items-center gap-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="flex-1 py-2.5 bg-ink-black hover:bg-charcoal text-white rounded-xl text-center text-xs font-bold transition shadow-xs btn-press">
                                Buka Detail Pesanan
                            </a>
                            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="p-2.5 bg-warm-stone hover:bg-pure-white text-ink-black border border-hairline rounded-xl text-xs font-bold transition btn-press" title="Cetak Struk">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Pesanan Terkini List --}}
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 shadow-card anim-fade-up anim-delay-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-ink-black mb-3 pb-2 border-b border-hairline">
                    5 Pesanan Terakhir
                </h3>
                <div class="divide-y divide-hairline">
                    @forelse($recentOrders as $ro)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div>
                                <a href="{{ route('admin.orders.show', $ro->id) }}" class="font-mono font-bold text-ink-black hover:text-accent-terracotta block">
                                    {{ $ro->code }}
                                </a>
                                <span class="text-[10px] text-muted-text">{{ $ro->customer_name }} &bull; {{ $ro->created_at->format('H:i') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-ink-black block">Rp {{ number_format($ro->total, 0, ',', '.') }}</span>
                                <span class="text-[9px] font-bold text-muted-text">{{ $ro->status_label }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-muted-text text-xs">
                            Belum ada pesanan terbaru.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

<!-- HTML5-QRCode Scanner Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrCode = null;

    function startScan() {
        const readerElement = document.getElementById('reader');
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');
        const errorEl = document.getElementById('scanError');

        errorEl.classList.add('hidden');

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        const config = { fps: 10, qrbox: { width: 220, height: 220 } };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                onScanSuccess(decodedText);
            },
            (errorMessage) => {
                // scanning...
            }
        ).then(() => {
            startBtn.style.display = 'none';
            stopBtn.style.display = 'block';
        }).catch((err) => {
            errorEl.classList.remove('hidden');
            document.getElementById('scanErrorMsg').textContent = 'Kamera tidak dapat diakses: ' + err;
            if (window.showAdminToast) {
                window.showAdminToast('Pastikan izin akses kamera diberikan pada peramban.', 'error', 'Kamera Gagal Diakses');
            }
        });
    }

    function stopScan() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                document.getElementById('startScanBtn').style.display = 'block';
                document.getElementById('stopScanBtn').style.display = 'none';
            }).catch(err => console.error(err));
        }
    }

    function onScanSuccess(decodedText) {
        stopScan();

        const resultEl = document.getElementById('scanResult');
        const urlEl = document.getElementById('scanResultUrl');
        resultEl.classList.remove('hidden');
        urlEl.textContent = decodedText;

        if (window.showAdminToast) {
            window.showAdminToast('QR code terdeteksi! Membuka detail transaksi...', 'success', 'Scan Berhasil');
        }

        // Redirect if URL or search by code
        if (decodedText.includes('/qr/pesanan/') || decodedText.includes('/pesanan/')) {
            window.location.href = decodedText;
        } else {
            window.location.href = "{{ route('admin.orders.scan.search') }}?code=" + encodeURIComponent(decodedText);
        }
    }
</script>
@endsection
