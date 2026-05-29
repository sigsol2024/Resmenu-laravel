/**
 * Playwright screenshot parity harness (optional CI check).
 *
 * Setup:
 *   npm init -y && npm i -D @playwright/test
 *   npx playwright install chromium
 *
 * Usage:
 *   LEGACY_BASE=http://localhost/Resmenu LARAVEL_BASE=http://localhost:8000 node scripts/ui-parity-screenshots.mjs
 *
 * Compares Laravel pages against legacy at 375/768/1280 widths.
 * Golden screenshots from legacy are stored under tests/ui-goldens/legacy/
 * Laravel screenshots under tests/ui-goldens/laravel/
 */

import { chromium } from '@playwright/test';
import fs from 'fs';
import path from 'path';

const LEGACY_BASE = (process.env.LEGACY_BASE || 'http://localhost/Resmenu').replace(/\/$/, '');
const LARAVEL_BASE = (process.env.LARAVEL_BASE || 'http://localhost:8000').replace(/\/$/, '');
const OUT = process.env.OUT || 'tests/ui-goldens';
const WIDTHS = [375, 768, 1280];

const PAGES = [
  { name: 'admin-dashboard', legacy: '/admin/dashboard.php', laravel: '/admin/dashboard' },
  { name: 'admin-restaurants', legacy: '/admin/restaurants.php', laravel: '/admin/restaurants' },
  { name: 'admin-payments', legacy: '/admin/payments.php', laravel: '/admin/payments' },
  { name: 'admin-subscriptions', legacy: '/admin/subscriptions.php', laravel: '/admin/subscriptions' },
  { name: 'manager-dashboard', legacy: '/manager/dashboard.php', laravel: '/manager/dashboard' },
  { name: 'login', legacy: '/index.php', laravel: '/login' },
];

async function capture(browser, base, route, label, name, width) {
  const page = await browser.newPage({ viewport: { width, height: 900 } });
  const url = `${base}${route}`;
  try {
    await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
    const dir = path.join(OUT, label, String(width));
    fs.mkdirSync(dir, { recursive: true });
    await page.screenshot({ path: path.join(dir, `${name}.png`), fullPage: true });
    console.log(`OK ${label} ${width}px ${name}`);
  } catch (e) {
    console.error(`FAIL ${label} ${width}px ${name}: ${e.message}`);
  } finally {
    await page.close();
  }
}

const browser = await chromium.launch();
for (const { name, legacy, laravel } of PAGES) {
  for (const width of WIDTHS) {
    await capture(browser, LEGACY_BASE, legacy, 'legacy', name, width);
    await capture(browser, LARAVEL_BASE, laravel, 'laravel', name, width);
  }
}
await browser.close();
console.log(`Screenshots saved under ${OUT}/`);
