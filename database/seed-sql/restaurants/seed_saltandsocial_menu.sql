-- Seed: Food Menu + Drinks Menu — Salt And Social
-- Regenerate: node database/build_saltandsocial_seed.mjs
-- Restaurant: admin@saltandsocial.our-menu.online (id 26 in sigsolmenu_resmenu.sql dump)
-- Slug: salt-and-social | Source: https://saltandsocial.our-menu.online/main/
-- Run AFTER migration.sql. Safe to re-run (NOT EXISTS guards).

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

SET @rid = (
  SELECT id FROM restaurants
  WHERE email = 'admin@saltandsocial.our-menu.online'
     OR manager_email = 'admin@saltandsocial.our-menu.online'
  LIMIT 1
);

-- ----- SECTION: Food Menu -----
SET @sid_fm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'food-menu' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'Food Menu', 'food-menu', 1, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_fm IS NULL;
SET @sid_fm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'food-menu' LIMIT 1);

-- ----- SECTION: Drinks Menu -----
SET @sid_dm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'drink-menu' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'Drinks Menu', 'drink-menu', 2, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_dm IS NULL;
SET @sid_dm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'drink-menu' LIMIT 1);

-- ----- FOOD-MENU categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Salads', 'fm-salads', 1, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Appetizers', 'fm-appetizers', 2, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Sandwiches', 'fm-sandwiches', 3, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Burgers', 'fm-burgers', 4, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Fried Chicken', 'fm-fried-chicken', 5, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-fried-chicken')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Mains', 'fm-mains', 6, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Sides', 'fm-sides', 7, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Pasta', 'fm-pasta', 8, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Pizza', 'fm-pizza', 9, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Nigerian Section', 'fm-nigerian', 10, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Dessert', 'fm-dessert', 11, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert')
LIMIT 1;

-- Salads
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Caesar Salad', 'fm-salads-caesar-salad', 'Lettuce, croutons, parmesan, grilled chicken, caesar dressing', 19500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-caesar-salad'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beetroot salad', 'fm-salads-beetroot-salad', 'Mixed leaves, Cherry tomatoes, Beetroot, Mushrooms, sweet corn, feta cheese, vinegar dressing', 18500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-beetroot-salad'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimp Salad', 'fm-salads-shrimp-salad', 'Shrimps, mixed bell peppers, Mushrooms, mixed leaves and lemon mustard sauce', 22500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-shrimp-salad'
  )
LIMIT 1;

-- Appetizers
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Loaded Fries — Boneless chicken', 'fm-appetizers-loaded-fries-boneless-chicken', 'Fries, chicken, mixed cheese, jalapeños, spicy mayo barbecue sauce', 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-loaded-fries-boneless-chicken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Loaded Fries — Chicken suya', 'fm-appetizers-loaded-fries-chicken-suya', 'Fries, chicken suya, onions, spicy suya mayo sauce', 15000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-loaded-fries-chicken-suya'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Loaded Fries — Beef suya', 'fm-appetizers-loaded-fries-beef-suya', 'Fries, beef suya, onions, spicy suya mayo sauce', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-loaded-fries-beef-suya'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken wings', 'fm-appetizers-chicken-wings', 'Provençal, Barbecue or Nigerian mix', 10000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-chicken-wings'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mini Burgers Sliders', 'fm-appetizers-mini-burgers-sliders', 'A set of 3 mini burgers: chicken, classic and smash burger.', 15000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-mini-burgers-sliders'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mini Corn dog', 'fm-appetizers-mini-corn-dog', 'Breaded fried mini hotdog', 8000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-mini-corn-dog'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken pops', 'fm-appetizers-chicken-pops', 'Battered fried chicken with sweet chili sauce', 11000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-chicken-pops'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimp pops', 'fm-appetizers-shrimp-pops', 'Battered fried shrimps with sweet chili sauce', 13500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-shrimp-pops'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beef tacos', 'fm-appetizers-beef-tacos', NULL, 12500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-beef-tacos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken tacos', 'fm-appetizers-chicken-tacos', NULL, 12500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-chicken-tacos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimp tacos', 'fm-appetizers-shrimp-tacos', NULL, 14500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-shrimp-tacos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Suya Quesadilla', 'fm-appetizers-chicken-suya-quesadilla', NULL, 17000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-chicken-suya-quesadilla'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Avocado Bruschetta', 'fm-appetizers-avocado-bruschetta', NULL, 9500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-avocado-bruschetta'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tomato Bruschetta', 'fm-appetizers-tomato-bruschetta', NULL, 8000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-tomato-bruschetta'
  )
LIMIT 1;

-- Sandwiches
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken avocado sandwich', 'fm-sandwiches-chicken-avocado-sandwich', 'Grilled chicken, lettuce, tomato, mayo, parmesan cheese guacamole sauce', 17000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-chicken-avocado-sandwich'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fajita', 'fm-sandwiches-fajita', 'Chicken, grilled mixed vegetables, mozzarella cheese', 13500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-fajita'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Philly Cheese steak', 'fm-sandwiches-philly-cheese-steak', 'Shredded beef, bell peppers, mushrooms, mozzarella cheese', 17000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-philly-cheese-steak'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic hotdog', 'fm-sandwiches-classic-hotdog', 'Hot dog, crispy matchsticks fries, onions, mustard, ketchup', 10000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-classic-hotdog'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Steak Sandwich Tuscan', 'fm-sandwiches-steak-sandwich-tuscan', 'Imported beef fillet, caramelised onions and mushrooms, sundried tomatoes, parmesan cheese', 28000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sandwiches-steak-sandwich-tuscan'
  )
LIMIT 1;

-- Burgers
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'U F O', 'fm-burgers-u-f-o', 'Beef patty, tomatoes, lettuce, onions, pickles, cheddar sauce', 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-u-f-o'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic burger', 'fm-burgers-classic-burger', 'Beef patty, tomatoes, lettuce, pickles, spicy mayo sauce', 13500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-classic-burger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smash burger', 'fm-burgers-smash-burger', 'Double smashed beef, cheddar cheese, pickles, lettuce, burger sauce', 16000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-smash-burger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'S&S Burger', 'fm-burgers-sands-burger', 'Beef patty, caramelised onions and mushrooms, mozzarella, cheddar sauce', 20000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-sands-burger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken suya burger', 'fm-burgers-chicken-suya-burger', 'Chicken suya, tomatoes, onions, lettuce, cucumber and suya mayo sauce', 16500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-chicken-suya-burger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nashville Chicken Burger', 'fm-burgers-nashville-chicken-burger', 'Breaded fried chicken dipped sweet chilli sauce, tomato, pickles and honey mustard sauce.', 18000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-nashville-chicken-burger'
  )
LIMIT 1;

-- Fried Chicken
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whole chicken', 'fm-fried-chicken-whole-chicken', 'Comes with coleslaw, jollof rice and French fries. 10 pcs.', 33500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-fried-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-fried-chicken-whole-chicken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Half chicken', 'fm-fried-chicken-half-chicken', 'Comes with coleslaw, jollof rice and French fries. 5 pcs.', 19000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-fried-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-fried-chicken-half-chicken'
  )
LIMIT 1;

-- Mains
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Chicken Breast', 'fm-mains-grilled-chicken-breast', NULL, 22000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mains-grilled-chicken-breast'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ribeye', 'fm-mains-ribeye', NULL, 46000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mains-ribeye'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lamb chops', 'fm-mains-lamb-chops', NULL, 39000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mains-lamb-chops'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fish and chips', 'fm-mains-fish-and-chips', NULL, 24000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mains-fish-and-chips'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Jumbo Prawns', 'fm-mains-grilled-jumbo-prawns', NULL, 33000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mains-grilled-jumbo-prawns'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Salmon', 'fm-mains-grilled-salmon', NULL, 31000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mains-grilled-salmon'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Chicken Lap', 'fm-mains-grilled-chicken-lap', NULL, 18000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-mains'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-mains-grilled-chicken-lap'
  )
LIMIT 1;

-- Sides
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'French fries', 'fm-sides-french-fries', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-french-fries'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried yam', 'fm-sides-fried-yam', NULL, 5000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-fried-yam'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried plantain', 'fm-sides-fried-plantain', NULL, 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-fried-plantain'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jollof rice', 'fm-sides-jollof-rice', NULL, 6500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-jollof-rice'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mashed potatoes', 'fm-sides-mashed-potatoes', NULL, 6000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-mashed-potatoes'
  )
LIMIT 1;

-- Pasta
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Penne Arrabbiata', 'fm-pasta-penne-arrabbiata', 'Penne pasta, arrabbiata sauce, parmesan cheese.', 16000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-penne-arrabbiata'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rigatoni chicken Alfredo', 'fm-pasta-rigatoni-chicken-alfredo', 'White sauce, chicken, parmesan cheese, mushrooms', 19500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-rigatoni-chicken-alfredo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spaghetti Bolognese', 'fm-pasta-spaghetti-bolognese', 'Spaghetti, tomato sauce, minced beef', 18000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-spaghetti-bolognese'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Prawn Linguine', 'fm-pasta-prawn-linguine', 'Linguine pasta, prawns and tomato chili sauce', 25000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-prawn-linguine'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mac and cheese', 'fm-pasta-mac-and-cheese', 'Macaroni, cheddar sauce and mozzarella cheese', 14000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pasta-mac-and-cheese'
  )
LIMIT 1;

-- Pizza
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margherita', 'fm-pizza-margherita', 'Tomato sauce, mozzarella cheese', 16500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-margherita'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepperoni', 'fm-pizza-pepperoni', 'Tomato sauce, mozzarella cheese, beef pepperoni', 21000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-pepperoni'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vegetarian', 'fm-pizza-vegetarian', 'Tomato sauce, mozzarella cheese, mixed vegetables', 19000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-vegetarian'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Naija', 'fm-pizza-naija', 'Tomato sauce, mozzarella cheese, Chicken suya, and suya mayo sauce', 22000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-naija'
  )
LIMIT 1;

-- Nigerian Section
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Suya', 'fm-nigerian-chicken-suya', NULL, 8500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-chicken-suya'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beef Suya', 'fm-nigerian-beef-suya', NULL, 10000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-beef-suya'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Goat Asun', 'fm-nigerian-goat-asun', 'Assorted goat meat with mixed bell pepper, onions and chili sauce', 14000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-goat-asun'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepper Snails', 'fm-nigerian-pepper-snails', 'Pan fried snail with mixed bell pepper and chili sauce', 19500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-pepper-snails'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Croacker Fish', 'fm-nigerian-grilled-croacker-fish', 'Grilled croaker fish with your choice of side', 25000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-grilled-croacker-fish'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepper Steak', 'fm-nigerian-pepper-steak', 'Sliced grilled beef with our special pepper sauce. Comes with mashed potatoes.', 25000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-pepper-steak'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian Poke Bowl', 'fm-nigerian-nigerian-poke-bowl', 'Bowl of jollof rice, suya (beef or chicken), plantain, chopped onions and tomatoes', 18500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-nigerian-poke-bowl'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian Platter', 'fm-nigerian-nigerian-platter', 'Beef suya, chicken suya, chicken wings, jollof rice, plantain, and fried yam', 38000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-nigerian-platter'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Platter', 'fm-nigerian-seafood-platter', 'Fried calamari, shrimp pops, Nigerian shrimp mix, battered fried fish, plantain, French fries and jollof', 48000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-nigerian'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-nigerian-seafood-platter'
  )
LIMIT 1;

-- Dessert
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'French toast', 'fm-dessert-french-toast', NULL, 11500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-french-toast'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Churros', 'fm-dessert-churros', NULL, 9000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-churros'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'UFO SPLASH', 'fm-dessert-ufo-splash', NULL, 12000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-ufo-splash'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ice cream Delight', 'fm-dessert-ice-cream-delight', NULL, 7500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-ice-cream-delight'
  )
LIMIT 1;

-- ----- DRINK-MENU categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Hot Drinks', 'dm-hot-drinks', 1, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-drinks')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Soft drinks', 'dm-soft-drinks', 2, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Fresh Juices', 'dm-fresh-juices', 3, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-fresh-juices')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Milkshakes', 'dm-milkshakes', 4, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-milkshakes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Mocktails', 'dm-mocktails', 5, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Signature Cocktail', 'dm-signature-cocktail', 6, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Cocktails', 'dm-cocktails', 7, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Beer', 'dm-beer', 8, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Ready to Drink', 'dm-ready-to-drink', 9, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-ready-to-drink')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Vodka', 'dm-vodka', 10, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Tequila', 'dm-tequila', 11, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Rum', 'dm-rum', 12, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Gin', 'dm-gin', 13, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Whiskey', 'dm-whiskey', 14, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Cognac', 'dm-cognac', 15, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Liqueur', 'dm-liqueur', 16, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-liqueur')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Wine', 'dm-wine', 17, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Champagne', 'dm-champagne', 18, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne')
LIMIT 1;

-- Hot Drinks
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Espresso', 'dm-hot-drinks-espresso', NULL, 4000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-drinks-espresso'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Americano', 'dm-hot-drinks-americano', NULL, 5000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-drinks-americano'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cappuccino', 'dm-hot-drinks-cappuccino', NULL, 6500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-drinks-cappuccino'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Café Latte', 'dm-hot-drinks-caf-latte', NULL, 6500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-drinks-caf-latte'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tea selection', 'dm-hot-drinks-tea-selection', NULL, 4500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-drinks-tea-selection'
  )
LIMIT 1;

-- Soft drinks
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Soft drinks', 'dm-soft-drinks-soft-drinks', NULL, 2000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-soft-drinks'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Water Small', 'dm-soft-drinks-water-small', NULL, 1500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-water-small'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Water Big', 'dm-soft-drinks-water-big', NULL, 2500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-water-big'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Red Bull', 'dm-soft-drinks-red-bull', NULL, 4000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-red-bull'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tonic', 'dm-soft-drinks-tonic', NULL, 2000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-tonic'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Soda Water', 'dm-soft-drinks-soda-water', NULL, 2000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-soda-water'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sparkling water small', 'dm-soft-drinks-sparkling-water-small', NULL, 4500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-sparkling-water-small'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sparkling water big', 'dm-soft-drinks-sparkling-water-big', NULL, 8000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-soft-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-soft-drinks-sparkling-water-big'
  )
LIMIT 1;

-- Fresh Juices
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon', 'dm-fresh-juices-watermelon', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-fresh-juices-watermelon'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple', 'dm-fresh-juices-pineapple', NULL, 5000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-fresh-juices-pineapple'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mango', 'dm-fresh-juices-mango', NULL, 6500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-fresh-juices-mango'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carrot', 'dm-fresh-juices-carrot', NULL, 5500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-fresh-juices'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-fresh-juices-carrot'
  )
LIMIT 1;

-- Milkshakes
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Oreo milkshake', 'dm-milkshakes-oreo-milkshake', NULL, 7000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-milkshakes-oreo-milkshake'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chocolate', 'dm-milkshakes-chocolate', NULL, 7000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-milkshakes-chocolate'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vanilla', 'dm-milkshakes-vanilla', NULL, 7000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-milkshakes-vanilla'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry', 'dm-milkshakes-strawberry', NULL, 7000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-milkshakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-milkshakes-strawberry'
  )
LIMIT 1;

-- Mocktails
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tropical dream', 'dm-mocktails-tropical-dream', NULL, 10500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-tropical-dream'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Date night', 'dm-mocktails-date-night', NULL, 10500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-date-night'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fruity iceberg', 'dm-mocktails-fruity-iceberg', NULL, 10500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-fruity-iceberg'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Twilght', 'dm-mocktails-twilght', NULL, 10500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-mocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-mocktails-twilght'
  )
LIMIT 1;

-- Signature Cocktail
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gold Mine', 'dm-signature-cocktail-gold-mine', NULL, 16500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktail-gold-mine'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sweet sensation', 'dm-signature-cocktail-sweet-sensation', NULL, 16500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktail-sweet-sensation'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Green Land', 'dm-signature-cocktail-green-land', NULL, 16500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktail-green-land'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margua Crusta', 'dm-signature-cocktail-margua-crusta', NULL, 16500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktail-margua-crusta'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smoked Sazerac', 'dm-signature-cocktail-smoked-sazerac', NULL, 16500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktail-smoked-sazerac'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lychee Blossom', 'dm-signature-cocktail-lychee-blossom', NULL, 16500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktail-lychee-blossom'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Social island', 'dm-signature-cocktail-social-island', NULL, 16500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktail'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktail-social-island'
  )
LIMIT 1;

-- Cocktails
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Porn star martini', 'dm-cocktails-porn-star-martini', NULL, 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-porn-star-martini'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Old Fashioned', 'dm-cocktails-old-fashioned', NULL, 13500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-old-fashioned'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Negroni', 'dm-cocktails-negroni', NULL, 13500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-negroni'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Long Island', 'dm-cocktails-long-island', NULL, 15000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-long-island'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mojito', 'dm-cocktails-mojito', NULL, 13500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-mojito'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margarita', 'dm-cocktails-margarita', NULL, 13500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-margarita'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin Basil', 'dm-cocktails-gin-basil', NULL, 13500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-gin-basil'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mimosa', 'dm-cocktails-mimosa', NULL, 13500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-mimosa'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Aperol spritz', 'dm-cocktails-aperol-spritz', NULL, 13500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-aperol-spritz'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dry Martini', 'dm-cocktails-dry-martini', NULL, 13500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-dry-martini'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Espresso Martini', 'dm-cocktails-espresso-martini', NULL, 13500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-espresso-martini'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whiskey sour', 'dm-cocktails-whiskey-sour', NULL, 13500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-whiskey-sour'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cosmopolitan', 'dm-cocktails-cosmopolitan', NULL, 13500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-cosmopolitan'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Colada', 'dm-cocktails-colada', NULL, 13500, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cocktails-colada'
  )
LIMIT 1;

-- Beer
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Heineken Draught', 'dm-beer-heineken-draught', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-heineken-draught'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tiger Draught', 'dm-beer-tiger-draught', NULL, 4000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-tiger-draught'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Guinness Draught', 'dm-beer-guinness-draught', NULL, 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-guinness-draught'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Budweiser', 'dm-beer-budweiser', NULL, 5000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-budweiser'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Castle Lite', 'dm-beer-castle-lite', NULL, 5000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-castle-lite'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gulder', 'dm-beer-gulder', NULL, 5000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-gulder'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Desperado', 'dm-beer-desperado', NULL, 5000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-desperado'
  )
LIMIT 1;

-- Ready to Drink
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smirnoff Ice Original', 'dm-ready-to-drink-smirnoff-ice-original', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-ready-to-drink'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-ready-to-drink-smirnoff-ice-original'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smirnoff Ice Double Black Can', 'dm-ready-to-drink-smirnoff-ice-double-black-can', NULL, 5000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-ready-to-drink'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-ready-to-drink-smirnoff-ice-double-black-can'
  )
LIMIT 1;

-- Vodka
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Belvedere', 'dm-vodka-belvedere', 'Shot = ₦8,000', 165000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-belvedere'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grey Goose', 'dm-vodka-grey-goose', 'Shot = ₦5,000', 115000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-grey-goose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beluga', 'dm-vodka-beluga', 'Shot = ₦5,000', 110000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-beluga'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Skyy', 'dm-vodka-skyy', 'Shot = ₦3,500', 70000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-skyy'
  )
LIMIT 1;

-- Tequila
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Azul Reposado', 'dm-tequila-azul-reposado', NULL, 700000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-azul-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Julio 1942', 'dm-tequila-don-julio-1942', NULL, 750000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-don-julio-1942'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Reposado', 'dm-tequila-casamigos-reposado', NULL, 290000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-casamigos-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Añejo', 'dm-tequila-casamigos-a-ejo', NULL, 390000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-casamigos-a-ejo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Teremana Reposado', 'dm-tequila-teremana-reposado', NULL, 240000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-teremana-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'El Mayór Blanco', 'dm-tequila-el-may-r-blanco', NULL, 140000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-el-may-r-blanco'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'El Mayór Reposado', 'dm-tequila-el-may-r-reposado', NULL, 230000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-el-may-r-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'El Mayór Añejo', 'dm-tequila-el-may-r-a-ejo', NULL, 280000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-el-may-r-a-ejo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Patrón Blanco', 'dm-tequila-patr-n-blanco', 'Shot = ₦6,500', 135000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-patr-n-blanco'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Patrón Reposado', 'dm-tequila-patr-n-reposado', 'Shot = ₦8,000', 170000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-patr-n-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Patrón Añejo', 'dm-tequila-patr-n-a-ejo', 'Shot = ₦9,000', 210000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-patr-n-a-ejo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mijenta Blanco', 'dm-tequila-mijenta-blanco', 'Shot = ₦7,000', 145000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-mijenta-blanco'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mijenta Reposado', 'dm-tequila-mijenta-reposado', NULL, 280000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-mijenta-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jose Cuervo Blanco', 'dm-tequila-jose-cuervo-blanco', 'Shot = ₦3,500', 80000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-jose-cuervo-blanco'
  )
LIMIT 1;

-- Rum
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peaky Blinders', 'dm-rum-peaky-blinders', 'Shot = ₦4,000', 80000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-peaky-blinders'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bacardí White', 'dm-rum-bacard-white', 'Shot = ₦3,500', 70000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-bacard-white'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bacardí Oak Heart', 'dm-rum-bacard-oak-heart', 'Shot = ₦3,500', 70000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-bacard-oak-heart'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Diplomático', 'dm-rum-diplom-tico', NULL, 180000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-diplom-tico'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Captain Morgan', 'dm-rum-captain-morgan', NULL, 55000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-captain-morgan'
  )
LIMIT 1;

-- Gin
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hendrick''s', 'dm-gin-hendrick-s', 'Shot = ₦6,500', 140000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-hendrick-s'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bombay Sapphire', 'dm-gin-bombay-sapphire', 'Shot = ₦3,500', 85000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-bombay-sapphire'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tanqueray N.10', 'dm-gin-tanqueray-n-10', 'Shot = ₦5,000', 100000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-tanqueray-n-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin Mare', 'dm-gin-gin-mare', 'Shot = ₦5,500', 110000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-gin-mare'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Three Brothers', 'dm-gin-three-brothers', NULL, 165000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-three-brothers'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pedro''s Ogogoro Gin', 'dm-gin-pedro-s-ogogoro-gin', NULL, 80000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-pedro-s-ogogoro-gin'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gordon''s Pink Berry', 'dm-gin-gordon-s-pink-berry', NULL, 60000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-gordon-s-pink-berry'
  )
LIMIT 1;

-- Whiskey
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Singleton 12', 'dm-whiskey-singleton-12', 'Glass = ₦11,500', 160000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-singleton-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Singleton 15', 'dm-whiskey-singleton-15', 'Glass = ₦14,500', 200000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-singleton-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 12', 'dm-whiskey-glenfiddich-12', 'Shot = ₦8,000', 180000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-glenfiddich-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 15', 'dm-whiskey-glenfiddich-15', 'Shot = ₦10,000', 225000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-glenfiddich-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 18', 'dm-whiskey-glenfiddich-18', 'Shot = ₦14,000', 300000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-glenfiddich-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Monkey Shoulder', 'dm-whiskey-monkey-shoulder', 'Shot = ₦5,000', 100000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-monkey-shoulder'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Kilchoman', 'dm-whiskey-kilchoman', NULL, 280000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-kilchoman'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jack Daniel''s', 'dm-whiskey-jack-daniel-s', 'Shots = ₦4,000', 80000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-jack-daniel-s'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jameson', 'dm-whiskey-jameson', 'Shot = ₦4,000', 85000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-jameson'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jameson Black Barrel', 'dm-whiskey-jameson-black-barrel', 'Shot = ₦5,000', 100000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-jameson-black-barrel'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnnie Walker Gold Label', 'dm-whiskey-johnnie-walker-gold-label', NULL, 185000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-johnnie-walker-gold-label'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnnie Walker Black Label', 'dm-whiskey-johnnie-walker-black-label', 'Shot = ₦5,000', 105000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-johnnie-walker-black-label'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Akashi', 'dm-whiskey-akashi', NULL, 170000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-akashi'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Kavalan', 'dm-whiskey-kavalan', NULL, 280000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whiskey'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whiskey-kavalan'
  )
LIMIT 1;

-- Cognac
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rémy Martin VS', 'dm-cognac-r-my-martin-vs', 'Shot = ₦7,000', 175000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-r-my-martin-vs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rémy Martin VSOP', 'dm-cognac-r-my-martin-vsop', NULL, 240000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-r-my-martin-vsop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rémy Martin 1738', 'dm-cognac-r-my-martin-1738', 'Shot = ₦19,000', 280000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-r-my-martin-1738'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rémy Martin XO', 'dm-cognac-r-my-martin-xo', NULL, 700000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-r-my-martin-xo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VSOP', 'dm-cognac-hennessy-vsop', NULL, 280000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-hennessy-vsop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VS', 'dm-cognac-hennessy-vs', 'Shot = ₦7,000', 175000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-hennessy-vs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martell XO', 'dm-cognac-martell-xo', NULL, 800000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-martell-xo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martell Blue Swift', 'dm-cognac-martell-blue-swift', NULL, 250000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-martell-blue-swift'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martell VS', 'dm-cognac-martell-vs', 'Shot = ₦7,000', 150000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-martell-vs'
  )
LIMIT 1;

-- Liqueur
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jägermeister', 'dm-liqueur-j-germeister', 'Shot = ₦3,500', 80000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-liqueur'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-liqueur-j-germeister'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Arak', 'dm-liqueur-arak', 'Shot = ₦3,500', 70000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-liqueur'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-liqueur-arak'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Campari', 'dm-liqueur-campari', 'Shot = ₦3,000', 60000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-liqueur'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-liqueur-campari'
  )
LIMIT 1;

-- Wine
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Kopke white', 'dm-wine-kopke-white', 'Porto sweet white wine', 70000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-kopke-white'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Kopke red', 'dm-wine-kopke-red', 'Porto sweet red wine', 70000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-kopke-red'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Kopke 2018', 'dm-wine-kopke-2018', 'Porto sweet red wine', 115000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-kopke-2018'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Alma De Vega Honesty', 'dm-wine-alma-de-vega-honesty', 'Dry white wine', 45000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-alma-de-vega-honesty'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Alma De Vega Love', 'dm-wine-alma-de-vega-love', 'Semi-sweet white wine', 45000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-alma-de-vega-love'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Alma De Vega Sensibility', 'dm-wine-alma-de-vega-sensibility', 'Sweet rosé wine', 45000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-alma-de-vega-sensibility'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Alma De Vega Respect', 'dm-wine-alma-de-vega-respect', 'Dry red wine', 45000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-alma-de-vega-respect'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Wine glass', 'dm-wine-wine-glass', NULL, 8000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-wine-glass'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Alma de Vega Sparkling Rosé', 'dm-wine-alma-de-vega-sparkling-ros', NULL, 60000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-alma-de-vega-sparkling-ros'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amabile Di Rosa', 'dm-wine-amabile-di-rosa', 'Sweet red, white and rosé', 45000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-wine-amabile-di-rosa'
  )
LIMIT 1;

-- Champagne
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët Brut', 'dm-champagne-mo-t-brut', NULL, 225000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-mo-t-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moët Nectar Rosé', 'dm-champagne-mo-t-nectar-ros', NULL, 290000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-mo-t-nectar-ros'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Laurent Perrier Brut', 'dm-champagne-laurent-perrier-brut', NULL, 260000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-laurent-perrier-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Laurent Perrier Demi Sec', 'dm-champagne-laurent-perrier-demi-sec', NULL, 270000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-laurent-perrier-demi-sec'
  )
LIMIT 1;

-- Done. @rid should be 26 for Salt And Social on production dump.
