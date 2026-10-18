<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login | SpringKleaners</title>
    <link rel="icon" type="image/png" href="/images/fav.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body class="min-h-screen flex items-center justify-center px-4 bg-[#081d3a]">

    <div class="w-full max-w-[380px] relative z-10">
        <div class="flex justify-center mb-8">
            <img src="/images/logo-light.png" alt="SpringKleaners" width="275" height="200" class="h-20 w-auto">
        </div>

        <div class="bg-white rounded-2xl p-8" style="box-shadow: 0 20px 50px -12px rgba(0,0,0,0.4);">
            <p class="text-[#8a94a6] text-[11px] uppercase tracking-wider font-semibold mb-1.5">Admin</p>
            <h1 class="text-[#081d3a] font-extrabold text-[20px] tracking-tight mb-6">Sign in to continue</h1>

            @if ($errors->any())
                <div class="mb-5 px-3.5 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[#8a94a6] text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-white border border-[#e8eaf0] rounded-xl px-4 py-3 text-[#081d3a] text-[14px] focus:border-[#081d3a] focus:outline-none transition-colors">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[#8a94a6] text-[11px] uppercase tracking-wider font-semibold">Password</label>
                        <a href="{{ route('password.request') }}" class="text-[#081d3a] text-[11.5px] font-semibold hover:opacity-70 transition-opacity">Forgot password?</a>
                    </div>
                    <input type="password" name="password" required
                           class="w-full bg-white border border-[#e8eaf0] rounded-xl px-4 py-3 text-[#081d3a] text-[14px] focus:border-[#081d3a] focus:outline-none transition-colors">
                </div>
                <label class="flex items-center gap-2 text-[#647082] text-[13px]">
                    <input type="checkbox" name="remember" class="rounded border-[#e8eaf0]">
                    Remember me
                </label>
                <button type="submit"
                        class="w-full bg-[#081d3a] text-white font-bold py-3.5 rounded-xl hover:bg-[#0d2a4a] active:scale-[0.98] transition-all text-[14px]">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-white/30 text-[12px] mt-6">
            <a href="/" class="hover:text-[#f6e304] transition-colors">&larr; Back to site</a>
        </p>
    </div>
</body>
</html>
