# Design System — SpringKleaners

## Typography

- **Font**: Plus Jakarta Sans, self-hosted variable latin woff2 (`/fonts`, preloaded, declared in `app.css`). Geist 600 is self-hosted for the hero headline only.
- Body copy sizes cluster around `13px`–`16px`; headings scale from `text-3xl` (section headers) up to `text-7xl`/`80px` (homepage hero) using `clamp()` on a few key headings for responsive sizing without breakpoint-specific overrides.
- Tracking is consistently tightened on headings (`tracking-tight`) and loosened on small uppercase labels (`tracking-[0.18em]` on eyebrow/kicker text).

## Color tokens

Two separate palettes, defined as Tailwind v4 `@theme` tokens in two separate stylesheets (`resources/css/app.css` and `resources/css/admin.css` — see [`instructions.md`](./instructions.md#build-process-tailwind-v4--vite) for why they're split).

**Public site** (`app.css`):

| Token | Hex | Use |
|---|---|---|
| `navy` | `#081d3a` | Primary brand color — headings, dark sections, primary buttons' text-on-gold |
| `navy-deep` | `#040f1f` | Darkest sections (marquee background, deepest gradients) |
| `gold` | `#f6e304` | Accent — CTAs, badges, highlight borders, active states |
| `muted` | `#647082` | Secondary/body text on light backgrounds |
| `light` | `#f8f9fc` | Light section backgrounds (alternates with white) |

**Admin panel** (`admin.css`) — shares navy/navy-deep/gold/muted, plus:

| Token | Hex | Use |
|---|---|---|
| `ink` | `#0f2038` | Admin body text (near-navy, distinct token so it can diverge later) |
| `line` | `#e8eaf0` | Borders, dividers |
| `label` | `#8a94a6` | Uppercase micro-labels, table headers |
| `canvas` | `#f6f7fa` | Admin page background |

Both stylesheets also define a handful of composed component classes that aren't simple color utilities: `.btn-gold`, `.btn-outline` (public only), `.card`, `.btn-primary`, `.nav-link` (admin only) — check the existing definition before adding a near-duplicate class.

## Layout conventions

- `.section-wrap` — the shared max-width (`80rem`) + responsive horizontal padding container used by every full-width section. Always wrap new section content in this rather than hand-rolling padding.
- `.section-py` — the shared vertical section padding (`5rem`, `7rem` on `lg:`). Use `!py-16` override (Tailwind important-modifier) for sections that intentionally want tighter padding (e.g. "Related reading" / "Other services" cross-link sections).
- Alternating light/white/navy section backgrounds is the standing rhythm down the homepage and every service/area page — new sections should fit into that alternation rather than introducing a new background color.

## Motion

- **WOW.js** (`wow` class + `data-wow-duration`/`data-wow-delay` attributes) drives scroll-reveal fades sitewide — this is the default entrance animation for any new section content. Class starts `visibility: hidden` until WOW triggers it.
- **Homepage hero** is pure CSS (no GSAP, removed 2026-10-18): a squeegee-wipe `.hero-film` panel slides off the photo while the headline lines rise (`.hero-line`). Transform/opacity only, disabled under `prefers-reduced-motion`. Hero headline uses self-hosted **Geist 600** via `.font-display` (also on the form title); everything else stays Plus Jakarta Sans.
- **Odometer.js** drives the animated number counters (`data-odometer` attribute + IntersectionObserver trigger) — used in `why-us.blade.php`'s stat bento grid and the `about.blade.php` stats row.
- `prefers-reduced-motion` is respected in the hero only; WOW.js scroll reveals don't honour it yet.

## Component patterns worth reusing

- **Buttons are pills, always.** Use `<x-arrow-button>` (`resources/views/components/arrow-button.blade.php`, styles `.btn-arrow` in `app.css`) for every call to action: `variant="gold"` (default), `"navy"` on gold surfaces, `"outline"` on dark surfaces; `size="sm"` in the header, `block` for full width. Hover floods the pill from the arrow chip and swaps the arrow. Functional buttons (wizard steps, form submits, toggles, chips) use `rounded-full` with the same colours. Cards (service/area links, wizard option cards) are not buttons and keep their radius. Legacy `.btn-gold`/`.btn-outline` classes are pills too but prefer the component. The CTA wording is "Get My Instant Quote" everywhere.

- **Bento stat grid** (`why-us.blade.php`) — asymmetric CSS grid of stat/image/copy tiles on a navy background, gold accent tiles interspersed. A strong pattern for any future "why trust us" style section.
- **Accordion FAQ** (`components/faq.blade.php`, reused per-service and per-area) — Alpine `x-data="{ active: n }"` single-open-at-a-time pattern, numbered with `str_pad`.
- **Breadcrumb + navy hero** — every interior page (service, area, blog post, about, legal) opens with a navy `hero-pattern` section containing a `/`-separated breadcrumb trail in `white/40` text, then the page H1. Keep new interior pages consistent with this.
- **Card-with-CTA-pair** (service pages' "other services" grid, area pages' "other areas" grid) — white card, border-on-hover-to-gold, chevron icon that inverts to navy-filled on hover.

## What NOT to introduce

- No jQuery, Swiper, or appear.js — removed as dead/redundant (see [`instructions.md`](./instructions.md)). Use Alpine or plain CSS for anything carousel- or scroll-trigger-shaped.
- No separate design tokens per new page — extend the two existing `@theme` blocks rather than hardcoding a new one-off hex value with Tailwind's `[#hex]` arbitrary-value syntax, unless it's genuinely a one-time decorative color (several existing gradients/overlays already do this deliberately, e.g. hero overlay gradients).

