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

    public function isZendo(): bool
    {
        return in_array($this->kind, ['zendo_tracking', 'zendo_chat'], true);
    }

    public function getServiceTypeLabelAttribute(): string
    {
        if ($this->isZendo()) {
            return 'Kurir Zendo';
        }

        return $this->order_type === 'dine_in' ? 'Makan di Sini' : 'Bawa Pulang';
    }

    public function canBeEditedAtKasir(): bool
    {
        return ! in_array($this->status, ['completed', 'cancelled'], true)
            && $this->payment_status !== 'paid';
    }

    public function getEffectiveSubtotalAttribute(): int
    {
        if ($this->relationLoaded('items') && $this->items->isNotEmpty()) {
            $itemsSum = (int) $this->items->sum('line_total');
            if ($itemsSum > 0) {
                return $itemsSum;
            }
        }

        return (int) $this->subtotal;
    }

    public function getEffectiveDeliveryFeeAttribute(): int
    {
        if ((int) ($this->delivery_fee ?? 0) > 0) {
            return (int) $this->delivery_fee;
        }

        if ($this->isZendo()) {
            return max(0, (int) $this->total - $this->effective_subtotal);
        }

        return 0;
    }

    public function getEffectiveDeliveryAddressAttribute(): ?string
    {
        $direct = trim((string) ($this->delivery_address ?? ''));
        if ($direct !== '') {
            return $direct;
        }

        if ($this->relationLoaded('zendoDelivery') && $this->zendoDelivery) {
            $zendoAddr = trim((string) ($this->zendoDelivery->delivery_address ?? ''));

            return $zendoAddr !== '' ? $zendoAddr : null;
        }

        return null;
    }

    public function getCafePayableAmountAttribute(): int
    {
        return $this->isZendo() ? $this->effective_subtotal : (int) $this->total;
    }

    public function getTotalItemQtyAttribute(): int
    {
        if ($this->relationLoaded('items')) {
            return (int) $this->items->sum('qty');
        }

        return (int) $this->items()->sum('qty');
    }

    public function getCashReceivedAmountAttribute(): int
    {
        $stored = data_get($this->form_data, 'cash_received');
        if (is_numeric($stored) && (int) $stored > 0) {
            return (int) $stored;
        }

        if ($this->payment_status === 'paid') {
            return $this->cafe_payable_amount;
        }

        return 0;
    }

    public function getChangeAmountAttribute(): int
    {
        $stored = data_get($this->form_data, 'change_amount');
        if (is_numeric($stored) && (int) $stored >= 0) {
            return (int) $stored;
        }

        if ($this->payment_status === 'paid' && strtolower((string) $this->payment_method) === 'cash') {
            return max(0, $this->cash_received_amount - $this->cafe_payable_amount);
        }

        return 0;
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
            'transfer' => 'Transfer',
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
