<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessoryController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();

        $accessories = Accessory::query()
            ->active()
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->when(
                $category !== '' && array_key_exists($category, Accessory::CATEGORIES),
                fn ($query) => $query->where('category', $category)
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Accessory::CATEGORIES;

        return view('accessories.index', compact('accessories', 'categories', 'category'));
    }

    public function show(Accessory $accessory): View
    {
        abort_unless($accessory->is_active, 404);

        $related = Accessory::query()
            ->active()
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('id', '!=', $accessory->id)
            ->where('category', $accessory->category)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        if ($related->count() < 4) {
            $related = $related->merge(
                Accessory::query()
                    ->active()
                    ->withCount('approvedReviews')
                    ->withAvg('approvedReviews', 'rating')
                    ->where('id', '!=', $accessory->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderBy('sort_order')
                    ->limit(4 - $related->count())
                    ->get()
            );
        }

        return view('accessories.show', compact('accessory', 'related'));
    }
}
