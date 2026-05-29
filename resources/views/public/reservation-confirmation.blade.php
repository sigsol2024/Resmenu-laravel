@extends('layouts.auth-marketing')
@section('title', 'Reservation confirmed')
@section('content')
<div style="max-width:560px;margin:40px auto;padding:24px;text-align:center">
  <h1>Reservation received</h1>
  @if($restaurant)<p>{{ $restaurant->name }}</p>@endif
  <p>{{ $reservation->guest_name }} · {{ $reservation->party_size }} guests</p>
  <p>{{ $reservation->reservation_date?->format('M d, Y') }} at {{ $reservation->reservation_time }}</p>
  <p>Status: {{ $reservation->status }}</p>
</div>
@endsection
