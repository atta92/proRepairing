@extends('errors.layout')

@section('title', 'We’ll be right back')

@section('content')
    <p class="font-display text-7xl font-extrabold tracking-tight text-accent sm:text-8xl">503</p>
    <div class="mx-auto mt-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/10 text-accent">
        <i class="bi bi-tools text-2xl" aria-hidden="true"></i>
    </div>
    <h1 class="mt-5 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
        We’ll be right back
    </h1>
    <p class="mt-3 text-sm leading-relaxed text-steel sm:text-base">
        The site is temporarily down for maintenance. Please check back shortly.
    </p>
    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <button type="button" class="btn-primary" data-no-spinner onclick="window.location.reload()">Refresh</button>
    </div>
@endsection
