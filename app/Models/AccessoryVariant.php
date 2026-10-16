<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessoryVariant extends Model
{
    protected $fillable = [
        'accessory_id',
        'sku',
        'storage',
        'color',
        'condition',
        'unlock_status',
        'ram',
        'cpu',
        'screen_size',
        'price',
        'compare_at_price',
        'stock',
        'image_path',
        'sort_order',
        'is_active',
    ];

    public const CONDITIONS = [
        'Fair' => 'Fair',
        'Good' => 'Good',
        'Excellent' => 'Excellent',
        'Premium' => 'Premium',
        'New' => 'New',
    ];

    public const UNLOCK_STATUSES = [
        'Unlocked' => 'Unlocked',
        'Locked' => 'Locked',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'stock' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function accessory(): BelongsTo
    {
        return $this->belongsTo(Accessory::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isInStock(): bool
    {
        return $this->stock === null || $this->stock > 0;
    }

    public function optionParts(): array
    {
        return array_values(array_filter([
            $this->storage,
            $this->color,
            $this->condition,
            $this->unlock_status,
            $this->ram ? $this->ram.' RAM' : null,
            $this->cpu,
            $this->screen_size,
        ], fn ($value) => filled($value)));
    }

    public function optionsLabel(): string
    {
        return implode(' · ', $this->optionParts());
    }

    public function displayName(?Accessory $product = null): string
    {
        $product ??= $this->accessory;
        $base = $product?->name ?? 'Product';
        $options = $this->optionsLabel();

        return $options !== '' ? $base.' - '.$options : $base;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '£'.number_format((float) $this->price, 2);
    }

    /** @return array<string, mixed> */
    public function toPickerArray(): array
    {
        return [
            'id' => $this->id,
            'storage' => $this->storage,
            'color' => $this->color,
            'condition' => $this->condition,
            'unlock_status' => $this->unlock_status,
            'ram' => $this->ram,
            'cpu' => $this->cpu,
            'screen_size' => $this->screen_size,
            'price' => (float) $this->price,
            'price_formatted' => $this->formatted_price,
            'compare_at_price' => $this->compare_at_price !== null ? (float) $this->compare_at_price : null,
            'stock' => $this->stock,
            'in_stock' => $this->isInStock(),
            'image' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'label' => $this->optionsLabel(),
        ];
    }
}
