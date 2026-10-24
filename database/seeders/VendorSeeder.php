<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\SiteSetting;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $settings = SiteSetting::current();
        $name = $settings->displayName() ?: 'Own shop';

        $platform = Vendor::query()->updateOrCreate(
            ['is_platform' => true],
            [
                'name' => $name,
                'slug' => Vendor::uniqueSlug($name),
                'email' => $settings->contact_email ?: config('mail.from.address', 'shop@example.com'),
                'phone' => $settings->contact_phone,
                'description' => 'Official store.',
                'status' => 'approved',
                'commission_rate' => 0,
                'is_platform' => true,
                'sort_order' => 0,
                'country' => 'GB',
            ]
        );

        Accessory::query()
            ->whereNull('vendor_id')
            ->update(['vendor_id' => $platform->id]);
    }
}
