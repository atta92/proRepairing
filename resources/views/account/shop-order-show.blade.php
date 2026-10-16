@extends('layouts.app')

@section('title', 'Order ' . $order->reference . ' - ' . $siteSettings->displayName())

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <a href="{{ route('account.shop') }}" class="text-sm font-semibold text-accent-light hover:text-white">← Back to shop orders</a>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $order->reference }}</h1>
            <p class="mt-2 text-white/70">{{ ucfirst($order->fulfilment) }} · {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
        </div>
    </section>

    <section class="bg-snow py-10 sm:py-14">
        <div class="site-container max-w-3xl space-y-6">
            @include('account._nav')

            <div class="rounded-2xl border border-mist bg-white p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Items</h2>
                <ul class="mt-4 divide-y divide-mist">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between gap-3 py-3 text-sm">
                            <span class="text-ink">
                                {{ $item->name }} × {{ $item->quantity }}
                                @if ($item->options_label)
                                    <span class="mt-0.5 block text-xs text-steel">{{ $item->options_label }}</span>
                                @endif
                            </span>
                            <span class="font-medium text-ink">£{{ number_format((float) $item->price * $item->quantity, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 space-y-1 border-t border-mist pt-4 text-sm">
                    <div class="flex justify-between text-steel"><span>Subtotal</span><span>£{{ number_format((float) $order->subtotal, 2) }}</span></div>
                    @if ((float) $order->discount_amount > 0)
                        <div class="flex justify-between text-steel"><span>Discount</span><span>-£{{ number_format((float) $order->discount_amount, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between font-semibold text-ink"><span>Total</span><span>£{{ number_format((float) $order->total, 2) }}</span></div>
                </div>
            </div>
        </div>
    </section>
@endsection
