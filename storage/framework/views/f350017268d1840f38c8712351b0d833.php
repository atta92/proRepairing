<a
    href="<?php echo e(route('blog.show', $post)); ?>"
    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-mist bg-white transition hover:border-accent/40 hover:shadow-md"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->image_path): ?>
        <div class="aspect-[16/10] overflow-hidden bg-mist">
            <img
                src="<?php echo e(asset('storage/' . $post->image_path)); ?>"
                alt=""
                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
            >
        </div>
    <?php else: ?>
        <div class="flex aspect-[16/10] items-center justify-center bg-gradient-to-br from-accent/15 to-ink/10">
            <span class="font-display text-sm font-semibold uppercase tracking-wider text-accent"><?php echo e($siteSettings->displayName()); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="flex flex-1 flex-col p-3.5 sm:p-5">
        <time class="text-[10px] font-medium uppercase tracking-wider text-steel/70 sm:text-xs" datetime="<?php echo e($post->published_at?->toDateString()); ?>">
            <?php echo e($post->published_at?->format('d M Y')); ?>

        </time>
        <h3 class="mt-1.5 font-display text-sm font-semibold leading-snug text-ink group-hover:text-accent-dark sm:mt-2 sm:text-lg">
            <?php echo e($post->title); ?>

        </h3>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->excerpt): ?>
            <p class="mt-2 hidden flex-1 text-sm leading-relaxed text-steel sm:block">
                <?php echo e(Str::limit($post->excerpt, 110)); ?>

            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <p class="mt-3 text-xs font-semibold text-accent sm:mt-4 sm:text-sm">Read more →</p>
    </div>
</a>
<?php /**PATH D:\laravel\repairing\resources\views/blog/_card.blade.php ENDPATH**/ ?>