<x-mail::message>
# New paid repair booking

Booking **{{ $order->reference }}** has been paid online.

**Customer:** {{ $order->customer?->name }} ({{ $order->customer?->email }})  
**Phone:** {{ $order->customer?->phone }}  
**Device:** {{ $order->device?->name }}  
**Total:** £{{ number_format((float) $order->total, 2) }}  
**Fulfilment:** {{ ucfirst(str_replace('_', ' ', $order->fulfilment)) }}

<x-mail::panel>
@foreach ($order->items as $item)
{{ $item->name }} - £{{ number_format((float) $item->price, 2) }}  
@endforeach
</x-mail::panel>

<x-mail::button :url="url('/admin/orders/'.$order->id.'/edit')">
Open in admin
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
