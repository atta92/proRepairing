<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repair extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function deviceRepairs(): HasMany
    {
        return $this->hasMany(DeviceRepair::class);
    }

    public function emoji(): string
    {
        $haystack = strtolower(trim(($this->slug ?? '').' '.($this->name ?? '')));

        $map = [
            'screen' => '📱',
            'display' => '📱',
            'lcd' => '📱',
            'oled' => '📱',
            'glass' => '🪞',
            'back' => '🪞',
            'camera' => '📷',
            'lens' => '📷',
            'battery' => '🔋',
            'charg' => '🔌',
            'port' => '🔌',
            'usb' => '🔌',
            'lightning' => '🔌',
            'water' => '💧',
            'liquid' => '💧',
            'speaker' => '🔊',
            'earpiece' => '🔊',
            'mic' => '🎤',
            'button' => '🔘',
            'home' => '🔘',
            'face id' => '🙂',
            'face-id' => '🙂',
            'touch id' => '👆',
            'touch-id' => '👆',
            'fingerprint' => '👆',
            'software' => '💻',
            'diagnostic' => '🔍',
            "can't find" => '🔍',
            'cant find' => '🔍',
            'quote' => '💬',
            'other' => '🔧',
        ];

        foreach ($map as $needle => $emoji) {
            if (str_contains($haystack, $needle)) {
                return $emoji;
            }
        }

        return '🔧';
    }
}
