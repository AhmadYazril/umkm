<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'field_key',
        'type',
        'placeholder',
        'options',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to fetch active fields in ascending sort order.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
