<?php

namespace App\Services\CRM\DTOs;

final class ContactPayload
{
    public function __construct(
        public readonly string $email,
        public readonly ?string $name = null,
        public readonly ?string $phone = null,
        public readonly ?string $message = null,
        public readonly string $source = 'registration',
        public readonly bool $marketingConsent = false,
        public readonly ?string $marketingConsentAt = null,
        public readonly ?string $marketingConsentTextVersion = null,
        public readonly ?int $managerId = null,
        public readonly ?int $crmLeadId = null,
        public readonly ?int $consentEventId = null,
        public readonly bool $shouldSubscribe = false,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            email: strtolower(trim((string) ($data['email'] ?? ''))),
            name: isset($data['name']) ? trim((string) $data['name']) : null,
            phone: isset($data['phone']) ? trim((string) $data['phone']) : null,
            message: isset($data['message']) ? trim((string) $data['message']) : null,
            source: (string) ($data['source'] ?? 'registration'),
            marketingConsent: (bool) ($data['marketing_consent'] ?? false),
            marketingConsentAt: isset($data['marketing_consent_at']) ? (string) $data['marketing_consent_at'] : null,
            marketingConsentTextVersion: isset($data['marketing_consent_text_version']) ? (string) $data['marketing_consent_text_version'] : null,
            managerId: isset($data['manager_id']) ? (int) $data['manager_id'] : null,
            crmLeadId: isset($data['crm_lead_id']) ? (int) $data['crm_lead_id'] : null,
            consentEventId: isset($data['consent_event_id']) ? (int) $data['consent_event_id'] : null,
            shouldSubscribe: (bool) ($data['should_subscribe'] ?? false),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
            'message' => $this->message,
            'source' => $this->source,
            'marketing_consent' => $this->marketingConsent,
            'marketing_consent_at' => $this->marketingConsentAt,
            'marketing_consent_text_version' => $this->marketingConsentTextVersion,
            'manager_id' => $this->managerId,
            'crm_lead_id' => $this->crmLeadId,
            'consent_event_id' => $this->consentEventId,
            'should_subscribe' => $this->shouldSubscribe,
        ];
    }
}
