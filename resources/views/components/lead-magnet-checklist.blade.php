<section class="bg-[#0d1b33] section-py !py-16">
    <div class="section-wrap">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7">
                <span class="text-[#f6e304] text-[11px] font-semibold uppercase tracking-[0.18em]">Free Download</span>
                <h2 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight mt-3 mb-3">
                    The End-of-Tenancy Deposit-Back Checklist
                </h2>
                <p class="text-white/50 text-[14px] leading-relaxed max-w-lg">
                    Everything letting agents and landlords actually check during a move-out inspection — kitchen, bathrooms, walls, windows and the final steps before handing back your keys. Free PDF, no obligation to book.
                </p>
            </div>
            <div class="lg:col-span-5">
                <form method="POST" action="{{ route('lead-magnets.deposit-back-checklist') }}" class="bg-white rounded-2xl p-6 space-y-3">
                    @csrf
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required
                           class="w-full bg-[#f8f9fc] border border-gray-200 rounded-xl px-4 py-3 text-[#081d3a] text-[14px] placeholder-[#081d3a]/40 focus:border-[#f6e304] focus:outline-none transition-colors">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Your email" required
                           class="w-full bg-[#f8f9fc] border border-gray-200 rounded-xl px-4 py-3 text-[#081d3a] text-[14px] placeholder-[#081d3a]/40 focus:border-[#f6e304] focus:outline-none transition-colors">
                    @error('email')
                    <p class="text-rose-500 text-[12px]">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="w-full bg-[#f6e304] text-[#081d3a] font-bold py-3.5 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[14px]">
                        Get the Free Checklist
                    </button>
                    <p class="text-center text-[#647082] text-[11px]">Your download starts immediately — we'll email you a copy too. No spam, unsubscribe anytime.</p>
                </form>
            </div>
        </div>
    </div>
</section>
