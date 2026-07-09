<?php

return [

    'pricing' => [
        'label' => 'Pricing & Process',
        'icon' => 'pricing',
        'image' => '/images/works/2.png',
        'blurb' => 'What things cost, how quotes work, and when you pay.',
        'faqs' => [
            ['q' => 'How much does a deep clean cost in Cape Town?', 'a' => 'From R1,200 for 2 bedrooms and 1 bathroom, plus R100 per extra bedroom or bathroom — confirmed with a free inspection before we start.', 'link' => ['route' => 'services.show', 'param' => 'deep-cleaning', 'label' => 'Deep Cleaning']],
            ['q' => 'How much does end-of-tenancy cleaning cost?', 'a' => 'From R1,200 for 2 bedrooms and 1 bathroom, plus R120 per extra bedroom and R100 per extra bathroom.', 'link' => ['route' => 'services.show', 'param' => 'end-of-tenancy', 'label' => 'End-of-Tenancy Cleaning']],
            ['q' => 'How much does post-construction cleaning cost?', 'a' => 'From R1,800 for 2 bedrooms and 1 bathroom, plus R150 per extra bedroom and R120 per extra bathroom.', 'link' => ['route' => 'services.show', 'param' => 'post-construction', 'label' => 'Post-Construction Cleaning']],
            ['q' => 'Do you charge a call-out fee for inspections?', 'a' => 'No — the site inspection or details-based estimate itself is always free. A few specialist services (Office & Commercial, Carpet, Upholstery, Window Cleaning) have a minimum call-out fee for the job itself, published on each service\'s page.'],
            ['q' => 'Is the quoted price final, or can it change on the day?', 'a' => 'The price you see online is a real starting estimate, not a placeholder — but we always confirm it against your actual property or job via a free inspection, and you approve the final quote before we start. No surprises on the day.'],
            ['q' => 'What payment methods do you accept?', 'a' => 'Cash or EFT. We don\'t currently process online card payments — invoices are issued and payment is tracked manually after the job.'],
            ['q' => 'Do you offer discounts for recurring bookings?', 'a' => 'Our Recurring House Cleaning service is already priced lower per visit than a full Deep Clean by design, since it\'s a lighter maintenance scope. We don\'t currently run an additional loyalty discount on top of that — ask us on WhatsApp if you\'re setting up a long-term recurring schedule.'],
            ['q' => 'How far in advance should I book?', 'a' => 'As early as you can. We currently operate weekend-only with limited daily capacity, so slots — especially around end-of-month moving dates — can fill up ahead of time.'],
            ['q' => 'Can I get a same-day or same-week booking?', 'a' => 'Same-week is often possible; message us on WhatsApp with your address and preferred date and we\'ll check availability immediately. Same-day is unlikely given our weekend-only, limited-capacity schedule.'],
            ['q' => 'Do you charge more for last-minute bookings?', 'a' => 'No — our published prices don\'t change based on how soon you book. Availability may simply be more limited the closer you get to your preferred date.'],
        ],
    ],

    'trust' => [
        'label' => 'Trust, Insurance & Safety',
        'icon' => 'trust',
        'image' => '/images/works/1.png',
        'blurb' => 'Insurance, vetting, guarantees — what protects you.',
        'faqs' => [
            ['q' => 'Are your cleaners background-checked?', 'a' => 'Yes — every team member is background-checked before joining us.'],
            ['q' => 'Are you insured if something gets damaged?', 'a' => 'Yes. SpringKleaners is fully insured for public liability, so you\'re covered in the rare event something is damaged during a clean. Let your team or our office know immediately and we\'ll sort it out.'],
            ['q' => 'Is SpringKleaners a registered company?', 'a' => 'Yes — registration number 2021/363748/07. See our About page for more on who we are.', 'link' => ['route' => 'about', 'param' => [], 'label' => 'About SpringKleaners']],
            ['q' => 'Do I need to supervise the cleaners while they work?', 'a' => 'No. Most clients give access instructions and return to a finished job. If you\'d prefer to be present, that\'s completely fine too.'],
            ['q' => 'What cleaning products do you use — are they safe for pets and kids?', 'a' => 'Our teams arrive fully equipped with professional-grade, eco-friendly cleaning products and all necessary equipment — you don\'t need to provide anything.'],
            ['q' => 'What happens if I\'m not satisfied with the clean?', 'a' => 'Let us know within 24 hours of the clean and we\'ll return to address it at no additional charge — that\'s our satisfaction guarantee.'],
            ['q' => 'Do you offer a satisfaction guarantee?', 'a' => 'Yes — if you\'re not happy with any aspect of the clean, tell us within 24 hours and we\'ll return to fix it at no extra charge.'],
            ['q' => 'Are your staff trained, or subcontracted per job?', 'a' => 'Our cleaning teams are SpringKleaners\' own background-checked staff, not ad-hoc subcontractors — that\'s how we hold a consistent standard across every job.'],
        ],
    ],

    'services' => [
        'label' => 'Per-Service',
        'icon' => 'services',
        'image' => '/images/works/we_clean.avif',
        'blurb' => 'Specifics on Deep Cleaning, End-of-Tenancy, Post-Construction, Office & Commercial, and Carpet/Upholstery/Window Cleaning.',
        'faqs' => [
            // Deep Cleaning
            ['q' => 'What\'s included in a deep clean?', 'a' => 'Kitchen and bathroom deep clean, all rooms vacuumed and mopped, inside appliances, baseboards and skirting, cupboard interiors, and light fixtures and switches dusted.', 'group' => 'Deep Cleaning', 'link' => ['route' => 'services.show', 'param' => 'deep-cleaning', 'label' => 'Deep Cleaning']],
            ['q' => 'How long does a deep clean take?', 'a' => 'On average around 4 hours, depending on the size of the property and number of bedrooms and bathrooms — we confirm an accurate estimate after the free inspection.', 'group' => 'Deep Cleaning'],
            ['q' => 'How is deep cleaning different from a regular clean?', 'a' => 'A regular clean maintains a space that\'s already tidy. A deep clean resets it — inside appliances, cupboard interiors, and built-up grime in bathrooms and kitchens that a weekly clean skips.', 'group' => 'Deep Cleaning'],
            ['q' => 'Do I need to provide any equipment or products?', 'a' => 'No. Our teams arrive fully equipped with professional-grade cleaning products and all necessary equipment for every service.', 'group' => 'Deep Cleaning'],
            ['q' => 'How often should I book a deep clean?', 'a' => 'There\'s no fixed rule — many clients book a Deep Clean seasonally, or before/after a big life event, then maintain that standard in between with Recurring House Cleaning.', 'group' => 'Deep Cleaning', 'link' => ['route' => 'services.show', 'param' => 'recurring-house-cleaning', 'label' => 'Recurring House Cleaning']],

            // End-of-Tenancy
            ['q' => 'What\'s included in an end-of-tenancy clean?', 'a' => 'Everything in a Deep Clean, plus walls wiped and marks removed, windows and tracks cleaned, inside all cupboards and wardrobes, kitchen appliances deep cleaned, and a condition checklist.', 'group' => 'End-of-Tenancy Cleaning', 'link' => ['route' => 'services.show', 'param' => 'end-of-tenancy', 'label' => 'End-of-Tenancy Cleaning']],
            ['q' => 'Will this guarantee I get my deposit back?', 'a' => 'No cleaning company can guarantee a deposit outcome — that\'s the landlord\'s call. But a poor final clean is the single biggest factor in most deposit disputes, so we clean to the standard landlords and letting agents actually inspect against, and provide a condition checklist as proof of scope.', 'group' => 'End-of-Tenancy Cleaning'],
            ['q' => 'Who is responsible for paying for end-of-lease cleaning in South Africa?', 'a' => 'Under most standard South African lease agreements, tenants are expected to return a property in the same clean condition it was received in (fair wear and tear aside), and landlords can often deduct cleaning costs from the deposit if it isn\'t. Always check your specific lease for the exact clause — this is general guidance, not legal advice.', 'group' => 'End-of-Tenancy Cleaning'],
            ['q' => 'Do you provide a checklist for the landlord/agent?', 'a' => 'Yes — every End-of-Tenancy clean includes a condition checklist you can use as proof of the clean\'s scope during a handover inspection.', 'group' => 'End-of-Tenancy Cleaning'],
            ['q' => 'Can you clean before or after we\'ve moved our furniture out?', 'a' => 'Ideally once the property is empty, so we can reach every surface, corner and cupboard — but we can work around a partial move if needed.', 'group' => 'End-of-Tenancy Cleaning'],
            ['q' => 'Do you clean appliances as part of end-of-tenancy?', 'a' => 'Yes — kitchen appliances are deep cleaned as standard, on top of everything included in a Deep Clean.', 'group' => 'End-of-Tenancy Cleaning'],

            // Post-Construction
            ['q' => 'What does post-construction cleaning include?', 'a' => 'Dust and debris removal, paint and adhesive cleanup, windows and frames polished, floor scrubbing and finishing, light fittings and vents cleaned, and cupboards wiped inside and out.', 'group' => 'Post-Construction Cleaning', 'link' => ['route' => 'services.show', 'param' => 'post-construction', 'label' => 'Post-Construction Cleaning']],
            ['q' => 'How long after builders finish should we book a clean?', 'a' => 'As soon as the site is safe to enter and major construction work has finished — ideally once trades have wrapped up so the clean doesn\'t need repeating after final touch-ups.', 'group' => 'Post-Construction Cleaning'],
            ['q' => 'Can you remove paint splatter and adhesive residue?', 'a' => 'Yes — that\'s core to this service. We use equipment and techniques suited to construction-grade dust, paint splatter and adhesive residue that a standard clean isn\'t equipped for.', 'group' => 'Post-Construction Cleaning'],
            ['q' => 'Do you clean construction dust from air vents and light fixtures?', 'a' => 'Yes, light fittings and vents are cleaned as standard as part of every Post-Construction clean.', 'group' => 'Post-Construction Cleaning'],
            ['q' => 'Is post-construction cleaning different from a regular deep clean?', 'a' => 'Yes — it\'s built around construction-grade dust, paint splatter and adhesive residue (plus floor scrubbing and finishing), rather than the inside-appliance and cupboard focus of a standard Deep Clean.', 'group' => 'Post-Construction Cleaning'],

            // Office & Commercial
            ['q' => 'Do you offer daily, weekly, or monthly office cleaning?', 'a' => 'We quote office cleaning per visit or as a monthly contract. Given our current weekend-only operating model, most office contracts are scheduled over weekends or before/after business hours rather than daily during the week — message us if your business needs a different schedule.', 'group' => 'Office & Commercial Cleaning', 'link' => ['route' => 'services.show', 'param' => 'office-commercial-cleaning', 'label' => 'Office & Commercial Cleaning']],
            ['q' => 'Can you clean outside of business hours?', 'a' => 'Yes — most office contracts are cleaned over the weekend or before opening on Monday, so your team never works around us.', 'group' => 'Office & Commercial Cleaning'],
            ['q' => 'Do you provide cleaning supplies for offices?', 'a' => 'Yes, the same as every service — our teams bring professional-grade products and equipment, nothing required from you.', 'group' => 'Office & Commercial Cleaning'],
            ['q' => 'Can you handle multi-floor or multi-tenant buildings?', 'a' => 'Yes, though those move to a custom monthly contract quote rather than the standard per-m² rate, since pricing stops scaling linearly at that size.', 'group' => 'Office & Commercial Cleaning'],
            ['q' => 'Do you offer a fixed monthly contract rate for offices?', 'a' => 'Yes — we quote both per-visit and monthly contract rates for office cleaning, whichever suits your business.', 'group' => 'Office & Commercial Cleaning'],

            // Carpet, Upholstery & Windows
            ['q' => 'Do you offer steam cleaning for carpets?', 'a' => 'Yes — we use hot-water extraction, which lifts embedded dirt, pet hair and odours out of the pile rather than just the surface.', 'group' => 'Carpet, Upholstery & Window Cleaning', 'link' => ['route' => 'services.show', 'param' => 'carpet-cleaning', 'label' => 'Carpet Cleaning']],
            ['q' => 'Can you remove stains from upholstery?', 'a' => 'Yes, stain-specific spot treatment is included as standard on both fabric and leather upholstery.', 'group' => 'Carpet, Upholstery & Window Cleaning', 'link' => ['route' => 'services.show', 'param' => 'upholstery-cleaning', 'label' => 'Upholstery Cleaning']],
            ['q' => 'Do you clean interior and exterior windows?', 'a' => 'Yes — interior and exterior glass, frames, sills and tracks are all included as standard.', 'group' => 'Carpet, Upholstery & Window Cleaning', 'link' => ['route' => 'services.show', 'param' => 'window-cleaning', 'label' => 'Window Cleaning']],
            ['q' => 'How long does carpet cleaning take to dry?', 'a' => 'Typically a few hours with normal ventilation — most clients can walk on carpets (in socks) within 2-4 hours thanks to our fast-dry extraction technique.', 'group' => 'Carpet, Upholstery & Window Cleaning'],
            ['q' => 'Do you clean curtains and blinds?', 'a' => 'Yes — as a dedicated Blind & Curtain Cleaning service, on-site, with the method adjusted for venetian, vertical or fabric.', 'group' => 'Carpet, Upholstery & Window Cleaning', 'link' => ['route' => 'services.show', 'param' => 'blind-curtain-cleaning', 'label' => 'Blind & Curtain Cleaning']],
        ],
    ],

    'areas' => [
        'label' => 'Areas We Serve',
        'icon' => 'areas',
        'image' => '/images/works/home.avif',
        'blurb' => 'The 19 Cape Town suburbs we cover, and what happens if yours isn\'t listed.',
        'faqs' => [
            ['q' => 'Which Cape Town suburbs do you service?', 'a' => 'Milnerton, Sunningdale, Blouberg, Parklands, Century City, Table View, Big Bay, Bloubergstrand, Sea Point, Green Point, West Beach, Monte Vista, Edgemead, Bothasig, Montague Gardens, Paarden Eiland, Richwood, Burgundy Estate and Flamingo Vlei, plus a wider surrounding area covered on request.'],
            ['q' => 'Do you service apartments and complexes, or only houses?', 'a' => 'Both — many of our regular jobs are in apartments and sectional title complexes (Century City, Table View and Big Bay especially), not just freestanding houses.'],
            ['q' => 'Can you service areas outside the Northern Suburbs on request?', 'a' => 'Ask us on WhatsApp — our published area pages aren\'t necessarily the limit of where we\'ll travel for the right job.'],
        ],
    ],

    'comparison' => [
        'label' => 'Comparisons',
        'icon' => 'comparison',
        'image' => '/images/works/3.png',
        'blurb' => 'Which service actually fits your situation.',
        'faqs' => [
            ['q' => 'What\'s the difference between a regular clean and a deep clean?', 'a' => 'A Deep Clean resets a home from scratch — inside appliances, cupboard interiors, built-up grime. Our Recurring House Cleaning service maintains that standard week to week at a lighter, lower-cost scope. Most clients use both.'],
            ['q' => 'Should I hire a cleaning company or an individual cleaner?', 'a' => 'That depends on what matters most to you. A registered, insured company gives you accountability an individual cleaner typically can\'t: public liability cover if something\'s damaged, background-checked staff, and a stated satisfaction guarantee. An individual cleaner may work out cheaper for very simple, low-risk jobs.'],
            ['q' => 'How do I choose between a once-off clean and a recurring plan?', 'a' => 'If you need a single reset — moving house, after a renovation, before an event — go once-off (Deep Clean, End-of-Tenancy or Post-Construction). If you want to maintain that standard ongoing, Recurring House Cleaning keeps it up on a weekly or bi-weekly schedule at a lighter scope and lower cost per visit.'],
        ],
    ],

];
