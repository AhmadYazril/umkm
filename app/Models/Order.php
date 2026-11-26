<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Order extends Model
{
    use HasFactory;

    public const CHANNEL_DIRECT = 'direct';

    public const CHANNEL_ZENDO = 'zendo';

    public const CHANNEL_GRAB = 'grab';

    public const BARCODE_QR = 'qr';

    public const BARCODE_LINEAR = 'barcode';

    protected $fillable = [
        'code',
        'customer_name',
        'customer_phone',
        'order_type',
        'order_channel',
        'barcode_type',
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

    /**
     * Ensure channel and barcode columns exist on older local databases that have not yet run migrations.
     */
    public static function ensureChannelColumnsExist(): bool
    {
        try {
            if (! Schema::hasTable('orders')) {
                return false;
            }

            $hasChannel = Schema::hasColumn('orders', 'order_channel');
            $hasBarcode = Schema::hasColumn('orders', 'barcode_type');

            if (! $hasChannel || ! $hasBarcode) {
                Schema::table('orders', function (Blueprint $table) use ($hasChannel, $hasBarcode): void {
                    if (! $hasChannel) {
                        $table->string('order_channel', 30)->default(self::CHANNEL_DIRECT)->index()->after('order_type');
                    }
                    if (! $hasBarcode) {
                        $table->string('barcode_type', 20)->nullable()->after('order_channel');
                    }
                });
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

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
     * Scope query to a specific transaction channel: 'direct', 'zendo', or 'grab'.
     */
    #[Scope]
    protected function forChannel(Builder $query, string $channel): Builder
    {
        $hasChannelCol = Schema::hasColumn($this->getTable(), 'order_channel');

        if ($channel === self::CHANNEL_GRAB) {
            return $query->where(function (Builder $q) use ($hasChannelCol): void {
                if ($hasChannelCol) {
                    $q->where('order_channel', self::CHANNEL_GRAB)
                        ->orWhere('notes', 'like', '%[GRAB%')
                        ->orWhere('form_data', 'like', '%"channel":"grab"%');
                } else {
                    $q->where('notes', 'like', '%[GRAB%')
                        ->orWhere('form_data', 'like', '%"channel":"grab"%');
                }
            });
        }

        if ($channel === self::CHANNEL_ZENDO) {
            return $query->where(function (Builder $q) use ($hasChannelCol): void {
                if ($hasChannelCol) {
                    $q->where('order_channel', self::CHANNEL_ZENDO)
                        ->orWhere('notes', 'like', '%[KURIR ZENDO]%')
                        ->orWhereNotNull('delivery_address')
                        ->orWhere('form_data', 'like', '%"channel":"zendo"%');
                } else {
                    $q->where('notes', 'like', '%[KURIR ZENDO]%')
                        ->orWhereNotNull('delivery_address')
                        ->orWhere('form_data', 'like', '%"channel":"zendo"%');
                }
            })->where(function (Builder $q) use ($hasChannelCol): void {
                if ($hasChannelCol) {
                    $q->whereNull('order_channel')
                        ->orWhere('order_channel', '!=', self::CHANNEL_GRAB);
                } else {
                    $q->whereNull('notes')
                        ->orWhere('notes', 'not like', '%[GRAB%');
                }
            });
        }

        // Default: direct (Di Tempat / Barcode)
        return $query->where(function (Builder $q) use ($hasChannelCol): void {
            if ($hasChannelCol) {
                $q->whereNull('order_channel')
                    ->orWhere('order_channel', self::CHANNEL_DIRECT);
            } else {
                $q->whereNotNull('id');
            }
        })->whereNull('delivery_address')
            ->where(function (Builder $n): void {
                $n->whereNull('notes')
                    ->orWhere(function (Builder $n2): void {
                        $n2->where('notes', 'not like', '%[KURIR ZENDO]%')
                            ->where('notes', 'not like', '%[GRAB%');
                    });
            })
            ->where(function (Builder $f): void {
                $f->whereNull('form_data')
                    ->orWhere(function (Builder $f2): void {
                        $f2->where('form_data', 'not like', '%"channel":"zendo"%')
                            ->where('form_data', 'not like', '%"channel":"grab"%');
                    });
            });
    }

    /**
     * Resolve normalized transaction channel: 'direct', 'zendo', or 'grab'.
     */
    public function getChannelAttribute(): string
    {
        $rawChannel = strtolower(trim((string) ($this->attributes['order_channel'] ?? '')));
        if (in_array($rawChannel, [self::CHANNEL_GRAB, self::CHANNEL_ZENDO], true)) {
            return $rawChannel;
        }

        $formChannel = strtolower(trim((string) data_get($this->form_data, 'channel', '')));
        if (in_array($formChannel, [self::CHANNEL_GRAB, self::CHANNEL_ZENDO], true)) {
            return $formChannel;
        }

        $notes = (string) ($this->notes ?? '');
        if (str_contains(strtoupper($notes), '[GRAB')) {
            return self::CHANNEL_GRAB;
        }

        if (str_starts_with($notes, '[KURIR ZENDO]') || str_contains($notes, '[KURIR ZENDO]') || ! empty($this->delivery_address)) {
            return self::CHANNEL_ZENDO;
        }

        return self::CHANNEL_DIRECT;
    }

    public function getChannelLabelAttribute(): string
    {
        return match ($this->channel) {
            self::CHANNEL_ZENDO => 'Zendo',
            self::CHANNEL_GRAB => 'GrabFood',
            default => 'Di Tempat (Barcode)',
        };
    }

    public function getChannelLogoAttribute(): ?string
    {
        return match ($this->channel) {
            self::CHANNEL_ZENDO => asset('images/logos/zendo-courier.png'),
            self::CHANNEL_GRAB => asset('images/logos/grabfood.png'),
            default => null,
        };
    }

    /**
     * Only direct/on-site orders use QR/1D barcodes; Zendo & Grab transactions never use barcodes.
     */
    public function getUsesBarcodeAttribute(): bool
    {
        return $this->channel === self::CHANNEL_DIRECT;
    }

    public function getEffectiveBarcodeTypeAttribute(): ?string
    {
        if (! $this->uses_barcode) {
            return null;
        }

        $rawType = strtolower(trim((string) ($this->attributes['barcode_type'] ?? data_get($this->form_data, 'barcode_type', self::BARCODE_QR))));

        return $rawType === self::BARCODE_LINEAR ? self::BARCODE_LINEAR : self::BARCODE_QR;
    }

    /**
     * Determine granular transaction kind: 'dine_in', 'zendo_tracking', 'zendo_chat', 'zendo_kasir', or 'grab'.
     */
    public function getKindAttribute(): string
    {
        $channel = $this->channel;

        if ($channel === self::CHANNEL_GRAB) {
            return 'grab';
        }

        if ($channel !== self::CHANNEL_ZENDO) {
            return 'dine_in';
        }

        $notes = (string) ($this->notes ?? '');
        $hasTrackingMarkers = str_contains($notes, 'Pin:')
            || str_contains($notes, 'Tahap_Zendo:')
            || ! empty(data_get($this->form_data, 'customer_lat'))
            || ($this->relationLoaded('zendoDelivery') ? ($this->zendoDelivery !== null) : $this->zendoDelivery()->exists());

        if ($hasTrackingMarkers) {
            return 'zendo_tracking';
        }

        if (data_get($this->form_data, 'source') === 'kasir' || str_contains($notes, '[KASIR ZENDO]')) {
            return 'zendo_kasir';
        }

        return 'zendo_chat';
    }

    public function getKindLabelAttribute(): string
    {
        return match ($this->kind) {
            'grab' => 'GrabFood (Kasir)',
            'zendo_tracking' => 'Antar Zendo (Pelacakan)',
            'zendo_kasir' => 'Zendo (Kasir)',
            'zendo_chat' => 'Chat Zendo (WA)',
            default => 'Di Tempat (Barcode)',
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
