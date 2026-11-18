<?php

namespace Tests\Feature\Admin;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Expense;
use App\Models\User;
use App\Support\AdminPagination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaginationTest extends TestCase
{
    use RefreshDatabase;

    private const ROWS = AdminPagination::PER_PAGE + 3;

    private User $admin;

    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'name' => 'Aaa Admin']);
        $this->client = Client::create(['name' => 'Paged Client', 'phone_raw' => '0820000000', 'phone_normalized' => '27820000000']);
    }

    private function bookings(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $booking = Booking::create([
                'client_id' => $this->client->id, 'service' => 'deep-cleaning', 'date' => now()->subDays($i)->toDateString(),
                'time' => Booking::MORNING_SLOT, 'name' => 'Paged Client', 'phone' => '0820000000', 'total' => 1000,
                'status' => BookingStatus::Completed,
            ]);
            $booking->update(['invoice_number' => 'INV-T-'.$i, 'invoiced_at' => now()->subDays($i)]);
        }
    }

    /** @return array<string, array{0: string, 1: string}> */
    public static function lists(): array
    {
        return [
            'bookings' => ['/admin/bookings', 'bookings'],
            'invoices' => ['/admin/invoices', 'invoices'],
            'expenses' => ['/admin/expenses', 'expenses'],
            'users' => ['/admin/users', 'users'],
        ];
    }

    /** @dataProvider lists */
    public function test_every_admin_table_shows_eight_rows_per_page(string $url, string $var): void
    {
        $this->bookings(self::ROWS);
        for ($i = 1; $i <= self::ROWS; $i++) {
            Expense::create(['description' => 'Supplies '.$i, 'amount' => 100, 'date' => now()->subDays($i)->toDateString(), 'category' => 'stock']);
        }
        User::factory()->count(self::ROWS)->create();

        $first = $this->actingAs($this->admin)->get($url)->assertOk()->viewData($var);
        $this->assertSame(AdminPagination::PER_PAGE, $first->perPage());
        $this->assertCount(AdminPagination::PER_PAGE, $first->items());
        $this->assertTrue($first->hasPages());

        $second = $this->actingAs($this->admin)->get($url.'?page=2')->assertOk()->viewData($var);
        $this->assertCount($first->total() - AdminPagination::PER_PAGE, $second->items());
    }

    public function test_clients_list_and_a_clients_booking_history_page_by_eight(): void
    {
        $this->bookings(self::ROWS);
        for ($i = 1; $i <= self::ROWS; $i++) {
            Client::create(['name' => 'Client '.$i, 'phone_raw' => '08300000'.str_pad((string) $i, 2, '0', STR_PAD_LEFT), 'phone_normalized' => '278300000'.str_pad((string) $i, 2, '0', STR_PAD_LEFT)]);
        }

        $clients = $this->actingAs($this->admin)->get('/admin/clients')->assertOk()->viewData('clients');
        $this->assertCount(AdminPagination::PER_PAGE, $clients->items());

        $history = $this->actingAs($this->admin)->get(route('admin.clients.show', $this->client))->assertOk();
        $this->assertCount(AdminPagination::PER_PAGE, $history->viewData('bookings')->items());
        $history->assertSee('Showing');

        $page2 = $this->actingAs($this->admin)->get(route('admin.clients.show', [$this->client, 'page' => 2]))->assertOk();
        $this->assertCount(self::ROWS - AdminPagination::PER_PAGE, $page2->viewData('bookings')->items());
    }

    public function test_a_page_past_the_end_goes_to_the_last_page_and_keeps_filters(): void
    {
        $this->bookings(self::ROWS);

        $this->actingAs($this->admin)
            ->get('/admin/bookings?status=completed&page=50')
            ->assertRedirect('/admin/bookings?status=completed&page=2');
    }

    public function test_a_short_list_shows_no_pager(): void
    {
        $this->actingAs($this->admin)->get('/admin/users')->assertOk()->assertDontSee('Showing');
    }
}
