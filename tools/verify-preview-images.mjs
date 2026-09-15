import fs from 'fs';
import path from 'path';

const root = process.cwd();
const previewRoot = path.join(root, 'public/templates/preview_images');
const cfg = fs.readFileSync(path.join(root, 'config/template_preview_images.php'), 'utf8');

function extractBlock(key) {
  const m = cfg.match(new RegExp(`'${key}'\\s*=>\\s*\\[([\\s\\S]*?)\\n\\s*\\],`));
  return m ? m[1] : '';
}

function pairs(block, numericKeys = false) {
  const out = {};
  const re = numericKeys
    ? /(\d+)\s*=>\s*'([^']+)'/g
    : /'([a-z0-9-]+)'\s*=>\s*'([^']+)'/g;
  let p;
  while ((p = re.exec(block))) out[p[1]] = p[2];
  return out;
}

const missing = [];
function check(rel, label) {
  if (!fs.existsSync(path.join(previewRoot, rel))) missing.push(`${label}: ${rel}`);
}

const restaurantCover = (cfg.match(/'restaurant_cover'\s*=>\s*'([^']+)'/) || [])[1];
check(restaurantCover, 'restaurant_cover');

for (const [k, v] of Object.entries(pairs(extractBlock('sections')))) check(v, `sections.${k}`);
for (const [k, v] of Object.entries(pairs(extractBlock('categories')))) check(v, `categories.${k}`);
for (const [k, v] of Object.entries(pairs(extractBlock('items')))) check(v, `items.${k}`);
const gallery = pairs(extractBlock('gallery_covers'), true);
for (const [k, v] of Object.entries(gallery)) check(v, `gallery.${k}`);

const templatesDir = path.join(root, 'resources/views/menu/php-templates');
function walk(dir, acc = []) {
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const p = path.join(dir, e.name);
    if (e.isDirectory()) walk(p, acc);
    else if (e.name.endsWith('.php')) acc.push(p);
  }
  return acc;
}
const oldConcat = walk(templatesDir).filter((f) =>
  /\$uploadBaseUrl\s*\.\s*['"]\/(menu-items|categories|sections|heroes)\//.test(fs.readFileSync(f, 'utf8'))
).map((f) => path.relative(root, f));

const svc = fs.readFileSync(path.join(root, 'app/Services/TemplatePreviewDemoService.php'), 'utf8');
const marketing = fs.readFileSync(path.join(root, '../resmenu.net/templates.php'), 'utf8');

console.log(JSON.stringify({
  missingCount: missing.length,
  missing,
  galleryIds: Object.keys(gallery).map(Number).sort((a, b) => a - b),
  galleryCount: Object.keys(gallery).length,
  oldConcat,
  hasHelper: fs.readFileSync(path.join(root, 'app/Support/legacy_menu_helpers.php'), 'utf8').includes('function resmenu_media_url'),
  hasSyncCmd: fs.existsSync(path.join(root, 'app/Console/Commands/SyncPreviewCoversCommand.php')),
  noListingFallback: !/preview_bg\s*\?:\s*\$t\['listing_image'\]/.test(marketing),
  noBannedSections: !/(Grill & Burgers|'Sides'|Pastries)/.test(svc),
  previewBase: svc.includes('/templates/preview_images'),
}, null, 2));
