<div class="grid gap-8 lg:grid-cols-5" aria-hidden="true" role="status">
    <span class="sr-only">Loading checkout…</span>
    <div class="space-y-6 lg:col-span-3">
        <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
            <div class="skeleton skeleton-line is-lg w-32"></div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="space-y-2 sm:col-span-2">
                    <div class="skeleton skeleton-line is-sm w-20"></div>
                    <div class="skeleton h-11 w-full rounded-lg"></div>
                </div>
                <div class="space-y-2">
                    <div class="skeleton skeleton-line is-sm w-14"></div>
                    <div class="skeleton h-11 w-full rounded-lg"></div>
                </div>
                <div class="space-y-2">
                    <div class="skeleton skeleton-line is-sm w-14"></div>
                    <div class="skeleton h-11 w-full rounded-lg"></div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
            <div class="skeleton skeleton-line is-lg w-28"></div>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                <div class="skeleton h-24 w-full rounded-xl"></div>
                <div class="skeleton h-24 w-full rounded-xl"></div>
            </div>
        </div>

        <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
            <div class="skeleton skeleton-line is-lg w-24"></div>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                <div class="skeleton h-24 w-full rounded-xl"></div>
                <div class="skeleton h-24 w-full rounded-xl"></div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="skeleton skeleton-line is-sm w-36"></div>
                <div class="skeleton h-24 w-full rounded-lg"></div>
            </div>
        </div>
    </div>

    <aside class="lg:col-span-2">
        <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
            <div class="skeleton skeleton-line is-lg w-36"></div>
            <div class="mt-5 space-y-3">
                @for ($i = 0; $i < 4; $i++)
                    <div class="flex justify-between gap-3">
                        <div class="skeleton skeleton-line w-[65%]"></div>
                        <div class="skeleton skeleton-line w-12"></div>
                    </div>
                @endfor
            </div>
            <div class="mt-5 space-y-3 border-t border-mist pt-4">
                <div class="flex justify-between gap-3">
                    <div class="skeleton skeleton-line w-20"></div>
                    <div class="skeleton skeleton-line w-14"></div>
                </div>
                <div class="flex justify-between gap-3">
                    <div class="skeleton skeleton-line w-20"></div>
                    <div class="skeleton skeleton-line w-14"></div>
                </div>
                <div class="flex justify-between gap-3">
                    <div class="skeleton skeleton-line is-lg w-16"></div>
                    <div class="skeleton skeleton-line is-lg w-20"></div>
                </div>
            </div>
            <div class="skeleton mt-6 h-11 w-full rounded-lg"></div>
        </div>
    </aside>
</div>
