@extends('layouts.app')

@section('title', 'Checkout - ' . $siteSettings->displayName())

@section('meta_description', 'Complete your repair booking checkout at ' . $siteSettings->displayName() . '.')

@section('meta_robots', 'noindex, nofollow')

@php
    $selectedAddonIds = collect(old('addon_ids', $selectedAddons->pluck('id')->all()))
        ->map(fn ($id) => (int) $id)
        ->values()
        ->all();
@endphp

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

            <form
                method="POST"
                action="{{ route('checkout.store') }}"
                class="grid gap-8 lg:grid-cols-5"
                x-data="{
                    endpoint: @js(route('checkout.addons')),
                    csrf: @js(csrf_token()),
                    catalog: @js($addons->map(fn ($addon) => [
                        'id' => $addon->id,
                        'name' => $addon->name,
                        'price' => (float) $addon->price,
                    ])->values()),
                    selectedIds: @js($selectedAddonIds),
                    repairsSubtotal: {{ (float) $totals['repairs_subtotal'] }},
                    saving: false,
                    get selectedAddons() {
                        return this.catalog.filter((addon) => this.selectedIds.includes(Number(addon.id)));
                    },
                    get totals() {
                        const addonsSubtotal = this.selectedAddons.reduce((sum, addon) => sum + Number(addon.price || 0), 0);
                        const subtotal = Math.round((this.repairsSubtotal + addonsSubtotal) * 100) / 100;
                        return { subtotal, total: subtotal };
                    },
                    isSelected(id) {
                        return this.selectedIds.includes(Number(id));
                    },
                    money(value, decimals = 2) {
                        return '£' + Number(value || 0).toFixed(decimals);
                    },
                    async toggle(id) {
                        const addonId = Number(id);
                        if (this.selectedIds.includes(addonId)) {
                            this.selectedIds = this.selectedIds.filter((value) => value !== addonId);
                        } else {
                            this.selectedIds = [...this.selectedIds, addonId];
                        }
                        this.saving = true;
                        try {
                            await fetch(this.endpoint, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': this.csrf,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: JSON.stringify({ addon_ids: this.selectedIds }),
                            });
                        } catch (e) {}
                        this.saving = false;
                    },
                }"
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
                        <div class="mt-4 space-y-3">
                            <label class="block cursor-pointer rounded-xl border-2 border-accent bg-accent/5 p-4 {{ empty($stripeConfigured) ? 'opacity-60' : '' }}" aria-current="true">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="card"
                                    class="sr-only"
                                    {{ old('payment_method', 'card') === 'card' ? 'checked' : '' }}
                                    {{ empty($stripeConfigured) ? 'disabled' : '' }}
                                >
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
                                        Online payments are not configured yet. You can still pay on completion.
                                    @endif
                                </p>
                            </label>

                            <label class="block cursor-pointer rounded-xl border-2 border-mist/70 bg-snow/80 p-4 text-steel/70 opacity-60 transition has-[:checked]:border-mist has-[:checked]:bg-snow has-[:checked]:opacity-80">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="store"
                                    class="sr-only"
                                    {{ old('payment_method', 'card') === 'store' ? 'checked' : '' }}
                                >
                                <p class="font-semibold text-steel/80">
                                    <span class="inline-flex items-center gap-2">
                                        <i class="bi bi-shop" aria-hidden="true"></i>
                                        Pay on completion
                                    </span>
                                </p>
                                <p class="mt-2 text-sm text-steel/70">Pay by card when we return your repaired device.</p>
                            </label>
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-2">
                    <div class="sticky top-28 rounded-2xl border border-mist bg-white p-5 shadow-sm sm:p-6">
                        @if ($addons->isNotEmpty())
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-accent">Optional extras</p>
                                <h2 class="mt-1 font-display text-lg font-semibold text-ink sm:text-xl">Protect Your Device</h2>
                                <p class="mt-1 text-sm text-steel">Tick to add to your booking total.</p>

                                <div class="mt-4 space-y-2.5">
                                    @foreach ($addons as $addon)
                                        <label
                                            class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border bg-snow px-3.5 py-3 transition"
                                            :class="isSelected({{ $addon->id }})
                                                ? 'border-accent bg-accent/5 ring-1 ring-accent/30'
                                                : 'border-mist hover:border-accent/40'"
                                        >
                                            <span class="flex min-w-0 items-center gap-2.5">
                                                <input
                                                    type="checkbox"
                                                    name="addon_ids[]"
                                                    value="{{ $addon->id }}"
                                                    class="h-4 w-4 shrink-0 rounded border-mist text-accent focus:ring-accent"
                                                    @checked(in_array($addon->id, $selectedAddonIds, true))
                                                    @change="toggle({{ $addon->id }})"
                                                >
                                                <span class="text-sm font-medium leading-snug text-ink">{{ $addon->name }}</span>
                                            </span>
                                            <span class="shrink-0 text-sm font-bold text-accent">£{{ number_format((float) $addon->price, 2) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-6 border-t border-dashed border-mist" aria-hidden="true"></div>
                        @endif

                        <div class="{{ $addons->isNotEmpty() ? 'pt-6' : '' }}">
                            <h2 class="font-display text-xl font-semibold text-ink">Order summary</h2>
                            <p class="mt-1 text-sm text-steel">{{ $device->name }} · {{ $device->category->name }}</p>
                        </div>

                        <ul class="mt-5 space-y-3 border-b border-mist pb-5">
                            @foreach ($items as $item)
                                <li class="flex items-start justify-between gap-3 text-sm">
                                    <span class="text-ink">
                                        <span class="mr-1.5 inline-flex text-accent" aria-hidden="true">
                                            <i class="bi {{ $item->repair->iconClass() }}"></i>
                                        </span>
                                        {{ $item->repair->name }}
                                    </span>
                                    <span class="font-semibold text-ink">£{{ number_format((float) $item->price, 0) }}</span>
                                </li>
                            @endforeach

                            <template x-for="addon in selectedAddons" :key="'a-'+addon.id">
                                <li class="flex items-start justify-between gap-3 text-sm">
                                    <span class="text-ink">
                                        <span class="mr-1.5 inline-flex text-accent" aria-hidden="true">
                                            <i class="bi bi-shield-check"></i>
                                        </span>
                                        <span x-text="addon.name"></span>
                                    </span>
                                    <span class="font-semibold text-ink" x-text="money(addon.price)"></span>
                                </li>
                            </template>
                        </ul>

                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-steel">Subtotal</dt>
                                <dd class="font-medium text-ink" x-text="money(totals.subtotal)">£{{ number_format($totals['subtotal'], 2) }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-mist pt-3 text-base">
                                <dt class="font-semibold text-ink">Total</dt>
                                <dd class="font-display text-xl font-bold text-accent" x-text="money(totals.total)">£{{ number_format($totals['total'], 2) }}</dd>
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
