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

    /**
     * Bootstrap Icons class for this repair type.
     */
    public function iconClass(): string
    {
        $haystack = strtolower(trim(($this->slug ?? '').' '.($this->name ?? '')));

        $map = [
            'screen' => 'bi-phone',
            'display' => 'bi-phone',
            'lcd' => 'bi-phone',
            'oled' => 'bi-phone',
            'glass' => 'bi-phone-flip',
            'back glass' => 'bi-phone-flip',
            'back' => 'bi-phone-flip',
            'camera' => 'bi-camera',
            'lens' => 'bi-camera',
            'battery' => 'bi-battery-charging',
            'charg' => 'bi-plug',
            'port' => 'bi-usb-plug',
            'usb' => 'bi-usb-plug',
            'lightning' => 'bi-lightning-charge',
            'water' => 'bi-droplet',
            'liquid' => 'bi-droplet',
            'speaker' => 'bi-volume-up',
            'earpiece' => 'bi-headphones',
            'mic' => 'bi-mic',
            'button' => 'bi-circle',
            'home' => 'bi-circle',
            'face id' => 'bi-person-bounding-box',
            'face-id' => 'bi-person-bounding-box',
            'touch id' => 'bi-hand-index',
            'touch-id' => 'bi-hand-index',
            'fingerprint' => 'bi-hand-index',
            'software' => 'bi-laptop',
            'data' => 'bi-device-hdd',
            'diagnostic' => 'bi-search',
            "can't find" => 'bi-search',
            'cant find' => 'bi-search',
            'quote' => 'bi-chat-quote',
            'board' => 'bi-cpu',
            'logic' => 'bi-cpu',
            'other' => 'bi-tools',
        ];

        foreach ($map as $needle => $icon) {
            if (str_contains($haystack, $needle)) {
                return $icon;
            }
        }

        return 'bi-tools';
    }

    /**
     * @deprecated Use iconClass() — kept for older callers.
     */
    public function emoji(): string
    {
        return '';
    }
}
