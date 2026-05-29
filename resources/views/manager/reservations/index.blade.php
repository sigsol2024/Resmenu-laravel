@extends('layouts.manager')
@section('title', 'Reservations')
@section('content')
@if(!empty($showUpgradeOverlay))
<div class="card" style="margin-bottom:24px;border:2px solid #f59e0b;background:#fffbeb;">
    <h2 style="margin:0 0 8px;">Upgrade required</h2>
    <p style="margin:0 0 12px;color:#4b5563;">{{ $upgradeMessage }}</p>
    <a href="{{ route('manager.billing') }}" class="btn btn-primary">View plans</a>
</div>
<div style="filter:blur(3px);opacity:0.45;pointer-events:none;">
@endif
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h1 style="margin:0;">Reservations</h1>
    <a href="{{ route('manager.reservations.list') }}" class="btn btn-primary">View all</a>
</div>
@if(empty($showUpgradeOverlay))
<div class="card" style="margin-bottom:24px;">
    <h2 style="margin:0 0 12px;font-size:1rem;">Reservation deposit</h2>
    <form method="post" action="{{ route('manager.reservations.deposit') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
        @csrf
        <div>
            <label class="text-sm">Amount (₦)</label>
            <input type="number" name="deposit_amount" min="0" step="0.01" value="{{ $depositAmount }}" class="form-input" style="width:140px">
        </div>
        <button type="submit" class="btn btn-primary">Save deposit</button>
    </form>
    <p style="font-size:0.8rem;color:#6b7280;margin:8px 0 0">Guests pay this deposit when booking (0 = free request).</p>
</div>
@endif
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
@if(!empty($showUpgradeOverlay))</div>@endif
@endsection
