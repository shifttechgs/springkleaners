<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Mail\BookingRequestReceivedMail;
use App\Mail\NewBookingAlertMail;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Before & after photo consent: optional when booking, off unless ticked, and stated on the quote, the PDF and
 * the emails so both sides can see what was agreed.
 */
class PhotoConsentTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'service' => 'deep-cleaning',
            'date' => Carbon::now()->next(Carbon::SATURDAY)->toDateString(),
            'time' => Booking::MORNING_SLOT,
            'name' => 'Test Client',
            'phone' => '0821234567',
            'email' => 'client@example.com',
        ], $overrides);
    }

    private function quotedBooking(bool $consent): Booking
    {
        return Booking::create([
            'service' => 'deep-cleaning',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '9:00 AM',
            'name' => 'Jane Client',
            'phone' => '0821234567',
            'photo_consent' => $consent,
            'subtotal' => 1600,
            'total' => 1750,
            'status' => BookingStatus::Quoted,
            'quoted_price' => 1750,
            'accepted_token' => Str::random(32),
        ]);
    }

    public function test_ticked_box_is_saved(): void
    {
        Mail::fake();

        $this->postJson('/book/reserve', $this->payload(['photo_consent' => true]))->assertOk()->assertJson(['status' => 'ok']);

        $this->assertTrue(Booking::firstOrFail()->photo_consent);
    }

    public function test_booking_works_without_consent_and_defaults_to_no(): void
    {
        Mail::fake();

        $this->postJson('/book/reserve', $this->payload())->assertOk()->assertJson(['status' => 'ok']);
        $this->postJson('/book/reserve', $this->payload(['photo_consent' => null, 'time' => Booking::TIME_SLOTS[1], 'phone' => '0827654321']))
            ->assertOk()->assertJson(['status' => 'ok']);

        $this->assertSame([false, false], Booking::orderBy('id')->pluck('photo_consent')->all());
    }

    public function test_quote_page_and_pdf_state_what_was_agreed(): void
    {
        $yes = $this->quotedBooking(true);
        $no = $this->quotedBooking(false);

        $this->get(route('quote.show', $yes->accepted_token))->assertOk()
            ->assertSee('Before &amp; after photos', false)
            ->assertSee('Yes, as proof of the work and to showcase it');
        $this->get(route('quote.show', $no->accepted_token))->assertOk()
            ->assertSee('No, we won&#039;t take any', false);

        $pdf = view('pdf.quote', ['booking' => $yes])->render();
        $this->assertStringContainsString('Before &amp; after photos', $pdf);
        $this->assertStringContainsString('Yes, as proof of the work and to showcase it', $pdf);
    }

    public function test_emails_state_what_was_agreed(): void
    {
        $booking = $this->quotedBooking(true);

        $this->assertStringContainsString('Yes, as proof of the work and to showcase it', (new BookingRequestReceivedMail($booking))->render());
        $this->assertStringContainsString('Yes, before &amp; after allowed', (new NewBookingAlertMail($booking))->render());
    }

    public function test_both_booking_forms_show_the_same_optional_checkbox(): void
    {
        foreach (['/', '/book'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('Yes, take before &amp; after photos of my clean', false)
                ->assertSee('proof of the work we did for you');
        }
    }
}
