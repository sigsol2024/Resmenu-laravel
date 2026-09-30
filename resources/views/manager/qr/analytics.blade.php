@extends('layouts.manager')

@section('title', 'QR analytics')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-qr-analytics.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">QR Code Analytics</h1>
    <p class="page-subtitle">View scan statistics, device types, and trends for your QR code</p>
</div>
@if(!empty($exportUrl))
<p style="margin-bottom:16px"><a href="{{ $exportUrl }}" class="btn btn-secondary">Export CSV</a></p>
@endif
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:24px;">
    <div class="card"><strong>{{ $analytics['total_scans'] ?? 0 }}</strong><br><span style="color:#6b7280;font-size:0.8rem;">Total scans</span></div>
    @foreach($analytics['scans_by_device'] ?? [] as $device => $count)
    <div class="card"><strong>{{ $count }}</strong><br><span style="color:#6b7280;font-size:0.8rem;">{{ ucfirst($device) }}</span></div>
    @endforeach
</div>
<div class="card">
    <h2 style="margin:0 0 16px;font-size:1rem;">Recent scans</h2>
    <div class="table-responsive">
    <table class="table">
        <thead><tr><th>When</th><th>Device / browser</th><th>Operating system</th></tr></thead>
        <tbody>
        @forelse($recentScans ?? [] as $scan)
            <tr>
                <td>{{ $scan->scanned_at ?? '—' }}</td>
                <td>{{ ucfirst($scan->device_type ?? '—') }}@if(!empty($scan->browser)) / {{ $scan->browser }}@endif</td>
                <td>{{ $scan->os ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="table-empty">No scans recorded yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
<p style="margin-top:16px"><a href="{{ route('manager.qr.code') }}">← QR code</a></p>
@endsection
