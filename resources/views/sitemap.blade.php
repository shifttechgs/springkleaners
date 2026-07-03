<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/book') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/get-my-quote') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ url('/blog') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach ($posts as $post)
    <url>
        <loc>{{ url('/blog/'.$post['slug']) }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($post['date'])->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach (\App\Support\Services::slugs() as $serviceSlug)
    <url>
        <loc>{{ url('/services/'.$serviceSlug) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
    @foreach (array_keys(config('locations')) as $areaSlug)
    <url>
        <loc>{{ url('/areas/'.$areaSlug) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
