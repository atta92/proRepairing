@extends('layouts.app')

@section('title', 'Shop — ' . config('app.name'))

@section('meta_description', 'Shop mobiles, accessories, tablets and more from ' . config('app.name') . '.')

@section('content')
    <section class="bg-ink py-14 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Shop</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                Mobiles, accessories &amp; more
            </h1>
            <p class="mt-3 max-w-2xl text-base text-white/70">
                Buy online for collection or UK delivery. Cases, chargers, refurbished phones and tablets.
            </p>
        </div>
    </section>

    <section class="bg-snow py-14 sm:py-16">
        <div class="site-container">
            <div class="flex flex-wrap gap-2.5">
                <a
                    href="{{ route('shop.index') }}"
                    class="shop-tab {{ $type === '' ? 'is-active' : '' }}"
                >
                    All
                </a>
                @foreach ($types as $slug => $label)
                    <a
                        href="{{ route('shop.index', ['type' => $slug]) }}"
                        class="shop-tab {{ $type === $slug ? 'is-active' : '' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            @if ($products->isEmpty())
                <p class="mt-10 text-steel">No products in this section yet.</p>
            @else
                <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                    @foreach ($products as $product)
                        @include('shop._card', ['product' => $product])
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
