<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $devices = $category->devices()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->withCount(['deviceRepairs' => fn ($q) => $q->where('is_active', true)])
            ->paginate(8)
            ->withQueryString();

        return view('categories.show', compact('category', 'devices'));
    }
}
