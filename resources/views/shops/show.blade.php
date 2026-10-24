@extends('layouts.app')

@section('title', $vendor->name . ' - Shop - ' . $siteSettings->displayName())

@section('meta_description', Str::limit(strip_tags((string) ($vendor->description ?: 'Products from '.$vendor->name)), 155))

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm text-white/60">
                <a href="{{ route('shop.index') }}" class="hover:text-white">Shop</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $vendor->name }}</span>
            </p>
            <div class="mt-6 flex flex-wrap items-center gap-4">
                @if ($vendor->logoUrl())
                    <img src="{{ $vendor->logoUrl() }}" alt="" class="h-16 w-16 rounded-2xl border border-white/10 object-cover bg-white/5">
                @endif
                <div>
                    <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">
                        {{ $vendor->name }}
                    </h1>
                    @if ($vendor->is_platform)
                        <p class="mt-1 text-sm text-accent-light">Official store</p>
                    @endif
                </div>
            </div>
            @if ($vendor->description)
                <p class="mt-4 max-w-2xl text-base text-white/70">{{ $vendor->description }}</p>
            @endif
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            @if ($products->isEmpty())
                <p class="text-steel">No products listed yet.</p>
            @else
                <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
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
