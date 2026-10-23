<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = Client::query()
            ->withCount('bookings')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->query('search');
                $q->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('phone_raw', 'like', "%{$search}%"));
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.clients.index', [
            'clients' => $clients,
        ]);
    }

    /**
     * Type-ahead for the admin quote builder: matching clients (or the most recent ones when the
     * query is empty), with address details from their latest booking so a repeat quote fills itself.
     */
    public function search(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));
        $digits = preg_replace('/\D+/', '', $search);

        $clients = Client::query()
            ->with(['bookings' => fn ($q) => $q->latest('date')->limit(1)])
            ->when($search !== '', function ($q) use ($search, $digits) {
                $q->where(function ($q) use ($search, $digits) {
                    $q->where('name', 'like', "%{$search}%")->orWhere('phone_raw', 'like', "%{$search}%");
                    if (strlen($digits) >= 3) {
                        $q->orWhere('phone_normalized', 'like', '%'.ltrim($digits, '0').'%');
                    }
                });
            })
            ->orderByDesc('updated_at')
            ->limit(6)
            ->get();

        return response()->json($clients->map(function (Client $client) {
            $last = $client->bookings->first();

            return [
                'id' => $client->id,
                'name' => $client->name,
                'phone' => $client->phone_raw,
                'suburb' => $client->suburb ?? $last?->suburb,
                'address' => $last?->address,
                'access_instructions' => $last?->access_instructions,
                'parking' => $last?->parking,
                'last_booking' => $last?->date?->format('d M Y'),
            ];
        })->values());
    }

    public function show(Client $client): View
    {
        $bookings = $client->bookings()->orderByDesc('date')->get();

        return view('admin.clients.show', [
            'client' => $client,
            'bookings' => $bookings,
        ]);
    }
}
