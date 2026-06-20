<?php
/** @var array<string, mixed>|null $reservationFormData */
$rfd = $reservationFormData ?? null;
if (empty($rfd) || ! is_array($rfd)) {
    return;
}
$t6Primary = t6_design_primary();
$t6OnPrimary = t6_design_on_primary();
$t6Deposit = (float) ($rfd['depositAmount'] ?? 0);
$t6Slots = $rfd['timeSlots'] ?? [];
$t6SelectedDate = $rfd['selectedDate'] ?? date('Y-m-d');
$t6SiteBase = $rfd['siteBase'] ?? t6_platform_base($fullMenuUrl ?? '');
$t6Old = static function (string $key, $default = '') {
    return function_exists('old') ? old($key, $default) : $default;
};
$t6FormErrorList = [];
if (session()->has('errors')) {
    $bag = session('errors');
    if (is_object($bag) && method_exists($bag, 'all')) {
        foreach ($bag->all() as $msgs) {
            foreach ((array) $msgs as $m) {
                $t6FormErrorList[] = $m;
            }
        }
    }
}
$t6ResSuccess = session('reservation_success') || session('success');
?>
<?php if ($t6ResSuccess): ?>
<div class="mb-6 p-4 rounded t6-res-alert-success font-body-sm">
Reservation confirmed! We look forward to seeing you.
</div>
<?php endif; ?>
<?php if (! empty($t6FormErrorList)): ?>
<div class="mb-6 p-4 rounded t6-res-alert-error font-body-sm space-y-1">
<?php foreach ($t6FormErrorList as $err): ?>
<p><?php echo t6_esc($err); ?></p>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div id="t6-reservation-form" class="t6-res-form" style="--t6-primary: <?php echo t6_esc($t6Primary); ?>;">
<form method="post" id="reservation-form" action="<?php echo t6_esc($rfd['actionUrl'] ?? ''); ?>" class="space-y-6">
<input type="hidden" name="_token" value="<?php echo t6_esc($rfd['csrfToken'] ?? ''); ?>">
<input type="hidden" name="return_url" value="<?php echo t6_esc(($fullMenuUrl ?? '').'#reservation'); ?>">
<input type="hidden" name="slug" value="<?php echo t6_esc($rfd['slug'] ?? ''); ?>">
<input type="hidden" name="party_size" id="party-size-input" value="<?php echo (int) $t6Old('party_size', 1); ?>">

<div class="mb-6">
<div class="flex items-center justify-between w-full text-xs md:text-sm gap-1">
<div class="flex items-center gap-1.5 min-w-0">
<div class="res-step-indicator w-7 h-7 md:w-8 md:h-8 rounded-full font-semibold flex items-center justify-center shrink-0 t6-step-active" id="step-ind-1" data-step="1">1</div>
<span class="t6-res-step-label font-label-md text-label-md uppercase text-on-surface hidden sm:inline truncate">Date</span>
</div>
<div class="flex-1 h-px mx-1 bg-outline-variant/30 hidden sm:block"></div>
<div class="flex items-center gap-1.5 min-w-0">
<div class="res-step-indicator w-7 h-7 md:w-8 md:h-8 rounded-full font-medium flex items-center justify-center shrink-0 border border-outline-variant/40 text-on-surface-variant" id="step-ind-2" data-step="2">2</div>
<span class="t6-res-step-label font-label-md text-label-md uppercase text-on-surface-variant hidden md:inline truncate">Guest</span>
</div>
<div class="flex-1 h-px mx-1 bg-outline-variant/30 hidden sm:block"></div>
<div class="flex items-center gap-1.5 min-w-0">
<div class="res-step-indicator w-7 h-7 md:w-8 md:h-8 rounded-full font-medium flex items-center justify-center shrink-0 border border-outline-variant/40 text-on-surface-variant" id="step-ind-3" data-step="3">3</div>
<span class="t6-res-step-label font-label-md text-label-md uppercase text-on-surface-variant hidden md:inline truncate">Requests</span>
</div>
<div class="flex-1 h-px mx-1 bg-outline-variant/30 hidden sm:block"></div>
<div class="flex items-center gap-1.5 min-w-0">
<div class="res-step-indicator w-7 h-7 md:w-8 md:h-8 rounded-full font-medium flex items-center justify-center shrink-0 border border-outline-variant/40 text-on-surface-variant" id="step-ind-4" data-step="4">4</div>
<span class="t6-res-step-label font-label-md text-label-md uppercase text-on-surface-variant hidden lg:inline truncate">Confirm</span>
</div>
</div>
</div>

<div class="res-step" data-step="1">
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
<div>
<label class="font-label-md text-label-md uppercase text-on-surface-variant mb-2 block">Date</label>
<input type="hidden" name="reservation_date" id="reservation-date-input" value="<?php echo t6_esc($t6Old('reservation_date', $t6SelectedDate)); ?>" required>
<div id="reservation-date-trigger" class="t6-res-field w-full p-3 cursor-pointer flex items-center justify-between" role="button" tabindex="0">
<span id="res-date-display" class="text-on-surface font-body-md">Click to select date</span>
<span class="material-symbols-outlined text-on-surface-variant text-lg">expand_more</span>
</div>
<div id="reservation-calendar-wrap" class="t6-res-field mt-3 p-4 hidden">
<div class="flex justify-between items-center mb-4">
<button type="button" id="res-cal-prev" class="p-2 rounded text-on-surface-variant hover:text-[#f0be78] transition-colors"><span class="material-symbols-outlined">chevron_left</span></button>
<span id="res-cal-month" class="font-label-lg text-label-lg text-on-surface"></span>
<button type="button" id="res-cal-next" class="p-2 rounded text-on-surface-variant hover:text-[#f0be78] transition-colors"><span class="material-symbols-outlined">chevron_right</span></button>
</div>
<div id="reservation-calendar" class="grid grid-cols-7 gap-1 text-center text-xs"></div>
<p id="res-cal-legend" class="mt-3 text-xs text-on-surface-variant flex flex-wrap gap-3">
<span><span class="inline-block w-2.5 h-2.5 rounded-sm mr-1" style="background:#f0be78"></span>Available</span>
<span><span class="inline-block w-2.5 h-2.5 rounded-sm bg-amber-500/60 mr-1"></span>Limited</span>
<span><span class="inline-block w-2.5 h-2.5 rounded-sm bg-outline-variant/60 mr-1"></span>Full</span>
</p>
</div>
</div>
<div>
<label class="font-label-md text-label-md uppercase text-on-surface-variant mb-2 block">Guests</label>
<div class="t6-res-field flex items-center justify-between px-4 py-3 party-wrap">
<button type="button" id="party-minus" class="t6-res-qty-btn w-9 h-9 flex items-center justify-center rounded-full border border-outline-variant/40 text-on-surface transition-colors"><span class="material-symbols-outlined text-lg">remove</span></button>
<span id="party-display" class="font-label-lg text-label-lg text-on-surface px-3"><?php echo (int) $t6Old('party_size', 1); ?> Guest<?php echo (int) $t6Old('party_size', 1) === 1 ? '' : 's'; ?></span>
<button type="button" id="party-plus" class="t6-res-qty-btn w-9 h-9 flex items-center justify-center rounded-full border border-outline-variant/40 text-on-surface transition-colors"><span class="material-symbols-outlined text-lg">add</span></button>
</div>
</div>
</div>
<div>
<label class="font-label-md text-label-md uppercase text-on-surface-variant mb-3 block text-center sm:text-left">Available Time Slots</label>
<div id="time-slots-container" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
<?php foreach ($t6Slots as $slot):
    $avail = ! empty($slot['available']);
?>
<button type="button" data-time="<?php echo t6_esc($slot['time'] ?? ''); ?>" class="time-slot t6-time-slot py-2.5 px-1 text-xs sm:text-sm font-label-md rounded border border-outline-variant/40 text-on-surface bg-surface-container-low transition-all<?php echo $avail ? '' : ' opacity-40 cursor-not-allowed line-through'; ?>"<?php echo $avail ? '' : ' disabled'; ?>><?php echo t6_esc($slot['time'] ?? ''); ?></button>
<?php endforeach; ?>
</div>
<input type="hidden" name="reservation_time" id="reservation-time-input" value="<?php echo t6_esc($t6Old('reservation_time', '')); ?>" required>
</div>
<div class="flex justify-end mt-6">
<button type="button" class="res-next-btn t6-res-btn-primary px-6 py-3 font-label-lg text-label-lg uppercase tracking-widest">Next</button>
</div>
</div>

<div class="res-step hidden" data-step="2">
<label class="font-label-md text-label-md uppercase text-on-surface-variant mb-3 block">Guest Information</label>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
<input name="guest_name" type="text" placeholder="Full Name" required class="t6-res-input w-full px-4 py-3 bg-surface-container-low border-b border-outline-variant/30 text-on-surface font-body-md outline-none transition-colors" value="<?php echo t6_esc($t6Old('guest_name', '')); ?>">
<input name="guest_email" type="email" placeholder="Email Address" required class="t6-res-input w-full px-4 py-3 bg-surface-container-low border-b border-outline-variant/30 text-on-surface font-body-md outline-none transition-colors" value="<?php echo t6_esc($t6Old('guest_email', '')); ?>">
</div>
<input name="guest_phone" type="tel" placeholder="Phone Number" required inputmode="numeric" class="t6-res-input w-full px-4 py-3 bg-surface-container-low border-b border-outline-variant/30 text-on-surface font-body-md outline-none transition-colors mb-6" value="<?php echo t6_esc($t6Old('guest_phone', '')); ?>">
<div class="flex justify-between gap-3">
<button type="button" class="res-back-btn t6-res-btn-ghost px-5 py-3 font-label-lg text-label-lg uppercase tracking-widest">Back</button>
<button type="button" class="res-next-btn t6-res-btn-primary px-6 py-3 font-label-lg text-label-lg uppercase tracking-widest">Next</button>
</div>
</div>

<div class="res-step hidden" data-step="3">
<label class="font-label-md text-label-md uppercase text-on-surface-variant mb-3 block">Special Requests</label>
<div class="flex flex-wrap gap-2 mb-4">
<?php foreach (['BIRTHDAY', 'ANNIVERSARY', 'BUSINESS', 'DATE_NIGHT'] as $occ): ?>
<button type="button" data-occasion="<?php echo t6_esc($occ); ?>" class="occasion-btn t6-occasion-btn px-3 py-2 text-xs font-label-md uppercase rounded-full border border-outline-variant/40 text-on-surface-variant bg-surface-container-low transition-colors"><?php echo t6_esc(str_replace('_', ' ', $occ)); ?></button>
<?php endforeach; ?>
</div>
<input type="hidden" name="special_occasion" id="special-occasion-input" value="<?php echo t6_esc($t6Old('special_occasion', '')); ?>">
<textarea name="notes" rows="3" placeholder="Dietary requirements or additional notes..." class="t6-res-input w-full px-4 py-3 bg-surface-container-low border-b border-outline-variant/30 text-on-surface font-body-md outline-none transition-colors resize-none mb-6"><?php echo t6_esc($t6Old('notes', '')); ?></textarea>
<div class="flex justify-between gap-3">
<button type="button" class="res-back-btn t6-res-btn-ghost px-5 py-3 font-label-lg text-label-lg uppercase tracking-widest">Back</button>
<button type="button" class="res-next-btn t6-res-btn-primary px-6 py-3 font-label-lg text-label-lg uppercase tracking-widest">Next</button>
</div>
</div>

<div class="res-step hidden" data-step="4">
<div id="res-review-summary" class="mb-6 p-4 bg-surface-container-low border border-outline-variant/30 rounded text-on-surface font-body-sm space-y-2"></div>
<?php if ($t6Deposit > 0): ?>
<p class="mb-4 text-on-surface-variant font-body-sm">A deposit of <strong class="text-[#f0be78]">₦<?php echo number_format($t6Deposit, 2); ?></strong> will be required at checkout.</p>
<?php endif; ?>
<div class="flex justify-between gap-3">
<button type="button" class="res-back-btn t6-res-btn-ghost px-5 py-3 font-label-lg text-label-lg uppercase tracking-widest">Back</button>
<button type="submit" class="t6-res-btn-primary px-6 py-3 font-label-lg text-label-lg uppercase tracking-widest">Confirm Reservation</button>
</div>
<p class="text-center text-xs text-on-surface-variant mt-4">By booking, you agree to our terms and cancellation policy.</p>
</div>
</form>
</div>
<script>
window.RESERVATION_CONFIG = <?php echo json_encode([
    'primaryColor' => $t6Primary,
    'onPrimaryColor' => $t6OnPrimary,
    'theme' => 'template6',
    'baseUrl' => $t6SiteBase,
    'slug' => $rfd['slug'] ?? '',
    'partySize' => (int) $t6Old('party_size', 1),
    'minDate' => $rfd['minDate'] ?? date('Y-m-d'),
    'slotsUrl' => $t6SiteBase.'/api/reservations/slots',
    'availabilityUrl' => $t6SiteBase.'/api/reservations/availability',
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
</script>
<?php
$wizardJsPath = dirname(__DIR__, 6).'/public/assets/js/reservation-wizard.js';
$t6WizardVer = is_readable($wizardJsPath) ? (string) filemtime($wizardJsPath) : '1';
?>
<script src="<?php echo t6_esc($t6SiteBase); ?>/assets/js/reservation-wizard.js?v=<?php echo t6_esc($t6WizardVer); ?>"></script>
