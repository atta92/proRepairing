@extends('layouts.app')

@section('title', 'Order confirmed — ' . $siteSettings->displayName())

@section('meta_description', 'Your shop order has been confirmed. Thank you for shopping with ' . $siteSettings->displayName() . '.')

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="bg-snow py-16 sm:py-20">
        <div class="mx-auto max-w-2xl px-4 text-center sm:px-6">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent">Thank you</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                Order confirmed
            </h1>
            <p class="mt-3 text-steel">
                Reference <span class="font-semibold text-ink">{{ $order->reference }}</span>
            </p>

            <div class="mt-8 rounded-2xl border border-mist bg-white p-6 text-left text-sm">
                <p class="font-semibold text-ink">{{ $order->customer->name }}</p>
                <p class="mt-1 text-steel">{{ $order->customer->email }} · {{ $order->customer->phone }}</p>
            <p class="mt-3 text-steel">
                {{ $order->fulfilment === 'delivery' ? 'UK delivery' : 'Store collection' }}
                ·
                @if ($order->payment_method === 'card')
                    {{ $order->status === 'paid' ? 'Paid online with Stripe' : 'Online payment ' . $order->status }}
                @else
                    Pay in store
                @endif
            </p>
                <ul class="mt-4 space-y-2 border-t border-mist pt-4">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between gap-3">
                            <span>{{ $item->name }} × {{ $item->quantity }}</span>
                            <span class="font-semibold">£{{ number_format((float) $item->price * $item->quantity, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 flex justify-between border-t border-mist pt-4 text-sm text-steel">
                    <span>Subtotal</span>
                    <span>£{{ number_format((float) $order->subtotal, 2) }}</span>
                </div>
                @if ((float) $order->discount_amount > 0)
                    <div class="mt-2 flex justify-between text-sm text-accent">
                        <span>Discount{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}</span>
                        <span>-£{{ number_format((float) $order->discount_amount, 2) }}</span>
                    </div>
                @endif
                @if ((float) $order->shipping_amount > 0)
                    <div class="mt-2 flex justify-between text-sm text-steel">
                        <span>Shipping</span>
                        <span>£{{ number_format((float) $order->shipping_amount, 2) }}</span>
                    </div>
                @endif
                <div class="mt-4 flex justify-between border-t border-mist pt-4 font-semibold text-ink">
                    <span>Total</span>
                    <span>£{{ number_format((float) $order->total, 2) }}</span>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('shop.index') }}" class="btn-primary">Continue shopping</a>
                <a href="{{ route('home') }}" class="btn-ghost">Home</a>
            </div>
        </div>
    </section>
@endsection
