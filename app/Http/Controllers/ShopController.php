<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Services\ShopCart;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();
        $category = $request->string('category')->toString();

        $products = Accessory::query()
            ->active()
            ->when(
                $type !== '' && array_key_exists($type, Accessory::TYPES),
                fn ($query) => $query->where('type', $type)
            )
            ->when(
                $category !== '' && array_key_exists($category, Accessory::CATEGORIES),
                fn ($query) => $query->where('category', $category)
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', [
            'products' => $products,
            'types' => Accessory::TYPES,
            'categories' => Accessory::CATEGORIES,
            'type' => $type,
            'category' => $category,
        ]);
    }

    public function show(Accessory $accessory): View
    {
        abort_unless($accessory->is_active, 404);

        $related = Accessory::query()
            ->active()
            ->where('id', '!=', $accessory->id)
            ->where(function ($query) use ($accessory) {
                $query->where('type', $accessory->type)
                    ->orWhere('category', $accessory->category);
            })
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('shop.show', [
            'product' => $accessory,
            'related' => $related,
        ]);
    }
}
