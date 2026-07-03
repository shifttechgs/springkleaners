<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function show(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function send(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        try {
            Password::sendResetLink($request->only('email'));
        } catch (Throwable $e) {
            Log::error('Failed to send password reset email', ['email' => $request->input('email'), 'error' => $e->getMessage()]);
        }

        // Always show the same message, whether or not the email exists (or the send failed) — avoids leaking which admin emails are registered.
        return back()->with('status', "If that email is registered, we've sent a password reset link to it.");
    }
}
