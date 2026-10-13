@extends('layouts.app')

@section('title', 'Your bag — ' . config('app.name'))

@section('meta_description', 'Review items in your shopping bag at ' . config('app.name') . '.')

@section('content')
    <section class="bg-ink py-10 text-white">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Your bag</h1>
            <p class="mt-2 text-white/70">Review items before checkout.</p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            @if ($lines->isEmpty())
                <div class="rounded-2xl border border-mist bg-white p-8 text-center">
                    <p class="text-steel">Your bag is empty.</p>
                    <a href="{{ route('shop.index') }}" class="btn-primary mt-6 inline-flex">Continue shopping</a>
                </div>
            @else
                <div
                    x-data="shopCart({
                        csrf: @js(csrf_token()),
                        lines: @js($lines->map(fn ($line) => [
                            'id' => $line['product']->id,
                            'name' => $line['product']->name,
                            'type' => $line['product']->type_label,
                            'url' => route('shop.show', $line['product']),
                            'image' => $line['product']->image_path ? asset('storage/' . $line['product']->image_path) : null,
                            'price' => (float) $line['product']->price,
                            'price_formatted' => $line['product']->formatted_price,
                            'quantity' => (int) $line['quantity'],
                            'line_total' => (float) $line['line_total'],
                            'update_url' => route('shop.cart.update', $line['product']->id),
                            'remove_url' => route('shop.cart.remove', $line['product']->id),
                        ])->values()),
                        totals: @js($totals),
                    })"
                >
                    <div x-show="!ready">
                        @include('partials.skeletons.cart')
                    </div>

                    <div class="grid gap-8 lg:grid-cols-5" x-show="ready" x-cloak>
                        <div class="space-y-4 lg:col-span-3">
                            <template x-for="line in lines" :key="line.id">
                                <div class="flex gap-4 rounded-2xl border border-mist bg-white p-4 sm:p-5">
                                    <a :href="line.url" class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-mist sm:h-24 sm:w-24">
                                        <template x-if="line.image">
                                            <img :src="line.image" alt="" class="h-full w-full object-cover">
                                        </template>
                                        <template x-if="!line.image">
                                            <div class="flex h-full items-center justify-center text-accent/50">
                                                <i class="bi bi-bag text-xl"></i>
                                            </div>
                                        </template>
                                    </a>
                                    <div class="min-w-0 flex-1">
                                        <a :href="line.url" class="font-display font-semibold text-ink hover:text-accent" x-text="line.name"></a>
                                        <p class="mt-0.5 text-xs uppercase tracking-wider text-steel/70" x-text="line.type"></p>
                                        <p class="mt-2 font-semibold text-ink" x-text="line.price_formatted"></p>

                                        <div class="mt-3 flex flex-wrap items-center gap-3">
                                            <div class="flex items-center gap-2">
                                                <label class="text-xs font-semibold uppercase tracking-wider text-steel">Qty</label>
                                                <div class="inline-flex items-center overflow-hidden rounded-lg border border-mist">
                                                    <button
                                                        type="button"
                                                        class="px-2.5 py-1.5 text-sm font-bold text-ink hover:bg-mist disabled:opacity-40"
                                                        :disabled="line.quantity <= 1"
                                                        @click="changeQty(line, line.quantity - 1)"
                                                    >−</button>
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        max="20"
                                                        class="w-12 border-x border-mist bg-white px-1 py-1.5 text-center text-sm outline-none"
                                                        x-model.number="line.quantity"
                                                        @change="changeQty(line, line.quantity)"
                                                    >
                                                    <button
                                                        type="button"
                                                        class="px-2.5 py-1.5 text-sm font-bold text-ink hover:bg-mist disabled:opacity-40"
                                                        :disabled="line.quantity >= 20"
                                                        @click="changeQty(line, line.quantity + 1)"
                                                    >+</button>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                class="text-sm font-semibold text-signal hover:text-signal-dark disabled:opacity-40"
                                                :disabled="removingId === line.id"
                                                @click="removeLine(line)"
                                            >Remove</button>
                                        </div>
                                    </div>
                                    <p class="shrink-0 font-display font-bold text-ink" x-text="money(line.line_total)"></p>
                                </div>
                            </template>
                        </div>

                        <aside class="lg:col-span-2">
                            <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
                                <h2 class="font-display text-xl font-semibold text-ink">Summary</h2>
                                <dl class="mt-4 space-y-2 text-sm">
                                    <div class="flex justify-between text-steel">
                                        <dt>Subtotal</dt>
                                        <dd class="font-semibold text-ink" x-text="money(totals.subtotal)"></dd>
                                    </div>
                                    <div class="flex justify-between text-steel">
                                        <dt>Items</dt>
                                        <dd x-text="totals.count"></dd>
                                    </div>
                                </dl>
                                <p class="mt-3 text-xs text-steel/80">Delivery (£3.99) or free collection chosen at checkout.</p>
                                <a
                                    href="{{ route('shop.checkout') }}"
                                    class="btn-primary mt-6 w-full"
                                    :class="{ 'pointer-events-none opacity-50': lines.length === 0, 'btn-loading': navigating }"
                                    @click="if (lines.length) navigating = true"
                                >
                                    <span class="btn-spinner" x-show="navigating"></span>
                                    <span x-text="navigating ? 'Loading…' : 'Checkout'"></span>
                                </a>
                                <a href="{{ route('shop.index') }}" class="mt-3 block text-center text-sm font-semibold text-accent hover:text-accent-dark">Continue shopping</a>
                            </div>
                        </aside>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
