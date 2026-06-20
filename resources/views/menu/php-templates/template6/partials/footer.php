<?php
$t6Logo = t6_logo_url($uploadBaseUrl ?? '', $restaurant ?? []);
$t6Year = date('Y');
$t6ConnectLinks = t6_connect_links($restaurant ?? []);
$t6Divider = 'border-outline-variant/35';
?>
<footer class="bg-surface-container-low border-t border-outline-variant/20 w-full mt-xl">
<div class="max-w-container-max mx-auto px-4 md:px-gutter py-lg">
<div class="grid grid-cols-1 md:grid-cols-3 mb-xl">
<div class="py-6 md:py-0 md:pr-gutter md:border-r <?php echo $t6Divider; ?>">
<div class="flex items-center gap-2 mb-4">
<?php if ($t6Logo): ?>
<img alt="<?php echo t6_esc($restaurant['name'] ?? ''); ?>" class="w-10 h-10 object-contain" src="<?php echo t6_esc($t6Logo); ?>">
<?php endif; ?>
<span class="font-display-lg text-headline-md text-primary tracking-widest"><?php echo t6_esc($restaurant['name'] ?? ''); ?></span>
</div>
<p class="text-on-surface-variant font-body-md max-w-sm"><?php echo t6_esc($restaurant['description'] ?? $restaurant['footer_content'] ?? ''); ?></p>
<?php if (! empty($restaurant['address']) || ! empty($restaurant['phone'])): ?>
<div class="mt-4 space-y-1 text-on-surface-variant font-body-sm text-body-sm">
<?php if (! empty($restaurant['address'])): ?><p><?php echo t6_esc($restaurant['address']); ?></p><?php endif; ?>
<?php if (! empty($restaurant['phone'])): ?><p><a href="tel:<?php echo t6_esc(preg_replace('/\s+/', '', (string) $restaurant['phone'])); ?>" class="hover:text-primary transition-colors"><?php echo t6_esc($restaurant['phone']); ?></a></p><?php endif; ?>
<?php if (! empty($restaurant['email'])): ?><p><a href="mailto:<?php echo t6_esc($restaurant['email']); ?>" class="hover:text-primary transition-colors"><?php echo t6_esc($restaurant['email']); ?></a></p><?php endif; ?>
</div>
<?php endif; ?>
</div>
<div class="py-6 md:py-0 md:px-gutter border-t md:border-t-0 md:border-r <?php echo $t6Divider; ?>">
<h5 class="font-label-lg text-label-lg uppercase text-primary mb-4">Connect</h5>
<?php if (! empty($t6ConnectLinks)): ?>
<ul class="space-y-3 font-label-md text-label-md uppercase tracking-wide">
<?php foreach ($t6ConnectLinks as $link): ?>
<li>
<a class="inline-flex items-center gap-2.5 text-on-surface-variant hover:text-primary transition-colors group" href="<?php echo t6_esc($link['url']); ?>" target="_blank" rel="noopener">
<span class="w-7 h-7 flex items-center justify-center rounded-full bg-surface-container-high border border-outline-variant/10 text-primary group-hover:bg-primary/10 group-hover:border-primary/30 transition-colors">
<?php echo t6_connect_icon($link['icon']); ?>
</span>
<span><?php echo t6_esc($link['label']); ?></span>
</a>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
<div class="py-6 md:py-0 md:pl-gutter border-t md:border-t-0 <?php echo $t6Divider; ?>">
<h5 class="font-label-lg text-label-lg uppercase text-primary mb-4">Information</h5>
<div class="space-y-3 text-on-surface-variant font-body-sm text-body-sm leading-relaxed">
<p>All dishes may contain allergens. Please inform your server of any dietary requirements before ordering.</p>
<p>🍷 Alcohol is strictly for persons aged 18 and above. Drink responsibly.</p>
<p class="font-label-md text-label-md uppercase tracking-[0.2em] text-primary pt-1">Superior Luxury Perfected</p>
</div>
</div>
</div>
<div class="flex flex-col md:flex-row justify-center md:justify-start items-center pt-8 border-t <?php echo $t6Divider; ?>">
<p class="text-on-surface-variant font-body-sm text-body-sm">&copy; <?php echo $t6Year; ?> <?php echo t6_esc($restaurant['name'] ?? ''); ?>. All rights reserved.</p>
</div>
</div>
</footer>
