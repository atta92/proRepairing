<?php
    $title = $title ?? "Can't find your model?";
    $text = $text ?? 'Older device, rare fault, or not listed above? Request a free quote and we’ll come back with a fixed price.';
?>

<div class="mt-12 overflow-hidden rounded-2xl border border-mist bg-white shadow-sm">
    <div class="grid lg:grid-cols-2">
        <div class="border-b border-mist p-6 sm:p-8 lg:border-b-0 lg:border-r lg:border-mist lg:p-10">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent">Free quotation</p>
            <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                <?php echo e($title); ?>

            </h2>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-steel sm:text-base">
                <?php echo e($text); ?>

            </p>
            <ul class="mt-5 space-y-2.5 text-sm text-steel">
                <li class="flex items-center gap-2.5">
                    <i class="bi bi-check-circle-fill shrink-0 text-accent" aria-hidden="true"></i>
                    <span>Fixed price reply</span>
                </li>
                <li class="flex items-center gap-2.5">
                    <i class="bi bi-check-circle-fill shrink-0 text-accent" aria-hidden="true"></i>
                    <span>No obligation</span>
                </li>
                <li class="flex items-center gap-2.5">
                    <i class="bi bi-check-circle-fill shrink-0 text-accent" aria-hidden="true"></i>
                    <span>12-month warranty on repairs</span>
                </li>
            </ul>
        </div>

        <div class="flex items-center justify-center bg-snow/70 p-6 sm:p-8 lg:justify-end lg:p-10">
            <div class="flex w-full max-w-xs flex-col gap-3 sm:max-w-sm lg:w-auto lg:min-w-[14rem]">
                <button type="button" class="btn-primary w-full" data-quote-open>
                    Request a free quote
                </button>
                <a href="<?php echo e(route('pages.contact')); ?>" class="btn-ghost w-full">
                    Contact us
                </a>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\laravel\repairing\resources\views/partials/quote-cta.blade.php ENDPATH**/ ?>