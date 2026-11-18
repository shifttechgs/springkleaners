<?php

namespace Tests\Feature\Admin;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpcomingJobsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function booking(string $name, string $date, BookingStatus $status, string $time = Booking::MORNING_SLOT): Booking
    {
        return Booking::create([
            'service' => 'deep-cleaning', 'date' => $date, 'time' => $time,
            'name' => $name, 'phone' => '0820000000', 'total' => 1000, 'status' => $status,
        ]);
    }

    private function seedSchedule(): void
    {
        $this->booking('Pending Future', now()->addDays(3)->toDateString(), BookingStatus::Pending);
        $this->booking('Quoted Future', now()->addDays(2)->toDateString(), BookingStatus::Quoted);
        $this->booking('Accepted Today PM', now()->toDateString(), BookingStatus::Accepted, Booking::AFTERNOON_SLOT);
        $this->booking('Accepted Today AM', now()->toDateString(), BookingStatus::Accepted, Booking::MORNING_SLOT);

        $this->booking('Accepted Past', now()->subDays(2)->toDateString(), BookingStatus::Accepted);
        $this->booking('Completed Future', now()->addDays(4)->toDateString(), BookingStatus::Completed);
        $this->booking('Declined Future', now()->addDays(5)->toDateString(), BookingStatus::Declined);
        $this->booking('Expired Future', now()->addDays(6)->toDateString(), BookingStatus::Expired);
    }

    public function test_dashboard_lists_every_job_still_on_the_schedule_soonest_first(): void
    {
        $this->seedSchedule();

        $res = $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertOk();

        $this->assertSame(
            ['Accepted Today AM', 'Accepted Today PM', 'Quoted Future', 'Pending Future'],
            $res->viewData('upcomingJobs')->pluck('name')->all(),
        );
        $this->assertSame(4, $res->viewData('upcomingCount'));

        $res->assertSee('Pending Future')->assertSee('Today')
            ->assertDontSee('Accepted Past')->assertDontSee('Completed Future')->assertDontSee('Declined Future')
            ->assertDontSee('No upcoming jobs on the schedule');
    }

    public function test_dashboard_shows_an_empty_state_when_nothing_is_coming_up(): void
    {
        $this->booking('Accepted Past', now()->subDays(2)->toDateString(), BookingStatus::Accepted);

        $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertOk()->assertSee('No upcoming jobs on the schedule');
    }

    public function test_view_all_lists_upcoming_jobs_in_date_order(): void
    {
        $this->seedSchedule();

        $res = $this->actingAs($this->admin)->get(route('admin.bookings.index', ['when' => 'upcoming']))->assertOk();

        $this->assertSame(
            ['Accepted Today AM', 'Accepted Today PM', 'Quoted Future', 'Pending Future'],
            collect($res->viewData('bookings')->items())->pluck('name')->all(),
        );
    }
}
