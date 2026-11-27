@php
    $activeTheme = ($siteTheme ?? \App\Models\Setting::getSiteTheme()) === 'dark' ? 'dark' : 'light';
    $isDarkTheme = $activeTheme === 'dark';
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $activeTheme }}" class="{{ $isDarkTheme ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="{{ $activeTheme }}">
    <title>Slip Pesanan #{{ $order->code }} — Nucomu Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/nucomu.css') }}?v={{ filemtime(public_path('css/nucomu.css')) }}">
    {{-- QR Code & Offline 1D Code 128 Barcode Libraries --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js"></script>
    <script src="{{ asset('js/nucomu-barcode.js') }}"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        obsidian: 'var(--bg-page)',
                        charcoal: 'var(--surface-solid)',
                        goldAccent: 'var(--accent-solid)',
                        violetAccent: 'var(--text-muted)',
                        cyanAccent: 'var(--text-secondary)',
                    },
                    fontFamily: {
                        sans: ['"Montserrat"', 'sans-serif'],
                        editorial: ['"Playfair Display"', 'Georgia', 'serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: var(--surface);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
        }
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .print-card { background: white !important; border: 2px solid #e5e7eb !important; color: black !important; }
            .print-gold { color: #111111 !important; }
        }
    </style>
</head>
<body class="bg-[var(--bg-page)] text-[var(--text)] font-sans min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">

    {{-- Ambient Glow --}}
    <div class="fixed top-10 left-1/3 w-96 h-96 rounded-full pointer-events-none z-0 blur-3xl" style="background: var(--ambient-glow-top);"></div>
    <div class="fixed bottom-0 right-0 w-80 h-80 rounded-full pointer-events-none z-0 blur-3xl" style="background: var(--ambient-glow-top);"></div>

    <div class="relative z-10 w-full max-w-md mx-auto space-y-4">

        {{-- SUCCESS BADGE --}}
        <div class="text-center space-y-2 no-print">
            <div class="w-14 h-14 rounded-full bg-emerald-500/15 border-2 border-emerald-500 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/10">
                <i class="fa-solid fa-check text-emerald-600 dark:text-emerald-400 text-xl"></i>
            </div>
            <h1 class="text-xl font-black text-[var(--text)]">Pesanan Dibuat!</h1>
            <p class="text-[var(--text-muted)] text-xs sm:text-sm">Tunjukkan QR code di bawah ini ke kasir atau scan via Google Lens di HP.</p>
        </div>

        {{-- SLIP CARD (Printable) --}}
        <div class="glass-card rounded-3xl overflow-hidden print-card shadow-[var(--shadow)] border border-[var(--border)]">

            {{-- Header Slip --}}
            <div class="bg-[var(--surface-strong-solid)] border-b border-[var(--border)] px-6 py-5 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fa-solid fa-mug-hot text-[var(--text)]"></i>
                    <span class="font-black text-lg text-[var(--text)] tracking-wider">NUCOMU CAFE</span>
                </div>
                <p class="text-[10px] text-[var(--text-muted)] uppercase tracking-widest">Coffee & Stories · Slip Pesanan</p>
            </div>

            {{-- Customer Info --}}
            <div class="px-6 py-4 border-b border-[var(--border-soft)]">
                <div class="flex items-center justify-between text-sm">
                    <div>
                        <p class="text-[var(--text-muted)] text-xs mb-0.5">Nama Pemesan</p>
                        <p class="font-black text-[var(--text)] text-lg">{{ $order->customer_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[var(--text-muted)] text-xs mb-0.5">Kode Pesanan</p>
                        <p class="font-mono font-black text-[var(--text)] print-gold text-base">{{ $order->code }}</p>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-3 text-xs text-[var(--text-muted)]">
                    <span><i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('H:i') }} WIB</span>
                    <span>·</span>
                    <span><i class="fa-regular fa-calendar mr-1"></i>{{ $order->created_at->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            {{-- SWITCHABLE QR CODE & 1D LINEAR BARCODE SECTION --}}
            <div class="px-6 py-6 flex flex-col items-center border-b border-[var(--border-soft)] bg-[var(--badge-bg)]">
                {{-- Switcher Tab: QR Code vs Barcode Bergaris (Code 128) --}}
                <div class="grid grid-cols-2 gap-1.5 bg-[var(--surface-solid)] border border-[var(--border)] p-1 rounded-2xl w-full max-w-[300px] mb-4 no-print">
                    <button type="button"
                            id="slipModeBtn-qr"
                            onclick="switchSlipCodeMode('qr')"
                            class="py-2 px-3 rounded-xl text-[11px] font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5 bg-[var(--accent-solid)] text-[var(--accent-solid-text)] shadow-xs">
                        <i class="fa-solid fa-qrcode text-xs"></i>
                        <span>QR Code</span>
                    </button>
                    <button type="button"
                            id="slipModeBtn-barcode"
                            onclick="switchSlipCodeMode('barcode')"
                            class="py-2 px-3 rounded-xl text-[11px] font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5 text-[var(--text-muted)] hover:text-[var(--text)]">
                        <i class="fa-solid fa-barcode text-xs"></i>
                        <span>Barcode Garis</span>
                    </button>
                </div>

                {{-- MODE 1: QR CODE --}}
                <div id="slipQrModeWrap" class="w-full flex flex-col items-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[var(--surface-solid)] border border-[var(--border)] text-[10px] text-[var(--text)] font-semibold mb-3">
                        <i class="fa-solid fa-camera"></i> Siap Scan Kamera Kasir / Google Lens
                    </div>

                    {{-- White QR Plate Container --}}
                    <div class="bg-white p-4 rounded-3xl border border-[var(--border)] shadow-xl flex items-center justify-center min-w-[210px] min-h-[210px]" id="qrContainer">
                        <canvas id="qrCanvas" class="hidden"></canvas>
                        <div id="qrcodeJsTarget"></div>
                        <img id="qrFallbackImg" class="hidden w-[200px] h-[200px] object-contain rounded" alt="QR Code">
                    </div>

                    {{-- Host IP & Direct URL Info for User & Kasir --}}
                    <div class="mt-4 w-full bg-[var(--surface-solid)] border border-[var(--border)] rounded-2xl p-3 text-center space-y-1.5 no-print">
                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-[var(--text-muted)]">
                            <i class="fa-solid fa-wifi text-emerald-600 dark:text-emerald-400 text-[10px]"></i>
                            <span class="font-semibold text-[var(--text)]">Target Akses IP:</span>
                            <span class="font-mono text-[var(--text)] font-bold" id="currentScanHost">{{ $host }}{{ $portSuffix }}</span>
                        </div>

                        <div class="flex items-center justify-center gap-2 pt-1">
                            <button type="button" onclick="copyScanUrl()" class="px-3 py-1 bg-[var(--badge-bg)] hover:bg-[var(--btn-outline-hover-bg)] text-[var(--text)] border border-[var(--border)] rounded-lg text-[10px] font-bold transition-all flex items-center gap-1.5">
                                <i class="fa-regular fa-copy"></i>
                                <span id="copyBtnText">Salin Tautan</span>
                            </button>
                            <button type="button" onclick="toggleIpModal()" class="px-3 py-1 bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] rounded-lg text-[10px] font-bold transition-all flex items-center gap-1.5">
                                <i class="fa-solid fa-gear"></i>
                                <span>Ganti Host IP</span>
                            </button>
                        </div>

                        <p class="text-[9px] text-[var(--text-faint)] font-mono pt-1" id="fullUrlPreview">{{ $adminOrderUrl }}</p>
                    </div>

                    <p class="text-[10px] text-[var(--text-faint)] mt-2.5 text-center">
                        Tunjukkan QR code ini ke kasir atau ganti ke mode Barcode Garis untuk alat scanner kasir.
                    </p>
                </div>

                {{-- MODE 2: 1D LINEAR BARCODE (CODE 128B — ENCODES CLEAN ORDER CODE) --}}
                <div id="slipBarcodeModeWrap" class="hidden w-full flex flex-col items-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-[10px] text-emerald-700 dark:text-emerald-300 font-semibold mb-3">
                        <i class="fa-solid fa-barcode"></i> Code 128 &bull; Siap Scan Alat Scanner Kasir
                    </div>

                    <div class="bg-white px-4 py-5 rounded-3xl border border-[var(--border)] shadow-xl w-full flex flex-col items-center justify-center">
                        <div id="slipLinearBarcodeTarget" class="w-full"></div>
                    </div>

                    <p class="text-[10.5px] text-[var(--text-muted)] mt-3 text-center leading-relaxed">
                        Barcode garis ini memuat kode <span class="font-mono font-bold text-[var(--text)]">{{ $order->code }}</span>.<br>
                        Saat di-scan dengan alat scanner kasir, kode langsung otomatis tertulis di pencarian pesanan.
                    </p>
                </div>
            </div>

            {{-- ORDER ITEMS --}}
            <div class="px-6 py-4 border-b border-[var(--border-soft)]">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-[var(--text-muted)] mb-3">Rincian Pesanan</h3>
                <div class="space-y-2.5">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex-1">
                                <p class="font-semibold text-[var(--text)]">{{ $item->menu_name }}</p>
                                <p class="text-xs text-[var(--text-muted)] font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->qty }}</p>
                            </div>
                            <span class="font-mono font-bold text-[var(--text)] ml-4">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- DELIVERY ADDRESS & NOTES --}}
            @if(!empty($deliveryAddress))
                <div class="px-6 py-3.5 border-b border-[var(--border-soft)] bg-emerald-500/10 space-y-1">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] text-emerald-700 dark:text-emerald-400 font-bold uppercase tracking-widest">
                            <i class="fa-solid fa-location-dot mr-1"></i> Alamat Pengiriman (Kurir Zendo)
                        </p>
                        @if(!empty($distanceKm))
                            <span class="text-[10px] font-mono font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-500/15 px-2 py-0.5 rounded-full">
                                {{ number_format((float) $distanceKm, 1, ',', '.') }} km dari Kafe
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-[var(--text)]">{{ $deliveryAddress }}</p>
                    @if(!empty($customerLat) && !empty($customerLng))
                        <a href="https://www.google.com/maps?q={{ $customerLat }},{{ $customerLng }}" target="_blank"
                           class="inline-flex items-center gap-1 text-[10px] text-emerald-700 dark:text-emerald-400 hover:underline font-semibold pt-0.5">
                            <i class="fa-solid fa-map-pin"></i> Buka Titik Peta Pengiriman ↗
                        </a>
                    @endif
                </div>
            @endif

            @if(!empty($cleanNotes ?? $order->notes))
                <div class="px-6 py-3 border-b border-[var(--border-soft)] bg-[var(--badge-bg)]">
                    <p class="text-[10px] text-[var(--text-muted)] uppercase tracking-widest mb-1">Catatan</p>
                    <p class="text-xs text-[var(--text-secondary)] italic">{{ $cleanNotes ?? $order->notes }}</p>
                </div>
            @endif

            {{-- TOTAL & ONGKIR BREAKDOWN --}}
            <div class="px-6 py-5 bg-[var(--surface-strong-solid)] space-y-2">
                @if(!empty($isZendoOrder) && !empty($deliveryFee) && $deliveryFee > 0)
                    <div class="flex items-center justify-between text-xs text-[var(--text-muted)]">
                        <span>Subtotal Menu</span>
                        <span class="font-mono font-semibold text-[var(--text)]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-emerald-700 dark:text-emerald-400 pb-2 border-b border-[var(--border-soft)]">
                        <span>
                            @if($order->kind === 'zendo_chat' || empty($distanceKm))
                                Ongkir (perkiraan, final dikonfirmasi kurir)
                            @else
                                Ongkir Kurir Zendo ({{ number_format((float) $distanceKm, 1, ',', '.') }} km)
                            @endif
                        </span>
                        <span class="font-mono font-bold">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-[var(--text-muted)]">Total Pembayaran</p>
                        <p class="text-[10px] text-[var(--text-faint)]">
                            {{ !empty($isZendoOrder) ? '*Terkirim otomatis ke kontak Kurir Zendo' : '*Bayar tunai di kasir' }}
                        </p>
                    </div>
                    <span class="font-mono font-black text-2xl text-[var(--text)] print-gold">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Footer Slip --}}
            @php
                $slipBrand = $brand ?? \App\Http\Controllers\NucomuController::resolveBrandSettings();
                $slipContact = $contact ?? \App\Http\Controllers\NucomuController::getContactSettings();
            @endphp
            <div class="px-6 py-4 text-center bg-[var(--badge-bg)]">
                <p class="text-[10px] text-[var(--text-faint)]">Terima kasih atas kunjungan Anda 🙏</p>
                <p class="text-[10px] text-[var(--text-faint)]">{{ $slipBrand['name'] ?? 'Nucomu Cafe' }} · {{ $slipContact['hours'] ?? config('nucomu.contact.hours', 'Senin – Sabtu: 11:00 – 21:00 WIB | Minggu: 11:00 – 22:00 WIB') }}</p>
            </div>
        </div>

        {{-- ZENDO WHATSAPP BUTTON (IF ZENDO ENABLED OR ZENDO ORDER) --}}
        @if(!empty($zendoWhatsappUrl) && (!empty($isZendoOrder) || ($transactionMode ?? 'both') !== 'kasir'))
            <div class="no-print">
                <a href="{{ $zendoWhatsappUrl }}" target="_blank"
                   class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-brands fa-whatsapp text-base"></i>
                    <span>{{ !empty($isZendoOrder) ? 'Hubungi Kontak Kurir Zendo (+' . ($zendoWaClean ?? '') . ')' : 'Pesan Delivery via Kurir Zendo' }}</span>
                </a>
            </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-col sm:flex-row gap-3 no-print">
            <button onclick="window.print()"
                class="flex-1 py-3.5 rounded-2xl bg-[var(--accent-solid)] hover:bg-[var(--accent-solid-hover)] text-[var(--accent-solid-text)] font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                <i class="fa-solid fa-print"></i> Cetak / Screenshot
            </button>
            <a href="{{ route('nucomu.order') }}"
               class="flex-1 py-3.5 rounded-2xl glass-card border border-[var(--border)] text-[var(--text-muted)] font-bold text-sm hover:text-[var(--text)] transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Menu
            </a>
        </div>

        {{-- Status pesanan --}}
        <div class="glass-card rounded-2xl p-4 border border-[var(--border)] no-print">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full {{ !empty($isZendoOrder) ? 'bg-emerald-500/20' : 'bg-[var(--badge-bg)]' }} flex items-center justify-center shrink-0">
                        <i class="{{ !empty($isZendoOrder) ? 'fa-solid fa-check-double text-emerald-600 dark:text-emerald-400' : 'fa-solid fa-clock text-[var(--text)]' }} text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[var(--text)]">
                            Status:
                            <span class="{{ !empty($isZendoOrder) ? 'text-emerald-600 dark:text-emerald-400' : 'text-[var(--text)]' }}">
                                {{ !empty($isZendoOrder) ? 'Terkirim Otomatis ke Kontak Zendo' : 'Menunggu Kasir' }}
                            </span>
                        </p>
                        <p class="text-[10px] text-[var(--text-muted)]">
                            {{ !empty($isZendoOrder) ? 'Pesanan & titik alamat Anda telah dikirim ke kontak Kurir Zendo.' : 'Tunjukkan slip ini ke kasir untuk membayar.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL GANTI HOST IP CONFIG --}}
    <div id="ipModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-[var(--surface-solid)] w-full max-w-sm rounded-3xl p-6 border border-[var(--border)] shadow-2xl relative">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-[var(--text)] flex items-center gap-2">
                    <i class="fa-solid fa-network-wired text-[var(--text)]"></i>
                    <span>Pengaturan Host IP QR</span>
                </h3>
                <button onclick="toggleIpModal()" class="text-[var(--text-muted)] hover:text-[var(--text)] text-sm"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <p class="text-[11px] text-[var(--text-muted)] mb-3 leading-relaxed">
                Sesuaikan alamat IP dari perintah <code class="text-[var(--text)] font-mono">ipconfig</code> komputer kasir agar HP di jaringan Wi-Fi lokal dapat langsung mengakses data pesanan.
            </p>

            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-[var(--text)] uppercase mb-1">Host IP / Domain</label>
                    <input type="text" id="customHostInput" value="{{ $host }}" placeholder="Contoh: 192.168.0.104"
                           class="w-full bg-[var(--field-bg)] border border-[var(--field-border)] rounded-xl px-3 py-2 text-xs font-mono text-[var(--text)] focus:outline-none focus:border-[var(--field-focus)]">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-[var(--text)] uppercase mb-1">Port (Kosongkan jika port 80)</label>
                    <input type="text" id="customPortInput" value="{{ ltrim($portSuffix, ':') }}" placeholder="8000"
                           class="w-full bg-[var(--field-bg)] border border-[var(--field-border)] rounded-xl px-3 py-2 text-xs font-mono text-[var(--text)] focus:outline-none focus:border-[var(--field-focus)]">
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="button" onclick="applyCustomHost()" class="flex-1 py-2.5 rounded-xl bg-[var(--accent-solid)] text-[var(--accent-solid-text)] font-extrabold text-xs uppercase tracking-wider hover:bg-[var(--accent-solid-hover)] transition-all">
                        Terapkan ke QR
                    </button>
                    <button type="button" onclick="toggleIpModal()" class="py-2.5 px-4 rounded-xl bg-[var(--badge-bg)] border border-[var(--border)] text-[var(--text-muted)] text-xs font-bold">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentAdminUrl = '{{ $adminOrderUrl }}';
        const scheme = '{{ $scheme }}';
        const orderCode = '{{ $order->code }}';
        const initialSlipCodeMode = '{{ $initialBarcodeMode ?? 'qr' }}';

        function switchSlipCodeMode(mode) {
            const normalized = mode === 'barcode' ? 'barcode' : 'qr';
            const qrWrap = document.getElementById('slipQrModeWrap');
            const barWrap = document.getElementById('slipBarcodeModeWrap');
            const qrBtn = document.getElementById('slipModeBtn-qr');
            const barBtn = document.getElementById('slipModeBtn-barcode');

            if (qrWrap) qrWrap.classList.toggle('hidden', normalized !== 'qr');
            if (barWrap) barWrap.classList.toggle('hidden', normalized !== 'barcode');

            if (qrBtn) {
                qrBtn.className = normalized === 'qr'
                    ? 'py-2 px-3 rounded-xl text-[11px] font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5 bg-[var(--accent-solid)] text-[var(--accent-solid-text)] shadow-xs'
                    : 'py-2 px-3 rounded-xl text-[11px] font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5 text-[var(--text-muted)] hover:text-[var(--text)]';
            }
            if (barBtn) {
                barBtn.className = normalized === 'barcode'
                    ? 'py-2 px-3 rounded-xl text-[11px] font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5 bg-[var(--accent-solid)] text-[var(--accent-solid-text)] shadow-xs'
                    : 'py-2 px-3 rounded-xl text-[11px] font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5 text-[var(--text-muted)] hover:text-[var(--text)]';
            }

            if (normalized === 'barcode' && window.NucomuBarcode) {
                window.NucomuBarcode.renderCode128Svg('#slipLinearBarcodeTarget', orderCode, {
                    barHeight: 74,
                    moduleWidth: 2,
                    fontSize: 15,
                    maxWidth: '320px',
                });
            }
        }

        function renderQrCode(url) {
            const container = document.getElementById('qrcodeJsTarget');
            container.innerHTML = '';
            document.getElementById('qrFallbackImg').classList.add('hidden');
            document.getElementById('qrCanvas').classList.add('hidden');

            let rendered = false;

            // Strategy 1: QRCodeJS (DOM based SVG/Table/Canvas)
            if (typeof QRCode === 'function') {
                try {
                    new QRCode(container, {
                        text: url,
                        width: 200,
                        height: 200,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel ? QRCode.CorrectLevel.M : 2
                    });
                    rendered = true;
                } catch (e) {
                    console.warn('QRCodeJS error:', e);
                }
            }

            // Strategy 2: QRCode.toCanvas
            if (!rendered && typeof QRCode !== 'undefined' && typeof QRCode.toCanvas === 'function') {
                const canvas = document.getElementById('qrCanvas');
                canvas.classList.remove('hidden');
                QRCode.toCanvas(canvas, url, {
                    width: 200,
                    margin: 1,
                    color: { dark: '#000000', light: '#ffffff' }
                }, function (err) {
                    if (err) fallbackToApiImage(url);
                });
                rendered = true;
            }

            // Strategy 3: Dynamic Image API Fallback
            if (!rendered) {
                fallbackToApiImage(url);
            }
        }

        function fallbackToApiImage(url) {
            const img = document.getElementById('qrFallbackImg');
            const encoded = encodeURIComponent(url);
            img.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=2&data=${encoded}`;
            img.classList.remove('hidden');
            const target = document.getElementById('qrcodeJsTarget');
            if (target) target.classList.add('hidden');
        }

        function copyScanUrl() {
            navigator.clipboard.writeText(currentAdminUrl).then(() => {
                const btnText = document.getElementById('copyBtnText');
                btnText.innerText = 'Tersalin!';
                setTimeout(() => { btnText.innerText = 'Salin Tautan'; }, 2000);
            }).catch(() => {
                alert('Tautan: ' + currentAdminUrl);
            });
        }

        function toggleIpModal() {
            const modal = document.getElementById('ipModal');
            modal.classList.toggle('opacity-0');
            modal.classList.toggle('pointer-events-none');
        }

        function applyCustomHost() {
            const hostVal = document.getElementById('customHostInput').value.trim() || '192.168.0.104';
            const portVal = document.getElementById('customPortInput').value.trim();
            const portStr = portVal && portVal !== '80' && portVal !== '443' ? `:${portVal}` : '';

            currentAdminUrl = `${scheme}://${hostVal}${portStr}/qr/pesanan/${orderCode}`;

            document.getElementById('currentScanHost').innerText = `${hostVal}${portStr}`;
            document.getElementById('fullUrlPreview').innerText = currentAdminUrl;

            renderQrCode(currentAdminUrl);
            toggleIpModal();
        }

        // Initialize on DOM Ready
        window.addEventListener('DOMContentLoaded', function () {
            renderQrCode(currentAdminUrl);
            switchSlipCodeMode(initialSlipCodeMode);
        });
    </script>
</body>
</html>
