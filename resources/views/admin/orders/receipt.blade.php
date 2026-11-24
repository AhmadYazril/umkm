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

    @php
        $isZendo = $order->isZendo();
        $deliveryAddress = $order->effective_delivery_address;
        $productTotal = $order->effective_subtotal;
        $deliveryFee = $order->effective_delivery_fee;
        $cafeTotal = $order->cafe_payable_amount;
        $totalItemQty = $order->total_item_qty;
        $isPaid = $order->payment_status === 'paid';
        $isCash = strtolower((string) $order->payment_method) === 'cash';
        $cashReceived = $order->cash_received_amount;
        $changeAmount = $order->change_amount;
    @endphp

    <div style="font-size: 10px;">
        <div>KODE: <span class="bold">{{ $order->code }}</span></div>
        <div>TGL : {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div>PLG : {{ $order->customer_name }} ({{ $order->customer_phone }})</div>
        <div>TIPE: <span class="bold">{{ strtoupper($order->service_type_label) }}</span> @if($order->table) [MEJA {{ $order->table->number }}]@endif</div>
        @if($deliveryAddress)
            <div>ALMT: {{ $deliveryAddress }}</div>
        @endif
        <div>BAYAR: {{ strtoupper($order->payment_method_label) }} ({{ strtoupper($order->payment_status_label) }})</div>
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
            <td>TOTAL PRODUK ({{ $totalItemQty }} ITEM):</td>
            <td class="text-right">Rp {{ number_format($productTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">{{ $isZendo ? 'TOTAL BAYAR KAFE:' : 'TOTAL AKHIR:' }}</td>
            <td class="text-right bold" style="font-size: 13px;">Rp {{ number_format($cafeTotal, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        @if($isPaid)
            <tr>
                <td>PEMBAYARAN ({{ strtoupper($order->payment_method_label) }}):</td>
                <td class="text-right bold">Rp {{ number_format($cashReceived, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>KEMBALIAN:</td>
                <td class="text-right bold">Rp {{ number_format($isCash ? $changeAmount : 0, 0, ',', '.') }}</td>
            </tr>
        @else
            <tr>
                <td>STATUS PEMBAYARAN:</td>
                <td class="text-right bold">BELUM BAYAR</td>
            </tr>
        @endif
    </table>

    @if($isZendo && $deliveryFee > 0)
        <div class="divider"></div>
        <div style="font-size: 9px; margin-bottom: 2px;" class="bold">INFO KURIR ZENDO (TERPISAH):</div>
        <table style="font-size: 10px;">
            <tr>
                <td>ONGKIR ZENDO:</td>
                <td class="text-right">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>EST. TOTAL PELANGGAN:</td>
                <td class="text-right">Rp {{ number_format($productTotal + $deliveryFee, 0, ',', '.') }}</td>
            </tr>
        </table>
        <div style="font-size: 8px; color: #333;">* Ongkir dibayarkan terpisah ke kurir Zendo.</div>
    @endif

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 10px; font-size: 9px;">
        <div>TERIMA KASIH ATAS KUNJUNGAN ANDA!</div>
        <div>"New, Unforgettable, Comfy, Musings"</div>
        <div>Follow IG: @nucomu.cafe</div>
    </div>

</body>
</html>
