<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPayoutItem extends Model
{
    protected $fillable = [
        'vendor_payout_id',
        'shop_order_item_id',
        'gross',
        'commission',
        'net',
    ];

    protected function casts(): array
    {
        return [
            'gross' => 'decimal:2',
            'commission' => 'decimal:2',
            'net' => 'decimal:2',
        ];
    }

    public function payout(): BelongsTo
    {
        return $this->belongsTo(VendorPayout::class, 'vendor_payout_id');
    }

    public function shopOrderItem(): BelongsTo
    {
        return $this->belongsTo(ShopOrderItem::class);
    }
}
