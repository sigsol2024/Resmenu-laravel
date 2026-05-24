<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\LegacyEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        return view('admin.payment-settings.index', [
            'paystack' => DB::table('payment_settings')->where('gateway', 'paystack')->first(),
            'flutterwave' => DB::table('payment_settings')->where('gateway', 'flutterwave')->first(),
        ]);
    }

    public function update(Request $request)
    {
        $gateway = $request->validate(['gateway' => 'required|in:paystack,flutterwave'])['gateway'];

        $data = $request->validate([
            'is_active' => 'nullable|boolean',
            'test_mode' => 'nullable|boolean',
            'public_key_live' => 'nullable|string',
            'secret_key_live' => 'nullable|string',
            'public_key_test' => 'nullable|string',
            'secret_key_test' => 'nullable|string',
        ]);

        $payload = [
            'is_active' => $request->boolean('is_active') ? 1 : 0,
            'test_mode' => $request->boolean('test_mode') ? 1 : 0,
            'public_key_live' => $data['public_key_live'] ?? null,
            'public_key_test' => $data['public_key_test'] ?? null,
            'updated_at' => now(),
        ];

        if (! empty($data['secret_key_live'])) {
            $payload['secret_key_live'] = LegacyEncryption::encrypt($data['secret_key_live']);
        }
        if (! empty($data['secret_key_test'])) {
            $payload['secret_key_test'] = LegacyEncryption::encrypt($data['secret_key_test']);
        }

        if (DB::table('payment_settings')->where('gateway', $gateway)->exists()) {
            DB::table('payment_settings')->where('gateway', $gateway)->update($payload);
        } else {
            $payload['gateway'] = $gateway;
            $payload['created_at'] = now();
            DB::table('payment_settings')->insert($payload);
        }

        return back()->with('success', ucfirst($gateway).' settings saved.');
    }
}
