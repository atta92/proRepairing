<?php
    $count = (int) ($count ?? ($product->approved_reviews_count ?? 0));
    $average = $average ?? ($product->approved_reviews_avg_rating ?? null);
    $size = $size ?? 'sm';
    $link = $link ?? null;
    $class = $class ?? 'mt-1.5';
    $hasReviews = $count > 0 && $average;
    $displayRating = $hasReviews ? (float) $average : 0.0;
    $wrapperClass = $class.' inline-flex flex-wrap items-center gap-1.5 text-xs sm:text-sm '.($hasReviews ? 'text-steel' : 'text-steel/60');
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($link): ?>
    <a href="<?php echo e($link); ?>" class="<?php echo e($wrapperClass); ?> hover:text-accent">
        <?php echo $__env->make('partials.star-rating', [
            'rating' => $displayRating,
            'size' => $size,
            'tone' => $hasReviews ? 'gold' : 'muted',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasReviews): ?>
            <span class="font-semibold text-ink"><?php echo e(number_format($displayRating, 1)); ?></span>
            <span>(<?php echo e($count); ?> <?php echo e(Str::plural('review', $count)); ?>)</span>
        <?php else: ?>
            <span>No reviews yet</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </a>
<?php else: ?>
    <p class="<?php echo e($wrapperClass); ?>">
        <?php echo $__env->make('partials.star-rating', [
            'rating' => $displayRating,
            'size' => $size,
            'tone' => $hasReviews ? 'gold' : 'muted',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasReviews): ?>
            <span class="font-semibold text-ink"><?php echo e(number_format($displayRating, 1)); ?></span>
            <span>(<?php echo e($count); ?>)</span>
        <?php else: ?>
            <span>No reviews yet</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </p>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\laravel\repairing\resources\views/partials/product-rating.blade.php ENDPATH**/ ?>