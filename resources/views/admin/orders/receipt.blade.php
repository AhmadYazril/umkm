<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Nota #{{ $order->code }} — Nucomu Cafe</title>
    @if($order->uses_barcode)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script src="{{ asset('js/nucomu-barcode.js') }}"></script>
    @endif
    <style>
        @page { size: 80mm auto; margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 76mm;
            margin: 0 auto;
            padding: 5mm;
            font-size: 11px;
            color: #000;
            background: #fff;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-bottom: 1px dashed #000; margin: 6px 0; }
        .double-divider { border-bottom: 2px double #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; margin: 4px 0; }
        td, th { padding: 2px 0; vertical-align: top; }
        .btn-print {
            display: block;
            width: 100%;
            padding: 8px;
            background: #000;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .mode-switcher {
            display: flex;
            gap: 4px;
            margin-bottom: 10px;
            font-family: sans-serif;
        }
        .mode-btn {
            flex: 1;
            padding: 6px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid #111;
            background: #fff;
            color: #111;
            border-radius: 4px;
            cursor: pointer;
        }
        .mode-btn.active {
            background: #2F6B4F;
            border-color: #2F6B4F;
            color: #fff;
        }
        .partner-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 6px 8px;
            border: 1px solid #000;
            border-radius: 6px;
            margin: 6px 0;
        }
        .partner-box img {
            width: 26px;
            height: 26px;
            object-fit: contain;
        }
        .code-plate {
            text-align: center;
            margin: 8px 0 4px;
        }
        #receiptQrTarget {
            display: inline-block;
        }
        .hidden { display: none !important; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">CETAK STRUK NOTA</button>

    @if($order->uses_barcode)
        <div class="mode-switcher no-print">
            <button type="button" id="receiptBtn-barcode" onclick="switchReceiptCode('barcode')" class="mode-btn">Barcode Garis</button>
            <button type="button" id="receiptBtn-qr" onclick="switchReceiptCode('qr')" class="mode-btn">QR Code</button>
        </div>
    @endif

    @php
        $receiptBrand = $brand ?? \App\Http\Controllers\NucomuController::resolveBrandSettings();
        $receiptContact = $contact ?? \App\Http\Controllers\NucomuController::getContactSettings();
        $receiptCafeName = $receiptBrand['name'] ?? config('nucomu.brand.name', 'Nucomu Cafe');
        $receiptTagline = $receiptBrand['tagline'] ?? ($settings['cafe_tagline'] ?? 'Coffee & Dessert Tulungagung');
        $receiptAddress = $receiptContact['address'] ?? config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Tulungagung');
        $receiptWa = $receiptContact['whatsapp_display'] ?? ($settings['cafe_whatsapp'] ?? ($settings['phone'] ?? '0812-3456-7890'));
        $rawIg = $receiptContact['instagram'] ?? ($settings['cafe_instagram'] ?? config('nucomu.contact.instagram_handle', '@nucomu.cafe'));
        $receiptIgHandle = '@nucomu.cafe';
        if (! empty($rawIg)) {
            if (str_starts_with($rawIg, 'http')) {
                $pathParts = array_values(array_filter(explode('/', (string) parse_url($rawIg, PHP_URL_PATH))));
                $receiptIgHandle = ! empty($pathParts) ? '@'.ltrim($pathParts[0], '@') : '@nucomu.cafe';
            } else {
                $receiptIgHandle = '@'.ltrim($rawIg, '@');
            }
        }
    @endphp

    <div class="text-center">
        <div class="bold" style="font-size: 16px; letter-spacing: 1px;">{{ strtoupper($receiptCafeName) }}</div>
        <div style="font-size: 9px;">{{ $receiptTagline }}</div>
        <div style="font-size: 9px;">{{ $receiptAddress }}</div>
        <div style="font-size: 9px;">WA: {{ $receiptWa }}</div>
    </div>

    @if(! $order->uses_barcode && $order->channel_logo)
        <div class="partner-box">
            <img src="{{ $order->channel_logo }}" alt="{{ $order->channel_label }}">
            <div style="text-align: left;">
                <div class="bold" style="font-size: 11px;">TRANSAKSI {{ strtoupper($order->channel_label) }}</div>
                <div style="font-size: 9px;">Input Manual Kasir (Tanpa Barcode)</div>
            </div>
        </div>
    @endif

    <div class="divider"></div>

    <div style="font-size: 10px;">
        <div>KODE: <span class="bold">{{ $order->code }}</span></div>
        <div>TGL : {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div>SUMB: <span class="bold">{{ strtoupper($order->channel_label) }}</span></div>
        <div>PLG : {{ $order->customer_name }} ({{ $order->customer_phone }})</div>
        <div>TIPE: <span class="bold">{{ strtoupper(str_replace('_', ' ', $order->order_type)) }}</span> @if($order->table) [MEJA {{ $order->table->number }}]@endif</div>
        <div>BAYAR: {{ strtoupper($order->payment_method) }} ({{ strtoupper($order->payment_status) }})</div>
    </div>

    <div class="double-divider"></div>

    <table>
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th style="text-align: left;">ITEM</th>
                <th style="text-align: center;">QTY</th>
                <th style="text-align: right;">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td style="text-align: left;">
                        <span class="bold">{{ $item->menu_name }}</span>
                        @if($item->options && is_array($item->options))
                            <div style="font-size: 8px; color: #333;">
                                @foreach($item->options as $opt)
                                    + {{ is_array($opt) ? $opt['name'] : $opt }}
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->qty }}</td>
                    <td style="text-align: right;">{{ number_format($item->line_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="double-divider"></div>

    <table>
        <tr>
            <td class="bold">TOTAL AKHIR:</td>
            <td class="text-right bold" style="font-size: 13px;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($order->uses_barcode)
        <div class="divider"></div>
        <div class="code-plate">
            <div id="receiptBarcodeWrap">
                <div id="receiptBarcodeTarget"></div>
            </div>
            <div id="receiptQrWrap" class="hidden">
                <div id="receiptQrTarget"></div>
                <div class="bold" style="font-size: 10px; margin-top: 4px;">{{ $order->code }}</div>
            </div>
        </div>
    @endif

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 10px; font-size: 9px;">
        <div>TERIMA KASIH ATAS KUNJUNGAN ANDA!</div>
        <div>"New, Unforgettable, Comfy, Musings"</div>
        <div>Follow IG: {{ $receiptIgHandle }}</div>
    </div>

    @if($order->uses_barcode)
        <script>
            const RECEIPT_ORDER_CODE = @json($order->code);
            const RECEIPT_INITIAL_MODE = @json($order->effective_barcode_type ?? 'qr');
            const RECEIPT_QR_URL = @json(route('admin.orders.qr', ['code' => $order->code]));

            function switchReceiptCode(mode) {
                const normalized = mode === 'qr' ? 'qr' : 'barcode';
                const barWrap = document.getElementById('receiptBarcodeWrap');
                const qrWrap = document.getElementById('receiptQrWrap');
                const barBtn = document.getElementById('receiptBtn-barcode');
                const qrBtn = document.getElementById('receiptBtn-qr');

                if (barWrap) barWrap.classList.toggle('hidden', normalized !== 'barcode');
                if (qrWrap) qrWrap.classList.toggle('hidden', normalized !== 'qr');
                if (barBtn) barBtn.classList.toggle('active', normalized === 'barcode');
                if (qrBtn) qrBtn.classList.toggle('active', normalized === 'qr');

                if (normalized === 'barcode' && window.NucomuBarcode) {
                    window.NucomuBarcode.renderCode128Svg('#receiptBarcodeTarget', RECEIPT_ORDER_CODE, {
                        barHeight: 48,
                        moduleWidth: 1.5,
                        fontSize: 11,
                        maxWidth: '240px',
                    });
                } else if (normalized === 'qr') {
                    const target = document.getElementById('receiptQrTarget');
                    if (target && target.innerHTML.trim() === '' && typeof QRCode === 'function') {
                        new QRCode(target, {
                            text: RECEIPT_QR_URL,
                            width: 116,
                            height: 116,
                            colorDark: '#000000',
                            colorLight: '#ffffff',
                        });
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                switchReceiptCode(RECEIPT_INITIAL_MODE);
            });
        </script>
    @endif

</body>
</html>
