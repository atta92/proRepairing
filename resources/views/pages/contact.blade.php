@extends('layouts.app')

@section('title', 'Contact us — ' . config('app.name'))

@section('meta_description', Str::limit(strip_tags($settings->contact_intro ?? 'Contact us for repairs and quotes.'), 155))

@section('content')
    <section class="bg-ink py-14 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Contact</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">Contact us</h1>
            @if ($settings->contact_intro)
                <p class="mt-3 max-w-2xl text-base text-white/70">{{ $settings->contact_intro }}</p>
            @endif
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container grid gap-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-mist bg-white p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-ink">Get in touch</h2>
                <ul class="mt-6 space-y-4 text-sm text-steel">
                    @if ($settings->contact_email)
                        <li class="flex gap-3">
                            <i class="bi bi-envelope text-accent" aria-hidden="true"></i>
                            <a href="mailto:{{ $settings->contact_email }}" class="font-medium text-ink hover:text-accent">{{ $settings->contact_email }}</a>
                        </li>
                    @endif
                    @if ($settings->contact_phone)
                        <li class="flex gap-3">
                            <i class="bi bi-telephone text-accent" aria-hidden="true"></i>
                            <a href="tel:{{ preg_replace('/\s+/', '', $settings->contact_phone) }}" class="font-medium text-ink hover:text-accent">{{ $settings->contact_phone }}</a>
                        </li>
                    @endif
                    @if ($settings->contact_hours)
                        <li class="flex gap-3">
                            <i class="bi bi-clock text-accent" aria-hidden="true"></i>
                            <span>{{ $settings->contact_hours }}</span>
                        </li>
                    @endif
                    @if ($settings->contact_address)
                        <li class="flex gap-3">
                            <i class="bi bi-geo-alt text-accent" aria-hidden="true"></i>
                            <span>{{ $settings->contact_address }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="rounded-2xl border border-mist bg-white p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-ink">Need a repair quote?</h2>
                <p class="mt-3 text-sm leading-relaxed text-steel">
                    Send device details and we’ll come back with a fixed price. Same-day repairs available for most common faults.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="button" class="btn-primary" data-quote-open>Request a quote</button>
                    <a href="{{ route('home') }}#price-checker" class="btn-ghost">Price checker</a>
                </div>
            </div>
        </div>
    </section>
@endsection
