/**
 * Stage Alo Alo images into database/data/alo_alo/images from image_sources.php
 * Usage: node database/data/alo_alo/stage_images.mjs
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '../../..');
const publicRoot = path.join(root, 'public');
const imagesRoot = path.join(__dirname, 'images');
const sourcesPhp = path.join(__dirname, 'image_sources.php');

// Parse simple PHP return array of 'key' => 'value'
const php = fs.readFileSync(sourcesPhp, 'utf8');
const map = {};
const re = /'([^']+)'\s*=>\s*'([^']+)'/g;
let m;
while ((m = re.exec(php))) {
  map[m[1]] = m[2];
}

function ensureDir(p) {
  fs.mkdirSync(p, { recursive: true });
}

async function fetchUrl(url, dest) {
  const res = await fetch(url, { headers: { 'User-Agent': 'ResmenuAloAloSeed/1.0' } });
  if (!res.ok) throw new Error(`HTTP ${res.status} for ${url}`);
  const buf = Buffer.from(await res.arrayBuffer());
  if (buf.length < 500) throw new Error(`too small ${url}`);
  fs.writeFileSync(dest, buf);
  return buf.length;
}

let ok = 0;
let fail = 0;
const entries = Object.entries(map);
for (const [key, source] of entries) {
  const dest = path.join(imagesRoot, ...key.split('/'));
  ensureDir(path.dirname(dest));
  if (fs.existsSync(dest) && fs.statSync(dest).size > 500) {
    ok++;
    continue;
  }
  try {
    if (source.startsWith('http://') || source.startsWith('https://')) {
      const n = await fetchUrl(source, dest);
      console.log('DL', key, n);
      ok++;
    } else {
      const local = path.join(publicRoot, source.replace(/\//g, path.sep));
      if (!fs.existsSync(local)) throw new Error(`missing local ${local}`);
      fs.copyFileSync(local, dest);
      console.log('CP', key);
      ok++;
    }
  } catch (e) {
    console.log('FAIL', key, e.message);
    fail++;
  }
}
console.log(`Done ok=${ok} fail=${fail} total=${entries.length}`);
