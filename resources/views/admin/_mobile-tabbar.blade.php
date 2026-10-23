{{-- Phones/tablets: navy iOS-style tab bar — Home, Bookings, a raised "New Quote" button, Invoices, More —
     plus a "More" sheet for the less-used sections. Desktop uses the sidebar. --}}
@php
    $moreActive = request()->routeIs('admin.clients.*', 'admin.expenses.*', 'admin.services.*', 'admin.service-addons.*', 'admin.profile.*', 'admin.password.*', 'admin.users.*', 'admin.notifications.*', 'admin.company-settings.*', 'admin.fuel-settings.*');
    $tabs = [
        ['admin.dashboard', 'Home', request()->routeIs('admin.dashboard'), 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['admin.bookings.index', 'Bookings', request()->routeIs('admin.bookings.*'), 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['admin.invoices.index', 'Invoices', request()->routeIs('admin.invoices.*'), 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
    ];
    $moreLinks = [
        ['admin.clients.index', 'Clients', 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-8.13a4 4 0 11-8 0 4 4 0 018 0zm6 8v-1a4 4 0 00-3-3.87m-2-3.13a4 4 0 010 7.75'],
        ['admin.expenses.index', 'Expenses', 'M12 6v12m-3-2.818l.879.659a3 3 0 003.642 0l.879-.659M4.5 6.75h15M4.5 6.75a1.5 1.5 0 01-1.5-1.5V4.5A1.5 1.5 0 014.5 3h15a1.5 1.5 0 011.5 1.5v.75a1.5 1.5 0 01-1.5 1.5M4.5 6.75v12a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5v-12'],
    ];
    if (auth()->user()->isAdmin()) {
        $moreLinks[] = ['admin.services.index', 'Services & Pricing', 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z M6 6h.008v.008H6V6z'];
    }
    $moreLinks[] = ['admin.profile.edit', 'Settings', 'M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z M15 12a3 3 0 11-6 0 3 3 0 016 0z'];
@endphp

<nav id="admin-tabbar" class="ios-tabbar lg:hidden" aria-label="Main">
    <div class="flex">
        @foreach ($tabs as $i => [$route, $label, $active, $icon])
            @if ($i === 2)
                <a href="{{ route('admin.bookings.create') }}" class="ios-tab ios-tab-create {{ request()->routeIs('admin.bookings.create') ? 'is-active' : '' }}" aria-label="New quote">
                    <span class="ios-tab-create-btn" aria-hidden="true">
                        <svg fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                    </span>
                    New Quote
                </a>
            @endif
            @php $active = $active && ! request()->routeIs('admin.bookings.create'); @endphp
            <a href="{{ route($route) }}" class="ios-tab {{ $active ? 'is-active' : '' }}" @if ($active) aria-current="page" @endif>
                <svg fill="none" stroke="currentColor" stroke-width="{{ $active ? '2' : '1.6' }}" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            </a>
        @endforeach
        <button type="button" class="ios-tab {{ $moreActive ? 'is-active' : '' }}" x-on:click="more = true" aria-haspopup="dialog" :aria-expanded="more">
            <svg fill="none" stroke="currentColor" stroke-width="{{ $moreActive ? '2' : '1.6' }}" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            More
        </button>
    </div>
</nav>

{{-- More sheet --}}
<div x-show="more" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true" aria-label="More">
    <div x-show="more" x-transition.opacity.duration.250ms class="absolute inset-0 bg-black/30" x-on:click="more = false"></div>
    <div x-show="more"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
         class="ios-sheet absolute inset-x-0 bottom-0">
        <div class="mx-auto mt-2 mb-1 h-[5px] w-9 rounded-full bg-[#c7c9d1]" aria-hidden="true"></div>
        <div class="flex items-center justify-between px-4 pt-1 pb-3">
            <p class="text-[17px] font-semibold text-ink">More</p>
            <button type="button" class="ios-text-btn font-semibold" x-on:click="more = false">Done</button>
        </div>

        <a href="{{ route('admin.profile.edit') }}" class="ios-group mx-4 mb-5 flex items-center gap-3 px-4 py-3">
            <div class="avatar" style="width:44px;height:44px;border-radius:999px;font-size:17px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="min-w-0 flex-1">
                <p class="text-[17px] font-semibold text-ink truncate">{{ auth()->user()->name }}</p>
                <p class="text-[13px] text-label truncate">{{ auth()->user()->email }} &middot; {{ auth()->user()->role->label() }}</p>
            </div>
            <svg class="ios-chevron" viewBox="0 0 8 14" aria-hidden="true"><path d="M1 1l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>

        <div class="ios-group mx-4 mb-5">
            @foreach ($moreLinks as [$route, $label, $icon])
                <a href="{{ route($route) }}" class="ios-cell">
                    <span class="ios-cell-icon"><svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg></span>
                    <span class="flex-1">{{ $label }}</span>
                    <svg class="ios-chevron" viewBox="0 0 8 14" aria-hidden="true"><path d="M1 1l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            @endforeach
        </div>

        <form method="POST" action="{{ route('logout') }}" class="ios-group mx-4">
            @csrf
            <button type="submit" class="ios-cell ios-cell-destructive w-full justify-center">Sign Out</button>
        </form>
    </div>
</div>
