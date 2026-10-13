<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShopOrder;
use App\Models\SiteSetting;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeCheckoutService
{
    private SiteSetting $settings;

    public function __construct()
    {
        $this->settings = SiteSetting::current();
    }

    public function isConfigured(): bool
    {
        return $this->settings->hasStripeConfigured();
    }

    public function createShopSession(ShopOrder $order): Session
    {
        $this->boot();

        $order->loadMissing('items', 'customer');

        $lineItems = $order->items->map(function ($item) {
            return [
                'quantity' => (int) $item->quantity,
                'price_data' => [
                    'currency' => 'gbp',
                    'unit_amount' => (int) round(((float) $item->price) * 100),
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
            ];
        })->values()->all();

        if ((float) $order->shipping_amount > 0) {
            $lineItems[] = [
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'gbp',
                    'unit_amount' => (int) round(((float) $order->shipping_amount) * 100),
                    'product_data' => [
                        'name' => 'UK delivery',
                    ],
                ],
            ];
        }

        return $this->createSession(
            lineItems: $lineItems,
            email: $order->customer?->email,
            referenceId: (string) $order->id,
            successRoute: 'shop.checkout.stripe.success',
            cancelRoute: 'shop.checkout.stripe.cancel',
            routeParams: ['shopOrder' => $order->id],
            metadata: [
                'type' => 'shop',
                'shop_order_id' => (string) $order->id,
                'reference' => $order->reference,
            ],
            discountAmount: (float) $order->discount_amount,
            couponCode: $order->coupon_code,
        );
    }

    public function createRepairSession(Order $order): Session
    {
        $this->boot();

        $order->loadMissing('items', 'customer', 'device');

        $lineItems = $order->items->map(function ($item) {
            return [
                'quantity' => (int) ($item->quantity ?: 1),
                'price_data' => [
                    'currency' => 'gbp',
                    'unit_amount' => (int) round(((float) $item->price) * 100),
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
            ];
        })->values()->all();

        return $this->createSession(
            lineItems: $lineItems,
            email: $order->customer?->email,
            referenceId: (string) $order->id,
            successRoute: 'checkout.stripe.success',
            cancelRoute: 'checkout.stripe.cancel',
            routeParams: ['order' => $order->id],
            metadata: [
                'type' => 'repair',
                'order_id' => (string) $order->id,
                'reference' => $order->reference,
            ],
            discountAmount: (float) $order->discount_amount,
            couponCode: null,
        );
    }

    public function retrieveSession(string $sessionId): Session
    {
        $this->boot();

        return Session::retrieve($sessionId);
    }

    /**
     * @param  list<array<string, mixed>>  $lineItems
     * @param  array<string, string>  $routeParams
     * @param  array<string, string>  $metadata
     */
    private function createSession(
        array $lineItems,
        ?string $email,
        string $referenceId,
        string $successRoute,
        string $cancelRoute,
        array $routeParams,
        array $metadata,
        float $discountAmount,
        ?string $couponCode,
    ): Session {
        if ($lineItems === []) {
            throw new RuntimeException('No line items available for Stripe checkout.');
        }

        $sessionPayload = [
            'mode' => 'payment',
            'customer_email' => $email,
            'client_reference_id' => $referenceId,
            'line_items' => $lineItems,
            'success_url' => route($successRoute, $routeParams).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route($cancelRoute, $routeParams),
            'metadata' => $metadata,
        ];

        $discountPence = (int) round($discountAmount * 100);

        if ($discountPence > 0) {
            $stripeCoupon = \Stripe\Coupon::create([
                'amount_off' => $discountPence,
                'currency' => 'gbp',
                'duration' => 'once',
                'name' => $couponCode ? 'Coupon '.$couponCode : 'Discount',
            ]);

            $sessionPayload['discounts'] = [
                ['coupon' => $stripeCoupon->id],
            ];
        }

        return Session::create($sessionPayload);
    }

    private function boot(): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Stripe is not configured. Add your keys in Admin → Settings → Stripe.');
        }

        Stripe::setApiKey($this->settings->stripe_secret_key);
    }
}
