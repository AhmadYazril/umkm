<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('category')->withTrashed();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            match ($request->status) {
                'available'   => $query->where('is_available', true)->whereNull('deleted_at'),
                'unavailable' => $query->where('is_available', false)->whereNull('deleted_at'),
                'sample'      => $query->where('is_sample', true)->whereNull('deleted_at'),
                'trashed'     => $query->onlyTrashed(),
                default       => null,
            };
        }

        $menus      = $query->orderBy('sort_order')->paginate(20);
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.menus.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
            'is_available'   => 'boolean',
            'is_featured'    => 'boolean',
            'is_best_seller' => 'boolean',
            'is_sample'      => 'boolean',
            'sort_order'     => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_available']   = $request->boolean('is_available');
        $validated['is_featured']    = $request->boolean('is_featured');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');
        $validated['is_sample']      = $request->boolean('is_sample');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::orderBy('sort_order')->get();
        $options    = $menu->options;
        return view('admin.menus.form', compact('menu', 'categories', 'options'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
            'is_available'   => 'boolean',
            'is_featured'    => 'boolean',
            'is_best_seller' => 'boolean',
            'is_sample'      => 'boolean',
            'sort_order'     => 'integer',
        ]);

        $validated['is_available']   = $request->boolean('is_available');
        $validated['is_featured']    = $request->boolean('is_featured');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');
        $validated['is_sample']      = $request->boolean('is_sample');

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus.');
    }

    public function restore($id)
    {
        Menu::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Menu berhasil dipulihkan.');
    }

    public function toggleAvailable(Menu $menu)
    {
        $menu->update(['is_available' => !$menu->is_available]);
        return back()->with('success', 'Status ketersediaan menu diperbarui.');
    }

    public function toggleFeatured(Menu $menu)
    {
        $menu->update(['is_featured' => !$menu->is_featured]);
        return back()->with('success', 'Status unggulan menu diperbarui.');
    }

    public function deleteSampleData()
    {
        $count = Menu::where('is_sample', true)->count();
        Menu::where('is_sample', true)->delete();
        return back()->with('success', "$count menu data contoh berhasil dihapus.");
    }
}
