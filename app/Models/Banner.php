<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'media_type',
        'media_path',
        'layout_mode',
        'start_time',
        'end_time',
        'button_text',
        'button_link',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope for banners currently visible to customers on the website.
     */
    public function scopeActiveNow(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where('is_active', true)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now);
    }

    /**
     * Scope for banners that will expire within the next 30 minutes.
     */
    public function scopeExpiringSoon(Builder $query): Builder
    {
        $now = Carbon::now();
        $threshold = Carbon::now()->addMinutes(30);

        return $query->where('is_active', true)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where('end_time', '<=', $threshold);
    }

    /**
     * URL accessor for the uploaded banner media.
     */
    public function getMediaUrlAttribute(): string
    {
        if (empty($this->media_path)) {
            return asset('images/nucomu/nucomu_logo_brand.png');
        }

        if (str_starts_with($this->media_path, 'http://') || str_starts_with($this->media_path, 'https://')) {
            return $this->media_path;
        }

        if (file_exists(public_path('uploads/banners/'.$this->media_path))) {
            return asset('uploads/banners/'.$this->media_path);
        }

        if (file_exists(public_path($this->media_path))) {
            return asset($this->media_path);
        }

        return asset('storage/'.$this->media_path);
    }

    /**
     * Human-friendly label for layout mode.
     */
    public function getLayoutModeLabelAttribute(): string
    {
        return match ($this->layout_mode) {
            'overlay' => 'Teks di Atas Gambar',
            'split' => 'Terbagi',
            default => 'Poster',
        };
    }

    /**
     * Status of the banner: 'active', 'upcoming', 'expired', or 'inactive'.
     */
    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'inactive';
        }

        $now = Carbon::now();

        if ($now->lt($this->start_time)) {
            return 'upcoming';
        }

        if ($now->gt($this->end_time)) {
            return 'expired';
        }

        return 'active';
    }

    /**
     * Check if banner expires within 30 minutes.
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $remaining = Carbon::now()->diffInMinutes($this->end_time, false);

        return $remaining >= 0 && $remaining <= 30;
    }

    /**
     * Human-friendly Indonesian remaining time string.
     * Examples: 'Tersisa 4 hari', 'Tersisa 20 menit', 'Sudah berakhir 2 hari lalu'
     */
    public function getRemainingHumanAttribute(): string
    {
        $now = Carbon::now();

        if ($now->gt($this->end_time)) {
            $diffHours = (int) $this->end_time->diffInHours($now);
            $diffMinutes = (int) $this->end_time->diffInMinutes($now);

            if ($diffHours >= 24) {
                $diffDays = (int) round($diffHours / 24);

                return "Sudah berakhir {$diffDays} hari lalu";
            }
            if ($diffHours >= 1) {
                return "Sudah berakhir {$diffHours} jam lalu";
            }

            return "Sudah berakhir {$diffMinutes} menit lalu";
        }

        if ($now->lt($this->start_time)) {
            $diffHours = (int) $now->diffInHours($this->start_time);
            $diffMinutes = (int) $now->diffInMinutes($this->start_time);

            if ($diffHours >= 24) {
                $diffDays = (int) round($diffHours / 24);

                return "Akan tampil dalam {$diffDays} hari";
            }
            if ($diffHours >= 1) {
                return "Akan tampil dalam {$diffHours} jam";
            }

            return "Akan tampil dalam {$diffMinutes} menit";
        }

        $diffHours = (int) $now->diffInHours($this->end_time);
        $diffMinutes = (int) $now->diffInMinutes($this->end_time);

        if ($diffHours >= 24) {
            $diffDays = (int) round($diffHours / 24);

            return "Tersisa {$diffDays} hari";
        }
        if ($diffHours >= 1) {
            return "Tersisa {$diffHours} jam";
        }

        return "Tersisa {$diffMinutes} menit";
    }

    /**
     * Friendly schedule string.
     * Example: 'Tampil 2 Nov, 03.06 sampai 7 Nov, 05.31'
     */
    public function getScheduleHumanAttribute(): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $startMonth = $months[(int) $this->start_time->format('n')] ?? $this->start_time->format('M');
        $endMonth = $months[(int) $this->end_time->format('n')] ?? $this->end_time->format('M');

        $startStr = $this->start_time->format('j ').$startMonth.', '.$this->start_time->format('H.i');
        $endStr = $this->end_time->format('j ').$endMonth.', '.$this->end_time->format('H.i');

        return "Tampil {$startStr} sampai {$endStr}";
    }

    /**
     * Percentage of duration elapsed (0 to 100).
     */
    public function getProgressPercentageAttribute(): int
    {
        $now = Carbon::now();
        if ($now->lt($this->start_time)) {
            return 0;
        }
        if ($now->gt($this->end_time)) {
            return 100;
        }

        $totalSeconds = max(1, $this->end_time->timestamp - $this->start_time->timestamp);
        $elapsedSeconds = $now->timestamp - $this->start_time->timestamp;

        return (int) min(100, max(0, round(($elapsedSeconds / $totalSeconds) * 100)));
    }

    /**
     * Contextual progress text next to the progress bar.
     * Examples: 'Tersisa 4 hari', 'Mulai 5 Nov', 'Sudah berakhir 2 hari lalu'
     */
    public function getProgressLabelAttribute(): string
    {
        if ($this->status === 'upcoming') {
            $months = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
            ];
            $month = $months[(int) $this->start_time->format('n')] ?? $this->start_time->format('M');

            return "Mulai {$this->start_time->format('j')} {$month}";
        }

        if (! $this->is_active) {
            return 'Dinonaktifkan';
        }

        return $this->remaining_human;
    }
}
