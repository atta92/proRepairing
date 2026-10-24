@php
    use App\Models\ShopOrderItem;
@endphp
<x-mail::message>
# Order status update

Hi {{ $order->customer?->name }},

@if ($vendor?->name)
**{{ $vendor->name }}** updated fulfilment on your order **{{ $order->reference }}**.
@else
Your order **{{ $order->reference }}** has a fulfilment update.
@endif

<x-mail::panel>
@foreach ($changes as $change)
@php
    $item = $change['item'];
    $from = ShopOrderItem::FULFILMENT_STATUSES[$change['from']] ?? ucfirst(str_replace('_', ' ', $change['from']));
    $to = ShopOrderItem::FULFILMENT_STATUSES[$change['to']] ?? ucfirst(str_replace('_', ' ', $change['to']));
@endphp
**{{ $item->name }}** × {{ $item->quantity }}  
{{ $from }} → **{{ $to }}**

@endforeach
</x-mail::panel>

If you have an account, you can review this order there anytime.

<x-mail::button :url="route('home')">
Visit website
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
