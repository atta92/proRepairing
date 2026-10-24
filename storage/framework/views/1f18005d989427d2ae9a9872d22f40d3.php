<?php
    $rating = (float) ($rating ?? 0);
    $size = $size ?? 'md';
    $tone = $tone ?? 'accent';
    $classes = $size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-xl' : 'text-base');
    $toneClass = match ($tone) {
        'gold' => 'text-[#f5b301]',
        'muted' => 'text-mist',
        default => 'text-accent',
    };
    $emptyClass = $tone === 'muted' ? 'text-mist' : 'opacity-35';
?>
<span class="inline-flex items-center gap-0.5 <?php echo e($toneClass); ?> <?php echo e($classes); ?>" aria-label="<?php echo e(number_format($rating, 1)); ?> out of 5 stars">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rating >= $i): ?>
            <i class="bi bi-star-fill" aria-hidden="true"></i>
        <?php elseif($rating >= $i - 0.5): ?>
            <i class="bi bi-star-half" aria-hidden="true"></i>
        <?php else: ?>
            <i class="bi bi-star <?php echo e($emptyClass); ?>" aria-hidden="true"></i>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</span>
<?php /**PATH D:\laravel\repairing\resources\views/partials/star-rating.blade.php ENDPATH**/ ?>