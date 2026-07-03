<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = collect(config('blog.posts'))
            ->sortByDesc('date')
            ->values();

        $categories = $posts->pluck('category')->unique()->values();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    public function show(string $slug): View
    {
        $posts = collect(config('blog.posts'))->sortByDesc('date')->values();

        $post = $posts->firstWhere('slug', $slug);

        abort_if(! $post, 404);

        $related = $posts
            ->where('slug', '!=', $slug)
            ->filter(fn ($p) => $p['category'] === $post['category'])
            ->take(3);

        if ($related->count() < 3) {
            $related = $related->merge(
                $posts->where('slug', '!=', $slug)
                    ->filter(fn ($p) => ! $related->contains('slug', $p['slug']))
                    ->take(3 - $related->count())
            );
        }

        return view('blog.show', [
            'post' => $post,
            'related' => $related->values(),
        ]);
    }
}
