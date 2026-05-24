-- Staging bootstrap: minimal restaurant rows required by database/seed-sql/restaurants/*.sql
-- Idempotent by slug. Safe to re-run after migrate on empty DB.

SET NAMES utf8mb4;

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations)
SELECT 'The Lusso Restaurant', 'the-lusso-restaurant', 'restaurant@lussohotelsabuja.com', 'restaurant@lussohotelsabuja.com', 1, 1, 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'the-lusso-restaurant');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations)
SELECT 'Mania House', 'mania-house', 'admin@maniahouse.our-menu.online', 'admin@maniahouse.our-menu.online', 4, 1, 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'mania-house');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations)
SELECT 'Opal Lagos', 'opal-lagos', 'opallagos1@gmail.com', 'opallagos1@gmail.com', 4, 1, 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'opal-lagos');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations)
SELECT 'Salt and Social', 'salt-and-social', 'admin@saltandsocial.our-menu.online', 'admin@saltandsocial.our-menu.online', 4, 1, 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'salt-and-social');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations)
SELECT 'Swiss The Vistana', 'swiss-the-vistana', 'it.vistana@swissinternationalhotels.com', 'it.vistana@swissinternationalhotels.com', 4, 1, 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'swiss-the-vistana');

INSERT INTO restaurants (name, slug, email, manager_email, template_id, is_active, enable_food_ordering, enable_table_reservations)
SELECT 'Vendome Cafe', 'vendome-cafe-s-menu', 'admin@vendomecafe.our-menu.online', 'admin@vendomecafe.our-menu.online', 4, 1, 1, 1
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM restaurants WHERE slug = 'vendome-cafe-s-menu');
