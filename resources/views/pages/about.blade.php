@extends('layouts.app')

@section('title', ($settings->about_title ?: 'About us') . ' — ' . $siteSettings->displayName())

@section('meta_description', Str::limit(strip_tags($settings->about_body ?? ''), 155))

@section('content')
    <section class="site-grid-bg bg-ink py-14 text-white sm:py-16">
        <div class="site-container">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-light">About</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight sm:text-4xl">
                {{ $settings->about_title ?: 'About us' }}
            </h1>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container">
            <div class="mx-auto max-w-3xl rounded-2xl border border-mist bg-white p-6 sm:p-8">
                <div class="blog-content">
                    {!! $settings->about_body !!}
                </div>
            </div>
        </div>
    </section>
@endsection
