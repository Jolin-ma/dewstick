# dewstick on WordPress + WooCommerce

The dewstick store as a classic WordPress theme on WooCommerce — the third build of the same store, next to the
static copy (Vercel) and the Shopify theme. Same design (`../DESIGN.md`), same copy (the Shopify theme's language
files), same catalog, English / 中文.

## Live demo (no server)

[Open the store in WordPress Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/Jolin-ma/dewstick/main/wordpress/playground.json)
— `playground.json` boots WordPress, WooCommerce and this theme (pulled from GitHub) in the visitor's browser and
seeds the store. Takes ~30 s to load; each visitor gets a fresh store, nothing is saved. The portfolio links the
same blueprint base64-encoded in the URL (`https://playground.wordpress.net/#<base64 JSON>`), because some networks
block raw.githubusercontent.com — regenerate that link if you change `playground.json`. It installs the theme from
`main` on GitHub, so push theme changes before they show up there.

`playground-builders.json` is the same plus Elementor and Kadence Blocks, landing on the page-builder pages below
(slower to boot, ~1 min). The portfolio links it the same way, once per page (`landingPage` `/ingredients/` or
`/the-idea/`).

## Page-builder pages

Two pages are rebuilt in page builders next to the hand-written PHP templates, inside the same theme header and footer:

| Page | Built with | How it's made |
|---|---|---|
| `/ingredients/` | **Elementor** (Flexbox containers, Heading / Text Editor / Image / Button widgets) | `dewstick_seed_elementor_page()` in `theme/inc/builders.php` writes the Elementor document through Elementor's own API. Brand colours and type are set once as **Site Settings → Global Colors / Global Fonts** and every widget points at them. Template: *Elementor Full Width* |
| `/the-idea/` | **Kadence Blocks** (Row Layout, Advanced Heading, Image, Advanced Button) in the block editor | Laid out in the block editor, saved, and its markup exported to `theme/inc/kadence-the-idea.html`; `dewstick_seed_kadence_page()` recreates it, filling in image ids/URLs and links (`{{img:…}}`, `{{id:…}}`, `{{home}}`). Template: *Full width (page builder)* (`template-builder.php`) |

Both open in their builder for editing (Edit with Elementor / the block editor), and the seed never overwrites a page
that already exists. The theme registers the brand palette and editor styles, so Kadence's colour pickers and the
block editor show the store's colours and fonts. Builder content is English only — it sits outside the theme's
`lang/*.json` switch (a multilingual plugin such as Polylang or WPML would be the next step).

To change the Kadence page and keep it reproducible: edit it in the block editor, then re-export its `post_content`
to `kadence-the-idea.html` with the image ids, image URLs and internal links swapped back for the placeholders.

## Run it

```bash
cd wordpress
npm install    # once — installs WordPress Playground's CLI
npm start      # http://127.0.0.1:9400, logged in as admin
```

On Windows, `open-wordpress.ps1` does both in one step: it starts the server if it isn't running, waits
until WordPress answers and opens the browser. Point a desktop shortcut at it (`wordpress.ico` is its icon);
closing the minimized "dewstick WordPress" window stops the server.

No PHP, MySQL or Docker needed: [WordPress Playground](https://wordpress.github.io/wordpress-playground/) runs
WordPress in Node with SQLite.

- **First run** downloads WordPress into `site/` and WooCommerce, Elementor and Kadence Blocks into its plugins
  (`blueprint.json`), activates the theme and runs `dewstick_seed()` (`theme/inc/seed.php`), which sets up the store.
  On Windows, Playground can't move a plugin into the mounted `site/` once it exists; if a plugin is missing, unzip it
  from `https://downloads.wordpress.org/plugin/<slug>.latest-stable.zip` into `site/wp-content/plugins/`.
- **Later runs** reuse `site/`, so products, orders and settings persist. `site/` is gitignored — delete it to start over.
- `theme/` is mounted live into `wp-content/themes/dewstick`: edit and reload.
- Admin: http://127.0.0.1:9400/wp-admin (Playground logs you in automatically).

## What's in the store

`dewstick_seed()` is safe to rerun; it skips whatever already exists.

- **Catalog** — *dewstick tinted lip balm*, a variable product with the five shades as variations ($22 CAD each,
  per-shade photo), and *the set of five* ($98 CAD, was $110). Category `shades`.
- **URLs** — `/collections/shades/`, `/products/<handle>/`, `/cart/`, `/checkout/`, matching the other two builds.
  A shade is preselected with `?attribute_shade=poppy`.
- **Checkout** — real WooCommerce checkout. Payment is *Test payment (no charge)* (WooCommerce's cash-on-delivery
  gateway, renamed), shipping is free, taxes are off. Orders land in WooCommerce → Orders.
- **Newsletter** — signups are saved and listed under Tools → Newsletter.

## Theme (`theme/`)

| | |
|---|---|
| `front-page.php` | Homepage — the same sections as the static site |
| `woocommerce/archive-product.php` | Shop + collection page |
| `woocommerce/single-product.php` | Product page; the form posts to WooCommerce's add-to-cart handler |
| `woocommerce/cart/*.php` | Cart, in the static site's layout |
| `page.php` | Checkout, account and other pages |
| `template-builder.php` | *Full width (page builder)*: header + blocks + footer, for the Kadence page |
| `inc/builders.php` | The Elementor and Kadence pages (see above) |
| `inc/i18n.php` | EN / 中文: `?lang=zh` switches, a cookie remembers it. Strings come from `lang/*.json` (copied from `shopify-theme/locales/`) |
| `inc/template.php` | Helpers shared by the templates: images, swatches, product cards |
| `inc/seed.php` | Store setup and catalog |
| `assets/css/dewstick.css` | `shop/dewstick.css`, unchanged — change both together |
| `assets/css/wp.css` | WordPress and WooCommerce markup in the same design (notices, checkout) |
| `assets/css/editor.css` | Block editor styles (ground and fonts) |
| `assets/js/dewstick.js` | `shop/site.js` without the localStorage cart (WooCommerce keeps the cart) |
| `assets/img`, `assets/video` | Copies of `shop/img` and `shop/video` |

**Known gap:** in 中文, WooCommerce's own checkout text (field labels, order summary) stays in English. The theme
translates everything it renders itself; translating checkout would need WooCommerce's zh_CN language pack plus a
multilingual plugin such as Polylang.
