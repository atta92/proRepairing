<x-mail::message>
# Repair booking confirmed

Thanks {{ $order->customer?->name }}. We’ve received your payment for booking **{{ $order->reference }}**.

**Device:** {{ $order->device?->name }}  
**Total paid:** £{{ number_format((float) $order->total, 2) }}  
**Fulfilment:** {{ ucfirst(str_replace('_', ' ', $order->fulfilment)) }}  
@if ($order->scheduled_date)
**Scheduled:** {{ $order->scheduled_date->format('d M Y') }}{{ $order->scheduled_time ? ' · '.$order->scheduled_time : '' }}
@endif

<x-mail::panel>
@foreach ($order->items as $item)
{{ $item->name }} — £{{ number_format((float) $item->price, 2) }}  
@endforeach
</x-mail::panel>

Keep this email for your records. If you have an account, your bookings will appear in your dashboard.

<x-mail::button :url="route('home')">
Visit website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
