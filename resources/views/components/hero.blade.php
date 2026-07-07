<section class="relative min-h-screen flex items-center pt-20 pb-8 overflow-hidden">

    {{-- Background image --}}
    <img src="/images/works/hero/hero2_cleaning.avif"
         alt="SpringKleaners professional cleaning"
         class="absolute inset-0 w-full h-full object-cover object-center">

    {{-- Dark overlay: matches reference — strong on left, slightly lighter on right --}}
    <div class="absolute inset-0" style="background: linear-gradient(105deg, rgba(4,15,31,0.93) 0%, rgba(4,15,31,0.80) 35%, rgba(4,15,31,0.30) 65%, rgba(4,15,31,0.08) 100%);"></div>

    <div class="hero-pattern absolute inset-0"></div>
    <div class="absolute top-1/3 -left-32 w-72 h-72 bg-[#f6e304] rounded-full opacity-[0.04] blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-[#f6e304] rounded-full opacity-[0.03] blur-3xl pointer-events-none"></div>

    <div class="section-wrap relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 xl:gap-16 items-center">

            <div class="lg:col-span-7 lg:translate-y-16">
                <div id="hero-badge" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-2 mb-8">
                    <div class="flex text-[#f6e304] text-xs">★★★★★</div>
                    <span class="text-white/70 text-[13px] tracking-tight font-medium">Cape Town's Premium Cleaning Service</span>
                </div>

                <h1 id="hero-headline" class="text-5xl sm:text-6xl lg:text-7xl xl:text-[80px] font-extrabold text-white leading-[1.05] tracking-tight mb-6">
                    Spotlessly Clean.<br><span class="text-[#f6e304]">Every Time.</span>
                </h1>

                <p id="hero-sub" class="text-lg text-white/60 leading-relaxed max-w-2xl mb-10 font-normal tracking-tight">
                    Professional deep cleaning, end-of-tenancy &amp; post-construction cleaning for homes and businesses across Cape Town's Northern Suburbs.
                </p>

                <div id="hero-trust" class="flex flex-wrap gap-x-6 gap-y-3 mb-10">
                    <div class="flex items-center gap-2 text-white/60 text-[13px]">
                        <svg class="w-4 h-4 text-[#f6e304] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                        Fully Insured
                    </div>
                    <div class="flex items-center gap-2 text-white/60 text-[13px]">
                        <svg class="w-4 h-4 text-[#f6e304] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                        Vetted Staff
                    </div>
                    <div class="flex items-center gap-2 text-white/60 text-[13px]">
                        <svg class="w-4 h-4 text-[#f6e304] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                        Eco-Friendly Products
                    </div>
                    <div class="flex items-center gap-2 text-white/60 text-[13px]">
                        <svg class="w-4 h-4 text-[#f6e304] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                        Free Inspection
                    </div>
                </div>

                <div class="flex gap-4 lg:hidden">
                    <a href="#services" class="btn-outline">Our Services</a>
                </div>
            </div>

            <div class="lg:col-span-5" id="hero-form">
                <div x-data="{
                    suburbs: [
                        'Milnerton', 'Sunningdale', 'Blouberg', 'Parklands', 'Century City',
                        'Table View', 'Big Bay', 'Bloubergstrand', 'West Beach', 'Monte Vista',
                        'Edgemead', 'Bothasig', 'Richwood', 'Burgundy Estate', 'Flamingo Vlei',
                        'Sandown', 'Sunset Beach', 'Parklands North', 'Waves Edge', 'Montague Gardens',
                        'Blouberg Rise', 'Summer Greens', 'Rugby', 'Paarden Eiland', 'Marconi Beam',
                        'Dunoon', 'Joe Slovo Park', 'Penhill', 'Kerria', 'Ravensmead',
                        'Sea Point', 'Green Point'
                    ],
                    suburbQuery: '',
                    filteredSuburbs: [],
                    showSuggestions: false,
                    selectedSuburb: '',
                    locationStatus: null,
                    serviceError: false,
                    errors: { name: false, phone: false },
                    form: {
                        name: '',
                        phone: '',
                        email: '',
                        service: '',
                        propertyType: 'Residential'
                    },
                    waitlistEmail: '',
                    filterSuburbs() {
                        if (this.suburbQuery.length < 1) {
                            this.filteredSuburbs = [];
                            this.showSuggestions = false;
                            return;
                        }
                        this.filteredSuburbs = this.suburbs.filter(s =>
                            s.toLowerCase().includes(this.suburbQuery.toLowerCase())
                        );
                        this.showSuggestions = this.filteredSuburbs.length > 0;
                        this.locationStatus = null;
                    },
                    selectSuburb(suburb) {
                        this.selectedSuburb = suburb;
                        this.suburbQuery = suburb;
                        this.showSuggestions = false;
                        this.locationStatus = 'valid';
                    },
                    checkLocation() {
                        this.showSuggestions = false;
                        if (!this.suburbQuery.trim()) return;
                        const match = this.suburbs.find(s =>
                            s.toLowerCase() === this.suburbQuery.toLowerCase()
                        );
                        if (match) {
                            this.selectedSuburb = match;
                            this.locationStatus = 'valid';
                        } else {
                            this.locationStatus = 'invalid';
                        }
                    },
                    submitForm() {
                        this.errors.name = !this.form.name.trim();
                        this.errors.phone = !this.form.phone.trim();
                        this.serviceError = !this.form.service;
                        if (!this.suburbQuery.trim()) {
                            this.locationStatus = 'required';
                        } else if (this.locationStatus !== 'valid') {
                            this.checkLocation();
                        }
                        if (this.errors.name || this.errors.phone || this.serviceError || this.locationStatus !== 'valid') {
                            return;
                        }
                        const params = new URLSearchParams({
                            service: this.form.service,
                            name: this.form.name,
                            phone: this.form.phone,
                            suburb: this.selectedSuburb,
                        });
                        window.location.href = '{{ route('booking.show') }}?' + params.toString();
                    }
                }" class="bg-[#f8f9fc]/95 backdrop-blur-md rounded-2xl p-5 shadow-2xl">

                    <div>
                        <div class="mb-1">
                            <h3 class="text-[#081d3a] font-bold text-[20px] tracking-tight">Get Your Free Estimate</h3>
                            <p class="text-[#647082] text-sm mt-1">Get your instant price estimate</p>
                        </div>

                        <div class="border-t border-[#081d3a]/10 my-3"></div>

                        <div class="mb-3">
                            <label class="block text-[#081d3a]/70 text-xs uppercase tracking-wider mb-2 font-medium">Your Suburb <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="suburbQuery"
                                    x-on:input="filterSuburbs()"
                                    x-on:blur="setTimeout(() => { showSuggestions = false }, 200)"
                                    x-on:keydown.enter.prevent="checkLocation()"
                                    placeholder="e.g. Milnerton, Parklands..."
                                    class="w-full bg-white border rounded-xl px-4 py-3 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors"
                                    :class="locationStatus === 'required' ? 'border-red-400' : 'border-[#081d3a]/15'"
                                >
                                <div x-show="showSuggestions"
                                     class="absolute left-0 right-0 top-full mt-1 bg-[#081d3a] border border-white/10 rounded-xl z-50 max-h-48 overflow-y-auto"
                                     style="display:none;">
                                    <template x-for="suburb in filteredSuburbs" :key="suburb">
                                        <div @click="selectSuburb(suburb)"
                                             class="px-4 py-2.5 text-[14px] text-white/80 hover:bg-white/5 hover:text-[#f6e304] cursor-pointer flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-[#f6e304]/60 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span x-text="suburb"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div x-show="locationStatus === 'required'" class="mt-2 flex items-center gap-1.5 text-red-600 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                <span>Please enter your suburb</span>
                            </div>
                            <div x-show="locationStatus === 'valid'" class="mt-2 flex items-center gap-1.5 text-green-600 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Great! We service <span x-text="selectedSuburb" class="font-semibold"></span></span>
                            </div>
                            <div x-show="locationStatus === 'invalid'" class="mt-2 flex items-center gap-1.5 text-red-600 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                <span>We don't service <span x-text="suburbQuery || 'that area'" class="font-semibold"></span> yet</span>
                            </div>

                            <div x-show="locationStatus !== 'valid' && !showSuggestions" class="mt-3" style="display:none;">
                                <p class="text-[#081d3a]/40 text-[11px] uppercase tracking-wider mb-2">Popular areas</p>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="suburb in ['Milnerton', 'Blouberg', 'Table View', 'Parklands']" :key="suburb">
                                        <button type="button"
                                                @click="selectSuburb(suburb)"
                                                class="px-2.5 py-1 bg-[#081d3a]/5 border border-[#081d3a]/10 rounded-full text-[11px] text-[#081d3a]/70 hover:bg-[#f6e304] hover:border-[#f6e304] hover:text-[#081d3a] transition-colors"
                                                x-text="suburb">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-show="locationStatus === 'invalid'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-3"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mb-3 p-4 bg-[#081d3a]/5 rounded-xl border border-[#081d3a]/10"
                             style="display:none;">
                            <p class="text-[#081d3a] font-semibold text-[14px] mb-1">We're not in <span x-text="suburbQuery || 'your area'" class="font-bold"></span> yet</p>
                            <p class="text-[#647082] text-[12px] mb-3">We're expanding! Join our waitlist to be first.</p>
                            <div class="flex gap-2">
                                <input type="email" x-model="waitlistEmail" placeholder="Your email address" class="flex-1 bg-white border border-[#081d3a]/15 rounded-xl px-3 py-2.5 text-[#081d3a] text-[13px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors">
                                <button type="button" class="px-4 py-2.5 bg-[#f6e304] text-[#081d3a] font-bold rounded-xl text-[13px] hover:bg-yellow-300 transition-colors flex-shrink-0">
                                    Join
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[#081d3a]/50 text-[11px] uppercase tracking-wider mb-1 font-medium">Full Name <span class="text-rose-500 normal-case tracking-normal">*</span></label>
                                    <input type="text" x-model="form.name" @input="errors.name = false" placeholder="e.g. Jane Smith" class="w-full bg-white border rounded-xl px-4 py-2.5 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors" :class="errors.name ? 'border-red-400' : 'border-[#081d3a]/15'">
                                    <p x-show="errors.name" x-cloak class="text-red-600 text-[11.5px] mt-1">Please enter your name</p>
                                </div>
                                <div>
                                    <label class="block text-[#081d3a]/50 text-[11px] uppercase tracking-wider mb-1 font-medium">Phone <span class="text-rose-500 normal-case tracking-normal">*</span></label>
                                    <input type="tel" x-model="form.phone" @input="errors.phone = false" placeholder="082 xxx xxxx" class="w-full bg-white border rounded-xl px-4 py-2.5 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors" :class="errors.phone ? 'border-red-400' : 'border-[#081d3a]/15'">
                                    <p x-show="errors.phone" x-cloak class="text-red-600 text-[11.5px] mt-1">Please enter your phone number</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[#081d3a]/50 text-[11px] uppercase tracking-wider mb-1 font-medium">Email</label>
                                    <input type="email" x-model="form.email" placeholder="you@email.com" class="w-full bg-white border border-[#081d3a]/15 rounded-xl px-4 py-2.5 text-[#081d3a] text-[14px] placeholder-[#081d3a]/35 focus:border-[#f6e304] focus:outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[#081d3a]/50 text-[11px] uppercase tracking-wider mb-1 font-medium">Service <span class="text-rose-500 normal-case tracking-normal">*</span></label>
                                    <select x-model="form.service" @change="serviceError = false" class="w-full bg-white border rounded-xl px-4 py-2.5 text-[#081d3a] text-[14px] focus:border-[#f6e304] focus:outline-none transition-colors appearance-none cursor-pointer" :class="serviceError ? 'border-red-400' : 'border-[#081d3a]/15'">
                                        <option value="" class="bg-white">Select Service</option>
                                        <option value="deep-cleaning" class="bg-white">Deep Cleaning</option>
                                        <option value="end-of-tenancy" class="bg-white">End-of-Tenancy</option>
                                        <option value="post-construction" class="bg-white">Post Construction</option>
                                    </select>
                                    <p x-show="serviceError" x-cloak class="text-red-600 text-[11.5px] mt-1">Please choose a service</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="button"
                                        @click="form.propertyType = 'Residential'"
                                        :class="form.propertyType === 'Residential' ? 'bg-[#f6e304] text-[#081d3a]' : 'bg-[#081d3a]/5 text-[#081d3a]/60 border border-[#081d3a]/10'"
                                        class="flex-1 px-4 py-2.5 rounded-lg text-[13px] font-medium transition-colors">
                                    Residential
                                </button>
                                <button type="button"
                                        @click="form.propertyType = 'Commercial'"
                                        :class="form.propertyType === 'Commercial' ? 'bg-[#f6e304] text-[#081d3a]' : 'bg-[#081d3a]/5 text-[#081d3a]/60 border border-[#081d3a]/10'"
                                        class="flex-1 px-4 py-2.5 rounded-lg text-[13px] font-medium transition-colors">
                                    Commercial
                                </button>
                            </div>
                            <p x-show="errors.name || errors.phone || serviceError || locationStatus === 'required'" x-cloak class="text-red-500 text-[12px] -mt-1">Please fill in the highlighted fields above to continue.</p>
                            <button @click="submitForm()"
                                    type="button"
                                    class="w-full flex items-center justify-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold py-3.5 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-base shadow-lg">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h4m3 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Book My Clean
                            </button>
                            <p class="text-center text-[#647082] text-[11px]">🔒 Free inspection · No commitment · Instant estimate</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 bg-[#081d3a]/90 backdrop-blur-sm border border-white/10 rounded-2xl px-5 py-3.5 grid grid-cols-3 divide-x divide-white/10 shadow-xl">
                    <div class="text-center px-2">
                        <div class="text-white font-bold text-lg leading-none">4.9 <span class="text-[#f6e304]">★</span></div>
                        <div class="text-white/50 text-[10px] uppercase tracking-wider mt-1.5">Google Reviews</div>
                    </div>
                    <div class="text-center px-2">
                        <div class="text-[#f6e304] font-bold text-lg leading-none">98%</div>
                        <div class="text-white/50 text-[10px] uppercase tracking-wider mt-1.5">Satisfaction</div>
                    </div>
                    <div class="text-center px-2">
                        <div class="text-white font-bold text-lg leading-none">3+</div>
                        <div class="text-white/50 text-[10px] uppercase tracking-wider mt-1.5">Years Exp.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
