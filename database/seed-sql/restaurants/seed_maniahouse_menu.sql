-- Seed: WING MANIA + MANIA BRUNCH + HOOKAH MANIA — Mania House
-- Regenerate this file: node build_mania_seed.mjs
-- Restaurant: admin@maniahouse.our-menu.online (restaurants.email OR restaurants.manager_email)
-- Run AFTER migration.sql. Safe to re-run: categories/items use NOT EXISTS guards.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

SET @rid = (
  SELECT id FROM restaurants
  WHERE email = 'admin@maniahouse.our-menu.online'
     OR manager_email = 'admin@maniahouse.our-menu.online'
  LIMIT 1
);

-- ----- SECTIONS -----
SET @sid_wm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'wing-mania' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'WING MANIA', 'wing-mania', 1, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_wm IS NULL;
SET @sid_wm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'wing-mania' LIMIT 1);

SET @sid_mb = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'mania-brunch' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'MANIA BRUNCH', 'mania-brunch', 2, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_mb IS NULL;
SET @sid_mb = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'mania-brunch' LIMIT 1);

SET @sid_hm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'hookah-mania' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'HOOKAH MANIA', 'hookah-mania', 3, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_hm IS NULL;
SET @sid_hm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'hookah-mania' LIMIT 1);

-- ----- WING MANIA — categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Lord of the Wings', 'wm-lord', 1, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-lord')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Waffle combos', 'wm-waffle', 2, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-waffle')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Wings on fire challenge (poppers)', 'wm-poppers', 3, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-poppers')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Choose your flavor', 'wm-flavors', 4, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Choose your dip', 'wm-dips', 5, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-dips')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Sides', 'wm-sides', 6, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-sides')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Combo deals', 'wm-combo', 7, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Kids zone meals', 'wm-kids', 8, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-kids')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Sweet treats', 'wm-sweets', 9, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-sweets')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_wm, 'Wings on fire challenge', 'wm-challenge', 10, 1 FROM DUAL
WHERE @sid_wm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'wm-challenge')
LIMIT 1;

-- Wing Mania items
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '6pcs wings', 'wm-6pcs', 'LORD OF THE WINGS! Choose your flavor & dip.', 12000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-lord'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-6pcs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '8pcs wings', 'wm-8pcs', 'LORD OF THE WINGS! Choose your flavor & dip.', 13500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-lord'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-8pcs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '10pcs wings', 'wm-10pcs', 'LORD OF THE WINGS! Choose your 1 flavor & 1 dip.', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-lord'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-10pcs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '15pcs wings', 'wm-15pcs', 'LORD OF THE WINGS! Choose up to 2 flavors & 1 dip.', 20500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-lord'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-15pcs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '20pcs wings', 'wm-20pcs', 'LORD OF THE WINGS! Choose up to 2 flavors & 1 dip.', 22000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-lord'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-20pcs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '30pcs wings', 'wm-30pcs', 'LORD OF THE WINGS! Choose up to 3 flavors & 1 dip.', 30000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-lord'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-30pcs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'WAFFLE UP POWER UP!', 'wm-waffle-power', 'Waffles, chicken tenders in flavor of choice & cheesy Mac.', 12000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-waffle'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-waffle-power'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DUNKED', 'wm-dunked', 'Waffles, chicken tenders in flavor of choice.', 10000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-waffle'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-dunked'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'HULK', 'wm-hulk', 'Waffles, chicken tenders in flavor of choice, classic French fries & ketchup.', 12000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-waffle'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-hulk'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BIGGIE', 'wm-biggie', 'Waffles, chicken tenders in flavor of choice, classic French fries, chicken poppers, 6pcs wings in flavor of choice & ketchup.', 20000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-waffle'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-biggie'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHAIRMAN', 'wm-chairman', 'Waffles, chicken tenders in flavor of choice, seasoned wedges, chicken poppers & ketchup.', 30000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-waffle'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-chairman'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKUTERIE', 'wm-chickuterie', 'Waffles, chicken tenders in flavor of choice, seasoned wedges, chicken poppers, 8 wings in flavor of choice, coleslaw & ketchup.', 30000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-waffle'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-chickuterie'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'POP IT LIKE ITS HOT OG', 'wm-pop-og', '10pcs chicken poppers (choose your flavor & dip).', 8000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-poppers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-pop-og'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'RANGER', 'wm-ranger', 'Chicken poppers loaded fries with peri peri spice mix, cheese & ranch sauce.', 15000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-poppers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-ranger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BOSSMAN', 'wm-bossman', '20pcs chicken poppers (choose your flavor & dip).', 12000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-poppers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-bossman'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'THE SHAKER', 'wm-shaker', '25pcs chicken poppers with suya spice.', 9000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-poppers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-shaker'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'POP STARS', 'wm-pop-stars', '30pcs smothered hot chicken poppers, seasoned wedges & ketchup.', 13000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-poppers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-pop-stars'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coleslaw', 'wm-side-coleslaw', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-side-coleslaw'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic French fries', 'wm-side-fries', NULL, 6500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-side-fries'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seasoned Potato wedges', 'wm-side-wedges', NULL, 7500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-side-wedges'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spicy suya fries', 'wm-side-suya', NULL, 8000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-side-suya'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cheesy mac', 'wm-side-mac', NULL, 7000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-side-mac'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cajun fried corn', 'wm-side-corn', NULL, 6000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-side-corn'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TORNADO', 'wm-combo-tornado', 'Mango habanero chicken tender sandwich, classic French fries & ketchup.', 12500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-combo-tornado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'THE BIG BANG', 'wm-combo-big-bang', 'Cajun chicken tender double cheese burger, coleslaw, seasoned wedges & ketchup.', 16000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-combo-big-bang'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TRAFFIC', 'wm-combo-traffic', '8pcs wings in flavor of choice, Cajun fried corn, French fries & ketchup.', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-combo-traffic'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CITIZEN', 'wm-combo-citizen', 'Smothered hot chicken poppers, 8pcs wings in flavor of choice, spicy suya fries & ketchup.', 15000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-combo-citizen'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SUPERBOWL', 'wm-combo-superbowl', 'Superbowl salad: lettuce, sweet corn, purple cabbage, tomatoes, cheese shavings, croutons, chopped sweet chili tenders & lemon honey vinaigrette.', 12500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-combo-superbowl'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'EMPIRE', 'wm-combo-empire', 'Boneless jerk wing cheese burger, French fries & ketchup.', 15000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-combo-empire'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SUB', 'wm-combo-sub', 'Boneless teriyaki wings wrap: tortilla, lettuce, cheese shavings, avocado lime sauce, boneless teriyaki wings, classic French fries & ketchup.', 12500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-combo'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-combo-sub'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SONIC', 'wm-kids-sonic', '4pcs hickory BBQ wings, kid fries, ketchup & chi smart malt drink.', 7000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-kids'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-kids-sonic'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PANDA', 'wm-kids-panda', 'Kid fries, 2pcs sweet chili tenders, ketchup, a pack of reel fruits & chi smart malt drink.', 8500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-kids'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-kids-panda'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'RUGRATS', 'wm-kids-rugrats', '8pcs sweet chili chicken poppers, cheesy mac & chi smart malt drink.', 8000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-kids'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-kids-rugrats'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BUZZ', 'wm-kids-buzz', 'Waffles, 2pcs BBQ tenders, kid fries, ketchup, a pack of reel fruits & chi smart malt drink.', 12000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-kids'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-kids-buzz'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chocolate sundae', 'wm-sweet-sundae', NULL, 7500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-sweet-sundae'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mini churros & chocolate dip', 'wm-sweet-churros', NULL, 5500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-sweet-churros'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple pies', 'wm-sweet-apple-pies', NULL, 6000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-sweet-apple-pies'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BLIZZARD', 'wm-sweet-blizzard', NULL, 8500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-sweet-blizzard'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Soft ice-cream (₦1,500)', 'wm-sweet-ice-single', NULL, 1500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-sweet-ice-single'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Soft ice-cream (₦5,000)', 'wm-sweet-ice-regular', NULL, 5000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-sweet-ice-regular'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Wings on Fire challenge (rules)', 'wm-wof-concept', 'Customers order 20pcs of incredibly hot wings, to be consumed in 60 seconds without any liquid (monitored). If they finish in time: a special meal for free and signature on the illustrious winner wall.', 0, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-challenge'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-wof-concept'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Wings on Fire challenge (order)', 'wm-wof-order', 'Challenge entry / pricing at venue.', 0, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-challenge'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-wof-order'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mango habanero', 'wm-fl-1', 'Flavor option (no separate charge).', 0, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic mild buffalo', 'wm-fl-2', 'Flavor option (no separate charge).', 0, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sweet chili', 'wm-fl-3', 'Flavor option (no separate charge).', 0, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cajun', 'wm-fl-4', 'Flavor option (no separate charge).', 0, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lemon pepper', 'wm-fl-5', 'Flavor option (no separate charge).', 0, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fire power', 'wm-fl-6', 'Flavor option (no separate charge).', 0, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hickory BBQ', 'wm-fl-7', 'Flavor option (no separate charge).', 0, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Teriyaki', 'wm-fl-8', 'Flavor option (no separate charge).', 0, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jerk', 'wm-fl-9', 'Flavor option (no separate charge).', 0, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lemon garlic', 'wm-fl-10', 'Flavor option (no separate charge).', 0, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-flavors'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-fl-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spicy honey mustard', 'wm-dip-1', 'Dip option.', 0, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-dips'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-dip-1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bangbang', 'wm-dip-2', 'Dip option.', 0, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-dips'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-dip-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Randy''s ranch', 'wm-dip-3', 'Dip option.', 0, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'wm-dips'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'wm-dip-3'
  )
LIMIT 1;

-- ----- MANIA BRUNCH — categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mb, 'Breakfast', 'mb-breakfast', 1, 1 FROM DUAL
WHERE @sid_mb IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mb-breakfast')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mb, 'Starter', 'mb-starters', 2, 1 FROM DUAL
WHERE @sid_mb IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mb-starters')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mb, 'Soups', 'mb-soups', 3, 1 FROM DUAL
WHERE @sid_mb IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mb-soups')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mb, 'Main dish', 'mb-mains', 4, 1 FROM DUAL
WHERE @sid_mb IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mb-mains')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mb, 'Champagne & wines', 'mb-wines', 5, 1 FROM DUAL
WHERE @sid_mb IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines')
LIMIT 1;
-- Mania Brunch items
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'English Breakfast', 'mb-eng', 'A classic full English plate featuring: golden toast with fluffy scrambled eggs, grilled cherry tomatoes & sautéed mushrooms, juicy sausages & warm baked beans. A hearty, traditional breakfast to start your day right.', 25000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-eng'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'American Breakfast', 'mb-usa', 'A rich, indulgent spread of: fluffy pancakes drizzled with maple syrup, tender beef steak with broccoli & potato sides, fresh farm eggs cooked to your style. A bold and satisfying all-American morning treat.', 30000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-usa'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Caesar Salad', 'mb-caesar', 'Classic Caesar with grilled chicken, parmesan, crunchy croutons, and creamy Greek yogurt dressing.', 20000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-starters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-caesar'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Conch Salad', 'mb-conch', 'A refreshing mix of calamari, shrimps, bell peppers, pineapple, and Dijon mustard with a spicy habanero kick.', 25000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-starters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-conch'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Caprese Salad with flank steak', 'mb-caprese', 'Mozzarella, avocado, sweet basil and pickles, drizzled with olive oil and Dijon mustard, topped with juicy flank steak.', 25000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-starters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-caprese'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Assorted Pepper Soup', 'mb-pepper', 'Traditional spiced broth with assorted meats, scent leaves, and peppers.', 17000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-pepper'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ginger & Carrot Soup', 'mb-ginger', 'A velvety blend of carrots, ginger, Irish potatoes, and cream.', 12000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-ginger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Creamy Jackpasta with Stuffed Chicken Breast', 'mb-jackpasta', 'Velvety jack-cheese pasta served with tender, herb-stuffed chicken breast.', 30000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-jackpasta'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Butter Saffron Rice with Seafood Sauce & Asparagus', 'mb-saffron', 'Fragrant saffron basmati rice topped with juicy prawns and buttery seafood sauce, finished with crisp asparagus.', 40000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-saffron'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jamaican Oxtail Stew with Rice & Peas', 'mb-oxtail', 'Slow-braised oxtail in rich Caribbean spices, served with coconut rice and kidney beans.', 30000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-oxtail'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Clicquot Brut', 'mb-vcb', 'Bold and crisp, with notes of apple and brioche.', 350000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-vcb'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Clicquot Rosé', 'mb-vcr', 'Vibrant and fruity, with red berry aromas.', 410000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-vcr'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët & Chandon Brut', 'mb-mb', 'Classic champagne, fresh citrus and floral hints.', 386000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-mb'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët & Chandon Rosé', 'mb-mr', 'Elegant rosé with wild strawberry and raspberry notes.', 446000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-mr'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët & Chandon Imperial Brut', 'mb-mi', 'Signature style, balanced with apple and citrus zest.', 398000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-mi'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amabile Red', 'mb-ar', 'Medium-bodied, soft berry flavour.', 45000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-ar'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amabile Rosé', 'mb-aro', 'Semi-sweet, smooth, fruity finish.', 45000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-aro'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carlo Rossi Red', 'mb-crr', 'Smooth, medium-bodied with ripe berry flavours and a soft finish.', 45000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-crr'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carlo Rossi White', 'mb-crw', 'Light, crisp, and refreshing with fruity notes.', 45000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mb-wines'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mb-crw'
  )
LIMIT 1;

-- ----- HOOKAH MANIA — categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Munchies & plates', 'hm-munch', 1, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Fish & rice mains', 'hm-fish', 2, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Sides', 'hm-sides', 3, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-sides')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Sweets', 'hm-sweets', 4, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-sweets')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Soft drinks (shared)', 'shared-soft-drinks', 5, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Juices (shared)', 'shared-juices', 6, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Milkshakes (shared)', 'shared-milkshakes', 7, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'shared-milkshakes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Smoothies (shared)', 'shared-smoothies', 8, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'shared-smoothies')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Champagne', 'hm-champagne', 5, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Whiskey', 'hm-whiskey', 6, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Cognac', 'hm-cognac', 7, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-cognac')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Gin', 'hm-gin', 8, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-gin')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Vodka', 'hm-vodka', 9, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-vodka')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Tequila', 'hm-tequila', 10, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Beer', 'hm-beer', 11, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-beer')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'White wine', 'hm-white', 12, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-white')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Rosé wine', 'hm-rose', 13, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-rose')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Red wine', 'hm-red', 14, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-red')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Cocktails', 'hm-cocktails', 15, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Mocktails', 'hm-mocktails', 16, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-mocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_hm, 'Smoothies', 'hm-smooth', 17, 1 FROM DUAL
WHERE @sid_hm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'hm-smooth')
LIMIT 1;
-- Hookah Mania items
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'MUNCHIEZ DIPERZ', 'hm-m1', 'Tortilla nachos & bang bang dip.', 10000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'AFRICAN GIANT PLATTER', 'hm-m2', 'Consisting of wings, chili beef chunks, peppered snails, puff puff, kelewele, prawn skewers, mosa, fried yam, goat chunks and pepper sauce.', 70000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'YING YANG', 'hm-m3', '2 flavor calamari. Pan chili calamari & deep fried calamari with garlic mayo.', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CAPRI', 'hm-m4', 'Deep fried goat chunks tossed in green chili with fried yam.', 17000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'MEX', 'hm-m5', 'Mince and cheese taquitos with simple salsa.', 10500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DYNAMITEZ', 'hm-m6', 'Prawn dynamites.', 25000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'RELOAD ALOHA', 'hm-m7', 'Chicken Caesar salad and dressing.', 15000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'THAI TANIC', 'hm-m8', 'Thai fisherman soup with garlic bread.', 15000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DUTCH', 'hm-m9', 'One skillet beef and broccoli with steamed rice.', 12500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TUSCANY (chicken/beef)', 'hm-m10', 'An option of chicken or beef pasta.', 20500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'STIR IT UP', 'hm-m11', 'One spicy stir fried rice consisting of shredded beef, shredded chicken, broccoli, assorted bell peppers, spring onion, chili flakes and spices.', 20500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'WAIKIKI', 'hm-m12', 'Surf n turf steak, crushed herbed sweet potatoes, glazed marrow & creamy mushroom sauce.', 60000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'THE G.O.A.T', 'hm-m13', 'Spicy goat rice mix.', 25000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHOPPED', 'hm-m14', 'Succulent lamb chops with creamy mushroom sauce, seasoned wedges or Smokey Jollof and coleslaw.', 70000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TUSCANY (Seafood)', 'hm-m15', 'Seafood pasta.', 22500, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-munch'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-m15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FISH MONAY (Standard)', 'hm-f1', 'Grilled medium tilapia, expertly seasoned and served with rich, spicy pepper sauce.', 40000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CATFISH SUPREME', 'hm-f2', 'Tender catfish in a flavorful pepper soup broth, served with white rice or grilled garlic bread. A perfect blend of spices and fresh herbs.', 25000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'THE POT', 'hm-f3', 'A hearty blend of sweet potato or yam, catfish, smoked fish and aromatic herbs, cooked in rich red oil for a satisfying flavorful meal. Pure comfort in every bite.', 25000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shepherd''s Pie', 'hm-f4', 'A comforting dish made with creamy mashed Irish potatoes, savoury minced meat, and a blend of spices, topped with melted parmesan cheese for a perfect finish. A delicious, hearty meal.', 20000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FISH MONAY (Deluxe)', 'hm-f5', 'Full size tilapia, flamed grilled to perfection with bold spice and signature sauce.', 50000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'THE KINGS CATCH', 'hm-f6', 'Whole grilled catfish: a majestic serving of whole catfish, marinated in bold spices and grilled to tender, smokey perfection. Packed with flavor and served with your preferred side. A true showstopper.', 60000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coconut Rice', 'hm-f7', 'Flavourful coconut-infused rice, served with well-seasoned turkey.', 35000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Special Jollof Rice', 'hm-f8', 'Rich smoky jollof rice served with any protein of your choice (Chicken/Turkey/Fish). Additional charges may apply for premium proteins.', 35000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Special Fried Rice', 'hm-f9', 'Savory fried rice with mixed veggies and spices, served with turkey.', 35000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-fish'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-f9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smokey Jollof rice', 'hm-s1', NULL, 8000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-s1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried yam', 'hm-s2', NULL, 7500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-s2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Steamed rice', 'hm-s3', NULL, 7500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-s3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crushed sweet potatoes', 'hm-s4', NULL, 7500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-s4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PICCASSO', 'hm-sw1', 'French toast pudding, ice cream, syrup & berries.', 10000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-sw1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PIE-RATES', 'hm-sw2', 'Apple crumble & vanilla ice cream.', 12000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-sw2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SUNDAE', 'hm-sw3', 'Ice cream sundae (as listed on drink menu).', 10000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-sweets'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-sw3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Red Bull', 'shared-redbull', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-redbull'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coke', 'shared-coke', NULL, 2000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-coke'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepsi', 'shared-pepsi', NULL, 2000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-pepsi'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sprite', 'shared-sprite', NULL, 2000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-sprite'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fanta', 'shared-fanta', NULL, 2000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-fanta'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '7up', 'shared-7up', NULL, 2000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-7up'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepsi Diet', 'shared-pepsi-diet', NULL, 2000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-pepsi-diet'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepsi wingman', 'shared-pepsi-wingman', NULL, 2000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-pepsi-wingman'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepsi diet wingman', 'shared-pepsi-diet-wingman', NULL, 2000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-pepsi-diet-wingman'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '7up Diet', 'shared-7up-diet', NULL, 2000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-7up-diet'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Miranda', 'shared-miranda', NULL, 2000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-miranda'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Soda water', 'shared-soda-water', NULL, 2000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-soda-water'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tonic', 'shared-tonic', NULL, 2000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-tonic'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bitter lemon', 'shared-bitter-lemon', NULL, 2000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-bitter-lemon'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cranberry Juice (glass)', 'shared-cranberry-glass', NULL, 6000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-cranberry-glass'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cranberry Juice (pitcher)', 'shared-cranberry-pitcher', NULL, 15000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-cranberry-pitcher'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orange Juice (glass)', 'shared-orange-glass', NULL, 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-orange-glass'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orange Juice (pitcher)', 'shared-orange-pitcher', NULL, 12000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-orange-pitcher'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple Juice (glass)', 'shared-pineapple-glass', NULL, 5000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-pineapple-glass'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple Juice (pitcher)', 'shared-pineapple-pitcher', NULL, 12000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-pineapple-pitcher'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple Juice (glass)', 'shared-apple-glass', NULL, 5000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-apple-glass'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple Juice (pitcher)', 'shared-apple-pitcher', NULL, 12000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-apple-pitcher'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivita Orange Juice', 'shared-chivita-orange', NULL, 12000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-chivita-orange'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivita Pineapple Juice', 'shared-chivita-pineapple', NULL, 12000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-chivita-pineapple'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivita Apple Juice', 'shared-chivita-apple', 'As listed on menu (Chivita Apple Juice…).', 12000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-chivita-apple'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Perrier', 'shared-perrier', NULL, 5000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-perrier'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry milkshake', 'shared-milkshake-strawberry', NULL, 12500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-milkshake-strawberry'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Salted caramel milk shake', 'shared-milkshake-salted-caramel', NULL, 12500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-milkshake-salted-caramel'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'S’mores chocolate milkshake', 'shared-milkshake-smores', NULL, 12500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-milkshake-smores'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Oreo cheesecake milkshake', 'shared-milkshake-oreo-cheesecake', NULL, 12500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-milkshake-oreo-cheesecake'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Banana & Mango', 'shared-smoothie-banana-mango', NULL, 12500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-smoothies'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-smoothie-banana-mango'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon & Strawberry', 'shared-smoothie-watermelon-strawberry', NULL, 12500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-smoothies'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-smoothie-watermelon-strawberry'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BANANA&STRAWBERRY', 'shared-smoothie-banana-strawberry', NULL, 12500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-smoothies'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-smoothie-banana-strawberry'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'KALE Green', 'shared-smoothie-kale-green', NULL, 12500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-smoothies'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'shared-smoothie-kale-green'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Clicquot Brut', 'hm-ch1', NULL, 350000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Clicquot Rosé', 'hm-ch2', NULL, 410000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët et Chandon Brut', 'hm-ch3', NULL, 386000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët et Chandon Rosé', 'hm-ch4', NULL, 446000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët et Chandon Imperial Brut', 'hm-ch5', NULL, 398000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dom Pérignon Brut', 'hm-ch6', NULL, 1250000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ace Of Spades', 'hm-ch7', NULL, 1250000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ace Of Spades Rosé', 'hm-ch8', NULL, 1850000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'LP Rosé', 'hm-ch9', NULL, 380000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'LP Brut', 'hm-ch10', NULL, 290000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ch10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnnie Walker Black Label', 'hm-w1', 'Bottle ₦150,000. Glass/5cl ₦15,000.', 150000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnnie Walker Blue Label', 'hm-w2', NULL, 600000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnnie Walker Green Label', 'hm-w3', NULL, 300000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnnie Walker Gold Label', 'hm-w4', NULL, 250000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jameson Irish Original', 'hm-w5', NULL, 195000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 12', 'hm-w6', 'Bottle ₦200,000. Glass/5cl ₦20,000.', 200000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 15', 'hm-w7', NULL, 370000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 18', 'hm-w8', NULL, 450000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 21', 'hm-w9', NULL, 750000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Monkey Shoulder', 'hm-w10', 'Bottle ₦130,000. Glass/5cl ₦12,000.', 130000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan 12', 'hm-w11', 'Bottle ₦150,000. Glass/5cl ₦15,000.', 150000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan 15', 'hm-w12', NULL, 280000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan 18', 'hm-w13', NULL, 550000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jameson Black Barrel', 'hm-w14', NULL, 250000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Balvenie 12', 'hm-w15', NULL, 221000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Balvenie 14', 'hm-w16', NULL, 300000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jack Daniel''s', 'hm-w17', NULL, 220000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'The Singleton', 'hm-w18', NULL, 225000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'The Pogues', 'hm-w19', NULL, 100000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-w19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VSOP', 'hm-cg1', NULL, 400000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-cg1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VS', 'hm-cg2', 'Glass 5cl ₦20,000', 300000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-cg2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martell Blue Swift', 'hm-cg3', NULL, 300000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-cg3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martell XO', 'hm-cg4', NULL, 780000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-cg4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy XO', 'hm-cg5', NULL, 800000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-cg5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hendrick''s', 'hm-g1', 'Glass 5cl ₦18,000', 235000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-g1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin Mare', 'hm-g2', 'Glass 5cl ₦9,000', 160000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-g2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tanqueray No. Ten', 'hm-g3', NULL, 195000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-g3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bombay Sapphire', 'hm-g4', 'Glass 5cl ₦13,500', 167000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-g4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Monkey 47', 'hm-g5', NULL, 150000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-g5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cape Town', 'hm-g6', 'Glass 5cl ₦11,500', 180000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-g6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Belvedere', 'hm-v1', NULL, 200000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-v1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grey Goose', 'hm-v2', NULL, 150000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-v2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Absolut', 'hm-v3', 'Glass 5cl ₦10,000', 155000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-v3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cîroc', 'hm-v4', NULL, 150000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-v4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jose Cuervo (premium)', 'hm-t1', 'Shot ₦12,000', 160000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jose Cuervo (standard)', 'hm-t2', 'Shot ₦7,000', 110000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Añejo', 'hm-t3', NULL, 550000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Reposado', 'hm-t4', NULL, 520000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Julio 1942', 'hm-t5', NULL, 900000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Julio Reposado', 'hm-t6', NULL, 550000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Patrón Blanco', 'hm-t7', 'Glass 5cl ₦15,000', 200000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Patrón Reposado', 'hm-t8', NULL, 200000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Patrón Añejo', 'hm-t9', NULL, 350000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clase Azul Reposado', 'hm-t10', NULL, 950000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clase Azul Añejo', 'hm-t11', NULL, 2500000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vivir Blanco', 'hm-t12', NULL, 270000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vivir Reposado', 'hm-t13', NULL, 350000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Teremana Reposado', 'hm-t14', NULL, 500000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-t14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Heineken Draft', 'hm-b1', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-b1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Guinness / Legend', 'hm-b2', NULL, 5000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-b2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tiger', 'hm-b3', NULL, 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-b3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Star Radler Can', 'hm-b4', NULL, 4000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-b4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DC Sweet (white, bottle)', 'hm-ww1', 'Bottle ₦45,000; glass ₦20,000 (per menu).', 45000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-white'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ww1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DC Dry Chenin (white, bottle)', 'hm-ww2', 'Bottle ₦45,000; glass ₦20,000.', 45000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-white'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ww2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DC Dry Sweet (white, bottle)', 'hm-ww-drysweet', 'Menu listing (Dc SDc Dryweet). Bottle ₦45,000; glass ₦20,000.', 45000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-white'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ww-drysweet'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sungoddess (white)', 'hm-ww3', NULL, 100000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-white'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ww3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Santa Rita 120 Chardonnay', 'hm-ww4', NULL, 100000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-white'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ww4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amabile Sweet (white)', 'hm-ww5', 'Juicy Italian white wine.', 45000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-white'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ww5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sungoddess Pinot Grigio', 'hm-rw1', NULL, 100000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-rose'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rw1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ermelinda Tulipa Rosé', 'hm-rw2', NULL, 50000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-rose'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rw2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amabili Di Rosa', 'hm-rw3', 'Juicy Italian wine.', 40000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-rose'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rw3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DC Sweet Red (bottle)', 'hm-rd1', 'Glass ₦16,000.', 40000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amabile Di Rosa (red)', 'hm-rd2', 'Glass ₦16,000.', 40000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DC Sweet Red (sweet & smooth)', 'hm-rd-sweet', 'Sweet & smooth with fruits & flowers. Glass ₦16,000.', 40000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd-sweet'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DC Dry Red', 'hm-rd3', 'Glass ₦16,000.', 40000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bla Bla', 'hm-rd4', NULL, 60000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Escudo Rojo', 'hm-rd5', 'Glass ₦18,000.', 60000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carlo Rossi (red)', 'hm-rd6', NULL, 40000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '4 Cousins', 'hm-rd7', NULL, 40000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Prosecco Rosario', 'hm-rd8', 'Glass 5cl ₦9,000.', 42000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sungoddess (red)', 'hm-rd9', NULL, 65000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-red'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-rd9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mr Flinstone', 'hm-ck1', 'Gin, pineapple, amaro, yellow chartreuse, honey/ginger syrup, lemon juice & aromatic bitters.', 22500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Roller coaster', 'hm-ck2', 'Vodka, Midori, Cointreau, lemon juice, egg white & aquafaba.', 22500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orange & Basil', 'hm-ck3', 'Gin, orange & basil cordial, lime cordial.', 22500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peer pressure', 'hm-ck4', 'Apple cider vinegar, celery, honey syrup, bitters, lemon juice, pear juice, soda, aged rum & Grand Marnier.', 22500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Colder club', 'hm-ck5', 'Fresh fig, fresh raspberries, almond syrup, gin, lemon juice (optional), egg white, aquafaba.', 22500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'The bullshort', 'hm-ck6', 'Vodka, grapefruit juice, red vermouth, elderflower liqueur.', 22500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Borrowed Time', 'hm-ck7', 'Whiskey, triple sec, grapefruit juice, thyme syrup.', 22500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sunny Spritzer', 'hm-ck8', 'Limoncello, lemon soda, Prosecco.', 22500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Berry Whipped', 'hm-ck9', 'Lemon juice, cranberry juice, raspberry liqueur, white rum, aquafaba.', 22500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Frame Up', 'hm-ck10', 'Pineapple juice, Midori, coconut rum, vodka.', 22500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bloody Mary', 'hm-ck11', 'Classic cocktail.', 22500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Long Island Iced Tea', 'hm-ck12', 'Vodka, tequila, gin, triple sec, lemon juice, cola & rum.', 22500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Wild Sex', 'hm-ck13', 'Rum, juice, triple sec, vodka, coconut & rum.', 20000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cosmopolitan', 'hm-ck14', 'Vodka, triple sec, juice & lime juice.', 20000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chapman', 'hm-ck15', NULL, 20000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mai Tai', 'hm-ck16', 'Rum, triple sec, gold rum, lime juice & almond syrup.', 20000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sex On The Beach', 'hm-ck17', 'Vodka, peach liqueur, juice & grenadine.', 20000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin Basil', 'hm-ck18', 'Gin, simple syrup, basil leaf & lemon juice.', 20000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Porn Star Martini', 'hm-ck19', 'Vanilla syrup, vodka, passion fruit liqueur, lime juice & sparkling wine.', 20000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry Daiquiri', 'hm-ck20', 'Rum, lime juice & syrup.', 20000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck20'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whiskey Sour', 'hm-ck21', 'Lemon juice, simple syrup, bitters & egg.', 20000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck21'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margarita', 'hm-ck22', 'Classic.', 22500, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-ck22'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mango Favez', 'hm-mk1', 'Mango puree, fresh mint leaf, lime juice, mango soda.', 18000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-mk1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sunny Breeze', 'hm-mk2', 'Coconut cordial, grapefruit juice, 7up.', 18000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-mk2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Passion Rise', 'hm-mk3', 'Fresh passion fruit, grenadine syrup, orange juice, lime juice.', 18000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-mk3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Goodluck Charm', 'hm-mk4', 'Grapefruit juice, guava juice, strawberry puree, cranberry juice.', 18000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-mk4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peach & Thyme Fizz', 'hm-mk5', 'Peach syrup, lemon juice, soda water.', 18000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-mk5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry margarita', 'hm-mk6', NULL, 22500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-mk6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Banana & Mango smoothie', 'hm-sm1', NULL, 18000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-smooth'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-sm1'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon & Strawberry smoothie', 'hm-sm2', NULL, 18000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-smooth'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-sm2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Banana & Strawberry smoothie', 'hm-sm-bs', NULL, 18000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-smooth'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-sm-bs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'KALE Green smoothie', 'hm-sm3', NULL, 18000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'hm-smooth'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'hm-sm3'
  )
LIMIT 1;

-- ----- CATEGORY SECONDARY SECTION MAPPINGS (shared drinks -> Wing Mania) -----
-- Map 'shared-soft-drinks' into WING MANIA
INSERT INTO category_secondary_sections (category_id, section_id, is_active)
SELECT c.id, @sid_wm, 1
FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-soft-drinks'
  AND @sid_wm IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM category_secondary_sections css
    WHERE css.category_id = c.id AND css.section_id = @sid_wm
  )
LIMIT 1;
-- Map 'shared-juices' into WING MANIA
INSERT INTO category_secondary_sections (category_id, section_id, is_active)
SELECT c.id, @sid_wm, 1
FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-juices'
  AND @sid_wm IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM category_secondary_sections css
    WHERE css.category_id = c.id AND css.section_id = @sid_wm
  )
LIMIT 1;
-- Map 'shared-milkshakes' into WING MANIA
INSERT INTO category_secondary_sections (category_id, section_id, is_active)
SELECT c.id, @sid_wm, 1
FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-milkshakes'
  AND @sid_wm IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM category_secondary_sections css
    WHERE css.category_id = c.id AND css.section_id = @sid_wm
  )
LIMIT 1;
-- Map 'shared-smoothies' into WING MANIA
INSERT INTO category_secondary_sections (category_id, section_id, is_active)
SELECT c.id, @sid_wm, 1
FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'shared-smoothies'
  AND @sid_wm IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM category_secondary_sections css
    WHERE css.category_id = c.id AND css.section_id = @sid_wm
  )
LIMIT 1;
