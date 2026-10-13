<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    protected $fillable = [
        'name',
        'page_title',
        'slug',
        'type',
        'category',
        'excerpt',
        'description',
        'price',
        'compare_at_price',
        'stock',
        'image_path',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    public const TYPES = [
        'accessories' => 'Accessories',
        'mobiles' => 'Mobiles',
        'tablets' => 'Tablets',
        'wearables' => 'Wearables',
        'other' => 'Other',
    ];

    public const CATEGORIES = [
        'cases' => 'Cases & Covers',
        'chargers' => 'Chargers',
        'cables' => 'Cables',
        'screen-protectors' => 'Screen Protectors',
        'audio' => 'Audio',
        'apple' => 'Apple',
        'samsung' => 'Samsung',
        'google' => 'Google',
        'refurbished' => 'Refurbished',
        'other' => 'Other',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'stock' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst((string) $this->type);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst(str_replace('-', ' ', (string) $this->category));
    }

    public function getFormattedPriceAttribute(): string
    {
        return '£' . number_format((float) $this->price, 2);
    }

    public function isInStock(): bool
    {
        return $this->stock === null || $this->stock > 0;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
