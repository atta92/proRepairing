@php
    $settings = \App\Models\SiteSetting::current();
    $mailInAddress = trim((string) ($settings->mail_in_address ?? ''));
    $isPaid = filled($order->paid_at);
@endphp
<x-mail::message>
# {{ $isPaid ? 'Repair booking paid' : 'Repair booking confirmed' }}

Thanks {{ $order->customer?->name }}.  
@if ($isPaid)
We’ve received your payment for booking **{{ $order->reference }}**.
@else
Your booking **{{ $order->reference }}** is confirmed. You can pay when your repair is complete.
@endif

**Device:** {{ $order->device?->name }}  
**Total:** £{{ number_format((float) $order->total, 2) }}  
**Fulfilment:** {{ ucfirst(str_replace('_', ' ', $order->fulfilment)) }}  
@if ($order->scheduled_date)
**Scheduled:** {{ $order->scheduled_date->format('d M Y') }}{{ $order->scheduled_time ? ' · '.$order->scheduled_time : '' }}
@endif

<x-mail::panel>
@foreach ($order->items as $item)
{{ $item->name }} — £{{ number_format((float) $item->price, 2) }}  
@endforeach
</x-mail::panel>

@if ($mailInAddress !== '' && $order->fulfilment === 'mail_in')
## Where to send your device

Please pack your device securely and post it to:

<x-mail::panel>
{!! nl2br(e($mailInAddress)) !!}
</x-mail::panel>

Include your booking reference **{{ $order->reference }}** inside the parcel. We’ll email a tracked return label after we receive your device.
@endif

Keep this email for your records. If you have an account, your bookings will appear in your dashboard.

<x-mail::button :url="route('home')">
Visit website
</x-mail::button>

Thanks,<br>
{{ $settings->displayName() }}
</x-mail::message>
