<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/** Dates in config/closed_dates.php can't be booked anywhere and show as Closed. */
class ClosedDatesTest extends TestCase
{
    use RefreshDatabase;

    private string $closed;

    protected function setUp(): void
    {
        parent::setUp();
        // A Saturday we'd normally take bookings on, marked closed.
        $this->closed = Carbon::now()->next(Carbon::SATURDAY)->addWeek()->toDateString();
        config(['closed_dates' => [$this->closed => 'Team day off']]);
    }

    public function test_christmas_weekend_2026_is_closed(): void
    {
        $this->refreshApplication(); // the real config file, not the test override
        $this->assertTrue(Booking::isClosed(Carbon::parse('2026-12-26')));
        $this->assertTrue(Booking::isClosed(Carbon::parse('2026-12-27')));
        $this->assertFalse(Booking::isBookableDay(Carbon::parse('2026-12-26')));
        $this->assertTrue(Booking::isBookableDay(Carbon::parse('2026-12-19')));
    }

    public function test_a_closed_date_cannot_be_booked(): void
    {
        Mail::fake();

        $this->postJson('/book/reserve', [
            'service' => 'deep-cleaning', 'date' => $this->closed, 'time' => Booking::MORNING_SLOT,
            'name' => 'Test Client', 'phone' => '0821234567', 'email' => 'client@example.com',
        ])->assertStatus(422)->assertJson(['status' => 'closed']);

        $this->assertSame(0, Booking::count());
    }

    public function test_availability_and_forms_know_the_closed_dates(): void
    {
        $this->getJson('/book/availability?month='.$this->closed)
            ->assertOk()
            ->assertJsonPath('closed.'.$this->closed, 'Team day off');

        // Hero card + phone sheet (shared booking logic) and the full form all carry the list.
        $this->assertStringContainsString($this->closed, $this->get('/')->getContent());
        $this->assertStringContainsString($this->closed, $this->get('/book')->getContent());
    }

    public function test_next_available_date_skips_closed_dates(): void
    {
        $json = $this->getJson('/book/availability')->assertOk()->json();
        $this->assertNotSame($this->closed, $json['next_available']);
    }
}
