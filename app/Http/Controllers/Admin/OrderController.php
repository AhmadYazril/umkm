<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KasirController;
use App\Models\Order;
use App\Models\Setting;
use App\Support\TransactionReportPdfGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public const DEFAULT_ZENDO_TEMPLATE = "Halo Nucomu Cafe & Kurir Zendo, saya ingin memesan:\n\n"
        ."• No. Pesanan: #{kode_pesanan}\n"
        ."• Nama: {nama}\n"
        ."• WhatsApp: {telepon}\n"
        ."• Alamat Pengantaran: {alamat}\n\n"
        ."Daftar Menu:\n{daftar_pesanan}\n\n"
        ."• Ongkir (perkiraan, final dikonfirmasi kurir): {ongkir}\n"
        ."• Total Pembayaran: {total}\n"
        ."• Catatan: {catatan}\n\n"
        .'Mohon konfirmasi pesanan dan proses pengantaran. Terima kasih!';

    public function index(Request $request)
    {
        $query = Order::with(['table', 'items', 'zendoDelivery']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kind')) {
            $kind = $request->kind;
            if ($kind === 'dine_in') {
                $query->where(function ($q) {
                    $q->whereNull('delivery_address')
                        ->where(function ($n) {
                            $n->whereNull('notes')
                                ->orWhere('notes', 'not like', '%[KURIR ZENDO]%');
                        });
                });
            } elseif ($kind === 'zendo_tracking') {
                $query->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('notes', 'like', '%[KURIR ZENDO]%')
                            ->orWhereNotNull('delivery_address');
                    })->where(function ($sub2) {
                        $sub2->where('notes', 'like', '%Pin:%')
                            ->orWhere('notes', 'like', '%Tahap_Zendo%')
                            ->orWhere('form_data', 'like', '%customer_lat%')
                            ->orWhereHas('zendoDelivery');
                    });
                });
            } elseif ($kind === 'zendo_chat') {
                $query->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('notes', 'like', '%[KURIR ZENDO]%')
                            ->orWhereNotNull('delivery_address');
                    })->where('notes', 'not like', '%Pin:%')
                        ->where('notes', 'not like', '%Tahap_Zendo%')
                        ->where(function ($sub3) {
                            $sub3->whereNull('form_data')
                                ->orWhere('form_data', 'not like', '%customer_lat%');
                        })
                        ->whereDoesntHave('zendoDelivery');
                });
            }
        }
        if ($request->filled('type')) {
            $query->where('order_type', $request->type);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%'.$search.'%')
                    ->orWhere('customer_name', 'like', '%'.$search.'%')
                    ->orWhere('customer_phone', 'like', '%'.$search.'%');
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Transaction Summary Metrics & Status Counts
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $readyOrders = Order::where('status', 'ready')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $totalRevenue = (int) Order::where('status', 'completed')->sum('total');

        // Kind Counts
        $totalDineIn = Order::where(function ($q) {
            $q->whereNull('delivery_address')
                ->where(function ($n) {
                    $n->whereNull('notes')
                        ->orWhere('notes', 'not like', '%[KURIR ZENDO]%');
                });
        })->count();

        $totalZendoTracking = Order::where(function ($q) {
            $q->where(function ($sub) {
                $sub->where('notes', 'like', '%[KURIR ZENDO]%')
                    ->orWhereNotNull('delivery_address');
            })->where(function ($sub2) {
                $sub2->where('notes', 'like', '%Pin:%')
                    ->orWhere('notes', 'like', '%Tahap_Zendo%')
                    ->orWhere('form_data', 'like', '%customer_lat%')
                    ->orWhereHas('zendoDelivery');
            });
        })->count();

        $totalZendoChat = Order::where(function ($q) {
            $q->where(function ($sub) {
                $sub->where('notes', 'like', '%[KURIR ZENDO]%')
                    ->orWhereNotNull('delivery_address');
            })->where('notes', 'not like', '%Pin:%')
                ->where('notes', 'not like', '%Tahap_Zendo%')
                ->where(function ($sub3) {
                    $sub3->whereNull('form_data')
                        ->orWhere('form_data', 'not like', '%customer_lat%');
                })
                ->whereDoesntHave('zendoDelivery');
        })->count();

        // Mode Toggles & Settings with Backward-Compatible Fallback
        $dbSettings = Setting::pluck('value', 'key');
        $legacyMode = $dbSettings['transaction_mode'] ?? 'both';
        $legacyHideTracking = in_array((string) ($dbSettings['hide_live_tracking'] ?? '0'), ['1', 'true', 'yes'], true);

        // Mode 1: Makan di Cafe (Kasir)
        if (isset($dbSettings['order_mode_dine_in'])) {
            $orderModeDineIn = in_array((string) $dbSettings['order_mode_dine_in'], ['1', 'true', 'yes'], true);
        } else {
            $orderModeDineIn = in_array($legacyMode, ['kasir', 'both'], true);
        }

        // Mode 2: Antar via Zendo (Pelacakan Kurir)
        if (isset($dbSettings['order_mode_zendo_tracking'])) {
            $orderModeZendoTracking = in_array((string) $dbSettings['order_mode_zendo_tracking'], ['1', 'true', 'yes'], true);
        } else {
            $orderModeZendoTracking = in_array($legacyMode, ['zendo', 'both'], true) && ! $legacyHideTracking;
        }

        // Mode 3: Hanya Chat ke Zendo (WhatsApp)
        if (isset($dbSettings['order_mode_zendo_chat'])) {
            $orderModeZendoChat = in_array((string) $dbSettings['order_mode_zendo_chat'], ['1', 'true', 'yes'], true);
        } else {
            $orderModeZendoChat = in_array($legacyMode, ['zendo', 'both'], true) && $legacyHideTracking;
        }

        // Fail-safe mutual exclusion read: if both Mode 2 and Mode 3 are somehow true, Mode 2 wins
        if ($orderModeZendoTracking && $orderModeZendoChat) {
            $orderModeZendoChat = false;
        }

        // Sub-settings
        $orderDineInCash = ! isset($dbSettings['order_dine_in_cash']) || in_array((string) $dbSettings['order_dine_in_cash'], ['1', 'true', 'yes'], true);
        $orderDineInQris = ! isset($dbSettings['order_dine_in_qris']) || in_array((string) $dbSettings['order_dine_in_qris'], ['1', 'true', 'yes'], true);
        $qrisImage = $dbSettings['qris_image'] ?? null;

        $zendoWhatsapp = Setting::get('zendo_whatsapp', config('nucomu.contact.zendo_whatsapp', '085707300240'));
        $zendoChatTemplate = Setting::get('zendo_chat_template') ?: self::DEFAULT_ZENDO_TEMPLATE;

        $zendoTariff = [
            'tier_0_3' => (int) ($dbSettings['zendo_tier_0_3'] ?? 10000),
            'tier_3_4' => (int) ($dbSettings['zendo_tier_3_4'] ?? 12000),
            'tier_4_5' => (int) ($dbSettings['zendo_tier_4_5'] ?? 15000),
            'extra_per_km' => (int) ($dbSettings['zendo_extra_per_km'] ?? 2500),
        ];
        $zendoPublicUrl = $dbSettings['zendo_public_url'] ?? null;

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'readyOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue',
            'totalDineIn',
            'totalZendoTracking',
            'totalZendoChat',
            'orderModeDineIn',
            'orderModeZendoTracking',
            'orderModeZendoChat',
            'orderDineInCash',
            'orderDineInQris',
            'qrisImage',
            'zendoWhatsapp',
            'zendoChatTemplate',
            'zendoTariff',
            'zendoPublicUrl'
        ));
    }

    /**
     * Save 3 transaction modes, sub-settings, and normalize mutual locks.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'zendo_whatsapp' => 'nullable|string|max:30',
            'zendo_chat_template' => 'nullable|string|max:2000',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'last_changed_mode' => 'nullable|string|in:tracking,chat',
        ], [
            'qris_image.image' => 'File QRIS harus berupa gambar.',
            'qris_image.mimes' => 'Format gambar QRIS yang didukung adalah JPEG, PNG, JPG, atau WebP.',
            'qris_image.max' => 'Ukuran file gambar QRIS tidak boleh melebihi 2 MB.',
        ]);

        $dineIn = $request->boolean('order_mode_dine_in');
        $tracking = $request->boolean('order_mode_zendo_tracking');
        $chat = $request->boolean('order_mode_zendo_chat');
        $lastChanged = $request->input('last_changed_mode');

        $autoDisabledMessage = null;

        // Mutual locking between Mode 2 (Tracking) and Mode 3 (Chat)
        if ($tracking && $chat) {
            if ($lastChanged === 'chat') {
                $tracking = false;
                $autoDisabledMessage = 'Mode pelacakan kurir dimatikan otomatis karena Anda baru saja menyalakan mode chat Zendo (kedua mode antar tidak dapat aktif bersamaan).';
            } elseif ($lastChanged === 'tracking') {
                $chat = false;
                $autoDisabledMessage = 'Mode chat Zendo dimatikan otomatis karena Anda baru saja menyalakan mode pelacakan kurir (kedua mode antar tidak dapat aktif bersamaan).';
            } else {
                // Fallback jika penanda tidak ada atau tidak jelas: pelacakan kurir menang, chat dimatikan
                $chat = false;
                $autoDisabledMessage = 'Mode chat Zendo dimatikan otomatis demi memprioritaskan mode pelacakan kurir (kedua mode antar tidak dapat aktif bersamaan).';
            }
        }

        // Simpan 3 key utama baru
        Setting::set('order_mode_dine_in', $dineIn ? '1' : '0');
        Setting::set('order_mode_zendo_tracking', $tracking ? '1' : '0');
        Setting::set('order_mode_zendo_chat', $chat ? '1' : '0');

        // Sub-pengaturan Mode 1: Makan di Cafe
        $dineInCash = $request->boolean('order_dine_in_cash');
        $dineInQris = $request->boolean('order_dine_in_qris');
        if ($dineIn && ! $dineInCash && ! $dineInQris) {
            $dineInCash = true; // Minimal satu opsi aktif jika mode makan di cafe aktif
        }
        Setting::set('order_dine_in_cash', $dineInCash ? '1' : '0');
        Setting::set('order_dine_in_qris', $dineInQris ? '1' : '0');

        // Upload / Hapus Foto QRIS
        if ($request->boolean('remove_qris_image')) {
            $oldQris = Setting::get('qris_image');
            if ($oldQris && file_exists(public_path($oldQris))) {
                @unlink(public_path($oldQris));
            }
            Setting::set('qris_image', null);
        } elseif ($request->hasFile('qris_image')) {
            $file = $request->file('qris_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'qris_'.time().'_'.Str::random(6).'.'.$extension;
            $uploadDir = public_path('uploads/qris');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $fileName);
            Setting::set('qris_image', 'uploads/qris/'.$fileName);
        }

        // Tulis nilai key lama untuk kompatibilitas penuh (backward-compatibility)
        $legacyTransactionMode = 'none';
        if ($dineIn && ($tracking || $chat)) {
            $legacyTransactionMode = 'both';
        } elseif ($dineIn) {
            $legacyTransactionMode = 'kasir';
        } elseif ($tracking || $chat) {
            $legacyTransactionMode = 'zendo';
        }
        Setting::set('transaction_mode', $legacyTransactionMode);
        Setting::set('hide_live_tracking', $chat ? '1' : '0');
        Setting::set('order_mode_zendo', ($tracking || $chat) ? '1' : '0');

        // Sinkronisasi nomor WhatsApp Zendo (1 sumber dengan Kontak)
        if ($request->filled('zendo_whatsapp')) {
            $cleanPhone = self::normalizeWhatsappNumber((string) $request->input('zendo_whatsapp'));
            if (strlen($cleanPhone) >= 9) {
                Setting::set('zendo_whatsapp', $cleanPhone);
                Setting::set('zendo_courier_1_phone', $cleanPhone);
            }
        }

        // Template Chat Zendo
        if ($request->has('zendo_chat_template')) {
            $tpl = trim((string) $request->input('zendo_chat_template'));
            Setting::set('zendo_chat_template', $tpl !== '' ? $tpl : self::DEFAULT_ZENDO_TEMPLATE);
        }

        // Menjaga kompatibilitas jika grabfood_url dikirimkan lewat form kontak/tes
        if ($request->has('grabfood_url')) {
            Setting::set('grabfood_url', trim((string) $request->input('grabfood_url')));
        }
        if ($request->has('order_mode_grabfood')) {
            Setting::set('order_mode_grabfood', $request->boolean('order_mode_grabfood') ? '1' : '0');
        }

        $successMsg = 'Pengaturan cara transaksi berhasil disimpan.';
        if ($autoDisabledMessage) {
            $successMsg .= ' '.$autoDisabledMessage;
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Reset Zendo WhatsApp chat template to default format.
     */
    public function resetTemplate()
    {
        Setting::set('zendo_chat_template', self::DEFAULT_ZENDO_TEMPLATE);

        return back()->with('success', 'Template chat WhatsApp Zendo berhasil dikembalikan ke format standar.');
    }

    public function show(Order $order)
    {
        $order->load(['table', 'items']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid',
        ]);
        $data = ['status' => $request->status];
        if ($request->filled('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }

        $order->update($data);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function printReceipt(Order $order)
    {
        $order->load(['table', 'items']);

        return view('admin.orders.receipt', compact('order'));
    }

    /**
     * QR Scan landing — admin or kasir scans QR, gets redirected here.
     * Accessible without login (public-facing via QR code).
     */
    public function qrDetail(Request $request, string $code)
    {
        $order = Order::where('code', $code)->with(['table', 'items'])->firstOrFail();

        // Check or restore Kasir/Admin cookie auth
        KasirController::checkOrRestoreKasirAuth($request);

        if (auth()->check()) {
            if (auth()->user()->role === 'kasir') {
                return redirect()->route('kasir.index', ['tab' => 'scan', 'code' => $order->code]);
            }
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.orders.show', $order->id);
            }
        }

        // Guest view — simple order detail for kasir
        return view('admin.orders.qr-detail', compact('order'));
    }

    /**
     * Scan page — admin uses camera to scan QR.
     */
    public function scanPage(Request $request)
    {
        $order = null;
        if ($request->filled('code')) {
            $order = Order::where('code', strtoupper($request->code))->with('items')->first();
        }

        $recentOrders = Order::with('items')->latest()->take(5)->get();

        return view('admin.orders.scan', compact('order', 'recentOrders'));
    }

    /**
     * Export / Download Transaction Recap Report (PDF, Print View, or CSV)
     * Supports Daily (per hari), Weekly (per minggu), and Monthly (per bulan).
     */
    public function exportRecap(Request $request)
    {
        $rawPeriod = strtolower(trim((string) $request->query('period', 'daily')));
        $period = match ($rawPeriod) {
            'weekly', 'mingguan', 'minggu', 'week' => 'weekly',
            'monthly', 'bulanan', 'bulan', 'month' => 'monthly',
            default => 'daily',
        };

        $format = strtolower(trim((string) $request->query('format', 'pdf')));
        if (! in_array($format, ['pdf', 'print', 'csv'], true)) {
            $format = 'pdf';
        }

        $today = Carbon::today();
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        if ($period === 'daily') {
            $dateInput = trim((string) $request->query('date', $today->toDateString()));
            try {
                $targetDate = Carbon::parse($dateInput);
            } catch (\Throwable $e) {
                $targetDate = $today->copy();
            }
            $startDate = $targetDate->copy()->startOfDay();
            $endDate = $targetDate->copy()->endOfDay();
            $periodLabel = 'Harian (Per Hari)';
            $dateRangeLabel = $targetDate->format('d').' '.($monthNames[(int) $targetDate->format('n')] ?? $targetDate->format('M')).' '.$targetDate->format('Y');
            $fileSlug = 'harian-'.$targetDate->format('Y-m-d');
        } elseif ($period === 'weekly') {
            $weekInput = trim((string) ($request->query('week_date') ?: $request->query('week') ?: $request->query('date') ?: $today->toDateString()));
            try {
                if (preg_match('/^(\d{4})-W(\d{1,2})$/i', $weekInput, $matches)) {
                    $refDate = Carbon::now()->setISODate((int) $matches[1], (int) $matches[2], 1);
                } else {
                    $refDate = Carbon::parse($weekInput);
                }
            } catch (\Throwable $e) {
                $refDate = $today->copy();
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                try {
                    $startDate = Carbon::parse((string) $request->query('start_date'))->startOfDay();
                    $endDate = Carbon::parse((string) $request->query('end_date'))->endOfDay();
                } catch (\Throwable $e) {
                    $startDate = $refDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
                    $endDate = $refDate->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
                }
            } else {
                $startDate = $refDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
                $endDate = $refDate->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            }

            $periodLabel = 'Mingguan (Per Minggu)';
            $dateRangeLabel = $startDate->format('d/m/Y').' s/d '.$endDate->format('d/m/Y');
            $fileSlug = 'mingguan-'.$startDate->format('Ymd').'-'.$endDate->format('Ymd');
        } else {
            $monthInput = trim((string) $request->query('month', $today->format('Y-m')));
            try {
                if (preg_match('/^(\d{4})-(\d{1,2})$/', $monthInput, $matches)) {
                    $refMonth = Carbon::createFromDate((int) $matches[1], (int) $matches[2], 1);
                } else {
                    $refMonth = Carbon::parse($monthInput)->startOfMonth();
                }
            } catch (\Throwable $e) {
                $refMonth = $today->copy()->startOfMonth();
            }

            $startDate = $refMonth->copy()->startOfMonth()->startOfDay();
            $endDate = $refMonth->copy()->endOfMonth()->endOfDay();
            $periodLabel = 'Bulanan (Per Bulan)';
            $dateRangeLabel = ($monthNames[(int) $startDate->format('n')] ?? $startDate->format('F')).' '.$startDate->format('Y');
            $fileSlug = 'bulanan-'.$startDate->format('Y-m');
        }

        $query = Order::with(['table', 'items', 'zendoDelivery'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $filterParts = [];

        if ($request->filled('status') && $request->query('status') !== 'all') {
            $statusFilter = (string) $request->query('status');
            $query->where('status', $statusFilter);
            $filterParts[] = 'Status: '.ucfirst($statusFilter);
        }

        if ($request->filled('kind') && $request->query('kind') !== 'all') {
            $kindFilter = (string) $request->query('kind');
            $this->applyOrderKindFilter($query, $kindFilter);
            $filterParts[] = 'Jenis: '.match ($kindFilter) {
                'dine_in' => 'Di Cafe',
                'zendo_tracking' => 'Antar Zendo',
                'zendo_chat' => 'Chat Zendo',
                default => $kindFilter,
            };
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Calculate Summary Metrics
        $nonCancelled = $orders->where('status', '!=', 'cancelled');
        $paidOrCompleted = $nonCancelled->filter(fn (Order $o) => $o->payment_status === 'paid' || $o->status === 'completed');

        $summary = [
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'pending_orders' => $orders->whereIn('status', ['pending', 'processing', 'ready'])->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'paid_or_completed_count' => $paidOrCompleted->count(),
            'paid_revenue' => (int) $paidOrCompleted->sum('total'),
            'gross_revenue' => (int) $nonCancelled->sum('total'),
            'subtotal_menu' => (int) $nonCancelled->sum('subtotal'),
            'delivery_fee_total' => (int) $nonCancelled->sum('delivery_fee'),
            'cash_count' => $nonCancelled->filter(fn (Order $o) => strtolower((string) $o->payment_method) !== 'qris')->count(),
            'qris_count' => $nonCancelled->filter(fn (Order $o) => strtolower((string) $o->payment_method) === 'qris')->count(),
            'dine_in_count' => $nonCancelled->filter(fn (Order $o) => $o->kind === 'dine_in')->count(),
            'zendo_tracking_count' => $nonCancelled->filter(fn (Order $o) => $o->kind === 'zendo_tracking')->count(),
            'zendo_chat_count' => $nonCancelled->filter(fn (Order $o) => $o->kind === 'zendo_chat')->count(),
        ];

        // Build Daily Breakdown for Weekly & Monthly recaps
        $dailyBreakdown = [];
        if ($period !== 'daily') {
            $groupedByDate = $orders->groupBy(fn (Order $o) => $o->created_at ? $o->created_at->format('Y-m-d') : 'unknown');
            foreach ($groupedByDate as $dateKey => $dayOrders) {
                $dayNonCancelled = $dayOrders->where('status', '!=', 'cancelled');
                $dayPaid = $dayNonCancelled->filter(fn (Order $o) => $o->payment_status === 'paid' || $o->status === 'completed');
                try {
                    $dCarbon = Carbon::parse($dateKey);
                    $dLabel = $dCarbon->format('d/m/Y');
                } catch (\Throwable $e) {
                    $dLabel = $dateKey;
                }
                $dailyBreakdown[] = [
                    'date_key' => $dateKey,
                    'date_label' => $dLabel,
                    'orders_count' => $dayOrders->count(),
                    'paid_count' => $dayPaid->count(),
                    'paid_revenue' => (int) $dayPaid->sum('total'),
                    'gross_revenue' => (int) $dayNonCancelled->sum('total'),
                ];
            }
        }

        $cafeName = Setting::get('cafe_name', config('nucomu.brand.name', 'Nucomu Cafe'));
        $meta = [
            'cafe_name' => $cafeName,
            'period' => $period,
            'period_label' => $periodLabel,
            'date_range_label' => $dateRangeLabel,
            'filter_label' => empty($filterParts) ? 'Semua Jenis & Status' : implode(', ', $filterParts),
            'printed_at' => now()->format('d/m/Y H:i'),
        ];

        if ($format === 'print') {
            return view('admin.orders.report-print', compact('meta', 'summary', 'orders', 'dailyBreakdown'));
        }

        if ($format === 'csv') {
            $csvFilename = "rekap-transaksi-{$fileSlug}.csv";

            return response()->streamDownload(function () use ($meta, $summary, $orders) {
                $out = fopen('php://output', 'w');
                // UTF-8 BOM for Excel compatibility
                fwrite($out, "\xEF\xBB\xBF");
                fputcsv($out, [$meta['cafe_name'].' - LAPORAN REKAP TRANSAKSI']);
                fputcsv($out, ['Periode', $meta['period_label'], 'Rentang', $meta['date_range_label']]);
                fputcsv($out, ['Total Pesanan', $summary['total_orders'], 'Omzet Lunas/Selesai (Rp)', $summary['paid_revenue'], 'Total Non-Batal (Rp)', $summary['gross_revenue']]);
                fputcsv($out, []);
                fputcsv($out, ['No', 'Kode Pesanan', 'Tanggal & Waktu', 'Nama Pelanggan', 'No. WhatsApp', 'Jenis Layanan', 'Metode Bayar', 'Status Bayar', 'Status Pesanan', 'Subtotal Menu (Rp)', 'Ongkir (Rp)', 'Total Akhir (Rp)']);

                foreach ($orders->values() as $idx => $order) {
                    fputcsv($out, [
                        $idx + 1,
                        $order->code,
                        $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-',
                        $order->customer_name,
                        $order->customer_phone ?: '-',
                        $order->kind_label,
                        $order->payment_method_label,
                        $order->payment_status_label,
                        $order->status_label,
                        $order->subtotal,
                        $order->delivery_fee,
                        $order->total,
                    ]);
                }

                fclose($out);
            }, $csvFilename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        // Default: Generate and download native PDF binary
        $pdfBinary = TransactionReportPdfGenerator::generate($meta, $summary, $orders, $dailyBreakdown);
        $pdfFilename = "rekap-transaksi-{$fileSlug}.pdf";

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$pdfFilename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    /**
     * Apply kind filter (dine_in, zendo_tracking, zendo_chat) to an Order query builder.
     */
    protected function applyOrderKindFilter(Builder $query, string $kind): void
    {
        if ($kind === 'dine_in') {
            $query->where(function ($q) {
                $q->whereNull('delivery_address')
                    ->where(function ($n) {
                        $n->whereNull('notes')
                            ->orWhere('notes', 'not like', '%[KURIR ZENDO]%');
                    });
            });
        } elseif ($kind === 'zendo_tracking') {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('notes', 'like', '%[KURIR ZENDO]%')
                        ->orWhereNotNull('delivery_address');
                })->where(function ($sub2) {
                    $sub2->where('notes', 'like', '%Pin:%')
                        ->orWhere('notes', 'like', '%Tahap_Zendo%')
                        ->orWhere('form_data', 'like', '%customer_lat%')
                        ->orWhereHas('zendoDelivery');
                });
            });
        } elseif ($kind === 'zendo_chat') {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('notes', 'like', '%[KURIR ZENDO]%')
                        ->orWhereNotNull('delivery_address');
                })->where('notes', 'not like', '%Pin:%')
                    ->where('notes', 'not like', '%Tahap_Zendo%')
                    ->where(function ($sub3) {
                        $sub3->whereNull('form_data')
                            ->orWhere('form_data', 'not like', '%customer_lat%');
                    })
                    ->whereDoesntHave('zendoDelivery');
            });
        }
    }

    /**
     * Helper to normalize Indonesian WhatsApp numbers to 628xxx format.
     */
    public static function normalizeWhatsappNumber(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($digits, '08')) {
            return '628'.substr($digits, 2);
        }
        if (str_starts_with($digits, '8')) {
            return '628'.substr($digits, 1);
        }
        if (str_starts_with($digits, '628')) {
            return $digits;
        }
        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        return $digits;
    }
}
