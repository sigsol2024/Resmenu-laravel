<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function index()
    {
        return view('admin.templates.index', [
            'templates' => DB::table('templates')->orderBy('display_order')->get(),
        ]);
    }

    public function toggle(int $template)
    {
        $row = DB::table('templates')->where('id', $template)->first();
        if (! $row) {
            abort(404);
        }

        DB::table('templates')->where('id', $template)->update([
            'is_active' => ! ($row->is_active ?? 1),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Template status updated.');
    }
}
