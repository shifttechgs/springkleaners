<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitewideQaTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{0: string}> */
    public static function allPublicUrls(): array
    {
        $urls = [
            '/', '/about', '/faq', '/book', '/blog',
        ];

        foreach (Service::orderBy('sort_order')->pluck('slug') as $slug) {
            $urls[] = "/services/{$slug}";
        }

        foreach (array_keys(config('locations')) as $slug) {
            $urls[] = "/areas/{$slug}";
        }

        foreach (collect(config('blog.posts'))->pluck('slug') as $slug) {
            $urls[] = "/blog/{$slug}";
        }

        return collect($urls)->mapWithKeys(fn ($url) => [$url => [$url]])->all();
    }

    public function test_every_public_page_has_self_referencing_canonical_unique_title_and_meta_description(): void
    {
        $seenTitles = [];

        foreach (self::allPublicUrls() as [$url]) {
            $html = $this->get($url)->assertStatus(200)->getContent();

            preg_match('#<link rel="canonical" href="([^"]+)">#', $html, $canonicalMatch);
            $this->assertNotEmpty($canonicalMatch, "Missing canonical tag on {$url}");
            $this->assertStringEndsWith($url === '/' ? url('/') : url($url), rtrim($canonicalMatch[1], '/'), "Canonical on {$url} doesn't self-reference");

            preg_match('#<title>(.*?)</title>#s', $html, $titleMatch);
            $this->assertNotEmpty($titleMatch, "Missing title tag on {$url}");
            $title = trim($titleMatch[1]);
            $this->assertNotEmpty($title, "Empty title tag on {$url}");

            $firstSeenOn = $seenTitles[$title] ?? '';
            $this->assertArrayNotHasKey($title, $seenTitles, "Duplicate <title> \"{$title}\" — first seen on {$firstSeenOn}, again on {$url}");
            $seenTitles[$title] = $url;

            preg_match('#<meta name="description" content="([^"]*)"#', $html, $descMatch);
            $this->assertNotEmpty($descMatch, "Missing meta description on {$url}");
            $this->assertNotEmpty(trim(html_entity_decode($descMatch[1])), "Empty meta description on {$url}");

            preg_match('#<meta name="robots" content="([^"]*)"#', $html, $robotsMatch);
            $this->assertNotEmpty($robotsMatch, "Missing meta robots on {$url}");
            $this->assertStringContainsString('index', $robotsMatch[1], "{$url} is unexpectedly noindex");
        }
    }

    public function test_every_public_page_has_descriptive_alt_text_on_every_image(): void
    {
        foreach (self::allPublicUrls() as [$url]) {
            $html = $this->get($url)->assertStatus(200)->getContent();

            preg_match_all('#<img\s[^>]*>#i', $html, $imgTags);

            foreach ($imgTags[0] as $tag) {
                $hasAlt = preg_match('#alt="([^"]*)"#i', $tag, $altMatch);
                $this->assertSame(1, $hasAlt, "Image without alt attribute on {$url}: {$tag}");
                $this->assertNotEmpty(trim(html_entity_decode($altMatch[1])), "Image with empty alt text on {$url}: {$tag}");
            }
        }
    }

    public function test_every_public_page_emits_only_well_formed_json_ld(): void
    {
        foreach (self::allPublicUrls() as [$url]) {
            $html = $this->get($url)->assertStatus(200)->getContent();

            preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

            $this->assertNotEmpty($matches[1], "No JSON-LD blocks found on {$url}");

            foreach ($matches[1] as $i => $json) {
                $decoded = json_decode($json, true);
                $this->assertNotNull($decoded, "Malformed JSON-LD block #{$i} on {$url}: ".json_last_error_msg());
                $this->assertArrayHasKey('@type', $decoded, "JSON-LD block #{$i} on {$url} missing @type");
                $this->assertArrayHasKey('@context', $decoded, "JSON-LD block #{$i} on {$url} missing @context");
            }
        }
    }

    public function test_sitemap_includes_every_public_url_with_lastmod_and_priority(): void
    {
        $xml = $this->get('/sitemap.xml')->assertStatus(200)->getContent();

        preg_match_all('#<loc>(.*?)</loc>#', $xml, $locs);
        preg_match_all('#<lastmod>(.*?)</lastmod>#', $xml, $lastmods);
        preg_match_all('#<priority>(.*?)</priority>#', $xml, $priorities);

        $this->assertSame(count($locs[1]), count($lastmods[1]), 'Every sitemap URL should have a <lastmod>');
        $this->assertSame(count($locs[1]), count($priorities[1]), 'Every sitemap URL should have a <priority>');

        $sitemapPaths = collect($locs[1])->map(fn ($loc) => '/'.ltrim(parse_url($loc, PHP_URL_PATH), '/'))->map(fn ($p) => rtrim($p, '/') ?: '/');

        foreach (self::allPublicUrls() as [$url]) {
            $this->assertTrue(
                $sitemapPaths->contains(rtrim($url, '/') ?: '/'),
                "Sitemap is missing {$url}"
            );
        }

        // Every URL type is represented, not just a handful.
        $this->assertGreaterThanOrEqual(14, $sitemapPaths->filter(fn ($p) => str_starts_with($p, '/services/'))->count());
        $this->assertGreaterThanOrEqual(19, $sitemapPaths->filter(fn ($p) => str_starts_with($p, '/areas/'))->count());
        $this->assertGreaterThanOrEqual(16, $sitemapPaths->filter(fn ($p) => str_starts_with($p, '/blog/'))->count());
    }
}
