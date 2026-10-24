<div aria-hidden="true" role="status">
    <span class="sr-only">Loading shop…</span>

    <div class="rounded-2xl border border-mist bg-white p-4">
        <div class="skeleton skeleton-line is-sm mb-3 w-14"></div>
        <div class="flex gap-2 overflow-hidden">
            @for ($i = 0; $i < 4; $i++)
                <div class="skeleton h-10 w-28 shrink-0 rounded-full"></div>
            @endfor
        </div>
    </div>

    <div class="mt-8 md:grid md:grid-cols-[15.5rem_minmax(0,1fr)] md:gap-6 lg:grid-cols-[16.5rem_minmax(0,1fr)] lg:gap-8">
        <div class="mb-5 flex items-center justify-between md:hidden">
            <div class="skeleton h-10 w-28 rounded-full"></div>
            <div class="skeleton skeleton-line is-sm w-16"></div>
        </div>

        <div class="hidden rounded-2xl border border-mist bg-white p-4 md:block">
            <div class="skeleton skeleton-line is-lg mb-5 w-20"></div>
            <div class="space-y-2">
                @for ($i = 0; $i < 5; $i++)
                    <div class="skeleton h-9 w-full rounded-xl"></div>
                @endfor
            </div>
            <div class="mt-6 space-y-2 border-t border-mist pt-5">
                @for ($i = 0; $i < 4; $i++)
                    <div class="skeleton h-9 w-full rounded-xl"></div>
                @endfor
            </div>
        </div>

        <div>
            <div class="mb-5 border-b border-mist pb-4">
                <div class="skeleton skeleton-line is-lg w-36"></div>
                <div class="skeleton skeleton-line is-sm mt-2 w-24"></div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-5">
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
    </div>
</div>
