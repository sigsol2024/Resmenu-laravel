<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function send(string $to, string $toName, string $subject, string $html, array $options = []): bool
    {
        $token = config('resmenu.zeptomail_sendmail_token');
        if ($token !== '') {
            return $this->sendZeptoMail($to, $toName, $subject, $html, $options);
        }

        if (! config('resmenu.mail_enabled')) {
            return false;
        }

        try {
            Mail::html($html, function ($message) use ($to, $toName, $subject, $options) {
                $message->to($to, $toName)
                    ->subject($subject)
                    ->from(
                        config('resmenu.zeptomail_from_address'),
                        $options['from_name'] ?? config('resmenu.mail_from_name')
                    );
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Mail send failed: '.$e->getMessage());

            return false;
        }
    }

    public function sendOrderCreated(int $orderId, int $restaurantId): void
    {
        app(RestaurantTransactionalMailService::class)->sendOrderCreated($orderId, $restaurantId);
    }

    private function sendZeptoMail(string $to, string $toName, string $subject, string $html, array $options): bool
    {
        $token = config('resmenu.zeptomail_sendmail_token');
        if (! str_starts_with($token, 'Zoho-enczapikey')) {
            $token = 'Zoho-enczapikey '.$token;
        }

        $from = config('resmenu.zeptomail_from_address');
        $fromName = $options['from_name'] ?? config('resmenu.zeptomail_from_name');

        try {
            $response = Http::timeout(config('resmenu.zeptomail_timeout_seconds', 30))
                ->withHeaders([
                    'Authorization' => $token,
                    'Content-Type' => 'application/json',
                ])
                ->post(config('resmenu.zeptomail_url'), [
                    'from' => ['address' => $from, 'name' => $fromName],
                    'to' => [['email_address' => ['address' => $to, 'name' => $toName]]],
                    'subject' => $subject,
                    'htmlbody' => $html,
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('ZeptoMail failed: '.$e->getMessage());

            return false;
        }
    }
}
