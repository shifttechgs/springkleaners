<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\ServiceController;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesScreenTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function addAddons(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            ServiceAddon::create(['key' => 'extra-'.$i, 'label' => 'Extra add-on '.$i, 'price' => 10, 'sort_order' => 100 + $i]);
        }
    }

    public function test_both_lists_are_paginated_independently(): void
    {
        $this->assertGreaterThan(ServiceController::PER_PAGE, Service::count(), 'Seeded services span more than one page');
        $this->addAddons(ServiceController::PER_PAGE);

        $res = $this->actingAs($this->admin())->get('/admin/services?services_page=2&addons_page=2')->assertOk();

        $services = $res->viewData('services');
        $addons = $res->viewData('addons');

        $this->assertSame(2, $services->currentPage());
        $this->assertSame(2, $addons->currentPage());
        $this->assertCount(Service::count() - ServiceController::PER_PAGE, $services->items());
        $this->assertCount(ServiceAddon::count() - ServiceController::PER_PAGE, $addons->items());

        // Each list's links keep the other list's page.
        $this->assertStringContainsString('addons_page=2', $services->url(1));
        $this->assertStringContainsString('services_page=2', $addons->url(1));
    }

    public function test_services_and_addons_are_on_separate_tabs(): void
    {
        $admin = $this->admin();

        $main = $this->actingAs($admin)->get('/admin/services')->assertOk();
        $main->assertSee('Deep Cleaning')->assertDontSee('Windows (inside &amp; out)', false)->assertSee('Add Service');
        $this->assertSame('services', $main->viewData('tab'));

        $extras = $this->actingAs($admin)->get('/admin/services?tab=addons')->assertOk();
        $extras->assertSee('Windows (inside &amp; out)', false)->assertDontSee('deep-cleaning')->assertSee('Add Add-on');
        $this->assertSame('addons', $extras->viewData('tab'));

        // Paging inside the add-ons tab stays on the add-ons tab.
        $this->addAddons(ServiceController::PER_PAGE);
        $this->assertStringContainsString('tab=addons', $this->actingAs($admin)->get('/admin/services?tab=addons')->viewData('addons')->url(2));
    }

    public function test_saving_an_addon_returns_to_the_addons_tab(): void
    {
        $addon = ServiceAddon::where('key', 'balcony')->sole();

        $this->actingAs($this->admin())
            ->put(route('admin.service-addons.update', $addon), ['key' => 'balcony', 'label' => 'Balcony / patio', 'price' => 175, 'sort_order' => $addon->sort_order])
            ->assertRedirect(route('admin.services.index', ['tab' => 'addons']));

        $this->assertEquals(175, $addon->fresh()->price);
    }

    public function test_every_row_is_rendered_across_the_pages(): void
    {
        $admin = $this->admin();
        $names = Service::pluck('name');
        $html = '';

        for ($page = 1; $page <= (int) ceil($names->count() / ServiceController::PER_PAGE); $page++) {
            $html .= $this->actingAs($admin)->get('/admin/services?services_page='.$page)->assertOk()->getContent();
        }

        foreach ($names as $name) {
            $this->assertStringContainsString(e($name), $html);
        }
    }

    public function test_a_page_past_the_end_goes_to_the_last_page(): void
    {
        $last = (int) ceil(Service::count() / ServiceController::PER_PAGE);

        $this->actingAs($this->admin())
            ->get('/admin/services?services_page=99&addons_page=5')
            ->assertRedirect(route('admin.services.index', ['services_page' => $last, 'addons_page' => 1]));
    }
}
