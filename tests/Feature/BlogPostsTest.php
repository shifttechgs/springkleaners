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
            'move-out-cleaning-checklist-cape-town',
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
        $totalPages = (int) ceil(count(config('blog.posts')) / \App\Http\Controllers\BlogController::PER_PAGE);

        $html = '';
        for ($page = 1; $page <= $totalPages; $page++) {
            $html .= $this->get('/blog?page='.$page)->assertStatus(200)->getContent();
        }

        foreach (self::newPostSlugs() as [$slug]) {
            $this->assertStringContainsString($slug, $html, "Blog index missing link to {$slug} across all pages");
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
            'move-out-cleaning-checklist-cape-town',
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

    public function test_old_end_of_tenancy_checklist_redirects_permanently_to_the_move_out_checklist(): void
    {
        $this->get('/blog/end-of-tenancy-cleaning-checklist-cape-town')
            ->assertStatus(301)
            ->assertRedirect('/blog/move-out-cleaning-checklist-cape-town');
    }

    public function test_move_out_checklist_uses_live_prices_and_links_to_booking_and_the_printable_checklist(): void
    {
        $eot = \App\Support\Services::find('end-of-tenancy');
        $html = $this->get('/blog/move-out-cleaning-checklist-cape-town')->assertOk()->getContent();

        $this->assertStringContainsString('R'.number_format($eot['base_price']), $html);
        $this->assertStringContainsString(e(route('booking.show', ['service' => 'end-of-tenancy'])), $html);
        $this->assertStringContainsString(route('services.show', 'end-of-tenancy').'#deposit-checklist', $html);
        $this->assertStringContainsString('No cleaning company can guarantee a deposit outcome', $html);

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $faq = collect($m[1])->map(fn ($j) => json_decode($j, true))->firstWhere('@type', 'FAQPage');
        $this->assertCount(6, $faq['mainEntity']);

        // The old URL is gone from the site's own links and the sitemap.
        $this->assertStringNotContainsString('end-of-tenancy-cleaning-checklist-cape-town"', $this->get('/blog')->getContent());
        $this->assertStringNotContainsString('/blog/end-of-tenancy-cleaning-checklist-cape-town<', $this->get('/sitemap.xml')->getContent());
    }
}
