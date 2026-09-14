#!/usr/bin/env node
/**
 * Capture template preview heroes as WebP for gallery cards.
 * Invoked by: php artisan templates:generate-previews
 *
 * New templates MUST mark the above-the-fold hero:
 *   <section data-template-preview-hero>...</section>
 *   (header/div with the same attribute also works)
 */
import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const args = process.argv.slice(2);

function argValue(name) {
  const idx = args.findIndex((a) => a === `--${name}` || a.startsWith(`--${name}=`));
  if (idx < 0) return null;
  const cur = args[idx];
  if (cur.includes('=')) return cur.split('=').slice(1).join('=');
  return args[idx + 1] ?? null;
}

const baseUrl = (argValue('base') || process.env.APP_URL || 'http://127.0.0.1:8000').replace(/\/$/, '');
const outDir = argValue('out') || path.resolve(__dirname, '../public/uploads/template-previews');
const dryRun = args.includes('--dry-run');
const all = args.includes('--all');
const templateArg = argValue('template');
const idsArg = argValue('ids');

let ids = [];
if (idsArg) {
  ids = idsArg.split(',').map((s) => parseInt(s.trim(), 10)).filter((n) => n > 0);
} else if (templateArg) {
  ids = [parseInt(templateArg, 10)].filter((n) => n > 0);
} else if (all) {
  const tplRoot = path.resolve(__dirname, '../resources/views/menu/php-templates');
  ids = fs.readdirSync(tplRoot)
    .map((d) => {
      const m = /^template(\d+)$/.exec(d);
      return m ? parseInt(m[1], 10) : 0;
    })
    .filter((n) => n > 0)
    .sort((a, b) => a - b);
} else {
  console.error('Usage: node generate-template-previews.mjs --all|--template=N [--dry-run] [--base=URL] [--ids=1,2,3]');
  process.exit(2);
}

fs.mkdirSync(outDir, { recursive: true });

const results = [];
const channel = argValue('channel') || process.env.PLAYWRIGHT_CHROME_CHANNEL || 'chrome';
let browser;
try {
  // Prefer installed Chrome/Edge so hosts that cannot download Playwright browsers still work.
  browser = await chromium.launch({
    headless: true,
    channel,
  });
} catch (channelErr) {
  try {
    browser = await chromium.launch({ headless: true });
  } catch (bundledErr) {
    console.error(`Failed to launch browser (channel=${channel}): ${channelErr.message || channelErr}`);
    console.error(`Bundled Chromium also unavailable: ${bundledErr.message || bundledErr}`);
    console.error('Install Google Chrome, or run: npx playwright install chromium');
    process.exit(1);
  }
}
const context = await browser.newContext({
  // Match gallery card aspect-[4/3] so object-cover does not over-crop.
  viewport: { width: 1440, height: 1080 },
  deviceScaleFactor: 1,
});

let sharp = null;
try {
  sharp = (await import('sharp')).default;
} catch {
  console.warn('sharp not installed — will write PNG fallbacks instead of WebP');
}

for (const id of ids) {
  const url = `${baseUrl}/templates/${id}/preview?capture=1`;
  const page = await context.newPage();
  try {
    const res = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 90000 });
    if (!res || !res.ok()) {
      throw new Error(`HTTP ${res ? res.status() : 'no-response'}`);
    }
    await page.waitForLoadState('networkidle', { timeout: 20000 }).catch(() => {});

    await page.addStyleTag({
      content: `
        *, *::before, *::after { animation: none !important; transition: none !important; }
        [data-menu-drawer], .menu-drawer, .drawer, .modal, .cookie, #cookie-banner { display: none !important; }
      `,
    });

    await page.waitForTimeout(600);
    const locator = page.locator('[data-template-preview-hero]').first();
    if ((await locator.count()) < 1) {
      throw new Error('missing [data-template-preview-hero] marker');
    }

    // Marker is used for readiness only — capture the full above-the-fold viewport.
    // Element screenshots of small <header> blocks produce ultra-wide strips that
    // gallery object-cover then crops badly (T4/T7 look fine because their heroes are tall).
    await locator.scrollIntoViewIfNeeded();
    await page.waitForFunction(() => {
      const root = document.querySelector('[data-template-preview-hero]');
      if (!root) return false;
      const imgs = [...root.querySelectorAll('img')];
      if (imgs.length === 0) return true;
      return imgs.every((img) => img.complete);
    }, { timeout: 20000 }).catch(() => {});
    await page.evaluate(() => window.scrollTo(0, 0));
    await page.waitForTimeout(200);

    const pngBuf = await page.screenshot({
      type: 'png',
      clip: { x: 0, y: 0, width: 1440, height: 1080 },
    });

    if (dryRun) {
      results.push({ id, ok: true, note: 'dry-run (not written)' });
      console.log(`Template ${id}  ✓ screenshot generated (dry-run)`);
    } else if (sharp) {
      const outFile = path.join(outDir, `template-${id}.webp`);
      await sharp(pngBuf).webp({ quality: 82 }).toFile(outFile);
      results.push({ id, ok: true, note: path.basename(outFile) });
      console.log(`Template ${id}  ✓ screenshot generated`);
    } else {
      const outFile = path.join(outDir, `template-${id}.png`);
      fs.writeFileSync(outFile, pngBuf);
      results.push({ id, ok: true, note: path.basename(outFile) });
      console.log(`Template ${id}  ✓ screenshot generated (png fallback)`);
    }
  } catch (e) {
    results.push({ id, ok: false, note: e.message || String(e) });
    console.log(`Template ${id}  ✗ ${e.message || e}`);
  } finally {
    await page.close();
  }
}

await browser.close();

const success = results.filter((r) => r.ok).length;
const total = results.length;
console.log('');
console.log(`${success}/${total} successful`);

fs.writeFileSync(
  path.join(outDir, '_generate-summary.json'),
  JSON.stringify({ baseUrl, dryRun, results }, null, 2)
);

process.exit(success === total ? 0 : 1);
