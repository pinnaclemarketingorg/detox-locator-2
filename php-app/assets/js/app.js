/* ==========================================================================
   Detox Locator — the only script the site ships. No framework, no
   dependencies; loaded with defer on every page.

     1. Attribution snapshot   ad params captured on landing, sent with a lead
     2. Benefits form          masks, inline validation, JSON submit
     3. Review rail            prev/next buttons for the scroll-snap rows
     4. Location line          -dli pages: "Find Treatment in <state>…"
     5. Site menu              the microsite's mobile nav
     6. CTA events             one dataLayer event per call/verify click
     7. Landing page header    solid once the page scrolls
     8. Entrance animations    the live pages' fade/rise/zoom, once each

   Nothing personal ever goes into dataLayer. This is an addiction-treatment
   site: names, phone numbers, dates of birth and carriers stay in the POST to
   /api/lead.php and never reach GA4 or the ad platforms.
   ========================================================================== */
(function () {
  'use strict';

  var dataLayer = (window.dataLayer = window.dataLayer || []);
  function track(payload) {
    try { dataLayer.push(payload); } catch (_) { /* analytics never breaks the page */ }
  }

  /* ---------------------------------------------------------------------
     1. Attribution. First write wins, so a later pageview in the same
     session cannot overwrite the click that brought the visitor in.
     ------------------------------------------------------------------- */
  var ATTR_KEY = 'dl_attribution';
  var PARAMS = ['gclid', 'gbraid', 'wbraid', 'msclkid', 'fbclid', 'utm_source', 'utm_medium',
    'utm_campaign', 'utm_term', 'utm_content', 'campaignid', 'adgroupid', 'creative',
    'campaign_id', 'adgroup_id', 'creative_id'];

  try {
    if (!sessionStorage.getItem(ATTR_KEY)) {
      var q = new URLSearchParams(location.search);
      var found = {};
      PARAMS.forEach(function (k) { var v = q.get(k); if (v) found[k] = v; });
      if (Object.keys(found).length) {
        found.landing_page = location.href;
        if (document.referrer) found.referrer = document.referrer;
        sessionStorage.setItem(ATTR_KEY, JSON.stringify(found));
      }
    }
  } catch (_) { /* private browsing can throw; attribution is optional */ }

  function attribution() {
    try { return JSON.parse(sessionStorage.getItem(ATTR_KEY) || '{}'); } catch (_) { return {}; }
  }

  // Set by CTM's t.js; ties the lead to the visitor's traffic source.
  function visitorSid() {
    try {
      if (window.__ctm && window.__ctm.config && window.__ctm.config.sid) return window.__ctm.config.sid;
      var c = document.cookie.split(';').map(function (s) { return s.trim(); })
        .filter(function (s) { return s.indexOf('__ctmid=') === 0; })[0];
      return c ? decodeURIComponent(c.slice(8)) : '';
    } catch (_) { return ''; }
  }

  /* ---------------------------------------------------------------------
     2. Benefits form
     ------------------------------------------------------------------- */
  function digits(s) { return (s || '').replace(/\D/g, ''); }

  var masks = {
    phone: function (v) {
      var d = digits(v);
      if (d.length === 11 && d[0] === '1') d = d.slice(1);
      d = d.slice(0, 10);
      if (d.length < 4) return d;
      if (d.length < 7) return '(' + d.slice(0, 3) + ') ' + d.slice(3);
      return '(' + d.slice(0, 3) + ') ' + d.slice(3, 6) + '-' + d.slice(6);
    },
    date: function (v) {
      var d = digits(v).slice(0, 8);
      if (d.length < 3) return d;
      if (d.length < 5) return d.slice(0, 2) + '/' + d.slice(2);
      return d.slice(0, 2) + '/' + d.slice(2, 4) + '/' + d.slice(4);
    }
  };

  function validDob(v) {
    var m = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(v);
    if (!m) return false;
    var mo = +m[1], da = +m[2], yr = +m[3];
    var dt = new Date(yr, mo - 1, da);
    var now = new Date();
    return dt.getFullYear() === yr && dt.getMonth() === mo - 1 && dt.getDate() === da &&
      yr >= 1900 && dt < now;
  }

  var RULES = {
    first: function (v) { return v.trim() ? '' : 'Enter your first name.'; },
    last: function (v) { return v.trim() ? '' : 'Enter your last name.'; },
    phone: function (v) {
      var d = digits(v);
      if (d.length === 11 && d[0] === '1') d = d.slice(1);
      return d.length === 10 && !/^[01]/.test(d) ? '' : 'Enter a 10-digit phone number, like (555) 555-5555.';
    },
    dob: function (v) { return validDob(v.trim()) ? '' : 'Enter your date of birth as MM/DD/YYYY.'; },
    insurance: function (v) { return v.trim() ? '' : 'Enter your insurance provider, as it appears on your card.'; },
    policy: function (v) { return v.trim() ? '' : 'Enter your member ID. It’s on the front of your insurance card.'; },
    consent: function (_, el) { return el.checked ? '' : 'Check the box so our team can contact you.'; }
  };

  function setError(el, msg) {
    var err = document.getElementById(el.id + '-err');
    if (msg) el.setAttribute('aria-invalid', 'true'); else el.removeAttribute('aria-invalid');
    if (err) err.textContent = msg;
  }

  function check(el) {
    var rule = RULES[el.name];
    if (!rule) return '';
    var msg = rule(el.value, el);
    setError(el, msg);
    return msg;
  }

  function initForm(form) {
    var card = form.parentElement;
    var done = card.querySelector('[data-done]');
    var doneTitle = card.querySelector('[data-done-title]');
    var status = form.querySelector('[data-status]');
    var btn = form.querySelector('button[type="submit"]');

    form.querySelectorAll('[data-mask]').forEach(function (el) {
      var fn = masks[el.getAttribute('data-mask')];
      el.addEventListener('input', function () {
        // Only reformat when the caret is at the end, so editing the middle
        // of a number does not throw the caret around.
        if (el.selectionStart === el.value.length) el.value = fn(el.value);
      });
      el.addEventListener('blur', function () { el.value = fn(el.value); });
    });

    // Validate a field once the visitor has left it, then live while they fix it.
    form.querySelectorAll('input[name]').forEach(function (el) {
      if (!RULES[el.name]) return;
      el.addEventListener('blur', function () { if (el.value || el.type === 'checkbox') { el.dataset.touched = '1'; check(el); } });
      el.addEventListener(el.type === 'checkbox' ? 'change' : 'input', function () {
        if (el.dataset.touched || el.getAttribute('aria-invalid')) check(el);
      });
    });

    var started = false;
    form.addEventListener('focusin', function () {
      if (started) return;
      started = true;
      track({ event: 'form_start', form_id: form.id });
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var firstBad = null;
      form.querySelectorAll('input[name]').forEach(function (el) {
        if (RULES[el.name]) { el.dataset.touched = '1'; if (check(el) && !firstBad) firstBad = el; }
      });
      if (firstBad) {
        firstBad.focus();
        if (status) status.textContent = 'Please fix the highlighted fields.';
        return;
      }

      var data = {};
      new FormData(form).forEach(function (v, k) { data[k] = v; });
      data.attribution = attribution();
      data.visitor_sid = visitorSid();

      btn.setAttribute('aria-busy', 'true');
      btn.disabled = true;
      if (status) status.textContent = 'Sending…';

      function finish() {
        var name = (data.first || '').trim();
        doneTitle.textContent = name ? 'Thank you, ' + name + ' — we’ve got your details.' : 'Thank you — we’ve got your details.';
        form.hidden = true;
        done.hidden = false;
        done.focus();
      }

      fetch('/api/lead.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data),
        keepalive: true
      })
        .then(function (r) { return r.json().then(function (j) { return { status: r.status, body: j }; }); })
        .then(function (res) {
          if (res.status === 422 && res.body.reason === 'no_phone') {
            btn.removeAttribute('aria-busy');
            btn.disabled = false;
            var phone = form.querySelector('[name="phone"]');
            setError(phone, RULES.phone(''));
            phone.focus();
            return;
          }
          // Confirm either way: if CTM failed the lead is still in the log
          // and someone will call. The real outcome goes to GTM, so a silent
          // CTM outage shows up in GA4 instead of looking like zero demand.
          track(res.body.delivered
            ? { event: 'generate_lead', form_id: form.id, lead_status: 'delivered' }
            : { event: 'lead_failed', form_id: form.id, lead_status: res.body.reason || 'unknown' });
          finish();
        })
        .catch(function () {
          track({ event: 'lead_failed', form_id: form.id, lead_status: 'network_error' });
          finish();
        });
    });
  }

  document.querySelectorAll('form[data-lead]').forEach(initForm);

  /* ---------------------------------------------------------------------
     3. Review rails. The rail is a native scroll container; these buttons
     page it by its own visible width and grey out at either end.
     ------------------------------------------------------------------- */
  document.querySelectorAll('[data-rail]').forEach(function (btn) {
    var rail = document.getElementById(btn.getAttribute('aria-controls'));
    if (!rail) return;
    var dir = btn.getAttribute('data-rail') === 'next' ? 1 : -1;
    var still = window.matchMedia('(prefers-reduced-motion: reduce)');

    btn.addEventListener('click', function () {
      rail.scrollBy({ left: dir * rail.clientWidth * 0.9, behavior: still.matches ? 'auto' : 'smooth' });
    });

    function sync() {
      var max = rail.scrollWidth - rail.clientWidth - 2;
      btn.disabled = dir < 0 ? rail.scrollLeft <= 2 : rail.scrollLeft >= max;
    }
    rail.addEventListener('scroll', sync, { passive: true });
    window.addEventListener('resize', sync, { passive: true });
    sync();
  });

  /* ---------------------------------------------------------------------
     4. Location line (-dli). The live page's own approach: the browser asks
     ipinfo.io for its region. (A server-side lookup sees Cloudways' proxy,
     not the visitor.) The nationwide line is already rendered, so a failed
     or blocked lookup just leaves it. textContent only: third-party input.
     ------------------------------------------------------------------- */
  document.querySelectorAll('[data-geo]').forEach(function (el) {
    var token = el.getAttribute('data-geo-token');
    if (!token || !window.fetch) return;
    fetch('https://ipinfo.io/json?token=' + encodeURIComponent(token), { credentials: 'omit' })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (d) {
        if (d && d.region) el.textContent = el.getAttribute('data-geo').replace('{state}', d.region);
      })
      .catch(function () { /* keep the fallback */ });
  });

  /* ---------------------------------------------------------------------
     5. Site menu (microsite only)
     ------------------------------------------------------------------- */
  var toggle = document.querySelector('[data-nav-toggle]');
  if (toggle) {
    var nav = document.getElementById(toggle.getAttribute('aria-controls'));
    function setOpen(open) {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      nav.classList.toggle('is-open', open);
    }
    toggle.addEventListener('click', function () { setOpen(toggle.getAttribute('aria-expanded') !== 'true'); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') { setOpen(false); toggle.focus(); }
    });
    nav.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
  }

  /* ---------------------------------------------------------------------
     6. CTA events. One delegated listener; every call/verify control
     carries data-cta naming where on the page it is.
     ------------------------------------------------------------------- */
  document.addEventListener('click', function (e) {
    var a = e.target.closest('[data-cta]');
    if (!a) return;
    var href = a.getAttribute('href') || '';
    track({ event: href.indexOf('tel:') === 0 ? 'phone_click' : 'cta_click', cta: a.getAttribute('data-cta') });
  });

  /* ---------------------------------------------------------------------
     7. Landing page header. Fixed; clear over the hero, solid white once
     the page moves, as on the live pages.
     ------------------------------------------------------------------- */
  var lpTop = document.querySelector('[data-lp-top]');
  if (lpTop) {
    var setSolid = function () { lpTop.classList.toggle('is-solid', window.scrollY > 24); };
    window.addEventListener('scroll', setSolid, { passive: true });
    setSolid();
  }

  /* ---------------------------------------------------------------------
     8. Entrance animations. [data-reveal] = fade | up | zoom, and an
     optional data-reveal-delay in ms for a short stagger. Each plays once
     as it scrolls in. The head script hides them only when this file will
     run, and reduced-motion users get everything at once (see base.css).
     ------------------------------------------------------------------- */
  var reveals = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var d = en.target.getAttribute('data-reveal-delay');
        if (d) en.target.style.transitionDelay = d + 'ms';
        en.target.classList.add('is-in');
        io.unobserve(en.target);
      });
    }, { rootMargin: '0px 0px -8% 0px' });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-in'); });
  }
  window.dlReady = true;
})();
