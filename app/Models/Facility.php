<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'tab_name',
        'title',
        'badge',
        'subtitle',
        'icon',
        'label',
        'image',
        'description',
        'features',
        'photos',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'photos' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Resolves an image path to a full public asset URL.
     */
    public static function resolvePhotoUrl(?string $path): string
    {
        if (empty($path)) {
            return asset('images/nucomu/nucomu_story_2.png');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (file_exists(public_path('uploads/facilities/'.$path))) {
            return asset('uploads/facilities/'.$path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('uploads/facilities/'.$path);
    }

    /**
     * Get the formatted list of photos with resolved URLs and safe defaults.
     *
     * @return array<int, array{src: string, image: string, caption: string, alt: string}>
     */
    public function getFormattedPhotosAttribute(): array
    {
        $rawPhotos = is_array($this->photos) ? $this->photos : [];

        if (empty($rawPhotos)) {
            if (! empty($this->image)) {
                $rawPhotos = [
                    [
                        'image' => $this->image,
                        'caption' => $this->title ?? $this->label ?? 'Nucomu Cafe',
                        'alt' => ($this->title ?? $this->label ?? 'Nucomu Cafe').' — Galeri Fasilitas',
                    ],
                ];
            } else {
                $rawPhotos = [
                    [
                        'image' => 'images/nucomu/nucomu_story_2.png',
                        'caption' => $this->title ?? 'Nucomu Space',
                        'alt' => ($this->title ?? 'Nucomu Space').' — Nucomu Cafe',
                    ],
                ];
            }
        }

        $formatted = [];
        foreach ($rawPhotos as $item) {
            $imgPath = is_array($item) ? ($item['image'] ?? '') : (string) $item;
            $caption = is_array($item) ? ($item['caption'] ?? '') : '';
            $alt = is_array($item) ? ($item['alt'] ?? '') : '';

            if (empty($alt)) {
                $alt = ($this->title ?? $this->tab_name ?? 'Fasilitas Nucomu').' — '.($caption ?: 'Nucomu Cafe');
            }

            $formatted[] = [
                'src' => self::resolvePhotoUrl($imgPath),
                'image' => $imgPath,
                'caption' => $caption ?: ($this->title ?? 'Nucomu Cafe'),
                'alt' => $alt,
            ];
        }

        return $formatted;
    }

    /**
     * URL for the primary photo.
     */
    public function getImageUrlAttribute(): string
    {
        $photos = $this->formatted_photos;

        return ! empty($photos) ? $photos[0]['src'] : asset('images/nucomu/nucomu_story_2.png');
    }

    /**
     * Display label fallback (tab_name or title).
     */
    public function getDisplayTitleAttribute(): string
    {
        return $this->title ?: ($this->label ?: ($this->tab_name ?: 'Fasilitas'));
    }

    /**
     * Delete uploaded physical photo files for this facility slide.
     */
    public function deletePhotoFiles(): void
    {
        $photos = is_array($this->photos) ? $this->photos : [];
        foreach ($photos as $item) {
            $imgPath = is_array($item) ? ($item['image'] ?? '') : (string) $item;
            if (! empty($imgPath)) {
                $targetFile = public_path('uploads/facilities/'.$imgPath);
                if (file_exists($targetFile)) {
                    @unlink($targetFile);
                }
            }
        }

        if (! empty($this->image)) {
            $targetFile = public_path('uploads/facilities/'.$this->image);
            if (file_exists($targetFile)) {
                @unlink($targetFile);
            }
        }
    }

    /**
     * Map icon names to proper FontAwesome classes (for backwards compatibility).
     */
    public function getIconClassAttribute(): string
    {
        return match ($this->icon) {
            'zap', 'colokan' => 'fa-solid fa-bolt',
            'wifi' => 'fa-solid fa-wifi',
            'bath', 'toilet', 'restroom' => 'fa-solid fa-restroom',
            'moon', 'mushola', 'mosque' => 'fa-solid fa-mosque',
            'laptop', 'wfc' => 'fa-solid fa-laptop',
            'coffee' => 'fa-solid fa-mug-saucer',
            'parking' => 'fa-solid fa-square-parking',
            default => str_starts_with($this->icon ?? '', 'fa-') ? $this->icon : 'fa-solid fa-'.($this->icon ?: 'circle-check'),
        };
    }
}
