<?php

namespace App\Services;

use App\Support\LegacyEncryption;
use Illuminate\Support\Facades\DB;

class RestaurantPaymentSettingsService
{
    /** @return array<string, array<string, mixed>> */
    public function allForRestaurant(int $restaurantId): array
    {
        $rows = DB::table('restaurant_payment_settings')
            ->where('restaurant_id', $restaurantId)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[$row->gateway] = (array) $row;
        }

        return $result;
    }

    public function forGateway(int $restaurantId, string $gateway): ?object
    {
        return DB::table('restaurant_payment_settings')
            ->where('restaurant_id', $restaurantId)
            ->where('gateway', $gateway)
            ->first();
    }

    /** @param  array<string, mixed>  $settings */
    public function update(int $restaurantId, string $gateway, array $settings): bool
    {
        if (! in_array($gateway, ['paystack', 'flutterwave', 'bank_transfer'], true)) {
            return false;
        }

        $allowed = $gateway === 'bank_transfer'
            ? ['is_active', 'bank_name', 'account_number', 'account_name']
            : [
                'is_active', 'test_mode',
                'public_key_test', 'secret_key_test', 'webhook_secret_test',
                'public_key_live', 'secret_key_live', 'webhook_secret_live',
            ];

        $payload = [];
        foreach ($allowed as $field) {
            if (! array_key_exists($field, $settings)) {
                continue;
            }
            $value = $settings[$field];
            if (is_bool($value)) {
                $value = $value ? 1 : 0;
            }
            if (in_array($gateway, ['paystack', 'flutterwave'], true)
                && str_contains($field, 'secret_key')
                && is_string($value)
                && $value !== ''
            ) {
                $value = LegacyEncryption::encrypt($value);
            }
            $payload[$field] = $value;
        }

        if ($payload === []) {
            return true;
        }

        $payload['updated_at'] = now();
        $exists = $this->forGateway($restaurantId, $gateway);

        if ($exists) {
            return DB::table('restaurant_payment_settings')
                ->where('restaurant_id', $restaurantId)
                ->where('gateway', $gateway)
                ->update($payload) >= 0;
        }

        $payload['restaurant_id'] = $restaurantId;
        $payload['gateway'] = $gateway;
        $payload['created_at'] = now();

        return DB::table('restaurant_payment_settings')->insert($payload);
    }
}
