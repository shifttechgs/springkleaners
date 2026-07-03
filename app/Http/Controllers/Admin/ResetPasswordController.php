<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function show(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:10|confirmed',
        ]);

        $status = Password::reset($credentials, function ($user) use ($credentials) {
            $user->forceFill(['password' => $credentials['password']])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password reset — sign in with your new password.');
        }

        return back()->withErrors(['email' => __($status)])->onlyInput('email');
    }
}
