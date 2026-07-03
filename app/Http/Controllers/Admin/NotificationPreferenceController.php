<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationPreferenceController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.notifications.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->user()->update([
            'notify_new_bookings' => $request->boolean('notify_new_bookings'),
        ]);

        return back()->with('status', 'Notification preferences updated.');
    }
}
