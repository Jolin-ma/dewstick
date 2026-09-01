# dewstick — bilingual waitlist landing page

A self-directed case study in end-to-end e-commerce coordination: scan live
marketplaces for a viable product → stand up the store → design the
content-to-waitlist funnel → read the results. **dewstick** is the fictional
product used to run that workflow — a tinted lip balm positioned as "half
lipstick, half lip mask".

This repo is step 2–3: the site and the front of the funnel.

## What it is

- **Single self-contained `index.html`** — Tailwind via CDN, inline `<style>` / `<script>`, no build step.
- **Bilingual** — every string in English and Simplified Chinese, switched by an `EN / 中文` toggle (pure-CSS `html[data-lang]`, persisted to `localStorage`, updates `<title>` / `<html lang>` / input placeholders).
- **Layout** recreates the [Typology](https://typology.com) homepage structure (announcement bar → full-bleed hero → formula strip → product grids → lifestyle break → ingredient grid → `@dewstick` Instagram wall → founder note → philosophy → footer), adapted for a waitlist rather than a store. The two closing sections (philosophy, footer) break the flat single-accent system on purpose: a full Dew Red ground and an espresso-brown footer as an end-of-scroll crescendo.
- **Images** are real product/lifestyle photography in `image/` (per-shade packshots auto-cropped to 4:5 from multi-panel shoots; every well is full-bleed `object-cover`).
- Fonts: Hanken Grotesk (Latin) + Noto Sans SC (Chinese), **17px base, 13px uppercase micro-labels**.

## Run locally

```bash
npm run dev        # npx serve .
# or open index.html through any static server
```

## Deploy

Live at **https://dewstick.vercel.app**. Static — the repo root deploys on
Vercel with no build step; `vercel.json` sets clean URLs and image caching.
The Vercel project is git-linked, so every push to `main` auto-deploys.

## Build spec & decisions

The full brief is in [`docs/dewstick_master_build_brief.md`](docs/dewstick_master_build_brief.md).
Durable product truth lives in [`PRODUCT.md`](PRODUCT.md). The §13 "open decisions"
were confirmed on 2026-08-31 and are now canonical:

- **Positioning** — luxury lip treatment first, colour is the bonus. Skincare-first, warm not joke-forward; deliberately not in Tutti's playful/value lane.
- **Pricing** — single **$22 CAD**, the set of five **$98 CAD** (save $12).
- **Shade names** — nectar (coral), poppy (true red), peony (rosy nude), blush (soft pink), orchid (berry rose).
- **Funnel tool** — Klaviyo free tier (still to wire).

Two deliberate departures from a literal Typology copy: the lifestyle-break CTA
is a real email capture (this is a waitlist, not a store), and the reviews block
is an honest "No reviews yet." + founder note rather than fabricated reviews.

## Still to wire

- `submitToWaitlist()` in `index.html` is a stubbed 400ms resolve. Point it at a
  real list tool (the brief recommends Klaviyo's free tier) and add UTM capture
  so the funnel is measurable.
- Social links: footer links are `#`, and the `@dewstick` Instagram wall tiles
  are deliberately *not* linked (no real account exists yet — the earlier link
  pointed at someone else's `instagram.com/dewstick`). Wire tile links + a
  "Follow" CTA back in once the account is real.
- The "read the results" analytics layer — per-source landing → email conversion.
