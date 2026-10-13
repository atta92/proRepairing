@extends('layouts.app')

@section('title', 'Shop checkout — ' . $siteSettings->displayName())

@section('meta_description', 'Complete your accessory order securely at ' . $siteSettings->displayName() . '.')

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-10 text-white">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Checkout</h1>
            <p class="mt-2 text-white/70">Pay online or collect and pay in store.</p>
        </div>
    </section>

    <section
        class="bg-snow py-12 sm:py-16"
        x-data="shopCheckout({
            subtotal: {{ json_encode((float) $totals['subtotal']) }},
            shipping: {{ json_encode((float) $totals['shipping_amount']) }},
            discount: {{ json_encode((float) ($totals['discount_amount'] ?? 0)) }},
            fulfilment: @js(old('fulfilment', 'collection')),
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
                            <h2 class="font-display text-xl font-semibold text-ink">Fulfilment</h2>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                    <input type="radio" name="fulfilment" value="collection" class="sr-only" {{ old('fulfilment', 'collection') === 'collection' ? 'checked' : '' }} data-fulfilment @change="syncTotals()">
                                    <p class="font-semibold text-ink">Collection</p>
                                    <p class="mt-1 text-sm text-steel">Pick up in store — free.</p>
                                </label>
                                <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                    <input type="radio" name="fulfilment" value="delivery" class="sr-only" {{ old('fulfilment') === 'delivery' ? 'checked' : '' }} data-fulfilment @change="syncTotals()">
                                    <p class="font-semibold text-ink">UK delivery</p>
                                    <p class="mt-1 text-sm text-steel">Tracked post — £3.99.</p>
                                </label>
                            </div>

                            <div class="mt-4 grid gap-4 sm:grid-cols-2" x-show="fulfilment === 'delivery'" x-cloak>
                                <label class="block sm:col-span-2">
                                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Address line 1</span>
                                    <input type="text" name="address_line_1" value="{{ old('address_line_1', $customer?->address_line_1) }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                                </label>
                                <label class="block sm:col-span-2">
                                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Address line 2</span>
                                    <input type="text" name="address_line_2" value="{{ old('address_line_2', $customer?->address_line_2) }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                                </label>
                                <label class="block">
                                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">City</span>
                                    <input type="text" name="city" value="{{ old('city', $customer?->city) }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                                </label>
                                <label class="block">
                                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Postcode</span>
                                    <input type="text" name="postcode" value="{{ old('postcode', $customer?->postcode) }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                                </label>
                            </div>
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
                                        {{ old('payment_method', $stripeConfigured ? 'card' : 'store') === 'card' ? 'checked' : '' }}
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
                                    <input type="radio" name="payment_method" value="store" class="sr-only" {{ old('payment_method', $stripeConfigured ? 'card' : 'store') === 'store' ? 'checked' : '' }}>
                                    <p class="font-semibold text-ink">Pay in store</p>
                                    <p class="mt-1 text-sm text-steel">Pay when you collect.</p>
                                </label>
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
                                        <span>{{ $line['product']->name }} × {{ $line['quantity'] }}</span>
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
                                <span x-text="submitting ? 'Processing…' : 'Place order'"></span>
                            </button>
                            <p class="mt-3 text-center text-xs text-steel/80">
                                Choosing Stripe opens Stripe’s payment page in a secure redirect.
                            </p>
                        </div>
                    </aside>
                </form>
            </div>
        </div>
    </section>
@endsection
