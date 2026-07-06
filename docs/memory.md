# Project Memory — SpringKleaners

A condensed build history and decision log, kept in-repo so the context survives independent of any one tool's private memory. Newest decisions at the bottom of each section carry the most weight if anything here conflicts with the current code — verify against the actual files before trusting a claim as still-true.

## Timeline

**2026-05-02 — Landing page built.** Premium single-page marketing site: navbar → hero → marquee → services → why-us → how-it-works → testimonials → pricing → faq → cta → footer. Hero includes a 30+ suburb autocomplete location filter that originally submitted via a WhatsApp URL directly.

**2026-07-03 — Blog added.** No database — `config/blog.php` for metadata, `resources/views/blog/posts/{slug}.blade.php` for bodies. Added `/sitemap.xml` (dynamic route, auto-includes blog posts), `robots.txt`, canonical tags, per-page `@yield('description')`.

**2026-07-03 — Booking wizard added**, modeled on a competitor (Shalean Cleaning Services, `shalean.co.za/book/deep-cleaning`) but adapted to end in a WhatsApp message rather than card payment (no payment gateway exists). Consolidated to a single `/book` route (was per-service URLs) with service selection as an in-wizard field. Real weekend-only capacity system added (`Booking::DAILY_CAPACITY = 2`, Sat/Sun only) — the one place in the app with a genuine database table, because capacity checking needs real persisted state.

**2026-07-03 — SEO service + location pages added.** User explicitly chose this over building a custom admin/CRM first, since the site had zero indexable pages beyond the homepage. `config/service_pages.php` (3 real services) and `config/locations.php` (8 core suburbs, deliberately not a full service×suburb thin-content matrix).

**2026-07-03 — Admin panel, CRM, invoicing added**, borrowing UI *patterns* (not data models) from an unrelated larger project (`luminii_landingPage`) the user pointed at for reference. Booking status workflow: `pending → quoted → accepted/declined → completed`. Public token-gated `/quote/{token}` and `/invoice/{token}` pages for client accept/decline. DB switched from SQLite to MySQL locally (WAMP) for this work — production (Render) still on SQLite at the time, which resets on every deploy (known, accepted limitation; see [`docs/todo.md`](./todo.md)).

**2026-07-06 — SEO/GEO strategy audit + implementation.** Full 15-part audit conducted directly against the live codebase (see [`docs/reports/`](./reports/)). Confirmed via direct question to the user: only 3 services are real (not the 9 originally considered), `.co.za` domain is purchased but DNS not yet pointed, GBP is already claimed with real reviews. Implemented same day: dynamic `robots.txt`, `LocalBusiness`/`FAQPage`/`BreadcrumbList` schema sitewide, Open Graph/Twitter tags, noindex on admin/token/orphaned pages, real Privacy Policy/Terms pages, dead social links removed, stock testimonial photo replaced with a real one. Discovered along the way that the homepage testimonials are genuinely real (sourced from actual Google/Bark reviews, not fabricated as an earlier assumption held) and that a real street address (1 Stepney Rd, Parklands) was already public via a Maps link on the homepage.

**2026-07-06 — Review-request automation wired up.** A WhatsApp/Email "ask for a review" flow already existed but was buried under the Invoice card and gated behind `payment_status = Paid`. Moved to fire on `BookingStatus::Completed` alone, in its own visible card. Found and fixed a real bug in the process: the stored `review_url` setting was a messy, session-bound Google Search results URL (tracking params included) rather than a stable link — replaced with the real `g.page/r/...` short link. **Note:** that fix only touched the local dev database; production needs the same value re-entered in Admin → Business Details.

**2026-07-06 — Tailwind CDN → real Vite build, JS de-duplication, About page, deepened area pages.** Tailwind v4 + `@tailwindcss/vite` had been installed as devDependencies but never wired in (`@vite` appeared nowhere) — finished the switch rather than rebuilding. Found and removed genuinely dead code while doing this: Swiper (testimonials had already moved to a CSS-only scroll animation, so Swiper's target element didn't exist), and jQuery + appear.js (fully redundant with an IntersectionObserver fallback that already existed but never ran, because jQuery always won the `if` check ahead of it). GSAP/ScrollTrigger/SplitText now scoped to the homepage only. Built `/about` using only real, already-published facts (company registration number, existing homepage stats) — no fabricated founder bio. Deepened all 8 area pages with genuine local specificity.

**2026-07-06 — Copy fix.** Removed the hardcoded "10" from "rated by 10 Cape Town clients" (marquee) and "Based on 10 Google reviews" (testimonials) — a specific review count goes stale fast and the user wanted it gone from visible copy. The `reviewCount: '10'` value inside the invisible `LocalBusiness` schema in `layouts/app.blade.php` was left untouched since that's a data-accuracy question (does it match real GBP data), not a display preference — revisit it if the real count has changed.

## Standing decisions (don't silently reverse these)

- **No database for content** — blog/services/areas stay as static config unless content genuinely outgrows it.
- **No payment gateway** — WhatsApp confirmation is the deliberate flow; don't introduce Stripe/PayPal/etc. without being asked.
- **No Filament, no `spatie/laravel-permission`** — explicitly rejected as scope creep for a 1–2 person business; the hand-built Blade+Alpine admin panel is the standing choice.
- **8 suburbs, not 30** — avoid a full service×suburb page matrix; it was deliberately rejected as thin/duplicate-risk content. Revisit only with real search-demand evidence per suburb.
- **GA4 is deferred** until the custom domain is live — don't set it up prematurely.
- **Admin invite/reset passwords are never stored anywhere but the terminal at generation time** — if one is lost, reseed or reset via `tinker`, don't go looking for it in a file.
