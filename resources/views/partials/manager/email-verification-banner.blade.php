@php
    $manager = auth('manager')->user();
@endphp
@if($manager && empty($manager->email_verified_at))
<div class="message" style="background:#fff7ed;border:1px solid #fdba74;color:#9a3412;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.75rem;">
    <div>
        <strong>Verify your email</strong> to unlock full menu management.
        Check your inbox for the link, or resend if it expired.
    </div>
    <form method="post" action="{{ route('manager.verification.resend') }}" style="margin:0;">
        @csrf
        <button type="submit" class="btn btn-primary" style="white-space:nowrap;">Resend verification email</button>
    </form>
</div>
@endif
