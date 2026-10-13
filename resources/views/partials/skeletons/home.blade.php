<div aria-hidden="true" role="status">
    <span class="sr-only">Loading home page…</span>

    {{-- Hero --}}
    <section class="bg-ink py-16 sm:py-20 lg:py-24">
        <div class="site-container grid gap-10 lg:grid-cols-2 lg:items-center lg:gap-16">
            <div class="space-y-4">
                <div class="skeleton h-7 w-44 rounded-full bg-white/15"></div>
                <div class="skeleton h-10 w-full max-w-md rounded-lg bg-white/15"></div>
                <div class="skeleton h-10 w-4/5 max-w-sm rounded-lg bg-white/15"></div>
                <div class="skeleton h-4 w-full max-w-xl rounded bg-white/10"></div>
                <div class="skeleton h-4 w-5/6 max-w-lg rounded bg-white/10"></div>
                <div class="flex flex-wrap gap-2 pt-4">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="skeleton h-8 w-28 rounded-full bg-white/10"></div>
                    @endfor
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white p-5 sm:p-7">
                <div class="skeleton h-6 w-36 rounded-full"></div>
                <div class="skeleton skeleton-line is-lg mt-4 w-3/4"></div>
                <div class="skeleton skeleton-line is-sm mt-2 w-2/3"></div>
                <div class="mt-5 space-y-3.5">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="space-y-2">
                            <div class="skeleton skeleton-line is-sm w-28"></div>
                            <div class="skeleton h-12 w-full rounded-xl"></div>
                        </div>
                    @endfor
                </div>
                <div class="skeleton mt-4 h-20 w-full rounded-xl"></div>
                <div class="skeleton mt-3 h-11 w-full rounded-full"></div>
            </div>
        </div>
    </section>

    {{-- Devices --}}
    <section class="bg-white py-16 sm:py-20">
        <div class="site-container">
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-line mt-3 max-w-md"></div>
            <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                @for ($i = 0; $i < 4; $i++)
                    <div class="rounded-2xl border border-mist bg-snow p-4 sm:p-5">
                        <div class="skeleton skeleton-line is-lg w-3/4"></div>
                        <div class="skeleton skeleton-line is-sm mt-2 w-1/2"></div>
                        <div class="skeleton skeleton-line is-sm mt-4 w-20"></div>
                        <div class="skeleton skeleton-line mt-3 w-24"></div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Shop / cards --}}
    <section class="bg-snow py-16 sm:py-20">
        <div class="site-container">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <div class="skeleton skeleton-title"></div>
                    <div class="skeleton skeleton-line mt-3 max-w-sm"></div>
                </div>
                <div class="skeleton skeleton-line w-28"></div>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                @for ($i = 0; $i < 4; $i++)
                    <div class="overflow-hidden rounded-2xl border border-mist bg-white">
                        <div class="skeleton aspect-square w-full rounded-none"></div>
                        <div class="space-y-2 p-3.5 sm:p-5">
                            <div class="skeleton skeleton-line is-sm w-16"></div>
                            <div class="skeleton skeleton-line is-lg w-4/5"></div>
                            <div class="skeleton skeleton-line mt-3 w-20"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
</div>
