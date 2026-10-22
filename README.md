# dewstick — a tinted lip balm store, end to end

A self-directed case study in end-to-end e-commerce coordination: scan live
marketplaces for a viable product → stand up the store → design the
content-to-purchase funnel → read the results. **dewstick** is the fictional
product used to run that workflow — a tinted lip balm positioned as "half
lipstick, half lip mask".

## See it

| | |
|---|---|
| **Store (public copy)** | **https://dewstick.vercel.app** — every page of the Shopify store, EN / 中文, with a working cart |
| Shopify store | https://dewstick.myshopify.com — the real one, with Shopify checkout in test mode. Password-protected (Shopify development stores can't be made public) |
| Waitlist version | https://dewstick.vercel.app/waitlist — the earlier pre-launch landing page |

## Static copy of the store (Vercel)

`index.html`, `collections/`, `products/`, `cart.html`, `404.html` and `zh/` are
**generated** from the Shopify theme by `scripts/build_shop.py` — same markup and
CSS (`shop/dewstick.css` is copied from the theme), same copy (the theme's
`locales/*.json`), product data mirrored from the store. Images and the hero video
are local copies in `shop/img/` and `shop/video/`.

- Cart lives in `localStorage` (`shop/site.js`): add, change quantity, remove, subtotal.
- **Check out** explains that this is the portfolio copy and links to the Shopify
  store, where the real (test-mode) checkout runs.
- The newsletter form doesn't collect anything here; it says so.

```bash
python scripts/build_shop.py   # regenerate after changing the theme or its locales
npm run dev                    # npx serve . — cleanUrls like Vercel
```

## Waitlist page (`waitlist.html`)

The original pre-launch site, kept at `/waitlist`.

## What it is

- **Single self-contained `waitlist.html`** — Tailwind via CDN, inline `<style>` / `<script>`, no build step.
- **Bilingual** — every string in English and Simplified Chinese, switched by an `EN / 中文` toggle (pure-CSS `html[data-lang]`, persisted to `localStorage`, updates `<title>` / `<html lang>` / input placeholders).
- **Layout** recreates the [Typology](https://typology.com) homepage structure (announcement bar → full-bleed hero → formula strip → product grids → lifestyle break → ingredient grid → `@dewstick` Instagram wall → founder note → philosophy → footer), adapted for a waitlist rather than a store. The two closing sections (philosophy, footer) break the flat single-accent system on purpose: a full Dew Red ground and an espresso-brown footer as an end-of-scroll crescendo.
- **Images** are real product/lifestyle photography in `image/` (per-shade packshots auto-cropped to 4:5 from multi-panel shoots; every well is full-bleed `object-cover`).
- Fonts: Hanken Grotesk (Latin) + Noto Sans SC (Chinese), **17px base, 13px uppercase micro-labels**.

## Deploy

Live at **https://dewstick.vercel.app** (store copy at `/`, waitlist at `/waitlist`). Static — the repo root deploys on
Vercel with no build step; `vercel.json` sets clean URLs and image caching.
The Vercel project is git-linked, so every push to `main` auto-deploys.

## Shopify store (`shopify-theme/`)

The site is also rebuilt as a **real, functional shop** on the Shopify development
store `dewstick.myshopify.com` — no waitlist: add to cart → cart → Shopify checkout
(Shopify's test payment gateway, so no real money moves).

- **Catalog** — one product, *dewstick tinted lip balm*, with the five shades as
  variants ($22 CAD), plus *the set of five* ($98 CAD, compare-at $110). Collection `shades`.
- **Theme** — custom Online Store 2.0 theme, same "Pharmacy Counter" design system
  in plain CSS (no Tailwind). All copy lives in `locales/en.default.json` and
  `locales/zh-CN.json`; EN / 中文 uses Shopify's native languages (`/zh-cn/` URLs).
- **Hero** takes a short looping background video (set in the theme editor).
- **Newsletter** — Shopify customer form; signups land in Customers tagged
  `newsletter` + `source:` / `medium:` / `campaign:` from the landing URL's UTMs.
- Images live in Shopify Files (`dewstick-01.png` … `dewstick-13.png`) and are
  referenced from the JSON templates.

```bash
shopify theme check --path shopify-theme                 # lint
shopify theme dev   --path shopify-theme --store dewstick  # local preview
shopify theme push  --path shopify-theme --store dewstick --unpublished
```

`.vercelignore` keeps `shopify-theme/` and `scripts/` out of the Vercel deploy.

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

- `submitToWaitlist()` in `waitlist.html` is a stubbed 400ms resolve. Point it at a
  real list tool (the brief recommends Klaviyo's free tier) and add UTM capture
  so the funnel is measurable.
- Social links: footer links are `#`, and the `@dewstick` Instagram wall tiles
  are deliberately *not* linked (no real account exists yet — the earlier link
  pointed at someone else's `instagram.com/dewstick`). Wire tile links + a
  "Follow" CTA back in once the account is real.
- The "read the results" analytics layer — per-source landing → email conversion.
