<?php

namespace Database\Seeders;

use App\Models\Repair;
use Illuminate\Database\Seeder;

class RepairSeeder extends Seeder
{
    public function run(): void
    {
        $repairs = [
            [
                'name' => 'Front screen',
                'slug' => 'front-screen',
                'description' => 'Cracked or unresponsive display assembly replacement.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Back Glass',
                'slug' => 'back-glass',
                'description' => 'Rear glass replacement while preserving cameras and wireless charging.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Camera repair',
                'slug' => 'camera-repair',
                'description' => 'Rear or front camera module repair / replacement.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Battery Replacement',
                'slug' => 'battery-replacement',
                'description' => 'New battery cell for full-day charge life.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Charging Repair',
                'slug' => 'charging-repair',
                'description' => 'Charging port clean, test and replacement.',
                'sort_order' => 5,
            ],
            [
                'name' => "Can't Find Your Repair?",
                'slug' => 'diagnostic',
                'description' => 'Diagnostic and quote for uncommon faults.',
                'sort_order' => 99,
            ],
        ];

        foreach ($repairs as $repair) {
            Repair::query()->updateOrCreate(
                ['slug' => $repair['slug']],
                array_merge($repair, ['is_active' => true])
            );
        }
    }
}
