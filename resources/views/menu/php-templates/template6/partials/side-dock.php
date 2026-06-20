<?php
$conciergeUrl = ! empty($restaurant['whatsapp_link'])
    ? $restaurant['whatsapp_link']
    : (! empty($restaurant['phone']) ? 'tel:'.preg_replace('/\s+/', '', (string) $restaurant['phone']) : null);
?>
<aside class="fixed right-6 top-1/2 -translate-y-1/2 flex flex-col gap-4 z-40 hidden lg:flex" aria-label="Quick actions">
<div class="side-dock p-3 rounded-full flex flex-col gap-6 shadow-2xl items-center">
<a href="<?php echo t6_esc($fullMenuUrl ?? '#'); ?>" class="w-10 h-10 flex items-center justify-center rounded-full text-primary hover:bg-primary/10 transition-all" title="View Menu">
<span class="material-symbols-outlined">restaurant_menu</span>
</a>
<?php if (! empty($supportsOrdering)): ?>
<button type="button" id="t6-dock-cart" class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:text-primary transition-all" title="My Orders">
<span class="material-symbols-outlined">shopping_bag</span>
</button>
<?php endif; ?>
<?php if ($conciergeUrl): ?>
<div class="w-6 h-[1px] bg-outline-variant/30"></div>
<a href="<?php echo t6_esc($conciergeUrl); ?>" <?php echo str_starts_with($conciergeUrl, 'http') ? 'target="_blank" rel="noopener"' : ''; ?> class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:text-primary transition-all" title="Concierge">
<span class="material-symbols-outlined">support_agent</span>
</a>
<?php endif; ?>
</div>
</aside>
