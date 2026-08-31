---
name: Dewstick
description: Bilingual waitlist landing page for a treatment-first tinted lip balm — editorial grid, hairline rules, one warm red.
colors:
  ink: "#1a1a1a"
  dew: "#e94b3c"
  paper: "#ffffff"
  mist: "#f5f4f2"
  line: "#e4e2dd"
typography:
  display:
    fontFamily: "\"Hanken Grotesk\", \"Noto Sans SC\", system-ui, -apple-system, \"Segoe UI\", Roboto, sans-serif"
    fontSize: "clamp(1.875rem, 5vw, 3rem)"
    fontWeight: 600
    lineHeight: 1.1
    letterSpacing: "-0.025em"
  headline:
    fontFamily: "\"Hanken Grotesk\", \"Noto Sans SC\", system-ui, -apple-system, \"Segoe UI\", Roboto, sans-serif"
    fontSize: "clamp(1.5rem, 4vw, 2.25rem)"
    fontWeight: 600
    lineHeight: 1.15
    letterSpacing: "-0.025em"
  title:
    fontFamily: "\"Hanken Grotesk\", \"Noto Sans SC\", system-ui, -apple-system, \"Segoe UI\", Roboto, sans-serif"
    fontSize: "clamp(1.125rem, 2vw, 1.25rem)"
    fontWeight: 400
    lineHeight: 1.625
    letterSpacing: "normal"
  body:
    fontFamily: "\"Hanken Grotesk\", \"Noto Sans SC\", system-ui, -apple-system, \"Segoe UI\", Roboto, sans-serif"
    fontSize: "clamp(0.875rem, 1.5vw, 1rem)"
    fontWeight: 400
    lineHeight: 1.625
    letterSpacing: "normal"
  label:
    fontFamily: "\"Hanken Grotesk\", \"Noto Sans SC\", system-ui, -apple-system, \"Segoe UI\", Roboto, sans-serif"
    fontSize: "11px"
    fontWeight: 500
    lineHeight: 1.3
    letterSpacing: "0.18em"
rounded:
  none: "0px"
spacing:
  grid-gap: "16px"
  page-gutter: "16px"
  page-gutter-lg: "32px"
  section-y: "56px"
  section-y-lg: "80px"
components:
  button-primary:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.paper}"
    typography: "{typography.label}"
    rounded: "{rounded.none}"
    padding: "12px 20px"
  button-primary-hover:
    backgroundColor: "{colors.dew}"
    textColor: "{colors.paper}"
  cta-underline:
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.none}"
    padding: "0 0 4px"
  cta-underline-hover:
    textColor: "{colors.dew}"
  input-email:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    typography: "{typography.body}"
    rounded: "{rounded.none}"
    padding: "12px 16px"
  nav-link:
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    padding: "0"
  nav-link-hover:
    textColor: "{colors.dew}"
---

# Design System: Dewstick

## Overview

**Creative North Star: "The Pharmacy Counter"**

Dewstick looks like the front counter of a good apothecary: bright, orderly, unhurried, and quietly authoritative. The product is a tinted lip balm, but the page treats it as a treatment first and a color second — so the design borrows the discipline of skincare packaging (generous white space, exact hairline rules, a single prescriptive red) rather than the candy energy of a lipstick brand. Everything is set on a wide editorial grid lifted from Typology's homepage: full-bleed photography alternating with tightly-captioned product rows, sections that each earn the next one, and a single unmissable job on every screen — leave an email.

The restraint is the brand. Type is mostly small: uppercase micro-labels, sentence-case body, and a handful of large semibold headings. There is exactly one accent color and it is used like a wax seal — the wordmark, every hover, one or two pieces of emphasis per section, and nothing else. Surfaces are flat. Depth comes from tone (a warm off-white panel against pure white) and from structure (a 1px divider, a full-bleed image), never from a shadow. The page is bilingual to its foundation: every string exists in English and Simplified Chinese, and neither is treated as the translation of the other.

**Visual anti-reference:** the playful, value-priced DTC lipstick look — joke-forward copy, candy-bright fills, sticker and script clutter, rounded bubbly containers — exemplified by tuttilipstick.com. Dewstick shares the product category and deliberately rejects that surface. Where that world is loud and cute, Dewstick is calm and exact.

**Key Characteristics:**
- Wide editorial grid (max 1400px), full-bleed image sections alternating with captioned product rows.
- Zero corner radius anywhere in the UI. Every edge is square.
- No shadows. Flat surfaces, tonal and structural depth only.
- One accent color (Dew Red), rationed to roughly 10% of any screen.
- Small type by default; uppercase micro-labels with wide tracking as the connective tissue.
- The dominant CTA is an underlined caps micro-label, not a filled button.
- English and 中文 are equal citizens, toggled and persisted.

## Colors

A near-neutral system — warm off-whites and near-black ink — activated by a single warm red.

### Primary
- **Dew Red** (`#e94b3c`): the only accent. Appears on the wordmark (always), every hover and focus transition, the mobile menu's "Join" link, one piece of per-section emphasis at most (e.g. "— save $12"), and the form error outline. Never used for large fills, never for body text, never two adjacent uses. A slightly coral-leaning red — warm, not fire-engine, not pink.

### Neutral
- **Ink** (`#1a1a1a`): all text, via an opacity ramp rather than separate greys — 100% for headings, 80% for pull-quote and note body, 70% for standard body, 60% for eyebrows, 50% for captions and footer meta, 40% for muted inline labels, 25% for separators. Also the solid fill of the announcement bar and the primary (form-submit) button, and the emphasis border on inputs and CTAs.
- **Paper** (`#ffffff`): the page ground. Also the sticky header at 95% opacity with a backdrop blur, and the hero copy card at 90%.
- **Mist** (`#f5f4f2`): a warm off-white. Fills every product image well, the alternating feature-block image panels, and section backgrounds that need to separate from Paper. The workhorse of tonal depth.
- **Line** (`#e4e2dd`): hairline dividers and card-free section borders only. One weight (1px), one job.

### Named Rules
**The One Seal Rule.** Dew Red is a stamp, not a system. It may occupy no more than ~10% of any viewport, and no two red elements may sit adjacent. If a screen needs red in two places to work, the layout is wrong, not the palette.

**The Ink-Is-Grey Rule.** There are no grey tokens. Every "grey" is `#1a1a1a` at a set opacity. Pick from the ramp (80 / 70 / 60 / 50 / 40 / 25); do not introduce a new neutral.

## Typography

**Display / Body Font:** Hanken Grotesk (400, 500, 600, 700), with Noto Sans SC (400, 500, 700) for Chinese, falling back to `system-ui, -apple-system, "Segoe UI", Roboto, sans-serif`.

**Character:** one grotesk does everything. Hanken Grotesk is a quiet, slightly humanist sans that stands in for Typology's Post Grotesk; Noto Sans SC is matched to it for weight and rhythm so a language switch never shifts the layout's texture. Base size is **15px**. The personality is in the *treatment*, not the typeface: wide-tracked uppercase micro-labels against calm sentence-case body.

### Hierarchy
- **Display** (600, `clamp(28px, 5vw, 45px)`, line-height 1.1, tracking -0.025em): reserved for the single largest statement on the page — the founder-note headline ("No reviews yet."). Sentence case.
- **Headline** (600, `clamp(24px, 4vw, 34px)`, line-height ~1.15, tracking -0.025em): section titles and the hero H1. Section titles are set **UPPERCASE**; the hero H1 is sentence case with `leading-tight`.
- **Title** (400, `clamp(17px, 2vw, 19px)`, line-height 1.625, Ink 80%): the centered philosophy statement. Larger than body, same weight — read as a held breath, not a heading.
- **Body** (400, `clamp(13px, 1.5vw, 15px)`, line-height 1.625, Ink 70%): all running copy. Constrain measure to ~40–48ch (`max-w-sm` / `max-w-md`) — this system never runs body text the full grid width.
- **Label** (500, `11px`, letter-spacing 0.18em, UPPERCASE, Ink 60–100%): nav, eyebrows, CTA text, captions under sensory tiles, footer column headers, price tags. The connective tissue of the whole page.

### Named Rules
**The Micro-Label Rule.** Anything that is not a heading or a sentence of body copy is an 11px uppercase label with 0.18em tracking. Nav, buttons, eyebrows, captions, section kickers — all the same treatment. Consistency here is what makes the sparse layout feel designed rather than empty.

**The One Big Thing Rule.** At most one Display-size element per section, and only one per page at the very top of the scale. Size is rationed like the accent color.

## Layout

A wide editorial grid. Containers are `max-width: 1400px`, centered, with `16px` gutters on mobile and `32px` from the `md` breakpoint (768px). The hero and lifestyle-break sections use wider inner gutters (`24px` → `64px`) because their content is a single floating card.

**Vertical rhythm:** sections are `56px` top/bottom on mobile, `80px` from `md`. Dividers between sections are either a full-bleed image or a single `1px` Line border — never both, never extra whitespace stacked on top of a border.

**Grid patterns**, in order of frequency: 3-up product row (`md:grid-cols-3`), 5-up shade grid (`grid-cols-2` → `md:grid-cols-5`), 4-up benefit/care row, and the 2-column feature block (image flush to one side as `position: absolute; inset: 0; object-fit: cover`, copy on the other with its own vertical padding and vertical centering). Grid gap is `12px` mobile / `16px` desktop throughout.

**Image wells:** packshots sit in a `bg-mist` box at `aspect-ratio: 4/5` (or `1/1` for benefit icons) with heavy internal padding (`56px`–`80px`) and `object-fit: contain`, so the product floats. Lifestyle and texture images use `object-fit: cover` and fill their box edge to edge.

**Header:** sticky, `56px` tall, `#ffffff` at 95% with `backdrop-filter: blur`, `1px` Line bottom border. Three-part: left nav (desktop) / hamburger (mobile), centered wordmark, right-side language toggle + waitlist link.

**Mobile:** single column, one thumb, no pinch-zoom. Nav collapses to a hamburger that opens a bordered dropdown panel (`13px` links, `12px` vertical rhythm). The email input and its button must be reachable in the hero without scrolling on a standard phone.

## Elevation & Depth

**Flat, permanently.** There is no `box-shadow` anywhere in the system, and there is no intention to add one. Depth is communicated two ways only:

1. **Tonal** — a `#f5f4f2` (Mist) panel sitting on the `#ffffff` (Paper) ground. The 3% warmth difference is the entire elevation vocabulary for panels and wells.
2. **Structural** — a `1px` `#e4e2dd` (Line) border, or a full-bleed image butting against a text section.

The only compositing effects in use are `backdrop-filter: blur` (sticky header, hero copy card over photography) and low-opacity black image overlays (`rgba(0,0,0,0.1)` on the hero, `0.3` on the lifestyle break) to hold white text. `text-shadow` (a soft `drop-shadow`) is permitted **only** on white text placed over a photograph, for legibility — never on text over a solid color.

### Named Rules
**The Flat-By-Default Rule.** Surfaces are flat at rest and flat on interaction. A hover changes color, a border, or position — never adds a shadow. If a card needs to "lift," it moves or its border darkens; it does not cast.

## Shapes

**Square, everywhere.** Corner radius is `0` on every UI element: buttons, inputs, image wells, cards, dropdown panels, the announcement bar. The only rounded corner in the entire project is the favicon's container, which is not UI. This is the single most load-bearing shape decision in the system — it is what separates Dewstick from the rounded, bubbly containers of the anti-reference.

**Borders:** one hairline weight (`1px`). `#e4e2dd` (Line) for neutral dividers and structure; `#1a1a1a` (Ink) when a border needs to read as deliberate emphasis — the footer email field, the founder-note card, CTA underlines. Never a border wider than 1px except the transient `2px` Dew Red error outline on a form row.

**Underlines as structure:** links and CTAs use a `border-bottom` on the text itself (`padding-bottom: 4px`), not `text-decoration`. This gives the caps micro-label a precise baseline rule that shifts to Dew Red on hover.

### Named Rules
**The No-Radius Rule.** Every corner is 90°. There is no `border-radius` token above `0`, and none is to be introduced. Softness in this brand comes from photography and language, never from geometry.

## Components

### Buttons
Two button expressions, and they are not interchangeable.

- **Underline CTA (primary, dominant).** An 11px uppercase label (0.18em tracking) with a `1px` bottom border and `4px` of padding below the text. Ink text and Ink border at rest; both shift to Dew Red on hover via a `color`/`border-color` transition (~150ms). This is the correct CTA for *every* navigational and in-content call to action — "Join the waitlist →", "See all", "The idea". No background, no box.
- **Filled submit button (reserved).** Solid `#1a1a1a` background, `#ffffff` label (same 11px uppercase treatment), `~12px 20px` padding, zero radius. Background shifts to Dew Red on hover. **Used only as the submit control inside an email capture form**, sitting flush against the input in a shared row. It never appears on its own as a page CTA.

### Inputs / Fields
- **Style:** transparent background, `12px 16px` padding, 13px text, `outline: none`, zero radius. The field carries no border of its own — the *containing row* provides the edge: a `#ffffff` fill when the form sits over a photograph, or a `1px` Ink border when it sits on Paper (the footer).
- **Layout:** input and submit button share one horizontal row with no gap; the row is the visual object.
- **Error:** JavaScript applies a `2px solid #e94b3c` outline to the row and refocuses the input. There is no inline error text.
- **Focus:** *(known gap — the incumbent removes the focus ring with `outline: none` and adds nothing back. New work should restore a visible focus state, e.g. a `2px` Ink outline offset from the row.)*

### Navigation
- **Desktop:** three-column header — left link cluster, centered Dew Red wordmark, right-side controls. Links are 11px uppercase labels (0.18em tracking), Ink, shifting to Dew Red on hover.
- **Wordmark:** `dewstick`, lowercase, Hanken Grotesk 700, `tracking-tight`, always Dew Red. The one permanent instance of the accent.
- **Mobile:** hamburger icon (1.6px stroke, square line caps) toggles a full-width dropdown below the header — `1px` Line top border, 13px links, `12px` vertical spacing, the waitlist link in Dew Red.

### Language Toggle
`EN / 中文`, two text buttons split by a low-contrast slash (Ink 25%). The active language is bold Ink (`aria-pressed="true"`); the inactive is Ink 40%. Appears in both the header and the footer. Toggling sets `html[data-lang]`, updates `<title>` and `<html lang>` (`zh-Hans` for Chinese), swaps input placeholders, and persists to `localStorage`.

### Product Card
No container, no border, no shadow. A `bg-mist` image well (`aspect-ratio: 4/5`, product floated with `object-fit: contain` and heavy padding) with a caption stack beneath it: shade name in `font-medium` (500), translated color descriptor in Ink 40–50%, price as an 11px label. The `.group` class is present on the article for a hover affordance that is currently unstyled — any hover added here must obey the Flat-By-Default Rule (color or transform, not shadow).

### Feature Block
A 2-column section (`md:grid-cols-2`): one column is a full-bleed `bg-mist` image (`position: absolute; inset: 0; object-fit: cover`, `min-height` ~320–360px), the other is a copy column with `56px`–`80px` vertical padding, vertically centered, holding an uppercase headline, a short `max-w-sm` body paragraph, a price line, and an Underline CTA.

## Do's and Don'ts

### Do:
- **Do** keep every corner at `0` radius. Square is the brand.
- **Do** treat Dew Red as a seal: wordmark, hovers, and ≤1 emphasis per section. Keep it under ~10% of any screen.
- **Do** build "grey" from `#1a1a1a` at a fixed opacity from the ramp (80 / 70 / 60 / 50 / 40 / 25).
- **Do** make every non-heading, non-sentence element an 11px uppercase label at 0.18em tracking.
- **Do** convey depth with a Mist panel on Paper, or a 1px Line border — one or the other, never stacked.
- **Do** use the Underline CTA for every call to action except a form's own submit button.
- **Do** ship every new string in English and 中文, and check that a language switch does not reflow the layout.
- **Do** constrain body copy to a `max-w-sm`/`max-w-md` measure; never run it the full grid width.
- **Do** keep the hero email field and button visible without scrolling on a standard phone.

### Don't:
- **Don't** add a `box-shadow` to anything, at rest or on hover.
- **Don't** introduce a second accent color, a gradient, or a tinted fill beyond Mist.
- **Don't** use a filled button as a standalone page CTA — that treatment belongs to form submit only.
- **Don't** add rounded corners, sticker/script callouts, or candy-bright fills — that is the tuttilipstick.com anti-reference, not Dewstick.
- **Don't** create new grey tokens or a `border-radius` token above `0`.
- **Don't** widen a border past `1px` (the transient `2px` red error outline is the sole exception).
- **Don't** fabricate reviews, ratings, or customer counts to fill the social-proof slot — the honest "No reviews yet." founder note is deliberate.
- **Don't** let Chinese read as a translation afterthought: match line-length and emphasis to the English, not word-for-word.
