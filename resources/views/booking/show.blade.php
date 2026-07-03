@extends('layouts.app')

@section('title', 'Book Your Clean Online — SpringKleaners Cape Town')
@section('description', 'Book Deep Cleaning, End-of-Tenancy or Post Construction cleaning online in minutes. Instant estimate, transparent pricing, no hidden fees — confirmed via WhatsApp.')

@push('styles')
<style>
    .bk-body { background: #f8f9fc; }

    .fi {
        width: 100%;
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 12px;
        padding: 13px 16px;
        color: #081d3a;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        letter-spacing: -0.01em;
        appearance: none;
    }
    .fi::placeholder { color: #adb5c4; }
    .fi:focus { outline: none; border-color: #081d3a; box-shadow: 0 0 0 3px rgba(8,29,58,0.07); }
    .fi-error { border-color: #fca5a5 !important; background: #fef9f9; }
    .fl {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #8a94a6;
        margin-bottom: 7px;
    }
    .req { color: #f43f5e; }
    .field-err { display: flex; align-items: center; gap: 5px; color: #ef4444; font-size: 12px; font-weight: 600; margin-top: 6px; }
    .fs-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #adb5c4;
    }
    .type-btn {
        flex: 1; padding: 11px 12px; border-radius: 10px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        transition: all 0.18s; border: 1.5px solid #e8eaf0;
        text-align: center;
    }
    .type-inactive { background: #fff; color: #8a94a6; }
    .type-active   { background: #081d3a; color: #fff; border-color: #081d3a; }

    .service-pick {
        padding: 12px 14px; border-radius: 10px; text-align: left;
        border: 1.5px solid #e8eaf0; cursor: pointer; transition: all 0.18s; background: #fff;
    }
    .service-pick-active { background: #081d3a; border-color: #081d3a; }
    .sp-name { font-size: 13.5px; font-weight: 700; color: #081d3a; }
    .service-pick-active .sp-name { color: #fff; }
    .sp-sub { font-size: 11px; color: #8a94a6; margin-top: 2px; }
    .service-pick-active .sp-sub { color: rgba(255,255,255,0.6); }

    .pill-btn {
        padding: 9px 14px; border-radius: 999px; font-size: 12.5px; font-weight: 600;
        border: 1.5px solid #e8eaf0; cursor: pointer; transition: all 0.18s; color: #647082; background: #fff;
        white-space: nowrap;
    }
    .pill-active { background: #081d3a; color: #fff; border-color: #081d3a; }

    .addon-card {
        border: 1.5px solid #e8eaf0; border-radius: 14px; padding: 14px 16px;
        cursor: pointer; transition: all 0.15s; background: #fff;
    }
    .addon-card:hover { border-color: #c9cedb; }
    .addon-active { border-color: #081d3a; background: #f8f9fc; }

    .cal-cell {
        width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
        border-radius: 10px; font-size: 13px; font-weight: 600; color: #081d3a; cursor: pointer;
        transition: all 0.15s;
    }
    .cal-cell:hover:not(.cal-disabled):not(.cal-selected) { background: #f0f2f7; }
    .cal-disabled { color: #d5d9e2; cursor: not-allowed; }
    .cal-disabled.cal-full { background: #fef2f2; color: #fca5a5; }
    .cal-selected { background: #081d3a; color: #fff; }

    .time-slot {
        padding: 11px 8px; border-radius: 10px; border: 1.5px solid #e8eaf0; text-align: center;
        font-size: 13px; font-weight: 600; color: #081d3a; cursor: pointer; transition: all 0.15s; background: #fff;
    }
    .time-slot:hover { border-color: #c9cedb; }
    .time-selected { background: #081d3a; color: #fff; border-color: #081d3a; }

    .step-dot {
        width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 800; flex-shrink: 0; transition: all 0.2s;
    }
    .step-dot-done { background: #f6e304; color: #081d3a; }
    .step-dot-current { background: #fff; color: #081d3a; border: 2px solid #f6e304; }
    .step-dot-upcoming { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.4); }
    .step-line { flex: 1; height: 1px; background: rgba(255,255,255,0.15); max-width: 80px; }
    .step-line-done { background: #f6e304; }

    .summary-row { display: flex; align-items: flex-start; gap: 12px; padding: 16px 0; border-bottom: 1px solid #edf0f5; }
    .summary-icon { width: 38px; height: 38px; border-radius: 10px; background: #f0f2f7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="{
        step: 1,
        submitted: false,
        services: {{ Illuminate\Support\Js::from($services) }},
        selectedService: {{ Illuminate\Support\Js::from($selectedSlug) }},
        get service() { return this.services[this.selectedService]; },
        addonsList: {{ Illuminate\Support\Js::from($addons) }},
        suburbs: [
            'Milnerton','Sunningdale','Blouberg','Parklands','Century City',
            'Table View','Big Bay','Bloubergstrand','West Beach','Monte Vista',
            'Edgemead','Bothasig','Richwood','Burgundy Estate','Flamingo Vlei',
            'Sandown','Sunset Beach','Parklands North','Waves Edge','Montague Gardens',
            'Blouber Rise','Summer Greens','Rugby','Paarden Eiland','Marconi Beam',
            'Dunoon','Joe Slovo Park','Penhill','Kerria','Ravensmead'
        ],
        suburbQuery: '', filteredSuburbs: [], showSuggestions: false, selectedSuburb: '', locationStatus: null,
        form: {
            name: '', phone: '', propertyType: 'House', bedrooms: '2', bathrooms: '1', extraRooms: '0',
            lastCleaned: '', floorTypes: [], pets: false, notes: '', address: '', accessInstructions: '', parking: ''
        },
        floorTypeOptions: ['Carpet', 'Tile', 'Hardwood', 'Laminate', 'Vinyl', 'Epoxy'],
        toggleFloorType(type) {
            if (this.form.floorTypes.includes(type)) { this.form.floorTypes = this.form.floorTypes.filter(t => t !== type); }
            else { this.form.floorTypes.push(type); }
        },
        selectedAddons: [],
        bookingType: 'once-off', frequency: 'Weekly',
        calMonth: (new Date()).getMonth(), calYear: (new Date()).getFullYear(),
        selectedDate: null, selectedTime: null,
        timeSlots: ['8:00 AM','9:00 AM','10:00 AM','11:00 AM','12:00 PM','1:00 PM','2:00 PM','3:00 PM'],
        availability: {}, capacity: 2, nextAvailable: null, dateError: '', checkingDate: false,

        init() {
            this.fetchAvailability();
            const params = new URLSearchParams(window.location.search);
            const name = params.get('name');
            const phone = params.get('phone');
            const suburb = params.get('suburb');
            if (name) this.form.name = name;
            if (phone) this.form.phone = phone;
            if (suburb) {
                const match = this.suburbs.find(s => s.toLowerCase() === suburb.toLowerCase());
                this.selectedSuburb = match || suburb;
                this.suburbQuery = this.selectedSuburb;
                this.locationStatus = 'valid';
            }
        },
        selectService(slug) {
            if (!this.services[slug]) return;
            this.selectedService = slug;
            const url = new URL(window.location.href);
            url.searchParams.set('service', slug);
            window.history.replaceState(null, '', url);
        },
        bedroomMap: {'1':1,'2':2,'3':3,'4':4,'5+':5},
        bathroomMap: {'1':1,'2':2,'3':3,'4+':4},
        roomMap: {'0':0,'1':1,'2':2,'3':3,'4+':4},

        get bedroomCount() { return this.bedroomMap[this.form.bedrooms] ?? 0 },
        get bathroomCount() { return this.bathroomMap[this.form.bathrooms] ?? 0 },
        get extraRoomsCount() { return this.roomMap[this.form.extraRooms] ?? 0 },
        get extraBedroomCost() { return Math.max(0, this.bedroomCount - this.service.included_bedrooms) * this.service.bedroom_price },
        get extraBathroomCost() { return Math.max(0, this.bathroomCount - this.service.included_bathrooms) * this.service.bathroom_price },
        get extraRoomsCost() { return this.extraRoomsCount * this.service.extra_room_price },
        get addonsCost() { return this.selectedAddons.reduce((sum, key) => { const a = this.addonsList.find(x => x.key === key); return sum + (a ? a.price : 0); }, 0) },
        get subtotal() { return this.service.base_price + this.extraBedroomCost + this.extraBathroomCost + this.extraRoomsCost + this.addonsCost },
        get total() { return this.subtotal + this.service.service_fee },

        toggleAddon(key) {
            if (this.selectedAddons.includes(key)) { this.selectedAddons = this.selectedAddons.filter(k => k !== key); }
            else { this.selectedAddons.push(key); }
        },

        filterSuburbs() {
            if (this.suburbQuery.length < 1) { this.filteredSuburbs = []; this.showSuggestions = false; return; }
            this.filteredSuburbs = this.suburbs.filter(s => s.toLowerCase().includes(this.suburbQuery.toLowerCase()));
            this.showSuggestions = this.filteredSuburbs.length > 0;
            this.locationStatus = null;
        },
        selectSuburb(s) { this.selectedSuburb = s; this.suburbQuery = s; this.showSuggestions = false; this.locationStatus = 'valid'; },
        checkLocation() {
            this.showSuggestions = false;
            if (!this.suburbQuery.trim()) return;
            const m = this.suburbs.find(s => s.toLowerCase() === this.suburbQuery.toLowerCase());
            if (m) { this.selectedSuburb = m; this.locationStatus = 'valid'; } else { this.locationStatus = 'invalid'; }
        },

        get calDays() {
            const days = [];
            const first = new Date(this.calYear, this.calMonth, 1);
            const startDow = (first.getDay() + 6) % 7;
            const daysInMonth = new Date(this.calYear, this.calMonth + 1, 0).getDate();
            for (let i = 0; i < startDow; i++) days.push(null);
            for (let d = 1; d <= daysInMonth; d++) days.push(d);
            return days;
        },
        get calMonthLabel() { return new Date(this.calYear, this.calMonth, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' }); },
        isPast(d) {
            if (!d) return false;
            const dt = new Date(this.calYear, this.calMonth, d);
            const today = new Date(); today.setHours(0,0,0,0);
            return dt < today;
        },
        isWeekend(d) {
            if (!d) return false;
            const dow = new Date(this.calYear, this.calMonth, d).getDay();
            return dow === 0 || dow === 6;
        },
        dateISO(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + dd;
        },
        dateKey(d) { return this.dateISO(new Date(this.calYear, this.calMonth, d)); },
        bookedCount(d) { return this.availability[this.dateKey(d)] || 0; },
        isFull(d) { return d ? this.bookedCount(d) >= this.capacity : false; },
        isSelectable(d) { return !!d && this.isWeekend(d) && !this.isPast(d) && !this.isFull(d); },
        isSelected(d) {
            if (!d || !this.selectedDate) return false;
            return this.selectedDate.getFullYear() === this.calYear && this.selectedDate.getMonth() === this.calMonth && this.selectedDate.getDate() === d;
        },
        prevMonth() { this.calMonth--; if (this.calMonth < 0) { this.calMonth = 11; this.calYear--; } this.fetchAvailability(); },
        nextMonth() { this.calMonth++; if (this.calMonth > 11) { this.calMonth = 0; this.calYear++; } this.fetchAvailability(); },
        pickDate(d) {
            if (!this.isSelectable(d)) return;
            this.selectedDate = new Date(this.calYear, this.calMonth, d);
            this.selectedTime = null;
            this.dateError = '';
            this.errors.dateTime = false;
        },
        get selectedDateLabel() { return this.selectedDate ? this.selectedDate.toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) : ''; },

        errors: { name: false, phone: false, address: false, suburb: false, dateTime: false },

        validateStep1() {
            this.errors.name = !this.form.name.trim();
            this.errors.phone = !this.form.phone.trim();
            this.errors.address = !this.form.address.trim();
            this.errors.suburb = !this.suburbQuery.trim();
            if (this.suburbQuery.trim()) { this.checkLocation(); }
            return !this.errors.name && !this.errors.phone && !this.errors.address && !this.errors.suburb;
        },
        validateStep2() {
            this.errors.dateTime = !this.selectedDate || !this.selectedTime;
            return !this.errors.dateTime;
        },

        goStep(n) { this.step = n; window.scrollTo({ top: 260, behavior: 'smooth' }); },

        async fetchAvailability() {
            const month = this.calYear + '-' + String(this.calMonth + 1).padStart(2, '0') + '-01';
            try {
                const res = await fetch('{{ route('booking.availability') }}?month=' + month);
                const json = await res.json();
                this.availability = json.counts || {};
                this.capacity = json.capacity || this.capacity;
                this.nextAvailable = json.next_available || null;
            } catch (e) {}
        },

        get nextAvailableLabel() {
            if (!this.nextAvailable) return '';
            const [y, m, d] = this.nextAvailable.split('-').map(Number);
            return new Date(y, m - 1, d).toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long' });
        },
        get isNextAvailableAlreadySelected() {
            return !!(this.selectedDate && this.nextAvailable && this.dateISO(this.selectedDate) === this.nextAvailable);
        },
        goToNextAvailable() {
            if (!this.nextAvailable) return;
            const [y, m, d] = this.nextAvailable.split('-').map(Number);
            this.calYear = y; this.calMonth = m - 1;
            this.selectedDate = new Date(y, m - 1, d);
            this.selectedTime = null;
            this.dateError = '';
            this.errors.dateTime = false;
            this.fetchAvailability();
        },

        buildWhatsAppUrl() {
            const addonLines = this.selectedAddons.map(key => { const a = this.addonsList.find(x => x.key === key); return '- ' + a.label + ' (+R' + a.price + ')'; }).join('\n');
            const lines = [
                'Hi SpringKleaners! I\'d like to book: ' + this.service.name,
                '',
                'Name: ' + this.form.name,
                'Phone: ' + this.form.phone,
                'Address: ' + this.form.address,
                'Suburb: ' + this.selectedSuburb,
                'Property type: ' + this.form.propertyType,
                'Bedrooms: ' + this.form.bedrooms,
                'Bathrooms: ' + this.form.bathrooms,
                'Extra rooms: ' + this.form.extraRooms,
                'Last professionally cleaned: ' + (this.form.lastCleaned || 'N/A'),
                'Main flooring type(s): ' + (this.form.floorTypes.length ? this.form.floorTypes.join(', ') : 'N/A'),
                'Pets on premises: ' + (this.form.pets ? 'Yes' : 'No'),
                'Add-ons: ' + (this.selectedAddons.length ? ('\n' + addonLines) : 'None'),
                'Booking type: ' + (this.bookingType === 'recurring' ? ('Recurring (' + this.frequency + ')') : 'Once-off'),
                'Preferred date: ' + this.selectedDateLabel,
                'Preferred time: ' + this.selectedTime,
                'Access instructions: ' + (this.form.accessInstructions || 'N/A'),
                'Parking: ' + (this.form.parking || 'N/A'),
                'Special instructions: ' + (this.form.notes || 'None'),
                '',
                'Estimated total: R' + this.total.toLocaleString() + ' (estimate only, confirmed after a quick property check)',
            ];
            return 'https://wa.me/27815274711?text=' + encodeURIComponent(lines.join('\n'));
        },

        submitForm() {
            if (this.checkingDate || !this.selectedDate || !this.selectedTime) return;
            this.checkingDate = true;
            this.dateError = '';
            const popup = window.open('', '_blank');

            fetch('{{ route('booking.reserve') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({
                    service: this.service.slug,
                    date: this.dateISO(this.selectedDate),
                    time: this.selectedTime,
                    name: this.form.name,
                    phone: this.form.phone,
                    address: this.form.address,
                    suburb: this.selectedSuburb,
                }),
            })
            .then(res => res.json().then(json => ({ ok: res.ok, json })))
            .then(({ ok, json }) => {
                this.checkingDate = false;
                if (!ok || json.status !== 'ok') {
                    if (popup) popup.close();
                    this.nextAvailable = json.next_available || this.nextAvailable;
                    this.dateError = 'That date just got fully booked for the weekend — please choose another date below.';
                    this.selectedDate = null;
                    this.selectedTime = null;
                    this.goStep(2);
                    this.fetchAvailability();
                    return;
                }
                const url = this.buildWhatsAppUrl();
                if (popup) { popup.location.href = url; } else { window.open(url, '_blank'); }
                this.submitted = true;
            })
            .catch(() => {
                this.checkingDate = false;
                if (popup) popup.close();
                this.dateError = 'Something went wrong — please try again or WhatsApp us directly.';
            });
        }
     }">

    <div class="bg-[#081d3a] pt-20 pb-8 relative overflow-hidden">
        <div class="absolute inset-0 hero-pattern"></div>
        @include('components.navbar')

        <div class="relative z-10 section-wrap pt-10 pb-2 text-center">
            <p class="text-white/40 text-[11px] font-semibold uppercase tracking-[0.22em] mb-3">Book Online</p>
            <h1 class="text-white text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight mb-8" x-text="service.name"></h1>
        </div>
    </div>

    <div class="bk-body">

    {{-- Step indicator --}}
    <div class="bg-[#081d3a] pb-8">
        <div class="section-wrap">
            <div class="flex items-center justify-center gap-3 sm:gap-4 max-w-md mx-auto">
                <template x-for="(label, idx) in ['Details','Schedule','Review']" :key="idx">
                    <template x-if="true">
                        <div class="flex items-center gap-3 sm:gap-4" :class="idx < 2 ? 'flex-1' : ''">
                            <div class="flex flex-col items-center gap-2 flex-shrink-0">
                                <div class="step-dot"
                                     :class="step > idx + 1 ? 'step-dot-done' : (step === idx + 1 ? 'step-dot-current' : 'step-dot-upcoming')">
                                    <svg x-show="step > idx + 1" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span x-show="step <= idx + 1" x-text="idx + 1"></span>
                                </div>
                                <span class="text-[11px] font-semibold" :class="step >= idx + 1 ? 'text-white/80' : 'text-white/30'" x-text="label"></span>
                            </div>
                            <div class="step-line" :class="step > idx + 1 ? 'step-line-done' : ''" x-show="idx < 2"></div>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </div>

    <div class="section-wrap py-14 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12 items-start">

            {{-- ══ LEFT: WIZARD ══ --}}
            <div class="lg:col-span-7">

                {{-- Success screen --}}
                <div x-show="submitted" x-cloak
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white rounded-3xl p-10 sm:p-16 text-center shadow-sm border border-[#edf0f5]">
                    <div class="w-20 h-20 rounded-full bg-[#f6e304] mx-auto mb-7 flex items-center justify-center">
                        <svg class="w-9 h-9 text-[#081d3a]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-[#081d3a] text-3xl font-black tracking-tight mb-3">Booking request sent!</h2>
                    <p class="text-[#8a94a6] text-[15px] leading-relaxed max-w-sm mx-auto">
                        Check your WhatsApp — our team will confirm availability and your final price within a few hours.
                    </p>
                    <div class="mt-8">
                        <a href="/" class="inline-flex items-center gap-2 text-[#081d3a] text-[13.5px] font-semibold hover:text-[#f6e304] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to home
                        </a>
                    </div>
                </div>

                <div x-show="!submitted">

                {{-- ── STEP 1: DETAILS ── --}}
                <div x-show="step === 1" x-cloak class="bg-white rounded-3xl border border-[#edf0f5] shadow-sm p-6 sm:p-8">
                    <h2 class="text-[#081d3a] text-[22px] font-black tracking-tight">Your details</h2>
                    <p class="text-[#adb5c4] text-[13.5px] mt-1 mb-7">Tell us about the property and what you need cleaned.</p>

                    <div class="mb-7">
                        <label class="fl">Which service do you need?</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <template x-for="(svc, slug) in services" :key="slug">
                                <button type="button" @click="selectService(slug)"
                                        class="service-pick" :class="selectedService === slug ? 'service-pick-active' : ''">
                                    <span class="sp-name block" x-text="svc.name"></span>
                                    <span class="sp-sub block" x-text="'From R' + svc.base_price.toLocaleString()"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="h-px bg-[#edf0f5] mb-7"></div>

                    <p class="fs-label mb-4">About the clean</p>

                    <div class="mb-5">
                        <label class="fl">Property type</label>
                        <div class="flex gap-2">
                            <button type="button" @click="form.propertyType = 'House'" :class="form.propertyType === 'House' ? 'type-active' : 'type-inactive'" class="type-btn">House</button>
                            <button type="button" @click="form.propertyType = 'Apartment / Flat'" :class="form.propertyType === 'Apartment / Flat' ? 'type-active' : 'type-inactive'" class="type-btn">Apartment / Flat</button>
                            <button type="button" @click="form.propertyType = 'Townhouse'" :class="form.propertyType === 'Townhouse' ? 'type-active' : 'type-inactive'" class="type-btn">Townhouse</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                        <div>
                            <label class="fl">Bedrooms</label>
                            <select x-model="form.bedrooms" class="fi cursor-pointer">
                                <option value="1">Studio / 1 Bed</option>
                                <option value="2">2 Bedrooms</option>
                                <option value="3">3 Bedrooms</option>
                                <option value="4">4 Bedrooms</option>
                                <option value="5+">5+ Bedrooms</option>
                            </select>
                        </div>
                        <div>
                            <label class="fl">Bathrooms</label>
                            <select x-model="form.bathrooms" class="fi cursor-pointer">
                                <option value="1">1 Bathroom</option>
                                <option value="2">2 Bathrooms</option>
                                <option value="3">3 Bathrooms</option>
                                <option value="4+">4+ Bathrooms</option>
                            </select>
                        </div>
                        <div>
                            <label class="fl">Extra rooms</label>
                            <select x-model="form.extraRooms" class="fi cursor-pointer">
                                <option value="0">None</option>
                                <option value="1">1 Extra Room</option>
                                <option value="2">2 Extra Rooms</option>
                                <option value="3">3 Extra Rooms</option>
                                <option value="4+">4+ Extra Rooms</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="fl">When was it last professionally cleaned?</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="form.lastCleaned = 'Never / unsure'" :class="form.lastCleaned === 'Never / unsure' ? 'pill-active' : ''" class="pill-btn">Never / unsure</button>
                            <button type="button" @click="form.lastCleaned = '6+ months ago'" :class="form.lastCleaned === '6+ months ago' ? 'pill-active' : ''" class="pill-btn">6+ months ago</button>
                            <button type="button" @click="form.lastCleaned = '3-6 months ago'" :class="form.lastCleaned === '3-6 months ago' ? 'pill-active' : ''" class="pill-btn">3–6 months ago</button>
                            <button type="button" @click="form.lastCleaned = '1-3 months ago'" :class="form.lastCleaned === '1-3 months ago' ? 'pill-active' : ''" class="pill-btn">1–3 months ago</button>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="fl">Main flooring type(s) <span class="normal-case tracking-normal text-[#c8cdd8]">(select all that apply)</span></label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="ft in floorTypeOptions" :key="ft">
                                <button type="button" @click="toggleFloorType(ft)" class="pill-btn inline-flex items-center gap-1.5" :class="form.floorTypes.includes(ft) ? 'pill-active' : ''">
                                    <span class="w-3.5 h-3.5 rounded border flex items-center justify-center flex-shrink-0"
                                          :class="form.floorTypes.includes(ft) ? 'bg-white border-white' : 'border-[#c9cedb]'">
                                        <svg x-show="form.floorTypes.includes(ft)" class="w-2.5 h-2.5 text-[#081d3a]" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span x-text="ft"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6 bg-[#f8f9fc] rounded-xl px-4 py-3.5">
                        <span class="text-[#081d3a] text-[13.5px] font-semibold">Do you have any pets?</span>
                        <button type="button" @click="form.pets = !form.pets"
                                class="w-11 h-6 rounded-full relative transition-colors flex-shrink-0"
                                :class="form.pets ? 'bg-[#081d3a]' : 'bg-[#dbe0ea]'">
                            <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all" :class="form.pets ? 'left-[22px]' : 'left-0.5'"></span>
                        </button>
                    </div>

                    <div class="mb-7">
                        <label class="fl">Special instructions <span class="normal-case tracking-normal text-[#c8cdd8]">(optional)</span></label>
                        <textarea x-model="form.notes" rows="2" placeholder="Any areas to focus on or avoid..." class="fi resize-none"></textarea>
                    </div>

                    <div class="h-px bg-[#edf0f5] mb-7"></div>

                    <p class="fs-label mb-4">Property address</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="fl">Full name <span class="req">*</span></label>
                            <input type="text" x-model="form.name" @input="errors.name = false" placeholder="Jane Smith" class="fi" :class="errors.name ? 'fi-error' : ''">
                            <p x-show="errors.name" x-cloak class="field-err">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Please enter your name
                            </p>
                        </div>
                        <div>
                            <label class="fl">Phone / WhatsApp <span class="req">*</span></label>
                            <input type="tel" x-model="form.phone" @input="errors.phone = false" placeholder="+27 81 000 0000" class="fi" :class="errors.phone ? 'fi-error' : ''">
                            <p x-show="errors.phone" x-cloak class="field-err">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Please enter a phone number
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="fl">Street address <span class="req">*</span></label>
                            <input type="text" x-model="form.address" @input="errors.address = false" placeholder="e.g. 12 Ocean View Drive" class="fi" :class="errors.address ? 'fi-error' : ''">
                            <p x-show="errors.address" x-cloak class="field-err">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Please enter your street address
                            </p>
                        </div>
                        <div class="relative">
                            <label class="fl">Suburb <span class="req">*</span></label>
                            <input type="text"
                                   x-model="suburbQuery"
                                   @input="filterSuburbs(); errors.suburb = false"
                                   @blur="setTimeout(() => showSuggestions = false, 300)"
                                   @keydown.enter.prevent="checkLocation()"
                                   placeholder="e.g. Milnerton, Table View..."
                                   class="fi" :class="errors.suburb ? 'fi-error' : ''">
                            <div x-show="showSuggestions" x-cloak class="absolute left-0 right-0 top-full mt-1 bg-white border border-[#e8eaf0] rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto">
                                <template x-for="s in filteredSuburbs" :key="s">
                                    <div @click="selectSuburb(s)" class="px-4 py-2.5 text-[13.5px] text-[#4a5568] hover:bg-[#f4f6fb] hover:text-[#081d3a] cursor-pointer" x-text="s"></div>
                                </template>
                            </div>
                            <p x-show="errors.suburb" x-cloak class="field-err">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Please enter your suburb
                            </p>
                            <p x-show="!errors.suburb && locationStatus === 'invalid'" x-cloak class="text-rose-500 text-[12px] mt-1.5">We don't currently service this suburb — we'll still reach out.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div>
                            <label class="fl">Access instructions <span class="normal-case tracking-normal text-[#c8cdd8]">(optional)</span></label>
                            <input type="text" x-model="form.accessInstructions" placeholder="e.g. Ring bell, use side gate..." class="fi">
                        </div>
                        <div>
                            <label class="fl">Parking <span class="normal-case tracking-normal text-[#c8cdd8]">(optional)</span></label>
                            <input type="text" x-model="form.parking" placeholder="Street parking, driveway..." class="fi">
                        </div>
                    </div>

                    <div class="h-px bg-[#edf0f5] mb-7"></div>

                    <p class="fs-label mb-1">Add-on extras</p>
                    <p class="text-[#adb5c4] text-[13px] mb-4">Optional — added to your estimate below.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                        <template x-for="addon in addonsList" :key="addon.key">
                            <div @click="toggleAddon(addon.key)" class="addon-card" :class="selectedAddons.includes(addon.key) ? 'addon-active' : ''">
                                <div class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-md border-2 flex items-center justify-center flex-shrink-0 mt-0.5 transition-colors"
                                         :class="selectedAddons.includes(addon.key) ? 'bg-[#081d3a] border-[#081d3a]' : 'border-[#dbe0ea]'">
                                        <svg x-show="selectedAddons.includes(addon.key)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-[#081d3a] font-bold text-[13.5px]" x-text="addon.label"></span>
                                            <span class="text-[#081d3a] font-bold text-[13px] flex-shrink-0" x-text="'+R' + addon.price"></span>
                                        </div>
                                        <p class="text-[#8a94a6] text-[12px] mt-0.5" x-text="addon.desc"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <p x-show="errors.name || errors.phone || errors.address || errors.suburb" x-cloak
                       class="flex items-center gap-2 bg-rose-50 text-rose-600 text-[13px] font-semibold rounded-lg px-4 py-3 mb-4">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        Please fill in the highlighted fields above to continue.
                    </p>

                    <div class="flex items-center justify-between gap-4">
                        <a href="/#services" class="inline-flex items-center gap-2 text-[#647082] text-[13.5px] font-semibold hover:text-[#081d3a] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to services
                        </a>
                        <button type="button" @click="if (validateStep1()) goStep(2)"
                                class="inline-flex items-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold px-6 py-3.5 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[14px]">
                            Continue
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 2: SCHEDULE ── --}}
                <div x-show="step === 2" x-cloak class="bg-white rounded-3xl border border-[#edf0f5] shadow-sm p-6 sm:p-8">
                    <h2 class="text-[#081d3a] text-[22px] font-black tracking-tight">Choose your date &amp; time</h2>
                    <p class="text-[#adb5c4] text-[13.5px] mt-1 mb-7">We clean on Saturdays &amp; Sundays only, with {{ \App\Models\Booking::DAILY_CAPACITY }} client slots per day — pick a date below.</p>

                    <div x-show="nextAvailable && !isNextAvailableAlreadySelected" x-cloak
                         class="flex items-center justify-between gap-3 bg-[#f8f9fc] border border-[#e8eaf0] rounded-xl px-4 py-3 mb-6">
                        <div class="min-w-0">
                            <p class="text-[#081d3a] text-[13px] font-bold">Next available weekend</p>
                            <p class="text-[#8a94a6] text-[12px]" x-text="nextAvailableLabel"></p>
                        </div>
                        <button type="button" @click="goToNextAvailable()" class="flex-shrink-0 bg-[#081d3a] text-white text-[12.5px] font-bold px-4 py-2 rounded-lg hover:bg-[#0d2a4a] transition-colors">
                            Select this date
                        </button>
                    </div>

                    <div class="mb-7">
                        <label class="fl">Booking type</label>
                        <div class="flex gap-2 max-w-xs">
                            <button type="button" @click="bookingType = 'once-off'" :class="bookingType === 'once-off' ? 'type-active' : 'type-inactive'" class="type-btn">Once-off</button>
                            <button type="button" @click="bookingType = 'recurring'" :class="bookingType === 'recurring' ? 'type-active' : 'type-inactive'" class="type-btn">Recurring</button>
                        </div>
                        <div x-show="bookingType === 'recurring'" x-cloak class="flex gap-2 mt-3">
                            <template x-for="freq in ['Weekly','Bi-weekly','Monthly']" :key="freq">
                                <button type="button" @click="frequency = freq" :class="frequency === freq ? 'pill-active' : ''" class="pill-btn" x-text="freq"></button>
                            </template>
                        </div>
                    </div>

                    <label class="fl">Date &amp; time <span class="req">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="border rounded-2xl p-4" :class="errors.dateTime ? 'fi-error' : 'border-[#edf0f5]'">
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" @click="prevMonth()" class="w-8 h-8 rounded-lg hover:bg-[#f4f6fb] flex items-center justify-center text-[#647082]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <span class="text-[#081d3a] font-bold text-[13.5px]" x-text="calMonthLabel"></span>
                                <button type="button" @click="nextMonth()" class="w-8 h-8 rounded-lg hover:bg-[#f4f6fb] flex items-center justify-center text-[#647082]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-7 gap-1 mb-1">
                                <template x-for="(d, i) in ['M','T','W','T','F','S','S']" :key="i">
                                    <div class="text-center text-[10.5px] font-bold text-[#adb5c4] py-1" x-text="d"></div>
                                </template>
                            </div>
                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="(d, i) in calDays" :key="i">
                                    <div>
                                        <div x-show="d" @click="pickDate(d)" class="cal-cell"
                                             :class="{
                                                'cal-disabled': d && !isSelectable(d) && !isSelected(d),
                                                'cal-full': d && isWeekend(d) && !isPast(d) && isFull(d),
                                                'cal-selected': isSelected(d)
                                             }"
                                             :title="d && isWeekend(d) && !isPast(d) && isFull(d) ? 'Fully booked' : ''"
                                             x-text="d"></div>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center gap-4 mt-3 pt-3 border-t border-[#f0f2f7]">
                                <span class="flex items-center gap-1.5 text-[11px] text-[#8a94a6]"><span class="w-2.5 h-2.5 rounded-full bg-[#081d3a] inline-block"></span> Selected</span>
                                <span class="flex items-center gap-1.5 text-[11px] text-[#8a94a6]"><span class="w-2.5 h-2.5 rounded-full bg-[#fef2f2] border border-[#fca5a5] inline-block"></span> Fully booked</span>
                            </div>
                        </div>

                        <div>
                            <p class="fl mb-3">Pick a time</p>
                            <div x-show="!selectedDate" class="text-[#adb5c4] text-[13px] bg-[#f8f9fc] rounded-xl p-5 text-center">Choose a date first.</div>
                            <div x-show="selectedDate" x-cloak class="grid grid-cols-2 gap-2">
                                <template x-for="t in timeSlots" :key="t">
                                    <div @click="selectedTime = t; errors.dateTime = false" class="time-slot" :class="selectedTime === t ? 'time-selected' : ''" x-text="t"></div>
                                </template>
                            </div>
                            <p class="text-[#adb5c4] text-[12px] mt-4">Need a different time? Mention it in your WhatsApp message and we'll do our best to accommodate.</p>
                        </div>
                    </div>

                    <p x-show="dateError" x-cloak x-text="dateError" class="text-rose-500 text-[12.5px] font-semibold mt-5 bg-rose-50 rounded-lg px-4 py-3"></p>
                    <p x-show="errors.dateTime && !dateError" x-cloak class="field-err mt-5 bg-rose-50 rounded-lg px-4 py-3">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Please select an available Saturday or Sunday and a time.
                    </p>

                    <div class="flex items-center justify-between gap-4 mt-8">
                        <button type="button" @click="goStep(1)" class="inline-flex items-center gap-2 text-[#647082] text-[13.5px] font-semibold hover:text-[#081d3a] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back
                        </button>
                        <button type="button" @click="if (validateStep2()) goStep(3)"
                                class="inline-flex items-center gap-2 bg-[#f6e304] text-[#081d3a] font-bold px-6 py-3.5 rounded-xl hover:bg-yellow-300 active:scale-95 transition-all text-[14px]">
                            Continue
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 3: REVIEW ── --}}
                <div x-show="step === 3" x-cloak class="bg-white rounded-3xl border border-[#edf0f5] shadow-sm p-6 sm:p-8">
                    <h2 class="text-[#081d3a] text-[22px] font-black tracking-tight">Review your booking</h2>
                    <p class="text-[#adb5c4] text-[13.5px] mt-1 mb-7">Everything look right? You can still make changes before you send it.</p>

                    <div class="space-y-4 mb-7">

                        <div class="border border-[#edf0f5] rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="fs-label">Location</span>
                                <button type="button" @click="goStep(1)" class="text-[#081d3a] text-[12.5px] font-bold hover:text-[#f6e304] transition-colors">Edit</button>
                            </div>
                            <p class="text-[#081d3a] font-semibold text-[14px]" x-text="form.address"></p>
                            <p class="text-[#8a94a6] text-[13px]" x-text="selectedSuburb + ', Cape Town'"></p>
                        </div>

                        <div class="border border-[#edf0f5] rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="fs-label">Clean details</span>
                                <button type="button" @click="goStep(1)" class="text-[#081d3a] text-[12.5px] font-bold hover:text-[#f6e304] transition-colors">Edit</button>
                            </div>
                            <div class="grid grid-cols-2 gap-y-2 text-[13.5px]">
                                <span class="text-[#8a94a6]">Property type</span><span class="text-[#081d3a] font-semibold" x-text="form.propertyType"></span>
                                <span class="text-[#8a94a6]">Bedrooms</span><span class="text-[#081d3a] font-semibold" x-text="form.bedrooms"></span>
                                <span class="text-[#8a94a6]">Bathrooms</span><span class="text-[#081d3a] font-semibold" x-text="form.bathrooms"></span>
                                <span class="text-[#8a94a6]">Flooring</span><span class="text-[#081d3a] font-semibold" x-text="form.floorTypes.length ? form.floorTypes.join(', ') : 'Not specified'"></span>
                                <span class="text-[#8a94a6]">Pets on premises</span><span class="text-[#081d3a] font-semibold" x-text="form.pets ? 'Yes' : 'No'"></span>
                            </div>
                        </div>

                        <div class="border border-[#edf0f5] rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="fs-label">Schedule</span>
                                <button type="button" @click="goStep(2)" class="text-[#081d3a] text-[12.5px] font-bold hover:text-[#f6e304] transition-colors">Edit</button>
                            </div>
                            <p class="text-[#081d3a] font-semibold text-[14px]" x-text="selectedDateLabel"></p>
                            <p class="text-[#8a94a6] text-[13px]" x-text="selectedTime + ' · ' + (bookingType === 'recurring' ? ('Recurring — ' + frequency) : 'Once-off')"></p>
                        </div>

                        <div class="border border-[#edf0f5] rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="fs-label">Add-ons</span>
                                <button type="button" @click="goStep(1)" class="text-[#081d3a] text-[12.5px] font-bold hover:text-[#f6e304] transition-colors">Edit</button>
                            </div>
                            <p x-show="selectedAddons.length === 0" class="text-[#8a94a6] text-[13.5px]">No add-ons selected.</p>
                            <ul x-show="selectedAddons.length > 0" class="space-y-1.5">
                                <template x-for="key in selectedAddons" :key="key">
                                    <li class="flex items-center justify-between text-[13.5px]">
                                        <span class="text-[#081d3a]" x-text="addonsList.find(a => a.key === key)?.label"></span>
                                        <span class="text-[#081d3a] font-semibold" x-text="'+R' + addonsList.find(a => a.key === key)?.price"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <button type="button" @click="goStep(2)" class="inline-flex items-center gap-2 text-[#647082] text-[13.5px] font-semibold hover:text-[#081d3a] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back
                        </button>
                        <button type="button" @click="submitForm()" :disabled="checkingDate"
                                class="inline-flex items-center gap-2 bg-[#25d366] text-white font-bold px-6 py-3.5 rounded-xl hover:bg-[#22c05e] active:scale-95 transition-all text-[14px] disabled:opacity-60 disabled:cursor-wait">
                            <svg x-show="!checkingDate" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span x-text="checkingDate ? 'Checking availability…' : 'Send Booking Request'"></span>
                        </button>
                    </div>
                </div>

                </div>
            </div>

            {{-- ══ RIGHT: STICKY SUMMARY ══ --}}
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-28 bg-white rounded-3xl border border-[#edf0f5] shadow-sm p-6 sm:p-7">

                    <div class="flex items-center gap-3 pb-5 border-b border-[#edf0f5]">
                        <div class="w-11 h-11 rounded-xl bg-[#081d3a] flex items-center justify-center flex-shrink-0">
                            <svg x-show="service.icon === 'deep'" class="w-5 h-5 text-[#f6e304]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <svg x-show="service.icon === 'eot'" class="w-5 h-5 text-[#f6e304]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <svg x-show="service.icon === 'construction'" class="w-5 h-5 text-[#f6e304]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <p class="text-[#081d3a] font-black text-[16px] tracking-tight" x-text="service.name"></p>
                            <p class="text-[#8a94a6] text-[12px]" x-text="'Est. ' + service.avg_hours + ' hrs'"></p>
                        </div>
                    </div>

                    <div x-show="step >= 1 && form.address" x-cloak class="summary-row">
                        <div class="summary-icon">
                            <svg class="w-4 h-4 text-[#647082]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="fs-label mb-1">Location</p>
                            <p class="text-[#081d3a] font-semibold text-[13.5px] truncate" x-text="form.address"></p>
                            <p class="text-[#8a94a6] text-[12.5px]" x-text="selectedSuburb"></p>
                        </div>
                    </div>

                    <div x-show="selectedDate" x-cloak class="summary-row">
                        <div class="summary-icon">
                            <svg class="w-4 h-4 text-[#647082]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="fs-label mb-1">Date &amp; time</p>
                            <p class="text-[#081d3a] font-semibold text-[13.5px]" x-text="selectedDateLabel"></p>
                            <p class="text-[#8a94a6] text-[12.5px]" x-text="selectedTime"></p>
                        </div>
                    </div>

                    <div class="pt-5">
                        <p class="fs-label mb-3">Price breakdown</p>
                        <div class="space-y-2 text-[13.5px]">
                            <div class="flex items-center justify-between">
                                <span class="text-[#647082]" x-text="service.name + ' (base)'"></span>
                                <span class="text-[#081d3a] font-semibold" x-text="'R' + service.base_price.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center justify-between" x-show="extraBedroomCost > 0" x-cloak>
                                <span class="text-[#647082]" x-text="bedroomCount + ' bedrooms'"></span>
                                <span class="text-[#081d3a] font-semibold" x-text="'R' + extraBedroomCost.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center justify-between" x-show="extraBathroomCost > 0" x-cloak>
                                <span class="text-[#647082]" x-text="bathroomCount + ' bathrooms'"></span>
                                <span class="text-[#081d3a] font-semibold" x-text="'R' + extraBathroomCost.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center justify-between" x-show="extraRoomsCost > 0" x-cloak>
                                <span class="text-[#647082]" x-text="extraRoomsCount + ' extra room(s)'"></span>
                                <span class="text-[#081d3a] font-semibold" x-text="'R' + extraRoomsCost.toLocaleString()"></span>
                            </div>
                            <template x-for="key in selectedAddons" :key="key">
                                <div class="flex items-center justify-between">
                                    <span class="text-[#647082]" x-text="addonsList.find(a => a.key === key)?.label"></span>
                                    <span class="text-[#081d3a] font-semibold" x-text="'R' + addonsList.find(a => a.key === key)?.price"></span>
                                </div>
                            </template>
                            <div class="flex items-center justify-between">
                                <span class="text-[#647082]">Service fee</span>
                                <span class="text-[#081d3a] font-semibold" x-text="'R' + service.service_fee"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 bg-[#f8f9fc] rounded-2xl p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-[#081d3a] font-black text-[15px]">Estimated total</span>
                            <span class="text-[#081d3a] font-black text-[26px] tracking-tight" x-text="'R' + total.toLocaleString()"></span>
                        </div>
                        <p class="text-[#adb5c4] text-[11.5px] mt-2 leading-relaxed">
                            This is an estimate only. Prices may be adjusted after a quick property check — never without your sign-off, and never a surprise.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mt-5">
                        <div class="text-center">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center mx-auto mb-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <p class="text-[#081d3a] text-[10.5px] font-bold leading-tight">Vetted<br>cleaners</p>
                        </div>
                        <div class="text-center">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mx-auto mb-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-7 8h8a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-[#081d3a] text-[10.5px] font-bold leading-tight">Transparent<br>pricing</p>
                        </div>
                        <div class="text-center">
                            <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mx-auto mb-1.5">
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.951.69h4.914c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.575 9.101c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <p class="text-[#081d3a] text-[10.5px] font-bold leading-tight">Satisfaction<br>guarantee</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>
</div>

@include('components.footer')
@endsection
