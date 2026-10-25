<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Pesanan #{{ $order->code }} — Nucomu Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- QR Code libraries (Primary + Fallbacks) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        obsidian: '#0c0c0f',
                        charcoal: '#15151c',
                        goldAccent: '#f59e0b',
                        violetAccent: '#8b5cf6',
                        cyanAccent: '#06b6d4',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0c0c0f; }
        ::-webkit-scrollbar-thumb { background: #232330; border-radius: 9999px; }
        .glass-card {
            background: rgba(21, 21, 28, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .print-card { background: white !important; border: 2px solid #e5e7eb !important; color: black !important; }
            .print-gold { color: #b45309 !important; }
        }
    </style>
</head>
<body class="bg-obsidian text-gray-100 font-sans min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">

    {{-- Ambient Glow --}}
    <div class="fixed top-10 left-1/3 w-96 h-96 rounded-full bg-goldAccent/10 pointer-events-none z-0 blur-3xl"></div>
    <div class="fixed bottom-0 right-0 w-80 h-80 rounded-full bg-violetAccent/10 pointer-events-none z-0 blur-3xl"></div>

    <div class="relative z-10 w-full max-w-md mx-auto space-y-4">

        {{-- SUCCESS BADGE --}}
        <div class="text-center space-y-2 no-print">
            <div class="w-14 h-14 rounded-full bg-emerald-500/20 border-2 border-emerald-500 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/10">
                <i class="fa-solid fa-check text-emerald-400 text-xl"></i>
            </div>
            <h1 class="text-xl font-black text-white">Pesanan Dibuat!</h1>
            <p class="text-gray-400 text-xs sm:text-sm">Tunjukkan QR code di bawah ini ke kasir atau scan via Google Lens di HP.</p>
        </div>

        {{-- SLIP CARD (Printable) --}}
        <div class="glass-card rounded-3xl overflow-hidden print-card shadow-2xl border border-white/15">

            {{-- Header Slip --}}
            <div class="bg-gradient-to-r from-goldAccent/20 to-amber-600/10 border-b border-goldAccent/20 px-6 py-5 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <i class="fa-solid fa-mug-hot text-goldAccent"></i>
                    <span class="font-black text-lg text-white tracking-wider">NUCOMU CAFE</span>
                </div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Coffee & Stories · Slip Pesanan</p>
            </div>

            {{-- Customer Info --}}
            <div class="px-6 py-4 border-b border-white/5">
                <div class="flex items-center justify-between text-sm">
                    <div>
                        <p class="text-gray-400 text-xs mb-0.5">Nama Pemesan</p>
                        <p class="font-black text-white text-lg">{{ $order->customer_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-400 text-xs mb-0.5">Kode Pesanan</p>
                        <p class="font-mono font-black text-goldAccent print-gold text-base">{{ $order->code }}</p>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-3 text-xs text-gray-400">
                    <span><i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('H:i') }} WIB</span>
                    <span>·</span>
                    <span><i class="fa-regular fa-calendar mr-1"></i>{{ $order->created_at->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            {{-- QR CODE SECTION --}}
            <div class="px-6 py-6 flex flex-col items-center border-b border-white/5 bg-black/20">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] text-goldAccent font-semibold mb-3">
                    <i class="fa-solid fa-camera"></i> Siap Scan Google Lens / HP Kasir
                </div>

                {{-- White QR Plate Container --}}
                <div class="bg-white p-4 rounded-3xl shadow-2xl flex items-center justify-center min-w-[210px] min-h-[210px]" id="qrContainer">
                    <canvas id="qrCanvas" class="hidden"></canvas>
                    <div id="qrcodeJsTarget"></div>
                    <img id="qrFallbackImg" class="hidden w-[200px] h-[200px] object-contain rounded" alt="QR Code">
                </div>

                {{-- Host IP & Direct URL Info for User & Kasir --}}
                <div class="mt-4 w-full bg-black/50 border border-white/10 rounded-2xl p-3 text-center space-y-1.5">
                    <div class="flex items-center justify-center gap-1.5 text-[11px] text-gray-300">
                        <i class="fa-solid fa-wifi text-emerald-400 text-[10px]"></i>
                        <span class="font-semibold text-white">Target Akses IP:</span>
                        <span class="font-mono text-goldAccent font-bold" id="currentScanHost">{{ $host }}{{ $portSuffix }}</span>
                    </div>

                    <div class="flex items-center justify-center gap-2 pt-1">
                        <button type="button" onclick="copyScanUrl()" class="px-3 py-1 bg-white/10 hover:bg-white/20 text-gray-200 rounded-lg text-[10px] font-bold transition-all flex items-center gap-1.5">
                            <i class="fa-regular fa-copy"></i>
                            <span id="copyBtnText">Salin Tautan</span>
                        </button>
                        <button type="button" onclick="toggleIpModal()" class="px-3 py-1 bg-goldAccent/15 hover:bg-goldAccent/25 text-goldAccent border border-goldAccent/30 rounded-lg text-[10px] font-bold transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-gear"></i>
                            <span>Ganti Host IP</span>
                        </button>
                    </div>

                    <p class="text-[9px] text-gray-500 font-mono pt-1" id="fullUrlPreview">{{ $adminOrderUrl }}</p>
                </div>

                <p class="text-[10px] text-gray-500 mt-2.5 text-center">
                    Tunjukkan barcode QR ini ke kasir atau buka tautan di atas via HP.
                </p>
            </div>

            {{-- ORDER ITEMS --}}
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-3">Rincian Pesanan</h3>
                <div class="space-y-2.5">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex-1">
                                <p class="font-semibold text-white">{{ $item->menu_name }}</p>
                                <p class="text-xs text-gray-400 font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }} × {{ $item->qty }}</p>
                            </div>
                            <span class="font-mono font-bold text-white ml-4">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- DELIVERY ADDRESS & NOTES --}}
            @if(!empty($deliveryAddress))
                <div class="px-6 py-3.5 border-b border-white/5 bg-emerald-500/5 space-y-1">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest">
                            <i class="fa-solid fa-location-dot mr-1"></i> Alamat Pengiriman (Kurir Zendo)
                        </p>
                        @if(!empty($distanceKm))
                            <span class="text-[10px] font-mono font-bold text-emerald-300 bg-emerald-500/15 px-2 py-0.5 rounded-full">
                                {{ number_format((float) $distanceKm, 1, ',', '.') }} km dari Kafe
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-200">{{ $deliveryAddress }}</p>
                    @if(!empty($customerLat) && !empty($customerLng))
                        <a href="https://www.google.com/maps?q={{ $customerLat }},{{ $customerLng }}" target="_blank"
                           class="inline-flex items-center gap-1 text-[10px] text-emerald-400 hover:underline font-semibold pt-0.5">
                            <i class="fa-solid fa-map-pin"></i> Buka Titik Peta Pengiriman ↗
                        </a>
                    @endif
                </div>
            @endif

            @if(!empty($cleanNotes ?? $order->notes))
                <div class="px-6 py-3 border-b border-white/5 bg-white/2">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Catatan</p>
                    <p class="text-xs text-gray-300 italic">{{ $cleanNotes ?? $order->notes }}</p>
                </div>
            @endif

            {{-- TOTAL & ONGKIR BREAKDOWN --}}
            <div class="px-6 py-5 bg-goldAccent/5 space-y-2">
                @if(!empty($isZendoOrder) && !empty($deliveryFee) && $deliveryFee > 0)
                    <div class="flex items-center justify-between text-xs text-gray-300">
                        <span>Subtotal Menu</span>
                        <span class="font-mono font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-emerald-400 pb-2 border-b border-white/10">
                        <span>Ongkir Kurir Zendo @if(!empty($distanceKm))({{ number_format((float) $distanceKm, 1, ',', '.') }} km)@endif</span>
                        <span class="font-mono font-bold">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Total Pembayaran</p>
                        <p class="text-[10px] text-gray-500">
                            {{ !empty($isZendoOrder) ? '*Terkirim otomatis ke kontak Kurir Zendo' : '*Bayar tunai di kasir' }}
                        </p>
                    </div>
                    <span class="font-mono font-black text-2xl text-goldAccent print-gold">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Footer Slip --}}
            <div class="px-6 py-4 text-center bg-white/2">
                <p class="text-[10px] text-gray-500">Terima kasih atas kunjungan Anda 🙏</p>
                <p class="text-[10px] text-gray-500">Nucomu Cafe · Open 08:00 - 23:00</p>
            </div>
        </div>

        {{-- ZENDO WHATSAPP BUTTON (IF ZENDO ENABLED OR ZENDO ORDER) --}}
        @if(!empty($zendoWhatsappUrl) && (!empty($isZendoOrder) || ($transactionMode ?? 'both') !== 'kasir'))
            <div class="no-print">
                <a href="{{ $zendoWhatsappUrl }}" target="_blank"
                   class="w-full py-3.5 rounded-2xl bg-emerald-500/15 border border-emerald-500/40 text-emerald-300 font-bold text-xs uppercase tracking-wider hover:bg-emerald-500/25 transition-all flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-base"></i>
                    <span>{{ !empty($isZendoOrder) ? 'Hubungi Kontak Kurir Zendo (+' . ($zendoWaClean ?? '') . ')' : 'Pesan Delivery via Kurir Zendo' }}</span>
                </a>
            </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-col sm:flex-row gap-3 no-print">
            <button onclick="window.print()"
                class="flex-1 py-3.5 rounded-2xl bg-white/10 border border-white/20 text-white font-bold text-sm hover:bg-white/15 transition-all flex items-center justify-center gap-2 shadow-sm">
                <i class="fa-solid fa-print"></i> Cetak / Screenshot
            </button>
            <a href="{{ route('nucomu.order') }}"
               class="flex-1 py-3.5 rounded-2xl glass-card border border-white/10 text-gray-300 font-bold text-sm hover:text-white transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Menu
            </a>
        </div>

        {{-- Status pesanan --}}
        <div class="glass-card rounded-2xl p-4 border border-white/10 no-print">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full {{ !empty($isZendoOrder) ? 'bg-emerald-500/20' : 'bg-amber-500/20' }} flex items-center justify-center shrink-0">
                        <i class="{{ !empty($isZendoOrder) ? 'fa-solid fa-check-double text-emerald-400' : 'fa-solid fa-clock text-goldAccent' }} text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white">
                            Status:
                            <span class="{{ !empty($isZendoOrder) ? 'text-emerald-400' : 'text-goldAccent' }}">
                                {{ !empty($isZendoOrder) ? 'Terkirim Otomatis ke Kontak Zendo' : 'Menunggu Kasir' }}
                            </span>
                        </p>
                        <p class="text-[10px] text-gray-400">
                            {{ !empty($isZendoOrder) ? 'Pesanan & titik alamat Anda telah dikirim ke kontak Kurir Zendo.' : 'Tunjukkan slip ini ke kasir untuk membayar.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL GANTI HOST IP CONFIG --}}
    <div id="ipModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="glass-card bg-[#15151c] w-full max-w-sm rounded-3xl p-6 border border-white/15 shadow-2xl relative">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-network-wired text-goldAccent"></i>
                    <span>Pengaturan Host IP QR</span>
                </h3>
                <button onclick="toggleIpModal()" class="text-gray-400 hover:text-white text-sm"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <p class="text-[11px] text-gray-400 mb-3 leading-relaxed">
                Sesuaikan alamat IP dari perintah <code class="text-goldAccent font-mono">ipconfig</code> komputer kasir agar HP di jaringan Wi-Fi lokal dapat langsung mengakses data pesanan.
            </p>

            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Host IP / Domain</label>
                    <input type="text" id="customHostInput" value="{{ $host }}" placeholder="Contoh: 192.168.0.104"
                           class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-goldAccent">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-300 uppercase mb-1">Port (Kosongkan jika port 80)</label>
                    <input type="text" id="customPortInput" value="{{ ltrim($portSuffix, ':') }}" placeholder="8000"
                           class="w-full bg-black/50 border border-white/10 rounded-xl px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-goldAccent">
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="button" onclick="applyCustomHost()" class="flex-1 py-2.5 rounded-xl bg-goldAccent text-black font-extrabold text-xs uppercase tracking-wider hover:bg-amber-400 transition-all">
                        Terapkan ke QR
                    </button>
                    <button type="button" onclick="toggleIpModal()" class="py-2.5 px-4 rounded-xl bg-white/5 border border-white/10 text-gray-400 text-xs font-bold">
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
        });
    </script>
</body>
</html>
