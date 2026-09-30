@extends('layouts.manager')
@section('title', 'All reservations')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-restaurant-reservations.css') }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">All Reservations</h1>
    <p class="page-subtitle">Full reservation list with filters for {{ $restaurant->name ?? 'your restaurant' }}</p>
</div>
<p style="margin-bottom:20px;">
    <a href="{{ route('manager.reservations.index') }}" class="btn btn-secondary">Back to Reservations Overview</a>
</p>
<div class="card" style="margin-bottom:20px;">
<form method="get" style="display:flex;gap:12px;align-items:end;">
    <div class="form-group" style="margin:0;">
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="all">All</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" @selected(($filters['status'] ?? 'all') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>
</div>
<div class="card">
<div class="table-responsive">
<table class="table">
    <thead><tr><th>Guest</th><th>Date</th><th>Time</th><th>Party</th><th>Deposit</th><th>Status</th><th class="text-right">Change status</th></tr></thead>
    <tbody>
    @forelse($reservations as $r)
        <tr>
            <td>{{ $r->guest_name }}@if($r->guest_phone)<span class="cell-muted">{{ $r->guest_phone }}</span>@endif</td>
            <td>{{ $r->reservation_date?->format('M j, Y') }}</td>
            <td>{{ \Illuminate\Support\Str::of($r->reservation_time)->limit(5,'') }}</td>
            <td>{{ $r->party_size }}</td>
            <td>@include('manager.reservations.partials.deposit-cell', ['r' => $r])</td>
            <td><span class="status-pill status-pill--{{ $r->status }}">{{ $r->status }}</span></td>
            <td class="text-right">
                <form method="post" action="{{ route('manager.reservations.status', $r) }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="return_to" value="list">
                    <select name="status" onchange="this.form.submit()" style="max-width:140px;padding:6px 8px;border:1px solid #d1d5db;border-radius:8px;">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected($r->status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="table-empty">No reservations found.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
{{ $reservations->links() }}
</div>
@endsection
