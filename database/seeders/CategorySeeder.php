<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Apple iPhone',
                'slug' => 'iphone-repairs',
                'type' => 'phone',
                'excerpt' => 'All model screens, batteries, back glass',
                'sort_order' => 1,
            ],
            [
                'name' => 'Samsung Galaxy',
                'slug' => 'samsung-repairs',
                'type' => 'phone',
                'excerpt' => 'Galaxy S · A · Note · Z Fold & Flip',
                'sort_order' => 2,
            ],
            [
                'name' => 'Google Pixel',
                'slug' => 'google-repairs',
                'type' => 'phone',
                'excerpt' => 'Pixel screens, batteries, cameras',
                'sort_order' => 3,
            ],
            [
                'name' => 'Huawei',
                'slug' => 'huawei-repairs',
                'type' => 'phone',
                'excerpt' => 'P series, Mate, Nova',
                'sort_order' => 4,
            ],
            [
                'name' => 'Apple iPad',
                'slug' => 'ipad-repairs',
                'type' => 'tablet',
                'excerpt' => 'Glass, LCD, battery, charging port',
                'sort_order' => 5,
            ],
            [
                'name' => 'Samsung Tablet',
                'slug' => 'samsung-tablet',
                'type' => 'tablet',
                'excerpt' => 'Galaxy Tab S, A and Active',
                'sort_order' => 6,
            ],
            [
                'name' => 'MacBook',
                'slug' => 'macbook-repairs',
                'type' => 'laptop',
                'excerpt' => 'Displays, keyboards, batteries',
                'sort_order' => 7,
            ],
            [
                'name' => 'Apple Watch',
                'slug' => 'apple-watch-repairs',
                'type' => 'watch',
                'excerpt' => 'SE · Ultra · Series models',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['is_active' => true])
            );
        }
    }
}
