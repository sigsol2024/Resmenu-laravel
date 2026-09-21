@extends('layouts.admin')

@section('title', 'CRM Leads')

@section('content')
<div class="page-header">
    <h1 class="page-title">CRM Leads &amp; Sync</h1>
    <p class="page-subtitle">Registration, newsletter, popup, and contact-form leads. Retry failed syncs.</p>
</div>

@include('partials.admin.flash-messages')

<p style="margin-bottom:1rem;"><a href="{{ route('admin.crm.index') }}">← CRM settings</a></p>

<div class="table-responsive">
    <table class="data-table">
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
                    <td>{{ $lead->sync_status }}</td>
                    <td style="max-width:240px;overflow:hidden;text-overflow:ellipsis;">{{ $lead->sync_error }}</td>
                    <td>
                        @if(in_array($lead->sync_status, ['failed', 'pending', 'skipped'], true))
                            <form method="post" action="{{ route('admin.crm.leads.retry', $lead->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm">Retry</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No leads yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($leads, 'links'))
    <div style="margin-top:1rem;">{{ $leads->links() }}</div>
@endif

<h3 style="margin:2rem 0 0.75rem;">Recent sync logs</h3>
<div class="table-responsive">
    <table class="data-table">
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
                <tr><td colspan="6">No sync logs.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
