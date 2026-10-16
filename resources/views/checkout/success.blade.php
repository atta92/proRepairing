@extends('layouts.app')

@section('title', 'Booking confirmed - ' . $siteSettings->displayName())

@section('meta_description', 'Your repair booking is confirmed. We will be in touch from ' . $siteSettings->displayName() . '.')

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="bg-snow py-16 sm:py-24">
        <div class="mx-auto max-w-2xl px-4 text-center sm:px-6">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent">Booking confirmed</p>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                Thanks, {{ $order->customer->name }}
            </h1>
            <p class="mt-3 text-steel">
                Your repair reference is
                <span class="font-semibold text-ink">{{ $order->reference }}</span>.
            </p>

            <div class="mt-8 rounded-2xl border border-mist bg-white p-6 text-left shadow-sm">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-steel">Device</dt>
                        <dd class="font-medium text-ink">{{ $order->device->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-steel">Fulfilment</dt>
                        <dd class="font-medium text-ink">Mail-in</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-steel">Scheduled</dt>
                        <dd class="font-medium text-ink">
                            {{ $order->scheduled_date?->format('d M Y') }}
                            @if ($order->scheduled_time)
                                · {{ $order->scheduled_time }}
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-steel">Status</dt>
                        <dd class="font-medium text-ink">{{ str_replace('_', ' ', $order->status) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-t border-mist pt-3">
                        <dt class="text-steel">Total</dt>
                        <dd class="font-display text-xl font-bold text-accent">£{{ number_format((float) $order->total, 2) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-steel">Payment</dt>
                        <dd class="font-medium text-ink">{{ $order->paid_at ? 'Paid online' : 'Pay at store / on completion' }}</dd>
                    </div>
                </dl>

                <ul class="mt-5 space-y-2 border-t border-mist pt-4 text-sm">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between gap-3">
                            <span class="text-ink">{{ $item->name }}</span>
                            <span class="text-steel">£{{ number_format((float) $item->price, 0) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="btn-primary">Back to home</a>
                <a href="{{ route('categories.show', $order->device->category->slug) }}" class="btn-ghost">Browse more repairs</a>
            </div>
        </div>
    </section>
@endsection
