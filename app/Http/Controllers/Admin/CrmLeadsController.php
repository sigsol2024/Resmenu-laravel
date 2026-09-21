<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CRM\ContactLeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CrmLeadsController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('crm_leads')) {
            return view('admin.crm.leads', [
                'leads' => collect(),
                'logs' => collect(),
            ]);
        }

        $query = DB::table('crm_leads')->orderByDesc('id');
        if ($status = $request->query('status')) {
            $query->where('sync_status', $status);
        }
        if ($source = $request->query('source')) {
            $query->where('source', $source);
        }

        $leads = $query->paginate(30)->withQueryString();

        $logs = Schema::hasTable('crm_sync_logs')
            ? DB::table('crm_sync_logs')->orderByDesc('id')->limit(40)->get()
            : collect();

        return view('admin.crm.leads', compact('leads', 'logs'));
    }

    public function retry(int $lead, ContactLeadService $leads)
    {
        $result = $leads->retryLead($lead);

        return back()->with(
            ($result['success'] ?? false) ? 'success' : 'error',
            $result['message'] ?? (($result['success'] ?? false) ? 'Synced' : 'Retry failed')
        );
    }
}
