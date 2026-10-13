<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->published()
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->is_published && $post->published_at && $post->published_at->lte(now()),
            404
        );

        $related = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
