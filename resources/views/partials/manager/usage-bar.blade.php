@props(['label', 'usage'])

@php
    if ($usage['unlimited'] ?? false) {
        $percentage = 0;
        $display = 'Unlimited';
    } else {
        $percentage = ($usage['used'] / max(1, (int) $usage['limit'])) * 100;
        $display = "{$usage['used']} / {$usage['limit']}";
    }
    $colorClass = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success');
@endphp

<div class="usage-item">
    <div class="usage-header">
        <span class="usage-label">{{ $label }}</span>
        <span class="usage-value">{{ $display }}</span>
    </div>
    @if(!($usage['unlimited'] ?? false))
        <div class="usage-bar">
            <div class="usage-fill {{ $colorClass }}" style="width: {{ min(100, $percentage) }}%"></div>
        </div>
    @endif
</div>
