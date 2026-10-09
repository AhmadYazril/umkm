<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart   = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }
        $tables = Table::where('is_active', true)->get();
        $total  = collect($cart)->sum('line_total');
        return view('public.checkout', compact('cart', 'total', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_phone'   => 'required|string|max:20',
            'order_type'       => 'required|in:dine_in,take_away,pre_order',
            'table_id'         => 'nullable|exists:tables,id',
            'payment_method'   => 'required|in:cash,qris,transfer',
            'notes'            => 'nullable|string|max:500',
            'pickup_at'        => 'nullable|date',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = collect($cart)->sum('line_total');

        try {
            $order = DB::transaction(function () use ($request, $cart, $subtotal) {
                // Generate kode pesanan
                $date  = Carbon::now()->format('Ymd');
                $count = Order::whereDate('created_at', today())->count() + 1;
                $code  = 'NCM-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

                $order = Order::create([
                    'code'            => $code,
                    'customer_name'   => $request->customer_name,
                    'customer_phone'  => $request->customer_phone,
                    'order_type'      => $request->order_type,
                    'table_id'        => $request->table_id,
                    'notes'           => $request->notes,
                    'subtotal'        => $subtotal,
                    'total'           => $subtotal,
                    'payment_method'  => $request->payment_method,
                    'payment_status'  => 'unpaid',
                    'status'          => 'pending',
                    'pickup_at'       => $request->pickup_at,
                ]);

                foreach ($cart as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'menu_id'    => $item['menu_id'],
                        'menu_name'  => $item['menu_name'],
                        'unit_price' => $item['unit_price'],
                        'qty'        => $item['qty'],
                        'options'    => $item['options'],
                        'line_total' => $item['line_total'],
                    ]);
                }

                return $order;
            });

            session()->forget('cart');

            return redirect()->route('order.confirmation', $order->code);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }

    public function confirmation($code)
    {
        $order = Order::where('code', $code)->with('items')->firstOrFail();
        $whatsapp = Setting::get('cafe_whatsapp', '');
        return view('public.order-confirmation', compact('order', 'whatsapp'));
    }

    public function track(Request $request)
    {
        $order = null;
        if ($request->filled('code')) {
            $order = Order::where('code', $request->code)->with('items')->first();
        }
        return view('public.track-order', compact('order'));
    }
}
