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

    var mobileBtn = document.getElementById('t6-mobile-menu-btn');
    var mobileNav = document.getElementById('t6-mobile-nav');
    if (mobileBtn && mobileNav) {
        mobileBtn.addEventListener('click', function() {
            mobileNav.classList.toggle('is-open');
            mobileBtn.textContent = mobileNav.classList.contains('is-open') ? 'close' : 'menu';
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

    function t6CloseMobileNav() {
        if (mobileNav) mobileNav.classList.remove('is-open');
        if (mobileBtn) mobileBtn.textContent = 'menu';
    }

    document.querySelectorAll('a.t6-scroll-anchor[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var id = this.getAttribute('href');
            if (!id || id === '#') return;
            var target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            t6CloseMobileNav();
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
})();
</script>
</body>
</html>
