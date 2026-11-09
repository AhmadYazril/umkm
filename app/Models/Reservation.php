<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'date',
        'time',
        'guests',
        'notes',
        'form_data',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'guests' => 'integer',
        'form_data' => 'array',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Delete any uploaded files attached to this reservation.
     */
    public function deleteUploadedFiles(): void
    {
        if (is_array($this->form_data)) {
            foreach ($this->form_data as $field) {
                if (is_array($field) && ($field['type'] ?? '') === 'photo') {
                    $rawPath = (string) ($field['path'] ?? $field['value'] ?? '');
                    if ($rawPath !== '') {
                        if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
                            $parsed = parse_url($rawPath, PHP_URL_PATH);
                            $rawPath = ltrim((string) $parsed, '/');
                        }
                        $fullPath = public_path($rawPath);
                        if (file_exists($fullPath) && is_file($fullPath)) {
                            @unlink($fullPath);
                        }
                    }
                }
            }
        }
    }
}
