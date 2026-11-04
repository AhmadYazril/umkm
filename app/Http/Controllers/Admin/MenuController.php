<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KasirController;
use App\Models\Category;
use App\Models\Menu;
use App\Support\FileUploadHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display a listing of menus.
     */
    public function index(Request $request): View
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $status = $request->input('status', 'all');
        $categoryId = $request->input('category');
        $search = trim((string) $request->input('search'));
        $sortBy = $request->input('sort', 'default');
        $perPage = (int) $request->input('per_page', 12);

        if (! in_array($perPage, [12, 24, 48])) {
            $perPage = 12;
        }

        // Base query
        if ($status === 'trashed') {
            $query = Menu::onlyTrashed()->with('category')->withCount('orderItems');
        } else {
            $query = Menu::with('category')->withCount('orderItems');
        }

        // Category filter
        if (! empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // Search
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        match ($sortBy) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('sort_order', 'asc')->orderByDesc('id'),
        };

        $menus = $query->paginate($perPage)->withQueryString();

        // Metrics for summary
        $totalCount = Menu::count();
        $trashedCount = Menu::onlyTrashed()->count();

        // Categories with menu count
        $categories = Category::withCount('menus')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $menuPhotoLimits = FileUploadHelper::getEffectiveLimit(1024 * 1024, 'foto menu');

        return view('admin.menus.index', compact(
            'menus',
            'categories',
            'totalCount',
            'trashedCount',
            'menuPhotoLimits',
            'status',
            'categoryId',
            'search',
            'sortBy',
            'perPage'
        ));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create(): View
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        if ($categories->isEmpty()) {
            $categories = Category::orderBy('sort_order')->get();
        }

        $nextSortOrder = (Menu::max('sort_order') ?? 0) + 1;
        $menuPhotoLimits = FileUploadHelper::getEffectiveLimit(1024 * 1024, 'foto menu');

        return view('admin.menus.form', compact('categories', 'nextSortOrder', 'menuPhotoLimits'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1500'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'category_id.required' => 'Pilih kategori yang sesuai untuk sajian ini.',
            'category_id.exists' => 'Kategori yang dipilih tidak terdaftar di sistem.',
            'name.required' => 'Nama menu wajib diisi.',
            'name.max' => 'Nama menu maksimal 255 karakter.',
            'price.required' => 'Harga menu wajib diisi.',
            'price.integer' => 'Harga menu harus berupa angka bulat dalam Rupiah (tanpa titik).',
            'price.min' => 'Harga menu tidak boleh kurang dari Rp 0.',
            'stock.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'stock.min' => 'Jumlah stok tidak boleh kurang dari 0.',
            'stock.max' => 'Jumlah stok maksimal 9999.',
            'image.uploaded' => 'Foto menu gagal diunggah ke server. Pastikan ukuran file tidak melebihi batas sistem.',
            'image.mimes' => 'Format foto menu harus berupa JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran file foto menu melebihi batas maksimal yang diizinkan.',
        ]);

        try {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug ?: 'menu-'.time();
            $counter = 1;
            while (Menu::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            $stock = $request->filled('stock')
                ? max(0, (int) $request->input('stock'))
                : 25;

            $validated['slug'] = $slug;
            $validated['stock'] = $stock;
            $validated['is_available'] = true;
            $validated['is_featured'] = $request->boolean('is_featured', false);
            $validated['is_best_seller'] = $request->boolean('is_best_seller', false);
            $validated['sort_order'] = $validated['sort_order'] ?? ((Menu::max('sort_order') ?? 0) + 1);

            if ($request->hasFile('image')) {
                $uploadDir = public_path('uploads/menus');
                FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Menu');

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'menu_'.time().'_'.bin2hex(random_bytes(3)).'.'.$extension;
                $file->move($uploadDir, $fileName);

                $validated['image'] = $fileName;
            }

            $menu = Menu::create($validated);
            KasirController::syncSingleMenuStock($menu, (int) $validated['stock']);

            if ($request->input('action') === 'save_and_add_another') {
                return redirect()->route('admin.menus.create')
                    ->with('success', 'Menu "'.$menu->name.'" berhasil disimpan. Silakan tambahkan menu berikutnya.');
            }

            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu "'.$menu->name.'" berhasil ditambahkan ke katalog.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan menu: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu): View
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $categories = Category::orderBy('sort_order')->get();
        $menuPhotoLimits = FileUploadHelper::getEffectiveLimit(1024 * 1024, 'foto menu');

        return view('admin.menus.form', compact('menu', 'categories', 'menuPhotoLimits'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1500'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'category_id.required' => 'Pilih kategori yang sesuai untuk sajian ini.',
            'category_id.exists' => 'Kategori yang dipilih tidak terdaftar di sistem.',
            'name.required' => 'Nama menu wajib diisi.',
            'name.max' => 'Nama menu maksimal 255 karakter.',
            'price.required' => 'Harga menu wajib diisi.',
            'price.integer' => 'Harga menu harus berupa angka bulat dalam Rupiah (tanpa titik).',
            'price.min' => 'Harga menu tidak boleh kurang dari Rp 0.',
            'stock.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'stock.min' => 'Jumlah stok tidak boleh kurang dari 0.',
            'stock.max' => 'Jumlah stok maksimal 9999.',
            'image.uploaded' => 'Foto menu gagal diunggah ke server. Pastikan ukuran file tidak melebihi batas sistem.',
            'image.mimes' => 'Format foto menu harus berupa JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran file foto menu melebihi batas maksimal yang diizinkan.',
        ]);

        try {
            $stock = $request->filled('stock')
                ? max(0, (int) $request->input('stock'))
                : (($menu->stock !== null) ? (int) $menu->stock : 25);

            $validated['stock'] = $stock;
            $validated['is_available'] = true;
            $validated['is_featured'] = $request->boolean('is_featured', false);
            $validated['is_best_seller'] = $request->boolean('is_best_seller', false);
            $validated['sort_order'] = $validated['sort_order'] ?? $menu->sort_order;

            // Hapus foto jika diminta
            if ($request->boolean('remove_image')) {
                $menu->deleteImageFile();
                $validated['image'] = null;
            }

            // Ganti foto baru jika diunggah
            if ($request->hasFile('image')) {
                $uploadDir = public_path('uploads/menus');
                FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Menu');

                $menu->deleteImageFile();

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'menu_'.time().'_'.bin2hex(random_bytes(3)).'.'.$extension;
                $file->move($uploadDir, $fileName);

                $validated['image'] = $fileName;
            }

            $menu->update($validated);
            KasirController::syncSingleMenuStock($menu, (int) $validated['stock']);

            return redirect()->route('admin.menus.index')
                ->with('success', 'Perubahan pada menu "'.$menu->name.'" berhasil disimpan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui menu: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(Request $request, Menu $menu): RedirectResponse|JsonResponse
    {
        $orderedCount = $menu->orderItems()->count();
        $name = $menu->name;

        // Jika menu pernah dipesan oleh pelanggan
        if ($orderedCount > 0) {
            // Jika diminta force delete, tolak karena foreign key riwayat transaksi
            if ($request->boolean('force')) {
                $msg = 'Menu "'.$name.'" sudah pernah dipesan pelanggan ('.$orderedCount.' pesanan) sehingga tidak dapat dihapus permanen agar riwayat transaksi tetap aman.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return back()->with('error', $msg);
            }

            // Soft-delete aman: arsipkan/sembunyikan dari katalog website
            $menu->delete();
            $msg = 'Menu "'.$name.'" berhasil disembunyikan dari katalog website (diarsipkan). Riwayat transaksi pesanan pelanggan tetap aman.';
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }

            return back()->with('success', $msg);
        }

        // Jika menu belum pernah dipesan, bisa soft delete atau force delete jika diminta
        if ($request->boolean('force')) {
            $menu->deleteImageFile();
            $menu->forceDelete();
            $msg = 'Menu "'.$name.'" berhasil dihapus permanen.';
        } else {
            $menu->delete();
            $msg = 'Menu "'.$name.'" berhasil dihapus dari katalog.';
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Restore the specified soft-deleted menu.
     */
    public function restore($id): RedirectResponse
    {
        $menu = Menu::withTrashed()->findOrFail($id);
        $menu->restore();
        KasirController::syncSingleMenuStock($menu, (int) ($menu->stock ?? 25));

        return back()->with('success', 'Menu "'.$menu->name.'" berhasil dipulihkan ke katalog.');
    }

    /**
     * Toggle availability status of menu.
     */
    public function toggleAvailable(Request $request, Menu $menu): RedirectResponse|JsonResponse
    {
        KasirController::ensureDatabaseSchemaAndSync();

        $nextAvailable = ! $menu->is_available;
        $nextStock = $nextAvailable
            ? (($menu->stock !== null && (int) $menu->stock > 0) ? (int) $menu->stock : 25)
            : 0;

        KasirController::syncSingleMenuStock($menu, $nextStock);

        $statusText = $nextAvailable ? 'tersedia' : 'habis (sold out)';
        $msg = 'Status menu "'.$menu->name.'" diubah menjadi '.$statusText.'.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'is_available' => $nextAvailable,
                'stock' => $nextStock,
            ]);
        }

        return back()->with('success', 'Status ketersediaan & stok menu diperbarui di Admin dan Kasir.');
    }

    /**
     * Update stock of a menu.
     */
    public function updateStock(Request $request, Menu $menu): RedirectResponse|JsonResponse
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

        if ($request->expectsJson() || $request->wantsJson()) {
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

    /**
     * Toggle featured status of menu.
     */
    public function toggleFeatured(Request $request, Menu $menu): RedirectResponse|JsonResponse
    {
        $menu->update(['is_featured' => ! $menu->is_featured]);

        $statusText = $menu->is_featured ? 'ditandai sebagai menu andalan' : 'dihapus dari menu andalan';
        $msg = 'Menu "'.$menu->name.'" '.$statusText.'.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'is_featured' => $menu->is_featured,
                'is_best_seller' => $menu->is_best_seller,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Toggle best seller status of menu.
     */
    public function toggleBestSeller(Request $request, Menu $menu): RedirectResponse|JsonResponse
    {
        $menu->update(['is_best_seller' => ! $menu->is_best_seller]);

        $statusText = $menu->is_best_seller ? 'ditandai sebagai best seller' : 'dihapus dari best seller';
        $msg = 'Menu "'.$menu->name.'" '.$statusText.'.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'is_best_seller' => $menu->is_best_seller,
                'is_featured' => $menu->is_featured,
            ]);
        }

        return back()->with('success', $msg);
    }
}
