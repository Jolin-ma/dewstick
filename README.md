# dewstick — a tinted lip balm store, end to end

A self-directed case study in end-to-end e-commerce coordination: scan live
marketplaces for a viable product → stand up the store → design the
content-to-purchase funnel → read the results. **dewstick** is the fictional
product used to run that workflow — a tinted lip balm positioned as "half
lipstick, half lip mask".

## See it

| | |
|---|---|
| **Store (public copy)** | **https://dewstick.vercel.app** — every page of the store, EN / 中文, with a working cart |
| Shopify store | https://dewstick.myshopify.com — the real one, with Shopify checkout in test mode. Password-protected (Shopify development stores can't be made public) |

## Website (Vercel)

`index.html`, `collections/`, `products/`, `cart.html`, `404.html` and `zh/` are
**generated** by `scripts/build_shop.py`:

- **Design** — redesigned 2026-10-23 after an editorial study of susannekaufmann.com:
  linen ground, Newsreader serif headings and buttons, a 12-column grid, square
  frames, one red. The system is in [`DESIGN.md`](DESIGN.md); the study, decisions
  and what changed during the build are in [`docs/redesign/DESIGN.md`](docs/redesign/DESIGN.md).
- **Stylesheet** — `shop/dewstick.css`; the Shopify theme's `assets/dewstick.css` is the
  same file plus two lines for its section layout.
- **Copy** — the theme's `locales/*.json` (the redesigned homepage is the `home`
  namespace). Every string ships in English and 中文.
- **Images** — the store's photo set in `shop/img/` (`dewstick-01…13` at 500 / 1000px,
  plus the ingredient photos and the lips band image) and the hero video in `shop/video/`.
- **Cart** lives in `localStorage` (`shop/site.js`): add, change quantity, remove,
  subtotal. **Check out** explains that this is the portfolio copy and links to the
  Shopify store, where the real (test-mode) checkout runs.
- The newsletter form doesn't collect anything here; it says so.

```bash
python scripts/build_shop.py   # regenerate the pages after changing the builder, copy or CSS
npm run dev                    # npx serve . — cleanUrls like Vercel
```

Homepage, top to bottom: announcement bar → full-bleed hero video → USP strip →
"Find your shade" product row → "Juicy. Glossy. Balmy." (image left, text right) →
"Half the tube is lipstick" story band (text left, photo right) → "Start here" tiles →
"Why it works" ingredient cards → "No reviews yet." founder note → newsletter +
photo wall → footer.

## Deploy

Live at **https://dewstick.vercel.app**. Static — the repo root deploys on Vercel
with no build step; `vercel.json` sets clean URLs and caching for `shop/img` and
`shop/video`. The Vercel project is git-linked, so every push to `main` auto-deploys.
`.vercelignore` keeps `shopify-theme/` and `scripts/` out of the deploy.

## Shopify store (`shopify-theme/`)

The store is a **real, functional shop** on the Shopify development store
`dewstick.myshopify.com`: add to cart → cart → Shopify checkout (Shopify's test
payment gateway, so no real money moves).

- **Catalog** — one product, *dewstick tinted lip balm*, with the five shades as
  variants ($22 CAD), plus *the set of five* ($98 CAD, compare-at $110). Collection `shades`.
- **Theme** — custom Online Store 2.0 theme in plain CSS, running the redesign
  ([`DESIGN.md`](DESIGN.md)); published as "Dewstick (redesign)". All copy lives in `locales/en.default.json`
  and `locales/zh-CN.json`; EN / 中文 uses Shopify's native languages (`/zh-cn/` URLs).
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

## WordPress + WooCommerce (`wordpress/`)

The same store as a classic WordPress theme on WooCommerce: same design, copy, catalog and EN / 中文, with a real
WooCommerce cart and checkout (test payment, no charge). Runs locally on WordPress Playground, no PHP or MySQL install:

```bash
cd wordpress && npm install && npm start   # http://127.0.0.1:9400
```

Details in [`wordpress/README.md`](wordpress/README.md).

## Docs

- [`PRODUCT.md`](PRODUCT.md) — users, positioning, constraints, brand commitments.
- [`DESIGN.md`](DESIGN.md) — the current design system (website).
- [`docs/redesign/`](docs/redesign/DESIGN.md) — the Susanne Kaufmann study and the redesign decisions; reference screenshots in `docs/reference/susanne-kaufmann/`.
- [`docs/archive/`](docs/archive/) — the previous design system and the original waitlist build brief.

Canonical decisions (confirmed 2026-08-31):

- **Positioning** — luxury lip treatment first, colour is the bonus. Skincare-first, warm not joke-forward; deliberately not in Tutti's playful/value lane.
- **Pricing** — single **$22 CAD**, the set of five **$98 CAD** (save $12).
- **Shade names** — nectar (coral), poppy (true red), peony (rosy nude), blush (soft pink), orchid (berry rose).
- **Reviews** — an honest "No reviews yet." + founder note rather than fabricated reviews.

## Still to do

- Social links: the photo wall is deliberately *not* linked (no real `@dewstick`
  account exists yet). Wire tile links + a "Follow" CTA once the account is real.
- The "read the results" analytics layer — per-source landing → purchase / signup conversion.
