<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Support\QuotePricing;
use App\Support\Services;
use App\Support\ServiceSnapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AddonQuantitiesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function payload(array $overrides = []): array
    {
        return [
            'service' => 'deep-cleaning',
            'date' => Carbon::now()->next(Carbon::SATURDAY)->toDateString(),
            'time' => Booking::MORNING_SLOT,
            'name' => 'Test Client',
            'phone' => '0821234567',
            'email' => 'client@example.com',
            ...$overrides,
        ];
    }

    public function test_window_and_door_panel_addons_are_priced_per_item(): void
    {
        $addons = collect(Services::addons())->keyBy('key');

        $this->assertSame([50.0, 'window'], [$addons['windows']['price'], $addons['windows']['unit']]);
        $this->assertSame([80.0, 'panel'], [$addons['stacking-door-panels']['price'], $addons['stacking-door-panels']['unit']]);
        $this->assertSame([80.0, 'panel'], [$addons['sliding-door-panels']['price'], $addons['sliding-door-panels']['unit']]);
        $this->assertNull($addons['balcony']['unit'] ?? null, 'Flat add-ons stay flat');
    }

    public function test_booking_saves_quantities_and_the_quote_multiplies_them(): void
    {
        $this->postJson('/book/reserve', $this->payload([
            'addons' => ['windows', 'sliding-door-panels', 'balcony'],
            'addon_quantities' => ['windows' => 12, 'sliding-door-panels' => 4, 'balcony' => 9],
        ]))->assertOk();

        $booking = Booking::sole();

        $this->assertSame(['windows', 'sliding-door-panels', 'balcony'], $booking->addons);
        $this->assertSame(['windows' => 12, 'sliding-door-panels' => 4], $booking->addon_quantities, 'Flat add-ons never carry a quantity');

        $lines = collect(QuotePricing::lineItems($booking))->keyBy('description');

        $this->assertSame([12, 50.0, 600.0], [$lines['Windows (inside & out) (per window)']['qty'], $lines['Windows (inside & out) (per window)']['unit_price'], $lines['Windows (inside & out) (per window)']['total']]);
        $this->assertSame([4, 80.0, 320.0], [$lines['Sliding glass doors (per panel)']['qty'], $lines['Sliding glass doors (per panel)']['unit_price'], $lines['Sliding glass doors (per panel)']['total']]);
    }

    public function test_per_item_addon_with_no_quantity_is_dropped(): void
    {
        $this->postJson('/book/reserve', $this->payload([
            'addons' => ['windows', 'stacking-door-panels', 'not-a-real-addon'],
            'addon_quantities' => ['windows' => 0],
        ]))->assertOk();

        $booking = Booking::sole();

        $this->assertSame([], $booking->addons);
        $this->assertNull($booking->addon_quantities);
    }

    public function test_older_flat_window_addon_keeps_its_frozen_price(): void
    {
        $booking = Booking::create([
            'service' => 'deep-cleaning', 'date' => now()->toDateString(), 'time' => '9:00 AM',
            'name' => 'Old', 'phone' => '0820000000', 'addons' => ['windows'],
            'service_snapshot' => [
                'name' => 'Deep Cleaning', 'description' => null, 'inclusions' => [], 'avg_hours' => null, 'unit_label' => null,
                'addons' => [['key' => 'windows', 'label' => 'Interior windows', 'price' => 200.0, 'description' => null]],
            ],
        ]);

        $line = collect(QuotePricing::lineItems($booking))->firstWhere('description', 'Interior windows');

        $this->assertSame([1, 200.0, 200.0], [$line['qty'], $line['unit_price'], $line['total']]);
    }

    public function test_snapshot_records_the_quantity(): void
    {
        $booking = Booking::create([
            'service' => 'deep-cleaning', 'date' => now()->toDateString(), 'time' => Booking::MORNING_SLOT,
            'name' => 'New', 'phone' => '0820000000',
            'addons' => ['stacking-door-panels'], 'addon_quantities' => ['stacking-door-panels' => 6],
        ]);

        $addon = ServiceSnapshot::build($booking)['addons'][0];

        $this->assertSame(['stacking-door-panels', 80.0, 'panel', 6], [$addon['key'], $addon['price'], $addon['unit_label'], $addon['qty']]);
    }

    public function test_admin_booking_saves_quantities(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('admin.bookings.store'), [
                'name' => 'Phone lead', 'phone' => '0841111111', 'service' => 'deep-cleaning',
                'date' => Carbon::now()->next(Carbon::SATURDAY)->toDateString(), 'time' => Booking::AFTERNOON_SLOT,
                'addons' => ['windows'], 'addon_quantities' => ['windows' => 8],
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(['windows' => 8], Booking::sole()->addon_quantities);
    }

    public function test_window_cleaning_service_starts_at_fifty_rand_per_window(): void
    {
        $service = Services::find('window-cleaning');

        $this->assertSame([50.0, 'window'], [$service['base_price'], $service['unit_label']]);
    }
}
