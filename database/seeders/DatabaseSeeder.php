<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@repairing.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $this->call([
            CategorySeeder::class,
            RepairSeeder::class,
            DeviceSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            QuoteSeeder::class,
            PostSeeder::class,
            AccessorySeeder::class,
        ]);
    }
}
