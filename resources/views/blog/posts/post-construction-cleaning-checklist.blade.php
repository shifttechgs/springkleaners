<p class="text-navy/80 text-[17px] leading-[1.8] font-medium mb-8">
    A proper post-construction clean covers every surface builders touch — dust, paint splatter, adhesive residue, and construction grime hiding in vents and light fittings — before a new build or renovation is genuinely move-in ready, not just visually tidy.
</p>

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">Why "looks clean" and "is clean" are different after a build</h2>
<p class="text-muted text-[15.5px] leading-[1.85] mb-5">
    Fine construction dust settles into places a quick wipe-down never reaches — air vents, light fittings, the tops of cupboards, window tracks. It resurfaces for weeks after move-in if it isn't properly removed the first time, which is why post-construction cleaning uses different equipment and technique to a standard deep clean.
</p>

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">The room-by-room checklist</h2>
<x-blog.checklist :items="[
    'Every room — dust and debris removed from all surfaces, skirting, and corners',
    'Windows & frames — paint splatter and adhesive residue removed, glass polished',
    'Floors — scrubbed and finished, not just swept',
    'Light fittings & vents — construction dust cleaned out, not just wiped around',
    'Cupboards — wiped inside and out before anything gets stored in them',
    'Kitchen & bathrooms — full appliance and fixture clean, same as a Deep Clean, plus construction-specific residue',
]" />

<x-blog.callout label="Timing matters">
    Book once trades have genuinely finished — cleaning too early means redoing it after final touch-ups. As soon as the site is safe to enter and major work is done, that's the right window.
</x-blog.callout>

<h2 class="text-2xl md:text-[28px] font-extrabold text-navy tracking-tight mt-12 mb-4">Builders, contractors and developers</h2>
<p class="text-muted text-[15.5px] leading-[1.85] mb-5">
    We regularly work directly with contractors and developers to schedule handover cleans around project timelines, not just homeowners cleaning up after their own renovation. From R1,800 for a 2-bedroom, 1-bathroom project, scaling with size — the same transparent pricing model as our other services.
</p>

<x-blog.cta heading="Book your post-construction clean" text="Free inspection, itemised quote, move-in ready when we're done." />

<x-blog.faq :items="[
    ['q' => 'How soon after builders finish should we book a clean?', 'a' => 'As soon as the site is safe to enter and major construction work has finished — ideally once trades have wrapped up so the clean doesn\'t need repeating after final touch-ups.'],
    ['q' => 'Can you remove paint splatter and adhesive residue?', 'a' => 'Yes — that\'s core to this service. We use equipment and techniques suited to construction-grade dust, paint splatter and adhesive residue that a standard clean isn\'t equipped for.'],
    ['q' => 'Is this different from a regular deep clean?', 'a' => 'Yes — it\'s built around construction-grade dust, paint splatter and adhesive residue, plus floor scrubbing and finishing, rather than the inside-appliance and cupboard focus of a standard Deep Clean.'],
]" />

<p class="text-muted text-[15.5px] leading-[1.85]">
    See full pricing and inclusions on our <a href="{{ route('services.show', 'post-construction') }}" class="text-navy font-semibold underline">Post-Construction Cleaning</a> page. Moving in once the clean is done? <a href="{{ route('services.show', 'move-in-cleaning') }}" class="text-navy font-semibold underline">Move-In Cleaning</a> covers the fresh-start sanitising before you unpack.
</p>
