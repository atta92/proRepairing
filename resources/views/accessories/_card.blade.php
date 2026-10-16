<a
    href="{{ route('accessories.show', $accessory) }}"
    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-mist bg-white transition hover:border-accent/40 hover:shadow-md"
>
    @if ($accessory->image_path)
        <div class="aspect-square overflow-hidden bg-mist">
            <img
                src="{{ asset('storage/' . $accessory->image_path) }}"
                alt=""
                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
            >
        </div>
    @else
        <div class="flex aspect-square items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
            <i class="bi bi-bag text-3xl text-accent/70" aria-hidden="true"></i>
        </div>
    @endif

    <div class="flex flex-1 flex-col p-3.5 sm:p-5">
        <p class="text-[10px] font-semibold uppercase tracking-wider text-accent sm:text-xs">
            {{ $accessory->category_label }}
        </p>
        <h3 class="mt-1.5 font-display text-sm font-semibold leading-snug text-ink group-hover:text-accent-dark sm:mt-2 sm:text-lg">
            {{ $accessory->name }}
        </h3>
        @include('partials.product-rating', [
            'product' => $accessory,
            'class' => 'mt-1.5',
        ])
        @if ($accessory->excerpt)
            <p class="mt-2 hidden flex-1 text-sm leading-relaxed text-steel sm:block">
                {{ Str::limit($accessory->excerpt, 90) }}
            </p>
        @endif
        <div class="mt-3 flex flex-wrap items-baseline gap-2 sm:mt-4">
            <span class="font-display text-base font-bold text-ink sm:text-lg">{{ $accessory->formatted_price }}</span>
            @if ($accessory->compare_at_price && $accessory->compare_at_price > $accessory->price)
                <span class="text-xs text-steel/70 line-through sm:text-sm">
                    £{{ number_format((float) $accessory->compare_at_price, 2) }}
                </span>
            @endif
        </div>
    </div>
</a>
