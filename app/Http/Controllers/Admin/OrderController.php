<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('order_type', $request->type);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('customer_name', 'like', '%'.$request->search.'%');
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
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
     * QR Scan landing — admin scans QR, gets redirected here.
     * Accessible without login (public-facing via QR code).
     */
    public function qrDetail(string $code)
    {
        $order = Order::where('code', $code)->with(['table', 'items'])->firstOrFail();

        // Redirect to admin detail if logged in, else show simplified view
        if (auth()->check()) {
            return redirect()->route('admin.orders.show', $order->id);
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
}
