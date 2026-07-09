<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<?php $sitewideLastmod = '2026-07-09'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ $sitewideLastmod }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/book') }}</loc>
        <lastmod>{{ $sitewideLastmod }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
        <lastmod>{{ $sitewideLastmod }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/privacy-policy') }}</loc>
        <lastmod>{{ $sitewideLastmod }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.2</priority>
    </url>
    <url>
        <loc>{{ url('/terms-of-service') }}</loc>
        <lastmod>{{ $sitewideLastmod }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.2</priority>
    </url>
    <url>
        <loc>{{ url('/blog') }}</loc>
        <lastmod>{{ $sitewideLastmod }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/faq') }}</loc>
        <lastmod>{{ $sitewideLastmod }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach ($posts as $post)
    <url>
        <loc>{{ url('/blog/'.$post['slug']) }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($post['date'])->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach ($services as $service)
    <url>
        <loc>{{ url('/services/'.$service->slug) }}</loc>
        <lastmod>{{ $service->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
    @foreach (config('locations') as $areaSlug => $area)
    <url>
        <loc>{{ url('/areas/'.$areaSlug) }}</loc>
        <lastmod>{{ $area['updated_at'] ?? $sitewideLastmod }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
