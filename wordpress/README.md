# dewstick on WordPress + WooCommerce

The dewstick store as a classic WordPress theme on WooCommerce — the third build of the same store, next to the
static copy (Vercel) and the Shopify theme. Same design (`../DESIGN.md`), same copy (the Shopify theme's language
files), same catalog, English / 中文.

## Run it

```bash
cd wordpress
npm install    # once — installs WordPress Playground's CLI
npm start      # http://127.0.0.1:9400, logged in as admin
```

No PHP, MySQL or Docker needed: [WordPress Playground](https://wordpress.github.io/wordpress-playground/) runs
WordPress in Node with SQLite.

- **First run** downloads WordPress into `site/` and WooCommerce into its plugins (`blueprint.json`), activates the
  theme and runs `dewstick_seed()` (`theme/inc/seed.php`), which sets up the store.
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
| `inc/i18n.php` | EN / 中文: `?lang=zh` switches, a cookie remembers it. Strings come from `lang/*.json` (copied from `shopify-theme/locales/`) |
| `inc/template.php` | Helpers shared by the templates: images, swatches, product cards |
| `inc/seed.php` | Store setup and catalog |
| `assets/css/dewstick.css` | `shop/dewstick.css`, unchanged — change both together |
| `assets/css/wp.css` | WordPress and WooCommerce markup in the same design (notices, checkout) |
| `assets/js/dewstick.js` | `shop/site.js` without the localStorage cart (WooCommerce keeps the cart) |
| `assets/img`, `assets/video` | Copies of `shop/img` and `shop/video` |

**Known gap:** in 中文, WooCommerce's own checkout text (field labels, order summary) stays in English. The theme
translates everything it renders itself; translating checkout would need WooCommerce's zh_CN language pack plus a
multilingual plugin such as Polylang.
