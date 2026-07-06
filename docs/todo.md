# SpringKleaners — Deferred Tasks

## GA4 analytics + booking-funnel event tracking
**Blocked on:** custom domain not set up yet (still on `springkleaners.onrender.com`). Revisit once the real domain is live.
- Create a GA4 property at analytics.google.com (user does this — account creation isn't something Claude can do) and get the Measurement ID (`G-XXXXXXXXXX`).
- Wire the GA4 snippet into `resources/views/layouts/app.blade.php`.
- Add event tracking on the `/book` wizard steps (Details → Schedule → Review) and on "Get My Instant Quote" / WhatsApp CTAs, so drop-off in the booking funnel is measurable.

## Render → cPanel deployment migration
**Not started.** User plans to stop using Render and deploy to cPanel hosting instead. The DB was already switched to MySQL locally in prep for this (cPanel is universally MySQL-based). Still needed when ready to actually cut over: cPanel-side app setup (PHP version, composer, `.htaccess`/document root pointing at `public/`), a production `.env` for the new host, and a real migration path for whatever production data exists on Render at cutover time (if any — Render's SQLite currently resets on every deploy, so there's likely nothing worth migrating, but confirm before switching).

## Admin panel / CRM / quotes — DONE (2026-07-03)
~~Custom admin/ERP panel (clients + quotes)~~ — built. See project memory for full details: `/admin` login-gated panel, `clients` table for CRM history, `bookings` extended into full quote records with a status workflow (pending → quoted → accepted/declined → completed), and a public token-gated `/quote/{token}` page for client accept/decline, all wired to the existing WhatsApp-first notification pattern (no email infra added).
