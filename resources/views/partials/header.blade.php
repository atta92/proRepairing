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

<header
    id="site-header"
    class="site-header sticky top-0 z-50 overflow-visible transition-[background-color,color,box-shadow] duration-300"
>
    <div
        class="site-promo-bar relative z-[1] border-b border-accent/40 bg-accent text-white"
        x-data="{
            messages: [
                'Same-day repairs',
                '12-month warranty',
                'Free UK mail-in',
            ],
            index: 0,
            fading: false,
            busy: false,
            timer: null,
            go(step) {
                if (this.busy) return;
                this.busy = true;
                this.fading = true;
                setTimeout(() => {
                    this.index = (this.index + step + this.messages.length) % this.messages.length;
                    this.fading = false;
                    setTimeout(() => { this.busy = false; }, 250);
                }, 180);
                this.restart();
            },
            next() { this.go(1); },
            prev() { this.go(-1); },
            restart() {
                clearInterval(this.timer);
                this.timer = setInterval(() => this.go(1), 3000);
            },
            init() {
                this.restart();
            },
        }"
        @mouseenter="clearInterval(timer)"
        @mouseleave="restart()"
    >
        <div class="site-promo-track mx-auto flex items-center justify-center px-4 py-1">
            <button
                type="button"
                class="site-promo-arrow inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-white/90 transition hover:bg-white/15 hover:text-white"
                aria-label="Previous announcement"
                data-no-spinner
                @click="prev()"
            >
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.832 10l3.938 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" />
                </svg>
            </button>

            <p
                class="site-promo-text text-center text-[11px] font-medium leading-none sm:text-xs"
                :class="fading ? 'is-fading' : ''"
                x-text="messages[index]"
                aria-live="polite"
            >Same-day repairs</p>

            <button
                type="button"
                class="site-promo-arrow inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-white/90 transition hover:bg-white/15 hover:text-white"
                aria-label="Next announcement"
                data-no-spinner
                @click="next()"
            >
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <div class="site-container relative z-[1] flex items-center justify-between gap-4 py-4">
        @include('partials.site-brand', [
            'textClass' => 'site-brand-text font-display text-xl font-bold tracking-tight transition-colors duration-300 sm:text-2xl',
        ])

        <nav class="hidden min-w-0 flex-1 items-center justify-center gap-0.5 nav:flex" aria-label="Primary" id="desktop-nav">
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
                            class="site-nav-dropdown absolute left-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-56 rounded-xl border p-2 shadow-2xl"
                            role="menu"
                        >
                            @foreach ($menu['items'] as $category)
                                <a
                                    href="{{ route('categories.show', $category->slug) }}"
                                    class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm"
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
                    class="site-nav-dropdown absolute left-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-56 rounded-xl border p-2 shadow-2xl"
                    role="menu"
                >
                    <a href="{{ route('pages.about') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">About us</a>
                    <a href="{{ route('pages.privacy') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">Privacy policy</a>
                    <a href="{{ route('pages.terms') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">Terms &amp; conditions</a>
                </div>
            </div>
        </nav>

        <div class="site-header-actions relative z-10 flex shrink-0 items-center gap-3 pl-2 transition-colors duration-300">
            <button
                type="button"
                data-bag-open
                class="site-header-icon relative inline-flex h-10 w-10 items-center justify-center overflow-visible rounded-lg border transition"
                aria-label="Shopping bag{{ ($shopCartCount ?? 0) > 0 ? ' (' . $shopCartCount . ' items)' : '' }}"
                @click="$store.bag.openDrawer()"
            >
                <i class="bi bi-bag text-base leading-none" aria-hidden="true"></i>
                @if (($shopCartCount ?? 0) > 0)
                    <span class="cart-count">{{ $shopCartCount > 99 ? '99+' : $shopCartCount }}</span>
                @endif
            </button>

            @auth
                <div class="relative hidden sm:block" data-nav-dropdown>
                    <button
                        type="button"
                        class="site-header-chip inline-flex items-center gap-1.5 rounded-lg border px-3 py-2 text-sm font-medium transition"
                        data-nav-trigger
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <i class="bi bi-person" aria-hidden="true"></i>
                        <span>
                            @if (auth()->user()->is_admin)
                                Admin
                            @elseif (auth()->user()->isApprovedVendor())
                                Seller
                            @elseif (auth()->user()->hasVendorAccount())
                                Seller
                            @else
                                Account
                            @endif
                        </span>
                        <svg class="h-3.5 w-3.5 shrink-0 opacity-70" data-nav-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div
                        data-nav-panel
                        class="site-nav-dropdown absolute right-0 top-[calc(100%+0.35rem)] z-[60] hidden min-w-48 rounded-xl border p-2 shadow-2xl"
                        role="menu"
                    >
                        @if (auth()->user()->is_admin)
                            <a href="{{ url('/admin') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">Admin panel</a>
                        @elseif (auth()->user()->isApprovedVendor())
                            <a href="{{ url('/seller') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">Seller centre</a>
                            <a href="{{ url('/seller/products') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">My products</a>
                            <a href="{{ url('/seller/orders') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">My orders</a>
                        @elseif (auth()->user()->hasVendorAccount())
                            <a href="{{ route('seller.home') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">Application status</a>
                        @else
                            <a href="{{ route('account.dashboard') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">Dashboard</a>
                            <a href="{{ route('account.profile') }}" class="site-nav-dropdown-link block rounded-lg px-3 py-2 text-sm" role="menuitem">Settings</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="site-nav-dropdown-link block w-full rounded-lg px-3 py-2 text-left text-sm" role="menuitem">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a
                    href="{{ route('login') }}"
                    class="site-header-chip hidden rounded-lg border px-3 py-2 text-sm font-medium transition sm:inline-flex"
                >
                    Sign in
                </a>
                <a href="{{ route('register') }}" class="btn-primary hidden sm:inline-flex">
                    Create account
                </a>
            @endauth

            <a href="#" class="btn-primary hidden sm:inline-flex" data-quote-open>
                Get a Quote
            </a>

            <button
                id="nav-toggle"
                type="button"
                class="site-header-icon inline-flex h-10 w-10 items-center justify-center rounded-lg border transition nav:hidden"
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

    <div id="nav-panel" class="site-mobile-nav relative z-[1] hidden border-t nav:hidden" role="dialog" aria-label="Mobile menu">
        <nav class="site-container flex flex-col gap-1 py-4">
            <a href="{{ route('home') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Home</a>

            @foreach ($desktopMenus as $menu)
                @if ($menu['items']->isNotEmpty())
                    <div data-mobile-accordion class="rounded-lg">
                        <button
                            type="button"
                            class="site-mobile-nav-link flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium"
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
                                <a href="{{ route('categories.show', $category->slug) }}" class="site-mobile-nav-sublink block rounded-lg px-3 py-2 text-sm">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <a href="{{ route('shop.index') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Shop</a>
            <a href="{{ route('pages.contact') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Contact</a>

            <div data-mobile-accordion class="rounded-lg">
                <button
                    type="button"
                    class="site-mobile-nav-link flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm font-medium"
                    data-mobile-trigger
                    aria-expanded="false"
                >
                    <span>About</span>
                    <svg class="h-4 w-4 transition" data-mobile-chevron viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div data-mobile-panel class="hidden space-y-1 pb-2 pl-3">
                    <a href="{{ route('pages.about') }}" class="site-mobile-nav-sublink block rounded-lg px-3 py-2 text-sm">About us</a>
                    <a href="{{ route('pages.privacy') }}" class="site-mobile-nav-sublink block rounded-lg px-3 py-2 text-sm">Privacy policy</a>
                    <a href="{{ route('pages.terms') }}" class="site-mobile-nav-sublink block rounded-lg px-3 py-2 text-sm">Terms &amp; conditions</a>
                </div>
            </div>

            @auth
                @if (auth()->user()->is_admin)
                    <a href="{{ url('/admin') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Admin panel</a>
                @elseif (auth()->user()->isApprovedVendor())
                    <a href="{{ url('/seller') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Seller centre</a>
                    <a href="{{ url('/seller/products') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">My products</a>
                @elseif (auth()->user()->hasVendorAccount())
                    <a href="{{ route('seller.home') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Application status</a>
                @else
                    <a href="{{ route('account.dashboard') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">My account</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="site-mobile-nav-link w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium">Sign out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Sign in</a>
                <a href="{{ route('register') }}" class="site-mobile-nav-link rounded-lg px-3 py-2.5 text-sm font-medium">Create account</a>
            @endauth

            <a href="#" class="btn-primary mt-2 w-full sm:hidden" data-quote-open>Get a Quote</a>
        </nav>
    </div>
</header>
