<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\AccessoryVariant;
use App\Services\ShopCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopCartController extends Controller
{
    public function show(ShopCart $cart): View
    {
        $lines = $cart->lines();
        $totals = $cart->totals();

        return view('shop.cart', compact('lines', 'totals'));
    }

    public function add(Request $request, ShopCart $cart): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'accessory_id' => ['required', 'integer', 'exists:accessories,id'],
            'variant_id' => ['nullable', 'integer', 'exists:accessory_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Accessory::query()
            ->active()
            ->with('activeVariants')
            ->findOrFail($validated['accessory_id']);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $variant = null;

        if ($product->hasVariants()) {
            $variantId = $validated['variant_id'] ?? null;

            if (! $variantId) {
                return $this->fail($request, 'Please choose storage, colour and other options first.');
            }

            $variant = AccessoryVariant::query()
                ->where('accessory_id', $product->id)
                ->where('is_active', true)
                ->find($variantId);

            if (! $variant) {
                return $this->fail($request, 'That option is no longer available.');
            }

            if (! $variant->isInStock()) {
                return $this->fail($request, 'That option is currently out of stock.');
            }

            $cart->addVariant($variant->id, $quantity);
            $label = $variant->displayName($product);
        } else {
            if (! $product->isInStock()) {
                return $this->fail($request, 'That item is currently out of stock.');
            }

            $cart->addAccessory($product->id, $quantity);
            $label = $product->name;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $label.' added to your bag.',
                'cart' => $cart->clientPayload(),
            ]);
        }

        return redirect()
            ->route('shop.cart')
            ->with('success', $label.' added to your bag.');
    }

    public function update(Request $request, ShopCart $cart, string $line): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $cart->update($line, (int) $validated['quantity']);

        if ($request->wantsJson()) {
            $payload = $cart->clientPayload();
            $key = str_replace('-', ':', $line);
            $matched = collect($payload['lines'])->firstWhere('id', $key);

            return response()->json([
                'ok' => true,
                'removed' => $matched === null,
                'quantity' => $matched['quantity'] ?? 0,
                'line_total' => $matched['line_total'] ?? 0,
                'line_total_formatted' => '£'.number_format((float) ($matched['line_total'] ?? 0), 2),
                'totals' => $payload['totals'],
                'empty' => $payload['empty'],
                'cart' => $payload,
            ]);
        }

        return redirect()->route('shop.cart')->with('success', 'Bag updated.');
    }

    public function remove(Request $request, ShopCart $cart, string $line): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $cart->remove($line);

        if ($request->wantsJson()) {
            $payload = $cart->clientPayload();

            return response()->json([
                'ok' => true,
                'removed' => true,
                'totals' => $payload['totals'],
                'empty' => $payload['empty'],
                'cart' => $payload,
            ]);
        }

        return redirect()->route('shop.cart')->with('success', 'Item removed.');
    }

    private function fail(Request $request, string $message): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['ok' => false, 'message' => $message], 422);
        }

        return back()->with('error', $message);
    }
}
