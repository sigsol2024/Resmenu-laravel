<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QrGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrTemplateController extends Controller
{
  public function index()
  {
    try {
      $templates = DB::table('qr_templates')
        ->select('qr_templates.*', DB::raw('(SELECT COUNT(*) FROM restaurant_qr_codes WHERE qr_template_id = qr_templates.id) as usage_count'))
        ->orderBy('qr_templates.id')
        ->get();
    } catch (\Throwable) {
      $templates = collect();
    }

    return view('admin.qr-templates.index', [
      'templates' => $templates,
    ]);
  }

  public function create()
  {
    return view('admin.qr-templates.form', ['template' => null, 'config' => $this->defaultConfig()]);
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
    $row = DB::table('qr_templates')->where('id', $qrTemplate)->first();
    if (! $row) {
      abort(404);
    }

    return view('admin.qr-templates.form', [
      'template' => $row,
      'config' => json_decode($row->config_json, true) ?: $this->defaultConfig(),
    ]);
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
    ]);

    $config = $this->defaultConfig();
    $config['pattern'] = $request->input('pattern', 'square');
    $config['eyes'] = $request->input('eyes', 'square');
    $config['colors']['foreground'] = $request->input('foreground_color', '#000000');
    $config['colors']['background'] = $request->input('background_color', '#FFFFFF');
    $config['frame']['type'] = $request->input('frame_type', 'none');
    $config['frame']['text'] = $request->input('frame_text', '');
    $config['logo']['enabled'] = $request->boolean('logo_enabled');

    return [
      'name' => $request->input('name'),
      'description' => $request->input('description'),
      'has_text' => $config['frame']['text'] !== '' ? 1 : 0,
      'config' => $config,
    ];
  }
}
