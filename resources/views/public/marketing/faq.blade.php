@extends('layouts.marketing')

@section('title', 'FAQ')

@section('content')
<section class="section" style="max-width: 720px;">
    <h1 class="section-title">Frequently asked questions</h1>
    <div class="faq-container">
        <div class="faq-item active">
            <div class="faq-question">What is Resmenu?</div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>Resmenu is a digital menu platform for restaurants. Create a beautiful online menu, share it via QR code, and optionally accept orders and table reservations.</p>
                </div>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">How do I get started?</div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p><a href="{{ route('register') }}">Register</a> for a free trial, add your menu items, pick a template, and share your menu link or QR code with guests.</p>
                </div>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Which plans include ordering and reservations?</div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>Food ordering is available on Professional and Enterprise plans. Table reservations are included on Enterprise.</p>
                </div>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Need help?</div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>Visit our <a href="{{ route('public.contact') }}">contact page</a> or email {{ $settings['contact_support_email'] ?? 'support@resmenu.net' }}.</p>
                </div>
            </div>
        </div>
    </div>
    <p style="margin-top: 32px;"><a href="{{ route('login') }}">← Back to login</a></p>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.faq-question').forEach(function (q) {
    q.addEventListener('click', function () {
        const item = q.closest('.faq-item');
        if (item) item.classList.toggle('active');
    });
});
</script>
@endpush
