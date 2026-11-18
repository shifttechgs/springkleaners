<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingSlotsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function saturday(): string
    {
        return Carbon::now()->next(Carbon::SATURDAY)->toDateString();
    }

    private function payload(string $time, array $overrides = []): array
    {
        return [
            'service' => 'deep-cleaning',
            'date' => $this->saturday(),
            'time' => $time,
            'name' => 'Test Client',
            'phone' => '0821234567',
            'email' => 'client@example.com',
            ...$overrides,
        ];
    }

    public function test_only_the_two_slots_are_accepted(): void
    {
        $this->postJson('/book/reserve', $this->payload('9:00 AM'))->assertStatus(422);
        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT))->assertOk();
    }

    public function test_the_same_slot_cannot_be_booked_twice(): void
    {
        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT))->assertOk();

        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT, ['phone' => '0830000000']))
            ->assertStatus(409)
            ->assertJson(['status' => 'time_taken']);

        $this->assertSame(1, Booking::count());
    }

    public function test_both_slots_fill_the_day(): void
    {
        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT))->assertOk();
        $this->postJson('/book/reserve', $this->payload(Booking::AFTERNOON_SLOT))->assertOk();

        $this->postJson('/book/reserve', $this->payload(Booking::AFTERNOON_SLOT))
            ->assertStatus(409)
            ->assertJson(['status' => 'full']);

        $this->assertSame(2, Booking::count());
    }

    public function test_an_older_single_time_booking_blocks_its_slot(): void
    {
        Booking::create([
            'service' => 'deep-cleaning', 'date' => $this->saturday(), 'time' => '2:00 PM',
            'name' => 'Old', 'phone' => '0820000000', 'status' => BookingStatus::Accepted,
        ]);

        $this->postJson('/book/reserve', $this->payload(Booking::AFTERNOON_SLOT))
            ->assertStatus(409)
            ->assertJson(['status' => 'time_taken']);

        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT))->assertOk();
    }

    public function test_a_declined_booking_frees_its_slot(): void
    {
        Booking::create([
            'service' => 'deep-cleaning', 'date' => $this->saturday(), 'time' => Booking::MORNING_SLOT,
            'name' => 'Gone', 'phone' => '0820000000', 'status' => BookingStatus::Declined,
        ]);

        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT))->assertOk();
    }

    public function test_availability_lists_slots_and_taken_times(): void
    {
        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT))->assertOk();

        $this->getJson('/book/availability?month='.$this->saturday())
            ->assertOk()
            ->assertJsonPath('slots', Booking::TIME_SLOTS)
            ->assertJsonPath('times.'.$this->saturday(), [Booking::MORNING_SLOT]);
    }

    public function test_admin_cannot_double_book_or_reopen_into_a_taken_slot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $declined = Booking::create([
            'service' => 'deep-cleaning', 'date' => $this->saturday(), 'time' => Booking::MORNING_SLOT,
            'name' => 'Declined', 'phone' => '0820000000', 'status' => BookingStatus::Declined,
        ]);

        $this->postJson('/book/reserve', $this->payload(Booking::MORNING_SLOT))->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.bookings.store'), [
                'name' => 'Phone lead', 'phone' => '0841111111', 'service' => 'deep-cleaning',
                'date' => $this->saturday(), 'time' => Booking::MORNING_SLOT,
            ])
            ->assertSessionHasErrors('time');

        $this->actingAs($admin)
            ->patch(route('admin.bookings.update', $declined), ['status' => BookingStatus::Pending->value])
            ->assertSessionHasErrors('status');

        $this->assertSame(BookingStatus::Declined, $declined->fresh()->status);
    }
}
