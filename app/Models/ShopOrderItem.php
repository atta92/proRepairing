<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopOrderItem extends Model
{
    public const FULFILMENT_STATUSES = [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'collected' => 'Collected',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'shop_order_id',
        'vendor_id',
        'vendor_name',
        'accessory_id',
        'accessory_variant_id',
        'name',
        'options_label',
        'type',
        'price',
        'quantity',
        'commission_rate',
        'commission_amount',
        'vendor_net',
        'fulfilment_status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'vendor_net' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class, 'shop_order_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function accessory(): BelongsTo
    {
        return $this->belongsTo(Accessory::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(AccessoryVariant::class, 'accessory_variant_id');
    }

    public function payoutItem(): HasOne
    {
        return $this->hasOne(VendorPayoutItem::class);
    }

    public function lineTotal(): float
    {
        return round((float) $this->price * (int) $this->quantity, 2);
    }

    public function isPayable(): bool
    {
        return $this->payoutItem === null
            && in_array($this->order?->status, ['paid', 'processing', 'shipped', 'collected'], true);
    }
}
