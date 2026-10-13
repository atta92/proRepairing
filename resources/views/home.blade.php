@extends('layouts.app')

@section('title', config('app.name') . ' — Phone, Tablet & Laptop Repairs')

@section('meta_description', 'Cracked screen, worn battery or charging fault? ' . config('app.name') . ' fixes phones, tablets and laptops in-house with published prices and a 12-month warranty.')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-ink text-white">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-accent/20 blur-3xl"></div>
            <div class="absolute -right-16 bottom-0 h-80 w-80 rounded-full bg-signal/15 blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.07]" style="background-image: linear-gradient(rgba(255,255,255,.12) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.12) 1px, transparent 1px); background-size: 48px 48px;"></div>
        </div>

        <div class="site-container relative grid gap-10 py-16 lg:grid-cols-2 lg:items-center lg:gap-16 lg:py-24">
            <div>
                <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-accent/40 bg-accent/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-accent-light">
                    Trusted repair specialists
                </p>
                <h1 class="font-display text-4xl font-bold leading-tight tracking-tight sm:text-5xl lg:text-[3.25rem]">
                Professional Phone Repair Specialists Across the UK
                </h1>
                <p class="mt-4 max-w-xl text-lg text-white/75">
                    Cracked screen, worn battery, charging fault or water damage — we fix phones, tablets and laptops in-house. Walk in, or post it free from anywhere in the UK.
                </p>

                <ul class="mt-8 flex flex-wrap gap-2">
                    @foreach ([
                        '12-Month Warranty',
                        'Same-Day Repairs',
                        'Free UK Mail-In',
                        'Certified or Genuine Parts',
                        'No Fix, No Fee',
                    ] as $pill)
                        <li class="rounded-full border border-white/20 bg-white/5 px-3 py-1.5 text-xs font-medium text-white/90 sm:text-sm">
                            {{ $pill }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative scroll-mt-24" id="price-checker">
                <div class="absolute -inset-3 rounded-[1.75rem] bg-accent/20 blur-2xl" aria-hidden="true"></div>
                <div class="relative rounded-2xl bg-white p-5 text-ink shadow-2xl shadow-black/30 sm:p-7">
                    <span class="inline-flex rounded-full bg-accent px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-white">
                        Instant Price Checker
                    </span>

                    <h2 class="mt-3 font-display text-xl font-bold tracking-tight text-ink sm:text-2xl">
                        Get your repair price in 30 seconds
                    </h2>
                    <p class="mt-1.5 text-sm text-steel">
                        No email, no phone number. Pick your device and fault.
                    </p>

                    <div class="mt-5 space-y-3.5">
                        <label class="block text-left" for="pc-brand">
                            <span class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-steel">1. Choose your brand</span>
                            <select id="pc-brand" class="pc-select">
                                <option value="">Select a brand…</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="block text-left" for="pc-model">
                            <span class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-steel">2. Choose your model</span>
                            <select id="pc-model" disabled class="pc-select is-disabled">
                                <option value="">Select a brand first</option>
                            </select>
                        </label>
                        <label class="block text-left" for="pc-repair">
                            <span class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-steel">3. What needs fixing?</span>
                            <select id="pc-repair" disabled class="pc-select is-disabled">
                                <option value="">Select a model first</option>
                            </select>
                        </label>
                    </div>

                    <div
                        id="pc-out"
                        class="pc-out mt-4"
                        aria-live="polite"
                    >
                        Your price and turnaround will appear here.
                    </div>

                    <p class="mt-3 text-center text-xs leading-relaxed text-steel/80">
                        Guide prices include parts, labour, VAT, free return postage and the 12-month warranty.
                    </p>
                    <button
                        type="button"
                        class="mt-3 flex w-full items-center justify-center rounded-full border-2 border-accent px-4 py-2.5 text-sm font-semibold text-accent transition hover:bg-accent hover:text-white"
                        data-quote-open
                    >
                        Can't find your device? Request a free quote
                    </button>
                </div>
            </div>
        </div>
    </section>


    {{-- Devices from categories --}}
    <section id="devices" class="scroll-mt-24 bg-white py-16 sm:py-20">
        <div class="site-container">
            <div class="max-w-2xl">
                <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                    Repairs by device
                </h2>
                <p class="mt-3 text-base text-steel">
                    {{ $stats['categories'] }} categories · {{ $stats['devices'] }} models — diagnosed and repaired in house.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                @forelse ($categories as $category)
                    <a
                        href="{{ route('categories.show', $category->slug) }}"
                        class="group rounded-2xl border border-mist bg-snow p-4 transition hover:border-accent/40 hover:bg-white hover:shadow-md sm:p-5"
                    >
                        <p class="font-display text-base font-semibold text-ink group-hover:text-accent-dark sm:text-lg">
                            {{ $category->name }}
                        </p>
                        <p class="mt-1 text-xs text-steel sm:text-sm">
                            {{ $category->excerpt ?: ucfirst($category->type) . ' repairs' }}
                        </p>
                        <p class="mt-3 text-xs font-medium uppercase tracking-wide text-steel/70">
                            {{ $category->devices_count }} {{ Str::plural('model', $category->devices_count) }}
                        </p>
                        <p class="mt-2 text-sm font-semibold text-accent">View models →</p>
                    </a>
                @empty
                    <p class="col-span-2 text-steel">No active categories yet. Add them in the admin panel.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Shop --}}
    @if ($accessories->isNotEmpty())
        <section id="shop" class="scroll-mt-24 bg-snow py-16 sm:py-20">
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Shop
                        </h2>
                        <p class="mt-3 text-base text-steel">
                            Mobiles, accessories, tablets and more — buy online for collection or delivery.
                        </p>
                    </div>
                    <a href="{{ route('shop.index') }}" class="text-sm font-semibold text-accent hover:text-accent-dark">
                        Browse shop →
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                    @foreach ($accessories as $accessory)
                        @include('shop._card', ['product' => $accessory])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- How it works --}}
    <section id="how-it-works" class="scroll-mt-24 bg-snow py-16 sm:py-20">
        <div class="site-container">
            <div class="max-w-2xl">
                <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                    How your repair works
                </h2>
                <p class="mt-3 text-base text-steel">
                    Walk into our store, or post your device free from anywhere in the UK. Same technicians, same warranty, same fixed price.
                </p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-mist bg-white p-6 sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-wider text-accent">Free mail-in</p>
                    <ol class="mt-6 space-y-5">
                        @foreach ([
                            ['step' => '1', 'title' => 'Book online', 'text' => 'Pick your device and fault for a fixed price.'],
                            ['step' => '2', 'title' => 'Post it to us', 'text' => 'Your booking email tells you exactly where to send it.'],
                            ['step' => '3', 'title' => 'We diagnose & repair', 'text' => 'Most devices are fixed the same day they arrive.'],
                            ['step' => '4', 'title' => 'Free return delivery', 'text' => 'Tested, cleaned and posted back with warranty.'],
                        ] as $item)
                            <li class="flex gap-4">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-accent text-sm font-bold text-white">{{ $item['step'] }}</span>
                                <div>
                                    <p class="font-semibold text-ink">{{ $item['title'] }}</p>
                                    <p class="mt-0.5 text-sm text-steel">{{ $item['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="rounded-2xl border border-mist bg-white p-6 sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-wider text-signal">Walk-in</p>
                    <ol class="mt-6 space-y-5">
                        @foreach ([
                            ['step' => '1', 'title' => 'Pop in — no appointment', 'text' => 'Open Monday to Sunday. Walk-ins always welcome.'],
                            ['step' => '2', 'title' => 'Free diagnostic & quote', 'text' => 'We test in front of you and agree a fixed price first.'],
                            ['step' => '3', 'title' => 'Wait while we fix it', 'text' => 'Most screens and batteries done in 30–120 minutes.'],
                            ['step' => '4', 'title' => 'Test, pay & go', 'text' => 'Check everything works. Warranty starts as you leave.'],
                        ] as $item)
                            <li class="flex gap-4">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-signal text-sm font-bold text-white">{{ $item['step'] }}</span>
                                <div>
                                    <p class="font-semibold text-ink">{{ $item['title'] }}</p>
                                    <p class="mt-0.5 text-sm text-steel">{{ $item['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust strip --}}
    <section class="border-y border-mist bg-white py-12">
        <div class="site-container grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['icon' => 'bi-lightning-charge-fill', 'title' => 'Same-day', 'text' => 'Most screens & batteries while you wait'],
                ['icon' => 'bi-shield-check', 'title' => '12-month warranty', 'text' => 'On parts and workmanship'],
                ['icon' => 'bi-box-seam', 'title' => 'Free UK mail-in', 'text' => 'Tracked return delivery included'],
                ['icon' => 'bi-check-circle-fill', 'title' => 'No fix, no fee', 'text' => 'If we cannot repair it, you pay nothing'],
            ] as $item)
                <div class="text-center lg:text-left">
                    <i class="bi {{ $item['icon'] }} text-2xl text-accent" aria-hidden="true"></i>
                    <p class="mt-2 font-display text-lg font-semibold text-ink">{{ $item['title'] }}</p>
                    <p class="mt-1 text-sm text-steel">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Blog --}}
    @if ($posts->isNotEmpty())
        <section id="blog" class="scroll-mt-24 bg-snow py-16 sm:py-20">
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Our Repair process
                        </h2>
                        <p class="mt-3 text-base text-steel">
                        Guides written by our technicians to help you work out what's actually wrong with your device.
                        </p>
                    </div>
                    <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-accent hover:text-accent-dark">
                        View all posts →
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                    @foreach ($posts as $post)
                        @include('blog._card', ['post' => $post])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Contact CTA --}}
    <section id="contact" class="scroll-mt-24 bg-ink py-16 text-white sm:py-20">
        <div class="site-container text-center">
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">
                Tell us what’s broken
            </h2>
            <p class="mx-auto mt-3 max-w-xl text-base text-white/70">
                {{ $siteSettings->contact_intro ?: 'Older device, rare model, or a fault you can’t describe? Request a free quote and we’ll come back with a fixed price.' }}
            </p>
            @if (($siteSettings->contact_email ?? null) || ($siteSettings->contact_phone ?? null))
                <div class="mx-auto mt-6 flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm text-white/70">
                    @if ($siteSettings->contact_email)
                        <a href="mailto:{{ $siteSettings->contact_email }}" class="inline-flex items-center gap-2 transition hover:text-white">
                            <i class="bi bi-envelope text-accent-light" aria-hidden="true"></i>
                            {{ $siteSettings->contact_email }}
                        </a>
                    @endif
                    @if ($siteSettings->contact_phone)
                        <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->contact_phone) }}" class="inline-flex items-center gap-2 transition hover:text-white">
                            <i class="bi bi-telephone text-accent-light" aria-hidden="true"></i>
                            {{ $siteSettings->contact_phone }}
                        </a>
                    @endif
                </div>
            @endif
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="#price-checker" class="btn-primary">Check a price</a>
                <a href="{{ route('pages.contact') }}" class="btn-secondary">Contact us</a>
                <button type="button" class="btn-secondary" data-quote-open>Request a Quote</button>
            </div>
        </div>
    </section>
@endsection
