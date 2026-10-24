<?php
    $phoneCategories = ($navCategories ?? collect())->where('type', 'phone')->values();
    $tabletCategories = ($navCategories ?? collect())->where('type', 'tablet')->values();
    $laptopCategories = ($navCategories ?? collect())->where('type', 'laptop')->values();
    $watchCategories = ($navCategories ?? collect())->where('type', 'watch')->values();

    $desktopMenus = [
        ['label' => 'Phone Repairs', 'items' => $phoneCategories],
        ['label' => 'Tablet Repair', 'items' => $tabletCategories],
        ['label' => 'Laptop Repair', 'items' => $laptopCategories],
        ['label' => 'Watch Repair', 'items' => $watchCategories],
    ];
?>

<header class="sticky top-0 z-50 overflow-visible bg-ink text-white shadow-lg shadow-ink/20">
    <div class="relative z-[1] border-b border-accent/40 bg-accent px-4 py-2 text-center text-xs font-medium text-white sm:text-sm">
        Same-day repairs · 12-month warranty · Free UK mail-in
    </div>

    <div class="site-container relative z-[1] flex items-center justify-between gap-4 py-4">
        <?php echo $__env->make('partials.site-brand', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <nav class="hidden min-w-0 flex-1 items-center justify-center gap-0.5 nav:flex" aria-label="Primary" id="desktop-nav">
            <a href="<?php echo e(route('home')); ?>" class="site-nav-link whitespace-nowrap rounded-lg px-2.5 py-2 <?php echo e(request()->routeIs('home') ? 'site-nav-link-active' : ''); ?>">
                Home
            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $desktopMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($menu['items']->isNotEmpty()): ?>
                    <div class="relative" data-nav-dropdown>
                        <button
                            type="button"
                            class="site-nav-link inline-flex items-center gap-1 whitespace-nowrap rounded-lg px-2 py-2"
                            data-nav-trigger
                            aria-expanded="false"
                            aria-haspopup="true"
                        >
                            <span><?php echo e($menu['label']); ?></span>
                            <svg class="h-3.5 w-3.5 shrink-0 opacity-70 transition" data-nav-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div
                            data-nav-panel
                            class="absolute left-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-56 rounded-xl border border-white/10 bg-ink-soft p-2 shadow-2xl"
                            role="menu"
                        >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $menu['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a
                                    href="<?php echo e(route('categories.show', $category->slug)); ?>"
                                    class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white"
                                    role="menuitem"
                                >
                                    <?php echo e($category->name); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <a href="<?php echo e(route('shop.index')); ?>" class="site-nav-link whitespace-nowrap rounded-lg px-2 py-2 <?php echo e(request()->routeIs('shop.*') ? 'site-nav-link-active' : ''); ?>">Shop</a>
            <a href="<?php echo e(route('pages.contact')); ?>" class="site-nav-link whitespace-nowrap rounded-lg px-2 py-2 <?php echo e(request()->routeIs('pages.contact') ? 'site-nav-link-active' : ''); ?>">Contact</a>

            <div class="relative" data-nav-dropdown>
                <button
                    type="button"
                    class="site-nav-link inline-flex items-center gap-1 whitespace-nowrap rounded-lg px-2 py-2 <?php echo e(request()->routeIs('pages.about', 'pages.privacy', 'pages.terms') ? 'site-nav-link-active' : ''); ?>"
                    data-nav-trigger
                    aria-expanded="false"
                    aria-haspopup="true"
                >
                    <span>About</span>
                    <svg class="h-3.5 w-3.5 shrink-0 opacity-70 transition" data-nav-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div
                    data-nav-panel
                    class="absolute left-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-56 rounded-xl border border-white/10 bg-ink-soft p-2 shadow-2xl"
                    role="menu"
                >
                    <a href="<?php echo e(route('pages.about')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">About us</a>
                    <a href="<?php echo e(route('pages.privacy')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Privacy policy</a>
                    <a href="<?php echo e(route('pages.terms')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Terms &amp; conditions</a>
                </div>
            </div>
        </nav>

        <div class="relative z-10 flex shrink-0 items-center gap-3 bg-ink pl-2">
            <button
                type="button"
                data-bag-open
                class="relative inline-flex h-10 w-10 items-center justify-center overflow-visible rounded-lg border border-white/20 text-white transition hover:bg-white/10"
                aria-label="Shopping bag<?php echo e(($shopCartCount ?? 0) > 0 ? ' (' . $shopCartCount . ' items)' : ''); ?>"
                @click="$store.bag.openDrawer()"
            >
                <i class="bi bi-bag text-base leading-none" aria-hidden="true"></i>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($shopCartCount ?? 0) > 0): ?>
                    <span class="cart-count"><?php echo e($shopCartCount > 99 ? '99+' : $shopCartCount); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </button>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <div class="relative hidden sm:block" data-nav-dropdown>
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-white/20 px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10"
                        data-nav-trigger
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <i class="bi bi-person" aria-hidden="true"></i>
                        <span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->is_admin): ?>
                                Admin
                            <?php elseif(auth()->user()->isApprovedVendor()): ?>
                                Seller
                            <?php elseif(auth()->user()->hasVendorAccount()): ?>
                                Seller
                            <?php else: ?>
                                Account
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                        <svg class="h-3.5 w-3.5 shrink-0 opacity-70" data-nav-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div
                        data-nav-panel
                        class="absolute right-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-48 rounded-xl border border-white/10 bg-ink-soft p-2 shadow-2xl"
                        role="menu"
                    >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->is_admin): ?>
                            <a href="<?php echo e(url('/admin')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Admin panel</a>
                        <?php elseif(auth()->user()->isApprovedVendor()): ?>
                            <a href="<?php echo e(url('/seller')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Seller centre</a>
                            <a href="<?php echo e(url('/seller/products')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">My products</a>
                            <a href="<?php echo e(url('/seller/orders')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">My orders</a>
                        <?php elseif(auth()->user()->hasVendorAccount()): ?>
                            <a href="<?php echo e(route('seller.home')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Application status</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('account.dashboard')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Dashboard</a>
                            <a href="<?php echo e(route('account.profile')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Settings</a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a
                    href="<?php echo e(route('login')); ?>"
                    class="hidden rounded-lg border border-white/20 px-3 py-2 text-sm font-medium text-white/90 transition hover:bg-white/10 sm:inline-flex"
                >
                    Sign in
                </a>
                <a href="<?php echo e(route('register')); ?>" class="btn-primary hidden sm:inline-flex">
                    Create account
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <a href="#" class="btn-primary hidden sm:inline-flex" data-quote-open>
                Get a Quote
            </a>

            <button
                id="nav-toggle"
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/20 text-white transition hover:bg-white/10 nav:hidden"
                aria-controls="nav-panel"
                aria-expanded="false"
                aria-label="Open menu"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>
    </div>

    <div id="nav-panel" class="relative z-[1] hidden border-t border-white/10 bg-ink-soft nav:hidden" role="dialog" aria-label="Mobile menu">
        <nav class="site-container flex flex-col gap-1 py-4">
            <a href="<?php echo e(route('home')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Home</a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $desktopMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($menu['items']->isNotEmpty()): ?>
                    <div data-mobile-accordion class="rounded-lg">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium text-white/85 hover:bg-white/10"
                            data-mobile-trigger
                            aria-expanded="false"
                        >
                            <span><?php echo e($menu['label']); ?></span>
                            <svg class="h-4 w-4 transition" data-mobile-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div data-mobile-panel class="hidden space-y-1 pb-2 pl-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $menu['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">
                                    <?php echo e($category->name); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <a href="<?php echo e(route('shop.index')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Shop</a>
            <a href="<?php echo e(route('pages.contact')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Contact</a>

            <div data-mobile-accordion class="rounded-lg">
                <button
                    type="button"
                    class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium text-white/85 hover:bg-white/10"
                    data-mobile-trigger
                    aria-expanded="false"
                >
                    <span>About</span>
                    <svg class="h-4 w-4 transition" data-mobile-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div data-mobile-panel class="hidden space-y-1 pb-2 pl-3">
                    <a href="<?php echo e(route('pages.about')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">About us</a>
                    <a href="<?php echo e(route('pages.privacy')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">Privacy policy</a>
                    <a href="<?php echo e(route('pages.terms')); ?>" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">Terms &amp; conditions</a>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->is_admin): ?>
                    <a href="<?php echo e(url('/admin')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Admin panel</a>
                <?php elseif(auth()->user()->isApprovedVendor()): ?>
                    <a href="<?php echo e(url('/seller')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Seller centre</a>
                    <a href="<?php echo e(url('/seller/products')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">My products</a>
                <?php elseif(auth()->user()->hasVendorAccount()): ?>
                    <a href="<?php echo e(route('seller.home')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Application status</a>
                <?php else: ?>
                    <a href="<?php echo e(route('account.dashboard')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">My account</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-1">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-white/85 hover:bg-white/10">Sign out</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Sign in</a>
                <a href="<?php echo e(route('register')); ?>" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Create account</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <a href="#" class="btn-primary mt-2 w-full sm:hidden" data-quote-open>Get a Quote</a>
        </nav>
    </div>
</header>
<?php /**PATH D:\laravel\repairing\resources\views/partials/header.blade.php ENDPATH**/ ?>