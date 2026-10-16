@extends('layouts.app')

@section('title', $product->name . ' - Shop - ' . $siteSettings->displayName())

@section('meta_description', $product->excerpt ?: Str::limit(strip_tags((string) $product->description), 155))

@if ($product->image_path)
    @section('og_image', asset('storage/' . ltrim($product->image_path, '/')))
@endif

@section('content')
    @php
        $hasVariants = $variants->isNotEmpty();
    @endphp

    <section
        class="bg-white"
        x-data="productVariants({
            variants: @js($variants),
            baseImage: @js($product->image_path ? asset('storage/' . $product->image_path) : null),
            basePrice: {{ (float) $product->price }},
            baseCompare: {{ $product->compare_at_price ? (float) $product->compare_at_price : 'null' }},
            hasVariants: {{ $hasVariants ? 'true' : 'false' }},
            inStock: {{ $product->isInStock() ? 'true' : 'false' }},
            productName: @js($product->name),
        })"
    >
        <div class="site-container py-4 sm:py-5">
            <nav class="text-sm text-steel" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <li><a href="{{ route('shop.index') }}" class="hover:text-accent">Shop</a></li>
                    <li aria-hidden="true" class="text-mist">/</li>
                    <li>
                        <a href="{{ route('shop.index', ['type' => $product->type]) }}" class="hover:text-accent">
                            {{ $product->type_label }}
                        </a>
                    </li>
                    <li aria-hidden="true" class="text-mist">/</li>
                    <li class="font-medium text-ink">{{ $product->name }}</li>
                </ol>
            </nav>

            @if (session('success'))
                <div class="mt-4 rounded-xl border border-accent/30 bg-accent/10 px-4 py-3 text-sm text-ink">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="site-container pb-12 lg:pb-16">
            <div class="grid gap-8 lg:grid-cols-12 lg:gap-10 xl:gap-14">
                {{-- Gallery --}}
                <div class="lg:col-span-6 xl:col-span-7">
                    <div class="sticky top-28">
                        <div class="pdp-gallery flex aspect-square items-center justify-center overflow-hidden rounded-3xl bg-[#f6f7f9]">
                            <template x-if="currentImage">
                                <img
                                    :src="currentImage"
                                    :alt="@js($product->name)"
                                    class="max-h-full max-w-full object-contain p-6 sm:p-10"
                                >
                            </template>
                            <template x-if="!currentImage">
                                <div class="flex h-full w-full flex-col items-center justify-center gap-3 text-steel/50">
                                    <i class="bi bi-phone text-6xl" aria-hidden="true"></i>
                                    <span class="text-sm">Product image</span>
                                </div>
                            </template>
                        </div>

                        @include('partials.product-rating', [
                            'average' => $averageRating,
                            'count' => $reviewsCount,
                            'link' => '#reviews',
                            'class' => 'mt-4 lg:hidden',
                            'size' => 'sm',
                        ])
                    </div>
                </div>

                {{-- Buy box --}}
                <div class="lg:col-span-6 xl:col-span-5">
                    <div class="pdp-buybox">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-steel">
                            {{ $product->type_label }} · {{ $product->category_label }}
                        </p>

                        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-[2.15rem] sm:leading-tight">
                            {{ $product->name }}
                        </h1>

                        @include('partials.product-rating', [
                            'average' => $averageRating,
                            'count' => $reviewsCount,
                            'link' => '#reviews',
                            'class' => 'mt-3',
                            'size' => 'sm',
                        ])

                        <p class="mt-2 text-sm text-steel" x-show="summaryBits.length" x-cloak>
                            <span x-text="summaryBits.join(' · ')"></span>
                        </p>

                        <div class="mt-5 flex flex-wrap items-end gap-x-3 gap-y-1">
                            <span class="font-display text-4xl font-bold tracking-tight text-ink" x-text="priceLabel"></span>
                            <span class="pb-1 text-lg text-steel/60 line-through" x-show="compareLabel" x-cloak x-text="compareLabel"></span>
                            <span
                                class="mb-1 rounded-full bg-accent/10 px-2.5 py-0.5 text-xs font-semibold text-accent-dark"
                                x-show="savingsLabel"
                                x-cloak
                                x-text="savingsLabel"
                            ></span>
                        </div>

                        @if (filled($product->excerpt))
                            <p class="mt-3 text-sm leading-relaxed text-steel">{{ $product->excerpt }}</p>
                        @endif

                        {{-- Variant pickers (Back Market style) --}}
                        <template x-if="hasVariants">
                            <div class="mt-8 space-y-7">
                                <template x-for="group in optionGroups" :key="group.key">
                                    <div>
                                        <div class="mb-3 flex items-baseline justify-between gap-3">
                                            <h2 class="text-sm font-semibold text-ink" x-text="group.label"></h2>
                                            <span class="text-sm font-medium text-steel" x-text="selected[group.key]"></span>
                                        </div>

                                        {{-- Condition-style priced cards --}}
                                        <div class="grid gap-2.5" x-show="group.style === 'cards'">
                                            <template x-for="value in group.values" :key="value">
                                                <button
                                                    type="button"
                                                    class="pdp-option-card flex w-full items-center justify-between gap-3 rounded-2xl border-2 px-4 py-3.5 text-left transition"
                                                    :class="selected[group.key] === value
                                                        ? 'border-ink bg-ink text-white'
                                                        : (isOptionAvailable(group.key, value)
                                                            ? 'border-mist bg-white text-ink hover:border-ink/40'
                                                            : 'cursor-not-allowed border-mist/70 bg-[#f8f9fa] text-steel/40')"
                                                    :disabled="!isOptionAvailable(group.key, value)"
                                                    @click="selectOption(group.key, value)"
                                                >
                                                    <span class="font-semibold" x-text="value"></span>
                                                    <span
                                                        class="text-sm font-medium"
                                                        :class="selected[group.key] === value ? 'text-white/80' : 'text-steel'"
                                                        x-text="isOptionAvailable(group.key, value) ? optionPriceLabel(group.key, value) : 'Sold out'"
                                                    ></span>
                                                </button>
                                            </template>
                                        </div>

                                        {{-- Storage / colour / other chips --}}
                                        <div class="flex flex-wrap gap-2.5" x-show="group.style !== 'cards'">
                                            <template x-for="value in group.values" :key="value">
                                                <button
                                                    type="button"
                                                    class="pdp-chip min-w-[4.5rem] rounded-full border-2 px-4 py-2.5 text-sm font-semibold transition"
                                                    :class="selected[group.key] === value
                                                        ? 'border-ink bg-ink text-white'
                                                        : (isOptionAvailable(group.key, value)
                                                            ? 'border-mist bg-white text-ink hover:border-ink/40'
                                                            : 'cursor-not-allowed border-mist/70 bg-[#f8f9fa] text-steel/40 line-through')"
                                                    :disabled="!isOptionAvailable(group.key, value)"
                                                    @click="selectOption(group.key, value)"
                                                    x-text="value"
                                                ></button>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <p class="text-sm text-signal-dark" x-show="hasVariants && !selectedVariant && allOptionsChosen" x-cloak>
                                    That combination isn’t available. Try another option.
                                </p>
                            </div>
                        </template>

                        {{-- CTA --}}
                        <form
                            method="POST"
                            action="{{ route('shop.cart.add') }}"
                            class="mt-8 space-y-4"
                            @submit="if (!canAdd) { $event.preventDefault(); return; } submitting = true"
                        >
                            @csrf
                            <input type="hidden" name="accessory_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" :value="selectedVariant?.id || ''">
                            <input type="hidden" name="quantity" :value="quantity">

                            <div class="flex flex-wrap items-center gap-3" x-show="canAdd">
                                <div class="inline-flex items-center overflow-hidden rounded-full border-2 border-mist">
                                    <button
                                        type="button"
                                        class="px-3.5 py-2.5 text-lg font-bold text-ink hover:bg-snow disabled:opacity-40"
                                        :disabled="quantity <= 1"
                                        @click="quantity = Math.max(1, quantity - 1)"
                                    >−</button>
                                    <span class="min-w-10 px-2 text-center text-sm font-semibold" x-text="quantity"></span>
                                    <button
                                        type="button"
                                        class="px-3.5 py-2.5 text-lg font-bold text-ink hover:bg-snow"
                                        @click="quantity = Math.min(20, quantity + 1)"
                                    >+</button>
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="btn-primary w-full rounded-full py-3.5 text-base"
                                x-show="canAdd"
                                :disabled="submitting"
                                :class="{ 'btn-loading': submitting }"
                            >
                                <span class="btn-spinner" x-show="submitting" x-cloak></span>
                                <span x-text="submitting ? 'Adding…' : ('Add to bag - ' + priceLabel)"></span>
                            </button>
                        </form>

                        <div class="mt-4" x-show="!canAdd" x-cloak>
                            <p
                                class="rounded-2xl border border-signal/20 bg-signal/10 px-4 py-3 text-sm text-signal-dark"
                                x-show="!inStock || (selectedVariant && !selectedVariant.in_stock)"
                            >
                                Currently out of stock. Enquire and we’ll notify you when it’s back.
                            </p>
                            <p
                                class="rounded-2xl border border-mist bg-snow px-4 py-3 text-sm text-steel"
                                x-show="hasVariants && !selectedVariant && inStock"
                            >
                                Choose your options above to continue.
                            </p>
                            <button
                                type="button"
                                class="btn-primary mt-4 w-full rounded-full"
                                data-quote-open
                                x-show="!inStock || (selectedVariant && !selectedVariant.in_stock)"
                            >
                                Enquire
                            </button>
                        </div>

                        {{-- Trust strip --}}
                        <ul class="mt-8 divide-y divide-mist rounded-2xl border border-mist bg-snow/80">
                            <li class="flex gap-3 px-4 py-3.5 text-sm text-ink">
                                <i class="bi bi-shield-check mt-0.5 text-lg text-accent" aria-hidden="true"></i>
                                <div>
                                    <p class="font-semibold">12-month warranty</p>
                                    <p class="mt-0.5 text-steel">Parts and workmanship covered on refurbished devices.</p>
                                </div>
                            </li>
                            <li class="flex gap-3 px-4 py-3.5 text-sm text-ink">
                                <i class="bi bi-truck mt-0.5 text-lg text-accent" aria-hidden="true"></i>
                                <div>
                                    <p class="font-semibold">UK delivery or free collection</p>
                                    <p class="mt-0.5 text-steel">Tracked post or pick up in store.</p>
                                </div>
                            </li>
                            <li class="flex gap-3 px-4 py-3.5 text-sm text-ink">
                                <i class="bi bi-arrow-repeat mt-0.5 text-lg text-accent" aria-hidden="true"></i>
                                <div>
                                    <p class="font-semibold">Easy returns</p>
                                    <p class="mt-0.5 text-steel">Not right? Get in touch and we’ll sort it.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Specs / about --}}
    <section class="border-t border-mist bg-[#f6f7f9] py-12 sm:py-16">
        <div class="site-container max-w-4xl">
            <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                Everything about {{ $product->name }}
            </h2>

            @if (filled($product->description) || filled($product->page_title))
                <div class="mt-6 rounded-3xl border border-mist bg-white p-5 sm:p-8">
                    @if (filled($product->page_title))
                        <h3 class="font-display text-lg font-semibold text-ink">{{ $product->page_title }}</h3>
                    @endif
                    @if (filled($product->description))
                        <p class="{{ filled($product->page_title) ? 'mt-3' : '' }} text-base leading-relaxed text-steel whitespace-pre-line">{{ $product->description }}</p>
                    @endif
                </div>
            @endif

            @if ($hasVariants)
                <div class="mt-6 overflow-hidden rounded-3xl border border-mist bg-white">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-mist">
                            @php
                                $specRows = [
                                    'Storage' => $variants->pluck('storage')->filter()->unique()->values(),
                                    'Colour' => $variants->pluck('color')->filter()->unique()->values(),
                                    'Condition' => $variants->pluck('condition')->filter()->unique()->values(),
                                    'Network' => $variants->pluck('unlock_status')->filter()->unique()->values(),
                                    'RAM' => $variants->pluck('ram')->filter()->unique()->values(),
                                    'CPU' => $variants->pluck('cpu')->filter()->unique()->values(),
                                    'Screen' => $variants->pluck('screen_size')->filter()->unique()->values(),
                                ];
                            @endphp
                            @foreach ($specRows as $label => $values)
                                @if ($values->isNotEmpty())
                                    <tr>
                                        <th class="w-1/3 bg-snow/80 px-4 py-3.5 font-semibold text-ink sm:px-6">{{ $label }}</th>
                                        <td class="px-4 py-3.5 text-steel sm:px-6">{{ $values->implode(', ') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <th class="w-1/3 bg-snow/80 px-4 py-3.5 font-semibold text-ink sm:px-6">Category</th>
                                <td class="px-4 py-3.5 text-steel sm:px-6">{{ $product->category_label }}</td>
                            </tr>
                            <tr>
                                <th class="w-1/3 bg-snow/80 px-4 py-3.5 font-semibold text-ink sm:px-6">Type</th>
                                <td class="px-4 py-3.5 text-steel sm:px-6">{{ $product->type_label }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    @include('shop._reviews')

    @if ($related->isNotEmpty())
        <section class="border-t border-mist bg-[#f6f7f9] py-12 sm:py-16">
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
