<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index(Request $request, MailService $mail)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'site_name' => 'nullable|string|max:255',
                'support_email' => 'nullable|email',
            ]);
            foreach ($data as $key => $value) {
                if ($value === null) {
                    continue;
                }
                DB::table('site_settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }

            if ($request->boolean('send_test_email') && $request->filled('test_email')) {
                $mail->send($request->input('test_email'), '', 'Resmenu test', '<p>Test email OK</p>');
            }

            return back()->with('success', 'Settings saved.');
        }

        $settings = DB::table('site_settings')->pluck('value', 'key');

        return view('admin.settings.index', ['settings' => $settings]);
    }
}
