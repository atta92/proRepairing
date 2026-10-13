<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Device;
use App\Models\DeviceRepair;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'customer_email' => 'timotheus@example.com',
                'device_slug' => 'galaxy-s24-ultra-repair',
                'repair_slug' => 'front-screen',
                'fulfilment' => 'walk_in',
                'status' => 'completed',
                'reference' => 'IER-10001',
            ],
            [
                'customer_email' => 'william@example.com',
                'device_slug' => 'ipad-pro-97-repair',
                'repair_slug' => 'battery-replacement',
                'fulfilment' => 'mail_in',
                'status' => 'completed',
                'reference' => 'IER-10002',
            ],
            [
                'customer_email' => 'joe@example.com',
                'device_slug' => 'galaxy-z-flip-6-repair',
                'repair_slug' => 'front-screen',
                'fulfilment' => 'mail_in',
                'status' => 'in_repair',
                'reference' => 'IER-10003',
            ],
            [
                'customer_email' => 'angharad@example.com',
                'device_slug' => 'iphone-16-repair',
                'repair_slug' => 'front-screen',
                'fulfilment' => 'mail_in',
                'status' => 'awaiting_device',
                'reference' => 'IER-10004',
            ],
            [
                'customer_email' => 'lindsay@example.com',
                'device_slug' => 'macbook-air-m2-repair',
                'repair_slug' => 'battery-replacement',
                'fulfilment' => 'mail_in',
                'status' => 'pending',
                'reference' => 'IER-10005',
            ],
        ];

        foreach ($samples as $sample) {
            $customer = Customer::query()->where('email', $sample['customer_email'])->first();
            $device = Device::query()->where('slug', $sample['device_slug'])->first();

            if (! $customer || ! $device) {
                continue;
            }

            $deviceRepair = DeviceRepair::query()
                ->where('device_id', $device->id)
                ->whereHas('repair', fn ($query) => $query->where('slug', $sample['repair_slug']))
                ->with('repair')
                ->first();

            if (! $deviceRepair) {
                continue;
            }

            $price = (float) $deviceRepair->price;
            $itemName = $device->name . ' ' . $deviceRepair->repair->name;

            $order = Order::query()->updateOrCreate(
                ['reference' => $sample['reference']],
                [
                    'customer_id' => $customer->id,
                    'device_id' => $device->id,
                    'fulfilment' => $sample['fulfilment'],
                    'scheduled_date' => now()->addDays(2)->toDateString(),
                    'scheduled_time' => '16:00',
                    'subtotal' => $price,
                    'discount_amount' => 0,
                    'total' => $price,
                    'status' => $sample['status'],
                    'notes' => 'Seeded sample booking.',
                    'paid_at' => $sample['status'] === 'completed' ? now()->subDays(3) : null,
                ]
            );

            OrderItem::query()->updateOrCreate(
                [
                    'order_id' => $order->id,
                    'device_repair_id' => $deviceRepair->id,
                ],
                [
                    'name' => $itemName,
                    'price' => $price,
                    'quantity' => 1,
                ]
            );
        }
    }
}
