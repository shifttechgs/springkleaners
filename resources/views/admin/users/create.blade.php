@extends('admin.layout')
@section('title', 'Invite User')
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

    <div class="card overflow-hidden">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Full name</p>
                    <p class="text-muted text-[12.5px] mt-1">How they'll appear across the admin panel.</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Email address</p>
                    <p class="text-muted text-[12.5px] mt-1">Where we'll send the invite link.</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="email" name="email" value="{{ old('email') }}" required
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
                            <option value="{{ $role->value }}" @selected(old('role') === $role->value)>{{ $role->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-between bg-canvas">
                <p class="text-label text-[12px]">They'll get an email with a link to set their own password.</p>
                <button type="submit" class="btn-primary">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Send Invite
                </button>
            </div>
        </form>
    </div>

@endsection
