<?php

namespace App\Services\CRM\Providers\HubSpot;

/**
 * Helpers for HubSpot tracking snippet (public portal ID only — never private token).
 */
class HubSpotTrackingConfig
{
    public static function snippet(?string $portalId): string
    {
        $portalId = trim((string) $portalId);
        if ($portalId === '' || ! ctype_digit($portalId)) {
            return '';
        }

        $id = e($portalId);

        return <<<HTML
<script type="text/javascript" id="hs-script-loader" async defer src="https://js.hs-scripts.com/{$id}.js"></script>
HTML;
    }
}
