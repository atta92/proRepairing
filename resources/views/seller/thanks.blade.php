@extends('layouts.app')

@section('title', 'Application received - ' . $siteSettings->displayName())

@section('content')
    <section class="bg-snow py-16 sm:py-24">
        <div class="site-container max-w-xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent">Sellers</p>
            <h1 class="mt-2 font-display text-3xl font-bold text-ink">Application received</h1>
            <p class="mt-4 text-steel">
                We’ll review your shop and email you when it’s approved. After approval, sign in at the seller centre to list products.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="btn-primary">Back to home</a>
                <a href="{{ url('/seller/login') }}" class="btn-ghost">Seller login</a>
            </div>
        </div>
    </section>
@endsection
