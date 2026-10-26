@extends('layouts.app')

@section('title', $vendor->name . ' - Shop - ' . $siteSettings->displayName())

@section('meta_description', Str::limit(strip_tags((string) ($vendor->description ?: 'Products from '.$vendor->name)), 155))

@php
    $whatsappDigits = preg_replace('/\D+/', '', (string) ($vendor->phone ?? ''));
    $locationLine = collect([
        $vendor->city,
        $vendor->postcode,
    ])->filter()->implode(' · ');
@endphp

@section('content')
    {{-- Storefront header --}}
    <section class="site-grid-bg relative overflow-hidden bg-ink text-white">
        @if ($vendor->bannerUrl())
            <div class="pointer-events-none absolute inset-0 z-0">
                <img src="{{ $vendor->bannerUrl() }}" alt="" class="h-full w-full object-cover opacity-35">
                <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/80 to-ink/50"></div>
            </div>
        @endif

        <div class="site-container relative z-[1] py-10 sm:py-12 lg:py-14">
            <p class="text-sm text-white/55">
                <a href="{{ route('shop.index') }}" class="transition hover:text-white">Shop</a>
                <span class="mx-2 text-white/30">/</span>
                <span class="text-white/85">{{ $vendor->name }}</span>
            </p>

            <div class="mt-6 flex items-start gap-4 sm:gap-5">
                @if ($vendor->logoUrl())
                    <img
                        src="{{ $vendor->logoUrl() }}"
                        alt=""
                        class="h-16 w-16 shrink-0 rounded-2xl border border-white/15 bg-white object-cover shadow-lg sm:h-20 sm:w-20"
                    >
                @else
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-accent font-display text-xl font-bold tracking-tight text-white shadow-lg sm:h-20 sm:w-20 sm:text-2xl" aria-hidden="true">
                        {{ $vendor->initials() }}
                    </div>
                @endif

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">
                            {{ $vendor->name }}
                        </h1>
                        @if ($vendor->is_platform)
                            <span class="rounded-full bg-accent/20 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-accent-light">
                                Official store
                            </span>
                        @else
                            <span class="rounded-full border border-white/15 bg-white/5 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-white/70">
                                Seller
                            </span>
                        @endif
                    </div>

                    @if ($locationLine || $vendor->address_line_1)
                        <p class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-white/65">
                            <span class="inline-flex items-center gap-1.5">
                                <i class="bi bi-geo-alt text-accent-light" aria-hidden="true"></i>
                                {{ $locationLine ?: $vendor->address_line_1 }}
                            </span>
                        </p>
                    @endif

                    @if ($vendor->description)
                        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/70 sm:text-base">
                            {{ $vendor->description }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="mt-8 grid grid-cols-3 gap-2 sm:gap-3 md:max-w-xl">
                <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center sm:px-4 sm:py-3.5">
                    <p class="font-display text-xl font-bold tracking-tight sm:text-2xl">{{ $totalListings }}</p>
                    <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-wider text-white/50 sm:text-[11px]">Listings</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center sm:px-4 sm:py-3.5">
                    <p class="font-display text-xl font-bold tracking-tight sm:text-2xl">{{ $typeCounts->count() }}</p>
                    <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-wider text-white/50 sm:text-[11px]">Categories</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center sm:px-4 sm:py-3.5">
                    <p class="font-display text-xl font-bold tracking-tight sm:text-2xl">
                        {{ $vendor->is_platform ? 'Own' : 'Live' }}
                    </p>
                    <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-wider text-white/50 sm:text-[11px]">Status</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Dashboard body --}}
    <section class="site-grid-bg-soft border-t border-mist bg-snow py-8 sm:py-10 lg:py-12">
        <div class="site-container">
            <div class="grid gap-6 lg:grid-cols-[16.5rem_minmax(0,1fr)] lg:gap-8 xl:grid-cols-[18rem_minmax(0,1fr)]">
                {{-- Sidebar --}}
                <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
                    <div class="overflow-hidden rounded-2xl border border-mist bg-white shadow-sm">
                        <div class="border-b border-mist px-4 py-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-steel">Catalog</p>
                        </div>
                        <nav class="p-2" aria-label="Shop categories">
                            <a
                                href="{{ route('shops.show', $vendor) }}"
                                class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $activeType === '' ? 'bg-ink text-white' : 'text-ink hover:bg-snow' }}"
                            >
                                <span>All products</span>
                                <span class="rounded-full px-2 py-0.5 text-xs {{ $activeType === '' ? 'bg-white/15 text-white' : 'bg-snow text-steel' }}">
                                    {{ $totalListings }}
                                </span>
                            </a>

                            @foreach (\App\Models\Accessory::TYPES as $typeSlug => $typeLabel)
                                @php $count = (int) ($typeCounts[$typeSlug] ?? 0); @endphp
                                @continue($count < 1)
                                <a
                                    href="{{ route('shops.show', ['vendor' => $vendor, 'type' => $typeSlug]) }}"
                                    class="mt-1 flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $activeType === $typeSlug ? 'bg-ink text-white' : 'text-ink hover:bg-snow' }}"
                                >
                                    <span>{{ $typeLabel }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-xs {{ $activeType === $typeSlug ? 'bg-white/15 text-white' : 'bg-snow text-steel' }}">
                                        {{ $count }}
                                    </span>
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    @if ($vendor->phone || $vendor->email || $vendor->address_line_1 || $vendor->city)
                        <div class="overflow-hidden rounded-2xl border border-mist bg-white shadow-sm">
                            <div class="border-b border-mist px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wider text-steel">Shop details</p>
                            </div>
                            <div class="space-y-3 p-4 text-sm">
                                @if ($vendor->phone)
                                    <a href="tel:{{ preg_replace('/\s+/', '', $vendor->phone) }}" class="flex items-start gap-3 text-ink transition hover:text-accent">
                                        <i class="bi bi-telephone mt-0.5 text-accent" aria-hidden="true"></i>
                                        <span>{{ $vendor->phone }}</span>
                                    </a>
                                @endif
                                @if ($vendor->email)
                                    <a href="mailto:{{ $vendor->email }}" class="flex items-start gap-3 break-all text-ink transition hover:text-accent">
                                        <i class="bi bi-envelope mt-0.5 text-accent" aria-hidden="true"></i>
                                        <span>{{ $vendor->email }}</span>
                                    </a>
                                @endif
                                @if ($vendor->address_line_1 || $vendor->city)
                                    <div class="flex items-start gap-3 text-steel">
                                        <i class="bi bi-geo-alt mt-0.5 text-accent" aria-hidden="true"></i>
                                        <span>
                                            @if ($vendor->address_line_1)
                                                {{ $vendor->address_line_1 }}@if ($vendor->address_line_2), {{ $vendor->address_line_2 }}@endif<br>
                                            @endif
                                            {{ collect([$vendor->city, $vendor->postcode])->filter()->implode(', ') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            @if ($whatsappDigits)
                                <div class="border-t border-mist p-3">
                                    <a
                                        href="https://wa.me/{{ $whatsappDigits }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn-primary w-full justify-center"
                                    >
                                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                                        Message seller
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-1 text-sm font-medium text-steel transition hover:text-accent">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        Back to marketplace
                    </a>
                </aside>

                {{-- Catalog panel --}}
                <div class="min-w-0">
                    <div class="mb-4 flex flex-wrap items-end justify-between gap-3 rounded-2xl border border-mist bg-white px-4 py-3 shadow-sm sm:px-5">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-steel">Products</p>
                            <h2 class="mt-0.5 font-display text-lg font-bold tracking-tight text-ink sm:text-xl">
                                @if ($activeType !== '')
                                    {{ \App\Models\Accessory::TYPES[$activeType] ?? 'Products' }}
                                @else
                                    All listings
                                @endif
                            </h2>
                        </div>
                        <p class="text-sm text-steel">
                            <span class="font-semibold text-ink">{{ $products->total() }}</span>
                            {{ Str::plural('item', $products->total()) }}
                            @if ($products->hasPages())
                                <span class="text-steel/60">· page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
                            @endif
                        </p>
                    </div>

                    @if ($products->isEmpty())
                        <div class="rounded-2xl border border-dashed border-mist bg-white px-6 py-16 text-center shadow-sm">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-snow text-accent">
                                <i class="bi bi-bag text-xl" aria-hidden="true"></i>
                            </div>
                            <p class="mt-4 font-display text-lg font-semibold text-ink">No products in this category</p>
                            <p class="mt-1 text-sm text-steel">Try another filter or browse the full marketplace.</p>
                            <a href="{{ route('shops.show', $vendor) }}" class="btn-secondary mt-6 inline-flex">
                                View all products
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-3 xl:gap-5">
                            @foreach ($products as $product)
                                @include('shop._card', ['product' => $product, 'hideVendor' => true])
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                            <div class="mt-8">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
