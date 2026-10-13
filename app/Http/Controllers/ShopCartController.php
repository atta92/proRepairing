<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
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
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Accessory::query()
            ->active()
            ->findOrFail($validated['accessory_id']);

        if (! $product->isInStock()) {
            if ($request->wantsJson()) {
                return response()->json(['ok' => false, 'message' => 'That item is currently out of stock.'], 422);
            }

            return back()->with('error', 'That item is currently out of stock.');
        }

        $cart->add($product->id, (int) ($validated['quantity'] ?? 1));

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $product->name . ' added to your bag.',
                'cart' => $cart->clientPayload(),
            ]);
        }

        return redirect()
            ->route('shop.cart')
            ->with('success', $product->name . ' added to your bag.');
    }

    public function update(Request $request, ShopCart $cart, int $accessory): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $cart->update($accessory, (int) $validated['quantity']);

        if ($request->wantsJson()) {
            $payload = $cart->clientPayload();
            $line = collect($payload['lines'])->firstWhere('id', $accessory);

            return response()->json([
                'ok' => true,
                'removed' => $line === null,
                'quantity' => $line['quantity'] ?? 0,
                'line_total' => $line['line_total'] ?? 0,
                'line_total_formatted' => '£' . number_format((float) ($line['line_total'] ?? 0), 2),
                'totals' => $payload['totals'],
                'empty' => $payload['empty'],
                'cart' => $payload,
            ]);
        }

        return redirect()->route('shop.cart')->with('success', 'Bag updated.');
    }

    public function remove(Request $request, ShopCart $cart, int $accessory): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $cart->remove($accessory);

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
}
