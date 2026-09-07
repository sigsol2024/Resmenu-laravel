<?php

/**
 * Image source map for Alo Alo Restaurant migration.
 *
 * Keys   = image_key values used in menu.php
 * Values = either:
 *   - A path relative to public/  (local DESIGN_1 asset)
 *   - An https:// URL             (remote / Unsplash image)
 *
 * The migration seeder should copy / download each source and store it
 * under the restaurant's media directory, keyed by the image_key.
 */

return [

    // ── SECTION BANNERS ──────────────────────────────────────────────────────

    'sections/breakfast.jpg'
        => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1280&q=80',

    'sections/starters.jpg'
        => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1280&q=80',

    'sections/wraps.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/IMG-20250803-WA0041.jpg',

    'sections/entree.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/bURGER-1.webp',

    'sections/grill.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/WhatsApp-Image-2025-08-04-at-02.07.39_2969425a-copy.webp',

    'sections/national-menu.jpg'
        => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=1280&q=80',

    'sections/world.jpg'
        => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1280&q=80',

    'sections/vcp-specials.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/IMG-20250803-WA0100.jpg',

    'sections/sides.jpg'
        => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=1280&q=80',

    'sections/dessert.jpg'
        => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=1280&q=80',

    'sections/drinks.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/cocktails.jpg',

    // ── CATEGORY HEADERS ─────────────────────────────────────────────────────

    'categories/hot-beverages.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/cappuccino.jpg',

    'categories/mocktails.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/mocktail.jpg',

    'categories/cocktails.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/cocktails.jpg',

    'categories/soft-drinks.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/soft-drinks.jpg',

    'categories/smoothies.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/smoothie-mixed.jpg',

    'categories/juices.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/juice-glass.jpg',

    'categories/beers.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/beer-bar.jpg',

    'categories/whiskey.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/whiskey-bg.jpg',

    'categories/wine.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/wine-bg.jpg',

    'categories/cognac.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/cognac.jpg',

    'categories/champagne.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/champagne-bg.jpg',

    'categories/burger.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/bURGER-1.webp',

    'categories/sandwiches.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/chops.webp',

    'categories/pizza.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/pizza-300x300.webp',

    // ── MENU ITEM IMAGES ─────────────────────────────────────────────────────

    // Breakfast
    'menu-items/american-breakfast.jpg'
        => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=640&q=80',

    'menu-items/nigerian-breakfast.jpg'
        => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=640&q=80',

    'menu-items/vcp-full-english-breakfast.jpg'
        => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=640&q=80',

    // Starters
    'menu-items/caprese-salad.jpg'
        => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=640&q=80',

    'menu-items/nigerian-pepper-soup.jpg'
        => 'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&fit=crop&w=640&q=80',

    'menu-items/buffalo-wings.jpg'
        => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=640&q=80',

    // Wraps
    'menu-items/wraps.jpg'
        => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=640&q=80',

    // Entree
    'menu-items/burger.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/bURGER-1.webp',

    'menu-items/sandwich.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/chops.webp',

    'menu-items/pizza.jpg'
        => 'https://web.vcphotels.com/wp-content/uploads/2025/08/pizza-300x300.webp',

    // From the Grill
    'menu-items/catfish.jpg'
        => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?auto=format&fit=crop&w=640&q=80',

    'menu-items/grilled-chicken.jpg'
        => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=640&q=80',

    'menu-items/grilled-prawns.jpg'
        => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=640&q=80',

    'menu-items/rib-eye-steak.jpg'
        => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=640&q=80',

    // World
    'menu-items/aubergine-parmigiana.jpg'
        => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=640&q=80',

    'menu-items/lasagna.jpg'
        => 'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&fit=crop&w=640&q=80',

    'menu-items/chinese-dumplings.jpg'
        => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=640&q=80',

    'menu-items/biryani.jpg'
        => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=640&q=80',

    // VCP Specials
    'menu-items/carnivore-platter.jpg'
        => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?auto=format&fit=crop&w=640&q=80',

    'menu-items/fisherman-platter.jpg'
        => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=640&q=80',

    'menu-items/seafood-boil.jpg'
        => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=640&q=80',

    'menu-items/vcp-pineapple-fried-rice.jpg'
        => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=640&q=80',

    // Dessert
    'menu-items/cake-slices.jpg'
        => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=640&q=80',

    // ── DRINKS — local DESIGN_1 assets ───────────────────────────────────────

    // Hot Beverages
    'menu-items/americano.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/espresso.jpg',

    'menu-items/cappuccino.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/cappuccino.jpg',

    'menu-items/coffee.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/coffee.jpg',

    'menu-items/espresso.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/espresso.jpg',

    'menu-items/frappuccino.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/frappuccino.jpg',

    'menu-items/tea.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/tea.jpg',

    'menu-items/hot-chocolate.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/hot-chocolate.jpg',

    'menu-items/latte.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/latte.jpg',

    // Mocktails
    'menu-items/mocktail.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/mocktail.jpg',

    'menu-items/lemonade.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/lemonade.jpg',

    'menu-items/pina-colada.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/pina-colada.jpg',

    'menu-items/mojito.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/mojito.jpg',

    // Cocktails
    'menu-items/cocktail.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/cocktails.jpg',

    'menu-items/espresso-martini.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/espresso-martini.jpg',

    'menu-items/margarita.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/margarita.jpg',

    'menu-items/negroni.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/negroni.jpg',

    'menu-items/orange-juice.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/orange-juice.jpg',

    'menu-items/whiskey-sour.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/whiskey-sour.jpg',

    'menu-items/wine.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/wine.jpg',

    // Soft Drinks
    'menu-items/soft-drink.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/soft-drinks.jpg',

    'menu-items/soda-can.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/soda-can.jpg',

    // Smoothies & Juices
    'menu-items/smoothie-banana.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/smoothie-banana.jpg',

    'menu-items/smoothie-pineapple.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/smoothie-pineapple.jpg',

    'menu-items/smoothie-watermelon.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/smoothie-watermelon.jpg',

    'menu-items/juice-glass.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/juice-glass.jpg',

    'menu-items/smoothie-mixed.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/smoothie-mixed.jpg',

    // Beers
    'menu-items/beer.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/beer.jpg',

    // Whiskey
    'menu-items/whiskey.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/whiskey.jpg',

    // Cognac / Brandy
    'menu-items/cognac.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/cognac.jpg',

    // Champagne
    'menu-items/champagne.jpg'
        => 'REFERENCES/DESIGN_1/assets/drinks/champagne.jpg',

];
