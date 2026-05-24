<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QrTemplateController extends Controller
{
    public function index()
    {
        return view('admin.qr-templates.index', [
            'templates' => DB::table('qr_templates')->orderBy('id')->get(),
        ]);
    }
}
