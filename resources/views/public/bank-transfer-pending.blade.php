<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bank transfer pending</title>
  <link rel="stylesheet" href="{{ asset('assets/css/marketing.css') }}">
</head>
<body>
  <main style="max-width:560px;margin:40px auto;padding:24px">
    <h1>Complete your bank transfer</h1>
    @if($restaurant)<p><strong>{{ $restaurant->name }}</strong></p>@endif
    <p>Amount: <strong>₦{{ number_format((float) ($draft->total ?? 0), 2) }}</strong></p>
    <p>Reference: <code>{{ $token }}</code></p>
    <p style="color:#6b7280;font-size:0.9rem;">You have 15 minutes to complete payment. After transferring, click the button below to confirm.</p>
    <button type="button" id="confirm-btn" class="btn" style="margin-top:16px;padding:12px 24px;background:#ea580c;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:600;">
      I have made this payment
    </button>
    <p id="status" style="margin-top:12px;color:#b91c1c;"></p>
  </main>
  <script>
    document.getElementById('confirm-btn').addEventListener('click', async function () {
      const btn = this;
      const status = document.getElementById('status');
      btn.disabled = true;
      status.textContent = 'Confirming…';
      try {
        const res = await fetch('{{ url('/api/bank-transfer/confirm') }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: JSON.stringify({ token: @json($token) }),
        });
        const data = await res.json();
        if (data.success && data.data && data.data.redirect) {
          window.location.href = data.data.redirect;
          return;
        }
        status.textContent = data.message || 'Could not confirm payment.';
        btn.disabled = false;
      } catch (e) {
        status.textContent = 'Network error. Please try again.';
        btn.disabled = false;
      }
    });
  </script>
</body>
</html>
