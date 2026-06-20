<?php
$t6Platform = t6_platform_base($fullMenuUrl ?? '');
$t6TermsUrl = $t6Platform.'/terms';
$t6ContactUrl = $t6Platform.'/contact';
$t6Logo = t6_logo_url($uploadBaseUrl ?? '', $restaurant ?? []);
$t6Year = date('Y');
?>
<footer class="bg-surface-container-low border-t border-outline-variant/20 w-full mt-xl">
<div class="max-w-container-max mx-auto px-gutter py-lg">
<div class="grid grid-cols-1 md:grid-cols-4 gap-xl mb-xl">
<div class="md:col-span-2">
<div class="flex items-center gap-2 mb-6">
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
<div>
<h5 class="font-label-lg text-label-lg uppercase text-primary mb-6">Connect</h5>
<ul class="space-y-4 font-label-md text-label-md uppercase tracking-wide">
<?php if (! empty($restaurant['instagram_url'])): ?>
<li><a class="text-on-surface-variant hover:text-primary transition-colors" href="<?php echo t6_esc($restaurant['instagram_url']); ?>" target="_blank" rel="noopener">Instagram</a></li>
<?php endif; ?>
<?php if (! empty($restaurant['facebook_url'])): ?>
<li><a class="text-on-surface-variant hover:text-primary transition-colors" href="<?php echo t6_esc($restaurant['facebook_url']); ?>" target="_blank" rel="noopener">Facebook</a></li>
<?php endif; ?>
<?php if (! empty($restaurant['twitter_url'])): ?>
<li><a class="text-on-surface-variant hover:text-primary transition-colors" href="<?php echo t6_esc($restaurant['twitter_url']); ?>" target="_blank" rel="noopener">Twitter (X)</a></li>
<?php endif; ?>
<?php if (! empty($restaurant['website'])): ?>
<li><a class="text-on-surface-variant hover:text-primary transition-colors" href="<?php echo t6_esc($restaurant['website']); ?>" target="_blank" rel="noopener">Website</a></li>
<?php endif; ?>
</ul>
</div>
<div>
<h5 class="font-label-lg text-label-lg uppercase text-primary mb-6">Legal</h5>
<ul class="space-y-4 font-label-md text-label-md uppercase tracking-wide">
<li><a class="text-on-surface-variant hover:text-primary transition-colors" href="<?php echo t6_esc($t6TermsUrl); ?>">Terms of Service</a></li>
<li><a class="text-on-surface-variant hover:text-primary transition-colors" href="<?php echo t6_esc($t6ContactUrl); ?>">Contact Us</a></li>
</ul>
</div>
</div>
<div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-outline-variant/10">
<p class="text-on-surface-variant font-body-sm text-body-sm mb-4 md:mb-0">&copy; <?php echo $t6Year; ?> <?php echo t6_esc($restaurant['name'] ?? ''); ?>. All rights reserved.</p>
<?php if (! empty($restaurant['whatsapp_link'])): ?>
<a class="font-label-md text-label-md text-primary hover:underline" href="<?php echo t6_esc($restaurant['whatsapp_link']); ?>" target="_blank" rel="noopener">WhatsApp</a>
<?php endif; ?>
</div>
</div>
</footer>
