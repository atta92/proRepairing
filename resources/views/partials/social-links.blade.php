@php
    $links = ($siteSettings ?? null)?->socialLinks() ?? [];
    $wrapClass = $wrapClass ?? 'mt-5 flex flex-wrap gap-2';
    $linkClass = $linkClass ?? 'inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/15 text-white/70 transition hover:border-accent hover:bg-accent/20 hover:text-white';
@endphp

@if (count($links))
    <div class="{{ $wrapClass }}" aria-label="Social links">
        @foreach ($links as $link)
            <a
                href="{{ $link['url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="{{ $linkClass }}"
                aria-label="{{ $link['label'] }}"
            >
                <i class="bi {{ $link['icon'] }}" aria-hidden="true"></i>
            </a>
        @endforeach
    </div>
@endif
