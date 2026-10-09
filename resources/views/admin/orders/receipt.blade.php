<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Nota #{{ $order->code }} — Nucomu Cafe</title>
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
            margin-bottom: 10px;
            border-radius: 4px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">CETAK STRUK NOTA</button>

    <div class="text-center">
        <div class="bold" style="font-size: 16px; tracking: 1px;">NUCOMU CAFE</div>
        <div style="font-size: 9px;">Coffee & Dessert Tulungagung</div>
        <div style="font-size: 9px;">Jl. Panglima Sudirman No. 45, Kebonsari</div>
        <div style="font-size: 9px;">WA: {{ $settings['phone'] ?? '0812-3456-7890' }}</div>
    </div>

    <div class="divider"></div>

    <div style="font-size: 10px;">
        <div>KODE: <span class="bold">{{ $order->code }}</span></div>
        <div>TGL : {{ $order->created_at->format('d/m/Y H:i') }}</div>
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

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 10px; font-size: 9px;">
        <div>TERIMA KASIH ATAS KUNJUNGAN ANDA!</div>
        <div>"New, Unforgettable, Comfy, Musings"</div>
        <div>Follow IG: @nucomu.cafe</div>
    </div>

</body>
</html>
