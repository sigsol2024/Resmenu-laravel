<header class="site-header" id="siteHeader">
    <nav class="main-nav">
        <div class="nav-container">
            <a href="{{ route('login') }}" class="logo">
                <h1>{{ $siteName ?? config('app.name', 'SigSol Resmenu') }}</h1>
            </a>
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('login') }}">Home</a></li>
                <li><a href="{{ route('public.restaurants-list') }}">Restaurants</a></li>
                <li><a href="{{ route('public.templates') }}">Templates</a></li>
                <li><a href="{{ route('public.faq') }}">FAQ</a></li>
                <li><a href="{{ route('public.contact') }}">Contact</a></li>
                <li><a href="{{ route('register') }}" class="btn-nav">Get Started</a></li>
            </ul>
        </div>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('mobileMenuToggle');
    const menu = document.getElementById('navMenu');
    const header = document.getElementById('siteHeader');

    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            menu.classList.toggle('active');
            toggle.classList.toggle('active');
        });
    }

    if (header) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 100) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }
        });
    }
});
</script>
