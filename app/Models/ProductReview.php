<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'accessory_id',
        'author_name',
        'author_email',
        'rating',
        'title',
        'body',
        'image_path',
        'is_approved',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
            'is_verified' => 'boolean',
        ];
    }

    public function accessory(): BelongsTo
    {
        return $this->belongsTo(Accessory::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    /**
     * True when this email has a non-cancelled shop order containing the product.
     */
    public static function emailHasPurchasedProduct(?string $email, int $accessoryId): bool
    {
        $email = strtolower(trim((string) $email));

        if ($email === '' || $accessoryId <= 0) {
            return false;
        }

        return ShopOrderItem::query()
            ->where('accessory_id', $accessoryId)
            ->whereHas('order', function (Builder $query) use ($email) {
                $query
                    ->where('status', '!=', 'cancelled')
                    ->whereHas('customer', function (Builder $customerQuery) use ($email) {
                        $customerQuery->whereRaw('LOWER(email) = ?', [$email]);
                    });
            })
            ->exists();
    }
}
