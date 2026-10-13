<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Timotheus Valaar',
                'email' => 'timotheus@example.com',
                'phone' => '07700 900001',
                'address_line_1' => '12 High Street',
                'city' => 'Southend-on-Sea',
                'postcode' => 'SS1 1AB',
            ],
            [
                'name' => 'William Hardman',
                'email' => 'william@example.com',
                'phone' => '07700 900002',
                'address_line_1' => '45 Station Road',
                'city' => 'London',
                'postcode' => 'E1 6AN',
            ],
            [
                'name' => 'Joe Bennett',
                'email' => 'joe@example.com',
                'phone' => '07700 900003',
                'address_line_1' => '8 Marine Parade',
                'city' => 'Westcliff-on-Sea',
                'postcode' => 'SS0 7EL',
            ],
            [
                'name' => 'Angharad Lewis',
                'email' => 'angharad@example.com',
                'phone' => '07700 900004',
                'address_line_1' => '22 Queen Street',
                'city' => 'Manchester',
                'postcode' => 'M1 2HQ',
            ],
            [
                'name' => 'Lindsay Harvey',
                'email' => 'lindsay@example.com',
                'phone' => '07700 900005',
                'address_line_1' => '3 Victoria Avenue',
                'city' => 'Leeds',
                'postcode' => 'LS1 4DY',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::query()->updateOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}
