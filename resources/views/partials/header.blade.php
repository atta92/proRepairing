@php
    $phoneCategories = ($navCategories ?? collect())->where('type', 'phone')->values();
    $tabletCategories = ($navCategories ?? collect())->where('type', 'tablet')->values();
    $laptopCategories = ($navCategories ?? collect())->where('type', 'laptop')->values();
    $watchCategories = ($navCategories ?? collect())->where('type', 'watch')->values();

    $desktopMenus = [
        ['label' => 'Phone Repairs', 'items' => $phoneCategories],
        ['label' => 'Tablet Repair', 'items' => $tabletCategories],
        ['label' => 'Laptop Repair', 'items' => $laptopCategories],
        ['label' => 'Watch Repair', 'items' => $watchCategories],
    ];
@endphp

<header class="sticky top-0 z-50 overflow-visible bg-ink text-white shadow-lg shadow-ink/20">
    <div class="border-b border-accent/40 bg-accent px-4 py-2 text-center text-xs font-medium text-white sm:text-sm">
        Same-day repairs · 12-month warranty · Free UK mail-in
    </div>

    <div class="site-container flex items-center justify-between gap-4 py-4">
        <a href="{{ route('home') }}" class="font-display text-xl font-bold tracking-tight text-white sm:text-2xl">
            {{ config('app.name') }}
        </a>

        <nav class="hidden min-w-0 flex-1 items-center justify-center gap-0.5 lg:flex" aria-label="Primary" id="desktop-nav">
            <a href="{{ route('home') }}" class="site-nav-link whitespace-nowrap rounded-lg px-2.5 py-2 {{ request()->routeIs('home') ? 'site-nav-link-active' : '' }}">
                Home
            </a>

            @foreach ($desktopMenus as $menu)
                @if ($menu['items']->isNotEmpty())
                    <div class="relative" data-nav-dropdown>
                        <button
                            type="button"
                            class="site-nav-link inline-flex items-center gap-1 whitespace-nowrap rounded-lg px-2 py-2"
                            data-nav-trigger
                            aria-expanded="false"
                            aria-haspopup="true"
                        >
                            <span>{{ $menu['label'] }}</span>
                            <svg class="h-3.5 w-3.5 shrink-0 opacity-70 transition" data-nav-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div
                            data-nav-panel
                            class="absolute left-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-56 rounded-xl border border-white/10 bg-ink-soft p-2 shadow-2xl"
                            role="menu"
                        >
                            @foreach ($menu['items'] as $category)
                                <a
                                    href="{{ route('categories.show', $category->slug) }}"
                                    class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white"
                                    role="menuitem"
                                >
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <a href="{{ route('shop.index') }}" class="site-nav-link whitespace-nowrap rounded-lg px-2 py-2 {{ request()->routeIs('shop.*') ? 'site-nav-link-active' : '' }}">Shop</a>
            <a href="{{ route('home') }}#how-it-works" class="site-nav-link hidden whitespace-nowrap rounded-lg px-2 py-2 xl:inline-flex">How it works</a>
            <a href="{{ route('pages.contact') }}" class="site-nav-link whitespace-nowrap rounded-lg px-2 py-2 {{ request()->routeIs('pages.contact') ? 'site-nav-link-active' : '' }}">Contact</a>

            <div class="relative" data-nav-dropdown>
                <button
                    type="button"
                    class="site-nav-link inline-flex items-center gap-1 whitespace-nowrap rounded-lg px-2 py-2 {{ request()->routeIs('pages.about', 'pages.privacy', 'pages.terms') ? 'site-nav-link-active' : '' }}"
                    data-nav-trigger
                    aria-expanded="false"
                    aria-haspopup="true"
                >
                    <span>About</span>
                    <svg class="h-3.5 w-3.5 shrink-0 opacity-70 transition" data-nav-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div
                    data-nav-panel
                    class="absolute left-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-56 rounded-xl border border-white/10 bg-ink-soft p-2 shadow-2xl"
                    role="menu"
                >
                    <a href="{{ route('pages.about') }}" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">About us</a>
                    <a href="{{ route('pages.privacy') }}" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Privacy policy</a>
                    <a href="{{ route('pages.terms') }}" class="block rounded-lg px-3 py-2 text-sm text-white/80 hover:bg-white/10 hover:text-white" role="menuitem">Terms &amp; conditions</a>
                </div>
            </div>
        </nav>

        <div class="relative z-10 flex shrink-0 items-center gap-3 bg-ink pl-2">
            <button
                type="button"
                data-bag-open
                class="relative inline-flex h-10 w-10 items-center justify-center overflow-visible rounded-lg border border-white/20 text-white transition hover:bg-white/10"
                aria-label="Shopping bag{{ ($shopCartCount ?? 0) > 0 ? ' (' . $shopCartCount . ' items)' : '' }}"
                @click="$store.bag.openDrawer()"
            >
                <i class="bi bi-bag text-base leading-none" aria-hidden="true"></i>
                @if (($shopCartCount ?? 0) > 0)
                    <span class="cart-count">{{ $shopCartCount > 99 ? '99+' : $shopCartCount }}</span>
                @endif
            </button>

            <a href="#" class="btn-primary hidden sm:inline-flex" data-quote-open>
                Get a Quote
            </a>

            <button
                id="nav-toggle"
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/20 text-white transition hover:bg-white/10 lg:hidden"
                aria-controls="nav-panel"
                aria-expanded="false"
                aria-label="Open menu"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>
    </div>

    <div id="nav-panel" class="hidden border-t border-white/10 bg-ink-soft lg:hidden" role="dialog" aria-label="Mobile menu">
        <nav class="site-container flex flex-col gap-1 py-4">
            <a href="{{ route('home') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Home</a>

            @foreach ($desktopMenus as $menu)
                @if ($menu['items']->isNotEmpty())
                    <div data-mobile-accordion class="rounded-lg">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium text-white/85 hover:bg-white/10"
                            data-mobile-trigger
                            aria-expanded="false"
                        >
                            <span>{{ $menu['label'] }}</span>
                            <svg class="h-4 w-4 transition" data-mobile-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div data-mobile-panel class="hidden space-y-1 pb-2 pl-3">
                            @foreach ($menu['items'] as $category)
                                <a href="{{ route('categories.show', $category->slug) }}" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <a href="{{ route('shop.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Shop</a>
            <a href="{{ route('home') }}#how-it-works" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">How it works</a>
            <a href="{{ route('pages.contact') }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">Contact</a>

            <div data-mobile-accordion class="rounded-lg">
                <button
                    type="button"
                    class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium text-white/85 hover:bg-white/10"
                    data-mobile-trigger
                    aria-expanded="false"
                >
                    <span>About</span>
                    <svg class="h-4 w-4 transition" data-mobile-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div data-mobile-panel class="hidden space-y-1 pb-2 pl-3">
                    <a href="{{ route('pages.about') }}" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">About us</a>
                    <a href="{{ route('pages.privacy') }}" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">Privacy policy</a>
                    <a href="{{ route('pages.terms') }}" class="block rounded-lg px-3 py-2 text-sm text-white/70 hover:bg-white/10 hover:text-white">Terms &amp; conditions</a>
                </div>
            </div>

            <a href="#" class="btn-primary mt-2 w-full sm:hidden" data-quote-open>Get a Quote</a>
        </nav>
    </div>
</header>
