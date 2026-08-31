# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

**Primary visitor:** someone who already spends on skincare-adjacent beauty — lip masks, sleeping masks, treatment balms in the roughly $18–35 range — and would consider a tinted version if it did not feel like a compromise on either care or color. They arrive on a phone, usually from an Instagram or TikTok post, and have never heard of Dewstick before the moment they land. The page has to explain the whole idea within one screen of scrolling before asking for anything.

**Secondary audience:** Simplified-Chinese readers. Every string on the site ships in English and 中文, switchable and persisted; this is a first-class requirement, not a localization afterthought.

**The person running the case study** (the site's true audience in practice): this is a portfolio artifact. A reviewer of the work should be able to see the whole e-commerce coordination workflow — product sourcing, storefront, funnel, results — reflected in how the site and its supporting decisions are built.

## Product Purpose

Dewstick is a **fictional product** used to run a real, self-directed case study in end-to-end e-commerce coordination. The workflow the project demonstrates, in order:

1. Scan live marketplaces for a viable product to sell.
2. Stand up the website / store.
3. Design the content-to-waitlist funnel.
4. Read the results — conversion by traffic source, etc.

This repository is steps 2–3: the site and the front of the funnel. It is **not** a store and not an inventory-backed launch. The site is a single waitlist landing page whose only job is to capture an email address. Success is measured one way: of everyone who lands from a given traffic source, what percentage leaves an email. Warm social traffic in this category typically converts in the 15–30% range; that is the rough bar.

Because the product is fictional, invented specifics (pricing, shade names) are legitimate as long as they stay plausible and internally consistent — but the *funnel logic, the analytics story, and the "how would this actually run" details matter as much as visual polish.*

## Positioning

**Confirmed lane: luxury lip treatment first, color is the bonus.** Dewstick is "a lip mask that happens to have color" — skincare-first, warm, and a little more elevated than the playful, value-priced space occupied by the close analog tuttilipstick.com. Tutti sells nearly the same idea (lipstick that acts like a balm), the same ingredient trio, a similar cloud/blob packaging pattern, and a similar bold rounded wordmark. Dewstick deliberately does **not** sit in that lane: the differentiation a neighbor could not truthfully copy is the "luxury lip mask, in a stick" treatment framing — squalane, shea butter, and ceramides doing the work of a $30+ lip mask, with real color layered over it, positioned and art-directed as skincare rather than as a novelty lipstick.

Voice follows from this: warm and simple, not joke-forward. Pull back from full joke-voice; a skincare brand that happens to be fun, not a bit.

## Operating Context

- **Arrival:** almost entirely mobile, one-handed, one thumb, from a social post. No pinch-zoom should ever be required. The email input and its button must be usable without scrolling in the hero on a standard phone.
- **Decision window:** ~15 seconds. The visitor is cold and the page competes with the app they came from.
- **Traffic is instrumented by source.** The reason the site exists is to compare conversion across traffic sources, so UTM capture on the capture event is part of the product, not an enhancement.
- **Reference layout:** the site recreates the [Typology](https://typology.com) homepage structure (announcement bar → full-bleed hero → formula strip → product grids → lifestyle break → ingredient grid → captioned tiles → founder note → philosophy → footer), adapted for a waitlist rather than a store. Two deliberate departures from a literal copy: the lifestyle-break CTA is a real email capture, and the reviews block is an honest "No reviews yet." + founder note.

## Capabilities and Constraints

- **Single self-contained `index.html`** — Tailwind via CDN, inline `<style>` and `<script>`, no build step, no framework, no backend. Deploy is the repo root on Vercel as static files (`vercel.json` sets clean URLs and image caching). Keep it single-file unless a change genuinely cannot be done that way.
- **Bilingual EN / 中文** across every string, switched by an `EN / 中文` toggle: pure-CSS `html[data-lang]` mechanism, persisted to `localStorage` key `dewstick_lang`, updates `<title>`, `<html lang>`, and input placeholders. A head init script prevents FOUC. Any new copy must ship in both languages.
- **Fonts:** Hanken Grotesk (Latin) + Noto Sans SC (Chinese), 15px base.
- **Imagery is placeholder.** All `<img>` slots use `placehold.co`. Real transparent product PNGs drop into the same slots. Existing brand asset set (12 images in the owner's Pictures/dewstick folder) is finished-looking and should be used directly rather than re-invented: bold rounded bubbly red wordmark, white cloud/blob pattern over five candy colorways, cream / blush / powder-blue / warm-tan backgrounds, occasional script callouts.
- **Product form:** twist-up bullet tube ("tinted lip balm"), 4g / 0.14 oz, sold as singles or a five-pack gift box ("happy shades happy days").
- **Confirmed pricing (canonical):** single **$22 CAD**, the set of five **$98 CAD** ("save $12" vs. singles). Site renders the literal strings "$22 CAD" / "$98 CAD".
- **Confirmed shade names (canonical):** nectar (coral), poppy (true red), peony (rosy nude), blush (soft pink), orchid (berry rose). Hero trio on the site: nectar, poppy, blush.
- **Waitlist capture:** intended integration is **Klaviyo free tier** (free to 250 contacts, real double opt-in, DTC-standard so no later migration). Currently `submitToWaitlist()` is a stubbed 400ms resolve — it must be pointed at Klaviyo and paired with UTM capture. A Google Form + Sheet is the only acceptable fallback and only if zero cost outweighs everything during early testing.
- **Not yet wired:** real Klaviyo POST + UTM capture; real Instagram / TikTok URLs (footer links are `#`); the "read the results" analytics layer (per-source landing → email conversion).
- **Domain:** dewstick.com ownership/availability unconfirmed as of the brief.

## Brand Commitments

- **Name:** dewstick (lowercase), bold rounded red wordmark.
- **Existing lines to reuse verbatim or near-verbatim:** "half lipstick. half lip balm." · "all the care. all the color." · "one swipe. juicy glow." · "a luxury lip mask — in a stick." · "100% you" · descriptor tags: juicy, glossy, balmy · "asmr — the sound. the texture. the dew." · checklist: hydrating, tinted, effortless.
- **Ingredient story (fixed):** squalane (deep hydration), shea butter (soft & smooth), ceramides (strengthen & protect); framed as the benefit list hydrate / nourish / protect / glow.
- **Voice:** warm, simple, skincare-adjacent. Not joke-forward.
- **CTA wording:** "join the waitlist" — never "submit" or "sign up".
- **Privacy reassurance line:** "we'll only email you about the Dewstick launch. no spam, unsubscribe anytime."
- **Post-signup confirmation:** "you're in. first pick of shades, first to know when it's real."

## Evidence on Hand

- **Real:** the finished brand asset set (wordmark, five packaging colorways, ingredient icon sets, one posed model shot, one unboxing shot, one ASMR/texture macro still) — in the owner's Pictures/dewstick folder, not in this repo. The master build brief (`docs/dewstick_master_build_brief.md`) and layout reference screenshots (`docs/reference/`).
- **Does not exist — future work must not fabricate it:** customer reviews, star ratings, testimonials, customer/waitlist counts, organic UGC photography, any texture/ASMR video or GIF, a standalone logo file separate from packshots, real waitlist conversion data. The reviews section is deliberately "No reviews yet." for this reason (brief §6).

## Product Principles

1. **One job: the email.** Every section earns the right to the next one and everything is judged against "does this help someone hand over their email in the next 15 seconds on their phone."
2. **Treatment first, color is the bonus.** When a choice trades skincare credibility against lipstick-novelty charm, credibility wins. This is the whole reason Dewstick is not just another Tutti.
3. **The funnel is the deliverable.** Capture instrumentation, traffic-source attribution, and a believable "how this runs" story are core product, not polish to add later.
4. **Honest where proof is missing.** No fabricated reviews, counts, or UGC. The waitlist framing ("we're still finding out who wants this") is turned into a strength, not hidden.
5. **Bilingual as a first-class constraint.** English and 中文 are equal; neither is a bolt-on.
6. **Stay lean.** Single file, no build, mobile-first, compressed imagery, no load interstitials, email-only form.

## Accessibility & Inclusion

- Mobile: usable one-handed, one thumb, no pinch-zoom anywhere; tap targets large enough for a thumb, no fine-print links doubling as buttons.
- `prefers-reduced-motion` is respected (smooth scroll disabled).
- Language toggle updates `<html lang>` for assistive tech; Chinese uses `zh-Hans`.
- No product-specific WCAG level has been formally set; treat WCAG 2.1 AA contrast and focus-visibility as the working standard.
