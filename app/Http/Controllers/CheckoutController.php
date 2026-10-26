<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RepairAddon;
use App\Services\BookingCart;
use App\Services\OrderMailer;
use App\Services\StripeCheckoutService;
use App\Support\CheckoutCustomer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request, BookingCart $cart, StripeCheckoutService $stripe): View|RedirectResponse
    {
        $device = $cart->device();
        $items = $cart->items();

        if (! $device || $items->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your booking basket is empty. Choose a repair to continue.');
        }

        if ($request->has('addon_ids')) {
            $cart->setAddonIds((array) $request->input('addon_ids', []));
        }

        $addons = RepairAddon::query()->active()->ordered()->get();
        $selectedAddons = $cart->addons();
        $totals = $cart->totals();
        $user = $request->user();
        $customer = $user?->customer;
        $stripeConfigured = $stripe->isConfigured();

        return view('checkout.show', compact(
            'device',
            'items',
            'addons',
            'selectedAddons',
            'totals',
            'user',
            'customer',
            'stripeConfigured'
        ));
    }

    public function updateAddons(Request $request, BookingCart $cart): JsonResponse|RedirectResponse
    {
        if (! $cart->device() || $cart->items()->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Basket empty'], 422);
            }

            return redirect()->route('home')->with('error', 'Your booking basket is empty.');
        }

        $validated = $request->validate([
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['integer', 'exists:repair_addons,id,is_active,1'],
        ]);

        $cart->setAddonIds($validated['addon_ids'] ?? []);

        $selectedAddons = $cart->addons()->map(fn (RepairAddon $addon) => [
            'id' => $addon->id,
            'name' => $addon->name,
            'price' => (float) $addon->price,
        ])->values();

        if ($request->expectsJson()) {
            return response()->json([
                'addon_ids' => $cart->addonIds(),
                'addons' => $selectedAddons,
                'totals' => $cart->totals(),
            ]);
        }

        return redirect()->route('checkout.show');
    }

    public function store(Request $request, BookingCart $cart, StripeCheckoutService $stripe, OrderMailer $mailer): RedirectResponse
    {
        $device = $cart->device();
        $items = $cart->items();

        if (! $device || $items->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your booking basket is empty.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:20'],
            'fulfilment' => ['required', 'in:mail_in'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:card,store'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['integer', 'exists:repair_addons,id,is_active,1'],
        ]);

        $cart->setAddonIds($validated['addon_ids'] ?? []);
        $selectedAddons = $cart->addons();

        if ($validated['payment_method'] === 'card' && ! $stripe->isConfigured()) {
            return back()
                ->withInput()
                ->with('error', 'Online card payment is not available yet. Please choose pay in store, or ask us to enable Stripe.');
        }

        $totals = $cart->totals();
        $payOnline = $validated['payment_method'] === 'card';

        $order = DB::transaction(function () use ($request, $validated, $device, $items, $selectedAddons, $totals, $payOnline) {
            $customer = CheckoutCustomer::resolve($request->user(), [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address_line_1' => $validated['address_line_1'] ?? null,
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'] ?? null,
                'postcode' => $validated['postcode'] ?? null,
            ]);

            $order = Order::query()->create([
                'reference' => $this->nextReference(),
                'customer_id' => $customer->id,
                'device_id' => $device->id,
                'fulfilment' => $validated['fulfilment'],
                'payment_method' => $validated['payment_method'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'] ?: null,
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'],
                'total' => $totals['total'],
                'status' => 'pending',
                'notes' => trim(($validated['notes'] ?? '')."\nPayment: ".($payOnline ? 'Card (Stripe)' : 'Pay at store')),
                'paid_at' => null,
            ]);

            foreach ($items as $sku) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'device_repair_id' => $sku->id,
                    'name' => $device->name.' '.$sku->repair->name,
                    'price' => $sku->price,
                    'quantity' => 1,
                ]);
            }

            foreach ($selectedAddons as $addon) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'device_repair_id' => null,
                    'repair_addon_id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price,
                    'quantity' => 1,
                ]);
            }

            return $order;
        });

        if ($payOnline) {
            try {
                $session = $stripe->createRepairSession($order->load(['items', 'customer', 'device']));
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
        $request->session()->put('repair_order_access', $order->id);
        $mailer->repairOrderPaid($order->fresh(['customer', 'items', 'device']));

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Booking confirmed. Pay when you visit the store.');
    }

    public function stripeSuccess(Request $request, Order $order, BookingCart $cart, StripeCheckoutService $stripe, OrderMailer $mailer): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');

        if ($sessionId === '' || $order->payment_method !== 'card') {
            return redirect()->route('checkout.show')->with('error', 'Invalid payment return.');
        }

        try {
            $session = $stripe->retrieveSession($sessionId);
        } catch (\Throwable $e) {
            return redirect()->route('checkout.show')->with('error', 'Could not verify Stripe payment.');
        }

        if (($session->metadata['order_id'] ?? null) !== (string) $order->id
            && ($session->client_reference_id ?? null) !== (string) $order->id) {
            return redirect()->route('checkout.show')->with('error', 'Payment session does not match this booking.');
        }

        if ($session->payment_status !== 'paid' && $session->status !== 'complete') {
            return redirect()->route('checkout.show')->with('error', 'Payment was not completed.');
        }

        $alreadyPaid = filled($order->paid_at);

        if (! $alreadyPaid) {
            $order->update([
                'status' => 'awaiting_device',
                'paid_at' => now(),
                'stripe_session_id' => $session->id,
                'stripe_payment_intent' => is_string($session->payment_intent)
                    ? $session->payment_intent
                    : ($session->payment_intent->id ?? null),
            ]);

            $mailer->repairOrderPaid($order->fresh(['customer', 'items', 'device']));
        }

        $cart->clear();
        $request->session()->put('repair_order_paid', $order->id);

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Payment successful — your repair booking is confirmed. A confirmation email is on its way.');
    }

    public function stripeCancel(Order $order): RedirectResponse
    {
        if ($order->status === 'pending' && $order->payment_method === 'card' && blank($order->paid_at)) {
            $order->update(['status' => 'cancelled']);
        }

        return redirect()
            ->route('checkout.show')
            ->with('error', 'Payment cancelled. Your booking basket is unchanged — you can try again.');
    }

    public function success(Request $request, Order $order): View
    {
        $order->load(['customer', 'device.category', 'items']);

        $owns = $request->user() && $order->customer?->user_id === $request->user()->id;
        $justPaid = $request->session()->get('repair_order_paid') === $order->id;
        $guestAccess = $request->session()->get('repair_order_access') === $order->id;

        abort_unless($owns || $justPaid || $guestAccess, 403);

        return view('checkout.success', compact('order'));
    }

    private function nextReference(): string
    {
        $latest = Order::query()->latest('id')->value('reference');
        $number = 10001;

        if ($latest && preg_match('/(\d+)$/', $latest, $matches)) {
            $number = ((int) $matches[1]) + 1;
        }

        return 'IER-'.$number;
    }
}
