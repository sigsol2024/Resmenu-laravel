@extends('layouts.manager')

@section('title', 'Bank Transfers')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Pending bank transfers</h1>
    <p class="text-gray-600 mb-6">Review customer bank transfer claims and approve or reject them.</p>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    @if($drafts->isEmpty())
        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center text-gray-600">
            No pending bank transfers.
        </div>
    @else
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Reference</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Type</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Amount</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($drafts as $draft)
                        @php
                            $ref = 'BT-'.strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $draft->token), 0, 8));
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-mono">#{{ $ref }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $draft->customer_name }}</div>
                                <div class="text-gray-500">{{ $draft->customer_email }}</div>
                            </td>
                            <td class="px-4 py-3 capitalize">{{ $draft->payment_type ?? 'order' }}</td>
                            <td class="px-4 py-3 font-semibold">₦{{ number_format((float) $draft->total, 2) }}</td>
                            <td class="px-4 py-3">
                                @if(($draft->status ?? 'pending') === 'customer_claimed')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Customer claimed</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <form method="post" action="{{ route('manager.bank-transfers.approve', $draft->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded bg-green-600 text-white text-xs font-semibold hover:bg-green-700">Approve</button>
                                </form>
                                <form method="post" action="{{ route('manager.bank-transfers.reject', $draft->id) }}" class="inline" onsubmit="return confirm('Reject this bank transfer?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded bg-red-600 text-white text-xs font-semibold hover:bg-red-700">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
