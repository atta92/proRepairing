@extends('layouts.app')

@section('title', 'Account settings — ' . $siteSettings->displayName())

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Account settings</h1>
            <p class="mt-2 text-white/70">Keep your contact details up to date for bookings and deliveries.</p>
        </div>
    </section>

    <section class="bg-snow py-10 sm:py-14">
        <div class="site-container space-y-8">
            @include('account._nav')

            <form method="POST" action="{{ route('account.profile.update') }}" class="mx-auto max-w-2xl rounded-2xl border border-mist bg-white p-6 sm:p-8">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="font-display text-lg font-semibold text-ink">Profile</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="block sm:col-span-2">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Full name</span>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Phone</span>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone ?: $customer?->phone) }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                </div>

                <h2 class="mt-8 font-display text-lg font-semibold text-ink">Address</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
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

                <h2 class="mt-8 font-display text-lg font-semibold text-ink">Change password</h2>
                <p class="mt-1 text-sm text-steel">Leave blank to keep your current password.</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">New password</span>
                        <input type="password" name="password" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Confirm password</span>
                        <input type="password" name="password_confirmation" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                    </label>
                </div>

                <button type="submit" class="btn-primary mt-8">Save settings</button>
            </form>
        </div>
    </section>
@endsection
