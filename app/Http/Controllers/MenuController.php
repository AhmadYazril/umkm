<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuOption;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $query = Menu::available()->with('category');

        if ($request->filled('kategori')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->kategori));
        }
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $menus = $query->orderBy('sort_order')->paginate(12);
        return view('public.menu', compact('menus', 'categories'));
    }

    public function show(Menu $menu)
    {
        $menu->load(['category', 'options']);
        $variants = $menu->options->where('type', 'variant');
        $addons   = $menu->options->where('type', 'addon');
        return view('public.menu-detail', compact('menu', 'variants', 'addons'));
    }
}
