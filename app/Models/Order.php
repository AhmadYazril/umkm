<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'customer_phone',
        'order_type',
        'table_id',
        'notes',
        'delivery_address',
        'delivery_fee',
        'form_data',
        'subtotal',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'pickup_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'delivery_fee' => 'integer',
        'total' => 'integer',
        'form_data' => 'array',
        'pickup_at' => 'datetime',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function zendoDelivery(): HasOne
    {
        return $this->hasOne(ZendoDelivery::class);
    }

    /**
     * Determine transaction kind: 'dine_in', 'zendo_tracking', or 'zendo_chat'.
     */
    public function getKindAttribute(): string
    {
        $notes = (string) ($this->notes ?? '');
        $isZendo = str_starts_with($notes, '[KURIR ZENDO]') || ! empty($this->delivery_address);

        if (! $isZendo) {
            return 'dine_in';
        }

        $hasTrackingMarkers = str_contains($notes, 'Pin:')
            || str_contains($notes, 'Tahap_Zendo:')
            || ! empty(data_get($this->form_data, 'customer_lat'))
            || ($this->relationLoaded('zendoDelivery') ? ($this->zendoDelivery !== null) : $this->zendoDelivery()->exists());

        return $hasTrackingMarkers ? 'zendo_tracking' : 'zendo_chat';
    }

    public function getKindLabelAttribute(): string
    {
        return match ($this->kind) {
            'zendo_tracking' => 'Antar Zendo (Pelacakan)',
            'zendo_chat' => 'Chat Zendo (WA)',
            default => 'Di Cafe',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'processing' => 'Diproses',
            'ready' => 'Siap Saji',
            'completed' => 'Selesai',
            'cancelled' => 'Batal',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match (strtolower((string) $this->payment_method)) {
            'qris' => 'QRIS',
            default => 'Tunai',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Lunas',
            default => 'Belum Bayar',
        };
    }
}
