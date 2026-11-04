<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'handle',
        'order_tag',
        'avatar_url',
        'review_image',
        'content',
        'rating',
        'source',
        'is_sample',
        'is_active',
        'is_pinned',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_sample' => 'boolean',
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    /**
     * Scope a query to only include pinned testimonials.
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope a query to only include active testimonials.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Delete physical review image file from disk if uploaded.
     */
    public function deleteReviewImageFile(): void
    {
        if (empty($this->review_image)) {
            return;
        }

        // Do not delete stock images or external URLs
        if (str_starts_with($this->review_image, 'http://') || str_starts_with($this->review_image, 'https://')
            || str_starts_with($this->review_image, 'images/')) {
            return;
        }

        $directPath = public_path($this->review_image);
        if (file_exists($directPath) && is_file($directPath)) {
            @unlink($directPath);
        }

        $uploadFile = public_path('uploads/reviews/'.basename($this->review_image));
        if (file_exists($uploadFile) && is_file($uploadFile)) {
            @unlink($uploadFile);
        }
    }
}
