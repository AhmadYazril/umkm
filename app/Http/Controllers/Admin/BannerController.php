<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\FileUploadHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    /**
     * Display a listing of the banners.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');

        $query = Banner::query()->orderBy('sort_order')->orderByDesc('id');

        $now = Carbon::now();

        if ($statusFilter === 'active') {
            $query->where('is_active', true)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now);
        } elseif ($statusFilter === 'expiring') {
            $threshold = Carbon::now()->addMinutes(30);
            $query->where('is_active', true)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now)
                ->where('end_time', '<=', $threshold);
        } elseif ($statusFilter === 'upcoming') {
            $query->where('is_active', true)
                ->where('start_time', '>', $now);
        } elseif ($statusFilter === 'expired') {
            $query->where('end_time', '<', $now);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $banners = $query->paginate(12)->withQueryString();

        // Metrics for summary cards
        $totalCount = Banner::count();
        $activeCount = Banner::activeNow()->count();
        $expiringCount = Banner::expiringSoon()->count();
        $expiredCount = Banner::where('end_time', '<', $now)->count();

        return view('admin.banners.index', compact(
            'banners',
            'statusFilter',
            'totalCount',
            'activeCount',
            'expiringCount',
            'expiredCount'
        ));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create(): View
    {
        $defaultStartTime = Carbon::now()->format('Y-m-d\TH:i');
        $defaultEndTime = Carbon::now()->addDays(7)->format('Y-m-d\TH:i');

        return view('admin.banners.create', compact('defaultStartTime', 'defaultEndTime'));
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $mediaType = $request->input('media_type', 'image');

        $mediaRule = $mediaType === 'video'
            ? ['required', 'file', 'mimes:mp4,webm,mov', 'max:20480'] // max 20MB
            : [
                'required',
                'file',
                'mimes:jpeg,png,jpg,webp,gif',
                'max:5120', // max 5MB
                'dimensions:min_width=1600',
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        $info = @getimagesize($value->getRealPath());
                        if ($info && $info[1] > 0) {
                            if ($info[0] < $info[1]) {
                                $fail('Foto banner harus berorientasi landscape (melebar), minimal lebar 1600 piksel.');
                            }
                        }
                    }
                },
            ];

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'layout_mode' => ['nullable', 'string', 'in:poster,overlay,split'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'media_type' => ['required', 'in:image,video'],
            'media_file' => $mediaRule,
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after_or_equal:start_time'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'title.required' => 'Judul banner wajib diisi.',
            'media_file.required' => 'File foto atau video banner wajib diunggah.',
            'media_file.uploaded' => 'File foto atau video banner gagal diunggah karena ukurannya melebihi batas server PHP (maksimal '.(ini_get('upload_max_filesize') ?: '2M').'). Perkecil ukuran file atau hubungi pengelola untuk menaikkan batas upload di php.ini.',
            'media_file.mimes' => $mediaType === 'video'
                ? 'Format video harus berupa MP4, WebM, atau MOV.'
                : 'Format gambar harus berupa JPG, PNG, atau WebP.',
            'media_file.max' => $mediaType === 'video'
                ? 'Ukuran video maksimal 20 MB.'
                : 'Ukuran foto maksimal 5 MB (disarankan di bawah 2 MB).',
            'media_file.dimensions' => 'Ukuran foto minimal lebar 1600 piksel (disarankan 1920×800 atau 1920×1080 piksel).',
            'end_time.after_or_equal' => 'Tanggal & jam selesai harus sama atau setelah tanggal mulai.',
        ]);

        if (isset($validated['subtitle'])) {
            $validated['subtitle'] = str_ireplace(['&bull;', '&amp;bull;'], '•', $validated['subtitle']);
        }
        if (isset($validated['title'])) {
            $validated['title'] = str_ireplace(['&bull;', '&amp;bull;'], '•', $validated['title']);
        }

        $uploadDir = public_path('uploads/banners');
        try {
            FileUploadHelper::ensureDirectoryWritable($uploadDir, 'Folder upload banner');

            $file = $request->file('media_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'banner_'.time().'_'.bin2hex(random_bytes(4)).'.'.$extension;
            $file->move($uploadDir, $fileName);
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['media_file' => 'Gagal menyimpan file banner ke server: '.$e->getMessage().'. Periksa kapasitas penyimpanan atau izin folder.']);
        }

        Banner::create([
            'title' => $validated['title'],
            'layout_mode' => $validated['layout_mode'] ?? 'poster',
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'media_type' => $validated['media_type'],
            'media_path' => $fileName,
            'start_time' => Carbon::parse($validated['start_time']),
            'end_time' => Carbon::parse($validated['end_time']),
            'button_text' => (! empty($validated['button_text'])) ? $validated['button_text'] : 'Pesan Sekarang',
            'button_link' => (! empty($validated['button_link'])) ? $validated['button_link'] : '#menu',
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diterbitkan dan dijadwalkan!');
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $mediaType = $request->input('media_type', $banner->media_type);

        $mediaRule = $mediaType === 'video'
            ? ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:20480']
            : [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,webp,gif',
                'max:5120',
                'dimensions:min_width=1600',
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        $info = @getimagesize($value->getRealPath());
                        if ($info && $info[1] > 0) {
                            if ($info[0] < $info[1]) {
                                $fail('Foto banner harus berorientasi landscape (melebar), minimal lebar 1600 piksel.');
                            }
                        }
                    }
                },
            ];

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'layout_mode' => ['nullable', 'string', 'in:poster,overlay,split'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'media_type' => ['required', 'in:image,video'],
            'media_file' => $mediaRule,
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after_or_equal:start_time'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'title.required' => 'Judul banner wajib diisi.',
            'media_file.uploaded' => 'File foto atau video banner gagal diunggah karena ukurannya melebihi batas server PHP (maksimal '.(ini_get('upload_max_filesize') ?: '2M').'). Perkecil ukuran file atau hubungi pengelola untuk menaikkan batas upload di php.ini.',
            'media_file.mimes' => $mediaType === 'video'
                ? 'Format video harus berupa MP4, WebM, atau MOV.'
                : 'Format gambar harus berupa JPG, PNG, atau WebP.',
            'media_file.max' => $mediaType === 'video'
                ? 'Ukuran video maksimal 20 MB.'
                : 'Ukuran foto maksimal 5 MB (disarankan di bawah 2 MB).',
            'media_file.dimensions' => 'Ukuran foto minimal lebar 1600 piksel (disarankan 1920×800 atau 1920×1080 piksel).',
            'end_time.after_or_equal' => 'Tanggal & jam selesai harus sama atau setelah tanggal mulai.',
        ]);

        if (isset($validated['subtitle'])) {
            $validated['subtitle'] = str_ireplace(['&bull;', '&amp;bull;'], '•', $validated['subtitle']);
        }
        if (isset($validated['title'])) {
            $validated['title'] = str_ireplace(['&bull;', '&amp;bull;'], '•', $validated['title']);
        }

        $fileName = $banner->media_path;

        if ($request->hasFile('media_file')) {
            $uploadDir = public_path('uploads/banners');
            try {
                FileUploadHelper::ensureDirectoryWritable($uploadDir, 'Folder upload banner');

                // Remove old uploaded file if it exists
                $oldPath = public_path('uploads/banners/'.$banner->media_path);
                if (! empty($banner->media_path) && file_exists($oldPath)) {
                    @unlink($oldPath);
                }

                $file = $request->file('media_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'banner_'.time().'_'.bin2hex(random_bytes(4)).'.'.$extension;
                $file->move($uploadDir, $fileName);
            } catch (\Throwable $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['media_file' => 'Gagal menyimpan perubahan file banner ke server: '.$e->getMessage().'. Periksa kapasitas penyimpanan atau izin folder.']);
            }
        }

        $banner->update([
            'title' => $validated['title'],
            'layout_mode' => $validated['layout_mode'] ?? $banner->layout_mode ?? 'poster',
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'media_type' => $validated['media_type'],
            'media_path' => $fileName,
            'start_time' => Carbon::parse($validated['start_time']),
            'end_time' => Carbon::parse($validated['end_time']),
            'button_text' => (! empty($validated['button_text'])) ? $validated['button_text'] : 'Pesan Sekarang',
            'button_link' => (! empty($validated['button_link'])) ? $validated['button_link'] : '#menu',
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Perubahan banner berhasil disimpan!');
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        $filePath = public_path('uploads/banners/'.$banner->media_path);
        if (! empty($banner->media_path) && file_exists($filePath)) {
            @unlink($filePath);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }

    /**
     * Extend banner duration (Fast action for 30-minute expiry notification).
     */
    public function extendDuration(Request $request, Banner $banner): JsonResponse|RedirectResponse
    {
        $addition = $request->input('extend_by', '1_hour');
        $customEndTime = $request->input('new_end_time');

        $baseTime = $banner->end_time && $banner->end_time->isFuture()
            ? $banner->end_time
            : Carbon::now();

        if (! empty($customEndTime)) {
            $newEndTime = Carbon::parse($customEndTime);
        } else {
            $newEndTime = match ($addition) {
                '30_minutes' => $baseTime->copy()->addMinutes(30),
                '1_hour' => $baseTime->copy()->addHour(),
                '6_hours' => $baseTime->copy()->addHours(6),
                '1_day' => $baseTime->copy()->addDay(),
                '3_days' => $baseTime->copy()->addDays(3),
                '7_days' => $baseTime->copy()->addDays(7),
                default => $baseTime->copy()->addHour(),
            };
        }

        $banner->update([
            'end_time' => $newEndTime,
            'is_active' => true,
        ]);

        $message = 'Durasi banner "'.$banner->title.'" berhasil diperpanjang hingga '.$newEndTime->translatedFormat('d M Y, H:i').' WIB.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'new_end_time' => $newEndTime->toIso8601String(),
                'remaining_human' => $banner->remaining_human,
                'is_expiring_soon' => $banner->is_expiring_soon,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Banner $banner): RedirectResponse
    {
        $banner->update([
            'is_active' => ! $banner->is_active,
        ]);

        $statusText = $banner->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', 'Banner berhasil '.$statusText.'.');
    }

    /**
     * API for in-app 30-minute expiration check (polled by admin panel).
     */
    public function checkExpiring(): JsonResponse
    {
        $expiringBanners = Banner::expiringSoon()
            ->orderBy('end_time')
            ->get()
            ->map(function (Banner $banner) {
                $diffMinutes = max(0, round(Carbon::now()->diffInMinutes($banner->end_time, false)));

                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'media_url' => $banner->media_url,
                    'remaining_minutes' => $diffMinutes,
                    'remaining_human' => $diffMinutes.' menit lagi',
                    'end_time_formatted' => $banner->end_time->translatedFormat('H:i').' WIB',
                    'extend_url' => route('admin.banners.extend', $banner),
                    'edit_url' => route('admin.banners.edit', $banner),
                ];
            });

        return response()->json([
            'count' => $expiringBanners->count(),
            'banners' => $expiringBanners,
        ]);
    }

    /**
     * Preview design options (A, B, C) for visual selection by admin.
     */
    public function previewDesign(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $now = Carbon::now();

        $query = Banner::query()->orderBy('sort_order')->orderByDesc('id');

        if ($statusFilter === 'active') {
            $query->where('is_active', true)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now);
        } elseif ($statusFilter === 'expiring') {
            $threshold = Carbon::now()->addMinutes(30);
            $query->where('is_active', true)
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now)
                ->where('end_time', '<=', $threshold);
        } elseif ($statusFilter === 'upcoming') {
            $query->where('is_active', true)
                ->where('start_time', '>', $now);
        } elseif ($statusFilter === 'expired') {
            $query->where('end_time', '<', $now);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $banners = $query->get();

        $totalCount = Banner::count();
        $activeCount = Banner::activeNow()->count();
        $expiringCount = Banner::expiringSoon()->count();
        $expiredCount = Banner::where('end_time', '<', $now)->count();

        return view('admin.preview.index', compact(
            'banners',
            'statusFilter',
            'totalCount',
            'activeCount',
            'expiringCount',
            'expiredCount'
        ));
    }
}
