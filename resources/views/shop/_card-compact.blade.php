<article class="group relative flex h-full flex-col overflow-hidden rounded-xl border border-mist bg-white transition hover:border-accent/40 hover:shadow-sm">
    <a href="{{ route('shop.show', $product) }}" class="flex min-h-0 flex-1 flex-col">
        <div class="relative aspect-[5/4] w-full shrink-0 overflow-hidden bg-[#eef1f4]">
            @if ($product->primaryImageUrl())
                <img
                    src="{{ $product->primaryImageUrl() }}"
                    alt=""
                    class="absolute inset-0 h-full w-full object-contain object-center p-1.5"
                    loading="lazy"
                >
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                    <i class="bi bi-bag text-lg text-accent/70" aria-hidden="true"></i>
                </div>
            @endif
        </div>

        <div class="flex flex-1 flex-col gap-0.5 p-2 pb-8">
            <p class="truncate text-[9px] font-semibold uppercase tracking-wider text-accent">
                {{ $product->type_label }}
            </p>
            <h3 class="line-clamp-2 font-display text-[11px] font-semibold leading-snug text-ink group-hover:text-accent-dark">
                {{ $product->name }}
            </h3>
            <p class="mt-auto font-display text-xs font-bold text-ink">
                {{ $product->formatted_price }}
            </p>
        </div>
    </a>

    @if ($product->isInStock() && ! $product->hasVariants())
        <button
            type="button"
            class="absolute bottom-2 right-2 z-10 inline-flex h-7 w-7 items-center justify-center rounded-full bg-accent text-white shadow-sm transition hover:bg-accent-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent disabled:cursor-wait disabled:opacity-80"
            aria-label="Add {{ $product->name }} to bag"
            @click.prevent="$store.bag.addProduct({{ $product->id }})"
            :disabled="$store.bag.saving"
            data-no-spinner
        >
            <span class="btn-spinner is-sm" x-show="$store.bag.addingId === {{ $product->id }}" x-cloak></span>
            <i class="bi bi-plus-lg text-sm leading-none" aria-hidden="true" x-show="$store.bag.addingId !== {{ $product->id }}"></i>
        </button>
    @elseif ($product->hasVariants())
        <a
            href="{{ route('shop.show', $product) }}"
            class="absolute bottom-2 right-2 z-10 inline-flex h-7 w-7 items-center justify-center rounded-full bg-accent text-white shadow-sm transition hover:bg-accent-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
            aria-label="Choose options for {{ $product->name }}"
            data-no-spinner
        >
            <i class="bi bi-sliders text-sm leading-none" aria-hidden="true"></i>
        </a>
    @endif
</article>
