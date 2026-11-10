@extends('errors.layout')

@section('title', 'Something went wrong')

@section('content')
    <p class="font-display text-7xl font-extrabold tracking-tight text-accent sm:text-8xl">500</p>
    <div class="mx-auto mt-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-signal/10 text-signal">
        <i class="bi bi-exclamation-triangle text-2xl" aria-hidden="true"></i>
    </div>
    <h1 class="mt-5 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
        Something went wrong
    </h1>
    <p class="mt-3 text-sm leading-relaxed text-steel sm:text-base">
        We’re having a technical issue on our side. Please try again in a moment — your booking or order data is safe.
    </p>
    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <button type="button" class="btn-primary" data-no-spinner onclick="window.location.reload()">Try again</button>
        <a href="{{ url('/') }}" class="btn-ghost">Go to homepage</a>
    </div>
@endsection
