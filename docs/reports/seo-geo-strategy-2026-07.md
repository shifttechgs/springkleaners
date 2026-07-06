# SpringKleaners — SEO & GEO Growth Strategy

**Prepared:** July 2026 · **Last updated:** 2026-07-06 · **Business:** SpringKleaners, Cape Town Northern Suburbs

A 15-part technical SEO, Local SEO, and Generative Engine Optimisation (GEO) strategy, built from a direct line-by-line audit of the live Laravel codebase — not a generic checklist. This is a dated snapshot; for current status see [`docs/seo.md`](../seo.md).

**Working method:** Parts 1, 2, 6, 8, 9, 10, and 13 are grounded in a direct codebase audit — every claim traces to a file. Parts 3, 4, 11, and 12 draw on cleaning-industry/Cape Town market expertise; live web search was unavailable during the audit session, so competitor domain authority, backlink counts, and search volumes are marked as *estimates to verify*, not measured fact.

**Confirmed directly with the business owner before this report was finalized:** only 3 services are real (Deep Cleaning, End-of-Tenancy, Post-Construction — not the 9 originally considered); the `.co.za` domain is purchased but DNS isn't pointed yet; Google Business Profile is already claimed with real reviews.

---

## Scoreboard

| Category | Original (audit) | Current (2026-07-06) | Change |
|---|---|---|---|
| Technical SEO | 41 / 100 | 78 / 100 | ↑37 |
| Local SEO | 52 / 100 | 58 / 100 | ↑6 |
| GEO / AI visibility | 22 / 100 | 45 / 100 | ↑23 |
| EEAT | 40 / 100 | 60 / 100 | ↑20 |
| Content & topical authority | 46 / 100 | 54 / 100 | ↑8 |
| Conversion (CRO) | 68 / 100 | 72 / 100 | ↑4 |
| Backlinks / off-site authority | 15 / 100 | 15 / 100 | unchanged |
| **Composite** | **40 / 100** | **59 / 100** | **↑19** |

Read this as: the product layer (booking, pricing, UX) was always ahead of the category. The discoverability layer was near zero and is now mostly fixed at the code level — the growth ceiling from here is limited by off-site work (domain cutover, citations, backlinks, content), not engineering.

---

## Part 1 — Technical SEO Audit

Architecture is unusually disciplined for a site this size — shallow crawl depth, one H1 per page, clean canonicals. The problems were downstream of two decisions: shipping Tailwind from a CDN, and never finishing the structured-data layer. **Both are now fixed.**

| Dimension | Score | Verdict |
|---|---|---|
| Site architecture & crawl depth | 85/100 | Every page ≤2 clicks from home; flat route structure. Genuinely good. |
| Internal linking | 70/100 | Dense for a small site. No service×suburb combination pages yet — correct call at current authority. |
| Page speed / Core Web Vitals | 28/100 → fixed | Was Tailwind-by-CDN + 8 unscoped JS libraries on every route. Migrated to real Vite build; dead/redundant JS removed. |
| Structured data / schema | 20/100 → fixed | Was only `Service` + `BlogPosting`. Now `LocalBusiness`, `FAQPage`, `BreadcrumbList` sitewide. |
| Metadata (title/description) | 75/100 | Solid, unique per-page. |
| Headings | 90/100 | Single H1 discipline confirmed sitewide. |
| Canonicals & indexation | 45/100 → fixed | Canonical logic correct; `noindex` now added to admin/token/orphaned pages. |
| Sitemap & robots.txt | 30/100 → fixed | Sitemap route was well built; `robots.txt` now dynamic, self-corrects with `APP_URL`. |

### Findings (✓ = resolved 2026-07-06)

- ✓ **`robots.txt` pointed at an unresolvable domain.** Hardcoded `Sitemap: https://springkleaners.co.za/sitemap.xml` while only `springkleaners.onrender.com` was live. Now a dynamic route driven by `config('app.url')`.
- ✓ **Tailwind served from CDN in production.** Not supported for production per Tailwind's own docs — render-blocking, no purge, full JIT shipped to the browser. `tailwindcss ^4.0.0` + `@tailwindcss/vite` were already installed and unused. Migrated to the real build.
- ✓ **8 unscoped JS libraries on every route.** jQuery, GSAP, ScrollTrigger, SplitText, WOW.js, Swiper, Odometer, appear.js all loaded regardless of whether a page used them. Swiper and jQuery+appear.js were fully dead/redundant and removed entirely; GSAP/ScrollTrigger/SplitText now scoped to the homepage only.
- ✓ **No `LocalBusiness`/`Organization` schema anywhere**, and `FAQPage` schema missing despite 20 existing FAQ answers. Both shipped sitewide.
- ✓ **Nothing emitted `noindex`**, including `/admin/*` and per-client token pages. Fixed, plus the orphaned `/get-my-quote` page found during this work.
- ✓ **No Open Graph/Twitter Card tags**, despite 100% of the conversion funnel running through WhatsApp link-sharing. Added sitewide.
- **Working well (unchanged):** Alt text is genuinely well handled, lazy loading on blog cards, canonical tags correctly self-reference and strip query strings, single-H1 discipline holds everywhere.

---

## Part 2 — Local SEO

GBP is already claimed with real reviews — this is an optimization job, not a from-zero build.

### GBP checklist (still needs the business owner)

| Item | Priority | Action |
|---|---|---|
| Primary category | Verify | Should be *House cleaning service*, not a generic bucket. |
| Secondary categories | Check | Only the 3 real services — don't add categories for services not offered. |
| Website field | Time-sensitive | Update the moment DNS cuts to `springkleaners.co.za`. |
| Service area | Confirm | Set to the 8 core suburbs, kept tight. |
| Business type | Confirm | Service-Area Business, address hidden (no fake storefront). |
| Photos | Ongoing | Real, geo-relevant job photos weekly — not stock imagery. |
| Review velocity | ✓ Automated | Review-request flow now fires on `Completed` status (see below). |
| GBP Posts | Weekly cadence | Tied to real content (blog post, seasonal angle, before/after photo). |
| Q&A seeding | Low effort | Paste the 20 existing FAQ answers into GBP Q&A. |
| Booking link | Verify | Point at `/book` directly. |

### Citations to build

| Source | Why | Priority |
|---|---|---|
| Hellopeter | SA's dominant independent review platform; high domain trust | Now |
| Facebook Business Page | Currently doesn't exist — build before linking | Now |
| Bing Places / Apple Business Connect | Captures Bing/Copilot and Apple Maps/Siri traffic | 30 days |
| Property24 / Private Property listings | Direct intent overlap with end-of-tenancy | 30–60 days |
| Northern Suburbs Facebook community groups | Referral traffic + mention corroboration | Ongoing |
| General local directories | Baseline citation consistency | 60 days |

### Resolved

- ✓ **Review automation.** WhatsApp/Email review-request flow existed but was gated behind `payment_status = Paid` and buried in the UI. Now fires on `Completed` alone, in its own card. Also fixed a real bug: the stored `review_url` was a messy Google Search URL, not a stable link — replaced with the clean `g.page/r/...` link (**production still needs this re-entered — the fix only touched the local dev DB**).
- ✓ **Trust signals gap.** No `/about` page existed. Now built at `/about`, linked from the navbar, with real registration number and verifiable claims — no fabricated founder bio.

---

## Part 3 — Keyword Research

Grounded in the *actual* 3 service lines. Volumes are qualitative bands — pull exact numbers from Search Console once the domain is live.

**Cluster A — Deep Cleaning**: `deep cleaning cape town` (P1) · `deep cleaning services milnerton/blouberg/table view` (P1) · `how much does a deep clean cost cape town` (P2, existing blog) · `deep clean vs regular clean` (P2, new post) · `spring cleaning cape town` (P2, brand-name hook)

**Cluster B — End-of-Tenancy**: `end of tenancy cleaning cape town` (P1) · `move out cleaning cape town` (P1, synonym coverage needed) · `will I get my deposit back cleaning` (done, existing blog) · `letting agent approved cleaning cape town` (P2) · `end of tenancy cleaning blouberg/century city` (P1)

**Cluster C — Post-Construction**: `post construction cleaning cape town` (P1) · `builders clean cape town` (P2, synonym) · `post construction cleaning contractors/developers` (P2, new B2B page) · `post renovation cleaning cape town` (P3)

**Cluster D — Comparison/buyer-guide (highest GEO value)**: `how to choose a cleaning company cape town` (P1, new pillar) · `best cleaning company cape town` (P3, long game — needs EEAT/backlinks first) · `average cleaning cost cape town` (P1, ties to Part 12's Price Index)

**Constraint, not a gap:** "Same-day cleaning Cape Town" has real volume but the weekend-only, 2/day capacity model can't fulfil it — don't build content promising what operations can't deliver.

---

## Part 4 — Competitor Analysis

*Data limitation: live web search was unavailable during the audit; figures below are framework/pattern guidance, not measured data. Pull live SERPs + a backlink export before acting on priority.*

**National marketplace platforms** win on brand/domain authority and paid acquisition, lose on hyperlocal specificity and pricing opacity.

**Cape Town independents** win on Maps proximity/reviews, almost never invest in content or schema — this is where SpringKleaners' technical foundation can outrank on content depth alone.

| Front | Typical competitor weakness | SpringKleaners' edge |
|---|---|---|
| Pricing transparency | Hidden behind "request a quote" | Published estimate tiers — rare in this category |
| Booking UX | Generic multi-city flows or WhatsApp-only | Live-availability calendar with real capacity data |
| Content depth | Rarely publish comparison/checklist/cost-guide content | 6 blog posts already cover these formats |
| Structured data | Neither segment typically implements full schema | First-mover on `LocalBusiness`+`FAQPage`+`Review` schema |

---

## Part 5 — Generative Engine Optimisation (GEO)

AI answer engines don't rank pages — they retrieve facts and decide which source to trust enough to repeat.

**How AI systems choose sources:** extractability first (answer in the first sentence, a table, or schema); structured facts over prose; corroboration across independent sources; entity consistency (same name/phone/services everywhere); original citable data over paraphrase.

| Missing element | Status |
|---|---|
| LocalBusiness/entity schema | ✓ Shipped |
| FAQPage schema | ✓ Shipped |
| Third-party citations | Open — needs Hellopeter/directories/press |
| Original statistics | Open — Price Index/survey not built yet |
| Named authorship | Open — blog still credits "SpringKleaners Team" |
| Comparison content | Open — buyer's-guide pillar not written yet |
| `llms.txt` | Open — cheap, not yet added |

**Roadmap:** schema (done) → original dataset → third-party corroboration → named About/Team page (done) → `llms.txt`.

---

## Part 6 — Entity SEO

| Layer | Entities |
|---|---|
| Primary entity | SpringKleaners (Organization/LocalBusiness) |
| Service entities | Deep Cleaning, End-of-Tenancy, Post-Construction |
| Geographic entities | Cape Town, Northern Suburbs, + 8 core suburbs |
| Related concepts | End-of-tenancy deposit, letting agent inspection, builders clean, house cleaning cost |
| People entities | Still missing — no named owner/operator anywhere |
| Social entities | Dead links removed (2026-07-06); real profiles still need to be created before they can serve as `sameAs` corroboration |

**Knowledge graph path:** Wikipedia isn't realistic at this size. A Wikidata entry is realistic once 2–3 independent citations exist — feeds Google's Knowledge Graph and is read by several AI retrieval pipelines.

---

## Part 7 — Topical Authority (Content Architecture)

Three real service clusters, each with a pillar page (exists), 8 shared local variants (exist, deepened 2026-07-06), and a supporting-content ring (mostly not yet built):

| Cluster | Pillar | Local variants | Supporting content to add |
|---|---|---|---|
| Deep Cleaning | `/services/deep-cleaning` | 8 area pages | Deep clean vs. regular clean · How often to deep clean · Spring cleaning seasonal page |
| End-of-Tenancy | `/services/end-of-tenancy` | 8 area pages | Letting-agent inspection standards · Landlord vs. tenant guide · Student move-out seasonal angle |
| Post-Construction | `/services/post-construction` | 8 area pages | B2B contractor/developer page · Post-renovation vs. new-build · Handover checklist |

**Cross-cutting resource hub:** About page (✓ built) · Cape Town Cleaning Price Index (open) · Buyer's-guide pillar (open) · Consolidated FAQ hub (open).

---

## Part 8 — Content Audit

| Page type | Depth | Originality | Note |
|---|---|---|---|
| Homepage | Low body-text | High (bespoke) | Fine — it's not carrying head-term ranking weight. |
| 3 service pages | Good (200–300 word intros + 4 FAQs each) | High | Strongest content asset on the site. |
| 8 area pages | ✓ Deepened 2026-07-06 (was 60–90 words, now ~doubled) | High | Real per-suburb detail: golf course, lagoon, canal-side turnover, kitesurfing, sea-air exposure. |
| 6 blog posts | Not fully audited | Good intent variety | Recommend a follow-up pass on word count/last-updated/named authorship. |

**Resolved:** stock testimonial photo (credited to an unrelated US franchise) replaced with a real work photo. Separately confirmed the testimonials themselves are genuine — real Google/Bark reviews, not fabricated as an earlier pass had assumed.

---

## Part 9 — AI Visibility Audit

- **Would AI trust this site?** Partially — pricing/process specificity is AI-favourable, and schema now makes facts reliably extractable. Still no independent corroboration.
- **Would AI cite it?** Unlikely beyond a direct brand query, until original data + citations exist.
- **Would AI summarise it?** Yes, for direct questions — the FAQ content answers real questions well.
- **Would AI recommend it?** Not yet — recommendation needs corroboration this site doesn't have yet (Part 5/11 targets).

---

## Part 10 — Conversion Optimisation

The strongest part of the whole build — booking wizard, WhatsApp-first flow, and pricing transparency are genuinely ahead of the category.

**Working well:** live-availability calendar with real capacity data, mobile sticky WhatsApp/quote bar, published estimate pricing, instant price feedback in the wizard.

**Open opportunities:**
- ✓ *Resolved* — Open Graph share cards (every WhatsApp-shared link now previews correctly)
- Real embedded Google Reviews widget (currently hardcoded testimonials, even though genuine)
- Genuine scarcity messaging on the homepage, sourced from the real availability endpoint
- Trust-badge row for insurance/vetting claims (currently claimed in copy with no visual proof)

---

## Part 11 — Backlink Strategy

| Partner type | Why high-value | Priority |
|---|---|---|
| Letting agents / property managers | Natural end-of-tenancy referral + real lead flow | P1 |
| Construction companies / developers | Post-construction B2B relationship, project case studies | P1 |
| Hellopeter | SA-specific, high domain trust | P1 |
| Hyperlocal community press | Genuine news value, highest-quality link type | P2 (tie to Part 12) |
| Interior designers / architects | Lower volume, decent relevance | P3 |
| General local directories | Baseline citation consistency | P3 |

*Expected authority gain is directional without a live backlink baseline — establish that baseline via Ahrefs/Semrush/GSC once the domain is live.*

---

## Part 12 — Content Worth Quoting (GEO Bait)

| Asset | Source of originality | Why it earns links/citations |
|---|---|---|
| Cape Town Cleaning Price Index | `bookings` table already has real pricing data to aggregate | Defensible, updatable primary-source statistic |
| Deposit Recovery Survey | Survey past end-of-tenancy clients | Original statistic tied to highest-intent service |
| Public cleaning cost calculator | Booking wizard's pricing logic already exists | Embeddable/shareable — attracts links articles don't |
| Spring Cleaning Checklist | Leans into the brand name | Seasonal spike + brand reinforcement |
| Post-Construction Handover Checklist | Practical, developer-facing | Backlink bait for the B2B partnership angle |

---

## Part 13 — Zero-Click SEO

- ✓ *Shipped* — `FAQPage` schema across 20 existing FAQ answers.
- Open: direct-answer opening sentences in relevant sections (extractive-friendly for snippets and AI).
- Open: repurpose the existing `x-blog.callout` component as a "Quick answer" variant.
- Open: `HowTo` schema for the handover/deposit-recovery checklists once written.
- ✓ *Shipped* — `BreadcrumbList` schema on service/area/blog/about pages.

---

## Part 14 — Implementation Roadmap

**Quick wins (this week) — mostly done.** Remaining: DNS cutover + GBP field/category review (needs the business owner).

**30 days — mostly done.** Remaining: claiming Hellopeter/Facebook/Bing Places (needs the business owner).

**60 days (not started):** Cape Town Cleaning Price Index v1 · public cost calculator · 2–3 letting-agent partnerships · buyer's-guide content · weekly GBP posting.

**90 days (not started):** Digital PR pitch · first construction/developer backlink partnership · `llms.txt` · Wikidata groundwork.

**6 months:** Use accumulated Search Console data to decide, with evidence, whether commercial/office cleaning demand justifies actually building that service line.

**12 months:** Full topical authority across all three clusters; revisit the deferred admin/ERP investment if booking volume justifies it.

---

## Part 15 — Final Deliverables

### 10 biggest mistakes (9 of 10 now fixed)

1. ✓ `robots.txt` pointed its sitemap at a domain that wasn't live
2. ✓ Tailwind served from a CDN while a real Vite build sat unused
3. ✓ Zero `LocalBusiness` schema anywhere
4. ✓ No Open Graph tags on a 100%-WhatsApp funnel
5. ✓ No `noindex` on admin/token pages
6. ✓ Mismatched stock testimonial photo
7. ✓ Dead social/legal links
8. ✓ No review-request automation despite the `Completed` hook already existing
9. ✓ No About/Team page
10. **Open** — no live backlink/citation profile; domain cutover, Hellopeter, and a real Facebook page all still need the business owner's action

### Fastest path to #1 locally

Domain cutover → GBP field/category correction → review-velocity (now automated) → core citations (Hellopeter, Facebook, Bing) → schema (done). Google's local-ranking framework weighs relevance, distance, and prominence — prominence is driven disproportionately by review signals and citation consistency.

### Fastest path to AI-recommended

Schema (done) → one original dataset (not yet built) → third-party corroboration (not yet built). Everything else in this report supports one of those three moves.
