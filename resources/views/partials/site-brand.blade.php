@php
    $name = ($siteSettings ?? null)?->displayName() ?: config('app.name');
    $logo = ($siteSettings ?? null)?->logoUrl();
    $tag = $tag ?? 'span';
    $textClass = $textClass ?? 'font-display text-xl font-bold tracking-tight text-white sm:text-2xl';
    $logoClass = $logoClass ?? 'h-9 w-auto max-w-[10rem] object-contain sm:h-10';
@endphp

<a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 {{ $linkClass ?? '' }}">
    @if ($logo)
        <img src="{{ $logo }}" alt="{{ $name }}" class="{{ $logoClass }}">
        <span class="sr-only">{{ $name }}</span>
    @else
        <{{ $tag }} class="{{ $textClass }}">{{ $name }}</{{ $tag }}>
    @endif
</a>
