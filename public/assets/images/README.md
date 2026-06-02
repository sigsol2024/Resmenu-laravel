# Template preview demo images

Used only by `/templates/{id}/preview` (marketing demos). Live restaurant menus use `public/uploads/`.

## Where files live

Put photos in **this folder** (`public/assets/images/`), e.g. `5zm3C5SMKk7sgdYHRUP5eAlb86fe9.jpg`.

Templates request URLs like `/assets/images/menu-items/{file}` and `/assets/images/categories/{file}`.
Apache rewrites those to the same file in this root folder (see `public/.htaccess` and project `.htaccess`).
You do **not** need duplicate copies in `menu-items/` on the server unless you prefer physical subfolders.

Mapping: `config/template_preview_images.php`.

## Deploy

1. Upload new/changed `*.jpg` / `*.png` files to `public/assets/images/` on the server.
2. Deploy updated `.htaccess` rules (required for menu-items/category URLs).
3. Optional: `php artisan resmenu:sync-preview-images` to mirror files into `menu-items/` and `categories/` locally.

Hero/cover images use `/assets/images/{file}` directly.
