<x-mail::message>
# Payment successful

Thanks {{ $order->customer?->name }}. We’ve received your payment for order **{{ $order->reference }}**.

**Total paid:** £{{ number_format((float) $order->total, 2) }}  
**Fulfilment:** {{ ucfirst($order->fulfilment) }}

<x-mail::panel>
@foreach ($order->items as $item)
{{ $item->name }} × {{ $item->quantity }} — £{{ number_format((float) $item->price * $item->quantity, 2) }}  
@endforeach
</x-mail::panel>

Keep this email for your records. If you have an account, you can also review orders there anytime.

<x-mail::button :url="route('home')">
Visit website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
