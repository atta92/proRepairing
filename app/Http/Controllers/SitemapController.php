<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Category;
use App\Models\Device;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = Cache::remember('sitemap.urls', now()->addHour(), fn (): array => $this->buildUrls());

        return response()
            ->view('sitemap.index', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @return list<array{loc: string, lastmod: ?string, changefreq: ?string, priority: ?string}>
     */
    private function buildUrls(): array
    {
        $urls = [];

        $static = [
            ['route' => 'home', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['route' => 'pages.about', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'pages.contact', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'pages.privacy', 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['route' => 'pages.terms', 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['route' => 'blog.index', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['route' => 'shop.index', 'changefreq' => 'weekly', 'priority' => '0.9'],
        ];

        foreach ($static as $entry) {
            $urls[] = [
                'loc' => route($entry['route']),
                'lastmod' => null,
                'changefreq' => $entry['changefreq'],
                'priority' => $entry['priority'],
            ];
        }

        Post::query()
            ->published()
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at'])
            ->each(function (Post $post) use (&$urls): void {
                $urls[] = [
                    'loc' => route('blog.show', $post),
                    'lastmod' => $post->updated_at?->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ];
            });

        Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['slug', 'updated_at'])
            ->each(function (Category $category) use (&$urls): void {
                $urls[] = [
                    'loc' => route('categories.show', $category->slug),
                    'lastmod' => $category->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            });

        Device::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['slug', 'updated_at'])
            ->each(function (Device $device) use (&$urls): void {
                $urls[] = [
                    'loc' => route('devices.show', $device->slug),
                    'lastmod' => $device->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

        Accessory::query()
            ->active()
            ->orderBy('name')
            ->get(['slug', 'updated_at'])
            ->each(function (Accessory $accessory) use (&$urls): void {
                $urls[] = [
                    'loc' => route('shop.show', $accessory),
                    'lastmod' => $accessory->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

        return $urls;
    }
}
