<?php
$t6MenuViewLevel = $menuViewLevel ?? 'home';
$t6HasContact = ! empty($restaurant) && is_array($restaurant) && t6_has_contact_info($restaurant);
$t6ShowContact = $t6MenuViewLevel === 'home' && $t6HasContact;
$t6BackUrl = $t6BackUrl ?? null;
if (! $t6ShowContact && empty($t6BackUrl)) {
    return;
}

$t6Phone = trim((string) ($restaurant['phone'] ?? ''));
$t6Email = trim((string) ($restaurant['email'] ?? ''));
$t6Address = trim((string) ($restaurant['address'] ?? ''));
$t6Whatsapp = trim((string) ($restaurant['whatsapp_link'] ?? ''));
$t6Tel = $t6Phone !== '' ? 'tel:'.preg_replace('/\s+/', '', $t6Phone) : null;
$t6MapEmbed = t6_map_embed_url($restaurant);
$t6Directions = t6_directions_url($restaurant);
$t6RestName = trim((string) ($restaurant['name'] ?? 'Restaurant'));
?>
<nav class="t6-left-dock fixed left-0 top-1/2 -translate-y-1/2 z-50 flex flex-col gap-0.5 shadow-2xl" aria-label="Quick actions">
<?php if ($t6ShowContact): ?>
<button type="button" id="t6-contact-toggle" class="t6-dock-btn flex items-center justify-center p-3 rounded-r-full" aria-label="Contact us" aria-controls="t6-contact-drawer" aria-expanded="false">
<span class="material-symbols-outlined text-primary text-2xl">support_agent</span>
</button>
<?php endif; ?>
<?php if (! empty($t6BackUrl)): ?>
<a href="<?php echo t6_esc($t6BackUrl); ?>" class="t6-dock-btn flex items-center justify-center p-3 rounded-r-full" aria-label="Go back">
<span class="material-symbols-outlined text-primary text-2xl">arrow_back</span>
</a>
<?php endif; ?>
</nav>

<?php if (! $t6ShowContact) {
    return;
} ?>

<div id="t6-contact-backdrop" class="hidden fixed inset-0 z-[90] bg-surface/60 backdrop-blur-sm" aria-hidden="true"></div>

<aside id="t6-contact-drawer" class="hidden fixed right-0 top-0 h-full w-full sm:w-96 md:w-[420px] bg-surface-container-highest shadow-2xl z-[100] border-l border-outline-variant/10 overflow-y-auto hide-scrollbar" aria-labelledby="t6-contact-drawer-title" role="dialog" aria-modal="true">
<div class="px-gutter py-4 flex items-center justify-between sticky top-0 bg-surface-container-highest/95 backdrop-blur-md z-20 border-b border-outline-variant/10">
<div>
<h2 id="t6-contact-drawer-title" class="font-headline-md text-headline-md text-primary">Contact Us</h2>
<p class="font-label-md text-label-md text-on-surface-variant/70 uppercase"><?php echo t6_esc($t6RestName); ?></p>
</div>
<button type="button" id="t6-contact-close" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors text-3xl" aria-label="Close contact panel">close</button>
</div>

<div class="px-gutter pt-4 pb-3">
<div class="grid grid-cols-1 gap-2">
<?php if ($t6Tel): ?>
<a class="group flex items-center justify-between py-2.5 px-3 bg-surface-container-high rounded-lg hover:bg-surface-variant transition-all duration-300 border border-outline-variant/5" href="<?php echo t6_esc($t6Tel); ?>">
<div class="flex items-center gap-2.5 min-w-0">
<div class="w-8 h-8 shrink-0 flex items-center justify-center bg-primary-container/20 rounded-full">
<span class="material-symbols-outlined text-primary text-lg">call</span>
</div>
<div class="min-w-0">
<span class="font-label-md text-label-md text-on-surface block leading-tight">Call Us</span>
<span class="font-body-sm text-body-sm text-on-surface-variant truncate block leading-snug"><?php echo t6_esc($t6Phone); ?></span>
</div>
</div>
<span class="material-symbols-outlined text-primary text-sm opacity-0 group-hover:opacity-100 transition-opacity shrink-0">arrow_forward_ios</span>
</a>
<?php endif; ?>
<?php if ($t6Whatsapp !== ''): ?>
<a class="group flex items-center justify-between py-2.5 px-3 bg-surface-container-high rounded-lg hover:bg-surface-variant transition-all duration-300 border border-outline-variant/5" href="<?php echo t6_esc($t6Whatsapp); ?>" target="_blank" rel="noopener">
<div class="flex items-center gap-2.5 min-w-0">
<div class="w-8 h-8 shrink-0 flex items-center justify-center bg-primary-container/20 rounded-full">
<span class="material-symbols-outlined text-primary text-lg">chat</span>
</div>
<div class="min-w-0">
<span class="font-label-md text-label-md text-on-surface block leading-tight">WhatsApp</span>
<span class="font-body-sm text-body-sm text-on-surface-variant leading-snug">Message us</span>
</div>
</div>
<span class="material-symbols-outlined text-primary text-sm opacity-0 group-hover:opacity-100 transition-opacity shrink-0">arrow_forward_ios</span>
</a>
<?php endif; ?>
<?php if ($t6Email !== ''): ?>
<a class="group flex items-center justify-between py-2.5 px-3 bg-surface-container-high rounded-lg hover:bg-surface-variant transition-all duration-300 border border-outline-variant/5" href="mailto:<?php echo t6_esc($t6Email); ?>">
<div class="flex items-center gap-2.5 min-w-0">
<div class="w-8 h-8 shrink-0 flex items-center justify-center bg-primary-container/20 rounded-full">
<span class="material-symbols-outlined text-primary text-lg">mail</span>
</div>
<div class="min-w-0">
<span class="font-label-md text-label-md text-on-surface block leading-tight">Email</span>
<span class="font-body-sm text-body-sm text-on-surface-variant truncate block leading-snug"><?php echo t6_esc($t6Email); ?></span>
</div>
</div>
<span class="material-symbols-outlined text-primary text-sm opacity-0 group-hover:opacity-100 transition-opacity shrink-0">arrow_forward_ios</span>
</a>
<?php endif; ?>
</div>
</div>

<?php if ($t6Address !== ''): ?>
<div class="px-gutter pb-gutter">
<h3 class="font-label-lg text-label-lg text-on-surface-variant mb-2 uppercase">Our Location</h3>
<div class="relative group">
<div class="w-full h-40 rounded-xl overflow-hidden border border-outline-variant/10 shadow-lg bg-surface-container-low">
<?php if ($t6MapEmbed): ?>
<iframe class="w-full h-full border-0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" title="<?php echo t6_esc($t6RestName); ?> location map" src="<?php echo t6_esc($t6MapEmbed); ?>"></iframe>
<div class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-surface-container-highest from-30% via-surface-container-highest/90 to-transparent"></div>
<div class="absolute bottom-0 left-0 right-0 p-3 flex items-end justify-between gap-2 z-10">
<div class="min-w-0">
<p class="font-label-md text-label-md text-on-surface font-semibold truncate leading-tight"><?php echo t6_esc($t6RestName); ?></p>
<p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 leading-snug mt-0.5"><?php echo t6_esc($t6Address); ?></p>
</div>
<?php if ($t6Directions): ?>
<a href="<?php echo t6_esc($t6Directions); ?>" target="_blank" rel="noopener" class="shrink-0 bg-primary text-on-primary p-1.5 rounded-full shadow-lg active:scale-95 transition-transform" aria-label="Get directions">
<span class="material-symbols-outlined text-lg">directions</span>
</a>
<?php endif; ?>
</div>
<?php else: ?>
<div class="w-full h-full flex items-center justify-center p-4 text-center">
<p class="font-body-sm text-body-sm text-on-surface-variant"><?php echo t6_esc($t6Address); ?></p>
</div>
<?php endif; ?>
</div>
</div>
<?php if (! $t6MapEmbed && $t6Directions): ?>
<a href="<?php echo t6_esc($t6Directions); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-2 font-label-md text-label-md text-primary hover:underline uppercase">
<span class="material-symbols-outlined text-base">directions</span>
Get Directions
</a>
<?php endif; ?>
</div>
<?php endif; ?>
</aside>
