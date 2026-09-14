const fs = require('fs');
const path = require('path');

const root = String.raw`C:\Users\user pc\OneDrive\Documents\GitHub\Resmenu_Digital\Resmenu-laravel\resources\views\menu\php-templates`;

for (const dir of fs.readdirSync(root)) {
  if (!/^template\d+$/.test(dir)) continue;
  const p = path.join(root, dir, 'index.php');
  if (!fs.existsSync(p)) continue;
  let t = fs.readFileSync(p, 'utf8');
  if (t.includes('data-template-preview-hero')) {
    console.log('ok', dir);
    continue;
  }

  // Prefer common hero section patterns; else first <section after <body>.
  const patterns = [
    /(<section\b[^>]*\b(?:id|class)=["'][^"']*\bhero\b[^"']*["'][^>]*)(>)/i,
    /(<section\b[^>]*\b(?:id|class)=["'][^"']*\b(?:landing|cover|jumbotron)\b[^"']*["'][^>]*)(>)/i,
    /(<header\b[^>]*\b(?:id|class)=["'][^"']*\bhero\b[^"']*["'][^>]*)(>)/i,
  ];

  let updated = false;
  for (const re of patterns) {
    if (re.test(t)) {
      t = t.replace(re, (m, open, close) => {
        if (open.includes('data-template-preview-hero')) return m;
        return `${open} data-template-preview-hero${close}`;
      });
      updated = true;
      break;
    }
  }

  if (!updated) {
    const bodyIdx = t.search(/<body\b[^>]*>/i);
    if (bodyIdx >= 0) {
      const after = t.slice(bodyIdx);
      const sec = after.match(/<section\b[^>]*>/i);
      if (sec && sec.index != null) {
        const abs = bodyIdx + sec.index;
        const tag = sec[0];
        if (!tag.includes('data-template-preview-hero')) {
          const tagged = tag.replace(/>$/, ' data-template-preview-hero>');
          t = t.slice(0, abs) + tagged + t.slice(abs + tag.length);
          updated = true;
        }
      }
    }
  }

  if (!updated) {
    // Last resort: inject an explicit capture section immediately after <body>.
    t = t.replace(
      /(<body\b[^>]*>)/i,
      '$1\n<section data-template-preview-hero class="resmenu-template-preview-hero" style="display:block;min-height:70vh;"></section>'
    );
    updated = true;
  }

  fs.writeFileSync(p, t);
  console.log('updated', dir);
}
