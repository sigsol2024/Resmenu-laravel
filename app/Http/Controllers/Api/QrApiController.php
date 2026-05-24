<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiJsonResponse;
use Illuminate\Http\Request;

class QrApiController extends Controller
{
    public function generate(Request $request)
    {
        return ApiJsonResponse::success('QR generated', ['url' => $request->input('url')]);
    }

    public function export(Request $request)
    {
        return ApiJsonResponse::success('Export ready', []);
    }
}
