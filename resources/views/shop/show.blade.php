@extends('layouts.app')

@section('title', $product->name . ' — Shop — ' . $siteSettings->displayName())

@section('meta_description', $product->excerpt ?: Str::limit(strip_tags((string) $product->description), 155))

@if ($product->image_path)
    @section('og_image', asset('storage/' . ltrim($product->image_path, '/')))
@endif

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <p class="text-sm text-white/60">
                <a href="{{ route('shop.index') }}" class="hover:text-white">Shop</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop.index', ['type' => $product->type]) }}" class="hover:text-white">{{ $product->type_label }}</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $product->name }}</span>
            </p>
            <p class="mt-4 text-sm font-semibold uppercase tracking-wider text-accent-light">
                {{ $product->type_label }} · {{ $product->category_label }}
            </p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                {{ $product->name }}
            </h1>
        </div>
    </section>

    <section
        class="bg-snow py-10 sm:py-14"
        x-data="{ ready: false }"
        x-init="setTimeout(() => ready = true, 220)"
    >
        <div class="site-container">
            <div x-show="!ready">
                @include('partials.skeletons.shop-show')
            </div>

            <div x-show="ready" x-cloak>
            @if (filled($product->page_title) || filled($product->description) || filled($product->excerpt))
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    @if (filled($product->page_title))
                        <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                            {{ $product->page_title }}
                        </h2>
                        <span class="mt-3 inline-block h-1 w-16 rounded-full bg-accent" aria-hidden="true"></span>
                    @endif
                    @if (filled($product->description))
                        <p class="{{ filled($product->page_title) ? 'mt-5' : '' }} text-base leading-relaxed text-steel sm:text-lg whitespace-pre-line">{{ $product->description }}</p>
                    @elseif (filled($product->excerpt))
                        <p class="{{ filled($product->page_title) ? 'mt-5' : '' }} text-base leading-relaxed text-steel sm:text-lg">{{ $product->excerpt }}</p>
                    @endif
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 lg:items-start">
                <div class="overflow-hidden rounded-2xl border border-mist bg-white">
                    @if ($product->image_path)
                        <img
                            src="{{ asset('storage/' . $product->image_path) }}"
                            alt="{{ $product->name }}"
                            class="aspect-square w-full object-cover"
                        >
                    @else
                        <div class="flex aspect-square items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                            <i class="bi bi-bag text-5xl text-accent/60" aria-hidden="true"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="flex flex-wrap items-baseline gap-3">
                        <span class="font-display text-3xl font-bold text-ink">{{ $product->formatted_price }}</span>
                        @if ($product->compare_at_price && $product->compare_at_price > $product->price)
                            <span class="text-lg text-steel/70 line-through">
                                £{{ number_format((float) $product->compare_at_price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if ($product->isInStock())
                        <form
                            method="POST"
                            action="{{ route('shop.cart.add') }}"
                            class="mt-8 flex flex-wrap items-end gap-3"
                            x-data="{ submitting: false }"
                            @submit="submitting = true"
                        >
                            @csrf
                            <input type="hidden" name="accessory_id" value="{{ $product->id }}">
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Qty</span>
                                <input
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="20"
                                    class="w-20 rounded-lg border border-mist px-3 py-2.5 text-sm"
                                    :disabled="submitting"
                                >
                            </label>
                            <button type="submit" class="btn-primary" :disabled="submitting" :class="{ 'btn-loading': submitting }">
                                <span class="btn-spinner" x-show="submitting" x-cloak></span>
                                <span x-text="submitting ? 'Adding…' : 'Add to bag'"></span>
                            </button>
                            <a href="{{ route('shop.cart') }}" class="btn-ghost">View bag</a>
                        </form>
                    @else
                        <p class="mt-8 rounded-xl border border-signal/20 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                            Currently out of stock. Enquire and we’ll notify you when it’s back.
                        </p>
                        <button type="button" class="btn-primary mt-4" data-quote-open>Enquire</button>
                    @endif

                    <p class="mt-4 text-xs text-steel/80">
                        Pay online for collection or UK delivery. Simulated card checkout — ready to connect Stripe later.
                    </p>
                </div>
            </div>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-mist bg-white py-14 sm:py-16">
            <div class="site-container">
                <h2 class="font-display text-2xl font-bold tracking-tight text-ink">You may also like</h2>
                <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                    @foreach ($related as $item)
                        @include('shop._card', ['product' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
