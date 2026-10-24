/* dewstick — the static site's behaviour (shop/site.js) for the WordPress theme. The cart itself is WooCommerce's. */
(function () {
  'use strict';

  function each(list, fn) { Array.prototype.forEach.call(list, fn); }

  /* ---------- language switch keeps the selected shade ---------- */
  each(document.querySelectorAll('.lang-switch a'), function (a) {
    a.addEventListener('click', function () {
      var shade = new URLSearchParams(location.search).get('attribute_shade');
      if (shade) {
        var url = new URL(a.href);
        url.searchParams.set('attribute_shade', shade);
        a.href = url.toString();
      }
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
  each(document.querySelectorAll('[data-qty]'), function (wrap) {
    var input = wrap.querySelector('input');
    each(wrap.querySelectorAll('button[data-step]'), function (b) {
      b.addEventListener('click', function () {
        input.value = Math.max(Number(input.min || 0), (parseInt(input.value, 10) || 0) + Number(b.dataset.step));
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });
    });
  });

  /* ---------- product page: thumbs + shade picker ---------- */
  var productRoot = document.querySelector('[data-product]');
  if (productRoot) {
    var mainImgs = productRoot.querySelectorAll('[data-media-id]');
    var thumbs = productRoot.querySelectorAll('[data-thumb]');
    var variationInput = productRoot.querySelector('[data-variation-id]');

    var showMedia = function (id) {
      if (!id) return;
      each(mainImgs, function (el) { el.hidden = el.dataset.mediaId !== id; });
      each(thumbs, function (t) { t.setAttribute('aria-current', t.dataset.thumb === id ? 'true' : 'false'); });
    };
    each(thumbs, function (t) { t.addEventListener('click', function () { showMedia(t.dataset.thumb); }); });

    each(productRoot.querySelectorAll('input[data-option]'), function (input) {
      input.addEventListener('change', function () {
        if (variationInput) variationInput.value = input.dataset.variation;
        showMedia(input.dataset.media);
        var url = new URL(location.href);
        url.searchParams.set('attribute_shade', input.value);
        history.replaceState(null, '', url.toString());
      });
    });
  }

  /* ---------- cart: a quantity change updates the cart ---------- */
  var cartForm = document.querySelector('[data-cart-form]');
  if (cartForm) {
    var update = cartForm.querySelector('[data-update-cart]');
    update.hidden = true;
    each(cartForm.querySelectorAll('[data-cart-qty]'), function (input) {
      input.addEventListener('change', function () { update.click(); });
    });
  }

  /* ---------- header tint once the page scrolls ---------- */
  var header = document.querySelector('.site-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- card scroller prev / next ---------- */
  each(document.querySelectorAll('[data-scroll]'), function (btn) {
    var track = document.getElementById(btn.getAttribute('aria-controls'));
    if (!track) return;
    btn.addEventListener('click', function () {
      var card = track.firstElementChild;
      var step = card ? card.getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap || 0) : track.clientWidth;
      track.scrollBy({ left: step * Number(btn.getAttribute('data-scroll')), behavior: 'smooth' });
    });
  });

  /* ---------- newsletter: check the address before it posts ---------- */
  each(document.querySelectorAll('form.js-newsletter'), function (form) {
    var email = form.querySelector('input[type="email"]');
    var row = form.querySelector('.field-row');
    var note = form.querySelector('[data-note]');
    form.addEventListener('submit', function (e) {
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        e.preventDefault();
        row.classList.add('is-error');
        note.textContent = window.DEWSTICK ? window.DEWSTICK.invalidEmail : '';
        email.focus();
      }
    });
    email.addEventListener('input', function () { row.classList.remove('is-error'); });
  });
})();
