@extends('layouts.app')

@section('title', 'Shop checkout - ' . $siteSettings->displayName())

@section('meta_description', 'Complete your accessory order securely at ' . $siteSettings->displayName() . '.')

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-10 text-white">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Checkout</h1>
            <p class="mt-2 text-white/70">UK delivery · pay securely online with Stripe.</p>
        </div>
    </section>

    <section
        class="bg-snow py-12 sm:py-16"
        x-data="shopCheckout({
            subtotal: {{ json_encode((float) $totals['subtotal']) }},
            shipping: {{ json_encode((float) $totals['shipping_amount']) }},
            discount: {{ json_encode((float) ($totals['discount_amount'] ?? 0)) }},
            fulfilment: 'delivery',
            coupon: @js($totals['coupon'] ?? null),
            applyUrl: @js(route('shop.checkout.coupon.apply')),
            removeUrl: @js(route('shop.checkout.coupon.remove')),
            csrf: @js(csrf_token()),
        })"
    >
        <div class="site-container">
            <div x-show="!ready">
                @include('partials.skeletons.checkout')
            </div>

            <div x-show="ready" x-cloak>
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('shop.checkout.store') }}"
                    class="grid gap-8 lg:grid-cols-5"
                    id="shop-checkout-form"
                    @submit="submitting = true"
                >
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
                            <h2 class="font-display text-xl font-semibold text-ink">Delivery address</h2>
                            <p class="mt-1 text-sm text-steel">UK tracked delivery - £3.99.</p>
                            <input type="hidden" name="fulfilment" value="delivery">

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
                        </div>

                        <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                            <h2 class="font-display text-xl font-semibold text-ink">Payment</h2>
                            <input type="hidden" name="payment_method" value="card">
                            <div class="mt-4 rounded-xl border-2 border-accent bg-accent/5 p-4 {{ empty($stripeConfigured) ? 'opacity-60' : '' }}" aria-current="true">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <p class="font-semibold text-ink">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="bi bi-check-circle-fill text-accent" aria-hidden="true"></i>
                                            Pay online
                                        </span>
                                    </p>
                                    <div class="flex items-center gap-2.5 text-[1.65rem] leading-none text-steel" aria-label="Accepted cards">
                                        <i class="fa-brands fa-cc-visa" title="Visa" aria-hidden="true"></i>
                                        <i class="fa-brands fa-cc-mastercard" title="Mastercard" aria-hidden="true"></i>
                                        <i class="fa-brands fa-cc-amex" title="American Express" aria-hidden="true"></i>
                                        <i class="fa-brands fa-cc-discover" title="Discover" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-steel">
                                    @if (! empty($stripeConfigured))
                                        You’ll be redirected to a secure checkout, then returned here.
                                    @else
                                        Online payments are not configured yet. Please contact us to complete your order.
                                    @endif
                                </p>
                            </div>
                            <label class="mt-4 block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Order notes (optional)</span>
                                <textarea name="notes" rows="3" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">{{ old('notes') }}</textarea>
                            </label>
                        </div>
                    </div>

                    <aside class="lg:col-span-2">
                        <div class="sticky top-28 rounded-2xl border border-mist bg-white p-5 sm:p-6">
                            <h2 class="font-display text-xl font-semibold text-ink">Order summary</h2>
                            <ul class="mt-4 space-y-3 text-sm">
                                @foreach ($lines as $line)
                                    <li class="flex justify-between gap-3 text-steel">
                                        <span>
                                            {{ $line['name'] }} × {{ $line['quantity'] }}
                                            @if ($line['options_label'])
                                                <span class="block text-xs text-steel">{{ $line['options_label'] }}</span>
                                            @endif
                                        </span>
                                        <span class="font-semibold text-ink">£{{ number_format($line['line_total'], 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <dl class="mt-4 space-y-2 border-t border-mist pt-4 text-sm">
                                <div class="flex justify-between text-steel">
                                    <dt>Subtotal</dt>
                                    <dd>£{{ number_format($totals['subtotal'], 2) }}</dd>
                                </div>
                                <div class="flex justify-between text-steel" x-show="discount > 0" x-cloak>
                                    <dt>
                                        Discount
                                        <span class="text-accent" x-text="coupon ? '(' + coupon.code + ')' : ''"></span>
                                    </dt>
                                    <dd class="font-semibold text-accent" x-text="'-' + money(discount)"></dd>
                                </div>
                                <div class="flex justify-between text-steel">
                                    <dt>Shipping</dt>
                                    <dd x-text="money(shipping)"></dd>
                                </div>
                                <div class="flex justify-between text-base font-semibold text-ink">
                                    <dt>Total</dt>
                                    <dd x-text="money(total)"></dd>
                                </div>
                            </dl>

                            <div class="mt-5 border-t border-mist pt-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-steel">Coupon code</p>
                                <div class="mt-2 flex gap-2" x-show="!coupon" x-cloak>
                                    <input
                                        type="text"
                                        x-model="couponInput"
                                        @keydown.enter.prevent="applyCoupon()"
                                        placeholder="Enter code"
                                        class="min-w-0 flex-1 rounded-lg border border-mist px-3 py-2 text-sm uppercase"
                                        :disabled="couponBusy"
                                    >
                                    <button
                                        type="button"
                                        class="btn-ghost shrink-0 px-3 py-2"
                                        :disabled="couponBusy || !couponInput"
                                        @click="applyCoupon()"
                                    >
                                        <span class="btn-spinner is-dark is-sm" x-show="couponBusy" x-cloak></span>
                                        <span x-show="!couponBusy">Apply</span>
                                    </button>
                                </div>
                                <div class="mt-2 flex items-center justify-between gap-3 rounded-lg border border-accent/30 bg-accent/5 px-3 py-2" x-show="coupon" x-cloak>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-ink" x-text="coupon?.code"></p>
                                        <p class="text-xs text-steel" x-text="coupon?.label"></p>
                                    </div>
                                    <button
                                        type="button"
                                        class="shrink-0 text-xs font-semibold text-signal hover:text-signal-dark"
                                        :disabled="couponBusy"
                                        @click="removeCoupon()"
                                    >Remove</button>
                                </div>
                                <p class="mt-2 text-xs text-signal-dark" x-show="couponError" x-text="couponError" x-cloak></p>
                                <p class="mt-2 text-xs text-accent-dark" x-show="couponMessage" x-text="couponMessage" x-cloak></p>
                            </div>
                            <button
                                type="submit"
                                class="btn-primary mt-6 w-full"
                                :class="{ 'btn-loading pointer-events-none': submitting }"
                            >
                                <span class="btn-spinner" :class="submitting ? '' : 'hidden'"></span>
                                <span class="inline-flex items-center justify-center gap-2" x-show="!submitting">
                                    <i class="bi bi-lock-fill text-sm" aria-hidden="true"></i>
                                    Pay now
                                </span>
                                <span x-show="submitting" x-cloak>Processing…</span>
                            </button>
                            <p class="mt-3 text-center text-xs font-medium text-accent">
                                You’ll be taken to a secure payment page to complete your order.
                            </p>
                        </div>
                    </aside>
                </form>
            </div>
        </div>
    </section>
@endsection
