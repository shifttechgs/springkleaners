<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPageRendersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The booking wizard's Alpine state lives in one double-quoted x-data attribute. Anything printed into it
     * with raw double quotes ends the attribute early and kills the whole page, so check it stays in one piece.
     */
    public function test_booking_wizard_state_is_not_cut_short(): void
    {
        $html = $this->get('/book')->assertOk()->getContent();

        // Read the attribute the way a browser would, so a stray double quote shows up as a cut-off value.
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        $state = (new \DOMXPath($dom))->evaluate('string((//div[@x-data])[1]/@x-data)');

        $this->assertStringContainsString('timeSlots:', $state);
        $this->assertStringContainsString('bookableDays:', $state);
        $this->assertStringContainsString('submitForm()', $state, 'The x-data attribute ended early.');
    }
}
