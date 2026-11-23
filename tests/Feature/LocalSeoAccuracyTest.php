<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards the facts search snippets and AI summaries repeat about us: area titles, descriptions and FAQ
 * answers must describe the right region, no stale prices, and no claims about booking days we don't offer.
 */
class LocalSeoAccuracyTest extends TestCase
{
    use RefreshDatabase;

    private function title(string $html): string
    {
        preg_match('#<title>([^<]*)</title>#', $html, $m);

        return html_entity_decode($m[1] ?? '', ENT_QUOTES);
    }

    public function test_area_pages_have_short_accurate_titles_and_no_hardcoded_price(): void
    {
        foreach (config('locations') as $slug => $location) {
            $html = $this->get("/areas/{$slug}")->assertOk()->getContent();
            $title = $this->title($html);

            $this->assertSame("Cleaning Services in {$location['name']}, Cape Town | SpringKleaners", $title);
            $this->assertLessThanOrEqual(65, mb_strlen($title), "Title too long on /areas/{$slug}");
            $this->assertStringNotContainsString('R950', $location['meta_description'], "Stale price on /areas/{$slug}");
            $this->assertLessThanOrEqual(160, mb_strlen($location['meta_description']), "Description too long on /areas/{$slug}");
        }
    }

    public function test_atlantic_seaboard_pages_are_not_called_northern_suburbs(): void
    {
        foreach (['sea-point', 'green-point'] as $slug) {
            $html = $this->get("/areas/{$slug}")->assertOk()->getContent();

            $this->assertStringNotContainsString('Northern Suburbs', $this->title($html));
            $this->assertStringContainsString('the Atlantic Seaboard. Enter your address', $html);
        }
    }

    public function test_area_pages_link_to_their_configured_nearby_areas_and_carry_the_suburb_into_booking(): void
    {
        foreach (config('locations') as $slug => $location) {
            $html = $this->get("/areas/{$slug}")->assertOk()->getContent();

            foreach ($location['nearby'] as $nearby) {
                $this->assertArrayHasKey($nearby, config('locations'), "Unknown nearby slug {$nearby} on {$slug}");
                $this->assertStringContainsString(route('areas.show', $nearby), $html);
            }

            $bookUrl = e(route('booking.show', ['suburb' => $location['name']]));
            $this->assertMatchesRegularExpression('#href="'.preg_quote($bookUrl, '#').'" data-mobile-bar-quote#', $html);
        }
    }

    public function test_area_faq_lists_bookable_services(): void
    {
        $html = $this->get('/areas/table-view')->assertOk()->getContent();

        $this->assertStringContainsString('Which cleaning services can I book online in Table View?', $html);
        $this->assertStringContainsString('Deep Cleaning', $html);
    }

    public function test_wizard_service_pages_carry_the_service_into_booking_and_quote_pages_do_not(): void
    {
        $this->get('/services/deep-cleaning')->assertOk()
            ->assertSee('href="'.e(route('booking.show', ['service' => 'deep-cleaning'])).'" data-mobile-bar-quote', false);

        $this->get('/services/carpet-cleaning')->assertOk()
            ->assertSee('href="'.route('booking.show').'" data-mobile-bar-quote', false);
    }

    public function test_end_of_tenancy_title_does_not_compete_with_move_in_page(): void
    {
        $html = $this->get('/services/end-of-tenancy')->assertOk()->getContent();

        $this->assertStringNotContainsString('Move-In', $this->title($html));
        $this->assertStringContainsString('Move-Out', $this->title($html));

        // The service-card tagline (from the database) shouldn't claim move-in either.
        $service = \App\Models\Service::where('slug', 'end-of-tenancy')->first();
        $this->assertSame('End-of-Tenancy Cleaning', $service->name);
        $this->assertSame('Move-out cleaning to landlord and letting-agent standard.', $service->tagline);
    }

    public function test_no_page_claims_every_day_booking_while_bookings_are_weekend_only(): void
    {
        $llms = $this->get('/llms.txt')->assertOk()->getContent();
        $this->assertStringNotContainsString('every day of the week', $llms);
        $this->assertStringContainsString('online booking for Saturdays and Sundays', $llms);

        $airbnb = $this->get('/services/airbnb-turnover-cleaning')->assertOk()->getContent();
        $this->assertStringNotContainsString('any day of the week', $airbnb);
        $this->assertStringNotContainsString('every-day changeovers', $airbnb);
    }

    public function test_legacy_book_urls_redirect_permanently(): void
    {
        $this->get('/book/deep-cleaning')->assertStatus(301)
            ->assertRedirect(route('booking.show', ['service' => 'deep-cleaning']));
    }

    public function test_homepage_title_and_description_fit_search_results(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        preg_match('#name="description" content="([^"]*)"#', $html, $m);

        $this->assertStringStartsWith('SpringKleaners | Cleaning Services', $this->title($html));
        $this->assertLessThanOrEqual(65, mb_strlen($this->title($html)));
        $this->assertLessThanOrEqual(160, mb_strlen(html_entity_decode($m[1], ENT_QUOTES)));
    }

    public function test_office_copy_matches_weekend_only_scheduling(): void
    {
        foreach (['/services/office-commercial-cleaning', '/faq', '/blog/office-cleaning-cost-cape-town'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringNotContainsString('any day of the week', $html, $url);
            $this->assertStringNotContainsString('early morning, evenings', $html, $url);
        }
    }

    public function test_every_area_page_suburb_can_actually_be_booked(): void
    {
        $served = config('served_suburbs');
        $booking = $this->get('/book')->assertOk()->getContent();
        $footer = $this->get('/')->assertOk()->getContent();

        foreach (config('locations') as $slug => $location) {
            // An area page for a suburb the booking form rejects would advertise a service nobody can book.
            $this->assertContains($location['name'], $served, "{$location['name']} has a page but isn't in served_suburbs");
            // The wizard's suburb whitelist is rendered with Js::from, which encodes quotes as ".
            $this->assertStringContainsString('"'.$location['name'].'"', $booking, "{$location['name']} missing from the /book suburb list");
            $this->assertStringContainsString(route('areas.show', $slug), $footer, "Footer doesn't link /areas/{$slug}");
        }
    }

    public function test_new_northern_suburbs_are_listed_on_the_areas_hub(): void
    {
        $html = $this->get('/areas')->assertOk()->getContent();

        foreach (['Durbanville', 'Bellville', 'Parow', 'Goodwood', 'Brackenfell', 'Plattekloof'] as $name) {
            $this->assertStringContainsString($name, $html);
        }
        $this->assertStringContainsString('across '.count(config('locations')).' Cape Town suburbs', $html);
    }

    public function test_pricing_guides_open_booking_on_the_matching_service(): void
    {
        $this->get('/blog/deep-clean-cost-cape-town-pricing-guide')->assertOk()
            ->assertSee(e(route('booking.show', ['service' => 'deep-cleaning'])), false);
        $this->get('/blog/end-of-tenancy-cleaning-cost-cape-town')->assertOk()
            ->assertSee(e(route('booking.show', ['service' => 'end-of-tenancy'])), false);
    }
}
