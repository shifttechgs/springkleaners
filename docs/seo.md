# SEO & GEO Status — SpringKleaners

Living summary of where SEO/Local SEO/GEO (Generative Engine Optimisation) stands. The full point-in-time audit with all 15 strategy sections lives in [`docs/reports/seo-geo-strategy-2026-07.md`](./reports/seo-geo-strategy-2026-07.md) — this file tracks current state and should be updated as work progresses; the report file should not (it's a dated snapshot).

## Scoreboard (as of 2026-07-06)

| Category | Score | Change |
|---|---|---|
| Technical SEO | 78 / 100 | ↑37 |
| Local SEO | 58 / 100 | ↑6 |
| GEO / AI visibility | 45 / 100 | ↑23 |
| EEAT | 60 / 100 | ↑20 |
| Content & topical authority | 54 / 100 | ↑8 |
| Conversion (CRO) | 72 / 100 | ↑4 |
| Backlinks / off-site authority | 15 / 100 | unchanged |
| **Composite** | **59 / 100** | ↑19 |

Read this as: everything fixable from inside the codebase has moved meaningfully. Backlinks/off-site authority is flat because nothing in that category can be fixed by editing code — it needs domain cutover and account-verification actions from the business owner.

## Done

- Dynamic `robots.txt` (self-corrects with `APP_URL`, no longer hardcoded to an unresolvable domain)
- `LocalBusiness`/`HousekeepingService` schema sitewide, `FAQPage` schema (homepage + 3 service + 8 area pages), `BreadcrumbList` (service/area/blog/about pages)
- Open Graph + Twitter Card meta tags sitewide
- `noindex` on `/admin/*`, `/quote/{token}`, `/invoice/{token}`, and the orphaned `/get-my-quote` page (also removed from the sitemap)
- Real Privacy Policy and Terms of Service pages (POPIA-aware), dead social links removed
- Mismatched stock testimonial photo replaced
- Review-request WhatsApp/Email automation now fires on `BookingStatus::Completed`, independent of payment status (was gated behind Paid, and buried in the UI)
- `review_url` setting fixed — was a messy, session-bound Google Search URL; now a stable `g.page/r/...` link (**note: only fixed in the local dev DB — re-enter in Admin → Business Details on production**)
- Tailwind migrated off the CDN to a real Vite build
- Dead/redundant JS removed sitewide (Swiper, jQuery, appear.js); GSAP/ScrollTrigger/SplitText scoped to the homepage only
- `/about` page built and linked from the navbar
- All 8 area pages deepened with genuine local specificity
- Hardcoded review-count copy ("rated by 10 Cape Town clients", "Based on 10 Google reviews") removed from visible text

## Still open

**Needs the business owner, not code:**
- DNS cutover to `springkleaners.co.za` + `APP_URL` update
- GBP category/service-area/photos review
- Claiming Hellopeter, a real Facebook Business Page, Bing Places
- Re-entering the fixed `review_url` on the production database

**Content/backlink work not yet started (60–90 day items):**
- Cape Town Cleaning Price Index (original data pillar — the `bookings` table already has real pricing data to aggregate)
- Public cleaning cost calculator (reuse the booking wizard's existing pricing logic)
- Letting-agent/property-manager backlink-and-referral partnerships
- Buyer's-guide/comparison content ("how to choose a cleaning company")
- Digital PR pitch to hyperlocal Cape Town press
- `llms.txt`, Wikidata entity groundwork

**CRO refinements not yet started:**
- Embedded real Google Reviews widget (currently hardcoded testimonials, even though the content itself is genuine)
- Trust-badge row for insurance/vetting claims
- Honest scarcity messaging on the homepage sourced from the real availability endpoint

## Constraints to respect

- Only 3 real services exist (Deep Cleaning, End-of-Tenancy, Post-Construction) — don't build pages/schema/keywords for Residential/Commercial/Office/Window/Carpet cleaning unless the business actually adds that service line.
- Weekend-only, 2-bookings/day capacity — don't build content promising same-day/emergency service.
- 8 area pages, not a full service×suburb matrix — this was a deliberate anti-thin-content decision, keep it that way unless there's real per-suburb search-demand evidence.
