<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\UserInvitationMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $user = User::create([
            ...$data,
            'invited_at' => now(),
            'invited_by' => $request->user()->id,
        ]);

        if ($this->sendInvite($user, $request->user()->name)) {
            return redirect()->route('admin.users.index')->with('status', "Invite sent to {$user->email}.");
        }

        return redirect()->route('admin.users.index')->with(
            'status',
            "{$user->name}'s account was created, but the invite email couldn't be sent. Use \"Resend\" once your mail settings are fixed."
        );
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'targetUser' => $user,
            'roles' => UserRole::cases(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        if ($user->is($request->user()) && $data['role'] !== UserRole::Admin->value && $this->isLastAdmin($user)) {
            return back()->withErrors(['role' => 'You can\'t remove your own admin role — you\'re the last admin.'])->withInput();
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function resendInvite(Request $request, User $user): RedirectResponse
    {
        abort_if(! $user->isPending(), 404);

        $user->update(['invited_at' => now()]);

        if ($this->sendInvite($user, $request->user()->name)) {
            return back()->with('status', "Invite re-sent to {$user->email}.");
        }

        return back()->withErrors(['user' => 'Could not send the invite email — check the mail settings and try again.']);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'You can\'t delete your own account.']);
        }

        if ($this->isLastAdmin($user)) {
            return back()->withErrors(['user' => 'You can\'t delete the last remaining admin.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', $user->isPending() ? 'Invite cancelled.' : 'User removed.');
    }

    private function sendInvite(User $user, string $inviterName): bool
    {
        $url = URL::temporarySignedRoute('admin.invite.accept', now()->addDays(7), ['user' => $user->id]);

        try {
            Mail::to($user->email)->send(new UserInvitationMail(
                inviteeName: $user->name,
                inviterName: $inviterName,
                role: $user->role->label(),
                inviteUrl: $url,
            ));

            return true;
        } catch (Throwable $e) {
            Log::error('Failed to send user invitation email', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            return false;
        }
    }

    private function isLastAdmin(User $user): bool
    {
        return $user->isAdmin() && User::where('role', UserRole::Admin->value)->count() <= 1;
    }
}
