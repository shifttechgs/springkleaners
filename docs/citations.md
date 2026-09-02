# Citations & backlinks

Off-site authority work — directory citations, referral partnerships, digital PR. This is the gap flagged in [`docs/seo.md`](./seo.md) as not-yet-started, and it matters more for both traditional ranking and AI-answer-engine citability than anything on-page: external validation (other sites/directories/press mentioning the business) is a signal no amount of on-site schema can substitute for.

I can't create or verify these accounts myself — signup requires email/phone verification the business owner controls, and in some cases a business document upload. Everything below is prepped so claiming each one is a five-minute copy-paste job, not a from-scratch write.

## Canonical NAP — use this exact block everywhere

Directory citations only help if they're **consistent** — a mismatched address or phone format across listings actively hurts local ranking. Copy this verbatim into every profile; don't reformat per-platform.

```
Business name:     SpringKleaners
Legal name:        SpringKleaners (Reg. 2021/363748/07)
Address:           1 Stepney Rd, Parklands, 7441, Western Cape, South Africa
Phone:             +27 81 527 4711
Email:              bookings@springkleaners.co.za
Website:           https://springkleaners.co.za
Hours:             Every day, 8:00 AM – 5:00 PM
Logo/profile photo: https://springkleaners.co.za/images/logo.webp
```

**Categories** (use the closest match on each platform):
Primary: House Cleaning Service. Secondary (where multiple categories are allowed): Commercial Cleaning Service, Carpet Cleaning Service, Window Cleaning Service.

**Service area:** Cape Town — Northern Suburbs, Blaauwberg & Atlantic Seaboard (19 suburbs: Milnerton, Sunningdale, Blouberg, Parklands, Century City, Table View, Big Bay, Bloubergstrand, Sea Point, Green Point, West Beach, Monte Vista, Edgemead, Bothasig, Montague Gardens, Paarden Eiland, Richwood, Burgundy Estate, Flamingo Vlei).

### Short description (~150 chars — Facebook short bio, GBP short description)
```
Fully insured, background-checked cleaning company in Cape Town's Northern Suburbs & Atlantic Seaboard. Free inspections, upfront pricing, book online.
```

### Long description (~700 chars — Hellopeter bio, Facebook About, Bing Places)
```
SpringKleaners (registration 2021/363748/07) is a fully insured, background-checked residential and commercial cleaning company based in Parklands, Cape Town. We provide Deep Cleaning, End-of-Tenancy, Post-Construction, Office & Commercial, Recurring House Cleaning, Carpet, Upholstery, Window, Airbnb Turnover, Spring Cleaning, Oven, Fridge & Appliance, Blind & Curtain, and Move-In Cleaning — across 19 suburbs spanning Cape Town's Northern Suburbs, Blaauwberg and the Atlantic Seaboard. Every service is priced upfront on our website, confirmed with a free, no-obligation on-site inspection before we start, and backed by a 24-hour satisfaction guarantee. Book online any day of the week at springkleaners.co.za.
```

---

## Directory claims

### 1. Hellopeter
South Africa's main review platform — carries real weight locally, and letting agents/property managers often check it before referring a cleaner.

1. Go to hellopeter.com → **Claim your business** (or **List your business** if it doesn't already exist under the name).
2. Search "SpringKleaners" first — if a placeholder listing already exists (sometimes auto-created from a review mention), claim that one rather than creating a duplicate.
3. Paste the NAP block above exactly. Category: Cleaning Services.
4. Verification is typically via the business email (`bookings@springkleaners.co.za`) or phone OTP.
5. Once claimed, link it: add the Hellopeter profile URL to `sameAs` in the `LocalBusiness` schema (`resources/views/layouts/app.blade.php`) and to `config/reviews.php`'s source options if you want to start pulling real Hellopeter reviews in too — tell me the profile URL once it's live and I'll wire that up.

### 2. Facebook Business Page
Flagged as not yet claimed in `docs/seo.md`. Also gives you a real `sameAs` entity and a place to cross-post the GBP posts already queued in `docs/gbp_posts.md`.

1. facebook.com/pages/create → **Business or Brand**.
2. Name: `SpringKleaners`. Category: Cleaning Service.
3. Paste NAP + long description above. Profile photo: the logo; cover photo: a real job photo (not stock — same rule as everywhere else on the site).
4. Add the website link (`https://springkleaners.co.za`) and phone as click-to-call.
5. Once live, send me the page URL — I'll add it to `sameAs` in the schema alongside the Google review link that's already there.

### 3. Bing Places
Feeds Bing's local index directly — which matters more than it used to, since Bing is what ChatGPT's search/browsing mode leans on for real-time web results.

1. bingplaces.com → sign in with a Microsoft account → **Add a new business**.
2. If it offers to import from Google Business Profile, use that — it'll pull the already-correct GBP data instead of manual re-entry.
3. Confirm NAP matches the block above exactly, and set the same categories.
4. Verification is usually instant if importing from an already-verified GBP; otherwise it's a phone/postcard verification, same as GBP.

---

## Letting-agent / property-manager partnerships

Real backlink-and-referral channel per `docs/seo.md` — End-of-Tenancy is already the strongest-performing service category, and letting agents are the exact people who refer that work. Draft outreach message, adjust tone/names before sending:

```
Subject: Cleaning partner for your move-out inspections?

Hi [Name],

I run SpringKleaners — we do end-of-tenancy cleaning across [Area], and a big part of
what we focus on is getting properties to the standard your inspections actually check
against (not just "clean," but move-out ready: appliances, cupboard interiors, walls,
windows and tracks).

We're fully insured, background-checked, and every job gets a printable condition
checklist for the handover file. Happy to do a free walkthrough on a current vacancy
so you can see the standard directly, and we're open to a standing referral arrangement
if it's useful to have someone reliable to point tenants/landlords to.

Worth a quick call this week?

[Name]
SpringKleaners
+27 81 527 4711 · bookings@springkleaners.co.za
```

**Where to send it:** local letting agencies in the service area — search "letting agents [suburb]" for Milnerton, Table View, Century City, Blouberg, Parklands specifically (highest end-of-tenancy volume per the area pages). A link from even 3-4 agency websites ("recommended cleaners") is a genuinely strong local backlink signal, not just a referral source.

## Digital PR — local press pitch

Cape Town hyperlocal press angle, per `docs/seo.md`'s 60–90 day list. A real, non-promotional hook works better than a straight company announcement:

```
Subject: Local data — what end-of-tenancy cleaning actually costs in Cape Town right now

Hi [Journalist name],

I run a cleaning company in the Northern Suburbs and we publish our real pricing rather
than "request a quote" — so we've got actual booking data on what deep cleans,
end-of-tenancy cleans etc. cost across different Cape Town suburbs right now, plus which
areas see the most last-minute move-out bookings.

If you're ever covering rental/moving costs, deposit disputes, or the coastal-suburb
"salt and sand" maintenance angle (windows/carpets near Blouberg and Bloubergstrand need
cleaning noticeably more often than inland suburbs), happy to share the real numbers —
no pitch, just data if it's useful.

[Name]
SpringKleaners
```

**Where to send it:** CapeTownEtc, People's Post (community edition for the relevant suburb), Northern News/Tygerburger, or a property-focused freelance journalist covering the Cape Town rental market. The "who pays for end-of-lease cleaning" blog post is a ready-made supporting link if a journalist wants to cite a source.

## Status

| Channel | Status | Date |
|---|---|---|
| Hellopeter | Not claimed | — |
| Facebook Business Page | Not claimed | — |
| Bing Places | Not claimed | — |
| Letting-agent outreach | Not started | — |
| Digital PR pitch | Not started | — |

Update this table as each one goes live, and send me the profile URLs so they get added to the site's `sameAs` schema — that's what turns a citation into an actual ranking/trust signal instead of just a listing that exists.
