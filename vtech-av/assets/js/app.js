/* VTECH — front-end JS. Vanilla ES modules, no jQuery. Loaded with defer.
   Modules: sticky header shadow, mobile nav, sticky-CTA reveal, project
   filters, exit-intent lead popup, and the cost estimator AJAX call. */
(function () {
  'use strict';
  var cfg = window.VTECH || {};

  /* ---- Sticky header shadow ---- */
  var header = document.querySelector('.site-header[data-sticky]');
  if (header) {
    var onScroll = function () {
      if (window.scrollY > 8) header.setAttribute('data-scrolled', '');
      else header.removeAttribute('data-scrolled');
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---- Mobile nav ---- */
  var toggle = document.querySelector('.nav-toggle');
  var mobileNav = document.getElementById('mobile-nav');
  if (toggle && mobileNav) {
    var closeNav = function () {
      mobileNav.setAttribute('hidden', '');
      document.body.classList.remove('nav-open');
      toggle.setAttribute('aria-expanded', 'false');
    };
    toggle.addEventListener('click', function () {
      var willOpen = mobileNav.hasAttribute('hidden');
      if (willOpen) { mobileNav.removeAttribute('hidden'); document.body.classList.add('nav-open'); }
      else { closeNav(); }
      toggle.setAttribute('aria-expanded', String(willOpen));
    });
    var closeBtn = mobileNav.querySelector('.mobile-nav__close');
    if (closeBtn) { closeBtn.addEventListener('click', closeNav); }
    mobileNav.addEventListener('click', function (e) {
      var a = e.target.closest('a');
      if (a && a.parentNode && a.parentNode.className.indexOf('menu-item-has-children') === -1) { closeNav(); }
    });
    document.addEventListener('keyup', function (e) {
      if ((e.key === 'Escape' || e.keyCode === 27) && !mobileNav.hasAttribute('hidden')) { closeNav(); }
    });
  }

  /* ---- Sticky CTA reveal after scrolling past hero ---- */
  var stickyCta = document.querySelector('[data-sticky-cta]');
  if (stickyCta) {
    var hero = document.querySelector('.hero, .svc-hero');
    var reveal = function () {
      var past = hero ? (hero.getBoundingClientRect().bottom < 0) : (window.scrollY > 600);
      if (past) stickyCta.removeAttribute('hidden'); else stickyCta.setAttribute('hidden', '');
    };
    window.addEventListener('scroll', reveal, { passive: true });
  }

  /* ---- Project filters (progressive enhancement) ---- */
  var filterBar = document.querySelector('[data-filter]');
  var grid = document.querySelector('[data-filter-grid]');
  if (filterBar && grid) {
    filterBar.addEventListener('click', function (e) {
      var btn = e.target.closest('.chip');
      if (!btn) return;
      filterBar.querySelectorAll('.chip').forEach(function (c) { c.classList.remove('is-active'); });
      btn.classList.add('is-active');
      var val = btn.getAttribute('data-filter-value');
      grid.querySelectorAll('[data-tags]').forEach(function (card) {
        var tags = card.getAttribute('data-tags') || '';
        card.style.display = (val === '*' || tags.split(' ').indexOf(val) > -1) ? '' : 'none';
      });
    });
  }

  /* ---- Exit-intent lead popup (once per session) ---- */
  if (document.body.dataset.exitIntent !== 'off' && !sessionStorage.getItem('vtech_exit')) {
    var fired = false;
    document.addEventListener('mouseout', function (e) {
      if (fired || e.clientY > 0 || e.relatedTarget) return;
      fired = true; sessionStorage.setItem('vtech_exit', '1');
      var popup = document.getElementById('vtech-exit-popup');
      if (popup) popup.removeAttribute('hidden');
    });
  }

  /* ---- Cost estimator (calls stubbed AJAX endpoint) ---- */
  var estForm = document.querySelector('[data-estimator]');
  if (estForm && cfg.ajaxUrl) {
    estForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var out = estForm.querySelector('[data-estimate-result]');
      var body = new URLSearchParams({
        action: 'vtech_estimate',
        nonce: cfg.nonce,
        service: estForm.service.value,
        qty: estForm.qty.value
      });
      out.textContent = 'Calculating…';
      fetch(cfg.ajaxUrl, { method: 'POST', body: body })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.success) {
            var d = res.data;
            out.innerHTML = '<strong>Estimated range:</strong> KES ' +
              d.low.toLocaleString() + ' – ' + d.high.toLocaleString() +
              '<br><small>' + d.note + '</small>';
          } else { out.textContent = (res.data && res.data.message) || 'Please try again.'; }
        })
        .catch(function () { out.textContent = 'Network error — please call us instead.'; });
    });
  }

  /* ---- Count-up stats when in view ---- */
  var nums = document.querySelectorAll('[data-countup]');
  if (nums.length && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = en.target, target = parseFloat(el.dataset.countup), start = null;
        var step = function (ts) {
          if (!start) start = ts;
          var p = Math.min((ts - start) / 1200, 1);
          el.textContent = Math.floor(p * target).toLocaleString() + (el.dataset.suffix || '');
          if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step); io.unobserve(el);
      });
    }, { threshold: 0.4 });
    nums.forEach(function (n) { io.observe(n); });
  }

  /* ---- Quote/contact form: native POST (no interception) ---- */
  var qf = document.getElementById('vtech-quote-form');
  if (qf) {
    qf.addEventListener('submit', function () {
      var btn = qf.querySelector('button[type=submit]');
      if (btn) { btn.disabled = true; btn.textContent = 'Sending...'; }
    });
  }


  /* ---- Multi-step forms (consultation + hire request) ----
     Shared engine: per-step validation (incl. radio groups + email format),
     inline error message, no page jump on load, smooth scroll only when the
     visitor moves between steps, double-submit guard, and package
     pre-selection from ?package=slug on the hire form. */
  function initStepForm(form, opts) {
    opts = opts || {};
    var steps = Array.prototype.slice.call(form.querySelectorAll('.cform__step'));
    if (!steps.length) return;
    var bar = form.querySelector('[data-cform-bar]');
    var note = form.querySelector('[data-cform-note]');
    var prevBtn = form.querySelector('[data-cform-prev]');
    var nextBtn = form.querySelector('[data-cform-next]');
    var subBtn = form.querySelector('[data-cform-submit]');
    var status = form.querySelector('.cf-status');
    var cur = 0;

    function setStatus(msg, isError) {
      if (!status) return;
      status.textContent = msg || '';
      status.classList.toggle('is-error', Boolean(isError));
    }

    function show(i, scroll) {
      cur = Math.max(0, Math.min(i, steps.length - 1));
      steps.forEach(function (s, idx) { s.classList.toggle('is-active', idx === cur); });
      if (bar) bar.style.width = ((cur + 1) / steps.length * 100) + '%';
      if (note) note.textContent = 'Step ' + (cur + 1) + ' of ' + steps.length;
      if (prevBtn) prevBtn.hidden = cur === 0;
      if (nextBtn) nextBtn.hidden = cur === steps.length - 1;
      if (subBtn) subBtn.hidden = cur !== steps.length - 1;
      setStatus('');
      if (scroll) {
        var top = form.getBoundingClientRect().top + window.pageYOffset - 110;
        window.scrollTo({ top: top, behavior: 'smooth' });
        var first = steps[cur].querySelector('input:not([type=hidden]),select,textarea');
        if (first) { setTimeout(function () { try { first.focus({ preventScroll: true }); } catch (e) { first.focus(); } }, 350); }
      }
    }

    function validateStep() {
      var active = steps[cur];
      active.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
      var fields = active.querySelectorAll('input,select,textarea');
      for (var k = 0; k < fields.length; k++) {
        var f = fields[k];
        if (f.type === 'hidden' || f.closest('[hidden]')) continue;
        var bad = false;
        if (f.type === 'radio' && f.required) {
          bad = !active.querySelector('input[type=radio][name="' + f.name + '"]:checked');
        } else if (f.type === 'checkbox') {
          bad = f.required && !f.checked;
        } else if (f.required && !String(f.value).trim()) {
          bad = true;
        } else if (String(f.value).trim() && typeof f.checkValidity === 'function' && !f.checkValidity()) {
          bad = true;
        }
        if (bad) {
          var wrap = f.closest('.cf-field,.cf-check') || f;
          wrap.classList.add('is-invalid');
          var label = wrap.querySelector('label,.cf-label');
          var name = label ? label.textContent.replace(/\*/g, '').trim() : 'this field';
          var msg = (f.type === 'email' && String(f.value).trim()) ? 'Please enter a valid email address.'
            : (f.type === 'checkbox' ? 'Please tick the confirmation box to continue.' : 'Please complete: ' + name + '.');
          setStatus(msg, true);
          try { f.focus({ preventScroll: false }); } catch (e) { f.focus(); }
          return false;
        }
      }
      return true;
    }

    form.addEventListener('input', function (e) {
      var w = e.target.closest('.is-invalid');
      if (w) { w.classList.remove('is-invalid'); setStatus(''); }
    });
    form.addEventListener('change', function (e) {
      var w = e.target.closest('.is-invalid');
      if (w) { w.classList.remove('is-invalid'); setStatus(''); }
      if (opts.onChange) opts.onChange(e);
    });

    if (nextBtn) nextBtn.addEventListener('click', function () { if (validateStep()) { if (opts.beforeNext) opts.beforeNext(); show(cur + 1, true); } });
    if (prevBtn) prevBtn.addEventListener('click', function () { show(cur - 1, true); });

    // Enter key inside a text field advances instead of submitting early.
    form.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && e.target.tagName === 'INPUT' && cur < steps.length - 1) {
        e.preventDefault();
        if (validateStep()) { if (opts.beforeNext) opts.beforeNext(); show(cur + 1, true); }
      }
    });

    var sending = false;
    form.addEventListener('submit', function (e) {
      if (cur < steps.length - 1) {
        e.preventDefault();
        if (validateStep()) { if (opts.beforeNext) opts.beforeNext(); show(cur + 1, true); }
        return;
      }
      if (sending || !validateStep()) { e.preventDefault(); return; }
      sending = true;
      if (subBtn) { subBtn.disabled = true; subBtn.textContent = 'Submitting...'; }
      setStatus('Sending your request...');
    });

    show(0, false);
  }

  (function () {
    var form = document.getElementById('vtech-consult-form');
    if (!form) return;
    function applyAdaptive() {
      var sys = Array.prototype.slice.call(form.querySelectorAll('[data-cform-systems] input:checked')).map(function (c) { return c.getAttribute('data-system'); });
      form.querySelectorAll('.cf-sub[data-when]').forEach(function (sub) {
        sub.hidden = sys.indexOf(sub.getAttribute('data-when')) === -1;
      });
      var sectorStep = form.querySelector('[data-step="5"]');
      if (sectorStep) {
        var anyVisible = Array.prototype.slice.call(sectorStep.querySelectorAll('.cf-sub')).some(function (s) { return !s.hidden; });
        var emptyNote = sectorStep.querySelector('[data-sector-empty]');
        if (emptyNote) emptyNote.hidden = anyVisible;
      }
    }
    initStepForm(form, {
      beforeNext: applyAdaptive,
      onChange: function (e) { if (e.target.closest('[data-cform-systems]')) applyAdaptive(); }
    });
    applyAdaptive();
  })();

  (function () {
    var form = document.getElementById('vtech-hire-form');
    if (!form) return;
    // Pre-select the package chosen on a package page (?package=gold-package).
    try {
      var pkg = new URLSearchParams(window.location.search).get('package');
      if (pkg) {
        var key = pkg.split('-')[0].toLowerCase();
        form.querySelectorAll('input[name="package_interest"]').forEach(function (r) {
          if (r.value.toLowerCase().indexOf(key) === 0) { r.checked = true; }
        });
        var desc = form.querySelector('[name="event_desc"]');
        if (desc && !desc.value) { desc.value = 'Interested in the ' + key.charAt(0).toUpperCase() + key.slice(1) + ' package.'; }
      }
    } catch (e) { /* older browsers: skip pre-selection */ }
    initStepForm(form);
  })();



  /* ---- Form extras: labelled stepper, date limits, draft saving ---- */
  (function () {
    var forms = document.querySelectorAll('#vtech-hire-form, #vtech-consult-form, #vtech-quote-form');
    var justSent = /[?&]vtech_sent=1/.test(window.location.search);
    var success = document.querySelector('.cf-success');
    if (success) { try { success.focus({ preventScroll: true }); } catch (e) { /* ignore */ } }

    // Dates can't be in the past.
    var today = new Date();
    var iso = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
    document.querySelectorAll('.cform input[type=date]').forEach(function (d) { if (!d.min) d.min = iso; });

    forms.forEach(function (form) {
      var key = 'vtech_draft_' + form.id;

      // Labelled stepper built from each step's legend.
      var steps = form.querySelectorAll('.cform__step');
      if (steps.length > 1) {
        var ol = document.createElement('ol');
        ol.className = 'cform__steps';
        ol.setAttribute('aria-label', 'Form progress');
        steps.forEach(function (s, i) {
          var lg = s.querySelector('legend');
          var li = document.createElement('li');
          var sp = document.createElement('span');
          sp.textContent = lg ? lg.textContent.replace(/^\s*\d+\.\s*/, '') : 'Step ' + (i + 1);
          li.appendChild(sp);
          li.setAttribute('data-goto', String(i));
          ol.appendChild(li);
        });
        var anchor = form.querySelector('.cform__progress') || form.firstElementChild;
        form.insertBefore(ol, anchor);
        form.classList.add('has-stepper');
        var sync = function () {
          var cur = 0;
          steps.forEach(function (s, i) { if (s.classList.contains('is-active')) cur = i; });
          ol.querySelectorAll('li').forEach(function (li, i) {
            li.classList.toggle('is-current', i === cur);
            li.classList.toggle('is-done', i < cur);
            if (i === cur) li.setAttribute('aria-current', 'step'); else li.removeAttribute('aria-current');
          });
        };
        sync();
        new MutationObserver(sync).observe(form, { attributes: true, subtree: true, attributeFilter: ['class'] });
        // Click a completed step to go back to it.
        ol.addEventListener('click', function (e) {
          var li = e.target.closest('li.is-done');
          if (!li) return;
          var target = parseInt(li.getAttribute('data-goto'), 10);
          var prev = form.querySelector('[data-cform-prev]');
          var guard = 0;
          var active = function () { var c = 0; steps.forEach(function (s, i) { if (s.classList.contains('is-active')) c = i; }); return c; };
          while (prev && active() > target && guard < 10) { prev.click(); guard++; }
        });
      }

      // Draft saving so a refresh or a lost connection doesn't lose answers.
      var store;
      try { store = window.localStorage; } catch (e) { store = null; }
      if (!store) return;
      if (justSent) { store.removeItem(key); return; }
      var skip = /^(action|form_kind|_vtech_return|vtech_form_nonce|vtech_quote_nonce|_wp_http_referer|website|vtech_ts|consent)$/;
      var note = document.createElement('p');
      note.className = 'cform__saved';
      note.setAttribute('aria-live', 'polite');
      (form.querySelector('.cform__nav') || form).insertAdjacentElement('afterend', note);

      try {
        var saved = JSON.parse(store.getItem(key) || 'null');
        if (saved && saved.t && (Date.now() - saved.t) < 7 * 864e5) {
          Object.keys(saved.v).forEach(function (name) {
            var val = saved.v[name];
            form.querySelectorAll('[name="' + name.replace(/"/g, '') + '"]').forEach(function (el) {
              if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = Array.isArray(val) ? val.indexOf(el.value) > -1 : el.value === val;
              } else if (!el.value) {
                el.value = val;
              }
            });
          });
          note.textContent = 'We restored the answers you started earlier.';
          form.dispatchEvent(new Event('change', { bubbles: true }));
        }
      } catch (e) { /* ignore corrupt drafts */ }

      var timer;
      form.addEventListener('input', save);
      form.addEventListener('change', save);
      function save() {
        clearTimeout(timer);
        timer = setTimeout(function () {
          var v = {};
          form.querySelectorAll('input,select,textarea').forEach(function (el) {
            if (!el.name || skip.test(el.name) || el.type === 'hidden') return;
            if (el.type === 'checkbox') {
              if (!el.checked) return;
              if (el.name.slice(-2) === '[]') { (v[el.name] = v[el.name] || []).push(el.value); } else { v[el.name] = el.value; }
            } else if (el.type === 'radio') {
              if (el.checked) v[el.name] = el.value;
            } else if (el.value) {
              v[el.name] = el.value;
            }
          });
          try { store.setItem(key, JSON.stringify({ t: Date.now(), v: v })); note.textContent = 'Your answers are saved on this device.'; } catch (e) { /* storage full */ }
        }, 400);
      }
    });
  })();

  /* ---- Map facade: load Google Maps (heavy) only when asked ---- */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.map-facade__btn');
    if (!btn) return;
    var box = btn.closest('.map-facade');
    var src = box && box.getAttribute('data-map-src');
    if (!src) return;
    var f = document.createElement('iframe');
    f.src = src;
    f.title = box.getAttribute('data-map-title') || 'Map';
    f.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
    f.setAttribute('allowfullscreen', '');
    box.innerHTML = '';
    box.appendChild(f);
    box.classList.add('is-loaded');
  });


  /* ---- WhatsApp order / consultation popup ---- */
  (function () {
    var cfg = window.VTECH_WA || {};
    var modal = document.getElementById('wa-modal');
    if (!modal || !cfg.enabled) return;
    var form = modal.querySelector('[data-wa-form]');
    var done = modal.querySelector('[data-wa-done]');
    var errBox = modal.querySelector('[data-wa-error]');
    var submit = modal.querySelector('[data-wa-submit]');
    var titleEl = modal.querySelector('.wa-modal__title');
    var summary = modal.querySelector('[data-wa-summary]');
    var openBtn = modal.querySelector('[data-wa-open]');
    var copyBtn = modal.querySelector('[data-wa-copy]');
    var ctx = { kind: 'enquiry', item: '', price: '', extra: '' };
    var lastFocus = null;
    var message = '';

    var today = new Date();
    var iso = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
    modal.querySelectorAll('input[type=date]').forEach(function (d) { d.min = iso; });

    function val(name) {
      var els = form.querySelectorAll('[name="' + name + '"]');
      if (!els.length) return '';
      if (els[0].type === 'radio') { var c = form.querySelector('[name="' + name + '"]:checked'); return c ? c.value : ''; }
      return String(els[0].value || '').trim();
    }
    function niceDate(v) {
      if (!v) return '';
      var d = new Date(v + 'T12:00:00');
      if (isNaN(d)) return v;
      return d.toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
    }
    function fullPhone() {
      var cc = val('cc');
      var raw = val('phone');
      var digits = raw.replace(/\D/g, '');
      if (!cc) return raw.charAt(0) === '+' ? '+' + digits : (digits ? '+' + digits : '');
      if (digits.indexOf(cc) === 0 && digits.length > cc.length + 6) return '+' + digits;
      digits = digits.replace(/^0+/, '');
      return '+' + cc + digits;
    }
    function phoneOk() {
      var d = fullPhone().replace(/\D/g, '');
      if (val('cc') === '254') return /^254[17]\d{8}$/.test(d);
      return d.length >= 9 && d.length <= 15;
    }

    function setKind(kind) {
      ctx.kind = kind;
      form.querySelectorAll('[data-wa-for]').forEach(function (g) {
        var on = (' ' + g.getAttribute('data-wa-for') + ' ').indexOf(' ' + kind + ' ') > -1;
        g.hidden = !on;
        g.querySelectorAll('input,select,textarea').forEach(function (i) { i.disabled = !on; });
      });
      if (titleEl && cfg.titles && cfg.titles[kind]) titleEl.textContent = cfg.titles[kind];
      var nl = form.querySelector('[data-wa-notes-label]');
      if (nl) nl.textContent = kind === 'consult' ? 'Tell us about the project *' : (kind === 'enquiry' ? 'Your message *' : 'Anything else we should know?');
    }

    function open(trigger) {
      lastFocus = trigger || document.activeElement;
      ctx.item = trigger ? (trigger.getAttribute('data-wa-item') || '') : '';
      ctx.price = trigger ? (trigger.getAttribute('data-wa-price') || '') : '';
      ctx.extra = trigger ? (trigger.getAttribute('data-wa-extra') || '') : '';
      setKind(trigger ? (trigger.getAttribute('data-wa-order') || 'enquiry') : 'enquiry');
      if (ctx.item) {
        summary.hidden = false;
        summary.querySelector('[data-wa-summary-label]').textContent = ctx.kind === 'consult' ? 'Service' : (ctx.kind === 'package' ? 'Package' : 'Item');
        summary.querySelector('[data-wa-summary-item]').textContent = ctx.item;
        summary.querySelector('[data-wa-summary-meta]').textContent = [ctx.price, ctx.extra].filter(Boolean).join(' · ');
        if (ctx.kind === 'consult') {
          var sel = form.querySelector('[name=service]');
          if (sel) Array.prototype.forEach.call(sel.options, function (o) { if (o.text.toLowerCase().indexOf(ctx.item.toLowerCase().split(' ')[0]) > -1 && !sel.value) sel.value = o.text; });
        }
      } else { summary.hidden = true; }
      form.hidden = false; done.hidden = true; errBox.hidden = true;
      submit.disabled = false;
      modal.hidden = false;
      document.documentElement.classList.add('wa-noscroll');
      requestAnimationFrame(function () { modal.classList.add('is-open'); });
      try {
        var saved = JSON.parse(localStorage.getItem('vtech_wa_contact') || 'null');
        if (saved) { ['name', 'organisation', 'phone', 'email', 'cc'].forEach(function (k) { var el = form.querySelector('[name="' + k + '"]'); if (el && !el.value && saved[k]) el.value = saved[k]; }); }
      } catch (e) { /* ignore */ }
      setTimeout(function () { var f = form.querySelector('input:not([type=hidden]):not([tabindex="-1"])'); if (f) f.focus(); }, 60);
    }
    function close() {
      modal.classList.remove('is-open');
      document.documentElement.classList.remove('wa-noscroll');
      setTimeout(function () { modal.hidden = true; }, 180);
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    function bullet(label, v) { return v ? '\u2022 ' + label + ': ' + v : null; }
    function build(ref) {
      var L = [];
      L.push(cfg.greeting || 'Hello,');
      L.push('');
      if (ctx.kind === 'package') L.push('I would like to book the *' + (ctx.item || 'hire package') + '*.');
      else if (ctx.kind === 'item') L.push('I would like to order *' + (ctx.item || 'equipment') + '*.');
      else if (ctx.kind === 'consult') L.push('I would like to book a *consultation*' + (ctx.item ? ' for *' + ctx.item + '*' : '') + '.');
      else L.push('I have an enquiry' + (val('topic') ? ' about *' + val('topic').toLowerCase() + '*' : '') + '.');
      L.push('');
      if (ctx.kind === 'package' || ctx.kind === 'item') {
        L.push('*ORDER DETAILS*');
        [bullet(ctx.kind === 'package' ? 'Package' : 'Item', ctx.item), bullet('Listed price', ctx.price), bullet('Capacity', ctx.extra),
          ctx.kind === 'item' ? bullet('Quantity', val('quantity')) : null,
          bullet('Event date', niceDate(val('event_date'))), bullet('Number of days', val('days')), bullet('Venue', val('venue')),
          bullet('Event type', val('event_type')), bullet('Expected guests', val('guests')), bullet('Technician on site', val('technician'))
        ].forEach(function (x) { if (x) L.push(x); });
        L.push('');
      } else if (ctx.kind === 'consult') {
        L.push('*PROJECT DETAILS*');
        [bullet('Service', val('service') || ctx.item), bullet('Location', val('location')), bullet('Preferred date', niceDate(val('preferred_date'))), bullet('Follow-up', val('follow_up'))]
          .forEach(function (x) { if (x) L.push(x); });
        L.push('');
      }
      L.push('*CONTACT*');
      [bullet('Name', val('name')), bullet('Organisation', val('organisation')), bullet('Phone', fullPhone()), bullet('Email', val('email'))]
        .forEach(function (x) { if (x) L.push(x); });
      if (val('notes')) { L.push(''); L.push(ctx.kind === 'enquiry' ? '*MESSAGE*' : '*NOTES*'); L.push(val('notes')); }
      L.push('');
      if (ref) L.push('Ref: ' + ref);
      L.push('Page: ' + window.location.href.split('#')[0].split('?')[0]);
      if (cfg.signoff) L.push('_' + cfg.signoff + '_');
      return L.join('\n');
    }

    function showError(msg, field) {
      errBox.textContent = msg; errBox.hidden = false;
      if (field) { var w = field.closest('.wa-field') || field; w.classList.add('is-invalid'); field.focus(); }
    }

    form.addEventListener('input', function (e) { var w = e.target.closest('.is-invalid'); if (w) w.classList.remove('is-invalid'); errBox.hidden = true; });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      form.querySelectorAll('.is-invalid').forEach(function (x) { x.classList.remove('is-invalid'); });
      if (val('name').length < 2) return showError('Please enter your full name.', form.querySelector('[name=name]'));
      if (!phoneOk()) return showError('Please enter a valid phone number' + (val('cc') === '254' ? ' (e.g. 0712 345 678).' : '.'), form.querySelector('[name=phone]'));
      var em = form.querySelector('[name=email]');
      if (em.value && !em.checkValidity()) return showError('Please enter a valid email address or leave it blank.', em);
      var req = form.querySelectorAll('[data-required]:not(:disabled)');
      for (var i = 0; i < req.length; i++) {
        if (!String(req[i].value).trim()) { var lb = req[i].closest('.wa-field').querySelector('label'); return showError('Please complete: ' + (lb ? lb.textContent.replace('*', '').trim() : 'this field') + '.', req[i]); }
      }
      if ((ctx.kind === 'consult' || ctx.kind === 'enquiry') && val('notes').length < 5) return showError(ctx.kind === 'consult' ? 'Please tell us a little about the project.' : 'Please type your message.', form.querySelector('[name=notes]'));

      try { localStorage.setItem('vtech_wa_contact', JSON.stringify({ name: val('name'), organisation: val('organisation'), phone: val('phone'), email: val('email'), cc: val('cc') })); } catch (e2) { /* ignore */ }

      submit.disabled = true; submit.textContent = 'Preparing your message...';
      // Open a tab synchronously (keeps popup blockers happy on desktop), fill it once the reference is back.
      var isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
      var win = null;
      if (!isMobile) { try { win = window.open('', '_blank'); } catch (e3) { win = null; } }

      var payload = {
        kind: ctx.kind, item: ctx.item, price: ctx.price, name: val('name'), phone: fullPhone(), email: val('email'),
        organisation: val('organisation'), event_date: val('event_date'), days: val('days'), venue: val('venue'), event_type: val('event_type'),
        guests: val('guests'), quantity: val('quantity'), technician: val('technician'), service: val('service'), location: val('location'),
        preferred_date: val('preferred_date'), follow_up: val('follow_up'), topic: val('topic'), notes: val('notes'),
        page: window.location.href.split('#')[0], website: val('website')
      };
      var finished = false;
      function finish(ref) {
        if (finished) return; finished = true;
        message = build(ref);
        var url = 'https://wa.me/' + String(cfg.number || '').replace(/\D/g, '') + '?text=' + encodeURIComponent(message);
        openBtn.href = url;
        done.querySelector('[data-wa-done-ref]').textContent = ref ? 'Reference: ' + ref : '';
        form.hidden = true; done.hidden = false;
        submit.disabled = false; submit.textContent = submit.getAttribute('data-label') || submit.textContent;
        if (window.gtag) { try { window.gtag('event', 'whatsapp_lead', { lead_type: ctx.kind, item: ctx.item }); } catch (e4) { /* ignore */ } }
        if (win && !win.closed) { win.location.href = url; } else if (isMobile) { window.location.href = url; }
      }
      submit.setAttribute('data-label', submit.getAttribute('data-label') || 'Continue to WhatsApp');
      var timer = setTimeout(function () { finish(''); }, 3500);
      if (cfg.endpoint && window.fetch) {
        fetch(cfg.endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload), credentials: 'same-origin' })
          .then(function (r) { return r.json(); })
          .then(function (j) { clearTimeout(timer); finish(j && j.ref ? j.ref : ''); })
          .catch(function () { clearTimeout(timer); finish(''); });
      } else { clearTimeout(timer); finish(''); }
    });

    copyBtn.addEventListener('click', function () {
      var ok = function () { copyBtn.textContent = 'Copied. Paste it into WhatsApp.'; };
      if (navigator.clipboard) navigator.clipboard.writeText(message).then(ok, function () {});
    });

    modal.addEventListener('click', function (e) { if (e.target.closest('[data-wa-close]')) close(); });
    document.addEventListener('keydown', function (e) {
      if (modal.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'Tab') {
        var f = Array.prototype.filter.call(modal.querySelectorAll('a[href],button,input,select,textarea'), function (x) { return !x.disabled && x.offsetParent !== null && x.tabIndex !== -1; });
        if (!f.length) return;
        if (e.shiftKey && document.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
        else if (!e.shiftKey && document.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
      }
    });

    // Triggers: explicit data-wa-order buttons, plus every plain wa.me link on the site.
    document.addEventListener('click', function (e) {
      var t = e.target.closest('[data-wa-order], a[href*="wa.me/"], a[href*="api.whatsapp.com"]');
      if (!t || t.hasAttribute('data-wa-direct') || t.closest('#wa-modal') || t.closest('.cf-success')) return;
      if (e.ctrlKey || e.metaKey || e.shiftKey) return;
      e.preventDefault();
      if (!t.hasAttribute('data-wa-order')) t.setAttribute('data-wa-order', 'enquiry');
      open(t);
    });
  })();

  /* ---- Brands carousel (About page) ---- */
  (function () {
    var wrap = document.querySelector('[data-brand-carousel]');
    if (!wrap) return;
    var track = wrap.querySelector('[data-brand-track]');
    var prev = wrap.querySelector('[data-brand-prev]');
    var next = wrap.querySelector('[data-brand-next]');
    if (!track) return;
    function step() {
      var item = track.querySelector('.brand-carousel__item');
      var w = item ? item.getBoundingClientRect().width + 20 : 200;
      return Math.max(w, Math.floor(track.clientWidth * 0.8));
    }
    if (prev) prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
    if (next) next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });
  })();

})();
