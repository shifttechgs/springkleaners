# SpringKleaners — Deferred Tasks

## GA4 analytics + booking-funnel event tracking
**Unblocked (2026-07-09):** the real domain `springkleaners.co.za` is now live (DNS cut over as part of the Hosting Pods migration below). Ready to pick up.
- Create a GA4 property at analytics.google.com (user does this — account creation isn't something Claude can do) and get the Measurement ID (`G-XXXXXXXXXX`).
- Wire the GA4 snippet into `resources/views/layouts/app.blade.php`.
- Add event tracking on the `/book` wizard steps (Details → Schedule → Review) and on "Get My Instant Quote" / WhatsApp CTAs, so drop-off in the booking funnel is measurable.

## Render → Hosting Pods deployment migration — DONE (2026-07-09)
~~Render's auto-deploy had silently stopped working weeks ago — production was serving a build from before the admin panel/booking/blog system even existed, and nobody noticed.~~ Migrated to Hosting Pods (cPanel): GitHub Actions builds and pushes over FTPS on every push to `main`, DNS now points `springkleaners.co.za` at the new host, and Render is no longer used (`render.yaml`, `Dockerfile`, `docker/entrypoint.sh` removed from the repo). Full pipeline reference in [`deployment.md`](./deployment.md).

## Admin panel / CRM / quotes — DONE (2026-07-03)
~~Custom admin/ERP panel (clients + quotes)~~ — built. See project memory for full details: `/admin` login-gated panel, `clients` table for CRM history, `bookings` extended into full quote records with a status workflow (pending → quoted → accepted/declined → completed), and a public token-gated `/quote/{token}` page for client accept/decline, all wired to the existing WhatsApp-first notification pattern (no email infra added).
