@extends('admin.layout')
@section('title', 'New Booking')

@push('styles')
<style>
    .fi {
        width: 100%; background: #fff; border: 1.5px solid #e8eaf0; border-radius: 10px;
        padding: 11px 14px; color: #081d3a; font-size: 13.5px;
        transition: border-color 0.2s, box-shadow 0.2s; appearance: none;
    }
    .fi::placeholder { color: #adb5c4; }
    .fi:focus { outline: none; border-color: #081d3a; box-shadow: 0 0 0 3px rgba(8,29,58,0.07); }
    .fi-error { border-color: #fca5a5 !important; }
    .fl { display: block; font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #8a94a6; margin-bottom: 7px; }
    .type-btn { flex: 1; padding: 9px 10px; border-radius: 9px; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all 0.15s; border: 1.5px solid #e8eaf0; text-align: center; }
    .type-inactive { background: #fff; color: #8a94a6; }
    .type-active { background: #081d3a; color: #fff; border-color: #081d3a; }
    .pill-btn { padding: 8px 13px; border-radius: 999px; font-size: 12px; font-weight: 600; border: 1.5px solid #e8eaf0; cursor: pointer; transition: all 0.15s; color: #647082; background: #fff; white-space: nowrap; }
    .pill-active { background: #081d3a; color: #fff; border-color: #081d3a; }
    .addon-card { border: 1.5px solid #e8eaf0; border-radius: 12px; padding: 12px 14px; cursor: pointer; transition: all 0.15s; background: #fff; }
    .addon-card:hover { border-color: #c9cedb; }
    .addon-active { border-color: #081d3a; background: #f8f9fc; }
    .cal-cell { width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 9px; font-size: 12.5px; font-weight: 600; color: #081d3a; cursor: pointer; transition: all 0.15s; }
    .cal-cell:hover:not(.cal-disabled):not(.cal-selected) { background: #f0f2f7; }
    .cal-disabled { color: #d5d9e2; cursor: not-allowed; }
    .cal-disabled.cal-full { background: #fef2f2; color: #fca5a5; }
    .cal-selected { background: #081d3a; color: #fff; }
    .time-slot { padding: 9px 6px; border-radius: 9px; border: 1.5px solid #e8eaf0; text-align: center; font-size: 12.5px; font-weight: 600; color: #081d3a; cursor: pointer; transition: all 0.15s; background: #fff; }
    .time-slot:hover { border-color: #c9cedb; }
    .time-selected { background: #081d3a; color: #fff; border-color: #081d3a; }
    .time-taken { background: #f8f9fc; color: #d5d9e2; cursor: not-allowed; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="{
        step: 1,
        totalSteps: 6,
        stepLabels: ['Service & Property', 'Client & Location', 'Add-ons', 'Notes', 'Schedule', 'Review'],

        services: {{ Illuminate\Support\Js::from($services) }},
        selectedService: {{ Illuminate\Support\Js::from(old('service', array_key_first($services))) }},
        get service() { return this.services[this.selectedService]; },
        addonsList: {{ Illuminate\Support\Js::from($addons) }},
        timeSlots: {{ Illuminate\Support\Js::from($timeSlots) }},

        name: {{ Illuminate\Support\Js::from(old('name', '')) }},
        phone: {{ Illuminate\Support\Js::from(old('phone', '')) }},
        address: {{ Illuminate\Support\Js::from(old('address', '')) }},
        suburb: {{ Illuminate\Support\Js::from(old('suburb', '')) }},
        propertyType: {{ Illuminate\Support\Js::from(old('property_type', 'House')) }},
        bedrooms: {{ Illuminate\Support\Js::from(old('bedrooms', '2')) }},
        bathrooms: {{ Illuminate\Support\Js::from(old('bathrooms', '1')) }},
        extraRooms: {{ Illuminate\Support\Js::from(old('extra_rooms', '0')) }},
        lastCleaned: {{ Illuminate\Support\Js::from(old('last_cleaned', '')) }},
        floorTypes: [], pets: false,

        floorTypeOptions: ['Carpet', 'Tile', 'Hardwood', 'Laminate', 'Vinyl', 'Epoxy'],
        toggleFloorType(t) { this.floorTypes.includes(t) ? this.floorTypes = this.floorTypes.filter(x => x !== t) : this.floorTypes.push(t); },
        selectedAddons: [],
        toggleAddon(key) { this.selectedAddons.includes(key) ? this.selectedAddons = this.selectedAddons.filter(k => k !== key) : this.selectedAddons.push(key); },

        bookingType: {{ Illuminate\Support\Js::from(old('booking_type', 'once-off')) }},
        frequency: {{ Illuminate\Support\Js::from(old('frequency') ?: 'Weekly') }},

        bedroomMap: {'1':1,'2':2,'3':3,'4':4,'5+':5},
        bathroomMap: {'1':1,'2':2,'3':3,'4+':4},
        roomMap: {'0':0,'1':1,'2':2,'3':3,'4+':4},
        get bedroomCount() { return this.bedroomMap[this.bedrooms] ?? 0 },
        get bathroomCount() { return this.bathroomMap[this.bathrooms] ?? 0 },
        get extraRoomsCount() { return this.roomMap[this.extraRooms] ?? 0 },
        get extraBedroomCost() { return Math.max(0, this.bedroomCount - this.service.included_bedrooms) * this.service.bedroom_price },
        get extraBathroomCost() { return Math.max(0, this.bathroomCount - this.service.included_bathrooms) * this.service.bathroom_price },
        get extraRoomsCost() { return this.extraRoomsCount * this.service.extra_room_price },
        get addonsCost() { return this.selectedAddons.reduce((sum, key) => { const a = this.addonsList.find(x => x.key === key); return sum + (a ? a.price : 0); }, 0) },
        get subtotal() { return this.service.base_price + this.extraBedroomCost + this.extraBathroomCost + this.extraRoomsCost + this.addonsCost },
        get total() { return this.subtotal + this.service.service_fee },

        calMonth: (new Date()).getMonth(), calYear: (new Date()).getFullYear(),
        selectedDate: null, selectedTime: null,
        availability: {}, bookedTimes: {}, capacity: 2, nextAvailable: null,

        init() { this.fetchAvailability(); },

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
        isPast(d) { if (!d) return false; const dt = new Date(this.calYear, this.calMonth, d); const today = new Date(); today.setHours(0,0,0,0); return dt < today; },
        isWeekend(d) { if (!d) return false; const dow = new Date(this.calYear, this.calMonth, d).getDay(); return dow === 0 || dow === 6; },
        dateISO(date) { const y = date.getFullYear(); const m = String(date.getMonth() + 1).padStart(2, '0'); const dd = String(date.getDate()).padStart(2, '0'); return y + '-' + m + '-' + dd; },
        dateKey(d) { return this.dateISO(new Date(this.calYear, this.calMonth, d)); },
        bookedCount(d) { return this.availability[this.dateKey(d)] || 0; },
        isFull(d) { return d ? this.bookedCount(d) >= this.capacity : false; },
        isSelectable(d) { return !!d && this.isWeekend(d) && !this.isPast(d) && !this.isFull(d); },
        isSelected(d) { if (!d || !this.selectedDate) return false; return this.selectedDate.getFullYear() === this.calYear && this.selectedDate.getMonth() === this.calMonth && this.selectedDate.getDate() === d; },
        isTimeTaken(t) { if (!this.selectedDate) return false; return (this.bookedTimes[this.dateISO(this.selectedDate)] || []).includes(t); },
        prevMonth() { this.calMonth--; if (this.calMonth < 0) { this.calMonth = 11; this.calYear--; } this.fetchAvailability(); },
        nextMonth() { this.calMonth++; if (this.calMonth > 11) { this.calMonth = 0; this.calYear++; } this.fetchAvailability(); },
        pickDate(d) { if (!this.isSelectable(d)) return; this.selectedDate = new Date(this.calYear, this.calMonth, d); this.selectedTime = null; this.errors.dateTime = false; },
        get selectedDateLabel() { return this.selectedDate ? this.selectedDate.toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) : ''; },

        async fetchAvailability() {
            const month = this.calYear + '-' + String(this.calMonth + 1).padStart(2, '0') + '-01';
            try {
                const res = await fetch('{{ route('booking.availability') }}?month=' + month);
                const json = await res.json();
                this.availability = json.counts || {};
                this.bookedTimes = json.times || {};
                this.capacity = json.capacity || this.capacity;
                this.nextAvailable = json.next_available || null;
                if (this.selectedTime && this.isTimeTaken(this.selectedTime)) this.selectedTime = null;
            } catch (e) {}
        },
        get nextAvailableLabel() {
            if (!this.nextAvailable) return '';
            const [y, m, d] = this.nextAvailable.split('-').map(Number);
            return new Date(y, m - 1, d).toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long' });
        },
        goToNextAvailable() {
            if (!this.nextAvailable) return;
            const [y, m, d] = this.nextAvailable.split('-').map(Number);
            this.calYear = y; this.calMonth = m - 1;
            this.selectedDate = new Date(y, m - 1, d);
            this.selectedTime = null;
            this.errors.dateTime = false;
            this.fetchAvailability();
        },

        errors: { name: false, phone: false, dateTime: false },
        validateClientStep() {
            this.errors.name = !this.name.trim();
            this.errors.phone = !this.phone.trim();
            return !this.errors.name && !this.errors.phone;
        },
        validateScheduleStep() {
            this.errors.dateTime = !this.selectedDate || !this.selectedTime;
            return !this.errors.dateTime;
        },
        goStep(n) { this.step = n; window.scrollTo({ top: 0, behavior: 'smooth' }); },
     }">

    <div class="flex items-center gap-2 text-label text-[12px] font-medium mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="hover:text-navy transition-colors">Bookings</a>
        <span>/</span>
        <span class="text-ink">New Booking</span>
    </div>

    <div class="mb-6">
        <p class="text-label text-[11px] uppercase tracking-wider font-semibold mb-1">Pipeline</p>
        <h1 class="text-[26px] font-extrabold tracking-tight leading-none">Record a Booking</h1>
        <p class="text-muted text-[13px] mt-1.5">For leads that came in by phone, WhatsApp, or in person — same fields and pricing as the website, same 2-per-day / 4-per-weekend capacity.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Step progress --}}
    <div class="mb-6 max-w-2xl">
        <div class="flex items-center justify-between mb-2">
            <span class="text-ink text-[13px] font-bold" x-text="'Step ' + step + ' of ' + totalSteps + ': ' + stepLabels[step - 1]"></span>
            <button type="button" x-show="step > 1" x-cloak @click="goStep(step - 1)" class="text-muted text-[12px] font-semibold hover:text-navy transition-colors">&larr; Back</button>
        </div>
        <div class="w-full h-1.5 bg-line rounded-full overflow-hidden">
            <div class="h-full bg-navy rounded-full transition-all duration-300" :style="'width:' + (step / totalSteps * 100) + '%'"></div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.bookings.store') }}">
        @csrf

        <input type="hidden" name="service" :value="selectedService">
        <input type="hidden" name="name" :value="name">
        <input type="hidden" name="phone" :value="phone">
        <input type="hidden" name="property_type" :value="propertyType">
        <input type="hidden" name="bedrooms" :value="bedrooms">
        <input type="hidden" name="bathrooms" :value="bathrooms">
        <input type="hidden" name="extra_rooms" :value="extraRooms">
        <input type="hidden" name="last_cleaned" :value="lastCleaned">
        <input type="hidden" name="pets" :value="pets ? 1 : 0">
        <input type="hidden" name="booking_type" :value="bookingType">
        <input type="hidden" name="frequency" :value="bookingType === 'recurring' ? frequency : ''">
        <input type="hidden" name="date" :value="selectedDate ? dateISO(selectedDate) : ''">
        <input type="hidden" name="time" :value="selectedTime || ''">
        <input type="hidden" name="subtotal" :value="subtotal">
        <input type="hidden" name="total" :value="total">
        <template x-for="t in floorTypes" :key="t"><input type="hidden" name="floor_types[]" :value="t"></template>
        <template x-for="key in selectedAddons" :key="key"><input type="hidden" name="addons[]" :value="key"></template>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- LEFT: form --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- ── STEP 1: SERVICE & PROPERTY ── --}}
                <div x-show="step === 1" x-cloak class="space-y-6">
                    <div class="card p-6">
                        <h2 class="font-bold text-[14px] tracking-tight mb-4">Service</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-5">
                            <template x-for="(svc, slug) in services" :key="slug">
                                <button type="button" @click="selectedService = slug"
                                        class="type-btn" :class="selectedService === slug ? 'type-active' : 'type-inactive'"
                                        x-text="svc.name"></button>
                            </template>
                        </div>

                        <label class="fl">Property type</label>
                        <div class="flex gap-2 mb-5">
                            <button type="button" @click="propertyType = 'House'" :class="propertyType === 'House' ? 'type-active' : 'type-inactive'" class="type-btn">House</button>
                            <button type="button" @click="propertyType = 'Apartment / Flat'" :class="propertyType === 'Apartment / Flat' ? 'type-active' : 'type-inactive'" class="type-btn">Apartment / Flat</button>
                            <button type="button" @click="propertyType = 'Townhouse'" :class="propertyType === 'Townhouse' ? 'type-active' : 'type-inactive'" class="type-btn">Townhouse</button>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <div>
                                <label class="fl">Bedrooms</label>
                                <select x-model="bedrooms" class="fi cursor-pointer">
                                    <option value="1">Studio / 1</option>
                                    <option value="2">2 Bedrooms</option>
                                    <option value="3">3 Bedrooms</option>
                                    <option value="4">4 Bedrooms</option>
                                    <option value="5+">5+ Bedrooms</option>
                                </select>
                            </div>
                            <div>
                                <label class="fl">Bathrooms</label>
                                <select x-model="bathrooms" class="fi cursor-pointer">
                                    <option value="1">1 Bathroom</option>
                                    <option value="2">2 Bathrooms</option>
                                    <option value="3">3 Bathrooms</option>
                                    <option value="4+">4+ Bathrooms</option>
                                </select>
                            </div>
                            <div>
                                <label class="fl">Extra rooms</label>
                                <select x-model="extraRooms" class="fi cursor-pointer">
                                    <option value="0">None</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4+">4+</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="fl">Last professionally cleaned</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="opt in ['Never / unsure', '6+ months ago', '3-6 months ago', '1-3 months ago']" :key="opt">
                                    <button type="button" @click="lastCleaned = opt" :class="lastCleaned === opt ? 'pill-active' : ''" class="pill-btn" x-text="opt"></button>
                                </template>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="fl">Flooring <span class="normal-case font-normal text-label">(select all that apply)</span></label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="ft in floorTypeOptions" :key="ft">
                                    <button type="button" @click="toggleFloorType(ft)" class="pill-btn" :class="floorTypes.includes(ft) ? 'pill-active' : ''" x-text="ft"></button>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between bg-canvas rounded-xl px-4 py-3">
                            <span class="text-ink text-[13px] font-semibold">Pets on premises?</span>
                            <button type="button" @click="pets = !pets" class="w-11 h-6 rounded-full relative transition-colors flex-shrink-0" :class="pets ? 'bg-navy' : 'bg-[#dbe0ea]'">
                                <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all" :class="pets ? 'left-[22px]' : 'left-0.5'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="goStep(2)" class="btn-primary px-6 py-3">
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 2: CLIENT & LOCATION ── --}}
                <div x-show="step === 2" x-cloak class="space-y-6">
                    <div class="card p-6">
                        <h2 class="font-bold text-[14px] tracking-tight mb-4">Client &amp; Location</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="fl">Name</label>
                                <input type="text" x-model="name" @input="errors.name = false" class="fi" :class="errors.name ? 'fi-error' : ''">
                                <p x-show="errors.name" x-cloak class="text-rose-500 text-[12px] font-semibold mt-1.5">Please enter a name</p>
                            </div>
                            <div>
                                <label class="fl">Phone</label>
                                <input type="text" x-model="phone" @input="errors.phone = false" placeholder="081 234 5678" class="fi" :class="errors.phone ? 'fi-error' : ''">
                                <p x-show="errors.phone" x-cloak class="text-rose-500 text-[12px] font-semibold mt-1.5">Please enter a phone number</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="fl">Street address</label>
                                <input type="text" name="address" x-model="address" class="fi">
                            </div>
                            <div>
                                <label class="fl">Suburb</label>
                                <input type="text" name="suburb" x-model="suburb" list="suburbs-list" class="fi">
                                <datalist id="suburbs-list">
                                    @foreach (['Milnerton','Sunningdale','Blouberg','Parklands','Century City','Table View','Big Bay','Bloubergstrand','West Beach','Monte Vista','Edgemead','Bothasig','Richwood','Burgundy Estate','Flamingo Vlei','Sandown','Sunset Beach','Parklands North','Waves Edge','Montague Gardens','Blouber Rise','Summer Greens','Rugby','Paarden Eiland','Marconi Beam','Dunoon','Joe Slovo Park','Penhill','Kerria','Ravensmead'] as $s)
                                        <option value="{{ $s }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="fl">Access instructions</label>
                                <input type="text" name="access_instructions" value="{{ old('access_instructions') }}" class="fi">
                            </div>
                            <div>
                                <label class="fl">Parking</label>
                                <input type="text" name="parking" value="{{ old('parking') }}" class="fi">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="if (validateClientStep()) goStep(3)" class="btn-primary px-6 py-3">
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 3: ADD-ONS ── --}}
                <div x-show="step === 3" x-cloak class="space-y-6">
                    <div class="card p-6">
                        <h2 class="font-bold text-[14px] tracking-tight mb-1">Add-on extras</h2>
                        <p class="text-label text-[12.5px] mb-4">Optional — added to the estimate.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="addon in addonsList" :key="addon.key">
                                <div @click="toggleAddon(addon.key)" class="addon-card" :class="selectedAddons.includes(addon.key) ? 'addon-active' : ''">
                                    <div class="flex items-start gap-3">
                                        <div class="w-5 h-5 rounded-md border-2 flex items-center justify-center flex-shrink-0 mt-0.5" :class="selectedAddons.includes(addon.key) ? 'bg-navy border-navy' : 'border-[#dbe0ea]'">
                                            <svg x-show="selectedAddons.includes(addon.key)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-ink font-bold text-[13px]" x-text="addon.label"></span>
                                                <span class="text-ink font-bold text-[12.5px]" x-text="'+R' + addon.price"></span>
                                            </div>
                                            <p class="text-label text-[11.5px] mt-0.5" x-text="addon.desc"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="goStep(4)" class="btn-primary px-6 py-3">
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 4: NOTES ── --}}
                <div x-show="step === 4" x-cloak class="space-y-6">
                    <div class="card p-6">
                        <h2 class="font-bold text-[14px] tracking-tight mb-4">Notes</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="fl">Client notes</label>
                                <textarea name="notes" rows="3" class="fi resize-none">{{ old('notes') }}</textarea>
                            </div>
                            <div>
                                <label class="fl">Internal admin notes</label>
                                <textarea name="admin_notes" rows="3" placeholder="e.g. Called in on 03 Jul, referred by an existing client" class="fi resize-none">{{ old('admin_notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="goStep(5)" class="btn-primary px-6 py-3">
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 5: SCHEDULE ── --}}
                <div x-show="step === 5" x-cloak class="space-y-6">
                    <div class="card p-6">
                        <h2 class="font-bold text-[14px] tracking-tight mb-4">Schedule</h2>

                        <div class="mb-5">
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

                        <div x-show="nextAvailable" x-cloak class="flex items-center justify-between gap-3 bg-canvas border border-line rounded-xl px-4 py-3 mb-5">
                            <div class="min-w-0">
                                <p class="text-ink text-[12.5px] font-bold">Next available weekend</p>
                                <p class="text-label text-[11.5px]" x-text="nextAvailableLabel"></p>
                            </div>
                            <button type="button" @click="goToNextAvailable()" class="flex-shrink-0 bg-navy text-white text-[11.5px] font-bold px-3.5 py-2 rounded-lg hover:bg-[#0d2a4a] transition-colors">Select</button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="border rounded-2xl p-4" :class="errors.dateTime ? 'fi-error' : 'border-line'">
                                <div class="flex items-center justify-between mb-3">
                                    <button type="button" @click="prevMonth()" class="w-7 h-7 rounded-lg hover:bg-canvas flex items-center justify-center text-muted">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <span class="text-ink font-bold text-[12.5px]" x-text="calMonthLabel"></span>
                                    <button type="button" @click="nextMonth()" class="w-7 h-7 rounded-lg hover:bg-canvas flex items-center justify-center text-muted">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-7 gap-1 mb-1">
                                    <template x-for="(d, i) in ['M','T','W','T','F','S','S']" :key="i">
                                        <div class="text-center text-[10px] font-bold text-label py-1" x-text="d"></div>
                                    </template>
                                </div>
                                <div class="grid grid-cols-7 gap-1">
                                    <template x-for="(d, i) in calDays" :key="i">
                                        <div>
                                            <div x-show="d" @click="pickDate(d)" class="cal-cell"
                                                 :class="{ 'cal-disabled': d && !isSelectable(d) && !isSelected(d), 'cal-full': d && isWeekend(d) && !isPast(d) && isFull(d), 'cal-selected': isSelected(d) }"
                                                 :title="d && isWeekend(d) && !isPast(d) && isFull(d) ? 'Fully booked' : ''" x-text="d"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <p class="fl mb-2">Pick a time</p>
                                <div x-show="!selectedDate" class="text-label text-[12.5px] bg-canvas rounded-xl p-4 text-center">Choose a date first.</div>
                                <div x-show="selectedDate" x-cloak class="grid grid-cols-2 gap-2">
                                    <template x-for="t in timeSlots" :key="t">
                                        <div @click="if (!isTimeTaken(t)) { selectedTime = t; errors.dateTime = false; }" class="time-slot" :class="isTimeTaken(t) ? 'time-taken' : (selectedTime === t ? 'time-selected' : '')" :title="isTimeTaken(t) ? 'Already booked' : ''" x-text="t"></div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <p x-show="errors.dateTime" x-cloak class="text-rose-500 text-[12.5px] font-semibold mt-4 bg-rose-50 rounded-lg px-4 py-3">Please select an available Saturday or Sunday and a time.</p>
                        <p class="text-label text-[11.5px] mt-4">Weekends only, {{ \App\Models\Booking::DAILY_CAPACITY }} slots/day — same rule as the website.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="if (validateScheduleStep()) goStep(6)" class="btn-primary px-6 py-3">
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 6: REVIEW ── --}}
                <div x-show="step === 6" x-cloak class="space-y-6">
                    <div class="card p-6">
                        <h2 class="font-bold text-[14px] tracking-tight mb-4">Review</h2>

                        <div class="space-y-3">
                            <div class="border border-line rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="fl mb-0">Client</span>
                                    <button type="button" @click="goStep(2)" class="text-navy text-[12px] font-bold hover:opacity-70 transition-opacity">Edit</button>
                                </div>
                                <p class="text-ink font-semibold text-[13.5px]" x-text="name"></p>
                                <p class="text-muted text-[12.5px]" x-text="phone"></p>
                                <p class="text-muted text-[12.5px]" x-show="address" x-text="address + (suburb ? ', ' + suburb : '')"></p>
                            </div>

                            <div class="border border-line rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="fl mb-0">Clean details</span>
                                    <button type="button" @click="goStep(1)" class="text-navy text-[12px] font-bold hover:opacity-70 transition-opacity">Edit</button>
                                </div>
                                <div class="grid grid-cols-2 gap-y-1.5 text-[13px]">
                                    <span class="text-muted">Service</span><span class="text-ink font-semibold" x-text="service.name"></span>
                                    <span class="text-muted">Property type</span><span class="text-ink font-semibold" x-text="propertyType"></span>
                                    <span class="text-muted">Bedrooms / Bathrooms</span><span class="text-ink font-semibold" x-text="bedrooms + ' / ' + bathrooms"></span>
                                </div>
                            </div>

                            <div class="border border-line rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="fl mb-0">Add-ons</span>
                                    <button type="button" @click="goStep(3)" class="text-navy text-[12px] font-bold hover:opacity-70 transition-opacity">Edit</button>
                                </div>
                                <p class="text-ink font-semibold text-[13.5px]" x-text="selectedAddons.length ? selectedAddons.length + ' selected' : 'None'"></p>
                            </div>

                            <div class="border border-line rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="fl mb-0">Schedule</span>
                                    <button type="button" @click="goStep(5)" class="text-navy text-[12px] font-bold hover:opacity-70 transition-opacity">Edit</button>
                                </div>
                                <p class="text-ink font-semibold text-[13.5px]" x-text="selectedDateLabel"></p>
                                <p class="text-muted text-[12.5px]" x-text="selectedTime + ' · ' + (bookingType === 'recurring' ? ('Recurring — ' + frequency) : 'Once-off')"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary px-6 py-3">Add Booking</button>
                    </div>
                </div>

            </div>

            {{-- RIGHT: sticky summary --}}
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-24 card p-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-line">
                        <div class="w-10 h-10 rounded-xl bg-navy flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <div>
                            <p class="text-ink font-extrabold text-[15px] tracking-tight" x-text="service.name"></p>
                            <p class="text-label text-[11.5px]" x-text="'Est. ' + service.avg_hours + ' hrs'"></p>
                        </div>
                    </div>

                    <div x-show="address" x-cloak class="py-3.5 border-b border-line">
                        <p class="fl mb-1">Location</p>
                        <p class="text-ink font-semibold text-[13px]" x-text="address"></p>
                        <p class="text-label text-[12px]" x-text="suburb"></p>
                    </div>

                    <div x-show="selectedDate" x-cloak class="py-3.5 border-b border-line">
                        <p class="fl mb-1">Date &amp; time</p>
                        <p class="text-ink font-semibold text-[13px]" x-text="selectedDateLabel"></p>
                        <p class="text-label text-[12px]" x-text="selectedTime || 'No time selected'"></p>
                    </div>

                    <div class="pt-4">
                        <p class="fl mb-3">Price breakdown</p>
                        <div class="space-y-2 text-[13px]">
                            <div class="flex items-center justify-between">
                                <span class="text-muted" x-text="service.name + ' (base)'"></span>
                                <span class="text-ink font-semibold" x-text="'R' + service.base_price.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center justify-between" x-show="extraBedroomCost > 0" x-cloak>
                                <span class="text-muted" x-text="bedroomCount + ' bedrooms'"></span>
                                <span class="text-ink font-semibold" x-text="'R' + extraBedroomCost.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center justify-between" x-show="extraBathroomCost > 0" x-cloak>
                                <span class="text-muted" x-text="bathroomCount + ' bathrooms'"></span>
                                <span class="text-ink font-semibold" x-text="'R' + extraBathroomCost.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center justify-between" x-show="extraRoomsCost > 0" x-cloak>
                                <span class="text-muted" x-text="extraRoomsCount + ' extra room(s)'"></span>
                                <span class="text-ink font-semibold" x-text="'R' + extraRoomsCost.toLocaleString()"></span>
                            </div>
                            <template x-for="key in selectedAddons" :key="key">
                                <div class="flex items-center justify-between">
                                    <span class="text-muted" x-text="addonsList.find(a => a.key === key)?.label"></span>
                                    <span class="text-ink font-semibold" x-text="'R' + addonsList.find(a => a.key === key)?.price"></span>
                                </div>
                            </template>
                            <div class="flex items-center justify-between">
                                <span class="text-muted">Service fee</span>
                                <span class="text-ink font-semibold" x-text="'R' + service.service_fee"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 bg-canvas rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-ink font-black text-[14px]">Estimated total</span>
                            <span class="text-ink font-black text-[22px] tracking-tight" x-text="'R' + total.toLocaleString()"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
