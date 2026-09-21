@extends('layouts.admin')

@section('title', 'CRM Leads')

@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/admin-crm.css') }}?v=2">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">CRM Leads &amp; Sync</h1>
    <p class="page-subtitle">Registration, newsletter, popup, and contact-form leads. Retry failed syncs without re-subscribing.</p>
</div>

@include('partials.admin.flash-messages')

<div class="crm-link-row">
    <a href="{{ route('admin.crm.index') }}">← CRM settings</a>
</div>

<div class="crm-table-card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Source</th>
                    <th>Consent</th>
                    <th>Sync</th>
                    <th>Error</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td>{{ $lead->id }}</td>
                        <td>{{ $lead->email }}</td>
                        <td>{{ $lead->source }}</td>
                        <td>{{ $lead->marketing_consent ? 'Yes' : 'No' }}</td>
                        <td>
                            <span class="crm-sync-pill {{ $lead->sync_status }}">{{ $lead->sync_status }}</span>
                        </td>
                        <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $lead->sync_error }}">{{ $lead->sync_error }}</td>
                        <td>
                            @if(in_array($lead->sync_status, ['failed', 'pending', 'skipped'], true))
                                <form method="post" action="{{ route('admin.crm.leads.retry', $lead->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-retry">Retry</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="crm-empty">No leads yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(method_exists($leads, 'links'))
    <div style="margin-bottom:1.5rem;">{{ $leads->links() }}</div>
@endif

<div class="page-header" style="margin-top:8px;">
    <h2 class="page-title" style="font-size:1.125rem;">Recent sync logs</h2>
</div>

<div class="crm-table-card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>When</th>
                    <th>Provider</th>
                    <th>Action</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->provider }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->email }}</td>
                        <td>{{ $log->status }}</td>
                        <td>{{ $log->message }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="crm-empty">No sync logs.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
