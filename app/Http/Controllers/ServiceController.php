<?php

namespace App\Http\Controllers;

use App\Support\Services;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function show(string $slug): View
    {
        $service = Services::find($slug);
        $content = config('service_pages.'.$slug);

        abort_if(! $service || ! $content, 404);

        $allServices = Services::list();

        $posts = collect(config('blog.posts'))
            ->filter(fn ($post) => in_array($post['category'], $content['blog_categories'], true))
            ->sortByDesc('date')
            ->take(3)
            ->values();

        // Inclusions are maintained in the DB (Admin > Services > Edit) so the
        // public page and the quote/invoice documents stay in step. The config
        // list is the seed for that column and the fallback if it is cleared.
        $inclusions = filled($service['inclusions'] ?? []) ? $service['inclusions'] : ($content['included'] ?? []);

        return view('services.show', [
            'slug' => $slug,
            'service' => $service,
            'content' => $content,
            'inclusions' => $inclusions,
            'allServices' => $allServices,
            'posts' => $posts,
        ]);
    }
}
