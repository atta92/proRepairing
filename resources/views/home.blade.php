@extends('layouts.app')

@section('title', $siteSettings->displayName() . ' - Phone, Tablet & Laptop Repairs')

@section('meta_description', $siteSettings->site_description ?: ('Cracked screen, worn battery or charging fault? ' . $siteSettings->displayName() . ' fixes phones, tablets and laptops in-house with published prices and a 12-month warranty.'))

@section('content')
    @php $homeBg = 0; @endphp

    <div
        x-data="{ ready: false }"
        x-init="setTimeout(() => ready = true, 240)"
    >
        <div x-show="!ready">
            @include('partials.skeletons.home')
        </div>

        <div x-show="ready" x-cloak>
    {{-- Hero - emojis --}}
    <section class="{{ $homeBg++ % 2 === 0 ? 'site-emoji-bg' : 'site-grid-bg' }} relative overflow-hidden bg-ink text-white">
        <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-accent/20 blur-3xl"></div>
            <div class="absolute -right-16 bottom-0 h-80 w-80 rounded-full bg-signal/15 blur-3xl"></div>
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
                    Cracked screen, worn battery, charging fault or water damage - we fix phones, tablets and laptops in-house. Post it free from anywhere in the UK.
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
                <div class="pc-snake-border site-grid-bg-soft relative rounded-2xl border border-mist bg-white p-5 text-ink shadow-2xl shadow-black/30 sm:p-7">
                    <svg class="pc-snake-line" aria-hidden="true">
                        <rect pathLength="100" rx="16" ry="16"></rect>
                    </svg>
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


    {{-- Devices from categories - lines --}}
    <section id="devices" class="{{ $homeBg++ % 2 === 0 ? 'site-emoji-bg' : 'site-grid-bg-soft' }} scroll-mt-24 bg-white py-16 sm:py-20">
        <div class="site-container">
            <div class="max-w-2xl">
                <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                    Repairs by device
                </h2>
                <p class="mt-3 text-base text-steel">
                    {{ $stats['categories'] }} categories · {{ $stats['devices'] }} models - diagnosed and repaired in house.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                @forelse ($categories as $category)
                    <a
                        href="{{ route('categories.show', $category->slug) }}"
                        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-mist bg-white transition hover:border-accent/40 hover:shadow-md"
                    >
                        <div class="relative aspect-[4/3] shrink-0 overflow-hidden bg-[#eef1f4]">
                            @if ($category->imageUrl())
                                <img
                                    src="{{ $category->imageUrl() }}"
                                    alt=""
                                    class="absolute inset-0 h-full w-full object-contain object-center p-3 sm:p-4"
                                    loading="lazy"
                                >
                            @else
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                                    <i class="bi bi-phone text-3xl text-accent/70" aria-hidden="true"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col bg-white p-4 sm:p-5">
                            <p class="font-display text-base font-semibold text-ink group-hover:text-accent-dark sm:text-lg">
                                {{ $category->name }}
                            </p>
                            <p class="mt-1 text-xs text-steel sm:text-sm">
                                {{ $category->excerpt ?: ucfirst($category->type) . ' repairs' }}
                            </p>
                            <p class="mt-3 text-xs font-medium uppercase tracking-wide text-steel/70">
                                {{ $category->devices_count }} {{ Str::plural('model', $category->devices_count) }}
                            </p>
                            <p class="mt-auto pt-2 text-sm font-semibold text-accent">View models →</p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-2 text-steel">No active categories yet. Add them in the admin panel.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Shop --}}
    @if ($accessories->isNotEmpty())
        <section id="shop" class="{{ $homeBg++ % 2 === 0 ? 'site-emoji-bg' : 'site-grid-bg-soft' }} scroll-mt-24 bg-snow py-16 sm:py-20">
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Shop
                        </h2>
                        <p class="mt-3 text-base text-steel">
                            Mobiles, accessories, tablets and more - buy online for collection or delivery.
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
    <section id="how-it-works" class="{{ $homeBg++ % 2 === 0 ? 'site-emoji-bg' : 'site-grid-bg-soft' }} scroll-mt-24 bg-snow py-16 sm:py-20">
        <div class="site-container">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-accent">Free mail-in</p>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                    How your repair works
                </h2>
                <p class="mt-3 text-base text-steel">
                    Post your device free from anywhere in the UK. Same technicians, same warranty, same fixed price.
                </p>
            </div>

            <ol class="how-steps mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4">
                @foreach ([
                    ['step' => '1', 'title' => 'Book online', 'text' => 'Pick your device and fault for a fixed price.'],
                    ['step' => '2', 'title' => 'Post it free', 'text' => 'We email a tracked label - pack and drop at any Post Office.'],
                    ['step' => '3', 'title' => 'We diagnose & repair', 'text' => 'In-house technicians fix it with genuine or certified parts.'],
                    ['step' => '4', 'title' => 'Tracked return', 'text' => 'We post it back with warranty. Most jobs turn around fast.'],
                ] as $item)
                    <li class="how-step relative text-center">
                        <span class="relative z-10 mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent text-base font-bold text-white shadow-md shadow-accent/25">
                            {{ $item['step'] }}
                        </span>
                        <p class="mt-4 font-display text-lg font-semibold text-ink">{{ $item['title'] }}</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-steel">{{ $item['text'] }}</p>
                    </li>
                @endforeach
            </ol>
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
        <section id="blog" class="{{ $homeBg++ % 2 === 0 ? 'site-emoji-bg' : 'site-grid-bg-soft' }} scroll-mt-24 bg-snow py-16 sm:py-20">
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

    {{-- Before & After --}}
    @if ($beforeAfters->isNotEmpty())
        @php
            $beforeAfterPerPage = 3;
            $beforeAfterPages = $beforeAfters->values()->chunk($beforeAfterPerPage);
            $beforeAfterPageCount = $beforeAfterPages->count();
        @endphp
        <section
            id="before-after"
            class="scroll-mt-24 bg-white py-16 sm:py-20"
            x-data="{ page: 0, pages: {{ $beforeAfterPageCount }} }"
        >
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Before &amp; after
                        </h2>
                        <p class="mt-3 text-base text-steel">
                            Real repairs from our workshop - see the difference side by side.
                        </p>
                    </div>

                    @if ($beforeAfterPageCount > 1)
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-steel/20 bg-white text-ink transition hover:border-accent hover:text-accent"
                                aria-label="Previous before and after"
                                @click="page = (page - 1 + pages) % pages"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-left text-lg" aria-hidden="true"></i>
                            </button>
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-steel/20 bg-white text-ink transition hover:border-accent hover:text-accent"
                                aria-label="Next before and after"
                                @click="page = (page + 1) % pages"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-right text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="relative mt-10">
                    <div class="overflow-hidden">
                        <div
                            class="flex w-full transition-transform duration-500 ease-out"
                            :style="`transform: translateX(-${page * 100}%)`"
                        >
                            @foreach ($beforeAfterPages as $pageItems)
                                <div class="grid w-full shrink-0 grow-0 basis-full grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6">
                                    @foreach ($pageItems as $item)
                                        @php
                                            $beforeAlt = filled($item->title) ? $item->title.' - before' : 'Repair before';
                                            $afterAlt = filled($item->title) ? $item->title.' - after' : 'Repair after';
                                        @endphp
                                        <article class="min-w-0 overflow-hidden rounded-2xl border border-mist bg-white shadow-md shadow-ink/5">
                                            <div class="grid grid-cols-2 divide-x divide-mist">
                                                <figure class="min-w-0">
                                                    <div class="bg-signal/10 px-2 py-1.5 text-center text-[11px] font-semibold uppercase tracking-wider text-signal-dark">
                                                        Before
                                                    </div>
                                                    <div
                                                        class="aspect-[4/5] cursor-zoom-in overflow-hidden bg-mist transition hover:opacity-95"
                                                        role="button"
                                                        tabindex="0"
                                                        data-no-spinner
                                                        @click="$store.lightbox.show(@js($item->beforeImageUrl()), @js($beforeAlt), 'Before')"
                                                        @keydown.enter.prevent="$store.lightbox.show(@js($item->beforeImageUrl()), @js($beforeAlt), 'Before')"
                                                    >
                                                        <img
                                                            src="{{ $item->beforeImageUrl() }}"
                                                            alt="{{ $beforeAlt }}"
                                                            class="pointer-events-none h-full w-full max-w-full object-cover"
                                                            loading="lazy"
                                                        >
                                                    </div>
                                                </figure>
                                                <figure class="min-w-0">
                                                    <div class="bg-accent/10 px-2 py-1.5 text-center text-[11px] font-semibold uppercase tracking-wider text-accent-dark">
                                                        After
                                                    </div>
                                                    <div
                                                        class="aspect-[4/5] cursor-zoom-in overflow-hidden bg-mist transition hover:opacity-95"
                                                        role="button"
                                                        tabindex="0"
                                                        data-no-spinner
                                                        @click="$store.lightbox.show(@js($item->afterImageUrl()), @js($afterAlt), 'After')"
                                                        @keydown.enter.prevent="$store.lightbox.show(@js($item->afterImageUrl()), @js($afterAlt), 'After')"
                                                    >
                                                        <img
                                                            src="{{ $item->afterImageUrl() }}"
                                                            alt="{{ $afterAlt }}"
                                                            class="pointer-events-none h-full w-full max-w-full object-cover"
                                                            loading="lazy"
                                                        >
                                                    </div>
                                                </figure>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </section>
    @endif

    {{-- Contact CTA --}}
    <section id="contact" class="{{ $homeBg++ % 2 === 0 ? 'site-emoji-bg' : 'site-grid-bg' }} scroll-mt-24 bg-ink py-16 text-white sm:py-20">
        <div class="site-container">
            <div class="overflow-hidden rounded-2xl border border-white/15 bg-white/5">
                <div class="grid lg:grid-cols-2">
                    <div class="border-b border-white/10 p-6 sm:p-8 lg:border-b-0 lg:border-r lg:border-white/10 lg:p-10">
                        <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">
                            Tell us what’s broken
                        </h2>
                        <p class="mt-3 max-w-lg text-base text-white/70">
                            {{ $siteSettings->contact_intro ?: 'Older device, rare model, or a fault you can’t describe? Request a free quote and we’ll come back with a fixed price.' }}
                        </p>
                        @if (($siteSettings->contact_email ?? null) || ($siteSettings->contact_phone ?? null))
                            <div class="mt-6 flex flex-col gap-2.5 text-sm text-white/70">
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
                    </div>

                    <div class="flex items-center justify-end p-6 sm:p-8 lg:p-10">
                        <div class="ml-auto flex flex-wrap items-center justify-end gap-3">
                            <a href="#price-checker" class="btn-primary">Check a price</a>
                            <a href="{{ route('pages.contact') }}" class="btn-secondary">Contact us</a>
                            <button type="button" class="btn-secondary" data-quote-open>Request a Quote</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>
    </div>
@endsection
