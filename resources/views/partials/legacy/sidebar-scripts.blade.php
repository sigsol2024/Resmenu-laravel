<script>
(function() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar && getCookie('sidebar_collapsed') === 'true') {
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

function toggleMobile(){
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
}

function toggleCollapse(){
  const sidebar = document.getElementById('sidebar');
  if (sidebar) {
    sidebar.classList.toggle('collapsed');
    const isCollapsed = sidebar.classList.contains('collapsed');
    setCookie('sidebar_collapsed', isCollapsed ? 'true' : 'false', 365);
  }
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
  return null;
}

function setCookie(name, value, days) {
  const expires = new Date();
  expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
}
</script>
