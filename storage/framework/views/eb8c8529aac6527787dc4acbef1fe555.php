

<?php $__env->startSection('title', $device->name . ' Repair - ' . $siteSettings->displayName()); ?>

<?php
    $devicePageDescription = $device->description ?: $device->content ?: $device->excerpt;
?>

<?php $__env->startSection('meta_description', Str::limit(strip_tags((string) ($devicePageDescription ?: 'Repair prices and options for ' . $device->name)), 155)); ?>

<?php $__env->startSection('content'); ?>
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-16">
        <div class="site-container">
            <div class="flex flex-col gap-8 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-sm text-white/60">
                        <a href="<?php echo e(route('home')); ?>" class="hover:text-white">Home</a>
                        <span class="mx-2">/</span>
                        <a href="<?php echo e(route('categories.show', $device->category->slug)); ?>" class="hover:text-white"><?php echo e($device->category->name); ?></a>
                        <span class="mx-2">/</span>
                        <span class="text-white"><?php echo e($device->name); ?></span>
                    </p>
                    <h1 class="mt-4 font-display text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
                        <?php echo e($device->name); ?> Repair
                    </h1>
                    <p class="mt-4 text-sm text-accent-light">
                        What needs fixing? Select one or more repairs.
                    </p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->imageUrl()): ?>
                    <div class="mx-auto w-36 shrink-0 overflow-hidden rounded-2xl border border-white/10 bg-white/5 sm:mx-0 sm:w-44">
                        <img
                            src="<?php echo e($device->imageUrl()); ?>"
                            alt="<?php echo e($device->name); ?>"
                            class="aspect-square w-full object-cover"
                        >
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($device->page_title) || filled($devicePageDescription)): ?>
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($device->page_title)): ?>
                        <h2 class="font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                            <?php echo e($device->page_title); ?>

                        </h2>
                        <span class="mt-3 inline-block h-1 w-16 rounded-full bg-accent" aria-hidden="true"></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($devicePageDescription)): ?>
                        <p class="<?php echo e(filled($device->page_title) ? 'mt-5' : ''); ?> text-base leading-relaxed text-steel sm:text-lg whitespace-pre-line"><?php echo e($devicePageDescription); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-6 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('devices.book', $device->slug)); ?>" id="repair-book-form">
                <?php echo csrf_field(); ?>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $device->deviceRepairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sku): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="group relative cursor-pointer rounded-2xl border-2 border-mist bg-white p-5 shadow-sm transition has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                            <input
                                type="checkbox"
                                name="device_repair_ids[]"
                                value="<?php echo e($sku->id); ?>"
                                class="sr-only"
                                data-price="<?php echo e($sku->price); ?>"
                                <?php if((int) request('repair') === (int) $sku->id): echo 'checked'; endif; ?>
                            >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start gap-3">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-accent/10 text-lg text-accent" aria-hidden="true">
                                            <i class="bi <?php echo e($sku->repair->iconClass()); ?>"></i>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-display text-lg font-semibold text-ink"><?php echo e($sku->repair->name); ?></p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($sku->repair->description)): ?>
                                                <p class="mt-1.5 text-sm leading-relaxed text-steel"><?php echo e($sku->repair->description); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="mt-3 font-display text-2xl font-bold text-accent">
                                        £<?php echo e(number_format((float) $sku->price, 0)); ?>

                                    </p>
                                    <p class="mt-2 text-sm text-steel">
                                        <?php echo e($sku->turnaround ?: 'Same day'); ?>

                                        · <?php echo e($sku->warranty_months); ?>-month warranty
                                    </p>
                                </div>
                                <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 border-mist text-transparent group-has-[:checked]:border-accent group-has-[:checked]:bg-accent group-has-[:checked]:text-white">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-steel">No active repairs listed for this device yet.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($device->deviceRepairs->isNotEmpty()): ?>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </form>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($device->content) && filled($device->description) && $device->content !== $device->description): ?>
                <div class="mt-10 max-w-3xl text-steel whitespace-pre-line">
                    <p><?php echo e($device->content); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recommendedProducts->isNotEmpty()): ?>
                <div class="mt-12">
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div class="max-w-2xl">
                            <p class="text-sm font-semibold uppercase tracking-wider text-accent">Recommended</p>
                            <h2 class="mt-1 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                                Shop accessories for your <?php echo e($device->name); ?>

                            </h2>
                            <p class="mt-2 text-sm text-steel">
                                Our own shop picks first, then trusted sellers.
                            </p>
                        </div>
                        <a href="<?php echo e(route('shop.index')); ?>" class="text-sm font-semibold text-accent hover:text-accent-dark">
                            See more →
                        </a>
                    </div>

                    <div class="relative mt-8" data-product-scroller>
                        <button
                            type="button"
                            class="absolute left-1 top-1/2 z-20 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-mist bg-white text-ink shadow-md transition hover:border-accent hover:text-accent disabled:pointer-events-none disabled:opacity-0 sm:left-0"
                            data-scroller-prev
                            aria-label="Previous products"
                            data-no-spinner
                        >
                            <i class="bi bi-chevron-left text-base" aria-hidden="true"></i>
                        </button>
                        <button
                            type="button"
                            class="absolute right-1 top-1/2 z-20 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-mist bg-white text-ink shadow-md transition hover:border-accent hover:text-accent disabled:pointer-events-none disabled:opacity-0 sm:right-0"
                            data-scroller-next
                            aria-label="Next products"
                            data-no-spinner
                        >
                            <i class="bi bi-chevron-right text-base" aria-hidden="true"></i>
                        </button>

                        <div class="overflow-hidden px-8 sm:px-10">
                            <div
                                class="product-scroller-track flex items-stretch gap-3 overflow-x-auto overflow-y-hidden scroll-smooth snap-x snap-mandatory sm:gap-4"
                                data-scroller-track
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recommendedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="product-scroller-item shrink-0 snap-start">
                                        <?php echo $__env->make('shop._card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php echo $__env->make('partials.quote-cta', [
                'title' => "Can't see the right repair for {$device->name}?",
                'text' => 'Unusual fault, water damage, or a repair not listed? Request a free quotation and we’ll confirm a fixed price.',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="mt-8">
                <a href="<?php echo e(route('categories.show', $device->category->slug)); ?>" class="btn-ghost">
                    ← All <?php echo e($device->category->name); ?> models
                </a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
    .product-scroller-track {
        scrollbar-width: none;
        -ms-overflow-style: none;
        overscroll-behavior-x: contain;
    }
    .product-scroller-track::-webkit-scrollbar {
        width: 0;
        height: 0;
        display: none;
    }
    /* 2 visible on small screens, 4 on desktop; 2 more via scroll */
    .product-scroller-item {
        flex: 0 0 calc((100% - 0.75rem) / 2);
    }
    @media (min-width: 640px) {
        .product-scroller-item {
            flex: 0 0 calc((100% - 3rem) / 4);
        }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('repair-book-form');
    if (form) {
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
    }

    document.querySelectorAll('[data-product-scroller]').forEach((root) => {
        const track = root.querySelector('[data-scroller-track]');
        const prev = root.querySelector('[data-scroller-prev]');
        const next = root.querySelector('[data-scroller-next]');
        if (!track || !prev || !next) return;

        const step = () => {
            const item = track.querySelector('.product-scroller-item');
            if (!item) {
                return track.clientWidth * 0.5;
            }
            const gap = Number.parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
            // Advance by 2 cards per click
            return (item.getBoundingClientRect().width + gap) * 2;
        };

        const syncButtons = () => {
            const maxScroll = track.scrollWidth - track.clientWidth - 2;
            prev.disabled = track.scrollLeft <= 2;
            next.disabled = track.scrollLeft >= maxScroll;
        };

        prev.addEventListener('click', () => {
            track.scrollBy({ left: -step(), behavior: 'smooth' });
        });
        next.addEventListener('click', () => {
            track.scrollBy({ left: step(), behavior: 'smooth' });
        });
        track.addEventListener('scroll', syncButtons, { passive: true });
        window.addEventListener('resize', syncButtons);
        syncButtons();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\repairing\resources\views/devices/show.blade.php ENDPATH**/ ?>