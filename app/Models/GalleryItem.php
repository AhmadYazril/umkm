<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'caption',
        'category',
        'sort_order',
        'author',
        'time_label',
        'category_badge',
        'avatar_url',
        'description',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
