<?php

namespace App\Http\Controllers;

use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\Vendor;
use App\Services\CouponService;
use App\Services\OrderMailer;
use App\Services\ShopCart;
use App\Services\StripeCheckoutService;
use App\Services\VendorCommission;
use App\Support\CheckoutCustomer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShopCheckoutController extends Controller
{
    public function show(Request $request, ShopCart $cart, StripeCheckoutService $stripe, CouponService $coupons): View|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your bag is empty.');
        }

        $lines = $cart->lines();
        $fulfilment = 'delivery';
        $totals = $coupons->withDiscount($cart->totals($fulfilment));
        $stripeConfigured = $stripe->isConfigured();
        $user = $request->user();
        $customer = $user?->customer;

        return view('shop.checkout', compact('lines', 'totals', 'stripeConfigured', 'user', 'customer'));
    }

    public function applyCoupon(Request $request, ShopCart $cart, CouponService $coupons): JsonResponse|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return $this->couponResponse($request, false, 'Your bag is empty.', $cart->totals());
        }

        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
            'fulfilment' => ['nullable', 'in:delivery'],
        ]);

        $fulfilment = 'delivery';
        $base = $cart->totals($fulfilment);

        try {
            $coupon = $coupons->apply($validated['coupon_code'], (float) $base['subtotal']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => collect($e->errors())->flatten()->first() ?? 'Invalid coupon.',
                    'totals' => $coupons->withDiscount($base),
                ], 422);
            }

            throw $e;
        }

        $totals = $coupons->withDiscount($base);

        return $this->couponResponse(
            $request,
            true,
            'Coupon '.$coupon->code.' applied.',
            $totals
        );
    }

    public function removeCoupon(Request $request, ShopCart $cart, CouponService $coupons): JsonResponse|RedirectResponse
    {
        $coupons->clear();
        $fulfilment = 'delivery';
        $totals = $coupons->withDiscount($cart->totals($fulfilment));

        return $this->couponResponse($request, true, 'Coupon removed.', $totals);
    }

    public function store(Request $request, ShopCart $cart, StripeCheckoutService $stripe, CouponService $coupons): RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your bag is empty.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:20'],
            'fulfilment' => ['required', 'in:delivery'],
            'payment_method' => ['required', 'in:card'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['fulfilment'] = 'delivery';
        $validated['payment_method'] = 'card';

        if (! $stripe->isConfigured()) {
            return back()
                ->withInput()
                ->with('error', 'Online card payment is not available yet. Please try again later or contact us.');
        }

        $totals = $coupons->withDiscount($cart->totals('delivery'));
        $lines = $cart->lines();
        $payOnline = true;
        $coupon = $coupons->current();

        if ($coupons->currentCode() && (! $coupon || ($totals['discount_amount'] ?? 0) <= 0)) {
            $coupons->clear();
            $coupon = null;
            $totals = $coupons->withDiscount($cart->totals('delivery'));
        }

        $order = DB::transaction(function () use ($request, $validated, $lines, $totals, $payOnline, $coupon, $coupons) {
            $customer = CheckoutCustomer::resolve($request->user(), [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address_line_1' => $validated['address_line_1'] ?? null,
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'] ?? null,
                'postcode' => $validated['postcode'] ?? null,
            ]);

            $order = ShopOrder::query()->create([
                'reference' => $this->nextReference(),
                'customer_id' => $customer->id,
                'fulfilment' => $validated['fulfilment'],
                'payment_method' => $validated['payment_method'],
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'subtotal' => $totals['subtotal'],
                'shipping_amount' => $totals['shipping_amount'],
                'discount_amount' => $totals['discount_amount'],
                'total' => $totals['total'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'paid_at' => null,
            ]);

            $platformVendor = Vendor::platform();
            $commissionLines = VendorCommission::allocate(
                $lines->map(fn (array $line) => [
                    'line_total' => (float) $line['line_total'],
                    'vendor' => $line['vendor'] ?? $line['product']->vendor ?? $platformVendor,
                ])->all(),
                (float) ($totals['discount_amount'] ?? 0)
            );

            foreach ($lines as $index => $line) {
                $variant = $line['variant'] ?? null;
                $vendor = $line['vendor'] ?? $line['product']->vendor ?? $platformVendor;
                $split = $commissionLines[$index] ?? [
                    'commission_rate' => 0,
                    'commission_amount' => 0,
                    'vendor_net' => (float) $line['line_total'],
                ];

                ShopOrderItem::query()->create([
                    'shop_order_id' => $order->id,
                    'vendor_id' => $vendor?->id,
                    'vendor_name' => $vendor?->name,
                    'accessory_id' => $line['product']->id,
                    'accessory_variant_id' => $variant?->id,
                    'name' => $line['name'],
                    'options_label' => $line['options_label'] ?: null,
                    'type' => $line['product']->type,
                    'price' => $line['price'],
                    'quantity' => $line['quantity'],
                    'commission_rate' => $split['commission_rate'],
                    'commission_amount' => $split['commission_amount'],
                    'vendor_net' => $split['vendor_net'],
                    'fulfilment_status' => 'pending',
                ]);

                if (! $payOnline) {
                    if ($variant && $variant->stock !== null) {
                        $variant->decrement('stock', $line['quantity']);
                    } elseif (! $variant && $line['product']->stock !== null) {
                        $line['product']->decrement('stock', $line['quantity']);
                    }
                }
            }

            if ($coupon) {
                $coupons->markUsed($coupon);
            }

            return $order;
        });

        if ($payOnline) {
            try {
                $session = $stripe->createShopSession($order);
                $order->update(['stripe_session_id' => $session->id]);

                return redirect()->away($session->url);
            } catch (\Throwable $e) {
                $order->update(['status' => 'cancelled']);

                return back()
                    ->withInput()
                    ->with('error', 'Could not start Stripe checkout: '.$e->getMessage());
            }
        }

        $cart->clear();
        $coupons->clear();
        $request->session()->put('shop_order_access', $order->id);

        return redirect()
            ->route('shop.checkout.success', $order)
            ->with('success', 'Order placed successfully. Pay when you collect.');
    }

    public function stripeSuccess(Request $request, ShopOrder $shopOrder, ShopCart $cart, StripeCheckoutService $stripe, CouponService $coupons, OrderMailer $mailer): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');

        if ($sessionId === '' || $shopOrder->payment_method !== 'card') {
            return redirect()->route('shop.checkout')->with('error', 'Invalid payment return.');
        }

        try {
            $session = $stripe->retrieveSession($sessionId);
        } catch (\Throwable $e) {
            return redirect()->route('shop.checkout')->with('error', 'Could not verify Stripe payment.');
        }

        if (($session->metadata['shop_order_id'] ?? null) !== (string) $shopOrder->id
            && ($session->client_reference_id ?? null) !== (string) $shopOrder->id) {
            return redirect()->route('shop.checkout')->with('error', 'Payment session does not match this order.');
        }

        if ($session->payment_status !== 'paid' && $session->status !== 'complete') {
            return redirect()->route('shop.checkout')->with('error', 'Payment was not completed.');
        }

        $alreadyPaid = $shopOrder->status === 'paid';

        if (! $alreadyPaid) {
            DB::transaction(function () use ($shopOrder, $session) {
                $shopOrder->refresh();

                if ($shopOrder->status === 'paid') {
                    return;
                }

                $shopOrder->load(['items.accessory', 'items.variant']);

                foreach ($shopOrder->items as $item) {
                    if ($item->variant && $item->variant->stock !== null) {
                        $item->variant->decrement('stock', $item->quantity);
                    } elseif (! $item->variant && $item->accessory && $item->accessory->stock !== null) {
                        $item->accessory->decrement('stock', $item->quantity);
                    }
                }

                $shopOrder->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'stripe_session_id' => $session->id,
                    'stripe_payment_intent' => is_string($session->payment_intent)
                        ? $session->payment_intent
                        : ($session->payment_intent->id ?? null),
                ]);
            });

            $mailer->shopOrderPaid($shopOrder->fresh(['customer', 'items']));
        }

        $cart->clear();
        $coupons->clear();
        $request->session()->put('shop_order_paid', $shopOrder->id);

        return redirect()
            ->route('shop.checkout.success', $shopOrder)
            ->with('success', 'Payment successful — thank you! A confirmation email is on its way.');
    }

    public function stripeCancel(ShopOrder $shopOrder): RedirectResponse
    {
        if ($shopOrder->status === 'pending' && $shopOrder->payment_method === 'card') {
            $shopOrder->update(['status' => 'cancelled']);
        }

        return redirect()
            ->route('shop.checkout')
            ->with('error', 'Payment cancelled. Your bag is unchanged — you can try again.');
    }

    public function success(Request $request, ShopOrder $shopOrder): View
    {
        $shopOrder->load(['customer', 'items']);

        $owns = $request->user() && $shopOrder->customer?->user_id === $request->user()->id;
        $justPaid = $request->session()->get('shop_order_paid') === $shopOrder->id;
        $guestAccess = $request->session()->get('shop_order_access') === $shopOrder->id;

        abort_unless($owns || $justPaid || $guestAccess, 403);

        return view('shop.success', ['order' => $shopOrder]);
    }

    private function couponResponse(Request $request, bool $ok, string $message, array $totals): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'ok' => $ok,
                'message' => $message,
                'totals' => $totals,
            ], $ok ? 200 : 422);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }

    private function nextReference(): string
    {
        $latest = ShopOrder::query()->latest('id')->value('reference');
        $number = 20001;

        if ($latest && preg_match('/(\d+)$/', $latest, $matches)) {
            $number = ((int) $matches[1]) + 1;
        }

        return 'SHOP-'.$number;
    }
}
