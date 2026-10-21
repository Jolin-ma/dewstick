(function () {
  'use strict';

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

  /* ---------- UTM capture ----------
     Remember the first-touch utm_* params for the session and tag every
     newsletter signup with them, so Customers can be filtered by source. */
  var UTM_KEY = 'dewstick_utm';
  try {
    var params = new URLSearchParams(location.search);
    var found = [];
    ['utm_source', 'utm_medium', 'utm_campaign'].forEach(function (k) {
      var v = params.get(k);
      if (v) found.push(k.replace('utm_', '') + ':' + v.toLowerCase().replace(/[^a-z0-9_-]/g, '').slice(0, 40));
    });
    if (found.length && !sessionStorage.getItem(UTM_KEY)) sessionStorage.setItem(UTM_KEY, found.join(','));
  } catch (e) {}

  Array.prototype.forEach.call(document.querySelectorAll('form.js-newsletter'), function (form) {
    var tags = form.querySelector('input[name="contact[tags]"]');
    var email = form.querySelector('input[type="email"]');
    var row = email && email.closest('.field-row');
    form.addEventListener('submit', function (e) {
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        e.preventDefault();
        if (row) row.classList.add('is-error');
        email.focus();
        return;
      }
      try {
        var utm = sessionStorage.getItem(UTM_KEY);
        if (utm && tags) tags.value = tags.value + ',' + utm;
      } catch (err) {}
    });
    if (email) email.addEventListener('input', function () { if (row) row.classList.remove('is-error'); });
  });

  /* ---------- product page ---------- */
  var productRoot = document.querySelector('[data-product]');
  if (productRoot) {
    var dataEl = productRoot.querySelector('script[data-variants]');
    var variants = dataEl ? JSON.parse(dataEl.textContent) : [];
    var idInput = productRoot.querySelector('input[name="id"]');
    var priceEl = productRoot.querySelector('[data-price]');
    var addBtn = productRoot.querySelector('[data-add]');
    var mainImgs = productRoot.querySelectorAll('[data-media-id]');
    var thumbs = productRoot.querySelectorAll('[data-thumb]');

    function showMedia(id) {
      if (!id) return;
      Array.prototype.forEach.call(mainImgs, function (el) { el.hidden = String(el.dataset.mediaId) !== String(id); });
      Array.prototype.forEach.call(thumbs, function (t) { t.setAttribute('aria-current', String(t.dataset.thumb) === String(id) ? 'true' : 'false'); });
    }

    Array.prototype.forEach.call(thumbs, function (t) {
      t.addEventListener('click', function () { showMedia(t.dataset.thumb); });
    });

    Array.prototype.forEach.call(productRoot.querySelectorAll('input[data-option]'), function (input) {
      input.addEventListener('change', function () {
        var v = variants.filter(function (x) { return x.option1 === input.value; })[0];
        if (!v) return;
        idInput.value = v.id;
        if (priceEl) priceEl.textContent = v.price_label;
        if (addBtn) {
          addBtn.disabled = !v.available;
          addBtn.querySelector('span').textContent = v.available ? addBtn.dataset.labelAdd : addBtn.dataset.labelSoldout;
        }
        showMedia(v.media_id);
        var url = new URL(location.href);
        url.searchParams.set('variant', v.id);
        history.replaceState(null, '', url.toString());
      });
    });
  }

  /* ---------- quantity steppers ---------- */
  Array.prototype.forEach.call(document.querySelectorAll('[data-qty]'), function (wrap) {
    var input = wrap.querySelector('input');
    Array.prototype.forEach.call(wrap.querySelectorAll('button[data-step]'), function (b) {
      b.addEventListener('click', function () {
        var next = Math.max(Number(input.min || 0), (parseInt(input.value, 10) || 0) + Number(b.dataset.step));
        input.value = next;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });
    });
  });
})();
