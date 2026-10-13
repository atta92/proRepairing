@extends('layouts.app')

@section('title', 'Create account — ' . $siteSettings->displayName())

@section('meta_robots', 'noindex, nofollow')

@section('content')
    <section class="site-grid-bg bg-ink py-12 text-white sm:py-14">
        <div class="site-container">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">Create account</h1>
            <p class="mt-2 text-white/70">Book repairs and shop online with a free account.</p>
        </div>
    </section>

    <section class="bg-snow py-12 sm:py-16">
        <div class="site-container max-w-md">
            <form method="POST" action="{{ route('register') }}" class="rounded-2xl border border-mist bg-white p-6 sm:p-8">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Full name</span>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>

                <label class="mt-4 block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>

                <label class="mt-4 block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Phone <span class="normal-case text-steel/60">(optional)</span></span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>

                <label class="mt-4 block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Password</span>
                    <input type="password" name="password" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>

                <label class="mt-4 block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-steel">Confirm password</span>
                    <input type="password" name="password_confirmation" required class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm">
                </label>

                <button type="submit" class="btn-primary mt-6 w-full">Create account</button>

                <p class="mt-5 text-center text-sm text-steel">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-accent hover:text-accent-dark">Sign in</a>
                </p>
            </form>
        </div>
    </section>
@endsection
