<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Mail\InvoiceMail;
use App\Models\Booking;
use App\Models\User;
use App\Support\Company;
use App\Support\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankingDetailsTest extends TestCase
{
    use RefreshDatabase;

    private function booking(array $attrs = []): Booking
    {
        return Booking::create([
            'service' => 'deep-cleaning', 'date' => now()->addWeek()->toDateString(), 'time' => Booking::MORNING_SLOT,
            'name' => 'Bank Client', 'phone' => '0820000000', 'bedrooms' => '2', 'bathrooms' => '1', 'extra_rooms' => '0',
            'total' => 1230, 'quoted_price' => 1230, 'status' => BookingStatus::Quoted, 'accepted_token' => 'bank-tok',
            'quote_sent_at' => now(), ...$attrs,
        ]);
    }

    private function invoiced(array $attrs = []): Booking
    {
        // fresh(): pick up the database default payment status ("unpaid"), as real invoices do.
        return $this->booking(['status' => BookingStatus::Completed, 'invoice_number' => 'INV-2026-0099', 'invoiced_at' => now(), ...$attrs])->fresh();
    }

    private function assertHasBanking(string $html, string $where): void
    {
        foreach ([Company::accountHolder(), Company::bankName(), Company::accountNo(), Company::branchCode()] as $value) {
            $this->assertStringContainsString(e($value), $html, "{$where} is missing banking detail: {$value}");
        }
    }

    public function test_quote_pdf_includes_banking_details_even_without_a_deposit(): void
    {
        $this->assertHasBanking(view('pdf.quote', ['booking' => $this->booking(['deposit_amount' => null])])->render(), 'Quote PDF');
    }

    public function test_client_quote_page_includes_banking_details(): void
    {
        $this->booking();

        $this->assertHasBanking($this->get(route('quote.show', 'bank-tok'))->assertOk()->getContent(), 'Client quote page');
    }

    public function test_invoice_pdf_includes_banking_details_unpaid_and_paid(): void
    {
        $unpaid = $this->invoiced();
        $this->assertHasBanking(view('pdf.invoice', ['booking' => $unpaid])->render(), 'Unpaid invoice PDF');

        $unpaid->update(['payment_status' => PaymentStatus::Paid, 'paid_at' => now()]);
        $this->assertHasBanking(view('pdf.invoice', ['booking' => $unpaid->fresh()])->render(), 'Paid invoice PDF');
    }

    public function test_client_invoice_page_and_invoice_email_include_banking_details(): void
    {
        $booking = $this->invoiced();

        $this->assertHasBanking($this->get(route('invoice.show', 'bank-tok'))->assertOk()->getContent(), 'Client invoice page');
        $this->assertHasBanking((new InvoiceMail($booking))->render(), 'Invoice email');
    }

    public function test_account_holder_and_type_are_editable_and_flow_onto_documents(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.company-settings.update'), [
            'name' => 'SpringKleaners', 'address' => '1 Stepney Road', 'cell' => '+27 815 274 711', 'email' => 'bookings@springkleaners.co.za',
            'account_holder' => 'SpringKleaners (Pty) Ltd', 'account_type' => 'Business cheque',
            'bank_name' => 'Nedbank', 'branch_code' => '104509', 'account_no' => '1195899688', 'reference_note' => 'Use your name as reference.',
        ])->assertSessionHasNoErrors();

        $this->assertSame('SpringKleaners (Pty) Ltd', Setting::get('banking_account_holder'));

        $html = view('pdf.quote', ['booking' => $this->booking()])->render();
        $this->assertStringContainsString('SpringKleaners (Pty) Ltd', $html);
        $this->assertStringContainsString('Business cheque', $html);
        $this->assertStringContainsString('Use your name as reference.', $html);
    }

    public function test_account_holder_defaults_to_the_business_name_and_empty_type_is_left_off(): void
    {
        $labels = array_column(Company::bankingRows(), 'label');

        $this->assertSame(Company::name(), Company::accountHolder());
        $this->assertNotContains('Account type', $labels);
    }
}
