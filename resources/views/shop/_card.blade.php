<article class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-mist bg-white transition hover:border-accent/40 hover:shadow-md">
    <a href="{{ route('shop.show', $product) }}" class="flex flex-1 flex-col">
        <div class="relative aspect-square shrink-0 overflow-hidden bg-[#eef1f4]">
            @if ($product->primaryImageUrl())
                <img
                    src="{{ $product->primaryImageUrl() }}"
                    alt=""
                    class="absolute inset-0 h-full w-full object-contain object-center p-3 sm:p-4"
                    loading="lazy"
                >
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                    <i class="bi bi-bag text-3xl text-accent/70" aria-hidden="true"></i>
                </div>
            @endif
        </div>

        <div class="flex flex-1 flex-col p-3.5 sm:p-5">
            <p class="text-[10px] font-semibold uppercase tracking-wider text-accent sm:text-xs">
                {{ $product->type_label }}
            </p>
            <h3 class="mt-1.5 font-display text-sm font-semibold leading-snug text-ink group-hover:text-accent-dark sm:mt-2 sm:text-lg">
                {{ $product->name }}
            </h3>
            @if ($product->vendor)
                <p class="mt-1 text-[11px] text-steel">
                    Sold by
                    <span class="font-medium text-ink">{{ $product->vendor->name }}</span>
                </p>
            @endif

            @include('partials.product-rating', [
                'product' => $product,
                'class' => 'mt-1.5',
                'showEmpty' => false,
            ])

            @if ($product->excerpt)
                <p class="mt-2 hidden flex-1 text-sm leading-relaxed text-steel sm:block">
                    {{ Str::limit($product->excerpt, 90) }}
                </p>
            @endif
            <div class="mt-3 flex flex-wrap items-baseline gap-2 pr-11 sm:mt-4">
                <span class="font-display text-base font-bold text-ink sm:text-lg">{{ $product->formatted_price }}</span>
                @if ($product->compare_at_price && $product->compare_at_price > $product->price)
                    <span class="text-xs text-steel/70 line-through sm:text-sm">
                        £{{ number_format((float) $product->compare_at_price, 2) }}
                    </span>
                @endif
            </div>
        </div>
    </a>

    @if ($product->isInStock() && ! $product->hasVariants())
        <button
            type="button"
            class="absolute bottom-3 right-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-accent text-white shadow-md transition hover:bg-accent-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent disabled:cursor-wait disabled:opacity-80 sm:bottom-4 sm:right-4"
            aria-label="Add {{ $product->name }} to bag"
            @click.prevent="$store.bag.addProduct({{ $product->id }})"
            :disabled="$store.bag.saving"
        >
            <span class="btn-spinner is-sm" x-show="$store.bag.addingId === {{ $product->id }}" x-cloak></span>
            <i class="bi bi-plus-lg text-base leading-none" aria-hidden="true" x-show="$store.bag.addingId !== {{ $product->id }}"></i>
        </button>
    @elseif ($product->hasVariants())
        <a
            href="{{ route('shop.show', $product) }}"
            class="absolute bottom-3 right-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-accent text-white shadow-md transition hover:bg-accent-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent sm:bottom-4 sm:right-4"
            aria-label="Choose options for {{ $product->name }}"
        >
            <i class="bi bi-sliders text-base leading-none" aria-hidden="true"></i>
        </a>
    @endif
</article>
