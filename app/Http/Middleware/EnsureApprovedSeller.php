<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user()?->loadMissing('vendor');
        $vendor = $user?->vendor;

        if (! $vendor) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Register as a seller to access the seller centre.');
        }

        if (! $vendor->isApproved()) {
            return redirect()->route('seller.home');
        }

        return $next($request);
    }
}
