<?php

namespace App\Services;

use App\Models\Accessory;
use App\Models\AccessoryVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class ShopCart
{
    private const SESSION_KEY = 'shop_cart';

    public function addAccessory(int $accessoryId, int $quantity = 1): void
    {
        $this->addKey($this->accessoryKey($accessoryId), $quantity);
    }

    public function addVariant(int $variantId, int $quantity = 1): void
    {
        $this->addKey($this->variantKey($variantId), $quantity);
    }

    public function update(string $key, int $quantity): void
    {
        $key = $this->normalizeKey($key);
        $items = $this->raw();

        if ($quantity <= 0) {
            unset($items[$key]);
        } else {
            $items[$key] = $quantity;
        }

        Session::put(self::SESSION_KEY, $items);
    }

    public function remove(string $key): void
    {
        $items = $this->raw();
        unset($items[$this->normalizeKey($key)]);
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

        $normalized = [];

        foreach ($items as $key => $qty) {
            // Legacy carts stored plain accessory IDs as keys.
            if (is_int($key) || (is_string($key) && ctype_digit($key))) {
                $key = $this->accessoryKey((int) $key);
            }

            $key = $this->normalizeKey((string) $key);

            if ($key === '') {
                continue;
            }

            $normalized[$key] = max(1, (int) $qty);
        }

        return $normalized;
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

        $accessoryIds = [];
        $variantIds = [];

        foreach (array_keys($raw) as $key) {
            if (str_starts_with($key, 'v:')) {
                $variantIds[] = (int) substr($key, 2);
            } elseif (str_starts_with($key, 'a:')) {
                $accessoryIds[] = (int) substr($key, 2);
            }
        }

        $variants = AccessoryVariant::query()
            ->with('accessory')
            ->whereIn('id', $variantIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $products = Accessory::query()
            ->active()
            ->whereIn('id', $accessoryIds)
            ->get()
            ->keyBy('id');

        return collect($raw)
            ->map(function (int $quantity, string $key) use ($variants, $products) {
                if (str_starts_with($key, 'v:')) {
                    $variant = $variants->get((int) substr($key, 2));
                    $product = $variant?->accessory;

                    if (! $variant || ! $product || ! $product->is_active) {
                        return null;
                    }

                    $price = (float) $variant->price;
                    $imagePath = $variant->image_path ?: $product->image_path;

                    return [
                        'key' => $key,
                        'product' => $product,
                        'variant' => $variant,
                        'name' => $variant->displayName($product),
                        'options_label' => $variant->optionsLabel(),
                        'price' => $price,
                        'price_formatted' => $variant->formatted_price,
                        'quantity' => $quantity,
                        'line_total' => round($price * $quantity, 2),
                        'image_path' => $imagePath,
                        'type_label' => $product->type_label,
                    ];
                }

                if (str_starts_with($key, 'a:')) {
                    $product = $products->get((int) substr($key, 2));

                    if (! $product || $product->hasVariants()) {
                        return null;
                    }

                    $price = (float) $product->price;

                    return [
                        'key' => $key,
                        'product' => $product,
                        'variant' => null,
                        'name' => $product->name,
                        'options_label' => '',
                        'price' => $price,
                        'price_formatted' => '£'.number_format($price, 2),
                        'quantity' => $quantity,
                        'line_total' => round($price * $quantity, 2),
                        'image_path' => $product->image_path,
                        'type_label' => $product->type_label,
                    ];
                }

                return null;
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
                'id' => $line['key'],
                'name' => $line['name'],
                'options' => $line['options_label'],
                'type' => $line['type_label'],
                'url' => route('shop.show', $product),
                'image' => $line['image_path'] ? asset('storage/'.$line['image_path']) : null,
                'price' => (float) $line['price'],
                'price_formatted' => $line['price_formatted'],
                'quantity' => (int) $line['quantity'],
                'line_total' => (float) $line['line_total'],
                'update_url' => route('shop.cart.update', str_replace(':', '-', $line['key'])),
                'remove_url' => route('shop.cart.remove', str_replace(':', '-', $line['key'])),
            ];
        })->values()->all();

        return [
            'lines' => $lines,
            'totals' => $this->totals(),
            'empty' => $this->isEmpty(),
            'count' => $this->count(),
        ];
    }

    public function accessoryKey(int $id): string
    {
        return 'a:'.$id;
    }

    public function variantKey(int $id): string
    {
        return 'v:'.$id;
    }

    private function addKey(string $key, int $quantity): void
    {
        $quantity = max(1, $quantity);
        $items = $this->raw();

        if (isset($items[$key])) {
            $items[$key] += $quantity;
        } else {
            $items[$key] = $quantity;
        }

        Session::put(self::SESSION_KEY, $items);
    }

    private function normalizeKey(string $key): string
    {
        $key = str_replace('-', ':', trim($key));

        if (preg_match('/^[av]:\d+$/', $key)) {
            return $key;
        }

        return '';
    }
}
