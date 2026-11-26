@extends('layouts.admin')
@section('title', 'Scan QR & Barcode Pesanan Kasir — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-barcode text-[10px]"></i>
                <span>Meja Kasir &bull; QR &amp; Barcode Garis</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Scan QR &amp; Barcode Pesanan
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Arahkan kamera, ambil foto slip, gunakan alat scan barcode kasir (USB/Bluetooth), atau masukkan kode transaksi secara manual.
            </p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition btn-press flex items-center gap-2 shadow-xs">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Kembali ke Pesanan</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

        {{-- Left: QR & 1D Barcode Scanner Panel (Col 6) --}}
        <div class="md:col-span-6 bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-4 shadow-card reveal-on-scroll anim-fade-up">
            <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black border-b border-hairline pb-3 flex items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-camera text-charcoal"></i>
                    <span>Kamera Scanner Kasir</span>
                </span>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                    QR + Code 128
                </span>
            </h2>

            {{-- Peringatan Secure Context untuk hosting HTTP via IP LAN --}}
            <div id="adminHttpCameraNotice" class="hidden bg-amber-50 border border-amber-300 text-amber-950 p-3.5 rounded-xl text-xs space-y-1.5">
                <div class="font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-amber-700"></i>
                    <span>Kamera Live Diblokir Browser di HTTP IP LAN</span>
                </div>
                <p class="leading-relaxed">
                    Browser mewajibkan <strong>HTTPS</strong> atau <strong>localhost</strong> untuk akses streaming kamera (<code id="adminScanOriginText" class="font-mono font-bold"></code>). Solusi langsung pakai:
                </p>
                <ul class="list-disc pl-4 space-y-0.5 text-[11px]">
                    <li>Klik <strong>Foto / Kamera HP</strong> di bawah (langsung buka kamera bawaan HP meski pakai HTTP IP).</li>
                    <li>Tembak barcode garis dengan <strong>alat scanner kasir USB/BT</strong> — kode langsung masuk ke pencarian di samping.</li>
                    <li>Di laptop host, buka via <code>http://localhost:8000/admin/pesanan/scan</code> atau aktifkan HTTPS (ngrok / Laragon SSL).</li>
                </ul>
            </div>

            <div id="reader" class="w-full rounded-2xl overflow-hidden bg-warm-stone border border-hairline flex items-center justify-center" style="min-height: 260px;"></div>
            <div id="adminFileReaderHidden" class="hidden"></div>

            <div id="scanResult" class="hidden bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-xl text-xs font-semibold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <div class="min-w-0">
                    <p class="font-bold">Kode terdeteksi! Mengalihkan ke pesanan...</p>
                    <p id="scanResultUrl" class="text-[11px] font-mono text-emerald-800 mt-0.5 truncate"></p>
                </div>
            </div>

            <div id="scanError" class="hidden bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-xl text-xs">
                <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                <span id="scanErrorMsg">Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan di peramban.</span>
            </div>

            <div class="space-y-2.5">
                <button onclick="startScan()" id="startScanBtn"
                    class="w-full py-3 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-camera text-xs"></i>
                    <span>Mulai Scan Kamera Live</span>
                </button>
                <button onclick="stopScan()" id="stopScanBtn" style="display:none;"
                    class="w-full py-3 rounded-xl bg-warm-stone hover:bg-hairline text-ink-black font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-stop text-xs"></i>
                    <span>Hentikan Kamera</span>
                </button>

                {{-- Native Camera Photo Fallback (Works on HTTP LAN IP on Mobile!) --}}
                <input type="file" id="adminPhotoScanInput" accept="image/*" capture="environment" onchange="handleAdminPhotoScan(this)" class="hidden">
                <label for="adminPhotoScanInput"
                    class="w-full py-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-900 font-bold text-xs uppercase tracking-wider transition shadow-2xs btn-press flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-camera-retro text-xs text-emerald-700"></i>
                    <span>Scan via Foto / Kamera HP (Jalan di HTTP IP)</span>
                </label>
            </div>
        </div>

        {{-- Right: Hardware Barcode Scanner / Manual Search & Result (Col 6) --}}
        <div class="md:col-span-6 space-y-6">
            
            <div class="bg-pure-white border border-hairline rounded-2xl sm:rounded-3xl p-6 sm:p-8 space-y-4 shadow-card reveal-on-scroll">
                <div class="flex items-center justify-between gap-2 border-b border-hairline pb-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-ink-black flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-charcoal"></i>
                        <span>Cari Pesanan &amp; Alat Scanner Barcode</span>
                    </h2>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-bold">
                        <i class="fa-solid fa-barcode"></i>
                        <span>USB/BT Siaga</span>
                    </span>
                </div>

                <form id="adminScanSearchForm" action="{{ route('admin.orders.scan.search') }}" method="GET" class="space-y-3">
                    <div>
                        <label for="adminScanCodeInput" class="block text-xs font-bold text-charcoal mb-1.5">Kode Pesanan Slip (Otomatis Terisi Saat Scan)</label>
                        <input type="text" id="adminScanCodeInput" name="code" value="{{ request('code') }}"
                            autocomplete="off" autofocus
                            placeholder="Scan barcode garis atau ketik: NCM-20261021-001"
                            class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-mono placeholder-muted-text focus:outline-none focus:border-ink-black uppercase tracking-wider"
                            oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-warm-stone hover:bg-ink-black hover:text-white text-ink-black font-bold text-xs uppercase tracking-wider transition shadow-2xs btn-press cursor-pointer">
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
                            <div class="flex justify-between items-center py-1 border-b border-hairline">
                                <span class="text-muted-text">Sumber Transaksi</span>
                                @if($order->channel === 'zendo')
                                    <span class="inline-flex items-center gap-1.5 font-bold text-rose-700">
                                        <img src="{{ asset('images/logos/zendo-courier.png') }}" alt="Zendo" class="w-4 h-4 rounded-full object-cover">
                                        <span>Zendo (Tanpa Barcode)</span>
                                    </span>
                                @elseif($order->channel === 'grab')
                                    <span class="inline-flex items-center gap-1.5 font-bold text-emerald-800">
                                        <img src="{{ asset('images/logos/grabfood.png') }}" alt="GrabFood" class="w-4 h-4 object-contain">
                                        <span>GrabFood (Tanpa Barcode)</span>
                                    </span>
                                @else
                                    <span class="font-bold text-ink-black">Di Tempat (Barcode)</span>
                                @endif
                            </div>
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
                            <div class="flex items-center gap-2.5 min-w-0">
                                @if($ro->channel === 'zendo')
                                    <img src="{{ asset('images/logos/zendo-courier.png') }}" alt="Zendo" class="w-6 h-6 rounded-full object-cover shrink-0">
                                @elseif($ro->channel === 'grab')
                                    <img src="{{ asset('images/logos/grabfood.png') }}" alt="GrabFood" class="w-6 h-6 rounded object-contain bg-white border border-hairline shrink-0">
                                @else
                                    <span class="w-6 h-6 rounded-md bg-warm-stone text-charcoal flex items-center justify-center shrink-0 text-[10px]">
                                        <i class="fa-solid fa-barcode"></i>
                                    </span>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('admin.orders.show', $ro->id) }}" class="font-mono font-bold text-ink-black hover:text-accent-terracotta block truncate">
                                        {{ $ro->code }}
                                    </a>
                                    <span class="text-[10px] text-muted-text block truncate">{{ $ro->customer_name }} &bull; {{ $ro->channel_label }} &bull; {{ $ro->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-2">
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

<!-- HTML5-QRCode & Offline Code 128 + Hardware Scanner Helper -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="{{ asset('js/nucomu-barcode.js') }}"></script>
<script>
    let html5QrCode = null;
    let isScannerRunning = false;

    function checkAdminCameraNotice() {
        const notice = document.getElementById('adminHttpCameraNotice');
        const originText = document.getElementById('adminScanOriginText');
        if (!notice || !window.NucomuBarcode) return;
        const support = window.NucomuBarcode.checkCameraSupport();
        if (originText) originText.textContent = support.origin || window.location.origin;
        notice.classList.toggle('hidden', support.supported);
    }

    async function startScan() {
        const startBtn = document.getElementById('startScanBtn');
        const stopBtn = document.getElementById('stopScanBtn');
        const errorEl = document.getElementById('scanError');
        const errorMsg = document.getElementById('scanErrorMsg');

        errorEl.classList.add('hidden');
        checkAdminCameraNotice();

        if (!window.NucomuBarcode) {
            errorEl.classList.remove('hidden');
            errorMsg.textContent = 'Helper scanner belum termuat.';
            return;
        }

        const support = window.NucomuBarcode.checkCameraSupport();
        if (!support.supported) {
            errorEl.classList.remove('hidden');
            errorMsg.textContent = support.reason + ' Gunakan tombol "Scan via Foto / Kamera HP" atau alat barcode scanner USB/BT.';
            if (window.showAdminToast) {
                window.showAdminToast('Gunakan tombol Foto / Kamera HP atau buka lewat HTTPS / localhost.', 'error', 'Kamera Live Butuh HTTPS');
            }
            return;
        }

        try {
            html5QrCode = await window.NucomuBarcode.startSmartCamera('reader', (decodedText) => {
                onScanSuccess(decodedText);
            });
            isScannerRunning = true;
            startBtn.style.display = 'none';
            stopBtn.style.display = 'flex';
        } catch (err) {
            isScannerRunning = false;
            errorEl.classList.remove('hidden');
            errorMsg.textContent = 'Kamera tidak dapat diakses: ' + (err?.message || err);
            if (window.showAdminToast) {
                window.showAdminToast('Pastikan izin akses kamera diberikan, atau pakai tombol Foto / Kamera HP.', 'error', 'Kamera Gagal Diakses');
            }
        }
    }

    async function handleAdminPhotoScan(inputEl) {
        const file = inputEl?.files?.[0];
        if (!file || !window.NucomuBarcode) return;

        const errorEl = document.getElementById('scanError');
        const errorMsg = document.getElementById('scanErrorMsg');
        errorEl.classList.add('hidden');

        try {
            const decodedText = await window.NucomuBarcode.scanImageFile('adminFileReaderHidden', file);
            onScanSuccess(decodedText);
        } catch (err) {
            errorEl.classList.remove('hidden');
            errorMsg.textContent = 'Barcode / QR tidak terbaca dari foto. Coba foto lebih dekat dan jelas.';
            if (window.showAdminToast) {
                window.showAdminToast('Barcode / QR tidak terdeteksi di foto.', 'error', 'Foto Tidak Terbaca');
            }
        } finally {
            inputEl.value = '';
        }
    }

    function stopScan() {
        if (html5QrCode && isScannerRunning) {
            html5QrCode.stop().then(() => {
                isScannerRunning = false;
                document.getElementById('startScanBtn').style.display = 'flex';
                document.getElementById('stopScanBtn').style.display = 'none';
            }).catch(err => console.error(err));
        }
    }

    function onScanSuccess(decodedText) {
        stopScan();

        const cleanCode = window.NucomuBarcode ? window.NucomuBarcode.extractOrderCode(decodedText) : decodedText.trim();
        const codeInput = document.getElementById('adminScanCodeInput');
        if (codeInput) codeInput.value = cleanCode;

        const resultEl = document.getElementById('scanResult');
        const urlEl = document.getElementById('scanResultUrl');
        resultEl.classList.remove('hidden');
        urlEl.textContent = cleanCode || decodedText;

        if (window.showAdminToast) {
            window.showAdminToast('Kode pesanan terdeteksi! Membuka detail transaksi...', 'success', 'Scan Berhasil');
        }

        if (decodedText.includes('/qr/pesanan/') || decodedText.includes('/pesanan/')) {
            window.location.href = decodedText;
        } else {
            window.location.href = "{{ route('admin.orders.scan.search') }}?code=" + encodeURIComponent(cleanCode);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        checkAdminCameraNotice();

        if (window.NucomuBarcode) {
            window.NucomuBarcode.attachHardwareScanner({
                getInput: () => document.getElementById('adminScanCodeInput'),
                onScan: (cleanCode) => {
                    const inputEl = document.getElementById('adminScanCodeInput');
                    const formEl = document.getElementById('adminScanSearchForm');
                    if (inputEl) inputEl.value = cleanCode;
                    if (formEl) {
                        formEl.submit();
                    } else {
                        onScanSuccess(cleanCode);
                    }
                },
            });
        }
    });
</script>
@endsection
