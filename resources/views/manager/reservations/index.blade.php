@extends('layouts.manager')
@section('title', 'Reservations')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h1 style="margin:0;">Reservations</h1>
    <a href="{{ route('manager.reservations.list') }}" class="btn btn-primary">View all</a>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;margin-bottom:24px;">
    <div class="card"><strong>{{ $todayCount }}</strong><br><span style="color:#6b7280;font-size:0.8rem;">Today</span></div>
    @foreach($stats as $status => $count)
        <div class="card"><strong>{{ $count }}</strong><br><span style="color:#6b7280;font-size:0.8rem;">{{ ucfirst($status) }}</span></div>
    @endforeach
</div>
<div class="card">
    <h2 style="margin:0 0 16px;font-size:1rem;">Recent reservations</h2>
    <table>
        <thead><tr><th>Guest</th><th>Date</th><th>Time</th><th>Party</th><th>Status</th></tr></thead>
        <tbody>
        @forelse($recent as $r)
            <tr>
                <td>{{ $r->guest_name }}</td>
                <td>{{ $r->reservation_date?->format('M j, Y') }}</td>
                <td>{{ \Illuminate\Support\Str::of($r->reservation_time)->limit(5,'') }}</td>
                <td>{{ $r->party_size }}</td>
                <td>{{ $r->status }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No reservations yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
