<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f6f7fa">
    {{-- "Add to Home Screen" opens the admin full-screen, like an installed app --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SK Admin">
    <link rel="apple-touch-icon" href="/images/fav-180.png">
    <link rel="manifest" href="/admin.webmanifest">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') | SpringKleaners</title>
    <link rel="icon" type="image/png" href="/images/fav.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
{{-- Phones/tablets: the page itself scrolls (address bar collapses, momentum scrolling works) under an iOS-style
     navigation bar and tab bar. lg+: fixed app shell with the sidebar and its own content scroller. --}}
<body class="bg-canvas text-[#0f2038] antialiased text-[13px] min-h-dvh lg:h-screen lg:overflow-hidden"
      x-data="{ more: false, scrolled: false, largeTitle: false }"
      x-init="largeTitle = !!document.querySelector('#admin-content h1'); scrolled = window.scrollY > 36"
      x-on:scroll.window.passive="scrolled = window.scrollY > 36"
      x-on:keydown.escape.window="more = false" :class="more && 'overflow-hidden'">

    <div class="flex min-h-dvh lg:h-screen">
        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="hidden lg:flex w-[236px] flex-shrink-0 bg-[#081d3a] flex-col overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <img src="/images/logo-light.png" alt="SpringKleaners" width="275" height="200" class="h-14 w-auto">
                </a>
            </div>

            <nav class="flex-1 px-6 py-3 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/[0.06] text-white' : 'text-white/50 hover:bg-white/[0.04] hover:text-white/80' }}">
                    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                   class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-white/[0.06] text-white' : 'text-white/50 hover:bg-white/[0.04] hover:text-white/80' }}">
                    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Bookings &amp; Quotes
                </a>
                <a href="{{ route('admin.clients.index') }}"
                   class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-colors {{ request()->routeIs('admin.clients.*') ? 'bg-white/[0.06] text-white' : 'text-white/50 hover:bg-white/[0.04] hover:text-white/80' }}">
                    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-8.13a4 4 0 11-8 0 4 4 0 018 0zm6 8v-1a4 4 0 00-3-3.87m-2-3.13a4 4 0 010 7.75"/></svg>
                    Clients
                </a>
                <a href="{{ route('admin.expenses.index') }}"
                   class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-colors {{ request()->routeIs('admin.expenses.*') ? 'bg-white/[0.06] text-white' : 'text-white/50 hover:bg-white/[0.04] hover:text-white/80' }}">
                    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659a3 3 0 003.642 0l.879-.659M4.5 6.75h15M4.5 6.75a1.5 1.5 0 01-1.5-1.5V4.5A1.5 1.5 0 014.5 3h15a1.5 1.5 0 011.5 1.5v.75a1.5 1.5 0 01-1.5 1.5M4.5 6.75v12a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5v-12"/></svg>
                    Expenses
                </a>
                <a href="{{ route('admin.invoices.index') }}"
                   class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-colors {{ request()->routeIs('admin.invoices.*') ? 'bg-white/[0.06] text-white' : 'text-white/50 hover:bg-white/[0.04] hover:text-white/80' }}">
                    <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 15h3.75M9 18h3.75"/></svg>
                    Invoices
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.services.index') }}"
                       class="nav-link {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.service-addons.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13px] font-semibold transition-colors {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.service-addons.*') ? 'bg-white/[0.06] text-white' : 'text-white/50 hover:bg-white/[0.04] hover:text-white/80' }}">
                        <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Services
                    </a>
                @endif
            </nav>

            <div class="px-3 pb-3">
                <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); box-shadow:0 8px 24px rgba(0,0,0,0.18);">

                    {{-- Profile --}}
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-3.5 hover:bg-white/[0.04] transition-colors" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                        <div class="avatar flex-shrink-0" style="width:34px;height:34px;border-radius:10px;background:rgba(246,227,4,0.15);color:#f6e304;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div class="min-w-0">
                            <p class="text-white/40 text-[10px] uppercase tracking-wider font-semibold leading-none mb-1">Profile &middot; {{ auth()->user()->role->label() }}</p>
                            <p class="text-white font-semibold text-[13px] leading-tight truncate">{{ auth()->user()->name }}</p>
                        </div>
                    </a>

                    <div class="p-1.5">
                        @php
                            $settingsActive = request()->routeIs('admin.profile.*', 'admin.password.*', 'admin.users.*');
                        @endphp
                        <a href="{{ route('admin.profile.edit') }}"
                           class="nav-link {{ $settingsActive ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[13px] font-semibold transition-colors {{ $settingsActive ? 'bg-white/[0.08] text-white' : 'text-white/50 hover:bg-white/[0.06] hover:text-white/80' }}">
                            <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.216.456a1.125 1.125 0 01-1.37-.49l-1.296-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            User Management
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-white/50 hover:bg-white/[0.06] hover:text-white/80 text-[13px] font-semibold transition-colors">
                                <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 min-w-0 flex flex-col lg:h-screen lg:overflow-hidden">
            {{-- Phones/tablets: iOS navigation bar — back chevron, compact title once the large title scrolls away, page action --}}
            <header id="admin-navbar" class="ios-bar lg:hidden" :class="scrolled && 'is-scrolled'">
                <div class="relative flex h-11 items-center justify-between px-1">
                    <div class="flex min-w-[64px] items-center">
                        @hasSection('back')
                            <a href="@yield('back')" class="ios-back">
                                <svg viewBox="0 0 12 21" aria-hidden="true"><path d="M10.5 1.5l-9 9 9 9" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>@yield('backLabel', 'Back')</span>
                            </a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="ios-brand" aria-label="SpringKleaners admin home">
                                <img src="/images/fav-180.png" alt="" width="30" height="30">
                                <span :class="(scrolled || !largeTitle) && 'is-hidden'">SpringKleaners</span>
                            </a>
                        @endif
                    </div>
                    <p class="ios-bar-title" :class="(scrolled || !largeTitle) && 'is-visible'">
                        @hasSection('navTitle')
                            @yield('navTitle')
                        @else
                            @yield('title', 'Dashboard')
                        @endif
                    </p>
                    <div class="flex min-w-[64px] items-center justify-end">@yield('navAction')</div>
                </div>
            </header>

            {{-- Desktop topbar --}}
            <header class="hidden lg:flex flex-shrink-0 z-20 bg-white border-b border-line px-10 py-3.5 items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <h2 class="font-bold text-[14px] tracking-tight text-ink truncate">@yield('title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right leading-tight">
                        <p class="font-semibold text-[13px]">{{ auth()->user()->name }}</p>
                        <p class="text-label text-[11px]">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="avatar" style="width:34px;height:34px;border-radius:10px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                </div>
            </header>

            <div id="admin-content" class="flex-1 lg:min-h-0 lg:overflow-y-auto overscroll-contain @yield('contentPadding', 'px-4 py-5 sm:px-6 sm:py-7 lg:px-10 lg:py-9')">
                <div class="w-full h-full flex flex-col">
                    @if (session('status'))
                        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium flex items-center gap-2 flex-shrink-0">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    @hasSection('hideTabbar')
    @else
        @include('admin._mobile-tabbar')
    @endif

    {{-- Pull-to-refresh spinner for the installed home-screen app (browsers already have their own) --}}
    <div id="admin-ptr" class="ios-ptr lg:hidden" aria-hidden="true">
        <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
    </div>

    <script>
        // Pull-to-refresh when installed to the home screen: drag down from the top, release past the threshold.
        (() => {
            const standalone = navigator.standalone || window.matchMedia('(display-mode: standalone)').matches;
            if (!standalone || window.matchMedia('(min-width: 1024px)').matches) return;
            const ptr = document.getElementById('admin-ptr');
            const threshold = 64;
            let startY = null, pull = 0;
            const show = (px) => {
                ptr.classList.toggle('is-pulling', px > 4);
                ptr.classList.toggle('is-armed', px >= threshold);
                ptr.style.setProperty('--pull', px + 'px');
                ptr.style.setProperty('--spin', (px * 4) + 'deg');
            };
            window.addEventListener('touchstart', (e) => {
                startY = window.scrollY <= 0 && !document.body.classList.contains('overflow-hidden') ? e.touches[0].clientY : null;
            }, { passive: true });
            window.addEventListener('touchmove', (e) => {
                if (startY === null) return;
                pull = Math.min(Math.max(0, e.touches[0].clientY - startY) * 0.5, 96);
                show(pull);
            }, { passive: true });
            window.addEventListener('touchend', () => {
                if (startY === null) return;
                startY = null;
                if (pull >= threshold) { ptr.classList.add('is-loading'); window.location.reload(); } else { show(0); }
                pull = 0;
            });
        })();

        // Phones: swipe a list row left to reveal its actions (call / WhatsApp); tap anywhere to close.
        (() => {
            if (!window.matchMedia('(max-width: 639.98px)').matches) return;
            const reveal = 152;
            let row = null, open = null, wasOpen = false, startX = 0, startY = 0, x = 0, axis = null;
            const swallowClick = (tr) => {
                const swallow = (ev) => { ev.preventDefault(); ev.stopPropagation(); };
                tr.addEventListener('click', swallow, { capture: true, once: true });
                setTimeout(() => tr.removeEventListener('click', swallow, { capture: true }), 350);
            };
            const place = (tr, px) => tr.style.setProperty('--swipe-x', px + 'px');
            const close = (tr) => {
                if (!tr) return;
                place(tr, 0);
                tr.classList.remove('is-swiped');
                if (open === tr) open = null;
            };
            document.addEventListener('touchstart', (e) => {
                const tr = e.target.closest('tr[data-swipe]');
                if (open && open !== tr) close(open);
                row = tr && !e.target.closest('.td-swipe') ? tr : null;
                if (!row) return;
                wasOpen = row === open;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                x = row.classList.contains('is-swiped') ? -reveal : 0;
                axis = null;
            }, { passive: true });
            document.addEventListener('touchmove', (e) => {
                if (!row) return;
                const dx = e.touches[0].clientX - startX, dy = e.touches[0].clientY - startY;
                if (!axis) {
                    if (Math.abs(dx) < 8 && Math.abs(dy) < 8) return;
                    axis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
                    if (axis === 'x') row.classList.add('is-dragging');
                }
                if (axis !== 'x') return;
                x = Math.min(0, Math.max(-reveal - 40, (row.classList.contains('is-swiped') ? -reveal : 0) + dx));
                place(row, x);
            }, { passive: true });
            document.addEventListener('touchend', () => {
                if (!row) return;
                const tr = row;
                row = null;
                if (axis !== 'x') {
                    // A plain tap on an open row closes it rather than opening the booking.
                    if (!axis && wasOpen) { close(tr); swallowClick(tr); }
                    return;
                }
                tr.classList.remove('is-dragging');
                if (x < -reveal / 2) { place(tr, -reveal); tr.classList.add('is-swiped'); open = tr; } else { close(tr); }
                swallowClick(tr); // a swipe is not a tap
            });
            // While a row is open, a tap closes it rather than opening the booking.
            document.addEventListener('click', (e) => {
                if (!open || e.target.closest('.td-swipe')) return;
                if (e.target.closest('tr') === open) e.preventDefault();
                close(open);
            }, true);
        })();

        // Filter bars apply as soon as a dropdown or date changes — no separate Filter tap needed.
        document.addEventListener('change', (e) => {
            const form = e.target.closest('form.filter-bar');
            if (form && e.target.matches('select, input[type=date]')) form.requestSubmit();
        });
    </script>
    @stack('scripts')
</body>
</html>
