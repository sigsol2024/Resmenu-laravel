<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QrGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrTemplateController extends Controller
{
  public function index(Request $request)
  {
    try {
      $templates = DB::table('qr_templates')
        ->select('qr_templates.*', DB::raw('(SELECT COUNT(*) FROM restaurant_qr_codes WHERE qr_template_id = qr_templates.id) as usage_count'))
        ->orderBy('qr_templates.id')
        ->get();
    } catch (\Throwable) {
      $templates = collect();
    }

    $editTemplate = null;
    $editConfig = $this->defaultConfig();
    $editId = (int) $request->query('edit', 0);

    if ($editId > 0) {
      $editTemplate = DB::table('qr_templates')->where('id', $editId)->first();
      if (! $editTemplate) {
        return redirect()->route('admin.qr-templates.index')
          ->with('error', 'QR template not found.');
      }
      $decoded = json_decode($editTemplate->config_json ?? '', true);
      if (is_array($decoded)) {
        $editConfig = $decoded;
      }
    }

    return view('admin.qr-templates.index', [
      'templates' => $templates,
      'editTemplate' => $editTemplate,
      'editConfig' => $editConfig,
      'openCreateModal' => $request->boolean('create'),
    ]);
  }

  public function create()
  {
    return redirect()->route('admin.qr-templates.index', ['create' => 1]);
  }

  public function store(Request $request)
  {
    $data = $this->validated($request);
    $id = DB::table('qr_templates')->insertGetId([
      'name' => $data['name'],
      'description' => $data['description'],
      'has_text' => $data['has_text'],
      'config_json' => json_encode($data['config']),
      'is_active' => 1,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    return redirect()->route('admin.qr-templates.index')->with('success', 'QR template created.');
  }

  public function edit(int $qrTemplate)
  {
    if (! DB::table('qr_templates')->where('id', $qrTemplate)->exists()) {
      abort(404);
    }

    return redirect()->route('admin.qr-templates.index', ['edit' => $qrTemplate]);
  }

  public function update(Request $request, int $qrTemplate)
  {
    if (! DB::table('qr_templates')->where('id', $qrTemplate)->exists()) {
      abort(404);
    }

    $data = $this->validated($request);
    DB::table('qr_templates')->where('id', $qrTemplate)->update([
      'name' => $data['name'],
      'description' => $data['description'],
      'has_text' => $data['has_text'],
      'config_json' => json_encode($data['config']),
      'is_active' => $request->boolean('is_active') ? 1 : 0,
      'updated_at' => now(),
    ]);

    return redirect()->route('admin.qr-templates.index')->with('success', 'QR template updated.');
  }

  public function destroy(int $qrTemplate)
  {
    DB::table('qr_templates')->where('id', $qrTemplate)->delete();

    return back()->with('success', 'QR template deleted.');
  }

  public function regeneratePreviews(QrGeneratorService $qrGenerator)
  {
    $ids = DB::table('qr_templates')->orderBy('id')->pluck('id');
    $done = 0;
    foreach ($ids as $id) {
      if ($qrGenerator->generateTemplatePreview((int) $id)) {
        $done++;
      }
    }

    return back()->with('success', "Preview images generated for {$done} of ".$ids->count().' template(s).');
  }

  /** @return array<string, mixed> */
  private function defaultConfig(): array
  {
    return [
      'pattern' => 'square',
      'eyes' => 'square',
      'frame' => ['type' => 'none', 'text' => '', 'color' => '#000000', 'text_color' => '#000000', 'text_size' => 14, 'bg_enabled' => false, 'bg_color' => '#FFFFFF'],
      'colors' => ['foreground' => '#000000', 'background' => '#FFFFFF'],
      'logo' => ['enabled' => false, 'size' => 0.2, 'center_only' => false],
    ];
  }

  /** @return array{name:string,description:?string,has_text:int,config:array<string,mixed>} */
  private function validated(Request $request): array
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'pattern' => 'nullable|string',
      'eyes' => 'nullable|string',
      'foreground_color' => 'nullable|string',
      'background_color' => 'nullable|string',
      'frame_type' => 'nullable|string',
      'frame_text' => 'nullable|string',
      'frame_color' => 'nullable|string',
      'frame_text_color' => 'nullable|string',
      'frame_text_size' => 'nullable|integer|min:10|max:48',
      'frame_bg_color' => 'nullable|string',
      'logo_size' => 'nullable|numeric|min:0.1|max:0.3',
    ]);

    $config = $this->defaultConfig();
    $config['pattern'] = $request->input('pattern', 'square');
    $config['eyes'] = $request->input('eyes', 'square');
    $config['colors']['foreground'] = $request->input('foreground_color', '#000000');
    $config['colors']['background'] = $request->input('background_color', '#FFFFFF');
    $config['frame']['type'] = $request->input('frame_type', 'none');
    $config['frame']['text'] = $request->input('frame_text', '');
    $config['frame']['color'] = $request->input('frame_color', '#000000');
    $config['frame']['text_color'] = $request->input('frame_text_color', '#FFFFFF');
    $config['frame']['text_size'] = (int) $request->input('frame_text_size', 14);
    $config['frame']['bg_enabled'] = $request->boolean('frame_bg_enabled');
    $config['frame']['bg_color'] = $request->input('frame_bg_color', '#000000');
    $config['logo']['enabled'] = $request->boolean('logo_enabled');
    $config['logo']['size'] = (float) $request->input('logo_size', 0.2);
    $config['logo']['center_only'] = $request->boolean('logo_center_only', true);

    return [
      'name' => $request->input('name'),
      'description' => $request->input('description'),
      'has_text' => $config['frame']['text'] !== '' ? 1 : 0,
      'config' => $config,
    ];
  }
}
