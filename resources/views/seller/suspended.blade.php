@extends('layouts.app')

@section('title', 'Seller account suspended - ' . $siteSettings->displayName())

@section('content')
    <section class="bg-snow py-16 sm:py-24">
        <div class="site-container max-w-xl text-center">
            <h1 class="font-display text-3xl font-bold text-ink">Shop suspended</h1>
            <p class="mt-4 text-steel">
                Access to <span class="font-semibold text-ink">{{ $vendor->name }}</span> is temporarily suspended.
                Please contact support for help.
            </p>
            <div class="mt-8">
                <a href="{{ route('pages.contact') }}" class="btn-primary">Contact us</a>
            </div>
        </div>
    </section>
@endsection
