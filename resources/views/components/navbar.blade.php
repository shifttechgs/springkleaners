<nav id="mainNav" class="fixed w-full top-0 z-50" x-data="{
    open: false,
    laundryModal: false,
    laundryForm: { name: '', phone: '', suburb: '', address: '', pickupDate: '', pickupTimeSlot: '' },
    laundrySuburbs: ['Milnerton','Sunningdale','Blouberg','Parklands','Century City','Table View','Big Bay','Bloubergstrand','West Beach','Monte Vista','Edgemead','Bothasig','Richwood','Burgundy Estate','Flamingo Vlei','Sandown','Sunset Beach','Parklands North','Waves Edge','Montague Gardens','Blouberg Rise','Summer Greens'],
    laundrySuburbQuery: '',
    laundryFilteredSuburbs: [],
    showLaundrySuburbs: false,
    laundryLocationStatus: null,
    laundryWaitlistEmail: '',
    filterLaundrySuburbs() {
        if (this.laundrySuburbQuery.length < 1) { this.laundryFilteredSuburbs = []; this.showLaundrySuburbs = false; return; }
        this.laundryFilteredSuburbs = this.laundrySuburbs.filter(s => s.toLowerCase().includes(this.laundrySuburbQuery.toLowerCase()));
        this.showLaundrySuburbs = this.laundryFilteredSuburbs.length > 0;
        this.laundryLocationStatus = null;
    },
    selectLaundrySuburb(s) { this.laundryForm.suburb = s; this.laundrySuburbQuery = s; this.showLaundrySuburbs = false; this.laundryLocationStatus = 'valid'; },
    checkLaundryLocation() {
        this.showLaundrySuburbs = false;
        if (!this.laundrySuburbQuery.trim()) return;
        const m = this.laundrySuburbs.find(s => s.toLowerCase() === this.laundrySuburbQuery.toLowerCase());
        if (m) { this.laundryForm.suburb = m; this.laundryLocationStatus = 'valid'; } else { this.laundryLocationStatus = 'invalid'; }
    },
    submitLaundry() {
        const lines = ['Hi SpringKleaners! I\'d like to request a laundry pickup.', '', 'Name: ' + this.laundryForm.name, 'Phone: ' + this.laundryForm.phone, 'Suburb: ' + this.laundryForm.suburb, 'Address: ' + this.laundryForm.address, 'Pickup Date: ' + this.laundryForm.pickupDate, 'Pickup Time: ' + this.laundryForm.pickupTimeSlot];
        window.open('https://wa.me/27814303023?text=' + encodeURIComponent(lines.join('\n')), '_blank');
        this.laundryModal = false;
        this.laundryForm = { name: '', phone: '', suburb: '', address: '', pickupDate: '', pickupTimeSlot: '' };
        this.laundrySuburbQuery = '';
        this.laundryLocationStatus = null;
    }
}">
    <div class="max-w-7xl mx-auto px-5 lg:px-12">
        <div class="flex justify-between items-center h-20">
            <a href="/" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl bg-[#f6e304] flex items-center justify-center flex-shrink-0 shadow-md">
                    <span class="text-[#081d3a] font-black text-[14px] tracking-tight leading-none">SK</span>
                </div>
                <span class="font-extrabold text-[20px] tracking-tight leading-none">
                    <span class="text-white">Spring</span><span class="text-[#f6e304]">Kleaners</span>
                </span>
            </a>

            <div class="hidden lg:flex items-center gap-10">
                <a href="#services" class="text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">Services</a>
                <a href="#how-it-works" class="text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">How It Works</a>
                <a href="#testimonials" class="text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">Reviews</a>
                <a href="#pricing" class="text-white/70 hover:text-[#f6e304] font-medium text-[14px] tracking-tight transition-colors duration-200">Pricing</a>
            </div>

            <div class="hidden lg:flex items-center gap-3">
                <button @click="laundryModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-[#081d3a] font-semibold rounded-xl hover:bg-gray-100 active:scale-95 transition-all duration-200 text-sm tracking-tight">
                    Request Laundry Pickup
                </button>
                <a href="/get-my-quote" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#f6e304] text-[#081d3a] font-bold rounded-xl hover:bg-yellow-300 active:scale-95 transition-all duration-200 text-sm tracking-tight shadow-lg">
                    Get My Instant Quote
                </a>
            </div>

            <button @click="open = !open" class="lg:hidden p-2 rounded-lg text-white hover:bg-white/10 transition-colors duration-200" aria-label="Toggle menu">
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
         class="lg:hidden bg-[#0d1b33] border-t border-white/10"
         style="display: none;">
        <div class="py-6 px-5 flex flex-col gap-1">
            <a href="#services" @click="open = false" class="block py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">Services</a>
            <a href="#how-it-works" @click="open = false" class="block py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">How It Works</a>
            <a href="#testimonials" @click="open = false" class="block py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">Reviews</a>
            <a href="#pricing" @click="open = false" class="block py-3 px-4 rounded-lg text-white/80 hover:bg-white/5 hover:text-[#f6e304] text-[15px] transition-colors">Pricing</a>
            <div class="flex flex-col gap-2 mt-4 pt-4 border-t border-white/10">
                <button @click="open = false; laundryModal = true" class="flex items-center justify-center py-3 px-4 border border-white/15 text-white/70 rounded-xl font-medium text-[14px] hover:border-white/30 hover:text-white transition-colors">
                    Request Laundry Pickup
                </button>
                <a href="/get-my-quote" @click="open = false" class="flex items-center justify-center py-3 px-4 bg-[#f6e304] text-[#081d3a] rounded-xl font-bold text-[14px] hover:bg-yellow-300 transition-colors">
                    Get My Instant Quote
                </a>
            </div>
        </div>
    </div>

    {{-- Laundry Pickup Modal --}}
    <div x-show="laundryModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;"
         @keydown.escape.window="laundryModal = false">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-[#081d3a]/70 backdrop-blur-sm" @click="laundryModal = false"></div>

        {{-- Modal Card --}}
        <div x-show="laundryModal"
             x-transition:enter="transition ease-out duration-300 delay-75"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md"
             @click.stop>

            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-start justify-between">
                <div>
                    <h3 class="text-[#081d3a] text-[18px] font-black tracking-tight" style="font-family:'Geist',sans-serif;">Request Laundry Pickup</h3>
                    <p class="text-[#8a94a6] text-[12.5px] mt-1" style="font-family:'Plus Jakarta Sans',sans-serif;">We'll collect, clean & deliver back to you.</p>
                </div>
                <button @click="laundryModal = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-[#8a94a6] hover:bg-gray-100 hover:text-[#081d3a] transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <div class="p-6 space-y-4">

                {{-- Suburb selector (always on top) --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.09em] text-[#8a94a6] mb-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Suburb</label>
                    <div class="relative">
                        <input type="text" x-model="laundrySuburbQuery"
                               @input="filterLaundrySuburbs()"
                               @blur="setTimeout(() => { showLaundrySuburbs = false; if(laundrySuburbQuery && !laundryLocationStatus) checkLaundryLocation(); }, 300)"
                               @keydown.enter.prevent="checkLaundryLocation()"
                               placeholder="e.g. Milnerton, Table View..."
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[14px] text-[#081d3a] placeholder-[#adb5c4] focus:outline-none focus:border-[#081d3a] focus:ring-2 focus:ring-[#081d3a]/5 transition-all"
                               style="font-family:'Plus Jakarta Sans',sans-serif;">
                        <div x-show="showLaundrySuburbs"
                             class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl z-50 max-h-[160px] overflow-y-auto shadow-lg"
                             style="display:none;">
                            <template x-for="s in laundryFilteredSuburbs" :key="s">
                                <div @click="selectLaundrySuburb(s)"
                                     class="px-4 py-2.5 text-[13px] text-[#4a5568] hover:bg-gray-50 hover:text-[#081d3a] cursor-pointer"
                                     style="font-family:'Plus Jakarta Sans',sans-serif;"
                                     x-text="s"></div>
                            </template>
                        </div>
                    </div>
                    <div x-show="laundryLocationStatus === 'valid'" class="mt-1.5 flex items-center gap-1.5 text-emerald-600 text-[11.5px]" style="display:none; font-family:'Plus Jakarta Sans',sans-serif;">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        We service <strong x-text="laundryForm.suburb" class="ml-0.5"></strong>
                    </div>
                    <div x-show="laundryLocationStatus === 'invalid'" class="mt-1.5 flex items-center gap-1.5 text-rose-500 text-[11.5px]" style="display:none; font-family:'Plus Jakarta Sans',sans-serif;">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Sorry, we don't service this area yet.
                    </div>
                </div>

                {{-- Waitlist for unserviced areas --}}
                <div x-show="laundryLocationStatus === 'invalid'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="rounded-xl p-4 border border-gray-100 bg-[#f8f9fc]"
                     style="display:none;">
                    <p class="text-[#081d3a] font-semibold text-[13px] mb-0.5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        We're expanding into <span x-text="laundrySuburbQuery" class="text-[#f6e304] bg-[#081d3a] px-1.5 py-0.5 rounded-md text-[12px]"></span> soon.
                    </p>
                    <p class="text-[#adb5c4] text-[12px] mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">Join our waitlist — be the first to know.</p>
                    <div class="flex gap-2">
                        <input type="email" x-model="laundryWaitlistEmail" placeholder="Your email address"
                               class="flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] text-[#081d3a] placeholder-[#adb5c4] focus:outline-none focus:border-[#081d3a] transition-all"
                               style="font-family:'Plus Jakarta Sans',sans-serif;">
                        <button type="button" class="px-4 py-2.5 bg-[#081d3a] text-white font-bold rounded-lg text-[12px] hover:bg-[#0d2a4a] transition-colors flex-shrink-0" style="font-family:'Geist',sans-serif;">
                            Join
                        </button>
                    </div>
                </div>

                {{-- Name & Phone (shown after valid suburb) --}}
                <div x-show="laundryLocationStatus === 'valid'" style="display:none;">
                    <label class="block text-[11px] font-bold uppercase tracking-[0.09em] text-[#8a94a6] mb-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Full Name</label>
                    <input type="text" x-model="laundryForm.name" placeholder="Jane Smith"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[14px] text-[#081d3a] placeholder-[#adb5c4] focus:outline-none focus:border-[#081d3a] focus:ring-2 focus:ring-[#081d3a]/5 transition-all"
                           style="font-family:'Plus Jakarta Sans',sans-serif;">
                </div>
                <div x-show="laundryLocationStatus === 'valid'" style="display:none;">
                    <label class="block text-[11px] font-bold uppercase tracking-[0.09em] text-[#8a94a6] mb-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Phone / WhatsApp</label>
                    <input type="tel" x-model="laundryForm.phone" placeholder="+27 81 000 0000"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[14px] text-[#081d3a] placeholder-[#adb5c4] focus:outline-none focus:border-[#081d3a] focus:ring-2 focus:ring-[#081d3a]/5 transition-all"
                           style="font-family:'Plus Jakarta Sans',sans-serif;">
                </div>

                {{-- Street address --}}
                <div x-show="laundryLocationStatus === 'valid'" style="display:none;">
                    <label class="block text-[11px] font-bold uppercase tracking-[0.09em] text-[#8a94a6] mb-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Street Address</label>
                    <input type="text" x-model="laundryForm.address" placeholder="e.g. 12 Ocean View Drive"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[14px] text-[#081d3a] placeholder-[#adb5c4] focus:outline-none focus:border-[#081d3a] focus:ring-2 focus:ring-[#081d3a]/5 transition-all"
                           style="font-family:'Plus Jakarta Sans',sans-serif;">
                </div>

                {{-- Pickup date --}}
                <div x-show="laundryLocationStatus === 'valid'" class="grid grid-cols-2 gap-3" style="display:none;">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.09em] text-[#8a94a6] mb-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Pickup Date</label>
                        <input type="date" x-model="laundryForm.pickupDate"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[14px] text-[#081d3a] focus:outline-none focus:border-[#081d3a] focus:ring-2 focus:ring-[#081d3a]/5 transition-all"
                               style="font-family:'Plus Jakarta Sans',sans-serif;">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.09em] text-[#8a94a6] mb-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Pickup Time</label>
                        <select x-model="laundryForm.pickupTimeSlot"
                                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[14px] text-[#081d3a] focus:outline-none focus:border-[#081d3a] focus:ring-2 focus:ring-[#081d3a]/5 transition-all appearance-none"
                                style="font-family:'Plus Jakarta Sans',sans-serif;">
                            <option value="">Select...</option>
                            <option value="17:30 - 18:00">17:30 – 18:00</option>
                            <option value="18:00 - 18:30">18:00 – 18:30</option>
                            <option value="18:30 - 19:00">18:30 – 19:00</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-6" x-show="laundryLocationStatus === 'valid'" style="display:none;">
                <button @click="submitLaundry()" type="button"
                        class="w-full flex items-center justify-center gap-2.5 bg-[#f6e304] text-[#081d3a] font-extrabold py-3.5 rounded-xl hover:bg-yellow-300 active:scale-[0.97] transition-all text-[14px] tracking-tight"
                        style="font-family:'Geist',sans-serif;">
                    Request Pickup
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7v10"/>
                    </svg>
                </button>
                <p class="text-center text-[#c8cdd8] text-[11px] mt-2.5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Free collection · 24hr turnaround · Delivery included
                </p>
            </div>
        </div>
    </div>
</nav>
