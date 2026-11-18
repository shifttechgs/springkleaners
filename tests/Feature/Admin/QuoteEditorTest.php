<?php

namespace Tests\Feature\Admin;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use App\Support\QuotePricing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteEditorTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function booking(array $attrs = []): Booking
    {
        return Booking::create([
            'service' => 'deep-cleaning', 'date' => now()->addWeek()->toDateString(), 'time' => Booking::MORNING_SLOT,
            'name' => 'Quote Client', 'phone' => '0820000000', 'bedrooms' => '2', 'bathrooms' => '1', 'extra_rooms' => '0',
            'total' => 1230, 'status' => BookingStatus::Pending, ...$attrs,
        ]);
    }

    private function lines(array ...$rows): array
    {
        return ['items' => array_map(fn ($r) => ['description' => $r[0], 'note' => $r[3] ?? '', 'qty' => $r[1], 'unit_price' => $r[2]], $rows)];
    }

    public function test_editor_opens_with_the_generated_lines(): void
    {
        $booking = $this->booking();

        $res = $this->actingAs($this->admin)->get(route('admin.bookings.quote.edit', $booking))->assertOk();

        $this->assertEquals(QuotePricing::automaticLineItems($booking), $res->viewData('items'));
        $this->assertFalse($res->viewData('isCustom'));
    }

    public function test_admin_can_reword_reprice_add_and_discount_lines(): void
    {
        $booking = $this->booking();

        $this->actingAs($this->admin)
            ->put(route('admin.bookings.quote.update', $booking), [
                ...$this->lines(
                    ['Deep clean — 2 bed, 1 bath', 1, 1200, 'Includes inside the oven'],
                    ['Windows (inside & out)', 10, 50],
                    ['Loyalty discount', 1, -150],
                ),
                'deposit_amount' => 300,
            ])
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();
        $this->assertEquals(1550.0, (float) $booking->quoted_price); // 1200 + 500 - 150
        $this->assertEquals(300.0, (float) $booking->deposit_amount);
        $this->assertNotNull($booking->quote_edited_at);

        $lines = QuotePricing::lineItems($booking);
        $this->assertCount(3, $lines);
        $this->assertSame(['Windows (inside & out)', 10, 50.0, 500.0], [$lines[1]['description'], $lines[1]['qty'], $lines[1]['unit_price'], $lines[1]['total']]);
        $this->assertSame('Includes inside the oven', $lines[0]['note']);
        $this->assertSame(-150.0, $lines[2]['total']);
    }

    public function test_quote_total_cannot_go_below_zero_and_deposit_cannot_exceed_it(): void
    {
        $booking = $this->booking();

        $this->actingAs($this->admin)
            ->put(route('admin.bookings.quote.update', $booking), $this->lines(['Clean', 1, 100], ['Discount', 1, -200]))
            ->assertSessionHasErrors('items');

        $this->actingAs($this->admin)
            ->put(route('admin.bookings.quote.update', $booking), [...$this->lines(['Clean', 1, 100]), 'deposit_amount' => 150])
            ->assertSessionHasErrors('deposit_amount');

        $this->actingAs($this->admin)
            ->put(route('admin.bookings.quote.update', $booking), ['items' => [['description' => '', 'qty' => 0, 'unit_price' => 'x']]])
            ->assertSessionHasErrors(['items.0.description', 'items.0.qty', 'items.0.unit_price']);

        $this->assertNull($booking->fresh()->quote_items);
    }

    public function test_changing_an_accepted_quote_sends_it_back_for_the_client_to_confirm(): void
    {
        $booking = $this->booking(['status' => BookingStatus::Accepted, 'quoted_price' => 1230, 'accepted_at' => now(), 'accepted_token' => 'tok-1', 'quote_sent_at' => now()]);

        $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), $this->lines(['Deep clean', 1, 1400]))->assertRedirect();

        $booking->refresh();
        $this->assertSame(BookingStatus::Quoted, $booking->status);
        $this->assertNull($booking->accepted_at);
        $this->assertSame('tok-1', $booking->accepted_token, 'Same client link');
    }

    public function test_an_accepted_quote_with_the_same_total_stays_accepted(): void
    {
        $booking = $this->booking(['status' => BookingStatus::Accepted, 'quoted_price' => 1230, 'accepted_at' => now(), 'accepted_token' => 'tok-2']);

        $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), $this->lines(['Deep clean (reworded)', 1, 1230]));

        $this->assertSame(BookingStatus::Accepted, $booking->fresh()->status);
    }

    public function test_an_invoiced_quote_is_locked(): void
    {
        $booking = $this->booking(['status' => BookingStatus::Completed, 'quoted_price' => 1230, 'invoice_number' => 'INV-2026-0001', 'invoiced_at' => now()]);

        $this->actingAs($this->admin)->get(route('admin.bookings.quote.edit', $booking))->assertRedirect(route('admin.bookings.show', $booking));
        $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), $this->lines(['Cheaper', 1, 10]))->assertSessionHasErrors('quote');
        $this->actingAs($this->admin)->post(route('admin.bookings.send-quote', $booking), ['quoted_price' => 10])->assertSessionHasErrors('quote');

        $this->assertEquals(1230.0, (float) $booking->fresh()->quoted_price);
        $this->assertSame(BookingStatus::Completed, $booking->fresh()->status);
    }

    public function test_quick_price_on_an_automatic_quote_adds_an_adjustment_line(): void
    {
        $booking = $this->booking();
        $auto = QuotePricing::total(QuotePricing::automaticLineItems($booking));

        $this->actingAs($this->admin)->post(route('admin.bookings.send-quote', $booking), ['quoted_price' => $auto + 170])->assertRedirect();

        $booking->refresh();
        $lines = QuotePricing::lineItems($booking);
        $this->assertSame('Price adjustment', end($lines)['description']);
        $this->assertEquals(170.0, end($lines)['total']);
        $this->assertEquals((float) $booking->quoted_price, QuotePricing::total($lines), 'Lines add up to the price the client sees');
        $this->assertSame(BookingStatus::Quoted, $booking->status);
    }

    public function test_quick_price_cannot_override_hand_edited_lines(): void
    {
        $booking = $this->booking();
        $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), $this->lines(['Deep clean', 1, 900]));

        $this->actingAs($this->admin)->post(route('admin.bookings.send-quote', $booking), ['quoted_price' => 5000]);
        $this->assertEquals(900.0, (float) $booking->fresh()->quoted_price);

        $this->actingAs($this->admin)->patch(route('admin.bookings.update', $booking), ['status' => 'quoted', 'quoted_price' => 1]);
        $this->assertEquals(900.0, (float) $booking->fresh()->quoted_price);
    }

    public function test_reset_goes_back_to_automatic_pricing(): void
    {
        $booking = $this->booking(['quote_sent_at' => now(), 'status' => BookingStatus::Quoted]);
        $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), $this->lines(['Deep clean', 1, 900]));

        $this->actingAs($this->admin)->delete(route('admin.bookings.quote.reset', $booking))->assertRedirect(route('admin.bookings.quote.edit', $booking));

        $booking->refresh();
        $this->assertNull($booking->quote_items);
        $this->assertEquals(QuotePricing::total(QuotePricing::automaticLineItems($booking)), (float) $booking->quoted_price);
    }

    public function test_client_quote_page_and_pdf_show_the_edited_lines(): void
    {
        $booking = $this->booking(['accepted_token' => 'tok-3', 'status' => BookingStatus::Quoted, 'quote_sent_at' => now()]);
        $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), $this->lines(['Bespoke deep clean', 1, 1000], ['Loyalty discount', 1, -100]));

        $this->get(route('quote.show', 'tok-3'))->assertOk()
            ->assertSee('Bespoke deep clean')->assertSee('Loyalty discount')->assertSee('R900.00');

        $html = view('pdf.quote', ['booking' => $booking->fresh()])->render();
        $this->assertStringContainsString('Bespoke deep clean', $html);
        $this->assertStringContainsString('900.00', $html);
    }

    private function saturday(int $weeksAhead = 1): string
    {
        return now()->next(\Illuminate\Support\Carbon::SATURDAY)->addWeeks($weeksAhead - 1)->toDateString();
    }

    public function test_admin_can_move_the_service_date_and_slot(): void
    {
        $booking = $this->booking(['date' => $this->saturday(), 'time' => Booking::MORNING_SLOT]);
        $sunday = now()->next(\Illuminate\Support\Carbon::SATURDAY)->addDay()->toDateString();

        $this->actingAs($this->admin)
            ->put(route('admin.bookings.quote.update', $booking), [...$this->lines(['Deep clean', 1, 1230]), 'date' => $sunday, 'time' => Booking::AFTERNOON_SLOT])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', fn ($msg) => str_contains($msg, 'Moved to'));

        $booking->refresh();
        $this->assertSame($sunday, $booking->date->toDateString());
        $this->assertSame(Booking::AFTERNOON_SLOT, $booking->time);
    }

    public function test_cannot_move_onto_a_booked_slot_a_weekday_or_the_past(): void
    {
        $booking = $this->booking(['date' => $this->saturday(), 'time' => Booking::MORNING_SLOT]);
        $this->booking(['date' => $this->saturday(2), 'time' => Booking::AFTERNOON_SLOT, 'name' => 'Other client']);
        $wednesday = now()->next(\Illuminate\Support\Carbon::WEDNESDAY)->toDateString();
        $lastSaturday = now()->previous(\Illuminate\Support\Carbon::SATURDAY)->toDateString();

        $put = fn (array $when) => $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), [...$this->lines(['Deep clean', 1, 1230]), ...$when]);

        $put(['date' => $this->saturday(2), 'time' => Booking::AFTERNOON_SLOT])->assertSessionHasErrors('time');
        $put(['date' => $wednesday, 'time' => Booking::MORNING_SLOT])->assertSessionHasErrors('date');
        $put(['date' => $lastSaturday, 'time' => Booking::MORNING_SLOT])->assertSessionHasErrors('date');
        $put(['date' => $this->saturday(2), 'time' => '9:00 AM'])->assertSessionHasErrors('time');

        $this->assertSame($this->saturday(), $booking->fresh()->date->toDateString(), 'Nothing saved on a failed move');
        $this->assertNull($booking->fresh()->quote_items);

        // The other slot that day is free.
        $put(['date' => $this->saturday(2), 'time' => Booking::MORNING_SLOT])->assertSessionHasNoErrors();
        $this->assertSame($this->saturday(2), $booking->fresh()->date->toDateString());
    }

    public function test_an_older_single_time_booking_keeps_its_time_when_not_moved(): void
    {
        // Saved on a weekday before the weekend-only rule, with an old single time.
        $wednesday = now()->next(\Illuminate\Support\Carbon::WEDNESDAY)->toDateString();
        $booking = $this->booking(['date' => $wednesday, 'time' => '9:00 AM']);

        $this->actingAs($this->admin)
            ->put(route('admin.bookings.quote.update', $booking), [...$this->lines(['Deep clean', 1, 1000]), 'date' => $wednesday, 'time' => Booking::MORNING_SLOT])
            ->assertSessionHasNoErrors();

        $booking->refresh();
        $this->assertSame('9:00 AM', $booking->time);
        $this->assertSame($wednesday, $booking->date->toDateString());
        $this->assertEquals(1000.0, (float) $booking->quoted_price);
    }

    public function test_editor_page_state_is_quote_safe(): void
    {
        $booking = $this->booking();
        $this->actingAs($this->admin)->put(route('admin.bookings.quote.update', $booking), $this->lines(['Say "hello" & <b>bye</b>', 1, 100]));

        $html = $this->actingAs($this->admin)->get(route('admin.bookings.quote.edit', $booking))->assertOk()->getContent();

        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        $state = (new \DOMXPath($dom))->evaluate('string(//form[@x-data]/@x-data)');
        $this->assertStringContainsString('moveLine', $state, 'x-data attribute was cut short');

        $when = (new \DOMXPath($dom))->evaluate('string(//div[@x-data][.//input[@name="date"]]/@x-data)');
        $this->assertStringContainsString('isTaken', $when, 'Date & time x-data attribute was cut short');
    }
}
