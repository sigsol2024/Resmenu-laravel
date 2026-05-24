@extends('layouts.manager')

@section('title', 'Transactions')

@section('content')
<h1 class="text-2xl font-bold mb-6">Transaction history</h1>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="p-3 text-left">Date</th><th class="p-3 text-left">Amount</th><th class="p-3 text-left">Status</th></tr></thead>
        <tbody>
        @forelse($transactions as $tx)
            <tr class="border-t"><td class="p-3">{{ $tx->created_at }}</td><td class="p-3">₦{{ number_format($tx->amount ?? 0, 0) }}</td><td class="p-3">{{ $tx->status ?? '-' }}</td></tr>
        @empty
            <tr><td colspan="3" class="p-6 text-center text-gray-500">No transactions yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
