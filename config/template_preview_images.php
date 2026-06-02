<?php

/**
 * Maps template preview demo menu items/categories to filenames in public/assets/images/.
 * Templates request /assets/images/menu-items/{file} — .htaccess serves public/assets/images/{file}.
 */
return [
    'exclude' => [
        'J1U9v8PQYXunzG3Q5rB7HLBD06Afe58.png',
        '5OJq2dwgB4xWCNrVWIAeSDfQ.jpg',
        'lk7RXLJ4pHNPVVFRfXm4UhB9Xkfe56.jpg',
        'rwKUkCKwjsoZZ9XUXj6RFj8o90e591.jpg',
        'woman_work.jpg',
        'README.md',
        '2aEJrliv9HetcjnP4DyMNiaU01cc.png',
        'UlvYo24xqH8e9HUjmgdV6Hm88Ns7a92.png',
    ],

    /** Hero / cover images for template preview (rotated by template id). */
    'covers' => [
        '5qRm87VW5lLs5bHzJRlcNQHJTg95ef.png',
        'TSx2MrnudIy3hZawlNIf0XkE6b0c215.jpg',
        'HOIsucbufEFhwCtRr1P9wANmC3Ua5eb.png',
        'KOi6q7BqMN9oUvYaqIvCLXfaKRce591.png',
        'NAZyQCjsYof8AIS1k4Srbx20s6fe9.jpg',
        'VmGagXikz2YMKcEmsvHw5tGlk6fe9.png',
        'Gce3Rrhr6Fu2ulj19xPmIrv3b5g6fe9.jpg',
        '8KNT0Cmwadj1pOqUf8eYtJvgUWw60b7.png',
    ],

    /** Category slug => cover image filename. */
    'categories' => [
        'starters' => 'Gce3Rrhr6Fu2ulj19xPmIrv3b5g6fe9.jpg',
        'salads' => 'Q4NLIWJFP2p1o0D77zfQC8cbesM9adb.png',
        'mains' => '5qRm87VW5lLs5bHzJRlcNQHJTg95ef.png',
        'pasta-rice' => 'MM8tb8UFdtdejnJ1CUUDFE5xgEbb74.png',
        'grill' => 'TSx2MrnudIy3hZawlNIf0XkE6b0c215.jpg',
        'sides' => 'YcO87k7zeSnBW0rBux9THPmA78e591.png',
        'desserts' => 'fC0z2CX335ZNJUU4Xyw2OOEdr1cfcb2.png',
        'pastries' => 'SYItS4KF8H7Q2DW6d6GQpZCeZQI5b20.png',
        'cocktails' => 'fIL7dvfGFqFWNmraqhqORlF3mCU6fe9.png',
        'wine-beer' => 'RHnIPJEf74MtzfQzEP1ofrsWTJU1b3b.png',
        'soft-drinks' => 'Ga5n1CPt11gw5lYs25aY3N1bUg39c2.png',
        'coffee-tea' => '8KNT0Cmwadj1pOqUf8eYtJvgUWw60b7.png',
    ],

    /**
     * Menu item slug (from item name) => image filename in public/assets/images/.
     */
    'items' => [
        'bruschetta-trio' => 'mQIrPVbm53DrgvAZw6upKF0RAw6c73.png',
        'chicken-wings' => '9X7XaUT5RWMUxM47WyN9iYKnY6fe9.jpg',
        'prawn-cocktail' => '5zm3C5SMKk7sgdYHRUP5eAlb86fe9.jpg',
        'soup-of-the-day' => 'fFvoDJ6sQmXVjr75rkpNXaMQJAa4eb.png',
        'calamari-fritti' => 'gEejqwJbsPJeGjh5sNoNIkoU7iIa6db.png',

        'caesar-salad' => 'Q4NLIWJFP2p1o0D77zfQC8cbesM9adb.png',
        'greek-salad' => 'rcrYUjSmISVYGy7EOHdtNBFtcgA9adb.png',
        'avocado-quinoa' => 'OevATMoe3hwy6lgCtWIuHJhvqV04788.png',
        'grilled-chicken-salad' => 'OevATMoe3hwy6lgCtWIuHJhvqV04788.png',

        'grilled-salmon' => 'i1M2A7kIuLgGSd2ZjgCUSy1yCus6a7c.png',
        'ribeye-steak' => 'HOIsucbufEFhwCtRr1P9wANmC3Ua5eb.png',
        'herb-roast-chicken' => 'KOi6q7BqMN9oUvYaqIvCLXfaKRce591.png',
        'lamb-shank' => 'T5AIpvo9Y5ugqKVu5ZQuCWHs1Mw6fce.png',
        'seafood-paella' => 'pAS3RnPIobyebHSssDpEgrAXxIEdee8.png',
        'vegan-buddha-bowl' => 'zIGIHQmAQbihSYYmQRW7XPFECvE95ef.png',

        'truffle-pasta' => 'Adgajc7sHdEwi9ps5779AkDvZdk9adb.png',
        'spaghetti-bolognese' => 'MM8tb8UFdtdejnJ1CUUDFE5xgEbb74.png',
        'jollof-rice-chicken' => '5qRm87VW5lLs5bHzJRlcNQHJTg95ef.png',
        'fried-rice-special' => 'zIGIHQmAQbihSYYmQRW7XPFECvE95ef.png',
        'coconut-rice' => 'VrEMHGr0t7YAgJWsvaXSQ54RVms5fcf.png',

        'classic-beef-burger' => 'VmGagXikz2YMKcEmsvHw5tGlk6fe9.png',
        'bbq-chicken-burger' => 'IgXdrkh7KSpIYghk1pxRbOlznPQ6fe9.png',
        'grilled-prawns' => 'm4OoyrTpDAcHEFbi8mZby7tgbgc6aa.png',
        'mixed-grill-platter' => 'TSx2MrnudIy3hZawlNIf0XkE6b0c215.jpg',

        'truffle-fries' => 'VAk9zR3aPcWmMZYOeb0TSYO8CAwe591.png',
        'plantain-chips' => 'fVp9qhhehkg9hbOqHe6DUsJm0I9257.png',
        'steamed-vegetables' => '87fhYpSB4hmsMJbq7Zm6eRb9lwM9adb.png',
        'coleslaw' => 'rcrYUjSmISVYGy7EOHdtNBFtcgA9adb.png',

        'chocolate-lava-cake' => 'fC0z2CX335ZNJUU4Xyw2OOEdr1cfcb2.png',
        'new-york-cheesecake' => 'yBFIDpAeU4h5XemVids71JhD44788.png',
        'tiramisu' => 'L1XCFcBrP0QeWIw5bXER0Ifi3Mf94a.png',
        'fruit-salad' => 'MfjnIgk58Ko5AbqCO3whneZRBVI9adb.png',
        'ice-cream-scoop' => 'yBFIDpAeU4h5XemVids71JhD44788.png',

        'red-velvet-slice' => 'pioy3pE6BDBdsfZGJCj5IRNqQus18ae.png',
        'carrot-cake' => 'rGkRwBKOai3qVKejVemFAvkvHWc6fce.png',
        'croissant' => 'mjXo18z3xyxc92W6uxmt2s0cGfQ9adb.png',
        'puff-puff-basket' => 'Gce3Rrhr6Fu2ulj19xPmIrv3b5g6fe9.jpg',

        'old-fashioned' => 'DCunEmX9MKKNpPH5EguS8THL5I4c801.png',
        'mojito' => 'fIL7dvfGFqFWNmraqhqORlF3mCU6fe9.png',
        'margarita' => 'f8vjySnXXC9CF2vwfXdtMK4gvIeb3b.png',
        'nigerian-chapman' => 'BxL6gaMmFKTaCkSOtqBXxnaSOO8399f.png',

        'house-red' => 'DCunEmX9MKKNpPH5EguS8THL5I4c801.png',
        'house-white' => 'BxL6gaMmFKTaCkSOtqBXxnaSOO8399f.png',
        'craft-lager' => 'RHnIPJEf74MtzfQzEP1ofrsWTJU1b3b.png',
        'imported-beer' => 'RHnIPJEf74MtzfQzEP1ofrsWTJU1b3b.png',

        'fresh-lemonade' => 'Ga5n1CPt11gw5lYs25aY3N1bUg39c2.png',
        'chapman-pitcher' => 'BxL6gaMmFKTaCkSOtqBXxnaSOO8399f.png',
        'bottled-water' => 'IMhdBxZKdY4pIytcSePsOG5V4Bkc67a.png',
        'ginger-ale' => 'OiXlT88BpqIU3WgJ9ZYRVgV8fFUdf64.png',

        'espresso' => 'mX7KFl9IRESVsgUWlA1QoQZITCce316.png',
        'cappuccino' => 'L1XCFcBrP0QeWIw5bXER0Ifi3Mf94a.png',
        'latte' => '8KNT0Cmwadj1pOqUf8eYtJvgUWw60b7.png',
        'english-breakfast' => 'mX7KFl9IRESVsgUWlA1QoQZITCce316.png',
        'green-tea' => 'Ga5n1CPt11gw5lYs25aY3N1bUg39c2.png',
    ],
];
