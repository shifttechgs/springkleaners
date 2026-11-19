<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HeroQuoteFormTest extends TestCase
{
    use RefreshDatabase;

    private function heroState(string $html): string
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);

        return (new \DOMXPath($dom))->evaluate('string(//*[@id="hero-form"]//div[@x-data][1]/@x-data)');
    }

    public function test_homepage_hero_has_the_stepper_with_live_pricing(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        $state = $this->heroState($html);

        // The hero card runs the shared booking logic (partials/quote-flow-script), which is on the page.
        $this->assertStringStartsWith('quoteFlow(', $state);
        $this->assertStringContainsString("Alpine.data('quoteFlow'", $html);
        foreach (['dateOptions', 'submit()', 'loadDates', 'fullFormLink'] as $piece) {
            $this->assertStringContainsString($piece, $html, "Booking logic is missing {$piece}");
        }

        // Bookable services travel with their pricing so the price updates without a round trip.
        $this->assertStringContainsString('base_price', $state);
        $this->assertStringContainsString('service_fee', $state);
        $this->assertStringContainsString('deep-cleaning', $state);
        $this->assertStringContainsString('Get your instant quote', $html);
    }

    public function test_what_the_hero_submits_creates_a_booking(): void
    {
        Mail::fake();
        $saturday = Carbon::now()->next(Carbon::SATURDAY)->toDateString();

        // Same payload shape the hero's submit() sends (5+ / 4+ sizes, a slot label, totals).
        $this->postJson('/book/reserve', [
            'service' => 'deep-cleaning', 'date' => $saturday, 'time' => Booking::AFTERNOON_SLOT,
            'name' => 'Hero Client', 'phone' => '082 123 4567', 'email' => 'hero@example.com',
            'address' => '12 Beach Road', 'suburb' => 'Milnerton', 'property_type' => 'House',
            'bedrooms' => '5+', 'bathrooms' => '4+', 'extra_rooms' => '1',
            'booking_type' => 'once-off', 'subtotal' => 1880, 'total' => 1910,
        ])->assertOk()->assertJson(['status' => 'ok']);

        $booking = Booking::sole();
        $this->assertSame(['5+', '4+', '1', Booking::AFTERNOON_SLOT, 'Milnerton'], [$booking->bedrooms, $booking->bathrooms, $booking->extra_rooms, $booking->time, $booking->suburb]);
        $this->assertEquals(1910.0, (float) $booking->total);
    }

    public function test_homepage_serves_both_layouts_from_one_url(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // Phones: the app layout (tiles, booking sheet, tab bar) with the shared booking logic in app layout.
        $this->assertStringContainsString('id="app-services"', $html);
        $this->assertStringContainsString('aria-label="App"', $html);
        $this->assertSame(2, substr_count($html, 'x-data="quoteFlow('), 'Expected the hero card and the phone booking sheet');
        $this->assertStringContainsString("Where's the clean?", $html);
        // Computers: the classic hero card. And every content section is still in the HTML for search engines.
        $this->assertStringContainsString('id="hero-form"', $html);
        foreach (['id="services"', 'id="pricing"', 'id="faq"', 'id="testimonials"', 'id="how-it-works"', 'id="why-us"'] as $section) {
            $this->assertStringContainsString($section, $html, "Homepage HTML is missing {$section}");
        }
    }
}
