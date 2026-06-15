<section class="relative min-h-screen flex items-center pt-20 pb-16 overflow-hidden">

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

            <div class="lg:col-span-7">
                <div id="hero-badge" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-2 mb-8">
                    <div class="flex text-[#f6e304] text-xs">★★★★★</div>
                    <span class="text-white/70 text-[13px] tracking-tight font-medium">Cape Town's Premium Cleaning Service</span>
                </div>

                <h1 id="hero-headline" class="text-5xl sm:text-6xl lg:text-7xl xl:text-[80px] font-extrabold text-white leading-[1.05] tracking-tight mb-6">
                    Spotlessly Clean.<br><span class="text-[#f6e304]">Every Time.</span>
                </h1>

                <p id="hero-sub" class="text-lg text-white/60 leading-relaxed max-w-xl mb-10 font-normal tracking-tight">
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
                        'Dunoon', 'Joe Slovo Park', 'Penhill', 'Kerria', 'Ravensmead'
                    ],
                    suburbQuery: '',
                    filteredSuburbs: [],
                    showSuggestions: false,
                    selectedSuburb: '',
                    locationStatus: null,
                    formStep: 1,
                    form: {
                        name: '',
                        phone: '',
                        email: '',
                        service: '',
                        propertyType: 'Residential',
                        message: ''
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
                        const msg = encodeURIComponent(
                            'Hi SpringKleaners! I would like to request a free estimate.\n\n' +
                            'Name: ' + this.form.name + '\n' +
                            'Phone: ' + this.form.phone + '\n' +
                            'Email: ' + this.form.email + '\n' +
                            'Service: ' + this.form.service + '\n' +
                            'Property Type: ' + this.form.propertyType + '\n' +
                            'Suburb: ' + this.selectedSuburb + '\n' +
                            'Message: ' + (this.form.message || 'None')
                        );
                        window.open('https://wa.me/27814303023?text=' + msg, '_blank');
                        this.formStep = 3;
                    }
                }" class="bg-white/[0.04] border border-white/10 rounded-2xl p-7 backdrop-blur-sm">

                    <div x-show="formStep !== 3">
                        <div class="mb-1">
                            <h3 class="text-white font-bold text-[20px] tracking-tight">Get Your Free Estimate</h3>
                            <p class="text-[#f6e304]/70 text-sm mt-1">Get your instant price estimate</p>
                        </div>

                        <div class="border-t border-white/10 my-5"></div>

                        <div class="mb-5">
                            <label class="block text-white/70 text-xs uppercase tracking-wider mb-2 font-medium">Your Suburb</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="suburbQuery"
                                    x-on:input="filterSuburbs()"
                                    x-on:blur="setTimeout(() => { showSuggestions = false }, 200)"
                                    x-on:keydown.enter.prevent="checkLocation()"
                                    placeholder="e.g. Milnerton, Parklands..."
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-[14px] placeholder-white/30 focus:border-[#f6e304] focus:outline-none transition-colors"
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

                            <div x-show="locationStatus === 'valid'" class="mt-2 flex items-center gap-1.5 text-green-400 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Great! We service <span x-text="selectedSuburb" class="font-semibold"></span></span>
                            </div>
                            <div x-show="locationStatus === 'invalid'" class="mt-2 flex items-center gap-1.5 text-red-400 text-[13px]" style="display:none;">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                <span>We don't service <span x-text="suburbQuery" class="font-semibold"></span> yet</span>
                            </div>
                        </div>

                        <div x-show="locationStatus === 'valid'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-3"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-4"
                             style="display:none;">

                            <div>
                                <input type="text" x-model="form.name" placeholder="Full Name" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-[14px] placeholder-white/30 focus:border-[#f6e304] focus:outline-none transition-colors">
                            </div>
                            <div>
                                <input type="tel" x-model="form.phone" placeholder="+27 xxx xxx xxxx" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-[14px] placeholder-white/30 focus:border-[#f6e304] focus:outline-none transition-colors">
                            </div>
                            <div>
                                <input type="email" x-model="form.email" placeholder="Email address" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-[14px] placeholder-white/30 focus:border-[#f6e304] focus:outline-none transition-colors">
                            </div>
                            <div>
                                <select x-model="form.service" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-[14px] focus:border-[#f6e304] focus:outline-none transition-colors appearance-none cursor-pointer">
                                    <option value="" class="bg-[#081d3a]">Select Service</option>
                                    <option value="Deep Cleaning" class="bg-[#081d3a]">Deep Cleaning</option>
                                    <option value="End-of-Tenancy Cleaning" class="bg-[#081d3a]">End-of-Tenancy Cleaning</option>
                                    <option value="Post Construction Cleaning" class="bg-[#081d3a]">Post Construction Cleaning</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-white/50 text-xs uppercase tracking-wider mb-2">Property Type</label>
                                <div class="flex gap-2">
                                    <button type="button"
                                            @click="form.propertyType = 'Residential'"
                                            :class="form.propertyType === 'Residential' ? 'bg-[#f6e304] text-[#081d3a]' : 'bg-white/5 text-white/60 border border-white/10'"
                                            class="flex-1 px-4 py-2 rounded-lg text-[13px] font-medium transition-colors">
                                        Residential
                                    </button>
                                    <button type="button"
                                            @click="form.propertyType = 'Commercial'"
                                            :class="form.propertyType === 'Commercial' ? 'bg-[#f6e304] text-[#081d3a]' : 'bg-white/5 text-white/60 border border-white/10'"
                                            class="flex-1 px-4 py-2 rounded-lg text-[13px] font-medium transition-colors">
                                        Commercial
                                    </button>
                                </div>
                            </div>
                            <div>
                                <textarea x-model="form.message" rows="2" placeholder="Any additional details..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-[14px] placeholder-white/30 focus:border-[#f6e304] focus:outline-none transition-colors resize-none"></textarea>
                            </div>
                            <button @click="submitForm()"
                                    type="button"
                                    class="w-full flex items-center justify-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold py-4 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-base shadow-lg">
                                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Send via WhatsApp
                            </button>
                            <p class="text-center text-white/40 text-[12px]">🔒 Free inspection · No commitment · Instant estimate</p>
                        </div>

                        <div x-show="locationStatus === 'invalid'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-3"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mt-4 p-5 bg-white/5 rounded-xl border border-white/10"
                             style="display:none;">
                            <p class="text-white font-semibold text-[15px] mb-1">We're not in <span x-text="suburbQuery" class="text-[#f6e304]"></span> yet</p>
                            <p class="text-white/50 text-[13px] mb-4">We're expanding! Join our waitlist to be first.</p>
                            <div class="flex gap-2">
                                <input type="email" x-model="waitlistEmail" placeholder="Your email address" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-[13px] placeholder-white/30 focus:border-[#f6e304] focus:outline-none transition-colors">
                                <button type="button" class="px-4 py-2.5 bg-[#f6e304] text-[#081d3a] font-bold rounded-xl text-[13px] hover:bg-yellow-300 transition-colors flex-shrink-0">
                                    Join
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="formStep === 3"
                         x-transition:enter="transition ease-out duration-400"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="py-8 text-center"
                         style="display:none;">
                        <div class="w-16 h-16 rounded-full bg-[#f6e304]/10 border-2 border-[#f6e304] flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8 text-[#f6e304]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-white font-bold text-[20px] mb-2 tracking-tight">Request sent!</h3>
                        <p class="text-white/60 text-[14px] leading-relaxed">Check your WhatsApp — we'll confirm shortly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
