<?php

namespace App\View\Composers;

use App\Services\SiteSettingsService;
use Illuminate\View\View;

class SiteBrandingComposer
{
    public function __construct(private SiteSettingsService $siteSettings) {}

    public function compose(View $view): void
    {
        $view->with([
            'siteFaviconUrl' => $this->siteSettings->faviconUrl(),
            'siteFaviconType' => $this->siteSettings->faviconMimeType(),
            'siteName' => $this->siteSettings->siteName(),
        ]);
    }
}
