@if ($products->isEmpty())
    <div class="rounded-2xl border border-dashed border-mist bg-white px-6 py-14 text-center">
        <i class="bi bi-bag-x text-3xl text-steel/40" aria-hidden="true"></i>
        <p class="mt-3 font-display text-lg font-semibold text-ink">No products match</p>
        <p class="mt-1 text-sm text-steel">Try another type, category, or seller.</p>
        <button
            type="button"
            class="btn-secondary mt-5 inline-flex"
            @click="resetFilters()"
            data-no-spinner
        >
            Reset shop
        </button>
    </div>
@else
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-5">
        @foreach ($products as $product)
            @include('shop._card', ['product' => $product])
        @endforeach
    </div>

    @if ($products->hasPages())
        <div class="mt-10" data-shop-pagination>
            {{ $products->links() }}
        </div>
    @endif
@endif
