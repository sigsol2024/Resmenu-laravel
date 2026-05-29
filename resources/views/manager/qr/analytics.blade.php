@extends('layouts.manager')

@section('title', 'QR analytics')

@section('content')
<h1 class="page-title" style="margin-bottom:20px;">QR analytics</h1>
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
    <table>
        <thead><tr><th>When</th><th>Device</th><th>Section</th></tr></thead>
        <tbody>
        @forelse($recentScans ?? [] as $scan)
            <tr>
                <td>{{ $scan->scanned_at ?? '—' }}</td>
                <td>{{ $scan->device_type ?? '—' }} / {{ $scan->browser ?? '' }}</td>
                <td>{{ $scan->os ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No scans recorded yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<p style="margin-top:16px"><a href="{{ route('manager.qr.code') }}">← QR code</a></p>
@endsection
