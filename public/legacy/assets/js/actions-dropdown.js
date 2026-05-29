/**
 * Global Actions Dropdown (portal) – prevents clipping by overflow:hidden parents.
 * Moves .actions-dropdown into body when open, positions with fixed, closes on outside click / Escape.
 */
(function () {
  const PORTAL_CLASS = 'resmenu-portal';
  const TRIGGER_SELECTOR = '.actions-btn';

  let activeDropdown = null;
  let activePlaceholder = null;
  let activeTrigger = null;

  function measureDropdown(dropdown) {
    dropdown.style.display = 'block';
    dropdown.style.visibility = 'hidden';
    var width = dropdown.offsetWidth;
    var height = dropdown.offsetHeight;
    dropdown.style.visibility = '';
    return {
      width: width || 180,
      height: height || 40,
    };
  }

  function positionPortal(trigger, dropdown) {
    var rect = trigger.getBoundingClientRect();
    var viewportH = window.innerHeight;
    var viewportW = window.innerWidth;
    var gap = 6;
    var size = measureDropdown(dropdown);

    dropdown.classList.add(PORTAL_CLASS);
    dropdown.style.position = 'fixed';
    dropdown.style.zIndex = '99999';
    dropdown.style.right = 'auto';
    dropdown.style.marginRight = '0';
    dropdown.style.display = 'block';
    dropdown.style.visibility = 'visible';

    var left = rect.left - size.width - gap;
    if (left < 8) {
      left = rect.right + gap;
    }
    if (left + size.width > viewportW - 8) {
      left = Math.max(8, viewportW - size.width - 8);
    }
    dropdown.style.left = left + 'px';

    var top = rect.top;
    if (top + size.height > viewportH - 8) {
      var topAbove = rect.top - size.height - gap;
      if (topAbove >= 8) {
        top = topAbove;
      } else {
        top = Math.max(8, viewportH - size.height - 8);
      }
    }
    if (top < 8) {
      top = 8;
    }
    dropdown.style.top = top + 'px';
  }

  function openDropdown(trigger) {
    if (!trigger) return;
    if (activeDropdown && activeTrigger === trigger) {
      closeDropdown();
      return;
    }
    var dropdown = trigger.nextElementSibling;
    if (!dropdown || !dropdown.classList.contains('actions-dropdown')) {
      closeDropdown();
      return;
    }

    closeDropdown();

    var parent = dropdown.parentNode;
    var placeholder = document.createElement('span');
    placeholder.setAttribute('aria-hidden', 'true');
    placeholder.style.display = 'none';
    parent.insertBefore(placeholder, dropdown);
    document.body.appendChild(dropdown);

    dropdown.classList.add('show');
    positionPortal(trigger, dropdown);

    activeDropdown = dropdown;
    activePlaceholder = placeholder;
    activeTrigger = trigger;

    setTimeout(function () {
      document.addEventListener('click', onDocumentClick);
      document.addEventListener('keydown', onEscape);
      window.addEventListener('resize', onReposition);
      window.addEventListener('scroll', onReposition, true);
    }, 0);
  }

  function closeDropdown() {
    window.removeEventListener('resize', onReposition);
    window.removeEventListener('scroll', onReposition, true);
    document.removeEventListener('click', onDocumentClick);
    document.removeEventListener('keydown', onEscape);

    if (activeDropdown && activePlaceholder) {
      activeDropdown.classList.remove('show', PORTAL_CLASS);
      activeDropdown.style.position = '';
      activeDropdown.style.zIndex = '';
      activeDropdown.style.left = '';
      activeDropdown.style.top = '';
      activeDropdown.style.right = '';
      activeDropdown.style.marginRight = '';
      activeDropdown.style.display = '';
      activeDropdown.style.visibility = '';
      activePlaceholder.parentNode.insertBefore(activeDropdown, activePlaceholder);
      activePlaceholder.parentNode.removeChild(activePlaceholder);
      activeDropdown = null;
      activePlaceholder = null;
      activeTrigger = null;
    }

    document.querySelectorAll(TRIGGER_SELECTOR + '.active-dropdown').forEach(function (t) {
      t.classList.remove('active-dropdown');
    });
  }

  function onReposition() {
    if (activeDropdown && activeTrigger) {
      positionPortal(activeTrigger, activeDropdown);
    }
  }

  function onDocumentClick(e) {
    if (!activeDropdown) return;
    var t = e.target;
    if (activeDropdown.contains(t)) return;
    if (activeTrigger && activeTrigger.contains(t)) return;
    closeDropdown();
  }

  function onEscape(e) {
    if (e.key === 'Escape') closeDropdown();
  }

  document.addEventListener('click', function (e) {
    var trigger = e.target.closest(TRIGGER_SELECTOR);
    if (!trigger) return;
    e.preventDefault();
    e.stopPropagation();
    document.querySelectorAll(TRIGGER_SELECTOR).forEach(function (t) {
      t.classList.remove('active-dropdown');
    });
    trigger.classList.add('active-dropdown');
    openDropdown(trigger);
  });

  window.ResmenuActionsDropdown = { open: openDropdown, close: closeDropdown };
})();
