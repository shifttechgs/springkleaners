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

        // Past and completed jobs can still appear in the calendar's day tooltips; declined/expired never do.
        $res->assertSee('Pending Future')->assertSee('Today')
            ->assertDontSee('Declined Future')->assertDontSee('Expired Future')
            ->assertDontSee('No upcoming jobs on the schedule');
    }

    public function test_dashboard_shows_an_empty_state_when_nothing_is_coming_up(): void
    {
        $this->booking('Accepted Past', now()->subDays(2)->toDateString(), BookingStatus::Accepted);

        $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertOk()->assertSee('No upcoming jobs on the schedule');
    }

    public function test_calendar_marks_upcoming_jobs_by_slot_with_details(): void
    {
        $this->travelTo(now()->startOfMonth()->addDays(1)); // keep $day inside the month being viewed
        $day = now()->addDays(3)->toDateString();

        $this->booking('Morning Client', $day, BookingStatus::Accepted, Booking::MORNING_SLOT);
        $this->booking('Afternoon Client', $day, BookingStatus::Pending, Booking::AFTERNOON_SLOT);
        $this->booking('Gone Client', $day, BookingStatus::Declined, Booking::AFTERNOON_SLOT);

        $res = $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertOk();
        $cell = $res->viewData('calendarDays')[$day];

        $this->assertTrue($cell['upcoming']);
        $this->assertFalse($cell['allConfirmed'], 'One job is still awaiting');
        $this->assertSame(['confirmed', 'waiting'], [$cell['slots']['am']['tone'], $cell['slots']['pm']['tone']]);
        $this->assertSame(['Morning Client', 'Afternoon Client'], array_column($cell['jobs'], 'name'), 'Declined bookings are left off');

        $res->assertSee('Morning Client · Accepted', false)->assertSee('Next job:');
    }

    public function test_calendar_month_switching_is_safe(): void
    {
        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-01-31 10:00'));

        // On the 31st, asking for February must show February (not overflow into March).
        $this->actingAs($this->admin)->get(route('admin.dashboard', ['month' => '2026-02']))->assertOk()->assertSee('February 2026');

        // Bad input falls back to the current month instead of an error.
        $this->actingAs($this->admin)->get(route('admin.dashboard', ['month' => 'nonsense']))->assertOk()->assertSee('January 2026');
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
