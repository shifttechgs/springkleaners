<p class="text-navy/80 text-[17px] leading-[1.8] font-medium mb-8">
    A deep clean resets a home from scratch — inside appliances, cupboard interiors, built-up grime in bathrooms and kitchens — while a regular clean simply maintains a space that's already tidy. They're not two price points on the same service; they're genuinely different jobs.
</p>

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">What a Deep Clean actually resets</h2>
<x-blog.checklist :items="[
    'Inside every appliance — oven, fridge, microwave, not just the visible exterior',
    'Cupboard and cabinet interiors, not just the counters around them',
    'Baseboards, skirting and light switches, dusted and wiped',
    'Bathroom grout and built-up grime, not just a surface wipe',
    'Every room, vacuumed and mopped from a genuinely blank slate',
]" />

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">What a regular (recurring) clean maintains</h2>
<p class="text-muted text-[15.5px] leading-[1.85] mb-5">
    Recurring cleaning covers the surfaces and rooms that get used day to day — tidying, dusting, vacuuming, kitchen and bathroom upkeep — at a lighter scope and lower cost per visit than a Deep Clean, on a standing weekly or bi-weekly schedule. It's designed to maintain a standard a Deep Clean already set, not reset one that's slipped.
</p>

<x-blog.callout label="The honest answer to 'which do I need?'">
    If the home hasn't had a proper reset in months (or ever), start with a Deep Clean. Once it's back to a good standard, Recurring House Cleaning keeps it there without redoing the full reset every visit. Most of our clients use both.
</x-blog.callout>

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">Price reflects the actual difference in scope</h2>
<p class="text-muted text-[15.5px] leading-[1.85] mb-5">
    Deep Cleaning starts from R1,200 for 2 bedrooms and 1 bathroom. Recurring House Cleaning starts from R750 for the same size property — not because it's a discount on the same job, but because it genuinely is a lighter job, done more often.
</p>

<x-blog.cta heading="Not sure which one you need?" text="Message us on WhatsApp and we'll help you decide before you book." />

<x-blog.faq :items="[
    ['q' => 'How is Recurring House Cleaning different from a Deep Clean?', 'a' => 'A Deep Clean resets a home from scratch — inside appliances, cupboard interiors, built-up grime. Recurring cleaning maintains that standard in between, covering day-to-day surfaces at a lighter (and lower) rate. Most clients pair a periodic Deep Clean with a weekly or bi-weekly recurring visit.'],
    ['q' => 'How often should I book a Deep Clean?', 'a' => 'There\'s no fixed rule — many clients book one seasonally, or before/after a big life event, then maintain that standard in between with Recurring House Cleaning.'],
]" />

<p class="text-muted text-[15.5px] leading-[1.85]">
    See full pricing and inclusions on our <a href="{{ route('services.show', 'deep-cleaning') }}" class="text-navy font-semibold underline">Deep Cleaning</a> and <a href="{{ route('services.show', 'recurring-house-cleaning') }}" class="text-navy font-semibold underline">Recurring House Cleaning</a> pages. Doing a full seasonal reset? <a href="{{ route('services.show', 'spring-cleaning') }}" class="text-navy font-semibold underline">Spring Cleaning</a> goes further still, adding windows, curtains and blinds.
</p>
