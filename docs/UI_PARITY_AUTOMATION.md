# UI Parity Automation

## DOM diff (no dependencies)

Compare normalized HTML and CSS class usage between legacy and Laravel:

```bash
php database/scripts/dom_parity_diff.php \
  --legacy-url="http://localhost/Resmenu/admin/dashboard.php" \
  --laravel-url="http://localhost:8000/admin/dashboard"
```

Exit code `0` = identical normalized HTML; `1` = differences (see JSON output).

## Screenshot harness (Playwright)

```bash
cd Resmenu-laravel
npm init -y
npm i -D @playwright/test
npx playwright install chromium

LEGACY_BASE=http://localhost/Resmenu \
LARAVEL_BASE=http://localhost:8000 \
node scripts/ui-parity-screenshots.mjs
```

Screenshots saved to `tests/ui-goldens/legacy/{width}/` and `tests/ui-goldens/laravel/{width}/`.
Compare visually or add pixel-diff in CI as needed.

## Extract legacy page body (helper)

```bash
php database/scripts/extract_legacy_page_body.php ../Resmenu/admin/dashboard.php
```

## Fix menu template asset paths

```bash
php database/scripts/fix_menu_template_asset_paths.php
```
