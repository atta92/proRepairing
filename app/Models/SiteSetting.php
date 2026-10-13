<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SiteSetting extends Model
{
    protected $fillable = [
        'stripe_publishable_key',
        'stripe_secret_key',
        'contact_email',
        'contact_phone',
        'contact_hours',
        'contact_address',
        'contact_intro',
        'about_title',
        'about_body',
        'privacy_title',
        'privacy_body',
        'terms_title',
        'terms_body',
    ];

    public static function current(): self
    {
        $defaults = [
            'contact_email' => 'info@example.com',
            'contact_phone' => '01234 567890',
            'contact_hours' => 'Mon–Sun · Walk-ins welcome',
            'contact_intro' => 'Get in touch for repairs, quotes, or shop orders. Walk-ins welcome.',
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
