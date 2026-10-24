<div
    x-data
    x-cloak
    @keydown.escape.window="$store.bag.close()"
>
    <div
        class="fixed inset-0 z-[80] bg-ink/50 transition-opacity duration-300"
        x-show="$store.bag.open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.bag.close()"
        aria-hidden="true"
    ></div>

    <aside
        class="fixed inset-y-0 right-0 z-[90] flex w-full max-w-md flex-col bg-white shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-label="Shopping bag"
        x-show="$store.bag.open"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        @click.stop
    >
        <div class="flex items-center justify-between border-b border-mist px-5 py-4">
            <div>
                <h2 class="font-display text-lg font-semibold text-ink">Your bag</h2>
                <p class="text-xs text-steel" x-text="$store.bag.totals.count + ' item' + ($store.bag.totals.count === 1 ? '' : 's')"></p>
            </div>
            <button
                type="button"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-mist text-ink transition hover:bg-snow"
                @click="$store.bag.close()"
                aria-label="Close bag"
            >
                <i class="bi bi-x-lg text-sm" aria-hidden="true"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4">
            <template x-if="$store.bag.lines.length === 0">
                <div class="py-16 text-center">
                    <i class="bi bi-bag text-3xl text-steel/40" aria-hidden="true"></i>
                    <p class="mt-3 text-sm text-steel">Your bag is empty.</p>
                    <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary mt-5 inline-flex" @click="$store.bag.close()">Continue shopping</a>
                </div>
            </template>

            <div class="space-y-4" x-show="$store.bag.lines.length > 0">
                <template x-for="line in $store.bag.lines" :key="line.id">
                    <div class="flex gap-3">
                        <a :href="line.url" class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-mist" @click="$store.bag.close()">
                            <template x-if="line.image">
                                <img :src="line.image" alt="" class="h-full w-full object-cover">
                            </template>
                            <template x-if="!line.image">
                                <div class="flex h-full items-center justify-center text-accent/50">
                                    <i class="bi bi-bag"></i>
                                </div>
                            </template>
                        </a>
                        <div class="min-w-0 flex-1">
                            <a :href="line.url" class="block truncate text-sm font-semibold text-ink hover:text-accent" x-text="line.name" @click="$store.bag.close()"></a>
                            <p class="mt-0.5 truncate text-xs text-steel" x-show="line.options" x-text="line.options"></p>
                            <p class="mt-0.5 text-xs text-steel" x-text="line.price_formatted"></p>
                            <div class="mt-2 flex items-center gap-2">
                                <div class="inline-flex items-center overflow-hidden rounded-md border border-mist">
                                    <button
                                        type="button"
                                        class="px-2 py-1 text-xs font-bold text-ink hover:bg-snow disabled:opacity-40"
                                        :disabled="line.quantity <= 1"
                                        @click="$store.bag.changeQty(line, line.quantity - 1)"
                                    >−</button>
                                    <span class="min-w-7 border-x border-mist px-1.5 py-1 text-center text-xs font-semibold" x-text="line.quantity"></span>
                                    <button
                                        type="button"
                                        class="px-2 py-1 text-xs font-bold text-ink hover:bg-snow disabled:opacity-40"
                                        :disabled="line.quantity >= 20"
                                        @click="$store.bag.changeQty(line, line.quantity + 1)"
                                    >+</button>
                                </div>
                                <button
                                    type="button"
                                    class="text-xs font-semibold text-signal hover:text-signal-dark disabled:opacity-40"
                                    :disabled="$store.bag.removingId === line.id"
                                    @click="$store.bag.removeLine(line)"
                                >Remove</button>
                            </div>
                        </div>
                        <p class="shrink-0 text-sm font-bold text-ink" x-text="$store.bag.money(line.line_total)"></p>
                    </div>
                </template>
            </div>
        </div>

        <div class="border-t border-mist px-5 py-4" x-show="$store.bag.lines.length > 0">
            <div class="mb-4 flex items-center justify-between text-sm">
                <span class="text-steel">Subtotal</span>
                <span class="font-display text-lg font-bold text-ink" x-text="$store.bag.money($store.bag.totals.subtotal)"></span>
            </div>
            <a
                href="<?php echo e(route('shop.checkout')); ?>"
                class="btn-primary w-full"
                :class="{ 'btn-loading pointer-events-none': $store.bag.navigating }"
                @click="$store.bag.startCheckout()"
            >
                <span class="btn-spinner" x-show="$store.bag.navigating"></span>
                <span x-text="$store.bag.navigating ? 'Loading…' : 'Checkout'"></span>
            </a>
            <a href="<?php echo e(route('shop.cart')); ?>" class="mt-3 block text-center text-sm font-semibold text-accent hover:text-accent-dark" @click="$store.bag.close()">View full bag</a>
        </div>
    </aside>
</div>
<?php /**PATH D:\laravel\repairing\resources\views/partials/bag-drawer.blade.php ENDPATH**/ ?>