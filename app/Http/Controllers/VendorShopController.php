<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorShopController extends Controller
{
    public function show(Request $request, Vendor $vendor): View
    {
        abort_unless($vendor->isApproved(), 404);

        $activeType = (string) $request->query('type', '');
        if ($activeType !== '' && ! array_key_exists($activeType, Accessory::TYPES)) {
            $activeType = '';
        }

        $typeCounts = $vendor->accessories()
            ->active()
            ->selectRaw('type, COUNT(*) as aggregate')
            ->groupBy('type')
            ->pluck('aggregate', 'type');

        $totalListings = (int) $typeCounts->sum();

        $products = $vendor->accessories()
            ->active()
            ->when($activeType !== '', fn ($query) => $query->where('type', $activeType))
            ->with(['activeVariants', 'vendor', 'approvedReviews'])
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('shops.show', [
            'vendor' => $vendor,
            'products' => $products,
            'activeType' => $activeType,
            'typeCounts' => $typeCounts,
            'totalListings' => $totalListings,
        ]);
    }
}
