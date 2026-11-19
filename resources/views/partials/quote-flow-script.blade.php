{{-- The instant quote + booking logic, shared by every layout that books (hero card, mobile app sheet), so prices,
     availability, slot rules, tracking and photo consent can't drift apart. Data comes from App\Support\QuoteFlow.
     Registered before Alpine starts (Alpine loads deferred; this runs while the page is parsed). --}}
@once
@push('scripts')
<script>
document.addEventListener('alpine:init', function () {
    Alpine.data('quoteFlow', function (cfg) {
        return {
            cfg: cfg,
            steps: cfg.steps,
            stepNames: cfg.stepNames,
            step: 1, dir: 1,
            done: false, submitting: false, serverError: '',
            suburbs: cfg.suburbs, popularSuburbs: cfg.popularSuburbs,
            suburbQuery: '', filteredSuburbs: [], showSuggestions: false, selectedSuburb: '', locationStatus: null,
            services: cfg.services, serviceKey: cfg.defaultService,
            propertyType: 'House', bedrooms: 2, bathrooms: 1, extraRooms: 0,
            date: '', slot: '',
            slotLabels: cfg.slotLabels, bookableDays: cfg.bookableDays,
            counts: {}, taken: {}, capacity: 2, loadingDates: false, loadedMonths: [],
            form: { name: '', phone: '', email: '', address: '' },
            photoConsent: false,
            errors: {},
            started: false, reached: [1],

            // The component's own element, kept at start-up: steps that move on by themselves (after a short delay,
            // or from a nested scope) can't rely on $root being available.
            init() { this._el = this.$el; },
            rootEl() { return this._el || this.$root; },

            // Analytics (GA4, via the gtag already on every page): quote_start → quote_step → generate_lead, plus
            // drop-outs. Never sends name, phone, email, address or typed text.
            track(name, params = {}) {
                try { if (typeof window.gtag === 'function') window.gtag('event', name, Object.assign({ form_location: this.cfg.location }, params)); } catch (e) {}
            },
            start() { if (this.started) return; this.started = true; this.track('quote_start', { service: this.serviceKey }); },

            get stepKey() { return this.steps[this.step - 1] },
            get stepCount() { return this.steps.length },
            indexOf(key) { return this.steps.indexOf(key) + 1 },

            get service() { return this.services[this.serviceKey] || null },
            get subtotal() {
                const s = this.service; if (!s) return 0;
                return s.base_price
                    + Math.max(0, this.bedrooms - s.included_bedrooms) * s.bedroom_price
                    + Math.max(0, this.bathrooms - s.included_bathrooms) * s.bathroom_price
                    + this.extraRooms * s.extra_room_price;
            },
            get total() { return this.subtotal + (this.service ? this.service.service_fee : 0) },
            money(n) { return 'R' + Math.round(n).toLocaleString('en-US') },
            fromPrice(key) { const s = this.services[key]; return s ? this.money(s.base_price + s.service_fee) : '' },
            sizeLabel(n, max) { return n >= max ? max + '+' : String(n) },

            // Suburb: typed (with suggestions) or tapped.
            filterSuburbs() {
                this.locationStatus = null; this.selectedSuburb = '';
                const q = this.suburbQuery.trim().toLowerCase();
                if (!q) { this.filteredSuburbs = []; this.showSuggestions = false; return; }
                this.filteredSuburbs = this.suburbs.filter(s => s.toLowerCase().includes(q));
                this.showSuggestions = this.filteredSuburbs.length > 0;
                // Typed in full (any case)? Count it as chosen. If longer names still match (Parklands → Parklands
                // North) the list stays open so those can still be picked.
                const exact = this.suburbs.find(s => s.toLowerCase() === q);
                if (exact) {
                    if (this.filteredSuburbs.length === 1) this.selectSuburb(exact);
                    else { this.selectedSuburb = exact; this.locationStatus = 'valid'; this.errors.suburb = false; }
                }
            },
            suburbBlur() {
                setTimeout(() => {
                    this.showSuggestions = false;
                    if (this.suburbQuery.trim() && this.locationStatus !== 'valid') this.checkLocation();
                }, 150);
            },
            // Chosen on pointerdown, before the field loses focus: on phones the keyboard closing shifts the page and
            // a plain click could land after the list has gone.
            pickSuburb(s) { this.selectSuburb(s); this.$nextTick(() => this.rootEl().querySelector('[data-suburb-input]')?.blur()); },
            selectSuburb(s) { this.selectedSuburb = s; this.suburbQuery = s; this.showSuggestions = false; this.locationStatus = 'valid'; this.errors.suburb = false; },
            checkLocation() {
                this.showSuggestions = false;
                if (!this.suburbQuery.trim()) { this.locationStatus = 'required'; return; }
                const m = this.suburbs.find(s => s.toLowerCase() === this.suburbQuery.trim().toLowerCase());
                if (m) { this.selectSuburb(m); } else { this.locationStatus = 'invalid'; this.track('quote_out_of_area'); }
            },

            // Is the current step complete? Shows its errors if not.
            stepValid() {
                const k = this.stepKey;
                if (k === 'clean' || k === 'where') {
                    if (this.locationStatus !== 'valid') this.checkLocation();
                    if (this.locationStatus !== 'valid') return false;
                }
                if (k === 'clean' || k === 'service') {
                    this.errors.service = !this.service;
                    if (this.errors.service) return false;
                }
                if (k === 'when') {
                    this.errors.when = !this.date || !this.slot;
                    if (this.errors.when) return false;
                }
                return true;
            },
            next() {
                this.serverError = '';
                if (!this.stepValid()) return;
                let n = this.step + 1;
                // A suburb already chosen (e.g. from the "do we clean in your suburb?" check) doesn't need asking again.
                if (this.steps[n - 1] === 'where' && this.locationStatus === 'valid') n++;
                this.go(n);
            },
            back() {
                if (this.step <= 1) return;
                this.go(this.step - 1);
            },
            // Tap-first steps (app): choosing the only thing on the step moves on by itself.
            chooseService(key) { this.serviceKey = key; this.errors.service = false; setTimeout(() => this.next(), 180); },
            chooseSuburb(s) { this.selectSuburb(s); setTimeout(() => this.next(), 180); },

            go(n) {
                this.dir = n >= this.step ? 1 : -1;
                this.step = n;
                // Each step counts once per visit, so going Back and forward again doesn't inflate the funnel.
                if (!this.reached.includes(n)) {
                    this.reached.push(n);
                    this.track('quote_step', { step_number: n, step_name: this.stepNames[n - 1], service: this.serviceKey, property_type: this.propertyType, value: Math.round(this.total), currency: 'ZAR' });
                }
                if (this.steps[n - 1] === 'when') this.loadDates();
                this.$nextTick(() => {
                    this.keepInView();
                    // No auto-focus on touch screens: focusing a text field would pop the keyboard up unasked.
                    if (!window.matchMedia('(pointer: coarse)').matches) this.$refs['step' + n]?.querySelector('input, button, select')?.focus({ preventScroll: true });
                });
            },
            // Opened from elsewhere on the page (a service tile, a suburb check) with a choice already made.
            openWith(d) {
                if (this.done) this.reset();
                if (this.step !== 1) return;
                if (d.suburb && this.suburbs.includes(d.suburb)) this.selectSuburb(d.suburb);
                if (d.service && this.services[d.service]) {
                    this.serviceKey = d.service; this.errors.service = false;
                    if (this.stepKey === 'service') this.go(2);
                }
            },
            // Back to a fresh first step for another booking; availability is fetched again so the slot just
            // booked shows as taken.
            reset() {
                Object.assign(this, {
                    done: false, step: 1, dir: 1, serverError: '', errors: {},
                    suburbQuery: '', selectedSuburb: '', locationStatus: null, showSuggestions: false,
                    serviceKey: this.cfg.defaultService,
                    propertyType: 'House', bedrooms: 2, bathrooms: 1, extraRooms: 0,
                    date: '', slot: '', loadedMonths: [], counts: {}, taken: {},
                    form: { name: '', phone: '', email: '', address: '' }, photoConsent: false,
                    started: false, reached: [1],
                });
                this.$nextTick(() => { this.keepInView(); this.$refs.title?.focus({ preventScroll: true }); });
            },
            // After a step change, bring the top of the flow back into view: inside a sheet scroll the sheet, on a
            // phone page keep the card's top clear of the floating navbar.
            keepInView() {
                const sheet = this.rootEl().closest('[data-sheet-scroll]') || this.rootEl().querySelector('[data-sheet-scroll]');
                if (sheet) { sheet.scrollTo({ top: 0, behavior: 'smooth' }); return; }
                if (window.innerWidth >= 1024) return;
                const top = this.rootEl().getBoundingClientRect().top;
                if (top < 88 || top > window.innerHeight * 0.5) window.scrollTo({ top: window.scrollY + top - 96, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
            },

            // Next bookable dates, with how many slots are left on each.
            async loadDates() {
                const now = new Date();
                const months = [0, 1].map(k => { const d = new Date(now.getFullYear(), now.getMonth() + k, 1); return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-01'; });
                const todo = months.filter(m => !this.loadedMonths.includes(m));
                if (!todo.length) return;
                this.loadingDates = true;
                try {
                    for (const m of todo) {
                        const json = await (await fetch(this.cfg.availabilityUrl + '?month=' + m)).json();
                        Object.assign(this.counts, json.counts || {});
                        Object.assign(this.taken, json.times || {});
                        this.capacity = json.capacity || this.capacity;
                        this.loadedMonths.push(m);
                    }
                } catch (e) {}
                this.loadingDates = false;
            },
            iso(d) { return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0') },
            get dateOptions() {
                const out = [];
                const d = new Date(); d.setHours(0, 0, 0, 0);
                for (let i = 0; i < 62 && out.length < 6; i++, d.setDate(d.getDate() + 1)) {
                    if (!this.bookableDays.includes(d.getDay())) continue;
                    const iso = this.iso(d);
                    const used = Math.max(this.counts[iso] || 0, (this.taken[iso] || []).length);
                    out.push({ iso, day: d.toLocaleDateString('en-GB', { weekday: 'short' }), date: d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }), left: Math.max(0, this.capacity - used) });
                }
                return out;
            },
            slotTaken(s) { return (this.taken[this.date] || []).includes(s) },
            pickDate(o) { if (o.left < 1) return; this.date = o.iso; if (this.slot && this.slotTaken(this.slot)) this.slot = ''; this.errors.when = false; },
            get whenLabel() {
                if (!this.date) return '';
                return new Date(this.date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short' }) + (this.slot ? ', ' + this.slot : '');
            },
            get fullFormLink() {
                const p = new URLSearchParams();
                if (this.serviceKey) p.set('service', this.serviceKey);
                if (this.selectedSuburb) p.set('suburb', this.selectedSuburb);
                return this.cfg.fullFormUrl + (p.toString() ? '?' + p.toString() : '');
            },

            async submit() {
                if (this.submitting) return;
                const f = this.form;
                this.errors = {
                    name: !f.name.trim(),
                    phone: f.phone.replace(/\D/g, '').length < 9,
                    email: !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.trim()),
                    address: !f.address.trim(),
                };
                if (Object.values(this.errors).some(Boolean)) return;
                this.submitting = true; this.serverError = '';
                try {
                    const res = await fetch(this.cfg.reserveUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                        body: JSON.stringify({
                            service: this.serviceKey, date: this.date, time: this.slot,
                            name: f.name.trim(), phone: f.phone.trim(), email: f.email.trim(), address: f.address.trim(), suburb: this.selectedSuburb,
                            property_type: this.propertyType, photo_consent: this.photoConsent,
                            bedrooms: this.sizeLabel(this.bedrooms, 5), bathrooms: this.sizeLabel(this.bathrooms, 4), extra_rooms: this.sizeLabel(this.extraRooms, 4),
                            booking_type: 'once-off', subtotal: this.subtotal, total: this.total,
                        }),
                    });
                    const json = await res.json().catch(() => ({}));
                    if (res.ok && json.status === 'ok') {
                        this.done = true;
                        this.track('generate_lead', { service: this.serviceKey, property_type: this.propertyType, value: Math.round(this.total), currency: 'ZAR' });
                        this.$nextTick(() => this.keepInView()); return;
                    }
                    this.track('quote_error', { reason: json.status || ('http_' + res.status), step_number: this.step });
                    if (['time_taken', 'full', 'invalid_day'].includes(json.status)) {
                        this.loadedMonths = []; await this.loadDates();
                        this.slot = ''; if (json.status !== 'time_taken') this.date = '';
                        this.serverError = json.status === 'time_taken' ? 'That slot was just booked — please pick another.' : 'That date just filled up — please pick another.';
                        this.dir = -1; this.step = this.indexOf('when'); this.$nextTick(() => this.keepInView()); return;
                    }
                    this.serverError = json.message || 'Something went wrong — please try again, or WhatsApp us on +27 81 527 4711.';
                } catch (e) {
                    this.track('quote_error', { reason: 'network', step_number: this.step });
                    this.serverError = 'Something went wrong — please try again, or WhatsApp us on +27 81 527 4711.';
                } finally {
                    this.submitting = false;
                }
            },
        };
    });
});
</script>
@endpush
@endonce
