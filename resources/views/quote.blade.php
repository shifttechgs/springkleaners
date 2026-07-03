@extends('layouts.app')

@section('title', 'Get My Quote — SpringKleaners | Free Cleaning Estimate Cape Town')

@push('styles')
<style>
    body { background-color: #0d1b33; }

    h1, h2, h3, h4 { font-family: 'Geist', sans-serif !important; }

    /* ── Hero ── */
    .quote-hero {
        background-color: #0d1b33;
        min-height: 52vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .quote-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 70% 80% at 50% 120%, rgba(246,227,4,0.045) 0%, transparent 65%);
        pointer-events: none;
    }

    /* ── Content area ── */
    .quote-body {
        background: #f8f9fc;
    }

    /* ── Form inputs (light bg version) ── */
    .fi {
        width: 100%;
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 12px;
        padding: 14px 18px;
        color: #081d3a;
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        letter-spacing: -0.01em;
        appearance: none;
    }
    .fi::placeholder { color: #adb5c4; }
    .fi:focus {
        outline: none;
        border-color: #081d3a;
        box-shadow: 0 0 0 3px rgba(8,29,58,0.07);
    }
    .fi option { background: #fff; color: #081d3a; }

    .fl {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        color: #8a94a6;
        margin-bottom: 7px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Suburb dropdown ── */
    .suburb-drop {
        position: absolute; left: 0; right: 0; top: calc(100% + 5px);
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 14px;
        z-index: 9999;
        max-height: 260px;
        overflow-y: auto;
        box-shadow: 0 16px 40px rgba(8,29,58,0.18);
    }
    .suburb-drop::-webkit-scrollbar { width: 4px; }
    .suburb-drop::-webkit-scrollbar-thumb { background: #e0e4ed; border-radius: 2px; }
    .suburb-opt {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 16px;
        font-size: 13.5px;
        color: #4a5568;
        cursor: pointer;
        transition: background 0.12s, color 0.12s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .suburb-opt:hover { background: #f4f6fb; color: #081d3a; }

    /* ── Type toggle ── */
    .type-btn {
        flex: 1; padding: 11px 12px; border-radius: 10px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        transition: all 0.18s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid #e8eaf0;
    }
    .type-inactive { background: #fff; color: #8a94a6; }
    .type-active   { background: #081d3a; color: #fff; border-color: #081d3a; }

    /* ── Submit button ── */
    .submit-btn {
        width: 100%;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        background: #f6e304;
        color: #081d3a;
        font-weight: 800; font-size: 15px;
        padding: 16px 24px;
        border-radius: 14px;
        border: none; cursor: pointer;
        transition: all 0.2s;
        letter-spacing: -0.02em;
        font-family: 'Geist', sans-serif;
    }
    .submit-btn:hover {
        background: #fef08a;
        box-shadow: 0 8px 30px rgba(246,227,4,0.35);
        transform: translateY(-1px);
    }
    .submit-btn:active { transform: scale(0.97); }

    /* ── Info side ── */
    .info-divider { height: 1px; background: #e8eaf0; }
    .contact-row {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid #edf0f5;
    }
    .contact-row:last-child { border-bottom: none; }
    .icon-chip {
        width: 42px; height: 42px; border-radius: 11px;
        background: #081d3a;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ── Step numbers ── */
    .step-circle {
        width: 30px; height: 30px; border-radius: 50%;
        background: #081d3a;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 800;
        color: #f6e304;
        flex-shrink: 0;
        font-family: 'Geist', sans-serif;
    }

    /* ── Animations ── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .au  { animation: fade-up 0.75s cubic-bezier(0.22,1,0.36,1) both; }
    .d1  { animation-delay: 0.05s; }
    .d2  { animation-delay: 0.15s; }
    .d3  { animation-delay: 0.25s; }
    .d4  { animation-delay: 0.35s; }

    /* ── Guarantee pill ── */
    .g-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 100px;
        padding: 6px 13px;
        font-size: 12px; font-weight: 600;
        color: #4a5568;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* waitlist input */
    .wl-input {
        flex: 1;
        background: #fff;
        border: 1.5px solid #e8eaf0;
        border-radius: 10px;
        padding: 11px 14px;
        color: #081d3a;
        font-size: 13px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .wl-input:focus { outline: none; border-color: #081d3a; }
    .wl-input::placeholder { color: #adb5c4; }
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════════════════
     HERO / BREADCRUMB
════════════════════════════════════════════════════ --}}
<div class="quote-hero pt-24">
    @include('components.navbar')

    <div class="relative z-10 px-5 py-14 sm:py-20 text-center w-full">
        <p class="au d1 text-white/40 text-[11.5px] font-semibold uppercase tracking-[0.22em] mb-5"
           style="font-family:'Plus Jakarta Sans',sans-serif;">
            Get a Quote
        </p>
        <h1 class="au d2 text-white leading-[1.06] tracking-[-0.02em]"
            style="font-family:'Geist',sans-serif; font-weight: 700; font-size: clamp(36px, 5vw, 68px); max-width: 760px; margin: 0 auto;">
            Let's get your<br>space spotless.
        </h1>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     MAIN BODY
════════════════════════════════════════════════════ --}}
<div class="quote-body">

    <div class="section-wrap py-20 lg:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 xl:gap-20 items-start">

            {{-- ══ LEFT: INFO ══ --}}
            <div class="lg:col-span-5 au d2">

                {{-- Intro text --}}
                <p class="text-[#081d3a] text-[17px] leading-relaxed font-normal mb-10 max-w-md"
                   style="font-family:'Plus Jakarta Sans',sans-serif; letter-spacing:-0.01em;">
                    Fill in the form and get an instant quote — no guesswork, no hidden fees, no commitment.
                </p>

                {{-- Guarantee pills --}}
                <div class="flex flex-wrap gap-2 mb-12">
                    <span class="g-pill">
                        <svg class="w-3.5 h-3.5 text-[#081d3a]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Free inspection
                    </span>
                    <span class="g-pill">
                        <svg class="w-3.5 h-3.5 text-[#081d3a]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        No commitment
                    </span>
                    <span class="g-pill">
                        <svg class="w-3.5 h-3.5 text-[#081d3a]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Vetted staff
                    </span>
                    <span class="g-pill">
                        <svg class="w-3.5 h-3.5 text-[#081d3a]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Eco-friendly
                    </span>
                </div>

                {{-- How it works --}}
                <div class="mb-12">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-[#8a94a6] mb-6"
                       style="font-family:'Plus Jakarta Sans',sans-serif;">How it works</p>
                    <div class="space-y-6">
                        @foreach([
                            ['Fill in the form', 'Tell us your suburb, service type, and property size.'],
                            ['We reach out', 'Our team confirms details and sends your tailored quote.'],
                            ['Book your clean', 'Pick a date — our vetted team arrives on time, every time.'],
                        ] as $i => $step)
                        <div class="flex items-start gap-4">
                            <div class="step-circle mt-0.5">{{ $i + 1 }}</div>
                            <div>
                                <p class="text-[#081d3a] font-bold text-[14.5px] tracking-tight"
                                   style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $step[0] }}</p>
                                <p class="text-[#8a94a6] text-[13px] mt-0.5 leading-relaxed"
                                   style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $step[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="info-divider mb-10"></div>

                {{-- Contact direct --}}
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-[#8a94a6] mb-4"
                       style="font-family:'Plus Jakarta Sans',sans-serif;">Or reach us directly</p>

                    <div class="contact-row">
                        <div class="icon-chip">
                            <svg class="w-4.5 h-4.5 text-[#f6e304]" fill="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#adb5c4] uppercase tracking-widest mb-0.5" style="font-family:'Plus Jakarta Sans',sans-serif;">WhatsApp / Call</p>
                            <a href="https://wa.me/27815274711" target="_blank"
                               class="text-[#081d3a] font-semibold text-[14.5px] hover:text-[#f6e304] transition-colors"
                               style="font-family:'Plus Jakarta Sans',sans-serif;">+27 81 527 4711</a>
                        </div>
                    </div>

                    <div class="contact-row">
                        <div class="icon-chip">
                            <svg class="text-[#f6e304]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#adb5c4] uppercase tracking-widest mb-0.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Email</p>
                            <a href="mailto:bookings@springkleaners.co.za"
                               class="text-[#081d3a] font-semibold text-[14.5px] hover:text-[#f6e304] transition-colors"
                               style="font-family:'Plus Jakarta Sans',sans-serif;">bookings@springkleaners.co.za</a>
                        </div>
                    </div>

                    <div class="contact-row">
                        <div class="icon-chip">
                            <svg class="text-[#f6e304]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#adb5c4] uppercase tracking-widest mb-0.5" style="font-family:'Plus Jakarta Sans',sans-serif;">Service Area</p>
                            <p class="text-[#081d3a] font-semibold text-[14.5px]" style="font-family:'Plus Jakarta Sans',sans-serif;">Cape Town Northern Suburbs</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT: FORM ══ --}}
            <div class="lg:col-span-7 au d3"
                 x-data="{
                    suburbs: [
                        'Milnerton','Sunningdale','Blouberg','Parklands','Century City',
                        'Table View','Big Bay','Bloubergstrand','West Beach','Monte Vista',
                        'Edgemead','Bothasig','Richwood','Burgundy Estate','Flamingo Vlei',
                        'Sandown','Sunset Beach','Parklands North','Waves Edge','Montague Gardens',
                        'Blouberg Rise','Summer Greens','Rugby','Paarden Eiland','Marconi Beam',
                        'Dunoon','Joe Slovo Park','Penhill','Kerria','Ravensmead'
                    ],
                    suburbQuery: '', filteredSuburbs: [], showSuggestions: false,
                    selectedSuburb: '', locationStatus: null,
                    waitlistEmail: '', submitted: false,
                    form: { name:'', phone:'', email:'', address:'', service:'', propertyType:'Residential', bedrooms:'', message:'' },
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
                    submitForm() {
                        const lines = [
                            'Hi SpringKleaners! I\'d like a free quote.',
                            '',
                            'Name: '    + this.form.name,
                            'Phone: '   + this.form.phone,
                            'Email: '   + this.form.email,
                            'Address: ' + (this.form.address || 'N/A'),
                            'Service: ' + this.form.service,
                            'Property: '+ this.form.propertyType,
                            'Bedrooms: '+ (this.form.bedrooms || 'N/A'),
                            'Suburb: '  + this.selectedSuburb,
                            'Notes: '   + (this.form.message || 'None'),
                        ];
                        window.open('https://wa.me/27815274711?text=' + encodeURIComponent(lines.join('\n')), '_blank');
                        this.submitted = true;
                    }
                 }">

                {{-- ── Success ── --}}
                <div x-show="submitted"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white rounded-3xl p-16 text-center shadow-sm border border-[#edf0f5]"
                     style="display:none;">
                    <div class="w-20 h-20 rounded-full bg-[#f6e304] mx-auto mb-7 flex items-center justify-center">
                        <svg class="w-9 h-9 text-[#081d3a]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-[#081d3a] text-3xl font-black tracking-tight mb-3">Quote sent!</h2>
                    <p class="text-[#8a94a6] text-[15px] leading-relaxed max-w-sm mx-auto" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Check your WhatsApp — our team will confirm your details and send a tailored quote within a few hours.
                    </p>
                    <div class="mt-8">
                        <a href="/" class="inline-flex items-center gap-2 text-[#081d3a] text-[13.5px] font-semibold hover:text-[#f6e304] transition-colors" style="font-family:'Plus Jakarta Sans',sans-serif;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to home
                        </a>
                    </div>
                </div>

                {{-- ── Form card ── --}}
                <div x-show="!submitted"
                     class="bg-white rounded-3xl border border-[#edf0f5] shadow-sm">

                    {{-- Card header --}}
                    <div class="px-8 pt-8 pb-6 border-b border-[#edf0f5]">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-[#081d3a] text-[22px] font-black tracking-tight leading-tight">
                                    Get your free quote
                                </h2>
                                <p class="text-[#adb5c4] text-[13.5px] mt-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                                    Instant estimate — confirmed at your free inspection.
                                </p>
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0 mt-1">
                                <span class="text-[#f6e304] text-base">★★★★★</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">

                        {{-- Suburb --}}
                        <div>
                            <label class="fl">Your Suburb</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="text-[#adb5c4]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       x-model="suburbQuery"
                                       @input="filterSuburbs()"
                                       @blur="setTimeout(() => showSuggestions = false, 300)"
                                       @keydown.enter.prevent="checkLocation()"
                                       placeholder="e.g. Milnerton, Table View, Parklands..."
                                       class="fi pl-11">
                                <div x-show="showSuggestions" class="suburb-drop" style="display:none;">
                                    <template x-for="s in filteredSuburbs" :key="s">
                                        <div @click="selectSuburb(s)" class="suburb-opt">
                                            <svg class="text-[#adb5c4] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" style="width:13px;height:13px;">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span x-text="s"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div x-show="locationStatus === 'valid'" class="mt-2.5 flex items-center gap-2 text-emerald-600 text-[12.5px]" style="display:none; font-family:'Plus Jakarta Sans',sans-serif;">
                                <svg class="flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" style="width:15px;height:15px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Great — we service <strong x-text="selectedSuburb" class="ml-1"></strong>
                            </div>
                            <div x-show="locationStatus === 'invalid'" class="mt-2.5 flex items-center gap-2 text-rose-500 text-[12.5px]" style="display:none; font-family:'Plus Jakarta Sans',sans-serif;">
                                <svg class="flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" style="width:15px;height:15px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                We don't service <strong x-text="suburbQuery" class="mx-1"></strong> yet
                            </div>
                        </div>

                        {{-- Waitlist --}}
                        <div x-show="locationStatus === 'invalid'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="rounded-2xl p-5 border border-[#edf0f5] bg-[#f8f9fc]"
                             style="display:none;">
                            <p class="text-[#081d3a] font-semibold text-[14px] mb-1" style="font-family:'Plus Jakarta Sans',sans-serif;">
                                We're expanding into <span x-text="suburbQuery" class="text-[#f6e304] bg-[#081d3a] px-1.5 py-0.5 rounded-md"></span> soon.
                            </p>
                            <p class="text-[#adb5c4] text-[13px] mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">Join our waitlist — be the first to know.</p>
                            <div class="flex gap-2">
                                <input type="email" x-model="waitlistEmail" placeholder="Your email address" class="wl-input">
                                <button type="button" class="px-5 py-3 bg-[#081d3a] text-white font-bold rounded-xl text-[13px] hover:bg-[#0d2a4a] transition-colors flex-shrink-0" style="font-family:'Geist',sans-serif;">
                                    Join
                                </button>
                            </div>
                        </div>

                        {{-- Rest of form --}}
                        <div x-show="locationStatus === 'valid'"
                             x-transition:enter="transition ease-out duration-400"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-5"
                             style="display:none;">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="fl">Full Name</label>
                                    <input type="text" x-model="form.name" placeholder="Jane Smith" class="fi">
                                </div>
                                <div>
                                    <label class="fl">Phone / WhatsApp</label>
                                    <input type="tel" x-model="form.phone" placeholder="+27 81 000 0000" class="fi">
                                </div>
                            </div>

                            <div>
                                <label class="fl">Email Address</label>
                                <input type="email" x-model="form.email" placeholder="jane@example.com" class="fi">
                            </div>

                            <div>
                                <label class="fl">Property Address</label>
                                <input type="text" x-model="form.address" placeholder="e.g. 12 Ocean View Drive, Blouberg" class="fi">
                            </div>

                            <div style="height:1px;background:#edf0f5;"></div>

                            <div>
                                <label class="fl">Service Required</label>
                                <div class="relative">
                                    <select x-model="form.service" class="fi pr-10 cursor-pointer">
                                        <option value="">Choose a service...</option>
                                        <option value="Deep Cleaning">Deep Cleaning</option>
                                        <option value="End-of-Tenancy Cleaning">End-of-Tenancy Cleaning</option>
                                        <option value="Post Construction Cleaning">Post Construction Cleaning</option>
                                        <option value="Regular Cleaning">Regular Cleaning</option>
                                        <option value="Move-In Cleaning">Move-In Cleaning</option>
                                        <option value="Office Cleaning">Office Cleaning</option>
                                    </select>
                                    <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="text-[#adb5c4]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:15px;height:15px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="fl">Property Type</label>
                                    <div class="flex gap-2">
                                        <button type="button"
                                                @click="form.propertyType = 'Residential'"
                                                :class="form.propertyType === 'Residential' ? 'type-btn type-active' : 'type-btn type-inactive'">
                                            Residential
                                        </button>
                                        <button type="button"
                                                @click="form.propertyType = 'Commercial'"
                                                :class="form.propertyType === 'Commercial' ? 'type-btn type-active' : 'type-btn type-inactive'">
                                            Commercial
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="fl">No. of Bedrooms</label>
                                    <div class="relative">
                                        <select x-model="form.bedrooms" class="fi pr-10 cursor-pointer">
                                            <option value="">Select...</option>
                                            <option value="Studio">Studio / 1 Bed</option>
                                            <option value="2">2 Bedrooms</option>
                                            <option value="3">3 Bedrooms</option>
                                            <option value="4">4 Bedrooms</option>
                                            <option value="5+">5+ Bedrooms</option>
                                        </select>
                                        <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
                                            <svg class="text-[#adb5c4]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:15px;height:15px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="fl">Additional Details <span class="normal-case tracking-normal text-[#c8cdd8]">(optional)</span></label>
                                <textarea x-model="form.message" rows="3"
                                          placeholder="e.g. post-renovation, move-out date, pets on premises..."
                                          class="fi resize-none"></textarea>
                            </div>

                            <div style="height:1px;background:#edf0f5;"></div>

                            <div>
                                <button @click="submitForm()" type="button" class="submit-btn">
                                    Get My Free Quote
                                    <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:18px;height:18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H7M17 7v10"/>
                                    </svg>
                                </button>
                                <p class="text-center text-[#c8cdd8] text-[12px] mt-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                                    Free inspection · No commitment · No spam
                                </p>
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
