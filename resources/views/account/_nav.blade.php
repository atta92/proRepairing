@php
    $items = [
        ['route' => 'account.dashboard', 'label' => 'Overview', 'match' => 'account.dashboard'],
        ['route' => 'account.repairs', 'label' => 'Repair bookings', 'match' => 'account.repairs*'],
        ['route' => 'account.shop', 'label' => 'Shop orders', 'match' => 'account.shop*'],
        ['route' => 'account.profile', 'label' => 'Account settings', 'match' => 'account.profile'],
    ];
@endphp

<nav class="flex flex-wrap items-center gap-2 border-b border-mist pb-4" aria-label="Account">
    @foreach ($items as $item)
        <a
            href="{{ route($item['route']) }}"
            class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs($item['match']) ? 'bg-accent text-white' : 'bg-white text-steel hover:bg-mist' }}"
        >
            {{ $item['label'] }}
        </a>
    @endforeach

    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
        @csrf
        <button type="submit" class="rounded-lg px-3 py-2 text-sm font-medium text-signal hover:bg-signal/10">
            Sign out
        </button>
    </form>
</nav>
