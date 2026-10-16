@extends('layouts.app')

@section('title', 'Accessories - ' . $siteSettings->displayName())

@section('meta_description', 'Cases, chargers, cables, screen protectors and more from ' . $siteSettings->displayName() . '.')

@section('content')
    <section class="site-grid-bg bg-ink py-14 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Shop</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                Accessories
            </h1>
            <p class="mt-3 max-w-2xl text-base text-white/70">
                Cases, chargers, cables and screen protectors - pick up in store.
            </p>
        </div>
    </section>

    <section class="bg-snow py-14 sm:py-16">
        <div class="site-container">
            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route('accessories.index') }}"
                    class="rounded-full px-3.5 py-1.5 text-sm font-semibold transition {{ $category === '' ? 'bg-accent text-white' : 'border border-mist bg-white text-steel hover:border-accent/40 hover:text-accent' }}"
                >
                    All
                </a>
                @foreach ($categories as $slug => $label)
                    <a
                        href="{{ route('accessories.index', ['category' => $slug]) }}"
                        class="rounded-full px-3.5 py-1.5 text-sm font-semibold transition {{ $category === $slug ? 'bg-accent text-white' : 'border border-mist bg-white text-steel hover:border-accent/40 hover:text-accent' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            @if ($accessories->isEmpty())
                <p class="mt-10 text-steel">No accessories in this category yet.</p>
            @else
                <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                    @foreach ($accessories as $accessory)
                        @include('accessories._card', ['accessory' => $accessory])
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $accessories->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
