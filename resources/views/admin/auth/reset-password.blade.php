<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Reset Password | SpringKleaners</title>
    <link rel="icon" type="image/png" href="/images/fav.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body class="min-h-screen flex items-center justify-center px-4" style="background: radial-gradient(120% 120% at 50% -10%, #0d2a4a 0%, #081d3a 45%, #040f1f 100%);">
    <div class="absolute inset-0 bg-grid pointer-events-none"></div>

    <div class="w-full max-w-[380px] relative z-10">
        <div class="flex items-center justify-center gap-2.5 mb-8">
            <div class="w-9 h-9 rounded-xl bg-[#f6e304] flex items-center justify-center flex-shrink-0">
                <span class="text-[#081d3a] font-black text-[14px] tracking-tight leading-none">SK</span>
            </div>
            <span class="font-extrabold text-[19px] tracking-tight leading-none">
                <span class="text-white">Spring</span><span class="text-[#f6e304]">Kleaners</span>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-8" style="box-shadow: 0 20px 50px -12px rgba(0,0,0,0.4);">
            <p class="text-[#8a94a6] text-[11px] uppercase tracking-wider font-semibold mb-1.5">Admin</p>
            <h1 class="text-[#081d3a] font-extrabold text-[20px] tracking-tight mb-6">Set a new password</h1>

            @if ($errors->any())
                <div class="mb-5 px-3.5 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="block text-[#8a94a6] text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                           class="w-full bg-white border border-[#e8eaf0] rounded-xl px-4 py-3 text-[#081d3a] text-[14px] focus:border-[#081d3a] focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-[#8a94a6] text-[11px] uppercase tracking-wider mb-1.5 font-semibold">New Password</label>
                    <input type="password" name="password" required minlength="10"
                           class="w-full bg-white border border-[#e8eaf0] rounded-xl px-4 py-3 text-[#081d3a] text-[14px] focus:border-[#081d3a] focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-[#8a94a6] text-[11px] uppercase tracking-wider mb-1.5 font-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" required minlength="10"
                           class="w-full bg-white border border-[#e8eaf0] rounded-xl px-4 py-3 text-[#081d3a] text-[14px] focus:border-[#081d3a] focus:outline-none transition-colors">
                </div>
                <button type="submit"
                        class="w-full bg-[#081d3a] text-white font-bold py-3.5 rounded-xl hover:bg-[#0d2a4a] active:scale-[0.98] transition-all text-[14px]">
                    Reset Password
                </button>
            </form>
        </div>

        <p class="text-center text-white/30 text-[12px] mt-6">
            <a href="{{ route('login') }}" class="hover:text-[#f6e304] transition-colors">&larr; Back to sign in</a>
        </p>
    </div>
</body>
</html>
