<?php

namespace App\Models;

use Database\Factories\ZendoDeliveryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ZendoDelivery extends Model
{
    /** @use HasFactory<ZendoDeliveryFactory> */
    use HasFactory;

    /**
     * Delivery stages that still occupy a courier.
     *
     * @var list<string>
     */
    public const ACTIVE_STATUSES = ['assigned', 'picked_up', 'almost_done'];

    protected $fillable = [
        'order_id',
        'order_code',
        'courier_index',
        'courier_name',
        'courier_phone',
        'tracking_token',
        'courier_token',
        'status',
        'customer_name',
        'delivery_address',
        'destination_lat',
        'destination_lng',
        'courier_lat',
        'courier_lng',
        'courier_accuracy',
        'courier_heading',
        'courier_speed',
        'location_updated_at',
        'route_trail',
        'gps_suspicious_count',
        'last_gps_warning',
        'gps_locked_until',
        'courier_verified_at',
        'picked_up_at',
        'completed_at',
    ];

    protected $casts = [
        'courier_index' => 'integer',
        'destination_lat' => 'float',
        'destination_lng' => 'float',
        'courier_lat' => 'float',
        'courier_lng' => 'float',
        'courier_accuracy' => 'float',
        'courier_heading' => 'float',
        'courier_speed' => 'float',
        'route_trail' => 'array',
        'gps_suspicious_count' => 'integer',
        'location_updated_at' => 'datetime',
        'gps_locked_until' => 'datetime',
        'courier_verified_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Generate a unique, unguessable token for tracking / courier links.
     */
    public static function generateToken(): string
    {
        return Str::random(40);
    }

    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES, true);
    }

    public function isGpsLocked(): bool
    {
        return $this->gps_locked_until !== null && $this->gps_locked_until->isFuture();
    }

    public function hasDestinationPin(): bool
    {
        return $this->destination_lat !== null && $this->destination_lng !== null;
    }

    public function hasCourierLocation(): bool
    {
        return $this->courier_lat !== null && $this->courier_lng !== null;
    }

    /**
     * Remaining distance (meters) from the courier's last verified GPS position to the destination pin.
     */
    public function remainingDistanceMeters(): ?float
    {
        if (! $this->hasDestinationPin() || ! $this->hasCourierLocation()) {
            return null;
        }

        return self::distanceMeters($this->courier_lat, $this->courier_lng, $this->destination_lat, $this->destination_lng);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'assigned' => 'Menunggu Kurir Mengambil Pesanan',
            'picked_up' => 'Kurir Dalam Perjalanan',
            'almost_done' => 'Kurir Hampir Sampai',
            'completed' => 'Pesanan Telah Diantar',
            'cancelled' => 'Dibatalkan',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Great-circle distance between two coordinates using the Haversine formula.
     */
    public static function distanceMeters(float $latFrom, float $lngFrom, float $latTo, float $lngTo): float
    {
        $earthRadius = 6371000.0;
        $dLat = deg2rad($latTo - $latFrom);
        $dLng = deg2rad($lngTo - $lngFrom);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($latFrom)) * cos(deg2rad($latTo)) * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
