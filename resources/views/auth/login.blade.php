@extends('layouts.app')

@section('title', 'Sign in — ' . $siteSettings->displayName())

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Sign in</h1>
            <p class="mt-2 text-white/70">Access your repairs, shop orders and account settings.</p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container max-w-md">
            <form method="POST" action="{{ route('login') }}" class="rounded-2xl border border-mist bg-white p-6 sm:p-8">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                        {{ $errors->first() }}
                    </div>
                @endif

                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>

                <label class="mt-4 block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Password</span>
                    <input type="password" name="password" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>

                <label class="mt-4 inline-flex items-center gap-2 text-sm text-steel">
                    <input type="checkbox" name="remember" value="1" class="rounded border-mist text-accent focus:ring-accent">
                    Remember me
                </label>

                <button type="submit" class="btn-primary mt-6 w-full">Sign in</button>

                <p class="mt-5 text-center text-sm text-steel">
                    No account yet?
                    <a href="{{ route('register') }}" class="font-semibold text-accent hover:text-accent-dark">Create one</a>
                </p>
            </form>
        </div>
    </section>
@endsection
