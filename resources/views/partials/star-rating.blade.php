@php
    $rating = (float) ($rating ?? 0);
    $size = $size ?? 'md';
    $tone = $tone ?? 'accent';
    $classes = $size === 'sm' ? 'text-sm' : ($size === 'lg' ? 'text-xl' : 'text-base');
    $toneClass = match ($tone) {
        'gold' => 'text-[#f5b301]',
        'muted' => 'text-mist',
        default => 'text-accent',
    };
    $emptyClass = $tone === 'muted' ? 'text-mist' : 'opacity-35';
@endphp
<span class="inline-flex items-center gap-0.5 {{ $toneClass }} {{ $classes }}" aria-label="{{ number_format($rating, 1) }} out of 5 stars">
    @for ($i = 1; $i <= 5; $i++)
        @if ($rating >= $i)
            <i class="bi bi-star-fill" aria-hidden="true"></i>
        @elseif ($rating >= $i - 0.5)
            <i class="bi bi-star-half" aria-hidden="true"></i>
        @else
            <i class="bi bi-star {{ $emptyClass }}" aria-hidden="true"></i>
        @endif
    @endfor
</span>
