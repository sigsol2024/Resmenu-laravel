<?php

/**
 * Curated demo images under public/templates/preview_images/.
 * Paths are relative to that root. Hash-named files were assigned by visual inspection.
 */
return [
    'restaurant_cover' => 'demo_restaurant_coverimage.jpg',

    /**
     * Gallery card images (resmenu.net) — only supplied Preview_covers files.
     * Template IDs without an entry stay empty (no fakes).
     */
    'gallery_covers' => [
        7 => 'Preview_covers/The-Art-Fusion-—-Demo-09-14-2026_11_41_PM.png',
        8 => 'Preview_covers/Sweet-Delight-—-Demo-09-14-2026_11_41_PM.png',
        9 => 'Preview_covers/Street-Food-Hub-—-Demo-09-14-2026_11_41_PM.png',
        11 => 'Preview_covers/White-Party-—-Demo-09-14-2026_11_41_PM.png',
        12 => 'Preview_covers/Mediterranean-Fresh-—-Demo-09-14-2026_11_40_PM.png',
        13 => 'Preview_covers/Forged-In-Spirit-—-Demo-09-14-2026_11_40_PM.png',
        14 => 'Preview_covers/Eart-Kitchen-—-Demo-09-14-2026_11_40_PM.png',
        15 => 'Preview_covers/Bold-Flavours-—-Demo-09-14-2026_11_39_PM.png',
        17 => 'Preview_covers/Night-Enthusiast_templates17.png',
    ],

    /** Section landing covers — one image from that section tree. */
    'sections' => [
        'food' => 'menu_images/food/Mains/5qRm87VW5lLs5bHzJRlcNQHJTg95ef.png',
        'desserts' => 'menu_images/dessert/ZB0gfHTN5GC77mVnvI5P6r7Bi05b20.png',
        'drinks' => 'menu_images/drinks/cocktails/iuH47BXTeI9dyLgGmloDrMDHQAca6b.png',
    ],

    /** Category covers — from the matching category folder. */
    'categories' => [
        'breakfast' => 'menu_images/food/Breakfast/mjXo18z3xyxc92W6uxmt2s0cGfQ9adb.png',
        'starters' => 'menu_images/food/Starters_Small_Plates/9X7XaUT5RWMUxM47WyN9iYKnY6fe9.jpg',
        'salads' => 'menu_images/food/Salads/Q4NLIWJFP2p1o0D77zfQC8cbesM9adb.png',
        'wraps' => 'menu_images/food/Wraps_Snacks/VmGagXikz2YMKcEmsvHw5tGlk6fe9.png',
        'mains' => 'menu_images/food/Mains/HOIsucbufEFhwCtRr1P9wANmC3Ua5eb.png',
        'pasta-rice' => 'menu_images/food/Pasta_Rice/HR7ognw2vTxDqAYZmQ7MallTc8eaf.png',
        'desserts' => 'menu_images/dessert/7NWBsbWQEzKf75ZTq3J0ePxvdncf2f4.png',
        'cocktails' => 'menu_images/drinks/cocktails/6dHdEkU3vgTQQlwuPKlKVXsc6Md829.png',
        'wine-beer' => 'menu_images/drinks/Wine_Beer/Guinness_Stout.jpg',
        'soft-drinks' => 'menu_images/drinks/soft_drinks/cocacola_can.png',
        'coffee-tea' => 'menu_images/drinks/coffee/8KNT0Cmwadj1pOqUf8eYtJvgUWw60b7.png',
    ],

    /**
     * Menu item slug => relative path. Assigned by visual content, not filename.
     * Omissions mean the item has no image (preferred over a wrong match).
     */
    'items' => [
        // Breakfast
        'sunrise-scramble' => 'menu_images/food/Breakfast/fFvoDJ6sQmXVjr75rkpNXaMQJAa4eb.png',
        'pancake-stack' => 'menu_images/food/Breakfast/mjXo18z3xyxc92W6uxmt2s0cGfQ9adb.png',
        'nigerian-breakfast-plate' => 'menu_images/food/Breakfast/6zXZyzdChYawMLHd3qamyL50xnY94d0.png',

        // Starters
        'bruschetta-trio' => 'menu_images/food/Starters_Small_Plates/Gce3Rrhr6Fu2ulj19xPmIrv3b5g6fe9.jpg',
        'chicken-wings' => 'menu_images/food/Starters_Small_Plates/9X7XaUT5RWMUxM47WyN9iYKnY6fe9.jpg',
        'prawn-cocktail' => 'menu_images/food/Starters_Small_Plates/gEejqwJbsPJeGjh5sNoNIkoU7iIa6db.png',
        'soup-of-the-day' => 'menu_images/food/Mains/uytTIed2BfceGHloW0EDk9AdOpk4d78.png',
        'calamari-fritti' => 'menu_images/food/Starters_Small_Plates/h0jbRXgftZlgBMLifpOUPT2FIY3541.png',

        // Salads
        'caesar-salad' => 'menu_images/food/Salads/Q4NLIWJFP2p1o0D77zfQC8cbesM9adb.png',
        'greek-salad' => 'menu_images/food/Salads/njQfm1lEMNvHIKEUdRxTjGsyAk0450.png',
        'avocado-quinoa' => 'menu_images/food/Salads/OevATMoe3hwy6lgCtWIuHJhvqV04788.png',
        'grilled-chicken-salad' => 'menu_images/food/Salads/rcrYUjSmISVYGy7EOHdtNBFtcgA9adb.png',

        // Wraps & snacks
        'chicken-wrap' => 'menu_images/food/Wraps_Snacks/IgXdrkh7KSpIYghk1pxRbOlznPQ6fe9.png',
        'veggie-wrap' => 'menu_images/food/Wraps_Snacks/uBmedQbj3EE2kXYgMaLslPAGZ5k9adb.png',
        'club-sandwich' => 'menu_images/food/Wraps_Snacks/mQIrPVbm53DrgvAZw6upKF0RAwe672.png',

        // Mains
        'grilled-salmon' => 'menu_images/food/Breakfast/i1M2A7kIuLgGSd2ZjgCUSy1yCus6a7c.png',
        'ribeye-steak' => 'menu_images/food/Mains/HOIsucbufEFhwCtRr1P9wANmC3Ua5eb.png',
        'herb-roast-chicken' => 'menu_images/food/Mains/5qRm87VW5lLs5bHzJRlcNQHJTg95ef.png',
        'lamb-shank' => 'menu_images/food/Mains/T5AIpvo9Y5ugqKVu5ZQuCWHs1Mw6fce.png',
        'seafood-paella' => 'menu_images/food/Starters_Small_Plates/0uO8zQaxP91rWRhBhSnqPVDU2660.png',
        'vegan-buddha-bowl' => 'menu_images/food/Pasta_Rice/zIGIHQmAQbihSYYmQRW7XPFECvE95ef.png',

        // Pasta & rice
        'truffle-pasta' => 'menu_images/food/Breakfast/Adgajc7sHdEwi9ps5779AkDvZdk9adb.png',
        'spaghetti-bolognese' => 'menu_images/food/Pasta_Rice/HR7ognw2vTxDqAYZmQ7MallTc8eaf.png',
        'jollof-rice-chicken' => 'menu_images/food/Mains/UZFPFW9xADeRKuk1YMNlP1go2g09de1.png',
        'fried-rice-special' => 'menu_images/food/Pasta_Rice/GK7Drv2DaovG1tQzxvkYX25G6s94d0.png',
        'coconut-rice' => 'menu_images/food/Mains/wt3dCjlSD1mSmklm53aSSl45rT81aa0.png',

        // Desserts (folder is milkshake/sundae stock — items renamed in catalog)
        'cookies-cream-shake' => 'menu_images/dessert/fC0z2CX335ZNJUU4Xyw2OOEdr1cfcb2.png',
        'strawberry-milkshake' => 'menu_images/dessert/gLvHLW3xiTwejbQ0JbshMynYmEY9257.png',
        'banana-split' => 'menu_images/dessert/7NWBsbWQEzKf75ZTq3J0ePxvdncf2f4.png',
        'vanilla-milkshake' => 'menu_images/dessert/9kTdbwVDpQNpY4XS6J3U8UU3RId11e.png',
        'milkshake-flight' => 'menu_images/dessert/ZB0gfHTN5GC77mVnvI5P6r7Bi05b20.png',

        // Cocktails
        'old-fashioned' => 'menu_images/drinks/cocktails/p0gqlmE4HF894DMDLzVmFkzxSjUf70e.png',
        'mojito' => 'menu_images/drinks/cocktails/6dHdEkU3vgTQQlwuPKlKVXsc6Md829.png',
        'margarita' => 'menu_images/drinks/cocktails/iuH47BXTeI9dyLgGmloDrMDHQAca6b.png',
        'nigerian-chapman' => 'menu_images/drinks/cocktails/BxL6gaMmFKTaCkSOtqBXxnaSOO8399f.png',

        // Wine & beer (labeled filenames)
        'budweiser' => 'menu_images/drinks/Wine_Beer/budwiser.png',
        'desperado' => 'menu_images/drinks/Wine_Beer/desperado.jpg',
        'guinness-stout' => 'menu_images/drinks/Wine_Beer/Guinness_Stout.jpg',
        'gulder' => 'menu_images/drinks/Wine_Beer/Gulder.jpg',
        'power-horse' => 'menu_images/drinks/Wine_Beer/power_horse.webp',
        'red-bull' => 'menu_images/drinks/Wine_Beer/redbull.png',
        'smirnoff-ice' => 'menu_images/drinks/Wine_Beer/Smirnoff_Ice.jpg',

        // Soft drinks (labeled filenames)
        'active-chivita' => 'menu_images/drinks/soft_drinks/Active_chivita_1ltr.png',
        'chi-exotic' => 'menu_images/drinks/soft_drinks/chiexotic_can.png',
        'coca-cola' => 'menu_images/drinks/soft_drinks/cocacola_can.png',
        'fanta' => 'menu_images/drinks/soft_drinks/fanta_can.png',
        'hollandia-yoghurt' => 'menu_images/drinks/soft_drinks/hollandia_youghurt_1ltr.png',
        'malta-guinness' => 'menu_images/drinks/soft_drinks/malta_guinness.png',
        'sprite' => 'menu_images/drinks/soft_drinks/sprite_can.png',

        // Coffee (tea items left unmapped — no tea photos)
        'espresso' => 'menu_images/drinks/coffee/8ttRECWpZfuRQ1oHV1MVSKyYes05af1.png',
        'cappuccino' => 'menu_images/drinks/coffee/8KNT0Cmwadj1pOqUf8eYtJvgUWw60b7.png',
        'latte' => 'menu_images/drinks/coffee/BX92mo8g3WwakuX0vDRaMOsOw9cd0.png',
    ],
];
