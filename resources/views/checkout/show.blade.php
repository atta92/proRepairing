@extends('layouts.app')

@section('title', 'Checkout - ' . $siteSettings->displayName())

@section('meta_description', 'Complete your repair booking checkout at ' . $siteSettings->displayName() . '.')

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-10 text-white">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Checkout</h1>
            <p class="mt-2 text-white/70">Confirm your repair, details and payment.</p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-8 lg:grid-cols-5">
                @csrf

                <div class="space-y-6 lg:col-span-3">
                    <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                        <h2 class="font-display text-xl font-semibold text-ink">Your details</h2>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <label class="block sm:col-span-2">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Full name</span>
                                <input type="text" name="name" value="{{ old('name', $user?->name) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                                <input type="email" name="email" value="{{ old('email', $user?->email) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Phone</span>
                                <input type="tel" name="phone" value="{{ old('phone', $user?->phone ?: $customer?->phone) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                        <h2 class="font-display text-xl font-semibold text-ink">Mail-in booking</h2>
                        <p class="mt-1 text-sm text-steel">Post it free. We’ll email a tracked return label after booking.</p>
                        <input type="hidden" name="fulfilment" value="mail_in">

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Preferred date</span>
                                <input type="date" name="scheduled_date" value="{{ old('scheduled_date', now()->addDay()->toDateString()) }}" required min="{{ now()->toDateString() }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Time (optional)</span>
                                <select name="scheduled_time" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                                    <option value="">Any time</option>
                                    @foreach (['10:00','11:00','12:00','13:00','14:00','15:00','16:00','16:30','17:00','17:30','18:00'] as $time)
                                        <option value="{{ $time }}" @selected(old('scheduled_time') === $time)>{{ $time }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <label class="block sm:col-span-2">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Address line 1</span>
                                <input type="text" name="address_line_1" value="{{ old('address_line_1', $customer?->address_line_1) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block sm:col-span-2">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Address line 2</span>
                                <input type="text" name="address_line_2" value="{{ old('address_line_2', $customer?->address_line_2) }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">City</span>
                                <input type="text" name="city" value="{{ old('city', $customer?->city) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Postcode</span>
                                <input type="text" name="postcode" value="{{ old('postcode', $customer?->postcode) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                        </div>

                        <label class="mt-4 block">
                            <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Notes</span>
                            <textarea name="notes" rows="3" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">{{ old('notes') }}</textarea>
                        </label>
                    </div>

                    <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                        <h2 class="font-display text-xl font-semibold text-ink">Payment</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5 {{ empty($stripeConfigured) ? 'opacity-50' : '' }}">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="card"
                                    class="sr-only"
                                    {{ old('payment_method', ! empty($stripeConfigured) ? 'card' : 'store') === 'card' ? 'checked' : '' }}
                                    {{ empty($stripeConfigured) ? 'disabled' : '' }}
                                >
                                <p class="font-semibold text-ink">Pay online with Stripe</p>
                                <p class="mt-1 text-sm text-steel">
                                    @if (! empty($stripeConfigured))
                                        You’ll be redirected to Stripe’s secure checkout, then returned here.
                                    @else
                                        Online payments are not configured yet.
                                    @endif
                                </p>
                            </label>
                            <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                <input type="radio" name="payment_method" value="store" class="sr-only" {{ old('payment_method', ! empty($stripeConfigured) ? 'card' : 'store') === 'store' ? 'checked' : '' }}>
                                <p class="font-semibold text-ink">Pay on completion</p>
                                <p class="mt-1 text-sm text-steel">Pay by card when we return your repaired device.</p>
                            </label>
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-2">
                    <div class="sticky top-28 rounded-2xl border border-mist bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="font-display text-xl font-semibold text-ink">Order summary</h2>
                        <p class="mt-1 text-sm text-steel">{{ $device->name }} · {{ $device->category->name }}</p>

                        <ul class="mt-5 space-y-3 border-b border-mist pb-5">
                            @foreach ($items as $item)
                                <li class="flex items-start justify-between gap-3 text-sm">
                                    <span class="text-ink">
                                        <span aria-hidden="true">{{ $item->repair->emoji() }}</span>
                                        {{ $item->repair->name }}
                                    </span>
                                    <span class="font-semibold text-ink">£{{ number_format((float) $item->price, 0) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-steel">Subtotal</dt>
                                <dd class="font-medium text-ink">£{{ number_format($totals['subtotal'], 2) }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-mist pt-3 text-base">
                                <dt class="font-semibold text-ink">Total</dt>
                                <dd class="font-display text-xl font-bold text-accent">£{{ number_format($totals['total'], 2) }}</dd>
                            </div>
                        </dl>

                        <button type="submit" class="btn-primary mt-6 w-full">
                            Confirm & pay
                        </button>
                        <a href="{{ route('devices.show', $device->slug) }}" class="mt-3 block text-center text-sm font-medium text-steel hover:text-accent">
                            ← Change repairs
                        </a>
                    </div>
                </aside>
            </form>
        </div>
    </section>
@endsection
