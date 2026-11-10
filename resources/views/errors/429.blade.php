@extends('errors.layout')

@section('title', 'Too many requests')

@section('content')
    <p class="font-display text-7xl font-extrabold tracking-tight text-accent sm:text-8xl">429</p>
    <div class="mx-auto mt-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-signal/10 text-signal">
        <i class="bi bi-speedometer2 text-2xl" aria-hidden="true"></i>
    </div>
    <h1 class="mt-5 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
        Slow down a moment
    </h1>
    <p class="mt-3 text-sm leading-relaxed text-steel sm:text-base">
        You’ve made too many requests in a short time. Wait a few seconds, then try again.
    </p>
    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ url('/') }}" class="btn-primary">Go to homepage</a>
    </div>
@endsection
