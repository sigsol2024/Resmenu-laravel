<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-column">
                <h3>{{ $siteName ?? config('app.name', 'SigSol Resmenu') }}</h3>
                <p>Professional digital menu platform for restaurants. Create beautiful, customizable menus that engage your customers.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook" target="_blank" rel="noopener">Facebook</a>
                    <a href="#" aria-label="Twitter" target="_blank" rel="noopener">Twitter</a>
                    <a href="#" aria-label="Instagram" target="_blank" rel="noopener">Instagram</a>
                </div>
            </div>

            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('login') }}">Home</a></li>
                    <li><a href="{{ route('public.restaurants-list') }}">Restaurants</a></li>
                    <li><a href="{{ route('public.templates') }}">Templates</a></li>
                    <li><a href="{{ route('public.faq') }}">FAQ</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Support</h4>
                <ul>
                    <li><a href="{{ route('public.contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('public.terms') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('admin.login') }}">Login</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Get Started</h4>
                <p>Ready to create your digital menu?</p>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up Free</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $siteName ?? config('app.name', 'SigSol Resmenu') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
