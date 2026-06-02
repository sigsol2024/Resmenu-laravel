# Template preview demo images

Used by `/templates/{id}/preview` when the database has no menu/category images to reuse.

Place files on the **Laravel app host** (`our-menu.online`):

| Path | Purpose |
|------|---------|
| `menu-items/preview-item-01.jpg` … `preview-item-40.jpg` | Menu item photos in template previews |
| `categories/preview-cat-starters.jpg` | Starters / salads category headers |
| `categories/preview-cat-mains.jpg` | Mains / sides / pasta |
| `categories/preview-cat-desserts.jpg` | Desserts / pastries |
| `categories/preview-cat-drinks.jpg` | Drinks categories |

URLs resolve as: `{APP_URL}/assets/images/menu-items/{filename}`.

If the shared database already has `menu_items.image` / `categories.image`, previews use `{UPLOAD_URL}/menu-items/` and `{UPLOAD_URL}/categories/` instead (live production uploads).
