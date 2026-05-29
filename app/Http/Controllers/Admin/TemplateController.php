<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Services\UploadService;
use App\Support\MenuTemplateResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TemplateController extends Controller
{
  public function index(MenuTemplateResolver $resolver)
  {
    $templateIds = $resolver->availableTemplateIds();
    $templates = $this->hasTable('templates')
      ? DB::table('templates')->orderBy('id')->get()->keyBy('id')
      : collect();

    $rows = [];
    foreach ($templateIds as $id) {
      $rows[] = $templates->get($id) ?? (object) [
        'id' => $id,
        'name' => 'Template '.$id,
        'slug' => 'template'.$id,
        'is_active' => 1,
        'is_private' => 0,
      ];
    }

    $restaurantIdsByTemplate = [];
    $isPrivateByTemplate = [];
    foreach ($templateIds as $id) {
      $restaurantIdsByTemplate[$id] = $this->pluckTemplateColumn('template_restaurants', $id, 'restaurant_id');
      $row = $templates->get($id);
      $isPrivateByTemplate[$id] = (bool) ($row->is_private ?? 0);
    }

    return view('admin.templates.index', [
      'templates' => collect($rows),
      'planIdsByTemplate' => $this->planIdsByTemplate($templateIds),
      'restaurantIdsByTemplate' => $restaurantIdsByTemplate,
      'restaurantNamesByTemplate' => $this->restaurantNamesByTemplate($restaurantIdsByTemplate, $this->hasTable('restaurants')
        ? DB::table('restaurants')->orderBy('name')->get(['id', 'name'])
        : collect()),
      'isPrivateByTemplate' => $isPrivateByTemplate,
      'plans' => SubscriptionPlan::orderBy('display_order')->get(),
      'restaurants' => $this->hasTable('restaurants')
        ? DB::table('restaurants')->orderBy('name')->get(['id', 'name'])
        : collect(),
      'expandedId' => (int) request()->query('expand', 0),
    ]);
  }

  public function edit(int $template, MenuTemplateResolver $resolver)
  {
    if (! $resolver->supportsTemplate($template)) {
      abort(404);
    }

    $row = DB::table('templates')->where('id', $template)->first();

    return view('admin.templates.edit', [
      'template' => $row ?? (object) ['id' => $template, 'name' => 'Template '.$template, 'slug' => 'template'.$template],
      'plans' => SubscriptionPlan::orderBy('display_order')->get(),
      'assignedPlanIds' => DB::table('template_plans')->where('template_id', $template)->pluck('plan_id')->all(),
      'assignedRestaurantIds' => DB::table('template_restaurants')->where('template_id', $template)->pluck('restaurant_id')->all(),
      'restaurants' => DB::table('restaurants')->orderBy('name')->get(['id', 'name']),
    ]);
  }

  public function update(Request $request, int $template, UploadService $uploads, MenuTemplateResolver $resolver)
  {
    if (! $resolver->supportsTemplate($template)) {
      abort(404);
    }

    $data = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'is_private' => 'nullable|boolean',
      'plan_ids' => 'nullable|array',
      'plan_ids.*' => 'integer|exists:subscription_plans,id',
      'restaurant_ids' => 'nullable|array',
      'restaurant_ids.*' => 'integer|exists:restaurants,id',
    ]);

    $slug = 'template'.$template;
    $existing = DB::table('templates')->where('id', $template)->first();
    $preview = $existing->preview_image ?? null;
    $listing = $existing->listing_image ?? null;

    if ($request->hasFile('preview_image')) {
      $result = $uploads->storeImage($request->file('preview_image'), 'template-previews');
      if ($result['success'] ?? false) {
        $uploads->delete('template-previews', $preview);
        $preview = $result['filename'];
      }
    }
    if ($request->hasFile('listing_image')) {
      $result = $uploads->storeImage($request->file('listing_image'), 'template-previews');
      if ($result['success'] ?? false) {
        $uploads->delete('template-previews', $listing);
        $listing = $result['filename'];
      }
    }

    $payload = [
      'name' => $data['name'],
      'slug' => $slug,
      'description' => $data['description'] ?? null,
      'preview_image' => $preview,
      'listing_image' => $listing,
      'is_private' => $request->boolean('is_private') ? 1 : 0,
      'updated_at' => now(),
    ];

    if ($existing) {
      DB::table('templates')->where('id', $template)->update($payload);
    } else {
      $payload['id'] = $template;
      $payload['is_active'] = 1;
      $payload['created_at'] = now();
      DB::table('templates')->insert($payload);
    }

    DB::table('template_plans')->where('template_id', $template)->delete();
    foreach ($data['plan_ids'] ?? [] as $planId) {
      DB::table('template_plans')->insert(['template_id' => $template, 'plan_id' => (int) $planId]);
    }

    DB::table('template_restaurants')->where('template_id', $template)->delete();
    foreach ($data['restaurant_ids'] ?? [] as $restaurantId) {
      DB::table('template_restaurants')->insert(['template_id' => $template, 'restaurant_id' => (int) $restaurantId]);
    }

    return redirect()->route('admin.templates.index', ['expand' => $template])->with('success', 'Template saved.');
  }

  public function toggle(int $template)
  {
    $row = DB::table('templates')->where('id', $template)->first();
    if (! $row) {
      DB::table('templates')->insert([
        'id' => $template,
        'name' => 'Template '.$template,
        'slug' => 'template'.$template,
        'is_active' => 0,
        'is_private' => 0,
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return back()->with('success', 'Template status updated.');
    }

    DB::table('templates')->where('id', $template)->update([
      'is_active' => ! ($row->is_active ?? 1),
      'updated_at' => now(),
    ]);

    return back()->with('success', 'Template status updated.');
  }

  /** @param list<int> $templateIds */
  private function planIdsByTemplate(array $templateIds): array
  {
    $map = [];
    foreach ($templateIds as $id) {
      $map[$id] = $this->pluckTemplateColumn('template_plans', $id, 'plan_id');
    }

    return $map;
  }

  private function hasTable(string $table): bool
  {
    try {
      return Schema::hasTable($table);
    } catch (\Throwable) {
      return false;
    }
  }

  /** @return list<int|string> */
  private function pluckTemplateColumn(string $table, int $templateId, string $column): array
  {
    if (! $this->hasTable($table)) {
      return [];
    }

    try {
      return DB::table($table)->where('template_id', $templateId)->pluck($column)->all();
    } catch (\Throwable) {
      return [];
    }
  }

  /** @param array<int, list<int>> $restaurantIdsByTemplate */
  private function restaurantNamesByTemplate(array $restaurantIdsByTemplate, $restaurants): array
  {
    $lookup = collect($restaurants)->keyBy('id');
    $map = [];
    foreach ($restaurantIdsByTemplate as $templateId => $ids) {
      $map[$templateId] = [];
      foreach ($ids as $id) {
        $row = $lookup->get($id);
        if ($row) {
          $map[$templateId][$id] = $row->name;
        }
      }
    }

    return $map;
  }
}
