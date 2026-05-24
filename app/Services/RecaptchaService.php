<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    public function shouldEnforce(): bool
    {
        if (app()->environment('production')) {
            return true;
        }

        return $this->isConfigured();
    }

    public function isConfigured(): bool
    {
        return config('resmenu.recaptcha_site_key') !== ''
            && config('resmenu.recaptcha_secret_key') !== '';
    }

    public function verifyRequest(Request $request): bool
    {
        return $this->verifyToken((string) $request->input('g-recaptcha-response', ''));
    }

    public function verifyToken(string $token): bool
    {
        if (! $this->shouldEnforce()) {
            return true;
        }

        if (! $this->isConfigured()) {
            Log::error('reCAPTCHA enforcement active but keys are not configured');

            return false;
        }

        if ($token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout((int) config('resmenu.recaptcha_timeout_seconds', 5))
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('resmenu.recaptcha_secret_key'),
                    'response' => $token,
                    'remoteip' => request()->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA verification request failed', ['message' => $e->getMessage()]);

            return false;
        }

        if (! $response->successful()) {
            Log::warning('reCAPTCHA verification HTTP error', ['status' => $response->status()]);

            return false;
        }

        return (bool) $response->json('success', false);
    }
}
