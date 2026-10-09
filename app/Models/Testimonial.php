<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'rating',
        'is_sample',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_sample' => 'boolean',
        'is_active' => 'boolean',
    ];
}
