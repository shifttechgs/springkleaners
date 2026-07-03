<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | SpringKleaners</title>
    <link rel="icon" type="image/png" href="/images/fav.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#081d3a',
                        'navy-deep': '#040f1f',
                        gold: '#f6e304',
                        muted: '#647082',
                        ink: '#0f2038',
                        line: '#e8eaf0',
                        label: '#8a94a6',
                        canvas: '#f6f7fa',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-font-smoothing: antialiased; }
        [x-cloak] { display: none !important; }
        .card { background: #fff; border: 1px solid #e8eaf0; border-radius: 16px; box-shadow: 0 1px 2px rgba(8,29,58,0.04); }
        .nav-link { position: relative; }
        .nav-link.active::before {
            content: ''; position: absolute; left: -16px; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px; border-radius: 2px; background: #f6e304;
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: #081d3a; color: #fff; font-weight: 600; font-size: 13px;
            padding: 10px 18px; border-radius: 10px; transition: all .15s;
        }
        .btn-primary:hover { background: #0d2a4a; }
        .btn-gold {
            display: inline-flex; align-items: center; gap: 8px;
            background: #f6e304; color: #081d3a; font-weight: 700; font-size: 13px;
            padding: 10px 18px; border-radius: 10px; transition: all .15s;
        }
        .btn-gold:hover { background: #fde047; }
        table.data-table th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #8a94a6; font-weight: 600; text-align: left; }
        table.data-table tbody tr { transition: background-color .1s; }
        table.data-table tbody tr:hover { background: #fafbfc; }
        .badge-dot { width: 6px; height: 6px; border-radius: 999px; flex-shrink: 0; }
        .avatar {
            width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            background: #081d3a0d; color: #081d3a; font-weight: 700; font-size: 12px;
        }
        .card-interactive {
            transition: transform .18s cubic-bezier(.16,1,.3,1), box-shadow .18s cubic-bezier(.16,1,.3,1), border-color .18s ease;
        }
        .card-interactive:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(8,29,58,0.08);
            border-color: #cdd5e0;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp .45s cubic-bezier(.16,1,.3,1) both;
        }
        .animate-in.delay-1 { animation-delay: .04s; }
        .animate-in.delay-2 { animation-delay: .08s; }
        .animate-in.delay-3 { animation-delay: .12s; }
        .animate-in.delay-4 { animation-delay: .16s; }
    </style>
    @stack('styles')
</head>
<body class="bg-canvas text-[#0f2038] antialiased text-[13px] h-screen overflow-hidden">

    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-[236px] flex-shrink-0 bg-[#081d3a] flex flex-col overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-6 py-6">
                <div class="w-8 h-8 rounded-lg bg-[#f6e304] flex items-center justify-center flex-shrink-0">
                    <span class="text-[#081d3a] font-black text-[13px] tracking-tight leading-none">SK</span>
                </div>
                <span class="font-extrabold text-[16px] tracking-tight leading-none">
                    <span class="text-white">Spring</span><span class="text-[#f6e304]">Kleaners</span>
                </span>
            </a>

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
        <main class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden">
            {{-- Topbar --}}
            <header class="flex-shrink-0 z-10 bg-white/90 border-b border-line px-10 py-3.5 flex items-center justify-between" style="backdrop-filter: blur(8px);">
                <h2 class="font-bold text-[14px] tracking-tight text-ink">@yield('title', 'Dashboard')</h2>
                <div class="flex items-center gap-3">
                    <div class="text-right leading-tight">
                        <p class="font-semibold text-[13px]">{{ auth()->user()->name }}</p>
                        <p class="text-label text-[11px]">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="avatar" style="width:34px;height:34px;border-radius:10px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                </div>
            </header>

            <div class="flex-1 min-h-0 overflow-y-auto @yield('contentPadding', 'px-10 py-9')">
                <div class="max-w-6xl mx-auto h-full flex flex-col">
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

    @stack('scripts')
</body>
</html>
