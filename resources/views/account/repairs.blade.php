@extends('layouts.app')

@section('title', 'Repair bookings - ' . $siteSettings->displayName())

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Repair bookings</h1>
        </div>
    </section>

    <section class="bg-snow py-10 sm:py-14">
        <div class="site-container space-y-8">
            @include('account._nav')

            <div class="overflow-hidden rounded-2xl border border-mist bg-white">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-mist bg-snow text-xs uppercase tracking-wider text-steel">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Reference</th>
                            <th class="px-4 py-3 font-semibold">Device</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Total</th>
                            <th class="px-4 py-3 font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-mist">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-4 py-3 font-medium text-ink">{{ $order->reference }}</td>
                                <td class="px-4 py-3 text-steel">{{ $order->device?->name }}</td>
                                <td class="px-4 py-3 text-steel">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                                <td class="px-4 py-3 text-ink">£{{ number_format((float) $order->total, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('account.repairs.show', $order) }}" class="font-semibold text-accent hover:text-accent-dark">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-steel">No repair bookings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($orders, 'links'))
                <div>{{ $orders->links() }}</div>
            @endif
        </div>
    </section>
@endsection
