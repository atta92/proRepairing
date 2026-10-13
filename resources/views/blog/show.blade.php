@extends('layouts.app')

@section('title', $post->title . ' — ' . $siteSettings->displayName())

@section('meta_description', $post->excerpt ?: Str::limit(strip_tags($post->body), 155))

@section('og_type', 'article')

@if ($post->image_path)
    @section('og_image', asset('storage/' . ltrim($post->image_path, '/')))
@endif

@section('content')
    <article>
        <header class="site-grid-bg bg-ink py-14 text-white sm:py-16">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-accent-light transition hover:text-white">
                    ← Back to blog
                </a>
                <h1 class="mt-4 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                    {{ $post->title }}
                </h1>
                <p class="mt-4 flex flex-wrap gap-x-3 gap-y-1 text-sm text-white/65">
                    <span>{{ $post->author_name }}</span>
                    <span aria-hidden="true">·</span>
                    <time datetime="{{ $post->published_at?->toDateString() }}">
                        {{ $post->published_at?->format('d M Y') }}
                    </time>
                </p>
            </div>
        </header>

        @if ($post->image_path)
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <img
                    src="{{ asset('storage/' . $post->image_path) }}"
                    alt=""
                    class="-mt-6 w-full rounded-2xl border border-mist object-cover shadow-lg sm:-mt-8"
                >
            </div>
        @endif

        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
            @if ($post->excerpt)
                <p class="text-lg leading-relaxed text-steel">{{ $post->excerpt }}</p>
            @endif

            <div class="blog-content mt-8">
                {!! $post->body !!}
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="border-t border-mist bg-snow py-14 sm:py-16">
            <div class="site-container">
                <h2 class="font-display text-2xl font-bold tracking-tight text-ink">More from the blog</h2>
                <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
                    @foreach ($related as $item)
                        @include('blog._card', ['post' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
