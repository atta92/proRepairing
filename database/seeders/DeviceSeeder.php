<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Device;
use App\Models\DeviceRepair;
use App\Models\Repair;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $catalogue = [
            'iphone-repairs' => [
                ['name' => 'iPhone 16 Pro Max', 'prices' => [299, 109, 99, 99, 89]],
                ['name' => 'iPhone 16 Pro', 'prices' => [239, 99, 99, 99, 89]],
                ['name' => 'iPhone 16', 'prices' => [179, 99, 99, 95, 89]],
                ['name' => 'iPhone 16 Plus', 'prices' => [189, 99, 89, 95, 89]],
                ['name' => 'iPhone 15 Pro Max', 'prices' => [219, 85, 79, 79, 59]],
                ['name' => 'iPhone 15 Pro', 'prices' => [185, 85, 69, 75, 59]],
                ['name' => 'iPhone 15', 'prices' => [159, 79, 69, 75, 59]],
                ['name' => 'iPhone 14 Pro Max', 'prices' => [185, 79, 79, 69, 69]],
                ['name' => 'iPhone 14 Pro', 'prices' => [165, 75, 69, 69, 59]],
                ['name' => 'iPhone 14', 'prices' => [95, 75, 65, 65, 59]],
                ['name' => 'iPhone 13 Pro Max', 'prices' => [149, 69, 69, 65, 59]],
                ['name' => 'iPhone 13', 'prices' => [89, 65, 65, 59, 55]],
                ['name' => 'iPhone 12 Pro Max', 'prices' => [95, 65, 65, 65, 59]],
                ['name' => 'iPhone 12', 'prices' => [75, 65, 59, 55, 55]],
                ['name' => 'iPhone 11', 'prices' => [59, 59, 45, 55, 45]],
                ['name' => 'iPhone SE (3rd Gen)', 'prices' => [59, 59, 49, 55, 49]],
            ],
            'samsung-repairs' => [
                ['name' => 'Galaxy S25 Ultra', 'prices' => [299, 129, 99, 89, 79]],
                ['name' => 'Galaxy S25+', 'prices' => [249, 109, 89, 79, 69]],
                ['name' => 'Galaxy S25', 'prices' => [199, 99, 79, 69, 59]],
                ['name' => 'Galaxy S24 Ultra', 'prices' => [279, 119, 89, 79, 69]],
                ['name' => 'Galaxy S24', 'prices' => [169, 89, 69, 59, 55]],
                ['name' => 'Galaxy S23 Ultra', 'prices' => [249, 99, 79, 69, 59]],
                ['name' => 'Galaxy S23', 'prices' => [149, 79, 65, 55, 49]],
                ['name' => 'Galaxy A55', 'prices' => [119, 69, 55, 49, 45]],
                ['name' => 'Galaxy A35', 'prices' => [99, 59, 49, 45, 39]],
                ['name' => 'Galaxy Z Flip 6', 'prices' => [399, 149, 99, 89, 79]],
                ['name' => 'Galaxy Z Fold 6', 'prices' => [549, 179, 119, 99, 89]],
            ],
            'google-repairs' => [
                ['name' => 'Pixel 9 Pro XL', 'prices' => [249, 99, 89, 79, 69]],
                ['name' => 'Pixel 9 Pro', 'prices' => [219, 89, 79, 69, 59]],
                ['name' => 'Pixel 9', 'prices' => [179, 79, 69, 65, 55]],
                ['name' => 'Pixel 8 Pro', 'prices' => [199, 85, 75, 65, 55]],
                ['name' => 'Pixel 8', 'prices' => [149, 75, 65, 59, 49]],
                ['name' => 'Pixel 7a', 'prices' => [119, 65, 55, 49, 45]],
            ],
            'huawei-repairs' => [
                ['name' => 'Pura 70 Pro', 'prices' => [199, 89, 79, 69, 59]],
                ['name' => 'P60 Pro', 'prices' => [169, 79, 69, 59, 55]],
                ['name' => 'Mate 60 Pro', 'prices' => [189, 85, 75, 65, 59]],
                ['name' => 'Nova 12', 'prices' => [99, 55, 49, 45, 39]],
            ],
            'ipad-repairs' => [
                ['name' => 'iPad Pro 11" (M4)', 'prices' => [249, 0, 89, 99, 79]],
                ['name' => 'iPad Pro 13" (M4)', 'prices' => [299, 0, 99, 109, 89]],
                ['name' => 'iPad Air (M2)', 'prices' => [179, 0, 79, 89, 69]],
                ['name' => 'iPad 10th Gen', 'prices' => [129, 0, 69, 79, 59]],
                ['name' => 'iPad Pro 9.7', 'prices' => [99, 0, 55, 59, 49]],
            ],
            'samsung-tablet' => [
                ['name' => 'Galaxy Tab S9 Ultra', 'prices' => [249, 0, 89, 99, 79]],
                ['name' => 'Galaxy Tab S9', 'prices' => [179, 0, 69, 79, 59]],
                ['name' => 'Galaxy Tab A9+', 'prices' => [99, 0, 49, 55, 45]],
            ],
            'macbook-repairs' => [
                ['name' => 'MacBook Air M3', 'prices' => [349, 0, 129, 149, 99]],
                ['name' => 'MacBook Air M2', 'prices' => [329, 0, 119, 139, 89]],
                ['name' => 'MacBook Pro 14" M3', 'prices' => [449, 0, 149, 169, 119]],
                ['name' => 'MacBook Pro 16" M3', 'prices' => [499, 0, 159, 179, 129]],
            ],
            'apple-watch-repairs' => [
                ['name' => 'Apple Watch Ultra 2', 'prices' => [249, 0, 89, 99, 79]],
                ['name' => 'Apple Watch Series 10', 'prices' => [179, 0, 69, 79, 59]],
                ['name' => 'Apple Watch Series 9', 'prices' => [159, 0, 65, 75, 55]],
                ['name' => 'Apple Watch SE (2nd Gen)', 'prices' => [119, 0, 55, 65, 49]],
            ],
        ];

        $repairSlugs = [
            'front-screen',
            'back-glass',
            'camera-repair',
            'battery-replacement',
            'charging-repair',
        ];

        $repairs = Repair::query()->whereIn('slug', $repairSlugs)->get()->keyBy('slug');
        $diagnostic = Repair::query()->where('slug', 'diagnostic')->first();

        foreach ($catalogue as $categorySlug => $devices) {
            $category = Category::query()->where('slug', $categorySlug)->first();

            if (! $category) {
                continue;
            }

            foreach ($devices as $index => $deviceData) {
                $slug = Str::slug($deviceData['name']) . '-repair';

                $device = Device::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'category_id' => $category->id,
                        'name' => $deviceData['name'],
                        'excerpt' => "Repair services for {$deviceData['name']}.",
                        'content' => "Same-day {$deviceData['name']} repairs with a 12-month warranty. Guide prices include parts, labour and VAT.",
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );

                foreach ($repairSlugs as $priceIndex => $repairSlug) {
                    $price = $deviceData['prices'][$priceIndex] ?? 0;

                    if ($price <= 0 || ! $repairs->has($repairSlug)) {
                        continue;
                    }

                    DeviceRepair::query()->updateOrCreate(
                        [
                            'device_id' => $device->id,
                            'repair_id' => $repairs[$repairSlug]->id,
                        ],
                        [
                            'price' => $price,
                            'turnaround' => 'Same day',
                            'warranty_months' => 12,
                            'is_diagnostic' => false,
                            'is_active' => true,
                            'sort_order' => $priceIndex + 1,
                        ]
                    );
                }

                if ($diagnostic) {
                    DeviceRepair::query()->updateOrCreate(
                        [
                            'device_id' => $device->id,
                            'repair_id' => $diagnostic->id,
                        ],
                        [
                            'price' => 15,
                            'turnaround' => 'Quote on diagnosis',
                            'warranty_months' => 12,
                            'is_diagnostic' => true,
                            'is_active' => true,
                            'sort_order' => 99,
                        ]
                    );
                }
            }
        }
    }
}
