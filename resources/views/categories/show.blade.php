@extends('layouts.app')

@section('title', $category->name . ' — ' . config('app.name'))

@section('meta_description', $category->excerpt ?: 'Repair models and prices for ' . $category->name)

@section('content')
    <section class="bg-ink py-12 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm text-white/60">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $category->name }}</span>
            </p>
            <h1 class="mt-4 font-display text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
                {{ $category->name }}
            </h1>
            @if ($category->excerpt)
                <p class="mt-3 max-w-2xl text-lg text-white/75">{{ $category->excerpt }}</p>
            @endif
            <p class="mt-4 text-sm text-accent-light">
                Choose your model to see what needs fixing
            </p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($category->devices as $device)
                    <a
                        href="{{ route('devices.show', $device->slug) }}"
                        class="group rounded-2xl border border-mist bg-white p-5 shadow-sm transition hover:border-accent/40 hover:shadow-md"
                    >
                        <p class="font-display text-lg font-semibold text-ink group-hover:text-accent-dark">
                            {{ $device->name }}
                        </p>
                        <p class="mt-2 text-sm text-steel">
                            {{ $device->excerpt ?: 'Screens, batteries, charging and more' }}
                        </p>
                        <p class="mt-4 text-xs font-medium uppercase tracking-wide text-steel/70">
                            {{ $device->device_repairs_count }} {{ Str::plural('repair', $device->device_repairs_count) }}
                        </p>
                        <p class="mt-2 text-sm font-semibold text-accent">
                            What needs fixing →
                        </p>
                    </a>
                @empty
                    <p class="text-steel">No models listed in this category yet.</p>
                @endforelse
            </div>

            <div class="mt-10">
                <a href="{{ route('home') }}#devices" class="btn-ghost">← All categories</a>
            </div>
        </div>
    </section>
@endsection
