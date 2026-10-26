<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', ($siteSettings ?? null)?->displayName() ?: config('app.name'))</title>

    @include('partials.seo')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/brands.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/fontawesome.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col" x-data>
    <script>
        window.__SHOP_BAG__ = {
            csrf: @js(csrf_token()),
            addUrl: @js(route('shop.cart.add')),
            cart: @js($shopBagPayload ?? ['lines' => [], 'totals' => ['subtotal' => 0, 'count' => 0], 'empty' => true]),
        };
    </script>
    @include('partials.header')

    <main class="flex-1">
        @if (session('error'))
            <div class="bg-signal/10 px-4 py-3 text-center text-sm text-signal-dark">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="bg-accent/10 px-4 py-3 text-center text-sm text-accent-dark">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.quote-modal')
    @include('partials.bag-drawer')
    @include('partials.image-lightbox')
    @include('partials.floating-actions')

    @stack('scripts')
</body>
</html>
