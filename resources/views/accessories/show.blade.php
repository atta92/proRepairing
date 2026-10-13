@extends('layouts.app')

@section('title', $accessory->name . ' — ' . config('app.name'))

@section('meta_description', $accessory->excerpt ?: Str::limit(strip_tags($accessory->description), 155))

@section('content')
    <section class="bg-snow py-10 sm:py-14">
        <div class="site-container">
            <a href="{{ route('accessories.index') }}" class="text-sm font-semibold text-accent hover:text-accent-dark">
                ← All accessories
            </a>

            <div class="mt-6 grid gap-8 lg:grid-cols-2 lg:gap-12 lg:items-start">
                <div class="overflow-hidden rounded-2xl border border-mist bg-white">
                    @if ($accessory->image_path)
                        <img
                            src="{{ asset('storage/' . $accessory->image_path) }}"
                            alt="{{ $accessory->name }}"
                            class="aspect-square w-full object-cover"
                        >
                    @else
                        <div class="flex aspect-square items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                            <i class="bi bi-bag text-5xl text-accent/60" aria-hidden="true"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-accent">
                        {{ $accessory->category_label }}
                    </p>
                    <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                        {{ $accessory->name }}
                    </h1>

                    <div class="mt-4 flex flex-wrap items-baseline gap-3">
                        <span class="font-display text-3xl font-bold text-ink">{{ $accessory->formatted_price }}</span>
                        @if ($accessory->compare_at_price && $accessory->compare_at_price > $accessory->price)
                            <span class="text-lg text-steel/70 line-through">
                                £{{ number_format((float) $accessory->compare_at_price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if ($accessory->excerpt)
                        <p class="mt-4 text-base leading-relaxed text-steel">{{ $accessory->excerpt }}</p>
                    @endif

                    @if ($accessory->description)
                        <div class="mt-6 space-y-3 text-sm leading-relaxed text-steel whitespace-pre-line">
                            {{ $accessory->description }}
                        </div>
                    @endif

                    <div class="mt-8 flex flex-wrap gap-3">
                        <button type="button" class="btn-primary" data-quote-open>
                            Enquire about this item
                        </button>
                        <a href="{{ route('accessories.index', ['category' => $accessory->category]) }}" class="btn-ghost">
                            More {{ strtolower($accessory->category_label) }}
                        </a>
                    </div>

                    <p class="mt-4 text-xs text-steel/80">
                        Available for walk-in pickup or to include with a mail-in repair. Ask in store for stock and colours.
                    </p>
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
                        @include('accessories._card', ['accessory' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
