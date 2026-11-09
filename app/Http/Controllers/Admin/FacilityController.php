<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Support\FileUploadHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Display a listing of facility slides.
     */
    public function index(): View
    {
        $facilities = Facility::orderBy('sort_order')->orderBy('id')->get();

        $totalCount = $facilities->count();
        $activeCount = $facilities->where('is_active', true)->count();
        $inactiveCount = $facilities->where('is_active', false)->count();
        $facilityPhotoLimits = FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto fasilitas');

        return view('admin.facilities.index', compact(
            'facilities',
            'totalCount',
            'activeCount',
            'inactiveCount',
            'facilityPhotoLimits'
        ));
    }

    /**
     * Show the form for creating a new facility slide.
     */
    public function create(): View
    {
        $nextSortOrder = (Facility::max('sort_order') ?? 0) + 1;
        $facilityPhotoLimits = FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto fasilitas');

        return view('admin.facilities.create', compact('nextSortOrder', 'facilityPhotoLimits'));
    }

    /**
     * Store a newly created facility slide in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tab_name' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:3000'],
            'features' => ['required', 'array', 'min:1'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'photo_files' => ['nullable', 'array', 'max:3'],
            'photo_files.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB
            'photo_captions' => ['nullable', 'array', 'max:3'],
            'photo_captions.*' => ['nullable', 'string', 'max:255'],
        ], [
            'tab_name.required' => 'Nama tombol pilihan (misal: Indoor, Outdoor, Musholla) wajib diisi.',
            'title.required' => 'Judul fasilitas wajib diisi.',
            'description.required' => 'Penjelasan singkat fasilitas wajib diisi.',
            'features.required' => 'Daftar keunggulan fasilitas wajib diisi minimal 1 poin.',
            'photo_files.*.uploaded' => 'File foto fasilitas gagal diunggah ke server.',
            'photo_files.*.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'photo_files.*.max' => 'Ukuran file foto maksimal 5 MB per foto (disarankan rasio 4:3, 1200x900).',
        ]);

        // Filter daftar poin bersih
        $cleanedFeatures = array_values(array_filter(
            array_map('trim', $request->input('features', [])),
            fn ($item) => $item !== ''
        ));

        if (empty($cleanedFeatures)) {
            return back()->withInput()->withErrors([
                'features' => 'Minimal 1 poin keunggulan fasilitas wajib diisi teksnya.',
            ]);
        }

        try {
            // Simpan file foto (1-3 foto)
            $uploadDir = public_path('uploads/facilities');
            FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Fasilitas');

            $photos = [];
            $photoFiles = $request->file('photo_files', []);
            $photoCaptions = $request->input('photo_captions', []);

            for ($i = 0; $i < 3; $i++) {
                if (isset($photoFiles[$i])) {
                    $file = $photoFiles[$i];
                    if (! $file->isValid()) {
                        $errCode = $file->getError();
                        $errMsg = FileUploadHelper::getErrorMessageForCode($errCode, 'Foto '.($i + 1));

                        return back()->withInput()->withErrors(['photo_files.'.$i => $errMsg]);
                    }

                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'facility_slide_'.time().'_'.$i.'_'.bin2hex(random_bytes(3)).'.'.$extension;
                    $file->move($uploadDir, $fileName);

                    $caption = trim($photoCaptions[$i] ?? '');
                    $photos[] = [
                        'image' => $fileName,
                        'caption' => $caption ?: $validated['title'],
                        'alt' => $validated['title'].' — '.($caption ?: 'Nucomu Cafe'),
                    ];
                }
            }

            if (empty($photos)) {
                return back()->withInput()->withErrors([
                    'photo_files' => 'Minimal 1 foto fasilitas wajib diunggah.',
                ]);
            }

            $sortOrder = $validated['sort_order'] ?? ((Facility::max('sort_order') ?? 0) + 1);
            $tabName = strtoupper(trim($validated['tab_name']));
            $badge = str_pad($sortOrder, 2, '0', STR_PAD_LEFT).' / '.$tabName;

            // Buat slug unik
            $baseSlug = Str::slug($tabName);
            $slug = $baseSlug ?: 'slide-'.time();
            $counter = 1;
            while (Facility::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            Facility::create([
                'slug' => $slug,
                'tab_name' => $tabName,
                'title' => $validated['title'],
                'label' => $validated['title'],
                'badge' => $badge,
                'subtitle' => $validated['subtitle'] ?: $validated['title'],
                'description' => $validated['description'],
                'features' => $cleanedFeatures,
                'photos' => $photos,
                'sort_order' => $sortOrder,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas "'.$tabName.'" berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan fasilitas: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified facility slide.
     */
    public function edit(Facility $facility): View
    {
        $facilityPhotoLimits = FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto fasilitas');

        return view('admin.facilities.edit', compact('facility', 'facilityPhotoLimits'));
    }

    /**
     * Update the specified facility slide in storage.
     */
    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate([
            'tab_name' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:3000'],
            'features' => ['required', 'array', 'min:1'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'photo_files' => ['nullable', 'array', 'max:3'],
            'photo_files.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'photo_captions' => ['nullable', 'array', 'max:3'],
            'photo_captions.*' => ['nullable', 'string', 'max:255'],
            'existing_photos' => ['nullable', 'array'],
            'remove_photos' => ['nullable', 'array'],
        ], [
            'tab_name.required' => 'Nama tombol pilihan (misal: Indoor, Outdoor, Musholla) wajib diisi.',
            'title.required' => 'Judul fasilitas wajib diisi.',
            'description.required' => 'Penjelasan singkat fasilitas wajib diisi.',
            'features.required' => 'Daftar keunggulan fasilitas wajib diisi minimal 1 poin.',
            'photo_files.*.uploaded' => 'File foto fasilitas gagal diunggah ke server.',
            'photo_files.*.mimes' => 'Format foto harus berupa JPG, PNG, atau WebP.',
            'photo_files.*.max' => 'Ukuran file foto maksimal 5 MB per foto (disarankan rasio 4:3, 1200x900).',
        ]);

        $cleanedFeatures = array_values(array_filter(
            array_map('trim', $request->input('features', [])),
            fn ($item) => $item !== ''
        ));

        if (empty($cleanedFeatures)) {
            return back()->withInput()->withErrors([
                'features' => 'Minimal 1 poin keunggulan fasilitas wajib diisi teksnya.',
            ]);
        }

        try {
            $uploadDir = public_path('uploads/facilities');
            FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Fasilitas');

            $photoFiles = $request->file('photo_files', []);
            $photoCaptions = $request->input('photo_captions', []);
            $existingPhotos = $request->input('existing_photos', []);
            $removePhotos = $request->input('remove_photos', []);

            // Hapus file foto yang ditandai hapus
            foreach ($removePhotos as $remIdx => $shouldRemove) {
                if ($shouldRemove && isset($existingPhotos[$remIdx])) {
                    $remPath = $existingPhotos[$remIdx];
                    if (! empty($remPath)) {
                        $targetFile = public_path('uploads/facilities/'.$remPath);
                        if (file_exists($targetFile)) {
                            @unlink($targetFile);
                        }
                    }
                    unset($existingPhotos[$remIdx]);
                }
            }

            $finalPhotos = [];

            for ($i = 0; $i < 3; $i++) {
                $caption = trim($photoCaptions[$i] ?? '');

                // Jika ada file baru diunggah untuk slot ini
                if (isset($photoFiles[$i])) {
                    $file = $photoFiles[$i];
                    if (! $file->isValid()) {
                        $errCode = $file->getError();
                        $errMsg = FileUploadHelper::getErrorMessageForCode($errCode, 'Foto '.($i + 1));

                        return back()->withInput()->withErrors(['photo_files.'.$i => $errMsg]);
                    }

                    // Hapus foto lama di slot ini jika ada
                    if (isset($existingPhotos[$i]) && ! empty($existingPhotos[$i])) {
                        $oldTarget = public_path('uploads/facilities/'.$existingPhotos[$i]);
                        if (file_exists($oldTarget)) {
                            @unlink($oldTarget);
                        }
                    }

                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'facility_slide_'.time().'_'.$i.'_'.bin2hex(random_bytes(3)).'.'.$extension;
                    $file->move($uploadDir, $fileName);

                    $finalPhotos[] = [
                        'image' => $fileName,
                        'caption' => $caption ?: $validated['title'],
                        'alt' => $validated['title'].' — '.($caption ?: 'Nucomu Cafe'),
                    ];
                } elseif (isset($existingPhotos[$i]) && ! empty($existingPhotos[$i])) {
                    // Pertahankan foto yang sudah ada dengan caption baru/saat ini
                    $finalPhotos[] = [
                        'image' => $existingPhotos[$i],
                        'caption' => $caption ?: $validated['title'],
                        'alt' => $validated['title'].' — '.($caption ?: 'Nucomu Cafe'),
                    ];
                }
            }

            if (empty($finalPhotos)) {
                return back()->withInput()->withErrors([
                    'photo_files' => 'Minimal 1 foto fasilitas wajib tersedia.',
                ]);
            }

            $sortOrder = $validated['sort_order'] ?? $facility->sort_order;
            $tabName = strtoupper(trim($validated['tab_name']));
            $badge = str_pad($sortOrder, 2, '0', STR_PAD_LEFT).' / '.$tabName;

            $facility->update([
                'tab_name' => $tabName,
                'title' => $validated['title'],
                'label' => $validated['title'],
                'badge' => $badge,
                'subtitle' => $validated['subtitle'] ?: $validated['title'],
                'description' => $validated['description'],
                'features' => $cleanedFeatures,
                'photos' => $finalPhotos,
                'sort_order' => $sortOrder,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas "'.$tabName.'" berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui fasilitas: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified facility slide from storage.
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        $name = $facility->tab_name ?: $facility->title;

        // Hapus semua file foto fisik milik slide ini
        $facility->deletePhotoFiles();

        $facility->delete();

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas "'.$name.'" beserta seluruh fotonya berhasil dihapus.');
    }

    /**
     * Toggle the active status of the facility slide.
     */
    public function toggleActive(Facility $facility): RedirectResponse
    {
        $facility->update([
            'is_active' => ! $facility->is_active,
        ]);

        $statusText = $facility->is_active ? 'ditampilkan di website' : 'disembunyikan dari website';

        return back()->with('success', 'Fasilitas "'.($facility->tab_name ?: $facility->title).'" berhasil '.$statusText.'.');
    }

    /**
     * Reorder facilities via drag-and-drop or move up/down buttons.
     */
    public function reorder(Request $request): JsonResponse|RedirectResponse
    {
        // 1. Dukungan format array IDs dari drag & drop: ['ids' => [8, 9, 4]]
        if ($request->has('ids') && is_array($request->input('ids'))) {
            $ids = $request->input('ids');
            foreach ($ids as $index => $id) {
                Facility::where('id', $id)->update(['sort_order' => $index + 1]);
            }
        }
        // 2. Dukungan tombol pindah naik / turun: ['facility_id' => 8, 'direction' => 'up'|'down']
        elseif ($request->has('facility_id') && $request->has('direction')) {
            $current = Facility::findOrFail($request->input('facility_id'));
            $direction = $request->input('direction');

            $all = Facility::orderBy('sort_order')->orderBy('id')->get();
            $currentIndex = $all->search(fn ($item) => $item->id === $current->id);

            if ($currentIndex !== false) {
                $targetIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;
                if ($targetIndex >= 0 && $targetIndex < $all->count()) {
                    $target = $all[$targetIndex];

                    $tempOrder = $current->sort_order;
                    $current->sort_order = $target->sort_order;
                    $target->sort_order = $tempOrder;

                    if ($current->sort_order === $target->sort_order) {
                        $current->sort_order = $targetIndex + 1;
                        $target->sort_order = $currentIndex + 1;
                    }

                    $current->save();
                    $target->save();
                }
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Urutan fasilitas berhasil diperbarui.',
            ]);
        }

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Urutan fasilitas berhasil diperbarui.');
    }
}
