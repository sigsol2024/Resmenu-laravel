<?php

namespace App\Jobs;

use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendTransactionalMailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var list<int> */
    public array $backoff = [60, 300];

    public function __construct(
        public string $to,
        public string $toName,
        public string $subject,
        public string $html,
        public array $options = [],
    ) {}

    public function handle(MailService $mail): void
    {
        if (! $mail->sendSync($this->to, $this->toName, $this->subject, $this->html, $this->options)) {
            Log::warning('Transactional mail job failed to send', ['to' => $this->to, 'subject' => $this->subject]);
        }
    }
}
