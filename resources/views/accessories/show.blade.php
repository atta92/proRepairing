@extends('layouts.app')

@section('title', $accessory->name . ' — ' . $siteSettings->displayName())

@section('meta_description', $accessory->excerpt ?: Str::limit(strip_tags($accessory->description), 155))

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <p class="text-sm text-white/60">
                <a href="{{ route('accessories.index') }}" class="hover:text-white">Accessories</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $accessory->name }}</span>
            </p>
            <p class="mt-4 text-sm font-semibold uppercase tracking-wider text-accent-light">
                {{ $accessory->category_label }}
            </p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                {{ $accessory->name }}
            </h1>
        </div>
    </section>

    <section class="bg-snow py-10 sm:py-14">
        <div class="site-container">
            @if (filled($accessory->page_title) || filled($accessory->description) || filled($accessory->excerpt))
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    @if (filled($accessory->page_title))
                        <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                            {{ $accessory->page_title }}
                        </h2>
                        <span class="mt-3 inline-block h-1 w-16 rounded-full bg-accent" aria-hidden="true"></span>
                    @endif
                    @if (filled($accessory->description))
                        <p class="{{ filled($accessory->page_title) ? 'mt-5' : '' }} text-base leading-relaxed text-steel sm:text-lg whitespace-pre-line">{{ $accessory->description }}</p>
                    @elseif (filled($accessory->excerpt))
                        <p class="{{ filled($accessory->page_title) ? 'mt-5' : '' }} text-base leading-relaxed text-steel sm:text-lg">{{ $accessory->excerpt }}</p>
                    @endif
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 lg:items-start">
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
                    <div class="flex flex-wrap items-baseline gap-3">
                        <span class="font-display text-3xl font-bold text-ink">{{ $accessory->formatted_price }}</span>
                        @if ($accessory->compare_at_price && $accessory->compare_at_price > $accessory->price)
                            <span class="text-lg text-steel/70 line-through">
                                £{{ number_format((float) $accessory->compare_at_price, 2) }}
                            </span>
                        @endif
                    </div>

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
