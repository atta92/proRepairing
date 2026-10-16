<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceRepair;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class BookingCart
{
    private const SESSION_KEY = 'booking_cart';

    public function put(int $deviceId, array $deviceRepairIds): void
    {
        Session::put(self::SESSION_KEY, [
            'device_id' => $deviceId,
            'device_repair_ids' => array_values(array_unique(array_map('intval', $deviceRepairIds))),
        ]);
    }

    public function get(): ?array
    {
        $cart = Session::get(self::SESSION_KEY);

        if (! is_array($cart) || empty($cart['device_id']) || empty($cart['device_repair_ids'])) {
            return null;
        }

        return $cart;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function device(): ?Device
    {
        $cart = $this->get();

        if (! $cart) {
            return null;
        }

        return Device::query()
            ->where('id', $cart['device_id'])
            ->where('is_active', true)
            ->with('category')
            ->first();
    }

    public function items(): Collection
    {
        $cart = $this->get();

        if (! $cart) {
            return collect();
        }

        return DeviceRepair::query()
            ->whereIn('id', $cart['device_repair_ids'])
            ->where('device_id', $cart['device_id'])
            ->where('is_active', true)
            ->with('repair')
            ->orderBy('sort_order')
            ->get();
    }

    public function totals(): array
    {
        $items = $this->items();
        $subtotal = (float) $items->sum(fn (DeviceRepair $item) => (float) $item->price);

        return [
            'subtotal' => $subtotal,
            'discount_percent' => 0,
            'discount_amount' => 0.0,
            'total' => round($subtotal, 2),
            'count' => $items->count(),
        ];
    }
}
