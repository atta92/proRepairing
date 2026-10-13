<?php

namespace App\Services;

use App\Models\Accessory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class ShopCart
{
    private const SESSION_KEY = 'shop_cart';

    public function add(int $accessoryId, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);
        $items = $this->raw();

        if (isset($items[$accessoryId])) {
            $items[$accessoryId] += $quantity;
        } else {
            $items[$accessoryId] = $quantity;
        }

        Session::put(self::SESSION_KEY, $items);
    }

    public function update(int $accessoryId, int $quantity): void
    {
        $items = $this->raw();

        if ($quantity <= 0) {
            unset($items[$accessoryId]);
        } else {
            $items[$accessoryId] = $quantity;
        }

        Session::put(self::SESSION_KEY, $items);
    }

    public function remove(int $accessoryId): void
    {
        $items = $this->raw();
        unset($items[$accessoryId]);
        Session::put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function raw(): array
    {
        $items = Session::get(self::SESSION_KEY, []);

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->mapWithKeys(fn ($qty, $id) => [(int) $id => max(1, (int) $qty)])
            ->all();
    }

    public function count(): int
    {
        return (int) array_sum($this->raw());
    }

    public function lines(): Collection
    {
        $raw = $this->raw();

        if ($raw === []) {
            return collect();
        }

        $products = Accessory::query()
            ->active()
            ->whereIn('id', array_keys($raw))
            ->get()
            ->keyBy('id');

        return collect($raw)
            ->map(function (int $quantity, int $id) use ($products) {
                $product = $products->get($id);

                if (! $product) {
                    return null;
                }

                $lineTotal = round((float) $product->price * $quantity, 2);

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            })
            ->filter()
            ->values();
    }

    public function totals(string $fulfilment = 'collection'): array
    {
        $lines = $this->lines();
        $subtotal = round((float) $lines->sum('line_total'), 2);
        $shipping = $fulfilment === 'delivery' && $subtotal > 0 ? 3.99 : 0.0;
        $total = round($subtotal + $shipping, 2);

        return [
            'subtotal' => $subtotal,
            'shipping_amount' => $shipping,
            'total' => $total,
            'count' => $this->count(),
        ];
    }

    public function isEmpty(): bool
    {
        return $this->lines()->isEmpty();
    }

    public function clientPayload(): array
    {
        $lines = $this->lines()->map(function (array $line) {
            $product = $line['product'];

            return [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->type_label,
                'url' => route('shop.show', $product),
                'image' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                'price' => (float) $product->price,
                'price_formatted' => $product->formatted_price,
                'quantity' => (int) $line['quantity'],
                'line_total' => (float) $line['line_total'],
                'update_url' => route('shop.cart.update', $product->id),
                'remove_url' => route('shop.cart.remove', $product->id),
            ];
        })->values()->all();

        return [
            'lines' => $lines,
            'totals' => $this->totals(),
            'empty' => $this->isEmpty(),
            'count' => $this->count(),
        ];
    }
}
