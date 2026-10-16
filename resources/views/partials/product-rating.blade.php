@php
    $count = (int) ($count ?? ($product->approved_reviews_count ?? 0));
    $average = $average ?? ($product->approved_reviews_avg_rating ?? null);
    $size = $size ?? 'sm';
    $link = $link ?? null;
    $class = $class ?? 'mt-1.5';
    $hasReviews = $count > 0 && $average;
    $displayRating = $hasReviews ? (float) $average : 0.0;
    $wrapperClass = $class.' inline-flex flex-wrap items-center gap-1.5 text-xs sm:text-sm '.($hasReviews ? 'text-steel' : 'text-steel/60');
@endphp

@if ($link)
    <a href="{{ $link }}" class="{{ $wrapperClass }} hover:text-accent">
        @include('partials.star-rating', [
            'rating' => $displayRating,
            'size' => $size,
            'tone' => $hasReviews ? 'gold' : 'muted',
        ])
        @if ($hasReviews)
            <span class="font-semibold text-ink">{{ number_format($displayRating, 1) }}</span>
            <span>({{ $count }} {{ Str::plural('review', $count) }})</span>
        @else
            <span>No reviews yet</span>
        @endif
    </a>
@else
    <p class="{{ $wrapperClass }}">
        @include('partials.star-rating', [
            'rating' => $displayRating,
            'size' => $size,
            'tone' => $hasReviews ? 'gold' : 'muted',
        ])
        @if ($hasReviews)
            <span class="font-semibold text-ink">{{ number_format($displayRating, 1) }}</span>
            <span>({{ $count }})</span>
        @else
            <span>No reviews yet</span>
        @endif
    </p>
@endif
