@extends('layouts.app')

@section('title', 'Shop - ' . $siteSettings->displayName())

@section('meta_description', 'Shop mobiles, accessories, tablets and more from ' . $siteSettings->displayName() . '.')

@php
    $shopCatalogConfig = [
        'endpoint' => route('shop.index', [], false),
        'seller' => $activeSellerSlug,
        'selectedTypes' => $selectedTypes,
        'selectedCategories' => $selectedCategories,
        'platformSeller' => $platformSellerSlug,
        'title' => $title,
        'total' => $products->total(),
        'types' => $types->map(fn ($label, $slug) => ['slug' => $slug, 'label' => $label])->values()->all(),
        'categories' => $categories->map(fn ($label, $slug) => ['slug' => $slug, 'label' => $label])->values()->all(),
    ];
@endphp

@section('content')
    <section class="site-grid-bg bg-ink py-14 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Shop</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                Mobiles, accessories &amp; more
            </h1>
            <p class="mt-3 max-w-2xl text-base text-white/70">
                Buy online for UK delivery. Cases, chargers, refurbished phones and tablets.
            </p>
        </div>
    </section>

    <section
        class="bg-snow py-10 sm:py-14"
        x-data="shopCatalog(@js($shopCatalogConfig))"
        @keydown.escape.window="filtersOpen = false"
    >
        <div class="site-container">
            <div x-show="!ready">
                @include('partials.skeletons.shop')
            </div>

            <div x-show="ready" x-cloak>
                {{-- Seller switcher --}}
                @if ($shopVendors->isNotEmpty())
                    <div class="shop-seller-bar">
                        <p class="shop-filter-label">Seller</p>
                        <div
                            class="shop-seller-scroller"
                            :class="{ 'has-left': sellerCanPrev, 'has-right': sellerCanNext }"
                        >
                            <span class="shop-seller-scroller__fade is-left" aria-hidden="true"></span>
                            <span class="shop-seller-scroller__fade is-right" aria-hidden="true"></span>

                            <button
                                type="button"
                                class="shop-seller-scroller__btn is-prev"
                                :disabled="!sellerCanPrev"
                                @click="scrollSellers(-1)"
                                aria-label="Previous sellers"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-left text-sm" aria-hidden="true"></i>
                            </button>
                            <button
                                type="button"
                                class="shop-seller-scroller__btn is-next"
                                :disabled="!sellerCanNext"
                                @click="scrollSellers(1)"
                                aria-label="Next sellers"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-right text-sm" aria-hidden="true"></i>
                            </button>

                            <div
                                class="shop-seller-tabs px-1"
                                role="tablist"
                                aria-label="Shop sellers"
                                x-ref="sellerTabs"
                                @scroll.passive="syncSellerScroll()"
                            >
                                @foreach ($shopVendors as $vendor)
                                    <button
                                        type="button"
                                        role="tab"
                                        class="shop-seller-chip"
                                        :class="seller === @js($vendor->slug) ? 'is-active' : ''"
                                        :aria-selected="seller === @js($vendor->slug) ? 'true' : 'false'"
                                        @click="setSeller(@js($vendor->slug))"
                                        data-no-spinner
                                    >
                                        @if ($vendor->is_platform)
                                            <span class="shop-seller-chip__icon" aria-hidden="true">
                                                <i class="bi bi-shop"></i>
                                            </span>
                                            <span>{{ $vendor->name }}</span>
                                            <span class="shop-seller-chip__badge">Own</span>
                                        @else
                                            @if ($vendor->logoUrl())
                                                <img src="{{ $vendor->logoUrl() }}" alt="" class="shop-seller-chip__logo">
                                            @else
                                                <span class="shop-seller-chip__icon is-muted" aria-hidden="true">
                                                    <i class="bi bi-bag"></i>
                                                </span>
                                            @endif
                                            <span>{{ $vendor->name }}</span>
                                        @endif
                                    </button>
                                @endforeach

                                <button
                                    type="button"
                                    role="tab"
                                    class="shop-seller-chip"
                                    :class="seller === 'all' ? 'is-active' : ''"
                                    :aria-selected="seller === 'all' ? 'true' : 'false'"
                                    @click="setSeller('all')"
                                    data-no-spinner
                                >
                                    <span class="shop-seller-chip__icon is-muted" aria-hidden="true">
                                        <i class="bi bi-grid"></i>
                                    </span>
                                    <span>All sellers</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-8 md:grid md:grid-cols-[15.5rem_minmax(0,1fr)] md:gap-6 lg:grid-cols-[16.5rem_minmax(0,1fr)] lg:gap-8 xl:grid-cols-[17.5rem_minmax(0,1fr)] xl:gap-10">
                    {{-- Mobile filter trigger --}}
                    <div class="mb-5 flex items-center justify-between gap-3 md:hidden">
                        <button
                            type="button"
                            class="shop-filter-trigger"
                            @click="filtersOpen = true"
                            data-no-spinner
                        >
                            <i class="bi bi-sliders" aria-hidden="true"></i>
                            <span>Filters</span>
                            <span class="shop-filter-trigger__count" x-show="activeFilterCount > 0" x-text="activeFilterCount" x-cloak></span>
                        </button>

                        <p class="text-sm text-steel">
                            <span x-text="total"></span>
                            <span x-text="total === 1 ? 'item' : 'items'"></span>
                        </p>
                    </div>

                    {{-- Mobile drawer backdrop --}}
                    <div
                        class="fixed inset-0 z-40 bg-ink/40 backdrop-blur-[2px] md:hidden"
                        x-show="filtersOpen"
                        x-transition.opacity
                        x-cloak
                        @click="filtersOpen = false"
                    ></div>

                    {{-- Filter panel --}}
                    <aside
                        class="shop-filter-panel"
                        :class="filtersOpen ? 'is-open' : ''"
                        aria-label="Product filters"
                    >
                        <div class="shop-filter-panel__header md:hidden">
                            <h2 class="font-display text-lg font-bold text-ink">Filters</h2>
                            <button
                                type="button"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-mist text-ink hover:border-accent hover:text-accent"
                                @click="filtersOpen = false"
                                aria-label="Close filters"
                                data-no-spinner
                            >
                                <i class="bi bi-x-lg text-sm" aria-hidden="true"></i>
                            </button>
                        </div>

                        <div class="shop-filter-panel__body">
                                <div class="hidden items-center justify-between md:flex">
                                    <h2 class="font-display text-lg font-bold text-ink">Filters</h2>
                                    <button
                                        type="button"
                                        class="text-xs font-semibold text-accent hover:text-accent-dark"
                                        x-show="hasSecondaryFilters"
                                        x-cloak
                                        @click="clearSecondaryFilters()"
                                        data-no-spinner
                                    >
                                        Reset
                                    </button>
                                </div>

                            <div class="shop-filter-group" x-show="types.length > 0" x-cloak>
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <p class="shop-filter-label !mb-0">Type</p>
                                    <button
                                        type="button"
                                        class="text-[11px] font-semibold text-accent hover:text-accent-dark"
                                        x-show="selectedTypes.length"
                                        x-cloak
                                        @click="clearTypes()"
                                        data-no-spinner
                                    >
                                        Clear
                                    </button>
                                </div>
                                <ul class="shop-filter-list" role="list">
                                    <template x-for="item in types" :key="'type-' + item.slug">
                                        <li>
                                            <button
                                                type="button"
                                                class="shop-filter-option w-full text-left"
                                                :class="isTypeSelected(item.slug) ? 'is-active' : ''"
                                                :aria-pressed="isTypeSelected(item.slug) ? 'true' : 'false'"
                                                @click="toggleType(item.slug)"
                                                data-no-spinner
                                            >
                                                <span class="shop-filter-option__check" aria-hidden="true">
                                                    <i class="bi bi-check-lg" x-show="isTypeSelected(item.slug)"></i>
                                                </span>
                                                <span x-text="item.label"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <div class="shop-filter-group" x-show="categories.length > 0" x-cloak>
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <p class="shop-filter-label !mb-0">Category</p>
                                    <button
                                        type="button"
                                        class="text-[11px] font-semibold text-accent hover:text-accent-dark"
                                        x-show="selectedCategories.length"
                                        x-cloak
                                        @click="clearCategories()"
                                        data-no-spinner
                                    >
                                        Clear
                                    </button>
                                </div>
                                <ul class="shop-filter-list" role="list">
                                    <template x-for="item in categories" :key="'cat-' + item.slug">
                                        <li>
                                            <button
                                                type="button"
                                                class="shop-filter-option w-full text-left"
                                                :class="isCategorySelected(item.slug) ? 'is-active' : ''"
                                                :aria-pressed="isCategorySelected(item.slug) ? 'true' : 'false'"
                                                @click="toggleCategory(item.slug)"
                                                data-no-spinner
                                            >
                                                <span class="shop-filter-option__check" aria-hidden="true">
                                                    <i class="bi bi-check-lg" x-show="isCategorySelected(item.slug)"></i>
                                                </span>
                                                <span x-text="item.label"></span>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="shop-filter-panel__footer md:hidden">
                            <button
                                type="button"
                                class="btn-ghost w-full"
                                x-show="activeFilterCount > 0"
                                x-cloak
                                @click="resetFilters()"
                                data-no-spinner
                            >
                                Clear all
                            </button>
                            <button
                                type="button"
                                class="btn-primary w-full"
                                @click="filtersOpen = false"
                                data-no-spinner
                            >
                                <span x-text="'Show ' + total + (total === 1 ? ' result' : ' results')"></span>
                            </button>
                        </div>
                    </aside>

                    <div class="min-w-0">
                        {{-- Results toolbar --}}
                        <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-mist pb-4">
                            <div>
                                <p class="font-display text-base font-semibold text-ink sm:text-lg" x-text="title"></p>
                                <p class="mt-0.5 text-sm text-steel" x-text="summaryText"></p>
                            </div>

                            <button
                                type="button"
                                class="hidden text-sm font-semibold text-accent hover:text-accent-dark md:inline-flex"
                                x-show="hasSecondaryFilters || seller === 'all'"
                                x-cloak
                                @click="resetFilters()"
                                data-no-spinner
                            >
                                Clear filters
                            </button>
                        </div>

                        {{-- Active chips --}}
                        <div class="mb-5 flex flex-wrap gap-2" x-show="hasSecondaryFilters" x-cloak>
                            <template x-for="chip in selectedTypeChips" :key="'chip-type-' + chip.slug">
                                <button
                                    type="button"
                                    class="shop-active-chip"
                                    @click="removeType(chip.slug)"
                                    data-no-spinner
                                >
                                    <span x-text="chip.label"></span>
                                    <i class="bi bi-x-lg text-[10px]" aria-hidden="true"></i>
                                    <span class="sr-only">Remove type filter</span>
                                </button>
                            </template>
                            <template x-for="chip in selectedCategoryChips" :key="'chip-cat-' + chip.slug">
                                <button
                                    type="button"
                                    class="shop-active-chip"
                                    @click="removeCategory(chip.slug)"
                                    data-no-spinner
                                >
                                    <span x-text="chip.label"></span>
                                    <i class="bi bi-x-lg text-[10px]" aria-hidden="true"></i>
                                    <span class="sr-only">Remove category filter</span>
                                </button>
                            </template>
                        </div>

                        <div class="relative min-h-[12rem]">
                            <div
                                class="pointer-events-none absolute inset-0 z-10 flex items-start justify-center bg-snow/60 pt-16 transition-opacity"
                                x-show="loading"
                                x-transition.opacity
                                x-cloak
                            >
                                <span class="inline-flex items-center gap-2 rounded-full border border-mist bg-white px-4 py-2 text-sm font-semibold text-ink shadow-sm">
                                    <span class="btn-spinner is-sm !static !opacity-100" aria-hidden="true"></span>
                                    Updating…
                                </span>
                            </div>

                            <div
                                x-ref="results"
                                @click="onResultsClick($event)"
                                :aria-busy="loading ? 'true' : 'false'"
                            >
                                @include('shop._results', ['products' => $products])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
