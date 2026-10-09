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
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
