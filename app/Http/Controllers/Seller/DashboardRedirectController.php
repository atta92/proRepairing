<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        $vendor = $user?->vendor;

        if (! $vendor) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Register as a seller to access the seller centre.');
        }

        if ($vendor->isApproved()) {
            return redirect('/seller');
        }

        if ($vendor->status === 'rejected') {
            return view('seller.rejected', compact('vendor'));
        }

        if ($vendor->status === 'suspended') {
            return view('seller.suspended', compact('vendor'));
        }

        return view('seller.pending', compact('vendor'));
    }
}
