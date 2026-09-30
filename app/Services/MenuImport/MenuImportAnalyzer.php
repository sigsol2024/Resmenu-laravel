<?php

namespace App\Services\MenuImport;

use App\Services\SubscriptionService;
use Illuminate\Support\Facades\DB;

/**
 * Read-only analysis of draft rows against the restaurant's current menu.
 * Matching is hierarchy-aware: a category is matched only inside its section
 * and an item only inside its category, by exact normalized name.
 */
final class MenuImportAnalyzer
{
    /**
     * Stored field lengths: one past each validation limit, so an over-long value
     * still fails validation instead of being silently cut to a valid length.
     */
    private const MAX_NAME_CHARS = 256;

    private const MAX_DESCRIPTION_CHARS = MenuImportRowRules::MAX_DESCRIPTION_BYTES + 1;

    private const MAX_PRICE_CHARS = 100;

    public function __construct(private SubscriptionService $subscriptions) {}

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return array<string, mixed>
     */
    public function analyze(int $restaurantId, array $rows): array
    {
        $rows = self::sanitizeRows($rows);
        $existing = $this->loadExisting($restaurantId);

        $out = [];
        $sections = [];
        $categories = [];
        $attention = [];
        $excluded = [];

        foreach ($rows as $row) {
            $id = $row['id'];
            $entry = $row + ['status' => null, 'errors' => [], 'messages' => [], 'existing' => null, 'changes' => [], 'price_value' => null];

            if ($row['excluded']) {
                $entry['status'] = 'excluded';
                $out[$id] = $entry;
                $excluded[] = $id;

                continue;
            }

            $validation = MenuImportRowRules::validate($row);
            $entry['errors'] = $validation['errors'];
            $entry['price_value'] = $validation['price'];
            if ($row['parse_error']) {
                $entry['errors'] = ['row' => $row['parse_error']] + $entry['errors'];
            }

            if (isset($entry['errors']['section']) || isset($entry['errors']['category'])) {
                $entry['status'] = 'attention';
                $out[$id] = $entry;
                $attention[] = $id;

                continue;
            }

            $sectionName = self::cleanName($row['section']);
            $sectionKey = self::normalize($sectionName);
            if (! isset($sections[$sectionKey])) {
                $sections[$sectionKey] = ['key' => $sectionKey, 'name' => $sectionName, 'spellings' => [], 'category_keys' => []];
            }
            $sections[$sectionKey]['spellings'][$sectionName][] = $row['row_number'];

            $categoryName = self::cleanName($row['category']);
            $categoryKey = $sectionKey.'|'.self::normalize($categoryName);
            if (! isset($categories[$categoryKey])) {
                $categories[$categoryKey] = ['key' => $categoryKey, 'section_key' => $sectionKey, 'name' => $categoryName, 'spellings' => [], 'row_ids' => []];
                $sections[$sectionKey]['category_keys'][] = $categoryKey;
            }
            $categories[$categoryKey]['spellings'][$categoryName][] = $row['row_number'];
            $categories[$categoryKey]['row_ids'][] = $id;

            $out[$id] = $entry;
        }

        foreach ($sections as $key => $section) {
            $sections[$key] = $this->resolveSection($section, $existing);
        }
        foreach ($categories as $key => $category) {
            $categories[$key] = $this->resolveCategory($category, $sections[$category['section_key']], $existing);
        }
        foreach ($categories as $category) {
            $this->resolveItems($category, $sections[$category['section_key']], $existing, $out);
        }

        $summary = $this->summarize($sections, $categories, $out);
        $globalErrors = $this->limitErrors($restaurantId, $summary);

        $blocking = count($globalErrors);
        foreach ($out as $entry) {
            if ($entry['errors'] !== []) {
                $blocking++;
            }
        }

        $plan = $this->buildPlan($sections, $categories, $out);
        $hasChanges = $summary['sections']['new'] + $summary['categories']['new'] + $summary['items']['new'] + $summary['items']['update'] > 0;

        $tree = [];
        foreach ($sections as $section) {
            $node = self::publicNode($section);
            $node['categories'] = [];
            foreach ($section['category_keys'] as $categoryKey) {
                $categoryNode = self::publicNode($categories[$categoryKey]);
                $categoryNode['row_ids'] = $categories[$categoryKey]['row_ids'];
                $node['categories'][] = $categoryNode;
            }
            $tree[] = $node;
        }

        $publicRows = [];
        foreach ($out as $id => $entry) {
            unset($entry['price_value'], $entry['existing_id']);
            $publicRows[$id] = $entry;
        }

        $revision = hash('sha256', json_encode([
            $restaurantId,
            array_map(fn ($r) => [$r['id'], $r['section'], $r['category'], $r['name'], $r['description'], $r['price'], $r['excluded'], $r['parse_error']], $rows),
            $plan,
            $summary,
            $globalErrors,
        ]));

        return [
            'revision' => $revision,
            'rows' => $publicRows,
            'row_order' => array_column($rows, 'id'),
            'tree' => $tree,
            'attention' => $attention,
            'excluded' => $excluded,
            'errors' => $globalErrors,
            'warnings' => $this->collectWarnings($sections, $categories, $out),
            'summary' => $summary,
            'blocking' => $blocking,
            'has_changes' => $hasChanges,
            'can_import' => $blocking === 0 && $hasChanges,
            'existing_categories' => $this->existingCategoryOptions($existing),
            'plan' => $plan,
        ];
    }

    /** Analysis without the server-only write plan. */
    public static function forClient(array $analysis): array
    {
        unset($analysis['plan']);

        return $analysis;
    }

    /**
     * Keeps only known fields with the expected types. Rows from the browser go
     * through this too, so nothing else (IDs, restaurant) can be smuggled in.
     *
     * @param  list<mixed>  $rows
     * @return list<array{id: string, row_number: int, section: string, category: string, name: string, description: string, price: string, excluded: bool, parse_error: ?string}>
     */
    public static function sanitizeRows(array $rows): array
    {
        $clean = [];
        $seen = [];
        $max = MenuImportCsvReader::maxRows();
        foreach (array_values($rows) as $index => $row) {
            if (! is_array($row) || count($clean) >= $max) {
                continue;
            }
            $id = is_string($row['id'] ?? null) && preg_match('/^[A-Za-z0-9_-]{1,40}$/', $row['id']) ? $row['id'] : 'x'.$index;
            if (isset($seen[$id])) {
                $id = 'x'.$index.'_'.count($seen);
            }
            $seen[$id] = true;

            $clean[] = [
                'id' => $id,
                'row_number' => max(0, (int) ($row['row_number'] ?? 0)),
                'section' => self::cell($row['section'] ?? '', self::MAX_NAME_CHARS),
                'category' => self::cell($row['category'] ?? '', self::MAX_NAME_CHARS),
                'name' => self::cell($row['name'] ?? '', self::MAX_NAME_CHARS),
                'description' => self::cell($row['description'] ?? '', self::MAX_DESCRIPTION_CHARS),
                'price' => self::cell($row['price'] ?? '', self::MAX_PRICE_CHARS),
                'excluded' => filter_var($row['excluded'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'parse_error' => is_string($row['parse_error'] ?? null) && $row['parse_error'] !== '' ? mb_substr($row['parse_error'], 0, 500) : null,
            ];
        }

        return $clean;
    }

    public static function normalize(string $name): string
    {
        return mb_strtolower(self::cleanName($name));
    }

    public static function cleanName(string $name): string
    {
        return trim(preg_replace('/\s+/u', ' ', $name) ?? $name);
    }

    private static function cell(mixed $value, int $max): string
    {
        return is_scalar($value) ? mb_substr(trim((string) $value), 0, $max) : '';
    }

    /** @return array<string, mixed> */
    private function loadExisting(int $restaurantId): array
    {
        $sections = DB::table('sections')
            ->where('restaurant_id', $restaurantId)
            ->orderBy('display_order')->orderBy('id')
            ->get(['id', 'name', 'is_active', 'display_order']);

        $categories = DB::table('categories')
            ->where('restaurant_id', $restaurantId)
            ->whereNotNull('section_id')
            ->where('section_id', '>', 0)
            ->orderBy('display_order')->orderBy('id')
            ->get(['id', 'section_id', 'name', 'is_active', 'display_order']);

        $items = DB::table('menu_items')
            ->where('restaurant_id', $restaurantId)
            ->orderBy('display_order')->orderBy('id')
            ->get(['id', 'category_id', 'name', 'description', 'price', 'is_available', 'display_order']);

        $sectionsById = [];
        $sectionsByName = [];
        foreach ($sections as $section) {
            $sectionsById[(int) $section->id] = $section;
            $sectionsByName[self::normalize($section->name)][] = $section;
        }

        $categoriesById = [];
        $categoriesByKey = [];
        $categoriesByName = [];
        foreach ($categories as $category) {
            if (! isset($sectionsById[(int) $category->section_id])) {
                continue;
            }
            $categoriesById[(int) $category->id] = $category;
            $categoriesByKey[(int) $category->section_id.'|'.self::normalize($category->name)][] = $category;
            $categoriesByName[self::normalize($category->name)][] = $category;
        }

        $itemsByKey = [];
        $itemsByName = [];
        foreach ($items as $item) {
            if (! isset($categoriesById[(int) $item->category_id])) {
                continue;
            }
            $itemsByKey[(int) $item->category_id.'|'.self::normalize($item->name)][] = $item;
            $itemsByName[self::normalize($item->name)][] = $item;
        }

        return compact('sectionsById', 'sectionsByName', 'categoriesById', 'categoriesByKey', 'categoriesByName', 'itemsByKey', 'itemsByName');
    }

    private function resolveSection(array $section, array $existing): array
    {
        $section['messages'] = [];
        $matches = $existing['sectionsByName'][$section['key']] ?? [];

        if ($matches) {
            $match = $matches[0];
            $section['status'] = 'existing';
            $section['existing_id'] = (int) $match->id;
            $section['name'] = $match->name;
            $section['messages'][] = self::msg('info', "Section already exists: {$match->name}. The existing section will be used and no duplicate section will be created.");
            if (count($matches) > 1) {
                $section['messages'][] = self::msg('warning', 'There are '.count($matches)." existing sections named {$match->name}. The first one in display order will be used.");
            }
            if (! $match->is_active) {
                $section['messages'][] = self::msg('warning', "The existing section {$match->name} is inactive, so it stays hidden on your public menu until you activate it.");
            }
        } else {
            $section['status'] = 'new';
            $section['existing_id'] = null;
        }

        $section['messages'] = array_merge($section['messages'], self::spellingNotes('Section', $section));

        return $section;
    }

    private function resolveCategory(array $category, array $section, array $existing): array
    {
        $category['messages'] = [];
        $category['section_name'] = $section['name'];
        $nameKey = self::normalize($category['name']);
        $matches = $section['existing_id'] ? ($existing['categoriesByKey'][$section['existing_id'].'|'.$nameKey] ?? []) : [];

        if ($matches) {
            $match = $matches[0];
            $category['status'] = 'existing';
            $category['existing_id'] = (int) $match->id;
            $category['name'] = $match->name;
            $category['messages'][] = self::msg('info', "Category already exists: {$match->name} under {$section['name']}. The existing category will be used and no duplicate category will be created.");
            if (count($matches) > 1) {
                $category['messages'][] = self::msg('warning', 'There are '.count($matches)." existing categories named {$match->name} under {$section['name']}. The first one in display order will be used.");
            }
            if (! $match->is_active) {
                $category['messages'][] = self::msg('warning', "The existing category {$match->name} under {$section['name']} is inactive, so its items stay hidden on your public menu until you activate it.");
            }
        } else {
            $category['status'] = 'new';
            $category['existing_id'] = null;
            $elsewhere = [];
            foreach ($existing['categoriesByName'][$nameKey] ?? [] as $other) {
                $otherSection = $existing['sectionsById'][(int) $other->section_id] ?? null;
                if ($otherSection && (int) $otherSection->id !== (int) $section['existing_id']) {
                    $elsewhere[$otherSection->name] = true;
                }
            }
            if ($elsewhere) {
                $category['messages'][] = self::msg('info', "A category named {$category['name']} already exists under ".implode(', ', array_keys($elsewhere)).". A new {$category['name']} category will be created under {$section['name']}.");
            }
        }

        $category['messages'] = array_merge($category['messages'], self::spellingNotes('Category', $category));

        return $category;
    }

    /** @param  array<string, array<string, mixed>>  $out */
    private function resolveItems(array $category, array $section, array $existing, array &$out): void
    {
        $path = "{$section['name']} / {$category['name']}";
        $groups = [];
        foreach ($category['row_ids'] as $id) {
            $entry = &$out[$id];
            if ($entry['errors'] !== []) {
                $entry['status'] = 'error';
                unset($entry);

                continue;
            }
            $groups[self::normalize($entry['name'])][] = $id;
            unset($entry);
        }

        foreach ($groups as $nameKey => $ids) {
            $primaryId = $ids[0];
            if (count($ids) > 1) {
                $first = $out[$primaryId];
                $identical = true;
                foreach (array_slice($ids, 1) as $id) {
                    if (self::text($out[$id]['description']) !== self::text($first['description']) || $out[$id]['price_value'] !== $first['price_value']) {
                        $identical = false;
                        break;
                    }
                }

                if (! $identical) {
                    $numbers = implode(' and ', array_map(fn ($id) => (string) $out[$id]['row_number'], $ids));
                    $text = "Rows {$numbers} define ".self::cleanName($first['name'])." under {$path} with different price/description. Edit or exclude one of them.";
                    foreach ($ids as $id) {
                        $out[$id]['status'] = 'conflict';
                        $out[$id]['errors']['row'] = $text;
                    }

                    continue;
                }

                foreach (array_slice($ids, 1) as $id) {
                    $out[$id]['status'] = 'duplicate';
                    $out[$id]['duplicate_of'] = $primaryId;
                    $out[$id]['messages'][] = self::msg('info', "Row {$out[$id]['row_number']} duplicates row {$first['row_number']} and will be imported once.");
                }
            }

            $this->classifyItem($out[$primaryId], $nameKey, $category, $section, $path, $existing);
        }
    }

    private function classifyItem(array &$entry, string $nameKey, array $category, array $section, string $path, array $existing): void
    {
        $name = self::cleanName($entry['name']);
        $matches = $category['existing_id'] ? ($existing['itemsByKey'][$category['existing_id'].'|'.$nameKey] ?? []) : [];

        if ((float) $entry['price_value'] === 0.0) {
            $entry['messages'][] = self::msg('info', "Price is 0, so {$name} will show as free.");
        }

        if (! $matches) {
            $entry['status'] = 'new';
            $elsewhere = [];
            foreach ($existing['itemsByName'][$nameKey] ?? [] as $other) {
                $otherCategory = $existing['categoriesById'][(int) $other->category_id] ?? null;
                if (! $otherCategory || (int) $otherCategory->id === (int) $category['existing_id']) {
                    continue;
                }
                $otherSection = $existing['sectionsById'][(int) $otherCategory->section_id];
                $elsewhere["{$otherSection->name} / {$otherCategory->name}"] = true;
            }
            if ($elsewhere) {
                $entry['messages'][] = self::msg('info', "A menu item named {$name} already exists under ".implode(', ', array_keys($elsewhere)).". A new {$name} will be created under {$path}.");
            }

            return;
        }

        $match = $matches[0];
        $entry['existing_id'] = (int) $match->id;
        $oldPrice = number_format((float) $match->price, 2, '.', '');
        $oldDescription = self::text((string) $match->description);
        $newDescription = self::text($entry['description']);
        $entry['existing'] = ['name' => $match->name, 'price' => $oldPrice, 'description' => $oldDescription];

        $changes = [];
        if ($entry['price_value'] !== $oldPrice) {
            $changes['price'] = ['from' => $oldPrice, 'to' => $entry['price_value']];
        }
        if ($newDescription !== '' && $newDescription !== $oldDescription) {
            $changes['description'] = ['from' => $oldDescription, 'to' => $newDescription];
        }
        $entry['changes'] = $changes;

        if ($changes) {
            $entry['status'] = 'update';
            $entry['messages'][] = self::msg('warning', "Menu item already exists: {$match->name} under {$path}. The existing menu item will be updated.");
        } else {
            $entry['status'] = 'unchanged';
            $entry['messages'][] = self::msg('info', "Menu item already exists: {$match->name} under {$path}. It already has this price and description, so nothing will change.");
        }
        if ($newDescription === '' && $oldDescription !== '') {
            $entry['messages'][] = self::msg('info', 'Description: unchanged (blank in file).');
        }
        if (count($matches) > 1) {
            $entry['messages'][] = self::msg('warning', 'There are '.count($matches)." existing items named {$match->name} under {$path}. The first one in display order will be updated.");
        }
        if (! $match->is_available) {
            $entry['messages'][] = self::msg('warning', "The existing menu item {$match->name} is marked unavailable and will stay unavailable.");
        }
    }

    /** @return array<string, array<string, int>> */
    private function summarize(array $sections, array $categories, array $out): array
    {
        $summary = [
            'sections' => ['new' => 0, 'existing' => 0],
            'categories' => ['new' => 0, 'existing' => 0],
            'items' => ['new' => 0, 'update' => 0, 'unchanged' => 0, 'duplicate' => 0, 'error' => 0, 'excluded' => 0],
            'rows' => count($out),
        ];
        foreach ($sections as $section) {
            $summary['sections'][$section['status']]++;
        }
        foreach ($categories as $category) {
            $summary['categories'][$category['status']]++;
        }
        foreach ($out as $entry) {
            $status = match ($entry['status']) {
                'error', 'conflict', 'attention' => 'error',
                default => $entry['status'],
            };
            $summary['items'][$status]++;
        }

        return $summary;
    }

    /** @return list<string> */
    private function limitErrors(int $restaurantId, array $summary): array
    {
        $errors = [];
        $checks = [
            'categories' => [$summary['categories']['new'], 'categories'],
            'menu_items' => [$summary['items']['new'], 'menu items'],
        ];
        foreach ($checks as $feature => [$adding, $label]) {
            if ($adding <= 0) {
                continue;
            }
            $usage = $this->subscriptions->getRemainingUsage($restaurantId, $feature);
            if ($usage['unlimited']) {
                continue;
            }
            $limit = (int) $usage['limit'];
            $used = (int) $usage['used'];
            if ($used + $adding > $limit) {
                $over = $used + $adding - $limit;
                $errors[] = "Your plan allows {$limit} {$label}. You have {$used}, and this import would add {$adding} ({$over} over the limit). Exclude some rows or upgrade your plan.";
            }
        }

        return $errors;
    }

    /** @return array<string, mixed> */
    private function buildPlan(array $sections, array $categories, array $out): array
    {
        $plan = ['sections' => [], 'categories' => [], 'items' => []];
        foreach ($sections as $key => $section) {
            $plan['sections'][$key] = ['status' => $section['status'], 'existing_id' => $section['existing_id'], 'name' => $section['name']];
        }
        foreach ($categories as $key => $category) {
            $plan['categories'][$key] = ['status' => $category['status'], 'existing_id' => $category['existing_id'], 'name' => $category['name'], 'section_key' => $category['section_key']];
            foreach ($category['row_ids'] as $id) {
                $entry = $out[$id];
                if (! in_array($entry['status'], ['new', 'update'], true)) {
                    continue;
                }
                $plan['items'][] = [
                    'row_id' => $id,
                    'row_number' => $entry['row_number'],
                    'action' => $entry['status'] === 'new' ? 'create' : 'update',
                    'category_key' => $key,
                    'existing_id' => $entry['existing_id'] ?? null,
                    'name' => self::cleanName($entry['name']),
                    'description' => self::text($entry['description']),
                    'price' => $entry['price_value'],
                    'changes' => $entry['changes'],
                ];
            }
        }

        return $plan;
    }

    /** @return list<array{level: string, text: string}> */
    private function collectWarnings(array $sections, array $categories, array $out): array
    {
        $all = [];
        foreach ($sections as $section) {
            array_push($all, ...$section['messages']);
        }
        foreach ($categories as $category) {
            array_push($all, ...$category['messages']);
        }
        foreach ($out as $entry) {
            array_push($all, ...$entry['messages']);
        }

        $unique = [];
        foreach ($all as $message) {
            $unique[$message['level'].'|'.$message['text']] = $message;
        }

        return array_values($unique);
    }

    /** @return list<array{section: string, category: string}> */
    private function existingCategoryOptions(array $existing): array
    {
        $options = [];
        foreach ($existing['categoriesById'] as $category) {
            $section = $existing['sectionsById'][(int) $category->section_id];
            $options[] = ['section' => $section->name, 'category' => $category->name];
        }

        return $options;
    }

    /** @return list<array{level: string, text: string}> */
    private static function spellingNotes(string $label, array $node): array
    {
        if (count($node['spellings']) < 2) {
            return [];
        }
        $spellings = array_keys($node['spellings']);
        $first = array_shift($spellings);
        $others = implode(', ', array_map(fn ($s) => "\"{$s}\"", $spellings));

        return [self::msg('info', "{$label} names {$others} were grouped with \"{$first}\" because they differ only by capitalisation or spacing.")];
    }

    private static function publicNode(array $node): array
    {
        return [
            'key' => $node['key'],
            'name' => $node['name'],
            'status' => $node['status'],
            'messages' => $node['messages'],
        ];
    }

    private static function text(string $value): string
    {
        return trim(str_replace(["\r\n", "\r"], "\n", $value));
    }

    /** @return array{level: string, text: string} */
    private static function msg(string $level, string $text): array
    {
        return ['level' => $level, 'text' => $text];
    }
}
