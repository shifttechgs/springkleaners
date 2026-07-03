@extends('admin.layout')
@section('title', 'Edit User')
@section('content')

    @include('admin.settings._tabs')

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-4">
        <a href="{{ route('admin.users.index') }}" class="hover:text-navy transition-colors">&larr; Back to Users</a>
    </div>

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card overflow-hidden mb-6">
        <form method="POST" action="{{ route('admin.users.update', $targetUser) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Full name</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="text" name="name" value="{{ old('name', $targetUser->name) }}" required autofocus
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Email address</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="email" name="email" value="{{ old('email', $targetUser->email) }}" required
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Role</p>
                    <p class="text-muted text-[12.5px] mt-1">Admins can manage users; Staff can't.</p>
                </div>
                <div class="lg:col-span-2">
                    <select name="role" class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                        @foreach ($roles as $role)
                            <option value="{{ $role->value }}" @selected(old('role', $targetUser->role->value) === $role->value)>{{ $role->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 flex justify-end bg-canvas">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    @if ($targetUser->isPending())
        <div class="card overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Invite pending</p>
                    <p class="text-muted text-[12.5px] mt-1">{{ $targetUser->name }} hasn't set a password yet — invited {{ $targetUser->invited_at?->diffForHumans() }}.</p>
                </div>
                <div class="lg:col-span-1 flex lg:justify-end">
                    <form method="POST" action="{{ route('admin.users.resend-invite', $targetUser) }}" class="w-full lg:w-auto">
                        @csrf
                        <button type="submit" class="btn-gold w-full justify-center">Resend Invite Email</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <p class="text-label text-[12.5px]">
            {{ $targetUser->name }} can reset their own password anytime via "Forgot password?" on the sign-in screen.
        </p>
    @endif

@endsection
