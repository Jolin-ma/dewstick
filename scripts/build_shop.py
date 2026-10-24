"""Build the static Vercel copy of the Shopify store.

Renders the same pages as the Shopify theme in shopify-theme/ (home, collection, both
products, cart, 404) in English and 中文, using the theme's own CSS and language files.
The cart lives in localStorage; checkout explains that the real one runs on Shopify.

    python scripts/build_shop.py
"""
import html
import json
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
THEME = ROOT / "shopify-theme"
SHOPIFY_URL = "https://dewstick.myshopify.com"
GITHUB_URL = "https://github.com/Jolin-ma/dewstick"
LANGS = {"en": "", "zh": "/zh"}  # url prefix per language

LOCALE = {
    "en": json.loads((THEME / "locales/en.default.json").read_text(encoding="utf-8")),
    "zh": json.loads((THEME / "locales/zh-CN.json").read_text(encoding="utf-8")),
}
# Strings the Shopify theme gets from Shopify itself (or that only the static copy needs).
EXTRA = {
    "en": {
        "collection.title": "all five shades",
        "collection.desc": "Every shade $22 CAD. One formula, every mood.",
        "footer.project": "This project",
        "footer.store": "Live Shopify store",
        "footer.source": "Source on GitHub",
        "footer.copy_note": "A static copy of the Shopify store, for the portfolio.",
        "demo.title": "This is the portfolio copy",
        "demo.body": "This site mirrors the dewstick Shopify store so anyone can browse it. The real checkout — "
                     "Shopify's checkout in test mode, so no money moves — runs on the Shopify store, which is "
                     "password-protected while it's a development store.",
        "demo.cta": "Go to the Shopify store →",
        "demo.newsletter": "Demo copy — signups are collected on the Shopify store, not here.",
        "newsletter.invalid": "Please enter a valid email.",
        "product.added": "Added to cart",
    },
    "zh": {
        "collection.title": "全部五色",
        "collection.desc": "每支 $22 加元。同一配方，适配每一种心情。",
        "footer.project": "关于本项目",
        "footer.store": "Shopify 线上店铺",
        "footer.source": "GitHub 源代码",
        "footer.copy_note": "Shopify 店铺的静态副本，用于作品集展示。",
        "demo.title": "这是作品集副本",
        "demo.body": "本网站是 dewstick Shopify 店铺的副本，方便所有人浏览。真正的结账流程（Shopify 测试模式，不会产生真实付款）"
                     "在 Shopify 店铺中进行；开发阶段的店铺需要密码访问。",
        "demo.cta": "前往 Shopify 店铺 →",
        "demo.newsletter": "演示副本 —— 订阅在 Shopify 店铺中收集，此处不保存。",
        "newsletter.invalid": "请输入有效的邮箱地址。",
        "product.added": "已加入购物袋",
    },
}

ALT = {
    "01": "dewstick in poppy — red tube beside its box, with squalane, shea butter and ceramides",
    "02": "dewstick in peony — pink tube over a swatch: half lipstick, half lip balm",
    "03": "dewstick in orchid — blue tube beside squalane, shea butter and ceramide swatches",
    "04": "The set of five in its red gift box: happy shades, happy days",
    "05": "dewstick in blush — green tube beside squalane, shea butter and ceramide swatches",
    "06": "A red dewstick over balm swatches: juicy, glossy, balmy",
    "07": "Get ready with me: applying dewstick — hydrating, tinted, effortless",
    "08": "Unboxing a dewstick from its shipping box",
    "09": "Macro of a dewy red bullet: the sound, the texture, the dew",
    "10": "A pile of dewsticks in every colourway",
    "11": "All five dewstick shades lined up with their swatches",
    "12": "Close-up of glossy tinted lips with a dewstick",
    "nectar": "dewstick in nectar — yellow tube with a red bullet",
    "poppy": "dewstick in poppy — red tube with a red bullet",
    "peony": "dewstick in peony — pink tube with a berry bullet",
    "blush": "dewstick in blush — green tube with a lime bullet",
    "orchid": "dewstick in orchid — blue tube with a blue bullet",
    "13": "dewstick in nectar — a luxury lip mask in a stick: hydrate, nourish, protect, glow",
}

SWATCH = {"nectar": "#f3b52f", "poppy": "#e2392f", "peony": "#f4a9bd", "blush": "#b6c935", "orchid": "#8dbde6"}

PRODUCTS = {
    "dewstick-tinted-lip-balm": {
        "title": {"en": "dewstick tinted lip balm", "zh": "dewstick 有色润唇膏"},
        "type": {"en": "Tinted lip balm", "zh": "有色润唇膏"},
        "price": 22, "compare": None,
        "media": ["nectar", "poppy", "peony", "blush", "orchid", "11", "06", "12"],
        "variants": {"nectar": "nectar", "poppy": "poppy", "peony": "peony", "blush": "blush", "orchid": "orchid"},
        "desc": {
            "en": "<p><strong>half lipstick. half lip balm.</strong></p><p>A luxury lip mask, in a stick — real colour over squalane, shea butter and ceramides.</p><ul><li><strong>Hydrate</strong> — squalane for deep hydration</li><li><strong>Nourish</strong> — shea butter, soft &amp; smooth</li><li><strong>Protect</strong> — ceramides to strengthen &amp; protect</li><li><strong>Glow</strong> — one swipe, juicy glow</li></ul><p>Twist-up bullet, 4 g / 0.14 oz.</p>",
            "zh": "<p><strong>半支口红，半支润唇膏。</strong></p><p>一支棒状的奢华唇膜 —— 真实色彩，融合角鲨烷、乳木果油与神经酰胺。</p><ul><li><strong>保湿</strong> —— 角鲨烷深层保湿</li><li><strong>滋养</strong> —— 乳木果油，柔滑顺润</li><li><strong>修护</strong> —— 神经酰胺强韧修护</li><li><strong>光泽</strong> —— 轻轻一抹，水润光泽</li></ul><p>旋转式唇膏管，4 克 / 0.14 盎司。</p>",
        },
    },
    "the-set-of-five": {
        "title": {"en": "the set of five", "zh": "五色套装"},
        "type": {"en": "Gift set", "zh": "礼盒套装"},
        "price": 98, "compare": 110,
        "media": ["04", "08", "10"],
        "variants": {},
        "desc": {
            "en": "<p><strong>All five shades in one box</strong> — one formula, every mood. Happy shades, happy days.</p><p>nectar (coral) · poppy (true red) · peony (rosy nude) · blush (soft pink) · orchid (berry rose).</p><p>$98 CAD — save $12 vs. singles.</p>",
            "zh": "<p><strong>五个色号，一盒收齐</strong> —— 同一配方，适配每一种心情。快乐色号，快乐每一天。</p><p>nectar（珊瑚色）· poppy（正红色）· peony（玫瑰裸色）· blush（柔粉色）· orchid（莓果玫瑰）。</p><p>$98 加元 —— 比单支省 $12。</p>",
        },
    },
}
BALM, SET = PRODUCTS["dewstick-tinted-lip-balm"], PRODUCTS["the-set-of-five"]


# ---------- helpers ----------
def t(lang, key, raw=False, **kw):
    if key in EXTRA[lang]:
        s = EXTRA[lang][key]
    else:
        s = LOCALE[lang]
        for part in key.split("."):
            s = s[part]
    for k, v in kw.items():
        s = s.replace("{{ %s }}" % k, str(v))
    return s if raw or key.endswith("_html") else html.escape(s, quote=False)


def money(n):
    return f"${n} CAD"


def well(num, sizes="(min-width: 768px) 50vw, 100vw", loading="lazy", cls=""):
    return (f'<div class="well well--square {cls}"><img src="/shop/img/dewstick-{num}-1000.jpg" '
            f'srcset="/shop/img/dewstick-{num}-500.jpg 500w, /shop/img/dewstick-{num}-1000.jpg 1000w" '
            f'sizes="{sizes}" loading="{loading}" width="1000" height="1000" alt="{html.escape(ALT[num])}"></div>')


def swatch(shade):
    return f'<span class="swatch-dot" style="background: {SWATCH.get(shade, "#e4e2dd")}" aria-hidden="true"></span>'


def catalog(lang):
    """Everything the cart needs to render a line, keyed the way site.js stores it."""
    p = LANGS[lang]
    items = {}
    for shade, num in BALM["variants"].items():
        items[f"dewstick-tinted-lip-balm:{shade}"] = {
            "title": BALM["title"][lang], "variant": shade, "swatch": SWATCH[shade], "price": BALM["price"],
            "img": f"/shop/img/dewstick-{num}-500.jpg", "url": f"{p}/products/dewstick-tinted-lip-balm?variant={shade}"}
    items["the-set-of-five"] = {
        "title": SET["title"][lang], "variant": None, "swatch": None, "price": SET["price"],
        "img": "/shop/img/dewstick-04-500.jpg", "url": f"{p}/products/the-set-of-five"}
    strings = {k: t(lang, k, raw=True) for k in
               ["cart.empty", "cart.continue", "cart.remove", "product.qty_up", "product.qty_down", "product.quantity",
                "product.added", "newsletter.invalid", "demo.newsletter"]}
    strings["cart_count_one"] = t(lang, "general.cart_count.one", raw=True)
    strings["cart_count_other"] = t(lang, "general.cart_count.other", raw=True)
    return {"lang": lang, "prefix": p, "currency": "CAD", "shop": f"{p}/collections/shades",
            "items": items, "strings": strings}


# ---------- layout ----------
def page(lang, path, title, body, description=None):
    p = LANGS[lang]
    other = "zh" if lang == "en" else "en"
    alt_href = {l: (LANGS[l] + path) or "/" for l in LANGS}
    desc = description or t(lang, "hero.body", raw=True)

    def lang_switch():
        out = []
        for i, (l, label) in enumerate([("en", "EN"), ("zh", "中文")]):
            if i:
                out.append('<span class="sep" aria-hidden="true">/</span>')
            cur = "true" if l == lang else "false"
            out.append(f'<a href="{alt_href[l]}" lang="{"zh-CN" if l == "zh" else "en"}" aria-current="{cur}" data-lang-link>{label}</a>')
        return '<div class="lang-switch u-mini">' + "".join(out) + "</div>"

    shop = f"{p}/collections/shades"
    home = p or "/"
    return f"""<!doctype html>
<html lang="{'zh-CN' if lang == 'zh' else 'en'}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#f4efe9">
  <title>{html.escape(title)}</title>
  <meta name="description" content="{html.escape(desc)}">
  <link rel="alternate" hreflang="en" href="{alt_href['en']}">
  <link rel="alternate" hreflang="zh-CN" href="{alt_href['zh']}">
  <meta property="og:site_name" content="dewstick">
  <meta property="og:title" content="{html.escape(title)}">
  <meta property="og:description" content="{html.escape(desc)}">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://dewstick.vercel.app/shop/img/og-hero.jpg">
  <meta property="og:image:width" content="1004">
  <meta property="og:image:height" content="656">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='9' fill='%23E94B3C'/%3E%3Cpath d='M16 7c3 4 5 6.5 5 9.5a5 5 0 0 1-10 0C11 13.5 13 11 16 7Z' fill='%23fff'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,500&family=Hanken+Grotesk:wght@400;500&family=Noto+Sans+SC:wght@400;500&family=Bodoni+Moda:opsz,wght@6..96,700&family=Noto+Serif+SC:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/shop/dewstick.css">
  <link rel="stylesheet" href="/shop/site.css">
  <script type="application/json" id="catalog">{json.dumps(catalog(lang), ensure_ascii=False)}</script>
  <script src="/shop/site.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main">{t(lang, 'general.skip')}</a>
  <div class="announcement"><p><a href="{shop}">{t(lang, 'announcement.text')}</a></p></div>
  <header class="site-header">
    <div class="g12 site-header__bar">
      <a href="{home}" class="wordmark" aria-label="{t(lang, 'general.home')}">dewstick</a>
      <nav class="site-header__nav u-caps" aria-label="{t(lang, 'general.nav')}">
        <a href="{shop}">{t(lang, 'nav.shop')}</a>
        <a href="{p}/products/the-set-of-five">{t(lang, 'set.title')}</a>
        <a href="{home}#why">{t(lang, 'nav.ingredients')}</a>
        <a href="{home}#note">{t(lang, 'nav.idea')}</a>
      </nav>
      <div class="site-header__tools u-caps">
        <span class="hide-mobile">{lang_switch()}</span>
        <a href="{p}/cart" class="cart-link" data-cart-link>
          <span class="hide-mobile">{t(lang, 'general.cart')}</span>
          <svg class="only-mobile" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
          <span class="cart-count" data-cart-count hidden></span>
        </a>
        <button id="menuBtn" class="menu-toggle" aria-label="{t(lang, 'general.menu')}" aria-expanded="false" aria-controls="menuPanel">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
        </button>
      </div>
    </div>
    <nav id="menuPanel" class="mobile-nav" hidden aria-label="{t(lang, 'general.nav')}">
      <a href="{shop}">{t(lang, 'nav.shop')}</a>
      <a href="{p}/products/the-set-of-five">{t(lang, 'set.title')}</a>
      <a href="{home}#why">{t(lang, 'nav.ingredients')}</a>
      <a href="{home}#note">{t(lang, 'nav.idea')}</a>
      <a href="{p}/cart">{t(lang, 'general.cart')} (<span data-cart-total>0</span>)</a>
      <div class="u-caps">{lang_switch()}</div>
    </nav>
  </header>
  <main id="main" tabindex="-1">
{body}
  </main>
  <footer class="site-footer">
    {news_wall(lang)}
    <div class="site-footer__rule" style="margin-top: var(--section);"></div>
    <div class="g12 site-footer__grid">
      <div class="site-footer__col">
        <p class="site-footer__label">{t(lang, 'home.footer_shop')}</p>
        <ul>
          <li><a href="{shop}">{t(lang, 'home.tile_shades')}</a></li>
          <li><a href="{p}/products/the-set-of-five">{t(lang, 'set.title')}</a></li>
          <li><a href="{p}/cart">{t(lang, 'general.cart')}</a></li>
        </ul>
      </div>
      <div class="site-footer__col">
        <p class="site-footer__label">{t(lang, 'footer.about')}</p>
        <ul>
          <li><a href="{home}#note">{t(lang, 'nav.idea')}</a></li>
          <li><a href="{home}#why">{t(lang, 'nav.ingredients')}</a></li>
        </ul>
      </div>
      <div class="site-footer__col">
        <p class="site-footer__label">{t(lang, 'footer.project')}</p>
        <ul>
          <li><a href="{SHOPIFY_URL}" target="_blank" rel="noopener">{t(lang, 'footer.store')}</a></li>
          <li><a href="{GITHUB_URL}" target="_blank" rel="noopener">{t(lang, 'footer.source')}</a></li>
        </ul>
      </div>
      <div class="site-footer__switch">
        <p>{t(lang, 'home.lang_q')}</p>
        {lang_switch()}
      </div>
    </div>
    <div class="site-footer__base">
      <p>© 2026 dewstick</p>
      <p class="muted">{t(lang, 'footer.copy_note')}</p>
    </div>
  </footer>
</body>
</html>
"""


# ---------- sections ----------
CHECK = '<svg viewBox="0 0 10 10" aria-hidden="true"><path d="M1.5 5.2 4 7.6 8.6 2.4"/></svg>'
# Store photography only (dewstick-01…13 + the per-shade packshots, the same set the Shopify store uses).
PACKSHOT = {s: f"/shop/img/dewstick-{n}-1000.jpg" for s, n in BALM["variants"].items()}


def img(src, alt="", sizes="100vw", loading="lazy", cls=""):
    """A plain <img> from /image or /shop/img. Product photos get a 500/1000 srcset."""
    m = re.fullmatch(r"/shop/img/dewstick-(\w+)-1000\.jpg", src)
    srcset = (f' srcset="/shop/img/dewstick-{m[1]}-500.jpg 500w, {src} 1000w" sizes="{sizes}"' if m else "")
    c = f' class="{cls}"' if cls else ""
    return f'<img{c} src="{src}"{srcset} loading="{loading}" alt="{html.escape(alt)}">'


def placeholder(label, cls=""):
    """Stand-in where the store has no suitable photo yet; says what belongs there."""
    return f'<div class="ph {cls}" aria-hidden="true"><span>{html.escape(label)}</span></div>'


def product_card(lang, title, href, image, price, desc="", badge="", compare=None, alt=""):
    badge = f'<span class="pcard__badge">{badge}</span>' if badge else ""
    compare = f'<span class="faint pcard__compare">{money(compare)}</span>' if compare else ""
    desc = f'<p class="pcard__desc">{desc}</p>' if desc else ""
    return f"""<a class="pcard" href="{href}">
          <div class="pcard__media">{img(image, alt or title, "(min-width: 768px) 25vw, 50vw")}{badge}</div>
          <h3 class="pcard__title t-xl">{title}</h3>
          <p class="pcard__price">{money(price)}{compare}</p>
          {desc}
        </a>"""


def shade_card(lang, shade):
    p = LANGS[lang]
    return product_card(
        lang, f"{swatch(shade)}{shade} <span class=\"faint\">/ {t(lang, f'shades.{shade}.tone')}</span>",
        f"{p}/products/dewstick-tinted-lip-balm?variant={shade}", PACKSHOT[shade], BALM["price"],
        t(lang, f"shades.{shade}.line"), alt=ALT[BALM["variants"][shade]])


def set_card(lang):
    return product_card(lang, SET["title"][lang], f"{LANGS[lang]}/products/the-set-of-five",
                        "/shop/img/dewstick-04-1000.jpg", SET["price"], t(lang, "set.body"),
                        badge=t(lang, "home.badge_save", amount=money(SET["compare"] - SET["price"]).split()[0]), compare=SET["compare"], alt=ALT["04"])


def add_form(lang, key, extra_cls=""):
    return (f'<form class="{extra_cls}" data-add-form><input type="hidden" name="id" value="{key}">'
            f'<button type="submit" class="btn">{t(lang, "product.add_to_cart")}</button></form>')


def news_wall(lang):
    """Newsletter + four photo tiles — sits above the footer on every page."""
    # the store photos carry their own type, so no overlay line on these
    wall = "\n      ".join(
        f'<div class="wall__tile">{img(f"/shop/img/dewstick-{n}-1000.jpg", ALT[n], "(min-width: 768px) 20vw, 33vw")}</div>'
        for n in ["06", "02", "01", "13"])
    return f"""<section class="g12 news" aria-labelledby="news-title">
    <div class="news__form">
      <h2 id="news-title" class="h2">{t(lang, 'newsletter.heading')}</h2>
      <p class="mt-3 measure">{t(lang, 'newsletter.privacy')}</p>
      <form class="js-newsletter" novalidate>
      <div class="field-row">
        <label class="visually-hidden" for="Newsletter-email">{t(lang, 'newsletter.label')}</label>
        <input id="Newsletter-email" type="email" name="email" inputmode="email" autocomplete="email" required placeholder="{t(lang, 'newsletter.placeholder')}">
        <button type="submit" class="btn">{t(lang, 'newsletter.submit')}</button>
      </div>
      <p class="form-note" data-note aria-live="polite"></p>
      </form>
    </div>
    <div class="wall" aria-label="@dewstick">
      {wall}
    </div>
  </section>"""


def home(lang):
    p = LANGS[lang]
    shop = f"{p}/collections/shades"
    usp = "".join(f"<li>{CHECK}{t(lang, f'home.{k}')}</li>" for k in ["usp_formula", "usp_shades", "usp_set"])
    cards = "\n        ".join([shade_card(lang, s) for s in ["nectar", "poppy", "blush"]] + [set_card(lang)])
    tiles = [(shop, "/shop/img/dewstick-11-1000.jpg", t(lang, "home.tile_shades")),
             (f"{p or '/'}#why", "/shop/img/dewstick-orchid-1000.jpg", t(lang, "nav.ingredients")),
             (f"{p}/products/the-set-of-five", "/shop/img/dewstick-10-1000.jpg", t(lang, "set.title")),
             (f"{p or '/'}#note", "/shop/img/dewstick-08-1000.jpg", t(lang, "nav.idea"))]
    tiles = "\n    ".join(
        f'<a class="tile" href="{href}"><div class="tile__media">{img(src, "", "(min-width: 768px) 25vw, 70vw")}</div>'
        f'<p class="tile__caption">{cap}</p></a>' for href, src, cap in tiles)
    why_cards = "\n    ".join(
        f"""<article class="scard">
      <h3 class="t-xl">{t(lang, f'home.why_{k}')}</h3>
      <p class="scard__label">{t(lang, f'why.{benefit}.title')}</p>
      <p class="scard__body">{t(lang, f'home.why_{k}_body')}</p>
      {img(f'/shop/img/{src}.jpg', alt, '(min-width: 768px) 15vw, 50vw', cls='scard__img')}
    </article>""" for k, benefit, src, alt in [  # ingredient photos (from the retired waitlist page)
        ("squalane", "hydrate", "ing-squalane", "Squalane"), ("shea", "nourish", "ing-shea", "Shea butter"),
        ("ceramides", "protect", "ing-ceramide", "Ceramide cream"), ("colour", "glow", "ing-swatches", "Tinted lip balm swatches")])
    body = f"""<section class="hero">
  <div class="hero__media">
    <video autoplay loop muted playsinline preload="metadata" poster="/shop/video/hero-poster.jpg" aria-hidden="true">
      <source src="/shop/video/hero.mp4" type="video/mp4">
    </video>
  </div>
  <div class="hero__content">
    <h1 class="h1">{t(lang, 'hero.title_html').replace('<br>', '<br> ')}</h1>
    <div class="hero__copy">
      <p>{t(lang, 'home.hero_p1')}</p>
      <p>{t(lang, 'home.hero_p2')}</p>
      <div class="btn-stack">
        <a class="btn btn--light" href="{shop}">{t(lang, 'hero.cta')}</a>
        <a class="btn btn--outline" href="#why">{t(lang, 'hero.cta_secondary')}</a>
      </div>
    </div>
  </div>
</section>

<ul class="usp">{usp}</ul>

<section id="shades" class="section">
  <div class="sec-head">
    <h2 class="h2">{t(lang, 'home.shop_title')}</h2>
    <a class="link-u" href="{shop}">{t(lang, 'shades.see_all')}</a>
  </div>
  <div class="g12 pgrid">
        {cards}
  </div>
</section>

<section id="feel" class="section g12 gallery">
  <div class="gallery__text">
    <h2 class="t-xl">{t(lang, 'feel.title')}</h2>
    <div class="prose measure" style="margin-top: 1.3rem;">
      <p>{t(lang, 'home.feel_lead')}</p>
      <p>{t(lang, 'home.feel_p1')}</p>
      <p>{t(lang, 'home.feel_p2')}</p>
    </div>
    <a class="btn btn--outline" href="{shop}">{t(lang, 'home.feel_cta')}</a>
  </div>
  <div class="gallery__img gallery__img--a">{img('/shop/img/dewstick-09-1000.jpg', ALT['09'], '(min-width: 768px) 50vw, 100vw')}</div>
</section>

<section class="band">
  <img class="band__img" src="/shop/img/band-lips-square.jpg" loading="lazy" alt="{html.escape(ALT['12'])}">
  <div class="g12">
    <div class="band__copy">
      <p class="t-3xl">{t(lang, 'home.story')}</p>
      <a class="btn btn--outline" href="#why">{t(lang, 'hero.cta_secondary')}</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="sec-head"><h2 class="h2">{t(lang, 'home.tiles_title')}</h2></div>
  <div class="tiles">
    {tiles}
  </div>
</section>

<section id="why" class="section" aria-labelledby="why-title">
  <div class="sec-head">
    <h2 id="why-title" class="h2">{t(lang, 'why.title')}</h2>
    <div class="scroller__nav">
      <button type="button" class="link-u" data-scroll="-1" aria-controls="whyTrack">{t(lang, 'home.why_prev')}</button>
      <button type="button" class="link-u" data-scroll="1" aria-controls="whyTrack">{t(lang, 'home.why_next')}</button>
    </div>
  </div>
  <div id="whyTrack" class="scroller" tabindex="0" aria-label="{t(lang, 'why.title')}">
    {why_cards}
  </div>
</section>

<section id="note" class="section g12 founder">
  <h2 class="h2 founder__head">{t(lang, 'note.title')}</h2>
  <div class="founder__body">
    <div class="prose measure">
      <p>{t(lang, 'note.sub')}</p>
      <p>{t(lang, 'note.body')}</p>
      <p>{t(lang, 'home.note_sign')}</p>
    </div>
    <a class="btn btn--outline" href="{shop}">{t(lang, 'hero.cta')}</a>
  </div>
  <div class="founder__img">{img('/shop/img/dewstick-07-1000.jpg', ALT['07'], '(min-width: 768px) 33vw, 100vw')}</div>
</section>"""
    title = "dewstick — " + re.sub(r"<br>", " ", t(lang, "hero.title_html"))
    return page(lang, "", title, body)


def why(lang, image):
    icons = {
        "hydrate": '<path d="M12 3c3 4 5 6.5 5 9.5a5 5 0 0 1-10 0C7 9.5 9 7 12 3Z"/>',
        "nourish": '<path d="M5 19c0-8 5-13 14-14-1 9-6 14-14 14Z"/><path d="M5 19 13 11"/>',
        "protect": '<path d="M12 3 5 6v5c0 4.5 3 8.5 7 10 4-1.5 7-5.5 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/>',
        "glow": '<path d="M12 21s-7-4.35-7-10a4 4 0 0 1 7-2.65A4 4 0 0 1 19 11c0 5.65-7 10-7 10Z"/>',
    }
    benefits = "\n        ".join(
        f'<div class="benefit"><svg viewBox="0 0 24 24" aria-hidden="true">{svg}</svg>'
        f'<h3>{t(lang, f"why.{k}.title")}</h3><p class="small muted">{t(lang, f"why.{k}.body")}</p></div>'
        for k, svg in icons.items())
    return f"""<section id="why" class="border-top">
  <div class="split split--reverse">
    <div class="split__media">{well(image)}</div>
    <div class="split__copy">
      <h2 class="h2">{t(lang, 'why.title')}</h2>
      <p class="lede mt-2" style="max-width: 28rem;">{t(lang, 'why.body')}</p>
      <div class="benefits">
        {benefits}
      </div>
    </div>
  </div>
</section>"""


def collection(lang):
    p = LANGS[lang]
    cards = [shade_card(lang, s) for s in BALM["variants"]] + [set_card(lang)]
    body = f"""<section class="section" style="padding-top: 3.6rem;">
  <div class="sec-head">
    <h1 class="h2">{t(lang, 'collection.title')}</h1>
    <p class="muted">{t(lang, 'collection.desc')}</p>
  </div>
  <div class="g12 pgrid">
        {chr(10).join(cards)}
  </div>
</section>"""
    return page(lang, "/collections/shades", f"{t(lang, 'collection.title', raw=True)} — dewstick", body,
                t(lang, "collection.desc", raw=True))


def product(lang, handle):
    prod = PRODUCTS[handle]
    has_shades = bool(prod["variants"])
    first = prod["media"][0]
    gallery = "\n      ".join(
        f'<div data-media-id="{n}"{"" if n == first else " hidden"}>{well(n, "(min-width: 900px) 55vw, 100vw", "eager")}</div>'
        for n in prod["media"])
    thumbs = "\n        ".join(
        f'<button type="button" data-thumb="{n}" aria-label="{html.escape(ALT[n])}" aria-current="{"true" if n == first else "false"}">'
        f'<img src="/shop/img/dewstick-{n}-500.jpg" loading="lazy" alt="" width="500" height="500"></button>'
        for n in prod["media"])
    compare = f'<span class="faint card__compare">{money(prod["compare"])}</span>' if prod["compare"] else ""
    picker = ""
    variants_json = "[]"
    key = handle
    if has_shades:
        opts = "\n          ".join(
            f'<input type="radio" id="shade-{i}" name="shade" value="{s}" data-option{" checked" if i == 1 else ""}>'
            f'<label for="shade-{i}">{swatch(s)}{s}<span class="faint">&nbsp;/ {t(lang, f"shades.{s}.tone")}</span></label>'
            for i, s in enumerate(prod["variants"], 1))
        picker = f"""<fieldset class="shade-picker">
        <legend class="u-mini">{t(lang, 'product.shade')}</legend>
        <div class="shade-picker__options">
          {opts}
        </div>
      </fieldset>"""
        variants_json = json.dumps([{"id": f"{handle}:{s}", "option1": s, "media_id": n} for s, n in prod["variants"].items()])
        key = f"{handle}:{next(iter(prod['variants']))}"
    upsell = ""
    if has_shades:
        upsell = f"""<section class="border-top">
  <div class="split split--mist">
    <div class="split__media">{well('04')}</div>
    <div class="split__copy">
      <h2 class="h2">{t(lang, 'set.title')}</h2>
      <p class="lede mt-2" style="max-width: 26rem;">{t(lang, 'set.body')}</p>
      <a class="link-cta mt-3" style="align-self: flex-start;" href="{LANGS[lang]}/products/the-set-of-five">{t(lang, 'set.details')}</a>
    </div>
  </div>
</section>

"""
    body = f"""<section class="section" data-product>
  <div class="container product">
    <div class="product__gallery">
      {gallery}
      <div class="product__thumbs">
        {thumbs}
      </div>
    </div>
    <div class="product__info">
      <p class="u-mini muted">{prod['type'][lang]}</p>
      <h1 class="h2 mt-1" style="text-transform: none;">{prod['title'][lang]}</h1>
      <p class="product__price"><span data-price>{money(prod['price'])}</span>{compare}</p>
      <form id="ProductForm" data-add-form>
        <input type="hidden" name="id" value="{key}">
        {picker}
        <div class="buy-row">
          <div class="qty" data-qty>
            <button type="button" data-step="-1" aria-label="{t(lang, 'product.qty_down')}">−</button>
            <label class="visually-hidden" for="Quantity">{t(lang, 'product.quantity')}</label>
            <input id="Quantity" type="number" name="quantity" value="1" min="1" inputmode="numeric">
            <button type="button" data-step="1" aria-label="{t(lang, 'product.qty_up')}">+</button>
          </div>
          <button type="submit" class="btn" data-add><span>{t(lang, 'product.add_to_cart')}</span></button>
        </div>
      </form>
      <div class="product__desc rte">{prod['desc'][lang]}</div>
    </div>
  </div>
  <script type="application/json" data-variants>{variants_json}</script>
</section>

{upsell}{why(lang, '05') if has_shades else ''}"""
    desc = re.sub(r"<[^>]+>", " ", prod["desc"][lang])
    desc = re.sub(r"\s+", " ", html.unescape(desc)).strip()[:160]
    return page(lang, f"/products/{handle}", f"{prod['title'][lang]} — dewstick", body, desc)


def cart(lang):
    body = f"""<section class="section">
  <div class="container page-narrow" data-cart>
    <h1 class="h2">{t(lang, 'cart.title')}</h1>
    <div data-cart-empty hidden>
      <p class="mt-2 muted">{t(lang, 'cart.empty')}</p>
      <a class="link-cta mt-3" href="{LANGS[lang]}/collections/shades">{t(lang, 'cart.continue')}</a>
    </div>
    <div data-cart-full hidden>
      <div class="mt-3" data-cart-lines></div>
      <div class="cart-footer">
        <p><span class="u-mini muted">{t(lang, 'cart.subtotal')}</span>&nbsp; <span class="medium" data-cart-subtotal></span></p>
        <p class="small muted">{t(lang, 'cart.taxes')}</p>
        <button type="button" class="btn" data-checkout aria-controls="demoCheckout" aria-expanded="false">{t(lang, 'cart.checkout')}</button>
      </div>
      <div id="demoCheckout" class="demo-panel" hidden tabindex="-1">
        <p class="u-mini accent">{t(lang, 'demo.title')}</p>
        <p class="small mt-1">{t(lang, 'demo.body')}</p>
        <a class="link-cta mt-2" href="{SHOPIFY_URL}" target="_blank" rel="noopener">{t(lang, 'demo.cta')}</a>
      </div>
    </div>
  </div>
</section>"""
    return page(lang, "/cart", f"{t(lang, 'cart.title', raw=True)} — dewstick", body)


def not_found(lang):
    body = f"""<section class="section">
  <div class="container page-narrow center">
    <p class="u-mini muted">404</p>
    <h1 class="h2 mt-1">{t(lang, 'not_found.title')}</h1>
    <a class="link-cta mt-3" href="{LANGS[lang]}/collections/shades">{t(lang, 'cart.continue')}</a>
  </div>
</section>"""
    return page(lang, "/404", f"404 — dewstick", body)


def main():
    # shop/dewstick.css is the website's stylesheet; shopify-theme/assets/dewstick.css is the same file plus a
    # few lines at the end for the theme's section layout. Change both together.
    for lang, prefix in LANGS.items():
        out = ROOT / prefix.lstrip("/")
        pages = {
            "index.html": home(lang),
            "collections/shades.html": collection(lang),
            "products/dewstick-tinted-lip-balm.html": product(lang, "dewstick-tinted-lip-balm"),
            "products/the-set-of-five.html": product(lang, "the-set-of-five"),
            "cart.html": cart(lang),
        }
        if lang == "en":
            pages["404.html"] = not_found(lang)
        for rel, content in pages.items():
            f = out / rel
            f.parent.mkdir(parents=True, exist_ok=True)
            f.write_text(content, encoding="utf-8", newline="\n")
            print("wrote", f.relative_to(ROOT))


if __name__ == "__main__":
    main()
