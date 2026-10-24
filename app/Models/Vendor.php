<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Vendor extends Model
{
    public const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'suspended' => 'Suspended',
        'rejected' => 'Rejected',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'email',
        'phone',
        'description',
        'logo_path',
        'banner_path',
        'status',
        'commission_rate',
        'is_platform',
        'sort_order',
        'payout_details',
        'address_line_1',
        'address_line_2',
        'city',
        'postcode',
        'country',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'is_platform' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Vendor $vendor): void {
            if ($vendor->is_platform) {
                $vendor->commission_rate = 0;
                $vendor->status = 'approved';
            }

            if (blank($vendor->slug) && filled($vendor->name)) {
                $vendor->slug = static::uniqueSlug((string) $vendor->name, $vendor->id);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'shop';
        $slug = $base;
        $i = 1;

        while (
            static::query()
                ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public static function platform(): ?self
    {
        return static::query()->where('is_platform', true)->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(Accessory::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(ShopOrderItem::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(VendorPayout::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeActiveOnStorefront(Builder $query): Builder
    {
        return $query->approved();
    }

    public function scopeOrderedForStorefront(Builder $query): Builder
    {
        return $query
            ->orderByDesc('is_platform')
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function logoUrl(): ?string
    {
        if (! filled($this->logo_path)) {
            return null;
        }

        return '/storage/'.ltrim(str_replace('\\', '/', (string) $this->logo_path), '/');
    }

    public function effectiveCommissionRate(): float
    {
        if ($this->is_platform) {
            return 0.0;
        }

        return (float) $this->commission_rate;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
