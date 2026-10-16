@extends('layouts.app')

@section('title', $device->name . ' Repair - ' . $siteSettings->displayName())

@php
    $devicePageDescription = $device->description ?: $device->content ?: $device->excerpt;
@endphp

@section('meta_description', Str::limit(strip_tags((string) ($devicePageDescription ?: 'Repair prices and options for ' . $device->name)), 155))

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-16">
        <div class="site-container">
            <div class="flex flex-col gap-8 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-sm text-white/60">
                        <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('categories.show', $device->category->slug) }}" class="hover:text-white">{{ $device->category->name }}</a>
                        <span class="mx-2">/</span>
                        <span class="text-white">{{ $device->name }}</span>
                    </p>
                    <h1 class="mt-4 font-display text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
                        {{ $device->name }} Repair
                    </h1>
                    <p class="mt-4 text-sm text-accent-light">
                        What needs fixing? Select one or more repairs.
                    </p>
                </div>
                @if ($device->imageUrl())
                    <div class="mx-auto w-36 shrink-0 overflow-hidden rounded-2xl border border-white/10 bg-white/5 sm:mx-0 sm:w-44">
                        <img
                            src="{{ $device->imageUrl() }}"
                            alt="{{ $device->name }}"
                            class="aspect-square w-full object-cover"
                        >
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            @if (filled($device->page_title) || filled($devicePageDescription))
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    @if (filled($device->page_title))
                        <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                            {{ $device->page_title }}
                        </h2>
                        <span class="mt-3 inline-block h-1 w-16 rounded-full bg-accent" aria-hidden="true"></span>
                    @endif
                    @if (filled($devicePageDescription))
                        <p class="{{ filled($device->page_title) ? 'mt-5' : '' }} text-base leading-relaxed text-steel sm:text-lg whitespace-pre-line">{{ $devicePageDescription }}</p>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('devices.book', $device->slug) }}" id="repair-book-form">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($device->deviceRepairs as $sku)
                        <label class="group relative cursor-pointer rounded-2xl border-2 border-mist bg-white p-5 shadow-sm transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                            <input
                                type="checkbox"
                                name="device_repair_ids[]"
                                value="{{ $sku->id }}"
                                class="sr-only"
                                data-price="{{ $sku->price }}"
                                @checked((int) request('repair') === (int) $sku->id)
                            >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-accent/10 text-2xl leading-none" aria-hidden="true">
                                            {{ $sku->repair->emoji() }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-display text-lg font-semibold text-ink">{{ $sku->repair->name }}</p>
                                            @if (filled($sku->repair->description))
                                                <p class="mt-1.5 text-sm leading-relaxed text-steel">{{ $sku->repair->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="mt-3 font-display text-2xl font-bold text-accent">
                                        £{{ number_format((float) $sku->price, 0) }}
                                    </p>
                                    <p class="mt-2 text-sm text-steel">
                                        {{ $sku->turnaround ?: 'Same day' }}
                                        · {{ $sku->warranty_months }}-month warranty
                                    </p>
                                </div>
                                <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 border-mist text-transparent group-has-[:checked]:border-accent group-has-[:checked]:bg-accent group-has-[:checked]:text-white">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </label>
                    @empty
                        <p class="text-steel">No active repairs listed for this device yet.</p>
                    @endforelse
                </div>

                @if ($device->deviceRepairs->isNotEmpty())
                    <div class="sticky bottom-4 z-20 mt-8 rounded-2xl border border-mist bg-white/95 p-4 shadow-lg backdrop-blur sm:p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-steel">
                                    <span id="selected-count">0</span> selected
                                </p>
                                <p class="font-display text-2xl font-bold text-ink">
                                    Total <span id="selected-total">£0</span>
                                </p>
                            </div>
                            <button type="submit" class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto" id="book-now-btn" disabled>
                                Book Now → Checkout
                            </button>
                        </div>
                    </div>
                @endif
            </form>

            @if (filled($device->content) && filled($device->description) && $device->content !== $device->description)
                <div class="mt-10 max-w-3xl text-steel whitespace-pre-line">
                    <p>{{ $device->content }}</p>
                </div>
            @endif

            @include('partials.quote-cta', [
                'title' => "Can't see the right repair for {$device->name}?",
                'text' => 'Unusual fault, water damage, or a repair not listed? Request a free quotation and we’ll confirm a fixed price.',
            ])

            <div class="mt-8">
                <a href="{{ route('categories.show', $device->category->slug) }}" class="btn-ghost">
                    ← All {{ $device->category->name }} models
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('repair-book-form');
    if (!form) return;

    const boxes = [...form.querySelectorAll('input[name="device_repair_ids[]"]')];
    const countEl = document.getElementById('selected-count');
    const totalEl = document.getElementById('selected-total');
    const bookBtn = document.getElementById('book-now-btn');

    const update = () => {
        const selected = boxes.filter((box) => box.checked);
        const total = selected.reduce((sum, box) => sum + Number(box.dataset.price || 0), 0);

        countEl.textContent = String(selected.length);
        totalEl.textContent = '£' + total.toFixed(total % 1 ? 2 : 0);
        if (bookBtn) {
            bookBtn.disabled = selected.length === 0;
        }
    };

    boxes.forEach((box) => box.addEventListener('change', update));
    update();
});
</script>
@endpush
