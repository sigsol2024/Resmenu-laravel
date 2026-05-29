<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class TemplateAvailabilityService
{
    public function __construct(private UploadService $uploads) {}

    /**
     * Templates visible to a restaurant with plan-gated can_use flag.
     *
     * @return list<array<string, mixed>>
     */
    public function availableForRestaurant(int $restaurantId): array
    {
        $planId = (int) (Subscription::query()
            ->where('restaurant_id', $restaurantId)
            ->orderByDesc('created_at')
            ->value('plan_id') ?? 0);

        try {
            $rows = DB::select('
                SELECT t.id, t.name, t.slug, t.description, t.preview_image, t.listing_image,
                    EXISTS (
                        SELECT 1 FROM template_plans tp
                        WHERE tp.template_id = t.id AND tp.plan_id = ?
                    ) AS can_use
                FROM templates t
                WHERE t.is_active = 1
                AND (
                    (COALESCE(t.is_private, 0) = 0 AND EXISTS (
                        SELECT 1 FROM template_plans tp2 WHERE tp2.template_id = t.id
                    ))
                    OR (COALESCE(t.is_private, 0) = 1 AND EXISTS (
                        SELECT 1 FROM template_restaurants tr
                        WHERE tr.template_id = t.id AND tr.restaurant_id = ?
                    ))
                )
                ORDER BY t.id ASC
            ', [$planId, $restaurantId]);
        } catch (\Throwable) {
            $rows = DB::table('templates')->where('is_active', 1)->orderBy('id')->get()->all();

            return collect($rows)->map(fn ($t) => $this->mapRow((object) $t, true))->all();
        }

        return collect($rows)
            ->map(fn ($row) => $this->mapRow($row, (bool) ($row->can_use ?? false)))
            ->filter(fn ($t) => $this->templateDirExists((int) $t['id']))
            ->values()
            ->all();
    }

    private function mapRow(object $row, bool $canUse): array
    {
        $id = (int) $row->id;

        return [
            'id' => $id,
            'name' => $row->name,
            'slug' => $row->slug ?? null,
            'description' => $row->description ?? null,
            'preview_image' => ! empty($row->preview_image)
                ? $this->uploads->publicUrl('template-previews', $row->preview_image)
                : null,
            'listing_image' => ! empty($row->listing_image)
                ? $this->uploads->publicUrl('template-previews', $row->listing_image)
                : null,
            'can_use' => $canUse,
            'can_see' => true,
        ];
    }

    private function templateDirExists(int $templateId): bool
    {
        $dir = public_path('templates/template'.$templateId);

        return is_dir($dir) || is_file(resource_path('views/menu/php-templates/template'.$templateId.'/index.blade.php'));
    }
}
