<?php if (! empty($supportsOrdering)):
    $t6SiteBase = defined('SITE_URL') ? rtrim((string) SITE_URL, '/') : t6_platform_base($fullMenuUrl ?? '');
    $t6Primary = t6_design_primary();
?>
<link rel="stylesheet" href="<?php echo t6_esc($t6SiteBase); ?>/legacy/assets/css/cart-modal.css">
<div id="resmenu-cart-widget" class="fixed bottom-6 left-6 z-50 hidden"></div>
<script src="<?php echo t6_esc($t6SiteBase); ?>/assets/js/cart.js"></script>
<script src="<?php echo t6_esc($t6SiteBase); ?>/assets/js/cart-widget.js"></script>
<script src="<?php echo t6_esc($t6SiteBase); ?>/assets/js/cart-modal.js"></script>
<script>
(function(){
    var baseUrl = <?php echo json_encode($t6SiteBase); ?>;
    var slug = <?php echo json_encode($restaurant['slug'] ?? ''); ?>;
    var config = {
        restaurantSlug: slug,
        currencySymbol: <?php echo json_encode('₦'); ?>,
        uploadBaseUrl: <?php echo json_encode($uploadBaseUrl ?? ''); ?>,
        checkoutUrl: baseUrl + '/restaurant/' + slug + '/checkout',
        primaryColor: <?php echo json_encode($t6Primary); ?>,
        deliveryFee: 0,
        taxRate: 0
    };
    window.RESMENU_CART_CONFIG = config;
    if (window.RESMENU_CART_MODAL) window.RESMENU_CART_MODAL.init(config);
    if (window.RESMENU_CART_WIDGET) window.RESMENU_CART_WIDGET.init(config);
    function bindCartButtons() {
        document.querySelectorAll('.add-to-bag-btn').forEach(function(btn) {
            if (btn.dataset.t6Bound) return;
            btn.dataset.t6Bound = '1';
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-item-id');
                var name = this.getAttribute('data-item-name');
                var price = this.getAttribute('data-item-price');
                var image = this.getAttribute('data-item-image') || '';
                if (window.RESMENU_CART) window.RESMENU_CART.addItem(slug, { id: id, name: name, price: price, image: image }, 1);
            });
        });
    }
    bindCartButtons();
    window.t6BindCartButtons = bindCartButtons;
    function openCart() {
        if (window.RESMENU_CART_MODAL && typeof window.RESMENU_CART_MODAL.open === 'function') {
            window.RESMENU_CART_MODAL.open();
        } else {
            var w = document.getElementById('resmenu-cart-widget-btn');
            if (w) w.click();
        }
    }
    ['t6-header-cart'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', openCart);
    });
})();
</script>
<?php endif; ?>
