<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BlogController extends Controller
{
    public const PER_PAGE = 9;

    public function index(Request $request): View
    {
        $posts = collect(config('blog.posts'))
            ->sortByDesc('date')
            ->values();

        $categories = $posts->pluck('category')->unique()->values();

        $activeCategory = $request->query('category');
        if (! $categories->contains($activeCategory)) {
            $activeCategory = null;
        }

        $filtered = $activeCategory
            ? $posts->where('category', $activeCategory)->values()
            : $posts;

        $page = LengthAwarePaginator::resolveCurrentPage();

        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, self::PER_PAGE)->values(),
            $filtered->count(),
            self::PER_PAGE,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('blog.index', [
            'posts' => $paginated,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
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
