<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Resolve the public URL of the category thumbnail if present.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (file_exists(public_path('uploads/categories/'.$this->image))) {
            return asset('uploads/categories/'.$this->image);
        }

        if (file_exists(public_path('storage/'.$this->image))) {
            return asset('storage/'.$this->image);
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('uploads/categories/'.$this->image);
    }

    /**
     * Delete physical category image file from disk if stored locally.
     */
    public function deleteImageFile(): void
    {
        if (empty($this->image)) {
            return;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return;
        }

        $uploadFile = public_path('uploads/categories/'.$this->image);
        if (file_exists($uploadFile)) {
            @unlink($uploadFile);
        }

        $storageFile = public_path('storage/'.$this->image);
        if (file_exists($storageFile)) {
            @unlink($storageFile);
        }
    }
}
