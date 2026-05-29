<?php

declare(strict_types=1);

/**
 * Emit slug-based sync SQL from parsed dump data.
 */
final class SyncSqlGenerator
{
    /** @var array<string, list<array<string, mixed>>> */
    private array $data;

    /** @var array<int, string> prod restaurant id => slug */
    private array $restaurantSlugByProdId = [];

    /** @var array<int, array<string, mixed>> prod restaurant id => row */
    private array $restaurantByProdId = [];

    /** @var array<int, string> prod section id => slug */
    private array $sectionSlugByProdId = [];

    /** @var array<int, int> prod section id => prod restaurant id */
    private array $sectionRestaurantByProdId = [];

    /** @var array<int, string> prod category id => slug */
    private array $categorySlugByProdId = [];

    /** @var array<int, int> prod category id => prod restaurant id */
    private array $categoryRestaurantByProdId = [];

    /** @var array<int, string> prod menu_item id => slug */
    private array $menuItemSlugByProdId = [];

    /** @var list<string> */
    private array $warnings = [];

    /** @var array<string, array<string, int>> */
    private array $expected = [];

    /**
     * @param  array<string, list<array<string, mixed>>>  $data
     * @param  array<string, mixed>  $options
     */
    public function __construct(array $data, private array $options)
    {
        $this->data = $data;
        $this->buildMaps();
    }

    /**
     * @return array{files: array<string, string>, expected: array<string, array<string, int>>, warnings: list<string>}
     */
    public function generate(): array
    {
        $this->warnings = [];
        $this->expected = [];

        if ($this->options['dry_run'] ?? false) {
            foreach ($this->activeRestaurants() as $restaurant) {
                $this->buildExpectedCounts($restaurant);
            }

            return [
                'files' => [],
                'expected' => $this->expected,
                'warnings' => $this->warnings,
            ];
        }

        $files = [];
        $restaurants = $this->activeRestaurants();
        $partNames = [];

        $platform = $this->emitPlatformBlock();
        $files['sync_part_00_platform.sql'] = $platform;
        $partNames[] = 'sync_part_00_platform.sql';

        $index = 1;
        foreach ($restaurants as $restaurant) {
            $slug = (string) $restaurant['slug'];
            $safeSlug = preg_replace('/[^a-z0-9-]/', '-', $slug) ?: 'restaurant';
            $filename = sprintf('sync_part_%02d_%s.sql', $index, $safeSlug);
            $files[$filename] = $this->emitRestaurantBlock($restaurant);
            $partNames[] = $filename;
            $index++;
        }

        $footer = $this->emitFooter();
        $lastFile = array_key_last($files);
        if ($lastFile !== null) {
            $files[$lastFile] .= "\n".$footer;
        }

        $readme = implode("\n", [
            '-- Run order:',
            ...array_map(static fn (string $f) => '--   '.$f, $partNames),
        ]);
        $files['sync_part_00_platform.sql'] = str_replace(
            '-- RUN_ORDER_PLACEHOLDER',
            $readme,
            $files['sync_part_00_platform.sql']
        );

        return [
            'files' => $files,
            'expected' => $this->expected,
            'warnings' => $this->warnings,
        ];
    }

    private function buildMaps(): void
    {
        foreach ($this->rows('restaurants') as $row) {
            $id = (int) $row['id'];
            $this->restaurantByProdId[$id] = $row;
            $this->restaurantSlugByProdId[$id] = (string) $row['slug'];
        }

        foreach ($this->rows('sections') as $row) {
            $id = (int) $row['id'];
            $this->sectionSlugByProdId[$id] = (string) $row['slug'];
            $this->sectionRestaurantByProdId[$id] = (int) $row['restaurant_id'];
        }

        foreach ($this->rows('categories') as $row) {
            $id = (int) $row['id'];
            $this->categorySlugByProdId[$id] = (string) $row['slug'];
            $this->categoryRestaurantByProdId[$id] = (int) $row['restaurant_id'];
        }

        foreach ($this->rows('menu_items') as $row) {
            $this->menuItemSlugByProdId[(int) $row['id']] = (string) $row['slug'];
        }
    }

    /** @return list<array<string, mixed>> */
    private function activeRestaurants(): array
    {
        $active = [];
        foreach ($this->rows('restaurants') as $row) {
            if ((int) ($row['is_active'] ?? 0) === 1) {
                $active[] = $row;
            }
        }

        usort($active, static fn (array $a, array $b) => strcmp((string) $a['slug'], (string) $b['slug']));

        return $active;
    }

    /** @return list<array<string, mixed>> */
    private function rows(string $table): array
    {
        return $this->data[$table] ?? [];
    }

    /** @param  array<string, mixed>  $restaurant */
    private function buildExpectedCounts(array $restaurant): void
    {
        $prodId = (int) $restaurant['id'];
        $slug = (string) $restaurant['slug'];

        $this->expected[$slug] = [
            'sections' => count($this->filterByRestaurant('sections', $prodId)),
            'categories' => count($this->filterByRestaurant('categories', $prodId)),
            'menu_items' => count($this->filterByRestaurant('menu_items', $prodId)),
            'orders' => count($this->filterByRestaurant('orders', $prodId)),
            'reservations' => count($this->filterByRestaurant('table_reservations', $prodId)),
            'payments' => count($this->filterByRestaurant('payments', $prodId)),
        ];
    }

    /** @return list<array<string, mixed>> */
    private function filterByRestaurant(string $table, int $prodRestaurantId): array
    {
        return array_values(array_filter(
            $this->rows($table),
            static fn (array $row) => (int) ($row['restaurant_id'] ?? 0) === $prodRestaurantId
        ));
    }

    private function emitPlatformBlock(): string
    {
        $lines = [
            '-- Sync from live production dump → Laravel Resmenu',
            '-- Generated: '.date('c'),
            '-- Source: '.basename((string) $this->options['source']),
            '-- Source MD5: '.($this->options['source_hash'] ?? ''),
            '-- Source size: '.number_format((int) ($this->options['source_size'] ?? 0)).' bytes',
            '-- BACKUP YOUR DATABASE BEFORE IMPORTING.',
            '-- RUN_ORDER_PLACEHOLDER',
            '',
            'SET NAMES utf8mb4;',
            'SET FOREIGN_KEY_CHECKS=0;',
            'SET SQL_MODE=\'NO_AUTO_VALUE_ON_ZERO\';',
            'SET @force_passwords = '.($this->options['force_passwords'] ? '1' : '0').';',
            '',
            'START TRANSACTION;',
        ];

        $lines = array_merge($lines, $this->emitReplaceTable('subscription_plans', [
            'id', 'name', 'slug', 'description', 'monthly_price', 'annual_price', 'yearly_discount_percent',
            'max_categories', 'max_menu_items', 'max_qr_styles', 'max_templates', 'features', 'is_active',
            'display_order', 'created_at', 'updated_at',
        ]));

        $lines = array_merge($lines, $this->emitReplaceTable('templates', [
            'id', 'name', 'slug', 'description', 'preview_image', 'listing_image', 'is_active', 'is_private',
            'created_at', 'updated_at',
        ]));

        $lines = array_merge($lines, $this->emitReplaceTable('template_customizations', [
            'id', 'template_id', 'menu_title_color', 'menu_title_size', 'menu_title_font', 'price_color',
            'price_size', 'price_font', 'description_color', 'description_size', 'description_font',
            'category_title_color', 'category_title_size', 'category_title_font', 'background_color',
            'header_background_color', 'primary_color', 'secondary_color', 'created_at', 'updated_at',
        ]));

        $lines = array_merge($lines, $this->emitDeleteAll('template_plans'));
        $lines = array_merge($lines, $this->emitInsertRows('template_plans', $this->rows('template_plans'), [
            'template_id', 'plan_id',
        ]));

        $lines = array_merge($lines, $this->emitReplaceTable('qr_templates', [
            'id', 'name', 'description', 'preview_image', 'has_text', 'config_json', 'is_active',
            'created_at', 'updated_at',
        ]));

        foreach ($this->rows('site_settings') as $row) {
            $sets = [];
            foreach ($row as $col => $val) {
                if ($col === 'id') {
                    continue;
                }
                $sets[] = "`{$col}` = ".$this->sqlValue($val);
            }
            $lines[] = 'UPDATE `site_settings` SET '.implode(', ', $sets).' WHERE `id` = '.(int) $row['id'].';';
        }

        foreach ($this->rows('payment_settings') as $row) {
            $gateway = $this->sqlValue($row['gateway']);
            $cols = [
                'gateway', 'is_active', 'test_mode', 'public_key_live', 'secret_key_live', 'webhook_secret_live',
                'public_key_test', 'secret_key_test', 'webhook_secret_test', 'created_at', 'updated_at',
            ];
            $insertCols = implode(', ', array_map(static fn ($c) => "`{$c}`", $cols));
            $insertVals = implode(', ', array_map(fn ($c) => $this->sqlValue($row[$c] ?? null), $cols));
            $updates = implode(', ', array_map(
                static fn ($c) => $c === 'gateway' ? "`{$c}` = `{$c}`" : "`{$c}` = VALUES(`{$c}`)",
                array_filter($cols, static fn ($c) => $c !== 'gateway')
            ));
            $lines[] = "INSERT INTO `payment_settings` ({$insertCols}) VALUES ({$insertVals}) ON DUPLICATE KEY UPDATE {$updates};";
            unset($gateway);
        }

        foreach ($this->rows('admins') as $row) {
            $lines[] = 'INSERT INTO `admins` (`username`, `email`, `password_hash`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    $this->sqlValue($row['username']),
                    $this->sqlValue($row['email']),
                    $this->sqlValue($row['password_hash']),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).') ON DUPLICATE KEY UPDATE '.
                '`username` = VALUES(`username`), '.
                '`password_hash` = IF(@force_passwords = 1, VALUES(`password_hash`), `password_hash`), '.
                '`updated_at` = VALUES(`updated_at`);';
        }

        $lines[] = 'DELETE FROM `template_restaurants`;';
        foreach ($this->rows('template_restaurants') as $row) {
            $prodRid = (int) $row['restaurant_id'];
            $slug = $this->restaurantSlugByProdId[$prodRid] ?? null;
            if ($slug === null) {
                $this->warnings[] = "template_restaurants references unknown restaurant_id {$prodRid}";
                continue;
            }
            $lines[] = 'INSERT INTO `template_restaurants` (`template_id`, `restaurant_id`)'.
                ' SELECT '.(int) $row['template_id'].', r.id FROM `restaurants` r'.
                ' WHERE r.slug = '.$this->sqlValue($slug).' LIMIT 1;';
        }

        $lines[] = 'COMMIT;';
        $lines[] = '';

        return implode("\n", $lines);
    }

    /** @param  array<string, mixed>  $restaurant */
    private function emitRestaurantBlock(array $restaurant): string
    {
        $prodId = (int) $restaurant['id'];
        $slug = (string) $restaurant['slug'];
        $this->buildExpectedCounts($restaurant);
        $counts = $this->expected[$slug];

        $lines = [
            '-- Restaurant: '.$slug.' (prod_restaurant_id='.$prodId.')',
            sprintf(
                '-- EXPECTED: sections=%d categories=%d menu_items=%d orders=%d reservations=%d payments=%d',
                $counts['sections'],
                $counts['categories'],
                $counts['menu_items'],
                $counts['orders'],
                $counts['reservations'],
                $counts['payments']
            ),
            '',
            'SET @rid = (SELECT id FROM `restaurants` WHERE slug = '.$this->sqlValue($slug).' LIMIT 1);',
        ];

        $insertCols = [
            'name', 'slug', 'logo', 'hero_image', 'description', 'phone', 'email', 'address', 'website',
            'whatsapp_link', 'instagram_url', 'facebook_url', 'twitter_url', 'enable_food_ordering',
            'enable_table_reservations', 'map_latitude', 'map_longitude', 'header_menu_items', 'footer_content',
            'manager_email', 'google_rating', 'rating_source', 'template_id', 'is_active',
            'available_items_count', 'unavailable_items_count', 'created_at', 'updated_at',
        ];
        $insertVals = implode(', ', array_map(fn ($c) => $this->sqlValue($restaurant[$c] ?? null), $insertCols));
        $insertColList = implode(', ', array_map(static fn ($c) => "`{$c}`", $insertCols));
        $lines[] = "INSERT INTO `restaurants` ({$insertColList}) SELECT {$insertVals} FROM DUAL WHERE @rid IS NULL;";
        $lines[] = 'SET @rid = (SELECT id FROM `restaurants` WHERE slug = '.$this->sqlValue($slug).' LIMIT 1);';
        $lines[] = '-- WARNING: skip destructive sync if slug still missing after insert attempt';
        $lines[] = '';

        $updateSets = [];
        foreach ($insertCols as $col) {
            if ($col === 'slug') {
                continue;
            }
            $updateSets[] = "`{$col}` = ".$this->sqlValue($restaurant[$col] ?? null);
        }
        $lines[] = 'UPDATE `restaurants` SET '.implode(', ', $updateSets).
            ' WHERE id = @rid AND @rid IS NOT NULL;';
        $lines[] = '';
        $lines[] = 'START TRANSACTION;';

        foreach ($this->filterByRestaurant('managers', $prodId) as $row) {
            $pwUpdate = '@force_passwords = 1, `password_hash` = VALUES(`password_hash`), ';
            $lines[] = 'INSERT INTO `managers` (`username`, `email`, `password_hash`, `restaurant_id`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    $this->sqlValue($row['username']),
                    $this->sqlValue($row['email']),
                    $this->sqlValue($row['password_hash']),
                    '@rid',
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).') ON DUPLICATE KEY UPDATE '.
                '`username` = VALUES(`username`), '.
                '`restaurant_id` = @rid, '.
                '`password_hash` = IF(@force_passwords = 1, VALUES(`password_hash`), `password_hash`), '.
                '`updated_at` = VALUES(`updated_at`);';
            unset($pwUpdate);
        }

        $lines[] = 'DELETE FROM `subscriptions` WHERE restaurant_id = @rid AND @rid IS NOT NULL;';
        $subscriptionRows = $this->filterByRestaurant('subscriptions', $prodId);
        foreach ($subscriptionRows as $sub) {
            $lines[] = 'INSERT INTO `subscriptions` (`restaurant_id`, `plan_id`, `billing_cycle`, `status`, `trial_ends_at`, `current_period_start`, `current_period_end`, `cancelled_at`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    (int) $sub['plan_id'],
                    $this->sqlValue($sub['billing_cycle']),
                    $this->sqlValue($sub['status']),
                    $this->sqlValue($sub['trial_ends_at']),
                    $this->sqlValue($sub['current_period_start']),
                    $this->sqlValue($sub['current_period_end']),
                    $this->sqlValue($sub['cancelled_at']),
                    $this->sqlValue($sub['created_at']),
                    $this->sqlValue($sub['updated_at']),
                ]).');';
        }
        $lines[] = 'SET @sub_id = (SELECT id FROM `subscriptions` WHERE restaurant_id = @rid ORDER BY id DESC LIMIT 1);';
        $lines[] = 'UPDATE `restaurants` SET `subscription_id` = @sub_id WHERE id = @rid AND @sub_id IS NOT NULL;';

        $lines[] = 'DELETE FROM `customization_settings` WHERE restaurant_id = @rid AND @rid IS NOT NULL;';
        foreach ($this->filterByRestaurant('customization_settings', $prodId) as $row) {
            $lines[] = 'INSERT INTO `customization_settings` (`restaurant_id`, `template_id`, `menu_title_color`, `menu_title_size`, `menu_title_font`, `price_color`, `price_size`, `price_font`, `description_color`, `description_size`, `description_font`, `category_title_color`, `category_title_size`, `category_title_font`, `background_color`, `header_background_color`, `primary_color`, `secondary_color`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    (int) $row['template_id'],
                    $this->sqlValue($row['menu_title_color']),
                    $this->sqlValue($row['menu_title_size']),
                    $this->sqlValue($row['menu_title_font']),
                    $this->sqlValue($row['price_color']),
                    $this->sqlValue($row['price_size']),
                    $this->sqlValue($row['price_font']),
                    $this->sqlValue($row['description_color']),
                    $this->sqlValue($row['description_size']),
                    $this->sqlValue($row['description_font']),
                    $this->sqlValue($row['category_title_color']),
                    $this->sqlValue($row['category_title_size']),
                    $this->sqlValue($row['category_title_font']),
                    $this->sqlValue($row['background_color']),
                    $this->sqlValue($row['header_background_color']),
                    $this->sqlValue($row['primary_color']),
                    $this->sqlValue($row['secondary_color']),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).');';
        }

        $lines[] = 'DELETE FROM `restaurant_payment_settings` WHERE restaurant_id = @rid AND @rid IS NOT NULL;';
        foreach ($this->filterByRestaurant('restaurant_payment_settings', $prodId) as $row) {
            $lines[] = 'INSERT INTO `restaurant_payment_settings` (`restaurant_id`, `gateway`, `is_active`, `test_mode`, `public_key_test`, `secret_key_test`, `webhook_secret_test`, `public_key_live`, `secret_key_live`, `webhook_secret_live`, `bank_name`, `account_number`, `account_name`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    $this->sqlValue($row['gateway']),
                    (int) ($row['is_active'] ?? 0),
                    (int) ($row['test_mode'] ?? 0),
                    $this->sqlValue($row['public_key_test']),
                    $this->sqlValue($row['secret_key_test']),
                    $this->sqlValue($row['webhook_secret_test']),
                    $this->sqlValue($row['public_key_live']),
                    $this->sqlValue($row['secret_key_live']),
                    $this->sqlValue($row['webhook_secret_live']),
                    $this->sqlValue($row['bank_name']),
                    $this->sqlValue($row['account_number']),
                    $this->sqlValue($row['account_name']),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).');';
        }

        $lines[] = 'DELETE FROM `restaurant_qr_codes` WHERE restaurant_id = @rid AND @rid IS NOT NULL;';
        foreach ($this->filterByRestaurant('restaurant_qr_codes', $prodId) as $row) {
            $lines[] = 'INSERT INTO `restaurant_qr_codes` (`restaurant_id`, `qr_template_id`, `override_json`, `final_config_json`, `background_color`, `qr_color`, `text_content`, `text_color`, `text_size`, `text_font`, `qr_size`, `margin`, `is_active`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    $this->sqlIntOrNull($row['qr_template_id'] ?? null),
                    $this->sqlValue($row['override_json']),
                    $this->sqlValue($row['final_config_json']),
                    $this->sqlValue($row['background_color']),
                    $this->sqlValue($row['qr_color']),
                    $this->sqlValue($row['text_content']),
                    $this->sqlValue($row['text_color']),
                    $this->sqlValue($row['text_size']),
                    $this->sqlValue($row['text_font']),
                    $this->sqlValue($row['qr_size']),
                    (int) ($row['margin'] ?? 0),
                    (int) ($row['is_active'] ?? 1),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).');';
        }

        foreach ($this->filterByRestaurant('restaurant_reservation_settings', $prodId) as $row) {
            $lines[] = 'INSERT INTO `restaurant_reservation_settings` (`restaurant_id`, `deposit_amount`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    $this->sqlValue($row['deposit_amount']),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).') ON DUPLICATE KEY UPDATE `deposit_amount` = VALUES(`deposit_amount`), `updated_at` = VALUES(`updated_at`);';
        }

        $lines = array_merge($lines, $this->emitMenuDeletes());
        $lines = array_merge($lines, $this->emitMenuInserts($prodId));
        $lines = array_merge($lines, $this->emitOrderHistory($prodId));
        $lines = array_merge($lines, $this->emitReservations($prodId));
        $lines = array_merge($lines, $this->emitPendingPayments($prodId));
        $lines = array_merge($lines, $this->emitPaymentUpserts($prodId));

        if ($this->options['include_inventory'] ?? false) {
            $lines = array_merge($lines, $this->emitTableInventory($prodId));
        }

        if ($this->options['include_scans'] ?? false) {
            $lines = array_merge($lines, $this->emitQrScans($prodId));
        }

        $lines[] = 'COMMIT;';
        $lines[] = '';

        return implode("\n", $lines);
    }

    /** @return list<string> */
    private function emitMenuDeletes(): array
    {
        return [
            'DELETE css FROM `category_secondary_sections` css',
            'INNER JOIN `categories` c ON c.id = css.category_id',
            'WHERE c.restaurant_id = @rid AND @rid IS NOT NULL;',
            'DELETE FROM `menu_items` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
            'DELETE FROM `categories` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
            'DELETE FROM `sections` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
            '',
        ];
    }

    /** @return list<string> */
    private function emitMenuInserts(int $prodRestaurantId): array
    {
        $lines = [];

        foreach ($this->filterByRestaurant('sections', $prodRestaurantId) as $row) {
            $lines[] = 'INSERT INTO `sections` (`restaurant_id`, `name`, `slug`, `image`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    $this->sqlValue($row['name']),
                    $this->sqlValue($row['slug']),
                    $this->sqlValue($row['image']),
                    (int) ($row['display_order'] ?? 0),
                    (int) ($row['is_active'] ?? 1),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).');';
        }

        foreach ($this->filterByRestaurant('categories', $prodRestaurantId) as $row) {
            $sectionSlug = $this->sectionSlugByProdId[(int) $row['section_id']] ?? null;
            if ($sectionSlug === null) {
                $this->warnings[] = "Category {$row['slug']} references missing section_id {$row['section_id']}";
                continue;
            }
            $lines[] = 'INSERT INTO `categories` (`restaurant_id`, `section_id`, `name`, `slug`, `image`, `description`, `display_order`, `is_active`, `created_at`, `updated_at`)'.
                ' SELECT @rid, s.id, '.$this->sqlValue($row['name']).', '.$this->sqlValue($row['slug']).', '.
                $this->sqlValue($row['image']).', '.$this->sqlValue($row['description']).', '.
                (int) ($row['display_order'] ?? 0).', '.(int) ($row['is_active'] ?? 1).', '.
                $this->sqlValue($row['created_at']).', '.$this->sqlValue($row['updated_at']).
                ' FROM `sections` s WHERE s.restaurant_id = @rid AND s.slug = '.$this->sqlValue($sectionSlug).' LIMIT 1;';
        }

        $cssRows = array_filter(
            $this->rows('category_secondary_sections'),
            function (array $row) use ($prodRestaurantId) {
                $catId = (int) $row['category_id'];
                return ($this->categoryRestaurantByProdId[$catId] ?? null) === $prodRestaurantId;
            }
        );
        foreach ($cssRows as $row) {
            $catSlug = $this->categorySlugByProdId[(int) $row['category_id']] ?? null;
            $secSlug = $this->sectionSlugByProdId[(int) $row['section_id']] ?? null;
            if ($catSlug === null || $secSlug === null) {
                continue;
            }
            $lines[] = 'INSERT INTO `category_secondary_sections` (`category_id`, `section_id`, `is_active`, `created_at`, `updated_at`)'.
                ' SELECT c.id, s.id, '.(int) ($row['is_active'] ?? 1).', '.
                $this->sqlValue($row['created_at']).', '.$this->sqlValue($row['updated_at']).
                ' FROM `categories` c INNER JOIN `sections` s ON s.restaurant_id = @rid'.
                ' WHERE c.restaurant_id = @rid AND c.slug = '.$this->sqlValue($catSlug).
                ' AND s.slug = '.$this->sqlValue($secSlug).' LIMIT 1;';
        }

        foreach ($this->filterByRestaurant('menu_items', $prodRestaurantId) as $row) {
            $catSlug = $this->categorySlugByProdId[(int) $row['category_id']] ?? null;
            if ($catSlug === null) {
                $this->warnings[] = "Menu item {$row['slug']} references missing category_id {$row['category_id']}";
                continue;
            }
            $lines[] = 'INSERT INTO `menu_items` (`restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`)'.
                ' SELECT @rid, c.id, '.$this->sqlValue($row['name']).', '.$this->sqlValue($row['slug']).', '.
                $this->sqlValue($row['description']).', '.$this->sqlValue($row['price']).', '.
                $this->sqlValue($row['image']).', '.(int) ($row['display_order'] ?? 0).', '.
                (int) ($row['is_available'] ?? 1).', '.$this->sqlValue($row['created_at']).', '.
                $this->sqlValue($row['updated_at']).
                ' FROM `categories` c WHERE c.restaurant_id = @rid AND c.slug = '.$this->sqlValue($catSlug).' LIMIT 1;';
        }

        $lines[] = '';

        return $lines;
    }

    /** @return list<string> */
    private function emitOrderHistory(int $prodRestaurantId): array
    {
        $lines = [
            'DELETE oi FROM `order_items` oi INNER JOIN `orders` o ON o.id = oi.order_id WHERE o.restaurant_id = @rid AND @rid IS NOT NULL;',
            'DELETE FROM `orders` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
        ];

        $orders = $this->filterByRestaurant('orders', $prodRestaurantId);
        $orderItemsByProdOrderId = [];
        foreach ($this->rows('order_items') as $item) {
            $orderItemsByProdOrderId[(int) $item['order_id']][] = $item;
        }

        foreach ($orders as $order) {
            $prodOrderId = (int) $order['id'];
            $lines[] = 'INSERT INTO `orders` (`order_number`, `restaurant_id`, `customer_name`, `customer_phone`, `customer_email`, `delivery_address`, `payment_method`, `status`, `subtotal`, `delivery_fee`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    $this->sqlValue($order['order_number']),
                    '@rid',
                    $this->sqlValue($order['customer_name']),
                    $this->sqlValue($order['customer_phone']),
                    $this->sqlValue($order['customer_email']),
                    $this->sqlValue($order['delivery_address']),
                    $this->sqlValue($order['payment_method']),
                    $this->sqlValue($order['status']),
                    $this->sqlValue($order['subtotal']),
                    $this->sqlValue($order['delivery_fee']),
                    $this->sqlValue($order['tax']),
                    $this->sqlValue($order['total']),
                    $this->sqlValue($order['created_at']),
                    $this->sqlValue($order['updated_at']),
                ]).');';
            $lines[] = 'SET @oid = LAST_INSERT_ID();';

            foreach ($orderItemsByProdOrderId[$prodOrderId] ?? [] as $item) {
                $menuSlug = $this->menuItemSlugByProdId[(int) $item['menu_item_id']] ?? null;
                if ($menuSlug === null) {
                    $this->warnings[] = "order_item id {$item['id']} references unmapped menu_item_id {$item['menu_item_id']}";
                    continue;
                }
                $menuIdExpr = '(SELECT mi.id FROM `menu_items` mi WHERE mi.restaurant_id = @rid AND mi.slug = '.
                    $this->sqlValue($menuSlug).' LIMIT 1)';
                $lines[] = 'INSERT INTO `order_items` (`order_id`, `menu_item_id`, `name`, `price`, `quantity`, `created_at`) VALUES ('.
                    '@oid, '.$menuIdExpr.', '.
                    $this->sqlValue($item['name']).', '.
                    $this->sqlValue($item['price']).', '.
                    (int) ($item['quantity'] ?? 1).', '.
                    $this->sqlValue($item['created_at']).
                    ');';
            }
        }

        if ($orders !== []) {
            $lines[] = '';
        }

        return $lines;
    }

    /** @return list<string> */
    private function emitReservations(int $prodRestaurantId): array
    {
        $lines = [
            'DELETE FROM `table_reservations` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
        ];

        foreach ($this->filterByRestaurant('table_reservations', $prodRestaurantId) as $row) {
            $lines[] = 'INSERT INTO `table_reservations` (`reservation_number`, `restaurant_id`, `reservation_date`, `reservation_time`, `party_size`, `guest_name`, `guest_email`, `guest_phone`, `special_occasion`, `notes`, `deposit_amount`, `deposit_paid`, `status`, `is_walkin`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    $this->sqlValue($row['reservation_number']),
                    '@rid',
                    $this->sqlValue($row['reservation_date']),
                    $this->sqlValue($row['reservation_time']),
                    (int) ($row['party_size'] ?? 1),
                    $this->sqlValue($row['guest_name']),
                    $this->sqlValue($row['guest_email']),
                    $this->sqlValue($row['guest_phone']),
                    $this->sqlValue($row['special_occasion']),
                    $this->sqlValue($row['notes']),
                    $this->sqlValue($row['deposit_amount']),
                    (int) ($row['deposit_paid'] ?? 0),
                    $this->sqlValue($row['status']),
                    (int) ($row['is_walkin'] ?? 0),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).');';
        }

        if ($this->filterByRestaurant('table_reservations', $prodRestaurantId) !== []) {
            $lines[] = '';
        }

        return $lines;
    }

    /** @return list<string> */
    private function emitPendingPayments(int $prodRestaurantId): array
    {
        $lines = [
            'DELETE FROM `pending_bank_transfers` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
            'DELETE FROM `pending_online_payments` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
        ];

        foreach ($this->filterByRestaurant('pending_bank_transfers', $prodRestaurantId) as $row) {
            $lines[] = 'INSERT INTO `pending_bank_transfers` (`token`, `restaurant_id`, `payment_type`, `reservation_id`, `cart_json`, `customer_name`, `customer_phone`, `customer_email`, `delivery_address`, `subtotal`, `delivery_fee`, `tax`, `total`, `created_at`) VALUES ('.
                implode(', ', [
                    $this->sqlValue($row['token']),
                    '@rid',
                    $this->sqlValue($row['payment_type']),
                    $this->sqlValue($row['reservation_id']),
                    $this->sqlValue($row['cart_json']),
                    $this->sqlValue($row['customer_name']),
                    $this->sqlValue($row['customer_phone']),
                    $this->sqlValue($row['customer_email']),
                    $this->sqlValue($row['delivery_address']),
                    $this->sqlValue($row['subtotal']),
                    $this->sqlValue($row['delivery_fee']),
                    $this->sqlValue($row['tax']),
                    $this->sqlValue($row['total']),
                    $this->sqlValue($row['created_at']),
                ]).');';
        }

        foreach ($this->filterByRestaurant('pending_online_payments', $prodRestaurantId) as $row) {
            $lines[] = 'INSERT INTO `pending_online_payments` (`reference`, `restaurant_id`, `payment_type`, `reservation_id`, `gateway`, `cart_json`, `customer_name`, `customer_phone`, `customer_email`, `delivery_address`, `subtotal`, `delivery_fee`, `tax`, `total`, `created_at`) VALUES ('.
                implode(', ', [
                    $this->sqlValue($row['reference']),
                    '@rid',
                    $this->sqlValue($row['payment_type']),
                    $this->sqlValue($row['reservation_id']),
                    $this->sqlValue($row['gateway']),
                    $this->sqlValue($row['cart_json']),
                    $this->sqlValue($row['customer_name']),
                    $this->sqlValue($row['customer_phone']),
                    $this->sqlValue($row['customer_email']),
                    $this->sqlValue($row['delivery_address']),
                    $this->sqlValue($row['subtotal']),
                    $this->sqlValue($row['delivery_fee']),
                    $this->sqlValue($row['tax']),
                    $this->sqlValue($row['total']),
                    $this->sqlValue($row['created_at']),
                ]).');';
        }

        return $lines;
    }

    /** @return list<string> */
    private function emitPaymentUpserts(int $prodRestaurantId): array
    {
        $lines = [];

        foreach ($this->filterByRestaurant('payments', $prodRestaurantId) as $row) {
            $ref = $row['transaction_reference'] ?? null;
            $notExists = $ref !== null && $ref !== ''
                ? 'NOT EXISTS (SELECT 1 FROM `payments` p WHERE p.restaurant_id = @rid AND p.transaction_reference = '.$this->sqlValue($ref).')'
                : 'NOT EXISTS (SELECT 1 FROM `payments` p WHERE p.restaurant_id = @rid AND p.transaction_reference IS NULL AND p.amount = '.
                    $this->sqlValue($row['amount']).' AND p.created_at = '.$this->sqlValue($row['created_at']).
                    ' AND p.payment_gateway = '.$this->sqlValue($row['payment_gateway']).')';

            $lines[] = 'INSERT INTO `payments` (`restaurant_id`, `subscription_id`, `amount`, `currency`, `payment_gateway`, `transaction_reference`, `gateway_response`, `status`, `paid_at`, `created_at`)'.
                ' SELECT @rid, @sub_id, '.
                $this->sqlValue($row['amount']).', '.
                $this->sqlValue($row['currency']).', '.
                $this->sqlValue($row['payment_gateway']).', '.
                $this->sqlValue($row['transaction_reference']).', '.
                $this->sqlValue($row['gateway_response']).', '.
                $this->sqlValue($row['status']).', '.
                $this->sqlValue($row['paid_at']).', '.
                $this->sqlValue($row['created_at']).
                ' FROM DUAL WHERE @rid IS NOT NULL AND @sub_id IS NOT NULL AND '.$notExists.';';
        }

        if ($this->filterByRestaurant('payments', $prodRestaurantId) !== []) {
            $lines[] = '';
        }

        return $lines;
    }

    /** @return list<string> */
    private function emitTableInventory(int $prodRestaurantId): array
    {
        $lines = [
            'DELETE FROM `table_inventory_daily` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
        ];

        foreach ($this->filterByRestaurant('table_inventory_daily', $prodRestaurantId) as $row) {
            $lines[] = 'INSERT INTO `table_inventory_daily` (`restaurant_id`, `inventory_date`, `total_tables`, `created_at`, `updated_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    $this->sqlValue($row['inventory_date']),
                    (int) ($row['total_tables'] ?? 0),
                    $this->sqlValue($row['created_at']),
                    $this->sqlValue($row['updated_at']),
                ]).');';
        }

        $lines[] = '';

        return $lines;
    }

    /** @return list<string> */
    private function emitQrScans(int $prodRestaurantId): array
    {
        $lines = [
            'DELETE FROM `qr_code_scans` WHERE restaurant_id = @rid AND @rid IS NOT NULL;',
        ];

        foreach ($this->filterByRestaurant('qr_code_scans', $prodRestaurantId) as $row) {
            $lines[] = 'INSERT INTO `qr_code_scans` (`restaurant_id`, `ip_address`, `user_agent`, `device_type`, `browser`, `os`, `country`, `city`, `latitude`, `longitude`, `scanned_at`) VALUES ('.
                implode(', ', [
                    '@rid',
                    $this->sqlValue($row['ip_address']),
                    $this->sqlValue($row['user_agent']),
                    $this->sqlValue($row['device_type']),
                    $this->sqlValue($row['browser']),
                    $this->sqlValue($row['os']),
                    $this->sqlValue($row['country']),
                    $this->sqlValue($row['city']),
                    $this->sqlValue($row['latitude']),
                    $this->sqlValue($row['longitude']),
                    $this->sqlValue($row['scanned_at']),
                ]).');';
        }

        $lines[] = '';

        return $lines;
    }

    /** @param  list<string>  $columns */
    private function emitReplaceTable(string $table, array $columns): array
    {
        $lines = [];
        foreach ($this->rows($table) as $row) {
            $colList = implode(', ', array_map(static fn ($c) => "`{$c}`", $columns));
            $vals = implode(', ', array_map(fn ($c) => $this->sqlValue($row[$c] ?? null), $columns));
            $lines[] = "REPLACE INTO `{$table}` ({$colList}) VALUES ({$vals});";
        }
        if ($lines !== []) {
            $lines[] = '';
        }

        return $lines;
    }

    /** @param  list<array<string, mixed>>  $rows @param  list<string>  $columns */
    private function emitInsertRows(string $table, array $rows, array $columns): array
    {
        $lines = [];
        foreach ($rows as $row) {
            $colList = implode(', ', array_map(static fn ($c) => "`{$c}`", $columns));
            $vals = implode(', ', array_map(fn ($c) => $this->sqlValue($row[$c] ?? null), $columns));
            $lines[] = "INSERT INTO `{$table}` ({$colList}) VALUES ({$vals});";
        }
        if ($lines !== []) {
            $lines[] = '';
        }

        return $lines;
    }

    /** @return list<string> */
    private function emitDeleteAll(string $table): array
    {
        return ["DELETE FROM `{$table}`;", ''];
    }

    private function emitFooter(): string
    {
        return implode("\n", [
            '-- End of sync import',
            'SET FOREIGN_KEY_CHECKS=1;',
        ]);
    }

    private function sqlIntOrNull(mixed $value): string
    {
        if ($value === null || $value === '') {
            return 'NULL';
        }

        return (string) (int) $value;
    }

    private function sqlValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        $str = (string) $value;
        $str = str_replace(["\\", "'", "\0", "\n", "\r", "\x1a"], ["\\\\", "\\'", "\\0", "\\n", "\\r", "\\Z"], $str);

        return "'{$str}'";
    }
}
