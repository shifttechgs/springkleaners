<nav id="mainNav" class="fixed top-3 sm:top-4 inset-x-3 sm:inset-x-4 lg:inset-x-6 z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto rounded-2xl border border-white/10 shadow-2xl px-4 sm:px-5 lg:px-6" style="background: rgba(13,27,51,0.55); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%);">
        <div class="grid grid-cols-2 lg:grid-cols-3 items-center h-16">
            <a href="/" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl bg-[#f6e304] flex items-center justify-center flex-shrink-0 shadow-md">
                    <span class="text-[#081d3a] font-black text-[14px] tracking-tight leading-none">SK</span>
                </div>
                <span class="font-extrabold text-[20px] tracking-tight leading-none">
                    <span class="text-white">Spring</span><span class="text-[#f6e304]">Kleaners</span>
                </span>
            </a>

            <div class="hidden lg:flex items-center justify-center gap-8">
                <div class="relative" x-data="{ servicesOpen: false }" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                    <a href="/#services" class="flex items-center gap-1 text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">
                        Services
                        <svg class="w-3 h-3 transition-transform duration-200" :class="servicesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div x-show="servicesOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 pt-3 w-64"
                         style="display:none;">
                        <div class="rounded-2xl border border-black/5 overflow-hidden p-2" style="background: rgba(255,255,255,0.96); backdrop-filter: blur(24px) saturate(180%); -webkit-backdrop-filter: blur(24px) saturate(180%); box-shadow: 0 20px 50px -12px rgba(8,29,58,0.28);">
                            <a href="{{ route('services.show', 'deep-cleaning') }}" class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                Deep Cleaning
                            </a>
                            <a href="{{ route('services.show', 'end-of-tenancy') }}" class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                End-of-Tenancy Cleaning
                            </a>
                            <a href="{{ route('services.show', 'post-construction') }}" class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                Post Construction Cleaning
                            </a>
                            <div class="border-t border-black/5 mt-1.5 pt-1.5">
                                <a href="/#services" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[#081d3a]/40 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[12px] font-semibold tracking-tight transition-colors">
                                    View all services
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative" x-data="{ areasOpen: false }" @mouseenter="areasOpen = true" @mouseleave="areasOpen = false">
                    <a href="/#areas" class="flex items-center gap-1 text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">
                        Areas
                        <svg class="w-3 h-3 transition-transform duration-200" :class="areasOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div x-show="areasOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 pt-3 w-80"
                         style="display:none;">
                        <div class="rounded-2xl border border-black/5 overflow-hidden p-2" style="background: rgba(255,255,255,0.96); backdrop-filter: blur(24px) saturate(180%); -webkit-backdrop-filter: blur(24px) saturate(180%); box-shadow: 0 20px 50px -12px rgba(8,29,58,0.28);">
                            <div class="grid grid-cols-2 gap-1">
                                <a href="{{ route('areas.show', 'milnerton') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Milnerton
                                </a>
                                <a href="{{ route('areas.show', 'sunningdale') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Sunningdale
                                </a>
                                <a href="{{ route('areas.show', 'blouberg') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Blouberg
                                </a>
                                <a href="{{ route('areas.show', 'parklands') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Parklands
                                </a>
                                <a href="{{ route('areas.show', 'century-city') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Century City
                                </a>
                                <a href="{{ route('areas.show', 'table-view') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Table View
                                </a>
                                <a href="{{ route('areas.show', 'big-bay') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Big Bay
                                </a>
                                <a href="{{ route('areas.show', 'bloubergstrand') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[#081d3a]/75 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[13px] font-semibold tracking-tight transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#f6e304] flex-shrink-0"></span>
                                    Bloubergstrand
                                </a>
                            </div>
                            <div class="border-t border-black/5 mt-1.5 pt-1.5">
                                <a href="/#areas" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[#081d3a]/40 hover:bg-[#081d3a]/[0.05] hover:text-[#081d3a] text-[12px] font-semibold tracking-tight transition-colors">
                                    View all areas
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="/#how-it-works" class="text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">How It Works</a>
                <a href="/#pricing" class="text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">Pricing</a>
                <a href="{{ route('blog.index') }}" class="text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">Blog</a>
            </div>

            <div class="hidden lg:flex items-center justify-end gap-5">
                <a href="tel:+27815274711" class="flex items-center gap-1.5 text-white/80 hover:text-[#f6e304] font-semibold text-[14px] tracking-tight transition-colors duration-200 flex-shrink-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-.826 1.677l-.916.458a1 1 0 00-.464 1.30 12.05 12.05 0 005.52 5.52 1 1 0 001.30-.464l.458-.916a1.5 1.5 0 011.677-.826l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-7.18 0-13-5.82-13-13V3.5z"/></svg>
                    081 527 4711
                </a>
                <a href="{{ route('booking.show') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#f6e304] text-[#081d3a] font-bold rounded-xl hover:bg-yellow-300 active:scale-95 transition-all duration-200 text-sm tracking-tight shadow-lg flex-shrink-0">
                    Get My Instant Quote
                </a>
            </div>

            <button @click="open = !open" class="lg:hidden justify-self-end p-2 rounded-lg text-white hover:bg-white/10 transition-colors duration-200" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden mt-2 rounded-2xl border border-white/10 shadow-2xl overflow-hidden"
         style="display: none; background: rgba(13,27,51,0.9); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        <div class="py-6 px-5 flex flex-col gap-1">
            <div x-data="{ servicesOpen: false }">
                <button type="button" @click="servicesOpen = !servicesOpen" class="w-full flex items-center justify-between py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">
                    <span>Services</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="servicesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="servicesOpen" x-transition class="pl-4 flex flex-col gap-1 pb-2" style="display:none;">
                    <a href="{{ route('services.show', 'deep-cleaning') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Deep Cleaning</a>
                    <a href="{{ route('services.show', 'end-of-tenancy') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">End-of-Tenancy Cleaning</a>
                    <a href="{{ route('services.show', 'post-construction') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Post Construction Cleaning</a>
                    <a href="/#services" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/40 hover:text-[#f6e304] text-[13px] transition-colors">View all services →</a>
                </div>
            </div>
            <div x-data="{ areasOpen: false }">
                <button type="button" @click="areasOpen = !areasOpen" class="w-full flex items-center justify-between py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">
                    <span>Areas We Serve</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="areasOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="areasOpen" x-transition class="pl-4 flex flex-col gap-1 pb-2" style="display:none;">
                    <a href="{{ route('areas.show', 'milnerton') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Milnerton</a>
                    <a href="{{ route('areas.show', 'sunningdale') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Sunningdale</a>
                    <a href="{{ route('areas.show', 'blouberg') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Blouberg</a>
                    <a href="{{ route('areas.show', 'parklands') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Parklands</a>
                    <a href="{{ route('areas.show', 'century-city') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Century City</a>
                    <a href="{{ route('areas.show', 'table-view') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Table View</a>
                    <a href="{{ route('areas.show', 'big-bay') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Big Bay</a>
                    <a href="{{ route('areas.show', 'bloubergstrand') }}" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/60 hover:bg-white/5 hover:text-[#f6e304] text-[14px] transition-colors">Bloubergstrand</a>
                    <a href="/#areas" @click="open = false" class="block py-2.5 px-4 rounded-lg text-white/40 hover:text-[#f6e304] text-[13px] transition-colors">View all areas →</a>
                </div>
            </div>
            <a href="/#how-it-works" @click="open = false" class="block py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">How It Works</a>
            <a href="/#pricing" @click="open = false" class="block py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">Pricing</a>
            <a href="{{ route('blog.index') }}" @click="open = false" class="block py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">Blog</a>
            <div class="flex flex-col gap-2 mt-4 pt-4 border-t border-white/10">
                <a href="{{ route('booking.show') }}" @click="open = false" class="flex items-center justify-center py-3 px-4 bg-[#f6e304] text-[#081d3a] rounded-xl font-bold text-[14px] hover:bg-yellow-300 transition-colors">
                    Get My Instant Quote
                </a>
            </div>
        </div>
    </div>
</nav>
