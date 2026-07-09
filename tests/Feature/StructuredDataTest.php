<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StructuredDataTest extends TestCase
{
    use RefreshDatabase;

    private function ldJsonBlocks(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        return array_map(fn ($json) => json_decode($json, true), $matches[1]);
    }

    public function test_faq_hub_renders_all_clusters_with_valid_schema(): void
    {
        $html = $this->get('/faq')->assertStatus(200)->getContent();

        $blocks = $this->ldJsonBlocks($html);
        $faq = collect($blocks)->firstWhere('@type', 'FAQPage');
        $breadcrumb = collect($blocks)->firstWhere('@type', 'BreadcrumbList');

        $this->assertNotNull($faq, 'FAQPage JSON-LD missing or invalid JSON');
        $this->assertSame(50, count($faq['mainEntity']), 'FAQ hub should carry the full 50-question set from the audit report');
        $this->assertNotNull($breadcrumb);

        foreach (['pricing', 'trust', 'services', 'areas', 'comparison'] as $cluster) {
            $this->assertStringContainsString("tab = '{$cluster}'", $html, "Missing tab trigger: {$cluster}");
        }

        // Per-service FAQs link back to their own service pages.
        $this->assertStringContainsString(route('services.show', 'deep-cleaning'), $html);
        $this->assertStringContainsString(route('services.show', 'carpet-cleaning'), $html);
        $this->assertStringContainsString('End-of-Tenancy Cleaning', $html);
        $this->assertStringContainsString('Office &amp; Commercial Cleaning', $html);
    }

    public function test_service_and_area_pages_link_to_the_faq_hub(): void
    {
        $servicePage = $this->get('/services/deep-cleaning')->assertStatus(200)->getContent();
        $this->assertStringContainsString(route('faq').'#services', $servicePage);

        $areaPage = $this->get('/areas/milnerton')->assertStatus(200)->getContent();
        $this->assertStringContainsString(route('faq').'#areas', $areaPage);
    }

    public function test_before_after_gallery_is_hidden_when_no_photos_are_configured(): void
    {
        $homepage = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('Before &amp; After', $homepage);

        $servicePage = $this->get('/services/deep-cleaning')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('Before &amp; After', $servicePage);
    }

    public function test_before_after_gallery_renders_and_filters_by_service_once_configured(): void
    {
        config(['before_after' => [
            ['before' => '/images/before.jpg', 'after' => '/images/after.jpg', 'caption' => 'General reset', 'services' => []],
            ['before' => '/images/dc-before.jpg', 'after' => '/images/dc-after.jpg', 'caption' => 'Deep Clean kitchen', 'services' => ['deep-cleaning']],
        ]]);

        $homepage = $this->get('/')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Before &amp; After', $homepage);
        $this->assertStringContainsString('General reset', $homepage);
        $this->assertStringNotContainsString('Deep Clean kitchen', $homepage);

        $deepCleanPage = $this->get('/services/deep-cleaning')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Deep Clean kitchen', $deepCleanPage);
        $this->assertStringNotContainsString('General reset', $deepCleanPage);

        $officePage = $this->get('/services/office-commercial-cleaning')->assertStatus(200)->getContent();
        $this->assertStringNotContainsString('Deep Clean kitchen', $officePage);
        $this->assertStringNotContainsString('General reset', $officePage);
    }

    public function test_homepage_emits_valid_organization_and_website_schema(): void
    {
        $blocks = $this->ldJsonBlocks($this->get('/')->assertStatus(200)->getContent());

        $organization = collect($blocks)->firstWhere('@type', 'Organization');
        $website = collect($blocks)->firstWhere('@type', 'WebSite');

        $this->assertNotNull($organization, 'Organization JSON-LD missing or invalid');
        $this->assertSame('SpringKleaners', $organization['name']);
        $this->assertSame('Spring Kleaners', $organization['alternateName']);

        $this->assertNotNull($website, 'WebSite JSON-LD missing or invalid');
        $this->assertSame('business', parse_url($website['publisher']['@id'], PHP_URL_FRAGMENT));
    }

    public function test_layout_emits_enriched_local_business_schema_on_every_page(): void
    {
        $blocks = $this->ldJsonBlocks($this->get('/')->assertStatus(200)->getContent());
        $business = collect($blocks)->firstWhere('@type', 'HousekeepingService');

        $this->assertNotNull($business);
        $this->assertSame('business', parse_url($business['@id'], PHP_URL_FRAGMENT));
        $this->assertSame('7441', $business['address']['postalCode']);
        $this->assertSame('SpringKleaners', $business['name']);
        $this->assertSame('-33.8038708', $business['geo']['latitude']);
        $this->assertContains('https://share.google/fP58DLAFT2V3FyTUK', $business['sameAs']);
        $this->assertSame('Spring Kleaners', $business['alternateName']);
    }

    public function test_service_page_emits_valid_service_faq_and_breadcrumb_schema(): void
    {
        $blocks = $this->ldJsonBlocks($this->get('/services/deep-cleaning')->assertStatus(200)->getContent());

        $service = collect($blocks)->firstWhere('@type', 'Service');
        $faq = collect($blocks)->firstWhere('@type', 'FAQPage');
        $breadcrumb = collect($blocks)->firstWhere('@type', 'BreadcrumbList');

        $this->assertNotNull($service, 'Service JSON-LD missing or invalid JSON');
        $this->assertSame('business', parse_url($service['provider']['@id'], PHP_URL_FRAGMENT));
        $this->assertEquals(1200, $service['offers']['price']);

        $this->assertNotNull($faq);
        $this->assertNotEmpty($faq['mainEntity']);

        $this->assertNotNull($breadcrumb);
    }

    public function test_area_page_emits_valid_service_and_breadcrumb_schema(): void
    {
        $html = $this->get('/areas/milnerton')->assertStatus(200)->getContent();
        $blocks = $this->ldJsonBlocks($html);

        $service = collect($blocks)->firstWhere('@type', 'Service');
        $breadcrumb = collect($blocks)->firstWhere('@type', 'BreadcrumbList');

        $this->assertNotNull($service, 'Service JSON-LD missing or invalid JSON');
        $this->assertSame('business', parse_url($service['provider']['@id'], PHP_URL_FRAGMENT));
        $this->assertSame('Milnerton, Cape Town', $service['areaServed']['name']);

        $this->assertNotNull($breadcrumb);

        // Regression: this page's services grid loops all 19 services, including
        // custom-quote ones (base_price=0, unit_label=null) — must not render "From R0 /".
        $this->assertStringNotContainsString('From R0', $html);
    }

    /** @return array<string, array{0: string}> */
    public static function newAreaPages(): array
    {
        return collect([
            'sea-point', 'green-point', 'west-beach', 'monte-vista', 'edgemead',
            'bothasig', 'montague-gardens', 'paarden-eiland', 'richwood',
            'burgundy-estate', 'flamingo-vlei',
        ])->mapWithKeys(fn ($slug) => [$slug => [$slug]])->all();
    }

    #[DataProvider('newAreaPages')]
    public function test_new_area_page_renders_with_valid_schema_and_no_broken_pricing(string $slug): void
    {
        $html = $this->get("/areas/{$slug}")->assertStatus(200)->getContent();

        $blocks = $this->ldJsonBlocks($html);
        $service = collect($blocks)->firstWhere('@type', 'Service');
        $breadcrumb = collect($blocks)->firstWhere('@type', 'BreadcrumbList');

        $this->assertNotNull($service, "Service JSON-LD missing or invalid JSON for {$slug}");
        $this->assertSame('business', parse_url($service['provider']['@id'], PHP_URL_FRAGMENT));
        $this->assertNotNull($breadcrumb);

        // Custom-quote services (base_price=0, unit_label=null) must render "Custom quote",
        // never a broken "From R0 /" price line, in this page's services grid.
        $this->assertStringNotContainsString('From R0', $html);
        $this->assertStringContainsString('Custom quote', $html);
    }

    public function test_office_commercial_page_renders_quote_cta_not_booking_wizard(): void
    {
        $response = $this->get('/services/office-commercial-cleaning')->assertStatus(200);
        $html = $response->getContent();

        $blocks = $this->ldJsonBlocks($html);
        $service = collect($blocks)->firstWhere('@type', 'Service');
        $faq = collect($blocks)->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($service, 'Service JSON-LD missing or invalid JSON');
        $this->assertEquals(850, $service['offers']['price']);
        $this->assertNotNull($faq);
        $this->assertNotEmpty($faq['mainEntity']);

        $this->assertStringContainsString('Request a Quote', $html);
        $this->assertStringNotContainsString('Book This Service', $html);
    }

    public function test_office_commercial_cleaning_is_excluded_from_the_booking_wizard(): void
    {
        $html = $this->get('/book')->assertStatus(200)->getContent();

        preg_match("#services:\s*JSON\.parse\('(.*?)'\)#s", $html, $matches);
        $this->assertNotEmpty($matches, 'Could not find the Alpine services data blob on /book');

        $this->assertStringNotContainsString('office-commercial-cleaning', $matches[1]);
        $this->assertStringContainsString('deep-cleaning', $matches[1]);
    }

    /** @return array<string, array{0: string, 1: int}> */
    public static function wizardModeServices(): array
    {
        return [
            'recurring-house-cleaning' => ['recurring-house-cleaning', 750],
            'airbnb-turnover-cleaning' => ['airbnb-turnover-cleaning', 900],
            'move-in-cleaning' => ['move-in-cleaning', 1200],
        ];
    }

    #[DataProvider('wizardModeServices')]
    public function test_wizard_mode_service_page_uses_the_booking_wizard(string $slug, int $expectedPrice): void
    {
        $html = $this->get("/services/{$slug}")->assertStatus(200)->getContent();

        $blocks = $this->ldJsonBlocks($html);
        $service = collect($blocks)->firstWhere('@type', 'Service');
        $faq = collect($blocks)->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($service, "Service JSON-LD missing or invalid JSON for {$slug}");
        $this->assertEquals($expectedPrice, $service['offers']['price']);
        $this->assertNotNull($faq);
        $this->assertNotEmpty($faq['mainEntity']);

        $this->assertStringContainsString('Book This Service', $html);
        $this->assertStringNotContainsString('Request a Quote', $html);
    }

    #[DataProvider('wizardModeServices')]
    public function test_wizard_mode_service_is_included_in_the_booking_wizard(string $slug): void
    {
        $html = $this->get('/book')->assertStatus(200)->getContent();

        preg_match("#services:\s*JSON\.parse\('(.*?)'\)#s", $html, $matches);
        $this->assertNotEmpty($matches, 'Could not find the Alpine services data blob on /book');

        $this->assertStringContainsString($slug, $matches[1]);
    }

    /** @return array<string, array{0: string, 1: int}> */
    public static function quoteModeServices(): array
    {
        return [
            'carpet-cleaning' => ['carpet-cleaning', 950],
            'upholstery-cleaning' => ['upholstery-cleaning', 800],
            'window-cleaning' => ['window-cleaning', 1400],
        ];
    }

    #[DataProvider('quoteModeServices')]
    public function test_quote_mode_service_page_renders_quote_cta_not_booking_wizard(string $slug, int $expectedPrice): void
    {
        $html = $this->get("/services/{$slug}")->assertStatus(200)->getContent();

        $blocks = $this->ldJsonBlocks($html);
        $service = collect($blocks)->firstWhere('@type', 'Service');
        $faq = collect($blocks)->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($service, "Service JSON-LD missing or invalid JSON for {$slug}");
        $this->assertEquals($expectedPrice, $service['offers']['price']);
        $this->assertNotNull($faq);
        $this->assertNotEmpty($faq['mainEntity']);

        $this->assertStringContainsString('Request a Quote', $html);
        $this->assertStringNotContainsString('Book This Service', $html);
    }

    #[DataProvider('quoteModeServices')]
    public function test_quote_mode_service_is_excluded_from_the_booking_wizard(string $slug): void
    {
        $html = $this->get('/book')->assertStatus(200)->getContent();

        preg_match("#services:\s*JSON\.parse\('(.*?)'\)#s", $html, $matches);
        $this->assertNotEmpty($matches, 'Could not find the Alpine services data blob on /book');

        $this->assertStringNotContainsString($slug, $matches[1]);
    }

    /** @return array<string, array{0: string}> */
    public static function customQuoteServices(): array
    {
        return collect([
            'spring-cleaning', 'oven-deep-cleaning', 'fridge-appliance-cleaning',
            'mattress-cleaning', 'blind-curtain-cleaning', 'retail-shop-cleaning',
            'medical-clinic-cleaning', 'restaurant-kitchen-cleaning',
            'school-educational-cleaning', 'pressure-washing',
        ])->mapWithKeys(fn ($slug) => [$slug => [$slug]])->all();
    }

    #[DataProvider('customQuoteServices')]
    public function test_custom_quote_service_page_has_no_fixed_price_and_no_wizard_cta(string $slug): void
    {
        $html = $this->get("/services/{$slug}")->assertStatus(200)->getContent();

        $blocks = $this->ldJsonBlocks($html);
        $service = collect($blocks)->firstWhere('@type', 'Service');
        $faq = collect($blocks)->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($service, "Service JSON-LD missing or invalid JSON for {$slug}");
        $this->assertArrayNotHasKey('offers', $service, "Custom-quote service {$slug} should not claim a fixed Offer price");
        $this->assertNotNull($faq);
        $this->assertNotEmpty($faq['mainEntity']);

        $this->assertStringContainsString('Get a Custom Quote', $html);
        $this->assertStringNotContainsString('Book This Service', $html);
    }

    #[DataProvider('customQuoteServices')]
    public function test_custom_quote_service_is_excluded_from_the_booking_wizard(string $slug): void
    {
        $html = $this->get('/book')->assertStatus(200)->getContent();

        preg_match("#services:\s*JSON\.parse\('(.*?)'\)#s", $html, $matches);
        $this->assertNotEmpty($matches, 'Could not find the Alpine services data blob on /book');

        $this->assertStringNotContainsString($slug, $matches[1]);
    }
}
