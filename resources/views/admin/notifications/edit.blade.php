@extends('admin.layout')
@section('title', 'Notifications')
@section('content')

    @include('admin.settings._tabs')

    @if (session('status'))
        <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <form method="POST" action="{{ route('admin.notifications.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">New booking alerts</p>
                    <p class="text-muted text-[12.5px] mt-1 max-w-lg">
                        Get notified the moment a new booking request comes in from the website, so you can quote it quickly.
                        Delivered to <strong class="text-ink">{{ $user->email }}</strong> for now — real-time WhatsApp push
                        needs a WhatsApp Business API connection (e.g. Twilio), which isn't set up yet. Ask to have that added
                        if you'd rather get these on WhatsApp.
                    </p>
                </div>
                <div class="lg:col-span-1 flex lg:justify-end items-start">
                    <button type="submit" name="notify_new_bookings" value="{{ $user->notify_new_bookings ? '0' : '1' }}"
                            class="w-12 h-7 rounded-full relative transition-colors flex-shrink-0 border-0 cursor-pointer"
                            style="background: {{ $user->notify_new_bookings ? '#081d3a' : '#dbe0ea' }};">
                        <span class="absolute top-0.5 w-6 h-6 bg-white rounded-full shadow transition-all" style="left: {{ $user->notify_new_bookings ? '22px' : '2px' }};"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection
