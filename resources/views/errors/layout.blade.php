@php
    try {
        $siteSettings = $siteSettings ?? \App\Models\SiteSetting::current();
    } catch (\Throwable) {
        $siteSettings = null;
    }

    $siteName = $siteSettings?->displayName() ?: config('app.name', 'Repairing');
    $logoUrl = null;
    try {
        $logoUrl = $siteSettings?->logoUrl();
    } catch (\Throwable) {
        $logoUrl = null;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Error') — {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-snow text-ink antialiased">
    <div class="site-grid-bg-soft flex min-h-screen flex-col">
        <header class="border-b border-mist/80 bg-white/80 backdrop-blur">
            <div class="site-container flex items-center justify-between py-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-9 w-auto max-w-[10rem] object-contain sm:h-10">
                        <span class="sr-only">{{ $siteName }}</span>
                    @else
                        <span class="font-display text-xl font-bold tracking-tight text-ink sm:text-2xl">{{ $siteName }}</span>
                    @endif
                </a>
                <a href="{{ url('/') }}" class="btn-ghost text-sm">Back to home</a>
            </div>
        </header>

        <main class="flex flex-1 items-center justify-center px-4 py-16 sm:py-20">
            <div class="w-full max-w-lg text-center">
                @yield('content')
            </div>
        </main>

        <footer class="border-t border-mist/80 py-5 text-center text-xs text-steel">
            © {{ date('Y') }} {{ $siteName }}
        </footer>
    </div>
</body>
</html>
