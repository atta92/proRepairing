<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class PriceCheckerController extends Controller
{
    public function models(string $categorySlug): JsonResponse
    {
        $category = Category::query()
            ->where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $devices = $category->devices()
            ->where('is_active', true)
            ->with(['deviceRepairs' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with('repair');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($device) {
                return [
                    'm' => $device->name,
                    'u' => route('devices.show', $device->slug),
                    'r' => $device->deviceRepairs->map(fn ($sku) => [
                        'i' => $sku->id,
                        'n' => $sku->repair->name,
                        'ic' => $sku->repair->iconClass(),
                        'p' => '£' . number_format((float) $sku->price, 0),
                        't' => $sku->turnaround,
                        'w' => $sku->warranty_months,
                    ])->values(),
                ];
            })
            ->values();

        return response()->json($devices);
    }
}
