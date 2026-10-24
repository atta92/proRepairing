@extends('layouts.app')

@section('title', 'Become a seller - ' . $siteSettings->displayName())

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-16">
        <div class="site-container max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Sellers</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">Sell on our marketplace</h1>
            <p class="mt-3 text-white/70">
                Register your shop. Once approved, you can list products and manage your orders. A platform commission applies to third-party sales — our own shop is separate and commission-free.
            </p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container max-w-2xl">
            <form method="POST" action="{{ route('seller.register.store') }}" class="space-y-5 rounded-2xl border border-mist bg-white p-6 sm:p-8" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                        <ul class="list-disc space-y-1 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block sm:col-span-2">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Your name</span>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Phone</span>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Password</span>
                        <input type="password" name="password" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Confirm password</span>
                        <input type="password" name="password_confirmation" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                </div>

                <hr class="border-mist">

                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Shop name</span>
                    <input type="text" name="shop_name" value="{{ old('shop_name') }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">About your shop</span>
                    <textarea name="description" rows="3" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">{{ old('description') }}</textarea>
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Payout details</span>
                    <textarea name="payout_details" rows="2" placeholder="Bank / PayPal notes for when we pay you" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">{{ old('payout_details') }}</textarea>
                </label>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block sm:col-span-2">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Address</span>
                        <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">City</span>
                        <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Postcode</span>
                        <input type="text" name="postcode" value="{{ old('postcode') }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="submitting" :class="{ 'btn-loading': submitting }">
                    <span class="btn-spinner" x-show="submitting" x-cloak></span>
                    <span x-text="submitting ? 'Submitting…' : 'Apply to sell'"></span>
                </button>
            </form>
        </div>
    </section>
@endsection
