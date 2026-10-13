@php
    $seoTitle = trim(preg_replace('/\s+/', ' ', strip_tags((string) $__env->yieldContent('title')))) ?: config('app.name');
    $seoDescription = trim(preg_replace('/\s+/', ' ', strip_tags((string) $__env->yieldContent('meta_description')))) ?: config('seo.default_description');
    $seoDescription = Str::limit($seoDescription, 160, '…');
    $seoCanonical = trim((string) $__env->yieldContent('canonical')) ?: url()->current();
    $seoRobots = trim((string) $__env->yieldContent('meta_robots')) ?: 'index, follow';
    $seoOgType = trim((string) $__env->yieldContent('og_type')) ?: 'website';
    $seoOgImage = trim((string) $__env->yieldContent('og_image'));
    if ($seoOgImage !== '' && ! str_starts_with($seoOgImage, 'http')) {
        $seoOgImage = url($seoOgImage);
    }
@endphp

<meta name="description" content="{{ $seoDescription }}">
<meta name="robots" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $seoCanonical }}">

<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta property="og:type" content="{{ $seoOgType }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonical }}">
@if ($seoOgImage !== '')
    <meta property="og:image" content="{{ $seoOgImage }}">
@endif

<meta name="twitter:card" content="{{ config('seo.twitter_card') }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if ($seoOgImage !== '')
    <meta name="twitter:image" content="{{ $seoOgImage }}">
@endif

<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="icon" href="{{ asset('apple-touch-icon.png') }}" type="image/png" sizes="180x180">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<meta name="theme-color" content="#0d9488">
