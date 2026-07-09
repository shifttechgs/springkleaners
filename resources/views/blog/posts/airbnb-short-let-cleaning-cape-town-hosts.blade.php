<p class="text-navy/80 text-[17px] leading-[1.8] font-medium mb-8">
    Airbnb and short-let turnover cleaning needs a faster, tighter-scope reset than a standard house clean — beds stripped and remade, bathrooms fully reset, a quick damage check, and the space left photo-ready before the next guest's check-in.
</p>

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">What a proper turnover clean actually covers</h2>
<x-blog.checklist :items="[
    'Beds stripped, linen changed and remade',
    'Bathrooms fully reset and restocked',
    'Kitchen cleared, wiped and reset for the next guest',
    'Floors vacuumed and mopped throughout',
    'Damage or missing-item check, reported back to the host',
    'Photo-ready staging before the listing goes live again',
]" />

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">Scheduling around guest changeovers</h2>
<p class="text-muted text-[15.5px] leading-[1.85] mb-5">
    We currently schedule turnovers within our weekend availability — which fits the Friday-in/Sunday-out pattern common to weekend-getaway short-lets along the Blaauwberg coast (Big Bay, Blouberg and Bloubergstrand especially). If your booking pattern needs weekday turnarounds, message us directly and we'll see what's realistic.
</p>

<x-blog.callout label="What to check before you book a cleaner for your listing">
    Ask specifically about a damage/missing-item check as part of the turnover — not every cleaning service reports back on this, and it's one of the most useful things a host can have documented between guests.
</x-blog.callout>

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">Keeping your listing consistently photo-ready</h2>
<p class="text-muted text-[15.5px] leading-[1.85] mb-5">
    Guest reviews increasingly mention cleanliness specifically, and a listing's photos only mean something if arriving guests find the same standard in person. A consistent turnover process — not just "a clean," but the same checklist every time — is what actually protects a listing's rating over dozens of bookings.
</p>

<x-blog.cta heading="From R900 per turnover" text="2 bed/1 bath included, +R100 per extra bedroom. Book online." />

<x-blog.faq :items="[
    ['q' => 'Can you turn a property around the same day a guest checks out?', 'a' => 'Yes, within our current weekend availability — this fits the Friday-in/Sunday-out pattern common to weekend-getaway short-lets in the Northern Suburbs. If your turnover schedule needs weekday cleaning, message us and we\'ll see what we can arrange.'],
    ['q' => 'What if something is damaged or missing after a guest checks out?', 'a' => 'We do a quick check during every turnover and report anything damaged, missing, or left behind, so you can follow up with the guest or platform before the next booking arrives.'],
]" />

<p class="text-muted text-[15.5px] leading-[1.85]">
    See full pricing on our <a href="{{ route('services.show', 'airbnb-turnover-cleaning') }}" class="text-navy font-semibold underline">Airbnb & Short-Let Turnover Cleaning</a> page, or check our <a href="{{ route('areas.show', 'big-bay') }}" class="text-navy font-semibold underline">Big Bay</a> and <a href="{{ route('areas.show', 'bloubergstrand') }}" class="text-navy font-semibold underline">Bloubergstrand</a> area pages if your listing is on the coast.
</p>
