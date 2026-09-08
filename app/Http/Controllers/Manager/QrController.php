<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\QrAnalyticsService;
use App\Services\RestaurantQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class QrController extends Controller
{
    public function __construct(
        private QrAnalyticsService $qr,
        private RestaurantQrService $restaurantQr,
    ) {}

    public function code(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);

        if ($request->isMethod('post')) {
            $data = $request->validate(['qr_template_id' => 'required|integer|exists:qr_templates,id']);
            $templateId = (int) $data['qr_template_id'];

            $active = DB::table('qr_templates')
                ->where('id', $templateId)
                ->where('is_active', 1)
                ->exists();

            if (! $active) {
                return back()->withErrors(['qr_template_id' => 'That template is not available. Please choose another.']);
            }

            if ($this->restaurantQr->selectTemplate($restaurantId, $templateId)) {
                return redirect()
                    ->route('manager.qr.code')
                    ->with('success', 'Template selected successfully! You can now download your QR code.');
            }

            return back()->withErrors(['qr_template_id' => 'Could not save template. Please try again or contact support.']);
        }

        $settings = $this->restaurantQr->settings($restaurantId);
        $sections = collect();
        if (Schema::hasTable('sections')) {
            try {
                $sections = DB::table('sections')
                    ->where('restaurant_id', $restaurantId)
                    ->where('is_active', 1)
                    ->orderBy('display_order')
                    ->get(['id', 'name', 'slug']);
            } catch (Throwable $e) {
                report($e);
            }
        }

        $templates = $this->restaurantQr->activeTemplates();
        $selectedTemplateId = (int) ($settings->qr_template_id ?? 0);
        $selectedTemplate = $selectedTemplateId > 0
            ? collect($templates)->first(fn ($t) => (int) $t->id === $selectedTemplateId)
            : null;

        // Stale pointer (deleted/inactive template) — clear so the UI asks for a real selection.
        if ($selectedTemplateId > 0 && $selectedTemplate === null) {
            try {
                DB::table('restaurant_qr_codes')
                    ->where('restaurant_id', $restaurantId)
                    ->update(['qr_template_id' => null, 'updated_at' => now()]);
            } catch (Throwable $e) {
                report($e);
            }
            $selectedTemplateId = 0;
        }

        $hasTemplate = $selectedTemplate !== null;
        $imageUrl = $hasTemplate ? route('manager.qr.image', ['format' => 'png', 'size' => 250]) : null;
        $analytics = $this->qr->summary($restaurantId);

        return view('manager.qr.code', [
            'restaurant' => $restaurant,
            'restaurantId' => $restaurantId,
            'menuUrl' => url('/restaurant/'.$restaurant->slug),
            'qrUrl' => url('/qr/'.$restaurant->slug),
            'templates' => $templates,
            'selectedTemplate' => $selectedTemplate,
            'qrSettings' => $settings,
            'sections' => $sections,
            'imageUrl' => $imageUrl,
            'totalScans' => (int) ($analytics['total_scans'] ?? 0),
            'templatePreviewUrl' => url('/api/qr-template-preview-image'),
        ]);
    }

    public function analytics(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $summary = $this->qr->summary($restaurantId);

        return view('manager.qr.analytics', [
            'restaurant' => Restaurant::findOrFail($restaurantId),
            'analytics' => $summary,
            'recentScans' => $summary['recent_scans'] ?? [],
            'exportUrl' => route('manager.qr.analytics.export'),
        ]);
    }

    public function exportCsv(Request $request)
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        $summary = $this->qr->summary($restaurantId);
        $scans = $summary['recent_scans'] ?? [];

        $filename = 'qr-scans-'.$restaurantId.'-'.date('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->stream(function () use ($scans) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['scanned_at', 'device_type', 'browser', 'os', 'ip_address']);
            foreach ($scans as $row) {
                fputcsv($out, [
                    $row->scanned_at ?? $row['scanned_at'] ?? '',
                    $row->device_type ?? $row['device_type'] ?? '',
                    $row->browser ?? $row['browser'] ?? '',
                    $row->os ?? $row['os'] ?? '',
                    $row->ip_address ?? $row['ip_address'] ?? '',
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }
}
