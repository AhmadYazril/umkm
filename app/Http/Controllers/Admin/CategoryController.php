<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\FileUploadHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = Category::withCount('menus')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->each(fn (Category $category) => $category->append('image_url'));

        $nextSortOrder = (Category::max('sort_order') ?? 0) + 1;
        $categoryPhotoLimits = FileUploadHelper::getEffectiveLimit(2 * 1024 * 1024, 'foto kategori');

        return view('admin.categories.index', compact('categories', 'nextSortOrder', 'categoryPhotoLimits'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $this->validateCategoryRequest($request);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug ?: 'kategori-'.time();
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $sortOrder = $validated['sort_order'] ?? ((Category::max('sort_order') ?? 0) + 1);

        try {
            $imageName = $request->hasFile('image') ? $this->storeUploadedImage($request) : null;

            $category = Category::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'image' => $imageName,
                'sort_order' => $sortOrder,
                'is_active' => $request->boolean('is_active', true),
                'description' => $validated['description'] ?? null,
            ]);
            $category->append('image_url');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan kategori: '.$e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal menyimpan kategori: '.$e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori "'.$category->name.'" berhasil ditambahkan.',
                'category' => $category,
            ]);
        }

        return back()->with('success', 'Kategori "'.$validated['name'].'" berhasil ditambahkan.');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse|JsonResponse
    {
        $validated = $this->validateCategoryRequest($request);

        $slug = $category->slug;
        if ($category->name !== $validated['name']) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug ?: 'kategori-'.$category->id;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
        }

        $sortOrder = $validated['sort_order'] ?? $category->sort_order;

        try {
            $imageName = $category->image;

            if ($request->boolean('remove_image') && ! $request->hasFile('image')) {
                $category->deleteImageFile();
                $imageName = null;
            }

            if ($request->hasFile('image')) {
                $newImageName = $this->storeUploadedImage($request);
                $category->deleteImageFile();
                $imageName = $newImageName;
            }

            $category->update([
                'name' => $validated['name'],
                'slug' => $slug,
                'image' => $imageName,
                'sort_order' => $sortOrder,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $category->is_active,
                'description' => array_key_exists('description', $validated) ? $validated['description'] : $category->description,
            ]);
            $category->append('image_url');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui kategori: '.$e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal memperbarui kategori: '.$e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori "'.$category->name.'" berhasil diperbarui.',
                'category' => $category,
            ]);
        }

        return back()->with('success', 'Kategori "'.$validated['name'].'" berhasil diperbarui.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse|JsonResponse
    {
        $menuCount = $category->menus()->count();

        if ($menuCount > 0) {
            $msg = 'Kategori "'.$category->name.'" masih berisi '.$menuCount.' menu. Pindahkan atau hapus menunya dulu.';
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'menu_count' => $menuCount,
                ], 422);
            }

            return back()->with('error', $msg);
        }

        $name = $category->name;
        $category->deleteImageFile();
        $category->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori "'.$name.'" berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'Kategori "'.$name.'" berhasil dihapus.');
    }

    /**
     * Toggle active status for category.
     */
    public function toggleActive(Category $category): RedirectResponse|JsonResponse
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        $statusText = $category->is_active ? 'ditampilkan di website' : 'disembunyikan dari website';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori "'.$category->name.'" '.$statusText.'.',
                'is_active' => $category->is_active,
            ]);
        }

        return back()->with('success', 'Kategori "'.$category->name.'" berhasil '.$statusText.'.');
    }

    /**
     * Reorder categories.
     */
    public function reorder(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:categories,id'],
        ]);

        foreach ($validated['order'] as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Urutan kategori berhasil disimpan.',
            ]);
        }

        return back()->with('success', 'Urutan kategori berhasil disimpan.');
    }

    /**
     * Validate create or update category payload including optional image upload.
     *
     * @return array<string, mixed>
     */
    private function validateCategoryRequest(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'image.uploaded' => 'Foto kategori gagal diunggah ke server. Pastikan ukuran file tidak melebihi batas sistem.',
            'image.image' => 'Berkas kategori harus berupa gambar.',
            'image.mimes' => 'Format foto kategori harus berupa JPG, JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran file foto kategori maksimal 2 MB.',
        ]);
    }

    /**
     * Persist uploaded category image under public/uploads/categories and return filename.
     */
    private function storeUploadedImage(Request $request): string
    {
        $uploadDir = public_path('uploads/categories');
        FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Kategori');

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension() ?: ($file->guessExtension() ?: 'jpg');
        $fileName = 'category_'.time().'_'.bin2hex(random_bytes(3)).'.'.$extension;
        $file->move($uploadDir, $fileName);

        return $fileName;
    }
}
