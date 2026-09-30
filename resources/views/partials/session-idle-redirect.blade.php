@php($idleSeconds = (int) config('resmenu.auth_session_idle_seconds', 3600))
@if($idleSeconds > 0)
<script>
(function () {
    // Reload after the server idle window so SessionIdleTimeout logs out and redirects to login.
    var idleMs = {{ $idleSeconds }} * 1000 + 5000;
    var timer = null;
    function arm() {
        if (timer) clearTimeout(timer);
        timer = setTimeout(function () {
            window.location.href = window.location.href;
        }, idleMs);
    }
    ['click', 'keydown', 'mousemove', 'scroll', 'touchstart'].forEach(function (evt) {
        window.addEventListener(evt, arm, { passive: true });
    });
    arm();
})();
</script>
@endif
