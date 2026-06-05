<?php

namespace App\Console\Commands;

use App\Services\QrGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegenerateQrTemplatePreviews extends Command
{
    protected $signature = 'resmenu:regenerate-qr-previews';

    protected $description = 'Regenerate PNG preview images for all QR templates';

    public function handle(QrGeneratorService $generator): int
    {
        $ids = DB::table('qr_templates')->orderBy('id')->pluck('id');
        $done = 0;

        foreach ($ids as $id) {
            if ($generator->generateTemplatePreview((int) $id)) {
                $done++;
                $this->line("  OK template #{$id}");
            } else {
                $this->warn("  Skipped template #{$id}");
            }
        }

        $this->info("Generated {$done} of ".$ids->count().' preview image(s).');

        return self::SUCCESS;
    }
}
