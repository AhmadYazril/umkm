<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'name',
        'price',
        'type',
        'is_sample',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_sample' => 'boolean',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price == 0) {
            return 'Gratis';
        }
        return '+Rp ' . number_format($this->price, 0, ',', '.');
    }
}
