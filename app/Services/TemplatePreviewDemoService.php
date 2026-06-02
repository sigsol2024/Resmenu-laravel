<?php

namespace App\Services;

use App\Support\LegacyMenuViewData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Rich sample menu for /templates/{id}/preview (marketing demos).
 * Images are mapped explicitly to files in public/assets/images/ (see config/template_preview_images.php).
 */
class TemplatePreviewDemoService
{
    private const PREVIEW_MENU_ITEMS_BASE = '/assets/images/menu-items';

    public function __construct(
        private UploadService $uploads,
        private CustomizationService $customization,
    ) {}

    public function buildPayload(int $templateId): array
    {
        $templateId = max(1, $templateId);
        $imageMap = config('template_preview_images', []);
        $uploadBaseUrl = rtrim((string) config('app.url'), '/').self::PREVIEW_MENU_ITEMS_BASE;

        $sections = $this->buildSections($imageMap);
        $sectionsForNav = array_map(static fn (array $s) => [
            'id' => $s['id'],
            'name' => $s['name'],
            'slug' => $s['slug'],
        ], $sections);

        $templateRow = DB::table('templates')->where('id', $templateId)->where('is_active', 1)->first();
        $templateName = $templateRow->name ?? ('Template '.$templateId);
        $heroUrl = ! empty($templateRow->preview_image)
            ? $this->uploads->publicUrl('template-previews', $templateRow->preview_image)
            : $this->previewCoverUrl($templateId, $imageMap);

        $restaurant = LegacyMenuViewData::normalizeRestaurant([
            'id' => 0,
            'name' => $templateName.' — Demo',
            'slug' => 'template-preview',
            'logo' => null,
            'description' => 'Live template preview with a full sample menu. Your branding, items, and photos replace this demo content.',
            'template_id' => $templateId,
            'header_menu_items' => null,
            'address' => '123 Sample Street, Victoria Island, Lagos',
            'phone' => '+234 800 000 0000',
            'email' => 'hello@yourrestaurant.com',
            'hero_image' => null,
            'hero_image_url' => $heroUrl,
            'footer_content' => 'This is a demonstration menu for the '.$templateName.' design. Sign up to publish your own menu with ordering and reservations.',
            'google_rating' => 4.8,
            'rating_source' => 'Google',
            'opening_hours' => "Mon–Thu: 11:00 – 22:00\nFri–Sat: 11:00 – 23:00\nSun: 12:00 – 21:00",
            'instagram_url' => 'https://instagram.com',
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://twitter.com',
            'whatsapp_link' => 'https://wa.me/2348000000000',
            'enable_food_ordering' => true,
            'enable_table_reservations' => true,
        ], rtrim(config('resmenu.upload_url'), '/'));

        $customization = $this->customization->templateDefaultsForPreview($templateId);

        return LegacyMenuViewData::normalize([
            'restaurant' => $restaurant,
            'sections' => $sections,
            'customization' => $customization,
            'headerMenuItems' => [],
            'singleSectionView' => false,
            'fullMenuUrl' => url('/templates/'.$templateId.'/preview'),
            'sectionsForNav' => $sectionsForNav,
            'uploadBaseUrl' => $uploadBaseUrl,
            'templateAssetBaseUrl' => url('/templates/template'.$templateId),
            'template4BaseUrl' => url('/templates/template4'),
            'supportsOrdering' => true,
            'supportsReservations' => true,
            'reservationUrl' => '#',
            'isTemplatePreview' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $imageMap
     * @return list<array<string, mixed>>
     */
    private function buildSections(array $imageMap): array
    {
        $catalog = $this->menuCatalog();
        $itemImages = $imageMap['items'] ?? [];
        $categoryImages = $imageMap['categories'] ?? [];
        $itemId = 1;
        $catId = 1;
        $sectionId = 1;
        $sections = [];

        foreach ($catalog as $sectionDef) {
            $categories = [];
            foreach ($sectionDef['categories'] as $catDef) {
                $menuItems = [];
                $order = 1;
                foreach ($catDef['items'] as $itemDef) {
                    $slug = Str::slug($itemDef['name']);
                    $menuItems[] = [
                        'id' => $itemId++,
                        'name' => $itemDef['name'],
                        'description' => $itemDef['description'],
                        'price' => $itemDef['price'],
                        'image' => $this->menuItemImageRef($slug, $itemImages, $categoryImages, $catDef['slug']),
                        'display_order' => $order++,
                        'is_available' => 1,
                    ];
                }

                $categories[] = [
                    'id' => $catId++,
                    'name' => $catDef['name'],
                    'slug' => $catDef['slug'],
                    'image' => $this->categoryImageRef($catDef['slug'], $categoryImages),
                    'menu_items' => $menuItems,
                    'is_active' => 1,
                    'display_order' => count($categories) + 1,
                ];
            }

            $sections[] = [
                'id' => $sectionId++,
                'name' => $sectionDef['name'],
                'slug' => $sectionDef['slug'],
                'display_order' => count($sections) + 1,
                'is_active' => 1,
                'image' => null,
                'categories' => $categories,
            ];
        }

        return $sections;
    }

    /**
     * @param  array<string, string>  $itemImages
     * @param  array<string, string>  $categoryImages
     */
    private function menuItemImageRef(string $itemSlug, array $itemImages, array $categoryImages, string $categorySlug): ?string
    {
        $filename = $itemImages[$itemSlug] ?? $categoryImages[$categorySlug] ?? null;
        if ($filename === null || $filename === '') {
            return null;
        }

        return $filename;
    }

    /** @param  array<string, string>  $categoryImages */
    private function categoryImageRef(string $categorySlug, array $categoryImages): ?string
    {
        $filename = $categoryImages[$categorySlug] ?? $categoryImages['mains'] ?? null;
        if ($filename === null || $filename === '') {
            return null;
        }

        return $filename;
    }

    /** @param  array<string, mixed>  $imageMap */
    private function previewCoverUrl(int $templateId, array $imageMap): string
    {
        $covers = $imageMap['covers'] ?? ['5qRm87VW5lLs5bHzJRlcNQHJTg95ef.png'];
        $covers = array_values(array_filter($covers, 'is_string'));
        if ($covers === []) {
            return url('/templates/template'.$templateId.'/cover.jpg');
        }

        $filename = $covers[($templateId - 1) % count($covers)];

        return asset('assets/images/'.$filename);
    }

    /**
     * Shared demo menu (~52 items) used for every template preview.
     *
     * @return list<array{name: string, slug: string, categories: list<array<string, mixed>>}>
     */
    private function menuCatalog(): array
    {
        return [
            [
                'name' => 'Food',
                'slug' => 'food',
                'categories' => [
                    $this->cat('Starters & Small Plates', 'starters', $this->items([
                        ['Bruschetta Trio', 'Toasted ciabatta, tomato basil relish, balsamic glaze', 4500],
                        ['Chicken Wings', 'Crispy wings, house spice rub, blue cheese dip', 6500],
                        ['Prawn Cocktail', 'Atlantic prawns, Marie Rose, buttered brioche', 8500],
                        ['Soup of the Day', 'Chef’s seasonal blend, served with artisan bread', 3800],
                        ['Calamari Fritti', 'Lightly fried squid, lemon aioli, pickled chili', 7200],
                    ])),
                    $this->cat('Salads', 'salads', $this->items([
                        ['Caesar Salad', 'Romaine, parmesan, croutons, classic dressing', 5500],
                        ['Greek Salad', 'Feta, olives, cucumber, oregano vinaigrette', 5200],
                        ['Avocado & Quinoa', 'Mixed leaves, cherry tomato, citrus dressing', 6800],
                        ['Grilled Chicken Salad', 'Herb chicken, avocado, honey mustard', 7500],
                    ])),
                    $this->cat('Mains', 'mains', $this->items([
                        ['Grilled Salmon', 'Pan-seared fillet, seasonal vegetables, dill butter', 18500],
                        ['Ribeye Steak', '300g Angus ribeye, peppercorn sauce, fries', 24500],
                        ['Herb Roast Chicken', 'Half bird, rosemary jus, roasted roots', 14500],
                        ['Lamb Shank', 'Slow-braised, red wine gravy, creamy mash', 16800],
                        ['Seafood Paella', 'Prawns, mussels, saffron rice, lemon', 19200],
                        ['Vegan Buddha Bowl', 'Roasted veg, chickpeas, tahini, grains', 9800],
                    ])),
                    $this->cat('Pasta & Rice', 'pasta-rice', $this->items([
                        ['Truffle Pasta', 'Fresh tagliatelle, mushroom cream, parmesan', 12500],
                        ['Spaghetti Bolognese', 'Slow-cooked beef ragu, aged cheese', 9800],
                        ['Jollof Rice & Chicken', 'Smoky party jollof, grilled quarter leg', 8500],
                        ['Fried Rice Special', 'Wok-fried rice, prawns, vegetables, soy', 9200],
                        ['Coconut Rice', 'Fragrant rice, curry leaf, grilled fish', 11000],
                    ])),
                    $this->cat('Grill & Burgers', 'grill', $this->items([
                        ['Classic Beef Burger', 'Angus patty, cheddar, pickles, brioche bun', 9500],
                        ['BBQ Chicken Burger', 'Slaw, smoked cheddar, crispy onions', 8800],
                        ['Grilled Prawns', 'Garlic butter, charred lemon, herb salad', 16500],
                        ['Mixed Grill Platter', 'Assorted meats, sauces, grilled vegetables', 22000],
                    ])),
                    $this->cat('Sides', 'sides', $this->items([
                        ['Truffle Fries', 'Parmesan, truffle oil, parsley', 4200],
                        ['Plantain Chips', 'Sweet plantain, spicy dip', 3500],
                        ['Steamed Vegetables', 'Seasonal greens, herb butter', 3800],
                        ['Coleslaw', 'Creamy house slaw', 2800],
                    ])),
                ],
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'categories' => [
                    $this->cat('Desserts', 'desserts', $this->items([
                        ['Chocolate Lava Cake', 'Warm fondant, vanilla ice cream', 5500],
                        ['New York Cheesecake', 'Berry compote, biscuit base', 5200],
                        ['Tiramisu', 'Espresso soak, mascarpone, cocoa', 4800],
                        ['Fruit Salad', 'Seasonal fruit, mint syrup', 3500],
                        ['Ice Cream Scoop', 'Vanilla, chocolate, or strawberry', 2800],
                    ])),
                    $this->cat('Pastries', 'pastries', $this->items([
                        ['Red Velvet Slice', 'Cream cheese frosting', 4200],
                        ['Carrot Cake', 'Walnuts, cream cheese icing', 4500],
                        ['Croissant', 'Butter laminated, served warm', 2500],
                        ['Puff Puff Basket', 'Nigerian dough bites, cinnamon sugar', 3200],
                    ])),
                ],
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'categories' => [
                    $this->cat('Cocktails', 'cocktails', $this->items([
                        ['Old Fashioned', 'Bourbon, bitters, orange twist', 7500],
                        ['Mojito', 'White rum, mint, lime, soda', 6500],
                        ['Margarita', 'Tequila, triple sec, fresh lime', 6800],
                        ['Nigerian Chapman', 'Fruit punch, Angostura, cucumber', 4500],
                    ])),
                    $this->cat('Wine & Beer', 'wine-beer', $this->items([
                        ['House Red', 'Glass — smooth merlot blend', 5500],
                        ['House White', 'Glass — crisp sauvignon', 5500],
                        ['Craft Lager', '330ml — local brewery', 3500],
                        ['Imported Beer', '330ml — premium lager', 4200],
                    ])),
                    $this->cat('Soft Drinks', 'soft-drinks', $this->items([
                        ['Fresh Lemonade', 'House-made, mint optional', 2500],
                        ['Chapman Pitcher', 'Sharing size fruit punch', 8500],
                        ['Bottled Water', 'Still or sparkling 75cl', 1500],
                        ['Ginger Ale', 'Premium ginger beer', 2200],
                    ])),
                    $this->cat('Coffee & Tea', 'coffee-tea', $this->items([
                        ['Espresso', 'Double shot', 1800],
                        ['Cappuccino', 'Steamed milk, cocoa dust', 2800],
                        ['Latte', 'Smooth espresso, velvety milk', 3000],
                        ['English Breakfast', 'Pot for two, milk on side', 2200],
                        ['Green Tea', 'Jasmine or mint', 2000],
                    ])),
                ],
            ],
        ];
    }

    /**
     * @param  list<array{0: string, 1: string, 2: int}>  $rows
     * @return list<array{name: string, description: string, price: int}>
     */
    private function items(array $rows): array
    {
        $out = [];
        foreach ($rows as [$name, $description, $price]) {
            $out[] = ['name' => $name, 'description' => $description, 'price' => $price];
        }

        return $out;
    }

    /**
     * @param  list<array{name: string, description: string, price: int}>  $items
     * @return array<string, mixed>
     */
    private function cat(string $name, string $slug, array $items): array
    {
        return [
            'name' => $name,
            'slug' => $slug,
            'items' => $items,
        ];
    }
}
