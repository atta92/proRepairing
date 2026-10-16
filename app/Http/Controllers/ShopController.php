<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
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
            ->with('activeVariants')
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
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

        $accessory->load(['activeVariants', 'approvedReviews']);

        $related = Accessory::query()
            ->active()
            ->with('activeVariants')
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->where('id', '!=', $accessory->id)
            ->where(function ($query) use ($accessory) {
                $query->where('type', $accessory->type)
                    ->orWhere('category', $accessory->category);
            })
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $variants = $accessory->activeVariants
            ->map(fn ($variant) => $variant->toPickerArray())
            ->values();

        $reviews = $accessory->approvedReviews;
        $averageRating = $accessory->averageRating();
        $reviewsCount = $reviews->count();
        $ratingBreakdown = collect(range(5, 1))->mapWithKeys(
            fn (int $stars) => [$stars => $reviews->where('rating', $stars)->count()]
        );

        return view('shop.show', [
            'product' => $accessory,
            'related' => $related,
            'variants' => $variants,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'reviewsCount' => $reviewsCount,
            'ratingBreakdown' => $ratingBreakdown,
        ]);
    }

    public function storeReview(Request $request, Accessory $accessory): RedirectResponse
    {
        abort_unless($accessory->is_active, 404);

        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        $email = $validated['author_email']
            ?? $request->user()?->email
            ?? $request->user()?->customer?->email;

        $email = filled($email) ? strtolower(trim((string) $email)) : null;
        $isVerified = ProductReview::emailHasPurchasedProduct($email, $accessory->id);

        ProductReview::query()->create([
            'accessory_id' => $accessory->id,
            'author_name' => $validated['author_name'],
            'author_email' => $email,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
            'image_path' => $imagePath,
            'is_approved' => false,
            'is_verified' => $isVerified,
        ]);

        $message = $isVerified
            ? 'Thanks for your review. It is marked as a verified purchase and will appear once approved.'
            : 'Thanks for your review. It will appear once approved.';

        return redirect()
            ->route('shop.show', $accessory)
            ->withFragment('reviews')
            ->with('success', $message);
    }
}
