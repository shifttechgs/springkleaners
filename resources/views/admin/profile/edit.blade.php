@extends('admin.layout')
@section('title', 'Profile')
@section('content')

    @include('admin.settings._tabs')

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card overflow-hidden">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-line">
            <div class="avatar" style="width:44px;height:44px;border-radius:14px;font-size:16px;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <p class="font-bold text-[15px] leading-tight">{{ $user->name }}</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold mt-1 {{ $user->role->badgeColor() }}">
                    {{ $user->role->label() }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Full name</p>
                    <p class="text-muted text-[12.5px] mt-1">Shown across the admin panel and to clients on quote PDFs.</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-1">
                    <p class="font-semibold text-ink text-[13.5px]">Email address</p>
                    <p class="text-muted text-[12.5px] mt-1">Used to sign in and to receive password reset links.</p>
                </div>
                <div class="lg:col-span-2">
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full max-w-md bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="px-6 py-4 flex justify-end bg-canvas">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

@endsection
