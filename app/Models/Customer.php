<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'postcode',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Customer $customer): void {
            // Repair orders FK has no cascade; remove them (items cascade from orders).
            $customer->orders()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function shopOrders(): HasMany
    {
        return $this->hasMany(ShopOrder::class);
    }
}
