-- Seed: Swiss Flavour + Ted & Co — Swiss The Vistana
-- Regenerate: node database/_scrape_swiss.mjs && node database/build_swissvistana_seed.mjs
-- Restaurant: it.vistana@swissinternationalhotels.com | Slug: swiss-the-vistana
-- Source: https://swissflavour.our-menu.online/ (food-menu + drink-menu)
-- Run AFTER migration.sql. Safe to re-run (NOT EXISTS guards).

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

SET @rid = (
  SELECT id FROM restaurants
  WHERE slug = 'swiss-the-vistana'
     OR email = 'it.vistana@swissinternationalhotels.com'
     OR manager_email = 'it.vistana@swissinternationalhotels.com'
  LIMIT 1
);

-- ----- SECTION: Swiss Flavour (food-menu) -----
SET @sid_fm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'food-menu' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'Swiss Flavour', 'food-menu', 1, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_fm IS NULL;
SET @sid_fm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'food-menu' LIMIT 1);

-- ----- SECTION: Ted & Co (drink-menu) -----
SET @sid_dm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'drink-menu' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'Ted & Co', 'drink-menu', 2, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_dm IS NULL;
SET @sid_dm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'drink-menu' LIMIT 1);

-- ----- food-menu categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SWISS CAFÉ MENU', 'fm-swiss-caf-menu', 1, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SANDWICHES & MORE', 'fm-sandwiches-and-more', 2, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SMALL CHOPS', 'fm-small-chops', 3, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'DEEP FRIED SERVED WITH A DIP SAUCE OF YOUR CHOICE', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice', 4, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'PEPPERED PROTEINS', 'fm-peppered-proteins', 5, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'HOT STARTER ( NIGERIAN PEPPER SOUP )', 'fm-hot-starter-nigerian-pepper-soup', 6, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SALAD', 'fm-salad', 7, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'PASTA AND PIZZA', 'fm-pasta-and-pizza', 8, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'NIGERIAN DISHES', 'fm-nigerian-dishes', 9, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'ASIAN FUSION', 'fm-asian-fusion', 10, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-asian-fusion')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SPECIAL PROTEIN', 'fm-special-protein', 11, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'MAMA AFRICA ( NIGERIAN SOUPS )', 'fm-mama-africa-nigerian-soups', 12, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'ALL DAY LONG A CHOICE OF DESSERTS OF THE DAY HANDMADE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade', 13, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'STARTERS & DELIGHTS', 'fm-starters-and-delights', 14, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights')
LIMIT 1;

-- SWISS CAFÉ MENU
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SWISS CAFE EARLY BIRD', 'fm-swiss-caf-menu-swiss-cafe-early-bird', '2 FRIED EGGS, TOMATO, SAUSAGES, BAKED BEANS & TOASTS WITH COFFEE OR TEA', 12000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-swiss-cafe-early-bird'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SUNDAY BRUNCH', 'fm-swiss-caf-menu-sunday-brunch-2', NULL, 30000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-sunday-brunch-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BREAKFAST BUFFET', 'fm-swiss-caf-menu-breakfast-buffet-3', NULL, 12000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-breakfast-buffet-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DINNER BUFFET', 'fm-swiss-caf-menu-dinner-buffet-4', NULL, 25000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-dinner-buffet-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'KIDDIES', 'fm-swiss-caf-menu-kiddies-5', NULL, 6000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-kiddies-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ENGLISH BREAKFAST', 'fm-swiss-caf-menu-english-breakfast-6', '2 SCRAMBLE EGGS, TOMATO, SAUSAGES, BAKED BEANS & TOASTS WITH COFFEE OR TEA', 12000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-english-breakfast-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'AMERICAN BREAKFAST', 'fm-swiss-caf-menu-american-breakfast-7', '2 CROISSANTS, JAM, SAUSAGES, BAKED BEANS & WITH COFFEE OR TEA', 18000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-american-breakfast-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SWISS CAFE CONTINENTAL BREAKFAST', 'fm-swiss-caf-menu-swiss-cafe-continental-breakfast-8', 'FRESH JUICE, TOAST, OVEN FRESH BREADROLL, BUTTER & JAM, CEREALS, CHOICE OF COFFEE OR TEA', 13500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-swiss-cafe-continental-breakfast-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'NIGERIAN BREAKFAST', 'fm-swiss-caf-menu-nigerian-breakfast-9', 'TOAST, EGG SAUCE or KIDNEY SAUCE, PLANTAIN or YAM, COFFEE OR TEA', 12000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-nigerian-breakfast-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BIRCHER MUESLI', 'fm-swiss-caf-menu-bircher-muesli-10', 'THE CLASSICAL SWISS VITAMIN SHOT WITH OAK FLAKES, YOGHURT, AND SEASONAL FRUIT', 6500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-bircher-muesli-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRESH FRUIT SALAD', 'fm-swiss-caf-menu-fresh-fruit-salad-11', NULL, 6000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-fresh-fruit-salad-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'EGGS AND NOODLES', 'fm-swiss-caf-menu-eggs-and-noodles-12', 'DUO OF FRIED OR BOILED EGGS WITH NOODLES', 8500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-eggs-and-noodles-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TWO EGGS OMELETTE', 'fm-swiss-caf-menu-two-eggs-omelette-13', NULL, 4500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-two-eggs-omelette-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TWO BOILED EGGS', 'fm-swiss-caf-menu-two-boiled-eggs-14', NULL, 3500, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-two-boiled-eggs-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPANISH OMELETTE', 'fm-swiss-caf-menu-spanish-omelette-15', 'TWO EGGS OMELETTE WITH ONIONS, TOMATOES, AND GREEN PEPPER.', 4000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-spanish-omelette-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'OATMEAL', 'fm-swiss-caf-menu-oatmeal-16', NULL, 3500, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-oatmeal-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TOAST BREAD AND EGGS', 'fm-swiss-caf-menu-toast-bread-and-eggs-17', NULL, 6000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-toast-bread-and-eggs-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BOILD YAM AND EGG', 'fm-swiss-caf-menu-boild-yam-and-egg-18', NULL, 10000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-swiss-caf-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-swiss-caf-menu-boild-yam-and-egg-18'
  )
LIMIT 1;

-- SANDWICHES & MORE
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CLUB SANDWICH', 'fm-sandwiches-and-more-club-sandwich', 'WE FOLLOW THE ORIGINAL RECIPE FOR THIS FAMOUS SANDWICH MADE WITH THE BEST FRESH VEGETABLES, SHREDDED CHICKEN, MAYONNAISE, BOILED EGG, AND BACON ON THE REQUEST. SERVED WITH FRENCH FRIES.', 9000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-club-sandwich'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN CLUB SANDWICH', 'fm-sandwiches-and-more-chicken-club-sandwich-2', NULL, 10000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-chicken-club-sandwich-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PRAWNS CLUB SANDWICH', 'fm-sandwiches-and-more-prawns-club-sandwich-3', 'A CHANGE FROM THE ORIGINAL RECIPE MADE WITH CURRY-BOILED PRAWNS. SERVED WITH FRENCH FRIES', 20000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-prawns-club-sandwich-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CROQUE MONSIEUR', 'fm-sandwiches-and-more-croque-monsieur-4', 'FROM FRANCE THIS GOLDEN BROWN TOASTED HAM AND CHEESE SANDWICH IS SERVED WITH FRENCH FRIES AND COLESLAW.', 12000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-croque-monsieur-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'HAMBURGER', 'fm-sandwiches-and-more-hamburger-5', 'HAMBURGER, A 150 GR. GROUND MEAT PATTY, LETTUCE, CUCUMBER, GRILLED ONION, AND A SECRET HOMEMADE SAUCE BETWEEN TWO SLICES OF BREAD, WAS FIRST CREATED IN AMERICA IN 1900 BY LOUIS LASSEN, A DANISH IMMIGRANT AND WE SERVE IT FOLLOWING THE SAME RECIPE BUT WITH OUR SECRET HOMEMADE SAUCE. SERVED WITH FRENCH FRIES.', 12500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-hamburger-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BEEF BURGER', 'fm-sandwiches-and-more-beef-burger-6', NULL, 11000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-beef-burger-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHEESEBURGER', 'fm-sandwiches-and-more-cheeseburger-7', NULL, 12000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-cheeseburger-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN BURGER', 'fm-sandwiches-and-more-chicken-burger-8', NULL, 15000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-chicken-burger-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRESH SPRING ROLLS', 'fm-sandwiches-and-more-fresh-spring-rolls-9', '(4 PIECES) WITH DIPPING SAUCE', 6000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-fresh-spring-rolls-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRESH SAMOSA', 'fm-sandwiches-and-more-fresh-samosa-10', '(4 PIECES) WITH DIPPING SAUCE', 4500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-fresh-samosa-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN WRAP', 'fm-sandwiches-and-more-chicken-wrap-11', 'COMES WITH FRANCH FRIES', 11000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-chicken-wrap-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ITALIAN BRUSCHETTA', 'fm-sandwiches-and-more-italian-bruschetta-12', '2 SLICES OF TOASTED BREAD GENTLY BRUSHED WITH GARLIC AND TOPPED WITH OLIVE OIL, FRESH TOMATO, OREGANO', 12000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches-and-more'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-and-more-italian-bruschetta-12'
  )
LIMIT 1;

-- SMALL CHOPS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1 STANDARD MEAT PIE', 'fm-small-chops-1-standard-meat-pie', NULL, 3000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-1-standard-meat-pie'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1 STANDARD CHICKEN PIE', 'fm-small-chops-1-standard-chicken-pie-2', NULL, 3000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-1-standard-chicken-pie-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1 STANDARD FISH PIE', 'fm-small-chops-1-standard-fish-pie-3', NULL, 3000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-1-standard-fish-pie-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PORTION CAKE', 'fm-small-chops-portion-cake-4', NULL, 6000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-portion-cake-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DOUGHNUTS', 'fm-small-chops-doughnuts-5', NULL, 3000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-doughnuts-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '2 CHEESE ROLLS', 'fm-small-chops-2-cheese-rolls-6', NULL, 3000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-2-cheese-rolls-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1 PACK OF CHIN-CHIN', 'fm-small-chops-1-pack-of-chin-chin-7', NULL, 3000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-1-pack-of-chin-chin-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '2 DANISH', 'fm-small-chops-2-danish-8', NULL, 3500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-2-danish-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1 PACK OF POP CORN', 'fm-small-chops-1-pack-of-pop-corn-9', NULL, 2000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-1-pack-of-pop-corn-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CAKE', 'fm-small-chops-cake-10', NULL, 10000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-cake-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'EGG ROLL', 'fm-small-chops-egg-roll-11', NULL, 3000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-egg-roll-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SAUSAGE ROLL', 'fm-small-chops-sausage-roll-12', NULL, 2500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-sausage-roll-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPRING ROLL', 'fm-small-chops-spring-roll-13', NULL, 2000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-small-chops'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-small-chops-spring-roll-13'
  )
LIMIT 1;

-- DEEP FRIED SERVED WITH A DIP SAUCE OF YOUR CHOICE
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FISH FINGERS', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-fish-fingers', NULL, 6500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-fish-fingers'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ONION RINGS', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-onion-rings-2', NULL, 3000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-onion-rings-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRENCH FRIES', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-french-fries-3', NULL, 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-french-fries-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FINGER YAM', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-finger-yam-4', NULL, 5000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-finger-yam-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SWEET POTATO CHIPS', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-sweet-potato-chips-5', NULL, 5000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-sweet-potato-chips-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRIED PLANTAIN', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-fried-plantain-6', NULL, 6000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-fried-plantain-6'
  )
LIMIT 1;

-- PEPPERED PROTEINS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SNAILS', 'fm-peppered-proteins-snails', NULL, 28000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-snails'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BEEF', 'fm-peppered-proteins-beef-2', NULL, 13000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-beef-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PEPPERED GOAT MEAT', 'fm-peppered-proteins-peppered-goat-meat-3', NULL, 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-peppered-goat-meat-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN NKWOBI', 'fm-peppered-proteins-chicken-nkwobi-4', NULL, 15000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-chicken-nkwobi-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'GIZZARDS', 'fm-peppered-proteins-gizzards-5', NULL, 13500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-gizzards-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN', 'fm-peppered-proteins-chicken-6', NULL, 13000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-chicken-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'COW LEG', 'fm-peppered-proteins-cow-leg-7', NULL, 13000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-cow-leg-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CROAKER FISH PT', 'fm-peppered-proteins-croaker-fish-pt-8', NULL, 12000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-croaker-fish-pt-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PEPPER PRAWNS', 'fm-peppered-proteins-pepper-prawns-9', NULL, 25000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-pepper-prawns-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SHRIMPS', 'fm-peppered-proteins-shrimps-10', NULL, 6000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-shrimps-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ROCK FISH PORTION', 'fm-peppered-proteins-rock-fish-portion-11', NULL, 17000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-rock-fish-portion-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TURKEY', 'fm-peppered-proteins-turkey-12', NULL, 15000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-turkey-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TITUS FISH', 'fm-peppered-proteins-titus-fish-13', NULL, 15000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-titus-fish-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'GOAT MEAT', 'fm-peppered-proteins-goat-meat-14', NULL, 16000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-goat-meat-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN LAP', 'fm-peppered-proteins-chicken-lap-15', NULL, 13000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-chicken-lap-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ROCK FISH FULL', 'fm-peppered-proteins-rock-fish-full-16', NULL, 35000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-rock-fish-full-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'DRY FISH (DRIED FISH)', 'fm-peppered-proteins-dry-fish-dried-fish-17', NULL, 17000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-dry-fish-dried-fish-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CATFISH', 'fm-peppered-proteins-catfish-18', NULL, 15000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-catfish-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BBQ CHICKEN', 'fm-peppered-proteins-bbq-chicken-19', NULL, 13000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-bbq-chicken-19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN DRUM STICK', 'fm-peppered-proteins-chicken-drum-stick-20', NULL, 13000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-chicken-drum-stick-20'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PEPPERED CHICKEN WINGS', 'fm-peppered-proteins-peppered-chicken-wings-21', NULL, 13000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-peppered-proteins'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-peppered-proteins-peppered-chicken-wings-21'
  )
LIMIT 1;

-- HOT STARTER ( NIGERIAN PEPPER SOUP )
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ROCK FISH PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-rock-fish-pepper-soup', NULL, 17000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-rock-fish-pepper-soup'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ROCK FISH SOUP', 'fm-hot-starter-nigerian-pepper-soup-rock-fish-soup-2', NULL, 18000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-rock-fish-soup-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CATFISH PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-catfish-pepper-soup-3', NULL, 25000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-catfish-pepper-soup-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRENCH ONION SOUP', 'fm-hot-starter-nigerian-pepper-soup-french-onion-soup-4', 'TOPPED WITH GRATINATED TOAST', 5000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-french-onion-soup-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BEEF PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-beef-pepper-soup-5', NULL, 13000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-beef-pepper-soup-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CREAM OF MIXED VEGETABLES', 'fm-hot-starter-nigerian-pepper-soup-cream-of-mixed-vegetables-6', 'A MIX OF SEASONAL FRESH VEGETABLES BLENDED AND THICKENED WITH WHIPPED CREAM', 6000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-cream-of-mixed-vegetables-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CREAM OF SWEET CORN SOUP', 'fm-hot-starter-nigerian-pepper-soup-cream-of-sweet-corn-soup-7', 'WE BLEND THE SWEET CORN AND MIX IT WITH STOCK AND SPICES THICKENING IT WITH WHIPPED CREAM.', 13000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-cream-of-sweet-corn-soup-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CATFISH PEPPER SOUP PORTION', 'fm-hot-starter-nigerian-pepper-soup-catfish-pepper-soup-portion-8', NULL, 15000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-catfish-pepper-soup-portion-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'COW PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-cow-pepper-soup-9', NULL, 13000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-cow-pepper-soup-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BEEF SOUP', 'fm-hot-starter-nigerian-pepper-soup-beef-soup-10', NULL, 13000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-beef-soup-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'COW LEG SOUP', 'fm-hot-starter-nigerian-pepper-soup-cow-leg-soup-11', NULL, 17000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-cow-leg-soup-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'GOAT MEAT SOUP', 'fm-hot-starter-nigerian-pepper-soup-goat-meat-soup-12', NULL, 15000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-goat-meat-soup-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TURKEY PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-turkey-pepper-soup-13', NULL, 15000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-hot-starter-nigerian-pepper-soup'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-hot-starter-nigerian-pepper-soup-turkey-pepper-soup-13'
  )
LIMIT 1;

-- SALAD
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'STARTERS & DELIGHTS WARM SHREDDED STIR FRY CHICKEN SALAD', 'fm-salad-starters-and-delights-warm-shredded-stir-fry-chicken-salad', 'An original recipe from the mountains of Thailand composed of fresh lettuce, carrot, and spring onion topped with shredded fried chicken with cashew nuts and served with a dip of soy sauce and green curry.', 12000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-starters-and-delights-warm-shredded-stir-fry-chicken-salad'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CAESAR SALAD', 'fm-salad-caesar-salad-2', 'We respect the tradition and we serve you fresh lettuce leaves topped with flakes of Italian Parmesan cheese and golden croutons served with the original homemade Caesar sauce on the side.', 12000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-caesar-salad-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN CAESAR SALAD', 'fm-salad-chicken-caesar-salad-3', 'We respect the tradition and we serve you fresh lettuce leaves topped with flakes of Italian Parmesan cheese and golden croutons served with the original homemade Caesar sauce on the side.', 14000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-chicken-caesar-salad-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'MIXED VEGETABLE SALAD', 'fm-salad-mixed-vegetable-salad-4', 'A healthy choice of a mix of seasonal vegetables served with Italian dressing.', 8500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-mixed-vegetable-salad-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'COLE SLAW', 'fm-salad-cole-slaw-5', 'A healthy choice for a mix of seasonal vegetables served with simple dressing.', 5000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-cole-slaw-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRESH FRUIT SALAD', 'fm-salad-fresh-fruit-salad-6', 'A healthy choice for a mix of seasonal vegetables served with simple dressing.', 6000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-fresh-fruit-salad-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SWISS SALAD', 'fm-salad-swiss-salad-7', 'Created from the mind of our chef, an original recipe inspired by the land where our hotel was born. We mix for you fresh lettuce leaves with slices of green apple topped with small cubes of Gruyere cheese and served with French vinaigrette.', 15000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-swiss-salad-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'GREEK SALAD', 'fm-salad-greek-salad-8', 'In the nest of democracy, they created this mix of flavour that we need to give you. On a bed of fresh lettuce leaves topped with fresh slices of tomatoes, cucumber, and green pepper, we garnish with Kalamata black olives and the famous feta cheese, served with garlic dressing.', 15000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-greek-salad-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CONCORD SALAD', 'fm-salad-concord-salad-9', 'With a combination of green beans, peas, celery, green pepper, and onions on a bed of cabbage, we mix on top the chopped chicken with mayonnaise and garnish with boiled egg and croutons.', 8500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-concord-salad-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SHRIMPS COCKTAIL', 'fm-salad-shrimps-cocktail-10', 'With our local shrimps, we serve you one of the most famous starters on a bed of grated carrots with cucumber and served with cocktail sauce.', 17000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salad'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salad-shrimps-cocktail-10'
  )
LIMIT 1;

-- PASTA AND PIZZA
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPAGHETTI BOLOGNAISE', 'fm-pasta-and-pizza-spaghetti-bolognaise', 'THE CLASSICAL PASTA DISH WITH TOMATO AND GROUND BEEF, GRATINATED WITH PARMESAN CHEES AND SERVED WITH FRESH GREENS', 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-spaghetti-bolognaise'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPAGHETTI AND JUMBO PRAWNS', 'fm-pasta-and-pizza-spaghetti-and-jumbo-prawns-2', 'TO COOK THIS FAMOUS DISH OF THE ITALIAN TRADITION WE PAN FRY THE LOCAL PRAWNS IN OLIVE OIL WITH GARLIC, DRY PEPPER AND SPICES THICKENED WITH CREAM', 35000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-spaghetti-and-jumbo-prawns-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'MAC AND CHEESE', 'fm-pasta-and-pizza-mac-and-cheese-3', 'MACARONI, COOKING CREAM, BUTTER WITH CHEDDAR CHEESE', 18000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-mac-and-cheese-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPAGHETTI CABONARA', 'fm-pasta-and-pizza-spaghetti-cabonara-4', 'WE FOLLOW THE ORIGINAL RECIPE AND WE PAN-FRY BACON IN OLIVE OIL WITH GARLIC MAKING SAUCE WITH SCRAMBLED EGGS AND SPICES. TOPPED WITH PARMESAN CHEESE', 15000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-spaghetti-cabonara-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SEAFOOD SPAGHETTI', 'fm-pasta-and-pizza-seafood-spaghetti-5', 'A DISH OF THE NEAPOLITAN TRADITION COOKED WITH THE LOCAL SEAFOOD PAN-FRIED IN OLIVE OIL WITH SLICED TOMATOES, GARLIC, AND SPICES THICKENED WITH BLENDED SHRIMP SAUCE.', 28000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-seafood-spaghetti-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'JOLLOF SPAGHETTI', 'fm-pasta-and-pizza-jollof-spaghetti-6', 'A DISH OF THE NEAPOLITAN TRADITION COOKED IN TOMATO SAUCE.', 7000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-jollof-spaghetti-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'GARDEN VEGETABLE SPAGHETTI', 'fm-pasta-and-pizza-garden-vegetable-spaghetti-7', 'A BOUNTY FROM THE GARDEN-FRESH SEASONAL MIX OF VEGETABLES, TOMATOES, AND SPICES--MAKES A DELICIOUS PASTA TOPPING.', 12000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-garden-vegetable-spaghetti-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SINGAPORE NOODLES', 'fm-pasta-and-pizza-singapore-noodles-8', 'FROM THE BORDER OF CHINA, A TRADITIONAL DISH MADE WITH FRIED CURRY NOODLES SALTED WITH SLICED FRESH VEGETABLES, BABY SHRIMPS, AND SHREDDED BEEF ACCOMPANIED WITH CHILI SAUCE', 13000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-singapore-noodles-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PIZZA MARGHERITA', 'fm-pasta-and-pizza-pizza-margherita-9', 'WITH ALL THE FLAVOURS OF THE SPRINGTIME HILLS WITH TOMATO SAUCE, GREEN PEPPER, ONIONS, MUSHROOMS, BLACK OLIVES, FRESH TOMATO, SWEET CORN, MOZZARELLA CHEESE, AND OREGANO', 12000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-pizza-margherita-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN PIZZA', 'fm-pasta-and-pizza-chicken-pizza-10', NULL, 12000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-chicken-pizza-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SEAFOOD PIZZA', 'fm-pasta-and-pizza-seafood-pizza-11', 'WITH ALL FLAVOURS FROM THE SEA, WE SERVE YOU IT WITH TOMATO, MIXED SEAFOOD, MOZZARELLA CHEESE, AND BASIL LEAVES', 22000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-seafood-pizza-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PANZEROTTI', 'fm-pasta-and-pizza-panzerotti-12', '2 SHAPED SOUTH-ITALIAN FRIED PIZZA STUFFED WITH SPICY TOMATO SAUCE, MOZZARELLA CHEESE, AND BASIL LEAVES', 10000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-panzerotti-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SEAFOOD PLATTER', 'fm-pasta-and-pizza-seafood-platter-13', 'A COMBINATION OF SEA-FOOD WITH CHOICE OF FRIES, YAM/SWEET POTATOES', 80000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-seafood-platter-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPAGHETTI MEAT BALL', 'fm-pasta-and-pizza-spaghetti-meat-ball-14', 'A COMBO OF SEAFOODS WITH THE OPTION OF FRIES, YAM/SWEET POTATOES', 12000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-spaghetti-meat-ball-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BEEF BOURGUIGNON', 'fm-pasta-and-pizza-beef-bourguignon-15', 'A COMBO OF SEAFOODS WITH THE OPTION OF FRIES, YAM/SWEET POTATOES', 9000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-beef-bourguignon-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'VEGETABLE PASTA', 'fm-pasta-and-pizza-vegetable-pasta-16', NULL, 8000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-vegetable-pasta-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPAGHETTI ARIABIATA', 'fm-pasta-and-pizza-spaghetti-ariabiata-17', NULL, 15000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta-and-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-and-pizza-spaghetti-ariabiata-17'
  )
LIMIT 1;

-- NIGERIAN DISHES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'JOLLOF RICE', 'fm-nigerian-dishes-jollof-rice', 'A TRADITIONAL RECIPE THAT YOU CAN MEET IN EVERY EVENT COOKED BY OUR NATIONAL CHEF WITH FRESH TOMATO STEW, FRESH PEPPER, AND GARNISHED WITH PEAS', 8500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-jollof-rice'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'COCONUT RICE', 'fm-nigerian-dishes-coconut-rice-2', 'ALL THE FLAVOURS OF NIGERIA IN THIS ORIGINAL RECIPE ARE COOKED WITH COCONUT MILK, COCONUT POWDER, AND DRY PEPPER', 7000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-coconut-rice-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BASMATI FRIED RICE', 'fm-nigerian-dishes-basmati-fried-rice-3', 'BASMATI RICE IS SALTED IN SPICY VEGETABLE OIL WITH CHOPPED ONIONS, CARROTS, AND GREEN BEANS.', 7000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-basmati-fried-rice-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHINESE FRIED RICE', 'fm-nigerian-dishes-chinese-fried-rice-4', 'BASMATI RICE SALTED IN SPICY VEGETABLE OIL WITH CRUMBLED EGGS, GREEN BEANS, PEAS, SHRIMP, CARROTS, AND CHOPPED ONIONS', 10000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-chinese-fried-rice-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'JAMBALAYA RICE', 'fm-nigerian-dishes-jambalaya-rice-5', 'BASMATI RICE WITH GARLIC, CHICKEN BREAST, SAUSAGE AND PLANTAIN IN TOMATO SAUCE', 13000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-jambalaya-rice-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BASMATI RICE', 'fm-nigerian-dishes-basmati-rice-6', NULL, 5000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-basmati-rice-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'OFADA RICE AND SAUCE', 'fm-nigerian-dishes-ofada-rice-and-sauce-7', 'OFADA RICE, OFADA SAUCE AND FRIED PLANTAIN', 12000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-ofada-rice-and-sauce-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'WHITE RICE', 'fm-nigerian-dishes-white-rice-8', NULL, 3000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-white-rice-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PORTAGE BEANS', 'fm-nigerian-dishes-portage-beans-9', NULL, 8000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-portage-beans-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'EBA', 'fm-nigerian-dishes-eba-10', NULL, 2500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-eba-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PLANTAIN FLOUR', 'fm-nigerian-dishes-plantain-flour-11', NULL, 3000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-plantain-flour-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'WHEAT MEAL', 'fm-nigerian-dishes-wheat-meal-12', NULL, 2500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-wheat-meal-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SEMOVITA', 'fm-nigerian-dishes-semovita-13', NULL, 2500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-semovita-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'POUNDO YAM', 'fm-nigerian-dishes-poundo-yam-14', NULL, 3000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-poundo-yam-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'VEGETABLE FRIED RICE', 'fm-nigerian-dishes-vegetable-fried-rice-15', NULL, 8500, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-vegetable-fried-rice-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'WHITE BEANS', 'fm-nigerian-dishes-white-beans-16', NULL, 4000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-dishes-white-beans-16'
  )
LIMIT 1;

-- ASIAN FUSION
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN YAKITORI', 'fm-asian-fusion-chicken-yakitori', '3 STICKS OF CHICKEN, ONIONS, AND GREEN PEPPER DIPPED IN A HOMEMADE SAUCE OF SOY SAUCE, GINGER AND GARLIC PASTE, SESAME OIL, SUGAR, AND SPRING ONION SERVED WITH FRIES', 12000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-asian-fusion'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-asian-fusion-chicken-yakitori'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SWEET AND SOUR CHICKEN', 'fm-asian-fusion-sweet-and-sour-chicken-2', 'MARINATED DICED MEAT COOKED IN A MEDIUM CHILLI TOMATO SAUCE, VINEGAR, HONEY, AND LIME WITH SPRING ONION, GREEN PEPPER, AND PINEAPPLE SERVED WITH STEAM BASMATI RICE', 15000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-asian-fusion'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-asian-fusion-sweet-and-sour-chicken-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN CURRY', 'fm-asian-fusion-chicken-curry-3', 'ALL FLAVOURS OF THE INCREDIBLE INDIA FOR THIS BONELESS CHICKEN COOKED IN A MIX OF SPICES AND VEGETABLES THICKENED WITH CORNFLOWER AND SERVED WITH STEAMED BASMATI RICE OR HOMEMADE CHAPATI BREAD', 12000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-asian-fusion'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-asian-fusion-chicken-curry-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRIED RICE', 'fm-asian-fusion-fried-rice-4', 'NIGERIAN RICE IS SALTED IN SPICY VEGETABLE OIL WITH CHOPPED ONIONS, CARROTS, AND GREEN BEANS.', 5000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-asian-fusion'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-asian-fusion-fried-rice-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPECIAL FRIED RICE', 'fm-asian-fusion-special-fried-rice-5', NULL, 12000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-asian-fusion'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-asian-fusion-special-fried-rice-5'
  )
LIMIT 1;

-- SPECIAL PROTEIN
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SPECIAL OF THE DAY', 'fm-special-protein-special-of-the-day', 'SERVED WITH FRENCH FRIES AND FRESH SALADS', 12000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-special-of-the-day'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FILLET OF FISH', 'fm-special-protein-fillet-of-fish-2', 'PAN FRIED FILLET OF RED SNAPPER or SHINY NOSE FROM OUR SEA GENTLY MARINATED AND SERVED WITH A CHOICE OF SAUCE, FRESH VEGETABLES, RICE, OR FRENCH FRIES', 15000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-fillet-of-fish-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN BREAST', 'fm-special-protein-chicken-breast-3', NULL, 13000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-chicken-breast-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'GRILLED JUMBO PRAWNS', 'fm-special-protein-grilled-jumbo-prawns-4', '2 JUMBO PRAWNS FROM THE BAY OF GUINEA GENTLY MARINATED AND SERVED WITH A CHOICE OF SAUCE, SAUTÉED VEGETABLES, AND RICE OF YOUR CHOICE OR CHIPS', 30000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-grilled-jumbo-prawns-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BUTTERFLY JUMBO PRAWNS', 'fm-special-protein-butterfly-jumbo-prawns-5', '2 JUMBO PRAWNS MARINATED AND COATES IN YOKES AND BREAD CRUMB, DEEP-FRIED AND SERVED WITH FRENCH FRIES AND TARTAR SAUCE.', 30000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-butterfly-jumbo-prawns-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'VEGETABLES SAUTÉ', 'fm-special-protein-vegetables-saut-6', 'SELECTED FRESH GARDEN VEGGIES COOKED TO ORDER ON MEDIUM HEAT SERVED WITH GRILLED CHICKEN BREAST.', 8000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-vegetables-saut-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FILET STEAK', 'fm-special-protein-filet-steak-7', 'MEDAILLONS OF QUALITY BEEF GRILLED RARE, MEDIUM, OR WELL-DONE, SERVED WITH CREAM OF YOUR CHOICE, FRENCH FRIES, VEGETABLE SALAD/ SEASONAL VEGETABLES', 15000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-filet-steak-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BEEF STROGANOFF', 'fm-special-protein-beef-stroganoff-8', 'TENDER STRIPS OF BEEF IN A MUSHROOM CREAM SAUCE SERVED WITH PASTA OR RICE OF YOUR CHOICE', 12000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-beef-stroganoff-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'T-BONE STEAK', 'fm-special-protein-t-bone-steak-9', 'A MARINATED LOCAL STEAK GRILLED AND COOKED TO YOUR CHOICE SERVED WITH FRENCH FRIES.', 45000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-t-bone-steak-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN WINGS', 'fm-special-protein-chicken-wings-10', '5 PIECES OF HOT SAUCE MARINATED WINGS DEEP FRIED AND SERVED WITH NIGERIAN PEPPER SAUCE', 13000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-chicken-wings-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN STIR FRY', 'fm-special-protein-chicken-stir-fry-11', NULL, 12000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-special-protein'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-special-protein-chicken-stir-fry-11'
  )
LIMIT 1;

-- MAMA AFRICA ( NIGERIAN SOUPS )
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'EXTRA BALL PONDO', 'fm-mama-africa-nigerian-soups-extra-ball-pondo', NULL, 4000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-extra-ball-pondo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'ISI EWU', 'fm-mama-africa-nigerian-soups-isi-ewu-2', 'THE FAMOUS RECIPE FROM THE NIGERIAN TRADITION WITH A CHOPPED GOAT HEAD DRESSED WITH PEPPERED AND SPICY GRAVY AND SERVED ON A WOOD PLATE.', 25000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-isi-ewu-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'NKWOBI', 'fm-mama-africa-nigerian-soups-nkwobi-3', 'THE FAMOUS RECIPE FROM THE NIGERIAN TRADITION WITH A CHOPPED COW LEG DRESSED WITH PEPPERED AND SPICY GRAVY AND SERVED ON A WOOD PLATE.', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-nkwobi-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FISHERMAN SOUP', 'fm-mama-africa-nigerian-soups-fisherman-soup-4', NULL, 30000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-fisherman-soup-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SEAFOOD OKRO', 'fm-mama-africa-nigerian-soups-seafood-okro-5', 'lorem insput dolor sit amet adipicicing alit,sed do elusmod tempor incididunt ut labor at dolore magna aliqua.Ut enim ad minim veniam,ques nostrud.', 30000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-seafood-okro-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'VEGETABLE SOUP', 'fm-mama-africa-nigerian-soups-vegetable-soup-6', NULL, 10000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-vegetable-soup-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'EGUSI SOUP', 'fm-mama-africa-nigerian-soups-egusi-soup-7', NULL, 10000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-egusi-soup-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'AFANG SOUP', 'fm-mama-africa-nigerian-soups-afang-soup-8', NULL, 13000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-afang-soup-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN NKWOBI', 'fm-mama-africa-nigerian-soups-chicken-nkwobi-9', NULL, 15000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mama-africa-nigerian-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mama-africa-nigerian-soups-chicken-nkwobi-9'
  )
LIMIT 1;

-- ALL DAY LONG A CHOICE OF DESSERTS OF THE DAY HANDMADE
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FOR OUR PASTRY CHEFS', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-for-our-pastry-chefs', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-for-our-pastry-chefs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'SLICED FRUIT PLATTER', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-sliced-fruit-platter-2', 'SEASONAL FRESH FRUIT FROM THE LOCAL FRUIT GARDEN MARKET', 6000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-sliced-fruit-platter-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'MINI CAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-mini-cake-3', 'CHOICE OF CAKE', 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-mini-cake-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'PORTIONCAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-portioncake-4', 'CHOICE OF CAKE', 6000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-portioncake-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'RED VELVET CAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-red-velvet-cake-5', 'CHOICE OF CAKE', 3500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-red-velvet-cake-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'WHOLE RED VELVET CAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-whole-red-velvet-cake-6', 'CHOICE OF CAKE', 15000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-whole-red-velvet-cake-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'FRUIT SALAD', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-fruit-salad-7', 'DICED SEASONAL FRUIT FROM THE LOCAL FRUIT GARDEN MARKET DIPPED IN ORANGE JUICE AND SUGAR.', 6000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-fruit-salad-7'
  )
LIMIT 1;

-- STARTERS & DELIGHTS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN & CHIPS', 'fm-starters-and-delights-chicken-and-chips', NULL, 17000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-starters-and-delights-chicken-and-chips'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BUFFALO WINGS', 'fm-starters-and-delights-buffalo-wings-2', NULL, 10000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-starters-and-delights-buffalo-wings-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CAJUN SHRIMP TACOS', 'fm-starters-and-delights-cajun-shrimp-tacos-3', NULL, 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-starters-and-delights-cajun-shrimp-tacos-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'CHICKEN PIZZA', 'fm-starters-and-delights-chicken-pizza-4', NULL, 12000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-starters-and-delights-chicken-pizza-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'MARGARITA', 'fm-starters-and-delights-margarita-5', NULL, 10000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-starters-and-delights-margarita-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'GRILLED WHOLE CAT FISH', 'fm-starters-and-delights-grilled-whole-cat-fish-6', NULL, 28000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-starters-and-delights-grilled-whole-cat-fish-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'BEEF STIR FRY', 'fm-starters-and-delights-beef-stir-fry-7', NULL, 15000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-starters-and-delights'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-starters-and-delights-beef-stir-fry-7'
  )
LIMIT 1;

-- ----- drink-menu categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'SOFT DRINKS', 'dm-soft-drinks', 1, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'SIGNATURE COCKTAILS', 'dm-signature-cocktails', 2, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'MOCKTAILS', 'dm-mocktails', 3, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'BEER & MIXES', 'dm-beer-and-mixes', 4, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'WINE SELECTION', 'dm-wine-selection', 5, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'SWEET & SPARKLING', 'dm-sweet-and-sparkling', 6, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'WHISKEY', 'dm-whiskey', 7, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'COGNAC & BRANDY', 'dm-cognac-and-brandy', 8, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'VODKA', 'dm-vodka', 9, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'GIN', 'dm-gin', 10, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'TEQUILA & SHOOTERS', 'dm-tequila-and-shooters', 11, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila-and-shooters')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'RUM', 'dm-rum', 12, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'VERMOUTH & LIQUEURS', 'dm-vermouth-and-liqueurs', 13, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'SMOOTHIES & FRESH JUICES', 'dm-smoothies-and-fresh-juices', 14, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices')
LIMIT 1;

-- SOFT DRINKS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Medium Water (75cl)', 'dm-soft-drinks-medium-water-75cl', NULL, 1500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-medium-water-75cl'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Assorted Soft Drinks', 'dm-soft-drinks-assorted-soft-drinks-2', NULL, 2500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-assorted-soft-drinks-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Maltina', 'dm-soft-drinks-maltina-3', NULL, 3000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-maltina-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amstel - Guinness', 'dm-soft-drinks-amstel-guinness-4', NULL, 3000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-amstel-guinness-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Power Horse', 'dm-soft-drinks-power-horse-5', NULL, 6000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-power-horse-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Red Bull', 'dm-soft-drinks-red-bull-6', NULL, 6000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-red-bull-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bullet', 'dm-soft-drinks-bullet-7', NULL, 6000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-bullet-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Eviron', 'dm-soft-drinks-eviron-8', NULL, 6000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-eviron-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Monster', 'dm-soft-drinks-monster-9', NULL, 7000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-monster-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Daravit Health Drink', 'dm-soft-drinks-daravit-health-drink-10', NULL, 7000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-daravit-health-drink-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivita', 'dm-soft-drinks-chivita-11', NULL, 8500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-chivita-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fresh Juice', 'dm-soft-drinks-fresh-juice-12', NULL, 7, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-fresh-juice-12'
  )
LIMIT 1;

-- SIGNATURE COCKTAILS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'TED & CO Mojito', 'dm-signature-cocktails-ted-and-co-mojito', 'Rum, Mint, Lime, Sugar & Soda', 10000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-ted-and-co-mojito'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blue Margarita', 'dm-signature-cocktails-blue-margarita-2', 'Blue Curacao, Triple Sec, Tequila & Lemon Juice', 8000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-blue-margarita-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cosmopolitan', 'dm-signature-cocktails-cosmopolitan-3', 'Vodka, Cointreau, Cranberry & Lime Juice', 8000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-cosmopolitan-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Long Island', 'dm-signature-cocktails-long-island-4', 'Rum, Vodka, Gin, Tequila, Triple Sex, Coke', 12000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-long-island-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Manhattan', 'dm-signature-cocktails-manhattan-5', 'Rum, Vodka, Gin, Tequila, Cointreau, Lime & Coke Ice, Jack Daniel, Vermouth (Martini dry) Angostura bitters and Cherry', 10000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-manhattan-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bloody Mary', 'dm-signature-cocktails-bloody-mary-6', 'Salt, Black pepper, Vodka, Tomato juice, Lemon juice, Worcestershire, Hot Chilli', 10000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-bloody-mary-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sex on the Beach', 'dm-signature-cocktails-sex-on-the-beach-7', 'Peach liqueur, pineapple Juice, Vodka, Cranberry Juice', 10000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-sex-on-the-beach-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila Sunrise', 'dm-signature-cocktails-tequila-sunrise-8', 'Tequila,Orange Juice, Grenadine orange wedge', 8000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-tequila-sunrise-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Screwdriver', 'dm-signature-cocktails-screwdriver-9', 'Vodka, Orange juice', 10000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-screwdriver-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Melon Berry', 'dm-signature-cocktails-melon-berry-10', 'Melon Liqueur,Vodka, Orange juice', 10000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-melon-berry-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pinacolada', 'dm-signature-cocktails-pinacolada-11', 'Coconut rum, Coconut cream, Pineaple Juice..', 10000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-pinacolada-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mojito', 'dm-signature-cocktails-mojito-12', 'White Rum, Mint Leaf (5or6), Lime Juice, Soda Water, Garnished with lime wedge.', 10000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-mojito-12'
  )
LIMIT 1;

-- MOCKTAILS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Virgin Mojito', 'dm-mocktails-virgin-mojito', NULL, 6000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-virgin-mojito'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Virgin Colada', 'dm-mocktails-virgin-colada-2', NULL, 7000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-virgin-colada-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Melon Berry', 'dm-mocktails-melon-berry-3', NULL, 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-melon-berry-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chapman', 'dm-mocktails-chapman-4', NULL, 6000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-chapman-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rock Shandy', 'dm-mocktails-rock-shandy-5', NULL, 5000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-rock-shandy-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jamaica', 'dm-mocktails-jamaica-6', NULL, 4000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-jamaica-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sun-Shine Delight', 'dm-mocktails-sun-shine-delight-7', NULL, 4000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-sun-shine-delight-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Milkshake', 'dm-mocktails-milkshake-8', NULL, 12000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-milkshake-8'
  )
LIMIT 1;

-- BEER & MIXES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Heineken 600ml', 'dm-beer-and-mixes-heineken-600ml', NULL, 4000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-heineken-600ml'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Budweiser 600ml', 'dm-beer-and-mixes-budweiser-600ml-2', NULL, 3500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-budweiser-600ml-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Guiness Stout Big 600ml', 'dm-beer-and-mixes-guiness-stout-big-600ml-3', NULL, 4500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-guiness-stout-big-600ml-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Star Radler 600ml', 'dm-beer-and-mixes-star-radler-600ml-4', NULL, 3000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-star-radler-600ml-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gulder 600ml', 'dm-beer-and-mixes-gulder-600ml-5', NULL, 3500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-gulder-600ml-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Goldberg 600ml', 'dm-beer-and-mixes-goldberg-600ml-6', NULL, 3500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-goldberg-600ml-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Desperados 600ml', 'dm-beer-and-mixes-desperados-600ml-7', NULL, 3500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-desperados-600ml-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orijin 600ml', 'dm-beer-and-mixes-orijin-600ml-8', NULL, 3500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-orijin-600ml-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Trophy 600ml', 'dm-beer-and-mixes-trophy-600ml-9', NULL, 3500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-trophy-600ml-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Trophy Stout 600ml', 'dm-beer-and-mixes-trophy-stout-600ml-10', NULL, 3000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-trophy-stout-600ml-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '33 Export 600ml', 'dm-beer-and-mixes-33-export-600ml-11', NULL, 3500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-33-export-600ml-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hero 600ml', 'dm-beer-and-mixes-hero-600ml-12', NULL, 3500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-hero-600ml-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smirnoff Ice 250ml', 'dm-beer-and-mixes-smirnoff-ice-250ml-13', NULL, 3500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-smirnoff-ice-250ml-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Snap 250ml', 'dm-beer-and-mixes-snap-250ml-14', NULL, 2500, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-snap-250ml-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Star 250ml', 'dm-beer-and-mixes-star-250ml-15', NULL, 3500, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-star-250ml-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Small Stout 325ml', 'dm-beer-and-mixes-small-stout-325ml-16', NULL, 3500, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-small-stout-325ml-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Medium Stout', 'dm-beer-and-mixes-medium-stout-17', NULL, 4000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-medium-stout-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Legend Beer 600ml', 'dm-beer-and-mixes-legend-beer-600ml-18', NULL, 4000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-legend-beer-600ml-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smirnoff Ice Big 600ml', 'dm-beer-and-mixes-smirnoff-ice-big-600ml-19', NULL, 4500, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-smirnoff-ice-big-600ml-19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hunter 325ml', 'dm-beer-and-mixes-hunter-325ml-20', NULL, 3500, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-hunter-325ml-20'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Black bullet 325ml', 'dm-beer-and-mixes-black-bullet-325ml-21', NULL, 6000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer-and-mixes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-and-mixes-black-bullet-325ml-21'
  )
LIMIT 1;

-- WINE SELECTION
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nederburg Chardonnay', 'dm-wine-selection-nederburg-chardonnay', NULL, 70000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-nederburg-chardonnay'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Patagonia', 'dm-wine-selection-patagonia-2', NULL, 47000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-patagonia-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Massino', 'dm-wine-selection-massino-3', NULL, 40000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-massino-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carlo Rossi', 'dm-wine-selection-carlo-rossi-4', NULL, 50000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-carlo-rossi-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mouton Cadet Bordeaux', 'dm-wine-selection-mouton-cadet-bordeaux-5', NULL, 80000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-mouton-cadet-bordeaux-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Drostdy-Hof Claret Select', 'dm-wine-selection-drostdy-hof-claret-select-6', NULL, 40000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-drostdy-hof-claret-select-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Escudo Rojo', 'dm-wine-selection-escudo-rojo-7', NULL, 80000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-escudo-rojo-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chateauneuf-de-Pape', 'dm-wine-selection-chateauneuf-de-pape-8', NULL, 60000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-chateauneuf-de-pape-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Two Oceans', 'dm-wine-selection-two-oceans-9', NULL, 47000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-two-oceans-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mouton Cadet Sauvignon Blanc', 'dm-wine-selection-mouton-cadet-sauvignon-blanc-10', NULL, 90000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-mouton-cadet-sauvignon-blanc-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Four Cousins Red', 'dm-wine-selection-four-cousins-red-11', NULL, 48000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-four-cousins-red-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Four Cousins White', 'dm-wine-selection-four-cousins-white-12', NULL, 48000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-four-cousins-white-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Andre Rose', 'dm-wine-selection-andre-rose-13', NULL, 55000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-andre-rose-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Andre Brut', 'dm-wine-selection-andre-brut-14', NULL, 50000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-andre-brut-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Frontera', 'dm-wine-selection-frontera-15', NULL, 50000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-frontera-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Declan', 'dm-wine-selection-declan-16', NULL, 48000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-declan-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hardys Cabernet Sauvignon', 'dm-wine-selection-hardys-cabernet-sauvignon-17', NULL, 30000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-hardys-cabernet-sauvignon-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Podere', 'dm-wine-selection-podere-18', NULL, 25000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-podere-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '4th Street Red', 'dm-wine-selection-4th-street-red-19', NULL, 40000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-4th-street-red-19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Castleflorit', 'dm-wine-selection-castleflorit-20', NULL, 30000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-castleflorit-20'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mini Drosdty Hof', 'dm-wine-selection-mini-drosdty-hof-21', NULL, 5000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-mini-drosdty-hof-21'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fox Brook Merlot', 'dm-wine-selection-fox-brook-merlot-22', NULL, 35000, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-fox-brook-merlot-22'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'RiUnite Lamrusco', 'dm-wine-selection-riunite-lamrusco-23', NULL, 38000, 23, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-riunite-lamrusco-23'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fox Brook Carbonet Sarvinon', 'dm-wine-selection-fox-brook-carbonet-sarvinon-24', NULL, 35000, 24, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-fox-brook-carbonet-sarvinon-24'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moscato', 'dm-wine-selection-moscato-25', NULL, 20000, 25, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-moscato-25'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sweet Red', 'dm-wine-selection-sweet-red-26', NULL, 20000, 26, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-sweet-red-26'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nederburg Cabernet', 'dm-wine-selection-nederburg-cabernet-27', NULL, 70000, 27, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-nederburg-cabernet-27'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nederburg Sauvignon', 'dm-wine-selection-nederburg-sauvignon-28', NULL, 70000, 28, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-nederburg-sauvignon-28'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glass Of Four Cousins', 'dm-wine-selection-glass-of-four-cousins-29', NULL, 10000, 29, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-glass-of-four-cousins-29'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Castillo Grande Red', 'dm-wine-selection-castillo-grande-red-30', NULL, 15000, 30, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-castillo-grande-red-30'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Castillo Grande White', 'dm-wine-selection-castillo-grande-white-31', NULL, 15000, 31, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-castillo-grande-white-31'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Castillo Grande Rose', 'dm-wine-selection-castillo-grande-rose-32', NULL, 15000, 32, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-castillo-grande-rose-32'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Santa Alba Sweet', 'dm-wine-selection-santa-alba-sweet-33', NULL, 28000, 33, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-santa-alba-sweet-33'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cavalo Branco', 'dm-wine-selection-cavalo-branco-34', NULL, 30000, 34, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-cavalo-branco-34'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Santa Sauvignon', 'dm-wine-selection-santa-sauvignon-35', NULL, 30000, 35, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-santa-sauvignon-35'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Santa-Alba', 'dm-wine-selection-santa-alba-36', NULL, 28000, 36, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-santa-alba-36'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '4th Street White', 'dm-wine-selection-4th-street-white-37', NULL, 40000, 37, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-4th-street-white-37'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '13 Secret', 'dm-wine-selection-13-secret-38', NULL, 48000, 38, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-13-secret-38'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sandana', 'dm-wine-selection-sandana-39', NULL, 25000, 39, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-sandana-39'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Majesty', 'dm-wine-selection-majesty-40', NULL, 30000, 40, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-majesty-40'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beacon Hill', 'dm-wine-selection-beacon-hill-41', NULL, 30000, 41, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-beacon-hill-41'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Feliza', 'dm-wine-selection-feliza-42', NULL, 30000, 42, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-feliza-42'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Stones', 'dm-wine-selection-stones-43', NULL, 30000, 43, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-stones-43'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rubis', 'dm-wine-selection-rubis-44', NULL, 60000, 44, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine-selection'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-selection-rubis-44'
  )
LIMIT 1;

-- SWEET & SPARKLING
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gold Label', 'dm-sweet-and-sparkling-gold-label', 'Shots ₦4,500', 126000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-gold-label'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Green Label', 'dm-sweet-and-sparkling-green-label-2', 'Shots ₦4,000', 90000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-green-label-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blue Label', 'dm-sweet-and-sparkling-blue-label-3', NULL, 90000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-blue-label-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Black Label', 'dm-sweet-and-sparkling-black-label-4', 'Shots ₦5,000', 120000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-black-label-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Black Barrel', 'dm-sweet-and-sparkling-black-barrel-5', 'Shots ₦4,000', 140000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-black-barrel-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Observatory', 'dm-sweet-and-sparkling-observatory-6', NULL, 180000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-observatory-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Red Label', 'dm-sweet-and-sparkling-red-label-7', 'Shots ₦3,000', 80000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-red-label-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jack Daniels', 'dm-sweet-and-sparkling-jack-daniels-8', 'Shots ₦4,000', 110000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-jack-daniels-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gentleman Jack', 'dm-sweet-and-sparkling-gentleman-jack-9', 'Shots ₦2,500', 157500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-gentleman-jack-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivas Regal 12 Years', 'dm-sweet-and-sparkling-chivas-regal-12-years-10', 'Shots ₦2,500', 40000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-chivas-regal-12-years-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivas Regal 18 Years', 'dm-sweet-and-sparkling-chivas-regal-18-years-11', NULL, 80000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-chivas-regal-18-years-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'White Horse', 'dm-sweet-and-sparkling-white-horse-12', 'Shots ₦1,500', 15000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-white-horse-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grants 12 Years', 'dm-sweet-and-sparkling-grants-12-years-13', 'Shots ₦2,500', 12000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-grants-12-years-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 12 Years', 'dm-sweet-and-sparkling-glenfiddich-12-years-14', 'Shots ₦6,000', 130000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-glenfiddich-12-years-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 18 Years', 'dm-sweet-and-sparkling-glenfiddich-18-years-15', NULL, 400000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-glenfiddich-18-years-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 15 years', 'dm-sweet-and-sparkling-glenfiddich-15-years-16', NULL, 300000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-glenfiddich-15-years-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jameson Black', 'dm-sweet-and-sparkling-jameson-black-17', NULL, 100000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-jameson-black-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jameson Irish', 'dm-sweet-and-sparkling-jameson-irish-18', 'Shots ₦3,500', 85000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-jameson-irish-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 21Years', 'dm-sweet-and-sparkling-glenfiddich-21years-19', NULL, 500000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-glenfiddich-21years-19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Olmeca Tequila', 'dm-sweet-and-sparkling-olmeca-tequila-20', 'Shots ₦4,000', 80000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-olmeca-tequila-20'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Camino Tequila', 'dm-sweet-and-sparkling-camino-tequila-21', 'Shots ₦3,000', 75000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-camino-tequila-21'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sierra Tequila', 'dm-sweet-and-sparkling-sierra-tequila-22', 'Shots ₦3,000', 75000, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-sweet-and-sparkling'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-sweet-and-sparkling-sierra-tequila-22'
  )
LIMIT 1;

-- WHISKEY
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Agor Sweet Red Wine', 'dm-whiskey-agor-sweet-red-wine', NULL, 48000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-agor-sweet-red-wine'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rialto (Espana)', 'dm-whiskey-rialto-espana-2', NULL, 8500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-rialto-espana-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Motivo', 'dm-whiskey-motivo-3', NULL, 10000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-motivo-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martini', 'dm-whiskey-martini-4', NULL, 30000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-martini-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Andre Brut', 'dm-whiskey-andre-brut-5', NULL, 50000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-andre-brut-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet & Chandon Imperial Brut', 'dm-whiskey-moet-and-chandon-imperial-brut-6', NULL, 350000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-moet-and-chandon-imperial-brut-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet & Chandon Imperia Rose', 'dm-whiskey-moet-and-chandon-imperia-rose-7', NULL, 380000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-moet-and-chandon-imperia-rose-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Romeo Brut', 'dm-whiskey-romeo-brut-8', NULL, 200000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-romeo-brut-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Du Vernay (Ice)', 'dm-whiskey-veuve-du-vernay-ice-9', NULL, 25000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-veuve-du-vernay-ice-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Du Vernay (Rose)', 'dm-whiskey-veuve-du-vernay-rose-10', NULL, 40000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-veuve-du-vernay-rose-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Clicquot (Rose)', 'dm-whiskey-veuve-clicquot-rose-11', NULL, 380000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-veuve-clicquot-rose-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Clicquot (Brut)', 'dm-whiskey-veuve-clicquot-brut-12', NULL, 350000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-veuve-clicquot-brut-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Perignon', 'dm-whiskey-don-perignon-13', NULL, 1500000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-don-perignon-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crystal', 'dm-whiskey-crystal-14', NULL, 400000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-crystal-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Monkey shoulder', 'dm-whiskey-monkey-shoulder-15', NULL, 90000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-monkey-shoulder-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mateus Rose', 'dm-whiskey-mateus-rose-16', NULL, 45000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-mateus-rose-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mateus Brut', 'dm-whiskey-mateus-brut-17', NULL, 45000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-mateus-brut-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'B and G Cuvee Speciale', 'dm-whiskey-b-and-g-cuvee-speciale-18', NULL, 35000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-b-and-g-cuvee-speciale-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Domino', 'dm-whiskey-domino-19', NULL, 32000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-domino-19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Feliza', 'dm-whiskey-feliza-20', NULL, 30000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-feliza-20'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Castillo Grande', 'dm-whiskey-castillo-grande-21', NULL, 30000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-castillo-grande-21'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Robertson Winery', 'dm-whiskey-robertson-winery-22', NULL, 32000, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-robertson-winery-22'
  )
LIMIT 1;

-- COGNAC & BRANDY
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VSOP', 'dm-cognac-and-brandy-hennessy-vsop', 'Shots ₦8,500', 240000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-hennessy-vsop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy XO', 'dm-cognac-and-brandy-hennessy-xo-2', NULL, 0, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-hennessy-xo-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VS Big', 'dm-cognac-and-brandy-hennessy-vs-big-3', 'Shots ₦7,000', 170000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-hennessy-vs-big-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VS Small', 'dm-cognac-and-brandy-hennessy-vs-small-4', NULL, 100000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-hennessy-vs-small-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin VSOP', 'dm-cognac-and-brandy-remy-martin-vsop-5', 'Shots ₦10,500', 240000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-remy-martin-vsop-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin XO', 'dm-cognac-and-brandy-remy-martin-xo-6', NULL, 0, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-remy-martin-xo-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martell VSOP', 'dm-cognac-and-brandy-martell-vsop-7', 'Shots ₦4,000', 120000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-martell-vsop-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martel Blue Swift', 'dm-cognac-and-brandy-martel-blue-swift-8', NULL, 240000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-martel-blue-swift-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martel Vs', 'dm-cognac-and-brandy-martel-vs-9', 'Shots ₦4,000', 120000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-martel-vs-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orijin Bitters', 'dm-cognac-and-brandy-orijin-bitters-10', NULL, 3500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-orijin-bitters-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Odogwu', 'dm-cognac-and-brandy-odogwu-11', NULL, 3500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-odogwu-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martinellis', 'dm-cognac-and-brandy-martinellis-12', NULL, 18000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac-and-brandy'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-and-brandy-martinellis-12'
  )
LIMIT 1;

-- VODKA
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Absolut Blue', 'dm-vodka-absolut-blue', 'Shots ₦2,000', 40000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-absolut-blue'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Absolute Mandarin', 'dm-vodka-absolute-mandarin-2', 'Shots ₦1,500', 40000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-absolute-mandarin-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smirn-Off', 'dm-vodka-smirn-off-3', 'Shots ₦1,500', 50000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-smirn-off-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ciroc Flavoured', 'dm-vodka-ciroc-flavoured-4', 'Shots ₦5,500', 160000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-ciroc-flavoured-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vodka Extract', 'dm-vodka-vodka-extract-5', 'Shots ₦2,500', 25000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-vodka-extract-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Flirt Vodka', 'dm-vodka-flirt-vodka-6', NULL, 35000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-flirt-vodka-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sky Vodka', 'dm-vodka-sky-vodka-7', NULL, 48000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-sky-vodka-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Absolute Vodka', 'dm-vodka-absolute-vodka-8', 'Shots ₦3,000', 40000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-absolute-vodka-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Olmeca (Hot Chocolate)', 'dm-vodka-olmeca-hot-chocolate-9', NULL, 75000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-olmeca-hot-chocolate-9'
  )
LIMIT 1;

-- GIN
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gordon', 'dm-gin-gordon', 'Shots ₦2,500', 35000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-gordon'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bombay Sapphire', 'dm-gin-bombay-sapphire-2', 'Shots ₦4,000', 97125, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-bombay-sapphire-2'
  )
LIMIT 1;

-- TEQUILA & SHOOTERS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bacardi Gold', 'dm-tequila-and-shooters-bacardi-gold', 'Shots ₦2,500', 55000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila-and-shooters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-and-shooters-bacardi-gold'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bacardi Superior', 'dm-tequila-and-shooters-bacardi-superior-2', 'Shots ₦2,500', 40000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila-and-shooters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-and-shooters-bacardi-superior-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'St James', 'dm-tequila-and-shooters-st-james-3', 'Shots ₦1,500', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila-and-shooters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-and-shooters-st-james-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Elliot', 'dm-tequila-and-shooters-elliot-4', 'Shots ₦1,500', 35000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila-and-shooters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-and-shooters-elliot-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Captain Morgan', 'dm-tequila-and-shooters-captain-morgan-5', 'Shots ₦2,000', 60000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila-and-shooters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-and-shooters-captain-morgan-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Captain Morgan', 'dm-tequila-and-shooters-captain-morgan-6', NULL, 40000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila-and-shooters'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-and-shooters-captain-morgan-6'
  )
LIMIT 1;

-- RUM
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila Gold Camino', 'dm-rum-tequila-gold-camino', 'Shots ₦2,500', 75000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-tequila-gold-camino'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila Blanco White', 'dm-rum-tequila-blanco-white-2', 'Shots ₦4,000', 80000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-tequila-blanco-white-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Siera Tequila', 'dm-rum-siera-tequila-3', 'Shots ₦2,500', 75000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-siera-tequila-3'
  )
LIMIT 1;

-- VERMOUTH & LIQUEURS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Campari', 'dm-vermouth-and-liqueurs-campari', 'Shots ₦1,500', 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-campari'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martini Rosso', 'dm-vermouth-and-liqueurs-martini-rosso-2', 'Shots ₦3,000', 53000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-martini-rosso-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martini Bianco', 'dm-vermouth-and-liqueurs-martini-bianco-3', 'Shots ₦7,000', 47000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-martini-bianco-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pastis', 'dm-vermouth-and-liqueurs-pastis-4', 'Shots ₦1,500', 42000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-pastis-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ricard', 'dm-vermouth-and-liqueurs-ricard-5', 'Shots ₦1,500', 12000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-ricard-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Baileys', 'dm-vermouth-and-liqueurs-baileys-6', 'Shots ₦3,000', 65000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-baileys-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cointreau', 'dm-vermouth-and-liqueurs-cointreau-7', 'Shots ₦2,000', 65000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-cointreau-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amarula', 'dm-vermouth-and-liqueurs-amarula-8', 'Shots ₦1,500', 15000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-amarula-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tia Maria', 'dm-vermouth-and-liqueurs-tia-maria-9', 'Shots ₦1,500', 11500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-tia-maria-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Kahlua', 'dm-vermouth-and-liqueurs-kahlua-10', 'Shots ₦1,500', 10500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-kahlua-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Drambuie', 'dm-vermouth-and-liqueurs-drambuie-11', 'Shots ₦3,000', 20500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-drambuie-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Drambuie', 'dm-vermouth-and-liqueurs-drambuie-12', 'Amaretto', 30000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-drambuie-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Southern Comfort', 'dm-vermouth-and-liqueurs-southern-comfort-13', 'Amaretto', 106000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-southern-comfort-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mini Campari', 'dm-vermouth-and-liqueurs-mini-campari-14', 'Amaretto', 15000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-mini-campari-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crema Turron', 'dm-vermouth-and-liqueurs-crema-turron-15', NULL, 49000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-crema-turron-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'The Nines', 'dm-vermouth-and-liqueurs-the-nines-16', NULL, 112000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-the-nines-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Malibu', 'dm-vermouth-and-liqueurs-malibu-17', NULL, 115500, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-malibu-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cream De Cafe', 'dm-vermouth-and-liqueurs-cream-de-cafe-18', NULL, 35000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vermouth-and-liqueurs'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vermouth-and-liqueurs-cream-de-cafe-18'
  )
LIMIT 1;

-- SMOOTHIES & FRESH JUICES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Power Smoothie', 'dm-smoothies-and-fresh-juices-power-smoothie', NULL, 7000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-power-smoothie'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mixed Fruit Smoothie', 'dm-smoothies-and-fresh-juices-mixed-fruit-smoothie-2', NULL, 7000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-mixed-fruit-smoothie-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple Smoothie', 'dm-smoothies-and-fresh-juices-pineapple-smoothie-3', NULL, 7000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-pineapple-smoothie-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon Smoothie', 'dm-smoothies-and-fresh-juices-watermelon-smoothie-4', NULL, 7000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-watermelon-smoothie-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cranberry Juice', 'dm-smoothies-and-fresh-juices-cranberry-juice-5', NULL, 12000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-cranberry-juice-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tea and Coffee', 'dm-smoothies-and-fresh-juices-tea-and-coffee-6', NULL, 2000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-tea-and-coffee-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple Juice', 'dm-smoothies-and-fresh-juices-pineapple-juice-7', NULL, 7000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-pineapple-juice-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon Juice', 'dm-smoothies-and-fresh-juices-watermelon-juice-8', NULL, 7000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-watermelon-juice-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orange Juice', 'dm-smoothies-and-fresh-juices-orange-juice-9', NULL, 6000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-orange-juice-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mixed Juice', 'dm-smoothies-and-fresh-juices-mixed-juice-10', NULL, 6000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-mixed-juice-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Parfait Juice', 'dm-smoothies-and-fresh-juices-parfait-juice-11', NULL, 8000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-parfait-juice-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fresh Juice', 'dm-smoothies-and-fresh-juices-fresh-juice-12', NULL, 7000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-fresh-juice-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fresh Smoothie', 'dm-smoothies-and-fresh-juices-fresh-smoothie-13', NULL, 7000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-fresh-smoothie-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivita Juice', 'dm-smoothies-and-fresh-juices-chivita-juice-14', NULL, 8000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-chivita-juice-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'HollandiaYoghurt', 'dm-smoothies-and-fresh-juices-hollandiayoghurt-15', NULL, 8000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-hollandiayoghurt-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Healthy Drink', 'dm-smoothies-and-fresh-juices-healthy-drink-16', NULL, 3000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-smoothies-and-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-smoothies-and-fresh-juices-healthy-drink-16'
  )
LIMIT 1;

-- Done. Restaurant must exist with slug swiss-the-vistana or email it.vistana@swissinternationalhotels.com.
