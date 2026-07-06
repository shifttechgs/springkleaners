# Business Reference — SpringKleaners

Facts that inform copy, SEO, and product decisions. Sourced from the codebase (`app/Support/Company.php` defaults, `config/*.php`, live templates) — treat as accurate as of 2026-07-06, re-verify before quoting externally if this file ages.

## Identity

- **Name**: SpringKleaners (one word, per every rendered brand mark — "Spring" + "Kleaners" as two spans forming one visual wordmark)
- **Registration**: 2021/363748/07
- **Address**: 1 Stepney Road, Unit H1, Hampton Place, Parklands, Cape Town (service-area business — no public walk-in premises; this address is already public via a Google Maps link on the homepage testimonials section)
- **Phone / WhatsApp**: +27 81 527 4711
- **Email**: `bookings@springkleaners.co.za` — used consistently across every public-facing page (footer, CTAs, legal pages, meta/schema). **Flagged discrepancy**: `App\Support\Company::email()`'s hardcoded fallback default is `sales@springkleaners.com` (a different address entirely) — this only matters if that fallback is ever actually rendered (e.g. an unconfigured `Setting`), but it's worth reconciling which address is actually correct for admin-generated documents (PDF quotes/invoices) rather than leaving two different emails live.
- **Current domain**: live at `springkleaners.onrender.com`; `springkleaners.co.za` is purchased but DNS is not yet pointed (see [`docs/todo.md`](./todo.md) and [`docs/seo.md`](./seo.md)).

## Services (real — only these three exist)

| Service | Base price (from) | Included | Avg. time | Unit |
|---|---|---|---|---|
| Deep Cleaning | R1,200 | 2 bed / 1 bath | 4 hrs | per visit |
| End-of-Tenancy Cleaning | R1,200 | 2 bed / 1 bath | 5 hrs | per property |
| Post-Construction Cleaning | R1,800 | 2 bed / 1 bath | 6 hrs | per project |

Extra bedrooms/bathrooms/rooms and add-ons (interior windows R200, balcony/patio R150, wall mark removal R150, garage R200) are priced per `config/cleaning_services.php`. **Do not build pages, schema, or marketing copy for Residential/Commercial/Office/Window-only/Carpet-only cleaning as standalone services** — confirmed directly with the business owner (2026-07-06) that these are not currently offered; treat them as a possible future expansion only, gated on the business actually adding that service line.

All pricing shown publicly is an **estimate** — confirmed via a free on-site (or details-based) inspection, approved by the client before work starts. This is a deliberate trust/transparency positioning, not just a legal hedge.

## Service area

**8 core suburbs** with dedicated area pages (`config/locations.php`): Milnerton, Sunningdale, Blouberg, Parklands, Century City, Table View, Big Bay, Bloubergstrand.

**Extended area** covered by the booking form's autocomplete (no dedicated page — by design, to avoid thin/duplicate content) also includes: West Beach, Monte Vista, Edgemead, Bothasig, Richwood, Burgundy Estate, Flamingo Vlei, Sandown, Sunset Beach, Parklands North, Waves Edge, Montague Gardens, Blouberg Rise, Summer Greens, Rugby, Paarden Eiland, Marconi Beam, Dunoon, Joe Slovo Park, Penhill, Kerria, Ravensmead, Sea Point, Green Point.

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

- **Current**: Render (Docker), `render.yaml`. SQLite database with no persistent disk — resets on every deploy (known, accepted limitation for now).
- **Planned**: migration to cPanel hosting (see [`docs/todo.md`](./todo.md)) — DB already switched to MySQL locally in preparation.

Banking details for client-facing invoices/quotes are configured via `App\Support\Company` / Admin → Business Details, and are intentionally **not** reproduced in this file.
