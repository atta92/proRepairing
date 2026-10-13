<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccessorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Clear MagSafe Case — iPhone 15',
                'type' => 'accessories',
                'category' => 'cases',
                'excerpt' => 'Slim crystal-clear case with MagSafe magnets and raised camera lip.',
                'description' => "Protect your iPhone without hiding its design. Scratch-resistant clear shell, MagSafe compatible, and raised edges for screen and camera.",
                'price' => 19.99,
                'compare_at_price' => 24.99,
                'stock' => 25,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '20W USB-C Fast Charger',
                'type' => 'accessories',
                'category' => 'chargers',
                'excerpt' => 'Compact PD charger for iPhone, iPad and Android.',
                'description' => "Official-spec 20W USB-C Power Delivery wall charger. Ideal as a spare for home, work or travel.",
                'price' => 14.99,
                'compare_at_price' => null,
                'stock' => 40,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'USB-C to Lightning Cable (1m)',
                'type' => 'accessories',
                'category' => 'cables',
                'excerpt' => 'Braided 1m cable for charging and data sync.',
                'description' => "Durable braided USB-C to Lightning cable. Works with PD chargers for fast charging on supported iPhones.",
                'price' => 9.99,
                'compare_at_price' => 12.99,
                'stock' => 50,
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tempered Glass Screen Protector',
                'type' => 'accessories',
                'category' => 'screen-protectors',
                'excerpt' => '9H tempered glass — fitted in store free with purchase.',
                'description' => "Edge-to-edge tempered glass for popular iPhone and Samsung models.",
                'price' => 7.99,
                'compare_at_price' => null,
                'stock' => 60,
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'iPhone 13 128GB — Refurbished',
                'type' => 'mobiles',
                'category' => 'apple',
                'excerpt' => 'Grade A refurbished, unlocked, 12-month warranty.',
                'description' => "Fully tested refurbished iPhone 13 128GB. Battery health 85%+. Unlocked to any network. Includes charger cable.",
                'price' => 329.00,
                'compare_at_price' => 379.00,
                'stock' => 6,
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Samsung Galaxy S22 128GB — Refurbished',
                'type' => 'mobiles',
                'category' => 'samsung',
                'excerpt' => 'Unlocked Galaxy S22 with warranty and fresh battery check.',
                'description' => "Grade A refurbished Galaxy S22. Fully tested, unlocked, 12-month parts & labour warranty from us.",
                'price' => 299.00,
                'compare_at_price' => 349.00,
                'stock' => 4,
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Google Pixel 7 128GB — Refurbished',
                'type' => 'mobiles',
                'category' => 'google',
                'excerpt' => 'Clean Pixel 7, unlocked, ready for any UK network.',
                'description' => "Refurbished Pixel 7 with strong battery health and cosmetic Grade A finish.",
                'price' => 249.00,
                'compare_at_price' => null,
                'stock' => 5,
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'iPad 9th Gen 64GB Wi‑Fi — Refurbished',
                'type' => 'tablets',
                'category' => 'apple',
                'excerpt' => 'Everyday iPad for browsing, streaming and schoolwork.',
                'description' => "Refurbished iPad 9th generation 64GB Wi‑Fi. Tested and warranted for 12 months.",
                'price' => 219.00,
                'compare_at_price' => 259.00,
                'stock' => 3,
                'is_featured' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Apple Watch SE GPS 40mm — Refurbished',
                'type' => 'wearables',
                'category' => 'apple',
                'excerpt' => 'Fitness tracking, notifications and crash detection.',
                'description' => "Refurbished Apple Watch SE (GPS) 40mm. Includes sport band. 12-month warranty.",
                'price' => 149.00,
                'compare_at_price' => 179.00,
                'stock' => 4,
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'name' => '65W GaN Laptop Charger',
                'type' => 'accessories',
                'category' => 'chargers',
                'excerpt' => 'Compact GaN charger for MacBook, USB-C laptops and phones.',
                'description' => "Travel-friendly 65W GaN USB-C charger.",
                'price' => 34.99,
                'compare_at_price' => 39.99,
                'stock' => 15,
                'is_featured' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($items as $item) {
            Accessory::query()->updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'category' => $item['category'],
                    'excerpt' => $item['excerpt'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'compare_at_price' => $item['compare_at_price'],
                    'stock' => $item['stock'],
                    'is_featured' => $item['is_featured'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
