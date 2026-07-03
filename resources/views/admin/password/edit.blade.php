@extends('admin.layout')
@section('title', 'Change Password')
@section('content')

    @include('admin.settings._tabs')

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Current password</p>
                    <p class="text-muted text-[12.5px] mt-1">Confirm it's really you before setting a new one.</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="password" name="current_password" required autofocus
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">New password</p>
                    <p class="text-muted text-[12.5px] mt-1">At least 10 characters.</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="password" name="password" required minlength="10"
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Confirm new password</p>
                    <p class="text-muted text-[12.5px] mt-1">Re-type the password above.</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="password" name="password_confirmation" required minlength="10"
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="px-6 py-4 flex justify-end bg-canvas">
                <button type="submit" class="btn-primary">Update Password</button>
            </div>
        </form>
    </div>

@endsection
