@php
    $settingsTabs = [
        ['route' => 'admin.profile.edit', 'active' => 'admin.profile.*', 'label' => 'Profile'],
        ['route' => 'admin.password.edit', 'active' => 'admin.password.*', 'label' => 'Security'],
        ['route' => 'admin.notifications.edit', 'active' => 'admin.notifications.*', 'label' => 'Notifications'],
        ['route' => 'admin.fuel-settings.edit', 'active' => 'admin.fuel-settings.*', 'label' => 'Fuel Settings'],
        ['route' => 'admin.company-settings.edit', 'active' => 'admin.company-settings.*', 'label' => 'Business Details'],
    ];

    if (auth()->user()->isAdmin()) {
        $settingsTabs[] = ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Users'];
    }
@endphp

<div class="mb-6">
    <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">User Management</p>
    <h1 class="text-[22px] sm:text-[26px] font-extrabold tracking-tight leading-none mb-5">Account &amp; Access</h1>

    {{-- Scrolls sideways on phones instead of pushing the page wider --}}
    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
    <div class="flex w-max min-w-full items-center gap-1 border-b border-line">
        @foreach ($settingsTabs as $tab)
            <a href="{{ route($tab['route']) }}"
               class="whitespace-nowrap px-3 sm:px-4 py-2.5 text-[13px] font-semibold border-b-2 -mb-px transition-colors {{ request()->routeIs($tab['active']) ? 'border-navy text-navy' : 'border-transparent text-muted hover:text-ink' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
    </div>
</div>
