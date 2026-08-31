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
- **Layout** recreates the [Typology](https://typology.com) homepage structure (announcement bar → full-bleed hero → formula strip → product grids → lifestyle break → ingredient grid → captioned tiles → founder note → philosophy → footer), adapted for a waitlist rather than a store.
- **Images** are all [`placehold.co`](https://placehold.co) placeholders — drop real transparent product PNGs into the same `<img>` slots.
- Fonts: Hanken Grotesk (Latin) + Noto Sans SC (Chinese), 15px base.

## Run locally

```bash
npm run dev        # npx serve .
# or open index.html through any static server
```

## Deploy

Static — deploy the repo root on Vercel, no build step. `vercel.json` sets
clean URLs and image caching.

## Build spec & decisions

The full brief is in [`docs/dewstick_master_build_brief.md`](docs/dewstick_master_build_brief.md).
Calls made during the build (were "open" in §13 of the brief, still unconfirmed):

- **Positioning** — "luxury lip mask, colour is the bonus", warm not joke-forward.
- **Pricing** — single **$22 CAD**, the set of five **$98 CAD** (save $12).
- **Shade names** — nectar (coral), poppy (true red), peony (rosy nude), blush (soft pink), orchid (berry rose).

Two deliberate departures from a literal Typology copy: the lifestyle-break CTA
is a real email capture (this is a waitlist, not a store), and the reviews block
is an honest "No reviews yet." + founder note rather than fabricated reviews.

## Still to wire

- `submitToWaitlist()` in `index.html` is a stubbed 400ms resolve. Point it at a
  real list tool (the brief recommends Klaviyo's free tier) and add UTM capture
  so the funnel is measurable.
- Real Instagram / TikTok URLs in the footer (currently `#`).
- The "read the results" analytics layer — per-source landing → email conversion.
