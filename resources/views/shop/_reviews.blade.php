@php
    $reviewPayload = $reviews->map(fn ($review) => [
        'id' => $review->id,
        'author' => $review->author_name,
        'initials' => collect(preg_split('/\s+/', trim($review->author_name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode(''),
        'rating' => (int) $review->rating,
        'title' => $review->title,
        'body' => $review->body,
        'image' => $review->image_path ? asset('storage/'.$review->image_path) : null,
        'verified' => (bool) $review->is_verified,
        'date' => $review->created_at->format('d M Y'),
        'timestamp' => $review->created_at->timestamp,
        'relative' => $review->created_at->diffForHumans(),
    ])->values();
@endphp

<section
    id="reviews"
    class="scroll-mt-24 border-t border-mist bg-white py-12 sm:py-16"
    x-data="productReviews({
        reviews: @js($reviewPayload),
        average: {{ $averageRating ? (float) $averageRating : 'null' }},
        count: {{ (int) $reviewsCount }},
        breakdown: @js($ratingBreakdown),
        formOpen: {{ ($errors->any() || session('success')) ? 'true' : 'false' }},
        oldRating: {{ (int) old('rating', 5) }},
        oldBody: @js(old('body', '')),
    })"
>
    <div class="site-container">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-accent">Customer reviews</p>
                <h2 class="mt-1 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                    What customers think
                </h2>
                <p class="mt-2 text-sm leading-relaxed text-steel">
                    Real feedback from people who bought {{ $product->name }}.
                </p>
            </div>

            <button
                type="button"
                class="btn-primary shrink-0 rounded-full"
                @click="formOpen = !formOpen; if (formOpen) $nextTick(() => document.getElementById('write-review')?.scrollIntoView({ behavior: 'smooth', block: 'start' }))"
            >
                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                <span x-text="formOpen ? 'Hide form' : 'Write a review'"></span>
            </button>
        </div>

        @if ($reviewsCount > 0 && $averageRating)
            <div class="mt-8 grid gap-6 lg:grid-cols-12">
                {{-- Score summary --}}
                <div class="lg:col-span-4">
                    <div class="rounded-3xl border border-mist bg-[#f6f7f9] p-6">
                        <div class="flex items-end gap-3">
                            <p class="font-display text-5xl font-bold leading-none text-ink">{{ number_format($averageRating, 1) }}</p>
                            <div class="pb-1">
                                @include('partials.star-rating', ['rating' => $averageRating, 'size' => 'lg', 'tone' => 'gold'])
                                <p class="mt-1 text-sm text-steel">{{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }}</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2.5">
                            @foreach ($ratingBreakdown as $stars => $count)
                                @php $pct = $reviewsCount > 0 ? round(($count / $reviewsCount) * 100) : 0; @endphp
                                <button
                                    type="button"
                                    class="group flex w-full items-center gap-3 rounded-xl px-1 py-1 text-left text-sm transition hover:bg-white/80"
                                    @click="setFilter({{ $stars }})"
                                    :class="filter === {{ $stars }} ? 'bg-white shadow-sm ring-1 ring-mist' : ''"
                                >
                                    <span class="w-10 shrink-0 font-medium text-ink">{{ $stars }} ★</span>
                                    <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-white">
                                        <div class="h-full rounded-full bg-[#f5b301] transition group-hover:bg-[#e5a800]" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="w-10 text-right tabular-nums text-steel">{{ $pct }}%</span>
                                </button>
                            @endforeach
                        </div>

                        <button
                            type="button"
                            class="mt-4 text-sm font-semibold text-accent hover:text-accent-dark"
                            x-show="filter !== 'all'"
                            x-cloak
                            @click="setFilter('all')"
                        >
                            Show all ratings
                        </button>
                    </div>
                </div>

                {{-- Review list --}}
                <div class="lg:col-span-8">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-steel">
                            Showing
                            <span class="font-semibold text-ink" x-text="filtered.length"></span>
                            of {{ $reviewsCount }}
                            <span x-show="filter !== 'all'" x-cloak>
                                · <span class="font-semibold text-ink" x-text="filter + '★'"></span>
                            </span>
                        </p>
                        <label class="inline-flex items-center gap-2 text-sm text-steel">
                            <span class="shrink-0">Sort</span>
                            <select
                                class="rounded-full border border-mist bg-white px-3 py-2 text-sm font-medium text-ink"
                                x-model="sort"
                            >
                                <option value="newest">Newest</option>
                                <option value="highest">Highest rating</option>
                                <option value="lowest">Lowest rating</option>
                            </select>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <template x-for="review in visibleReviews" :key="review.id">
                            <article class="rounded-3xl border border-mist bg-white p-5 shadow-[0_1px_0_rgba(15,23,32,0.03)] sm:p-6">
                                <div class="flex items-start gap-3 sm:gap-4">
                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-ink text-sm font-bold text-white"
                                        x-text="review.initials || '?'"
                                    ></div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <p class="font-semibold text-ink" x-text="review.author"></p>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-accent/10 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-accent-dark"
                                                x-show="review.verified"
                                                x-cloak
                                                title="Bought this product from us"
                                            >
                                                <i class="bi bi-patch-check-fill text-[10px]" aria-hidden="true"></i>
                                                Verified purchase
                                            </span>
                                        </div>
                                        <div class="mt-1.5 flex flex-wrap items-center gap-2">
                                            <span class="inline-flex gap-0.5 text-[#f5b301]" aria-hidden="true">
                                                <template x-for="n in 5" :key="n">
                                                    <i class="bi text-sm" :class="n <= review.rating ? 'bi-star-fill' : 'bi-star text-mist'"></i>
                                                </template>
                                            </span>
                                            <time class="text-xs text-steel" x-text="review.relative"></time>
                                        </div>

                                        <h3 class="mt-3 font-display text-lg font-semibold text-ink" x-show="review.title" x-text="review.title"></h3>
                                        <p
                                            class="mt-2 text-sm leading-relaxed text-steel whitespace-pre-line"
                                            :class="expanded[review.id] || review.body.length < 220 ? '' : 'line-clamp-4'"
                                            x-text="review.body"
                                        ></p>
                                        <button
                                            type="button"
                                            class="mt-2 text-sm font-semibold text-accent hover:text-accent-dark"
                                            x-show="review.body.length >= 220"
                                            x-cloak
                                            @click="expanded[review.id] = !expanded[review.id]"
                                            x-text="expanded[review.id] ? 'Show less' : 'Read more'"
                                        ></button>

                                        <button
                                            type="button"
                                            class="mt-4 block overflow-hidden rounded-2xl border border-mist bg-snow focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                            x-show="review.image"
                                            x-cloak
                                            @click="$store.lightbox.show(review.image, review.title || review.author + ' review photo', 'Review photo')"
                                        >
                                            <img
                                                :src="review.image"
                                                :alt="(review.title || review.author) + ' review photo'"
                                                class="max-h-56 w-full object-cover transition hover:opacity-95 sm:max-h-64"
                                                loading="lazy"
                                            >
                                        </button>
                                    </div>
                                </div>
                            </article>
                        </template>

                        <div
                            class="rounded-3xl border border-dashed border-mist bg-snow px-5 py-10 text-center"
                            x-show="filtered.length === 0"
                            x-cloak
                        >
                            <p class="font-medium text-ink">No reviews for this rating yet.</p>
                            <button type="button" class="mt-3 text-sm font-semibold text-accent" @click="setFilter('all')">
                                View all reviews
                            </button>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-ghost mt-6 w-full rounded-full sm:w-auto"
                        x-show="filtered.length > pageSize"
                        x-cloak
                        @click="showAll = !showAll"
                        x-text="showAll ? 'Show fewer reviews' : ('Show all ' + filtered.length + ' reviews')"
                    ></button>
                </div>
            </div>
        @else
            <div class="mt-8 rounded-3xl border border-dashed border-mist bg-[#f6f7f9] px-6 py-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-2xl text-accent shadow-sm">
                    <i class="bi bi-chat-quote" aria-hidden="true"></i>
                </div>
                <h3 class="mt-4 font-display text-xl font-semibold text-ink">No reviews yet</h3>
                <p class="mx-auto mt-2 max-w-md text-sm text-steel">
                    Be the first to share how {{ $product->name }} worked for you. Honest ratings help other buyers decide.
                </p>
                <button type="button" class="btn-primary mt-6 rounded-full" @click="formOpen = true">
                    Write the first review
                </button>
            </div>
        @endif

        {{-- Write review --}}
        <div
            id="write-review"
            class="mt-10 scroll-mt-28 overflow-hidden rounded-3xl border border-mist bg-[#f6f7f9]"
            x-show="formOpen"
            x-cloak
            x-transition
        >
            <div class="border-b border-mist bg-white px-5 py-4 sm:px-6">
                <h3 class="font-display text-xl font-semibold text-ink">Write a review</h3>
                <p class="mt-1 text-sm text-steel">Your review is checked before it appears on the site.</p>
            </div>

            <div class="p-5 sm:p-6">
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-accent/30 bg-accent/10 px-4 py-3 text-sm text-ink">
                        {{ session('success') }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('shop.reviews.store', $product) }}"
                    enctype="multipart/form-data"
                    class="space-y-5"
                >
                    @csrf

                    <div>
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-steel">Your rating</p>
                        <div class="flex items-center gap-1" role="radiogroup" aria-label="Rating">
                            <template x-for="n in 5" :key="n">
                                <button
                                    type="button"
                                    class="rounded-lg p-1.5 transition hover:scale-110"
                                    :aria-checked="rating === n"
                                    role="radio"
                                    @click="rating = n"
                                    @mouseenter="hoverRating = n"
                                    @mouseleave="hoverRating = 0"
                                >
                                    <i
                                        class="bi text-2xl"
                                        :class="(hoverRating || rating) >= n ? 'bi-star-fill text-[#f5b301]' : 'bi-star text-mist'"
                                    ></i>
                                </button>
                            </template>
                            <span class="ml-2 text-sm font-medium text-steel" x-text="ratingLabel"></span>
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Name</span>
                            <input type="text" name="author_name" value="{{ old('author_name') }}" required maxlength="120" class="w-full rounded-xl border border-mist bg-white px-3.5 py-2.5 text-sm">
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                            <input
                                type="email"
                                name="author_email"
                                value="{{ old('author_email', auth()->user()?->email) }}"
                                maxlength="255"
                                class="w-full rounded-xl border border-mist bg-white px-3.5 py-2.5 text-sm"
                                placeholder="Used to check verified purchase"
                            >
                            <span class="mt-1 block text-xs text-steel">Optional, but needed for a verified purchase badge if you’ve bought this item.</span>
                        </label>
                    </div>

                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Headline</span>
                        <input type="text" name="title" value="{{ old('title') }}" maxlength="160" placeholder="Sum it up in a few words" class="w-full rounded-xl border border-mist bg-white px-3.5 py-2.5 text-sm">
                    </label>

                    <label class="block">
                        <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Your review</span>
                        <textarea
                            name="body"
                            rows="5"
                            required
                            maxlength="2000"
                            x-model="body"
                            placeholder="What did you like? Anything to watch out for?"
                            class="w-full rounded-xl border border-mist bg-white px-3.5 py-2.5 text-sm"
                        >{{ old('body') }}</textarea>
                        <span class="mt-1.5 block text-right text-xs text-steel" x-text="body.length + ' / 2000'"></span>
                    </label>

                    <div>
                        <p class="mb-1.5 text-xs font-semibold uppercase tracking-wider text-steel">Photo <span class="font-normal normal-case tracking-normal text-steel/70">(optional)</span></p>
                        <label
                            class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border border-dashed border-mist bg-white px-4 py-6 text-center transition hover:border-accent/40"
                            @dragover.prevent
                            @drop.prevent="onPhotoDrop($event)"
                        >
                            <template x-if="!photoPreview">
                                <div>
                                    <i class="bi bi-image text-2xl text-accent" aria-hidden="true"></i>
                                    <p class="mt-2 text-sm font-medium text-ink">Add a photo</p>
                                    <p class="mt-1 text-xs text-steel">JPG, PNG or WebP · max 5MB</p>
                                </div>
                            </template>
                            <template x-if="photoPreview">
                                <div class="w-full">
                                    <img :src="photoPreview" alt="Selected review photo" class="mx-auto max-h-40 rounded-xl object-contain">
                                    <p class="mt-2 truncate text-xs text-steel" x-text="photoName"></p>
                                </div>
                            </template>
                            <input
                                type="file"
                                name="image"
                                accept="image/jpeg,image/png,image/webp,image/gif"
                                class="sr-only"
                                @change="onPhotoChange($event)"
                            >
                        </label>
                        <button
                            type="button"
                            class="mt-2 text-sm font-semibold text-signal-dark hover:underline"
                            x-show="photoPreview"
                            x-cloak
                            @click="clearPhoto()"
                        >
                            Remove photo
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="btn-primary rounded-full">Submit review</button>
                        <button type="button" class="btn-ghost rounded-full" @click="formOpen = false">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
