<?php
    $links = ($siteSettings ?? null)?->socialLinks() ?? [];
    $wrapClass = $wrapClass ?? 'mt-5 flex flex-wrap gap-2';
    $linkClass = $linkClass ?? 'inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/15 text-white/70 transition hover:border-accent hover:bg-accent/20 hover:text-white';
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($links)): ?>
    <div class="<?php echo e($wrapClass); ?>" aria-label="Social links">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a
                href="<?php echo e($link['url']); ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="<?php echo e($linkClass); ?>"
                aria-label="<?php echo e($link['label']); ?>"
            >
                <i class="bi <?php echo e($link['icon']); ?>" aria-hidden="true"></i>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\laravel\repairing\resources\views/partials/social-links.blade.php ENDPATH**/ ?>