<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user()?->loadMissing('vendor');

        if ($user?->is_admin) {
            return redirect('/admin')
                ->with('error', 'Admin accounts use the admin panel, not the customer dashboard.');
        }

        if ($user?->vendor) {
            return redirect()
                ->route('seller.home')
                ->with('error', 'Seller accounts use the seller centre, not the customer dashboard.');
        }

        return $next($request);
    }
}
