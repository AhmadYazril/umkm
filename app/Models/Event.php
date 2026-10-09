<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'image',
        'is_active',
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_active' => 'boolean',
    ];
}
