@php
    // Live figures from the services table and booking rules, so this post never quotes a stale price or day.
    $eot = \App\Support\Services::find('end-of-tenancy');
    $eotFrom = $eot ? 'R'.number_format($eot['base_price']) : null;
    $eotBase = $eot ? $eot['included_bedrooms'].'-bedroom, '.$eot['included_bathrooms'].'-bathroom' : null;
    $eotInclusions = $eot['inclusions'] ?? [];
    $dayNames = [0 => 'Sundays', 1 => 'Mondays', 2 => 'Tuesdays', 3 => 'Wednesdays', 4 => 'Thursdays', 5 => 'Fridays', 6 => 'Saturdays'];
    $bookingDays = collect(\App\Models\Booking::BOOKABLE_DAYS)->map(fn ($d) => $dayNames[$d])->join(', ', ' and ');
    $weekendsOnly = array_diff(\App\Models\Booking::BOOKABLE_DAYS, [0, 6]) === [];
    $h2 = 'text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4';
    $h3 = 'text-navy font-bold text-[18px] tracking-tight mt-8 mb-3';
    $p = 'text-muted text-[15.5px] leading-[1.85] mb-5';
    $a = 'text-navy font-semibold underline';
@endphp

<p class="text-navy/80 text-[17px] leading-[1.8] font-medium mb-8">
    A move-out clean (also called an end-of-tenancy clean) is a detailed clean of a rental property once it is empty, done before the final inspection and key handover. Done properly, it lets you hand the property back in the best possible condition and removes one avoidable reason for deductions from your deposit. This checklist covers the areas landlords, managing agents and incoming tenants are most likely to notice.
</p>

<p class="{{ $p }}">
    Moving out is stressful enough without worrying whether the property is clean enough for the final inspection. Use this list after your furniture and boxes have gone, when every room, cupboard and appliance is accessible.
</p>

<x-blog.callout label="Important">
    A cleaning service can help present the property well, but it cannot repair damage, remove permanent stains or guarantee a deposit outcome. Check your lease, your incoming inspection report and your landlord or agent’s requirements before you book.
</x-blog.callout>

<h2 class="{{ $h2 }}">When to clean before moving out</h2>
<p class="{{ $p }}">
    Plan the move-out clean for <strong class="text-navy">after the property is empty, but before the final inspection or key handover</strong>. Cleaning around furniture and boxes means floors, walls, cupboards and corners are blocked, and you end up cleaning twice.
</p>

<div class="overflow-x-auto mb-8 rounded-2xl border border-black/10 bg-white">
    <table class="w-full text-left text-[14.5px]">
        <caption class="sr-only">Move-out cleaning timeline</caption>
        <thead class="bg-[#f8f9fc] text-navy">
            <tr>
                <th scope="col" class="px-4 py-3 font-bold w-[34%]">When</th>
                <th scope="col" class="px-4 py-3 font-bold">What to do</th>
            </tr>
        </thead>
        <tbody class="text-muted divide-y divide-black/5">
            <tr><td class="px-4 py-3 font-semibold text-navy align-top">1–2 weeks before moving</td><td class="px-4 py-3">Confirm your inspection date, re-read your lease and incoming inspection report, and list any repairs or cleaning priorities. Book your clean early: we only take a few cleans a day, so popular dates fill up.</td></tr>
            <tr><td class="px-4 py-3 font-semibold text-navy align-top">A few days before moving</td><td class="px-4 py-3">Declutter, pack, and get rid of anything you are not taking with you.</td></tr>
            <tr><td class="px-4 py-3 font-semibold text-navy align-top">After the property is empty</td><td class="px-4 py-3">Do the full move-out clean.</td></tr>
            <tr><td class="px-4 py-3 font-semibold text-navy align-top">Before the inspection</td><td class="px-4 py-3">Walk through with this checklist and take clear, dated photos of every room.</td></tr>
            <tr><td class="px-4 py-3 font-semibold text-navy align-top">On handover day</td><td class="px-4 py-3">Do the outgoing inspection with the landlord or agent and keep copies of every record.</td></tr>
        </tbody>
    </table>
</div>

@if ($weekendsOnly)
<x-blog.callout label="Booking tip">
    We currently take bookings on {{ $bookingDays }}. If your inspection is midweek, book the clean for the weekend before, once your furniture has gone, so there is time for a final walkthrough.
</x-blog.callout>
@endif

<h2 class="{{ $h2 }}">The room-by-room move-out cleaning checklist</h2>

<h3 class="{{ $h3 }}">Kitchen</h3>
<p class="{{ $p }}">Kitchens usually need the most work: grease, food residue and limescale build up over a tenancy.</p>
<x-blog.checklist interactive :items="[
    'Oven, including racks, trays, door glass and interior surfaces',
    'Stovetop, burner rings, knobs and surrounding surfaces',
    'Extractor fan, filters and the grease around them',
    'Microwave, inside and out',
    'Fridge and freezer, including shelves, drawers, door seals and exterior',
    'Dishwasher filter and door seals, if there is one',
    'Washing machine drum, detergent drawer, door seal and exterior, if there is one',
    'Cupboards, drawers and shelves, inside and out',
    'Countertops, splashbacks and wall tiles',
    'Sink, taps, draining board and plugholes',
    'Floor, including corners, skirting boards and behind appliances where you can reach',
    'Bins, recycling containers and the bin area',
]" />
<x-blog.callout label="Don’t forget the oven">
    Baked-on grease and burnt residue are among the most common things landlords and agents notice at a move-out inspection. If yours needs more than a wipe, a dedicated <a href="{{ route('services.show', 'oven-deep-cleaning') }}" class="{{ $a }}">oven deep clean</a> covers the racks, the glass door and the interior.
</x-blog.callout>

<h3 class="{{ $h3 }}">Bathrooms</h3>
<p class="{{ $p }}">Bathrooms need descaling, sanitising and attention to detail.</p>
<x-blog.checklist interactive :items="[
    'Toilet, including the base, cistern, seat, lid and surrounding floor',
    'Shower, bath, screens, doors and tracks',
    'Showerhead, taps and drains',
    'Tiles, grout and wall surfaces',
    'Mirrors and glass',
    'Vanity, drawers, cupboards and shelves',
    'Extractor fan cover, where you can reach it',
    'Towel rails, hooks and fittings',
    'Floors, skirting boards and corners',
    'Bins and the bin area',
]" />
<p class="{{ $p }}">
    Limescale around taps, shower glass, plugholes and tiles can make an otherwise clean bathroom look neglected. Use a suitable descaling product and follow the manufacturer’s instructions, especially on natural stone.
</p>

<h3 class="{{ $h3 }}">Bedrooms and living areas</h3>
<p class="{{ $p }}">These rooms are usually quicker, but the details matter.</p>
<x-blog.checklist interactive :items="[
    'Wardrobes, cupboards, drawers and shelves, inside and out',
    'Doors, handles, frames and light switches',
    'Skirting boards, corners and floor edges',
    'Walls: picture hooks, adhesive residue and scuff marks, where appropriate',
    'Windows, frames, sills, tracks and handles',
    'Curtains, blinds and curtain rails, if they are part of the lease',
    'Light fittings, lampshades and ceiling corners',
    'Mirrors and glass surfaces',
    'Floors: vacuum carpets and mop hard floors',
    'Balconies, patios and outdoor areas, if they are part of the lease',
]" />
<p class="{{ $p }}">
    If you hung pictures, fitted shelves or made other changes, check your lease and the original inspection report before filling holes or removing fixtures. Some landlords prefer to arrange repairs themselves.
</p>

<h3 class="{{ $h3 }}">Floors, walls and final details</h3>
<p class="{{ $p }}">These finishing touches make a big difference on inspection day.</p>
<x-blog.checklist interactive :items="[
    'Vacuum all carpets and rugs, and any upholstered furniture that stays in the property',
    'Mop hard floors, paying attention to edges, corners and behind doors',
    'Remove cobwebs from ceilings, corners and light fittings',
    'Wipe skirting boards, door frames and switch plates',
    'Clean interior windows and window tracks',
    'Remove rubbish, recycling and unwanted furniture',
    'Empty and clean the bins',
    'Check the garage, storeroom, balcony, patio and garden if they form part of the lease',
    'Replace light bulbs where needed',
    'Return keys, remotes, access cards and parking discs',
]" />
<x-blog.callout label="Take photos">
    Take clear, dated photos of every room after cleaning, including inside the oven, fridge and cupboards. They are useful evidence of the condition you handed the property back in.
</x-blog.callout>

<h2 class="{{ $h2 }}">What landlords and agents check</h2>
<p class="{{ $p }}">
    Every landlord and managing agent is different, but these areas commonly attract attention during a move-out inspection:
</p>
<x-blog.checklist :items="[
    'Oven, extractor and kitchen grease',
    'Bathroom limescale, mould and grout',
    'Inside cupboards, wardrobes and drawers',
    'Floors, skirting boards and corners',
    'Windows, tracks and glass',
    'Walls, scuffs and picture-hook holes',
    'Bins, rubbish and belongings left behind',
    'Balconies, patios, garages and outdoor spaces',
    'Damage beyond normal wear and tear',
]" />
<p class="{{ $p }}">
    A clean property does not automatically mean the whole deposit comes back. Deposits can also be affected by damage, unpaid accounts, missing keys or issues recorded in the original inspection. Leaving the property thoroughly clean removes one avoidable reason for a dispute. For how cleaning obligations usually work in South African leases, see our guide to <a href="{{ route('blog.show', 'who-pays-for-end-of-lease-cleaning-south-africa') }}" class="{{ $a }}">who pays for end-of-lease cleaning</a>.
</p>

<h2 class="{{ $h2 }}">Get the printable version</h2>
<p class="{{ $p }}">
    Want this on paper for your walkthrough? Download our free <a href="{{ route('services.show', 'end-of-tenancy') }}#deposit-checklist" class="{{ $a }}">End-of-Tenancy Deposit-Back Checklist</a> (PDF). It covers the kitchen, bathrooms, walls, windows and the final steps before you hand back the keys.
</p>

<h2 class="{{ $h2 }}">DIY move-out clean or professional help?</h2>
<p class="{{ $p }}">
    A DIY move-out clean can work well if the property is in good condition, you have enough time, and you have the right products and equipment.
</p>
<p class="{{ $p }}">Professional help may be worth it if:</p>
<x-blog.checklist :items="[
    'You are moving on a tight schedule',
    'The oven, bathroom or carpets need intensive cleaning',
    'You have lived in the property for several years',
    'You want the clean done to a consistent standard',
    'You need it done before a final inspection or key handover',
    'You would rather not spend your last days in the flat cleaning cupboards and skirting boards',
]" />

@if ($eot)
<h3 class="{{ $h3 }}">What our end-of-tenancy clean covers</h3>
@if ($eotInclusions)
<x-blog.checklist :items="$eotInclusions" />
@endif
<p class="{{ $p }}">
    Prices start from {{ $eotFrom }} for a {{ $eotBase }} property, with extra bedrooms and bathrooms added on. You see your price when you book, and we confirm it with you before we start. See <a href="{{ route('blog.show', 'end-of-tenancy-cleaning-cost-cape-town') }}" class="{{ $a }}">how end-of-tenancy pricing works</a> for the full breakdown.
</p>
<p class="{{ $p }}">
    Not part of a standard move-out clean, but bookable separately: <a href="{{ route('services.show', 'carpet-cleaning') }}" class="{{ $a }}">carpet cleaning</a> (some leases ask for carpets to be professionally cleaned), <a href="{{ route('services.show', 'blind-curtain-cleaning') }}" class="{{ $a }}">blind and curtain cleaning</a>, and <a href="{{ route('services.show', 'window-cleaning') }}" class="{{ $a }}">exterior window cleaning</a>. We clean; we don’t do repairs, painting or patching.
</p>
@endif

<x-blog.cta service="end-of-tenancy" heading="Moving out in Cape Town?" text="See your price for a landlord-ready end-of-tenancy clean and book online in minutes." />

<p class="{{ $p }}">
    SpringKleaners provides end-of-tenancy and move-out cleaning for homes and businesses across Cape Town’s Northern Suburbs, Milnerton and the Blaauwberg coast, including <a href="{{ route('areas.show', 'century-city') }}" class="{{ $a }}">Century City</a>. <a href="{{ route('areas.index') }}" class="{{ $a }}">See all the areas we cover</a>.
</p>

<h2 class="{{ $h2 }}">Move-out cleaning FAQs</h2>
<x-blog.faq :items="array_values(array_filter([
    ['q' => 'Is move-out cleaning the same as end-of-tenancy cleaning?', 'a' => 'Yes. Move-out cleaning and end-of-tenancy cleaning describe the same service: a detailed clean done when a tenant leaves a rental property. Exactly what is included depends on the package you book.'],
    ['q' => 'Is move-in cleaning different?', 'a' => 'Yes. Move-in cleaning prepares a property before you move in. It focuses on getting cupboards, bathrooms, kitchen surfaces, floors and appliances clean and ready for your belongings.'],
    ['q' => 'When should I book a move-out clean?', 'a' => 'After the property is empty and before the final inspection or key handover. If you can, leave time for a final walkthrough and any small touch-ups.'],
    $eot ? ['q' => 'How much does a move-out clean cost in Cape Town?', 'a' => 'At SpringKleaners, end-of-tenancy cleaning starts from '.$eotFrom.' for a '.$eotBase.' property, with extra bedrooms and bathrooms priced on top. You see your price when you book online, and we confirm it with you before we start.'] : null,
    ['q' => 'Do I need to clean if I am leaving a rental?', 'a' => 'Most leases require the tenant to return the property in a clean condition, subject to fair wear and tear. Check your lease and the incoming inspection report for the standard expected.'],
    ['q' => 'Will a professional clean guarantee my deposit back?', 'a' => 'No cleaning company can guarantee a deposit outcome. A professional clean helps the property present well at inspection, but deposits can also be affected by damage, unpaid accounts, missing items or other lease conditions.'],
]))" />

<p class="{{ $p }}">
    Moving into a new place as well? <a href="{{ route('services.show', 'move-in-cleaning') }}" class="{{ $a }}">Move-in cleaning</a> gets your next home cupboard-ready before you unpack. Ready to book your move-out clean? <a href="{{ route('booking.show', ['service' => 'end-of-tenancy']) }}" class="{{ $a }}">Book online</a>, call <a href="tel:+27815274711" class="{{ $a }}">+27 81 527 4711</a> or <a href="{{ \App\Support\WhatsApp::forService('End-of-Tenancy Cleaning') }}" target="_blank" rel="noopener noreferrer" class="{{ $a }}">WhatsApp us</a>.
</p>
