<?php

namespace App\Services\MenuImport;

use Illuminate\Support\Facades\Validator;

/**
 * Field rules for imported rows. The base rules mirror the manual Section,
 * Category and MenuItem controllers exactly (MenuImportRowRulesParityTest
 * fails if they drift); the extra guards keep a bad cell from reaching the
 * database write, where it would abort the whole import transaction.
 */
final class MenuImportRowRules
{
    public const MAX_PRICE = 99999999.99;

    public const MAX_DESCRIPTION_BYTES = 65535;

    /** @return array<string, list<string>> */
    public static function baseRules(): array
    {
        return [
            'section' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /** @return array<string, list<mixed>> */
    public static function importRules(): array
    {
        $rules = self::baseRules();

        $rules['description'][] = function (string $attribute, mixed $value, \Closure $fail): void {
            if (is_string($value) && strlen($value) > self::MAX_DESCRIPTION_BYTES) {
                $fail('Description is too long (maximum about 65,000 characters).');
            }
        };
        $rules['price'][] = 'max:'.self::MAX_PRICE;
        $rules['price'][] = 'regex:/^-?\d+(\.\d{1,2})?$/';

        foreach ($rules as $field => $fieldRules) {
            $rules[$field] = ['bail', ...$fieldRules];
        }

        return $rules;
    }

    /**
     * @param  array{section?: mixed, category?: mixed, name?: mixed, description?: mixed, price?: mixed}  $row
     * @return array{errors: array<string, string>, price: ?string}
     */
    public static function validate(array $row): array
    {
        $data = [
            'section' => self::text($row['section'] ?? ''),
            'category' => self::text($row['category'] ?? ''),
            'name' => self::text($row['name'] ?? ''),
            'description' => self::text($row['description'] ?? ''),
            'price' => self::normalizePrice((string) ($row['price'] ?? '')),
        ];
        foreach ($data as $field => $value) {
            if ($value === '') {
                $data[$field] = null;
            }
        }

        $validator = Validator::make($data, self::importRules(), self::messages());
        $errors = [];
        foreach ($validator->errors()->messages() as $field => $messages) {
            $errors[$field] = $messages[0];
        }

        $price = isset($errors['price']) || $data['price'] === null
            ? null
            : number_format((float) $data['price'], 2, '.', '');

        return ['errors' => $errors, 'price' => $price];
    }

    /**
     * Accepts 8500, 8500.50, 8,500, ₦8,500 and NGN 8500. Anything else is
     * returned as-is so validation can explain what is wrong with it.
     */
    public static function normalizePrice(string $raw): string
    {
        $value = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $raw) ?? $raw);
        $value = preg_replace('/^(-?)\s*(?:₦|NGN)\s*/iu', '$1', $value) ?? $value;
        $value = str_replace(' ', '', $value);

        if (preg_match('/^-?\d{1,3}(,\d{3})+(\.\d+)?$/', $value)) {
            $value = str_replace(',', '', $value);
        }

        return $value;
    }

    private static function text(mixed $value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }

    /** @return array<string, string> */
    private static function messages(): array
    {
        return [
            'section.required' => 'Section is required.',
            'section.max' => 'Section name cannot be longer than 255 characters.',
            'category.required' => 'Category is required.',
            'category.max' => 'Category name cannot be longer than 255 characters.',
            'name.required' => 'Menu item name is required.',
            'name.max' => 'Menu item name cannot be longer than 255 characters.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number, for example 8500 or 8500.50.',
            'price.min' => 'Price cannot be negative.',
            'price.max' => 'Price cannot be more than 99,999,999.99.',
            'price.regex' => 'Price must be a plain number with at most 2 decimal places, for example 8500.50.',
        ];
    }
}
