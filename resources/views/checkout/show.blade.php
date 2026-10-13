@extends('layouts.app')

@section('title', 'Checkout — ' . config('app.name'))

@section('meta_description', 'Complete your repair booking checkout at ' . config('app.name') . '.')

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="bg-ink py-10 text-white">
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
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Phone</span>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                        <h2 class="font-display text-xl font-semibold text-ink">How we’ll repair it</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                <input type="radio" name="fulfilment" value="walk_in" class="sr-only" {{ old('fulfilment', 'walk_in') === 'walk_in' ? 'checked' : '' }}>
                                <p class="font-semibold text-ink">Walk-in</p>
                                <p class="mt-1 text-sm text-steel">Bring it to the store. Most jobs same day.</p>
                            </label>
                            <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                <input type="radio" name="fulfilment" value="mail_in" class="sr-only" {{ old('fulfilment') === 'mail_in' ? 'checked' : '' }}>
                                <p class="font-semibold text-ink">Mail-in</p>
                                <p class="mt-1 text-sm text-steel">Post it free. Tracked return included.</p>
                            </label>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Date</span>
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

                        <div id="mail-fields" class="mt-4 grid gap-4 sm:grid-cols-2 {{ old('fulfilment') === 'mail_in' ? '' : 'hidden' }}">
                            <label class="block sm:col-span-2">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Address line 1</span>
                                <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                            </label>
                            <label class="block sm:col-span-2">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Address line 2</span>
                                <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
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

                        <label class="mt-4 block">
                            <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Notes</span>
                            <textarea name="notes" rows="3" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">{{ old('notes') }}</textarea>
                        </label>
                    </div>

                    <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                        <h2 class="font-display text-xl font-semibold text-ink">Payment</h2>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                <input type="radio" name="payment_method" value="card" class="sr-only" {{ old('payment_method', 'card') === 'card' ? 'checked' : '' }}>
                                <p class="font-semibold text-ink">Pay online now</p>
                                <p class="mt-1 text-sm text-steel">Secure card payment (demo — marks booking as paid).</p>
                            </label>
                            <label class="cursor-pointer rounded-xl border-2 border-mist p-4 has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                <input type="radio" name="payment_method" value="store" class="sr-only" {{ old('payment_method') === 'store' ? 'checked' : '' }}>
                                <p class="font-semibold text-ink">Pay at store / on completion</p>
                                <p class="mt-1 text-sm text-steel">Pay by card or cash when the repair is done.</p>
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
                                    <span class="text-ink">{{ $item->repair->name }}</span>
                                    <span class="font-semibold text-ink">£{{ number_format((float) $item->price, 0) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-steel">Subtotal</dt>
                                <dd class="font-medium text-ink">£{{ number_format($totals['subtotal'], 2) }}</dd>
                            </div>
                            @if ($totals['discount_amount'] > 0)
                                <div class="flex justify-between text-accent">
                                    <dt>Multi-repair discount ({{ $totals['discount_percent'] }}%)</dt>
                                    <dd>-£{{ number_format($totals['discount_amount'], 2) }}</dd>
                                </div>
                            @endif
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mailFields = document.getElementById('mail-fields');
    const radios = document.querySelectorAll('input[name="fulfilment"]');

    const sync = () => {
        const selected = document.querySelector('input[name="fulfilment"]:checked')?.value;
        mailFields?.classList.toggle('hidden', selected !== 'mail_in');
    };

    radios.forEach((radio) => radio.addEventListener('change', sync));
    sync();
});
</script>
@endpush
