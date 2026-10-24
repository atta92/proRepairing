@extends('layouts.app')

@section('title', 'Seller application declined - ' . $siteSettings->displayName())

@section('content')
    <section class="bg-snow py-16 sm:py-24">
        <div class="site-container max-w-xl text-center">
            <h1 class="font-display text-3xl font-bold text-ink">Application declined</h1>
            <p class="mt-4 text-steel">
                Your seller application for <span class="font-semibold text-ink">{{ $vendor->name }}</span> was not approved.
                Contact us if you think this is a mistake.
            </p>
            <div class="mt-8">
                <a href="{{ route('pages.contact') }}" class="btn-primary">Contact us</a>
            </div>
        </div>
    </section>
@endsection
