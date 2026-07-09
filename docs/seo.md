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
- (2026-07-08) Sitemap `<lastmod>` on every URL, not just blog; visible street address added to footer NAP; homepage H1→H3 heading skip fixed; area page title tags aligned with the service-page convention
- (2026-07-08) Standalone `Organization`+`WebSite` schema added to homepage; `LocalBusiness` enriched with `@id`, `alternateName`, `legalName`, `logo`, `postalCode`, `geo`, `sameAs`; service/area page JSON-LD refactored off hand-escaped strings to `json_encode()`
- (2026-07-08) Office & Commercial Cleaning shipped as the first new service page — `bookable` flag added to the `services` table so m²/room/seat/pane-priced services can ship as quote-request pages without forcing them through the bedroom/bathroom booking wizard
- (2026-07-08) Recurring/Weekly House Cleaning, Carpet Cleaning, Upholstery Cleaning, Window Cleaning, and Airbnb & Short-Let Turnover Cleaning shipped — all 6 newly-priced services from the audit are now live (5 of 6 wizard/quote patterns proven twice over). Google tag (gtag.js) added sitewide via `layouts/app.blade.php`.
- **Flag for the business owner**: Airbnb Turnover copy is honestly scoped to weekend changeovers only, since the booking wizard still hard-rejects non-Sat/Sun dates (`BookingController::reserve()`). This is the least evidence-backed of the 6 new prices per the original brief — revisit after a few real bookings, and reconsider if weekday turnover demand turns out to matter.
- (2026-07-08) All 10 custom-quote-only service pages shipped (Spring Cleaning, Oven Deep Cleaning, Fridge/Appliance Cleaning, Mattress Cleaning, Blind & Curtain Cleaning, Retail & Shop Cleaning, Medical & Clinic Cleaning, Restaurant & Commercial Kitchen Cleaning, School & Educational Facility Cleaning, Pressure Washing). Added a third `pricing_mode` ('custom') to the service-page template alongside 'wizard' and 'quote' — no fixed price is claimed anywhere (no `Offer` schema block at all for these), just a "Get a Custom Quote" CTA. That's 18 total services now live.
- **Deliberately scoped out of these 10** to avoid overclaiming: Medical & Clinic Cleaning excludes clinical/biohazard waste handling; Restaurant & Commercial Kitchen Cleaning excludes certified extraction-hood/canopy fire-safety degreasing; Pressure Washing excludes roof cleaning. All three explicitly say so and point to a specialist instead — don't remove these carve-outs without the business owner confirming they actually hold the relevant certification/equipment.
- (2026-07-08) Move-In Cleaning shipped — inherited End-of-Tenancy's exact pricing model (R1,200 base, 2bed/1bath included, R120/R100/R90 scaling) per the business owner's decision, since it's explicitly "split out of End-of-Tenancy." Copy is deliberately differentiated: End-of-Tenancy is framed for the person leaving (deposit/handover standard), Move-In is framed for the person arriving (fresh-start sanitising, no checklist). 19 services now live.
- **Suggestion, not yet actioned**: the footer's "Services" column is now 9 links long (deliberately not expanded further to avoid clutter) while `services/show.blade.php`'s "Other services" cross-sell section already lists all 19 dynamically. Worth considering a dedicated `/services` index/hub page at some point, with the footer trimmed to a "View All Services" link instead of an ever-growing list.
- (2026-07-08) All 11 new area pages shipped (Sea Point, Green Point, West Beach, Monte Vista, Edgemead, Bothasig, Montague Gardens, Paarden Eiland, Richwood, Burgundy Estate, Flamingo Vlei) — 19 area pages now live, matching the sitewide `areaServed` schema and footer claims exactly. Homepage's `areas-we-serve` component and footer's area list updated to link all 19 (8 of the 11 were previously shown as unlinked plain text on the homepage; 3 — Richwood, Burgundy Estate, Flamingo Vlei — weren't shown on the homepage at all).
- **Bug found and fixed while building these**: `areas/show.blade.php`'s "Services in this area" grid loops all services from `Services::list()` unfiltered — once quote-mode and custom-quote services existed (base_price=0, unit_label=null), every area page would have rendered a broken "From R0 /" price line and a "Book in [Area]" button that silently fell back to the wrong service in the wizard. Fixed by reading each service's `pricing_mode` from `config/service_pages.php` and branching the price display and CTA accordingly (wizard/quote/custom). Regression-tested across all 19 area pages.
- **Geographic note for the business owner**: Sea Point and Green Point are Atlantic Seaboard suburbs, a meaningfully longer drive from the Parklands base than the other 9 (all clustered in the Milnerton/Blaauwberg/Century City corridor). This was already implied by the sitewide `areaServed` claim before this session: no new decision was made here, just flagging it since it's the one geographic outlier worth double-checking against real operational capacity.
- (2026-07-09) FAQ hub shipped at `/faq`, linked from the navbar (desktop + mobile). Initially compiled from 42 real Q&A already true across the site, since the original audit report wasn't available in-session. **Reconciled same day** once the business owner located the actual source file (`SpringKleaners_SEO_GEO_Audit_2026.md`) — the hub now carries the real 50-question set from Phase 10 of that report, verbatim question wording, organized into its original clusters (Pricing & Process 10, Trust/Insurance/Safety 8, Per-Service 26 — sub-grouped by Deep Cleaning/End-of-Tenancy/Post-Construction/Office & Commercial/Carpet-Upholstery-Window — Areas 3, Comparisons 3). Every answer was checked against real site facts before publishing; a few needed deliberate hedging rather than the audit's implied phrasing (see below). Single `FAQPage` schema block covers all 50.
- **Answers deliberately softened from how the audit phrased the question**: "Will this guarantee I get my deposit back?" — no cleaning company can guarantee a landlord's deposit decision, so the answer clarifies that and reframes around the condition checklist. "Who is responsible for paying for end-of-lease cleaning in South Africa?" — general lease-agreement guidance, explicitly flagged as not legal advice. "Do you offer daily, weekly, or monthly office cleaning?" — the audit's own suggested GBP copy implies daily office cleaning is available; the real answer is honest about the weekend-only operating model instead, consistent with how Airbnb Turnover and Office & Commercial were already scoped earlier in this session.
- **Docs correction**: `docs/business.md` was stale (dated 2026-07-06, pre-dated this whole session) — still said "only 3 services," "8 area pages," and carried the now-fixed email fallback discrepancy as an open flag. Updated to reflect the real 20-service / 19-area state and moved the satisfaction-guarantee/damage-liability facts into this FAQ hub's Trust cluster.
- (2026-07-09) FAQ hub redesigned as tabs (was 5 long scroll sections) per the business owner's request — Alpine-driven tab switcher with an icon + real photo per cluster, deep-linking preserved from service/area page cross-links.
- (2026-07-09) Phase E (Conversion) shipped: `<x-trust-badges>` component (Registered Company, Fully Insured, Background-Checked, 24-Hour Guarantee — all real, already-claimed facts) added near the primary CTA on the homepage hero and every service/area page hero. Testimonials refactored off two hardcoded 3-review arrays onto `config/reviews.php`, so adding more real reviews is a config edit, not a template edit — the two-column auto-scroll now sizes its repeat count to whatever pool exists instead of assuming exactly 6. Before/after gallery component built (`<x-before-after-gallery>` + `config/before_after.php`) and wired onto the homepage and every service page — **renders nothing until real job photos are supplied**, deliberately, since faking a before/after pair would be a dishonest marketing claim, not just an incomplete placeholder. Deposit-back checklist lead magnet shipped end-to-end: real checklist content (written from the EOT service's actual included-list plus general moving-out practice), PDF generated via the existing `barryvdh/laravel-dompdf` dependency, capture form on the End-of-Tenancy page only, stores the lead in a new `lead_magnet_downloads` table, emails a copy, and downloads the PDF immediately on submit.
- **Still needs the business owner**: real before/after job photos to populate `config/before_after.php`, and more real reviews to add to `config/reviews.php` (post review-velocity push) — infrastructure for both is ready, content is not fabricated.
- (2026-07-09) Phase F shipped: the 10-post priority-ordered starter batch from the original brief, all with a direct declarative opening sentence, internal links to relevant service/area pages, and `FAQPage` schema on the 7 posts that have a real Q&A section. New `<x-blog.faq>` component (visible Q&A, not hidden in an accordion — more citable for AI Overviews per the audit's own GEO advice). "Who Pays for End-of-Lease Cleaning" and "Meet the Team" were written with extra care: the former is explicitly flagged as general guidance, not legal advice; the latter only uses already-real, already-quoted facts about named staff (Charity, Prosper) rather than inventing biography for real identifiable people.
- (2026-07-09) Phase H (QA) — what's automatable from inside this session was run as real test coverage, not a manual checklist: `tests/Feature/SitewideQaTest.php` hits every one of the 67 public pages (home, about, faq, book, blog index, 20 services, 19 areas, 16 blog posts) and asserts a self-referencing canonical, a unique non-empty `<title>`, a non-empty meta description, `index,follow` robots, descriptive alt text on every image, and well-formed JSON-LD with `@type`/`@context` on every block — **2,387 assertions, all passing**. Sitemap coverage confirmed the same way (every URL present, every URL has both `<lastmod>` and `<priority>`). Full suite: 87 tests, 2,798 assertions.
- **Could not be done from this session — needs the business owner or a live URL**: Google's Rich Results Test and Lighthouse/PageSpeed both require a publicly reachable URL (or live browser access), and the site isn't deployed to `springkleaners.co.za` yet. Once DNS cuts over, run both against the homepage + 3 highest-priority pages and share the results — the JSON-LD is already validated as well-formed and schema-complete here, so Rich Results Test should be confirming presentation (rich snippet eligibility), not catching structural errors.

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

- **Superseded 2026-07-08**: the business is now deliberately expanding past the original 3 services — 6 new services have real, benchmarked SA pricing (Office & Commercial, Recurring/Weekly House Cleaning, Carpet, Upholstery, Window, Airbnb Turnover), plus a further batch planned as custom-quote-only pages. Services priced by m²/room/seat/pane (starting with Office & Commercial, shipped 2026-07-08) are marked `bookable = false` on the `services` table and get a "Request a Quote" page instead of the instant-quote wizard, since the wizard's pricing engine only supports bedroom/bathroom-based math. Don't revert to the "only 3 services" assumption — check the `services` table and `config/service_pages.php` for the current real list instead.
- Weekend-only, 2-bookings/day capacity — don't build content promising same-day/emergency service. Note this applies to the wizard-bookable residential services; quote-only B2B services (like office contracts) are scheduled by direct conversation, not the automated slot system, so this constraint doesn't block them the same way.
- 8 area pages, not a full service×suburb matrix — this was a deliberate anti-thin-content decision at the time. **Also superseded 2026-07-08**: 11 more suburbs are already claimed in the sitewide `areaServed` schema and footer with no page to back them up — building those pages closes a real claim/page mismatch rather than expanding scope speculatively.
