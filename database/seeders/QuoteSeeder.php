<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Device;
use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        $iphone = Category::query()->where('slug', 'iphone-repairs')->first();
        $samsung = Category::query()->where('slug', 'samsung-repairs')->first();
        $device = Device::query()->where('slug', 'iphone-13-repair')->first();

        $quotes = [
            [
                'name' => 'Nancy Willett',
                'email' => 'nancy@example.com',
                'phone' => '07700 900010',
                'device_name' => 'iPhone 13 water damage',
                'category_id' => $iphone?->id,
                'device_id' => $device?->id,
                'message' => 'Phone got wet. Powers on but camera fog and charging is intermittent.',
                'status' => 'new',
            ],
            [
                'name' => 'Martin Hibbard',
                'email' => 'martin@example.com',
                'phone' => '07700 900011',
                'device_name' => 'Galaxy Fold 4',
                'category_id' => $samsung?->id,
                'device_id' => null,
                'message' => 'Inner screen has lines. Can you repair Fold 4 hinge area too?',
                'status' => 'contacted',
            ],
            [
                'name' => 'Dave Tanner',
                'email' => 'dave@example.com',
                'phone' => '07700 900012',
                'device_name' => 'Old Huawei Mate that won\'t boot',
                'category_id' => null,
                'device_id' => null,
                'message' => 'No power after drop. Looking for board-level diagnosis quote.',
                'status' => 'quoted',
            ],
        ];

        foreach ($quotes as $quote) {
            Quote::query()->updateOrCreate(
                [
                    'email' => $quote['email'],
                    'device_name' => $quote['device_name'],
                ],
                $quote
            );
        }
    }
}
