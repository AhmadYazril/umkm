<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'caption',
        'category',
        'sort_order',
        'is_active',
        'author',
        'time_label',
        'category_badge',
        'avatar_url',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'title',
        'image_url',
    ];

    /**
     * Alias accessor for title -> caption
     */
    public function getTitleAttribute(): ?string
    {
        return $this->caption;
    }

    /**
     * Alias mutator for title -> caption
     */
    public function setTitleAttribute(?string $value): void
    {
        $this->attributes['caption'] = $value;
    }

    /**
     * Resolves the full URL to the gallery image with smart fallback.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Direct check in public/uploads/gallery
        if (file_exists(public_path('uploads/gallery/'.$this->image))) {
            return asset('uploads/gallery/'.$this->image);
        }

        // Check if $this->image already contains uploads/gallery/
        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        // Check storage link
        if (file_exists(public_path('storage/'.$this->image))) {
            return asset('storage/'.$this->image);
        }

        // Fallback for placeholder relative path or unsplash
        return 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80';
    }

    /**
     * Delete physical image file from disk safely.
     * Prevents deleting stock images in images/ or assets/.
     */
    public function deleteImageFile(): void
    {
        if (empty($this->image)) {
            return;
        }

        // Do not delete stock images or external URLs
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')
            || str_starts_with($this->image, 'images/') || str_starts_with($this->image, 'assets/')) {
            return;
        }

        $uploadFile = public_path('uploads/gallery/'.$this->image);
        if (file_exists($uploadFile) && is_file($uploadFile)) {
            @unlink($uploadFile);
        }

        if (file_exists(public_path($this->image)) && is_file(public_path($this->image))) {
            @unlink(public_path($this->image));
        }

        $storageFile = public_path('storage/'.$this->image);
        if (file_exists($storageFile) && is_file($storageFile)) {
            @unlink($storageFile);
        }

        try {
            Storage::disk('public')->delete($this->image);
        } catch (\Throwable $e) {
            // Ignore error if storage disk is not configured
        }
    }
}
