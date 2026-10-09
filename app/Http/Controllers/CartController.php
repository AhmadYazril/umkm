<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuOption;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart  = session('cart', []);
        $total = collect($cart)->sum('line_total');
        return view('public.cart', compact('cart', 'total'));
    }

    public function add(Request $request, Menu $menu)
    {
        $request->validate([
            'qty'     => 'required|integer|min:1|max:20',
            'options' => 'nullable|array',
        ]);

        $cart   = session('cart', []);
        $key    = $menu->id . '_' . md5(json_encode($request->options ?? []));
        $price  = $menu->price;

        // Tambah harga dari options (addon)
        if ($request->options) {
            foreach ($request->options as $optionId) {
                $opt = MenuOption::find($optionId);
                if ($opt) $price += $opt->price;
            }
        }

        if (isset($cart[$key])) {
            $cart[$key]['qty']        += $request->qty;
            $cart[$key]['line_total']  = $cart[$key]['qty'] * $price;
        } else {
            $cart[$key] = [
                'menu_id'    => $menu->id,
                'menu_name'  => $menu->name,
                'unit_price' => $price,
                'qty'        => $request->qty,
                'options'    => $request->options ?? [],
                'line_total' => $request->qty * $price,
            ];
        }

        session(['cart' => $cart]);
        return back()->with('success', $menu->name . ' ditambahkan ke keranjang.');
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->key]);
        session(['cart' => $cart]);
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
