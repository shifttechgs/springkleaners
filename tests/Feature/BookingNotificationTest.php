<?php

namespace Tests\Feature;

use App\Mail\BookingRequestReceivedMail;
use App\Mail\NewBookingAlertMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function validBookingPayload(): array
    {
        return [
            'service' => 'deep-cleaning',
            'date' => Carbon::now()->next(Carbon::SATURDAY)->toDateString(),
            'time' => \App\Models\Booking::MORNING_SLOT,
            'name' => 'Test Client',
            'phone' => '0821234567',
            'email' => 'client@example.com',
        ];
    }

    public function test_official_address_is_always_notified_with_no_subscribers(): void
    {
        Mail::fake();

        $this->postJson('/book/reserve', $this->validBookingPayload())
            ->assertOk()
            ->assertJson(['status' => 'ok']);

        Mail::assertSent(NewBookingAlertMail::class, fn ($mail) => $mail->hasTo('bookings@springkleaners.co.za'));
        Mail::assertSent(NewBookingAlertMail::class, fn ($mail) => $mail->hasTo('shifttechgs1@gmail.com'));
        Mail::assertSent(NewBookingAlertMail::class, fn ($mail) => $mail->hasTo('netsaicharity@gmail.com'));
        Mail::assertSent(BookingRequestReceivedMail::class, fn ($mail) => $mail->hasTo('client@example.com'));
        Mail::assertSentCount(4);
    }

    public function test_opted_in_subscriber_also_receives_it(): void
    {
        Mail::fake();

        User::factory()->create([
            'email' => 'staff@example.com',
            'notify_new_bookings' => true,
        ]);

        $this->postJson('/book/reserve', $this->validBookingPayload())->assertOk();

        Mail::assertSent(NewBookingAlertMail::class, fn ($mail) => $mail->hasTo('bookings@springkleaners.co.za'));
        Mail::assertSent(NewBookingAlertMail::class, fn ($mail) => $mail->hasTo('staff@example.com'));
        Mail::assertSent(BookingRequestReceivedMail::class, fn ($mail) => $mail->hasTo('client@example.com'));
        Mail::assertSentCount(5);
    }

    public function test_no_duplicate_when_official_address_is_also_opted_in(): void
    {
        Mail::fake();

        User::factory()->create([
            'email' => 'bookings@springkleaners.co.za',
            'notify_new_bookings' => true,
        ]);

        $this->postJson('/book/reserve', $this->validBookingPayload())->assertOk();

        Mail::assertSentCount(4);
        Mail::assertSent(NewBookingAlertMail::class, fn ($mail) => $mail->hasTo('bookings@springkleaners.co.za'));
        Mail::assertSent(BookingRequestReceivedMail::class, fn ($mail) => $mail->hasTo('client@example.com'));
    }

    public function test_copied_address_gets_one_alert_even_if_also_opted_in(): void
    {
        Mail::fake();

        User::factory()->create([
            'email' => 'netsaicharity@gmail.com',
            'notify_new_bookings' => true,
        ]);

        $this->postJson('/book/reserve', $this->validBookingPayload())->assertOk();

        Mail::assertSent(NewBookingAlertMail::class, fn ($mail) => $mail->hasTo('netsaicharity@gmail.com'), 1);
        Mail::assertSentCount(4);
    }

    public function test_weekday_booking_is_rejected(): void
    {
        Mail::fake();

        $payload = ['date' => Carbon::now()->next(Carbon::WEDNESDAY)->toDateString()] + $this->validBookingPayload();

        $this->postJson('/book/reserve', $payload)
            ->assertStatus(422)
            ->assertJson(['status' => 'invalid_day']);

        Mail::assertNothingSent();
    }
}
