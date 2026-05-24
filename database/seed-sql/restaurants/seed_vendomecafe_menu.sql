-- Seed: Main Menu + Afro & Continental + Tacos Bar — Vendome Cafe
-- Regenerate: node database/_scrape_vendome.mjs && node database/build_vendomecafe_seed.mjs
-- Restaurant: admin@vendomecafe.our-menu.online | Slug: vendome-cafe-s-menu
-- Source: https://vendomecafe.our-menu.online/
-- Run AFTER migration.sql. Safe to re-run (NOT EXISTS guards).

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

SET @rid = (
  SELECT id FROM restaurants
  WHERE slug = 'vendome-cafe-s-menu'
     OR email = 'admin@vendomecafe.our-menu.online'
     OR manager_email = 'admin@vendomecafe.our-menu.online'
  LIMIT 1
);

-- ----- SECTION: Main Menu (main-menu) -----
SET @sid_mm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'main-menu' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'Main Menu', 'main-menu', 1, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_mm IS NULL;
SET @sid_mm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'main-menu' LIMIT 1);

-- ----- SECTION: Afro & Continental Menu (food-menu) -----
SET @sid_fm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'food-menu' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'Afro & Continental Menu', 'food-menu', 2, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_fm IS NULL;
SET @sid_fm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'food-menu' LIMIT 1);

-- ----- SECTION: Tacos Bar Menu (drink-menu) -----
SET @sid_dm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'drink-menu' LIMIT 1);
INSERT INTO sections (restaurant_id, name, slug, display_order, is_active)
SELECT @rid, 'Tacos Bar Menu', 'drink-menu', 3, 1 FROM DUAL
WHERE @rid IS NOT NULL AND @sid_dm IS NULL;
SET @sid_dm = (SELECT id FROM sections WHERE restaurant_id = @rid AND slug = 'drink-menu' LIMIT 1);

-- ----- main-menu categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'SOUPS', 'mm-soups', 1, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-soups')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'APPETIZERS', 'mm-appetizers', 2, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'SALADS', 'mm-salads', 3, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-salads')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'PASTA', 'mm-pasta', 4, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-pasta')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'FISH AND SEAFOOD', 'mm-fish-and-seafood', 5, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'MEAT', 'mm-meat', 6, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'CHICKEN', 'mm-chicken', 7, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'BURGER', 'mm-burger', 8, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-burger')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'PIZZA', 'mm-pizza', 9, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_mm, 'DESSERT', 'mm-dessert', 10, 1 FROM DUAL
WHERE @sid_mm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'mm-dessert')
LIMIT 1;

-- SOUPS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Thai Coconut Seafood Soup', 'mm-soups-thai-coconut-seafood-soup', 'Red curry creamy broth, calamari, octopus, shrimp', 16400, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-soups-thai-coconut-seafood-soup'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Meat On Bread', 'mm-soups-meat-on-bread-2', 'Minced marinated lamb meat, mixed cheese, roasted pine nuts, crispy caramelized onion and pomegranate sauce.', 12000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-soups-meat-on-bread-2'
  )
LIMIT 1;

-- APPETIZERS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jumbo Shrimps Cocktail', 'mm-appetizers-jumbo-shrimps-cocktail', 'Boiled jumbo-size shrimp, crispy romaine lettuce, and homemade cocktail sauce.', 19000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-jumbo-shrimps-cocktail'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fresh Vietnamese Summer Roll', 'mm-appetizers-fresh-vietnamese-summer-roll-2', 'Rolled in rice paper, shrimp, rice vermicelli, carrot, cucumber, mint, basil, and lettuce, served with homemade Vietnamese dip.', 16000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-fresh-vietnamese-summer-roll-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Imperial Fried Spring Rolls', 'mm-appetizers-imperial-fried-spring-rolls-3', 'Rolled in rice paper, deep fried, pork minced meat, rice vermicelli, carrot, and tacky mushrooms, served with homemade Asian dip.', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-imperial-fried-spring-rolls-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Tender Italian Style', 'mm-appetizers-chicken-tender-italian-style-4', 'Breaded tenderloin chicken deep fried, parmesan cheese, mixed Italian herbs, served with honey mustard dip.', 12500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-chicken-tender-italian-style-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coconut Crispy Calamari', 'mm-appetizers-coconut-crispy-calamari-5', 'Deep fried calamari marinated in coconut milk served with tartar and roasted bell pepper carrot dip.', 14000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-coconut-crispy-calamari-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crispy Chicken Wings', 'mm-appetizers-crispy-chicken-wings-6', 'Deep-fried marinated chicken wings, served with BBQ and sweet chili dip.', 11500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-crispy-chicken-wings-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Flame Kissed Octopus', 'mm-appetizers-flame-kissed-octopus-7', 'Marinated seared octopus, caramelized onions, pomegranate sauce.', 15500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-flame-kissed-octopus-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried Butterfly Shrimps', 'mm-appetizers-fried-butterfly-shrimps-8', 'Breaded deep-fried shrimp served with homemade cocktail dip.', 15500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-fried-butterfly-shrimps-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Appetizer Platter', 'mm-appetizers-appetizer-platter-9', '(Serve up to 4) Chicken tender, fried calamari, chicken wings, wedges potato, fried rolls, fried shrimp, served with tartar, cocktail, sweet chili, Thai dips.', 32500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-appetizer-platter-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dynamite Chicken', 'mm-appetizers-dynamite-chicken-10', 'Crispy, golden brown-fried chicken served with the dynamite sauce.', 11500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-dynamite-chicken-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dynamite Shrimp', 'mm-appetizers-dynamite-shrimp-11', 'Crispy, fried shrimp coated in a spicy mayonnaise dressing.', 12500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-dynamite-shrimp-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian Platter', 'mm-appetizers-nigerian-platter-12', 'Spicy gizzards & snails, chicken suya, yam fingers & plantain.', 20800, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-nigerian-platter-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spicy Chicken Wings', 'mm-appetizers-spicy-chicken-wings-13', 'Deep-fried chicken wings toasted in homemade chili pepper sauce or in BBQ sauce', 15500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-appetizers-spicy-chicken-wings-13'
  )
LIMIT 1;

-- SALADS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic Niçoise', 'mm-salads-classic-ni-oise', 'Crispy lettuce, tomato, boiled potato, boiled egg, green beans, white tuna, black olives, and anchovies, served with balsamic vinegar sauce.', 15500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-salads-classic-ni-oise'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Exotic Seafood', 'mm-salads-exotic-seafood-2', 'Crispy lettuce, tomato, fresh pineapple, avocado, sweet corn, carrot, calamari, crab, shrimp served with sweet lemon sauce.', 20500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-salads-exotic-seafood-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tropical Chicken', 'mm-salads-tropical-chicken-3', 'Crispy lettuce, tomato, grilled chicken, parmesan cheese, marinated croutons, grilled chicken breast served with tropical homemade sauce.', 16000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-salads-tropical-chicken-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Smoked Salmon Salad', 'mm-salads-smoked-salmon-salad-4', 'Crispy lettuce, smoked salmon, avocado, capers, red onions, served with lemon oil sauce.', 22000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-salads-smoked-salmon-salad-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Add Grilled Chicken Breast To Your Salad', 'mm-salads-add-grilled-chicken-breast-to-your-salad-5', NULL, 5200, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-salads-add-grilled-chicken-breast-to-your-salad-5'
  )
LIMIT 1;

-- PASTA
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome Shrimp Tagliatelle', 'mm-pasta-vendome-shrimp-tagliatelle', 'Cream homemade sauce, shrimp.', 15500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pasta-vendome-shrimp-tagliatelle'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spaghetti Mare', 'mm-pasta-spaghetti-mare-2', 'Marinated shrimp and calamari, white or red sauce of your choice.', 16000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pasta-spaghetti-mare-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Carbonara', 'mm-pasta-carbonara-3', 'Linguini pasta, creamy sauce, your choice of bacon or smoked turkey', 14500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pasta-carbonara-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Braised Rigatoni Ragout', 'mm-pasta-braised-rigatoni-ragout-4', 'Tozo beef with tomato red wine sauce.', 13500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pasta-braised-rigatoni-ragout-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Alfredo', 'mm-pasta-chicken-alfredo-5', 'Fettuccini, creamy sauce, chicken breast, mixed mushrooms, parmesan cheese', 15500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pasta'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pasta-chicken-alfredo-5'
  )
LIMIT 1;

-- FISH AND SEAFOOD
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seared Salmon', 'mm-fish-and-seafood-seared-salmon', 'Seared salmon filet, served with sautéed vegetables and creamy homemade sauce.', 33000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-seared-salmon'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Marinated Tiger Prawns', 'mm-fish-and-seafood-grilled-marinated-tiger-prawns-2', 'Grilled marinated tiger prawns, grilled vegetables, and baked potato served with tartar and cocktail sauce.', 33500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-grilled-marinated-tiger-prawns-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fish And Chips', 'mm-fish-and-seafood-fish-and-chips-3', 'Buttered deep-fried fish filet and French fries served with tartar sauce.', 16000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-fish-and-chips-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coconut Shrimps', 'mm-fish-and-seafood-coconut-shrimps-4', 'Your choice of grilled or breaded deep-fried shrimp, steamed rice, or grilled pineapple served with coconut curry sauce', 20500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-coconut-shrimps-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Whole Fish African Style', 'mm-fish-and-seafood-grilled-whole-fish-african-style-5', 'Grilled whole fish marinated in African sauce, sauteed vegetables served with fried yam and plantain.', 13500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-grilled-whole-fish-african-style-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Traditional Prawns', 'mm-fish-and-seafood-traditional-prawns-6', 'Marinated grilled prawns, pineapple fried rice, mushrooms, green pies, and sweet corn, served with coriander in tomato sauce.', 19500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-traditional-prawns-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Platter', 'mm-fish-and-seafood-seafood-platter-7', 'Stir-fried calamari, coconut shrimp, Deep-fried white fish fillet, Grilled octopus, served with cocktail sauce &Tartar sauce.', 25000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-seafood-platter-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Prawns Suya', 'mm-fish-and-seafood-prawns-suya-8', 'Marinated Grilled prawns, in suya spice served with French fries', 25000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-fish-and-seafood'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-fish-and-seafood-prawns-suya-8'
  )
LIMIT 1;

-- MEAT
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled T-Bone Steak', 'mm-meat-grilled-t-bone-steak', '350 gr of grilled T-bone steak, grilled vegetables, wedges potatoes served with mushroom sauce.', 30500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-meat-grilled-t-bone-steak'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lamb Shank', 'mm-meat-lamb-shank-2', 'South African lamb chunk slow-cooked with herb oil, mashed potato, and sauteed vegetables served with spicy homemade sauce.', 25500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-meat-lamb-shank-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mongolian Beef', 'mm-meat-mongolian-beef-3', 'Sliced imported beef filet, sauteed in a homemade Mongolian sauce served with Singaporean fried rice.', 30500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-meat-mongolian-beef-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chateau Brilliant', 'mm-meat-chateau-brilliant-4', '250g of Grilled imported beef filet, sauteed vegetables, French fries served with your choice of peppercorn or mushroom sauce', 30500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-meat-chateau-brilliant-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rib Eye Steak', 'mm-meat-rib-eye-steak-5', '250g of Grilled imported beef filet, sauteed vegetables, French fries served with your choice of peppercorn or mushroom sauce', 28500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-meat-rib-eye-steak-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Tozo', 'mm-meat-grilled-tozo-6', 'Marinated smoked beef cut served with leeks garlic creamy sauce, served with mashed potato', 17000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-meat-grilled-tozo-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Garlic Butter Lamb Chops', 'mm-meat-garlic-butter-lamb-chops-7', '4 pieces grilled imported lamb chops topped with garlic herbs sauce, grilled vegetables, wedges potato.', 32000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-meat'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-meat-garlic-butter-lamb-chops-7'
  )
LIMIT 1;

-- CHICKEN
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Parmesan', 'mm-chicken-chicken-parmesan', 'Deep-fried breaded chicken breast topped with tomato sauce and parmesan cheese served with tomato basil spaghetti.', 15000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-chicken-parmesan'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Chicken African Style', 'mm-chicken-grilled-chicken-african-style-2', 'African style marinated half chicken, fried plantain rice, spicy tomato sauce African way.', 16500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-grilled-chicken-african-style-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome Touch Chicken', 'mm-chicken-vendome-touch-chicken-3', 'Marinated grilled chicken breast, and grilled vegetables, served with Vendome touch rice.', 15000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-vendome-touch-chicken-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Braised Chicken Lap', 'mm-chicken-braised-chicken-lap-4', 'Season grilled chicken lap, grilled vegetable, potato wedges, served with garlic mayo sauce & spicy tomato sauce.', 14500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-braised-chicken-lap-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Creamy Chicken Supreme', 'mm-chicken-creamy-chicken-supreme-5', 'Grilled chicken breast toasted in a mushroom creamy sauce served with steamed rice.', 18500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-creamy-chicken-supreme-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Chicken Wings', 'mm-chicken-grilled-chicken-wings-6', 'Marinated grilled chicken wings served with French fries and ketchup BBQ sauce.', 13900, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-grilled-chicken-wings-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Oriental Grill', 'mm-chicken-oriental-grill-7', 'Grilled chicken skewers served with Soft tortilla bread, French fries or fried yam fingers, garlic mayo sauce.', 16500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-oriental-grill-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Suya', 'mm-chicken-chicken-suya-8', 'Marinated chicken breast, in suya spice', 16500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-chicken-suya-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Imported Beef Filet', 'mm-chicken-imported-beef-filet-9', NULL, 36500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-imported-beef-filet-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Breast', 'mm-chicken-chicken-breast-10', 'Stir fry Mexican style, salsa, guacamole, sour cream, and mixed cheese served with hot tortilla bread.', 25100, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-chicken-breast-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'South American Style Grilled Platter', 'mm-chicken-south-american-style-grilled-platter-11', '(Serve up to 4) 250 gr rib eye, 200 gr lamb chops, 280 gr duck breast, 230 gr chicken breast, and grilled vegetables served with chimichurri, teriyaki, and BBQ sauces.', 73900, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-south-american-style-grilled-platter-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'South American Style Grilled Platter', 'mm-chicken-south-american-style-grilled-platter-12', '(Serve up to 4) 250 gr rib eye, 200 gr lamb chops, 280 gr duck breast, 230 gr chicken breast, and grilled vegetables served with chimichurri, teriyaki, and BBQ sauces.', 25100, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-south-american-style-grilled-platter-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '(Serve up to 2)', 'mm-chicken-serve-up-to-2-13', NULL, 16200, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-serve-up-to-2-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Serve up to 4)', 'mm-chicken-serve-up-to-4-14', 'Grilled whole fish marinated in African sauce, sauteed vegetables served with fried yam and plantain.', 31700, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-chicken'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-chicken-serve-up-to-4-14'
  )
LIMIT 1;

-- BURGER
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mini Slider', 'mm-burger-mini-slider', 'Three flavors of mini burgers, beef, chicken, fried mozzarella', 15500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-burger'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-burger-mini-slider'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome Beef Burger', 'mm-burger-vendome-beef-burger-2', '170 gr beef patty, cheddar cheese, mushrooms, caramelized onions, crispy bacon, lettuce, tomato, homemade sauce, BBQ sauce served with French fries and a side salad.', 16500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-burger'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-burger-vendome-beef-burger-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cajun Chicken Burger', 'mm-burger-cajun-chicken-burger-3', 'Choice of grilled or fried chicken breast, lettuce, tomato, onion, jalapeño, cheddar cheese, homemade sauce, or BBQ sauce, served with French fries and a side salad.', 16500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-burger'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-burger-cajun-chicken-burger-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fish Burger', 'mm-burger-fish-burger-4', 'Soft bun, crispy fried white fish fillet, cheddar cheese, tartar sauce, sliced tomato, crispy lettuce.', 16500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-burger'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-burger-fish-burger-4'
  )
LIMIT 1;

-- PIZZA
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margarita', 'mm-pizza-margarita', 'Homemade tomato sauce topped with mixed cheese.', 10500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-margarita'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Pizza', 'mm-pizza-chicken-pizza-2', 'Homemade tomato sauce topped with mixed mushrooms and cheese, grilled chicken breast, and truffle oil.', 14500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-chicken-pizza-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veggie Pizza', 'mm-pizza-veggie-pizza-3', 'Homemade tomato sauce, eggplant, green pepper, onion, tomato, and black olives topped with mixed cheese.', 14000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-veggie-pizza-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hawaiian Seafood', 'mm-pizza-hawaiian-seafood-4', 'BBQ sauce, calamari, shrimp,0ctupus, fresh pineapple topped with mixed cheese', 17500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-hawaiian-seafood-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pepperoni Pizza', 'mm-pizza-pepperoni-pizza-5', 'Homemade tomato sauce, beef pepperoni topped with mixed cheese', 15000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-pepperoni-pizza-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Or Sauteed Vegetables', 'mm-pizza-grilled-or-sauteed-vegetables-6', NULL, 4500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-grilled-or-sauteed-vegetables-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mashed Potatoes', 'mm-pizza-mashed-potatoes-7', NULL, 5500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-mashed-potatoes-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'French Fries', 'mm-pizza-french-fries-8', NULL, 5000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-french-fries-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Wedges Potatoes', 'mm-pizza-wedges-potatoes-9', NULL, 4000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-wedges-potatoes-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Singaporean Fried Rice', 'mm-pizza-singaporean-fried-rice-10', NULL, 5500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-singaporean-fried-rice-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Steamed Rice', 'mm-pizza-steamed-rice-11', NULL, 5000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-steamed-rice-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spanish Rice', 'mm-pizza-spanish-rice-12', NULL, 5500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-spanish-rice-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lyonnaise Potatoes', 'mm-pizza-lyonnaise-potatoes-13', NULL, 8000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-lyonnaise-potatoes-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried Yam', 'mm-pizza-fried-yam-14', NULL, 4500, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-fried-yam-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried Plantain', 'mm-pizza-fried-plantain-15', NULL, 4500, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-pizza'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-pizza-fried-plantain-15'
  )
LIMIT 1;

-- DESSERT
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Exotic Fruit Tart', 'mm-dessert-exotic-fruit-tart', NULL, 9000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-dessert-exotic-fruit-tart'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fondant Chocolate', 'mm-dessert-fondant-chocolate-2', 'Served with vanilla ice cream', 10000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-dessert-fondant-chocolate-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ginger Cake', 'mm-dessert-ginger-cake-3', 'Served with vanilla ice cream and apple caramel.', 8000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-dessert-ginger-cake-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ice Cream Scoop', 'mm-dessert-ice-cream-scoop-4', 'Chocolate vanilla and strawberry.', 8000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'mm-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'mm-dessert-ice-cream-scoop-4'
  )
LIMIT 1;

-- ----- food-menu categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'BREAKFAST', 'ac-breakfast', 1, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-breakfast')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SOUPS', 'ac-soups', 2, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-soups')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'BEEF DISHES', 'ac-beef-dishes', 3, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-beef-dishes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SEAFOOD DISHES', 'ac-seafood-dishes', 4, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-seafood-dishes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'CHICKEN DISHES', 'ac-chicken-dishes', 5, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-chicken-dishes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SANDWICHES', 'ac-sandwiches', 6, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-sandwiches')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SIDE ORDERS', 'ac-side-orders', 7, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SWALLOWS', 'ac-swallows', 8, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-swallows')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'PANCAKES', 'ac-pancakes', 9, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-pancakes')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'WAFFLES', 'ac-waffles', 10, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-waffles')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'NIGERIAN PASTRIES', 'ac-nigerian-pastries', 11, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-nigerian-pastries')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'COLD SANDWICHES', 'ac-cold-sandwiches', 12, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-cold-sandwiches')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'PANINI', 'ac-panini', 13, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-panini')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'HOT SANDWICHES', 'ac-hot-sandwiches', 14, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'APPETIZERS', 'ac-appetizers', 15, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'SALADS', 'ac-salads', 16, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_fm, 'DESSERT', 'ac-dessert', 17, 1 FROM DUAL
WHERE @sid_fm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'ac-dessert')
LIMIT 1;

-- BREAKFAST
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'English breakfast', 'ac-breakfast-english-breakfast', 'Eggs (scrambled, fried, boiled, or omelette), sausage, bacon, hash brown potato, Baked beans served with sliced bread and butter.', 11500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-breakfast-english-breakfast'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian breakfast', 'ac-breakfast-nigerian-breakfast-2', 'Egg stew, sausage, boiled yam, and beans served with slice bread and butter.', 10500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-breakfast-nigerian-breakfast-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Healthy Yogurt Bowl', 'ac-breakfast-healthy-yogurt-bowl-3', 'Fresh yogurt, an assortment of berries fruit, banana, and dried almonds served with honey.', 10500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-breakfast-healthy-yogurt-bowl-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Maple syrup', 'ac-breakfast-maple-syrup-4', '3 buttermilk pancakes, maple syrup, fruits', 7000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-breakfast-maple-syrup-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Waffle', 'ac-breakfast-waffle-5', 'Homemade crispy waffles, and assorted berries served with a scoop of vanilla ice cream and chocolate sauce.', 7000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-breakfast-waffle-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peppered Soups', 'ac-breakfast-peppered-soups-6', 'From West Africa, chili peppers and calabash nutmeg have a spicy watery texture. A choice between chicken, goat meat fish, and Oxtail.', 7500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-breakfast'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-breakfast-peppered-soups-6'
  )
LIMIT 1;

-- SOUPS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Egusi', 'ac-soups-chicken-egusi', 'Creamy Egusi soup with egusi seeds, dried fish, smoked fish, kpomo, crayfish, ugu leaves served with roasted chicken.', 8000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-soups-chicken-egusi'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Seafood Okro', 'ac-soups-seafood-okro-2', 'Lady finger Okro, crayfish, prawns, squid, and fish with your choice of swallow.', 13500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-soups-seafood-okro-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beef Efo riro', 'ac-soups-beef-efo-riro-3', 'Assortment of Nigerian vegetables, crayfish, dry fish, stock fish, and kpomo served with beef meat and your choice of swallow or steamed rice.', 12000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-soups-beef-efo-riro-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'THE SHAKER', 'ac-soups-the-shaker-4', '25pcs chicken poppers with suya spice', 8500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-soups-the-shaker-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'POP STARS', 'ac-soups-pop-stars-5', '30pcs smothered hot chicken poppers, seasoned wedges & ketchup', 9000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-soups'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-soups-pop-stars-5'
  )
LIMIT 1;

-- BEEF DISHES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Groundnut stew with beef', 'ac-beef-dishes-groundnut-stew-with-beef', 'Grilled chicken breast in African homemade spicy peanut stew served with steamed rice', 9000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-beef-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-beef-dishes-groundnut-stew-with-beef'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian Lamb chops', 'ac-beef-dishes-nigerian-lamb-chops-2', 'Spicy grilled marinated local lamb chops served with fried plantain and yam and grilled vegetables.', 16000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-beef-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-beef-dishes-nigerian-lamb-chops-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ofada rice with spicy Ofada sauce', 'ac-beef-dishes-ofada-rice-with-spicy-ofada-sauce-3', 'Shaki, kpomo, black dry fish, boiled egg served with African ofada rice, and fried plantain.', 10500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-beef-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-beef-dishes-ofada-rice-with-spicy-ofada-sauce-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'African Goat Stew', 'ac-beef-dishes-african-goat-stew-4', 'Nigerian goat meat cooked till perfection sautéed in an African spicy tomato stew served with steamed rice and fried plantain.', 10500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-beef-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-beef-dishes-african-goat-stew-4'
  )
LIMIT 1;

-- SEAFOOD DISHES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Poisson Braise', 'ac-seafood-dishes-poisson-braise', '(African grilled fish) Grilled fresh whole Tilapia fish marinated in our special homemade sauce African style served with fried plantain, yam, and sweet potato.', 19500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-seafood-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-seafood-dishes-poisson-braise'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'African Grilled Tiger prawns', 'ac-seafood-dishes-african-grilled-tiger-prawns-2', 'Flame-grilled Tiger prawns, marinated in spicy homemade pepper sauce served with coconut rice and yam chips.', 19500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-seafood-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-seafood-dishes-african-grilled-tiger-prawns-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spicy fish African way', 'ac-seafood-dishes-spicy-fish-african-way-3', 'Fried or grilled fresh fish toasted in spicy tomato sauce served over Jolof rice, moi-moi, and fried plantain.', 8000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-seafood-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-seafood-dishes-spicy-fish-african-way-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jolof spaghetti with shrimp', 'ac-seafood-dishes-jolof-spaghetti-with-shrimp-4', 'Spaghetti pasta toasted in jolof spicy sauce and sautéed shrimp.', 8000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-seafood-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-seafood-dishes-jolof-spaghetti-with-shrimp-4'
  )
LIMIT 1;

-- CHICKEN DISHES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spicy Noodles with Chicken', 'ac-chicken-dishes-spicy-noodles-with-chicken', 'Stir-fried noodles with vegetables served with roasted chicken legs', 6500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-chicken-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-chicken-dishes-spicy-noodles-with-chicken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Yassa', 'ac-chicken-dishes-chicken-yassa-2', 'Chicken legs, onion, and homemade sauce served with steamed rice.', 7500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-chicken-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-chicken-dishes-chicken-yassa-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spicy Grilled whole chicken', 'ac-chicken-dishes-spicy-grilled-whole-chicken-3', 'Grilled spicy marinated whole chicken served with Jolof rice, Nigerian fried rice, fried plantain, moi-moi, and coleslaw salad.', 18500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-chicken-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-chicken-dishes-spicy-grilled-whole-chicken-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Turkey wings stew', 'ac-chicken-dishes-turkey-wings-stew-4', 'Fried Turkey wings toasted with homemade spicy tomato stew served with Jolof rice, fried plantain, and moi-moi.', 9500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-chicken-dishes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-chicken-dishes-turkey-wings-stew-4'
  )
LIMIT 1;

-- SANDWICHES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'African Burger', 'ac-sandwiches-african-burger', 'Burger bun, spicy meat patty, mayo chili sauce, tomato, onion, coleslaw served with French fries and coleslaw salad.', 8000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-sandwiches-african-burger'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome club', 'ac-sandwiches-vendome-club-2', 'Toasted slice American bread, choice of ham or turkey, Swiss cheese, chicken mayo mix, crispy bacon, boiled egg served with coleslaw and French fries', 11000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-sandwiches-vendome-club-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Suya sandwich', 'ac-sandwiches-suya-sandwich-3', 'White soft bread, beef or chicken suya slice, tomato, onion, suya powder, mayo chili sauce served with coleslaw and French fries.', 7500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-sandwiches-suya-sandwich-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spicy steak sandwich', 'ac-sandwiches-spicy-steak-sandwich-4', 'White soft bread, sautéed beef filet, green pepper, onion, mayo sauce, mixed cheese, served with French fries.', 8000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-sandwiches-spicy-steak-sandwich-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chili Hot Dog', 'ac-sandwiches-chili-hot-dog-5', 'Soft bread, chicken frankfurter, chili con carne, jalapeno topped with cheddar cheese', 9800, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-sandwiches-chili-hot-dog-5'
  )
LIMIT 1;

-- SIDE ORDERS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jolof rice', 'ac-side-orders-jolof-rice', NULL, 4000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-jolof-rice'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coconut rice', 'ac-side-orders-coconut-rice-2', NULL, 4000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-coconut-rice-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian fried rice', 'ac-side-orders-nigerian-fried-rice-3', NULL, 4000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-nigerian-fried-rice-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Steamed rice', 'ac-side-orders-steamed-rice-4', NULL, 4000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-steamed-rice-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried yam', 'ac-side-orders-fried-yam-5', NULL, 4000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-fried-yam-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried plantain', 'ac-side-orders-fried-plantain-6', NULL, 4000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-fried-plantain-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moi-Moi', 'ac-side-orders-moi-moi-7', NULL, 4000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-moi-moi-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'French fries', 'ac-side-orders-french-fries-8', NULL, 4000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-french-fries-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coleslaw salad', 'ac-side-orders-coleslaw-salad-9', NULL, 4000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-coleslaw-salad-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried rice', 'ac-side-orders-fried-rice-10', NULL, 4000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-fried-rice-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mashed potato', 'ac-side-orders-mashed-potato-11', NULL, 4000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-mashed-potato-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sautéed potato', 'ac-side-orders-saut-ed-potato-12', NULL, 4000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-side-orders'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-side-orders-saut-ed-potato-12'
  )
LIMIT 1;

-- SWALLOWS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Eba', 'ac-swallows-eba', NULL, 800, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-swallows'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-swallows-eba'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pounded yam', 'ac-swallows-pounded-yam-2', NULL, 900, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-swallows'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-swallows-pounded-yam-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Semolina', 'ac-swallows-semolina-3', NULL, 800, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-swallows'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-swallows-semolina-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Wheat', 'ac-swallows-wheat-4', NULL, 800, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-swallows'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-swallows-wheat-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'EGGS', 'ac-swallows-eggs-5', 'Eggs are served with fresh vegetables bread and butter', 5500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-swallows'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-swallows-eggs-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic Benedict', 'ac-swallows-classic-benedict-6', 'Seved with hollandaise sauce Just omelets Sunnyside Scrambled', 1500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-swallows'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-swallows-classic-benedict-6'
  )
LIMIT 1;

-- PANCAKES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mable syrup', 'ac-pancakes-mable-syrup', '3 buttermilk pancakes, maple syrup, fruits', 6500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-pancakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-pancakes-mable-syrup'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chocolate banana', 'ac-pancakes-chocolate-banana-2', '3 buttermilk pancakes, Nutella chocolate spread, banana, roasted hazelnuts', 6500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-pancakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-pancakes-chocolate-banana-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Breakfast sandwich', 'ac-pancakes-breakfast-sandwich-3', '3 buttermilk pancake, sausage, cheddar cheese, bacon, scrambled eggs', 7000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-pancakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-pancakes-breakfast-sandwich-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Oreo cheesecake milkshake', 'ac-pancakes-oreo-cheesecake-milkshake-4', NULL, 9500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-pancakes'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-pancakes-oreo-cheesecake-milkshake-4'
  )
LIMIT 1;

-- WAFFLES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Plain', 'ac-waffles-plain', 'Crispy waffle dusted with icing sugar', 3900, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-waffles'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-waffles-plain'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Berries and vanilla ice cream', 'ac-waffles-berries-and-vanilla-ice-cream-2', 'Homemade crispy waffles, and assorted berries served with a scoop of vanilla ice cream and chocolate sauce', 7000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-waffles'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-waffles-berries-and-vanilla-ice-cream-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chocolate banana', 'ac-waffles-chocolate-banana-3', 'Homemade crispy waffle, banana, Nutella chocolate, wept cream', 7000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-waffles'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-waffles-chocolate-banana-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken and waffle sandwich', 'ac-waffles-chicken-and-waffle-sandwich-4', 'Homemade crispy waffles topped with crispy fried chicken breast served with our secret sauce', 7500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-waffles'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-waffles-chicken-and-waffle-sandwich-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Waffle and egg', 'ac-waffles-waffle-and-egg-5', 'Homemade crispy waffle topped with scrambled eggs', 5500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-waffles'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-waffles-waffle-and-egg-5'
  )
LIMIT 1;

-- NIGERIAN PASTRIES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Meat pie', 'ac-nigerian-pastries-meat-pie', NULL, 2500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-nigerian-pastries'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-nigerian-pastries-meat-pie'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken pie', 'ac-nigerian-pastries-chicken-pie-2', NULL, 2500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-nigerian-pastries'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-nigerian-pastries-chicken-pie-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sausage rolls', 'ac-nigerian-pastries-sausage-rolls-3', NULL, 2500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-nigerian-pastries'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-nigerian-pastries-sausage-rolls-3'
  )
LIMIT 1;

-- COLD SANDWICHES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cold chicken and mayo', 'ac-cold-sandwiches-cold-chicken-and-mayo', 'Soft white bread, chicken mayo mix, tomato, crispy lettuce, homemade pickles', 6000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-cold-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-cold-sandwiches-cold-chicken-and-mayo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tuna salad', 'ac-cold-sandwiches-tuna-salad-2', 'Soft white bread, tuna mix, sweet corn, crispy lettuce, tomato, homemade pickles', 7500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-cold-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-cold-sandwiches-tuna-salad-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sausage rolls', 'ac-cold-sandwiches-sausage-rolls-3', NULL, 2500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-cold-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-cold-sandwiches-sausage-rolls-3'
  )
LIMIT 1;

-- PANINI
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ham and Swiss cheese', 'ac-panini-ham-and-swiss-cheese', 'White soft bread, butter, ham slice, Swiss cheese', 8000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-panini'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-panini-ham-and-swiss-cheese'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Turkey bacon and Swiss cheese', 'ac-panini-turkey-bacon-and-swiss-cheese-2', 'White soft bread, turkey slice, Swiss cheese, mayo sauce, tomato, crispy lettuce, homemade pickles', 8000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-panini'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-panini-turkey-bacon-and-swiss-cheese-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled chicken mozzarella', 'ac-panini-grilled-chicken-mozzarella-3', 'Soft white bread, grilled marinated chicken breast, pesto sauce, mozzarella cheese, tomato', 8000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-panini'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-panini-grilled-chicken-mozzarella-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vegetarian', 'ac-panini-vegetarian-4', 'Soft white bread, eggplant, zucchini, carrot, tomato, onion, mushrooms topped with balsamic vinegar dressing', 8000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-panini'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-panini-vegetarian-4'
  )
LIMIT 1;

-- HOT SANDWICHES
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fajita wraps', 'ac-hot-sandwiches-fajita-wraps', 'Tortilla bread, chicken slice marinated, green pepper, onion, mixed cheese, guacamole served with salsa sauce and French fries', 8500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-fajita-wraps'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crispy chicken wrap', 'ac-hot-sandwiches-crispy-chicken-wrap-2', 'Homemade bread wrap, crispy fried chicken bread, tomato, red onion, tomato sauce, blue cheese, ranch sauce, served with French fries', 9500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-crispy-chicken-wrap-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Chicken', 'ac-hot-sandwiches-grilled-chicken-3', 'Soft bun, grilled chicken breast, cheddar cheese, garlic mayo, BBQ sauce, sliced tomato, sliced red onion, homemade pickles, crispy lettuce', 9500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-grilled-chicken-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic Cheese', 'ac-hot-sandwiches-classic-cheese-4', 'Soft bun, two 170 gr of beef patty, double cheddar cheese, special sauce, ketchup, slice tomato, slice red onion, homemade pickles, crispy lettuce', 9500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-classic-cheese-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crispy Fish', 'ac-hot-sandwiches-crispy-fish-5', 'Soft bun, crispy fried white fish filet, cheddar cheese, tartar sauce, slice of tomato, crispy lettuce', 14000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-crispy-fish-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'After midnight', 'ac-hot-sandwiches-after-midnight-6', 'Soft bun, 170 gr of beef patty, cheddar cheese, fried mozzarella cheese, crispy bacon, special sauce, slice tomato, slice red onion, crispy lettuce', 15000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-after-midnight-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bacon', 'ac-hot-sandwiches-bacon-7', NULL, 2500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-bacon-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cheddar cheese', 'ac-hot-sandwiches-cheddar-cheese-8', 'Homemade bread wrap, crispy fried chicken bread, tomato, red onion, tomato sauce, blue cheese, ranch sauce, served with French fries', 9500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-cheddar-cheese-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grilled Chicken', 'ac-hot-sandwiches-grilled-chicken-9', 'Soft bun, grilled chicken breast, cheddar cheese, garlic mayo, BBQ sauce, sliced tomato, sliced red onion, homemade pickles, crispy lettuce', 9500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-grilled-chicken-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic Cheese', 'ac-hot-sandwiches-classic-cheese-10', 'Soft bun, two 170 gr of beef patty, double cheddar cheese, special sauce, ketchup, slice tomato, slice red onion, homemade pickles, crispy lettuce', 9500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-classic-cheese-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Crispy Fish', 'ac-hot-sandwiches-crispy-fish-11', 'Soft bun, crispy fried white fish filet, cheddar cheese, tartar sauce, slice of tomato, crispy lettuce', 14000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-crispy-fish-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'After midnight', 'ac-hot-sandwiches-after-midnight-12', 'Soft bun, 170 gr of beef patty, cheddar cheese, fried mozzarella cheese, crispy bacon, special sauce, slice tomato, slice red onion, crispy lettuce', 15000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-after-midnight-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bacon', 'ac-hot-sandwiches-bacon-13', NULL, 2500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-bacon-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cheddar cheese', 'ac-hot-sandwiches-cheddar-cheese-14', NULL, 1000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-cheddar-cheese-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried egg', 'ac-hot-sandwiches-fried-egg-15', NULL, 1000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-fried-egg-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ham or turkey slice', 'ac-hot-sandwiches-ham-or-turkey-slice-16', NULL, 1000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-hot-sandwiches'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-hot-sandwiches-ham-or-turkey-slice-16'
  )
LIMIT 1;

-- APPETIZERS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Nigerian peppered snails', 'ac-appetizers-nigerian-peppered-snails', 'Tender African snails toasted with homemade chili pepper sauce.', 11000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-nigerian-peppered-snails'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Assortment of peppered meat', 'ac-appetizers-assortment-of-peppered-meat-2', 'Gizzard, chicken, beef, snails, and kpomo toasted with homemade chili sauce served with fresh tomato and red onion.', 15500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-assortment-of-peppered-meat-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peppered Shrimps', 'ac-appetizers-peppered-shrimps-3', 'Shrimp toasted with homemade chili pepper sauce.', 11500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-peppered-shrimps-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peppered Kpomo', 'ac-appetizers-peppered-kpomo-4', 'Cow skin mixed with hot and spicy peppers.', 4000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-peppered-kpomo-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried peppered chicken tender', 'ac-appetizers-fried-peppered-chicken-tender-5', 'Deep-fried breaded chicken tender, toasted with African spicy sauce.', 8500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-fried-peppered-chicken-tender-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Nigerian spring rolls', 'ac-appetizers-chicken-nigerian-spring-rolls-6', '4 deep-fried spring rolls filled with shrimp and vegetables Nigerian style.', 8000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-chicken-nigerian-spring-rolls-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peppered fried chicken wings', 'ac-appetizers-peppered-fried-chicken-wings-7', 'Deep-fried chicken wings toasted in homemade chili pepper sauce', 7500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-peppered-fried-chicken-wings-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Popcorn Gizzard', 'ac-appetizers-popcorn-gizzard-8', 'Deep-fried breaded Gizzard served with homemade chili sauce', 7500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-popcorn-gizzard-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried spring rolls', 'ac-appetizers-fried-spring-rolls-9', '4 Deep fried chicken rolls, served with sweet chili sauce', 8000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-fried-spring-rolls-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Garlic lemon Octopus', 'ac-appetizers-garlic-lemon-octopus-10', 'Sauteed octopus in a garlic coriander sauce, topped with fresh lemon juice, served with bread', 9500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-garlic-lemon-octopus-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken or beef quesadillas', 'ac-appetizers-chicken-or-beef-quesadillas-11', 'Toasted tortilla bread filled with your choice of chicken or beef, green pepper, onion, mixed cheese, served with, guacamole, salsa, and sour cream', 10000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-chicken-or-beef-quesadillas-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fried chicken wings', 'ac-appetizers-fried-chicken-wings-12', 'Deep-fried chicken wings served with homemade hot sauce', 8000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-fried-chicken-wings-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mozzarella sticks', 'ac-appetizers-mozzarella-sticks-13', '5 Breaded mozzarella cheese, deep fried served with tomato sauce', 7500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-mozzarella-sticks-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Popcorn shrimps', 'ac-appetizers-popcorn-shrimps-14', 'Breaded shrimp, deep fried served with tartar sauce', 7500, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-popcorn-shrimps-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Loaded French fries', 'ac-appetizers-loaded-french-fries-15', 'French fries topped with chili con carne, cheddar cheese, jalapeño served with sour cream', 7500, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-loaded-french-fries-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hummus', 'ac-appetizers-hummus-16', 'Purred chickpeas in tahini sauce, and olive oil, served with fresh vegetables and pita bread', 7000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-appetizers'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-appetizers-hummus-16'
  )
LIMIT 1;

-- SALADS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken Macaroni', 'ac-salads-chicken-macaroni', 'Macaroni elbow, carrots, green pepper, tatashe, and red onion toasted with mayonnaise sauce topped with grilled chicken breast and boiled eggs.', 7000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-chicken-macaroni'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Classic Chicken Caesar', 'ac-salads-classic-chicken-caesar-2', 'Romain lettuce, parmesan cheese, garlic crouton, grilled chicken breast toasted in our homemade Caesar dressing', 9500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-classic-chicken-caesar-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Spaghetti Bolognese', 'ac-salads-spaghetti-bolognese-3', 'Spaghetti pasta, Bolognese sauce, served with parmesan cheese and bread', 9500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-spaghetti-bolognese-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Penne al pesto', 'ac-salads-penne-al-pesto-4', 'Penne pasta toasted in homemade pesto sauce served with parmesan cheese and bread', 9500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-penne-al-pesto-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chicken escalope', 'ac-salads-chicken-escalope-5', 'Deep-fried breaded chicken breast and sweet corn served with French fries coleslaw salad, and honey mustard sauce', 9000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-chicken-escalope-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Steak frites', 'ac-salads-steak-frites-6', 'Grilled Imported beef filet served with French fries and sautéed vegetables', 18800, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-steak-frites-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fisher main dish', 'ac-salads-fisher-main-dish-7', 'Fried fish filet, grilled prawns, grilled calamari, and sautéed octopus served with French fries coleslaw salad, and tartar sauce', 19500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-fisher-main-dish-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Oriental Grilled platter', 'ac-salads-oriental-grilled-platter-8', 'Grilled skewers of chicken kebab beef kebab served with grilled onion, grilled tomato spicy pita bread, garlic sauce, and hummus dip', 15000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-oriental-grilled-platter-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Red Curry with prawns', 'ac-salads-red-curry-with-prawns-9', 'Homemade Red curry sauce, prawns, green beans, potato, eggplant, served with steamed rice', 12500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-salads'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-salads-red-curry-with-prawns-9'
  )
LIMIT 1;

-- DESSERT
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ice cream scoop', 'ac-dessert-ice-cream-scoop', NULL, 3000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-dessert-ice-cream-scoop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tropical fruit salad', 'ac-dessert-tropical-fruit-salad-2', NULL, 2500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'ac-dessert'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'ac-dessert-tropical-fruit-salad-2'
  )
LIMIT 1;

-- ----- drink-menu categories -----
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'CHAMPAGNE', 'tb-champagne', 1, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'TEQUILA', 'tb-tequila', 2, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'COGNAC', 'tb-cognac', 3, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'VODKA', 'tb-vodka', 4, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-vodka')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'GIN', 'tb-gin', 5, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-gin')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'RUM', 'tb-rum', 6, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-rum')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'WHISKY', 'tb-whisky', 7, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'BEER', 'tb-beer', 8, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-beer')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Non Alcohol drinks', 'tb-non-alcohol-drinks', 9, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Juice Pitcher', 'tb-juice-pitcher', 10, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-juice-pitcher')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'Hot Beverages', 'tb-hot-beverages', 11, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-hot-beverages')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'CLASSIC COCKTAILS', 'tb-classic-cocktails', 12, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'SIGNATURE COCKTAILS', 'tb-signature-cocktails', 13, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'SHOTS', 'tb-shots', 14, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots')
LIMIT 1;
INSERT INTO categories (restaurant_id, section_id, name, slug, display_order, is_active)
SELECT @rid, @sid_dm, 'SPECIAL SHOTS', 'tb-special-shots', 15, 1 FROM DUAL
WHERE @sid_dm IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM categories c WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots')
LIMIT 1;

-- CHAMPAGNE
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bille cart blanc de blanc', 'tb-champagne-bille-cart-blanc-de-blanc', NULL, 200000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-bille-cart-blanc-de-blanc'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bille cart salmon brut rose', 'tb-champagne-bille-cart-salmon-brut-rose-2', NULL, 280000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-bille-cart-salmon-brut-rose-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Bille cart salmon demi sec', 'tb-champagne-bille-cart-salmon-demi-sec-3', NULL, 175000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-bille-cart-salmon-demi-sec-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dom Perignon Brut', 'tb-champagne-dom-perignon-brut-4', NULL, 800000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-dom-perignon-brut-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Dom Perignon rose', 'tb-champagne-dom-perignon-rose-5', NULL, 1150000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-dom-perignon-rose-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Laurent Perrier brut', 'tb-champagne-laurent-perrier-brut-6', NULL, 180000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-laurent-perrier-brut-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Laurent Perrier Cuvee rose', 'tb-champagne-laurent-perrier-cuvee-rose-7', NULL, 280000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-laurent-perrier-cuvee-rose-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet brut', 'tb-champagne-moet-brut-8', NULL, 240000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-moet-brut-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet chandon imperial rose', 'tb-champagne-moet-chandon-imperial-rose-9', NULL, 260000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-moet-chandon-imperial-rose-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet imperial ice', 'tb-champagne-moet-imperial-ice-10', NULL, 300000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-moet-imperial-ice-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moet nectar rose', 'tb-champagne-moet-nectar-rose-11', NULL, 280000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-moet-nectar-rose-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ruinart blanc de blanc', 'tb-champagne-ruinart-blanc-de-blanc-12', NULL, 320000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-ruinart-blanc-de-blanc-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ruinart brut', 'tb-champagne-ruinart-brut-13', NULL, 210000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-ruinart-brut-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Cliquot Brut', 'tb-champagne-veuve-cliquot-brut-14', NULL, 250000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-veuve-cliquot-brut-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Veuve Cliquot Rich', 'tb-champagne-veuve-cliquot-rich-15', NULL, 300000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-champagne'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-champagne-veuve-cliquot-rich-15'
  )
LIMIT 1;

-- TEQUILA
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Casamigo', 'tb-tequila-casamigo', NULL, 300000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-casamigo'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Clase azul rapasado', 'tb-tequila-clase-azul-rapasado-2', NULL, 720000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-clase-azul-rapasado-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Claze azul plata jalisco', 'tb-tequila-claze-azul-plata-jalisco-3', NULL, 420000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-claze-azul-plata-jalisco-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Julio 1942', 'tb-tequila-don-julio-1942-4', NULL, 720000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-don-julio-1942-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Don Julio Reposado', 'tb-tequila-don-julio-reposado-5', NULL, 450000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-don-julio-reposado-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Volcan anejo', 'tb-tequila-volcan-anejo-6', NULL, 330000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-volcan-anejo-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vivir tequila Reposado', 'tb-tequila-vivir-tequila-reposado-7', 'spicy goat rice mix', 190000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-vivir-tequila-reposado-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vivir Tequila Blanco', 'tb-tequila-vivir-tequila-blanco-8', NULL, 170000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-tequila'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-tequila-vivir-tequila-blanco-8'
  )
LIMIT 1;

-- COGNAC
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy VSOP', 'tb-cognac-hennessy-vsop', NULL, 270000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-hennessy-vsop'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessy X.O', 'tb-cognac-hennessy-x-o-2', NULL, 700000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-hennessy-x-o-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Martel Bleu Swift', 'tb-cognac-martel-bleu-swift-3', NULL, 230000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-martel-bleu-swift-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Remy Martin', 'tb-cognac-remy-martin-4', NULL, 195000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-remy-martin-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tesseron xo tradition', 'tb-cognac-tesseron-xo-tradition-5', NULL, 320000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-tesseron-xo-tradition-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tesseron xo ovation', 'tb-cognac-tesseron-xo-ovation-6', NULL, 420000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-tesseron-xo-ovation-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tesseron xo perfection', 'tb-cognac-tesseron-xo-perfection-7', NULL, 580000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-tesseron-xo-perfection-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sainte croix xo', 'tb-cognac-sainte-croix-xo-8', NULL, 280000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-cognac'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-cognac-sainte-croix-xo-8'
  )
LIMIT 1;

-- VODKA
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Beluga gold line', 'tb-vodka-beluga-gold-line', NULL, 100000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-vodka-beluga-gold-line'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Belverde', 'tb-vodka-belverde-2', NULL, 150000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-vodka-belverde-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grey Goose', 'tb-vodka-grey-goose-3', NULL, 130000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-vodka'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-vodka-grey-goose-3'
  )
LIMIT 1;

-- GIN
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin mare', 'tb-gin-gin-mare', NULL, 115000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-gin-gin-mare'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hendricks', 'tb-gin-hendricks-2', NULL, 120000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-gin'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-gin-hendricks-2'
  )
LIMIT 1;

-- RUM
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Baccardi White', 'tb-rum-baccardi-white', NULL, 60000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-rum-baccardi-white'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hendricks', 'tb-rum-hendricks-2', NULL, 800000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-rum'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-rum-hendricks-2'
  )
LIMIT 1;

-- WHISKY
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivas 15', 'tb-whisky-chivas-15', NULL, 125000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-chivas-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Chivas 18', 'tb-whisky-chivas-18-2', NULL, 170000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-chivas-18-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 12', 'tb-whisky-glenfiddich-12-3', NULL, 140000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-glenfiddich-12-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 15', 'tb-whisky-glenfiddich-15-4', NULL, 200000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-glenfiddich-15-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 18', 'tb-whisky-glenfiddich-18-5', NULL, 290000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-glenfiddich-18-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenfiddich 21', 'tb-whisky-glenfiddich-21-6', NULL, 600000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-glenfiddich-21-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenmorangie 10 original', 'tb-whisky-glenmorangie-10-original-7', NULL, 150000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-glenmorangie-10-original-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenmorangie 18 yrs. extreme', 'tb-whisky-glenmorangie-18-yrs-extreme-8', NULL, 300000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-glenmorangie-18-yrs-extreme-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Glenmorangie Signet', 'tb-whisky-glenmorangie-signet-9', NULL, 600000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-glenmorangie-signet-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jameson Black Barre', 'tb-whisky-jameson-black-barre-10', NULL, 130000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-jameson-black-barre-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Johnny walker blue label', 'tb-whisky-johnny-walker-blue-label-11', NULL, 560000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-johnny-walker-blue-label-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan double', 'tb-whisky-macallan-double-12', NULL, 495000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-macallan-double-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Macallan rare cast', 'tb-whisky-macallan-rare-cast-13', NULL, 410000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-whisky'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-whisky-macallan-rare-cast-13'
  )
LIMIT 1;

-- BEER
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Heineken', 'tb-beer-heineken', NULL, 5000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-beer-heineken'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Guinness', 'tb-beer-guinness-2', NULL, 5000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-beer'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-beer-guinness-2'
  )
LIMIT 1;

-- Non Alcohol drinks
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Water Large', 'tb-non-alcohol-drinks-water-large', NULL, 3500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-water-large'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Water small', 'tb-non-alcohol-drinks-water-small-2', NULL, 1600, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-water-small-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Coke', 'tb-non-alcohol-drinks-coke-3', NULL, 1800, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-coke-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fanta', 'tb-non-alcohol-drinks-fanta-4', NULL, 1800, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-fanta-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sprite', 'tb-non-alcohol-drinks-sprite-5', NULL, 1800, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-sprite-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ginger ale', 'tb-non-alcohol-drinks-ginger-ale-6', NULL, 4500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-ginger-ale-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Perrier', 'tb-non-alcohol-drinks-perrier-7', NULL, 4500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-perrier-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Power Horse', 'tb-non-alcohol-drinks-power-horse-8', NULL, 4800, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-power-horse-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Red bull', 'tb-non-alcohol-drinks-red-bull-9', NULL, 5000, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-red-bull-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Soda', 'tb-non-alcohol-drinks-soda-10', NULL, 2000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-soda-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tonic', 'tb-non-alcohol-drinks-tonic-11', NULL, 2000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-non-alcohol-drinks'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-non-alcohol-drinks-tonic-11'
  )
LIMIT 1;

-- Juice Pitcher
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cranberry Picher', 'tb-juice-pitcher-cranberry-picher', NULL, 18000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-juice-pitcher-cranberry-picher'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Orange', 'tb-juice-pitcher-orange-2', NULL, 18000, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-juice-pitcher-orange-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple', 'tb-juice-pitcher-apple-3', NULL, 8500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-juice-pitcher-apple-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pineapple', 'tb-juice-pitcher-pineapple-4', NULL, 8500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-juice-pitcher'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-juice-pitcher-pineapple-4'
  )
LIMIT 1;

-- Hot Beverages
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Single espresso', 'tb-hot-beverages-single-espresso', NULL, 4500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-hot-beverages-single-espresso'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Double espresso', 'tb-hot-beverages-double-espresso-2', NULL, 5500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-hot-beverages-double-espresso-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tea selection', 'tb-hot-beverages-tea-selection-3', NULL, 4600, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-hot-beverages-tea-selection-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cappuccino', 'tb-hot-beverages-cappuccino-4', NULL, 5400, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-hot-beverages-cappuccino-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Americano', 'tb-hot-beverages-americano-5', NULL, 5600, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-hot-beverages-americano-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Café late', 'tb-hot-beverages-caf-late-6', NULL, 4600, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-hot-beverages'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-hot-beverages-caf-late-6'
  )
LIMIT 1;

-- CLASSIC COCKTAILS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mojito', 'tb-classic-cocktails-mojito', NULL, 11500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-mojito'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Flavored Mojito', 'tb-classic-cocktails-flavored-mojito-2', NULL, 11500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-flavored-mojito-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila sunrise', 'tb-classic-cocktails-tequila-sunrise-3', NULL, 11500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-tequila-sunrise-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whisky sour', 'tb-classic-cocktails-whisky-sour-4', NULL, 11500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-whisky-sour-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry Daiquiri', 'tb-classic-cocktails-strawberry-daiquiri-5', NULL, 11500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-strawberry-daiquiri-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin Tonic', 'tb-classic-cocktails-gin-tonic-6', NULL, 11500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-gin-tonic-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Moscow mule', 'tb-classic-cocktails-moscow-mule-7', NULL, 11500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-moscow-mule-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Basilicum', 'tb-classic-cocktails-basilicum-8', NULL, 11500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-basilicum-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Long island', 'tb-classic-cocktails-long-island-9', NULL, 11500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-long-island-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Pinacolada', 'tb-classic-cocktails-pinacolada-10', NULL, 11500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-pinacolada-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Alexander', 'tb-classic-cocktails-alexander-11', NULL, 11500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-alexander-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Amaretto whiskey sour', 'tb-classic-cocktails-amaretto-whiskey-sour-12', NULL, 11500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-amaretto-whiskey-sour-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Cosmopolitan', 'tb-classic-cocktails-cosmopolitan-13', NULL, 11500, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-cosmopolitan-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Manhattan', 'tb-classic-cocktails-manhattan-14', NULL, 11500, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-manhattan-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Margarita', 'tb-classic-cocktails-margarita-15', NULL, 11500, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-margarita-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Flavored Margarita', 'tb-classic-cocktails-flavored-margarita-16', NULL, 11500, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-flavored-margarita-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Sex on the beach', 'tb-classic-cocktails-sex-on-the-beach-17', NULL, 11500, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-sex-on-the-beach-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Porn star Martini', 'tb-classic-cocktails-porn-star-martini-18', NULL, 11500, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-porn-star-martini-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vodka sour', 'tb-classic-cocktails-vodka-sour-19', NULL, 11500, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-vodka-sour-19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Stinger', 'tb-classic-cocktails-stinger-20', NULL, 11500, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-classic-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-classic-cocktails-stinger-20'
  )
LIMIT 1;

-- SIGNATURE COCKTAILS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, '1840 Original', 'tb-signature-cocktails-1840-original', NULL, 12500, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-1840-original'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lichee Martini', 'tb-signature-cocktails-lichee-martini-2', NULL, 12500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-lichee-martini-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome Caiparian', 'tb-signature-cocktails-vendome-caiparian-3', NULL, 12500, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-vendome-caiparian-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome pepper dem', 'tb-signature-cocktails-vendome-pepper-dem-4', NULL, 12500, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-vendome-pepper-dem-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ice fruity', 'tb-signature-cocktails-ice-fruity-5', NULL, 12500, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-ice-fruity-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peach and horny Rita', 'tb-signature-cocktails-peach-and-horny-rita-6', NULL, 12500, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-peach-and-horny-rita-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fallen olde fashioned', 'tb-signature-cocktails-fallen-olde-fashioned-7', NULL, 12500, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-fallen-olde-fashioned-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Green Latern', 'tb-signature-cocktails-green-latern-8', NULL, 12500, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-green-latern-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Londinum', 'tb-signature-cocktails-londinum-9', NULL, 12500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-londinum-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Ocean wave', 'tb-signature-cocktails-ocean-wave-10', NULL, 12500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-signature-cocktails'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-signature-cocktails-ocean-wave-10'
  )
LIMIT 1;

-- SHOTS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila', 'tb-shots-tequila', NULL, 4000, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-tequila'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Tequila Gold', 'tb-shots-tequila-gold-2', NULL, 4500, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-tequila-gold-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vodka', 'tb-shots-vodka-3', NULL, 4000, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-vodka-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gin', 'tb-shots-gin-4', NULL, 4000, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-gin-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Whiskey', 'tb-shots-whiskey-5', NULL, 4000, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-whiskey-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Hennessey VSOP', 'tb-shots-hennessey-vsop-6', NULL, 5000, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-hennessey-vsop-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Rum', 'tb-shots-rum-7', NULL, 4000, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-rum-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Jägermeister', 'tb-shots-j-germeister-8', NULL, 4000, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-j-germeister-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Londinum', 'tb-shots-londinum-9', NULL, 12500, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-londinum-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Baileys', 'tb-shots-baileys-10', NULL, 3500, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-baileys-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Campari', 'tb-shots-campari-11', NULL, 3500, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-campari-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Aperol', 'tb-shots-aperol-12', NULL, 3500, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-shots-aperol-12'
  )
LIMIT 1;

-- SPECIAL SHOTS
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Brain hemorrhage', 'tb-special-shots-brain-hemorrhage', NULL, 5600, 1, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-brain-hemorrhage'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Condom shot', 'tb-special-shots-condom-shot-2', NULL, 5600, 2, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-condom-shot-2'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Death of jellyfish', 'tb-special-shots-death-of-jellyfish-3', NULL, 5600, 3, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-death-of-jellyfish-3'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Doo Doo', 'tb-special-shots-doo-doo-4', NULL, 5600, 4, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-doo-doo-4'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Liquid cocaine', 'tb-special-shots-liquid-cocaine-5', NULL, 5600, 5, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-liquid-cocaine-5'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Absent without leave', 'tb-special-shots-absent-without-leave-6', NULL, 5600, 6, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-absent-without-leave-6'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Fire shots', 'tb-special-shots-fire-shots-7', NULL, 5600, 7, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-fire-shots-7'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome shot', 'tb-special-shots-vendome-shot-8', NULL, 5600, 8, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-vendome-shot-8'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blowjob', 'tb-special-shots-blowjob-9', NULL, 5600, 9, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-blowjob-9'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Vendome tower fire show', 'tb-special-shots-vendome-tower-fire-show-10', NULL, 25000, 10, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-vendome-tower-fire-show-10'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Iced Gum', 'tb-special-shots-iced-gum-11', NULL, 19000, 11, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-iced-gum-11'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Magic Love', 'tb-special-shots-magic-love-12', NULL, 19000, 12, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-magic-love-12'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Love', 'tb-special-shots-love-13', NULL, 19000, 13, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-love-13'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry', 'tb-special-shots-strawberry-14', NULL, 19000, 14, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-strawberry-14'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry and Mint', 'tb-special-shots-strawberry-and-mint-15', NULL, 19000, 15, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-strawberry-and-mint-15'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mixed Fruit', 'tb-special-shots-mixed-fruit-16', NULL, 19000, 16, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mixed-fruit-16'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gum and Mint', 'tb-special-shots-gum-and-mint-17', NULL, 19000, 17, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-gum-and-mint-17'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gum', 'tb-special-shots-gum-18', NULL, 19000, 18, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-gum-18'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lemon and Mint', 'tb-special-shots-lemon-and-mint-19', NULL, 19000, 19, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-lemon-and-mint-19'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mint and Cream', 'tb-special-shots-mint-and-cream-20', NULL, 19000, 20, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mint-and-cream-20'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grape and Mint', 'tb-special-shots-grape-and-mint-21', NULL, 19000, 21, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-grape-and-mint-21'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grape', 'tb-special-shots-grape-22', NULL, 19000, 22, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-grape-22'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Two Apple', 'tb-special-shots-two-apple-23', NULL, 19000, 23, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-two-apple-23'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mint', 'tb-special-shots-mint-24', NULL, 19000, 24, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mint-24'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peach', 'tb-special-shots-peach-25', NULL, 19000, 25, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-peach-25'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blueberry', 'tb-special-shots-blueberry-26', NULL, 19000, 26, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-blueberry-26'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blueberry and Mint', 'tb-special-shots-blueberry-and-mint-27', NULL, 19000, 27, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-blueberry-and-mint-27'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mango', 'tb-special-shots-mango-28', NULL, 19000, 28, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mango-28'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon', 'tb-special-shots-watermelon-29', NULL, 19000, 29, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-watermelon-29'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon and Mint', 'tb-special-shots-watermelon-and-mint-30', NULL, 19000, 30, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-watermelon-and-mint-30'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lady Killer', 'tb-special-shots-lady-killer-31', NULL, 19000, 31, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-lady-killer-31'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Two Apple', 'tb-special-shots-two-apple-32', NULL, 19000, 32, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-two-apple-32'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple', 'tb-special-shots-apple-33', NULL, 19000, 33, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-apple-33'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Iced Gum', 'tb-special-shots-iced-gum-34', NULL, 19000, 34, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-iced-gum-34'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Magic Love', 'tb-special-shots-magic-love-35', NULL, 19000, 35, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-magic-love-35'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Love', 'tb-special-shots-love-36', NULL, 19000, 36, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-love-36'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry', 'tb-special-shots-strawberry-37', NULL, 19000, 37, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-strawberry-37'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Strawberry and Mint', 'tb-special-shots-strawberry-and-mint-38', NULL, 19000, 38, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-strawberry-and-mint-38'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mixed Fruit', 'tb-special-shots-mixed-fruit-39', NULL, 19000, 39, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mixed-fruit-39'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gum and Mint', 'tb-special-shots-gum-and-mint-40', NULL, 19000, 40, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-gum-and-mint-40'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Gum', 'tb-special-shots-gum-41', NULL, 19000, 41, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-gum-41'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lemon and Mint', 'tb-special-shots-lemon-and-mint-42', NULL, 19000, 42, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-lemon-and-mint-42'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mint and Cream', 'tb-special-shots-mint-and-cream-43', NULL, 19000, 43, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mint-and-cream-43'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grape and Mint', 'tb-special-shots-grape-and-mint-44', NULL, 19000, 44, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-grape-and-mint-44'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Grape', 'tb-special-shots-grape-45', NULL, 19000, 45, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-grape-45'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Two Apple', 'tb-special-shots-two-apple-46', NULL, 19000, 46, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-two-apple-46'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mint', 'tb-special-shots-mint-47', NULL, 19000, 47, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mint-47'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Peach', 'tb-special-shots-peach-48', NULL, 19000, 48, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-peach-48'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blueberry', 'tb-special-shots-blueberry-49', NULL, 19000, 49, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-blueberry-49'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Blueberry and Mint', 'tb-special-shots-blueberry-and-mint-50', NULL, 19000, 50, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-blueberry-and-mint-50'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Mango', 'tb-special-shots-mango-51', NULL, 19000, 51, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-mango-51'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon', 'tb-special-shots-watermelon-52', NULL, 19000, 52, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-watermelon-52'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Watermelon and Mint', 'tb-special-shots-watermelon-and-mint-53', NULL, 19000, 53, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-watermelon-and-mint-53'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Lady Killer', 'tb-special-shots-lady-killer-54', NULL, 19000, 54, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-lady-killer-54'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Two Apple', 'tb-special-shots-two-apple-55', NULL, 19000, 55, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-two-apple-55'
  )
LIMIT 1;
INSERT INTO menu_items (restaurant_id, category_id, name, slug, description, price, display_order, is_available)
SELECT @rid, c.id, 'Apple', 'tb-special-shots-apple-56', NULL, 19000, 56, 1 FROM categories c
WHERE c.restaurant_id = @rid AND c.slug = 'tb-special-shots'
  AND NOT EXISTS (
    SELECT 1 FROM menu_items mi
    WHERE mi.restaurant_id = @rid AND mi.category_id = c.id AND mi.slug = 'tb-special-shots-apple-56'
  )
LIMIT 1;

-- Done. Restaurant must exist (vendome-cafe-s-menu or admin@vendomecafe.our-menu.online).
