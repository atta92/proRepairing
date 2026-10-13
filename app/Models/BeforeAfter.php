<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BeforeAfter extends Model
{
    protected $fillable = [
        'title',
        'before_image_path',
        'after_image_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function beforeImageUrl(): ?string
    {
        return $this->publicStorageUrl($this->before_image_path);
    }

    public function afterImageUrl(): ?string
    {
        return $this->publicStorageUrl($this->after_image_path);
    }

    protected function publicStorageUrl(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        // Root-relative so images work even when APP_URL host/port differs.
        return '/storage/'.ltrim(str_replace('\\', '/', $path), '/');
    }
}
