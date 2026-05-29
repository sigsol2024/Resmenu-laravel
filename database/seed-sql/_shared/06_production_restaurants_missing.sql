-- Restaurants present in production (sigsolmenu_resmenu.sql) but without dedicated menu seed-sql files.
-- Idempotent by slug. Import full production data for complete menus: see docs/DATABASE_PARITY.md

SET NAMES utf8mb4;

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations, description, phone, address)
SELECT 'LAVA', 'lava', 'info@lava.com', 'jamesamaila07@gmail.com', 3, 1, 1, 1,
  'Premium dining experience with exquisite cuisine and fine beverages', '+234 800 000 0000',
  'LAVA., 5 Adetokunbu Ademola Street, Victoria Island'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'lava');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations, description, phone, address)
SELECT 'Theview Hotel Lekki', 'theview-hotel', 'reservations@theviewlekki.com', 'reservations@theviewlekki.com', 6, 1, 1, 1,
  'Our restaurant offers the best platters like our very popular Ogazi Platter.',
  '+23490 9091 3608', '1, Godwin Omene Street, Chief Collins Uchidiuno, Off Fola Osibo, Lekki Phase 1, Lagos'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'theview-hotel');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations, description, phone, address)
SELECT 'NOSTALGIA', 'nostalgia-menu', 'info@nostalgialagos.com', 'admin@nostalgia.our-menu.online', 18, 1, 0, 0,
  '', '+234 911 311 9337', '88 Hakeem Dickson Road, Lekki Phase 1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'nostalgia-menu');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations, description, phone, address)
SELECT 'Ellipse Hotels', 'ellipse-hotels', 'ellipsehotelslagos@gmail.com', 'ellipsehotelslagos@gmail.com', 1, 1, 0, 1,
  'Luxury and Comfort Redefined', '08109453960', 'N0 31 Shola Adewumi Street, Bucknor Ejigbo Lagos State'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'ellipse-hotels');
