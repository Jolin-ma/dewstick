---
name: Dewstick
description: Bilingual store for a treatment-first tinted lip balm — linen ground, serif voice, 12-column editorial grid, one warm red.
colors:
  linen: "#f4efe9"
  paper: "#ffffff"
  ink: "#1e1a17"
  studio: "#f6f5f3"
  dew: "#e94b3c"
typography:
  heading:
    fontFamily: "\"Newsreader\", \"Noto Serif SC\", Georgia, \"Times New Roman\", serif"
    fontSize: "1.625rem → 2rem (md)"
    fontWeight: 400
    lineHeight: 1.16
    letterSpacing: "0"
  title:
    fontFamily: "\"Newsreader\", \"Noto Serif SC\", Georgia, serif"
    fontSize: "1.125rem"
    fontWeight: 400
    lineHeight: 1.2
    letterSpacing: "0"
  body:
    fontFamily: "\"Hanken Grotesk\", \"Noto Sans SC\", system-ui, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 400
    lineHeight: 1.35
    letterSpacing: "0.02em"
  label:
    fontFamily: "\"Hanken Grotesk\", \"Noto Sans SC\", system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.3
    letterSpacing: "0.02em"
    textTransform: uppercase
  wordmark:
    fontFamily: "\"Bodoni Moda\", \"Noto Serif SC\", Didot, serif"
    fontWeight: 700
rounded:
  none: "0px"
spacing:
  gap: "1.25rem"
  section: "4rem → 8rem (md)"
  header: "2.75rem → 4rem (md)"
components:
  button-light:
    backgroundColor: "{colors.linen}"
    textColor: "{colors.ink}"
    typography: "{typography.title} at 0.9375rem"
    rounded: "{rounded.none}"
    size: "2 grid columns wide, 2.5rem tall"
  button-outline:
    backgroundColor: "transparent"
    border: "1px currentColor"
    hover: "ink fill, linen text"
  button-filled:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.linen}"
    hover: "{colors.dew}"
    use: "add to cart, checkout only"
  link-underline:
    typography: "serif, 1px underline below the baseline"
    hover: "{colors.dew}"
  input-email:
    border: "bottom only, 1px ink"
    placeholder: "serif"
---

# Design System: Dewstick

> **Status (2026-10-23):** this is the system the website (dewstick.vercel.app, `shop/dewstick.css`) runs. The Shopify theme in `shopify-theme/` still uses the previous system, archived at [`docs/archive/DESIGN-pharmacy-counter.md`](docs/archive/DESIGN-pharmacy-counter.md), until the redesign is ported. How this system was derived (a study of susannekaufmann.com) is in [`docs/redesign/DESIGN.md`](docs/redesign/DESIGN.md).

## Overview

**Creative North Star: "The Morning Ritual."**

Dewstick reads like a quiet skincare journal: a warm linen page, a bookish serif for every heading and button, small sans for everything else, and the brand's own photography doing the emotional work. The product is a tinted lip balm treated as a treatment first, so the page keeps the discipline the brand started with — square corners, flat surfaces, one red — and adds editorial calm: asymmetric placement on a 12-column grid, generous empty ground, and sections that alternate between selling and storytelling.

**Visual anti-reference:** the playful, value-priced DTC lipstick look (tuttilipstick.com) — joke-forward copy, candy fills, rounded bubbly containers. The brand's photos are bright and candy-coloured; the page around them stays calm so they can be.

**Key characteristics:**
- Linen `#f4efe9` page ground; white only for cards.
- Serif (Newsreader) for headings, card titles, buttons and links; sans (Hanken Grotesk) for body, prices, nav.
- No bold anywhere except the wordmark. Hierarchy comes from family and size.
- 12-column grid (6 on phones), 1.25rem gutters, no max-width container — the whole layout grows with the root font size above 1440px.
- Zero radius, zero shadows.
- Dew Red is a seal: the wordmark, hovers, the cart count. Never a fill.
- English and 中文 are equal; every string ships in both.

## Colors

| Token | Value | Role |
|---|---|---|
| `--linen` | `#f4efe9` | Page ground, header, light buttons |
| `--paper` | `#ffffff` | Ingredient cards, product-page panels |
| `--ink` | `#1e1a17` | All text, outline borders, filled buttons, announcement bar (`#1e1e1e`) |
| `--muted` | ink at 60% | Secondary text, placeholders |
| `--line` | ink at 28% | Hairlines (USP strip, footer rule) |
| `--studio` | `#f6f5f3` | Image-well fallback while photos load |
| `--dew` | `#e94b3c` | Wordmark, link/button hover, cart count |

**The Seal Rule.** Red appears on the wordmark and on interaction only. If a section needs red to work, the layout is wrong.

**The Ink-Is-Grey Rule.** Greys are ink at an opacity, never new tokens.

## Typography

- **Heading** (`.h2`, `.t-3xl`): Newsreader 400, 1.625rem on phones → 2rem from 48rem, line-height 1.16, `text-wrap: balance`. Sentence case.
- **Title** (`.t-xl`): Newsreader 400, 1.125rem — card titles, the gallery heading, footer column heads.
- **Body**: Hanken Grotesk 400, .9375rem, line-height 1.35, tracking .02em. Measure ≤ 23rem (`.measure`).
- **Label** (`.u-caps`): Hanken Grotesk .875rem uppercase, tracking .02em — nav, footer links, badges (.75rem).
- **Wordmark**: `dewstick`, Bodoni Moda 700, always Dew Red.
- **中文**: Noto Serif SC for headings, Noto Sans SC for body.

**Fluid root.** `html { font-size: max(16px, calc(16px + (100vw - 1440px) * 6.4 / 1440)); }` — 16px up to 1440px, ~18.1px at 1920px. Everything is in `rem`, so the page scales instead of leaving more empty space on large screens.

## Layout

- **Grid:** `.g12` — `repeat(var(--cols), 1fr)`, `--cols` 6 → 12 at 48rem, `column-gap: 1.25rem`, `1.25rem` side padding. `--col` (one column, via container query units) sizes buttons and offsets: a button is 2 columns wide; copy blocks start at column 4.
- **Breakpoints:** 48rem (12 columns, larger headings, desktop sections) and 64rem (full header nav, USP strip in one row). Below 64rem the header uses the menu button.
- **Rhythm:** sections sit `4rem` apart on phones, `8rem` from 48rem; tuned exceptions (product row 3.8rem after the USP strip, "Why it works" 14rem) match the reference's pacing.
- **Images:** the store photos (`shop/img/dewstick-01…13`) are square artwork with type baked in, so every frame that shows them is **1:1** and nothing is overlaid on them. Never crop them to portrait.

## Components

### Buttons
- **Light** (`.btn--light`): linen fill, ink serif label, left-aligned, 2 columns wide. For use on photos/video.
- **Outline** (`.btn--outline`): 1px currentColor border, transparent. The default on the linen page; hover inverts to ink.
- **Filled** (`.btn`): ink fill, linen label, hover Dew Red. Reserved for add-to-cart and checkout.
- **Underline link** (`.link-u`): serif with a 1px underline; hover Dew Red. "Shop all", "Previous / Next", newsletter "Join".
- Stacked buttons use `.btn-stack` (0.6rem gap). Touch screens get a 2.75rem minimum height.

### Fields
`.field-row`: input with a bottom border only, serif placeholder, and an underlined text button beside it. Error = 2px Dew Red bottom border plus a message in `[data-note]`.

### Product card (`.pcard`)
No container. Square image well, optional linen badge chip top-left ("SAVE $12"), then serif title (shade swatch dot + name / tone), sans price (with struck compare-at for the set), and a one-line descriptor (clamped to 3 lines on phones). Hover scales the image to 1.01; press moves the card down 1px.

### Ingredient card (`.scard`)
White panel in a sideways scroller (`.scroller`, scroll-snap) that starts at column 4 on desktop. Serif title, uppercase benefit label, body, and an ingredient photo pinned to the bottom. Cards are 3:4 on desktop. Previous / Next text buttons on desktop.

### Header
Sticky, linen, tints to 90% + blur after 8px of scroll. Wordmark (cols 1–3), uppercase nav from column 4, EN/中文 + Cart on the right. Below 64rem: wordmark, bag icon, menu button; the menu opens a serif link list.

## Homepage structure

1. **Announcement bar** — dark, uppercase, starts at column 4 on desktop.
2. **Hero** — full-bleed 16:9 video (`shop/video/hero.mp4`, whole frame visible on desktop; 4:5 crop on phones). Headline top-left, two lines of copy and Light + Outline buttons offset by two columns.
3. **USP strip** — three ✓ claims between hairlines.
4. **"Find your shade"** — 4-up product row (nectar, poppy, blush, the set of five), 2-up on phones, "Shop all" link.
5. **"Juicy. Glossy. Balmy."** — the asmr macro (`dewstick-09`) docked left (cols 1–6), sticky copy + outline button on the right.
6. **Story band** — "Half the tube is lipstick…" on the linen page with an outline button; the full lips photo (`band-lips-square.jpg`) docked right at full height. On phones: copy, then the photo.
7. **"Start here"** — four square tiles (shades, ingredients, the set, the idea) with serif captions; a swipe row on phones.
8. **"Why it works"** — ingredient card scroller (squalane, shea butter, ceramides, real colour).
9. **"No reviews yet."** — founder note in three zones: heading (cols 1–3), copy + outline button (cols 4–7), photo (cols 9–12). Honest by design: no invented reviews, ratings or counts.
10. **Newsletter + photo wall** — "Stay in the dew" form beside four square store photos (three on phones). On every page.
11. **Footer** — hairline rule, serif column heads, uppercase links, language switch, © line.

## Motion

Minimal on purpose: the hero video carries the movement. Card images scale 1.01 on hover (0.4s `cubic-bezier(.25,1,.5,1)`), cards press 1px, links fade or turn red over 0.15s, smooth scrolling is off under `prefers-reduced-motion`. No scroll-reveal animations.

## Do's and Don'ts

**Do**
- Keep every corner square and every surface flat.
- Show store photos whole, in square frames.
- Use the outline button on the linen page, the light button on photos/video.
- Ship every string in English and 中文 (`scripts/site_strings.json` or the theme locales).
- Keep body copy to a ~23rem measure.

**Don't**
- Don't fill anything with Dew Red or add a second accent.
- Don't put text or scrims over the store photos.
- Don't use the retired waitlist photography (old "DewStick" packaging).
- Don't fabricate reviews, ratings or customer counts.
- Don't disable pinch-zoom or add a load-time popup.
