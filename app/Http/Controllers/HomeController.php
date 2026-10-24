<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\BeforeAfter;
use App\Models\Category;
use App\Models\Device;
use App\Models\DeviceRepair;
use App\Models\Post;
use App\Models\Vendor;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['devices' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $shopVendors = Vendor::query()
            ->activeOnStorefront()
            ->whereHas('accessories', fn ($query) => $query->active())
            ->orderedForStorefront()
            ->get();

        $shopVendorProducts = $shopVendors
            ->mapWithKeys(function (Vendor $vendor): array {
                $products = Accessory::query()
                    ->active()
                    ->forVendor($vendor->id)
                    ->with(['activeVariants', 'vendor'])
                    ->withCount('approvedReviews')
                    ->withAvg('approvedReviews', 'rating')
                    ->orderByDesc('is_featured')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->limit(4)
                    ->get();

                return [$vendor->id => $products];
            })
            ->filter(fn (Collection $products) => $products->isNotEmpty());

        // Drop vendors that somehow have no products after filtering.
        $shopVendors = $shopVendors->filter(
            fn (Vendor $vendor) => $shopVendorProducts->has($vendor->id)
        )->values();

        $defaultShopVendorId = $shopVendors->firstWhere('is_platform', true)?->id
            ?? $shopVendors->first()?->id;

        $posts = Post::query()
            ->published()
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        $beforeAfters = BeforeAfter::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $stats = [
            'devices' => Device::query()->where('is_active', true)->count(),
            'categories' => $categories->count(),
            'repairs' => DeviceRepair::query()->where('is_active', true)->count(),
        ];

        return view('home', compact(
            'categories',
            'stats',
            'posts',
            'beforeAfters',
            'shopVendors',
            'shopVendorProducts',
            'defaultShopVendorId',
        ));
    }
}
