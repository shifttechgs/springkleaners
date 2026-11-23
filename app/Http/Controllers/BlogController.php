<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * How-To Guides: every guide on one page, filtered by the reader's situation (config blog.topics).
     * ?topic= is applied here so filtering works without JavaScript; the page then filters instantly in the browser.
     */
    public function index(Request $request): View
    {
        $posts = collect(config('blog.posts'))
            ->sortByDesc('date')
            ->values();

        $topics = collect(config('blog.topics'))
            ->map(fn (string $label, string $key) => [
                'key' => $key,
                'label' => $label,
                'count' => $posts->filter(fn ($p) => in_array($key, $p['topics'] ?? [], true))->count(),
            ])
            ->filter(fn ($t) => $t['count'] > 0)
            ->values();

        $activeTopic = $request->query('topic');
        if (! $topics->contains('key', $activeTopic)) {
            $activeTopic = null;
        }

        return view('blog.index', [
            'posts' => $posts,
            'topics' => $topics,
            'activeTopic' => $activeTopic,
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
