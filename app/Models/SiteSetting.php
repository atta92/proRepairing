<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'stripe_publishable_key',
        'stripe_secret_key',
        'site_name',
        'site_logo_path',
        'site_description',
        'contact_email',
        'contact_phone',
        'contact_hours',
        'contact_address',
        'contact_intro',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_youtube',
        'social_linkedin',
        'social_tiktok',
        'about_title',
        'about_body',
        'privacy_title',
        'privacy_body',
        'terms_title',
        'terms_body',
    ];

    public static function current(): self
    {
        return once(function (): self {
            $defaults = [
                'site_name' => config('app.name', 'Repairing'),
                'site_description' => 'Top-rated same-day repair services for phones, tablets and laptops - free mail-in from anywhere in the UK.',
                'contact_email' => 'info@example.com',
                'contact_phone' => '01234 567890',
                'contact_hours' => 'Mon-Sun · Free UK mail-in',
                'contact_intro' => 'Get in touch for repairs, quotes, or shop orders. Free mail-in from anywhere in the UK.',
                'about_title' => 'About us',
                'about_body' => '<p>We provide same-day phone, tablet and laptop repairs with published prices and a 12-month warranty.</p>',
                'privacy_title' => 'Privacy policy',
                'privacy_body' => '<p>Update this privacy policy from the admin panel.</p>',
                'terms_title' => 'Terms &amp; conditions',
                'terms_body' => '<p>Update these terms and conditions from the admin panel.</p>',
            ];

            $settings = static::query()->firstOrCreate([], $defaults);

            $dirty = false;
            foreach ($defaults as $key => $value) {
                if (blank($settings->{$key})) {
                    $settings->{$key} = $value;
                    $dirty = true;
                }
            }

            if ($dirty) {
                $settings->save();
            }

            return $settings;
        });
    }

    public function displayName(): string
    {
        return filled($this->site_name) ? (string) $this->site_name : (string) config('app.name');
    }

    public function logoUrl(): ?string
    {
        if (blank($this->site_logo_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->site_logo_path);
    }

    /**
     * @return list<array{label: string, url: string, icon: string}>
     */
    public function socialLinks(): array
    {
        $map = [
            'facebook' => ['label' => 'Facebook', 'icon' => 'bi-facebook', 'field' => 'social_facebook'],
            'instagram' => ['label' => 'Instagram', 'icon' => 'bi-instagram', 'field' => 'social_instagram'],
            'twitter' => ['label' => 'X / Twitter', 'icon' => 'bi-twitter-x', 'field' => 'social_twitter'],
            'youtube' => ['label' => 'YouTube', 'icon' => 'bi-youtube', 'field' => 'social_youtube'],
            'linkedin' => ['label' => 'LinkedIn', 'icon' => 'bi-linkedin', 'field' => 'social_linkedin'],
            'tiktok' => ['label' => 'TikTok', 'icon' => 'bi-tiktok', 'field' => 'social_tiktok'],
        ];

        $links = [];
        foreach ($map as $item) {
            $url = $this->{$item['field']} ?? null;
            if (filled($url)) {
                $links[] = [
                    'label' => $item['label'],
                    'url' => $url,
                    'icon' => $item['icon'],
                ];
            }
        }

        return $links;
    }

    public function setStripeSecretKeyAttribute(?string $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['stripe_secret_key'] = null;

            return;
        }

        $this->attributes['stripe_secret_key'] = Crypt::encryptString($value);
    }

    public function getStripeSecretKeyAttribute(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return $value;
        }
    }

    public function hasStripeConfigured(): bool
    {
        return filled($this->stripe_publishable_key) && filled($this->stripe_secret_key);
    }
}
