<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\ProductReview;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $catalog = $this->buildCatalog($request);

        if ($request->wantsJson() || $request->ajax() || $request->boolean('ajax')) {
            return response()->json([
                'seller' => $catalog['activeSellerSlug'],
                'selectedTypes' => $catalog['selectedTypes'],
                'selectedCategories' => $catalog['selectedCategories'],
                'total' => $catalog['products']->total(),
                'title' => $catalog['title'],
                'types' => $this->optionsForJson($catalog['types']),
                'categories' => $this->optionsForJson($catalog['categories']),
                'html' => view('shop._results', [
                    'products' => $catalog['products'],
                ])->render(),
            ]);
        }

        return view('shop.index', [
            'products' => $catalog['products'],
            'types' => $catalog['types'],
            'categories' => $catalog['categories'],
            'selectedTypes' => $catalog['selectedTypes'],
            'selectedCategories' => $catalog['selectedCategories'],
            'shopVendors' => $catalog['shopVendors'],
            'selectedVendor' => $catalog['selectedVendor'],
            'activeSellerSlug' => $catalog['activeSellerSlug'],
            'sellerAll' => $catalog['sellerAll'],
            'platformSellerSlug' => $catalog['platformSellerSlug'],
            'title' => $catalog['title'],
        ]);
    }

    /**
     * @return array{
     *     shopVendors: Collection<int, Vendor>,
     *     selectedVendor: ?Vendor,
     *     sellerAll: bool,
     *     activeSellerSlug: string,
     *     platformSellerSlug: ?string,
     *     selectedTypes: list<string>,
     *     selectedCategories: list<string>,
     *     types: Collection<string, string>,
     *     categories: Collection<string, string>,
     *     products: \Illuminate\Contracts\Pagination\LengthAwarePaginator,
     *     title: string
     * }
     */
    private function buildCatalog(Request $request): array
    {
        $sellerSlug = $request->string('seller')->toString();
        $selectedTypes = $this->selectedSlugs($request, 'type', Accessory::TYPES);
        $selectedCategories = $this->selectedSlugs($request, 'category', Accessory::CATEGORIES);

        $shopVendors = Vendor::query()
            ->activeOnStorefront()
            ->whereHas('accessories', fn ($query) => $query->active())
            ->orderedForStorefront()
            ->get();

        $platformVendor = $shopVendors->firstWhere('is_platform', true);
        $platformSellerSlug = $platformVendor?->slug;
        $defaultSellerSlug = $platformSellerSlug ?? $shopVendors->first()?->slug;

        $selectedVendor = null;
        $sellerAll = $sellerSlug === 'all';

        if (! $sellerAll) {
            if ($sellerSlug === '') {
                $selectedVendor = $platformVendor ?? $shopVendors->first();
            } else {
                $selectedVendor = $shopVendors->firstWhere('slug', $sellerSlug);
            }

            if (! $selectedVendor && $sellerSlug !== '') {
                $selectedVendor = $platformVendor ?? $shopVendors->first();
            }
        }

        $activeSellerSlug = $sellerAll
            ? 'all'
            : ($selectedVendor?->slug ?? (string) $defaultSellerSlug);

        $products = Accessory::query()
            ->active()
            ->with(['activeVariants', 'vendor'])
            ->withCount('approvedReviews')
            ->withAvg('approvedReviews', 'rating')
            ->when(
                $selectedVendor,
                fn ($query) => $query->forVendor($selectedVendor)
            )
            ->when(
                $selectedTypes !== [],
                fn ($query) => $query->whereIn('type', $selectedTypes)
            )
            ->when(
                $selectedCategories !== [],
                fn ($query) => $query->whereIn('category', $selectedCategories)
            )
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString();

        $filterBase = Accessory::query()
            ->active()
            ->when($selectedVendor, fn ($query) => $query->forVendor($selectedVendor));

        $types = (clone $filterBase)
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->filter(fn ($slug) => array_key_exists($slug, Accessory::TYPES))
            ->mapWithKeys(fn ($slug) => [$slug => Accessory::TYPES[$slug]]);

        $categories = (clone $filterBase)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter(fn ($slug) => filled($slug) && array_key_exists($slug, Accessory::CATEGORIES))
            ->mapWithKeys(fn ($slug) => [$slug => Accessory::CATEGORIES[$slug]]);

        $title = $sellerAll
            ? 'All sellers'
            : ($selectedVendor?->is_platform
                ? 'Our own shop'
                : ($selectedVendor?->name ?? 'Shop'));

        return [
            'shopVendors' => $shopVendors,
            'selectedVendor' => $selectedVendor,
            'sellerAll' => $sellerAll,
            'activeSellerSlug' => $activeSellerSlug,
            'platformSellerSlug' => $platformSellerSlug,
            'selectedTypes' => $selectedTypes,
            'selectedCategories' => $selectedCategories,
            'types' => $types,
            'categories' => $categories,
            'products' => $products,
            'title' => $title,
        ];
    }

    /**
     * @param  array<string, string>  $allowed
     * @return list<string>
     */
    private function selectedSlugs(Request $request, string $key, array $allowed): array
    {
        $raw = $request->input($key, []);

        if (is_string($raw)) {
            $raw = $raw === ''
                ? []
                : (preg_split('/\s*,\s*/', $raw) ?: []);
        }

        if (! is_array($raw)) {
            $raw = [];
        }

        return collect($raw)
            ->map(fn ($value) => trim((string) $value))
            ->filter(fn (string $value) => $value !== '' && array_key_exists($value, $allowed))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  Collection<string, string>  $options
     * @return list<array{slug: string, label: string}>
     */
    private function optionsForJson(Collection $options): array
    {
        return $options
            ->map(fn (string $label, string $slug) => ['slug' => $slug, 'label' => $label])
            ->values()
            ->all();
    }

    public function show(Accessory $accessory): View
    {
        abort_unless($accessory->is_active, 404);

        $accessory->load(['activeVariants', 'approvedReviews', 'vendor']);

        $related = Accessory::query()
            ->active()
            ->with(['activeVariants', 'vendor'])
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
