@extends('layouts.app')

@section('title', $category->name . ' - ' . $siteSettings->displayName())

@section('meta_description', $category->description ?: $category->excerpt ?: 'Repair models and prices for ' . $category->name)

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm text-white/60">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $category->name }}</span>
            </p>
            <h1 class="mt-4 font-display text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
                {{ $category->name }}
            </h1>
            <p class="mt-4 text-sm text-accent-light">
                Choose your model to see what needs fixing
            </p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            @if (filled($category->page_title) || filled($category->description) || filled($category->excerpt))
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    @if (filled($category->page_title))
                        <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                            {{ $category->page_title }}
                        </h2>
                        <span class="mt-3 inline-block h-1 w-16 rounded-full bg-accent" aria-hidden="true"></span>
                    @endif
                    @if (filled($category->description))
                        <p class="{{ filled($category->page_title) ? 'mt-5' : '' }} text-base leading-relaxed text-steel sm:text-lg whitespace-pre-line">{{ $category->description }}</p>
                    @elseif (filled($category->excerpt))
                        <p class="{{ filled($category->page_title) ? 'mt-5' : '' }} text-base leading-relaxed text-steel sm:text-lg">{{ $category->excerpt }}</p>
                    @endif
                </div>
            @endif

            <div class="mb-8 text-center">
                <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                    Select Your {{ $category->name }} Model
                </h2>
                <span class="mt-3 inline-block h-1 w-16 rounded-full bg-accent" aria-hidden="true"></span>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-5">
                @forelse ($devices as $device)
                    <a
                        href="{{ route('devices.show', $device->slug) }}"
                        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-mist bg-white shadow-sm transition hover:border-accent/40 hover:shadow-md"
                    >
                        <div class="relative aspect-[4/3] shrink-0 overflow-hidden bg-[#eef1f4]">
                            @if ($device->imageUrl())
                                <img
                                    src="{{ $device->imageUrl() }}"
                                    alt=""
                                    class="absolute inset-0 h-full w-full object-contain object-center p-3 sm:p-4"
                                    loading="lazy"
                                >
                            @else
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                                    <i class="bi bi-phone text-3xl text-accent/70" aria-hidden="true"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p class="font-display text-lg font-semibold text-ink group-hover:text-accent-dark">
                                {{ $device->name }}
                            </p>
                            <p class="mt-2 text-sm text-steel">
                                {{ $device->excerpt ?: 'Screens, batteries, charging and more' }}
                            </p>
                            <p class="mt-4 text-xs font-medium uppercase tracking-wide text-steel/70">
                                {{ $device->device_repairs_count }} {{ Str::plural('repair', $device->device_repairs_count) }}
                            </p>
                            <p class="mt-auto pt-2 text-sm font-semibold text-accent">
                                What needs fixing →
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-steel">No models listed in this category yet.</p>
                @endforelse
            </div>

            @if ($devices->hasPages())
                <div class="mt-10">
                    {{ $devices->links() }}
                </div>
            @endif

            @include('partials.quote-cta', [
                'title' => "Can't find your {$category->name} model?",
                'text' => "If your {$category->name} isn’t listed above, request a free quotation and we’ll come back with a fixed repair price.",
            ])

            <div class="mt-8">
                <a href="{{ route('home') }}#devices" class="btn-ghost">← All categories</a>
            </div>
        </div>
    </section>
@endsection
