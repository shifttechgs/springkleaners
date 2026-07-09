# Business Reference — SpringKleaners

Facts that inform copy, SEO, and product decisions. Sourced from the codebase (`app/Support/Company.php` defaults, `config/*.php`, live templates) — treat as accurate as of 2026-07-08 (updated after the service/area expansion below), re-verify before quoting externally if this file ages.

## Identity

- **Name**: SpringKleaners (one word, per every rendered brand mark — "Spring" + "Kleaners" as two spans forming one visual wordmark)
- **Registration**: 2021/363748/07
- **Address**: 1 Stepney Road, Unit H1, Hampton Place, Parklands, Cape Town (service-area business — no public walk-in premises; this address is already public via a Google Maps link on the homepage testimonials section)
- **Phone / WhatsApp**: +27 81 527 4711
- **Email**: `bookings@springkleaners.co.za` — used consistently across every public-facing page (footer, CTAs, legal pages, meta/schema). `App\Support\Company::email()`'s fallback default was `sales@springkleaners.com` (a different address) — **fixed 2026-07-08**, fallback now matches the real address.
- **Current domain**: live at `springkleaners.co.za` (DNS cut over from the old `springkleaners.onrender.com`, which is no longer used — see [`docs/deployment.md`](./deployment.md)).

## Services (20 real services, expanded 2026-07-08 — see `docs/seo.md` for the full change log)

**Wizard-bookable** (bedroom/bathroom-based instant quote, `bookable = true` in the `services` table):

| Service | Base price (from) | Included | Avg. time | Unit |
|---|---|---|---|---|
| Deep Cleaning | R1,200 | 2 bed / 1 bath | 4 hrs | per visit |
| End-of-Tenancy Cleaning | R1,200 | 2 bed / 1 bath | 5 hrs | per property |
| Post-Construction Cleaning | R1,800 | 2 bed / 1 bath | 6 hrs | per project |
| Move-In Cleaning | R1,200 | 2 bed / 1 bath | 5 hrs | per property |
| Recurring / Weekly House Cleaning | R750 | 2 bed / 1 bath | 2 hrs (est.) | per visit |
| Airbnb & Short-Let Turnover Cleaning | R900 | 1 bed / 1 bath | 2 hrs (est.) | per turnover |

Extra bedrooms/bathrooms/rooms and add-ons (interior windows R200, balcony/patio R150, wall mark removal R150, garage R200) are priced per `config/cleaning_services.php`.

**Quote-mode** (published benchmark price shown as text, but routed to a WhatsApp/email quote request instead of the wizard — `bookable = false`, since their pricing units don't fit the bedroom/bathroom model): Office & Commercial Cleaning (R10/m², R850 min), Carpet Cleaning (R250/room, R950 min), Upholstery Cleaning (R150/seat fabric, +25% leather, R800 min), Window Cleaning (from R1,400/property, or R40/R75 per pane).

**Custom-quote-only** (no published price at all, `bookable = false`): Spring Cleaning, Oven Deep Cleaning, Fridge & Appliance Cleaning, Blind & Curtain Cleaning.

All pricing shown publicly is an **estimate** — confirmed via a free on-site (or details-based) inspection, approved by the client before work starts. This is a deliberate trust/transparency positioning, not just a legal hedge.

## Service area

**19 suburbs** with dedicated area pages (`config/locations.php`): Milnerton, Sunningdale, Blouberg, Parklands, Century City, Table View, Big Bay, Bloubergstrand, Sea Point, Green Point, West Beach, Monte Vista, Edgemead, Bothasig, Montague Gardens, Paarden Eiland, Richwood, Burgundy Estate, Flamingo Vlei. All 19 are linked from the homepage, footer, and sitewide `areaServed` schema.

Sea Point and Green Point are Atlantic Seaboard suburbs, a meaningfully longer drive from the Parklands base than the other 17 (clustered in the Milnerton/Blaauwberg/Century City corridor) — worth a gut-check against real operational capacity.

Still no dedicated page for the rest of the booking form's autocomplete list (Sandown, Sunset Beach, Parklands North, Waves Edge, Blouberg Rise, Summer Greens, Rugby, Marconi Beam, Dunoon, Joe Slovo Park, Penhill, Kerria, Ravensmead) — by design, to avoid thin/duplicate content until there's real demand evidence for those specifically.

## Operating model

- **Weekend-only**: Saturdays and Sundays only, currently. Not open to weekday bookings.
- **Capacity**: 2 bookings per day maximum, shared across all services (not per-service) — `Booking::DAILY_CAPACITY`.
- **Booking flow**: single wizard at `/book`, ends in a WhatsApp message to the business (not a payment gateway — no online payment processing exists).
- **Trust claims already made in copy**: fully insured (public liability), background-checked staff, satisfaction guarantee (return within 24 hours of a clean at no charge if unsatisfied), free inspection before quoting.

## Reviews

- Real Google + Bark reviews, not fabricated — confirmed against actual reviewer names/quotes in `components/testimonials.blade.php` and `components/marquee.blade.php`.
- Rating: 4.9/5 (displayed without a specific review count as of 2026-07-06 — the count was removed from visible copy since a hardcoded number goes stale quickly; see [`docs/memory.md`](./memory.md)).
- GBP (Google Business Profile) is already claimed and has real reviews on it — confirmed directly with the business owner.

## Admin / back office

- `/admin` panel: bookings & quotes, clients CRM, expenses, invoices, user management.
- Booking status workflow: `pending → quoted → accepted/declined → completed`.
- No payment gateway — invoicing and payment status are tracked manually (cash/EFT), not processed online.

## Deployment

- **Current**: Hosting Pods (cPanel), MySQL database, GitHub Actions builds and pushes over FTPS on every push to `main` (see [`docs/deployment.md`](./deployment.md)). Migrated off Render — that platform is no longer used.

Banking details for client-facing invoices/quotes are configured via `App\Support\Company` / Admin → Business Details, and are intentionally **not** reproduced in this file.
