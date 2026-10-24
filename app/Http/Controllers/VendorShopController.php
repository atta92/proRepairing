<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\View\View;

class VendorShopController extends Controller
{
    public function show(Vendor $vendor): View
    {
        abort_unless($vendor->isApproved(), 404);

        $products = $vendor->accessories()
            ->active()
            ->with(['activeVariants', 'vendor', 'approvedReviews'])
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12);

        return view('shops.show', compact('vendor', 'products'));
    }
}
