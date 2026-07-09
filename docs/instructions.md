# Working on SpringKleaners — Instructions

Practical conventions for anyone (human or AI) picking up this codebase. Read this before adding a feature — most of the patterns here exist because an earlier approach was tried and deliberately replaced.

## Stack

Laravel 12, Blade templates, Tailwind CSS v4 (real Vite build, **not** the CDN — see [Build process](#build-process)), Alpine.js (CDN, deferred), GSAP + ScrollTrigger + SplitText (homepage hero only), WOW.js + animate.css (scroll reveals, sitewide), Odometer.js (animated counters, sitewide). MySQL both locally (WAMP) and in production (Hosting Pods/cPanel).

## The "static config, not a database" pattern

Blog posts, services, and area/suburb pages are **not** Eloquent models. This is deliberate — it keeps the site fast to extend without migrations:

- **Services**: `config/cleaning_services.php` (pricing/booking logic) + `config/service_pages.php` (marketing copy, FAQs). To add a 4th service, add one entry to each — no new routes or controllers.
- **Areas**: `config/locations.php`. To add a 9th suburb, add one entry — keep the `name` casing identical to the suburb list in `hero.blade.php` (the booking page's location auto-validation matches on exact string).
- **Blog**: `config/blog.php` for metadata, `resources/views/blog/posts/{slug}.blade.php` for body content.

The **only** real database tables are `bookings`, `clients`, `users`, and `settings` — because capacity checking, CRM history, auth, and business settings genuinely need persisted state. Don't introduce a database table for content that config files already handle well.

## WhatsApp-first, no payment gateway

Every client-facing CTA ends in a `wa.me/...?text=...` link, not a payment processor or email-only flow. This is intentional — keep reusing this pattern for new CTAs rather than introducing new channels unless explicitly asked. Transactional emails (quote/invoice/thank-you/confirmation) exist in `app/Mail/*` and are sent from the admin panel alongside the WhatsApp option, never instead of it.

**Admin: invoicing/payment actions live under `/admin/invoices/{booking}` (added 2026-07-07), not the booking page.** `Admin\InvoiceController` owns `show`/`markPaid`/`markDepositPaid`/`sendEmail`, and `Admin\InvoicePdfController` is routed under the same `admin.invoices.*` prefix. `admin/bookings/show.blade.php` only keeps a "View Invoice →" link once `$booking->invoice_number` is set — add new invoice/payment features to `admin/invoices/show.blade.php` + `InvoiceController`, not back onto the booking page. One deliberate exception: the deposit **amount** is still set on the booking page (it's a quoting term, part of `sendQuote()`), and "Mark Deposit as Received" is reachable from both the booking page (needed pre-invoice, since deposits are usually paid to secure a booking before the job is ever completed/invoiced) and the invoice page — both post to the same `InvoiceController::markDepositPaid`. No new `Invoice` model/table was introduced; invoicing data still lives as columns on `bookings`, per the "only 4 real tables" convention above.

**One deliberate exception (added 2026-07-07): the main booking wizard (`/book`, `booking/show.blade.php`) no longer redirects to WhatsApp.** Submitting it now requires a client email (validated in `BookingController::reserve()`), saves to the DB, emails the business (`NewBookingAlertMail`, unchanged) and automatically emails the client an acknowledgement (`App\Mail\BookingRequestReceivedMail` — deliberately distinct from `BookingConfirmationMail`, which still means "an admin has actually reviewed/priced this," not "we received your request"), then shows the existing on-page success screen. `quote.blade.php`, the homepage mini CTA (`components/cta.blade.php`), and the quote-acceptance flow (`client-quote/show.blade.php`) are unchanged and still WhatsApp-only — don't assume this exception extends to them without asking.

## Known Blade/Alpine gotchas

- **Inline JSON-LD**: Laravel's Blade compiler treats a bare `@context`/`@type` at the start of a line as its own `@context`/`@type`-adjacent directive collision. Either escape as `@@context`/`@@type`, or — the pattern used for every schema block added after the initial rollout — build the array in a `@php` block and `json_encode()` the whole thing, which sidesteps the problem entirely. Prefer the `json_encode()` approach for new schema.
- **Alpine `x-data` on a double-quoted attribute**: never put a literal `"` inside inline JS written into a double-quoted `x-data="..."` attribute (e.g. `querySelector('meta[name="csrf-token"]')`) — it silently truncates the attribute. Use `meta[name=csrf-token]` (unquoted) or single-quote the outer attribute instead.
- **`x-for` `:key`**: must be unique across the array. Keying by a value that can repeat (e.g. weekday initials — `M`,`T`,`W`,`T`,`F`,`S`,`S`) silently collapses duplicates. Key by index when values can repeat.

## Build process (Tailwind v4 / Vite)

As of July 2026 the site no longer loads Tailwind from a CDN — it's a real compiled build. Two separate entry stylesheets exist because the public site and admin panel each define their own `.btn-gold` (different padding/sizing) and never share a page:

- `resources/css/app.css` → public site (`layouts/app.blade.php`)
- `resources/css/admin.css` → admin panel + all 4 admin auth pages

**Any time you change a CSS class or add a new one, run `npm run build`** before testing — there is no CDN fallback anymore to mask a stale build. The GitHub Actions deploy workflow already runs `npm ci && npm run build` before pushing to production over FTPS (see [Deployment](#deployment)), so production doesn't need manual intervention.

Do not reintroduce jQuery, Swiper, or appear.js — all three were removed as dead/redundant (Swiper's target element no longer exists since testimonials moved to a CSS-only scroll animation; jQuery+appear.js were fully redundant with an IntersectionObserver fallback that already existed in the code). If a future feature needs a carousel, build it with Alpine or plain CSS, not a jQuery-era library.

GSAP/ScrollTrigger/SplitText load **only** on the homepage (`@push('scripts')` in `welcome.blade.php`), because the hero entrance animation is the only thing that uses them, and its target element IDs only exist there. Don't move this back into the shared layout.

## Admin panel

`/admin/*` is gated by the `auth` middleware; a further `admin` middleware sub-group gates user/service management (checked via `$user->isAdmin()` on the `role` column — no external permissions package). The booking status workflow is `pending → quoted → accepted/declined → completed`, defined in `App\Enums\BookingStatus`. The review-request WhatsApp/Email flow fires as soon as a booking reaches `Completed`, independent of payment status — see `resources/views/admin/bookings/show.blade.php`.

## Deployment

Hosting Pods (cPanel), migrated off Render — see [`docs/deployment.md`](./deployment.md) for the full pipeline and [`docs/todo.md`](./todo.md) for what's still manual. Render's auto-deploy silently stopped working at some point and nobody noticed for weeks, which is the whole reason this pipeline includes an actual verification step (`docs/deployment.md`'s "Verifying a deploy actually landed" section) — don't assume a push succeeded just because CI didn't show red.

`POST /deploy/migrate` (`DeployController`) is a CSRF-exempt, token-guarded endpoint that runs migrations and clears caches — it exists specifically for hosts with no SSH access, called from the GitHub Actions workflow. Don't remove its CSRF exception in `bootstrap/app.php` (it has to be callable by CI, which can't send a session-based CSRF token) — its actual protection is the constant-time `DEPLOY_TOKEN` comparison in the controller. If SSH access is later confirmed, this endpoint can be left dormant (safe — it 403s without the token) while the workflow switches to running `artisan` directly over SSH instead.

## SEO / GEO

See [`docs/seo.md`](./seo.md) for current status and [`docs/reports/`](./reports/) for the full point-in-time strategy audit. In short: don't add pages for services that aren't real (only Deep Cleaning, End-of-Tenancy, and Post-Construction exist — see `config/cleaning_services.php`), keep the 8-suburb area-page discipline rather than building a full service×suburb thin-content matrix, and any new schema should follow the `json_encode()` pattern above.
