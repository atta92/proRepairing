<?php

namespace App\Services;

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

    public function createSession(ShopOrder $order): Session
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Stripe is not configured. Add your keys in Admin → Stripe settings.');
        }

        Stripe::setApiKey($this->settings->stripe_secret_key);

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

        $sessionPayload = [
            'mode' => 'payment',
            'customer_email' => $order->customer?->email,
            'client_reference_id' => (string) $order->id,
            'line_items' => $lineItems,
            'success_url' => route('shop.checkout.stripe.success', [
                'shopOrder' => $order->id,
            ]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('shop.checkout.stripe.cancel', [
                'shopOrder' => $order->id,
            ]),
            'metadata' => [
                'shop_order_id' => (string) $order->id,
                'reference' => $order->reference,
            ],
        ];

        $discountPence = (int) round(((float) $order->discount_amount) * 100);

        if ($discountPence > 0) {
            $stripeCoupon = \Stripe\Coupon::create([
                'amount_off' => $discountPence,
                'currency' => 'gbp',
                'duration' => 'once',
                'name' => $order->coupon_code ? 'Coupon ' . $order->coupon_code : 'Discount',
            ]);

            $sessionPayload['discounts'] = [
                ['coupon' => $stripeCoupon->id],
            ];
        }

        return Session::create($sessionPayload);
    }

    public function retrieveSession(string $sessionId): Session
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Stripe is not configured.');
        }

        Stripe::setApiKey($this->settings->stripe_secret_key);

        return Session::retrieve($sessionId);
    }
}
