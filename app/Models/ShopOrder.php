<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopOrder extends Model
{
    protected $fillable = [
        'reference',
        'customer_id',
        'fulfilment',
        'payment_method',
        'coupon_id',
        'coupon_code',
        'subtotal',
        'shipping_amount',
        'discount_amount',
        'total',
        'status',
        'notes',
        'paid_at',
        'stripe_session_id',
        'stripe_payment_intent',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShopOrderItem::class);
    }

    /**
     * @return \Illuminate\Support\Collection<int|string, \Illuminate\Support\Collection<int, ShopOrderItem>>
     */
    public function itemsGroupedByVendor()
    {
        return $this->items->groupBy(fn (ShopOrderItem $item) => $item->vendor_id ?? 0);
    }
}
