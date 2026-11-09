<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'is_available',
        'is_featured',
        'is_best_seller',
        'is_sample',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_sample' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(MenuOption::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    /**
     * Resolves the full URL to the menu image with smart fallback.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('images/nucomu-latte-hero.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (file_exists(public_path('uploads/menus/'.$this->image))) {
            return asset('uploads/menus/'.$this->image);
        }

        if (file_exists(public_path('storage/'.$this->image))) {
            return asset('storage/'.$this->image);
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('uploads/menus/'.$this->image);
    }

    /**
     * Delete physical image file from disk.
     */
    public function deleteImageFile(): void
    {
        if (empty($this->image)) {
            return;
        }

        $uploadFile = public_path('uploads/menus/'.$this->image);
        if (file_exists($uploadFile)) {
            @unlink($uploadFile);
        }

        $storageFile = public_path('storage/'.$this->image);
        if (file_exists($storageFile)) {
            @unlink($storageFile);
        }
    }
}
