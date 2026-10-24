@extends('layouts.app')

@section('title', 'Seller application pending - ' . $siteSettings->displayName())

@section('content')
    <section class="bg-snow py-16 sm:py-24">
        <div class="site-container max-w-xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-accent">Seller centre</p>
            <h1 class="mt-2 font-display text-3xl font-bold text-ink">Application under review</h1>
            <p class="mt-4 text-steel">
                Your shop <span class="font-semibold text-ink">{{ $vendor->name }}</span> is waiting for admin approval.
                You can’t list products until you’re approved. We’ll email <span class="font-semibold text-ink">{{ $vendor->email }}</span> when you’re ready.
            </p>
            <p class="mt-3 text-sm text-steel">
                Status:
                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wide text-amber-800">
                    {{ $vendor->status }}
                </span>
            </p>
            <p class="mt-6 rounded-xl border border-mist bg-white px-4 py-3 text-sm text-steel">
                After approval, open <a href="{{ url('/seller') }}" class="font-semibold text-accent hover:underline">Seller centre</a>
                to add products and manage orders — or use <strong>Seller</strong> in the site menu.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="btn-primary">Back to home</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-ghost">Sign out</button>
                </form>
            </div>
        </div>
    </section>
@endsection
