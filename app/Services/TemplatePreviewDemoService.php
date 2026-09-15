<?php

namespace App\Services;

use App\Support\LegacyMenuViewData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Rich sample menu for /templates/{id}/preview (marketing demos).
 * Images live under public/templates/preview_images/ (see config/template_preview_images.php).
 */
class TemplatePreviewDemoService
{
    private const PREVIEW_ASSETS_BASE = '/templates/preview_images';

    public function __construct(
        private CustomizationService $customization,
    ) {}

    /**
     * @param  bool  $fullCatalogue  When true, skip T6 home stripping (needed for section preview URLs).
     */
    public function buildPayload(int $templateId, bool $fullCatalogue = false): array
    {
        $templateId = max(1, $templateId);
        $imageMap = config('template_preview_images', []);
        $uploadBaseUrl = rtrim((string) config('app.url'), '/').self::PREVIEW_ASSETS_BASE;

        $sections = $this->buildSections($imageMap);
        $sectionsForNav = array_map(static fn (array $s) => [
            'id' => $s['id'],
            'name' => $s['name'],
            'slug' => $s['slug'],
        ], $sections);

        $templateRow = DB::table('templates')->where('id', $templateId)->where('is_active', 1)->first();
        $templateName = $templateRow->name ?? ('Template '.$templateId);

        $coverRel = (string) ($imageMap['restaurant_cover'] ?? 'demo_restaurant_coverimage.jpg');
        $heroUrl = $uploadBaseUrl.'/'.ltrim($coverRel, '/');
        $heroFilename = basename($coverRel);

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
            'hero_image' => $heroFilename,
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
        ], $uploadBaseUrl);

        // Keep absolute cover URL (normalizeRestaurant may rebuild from /heroes/{filename}).
        $restaurant['hero_image_url'] = $heroUrl;
        $restaurant['hero_image'] = $heroFilename;

        $customization = $this->customization->templateDefaultsForPreview($templateId);

        $sections = LegacyMenuViewData::normalizeSections($sections);

        $previewBase = url('/templates/'.$templateId.'/preview');

        $payload = LegacyMenuViewData::normalize([
            'restaurant' => $restaurant,
            'sections' => $sections,
            'categories' => LegacyMenuViewData::flattenCategoriesFromSections($sections),
            'customization' => $customization,
            'headerMenuItems' => [],
            'singleSectionView' => false,
            'fullMenuUrl' => $previewBase,
            'sectionsForNav' => $sectionsForNav,
            'uploadBaseUrl' => $uploadBaseUrl,
            'templateAssetBaseUrl' => url('/templates/template'.$templateId),
            'template4BaseUrl' => url('/templates/template4'),
            'supportsOrdering' => false,
            'supportsReservations' => true,
            'reservationUrl' => $previewBase.'#reservation',
            'isTemplatePreview' => true,
            'menuViewLevel' => 'home',
            'activeSection' => null,
            'activeCategory' => null,
            'sectionMenuUrl' => null,
            'categoryMenuUrl' => null,
            'popularItems' => [],
            'reservationFormData' => $this->demoReservationFormData($previewBase),
        ]);

        // Keep shared demo cover URL after normalize (do not rebuild as /heroes/{basename}).
        if (isset($payload['restaurant']) && is_array($payload['restaurant'])) {
            $payload['restaurant']['hero_image_url'] = $heroUrl;
            $payload['restaurant']['hero_image'] = $heroFilename;
        }

        if ($templateId === 6 && ! $fullCatalogue) {
            $popular = [];
            foreach ($sections as $section) {
                foreach ($section['categories'] ?? [] as $category) {
                    foreach ($category['menu_items'] ?? [] as $item) {
                        $popular[] = $item;
                    }
                }
            }
            $payload['popularItems'] = LegacyMenuViewData::normalizeMenuItems(array_slice($popular, 0, 3));
            $homeSections = [];
            foreach ($sections as $section) {
                $cats = [];
                foreach ($section['categories'] ?? [] as $category) {
                    if (empty($category['menu_items'])) {
                        continue;
                    }
                    $catCopy = $category;
                    unset($catCopy['menu_items']);
                    $cats[] = $catCopy;
                }
                if ($cats !== []) {
                    $sectionCopy = $section;
                    $sectionCopy['categories'] = $cats;
                    $homeSections[] = $sectionCopy;
                }
            }
            $payload['sections'] = $homeSections;
            $payload['categories'] = LegacyMenuViewData::flattenCategoriesFromSections($homeSections);
        }

        return $payload;
    }

    /** @return array<string, mixed> */
    private function demoReservationFormData(string $previewBase): array
    {
        $selectedDate = date('Y-m-d');
        $slots = [];
        foreach (['17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00'] as $t) {
            $slots[] = ['time' => $t, 'label' => $t, 'available' => true];
        }

        return [
            'csrfToken' => csrf_token(),
            'actionUrl' => route('public.template.preview.reservation'),
            'slug' => 'template-preview',
            'depositAmount' => 0,
            'selectedDate' => $selectedDate,
            'minDate' => $selectedDate,
            'timeSlots' => $slots,
            'primaryColor' => '#f0be78',
            'siteBase' => rtrim(url('/'), '/'),
            'isDemo' => true,
        ];
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
        $sectionImages = $imageMap['sections'] ?? [];
        $itemId = 1;
        $catId = 1;
        $sectionId = 1;
        $sections = [];

        foreach ($catalog as $sectionDef) {
            $categories = [];
            $itemCount = 0;
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
                        'image' => $this->menuItemImageRef($slug, $itemImages),
                        'display_order' => $order++,
                        'is_available' => 1,
                    ];
                }
                $itemCount += count($menuItems);

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

            $secSlug = $sectionDef['slug'];
            $sections[] = [
                'id' => $sectionId++,
                'name' => $sectionDef['name'],
                'slug' => $secSlug,
                'description' => $sectionDef['description'] ?? '',
                'display_order' => count($sections) + 1,
                'is_active' => 1,
                'image' => $this->sectionImageRef($secSlug, $sectionImages, $categoryImages, $categories),
                'item_count' => $itemCount,
                'categories' => $categories,
            ];
        }

        return $sections;
    }

    /** @param  array<string, string>  $itemImages */
    private function menuItemImageRef(string $itemSlug, array $itemImages): ?string
    {
        $filename = $itemImages[$itemSlug] ?? null;
        if ($filename === null || $filename === '') {
            return null;
        }

        return $filename;
    }

    /** @param  array<string, string>  $categoryImages */
    private function categoryImageRef(string $categorySlug, array $categoryImages): ?string
    {
        $filename = $categoryImages[$categorySlug] ?? null;
        if ($filename === null || $filename === '') {
            return null;
        }

        return $filename;
    }

    /**
     * @param  array<string, string>  $sectionImages
     * @param  array<string, string>  $categoryImages
     * @param  list<array<string, mixed>>  $categories
     */
    private function sectionImageRef(string $sectionSlug, array $sectionImages, array $categoryImages, array $categories): ?string
    {
        if (! empty($sectionImages[$sectionSlug])) {
            return $sectionImages[$sectionSlug];
        }
        foreach ($categories as $cat) {
            $img = $cat['image'] ?? null;
            if (is_string($img) && $img !== '') {
                return $img;
            }
            $slug = (string) ($cat['slug'] ?? '');
            if ($slug !== '' && ! empty($categoryImages[$slug])) {
                return $categoryImages[$slug];
            }
        }

        return null;
    }

    /**
     * Demo catalogue — only categories that exist under preview_images/menu_images/.
     *
     * @return list<array{name: string, slug: string, description?: string, categories: list<array<string, mixed>>}>
     */
    private function menuCatalog(): array
    {
        return [
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'From breakfast plates to grilled favourites.',
                'categories' => [
                    $this->cat('Breakfast', 'breakfast', $this->items([
                        ['Sunrise Scramble', 'Eggs, toast, roasted tomato, house relish', 6200],
                        ['Pancake Stack', 'Maple butter, seasonal berries', 5800],
                        ['Nigerian Breakfast Plate', 'Yam, eggs, pepper sauce, plantain', 7500],
                    ])),
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
                    $this->cat('Wraps & Snacks', 'wraps', $this->items([
                        ['Chicken Wrap', 'Grilled chicken, greens, yogurt dressing', 6900],
                        ['Veggie Wrap', 'Roasted peppers, hummus, crisp lettuce', 6100],
                        ['Club Sandwich', 'Triple-stack turkey, bacon, tomato', 8200],
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
                ],
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'description' => 'Sweet finishes.',
                'categories' => [
                    $this->cat('Desserts', 'desserts', $this->items([
                        ['Cookies & Cream Shake', 'Oreo blend, whipped cream', 4500],
                        ['Strawberry Milkshake', 'Fresh strawberry, whipped cream', 4200],
                        ['Banana Split', 'Ice cream, nuts, cherries', 5500],
                        ['Vanilla Milkshake', 'Classic vanilla, sprinkles', 4000],
                        ['Milkshake Flight', 'Three signature shakes to share', 9800],
                    ])),
                ],
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'description' => 'Cocktails, beer, soft drinks, and coffee.',
                'categories' => [
                    $this->cat('Cocktails', 'cocktails', $this->items([
                        ['Old Fashioned', 'Bourbon, bitters, orange twist', 7500],
                        ['Mojito', 'White rum, mint, lime, soda', 6500],
                        ['Margarita', 'Tequila, triple sec, fresh lime', 6800],
                        ['Nigerian Chapman', 'Fruit punch, Angostura, cucumber', 4500],
                    ])),
                    $this->cat('Wine & Beer', 'wine-beer', $this->items([
                        ['Budweiser', '330ml lager', 3500],
                        ['Desperado', '330ml tequila-flavoured beer', 4000],
                        ['Guinness Stout', '330ml', 3800],
                        ['Gulder', '330ml lager', 3200],
                        ['Power Horse', 'Energy drink 250ml', 2800],
                        ['Red Bull', 'Energy drink 250ml', 3000],
                        ['Smirnoff Ice', '275ml', 4200],
                    ])),
                    $this->cat('Soft Drinks', 'soft-drinks', $this->items([
                        ['Active Chivita', '1 litre fruit juice', 2500],
                        ['Chi Exotic', 'Can — tropical blend', 1800],
                        ['Coca-Cola', 'Can', 1500],
                        ['Fanta', 'Can', 1500],
                        ['Hollandia Yoghurt', '1 litre', 2800],
                        ['Malta Guinness', 'Non-alcoholic malt', 2000],
                        ['Sprite', 'Can', 1500],
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
