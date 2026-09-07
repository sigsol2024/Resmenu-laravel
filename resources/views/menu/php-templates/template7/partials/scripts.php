<?php
$menuSearchIndex = $menuSearchIndex ?? [];
$fullMenuUrlJs = $fullMenuUrl ?? '';
?>
<script>
(function () {
  var headerEl = document.getElementById('t7-header');

  function headerOffset() {
    return headerEl ? headerEl.offsetHeight + 12 : 88;
  }

  function scrollToEl(el, behavior) {
    if (!el) return;
    var top = el.getBoundingClientRect().top + window.pageYOffset - headerOffset();
    window.scrollTo({ top: Math.max(0, top), behavior: behavior || 'smooth' });
  }

  /* —— Menu drawer (section pages) —— */
  var menuToggle = document.getElementById('t7-menu-toggle');
  var menuDrawer = document.getElementById('t7-menu-drawer');
  var menuBackdrop = document.getElementById('t7-menu-backdrop');
  var menuClose = document.getElementById('t7-menu-close');

  function openMenuDrawer() {
    if (!menuDrawer || !menuBackdrop) return;
    menuDrawer.classList.remove('hidden');
    menuDrawer.classList.add('is-open');
    menuBackdrop.classList.remove('hidden');
    document.body.classList.add('t7-drawer-open');
    if (menuToggle) menuToggle.setAttribute('aria-expanded', 'true');
  }

  function closeMenuDrawer() {
    if (!menuDrawer || !menuBackdrop) return;
    menuDrawer.classList.add('hidden');
    menuDrawer.classList.remove('is-open');
    menuBackdrop.classList.add('hidden');
    document.body.classList.remove('t7-drawer-open');
    if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
  }

  if (menuToggle) menuToggle.addEventListener('click', openMenuDrawer);
  if (menuClose) menuClose.addEventListener('click', closeMenuDrawer);
  if (menuBackdrop) menuBackdrop.addEventListener('click', closeMenuDrawer);
  if (menuDrawer) {
    menuDrawer.addEventListener('click', function (e) { e.stopPropagation(); });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    closeMenuDrawer();
    var sug = document.getElementById('search-suggestions');
    var inp = document.getElementById('menu-search-input');
    if (sug && !sug.classList.contains('hidden')) {
      sug.classList.add('hidden');
      if (inp) inp.setAttribute('aria-expanded', 'false');
    }
  });

  document.querySelectorAll('.t7-menu-item-jump').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var href = this.getAttribute('href');
      if (!href || href.charAt(0) !== '#') return;
      var target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      closeMenuDrawer();
      scrollToEl(target);
      if (history.replaceState) history.replaceState(null, '', href);
    });
  });

  /* —— Deep-link highlight on section pages —— */
  function clearItemHighlight() {
    document.querySelectorAll('.menu-item-card.is-dimmed').forEach(function (c) {
      c.classList.remove('is-dimmed');
    });
    document.querySelectorAll('.menu-item-card.is-highlight').forEach(function (c) {
      c.classList.remove('is-highlight');
    });
  }

  function highlightItem(el) {
    if (!el) return;
    var cards = document.querySelectorAll('.menu-item-card');
    cards.forEach(function (c) {
      c.classList.toggle('is-dimmed', c !== el);
      c.classList.toggle('is-highlight', c === el);
    });
    scrollToEl(el);
    window.setTimeout(function () {
      clearItemHighlight();
    }, 4200);
  }

  function resolveItemTarget() {
    var hash = (location.hash || '').replace(/^#/, '');
    var params = new URLSearchParams(location.search);
    var itemParam = params.get('item');
    var anchor = '';

    if (hash.indexOf('item-') === 0) {
      anchor = hash;
    } else if (itemParam) {
      anchor = 'item-' + String(itemParam).replace(/^item-/, '');
      if (history.replaceState) {
        var clean = location.pathname + '#item-' + String(itemParam).replace(/^item-/, '');
        history.replaceState(null, '', clean);
      }
    }

    if (!anchor) return null;
    return document.getElementById(anchor);
  }

  function runDeepLink() {
    var el = resolveItemTarget();
    if (el) {
      window.setTimeout(function () { highlightItem(el); }, 80);
    }
  }

  if (document.body && document.body.getAttribute('data-t7-view') === 'section') {
    runDeepLink();
    window.addEventListener('hashchange', runDeepLink);
  }

  /* —— Landing search autocomplete —— */
  var searchIndex = <?php
    $t7SearchJson = json_encode(
        $menuSearchIndex,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | (defined('JSON_INVALID_UTF8_SUBSTITUTE') ? JSON_INVALID_UTF8_SUBSTITUTE : 0)
    );
    echo ($t7SearchJson === false) ? '[]' : $t7SearchJson;
  ?>;
  var fullMenuUrl = <?php
    $t7MenuJson = json_encode($fullMenuUrlJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    echo ($t7MenuJson === false) ? '""' : $t7MenuJson;
  ?>;
  var searchInput = document.getElementById('menu-search-input');
  var clearBtn = document.getElementById('clear-search-btn');
  var suggestions = document.getElementById('search-suggestions');

  if (!searchInput || !suggestions || !Array.isArray(searchIndex)) return;

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function formatPrice(price) {
    var n = Number(price);
    if (!n) return '';
    return '₦' + n.toLocaleString();
  }

  function itemUrl(entry) {
    var base = (fullMenuUrl || '').replace(/\/$/, '');
    return base + '/' + encodeURIComponent(entry.section_slug) + '#item-' + encodeURIComponent(entry.slug);
  }

  function setSuggestionsOpen(open) {
    suggestions.classList.toggle('hidden', !open);
    searchInput.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  function renderSuggestions(query) {
    var q = String(query || '').toLowerCase().trim();
    if (clearBtn) clearBtn.classList.toggle('hidden', q.length === 0);

    if (q.length < 1) {
      setSuggestionsOpen(false);
      suggestions.innerHTML = '';
      return;
    }

    var matches = [];
    for (var i = 0; i < searchIndex.length; i++) {
      var row = searchIndex[i];
      var name = String(row.name || '').toLowerCase();
      if (name.indexOf(q) !== -1) {
        matches.push(row);
        if (matches.length >= 12) break;
      }
    }

    if (!matches.length) {
      suggestions.innerHTML = '<p class="px-4 py-3 type-desc text-stone-500 text-sm">No matching dishes</p>';
      setSuggestionsOpen(true);
      return;
    }

    suggestions.innerHTML = matches.map(function (m) {
      var price = formatPrice(m.price);
      var href = itemUrl(m);
      return '<a role="option" class="suggestion-item block w-full text-left px-4 py-3 hover:bg-white/5 border-b border-white/5 last:border-0 flex justify-between gap-3 min-h-[44px] items-center" href="' + escapeHtml(href) + '">' +
        '<span class="font-serif-luxury text-sm text-white">' + escapeHtml(m.name || '') + '</span>' +
        '<span class="text-champagne-gold text-xs shrink-0">' + escapeHtml(price) + '</span></a>';
    }).join('');
    setSuggestionsOpen(true);
  }

  searchInput.addEventListener('input', function (e) {
    renderSuggestions(e.target.value);
  });

  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      searchInput.value = '';
      renderSuggestions('');
      searchInput.focus();
    });
  }

  document.addEventListener('click', function (e) {
    if (!suggestions.contains(e.target) && e.target !== searchInput) {
      setSuggestionsOpen(false);
    }
  });
})();
</script>
