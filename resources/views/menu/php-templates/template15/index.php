<?php
/**
 * Bold Flavours — Court5-inspired hospitality menu (split hero mockup)
 */
if (defined('UPLOAD_URL')) { $uploadBaseUrl = rtrim(UPLOAD_URL, '/'); } else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $uploadBaseUrl = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . (dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))) ?: '') . '/uploads';
}
$baseUrl = defined('SITE_URL') ? rtrim(SITE_URL, '/') : '';
if (empty($isTemplatePreview)) {
    $reservationUrl = $baseUrl . '/restaurant/' . ($restaurant['slug'] ?? '') . '/reservation';
} else {
    $reservationUrl = $reservationUrl ?? (($fullMenuUrl ?? '') . '#reservation');
}
$currencySymbol = '₦';
$primaryColor = isset($customization['primary_color']) ? $customization['primary_color'] : '#DA2514';
$template15AssetBase = rtrim((string) ($templateAssetBaseUrl ?? ($template15BaseUrl ?? (config('app.url').'/templates/template15'))), '/');

function bf_price($p, $s = '₦') {
    return formatPrice($p, $s);
}

function bf_feature_class(string $categoryName): string
{
    $hay = strtolower(trim($categoryName));
    if ($hay === '') {
        return '';
    }
    if (preg_match('/\b(main course|main courses|mains|main dish|main dishes|entree|entrees|entrée|entrées)\b/u', $hay)) {
        return 'feature-main';
    }
    if (preg_match('/\b(signature cocktail|signature cocktails|cocktail|cocktails)\b/u', $hay)) {
        return 'feature-cocktails';
    }

    return '';
}

function bf_is_drinks_section(array $section): bool
{
    $hay = strtolower(trim(($section['name'] ?? '').' '.($section['slug'] ?? '')));

    return $hay !== '' && (str_contains($hay, 'drink') || str_contains($hay, 'bar') || str_contains($hay, 'beverage') || str_contains($hay, 'wine') || str_contains($hay, 'cocktail'));
}

$activeCategories = [];
if (!empty($sections) && is_array($sections)) {
    foreach ($sections as $sec) {
        if (empty($sec['categories']) || !is_array($sec['categories'])) continue;
        foreach ($sec['categories'] as $c) {
            if (!empty($c['menu_items']) && is_array($c['menu_items']) && !empty($c['is_active'])) {
                $activeCategories[] = $c;
            }
        }
    }
}

$bfHeroUrl = '';
if (!empty($singleSectionView) && !empty($sections[0]['image'])) {
    $bfHeroUrl = resmenu_media_url($uploadBaseUrl, 'sections', $sections[0]['image']);
} elseif (!empty($restaurant['hero_image_url'])) {
    $bfHeroUrl = (string) $restaurant['hero_image_url'];
} elseif (!empty($restaurant['hero_image']) && empty($isTemplatePreview)) {
    $bfHeroUrl = resmenu_media_url($uploadBaseUrl, 'heroes', $restaurant['hero_image']);
}
if ($bfHeroUrl === '') {
    $bfHeroUrl = $template15AssetBase.'/hero-fallback.webp';
}

$bfSingle = !empty($singleSectionView);
$bfFullUrl = (string) ($fullMenuUrl ?? '');
$bfSingleSlug = ($bfSingle && !empty($sections[0]['slug'])) ? (string) $sections[0]['slug'] : '';
$bfCategoryHref = function (string $catSlug) use ($bfSingle, $bfFullUrl, $bfSingleSlug) {
    return ($bfSingle && $bfFullUrl !== '' && $bfSingleSlug !== '')
        ? $bfFullUrl.'/'.$bfSingleSlug.'#'.$catSlug
        : '#'.$catSlug;
};

$bfBrandLogo = (!empty($isTemplatePreview)) ? null : resmenu_logo_url($uploadBaseUrl ?? '', $restaurant['logo'] ?? null);
$bfName = trim((string) ($restaurant['name'] ?? 'Menu'));
$bfNameParts = preg_split('/\s+/u', $bfName, -1, PREG_SPLIT_NO_EMPTY) ?: [$bfName];
$bfNameAccent = array_pop($bfNameParts);
$bfNameLead = trim(implode(' ', $bfNameParts));
$bfAddress = trim((string) ($restaurant['address'] ?? ''));
$bfLead = trim((string) ($restaurant['description'] ?? ''));
if ($bfLead === '' && $bfAddress !== '') {
    $bfLead = $bfAddress;
}
$bfDotUrl = $template15AssetBase.'/orange-dot.webp';
$bfPatternUrl = $template15AssetBase.'/pattern-dark.png';
$bfDrinksBannerUrl = $template15AssetBase.'/drinks-banner.webp';

$bfFoodSections = [];
$bfDrinkSections = [];
foreach (($sections ?? []) as $section) {
    if (empty($section['categories']) || !is_array($section['categories'])) {
        continue;
    }
    if (bf_is_drinks_section($section)) {
        $bfDrinkSections[] = $section;
    } else {
        $bfFoodSections[] = $section;
    }
}
$bfUseSplit = !empty($bfDrinkSections) && !empty($bfFoodSections);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="theme-color" content="#f4f1ec"/>
<title><?php echo htmlspecialchars($bfName); ?><?php if (!empty($singleSectionView) && !empty($sections[0]['name'])): ?> - <?php echo htmlspecialchars($sections[0]['name']); ?><?php endif; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
:root{
  --tomato:#da2514;
  --flame:#ff800f;
  --amber:#ffb84d;
  --cream:#f4f1ec;
  --paper:#fcfbf9;
  --ink:#1c1c1c;
  --muted:#686861;
  --line:rgba(28,28,28,.14);
  --sans:"DM Sans",system-ui,sans-serif;
  --display:"Cormorant Garamond",Georgia,serif;
  --orange-dot:url("<?php echo htmlspecialchars($bfDotUrl, ENT_QUOTES, 'UTF-8'); ?>");
  --pattern:url("<?php echo htmlspecialchars($bfPatternUrl, ENT_QUOTES, 'UTF-8'); ?>");
}
*{box-sizing:border-box}
html{scroll-behavior:smooth;scroll-padding-top:96px}
body{margin:0;background:var(--cream);color:var(--ink);font-family:var(--sans);-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
button{font:inherit;cursor:pointer}
.site-header{position:sticky;top:0;z-index:30;background:rgba(252,251,249,.94);border-bottom:1px solid rgba(0,0,0,.06);backdrop-filter:blur(14px)}
.header-inner{max-width:1440px;margin:0 auto;padding:10px clamp(18px,5vw,68px);display:flex;align-items:center;justify-content:space-between;gap:18px}
.brand{display:flex;align-items:center;gap:13px;min-width:0}
.brand img{display:block;width:52px;height:52px;object-fit:contain}
.brand-wordmark{color:var(--ink);font-size:12px;font-weight:700;letter-spacing:.19em;text-transform:uppercase}
.header-actions{display:flex;align-items:center;gap:14px}
.header-location{max-width:220px;color:#77766f;font-size:8px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;line-height:1.45;text-align:right}
.reserve-pill{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;background:var(--flame);color:white;padding:11px 20px;font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;transition:background .18s,transform .18s}
.reserve-pill:hover{background:var(--tomato);transform:translateY(-1px)}
.header-reserve{display:inline-flex}
.menu-toggle,.drawer-close{width:43px;height:43px;display:grid;place-items:center;border:1px solid rgba(28,28,28,.17);border-radius:50%;background:transparent;color:var(--ink);transition:color .18s,border-color .18s,background .18s}
.menu-toggle:hover,.drawer-close:hover{color:var(--tomato);border-color:var(--tomato);background:#fff}
.menu-toggle svg,.drawer-close svg{width:19px;height:19px}
.hero{min-height:min(730px,78vh);min-height:min(730px,78dvh);display:grid;grid-template-columns:.88fr 1.12fr;background:var(--paper)}
.hero-copy{padding:clamp(34px,6vw,92px);display:flex;flex-direction:column;align-items:flex-start;justify-content:center;background-color:var(--paper);background-image:var(--pattern),linear-gradient(180deg,rgba(252,251,249,.94),rgba(244,241,236,.97));background-size:280px auto,auto;background-repeat:repeat,no-repeat;background-blend-mode:soft-light,normal}
.hero h1{margin:0 0 16px;color:var(--ink);font-size:clamp(48px,7vw,94px);font-weight:800;font-style:italic;line-height:.88;letter-spacing:-.065em;text-transform:uppercase}
.hero h1 span{color:var(--tomato)}
.hero-lead{max-width:430px;margin:0;color:rgba(28,28,28,.68);font-size:13px;line-height:1.8}
.hero-actions{display:flex;flex-wrap:wrap;align-items:center;gap:20px;margin-top:27px}
.hero-cta{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;background:var(--flame);color:#fff;padding:13px 21px;font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;transition:background .18s,transform .18s}
.hero-cta:hover{background:var(--tomato);transform:translateY(-1px)}
.hero-link{color:var(--ink);font-size:9px;font-weight:700;letter-spacing:.14em;text-transform:uppercase}
.hero-link:hover{color:var(--tomato)}
.hero-photo{position:relative;min-height:430px;overflow:hidden;background:#a79e8e}
.hero-photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 56%}
.hero-photo:after{content:"";position:absolute;inset:55% 0 0;background:linear-gradient(0deg,rgba(28,28,28,.52),transparent)}
.photo-caption{position:absolute;z-index:1;left:24px;bottom:19px;color:#fff;font-size:8px;font-weight:700;letter-spacing:.18em;text-transform:uppercase}
.color-rule{height:5px;background:linear-gradient(90deg,var(--tomato) 0 48%,var(--flame) 48% 100%)}
.intro-strip{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:12px clamp(20px,5vw,68px);background:var(--paper);color:rgba(28,28,28,.58);font-size:8px;font-weight:700;letter-spacing:.16em;text-transform:uppercase}
.intro-strip strong{color:var(--ink)}
.menu-body{max-width:1440px;margin:0 auto;padding:42px clamp(20px,5vw,68px) 76px;background-color:var(--paper);background-image:var(--pattern),linear-gradient(180deg,rgba(252,251,249,.95),rgba(244,241,236,.98));background-size:280px auto,auto;background-repeat:repeat,no-repeat;background-blend-mode:soft-light,normal}
.section-heading{margin:0 0 45px;text-align:center}
.section-heading .small-label{display:block;color:var(--flame);font-size:9px;font-weight:700;letter-spacing:.22em;text-transform:uppercase}
.section-heading h2{margin:8px 0 0;color:var(--tomato);font-family:var(--display);font-size:clamp(33px,4vw,47px);font-weight:300;letter-spacing:.08em;text-transform:uppercase}
.section-count{display:block;margin-top:4px;color:rgba(28,28,28,.45);font-size:8px;font-weight:600;letter-spacing:.12em;text-transform:uppercase}
.category-section{margin:0 0 55px;scroll-margin-top:95px}
.category-title{position:relative;margin:0 0 28px;padding:0 0 15px;border-bottom:1px solid rgba(28,28,28,.16);color:var(--tomato);font-family:var(--display);font-size:29px;font-weight:400;letter-spacing:.08em;text-align:center;text-transform:uppercase}
.category-title:after{content:"";position:absolute;left:50%;bottom:-5px;width:9px;height:9px;transform:translateX(-50%);background-image:var(--orange-dot);background-size:contain}
.items-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));column-gap:42px;row-gap:28px}
.category-section.feature-main{margin:4px -24px 58px;padding:31px 30px 34px;border-left:5px solid var(--flame);background-color:var(--ink);background-image:linear-gradient(rgba(28,28,28,.86),rgba(28,28,28,.91)),var(--pattern);background-size:auto,280px auto;background-repeat:no-repeat,repeat;background-blend-mode:normal,soft-light}
.feature-main .category-title{margin-bottom:25px;border-color:rgba(255,255,255,.25);color:#fff;font-size:36px;text-align:left}
.feature-main .category-title:after{left:auto;right:2px;transform:none}
.feature-main .dish-title,.feature-main .rm-item-title{color:#fff}
.feature-main .dish-price,.feature-main .rm-price{color:var(--amber)}
.feature-main .price-line{border-color:rgba(255,255,255,.3)}
.feature-main .dish-description,.feature-main .rm-description{color:rgba(255,255,255,.67)}
.feature-main .bf-order-btn{border-color:rgba(255,184,77,.45);color:var(--amber)}
.feature-main .bf-order-btn:hover{background:var(--flame);border-color:var(--flame);color:#fff}
.category-section.feature-cocktails{margin:0 -12px 58px;padding:26px 27px 30px;border:1px solid rgba(255,128,15,.28);border-top:4px solid var(--flame);background:#fff7ec}
.feature-cocktails .category-title{margin-bottom:23px;border-color:rgba(28,28,28,.17);color:var(--ink);font-size:34px;text-align:left}
.feature-cocktails .category-title:after{left:auto;right:2px;transform:none}
.feature-cocktails .dish-image{box-shadow:0 5px 18px rgba(145,75,21,.1)}
.dish{display:flex;align-items:flex-start;gap:15px;min-width:0}
.dish.is-unavailable{opacity:.55}
.dish-image{width:72px;height:72px;flex:0 0 72px;overflow:hidden;border:1px solid rgba(0,0,0,.05);border-radius:50%;background:#fff;box-shadow:0 4px 20px rgba(0,0,0,.06)}
.dish-image img{display:block;width:100%;height:100%;object-fit:cover}
.dish-fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#fff;color:var(--tomato);font-family:var(--display);font-size:27px;font-weight:300}
.dish-info{flex:1;min-width:0;padding-top:2px}
.dish-title-row{display:flex;align-items:baseline;min-width:0;gap:0}
.dish-title,.rm-item-title{flex:0 1 auto;min-width:0;margin:0;color:var(--tomato);font-size:11px;font-weight:600;letter-spacing:.11em;line-height:1.45;text-transform:uppercase}
.price-line{flex:1 1 auto;min-width:18px;height:.85em;margin:0 10px;border-bottom:1px dashed rgba(28,28,28,.23)}
.dish-price,.rm-price{flex:0 0 auto;color:var(--ink);font-size:11px;font-weight:700;font-variant-numeric:tabular-nums;white-space:nowrap}
.dish-description,.rm-description{margin:7px 0 0;color:rgba(28,28,28,.58);font-size:10px;line-height:1.65}
.bf-order-btn{display:inline-flex;align-items:center;gap:.35rem;margin-top:8px;border-radius:999px;border:1px solid rgba(218,37,20,.35);color:var(--tomato);font-size:8px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;padding:7px 12px;background:transparent;transition:background .18s,color .18s,border-color .18s}
.bf-order-btn:hover{background:var(--tomato);border-color:var(--tomato);color:#fff}
.unavailable-label{display:inline-block;margin-top:8px;color:var(--tomato);font-size:8px;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
.drinks-feature{display:grid;grid-template-columns:1fr .92fr;min-height:230px;margin:5px 0 54px;overflow:hidden;background:var(--ink);color:#fff}
.drinks-feature-copy{display:flex;flex-direction:column;justify-content:center;padding:32px clamp(24px,4vw,54px);background-image:var(--pattern);background-size:280px auto;background-repeat:repeat;background-blend-mode:soft-light}
.drinks-feature-copy small{color:var(--flame);font-size:8px;font-weight:700;letter-spacing:.2em;text-transform:uppercase}
.drinks-feature-copy strong{margin-top:10px;font-family:var(--display);font-size:34px;font-weight:400;line-height:1.05}
.drinks-feature-copy p{max-width:360px;margin:10px 0 0;color:rgba(255,255,255,.72);font-size:10px;line-height:1.65}
.drinks-feature img{width:100%;height:100%;min-height:230px;object-fit:cover}
.footer{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;padding:20px clamp(20px,5vw,68px);background:#fff;border-top:1px solid var(--line);color:rgba(28,28,28,.58);font-size:8px;font-weight:600;letter-spacing:.12em;text-transform:uppercase}
.footer strong{color:var(--ink);font-size:10px;letter-spacing:.18em}
.footer a{color:var(--tomato)}
.footer-social{width:100%}
.overlay{position:fixed;inset:0;z-index:48;background:rgba(28,28,28,.48);opacity:0;visibility:hidden;pointer-events:none;transition:opacity .2s,visibility .2s}
.overlay.open{opacity:1;visibility:visible;pointer-events:auto}
.drawer{position:fixed;z-index:50;top:0;right:0;display:flex;width:min(calc(100vw - 2.25rem),370px);height:100dvh;flex-direction:column;background:var(--cream);box-shadow:-18px 0 50px rgba(0,0,0,.18);transform:translateX(105%);transition:transform .28s ease}
.drawer.open{transform:translateX(0)}
.drawer-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:17px 20px;border-bottom:1px solid rgba(0,0,0,.12)}
.drawer-head-copy{min-width:0;display:flex;flex-direction:column;gap:8px}
.drawer-title{color:rgba(28,28,28,.7);font-size:9px;font-weight:700;letter-spacing:.22em;text-transform:uppercase}
.drawer-address{color:rgba(28,28,28,.55);font-size:9px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;line-height:1.5}
.drawer-nav{display:flex;flex:1;flex-direction:column;gap:8px;overflow-y:auto;padding:16px 16px 22px}
.drawer-link{display:block;padding:12px 14px;border:1px solid rgba(28,28,28,.1);border-radius:10px;background:rgba(255,255,255,.56);color:rgba(28,28,28,.72);font-size:10px;font-weight:600;letter-spacing:.1em;text-align:center;text-transform:uppercase;transition:border-color .15s,color .15s,background .15s}
.drawer-link:hover{border-color:var(--tomato);color:var(--tomato);background:#fff}
.drawer-main{color:var(--ink);font-size:12px;font-weight:700}
.drawer-separator{display:flex;align-items:center;justify-content:center;gap:9px;padding:7px 0;color:rgba(28,28,28,.2)}
.drawer-dot{width:8px;height:8px;background-image:var(--orange-dot);background-size:contain}
.drawer-separator:before,.drawer-separator:after{content:"";width:34px;height:1px;background:rgba(28,28,28,.16)}
.drawer-reserve{margin:0 16px 16px}
body.drawer-open{overflow:hidden}
.bf-reveal{opacity:0;transform:translateY(18px);transition:opacity .7s ease,transform .7s ease}
.bf-reveal.is-in{opacity:1;transform:none}
.hero-copy > *{opacity:0;transform:translateY(16px);animation:bfRise .8s ease forwards}
.hero-copy h1{animation-delay:.08s}
.hero-copy .hero-lead{animation-delay:.18s}
.hero-copy .hero-actions{animation-delay:.28s}
.hero-photo{opacity:0;transform:scale(1.04);animation:bfPhotoIn 1s ease .15s forwards}
.hero-photo img{transition:transform 8s ease}
.hero-photo:hover img{transform:scale(1.04)}
.dish{transition:transform .25s ease,box-shadow .25s ease}
.dish:hover{transform:translateY(-2px)}
.color-rule{transform-origin:left center;animation:bfRule .9s ease .35s both}
@keyframes bfRise{to{opacity:1;transform:none}}
@keyframes bfPhotoIn{to{opacity:1;transform:none}}
@keyframes bfRule{from{transform:scaleX(0)}to{transform:scaleX(1)}}
@media(max-width:900px){.hero{grid-template-columns:.9fr 1.1fr;min-height:600px}.hero-copy{padding:34px}.hero-photo{min-height:400px}.items-grid{column-gap:27px}}
@media(max-width:680px){
  .header-inner{padding:8px 15px}
  .brand img{width:44px;height:44px}
  .brand-wordmark{font-size:10px}
  .header-location,.header-reserve{display:none !important}
  .reserve-pill{padding:10px 13px;font-size:8px}
  .menu-toggle{width:40px;height:40px}
  .hero{min-height:0;grid-template-columns:1fr}
  .hero-copy{min-height:355px;padding:39px 24px}
  .hero h1{font-size:64px}
  .hero-photo{min-height:300px}
  .intro-strip{padding:10px 16px;font-size:7px}
  .menu-body{padding:34px 17px 55px}
  .section-heading{margin-bottom:37px}
  .category-section{margin-bottom:45px}
  .category-section.feature-main{margin:0 -7px 46px;padding:23px 16px 26px}
  .feature-main .category-title{font-size:30px}
  .category-section.feature-cocktails{margin:0 -5px 46px;padding:21px 15px 24px}
  .feature-cocktails .category-title{font-size:29px}
  .items-grid{grid-template-columns:1fr;row-gap:24px}
  .dish-image{width:66px;height:66px;flex-basis:66px}
  .dish-title,.rm-item-title{font-size:10px}
  .dish-price,.rm-price{font-size:10px}
  .drinks-feature{grid-template-columns:1fr}
  .drinks-feature img{grid-row:1;height:220px;min-height:0}
  .drinks-feature-copy{padding:25px}
  .footer{align-items:flex-start;flex-direction:column;gap:9px}
}
@media(prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
  .drawer,.overlay,.reserve-pill,.hero-cta,.dish,.bf-reveal,.hero-photo img{transition:none}
  .hero-copy > *,.hero-photo,.color-rule{animation:none !important;opacity:1;transform:none}
  .bf-reveal{opacity:1;transform:none}
}
</style>
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="<?php echo htmlspecialchars($bfFullUrl !== '' ? $bfFullUrl : '#top'); ?>" aria-label="<?php echo htmlspecialchars($bfName); ?>">
      <?php if ($bfBrandLogo): ?>
        <img src="<?php echo htmlspecialchars($bfBrandLogo); ?>" alt="<?php echo htmlspecialchars($bfName); ?>"/>
      <?php endif; ?>
      <span class="brand-wordmark"><?php echo htmlspecialchars($bfName); ?></span>
    </a>
    <div class="header-actions">
      <?php if ($bfAddress !== ''): ?>
        <span class="header-location"><?php echo htmlspecialchars($bfAddress); ?></span>
      <?php endif; ?>
      <?php if (!empty($supportsReservations)): ?>
        <a class="reserve-pill header-reserve" href="<?php echo htmlspecialchars($reservationUrl); ?>">Book a table</a>
      <?php endif; ?>
      <button class="menu-toggle" id="bf-menu-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="bf-drawer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>
</header>

<div class="overlay" id="bf-overlay" aria-hidden="true"></div>
<aside class="drawer" id="bf-drawer" aria-label="Menu navigation" aria-hidden="true">
  <div class="drawer-head">
    <div class="drawer-head-copy">
      <span class="drawer-title">Navigate</span>
      <?php if ($bfAddress !== ''): ?>
        <span class="drawer-address"><?php echo htmlspecialchars($bfAddress); ?></span>
      <?php endif; ?>
    </div>
    <button class="drawer-close" id="bf-drawer-close" type="button" aria-label="Close menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
    </button>
  </div>
  <nav class="drawer-nav" id="bf-drawer-nav">
    <?php if ($bfSingle && $bfFullUrl !== ''): ?>
      <a class="drawer-link drawer-main bf-drawer-link" href="<?php echo htmlspecialchars($bfFullUrl); ?>">Full menu</a>
    <?php endif; ?>
    <a class="drawer-link drawer-main bf-drawer-link" href="#menu">View menu</a>
    <?php if (!empty($sectionsForNav) && is_array($sectionsForNav) && $bfFullUrl !== ''): ?>
      <?php foreach ($sectionsForNav as $navSection): ?>
        <a class="drawer-link bf-drawer-link" href="<?php echo htmlspecialchars($bfFullUrl); ?>#section-<?php echo htmlspecialchars($navSection['slug'] ?? ''); ?>"><?php echo htmlspecialchars($navSection['name'] ?? ''); ?></a>
      <?php endforeach; ?>
    <?php endif; ?>
    <div class="drawer-separator" aria-hidden="true"><span class="drawer-dot"></span></div>
    <?php foreach ($activeCategories as $i => $cat):
      $s = isset($cat['slug']) ? $cat['slug'] : ('cat-'.$i);
    ?>
      <a class="drawer-link bf-drawer-link" href="<?php echo htmlspecialchars($bfCategoryHref($s)); ?>">
        <span style="color:var(--tomato);font-variant-numeric:tabular-nums;margin-right:8px"><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></span>
        <?php echo htmlspecialchars($cat['name']); ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <?php if (!empty($supportsReservations)): ?>
    <a class="reserve-pill drawer-reserve bf-drawer-link" href="<?php echo htmlspecialchars($reservationUrl); ?>">Reserve a table</a>
  <?php endif; ?>
</aside>

<main id="top">
  <section class="hero" aria-label="<?php echo htmlspecialchars($bfName); ?>" data-template-preview-hero>
    <div class="hero-copy">
      <h1>
        <?php if ($bfNameLead !== ''): ?><?php echo htmlspecialchars($bfNameLead); ?> <?php endif; ?>
        <span><?php echo htmlspecialchars($bfNameAccent); ?></span>
      </h1>
      <?php if ($bfLead !== ''): ?>
        <p class="hero-lead"><?php echo htmlspecialchars($bfLead); ?></p>
      <?php endif; ?>
      <div class="hero-actions">
        <a class="hero-cta" href="#menu">View the menu</a>
        <?php if (!empty($supportsReservations)): ?>
          <a class="hero-link" href="<?php echo htmlspecialchars($reservationUrl); ?>">Book a table</a>
        <?php elseif (!empty($bfDrinkSections)): ?>
          <a class="hero-link" href="#section-<?php echo htmlspecialchars($bfDrinkSections[0]['slug'] ?? 'drinks'); ?>">Drinks menu</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="hero-photo">
      <img src="<?php echo htmlspecialchars($bfHeroUrl); ?>" alt="" loading="eager"/>
      <?php if ($bfAddress !== ''): ?>
        <div class="photo-caption"><?php echo htmlspecialchars($bfAddress); ?></div>
      <?php endif; ?>
    </div>
  </section>

  <div class="color-rule" aria-hidden="true"></div>
  <div class="intro-strip bf-reveal">
    <span><strong>Food and drinks</strong> <span aria-hidden="true">/</span> Menu</span>
    <?php if ($bfAddress !== ''): ?><span><?php echo htmlspecialchars($bfAddress); ?></span><?php endif; ?>
  </div>

  <div class="menu-body" id="menu">
    <?php
    $renderSectionBlock = function (array $section) use ($bfFullUrl, $bfSingle, $uploadBaseUrl, $supportsOrdering) {
        $itemCount = 0;
        foreach ($section['categories'] as $category) {
            $items = $category['menu_items'] ?? [];
            if (is_array($items)) {
                $itemCount += count($items);
            }
        }
        ?>
        <section id="section-<?php echo htmlspecialchars($section['slug'] ?? ''); ?>" class="menu-section">
          <header class="section-heading bf-reveal">
            <span class="small-label"><?php echo htmlspecialchars($section['name'] ?? 'Menu'); ?></span>
            <h2>
              <?php if ($bfFullUrl !== '' && empty($bfSingle)): ?>
                <a href="<?php echo htmlspecialchars($bfFullUrl.'/'.($section['slug'] ?? '')); ?>"><?php echo htmlspecialchars($section['name'] ?? ''); ?></a>
              <?php else: ?>
                <?php echo htmlspecialchars($section['name'] ?? ''); ?>
              <?php endif; ?>
            </h2>
            <span class="section-count"><?php echo (int) $itemCount; ?> items</span>
          </header>

          <?php foreach ($section['categories'] as $catIndex => $category):
              $slug = isset($category['slug']) ? $category['slug'] : ('cat-'.$catIndex);
              $items = isset($category['menu_items']) ? $category['menu_items'] : [];
              if (empty($items)) {
                  continue;
              }
              $feature = bf_feature_class((string) ($category['name'] ?? ''));
          ?>
          <section class="category-section bf-reveal <?php echo htmlspecialchars($feature); ?>" id="<?php echo htmlspecialchars($slug); ?>">
            <h3 class="rm-category-title category-title"><?php echo htmlspecialchars($category['name'] ?? ''); ?></h3>
            <div class="items-grid">
              <?php foreach ($items as $itemIndex => $item):
                  $itemName = (string) ($item['name'] ?? '');
                  $initial = $itemName !== '' ? mb_strtoupper(mb_substr($itemName, 0, 1)) : '•';
                  $itemImg = !empty($item['image']) ? resmenu_media_url($uploadBaseUrl, 'menu-items', $item['image']) : '';
                  $available = !isset($item['is_available']) || !empty($item['is_available']);
                  $stagger = min((int) $itemIndex, 7) * 45;
              ?>
              <article class="dish bf-reveal <?php echo $available ? '' : 'is-unavailable'; ?>" style="transition-delay:<?php echo $stagger; ?>ms">
                <div class="dish-image">
                  <?php if ($itemImg !== ''): ?>
                    <img src="<?php echo htmlspecialchars($itemImg); ?>" alt="<?php echo htmlspecialchars($itemName); ?>" loading="lazy"/>
                  <?php else: ?>
                    <div class="dish-fallback" aria-hidden="true"><?php echo htmlspecialchars($initial); ?></div>
                  <?php endif; ?>
                </div>
                <div class="dish-info">
                  <div class="dish-title-row">
                    <h4 class="rm-item-title dish-title"><?php echo htmlspecialchars($itemName); ?></h4>
                    <span class="price-line" aria-hidden="true"></span>
                    <span class="rm-price dish-price"><?php echo bf_price($item['price'] ?? 0); ?></span>
                  </div>
                  <?php if (!empty($item['description'])): ?>
                    <p class="rm-description dish-description"><?php echo htmlspecialchars($item['description']); ?></p>
                  <?php endif; ?>
                  <?php if (!$available): ?>
                    <span class="unavailable-label">Unavailable</span>
                  <?php elseif (!empty($supportsOrdering)): ?>
                    <button type="button" class="add-to-bag-btn bf-order-btn"
                      data-item-id="<?php echo (int) ($item['id'] ?? 0); ?>"
                      data-item-name="<?php echo htmlspecialchars($itemName); ?>"
                      data-item-price="<?php echo htmlspecialchars($item['price'] ?? ''); ?>"
                      data-item-image="<?php echo !empty($item['image']) ? htmlspecialchars($item['image']) : ''; ?>">
                      Order
                      <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    </button>
                  <?php endif; ?>
                </div>
              </article>
              <?php endforeach; ?>
            </div>
          </section>
          <?php endforeach; ?>
        </section>
        <?php
    };

    if ($bfUseSplit) {
        foreach ($bfFoodSections as $section) {
            $renderSectionBlock($section);
        }
        ?>
        <section class="drinks-feature bf-reveal">
          <div class="drinks-feature-copy">
            <small>From the bar</small>
            <strong>Drinks for the table</strong>
            <p>Classics, signature cocktails, wines, spirits and more.</p>
          </div>
          <img src="<?php echo htmlspecialchars($bfDrinksBannerUrl); ?>" alt="" loading="lazy"/>
        </section>
        <?php
        foreach ($bfDrinkSections as $section) {
            $renderSectionBlock($section);
        }
    } else {
        foreach (($sections ?? []) as $section) {
            if (empty($section['categories']) || !is_array($section['categories'])) {
                continue;
            }
            $renderSectionBlock($section);
        }
    }
    ?>
  </div>
</main>

<footer class="footer">
  <strong><?php echo htmlspecialchars($bfName); ?></strong>
  <?php if ($bfAddress !== ''): ?><span><?php echo htmlspecialchars($bfAddress); ?></span><?php endif; ?>
  <?php if (!empty($supportsReservations)): ?>
    <a href="<?php echo htmlspecialchars($reservationUrl); ?>">Book a table</a>
  <?php elseif (!empty($restaurant['phone'])): ?>
    <a href="tel:<?php echo htmlspecialchars(preg_replace('/\s+/', '', $restaurant['phone'])); ?>"><?php echo htmlspecialchars($restaurant['phone']); ?></a>
  <?php endif; ?>
  <div class="footer-social"><?php echo resmenu_social_links($restaurant, 'mt-2 text-tomato'); ?></div>
</footer>

<?php if (!empty($supportsOrdering)): ?>
<link rel="stylesheet" href="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/legacy/assets/css/cart-modal.css">
<div id="resmenu-cart-widget" class="fixed bottom-6 left-6 z-50 hidden"></div>
<script src="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/assets/js/cart.js"></script>
<script src="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/assets/js/cart-widget.js"></script>
<script src="<?php echo rtrim(defined('SITE_URL') ? SITE_URL : $baseUrl, '/'); ?>/assets/js/cart-modal.js"></script>
<script>
(function(){
  var baseUrl=<?php echo json_encode($baseUrl); ?>;
  var slug=<?php echo json_encode($restaurant['slug'] ?? ''); ?>;
  var config={
    restaurantSlug:slug,
    currencySymbol:<?php echo json_encode($currencySymbol); ?>,
    uploadBaseUrl:<?php echo json_encode($uploadBaseUrl ?? ''); ?>,
    checkoutUrl:baseUrl+'/restaurant/'+slug+'/checkout',
    primaryColor:<?php echo json_encode($primaryColor); ?>,
    deliveryFee:0,
    taxRate:0
  };
  window.RESMENU_CART_CONFIG=config;
  if(window.RESMENU_CART_MODAL)window.RESMENU_CART_MODAL.init(config);
  if(window.RESMENU_CART_WIDGET)window.RESMENU_CART_WIDGET.init(config);
  document.querySelectorAll('.add-to-bag-btn').forEach(function(btn){
    btn.addEventListener('click',function(){
      var id=this.getAttribute('data-item-id'),
          name=this.getAttribute('data-item-name'),
          price=this.getAttribute('data-item-price'),
          image=this.getAttribute('data-item-image')||'';
      if(window.RESMENU_CART)window.RESMENU_CART.addItem(slug,{id:id,name:name,price:price,image:image},1);
    });
  });
})();
</script>
<?php endif; ?>

<a id="scrollToTop" href="#" aria-label="Scroll to top" style="position:fixed;bottom:24px;right:24px;z-index:40;width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:var(--flame);color:#fff;opacity:0;visibility:hidden;transform:translateY(10px);transition:opacity .3s,visibility .3s,transform .3s;box-shadow:0 4px 12px rgba(0,0,0,.2);">
  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>
</a>
<script>
(function(){
  var btn=document.getElementById('scrollToTop');
  if(btn){
    window.addEventListener('scroll',function(){
      var st=window.pageYOffset||document.documentElement.scrollTop;
      var dh=document.documentElement.scrollHeight-window.innerHeight;
      if(dh>0&&st>=dh*0.3){btn.style.opacity='1';btn.style.visibility='visible';btn.style.transform='translateY(0)';}
      else{btn.style.opacity='0';btn.style.visibility='hidden';btn.style.transform='translateY(10px)';}
    });
    btn.addEventListener('click',function(e){e.preventDefault();window.scrollTo({top:0,behavior:'smooth'});});
  }

  var drawer=document.getElementById('bf-drawer');
  var overlay=document.getElementById('bf-overlay');
  var toggle=document.getElementById('bf-menu-toggle');
  var closeBtn=document.getElementById('bf-drawer-close');
  function setDrawer(open){
    if(!drawer||!overlay||!toggle)return;
    drawer.classList.toggle('open',open);
    overlay.classList.toggle('open',open);
    document.body.classList.toggle('drawer-open',open);
    drawer.setAttribute('aria-hidden',String(!open));
    overlay.setAttribute('aria-hidden',String(!open));
    toggle.setAttribute('aria-expanded',String(open));
    toggle.setAttribute('aria-label',open?'Close menu':'Open menu');
  }
  if(toggle)toggle.addEventListener('click',function(){setDrawer(!drawer.classList.contains('open'));});
  if(closeBtn)closeBtn.addEventListener('click',function(){setDrawer(false);});
  if(overlay)overlay.addEventListener('click',function(){setDrawer(false);});
  document.querySelectorAll('.bf-drawer-link').forEach(function(link){
    link.addEventListener('click',function(){setDrawer(false);});
  });
  document.addEventListener('keydown',function(e){if(e.key==='Escape')setDrawer(false);});

  var reduceMotion=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var revealNodes=document.querySelectorAll('.bf-reveal');
  if(reduceMotion){
    revealNodes.forEach(function(el){el.classList.add('is-in');});
  }else if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if(entry.isIntersecting){
          entry.target.classList.add('is-in');
          io.unobserve(entry.target);
        }
      });
    },{threshold:0.12,rootMargin:'0px 0px -6% 0px'});
    revealNodes.forEach(function(el){io.observe(el);});
  }else{
    revealNodes.forEach(function(el){el.classList.add('is-in');});
  }
})();
</script>
</body>
</html>
