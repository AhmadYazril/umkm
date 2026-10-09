<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'menu_name',
        'unit_price',
        'qty',
        'options',
        'line_total',
    ];

    protected $casts = [
        'unit_price' => 'integer',
        'qty' => 'integer',
        'line_total' => 'integer',
        'options' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function getFormattedLineTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->line_total, 0, ',', '.');
    }
}
