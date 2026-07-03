<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
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

    public function show(Client $client): View
    {
        $bookings = $client->bookings()->orderByDesc('date')->get();

        return view('admin.clients.show', [
            'client' => $client,
            'bookings' => $bookings,
        ]);
    }
}
