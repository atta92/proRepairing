<div aria-hidden="true" role="status">
    <span class="sr-only">Loading shop…</span>

    <div class="flex flex-wrap gap-2.5">
        @for ($i = 0; $i < 5; $i++)
            <div class="skeleton h-10 w-24 rounded-full"></div>
        @endfor
    </div>

    <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
        @for ($i = 0; $i < 8; $i++)
            <div class="overflow-hidden rounded-2xl border border-mist bg-white">
                <div class="skeleton aspect-square w-full rounded-none"></div>
                <div class="space-y-2 p-3.5 sm:p-5">
                    <div class="skeleton skeleton-line is-sm w-16"></div>
                    <div class="skeleton skeleton-line is-lg w-4/5"></div>
                    <div class="skeleton skeleton-line is-sm hidden w-full sm:block"></div>
                    <div class="skeleton skeleton-line mt-3 w-20"></div>
                </div>
            </div>
        @endfor
    </div>
</div>
