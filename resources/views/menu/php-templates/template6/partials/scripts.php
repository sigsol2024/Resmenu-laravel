<script>
(function(){
    var header = document.getElementById('t6-header');
    window.addEventListener('scroll', function() {
        if (!header) return;
        if (window.scrollY > 50) {
            header.classList.add('py-2');
            header.classList.remove('py-4');
        } else {
            header.classList.add('py-4');
            header.classList.remove('py-2');
        }
    });

    var searchToggle = document.getElementById('t6-search-toggle');
    var searchBar = document.getElementById('t6-search-bar');
    var searchInput = document.getElementById('t6-search-input');
    if (searchToggle && searchBar) {
        searchToggle.addEventListener('click', function() {
            searchBar.classList.toggle('is-open');
            if (searchBar.classList.contains('is-open') && searchInput) searchInput.focus();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var q = this.value.toLowerCase().trim();
            document.querySelectorAll('[data-t6-searchable]').forEach(function(el) {
                var text = (el.getAttribute('data-t6-search-text') || el.textContent || '').toLowerCase();
                el.style.display = (!q || text.indexOf(q) !== -1) ? '' : 'none';
            });
        });
    }

    document.querySelectorAll('a.t6-scroll-anchor[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var id = this.getAttribute('href');
            if (!id || id === '#') return;
            var target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            var headerEl = document.getElementById('t6-header');
            var offset = headerEl ? headerEl.offsetHeight + 8 : 96;
            var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({ top: top, behavior: 'smooth' });
            if (history.replaceState) {
                history.replaceState(null, '', id);
            }
        });
    });

    document.querySelectorAll('button').forEach(function(button) {
        button.addEventListener('mousedown', function() {
            button.classList.add('scale-95');
            setTimeout(function() { button.classList.remove('scale-95'); }, 200);
        });
    });

    var contactToggle = document.getElementById('t6-contact-toggle');
    var contactDrawer = document.getElementById('t6-contact-drawer');
    var contactBackdrop = document.getElementById('t6-contact-backdrop');
    var contactClose = document.getElementById('t6-contact-close');

    function openContactDrawer() {
        if (!contactDrawer || !contactBackdrop) return;
        closeMenuDrawer();
        contactDrawer.classList.remove('hidden');
        contactDrawer.classList.add('is-open');
        contactBackdrop.classList.remove('hidden');
        document.body.classList.add('t6-drawer-open');
        if (contactToggle) contactToggle.setAttribute('aria-expanded', 'true');
    }

    function closeContactDrawer() {
        if (!contactDrawer || !contactBackdrop) return;
        contactDrawer.classList.add('hidden');
        contactDrawer.classList.remove('is-open');
        contactBackdrop.classList.add('hidden');
        document.body.classList.remove('t6-drawer-open');
        if (contactToggle) contactToggle.setAttribute('aria-expanded', 'false');
    }

    if (contactToggle) contactToggle.addEventListener('click', openContactDrawer);
    if (contactClose) contactClose.addEventListener('click', closeContactDrawer);
    if (contactBackdrop) contactBackdrop.addEventListener('click', closeContactDrawer);
    if (contactDrawer) {
        contactDrawer.addEventListener('click', function(e) { e.stopPropagation(); });
    }

    var menuToggle = document.getElementById('t6-menu-toggle');
    var menuDrawer = document.getElementById('t6-menu-drawer');
    var menuBackdrop = document.getElementById('t6-menu-backdrop');
    var menuClose = document.getElementById('t6-menu-close');

    function openMenuDrawer() {
        if (!menuDrawer || !menuBackdrop) return;
        closeContactDrawer();
        menuDrawer.classList.remove('hidden');
        menuDrawer.classList.add('is-open');
        menuBackdrop.classList.remove('hidden');
        document.body.classList.add('t6-drawer-open');
        if (menuToggle) menuToggle.setAttribute('aria-expanded', 'true');
    }

    function closeMenuDrawer() {
        if (!menuDrawer || !menuBackdrop) return;
        menuDrawer.classList.add('hidden');
        menuDrawer.classList.remove('is-open');
        menuBackdrop.classList.add('hidden');
        document.body.classList.remove('t6-drawer-open');
        if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
    }

    if (menuToggle) menuToggle.addEventListener('click', openMenuDrawer);
    if (menuClose) menuClose.addEventListener('click', closeMenuDrawer);
    if (menuBackdrop) menuBackdrop.addEventListener('click', closeMenuDrawer);
    if (menuDrawer) {
        menuDrawer.addEventListener('click', function(e) { e.stopPropagation(); });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (contactDrawer && !contactDrawer.classList.contains('hidden')) closeContactDrawer();
            if (menuDrawer && !menuDrawer.classList.contains('hidden')) closeMenuDrawer();
        }
    });
})();
</script>
</body>
</html>
