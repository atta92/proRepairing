<div
    x-data
    x-cloak
    x-show="$store.lightbox.open"
    x-transition.opacity.duration.150ms
    @keydown.escape.window="if ($store.lightbox.open) $store.lightbox.close()"
    class="fixed inset-0 z-[100]"
    role="dialog"
    aria-modal="true"
    :aria-hidden="! $store.lightbox.open"
    :aria-label="($store.lightbox.label || 'Image') + ' preview'"
>
    <div
        class="absolute inset-0 bg-ink/85"
        @click="$store.lightbox.close()"
    ></div>

    <button
        type="button"
        class="absolute right-4 top-4 z-20 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-ink shadow-lg transition hover:bg-snow"
        aria-label="Close image"
        data-no-spinner
        @click="$store.lightbox.close()"
    >
        <i class="bi bi-x-lg text-lg" aria-hidden="true"></i>
    </button>

    <div class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center p-4 sm:p-8">
        <div class="pointer-events-auto flex max-h-full w-full max-w-4xl flex-col items-center gap-3">
            <span
                class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white"
                x-text="$store.lightbox.label"
                x-show="$store.lightbox.label"
            ></span>
            <img
                :src="$store.lightbox.src"
                :alt="$store.lightbox.alt"
                class="h-auto max-h-[min(80vh,52rem)] w-auto max-w-full rounded-xl object-contain shadow-2xl"
                @click.stop
            >
        </div>
    </div>
</div>
<?php /**PATH D:\laravel\repairing\resources\views/partials/image-lightbox.blade.php ENDPATH**/ ?>