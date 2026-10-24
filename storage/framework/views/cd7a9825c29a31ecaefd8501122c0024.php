<article class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-mist bg-white transition hover:border-accent/40 hover:shadow-md">
    <a href="<?php echo e(route('shop.show', $product)); ?>" class="flex flex-1 flex-col">
        <div class="relative aspect-square shrink-0 overflow-hidden bg-[#eef1f4]">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->primaryImageUrl()): ?>
                <img
                    src="<?php echo e($product->primaryImageUrl()); ?>"
                    alt=""
                    class="absolute inset-0 h-full w-full object-contain object-center p-3 sm:p-4"
                    loading="lazy"
                >
            <?php else: ?>
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                    <i class="bi bi-bag text-3xl text-accent/70" aria-hidden="true"></i>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="flex flex-1 flex-col p-3.5 sm:p-5">
            <p class="text-[10px] font-semibold uppercase tracking-wider text-accent sm:text-xs">
                <?php echo e($product->type_label); ?>

            </p>
            <h3 class="mt-1.5 font-display text-sm font-semibold leading-snug text-ink group-hover:text-accent-dark sm:mt-2 sm:text-lg">
                <?php echo e($product->name); ?>

            </h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->vendor): ?>
                <p class="mt-1 text-[11px] text-steel">
                    Sold by
                    <span class="font-medium text-ink"><?php echo e($product->vendor->name); ?></span>
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php echo $__env->make('partials.product-rating', [
                'product' => $product,
                'class' => 'mt-1.5',
                'showEmpty' => false,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->excerpt): ?>
                <p class="mt-2 hidden flex-1 text-sm leading-relaxed text-steel sm:block">
                    <?php echo e(Str::limit($product->excerpt, 90)); ?>

                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="mt-3 flex flex-wrap items-baseline gap-2 pr-11 sm:mt-4">
                <span class="font-display text-base font-bold text-ink sm:text-lg"><?php echo e($product->formatted_price); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->compare_at_price && $product->compare_at_price > $product->price): ?>
                    <span class="text-xs text-steel/70 line-through sm:text-sm">
                        £<?php echo e(number_format((float) $product->compare_at_price, 2)); ?>

                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </a>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->isInStock() && ! $product->hasVariants()): ?>
        <button
            type="button"
            class="absolute bottom-3 right-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-accent text-white shadow-md transition hover:bg-accent-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent disabled:cursor-wait disabled:opacity-80 sm:bottom-4 sm:right-4"
            aria-label="Add <?php echo e($product->name); ?> to bag"
            @click.prevent="$store.bag.addProduct(<?php echo e($product->id); ?>)"
            :disabled="$store.bag.saving"
        >
            <span class="btn-spinner is-sm" x-show="$store.bag.addingId === <?php echo e($product->id); ?>" x-cloak></span>
            <i class="bi bi-plus-lg text-base leading-none" aria-hidden="true" x-show="$store.bag.addingId !== <?php echo e($product->id); ?>"></i>
        </button>
    <?php elseif($product->hasVariants()): ?>
        <a
            href="<?php echo e(route('shop.show', $product)); ?>"
            class="absolute bottom-3 right-3 inline-flex h-9 w-9 items-center justify-center rounded-full bg-accent text-white shadow-md transition hover:bg-accent-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent sm:bottom-4 sm:right-4"
            aria-label="Choose options for <?php echo e($product->name); ?>"
        >
            <i class="bi bi-sliders text-base leading-none" aria-hidden="true"></i>
        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</article>
<?php /**PATH D:\laravel\repairing\resources\views/shop/_card.blade.php ENDPATH**/ ?>