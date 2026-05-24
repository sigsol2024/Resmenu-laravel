-- Seed: Food Menu + Drinks Menu — The Opal
-- Regenerate: node database/build_opal_seed.mjs
-- Restaurant: opallagos1@gmail.com (id 19 in sigsolmenu_resmenu.sql dump)
-- Source: https://opal.our-menu.online/food-menu/ and /drink-menu/
-- Run AFTER migration.sql. Safe to re-run (NOT EXISTS guards). Skips items that already exist by slug.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

SET @rid = (
  SELECT id FROM restaurants
  WHERE email = 'opallagos1@gmail.com'
     OR manager_email = 'opallagos1@gmail.com'
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
SELECT @rid, @sid_fm, 'Burgers', 'fm-burgers', 3, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Sides', 'fm-sides', 4, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Bao', 'fm-bao', 5, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-bao')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Main Course', 'fm-main-course', 6, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Pastas', 'fm-pastas', 7, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-pastas')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Pizza', 'fm-pizza', 8, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Meats', 'fm-meats', 9, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Opal Platter', 'fm-opal-platter', 10, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-opal-platter')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Tacos Menu', 'fm-tacos-menu', 11, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Dessert', 'fm-dessert', 12, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'Karaoke Food Menu', 'fm-karaoke-food', 13, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'fm-karaoke-food')
LIMIT 1;

-- Salads
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beetroot Salad', 'fm-salads-beetroot-salad', 'Poached beetroot, goat cheese, caramelized walnuts, mixed greens, and Roman lettuce served with orange vinaigrette sauce', 18000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-beetroot-salad'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Caeser Salad Chicken', 'fm-salads-caeser-salad-chicken', 'Romaine lettuce, Parmesan cheese & garlic croutons tossed in our Caesar sauce.', 16300, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-caeser-salad-chicken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Salmon Salad', 'fm-salads-salmon-salad', 'Roman lettuce & mix mesclun, cherry tomato, edamame, walnuts, radish, avocado, mango, sesame seeds & sweet chili sauce.', 18900, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-salmon-salad'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cobb Salad', 'fm-salads-cobb-salad', 'Iceberg, avocado, tomato, and cucumber topped with chicken tender strips served with ranch sauce.', 19200, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-cobb-salad'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Salad', 'fm-salads-seafood-salad', 'Tender shrimp, calamari, smoked salmon, potatoes, olives, tomatoes, and lettuce served with lemon mustard.', 23400, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-seafood-salad'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Greek Salad', 'fm-salads-greek-salad', 'Roman lettuce, cucumber, green pepper, tomato, red onions, oregano, feta cheese, and rocket leaves served with lemon mustard sauce.', 18900, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-salads-greek-salad'
  )
LIMIT 1;

-- Appetizers
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hummus With Meat', 'fm-appetizers-hummus-with-meat', 'Chickpea puree with tahini sauce, served with crispy pitta bread', 14000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-hummus-with-meat'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Calamari Ring', 'fm-appetizers-calamari-ring', 'Deep-fried calamari rings, served in tartar sauce', 18000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-calamari-ring'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimp Spring Rolls', 'fm-appetizers-shrimp-spring-rolls', 'Marinated shrimp wrapped and fried in crispy rolls served with tartar & cocktail sauce', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-shrimp-spring-rolls'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Wings Your Way', 'fm-appetizers-chicken-wings-your-way', 'Deep fried chicken wings with choice of sauce: chili sauce, BBQ sauce, Suya spice', 14500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-chicken-wings-your-way'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'B.B.Q Chicken Skewers', 'fm-appetizers-b-b-q-chicken-skewers', 'Marinated chicken strips, grilled, glazed with BBQ sauce.', 14000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-b-b-q-chicken-skewers'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Imperial Fried Spring Roll', 'fm-appetizers-imperial-fried-spring-roll', 'Rolled in rice paper, deep fried, minced beef meat, rice vermicelli, carrot, and shitake mushrooms, served with homemade Asian dip.', 20300, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-imperial-fried-spring-roll'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Butterfly Shrimp', 'fm-appetizers-butterfly-shrimp', 'Breaded deep-fried shrimps served with homemade cocktail dip and tartar sauce', 18000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-butterfly-shrimp'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Taco', 'fm-appetizers-chicken-taco', 'Marinated chicken, avocado, fried onions & iceberg.', 16500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-chicken-taco'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Platter', 'fm-appetizers-seafood-platter', 'Fried calamari ring, shrimp spring roll, fish finger & prawns suya, served with tartar sauce, sweet chili', 37400, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-seafood-platter'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian Platter', 'fm-appetizers-nigerian-platter', 'Spicy gizzards & snails, chicken suya, yam fingers & plantain, fresh tomato, red crispy onions served with chili sauce.', 33000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-nigerian-platter'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Appetizer Plater', 'fm-appetizers-appetizer-plater', 'Chicken tender, fried calamari, chicken wings, yam finger, fried spring rolls, fried panzerotti, served with tartar sauce, cocktail, sweet chili, honey mustard, Thai dips.', 37400, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-appetizer-plater'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Suya', 'fm-appetizers-suya', 'Your choice: beef, shrimp, or chicken. Coated with suya pepper and served with tomato and onions.', 15700, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-suya'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Asun', 'fm-appetizers-asun', 'Goat meat served with sautéed green pepper, onions & tomato chili sauce', 15700, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-appetizers-asun'
  )
LIMIT 1;

-- Burgers
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Burger', 'fm-burgers-chicken-burger', 'Marinated grilled chicken breast with cheddar cheese, Roman lettuce, pickles, tomato, grilled onions, aioli & garlic mayo sauce. Served with french fries and coleslaw.', 20400, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-chicken-burger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic Beef Burger', 'fm-burgers-classic-beef-burger', 'Premium beef with cheddar cheese, Roman lettuce, pickles, jalapenos, grilled fresh mushroom & onions, avocado served with sriracha cocktail sauce. Served with french fries and coleslaw.', 22600, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-classic-beef-burger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Opal Beef Burger', 'fm-burgers-opal-beef-burger', 'Premium beef 170g with cheddar cheese, onion rings, crispy beef bacon, lettuce, and caramelized onion served with classic & sweet chili sauce. Served with french fries and coleslaw.', 24300, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-burgers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-burgers-opal-beef-burger'
  )
LIMIT 1;

-- Sides
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Creamy Potato Mash', 'fm-sides-creamy-potato-mash', NULL, 6500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-creamy-potato-mash'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'French Fries', 'fm-sides-french-fries', NULL, 5000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-french-fries'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Steam Rice', 'fm-sides-steam-rice', NULL, 4500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-steam-rice'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Fried Rice', 'fm-sides-seafood-fried-rice', NULL, 12000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-seafood-fried-rice'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Wedges Potato', 'fm-sides-wedges-potato', NULL, 5000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-wedges-potato'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sauteed Vegetables', 'fm-sides-sauteed-vegetables', NULL, 6000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-sauteed-vegetables'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Singaporean Fried Rice', 'fm-sides-singaporean-fried-rice', NULL, 6500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-singaporean-fried-rice'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Yam Fries', 'fm-sides-yam-fries', NULL, 7000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-yam-fries'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried Plantain', 'fm-sides-fried-plantain', NULL, 7000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-sides'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-sides-fried-plantain'
  )
LIMIT 1;

-- Bao
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tozo Bao', 'fm-bao-tozo-bao', 'Pulled BBQ tozo beef, fluffy steamed bao buns, Roman lettuce, chopped spring onions, and toasted sesame seeds.', 15600, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-bao'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-bao-tozo-bao'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Duck Bao', 'fm-bao-duck-bao', 'Smoked pulled duck in an orange cocktail, fluffy steamed bao buns, chopped spring onions, pickle carrot, sesame seed.', 16000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-bao'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-bao-duck-bao'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimp Bao', 'fm-bao-shrimp-bao', 'Crispy battered deep-fried shrimp, fluffy steamed bao, sriracha spicy cocktail, cucumber pickles, spring onion, sesame seed', 18000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-bao'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-bao-shrimp-bao'
  )
LIMIT 1;

-- Main Course
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whole Grill Fish', 'fm-main-course-whole-grill-fish', 'Grilled whole fish marinated in African sauce, sautéed vegetables served with fried yam and plantain', 32300, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-whole-grill-fish'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grill Chicken', 'fm-main-course-grill-chicken', 'African-style marinated half-grilled chicken, fried plantain rice, spicy tomato sauce African way', 27000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-grill-chicken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Skewer', 'fm-main-course-seafood-skewer', 'Prawns, fresh salmon, fish filet, bell pepper served with steamed vegetables & grilled potato.', 45100, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-seafood-skewer'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jumbo Prawns', 'fm-main-course-jumbo-prawns', 'Marinated grilled jumbo prawns, served with yam chips, steamed vegetables & martini sauce.', 45200, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-jumbo-prawns'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Baked Salmon', 'fm-main-course-baked-salmon', 'Marinated salmon filet served with spinach, edamame, asparagus, mashed potato & martini sauce', 53700, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-baked-salmon'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Roulade', 'fm-main-course-chicken-roulade', 'Crispy fried chicken stuffed with mozzarella cheese and turkey ham, grilled vegetables served with mushroom mustard sauce', 30800, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-chicken-roulade'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Artichoke Escalope', 'fm-main-course-artichoke-escalope', 'Deep fried breaded chicken breast topped with creamy sauce and mozzarella cheese served with red sauce', 28400, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-artichoke-escalope'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seabass', 'fm-main-course-seabass', 'Steamed fish served with spinach, mashed potato & lemon grass sauce.', 55700, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-main-course'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-main-course-seabass'
  )
LIMIT 1;

-- Pastas
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carbonara', 'fm-pastas-carbonara', 'Spaghetti pasta, creamy sauce, your choice of bacon or smoked turkey, parmesan cheese', 24800, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pastas'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pastas-carbonara'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tagliatelle Alfredo', 'fm-pastas-tagliatelle-alfredo', 'Fettuccini, creamy sauce, chicken breast, mixed mushrooms, parmesan cheese', 22600, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pastas'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pastas-tagliatelle-alfredo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Creamy Cajun Penne', 'fm-pastas-creamy-cajun-penne', 'Penne pasta, tomato sauce, creamy sauce, chicken breast, mushroom, Cajun spice, parmesan cheese', 29300, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pastas'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pastas-creamy-cajun-penne'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Penne Arrabbiata', 'fm-pastas-penne-arrabbiata', 'Penne pasta, spicy tomato sauce, black olive, basil, cherry tomato & parmesan cheese', 18700, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pastas'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pastas-penne-arrabbiata'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimp Linguini', 'fm-pastas-shrimp-linguini', 'Linguini pasta, tomato sauce, cherry tomato, basil, bisque sauce & parmesan cheese.', 30400, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pastas'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pastas-shrimp-linguini'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Pastahroom', 'fm-pastas-seafood-pastahroom', 'Mix marinated prawns, calamari, mussels, basil, cherry tomato, Parmesan cheese and homemade tomato sauce.', 36700, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pastas'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pastas-seafood-pastahroom'
  )
LIMIT 1;

-- Pizza
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margherita', 'fm-pizza-margherita', 'Homemade tomato sauce, mozzarella cheese, fresh basil', 13000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-margherita'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Fungi Pizza', 'fm-pizza-chicken-fungi-pizza', 'Homemade tomato sauce topped with mixed mushrooms and cheese, grilled chicken breast, truffle oil & basil.', 18000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-chicken-fungi-pizza'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vegetarian Pizza', 'fm-pizza-vegetarian-pizza', 'Homemade tomato sauce topped with mixed mushrooms and cheese, grilled vegetables, truffle oil & basil', 15400, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-vegetarian-pizza'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepperoni Pizza', 'fm-pizza-pepperoni-pizza', 'Beef pepperoni, homemade tomato sauce, mixed cheese', 17900, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-pepperoni-pizza'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Pizza', 'fm-pizza-seafood-pizza', 'BBQ sauce, calamari, shrimp, octopus, and fresh pineapple topped with mixed cheese, basil', 23600, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-seafood-pizza'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smocked Salmon Pizza', 'fm-pizza-smocked-salmon-pizza', 'Cream cheese topped with smoked salmon and mixed cheese, rocket leaves, and capers.', 29500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-pizza-smocked-salmon-pizza'
  )
LIMIT 1;

-- Meats
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled T-Bone Steak', 'fm-meats-grilled-t-bone-steak', 'Grilled T-bone steak, grilled vegetables, wedges, and potatoes served with mushroom sauce', 40000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-meats-grilled-t-bone-steak'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mongolian Beef', 'fm-meats-mongolian-beef', 'Sliced imported beef filet, sautéed in homemade Mongolian sauce served with Singaporean fried rice.', 42000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-meats-mongolian-beef'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ribb Eye Steak', 'fm-meats-ribb-eye-steak', 'Grilled imported rib eye, sautéed vegetables, and sweet mashed potato', 40000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-meats-ribb-eye-steak'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Tozo', 'fm-meats-grilled-tozo', 'Marinated smoked beef cut served with leeks garlic creamy sauce and mashed potato', 26000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-meats-grilled-tozo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Short Ribs', 'fm-meats-short-ribs', 'Slow braised beef short ribs, sticky buffalo glazed, served with creamy mash potato & glaze spicy carrot', 52000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-meats-short-ribs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Braised Lamb Shank', 'fm-meats-braised-lamb-shank', 'Braised and marinated to perfection served with green beans, mashed potato & pepper sauce', 49000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-meats-braised-lamb-shank'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lamb Chops', 'fm-meats-lamb-chops', 'Grilled imported lamb chops topped with garlic herbs sauce served with sautéed vegetables, jollof rice & pepper sauce.', 55900, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-meats'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-meats-lamb-chops'
  )
LIMIT 1;

-- Opal Platter
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Opal Platter', 'fm-opal-platter-opal-platter', 'Spicy BBQ wings, yaji prawn skewer, grilled beef slider, peppered snails, plantain slices, french fries', 85000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-opal-platter'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-opal-platter-opal-platter'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'South American Style Grilled Platter', 'fm-opal-platter-south-american-style-grilled-platter', 'Serve up to 4: rib eye, lamb chops, duck breast, chicken breast, and grilled vegetables served with chimichurri, teriyaki, and BBQ sauces.', 150000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-opal-platter'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-opal-platter-south-american-style-grilled-platter'
  )
LIMIT 1;

-- Tacos Menu
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Tacos', 'fm-tacos-menu-chicken-tacos', 'Choice of hard or soft shell served with salsa and lemon', 18000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-chicken-tacos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Minced Beef Tacos', 'fm-tacos-menu-minced-beef-tacos', 'Choice of hard or soft shell served with salsa and lemon', 17000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-minced-beef-tacos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimps Tacos', 'fm-tacos-menu-shrimps-tacos', 'Choice of hard or soft shell served with salsa and lemon', 23500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-shrimps-tacos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hawaian Fish Tacos', 'fm-tacos-menu-hawaian-fish-tacos', 'Choice of hard or soft shell served with salsa and lemon', 20500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-hawaian-fish-tacos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tacos Platter', 'fm-tacos-menu-tacos-platter', 'Choice of hard or soft shell served with salsa and lemon', 45000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-tacos-platter'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chiken Tacos Salad Bowl', 'fm-tacos-menu-chiken-tacos-salad-bowl', 'Crispy lettuce, salsa, guacamole, mixed cheese and jalapeno, served with BBQ sauce', 13800, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-chiken-tacos-salad-bowl'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimps Tacos Salad Bowl', 'fm-tacos-menu-shrimps-tacos-salad-bowl', 'Crispy lettuce, salsa, guacamole, mixed cheese and jalapeno, served with BBQ sauce', 18200, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-shrimps-tacos-salad-bowl'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dynamite Shrimp', 'fm-tacos-menu-dynamite-shrimp', 'Crispy fried shrimp coated in a spicy mayonnaise dressing', 18500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-dynamite-shrimp'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crispy Chicken Wings', 'fm-tacos-menu-crispy-chicken-wings', 'Marinated chicken wings served with BBQ and sweet chili dip', 18500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-crispy-chicken-wings'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fully Loaded Nachos', 'fm-tacos-menu-fully-loaded-nachos', 'Served with salsa, guacamole, sour cream, and melted cheddar cheese topped with jalapeño', 15600, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-fully-loaded-nachos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Quesadillas', 'fm-tacos-menu-chicken-quesadillas', 'Served with salsa, guacamole, and sour cream', 16000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-chicken-quesadillas'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beef Quesadillas', 'fm-tacos-menu-beef-quesadillas', 'Served with salsa, guacamole, and sour cream', 18000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-beef-quesadillas'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimps Quesadillas', 'fm-tacos-menu-shrimps-quesadillas', 'Served with salsa, guacamole, and sour cream', 30000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-shrimps-quesadillas'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Fajita Wrap', 'fm-tacos-menu-chicken-fajita-wrap', 'Served with salsa and french fries', 18500, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-chicken-fajita-wrap'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beef Fajita Wrap', 'fm-tacos-menu-beef-fajita-wrap', 'Served with salsa and french fries', 18500, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-beef-fajita-wrap'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Shrimps Fajita Wrap', 'fm-tacos-menu-shrimps-fajita-wrap', 'Served with salsa and french fries', 27400, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-shrimps-fajita-wrap'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dynamite Chicken', 'fm-tacos-menu-dynamite-chicken', 'Crispy golden brown fried chicken served with dynamite sauce', 16800, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-dynamite-chicken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nachos', 'fm-tacos-menu-nachos', 'Served with salsa and guacamole topped with jalapeño', 14000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-nachos'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'French Fries', 'fm-tacos-menu-french-fries', 'Tacos menu portion', 6000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-tacos-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-tacos-menu-french-fries'
  )
LIMIT 1;

-- Dessert
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chocolate Fondant', 'fm-dessert-chocolate-fondant', 'Served with vanilla ice cream.', 9000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-chocolate-fondant'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ice Cream Scoop', 'fm-dessert-ice-cream-scoop', 'Chocolate, vanilla and extra', 7000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-ice-cream-scoop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry Cheese Cake', 'fm-dessert-strawberry-cheese-cake', NULL, 14000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-strawberry-cheese-cake'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mixed Fruit & Vanille Ice Cream', 'fm-dessert-mixed-fruit-and-vanille-ice-cream', NULL, 9000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-dessert-mixed-fruit-and-vanille-ice-cream'
  )
LIMIT 1;

-- Karaoke Food Menu
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'The Spotlight', 'fm-karaoke-food-the-spotlight', '3–5 people: one bottle of wine (red or white), complimentary juice, royalty platter (chicken wings, spicy gizzard & snail, mini slider, french fries)', 100000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-karaoke-food'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-karaoke-food-the-spotlight'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'The Headliner', 'fm-karaoke-food-the-headliner', '3–7 people: one bottle of wine (red or white), complimentary juice, stove hok platter (chicken wings, spicy gizzard & snail, mini slider, suya, yam & plantain fries)', 150000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-karaoke-food'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-karaoke-food-the-headliner'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'The Legend', 'fm-karaoke-food-the-legend', 'Max 10 people: two bottles of wine (red or white), complimentary juice, tropical platter (chicken wings, spicy gizzard & snail, mini slider, spring rolls, yam & plantain fries)', 200000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'fm-karaoke-food'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'fm-karaoke-food-the-legend'
  )
LIMIT 1;

-- ----- DRINK-MENU categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Champagne', 'dm-champagne', 1, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Tequila', 'dm-tequila', 2, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Cognac', 'dm-cognac', 3, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Vodka', 'dm-vodka', 4, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Rum', 'dm-rum', 5, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Gin', 'dm-gin', 6, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Whisky', 'dm-whisky', 7, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Red Wine', 'dm-red-wine', 8, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Rose Wine', 'dm-rose-wine', 9, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'White Wine', 'dm-white-wine', 10, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-white-wine')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Beer', 'dm-beer', 11, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Non Alcohol Drinks', 'dm-non-alcohol', 12, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Juice Pitcher', 'dm-juice-pitcher', 13, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-juice-pitcher')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Hot Beverages', 'dm-hot-beverages', 14, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-beverages')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Classic Cocktails', 'dm-classic-cocktails', 15, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Signature Cocktails', 'dm-signature-cocktails', 16, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Shots', 'dm-shots', 17, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Special Shots', 'dm-special-shots', 18, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Shisha Menu', 'dm-shisha-menu', 19, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Karaoke Drink Menu', 'dm-karaoke-drinks', 20, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks')
LIMIT 1;

-- Champagne
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ace of Spades Brut', 'dm-champagne-ace-of-spades-brut', NULL, 1200000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-ace-of-spades-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dom Perignon Brut', 'dm-champagne-dom-perignon-brut', NULL, 1100000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-dom-perignon-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dom Perignon Rose', 'dm-champagne-dom-perignon-rose', NULL, 1300000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-dom-perignon-rose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Laurent Perrier Demi Sec', 'dm-champagne-laurent-perrier-demi-sec', NULL, 260000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-laurent-perrier-demi-sec'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Laurent Perrier Brut', 'dm-champagne-laurent-perrier-brut', NULL, 240000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-laurent-perrier-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Laurent Perrier Cuvee Rose', 'dm-champagne-laurent-perrier-cuvee-rose', NULL, 350000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-laurent-perrier-cuvee-rose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet Brut', 'dm-champagne-moet-brut', NULL, 250000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-moet-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet Imperial Ice', 'dm-champagne-moet-imperial-ice', NULL, 400000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-moet-imperial-ice'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet Nectar Rose', 'dm-champagne-moet-nectar-rose', NULL, 360000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-moet-nectar-rose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet Nectar Imperial', 'dm-champagne-moet-nectar-imperial', NULL, 380000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-moet-nectar-imperial'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ruinart Blanc de Blanc', 'dm-champagne-ruinart-blanc-de-blanc', NULL, 400000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-ruinart-blanc-de-blanc'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ruinart Brut', 'dm-champagne-ruinart-brut', NULL, 210000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-ruinart-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Cliquot Brut', 'dm-champagne-veuve-cliquot-brut', NULL, 320000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-veuve-cliquot-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Cliquot Rich', 'dm-champagne-veuve-cliquot-rich', NULL, 450000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-veuve-cliquot-rich'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Cliquot Rose', 'dm-champagne-veuve-cliquot-rose', NULL, 370000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-veuve-cliquot-rose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pol Roger Brut', 'dm-champagne-pol-roger-brut', NULL, 400000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-pol-roger-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pol Roger Rose', 'dm-champagne-pol-roger-rose', NULL, 450000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-pol-roger-rose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crystal', 'dm-champagne-crystal', NULL, 1000000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-crystal'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Luc Belaire Rose Fantome', 'dm-champagne-luc-belaire-rose-fantome', NULL, 150000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-luc-belaire-rose-fantome'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Luc Belaire Rose', 'dm-champagne-luc-belaire-rose', NULL, 150000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-luc-belaire-rose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carnard Duchene Demi Sec', 'dm-champagne-carnard-duchene-demi-sec', NULL, 160000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-carnard-duchene-demi-sec'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carnard Duchene Brut', 'dm-champagne-carnard-duchene-brut', NULL, 190000, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-carnard-duchene-brut'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carnard Duchene Rose', 'dm-champagne-carnard-duchene-rose', NULL, 250000, 23, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-champagne-carnard-duchene-rose'
  )
LIMIT 1;

-- Tequila
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Magnum', 'dm-tequila-casamigos-magnum', NULL, 1000000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-casamigos-magnum'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Reposado', 'dm-tequila-casamigos-reposado', NULL, 500000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-casamigos-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Reposado 1LTR', 'dm-tequila-casamigos-reposado-1ltr', NULL, 700000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-casamigos-reposado-1ltr'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigos Anejo', 'dm-tequila-casamigos-anejo', NULL, 400000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-casamigos-anejo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clase Azul Reposado', 'dm-tequila-clase-azul-reposado', NULL, 800000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-clase-azul-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clase Azul Plata Jalisco', 'dm-tequila-clase-azul-plata-jalisco', NULL, 420000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-clase-azul-plata-jalisco'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Magnum Don Julio 1942', 'dm-tequila-magnum-don-julio-1942', NULL, 2000000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-magnum-don-julio-1942'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Julio 1942', 'dm-tequila-don-julio-1942', NULL, 1000000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-don-julio-1942'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Julio Reposado', 'dm-tequila-don-julio-reposado', NULL, 500000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-don-julio-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Volcan Anejo', 'dm-tequila-volcan-anejo', NULL, 300000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-volcan-anejo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Avion Tequila', 'dm-tequila-avion-tequila', NULL, 780000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-avion-tequila'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Volcan Cristalino', 'dm-tequila-volcan-cristalino', NULL, 350000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-volcan-cristalino'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casa Maestri', 'dm-tequila-casa-maestri', NULL, 350000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-casa-maestri'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1800 Silver', 'dm-tequila-1800-silver', NULL, 250000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-1800-silver'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1800 Reposado', 'dm-tequila-1800-reposado', NULL, 300000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-1800-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1800 Anejo', 'dm-tequila-1800-anejo', NULL, 350000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-1800-anejo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Adiccion Reposado', 'dm-tequila-adiccion-reposado', NULL, 750000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-adiccion-reposado'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Adiccion Anejo', 'dm-tequila-adiccion-anejo', NULL, 950000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-tequila-adiccion-anejo'
  )
LIMIT 1;

-- Cognac
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VSOP', 'dm-cognac-hennessy-vsop', NULL, 350000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-hennessy-vsop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy X.O', 'dm-cognac-hennessy-x-o', NULL, 1000000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-hennessy-x-o'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martel Bleu Swift', 'dm-cognac-martel-bleu-swift', NULL, 320000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-martel-bleu-swift'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martel XO', 'dm-cognac-martel-xo', NULL, 850000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-martel-xo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin VS', 'dm-cognac-remy-martin-vs', NULL, 290000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-remy-martin-vs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin VSOP', 'dm-cognac-remy-martin-vsop', NULL, 290000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-remy-martin-vsop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin 1738', 'dm-cognac-remy-martin-1738', NULL, 320000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-remy-martin-1738'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin XO', 'dm-cognac-remy-martin-xo', NULL, 810000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-remy-martin-xo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin XO Night', 'dm-cognac-remy-martin-xo-night', NULL, 575000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-cognac-remy-martin-xo-night'
  )
LIMIT 1;

-- Vodka
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin Mare', 'dm-vodka-gin-mare', NULL, 130000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-gin-mare'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hendricks', 'dm-vodka-hendricks', NULL, 150000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-hendricks'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pedro', 'dm-vodka-pedro', NULL, 170000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-vodka-pedro'
  )
LIMIT 1;

-- Rum
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beluga Gold Line', 'dm-rum-beluga-gold-line', NULL, 166000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-beluga-gold-line'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Belverde', 'dm-rum-belverde', NULL, 200000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-belverde'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grey Goose', 'dm-rum-grey-goose', NULL, 130000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-grey-goose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Eminente Rum', 'dm-rum-eminente-rum', NULL, 210000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rum-eminente-rum'
  )
LIMIT 1;

-- Gin
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Baccardi White', 'dm-gin-baccardi-white', NULL, 90000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-baccardi-white'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coro Coro Spiced', 'dm-gin-coro-coro-spiced', NULL, 100000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-gin-coro-coro-spiced'
  )
LIMIT 1;

-- Whisky
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivas 15', 'dm-whisky-chivas-15', NULL, 200000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-chivas-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivas 18', 'dm-whisky-chivas-18', NULL, 270000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-chivas-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dalmore 12', 'dm-whisky-dalmore-12', NULL, 250000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-dalmore-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 12', 'dm-whisky-glenfiddich-12', NULL, 200000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenfiddich-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 15', 'dm-whisky-glenfiddich-15', NULL, 280000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenfiddich-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 18', 'dm-whisky-glenfiddich-18', NULL, 350000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenfiddich-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 21', 'dm-whisky-glenfiddich-21', NULL, 950000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenfiddich-21'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 23', 'dm-whisky-glenfiddich-23', NULL, 1150000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenfiddich-23'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 26', 'dm-whisky-glenfiddich-26', NULL, 2100000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenfiddich-26'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenmorangie 10 Original', 'dm-whisky-glenmorangie-10-original', NULL, 190000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenmorangie-10-original'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenmorangie 18 Yrs Extreme', 'dm-whisky-glenmorangie-18-yrs-extreme', NULL, 300000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenmorangie-18-yrs-extreme'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenmorangie Signet', 'dm-whisky-glenmorangie-signet', NULL, 700000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenmorangie-signet'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenlivet 18yrs', 'dm-whisky-glenlivet-18yrs', NULL, 320000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenlivet-18yrs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenlivet 15yrs', 'dm-whisky-glenlivet-15yrs', NULL, 280000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-glenlivet-15yrs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnny Walker Blue Label', 'dm-whisky-johnny-walker-blue-label', NULL, 860000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-johnny-walker-blue-label'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnnie Walker 18 Years', 'dm-whisky-johnnie-walker-18-years', NULL, 360000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-johnnie-walker-18-years'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan Double', 'dm-whisky-macallan-double', NULL, 468000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-macallan-double'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan Rare Cask', 'dm-whisky-macallan-rare-cask', NULL, 750000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-macallan-rare-cask'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan 12 Yrs', 'dm-whisky-macallan-12-yrs', NULL, 320000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-macallan-12-yrs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan 15 Yrs', 'dm-whisky-macallan-15-yrs', NULL, 500000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-macallan-15-yrs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan 18 Yrs', 'dm-whisky-macallan-18-yrs', NULL, 780000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-macallan-18-yrs'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Monkey Shoulder', 'dm-whisky-monkey-shoulder', NULL, 150000, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-monkey-shoulder'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smokey Monkey', 'dm-whisky-smokey-monkey', NULL, 150000, 23, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-whisky-smokey-monkey'
  )
LIMIT 1;

-- Red Wine
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chateau du Pape', 'dm-red-wine-chateau-du-pape', NULL, 100000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-chateau-du-pape'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chateau Giscours 2014', 'dm-red-wine-chateau-giscours-2014', NULL, 95500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-chateau-giscours-2014'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chateau Giscours Cazauviel 2017', 'dm-red-wine-chateau-giscours-cazauviel-2017', NULL, 90500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-chateau-giscours-cazauviel-2017'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chocolate Block 2019', 'dm-red-wine-chocolate-block-2019', NULL, 95500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-chocolate-block-2019'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clarendelle Red 2015', 'dm-red-wine-clarendelle-red-2015', NULL, 80000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-clarendelle-red-2015'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mounton Cardet', 'dm-red-wine-mounton-cardet', NULL, 95000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-mounton-cardet'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pascal Jolivet Sancerre Pinot Noir 2019', 'dm-red-wine-pascal-jolivet-sancerre-pinot-noir-2019', NULL, 110000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-pascal-jolivet-sancerre-pinot-noir-2019'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Saint Emmilion 2017', 'dm-red-wine-saint-emmilion-2017', NULL, 100000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-saint-emmilion-2017'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Georges Duboeuf Cote du Rhones', 'dm-red-wine-georges-duboeuf-cote-du-rhones', NULL, 60000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-georges-duboeuf-cote-du-rhones'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Santa Christina Maestrelle', 'dm-red-wine-santa-christina-maestrelle', NULL, 90000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-santa-christina-maestrelle'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Terrazaz Mailec 2018', 'dm-red-wine-terrazaz-mailec-2018', NULL, 80000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-terrazaz-mailec-2018'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tignanello di Anti 2017', 'dm-red-wine-tignanello-di-anti-2017', NULL, 290000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-red-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-red-wine-tignanello-di-anti-2017'
  )
LIMIT 1;

-- Rose Wine
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Diemersdal Sauvignon Blanc', 'dm-rose-wine-diemersdal-sauvignon-blanc', NULL, 80000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-diemersdal-sauvignon-blanc'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Georges Duboeuf Cote Du Rhones Sauvignon Blanc', 'dm-rose-wine-georges-duboeuf-cote-du-rhones-sauvignon-blanc', NULL, 65000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-georges-duboeuf-cote-du-rhones-sauvignon-blanc'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Henri Bourgeois Les Baronnes Sancerre', 'dm-rose-wine-henri-bourgeois-les-baronnes-sancerre', NULL, 110000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-henri-bourgeois-les-baronnes-sancerre'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cigalus Blanc 2019', 'dm-rose-wine-cigalus-blanc-2019', NULL, 200000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-cigalus-blanc-2019'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clarendelle White 2019', 'dm-rose-wine-clarendelle-white-2019', NULL, 85000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-clarendelle-white-2019'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Escodorojo', 'dm-rose-wine-escodorojo', NULL, 85000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-escodorojo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pascal Jolivet Sauvage', 'dm-rose-wine-pascal-jolivet-sauvage', NULL, 110000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-pascal-jolivet-sauvage'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pascal Jolivet Polly Fume', 'dm-rose-wine-pascal-jolivet-polly-fume', NULL, 110000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-pascal-jolivet-polly-fume'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Thomas Barton Graves Blanc', 'dm-rose-wine-thomas-barton-graves-blanc', NULL, 65000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-rose-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-rose-wine-thomas-barton-graves-blanc'
  )
LIMIT 1;

-- White Wine
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clarendelle Rose', 'dm-white-wine-clarendelle-rose', NULL, 75000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-white-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-white-wine-clarendelle-rose'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whispering Angel', 'dm-white-wine-whispering-angel', NULL, 95000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-white-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-white-wine-whispering-angel'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pascal Jolivet Sauvage', 'dm-white-wine-pascal-jolivet-sauvage-white', NULL, 110000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-white-wine'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-white-wine-pascal-jolivet-sauvage-white'
  )
LIMIT 1;

-- Beer
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Heineken', 'dm-beer-heineken', NULL, 6000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-heineken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Guinness', 'dm-beer-guinness', NULL, 6000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-beer-guinness'
  )
LIMIT 1;

-- Non Alcohol Drinks
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Water Large', 'dm-non-alcohol-water-large', NULL, 4500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-water-large'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Water Small', 'dm-non-alcohol-water-small', NULL, 2800, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-water-small'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coke', 'dm-non-alcohol-coke', NULL, 3800, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-coke'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fanta', 'dm-non-alcohol-fanta', NULL, 3800, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-fanta'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sprite', 'dm-non-alcohol-sprite', NULL, 3800, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-sprite'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ginger Ale', 'dm-non-alcohol-ginger-ale', NULL, 5500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-ginger-ale'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Perrier', 'dm-non-alcohol-perrier', NULL, 5500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-perrier'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Red Bull', 'dm-non-alcohol-red-bull', NULL, 7000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-red-bull'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Soda', 'dm-non-alcohol-soda', NULL, 3800, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-soda'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tonic', 'dm-non-alcohol-tonic', NULL, 3800, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-non-alcohol'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-non-alcohol-tonic'
  )
LIMIT 1;

-- Juice Pitcher
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cranberry Pitcher', 'dm-juice-pitcher-cranberry-pitcher', NULL, 18000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-juice-pitcher-cranberry-pitcher'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orange', 'dm-juice-pitcher-orange', NULL, 8500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-juice-pitcher-orange'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple', 'dm-juice-pitcher-apple', NULL, 8500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-juice-pitcher-apple'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple', 'dm-juice-pitcher-pineapple', NULL, 8500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-juice-pitcher-pineapple'
  )
LIMIT 1;

-- Hot Beverages
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Single Espresso', 'dm-hot-beverages-single-espresso', NULL, 4500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-beverages-single-espresso'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Double Espresso', 'dm-hot-beverages-double-espresso', NULL, 5500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-beverages-double-espresso'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tea Selection', 'dm-hot-beverages-tea-selection', NULL, 4500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-beverages-tea-selection'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cappuccino', 'dm-hot-beverages-cappuccino', NULL, 5500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-beverages-cappuccino'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Americano', 'dm-hot-beverages-americano', NULL, 5500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-beverages-americano'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cafe Late', 'dm-hot-beverages-cafe-late', NULL, 5500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-hot-beverages-cafe-late'
  )
LIMIT 1;

-- Classic Cocktails
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mojito', 'dm-classic-cocktails-mojito', NULL, 12000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-mojito'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Flavored Mojito', 'dm-classic-cocktails-flavored-mojito', NULL, 12000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-flavored-mojito'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila Sunrise', 'dm-classic-cocktails-tequila-sunrise', NULL, 12000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-tequila-sunrise'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whisky Sour', 'dm-classic-cocktails-whisky-sour', NULL, 12000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-whisky-sour'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry Daiquiri', 'dm-classic-cocktails-strawberry-daiquiri', NULL, 12000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-strawberry-daiquiri'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin Tonic', 'dm-classic-cocktails-gin-tonic', NULL, 12000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-gin-tonic'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moscow Mule', 'dm-classic-cocktails-moscow-mule', NULL, 12000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-moscow-mule'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Basilicum', 'dm-classic-cocktails-basilicum', NULL, 12000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-basilicum'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Long Island', 'dm-classic-cocktails-long-island', NULL, 12000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-long-island'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pinacolada', 'dm-classic-cocktails-pinacolada', NULL, 12000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-pinacolada'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amaretto Whiskey Sour', 'dm-classic-cocktails-amaretto-whiskey-sour', NULL, 12000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-amaretto-whiskey-sour'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cosmopolitan', 'dm-classic-cocktails-cosmopolitan', NULL, 12000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-cosmopolitan'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Manhattan', 'dm-classic-cocktails-manhattan', NULL, 12000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-manhattan'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margarita', 'dm-classic-cocktails-margarita', NULL, 12000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-margarita'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Flavored Margarita', 'dm-classic-cocktails-flavored-margarita', NULL, 12000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-flavored-margarita'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sex on the Beach', 'dm-classic-cocktails-sex-on-the-beach', NULL, 12000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-sex-on-the-beach'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Porn Star Martini', 'dm-classic-cocktails-porn-star-martini', NULL, 12000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-classic-cocktails-porn-star-martini'
  )
LIMIT 1;

-- Signature Cocktails
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Level Up', 'dm-signature-cocktails-level-up', NULL, 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-level-up'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sweet In the Middle', 'dm-signature-cocktails-sweet-in-the-middle', NULL, 15000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-sweet-in-the-middle'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smoky Opal', 'dm-signature-cocktails-smoky-opal', NULL, 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-smoky-opal'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'First Impression', 'dm-signature-cocktails-first-impression', NULL, 15000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-first-impression'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Opal Free Flow', 'dm-signature-cocktails-opal-free-flow', NULL, 15000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-opal-free-flow'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple Minded', 'dm-signature-cocktails-apple-minded', NULL, 15000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-apple-minded'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don''t Get Wet', 'dm-signature-cocktails-dont-get-wet', NULL, 15000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-signature-cocktails-dont-get-wet'
  )
LIMIT 1;

-- Shots
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila', 'dm-shots-tequila', NULL, 5900, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-tequila'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila Gold', 'dm-shots-tequila-gold', NULL, 5500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-tequila-gold'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vodka', 'dm-shots-vodka', NULL, 5000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-vodka'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin', 'dm-shots-gin', NULL, 4500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-gin'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whiskey', 'dm-shots-whiskey', NULL, 5000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-whiskey'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessey VSOP', 'dm-shots-hennessey-vsop', NULL, 14500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-hennessey-vsop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rum', 'dm-shots-rum', NULL, 5000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-rum'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jägermeister', 'dm-shots-j-germeister', NULL, 5000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-j-germeister'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Baileys', 'dm-shots-baileys', NULL, 4500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-baileys'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Campari', 'dm-shots-campari', NULL, 4500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-campari'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Aperol', 'dm-shots-aperol', NULL, 4500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shots-aperol'
  )
LIMIT 1;

-- Special Shots
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Brain Hemorrhage', 'dm-special-shots-brain-hemorrhage', NULL, 5500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-brain-hemorrhage'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Condom Shot', 'dm-special-shots-condom-shot', NULL, 5500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-condom-shot'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Death of Jellyfish', 'dm-special-shots-death-of-jellyfish', NULL, 5500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-death-of-jellyfish'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Doo Doo', 'dm-special-shots-doo-doo', NULL, 5500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-doo-doo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Liquid Cocaine', 'dm-special-shots-liquid-cocaine', NULL, 5500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-liquid-cocaine'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Absent Without Leave', 'dm-special-shots-absent-without-leave', NULL, 5500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-absent-without-leave'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fire Shots', 'dm-special-shots-fire-shots', NULL, 5500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-fire-shots'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome Shot', 'dm-special-shots-vendome-shot', NULL, 5500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-vendome-shot'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blowjob', 'dm-special-shots-blowjob', NULL, 5600, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-blowjob'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome Tower Fire Show', 'dm-special-shots-vendome-tower-fire-show', NULL, 25000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-special-shots-vendome-tower-fire-show'
  )
LIMIT 1;

-- Shisha Menu
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Iced Gum', 'dm-shisha-menu-iced-gum', NULL, 30000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-iced-gum'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Magic Love', 'dm-shisha-menu-magic-love', NULL, 30000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-magic-love'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Love 66', 'dm-shisha-menu-love-66', NULL, 36000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-love-66'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry', 'dm-shisha-menu-strawberry', NULL, 30000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-strawberry'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry and Mint', 'dm-shisha-menu-strawberry-and-mint', NULL, 30000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-strawberry-and-mint'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mixed Fruit', 'dm-shisha-menu-mixed-fruit', NULL, 30000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-mixed-fruit'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gum and Mint', 'dm-shisha-menu-gum-and-mint', NULL, 30000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-gum-and-mint'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gum', 'dm-shisha-menu-gum', NULL, 30000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-gum'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lemon and Mint', 'dm-shisha-menu-lemon-and-mint', NULL, 30000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-lemon-and-mint'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mint and Cream', 'dm-shisha-menu-mint-and-cream', NULL, 30000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-mint-and-cream'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grape and Mint', 'dm-shisha-menu-grape-and-mint', NULL, 30000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-grape-and-mint'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grape', 'dm-shisha-menu-grape', NULL, 30000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-grape'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Two Apple', 'dm-shisha-menu-two-apple', NULL, 30000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-two-apple'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mint', 'dm-shisha-menu-mint', NULL, 30000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-mint'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peach', 'dm-shisha-menu-peach', NULL, 30000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-peach'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blueberry', 'dm-shisha-menu-blueberry', NULL, 30000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-blueberry'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blueberry and Mint', 'dm-shisha-menu-blueberry-and-mint', NULL, 30000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-blueberry-and-mint'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mango', 'dm-shisha-menu-mango', NULL, 30000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-mango'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon', 'dm-shisha-menu-watermelon', NULL, 30000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-watermelon'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon and Mint', 'dm-shisha-menu-watermelon-and-mint', NULL, 30000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-watermelon-and-mint'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lady Killer', 'dm-shisha-menu-lady-killer', NULL, 36000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-lady-killer'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Two Apple (Duplicate listing)', 'dm-shisha-menu-two-apple-2', NULL, 30000, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-two-apple-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple', 'dm-shisha-menu-apple-shisha', NULL, 25000, 23, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-apple-shisha'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple Fruit', 'dm-shisha-menu-pineapple-fruit', NULL, 40000, 24, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-pineapple-fruit'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple Fruit', 'dm-shisha-menu-apple-fruit', NULL, 40000, 25, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-apple-fruit'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orange Fruit', 'dm-shisha-menu-orange-fruit', NULL, 40000, 26, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-shisha-menu'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-shisha-menu-orange-fruit'
  )
LIMIT 1;

-- Karaoke Drink Menu
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'First Impression', 'dm-karaoke-drinks-first-impression', NULL, 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-first-impression'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Opal Free Flow', 'dm-karaoke-drinks-opal-free-flow', 'Vodka, lemon juice, coconut syrup, strawberry syrup, watermelon chunks. Garnish: watermelon wedge.', 15000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-opal-free-flow'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple Minded', 'dm-karaoke-drinks-apple-minded', 'Whiskey, simple syrup, apple juice. Garnish: dehydrated orange and apple.', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-apple-minded'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Level Up', 'dm-karaoke-drinks-level-up', 'Cognac, lemon juice, simple syrup, topped with ginger beer. Garnish: lemon wedge and cherry.', 15000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-level-up'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don''t Get Wet', 'dm-karaoke-drinks-dont-get-wet', 'Gin, blue curaçao, grenadine syrup, orange juice. Garnish: dehydrated orange and cherry.', 15000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-dont-get-wet'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mojito (Virgin)', 'dm-karaoke-drinks-mojito-virgin', NULL, 12000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-mojito-virgin'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chapman', 'dm-karaoke-drinks-chapman', NULL, 12000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-chapman'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mint It Up', 'dm-karaoke-drinks-mint-it-up', NULL, 12000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'dm-karaoke-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'dm-karaoke-drinks-mint-it-up'
  )
LIMIT 1;

-- Done. @rid should be 19 for The Opal (OPAL CAFE MENU) on production dump.
