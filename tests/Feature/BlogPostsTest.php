<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BlogPostsTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{0: string}> */
    public static function newPostSlugs(): array
    {
        return collect([
            'end-of-tenancy-cleaning-cost-cape-town',
            'office-cleaning-cost-cape-town',
            'post-construction-cleaning-checklist',
            'deep-cleaning-vs-regular-cleaning',
            'who-pays-for-end-of-lease-cleaning-south-africa',
            'office-cleanliness-productivity',
            'spring-cleaning-cape-town-guide',
            'meet-the-team-behind-springkleaners',
            'why-we-publish-our-prices',
            'airbnb-short-let-cleaning-cape-town-hosts',
        ])->mapWithKeys(fn ($slug) => [$slug => [$slug]])->all();
    }

    #[DataProvider('newPostSlugs')]
    public function test_new_blog_post_renders_with_valid_blog_posting_and_breadcrumb_schema(string $slug): void
    {
        $html = $this->get("/blog/{$slug}")->assertStatus(200)->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);
        $blocks = array_map(fn ($json) => json_decode($json, true), $matches[1]);

        $blogPosting = collect($blocks)->firstWhere('@type', 'BlogPosting');
        $breadcrumb = collect($blocks)->firstWhere('@type', 'BreadcrumbList');

        $this->assertNotNull($blogPosting, "BlogPosting JSON-LD missing or invalid for {$slug}");
        $this->assertNotNull($breadcrumb, "BreadcrumbList JSON-LD missing or invalid for {$slug}");
    }

    public function test_blog_index_lists_all_new_posts(): void
    {
        $html = $this->get('/blog')->assertStatus(200)->getContent();

        foreach (self::newPostSlugs() as [$slug]) {
            $this->assertStringContainsString($slug, $html, "Blog index missing link to {$slug}");
        }
    }

    public function test_posts_with_faq_sections_emit_valid_faq_schema(): void
    {
        $slugsWithFaqs = [
            'end-of-tenancy-cleaning-cost-cape-town',
            'office-cleaning-cost-cape-town',
            'post-construction-cleaning-checklist',
            'deep-cleaning-vs-regular-cleaning',
            'who-pays-for-end-of-lease-cleaning-south-africa',
            'spring-cleaning-cape-town-guide',
            'airbnb-short-let-cleaning-cape-town-hosts',
        ];

        foreach ($slugsWithFaqs as $slug) {
            $html = $this->get("/blog/{$slug}")->assertStatus(200)->getContent();

            preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);
            $blocks = array_map(fn ($json) => json_decode($json, true), $matches[1]);
            $faq = collect($blocks)->firstWhere('@type', 'FAQPage');

            $this->assertNotNull($faq, "FAQPage JSON-LD missing or invalid for {$slug}");
            $this->assertNotEmpty($faq['mainEntity'], "FAQPage has no questions for {$slug}");
        }
    }
}
