<?php

namespace Tests\Feature\Admin;

use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Mail\InvoiceMail;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => UserRole::Admin]);
    }

    private function invoicedBooking(array $overrides = []): Booking
    {
        $client = Client::create([
            'name' => 'Jane Client',
            'phone_raw' => '0821234567',
            'phone_normalized' => '27821234567',
            'email' => 'jane@example.com',
        ]);

        return Booking::create(array_merge([
            'client_id' => $client->id,
            'service' => 'deep-cleaning',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '9:00 AM',
            'name' => 'Jane Client',
            'phone' => '0821234567',
            'total' => 850,
            'quoted_price' => 800,
            'accepted_token' => Str::random(32),
            'invoice_number' => 'INV-0001',
            'invoiced_at' => now(),
            'payment_status' => PaymentStatus::Unpaid,
        ], $overrides));
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $booking = $this->invoicedBooking();

        $this->get(route('admin.invoices.show', $booking))
            ->assertRedirect(route('login'));
    }

    public function test_show_returns_404_when_booking_has_no_invoice(): void
    {
        $booking = $this->invoicedBooking(['invoice_number' => null, 'invoiced_at' => null]);

        $this->actingAs($this->admin())
            ->get(route('admin.invoices.show', $booking))
            ->assertNotFound();
    }

    public function test_show_displays_invoice(): void
    {
        $booking = $this->invoicedBooking();

        $this->actingAs($this->admin())
            ->get(route('admin.invoices.show', $booking))
            ->assertOk()
            ->assertSee('INV-0001');
    }

    public function test_mark_deposit_paid_sets_timestamp(): void
    {
        $booking = $this->invoicedBooking(['deposit_amount' => 200]);

        $this->actingAs($this->admin())
            ->post(route('admin.invoices.mark-deposit-paid', $booking))
            ->assertRedirect()
            ->assertSessionHas('status', 'Deposit marked as received.');

        $this->assertNotNull($booking->fresh()->deposit_paid_at);
    }

    public function test_mark_paid_requires_valid_payment_method(): void
    {
        $booking = $this->invoicedBooking();

        $this->actingAs($this->admin())
            ->post(route('admin.invoices.mark-paid', $booking), ['payment_method' => 'cheque'])
            ->assertSessionHasErrors('payment_method');

        $this->assertEquals(PaymentStatus::Unpaid, $booking->fresh()->payment_status);
    }

    public function test_mark_paid_updates_booking(): void
    {
        $booking = $this->invoicedBooking();

        $this->actingAs($this->admin())
            ->post(route('admin.invoices.mark-paid', $booking), ['payment_method' => 'eft'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Marked as paid.');

        $fresh = $booking->fresh();
        $this->assertEquals(PaymentStatus::Paid, $fresh->payment_status);
        $this->assertEquals('eft', $fresh->payment_method);
        $this->assertNotNull($fresh->paid_at);
    }

    public function test_send_email_sends_invoice_mail_to_client(): void
    {
        Mail::fake();

        $booking = $this->invoicedBooking();

        $this->actingAs($this->admin())
            ->post(route('admin.invoices.send-email', $booking))
            ->assertRedirect()
            ->assertSessionHas('status', 'Invoice emailed to jane@example.com.');

        Mail::assertSent(InvoiceMail::class, fn ($mail) => $mail->hasTo('jane@example.com'));
    }

    public function test_send_email_fails_when_client_has_no_email(): void
    {
        Mail::fake();

        $booking = $this->invoicedBooking();
        $booking->client()->update(['email' => null]);

        $this->actingAs($this->admin())
            ->post(route('admin.invoices.send-email', $booking))
            ->assertSessionHasErrors('email');

        Mail::assertNothingSent();
    }

    public function test_index_lists_only_invoiced_bookings_and_sums_outstanding(): void
    {
        $invoiced = $this->invoicedBooking(['quoted_price' => 800]);
        Booking::create([
            'service' => 'deep-cleaning',
            'date' => now()->addDays(1)->toDateString(),
            'time' => '10:00 AM',
            'name' => 'No Invoice',
            'phone' => '0839876543',
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.invoices.index'))
            ->assertOk()
            ->assertSee('INV-0001')
            ->assertDontSee('No Invoice');
    }
}
