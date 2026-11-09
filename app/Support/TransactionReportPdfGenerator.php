<?php

namespace App\Support;

use App\Models\Order;
use Illuminate\Support\Collection;

class TransactionReportPdfGenerator
{
    /**
     * Generate a valid, multi-page PDF 1.4 binary document for transaction recaps.
     *
     * @param  array<string, mixed>  $meta
     * @param  array<string, mixed>  $summary
     * @param  Collection<int, Order>  $orders
     * @param  array<int, array<string, mixed>>  $dailyBreakdown
     */
    public static function generate(array $meta, array $summary, Collection $orders, array $dailyBreakdown = []): string
    {
        $pageWidth = 595.28;
        $pageHeight = 841.89;
        $marginLeft = 36.0;
        $marginRight = 36.0;
        $contentWidth = $pageWidth - $marginLeft - $marginRight;

        $pages = [];
        $currentStream = [];
        $y = $pageHeight - 40.0;

        $startNewPage = function () use (&$pages, &$currentStream, &$y, $pageHeight): void {
            if (! empty($currentStream)) {
                $pages[] = implode("\n", $currentStream);
            }
            $currentStream = [];
            $y = $pageHeight - 40.0;
        };

        $ensureSpace = function (float $neededHeight) use (&$y, $startNewPage): void {
            if (($y - $neededHeight) < 52.0) {
                $startNewPage();
            }
        };

        // 1. Header Banner Box
        $currentStream[] = self::drawFilledRect($marginLeft, $y - 54.0, $contentWidth, 54.0, [0.11, 0.12, 0.11]);
        $currentStream[] = self::drawText($marginLeft + 14.0, $y - 22.0, 'F2', 14.0, strtoupper((string) ($meta['cafe_name'] ?? 'NUCOMU CAFE')).' — LAPORAN REKAP TRANSAKSI', [1.0, 1.0, 1.0]);
        $currentStream[] = self::drawText($marginLeft + 14.0, $y - 38.0, 'F1', 9.0, 'Periode: '.($meta['period_label'] ?? '-').'  |  Rentang: '.($meta['date_range_label'] ?? '-'), [0.85, 0.87, 0.85]);
        $currentStream[] = self::drawText($marginLeft + 14.0, $y - 49.0, 'F1', 8.0, 'Dicetak pada: '.($meta['printed_at'] ?? now()->format('d/m/Y H:i')).' WIB  |  Filter: '.($meta['filter_label'] ?? 'Semua Transaksi'), [0.72, 0.75, 0.73]);
        $y -= 66.0;

        // 2. Summary Metric Boxes (4 Columns)
        $boxGap = 8.0;
        $boxWidth = ($contentWidth - ($boxGap * 3)) / 4.0;
        $boxHeight = 46.0;

        $cards = [
            [
                'title' => 'TOTAL TRANSAKSI',
                'value' => number_format((int) ($summary['total_orders'] ?? 0), 0, ',', '.').' Pesanan',
                'sub' => 'Selesai: '.number_format((int) ($summary['completed_orders'] ?? 0), 0, ',', '.').' | Batal: '.number_format((int) ($summary['cancelled_orders'] ?? 0), 0, ',', '.'),
            ],
            [
                'title' => 'OMZET PENJUALAN (LUNAS/SELESAI)',
                'value' => 'Rp '.number_format((int) ($summary['paid_revenue'] ?? 0), 0, ',', '.'),
                'sub' => 'Dari '.number_format((int) ($summary['paid_or_completed_count'] ?? 0), 0, ',', '.').' transaksi valid',
            ],
            [
                'title' => 'TOTAL NILAI PESANAN (NON-BATAL)',
                'value' => 'Rp '.number_format((int) ($summary['gross_revenue'] ?? 0), 0, ',', '.'),
                'sub' => 'Menu: Rp '.number_format((int) ($summary['subtotal_menu'] ?? 0), 0, ',', '.'),
            ],
            [
                'title' => 'METODE & LAYANAN',
                'value' => 'Tunai: '.(int) ($summary['cash_count'] ?? 0).' | QRIS: '.(int) ($summary['qris_count'] ?? 0),
                'sub' => 'Cafe: '.(int) ($summary['dine_in_count'] ?? 0).' | Zendo: '.((int) ($summary['zendo_tracking_count'] ?? 0) + (int) ($summary['zendo_chat_count'] ?? 0)),
            ],
        ];

        foreach ($cards as $idx => $card) {
            $bx = $marginLeft + ($idx * ($boxWidth + $boxGap));
            $currentStream[] = self::drawFilledSpikeRect($bx, $y - $boxHeight, $boxWidth, $boxHeight, [0.97, 0.97, 0.96], [0.85, 0.86, 0.84]);
            $currentStream[] = self::drawText($bx + 8.0, $y - 13.0, 'F2', 6.8, $card['title'], [0.40, 0.44, 0.42]);
            $currentStream[] = self::drawText($bx + 8.0, $y - 27.0, 'F2', 10.0, $card['value'], [0.11, 0.12, 0.11]);
            $currentStream[] = self::drawText($bx + 8.0, $y - 39.0, 'F1', 7.0, $card['sub'], [0.45, 0.48, 0.46]);
        }
        $y -= ($boxHeight + 14.0);

        // 3. Daily Breakdown Section (for Weekly / Monthly reports when available)
        if (count($dailyBreakdown) > 1) {
            $ensureSpace(45.0);
            $currentStream[] = self::drawText($marginLeft, $y - 10.0, 'F2', 9.5, 'RINGKASAN REKAPITULASI PER TANGGAL', [0.11, 0.12, 0.11]);
            $y -= 16.0;

            $sumCols = [90.0, 90.0, 110.0, 110.0, $contentWidth - 400.0];
            $sumHeaders = ['Tanggal', 'Jml Pesanan', 'Selesai / Lunas', 'Omzet Lunas (Rp)', 'Total Non-Batal (Rp)'];

            $currentStream[] = self::drawFilledSpikeRect($marginLeft, $y - 18.0, $contentWidth, 18.0, [0.91, 0.90, 0.87], [0.80, 0.80, 0.78]);
            $cx = $marginLeft;
            foreach ($sumHeaders as $i => $hText) {
                $currentStream[] = self::drawText($cx + 6.0, $y - 12.0, 'F2', 7.5, $hText, [0.15, 0.16, 0.15]);
                $cx += $sumCols[$i];
            }
            $y -= 18.0;

            foreach ($dailyBreakdown as $rIdx => $dayRow) {
                $ensureSpace(16.0);
                $bg = ($rIdx % 2 === 1) ? [0.98, 0.98, 0.97] : [1.0, 1.0, 1.0];
                $currentStream[] = self::drawFilledSpikeRect($marginLeft, $y - 15.0, $contentWidth, 15.0, $bg, [0.90, 0.90, 0.88]);

                $vals = [
                    (string) ($dayRow['date_label'] ?? '-'),
                    number_format((int) ($dayRow['orders_count'] ?? 0), 0, ',', '.').' trx',
                    number_format((int) ($dayRow['paid_count'] ?? 0), 0, ',', '.').' trx',
                    'Rp '.number_format((int) ($dayRow['paid_revenue'] ?? 0), 0, ',', '.'),
                    'Rp '.number_format((int) ($dayRow['gross_revenue'] ?? 0), 0, ',', '.'),
                ];

                $cx = $marginLeft;
                foreach ($vals as $i => $val) {
                    $font = ($i >= 3) ? 'F2' : 'F1';
                    $currentStream[] = self::drawText($cx + 6.0, $y - 10.5, $font, 7.5, $val, [0.15, 0.16, 0.15]);
                    $cx += $sumCols[$i];
                }
                $y -= 15.0;
            }

            $y -= 14.0;
        }

        // 4. Detailed Transactions Table
        $ensureSpace(45.0);
        $currentStream[] = self::drawText($marginLeft, $y - 10.0, 'F2', 9.5, 'DAFTAR RINCIAN TRANSAKSI PESANAN ('.$orders->count().' DATA)', [0.11, 0.12, 0.11]);
        $y -= 16.0;

        $colWidths = [
            24.0,  // No
            82.0,  // Kode
            68.0,  // Waktu
            98.0,  // Pelanggan
            82.0,  // Jenis
            54.0,  // Bayar
            48.0,  // Status
            $contentWidth - 456.0, // Total
        ];
        $headers = ['No', 'Kode Pesanan', 'Waktu (WIB)', 'Pelanggan', 'Jenis Layanan', 'Bayar', 'Status', 'Total (Rp)'];

        $drawTableHeader = function () use (&$currentStream, &$y, $marginLeft, $contentWidth, $colWidths, $headers): void {
            $currentStream[] = self::drawFilledSpikeRect($marginLeft, $y - 19.0, $contentWidth, 19.0, [0.18, 0.42, 0.31], [0.18, 0.42, 0.31]);
            $cx = $marginLeft;
            foreach ($headers as $i => $hText) {
                $currentStream[] = self::drawText($cx + 4.0, $y - 12.5, 'F2', 7.5, $hText, [1.0, 1.0, 1.0]);
                $cx += $colWidths[$i];
            }
            $y -= 19.0;
        };

        $drawTableHeader();

        if ($orders->isEmpty()) {
            $currentStream[] = self::drawFilledSpikeRect($marginLeft, $y - 26.0, $contentWidth, 26.0, [0.99, 0.99, 0.98], [0.88, 0.88, 0.86]);
            $currentStream[] = self::drawText($marginLeft + 14.0, $y - 16.0, 'F1', 8.5, 'Tidak ada data transaksi pada periode dan filter yang dipilih.', [0.45, 0.48, 0.46]);
            $y -= 26.0;
        } else {
            foreach ($orders->values() as $idx => $order) {
                if (($y - 17.0) < 52.0) {
                    $startNewPage();
                    $drawTableHeader();
                }

                $rowHeight = 16.5;
                $bg = ($idx % 2 === 1) ? [0.97, 0.98, 0.97] : [1.0, 1.0, 1.0];
                $currentStream[] = self::drawFilledSpikeRect($marginLeft, $y - $rowHeight, $contentWidth, $rowHeight, $bg, [0.90, 0.91, 0.89]);

                $customerLabel = self::truncate((string) $order->customer_name, 18);
                $kindLabel = match ($order->kind) {
                    'zendo_tracking' => 'Antar Zendo',
                    'zendo_chat' => 'Chat Zendo',
                    default => $order->order_type === 'take_away' ? 'Cafe (Takeaway)' : 'Makan di Cafe',
                };
                $payLabel = strtoupper((string) ($order->payment_method ?: 'CASH')).($order->payment_status === 'paid' ? ' (Lunas)' : '');
                $statusLabel = $order->status_label;
                $totalLabel = 'Rp '.number_format((int) $order->total, 0, ',', '.');

                $cells = [
                    (string) ($idx + 1),
                    self::truncate((string) $order->code, 16),
                    $order->created_at ? $order->created_at->format('d/m/y H:i') : '-',
                    $customerLabel,
                    $kindLabel,
                    self::truncate($payLabel, 11),
                    $statusLabel,
                    $totalLabel,
                ];

                $cx = $marginLeft;
                foreach ($cells as $cIdx => $cellText) {
                    $font = ($cIdx === 1 || $cIdx === 7) ? 'F2' : 'F1';
                    $color = [0.15, 0.16, 0.15];
                    if ($cIdx === 6) {
                        $color = match ($order->status) {
                            'completed' => [0.10, 0.45, 0.28],
                            'cancelled' => [0.70, 0.18, 0.18],
                            default => [0.45, 0.35, 0.10],
                        };
                    }
                    $currentStream[] = self::drawText($cx + 4.0, $y - 11.2, $font, 7.2, $cellText, $color);
                    $cx += $colWidths[$cIdx];
                }

                $y -= $rowHeight;
            }

            // Table Grand Total Row
            $ensureSpace(24.0);
            $currentStream[] = self::drawFilledSpikeRect($marginLeft, $y - 20.0, $contentWidth, 20.0, [0.93, 0.95, 0.94], [0.18, 0.42, 0.31]);
            $currentStream[] = self::drawText($marginLeft + 8.0, $y - 13.0, 'F2', 8.0, 'TOTAL OMZET PENJUALAN (LUNAS / SELESAI):', [0.11, 0.12, 0.11]);
            $currentStream[] = self::drawText($marginLeft + 230.0, $y - 13.0, 'F2', 8.5, 'Rp '.number_format((int) ($summary['paid_revenue'] ?? 0), 0, ',', '.'), [0.10, 0.45, 0.28]);
            $currentStream[] = self::drawText($marginLeft + 335.0, $y - 13.0, 'F2', 8.0, 'TOTAL NON-BATAL: Rp '.number_format((int) ($summary['gross_revenue'] ?? 0), 0, ',', '.'), [0.11, 0.12, 0.11]);
            $y -= 20.0;
        }

        if (! empty($currentStream)) {
            $pages[] = implode("\n", $currentStream);
        }

        // Add Footer & Page Numbers to every page
        $totalPages = count($pages);
        foreach ($pages as $pIdx => $streamContent) {
            $pageNum = $pIdx + 1;
            $footer = [];
            $footer[] = self::drawLine($marginLeft, 36.0, $pageWidth - $marginRight, 36.0, [0.82, 0.83, 0.81]);
            $footer[] = self::drawText($marginLeft, 24.0, 'F1', 7.5, ($meta['cafe_name'] ?? 'Nucomu Cafe').' — Dokumen Rekapitulasi Transaksi Resmi', [0.50, 0.53, 0.51]);
            $footer[] = self::drawText($pageWidth - $marginRight - 75.0, 24.0, 'F2', 7.5, "Halaman {$pageNum} dari {$totalPages}", [0.40, 0.43, 0.41]);
            $pages[$pIdx] = $streamContent."\n".implode("\n", $footer);
        }

        return self::compilePdfDocument($pages, $pageWidth, $pageHeight);
    }

    /**
     * Compile raw PDF content streams into a valid PDF 1.4 binary string.
     *
     * @param  array<int, string>  $pageStreams
     */
    protected static function compilePdfDocument(array $pageStreams, float $width, float $height): string
    {
        $objects = [];

        // Object 1: Catalog
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';

        // Object 3: Font Regular (Helvetica)
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';

        // Object 4: Font Bold (Helvetica-Bold)
        $objects[4] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        $kids = [];
        $nextObjId = 5;

        foreach ($pageStreams as $stream) {
            $pageObjId = $nextObjId++;
            $contentObjId = $nextObjId++;
            $kids[] = "{$pageObjId} 0 R";

            $streamLen = strlen($stream);
            $objects[$contentObjId] = "<< /Length {$streamLen} >>\nstream\n{$stream}\nendstream";
            $objects[$pageObjId] = sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2f %.2f] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents %d 0 R >>',
                $width,
                $height,
                $contentObjId
            );
        }

        // Object 2: Pages Tree
        $kidsStr = implode(' ', $kids);
        $pageCount = count($kids);
        $objects[2] = "<< /Type /Pages /Kids [ {$kidsStr} ] /Count {$pageCount} >>";

        ksort($objects);

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];

        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$body}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $maxObj = max(array_keys($objects));
        $totalEntries = $maxObj + 1;

        $pdf .= "xref\n0 {$totalEntries}\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= $maxObj; $i++) {
            $offset = $offsets[$i] ?? 0;
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer\n<< /Size {$totalEntries} /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    /**
     * @param  array{0: float, 1: float, 2: float}  $rgb
     */
    protected static function drawFilledRect(float $x, float $y, float $w, float $h, array $rgb): string
    {
        return sprintf(
            'q %.3f %.3f %.3f rg %.2f %.2f %.2f %.2f re f Q',
            $rgb[0],
            $rgb[1],
            $rgb[2],
            $x,
            $y,
            $w,
            $h
        );
    }

    /**
     * @param  array{0: float, 1: float, 2: float}  $fillRgb
     * @param  array{0: float, 1: float, 2: float}  $strokeRgb
     */
    protected static function drawFilledSpikeRect(float $x, float $y, float $w, float $h, array $fillRgb, array $strokeRgb): string
    {
        return sprintf(
            'q %.3f %.3f %.3f rg %.3f %.3f %.3f RG 0.6 w %.2f %.2f %.2f %.2f re B Q',
            $fillRgb[0],
            $fillRgb[1],
            $fillRgb[2],
            $strokeRgb[0],
            $strokeRgb[1],
            $strokeRgb[2],
            $x,
            $y,
            $w,
            $h
        );
    }

    /**
     * @param  array{0: float, 1: float, 2: float}  $rgb
     */
    protected static function drawLine(float $x1, float $y1, float $x2, float $y2, array $rgb): string
    {
        return sprintf(
            'q %.3f %.3f %.3f RG 0.6 w %.2f %.2f m %.2f %.2f l S Q',
            $rgb[0],
            $rgb[1],
            $rgb[2],
            $x1,
            $y1,
            $x2,
            $y2
        );
    }

    /**
     * @param  array{0: float, 1: float, 2: float}  $rgb
     */
    protected static function drawText(float $x, float $y, string $font, float $size, string $text, array $rgb): string
    {
        $clean = self::escapePdfText($text);

        return sprintf(
            'BT /%s %.2f Tf %.3f %.3f %.3f rg %.2f %.2f Td (%s) Tj ET',
            $font,
            $size,
            $rgb[0],
            $rgb[1],
            $rgb[2],
            $x,
            $y,
            $clean
        );
    }

    protected static function escapePdfText(string $text): string
    {
        $text = str_replace(['—', '–', '•'], ['-', '-', '-'], $text);
        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = $converted;
        } else {
            $text = preg_replace('/[^\x20-\x7E]/', '', $text) ?? '';
        }

        return str_replace(['\\', '(', ')', "\r", "\n"], ['\\\\', '\\(', '\\)', ' ', ' '], $text);
    }

    protected static function truncate(string $text, int $maxLength): string
    {
        $text = trim($text);
        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        return mb_substr($text, 0, max(1, $maxLength - 2)).'..';
    }
}
