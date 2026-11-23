<?php

// One entry per suburb with its own /areas/{slug} page.
//
// - meta_description: unique per suburb, written for the searcher. Don't put a "from R…" price here: prices
//   live in the services table and change from Admin, so a hardcoded figure goes stale (it did: "from R950").
// - region: how locals would describe where the suburb is. Used in the page's FAQ answers. Don't call the
//   Blaauwberg coast or Atlantic Seaboard "Northern Suburbs".
// - nearby: slugs of genuinely neighbouring served suburbs, for the "Nearby areas" links. Keep it geographic.
return [

    'milnerton' => [
        'name' => 'Milnerton',
        'region' => 'Milnerton and the Blaauwberg area',
        'meta_description' => 'House, apartment and rental cleaning in Milnerton: deep cleans, end-of-tenancy and move-in cleans. Insured, vetted team. See your price and book online.',
        'blurb' => 'Milnerton is one of our busiest patches — from the freestanding homes around the golf course and lagoon to the apartments near Woodbridge Island and the Milnerton lighthouse stretch. Whether it\'s a family home that needs a seasonal reset or a rental changing hands, our teams know the area well. The lagoon\'s birdlife and wind mean windowsills and outdoor living areas here collect more dust and pollen than most suburbs, so we pay extra attention to those spots.',
        'property_note' => 'A mix of freestanding family homes, golf course-facing properties, and apartments near the lagoon and Woodbridge Island.',
        'nearby' => ['century-city', 'richwood', 'montague-gardens', 'table-view', 'flamingo-vlei', 'paarden-eiland'],
        'updated_at' => '2026-11-23',
    ],

    'sunningdale' => [
        'name' => 'Sunningdale',
        'region' => 'the Blaauwberg area, near Parklands and Table View',
        'meta_description' => 'Cleaning for Sunningdale homes and estates: deep cleans, move-in cleans and builders-dust clean-ups for newer houses. Insured team. Get your price online.',
        'blurb' => 'Sunningdale\'s newer estates and family homes near the West Coast Road retail node are a regular stop for our teams — from move-in cleans for young families to a standing deep clean between busy weeks. Many homes here are only a few years old, which usually means less built-up grime but more builder\'s dust still settling into vents and skirting long after handover.',
        'property_note' => 'Mostly newer freestanding homes and secure complexes near West Coast Road, popular with young families.',
        'nearby' => ['parklands', 'west-beach', 'table-view', 'blouberg', 'bloubergstrand', 'big-bay'],
        'updated_at' => '2026-11-23',
    ],

    'blouberg' => [
        'name' => 'Blouberg',
        'region' => 'the Blaauwberg coast',
        'meta_description' => 'Deep cleaning, end-of-tenancy and short-let turnover cleaning for Blouberg homes and complexes. Insured, vetted cleaners. See your price and book online.',
        'blurb' => 'Blouberg\'s mix of family homes, sectional title complexes and holiday rentals means we see everything here — from a routine deep clean to a fast end-of-tenancy turnaround between guests or tenants. Being a few streets back from the coast still means dealing with the same salt-laden southeaster that Bloubergstrand gets, just with less sand underfoot.',
        'property_note' => 'A blend of family homes, sectional title complexes, and short-let holiday rentals set back from the beachfront.',
        'nearby' => ['bloubergstrand', 'big-bay', 'west-beach', 'table-view', 'sunningdale', 'parklands'],
        'updated_at' => '2026-11-23',
    ],

    'parklands' => [
        'name' => 'Parklands',
        'region' => 'the Blaauwberg area, close to our Parklands base',
        'meta_description' => 'Cleaning services in Parklands and Parklands North: deep, move-in and post-construction cleans for townhouses and estates. Get your price and book online.',
        'blurb' => 'Parklands and Parklands North are some of the fastest-growing suburbs we service — mostly townhouses and secure estates full of busy families and professionals who would rather spend a Saturday relaxing than cleaning. New sectional title developments are still going up along the Sandown Road corridor, which keeps a steady flow of post-construction and pre-move-in cleans alongside our regular deep cleans.',
        'property_note' => 'Predominantly townhouses and estate homes across Parklands and Parklands North, many in newer sectional title developments.',
        'nearby' => ['sunningdale', 'table-view', 'west-beach', 'blouberg', 'flamingo-vlei', 'burgundy-estate'],
        'updated_at' => '2026-11-23',
    ],

    'century-city' => [
        'name' => 'Century City',
        'region' => 'the Milnerton and Century City area',
        'meta_description' => 'Apartment and end-of-tenancy cleaning in Century City, with building access arranged ahead of time. Insured, vetted team. See your price and book online.',
        'blurb' => 'Century City\'s apartment blocks and canal-side estates around Canal Walk see a lot of tenant turnover, which makes end-of-tenancy cleaning one of our most requested services here — alongside deep cleans for owner-occupied units. Building-managed access and secure parking are the norm in most of these complexes, so we coordinate arrival times with body corporates and buzzer access ahead of the clean rather than leaving it to the day.',
        'property_note' => 'High-rise and mid-rise apartments, plus canal-side estates with frequent rental turnover, most with managed building access.',
        'nearby' => ['milnerton', 'monte-vista', 'edgemead', 'richwood', 'paarden-eiland', 'montague-gardens'],
        'updated_at' => '2026-11-23',
    ],

    'table-view' => [
        'name' => 'Table View',
        'region' => 'the Blaauwberg coast',
        'meta_description' => 'Deep cleaning, end-of-tenancy and window cleaning for Table View homes and beachfront apartments. Insured, vetted cleaners. See your price and book online.',
        'blurb' => 'Table View\'s beachfront apartments and family homes keep our teams busy year-round — sea air means more dust and salt residue than most suburbs, so a proper deep clean makes a bigger difference here. The promenade stretch facing Robben Island and Table Mountain is one of the most photographed views in Cape Town, and it\'s exactly the properties along that strip where windows and balconies need the most regular attention.',
        'property_note' => 'Beachfront apartments, family homes and a steady mix of holiday and long-term rentals along the promenade.',
        'nearby' => ['flamingo-vlei', 'parklands', 'west-beach', 'blouberg', 'milnerton', 'sunningdale'],
        'updated_at' => '2026-11-23',
    ],

    'big-bay' => [
        'name' => 'Big Bay',
        'region' => 'the Blaauwberg coast',
        'meta_description' => 'End-of-tenancy, short-let turnover and deep cleaning for Big Bay apartments and homes. Insured, background-checked team. See your price and book online.',
        'blurb' => 'Big Bay\'s newer apartment developments and luxury homes are often on a tight turnaround between guests or owners — we\'re used to fast, high-standard end-of-tenancy and pre-arrival cleans in this area. It\'s one of the Western Cape\'s best-known kitesurfing spots, so sand tracked in from the beach and gear left in entryways and garages is part of the job here more than almost anywhere else we service.',
        'property_note' => 'Newer luxury apartments and homes, many on short-let or high-turnover rental cycles near the beachfront.',
        'nearby' => ['bloubergstrand', 'blouberg', 'west-beach', 'sunningdale', 'parklands', 'table-view'],
        'updated_at' => '2026-11-23',
    ],

    'bloubergstrand' => [
        'name' => 'Bloubergstrand',
        'region' => 'the Blaauwberg coast',
        'meta_description' => 'Cleaning for Bloubergstrand beachfront homes and holiday lets: deep cleans, Airbnb turnovers and window cleaning. Insured team. Book online in minutes.',
        'blurb' => 'Bloubergstrand\'s famous beachfront homes and holiday lets — many with that postcard Table Mountain view — need cleaning that keeps up with sand, sea air and a steady stream of guests. That\'s exactly what we\'re set up for. Between the southeaster and the fine beach sand, window tracks, outdoor furniture and entryway floors need far more frequent attention here than in suburbs further inland.',
        'property_note' => 'Beachfront homes and holiday rentals, with a high share of short-let turnover cleans and heavy sea-air exposure.',
        'nearby' => ['big-bay', 'blouberg', 'west-beach', 'table-view', 'sunningdale', 'parklands'],
        'updated_at' => '2026-11-23',
    ],

    'sea-point' => [
        'name' => 'Sea Point',
        'region' => 'the Atlantic Seaboard',
        'meta_description' => 'Apartment, end-of-tenancy and short-let cleaning in Sea Point. Insured, background-checked cleaners. See your price online or WhatsApp us for a quote.',
        'blurb' => 'Sea Point\'s apartment blocks along the Promenade are some of the most in-demand rentals on the Atlantic Seaboard, which means fast end-of-tenancy turnarounds and a steady stream of short-let cleans between guests. Constant sea air off the Atlantic means salt residue on windows and balconies builds up faster here than almost anywhere else we service, so we treat that as standard rather than an afterthought.',
        'property_note' => 'Predominantly apartment blocks along the Promenade and Regent Road, with a high share of rental and short-let turnover.',
        'nearby' => ['green-point', 'paarden-eiland', 'century-city', 'milnerton'],
        'updated_at' => '2026-11-23',
    ],

    'green-point' => [
        'name' => 'Green Point',
        'region' => 'the Atlantic Seaboard',
        'meta_description' => 'Apartment, short-let and deep cleaning in Green Point, near the Stadium and V&A Waterfront. Insured, vetted cleaners. See your price and book online.',
        'blurb' => 'Green Point\'s mix of apartments near the Stadium and Green Point Park, plus a scattering of older houses further from the coast, means our teams handle everything from a quick pre-guest turnaround to a full deep clean of a family home. Proximity to the V&A Waterfront keeps short-let turnover high, especially around event weekends at the Stadium.',
        'property_note' => 'Apartments near the Stadium and Waterfront precinct, plus older houses further inland, with frequent short-let turnover.',
        'nearby' => ['sea-point', 'paarden-eiland', 'century-city', 'milnerton'],
        'updated_at' => '2026-11-23',
    ],

    'west-beach' => [
        'name' => 'West Beach',
        'region' => 'the Blaauwberg coast, between Table View and Bloubergstrand',
        'meta_description' => 'Deep cleaning, end-of-tenancy and window cleaning for West Beach homes, between Table View and Bloubergstrand. Insured team. Get your price online.',
        'blurb' => 'West Beach\'s newer homes and beachfront properties sit on the coast just south of Bloubergstrand, bordering Table View, with a quieter, more residential feel than the busier stretches of Blouberg further along. Sea air and sand are still part of daily life here, so window tracks and outdoor entertainment areas get the same attention we give the rest of the Blouberg coastline.',
        'property_note' => 'Newer freestanding homes and beachfront properties, quieter than the busier Blouberg strip further along the coast.',
        'nearby' => ['table-view', 'bloubergstrand', 'blouberg', 'sunningdale', 'parklands', 'big-bay'],
        'updated_at' => '2026-11-23',
    ],

    'monte-vista' => [
        'name' => 'Monte Vista',
        'region' => 'Cape Town\'s Northern Suburbs, near Century City',
        'meta_description' => 'Deep cleaning and recurring house cleaning for Monte Vista family homes. Insured, background-checked cleaners. See your price and book online.',
        'blurb' => 'Monte Vista\'s tree-lined streets and established gardens are part of one of the Northern Suburbs\' older, leafier neighbourhoods, close to the N1 and a short drive from Century City. Mature trees mean more leaf litter and garden debris tracked into entryways and patios than newer developments see, which our teams factor into every visit.',
        'property_note' => 'Established freestanding homes with mature gardens, in one of the Northern Suburbs\' older, leafier neighbourhoods.',
        'nearby' => ['edgemead', 'century-city', 'goodwood', 'plattekloof', 'bothasig', 'richwood'],
        'updated_at' => '2026-11-23',
    ],

    'edgemead' => [
        'name' => 'Edgemead',
        'region' => 'Cape Town\'s Northern Suburbs, near the N7',
        'meta_description' => 'Recurring house cleaning and deep cleans for Edgemead family homes. Insured, background-checked cleaners. See your price and book online in minutes.',
        'blurb' => 'Edgemead\'s quiet, family-oriented streets are mostly freestanding homes with established gardens, close to the N7 and Century City. It\'s a settled, low-turnover suburb, so most of our work here is recurring maintenance cleans and periodic deep cleans rather than fast end-of-tenancy turnarounds.',
        'property_note' => 'Freestanding family homes with established gardens, close to the N7 and Century City.',
        'nearby' => ['bothasig', 'monte-vista', 'plattekloof', 'goodwood', 'richwood', 'century-city'],
        'updated_at' => '2026-11-23',
    ],

    'bothasig' => [
        'name' => 'Bothasig',
        'region' => 'Cape Town\'s Northern Suburbs, just off the N7',
        'meta_description' => 'Deep cleaning, recurring house cleaning and move-in cleans in Bothasig. Insured, background-checked cleaners. See your price and book online.',
        'blurb' => 'Bothasig sits right off the N7, making it one of the easiest Northern Suburbs neighbourhoods for our teams to reach on a tight schedule. It\'s a mix of older established homes and newer infill developments, with families and long-term owners making up most of our client base here rather than fast-turnover rentals.',
        'property_note' => 'A mix of older established homes and newer infill developments, just off the N7.',
        'nearby' => ['edgemead', 'richwood', 'plattekloof', 'monte-vista', 'burgundy-estate', 'century-city'],
        'updated_at' => '2026-11-23',
    ],

    'montague-gardens' => [
        'name' => 'Montague Gardens',
        'region' => 'the Milnerton area',
        'meta_description' => 'Office and commercial cleaning for Montague Gardens business parks, showrooms and units, priced per m². Insured team. Request a quote on WhatsApp.',
        'blurb' => 'Montague Gardens is one of Milnerton\'s main light-industrial and business-park nodes, which makes it a natural fit for our Office & Commercial Cleaning service alongside the handful of residential pockets nearby. Showrooms, offices and small business premises here need a different cleaning rhythm to a home, planned around how the business runs rather than a family\'s week.',
        'property_note' => 'Primarily light-industrial units, warehouses and business parks, with a small surrounding residential pocket.',
        'nearby' => ['milnerton', 'flamingo-vlei', 'table-view', 'paarden-eiland', 'century-city'],
        'updated_at' => '2026-11-23',
    ],

    'paarden-eiland' => [
        'name' => 'Paarden Eiland',
        'region' => 'the area between Milnerton and the city, near the harbour',
        'meta_description' => 'Office and commercial cleaning for Paarden Eiland showrooms and business units, plus loft apartments. Insured team. Request a quote or book online.',
        'blurb' => 'Paarden Eiland\'s warehouses, showrooms and light-industrial units near the harbour make it another strong fit for our Office & Commercial Cleaning service, alongside the loft and studio conversions that have slowly been appearing among the older industrial buildings. Access is usually coordinated directly with a site manager or tenant rather than a homeowner.',
        'property_note' => 'Warehouses, showrooms and light-industrial units near the harbour, with a growing number of loft-style residential conversions.',
        'nearby' => ['milnerton', 'montague-gardens', 'century-city', 'green-point', 'sea-point'],
        'updated_at' => '2026-11-23',
    ],

    'richwood' => [
        'name' => 'Richwood',
        'region' => 'the Milnerton area, near the N7',
        'meta_description' => 'Deep cleaning and recurring house cleaning for Richwood homes and complexes. Insured, background-checked cleaners. See your price and book online.',
        'blurb' => 'Richwood\'s quiet residential streets, tucked between Milnerton and the N7, are a mix of freestanding homes and smaller sectional title complexes. It\'s a settled family suburb rather than a high-turnover rental area, so most bookings here are recurring maintenance cleans or a periodic deep clean rather than end-of-tenancy work.',
        'property_note' => 'A mix of freestanding homes and smaller sectional title complexes in a quiet, settled family suburb.',
        'nearby' => ['bothasig', 'edgemead', 'milnerton', 'burgundy-estate', 'century-city', 'monte-vista'],
        'updated_at' => '2026-11-23',
    ],

    'burgundy-estate' => [
        'name' => 'Burgundy Estate',
        'region' => 'the Milnerton area, off the M13',
        'meta_description' => 'Deep, move-in and end-of-tenancy cleaning for Burgundy Estate homes and sectional title units. Insured, vetted team. See your price and book online.',
        'blurb' => 'Burgundy Estate is a large residential estate in the Milnerton area, bordering the M13 with quick access to the N7 and N1. It\'s mostly sectional title complexes alongside freestanding family homes, and with so many units changing hands, move-in and end-of-tenancy cleans are as common here as our regular deep cleans.',
        'property_note' => 'Mostly sectional title complexes, plus freestanding family homes, in a large estate off the M13.',
        'nearby' => ['richwood', 'bothasig', 'parklands', 'table-view', 'milnerton'],
        'updated_at' => '2026-11-23',
    ],

    'flamingo-vlei' => [
        'name' => 'Flamingo Vlei',
        'region' => 'Table View, on the Blaauwberg side of Rietvlei',
        'meta_description' => 'Deep cleaning and end-of-tenancy cleaning for Flamingo Vlei family homes in Table View. Insured, vetted cleaners. See your price and book online.',
        'blurb' => 'Flamingo Vlei takes its name from the wetland it borders — part of the wider Rietvlei system that draws real flamingos and other birdlife within sight of the neighbourhood. Set slightly inland from the busier Blouberg beachfront, it\'s a mix of established family homes dealing with the same wind and sea-air exposure as the rest of the coastal Blaauwberg strip, just with a quieter, more residential feel.',
        'property_note' => 'Established family homes bordering the Flamingo Vlei wetland, slightly inland from the busier Blouberg beachfront.',
        'nearby' => ['table-view', 'milnerton', 'montague-gardens', 'parklands', 'blouberg'],
        'updated_at' => '2026-11-23',
    ],

    'durbanville' => [
        'name' => 'Durbanville',
        'region' => 'Cape Town\'s Northern Suburbs',
        'meta_description' => 'Deep cleaning, move-in and end-of-tenancy cleaning for Durbanville homes, estates and townhouses. Insured, vetted team. See your price and book online.',
        'blurb' => 'Durbanville sits on the northern edge of the Northern Suburbs, where the suburbs give way to the vineyards of the Durbanville wine valley. Homes range from established family houses on larger plots to newer townhouse complexes and security estates, so a clean here can be anything from a big-house deep clean to a compact end-of-tenancy clean. Bigger homes with extra living areas and a garage are where pricing by bedroom, bathroom and extra room, plus add-ons, gets you an accurate number upfront.',
        'property_note' => 'Established family homes on larger plots, plus newer townhouse complexes and security estates near the wine valley.',
        'nearby' => ['bellville', 'brackenfell', 'plattekloof', 'parow'],
        'updated_at' => '2026-11-23',
    ],

    'bellville' => [
        'name' => 'Bellville',
        'region' => 'Cape Town\'s Northern Suburbs',
        'meta_description' => 'Apartment, end-of-tenancy and office cleaning in Bellville, from Tyger Valley to the CBD. Insured, background-checked cleaners. See your price online.',
        'blurb' => 'Bellville is one of the Northern Suburbs\' main business and residential centres, from the offices and apartments around Tyger Valley to the older streets near the Bellville CBD. With UWC and CPUT\'s Bellville campus nearby, there is a large rental market of flats and townhouses, which is where end-of-tenancy and move-in cleans come in. The office blocks along the N1 corridor suit our Office & Commercial Cleaning, done on weekends so it stays out of your working week.',
        'property_note' => 'Apartments, townhouses and student rentals, plus office blocks around Tyger Valley and the N1 corridor.',
        'nearby' => ['durbanville', 'parow', 'brackenfell', 'plattekloof', 'goodwood'],
        'updated_at' => '2026-11-23',
    ],

    'parow' => [
        'name' => 'Parow',
        'region' => 'Cape Town\'s Northern Suburbs',
        'meta_description' => 'Deep cleaning, end-of-tenancy and move-in cleaning for Parow houses and flats, near Voortrekker Road. Insured, vetted team. See your price and book online.',
        'blurb' => 'Parow is one of the older Northern Suburbs, running from the busy Voortrekker Road corridor to the quieter streets of Parow North and Parow Valley, close to Tygerberg Hospital. Older freestanding homes sit alongside blocks of flats and rentals. Older homes usually need more time in kitchens, bathrooms and built-in cupboards, which is exactly what a deep clean is scoped for.',
        'property_note' => 'Older freestanding homes, blocks of flats and rentals along the Voortrekker Road corridor and in Parow North.',
        'nearby' => ['goodwood', 'bellville', 'plattekloof', 'edgemead', 'monte-vista'],
        'updated_at' => '2026-11-23',
    ],

    'goodwood' => [
        'name' => 'Goodwood',
        'region' => 'Cape Town\'s Northern Suburbs',
        'meta_description' => 'House, flat and rental cleaning in Goodwood, near GrandWest and the N1: deep cleans, end-of-tenancy and move-in cleans. Insured team. Book online.',
        'blurb' => 'Goodwood sits on the Voortrekker Road corridor west of Parow, close to the N1, GrandWest and the Goodwood showgrounds. It is a mix of older semi-detached and freestanding homes, flats and newer complexes, with plenty of rentals, so end-of-tenancy and move-in cleans fit naturally alongside regular deep cleans. Monte Vista, Edgemead and Century City are all next door.',
        'property_note' => 'Older semi-detached and freestanding homes, flats and newer complexes, with a good share of rentals.',
        'nearby' => ['parow', 'monte-vista', 'edgemead', 'century-city', 'bellville'],
        'updated_at' => '2026-11-23',
    ],

    'brackenfell' => [
        'name' => 'Brackenfell',
        'region' => 'Cape Town\'s Northern Suburbs',
        'meta_description' => 'Deep cleaning, recurring house cleaning and move-in cleans for Brackenfell family homes and complexes. Insured, vetted team. See your price online.',
        'blurb' => 'Brackenfell is a family suburb on the eastern side of the Northern Suburbs, with quick access to the N1 and R300. Most homes are freestanding family houses and secure complexes, many of them newer, which suits a move-in clean when a property changes hands and recurring house cleaning for busy households.',
        'property_note' => 'Freestanding family homes and secure complexes, many of them newer, near the N1 and R300.',
        'nearby' => ['durbanville', 'bellville', 'parow'],
        'updated_at' => '2026-11-23',
    ],

    'plattekloof' => [
        'name' => 'Plattekloof',
        'region' => 'Cape Town\'s Northern Suburbs',
        'meta_description' => 'Deep cleaning and window cleaning for Plattekloof homes, plus move-in and end-of-tenancy cleans. Insured, background-checked team. Book online.',
        'blurb' => 'Plattekloof sits on the hills above Parow and Panorama, close to Edgemead and Bothasig, and many homes here are built to take in the views across Table Bay towards Table Mountain. That usually means larger houses with plenty of glass, balconies and entertainment areas, which is where window cleaning and the balcony add-on earn their place on top of a standard deep clean.',
        'property_note' => 'Larger family homes on the hillside, many with big windows, balconies and views across Table Bay.',
        'nearby' => ['parow', 'edgemead', 'bothasig', 'monte-vista', 'bellville', 'durbanville'],
        'updated_at' => '2026-11-23',
    ],

];
