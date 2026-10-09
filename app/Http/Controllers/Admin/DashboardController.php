<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todayOrders   = Order::whereDate('created_at', $today)->count();
        $todayRevenue  = Order::whereDate('created_at', $today)
                              ->where('payment_status', 'paid')
                              ->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();

        // Menu terlaris dari order_items
        $topMenus = DB::table('order_items')
            ->select('menu_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->groupBy('menu_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Pesanan 7 hari terakhir untuk grafik
        $recentOrders = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'todayOrders', 'todayRevenue', 'pendingOrders',
            'pendingReservations', 'topMenus', 'recentOrders'
        ));
    }
}
