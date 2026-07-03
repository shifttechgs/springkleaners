<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function accept(User $user): View|RedirectResponse
    {
        if (! $user->isPending()) {
            return redirect()->route('login')->with('status', 'That invite has already been used — sign in below.');
        }

        return view('admin.auth.accept-invite', ['invitee' => $user]);
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        abort_if(! $user->isPending(), 404);

        $data = $request->validate([
            'password' => 'required|string|min:10|confirmed',
        ]);

        $user->forceFill(['password' => $data['password']])->save();

        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('status', 'Welcome to SpringKleaners — your account is ready.');
    }
}
