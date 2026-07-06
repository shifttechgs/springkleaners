# SpringKleaners — Deferred Tasks

## GA4 analytics + booking-funnel event tracking
**Blocked on:** custom domain not set up yet (still on `springkleaners.onrender.com`). Revisit once the real domain is live.
- Create a GA4 property at analytics.google.com (user does this — account creation isn't something Claude can do) and get the Measurement ID (`G-XXXXXXXXXX`).
- Wire the GA4 snippet into `resources/views/layouts/app.blade.php`.
- Add event tracking on the `/book` wizard steps (Details → Schedule → Review) and on "Get My Instant Quote" / WhatsApp CTAs, so drop-off in the booking funnel is measurable.

## Render → Hosting Pods deployment migration
**In progress (2026-07-06).** Discovered along the way: Render's auto-deploy had silently stopped working weeks ago — production was serving a build from before the admin panel/booking/blog system even existed, and nobody noticed. Moving to Hosting Pods (cPanel) specifically to get a deploy pipeline that's actually verifiable.

Code-side automation is built: `.github/workflows/deploy.yml` (builds + uploads over FTPS on every push to `main`) and a token-guarded `POST /deploy/migrate` endpoint (`DeployController`) for running migrations/cache-clears without SSH. Full setup instructions in [`deployment.md`](./deployment.md).

**Still needed — requires the business owner's Hosting Pods/cPanel access, not code:**
- Confirm whether the plan includes SSH (changes whether the token-guarded endpoint is needed long-term, or can be swapped for direct SSH commands — see `deployment.md`'s last section)
- Set the domain's document root to the app's `public/` folder
- Set PHP to 8.3 via MultiPHP Manager
- Create the MySQL database + user
- Create the server's `.env` (never uploaded by CI — has to be created once by hand) and generate `APP_KEY` + `DEPLOY_TOKEN`
- Add the 6 GitHub Secrets listed in `deployment.md`
- Point DNS at the new host once the first deploy is verified working (see [`seo.md`](./seo.md) for why this also unblocks robots.txt/GBP/sitemap)

## Admin panel / CRM / quotes — DONE (2026-07-03)
~~Custom admin/ERP panel (clients + quotes)~~ — built. See project memory for full details: `/admin` login-gated panel, `clients` table for CRM history, `bookings` extended into full quote records with a status workflow (pending → quoted → accepted/declined → completed), and a public token-gated `/quote/{token}` page for client accept/decline, all wired to the existing WhatsApp-first notification pattern (no email infra added).
