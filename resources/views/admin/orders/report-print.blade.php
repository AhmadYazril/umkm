<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Transaksi {{ $meta['period_label'] ?? '' }} — {{ $meta['cafe_name'] ?? 'Nucomu Cafe' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1B1F1D;
            background: #F6F7F5;
            margin: 0;
            padding: 24px;
            font-size: 12px;
            line-height: 1.45;
        }
        .sheet {
            max-width: 210mm;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 24px 28px;
            border-radius: 14px;
            border: 1px solid #E6E8E4;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .toolbar {
            max-width: 210mm;
            margin: 0 auto 16px auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: #FFFFFF;
            padding: 12px 18px;
            border-radius: 12px;
            border: 1px solid #E6E8E4;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .btn-primary {
            background: #2F6B4F;
            color: #FFFFFF;
        }
        .btn-dark {
            background: #1B1F1D;
            color: #FFFFFF;
        }
        .btn-outline {
            background: #FFFFFF;
            color: #1B1F1D;
            border-color: #D5D8D3;
        }
        .report-header {
            background: #1B1F1D;
            color: #FFFFFF;
            padding: 18px 20px;
            border-radius: 10px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }
        .report-title {
            font-size: 17px;
            font-weight: 800;
            margin: 0 0 4px 0;
            letter-spacing: -0.01em;
        }
        .report-sub {
            font-size: 11px;
            color: #D1D6D2;
            margin: 2px 0;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }
        .metric-card {
            background: #FAFBF9;
            border: 1px solid #E6E8E4;
            border-radius: 10px;
            padding: 10px 12px;
        }
        .metric-label {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6E7772;
            letter-spacing: 0.04em;
            display: block;
            margin-bottom: 4px;
        }
        .metric-val {
            font-size: 14px;
            font-weight: 800;
            color: #1B1F1D;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .metric-note {
            font-size: 10px;
            color: #555D58;
            margin-top: 3px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 16px 0 8px 0;
            color: #1B1F1D;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 14px;
        }
        th {
            background: #2F6B4F;
            color: #FFFFFF;
            text-align: left;
            padding: 8px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border: 1px solid #2F6B4F;
        }
        td {
            padding: 7px 8px;
            border: 1px solid #E6E8E4;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background: #FAFBF9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .bold {
            font-weight: 700;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 99px;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-completed { background: #E6F4EA; color: #137333; }
        .badge-cancelled { background: #FCE8E6; color: #C5221F; }
        .badge-pending { background: #FEF7E0; color: #B06000; }
        .footer-note {
            margin-top: 18px;
            padding-top: 10px;
            border-top: 1px solid #E6E8E4;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #7A847E;
        }
        @media print {
            body {
                background: #FFFFFF;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .sheet {
                max-width: 100%;
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="toolbar no-print">
        <div>
            <strong>Pratinjau Cetak &amp; PDF Rekap Transaksi</strong>
            <span style="color: #6E7772; margin-left: 6px;">({{ $meta['period_label'] ?? '' }} &bull; {{ $meta['date_range_label'] ?? '' }})</span>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.orders.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn btn-primary">
                &#128196; Download File PDF (.pdf)
            </a>
            <a href="{{ route('admin.orders.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-outline">
                &#128202; Download CSV / Excel
            </a>
            <button type="button" onclick="window.print()" class="btn btn-dark">
                &#128424; Cetak / Simpan PDF
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">
                &larr; Kembali ke Transaksi
            </a>
        </div>
    </div>

    <div class="sheet">
        <div class="report-header">
            <div>
                <h1 class="report-title">{{ strtoupper($meta['cafe_name'] ?? 'NUCOMU CAFE') }} &mdash; LAPORAN REKAP TRANSAKSI</h1>
                <div class="report-sub">Periode Rekap: <strong>{{ $meta['period_label'] ?? '-' }}</strong> ({{ $meta['date_range_label'] ?? '-' }})</div>
                <div class="report-sub">Filter: {{ $meta['filter_label'] ?? 'Semua Transaksi' }}</div>
            </div>
            <div class="text-right">
                <div class="report-sub">Dicetak pada:</div>
                <div class="bold" style="font-size: 11.5px; color: #fff;">{{ $meta['printed_at'] ?? now()->format('d/m/Y H:i') }} WIB</div>
            </div>
        </div>

        <div class="metrics-grid">
            <div class="metric-card">
                <span class="metric-label">Total Transaksi</span>
                <div class="metric-val">{{ number_format($summary['total_orders'] ?? 0, 0, ',', '.') }} Pesanan</div>
                <div class="metric-note">Selesai: {{ $summary['completed_orders'] ?? 0 }} | Batal: {{ $summary['cancelled_orders'] ?? 0 }}</div>
            </div>
            <div class="metric-card">
                <span class="metric-label">Omzet Penjualan (Selesai/Lunas)</span>
                <div class="metric-val" style="color: #137333;">Rp {{ number_format($summary['paid_revenue'] ?? 0, 0, ',', '.') }}</div>
                <div class="metric-note">{{ $summary['paid_or_completed_count'] ?? 0 }} transaksi terverifikasi</div>
            </div>
            <div class="metric-card">
                <span class="metric-label">Total Nilai Non-Batal</span>
                <div class="metric-val">Rp {{ number_format($summary['gross_revenue'] ?? 0, 0, ',', '.') }}</div>
                <div class="metric-note">Menu: Rp {{ number_format($summary['subtotal_menu'] ?? 0, 0, ',', '.') }} | Ongkir: Rp {{ number_format($summary['delivery_fee_total'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="metric-card">
                <span class="metric-label">Metode &amp; Layanan</span>
                <div class="metric-val" style="font-size: 12px;">Tunai: {{ $summary['cash_count'] ?? 0 }} | QRIS: {{ $summary['qris_count'] ?? 0 }}</div>
                <div class="metric-note">Di Cafe: {{ $summary['dine_in_count'] ?? 0 }} | Zendo: {{ ($summary['zendo_tracking_count'] ?? 0) + ($summary['zendo_chat_count'] ?? 0) }}</div>
            </div>
        </div>

        @if(count($dailyBreakdown) > 1)
            <div class="section-title">Ringkasan Rekapitulasi Per Tanggal</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th class="text-center">Jumlah Pesanan</th>
                        <th class="text-center">Selesai / Lunas</th>
                        <th class="text-right">Omzet Selesai/Lunas (Rp)</th>
                        <th class="text-right">Total Non-Batal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyBreakdown as $day)
                        <tr>
                            <td class="bold">{{ $day['date_label'] }}</td>
                            <td class="text-center mono">{{ $day['orders_count'] }} transaksi</td>
                            <td class="text-center mono">{{ $day['paid_count'] }} transaksi</td>
                            <td class="text-right mono bold">Rp {{ number_format($day['paid_revenue'], 0, ',', '.') }}</td>
                            <td class="text-right mono">Rp {{ number_format($day['gross_revenue'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="section-title">Daftar Rincian Transaksi ({{ $orders->count() }} Data)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 32px;">No</th>
                    <th>Kode Pesanan</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Pelanggan</th>
                    <th>Jenis Layanan</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th class="text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders->values() as $idx => $order)
                    <tr>
                        <td class="text-center mono">{{ $idx + 1 }}</td>
                        <td class="mono bold">{{ $order->code }}</td>
                        <td class="mono">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }} WIB</td>
                        <td>
                            <div class="bold">{{ $order->customer_name }}</div>
                            @if($order->customer_phone && $order->customer_phone !== '-')
                                <div style="font-size: 10px; color: #6E7772;">{{ $order->customer_phone }}</div>
                            @endif
                        </td>
                        <td>{{ $order->kind_label }}</td>
                        <td>
                            <span class="bold">{{ $order->payment_method_label }}</span>
                            <span style="font-size: 10px; color: {{ $order->payment_status === 'paid' ? '#137333' : '#B06000' }};">
                                ({{ $order->payment_status_label }})
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $order->status === 'completed' ? 'badge-completed' : ($order->status === 'cancelled' ? 'badge-cancelled' : 'badge-pending') }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="text-right mono bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 20px; color: #6E7772;">
                            Tidak ada data transaksi pada periode dan filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($orders->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right bold" style="background: #F1F5F2;">TOTAL OMZET PENJUALAN (LUNAS / SELESAI):</td>
                        <td class="text-right mono bold" style="background: #F1F5F2; color: #137333; font-size: 12px;">
                            Rp {{ number_format($summary['paid_revenue'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right bold" style="background: #FAFBF9;">TOTAL NILAI SELURUH PESANAN NON-BATAL:</td>
                        <td class="text-right mono bold" style="background: #FAFBF9;">
                            Rp {{ number_format($summary['gross_revenue'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <div class="footer-note">
            <span>{{ $meta['cafe_name'] ?? 'Nucomu Cafe' }} &mdash; Laporan Rekapitulasi Transaksi Resmi</span>
            <span>Periode: {{ $meta['period_label'] ?? '-' }} ({{ $meta['date_range_label'] ?? '-' }})</span>
        </div>
    </div>

</body>
</html>
