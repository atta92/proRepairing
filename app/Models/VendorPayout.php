<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorPayout extends Model
{
    public const STATUSES = [
        'due' => 'Due',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'vendor_id',
        'amount',
        'currency',
        'period_from',
        'period_to',
        'status',
        'paid_at',
        'reference',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'period_from' => 'date',
            'period_to' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VendorPayoutItem::class);
    }
}
