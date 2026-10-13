@extends('layouts.app')

@section('title', 'Booking ' . $order->reference . ' — ' . $siteSettings->displayName())

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <a href="{{ route('account.repairs') }}" class="text-sm font-semibold text-accent-light hover:text-white">← Back to bookings</a>
            <h1 class="mt-3 font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $order->reference }}</h1>
            <p class="mt-2 text-white/70">{{ $order->device?->name }} · {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
        </div>
    </section>

    <section class="bg-snow py-10 sm:py-14">
        <div class="site-container max-w-3xl space-y-6">
            @include('account._nav')

            <div class="rounded-2xl border border-mist bg-white p-6">
                <h2 class="font-display text-lg font-semibold text-ink">Repairs</h2>
                <ul class="mt-4 divide-y divide-mist">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between gap-3 py-3 text-sm">
                            <span class="text-ink">{{ $item->name }}</span>
                            <span class="font-medium text-ink">£{{ number_format((float) $item->price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 flex justify-between border-t border-mist pt-4 text-sm font-semibold text-ink">
                    <span>Total</span>
                    <span>£{{ number_format((float) $order->total, 2) }}</span>
                </div>
            </div>

            <div class="rounded-2xl border border-mist bg-white p-6 text-sm text-steel">
                <p><span class="font-semibold text-ink">Fulfilment:</span> {{ ucfirst(str_replace('_', ' ', $order->fulfilment)) }}</p>
                @if ($order->scheduled_date)
                    <p class="mt-2"><span class="font-semibold text-ink">Scheduled:</span> {{ $order->scheduled_date->format('d M Y') }}{{ $order->scheduled_time ? ' · '.$order->scheduled_time : '' }}</p>
                @endif
                <p class="mt-2"><span class="font-semibold text-ink">Placed:</span> {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </section>
@endsection
