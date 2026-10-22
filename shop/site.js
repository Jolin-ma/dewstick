/* dewstick — static copy of the Shopify store. Same behaviour as shopify-theme/assets/dewstick.js,
   plus a localStorage cart standing in for Shopify's /cart. */
(function () {
  'use strict';

  var CAT = JSON.parse(document.getElementById('catalog').textContent);
  var S = CAT.strings;
  var CART_KEY = 'dewstick_cart';

  function money(n) { return '$' + n + ' ' + CAT.currency; }
  function each(list, fn) { Array.prototype.forEach.call(list, fn); }

  /* ---------- cart storage ---------- */
  function readCart() {
    try {
      var c = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
      return Array.isArray(c) ? c.filter(function (l) { return CAT.items[l.id] && l.qty > 0; }) : [];
    } catch (e) { return []; }
  }
  function writeCart(c) {
    try { localStorage.setItem(CART_KEY, JSON.stringify(c)); } catch (e) {}
    renderCount();
    renderCart();
  }
  function addToCart(id, qty) {
    var c = readCart();
    var line = c.filter(function (l) { return l.id === id; })[0];
    if (line) line.qty += qty; else c.push({ id: id, qty: qty });
    writeCart(c);
  }
  function count() { return readCart().reduce(function (n, l) { return n + l.qty; }, 0); }

  function renderCount() {
    var n = count();
    each(document.querySelectorAll('[data-cart-count]'), function (el) { el.textContent = n; el.hidden = n === 0; });
    each(document.querySelectorAll('[data-cart-total]'), function (el) { el.textContent = n; });
    each(document.querySelectorAll('[data-cart-link]'), function (el) {
      el.setAttribute('aria-label', (n === 1 ? S.cart_count_one : S.cart_count_other).replace('{{ count }}', n));
    });
  }

  /* ---------- language switch keeps the selected shade ---------- */
  each(document.querySelectorAll('[data-lang-link]'), function (a) {
    a.addEventListener('click', function () {
      if (location.search && a.pathname.indexOf('/products/') !== -1) a.href = a.pathname + location.search;
    });
  });

  /* ---------- mobile menu ---------- */
  var menuBtn = document.getElementById('menuBtn');
  var menuPanel = document.getElementById('menuPanel');
  if (menuBtn && menuPanel) {
    menuBtn.addEventListener('click', function () {
      var open = menuPanel.hidden;
      menuPanel.hidden = !open;
      menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ---------- quantity steppers ---------- */
  function wireQty(root) {
    each(root.querySelectorAll('[data-qty]'), function (wrap) {
      var input = wrap.querySelector('input');
      each(wrap.querySelectorAll('button[data-step]'), function (b) {
        b.addEventListener('click', function () {
          var next = Math.max(Number(input.min || 0), (parseInt(input.value, 10) || 0) + Number(b.dataset.step));
          input.value = next;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        });
      });
    });
  }
  wireQty(document);

  /* ---------- product page ---------- */
  var productRoot = document.querySelector('[data-product]');
  if (productRoot) {
    var variants = JSON.parse(productRoot.querySelector('script[data-variants]').textContent);
    var idInput = productRoot.querySelector('input[name="id"]');
    var mainImgs = productRoot.querySelectorAll('[data-media-id]');
    var thumbs = productRoot.querySelectorAll('[data-thumb]');

    var showMedia = function (id) {
      each(mainImgs, function (el) { el.hidden = el.dataset.mediaId !== id; });
      each(thumbs, function (t) { t.setAttribute('aria-current', t.dataset.thumb === id ? 'true' : 'false'); });
    };
    each(thumbs, function (t) { t.addEventListener('click', function () { showMedia(t.dataset.thumb); }); });

    var selectShade = function (shade, push) {
      var v = variants.filter(function (x) { return x.option1 === shade; })[0];
      if (!v) return;
      idInput.value = v.id;
      showMedia(v.media_id);
      if (push) {
        var url = new URL(location.href);
        url.searchParams.set('variant', shade);
        history.replaceState(null, '', url.toString());
      }
    };
    var options = productRoot.querySelectorAll('input[data-option]');
    each(options, function (input) {
      input.addEventListener('change', function () { selectShade(input.value, true); });
    });
    var wanted = new URLSearchParams(location.search).get('variant');
    if (wanted) {
      each(options, function (input) { if (input.value === wanted) input.checked = true; });
      selectShade(wanted, false);
    }
  }

  /* ---------- add to cart (product page + "the set" on home) ---------- */
  each(document.querySelectorAll('form[data-add-form]'), function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var id = form.querySelector('input[name="id"]').value;
      var q = form.querySelector('input[name="quantity"]');
      addToCart(id, Math.max(1, parseInt(q ? q.value : 1, 10) || 1));
      location.href = CAT.prefix + '/cart';
    });
  });

  /* ---------- cart page ---------- */
  var cartRoot = document.querySelector('[data-cart]');
  function esc(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  function renderCart() {
    if (!cartRoot) return;
    var c = readCart();
    cartRoot.querySelector('[data-cart-empty]').hidden = c.length > 0;
    cartRoot.querySelector('[data-cart-full]').hidden = c.length === 0;
    var total = 0;
    cartRoot.querySelector('[data-cart-lines]').innerHTML = c.map(function (l, i) {
      var it = CAT.items[l.id];
      total += it.price * l.qty;
      var sub = it.variant
        ? '<p class="small muted"><span class="swatch-dot" style="background:' + it.swatch + '" aria-hidden="true"></span>' + esc(it.variant) + '</p>'
        : '';
      return '<div class="cart-line">' +
        '<a href="' + it.url + '"><div class="well well--square"><img src="' + it.img + '" alt="" width="500" height="500"></div></a>' +
        '<div><a href="' + it.url + '" class="medium">' + esc(it.title) + '</a>' + sub +
          '<div class="qty mt-1" data-qty data-line="' + l.id + '">' +
            '<button type="button" data-step="-1" aria-label="' + esc(S['product.qty_down']) + '">−</button>' +
            '<label class="visually-hidden" for="Qty-' + i + '">' + esc(S['product.quantity']) + '</label>' +
            '<input id="Qty-' + i + '" type="number" value="' + l.qty + '" min="0" inputmode="numeric">' +
            '<button type="button" data-step="1" aria-label="' + esc(S['product.qty_up']) + '">+</button>' +
          '</div>' +
          '<div><button type="button" class="cart-line__remove muted" data-remove="' + l.id + '">' + esc(S['cart.remove']) + '</button></div>' +
        '</div>' +
        '<p class="small">' + money(it.price * l.qty) + '</p>' +
      '</div>';
    }).join('');
    cartRoot.querySelector('[data-cart-subtotal]').textContent = money(total);

    var lines = cartRoot.querySelector('[data-cart-lines]');
    wireQty(lines);
    each(lines.querySelectorAll('[data-qty]'), function (wrap) {
      wrap.querySelector('input').addEventListener('change', function (e) {
        setQty(wrap.dataset.line, parseInt(e.target.value, 10) || 0);
      });
    });
    each(lines.querySelectorAll('[data-remove]'), function (b) {
      b.addEventListener('click', function () { setQty(b.dataset.remove, 0); });
    });
  }

  function setQty(id, qty) {
    var c = readCart().map(function (l) { if (l.id === id) l.qty = qty; return l; })
      .filter(function (l) { return l.qty > 0; });
    writeCart(c);
  }

  if (cartRoot) {
    var checkout = cartRoot.querySelector('[data-checkout]');
    var panel = document.getElementById('demoCheckout');
    checkout.addEventListener('click', function () {
      panel.hidden = false;
      checkout.setAttribute('aria-expanded', 'true');
      panel.focus();
    });
  }

  /* ---------- newsletter (no backend here) ---------- */
  each(document.querySelectorAll('form.js-newsletter'), function (form) {
    var email = form.querySelector('input[type="email"]');
    var row = form.querySelector('.field-row');
    var note = form.querySelector('[data-note]');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        row.classList.add('is-error');
        note.textContent = S['newsletter.invalid'];
        email.focus();
        return;
      }
      form.innerHTML = '<p class="small medium" role="status">' + esc(S['demo.newsletter']) + '</p>';
    });
    email.addEventListener('input', function () { row.classList.remove('is-error'); });
  });

  /* keep the header count right when another tab changes the cart */
  window.addEventListener('storage', function (e) { if (e.key === CART_KEY) { renderCount(); renderCart(); } });
  renderCount();
  renderCart();
})();
