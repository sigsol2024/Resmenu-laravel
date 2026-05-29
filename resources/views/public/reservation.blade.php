<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reserve a table — {{ $restaurant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: {{ $customization['primary_color'] ?? '#111827' }}; }
        .time-slot.selected { background: var(--primary); color: #fff; border-color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-4">
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6">
    <h1 class="text-xl font-bold mb-2">Table reservation</h1>
    <p class="text-gray-600 text-sm mb-6">{{ $restaurant->name }}</p>
    @if(session('success'))<p class="text-green-700 mb-4">{{ session('success') }}</p>@endif
    @if($errors->any())<div class="text-red-600 text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif

    <form method="post" action="{{ route('public.reservation', $restaurant->slug) }}" class="space-y-4" id="reservation-form">
        @csrf
        <div>
            <label class="text-sm font-medium block mb-1">Date</label>
            <input type="date" name="reservation_date" id="reservation_date" value="{{ old('reservation_date') }}" required
                   min="{{ date('Y-m-d') }}" class="w-full border rounded px-3 py-2">
            <p id="day-hint" class="text-xs text-gray-500 mt-1"></p>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">Time</label>
            <input type="hidden" name="reservation_time" id="reservation_time" value="{{ old('reservation_time') }}" required>
            <div id="time-slots" class="grid grid-cols-3 gap-2 min-h-[48px]">
                <p class="col-span-3 text-sm text-gray-500">Select a date to see available times.</p>
            </div>
        </div>
        <div><label class="text-sm font-medium">Name</label><input name="guest_name" value="{{ old('guest_name') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Email</label><input type="email" name="guest_email" value="{{ old('guest_email') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Phone</label><input name="guest_phone" value="{{ old('guest_phone') }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Party size</label><input type="number" name="party_size" min="1" max="50" value="{{ old('party_size', 2) }}" required class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm font-medium">Notes</label><textarea name="notes" class="w-full border rounded px-3 py-2" rows="2">{{ old('notes') }}</textarea></div>
        <button type="submit" class="w-full py-2 text-white rounded font-semibold" style="background:var(--primary)">Request reservation</button>
    </form>
    <p class="mt-4 text-center"><a href="{{ route('public.menu', $restaurant->slug) }}" class="text-sm text-gray-600 underline">← Back to menu</a></p>
</div>
<script>
(function() {
    const slug = @json($restaurant->slug);
    const apiBase = @json(url('/api'));
    const dateInput = document.getElementById('reservation_date');
    const timeInput = document.getElementById('reservation_time');
    const slotsEl = document.getElementById('time-slots');
    const dayHint = document.getElementById('day-hint');

    function loadSlots(date) {
        slotsEl.innerHTML = '<p class="col-span-3 text-sm text-gray-500 py-2">Loading times…</p>';
        fetch(apiBase + '/reservations/slots?slug=' + encodeURIComponent(slug) + '&date=' + encodeURIComponent(date))
            .then(r => r.json())
            .then(function(res) {
                const data = res.data || res;
                const slots = data.slots || [];
                dayHint.textContent = data.day_available === false ? 'No tables available this day.' : (data.tables_left != null ? data.tables_left + ' table(s) left' : '');
                if (!res.success && res.message) {
                    slotsEl.innerHTML = '<p class="col-span-3 text-sm text-red-600">' + res.message + '</p>';
                    return;
                }
                if (!slots.length) {
                    slotsEl.innerHTML = '<p class="col-span-3 text-sm text-gray-500">No slots.</p>';
                    return;
                }
                slotsEl.innerHTML = '';
                slots.forEach(function(slot) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = slot.time;
                    btn.className = 'time-slot py-2 px-1 text-sm font-medium rounded border border-gray-200';
                    if (!slot.available) {
                        btn.disabled = true;
                        btn.className += ' opacity-40 line-through cursor-not-allowed';
                    } else {
                        btn.addEventListener('click', function() {
                            slotsEl.querySelectorAll('.time-slot').forEach(b => b.classList.remove('selected'));
                            btn.classList.add('selected');
                            timeInput.value = slot.time;
                        });
                        if (timeInput.value === slot.time) btn.classList.add('selected');
                    }
                    slotsEl.appendChild(btn);
                });
            })
            .catch(function() {
                slotsEl.innerHTML = '<p class="col-span-3 text-sm text-red-600">Could not load time slots.</p>';
            });
    }

    dateInput.addEventListener('change', function() {
        timeInput.value = '';
        if (dateInput.value) loadSlots(dateInput.value);
    });

    if (dateInput.value) loadSlots(dateInput.value);
})();
</script>
</body>
</html>
