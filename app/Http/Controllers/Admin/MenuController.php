<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KasirController;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $query = Menu::with('category')->withTrashed();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('status')) {
            match ($request->status) {
                'available' => $query->where('is_available', true)->whereNull('deleted_at'),
                'unavailable' => $query->where('is_available', false)->whereNull('deleted_at'),
                'sample' => $query->where('is_sample', true)->whereNull('deleted_at'),
                'trashed' => $query->onlyTrashed(),
                default => null,
            };
        }

        $menus = $query->orderBy('sort_order')->paginate(20);
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        KasirController::ensureDatabaseSchemaAndSync();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.menus.form', compact('categories'));
    }

    public function store(Request $request)
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'nullable|integer|min:0|max:9999',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_sample' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $isAvailableInput = $request->has('is_available') ? $request->boolean('is_available') : true;
        $stock = $request->filled('stock')
            ? max(0, (int) $request->input('stock'))
            : ($isAvailableInput ? 25 : 0);

        if ($request->has('is_available') && ! $isAvailableInput && ! $request->filled('stock')) {
            $stock = 0;
        }

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(5);
        $validated['stock'] = $stock;
        $validated['is_available'] = $stock > 0 && $isAvailableInput;
        if (! $validated['is_available']) {
            $validated['stock'] = 0;
        }
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');
        $validated['is_sample'] = $request->boolean('is_sample');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu = Menu::create($validated);
        KasirController::syncSingleMenuStock($menu, (int) $validated['stock']);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan dan tersinkron ke Kasir.');
    }

    public function edit(Menu $menu)
    {
        KasirController::ensureDatabaseSchemaAndSync();
        $categories = Category::orderBy('sort_order')->get();
        $options = $menu->options;

        return view('admin.menus.form', compact('menu', 'categories', 'options'));
    }

    public function update(Request $request, Menu $menu)
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'nullable|integer|min:0|max:9999',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_sample' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $isAvailableInput = $request->boolean('is_available');
        if ($request->filled('stock')) {
            $stock = max(0, (int) $request->input('stock'));
            if (! $isAvailableInput && $stock === (int) ($menu->stock ?? 25)) {
                $stock = 0;
            }
        } else {
            $stock = $isAvailableInput
                ? (($menu->stock !== null && (int) $menu->stock > 0) ? (int) $menu->stock : 25)
                : 0;
        }

        $validated['stock'] = $stock;
        $validated['is_available'] = $stock > 0;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');
        $validated['is_sample'] = $request->boolean('is_sample');

        if ($request->hasFile('image')) {
            if ($menu->image && ! str_starts_with($menu->image, 'http')) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);
        KasirController::syncSingleMenuStock($menu, $stock);

        return redirect()->route('admin.menus.index')->with('success', 'Menu & stok berhasil diperbarui dan tersinkron ke Kasir.');
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
        KasirController::ensureDatabaseSchemaAndSync();

        $nextAvailable = ! $menu->is_available;
        $nextStock = $nextAvailable
            ? (($menu->stock !== null && (int) $menu->stock > 0) ? (int) $menu->stock : 25)
            : 0;

        KasirController::syncSingleMenuStock($menu, $nextStock);

        return back()->with('success', 'Status ketersediaan & stok menu diperbarui di Admin dan Kasir.');
    }

    public function updateStock(Request $request, Menu $menu)
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $validated = $request->validate([
            'stock' => 'nullable|integer|min:0|max:9999',
            'delta' => 'nullable|integer|min:-999|max:999',
        ]);

        $currentStock = $menu->stock !== null ? (int) $menu->stock : ($menu->is_available ? 25 : 0);
        if (array_key_exists('stock', $validated) && $validated['stock'] !== null) {
            $newStock = max(0, (int) $validated['stock']);
        } elseif (array_key_exists('delta', $validated) && $validated['delta'] !== null) {
            $newStock = max(0, $currentStock + (int) $validated['delta']);
        } else {
            $newStock = $currentStock;
        }

        $syncedStock = KasirController::syncSingleMenuStock($menu, $newStock);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'menu_id' => $menu->id,
                'stock' => $syncedStock,
                'is_available' => $syncedStock > 0,
                'message' => "Stok {$menu->name} diperbarui menjadi {$syncedStock} porsi.",
            ]);
        }

        return back()->with('success', "Stok {$menu->name} diperbarui menjadi {$syncedStock} porsi.");
    }

    public function toggleFeatured(Menu $menu)
    {
        $menu->update(['is_featured' => ! $menu->is_featured]);

        return back()->with('success', 'Status unggulan menu diperbarui.');
    }

    public function deleteSampleData()
    {
        $count = Menu::where('is_sample', true)->count();
        Menu::where('is_sample', true)->delete();

        return back()->with('success', "$count menu data contoh berhasil dihapus.");
    }
}
