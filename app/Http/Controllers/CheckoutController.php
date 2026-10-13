<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\BookingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(BookingCart $cart): View|RedirectResponse
    {
        $device = $cart->device();
        $items = $cart->items();

        if (! $device || $items->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your booking basket is empty. Choose a repair to continue.');
        }

        $totals = $cart->totals();

        return view('checkout.show', compact('device', 'items', 'totals'));
    }

    public function store(Request $request, BookingCart $cart): RedirectResponse
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
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:20'],
            'fulfilment' => ['required', 'in:walk_in,mail_in'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:card,store'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['fulfilment'] === 'mail_in') {
            $request->validate([
                'address_line_1' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:100'],
                'postcode' => ['required', 'string', 'max:20'],
            ]);
        }

        $totals = $cart->totals();

        $order = DB::transaction(function () use ($validated, $device, $items, $totals) {
            $customer = Customer::query()->updateOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'address_line_1' => $validated['address_line_1'] ?? null,
                    'address_line_2' => $validated['address_line_2'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'postcode' => $validated['postcode'] ?? null,
                ]
            );

            $paidOnline = $validated['payment_method'] === 'card';

            $order = Order::query()->create([
                'reference' => $this->nextReference(),
                'customer_id' => $customer->id,
                'device_id' => $device->id,
                'fulfilment' => $validated['fulfilment'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'] ?: null,
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'],
                'total' => $totals['total'],
                'status' => $paidOnline ? 'awaiting_device' : 'pending',
                'notes' => trim(($validated['notes'] ?? '') . "\nPayment: " . ($paidOnline ? 'Card (online)' : 'Pay at store')),
                'paid_at' => $paidOnline ? now() : null,
            ]);

            foreach ($items as $sku) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'device_repair_id' => $sku->id,
                    'name' => $device->name . ' ' . $sku->repair->name,
                    'price' => $sku->price,
                    'quantity' => 1,
                ]);
            }

            return $order;
        });

        $cart->clear();

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Booking confirmed.');
    }

    public function success(Order $order): View
    {
        $order->load(['customer', 'device.category', 'items']);

        return view('checkout.success', compact('order'));
    }

    private function nextReference(): string
    {
        $latest = Order::query()->latest('id')->value('reference');
        $number = 10001;

        if ($latest && preg_match('/(\d+)$/', $latest, $matches)) {
            $number = ((int) $matches[1]) + 1;
        }

        return 'IER-' . $number;
    }
}
