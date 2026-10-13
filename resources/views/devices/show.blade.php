@extends('layouts.app')

@section('title', $device->name . ' Repair — ' . config('app.name'))

@section('meta_description', $device->excerpt ?: 'Repair prices and options for ' . $device->name)

@section('content')
    <section class="bg-ink py-12 text-white sm:py-16">
        <div class="site-container">
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
            @if ($device->excerpt)
                <p class="mt-3 max-w-2xl text-lg text-white/75">{{ $device->excerpt }}</p>
            @endif
            <p class="mt-4 text-sm text-accent-light">
                What needs fixing? Select one or more repairs — add more and save 20%.
            </p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
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
                            >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-display text-lg font-semibold text-ink">{{ $sku->repair->name }}</p>
                                    <p class="mt-2 font-display text-2xl font-bold text-accent">
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
                                    <span id="discount-note" class="hidden text-accent"> · 20% multi-repair discount</span>
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

            @if ($device->content)
                <div class="mt-10 max-w-3xl text-steel">
                    <p>{{ $device->content }}</p>
                </div>
            @endif

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
    const discountNote = document.getElementById('discount-note');
    const bookBtn = document.getElementById('book-now-btn');

    const update = () => {
        const selected = boxes.filter((box) => box.checked);
        let subtotal = selected.reduce((sum, box) => sum + Number(box.dataset.price || 0), 0);
        const discount = selected.length >= 2 ? subtotal * 0.2 : 0;
        const total = Math.round((subtotal - discount) * 100) / 100;

        countEl.textContent = String(selected.length);
        totalEl.textContent = '£' + total.toFixed(total % 1 ? 2 : 0);
        discountNote.classList.toggle('hidden', selected.length < 2);
        bookBtn.disabled = selected.length === 0;
    };

    boxes.forEach((box) => box.addEventListener('change', update));
    update();
});
</script>
@endpush
