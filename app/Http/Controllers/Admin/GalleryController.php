<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Support\FileUploadHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery items in the admin panel.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $status = trim((string) $request->input('status', 'all'));
        $category = trim((string) $request->input('category', 'all'));

        $query = GalleryItem::query();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('caption', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($category !== 'all' && $category !== '') {
            $query->where('category', $category);
        }

        $items = $query->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        $totalCount = GalleryItem::count();
        $activeCount = GalleryItem::where('is_active', true)->count();
        $inactiveCount = GalleryItem::where('is_active', false)->count();

        // Get distinct categories for filter buttons
        $categories = GalleryItem::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->values();

        return view('admin.gallery.index', compact(
            'items',
            'totalCount',
            'activeCount',
            'inactiveCount',
            'categories',
            'q',
            'status',
            'category'
        ));
    }

    /**
     * Store a newly created gallery item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'caption' => 'required|string|max:150',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'caption.required' => 'Judul foto galeri wajib diisi.',
            'caption.max' => 'Judul foto galeri maksimal 150 karakter.',
            'image.required' => 'File foto galeri wajib diunggah.',
            'image.uploaded' => 'File foto galeri gagal diunggah ke server.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran file foto galeri maksimal 2 MB (2048 KB).',
            'description.max' => 'Keterangan foto maksimal 500 karakter.',
            'sort_order.integer' => 'Urutan harus berupa angka bulat positif.',
        ]);

        try {
            $destinationPath = public_path('uploads/gallery');
            FileUploadHelper::ensureDirectoryWritable($destinationPath, 'folder upload Galeri');

            $file = $request->file('image');
            $fileName = 'gallery_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            $sortOrder = $request->filled('sort_order')
                ? (int) $request->input('sort_order')
                : ((int) GalleryItem::max('sort_order') + 1);

            $category = trim((string) $request->input('category')) ?: 'ambience';
            $badge = '#'.ucfirst($category);

            GalleryItem::create([
                'image' => $fileName,
                'caption' => trim((string) $request->input('caption')),
                'description' => $request->filled('description') ? trim((string) $request->input('description')) : null,
                'category' => $category,
                'category_badge' => $badge,
                'author' => 'Nucomu Cafe',
                'time_label' => 'Baru saja',
                'sort_order' => $sortOrder,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Foto galeri baru berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan foto galeri: '.$e->getMessage());
        }
    }

    /**
     * Show details or return JSON for edit modal.
     */
    public function edit(GalleryItem $galleryItem, Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $galleryItem->id,
                    'title' => $galleryItem->caption,
                    'caption' => $galleryItem->caption,
                    'description' => $galleryItem->description ?? '',
                    'category' => $galleryItem->category ?? 'ambience',
                    'sort_order' => $galleryItem->sort_order,
                    'is_active' => (bool) $galleryItem->is_active,
                    'image_url' => $galleryItem->image_url,
                ],
            ]);
        }

        return redirect()->route('admin.gallery.index');
    }

    /**
     * Update the specified gallery item in storage.
     */
    public function update(Request $request, GalleryItem $galleryItem): RedirectResponse
    {
        $request->validate([
            'caption' => 'required|string|max:150',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'caption.required' => 'Judul foto galeri wajib diisi.',
            'caption.max' => 'Judul foto galeri maksimal 150 karakter.',
            'image.uploaded' => 'File foto galeri gagal diunggah ke server.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran file foto galeri maksimal 2 MB (2048 KB).',
            'description.max' => 'Keterangan foto maksimal 500 karakter.',
            'sort_order.integer' => 'Urutan harus berupa angka bulat positif.',
        ]);

        try {
            if ($request->hasFile('image')) {
                $destinationPath = public_path('uploads/gallery');
                FileUploadHelper::ensureDirectoryWritable($destinationPath, 'folder upload Galeri');

                // Delete old file
                $galleryItem->deleteImageFile();

                $file = $request->file('image');
                $fileName = 'gallery_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $galleryItem->image = $fileName;
            }

            $galleryItem->caption = trim((string) $request->input('caption'));
            $galleryItem->description = $request->filled('description') ? trim((string) $request->input('description')) : null;

            if ($request->filled('category')) {
                $galleryItem->category = trim((string) $request->input('category'));
                $galleryItem->category_badge = '#'.ucfirst($galleryItem->category);
            }

            if ($request->filled('sort_order')) {
                $galleryItem->sort_order = (int) $request->input('sort_order');
            }

            $galleryItem->is_active = $request->boolean('is_active', true);
            $galleryItem->save();

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Foto galeri "'.$galleryItem->caption.'" berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui foto galeri: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified gallery item and delete physical image.
     */
    public function destroy(GalleryItem $galleryItem): RedirectResponse
    {
        $caption = $galleryItem->caption ?: 'Foto galeri';
        $galleryItem->deleteImageFile();
        $galleryItem->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Foto "'.$caption.'" berhasil dihapus beserta file gambarnya.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleActive(GalleryItem $galleryItem, Request $request): JsonResponse|RedirectResponse
    {
        $galleryItem->is_active = ! $galleryItem->is_active;
        $galleryItem->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $galleryItem->is_active,
                'message' => $galleryItem->is_active
                    ? 'Foto berhasil diaktifkan di web.'
                    : 'Foto berhasil disembunyikan dari web.',
            ]);
        }

        return back()->with('success', $galleryItem->is_active
            ? 'Foto berhasil diaktifkan di web.'
            : 'Foto berhasil disembunyikan dari web.');
    }
}
