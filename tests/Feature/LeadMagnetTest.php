<?php

namespace Tests\Feature;

use App\Mail\DepositBackChecklistMail;
use App\Models\LeadMagnetDownload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LeadMagnetTest extends TestCase
{
    use RefreshDatabase;

    public function test_end_of_tenancy_page_shows_the_checklist_capture_form(): void
    {
        $html = $this->get('/services/end-of-tenancy')->assertStatus(200)->getContent();

        $this->assertStringContainsString('Deposit-Back Checklist', $html);
        $this->assertStringContainsString(route('lead-magnets.deposit-back-checklist'), $html);
    }

    public function test_other_service_pages_do_not_show_the_checklist_form(): void
    {
        $html = $this->get('/services/deep-cleaning')->assertStatus(200)->getContent();

        $this->assertStringNotContainsString('Deposit-Back Checklist', $html);
    }

    public function test_submitting_the_form_stores_a_lead_sends_an_email_and_downloads_the_pdf(): void
    {
        Mail::fake();

        $response = $this->post(route('lead-magnets.deposit-back-checklist'), [
            'name' => 'Jane Tenant',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        $this->assertDatabaseHas('lead_magnet_downloads', [
            'lead_magnet' => 'deposit-back-checklist',
            'name' => 'Jane Tenant',
            'email' => 'jane@example.com',
        ]);

        Mail::assertSent(DepositBackChecklistMail::class, fn ($mail) => $mail->hasTo('jane@example.com') && $mail->name === 'Jane Tenant');
    }

    public function test_invalid_email_is_rejected(): void
    {
        $response = $this->from('/services/end-of-tenancy')->post(route('lead-magnets.deposit-back-checklist'), [
            'name' => 'Jane Tenant',
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('lead_magnet_downloads', 0);
    }
}
