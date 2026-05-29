@extends('layouts.admin')

@section('title', 'Payment Gateway Settings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Payment Gateway Settings</h1>
    <p class="page-subtitle">Configure your payment gateway API credentials</p>
</div>

<div class="info-box">
    <div class="info-box-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Important Information
    </div>
    <div class="info-box-content">
        <ul>
            <li><strong>Test Mode:</strong> Use test keys during development. No real charges will be made.</li>
            <li><strong>Live Mode:</strong> Use live keys for production. Real payments will be processed.</li>
            <li><strong>Secret Keys:</strong> Never share your secret keys. They are encrypted before storage.</li>
            <li><strong>Webhook URLs:</strong> Copy each URL and add it to your Paystack/Flutterwave dashboard to receive subscription payment notifications.</li>
        </ul>
    </div>
</div>

<div class="tabs-container">
    <div class="tabs-nav">
        <button type="button" class="tab-button active" onclick="switchPaymentTab('paystack', this)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Paystack
        </button>
        <button type="button" class="tab-button" onclick="switchPaymentTab('flutterwave', this)">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Flutterwave
        </button>
    </div>

    <div id="tab-paystack" class="tab-content active">
        @include('partials.admin.payment-gateway-form', [
            'gateway' => 'paystack',
            'label' => 'Paystack',
            'settings' => $paystack,
            'webhookUrl' => $paystackWebhookUrl,
        ])
    </div>

    <div id="tab-flutterwave" class="tab-content">
        @include('partials.admin.payment-gateway-form', [
            'gateway' => 'flutterwave',
            'label' => 'Flutterwave',
            'settings' => $flutterwave,
            'webhookUrl' => $flutterwaveWebhookUrl,
        ])
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-payment-settings.css') }}">
@endpush

@push('scripts')
<script>
function switchPaymentTab(tabName, button) {
    document.querySelectorAll('.tabs-container .tab-content').forEach(function (tab) {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tabs-container .tab-button').forEach(function (btn) {
        btn.classList.remove('active');
    });
    document.getElementById('tab-' + tabName).classList.add('active');
    if (button) button.classList.add('active');
}

function toggleTestCredentials(gateway, isChecked) {
    var section = document.getElementById(gateway + '_test_credentials');
    if (section) section.style.display = isChecked ? 'block' : 'none';
}

function copyWebhookUrl(inputId, button) {
    var input = document.getElementById(inputId);
    if (!input) return;
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function () {
        button.textContent = 'Copied!';
        button.classList.add('copied');
        setTimeout(function () {
            button.textContent = 'Copy URL';
            button.classList.remove('copied');
        }, 2000);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    ['paystack', 'flutterwave'].forEach(function (gateway) {
        var toggle = document.getElementById(gateway + '_test_mode');
        if (toggle) toggleTestCredentials(gateway, toggle.checked);
    });
});
</script>
@endpush
