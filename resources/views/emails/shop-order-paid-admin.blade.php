<x-mail::message>
# New paid shop order

Order **{{ $order->reference }}** has been paid online.

**Customer:** {{ $order->customer?->name }} ({{ $order->customer?->email }})  
**Phone:** {{ $order->customer?->phone }}  
**Total:** £{{ number_format((float) $order->total, 2) }}  
**Fulfilment:** {{ ucfirst($order->fulfilment) }}

<x-mail::panel>
@foreach ($order->items as $item)
{{ $item->name }} × {{ $item->quantity }} - £{{ number_format((float) $item->price * $item->quantity, 2) }}  
@endforeach
</x-mail::panel>

<x-mail::button :url="url('/admin/shop-orders/'.$order->id.'/edit')">
Open in admin
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
