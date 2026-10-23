# Dewstick Redesign — Reference Study & Design System

> Source: **susannekaufmann.com** homepage, captured 2026-10-23 with Firecrawl (desktop 1920px full page, mobile 360px full page, branding extraction, raw HTML, and the live `theme.css`).
> Screenshots: [`../reference/susanne-kaufmann/desktop-full.jpg`](../reference/susanne-kaufmann/desktop-full.jpg) · [`mobile-full.jpg`](../reference/susanne-kaufmann/mobile-full.jpg)
>
> **Status (2026-10-23): implemented on the website** (`scripts/build_shop.py`, `shop/dewstick.css`, `scripts/site_strings.json`). The as-built system is the root [`/DESIGN.md`](../../DESIGN.md). The Shopify theme (`shopify-theme/`) is **not** ported yet; it still follows the previous system, archived at [`../archive/DESIGN-pharmacy-counter.md`](../archive/DESIGN-pharmacy-counter.md). Parts 2–6 below are the original proposal; **Part 0** lists where the build differs from it.

---

## Part 0 — What was built, and how it differs from the proposal

Built and compared against the reference at 1920px and 360px with headless Chrome, round by round (section positions ended within ~55px of the reference on desktop). Then adjusted on the owner's direction:

| Area | Proposal | As built | Why |
|---|---|---|---|
| Decisions D1–D6 | Recommendations | All taken as recommended; D4 = **dropped** both the red band and the espresso footer | Page ends quietly on linen, like the reference |
| Photography | Portrait 3:4 / 5:7 frames, waitlist photos | **Store photos only** (`shop/img/dewstick-01…13`) in **square** frames, no overlay text | Store photos are square artwork with baked-in type; portrait crops cut it. Waitlist photos (old packaging) retired |
| Ingredient cards | Placeholder → ingredient photos | Ingredient photos from the retired waitlist page (`shop/img/ing-*.jpg`) | Owner's request |
| Hero | 70vh box | **16:9 box** on desktop, so the whole video frame shows | Owner: top and bottom were cut off |
| Sticky gallery | Text left, two staggered images | **One image** (asmr macro, 09) docked **left** (cols 1–6), sticky text right | Owner moved the second image to the story band |
| Story band | Full-bleed photo, white text over it | Full lips photo **docked right** at full height; copy + outline button on the plain linen page; no fade, no colour ground | Owner's direction, step by step |
| Waitlist | — | `waitlist.html` and the `image/` folder deleted | Owner no longer uses the waitlist version |
| Shopify | Port sections to Liquid | **Deferred** — theme restored untouched | Owner: "keep the design in website first" |

---

## Part 1 — Reference analysis (Susanne Kaufmann)

### 1. Visual style and design direction

**"The Alpine spa journal."** It reads like a quiet print magazine about a spa: warm linen-coloured ground, a bookish serif for every heading, small sans for everything else, and large, warm, sun-lit photography doing almost all the emotional work. There is no accent colour in the UI. Colour comes only from the photos (amber oil, forest green, skin, red clothing).

Traits:
- **Low energy, high confidence.** Few elements per screen, lots of empty ground, and nothing in the layout asks for attention.
- **Editorial asymmetry.** Content is placed on a 12-column grid, not centred. Headings hang left, copy blocks start at column 4, images are offset, and gutters are left empty on purpose.
- **Print typography.** Sentence-case serif headings. Buttons are set in the serif too. Only the nav and footer links use uppercase sans.
- **Flat and square.** 0 radius on buttons, cards and images. No shadows. The only depth is white cards on the linen ground.
- **Photography types:** (a) studio packshots on pale grey, (b) warm macro and texture shots (oil, bubbles, botanicals), (c) outdoor lifestyle and landscape shots (forest, alpine field, spa interior). They alternate down the page.

### 2. Page layout and section order (homepage)

Section IDs come from the Shopify DOM.

| # | Section (`shopify-section-*`) | What it is |
|---|---|---|
| 1 | `announcement_bar` | Full-width near-black strip, one uppercase sans line ("NEW: FOR THE SENSES BODY WASH…"). |
| 2 | `header` | Light linen bar: wide serif caps wordmark on the left, uppercase category nav (Face · Body · Gifts & Sets · Routines · Spa · About) from column 4, utilities (Search · Wishlist · Account · Cart) on the right. |
| 3 | `featured_link` (hero) | Full-bleed **video** (muted, looped, poster image). Serif H1 at top-left. Short sans paragraph + 2 stacked buttons offset to columns 3–5. |
| 4 | `usp_banner` | Three ✓ claims centred between two full-width hairlines. |
| 5 | `featured_products` | "Build your ritual" + "Shop All" text link → 4-up product cards. |
| 6 | `sticky_gallery_and_text` | Small serif heading + body + outline button **sticky in columns 1–3**. Two portrait images (one video) scroll past in columns 5–8 and 9–11, staggered vertically. |
| 7 | `featured_link` (story) | Full-bleed spa-interior video. Large serif statement + light button at the top-right (columns 7–12). |
| 8 | `featured_links` | "Shop by Collection": 4 portrait tiles with a serif caption under each. |
| 9 | `featured_reviews` | "What Our Customers Say": horizontal scroller of white cards starting at column 4. |
| 10 | `featured_products` (2) | Second product row. Empty or not rendered in this capture. |
| 11 | `text_and_media` | Loyalty "Insider": heading in columns 1–3, body + outline button lower in columns 4–7, tall forest photo in columns 9–12. |
| 12 | `footer_social_content` | Newsletter block (heading, body, underline input + text-button) and 4 portrait journal tiles with serif titles over the image. |
| 13 | `footer` | Hairline on top, 5 link columns (serif heading, uppercase sans links), then language and currency switchers. |
| 14 | `footer_copyright_info` | © line + payment icon row. |
| — | Klaviyo modal | Newsletter popup on load (10% off) over a dark scrim. |

Rhythm: product → story → product → story. No two "selling" sections sit next to each other without an image section between them.

### 3. Typography, colour and spacing

**Fonts** (from `theme.css` `@layer theme`):
- `--font-serif: "Martina Plantijn"` (Klim, commercial). Used for all headings, product titles, buttons, review titles and footer column headings.
- `--font-sans: "KaufmannSans"` (custom). Used for body, descriptions, nav, USPs, prices and labels.

**Fluid root size.** Everything is in `rem`, and the root grows above 1440px:
```css
html { --zoom-amount:.4;
  font-size: max(16px, calc(16px + 16 * var(--zoom-amount) * (100vw - 1440px) / 1440)); }
```
At 1440px and below the root is 16px. At 1920px it is ≈18.13px. So the whole layout scales up on large screens instead of leaving more empty space.

**Type scale** (all weight 400; the system has no bold):

| Token | Size | @16px | @1920 | Line-height | Tracking | Used for |
|---|---|---|---|---|---|---|
| `text-3xl` | 2rem | 32 | 36.3 | 116% | 0 | Section headings, hero H1 (serif) |
| `text-2xl` | 1.625rem | 26 | 29.5 | 116% | 0 | Sub-headings, mobile headings |
| `text-xl` | 1.125rem | 18 | 20.4 | 116% | .02em | Small serif headings ("The scent of the ritual"), card titles |
| `text-lg` | .9375rem | 15 | 17 | 120% | .02em | Body copy (sans) |
| `text-base` | .875rem | 14 | 15.9 | 130% | .02em | Nav, descriptions |
| `text-sm` | .75rem | 12 | 13.6 | 116% | .02em | Badges, meta |
| `text-xs` | .625rem | 10 | 11.3 | 116% | .02em | Legal |

Notes: hierarchy comes from **family and size only, never weight**. Headings use tight leading (116%). Body has slight positive tracking (.02em). Copy measure is capped at `max-width: 25ch / 30ch` for small blocks, and body blocks are ~45ch.

**Colour:**

| Token | Value | Role |
|---|---|---|
| `--color-off-white` | `#f2eeeb` | Page ground, header, light buttons (`theme-color` too) |
| `--color-off-black` | `#1e1e1e` | Text, announcement bar, outline borders |
| `--color-off-black-50` | `#888684` | Muted text |
| `--color-white` | `#fff` | Review cards and product image wells (studio white-grey) |
| `--color-grey` / `light-grey` | `#dfdfdf` / `#e6e6e6` | Hairlines, dividers |
| `--color-red` | `#da291c` | Errors only |

**Spacing:** Tailwind v4 `--spacing: .25rem` base. Grid is `--cols:12` desktop / `6` mobile, `--gap: 1.25rem` (20px, also the page gutter, `--grid-bleed`). Column helpers `--col-3`, `--col-5` size buttons and text blocks to the grid. A primary button is exactly 3 columns wide. Sections sit ~100–140px apart on desktop, ~60–80px on mobile. Header height is `4rem` desktop and `2.625rem` mobile.

### 4. Image composition, aspect ratios and cropping

| Use | Ratio | Crop and composition |
|---|---|---|
| Hero / story | Full-bleed, ~16:9 desktop, ~3:4 portrait on mobile | Video. Subject off-centre (bottle right-of-centre) so text can sit on the calm left. Story video keeps the busy area left and text right. |
| Product card | `3/4` (`aspect-ratio:3/4`, also `151/203`) | Studio packshot on a pale grey-white gradient, product centred low with lots of headroom for badges. |
| Sticky gallery | Portrait ~4:5 and ~3:4 | Macro (oil + rosemary) and lifestyle (hand + field). Two different sizes, vertically offset by ~50px. Not aligned on purpose. |
| Collection tiles | `3/4` | Tight crops: face, skin texture, abstract oil bubbles, flat-lay. Mixes people, texture and product. |
| Journal tiles | `39/53` (~0.74) | Portrait with serif title + sans subtitle in white, set centrally or low over the photo. |
| Insider image | Tall portrait, ~3:4 | Landscape subject (forest path) cropped vertical. |
| Review thumbnail | Small `3/4` packshot | Inside the white card. |

Rules: always `object-fit: cover`. Portrait (vertical) ratios dominate everything below the hero. Packshots and lifestyle shots are **never mixed inside the same row**: product rows are all studio and story rows are all mood.

### 5. UI components

- **Announcement bar.** `#1e1e1e`, white uppercase sans ~14px, one line, ~32px tall.
- **Header.** Linen background. On desktop: wordmark left, uppercase sans nav (`text-base`, ~20px gaps) starting at grid column 4, and four utility text links on the right (words, not icons). On mobile: wordmark + three line icons (menu, search, bag).
- **Buttons.** Two fills, one shape: **0 radius, ~40px tall, fixed 3-column width, label left-aligned, serif, sentence case.**
  - *Light* (`#f2eeeb` fill, `#1e1e1e` text): the primary button on photos.
  - *Outline* (1px border, transparent): secondary on photos (white) and the default on the linen ground (black).
  - *Text link*: serif with a 1px underline set below the baseline ("Shop All", "Read more", "Subscribe", "See all +").
- **Product card.** No container. Studio image well at 3:4 with an uppercase badge stack top-left ("NEW", "WORTH $891.91") and a heart (wishlist) top-right. Below: serif title, sans price, 2–3 line sans description (clamped on mobile), ★ row + `(4.9)`.
- **USP strip.** Hairline top and bottom, three ✓ + sans claims centred in a row. Stacks to a left-aligned list on mobile.
- **Collection tile.** Image + small serif caption. No arrow, no overlay.
- **Review card.** White panel on linen, ~610px wide, generous padding (~24px). Serif product name, ★ row, sans quote, author, product thumbnail at the bottom.
- **Newsletter input.** No box. Placeholder in serif, 1px bottom border only. Submit is a separate underlined text button.
- **Footer.** Hairline rule, serif column headings, uppercase sans links, language and currency "switchers" as underlined text with ↓.
- **Popup.** Two-panel modal (form | photo). It is the only element that uses a black filled button and uppercase sans buttons. It comes from Klaviyo and is off-system.

### 6. Animations and interactions

What the capture shows. These are from CSS and DOM, not watched live:
- **Video everywhere:** `<video playsinline loop muted preload="none" poster>`. 5 on the homepage, lazily started. The hero and story backgrounds are motion; the rest of the page is still.
- **Easing tokens:** `--ease-out-quart (.25,1,.5,1)`, `--ease-out-quint (.22,1,.36,1)`, `--ease-out-circ (0,.55,.45,1)`. Default transition is `.15s cubic-bezier(.4,0,.2,1)`.
- **Product card:** on hover (`@media(hover:hover)`) the image scales to `1.01`, and on press the card moves down `1px` (`.product-card:has(:active){translate:0 1px}`, `.2s ease-out-quart`). Very subtle.
- **Links:** hover drops opacity to `.7`. The underline and border colour change to `currentColor`. A small "+"/line grows wider on hover (`group-hover:w-2.5`).
- **Entrances:** `@keyframes slide-in { from {opacity:0; translate:0 5px} }` at `.6s` or `.8s` with stagger (`.1s`/`.2s`) on product page images and info. Recommendations fade in over `.4s`. There is no scroll-reveal library (no AOS or GSAP; one IntersectionObserver, probably for video play).
- **Sticky:** the text column in the "scent of the ritual" section is `position: sticky` while the images scroll. The header uses `scroll-padding-top: var(--header-height)`, so it is likely sticky as well.
- **Carousels:** reviews, plus collections on mobile, are plain horizontal overflow scrollers. No Swiper or Splide.
- **Glass:** `backdrop-blur-xl` is available for overlays (menu or drawer).
- **Built with** Tailwind v4 + Alpine.js (`x-cloak`).

Motion overall: **almost none**. The video carries the movement, and UI motion stays at 5px and 1%.

### 7. Desktop → mobile behaviour

Breakpoints: `40rem`, **`48rem` (768)**, **`64rem` (1024)**, `80rem`, `96rem`. The grid drops from 12 to 6 columns. Header 4rem → 2.625rem.

| Section | Desktop | Mobile (360px) |
|---|---|---|
| Announcement | One line, centred | Same, shorter message |
| Header | Text nav + text utilities | Wordmark + ☰ / search / bag icons |
| Hero | Landscape video, H1 top-left, copy offset to col 3 | Portrait crop (a different composition), H1 → copy → stacked buttons, all left, covering ~70% of the image |
| USP | 3 in a row between hairlines | Vertical list, still between hairlines |
| Products | 4-up | **2-up**, descriptions clamped to 2–3 lines, "See all +" link at the end |
| Sticky gallery | Sticky text + 2 offset images | Text, then images stacked full-width. Not sticky |
| Story | Text top-right over video | Text top-left, video ~4:5 |
| Collections | 4-up grid | Horizontal scroll, ~70vw tiles with "Read more" links |
| Reviews | Scroller starting col 4 | Scroller at the gutter, ~85vw cards |
| Insider | 3-zone asymmetric | Heading → body → button → image, stacked |
| Newsletter + journal | Form left, 4 tiles right | Form full width, tiles in a 3-up thumbnail strip |
| Footer | 5 columns + switchers | 2 columns, switchers stacked below |

Gutters stay `1.25rem` at every size. Type uses the same tokens at every width (only the root grows on large screens). Headings drop from `3xl` to `2xl` on mobile.

**Don't copy:** the second viewport meta sets `maximum-scale=1.0, user-scalable=no`, which blocks pinch-zoom (an accessibility failure). The popup on load also breaks Dewstick's "no load interstitials" rule.

---

## Part 2 — Dewstick design system (proposed; as built in /DESIGN.md)

**North Star: "The Morning Ritual."** We keep the Pharmacy Counter's discipline (square, flat, one red seal) and add Susanne Kaufmann's editorial calm: a warm paper ground, a serif voice for headings, asymmetric grid placement, and photography as the main colour. Dewstick stays "a luxury lip mask — in a stick": skincare first, colour as the bonus.

### Tokens

```css
:root {
  /* colour */
  --linen:  #f4efe9;  /* page ground (warmer than SK's #f2eeeb, toward our blush/tan assets) */
  --paper:  #ffffff;  /* cards, image wells */
  --ink:    #1e1a17;  /* text; warm near-black */
  --ink-60: color-mix(in srgb, var(--ink) 60%, transparent);  /* keep the Ink-is-Grey ramp */
  --line:   #e2dbd3;  /* hairlines */
  --dew:    #e94b3c;  /* the seal: wordmark, hover, ≤1 emphasis per section */

  /* type */
  --font-serif: "Newsreader", "Noto Serif SC", Georgia, serif;   /* Plantin-like stand-in for Martina Plantijn */
  --font-sans:  "Hanken Grotesk", "Noto Sans SC", system-ui, sans-serif;
  --text-3xl: 2rem;      --lh-3xl: 1.16;
  --text-2xl: 1.625rem;  --lh-2xl: 1.16;
  --text-xl:  1.125rem;  --lh-xl: 1.2;
  --text-lg:  .9375rem;  --lh-lg: 1.35;   /* body */
  --text-base:.875rem;   --lh-base: 1.3;
  --text-sm:  .75rem;    --tracking: .02em;

  /* grid */
  --cols: 6; --gap: 1.25rem;
  --col: calc((100% - var(--gap) * (var(--cols) - 1)) / var(--cols));
  --header-h: 2.75rem;

  /* motion */
  --ease-out-quart: cubic-bezier(.25,1,.5,1);
  --ease-out-circ:  cubic-bezier(0,.55,.45,1);
}
@media (min-width: 48rem) { :root { --cols: 12; --header-h: 4rem; } }
html { font-size: max(16px, calc(16px + 6.4 * (100vw - 1440px) / 1440)); }
```

Notes:
- **Fonts.** Newsreader (Google Fonts, opsz 6–72, weight 400) is the closest free Plantin-family serif. Fallback option: Source Serif 4. Hanken Grotesk stays as the sans, already loaded and close in feel to KaufmannSans. Chinese: Noto Serif SC 600 is already loaded for headings; it would need the 400 weight added. Noto Sans SC stays for body.
- **No bold anywhere** except the wordmark. Hierarchy comes from serif vs sans and from size.
- **Labels.** The uppercase-tracked style becomes a nav/footer-only treatment (13px, .08em), no longer the "connective tissue". Eyebrows go away.

### Layout rules
1. A 12-column grid with 1.25rem gutters, full width, no max-width container. The page grows through the fluid root instead.
2. Headings hang at column 1. Running copy starts at **column 4** (desktop) with a measure ≤ 45ch.
3. Every button is exactly **3 columns** wide on desktop and 4 of 6 on mobile.
4. Alternate a product section with a story/image section. Never put two product rows next to each other.
5. Sections sit `clamp(4rem, 8vw, 8.5rem)` apart. Use hairlines only for the USP strip and the footer.

---

## Part 3 — Decisions (all resolved 2026-10-23, see Part 0)

| # | Change | Live rule it breaks | Recommendation |
|---|---|---|---|
| D1 | Ground goes from white `#fff` to linen `#f4efe9` | Paper is the page ground | **Adopt.** It is most of what makes SK feel calm. |
| D2 | Serif headings and serif buttons | One-grotesk system; uppercase semibold headlines | **Adopt.** This is the biggest single shift in the brand's voice. |
| D3 | Light-filled buttons on photos, outline buttons elsewhere | Underline CTA dominant; filled button = form submit only | **Adopt** for hero/story only. Keep underline text-links for "Shop all". |
| D4 | Red philosophy band + espresso footer crescendo | SK ends quietly on linen | **Drop**, or keep the red band only. A linen footer fits the new direction. Owner call. |
| D5 | Wordmark | Bodoni Moda (current) vs lowercase red rounded (brand asset) | Keep the current wordmark. Don't copy SK's wide serif caps. |
| D6 | Hero copy font | Quicksand (current) | Replace with Hanken Grotesk. Quicksand's rounded forms push toward the Tutti anti-reference. |

---

## Part 4 — Dewstick page structure (homepage, proposal — as built in /DESIGN.md)

These map onto existing `shopify-theme/sections/*` where possible. ★ = new section.

| # | Section | SK pattern | Dewstick content | Theme file |
|---|---|---|---|---|
| 1 | Announcement | Dark bar | Pick something true, e.g. "five shades. one ritual." | `announcement-bar.liquid` |
| 2 | Header | Wordmark L / nav / text utilities R | Shop · The Set · Ingredients · About · EN/中文 · Cart | `header.liquid` |
| 3 | Hero | Full-bleed video, H1 top-left, copy + 2 buttons at col 3 | `dewstick-hero.mp4`. H1 *"A luxury lip mask — in a stick."* Buttons: **Shop the shades** (light) / **Why it works** (outline) | `hero.liquid` |
| 4 | USP strip | ✓ × 3 between hairlines | ✓ Squalane, shea & ceramides · ✓ Five shades · ✓ $22 CAD each. Claims must be true; no shipping promise unless policy confirms it | `formula-strip.liquid` (restyle) |
| 5 | Product row | "Build your ritual" 4-up | **"Find your shade"**: nectar, poppy, blush + The Set of Five. "Shop all" text-link | `shade-grid.liquid` (4-col variant) |
| 6 | Sticky gallery + text ★ | Sticky text, 2 offset images | **"The feel"**: one swipe, juicy glow. Image: store photo `dewstick-09` (asmr macro) | ★ `sticky-gallery.liquid` (replaces `the-feel`) |
| 7 | Story band | Full-bleed media, statement top-right | "Treatment first. Colour is the bonus." + **Our story** button | `image-with-text.liquid` → full-bleed variant, or `philosophy.liquid` |
| 8 | Shop by… tiles ★ | 4 portrait tiles + serif caption | Singles · The Set · Ingredients · Gift. Mix model, texture and packshot crops | ★ `featured-links.liquid` |
| 9 | Card scroller | White review cards | **No reviews.** Use the same card pattern for: founder note ("No reviews yet.") + ingredient cards (`shop/img/ing-*.jpg`) | `why-it-works.liquid` + `founder-note.liquid` → ★ `card-scroller.liquid` |
| 10 | Text + media | Insider: 3-zone asymmetric | Waitlist / newsletter (Klaviyo) with a tall lifestyle image | `image-with-text.liquid` (asymmetric variant) |
| 11 | Social tiles | 4 portrait journal tiles with overlay titles | @dewstick wall: 4 tiles, no invented handles or captions | `instagram-wall.liquid` (4-up, 39:53) |
| 12 | Footer | Hairline, serif col heads, uppercase links, switchers | Shop / Help / About / Follow + EN/中文 switcher + © | `footer.liquid` |

The current `all-shades` (5-up) and `the-set` sections move to the collection page or fold into #5.

---

## Part 5 — Component specs

**Button** (`.btn`)
```css
.btn { display:flex; align-items:center; width:var(--btn-w, calc(var(--col)*3 + var(--gap)*2));
  min-height:2.5rem; padding:0 .75rem; font:400 var(--text-lg)/1 var(--font-serif);
  border:1px solid transparent; border-radius:0; transition:background-color .15s, color .15s, border-color .15s; }
.btn--light   { background:var(--linen); color:var(--ink); }
.btn--light:hover { background:var(--paper); }
.btn--outline { border-color:currentColor; background:transparent; }
.btn--outline:hover { background:var(--ink); color:var(--linen); }      /* on photos: white→ink inverse */
.link-u { font-family:var(--font-serif); border-bottom:1px solid currentColor; padding-bottom:.15em; }
.link-u:hover { color:var(--dew); }                                      /* the seal */
```
Min tap height is 40px desktop and 44px mobile (`@media (pointer:coarse)`).

**Product card**: no box. A `3/4` well on `--paper`, packshot `object-fit:cover`. Badge stack top-left (`text-sm` sans uppercase: SET, SAVE $12). Below: serif `text-xl` shade name, sans price "$22 CAD", 2-line clamped descriptor. **No stars and no heart icon** (no reviews exist, no wishlist app). Hover: image `scale:1.01` over `.4s var(--ease-out-quart)`. Active: `translate:0 1px`.

**USP strip**: `border-block:1px solid var(--line)`, padding `1rem 0`, items `gap:3.5rem` centred, `✓` as a 10px SVG stroke. On mobile, stacked left with `gap:.75rem`.

**Sticky gallery**: text spans cols 1–3 with `position:sticky; top:calc(var(--header-h) + 2rem)`. Image A spans cols 5–8 at `4/5`, image B spans cols 9–11 at `3/4` with `margin-top:-3rem`. On mobile everything stacks and sticky is off.

**Card scroller**: `display:flex; gap:var(--gap); overflow-x:auto; scroll-snap-type:x mandatory; padding-inline-start:calc(var(--col)*3 + var(--gap)*3)` on desktop (starts at col 4). Cards are `--paper`, `width:min(85vw, 38rem)`, padding `1.5rem`, `scroll-snap-align:start`. Hide the scrollbar, but add ← → text buttons on desktop for keyboard users.

**Collection / social tile**: `3/4` (collections) or `39/53` (social) image, serif `text-base` caption under it. Social tiles may carry a white serif overlay title with a 20% bottom gradient scrim for contrast.

**Newsletter field**: bottom border only, serif placeholder, underlined text-button "Join" to the right. Error state is a `--dew` bottom border plus inline text (fixes the old "no inline error" gap).

**Header**: linen, sticky. Becomes `color-mix(linen 90%) + backdrop-filter: blur(24px)` after 8px of scroll. Nav and utilities are 13px uppercase sans with .08em tracking. On mobile: wordmark + 3 icons at 1.25px stroke. The menu opens as a full-height linen sheet with the nav in serif `text-2xl`.

---

## Part 6 — Implementation notes (for the Shopify port, still to do)

1. **Where:** `shopify-theme/` is the live theme. `scripts/build_shop.py` mirrors it to `dewstick.vercel.app`, so rebuild after theme changes. Do the work on a **duplicate theme** (unpublished) so the live MAIN theme is untouched until sign-off.
2. **Tokens first.** Put Part 2's `:root` block in the theme's base CSS, then restyle the existing sections against the tokens before adding new ones (order: header → hero → USP → product card → footer → new sections).
3. **Fonts:** add `Newsreader:opsz,wght@6..72,400;6..72,400i` and `Noto+Serif+SC:wght@400` to the Google Fonts link in `layout/theme.liquid`. Drop Quicksand if D6 is accepted. Use `display=swap` and preload the Newsreader 400 Latin subset.
4. **Fluid root:** the `html { font-size }` formula only takes effect above 1440px, so mobile is unaffected. Check that any `px` values in existing sections don't fight it.
5. **Video:** `playsinline muted loop preload="none"` + `poster`. Start with an IntersectionObserver and pause off-screen. Under `prefers-reduced-motion`, show the poster only.
6. **Motion budget:** only `slide-in` (5px, .6s, `--ease-out-circ`) on hero copy and the product-card hover/press. No scroll-reveal on every section; SK doesn't do it and it costs on mobile.
7. **Images:** Shopify `image_url` with `widths: '360,540,720,960,1440,1920'` plus `sizes` that match the column span (e.g. product card `sizes="(min-width:768px) 25vw, 50vw"`). Lazy-load everything below the hero. Set `aspect-ratio` on every well to avoid layout shift.
8. **Mobile art direction:** the hero needs its own portrait crop or video (`<source media="(max-width:767px)">`). SK uses a different composition on mobile, not a centred crop.
9. **Bilingual:** check that serif headings in 中文 (Noto Serif SC) keep line breaks sensible. Headings are 2–3 lines on mobile; set `text-wrap: balance`.
10. **Accessibility:** do **not** copy `user-scalable=no`. White hero text needs a scrim or a calm image area (SK's hero copy is low contrast). Keep focus-visible outlines at 2px (SK uses `outline:2px solid #000; outline-offset:4px`, worth copying). Scrollers need keyboard controls.
11. **Honesty constraints** (from PRODUCT.md) still apply: no star ratings, review quotes, "worth $X" claims, or customer counts. The reviews pattern is reused only for the founder note and ingredient cards.
12. **No load popup.** Newsletter capture stays inline (section 10 + footer).
