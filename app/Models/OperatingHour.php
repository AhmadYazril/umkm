<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'day_name',
        'open_time',
        'close_time',
        'is_closed',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_closed' => 'boolean',
    ];

    public function getScheduleTextAttribute(): string
    {
        if ($this->is_closed) {
            return 'LIBUR';
        }
        return substr($this->open_time, 0, 5) . ' – ' . substr($this->close_time, 0, 5);
    }
}
