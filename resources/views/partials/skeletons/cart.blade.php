<div class="grid gap-8 lg:grid-cols-5" aria-hidden="true" role="status">
    <span class="sr-only">Loading bag…</span>
    <div class="space-y-4 lg:col-span-3">
        @for ($i = 0; $i < 3; $i++)
            <div class="flex gap-4 rounded-2xl border border-mist bg-white p-4 sm:p-5">
                <div class="skeleton h-20 w-20 shrink-0 rounded-xl sm:h-24 sm:w-24"></div>
                <div class="min-w-0 flex-1 space-y-3 py-1">
                    <div class="skeleton skeleton-line is-lg w-3/4 max-w-[16rem]"></div>
                    <div class="skeleton skeleton-line is-sm w-20"></div>
                    <div class="skeleton skeleton-line w-16"></div>
                    <div class="mt-2 flex gap-2">
                        <div class="skeleton h-8 w-28 rounded-lg"></div>
                        <div class="skeleton h-8 w-16 rounded-lg"></div>
                    </div>
                </div>
                <div class="skeleton skeleton-line is-lg mt-1 w-14 shrink-0"></div>
            </div>
        @endfor
    </div>

    <aside class="lg:col-span-2">
        <div class="rounded-2xl border border-mist bg-white p-5 sm:p-6">
            <div class="skeleton skeleton-line is-lg w-28"></div>
            <div class="mt-5 space-y-3">
                <div class="flex justify-between gap-4">
                    <div class="skeleton skeleton-line w-20"></div>
                    <div class="skeleton skeleton-line w-16"></div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="skeleton skeleton-line w-14"></div>
                    <div class="skeleton skeleton-line w-8"></div>
                </div>
            </div>
            <div class="skeleton mt-4 h-3 w-full max-w-[14rem]"></div>
            <div class="skeleton mt-6 h-11 w-full rounded-lg"></div>
            <div class="skeleton mx-auto mt-4 h-3 w-32"></div>
        </div>
    </aside>
</div>
