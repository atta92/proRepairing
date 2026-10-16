<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\BeforeAfter;
use App\Models\Category;
use App\Models\Device;
use App\Models\DeviceRepair;
use App\Models\Post;
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

        $accessories = Accessory::query()
            ->active()
            ->with('activeVariants')
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

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

        return view('home', compact('categories', 'stats', 'posts', 'accessories', 'beforeAfters'));
    }
}
