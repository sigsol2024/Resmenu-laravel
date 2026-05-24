<script>
(function () {
  if (typeof window.getCookie !== 'function') {
    window.getCookie = function (name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
      return null;
    };
  }
  if (typeof window.setCookie !== 'function') {
    window.setCookie = function (name, value, days) {
      const expires = new Date();
      expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
      document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    };
  }
  if (typeof window.toggleMobile !== 'function') {
    window.toggleMobile = function () {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.querySelector('.sidebar-overlay');
      const hamburger = document.querySelector('.mobile-hamburger');
      if (sidebar) {
        sidebar.classList.toggle('mobile-open');
        sidebar.style.transform = '';
      }
      if (overlay) overlay.classList.toggle('show');
      if (hamburger && sidebar) {
        hamburger.classList.toggle('hidden', sidebar.classList.contains('mobile-open'));
      }
    };
  }
  if (typeof window.toggleCollapse !== 'function') {
    window.toggleCollapse = function () {
      const sidebar = document.getElementById('sidebar');
      if (!sidebar) return;
      sidebar.classList.toggle('collapsed');
      const isCollapsed = sidebar.classList.contains('collapsed');
      try { window.setCookie('sidebar_collapsed', isCollapsed ? 'true' : 'false', 365); } catch (e) {}
    };
  }
  (function initSidebarState() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar && window.getCookie('sidebar_collapsed') === 'true') {
      sidebar.classList.add('collapsed');
    }
    function handleResize() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.querySelector('.sidebar-overlay');
      const hamburger = document.querySelector('.mobile-hamburger');
      if (window.innerWidth >= 769) {
        if (sidebar) {
          sidebar.classList.remove('mobile-open');
          sidebar.style.transform = '';
        }
        if (overlay) overlay.classList.remove('show');
        if (hamburger) hamburger.classList.remove('hidden');
      } else if (sidebar) {
        sidebar.style.transform = '';
      }
    }
    handleResize();
    window.addEventListener('resize', handleResize);
  })();
})();
</script>
