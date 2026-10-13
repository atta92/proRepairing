@extends('layouts.app')

@section('title', 'Blog — ' . $siteSettings->displayName())

@section('meta_description', 'Repair tips, device guides and news from ' . $siteSettings->displayName() . '.')

@section('content')
    <section class="site-grid-bg bg-ink py-14 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">Blog</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                Tips, guides &amp; repair news
            </h1>
            <p class="mt-3 max-w-2xl text-base text-white/70">
                Practical advice from our technicians — screens, batteries, water damage and more.
            </p>
        </div>
    </section>

    <section class="bg-snow py-14 sm:py-16">
        <div class="site-container">
            @if ($posts->isEmpty())
                <p class="text-steel">No posts published yet. Check back soon.</p>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        @include('blog._card', ['post' => $post])
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
