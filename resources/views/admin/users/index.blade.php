@extends('admin.layout')
@section('title', 'Users')
@section('content')

    @include('admin.settings._tabs')

    <div class="flex items-center justify-end mb-4">
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Invite User
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($users as $user)
                    <tr x-data="{ confirmOpen: false }">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span class="font-semibold">{{ $user->name }}</span>
                                @if ($user->is(auth()->user()))
                                    <span class="text-label text-[11px]">(you)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-muted">{{ $user->email }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11.5px] font-semibold {{ $user->role->badgeColor() }}">
                                {{ $user->role->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            @if ($user->isPending())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11.5px] font-semibold bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                    Invited &middot; {{ $user->invited_at?->diffForHumans() }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11.5px] font-semibold bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-4">
                                @if ($user->isPending())
                                    <form method="POST" action="{{ route('admin.users.resend-invite', $user) }}">
                                        @csrf
                                        <button type="submit" class="text-navy font-semibold hover:opacity-70 transition-opacity">Resend</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-navy font-semibold hover:opacity-70 transition-opacity">Edit</a>
                                @unless ($user->is(auth()->user()))
                                    <button type="button" @click="confirmOpen = true" class="text-rose-500 font-semibold hover:opacity-70 transition-opacity">
                                        {{ $user->isPending() ? 'Cancel' : 'Delete' }}
                                    </button>
                                @endunless
                            </div>
                        </td>

                        @unless ($user->is(auth()->user()))
                            <template x-teleport="body">
                                <div x-show="confirmOpen" x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                     style="background:rgba(8,29,58,0.45);"
                                     @click.self="confirmOpen = false">
                                    <div class="bg-white rounded-2xl p-6 w-full max-w-sm" style="box-shadow:0 20px 50px -12px rgba(0,0,0,0.4);">
                                        <h3 class="font-bold text-[16px] text-ink mb-2">
                                            {{ $user->isPending() ? 'Cancel this invite?' : 'Delete this user?' }}
                                        </h3>
                                        <p class="text-muted text-[13px] mb-5">
                                            @if ($user->isPending())
                                                {{ $user->email }} won't be able to use their invite link anymore.
                                            @else
                                                {{ $user->name }} will lose access to the admin panel immediately. This can't be undone.
                                            @endif
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click="confirmOpen = false" class="flex-1 px-4 py-2.5 rounded-xl text-[13px] font-semibold border border-line text-ink hover:border-navy transition-colors">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-[13px] font-semibold bg-rose-500 text-white hover:bg-rose-600 transition-colors">
                                                    {{ $user->isPending() ? 'Cancel Invite' : 'Delete User' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        @endunless
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
