<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CRM\ConsentService;
use App\Services\CRM\ContactLeadService;
use App\Services\CRM\CRMService;
use App\Services\RecaptchaService;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;

class LeadCaptureController extends Controller
{
    public function store(Request $request, ContactLeadService $leads, RecaptchaService $recaptcha)
    {
        // Honeypot — bots fill this; humans leave empty. Return benign success.
        if (trim((string) $request->input('website', '')) !== '' || trim((string) $request->input('company_url', '')) !== '') {
            return ApiJsonResponse::success('Lead captured.', ['lead_id' => null]);
        }

        $data = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:40',
            'message' => 'nullable|string|max:5000',
            'source' => 'required|in:'.implode(',', ConsentService::PUBLIC_SOURCES),
            'marketing_consent' => 'nullable|boolean',
            'g-recaptcha-response' => 'nullable|string',
        ]);

        if (! $recaptcha->verifyRequest($request)) {
            return ApiJsonResponse::error('Captcha verification failed.', null, 422);
        }

        if (! $leads->assertPublicRateLimits($data['email'], (string) $request->ip())) {
            return ApiJsonResponse::error('Too many submissions. Please try again later.', null, 429);
        }

        try {
            $result = $leads->capture([
                'email' => $data['email'],
                'name' => $data['name'] ?? null,
                'phone' => $data['phone'] ?? null,
                'message' => $data['message'] ?? null,
                'source' => $data['source'],
                'marketing_consent' => $request->boolean('marketing_consent'),
            ], $request);
        } catch (\InvalidArgumentException $e) {
            return ApiJsonResponse::error($e->getMessage(), null, 422);
        } catch (\Throwable) {
            return ApiJsonResponse::error('Could not save lead.', null, 500);
        }

        return ApiJsonResponse::success('Lead captured.', [
            'lead_id' => $result['lead_id'],
        ]);
    }

    public function publicConfig(CRMService $crm)
    {
        return ApiJsonResponse::success('OK', $crm->publicWebsiteConfig());
    }
}
