<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\FileUploadHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the About section management form.
     */
    public function index(): View
    {
        $settings = collect();
        try {
            $settings = Setting::pluck('value', 'key');
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        // Pre-filled defaults so admin never starts from a blank page
        $aboutData = [
            'about_label' => $settings['about_label'] ?? 'OUR STORY / TENTANG KAMI',
            'cafe_story_headline' => $settings['cafe_story_headline'] ?? 'Berawal dari Rasa, Kembali karena Rindu.',
            'cafe_story_p1' => $settings['cafe_story_p1'] ?? 'Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.',
            'cafe_story_p2' => $settings['cafe_story_p2'] ?? 'Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.',
            'about_quote' => $settings['about_quote'] ?? 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.',
            'about_button_text' => $settings['about_button_text'] ?? 'Jelajahi Pilihan Menu',
            'about_button_link' => $settings['about_button_link'] ?? '#menu',
            'about_media_type' => $settings['about_media_type'] ?? 'video',
            'about_media_path' => $settings['about_media_path'] ?? 'video/tiramisu.mp4',
        ];

        // Resolve absolute URL for media preview
        $aboutData['media_url'] = $this->resolveMediaUrl(
            $aboutData['about_media_path'],
            $aboutData['about_media_type']
        );

        $imageLimits = FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto Tentang Cafe');
        $videoLimits = FileUploadHelper::getEffectiveLimit(20 * 1024 * 1024, 'video Tentang Cafe');

        return view('admin.about.index', compact('aboutData', 'imageLimits', 'videoLimits'));
    }

    /**
     * Update the About section content and media.
     */
    public function update(Request $request): RedirectResponse
    {
        $mediaType = $request->input('media_type', 'video');

        $mediaRule = $mediaType === 'video'
            ? 'nullable|file|mimes:mp4,webm,mov|max:20480' // max 20MB
            : 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:5120'; // max 5MB

        $validated = $request->validate([
            'about_label' => ['required', 'string', 'max:100'],
            'cafe_story_headline' => ['required', 'string', 'max:255'],
            'cafe_story_p1' => ['required', 'string', 'max:1500'],
            'cafe_story_p2' => ['nullable', 'string', 'max:1500'],
            'about_quote' => ['nullable', 'string', 'max:500'],
            'about_button_text' => ['nullable', 'string', 'max:100'],
            'about_button_link' => ['nullable', 'string', 'max:255'],
            'media_type' => ['required', 'in:image,video'],
            'media_file' => $mediaRule,
        ], [
            'about_label.required' => 'Label kategori (sub-header) wajib diisi.',
            'cafe_story_headline.required' => 'Judul utama cerita cafe wajib diisi.',
            'cafe_story_p1.required' => 'Paragraf pertama cerita cafe wajib diisi.',
            'media_type.required' => 'Pilih tipe media yang digunakan (foto atau video).',
            'media_file.uploaded' => $mediaType === 'video'
                ? 'File video Tentang Cafe gagal diunggah ke server.'
                : 'File foto Tentang Cafe gagal diunggah ke server.',
            'media_file.mimes' => $mediaType === 'video'
                ? 'Format file video tidak didukung. Harap gunakan format MP4 atau WebM.'
                : 'Format file gambar tidak didukung. Harap gunakan format JPG, PNG, atau WebP.',
            'media_file.max' => $mediaType === 'video'
                ? 'Ukuran video melebihi batas. Maksimal ukuran video adalah 20 MB.'
                : 'Ukuran foto melebihi batas. Maksimal ukuran foto adalah 5 MB.',
        ]);

        try {
            // Text settings mapping
            $settingsToSave = [
                'about_label' => $validated['about_label'],
                'cafe_story_headline' => $validated['cafe_story_headline'],
                'cafe_story_p1' => $validated['cafe_story_p1'],
                'cafe_story_p2' => $validated['cafe_story_p2'] ?? '',
                'about_quote' => $validated['about_quote'] ?? '',
                'about_button_text' => $validated['about_button_text'] ?: 'Jelajahi Pilihan Menu',
                'about_button_link' => $validated['about_button_link'] ?: '#menu',
                'about_media_type' => $validated['media_type'],
            ];

            // Handle file upload if present
            if ($request->hasFile('media_file')) {
                $uploadDir = public_path('uploads/about');
                FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Tentang Cafe');

                // Remove previous uploaded file if exists in uploads/about
                $currentPath = Setting::where('key', 'about_media_path')->value('value');
                if ($currentPath && file_exists(public_path('uploads/about/'.$currentPath))) {
                    @unlink(public_path('uploads/about/'.$currentPath));
                }

                $file = $request->file('media_file');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'about_'.time().'_'.bin2hex(random_bytes(4)).'.'.$extension;
                $file->move($uploadDir, $fileName);

                $settingsToSave['about_media_path'] = $fileName;
            }

            // Save into settings table
            foreach ($settingsToSave as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            return redirect()->route('admin.about.index')
                ->with('success', 'Konten About / Tentang Cafe berhasil diperbarui dan disinkronkan ke website!');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan perubahan: '.$e->getMessage());
        }
    }

    /**
     * Resolve media URL for display.
     */
    protected function resolveMediaUrl(?string $path, string $type): string
    {
        if (empty($path)) {
            return $type === 'video'
                ? asset('videos/tiramisu.mp4')
                : asset('images/reviews/dya-3.jpg');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (file_exists(public_path('uploads/about/'.$path))) {
            return asset('uploads/about/'.$path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (file_exists(public_path('videos/'.basename($path)))) {
            return asset('videos/'.basename($path));
        }

        return asset($path);
    }
}
