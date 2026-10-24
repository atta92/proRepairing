<?php
    $name = ($siteSettings ?? null)?->displayName() ?: config('app.name');
    $logo = ($siteSettings ?? null)?->logoUrl();
    $tag = $tag ?? 'span';
    $textClass = $textClass ?? 'font-display text-xl font-bold tracking-tight text-white sm:text-2xl';
    $logoClass = $logoClass ?? 'h-9 w-auto max-w-[10rem] object-contain sm:h-10';
?>

<a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2.5 <?php echo e($linkClass ?? ''); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logo): ?>
        <img src="<?php echo e($logo); ?>" alt="<?php echo e($name); ?>" class="<?php echo e($logoClass); ?>">
        <span class="sr-only"><?php echo e($name); ?></span>
    <?php else: ?>
        <<?php echo e($tag); ?> class="<?php echo e($textClass); ?>"><?php echo e($name); ?></<?php echo e($tag); ?>>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</a>
<?php /**PATH D:\laravel\repairing\resources\views/partials/site-brand.blade.php ENDPATH**/ ?>