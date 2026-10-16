@php
    $siteName = ($siteSettings ?? null)?->displayName() ?: config('app.name');
    $siteDescription = ($siteSettings ?? null)?->site_description
        ?: 'Top-rated same-day repair services for phones, tablets and laptops - free mail-in from anywhere in the UK.';
@endphp

<footer class="site-grid-bg bg-ink text-white">
    <div class="site-container relative z-[1] grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
        <div class="md:col-span-2 lg:col-span-1">
            @include('partials.site-brand', [
                'textClass' => 'font-display text-2xl font-bold tracking-tight text-white',
                'logoClass' => 'h-10 w-auto max-w-[12rem] object-contain',
            ])
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-white/70">
                {{ $siteDescription }}
            </p>
            @include('partials.social-links')
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Repair services</p>
            <ul class="mt-4 space-y-2 text-sm text-white/70">
                @forelse (($navCategories ?? collect())->take(6) as $category)
                    <li>
                        <a href="{{ route('categories.show', $category->slug) }}" class="transition hover:text-white">
                            {{ $category->name }}
                        </a>
                    </li>
                @empty
                    <li><a href="{{ route('home') }}#devices" class="transition hover:text-white">All repairs</a></li>
                @endforelse
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Popular</p>
            <ul class="mt-4 space-y-2 text-sm text-white/70">
                <li><a href="{{ route('home') }}#price-checker" class="transition hover:text-white">Price checker</a></li>
                <li><a href="{{ route('shop.index') }}" class="transition hover:text-white">Shop</a></li>
                <li><a href="{{ route('blog.index') }}" class="transition hover:text-white">Blog</a></li>
                <li><a href="{{ route('pages.about') }}" class="transition hover:text-white">About us</a></li>
                <li><a href="#" class="transition hover:text-white" data-quote-open>Request a quote</a></li>
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Contact</p>
            <ul class="mt-4 space-y-2 text-sm text-white/70">
                @if (($siteSettings->contact_email ?? null))
                    <li>
                        <a href="mailto:{{ $siteSettings->contact_email }}" class="transition hover:text-white">
                            {{ $siteSettings->contact_email }}
                        </a>
                    </li>
                @endif
                @if (($siteSettings->contact_phone ?? null))
                    <li>
                        <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->contact_phone) }}" class="transition hover:text-white">
                            {{ $siteSettings->contact_phone }}
                        </a>
                    </li>
                @endif
                @if (($siteSettings->contact_hours ?? null))
                    <li>{{ $siteSettings->contact_hours }}</li>
                @endif
                @if (($siteSettings->contact_address ?? null))
                    <li>{{ $siteSettings->contact_address }}</li>
                @endif
                <li class="pt-1">
                    <a href="{{ route('pages.contact') }}" class="font-medium text-accent-light transition hover:text-white">Contact us →</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="relative z-[1] border-t border-white/10">
        <div class="site-container flex flex-col gap-2 py-5 text-xs text-white/50 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            <p class="flex flex-wrap gap-3">
                <a href="{{ route('pages.privacy') }}" class="transition hover:text-white">Privacy</a>
                <span aria-hidden="true">·</span>
                <a href="{{ route('pages.terms') }}" class="transition hover:text-white">Terms &amp; conditions</a>
            </p>
        </div>
    </div>
</footer>
