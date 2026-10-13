@extends('layouts.app')

@section('title', 'My account — ' . $siteSettings->displayName())

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Account</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">Hi, {{ $user->name }}</h1>
            <p class="mt-2 text-white/70">Manage your repairs, shop orders and profile.</p>
        </div>
    </section>

    <section class="bg-snow py-10 sm:py-14">
        <div class="site-container space-y-8">
            @include('account._nav')

            <div class="grid gap-4 sm:grid-cols-3">
                <a href="{{ route('account.repairs') }}" class="rounded-2xl border border-mist bg-white p-5 transition hover:border-accent">
                    <p class="text-xs font-semibold uppercase tracking-wider text-steel">Repair bookings</p>
                    <p class="mt-2 font-display text-3xl font-bold text-ink">{{ $repairOrders->count() }}+</p>
                    <p class="mt-1 text-sm text-steel">Recent bookings on your account</p>
                </a>
                <a href="{{ route('account.shop') }}" class="rounded-2xl border border-mist bg-white p-5 transition hover:border-accent">
                    <p class="text-xs font-semibold uppercase tracking-wider text-steel">Shop orders</p>
                    <p class="mt-2 font-display text-3xl font-bold text-ink">{{ $shopOrders->count() }}+</p>
                    <p class="mt-1 text-sm text-steel">Accessory & device purchases</p>
                </a>
                <a href="{{ route('account.profile') }}" class="rounded-2xl border border-mist bg-white p-5 transition hover:border-accent">
                    <p class="text-xs font-semibold uppercase tracking-wider text-steel">Settings</p>
                    <p class="mt-2 font-display text-xl font-bold text-ink">Profile</p>
                    <p class="mt-1 text-sm text-steel">Update contact details & password</p>
                </a>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-display text-lg font-semibold text-ink">Recent repairs</h2>
                        <a href="{{ route('account.repairs') }}" class="text-sm font-semibold text-accent hover:text-accent-dark">View all</a>
                    </div>
                    <ul class="mt-4 divide-y divide-mist">
                        @forelse ($repairOrders as $order)
                            <li class="flex items-center justify-between gap-3 py-3 text-sm">
                                <div>
                                    <a href="{{ route('account.repairs.show', $order) }}" class="font-semibold text-ink hover:text-accent">{{ $order->reference }}</a>
                                    <p class="text-steel">{{ $order->device?->name }} · {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                </div>
                                <span class="font-medium text-ink">£{{ number_format((float) $order->total, 2) }}</span>
                            </li>
                        @empty
                            <li class="py-6 text-sm text-steel">No repair bookings yet. <a href="{{ route('home') }}#price-checker" class="font-semibold text-accent">Check a price</a></li>
                        @endforelse
                    </ul>
                </div>

                <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-display text-lg font-semibold text-ink">Recent shop orders</h2>
                        <a href="{{ route('account.shop') }}" class="text-sm font-semibold text-accent hover:text-accent-dark">View all</a>
                    </div>
                    <ul class="mt-4 divide-y divide-mist">
                        @forelse ($shopOrders as $order)
                            <li class="flex items-center justify-between gap-3 py-3 text-sm">
                                <div>
                                    <a href="{{ route('account.shop.show', $order) }}" class="font-semibold text-ink hover:text-accent">{{ $order->reference }}</a>
                                    <p class="text-steel">{{ ucfirst($order->fulfilment) }} · {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                </div>
                                <span class="font-medium text-ink">£{{ number_format((float) $order->total, 2) }}</span>
                            </li>
                        @empty
                            <li class="py-6 text-sm text-steel">No shop orders yet. <a href="{{ route('shop.index') }}" class="font-semibold text-accent">Browse the shop</a></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
