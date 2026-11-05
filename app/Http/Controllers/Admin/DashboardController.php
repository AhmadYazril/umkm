<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where(function ($q) {
                $q->where('payment_status', 'paid')
                    ->orWhere('status', 'completed');
            })
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $totalMenus = Menu::available()->count();

        $stats = [
            'revenue_today' => $todayRevenue,
            'orders_today' => $todayOrders,
            'pending_orders' => $pendingOrders,
            'total_menus' => $totalMenus,
        ];

        // Menu terlaris dari order_items
        $topMenus = DB::table('order_items')
            ->select('menu_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->groupBy('menu_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Pesanan terbaru untuk tabel
        $recentOrders = Order::latest()->take(10)->get();

        // Cek pengaturan visibilitas grafik keuangan di Dashboard
        // Jika dalam posisi hide, grafik tidak dirender dan kalkulasi datanya tidak dijalankan
        $hideDashboardChart = in_array((string) Setting::get('hide_dashboard_chart', '0'), ['1', 'true', 'yes'], true)
            || in_array((string) Setting::get('show_dashboard_chart', '1'), ['0', 'false', 'no'], true);
        $showDashboardChart = ! $hideDashboardChart;

        $financialChart = $showDashboardChart
            ? $this->buildFinancialSalesChartData($today)
            : null;

        return view('admin.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'pendingOrders',
            'pendingReservations',
            'topMenus',
            'recentOrders',
            'stats',
            'showDashboardChart',
            'hideDashboardChart',
            'financialChart'
        ));
    }

    /**
     * Build financial sales chart data for Daily (7 days), Weekly (4 weeks), and Monthly (12 months).
     * Only executed when the dashboard chart is active (not hidden).
     *
     * @return array<string, mixed>
     */
    protected function buildFinancialSalesChartData(Carbon $today): array
    {
        $earliestDate = $today->copy()->subMonths(11)->startOfMonth();

        $orders = Order::query()
            ->select(['id', 'total', 'subtotal', 'delivery_fee', 'status', 'payment_status', 'created_at'])
            ->where('created_at', '>=', $earliestDate)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at')
            ->get();

        $monthNamesShort = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];
        $dayNames = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
        ];

        // 1. Daily (7 Hari Terakhir)
        $dailyBuckets = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = $today->copy()->subDays($i);
            $key = $d->format('Y-m-d');
            $dailyBuckets[$key] = [
                'label' => $d->format('d').' '.($monthNamesShort[(int) $d->format('n')] ?? $d->format('M')),
                'full_label' => ($dayNames[$d->dayOfWeek] ?? '').', '.$d->format('d').' '.($monthNamesShort[(int) $d->format('n')] ?? '').' '.$d->format('Y'),
                'revenue' => 0,
                'gross_sales' => 0,
                'orders_count' => 0,
                'paid_count' => 0,
            ];
        }

        // 2. Weekly (4 Minggu Terakhir)
        $weeklyBuckets = [];
        for ($w = 3; $w >= 0; $w--) {
            $startW = $today->copy()->subWeeks($w)->startOfWeek(Carbon::MONDAY);
            $endW = $today->copy()->subWeeks($w)->endOfWeek(Carbon::SUNDAY);
            if ($endW->gt($today)) {
                $endW = $today->copy()->endOfDay();
            }
            $wKey = 'W_'.$w;
            $weeklyBuckets[$wKey] = [
                'start' => $startW->copy()->startOfDay(),
                'end' => $endW->copy()->endOfDay(),
                'label' => $startW->format('d').' '.($monthNamesShort[(int) $startW->format('n')] ?? '').' - '.$endW->format('d').' '.($monthNamesShort[(int) $endW->format('n')] ?? ''),
                'full_label' => 'Minggu ('.$startW->format('d/m/Y').' s/d '.$endW->format('d/m/Y').')',
                'revenue' => 0,
                'gross_sales' => 0,
                'orders_count' => 0,
                'paid_count' => 0,
            ];
        }

        // 3. Monthly (12 Bulan Terakhir)
        $monthlyBuckets = [];
        for ($m = 11; $m >= 0; $m--) {
            $mDate = $today->copy()->startOfMonth()->subMonths($m);
            $mKey = $mDate->format('Y-m');
            $mName = $monthNamesShort[(int) $mDate->format('n')] ?? $mDate->format('M');
            $monthlyBuckets[$mKey] = [
                'label' => $mName.' '.$mDate->format('y'),
                'full_label' => $mName.' '.$mDate->format('Y'),
                'revenue' => 0,
                'gross_sales' => 0,
                'orders_count' => 0,
                'paid_count' => 0,
            ];
        }

        foreach ($orders as $order) {
            if (! $order->created_at) {
                continue;
            }
            $createdAt = $order->created_at;
            $dateKey = $createdAt->format('Y-m-d');
            $monthKey = $createdAt->format('Y-m');
            $total = (int) $order->total;
            $isPaidOrCompleted = ($order->payment_status === 'paid' || $order->status === 'completed');

            if (isset($dailyBuckets[$dateKey])) {
                $dailyBuckets[$dateKey]['gross_sales'] += $total;
                $dailyBuckets[$dateKey]['orders_count']++;
                if ($isPaidOrCompleted) {
                    $dailyBuckets[$dateKey]['revenue'] += $total;
                    $dailyBuckets[$dateKey]['paid_count']++;
                }
            }

            foreach ($weeklyBuckets as $wKey => &$wBucket) {
                if ($createdAt->betweenIncluded($wBucket['start'], $wBucket['end'])) {
                    $wBucket['gross_sales'] += $total;
                    $wBucket['orders_count']++;
                    if ($isPaidOrCompleted) {
                        $wBucket['revenue'] += $total;
                        $wBucket['paid_count']++;
                    }
                    break;
                }
            }
            unset($wBucket);

            if (isset($monthlyBuckets[$monthKey])) {
                $monthlyBuckets[$monthKey]['gross_sales'] += $total;
                $monthlyBuckets[$monthKey]['orders_count']++;
                if ($isPaidOrCompleted) {
                    $monthlyBuckets[$monthKey]['revenue'] += $total;
                    $monthlyBuckets[$monthKey]['paid_count']++;
                }
            }
        }

        // Remove Carbon instances from weekly buckets before serializing to JSON
        foreach ($weeklyBuckets as &$wb) {
            unset($wb['start'], $wb['end']);
        }
        unset($wb);

        return [
            'daily' => $this->compileChartSeries(array_values($dailyBuckets), '7 Hari Terakhir'),
            'weekly' => $this->compileChartSeries(array_values($weeklyBuckets), '4 Minggu Terakhir'),
            'monthly' => $this->compileChartSeries(array_values($monthlyBuckets), '12 Bulan Terakhir'),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $points
     * @return array<string, mixed>
     */
    protected function compileChartSeries(array $points, string $periodTitle): array
    {
        $totalRevenue = 0;
        $totalGrossSales = 0;
        $totalOrders = 0;
        $totalPaidOrders = 0;
        $peakRevenue = 0;
        $peakLabel = '-';

        foreach ($points as $pt) {
            $rev = (int) ($pt['revenue'] ?? 0);
            $gross = (int) ($pt['gross_sales'] ?? 0);
            $totalRevenue += $rev;
            $totalGrossSales += $gross;
            $totalOrders += (int) ($pt['orders_count'] ?? 0);
            $totalPaidOrders += (int) ($pt['paid_count'] ?? 0);

            $effectiveVal = $rev > 0 ? $rev : $gross;
            if ($effectiveVal > $peakRevenue) {
                $peakRevenue = $effectiveVal;
                $peakLabel = (string) ($pt['label'] ?? '-');
            }
        }

        $count = max(1, count($points));

        return [
            'title' => $periodTitle,
            'points' => $points,
            'labels' => array_column($points, 'label'),
            'revenues' => array_column($points, 'revenue'),
            'gross_sales' => array_column($points, 'gross_sales'),
            'orders_count' => array_column($points, 'orders_count'),
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_gross_sales' => $totalGrossSales,
                'total_orders' => $totalOrders,
                'total_paid_orders' => $totalPaidOrders,
                'avg_revenue' => (int) round($totalRevenue / $count),
                'peak_revenue' => $peakRevenue,
                'peak_label' => $peakLabel,
            ],
        ];
    }
}
