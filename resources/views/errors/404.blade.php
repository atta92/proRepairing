@extends('errors.layout')

@section('title', 'Page not found')

@section('content')
    <p class="font-display text-7xl font-extrabold tracking-tight text-accent sm:text-8xl">404</p>
    <div class="mx-auto mt-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/10 text-accent">
        <i class="bi bi-compass text-2xl" aria-hidden="true"></i>
    </div>
    <h1 class="mt-5 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
        Page not found
    </h1>
    <p class="mt-3 text-sm leading-relaxed text-steel sm:text-base">
        That page doesn’t exist or may have moved. Check the link, or head back and find what you need.
    </p>
    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ url('/') }}" class="btn-primary">Go to homepage</a>
        <a href="{{ url('/shop') }}" class="btn-ghost">Browse shop</a>
    </div>
@endsection
