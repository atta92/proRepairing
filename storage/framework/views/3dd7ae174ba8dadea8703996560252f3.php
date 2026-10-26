

<?php $__env->startSection('title', $siteSettings->displayName() . ' - Phone, Tablet & Laptop Repairs'); ?>

<?php $__env->startSection('meta_description', $siteSettings->site_description ?: ('Cracked screen, worn battery or charging fault? ' . $siteSettings->displayName() . ' fixes phones, tablets and laptops in-house with published prices and a 12-month warranty.')); ?>

<?php $__env->startSection('content'); ?>
    <div
        x-data="{ ready: false }"
        x-init="setTimeout(() => ready = true, 240)"
    >
        <div x-show="!ready">
            <?php echo $__env->make('partials.skeletons.home', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div x-show="ready" x-cloak>
    
    <section class="site-emoji-bg relative overflow-hidden bg-ink text-white">
        <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-accent/20 blur-3xl"></div>
            <div class="absolute -right-16 bottom-0 h-80 w-80 rounded-full bg-signal/15 blur-3xl"></div>
        </div>

        <div class="site-container relative grid gap-10 py-16 lg:grid-cols-2 lg:items-center lg:gap-16 lg:py-24">
            <div>
                <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-accent/40 bg-accent/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-accent-light">
                    Trusted repair specialists
                </p>
                <h1 class="font-display text-4xl font-bold leading-tight tracking-tight sm:text-5xl lg:text-[3.25rem]">
                Professional Phone Repair Specialists Across the UK
                </h1>
                <p class="mt-4 max-w-xl text-lg text-white/75">
                    Cracked screen, worn battery, charging fault or water damage - we fix phones, tablets and laptops in-house. Post it free from anywhere in the UK.
                </p>

                <ul class="mt-8 flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                        '12-Month Warranty',
                        'Same-Day Repairs',
                        'Free UK Mail-In',
                        'Certified or Genuine Parts',
                        'No Fix, No Fee',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="rounded-full border border-white/20 bg-white/5 px-3 py-1.5 text-xs font-medium text-white/90 sm:text-sm">
                            <?php echo e($pill); ?>

                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>

            <div class="relative scroll-mt-24" id="price-checker">
                <div class="absolute -inset-3 rounded-[1.75rem] bg-accent/20 blur-2xl" aria-hidden="true"></div>
                <div class="pc-snake-border site-grid-bg-soft relative rounded-2xl border border-mist bg-white p-5 text-ink shadow-2xl shadow-black/30 sm:p-7">
                    <svg class="pc-snake-line" aria-hidden="true">
                        <rect pathLength="100" rx="16" ry="16"></rect>
                    </svg>
                    <span class="inline-flex rounded-full bg-accent px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-white">
                        Instant Price Checker
                    </span>

                    <h2 class="mt-3 font-display text-xl font-bold tracking-tight text-ink sm:text-2xl">
                        Get your repair price in 30 seconds
                    </h2>
                    <p class="mt-1.5 text-sm text-steel">
                        No email, no phone number. Pick your device and fault.
                    </p>

                    <div class="mt-5 space-y-3.5">
                        <label class="block text-left" for="pc-brand">
                            <span class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-steel">1. Choose your brand</span>
                            <select id="pc-brand" class="pc-select">
                                <option value="">Select a brand…</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->slug); ?>"><?php echo e($category->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </label>
                        <label class="block text-left" for="pc-model">
                            <span class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-steel">2. Choose your model</span>
                            <select id="pc-model" disabled class="pc-select is-disabled">
                                <option value="">Select a brand first</option>
                            </select>
                        </label>
                        <label class="block text-left" for="pc-repair">
                            <span class="mb-1.5 block text-[11px] font-semibold uppercase tracking-wider text-steel">3. What needs fixing?</span>
                            <select id="pc-repair" disabled class="pc-select is-disabled">
                                <option value="">Select a model first</option>
                            </select>
                        </label>
                    </div>

                    <div
                        id="pc-out"
                        class="pc-out mt-4"
                        aria-live="polite"
                    >
                        Your price and turnaround will appear here.
                    </div>

                    <p class="mt-3 text-center text-xs leading-relaxed text-steel/80">
                        Guide prices include parts, labour, VAT, free return postage and the 12-month warranty.
                    </p>
                    <button
                        type="button"
                        class="mt-3 flex w-full items-center justify-center rounded-full border-2 border-accent px-4 py-2.5 text-sm font-semibold text-accent transition hover:bg-accent hover:text-white"
                        data-quote-open
                    >
                        Can't find your device? Request a free quote
                    </button>
                </div>
            </div>
        </div>
    </section>

    
    <div class="home-body-shell">

    
    <section id="devices" class="site-grid-bg-soft scroll-mt-24 bg-white py-16 sm:py-20">
        <div class="site-container">
            <div class="max-w-2xl">
                <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                    Repairs by device
                </h2>
                <p class="mt-3 text-base text-steel">
                    <?php echo e($stats['categories']); ?> categories · <?php echo e($stats['devices']); ?> models - diagnosed and repaired in house.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a
                        href="<?php echo e(route('categories.show', $category->slug)); ?>"
                        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-mist bg-white transition hover:border-accent/40 hover:shadow-md"
                    >
                        <div class="relative aspect-[4/3] shrink-0 overflow-hidden bg-[#eef1f4]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->imageUrl()): ?>
                                <img
                                    src="<?php echo e($category->imageUrl()); ?>"
                                    alt=""
                                    class="absolute inset-0 h-full w-full object-contain object-center p-3 sm:p-4"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-accent/10 to-ink/5">
                                    <i class="bi bi-phone text-3xl text-accent/70" aria-hidden="true"></i>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex flex-1 flex-col bg-white p-4 sm:p-5">
                            <p class="font-display text-base font-semibold text-ink group-hover:text-accent-dark sm:text-lg">
                                <?php echo e($category->name); ?>

                            </p>
                            <p class="mt-1 text-xs text-steel sm:text-sm">
                                <?php echo e($category->excerpt ?: ucfirst($category->type) . ' repairs'); ?>

                            </p>
                            <p class="mt-3 text-xs font-medium uppercase tracking-wide text-steel/70">
                                <?php echo e($category->devices_count); ?> <?php echo e(Str::plural('model', $category->devices_count)); ?>

                            </p>
                            <p class="mt-auto pt-2 text-sm font-semibold text-accent">View models →</p>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="col-span-2 text-steel">No active categories yet. Add them in the admin panel.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shopVendors->isNotEmpty()): ?>
        <section
            id="shop"
            class="site-grid-bg-soft scroll-mt-24 bg-snow py-16 sm:py-20"
            x-data="{
                shopVendorId: <?php echo e((int) $defaultShopVendorId); ?>,
                sellerCanPrev: false,
                sellerCanNext: false,
                syncSellerScroll() {
                    const track = this.$refs.sellerTabs;
                    if (!track) {
                        this.sellerCanPrev = false;
                        this.sellerCanNext = false;
                        return;
                    }
                    const max = track.scrollWidth - track.clientWidth;
                    this.sellerCanPrev = track.scrollLeft > 4;
                    this.sellerCanNext = max > 4 && track.scrollLeft < max - 4;
                },
                scrollSellers(direction) {
                    const track = this.$refs.sellerTabs;
                    if (!track) return;
                    const amount = Math.max(track.clientWidth * 0.65, 160);
                    track.scrollBy({ left: direction * amount, behavior: 'smooth' });
                },
                selectSeller(id) {
                    this.shopVendorId = id;
                    this.$nextTick(() => {
                        const track = this.$refs.sellerTabs;
                        const active = track?.querySelector('.is-active');
                        active?.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
                        this.syncSellerScroll();
                    });
                },
                init() {
                    const sync = () => this.syncSellerScroll();
                    this.$nextTick(sync);
                    // Parent home skeleton delays visibility — re-measure after it opens.
                    setTimeout(sync, 300);
                    setTimeout(sync, 600);
                    window.addEventListener('resize', sync);
                },
            }"
        >
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Shop
                        </h2>
                        <p class="mt-3 text-base text-steel">
                            Mobiles, accessories, tablets and more - buy online for collection or delivery.
                        </p>
                    </div>
                    <a href="<?php echo e(route('shop.index')); ?>" class="text-sm font-semibold text-accent hover:text-accent-dark">
                        Browse shop →
                    </a>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shopVendors->count() > 1): ?>
                    <div class="shop-seller-bar mt-8">
                        <p class="shop-filter-label">Seller</p>
                        <div
                            class="shop-seller-scroller"
                            :class="{ 'has-left': sellerCanPrev, 'has-right': sellerCanNext }"
                        >
                            <span class="shop-seller-scroller__fade is-left" aria-hidden="true"></span>
                            <span class="shop-seller-scroller__fade is-right" aria-hidden="true"></span>

                            <button
                                type="button"
                                class="shop-seller-scroller__btn is-prev"
                                :disabled="!sellerCanPrev"
                                @click="scrollSellers(-1)"
                                aria-label="Previous sellers"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-left text-sm" aria-hidden="true"></i>
                            </button>
                            <button
                                type="button"
                                class="shop-seller-scroller__btn is-next"
                                :disabled="!sellerCanNext"
                                @click="scrollSellers(1)"
                                aria-label="Next sellers"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-right text-sm" aria-hidden="true"></i>
                            </button>

                            <div
                                class="shop-seller-tabs px-1"
                                role="tablist"
                                aria-label="Shop sellers"
                                x-ref="sellerTabs"
                                @scroll.passive="syncSellerScroll()"
                            >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $shopVendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button
                                        type="button"
                                        role="tab"
                                        class="shop-seller-chip"
                                        :class="shopVendorId === <?php echo e($vendor->id); ?> ? 'is-active' : ''"
                                        :aria-selected="shopVendorId === <?php echo e($vendor->id); ?> ? 'true' : 'false'"
                                        @click="selectSeller(<?php echo e($vendor->id); ?>)"
                                        data-no-spinner
                                    >
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vendor->is_platform): ?>
                                            <span class="shop-seller-chip__icon" aria-hidden="true">
                                                <i class="bi bi-shop"></i>
                                            </span>
                                            <span><?php echo e($vendor->name); ?></span>
                                            <span class="shop-seller-chip__badge">Own</span>
                                        <?php else: ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vendor->logoUrl()): ?>
                                                <img src="<?php echo e($vendor->logoUrl()); ?>" alt="" class="shop-seller-chip__logo">
                                            <?php else: ?>
                                                <span class="shop-seller-chip__icon is-muted" aria-hidden="true">
                                                    <i class="bi bi-bag"></i>
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <span><?php echo e($vendor->name); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="mt-8">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $shopVendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div
                            x-show="shopVendorId === <?php echo e($vendor->id); ?>"
                            <?php if (! ($loop->first)): ?> x-cloak x-transition.opacity.duration.200ms <?php endif; ?>
                            role="tabpanel"
                        >
                            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm text-steel">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vendor->is_platform): ?>
                                        From our own shop
                                    <?php else: ?>
                                        Sold by <span class="font-semibold text-ink"><?php echo e($vendor->name); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $vendor->is_platform): ?>
                                    <a href="<?php echo e(route('shops.show', $vendor)); ?>" class="text-sm font-semibold text-accent hover:text-accent-dark">
                                        Visit shop →
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $shopVendorProducts[$vendor->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accessory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('shop._card', ['product' => $accessory], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <section id="how-it-works" class="site-grid-bg-soft scroll-mt-24 bg-white py-16 sm:py-20">
        <div class="site-container">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-accent">Free mail-in</p>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                    How your repair works
                </h2>
                <p class="mt-3 text-base text-steel">
                    Post your device free from anywhere in the UK. Same technicians, same warranty, same fixed price.
                </p>
            </div>

            <ol class="how-steps mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    ['step' => '1', 'title' => 'Book online', 'text' => 'Pick your device and fault for a fixed price.'],
                    ['step' => '2', 'title' => 'Post it free', 'text' => 'We email a tracked label - pack and drop at any Post Office.'],
                    ['step' => '3', 'title' => 'We diagnose & repair', 'text' => 'In-house technicians fix it with genuine or certified parts.'],
                    ['step' => '4', 'title' => 'Tracked return', 'text' => 'We post it back with warranty. Most jobs turn around fast.'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="how-step relative text-center">
                        <span class="relative z-10 mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent text-base font-bold text-white shadow-md shadow-accent/25">
                            <?php echo e($item['step']); ?>

                        </span>
                        <p class="mt-4 font-display text-lg font-semibold text-ink"><?php echo e($item['title']); ?></p>
                        <p class="mt-1.5 text-sm leading-relaxed text-steel"><?php echo e($item['text']); ?></p>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ol>
        </div>
    </section>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($posts->isNotEmpty()): ?>
        <section id="blog" class="site-grid-bg-soft scroll-mt-24 bg-snow py-16 sm:py-20">
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Our Repair process
                        </h2>
                        <p class="mt-3 text-base text-steel">
                        Guides written by our technicians to help you work out what's actually wrong with your device.
                        </p>
                    </div>
                    <a href="<?php echo e(route('blog.index')); ?>" class="text-sm font-semibold text-accent hover:text-accent-dark">
                        View all posts →
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('blog._card', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beforeAfters->isNotEmpty()): ?>
        <?php
            $beforeAfterPerPage = 3;
            $beforeAfterPages = $beforeAfters->values()->chunk($beforeAfterPerPage);
            $beforeAfterPageCount = $beforeAfterPages->count();
        ?>
        <section
            id="before-after"
            class="site-grid-bg-soft scroll-mt-24 bg-white py-16 sm:py-20"
            x-data="{ page: 0, pages: <?php echo e($beforeAfterPageCount); ?> }"
        >
            <div class="site-container">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <h2 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Before &amp; after
                        </h2>
                        <p class="mt-3 text-base text-steel">
                            Real repairs from our workshop - see the difference side by side.
                        </p>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($beforeAfterPageCount > 1): ?>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-steel/20 bg-white text-ink transition hover:border-accent hover:text-accent"
                                aria-label="Previous before and after"
                                @click="page = (page - 1 + pages) % pages"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-left text-lg" aria-hidden="true"></i>
                            </button>
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-steel/20 bg-white text-ink transition hover:border-accent hover:text-accent"
                                aria-label="Next before and after"
                                @click="page = (page + 1) % pages"
                                data-no-spinner
                            >
                                <i class="bi bi-chevron-right text-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="relative mt-10">
                    <div class="overflow-hidden">
                        <div
                            class="flex w-full transition-transform duration-500 ease-out"
                            :style="`transform: translateX(-${page * 100}%)`"
                        >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $beforeAfterPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pageItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="grid w-full shrink-0 grow-0 basis-full grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pageItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $beforeAlt = filled($item->title) ? $item->title.' - before' : 'Repair before';
                                            $afterAlt = filled($item->title) ? $item->title.' - after' : 'Repair after';
                                        ?>
                                        <article class="min-w-0 overflow-hidden rounded-2xl border border-mist bg-white shadow-md shadow-ink/5">
                                            <div class="grid grid-cols-2 divide-x divide-mist">
                                                <figure class="min-w-0">
                                                    <div class="bg-signal/10 px-2 py-1.5 text-center text-[11px] font-semibold uppercase tracking-wider text-signal-dark">
                                                        Before
                                                    </div>
                                                    <div
                                                        class="aspect-[4/5] cursor-zoom-in overflow-hidden bg-mist transition hover:opacity-95"
                                                        role="button"
                                                        tabindex="0"
                                                        data-no-spinner
                                                        @click="$store.lightbox.show(<?php echo \Illuminate\Support\Js::from($item->beforeImageUrl())->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($beforeAlt)->toHtml() ?>, 'Before')"
                                                        @keydown.enter.prevent="$store.lightbox.show(<?php echo \Illuminate\Support\Js::from($item->beforeImageUrl())->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($beforeAlt)->toHtml() ?>, 'Before')"
                                                    >
                                                        <img
                                                            src="<?php echo e($item->beforeImageUrl()); ?>"
                                                            alt="<?php echo e($beforeAlt); ?>"
                                                            class="pointer-events-none h-full w-full max-w-full object-cover"
                                                            loading="lazy"
                                                        >
                                                    </div>
                                                </figure>
                                                <figure class="min-w-0">
                                                    <div class="bg-accent/10 px-2 py-1.5 text-center text-[11px] font-semibold uppercase tracking-wider text-accent-dark">
                                                        After
                                                    </div>
                                                    <div
                                                        class="aspect-[4/5] cursor-zoom-in overflow-hidden bg-mist transition hover:opacity-95"
                                                        role="button"
                                                        tabindex="0"
                                                        data-no-spinner
                                                        @click="$store.lightbox.show(<?php echo \Illuminate\Support\Js::from($item->afterImageUrl())->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($afterAlt)->toHtml() ?>, 'After')"
                                                        @keydown.enter.prevent="$store.lightbox.show(<?php echo \Illuminate\Support\Js::from($item->afterImageUrl())->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($afterAlt)->toHtml() ?>, 'After')"
                                                    >
                                                        <img
                                                            src="<?php echo e($item->afterImageUrl()); ?>"
                                                            alt="<?php echo e($afterAlt); ?>"
                                                            class="pointer-events-none h-full w-full max-w-full object-cover"
                                                            loading="lazy"
                                                        >
                                                    </div>
                                                </figure>
                                            </div>
                                        </article>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <section id="sell-with-us" class="site-grid-bg-soft scroll-mt-24 bg-snow py-16 sm:py-20">
        <div class="site-container">
            <div class="overflow-hidden rounded-2xl border border-mist bg-white shadow-sm">
                <div class="grid lg:grid-cols-[1.4fr_1fr]">
                    <div class="border-b border-mist p-6 sm:p-8 lg:border-b-0 lg:border-r lg:border-mist lg:p-10 xl:p-12">
                        <p class="text-sm font-semibold uppercase tracking-wider text-accent">Partners</p>
                        <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                            Want to sell with us?
                        </h2>
                        <p class="mt-3 max-w-xl text-base leading-relaxed text-steel">
                            List accessories, phones and tablets on our marketplace. Reach customers already shopping for repairs and parts — with your own seller dashboard for products and orders.
                        </p>

                        <ul class="mt-7 grid gap-3 sm:grid-cols-3">
                            <li class="flex gap-3 rounded-xl border border-mist bg-snow/80 p-3.5">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-accent/10 text-accent">
                                    <i class="bi bi-shop-window text-lg" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="font-display text-sm font-semibold text-ink">Your shop</p>
                                    <p class="mt-0.5 text-xs leading-snug text-steel">Branded storefront on our site</p>
                                </div>
                            </li>
                            <li class="flex gap-3 rounded-xl border border-mist bg-snow/80 p-3.5">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-accent/10 text-accent">
                                    <i class="bi bi-box-seam text-lg" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="font-display text-sm font-semibold text-ink">Easy listings</p>
                                    <p class="mt-0.5 text-xs leading-snug text-steel">Add products and manage stock</p>
                                </div>
                            </li>
                            <li class="flex gap-3 rounded-xl border border-mist bg-snow/80 p-3.5">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-accent/10 text-accent">
                                    <i class="bi bi-receipt text-lg" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="font-display text-sm font-semibold text-ink">Order tools</p>
                                    <p class="mt-0.5 text-xs leading-snug text-steel">Fulfil and track sales in one place</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col justify-center gap-5 bg-ink p-6 text-white sm:p-8 lg:p-10 xl:p-12">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Get started</p>
                            <p class="mt-2 font-display text-2xl font-bold tracking-tight sm:text-3xl">
                                Apply in minutes
                            </p>
                            <p class="mt-2 text-sm leading-relaxed text-white/70">
                                Register your shop. We’ll review your application and email you when you’re approved to sell.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                            <a href="<?php echo e(route('seller.register')); ?>" class="btn-primary w-full sm:w-auto lg:w-full">
                                Become a seller
                            </a>
                            <a href="<?php echo e(route('shop.index', ['seller' => 'all'])); ?>" class="btn-secondary w-full sm:w-auto lg:w-full">
                                Browse marketplace
                            </a>
                        </div>

                        <p class="text-xs leading-relaxed text-white/50">
                            Third-party sales include a platform commission. You’ll see the rate before you list.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\repairing\resources\views/home.blade.php ENDPATH**/ ?>