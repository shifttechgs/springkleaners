<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\User;
use App\Support\ServiceSnapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ServiceDetailsOnDocumentsTest extends TestCase
{
    use RefreshDatabase;

    private function booking(array $overrides = []): Booking
    {
        return Booking::create(array_merge([
            'service' => 'deep-cleaning',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '9:00 AM',
            'name' => 'Jane Client',
            'phone' => '0821234567',
            'property_type' => 'House',
            'bedrooms' => '3',
            'bathrooms' => '2',
            'extra_rooms' => '1',
            'floor_types' => ['Tile', 'Carpet'],
            'pets' => true,
            'subtotal' => 1600,
            'total' => 1750,
            'accepted_token' => Str::random(32),
        ], $overrides));
    }

    public function test_services_carry_their_scope_copy_after_migration(): void
    {
        $service = Service::where('slug', 'deep-cleaning')->firstOrFail();

        $this->assertNotEmpty($service->description);
        $this->assertContains('Kitchen & bathroom deep clean', $service->inclusions);
    }

    public function test_quote_pdf_lists_the_service_scope_and_job_details(): void
    {
        $booking = $this->booking(['status' => BookingStatus::Quoted, 'quoted_price' => 1750]);

        $html = view('pdf.quote', ['booking' => $booking])->render();

        $this->assertStringContainsString('What This Service Covers', $html);
        $this->assertStringContainsString('Kitchen &amp; bathroom deep clean', $html);
        $this->assertStringContainsString('Job Details', $html);
        $this->assertStringContainsString('Tile, Carpet', $html);
    }

    public function test_invoice_pdf_lists_the_service_scope_and_job_details(): void
    {
        $booking = $this->booking([
            'invoice_number' => 'INV-0001',
            'invoiced_at' => now(),
            'payment_status' => PaymentStatus::Unpaid,
        ]);

        $html = view('pdf.invoice', ['booking' => $booking])->render();

        $this->assertStringContainsString('What This Service Covered', $html);
        $this->assertStringContainsString('Kitchen &amp; bathroom deep clean', $html);
        $this->assertStringContainsString('Job Details', $html);
    }

    public function test_add_on_line_items_carry_their_description(): void
    {
        $addon = ServiceAddon::firstOrFail();
        $booking = $this->booking(['addons' => [$addon->key]]);

        $html = view('pdf.quote', ['booking' => $booking])->render();

        $this->assertStringContainsString(e($addon->label), $html);

        if (filled($addon->description)) {
            $this->assertStringContainsString(e($addon->description), $html);
        }
    }

    public function test_client_quote_page_shows_what_the_service_covers(): void
    {
        $booking = $this->booking(['status' => BookingStatus::Quoted, 'quoted_price' => 1750]);

        $this->get(route('quote.show', $booking->accepted_token))
            ->assertOk()
            ->assertSee('What this service covers', false)
            ->assertSee('Kitchen &amp; bathroom deep clean', false);
    }

    public function test_sending_a_quote_freezes_the_service_scope(): void
    {
        $booking = $this->booking(['status' => BookingStatus::Pending]);
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->post(route('admin.bookings.send-quote', $booking), ['quoted_price' => 1750])
            ->assertRedirect();

        $snapshot = $booking->fresh()->service_snapshot;

        $this->assertSame('Deep Cleaning', $snapshot['name']);
        $this->assertContains('Kitchen & bathroom deep clean', $snapshot['inclusions']);
    }

    public function test_reworded_service_does_not_change_an_already_quoted_document(): void
    {
        $booking = $this->booking(['status' => BookingStatus::Pending]);
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->post(route('admin.bookings.send-quote', $booking), ['quoted_price' => 1750]);

        Service::where('slug', 'deep-cleaning')->update([
            'name' => 'Renamed Cleaning',
            'inclusions' => json_encode(['Something else entirely']),
        ]);

        $html = view('pdf.quote', ['booking' => $booking->fresh()])->render();

        $this->assertStringContainsString('Kitchen &amp; bathroom deep clean', $html);
        $this->assertStringNotContainsString('Something else entirely', $html);
    }

    public function test_bookings_without_a_snapshot_still_show_current_service_details(): void
    {
        $booking = $this->booking();

        $this->assertNull($booking->service_snapshot);

        $scope = ServiceSnapshot::for($booking);

        $this->assertSame('Deep Cleaning', $scope['name']);
        $this->assertContains('Kitchen & bathroom deep clean', $scope['inclusions']);
    }

    public function test_admin_can_edit_the_scope_copy(): void
    {
        $service = Service::where('slug', 'deep-cleaning')->firstOrFail();
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($admin)
            ->put(route('admin.services.update', $service), [
                ...$service->only([
                    'slug', 'name', 'tagline', 'icon', 'base_price', 'included_bedrooms',
                    'included_bathrooms', 'bedroom_price', 'bathroom_price', 'extra_room_price',
                    'service_fee', 'avg_hours', 'unit_label', 'sort_order',
                ]),
                'description' => 'A very thorough clean.',
                'inclusions' => "- Oven degreased\n\n• Windows cleaned inside\n",
                'bookable' => '1',
            ])
            ->assertRedirect(route('admin.services.index'));

        $fresh = $service->fresh();

        $this->assertSame('A very thorough clean.', $fresh->description);
        $this->assertSame(['Oven degreased', 'Windows cleaned inside'], $fresh->inclusions);
    }
}
