<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    public const TYPE_PERCENT = 'percent';

    public const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_subtotal',
        'max_uses',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_subtotal' => 'decimal:2',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function setCodeAttribute(?string $value): void
    {
        $this->attributes['code'] = $value === null ? null : strtoupper(trim($value));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isCurrentlyValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function discountForSubtotal(float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        if ($this->min_subtotal !== null && $subtotal < (float) $this->min_subtotal) {
            return 0.0;
        }

        $discount = $this->type === self::TYPE_PERCENT
            ? round($subtotal * ((float) $this->value / 100), 2)
            : round((float) $this->value, 2);

        return min($discount, $subtotal);
    }

    public function typeLabel(): string
    {
        return $this->type === self::TYPE_PERCENT
            ? rtrim(rtrim(number_format((float) $this->value, 2), '0'), '.') . '% off'
            : '£' . number_format((float) $this->value, 2) . ' off';
    }
}
