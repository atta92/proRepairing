<?php
    $siteName = ($siteSettings ?? null)?->displayName() ?: config('app.name');
    $defaultDescription = ($siteSettings ?? null)?->site_description ?: config('seo.default_description');
    $seoTitle = trim(preg_replace('/\s+/', ' ', strip_tags((string) $__env->yieldContent('title')))) ?: $siteName;
    $seoDescription = trim(preg_replace('/\s+/', ' ', strip_tags((string) $__env->yieldContent('meta_description')))) ?: $defaultDescription;
    $seoDescription = Str::limit($seoDescription, 160, '…');
    $seoCanonical = trim((string) $__env->yieldContent('canonical')) ?: url()->current();
    $seoRobots = trim((string) $__env->yieldContent('meta_robots')) ?: 'index, follow';
    $seoOgType = trim((string) $__env->yieldContent('og_type')) ?: 'website';
    $seoOgImage = trim((string) $__env->yieldContent('og_image'));
    if ($seoOgImage === '' && ($siteSettings ?? null)?->logoUrl()) {
        $seoOgImage = $siteSettings->logoUrl();
    }
    if ($seoOgImage !== '' && ! str_starts_with($seoOgImage, 'http')) {
        $seoOgImage = url($seoOgImage);
    }
?>

<meta name="description" content="<?php echo e($seoDescription); ?>">
<meta name="robots" content="<?php echo e($seoRobots); ?>">
<link rel="canonical" href="<?php echo e($seoCanonical); ?>">

<meta property="og:locale" content="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<meta property="og:type" content="<?php echo e($seoOgType); ?>">
<meta property="og:site_name" content="<?php echo e($siteName); ?>">
<meta property="og:title" content="<?php echo e($seoTitle); ?>">
<meta property="og:description" content="<?php echo e($seoDescription); ?>">
<meta property="og:url" content="<?php echo e($seoCanonical); ?>">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seoOgImage !== ''): ?>
    <meta property="og:image" content="<?php echo e($seoOgImage); ?>">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<meta name="twitter:card" content="<?php echo e(config('seo.twitter_card')); ?>">
<meta name="twitter:title" content="<?php echo e($seoTitle); ?>">
<meta name="twitter:description" content="<?php echo e($seoDescription); ?>">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seoOgImage !== ''): ?>
    <meta name="twitter:image" content="<?php echo e($seoOgImage); ?>">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($siteSettings ?? null)?->logoUrl()): ?>
    <link rel="icon" href="<?php echo e($siteSettings->logoUrl()); ?>" type="image/png">
<?php else: ?>
    <link rel="icon" href="<?php echo e(asset('favicon.svg')); ?>" type="image/svg+xml">
    <link rel="icon" href="<?php echo e(asset('apple-touch-icon.png')); ?>" type="image/png" sizes="180x180">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<link rel="apple-touch-icon" href="<?php echo e(($siteSettings ?? null)?->logoUrl() ?: asset('apple-touch-icon.png')); ?>">
<link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">
<meta name="theme-color" content="#0d9488">
<?php /**PATH D:\laravel\repairing\resources\views/partials/seo.blade.php ENDPATH**/ ?>