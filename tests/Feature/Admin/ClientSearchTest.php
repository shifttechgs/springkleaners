<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientSearchTest extends TestCase
{
    use RefreshDatabase;

    private function client(string $name, string $phone, string $normalized): Client
    {
        return Client::create(['name' => $name, 'phone_raw' => $phone, 'phone_normalized' => $normalized, 'suburb' => 'Milnerton']);
    }

    private function booking(Client $client, string $date, string $address): Booking
    {
        return Booking::create([
            'client_id' => $client->id,
            'service' => 'deep-cleaning',
            'date' => $date,
            'time' => '9:00 AM',
            'name' => $client->name,
            'phone' => $client->phone_raw,
            'address' => $address,
            'access_instructions' => 'Gate code 1234',
            'parking' => 'Street',
            'total' => 850,
        ]);
    }

    public function test_guests_cannot_search_clients(): void
    {
        $this->getJson(route('admin.clients.search', ['q' => 'jane']))->assertUnauthorized();
    }

    public function test_finds_clients_by_name_and_fills_details_from_their_latest_booking(): void
    {
        $jane = $this->client('Jane Client', '082 123 4567', '27821234567');
        $this->booking($jane, '2026-01-10', '1 Old Road');
        $this->booking($jane, '2026-06-10', '22 New Street');
        $this->client('Other Person', '083 000 0000', '27830000000');

        $this->actingAs(User::factory()->create(['role' => UserRole::Admin]))
            ->getJson(route('admin.clients.search', ['q' => 'jane']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.name', 'Jane Client')
            ->assertJsonPath('0.phone', '082 123 4567')
            ->assertJsonPath('0.address', '22 New Street')
            ->assertJsonPath('0.access_instructions', 'Gate code 1234')
            ->assertJsonPath('0.suburb', 'Milnerton');
    }

    public function test_finds_clients_by_phone_digits_in_any_format(): void
    {
        $this->client('Jane Client', '082 123 4567', '27821234567');

        $this->actingAs(User::factory()->create(['role' => UserRole::Admin]))
            ->getJson(route('admin.clients.search', ['q' => '0821234']))
            ->assertOk()
            ->assertJsonPath('0.name', 'Jane Client');
    }

    public function test_empty_query_returns_recent_clients_capped_at_six(): void
    {
        foreach (range(1, 8) as $i) {
            $this->client("Client {$i}", "08200000{$i}", "2782000000{$i}");
        }

        $this->actingAs(User::factory()->create(['role' => UserRole::Admin]))
            ->getJson(route('admin.clients.search'))
            ->assertOk()
            ->assertJsonCount(6);
    }
}
