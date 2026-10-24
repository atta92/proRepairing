<?php
    $siteName = ($siteSettings ?? null)?->displayName() ?: config('app.name');
    $siteDescription = ($siteSettings ?? null)?->site_description
        ?: 'Top-rated same-day repair services for phones, tablets and laptops - free mail-in from anywhere in the UK.';
?>

<footer class="site-grid-bg bg-ink text-white">
    <div class="site-container relative z-[1] grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
        <div class="md:col-span-2 lg:col-span-1">
            <?php echo $__env->make('partials.site-brand', [
                'textClass' => 'font-display text-2xl font-bold tracking-tight text-white',
                'logoClass' => 'h-10 w-auto max-w-[12rem] object-contain',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-white/70">
                <?php echo e($siteDescription); ?>

            </p>
            <?php echo $__env->make('partials.social-links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Repair services</p>
            <ul class="mt-4 space-y-2 text-sm text-white/70">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($navCategories ?? collect())->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li>
                        <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="transition hover:text-white">
                            <?php echo e($category->name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li><a href="<?php echo e(route('home')); ?>#devices" class="transition hover:text-white">All repairs</a></li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Popular</p>
            <ul class="mt-4 space-y-2 text-sm text-white/70">
                <li><a href="<?php echo e(route('home')); ?>#price-checker" class="transition hover:text-white">Price checker</a></li>
                <li><a href="<?php echo e(route('shop.index')); ?>" class="transition hover:text-white">Shop</a></li>
                <li><a href="<?php echo e(route('seller.register')); ?>" class="transition hover:text-white">Sell with us</a></li>
                <li><a href="<?php echo e(route('blog.index')); ?>" class="transition hover:text-white">Blog</a></li>
                <li><a href="<?php echo e(route('pages.about')); ?>" class="transition hover:text-white">About us</a></li>
                <li><a href="#" class="transition hover:text-white" data-quote-open>Request a quote</a></li>
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Contact</p>
            <ul class="mt-4 space-y-2 text-sm text-white/70">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($siteSettings->contact_email ?? null)): ?>
                    <li>
                        <a href="mailto:<?php echo e($siteSettings->contact_email); ?>" class="transition hover:text-white">
                            <?php echo e($siteSettings->contact_email); ?>

                        </a>
                    </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($siteSettings->contact_phone ?? null)): ?>
                    <li>
                        <a href="tel:<?php echo e(preg_replace('/\s+/', '', $siteSettings->contact_phone)); ?>" class="transition hover:text-white">
                            <?php echo e($siteSettings->contact_phone); ?>

                        </a>
                    </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($siteSettings->contact_hours ?? null)): ?>
                    <li><?php echo e($siteSettings->contact_hours); ?></li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($siteSettings->contact_address ?? null)): ?>
                    <li><?php echo e($siteSettings->contact_address); ?></li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <li class="pt-1">
                    <a href="<?php echo e(route('pages.contact')); ?>" class="font-medium text-accent-light transition hover:text-white">Contact us →</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="relative z-[1] border-t border-white/10">
        <div class="site-container flex flex-col gap-2 py-5 text-xs text-white/50 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. All rights reserved.</p>
            <p class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('pages.privacy')); ?>" class="transition hover:text-white">Privacy</a>
                <span aria-hidden="true">·</span>
                <a href="<?php echo e(route('pages.terms')); ?>" class="transition hover:text-white">Terms &amp; conditions</a>
            </p>
        </div>
    </div>
</footer>
<?php /**PATH D:\laravel\repairing\resources\views/partials/footer.blade.php ENDPATH**/ ?>