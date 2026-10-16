@extends('layouts.app')

@section('title', 'Contact us - ' . $settings->displayName())

@section('meta_description', Str::limit(strip_tags($settings->contact_intro ?? 'Contact us for repairs and quotes.'), 155))

@section('content')
    <section class="site-grid-bg bg-ink py-14 text-white sm:py-16">
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

                @include('partials.social-links', [
                    'siteSettings' => $settings,
                    'wrapClass' => 'mt-6 flex flex-wrap gap-2',
                    'linkClass' => 'inline-flex h-9 w-9 items-center justify-center rounded-lg border border-mist text-steel transition hover:border-accent hover:bg-accent/10 hover:text-accent',
                ])

                <div class="mt-8 border-t border-mist pt-6">
                    <h3 class="font-display text-base font-semibold text-ink">Need a repair quote?</h3>
                    <p class="mt-2 text-sm leading-relaxed text-steel">
                        Send device details and we’ll come back with a fixed price.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <button type="button" class="btn-primary" data-quote-open data-no-spinner>Request a quote</button>
                        <a href="{{ route('home') }}#price-checker" class="btn-ghost">Price checker</a>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-mist bg-white p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-ink">Send a message</h2>
                <p class="mt-2 text-sm text-steel">
                    Questions about repairs, shop orders, or anything else - drop us a note and we’ll reply soon.
                </p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                        <ul class="list-disc space-y-1 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('pages.contact.store') }}"
                    class="mt-6 space-y-4"
                    x-data="{ submitting: false }"
                    @submit="submitting = true"
                >
                    @csrf

                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Name</span>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            maxlength="255"
                            class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                            placeholder="Your name"
                        >
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                                placeholder="you@example.com"
                            >
                        </label>

                        <label class="block">
                            <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Phone <span class="font-normal normal-case tracking-normal text-steel/70">(optional)</span></span>
                            <input
                                type="tel"
                                name="phone"
                                value="{{ old('phone') }}"
                                maxlength="50"
                                class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                                placeholder="Phone number"
                            >
                        </label>
                    </div>

                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Subject <span class="font-normal normal-case tracking-normal text-steel/70">(optional)</span></span>
                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            maxlength="255"
                            class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                            placeholder="What’s this about?"
                        >
                    </label>

                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Message</span>
                        <textarea
                            name="message"
                            rows="5"
                            required
                            maxlength="5000"
                            class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                            placeholder="How can we help?"
                        >{{ old('message') }}</textarea>
                    </label>

                    <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="submitting" :class="{ 'btn-loading': submitting }">
                        <span class="btn-spinner" x-show="submitting" x-cloak></span>
                        <span x-text="submitting ? 'Sending…' : 'Send message'"></span>
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
