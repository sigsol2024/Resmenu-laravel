# Template preview demo images

Used only by `/templates/{id}/preview` (marketing demos). Live restaurant menus use `public/uploads/`.

## Layout

| Path | Purpose |
|------|---------|
| `menu-items/*.jpg` (and `.png`) | Item photos — URL: `{APP_URL}/assets/images/menu-items/{file}` |
| `categories/*.jpg` | Category headers — URL: `{APP_URL}/assets/images/categories/{file}` |
| Root `*.jpg` / `*.png` | Source copies (optional); run sync to populate subfolders |

Mapping is defined in `config/template_preview_images.php`.

## Deploy / update server

After pulling code (or adding new photos to the root folder):

```bash
php artisan resmenu:sync-preview-images
```

Commit the `menu-items/` and `categories/` copies so production serves them without running the command.

Hero/cover images use `{APP_URL}/assets/images/{file}` directly (same root folder is fine).
