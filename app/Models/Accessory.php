<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'gallery_paths',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    public const TYPES = [
        'accessories' => 'Accessories',
        'mobiles' => 'Mobiles',
        'laptops' => 'Laptops',
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
            'gallery_paths' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Accessory $accessory): void {
            $paths = collect($accessory->gallery_paths ?? [])
                ->filter(fn ($path) => filled($path))
                ->values()
                ->all();

            $accessory->gallery_paths = $paths !== [] ? $paths : null;
            $accessory->image_path = $paths[0] ?? $accessory->image_path;
        });
    }

    /**
     * @return list<string>
     */
    public function galleryPaths(): array
    {
        $paths = collect($this->gallery_paths ?? [])
            ->filter(fn ($path) => filled($path))
            ->map(fn ($path) => ltrim(str_replace('\\', '/', (string) $path), '/'))
            ->values()
            ->all();

        if ($paths === [] && filled($this->image_path)) {
            return [ltrim(str_replace('\\', '/', (string) $this->image_path), '/')];
        }

        return $paths;
    }

    public function primaryImagePath(): ?string
    {
        return $this->galleryPaths()[0] ?? null;
    }

    /**
     * @return list<string>
     */
    public function galleryUrls(): array
    {
        return array_map(
            fn (string $path) => asset('storage/'.$path),
            $this->galleryPaths()
        );
    }

    public function primaryImageUrl(): ?string
    {
        $path = $this->primaryImagePath();

        return $path ? asset('storage/'.$path) : null;
    }

    public function variants(): HasMany
    {
        return $this->hasMany(AccessoryVariant::class)->orderBy('sort_order');
    }

    public function activeVariants(): HasMany
    {
        return $this->variants()->active();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->latest();
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->approved();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function averageRating(): ?float
    {
        $reviews = $this->relationLoaded('approvedReviews')
            ? $this->approvedReviews
            : $this->approvedReviews()->get();

        if ($reviews->isEmpty()) {
            return null;
        }

        return round((float) $reviews->avg('rating'), 1);
    }

    public function reviewsCount(): int
    {
        if ($this->relationLoaded('approvedReviews')) {
            return $this->approvedReviews->count();
        }

        return $this->approvedReviews()->count();
    }

    public function hasVariants(): bool
    {
        if ($this->relationLoaded('variants')) {
            return $this->variants->where('is_active', true)->isNotEmpty();
        }

        if ($this->relationLoaded('activeVariants')) {
            return $this->activeVariants->isNotEmpty();
        }

        return $this->activeVariants()->exists();
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst((string) $this->type);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst(str_replace('-', ' ', (string) $this->category));
    }

    public function sellingPrice(): float
    {
        $variants = $this->relationLoaded('activeVariants')
            ? $this->activeVariants
            : ($this->relationLoaded('variants')
                ? $this->variants->where('is_active', true)
                : null);

        if ($variants !== null && $variants->isNotEmpty()) {
            return (float) $variants->min('price');
        }

        if ($this->hasVariants()) {
            return (float) $this->activeVariants()->min('price');
        }

        return (float) $this->price;
    }

    public function getFormattedPriceAttribute(): string
    {
        $price = $this->sellingPrice();
        $prefix = $this->hasVariants() ? 'From ' : '';

        return $prefix.'£'.number_format($price, $price == floor($price) ? 0 : 2);
    }

    public function isInStock(): bool
    {
        if ($this->hasVariants()) {
            $variants = $this->relationLoaded('activeVariants')
                ? $this->activeVariants
                : ($this->relationLoaded('variants')
                    ? $this->variants->where('is_active', true)
                    : $this->activeVariants()->get());

            return $variants->contains(fn (AccessoryVariant $variant) => $variant->isInStock());
        }

        return $this->stock === null || $this->stock > 0;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
